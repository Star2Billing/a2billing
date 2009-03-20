<?php

getpost_ifset(array('id', 'label', 'packagetype', 'billingtype','startday', 'freetimetocall'));

$HD_Form = new FormHandler("cc_package_offer","OFFER PACKAGE");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " label";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_package.php?form_action=ask-add&atmenu=prefixe&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Package");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/package_add.png" ;




$packagetype_list = Constants::getPackagesTypeList();
$billingtype_list = Constants::getBillingPeriodsList();

// If packagetype == NUMBER OF FREE CALL -> concat unit with "times"
// Else concat with "sec"
$filter_unit = 'CASE packagetype WHEN 1 THEN CONCAT( freetimetocall, " times" ) WHEN 0 THEN "ALL" ELSE CONCAT( freetimetocall, " sec" ) END ';

$HD_Form -> AddViewElement(gettext("LABEL"), "label", "18%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "15%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("PACKAGETYPE"), "packagetype", "15%", "center", "sort","","list",$packagetype_list);
$HD_Form -> AddViewElement(gettext("BILLINGTYPE"), "billingtype", "15%", "center", "sort","","list",$billingtype_list);
$HD_Form -> AddViewElement(gettext("STARTDAY"), "startday", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FREE"), "freetimetocall", "17%", "center", "sort", "30", "", "", "", "", "", "");

$HD_Form -> FieldViewElement ('label, creationdate, packagetype, billingtype, startday, '.$filter_unit);

$HD_Form -> FG_ACTION_SIZE_COLUMN='15%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("LABEL"),
	"label",	
	'$value',	
	"INPUT",	
	"size=30 maxlength=30",	
	"0",	
	gettext("Insert the package label"),
	"" , "", "",	"", "", "", "",	"");

$HD_Form -> AddEditElement (gettext("PACKAGETYPE"),
	"packagetype",		
	'$value',	
	"SELECT",	
	"",	
	"",	
	"",
	"list" , "", "", "", $packagetype_list, "%1", "", "");

$HD_Form -> AddEditElement (gettext("BILLINGTYPE"),
	"billingtype",
	'$value',	
	"SELECT",	
	"",	
	"",	
	"",
	"list" , "", "", "", $billingtype_list, "%1", "", "");

if (isset($form_action) && $form_action=="ask-add") {
	$default_init = "value='0'";
}

$HD_Form -> AddEditElement (gettext("STARTDAY"),
   "startday",
   '$value',	
   "INPUT",	
   "size=10 $default_init maxlength=5",	
   "4",	
   gettext("Insert the start day"),			   
   "" , "", "",	"", "", "", "", gettext("The value will be related to billingtype ; if monthly accepted value are 1-31 ; if Weekly accepted value 1-7 (Monday to Sunday) "));


$HD_Form -> AddEditElement (gettext("FREE UNITS"),
   "freetimetocall",	
   '$value',	
   "INPUT",	
   "size=10 maxlength=10",	
   "4",	
   gettext("Insert the amount of free time to call (seconds)"),
   "" , "", "",	"", "", "", "", gettext("Insert the amount of free units, in seconds, for UNLIMITED, FREE MINUTES, NORMAL. For NUMBER OF FREE CALLS insert the number of free calls"));
   

	
$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_ratecard");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow>0) {
	$HD_Form -> AddEditElement (gettext("RATE ASSOCIATE"),
		"rates",	
		'$value',	
		"SELECT",		
		'size="25" multiple="multiple"',
		"",		
		"",	
		"sql",		
		"package_rates",	
		"",		
		"", "", "%1 : (%2)", "", "", "cc_package_rate:rate_id:cc_ratecard LEFT JOIN cc_package_rate ON cc_ratecard.id=cc_package_rate.rate_id:DISTINCT CONCAT(cc_ratecard.destination,' - ',cc_ratecard.dialprefix ), cc_ratecard.id,cc_ratecard.destination, cc_ratecard.dialprefix: cc_package_rate.package_id = %id:package_id:%3 - ( %4 )::::%1:id='%1'");		
	
}   

$HD_Form -> FieldEditElement ('label, packagetype, billingtype, startday, freetimetocall');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the properties of the")." ".$HD_Form->FG_INSTANCE_NAME.".";
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Setup those values to create the new")." ".$HD_Form->FG_INSTANCE_NAME;

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



