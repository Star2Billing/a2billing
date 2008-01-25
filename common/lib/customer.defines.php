<?php
include_once (dirname(__FILE__)."/Class.A2Billing.php");
include_once (dirname(__FILE__)."/Class.Table.php");
//	require_once('DB.php'); // PEAR
require_once('adodb/adodb.inc.php'); // AdoDB

$A2B = new A2Billing();

// SELECT THE FILES TO LOAD THE CONFIGURATION
$A2B -> load_conf($agi, AST_CONFIG_DIR."a2billing.conf", 1);

// DEFINE FOR THE DATABASE CONNECTION
define ("HOST", isset($A2B->config['database']['hostname'])?$A2B->config['database']['hostname']:null);
define ("PORT", isset($A2B->config['database']['port'])?$A2B->config['database']['port']:null);
define ("USER", isset($A2B->config['database']['user'])?$A2B->config['database']['user']:null);
define ("PASS", isset($A2B->config['database']['password'])?$A2B->config['database']['password']:null);
define ("DBNAME", isset($A2B->config['database']['dbname'])?$A2B->config['database']['dbname']:null);
define ("DB_TYPE", isset($A2B->config['database']['dbtype'])?$A2B->config['database']['dbtype']:null);

define ("LEN_ALIASNUMBER", isset($A2B->config['global']['len_aliasnumber'])?$A2B->config['global']['len_aliasnumber']:null);
define ("LEN_VOUCHER", isset($A2B->config['global']['len_voucher'])?$A2B->config['global']['len_voucher']:null);
define ("BASE_CURRENCY", isset($A2B->config['global']['base_currency'])?$A2B->config['global']['base_currency']:null);
define ("MANAGER_HOST", isset($A2B->config['global']['manager_host'])?$A2B->config['global']['manager_host']:null);
define ("MANAGER_USERNAME", isset($A2B->config['global']['manager_username'])?$A2B->config['global']['manager_username']:null);
define ("MANAGER_SECRET", isset($A2B->config['global']['manager_secret'])?$A2B->config['global']['manager_secret']:null);
define ("SERVER_GMT", isset($A2B->config['global']['server_GMT'])?$A2B->config['global']['server_GMT']:null);
define ("CUSTOMER_UI_URL", isset($A2B->config['global']['customer_ui_url'])?$A2B->config['global']['customer_ui_url']:null);
define ("SMTP_SERVER", isset($A2B->config['global']['smtp_server'])?$A2B->config['global']['smtp_server']:null);
define ("SMTP_HOST", isset($A2B->config['global']['smtp_host'])?$A2B->config['global']['smtp_host']:null);
define ("SMTP_USERNAME", isset($A2B->config['global']['smtp_username'])?$A2B->config['global']['smtp_username']:null);
define ("SMTP_PASSWORD", isset($A2B->config['global']['smtp_password'])?$A2B->config['global']['smtp_password']:null);

// For ePayment Modules
define('PULL_DOWN_DEFAULT', 'Please Select');
define('TYPE_BELOW', 'Type Below');
define('TEXT_CCVAL_ERROR_INVALID_DATE', gettext('The expiry date entered for the credit card is invalid.')."<br>".gettext('Please check the date and try again.'));
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', gettext('The credit card number entered is invalid.')."<br>".gettext('Please check the number and try again.'));
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', gettext('The first four digits of the number entered are').": %s<br>".gettext('If that number is correct, we do not accept that type of credit card.')."<br>".gettext('If it is wrong, please try again.'));

define('REVIEW_TEXT_MIN_LENGTH', '10');
define('CC_OWNER_MIN_LENGTH', '2');
define('CC_NUMBER_MIN_LENGTH', '15');

// javascript messages
define('JS_ERROR', gettext('Errors have occured during the process of your form.')."\n\n".gettext('Please make the following corrections:\n\n'));
define('JS_REVIEW_TEXT', gettext('* The \'Review Text\' must have at least').' ' . REVIEW_TEXT_MIN_LENGTH .' '. gettext('characters').'.\n');
define('JS_REVIEW_RATING', '* '.gettext('You must rate the product for your review.').'\n');
define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* '.gettext('Please select a payment method for your order.').'\n');
define('JS_ERROR_SUBMITTED', gettext('This form has already been submitted. Please press Ok and wait for this process to be completed.'));
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

define ("EPAYMENT_PURCHASE_AMOUNT", isset($A2B->config['epayment_method']['purchase_amount'])?$A2B->config['epayment_method']['purchase_amount']:null);

