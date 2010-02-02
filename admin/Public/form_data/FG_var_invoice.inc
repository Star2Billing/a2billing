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



getpost_ifset(array('id', 'id_card', 'date','title','description'));
   
 
if (!isset($form_action) || $form_action=="list") {
	$list_query = "cc_invoice LEFT JOIN cc_card ON cc_card.id=id_card ";
	$list_query .= "LEFT JOIN (SELECT st1.id_invoice, TRUNCATE(SUM(st1.price*(1+(st1.vat/100))),2) as total_price " .
	               "FROM cc_invoice_item AS st1 GROUP BY st1.id_invoice ) as items ON items.id_invoice = cc_invoice.id ";
	$HD_Form = new FormHandler($list_query,"Invoice");
} else {
	$HD_Form = new FormHandler("cc_invoice","Invoice");
}

$HD_Form -> FG_TABLE_ID = 'cc_invoice.id';
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_invoice_item");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_invoice ");
 
$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_invoice_view.php?id=";
$HD_Form -> FG_INFO_ALT = "VIEW";
$HD_Form -> FG_INFO_IMG = "info.png";

$HD_Form -> FG_OTHER_BUTTON1_LINK = "A2B_invoice_edit.php?id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/page_white_gear.png";
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_CONDITION = '|col6| == "'.gettext('OPEN').'"';
$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext("Edit invoice items");
$HD_Form -> FG_OTHER_BUTTON2_LINK = "javascript:;";
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path."/lock.png";
$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_HTML_CLASS = "lock";
$HD_Form -> FG_OTHER_BUTTON2_HTML_ID = "|col0|";
$HD_Form -> FG_OTHER_BUTTON2_CONDITION = '|col6| == "'.gettext('OPEN').'"';
$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext("Close this invoice");
$HD_Form -> FG_OTHER_BUTTON3_LINK = "A2B_invoice_manage_payment.php?id=";
$HD_Form -> FG_OTHER_BUTTON3_IMG = Images_Path."/money.png";
$HD_Form -> FG_OTHER_BUTTON3 = true;
$HD_Form -> FG_OTHER_BUTTON3_CONDITION = '|col6| == "'.gettext('CLOSE').'"';
$HD_Form -> FG_OTHER_BUTTON3_ALT = gettext("Manage payments");
$HD_Form -> FG_EDITION_CONDITION = '|col6| == "'.gettext('OPEN').'"';
$HD_Form -> FG_DELETION_CONDITION = '|col6| == "'.gettext('OPEN').'"';
$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_invoice.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Invoice");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/money.png" ;


$status_list = Constants::getInvoiceStatusList();
$paid_status_list = Constants::getInvoicePaidStatusList();
$typepaid_list_r = Constants::getPaidTypeList_Revert_List();
$paid_status_list_r = Constants::getInvoicePaidStatusList_Revert_List();
$invoiceday_list = Constants::getInvoiceDay_List();
$status_list_r = Constants::getInvoiceStatusList_Revert_List();

//$filter_amount_without_vat ='SUM(cc_invoice_item.price)';
// $filter_amount_with_vat ='SUM(cc_invoice_item.price*(1+(cc_invoice_item.vat/100)))';
$filter_amount_with_vat = "total_price";
$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_invoice.id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("REFERENCE"), "cc_invoice.reference", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "10%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "21%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAID STATUS"), "status", "7%", "center", "sort", "", "list", $paid_status_list);
$HD_Form -> AddViewElement(gettext("STATUS"), "cc_invoice_status", "7%", "center", "sort", "", "list", $status_list);
$HD_Form -> AddViewElement(gettext("AMOUNT INCL VAT"), "total_price", "10%", "center", "sort","", "", "", "", "", "", "display_2bill");
$HD_Form -> FieldViewElement ("cc_invoice.id, cc_invoice.reference, username, cc_invoice.date,title, cc_invoice.paid_status, cc_invoice.status as cc_invoice_status,$filter_amount_with_vat");


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

if (!($popup_select>=1)) $HD_Form -> FG_FILTER_SEARCH_FORM = true;
if (!($popup_select>=1)) $HD_Form -> FG_FILTER_SEARCH_DELETE_ALL = false;

$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for invoice created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('CREATION DATE');

$HD_Form -> AddSearchElement_C1(gettext("FIRSTNAME"),'firstname','firstnametype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("REFERENCE"), 'reference','referencetype');
$HD_Form -> AddSearchElement_C1(gettext("TITLE"), 'title','titletype');

$HD_Form -> AddSearchElement_C2(gettext("AMOUNT INCL VAT"),'total_price1','total_price1type','total_price2','total_price2type','total_price');

$HD_Form -> AddSearchElement_Select(gettext("PAYMENT TYPE"), null, null, null, null,null , "typepaid", 0, $typepaid_list_r);
$HD_Form -> AddSearchElement_Select(gettext("CALL PLAN"), "cc_tariffgroup", "id, tariffgroupname", "", "tariffgroupname", "ASC", "tariff");
$HD_Form -> AddSearchElement_Select(gettext("INVOICE DAY"), null, null, null, null,null , "invoiceday", 0, $invoiceday_list);
$HD_Form -> AddSearchElement_Select(gettext("PAID STATUS"), null, null, null, null,null , "paid_status", 0, $paid_status_list_r);


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;

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

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
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

$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'create_invoice_reference';

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = "A2B_invoice_edit.php?id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



