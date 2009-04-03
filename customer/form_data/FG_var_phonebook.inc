<?php

getpost_ifset(array('id', 'name', 'description ,id_card'));

$HD_Form = new FormHandler("cc_phonebook ", gettext("Phone Book"));


$HD_Form -> FG_DEBUG = 0;

$HD_Form -> FG_TABLE_ID = 'cc_phonebook.id';
$HD_Form -> FG_TABLE_CLAUSE = " cc_phonebook.id_card=".$_SESSION['card_id'] ;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

// Code Here for Deleting the Dependent Records
// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_phonenumber");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_phonebook ");

$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You have some phone using this PhoneBook! Please comfirm that you really want to remove this PhoneBook ? ");


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_phonebook.php?form_action=ask-add&stitle=Card&section=16";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add PhoneBook");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/book_add.png" ;


$HD_Form -> AddViewElement(gettext("NAME"), "name", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "25%", "center", "SORT", "40");
$HD_Form -> FieldViewElement ( 'cc_phonebook.name, cc_phonebook.description');


$HD_Form -> CV_NO_FIELDS  = gettext("NO PHONEBOOK CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_ADDITION = true;

$HD_Form -> AddEditElement ( gettext("NAME"),	
	"name",	'$value',	
	"INPUT",
	"size= maxlength=40",
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

	
$HD_Form -> FieldEditElement ('name, description');



$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "id_card";
$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "".$_SESSION['card_id'];


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add").' '.$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