// WEB DEFINE FROM THE A2BILLING.CONF FILE
define ("EMAIL_ADMIN", isset($A2B->config['webui']['email_admin'])?$A2B->config['webui']['email_admin']:null);
define ("NUM_MUSICONHOLD_CLASS", isset($A2B->config['webui']['num_musiconhold_class'])?$A2B->config['webui']['num_musiconhold_class']:null);
define ("SHOW_HELP", isset($A2B->config['webui']['show_help'])?$A2B->config['webui']['show_help']:null);	
define ("MY_MAX_FILE_SIZE_IMPORT", isset($A2B->config['webui']['my_max_file_size_import'])?$A2B->config['webui']['my_max_file_size_import']:null);
define ("MY_MAX_FILE_SIZE", isset($A2B->config['webui']['my_max_file_size'])?$A2B->config['webui']['my_max_file_size']:null);
define ("DIR_STORE_MOHMP3",isset($A2B->config['webui']['dir_store_mohmp3'])?$A2B->config['webui']['dir_store_mohmp3']:null);
define ("DIR_STORE_AUDIO", isset($A2B->config['webui']['dir_store_audio'])?$A2B->config['webui']['dir_store_audio']:null);
define ("MY_MAX_FILE_SIZE_AUDIO", isset($A2B->config['webui']['my_max_file_size_audio'])?$A2B->config['webui']['my_max_file_size_audio']:null);
$file_ext_allow = isset($A2B->config['webui']['file_ext_allow'])?$A2B->config['webui']['file_ext_allow']:null;
$file_ext_allow_musiconhold = isset($A2B->config['webui']['file_ext_allow_musiconhold'])?$A2B->config['webui']['file_ext_allow_musiconhold']:null;
define ("LINK_AUDIO_FILE", isset($A2B->config['webui']['link_audio_file'])?$A2B->config['webui']['link_audio_file']:null);
define ("MONITOR_PATH", isset($A2B->config['webui']['monitor_path'])?$A2B->config['webui']['monitor_path']:null);
define ("MONITOR_FORMATFILE", isset($A2B->config['webui']['monitor_formatfile'])?$A2B->config['webui']['monitor_formatfile']:null); 
define ("SHOW_ICON_INVOICE", isset($A2B->config['webui']['show_icon_invoice'])?$A2B->config['webui']['show_icon_invoice']:null);
define ("SHOW_TOP_FRAME", isset($A2B->config['webui']['show_top_frame'])?$A2B->config['webui']['show_top_frame']:null);
define ("ADVANCED_MODE", isset($A2B->config['webui']['advanced_mode'])?$A2B->config['webui']['advanced_mode']:null);
define ("CURRENCY_CHOOSE", isset($A2B->config['webui']['currency_choose'])?$A2B->config['webui']['currency_choose']:null);

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
define ("PAYPAL_LOGFILE", isset($A2B->config['paypal']['paypal_logfile'])?$A2B->config['paypal']['paypal_logfile']:null);


define ("SIGNUP_LINK", isset($A2B->config["webcustomerui"]['signup_page_url'])?$A2B->config["webcustomerui"]['signup_page_url']:null);	
define ("ACTIVATEDBYUSER", isset($A2B->config["signup"]['activatedbyuser'])?$A2B->config["signup"]['activatedbyuser']:null);


// INCLUDE FILES
define ("FSROOT", substr(dirname(__FILE__),0,-3));
define ("LIBDIR", FSROOT."lib/");
include (FSROOT."lib/Misc.php");

// USE PHPMAILER
include_once (FSROOT."lib/mail/class.phpmailer.php");

/*
 *		GLOBAL USED VARIABLE
 */
$PHP_SELF = $_SERVER["PHP_SELF"];


$CURRENT_DATETIME = date("Y-m-d H:i:s");

/*
 *		GLOBAL POST/GET VARIABLE
 */
getpost_ifset(array('form_action', 'atmenu', 'action', 'stitle', 'sub_action', 'IDmanager', 'current_page', 'order', 'sens', 'mydisplaylimit', 'filterprefix','language', 'cssname', 'exporttype'));


// Include general language file
	// Language session
session_start();
if(ini_get('register_globals'))
{
	foreach($_REQUEST as $key => $value)
	{
		$$key = $value;
	}
}
if (!isset($_SESSION["language"]))
{
	$_SESSION["language"]='english';
}
else if (isset($language))
{
  $_SESSION["language"] = $language;
}

define ("LANGUAGE",$_SESSION["language"]);
//include (FSROOT."lib/languages/".LANGUAGE.".php");
//define ("LANGUAGE_DIR",FSROOT."lib/languages/".LANGUAGE."/");
require("languageSettings.php");

SetLocalLanguage();

/*
 *		CONNECT / DISCONNECT DATABASE
 */

function DbConnect($db= NULL)
{
	$ADODB_CACHE_DIR = '/tmp';
	/*	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;	*/
	
	if (DB_TYPE == "postgres"){
		$datasource = 'pgsql://'.USER.':'.PASS.'@'.HOST.'/'.DBNAME;
	}else{
		$datasource = 'mysql://'.USER.':'.PASS.'@'.HOST.'/'.DBNAME;
	}
	
	$DBHandle = NewADOConnection($datasource);
	if (!$DBHandle) die("Connection failed");
	
	return $DBHandle;
}



function DbDisconnect($DBHandle)
{
	$DBHandle ->disconnect();
}


if(isset($cssname) && $cssname != "")
{
	if ($_SESSION["stylefile"]!=$cssname){
		foreach (glob("./templates_c/*.*") as $filename)
		{
			unlink($filename);
		}			
	}
	$_SESSION["stylefile"] = $cssname;		
}

