<?php

getpost_ifset(array('id', 'label ','id_subscription','enable'));


$HD_Form = new FormHandler("cc_subscription_signup","Subscription in Signup");

$HD_Form -> FG_DEBUG = 0;


$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_subscriber_signup.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Subscriber in Signup");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/add.png" ;


$activated_list = Constants::getActivationList();
$HD_Form -> AddViewElement(gettext("LABEL"), "label", "20%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACTIVITY"), "enable", "20%", "center", "sort","", "list", $activated_list);
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "50%", "center", "SORT", "150");
$HD_Form -> FieldViewElement ( 'label, enable, description');


$HD_Form -> CV_NO_FIELDS  = gettext("NO CUSTOMER GROUP CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

$HD_Form -> AddEditElement ( gettext("LABEL"),	
		"label",	'$value',	
		"INPUT",
		"size=30 maxlength=40",
		"0",
		gettext("Insert the provider name"), 
		"" , "", "",	"", "", "", "", "" );
		
$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"enable",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this subscriber in the signup"),
	"" , "", "", "Yes :1, - No:0", "", "", "" , "" );

$CURRENCY = strtoupper(BASE_CURRENCY);	
$HD_Form -> AddEditElement (gettext("SUBSCRIBER"),
	   "id_subscription",
	   "",
	   "SELECT",
	   "", "", "",
	   "sql",
	   "cc_subscription_fee",
	   "label,id,fee",
	   "", "", "%1 - %3 $CURRENCY (id:%2)", "", "", "", '<OPTION  value="NULL" selected>'.gettext("NONE").'</OPTION>' );




$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
		"description",
		'',
		"TEXTAREA",
		"cols=50 rows=4",
		"",
		gettext("Insert the description"),
		"" , "", "", "", "" , "", "", "");

$HD_Form -> FieldEditElement ('label,enable,id_subscription, description');




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

