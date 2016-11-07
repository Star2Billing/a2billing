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

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}


getpost_ifset(array('id', 'agent_id', 'agent_name', 'agent_email_address', 'item_name', 'item_id', 'item_quantity', 'payment_method','cc_type', 'cc_owner','cc_number', 'cc_expires', 'orders_status', 'last_modified', 'date_purchased','orders_date_finished', 'orders_amount', 'currency', 'currency_value'));


$HD_Form = new FormHandler("cc_payments_agent","Transactions");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form -> AddViewElement(gettext("ID"), "id", "4%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ORDER DATE"), "date_purchased", "10%", "center", "sort", "19", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("CARDID"), "agent_id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("NAME"), "agent_name", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ITEM NAME"), "item_name", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ITEM ID"), "item_id", "10%", "center", "sort", "", "30", "", "", "", "", "linktoagent");
$HD_Form -> AddViewElement(gettext("METHOD"), "payment_method", "9%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CC NUMBER"), "cc_number", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "orders_status", "7%", "center", "sort","15","lie","cc_payments_status","status_name","status_id='%id'","%1");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "orders_amount", "8%", "center", "SORT", "30");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("CURRENCY")."\">".gettext("CUR")."</acronym>", "currency", "5%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("VALUE"), "currency_value", "5%", "center", "SORT", "30");

$HD_Form -> FieldViewElement ('id, date_purchased, agent_id, agent_name, item_name, item_id, payment_method, cc_number, orders_status, orders_amount, currency, currency_value');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = true;

// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'transaction_id';
$HD_Form -> FG_FILTERFIELDNAME = 'transaction ID';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id like '$filterprefix%'";
}

$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";




