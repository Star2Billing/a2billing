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


include ("../lib/agent.defines.php");


getpost_ifset(array('transactionID', 'sess_id', 'key', 'mc_currency', 'currency', 'md5sig', 'merchant_id', 'mb_amount', 'status', 'mb_currency',
					'transaction_id', 'mc_fee', 'card_number'));



write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION START (ID)----");
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionKey=$key"." ----EPAYMENT TRANSACTION KEY----");
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-POST Var \n".print_r($_POST, true));

if ($sess_id =="") {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SESSION ID PROVIDED IN RETURN URL TO PAYMENT MODULE");
    exit();
}

if($transactionID == "") {	
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." NO TRANSACTION ID PROVIDED IN REQUEST");
    exit();
}


include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/epayment/classes/payment.php");
include ("../lib/epayment/classes/order.php");
include ("../lib/epayment/classes/currencies.php");
include ("../lib/epayment/includes/general.php");
include ("../lib/epayment/includes/html_output.php");
include ("../lib/epayment/includes/configure.php");
include ("../lib/epayment/includes/loadconfiguration.php");


$DBHandle_max  = DbConnect();
$paymentTable = new Table();

if (DB_TYPE == "postgres") {
	$NOW_2MIN = " creationdate <= (now() - interval '2 minute') ";
} else {
	$NOW_2MIN = " creationdate <= DATE_SUB(NOW(), INTERVAL 2 MINUTE) ";
}

// Status - New 0 ; Proceed 1 ; In Process 2
$QUERY = "SELECT id, agent_id, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, status, cvv, credit_card_type, currency FROM cc_epayment_log_agent WHERE id = ".$transactionID." AND (status = 0 OR (status = 2 AND $NOW_2MIN))";
$transaction_data = $paymentTable->SQLExec ($DBHandle_max, $QUERY);

//Update the Transaction Status to 1
$QUERY = "UPDATE cc_epayment_log_agent SET status = 2 WHERE id = ".$transactionID;
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $QUERY");
$paymentTable->SQLExec ($DBHandle_max, $QUERY);


if(!is_array($transaction_data) && count($transaction_data) == 0) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).
		' line:'.__LINE__."- transactionID=$transactionID"." ERROR INVALID TRANSACTION ID PROVIDED, TRANSACTION ID =".$transactionID);
	exit();
} else {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).
		' line:'.__LINE__."- transactionID=$transactionID"." EPAYMENT RESPONSE: TRANSACTIONID = ".$transactionID.
		" FROM ".$transaction_data[0][4]."; FOR CUSTOMER ID ".$transaction_data[0][1]."; OF AMOUNT ".$transaction_data[0][2]);
}

$security_verify = true;
$transaction_detail = serialize($_POST);

$currencyObject 	= new currencies();
$currencies_list 	= get_currencies();

