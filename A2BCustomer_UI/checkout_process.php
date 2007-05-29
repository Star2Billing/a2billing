<?php
include ("./lib/defines.php");


getpost_ifset(array('transactionID', 'sess_id','key','mc_currency','currency','md5sig','merchant_id','mb_amount','status','mb_currency','transaction_id'));


write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION START (ID)----");
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionKey=$key"." ----EPAYMENT TRANSACTION KEY----");

if ($sess_id =="")
{
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SESSION ID PROVIDED IN RETURN URL TO PAYMENT MODULE");
    exit();
}

if($transactionID == "")
{	
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." NO TRANSACTION ID PROVIDED IN REQUEST");
    exit();
}


include ("./lib/module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./lib/epayment/classes/payment.php");
include ("./lib/epayment/classes/order.php");
include ("./lib/epayment/classes/currencies.php");
include ("./lib/epayment/includes/general.php");
include ("./lib/epayment/includes/html_output.php");
include ("./lib/epayment/includes/configure.php");
include ("./lib/epayment/includes/loadconfiguration.php");
//include("PP_header.php");


$DBHandle_max  = DbConnect();
$paymentTable = new Table();

$QUERY = "SELECT * from cc_epayment_log WHERE status = 0 AND id = ".$transactionID;
$transaction_data = $paymentTable->SQLExec ($DBHandle_max, $QUERY);


if(!is_array($transaction_data) && count($transaction_data) == 0)
{
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR INVALID TRANSACTION ID PROVIDED, TRANSACTION ID =".$transactionID);
	exit();
}
else
{
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT RESPONSE: TRANSACTIONID = ".$transactionID." FROM ".$transaction_data[0][4]."; FOR CUSTOMER ID ".$transaction_data[0][1]."; OF AMOUNT ".$transaction_data[0][2]);
}
$security_verify = true;

switch($transaction_data[0][4])
{
	case "paypal":	
		$currCurrency = $mc_currency;
		$postvars = array();
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $vkey => $Value) 
		{
			$req .= "&" . $vkey . "=" . urlencode ($Value);
		}				
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
		for ($i = 1; $i <=3; $i++)
		{
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-OPENDING HTTP CONNECTION TO ".PAYPAL_VERIFY_URL);
			$fp = fsockopen (PAYPAL_VERIFY_URL, 80, $errno, $errstr, 30);
			if($fp)
			{	
				break;
			}
			else
			{
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." -Try#".$i." Failed to open HTTP Connection : ".$errstr.". Error Code: ".$errno);
				sleep(3);
			}
		}		
		if (!$fp) 
		{
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-Failed to open HTTP Connection: ".$errstr.". Error Code: ".$errno);
			exit();
		}		
		else 
		{
			fputs ($fp, $header . $req);			
			$flag_ver = 0;
			while (!feof($fp)) 
			{
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) 
				{
					write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Verified ");
					$flag_ver = 1;
				}				
			}
			if($flag_ver == 0)
			{
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Failed ");
				$security_verify = false;
			}
		}
		fclose ($fp);	
		break;
	case "moneybookers":
		$sec_string = $merchant_id.$transaction_id.strtoupper(md5(MONEYBOOKERS_SECRETWORD)).$mb_amount.$mb_currency.$status;
		$sig_string = strtoupper(md5($sec_string));
		if($sig_string == $md5sig)
		{
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-MoneyBookers Transaction Verification Status: Verified ");
		}
		else
		{
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-MoneyBookers Transaction Verification Status: Failed | md5sig =".$md5sig." Reproduced Signature = ".$sig_string." Generated String = ".$sec_string);
			$security_verify = false;			
		}
		$currCurrency = $currency;
		break;
	case "authorizenet":
		$currCurrency = BASE_CURRENCY;
		break;
	default:
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-NO SUCH EPAYMENT FOUND");
		exit();
}
//If security verification fails then send an email to administrator as it may be a possible attack on epayment security.
$currencyObject = new currencies();
$currencies_list = get_currencies();
$amount_paid = convert_currency($currencies_list,$transaction_data[0][2], $currCurrency, BASE_CURRENCY);

