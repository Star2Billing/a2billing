<?php
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");


if (! has_rights (ACX_ACCESS)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, " .
		"freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer, cc_card.id, currency FROM cc_card " .
		"LEFT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff LEFT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer " .
		"LEFT JOIN cc_card_group ON cc_card_group.id=cc_card.id_group WHERE username = '".$_SESSION["pr_login"].
		"' AND uipass = '".$_SESSION["pr_password"]."'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) {
	echo gettext("Error loading your account information!");
	exit();
}
$customer_info =$resmax -> fetchRow();

if($customer_info [14] != "1" ) {
	exit();
}

$customer = $_SESSION["pr_login"];

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid'));

$currencies_list = get_currencies();

$two_currency = false;
if (!isset($currencies_list[strtoupper($customer_info [22])][2]) || !is_numeric($currencies_list[strtoupper($customer_info [22])][2])){
	$mycur = 1; 
}else{ 
	$mycur = $currencies_list[strtoupper($customer_info [22])][2];
	$display_currency =strtoupper($customer_info [22]);
	if(strtoupper($customer_info [22])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}
$credit_cur = $customer_info[1] / $mycur;
$credit_cur = round($credit_cur,3);



$smarty->display( 'main.tpl');

?>

<div>

<?php if ($A2B->config["webcustomerui"]['customerinfo']){ ?>

<table  class="tablebackgroundblue" align="center" >
<tr>
	<td><img src="<?php echo KICON_PATH ?>/personal.gif" align="left" class="kikipic"/></td>
	<td width="50%"><font class="fontstyle_002">
	<?php echo gettext("LAST NAME");?> :</font>  <font class="fontstyle_007"><?php echo $customer_info[2]; ?></font>
	<br/><font class="fontstyle_002"><?php echo gettext("FIRST NAME");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[3]; ?></font>
	<br/><font class="fontstyle_002"><?php echo gettext("EMAIL");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[10]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("PHONE");?> :</font><font class="fontstyle_007"> <?php echo $customer_info[9]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("FAX");?> :</font><font class="fontstyle_007"> <?php echo $customer_info[11]; ?></font> 
	</td>
	<td width="50%">
	<font class="fontstyle_002"><?php echo gettext("ADDRESS");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[4]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("ZIP CODE");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[8]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("CITY");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[5]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("STATE");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[6]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("COUNTRY");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[7]; ?></font> 
	</td>
</tr>

<tr>
	<td></td>
	<td align="right">
		<?php if ($_SESSION["cc_voicemail"]){ ?>
		<a href="../ARI/"><span class="cssbutton"><font color="red"><?php echo gettext("GO TO VOICEMAIL");?></font></span></a>
		<?php } ?>
	</td>
	<td align="right">
		<?php if ($A2B->config["webcustomerui"]['personalinfo']){ ?>
		<a href="A2B_entity_card.php?atmenu=password&form_action=ask-edit&stitle=Personal+Information"><span class="cssbutton"><font color="red"><?php echo gettext("EDIT PERSONAL INFORMATION");?></font></span></a>
		<?php } ?>
	</td>
</tr>
</table>

<?php } ?>
<br>
<table style="width:70%;margin:0 auto;"  align="center" >
<tr>
	<td align="center">
		<table width="80%" align="center" class="tablebackgroundcamel">
		<tr>
			<td><img src="<?php echo KICON_PATH ?>/gnome-finance.gif" class="kikipic"/></td>
			<td width="50%">
			<br><font class="fontstyle_002"><?php echo gettext("CARD NUMBER");?> :</font><font class="fontstyle_007"> <?php echo $customer_info[0]; ?></font>
			<br></br>
			</td>
			<td width="50%">
			<br><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$customer_info[22]; ?> </font>
			<br></br>
			</td>
			<?php if ($customer_info[15]>0) {
				$freetimetocall_used = $A2B->FT2C_used_seconds($DBHandle_max, $customer_info[21], $customer_info[20], $customer_info[18], $customer_info[19]);?>
			</tr><tr><td /><td width="50%">
			<font class="fontstyle_002"><?php echo gettext("CALLING PACKAGE");?> :</font><br><font class="fontstyle_007"> <?php echo $customer_info[16]; ?> </font>
			</td>
			<td width="50%">
			<font class="fontstyle_002"><?php if (($customer_info[17]==0) || ($customer_info[17]==1)) {	
					echo gettext("PACKAGE MINUTES REMAINING");?> :</font><br><font class="fontstyle_007"> <?php printf ("%d:%02d of %d:%02d",intval(($customer_info[15]-$freetimetocall_used) / 60),($customer_info[15]-$freetimetocall_used) % 60,intval($customer_info[15]/60),$customer_info[15] % 60);
				} else {
					echo gettext("PACKAGE MINUTES USED");?> :</font><br><font class="fontstyle_007"> <?php printf ("%d:%02d",intval($freetimetocall_used / 60),$freetimetocall_used % 60);
				}?> </font>
			</tr><tr><td /><td width="50%" /><td width="50%" />
			<?php }?>
			<td valign="bottom" align="right"><img src="<?php echo KICON_PATH ?>/help_index.gif" class="kikipic"></td>
		</tr>
		</table>
	</td>
</tr>
</table>


<?php if ($A2B->config["epayment_method"]['enable']){ ?>

<br>

<?php 
	echo $PAYMENT_METHOD;
?>

<table style="width:80%;margin:0 auto;" cellspacing="0"  align="center" >
	<tr background="<?php echo Images_Path; ?>/background_cells.gif" >
		<TD  valign="top" align="right" class="tableBodyRight"   >
			<font size="2"><?php echo gettext("Click below to buy credit : ");?> </font>
		</TD>
		<td class="tableBodyRight" >	
			<?php
			$arr_purchase_amount = split(":", EPAYMENT_PURCHASE_AMOUNT);
			if (!is_array($arr_purchase_amount)){
				$to_echo = 10;
			}else{
				if($two_currency){
				$purchase_amounts_convert= array();
				for($i=0;$i<count($arr_purchase_amount);$i++){
					$purchase_amounts_convert[$i]=round($arr_purchase_amount[$i]/$mycur,2);
				}
				$to_echo = join(" - ", $purchase_amounts_convert);
			
				echo $to_echo;
			?>
			<font size="2">
			<?php echo $display_currency; ?> </font>
			<br/>
			<?php } ?>
			<?php echo join(" - ", $arr_purchase_amount); ?>
			<font size="2"><?php echo strtoupper(BASE_CURRENCY);?> </font>
			<?php } ?>
			
		</TD>
	</tr>
	
	<tr>
		<td align="center" colspan="2" class="tableBodyRight" >	
			<form action="checkout_payment.php" method="post">
				
				<input type="submit" class="form_input_button" value="<?php echo gettext("BUY NOW");?>">
			</form>
	</tr>
</table>






<?php } else { ?>
<br></br><br></br>

<?php } ?>
</div>

<?php  

$smarty->display( 'footer.tpl');


