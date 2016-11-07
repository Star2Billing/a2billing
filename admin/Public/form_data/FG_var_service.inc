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


getpost_ifset(array('id', 'name', 'amount', 'period', 'rule', 'daynumber', 'stopmode', 'maxnumbercycle', 'status', 'emailreport'));


$HD_Form = new FormHandler("cc_service","Service");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_service.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add RECURRING SERVICE");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/cog_add.png" ;



// TODO Integrate a generic LIST to Framework
$period_list = array();
$period_list["1"]  = array( "Daily", "1");
for ($k=2;$k<=60;$k++)
        $period_list["$k"]  = array( "every $k days", "$k");

$cnts = new Constants();
$actived_list = $cnts->getActivationList();

$rule_list = array();
$rule_list["3"]  = array( gettext("Apply Service every Y days"), "3");
$rule_list["1"]  = array( gettext("Apply service if card not used in last X days"), "1");
$rule_list["2"]  = array( gettext("Apply service if card has been used in the last X Days"), "2");
$rule_list["0"]  = array( gettext("No rule"), "0");

$daynumber_list = array();
for ($k=1;$k<=20;$k++)
        $daynumber_list["$k"]  = array( "$k", "$k");

$stopmode_list = array();
$stopmode_list["0"]  = array( gettext("Never"), "0");
$stopmode_list["1"]  = array( gettext("Account balance below zero"), "1");
$stopmode_list["2"]  = array( gettext("Max number of cycle reach"), "2");

$operatemode_list = array();
$operatemode_list["0"] = array(gettext("Allow be charged into negative balance"),"0");
$operatemode_list["1"] = array(gettext("Do partial charge but never go into negative balance"),"1");

$usegroup_list = array();
$usegroup_list["0"] = array(gettext("List of groups below"),"0");
$usegroup_list["1"] = array(gettext("Any group"),"1");

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

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
// View Details
$HD_Form -> FG_OTHER_BUTTON1 = true;
// |param| will be replace by id .
// you don't need that, if you don't supply any |param| string id will be append after FG_OTHER_BUTTON1_LINK.

$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:;\" onClick=\"MM_openBrWindow('CC_entity_service_details.php?displayfooter=0&popup_select=1&displayheader=0&id=|param|','','scrollbars=yes,resizable=yes,width=600,height=350')\"";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path . "/icon-viewdetails.gif";
$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext("VIEW DETAILS");


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

$HD_Form -> AddEditElement (gettext("RULE"),
	"rule",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $rule_list, "%1", "", gettext("where X - day number (X) in days <br>	and Y = number set in period field"));

$HD_Form -> AddEditElement ("DAY NUMBER (X)",
	"daynumber",
	"$value",
	"INPUT",
	"size=20 maxlength=3",
	"4",
	gettext("Insert the daynumber"),
	"" , "", "", "", "", "" , "NO", gettext("Set the number of days to apply to the rule above."));

$HD_Form -> AddEditElement (gettext("PERIOD (Y)"),
	"period",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $period_list, "%1" , "" , gettext("Interval to apply service"));

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

$HD_Form -> AddEditElement (gettext("CALL PLAN"),
       "dialplan",
       "",
       "SELECT",
       "", "", "",
       "sql",
       "cc_tariffgroup union select '--ANY--',0",
       "tariffgroupname, id",
       "", "", "%1", "", "");

$HD_Form -> AddEditElement (gettext("OPERATE MODE"),
        "operate_mode",
        '$value',
        "SELECT",
        "",
        "",
        "",
        "list" , "", "", "", $operatemode_list, "%1", "", gettext("If you want to charge only part of sum in case if current balance less then amount, select second option. Will be charged only part of amount, cause balance to be 0.0"));

$HD_Form -> AddEditElement (gettext("SKIP GROUP"),
        "use_group",
        '$value',
        "SELECT",
        "",
        "",
        "",
        "list" , "", "", "", $usegroup_list, "%1", "", gettext("Select first option(default) to manualy select groups or select second option to use service for any group, discarding list below"));

$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_card_group");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow > 0) {
	$HD_Form -> AddEditElement (gettext("CARD GROUP"),
		"service",
		'$value',
		"SELECT",
		'size="10" multiple="multiple"',
		"",
		"",
		"sql",
		"cards_services",
		"name, id",
		"", "", "%1 : (%2)", "", "", "cc_cardgroup_service:id_card_group:cc_card_group LEFT JOIN cc_cardgroup_service ON cc_card_group.id=cc_cardgroup_service.id_card_group:DISTINCT cc_card_group.name, cc_card_group.id: cc_cardgroup_service.id_service = %id:id_service:%2 - (%1)::::%1:id='%1'");
}

$HD_Form->FieldEditElement('name, amount, rule, daynumber, period, stopmode, maxnumbercycle, status, emailreport, dialplan, operate_mode, use_group');

if (DB_TYPE == "mysql") {
	$HD_Form->FG_QUERY_ADITION_HIDDEN_FIELDS = "datecreate ";
	$HD_Form->FG_QUERY_ADITION_HIDDEN_VALUE = "now() ";
}

$HD_Form->FG_INTRO_TEXT_EDITION = gettext("You can modify, through the following form, the different properties of your") . " " . $HD_Form->FG_INSTANCE_NAME;
$HD_Form->FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this") . " " . $HD_Form->FG_INSTANCE_NAME . ", " . gettext("click on the delete button.");
$HD_Form->FG_INTRO_TEXT_ADD = gettext("you can add easily a new") . " " . $HD_Form->FG_INSTANCE_NAME . ", " . gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form->FG_INTRO_TEXT_ADITION = gettext("Add a service below");
$HD_Form->FG_TEXT_ADITION_CONFIRMATION = gettext("Your new") . " " . $HD_Form->FG_INSTANCE_NAME . " " . gettext("has been inserted") . ".<br>";

$HD_Form->FG_BUTTON_EDITION_SRC = $HD_Form->FG_BUTTON_ADITION_SRC = Images_Path . "/cormfirmboton.gif";
$HD_Form->FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form->FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form->FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) . "?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form->FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) . "?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form->FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL) . "?atmenu=document&stitle=Document&wh=AC&id=";

