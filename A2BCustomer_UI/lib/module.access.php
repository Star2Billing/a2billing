<?php /* file module.access.php
	
	Module access - an access control module for back office areas


If you're using $_SESSION , make sure you aren't using session_register() too.
From the manual.
If you are using $_SESSION (or $HTTP_SESSION_VARS), do not use session_register(), session_is_registered() and session_unregister().


*/
$FG_DEBUG = 0;
error_reporting(E_ALL & ~E_NOTICE);

// Zone strings
define ("MODULE_ACCESS_DOMAIN",		"CallingCard System");
define ("MODULE_ACCESS_DENIED",		"./Access_denied.htm");


define ("ACX_ACCESS",					1);



header("Expires: Sat, Jan 01 2000 01:01:01 GMT");
session_name("UICSESSION");
session_start();


if (isset($_GET["logout"]) && $_GET["logout"]=="true") { 
	session_destroy();
	$cus_rights=0;
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: index.php");	   
	die();
}
	
function access_sanitize_data($data){
	$lowerdata = strtolower ($data);
	$data = str_replace('--', '', $data);	
	$data = str_replace("'", '', $data);
	$data = str_replace('=', '', $data);
	$data = str_replace(';', '', $data);
	if (!(strpos($lowerdata, ' or ')===FALSE)){ return false;}
	if (!(strpos($lowerdata, 'table')===FALSE)){ return false;}

	return $data;
}

if ((!session_is_registered('pr_login') || !session_is_registered('pr_password') || !session_is_registered('cus_rights') || (isset($_POST["done"]) && $_POST["done"]=="submit_log") )){

	if ($FG_DEBUG == 1) echo "<br>0. HERE WE ARE";

	if ($_POST["done"]=="submit_log"){
		
		$DBHandle  = DbConnect();
		
		if ($FG_DEBUG == 1) echo "<br>1. ".$_POST["pr_login"].$_POST["pr_password"];
		$_POST["pr_login"] = access_sanitize_data($_POST["pr_login"]);
		$_POST["pr_password"] = access_sanitize_data($_POST["pr_password"]);
		
		$return = login ($_POST["pr_login"], $_POST["pr_password"]);		
		if ($FG_DEBUG == 1) print_r($return);
		if ($FG_DEBUG == 1) echo "==>".$return[1];
		
		if (!is_array($return))
        {		
			sleep(2);
			header ("HTTP/1.0 401 Unauthorized");
            if(is_int($return))
            {
                if($return == -1)
                {
			        Header ("Location: index.php?error=3");
                }
                else
                {
                    Header ("Location: index.php?error=2");
                }
            }
            else
            {
                Header ("Location: index.php?error=1");
            }
			die();
		}
		
		$cus_rights = 1;
		
		if ($_POST["pr_login"]){
			$pr_login = $return[0]; //$_POST["pr_login"];
			$pr_password = $_POST["pr_password"];
			
			if ($FG_DEBUG == 1) echo "<br>3. $pr_login-$pr_password-$cus_rights";
			$_SESSION["pr_login"]=$pr_login;
			$_SESSION["pr_password"]=$pr_password;
			$_SESSION["cus_rights"]=$cus_rights;
			$_SESSION["card_id"]=$return[3];
			$_SESSION["id_didgroup"]=$return[4];
			$_SESSION["tariff"]=$return[5];
			$_SESSION["vat"]=$return[6];
			$_SESSION["gmtoffset"]=$return[8];
		}
		
	}else{
		$_SESSION["cus_rights"]=0;
	}

}


// Functions

function login ($user, $pass) {
	global $DBHandle;
	$user = trim($user);
	$pass = trim($pass);
	if (strlen($user)==0 || strlen($user)>=50 || strlen($pass)==0 || strlen($pass)>=50) return false;
	
	$QUERY = "SELECT cc.username, cc.credit, cc.status, cc.id, cc.id_didgroup, cc.tariff, cc.vat, cc.activatedbyuser, ct.gmtoffset FROM cc_card cc LEFT JOIN cc_timezone AS ct ON ct.id = cc.id_timezone WHERE (cc.email = '".$user."' OR cc.useralias = '".$user."') AND cc.uipass = '".$pass."'"; 
	$res = $DBHandle -> Execute($QUERY);
	
	if (!$res) {
		$errstr = $DBHandle->ErrorMsg();
		return (false);
	}
	
	$row [] =$res -> fetchRow();
	
	if( $row [0][2] != "t" && $row [0][2] != "1" ) {
		return -1;
	}
	
    if( ACTIVATEDBYUSER==1 && $row [0][7] != "t" && $row [0][7] != "1" ) {
		return -2;
	}
	
	return ($row[0]);
}



function has_rights ($condition) {
	return ($_SESSION['cus_rights'] & $condition);
}

?>
