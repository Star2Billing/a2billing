<?php

include ("./lib/customer.defines.php");

getpost_ifset (array('transactionID', 'sess_id', 'key', 'mc_currency', 'currency', 'md5sig', 'merchant_id', 'mb_amount', 'status', 'mb_currency','transaction_id', 'mc_fee', 'card_number'));

write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION START (ID)----");
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionKey=$key"." ----EPAYMENT TRANSACTION KEY----");
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-POST Var \n".print_r($_POST, true));

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
include("../common/lib/epayment/includes/methods/iridium.php");

$DBHandle_max  = DbConnect();
$paymentTable = new Table();

	if (DB_TYPE == "postgres") {
		$NOW_2MIN = " date_purchased <= (now() - interval '0 minute') ";
	} else {
		$NOW_2MIN = " date_purchased <= DATE_SUB(NOW(), INTERVAL 0 MINUTE) ";
	}

// Status - New 0 ; Proceed 1 ; In Process 2
	$QUERY = "SELECT id, cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, status, cvv, credit_card_type, currency, item_id, item_type " .  " FROM cc_epayment_log " .  " WHERE id = ".$transactionID;

	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $QUERY". " Result = $cc_payments_id");
	$transaction_data = $paymentTable->SQLExec ($DBHandle_max, $QUERY);

	$item_id = $transaction_data[0][13];
	$item_type = $transaction_data[0][14];

