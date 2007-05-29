<?php

exit;

include ("../../lib/defines.php");
include ("../../lib/regular_express.inc");
require_once('SOAP/Server.php');
require_once('SOAP/Disco.php');



// 	http://domain/path/soap/soap-db-callback.php?security_key=13a7fa40cfcef6fe7ac9718a5c76cdb5&phone_number=XXXXX&callerid=123456	
// &callback_time=2006-09-20+19%3A30%3A00


//$phone_number = '34650784355';
$phone_number = $_GET['phone_number'];
$callerid = $_GET['callerid'];
$security_key = $_GET['security_key'];
$callback_time = urldecode($_GET['callback_time']);

$ans = Service_Callback($security_key, $phone_number, $callerid, $callback_time);
//print_r($ans);


/*
 *		Function for the Service Callback : it will call a phonenumber and redirect it into the BCB application
 */ 
function Service_Callback($security_key, $phone_number, $callerid, $callback_time){
	
	$uniqueid 	=  MDP_STRING(5).'-'.MDP_NUMERIC(10);
	$status = 'PENDING';
	$server_ip = 'localhost';
	$num_attempt = 0;
	$channel = 'SIP/'.$phone_number.'@mylittleIP';	
	$exten = $phone_number;
	$context = 'a2billing';
	$priority = 1;
	//$timeout	callerid
	$variable = "phonenumber=$phone_number|callerid=$callerid";
	
	$FG_regular[]  = array(    "^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$"   ,"(YYYY-MM-DD HH:MM:SS)");
	
			
	// The wrapper variables for security
	// $security_key = API_SECURITY_KEY;
	write_log(" Service_Callback( security_key=$security_key, keyword=$keyword, product_code=$product_code, companyid=$companyid, phone_number=$phone_number, callerid=$callerid, transaction_id=$transaction_id, callback_time=$callback_time)");

	$mysecurity_key = API_SECURITY_KEY;
				
	$mail_content = "[" . date("Y/m/d G:i:s", mktime()) . "] "."SOAP API - Request asked: Callback [$phone_number, callback_time=$callback_time]";
	
	
	
	// CHECK CALLERID
	if (strlen($callerid)<1)
	{
		write_log(basename(__FILE__).' line:'.__LINE__."[" . date("Y/m/d G:i:s", mktime()) . "] "." ERROR FORMAT CALLERID AT LEAST 1 DIGIT ");
		sleep(2);
		return array($keyword, 'result=Error', " ERROR - FORMAT CALLERID AT LEAST 1 DIGIT ");
	}
	
	// CHECK PHONE_NUMBER
	if (strlen($phone_number)<10)
	{
		write_log(basename(__FILE__).' line:'.__LINE__."[" . date("Y/m/d G:i:s", mktime()) . "] "." ERROR FORMAT PHONENUMBER AT LEAST 10 DIGITS ");
		sleep(2);
		return array($keyword, 'result=Error', " ERROR - FORMAT PHONENUMBER AT LEAST 10 DIGITS ");
	}
	
	// CHECK CALLBACK TIME
	if (strlen($callback_time)>1 && !(ereg( $FG_regular[0][0], $callback_time)))
	{
		write_log(basename(__FILE__).' line:'.__LINE__."[" . date("Y/m/d G:i:s", mktime()) . "] "." ERROR FORMAT CALLBACKTIME : ".$FG_regular[0][0]);
		sleep(2);
		return array($keyword, 'result=Error', " ERROR - FORMAT CALLBACKTIME : ".$FG_regular[0][0]);
	}
	
	// CHECK SECURITY KEY
	if (md5($mysecurity_key) !== $security_key  || strlen($security_key)==0)
	{
		write_log(basename(__FILE__).' line:'.__LINE__."[" . date("Y/m/d G:i:s", mktime()) . "] "." CODE_ERROR SECURITY_KEY");
		sleep(2);
		return array($keyword, 'result=Error', ' KEY - BAD PARAMETER ');
	}
	
	$DBHandle = DbConnect();
	if (!$DBHandle){			
		write_log(basename(__FILE__).' line:'.__LINE__."[" . date("Y/m/d G:i:s", mktime()) . "] "." ERROR CONNECT DB");
		sleep(2);
		return array($keyword, 'result=Error', ' ERROR - CONNECT DB ');
	}
	
	if (strlen($callback_time)>1){
		$QUERY = " INSERT INTO callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, callback_time ) ".
			 " values ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$callback_time')";
	}else{
		$QUERY = " INSERT INTO callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable ) ".
			 " values ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable')";
	}
	
	$res = $DBHandle -> Execute($QUERY);
	if (!$res){
		write_log(basename(__FILE__).' line:'.__LINE__."[" . date("Y/m/d G:i:s", mktime()) . "] "." ERROR INSERT INTO DB");
		sleep(2);
		return array($keyword, 'result=Error', ' ERROR - INSERT INTO DB');
	}
	
	return array($keyword, 'result=Success', " Success - Callback request has been accepted ");
}



?>
