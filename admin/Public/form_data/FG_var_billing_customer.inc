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

getpost_ifset(array('id', 'id_card', 'date'));

if (!isset($form_action) || $form_action=="list") {
    $HD_Form = new FormHandler("cc_billing_customer LEFT JOIN cc_card ON cc_card.id=id_card  ","Billing");
} else {
    $HD_Form = new FormHandler("cc_billing_customer","Billing");
}

$HD_Form -> FG_TABLE_ID = 'cc_billing_customer.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_billing_customer.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Billing");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/time_add.png" ;

$HD_Form -> AddViewElement(gettext("ID"), "cc_billing_customer.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("BILLING DATE"), "cc_billing_customer.date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> FieldViewElement ('cc_billing_customer.id, username, cc_billing_customer.date');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_DELETION = true;

$HD_Form -> AddEditElement (gettext("ID CUSTOMER"),
    "id_card",
    "$value", "POPUPVALUE", "size=30 maxlength=50",
    "4",    gettext("Insert the Customer ID"),
    "" , "", "", "", "",
    "A2B_entity_card.php?popup_select=1&", ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
    gettext("Select the Customer ID"));

if ($form_action=="ask-add"){
    $comp_date = date("Y-m-d");
    $comp_date="value='$comp_date'";
}

$HD_Form -> AddEditElement (gettext("BILLING DATE"),
    "date",
    '$value',
    "INPUT",
    "size=40 maxlength=40 $comp_date",
    "8",
    gettext("Insert the current date (YYYY-MM-DD)"),
    "" , "", "",    "", "", "", "", "" );

$HD_Form -> FieldEditElement ('id_card, date');

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'Account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
    if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
    $HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}

$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");

$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'proccessing_billing_customer';

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
