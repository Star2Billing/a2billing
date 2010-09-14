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

include ("./lib/customer.defines.php");

getpost_ifset (array('transactionID', 'sess_id', 'key', 'mc_currency', 'currency', 'md5sig', 'merchant_id', 'mb_amount', 'status', 'mb_currency',
					'transaction_id', 'mc_fee', 'card_number'));



write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."EPAYMENT : transactionID=$transactionID - transactionKey=$key \n -POST Var \n".print_r($_POST, true));

if ($sess_id =="") {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SESSION ID PROVIDED IN RETURN URL TO PAYMENT MODULE");
    exit();
}

if($transactionID == "") {	
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." NO TRANSACTION ID PROVIDED IN REQUEST");
    exit();
}


include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./lib/epayment/classes/payment.php");
include ("./lib/epayment/classes/order.php");
include ("./lib/epayment/classes/currencies.php");
include ("./lib/epayment/includes/general.php");
include ("./lib/epayment/includes/html_output.php");
include ("./lib/epayment/includes/configure.php");
include ("./lib/epayment/includes/loadconfiguration.php");
include ("./lib/support/classes/invoice.php");
include ("./lib/support/classes/invoiceItem.php");


$DBHandle_max  = DbConnect();
$paymentTable = new Table();

if (DB_TYPE == "postgres") {
	$NOW_2MIN = " creationdate <= (now() - interval '2 minute') ";
} else {
	$NOW_2MIN = " creationdate <= DATE_SUB(NOW(), INTERVAL 2 MINUTE) ";
}

// Status - New 0 ; Proceed 1 ; In Process 2
$QUERY = "SELECT id, cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, status, cvv, credit_card_type, currency, item_id, item_type " .
		 " FROM cc_epayment_log " .
		 " WHERE id = ".$transactionID." AND (status = 0 OR (status = 2 AND $NOW_2MIN))";
$transaction_data = $paymentTable->SQLExec ($DBHandle_max, $QUERY);

$item_id = $transaction_data[0][13];
$item_type = $transaction_data[0][14];

//Update the Transaction Status to 1
$QUERY = "UPDATE cc_epayment_log SET status = 2 WHERE id = ".$transactionID;
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
				$gather_res .= $res;
				if (strcmp ($res, "VERIFIED") == 0) {
					write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Verified ");
					$flag_ver = 1;
				}				
			}
			if ($flag_ver == 0) {
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-PAYPAL Transaction Verification Status: Failed \nreq=$req\n$gather_res");
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
		
	case 'iridium':
        $currCurrency           = BASE_CURRENCY;
        $currAmount             = $transaction_data[0][2];
		break;
		
	default:
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-NO SUCH EPAYMENT FOUND");
		exit();
}

if(empty($transaction_data[0]['vat']) || !is_numeric($transaction_data[0]['vat']))
	$VAT =0;
else
	$VAT = $transaction_data[0]['vat'];

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."curr amount $currAmount $currCurrency ".BASE_CURRENCY);
$amount_paid = convert_currency($currencies_list, $currAmount, $currCurrency, BASE_CURRENCY);
$amount_without_vat = $amount_paid / (1+$VAT/100);

//If security verification fails then send an email to administrator as it may be a possible attack on epayment security.
if ($security_verify == false) {
    try {
    	//TODO create mail class for agent
    	$mail = new Mail('epaymentverify',$id);
    } catch (A2bMailException $e) {
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO EMAIL TEMPLATE FOUND");
        exit();
    }
    $mail->replaceInEmail(Mail::$TIME_KEY,date("y-m-d H:i:s"));
    $mail->replaceInEmail(Mail::$PAYMENTGATEWAY_KEY, $transaction_data[0][4]);
    $mail->replaceInEmail(Mail::$ITEM_AMOUNT_KEY, $amount_paid.$currCurrency);
    
	// Add Post information / useful to track down payment transaction without having to log
	$mail->AddToMessage("\n\n\n\n"."-POST Var \n".print_r($_POST, true));
	$mail->send(ADMIN_EMAIL);

	exit;
}

$newkey = securitykey(EPAYMENT_TRANSACTION_KEY, $transaction_data[0][8]."^".$transactionID."^".$transaction_data[0][2]."^".$transaction_data[0][1]."^".$item_id."^".$item_type);
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

$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, currency, useralias, uipass " .
		 "FROM cc_card WHERE id = '".$transaction_data[0][1]."'";
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax) {
	$numrow = $resmax -> RecordCount();
} else {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SUCH CUSTOMER EXISTS, CUSTOMER ID = ".$transaction_data[0][1]);
    exit(gettext("No Such Customer exists."));
}
$customer_info = $resmax -> fetchRow();
$nowDate = date("Y-m-d H:i:s");

