<?php

getpost_ifset(array('id', 'idtariffplan', 'dialprefix', 'destination', 'buyrate', 'buyrateinitblock',
'buyrateincrement', 'rateinitial', 'initblock', 'billingblock', 'connectcharge', 'disconnectcharge','disconnectcharge_after',
'stepchargea', 'chargea', 'timechargea', 'billingblocka', 'stepchargeb', 'chargeb', 'timechargeb',
'billingblockb', 'stepchargec', 'chargec', 'timechargec', 'billingblockc', 'startdate', 'stopdate',
'starttime', 'endtime', 'id_trunk', 'musiconhold','tariffplan', 'tariffgroup','posted','id_outbound_cidgroup','rounding_calltime', 'rounding_threshold', 'additional_block_charge', 'additional_block_charge_time', 'additional_grace','minimal_cost'));

// put all the destination name to lowercase
$_POST["destination"] = strtolower ($_POST["destination"]);


$HD_Form = new FormHandler("cc_ratecard,cc_tariffplan,cc_tariffgroup_plan,cc_tariffgroup,cc_agent_tariffgroup","Rates");



$HD_Form -> FG_TABLE_ID = 'cc_ratecard.id';
$HD_Form -> FG_TABLE_CLAUSE = "cc_ratecard.idtariffplan = cc_tariffplan.id  AND cc_tariffplan.id = cc_tariffgroup_plan.idtariffplan AND cc_tariffgroup_plan.idtariffplan = cc_tariffgroup.id AND cc_tariffgroup.id = cc_agent_tariffgroup.id_tariffgroup AND cc_agent_tariffgroup.id_agent=".$_SESSION['agent_id'] ;
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " cc_ratecard.destination";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

$HD_Form->FG_FILTER_SEARCH_SESSION_NAME = 'entity_ratecard_selection';




if (isset($posted) && !$popup_select){
	if (is_string ($tariffplan) && strlen(trim($tariffplan))>0) {
		$idtariff = explode('-:-',$tariffplan);
		$HD_Form->FG_TOP_FILTER_NAME = gettext("THE CURRENT RATECARD").' : '.str_replace ("-:-","",$idtariff[1]);
		$HD_Form->FG_TOP_FILTER_VALUE = $idtariff[0];
		$HD_Form -> FG_TABLE_CLAUSE .= " AND cc_ratecard.idtariffplan='$HD_Form->FG_TOP_FILTER_VALUE' ";
		$_SESSION['def_ratecard']=$HD_Form -> FG_TABLE_CLAUSE;
	}
	
}else{
	$HD_Form -> FG_TOP_FILTER_NAME = 0;
	$HD_Form -> FG_TABLE_CLAUSE = $_SESSION['def_ratecard'];
	
	if (!empty($_SESSION['def_ratecard_tariffgroup'])){
		$HD_Form -> FG_TABLE_CLAUSE = $_SESSION['def_ratecard_tariffgroup'];
		if (empty($form_action) || $form_action=="list")
			$HD_Form -> FG_TABLE_NAME = 'cc_ratecard, cc_tariffplan t1, cc_tariffgroup t2, cc_tariffgroup_plan t3';
	}
}



$musiconhold_list = array();
$musiconhold_list[] = array( "No MusicOnHold", "");
for ($i=1;$i<=NUM_MUSICONHOLD_CLASS;$i++){
	$musiconhold_list[]  = array( "MUSICONHOLD CLASS ACC_$i", "acc_$i");
}

$yesno = Constants::getYesNoList();

if ($popup_select) $HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESTINATION"), "destination", "17%", "center", "sort", "");
$HD_Form -> AddViewElement("<acronym title=\"DIALING PREFIX\">".gettext("PREFIX")."</acronym>", "dialprefix", "10%", "center", "sort", "");
$HD_Form -> AddViewElement("<acronym title=\"SELLING RATE\">".gettext("SR")."</acronym>", "rateinitial", "4%", "center", "sort");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("START-DATE"), "startdate", "13%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("STOP-DATE"), "stopdate", "13%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
if (!$popup_select) $HD_Form -> AddViewElement("<acronym title=\"CONNECT CHARGE\">".gettext("CC")."</acronym>", "connectcharge", "4%", "center", "sort");
if (!$popup_select) $HD_Form -> AddViewElement(gettext("RATE CARD"), "idtariffplan", "12%", "center", "sort", "15", "lie", "cc_tariffplan", "tariffname", "id='%id'", "%1");


if (!$popup_select) {
	$HD_Form -> FieldViewElement ('DISTINCT cc_ratecard.destination, cc_ratecard.dialprefix, cc_ratecard.rateinitial, cc_ratecard.startdate, cc_ratecard.stopdate, cc_ratecard.connectcharge, cc_ratecard.idtariffplan ');
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
	$HD_Form -> FieldExportElement ('DISTINCT '. RATE_EXPORT_FIELD_LIST);
	$HD_Form -> FG_EXPORT_CSV = true;
	$HD_Form -> FG_EXPORT_XML = true;
	$HD_Form -> FG_EXPORT_SESSION_VAR = "pr_export_entity_rates";

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


$HD_Form -> AddSearchElement_C1(gettext("DESTINATION"), 'destination','destinationtype');
$HD_Form -> AddSearchElement_C1(gettext("PREFIX"),'dialprefix','dialprefixtype');
$HD_Form -> AddSearchElement_C2(gettext("RATE INITIAL"),'rateinitial1','rateinitial1type','rateinitial2','rateinitial2type','rateinitial');



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

//$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



