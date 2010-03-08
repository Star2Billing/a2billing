<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
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



getpost_ifset(array('id','subscriber_signup', 'username', 'useralias', 'uipass', 'credit', 'language', 'tariff', 'activated', 'simultaccess', 'currency', 'typepaid', 'creditlimit', 'lastname', 'firstname', 'email', 'address', 'city', 'state', 'country', 'zipcode', 'phone', 'fax', 'inuse', 'cid', 'runservice', 'firstusedate', 'expirationdate', 'enableexpire', 'expiredays','company_name' , 'company_website' , 'VAT_RN' , 'traffic' , 'traffic_target','captchacode','groupid', 'key'));

//put parameter in session
$HD_Form = new FormHandler("cc_card", "User");
$HD_Form->FG_DEBUG = 0;

$HD_Form->FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form->FG_FILTER_SEARCH_SESSION_NAME = 'entity_card_selection';
$HD_Form->FG_ADDITION = true;

$HD_Form->setDBHandler(DbConnect());

$agent_conf = false;
if (!empty ($key)) {
	$agentkey = $key;
	$_SESSION["key"] = $agentkey;
}
elseif (!empty ($_SESSION["key"])) {
	$agentkey = $_SESSION["key"];
}

if (!empty ($agentkey)) {
	$table_key = new Table("cc_agent_signup", "*");
	$data = $table_key->Get_list($HD_Form->DBHandle, "code= '" . $agentkey . "'");
	if (is_array($data)) {
		$groupid = $data[0]['id_group'];
		$tariffid = $data[0]['id_tariffgroup'];
		$agentid = $data[0]['id_agent'];
		// check if groupid and tarif in already in this agent
		$instance_table_agent_check = new Table("cc_agent JOIN cc_agent_tariffgroup ON cc_agent_tariffgroup.id_agent =  cc_agent.id JOIN cc_card_group ON cc_card_group.id_agent =  cc_agent.id  ", "cc_agent.id");
		$list_agent_check = $instance_table_agent_check->Get_list($HD_Form->DBHandle, "cc_agent.id= $agentid AND cc_agent_tariffgroup.id_tariffgroup = $tariffid  AND cc_card_group.id = $groupid", "cc_agent.id", "ASC", null, null, null, null);
		if (is_array($list_agent_check)) {
			if (!empty ($groupid) && is_numeric($groupid) && !empty ($tariffid) && is_numeric($tariffid)) {
				$agent_conf = true;
			} else {
				$agent_conf = false;
			}
		} else {
			$agent_conf = false;
		}
	}
}

if (!$agent_conf) {
	$callPlan_idlist = isset ($A2B->config["signup"]['callplan_id_list']) ? $A2B->config["signup"]['callplan_id_list'] : null;
	if (strlen($callPlan_idlist) == 0) {
		exit ("No Call Plan Defined.");
	}
	$call_table = new Table("cc_tariffgroup", "tariffgroupname,id");
	$callPlan_list = $call_table->Get_list($HD_Form->DBHandle, "id in ($callPlan_idlist)");

	if (!is_array($callPlan_list) && count($callPlan_list) <= 0) {
		exit (gettext("No Call Plan id are defined"));
	}
	$flag_callplan = 0;
	if ($tariff != "" || $tariff != null) {
		$callPlan_count = count($callPlan_list);
		for ($i = 0; $i < $callPlan_count; $i++) {
			if ($callPlan_list[$i][1] == trim($tariff, "\'")) {
				$flag_callplan = 1;
			}
		}
		if ($flag_callplan == 0) {
			exit ("Invalid Call Plan Id");
		}
	}
}

//check subscriber

$table_key = new Table("cc_agent_signup", "*");

//end check subscriber

$currency_list = array ();
$currency_list_r = array ();
$indcur = 0;

$currencies_list = get_currencies();

foreach ($currencies_list as $key => $cur_value) {
	$currency_list[$key] = array (
		$cur_value[1] . ' (' . $cur_value[2] . ')',
		$key
	);
	$currency_list_r[$key] = array (
		$key,
		$cur_value[1]
	);
	$currency_list_key[$key][0] = $key;
}

