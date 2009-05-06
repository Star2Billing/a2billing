<?php

getpost_ifset(array('id', 'dialprefix', 'destination', 'buyrate', 'buyrateinitblock',
'buyrateincrement', 'rateinitial', 'initblock', 'billingblock', 'connectcharge', 'disconnectcharge','disconnectcharge_after',
'stepchargea', 'chargea', 'timechargea', 'billingblocka', 'stepchargeb', 'chargeb', 'timechargeb',
'billingblockb', 'stepchargec', 'chargec', 'timechargec', 'billingblockc', 'startdate', 'stopdate',
'starttime', 'endtime', 'id_trunk', 'musiconhold','tariffplan', 'tariffgroup','posted','id_outbound_cidgroup','rounding_calltime', 'rounding_threshold', 'additional_block_charge', 'additional_block_charge_time', 'additional_grace','minimal_cost','announce_time_correction'));

// put all the destination name to lowercase
$_POST["destination"] = strtolower ($_POST["destination"]);

$HD_Form = new FormHandler("cc_ratecard LEFT JOIN cc_tariffgroup_plan ON cc_tariffgroup_plan.idtariffplan = cc_ratecard.idtariffplan ","Rates");

$HD_Form -> FG_TABLE_ID = 'cc_ratecard.id';
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " destination";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

$HD_Form->FG_FILTER_SEARCH_SESSION_NAME = 'entity_ratecard_selection';

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_def_ratecard.php?form_action=ask-add&atmenu=ratecard&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Rate");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/world_add.png" ;



$musiconhold_list = Constants::getMusicOnHold_List();
$yesno = Constants::getYesNoList();


if ($popup_select) $HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESTINATION"), "destination", "17%", "center", "sort", "15", "lie", "cc_prefix", "destination", "prefix='%id'", "%1");
$HD_Form -> AddViewElement("<acronym title=\"DIALING PREFIX\">".gettext("PREFIX")."</acronym>", "dialprefix", "10%", "center", "sort", "");
$HD_Form -> AddViewElement("<acronym title=\"BUYING RATE\">".gettext("BR")."</acronym>", "buyrate", "4%", "center", "sort");
$HD_Form -> AddViewElement("<acronym title=\"SELLING RATE\">".gettext("SR")."</acronym>", "rateinitial", "4%", "center", "sort");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("START-DATE"), "startdate", "13%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("STOP-DATE"), "stopdate", "13%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
if (!$popup_select) $HD_Form -> AddViewElement("<acronym title=\"INITBLOCK\">".gettext("INITB")."</acronym>", "initblock", "4%", "center", "sort");
if (!$popup_select) $HD_Form -> AddViewElement("<acronym title=\"CONNECT CHARGE\">".gettext("CC")."</acronym>", "connectcharge", "4%", "center", "sort");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("TRUNK"), "id_trunk", "10%", "center", "sort", "15", "lie", "cc_trunk", "trunkcode", "id_trunk='%id'", "%1");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("RATE CARD"), "idtariffplan", "12%", "center", "sort", "15", "lie", "cc_tariffplan", "tariffname", "id='%id'", "%1");


if (!$popup_select) {
	$HD_Form -> FieldViewElement ('DISTINCT cc_ratecard.destination, cc_ratecard.dialprefix, cc_ratecard.buyrate, cc_ratecard.rateinitial, cc_ratecard.startdate, cc_ratecard.stopdate, cc_ratecard.initblock, cc_ratecard.connectcharge, cc_ratecard.id_trunk , cc_ratecard.idtariffplan ');
} else {
	$HD_Form -> FieldViewElement ('cc_ratecard.id, cc_ratecard.destination, cc_ratecard.dialprefix, cc_ratecard.buyrate, cc_ratecard.rateinitial');
}

