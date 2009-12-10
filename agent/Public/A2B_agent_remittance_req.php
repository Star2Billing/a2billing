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

if (!has_rights(ACX_ACCESS)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

if (!$A2B->config["webagentui"]['remittance_request']) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$QUERY = "SELECT  credit, currency, com_balance, threshold_remittance FROM cc_agent WHERE id = " . $_SESSION['agent_id'];
$table_remittance = $table_remittance = new Table("cc_remittance_request", '*');
$remittance_clause = "id_agent = " . $_SESSION['agent_id'] . " AND status = 0";

$DBHandle_max = DbConnect();
$numrow = 0;
//echo $QUERY;
$resmax = $DBHandle_max->Execute($QUERY);
if ($resmax)
	$numrow = $resmax->RecordCount();

if ($numrow == 0)
	exit ();
$agent_info = $resmax->fetchRow();

$currencies_list = get_currencies();
$two_currency = false;

if (!isset ($currencies_list[strtoupper($agent_info['currency'])][2]) || !is_numeric($currencies_list[strtoupper($agent_info['currency'])][2])) {
	$mycur = 1;
} else {
	$mycur = $currencies_list[strtoupper($agent_info['currency'])][2];
}

$credit_cur = $agent_info['credit'] / $mycur;
$credit_cur = round($credit_cur, 3);

$commision_bal_cur = $agent_info['com_balance'] / $mycur;
$commision_bal_cur = round($commision_bal_cur, 3);

$threshold_cur = $agent_info['threshold_remittance'] / $mycur;
$threshold_cur = round($threshold_cur, 3);
$result_remittance = $table_remittance->Get_list($DBHandle_max, $remittance_clause);

if (is_array($result_remittance) && sizeof($result_remittance) >= 1) {
	$remittance_in_progress = true;
	$remittance_value = $result_remittance[0]['amount'];
} else {
	$remittance_in_progress = false;
}
$remittance_value_cur = $remittance_value / $mycur;
$smarty->display('main.tpl');

echo $CC_help_remittance_request . "<br>";

?>

<script language="JavaScript">
function CheckForm()
{
	var amount = document.frmRemittance.amount.value;
	var com_bal = <?php echo $commision_bal_cur?>;
	var threshold = <?php echo $threshold_cur?>;
    if(Number(amount) == NaN)
    {	
        alert('<?php echo gettext("The amount entered is not a number")?>');
        document.frmRemittance.amount.focus();
        return false;
    }
    if(Number(amount)>com_bal)
    {
        alert('<?php echo gettext("The amount entered is higher than the commission accrued")?>');
        document.frmRemittance.amount.focus();
        return false;
    }
    if(Number(amount)<threshold)
    {
        alert('<?php echo gettext("The amount entered is lower than the thresold to do a remittance request")?>');
        document.frmRemittance.amount.focus();
        return false;
    }
    if(Number(amount)<=0)
    {
        alert('<?php echo gettext("The amount entered must be positive")?>');
        document.frmRemittance.amount.focus();
        return false;
    }
    return true;
}
</script>
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
					<font class="fontstyle_002"><?php echo gettext("REMITTANCE");?> :</font><font class="fontstyle_007"> <?php if($commision_bal_cur>=$threshold_cur && $commision_bal_cur>0) echo gettext("AVAILABLE"); else echo gettext("UNAVAILABLE"); ?> </font>
				<?php }?>
			
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<?php if(!$remittance_in_progress) {?>
<form method="post" action="A2B_agent_remittance_conf.php" name="frmRemittance">
<center>
<table class="changepassword_maintable" align=center width="400px">
<tr class="bgcolor_009">
    <td align=center colspan=2><b><font color="#ffffff">- <?php echo gettext("Remittance Request")?>&nbsp; -</b></td>
</tr>
<?php if($commision_bal_cur>=$threshold_cur && $commision_bal_cur>0) { ?>
<?php if($threshold_cur>0) { ?>
<tr>
    <td align="center" colspan=2>&nbsp;<p class="liens"><?php echo gettext("You have to use an amount higher than")." ".$threshold_cur.' '.$agent_info['currency'];?></p></td>
</tr>
<?php } ?>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("AMOUNT")?>&nbsp; :</font></td>
    <td align=left><input name="amount" type="text" class="form_input_text" >&nbsp;<?php echo $agent_info['currency'];?></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("REMITTANCE TYPE")?>&nbsp; :</font></td>
    <td align=left><select name="remittance_type"  class="form_input_select" ><option value="BALANCE">balance withdraw </option> <option value="BANK">bank withdraw  </option>  </select> </td>
</tr>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>
<tr>
    <td align=center colspan=2 ><input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Confirm")?>&nbsp;" class="form_input_button" onclick="return CheckForm();"  > </td>
</tr>
<?php } else {?>
<tr>
    <td align=center colspan=2><?php echo gettext('Remittance Request is not available')?></td>
</tr>
<?php }?>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>


</table>
</center>
</form>
<?php } else { ?>
<center>
<table class="changepassword_maintable" align=center width="400px">
	<tr>
    	<td align="center" colspan=2>&nbsp;<p class="liens"><?php echo gettext("One remittance request is already in progress for")." ".$remittance_value_cur.' '.$agent_info['currency'];?></p></td>
	</tr>
</table>
</center>
<?php } ?>

<BR/>

<?php

$smarty->display( 'footer.tpl');
