<?php
getpost_ifset(array('id', 'numbertodial', 'id_cc_campaign', 'name', 'activated', 'last_attempt', 'inuse', 'additionalinfo'));

$plang='en';

$HD_Form = new FormHandler("cc_phonelist","phonelist");

$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "numbertodial";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


// TODO Integrate a generic LIST to Framework
$actived_list = Constants::getActivationList();
$yesno_list = Constants::getYesNoList();

$HD_Form -> AddViewElement(gettext("CAMPAIGN"), "id_cc_campaign", "12%", "center", "sort", "15", "lie", "cc_campaign", "campaign_name", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("PHONENUMBER"), "numbertodial", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "14%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("LAST_ATTEMPT"), "last_attempt", "14%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("INUSE"), "inuse", "5%", "center", "sort", "", "list", $yesno_list);
$HD_Form -> AddViewElement(gettext("ENABLE"), "enable", "5%", "center", "sort", "", "list", $yesno_list);
$HD_Form -> AddViewElement(gettext("<acronym title=\"NUMBER TRIALS\">NT</acronym>"), "num_trials_done", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("USED MINUTE"), "secondusedreal", "10%", "center", "SORT", "30", "", "", "", "", "", "display_minute");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id_cc_campaign, numbertodial, name, creationdate, last_attempt, inuse, enable, num_trials_done, secondusedreal');


$HD_Form -> CV_NO_FIELDS  = gettext("No phone list has been created.");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;





$HD_Form -> AddEditElement (gettext("Phone Number"),
	"numbertodial",
	'$value',
	"INPUT",
	"size=30 maxlength=40",
	"0",
	gettext("Insert the phonenumber"),
	"" , "", "", "", "", "", "", gettext("Enter here the phonenumber to call"));

$HD_Form -> AddEditElement (gettext("CAMPAIGN"),
	"id_cc_campaign",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_campaign",
	"campaign_name, id",
	"", "", "%1", "", "");

$HD_Form -> AddEditElement (gettext("NAME"),
	"name",
	'$value',
	"INPUT",
	"size=30 maxlength=20",
	"",
	gettext("Insert the name "),
	"" , "", "", "", "" , "", "", "");

if ($form_action=="ask-add" || $form_action=="add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";

	$HD_Form -> AddEditElement (gettext("LAST ATTEMPT DATE"),
			"last_attempt",
			'$value',
			"INPUT",
			"size=40 maxlength=40  $comp_date",
			"10",
			gettext("Insert the last attempt date"),
			"" , "", "", "", "", "", "res_display_dateformat", gettext("Format: YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'") );
}


$HD_Form -> AddEditElement (gettext("ENABLE"),
	"enable",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if this phonenumber would be enable or not"),
	"" , "", "","Yes :1, - No:0", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("ADDITIONALINFO"),
	"additionalinfo",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert additional information"),
	"" , "", "", "", "" , "", "", "");

if ($form_action!="ask-add" && $form_action!="add"){
	$HD_Form -> AddEditElement (gettext("INUSE"),
		"inuse",
		'1',
		"RADIOBUTTON",
		"",
		"",
		gettext("Choose the status of this phonenumber"),
		"" , "", "","In use :1, - Not in use :0", "" , "", "", "");
}																															     
if ($form_action=="ask-add" || $form_action=="add") 
	$HD_Form -> FieldEditElement ('numbertodial, id_cc_campaign, name, last_attempt, enable, additionalinfo');
else if ($form_action!="ask-add" && $form_action!="add")
	$HD_Form -> FieldEditElement ('numbertodial, id_cc_campaign, name, enable, additionalinfo, inuse');
else
	$HD_Form -> FieldEditElement ('numbertodial, id_cc_campaign, name, enable, additionalinfo');
	
$HD_Form -> FG_EDITION_CLAUSE = " id='%id'";


if (DB_TYPE == "postgres"){
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";	
}else{
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now()";	
}




$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a $HD_Form->FG_INSTANCE_NAME now.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
