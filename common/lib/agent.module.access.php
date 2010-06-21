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
define ("MODULE_ACCESS_DOMAIN",		"CallingCard System");
define ("MODULE_ACCESS_DENIED",		"./Access_denied.htm");



define ("ACX_CUSTOMER",					1);
define ("ACX_BILLING",					2);			// 1 << 1
define ("ACX_RATECARD",					4);			// 1 << 2
define ("ACX_CALL_REPORT",   			8);			// 1 << 3
define ("ACX_MYACCOUNT",				16);
define ("ACX_SUPPORT",					32);
define ("ACX_CREATE_CUSTOMER",			64);
define ("ACX_EDIT_CUSTOMER",			128);
define ("ACX_DELETE_CUSTOMER",			256);
define ("ACX_GENERATE_CUSTOMER",		512);
define ("ACX_SIGNUP",					1024);
define ("ACX_VOIPCONF",					2048);
define ("ACX_SEE_CUSTOMERS_CALLERID",	4096);


header("Expires: Sat, Jan 01 2000 01:01:01 GMT");


if (isset($_GET["logout"]) && $_GET["logout"]=="true") {
	$log = new Logger();
	$log -> insertLogAgent($admin_id, 1, "AGENT LOGGED OUT", "User Logged out from website", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],'');
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



		if (!is_array($return) ) {
			header ("HTTP/1.0 401 Unauthorized");
			Header ("Location: index.php?error=1");
			die();
		}

		$agent_id = $return[0];
		$rights = $return[1];


		if ($_POST["pr_login"]) {

			$pr_login = $_POST["pr_login"];
			$pr_password = $_POST["pr_password"];

			if ($FG_DEBUG == 1) echo "<br>3. $pr_login-$pr_password-$rights-$conf_addcust";
			$_SESSION["pr_login"]=$pr_login;
			$_SESSION["pr_password"]=$pr_password;
			$_SESSION["rights"]=$rights;
			$_SESSION["agent_id"] = $agent_id;
			$_SESSION["user_type"] = "AGENT";
			$_SESSION["currency"]=$return["currency"];
			$_SESSION["vat"]=$return["vat"];
			$log = new Logger();
			$log -> insertLogAgent($agent_id, 1, "Agent Logged In", "Agent Logged in to website", '', $_SERVER['REMOTE_ADDR'], 'PP_Intro.php','');
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
	if (strlen($user)==0 || strlen($user)>=50 || strlen($pass)==0 || strlen($pass)>=50) return false;
	$QUERY = "SELECT id, perms, active,currency,vat FROM cc_agent WHERE login = '".$user."' AND passwd = '".$pass."'";

	$res = $DBHandle -> Execute($QUERY);

	if (!$res) {
		$errstr = $DBHandle->ErrorMsg();
		return (false);
	}

	$row [] =$res -> fetchRow();

	if( $row [0][2] != "t" && $row [0][2] != "1" ) {
		return -1;
	}

	return ($row[0]);
}


function has_rights ($condition) {
	return ($_SESSION["rights"] & $condition);
}

$ACXACCESS 					= ($_SESSION["rights"] > 0) ? true : false;
$ACXSIGNUP 					= has_rights (ACX_SIGNUP);
$ACXCUSTOMER 				= has_rights (ACX_CUSTOMER);
$ACXBILLING 				= has_rights (ACX_BILLING);
$ACXRATECARD 				= has_rights (ACX_RATECARD);
$ACXCALLREPORT				= has_rights (ACX_CALL_REPORT);
$ACXMYACCOUNT  				= has_rights (ACX_MYACCOUNT);
$ACXSUPPORT  				= has_rights (ACX_SUPPORT);
$ACXCREATECUSTOMER  		= has_rights (ACX_CREATE_CUSTOMER);
$ACXEDITCUSTOMER  			= has_rights (ACX_EDIT_CUSTOMER);
$ACXDELETECUSTOMER  		= has_rights (ACX_DELETE_CUSTOMER);
$ACXGENERATECUSTOMER  		= has_rights (ACX_GENERATE_CUSTOMER);
$ACXVOIPCONF  				= has_rights (ACX_VOIPCONF);
$ACXSEE_CUSTOMERS_CALLERID	= has_rights (ACX_SEE_CUSTOMERS_CALLERID);


