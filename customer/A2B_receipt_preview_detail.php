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
include ("./lib/customer.smarty.php");
include ("./lib/support/classes/receipt.php");
include ("./lib/support/classes/receiptItem.php");

if (! has_rights (ACX_INVOICES)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('page'));


if (empty($page))$page=1;

$card_id = $_SESSION["card_id"];

function loadDetailledItems($startdate,$begin=null,$nb=null) {
	$result = array ();
	global $card_id;
	$DBHandle = DbConnect();
	$call_table = new Table("cc_call", "*");

	$call_clause = " card_id = " .$card_id ;
	if(!empty($startdate)) $call_clause .= " AND stoptime>= '$startdate'";
	$call_clause .=" AND stoptime < NOW()";
	$return_calls = $call_table->Get_list($DBHandle, $call_clause,'starttime','ASC',null,null,$nb,$begin);
	foreach ($return_calls as $call) {
		$min = floor($call['sessiontime'] / 60);
		$sec = $call['sessiontime'] % 60;
		$item = new ReceiptItem(null, "CALL : " . $call['calledstation'] . " DURATION : " . $min . " min " . $sec . " sec", $call['starttime'], $call["sessionbill"], $value["VAT"], true);
		$result[] = $item;
	}
	$charge_table = new Table("cc_charge", "*");
	$clause_charge = "id_cc_card = ".$card_id;
	if(!empty($startdate)) $clause_charge .= " AND creationdate >= '$startdate'";
	$clause_charge .=" AND  creationdate < NOW() AND charged_status = 1";
	$return_charges = $charge_table->Get_list($DBHandle, $clause_charge);
	foreach ($return_charges as $charge) {
		$item = new ReceiptItem(null, gettext("CHARGE :").$charge['description'], $charge['creationdate'], $charge['amount'], 'CHARGE');
		$result[]= $item;
	}
	//sort r�sult by date
	return $result;
}

function nbDetailledItems($startdate) {
	global $card_id;
	$result = array ();
	$DBHandle = DbConnect();
	$i = 0;
	$call_table = new Table("cc_call", "COUNT(*)");
	$call_clause = " card_id = " . $card_id;
	if(!empty($startdate)) $call_clause .= " AND stoptime>= '$startdate'";
	$call_clause .=" AND stoptime < NOW()";
	$return_calls = $call_table->Get_list($DBHandle, $call_clause,'starttime','ASC');
	if(is_array($return_calls))$i=$i+$return_calls[0][0];
	$charge_table = new Table("cc_charge", "COUNT(*)");
	$clause_charge = "id_cc_card = ".$card_id;
	if(!empty($startdate)) $clause_charge .= " AND creationdate >= '$startdate'";
	$clause_charge .=" AND  creationdate < NOW() AND charged_status = 1 ";
	$return_charges = $charge_table->Get_list($DBHandle, $clause_charge);
	if(is_array($return_charges))$i=$i+$return_charges[0][0];
	return $i;
}

function SumDetailledItems($startdate) {
	global $card_id;
	$result = array ();
	$DBHandle = DbConnect();
	$total = 0;
	$call_table = new Table("cc_call", "SUM(sessionbill)");
	$call_clause = " card_id = " . $card_id ;
	if(!empty($startdate)) $call_clause .= " AND stoptime>= '$startdate'";
	$call_clause .=" AND stoptime < NOW()";
	$return_calls = $call_table->Get_list($DBHandle, $call_clause);
	if(is_array($return_calls))$i=$i+$return_calls[0][0];
	$charge_table = new Table("cc_charge", "SUM(amount)");
	$clause_charge = "id_cc_card = ".$card_id;
	if(!empty($startdate)) $clause_charge .= " AND creationdate >= '$startdate'";
	$clause_charge .=" AND  creationdate < NOW() AND charged_status = 1 ";
	$return_charges = $charge_table->Get_list($DBHandle, $clause_charge);
	if(is_array($return_charges))$i=$i+$return_charges[0][0];
	return $i;
}

function amount_convert($amount) {
	global $mycur;
	return $amount/$mycur;
}

$billing_table = new Table('cc_billing_customer','id,date');
$clause_last_billing = "id_card = ".$_SESSION["card_id"];
$result = $billing_table -> Get_list($DBHandle, $clause_last_billing,"date","desc");
$call_table = new Table('cc_call','COALESCE(SUM(sessionbill),0)' );
$clause_call_billing ="card_id = ".$_SESSION["card_id"]." AND ";
$clause_charge = "id_cc_card = ".$_SESSION["card_id"]." AND ";
$start_date =$result[0][1];


