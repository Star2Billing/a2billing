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



getpost_ifset(array('id', 'agent_id', 'date', 'credit','description'));


if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_logrefill_agent LEFT JOIN cc_agent ON cc_agent.id=agent_id","Refill Agent");
} else {
	$HD_Form = new FormHandler("cc_logrefill_agent","Agent Refill");
}

$HD_Form -> FG_TABLE_ID = 'cc_logrefill_agent.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_logrefill_agent.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Refill");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/time_add.png" ;


$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_logrefill_agent.id", "10%", "center", "sort");
if (has_rights (ACX_ADMINISTRATOR)) { 
	$HD_Form -> AddViewElement(gettext("AGENT"), "agent_id", "20%", "center", "sort", "", "30", "", "", "", "", "linktoagent");
}else{
	$HD_Form -> AddViewElement(gettext("AGENT"), "agent_id", "20%", "center", "sort", "", "30", "", "", "", "", "nameofagent");
}
$HD_Form -> AddViewElement(gettext("REFILL DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("REFILL TYPE"),"refill_type", "10%", "center", "sort", "", "list", $list_refill_type);
$HD_Form -> AddViewElement(gettext("REFILL AMOUNT"), "credit", "15%", "center", "sort","", "", "", "", "", "", "display_money");

$HD_Form -> FieldViewElement ('cc_logrefill_agent.id, agent_id, date, cc_logrefill_agent.refill_type as refill_type, cc_logrefill_agent.credit');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO AGENT REFILL CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_refill_info_agent.php?id=";
$HD_Form -> FG_ADDITION = true;


if (!($popup_select>=1))	 $HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'refill_agent_selection';
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for refills created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('DATE');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_FIELD = 'date';
$HD_Form -> FG_FILTER_SEARCH_DELETE_ALL =false;
$HD_Form -> AddSearchElement_C1(gettext("LOGIN"), 'login','logintype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("FIRSTNAME"),'firstname','firstnametype');


if (has_rights (ACX_MODIFY_REFILLS)) {
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;	
	
	$HD_Form -> AddEditElement (gettext("ID AGENT"),
				   "agent_id",		
				   "$value", "POPUPVALUE", "size=30 maxlength=50",	
				   "4",	gettext("Insert the Customer ID"),
				    "" , "", "", "", "", 				
					"A2B_entity_agent.php?popup_select=1&", ", 'AgentSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
					gettext("Select the Agent ID"));
	
	if ($form_action=="ask-add") {
		$begin_date = date("Y");
		$end_date = date("-m-d H:i:s");
		$comp_date=$begin_date.$end_date;
		$comp_date="value='$comp_date'";
	}
	
	$HD_Form -> AddEditElement (gettext("REFILL DATE"),
				   "date",		
				   '$value',	
				   "INPUT",	
				   "size=40 maxlength=40  $comp_date",	
				   "10",	
				   gettext("Insert the current date"),
				   "" , "", "",	"", "", "", "", "" );
	
	$HD_Form -> AddEditElement (gettext("REFILL AMOUNT"),
				   "credit",		
				   '$value',	
				   "INPUT",	
				   "size=60 maxlength=10",	
				   "12",	
				   gettext("Insert the credit amount"),
				   "" , "", "",	"", "" , "", "", gettext("Enter the amount in the currency base : ").BASE_CURRENCY );
	
	$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
					"description",
					'',
					"TEXTAREA",
					"cols=50 rows=4",
					"",
					gettext("Insert the description"),
					"" , "", "", "", "" , "", "", "");
				   
	$HD_Form -> AddEditElement (gettext("REFILL TYPE"),
	               "refill_type",
	               '$value',
	               "SELECT",
	               "",
	               "",
	               "",
	               "list" , "", "",     "", $list_refill_type, "%1", "", gettext("Define type for payment and refill,if created.") );
	
	$HD_Form -> FieldEditElement ('agent_id, date, credit, description, refill_type');
	
	$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
	$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
	$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");
	
	
	$HD_Form -> FG_INTRO_TEXT_ADITION = '';
	$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");
	
	
	$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
	$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue.");
	
	$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'add_agent_refill';
	
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
	
}

