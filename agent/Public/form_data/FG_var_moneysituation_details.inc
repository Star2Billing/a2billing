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


getpost_ifset(array('id', 'displayheader', 'type','popup_select','popup_formname','popup_fieldname'));


// type : refill or payment
$HD_Form->FG_DEBUG = 0;

if ($type == 'payment') {
	$HD_Form = new FormHandler("cc_logpayment as t2 LEFT JOIN cc_card ON cc_card.id=t2.card_id LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group ", "Payment");
	$HD_Form->FG_TABLE_CLAUSE = "t2.card_id = $id AND cc_card_group.id_agent = " . $_SESSION['agent_id'];
	$HD_Form->FG_TABLE_ID = 't2.id';
	$HD_Form->FG_TABLE_DEFAULT_SENS = "DESC";
	//SELECT t3.date, t3.credit, t3.reseller_id from cc_logrefill as t3 WHERE t3.card_id='1'
	//SELECT t2.date, t2.payment, t2.reseller_id from cc_logpayment as t2 WHERE t2.card_id='1'
	$HD_Form->AddViewElement(gettext("DATE"), "date", "50%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
	$HD_Form->AddViewElement(gettext("AMOUNT"), "payment", "20%", "center", "sort");

	$HD_Form->FieldViewElement('t2.date, t2.payment');
} else {
	$list_sql = "cc_invoice " .
	"LEFT JOIN cc_card ON cc_card.id=cc_invoice.id_card LEFT JOIN cc_card_group ON cc_card_group.id = cc_card.id_group " .
	"LEFT JOIN ( SELECT st1.id_invoice, SUM(st1.price*(1+(st1.vat/100))) AS total_price FROM cc_invoice_item AS st1 GROUP BY st1.id_invoice) " .
	"AS items ON items.id_invoice=cc_invoice.id ";
	$HD_Form = new FormHandler($list_sql, "Invoice");
	$HD_Form->FG_TABLE_ID = 'cc_invoice.id';
	$HD_Form->FG_TABLE_DEFAULT_SENS = "DESC";
	$HD_Form->FG_TABLE_CLAUSE = "cc_invoice.id_card = $id AND cc_card_group.id_agent = " . $_SESSION['agent_id'] . " ";
	//SELECT t3.date, t3.credit, t3.reseller_id from cc_logrefill as t3 WHERE t3.card_id='1'
	//SELECT t2.date, t2.payment, t2.reseller_id from cc_logpayment as t2 WHERE t2.card_id='1'
	$HD_Form->AddViewElement(gettext("DATE"), "date", "50%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
	$HD_Form->AddViewElement(gettext("AMOUNT INCL VAT"), "total_price", "10%", "center", "sort", "", "", "", "", "", "", "display_2bill");
	$HD_Form->FieldViewElement("cc_invoice.date, total_price");
}

$HD_Form->CV_NO_FIELDS = gettext("THERE IS NO") . strtoupper($HD_Form->FG_INSTANCE_NAME) . " " . gettext("CREATED!");
$HD_Form->CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form->CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form->CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form->CV_FOLLOWPARAMETERS = "&popup_select=" . $popup_select . "&id=$id&type=$type";
$HD_Form->FG_EDITION = false;
$HD_Form->FG_DELETION = false;
$HD_Form->FG_ADDITION = false;

