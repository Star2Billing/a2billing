<?php

getpost_ifset(array('id', 'name','status','callerid','forward_number','startingdate', 'expirationdate', 'id_card', 'description'));


$HD_Form = new FormHandler("cc_campaign","Campaign");

if (!isset($form_action) || $form_action=="list"){
	$HD_Form = new FormHandler("cc_campaign LEFT JOIN cc_card ON cc_card.id=id_card","Campaign");
}else{
	$HD_Form = new FormHandler("cc_campaign","Campaign");
}


$HD_Form -> FG_TABLE_ID = 'cc_campaign.id';


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$actived_list = Constants::getActivationList();

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_campaign.php?form_action=ask-add&section=16";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add CAMPAIGN");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/flag_yellow.png" ;


$HD_Form -> AddViewElement(gettext("NAME"), "name", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STARTINGDATE"), "startingdate", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("EXPIRATIONDATE"), "expirationdate", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("CARD"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("USED MINUTE"), "secondusedreal", "10%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "5%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("NUMBER OF CALLS"), "nb_callmade", "8%", "center", "SORT", "30");


$HD_Form -> FieldViewElement ('cc_campaign.name, cc_campaign.startingdate, cc_campaign.expirationdate, username, cc_campaign.secondusedreal,cc_campaign.status, cc_campaign.nb_callmade');


$HD_Form -> CV_NO_FIELDS  = gettext("No campaigns have been created."); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;



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


$HD_Form -> AddEditElement (gettext("ID CARD"),
			   "id_card",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50",	
			   "4",	
			   gettext("Insert the Id CardNumber"),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Card Number ID"));


// TODO integrate in new framework
if ($form_action=="ask-add") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}




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


$HD_Form -> AddEditElement (gettext("CALLERID"),
	"callerid",
	'$value',
	"INPUT",
	"size=30 maxlength=20",
	"18",
	gettext("Insert the CallerID to use for the call "),
	"" , "", "", "", "" , "", "", "");	

$HD_Form -> AddEditElement (gettext("FORWARD NUMBER"),
	"forward_number",
	'$value',
	"INPUT",
	"size=30 maxlength=20",
	"11",
	gettext("Insert the number to use to forward the call "),
	"" , "", "", "", "" , "", "", "");	
	
	
	
	
$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "", "", "", "" );


$HD_Form -> FieldEditElement ('name, status,id_card, startingdate, expirationdate, callerid,forward_number, description');
$HD_Form -> FG_EDITION_CLAUSE = " id='%id'";

	
$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_phonebook");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow>0){ 
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




if (DB_TYPE == "postgres") {
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";
} else {
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now()";
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



