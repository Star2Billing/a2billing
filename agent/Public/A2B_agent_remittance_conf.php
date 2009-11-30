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

if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

if (!$A2B->config["webagentui"]['remittance_request']){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}
getpost_ifset(array('amount','remittance_type','action'));

$QUERY = "SELECT  credit, currency,com_balance,threshold_remittance,firstname,lastname,address,bank_info FROM cc_agent WHERE id = ".$_SESSION['agent_id'];
$table_remittance = $table_remittance = new Table("cc_remittance_request",'*');
$remittance_clause = "id_agent = ".$_SESSION['agent_id']." AND status = 0";

$DBHandle_max = DbConnect();
$numrow = 0;
//echo $QUERY;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$agent_info =$resmax -> fetchRow();
$currencies_list = get_currencies();
$two_currency = false;
if (!isset($currencies_list[strtoupper($agent_info ['currency'])][2]) || !is_numeric($currencies_list[strtoupper($agent_info ['currency'])][2])){
	$mycur = 1; 
}else{ 
	$mycur = $currencies_list[strtoupper($agent_info ['currency'])][2];
	$display_currency =strtoupper($agent_info ['currency']);
	if(strtoupper($agent_info ['currency'])!=strtoupper(BASE_CURRENCY))$two_currency=true;
}
$credit_cur = $agent_info['credit'] / $mycur;
$credit_cur = round($credit_cur,3);
$commision_bal_cur  =  $agent_info['com_balance'] / $mycur;
$commision_bal_cur = round($commision_bal_cur,3);
$threshold_cur  =  $agent_info['threshold-remittance'] / $mycur;
$threshold_cur = round($threshold_cur,3);
$smarty->display( 'main.tpl');
$table_remittance = $table_remittance = new Table("cc_remittance_request",'*');
$remittance_clause = "id_agent = ".$_SESSION['agent_id']." AND status = 0";
$result_remittance = $table_remittance -> Get_list($DBHandle_max,$remittance_clause);
if(is_array($result_remittance) && sizeof($result_remittance)>=1 ){
	$remittance_in_progress=true;
	$remittance_value = $result_remittance[0]['amount'];
}else{
	$remittance_in_progress=false;
}
$remittance_value_cur = $remittance_value/$mycur;
if(!$remittance_in_progress){
	$err_msg='';
	if($two_currency){
		$amount_gobal_cur = a2b_round($amount * $mycur); 
		$amount_rounded = a2b_round($amount_gobal_cur / $mycur);
	}else{
		$amount_rounded = $amount_gobal_cur = a2b_round($amount);
	}
	
	if($amount_rounded<$threshold_cur){
		$err_msg=gettext("Invalid amount, is higher than the threshold to authorize a remittance");
	}
	if($amount_rounded>$commision_bal_cur){
		$err_msg=gettext("Invalid amount, is higher than your commission Accrued");
	}
	
	if($action=="add" && empty($err_msg)){
		if($remittance_type == "BANK") $type =1;
		else $type =0;
		$table_remittance = new Table("cc_remittance_request");
		$fields = "id_agent,amount,type";
		$values =  $_SESSION['agent_id'].",'$amount_gobal_cur',$type";
		$id= $table_remittance -> Add_table($DBHandle_max,$values,$fields,"cc_remittance_request","id");
		if(is_numeric($id) && $id>0) $insert= true;
		else $insert = false;
		NotificationsDAO :: AddNotification("remittance_added_agent", Notification :: $MEDIUM, Notification :: $AGENT, $_SESSION['agent_id'],Notification::$LINK_REMITTANCE,$id);
	}
}
?>

