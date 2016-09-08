<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
**/

getpost_ifset(array('id', 'trunkcode', 'trunkprefix', 'removeprefix', 'providertech', 'providerip', 'addparameter','failover_trunk', 'id_provider', 'popup_select', 'inuse', 'maxuse', 'status', 'if_max_use', 'minutes_per_day', 'attempt_condition', 'attempt_statuses', 'attempt_count, attempt_statuses, priority, attempt_delay, calls_per_day', 'trunk_GMT'));

$DBHandle  = DbConnect();
$instance_table = new Table();

$HD_Form = new FormHandler("cc_trunk","trunk");


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_trunk.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Trunk");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/add.png" ;


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = " id_trunk";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " id_trunk";
$HD_Form -> FG_EDITION_CLAUSE = " id_trunk='%id' ";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

$actived_list = array();
$actived_list["1"] = array( gettext("Active"), "1");
$actived_list["0"] = array( gettext("Inactive"), "0");

$actived_list = array();
$actived_list["1"] = array( gettext("Active"), "1");
$actived_list["0"] = array( gettext("Inactive"), "0");

$trunk_actived_list = array();
$trunk_actived_list["0"] = array( gettext("0"), "Inactive");
$trunk_actived_list["1"] = array( gettext("1"), "Active");

$provider_list_query = "select * from cc_provider";
$res_provider_list  = $instance_table->SQLExec ($DBHandle, $provider_list_query);
if (is_array($res_provider_list)) {
    foreach($res_provider_list as $val){
	    $provider_list_result[] = array(gettext($val['id']),$val['provider_name']);
    }
}

$maxuse_behaviour_list = array();
$maxuse_behaviour_list["0"] = array( gettext("Use failover trunk"), "0");
$maxuse_behaviour_list["1"] = array( gettext("Use next trunk"), "1");

$attempt_cond_list = array();
$attempt_cond_list["0"] = array( gettext("Dialstatus in the list"), "0");
$attempt_cond_list["1"] = array( gettext("Dialstatus not in the list"), "1");

$timezone_list = constants::getTimezones_List();
$timezone_list['0'] = array(gettext("Not set"), '0');

// Dependent Tables
$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_ratecard");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_trunk ");

$HD_Form -> FG_FK_DELETE_MESSAGE = gettext("You have some rates attached to this Trunk! Please comfirm that you really want to remove this Trunk? ");


