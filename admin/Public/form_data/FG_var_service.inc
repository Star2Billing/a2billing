<?php
getpost_ifset(array('id', 'name', 'amount', 'period', 'rule', 'daynumber', 'stopmode', 'maxnumbercycle', 'status', 'emailreport'));


$HD_Form = new FormHandler("cc_service","Service");

$HD_Form -> FG_DEBUG = 0;
// FAQ
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


// TODO Integrate a generic LIST to Framework
$period_list = array();
$period_list["1"]  = array( "Daily", "1");
for ($k=2;$k<=60;$k++)
        $period_list["$k"]  = array( "every $k days", "$k");

$actived_list = Constants::getActivationList();

$rule_list = array();
$rule_list["0"]  = array( gettext("No rule"), "0");
$rule_list["1"]  = array( gettext("Apply service if card not used in last X days"), "1");
$rule_list["2"]  = array( gettext("Apply service if card has been used in the last X Days"), "2");
$rule_list["3"]  = array( gettext("Apply Service every Y days"), "3");


$daynumber_list = array();
for ($k=1;$k<=20;$k++)
        $daynumber_list["$k"]  = array( "$k", "$k");

$stopmode_list = array();
$stopmode_list["0"]  = array( "Never", "0");
$stopmode_list["1"]  = array( "Account balance below zero", "1");
$stopmode_list["2"]  = array( "Max number of cycle reach", "2");

$HD_Form -> AddViewElement(gettext("NAME"), "name", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "amount", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PERIOD"), "period", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "7%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement(gettext("NBRUN"), "numberofrun", "6%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATELASTRUN"), "datelastrun", "12%", "center", "sort", "16");
$HD_Form -> AddViewElement(gettext("CARDPERFORM"), "totalcardperform", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TOTALCREDIT"), "totalcredit", "10%", "center", "sort");


// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('name, amount, period, status, numberofrun, datelastrun, totalcardperform, totalcredit');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
// View Details
$HD_Form -> FG_OTHER_BUTTON1 = true;
// |param| will be replace by id .
// you don't need that, if you don't supply any |param| string id will be append after FG_OTHER_BUTTON1_LINK.

$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:;\" onClick=\"MM_openBrWindow('CC_entity_service_details.php?displayfooter=0&popup_select=1&displayheader=0&id=|param|','','scrollbars=yes,resizable=yes,width=600,height=350')\"";

$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";



$HD_Form -> AddEditElement (gettext("NAME"),
	"name",
	"$value",
	"INPUT",
	"size=35 maxlength=30",
	"3",
	gettext("Insert the service name"),
	"" , "", "", "", "" , "" , "" , "");

$HD_Form -> AddEditElement (gettext("AMOUNT CREDIT"),
	"amount",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"12",
	gettext("Insert the number of credit to remove from the cards."),
	"" , "", "", "", "" , "", "", gettext("Set the amount of credit to remove from the card. 0 will remove all credit from the card use -1 for DID recurrent service."));
	
$HD_Form -> AddEditElement (gettext("PERIOD"),
	"period",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $period_list, "%1" , "" , gettext("Interval to apply service"));

$HD_Form -> AddEditElement (gettext("RULE"),
	"rule",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $rule_list, "%1", "", gettext("where X - nextfield daynumber in days <br>	and Y = number set in period field"));
	
$HD_Form -> AddEditElement ("NEXTFIELD DAYNUMBER",
	"daynumber",
	"$value",
	"INPUT",
	"size=20 maxlength=3",
	"4",
	gettext("Insert the daynumber"),
	"" , "", "", "", "", "" , "NO", gettext("Set the number of days to apply to the rule above."));
	
$HD_Form -> AddEditElement ("STOP MODE",
	"stopmode",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $stopmode_list, "%1" , "", "");
	
$HD_Form -> AddEditElement ("MAX NUMBER OF CYCLES BY CARD",
	"maxnumbercycle",
	"$value",
	"INPUT",
	"size=20 maxlength=3",
	"4",
	gettext("Insert the max number of cycle"),
	"" , "", "", "", "", "" , "NO-NULL", gettext("Set the maximum number of times that the service should be applied."));
	
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



$HD_Form -> FieldEditElement ('name, amount, period, rule, daynumber, stopmode, maxnumbercycle, status, emailreport');


if (DB_TYPE == "postgres"){
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";
}else{
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "datecreate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now()";
}
				



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a service below");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted").".<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
