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

// Zone strings
define ("MODULE_ACCESS_DOMAIN",		"A2Billing");
define ("MODULE_ACCESS_DENIED",		"./Access_denied.htm");


define ("ACX_CUSTOMER",					1);
define ("ACX_BILLING",					2);			// 1 << 1
define ("ACX_RATECARD",					4);			// 1 << 2
define ("ACX_TRUNK",   					8);			// 1 << 3
define ("ACX_CALL_REPORT",   			16);		// 1 << 4
define ("ACX_CRONT_SERVICE",   			32);		// 1 << 5
define ("ACX_ADMINISTRATOR",   			64);		// 1 << 6
define ("ACX_MAINTENANCE",   			128);		// 1 << 7
define ("ACX_MAIL",   				    256);		// 1 << 8
define ("ACX_DID",   					512);		// 1 << 9
define ("ACX_CALLBACK",					1024);		// 1 << 10
define ("ACX_OUTBOUNDCID",				2048);		// 1 << 11
define ("ACX_PACKAGEOFFER",				4096);		// 1 << 12
define ("ACX_PREDICTIVE_DIALER",		8192);		// 1 << 13
define ("ACX_INVOICING",				16384);		// 1 << 14
define ("ACX_SUPPORT",					32768);		// 1 << 15
define ("ACX_DASHBOARD",				65536);		// 1 << 16
define ("ACX_ACXSETTING",				131072);	// 1 << 17
define ("ACX_MODIFY_REFILLS",			262144);	// 1 << 18
define ("ACX_MODIFY_PAYMENTS",			524288);	// 1 << 19
define ("ACX_MODIFY_CUSTOMERS",			1048576);	// 1 << 20
define ("ACX_DELETE_NOTIFICATIONS",		2097152);	// 1 << 21
define ("ACX_DELETE_CDR",				4194304);	// 1 << 22
define ("ACX_MODIFY_ADMINS",			8388608);	// 1 << 23
define ("ACX_MODIFY_AGENTS",			16777216);	// 1 << 24

header("Expires: Sat, Jan 01 2000 01:01:01 GMT");
//echo "PHP_AUTH_USER : $PHP_AUTH_USER";


if (isset($_GET["logout"]) && $_GET["logout"]=="true") {
	$log = new Logger();
	$log -> insertLog($_SESSION["admin_id"], 1, "USER LOGGED OUT", "User Logged out from website", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],'');
	$log = null;
	session_destroy();
	$rights=0;
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: index.php");
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

if ((!isset($_SESSION['pr_login']) || !isset($_SESSION['pr_password']) || !isset($_SESSION['rights']) || (isset($_POST["done"]) && $_POST["done"]=="submit_log") )){

	if ($FG_DEBUG == 1) echo "<br>0. HERE WE ARE";


	if ($_POST["done"]=="submit_log"){

		$DBHandle  = DbConnect();

		if ($FG_DEBUG == 1) echo "<br>1. ".$_POST["pr_login"].$_POST["pr_password"];
		$_POST["pr_login"] = access_sanitize_data($_POST["pr_login"]);
		$_POST["pr_password"] = access_sanitize_data($_POST["pr_password"]);

		$return = login ($_POST["pr_login"], $_POST["pr_password"]);

		if ($FG_DEBUG == 1) print_r($return);
		if ($FG_DEBUG == 1) echo "==>".$return[1];



		if (!is_array($return) || $return[1]==0 ) {
			header ("HTTP/1.0 401 Unauthorized");
			Header ("Location: index.php?error=1");
			die();
		}
		// if groupID egal 1, this user is a root

		if ($return[3]==0){
			$admin_id = $return[0];
			$return = true;
			$rights = 33554431;
			$is_admin = 1;
			$pr_groupID = $return[3];
		}else{
			$pr_reseller_ID = $return[0];
			$admin_id = $return[0];
			$rights = $return[1];
			if ($return[3]==1) $is_admin=1;
			else $is_admin=0;

			if ($return[3] == 3) $pr_reseller_ID = $return[4];

			$pr_groupID = $return[3];
		}

		if ($_POST["pr_login"]) {

			$pr_login = $_POST["pr_login"];
			$pr_password = $_POST["pr_password"];

			if ($FG_DEBUG == 1) echo "<br>3. $pr_login-$pr_password-$rights-$conf_addcust";
			$_SESSION["pr_login"]=$pr_login;
			$_SESSION["pr_password"]=$pr_password;
			$_SESSION["rights"]=$rights;
			$_SESSION["is_admin"]=$is_admin;
			$_SESSION["user_type"] = "ADMIN";
			$_SESSION["pr_reseller_ID"]=$pr_reseller_ID;
			$_SESSION["pr_groupID"]=$pr_groupID;
			$_SESSION["admin_id"] = $admin_id;
			$log = new Logger();
			$log -> insertLog($admin_id, 1, "User Logged In", "User Logged in to website", '', $_SERVER['REMOTE_ADDR'], 'PP_Intro.php','');
			$log = null;
		}

	} else {
		$rights=0;

	}

}