if ($popup_select) {
	$HD_Form -> AddViewElement(gettext("ID"), "id_trunk", "7%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("LABEL"), "trunkcode", "15%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("PROVIDER"), "id_provider" ,"10%", "center", "sort", "15", "lie", "cc_provider", "provider_name", "id='%id'", "%1");
	$HD_Form -> AddViewElement(gettext("TOLL FREE"), "tollfree", "5%", "center", "sort", "", "list", Constants::getYesNoList());

	$HD_Form -> FieldViewElement ('id_trunk, trunkcode, id_provider, tollfree');
} else {
	$HD_Form -> AddViewElement(gettext("LABEL"), "trunkcode", "14%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("ADD_PREFIX"), "trunkprefix", "6%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("REMOVE_PREFIX"), "removeprefix", "2%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("TECH"), "providertech", "6%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("IP/HOST"), "providerip" , "14%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("PROVIDER"), "id_provider" ,"9%", "center", "sort", "15", "lie", "cc_provider", "provider_name", "id='%id'", "%1");
	$HD_Form -> AddViewElement(gettext("MINUTES USED"), "secondusedreal", "%9", "center", "SORT", "30", "", "", "", "", "", "display_minute");
	$HD_Form -> AddViewElement(gettext("STATUS"), "status", "7%", "center", "sort", "", "list", $actived_list);
	$HD_Form -> AddViewElement(gettext("MAXUSE"), "maxuse" , "5%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("Minutes/day"), "minutes_per_day" , "5%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("Minutes/day used"), "id_trunk" , "5%", "center", "", "", "", "", "", "", "", "display_minute_used");
	$HD_Form -> AddViewElement(gettext("Whole Minutes/day used"), "id_trunk" , "5%", "center", "", "", "", "", "", "", "", "display_wholeminute_used");
	$HD_Form -> AddViewElement(gettext("Priority"), "priority" , "5%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("Calls/day"), "calls_per_day" , "5%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("Calls/day used"), "id_trunk" , "5%", "center", "", "", "", "", "", "", "", "display_calls_used");
    $HD_Form -> AddViewElement(gettext("TIMEZONE"), "trunk_GMT", "1%", "center", "SORT", "30", "", "", "", "", "", "display_timezone");
	$HD_Form -> AddViewElement(gettext("TOLL FREE"), "tollfree", "1%", "center", "sort", "", "list", Constants::getYesNoList());

	$HD_Form -> FieldViewElement ('trunkcode, trunkprefix, removeprefix, providertech, providerip, id_provider, secondusedreal, status, maxuse, minutes_per_day, id_trunk, id_trunk, priority, calls_per_day, id_trunk, trunk_GMT, tollfree');
}

$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext("Search");
$HD_Form -> AddSearchElement_C1(gettext("IP/HOST"),'providerip','provideriptype');
$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select(gettext("SELECT PROVIDER"), null, null, null, null, null, "id_provider", 0, $provider_list_result);
$HD_Form -> AddSearchElement_Select(gettext("SELECT STATUS"), null, null, null, null, null, "status", 0, $trunk_actived_list);

$HD_Form -> FG_ACTION_SIZE_COLUMN = '15%';
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


if ($popup_select) {
	$HD_Form -> FG_LIMITE_DISPLAY = 7;
	$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=" . $popup_select . "&popup_formname=" . $popup_formname . "&popup_fieldname=" . $popup_fieldname;

	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	//echo $popup_select;
	if ($popup_select == 1)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
	elseif ($popup_select == 2)
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
} else {
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;
	$HD_Form -> FG_ADDITION = true;
}

$counters = getTrunkCounters($id);
$def_value_inuse = '';
$def_value_maxuse = '';
$def_value_minutes = '';
$def_value_attempts = '';
$def_value_statuses = '';
$def_value_priority = '';
$def_value_attempt_delay = '';
$def_value_calls = '';
if (isset($form_action) && $form_action=="ask-add") {
    $counters = null;
    $def_value_inuse = "value='0'";
    $def_value_maxuse = "value='-1'";
    $def_value_minutes = "value='0'";
    $def_value_attempts = "value='0'";
    $def_value_statuses = "value=''";
    $def_value_priority = "value='0'";
    $def_value_attempt_delay = "value='0'";
    $def_value_calls = "value='0'";
}

$HD_Form -> AddEditElement (gettext("VOIP-PROVIDER"),
	"id_provider",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_provider",
	"provider_name, id",
	"", "", "%1","", "", '', '<OPTION  value="-1" selected>NOT DEFINED</OPTION>');

$HD_Form -> AddEditElement (gettext("LABEL"),
	"trunkcode",
	"$value",
	"INPUT",
	"size=45 maxlength=40",
	"3",
	gettext("Insert the trunkcode"),
	"" , "", "", "", "" , "" , "", gettext("Unique and friendly name for the trunk"));

$HD_Form -> AddEditElement (gettext("ADD PREFIX"),
	"trunkprefix",
	"$value",
	"INPUT",
	"size=30 maxlength=20",
	"",
	gettext("Insert the trunkprefix"),
	"" , "", "", "", "" , "" , "", gettext("Add a prefix to the dialled digits."));

$HD_Form -> AddEditElement (gettext("REMOVE PREFIX"),
	"removeprefix",
	"$value",
	"INPUT",
	"size=120",
	"",
	gettext("Insert the removeprefix"),
	"" , "", "", "", "" , "" , "", gettext("Remove prefix from the dialed digits.") . " " . gettext("Supported format: SEARCH_PLAIN[{,}/SEARCH_REGEX/[{-}REPLACEMENT][{-}STOP_FLAG]{,}/SEARCH_REGEX/{,}SEARCH_PLAIN{-}REPLACEMENT{,}etc...] Separators are: {,} - between rules , {-} - between SEARCH and REPLACEMENT rules and STOP sign (if 3rd parameter greater than 0 - than rule process will be stoped if current rule was applied; if / found in the rule - it is being recognized as REGEX; default for REPLACEMENT is empty string; default for STOP_FLAG is zero)."));

$HD_Form -> AddEditElement (gettext("PROVIDER TECH"),
	"providertech",
	"$value",
	"INPUT",
	"size=20 maxlength=15",
	"0",
	gettext("Insert the providertech"),
	"" , "", "", "", "" , "" , "", gettext("Technology used on the trunk (SIP,IAX2,ZAP,H323)"));

$HD_Form -> AddEditElement (gettext("PROVIDER IP"),
	"providerip",
	"$value",
	"INPUT",
	"size=80 maxlength=140",
	"9",
	gettext("Insert the providerip"),
	"" , "", "", "", "" , "" , "", gettext("Set the IP or URL of the VoIP provider. Alternatively, put in the name of a previously defined trunk in Asterisk or FreePBX. (MyVoiPTrunk, ZAP4G etc.) You can use the following tags to as variables: %dialingnumber%, %cardnumber%. ie g2/1644787890wwwwwwwwww%dialingnumber%"));

$HD_Form -> AddEditElement (gettext("ADDITIONAL PARAMETER"),
	"addparameter",
	"$value",
	"INPUT",
	"size=60 maxlength=100",
	"",
	gettext("Insert Additional parameters"),
	"" , "", "", "", "" , "" , "", gettext("Define any additional parameters that will be used when running the Dial Command in Asterisk. Use the following tags as variables  %dialingnumber%, %cardnumber%. ie 'D(ww%cardnumber%wwwwwwwwww%dialingnumber%)'"));

$HD_Form -> AddEditElement (gettext("FAILOVER TRUNK"),
	"failover_trunk",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_trunk",
	"trunkcode, id_trunk",
	"", "", "%1", "", gettext("You can define another trunk in case of failover!"), '', '<OPTION  value="-1" selected>NOT DEFINED</OPTION>');

$HD_Form -> AddEditElement (gettext("CURRENT CONNECTIONS"),
	"inuse",
	"",
	"INPUT",
	"size=30 $def_value_inuse maxlength=30",
	"12",
	gettext("Number of current connections"),
	"" , "", "", "", "", "", "", gettext("Updated to show the number of channels currently in use on this trunk.If there are no channels in use, and the system shows that there are, manually reset this field back to zero.") );

$HD_Form -> AddEditElement (gettext("MAXIMUM CONNECTIONS"),
	"maxuse",
	"",
	"INPUT",
	"size=30 $def_value_maxuse maxlength=30",
	"12",
	gettext("Number of maximum simultaneous connections"),
	"" , "", "", "", "", "", "", gettext("The maximum number of channels available to this trunk. Set to -1 to have an unlimited number of channels") );

$HD_Form -> AddEditElement (gettext("IFMAXUSED"),
	"if_max_use",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $maxuse_behaviour_list, "%1" , "", gettext("Specifies which trunk to use when the maximum number of connections is reached"));

$HD_Form -> AddEditElement (gettext("STATUS"),
	"status",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", gettext("Define if this trunk is active or not"));

$HD_Form -> AddEditElement (gettext("Minutes/day"),
	"minutes_per_day",
	"",
	"INPUT",
	"size=9 $def_value_minutes maxlength=30",
	"12",
	"",
	"" , "", "", "", "", "", "", gettext("Number of maximum minutes to the gateway per day, to disable - set to 0 (zero)") . (is_array($counters) ? ". <b>" . sprintf(gettext("Minutes today: %s"), sprintf("%02d", intval($counters['seconds'] / 60)) . ":" . sprintf("%02d", intval($counters['seconds'] % 60))) . sprintf(", Whole minutes today: %ld", $counters['minutes']) . '</b>' : ''));

$HD_Form -> AddEditElement (gettext("Attempts"),
	"attempt_count",
	"",
	"INPUT",
	"size=9 $def_value_attempts maxlength=30",
	"12",
	"",
	"" , "", "", "", "", "", "", gettext("Additional attempts according attempt condition, to disable - set to 0 (zero)"));

$HD_Form -> AddEditElement (gettext("Attempt condition"),
	"attempt_condition",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $attempt_cond_list, "%1" , "", gettext("Condition for checking the dialstatus in list"));

$HD_Form -> AddEditElement (gettext("Attempt dial status(es) list"),
	"attempt_statuses",
	"$value",
	"INPUT",
	"size=100 $def_value_statuses maxlength=255",
	"",
	"",
	"" , "", "", "", "", "", "", gettext("The list of dialstatuses (comma (,) as separator). Use any of: ANSWER,BUSY,NOANSWER,CONGESTION,CHANUNAVAIL,DONTCALL,TORTURE,INVALIDARGS,CANCEL. If empty - does nothing..."));

$HD_Form -> AddEditElement (gettext("Priority"),
	"priority",
	"",
	"INPUT",
	"size=9 $def_value_priority maxlength=30",
	"12",
	"",
	"" , "", "", "", "", "", "", gettext("Trunk select priority for tariffplan algorithm"));

$HD_Form -> AddEditElement (gettext("Attempt delay"),
	"attempt_delay",
	"",
	"INPUT",
	"size=9 $def_value_attempt_delay maxlength=30",
	"12",
	"",
	"" , "", "", "", "", "", "", gettext("Delay between calls to the trunk in seconds, to disable - set to 0 (zero)"));

$HD_Form -> AddEditElement (gettext("Calls/day"),
	"calls_per_day",
	"",
	"INPUT",
	"size=9 $def_value_calls maxlength=30",
	"12",
	"",
	"" , "", "", "", "", "", "", gettext("Number of maximum success calls to the gateway per day, to disable - set to 0 (zero)") . (is_array($counters) ? ". <b>" . sprintf(gettext("Calls today: %s"), $counters['success_calls']) . '</b>' : ''));

$HD_Form -> AddEditElement (gettext("Timezone"),
	"trunk_GMT",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $timezone_list, "%1" , "", gettext("Trunk timezone"));

$HD_Form -> AddEditElement (gettext("TOLL FREE"),
	"tollfree",
	'0',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if this provider is toll free"),
	"" , "", "", "Yes:1, - No:0", "", "" , "", gettext("Define if you want to apply toll free logic"));

$HD_Form -> FieldEditElement ('id_provider, trunkcode, trunkprefix, removeprefix, providertech, providerip, addparameter, failover_trunk, inuse, maxuse, if_max_use, status, minutes_per_day, attempt_count, attempt_condition, attempt_statuses, priority, attempt_delay, calls_per_day, trunk_GMT, tollfree');

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the properties of the trunk below.");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = 'Fill correctly the fields to add a new '.$HD_Form->FG_INSTANCE_NAME;

//$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";