$nbitems = nbDetailledItems($start_date);
$nb_by_page =100;
$nb_page = ceil($nbitems/$nb_by_page);
$items = loadDetailledItems($start_date,(($page-1)*$nb_by_page),$nb_by_page);
if ($nb_page>1) $totalprice = SumDetailledItems($start_date);

//load customer
$DBHandle  = DbConnect();

$smarty->display('main.tpl');

//Currencies check
$curr = $_SESSION['currency'];
$currencies_list = get_currencies();

if (!isset($currencies_list[strtoupper($curr)][2]) || !is_numeric($currencies_list[strtoupper($curr)][2])) {
	$mycur = 1;
	$display_curr=strtoupper(BASE_CURRENCY);
} else {
	$mycur = $currencies_list[strtoupper($curr)][2];
	$display_curr=strtoupper($curr);
}


?>

<?php if($nb_page>1) { ?>
<table width="90%" style ="margin-left:auto;margin-right:auto;" >
	<tr>
		<td colspan="3" align="left">
				<?php if($page>1) { ?>
			<a href="A2B_receipt_detail.php?popup_select=1&id=<?php echo $id; ?>&page=<?php echo $page-1; ?>"> &lt; <?php echo gettext("Page") ?>&nbsp;<?php echo $page-1; ?> </a>
		<?php } ?>
			&nbsp;
		</td>
		<td colspan="3" align="right">
			&nbsp;
				<?php if($page<$nb_page) { ?>
			<a href="A2B_receipt_detail.php?popup_select=1&id=<?php echo $id; ?>&page=<?php echo $page+1; ?>"><?php echo gettext("Page") ?>&nbsp;<?php echo $page+1; ?> &gt;</a>
		<?php } ?>
		</td>
	</tr>
</table>
	<?php } ?>

<div class="receipt-wrapper">
	<table class="receipt-table">
		<thead>
			<tr class="one">
				<td class="one">
					<h1><?php echo gettext("PREVIEW NEXT RECEIPT DETAIL"); ?></h1>

				</td>
			</tr>
			<tr class="two">
				<td colspan="3" class="receipt-details">
					<table class="receipt-details">
						<tbody>
							<tr>
								<td class="one">
									&nbsp;
								</td>
								<td class="three" align="right">
									<strong>Client number</strong>
									<div><?php echo $_SESSION['pr_login'] ?></div>
								</td>
							</tr>
						</tbody></table>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="3" class="items">
					<table class="items">
						<tbody>
							<tr class="one">
								<th style="text-align:left;" width="20%"><?php echo gettext("Date"); ?></th>
								<th class="description" width="60%"><?php echo gettext("Description"); ?></th>
								<th width="20%" ><?php echo gettext("Cost"); ?></th>
							</tr>
							<?php
							$i=0;
							foreach ($items as $item) { ?>
							<tr style="vertical-align:top;" class="<?php if($i%2==0) echo "odd"; else echo "even";?>" >
								<td style="text-align:left;">
									<?php echo $item->getDate(); ?>
								</td>
								<td class="description">
									<?php echo $item->getDescription(); ?>
								</td>
								<td align="right">
									<?php echo number_format(round(amount_convert($item->getPrice()),6),6); ?>
								</td>
							</tr>
							<?php
							$i++;
							} ?>
						</tbody>
					</table>
				</td>
			</tr>
			<?php
			$price= 0;
			foreach ($items as $item) {
				$price = $price + $item->getPrice();
			}
			if ($nb_page<=1) $totalprice = $price;
			?>
			<tr>
				<td colspan="3">
					<table class="total">
						<tbody>
			<?php if($nb_page > 1) { ?>
							<tr class="extotal">
								<td class="one"></td>
								<td class="two"><?php echo gettext("Total Page")." ".$page ?></td>
								<td class="three"><div class="inctotal"><div class="inctotal inner"><?php echo number_format(ceil(amount_convert(ceil($price*100)/100)*100)/100,2)." $display_curr"; ?></div></div></td>
							</tr>
			<?php } ?>
							<tr class="inctotal">
								<td class="one"></td>
								<td class="two"><?php echo gettext("Total Receipt :") ?></td>
								<td class="three"><div class="inctotal"><div class="inctotal inner"><?php echo number_format(ceil(amount_convert(ceil($totalprice*100)/100)*100)/100,2)." $display_curr"; ?></div></div></td>
							</tr>
						</tbody></table>
				</td>
			</tr>

		</tbody>

	</table></div>



<?php $smarty->display('profiler.tpl');