switch($transaction_data[0][4])
{
	case "paypal":
		$currCurrency = $mc_currency;
		if($A2B->config['epayment_method']['charge_paypal_fee']==1){
			$currAmount = $transaction_data[0][2] ;
		}else{
			$currAmount = $transaction_data[0][2] - $mc_fee;
		}
		$postvars = array();
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $vkey => $Value) {
			$req .= "&" . $vkey . "=" . urlencode ($Value);
		}
		
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen ($req) . "\r\n\r\n";
		for ($i = 1; $i <=3; $i++) {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-OPENDING HTTP CONNECTION TO ".PAYPAL_VERIFY_URL);
			$fp = fsockopen (PAYPAL_VERIFY_URL, 443, $errno, $errstr, 30);
			if($fp) {	
				break;
			} else {
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." -Try#".$i." Failed to open HTTP Connection : ".$errstr.". Error Code: ".$errno);
				sleep(3);
			}
		}		
		if (!$fp) {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-Failed to open HTTP Connection: ".$errstr.". Error Code: ".$errno);
			exit();
		} else {
			fputs ($fp, $header . $req);			
			$flag_ver = 0;
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Verified ");
					$flag_ver = 1;
				}				
			}
			if($flag_ver == 0) {
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Failed ");
				$security_verify = false;
			}
		}
		fclose ($fp);	
		break;
		
	case "moneybookers":
		$currAmount = $transaction_data[0][2];
		$sec_string = $merchant_id.$transaction_id.strtoupper(md5(MONEYBOOKERS_SECRETWORD)).$mb_amount.$mb_currency.$status;
		$sig_string = strtoupper(md5($sec_string));
		
		if($sig_string == $md5sig) {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-MoneyBookers Transaction Verification Status: Verified | md5sig =".$md5sig." Reproduced Signature = ".$sig_string." Generated String = ".$sec_string);
		} else {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-MoneyBookers Transaction Verification Status: Failed | md5sig =".$md5sig." Reproduced Signature = ".$sig_string." Generated String = ".$sec_string);
			$security_verify = false;			
		}
		$currCurrency = $currency;
		break;
		
	case "authorizenet":
		$currAmount = $transaction_data[0][2];
		$currCurrency = BASE_CURRENCY;
		break;
		
	case "plugnpay":
		
		if (substr($card_number,0,4) != substr($transaction_data[0][6],0,4)) {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- PlugNPay Error : First 4digits of the card doesn't match with the one stored.");
		}
		
		$currCurrency 		= BASE_CURRENCY;
		$currAmount 		= $transaction_data[0][2];
		$currAmount_usd		= convert_currency($currencies_list, $currAmount, BASE_CURRENCY, 'USD');
		
		$pnp_post_values = array(
	        'publisher-name' => MODULE_PAYMENT_PLUGNPAY_LOGIN,
	        'mode'           => 'auth',
	        'ipaddress'      => $_SERVER['REMOTE_ADDR'],
	        // Metainfo
	        'convert'        => 'underscores',
	        'easycart'       => '1',
	        'shipinfo'       => '1',
	        'authtype'       => MODULE_PAYMENT_PLUGNPAY_CCMODE,
	        'paymethod'      => MODULE_PAYMENT_PLUGNPAY_PAYMETHOD,
	        'dontsndmail'    => MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL,
	        // Card Info
	        'card_number'    => $card_number,
		    'card-name'      => $transaction_data[0][5],
		    'card-amount'    => $currAmount_usd,
		    'card-exp'       => $transaction_data[0][7],
		    'cc-cvv'         => $transaction_data[0][10] 
	    );
	    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- PlugNPay Value Sent : \n\n".print_r($pnp_post_values, true));
	    
		// init curl handle
		$pnp_ch = curl_init(PLUGNPAY_PAYMENT_URL);
		curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
		$http_query = http_build_query( $pnp_post_values );
		curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $http_query);
		#curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility
		
		// perform ssl post
		$pnp_result_page = curl_exec($pnp_ch);
		parse_str( $pnp_result_page, $pnp_transaction_array );
		
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- PlugNPay Result : \n\n".print_r($pnp_transaction_array, true));
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- RESULT : ".$pnp_transaction_array['FinalStatus']);
		
		// $pnp_transaction_array['FinalStatus'] = 'badcard';
		//echo "<pre>".print_r ($pnp_transaction_array, true)."</pre>";
		
		$transaction_detail = serialize($pnp_transaction_array);
		break;
		
	default:
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-NO SUCH EPAYMENT FOUND");
		exit();
}


if(empty($transaction_data[0]['vat']) || !is_numeric($transaction_data[0]['vat'])) $VAT =0;
else $VAT = $transaction_data[0]['vat'];
$amount_paid = convert_currency($currencies_list, $currAmount, $currCurrency, BASE_CURRENCY);
$amount_without_vat = $amount_paid / (1+$VAT/100);
//If security verification fails then send an email to administrator as it may be a possible attack on epayment security.
if ($security_verify == false) {
        try {
        //TODO create mail class for agent
        $mail = new Mail('epaymentverify',null);
        } catch (A2bMailException $e) {
            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO EMAIL TEMPLATE FOUND");
            exit();
        }
        $mail->replaceInEmail(Mail::$TIME_KEY,date("y-m-d H:i:s"));
        $mail->replaceInEmail(Mail::$PAYMENTGATEWAY_KEY, $transaction_data[0][4]);
        $mail->replaceInEmail(Mail::$ITEM_AMOUNT_KEY, $amount_paid.$currCurrency);
	// Add Post information / useful to track down payment transaction without having to log
	$mail->AddToMessage("\n\n\n\n"."-POST Var \n".print_r($_POST, true));
	$mail ->send(ADMIN_EMAIL);
	
	exit;
}

