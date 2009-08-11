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



getpost_ifset(array('id', 'name','status','monday','tuesday','wednesday','thursday','friday','saturday','sunday','frequency','startingdate', 'expirationdate','id_cid_group' ,'id_card', 'description'));


$HD_Form = new FormHandler("cc_campaign","Campaign");

if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_campaign LEFT JOIN cc_card ON cc_card.id=id_card","Campaign");
} else {
	$HD_Form = new FormHandler("cc_campaign","Campaign");
}


$HD_Form -> FG_TABLE_ID = 'cc_campaign.id';


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$actived_list = Constants::getActivationList();
if ($form_action=="ask-edit") {
	$DBHandle  = DbConnect();
	$instance_table = new Table("cc_campaign", "id_card");
	$clause = "id = $id";
	$list =$instance_table -> Get_list($DBHandle, $clause, 0);;
	$id_card= $list[0][0];
}

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_campaign.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add CAMPAIGN");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/flag_yellow.png" ;


$HD_Form -> AddViewElement(gettext("NAME"), "name", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STARTING"), "startingdate", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("EXPIRATION"), "expirationdate", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "5%", "center", "sort", "", "list", $actived_list);

$HD_Form -> FieldViewElement ('cc_campaign.name, cc_campaign.startingdate, cc_campaign.expirationdate, username,cc_campaign.status');


$HD_Form -> CV_NO_FIELDS  = gettext("No campaigns have been created."); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;

$HD_Form -> AddEditElement (gettext("CAMPAIGN NAME"),
	"name",
	'$value',
	"INPUT",
	"size=30 maxlength=20",
	"3",
	gettext("Insert the tariff name "),
	"" , "", "", "", "" , "", "", "");


$HD_Form -> AddEditElement ("STATUS",
	"status",
	'1',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", gettext("Define if this campaign is active or not"));


if ($form_action=="ask-add") {
	
	$HD_Form -> AddEditElement (gettext("CUSTOMER ID"),
				   "id_card",		
				   "$value",	
				   "POPUPVALUE",	
				   "size=30 maxlength=50 ",	
				   "4",	
				   gettext("Insert the customer ID"),
				    "" , "", "", "", "", 				
					"A2B_entity_card.php?popup_select=1&"
					, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
					gettext("Select the owner of this campaing, but you may not modify it in the future"));
} else {
	
	$HD_Form -> AddEditElement (gettext("CUSTOMER ID"),
		"id_card",
		'$value',
		"INPUT",
		"size=30 maxlength=50 readonly",
		"4",
		gettext("Insert the Customer ID"),
		"" , "", "", "", "" , "", "", gettext("Customer ID Selected "));

}

if ($form_action!="ask-add") {
	$edit_col = ", id_campaign_config";
	$HD_Form -> AddEditElement (gettext("CONFIG TO APPLY"),
		   "id_campaign_config",
		   "",
		   "SELECT",
		   "", "", "",
		   "sql",
		   "cc_campaign_config,cc_campaignconf_cardgroup,cc_card_group,cc_card",
		   "cc_campaign_config.name, cc_campaign_config.id ,flatrate",
		   "cc_campaign_config.id = id_campaign_config AND id_card_group = cc_card_group.id AND cc_card_group.id = cc_card.id_group AND cc_card.id = $id_card", "", "%1 (%3 ".strtoupper(BASE_CURRENCY)." ) ", "", gettext("Define the configuration that you want apply on this campaign"));
}


// TODO integrate in new framework
if ($form_action=="ask-add") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$time = "value= '10:00:00'";
	$time_plus = "value= '18:00:00'";
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}


$HD_Form -> AddEditElement (gettext("CID GROUP"),
	   "id_cid_group",
	   "",
	   "SELECT",
	   "", "", "",
	   "sql",
	   "cc_outbound_cid_group",
	   "group_name, id",
	   "", "", "%1", "", "");


$HD_Form -> AddEditElement (gettext("STARTING DATE"),
	"startingdate",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $comp_date",
	"10",
	gettext("Insert the starting date"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"));

$HD_Form -> AddEditElement (gettext("EXPIRATION DATE"),
	"expirationdate",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $comp_date_plus",
	"10",
	gettext("Insert the expiration date"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"));


$HD_Form -> AddEditElement (gettext("FREQUENCY"),
	"frequency",
	'$value',
	"INPUT",
	"size=20 maxlength=10",
	"4",
	gettext("Insert the frequency of callback in min "),
	"" , "", "", "", "" , "", "", gettext("Please insert the frequency in minutes"));	

$HD_Form -> AddEditElement (gettext("DAILY START TIME"),
	"daily_start_time",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $time",
	"20",
	gettext("Insert the daily start time"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Please respect the format HH:MM:SS. For instance, '00:00:00'"));

$HD_Form -> AddEditElement (gettext("DAILY STOP TIME"),
	"daily_stop_time",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $time_plus",
	"20",
	gettext("Insert the daily stop time"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Please respect the format HH:MM:SS. For instance, '00:00:00'"));
	

$HD_Form -> AddEditElement (gettext("MONDAY"),
   "monday",
   '1',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the monday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
   
$HD_Form -> AddEditElement (gettext("TUESDAY"),
   "tuesday",
   '1',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the monday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
   
$HD_Form -> AddEditElement (gettext("WEDNESDAY"),
   "wednesday",
   '1',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the monday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
   
$HD_Form -> AddEditElement (gettext("THURSDAY"),
   "thursday",
   '1',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the thursday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
   
$HD_Form -> AddEditElement (gettext("FRIDAY"),
   "friday",
   '1',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the friday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
   
$HD_Form -> AddEditElement (gettext("SATURDAY"),
   "saturday",
   '0',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the saturday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
   
$HD_Form -> AddEditElement (gettext("SUNDAY"),
   "sunday",
   '0',
   "RADIOBUTTON",
   "",
   "",
   gettext("Choose if you want to enable the campaign callback for the sunday"),
   "" , "", "", "Yes :1, - No:0", "", "" , "", "");
	
$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "", "", "", "" );


$HD_Form -> FieldEditElement ("name, status, id_card $edit_col, id_cid_group, startingdate, expirationdate, frequency, daily_start_time, daily_stop_time, monday, tuesday, wednesday, thursday, friday, saturday, sunday, description" );
$HD_Form -> FG_EDITION_CLAUSE = " id='%id'";

if ($form_action!="ask-delete") {	
	$DBHandle_max  = DbConnect();
	$numrow = 0;
	$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_phonebook");
	if ( $resmax )
		$numrow = $resmax -> RecordCount( );
	
	if ($numrow>0) {
		$HD_Form -> AddEditElement (gettext("PHONE BOOK"),
			"phonebook",	
			'$value',	
			"SELECT",		
			'size="10" multiple="multiple"',
			"",		
			"",	
			"sql",		
			"campaigns_phonebooks",	
			"name, id",		
			"", "", "%1 : (%2)", "", "", "cc_campaign_phonebook:id_phonebook:cc_phonebook LEFT JOIN cc_campaign_phonebook ON cc_phonebook.id=cc_campaign_phonebook.id_phonebook:DISTINCT cc_phonebook.name, cc_phonebook.id: cc_campaign_phonebook.id_campaign = %id:id_campaign:%2 - (%1)::::%1:id='%1'");		
		
	}
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a ".$HD_Form->FG_INSTANCE_NAME." now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Setup those values to create the new")." ".$HD_Form->FG_INSTANCE_NAME;



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



