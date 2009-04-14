<?php

getpost_ifset(array('id', 'uniqueid', 'entry_time', 'status', 'server_ip', 'num_attempt', 'last_attempt_time', 'manager_result', 'agi_result', 'callback_time', 'channel', 'exten', 'context', 'priority', 'application', 'data', 'timeout', 'callerid', 'variable', 'account', 'async', 'actionid'));


$HD_Form = new FormHandler("cc_callback_spool","Callback");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS  = "DESC";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_callback.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add CALLBACK");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/lightning_add.png" ;



$actived_list = Constants::getActivationTrueFalseList();

$status_list = array();
$status_list["PENDING"] = array( "PENDING", "PENDING");
$status_list["SENT"] = array( "SENT", "SENT");
$status_list["ERROR"] = array( "ERROR", "ERROR");

$yesno = Constants::getYesNoList();


$HD_Form -> AddViewElement("ID", "id", "3%", "center", "sort");
$HD_Form -> AddViewElement("UNIQUEID", "uniqueid", "5%", "center", "sort");
$HD_Form -> AddViewElement("ENTRY_TIME", "entry_time", "10%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement("STATUS", "status", "5%", "center", "sort");
$HD_Form -> AddViewElement("SERVER_IP", "server_ip", "5%", "center", "sort");
$HD_Form -> AddViewElement("<acronym title=\"NUMBER OF ATTEMPT\">NA</acronym>", "num_attempt", "5%", "center", "sort");
$HD_Form -> AddViewElement("<acronym title=\"MANAGER RESULT\">MR</acronym>", "manager_result", "10%", "center", "sort", "15");
$HD_Form -> AddViewElement("<acronym title=\"AGI RESULT\">AR</acronym>", "agi_result", "5%", "center", "sort");
$HD_Form -> AddViewElement("CALLBACK_TIME", "callback_time", "10%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement("LAST_ATTEMPT", "last_attempt_time", "10%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement("CHANNEL", "channel", "5%", "center", "sort");
$HD_Form -> AddViewElement("CONTEXT", "context", "5%", "center", "sort");
$HD_Form -> AddViewElement("SERVER", "id_server", "5%", "center", "sort", "15", "lie", "server_manager", "server_ip", "id='%id'", "%1");



$HD_Form -> FieldViewElement ('id, uniqueid, entry_time, status, server_ip, num_attempt, manager_result, agi_result, callback_time, last_attempt_time, channel, context, id_server');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("SERVER GROUP"),
	"id_server_group",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_server_group",
	"name, id",
	"", "", "%1","", "");

$HD_Form -> AddEditElement (gettext("UNIQUEID"),	
	"uniqueid",		
	'$value',	
	"INPUT",	
	"size=20 maxlength=30",	
	"3",	
	gettext("Insert the uniqueid"), 
	"" , "", "",	"", "" , "", "", gettext("This is an identifier to cross check the callback's request.".gettext("(useful if you have requests coming from an other platform through the API))") );
	
if ($form_action=="ask-add") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;	
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
	
	$def_ipserver = " value='localhost' "; 
	$def_nbattempt = " value='0' ";
}

$HD_Form -> AddEditElement (gettext("STATUS"),	
	"status",			
	'$value',	
	"SELECT",	
	"",	
	"",	
	"", 
	"list" , "", "",	"", $status_list, "%1", "", "" );

$HD_Form -> AddEditElement (gettext("IP SERVER"),
	"server_ip",
	'$value',
	"INPUT",
	"size=20 maxlength=30 $def_ipserver",
	"11",
	gettext("Insert the Ip Server"),
	"" , "", "",	"", "", "" , "", "");

$HD_Form -> AddEditElement (gettext("NUMBER ATTEMPTS"),	
	"num_attempt",		
	'$value',	
	"INPUT",	
	"size=5 maxlength=10 $def_nbattempt",	
	"4",
	gettext("Insert the number of attempts"), 
	"" , "", "",	"", "", "", "", "");

$HD_Form -> AddEditElement (gettext("MANAGER RESULT"),
	"manager_result",
	'$value',
	"INPUT",
	"size=50 maxlength=80",
	"11",
	gettext("Insert the manager result"),
	"" , "", "",	"", "", "" , "NO", "");

$HD_Form -> AddEditElement (gettext("AGI RESULT"),
	"agi_result",
	'$value',
	"INPUT",
	"size=50 maxlength=80",
	"11",
	gettext("Insert the agi result"),
	"" , "", "",	"", "", "" , "NO", "");
	
$HD_Form -> AddEditElement (gettext("CALLBACK DATE"),
	"callback_time",
	'$value',
	"INPUT",
	"size=20 maxlength=20  $comp_date",
	"10",
	"Insert the callback time",
	"" , "", "",	"", "", "", "NO-NULL", gettext("please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"), "", "res_display_dateformat");
	

$HD_Form -> AddEditElement (gettext("CHANNEL"),
	"channel",
	'$value',
	"INPUT",
	"size=60 maxlength=70",
	"11",
	gettext("Insert the channel"),
	"" , "", "",	"", "", "", "", gettext("Channel on which to originate the call (SIP/123@1.1.1.4)"), null, null, gettext("Define all the information requested by asterisk to generate the callback"));
	

$HD_Form -> AddEditElement (gettext("EXTENSION"),
	"exten",
	'$value',
	"INPUT",
	"size=15 maxlength=30",
	"11",
	gettext("Insert the channel"),
	"" , "", "",	"", "", "", "", gettext("Extension to use on connect (must use Context & Priority with it)") );

$HD_Form -> AddEditElement (gettext("CONTEXT"),
	"context",
	'$value',
	"INPUT",
	"size=30 maxlength=50",
	"11",
	gettext("Insert the context"),
	"" , "", "",	"", "", "", "", gettext("Context to use on connect (must use Exten & Priority with it)") );

$HD_Form -> AddEditElement (gettext("PRIORITY"),
	"priority",
	'$value',
	"INPUT",
	"size=10 maxlength=10",
	"4",
	gettext("Insert the priority"),
	"" , "", "",	"", "", "", "", gettext("Priority to use on connect (must use Context & Exten with it)") );

$HD_Form -> AddEditElement (gettext("APPLICATION"),
	"application",
	'$value',
	"INPUT",
	"size=20 maxlength=30",
	"11",
	gettext("Insert the application"),
	"" , "", "",	"", "", "", "NO", gettext("Application to use on connect : meetme or other (use Data for parameters)") );
   
$HD_Form -> AddEditElement (gettext("DATA"),
	"data",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"11",
	gettext("Insert the data"),
	"" , "", "",	"", "", "", "NO", gettext("Data if Application parameter is used") );

$HD_Form -> AddEditElement (gettext("TIMEOUT"),
	"timeout",
	'$value',
	"INPUT",
	"size=10 maxlength=15",
	"11",
	gettext("Insert the timeout"),
	"" , "", "",	"", "", "", "NO", gettext("Timeout for the connection to happen (30000)") );

$HD_Form -> AddEditElement (gettext("CALLERID"),
	"callerid",
	'$value',
	"INPUT",
	"size=20 maxlength=30",
	"11",
	gettext("Insert the callerid"),
	"" , "", "",	"", "", "", "NO", gettext("CallerID to use for the call") );
   
$HD_Form -> AddEditElement (gettext("variable"),
	"variable",
	'$value',
	"INPUT",
	"size=80 maxlength=300",
	"11",
	gettext("Insert the variable"),
	"" , "", "",	"", "", "", "NO", gettext("Callback info for Asterisk (variable list to transfer to the channel) var1=23|var2=24|var3=25") );
   
$HD_Form -> AddEditElement (gettext("account"),
	"account",
	'$value',
	"INPUT",
	"size=20 maxlength=30",
	"11",
	gettext("Insert the account"),
	"" , "", "",	"", "", "", "NO", gettext("Account code for the call for billing purpose") );
	   
$HD_Form -> AddEditElement (gettext("async"),
	"async",
	'$value',
	"INPUT",
	"size=10 maxlength=10",
	"11",
	gettext("Insert the async"),
	"" , "", "",	"", "", "", "NO", gettext("For the origination to be asynchronous (allows multiple calls to be generated without waiting for a response)") );


$HD_Form -> AddEditElement (gettext("actionid"),
	"actionid",
	'$value',
	"INPUT",
	"size=20 maxlength=30",
	"11",
	gettext("Insert the actionid"),
	"" , "", "",	"", "", "", "NO", gettext("The request identifier. It allows you to identify the response to this request. You may use a number or a string. <br> Useful when you make several simultaneous requests. ActionID: ABC45678901234567890 ") );




$HD_Form -> FieldEditElement ('id_server_group, uniqueid, status, server_ip, num_attempt, manager_result, agi_result, callback_time, channel, exten, context, priority, application, data, timeout, callerid, variable, account, async, actionid');


// Set the filter variables

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'uniqueid';
$HD_Form -> FG_FILTERFIELDNAME = 'uniqueid';


if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.=" uniqueid like '$filterprefix%'";
}



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
?>
