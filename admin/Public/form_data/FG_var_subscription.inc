<?php

getpost_ifset(array('id', 'label', 'fee', 'status', 'emailreport'));

$HD_Form = new FormHandler("cc_subscription_fee","Subscription");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_subscription.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add SUBSCRIPTION");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/script.png" ;

$actived_list = Constants::getActivationList();

$daynumber_list = array();
for ($k=1;$k<=20;$k++)
        $daynumber_list["$k"]  = array( "$k", "$k");



$currency_list=getCurrenciesList();
$currency_list_r = getCurrenciesRateList();
$currency_list_key = getCurrenciesKeyList() ;


$HD_Form -> AddViewElement(gettext("LABEL"), "label", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FEE"), "fee", "7%", "center", "sort");
$HD_Form -> AddViewElement("<acronym title=\"CURRENCY\">".gettext("CUR")."</acronym>", "currency", "5%", "center", "sort", "", "list", $currency_list_key);
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "7%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("NBRUN"), "numberofrun", "6%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATELASTRUN"), "datelastrun", "12%", "center", "sort", "16");
$HD_Form -> AddViewElement(gettext("CARDPERFORM"), "totalcardperform", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TOTALCREDIT"), "totalcredit", "10%", "center", "sort");


// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('label, fee, currency, status, numberofrun, datelastrun, totalcardperform, totalcredit');


$HD_Form -> CV_NO_FIELDS  = gettext("There are no subscriptions created!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
// View Details
// $HD_Form -> FG_OTHER_BUTTON1 = true;
// |param| will be replace by id .
// you don't need that, if you don't supply any |param| string id will be append after FG_OTHER_BUTTON1_LINK.

//$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:;\" onClick=\"MM_openBrWindow('CC_entity_subscription_details.php?displayfooter=0&popup_select=1&displayheader=0&id=|param|','','scrollbars=yes,resizable=yes,width=600,height=350')\"";
//$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";


$HD_Form -> AddEditElement (gettext("LABEL"),
	"label",
	"$value",
	"INPUT",
	"size=35 maxlength=30",
	"3",
	gettext("Insert the service label"),
	"" , "", "", "", "" , "" , "" , "");

$HD_Form -> AddEditElement (gettext("FEE"),
	"fee",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"12",
	gettext("Insert the fee amount."),
	"" , "", "", "", "" , "", "", gettext("Set the fee amount that the user have to pay per month."));

$HD_Form -> AddEditElement (gettext("CURRENCY"),
   "currency",
   "",
   "SELECT",
   "",
   "",
   "",
   "list" , "", "",	"", $currency_list, "%1", "", "" );

$HD_Form -> AddEditElement ("STATUS",
	"status",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", "");

$HD_Form -> AddEditElement (gettext("EMAIL TO SEND REPORT"),
	"emailreport",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"1",
	gettext("Insert the email to send the report"),
	"" , "", "", "", "", "" , "NO-NULL", "");



$HD_Form -> FieldEditElement ('label, fee, currency, status, emailreport');


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a service below");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
