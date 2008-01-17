<?php
include ("lib/defines.php");
include ("lib/module.access.php");
include ("lib/smarty.php");


if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}


$QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer, cc_card.id FROM cc_card RIGHT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff LEFT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$customer_info =$resmax -> fetchRow();

if($customer_info [14] != "1" ) {
	exit();
}

$customer = $_SESSION["pr_login"];

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid'));

$currencies_list = get_currencies();

if (!isset($currencies_list[strtoupper($customer_info [14])][2]) || !is_numeric($currencies_list[strtoupper($customer_info [14])][2])) $mycur = 1;
else $mycur = $currencies_list[strtoupper($customer_info [14])][2];
$credit_cur = $customer_info[1] / $mycur;
$credit_cur = round($credit_cur,3);



$smarty->display( 'main.tpl');
	
?>


<?php if ($A2B->config["webcustomerui"]['customerinfo']){ ?>

<table width="90%" class="tablebackgroundblue" align="center">
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
<?php if ($A2B->config["webcustomerui"]['personalinfo']){ ?>
<tr>
	<td></td>
	<td></td>
	<td align="right">
	<a href="A2B_entity_card.php?atmenu=password&form_action=ask-edit&stitle=Personal+Information"><span class="cssbutton"><font color="red"><?php echo gettext("EDIT PERSONAL INFORMATION");?></font></span></a>
	</td>
</tr>
<?php } ?>
</table>

<?php } ?>
<br>
<table width="100%" align="center" >
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
			<br><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$customer_info[14]; ?> </font>
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

<table width="70%" align="center">
	<tr>
		<TD  valign="top" align="center" class="tableBodyRight" background="<?php echo Images_Path; ?>/background_cells.gif" >
			<font size="2"><?php echo gettext("Click below to buy credit : ");?> </font>
			
			<?php
			$arr_purchase_amount = split(":", EPAYMENT_PURCHASE_AMOUNT);
			if (!is_array($arr_purchase_amount)){
				$to_echo = 10;
			}else{
				$to_echo = join(" - ", $arr_purchase_amount);
			}
			echo $to_echo;
			?>
			<font size="2"><?php echo strtoupper(BASE_CURRENCY);?> </font>
			
			<form action="checkout_payment.php" method="post">
				
				<input type="submit" class="form_input_button" value="BUY NOW">
			</form>
		</TD>
	</tr>
</table>






<?php }else{ ?>
<br></br><br></br>

<?php } ?>
<?php
	$smarty->display( 'footer.tpl');
?>
