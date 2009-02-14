<?php

/*
 * file module.access.php
 *

	Module access - an access control module for back office areas

	If you're using $_SESSION , make sure you aren't using session_register() too.
	From the manual.

	If you are using $_SESSION (or $HTTP_SESSION_VARS), do not use session_register(), session_is_registered() and session_unregister().

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
define ("ACX_SIPCONF",					2048);
define ("ACX_IAXCONF",					4096);

header("Expires: Sat, Jan 01 2000 01:01:01 GMT");

if (!isset($_SESSION)) {
	session_name("UIAGENTSESSION");
	session_start();
}


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

if ((!session_is_registered('pr_login') || !session_is_registered('pr_password') || !session_is_registered('rights') || (isset($_POST["done"]) && $_POST["done"]=="submit_log") )){

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
	$QUERY = "SELECT id, perms, active FROM cc_agent WHERE login = '".$user."' AND passwd = '".$pass."'";

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

$ACXSIGNUP 	= has_rights (ACX_SIGNUP);
$ACXCUSTOMER 	= has_rights (ACX_CUSTOMER);
$ACXBILLING 	= has_rights (ACX_BILLING);
$ACXRATECARD 	= has_rights (ACX_RATECARD);
$ACXCALLREPORT	= has_rights (ACX_CALL_REPORT);
$ACXMYACCOUNT  = has_rights (ACX_MYACCOUNT);
$ACXSUPPORT  = has_rights (ACX_SUPPORT);
$ACXCREATECUSTOMER  = has_rights (ACX_CREATE_CUSTOMER);
$ACXEDITCUSTOMER  = has_rights (ACX_EDIT_CUSTOMER);
$ACXDELETECUSTOMER  = has_rights (ACX_DELETE_CUSTOMER);
$ACXGENERATECUSTOMER  = has_rights (ACX_GENERATE_CUSTOMER);
$ACXSIPCONF  = has_rights (ACX_SIPCONF);
$ACXIAXCONF  = has_rights (ACX_IAXCONF);