$HD_Form->CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form->CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form->CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form->FG_LIMITE_DISPLAY = 10;
if (!($popup_select >= 1))
	$HD_Form->FG_EXPORT_CSV = true;
if (!($popup_select >= 1))
	$HD_Form->FG_EXPORT_XML = true;

if (($popup_select >= 1))
	$HD_Form->FG_LIMITE_DISPLAY = 7;

$HD_Form->CV_FOLLOWPARAMETERS = "&popup_select=" . $popup_select . "&popup_formname=" . $popup_formname . "&popup_fieldname=" . $popup_fieldname;

$maxi = '';
$maxi2 = '';
$maxi3 = '';
if (isset ($form_action) && $form_action == "add") {
	$array_card_generated = gen_card_with_alias();
	$maxi = $array_card_generated[0];
	$maxi2 = $array_card_generated[1];
	$pass = MDP_NUMERIC(10);
	$loginkey = MDP_STRING(20);
}

$language_list = Constants::getLanguagesList();
if (LANGUAGE_ENABLE)  {
$HD_Form->AddEditElement(gettext("LANGUAGE"), "language", "", "SELECT", "", "", "", "list", "", "", "", $language_list, "%1", "", "");
$language_editFG = "language,";
}
if (CURRENCY_ENABLE)  {
$HD_Form->AddEditElement("CURRENCY", "currency", "", "SELECT", "", "", "", "list", "", "", "", $currency_list, "%1", "", "");
$currency_editFG = "currency,";
}
if (!$agent_conf && strpos($callPlan_idlist, ',') !== false && !($subscriber_signup>0)) {
	$HD_Form->AddEditElement(gettext("CALL PLAN"), "tariff", "", "SELECT", "", "", "", "list", "", "", "", $callPlan_list, "%1", "", "");
}
$comp_zero = $comp_date_plus = '';
if (isset ($form_action) && $form_action == "ask-add") {
	$begin_date = date("Y");
	$begin_date_plus = date("Y") + 10;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='" . $begin_date . $end_date . "'";
	$comp_date_plus = "value='" . $begin_date_plus . $end_date . "'";
	$comp_zero = "value='0'";
}
if (LASTNAME_ENABLE)  {
$HD_Form->AddEditElement(gettext("LASTNAME"), 
		"lastname",
		'$value',
		"INPUT",
		"size=30 maxlength=50", "11",
		gettext("Insert your lastname"), "", "", "", "", "", "", "", "");
$lastname_editFG = "lastname,";
}
if (FIRSTNAME_ENABLE)  {
$HD_Form->AddEditElement(gettext("FIRSTNAME"), 
		"firstname",
		'$value',
		"INPUT",
		"size=30 maxlength=50", "11",
		gettext("Insert your firstname"), "", "", "", "", "", "", "", "");
$firstname_editFG = "firstname,";
}
$HD_Form->AddEditElement(gettext("EMAIL"), 
		"email",
		'$value',
		"INPUT",
		"size=30 maxlength=70", "1",
		gettext("Insert your email"), "", "", "", "", "", "", "", "");
