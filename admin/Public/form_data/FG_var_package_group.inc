<?php

getpost_ifset(array('id', 'name', 'description'));


$HD_Form = new FormHandler("cc_package_group","Package Group");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_package_group.php?form_action=ask-add&atmenu=prefixe&section=3_3";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Package Group");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/package_add.png" ;



$HD_Form -> AddViewElement(gettext("NAME"), "name", "35%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "35%", "center", "sort");
$HD_Form -> FieldViewElement ( 'name, description');


$HD_Form -> CV_NO_FIELDS  = gettext("NO PACKAGE GROUP CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '80%';


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


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
	

	
$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_package_offer");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow>0){ 
	$HD_Form -> AddEditElement (gettext("PACKAGES"),
		"packages",	
		'$value',	
		"SELECT",		
		'size="10" multiple="multiple"',
		"",		
		"",	
		"sql",		
		"group_packages",	
		"label, id",		
		"", "", "%1 : (%2)", "", "", "cc_packgroup_package:package_id:cc_package_offer LEFT JOIN cc_packgroup_package ON cc_package_offer.id=cc_packgroup_package.package_id:DISTINCT cc_package_offer.label, cc_package_offer.id: cc_packgroup_package.packagegroup_id = %id:packagegroup_id:%2 - (%1)::::%1:id='%1'");		
	
}
	
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