// 					FUNCTIONS
//////////////////////////////////////////////////////////////////////////////

function login ($user, $pass) {
	global $DBHandle;

	$user = trim($user);
	$pass = trim($pass);
	$pass_encoded= hash( 'whirlpool',$pass);
	if (strlen($user)==0 || strlen($user)>=50 || strlen($pass)==0 || strlen($pass)>=50) return false;
	$QUERY = "SELECT userid, perms, confaddcust, groupid FROM cc_ui_authen WHERE login = '".$user."' AND pwd_encoded = '".$pass_encoded."'";

	$res = $DBHandle -> Execute($QUERY);

	if (!$res) {
		$errstr = $DBHandle->ErrorMsg();
		return (false);
	}

	$row [] =$res -> fetchRow();
	return ($row[0]);
}


function has_rights ($condition) {
	return ($_SESSION["rights"] & $condition);
}

$ACXACCESS 				= ($_SESSION["rights"] > 0) ? true : false;
$ACXDASHBOARD			= has_rights (ACX_DASHBOARD);
$ACXCUSTOMER 			= has_rights (ACX_CUSTOMER);
$ACXBILLING 			= has_rights (ACX_BILLING);
$ACXRATECARD 			= has_rights (ACX_RATECARD);
$ACXTRUNK				= has_rights (ACX_TRUNK);
$ACXDID					= has_rights (ACX_DID);
$ACXCALLREPORT			= has_rights (ACX_CALL_REPORT);
$ACXCRONTSERVICE		= has_rights (ACX_CRONT_SERVICE);
$ACXMAIL 				= has_rights (ACX_MAIL);
$ACXADMINISTRATOR 		= has_rights (ACX_ADMINISTRATOR);
$ACXMAINTENANCE 		= has_rights (ACX_MAINTENANCE);
$ACXCALLBACK			= has_rights (ACX_CALLBACK);
$ACXOUTBOUNDCID 		= has_rights (ACX_OUTBOUNDCID);
$ACXPACKAGEOFFER 		= has_rights (ACX_PACKAGEOFFER);
$ACXPREDICTIVEDIALER 	= has_rights (ACX_PREDICTIVE_DIALER);
$ACXINVOICING 			= has_rights (ACX_INVOICING);
$ACXSUPPORT 			= has_rights (ACX_SUPPORT);
$ACXSETTING 			= has_rights (ACX_ACXSETTING);
$ACXMODIFY_REFILLS 		= has_rights (ACX_MODIFY_REFILLS);
$ACXMODIFY_PAYMENTS 	= has_rights (ACX_MODIFY_PAYMENTS);
$ACXMODIFY_CUSTOMERS 	= has_rights (ACX_MODIFY_CUSTOMERS);
$ACXDELETE_NOTIFICATIONS= has_rights (ACX_DELETE_NOTIFICATIONS);
$ACXDELETE_CDR			= has_rights (ACX_DELETE_CDR);


if(isset($_SESSION["admin_id"]))$NEW_NOTIFICATION = NotificationsDAO::IfNewNotification($_SESSION["admin_id"]);



