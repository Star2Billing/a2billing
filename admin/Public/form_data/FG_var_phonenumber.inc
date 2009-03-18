<?php

getpost_ifset(array('id', 'number', 'id_phonebook', 'name', 'status',  'info'));


$HD_Form = new FormHandler("cc_phonenumber","phonenumber");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "number";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_phonenumber.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add PHONE NUMBER");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/telephone_add.png" ;


// TODO Integrate a generic LIST to Framework
$actived_list = Constants::getActivationList();

$HD_Form -> AddViewElement(gettext("PHONEBOOK"), "id_phonebook", "15%", "center", "sort", "15", "lie", "cc_phonebook", "name", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("PHONENUMBER"), "number", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "20%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amount", "15%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("DATE"), "creationdate", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "5%", "center", "sort", "", "list", $actived_list);

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id_phonebook, number, cc_phonenumber.name, amount, creationdate, status');


$HD_Form -> CV_NO_FIELDS  = gettext("No phone number has been created.");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;



$HD_Form -> AddEditElement (gettext("Phone Number"),
	"number",
	'$value',
	"INPUT",
	"size=30 maxlength=40",
	"0",
	gettext("Insert the phonenumber"),
	"" , "", "", "", "", "", "", gettext("Enter here the phonenumber to call"));


$HD_Form -> AddEditElement (gettext("Phone Book"),
	"id_phonebook",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_phonebook",
	"name, id",
	"", "", "%1", "", "");


$HD_Form -> AddEditElement (gettext("NAME"),
	"name",
	'$value',
	"INPUT",
	"size=30 maxlength=20",
	"",
	gettext("Insert the name "),
	"" , "", "", "", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("AMOUNT"),
	"amount",
	'$value',
	"INPUT",
	"size=10 maxlength=10",
	"4",
	gettext("Insert the amount "),
	"" , "", "", "", "" , "", "", "");


$HD_Form -> AddEditElement ("STATUS",
	"status",
	'1',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", gettext("Define if this phone number is active or not"));
	
	
$HD_Form -> AddEditElement (gettext("ADDITIONALINFO"),
	"info",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert additional information"),
	"" , "", "", "", "" , "", "", "");

			
	$HD_Form -> FieldEditElement ('number, id_phonebook, name, amount, status, info');

$HD_Form -> FG_EDITION_CLAUSE = " id='%id'";


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