/* Update the Transaction Status to 1

$QUERY = "UPDATE cc_epayment_log SET status = 2 WHERE id = ".$transactionID;
write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $QUERY");
$paymentTable->SQLExec ($DBHandle_max, $QUERY); */

	if(!is_array($transaction_data) && count($transaction_data) == 0) {
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).  ' line:'.__LINE__."- transactionID=$transactionID"." ERROR INVALID TRANSACTION ID PROVIDED, TRANSACTION ID =".$transactionID);
		exit();

	} else {
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).  ' line:'.__LINE__."- transactionID=$transactionID"." EPAYMENT RESPONSE: TRANSACTIONID = ".$transactionID.  " FROM ".$transaction_data[0][4]."; FOR CUSTOMER ID ".$transaction_data[0][1]."; OF AMOUNT ".$transaction_data[0][2]);

	}

	foreach ($_POST as $field => $value) {
		$$field = $value;
	}

	$MerchantID = MODULE_PAYMENT_IRIDIUM_MERCHANTID;
	$Password = MODULE_PAYMENT_IRIDIUM_PASSWORD;
	$PaymentProcessorDomain = MODULE_PAYMENT_IRIDIUM_GATEWAY;
	$PaymentProcessorPort = MODULE_PAYMENT_IRIDIUM_GATEWAY_PORT;
	$rgeplRequestGatewayEntryPointList = new RequestGatewayEntryPointList();

	if ($PaymentProcessorPort == 443)
	{
		$PaymentProcessorFullDomain = $PaymentProcessorDomain."/";
	}
	else
	{
		$PaymentProcessorFullDomain = $PaymentProcessorDomain.":".$PaymentProcessorPort."/";
	}

	$rgeplRequestGatewayEntryPointList->add("https://gw1.".$PaymentProcessorFullDomain, 100, 2);
	$rgeplRequestGatewayEntryPointList->add("https://gw2.".$PaymentProcessorFullDomain, 200, 2);
	$rgeplRequestGatewayEntryPointList->add("https://gw3.".$PaymentProcessorFullDomain, 300, 2);

	$mdMerchantDetails = new MerchantDetails($MerchantID, $Password);
	$tdsidThreeDSecureInputData = new ThreeDSecureInputData($MD, $PaRes);
 	$tdsaThreeDSecureAuthentication = new ThreeDSecureAuthentication($rgeplRequestGatewayEntryPointList, 1, null, $mdMerchantDetails, $tdsidThreeDSecureInputData, "Some data to be passed out");

 	$boTransactionProcessed = $tdsaThreeDSecureAuthentication->processTransaction($goGatewayOutput, $tomTransactionOutputMessage);

 	if ($boTransactionProcessed == false)
 	{
  		// could not communicate with the payment gateway
  		$Message = "Couldn't communicate with payment gateway";
		$retCode = -2;
 	}
 	else
 	{
  		switch ($goGatewayOutput->getStatusCode())
  		{
   		case 0:
    			// status code of 0 - means transaction successful
    			$Message = $goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
			$Message = "Payment successful. Thank you";
    			$retCode = 2;
    			break;

   		case 5:
    			// status code of 5 - means transaction declined
    			$Message = $goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );
			$retCode = -2;
    			break;

   		case 20:

    			// status code of 20 - means duplicate transaction
    			$Message = $goGatewayOutput->getMessage();
			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );

    			if ($goGatewayOutput->getPreviousTransactionResult()->getStatusCode()->getValue() == 0){
				$retCode = 2;
    			} else {
				$retCode = -2;
       			}

    			$PreviousTransactionMessage = $goGatewayOutput->getPreviousTransactionResult()->getMessage();

    			break;

   		case 30:

    			// status code of 30 - means an error occurred

    			$Message = $goGatewayOutput->getMessage();

    			if ($goGatewayOutput->getErrorMessages()->getCount() > 0){

     				$Message = $Message."<br /><ul>";

     				for ($LoopIndex = 0; $LoopIndex < $goGatewayOutput->getErrorMessages()->getCount(); $LoopIndex++){

      					$Message = $Message."<li>".$goGatewayOutput->getErrorMessages()->getAt($LoopIndex)."</li>";

     				}

     				$Message = $Message."</ul>";

					$retCode = -2;

    			}

				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );

    			break;

   		default:

    			// unhandled status code 

    			$Message=$goGatewayOutput->getMessage();

				write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__.$Message );

				$retCode = -2;

    			break;

  		}



 	}





	$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, currency, useralias, uipass " .  "FROM cc_card " .  "WHERE id = '".$transaction_data[0][1]."'";

	$resmax = $DBHandle_max -> Execute($QUERY);
	if ($resmax) {
		$numrow = $resmax -> RecordCount();
	} else {
		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO SUCH CUSTOMER EXISTS, CUSTOMER ID = ".$transaction_data[0][1]);
		echo "<br>No Such Customer exists<br>";
		echo "<br><br>";
		$link = tep_href_link("userinfo.php","", 'SSL', false, false);
      		echo "<a href=\"$link\">Return to Account</a>";
		exit();

	}

	$customer_info = $resmax -> fetchRow();
	$nowDate = date("Y-m-d H:i:s");

	$pmodule = $transaction_data[0][4];
	$orderStatus = $retCode;
	if(empty($item_type)) $transaction_type='balance';
	else $transaction_type = $item_type;

	$currencies_list = get_currencies();
	$currAmount = $transaction_data[0][2];
	$currCurrency = BASE_CURRENCY;

	if(empty($transaction_data[0]['vat']) || !is_numeric($transaction_data[0]['vat'])) $VAT =0;
        else $VAT = $transaction_data[0]['vat'];

	$amount_paid = convert_currency($currencies_list, $currAmount, $currCurrency, BASE_CURRENCY);
	$amount_without_vat = $amount_paid / (1+$VAT/100);


	$Query = "SELECT id from cc_payments where customers_id = ". $transaction_data[0][1]. " AND orders_status = 0 AND  payment_method = 'iridium' AND $NOW_2MIN";
	$result = $DBHandle_max -> Execute($Query);
	$result->MoveLast();
	$id_array = $result->GetArray(1);
	$cc_payments_id = $id_array[0]['id'];

	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."- QUERY = $Query". " Result = $cc_payments_id");

	if($cc_payments_id < 1) {

		echo "<br>No Payments found<br>";
		echo "<br><br>";
		$link = tep_href_link("userinfo.php","", 'SSL', false, false);
                echo "<a href=\"$link\">Return to Account</a>";
		exit();	

	}

	

	$Query = "UPDATE cc_payments set orders_status = $orderStatus where id = $cc_payments_id";
	$result = $DBHandle_max -> Execute($Query);




