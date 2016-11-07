<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id', 'id_card', 'date','title','description'));

if (!isset($form_action) || $form_action=="list") {
	$list_query = "cc_invoice " .
	              "LEFT JOIN (SELECT st1.id_invoice, TRUNCATE(SUM(st1.price*(1+(st1.vat/100))),2) as total_price " .
	              "FROM cc_invoice_item AS st1 GROUP BY st1.id_invoice ) as items ON items.id_invoice = cc_invoice.id ";
	$HD_Form = new FormHandler($list_query,"Invoice");
} else {
	$HD_Form = new FormHandler("cc_invoice","Invoice");
}

$HD_Form -> FG_TABLE_ID = 'cc_invoice.id';
$HD_Form -> FG_TABLE_CLAUSE = " id_card = ".$_SESSION["card_id"]." AND status = 1";
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_invoice_view.php?id=";
$HD_Form -> FG_INFO_ALT = "VIEW";
$HD_Form -> FG_INFO_IMG = "info.png";

$HD_Form -> FG_OTHER_BUTTON3_LINK = "checkout_payment.php?item_type=invoice&item_id=";
$HD_Form -> FG_OTHER_BUTTON3_IMG = Images_Path."/money.png";
$HD_Form -> FG_OTHER_BUTTON3 = true;
$HD_Form -> FG_OTHER_BUTTON3_CONDITION = '|col6| == "UNPAID"';


$status_list = Constants::getInvoicePaidStatusList();

$HD_Form -> AddViewElement(gettext("REFERENCE"), "cc_invoice.reference", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "paid_status", "5%", "center", "sort", "", "list", $status_list);
$HD_Form -> AddViewElement(gettext("AMOUNT INCL VAT"), "price", "10%", "center", "sort","", "", "", "", "", "", "display_2bill");
$HD_Form -> FieldViewElement ("cc_invoice.reference, cc_invoice.date, title, cc_invoice.paid_status, total_price, id, cc_invoice.paid_status");


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO INVOICE CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;





