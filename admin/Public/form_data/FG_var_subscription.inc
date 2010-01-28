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



getpost_ifset(array('id', 'label', 'fee', 'status', 'emailreport'));

$HD_Form = new FormHandler("cc_subscription_service","Subscription Service");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_subscription.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add SUBSCRIPTION SERVICE");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/script.png" ;

$actived_list = Constants::getActivationList();

$daynumber_list = array();
for ($k=1;$k<=20;$k++)
        $daynumber_list["$k"]  = array( "$k", "$k");





$HD_Form -> AddViewElement(gettext("LABEL"), "label", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FEE"), "fee", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "7%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("DATE START"), "startdate", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE STOP"), "stopdate", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("NBRUN"), "numberofrun", "6%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE LASTRUN"), "datelastrun", "12%", "center", "sort", "16");
$HD_Form -> AddViewElement(gettext("ACC PERFORM"), "totalcardperform", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TOTAL CREDIT"), "totalcredit", "10%", "center", "sort");


// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('label, fee, status,startdate,stopdate, numberofrun, datelastrun, totalcardperform, totalcredit');


$HD_Form -> CV_NO_FIELDS  = gettext("There are no subscriptions created!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;
// View Details
// $HD_Form -> FG_OTHER_BUTTON1 = true;
// |param| will be replace by id .
// you don't need that, if you don't supply any |param| string id will be append after FG_OTHER_BUTTON1_LINK.

//$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:;\" onClick=\"MM_openBrWindow('CC_entity_subscription_details.php?displayfooter=0&popup_select=1&displayheader=0&id=|param|','','scrollbars=yes,resizable=yes,width=600,height=350')\"";
//$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";

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

$HD_Form -> AddEditElement (gettext("LABEL"),
	"label",
	"$value",
	"INPUT",
	"size=35 maxlength=30",
	"3",
	gettext("Insert the service label"),
	"" , "", "", "", "" , "" , "" , "");

$HD_Form -> AddEditElement (gettext("FEE"),
	"fee",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"12",
	gettext("Insert the fee amount."),
	"" , "", "", "", "" , "", "", gettext("Set the fee amount that the user have to pay per month."));


$HD_Form -> AddEditElement ("STATUS",
	"status",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", "");

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


$HD_Form -> AddEditElement (gettext("EMAIL TO SEND REPORT"),
	"emailreport",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"1",
	gettext("Insert the email to send the report"),
	"" , "", "", "", "", "" , "NO-NULL", "");



$HD_Form -> FieldEditElement ('label, fee, status,startdate,stopdate, emailreport');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a service below");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