if($security_verify == false)
{
	$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='epaymentverify' ";
	$res = $DBHandle_max -> Execute($QUERY);
	$num = 0;	
	if ($res)
		$num = $res -> RecordCount();
	
	if (!$num)
	{
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-NO EMAIL TEMPLATE FOUND TO SEND WARNING EMAIL TO ADMINISTRATOR FOR EPAYMENT VERIFICATION FAILURE");
		exit();
	}
	for($i=0;$i<$num;$i++)
	{
		$listtemplate[] = $res->fetchRow();
	}
	list($mailtype, $from, $fromname, $subject, $messagetext, $messagehtml) = $listtemplate [0];

	$messagetext = str_replace('$time', date("y-m-d H:i:s"), $messagetext);	
	$messagetext = str_replace('$paymentgateway', $transaction_data[0][4], $messagetext);
	$messagetext = str_replace('$amount', $amount_paid.$currCurrency, $messagetext);

	$em_headers  = "From: ".$fromname." <".$from.">\n";
	$em_headers .= "Reply-To: ".$from."\n";
	$em_headers .= "Return-Path: ".$from."\n";
	$em_headers .= "X-Priority: 3\n";

	mail(ADMIN_EMAIL, $subject, $messagetext, $em_headers);
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-EMAIL SENT: SENT A WARNING EMAIL TO ADMINISTRATOR FOR EPAYMENT VERIFICATION FAILURE");
	exit;
}
$newkey = securitykey(EPAYMENT_TRANSACTION_KEY, $transaction_data[0][8]."^".$transactionID."^".$transaction_data[0][2]."^".$transaction_data[0][1]);
if($newkey == $key)
{
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."----------- Transaction Key Verified ------------");
}
else
{
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."----NEW KEY =".$newkey." OLD KEY= ".$key." ------- Transaction Key Verification Failed:".$transaction_data[0][8]."^".$transactionID."^".$transaction_data[0][2]."^".$transaction_data[0][1]." ------------\n");
	exit();
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ---------- TRANSACTION INFO ------------\n".print_r($transaction_data,1));

$payment_modules = new payment($transaction_data[0][4]);
// load the before_process function from the payment modules
//$payment_modules->before_process();

$tansaction_ID = null;


$QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, currency FROM cc_card WHERE id = '".$transaction_data[0][1]."'";


$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0)
{
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SUCH CUSTOMER EXISTS, CUSTOMER ID = ".$transaction_data[0][1]);
    exit(gettext("No Such Customer exists."));
}
$customer_info =$resmax -> fetchRow();
$nowDate = date("y-m-d H:i:s");


$pmodule = $transaction_data[0][4];

$orderStatus = $payment_modules->get_OrderStatus();



$Query = "Insert into cc_payments ( customers_id,
                                    customers_name,
                                    customers_email_address,
                                    item_name,
                                    item_id,
                                    item_quantity,
                                    payment_method,
                                    cc_type,
                                    cc_owner,
                                    cc_number,
                                    cc_expires,
                                    orders_status,
                                    last_modified,
                                    date_purchased,
                                    orders_date_finished,
                                    orders_amount,
                                    currency,
                                    currency_value)
                                    values
                                    (
                                    '".$transaction_data[0][1]."',
                                    '".$customer_info[3]." ".$customer_info[2]."',
                                    '".$customer_info["email"]."',
                                    'balance',
                                    '".$customer_info[0]."',
                                    1,
                                    '$pmodule',
                                    '".$_SESSION["p_cardtype"]."',
                                    '".$transaction_data[0][5]."',
                                    '".$transaction_data[0][6]."',
                                    '".$transaction_data[0][7]."',
                                     $orderStatus,
                                    '".$nowDate."',
                                    '".$nowDate."',
                                    '".$nowDate."',
                                     ".$amount_paid.",
                                     '".$currCurrency."',
                                     '".$currencyObject->get_value($currCurrency)."'
                                    )";

$result = $DBHandle_max -> Execute($Query);


//************************UPDATE THE CREDIT IN THE CARD***********************
$id = 0;
if ($customer_info[0] > 0 && $orderStatus == 2)
{

    /* CHECK IF THE CARDNUMBER IS ON THE DATABASE */
    $instance_table_card = new Table("cc_card", "username, id");
    $FG_TABLE_CLAUSE_card = "username='".$customer_info[0]."'";
    $list_tariff_card = $instance_table_card -> Get_list ($DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);
    //print_r($list_tariff_card);
    if ($customer_info[0] == $list_tariff_card[0][0])
    {
        $id = $list_tariff_card[0][1];
    }
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." CARD FOUND IN DB ($id)");
}


