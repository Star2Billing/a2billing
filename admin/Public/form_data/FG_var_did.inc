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

getpost_ifset(array('id', 'billingtype', 'did', 'startingdate', 'expirationdate', 'id_cc_didgroup', 'id_cc_country', 'activated', 'fixrate', 'id_trunk', 'max_concurrent', 'connection_charge','selling_rate', 'aleg_timeinterval', 'aleg_carrier_connect_charge', 'aleg_carrier_cost_min', 'aleg_carrier_initblock', 'aleg_carrier_increment', 'aleg_retail_connect_charge', 'aleg_retail_cost_min', 'aleg_retail_initblock', 'aleg_retail_increment', 'aleg_carrier_connect_charge_offp', 'aleg_carrier_cost_min_offp', 'aleg_carrier_initblock_offp', 'aleg_carrier_increment_offp', 'aleg_retail_connect_charge_offp', 'aleg_retail_cost_min_offp', 'aleg_retail_initblock_offp', 'aleg_retail_increment_offp'));


$HD_Form = new FormHandler("cc_did","did");

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_did.php?form_action=ask-add&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add DID");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/telephone_add.png" ;

$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "did";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'add_did_use';
$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_DELETE = 'did_use_delete';
$HD_Form -> FG_ADDITIONAL_FUNCTION_BEFORE_DELETE = 'is_did_in_use';

$actived_list = Constants::getActivationList();
$billingtype_list = Constants::getBillingTypeList();
// -- billtype: 0 = fix per month + dialoutrate, 1= fix per month, 2 = dialoutrate, 3 = free
$billingtype_list_short = Constants::getBillingTypeShortList();

$cnts= new Constants();
$yesno = $cnts->getYesNoList();


$HD_Form -> AddViewElement(gettext("DID"), "did", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CUSTOMER"), "iduser", "10%", "center", "sort", "", "lie_link", "cc_card", "lastname, id, firstname", "id='%id'", "%1 %3", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("BILLING"), "billingtype", "10%", "center", "sort", "", "list", $billingtype_list_short);
$HD_Form -> AddViewElement(gettext("START DATE"), "startingdate", "14%", "center", "sort", "19", "", "", "", "",  "display_dateformat");
$HD_Form -> AddViewElement(gettext("DIDGROUP"), "id_cc_didgroup", "8%", "center", "sort", "15", "lie", "cc_didgroup", "didgroupname", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("COUNTRY"), "id_cc_country", "8%", "center", "sort", "15", "lie", "cc_country", "countryname", "id='%id'", "%1");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("ACTIVATED")."\">".gettext("ACT")."</acronym>", "activated", "5%", "center", "sort", "", "list", $actived_list);
$HD_Form -> AddViewElement("<acronym title=\"".gettext("MINUTES USED")."\">".gettext("MU")."</acronym>", "secondusedreal", "5%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("RESERVED")."\">".gettext("RES")."</acronym>", "reserved", "5%", "center", "SORT", "", "list", $yesno);
$HD_Form -> AddViewElement("<acronym title=\"".gettext("MONTHLY RATE")."\">".gettext("MR")."</acronym>", "fixrate", "5%", "center", "SORT", "30");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("SELLING RATE")."\">".gettext("SR")."</acronym>", "selling_rate", "5%", "center", "SORT", "30");

$HD_Form -> FieldViewElement ('did, iduser, billingtype, startingdate, id_cc_didgroup, id_cc_country, activated, secondusedreal, reserved, fixrate, selling_rate');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_SPLITABLE_FIELD = 'did';


// TODO integrate in Framework
if ($form_action=="ask-add" || $form_action=="purchase") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y") + 10;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

if ($form_action=="purchase") {
	$didval = "value='".$did."'";
	$fixval = "value='".$fixrate."'";
}
if ($form_action=="ask-add") {
	$fixval = "value='0'";
}

$did_regexpress = '9';


$HD_Form -> AddEditElement (gettext("DID"),
	"did",
	'$value',
	"INPUT",
	"size=40 maxlength=60 $didval",
	"$did_regexpress",
	gettext("Insert the DID"),
	"" , "", "", "", "" , "", "" ,
	gettext("Define the DID's. If you ADD a new DID, NOT an EDIT, you can define a range of DID. <br>80412340210-80412340218 would add all DID's between the range, whereas DIDs separated by a comma e.g. 80412340210,80412340212,80412340214 would only add the individual DID listed."));


