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




getpost_ifset(array('id', 'name','context','flatrate', 'description'));


$HD_Form = new FormHandler("cc_campaign_config","Campaign Config");

$HD_Form -> FG_DEBUG = 0;


$HD_Form -> FG_TABLE_DEFAULT_ORDER = "name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

// Code Here for Deleting the Dependent Records
// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_campaign");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_campaign_config ");

$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You have some campaign using this Configuration! Please comfirm that you really want to remove this Configuration ? ");


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_campaign_config.php?form_action=ask-add&stitle=Card&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Group");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/cog_add.png" ;



$HD_Form -> AddViewElement(gettext("NAME"), "name", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "35%", "center", "SORT", "40");
$HD_Form -> AddViewElement(gettext("CAMPAIGN CONTEXT"), "context", "20%", "center", "SORT", "20");
$HD_Form -> AddViewElement(gettext("CAMPAIGN FLATRATE"), "flatrate", "20%", "center", "SORT", "20");

$HD_Form -> FieldViewElement ( 'name, description, context, flatrate');


$HD_Form -> CV_NO_FIELDS  = gettext("NO CAMPAIGN CONFIGURATION CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';


$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement ( gettext("NAME"),	
	"name",	'$value',	
	"INPUT",
	"size=30 maxlength=40",
	"0",
	gettext("Insert the provider name"), 
	"" , "", "",	"", "", "", "", "" );


$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "" , "", "", "");

	
$HD_Form -> AddEditElement ( gettext("CAMPAIGN CONTEXT"),	
	"context",	'$value',	
	"INPUT",
	"size=30 maxlength=40",
	"0",
	gettext("Insert the context to use to forward the callback campaign"), 
	"" , "", "",	"", "", "", "", "" );
	

$HD_Form -> AddEditElement(gettext("CAMPAIGN FLAT RATE"),
   "flatrate",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the flat rate"),
   "" , "", "",	"", "" , "", "", gettext("The callback campaign system now can't use the billing system, you have to define cost for all callback "), null, null, null);


$HD_Form -> FieldEditElement ('name, description,context,flatrate');


	
$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_card_group");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow>0) { 
	$HD_Form -> AddEditElement (gettext("CARD GROUP"),
		"cardgroupe",	
		'$value',	
		"SELECT",		
		'size="10" multiple="multiple"',
		"",		
		"",	
		"sql",		
		"campaign_cards",	
		"name, id",		
		"", "", "%1 : (%2)", "", "", "cc_campaignconf_cardgroup:id_card_group:cc_card_group LEFT JOIN cc_campaignconf_cardgroup ON cc_card_group.id=cc_campaignconf_cardgroup.id_card_group:DISTINCT cc_card_group.name, cc_card_group.id: cc_campaignconf_cardgroup.id_campaign_config = %id:id_campaign_config:%2 - (%1)::::%1:id='%1'");		
	
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add $HD_Form->FG_INSTANCE_NAME.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


