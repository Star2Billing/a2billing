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


$FG_DEBUG = 0;
error_reporting(E_ALL & ~E_NOTICE);

define ("MODULE_ACCESS_DOMAIN",		"CallingCard System");
define ("MODULE_ACCESS_DENIED",		"./Access_denied.htm");

define ("ACX_ACCESS",						1);	
define ("ACX_PASSWORD",						2);	
define ("ACX_SIP_IAX",						4);			// 1 << 1
define ("ACX_CALL_HISTORY",					8);			// 1 << 2
define ("ACX_PAYMENT_HISTORY",   			16);		// 1 << 3
define ("ACX_VOUCHER",   					32);		// 1 << 4
define ("ACX_INVOICES",   					64);		// 1 << 5
define ("ACX_DID",   						128);		// 1 << 6
define ("ACX_SPEED_DIAL",   				256);		// 1 << 7
define ("ACX_RATECARD",   				    512);		// 1 << 8
define ("ACX_SIMULATOR",   					1024);		// 1 << 9
define ("ACX_CALL_BACK",   					2048);		// 1 << 10
define ("ACX_WEB_PHONE",					4096);		// 1 << 11
define ("ACX_CALLER_ID",					8192);		// 1 << 12
define ("ACX_SUPPORT",						16384);		// 1 << 14
define ("ACX_NOTIFICATION",					32768);		// 1 << 15
define ("ACX_AUTODIALER",					65536);		// 1 << 16
define ("ACX_PERSONALINFO",					131072);	
define ("ACX_SEERECORDING",					262144);

header("Expires: Sat, Jan 01 2000 01:01:01 GMT");


if(strlen(RETURN_URL_DISTANT_LOGIN)>1) {
	if (strpos(RETURN_URL_DISTANT_LOGIN, '?') === false)
		$C_RETURN_URL_DISTANT_LOGIN = RETURN_URL_DISTANT_LOGIN . '?';
	else
		$C_RETURN_URL_DISTANT_LOGIN = RETURN_URL_DISTANT_LOGIN . '&';
} else {
	$C_RETURN_URL_DISTANT_LOGIN = 'index.php?';
}

if (isset($_GET["logout"]) && $_GET["logout"]=="true") { 
	session_destroy();
	$cus_rights=0;
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: $C_RETURN_URL_DISTANT_LOGIN");	   
	die();
}
	
function access_sanitize_data($data)
{
	$lowerdata = strtolower ($data);
	$data = str_replace('--', '', $data);	
	$data = str_replace("'", '', $data);
	$data = str_replace('=', '', $data);
	$data = str_replace(';', '', $data);
	if (!(strpos($lowerdata, ' or ')===FALSE)){ return false;}
	if (!(strpos($lowerdata, 'table')===FALSE)){ return false;}

	return $data;
}


if ((!isset($_SESSION['pr_login']) || !isset($_SESSION['pr_password']) || !isset($_SESSION['cus_rights']) || (isset($_POST["done"]) && $_POST["done"]=="submit_log") )){

	if ($FG_DEBUG == 1) echo "<br>0. HERE WE ARE";

	if ($_POST["done"]=="submit_log") {
		
		$DBHandle  = DbConnect();
		if ($FG_DEBUG == 1) echo "<br>1. ".$_POST["pr_login"].$_POST["pr_password"];
		$_POST["pr_login"] = access_sanitize_data($_POST["pr_login"]);
		$_POST["pr_password"] = access_sanitize_data($_POST["pr_password"]);
		
		$return = login ($_POST["pr_login"], $_POST["pr_password"]);		
		if ($FG_DEBUG == 1) print_r($return);
		if ($FG_DEBUG == 1) echo "==>".$return[1];
		
		if (!is_array($return)) {
			sleep(2);
			header ("HTTP/1.0 401 Unauthorized");
            if (is_int($return)) {
                if ($return == -1) {
			        Header ("Location: $C_RETURN_URL_DISTANT_LOGIN"."error=3");
                } elseif ($return == -2) {
			        Header ("Location: $C_RETURN_URL_DISTANT_LOGIN"."error=4");
                } else {
                    Header ("Location: $C_RETURN_URL_DISTANT_LOGIN"."error=2");
                }
            } else {
                Header ("Location: $C_RETURN_URL_DISTANT_LOGIN"."error=1");
            }
			die();
		}
		
		$cust_default_right=1;
		if ($_POST["pr_login"]) {
			
			$pr_login = $return[0];
			$pr_password = $_POST["pr_password"];
			
			if ($FG_DEBUG == 1)
				echo "<br>3. $pr_login-$pr_password-$cus_rights";
			
			$_SESSION["pr_login"]=$pr_login;
			$_SESSION["pr_password"]=$pr_password;
			
			if(empty($return[10])) {
				$_SESSION["cus_rights"]=$cust_default_right;
			} else {
				$_SESSION["cus_rights"]=$return[10]+$cust_default_right;
			}
						
			$_SESSION["user_type"]		= "CUST";
			$_SESSION["card_id"]		= $return[3];
			$_SESSION["id_didgroup"]	= $return[4];
			$_SESSION["tariff"]			= $return[5];
			$_SESSION["vat"]			= $return[6];
			$_SESSION["gmtoffset"]		= $return[7];
			$_SESSION["currency"]		= $return["currency"];
			$_SESSION["voicemail"]		= $return[8];
		}
	} else {
		$_SESSION["cus_rights"]=0;
	}
}