$pmodule = $transaction_data[0][4];

$orderStatus = $payment_modules->get_OrderStatus();
if(empty($item_type))
	$transaction_type='balance';
else
	$transaction_type = $item_type;

$Query = "INSERT INTO cc_payments ( customers_id, customers_name, customers_email_address, item_name, item_id, item_quantity, payment_method, cc_type, cc_owner, " .
			" cc_number, cc_expires, orders_status, last_modified, date_purchased, orders_date_finished, orders_amount, currency, currency_value) values (" .
			" '".$transaction_data[0][1]."', '".$customer_info[3]." ".$customer_info[2]."', '".$customer_info["email"]."', '$transaction_type', '".
			$customer_info[0]."', 1, '$pmodule', '".$_SESSION["p_cardtype"]."', '".$transaction_data[0][5]."', '".$transaction_data[0][6]."', '".
			$transaction_data[0][7]."',  $orderStatus, '".$nowDate."', '".$nowDate."', '".$nowDate."',  ".$amount_paid.",  '".$currCurrency."', '".
			$currencyObject->get_value($currCurrency)."' )";
$result = $DBHandle_max -> Execute($Query);


// UPDATE THE CARD CREDIT
$id = 0;
if ($customer_info[0] > 0 && $orderStatus == 2) {
    /* CHECK IF THE CARDNUMBER IS ON THE DATABASE */
    $instance_table_card = new Table("cc_card", "username, id");
    $FG_TABLE_CLAUSE_card = " username='".$customer_info[0]."'";
    $list_tariff_card = $instance_table_card -> Get_list ($DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);
    if ($customer_info[0] == $list_tariff_card[0][0]) {
        $id = $list_tariff_card[0][1];
    }
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." CARD FOUND IN DB ($id)");
} else {
    write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR CUSTOMER INFO OR ORDERSTATUS ($orderStatus)\n".print_r($_POST, true)."\n");
}