$HD_Form -> AddEditElement (gettext("BILLING"),
	"billingtype",
	"",
	"SELECT",
	"",
	"",
	"",
	"list" , "", "",  "", $billingtype_list, "%1" , "", "");


$HD_Form -> AddEditElement (gettext("START DATE"),
	"startingdate",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $comp_date",
	"10",
	gettext("Insert the starting date"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'") );


$HD_Form -> AddEditElement (gettext("EXPIRY DATE"),
	"expirationdate",
	'$value',
	"INPUT",
	"size=40 maxlength=40  $comp_date_plus",
	"10",
	gettext("Insert the expiration date"),
	"" , "", "", "", "", "", "res_display_dateformat", gettext("Format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'") );


$HD_Form -> AddEditElement (gettext("DIDGROUP"),
	"id_cc_didgroup",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_didgroup",
	"didgroupname, id",
	"", "", "%1","", "");


$HD_Form -> AddEditElement (gettext("COUNTRY"),
	"id_cc_country",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_country",
	"countryname, id",
	"", "", "%1", "", "");


$HD_Form -> AddEditElement (gettext("ACTIVATED"),
	"activated",
	'1',
	"RADIOBUTTON",
	"",
	"",
	gettext("Choose if you want to activate this card"),
	"" , "", "", "Yes :1, - No:0", "", "", "" , "" );


$HD_Form -> AddEditElement (gettext("MONTHLY RATE"),
	"fixrate",
	'$value',
	"INPUT",
	"size=20  maxlength=20 $fixval",
	"12",
	gettext("Insert the monthly rate"),
	"" , "", "", "", "", "", "", gettext("currency : ").strtoupper(BASE_CURRENCY),
	null, null, gettext("Define the renting cost for the customers.") );


$HD_Form -> AddEditElement(gettext("CONNECT CHARGE"),
   "connection_charge",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert the connect charge"),
   "" , "", "",	"", "", "" , "NO", gettext("Apply a connection charge to connect DID together"),
   null, null, gettext("DID On Net Charges") );
   // On Net Charges is where one customer calls another customer without any external carrier being involved in the call

$HD_Form -> AddEditElement(gettext("SELLING RATE"),
   "selling_rate",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert the rate"),
   "" , "", "",	"", "" , "", "", gettext("The retail rate; or the cost per minute to apply to the customer to connect DID together, e.g. 0.02"));

$HD_Form -> AddEditElement (gettext("TIME INTERVAL"),
   "aleg_timeinterval",
   '$value',
   "TEXTAREA",
   "cols=50 rows=4",
   "",
   gettext("Insert time definition for peak and off peak time intervals."),
   "" , "", "", "", "" , "", "", gettext("The format is \"start_time;day_of_week;day_of_month;month;time_interval_type\" e.g. 7:00-19:00|mon-fri|*|*|p for peak hours or *|*|24|dec|o for off peak during Xmas day and so on. The last character indicates time interval type where \"p\" means PEAK and \"o\" OFF-PEAK. More definition one per line are supported. If there are PEAK and OFF-PEAK time interval definitions overlaps, than the OFF-PEAK is used."),
   null, null, gettext("Peak / Off Peak Time definition") );


$HD_Form -> AddEditElement(gettext("CARRIER CONNECTION CHARGE"),
   "aleg_carrier_connect_charge",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert a rate"),
   "" , "", "",	"", "" , "", "", gettext("The connection charge can be negative or positive"),
   null, null, gettext("DID Ingress Charges Peak") );


$HD_Form -> AddEditElement(gettext("CARRIER COST PER MINUTE"),
   "aleg_carrier_cost_min",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert the connect cost"),
   "" , "", "",	"", "", "" , "NO", gettext("The cost can be negative for Non geographic calls where there is an out-payment"));


$HD_Form -> AddEditElement(gettext("CARRIER MIN DURATION"),
   "aleg_carrier_initblock",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the buyrate init block"),
   "" , "", "",	"", "", "" , "NO", gettext("Set the minimum duration charged by the carrier. (i.e. 30 secs)"));


$HD_Form -> AddEditElement(gettext("CARRIER BILLING BLOCK"),
   "aleg_carrier_increment",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the billing block"),
   "" , "", "",	"", "", "" , "NO", gettext("Set the billing increment, in seconds (billing block), that the carrier applies. (ie 30 secs)"));


$HD_Form -> AddEditElement(gettext("RETAIL CONNECTION CHARGE"),
   "aleg_retail_connect_charge",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert a rate"),
   "" , "", "",	"", "" , "", "", gettext("The connection charge can be negative or positive"));


$HD_Form -> AddEditElement(gettext("RETAIL COST PER MINUTE"),
   "aleg_retail_cost_min",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert the connect cost"),
   "" , "", "",	"", "", "" , "NO", gettext("The cost can be negative where there is an out payment, or more usually, positive"));


$HD_Form -> AddEditElement(gettext("RETAIL MIN DURATION"),
   "aleg_retail_initblock",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the init block"),
   "" , "", "",	"", "", "", "NO", gettext("Set the minimum duration to charge the customer (ie 60 seconds)"));


$HD_Form -> AddEditElement(gettext("RETAIL BILLING BLOCK"),
   "aleg_retail_increment",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the billing block"),
   "" , "", "",	"", "", "", "NO", gettext("Set the billing increment in seconds to bill your customer."));


$HD_Form -> AddEditElement(gettext("CARRIER CONNECTION CHARGE OFF PEAK"),
   "aleg_carrier_connect_charge_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert a rate"),
   "" , "", "",	"", "" , "", "", gettext("The connection charge can be negative or positive"),
   null, null, gettext("DID Ingress Charges Off Peak") );


$HD_Form -> AddEditElement(gettext("CARRIER COST PER MINUTE OFF PEAK"),
   "aleg_carrier_cost_min_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert the connect cost"),
   "" , "", "",	"", "", "" , "NO", gettext("The cost can be negative for Non geographic calls where there is an out-payment"));


