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

getpost_ifset (array('id', 'key', 'payment_gross','payment_status', 'txn_type','payer_email'));

$log = "\nREQUEST RECURRING PAYMENT:\n";
$log .= "GET:\n";
foreach ($_GET as $vkey => $Value) {
	$log .= $vkey . " = " . $Value . "\n";
}
$log .= "POST:\n";
foreach ($_POST as $vkey => $Value) {
	$log .= $vkey . " = " . $Value . "\n";
}

write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "$log");

if ($payment_status != "Completed" || $txn_type != "subscr_payment") {
	die();
}

$req = 'cmd=_notify-validate';
foreach ($_POST as $vkey => $Value) {
	$req .= "&" . $vkey . "=" . urlencode($Value);
}

$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

for ($i = 1; $i <= 3; $i++) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-OPENDING HTTP CONNECTION TO " . PAYPAL_VERIFY_URL);
	$fp = fsockopen(PAYPAL_VERIFY_URL, 443, $errno, $errstr, 30);
	if ($fp) {
		break;
	} else {
		write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . " -Try#" . $i . " Failed to open HTTP Connection : " . $errstr . ". Error Code: " . $errno);
		sleep(3);
	}
}

if (!$fp) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-Failed to open HTTP Connection: " . $errstr . ". Error Code: " . $errno);
	exit ();
} else {
	fputs($fp, $header . $req);
	$flag_ver = 0;
	while (!feof($fp)) {
		$res = fgets($fp, 1024);
		$gather_res .= $res;
		if (strcmp($res, "VERIFIED") == 0) {
			write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-PAYPAL Transaction Verification Status: Verified ");
			$flag_ver = 1;
		}
	}
	write_log("/var/log/a2billing/test.log", "\nREQUEST:\n$gather_res");
	if ($flag_ver == 0) {
		write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-PAYPAL Transaction Verification Status: Failed \nreq=$req\n$gather_res");
		$security_verify = false;
	}
}
fclose($fp);
$DBHandle = DbConnect();
$table_card = new Table("cc_card", "username,useralias,UNIX_TIMESTAMP(creationdate) creationdate,vat,firstname,lastname");
$card_clause = "id = $id";
$result = $table_card->Get_list($DBHandle, $card_clause);

if (!is_array($result)) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-PAYPAL Reccurring Payment Failed : card id( $id ) not found");
	die();
}

$card = $result[0];
$username = $result[0]['username'];
$creationdate = $result[0]['creationdate'];
$useralias = $result[0]['useralias'];
$vat = $result[0]['vat'];
$firstname = $result[0]['firstname'];
$lastname = $result[0]['lastname'];
$email = $result[0]['email'];
$newkey = securitykey(EPAYMENT_TRANSACTION_KEY, $username . "^" . $id . "^" . $useralias . "^" . $creationdate);

if ($newkey == $key) {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "----------- Transaction Key Verified ------------");
} else {
	write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "----NEW KEY =" . $newkey . " OLD KEY= " . $key . " ------- Transaction Key Verification Failed:" . $transaction_data[0][8] . "^" . $transactionID . "^" . $transaction_data[0][2] . "^" . $transaction_data[0][1] . " ------------\n");
	exit ();
}

$amount_paid = $payment_gross;
$amount_without_vat = $amount_paid / (1 + $vat / 100);
$nowDate = date("Y-m-d H:i:s");

$Query = "INSERT INTO cc_payments ( customers_id, customers_name, customers_email_address, item_name, payment_method,cc_number,orders_status, " .
" last_modified, date_purchased, orders_date_finished, orders_amount, currency, currency_value) values (" .
" '" . $id . "', '" . $firstname . " " . $lastname . "', '" . $email . "', 'RECURRING PAYMENT', 'PAYPAL' ," .
" '$payer_email','2', '" . $nowDate . "', '" . $nowDate . "', '" . $nowDate . "',  " . $amount_paid . ",  '" . BASE_CURRENCY . "', '1' )";
$result = $DBHandle->Execute($Query);

