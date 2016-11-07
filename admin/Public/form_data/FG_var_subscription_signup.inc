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


getpost_ifset(array('id', 'label ','id_subscription','enable'));


$HD_Form = new FormHandler("cc_subscription_signup","Subscription in Signup");

$HD_Form -> FG_DEBUG = 0;


$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_subscriber_signup.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add New Subscription in Signup");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/add.png" ;


$cnts = new Constants();
$activated_list = $cnts->getActivationList();

$HD_Form -> AddViewElement(gettext("LABEL"), "label", "12%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACTIVITY"), "enable", "12%", "center", "sort","", "list", $activated_list);
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "35%", "center", "SORT", "150");
$HD_Form -> AddViewElement(gettext("SERVICE"), "id_subscription", "12%", "center", "sort", "15", "lie", "cc_subscription_service", "label", "id='%id'", "%1");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("CALL PLAN")."\">".gettext('PLAN')."</acronym>", "id_callplan", "12%", "center", "sort", "20", "lie", "cc_tariffgroup", "tariffgroupname", "id='%id'", "%1");
$HD_Form -> FieldViewElement ( 'label, enable, description, id_subscription, id_callplan');


$HD_Form -> CV_NO_FIELDS  = gettext("NO SUBSCRIPTION IN SIGNUP CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

$HD_Form -> AddEditElement ( gettext("LABEL"),
		"label",	'$value',
		"INPUT",
		"size=30 maxlength=40",
		"0",
		gettext("Insert the provider name"),
		"" , "", "",	"", "", "", "", "" );

$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"enable",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this subscriber in the signup"),
	"" , "", "", "Yes :1, - No:0", "", "", "" , "" );

$CURRENCY = strtoupper(BASE_CURRENCY);
$HD_Form -> AddEditElement (gettext("SUBSCRIPTION OPTIONS"),
	   "id_subscription",
	   "",
	   "SELECT",
	   "", "", "",
	   "sql",
	   "cc_subscription_service",
	   "label,id,fee",
	   "", "", "%1 - %3 $CURRENCY (id:%2)", "", "", "", '<OPTION  value="NULL" selected>'.gettext("NONE").'</OPTION>' );


$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
		"description",
		'',
		"TEXTAREA",
		"cols=50 rows=4",
		"",
		gettext("Insert the description"),
		"" , "", "", "", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("CALL PLAN"),
			   "id_callplan",
			   "",
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_tariffgroup",
			   "tariffgroupname, id",
			   "", "", "%1", "", gettext("Define the callplan to associate to your Subscription") );


$HD_Form -> FieldEditElement ('label,enable,id_subscription, description, id_callplan');




$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add $HD_Form->FG_INSTANCE_NAME.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";

