<?php

getpost_ifset(array('id', 'billingtype', 'did', 'startingdate', 'expirationdate', 'id_cc_didgroup', 'id_cc_country', 'activated', 'fixrate', 'id_trunk'));


$HD_Form = new FormHandler("cc_did","did");

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_did.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add DID");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/telephone_add.png" ;

$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "did";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'add_did_use';
$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_DELETE = 'did_use_delete';
$HD_Form -> FG_ADDITIONAL_FUNCTION_BEFORE_DELETE = 'is_did_in_use';

$actived_list = Constants::getActivationList();
$billingtype_list = Constants::getBillingTypeList();
// -- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free
$billingtype_list_short = Constants::getBillingTypeShortList();


$HD_Form -> AddViewElement(gettext("DID"), "did", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("BILLING"), "billingtype", "8%", "center", "sort", "", "list", $billingtype_list_short);
$HD_Form -> AddViewElement(gettext("START DATE"), "startingdate", "14%", "center", "sort", "19", "", "", "", "",  "display_dateformat");
$HD_Form -> AddViewElement(gettext("EXPIRY DATE"), "expirationdate", "14%", "center", "sort", "19", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("DIDGROUP"), "id_cc_didgroup", "12%", "center", "sort", "15", "lie", "cc_didgroup", "didgroupname", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("COUNTRY"), "id_cc_country", "12%", "center", "sort", "15", "lie", "cc_country", "countryname", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("<acronym title=\"ACTIVATED\">".gettext("ACT")."</acronym>"), "activated", "5%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("<acronym title=\"MINUTES USED\">".gettext("MU")."</acronym>"), "secondusedreal", "4%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$HD_Form -> AddViewElement(gettext("<acronym title=\"MONTHLY RATE\">".gettext("MR")."</acronym>"), "fixrate", "8%", "center", "SORT", "30");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('did, billingtype, startingdate, expirationdate, id_cc_didgroup, id_cc_country, activated, secondusedreal, fixrate');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_SPLITABLE_FIELD = 'did';



// TODO integrate in Framework
if ($form_action=="ask-add" || $form_action=="purchase") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

if ($form_action=="purchase") {
	$didval = "value='".$did."'";
	$fixval = "value='".$fixrate."'";
}
if ($form_action=="ask-add") {
	$fixval = "value='0'";
}

$did_regexpress = '9';
if ($form_action=="ask-edit" || $form_action=="edit") {
	$did_regexpress = '13';
}

$HD_Form -> AddEditElement (gettext("DID"),
	"did",
	'$value',
	"INPUT",
	"size=30 maxlength=30 $didval",
	"$did_regexpress",
	gettext("Insert the DID"),
	"" , "", "", "", "" , "", "" ,
	gettext("Define the DID's. If you ADD a new DID, NOT an EDIT, you can define a range of DID. <br>80412340210-80412340218 would add all DID's between the range, whereas DIDs separated by a comma e.g. 80412340210,80412340212,80412340214 would only add the individual DID listed."));

$HD_Form -> AddEditElement (gettext("BILLING"),
	"billingtype",
	"",
	"SELECT",
	"",
	"",
	"",
	"list" , "", "",  "", $billingtype_list, "%1" , "", "");

$HD_Form -> AddEditElement (gettext("START DATE"),
	"startingdate",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $comp_date",
	"10",
	gettext("Insert the starting date"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'") );

$HD_Form -> AddEditElement (gettext("EXPIRY DATE"),
	"expirationdate",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $comp_date_plus",
	"10",
	gettext("Insert the expiration date"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'") );

$HD_Form -> AddEditElement (gettext("DIDGROUP"),
	"id_cc_didgroup",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_didgroup",
	"didgroupname, id",
	"", "", "%1","", "");

$HD_Form -> AddEditElement (gettext("COUNTRY"),
	"id_cc_country",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_country",
	"countryname, id",
	"", "", "%1", "", "");


$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"activated",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this card"),
	"" , "", "", "Yes :1, - No:0", "", "", "" , "" );

$HD_Form -> AddEditElement (gettext("MONTHLY RATE"),
	"fixrate",
	'$value',
	"INPUT",
	"size=20  maxlength=20 $fixval",
	"12",
	gettext("Insert the monthly rate"),
	"" , "", "", "", "", "", "", gettext("currency : ").strtoupper(BASE_CURRENCY) );


$HD_Form -> FieldEditElement ('did, billingtype, startingdate, expirationdate, id_cc_didgroup, id_cc_country, activated, fixrate');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'did';
$HD_Form -> FG_FILTERFIELDNAME = 'did';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="did like '$filterprefix%'";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