if (ADDRESS_ENABLE)  {
$HD_Form->AddEditElement(gettext("ADDRESS"), 
		"address",
		'$value',
		"INPUT",
		"size=30 maxlength=100", "0",
		gettext("Insert your address"), "", "", "", "", "", "", "", "");
$address_editFG = "address,";
}
if (CITY_ENABLE)  {
$HD_Form->AddEditElement(gettext("CITY"), 
		"city",
		'$value',
		"INPUT",
		"size=30 maxlength=40", "0",
		gettext("Insert your city"), "", "", "", "", "", "", "", "");
$city_editFG = "city,";
}
if (STATE_ENABLE)  {
$HD_Form->AddEditElement(gettext("STATE/PROVINCE"), 
		"state", 
		'$value',
		"INPUT",
		"size=30 maxlength=40", "11",
		gettext("Insert your state"), "", "", "", "", "", "", "NO", "");
$state_editFG = "state,";
}
if ($form_action == 'ask-add') {
	$country_default_val = 'USA';
}
if (COUNTRY_ENABLE)  {
$HD_Form->AddEditElement(gettext("COUNTRY"), 
					"country", 
					"$country_default_val", 
					"SELECT", "", "", "", 
					"sql", 
					"cc_country", "countryname, countrycode", 
					"", "", "%1", "", "", "", '');
$country_editFG = "country,";
}
if (ZIPCODE_ENABLE)  {
$HD_Form->AddEditElement(gettext("ZIP/POSTAL CODE"), "zipcode", '$value', "INPUT", "size=30 maxlength=20", "0", gettext("Insert your zipcode"), "", "", "", "", "", "", "", "");
$zipcode_editFG = "zipcode,";
}
$timezone_list = get_timezones();
$one_select = false;
foreach ($timezone_list as $key => $cur_value) {
	$timezone_list[$key] = array (
		$cur_value[2],
		$key
	);
	if ($form_action == 'ask-add') {
		if (in_array(SERVER_GMT, $cur_value) && !$one_select) {
			$timezone_default_val = $key;
			$one_select = true;
		}
	}
}
if (TIMEZONE_ENABLE)  {
$HD_Form->AddEditElement(gettext("TIMEZONE"), 
								"id_timezone", 
								"$timezone_default_val", 
								"SELECT", 
								"", "", "", 
								"list", "", "", "", 
								$timezone_list, 
								"%1", "", "");
$id_timezone_editFG = "id_timezone,";
}
if (PHONE_ENABLE)  {
$HD_Form->AddEditElement(gettext("PHONENUMBER"), 
								"phone", 
								'$value', 
								"INPUT", "size=30 maxlength=20", 
								"7", gettext("Insert your phone number"), "", "", "", "", "", "", "", "");
$phone_editFG = "phone,";
}
if (FAX_ENABLE)  {
$HD_Form->AddEditElement(gettext("FAXNUMBER"), 
								"fax", 
								'$value', 
								"INPUT", "size=30 maxlength=20", 
								"", gettext("Insert your fax number"), "", "", "", "", "", "", "", "");
$fax_editFG = "fax,";
}
if (COMP_ENABLE)  {
$HD_Form->AddEditElement(gettext("COMPANY NAME"), 
								"company_name", 
								'$value', 
								"INPUT", "size=40 maxlength=50", 
								"", gettext("Insert Company name of this customer"), "", "", "", "", "", "", "NO", "");
$company_name_editFG = "company_name,";
}
if (COMP_WEB_ENABLE)  {
$HD_Form->AddEditElement(gettext("COMPANY WEBSITE"), 
								"company_website", 
								'$value', 
								"INPUT", "size=40 maxlength=60", 
								"", gettext("Insert the company website of this customer"), "", "", "", "", "", "", "NO", "");
$company_website_editFG = "company_website,";
}
if (VAT_RN_ENABLE)  {
$HD_Form->AddEditElement(gettext("VAT REGISTRATION NUMBER "), 
								"VAT_RN", 
								'$value', 
								"INPUT", "size=40 maxlength=40", 
								"", gettext("Insert the VAT registration number of this customer"), "", "", "", "", "", "", "NO", "");
$VAT_RN_editFG = "VAT_RN,";
}
if (TRAFFIC_ENABLE)  {
$HD_Form->AddEditElement(gettext("TRAFFIC PER MONTH"), 
								"traffic", 
								'$value', 
								"INPUT", "size=30 maxlength=20", 
								"4", gettext("Insert the traffic per month for this customer"), "", "", "", "", "", "", "NO", "");
$traffic_editFG = "traffic,";
}
if (TRAFFIC_TARGET_ENABLE)  {
$HD_Form->AddEditElement(gettext("TARGET TRAFFIC"), 
								"traffic_target", 
								'', 
								"TEXTAREA", "cols=50 rows=4", 
								"", gettext("Insert the target traffic description"), "", "", "", "", "", "", "", "");
$traffic_target_editFG = "traffic_target,";
}
if (CAPTCHA_ENABLE) {
	$HD_Form->AddEditElement(gettext("VERIFICATION"), "captchacode", '$value', "CAPTCHAIMAGE", "size=10 maxlength=6", "19", gettext("Insert code from above picture"), "", "", "", "", "", "", "", "", CAPTCHA_ENABLE);
	$captcha = "captchacode,";
}