$HD_Form -> AddEditElement(gettext("CARRIER MIN DURATION OFF PEAK"),
   "aleg_carrier_initblock_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the buyrate init block"),
   "" , "", "",	"", "", "" , "NO", gettext("Set the minimum duration charged by the carrier. (i.e. 30 secs)"));


$HD_Form -> AddEditElement(gettext("CARRIER BILLING BLOCK OFF PEAK"),
   "aleg_carrier_increment_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the billing block"),
   "" , "", "",	"", "", "" , "NO", gettext("Set the billing increment, in seconds (billing block), that the carrier applies. (ie 30 secs)"));


$HD_Form -> AddEditElement(gettext("RETAIL CONNECTION CHARGE OFF PEAK"),
   "aleg_retail_connect_charge_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert a rate"),
   "" , "", "",	"", "" , "", "", gettext("The connection charge can be negative or positive"));


$HD_Form -> AddEditElement(gettext("RETAIL COST PER MINUTE OFF PEAK"),
   "aleg_retail_cost_min_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20 $fixval",
   "12",
   gettext("Insert the connect cost"),
   "" , "", "",	"", "", "" , "NO", gettext("The cost can be negative where there is an out payment, or more usually, positive"));


$HD_Form -> AddEditElement(gettext("RETAIL MIN DURATION OFF PEAK"),
   "aleg_retail_initblock_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the init block"),
   "" , "", "",	"", "", "", "NO", gettext("Set the minimum duration to charge the customer (ie 60 seconds)"));


$HD_Form -> AddEditElement(gettext("RETAIL BILLING BLOCK OFF PEAK"),
   "aleg_retail_increment_offp",
   '$value',
   "INPUT",
   "size=30 maxlength=20",
   "4",
   gettext("Insert the billing block"),
   "" , "", "",	"", "", "", "NO", gettext("Set the billing increment in seconds to bill your customer."));


$HD_Form -> AddEditElement (gettext("MAX CONCURRENT CALLS"),
   "max_concurrent",
   '$value',
   "INPUT",
   "size=5 maxlength=5",
   "",
   "",
   "" , "", "",   "", "", "", "NO", gettext("Max concurrent calls (Feature not implemented)") );

$HD_Form -> FieldEditElement ('did, billingtype, startingdate, expirationdate, id_cc_didgroup, id_cc_country, activated, fixrate, connection_charge, selling_rate, aleg_timeinterval, aleg_carrier_connect_charge, aleg_carrier_cost_min, aleg_carrier_initblock, aleg_carrier_increment, aleg_retail_connect_charge, aleg_retail_cost_min, aleg_retail_initblock, aleg_retail_increment, aleg_carrier_connect_charge_offp, aleg_carrier_cost_min_offp, aleg_carrier_initblock_offp, aleg_carrier_increment_offp, aleg_retail_connect_charge_offp, aleg_retail_cost_min_offp, aleg_retail_initblock_offp, aleg_retail_increment_offp, max_concurrent');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'did';
$HD_Form -> FG_FILTERFIELDNAME = 'did';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="did like '$filterprefix%'";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";

