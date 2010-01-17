<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/



/*
Result :
    Send the Customer's Rates using the LCR/LCD Logic
	
Parameters :
	activation_code : Concatenation of Customer's Account code + '_' + Customer's password
	html : to display with <pre> tag

Usage :
    http://localhost/trunk/customer/webservice/Get_Rates.php?activation_code=XXXXXXXXXXX
    http://localhost/~areski/svn/asterisk2billing/trunk/customer/webservice/Get_Rates.php?activation_code=6098593343_12345&html=1
*/


include ("../lib/customer.defines.php");


getpost_ifset(array('activation_code', 'html'));

if ($activation_code)
$activation_code = trim($activation_code);

$rates = Service_Get_Rates($activation_code);

if (isset($html)) echo "<pre>";
echo $rates[0];
if (isset($html)) echo "</pre>";

/*
 *		Function for the Service Callback : it will call a phonenumber and redirect it into the BCB application
 */ 
function Service_Get_Rates($activation_code)
{
	
	$DBHandle = DbConnect();
	$table_instance = new Table();
	
	if (!$DBHandle) {			
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR CONNECT DB");
		return array('500', ' ERROR - CONNECT DB ');
	}
	
	list($accountnumber, $password) = (preg_split("{_}",$activation_code,2));
	
	$QUERY = "SELECT cc.username, cc.credit, cc.status, cc.id, cc.id_didgroup, cc.tariff, cc.vat, ct.gmtoffset, cc.voicemail_permitted, " .
			 "cc.voicemail_activated, cc_card_group.users_perms, cc.currency " .
			 "FROM cc_card cc LEFT JOIN cc_timezone AS ct ON ct.id = cc.id_timezone LEFT JOIN cc_card_group ON cc_card_group.id=cc.id_group " .
			 "WHERE cc.username = '".$accountnumber."' AND cc.uipass = '".$password."'";
	$res = $DBHandle -> Execute($QUERY);
	
	if (!$res) {
		return array('400', ' ERROR - AUTHENTICATE CODE');
	}
	$row [] = $res -> fetchRow();
	$card_id = $row[0][3];
	
	if (!$card_id || $card_id < 0) {
		return array('400', ' ERROR - AUTHENTICATE CODE');
	}
	
	$QUERY = "SELECT SQL_CALC_FOUND_ROWS dialprefix, destination, MIN(rateinitial) as rateinitial FROM cc_callplan_lcr WHERE tariffgroup_id = '220' ".
	         "GROUP BY dialprefix ORDER BY destination ASC LIMIT 0,50000";
	
	$res = $DBHandle -> Execute($QUERY);
	
	$num = 0;
    if ($res)
	    $num = $res->RecordCount();

    if (!$num) {
	    return array('400', ' ERROR - NO RATES FOUND');
    }

    $rates = '';
    $arr_rates = array();
    for ($i = 0; $i < $num; $i++) {
	    $arr_rates[$i] = $res->fetchRow();
	    $rates .= '"'.$arr_rates[$i]['destination'].'","'.$arr_rates[$i]['dialprefix'].'","'.$arr_rates[$i]['rateinitial']."\"\n";
    }
	
	return array($rates, '200 -- Rates OK');
	
}



