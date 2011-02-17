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
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./lib/epayment/includes/configure.php");
include ("./lib/epayment/classes/payment.php");
include ("./lib/epayment/classes/order.php");
include ("./lib/epayment/classes/currencies.php");
include ("./lib/epayment/includes/general.php");
include ("./lib/epayment/includes/html_output.php");
include ("./lib/epayment/includes/loadconfiguration.php");
include ("./lib/customer.smarty.php");


if (! has_rights (ACX_ACCESS)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$currencies_list = get_currencies();
$two_currency = false;
if (!isset($currencies_list[strtoupper($_SESSION['currency'])][2]) || !is_numeric($currencies_list[strtoupper($_SESSION['currency'])][2])) {
	$mycur = 1; 
} else { 
	$mycur = $currencies_list[strtoupper($_SESSION['currency'])][2];
	$display_currency =strtoupper($_SESSION['currency']);
	if(strtoupper($_SESSION['currency'])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}

$vat=$_SESSION["vat"];

getpost_ifset(array('amount','payment','authorizenet_cc_expires_year','authorizenet_cc_owner','authorizenet_cc_expires_month','authorizenet_cc_number','authorizenet_cc_expires_year'));
// PLUGNPAY
getpost_ifset(array('credit_card_type', 'plugnpay_cc_owner', 'plugnpay_cc_number', 'plugnpay_cc_expires_month', 'plugnpay_cc_expires_year', 'cvv'));
//Iridium
getpost_ifset(array('CardName', 'CardNumber', 'ExpiryDateMonth', 'ExpiryDateYear', 'CV2'));
// Invoice
getpost_ifset(array('item_id','item_type'));

$vat_amount= $amount*$vat/100;
$total_amount = $amount+($amount*$vat/100);
if (!isset($item_id) || is_null($item_id) || $item_id == "") {
	$item_id = 0;
}

if (!isset($item_type) || is_null($item_type)) {
	$item_type = '';
}

$HD_Form = new FormHandler("cc_payment_methods","payment_method");

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$_SESSION["p_module"] = $payment;
$_SESSION["p_amount"] = 3;


$paymentTable = new Table();
$time_stamp = date("Y-m-d H:i:s"); 
$amount_string = sprintf("%.3F", $total_amount);

if (strtoupper($payment)=='PLUGNPAY') {
	$QUERY_FIELDS = "cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, cvv, credit_card_type, currency , item_id , item_type";
	$QUERY_VALUES = "'".$_SESSION["card_id"]."','$amount_string', '".$_SESSION["vat"]."', '$payment','$plugnpay_cc_owner','".substr($plugnpay_cc_number,0,4)."XXXXXXXXXXXX','".$plugnpay_cc_expires_month."-".$plugnpay_cc_expires_year."','$time_stamp', '$cvv', '$credit_card_type', '".BASE_CURRENCY."' , '$item_id', '$item_type'";
} else if(strtoupper($payment)=='IRIDIUM'){
	$QUERY_FIELDS = "cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, currency, item_id, item_type";
	$QUERY_VALUES = "'".$_SESSION["card_id"]."','$amount_string', '".$_SESSION["vat"]."', '$payment','$CardName','".substr($CardNumber,0,4)."XXXXXXXXXXXX','".$ExpiryDateMonth."-".$ExpiryDateYear."','$time_stamp', '".BASE_CURRENCY."' , '$item_id','$item_type'";
} else {
	$QUERY_FIELDS = "cardid, amount, vat, paymentmethod, cc_owner, cc_number, cc_expires, creationdate, currency, item_id, item_type";
        $QUERY_VALUES = "'".$_SESSION["card_id"]."','$amount_string', '".$_SESSION["vat"]."', '$payment','$authorizenet_cc_owner','".substr($authorizenet_cc_number,0,4)."XXXXXXXXXXXX','".$authorizenet_cc_expires_month."-".$authorizenet_cc_expires_year."','$time_stamp', '".BASE_CURRENCY."' , '$item_id','$item_type'";
}

$transaction_no = $paymentTable->Add_table ($HD_Form -> DBHandle, $QUERY_VALUES, $QUERY_FIELDS, 'cc_epayment_log', 'id');

$key = securitykey(EPAYMENT_TRANSACTION_KEY, $time_stamp."^".$transaction_no."^".$amount_string."^".$_SESSION["card_id"]."^".$item_id."^".$item_type);
if (empty($transaction_no)) {
	exit(gettext("No Transaction ID found"));
}

$HD_Form -> create_toppage ($form_action);

$payment_modules = new payment($payment);
$order = new order($amount_string);

if (is_array($payment_modules->modules)) {
	$payment_modules->pre_confirmation_check();
}

// #### HEADER SECTION
$smarty->display( 'main.tpl');


if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
} else {
    $form_action_url = tep_href_link("checkout_process.php", '', 'SSL');
}

echo tep_draw_form('checkout_confirmation.php', $form_action_url, 'post', null, $payment);

if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button($transaction_no, $key);    
}
?>

<br><br>
<center>
<table width=80% align=center class="infoBox">
<tr height="15">
    <td colspan=2 class="infoBoxHeading">&nbsp;<?php echo gettext("Please confirm your order")?></td>
</tr>
<tr>
    <td width=50%>&nbsp;</td>
    <td width=50%>&nbsp;</td>
</tr>
<tr>
    <td width=50%><div align="right"><?php echo gettext("Payment Method");?>:&nbsp;</div></td>
    <td width=50%><?php echo strtoupper($payment)?></td>
</tr>
<?php if(strcasecmp("invoice",$item_type)!=0){?>
<tr>
    <td align=right><?php echo gettext("Amount")?>: &nbsp;</td>
    <td align=left>
    <?php
     echo round($amount,2)." ".strtoupper(BASE_CURRENCY);
     if($two_currency){
					echo " - ".round($amount/$mycur,2)." ".strtoupper($_SESSION['currency']);	
	 }	
     ?> </td>
</tr>
<tr>
    <td align=right><?php echo gettext("VAT")."(".$vat."%)"?>: &nbsp;</td>
    <td align=left>
    <?php
     echo round($vat_amount,2)." ".strtoupper(BASE_CURRENCY);
     if($two_currency){
					echo " - ".round($vat_amount/$mycur,2)." ".strtoupper($_SESSION['currency']);	
	 }	
     ?> </td>
</tr>
<?php } ?>
<tr>
    <td align=right><?php echo gettext("Total Amount Incl. VAT")?>: &nbsp;</td>
    <td align=left>
    <?php
     echo round($total_amount,2)." ".strtoupper(BASE_CURRENCY);
     if($two_currency){
					echo " - ".round($total_amount/$mycur,2)." ".strtoupper($_SESSION['currency']);	
	 }	
     ?> </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>
</table>
<br>
<table class="infoBox" width="80%" cellspacing="0" cellpadding="2" align=center>
   <tr height="25">
   <td  align=left class="main"> <b><?php echo gettext("Please click button to confirm your order")?>.</b>
   </td>
          <td align=right halign=center >
            <input type="image" src="<?php echo Images_Path;?>/button_confirm_order.gif" alt="Confirm Order" border="0" title="Confirm Order">
             &nbsp;</td>
          </tr>
</table>
</form>
</center>


<?php

// #### FOOTER SECTION
$smarty->display( 'footer.tpl');

