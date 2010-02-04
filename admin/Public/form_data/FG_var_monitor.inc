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

$HD_Form = new FormHandler("cc_monitor","Monitoring");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_monitor.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Monitoring");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/map.png" ;


// TODO Integrate a generic LIST to Framework
$mquery_type_list = Constants::getMonitorQueryType_List();
$mresult_type_list = Constants::getMonitorResultType_List();
$actived_list = Constants::getActivationList() ;



$HD_Form -> AddViewElement(gettext("LABEL"), "label", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DIAL CODE"), "dial_code", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "30%", "center", "sort", "70");
$HD_Form -> AddViewElement(gettext("TEXT"), "text_intro", "30%", "center", "sort", "70");
$HD_Form -> AddViewElement(gettext("ENABLE"), "enable", "6%", "center", "sort", "", "list", $actived_list);


$HD_Form -> FieldViewElement ('label, dial_code, description, text_intro, enable');


$HD_Form -> CV_NO_FIELDS  = gettext("There are no monitoring created!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;


$HD_Form -> AddEditElement (gettext("LABEL"),
	"label",
	"$value",
	"INPUT",
	"size=30 maxlength=50",
	"3",
	gettext("Insert the label"),
	"" , "", "", "", "" , "" , "yes" , "");


$HD_Form -> AddEditElement (gettext("DIAL CODE"),
	"dial_code",
	"$value",
	"INPUT",
	"size=5 maxlength=3",
	"4",
	gettext("Insert the dial code"),
	"" , "", "", "", "" , "" , "yes" , "");


$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'',
	"TEXTAREA",
	"cols=60 rows=2",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("TEXT INTRO"),
	"text_intro",
	"$value",
	"INPUT",
	"size=30 maxlength=50",
	"3",
	gettext("Insert the text intro"),
	"" , "", "", "", "" , "" , "yes" , "");

$HD_Form -> AddEditElement (gettext("QUERY TYPE"),
   "query_type",
   '$value',
   "SELECT",
   "",
   "",
   "",
   "list", "", "", "", $mquery_type_list, "%1", "", gettext("Define type of the query that will be performed.") );

$HD_Form -> AddEditElement (gettext("QUERY / SHELL SCRIPT"),
	"query",
	'',
	"TEXTAREA",
	"cols=80 rows=4",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "" , "", "", gettext("Shell Script need to be copied into the directory").SCRIPT_CONFIG_DIR."<br>".gettext("Do not specify the path here."));

$HD_Form -> AddEditElement (gettext("SAY RESULT AS"),
   "result_type",
   '$value',
   "SELECT",
   "",
   "",
   "",
   "list", "", "", "", $mresult_type_list, "%1", "", gettext("Define type of result.") );

$HD_Form -> AddEditElement (gettext("ENABLE"),
   "enable",
   '0',
   "RADIOBUTTON",
   "",
   "",
   "",
   "" , "", "" , "Yes :1, - No:0", "", "" , "", "");



$HD_Form -> FieldEditElement ('label, dial_code, description, text_intro, query_type, query, result_type, enable');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add the Monitoring below");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


