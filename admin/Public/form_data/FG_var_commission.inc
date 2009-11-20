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


getpost_ifset(array('id', 'card_id', 'date', 'payment','description','added_refill','refill_type'));

$HD_Form = new FormHandler("cc_agent_commission","Commission Agent");

$HD_Form -> FG_TABLE_ID = 'cc_agent_commission.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_commission_agent.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Commission");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/money_add.png" ;
$com_list = Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_agent_commission.id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "id_card", "10%", "center", "sort", "", "30", "", "", "", "", "linktocustomer_id");
$HD_Form -> AddViewElement(gettext("AGENT"), "id_agent", "10%", "center", "sort", "", "30", "", "", "", "", "linktoagent");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amount", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAID STATUS"),"paid_status", "10%", "center", "sort", "", "list", $com_list);
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "20%", "center", "sort", 50);
$HD_Form -> AddViewElement(gettext("LINK TO PAYMENT"), "id_payment", "10%", "center", "sort", "", "lie_link", "cc_logpayment", "payment, id", "id='%id'", "%1 ".BASE_CURRENCY." (id:%2)", "", "A2B_entity_payment.php");

$HD_Form -> FieldViewElement ('cc_agent_commission.id, id_card, id_agent, date, amount, commission_type , description, id_payment');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO COMMISSION AGENTS CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
//$HD_Form -> FG_EDITION = true;
//$HD_Form -> FG_DELETION = true;

if ($form_action=="ask-add" && !empty($id_card)) {
	$id_val = "value='$id_card'";
}

$HD_Form -> AddEditElement (gettext("CUSTOMER ID"),
			   "id_card",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50 ".$id_val,	
			   "4",	
			   gettext("Insert the Customer ID "),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Customer ID"));

$HD_Form -> AddEditElement (gettext("AGENT ID"),
			   "id_agent",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50 ".$id_val,	
			   "4",	
			   gettext("Insert the AGENT ID "),
			    "" , "", "", "", "", 				
				"A2B_entity_agent.php?popup_select=1&"
				, ", 'AgentSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Agent ID"));
			   
if ($form_action=="ask-add") {	
	$begin_date = date("Y");
	$end_date = date("-m-d H:i:s");
	$comp_date=$begin_date.$end_date;
	$comp_date="value='$comp_date'";
}

$HD_Form -> AddEditElement (gettext("PAYMENT DATE"),
			   "date",		
			   '$value',	
			   "INPUT",	
			   "size=40 READONLY maxlength=40  $comp_date",	
			   "10",	
			   gettext("Insert the current date"),
			   "" , "", "",	"", "", "", "", "" );

$HD_Form -> AddEditElement (gettext("COMMISSION AMOUNT"),
			   "amount",		
			   '$value',	
			   "INPUT",	
			   "size=60 maxlength=10",	
			   "12",	
			   gettext("Insert the commission amount"),
			   "" , "", "",	"", "" , "", "", gettext("Enter the amount in the currency base : ").BASE_CURRENCY );


$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
				"description",
				'',
				"TEXTAREA",
				"cols=50 rows=4",
				"",
				gettext("Insert the description"),
				"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("PAYMENT ID"),
			   "id_payment",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50 ".$id_val,	
			   "4",	
			   gettext("Insert the Payment ID "),
			    "" , "", "", "", "", 				
				"A2B_entity_payment.php?popup_select=1&"
				, ", 'PaymentSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Payment ID"));

$HD_Form -> AddEditElement (gettext("COMMISSION TYPE"),
               "commission_type",
               '$value',
               "SELECT",
               "",
               "",
               "",
               "list" , "", "",     "", $com_list, "%1", "", gettext("Define the type of this commission.") );

$HD_Form -> FieldEditElement ('id_card, id_agent, date, amount, description, id_payment, commission_type');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = "processing_commission_add";
$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