$instance_table = new Table("cc_card", "username, id");
$param_update = " credit = credit+'" . $amount_without_vat . "'";
$FG_EDITION_CLAUSE = " id='$id'";
$instance_table->Update_table($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-Recurring payment" . " Update_table cc_card : $param_update - CLAUSE : $FG_EDITION_CLAUSE");

$field_insert = "date, credit, card_id, description";
$value_insert = "'$nowDate', '" . $amount_without_vat . "', '$id', '" . gettext("Reccurring payment : automated refill") . "'";
$instance_sub_table = new Table("cc_logrefill", $field_insert);
$id_logrefill = $instance_sub_table->Add_table($DBHandle, $value_insert, null, null, 'id');
write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-Recurring payment" . " Add_table cc_logrefill : $field_insert - VALUES $value_insert");

$field_insert = "date, payment, card_id, id_logrefill, description";
$value_insert = "'$nowDate', '" . $amount_paid . "', '$id', '$id_logrefill', '" . gettext("Reccurring payment : automated refill") . "'";
$instance_sub_table = new Table("cc_logpayment", $field_insert);
$id_payment = $instance_sub_table->Add_table($DBHandle, $value_insert, null, null, "id");
write_log(LOGFILE_EPAYMENT, basename(__FILE__) . ' line:' . __LINE__ . "-Recurring payment" . " Add_table cc_logpayment : $field_insert - VALUES $value_insert");

//ADD an INVOICE
$reference = generate_invoice_reference();
$field_insert = "date, id_card, title ,reference, description,status,paid_status";
$date = $nowDate;
$card_id = $id;
$title = gettext("CUSTOMER REFILL");
$description = gettext("Invoice for refill");
$value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,1 ";
$instance_table = new Table("cc_invoice", $field_insert);
$id_invoice = $instance_table->Add_table($DBHandle, $value_insert, null, null, "id");

//load vat of this card
if (!empty ($id_invoice) && is_numeric($id_invoice)) {
	$amount = $amount_without_vat;
	$description = gettext("Automated Refill : recurring payment");
	$field_insert = "date, id_invoice ,price,vat, description";
	$instance_table = new Table("cc_invoice_item", $field_insert);
	$value_insert = " '$date' , '$id_invoice', '$amount','$VAT','$description' ";
	$instance_table->Add_table($DBHandle, $value_insert, null, null, "id");
}

//link payment to this invoice
$table_payment_invoice = new Table("cc_invoice_payment", "*");
$fields = " id_invoice , id_payment";
$values = " $id_invoice, $id_payment	";
$table_payment_invoice->Add_table($DBHandle, $values, $fields);

//END INVOICE
//Agent commision
// test if this card have a agent
$table_transaction = new Table();
$result_agent = $table_transaction->SQLExec($DBHandle, "SELECT cc_card_group.id_agent FROM cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card.id = $id");

if (is_array($result_agent) && !is_null($result_agent[0]['id_agent']) && $result_agent[0]['id_agent'] > 0) {
	//test if the agent exist and get its commission
	$id_agent = $result_agent[0]['id_agent'];
	$agent_table = new Table("cc_agent", "commission");
	$agent_clause = "id = " . $id_agent;
	$result_agent = $agent_table->Get_list($DBHandle, $agent_clause);

	if (is_array($result_agent) && is_numeric($result_agent[0]['commission']) && $result_agent[0]['commission'] > 0) {
		$field_insert = "id_payment, id_card, amount,description,id_agent";
		$commission = ceil(($amount_paid * ($result_agent[0]['commission']) / 100) * 100) / 100;
		$description_commission = gettext("AUTOMATICALY GENERATED COMMISSION!");
		$description_commission .= "\nID CARD : " . $id;
		$description_commission .= "\nID PAYMENT : " . $id_payment;
		$description_commission .= "\nPAYMENT AMOUNT: " . $amount_paid;
		$description_commission .= "\nCOMMISSION APPLIED: " . $result_agent[0]['commission'];
		$value_insert = "'" . $id_payment . "', '$id', '$commission','$description_commission','$id_agent'";
		$commission_table = new Table("cc_agent_commission", $field_insert);
		$id_commission = $commission_table->Add_table($DBHandle, $value_insert, null, null, "id");
	}
}

