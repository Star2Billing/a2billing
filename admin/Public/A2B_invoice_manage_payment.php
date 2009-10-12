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


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
include ("../lib/support/classes/invoice.php");
include ("../lib/support/classes/invoiceItem.php");

if (! has_rights (ACX_INVOICING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('id','addpayment','delpayment','status'));

if (empty($id)) {
	Header ("Location: A2B_entity_invoice.php?atmenu=payment&section=13");
}

$invoice = new invoice($id);
$items = $invoice->loadItems();

if(isset($addpayment) && is_numeric($addpayment)) {
	$invoice ->addPayment($addpayment);
	Header ("Location: A2B_invoice_manage_payment.php?id=$id");
}

if(isset($delpayment) && is_numeric($delpayment)) {
	$invoice ->delPayment($delpayment);
	Header ("Location: A2B_invoice_manage_payment.php?id=$id");
}

if(isset($status) && is_numeric($status)) {
	$invoice ->changeStatus($status);
	Header ("Location: A2B_invoice_manage_payment.php?id=$id");
}
$smarty->display('main.tpl');


$payments = $invoice->loadPayments();

$price_without_vat = 0;
$price_with_vat = 0;
$vat_array = array();
foreach ($items as $item) {
	$price_without_vat = $price_without_vat + $item->getPrice();
	$price_with_vat = $price_with_vat + ($item->getPrice()*(1+($item->getVAT()/100)));
	if(array_key_exists("".$item->getVAT(),$vat_array)) {
		$vat_array[$item->getVAT()] = $vat_array[$item->getVAT()] + $item->getPrice()*($item->getVAT()/100) ;
	} else {
		$vat_array[$item->getVAT()] =  $item->getPrice()*($item->getVAT()/100) ;
	}
} 
$payment_assigned = 0;
foreach ($payments as $payment) {
	$payment_assigned = $payment_assigned + $payment['payment'];
}

?>

<SCRIPT LANGUAGE="javascript">
<!-- Begin
var win= null;
function addpayment(selvalue){
	//test si win est encore ouvert et close ou refresh
    win=MM_openBrWindow('A2B_entity_payment_invoice.php?popup_select=1&invoice=<?php echo $id ?>&card=<?php echo $invoice->getCard() ?>','','scrollbars=yes,resizable=yes,width=700,height=500');
}
function delpayment(){
	//test si val is not null & numeric
	if($('#payment').val()!=null){
		self.location.href= "A2B_invoice_manage_payment.php?id=<?php echo $id; ?>&delpayment="+$('#payment').val();
	}
}

function changeStatus(){
	self.location.href= "A2B_invoice_manage_payment.php?id=<?php echo $id; ?>&status=<?php echo ($invoice->getPaidStatus()+1)%2; ?>";
}
// End -->

</script>
<table class="invoice_table" >
	<tr class="form_invoice_head">
	    <td width="75%"><font color="#FFFFFF"><?php echo gettext("INVOICE: "); ?></font><font color="#FFFFFF"><b><?php echo $invoice->getTitle();  ?></b></font></td>
	    <td width="25%"><font color="#FFFFFF"><?php echo gettext("REF: "); ?> </font><font color="#EE6564"> <?php echo $invoice->getReference(); ?></font></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<a href="javascript:;" onClick="MM_openBrWindow('A2B_invoice_view.php?popup_select=1&id=<?php echo $id ?>','','scrollbars=yes,resizable=yes,width=700,height=500')" > <img src="../Public/templates/default/images/page_white_text.png" title="Print" alt="Print" border="0"></a>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php echo gettext("TOTAL INVOICE EXCLUDE TVA"); ?>&nbsp;:&nbsp;<?php echo number_format(round($price_without_vat,2),2)." ".strtoupper(BASE_CURRENCY); ?>
		</td>
	</tr>
    <?php foreach ($vat_array as $key => $val) { ?>
     <tr>
    	 <td  colspan="2">
			<?php echo gettext("TOTAL VAT ($key%)") ?>&nbsp;:&nbsp;<?php echo number_format(round($val,2),2)." ".strtoupper(BASE_CURRENCY); ?>
		</td>	
    <?php } ?>
    <tr>
	<td colspan="2">
			<?php echo gettext("TOTAL INVOICE INCLUDE TVA"); ?>&nbsp;:&nbsp;<?php echo number_format(round($price_with_vat,2),2)." ".strtoupper(BASE_CURRENCY); ?>
		</td>
	</tr>
	
	<tr>
		<td  colspan="2">
			<?php echo gettext("TOTAL OF PAYMENTS ASSIGNED"); ?>&nbsp;:&nbsp;<?php echo number_format(round($payment_assigned,2),2)." ".strtoupper(BASE_CURRENCY); ?>
		</td>
	</tr>
	
	<tr>
		<td align="center" colspan="2">
			<br/>
			<table>
				<tr>
					<td align="center">
						<?php echo gettext("PAYMENTS ASSIGNED"); ?>
					</td>
				</tr>
				<tr>
					<td>
						<select id="payment" name="payment" size="5" style="width:250px;" class="form_input_select">
						    <?php foreach ($payments as $payment){ ?>
							<option value="<?php echo $payment['id'] ?>"  ><?php echo substr($payment['date'],0,10);?>&nbsp;:&nbsp;<?php echo $payment['payment']." ".strtoupper(BASE_CURRENCY); ?>&nbsp;&nbsp;<?php echo "(id : ".$payment['id'].")";?> </option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="center">
						<a href="javascript:;" onClick="addpayment()" > <img src="../Public/templates/default/images/add.png" title="Add Payment" alt="Add Payment" border="0"></a>
						<a href="javascript:;" onClick="delpayment()" > <img src="../Public/templates/default/images/del.png" title="Del Payment" alt="Del Payment" border="0"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<br/>
		<?php if($invoice->getPaidStatus()==0) $color="color:#EE6564;";
		 	   else $color="color:#5FA631;"    ?>
		 <font style="font-weight:bold;" ><?php echo gettext("PAID STATUS : "); ?></font> <font style="<?php echo $color; ?>" > <?php echo $invoice->getPaidStatusDisplay($invoice->getPaidStatus());  ?> </font>
		 &nbsp;&nbsp;<input class="form_input_button" type="button" onClick="changeStatus();" value="<?php echo gettext("CHANGE STATUS") ?>"/>
		</td>
	</tr>
</table>


<?php



// #### FOOTER SECTION
$smarty->display('footer.tpl');