$newkey = securitykey(EPAYMENT_TRANSACTION_KEY, $transaction_data[0][8]."^".$transactionID."^".$transaction_data[0][2]."^".$transaction_data[0][1]);
if($newkey == $key) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."----------- Transaction Key Verified ------------");
} else {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."----NEW KEY =".$newkey." OLD KEY= ".$key." ------- Transaction Key Verification Failed:".$transaction_data[0][8]."^".$transactionID."^".$transaction_data[0][2]."^".$transaction_data[0][1]." ------------\n");
	exit();
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ---------- TRANSACTION INFO ------------\n".print_r($transaction_data,1));

$payment_modules = new payment($transaction_data[0][4]);
// load the before_process function from the payment modules
//$payment_modules->before_process();



$QUERY = "SELECT id, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, currency FROM cc_agent WHERE id = '".$transaction_data[0][1]."'";
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SUCH CUSTOMER EXISTS, CUSTOMER ID = ".$transaction_data[0][1]);
    exit(gettext("No Such Customer exists."));
}
$customer_info = $resmax -> fetchRow();
$nowDate = date("Y-m-d H:i:s");

$pmodule = $transaction_data[0][4];

$orderStatus = $payment_modules->get_OrderStatus();

$Query = "Insert into cc_payments_agent ( agent_id, agent_name, agent_email_address, item_name, item_id, item_quantity, payment_method, cc_type, cc_owner, cc_number, " .
									" cc_expires, orders_status, last_modified, date_purchased, orders_date_finished, orders_amount, currency, currency_value) values (" .
									" '".$transaction_data[0][1]."', '".$customer_info[3]." ".$customer_info[2]."', '".$customer_info["email"]."', 'balance', '".
									$customer_info[0]."', 1, '$pmodule', '".$_SESSION["p_cardtype"]."', '".$transaction_data[0][5]."', '".$transaction_data[0][6]."', '".
									$transaction_data[0][7]."',  $orderStatus, '".$nowDate."', '".$nowDate."', '".$nowDate."',  ".$amount_paid.",  '".$currCurrency."', '".
									$currencyObject->get_value($currCurrency)."' )";
$result = $DBHandle_max -> Execute($Query);


//************************UPDATE THE CREDIT IN THE CARD***********************
$id = $customer_info[0];

if ($id > 0 ) {
    $addcredit = $transaction_data[0][2]; 
	$instance_table = new Table("cc_agent", "");
	$param_update .= " credit = credit+'".$amount_without_vat."'";
	$FG_EDITION_CLAUSE = " id='$id'";
	$instance_table -> Update_table ($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Update_table cc_card : $param_update - CLAUSE : $FG_EDITION_CLAUSE");

	$field_insert = "date, credit, agent_id, description";
	$value_insert = "'$nowDate', '".$amount_without_vat."', '$id', '".$transaction_data[0][4]."'";
	$instance_sub_table = new Table("cc_logrefill_agent", $field_insert);
	$id_logrefill = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null, 'id');
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logrefill : $field_insert - VALUES $value_insert");
	
	$field_insert = "date, payment, agent_id, id_logrefill, description";
	$value_insert = "'$nowDate', '".$amount_paid."', '$id', '$id_logrefill', '".$transaction_data[0][4]."'";
	$instance_sub_table = new Table("cc_logpayment_agent", $field_insert);
	$id_payment = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logpayment : $field_insert - VALUES $value_insert");
	
}

//*************************END UPDATE CREDIT************************************

$_SESSION["p_amount"] = null;
$_SESSION["p_cardexp"] = null;
$_SESSION["p_cardno"] = null;
$_SESSION["p_cardtype"] = null;
$_SESSION["p_module"] = null;
$_SESSION["p_module"] = null;

//Update the Transaction Status to 1
$QUERY = "UPDATE cc_epayment_log_agent SET status = 1, transaction_detail ='".addslashes($transaction_detail)."' WHERE id = ".$transactionID;
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $QUERY");
$paymentTable->SQLExec ($DBHandle_max, $QUERY);


switch ($orderStatus)
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

if ( ($orderStatus != 2) && ($transaction_data[0][4]=='plugnpay')) {
	Header ("Location: checkout_payment.php?payment_error=plugnpay&error=The+payment+couldnt+be+proceed+correctly!");
	die();
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS  = ".$statusmessage);


// load the after_process function from the payment modules
$payment_modules->after_process();
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS ID = ".$orderStatus." ".$statusmessage);
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION END----");


if ($transaction_data[0][4]=='plugnpay') {
	Header ("Location: agentinfo.php");
	die;
}
	

