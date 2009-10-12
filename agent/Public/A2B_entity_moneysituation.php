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
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_moneysituation.inc");
include ("../lib/agent.smarty.php");

if (!has_rights(ACX_BILLING)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form->setDBHandler(DbConnect());

$HD_Form->init();

if ($id != '' || !is_null($id)) {
	$HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
	$form_action = "list"; //ask-add
if (!isset ($action))
	$action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_money_situation;

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);


$table = new Table();
$result_nb_card = $table->SQLExec($HD_Form->DBHandle, "SELECT COUNT(*) from cc_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card_group.id_agent = " . $_SESSION['agent_id']);

if ($result_nb_card[0][0] > 0) {
	$temp = date("Y-m-01");
	$now_month = date("m");
	$nb_month = 5;
	$datetime = new DateTime($temp);
	$datetime->modify("-$nb_month month");
	$checkdate = $datetime->format("Y-m-d");
	
	$QUERY_INVOICE_ENOUGH_PAID = "SELECT DATE_FORMAT(sub.date,'%c'),SUM(sub.test) FROM( SELECT cc_invoice.date date,IF(IFNULL(SUM(CEIL(cc_invoice_item.price*(1+(cc_invoice_item.vat/100))*100)/100),0) <= IFNULL(SUM(CEIL(cc_logpayment.payment*100)/100),0),1,0) test FROM cc_invoice LEFT JOIN cc_invoice_item on cc_invoice_item.id_invoice=cc_invoice.id LEFT JOIN cc_invoice_payment on cc_invoice_payment.id_invoice = cc_invoice.id  LEFT JOIN cc_logpayment on cc_invoice_payment.id_payment = cc_logpayment.id LEFT JOIN cc_card ON cc_card.id=cc_invoice.id_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card_group.id_agent = " . $_SESSION['agent_id'] . " AND cc_invoice.date >= TIMESTAMP('$checkdate') AND cc_invoice.date <= CURRENT_TIMESTAMP group by cc_invoice.id ) AS sub group by MONTH(sub.date) ORDER BY  sub.date DESC";
	$result_invoice_enough_paid = $table->SQLExec($HD_Form->DBHandle, $QUERY_INVOICE_ENOUGH_PAID);
	
	$QUERY_INVOICE_PAID = "SELECT DATE_FORMAT(date,'%c'),COUNT(*) FROM cc_invoice LEFT JOIN cc_card ON cc_card.id=cc_invoice.id_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card_group.id_agent = " . $_SESSION['agent_id'] . " AND paid_status = 1 AND cc_invoice.date >= TIMESTAMP('$checkdate') AND cc_invoice.date <= CURRENT_TIMESTAMP group by MONTH(date) ORDER BY date DESC";
	$result_invoice_paid = $table->SQLExec($HD_Form->DBHandle, $QUERY_INVOICE_PAID);
	
	$QUERY_INVOICE_UNPAID = "SELECT DATE_FORMAT(date,'%c'),COUNT(*) FROM cc_invoice LEFT JOIN cc_card ON cc_card.id=cc_invoice.id_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card_group.id_agent = " . $_SESSION['agent_id'] . " AND paid_status = 0 AND cc_invoice.date >= TIMESTAMP('$checkdate') AND cc_invoice.date <= CURRENT_TIMESTAMP group by MONTH(date) ORDER BY date DESC";
	$result_invoice_unpaid = $table->SQLExec($HD_Form->DBHandle, $QUERY_INVOICE_UNPAID);
	
	$QUERY_INVOICE_COUNT = "SELECT DATE_FORMAT(date,'%c'),COUNT(*) FROM cc_invoice LEFT JOIN cc_card ON cc_card.id=cc_invoice.id_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group WHERE cc_card_group.id_agent = " . $_SESSION['agent_id'] . " AND cc_invoice.date >= TIMESTAMP('$checkdate') AND cc_invoice.date <= CURRENT_TIMESTAMP group by MONTH(date) ORDER BY date DESC";
	$result_invoice_count = $table->SQLExec($HD_Form->DBHandle, $QUERY_INVOICE_COUNT);
	$list_month = Constants :: getMonth();
	$list_invoice_enough_paid = array ();
	
	$j = 0;
	for ($i = 0; $i <= $nb_month; $i++) {
		if (sizeof($result_invoice_enough_paid) > $j) {
			$val = $result_invoice_enough_paid[$j];
			if ($now_month > $i)
				$month_test = intval($now_month - $i);
			else
				$month_test = $now_month + (12 - $i);
			if ($val[0] == $month_test) {
				$list_invoice_enough_paid[$i] = $val[1];
				$j++;
			} else
				$list_invoice_enough_paid[$i] = 0;
		} else
			$list_invoice_enough_paid[$i] = 0;
	}

	$list_invoice_unpaid = array ();
	$j = 0;
	for ($i = 0; $i <= $nb_month; $i++) {
		if (sizeof($result_invoice_unpaid) > $j) {
			$val = $result_invoice_unpaid[$j];
			if ($now_month > $i)
				$month_test = intval($now_month - $i);
			else
				$month_test = $now_month + (12 - $i);
			if ($val[0] == $month_test) {
				$list_invoice_unpaid[$i] = $val[1];
				$j++;
			} else
				$list_invoice_unpaid[$i] = 0;
		} else
			$list_invoice_unpaid[$i] = 0;
	}

	$list_invoice_paid = array ();
	$j = 0;
	for ($i = 0; $i <= $nb_month; $i++) {
		if (sizeof($result_invoice_paid) > $j) {
			if ($now_month > $i)
				$month_test = intval($now_month - $i);
			else
				$month_test = $now_month + (12 - $i);
			$val = $result_invoice_paid[$j];
			if ($val[0] == $month_test) {
				$list_invoice_paid[$i] = $val[1];
				$j++;
			} else
				$list_invoice_paid[$i] = 0;
		} else
			$list_invoice_paid[$i] = 0;
	}

	$list_invoice_count = array ();
	$j = 0;
	for ($i = 0; $i <= $nb_month; $i++) {
		if (sizeof($result_invoice_count) > $j) {
			if ($now_month > $i)
				$month_test = intval($now_month - $i);
			else
				$month_test = $now_month + (12 - $i);
			$val = $result_invoice_count[$j];
			if ($val[0] == $month_test) {
				$list_invoice_count[$i] = $val[1];
				$j++;
			} else
				$list_invoice_count[$i] = 0;
		} else
			$list_invoice_count[$i] = 0;
	}
	?>
	<br/>
	<table border="1" cellpadding="4" cellspacing="2" width="90%" align="center" class="bgcolor_017" >		
		<tr>
			<td>		
				<table border="2" cellpadding="3" cellspacing="5" width="450" align="right" class="bgcolor_018">		
					<tr class="form_head">      
						<td>&nbsp;</td> 
						<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("NB TOTAL INVOICE");?></strong></td>            					
						<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("NB INVOICE WITH ENOUGH PAYMENT ");?></strong></td>
						<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("NB INVOICE WITH PAID STATUS");?></strong></td>
						<td width="20%" align="center" class="tableBodyRight" style="padding: 2px;"><strong><?php echo gettext("NB INVOICE WITH UNPAID STATUS");?></strong></td>
					</tr>
					<?php for($i=0;$i<=$nb_month;$i++){
						if($now_month>$i) $month_display=intval($now_month-$i);
						else $month_display = $now_month + (12-$i)
						?>
					<tr>
						<td valign="top" align="center" class="tableBody" bgcolor="white"><b><?php echo $list_month[$month_display][0]; ?></b></td>
						<td valign="top" align="center" class="tableBody" bgcolor="white"><b><?php echo $list_invoice_count[$i]; ?></b></td>
						<td valign="top" align="center" class="tableBody" bgcolor="#5FA631"><b><?php echo $list_invoice_enough_paid[$i]; ?></b></td>
						<td valign="top" align="center" class="tableBody" bgcolor="#DDDDDD"><b><?php echo $list_invoice_paid[$i]; ?></b></td>
						<td valign="top" align="center" class="tableBody" bgcolor="#EE6564"><b><?php echo $list_invoice_unpaid[$i]; ?></b></td>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
	</table>
		<br></br>
<?php
}
// #### FOOTER SECTION
$smarty->display('footer.tpl');