// Functions

function login ($user, $pass)
{
	global $DBHandle;
	$user = trim($user);
	$pass = trim($pass);
	if (strlen($user)==0 || strlen($user)>=50 || strlen($pass)==0 || strlen($pass)>=50) return false;
	
	$QUERY = "SELECT cc.username, cc.credit, cc.status, cc.id, cc.id_didgroup, cc.tariff, cc.vat, ct.gmtoffset, cc.voicemail_permitted, " .
			 "cc.voicemail_activated, cc_card_group.users_perms, cc.currency " .
			 "FROM cc_card cc LEFT JOIN cc_timezone AS ct ON ct.id = cc.id_timezone LEFT JOIN cc_card_group ON cc_card_group.id=cc.id_group " .
			 "WHERE (cc.email = '".$user."' OR cc.useralias = '".$user."') AND cc.uipass = '".$pass."'";
			  
	$res = $DBHandle -> Execute($QUERY);
	
	if (!$res) {
		$errstr = $DBHandle->ErrorMsg();
		return (false);
	}
	
	$row [] =$res -> fetchRow();
	
	if( $row [0][2] != "t" && $row [0][2] != "1"  && $row [0][2] != "8" ) {
		if ($row [0][2] == "2")
			return -2;
		else
			return -1;
	}
	
	return ($row[0]);
}



function has_rights ($condition)
{
	return ($_SESSION['cus_rights'] & $condition);
}


$ACXPASSWORD 				= has_rights (ACX_PASSWORD);
$ACXSIP_IAX 				= has_rights (ACX_SIP_IAX);
$ACXCALL_HISTORY 			= has_rights (ACX_CALL_HISTORY);
$ACXPAYMENT_HISTORY			= has_rights (ACX_PAYMENT_HISTORY);
$ACXVOUCHER					= has_rights (ACX_VOUCHER);
$ACXINVOICES				= has_rights (ACX_INVOICES);
$ACXDID						= has_rights (ACX_DID);
$ACXSPEED_DIAL 				= has_rights (ACX_SPEED_DIAL);
$ACXRATECARD 				= has_rights (ACX_RATECARD);
$ACXSIMULATOR 				= has_rights (ACX_SIMULATOR);
$ACXWEB_PHONE				= has_rights (ACX_WEB_PHONE);
$ACXCALL_BACK				= has_rights (ACX_CALL_BACK);
$ACXCALLER_ID				= has_rights (ACX_CALLER_ID);
$ACXSUPPORT 				= has_rights (ACX_SUPPORT);
$ACXNOTIFICATION 			= has_rights (ACX_NOTIFICATION);
$ACXAUTODIALER 				= has_rights (ACX_AUTODIALER);
$ACXSEERECORDING 			= has_rights (ACX_SEERECORDING);
if (ACT_VOICEMAIL) {
    $ACXVOICEMAIL 				= $_SESSION["voicemail"];
}