if(!isset($_SESSION["stylefile"]) || $_SESSION["stylefile"]==''){
	$_SESSION["stylefile"]='default';
}

// EPayment Module Settings
define ("HTTP_SERVER", isset($A2B->config["epayment_method"]['http_server'])?$A2B->config["epayment_method"]['http_server']:null);
define ("HTTPS_SERVER", isset($A2B->config["epayment_method"]['https_server'])?$A2B->config["epayment_method"]['https_server']:null);
define ("HTTP_COOKIE_DOMAIN", isset($A2B->config["epayment_method"]['http_cookie_domain'])?$A2B->config["epayment_method"]['http_cookie_domain']:null);
define ("HTTPS_COOKIE_DOMAIN", isset($A2B->config["epayment_method"]['https_cookie_domain'])?$A2B->config["epayment_method"]['https_cookie_domain']:null);
define ("HTTP_COOKIE_PATH", isset($A2B->config["epayment_method"]['http_cookie_path'])?$A2B->config["epayment_method"]['http_cookie_path']:null);
define ("HTTPS_COOKIE_PATH", isset($A2B->config["epayment_method"]['https_cookie_path'])?$A2B->config["epayment_method"]['https_cookie_path']:null);
define ("DIR_WS_HTTP_CATALOG", isset($A2B->config["epayment_method"]['dir_ws_http_catalog'])?$A2B->config["epayment_method"]['dir_ws_http_catalog']:null);
define ("DIR_WS_HTTPS_CATALOG", isset($A2B->config["epayment_method"]['dir_ws_https_catalog'])?$A2B->config["epayment_method"]['dir_ws_https_catalog']:null);
define ("ENABLE_SSL", isset($A2B->config["epayment_method"]['enable_ssl'])?$A2B->config["epayment_method"]['enable_ssl']:null);
define ("EPAYMENT_TRANSACTION_KEY", isset($A2B->config["epayment_method"]['transaction_key'])?$A2B->config["epayment_method"]['transaction_key']:null);
define ("PAYPAL_VERIFY_URL", isset($A2B->config["epayment_method"]['paypal_verify_url'])?$A2B->config["epayment_method"]['paypal_verify_url']:null);
define ("MONEYBOOKERS_SECRETWORD", isset($A2B->config["epayment_method"]['moneybookers_secretword'])?$A2B->config["epayment_method"]['moneybookers_secretword']:null);

//SIP/IAX Info
define ("SIP_IAX_INFO_TRUNKNAME",isset($A2B->config['sip-iax-info']['sip_iax_info_trunkname'])?$A2B->config['sip-iax-info']['sip_iax_info_trunkname']:null);
define ("SIP_IAX_INFO_ALLOWCODEC",isset($A2B->config['sip-iax-info']['sip_iax_info_allowcodec'])?$A2B->config['sip-iax-info']['sip_iax_info_allowcodec']:null);
define ("SIP_IAX_INFO_HOST",isset($A2B->config['sip-iax-info']['sip_iax_info_host'])?$A2B->config['sip-iax-info']['sip_iax_info_host']:null);
define ("IAX_ADDITIONAL_PARAMETERS",isset($A2B->config['sip-iax-info']['iax_additional_parameters'])?$A2B->config['sip-iax-info']['iax_additional_parameters']:null);
define ("SIP_ADDITIONAL_PARAMETERS",isset($A2B->config['sip-iax-info']['sip_additional_parameters'])?$A2B->config['sip-iax-info']['sip_additional_parameters']:null);

//Images Path
define ("Images_Path","./templates/".$_SESSION["stylefile"]."/images");
define ("Images_Path_Main","./templates/".$_SESSION["stylefile"]."/images");
define ("KICON_PATH","./templates/".$_SESSION["stylefile"]."/images/kicons");
define('DIR_WS_IMAGES', Images_Path.'/');
define ("INVOICE_IMAGE", isset($A2B->config["global"]['invoice_image'])?$A2B->config["global"]['invoice_image']:null);
define ("ADMIN_EMAIL", isset($A2B->config["global"]['admin_email'])?$A2B->config["global"]['admin_email']:null);

// A2BILLING INFO
define ("WEBUI_DATE", 'Release : no date');
define ("WEBUI_VERSION", 'Asterisk2Billing - Version 1.4 - Trunk');
// A2BILLING COPYRIGHT & CONTACT
define ("TEXTCONTACT", gettext("This software has been created by Areski under GPL licence. For futher information, feel free to contact me:"));
define ("EMAILCONTACT", "areski@gmail.com");
define ("COPYRIGHT", gettext(" This software is under GPL licence. For further information, please visit : <a href=\"http://www.asterisk2billing.org\" target=\"_blank\">asterisk2billing.org</a>"));
define ("CCMAINTITLE", gettext("Asterisk2Billing : CallingCard & VOIP Billing system"));

include ("help.php");

define ("ENABLE_LOG", 0);
?>