if ($popup_select) {
	$HD_Form -> FG_LIMITE_DISPLAY = 7;
	$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=" . $_GET['popup_select'] . "&popup_formname=" . $_GET['popup_formname'] . "&popup_fieldname=" . $_GET['popup_fieldname'];

	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	//echo $popup_select;
	if ($popup_select == 1)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
	elseif ($popup_select == 2)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
} else {
	//$HD_Form -> FieldExportElement ('DISTINCT cc_ratecard.dialprefix, cc_ratecard.destination, cc_ratecard.buyrate , cc_ratecard.buyrateinitblock, cc_ratecard.buyrateincrement, cc_ratecard.rateinitial , cc_ratecard.initblock, cc_ratecard.billingblock, cc_ratecard.connectcharge, cc_ratecard.disconnectcharge , cc_ratecard.stepchargea, cc_ratecard.chargea, cc_ratecard.timechargea, cc_ratecard.billingblocka, cc_ratecard.stepchargeb, cc_ratecard.chargeb, cc_ratecard.timechargeb, cc_ratecard.billingblockb, cc_ratecard.stepchargec, cc_ratecard.chargec, cc_ratecard.timechargec, cc_ratecard.billingblockc, cc_ratecard.startdate, cc_ratecard.stopdate, cc_ratecard.starttime, cc_ratecard.endtime, cc_ratecard.id_trunk, cc_ratecard.musiconhold,  cc_ratecard.id_outbound_cidgroup, cc_ratecard.rounding_calltime, cc_ratecard.rounding_threshold, cc_ratecard.additional_block_charge, cc_ratecard.additional_block_charge_time, cc_ratecard.tag');
	$HD_Form -> FieldExportElement ('DISTINCT '. str_replace('dest_name', '(select a.destination from cc_prefix as a where cc_ratecard.destination=a.prefix limit 1) as dest_name', RATE_EXPORT_FIELD_LIST));
	$HD_Form -> FG_EXPORT_CSV = true;
	$HD_Form -> FG_EXPORT_XML = true;
	$HD_Form -> FG_EXPORT_SESSION_VAR = "pr_export_entity_rates";
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;
	$HD_Form -> FG_ADDITION = true;
}


