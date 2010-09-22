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

define ("PHP_QUICK_PROFILER", false);
// Include PHP-Quick-Profiler
require_once('PhpQuickProfiler.php');
$profiler = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());


define ("WRITELOG_QUERY",false);
define ("FSROOT", substr(dirname(__FILE__),0,-3));
define ("LIBDIR", FSROOT."lib/");

include (FSROOT."lib/interface/constants.php");
include_once (dirname(__FILE__)."/Class.A2Billing.php");
require_once ('adodb/adodb.inc.php'); // AdoDB
include_once (dirname(__FILE__)."/Class.Table.php");
include_once (dirname(__FILE__)."/Class.Connection.php");
include_once (dirname(__FILE__)."/Class.Realtime.php");

// USE PHPMAILER
include_once (FSROOT."lib/mail/class.phpmailer.php");

// INCLUDE MISC
include (FSROOT."lib/Misc.php");
include (dirname(__FILE__)."/Class.NotificationsDAO.php");
include (dirname(__FILE__)."/Class.Notification.php");
include (dirname(__FILE__)."/Class.Mail.php");

session_name("UIAGENTSESSION");
session_start();

$G_instance_Query_trace = Query_trace::getInstance();

define('CC_OWNER_MIN_LENGTH', '2');
define('CC_NUMBER_MIN_LENGTH', '15');


// A2B INSTANCE
$A2B = new A2Billing();

define ("ENABLE_LOG", 1);
include (FSROOT."lib/Class.Logger.php");
$log = new Logger();

// The system will not log for Public/index.php and 
// signup/index.php

//Enable Disable, list of values on page A2B_entity_config.php?form_action=ask-edit&id=1
define("LIST_OF_VALUES",true);

if (!isset($disable_load_conf) || !($disable_load_conf)) {
	// SELECT THE FILES TO LOAD THE CONFIGURATION
	$res_load_conf = $A2B -> load_conf($agi, A2B_CONFIG_DIR."a2billing.conf", 1);
	if (!$res_load_conf) exit;
}

include (LIBDIR."common.defines.php");

// COPYRIGHT
define ("LCMODAL", check_cp());	

// Define a demo mode
define("DEMO_MODE", false);


define ("LEN_ALIASNUMBER", isset($A2B->config['global']['len_aliasnumber'])?$A2B->config['global']['len_aliasnumber']:null);
define ("LEN_VOUCHER", isset($A2B->config['global']['len_voucher'])?$A2B->config['global']['len_voucher']:null);
define ("BASE_CURRENCY", isset($A2B->config['global']['base_currency'])?$A2B->config['global']['base_currency']:null);
define ("MANAGER_HOST", isset($A2B->config['global']['manager_host'])?$A2B->config['global']['manager_host']:null);
define ("MANAGER_USERNAME", isset($A2B->config['global']['manager_username'])?$A2B->config['global']['manager_username']:null);
define ("MANAGER_SECRET", isset($A2B->config['global']['manager_secret'])?$A2B->config['global']['manager_secret']:null);
define ("SERVER_GMT", isset($A2B->config['global']['server_GMT'])?$A2B->config['global']['server_GMT']:null);
define ("CUSTOMER_UI_URL", isset($A2B->config['global']['customer_ui_url'])?$A2B->config['global']['customer_ui_url']:null);

define ("API_SECURITY_KEY", isset($A2B->config['webui']['api_security_key'])?$A2B->config['webui']['api_security_key']:null);

