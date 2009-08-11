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



getpost_ifset(array('id', 'speeddial', 'id_cc_card', 'name', 'phone','popup_select','popup_formname','popup_fieldname'));

$HD_Form = new FormHandler("cc_speeddial","speeddial");

$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " creationdate";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$speeddial_list = array();
for ($k=0;$k<=9;$k++)
	$speeddial_list["$k"]  = array( "$k", "$k");


$HD_Form -> AddViewElement(gettext("SPEEDDIAL"), "speeddial", "20%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("PHONE"), "phone", "20%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "20%", "center", "sort", "26");
$HD_Form -> FieldViewElement ('speeddial, phone, name');


$HD_Form -> CV_NO_FIELDS  = gettext("There are no speed dials created.");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';

// My Code for Where Cluase

if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
    $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id_cc_card =".$_SESSION["card_id"];
	
if (strlen($HD_Form -> FG_EDITION_CLAUSE)>0)
    $HD_Form -> FG_EDITION_CLAUSE.=" AND ";
	$HD_Form -> FG_EDITION_CLAUSE.="id_cc_card =".$_SESSION["card_id"];
	

//end My Code for Where Clause

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("SPEEDDIAL"),
				   "speeddial",
				   "",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $speeddial_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("NAME"),
			   "name",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=50",
			   "0",
			   gettext("Insert the name"),
			   "" , "", "",	"", "", "",	"", gettext("Enter here the name/label that will identify this speed dial") );


$HD_Form -> AddEditElement (gettext("PHONE"),
			   "phone",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=50",
			   "4",
			   gettext("Insert the phone"),
			   "" , "", "",	"", "", "",	"", gettext("Enter here the phonenumber you want to call for this speed dial") );

$HD_Form -> FieldEditElement ('speeddial, name, phone');


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id_cc_card like '$filterprefix'";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path."/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

