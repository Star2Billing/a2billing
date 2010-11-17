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


include_once (FSROOT."lib/interface/constants.php");
include_once (dirname(__FILE__)."/Class.A2Billing.php");
require_once('adodb/adodb.inc.php'); // AdoDB
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

session_name("UIADMINSESSION");
session_start();

// Control Session Time
if (isset($_SESSION['startTime'])) {
    $timeDiff = time() - $_SESSION['startTime'];
  

    //destroy session
    if ($timeDiff > 3600) { // 60 minutes
        //echo "You've been logged in too long. ($timeDiff)";
        $_SESSION = array();
        session_destroy();
        setcookie('PHPSESSID', '', time()-3600, '/', '', 0,0);
    }
} else {
  $_SESSION['startTime'] = time();
}


$G_instance_Query_trace = Query_trace::getInstance();

// A2B INSTANCE
$A2B = new A2Billing();


// The system will not log for Public/index.php and signup/index.php
if (isset($_SERVER['REQUEST_URI'])) {
    $URI = $_SERVER['REQUEST_URI'];
} else {
    $URI = '';
}
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

//SIP/IAX Info
define ("SIP_IAX_INFO_TRUNKNAME",isset($A2B->config['sip-iax-info']['sip_iax_info_trunkname'])?$A2B->config['sip-iax-info']['sip_iax_info_trunkname']:null);
define ("SIP_IAX_INFO_ALLOWCODEC",isset($A2B->config['sip-iax-info']['sip_iax_info_allowcodec'])?$A2B->config['sip-iax-info']['sip_iax_info_allowcodec']:null);
define ("SIP_IAX_INFO_HOST",isset($A2B->config['sip-iax-info']['sip_iax_info_host'])?$A2B->config['sip-iax-info']['sip_iax_info_host']:null);
define ("IAX_ADDITIONAL_PARAMETERS",isset($A2B->config['sip-iax-info']['iax_additional_parameters'])?$A2B->config['sip-iax-info']['iax_additional_parameters']:null);
define ("SIP_ADDITIONAL_PARAMETERS",isset($A2B->config['sip-iax-info']['sip_additional_parameters'])?$A2B->config['sip-iax-info']['sip_additional_parameters']:null);

/*
 *		GLOBAL POST/GET VARIABLE
 */
getpost_ifset(array('form_action', 'atmenu', 'action', 'stitle', 'sub_action', 'IDmanager', 'current_page', 'order', 'sens', 'mydisplaylimit', 'filterprefix', 'cssname', 'popup_select', 'popup_formname', 'popup_fieldname', 'ui_language', 'msg', 'section'));

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
define ("BINDTEXTDOMAIN", '../../common/admin_ui_locale');
require("languageSettings.php");
SetLocalLanguage();

// Open menu
if (!empty($section)) {
	$_SESSION["menu_section"] = $section;
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


// COPYRIGHT
if (!isset($disable_check_cp) || $disable_check_cp != true)
    define ("LCMODAL", check_cp());


define ("RELOAD_ASTERISK_IF_SIPIAX_CREATED", isset($A2B->config["signup"]['reload_asterisk_if_sipiax_created'])?$A2B->config["signup"]['reload_asterisk_if_sipiax_created']:0);

if((stripos($URI, "Public/index.php") === FALSE) && isset($_SESSION["admin_id"])) {
	// Insert Log
	$log -> insertLog($_SESSION["admin_id"], 1, "Page Visit", "User Visited the Page", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],'');
	$log = null;
}