// WEB DEFINE FROM THE A2BILLING.CONF FILE
define ("EMAIL_ADMIN", isset($A2B->config['webui']['email_admin'])?$A2B->config['webui']['email_admin']:'root@localhost');
define ("NUM_MUSICONHOLD_CLASS", isset($A2B->config['webui']['num_musiconhold_class'])?$A2B->config['webui']['num_musiconhold_class']:null);
define ("SHOW_HELP", isset($A2B->config['webui']['show_help'])?$A2B->config['webui']['show_help']:null);	
define ("MY_MAX_FILE_SIZE_IMPORT", isset($A2B->config['webui']['my_max_file_size_import'])?$A2B->config['webui']['my_max_file_size_import']:null);
define ("DIR_STORE_MOHMP3",isset($A2B->config['webui']['dir_store_mohmp3'])?$A2B->config['webui']['dir_store_mohmp3']:null);
define ("DIR_STORE_AUDIO", isset($A2B->config['webui']['dir_store_audio'])?$A2B->config['webui']['dir_store_audio']:null);
define ("MY_MAX_FILE_SIZE_AUDIO", isset($A2B->config['webui']['my_max_file_size_audio'])?$A2B->config['webui']['my_max_file_size_audio']:null);
$file_ext_allow = is_array($A2B->config['webui']['file_ext_allow'])?$A2B->config['webui']['file_ext_allow']:null;
$file_ext_allow_musiconhold = is_array($A2B->config['webui']['file_ext_allow_musiconhold'])?$A2B->config['webui']['file_ext_allow_musiconhold']:null;
define ("LINK_AUDIO_FILE", isset($A2B->config['webui']['link_audio_file'])?$A2B->config['webui']['link_audio_file']:null);
define ("MONITOR_PATH", isset($A2B->config['webui']['monitor_path'])?$A2B->config['webui']['monitor_path']:null);
define ("MONITOR_FORMATFILE", isset($A2B->config['webui']['monitor_formatfile'])?$A2B->config['webui']['monitor_formatfile']:null); 
define ("SHOW_ICON_INVOICE", isset($A2B->config['webui']['show_icon_invoice'])?$A2B->config['webui']['show_icon_invoice']:null);
define ("SHOW_TOP_FRAME", isset($A2B->config['webui']['show_top_frame'])?$A2B->config['webui']['show_top_frame']:null);
define ("ADVANCED_MODE", isset($A2B->config['webui']['advanced_mode'])?$A2B->config['webui']['advanced_mode']:null);
define ("CURRENCY_CHOOSE", isset($A2B->config['webui']['currency_choose'])?$A2B->config['webui']['currency_choose']:null);
define ("DELETE_FK_CARD", isset($A2B->config['webui']['delete_fk_card'])?$A2B->config['webui']['delete_fk_card']:null);
define ("CARD_EXPORT_FIELD_LIST", isset($A2B->config['webui']['card_export_field_list'])?$A2B->config['webui']['card_export_field_list']:null);
define ("RATE_EXPORT_FIELD_LIST", isset($A2B->config['webui']['rate_export_field_list'])?$A2B->config['webui']['rate_export_field_list']:null);
define ("VOUCHER_EXPORT_FIELD_LIST", isset($A2B->config['webui']['voucher_export_field_list'])?$A2B->config['webui']['voucher_export_field_list']:null);


define('ERROR_NO_PAYMENT_MODULE_SELECTED', gettext('Please select a payment method for your order.'));
//CC	
define('MODULE_PAYMENT_CC_TEXT_TITLE', gettext('Credit Card'));
define('MODULE_PAYMENT_CC_TEXT_DESCRIPTION', gettext('Credit Card Test Info').':<br><br>CC#: 4111111111111111<br>'.gettext('Expiry: Any'));
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_TYPE', gettext('Credit Card Type').':');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER', gettext('Credit Card Owner').':');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER', gettext('Credit Card Number').':');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES', gettext('Credit Card Expiry Date').':');
define('MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER', gettext('* The owner\'s name of the credit card must be at least').' '. CC_OWNER_MIN_LENGTH .' '.gettext('characters').'.\n');
define('MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER', gettext('* The credit card number must be at least').' ' . CC_NUMBER_MIN_LENGTH . ' '.gettext('characters').'.\n');
define('MODULE_PAYMENT_CC_TEXT_ERROR', gettext('Credit Card Error!'));
//IPAY
define('MODULE_PAYMENT_IPAYMENT_TEXT_TITLE', 'iPayment');
define('MODULE_PAYMENT_IPAYMENT_TEXT_DESCRIPTION', gettext('Credit Card Test Info').':<br><br>CC#: 4111111111111111<br>'.gettext('Expiry: Any'));
define('IPAYMENT_ERROR_HEADING', gettext('There has been an error processing your credit card'));
define('IPAYMENT_ERROR_MESSAGE', gettext('Please check your credit card details!'));
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_OWNER', gettext('Credit Card Owner:'));
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_NUMBER', gettext('Credit Card Number:'));
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_EXPIRES', gettext('Credit Card Expiry Date:'));
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_CHECKNUMBER', gettext('Credit Card Checknumber:'));
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION', gettext('(located at the back of the credit card)'));
define('MODULE_PAYMENT_IPAYMENT_TEXT_JS_CC_OWNER', gettext('* The owner\'s name of the credit card must be at least').' ' . CC_OWNER_MIN_LENGTH . ' '.gettext('characters.').'\n');
define('MODULE_PAYMENT_IPAYMENT_TEXT_JS_CC_NUMBER', gettext('* The credit card number must be at least').' ' . CC_NUMBER_MIN_LENGTH .' '.gettext('characters').'\n');

