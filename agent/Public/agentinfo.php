<?php
include ("lib/agent.defines.php");
include ("lib/agent.module.access.php");
include ("lib/agent.smarty.php");


if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


$QUERY = "SELECT  credit, currency, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, id FROM cc_agent WHERE login = '".$_SESSION["pr_login"]."' AND passwd = '".$_SESSION["pr_password"]."'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$agent_info =$resmax -> fetchRow();


$currencies_list = get_currencies();

if (!isset($currencies_list[strtoupper($agent_info [1])][2]) || !is_numeric($currencies_list[strtoupper($agent_info [1])][2])) $mycur = 1;
else $mycur = $currencies_list[strtoupper($agent_info [1])][2];
$credit_cur = $agent_info[1] / $mycur;
$credit_cur = round($credit_cur,3);


$smarty->display( 'main.tpl');
?>



<table width="90%" class="tablebackgroundblue" align="center">
<tr>
	<td><img src="<?php echo KICON_PATH ?>/personal.gif" align="left" class="kikipic"/></td>
	<td width="50%"><font class="fontstyle_002">
	<?php echo gettext("LAST NAME");?> :</font>  <font class="fontstyle_007"><?php echo $agent_info[2]; ?></font>
	<br/><font class="fontstyle_002"><?php echo gettext("FIRST NAME");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[3]; ?></font>
	<br/><font class="fontstyle_002"><?php echo gettext("EMAIL");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[10]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("PHONE");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[9]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("FAX");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[11]; ?></font> 
	</td>
	<td width="50%">
	<font class="fontstyle_002"><?php echo gettext("ADDRESS");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[4]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("ZIP CODE");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[8]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("CITY");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[5]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("STATE");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[6]; ?></font> 
	<br/><font class="fontstyle_002"><?php echo gettext("COUNTRY");?> :</font> <font class="fontstyle_007"><?php echo $agent_info[7]; ?></font> 
	</td>
</tr>

</table>

<br>
<table width="100%" align="center" >
<tr>
	<td align="center">
		<table width="80%" align="center" class="tablebackgroundcamel">
		<tr>
			<td><img src="<?php echo KICON_PATH ?>/gnome-finance.gif" class="kikipic"/></td>
			<td width="50%">
			<br><font class="fontstyle_002"><?php echo gettext("AGENT ID");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[12]; ?></font>
			<br></br>
			</td>
			<td width="50%">
			<br><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$agent_info[1]; ?> </font>
			<br></br>
			</td>
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






<?php 

}else{ 
	echo '<br></br><br></br>';
} 

$smarty->display( 'footer.tpl');

?>