<table style="width:90%;margin:0 auto;" align="center">
<tr>
	<td align="center">
		<table width="80%" align="center" class="tablebackgroundcamel">
		<tr>
			<td rowspan="2"><img src="<?php echo KICON_PATH ?>/gnome-finance.gif" class="kikipic"/></td>
			
			<td width="50%">
			<br/><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$agent_info['currency']; ?> </font>
			
			</td>
			<td width="50%">
			<br/><font class="fontstyle_002"><?php echo gettext("THRESHOLD REMITTANCE REQUEST");?> :</font><font class="fontstyle_007"> <?php echo $threshold_cur.' '.$agent_info['currency']; ?> </font>
			
			</td>
		</tr>
		<tr>
			
			<td width="50%">
				<font class="fontstyle_002"><?php echo gettext("COMMISSION ACCRUED");?> :</font><font class="fontstyle_007"> <?php echo $commision_bal_cur.' '.$agent_info['currency']; ?> </font>
			</td>
			<td width="50%">
				<?php  if($remittance_in_progress){?>
					<font class="fontstyle_002"><?php echo gettext("REMITTANCE IN PROGRESS");?> :</font><font class="fontstyle_007"> <?php echo $remittance_value_cur.' '.$agent_info[1]; ?> </font>
				<?php }else{?>
					&nbsp;
				<?php }?>		
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br/>
<?php if(!$remittance_in_progress){?>
<?php
if(!empty($err_msg)){
?>
<div class="msg_error">
	<?php echo $err_msg ?>
</div>
<?php 	
}
?>
<center>
<table class="changepassword_maintable" align=center style="width:500px">
<tr class="bgcolor_009">
    <td align=center colspan=2><b><font color="#ffffff">- <?php echo gettext("Remittance Request Confirmation")?>&nbsp; -</b></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("FIRSTNAME")?>&nbsp; :</font></td>
    <td align=left><?php echo $agent_info['firstname']?></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("LASTNAME")?>&nbsp; :</font></td>
    <td align=left><?php echo $agent_info['lastname']?></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("ADDRESS")?>&nbsp; :</font></td>
    <td align=left><?php echo $agent_info['address'];?></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php if($amount!=$amount_rounded) echo gettext("AMOUNT (rounded for currency)"); else echo gettext("AMOUNT");?>&nbsp; :</font></td>
    <td align=left><?php echo $amount_rounded?>&nbsp;<?php echo $agent_info['currency'];?></td>
</tr>
<?php if($two_currency){?>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("AMOUNT IN ").strtoupper(BASE_CURRENCY)?>&nbsp; :</font></td>
    <td align=left><?php echo $amount_gobal_cur?>&nbsp;<?php echo strtoupper(BASE_CURRENCY);?></td>
</tr>
<?php }?>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("TRANSACTION TYPE")?>&nbsp; :</font></td>
    <td align=left><?php if($remittance_type == "BANK") echo gettext('withdraw to your bank account'); else echo gettext('withdraw to your balance');?></td>
</tr>
<?php if($remittance_type == "BANK"){ ?>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("BANK INFO")?>&nbsp; :</font></td>
    <td align=left><?php echo $agent_info['bank_info']?> </td>
</tr>
<?php }?>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>
<?php if($action=="add"){?>
<tr>
    <td align=center colspan=2 >
    	<?php if($insert){?>
    		<font color="green"><?php echo gettext('Request sent with success')?></font>
    	<?php }else{?>
    		<font color="red"><?php echo gettext('Request failed')?></font>
    	<?php }?>
    </td>
 </tr>
 <tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>
 <tr>
    <td align=center colspan=2 >
    	<a href=" PP_intro.php"> <?php echo gettext("Back to home page")?> </a>
    </td>
</tr>
<?php }else{?>
<tr>
    <td align=center colspan=2 >
    	<form method="post" action="A2B_agent_remittance_conf.php?action=add" name="frmRemittance">
    		<input type="hidden" name="amount" value="<?php echo $amount_gobal_cur?>" />
    		<input type="hidden" name="remittance_type" value="<?php echo $remittance_type?>" />
			<input type="submit" name="confirm" value="&nbsp;<?php echo gettext("Confirm")?>&nbsp;" class="form_input_button"   > 
		</form>
    </td>
</tr>
<?php }?>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>


</table>
</center>

<?php }else{?>
<center>
<table class="changepassword_maintable" align=center width="400px">
	<tr>
    	<td align="center" colspan=2>&nbsp;<p class="liens"><?php echo gettext("One remittance request is already in progress for")." ".$remittance_value_cur.' '.$agent_info['currency'];?></p></td>
	</tr>
</table>
</center>
<?php }?>
<?php
$smarty->display( 'footer.tpl');
?>