if ($id > 0 ) {
	if (strcasecmp("invoice",$item_type)!=0) {
	    $addcredit = $transaction_data[0][2]; 
		$instance_table = new Table("cc_card", "username, id");
		$param_update .= " credit = credit+'".$amount_without_vat."'";
		$FG_EDITION_CLAUSE = " id='$id'";
		$instance_table -> Update_table ($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Update_table cc_card : $param_update - CLAUSE : $FG_EDITION_CLAUSE");
		
		$table_transaction = new Table();
		$result_agent = $table_transaction -> SQLExec($DBHandle,"SELECT cc_card_group.id_agent FROM cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card.id = $id");
		if (is_array($result_agent) && !is_null($result_agent[0]['id_agent']) && $result_agent[0]['id_agent']>0 ) {
			$id_agent =  $result_agent[0]['id_agent'];
			$id_agent_insert = "'$id_agent'";
		} else {
			$id_agent = null;
			$id_agent_insert = "NULL";
		}
		
		$field_insert = "date, credit, card_id, description, agent_id";
		$value_insert = "'$nowDate', '".$amount_without_vat."', '$id', '".$transaction_data[0][4]."',$id_agent_insert";
		$instance_sub_table = new Table("cc_logrefill", $field_insert);
		$id_logrefill = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null, 'id');
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logrefill : $field_insert - VALUES $value_insert");
		
		$field_insert = "date, payment, card_id, id_logrefill, description, agent_id";
		$value_insert = "'$nowDate', '".$amount_paid."', '$id', '$id_logrefill', '".$transaction_data[0][4]."',$id_agent_insert ";
		$instance_sub_table = new Table("cc_logpayment", $field_insert);
		$id_payment = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logpayment : $field_insert - VALUES $value_insert");
		
		//ADD an INVOICE
		$reference = generate_invoice_reference();
		$field_insert = "date, id_card, title ,reference, description,status,paid_status";
		$date = $nowDate;
		$card_id = $id;
		$title = gettext("CUSTOMER REFILL");
		$description = gettext("Invoice for refill");
		$value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,1 ";
		$instance_table = new Table("cc_invoice", $field_insert);
		$id_invoice = $instance_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
		//load vat of this card
		if (!empty($id_invoice)&& is_numeric($id_invoice)) {
			$amount = $amount_without_vat;
			$description = gettext("Refill ONLINE")." : ".$transaction_data[0][4];
			$field_insert = "date, id_invoice ,price,vat, description";
			$instance_table = new Table("cc_invoice_item", $field_insert);
			$value_insert = " '$date' , '$id_invoice', '$amount','$VAT','$description' ";
			$instance_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
		}
	    //link payment to this invoice
		$table_payment_invoice = new Table("cc_invoice_payment", "*");
		$fields = " id_invoice , id_payment";
		$values = " $id_invoice, $id_payment	";
		$table_payment_invoice->Add_table($DBHandle, $values, $fields);
		//END INVOICE

		// Agent commision
		// test if this card have a agent		
		if (!empty($id_agent)) {

			//test if the agent exist and get its commission
			$agent_table = new Table("cc_agent", "commission");
			$agent_clause = "id = ".$id_agent;
			$result_agent= $agent_table -> Get_list($DBHandle,$agent_clause);
			if(is_array($result_agent) && is_numeric($result_agent[0]['commission']) && $result_agent[0]['commission']>0) {

				$field_insert = "id_payment, id_card, amount,description,id_agent,commission_percent,commission_type";
				$commission = ceil(($amount_without_vat * ($result_agent[0]['commission'])/100)*100)/100;
				$commission_percent = $result_agent[0]['commission'];

				$description_commission = gettext("AUTOMATICALY GENERATED COMMISSION!");
				$description_commission.= "\nID CARD : ".$id;
				$description_commission.= "\nID PAYMENT : ".$id_payment;
				$description_commission.= "\nPAYMENT AMOUNT: ".$amount_without_vat;
				$description_commission.= "\nCOMMISSION APPLIED: ".$commission_percent;

				$value_insert = "'".$id_payment."', '$id', '$commission','$description_commission','$id_agent','$commission_percent','0'";
				$commission_table = new Table("cc_agent_commission", $field_insert);
				$id_commission = $commission_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_agent_commission : $field_insert - VALUES $value_insert");

				$table_agent = new Table('cc_agent');
				$param_update_agent = "com_balance = com_balance + '".$commission."'";
				$clause_update_agent = " id='".$id_agent."'";
				$table_agent -> Update_table ($DBHandle, $param_update_agent, $clause_update_agent, $func_table = null);
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Update_table cc_agent : $param_update_agent - CLAUSE : $clause_update_agent");
			}
			
		}
	} else {
		if($item_id>0) {
			$invoice_table = new Table('cc_invoice','reference');
			$invoice_clause = "id = ".$item_id;
			$result_invoice = $invoice_table->Get_list($DBHandle,$invoice_clause);
			
			if (is_array($result_invoice) && sizeof($result_invoice)==1) {
				$reference =$result_invoice[0][0];
				
				$field_insert = "date, payment, card_id, description";
				$value_insert = "'$nowDate', '".$amount_paid."', '$id', '(".$transaction_data[0][4].") ".gettext('Invoice Payment Ref: ')."$reference '";
				$instance_sub_table = new Table("cc_logpayment", $field_insert);
				$id_payment = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null,"id");
				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logpayment : $field_insert - VALUES $value_insert");

				//update invoice to paid
				$invoice = new Invoice($item_id);
				$invoice -> addPayment($id_payment);
				$invoice -> changeStatus(1);
				$items = $invoice -> loadItems();
				foreach ($items as $item) {
					if ($item -> getExtType() == 'DID') {
						$QUERY = "UPDATE cc_did_use set month_payed = month_payed+1 , reminded = 0 WHERE id_did = '" . $item -> getExtId() .
								 "' AND activated = 1 AND ( releasedate IS NULL OR releasedate < '1984-01-01 00:00:00') ";
						$instance_table->SQLExec($DBHandle, $QUERY, 0);
					}
					if ($item -> getExtType() == 'SUBSCR') {
						//Load subscription
                        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- Type SUBSCR");
						$table_subsc = new Table('cc_card_subscription','paid_status');
						$subscr_clause = "id = ".$item -> getExtId();
						$result_subscr = $table_subsc -> Get_list($DBHandle,$subscr_clause);
						if(is_array($result_subscr)){
							$subscription = $result_subscr[0];
                            write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- cc_card_subscription paid_status : ".$subscription['paid_status']);
							if($subscription['paid_status']==3){
								$billdaybefor_anniversery = $A2B->config['global']['subscription_bill_days_before_anniversary'];
								$unix_startdate = time();
								$startdate = date("Y-m-d",$unix_startdate);
								$day_startdate = date("j",$unix_startdate);
								$month_startdate = date("m",$unix_startdate);
								$year_startdate= date("Y",$unix_startdate);
								$lastday_of_startdate_month = lastDayOfMonth($month_startdate,$year_startdate,"j");

								$next_bill_date = strtotime("01-$month_startdate-$year_startdate + 1 month");
								$lastday_of_next_month= lastDayOfMonth(date("m",$next_bill_date),date("Y",$next_bill_date),"j");

								if ($day_startdate > $lastday_of_next_month) {
									$next_limite_pay_date = date ("$lastday_of_next_month-m-Y" ,$next_bill_date);
								} else {
								$next_limite_pay_date = date ("$day_startdate-m-Y" ,$next_bill_date);
								}

								$next_bill_date = date("Y-m-d",strtotime("$next_limite_pay_date - $billdaybefor_anniversery day")) ;
								$QUERY = "UPDATE cc_card SET status=1 WHERE id=$id";
                                $result = $instance_table->SQLExec($DBHandle, $QUERY, 0);
								write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY - RESULT : $result");
                                
								$QUERY = "UPDATE cc_card_subscription SET paid_status = 2, startdate = '$startdate' ,limit_pay_date = '$next_limite_pay_date', 	next_billing_date ='$next_bill_date' WHERE id=" . $item -> getExtId();
                                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY");
								$instance_table->SQLExec($DBHandle, $QUERY, 0);
							}else{
                                $QUERY = "UPDATE cc_card SET status=1 WHERE id=$id";
                                $result = $instance_table->SQLExec($DBHandle, $QUERY, 0);
								write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY - RESULT : $result");

                                $QUERY = "UPDATE cc_card_subscription SET paid_status = 2 WHERE id=". $item -> getExtId();
                                write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY : $QUERY");
								$instance_table->SQLExec($DBHandle, $QUERY, 0);
							}
						}
					}
				}
			}
		}
	}
}

