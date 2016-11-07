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

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}


getpost_ifset(array('id', 'trunkcode', 'trunkprefix', 'removeprefix', 'providertech', 'providerip', 'addparameter','failover_trunk', 'id_provider', 'popup_select', 'inuse', 'maxuse', 'status', 'if_max_use'));

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

	$HD_Form -> FieldViewElement ('id_trunk, trunkcode, id_provider');
} else {
	$HD_Form -> AddViewElement(gettext("LABEL"), "trunkcode", "14%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("ADD_PREFIX"), "trunkprefix", "6%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("REMOVE_PREFIX"), "removeprefix", "6%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("TECH"), "providertech", "6%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("IP/HOST"), "providerip" , "14%", "center", "sort");
	$HD_Form -> AddViewElement(gettext("PROVIDER"), "id_provider" ,"9%", "center", "sort", "15", "lie", "cc_provider", "provider_name", "id='%id'", "%1");
	$HD_Form -> AddViewElement(gettext("MINUTES USED"), "secondusedreal", "%9", "center", "SORT", "30", "", "", "", "", "", "display_minute");
	$HD_Form -> AddViewElement(gettext("STATUS"), "status", "7%", "center", "sort", "", "list", $actived_list);
	$HD_Form -> AddViewElement(gettext("MAXUSE"), "maxuse" , "5%", "center", "sort");

	$HD_Form -> FieldViewElement ('trunkcode, trunkprefix, removeprefix, providertech, providerip, id_provider, secondusedreal, status, maxuse');
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

$def_value_inuse = '';
$def_value_maxuse = '';
if (isset($form_action) && $form_action=="ask-add"){
	$def_value_inuse = "value='0'";
	$def_value_maxuse = "value='-1'";
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
	"size=30 maxlength=20",
	"",
	gettext("Insert the removeprefix"),
	"" , "", "", "", "" , "" , "", gettext("Remove prefix from the dialed digits."));

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

$HD_Form -> AddEditElement ("IFMAXUSED",
	"if_max_use",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $maxuse_behaviour_list, "%1" , "", gettext("Specifies which trunk to use when the maximum number of connections is reached"));

$HD_Form -> AddEditElement ("STATUS",
	"status",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $actived_list, "%1" , "", gettext("Define if this trunk is active or not"));


$HD_Form -> FieldEditElement ('id_provider, trunkcode, trunkprefix, removeprefix, providertech, providerip, addparameter, failover_trunk, inuse, maxuse, if_max_use, status');


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