//************************UPDATE THE CREDIT IN THE CARD***********************

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

	}



	if ($id > 0 ) {

		if (strcasecmp("invoice",$item_type)!=0) {

		

			$addcredit = $transaction_data[0][2]; 

			$instance_table = new Table("cc_card", "username, id");

			$param_update .= " credit = credit+'".$amount_without_vat."'";

			$FG_EDITION_CLAUSE = " id='$id'";

			$instance_table -> Update_table ($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);

			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Update_table cc_card : $param_update - CLAUSE : $FG_EDITION_CLAUSE");

		

			$field_insert = "date, credit, card_id, description";

			$value_insert = "'$nowDate', '".$amount_without_vat."', '$id', '".$transaction_data[0][4]."'";

			$instance_sub_table = new Table("cc_logrefill", $field_insert);

			$id_logrefill = $instance_sub_table -> Add_table ($DBHandle, $value_insert, null, null, 'id');

			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Add_table cc_logrefill : $field_insert - VALUES $value_insert");

		

			$field_insert = "date, payment, card_id, id_logrefill, description";

			$value_insert = "'$nowDate', '".$amount_paid."', '$id', '$id_logrefill', '".$transaction_data[0][4]."'";

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

			//Agent commision

			// test if this card have a agent

			$table_transaction = new Table();

			$result_agent = $table_transaction -> SQLExec($DBHandle,"SELECT cc_card_group.id_agent FROM cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card.id = $id");

		

			if (is_array($result_agent) && !is_null($result_agent[0]['id_agent']) && $result_agent[0]['id_agent']>0 ) {

				//test if the agent exist and get its commission

				$id_agent =  $result_agent[0]['id_agent'];

				$agent_table = new Table("cc_agent", "commission");

				$agent_clause = "id = ".$id_agent;

				$result_agent= $agent_table -> Get_list($DBHandle,$agent_clause);

			

				if(is_array($result_agent) && is_numeric($result_agent[0]['commission']) &&							$result_agent[0]['commission']>0) {

					$field_insert = "id_payment, id_card, amount,description,id_agent";

					$commission = ceil(($amount_without_vat * ($result_agent[0]['commission'])/100)*100)/100;

					$description_commission = gettext("AUTOMATICALY GENERATED COMMISSION!");

					$description_commission.= "\nID CARD : ".$id;

					$description_commission.= "\nID PAYMENT : ".$id_payment;

					$description_commission.= "\nPAYMENT AMOUNT: ".$amount_without_vat;

					$description_commission.= "\nCOMMISSION APPLIED: ".$result_agent[0]['commission'];

					$value_insert = "'".$id_payment."', '$id', '$commission','$description_commission','$id_agent'";

					$commission_table = new Table("cc_agent_commission", $field_insert);

					$id_commission = $commission_table -> Add_table ($DBHandle, $value_insert, null, null,"id");

				}

			}

		}

	}

//*************************END UPDATE CREDIT************************************



	$_SESSION["p_amount"] = null;

	$_SESSION["p_cardexp"] = null;

	$_SESSION["p_cardno"] = null;

	$_SESSION["p_cardtype"] = null;

	$_SESSION["p_module"] = null;

	$_SESSION["p_module"] = null;



	

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



	if ( ($orderStatus != 2)) {

		$url_forward = "checkout_payment.php?payment_error=iridium&error=The+payment+couldnt+be+proceed+correctly";

		if(!empty($item_id) && !empty($item_type)) $url_forward .= "&item_id=".$item_id."&item_type=".$item_type;

		Header ("Location: $url_forward");

		die();

	}



	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS  = ".$statusmessage);



	// CHECK IF THE EMAIL ADDRESS IS CORRECT

	if (preg_match("/^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$/", $customer_info["email"])) {

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

			//$mail->AddToMessage("\n\n\n\n"."-POST Var \n".print_r($_POST, true));

			$mail->setTitle("COPY FOR ADMIN : ".$mail->getTitle());

			$mail->send(ADMIN_EMAIL);

        

		} catch (A2bMailException $e) {

			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ERROR NO EMAIL TEMPLATE FOUND");

			write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID". $e);

		}

	

	} else {

		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." Customer : no email info !!!");

	}





	// load the after_process function from the payment modules

	//$payment_modules->after_process();

        echo "<br>$Message<br>";
        $link = tep_href_link("userinfo.php","", 'SSL', false, false);
        //echo "<a href=\"$link\">Return to Account</a>";

	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." EPAYMENT ORDER STATUS ID = ".$orderStatus." ".$statusmessage);

	write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__."-transactionID=$transactionID"." ----EPAYMENT TRANSACTION END----");



  ?>