$_SESSION["p_amount"] = null;
$_SESSION["p_cardexp"] = null;
$_SESSION["p_cardno"] = null;
$_SESSION["p_cardtype"] = null;
$_SESSION["p_module"] = null;
$_SESSION["p_module"] = null;

//Update the Transaction Status to 1
$QUERY = "UPDATE cc_epayment_log SET status = 1, transaction_detail ='".addslashes($transaction_detail)."' WHERE id = ".$transactionID;
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
	$url_forward = "checkout_payment.php?payment_error=plugnpay&error=The+payment+couldnt+be+proceed+correctly";
	if(!empty($item_id) && !empty($item_type)) $url_forward .= "&item_id=".$item_id."&item_type=".$item_type;
	Header ("Location: $url_forward");
	die();
}

if ( ($orderStatus == 0) && ($transaction_data[0][4]=='iridium')) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS  = ".$statusmessage);
    die();
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS  = ".$statusmessage);

// CHECK IF THE EMAIL ADDRESS IS CORRECT
if (preg_match("/^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$/i", $customer_info["email"])) {
	// FIND THE TEMPLATE APPROPRIATE
	
    try {
        $mail = new Mail(Mail::$TYPE_PAYMENT,$id);
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-SENDING EMAIL TO CUSTOMER ".$customer_info["email"]);
        $mail->replaceInEmail(Mail::$ITEM_AMOUNT_KEY,$amount_paid);
        $mail->replaceInEmail(Mail::$ITEM_ID_KEY,$id_logrefill);
        $mail->replaceInEmail(Mail::$ITEM_NAME_KEY,'balance');
        $mail->replaceInEmail(Mail::$PAYMENT_METHOD_KEY,$pmodule);
        $mail->replaceInEmail(Mail::$PAYMENT_STATUS_KEY,$statusmessage);
        $mail->send($customer_info["email"]);
        
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-SENDING EMAIL TO CUSTOMER ".$customer_info["email"]);
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"."- MAILTO:".$customer_info["email"]."-Sub=".$mail->getTitle()." , mtext=".$mail->getMessage());
        
        // Add Post information / useful to track down payment transaction without having to log
		$mail->AddToMessage("\n\n\n\n"."-POST Var \n".print_r($_POST, true));
        $mail->setTitle("COPY FOR ADMIN : ".$mail->getTitle());
        $mail->send(ADMIN_EMAIL);
        
    } catch (A2bMailException $e) {
        write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO EMAIL TEMPLATE FOUND");
    }
	
} else {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Customer : no email info !!!");
}


// load the after_process function from the payment modules
$payment_modules->after_process();
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS ID = ".$orderStatus." ".$statusmessage);
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION END----");


if ($transaction_data[0][4]=='plugnpay') {
	Header ("Location: userinfo.php");
	die;
}
	

