<?php


getpost_ifset(array('id', 'name', 'id_agi_conf', 'description'));


$HD_Form = new FormHandler("cc_card_group","Card Group");

$HD_Form -> FG_DEBUG = 0;


$HD_Form -> FG_TABLE_DEFAULT_ORDER = "name";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

// Code Here for Deleting the Dependent Records
// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_card");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_group ");

$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You have some cards using this Customers Group! Please comfirm that you really want to remove this Customers Group ? ");


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_card_group.php?form_action=ask-add&stitle=Card&section=1";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Group");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/group_add.png" ;


//$DBHandle  = DbConnect();
//$conf_table = new Table("cc_config_group", "group_title");
//$clause_conf_agi = "cc_config_group.group_title LIKE 'agi_conf_%'";
//$result_config= $conf_table -> Get_list ($DBHandle, $clause_conf_agi, null, null, null, null, null, null);
//$agi_list = array();
//$agi_list[-1] = array( gettext("NOT DEFINED"), "-1");
//foreach ($result_config as $value){
//	$agi_list[substr($value["group_title"],8)] = array($value["group_title"], substr($value["group_title"],8));	
//}

$HD_Form -> AddViewElement(gettext("NAME"), "name", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "25%", "center", "SORT", "40");
$HD_Form -> FieldViewElement ( 'name, description');


$HD_Form -> CV_NO_FIELDS  = gettext("NO CARD GROUP CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';



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

$HD_Form -> FieldEditElement ('name, description');


	
$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_service");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow>0){ 
	$HD_Form -> AddEditElement (gettext("RECURRING SERVICE"),
		"service",	
		'$value',	
		"SELECT",		
		'size="10" multiple="multiple"',
		"",		
		"",	
		"sql",		
		"cards_services",	
		"name, id",		
		"", "", "%1 : (%2)", "", "", "cc_cardgroup_service:id_service:cc_service LEFT JOIN cc_cardgroup_service ON cc_service.id=cc_cardgroup_service.id_service:DISTINCT cc_service.name, cc_service.id: cc_cardgroup_service.id_card_group = %id:id_card_group:%2 - (%1)::::%1:id='%1'");		
	
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


