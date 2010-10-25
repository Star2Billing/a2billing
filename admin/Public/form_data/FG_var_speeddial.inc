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

if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_speeddial LEFT JOIN cc_card ON cc_card.id = cc_speeddial.id_cc_card","speed dial");
}else{
	$HD_Form = new FormHandler("cc_speeddial ","speed dial");
}
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "cc_speeddial.creationdate";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_TABLE_ID="cc_speeddial.id";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_speeddial.php?form_action=ask-add&atmenu=speeddial&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Speed Dial");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/page_white_add.png" ;


$speeddial_list = array();
for ($k=0;$k<=9;$k++)
	$speeddial_list["$k"] = array("$k", "$k");


$HD_Form -> AddViewElement(gettext("SPEED DIAL"), "cc_speeddial.speeddial", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("PHONE NUMBER"), "cc_speeddial.phone", "20%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("NAME"), "cc_speeddial.name", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), " cc_speeddial.id_cc_card", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer_id");
$HD_Form -> AddViewElement(gettext("DATE"), "cc_speeddial.creationdate", "20%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");


$HD_Form -> FieldViewElement ('cc_speeddial.speeddial, cc_speeddial.phone, cc_speeddial.name, cc_speeddial.id_cc_card, cc_speeddial.creationdate');


$HD_Form -> CV_NO_FIELDS  = gettext("NO SPEED DIALS HAVE BEEN CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;

$HD_Form -> AddEditElement (gettext("SPEED DIAL"),
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
			   "" , "", "",	"", "", "",	"", gettext("Enter the name or label that will identify this speed dial") );


$HD_Form -> AddEditElement (gettext("PHONE"),
			   "phone",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=50",	
			   "4",	
			   gettext("Insert the phone"),
			   "" , "", "",	"", "", "",	"", gettext("Enter the phone number for the speed dial.") );


$HD_Form -> AddEditElement (gettext("ID CARD"),
			   "id_cc_card",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50",	
			   "4",	
			   gettext("Insert the Id CardNumber"),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Define the card number ID to use."));

$HD_Form -> FieldEditElement ('cc_speeddial.speeddial, cc_speeddial.name, cc_speeddial.phone, cc_speeddial.id_cc_card');


$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'username';
$HD_Form -> FG_FILTERFIELDNAME = gettext('Account Number');
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click Confirm Data.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