$HD_Form -> CV_NO_FIELDS  = gettext("NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("HAVE BEEN CREATED."); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


// Search form
$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext("Define the criteria to search");
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext("Start Date");
$HD_Form -> FG_FILTER_SEARCH_1_TIME_FIELD = 'startdate';


$HD_Form -> AddSearchElement_C1(gettext("TAG"), 'tag','tagtype');
$HD_Form -> AddSearchElement_C1(gettext("DESTINATION"), 'destination','destinationtype');
$HD_Form -> AddSearchElement_C1(gettext("PREFIX"),'dialprefix','dialprefixtype');
$HD_Form -> AddSearchElement_C2(gettext("BUYRATE"),'buyrate1','buyrate1type','buyrate2','buyrate2type','buyrate');
$HD_Form -> AddSearchElement_C2(gettext("RATE INITIAL"),'rateinitial1','rateinitial1type','rateinitial2','rateinitial2type','rateinitial');

$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select('SELECT TRUNK',"cc_trunk","id_trunk, trunkcode, providerip","","trunkcode","ASC","id_trunk");
$HD_Form -> AddSearchElement_Select('SELECT RATECARD',"cc_tariffplan","id, tariffname","","tariffname","ASC","idtariffplan",1,null,"cc_ratecard");
$HD_Form -> AddSearchElement_Select('SELECT CALLPLAN',"cc_tariffgroup","id, tariffgroupname","","tariffgroupname","ASC","idtariffgroup");


$HD_Form -> AddEditElement(gettext("RATECARD"),
   "idtariffplan",	
   '$value',	
   "SELECT",	
   "", "", "",	
   "sql",		
   "cc_tariffplan",	
   "tariffname, id",		
   "", "", "%1", "", "");


$HD_Form -> AddEditElement(gettext("DIALPREFIX"),
   "dialprefix",
   '$value',	
   "POPUPVALUE",	
   "size=50 maxlength=80",	
   "13",
   gettext("Insert the dialing prefix, such as for UK '44' or for US '1' "),
   "" , "", "",	"", "" , "A2B_entity_prefix.php?popup_select=2&", ", 'prefix','width=550,height=340,top=20,left=100,scrollbars=1'", 
   gettext("A) Add destination prefixes, ie '441' for UK Landlines.<br>B) Use 'defaultprefix' to setup a rate for all destinations where a specific rate is not provided.<br>C) if you ADD a rate, NOT an EDIT, you can define a range of prefixes. '32484-32487' adds all prefixes between 32484 and 32487. '32484,32386,32488' would add only the individual prefixes listed.<br>D) Asterisk extensions style + POSIX regex syntaxes are supported. '_447[7-9]XXXXXXXX' matches 12-digit UK mobiles. '_X{0,3}(112|999|911)' matches any 0-3 digits followed an emergency number. '_' can be used to add length, and raise the priority."));


$HD_Form -> AddEditElement(gettext("Destination Prefix"),
   "destination",
   '$value',	
   "SELECT",	
   "", "", "",	
   "sql",		
   "cc_prefix",	
   "destination, prefix",		
   "", "", "%2 (%1)", "", gettext("Select the prefix destination corresponding to your prefix rate."));
   

$HD_Form -> AddEditElement("BUYING RATE",
   "buyrate",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the buy rate"),
   "" , "", "",	"", "" , "", "", gettext("Set the carrier cost, the price you pay for minutes."), null, null, 
   gettext("Set the carrier cost for this destination."));

$HD_Form -> AddEditElement(gettext("BUYRATE MIN DURATION"),
   "buyrateinitblock",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the buyrate init block"),
   "" , "", "",	"", "", "" , "NO", gettext("Set the minimum duration charged by the carrier. (i.e. 30 secs)"));

$HD_Form -> AddEditElement(gettext("BUYRATE BILLING BLOCK"),
   "buyrateincrement",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the billing block"),
   "" , "", "",	"", "", "" , "NO", gettext("Set the billing increment, in seconds (billing block), that the carrier applies. (ie 30 secs)"));

$HD_Form -> AddEditElement(gettext("SELLING RATE"),
   "rateinitial",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the initial rate"),
   "" , "", "",	"", "" , "", "", gettext("The retail rate; or the cost per minute to apply to the customer, e.g. 0.02"), null, null, gettext("Define the selling cost for customers."));



$HD_Form -> AddEditElement(gettext("SELLRATE MIN DURATION"),
   "initblock",		
   '$value',	
   "INPUT",	
   "size=30 maxlength=20",	
   "4",	
   gettext("Insert the init block"),
   "" , "", "",	"", "", "", "NO", gettext("Set the minimum duration to charge the customer (ie 60 seconds)"));

$HD_Form -> AddEditElement(gettext("SELLRATE BILLING BLOCK"),
   "billingblock",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the billing block"),
   "" , "", "",	"", "", "", "NO", gettext("Set the billing increment in seconds to bill your customer."));

$HD_Form -> AddEditElement(gettext("CONNECT CHARGE"),
   "connectcharge",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the connect charge"),
   "" , "", "",	"", "", "" , "NO", gettext("Apply a connection charge"));

$HD_Form -> AddEditElement(gettext("DISCONNECT CHARGE"),
   "disconnectcharge",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the disconnect charge"),
   "" , "", "",	"", "", "", "NO", gettext("Apply a disconnection charge" ));
$HD_Form -> AddEditElement(gettext("DISCONNECT CHARGE THRESHOLD"),
   "disconnectcharge_after",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the disconnect charge threshold"),
   "" , "", "", "", "", "", "NO", gettext("Apply the disconnection charge if the call duration is greater than this amount of seconds. If 0, it will always apply." ));


$HD_Form -> AddEditElement(gettext("MINIMUM CALL COST "),
   "minimal_cost",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "12",
   gettext("Insert the Minimum call cost"),
   "" , "", "",	"", "", "", "NO", gettext("Apply a minimum charge for the call." ));
   
if (ADVANCED_MODE){
	$HD_Form -> AddEditElement(gettext("STEPCHARGE A"),
	   "stepchargea",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "12",
	   gettext("Insert the stepcharge A"),
	   "" , "", "",	"", "", "", "NO", gettext("When entering in the cycle 'A', define the amount to charge for the entrance."), '', '', gettext("Below we have the section to create progressive rates. Progressive rate aims to bill the customer at various rates according the duration of the call. For instance you can define that you can to bill the customer 0.33dollars for the first 2 minutes and then you want to bill him 0.45dollars for the rest of the call." ));
	
	$HD_Form -> AddEditElement(gettext("CHARGE A"),
	   "chargea",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "12",
	   gettext("Insert the charge A"),
	   "" , "", "",	"", "", "", "NO", gettext("When entering in the cycle 'A', define the rate to apply." ));
	
	$HD_Form -> AddEditElement(gettext("TIMECHARGE A"),
	   "timechargea",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "4",
	   gettext("Insert the time charge A"),
	   "" , "", "",	"", "", "", "NO", gettext("Define the duration of the cycle 'A'." ));
	
	$HD_Form -> AddEditElement(gettext("BILLING BLOCK A"),
	   "billingblocka",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "4",
	   gettext("Insert the billing block A"),
	   "" , "", "",	"", "", "" , "NO", gettext("Define the duration of each billing block to apply the rate 'CHARGE A'."));
	
	$HD_Form -> AddEditElement(gettext("STEPCHARGE B"),
	   "stepchargeb",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "12",
	   gettext("Insert the stepcharge B"),
	   "" , "", "",	"", "", "" , "NO", gettext("When entering in the cycle 'B', define the amount to charge for the entrance."));
	
	$HD_Form -> AddEditElement(gettext("CHARGE B"),
	   "chargeb",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "12",
	   gettext("Insert the charge B"),
	   "" , "", "",	"", "", "", "NO", gettext("When entering in the cycle 'B', define the rate to apply." ));
	
	$HD_Form -> AddEditElement(gettext("TIMECHARGE B"),
	   "timechargeb",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "4",
	   gettext("Insert the time charge B"),
	   "" , "", "",	"", "", "", "NO", gettext("Define the duration of the cycle 'B'." ));
	
	$HD_Form -> AddEditElement(gettext("BILLING BLOCK B"),
	   "billingblockb",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "4",
	   gettext("Insert the billing block B"),
	   "" , "", "",	"", "", "", "NO", gettext("Define the duration of each billing block to apply the rate 'CHARGE B'." ));
	
	$HD_Form -> AddEditElement(gettext("STEPCHARGE C"),
	   "stepchargec",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "12",
	   "Insert the stepcharge C",
	   "" , "", "",	"", "", "", "NO", gettext("When entering in the cycle 'C', define the amount to charge for the entrance." ));
	
	$HD_Form -> AddEditElement(gettext("CHARGE C"),
	   "chargec",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "12",
	   gettext("Insert the charge C"),
	   "" , "", "",	"", "", "", "NO", gettext("When entering in the cycle 'C', define the rate to apply."));
	
	$HD_Form -> AddEditElement(gettext("TIMECHARGE C"),
	   "timechargec",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "4",
	   gettext("Insert the time charge C"),
	   "" , "", "",	"", "", "", "NO", gettext("Define the duration of the cycle 'C'."));
	
	$HD_Form -> AddEditElement(gettext("BILLING BLOCK C"),
	   "billingblockc",
	   '$value',
	   "INPUT",
	   "size=30 maxlength=20",
	   "4",
	   gettext("Insert the billing block C"),
	   "" , "", "",	"", "", "", "NO", gettext("Define the duration of each billing block to apply the rate 'CHARGE C'."));
	$HD_Form -> AddEditElement(gettext("ANNOUNCE TIME CORRECTION"),
           "announce_time_correction",
           '$value',
           "INPUT",
           "size=30 maxlength=20",
           "12",
           gettext("insert corection for announce time"),
           "" , "", "", "", "", "", "NO", gettext("Define the multiplier on announce time for clients."));

}//ENDif (ADVANCED_MODE)


if ($form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+10;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

$HD_Form -> AddEditElement(gettext("START DATE"),
   "startdate",
   '$value',
   "INPUT",
   "size=40 maxlength=40  $comp_date",
   "10",
   gettext("Insert the starting date"),
   "" , "", "",	"", "", "", "" , gettext("Format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'"),
   null, "res_display_dateformat", gettext("Define the period when this rate table is active."));

$HD_Form -> AddEditElement(gettext("STOP DATE"),
   "stopdate",
   '$value',
   "INPUT",
   "size=40 maxlength=40  $comp_date_plus",
   "10",
   gettext("Insert the stop date"),
   "", "", "", "", "", "NO-NULL", "", gettext("Leave empty to apply this rate indefinitely. Format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00"), null, "res_display_dateformat" );

if ($form_action=="ask-add"){
	$comp_time = " value=0 ";
	$comp_time_plus = " value=10079 ";
}


$HD_Form -> AddEditElement(gettext("START TIME"),
   "starttime",
   '$value',
   "POPUPDATETIME",
   "size=8 maxlength=8 $comp_time",
   "4",
   gettext("Insert the start time"),
   "" , "", "", "", "",  "", "", gettext("Set the time and day of the week at the rate is first valid (ie Monday 00:00 is 0)") );


$HD_Form -> AddEditElement(gettext("END TIME"),
   "endtime",
   '$value',
   "POPUPDATETIME",
   "size=8 maxlength=8 $comp_time_plus",
   "4",
   gettext("Insert the end time"),
   "" , "", "", "", "",  "", "", gettext("Set the time and the day of the week that the rate is no longer valid. (ie Sunday 23:59 is 10079)") );

$HD_Form -> AddEditElement(gettext("ROUNDING CALLTIME"),
   "rounding_calltime",
   '$value',
   "INPUT",
   "size=40 maxlength=40",
   "",
   gettext("Insert the rounding calltime"),
   "", "", "", "", "", "", "", gettext("Set the rounding calltime. All the selling rules will apply over this new calltime."));

$HD_Form -> AddEditElement(gettext("ROUNDING THRESHOLD"),
   "rounding_threshold",
   '$value',
   "INPUT",
   "size=40 maxlength=40",
   "",
   gettext("Insert the rounding threshold"),
   "", "", "", "", "", "", "", gettext("Set the rounding threshold"));

$HD_Form -> AddEditElement(gettext("ADDITIONAL BLOCK CHARGE"),
   "additional_block_charge",
   '$value',
   "INPUT",
   "size=40 maxlength=40",
   "",
   gettext("Insert the additional block charge"),
   "", "", "", "", "", "", "", gettext("Set the initial block charge"));

$HD_Form -> AddEditElement(gettext("ADDITIONAL BLOCK CHARGE TIME"),
   "additional_block_charge_time",
   '$value',
   "INPUT",
   "size=40 maxlength=40",
   "",
   gettext("Insert the additional block charge time"),
   "", "", "", "", "", "", "", gettext("Set the initial block charge time"));
   
$HD_Form -> AddEditElement(gettext("ADDITIONAL GRACE TIME"),
   "additional_grace",
   '$value',
   "INPUT",
   "size=40 maxlength=40",
   "",
   gettext("Insert the additional grace time in sec"),
   "", "", "", "", "", "", "", gettext("Set the additional grace time in sec"));

$HD_Form -> AddEditElement(gettext("TRUNK"),
   "id_trunk",
   '$value',
   "SELECT",
   "", "", "",
   "sql",
   "cc_trunk",
   "trunkcode, id_trunk",
	"", "", "%1", "", gettext("Set the trunk to use for this destination, or NOT DEFINED to use the rate card trunk."), '', '<OPTION  value="-1" selected>NOT DEFINED</OPTION>');

$HD_Form -> AddEditElement(gettext("CIDGroup"),
   "id_outbound_cidgroup",
   '$value',
   "SELECT",
   "", "", "",
   "sql",
   "cc_outbound_cid_group",
   "group_name, id",
	"", "", "%1", "", gettext("Set the Outgoing CID Group to use for this destination, or NOT DEFINED to use the rate card trunk."), '', '<OPTION  value="-1" selected>NOT DEFINED</OPTION>');

$HD_Form -> AddEditElement(gettext("TAG"),
   "tag",
   '$value',
   "INPUT",
   "size=30 maxlength=40",
   "11",
   "",
   "" , "", "",	"", "", "", "NO", gettext("Set a tag on this rate"));

if (ADVANCED_MODE) {
	$HD_Form -> AddEditElement(gettext("MUSICONHOLD"),
	   "musiconhold",
	   '$value',
	   "SELECT",
	   "",
	   "",
	   "",
	   "list" , "", "", "", $musiconhold_list, "%1", "", "" );
}

if (ADVANCED_MODE) {
	// This Variable store the argument for the SQL query
	$HD_Form -> FieldEditElement ('idtariffplan, dialprefix, destination, buyrate, buyrateinitblock, buyrateincrement, rateinitial, initblock, billingblock, connectcharge, disconnectcharge,disconnectcharge_after, minimal_cost, stepchargea, chargea, timechargea, billingblocka, stepchargeb, chargeb, timechargeb, billingblockb, stepchargec, chargec, timechargec, billingblockc,announce_time_correction, startdate, stopdate, starttime, endtime, rounding_calltime, rounding_threshold, additional_block_charge, additional_block_charge_time,additional_grace, id_trunk, id_outbound_cidgroup, tag, musiconhold');
}else{
	// This Variable store the argument for the SQL query
	$HD_Form -> FieldEditElement ('idtariffplan, dialprefix, destination, buyrate, buyrateinitblock, buyrateincrement,rateinitial, initblock, billingblock, connectcharge, disconnectcharge,disconnectcharge_after, minimal_cost, startdate, stopdate, starttime, endtime, rounding_calltime, rounding_threshold, additional_block_charge, additional_block_charge_time,additional_grace, id_trunk, id_outbound_cidgroup, tag');
}


$HD_Form -> FG_SPLITABLE_FIELD = 'dialprefix';


$HD_Form -> FG_INTRO_TEXT_ADITION = $HD_Form -> FG_INTRO_TEXT_EDITION = gettext("Rates must be entered in the currency base : ").strtoupper(BASE_CURRENCY);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME."<br>".gettext("Fill the following fields and confirm by clicking on the button add.");



$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue.");

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'dialprefix';
$HD_Form -> FG_FILTERFIELDNAME = 'dialprefix';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE) > 0) {
        $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
    }
	$HD_Form -> FG_TABLE_CLAUSE.="dialprefix like '$filterprefix%'";
}

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";


