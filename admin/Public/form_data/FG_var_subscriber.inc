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


getpost_ifset(array('id', 'id_cc_card', 'id_subscription_fee', 'datestart', 'datestop', 'product_name'));

$HD_Form = new FormHandler("cc_card_subscription","Subscriber");
$HD_Form -> FG_DEBUG = 0;

$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_subscriber.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add SUBSCRIBER");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/text_signature.png" ;


$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "id_cc_card", "15%", "center", "sort", "15", "lie", "cc_card", "username", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("SERVICE"), "id_subscription_fee", "20%", "center", "sort", "15", "lie", "cc_subscription_service", "label", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("DATE START"), "startdate", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE STOP"), "stopdate", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PRODUCT"), "product_name", "20%", "center", "sort", "50");
$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id_cc_card, id_subscription_fee, startdate, stopdate, product_name');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." MADE!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;

$comp_zero = $comp_date_plus='';
if (isset($form_action) && $form_action=="ask-add") {
	$begin_date = date("Y");
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	//set passed id if any (used if called from customer screen, id is passed to pr-epopluate value
	$idd = "value='$id'";
	//Max value Mysql Timestamp can store is '2038-01-19 03:14:07' UTC
	$comp_date_plus = "value='2038-01-01 00:00:00'";
	$comp_zero = "value='0'";
}

$HD_Form -> AddEditElement (gettext("ACCOUNT ID"),
				"id_cc_card",
				'',
				"POPUPVALUE",
				"size=30 maxlength=50 $idd",
				"4",
				gettext("Insert the Customer ID"),
				"" , "", "", "", "",
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=350,top=20,left=100,scrollbars=1'" ,
				gettext("Set the Customer ID."));

$HD_Form -> AddEditElement (gettext("ID SUBSCRIPTION SERVICE"),
				"id_subscription_fee",
				"",
				"SELECT",
				"", "", "",
				"sql",
				"cc_subscription_service",
				"label, id",
				"", "", "%1", "", "", "", '<OPTION  value="-1" selected>'.gettext("NOT DEFINED").'</OPTION>' );

$HD_Form -> AddEditElement (gettext("START DATE"),
				"startdate",
				'$value',
				"INPUT",
				"size=40 maxlength=40  $comp_date",
				"10",
				gettext("Insert the start date"),
				"" , "", "",	"", "", "", "NO-NULL", gettext("please use the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");

$HD_Form -> AddEditElement (gettext("STOP DATE"),
				"stopdate",
				'$value',
				"INPUT",
				"size=40 maxlength=40  $comp_date_plus",
				"10",
				gettext("Insert the stop date"),
				"" , "", "",	"", "", "", "NO-NULL", gettext("please use the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");

$HD_Form -> AddEditElement (gettext("PRODUCT LABEL"),
				"product_name",
				'',
				"TEXTAREA",
				"cols=50 rows=4",
				"",
				gettext("Enter the name of the product wich is subscribed"),
				"" , "", "",	"", "" , "", "", "");

$HD_Form -> FieldEditElement ('id_cc_card, id_subscription_fee, startdate, stopdate, product_name');

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Apply subscription to account");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on Confirm Data");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";