if (!$agent_conf && strpos($callPlan_idlist, ',') !== false && !($subscriber_signup>0)) {
	$HD_Form->FieldEditElement('username, useralias, uipass, credit, ' . $language_editFG . ' tariff, id_didgroup, id_campaign,' .
	'callback, activated, simultaccess, ' . $currency_editFG . ' runservice, typepaid, creditlimit, firstusedate, enableexpire,' .
	'expirationdate, expiredays, vat, ' . $lastname_editFG . $firstname_editFG . ' email,' . $address_editFG . $city_editFG . $state_editFG . $country_editFG . $zipcode_editFG . $id_timezone_editFG . $phone_editFG . $fax_editFG . $company_name_editFG . $company_website_editFG . $VAT_RN_editFG . $traffic_editFG . $traffic_target_editFG . $captcha . ' sip_buddy, iax_buddy, inuse');
} else {
	$HD_Form->FieldEditElement('username, useralias, uipass, credit, ' . $language_editFG . ' id_didgroup, id_campaign,' .
	'callback, activated, simultaccess, ' . $currency_editFG . ' runservice, typepaid, creditlimit, firstusedate, enableexpire,' .
	'expirationdate, expiredays, vat, ' . $lastname_editFG . $firstname_editFG . ' email,' . $address_editFG . $city_editFG . $state_editFG . $country_editFG . $zipcode_editFG . $id_timezone_editFG . $phone_editFG . $fax_editFG . $company_name_editFG . $company_website_editFG . $VAT_RN_editFG . $traffic_editFG . $traffic_target_editFG . $captcha . ' sip_buddy, iax_buddy, inuse');
}

if (!isset ($myusername))
	$myusername = '';

//Code here for Getting Data from configuration files
$comp_date_plus = $A2B->config["signup"]['expirationdate'];

if (!$A2B->config["signup"]['expirationdate']) {
	$comp_date_plus = '';
	$begin_date = date("Y");
	$begin_date_plus = date("Y") + 10;
	$end_date = date("-m-d H:i:s");
	$comp_date_plus = $begin_date_plus . $end_date;
}

$mytariff = '';
$mycredit = '';

$myexpirationdate = $comp_date_plus;
$mytariff = $callPlan_idlist;
$myactivated = $A2B->config["signup"]['activated'] ? 't' : 'f';
$mysimultaccess = $A2B->config["signup"]['simultaccess'];
$mytypepaid = $A2B->config["signup"]['typepaid'];
$mycreditlimit = $A2B->config["signup"]['creditlimit'];
$myrunservice = $A2B->config["signup"]['runservice'];
$myenableexpire = $A2B->config["signup"]['enableexpire'];
$myexpiredays = $A2B->config["signup"]['expiredays'];
$mycredit = $A2B->config["signup"]['credit'];
$sip_account = $A2B->config["signup"]['sip_account'] ? 1 : 0;
$iax_account = $A2B->config["signup"]['iax_account'] ? 1 : 0;
$amaflag = $A2B->config["signup"]['amaflag'];
$context = $A2B->config["signup"]['context'];

if ($A2B->config["signup"]['activated']) {
	// Status : 1 - Active
	$status = $A2B->config["signup"]['activatedbyuser'] ? '1' : '3';
} else {
	// Status : 2 - New
	$status = $A2B->config["signup"]['activatedbyuser'] ? '2' : '3';
}

