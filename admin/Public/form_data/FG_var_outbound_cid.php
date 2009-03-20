<?php
getpost_ifset(array('id', 'cid', 'outbound_cid_group', 'activated'));

$HD_Form = new FormHandler("cc_outbound_cid_list", "cid");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "cid";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_outbound_cid.php?form_action=ask-add&atmenu=cidgroup&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add CallerID");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/server_connect.png" ;


$actived_list = Constants::getActivationList();

$HD_Form -> AddViewElement(gettext("CID"), "cid", "30%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CIDGROUP"), "outbound_cid_group", "30%", "center", "sort", "15", "lie", "cc_outbound_cid_group", "group_name", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("STATUS"), "activated", "15%", "center", "sort", "", "list", $actived_list);

$HD_Form -> FieldViewElement ('cid, outbound_cid_group, activated');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_SPLITABLE_FIELD = 'cid';



// TODO integrate in Framework
if ($form_action=="ask-add") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

$HD_Form -> AddEditElement (gettext("CID"),
				"cid",
				'$value',
			   "TEXTAREA",	
			   "cols=50 rows=4",
				"",  //CID Regular Expression
				gettext("Insert the CID"),
				"" , "", "", "", "" , "", "",
				gettext("Define the CallerID's. If you ADD a new CID, NOT an EDIT, you can define a range of CallerID. <br>80412340210-80412340218 would add all CID's between the range, whereas CIDs separated by a comma e.g. 80412340210,80412340212,80412340214 would only add the individual CID listed."));


$HD_Form -> AddEditElement (gettext("CIDGROUP"),
				"outbound_cid_group",
				'$value',
				"SELECT",
				"", "", "",
				"sql",
				"cc_outbound_cid_group",
				"group_name, id",
				"", "", "%1","", "");
	
$HD_Form -> AddEditElement (gettext("ACTIVATED"),
				"activated",
				'1',
				"RADIOBUTTON",
				"",
				"",
				gettext("Choose if you want to activate this CallerID"),
				"" , "", "", "Yes :1, - No:0", "", "", "" , "" );
				
	

$HD_Form -> FieldEditElement ('cid, outbound_cid_group, activated');


$HD_Form -> FG_INTRO_TEXT_EDITION = '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



