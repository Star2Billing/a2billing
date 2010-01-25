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


getpost_ifset(array('IDCust', 'id', 'cid', 'activated'));

$HD_Form = new FormHandler("cc_callerid","CallerID");

$HD_Form -> FG_DEBUG = 0;

$HD_Form ->FG_LIST_ADDING_BUTTON2 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK2 = "A2B_entity_callerid.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT2 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG2 = gettext("Add CallerID");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG2 = Images_Path ."/page_white_add.png" ;

$HD_Form -> FG_OTHER_BUTTON3 = true;
$HD_Form -> FG_OTHER_BUTTON3_LINK = "A2B_entity_logrefill.php?form_action=ask-add&card_id=|col_orig1|";
$HD_Form -> FG_OTHER_BUTTON3_IMG = Images_Path . "/brick_add.png";
$HD_Form -> FG_OTHER_BUTTON3_ALT = gettext('ADD REFILL');


$actived_list = Constants::getActivationTrueFalseList();
$yesno = Constants::getYesNoList();

$HD_Form -> AddViewElement(gettext("CALLERID"), "cid", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "id_cc_card", "25%", "center", "sort", "", "lie_link", "cc_card", "username, id", "id='%id'", "%1", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("CUSTOMER NAME"), "id_cc_card", "25%", "center", "sort", "", "lie_link", "cc_card", "lastname, id, firstname", "id='%id'", "%1 %3", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("ACTIVATED"), "activated", "15%", "center", "sort", "", "list", $actived_list);

$HD_Form -> FieldViewElement ('cid, id_cc_card, id_cc_card, cc_callerid.activated, cc_callerid.id');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ").strtoupper($HD_Form->FG_INSTANCE_NAME).gettext(" CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("CALLERID"),
			   "cid",
			   '$value',
			   "INPUT",
			   "size=20 maxlength=30",
			   "18",
			   gettext("Insert the CallerID"),
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("ACTIVATED"),
			   "activated",
			   't',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to activate this card"),
			   "" , "", "", "Yes :t, - No:f", "", "", "", "" );

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


$HD_Form -> FieldEditElement ('cid, activated, id_cc_card');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'callerid';
$HD_Form -> FG_FILTERFIELDNAME = gettext('CALLERID');

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="cid like '$filterprefix%'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify via the form below, the properties of the callerID");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_LINK="A2B_entity_payment.php?stitle=Payment_add&form_action=ask-add&card_id=|col_orig1|";
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path . "/money.png";
$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext('ADD PAYMENT');



$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click Confirm Data to apply your changes");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



