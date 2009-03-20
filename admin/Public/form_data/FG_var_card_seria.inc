<?php

getpost_ifset(array('id', 'name', 'description'));


$HD_Form = new FormHandler("cc_card_seria","Card series");
$HD_Form -> FG_DEBUG = 0;


$HD_Form -> FG_TABLE_DEFAULT_ORDER = "name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

// Code Here for Deleting the Dependent Records
// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = false;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = false;
$HD_Form -> FG_FK_TABLENAMES = array("cc_card");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_seria ");
$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You have some cards using this Seria! Please comfirm that you really want to remove this seria ? ");


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_card_seria.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Series");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/page_white_add.png" ;


$HD_Form -> AddViewElement(gettext("ID"), "id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("NAME"), "name", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "40%", "center", "SORT", "40");
$HD_Form -> AddViewElement(gettext("SEQUENCE NUMBER"), "value", "15%", "center", "SORT", "40");
$HD_Form -> FieldViewElement ( 'id, name, description, value');


$HD_Form -> CV_NO_FIELDS  = gettext("NO SERIES CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = false;

$HD_Form -> AddEditElement ( gettext("NAME"),	
				"name",	'$value',	
				"INPUT",
				"size=30 maxlength=40",
				"0",
				gettext("Insert the Seria name"), 
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