if ($id > 0 ){
    //$addcredit = $_SESSION["p_amount"];
    $addcredit = $transaction_data[0][2]; 
	$instance_table = new Table("cc_card", "username, id");
	$param_update .= " credit = credit+'".$amount_paid."'";
	$FG_EDITION_CLAUSE = " id='$id'";
	$instance_table -> Update_table ($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Update_table cc_card : $param_update - CLAUSE : $FG_EDITION_CLAUSE");

	$field_insert = "date, credit, card_id";
	$value_insert = "'$nowDate', '".$amount_paid."', '$id'";
	$instance_sub_table = new Table("cc_logrefill", $field_insert);
	$result_query = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null);
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logrefill : $field_insert - VALUES $value_insert");

	$field_insert = "date, payment, card_id";
	$value_insert = "'$nowDate', '".$amount_paid."', '$id'";
	$instance_sub_table = new Table("cc_logpayment", $field_insert);
	$result_query = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null);
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logpayment : $field_insert - VALUES $value_insert");
}

//*************************END UPDATE CREDIT************************************

switch($orderStatus)
{
	case -2:
		$statusmessage = "Failed";
		break;
	case -1:
		$statusmessage = "Denied";
		break;
	case 0:
		$statusmessage = "Pending";
		break;
	case 1:
		$statusmessage = "In-Progress";
		break;
	case 2:
		$statusmessage = "Successful";
		break;
}
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS  = ".$statusmessage);
// CHECK IF THE EMAIL ADDRESS IS CORRECT
if(eregi("^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$", $customer_info["email"])){
	// FIND THE TEMPLATE APPROPRIATE
	$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='payment' ";
	$res = $DBHandle_max -> Execute($QUERY);
	
	$num = 0;
	if ($res){
		$num = $res -> RecordCount();
	}

	if (!$num)
	{
		// WE DONT HAVE A TEMPLATE
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO EMAIL TEMPLATE FOUND");
		
	} else {
		
		// WE HAVE A TEMPLATE
		for($i=0;$i<$num;$i++)
		{
			$listtemplate[] = $res->fetchRow();
		}
		
		list($mailtype, $from, $fromname, $subject, $messagetext, $messagehtml) = $listtemplate [0];
		$statusmessage= "";
		
		$messagetext = str_replace('$itemName', "balance", $messagetext);
		$messagetext = str_replace('$itemID', $customer_info[0], $messagetext);
		$messagetext = str_replace('$itemAmount', $amount_paid." ".strtoupper(BASE_CURRENCY), $messagetext);
		$messagetext = str_replace('$paymentMethod', $pmodule, $messagetext);
		$messagetext = str_replace('$paymentStatus', $statusmessage, $messagetext);
		
		$em_headers  = "From: ".$fromname." <".$from.">\n";
		$em_headers .= "Reply-To: ".$from."\n";
		$em_headers .= "Return-Path: ".$from."\n";
		$em_headers .= "X-Priority: 3\n";
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-SENDING EMAIL TO CUSTOMER ".$customer_info["email"]);
		mail($customer_info["email"], $subject, $messagetext, $em_headers);
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-SENDING EMAIL TO CUSTOMER ENDS");
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"."- MAILTO:".$customer_info["email"]."-Sub=$subject, mtext=$messagetext");
	}
}else{
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Customer : no email info !!!");
}

$_SESSION["p_amount"] = null;
$_SESSION["p_cardexp"] = null;
$_SESSION["p_cardno"] = null;
$_SESSION["p_cardtype"] = null;
$_SESSION["p_module"] = null;
$_SESSION["p_module"] = null;

//Update the Transaction Status to 1
$QUERY = "UPDATE cc_epayment_log SET status = 1 WHERE id = ".$transactionID;
$paymentTable->SQLExec ($DBHandle_max, $QUERY);

// load the after_process function from the payment modules
$payment_modules->after_process();
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS ID = ".$orderStatus." ".$statusmessage);
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION END----");


?>
