<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/C.lass.FormHandler.inc.php");
include ("../lib/epayment/includes/configure.php");
include ("../lib/epayment/classes/payment.php");
include ("../lib/epayment/classes/order.php");
include ("../lib/epayment/classes/currencies.php");
include ("../lib/epayment/includes/general.php");
include ("../lib/epayment/includes/html_output.php");
include ("../lib/epayment/includes/sessions.php");
include ("../lib/epayment/includes/loadconfiguration.php");
include ("../lib/agent.smarty.php");

//include ("./form_data/FG_var_callerid.inc");

if (! has_rights (ACX_ACCESS)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('amount','payment','authorizenet_cc_expires_year','authorizenet_cc_owner','authorizenet_cc_expires_month','authorizenet_cc_number',));

$_SESSION["p_module"] = $payment;
$_SESSION["p_amount"] = $amount;

$payment_modules = new payment($payment);

// #### HEADER SECTION


$smarty->display( 'main.tpl');
?>
<script language="javascript">
 function checkamount()
 {
 	if (document.checkout_amount.amount == "")
	{
		alert('Please enter some amount.');
		return false;
	}
	return true;
 }
 </script>
<?php
 
$form_action_url = tep_href_link("checkout_confirmation.php", '', 'SSL');
  

echo tep_draw_form('checkout_amount', $form_action_url, 'post', 'onsubmit="checkamount()"');
?>


<br>
<center>
<?php 
	echo $SPOT[strtoupper($payment)];
?>
<br>

<?php echo gettext("Payment Method");?>:&nbsp;
<?php echo strtoupper($payment)?>
</center>
<br>

<input type="hidden" name="payment" value="<?php echo $payment?>">
<input type="hidden" name="authorizenet_cc_expires_year" value="<?php echo $authorizenet_cc_expires_year?>">
<input type="hidden" name="authorizenet_cc_owner" value="<?php echo $authorizenet_cc_owner?>">
<input type="hidden" name="authorizenet_cc_expires_month" value="<?php echo $authorizenet_cc_expires_month?>">
<input type="hidden" name="authorizenet_cc_number" value="<?php echo $authorizenet_cc_number?>">
<input type="hidden" name="authorizenet_cc_expires_year" value="<?php echo $authorizenet_cc_expires_year?>">

<table width=80% align=center class="infoBox">
<tr height="15">
    <td colspan=2 class="infoBoxHeading">&nbsp;<?php echo gettext("Please enter the order amount")?>;</td>
</tr>
<tr>
    <td width=50%>&nbsp;</td>
    <td width=50%>&nbsp;</td>
</tr>
<tr>
    <td align=right><?php echo gettext("Total Amount")?>: &nbsp;</td>
    <td align=left><select name="amount" class="form_input_select" style="width:60px;" >
	<?php 
	$arr_purchase_amount = split(":", EPAYMENT_PURCHASE_AMOUNT);
			if (!is_array($arr_purchase_amount)) $arr_purchase_amount[0]=10;

			foreach($arr_purchase_amount as $value){
	?>
	<option value="<?php echo $value?>"><?php echo $value?></option>
	
	<?php }?></select>
	&nbsp;<?php echo $payment_modules->get_CurrentCurrency();?></td>
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
            <input type="image" src="<?php echo Images_Path;?>/button_continue.gif" alt="Continue" border="0" title="Continue" onFocus="checkamount()">
             &nbsp;</td>
          </tr>
 </table>
</form>


<br>
<?php if (strtoupper($payment)=='PAYPAL') { ?>
<table width="70%" align="center">
	<tr>
		<TD  valign="top" align="center" class="tableBodyRight" background="<?php echo Images_Path; ?>/background_cells.gif" >
			<?php echo gettext('The fee from $5 is $0.45, from $10 is $0.59, from $20 is $0.88, from $40 is $1.46.').' <br><font class="fontstyle_002">'.gettext('Paypal Fee Calculator').'</font>';?> <a target="_blank" href="http://www.ppcalc.com/">http://www.ppcalc.com/</a></b>
		</TD>
	</tr>
</table>
<?php } ?>

<?php 
// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
?>