$HD_Form->FG_QUERY_ADITION_HIDDEN_FIELDS = "iax_buddy, sip_buddy, expiredays, expirationdate, typepaid, enableexpire, runservice, simultaccess, credit, username, useralias, uipass, loginkey, status";
$HD_Form->FG_QUERY_ADITION_HIDDEN_VALUE = "$iax_account, $sip_account, $myexpiredays, $myexpirationdate, $mytypepaid, $myenableexpire, $myrunservice, $mysimultaccess, $mycredit, $maxi, $maxi2, $pass, $loginkey, $status";

if ($agent_conf) {
	$HD_Form->FG_QUERY_ADITION_HIDDEN_FIELDS .= ",id_group";
	$HD_Form->FG_QUERY_ADITION_HIDDEN_VALUE .= ",$groupid";
}

// username, useralias, uipass, loginkey
$HD_Form->FG_QUERY_EXTRA_HIDDED = array (
	$maxi,
	$maxi2,
	$pass,
	$loginkey
);

if (!is_numeric($subscriber_signup)) {
	if (!$agent_conf && strpos($callPlan_idlist, ',') === false) {
		$HD_Form->FG_QUERY_ADITION_HIDDEN_FIELDS .= ", tariff";
		$HD_Form->FG_QUERY_ADITION_HIDDEN_VALUE .= ", $mytariff";
	} elseif ($agent_conf) {
		$HD_Form->FG_QUERY_ADITION_HIDDEN_FIELDS .= ", tariff";
		$HD_Form->FG_QUERY_ADITION_HIDDEN_VALUE .= ", $tariffid";
	}
}

// If Service - find the callplan associate
if (is_numeric($subscriber_signup)) {

	$table_subscribe = new Table("cc_subscription_signup", "id_callplan");
	$data = $table_subscribe->Get_list($HD_Form->DBHandle, "id='" . $subscriber_signup . "'");
	if (is_array($data)) {
		$id_callplan = $data[0]['id_callplan'];
		$HD_Form->FG_QUERY_ADITION_HIDDEN_FIELDS .= ", tariff";
		$HD_Form->FG_QUERY_ADITION_HIDDEN_VALUE .= ", $id_callplan";
	}
	
	// HIDDEN VALUE
	$HD_Form->FG_ADITION_HIDDEN_PARAM = 'subscriber_signup';
	$HD_Form->FG_ADITION_HIDDEN_PARAM_VALUE = "$subscriber_signup";
}

$HD_Form->FG_FILTER_APPLY = true;
$HD_Form->FG_FILTERFIELD = 'cardnumber';
$HD_Form->FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form->FG_FILTER_FORM_ACTION = 'list';

$HD_Form->FG_INTRO_TEXT_EDITION = gettext("You can modify, through the following form, the different properties of your " . $HD_Form->FG_INSTANCE_NAME);
$HD_Form->FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this " . $HD_Form->FG_INSTANCE_NAME . ", click on the delete button.");
$HD_Form->FG_INTRO_TEXT_ADD = gettext("you can add easily a new " . $HD_Form->FG_INSTANCE_NAME . ".<br>Fill the following fields and confirm by clicking on the button add.");

$HD_Form->FG_INTRO_TEXT_ADITION = '';
$HD_Form->FG_TEXT_ADITION_CONFIRMATION = "<font>" . gettext("Thanks for registering with us.<br>A email with your information has been sent to your account ") . $_POST["email"] . "<br><br><b>" . gettext("Your card number is ") . $maxi . "</b><br><b>" . gettext("Your password is ") . $pass . "</b></font>";

$HD_Form->FG_BUTTON_EDITION_SRC = $HD_Form->FG_BUTTON_ADITION_SRC = Images_Path . "/cormfirmboton.gif";
$HD_Form->FG_ADD_PAGE_CONFIRM_BUTTON = gettext("SIGNUP");

$HD_Form->FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'processing_card_signup';

$HD_Form->FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF'] . "?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form->FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF'] . "?atmenu=document&stitle=Document&wh=AC&id=";


