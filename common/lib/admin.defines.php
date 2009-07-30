<?php

define ("PHP_QUICK_PROFILER", true);
// Include PHP-Quick-Profiler
require_once('PhpQuickProfiler.php');
$profiler = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());


define ("WRITELOG_QUERY",false);
define ("FSROOT", substr(dirname(__FILE__),0,-3));
define ("LIBDIR", FSROOT."lib/");	

include (FSROOT."lib/interface/constants.php");
include_once (dirname(__FILE__)."/Class.A2Billing.php");
require_once('adodb/adodb.inc.php'); // AdoDB
include_once (dirname(__FILE__)."/Class.Table.php");
include_once (dirname(__FILE__)."/Class.Connection.php");

// USE PHPMAILER
include_once (FSROOT."lib/mail/class.phpmailer.php");

// INCLUDE MISC
include (FSROOT."lib/Misc.php");
include (dirname(__FILE__)."/Class.NotificationsDAO.php");
include (dirname(__FILE__)."/Class.Notification.php");
include (dirname(__FILE__)."/Class.Mail.php");

session_name("UIADMINSESSION");
session_start();

$G_instance_Query_trace = Query_trace::getInstance();

// A2B INSTANCE
$A2B = new A2Billing();

// Store script start time
$_START_TIME = time();

// The system will not log for Public/index.php and signup/index.php
$URI = $_SERVER['REQUEST_URI'];

// Enable UI Logger
define ("ENABLE_LOG", 1);
include (FSROOT."lib/Class.Logger.php");
$log = new Logger();


// LOAD THE CONFIGURATION
if (stripos($URI, "Public/index.php") === FALSE) {
	$res_load_conf = $A2B -> load_conf($agi, A2B_CONFIG_DIR."a2billing.conf", 1);
	if (!$res_load_conf) exit;
}

// Parameter to enable/disable the update of list of value in Config Edition
define("LIST_OF_VALUES", false);

// Define a demo mode
define("DEMO_MODE", false);

// Parameter to show link to Asterisk GUI
define("ASTERISK_GUI_LINK", false);
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
define ("SMTP_PORT", isset($A2B->config['global']['smtp_port'])?$A2B->config['global']['smtp_port']:'25');
define ("SMTP_SECURE", isset($A2B->config['global']['smtp_secure'])?$A2B->config['global']['smtp_secure']:null);

define ("USE_REALTIME", isset($A2B->config['global']['use_realtime'])?$A2B->config['global']['use_realtime']:0);

define ("BUDDY_SIP_FILE", isset($A2B->config['webui']['buddy_sip_file'])?$A2B->config['webui']['buddy_sip_file']:null);
define ("BUDDY_IAX_FILE", isset($A2B->config['webui']['buddy_iax_file'])?$A2B->config['webui']['buddy_iax_file']:null);
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

// SIP IAX FRIEND CREATION
define ("FRIEND_TYPE", isset($A2B->config['peer_friend']['type'])?$A2B->config['peer_friend']['type']:null);
define ("FRIEND_ALLOW", isset($A2B->config['peer_friend']['allow'])?$A2B->config['peer_friend']['allow']:null);
define ("FRIEND_CONTEXT", isset($A2B->config['peer_friend']['context'])?$A2B->config['peer_friend']['context']:null);
define ("FRIEND_NAT", isset($A2B->config['peer_friend']['nat'])?$A2B->config['peer_friend']['nat']:null);
define ("FRIEND_AMAFLAGS", isset($A2B->config['peer_friend']['amaflags'])?$A2B->config['peer_friend']['amaflags']:null);
define ("FRIEND_QUALIFY", isset($A2B->config['peer_friend']['qualify'])?$A2B->config['peer_friend']['qualify']:null);
define ("FRIEND_HOST", isset($A2B->config['peer_friend']['host'])?$A2B->config['peer_friend']['host']:null);
define ("FRIEND_DTMFMODE", isset($A2B->config['peer_friend']['dtmfmode'])?$A2B->config['peer_friend']['dtmfmode']:null);

//DIDX.NET API
define ("DIDX_ID", isset($A2B->config['webui']['didx_id'])?$A2B->config['webui']['didx_id']:null);
define ("DIDX_PASS", isset($A2B->config['webui']['didx_pass'])?$A2B->config['webui']['didx_pass']:null);
define ("DIDX_MIN_RATING", isset($A2B->config['webui']['didx_min_rating'])?$A2B->config['webui']['didx_min_rating']:null);
define ("DIDX_SITE", "api.didx.net");
define ("DIDX_RING_TO", isset($A2B->config['webui']['didx_ring_to'])?$A2B->config['webui']['didx_ring_to']:null);

// VOICEMAIL
define ("ACT_VOICEMAIL", false);

// SHOW DONATION
define ("SHOW_DONATION", true);

// AGI
define ("ASTERISK_VERSION", isset($A2B->config['agi-conf1']['asterisk_version'])?$A2B->config['agi-conf1']['asterisk_version']:'1_4');


// GLOBAL USED VARIABLE
$PHP_SELF = $_SERVER["PHP_SELF"];
$CURRENT_DATETIME = date("Y-m-d H:i:s");		

/*
 *		GLOBAL POST/GET VARIABLE
 */
getpost_ifset(array('form_action', 'atmenu', 'action', 'stitle', 'sub_action', 'IDmanager', 'current_page', 'order', 'sens', 'mydisplaylimit', 'filterprefix', 'cssname', 'popup_select', 'language', 'msg'));

// Language Selection
if (isset($language)) {
	$_SESSION["language"] = $language;
        setcookie  ("language",$language);
} elseif (!isset($_SESSION["language"])) {
       if(!isset($_COOKIE["language"])) $_SESSION["language"]='english';
       else $_SESSION["language"]=$_COOKIE["language"];
}
// Open menu
if (!empty($_GET["section"])) {
	$section = $_GET["section"];
	$_SESSION["menu_section"] = $section;
}

define ("LANGUAGE",$_SESSION["language"]);
define ("BINDTEXTDOMAIN", '../../common/admin_ui_locale');
require("languageSettings.php");
SetLocalLanguage();

/*
 *		CONNECT / DISCONNECT DATABASE
 */
function DbConnect()
{
	return Connection::GetDBHandler();
}

function DbDisconnect($DBHandle)
{
	$DBHandle ->disconnect();
}


getpost_ifset(array('cssname'));
	
if(isset($cssname) && $cssname != "") {
	$_SESSION["stylefile"] = $cssname;
}
	
if(isset($cssname) && $cssname != "") {
	if ($_SESSION["stylefile"]!=$cssname) {
		foreach (glob("./templates_c/*.*") as $filename)
		{
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
include (LIBDIR."admin.help.php");

include (LIBDIR."common.defines.php");


define ("RELOAD_ASTERISK_IF_SIPIAX_CREATED", isset($A2B->config["signup"]['reload_asterisk_if_sipiax_created'])?$A2B->config["signup"]['reload_asterisk_if_sipiax_created']:0);

if((stripos($URI, "Public/index.php") === FALSE) && isset($_SESSION["admin_id"])) {
	// Insert Log
	$log -> insertLog($_SESSION["admin_id"], 1, "Page Visit", "User Visited the Page", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],'');
	$log = null;
}

