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



getpost_ifset(array('id', 'name', 'activated' ,'id_support','type_user' ));



$HD_Form = new FormHandler("cc_support_component","Support Component");


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "CC_support_component.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add COMPONENT");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/brick_add.png" ;


// Code Here for Deleting the Dependent Records
// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_ticket");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_component ");

$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You have some tickets using this Support Component! Please comfirm that you really want to remove this Support Component? ");

$user_type = Constants::getComponentUserTypeList();
$activated_list = Constants::getActivationList();

$HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACTIVATED"), "activated", "15%", "center", "sort", "", "list", $activated_list);
$HD_Form -> AddViewElement(gettext("USER TYPE"), "activated", "25%", "center", "sort", "", "list", $user_type);
$HD_Form -> AddViewElement(gettext("SUPPORT BOX"), "id_support", "10%", "center", "sort", "15", "lie", "cc_support", "name", "id='%id'", "%1");

$HD_Form -> FieldViewElement ('id, name, activated,type_user, id_support ');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;


$HD_Form -> AddEditElement (gettext("NAME"),
	"name",
	'$value',
	"INPUT",
	"size=30 maxlength=20",
	"9",
	gettext("Insert the Support Box name "),
	"" , "", "", "", "", "", "", "" );

$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"activated",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this component"),
	"" , "", "", "Yes :1, - No:0", "", "", "" , "" );

$HD_Form -> AddEditElement (gettext("Support Box"),
   "id_support",
   "",
   "SELECT",
   "", "", "",
   "sql",
   "cc_support",
   "name, id",
   "", "", "%1", "", "");

$HD_Form -> AddEditElement (gettext("USER TYPE"),
	               "type_user",
	               '$value',
	               "SELECT",
	               "",
	               "",
	               "",
	               "list" , "", "",     "", $user_type, "%1", "", gettext("Define who can use this component.") );

$HD_Form -> FieldEditElement ('name, activated, id_support,type_user');



$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