// EPayment Module Settings
define ("HTTP_SERVER", isset($A2B->config["epayment_method"]['http_server_agent'])?$A2B->config["epayment_method"]['http_server_agent']:null);
define ("HTTPS_SERVER", isset($A2B->config["epayment_method"]['https_server_agent'])?$A2B->config["epayment_method"]['https_server_agent']:null);
define ("HTTP_COOKIE_DOMAIN", isset($A2B->config["epayment_method"]['http_cookie_domain_agent'])?$A2B->config["epayment_method"]['http_cookie_domain_agent']:null);
define ("HTTPS_COOKIE_DOMAIN", isset($A2B->config["epayment_method"]['https_cookie_domain_agent'])?$A2B->config["epayment_method"]['https_cookie_domain_agent']:null);
define ("HTTP_COOKIE_PATH", isset($A2B->config["epayment_method"]['http_cookie_path_agent'])?$A2B->config["epayment_method"]['http_cookie_path_agent']:null);
define ("HTTPS_COOKIE_PATH", isset($A2B->config["epayment_method"]['https_cookie_path_agent'])?$A2B->config["epayment_method"]['https_cookie_path_agent']:null);
define ("DIR_WS_HTTP_CATALOG", isset($A2B->config["epayment_method"]['dir_ws_http_catalog_agent'])?$A2B->config["epayment_method"]['dir_ws_http_catalog_agent']:null);
define ("DIR_WS_HTTPS_CATALOG", isset($A2B->config["epayment_method"]['dir_ws_https_catalog_agent'])?$A2B->config["epayment_method"]['dir_ws_https_catalog_agent']:null);
define ("ENABLE_SSL", isset($A2B->config["epayment_method"]['enable_ssl'])?$A2B->config["epayment_method"]['enable_ssl']:null);
define ("EPAYMENT_TRANSACTION_KEY", isset($A2B->config["epayment_method"]['transaction_key'])?$A2B->config["epayment_method"]['transaction_key']:null);
define ("PAYPAL_VERIFY_URL", isset($A2B->config["epayment_method"]['paypal_verify_url'])?$A2B->config["epayment_method"]['paypal_verify_url']:null);
define ("MONEYBOOKERS_SECRETWORD", isset($A2B->config["epayment_method"]['moneybookers_secretword'])?$A2B->config["epayment_method"]['moneybookers_secretword']:null);


// PAYPAL	
define ("PAYPAL_EMAIL", isset($A2B->config['paypal']['paypal_email'])?$A2B->config['paypal']['paypal_email']:null);
define ("PAYPAL_FROM_EMAIL",isset( $A2B->config['paypal']['from_email'])?$A2B->config['paypal']['from_email']:null);
define ("PAYPAL_FROM_NAME", isset($A2B->config['paypal']['from_name'])?$A2B->config['paypal']['from_name']:null);
define ("PAYPAL_COMPANY_NAME", isset($A2B->config['paypal']['company_name'])?$A2B->config['paypal']['company_name']:null);
define ("PAYPAL_ERROR_EMAIL", isset($A2B->config['paypal']['error_email'])?$A2B->config['paypal']['error_email']:null);
define ("PAYPAL_ITEM_NAME", isset($A2B->config['paypal']['item_name'])?$A2B->config['paypal']['item_name']:null);
define ("PAYPAL_CURRENCY_CODE", isset($A2B->config['paypal']['currency_code'])?$A2B->config['paypal']['currency_code']:null);
define ("PAYPAL_NOTIFY_URL", isset($A2B->config['paypal']['notify_url'])?$A2B->config['paypal']['notify_url']:null);
define ("PAYPAL_PURCHASE_AMOUNT", isset($A2B->config['paypal']['purchase_amount'])?$A2B->config['paypal']['purchase_amount']:null);
define ("PAYPAL_FEES", isset($A2B->config['paypal']['paypal_fees'])?$A2B->config['paypal']['paypal_fees']:null); 


