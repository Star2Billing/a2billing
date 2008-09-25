<?php
getpost_ifset(array('id', 'destination', 'id_cc_card', 'id_cc_did', 'activated', 'priority', 'id_trunk', 'voip_call'));

$HD_Form = new FormHandler("cc_did_destination","Did Destination");

$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "destination";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_did_destination.php?form_action=ask-add&section=5";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add DESTINATION");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/world_add.png" ;


$actived_list = Constants::getActivationList();


$billingtype_list = Constants::getBillingTypeList();
// -- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free

$priority_list = array();
for ($k=1;$k<=5;$k++)
        $priority_list["$k"]  = array( "$k", "$k");


$HD_Form -> AddViewElement(gettext("DESTINATION"), "destination", "10%", "center", "sort", "26");
$HD_Form -> AddViewElement(gettext("CREATIONDATE"), "creationdate", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("DID"), "id_cc_did", "12%", "center", "sort", "15", "lie", "cc_did", "did", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "id_cc_card", "20%", "center", "sort", "", "lie_link", "cc_card", "username,id", "id='%id'", "%1", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("ACTIVE"), "activated", "5%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("VOIP"), "voip_call", "5%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("PRIOR"), "priority", "5%", "center", "sort", "", "list", $priority_list);
$HD_Form -> AddViewElement(gettext("MINUTES USED"), "secondusedreal", "10%", "center", "SORT", "30", "", "", "", "", "", "display_minute");




// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('destination, creationdate, id_cc_did, id_cc_card, activated, voip_call, priority, secondusedreal');

$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';
 

$HD_Form -> CV_NO_FIELDS  = gettext("NO DID DESTINATIONS HAVE BEEN CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;



// TODO integrate in Framework
if ($form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}



$HD_Form -> AddEditElement (gettext("DESTINATION"),
	"destination",
	'$value',
	"INPUT",
	"size=50 maxlength=40",
	"0",
	gettext("Insert the DID"),
	"" , "", "", "", "" , "", "", gettext("Enter the phone number you wish to call, or the VoIP client to reach. (ie: 347894999 or SIP/jeremy@182.212.1.45). If the call is VoIP, the VoIP_Call must be set to yes."));

$HD_Form -> AddEditElement (gettext("ID CARD"),
	"id_cc_card",
	'',
	"POPUPVALUE",
	"size=30 maxlength=50",
	"4",
	gettext("Insert the Id CardNumber"),
	"" , "", "", "", "",
	"A2B_entity_card.php?popup_select=1&"
	, ", 'CardNumberSelection','width=550,height=350,top=20,left=100,scrollbars=1'" ,
	gettext("Set the card number ID."));

$HD_Form -> AddEditElement (gettext("DID"),
	"id_cc_did",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_did",
	"did, id",
	"", "", "%1", "" , "");

$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"activated",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this card"),
	"" , "", "", "Yes :1, - No:0",   "", "" , "", "");

$HD_Form -> AddEditElement (gettext("PRIORITY"),
	"priority",
	"",
	"SELECT",
	"",
	"",
	"",
	"list" , "", "",  "", $priority_list, "%1" , "" , "");

$HD_Form -> AddEditElement (gettext("VOIP_CALL"),
	"voip_call",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to not use the trunk and let the asterisk call directly the destination (ie, Destination : SIP/jeremy@182.212.1.45)"),
	"" , "",  "", "Yes :1, - No:0",  "", "" , "" , "");


$HD_Form -> FieldEditElement ('destination, id_cc_card, id_cc_did, activated, priority, voip_call');


if (DB_TYPE == "postgres"){
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";
}else{
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now()";
}




$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
