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


getpost_ifset(array('id', 'id_card', 'date','title','description'));


if (!isset($form_action) || $form_action=="list") {
	$list_query = "cc_receipt LEFT JOIN cc_card ON cc_card.id=id_card";
	$list_query .= " LEFT JOIN ( SELECT st1.id_receipt, SUM(st1.price) as total_price FROM cc_receipt_item AS st1 GROUP BY st1.id_receipt) as items ON items.id_receipt = cc_receipt.id ";
	$HD_Form = new FormHandler($list_query,"Receipt");
} else {
	$HD_Form = new FormHandler("cc_receipt","Receipt");
}

$HD_Form -> FG_TABLE_ID = 'cc_receipt.id';
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_receipt_item");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_receipt ");

$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_receipt_view.php?id=";
$HD_Form -> FG_INFO_ALT = "VIEW";
$HD_Form -> FG_INFO_IMG = "info.png";

$HD_Form -> FG_OTHER_BUTTON1_LINK = "A2B_receipt_edit.php?id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/page_white_gear.png";
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_CONDITION = '|col4| == "OPEN"';
$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext("Edit receipt items");
$HD_Form -> FG_OTHER_BUTTON2_LINK = "javascript:;";
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path."/lock.png";
$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_HTML_CLASS = "lock";
$HD_Form -> FG_OTHER_BUTTON2_HTML_ID = "|col0|";
$HD_Form -> FG_OTHER_BUTTON2_CONDITION = '|col4| == "OPEN"';
$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext("Close this receipt");
$HD_Form -> FG_EDITION_CONDITION = '|col4| == "OPEN"';

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_receipt.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Receipt");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/money.png" ;


$status_list = Constants::getInvoiceStatusList();

$filter_amount_with_vat ='total_price';
$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_receipt.id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "10%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "21%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "7%", "center", "sort", "", "list", $status_list);
$HD_Form -> AddViewElement(gettext("AMOUNT INCL VAT"), "total_price", "10%", "center", "sort","", "", "", "", "", "", "display_2bill");
$HD_Form -> FieldViewElement ("cc_receipt.id, username, cc_receipt.date,title,cc_receipt.status,$filter_amount_with_vat");


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("ID CUSTOMER"),
			   "id_card",
			   "$value", "POPUPVALUE", "size=30 maxlength=50",
			   "4",	gettext("Insert the Customer ID"),
			    "" , "", "", "", "",
				"A2B_entity_card.php?popup_select=1&", ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Customer ID"));

if ($form_action=="ask-add"){
	$begin_date = date("Y");
	$end_date = date("-m-d H:i:s");
	$comp_date=$begin_date.$end_date;
	$comp_date="value='$comp_date'";
}

$HD_Form -> AddEditElement (gettext("INVOICE DATE"),
			   "date",
			   '$value',
			   "INPUT",
			   "size=40 READONLY maxlength=40  $comp_date",
			   "10",
			   gettext("Insert the current date"),
			   "" , "", "",	"", "", "", "", "" );


$HD_Form -> AddEditElement ( gettext("TITLE"),
				"title",	'$value',
				"INPUT",
				"size=30 maxlength=50",
				"0",
				gettext("Insert the title of invoice, if you need one"),
				"" , "", "",	"", "", "", "", "" );


$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
				"description",
				'',
				"TEXTAREA",
				"cols=50 rows=4",
				"",
				gettext("Insert the description"),
				"" , "", "", "", "" , "", "", "");


$HD_Form -> FieldEditElement ('id_card, date,title, description');



$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'Account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = "A2B_receipt_edit.php?id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";