// BACKUP
define ("BACKUP_PATH", isset($A2B->config['backup']['backup_path'])?$A2B->config['backup']['backup_path']:null);
define ("GZIP_EXE", isset($A2B->config['backup']['gzip_exe'])?$A2B->config['backup']['gzip_exe']:null);
define ("GUNZIP_EXE", isset($A2B->config['backup']['gunzip_exe'])?$A2B->config['backup']['gunzip_exe']:null);
define ("MYSQLDUMP", isset($A2B->config['backup']['mysqldump'])?$A2B->config['backup']['mysqldump']:null);
define ("PG_DUMP", isset($A2B->config['backup']['pg_dump'])?$A2B->config['backup']['pg_dump']:null);
define ("MYSQL", isset($A2B->config['backup']['mysql'])?$A2B->config['backup']['mysql']:null);
define ("PSQL", isset($A2B->config['backup']['psql'])?$A2B->config['backup']['psql']:null);

		
	
/*
 *		GLOBAL POST/GET VARIABLE
 */
getpost_ifset(array('form_action', 'atmenu', 'action', 'stitle', 'sub_action', 'IDmanager', 'current_page', 'order', 'sens', 'mydisplaylimit', 'filterprefix', 'cssname', 'popup_select', 'popup_formname', 'popup_fieldname', 'ui_language', 'msg', 'section'));

if (!isset($_SESSION)) {
	session_start();
}
 
// Language Selection
if (isset($ui_language)) {
	$_SESSION["ui_language"] = $ui_language;
	setcookie  ("ui_language", $ui_language);
} elseif (!isset($_SESSION["ui_language"])) {
    if(!isset($_COOKIE["ui_language"])) 
    	$_SESSION["ui_language"]='english';
    else 
    	$_SESSION["ui_language"]=$_COOKIE["ui_language"];
}

define ("LANGUAGE", $_SESSION["ui_language"]);
define ("BINDTEXTDOMAIN", '../../common/agent_ui_locale');
require("languageSettings.php");
SetLocalLanguage();

// Open menu
if (!empty($section)) {
	$_SESSION["menu_section"] = $section;
}

getpost_ifset(array('cssname'));
	
if(isset($cssname) && $cssname != "")
{
	$_SESSION["stylefile"] = $cssname;
}
	
if(isset($cssname) && $cssname != "") {
	if ($_SESSION["stylefile"]!=$cssname){
		foreach (glob("./templates_c/*.*") as $filename) {
			unlink($filename);
		}
	}
	$_SESSION["stylefile"] = $cssname;		
}

if(!isset($_SESSION["stylefile"]) || $_SESSION["stylefile"]=='') {
	$_SESSION["stylefile"]='default';
}

//Images Path
define ("Images_Path","../Public/templates/".$_SESSION["stylefile"]."/images");
define ("Images_Path_Main","../Public/templates/".$_SESSION["stylefile"]."/images");
define ("KICON_PATH","../Public/templates/".$_SESSION["stylefile"]."/images/kicons");
define ("INVOICE_IMAGE", isset($A2B->config["global"]['invoice_image'])?$A2B->config["global"]['invoice_image']:null);

// INCLUDE HELP
include (LIBDIR."agent.help.php");

//Enable Disable Captcha
define ("CAPTCHA_ENABLE", isset($A2B->config["signup"]['enable_captcha'])?$A2B->config["signup"]['enable_captcha']:0);
define ("RELOAD_ASTERISK_IF_SIPIAX_CREATED", isset($A2B->config["signup"]['reload_asterisk_if_sipiax_created'])?$A2B->config["signup"]['reload_asterisk_if_sipiax_created']:0);



define ("EPAYMENT_PURCHASE_AMOUNT", isset($A2B->config['epayment_method']['purchase_amount_agent'])?$A2B->config['epayment_method']['purchase_amount_agent']:"100");

$URI = $_SERVER['REQUEST_URI'];

if((stripos($URI, "Public/index.php") === FALSE)&& isset($_SESSION["agent_id"])) {
	$log -> insertLogAgent($_SESSION["agent_id"], 1, "Page Visit", "Agent Visited the Page", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],'');
$log = null;
}

