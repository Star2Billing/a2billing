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


getpost_ifset(array('id', 'calledstation', 'id_cc_card'));

$HD_Form = new FormHandler("cc_call","Call");

$HD_Form -> FG_DEBUG = 0;


$HD_Form -> AddViewElement(gettext("ID"), "id", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CALLEDSTATION"), "calledstation", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "card_id", "25%", "center", "sort", "", "lie_link", "cc_card", "username,id", "id='%id'", "%1", "", "A2B_entity_card.php");

$HD_Form -> FieldViewElement ('id, calledstation, card_id, id');



$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO CALL CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> FG_ADDITION = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = true;

$HD_Form -> AddEditElement (gettext("CALL ID"),
			   "id",
			   '$value',
			   "INPUT",
			   "size=10 maxlength=10",
			   "18",
			   "",
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("ID CARD"),
			   "card_id",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50",	
			   "4",	
			   gettext("Insert the Id CardNumber"),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Define the card number ID to use."));

$HD_Form -> AddEditElement (gettext("CALLED NUMBER"),
			   "calledstation",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=30",
			   "18",
			   "",
			   "" , "", "",	"", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("Source"),
			   "src",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=30",
			   "18",
			   "",
			   "" , "", "",	"", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("Dnid"),
			   "dnid",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=30",
			   "18",
			   "",
			   "" , "", "",	"", "" , "", "", "");
			   
$HD_Form -> AddEditElement (gettext("Duration"),
			   "sessiontime",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=30",
			   "18",
			   "",
			   "" , "", "",	"", "" , "", "", "");



$HD_Form -> FieldEditElement ('id, card_id, calledstation, src, dnid, sessiontime');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'id';
$HD_Form -> FG_FILTERFIELDNAME = 'call ID';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id like '$filterprefix%'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify via the form below, the properties of the call");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this call, click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new call")."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click Confirm Data to apply your changes");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = 'call-log-customers.php?nodisplay=1&posted=1';
$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE;
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE;



