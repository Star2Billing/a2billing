<?php
/*
Result :
    * 200 -- successful query
    * 400 -- malformed query
    * 500 -- error processing query or fetching data
	%transaction_id/OutboundEventSchedulerID% : Error description
	
	
Paremeters :
	security_key
	phone_number
	callerid
	callback_time *  -> callback_time=2006-09-20+19%3A30%3A00
	id_server_group
	
http://hostname/api/api_spool_callback.php?security_key=XXXX&phone_number=XXX&callerid=XXXX
*/

include ("../lib/admin.defines.php");

	
	
//$phone_number = '34650784355';
$phone_number = $_GET['phone_number']; // 01132473510969
$callerid = $_GET['callerid'];
$security_key = $_GET['security_key'];
$id_server_group = $_GET['id_server_group']; // id_server_group
$callback_time = urldecode($_GET['callback_time']);

$ans = Service_Callback($security_key, $phone_number, $callerid, $transaction_id, $callback_time, $id_server_group);
echo $ans[1]."\n";
echo $ans[0]." : ".$ans[2]."\n";


/*
 *		Function for the Service Callback : it will call a phonenumber and redirect it into the BCB application
 */ 
function Service_Callback($security_key, $phone_number, $callerid, $transaction_id, $callback_time, $id_server_group){
	
	$uniqueid 	=  MDP(10).'-'.$transaction_id;
	$status = 'PENDING';
	$server_ip = 'localhost';
	$num_attempt = 0;		
	$exten = $phone_number;
	$context = 'a2billing-callback';
	$priority = 1;
	//$timeout	callerid
	
	// USE RATE_ENGINE_FINDRATES - RATE_ENGINE_ALL_CALCULTIMEOUT - CALL PLAN BY DEFAULT TO FIND OUT THE CORRECT CHANNEL
	$channel = 'SIP/'.$phone_number.'@toreplace';
	$variable = "mode=callback|phonenumber=$phone_number|callerid=$callerid|transaction_id=$transaction_id|id_server_group=$id_server_group";
	
	$FG_regular[]  = array( "^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$"   ,"(YYYY-MM-DD HH:MM:SS)");
	
	// The wrapper variables for security
	// $security_key = API_SECURITY_KEY;
	write_log(LOGFILE_API_CALLBACK,"Service_Callback( security_key=$security_key, transaction_id=$transaction_id, phonenumber=$phone_number, callerid=$callerid, callerid=$callerid, callback_time=$callback_time, id_server_group=$id_server_group)");
	
	$mysecurity_key = API_SECURITY_KEY;
	
	$mail_content = "[" . date("Y/m/d G:i:s", mktime()) . "] "." API - Request asked: Callback [security_key=$security_key, transaction_id=$transaction_id, phonenumber=$phone_number, callerid=$callerid, callerid=$callerid, callback_time=$callback_time, id_server_group=$id_server_group]";
	
	if (!is_numeric($id_server_group)){
		$id_server_group = 1;
	}
	
	// CHECK CALLERID
	if (strlen($callerid)<1)
	{
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR FORMAT CALLERID AT LEAST 1 DIGIT ");
		sleep(2);
		return array($transaction_id, '400 -- malformed query', " ERROR - FORMAT CALLERID AT LEAST 1 DIGIT ");
	}
	
	// CHECK PHONE_NUMBER
	if (strlen($phone_number)<10)
	{
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR FORMAT PHONENUMBER AT LEAST 10 DIGITS ");
		sleep(2);
		return array($transaction_id, '400 -- malformed query', " ERROR - FORMAT PHONENUMBER AT LEAST 10 DIGITS ");
	}
	
	// CHECK CALLBACK TIME
	if (strlen($callback_time)>1 && !(ereg( $FG_regular[0][0], $callback_time)))
	{
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR FORMAT CALLBACKTIME : ".$FG_regular[0][0]);
		sleep(2);
		return array($transaction_id, '400 -- malformed query', " ERROR - FORMAT CALLBACKTIME : ".$FG_regular[0][0]);
	}
	
	// CHECK SECURITY KEY
	if (md5($mysecurity_key) !== $security_key  || strlen($security_key)==0)
	{
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." CODE_ERROR SECURITY_KEY");
		sleep(2);
		return array($transaction_id, '400 -- malformed query', ' KEY - BAD PARAMETER ');
	}
	
	$DBHandle = DbConnect();
	if (!$DBHandle){			
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR CONNECT DB");
		sleep(2);
		return array($transaction_id, '500 -- error processing query or fetching data', ' ERROR - CONNECT DB ');
	}
	
	if (strlen($callback_time)>1){
		$QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, callback_time, id_server_group ) VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$callback_time', '$id_server_group')";
	}else{
		$QUERY = " INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group ) ".
			 " VALUES ('$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group')";
	}
	$res = $DBHandle -> Execute($QUERY);
	
	if (!$res){
		write_log(LOGFILE_API_CALLBACK, basename(__FILE__).' line:'.__LINE__." ERROR INSERT INTO DB");
		sleep(2);
		return array($transaction_id, '500 -- error processing query or fetching data', ' ERROR - INSERT CALLBACK INTO DB');
	}
	
	
	return array($transaction_id, '200 -- successful query', " Success - Callback request has been accepted ");
}



?>
