<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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
    Get the Customer's Balance

Parameters :
    username : Customer's Account code
    password : Customer's password
    html : to display with <pre> tag

Usage :
    http://localhost/customer/webservice/get_balance.php?username=XXXXXXXXXXX&password=XXXXXXXXXXX&html=1
*/

include '../lib/customer.defines.php';


getpost_ifset(array('username', 'password', 'html'));


$balance = Service_Get_Balance($username, $password);

if (isset($html)) echo "<pre>";
echo $balance[0];
if (isset($html)) echo "</pre>";

/*
 *		Function for the Service Callback : it will call a phonenumber and redirect it into the BCB application
 */
function Service_Get_Balance($accountnumber, $password)
{
    $DBHandle = DbConnect();
    $table_instance = new Table();

    if (!$DBHandle) {
        write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR CONNECT DB");

        return array('500', ' ERROR - CONNECT DB ');
    }

    $QUERY = "SELECT cc.username, cc.credit, cc.status, cc.id, cc.currency " .
             "FROM cc_card cc " .
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

    $balance = $row[0][1];

    return array($balance, '200 -- Rates OK');
}
