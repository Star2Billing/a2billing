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



getpost_ifset(array('id', 'name', 'periode', 'type', 'maxvalue', 'minvalue', 'status', 'id_trunk', 'numberofrun', 'datelastrun', 'popup_select'));

$HD_Form = new FormHandler("cc_alarm","Alarms");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_alarm.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Alarm");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/bell_add.png" ;


// TODO Integrate a generic LIST to Framework
$period_list = Constants::getPeriodsList();


$actived_list = Constants::getActivationList() ;


$type_list = array();
$type_list["1"]  = array( gettext("Apply alarm on ALOC too low or too high"), "1");
$type_list["2"]  = array( gettext("Apply alarm on ASR too low or too high"), "2");
$type_list["3"]  = array( gettext("Apply alarm on CIC too many"), "3");


$daynumber_list = array();
for ($k=1;$k<=20;$k++)
        $daynumber_list["$k"]  = array( "$k", "$k");

$HD_Form -> AddViewElement(gettext("NAME"), "name", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PERIOD"), "periode", "10%", "center", "sort", "", "list", $period_list);
$HD_Form -> AddViewElement(gettext("TYPE"), "type", "20%", "center", "sort", "", "list", $type_list);
$HD_Form -> AddViewElement(gettext("MAX"), "maxvalue", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("MIN"), "minvalue", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "6%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("TRUNK"), "id_trunk", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREATION DATE"), "datecreate", "12%", "center", "sort", "22", "", "", "", "", "", "display_dateformat");




// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('name, periode, type, maxvalue, minvalue, status, id_trunk, datecreate');


$HD_Form -> CV_NO_FIELDS  = gettext("There are no Alarms Created");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;
// View Details
$HD_Form -> FG_OTHER_BUTTON1 = true;
// |param| will be replace by id .
// you don't need that, if you don't supply any |param| string id will be append after FG_OTHER_BUTTON1_LINK.

$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:;\" onClick=\"MM_openBrWindow('CC_entity_alarm_details.php?popup_select=2&displayfooter=0&displayheader=0&id=|param|','','scrollbars=yes,resizable=yes,width=600,height=350')\"";

$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";
$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext("View details");


$HD_Form -> AddEditElement (gettext("NAME"),
	"name",
	"$value",
	"INPUT",
	"",
	"3",
	gettext("Insert the alarm name"),
	"" , "", "", "", "" , "" , "yes" , "");

$HD_Form -> AddEditElement (gettext("PERIOD"),
	"periode",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $period_list, "%1" , "" , gettext("Interval to apply alarm"));

$HD_Form -> AddEditElement (gettext("TYPE"),
	"type",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $type_list, "%1", "", gettext("ALOC (average length of call) ; ASR (answer seize ratio) ; CIC (Consecutive Incomplete Calls)"));

$HD_Form -> AddEditElement (gettext("MAX VALUE"),
	"maxvalue",
	"$value",
	"INPUT",
	"",
	"",
	gettext("Insert the maximu value in") ,
	"" , "", "", "yes", "", "", "", gettext("Input the maximum value (between 0 and 1 for ASR ; > 0 for ALOC in sec ; > 0 for CIC)"));

$HD_Form -> AddEditElement (gettext("MIN VALUE"),
	"minvalue",
	'$value',
	"INPUT",
	"",
	"",
	"",
	"" , "", "", "", "", "", "", gettext("Input the minimum value (between 0 and 1 for ASR ; >= 0 for ALOC in sec ; not used by CIC) or leave it empty"));
$HD_Form -> AddEditElement(gettext("TRUNK"),
   "id_trunk",
   '$value',	
   "POPUPVALUE",	
   "",	
   "",
   "",
   "" , "", "",	"", "" , "A2B_entity_trunk.php?popup_select=1&", ", 'id_trunk','width=550,height=340,top=20,left=100,scrollbars=1'", gettext("Set if you want to apply the alarm on a specific trunk or leave empty for all trunk."));

$HD_Form -> AddEditElement (gettext("STATUS"),
	"status",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", "");

$HD_Form -> AddEditElement (gettext("EMAIL TO SEND ALARM"),
	"emailreport",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"1",
	gettext("Insert the email to send the alarm"),
	"" , "", "", "", "", "" , "yes", "");



$HD_Form -> FieldEditElement ('name, periode, type, maxvalue, minvalue, id_trunk, status, emailreport');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add an alarm below");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


