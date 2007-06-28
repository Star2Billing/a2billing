<?php
getpost_ifset(array('id', 'config_title', 'config_key', 'config_value', 'config_description', 'config_group_id'));


$HD_Form = new FormHandler("cc_config ccc, cc_config_group ccg","Configuration");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="ccc.id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "config_group_id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_TABLE_CLAUSE = "ccc.config_group_id =  ccg.id";
$HD_Form -> FG_EDITION_CLAUSE = " ccc.config_group_id =  ccg.id AND ccc.id = %id";
$HD_Form -> AddViewElement(gettext("TITLE"), "config_title", "20%", "center");
$HD_Form -> AddViewElement(gettext("KEY"), "config_key", "15%", "center");
$HD_Form -> AddViewElement(gettext("VALUE"), "config_value", "20%", "center");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "config_description", "40%", "left");
$HD_Form -> AddViewElement(gettext("GROUP"), "group_title", "5%", "center");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('config_title, config_key, config_value, config_description, group_title');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 25;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = false;

$HD_Form -> AddEditElement (gettext("GROUP"),
	"group_title",
	'$value',
	"INPUT",
	"size=40 readonly",
	"",
	gettext("This is the group of the configuration. Not editable!"),
	"" , "", "", "", "", "", "", gettext("Insert the Group Name"));
	

$HD_Form -> AddEditElement (gettext("TITLE"),
	"config_title",
	'$value',
	"INPUT",
	"size=30 maxlength=100",
	"",
	gettext("Insert the TITLE"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Title"));

$HD_Form -> AddEditElement (gettext("KEY"),
	"config_key",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the TITLE"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Key"));

$HD_Form -> AddEditElement (gettext("VALUE"),
	"config_value",
	'$value',
	"INPUT",
	"size=30 ",
	"",
	gettext("Insert the starting date"),
	"" , "", "", "", "", "", "", gettext("Insert Value"));

$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"config_description",
	'',
	"TEXTAREA",
	"cols=80 rows=10",
	"",
	gettext("Insert the Description"),
	"" , "", "", "", "" , "", "", "");


$HD_Form -> FieldEditElement ('ccg.group_title, config_title, config_key, config_value, config_description');

$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


?>
