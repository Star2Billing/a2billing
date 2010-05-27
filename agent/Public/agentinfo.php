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
include ("../lib/agent.module.access.php");
include ("../lib/agent.smarty.php");

if (! has_rights (ACX_ACCESS)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


$QUERY = "SELECT  credit, currency, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, id, com_balance FROM cc_agent WHERE login = '".$_SESSION["pr_login"]."' AND passwd = '".$_SESSION["pr_password"]."'";
$table_remittance = new Table("cc_remittance_request",'*');
$remittance_clause = "id_agent = ".$_SESSION['agent_id']." AND status = 0";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$agent_info =$resmax -> fetchRow();


$currencies_list = get_currencies();

$two_currency = false;
if (!isset($currencies_list[strtoupper($agent_info [1])][2]) || !is_numeric($currencies_list[strtoupper($agent_info [1])][2])){
	$mycur = 1; 
}else{ 
	$mycur = $currencies_list[strtoupper($agent_info [1])][2];
	$display_currency =strtoupper($agent_info [1]);
	if(strtoupper($agent_info [1])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}
$credit_cur = $agent_info[0] / $mycur;
$credit_cur = round($credit_cur,3);

$result_remittance = $table_remittance -> Get_list($DBHandle_max,$remittance_clause);
if(is_array($result_remittance) && sizeof($result_remittance)>=1 ){
	$remittance_in_progress=true;
	$remittance_value = $result_remittance[0]['amount'];
}else{
	$remittance_in_progress=false;
}
$remittance_value_cur = $remittance_value/$mycur;
$commision_bal_cur  =  $agent_info[13] / $mycur;
$commision_bal_cur = round($commision_bal_cur,3);
$smarty->display( 'main.tpl');
?>


<div>
<table  class="tablebackgroundblue"  align="center">
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
<tr>
	<td></td>
	<td>
	 	&nbsp;	
	</td>
	<td align="right">
		<?php if ($A2B->config["webagentui"]['personalinfo']){ ?>
		<a href="A2B_entity_agent.php?atmenu=password&form_action=ask-edit&stitle=Personal+Information"><span class="cssbutton"><font color="red"><?php echo gettext("EDIT PERSONAL INFORMATION");?></font></span></a>
		<?php } ?>
	</td>
</tr>
</table>
	
<br>
<table style="width:90%;margin:0 auto;" align="center">
<tr>
	<td align="center">
		<table width="80%" align="center" class="tablebackgroundcamel">
		<tr>
			<td rowspan="2"><img src="<?php echo KICON_PATH ?>/gnome-finance.gif" class="kikipic"/></td>
			<td width="50%">
			<br><font class="fontstyle_002"><?php echo gettext("AGENT ID");?> :</font><font class="fontstyle_007"> <?php echo $agent_info[12]; ?></font>
			<br></br>
			</td>
			<td width="50%">
			<br/><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$agent_info[1]; ?> </font>
			
			</td>
			<td valign="bottom" align="right" rowspan="2"  ><img src="<?php echo KICON_PATH ?>/help_index.gif" class="kikipic"></td>
		</tr>
		<tr>
			<td>
				<?php  if($remittance_in_progress){?>
					<font class="fontstyle_002"><?php echo gettext("REMITTANCE IN PROGRESS");?> :</font><font class="fontstyle_007"> <?php echo $remittance_value_cur.' '.$agent_info[1]; ?> </font>
				<?php }else{?>
					&nbsp;
				<?php }?>
			</td>
			<td width="50%">
				<font class="fontstyle_002"><?php echo gettext("COMMISSION ACCRUED");?> :</font><font class="fontstyle_007"> <?php echo $commision_bal_cur.' '.$agent_info[1]; ?> </font>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<?php if ($A2B->config["webagentui"]['remittance_request'] && !$remittance_in_progress && $commision_bal_cur>0){ ?>
<div style="width:70%;margin:0 auto;text-align:center;">
	<a href="A2B_agent_remittance_req.php"><span class="form_input_button"><?php echo gettext("REMITTANCE REQUEST");?></span></a>
</div>
<?php 
}
if ($A2B->config["epayment_method"]['enable']){ ?>

<br>

<?php 
	echo $PAYMENT_METHOD;
?>

<table style="width:70%;margin:0 auto;" cellspacing="0" align="center" >
	<tr background="<?php echo Images_Path; ?>/background_cells.gif" >
		<TD  valign="top" align="right" class="tableBodyRight"   >
			<font size="2"><?php echo gettext("Click below to buy credit : ");?> </font>
		</TD>
		<td class="tableBodyRight" >	
			<?php
			$arr_purchase_amount = preg_split("/:/", EPAYMENT_PURCHASE_AMOUNT);
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
				
				<input type="submit" class="form_input_button" value="BUY NOW">
			</form>
	</tr>
</table>



<?php 

}else{ 
	echo '<br></br><br></br>';
} 
?>
</div>
<?php 
$smarty->display( 'footer.tpl');

?>
