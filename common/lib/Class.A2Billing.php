<?php
/***************************************************************************
 *
 * Class.A2Billing.php : PHP A2Billing Functions for A2Billing
 * Written for PHP 4.x & PHP 5.X versions.
 *
 * A2Billing -- Billing solution for use with Asterisk(tm).
 * Copyright (C) 2004, 2009 Belaid Arezqui <areski _atl_ gmail com>
 *
 * See http://www.a2billing.org for more information about
 * the A2Billing project.
 * Please submit bug reports, patches, etc to <areski _atl_ gmail com>
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 ****************************************************************************/

define('A2B_CONFIG_DIR', '/etc/');
define('AST_CONFIG_DIR', '/etc/asterisk/');
define('DEFAULT_A2BILLING_CONFIG', A2B_CONFIG_DIR . '/a2billing.conf');

// DEFINE VERBOSITY & LOGGING LEVEL : 0 = FATAL; 1 = ERROR; WARN = 2 ; INFO = 3 ; DEBUG = 4
define ('FATAL',			0);
define ('ERROR',			1);
define ('WARN',				2);
define ('INFO',				3);
define ('DEBUG',			4);

class A2Billing {


	/**
    * Config variables
    *
    * @var array
    * @access public
    */
	var $config;

	/**
    * Config AGI variables
	* Create for coding readability facilities
    *
    * @var array
    * @access public
    */
	var $agiconfig;

	/**
    * IDConfig variables
    *
    * @var interger
    * @access public
    */
	var $idconfig=1;


	/**
    * cardnumber & CallerID variables
    *
    * @var string
    * @access public
    */
	var $cardnumber;
	var $CallerID;


	/**
    * Buffer variables
    *
    * @var string
    * @access public
    */
	var $BUFFER;


	/**
    * DBHandle variables
    *
    * @var object
    * @access public
    */
	var $DBHandle;


	/**
    * instance_table variables
    *
    * @var object
    * @access public
    */
	var $instance_table;

	/**
    * store the file name to store the logs
    *
    * @var string
    * @access public
    */
	var $log_file = '';


	/**
    * request AGI variables
    *
    * @var string
    * @access public
    */

	var $channel;
	var $uniqueid;
	var $accountcode;
	var $dnid;

	// from apply_rules, if a prefix is removed we keep it to track exactly what the user introduce
	
	var $myprefix;
	var $ipaddress;
	var $rate;
	var $destination;
	var $sip_iax_buddy;
	var $credit;
	var $tariff;
	var $active;
	var $status;
	var $hostname='';
	var $currency='usd';

	var $groupe_mode = false;
	var $groupe_id = '';
	var $mode = '';
	var $timeout;
	var $newdestination;
	var $tech;
	var $prefix;
	var $username;

	var $typepaid = 0;
	var $removeinterprefix = 1;
	var $restriction = 1;
	var $redial;
	var $nbused = 0;

	var $enableexpire;
	var $expirationdate;
	var $expiredays;
	var $firstusedate;
	var $creationdate;

	var $languageselected;
	var $current_language;


	var $cardholder_lastname;
	var $cardholder_firstname;
	var $cardholder_email;
	var $cardholder_uipass;
	var $id_campaign;
	var $id_card;
	var $useralias;

	// Enable voicemail for this card. For DID and SIP/IAX call
	var $voicemail = 0;

	// Flag to know that we ask for an othercardnumber when for instance we doesnt have enough credit to make a call
	var $ask_other_cardnumber=0;

	var $ivr_voucher;
	var $vouchernumber;
	var $add_credit;

	var $cardnumber_range;

	// Define if we have changed the status of the card
	var $set_inuse = 0;

	/**
	* CC_TESTING variables
	* for developer purpose, will replace some get_data inputs in order to test the application from shell
	*
	* @var interger
	* @access public
	*/
	var $CC_TESTING;
	
	// List of dialstatus
	var $dialstatus_rev_list;
	

	/* CONSTRUCTOR */
	function A2Billing()
	{
		// $this -> agiconfig['debug'] = true;
		// $this -> DBHandle = $DBHandle;
		
		$this -> dialstatus_rev_list = Constants::getDialStatus_Revert_List();
	}


	/* Init */
	function Reinit()
	{
		$this -> myprefix='';
		$this -> ipaddress='';
		$this -> rate='';
		$this -> destination='';
	}


	/*
	 * Debug
	 *
	 * usage : $A2B -> debug( INFO, $agi, __FILE__, __LINE__, $buffer_debug);
	 */
	function debug( $level, $agi, $file, $line, $buffer_debug)
	{
		$file = basename($file);

		// VERBOSE
		if ($this->agiconfig['verbosity_level'] >= $level && $agi) {
			$agi -> verbose('file:'.$file.' - line:'.$line.' - uniqueid:'.$this->uniqueid.' - '.$buffer_debug." ::> ".$level);
		}
		
		// LOG INTO FILE
		if ($this->agiconfig['logging_level'] >= $level) {
			$this -> write_log ($buffer_debug, 1, "[file:$file - line:$line - uniqueid:".$this->uniqueid."]:");
		}
	}

	/*
	 * Write log into file
	 */
	function write_log($output, $tobuffer = 1, $line_file_info = '')
	{
		//$tobuffer = 0;

		if (strlen($this->log_file) > 1) {
			$string_log = "[".date("d/m/Y H:i:s")."]:".$line_file_info."[CallerID:".$this->CallerID."]:[CN:".$this->cardnumber."]:[$output]\n";
			if ($this->CC_TESTING) echo $string_log;

			$this -> BUFFER .= $string_log;
			if (!$tobuffer || $this->CC_TESTING) {
				error_log ($this -> BUFFER, 3, $this->log_file);
				$this-> BUFFER = '';
			}
		}
	}

	/*
	 * set the DB handler
	 */
	function set_dbhandler ($DBHandle)
	{
		$this->DBHandle	= $DBHandle;
	}

	/*
	 * set_instance_table
	 */
	function set_instance_table ($instance_table)
	{
		$this->instance_table = $instance_table;
	}

	/*
	 * load_conf
	 */
	function load_conf( &$agi, $config=NULL, $webui=0, $idconfig=1, $optconfig=array())
    {
		$this -> idconfig = $idconfig;
		// load config
		if(!is_null($config) && file_exists($config)) {
			$this->config = parse_ini_file($config, true);
		} elseif(file_exists(DEFAULT_A2BILLING_CONFIG)) {
			$this->config = parse_ini_file(DEFAULT_A2BILLING_CONFIG, true);
		} else {
			echo "Error : A2Billing configuration file is missing!";
			exit;
		}

	  	/*  We don't need to do this twice.  load_conf_db() will do it
		// If optconfig is specified, stuff vals and vars into 'a2billing' config array.
		foreach($optconfig as $var=>$val) {
			$this->config["agi-conf$idconfig"][$var] = $val;
		}*/

		// conf for the database connection
		if(!isset($this->config['database']['hostname']))	$this->config['database']['hostname'] = 'localhost';
		if(!isset($this->config['database']['port']))		$this->config['database']['port'] = '5432';
		if(!isset($this->config['database']['user']))		$this->config['database']['user'] = 'postgres';
		if(!isset($this->config['database']['password']))	$this->config['database']['password'] = '';
		if(!isset($this->config['database']['dbname']))		$this->config['database']['dbname'] = 'a2billing';
		if(!isset($this->config['database']['dbtype']))		$this->config['database']['dbtype'] = 'postgres';

		return $this->load_conf_db($agi, NULL, 0, $idconfig, $optconfig);
    }

	/*
	 * Load config from Database
	 */
	function load_conf_db( &$agi, $config=NULL, $webui=0, $idconfig=1, $optconfig=array())
    {
		$this -> idconfig = $idconfig;
		// load config
		$config_table = new Table("cc_config", "config_key as cfgkey, config_value as cfgvalue, config_group_title as cfggname, config_valuetype as cfgtype");
		$this->DbConnect();

		$config_res = $config_table -> Get_list($this->DBHandle, "");
		if (!$config_res) {
			echo 'Error : cannot load conf : load_conf_db';
			return false;
		}

		foreach ($config_res as $conf)
		{
			// FOR DEBUG
			/*if ($conf['cfgkey'] == 'sip_iax_pstn_direct_call_prefix') {
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "\n\n conf :".$conf['cfgkey']);
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "\n\n conf :".$conf['cfgvalue']);
			}*/
			if($conf['cfgtype'] == 0) // if its type is text
			{
				$this->config[$conf['cfggname']][$conf['cfgkey']] = $conf['cfgvalue'];
			}
			elseif($conf['cfgtype'] == 1) // if its type is boolean
			{
				if(strtoupper($conf['cfgvalue']) == "YES" || $conf['cfgvalue'] == 1 || strtoupper($conf['cfgvalue']) == "TRUE") // if equal to 'yes'
				{
					$this->config[$conf['cfggname']][$conf['cfgkey']] = 1;
				}
				else // if equal to 'no'
				{
					$this->config[$conf['cfggname']][$conf['cfgkey']] = 0;
				}
			}
		}
		$this->DbDisconnect($this->DBHandle);

		// If optconfig is specified, stuff vals and vars into 'a2billing' config array.
		foreach($optconfig as $var=>$val)
		{
			$this->config["agi-conf$idconfig"][$var] = $val;
		}

		// add default values to config for uninitialized values
		//Card Number Length Code
		$card_length_range = isset($this->config['global']['interval_len_cardnumber'])?$this->config['global']['interval_len_cardnumber']:null;
		$this -> cardnumber_range = $this -> splitable_data ($card_length_range);

		if(is_array($this -> cardnumber_range) && ($this -> cardnumber_range[0] >= 4))
		{
			define ("CARDNUMBER_LENGTH_MIN", $this -> cardnumber_range[0]);
			define ("CARDNUMBER_LENGTH_MAX", $this -> cardnumber_range[count($this -> cardnumber_range)-1]);
			define ("LEN_CARDNUMBER", CARDNUMBER_LENGTH_MIN);
		}
		else
		{
			echo gettext("Invalid card number lenght defined in configuration.");
			exit;
		}
		if(!isset($this->config['global']['len_aliasnumber']))		$this->config['global']['len_aliasnumber'] = 15;
		if(!isset($this->config['global']['len_voucher']))			$this->config['global']['len_voucher'] = 15;
		if(!isset($this->config['global']['base_currency'])) 		$this->config['global']['base_currency'] = 'usd';
		if(!isset($this->config['global']['didbilling_daytopay'])) 	$this->config['global']['didbilling_daytopay'] = 5;
		if(!isset($this->config['global']['admin_email'])) 			$this->config['global']['admin_email'] = 'root@localhost';

				// Conf for the Callback
		if(!isset($this->config['callback']['context_callback']))	$this->config['callback']['context_callback'] = 'a2billing-callback';
		if(!isset($this->config['callback']['ani_callback_delay']))	$this->config['callback']['ani_callback_delay'] = '10';
		if(!isset($this->config['callback']['extension']))		$this->config['callback']['extension'] = '1000';
		if(!isset($this->config['callback']['sec_avoid_repeate']))	$this->config['callback']['sec_avoid_repeate'] = '30';
		if(!isset($this->config['callback']['timeout']))		$this->config['callback']['timeout'] = '20';
		if(!isset($this->config['callback']['answer_call']))		$this->config['callback']['answer_call'] = '1';
		if(!isset($this->config['callback']['nb_predictive_call']))	$this->config['callback']['nb_predictive_call'] = '10';
		if(!isset($this->config['callback']['nb_day_wait_before_retry']))	$this->config['callback']['nb_day_wait_before_retry'] = '1';
		if(!isset($this->config['callback']['context_preditctivedialer']))	$this->config['callback']['context_preditctivedialer'] = 'a2billing-predictivedialer';
		if(!isset($this->config['callback']['predictivedialer_maxtime_tocall']))	$this->config['callback']['predictivedialer_maxtime_tocall'] = '5400';
		if(!isset($this->config['callback']['sec_wait_before_callback']))	$this->config['callback']['sec_wait_before_callback'] = '10';

		// Conf for the signup
		if(!isset($this->config['signup']['enable_signup']))$this->config['signup']['enable_signup'] = '1';
		if(!isset($this->config['signup']['credit']))		$this->config['signup']['credit'] = '0';
		if(!isset($this->config['signup']['tariff']))		$this->config['signup']['tariff'] = '8';
		if(!isset($this->config['signup']['activated']))	$this->config['signup']['activated'] = 't';
		if(!isset($this->config['signup']['simultaccess']))	$this->config['signup']['simultaccess'] = '0';
		if(!isset($this->config['signup']['typepaid']))		$this->config['signup']['typepaid'] = '0';
		if(!isset($this->config['signup']['creditlimit']))	$this->config['signup']['creditlimit'] = '0';
		if(!isset($this->config['signup']['runservice']))	$this->config['signup']['runservice'] = '0';
		if(!isset($this->config['signup']['enableexpire']))	$this->config['signup']['enableexpire'] = '0';
		if(!isset($this->config['signup']['expiredays']))	$this->config['signup']['expiredays'] = '0';

		// Conf for Paypal
		if(!isset($this->config['paypal']['item_name']))	$this->config['paypal']['item_name'] = 'Credit Purchase';
		if(!isset($this->config['paypal']['currency_code']))	$this->config['paypal']['currency_code'] = 'USD';
		if(!isset($this->config['paypal']['purchase_amount']))	$this->config['paypal']['purchase_amount'] = '5;10;15';
		if(!isset($this->config['paypal']['paypal_fees']))   $this->config['paypal']['paypal_fees'] = '1';

		// Conf for Backup
		if(!isset($this->config['backup']['backup_path']))	$this->config['backup']['backup_path'] ='/tmp';
		if(!isset($this->config['backup']['gzip_exe']))		$this->config['backup']['gzip_exe'] ='/bin/gzip';
		if(!isset($this->config['backup']['gunzip_exe']))	$this->config['backup']['gunzip_exe'] ='/bin/gunzip';
		if(!isset($this->config['backup']['mysqldump']))	$this->config['backup']['mysqldump'] ='/usr/bin/mysqldump';
		if(!isset($this->config['backup']['pg_dump']))		$this->config['backup']['pg_dump'] ='/usr/bin/pg_dump';
		if(!isset($this->config['backup']['mysql']))		$this->config['backup']['mysql'] ='/usr/bin/mysql';
		if(!isset($this->config['backup']['psql']))		$this->config['backup']['psql'] ='/usr/bin/psql';
		if(!isset($this->config['backup']['archive_data_x_month']))		$this->config['backup']['archive_data_x_month'] ='3';

		// Conf for Customer Web UI
		if(!isset($this->config['webcustomerui']['customerinfo']))	$this->config['webcustomerui']['customerinfo'] = '1';
		if(!isset($this->config['webcustomerui']['personalinfo']))	$this->config['webcustomerui']['personalinfo'] = '1';
		if(!isset($this->config['webcustomerui']['limit_callerid']))	$this->config['webcustomerui']['limit_callerid'] = '5';
		if(!isset($this->config['webcustomerui']['error_email']))	$this->config['webcustomerui']['error_email'] = 'root@localhost';
		// conf for the web ui
		if(!isset($this->config['webui']['buddy_sip_file']))		$this->config['webui']['buddy_sip_file'] = '/etc/asterisk/additional_a2billing_sip.conf';
		if(!isset($this->config['webui']['buddy_iax_file']))		$this->config['webui']['buddy_iax_file'] = '/etc/asterisk/additional_a2billing_iax.conf';
		if(!isset($this->config['webui']['api_logfile']))		$this->config['webui']['api_logfile'] = '/tmp/api_ecommerce_request.log';
		if(isset($this->config['webui']['api_ip_auth']))		$this->config['webui']['api_ip_auth'] = explode(";", $this->config['webui']['api_ip_auth']);

		if(!isset($this->config['webui']['dir_store_mohmp3']))		$this->config['webui']['dir_store_mohmp3'] = '/var/lib/asterisk/mohmp3';
		if(!isset($this->config['webui']['num_musiconhold_class']))	$this->config['webui']['num_musiconhold_class'] = 10;
		if(!isset($this->config['webui']['show_help']))			$this->config['webui']['show_help'] = 1;
		if(!isset($this->config['webui']['my_max_file_size_import']))	$this->config['webui']['my_max_file_size_import'] = 1024000;
		if(!isset($this->config['webui']['dir_store_audio']))		$this->config['webui']['dir_store_audio'] = '/var/lib/asterisk/sounds/a2billing';
		if(!isset($this->config['webui']['my_max_file_size_audio']))	$this->config['webui']['my_max_file_size_audio'] = 3072000;

		if(isset($this->config['webui']['file_ext_allow']))		$this->config['webui']['file_ext_allow'] = explode(",", $this->config['webui']['file_ext_allow']);
		else $this->config['webui']['file_ext_allow'] = explode(",", "gsm, mp3, wav");

		if(isset($this->config['webui']['file_ext_allow_musiconhold']))	$this->config['webui']['file_ext_allow_musiconhold'] = explode(",", $this->config['webui']['file_ext_allow_musiconhold']);
		else $this->config['webui']['file_ext_allow_musiconhold'] = explode(",", "mp3");

		if(!isset($this->config['webui']['show_top_frame'])) 		$this->config['webui']['show_top_frame'] = 1;
		if(!isset($this->config['webui']['currency_choose'])) 		$this->config['webui']['currency_choose'] = 'all';
		if(!isset($this->config['webui']['card_export_field_list']))	$this->config['webui']['card_export_field_list'] = 'creationdate, username, credit, lastname, firstname';
		if(!isset($this->config['webui']['rate_export_field_list']))    $this->config['webui']['rate_export_field_list'] = 'dest_name, dialprefix, rateinitial';
		if(!isset($this->config['webui']['voucher_export_field_list']))	$this->config['webui']['voucher_export_field_list'] = 'id, voucher, credit, tag, activated, usedcardnumber, usedate, currency';
		if(!isset($this->config['webui']['advanced_mode']))				$this->config['webui']['advanced_mode'] = 0;
		if(!isset($this->config['webui']['delete_fk_card']))			$this->config['webui']['delete_fk_card'] = 1;

		// conf for the recurring process
		if(!isset($this->config["recprocess"]['batch_log_file'])) 	$this->config["recprocess"]['batch_log_file'] = '/tmp/batch-a2billing.log';

		// conf for the peer_friend
		if(!isset($this->config['peer_friend']['type'])) 		$this->config['peer_friend']['type'] = 'friend';
		if(!isset($this->config['peer_friend']['allow'])) 		$this->config['peer_friend']['allow'] = 'ulaw,alaw,gsm,g729';
		if(!isset($this->config['peer_friend']['context'])) 	$this->config['peer_friend']['context'] = 'a2billing';
		if(!isset($this->config['peer_friend']['nat'])) 		$this->config['peer_friend']['nat'] = 'yes';
		if(!isset($this->config['peer_friend']['amaflags'])) 	$this->config['peer_friend']['amaflags'] = 'billing';
		if(!isset($this->config['peer_friend']['qualify'])) 	$this->config['peer_friend']['qualify'] = 'yes';
		if(!isset($this->config['peer_friend']['host'])) 		$this->config['peer_friend']['host'] = 'dynamic';
		if(!isset($this->config['peer_friend']['dtmfmode'])) 	$this->config['peer_friend']['dtmfmode'] = 'RFC2833';
		if(!isset($this->config['peer_friend']['use_realtime'])) 	$this->config['peer_friend']['use_realtime'] = '0';


		//conf for the notifications
		if(!isset($this->config['notifications']['values_notifications'])) $this->config['notifications']['values_notifications'] = '0';
		if(!isset($this->config['notifications']['cron_notifications'])) $this->config['notifications']['cron_notifications'] = '1';
		if(!isset($this->config['notifications']['delay_notifications'])) $this->config['notifications']['delay_notifications'] = '1';

		// conf for the log-files
		if(isset($this->config['log-files']['agi']) && strlen ($this->config['log-files']['agi']) > 1)
		{
			$this -> log_file = $this -> config['log-files']['agi'];
		}
		define ("LOGFILE_CRONT_ALARM", 			isset($this->config['log-files']['cront_alarm'])			?$this->config['log-files']['cront_alarm']:null);
		define ("LOGFILE_CRONT_AUTOREFILL", 	isset($this->config['log-files']['cront_autorefill'])		?$this->config['log-files']['cront_autorefill']:null);
		define ("LOGFILE_CRONT_BATCH_PROCESS", 	isset($this->config['log-files']['cront_batch_process'])	?$this->config['log-files']['cront_batch_process']:null);
		define ("LOGFILE_CRONT_ARCHIVE_DATA", 	isset($this->config['log-files']['cront_archive_data'])	?$this->config['log-files']['cront_archive_data']:null);
		define ("LOGFILE_CRONT_BILL_DIDUSE", 	isset($this->config['log-files']['cront_bill_diduse'])		?$this->config['log-files']['cront_bill_diduse']:null);
		define ("LOGFILE_CRONT_SUBSCRIPTIONFEE",isset($this->config['log-files']['cront_subscriptionfee'])	?$this->config['log-files']['cront_subscriptionfee']:null);
		define ("LOGFILE_CRONT_CURRENCY_UPDATE",isset($this->config['log-files']['cront_currency_update'])	?$this->config['log-files']['cront_currency_update']:null);
		define ("LOGFILE_CRONT_INVOICE",		isset($this->config['log-files']['cront_invoice'])			?$this->config['log-files']['cront_invoice']:null);
		define ("LOGFILE_CRONT_CHECKACCOUNT",	isset($this->config['log-files']['cront_check_account'])	?$this->config['log-files']['cront_check_account']:null);

		define ("LOGFILE_API_ECOMMERCE", 		isset($this->config['log-files']['api_ecommerce'])			?$this->config['log-files']['api_ecommerce']:null);
		define ("LOGFILE_API_CALLBACK", 		isset($this->config['log-files']['api_callback'])			?$this->config['log-files']['api_callback']:null);
		define ("LOGFILE_PAYPAL", 				isset($this->config['log-files']['paypal'])					?$this->config['log-files']['paypal']:null);
		define ("LOGFILE_EPAYMENT", 			isset($this->config['log-files']['epayment'])				?$this->config['log-files']['epayment']:null);


		// conf for the AGI
		if(!isset($this->config["agi-conf$idconfig"]['play_audio'])) 	$this->config["agi-conf$idconfig"]['play_audio'] = 1;
		define ("PLAY_AUDIO", 											$this->config["agi-conf$idconfig"]['play_audio']);

		if(!isset($this->config["agi-conf$idconfig"]['verbosity_level'])) 	$this->config["agi-conf$idconfig"]['verbosity_level'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['logging_level'])) 	$this->config["agi-conf$idconfig"]['logging_level'] = 3;
		
		if(!isset($this->config["agi-conf$idconfig"]['logger_enable'])) $this->config["agi-conf$idconfig"]['logger_enable'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['log_file'])) $this->config["agi-conf$idconfig"]['log_file'] = '/var/log/a2billing/a2billing.log';

		if(!isset($this->config["agi-conf$idconfig"]['answer_call'])) $this->config["agi-conf$idconfig"]['answer_call'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['auto_setcallerid'])) $this->config["agi-conf$idconfig"]['auto_setcallerid'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['say_goodbye'])) $this->config["agi-conf$idconfig"]['say_goodbye'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['play_menulanguage'])) $this->config["agi-conf$idconfig"]['play_menulanguage'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['force_language'])) $this->config["agi-conf$idconfig"]['force_language'] = 'EN';
		if(!isset($this->config["agi-conf$idconfig"]['min_credit_2call'])) $this->config["agi-conf$idconfig"]['min_credit_2call'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['min_duration_2bill'])) $this->config["agi-conf$idconfig"]['min_duration_2bill'] = 0;

		if(!isset($this->config["agi-conf$idconfig"]['use_dnid'])) $this->config["agi-conf$idconfig"]['use_dnid'] = 0;
		// Explode the no_auth_dnid string
		if(isset($this->config["agi-conf$idconfig"]['no_auth_dnid'])) $this->config["agi-conf$idconfig"]['no_auth_dnid'] = explode(",",$this->config["agi-conf$idconfig"]['no_auth_dnid']);

		// Explode the international_prefixes, extracharge_did and extracharge_fee strings
		if(isset($this->config["agi-conf$idconfig"]['extracharge_did'])) $this->config["agi-conf$idconfig"]['extracharge_did'] = explode(",",$this->config["agi-conf$idconfig"]['extracharge_did']);
		if(isset($this->config["agi-conf$idconfig"]['extracharge_fee'])) $this->config["agi-conf$idconfig"]['extracharge_fee'] = explode(",",$this->config["agi-conf$idconfig"]['extracharge_fee']);
		if(isset($this->config["agi-conf$idconfig"]['extracharge_buyfee'])) $this->config["agi-conf$idconfig"]['extracharge_buyfee'] = explode(",",$this->config["agi-conf$idconfig"]['extracharge_buyfee']);

		if(isset($this->config["agi-conf$idconfig"]['international_prefixes'])) {
			$this->config["agi-conf$idconfig"]['international_prefixes'] = explode(",",$this->config["agi-conf$idconfig"]['international_prefixes']);
		} else {
			// to retain config file compatibility assume a default unless config option is set
			$this->config["agi-conf$idconfig"]['international_prefixes'] = explode(",","011,09,00,1");
		}



		if(!isset($this->config["agi-conf$idconfig"]['number_try'])) $this->config["agi-conf$idconfig"]['number_try'] = 3;
		if(!isset($this->config["agi-conf$idconfig"]['say_balance_after_auth'])) $this->config["agi-conf$idconfig"]['say_balance_after_auth'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['say_balance_after_call'])) $this->config["agi-conf$idconfig"]['say_balance_after_call'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['say_rateinitial'])) $this->config["agi-conf$idconfig"]['say_rateinitial'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['say_timetocall'])) $this->config["agi-conf$idconfig"]['say_timetocall'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['cid_enable'])) $this->config["agi-conf$idconfig"]['cid_enable'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['cid_sanitize'])) $this->config["agi-conf$idconfig"]['cid_sanitize'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['cid_askpincode_ifnot_callerid'])) $this->config["agi-conf$idconfig"]['cid_askpincode_ifnot_callerid'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['cid_auto_assign_card_to_cid'])) $this->config["agi-conf$idconfig"]['cid_auto_assign_card_to_cid'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['notenoughcredit_cardnumber'])) $this->config["agi-conf$idconfig"]['notenoughcredit_cardnumber'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['notenoughcredit_assign_newcardnumber_cid'])) $this->config["agi-conf$idconfig"]['notenoughcredit_assign_newcardnumber_cid'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['maxtime_tocall_negatif_free_route'])) $this->config["agi-conf$idconfig"]['maxtime_tocall_negatif_free_route'] = 1800;
		if(!isset($this->config["agi-conf$idconfig"]['callerid_authentication_over_cardnumber'])) $this->config["agi-conf$idconfig"]['callerid_authentication_over_cardnumber'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['cid_auto_create_card_len'])) $this->config["agi-conf$idconfig"]['cid_auto_create_card_len'] = 10;

		if(!isset($this->config["agi-conf$idconfig"]['sip_iax_friends'])) $this->config["agi-conf$idconfig"]['sip_iax_friends'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['sip_iax_pstn_direct_call'])) $this->config["agi-conf$idconfig"]['sip_iax_pstn_direct_call'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['dialcommand_param'])) $this->config["agi-conf$idconfig"]['dialcommand_param'] = '|30|HL(%timeout%:61000:30000)';
		if(!isset($this->config["agi-conf$idconfig"]['dialcommand_param_sipiax_friend'])) $this->config["agi-conf$idconfig"]['dialcommand_param_sipiax_friend'] = '|30|HL(3600000:61000:30000)';
		if(!isset($this->config["agi-conf$idconfig"]['switchdialcommand'])) $this->config["agi-conf$idconfig"]['switchdialcommand'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['failover_recursive_limit'])) $this->config["agi-conf$idconfig"]['failover_recursive_limit'] = 1;
		if(!isset($this->config["agi-conf$idconfig"]['record_call'])) $this->config["agi-conf$idconfig"]['record_call'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['monitor_formatfile'])) $this->config["agi-conf$idconfig"]['monitor_formatfile'] = 'gsm';
		
		if(!isset($this->config["agi-conf$idconfig"]['currency_association']))	$this->config["agi-conf$idconfig"]['currency_association'] = 'all:credit';
		$this->config["agi-conf$idconfig"]['currency_association'] = explode(",",$this->config["agi-conf$idconfig"]['currency_association']);
		foreach($this->config["agi-conf$idconfig"]['currency_association'] as $cur_val) {
			$cur_val = explode(":",$cur_val);
			$this->config["agi-conf$idconfig"]['currency_association_internal'][$cur_val[0]]=$cur_val[1];
		}
		
		//if(!isset($this->config["agi-conf$idconfig"]['currency_cents_association']))	$this->config["agi-conf$idconfig"]['currency_cents_association'] = '';
		if(isset($this->config["agi-conf$idconfig"]['currency_cents_association']) && strlen($this->config["agi-conf$idconfig"]['currency_cents_association']) > 0) {
			$this->config["agi-conf$idconfig"]['currency_cents_association'] = explode(",",$this->config["agi-conf$idconfig"]['currency_cents_association']);
			foreach($this->config["agi-conf$idconfig"]['currency_cents_association'] as $cur_val) {
				$cur_val = explode(":",$cur_val);
				$this->config["agi-conf$idconfig"]['currency_cents_association_internal'][$cur_val[0]]=$cur_val[1];
			}
		}
		if(!isset($this->config["agi-conf$idconfig"]['file_conf_enter_destination']))	$this->config["agi-conf$idconfig"]['file_conf_enter_destination'] = 'prepaid-enter-number-u-calling-1-or-011';
		if(!isset($this->config["agi-conf$idconfig"]['file_conf_enter_menulang']))	$this->config["agi-conf$idconfig"]['file_conf_enter_menulang'] = 'prepaid-menulang';
		if(!isset($this->config["agi-conf$idconfig"]['send_reminder'])) $this->config["agi-conf$idconfig"]['send_reminder'] = 0;
		if(isset($this->config["agi-conf$idconfig"]['debugshell']) && $this->config["agi-conf$idconfig"]['debugshell'] == 1 && isset($agi)) $agi->nlinetoread = 0;

		if(!isset($this->config["agi-conf$idconfig"]['ivr_voucher'])) $this->config["agi-conf$idconfig"]['ivr_voucher'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['ivr_voucher_prefixe'])) $this->config["agi-conf$idconfig"]['ivr_voucher_prefixe'] = 8;
		if(!isset($this->config["agi-conf$idconfig"]['jump_voucher_if_min_credit'])) $this->config["agi-conf$idconfig"]['jump_voucher_if_min_credit'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['failover_lc_prefix'])) $this->config["agi-conf$idconfig"]['failover_lc_prefix'] = 0;
		if(!isset($this->config["agi-conf$idconfig"]['cheat_on_announcement_time'])) $this->config["agi-conf$idconfig"]['cheat_on_announcement_time'] = 0;

		// Define the agiconfig property
		$this->agiconfig = $this->config["agi-conf$idconfig"];

		// Print out on CLI for debug purpose
		if (!$webui) $this -> debug( DEBUG, $agi, __FILE__, __LINE__, 'A2Billing AGI internal configuration:');
		if (!$webui) $this -> debug( DEBUG, $agi, __FILE__, __LINE__, print_r($this->agiconfig, true));
		
		return true;
    }


	/**
    * Log to console if debug mode.
    *
    * @example examples/ping.php Ping an IP address
    *
    * @param string $str
    * @param integer $vbl verbose level
    */
    function conlog($str, $vbl=1)
    {
		global $agi;
		static $busy = false;

		if($this->agiconfig['debug'] != false)
		{
			if(!$busy) // no conlogs inside conlog!!!
			{
			  $busy = true;
			  if (isset($agi)) $agi->verbose($str, $vbl);
			  $busy = false;
			}
		}
    }

	/*
	 * Function to create a menu to select the language
	 */
	function play_menulanguage ($agi)
	{
		// MENU LANGUAGE
		if ($this->agiconfig['play_menulanguage']==1){
			
			$list_prompt_menulang = explode(':',$this->agiconfig['conf_order_menulang']);
			$i=1;
			foreach ($list_prompt_menulang as $lg_value ){
				$res_dtmf = $agi->get_data("menu_".$lg_value, 500, 1);
				if(!empty($res_dtmf["result"]) && is_numeric($res_dtmf["result"])&& $res_dtmf["result"]>0)break;
				
				if($i==sizeof($list_prompt_menulang)) {$res_dtmf = $agi->get_data("num_".$lg_value."_".$i,3000, 1);}
				else {$res_dtmf = $agi->get_data("num_".$lg_value."_".$i,1000, 1);}
				
				if(!empty($res_dtmf["result"]) && is_numeric($res_dtmf["result"]) && $res_dtmf["result"]>0 )break;
				$i++;
			}
			
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES Menu Language DTMF : ".$res_dtmf ["result"]);

			$this -> languageselected = $res_dtmf ["result"];
			
			if($this -> languageselected>0 && $this -> languageselected<=sizeof($list_prompt_menulang) ){
				$language = $list_prompt_menulang[$this -> languageselected-1];
			}else{
				if (strlen($this->agiconfig['force_language'])==2) {
					$language = strtolower($this->agiconfig['force_language']);
				} else {
					$language = 'en';
				}
				
			}

            $this ->current_language = $language;  
            
            $this -> debug( DEBUG, $agi, __FILE__, __LINE__, " CURRENT LANGUAGE : ".$language);
            
            
			if($this->agiconfig['asterisk_version'] == "1_2") {
				$lg_var_set = 'LANGUAGE()';
			} else {
				$lg_var_set = 'CHANNEL(language)';
			}
			$agi -> set_variable($lg_var_set, $language);
			$this -> debug( INFO, $agi, __FILE__, __LINE__, "[SET $lg_var_set $language]");
			$this->languageselected = 1;

		} elseif (strlen($this->agiconfig['force_language'])==2) {

			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "FORCE LANGUAGE : ".$this->agiconfig['force_language']);
			$this->languageselected = 1;
			$language = strtolower($this->agiconfig['force_language']);
			$this ->current_language = $language;
			if($this->agiconfig['asterisk_version'] == "1_2") {
				$lg_var_set = 'LANGUAGE()';
			} else {
				$lg_var_set = 'CHANNEL(language)';
			}
			$agi -> set_variable($lg_var_set, $language);
			$this -> debug( INFO, $agi, __FILE__, __LINE__, "[SET $lg_var_set $language]");
		}
	}



	/*
	 * intialize evironement variables from the agi values
	 */
	function get_agi_request_parameter($agi)
	{
		$this->CallerID 	= $agi->request['agi_callerid'];
		$this->channel		= $agi->request['agi_channel'];
		$this->uniqueid		= $agi->request['agi_uniqueid'];
		$this->accountcode	= $agi->request['agi_accountcode'];
		//$this->dnid		= $agi->request['agi_dnid'];
		$this->dnid			= $agi->request['agi_extension'];
		
		//Call function to find the cid number
		$this -> isolate_cid();

		$this -> debug( INFO, $agi, __FILE__, __LINE__, ' get_agi_request_parameter = '.$this->CallerID.' ; '.$this->channel.' ; '.$this->uniqueid.' ; '.$this->accountcode.' ; '.$this->dnid);
	}



	/*
	 *	function to find the cid number
	 */
	function isolate_cid()
	{
		$pos_lt = strpos($this->CallerID, '<');
		$pos_gt = strpos($this->CallerID, '>');

		if (($pos_lt !== false) && ($pos_gt !== false)) {
			$len_gt = $pos_gt - $pos_lt - 1;
			$this->CallerID = substr($this->CallerID,$pos_lt+1,$len_gt);
		}
		
		$this->CallerID = str_replace("+", '', $this->CallerID);
	}


	/*
	 *	function would set when the card is used or when it release
	 */
	function callingcard_acct_start_inuse($agi, $inuse)
	{
		$upd_balance = 0;
		if (is_numeric($this->agiconfig['dial_balance_reservation'])) {
			$upd_balance = $this->agiconfig['dial_balance_reservation'];	
		}
			
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CARD STATUS UPDATE]");
		if ($inuse) {
			$QUERY = "UPDATE cc_card SET inuse=inuse+1, credit=credit-$upd_balance WHERE username='".$this->username."'";
			$this -> set_inuse = 1;
		} else {
			$QUERY = "UPDATE cc_card SET inuse=inuse-1, credit=credit+$upd_balance WHERE username='".$this->username."'";
			$this -> set_inuse = 0;
		}
		if (!$this -> CC_TESTING) $result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);

		return 0;
	}


	function enough_credit_to_call(){
		if($this->credit < $this->agiconfig['min_credit_2call'] && $A2B -> typepaid==0){
			$QUERY = "SELECT id_cc_package_offer FROM cc_tariffgroup WHERE id= ".$this->tariff ;
			$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
				if( !empty($result[0][0])){
					$id_package_groupe = $result[0][0];
					if($id_package_groupe >0){
						return true;
					}else return false;
				}else return false;
		}else return true;
	}

	/**
	 *	Function callingcard_ivr_authorize : check the dialed/dialing number and play the time to call
	 *
	 *  @param object $agi
     *  @param float $credit
     *  @return 1 if Ok ; -1 if error
	**/
	function callingcard_ivr_authorize($agi, &$RateEngine, $try_num)
	{
		$res=0;

		/************** 	ASK DESTINATION 	******************/
		$prompt_enter_dest = $this->agiconfig['file_conf_enter_destination'];

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__,  $this->agiconfig['use_dnid']." && ".in_array ($this->dnid, $this->agiconfig['no_auth_dnid'])." && ".strlen($this->dnid)."&& $try_num");

		// CHECK IF USE_DNID IF NOT GET THE DESTINATION NUMBER
		if ($this->agiconfig['use_dnid']==1 && !in_array ($this->dnid, $this->agiconfig['no_auth_dnid']) && strlen($this->dnid)>=1 && $try_num==0) {
			$this->destination = $this->dnid;
		} else {
			$res_dtmf = $agi->get_data($prompt_enter_dest, 6000, 20);
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES DTMF : ".$res_dtmf ["result"]);
			$this->destination = $res_dtmf ["result"];
		}
		
		
		//TEST if this card is restricted !
		if($this->restriction == 1 || $this->restriction == 2 ) {
			
			$QUERY = "SELECT * FROM cc_restricted_phonenumber WHERE number='".$this->destination."'";
			if ($this->removeinterprefix) { 
				$QUERY .= " OR number='". $this -> apply_rules ($this->destination)."'";
			}
			$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);

			if ($this->restriction == 1) {
				//CAN'T CALL RESTRICTED NUMBER	
				if(is_array($result)) {
					//NUMBER NOT AUHTORIZED
					$agi-> stream_file('prepaid-not-authorized-phonenumber', '#');
					return -1;
				}
			} else {
				//CAN ONLY CALL RESTRICTED NUMBER		
				if(!is_array($result)) {
			  		//NUMBER NOT AUHTORIZED
			  		$agi-> stream_file('prepaid-not-authorized-phonenumber', '#');
			  		return -1;
				}
			}
			
		}
		
		//REDIAL FIND THE LAST DIALED NUMBER (STORED IN THE DATABASE)
		if (strlen($this->destination)<=2 && is_numeric($this->destination) && $this->destination>=0) {
			$QUERY = "SELECT phone FROM cc_speeddial WHERE id_cc_card='".$this->id_card."' AND speeddial='".$this->destination."'";
			$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
			if( is_array($result))	$this->destination = $result[0][0];
			$this -> debug( INFO, $agi, __FILE__, __LINE__, "REDIAL : DESTINATION ::> ".$this->destination);
		}
		
		// FOR TESTING : ENABLE THE DESTINATION NUMBER
		if ($this->CC_TESTING) $this->destination="1800300200";
		
		$this -> debug( INFO, $agi, __FILE__, __LINE__, "DESTINATION ::> ".$this->destination);
		if ($this->removeinterprefix) $this->destination = $this -> apply_rules ($this->destination);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RULES APPLY ON DESTINATION ::> ".$this->destination);

		// TRIM THE "#"s IN THE END, IF ANY
		// usefull for SIP or IAX friends with "use_dnid" when their device sends also the "#"
		// it should be safe for normal use
		$this->destination = rtrim($this->destination, "#");
		
		// SAY BALANCE AND FT2C PACKAGE IF APPLICABLE
		// this is hardcoded for now but we might have a setting in a2billing.conf for the combination
		if ($this->destination=='*0') {
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SAY BALANCE ::> ".$this->credit."]");
			$this -> fct_say_balance ($agi, $this->credit);

			// Retrieve this customer's FT2C package details
			$QUERY = "SELECT freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer FROM cc_card RIGHT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff RIGHT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer WHERE cc_card.id='".$this->id_card."'";
			$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
			if (is_array($result) && ($result[0][0] > 0) ) {
				$freetime = $result[0][0];
				$label = $result[0][1];
				$packagetype = $result[0][2];
				$billingtype = $result[0][3];
				$startday = $result[0][4];
				$id_cc_package_offer = $result[0][5];
				$freetimetocall_used = $this->FT2C_used_seconds($this->DBHandle, $this->id_card, $id_cc_package_offer, $billingtype, $startday);
				
				//TO MANAGE BY PACKAGE TYPE IT -> only for freetime
				if (($packagetype == 0) || ($packagetype == 1)) {
					$minutes=intval(($freetime-$freetimetocall_used)/60);
					$seconds=($freetime-$freetimetocall_used) % 60;
				} else {
					$minutes=intval($freetimetocall_used/60);
					$seconds=$freetimetocall_used % 60;
				}
				// Now say either "You have X minutes and Y seconds of free package calls remaining this week/month"
				// or "You have dialed X minutes and Y seconds of free package calls this week/month"
				if (($packagetype == 0) || ($packagetype == 1)) {
					$agi-> stream_file('prepaid-you-have', '#');
				} else {
					$agi-> stream_file('prepaid-you-have-dialed', '#');
				}
				if (($minutes > 0) || ($seconds == 0)) {
					if ($minutes==1){
						if((strtolower($this ->current_language)=='ru')){
							$agi-> stream_file('digits/1f', '#');
						}else{
							$agi->say_number($minutes);
						}
						$agi-> stream_file('prepaid-minute', '#');
					}else{
						$agi->say_number($minutes);
						if((strtolower($this ->current_language)=='ru')&& ( ( $minutes%10==2) || ($minutes%10==3 )|| ($minutes%10==4)) ){
							// test for the specific grammatical rules in RUssian
							$agi-> stream_file('prepaid-minute2', '#');
						}else{
							$agi-> stream_file('prepaid-minutes', '#');
						}
					}
				}
				if ($seconds > 0) {
					if ($minutes > 0) $agi-> stream_file('vm-and', '#');
					if ($seconds == 1) {
						if((strtolower($this ->current_language)=='ru')) {
							$agi-> stream_file('digits/1f', '#');
						}else{
							$agi->say_number($seconds);
						}
						$agi-> stream_file('prepaid-second', '#');
					} else {
						$agi->say_number($seconds);
						if((strtolower($this ->current_language)=='ru')&& ( ( $seconds%10==2) || ($seconds%10==3 )|| ($seconds%10==4)) ){
							// test for the specific grammatical rules in RUssian
							$agi-> stream_file('prepaid-second2', '#');
						} else {
							$agi-> stream_file('prepaid-seconds', '#');
						}
					}
				}
				$agi-> stream_file('prepaid-of-free-package-calls', '#');
				if (($packagetype == 0) || ($packagetype == 1)) {
					$agi-> stream_file('prepaid-remaining', '#');
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SAY FT2C REMAINING ::> ".$minutes.":".$seconds."]");
				} else {
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SAY FT2C USED ::> ".$minutes.":".$seconds."]");
				}
				$agi-> stream_file('this', '#');
				if ($billingtype == 0) {
					$agi-> stream_file('month', '#');
				} else {
					$agi-> stream_file('weeks', '#');
				}
			}
			return -1;
		}

		//REDIAL FIND THE LAST DIALED NUMBER (STORED IN THE DATABASE)
		if ( $this->destination=='0*' ){
			$this->destination = $this->redial;
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[REDIAL : DTMF DESTINATION ::> ".$this->destination."]");
		}

		if ($this->destination<=0){
			$prompt = "prepaid-invalid-digits";
			// do not play the error message if the destination number is not numeric
			// because most probably it wasn't entered by user (he has a phone keypad remember?)
			// it helps with using "use_dnid" and extensions.conf routing
			if (is_numeric($this->destination)) $agi-> stream_file($prompt, '#');
			return -1;
		}

		// STRIP * FROM DESTINATION NUMBER
		$this->destination = str_replace('*', '', $this->destination);

		$this->save_redial_number($agi, $this->destination);
		
		// LOOKUP RATE : FIND A RATE FOR THIS DESTINATION
		$resfindrate = $RateEngine->rate_engine_findrates($this, $this->destination,$this->tariff);
		if ($resfindrate==0) {
			$this -> debug( ERROR, $agi, __FILE__, __LINE__, "ERROR ::> RateEngine didnt succeed to match the dialed number over the ratecard (Please check : id the ratecard is well create ; if the removeInter_Prefix is set according to your prefix in the ratecard ; if you hooked the ratecard to the Call Plan)");
		} else {
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "OK - RESFINDRATE::> ".$resfindrate);
		}

		// IF DONT FIND RATE
		if ($resfindrate==0){
			$prompt="prepaid-dest-unreachable";
			$agi-> stream_file($prompt, '#');
			return -1;
		}
		// CHECKING THE TIMEOUT
		$res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($this, $this->credit);

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES_ALL_CALCULTIMEOUT ::> $res_all_calcultimeout");
		if (!$res_all_calcultimeout){
			$prompt="prepaid-no-enough-credit";
			$agi-> stream_file($prompt, '#');
			return -1;
		}
		
		// calculate timeout
		//$this->timeout = intval(($this->credit * 60*100) / $rate);  // -- RATE is millime cents && credit is 1cents
		
		$this->timeout = $RateEngine-> ratecard_obj[0]['timeout'];
		$timeout = $this->timeout;
		if ($this->agiconfig['cheat_on_announcement_time']==1) {
			$timeout = $RateEngine-> ratecard_obj[0]['timeout_without_rules'];	
		}

		$announce_time_correction = $RateEngine->ratecard_obj[0][61];
		$timeout = $timeout * $announce_time_correction;

		// set destination and timeout
		// say 'you have x minutes and x seconds'
		$minutes = intval($timeout / 60);
		$seconds = $timeout % 60;

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "TIMEOUT::> ".$this->timeout." x". $announce_time_correction." : minutes=$minutes - seconds=$seconds");
		if (!($minutes>0) && !($seconds>10)) {
			$prompt="prepaid-no-enough-credit";
			$agi-> stream_file($prompt, '#');
			return -1;
		}

		if ($this->agiconfig['say_rateinitial']==1) {
			$this -> fct_say_rate ($agi, $RateEngine->ratecard_obj[0][12]);
		}

		if ($this->agiconfig['say_timetocall']==1) {
			$agi-> stream_file('prepaid-you-have', '#');
			if ($minutes>0){
				if ($minutes==1) {
					if((strtolower($this ->current_language)=='ru')){
			        	$agi-> stream_file('digits/1f', '#');
					} else {
						$agi->say_number($minutes);
					}
					$agi-> stream_file('prepaid-minute', '#');
				} else {
					$agi->say_number($minutes);
					if((strtolower($this ->current_language)=='ru')&& ( ( $minutes%10==2) || ($minutes%10==3 )|| ($minutes%10==4)) ){
						// test for the specific grammatical rules in RUssian
						$agi-> stream_file('prepaid-minute2', '#');
					} else {
						$agi-> stream_file('prepaid-minutes', '#');
					}
				}
			}
			if ($seconds>0 && ($this->agiconfig['disable_announcement_seconds']==0)) {
				if ($minutes>0) $agi-> stream_file('vm-and', '#');
				
				if ($seconds==1) {
					if((strtolower($this ->current_language)=='ru')){
						$agi-> stream_file('digits/1f', '#');
					} else {
						$agi-> say_number($seconds);
						$agi-> stream_file('prepaid-second', '#');
					}
				} else {
					$agi->say_number($seconds);
					if((strtolower($this ->current_language)=='ru')&& ( ( $seconds%10==2) || ($seconds%10==3 )|| ($seconds%10==4)) ){
						// test for the specific grammatical rules in RUssian
						$agi-> stream_file('prepaid-second2', '#');
					} else {
						$agi-> stream_file('prepaid-seconds', '#');
					}
				}
			}
		}
		return 1;
	}


	/**
	 *	Function call_sip_iax_buddy : make the Sip/IAX free calls
	 *
	 *  @param object $agi
	 *  @param object $RateEngine
     *  @param integer $try_num
     *  @return 1 if Ok ; -1 if error
	**/
	function call_sip_iax_buddy($agi, &$RateEngine, $try_num)
	{
		$res = 0;

		if ( ($this->agiconfig['use_dnid']==1) && (!in_array ($this->dnid, $this->agiconfig['no_auth_dnid'])) && (strlen($this->dnid)>2 ))
		{
			$this->destination = $this->dnid;
		} else {
			$res_dtmf = $agi->get_data('prepaid-sipiax-enternumber', 6000, $this->config['global']['len_aliasnumber'], '#');
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES DTMF : ".$res_dtmf ["result"]);
			$this->destination = $res_dtmf ["result"];

			if ($this->destination<=0) {
				return -1;
			}
		}

		$this->save_redial_number($this->destination);

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "SIP o IAX DESTINATION : ".$this->destination);
		$sip_buddies = 0;
		$iax_buddies = 0;

		$QUERY = "SELECT name FROM cc_iax_buddies, cc_card WHERE cc_iax_buddies.name=cc_card.username AND useralias='".$this->destination."'";
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, $QUERY);

		$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, $result);

		if( is_array($result) && count($result) > 0) {
			$iax_buddies = 1;
			$destiax=$result[0][0];
		}

		$card_alias = $this->destination;
		$QUERY = "SELECT name FROM cc_sip_buddies, cc_card WHERE cc_sip_buddies.name=cc_card.username AND useralias='".$this->destination."'";
		$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RESULT : ".print_r($result,true));
		
		if( is_array($result) && count($result) > 0) {
			$sip_buddies = 1;
			$destsip=$result[0][0];
		}

		if (!$sip_buddies && !$iax_buddies){
			$agi-> stream_file('prepaid-sipiax-num-nomatch', '#');
			return -1;
		}
		
		for ($k=0;$k< $sip_buddies+$iax_buddies;$k++)
		{
			if ($k==0 && $sip_buddies) {
				$this->tech = 'SIP';
				$this->destination= $destsip;
			} else {
				$this->tech = 'IAX2';
				$this->destination = $destiax;
			}
			if ($this -> CC_TESTING) $this->destination = "kphone";

			if ($this->agiconfig['record_call'] == 1){
				$myres = $agi->exec("MixMonitor {$this->uniqueid}.{$this->agiconfig['monitor_formatfile']}|b");
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "EXEC MixMonitor {$this->uniqueid}.{$this->agiconfig['monitor_formatfile']}|b");
			}

			$agi->set_callerid($this->useralias);
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[EXEC SetCallerID : ".$this->useralias."]");

			$dialparams = $this->agiconfig['dialcommand_param_sipiax_friend'];
			$dialstr = $this->tech."/".$this->destination.$dialparams;

			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "app_callingcard sip/iax friend: Dialing '$dialstr' ".$this->tech." Friend.\n");

			//# Channel: technology/number@ip_of_gw_to PSTN
			// Dial(IAX2/guest@misery.digium.com/s@default)
			$myres = $this -> run_dial($agi, $dialstr);
			$this -> debug( INFO, $agi, __FILE__, __LINE__, "DIAL $dialstr");
			
			$answeredtime = $agi->get_variable("ANSWEREDTIME");
			$answeredtime = $answeredtime['data'];
			$dialstatus = $agi->get_variable("DIALSTATUS");
			$dialstatus = $dialstatus['data'];

			if ($this->agiconfig['record_call'] == 1) {
				// Monitor(wav,kiki,m)
				$myres = $agi->exec("STOPMONITOR");
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "EXEC StopMonitor (".$this->uniqueid."-".$this->cardnumber.")");
			}

			$this -> debug( INFO, $agi, __FILE__, __LINE__, "[".$this->tech." Friend][K=$k]:[ANSWEREDTIME=".$answeredtime."-DIALSTATUS=".$dialstatus."]");

			//# Ooh, something actually happend!
			if ($dialstatus  == "BUSY") {
				$answeredtime=0;
				$agi-> stream_file('prepaid-isbusy', '#');
			} elseif ($this->dialstatus == "NOANSWER") {
				$answeredtime=0;
				$agi-> stream_file('prepaid-noanswer', '#');
			} elseif ($dialstatus == "CANCEL") {
				$answeredtime=0;
			} elseif ($dialstatus == "ANSWER") {
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "-> dialstatus : $dialstatus, answered time is ".$answeredtime." \n");
			} elseif ($k+1 == $sip_buddies+$iax_buddies){
				$prompt="prepaid-dest-unreachable";
				$agi-> stream_file($prompt, '#');
			}

			if (($dialstatus  == "CHANUNAVAIL") || ($dialstatus  == "CONGESTION"))	continue;
			
			if (strlen($this -> dialstatus_rev_list[$dialstatus])>0)
				$terminatecauseid = $this -> dialstatus_rev_list[$dialstatus];
			else
				$terminatecauseid = 0;
			
			if ($answeredtime > 0){
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CC_RATE_ENGINE_UPDATESYSTEM: usedratecard K=$K - (answeredtime=$answeredtime :: dialstatus=$dialstatus :: cost=$cost)]");
				$QUERY = "INSERT INTO cc_call (uniqueid, sessionid, card_id, nasipaddress, starttime, sessiontime, calledstation, ".
					" terminatecauseid, stoptime, calledrate, sessionbill, id_tariffgroup, id_tariffplan, id_ratecard, id_trunk, src, sipiax) VALUES ".
					"('".$this->uniqueid."', '".$this->channel."',  '".$this->id_card."', '".$this->hostname."',";
				$QUERY .= " CURRENT_TIMESTAMP - INTERVAL $answeredtime SECOND ";
				$QUERY .= ", '$answeredtime', '".$card_alias."', '$terminatecauseid', now(), '0', '0', '0', '0', '$this->CallerID', '1' )";
				
				$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
				return 1;
			}
		}
		if ($this->voicemail) {
			if (($dialstatus =="CHANUNAVAIL") || ($dialstatus == "CONGESTION") ||($dialstatus == "NOANSWER")) {
				// The following section will send the caller to VoiceMail with the unavailable priority.
				// $dest = "u".$card_alias;
				$dest = $card_alias;
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "[STATUS] CHANNEL UNAVAILABLE - GOTO VOICEMAIL ($dest)");
				$agi-> exec(VoiceMail,$dest);
			}

			if (($dialstatus =="BUSY")) {
				// The following section will send the caller to VoiceMail with the busy priority.
				// $dest = "b".$card_alias;
				$dest = $card_alias;
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "[STATUS] CHANNEL BUSY - GOTO VOICEMAIL ($dest)");
				$agi-> exec(VoiceMail,$dest);
			}
		}

		return -1;
	}


	/**
	 *	Function call_did
	 *
	 *  @param object $agi
	 *  @param object $RateEngine
	 *  @param object $listdestination
	 	cc_did.id, cc_did_destination.id, billingtype, cc_did.id_trunk,	destination, cc_did.id_trunk, voip_call

     *  @return 1 if Ok ; -1 if error
	**/
	function call_did ($agi, &$RateEngine, $listdestination)
	{
		$res=0;

		if ($this -> CC_TESTING) $this->destination="kphone";
		$this->agiconfig['say_balance_after_auth']=0;
		$this->agiconfig['say_timetocall']=0;


		if (($listdestination[0][2]==0) || ($listdestination[0][2]==2)) {
			$doibill = 1;
		} else {
			$doibill = 0;
		}

		$callcount=0;
		foreach ($listdestination as $inst_listdestination) {
			$callcount++;

			$this -> debug( INFO, $agi, __FILE__, __LINE__, "[A2Billing] DID call friend: FOLLOWME=$callcount (cardnumber:".$inst_listdestination[6]."|destination:".$inst_listdestination[4]."|tariff:".$inst_listdestination[3].")\n");

			$this->agiconfig['cid_enable']			= 0;
			$this->accountcode 				= $inst_listdestination[6];
			$this->tariff 					= $inst_listdestination[3];
			$this->destination 				= $inst_listdestination[4];
			$this->username 				= $inst_listdestination[6];
			$this->useralias 				= $inst_listdestination[7];
			
			if ($this -> set_inuse) $this -> callingcard_acct_start_inuse($agi,0);
			
			// MAKE THE AUTHENTICATION TO GET ALL VALUE : CREDIT - EXPIRATION - ...
			if ($this -> callingcard_ivr_authenticate($agi)!=0) {
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "[A2Billing] DID call friend: AUTHENTICATION FAILS !!!\n");
			} else {
				// CHECK IF DESTINATION IS SET
				if (strlen($inst_listdestination[4])==0) continue;

				// IF VOIP CALL
				if ($inst_listdestination[5]==1){

					// RUN MIXMONITOR TO RECORD CALL
					if ($this->agiconfig['record_call'] == 1){
						$myres = $agi->exec("MixMonitor {$this->uniqueid}.{$this->agiconfig['monitor_formatfile']}|b");
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "EXEC MixMonitor {$this->uniqueid}.{$this->agiconfig['monitor_formatfile']}|b");
					}

					$dialparams = $this->agiconfig['dialcommand_param_sipiax_friend'];
					$dialstr 	= $inst_listdestination[4].$dialparams;

					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[A2Billing] DID call friend: Dialing '$dialstr' Friend.\n");

					//# Channel: technology/number@ip_of_gw_to PSTN
					// Dial(IAX2/guest@misery.digium.com/s@default)
					$myres = $this -> run_dial($agi, $dialstr);
					$this -> debug( INFO, $agi, __FILE__, __LINE__, "DIAL $dialstr");
					
					$answeredtime 	= $agi->get_variable("ANSWEREDTIME");
					$answeredtime 	= $answeredtime['data'];
					$dialstatus 	= $agi->get_variable("DIALSTATUS");
					$dialstatus 	= $dialstatus['data'];

					if ($this->agiconfig['record_call'] == 1) {
						$myres = $agi->exec("STOPMONITOR");
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "EXEC StopMonitor (".$this->uniqueid."-".$this->cardnumber.")");
					}

					$this -> debug( INFO, $agi, __FILE__, __LINE__, "[".$inst_listdestination[4]." Friend][followme=$callcount]:[ANSWEREDTIME=".$answeredtime."-DIALSTATUS=".$dialstatus."]");
					
					//# Ooh, something actually happend!
					if ($dialstatus  == "BUSY") {
						$answeredtime=0;
						$agi-> stream_file('prepaid-isbusy', '#');
						// FOR FOLLOWME IF THERE IS MORE WE PASS TO THE NEXT ONE OTHERWISE WE NEED TO LOG THE CALL MADE
						if (count($listdestination)>$callcount) continue;
					} elseif ($this->dialstatus == "NOANSWER") {
						$answeredtime=0;
						$agi-> stream_file('prepaid-callfollowme', '#');
						// FOR FOLLOWME IF THERE IS MORE WE PASS TO THE NEXT ONE OTHERWISE WE NEED TO LOG THE CALL MADE
						if (count($listdestination)>$callcount) continue;
					} elseif ($dialstatus == "CANCEL") {
						$answeredtime=0;
						// FOR FOLLOWME IF THERE IS MORE WE PASS TO THE NEXT ONE OTHERWISE WE NEED TO LOG THE CALL MADE
						if (count($listdestination)>$callcount) continue;
					} elseif ($dialstatus == "ANSWER") {
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[A2Billing] DID call friend: dialstatus : $dialstatus, answered time is ".$answeredtime." \n");
					} elseif (($dialstatus  == "CHANUNAVAIL") || ($dialstatus  == "CONGESTION")) {
						$answeredtime=0;
						// FOR FOLLOWME IF THERE IS MORE WE PASS TO THE NEXT ONE OTHERWISE WE NEED TO LOG THE CALL MADE
						if (count($listdestination)>$callcount) continue;
					} else{
						$agi-> stream_file('prepaid-callfollowme', '#');
						// FOR FOLLOWME IF THERE IS MORE WE PASS TO THE NEXT ONE OTHERWISE WE NEED TO LOG THE CALL MADE
						if (count($listdestination)>$callcount) continue;
					}

					if ($answeredtime >0) {

						$this -> debug( INFO, $agi, __FILE__, __LINE__, "[DID CALL - LOG CC_CALL: FOLLOWME=$callcount - (answeredtime=$answeredtime :: dialstatus=$dialstatus :: cost=$cost)]");
						
						if (strlen($this -> dialstatus_rev_list[$dialstatus])>0)
							$terminatecauseid = $this -> dialstatus_rev_list[$dialstatus];
						else
							$terminatecauseid = 0;
						
						$QUERY = "INSERT INTO cc_call (uniqueid, sessionid, card_id, nasipaddress, starttime, sessiontime, calledstation, ".
							" terminatecauseid, stoptime, sessionbill, id_tariffgroup, id_tariffplan, id_ratecard, id_trunk, src, sipiax) VALUES ".
							"('".$this->uniqueid."', '".$this->channel."',  '".$this->id_card."', '".$this->hostname."',";
						$QUERY .= " CURRENT_TIMESTAMP - INTERVAL $answeredtime SECOND ";
						$QUERY .= ", '$answeredtime', '".$inst_listdestination[4]."', '$terminatecauseid', now(), '0', '0', '0', '0', '0', '$this->CallerID', '3' )";
						
						$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
						$this -> debug( INFO, $agi, __FILE__, __LINE__, "[DID CALL - LOG CC_CALL: SQL: $QUERY]:[result:$result]");
						
						// CC_DID & CC_DID_DESTINATION - cc_did.id, cc_did_destination.id
						$QUERY = "UPDATE cc_did SET secondusedreal = secondusedreal + $answeredtime WHERE id='".$inst_listdestination[0]."'";
						$result = $this->instance_table -> SQLExec ($this -> DBHandle, $QUERY, 0);
						$this -> debug( INFO, $agi, __FILE__, __LINE__, "[UPDATE DID]:[result:$result]");
						
						$QUERY = "UPDATE cc_did_destination SET secondusedreal = secondusedreal + $answeredtime WHERE id='".$inst_listdestination[1]."'";
						$result = $this->instance_table -> SQLExec ($this -> DBHandle, $QUERY, 0);
						$this -> debug( INFO, $agi, __FILE__, __LINE__, "[UPDATE DID_DESTINATION]:[result:$result]");
						
						return 1;
					}
					
				// ELSEIF NOT VOIP CALL
				} else {

					$this->agiconfig['use_dnid']=1;
					$this->agiconfig['say_timetocall']=0;

					$this->dnid = $this->destination = $inst_listdestination[4];
					if ($this->CC_TESTING) $this->dnid = $this->destination="011324885";


					if ($this -> callingcard_ivr_authorize($agi, $RateEngine, 0)==1){

						// PERFORM THE CALL
						$result_callperf = $RateEngine->rate_engine_performcall ($agi, $this -> destination, $this);
						if (!$result_callperf) {
							$prompt="prepaid-callfollowme";
							$agi-> stream_file($prompt, '#');
							continue;
						}

						$dialstatus = $RateEngine->dialstatus;
						if (($RateEngine->dialstatus == "NOANSWER") || ($RateEngine->dialstatus == "CANCEL") || ($RateEngine->dialstatus == "BUSY") || ($RateEngine->dialstatus == "CHANUNAVAIL") || ($RateEngine->dialstatus == "CONGESTION")) continue;

						// INSERT CDR  & UPDATE SYSTEM
						$RateEngine->rate_engine_updatesystem($this, $agi, $this-> destination, $doibill, 1);
						// CC_DID & CC_DID_DESTINATION - cc_did.id, cc_did_destination.id
						$QUERY = "UPDATE cc_did SET secondusedreal = secondusedreal + ".$RateEngine->answeredtime." WHERE id='".$inst_listdestination[0]."'";
						$result = $this->instance_table -> SQLExec ($this -> DBHandle, $QUERY, 0);
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[UPDATE DID]:[result:$result]");

						$QUERY = "UPDATE cc_did_destination SET secondusedreal = secondusedreal + ".$RateEngine->answeredtime." WHERE id='".$inst_listdestination[1]."'";
						$result = $this->instance_table -> SQLExec ($this -> DBHandle, $QUERY, 0);
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[UPDATE DID_DESTINATION]:[result:$result]");
						
						// THEN STATUS IS ANSWER
						break;
					}
				}
			} // END IF AUTHENTICATE
		}// END FOR
		
		if ($this->voicemail) {
			if (($dialstatus =="CHANUNAVAIL") || ($dialstatus == "CONGESTION") ||($dialstatus == "NOANSWER")) {
				// The following section will send the caller to VoiceMail with the unavailable priority.
				// $dest = "u".$this->useralias;
				$dest = $this->useralias;
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "[STATUS] CHANNEL UNAVAILABLE - GOTO VOICEMAIL ($dest)");
				$agi-> exec(VoiceMail,$dest);
			}

			if (($dialstatus =="BUSY")) {
				// The following section will send the caller to VoiceMail with the busy priority.
				// $dest = "b".$this->useralias;
				$dest = $this->useralias;
				$this -> debug( INFO, $agi, __FILE__, __LINE__, "[STATUS] CHANNEL BUSY - GOTO VOICEMAIL ($dest)");
				$agi-> exec(VoiceMail,$dest);
			}
		}
	}


	/**
	 *	Function to play the balance
	 * 	format : "you have 100 dollars and 28 cents"
	 *
	 *  @param object $agi
     *  @param float $credit
     *  @return nothing
	**/
	function fct_say_balance ($agi, $credit, $fromvoucher = 0)
	{
		global $currencies_list;

		if (isset($this->agiconfig['agi_force_currency']) && strlen($this->agiconfig['agi_force_currency'])==3)
		{
			$this->currency = $this->agiconfig['agi_force_currency'];
		}

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CURRENCY : $this->currency]");
		if (!isset($currencies_list[strtoupper($this->currency)][2]) || !is_numeric($currencies_list[strtoupper($this->currency)][2])) $mycur = 1;
		else $mycur = $currencies_list[strtoupper($this->currency)][2];
		$credit_cur = $credit / $mycur;

		list($units, $cents)=split('[.]', sprintf('%01.2f', $credit_cur));

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[BEFORE: $credit_cur SPRINTF : ".sprintf('%01.2f', $credit_cur)."]");

		if (isset($this->agiconfig['currency_association_internal'][strtolower($this->currency)])){
			$units_audio = $this->agiconfig['currency_association_internal'][strtolower($this->currency)];
			// substract the last character ex: dollars -> dollar
			$unit_audio = substr($units_audio,0,-1);
		}else{
			$units_audio = $this->agiconfig['currency_association_internal']['all'];
			$unit_audio = $units_audio;
		}
		
		if (isset($this->agiconfig['currency_cents_association_internal'][strtolower($this->currency)])){
			$cents_audio = $this->agiconfig['currency_cents_association_internal'][strtolower($this->currency)];
		}else{
			$cents_audio = "prepaid-cents";
		}
		switch ($cents_audio) {	
		case 'prepaid-pence':
			$cent_audio = 'prepaid-penny';  break;
		default:
			$cent_audio = substr($cents_audio,0,-1);
		}

		// say 'you have x dollars and x cents'
		if ($fromvoucher!=1)$agi-> stream_file('prepaid-you-have', '#');
		else $agi-> stream_file('prepaid-account_refill', '#');
		if ($units==0 && $cents==0) {
			$agi->say_number(0);
		  if (($this ->current_language=='ru') && (strtolower($this->currency)=='usd') ) {
			$agi-> stream_file($units_audio, '#');
		  }else {
			$agi-> stream_file($unit_audio, '#');
		  }
		} else {
			if ($units > 1) {
				$agi->say_number($units);

				if (($this ->current_language=='ru') && (strtolower($this->currency)=='usd') && (  ($units%10==0) ||( $units%10==2) || ($units%10==3 ) || ($units%10==4)) ) {
					// test for the specific grammatical rules in Russian
					$agi-> stream_file('dollar2', '#');
				}elseif (($this ->current_language=='ru') && (strtolower($this->currency)=='usd') && ( $units%10==1)) {
					// test for the specific grammatical rules in Russian
					$agi-> stream_file($unit_audio, '#');
				} else {
					$agi-> stream_file($units_audio, '#');
				}

			} else {
				$agi->say_number($units);
				
				if (($this ->current_language=='ru') && (strtolower($this->currency)=='usd') && ($units == 0)) {
					$agi-> stream_file($units_audio, '#');	
				} else {				
					$agi-> stream_file($unit_audio, '#');
				}
			}

			if ($units > 0 && $cents > 0){
				$agi-> stream_file('vm-and', '#');
			}
			if ($cents>0){
				$agi->say_number($cents);
				if($cents>1){
					if((strtolower($this->currency)=='usd')&&($this ->current_language=='ru')&& ( ( $cents%10==2) || ($cents%10==3 )|| ($cents%10==4)) ){
						// test for the specific grammatical rules in RUssian
						$agi-> stream_file('prepaid-cent2', '#');
					}elseif((strtolower($this->currency)=='usd')&&($this ->current_language=='ru')&&  ( $cents%10==1)  ){
						// test for the specific grammatical rules in RUssian
						$agi-> stream_file($cent_audio, '#');
					}else{
						$agi-> stream_file($cents_audio, '#');
					}
				}else{
				$agi-> stream_file($cent_audio, '#');
				}

			}
		}
	}


	/**
	 * 	Function to play the initial rate
	 *  format : "the cost of the call is 7 dollars and 50 cents per minutes"
	 *
	 *  @param object $agi
	 *  @param float $rate
	 *  @return nothing
	 **/
	function fct_say_rate ($agi, $rate)
	{
		global $currencies_list;

		if (isset($this->agiconfig['agi_force_currency']) && strlen($this->agiconfig['agi_force_currency'])==3)
		{
			$this->currency = $this->agiconfig['agi_force_currency'];
		}

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CURRENCY : $this->currency]");
		if (!isset($currencies_list[strtoupper($this->currency)][2]) || !is_numeric($currencies_list[strtoupper($this->currency)][2])) $mycur = 1;
		else $mycur = $currencies_list[strtoupper($this->currency)][2];
		$credit_cur = $rate / $mycur;

		list($units,$cents)=split('[.]', $credit_cur);
		if (strlen($cents)>2) $cents=substr($cents,0,2);
		if ($units=='') $units=0;
		if ($cents=='') $cents=0;
		elseif (strlen($cents)==1) $cents.= '0';

		if (isset($this->agiconfig['currency_association_internal'][strtolower($this->currency)])){
			$units_audio = $this->agiconfig['currency_association_internal'][strtolower($this->currency)];
			// leave the last character ex: dollars -> dollar
			$unit_audio = substr($units_audio,0,-1);
		}else{
			$units_audio = $this->agiconfig['currency_association_internal']['all'];
			$unit_audio = $units_audio;
		}
		$cent_audio = 'prepaid-cent';
		$cents_audio = 'prepaid-cents';


		// say 'the cost of the call is '
		$agi-> stream_file('prepaid-cost-call', '#');

		if ($units==0 && $cents==0){
			$agi -> say_number(0);
			$agi -> stream_file($unit_audio, '#');
		} else {
			if ($units > 1){
				$agi -> say_number($units);
					
				if(($this ->current_language=='ru')&&(strtolower($this->currency)=='usd')&& ( ( $units%10==2) || ($units%10==3 )|| ($units%10==4)) ){
					// test for the specific grammatical rules in RUssian
					$agi-> stream_file('dollar2', '#');
				}elseif(($this ->current_language=='ru')&&(strtolower($this->currency)=='usd')&& ( $units%10==1)) {
					// test for the specific grammatical rules in RUssian
					$agi-> stream_file($unit_audio, '#');
				}else{
					$agi-> stream_file($units_audio, '#');
				}
					
			}else{
				$agi -> say_number($units);
				$agi -> stream_file($unit_audio, '#');
			}

			if ($units > 0 && $cents > 0){
				$agi -> stream_file('vm-and', '#');
			}
			if ($cents>0){
				$agi -> say_number($cents);
				
				
				if($cents>1){
					if((strtolower($this->currency)=='usd')&&($this ->current_language=='ru')&& ( ( $cents%10==2) || ($cents%10==3 )|| ($cents%10==4)) ){
						// test for the specific grammatical rules in RUssian
						$agi-> stream_file('prepaid-cent2', '#');
					}elseif((strtolower($this->currency)=='usd')&&($this ->current_language=='ru')&&  ( $cents%10==1)  ){
						// test for the specific grammatical rules in RUssian
						$agi-> stream_file($cent_audio, '#');
					}else{
						$agi-> stream_file($cents_audio, '#');
					}
				}else{
				$agi-> stream_file($cent_audio, '#');
				}
				
				
				

				$agi -> stream_file($cent_audio, '#');
			}
		}
		// say 'per minutes'
		$agi-> stream_file('prepaid-per-minutes', '#');
	}

	/**
	 *	Function refill_card_with_voucher
	 *
	 *  @param object $agi
	 *  @param object $RateEngine
	 *  @param object $voucher number

     *  @return 1 if Ok ; -1 if error
	**/
	function refill_card_with_voucher ($agi, $try_num)
	{
		global $currencies_list;

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[VOUCHER REFILL CARD LOG BEGIN]");
		if (isset($this->agiconfig['agi_force_currency']) && strlen($this->agiconfig['agi_force_currency'])==3){
			$this -> currency = $this->agiconfig['agi_force_currency'];
		}

		if (!isset($currencies_list[strtoupper($this->currency)][2]) || !is_numeric($currencies_list[strtoupper($this->currency)][2])){
			$mycur = 1;
		} else {
			$mycur = $currencies_list[strtoupper($this->currency)][2];
		}
		$timetowait = ($this->config['global']['len_voucher'] < 6) ? 8000 : 20000;
		$res_dtmf = $agi->get_data('prepaid-voucher_enter_number', $timetowait, $this->config['global']['len_voucher'], '#');
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "VOUCHERNUMBER RES DTMF : ".$res_dtmf ["result"]);
		$this -> vouchernumber = $res_dtmf ["result"];
		if ($this -> vouchernumber <= 0){
			return -1;
		}

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "VOUCHER NUMBER : ".$this->vouchernumber);

		$QUERY = "SELECT voucher, credit, activated, tag, currency, expirationdate FROM cc_voucher WHERE expirationdate >= CURRENT_TIMESTAMP AND activated='t' AND voucher='".$this -> vouchernumber."'";

		$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[VOUCHER SELECT: $QUERY]\n".print_r($result,true));

		if ($result[0][0]==$this->vouchernumber) {
			if (!isset ($currencies_list[strtoupper($result[0][4])][2])) {
				$this -> debug( ERROR, $agi, __FILE__, __LINE__, "System Error : No currency table complete !!!");
				$agi-> stream_file('prepaid-unknow_used_currencie', '#');
				return -1;
			} else {
				// DISABLE THE VOUCHER
				$this -> add_credit = $result[0][1] * $currencies_list[strtoupper($result[0][4])][2];
				$QUERY = "UPDATE cc_voucher SET activated='f', usedcardnumber='".$this->accountcode."', used=1, usedate=now() WHERE voucher='".$this->vouchernumber."'";
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "QUERY UPDATE VOUCHER: $QUERY");
				$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);

				// UPDATE THE CARD AND THE CREDIT PROPERTY OF THE CLASS
				$QUERY = "UPDATE cc_card SET credit=credit+'".$this ->add_credit."' WHERE username='".$this->accountcode."'";
				$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
				$this -> credit += $this -> add_credit;

				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "QUERY UPDATE CARD: $QUERY");
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, ' The Voucher '.$this->vouchernumber.' has been used, We added '.$this ->add_credit/$mycur.' '.strtoupper($this->currency).' of credit on your account!');
				$this->fct_say_balance ($agi, $this->add_credit, 1);
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[VOUCHER REFILL CARD: $QUERY]");
				return 1;
			}
		}
		else
		{
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[VOUCHER REFILL ERROR: ".$this->vouchernumber." Voucher not avaible or dosn't exist]");
			$agi-> stream_file('voucher_does_not_exist');
			return -1;
		}
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[VOUCHER REFILL CARD LOG END]");
		return 1;
	}


	/*
	 * Function to generate a cardnumber
	 */
	function MDP( $chrs = 10 )
	{
		$pwd = "";
		 mt_srand ((double) microtime() * 1000000);
		 while (strlen($pwd) < $chrs)
		 {
			$chr = chr(mt_rand (0,255));
			if (eregi("^[0-9]$", $chr))
				$pwd = $pwd.$chr;
		 };
		 return $pwd;
	}

	/** Function to retrieve the number of used package Free call for a customer
	 * according to billingtype (Monthly ; Weekly) & Startday
	 *
	 *  @param object $DBHandle
	 *  @param integer $id_cc_card
	 *  @param integer $id_cc_package_offer
	 *  @param integer $billingtype
	 *  @param integer $startday
	 *  @return integer number of seconds used of FT2C package so far in this period
	 **/

   function number_free_calls_used($DBHandle, $id_cc_card, $id_cc_package_offer, $billingtype, $startday){
   	
   		if ($billingtype == 0){
			// PROCESSING FOR MONTHLY
			// if > last day of the month
			if ($startday > date("t")) $startday = date("t");
			if ($startday <= 0 ) $startday = 1;

			// Check if the startday is upper that the current day
			if ($startday > date("j")) $year_month = date('Y-m', strtotime('-1 month'));
			else $year_month = date('Y-m');

			$yearmonth = sprintf("%s-%02d",$year_month,$startday);
			$CLAUSE_DATE=" TIMESTAMP(date_consumption) >= TIMESTAMP('$yearmonth')";
		}else{
			// PROCESSING FOR WEEKLY
			$startday = $startday % 7;
			$dayofweek = date("w"); // Numeric representation of the day of the week 0 (for Sunday) through 6 (for Saturday)
			if ($dayofweek==0) $dayofweek=7;
			if ($dayofweek < $startday) $dayofweek = $dayofweek + 7;
			$diffday = $dayofweek - $startday;
			$CLAUSE_DATE = "date_consumption >= DATE_SUB(CURRENT_DATE, INTERVAL $diffday DAY) ";
		}
		$QUERY = "SELECT  COUNT(*) AS number_calls FROM cc_card_package_offer ".
				 "WHERE $CLAUSE_DATE AND id_cc_card = '$id_cc_card' AND id_cc_package_offer = '$id_cc_package_offer' ";
		$pack_result = $DBHandle -> Execute($QUERY);
		if ($pack_result && ($pack_result -> RecordCount() > 0)) {
			$result = $pack_result -> fetchRow();
			$number_calls_used = $result[0];
		} else {
			$number_calls_used = 0;
		}
		return $number_calls_used;
   	
   }


	/** Function to retrieve the amount of used package FT2C seconds for a customer
	 * according to billingtype (Monthly ; Weekly) & Startday
	 *
	 *  @param object $DBHandle
	 *  @param integer $id_cc_card
	 *  @param integer $id_cc_package_offer
	 *  @param integer $billingtype
	 *  @param integer $startday
	 *  @return integer number of seconds used of FT2C package so far in this period
	 **/
	function FT2C_used_seconds($DBHandle, $id_cc_card, $id_cc_package_offer, $billingtype, $startday) {
		if ($billingtype == 0){
			// PROCESSING FOR MONTHLY
			// if > last day of the month
			if ($startday > date("t")) $startday = date("t");
			if ($startday <= 0 ) $startday = 1;

			// Check if the startday is upper that the current day
			if ($startday > date("j")) $year_month = date('Y-m', strtotime('-1 month'));
			else $year_month = date('Y-m');

			$yearmonth = sprintf("%s-%02d",$year_month,$startday);
			$CLAUSE_DATE=" TIMESTAMP(date_consumption) >= TIMESTAMP('$yearmonth')";
		}else{
			// PROCESSING FOR WEEKLY
			$startday = $startday % 7;
			$dayofweek = date("w"); // Numeric representation of the day of the week 0 (for Sunday) through 6 (for Saturday)
			if ($dayofweek==0) $dayofweek=7;
			if ($dayofweek < $startday) $dayofweek = $dayofweek + 7;
			$diffday = $dayofweek - $startday;
			$CLAUSE_DATE = "date_consumption >= DATE_SUB(CURRENT_DATE, INTERVAL $diffday DAY) ";
		}
		$QUERY = "SELECT  sum(used_secondes) AS used_secondes FROM cc_card_package_offer ".
				 "WHERE $CLAUSE_DATE AND id_cc_card = '$id_cc_card' AND id_cc_package_offer = '$id_cc_package_offer' ";
		$pack_result = $DBHandle -> Execute($QUERY);
		if ($pack_result && ($pack_result -> RecordCount() > 0)) {
			$result = $pack_result -> fetchRow();
			$freetimetocall_used = $result[0];
		} else {
			$freetimetocall_used = 0;
		}
		return $freetimetocall_used;
	}
	
	
	/*
	 * Function apply_rules to the phonenumber : Remove internation prefix
	 */
	function apply_rules ($phonenumber)
	{
		if (is_array($this->agiconfig['international_prefixes']) && (count($this->agiconfig['international_prefixes'])>0)) {
			foreach ($this->agiconfig['international_prefixes'] as $testprefix) {
				if (substr($phonenumber,0,strlen($testprefix))==$testprefix) {
					$this->myprefix = $testprefix;
					return substr($phonenumber,strlen($testprefix));
				}
			}
		}

		$this->myprefix='';
		return $phonenumber;
	}


	/*
	 * Function callingcard_cid_sanitize : Ensure the caller is allowed to use their claimed CID.
	 * Returns: clean CID value, possibly empty.
	 */
	function callingcard_cid_sanitize($agi)
	{
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_SANITIZE - CID:".$this->CallerID."]");

		if (strlen($this->CallerID)==0) {
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_SANITIZE - CID: NO CID]");
			return '';
		}
		$QUERY="";
		if($this->agiconfig['cid_sanitize']=="CID" || $this->agiconfig['cid_sanitize']=="BOTH"){
			$QUERY .=  "SELECT cc_callerid.cid ".
				  " FROM cc_callerid ".
				  " JOIN cc_card ON cc_callerid.id_cc_card=cc_card.id ".
				  " WHERE cc_card.username='".$this->cardnumber."' ";
			$QUERY .= "ORDER BY 1";
			$result1 = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, print_r($result1,true));
		}
		
		$QUERY="";
		if($this->agiconfig['cid_sanitize']=="DID" || $this->agiconfig['cid_sanitize']=="BOTH"){
			$QUERY .=  "SELECT cc_did.did ".
				  " FROM cc_did ".
				  " JOIN cc_did_destination ON cc_did_destination.id_cc_did=cc_did.id ".
				  " JOIN cc_card ON cc_did_destination.id_cc_card=cc_card.id ".
				  " WHERE cc_card.username='".$this->cardnumber."' ";
			$QUERY .= "ORDER BY 1";
			$result2 = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, print_r($result2,true));
		}
		if (count($result1)>0 || count($result2)>0)
			$result = array_merge($result1, $result2);

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RESULT MERGE -> ".print_r($result,true));

		if( !is_array($result)) {
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_SANITIZE - CID: NO DATA]");
			return '';
		}
		for ($i=0;$i<count($result);$i++){
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_SANITIZE - CID COMPARING: ".substr($result[$i][0],strlen($this->CallerID)*-1)." to ".$this->CallerID."]");
			if(substr($result[$i][0],strlen($this->CallerID)*-1)==$this->CallerID) {
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_SANITIZE - CID: ".$result[$i][0]."]");
				return $result[$i][0];
			}
		}
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_SANITIZE - CID UNIQUE RESULT: ".$result[0][0]."]");
		return $result[0][0];
	}


	function callingcard_auto_setcallerid($agi)
	{
		// AUTO SetCallerID
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[AUTO SetCallerID]");
		if ($this->agiconfig['auto_setcallerid']==1){
			if ( strlen($this->agiconfig['force_callerid']) >=1 ){
				$agi -> set_callerid($this->agiconfig['force_callerid']);
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[EXEC SetCallerID : ".$this->agiconfig['force_callerid']."]");
			}elseif ( strlen($this->CallerID) >=1 ){
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[REQUESTED SetCallerID : ".$this->CallerID."]");

      			// IF REQUIRED, VERIFY THAT THE CALLERID IS LEGAL
      			$cid_sanitized = $this->CallerID;
				
				if (strlen($cid_sanitized)>0) {
					$agi->set_callerid($cid_sanitized);
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[EXEC SetCallerID : ".$cid_sanitized."]");
				}else{
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CANNOT SetCallerID : cid_san is empty]");
				}
			}
		}
	}

	
	function update_callback_campaign($agi)
	{
		$now = time();
		$username = $agi->get_variable("USERNAME", true);
		$userid= $agi->get_variable("USERID", true);
		$called= $agi->get_variable("CALLED", true);
		$phonenumber_id= $agi->get_variable("PHONENUMBER_ID", true);
		$campaign_id= $agi->get_variable("CAMPAIGN_ID", true);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[MODE CAMPAIGN CALLBACK: USERNAME=$username  USERID=$userid ]");

		$query_rate = "SELECT cc_campaign_config.flatrate, cc_campaign_config.context FROM cc_card,cc_card_group,cc_campaignconf_cardgroup,cc_campaign_config , cc_campaign WHERE cc_card.id = $userid AND cc_card.id_group = cc_card_group.id AND cc_campaignconf_cardgroup.id_card_group = cc_card_group.id  AND cc_campaignconf_cardgroup.id_campaign_config = cc_campaign_config.id AND cc_campaign.id = $campaign_id AND cc_campaign.id_campaign_config = cc_campaign_config.id";
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[QUERY SEARCH CAMPAIGN CONFIG : ".$query_rate);
		
		$result_rate = $this->instance_table -> SQLExec ($this -> DBHandle, $query_rate);	
		
		$cost = 0;
		if($result_rate) {
			$cost = $result_rate[0][0];
			$context = $result_rate[0][1];
		}
		
		if(empty($context)) {
			$context =  $this -> config["callback"]['context_campaign_callback'];
		}
		
		if ($cost>0) {
			$signe='-';
		} else {
			$signe='+';
		}
		//update balance	
		$QUERY = "UPDATE cc_card SET credit= credit $signe ".a2b_round(abs($cost))." ,  lastuse=now() WHERE username='".$username."'";
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[UPDATE CARD : ".$QUERY);
		$this->instance_table -> SQLExec ($this -> DBHandle, $QUERY);
		
		//dial other context
		$agi -> set_variable('CALLERID(name)', $phonenumber_id.','.$campaign_id);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CONTEXT TO CALL : ".$context."]");
		$agi->exec_dial("local","1@".$context);
		
		$duration = time() - $now;
		///create campaign cdr
		$QUERY_CALL = "INSERT INTO cc_call (uniqueid, sessionid, card_id,calledstation, sipiax,  sessionbill , sessiontime , stoptime ,starttime) VALUES ('".$this->uniqueid."', '".$this->channel."', '".
				$userid."','".$called."',6, ".$cost.", ".$duration." , CURRENT_TIMESTAMP , ";
		$QUERY_CALL .= "DATE_SUB(CURRENT_TIMESTAMP, INTERVAL $duration SECOND )";

		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[INSERT CAMPAIGN CALL : ".$QUERY_CALL);
		$this->instance_table -> SQLExec ($this -> DBHandle, $QUERY_CALL);
		
	}
	

	function callingcard_ivr_authenticate($agi)
	{
		$prompt				= '';
		$res				= 0;
		$retries			= 0;
		$language 			= 'en';
		$callerID_enable 	= $this->agiconfig['cid_enable'];
		// 		  -%-%-%-%-%-%-		FIRST TRY WITH THE CALLERID AUTHENTICATION 	-%-%-%-%-%-%-

		if ($callerID_enable==1 && is_numeric($this->CallerID) && $this->CallerID>0){
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_ENABLE - CID_CONTROL - CID:".$this->CallerID."]");

			// NOT USE A LEFT JOIN HERE - In case the callerID is alone without card bound
			$QUERY =  "SELECT cc_callerid.cid, cc_callerid.id_cc_card, cc_callerid.activated, cc_card.credit, ".
				  " cc_card.tariff, cc_card.activated, cc_card.inuse, cc_card.simultaccess,  ".
				  " cc_card.typepaid, cc_card.creditlimit, cc_card.language, cc_card.username, removeinterprefix, cc_card.redial, ";
			$QUERY .=  " enableexpire, UNIX_TIMESTAMP(expirationdate), expiredays, nbused, UNIX_TIMESTAMP(firstusedate), UNIX_TIMESTAMP(cc_card.creationdate), ";

			$QUERY .=  " cc_card.currency, cc_card.lastname, cc_card.firstname, cc_card.email, cc_card.uipass, cc_card.id_campaign, cc_card.id, useralias, cc_card.status, cc_card.voicemail_permitted, cc_card.voicemail_activated , cc_card.restriction".
						" FROM cc_callerid ".
						" LEFT JOIN cc_card ON cc_callerid.id_cc_card=cc_card.id ".
						" LEFT JOIN cc_tariffgroup ON cc_card.tariff=cc_tariffgroup.id ".
						" WHERE cc_callerid.cid='".$this->CallerID."'";
			$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, print_r($result,true));
			
			if (!is_array($result)) {
				
				if ($this->agiconfig['cid_auto_create_card']==1) {

					for ($k=0 ; $k <= 20 ; $k++) {
						if ($k == 20) {
							$this -> debug( WARN, $agi, __FILE__, __LINE__, "ERROR : Impossible to generate a cardnumber not yet used!");
							$prompt="prepaid-auth-fail";
							$agi-> stream_file($prompt, '#');
							return -2;
						}
						$card_gen = $this -> MDP ($this->agiconfig['cid_auto_create_card_len']);
						$numrow = 0;
						$resmax = $this->DBHandle -> Execute("SELECT username FROM $FG_TABLE_NAME where username='$card_gen'");
						if ($resmax)
							$numrow = $resmax -> RecordCount();

						if ($numrow!=0) continue;
						break;
					}
					$card_alias = $this -> MDP ($this->agiconfig['cid_auto_create_card_len']);
					$uipass = $this -> MDP (5);
					$ttcard = ($this->agiconfig['cid_auto_create_card_typepaid']=="POSTPAY") ? 1 : 0;

					//CREATE A CARD
					
					$QUERY_FIELS = 'username, useralias, uipass, credit, language, tariff, activated, typepaid, creditlimit, inuse, status, currency';
					$QUERY_VALUES = "'$card_gen', '$card_alias', '$uipass', '".$this->agiconfig['cid_auto_create_card_credit']."', 'en', '".$this->agiconfig['cid_auto_create_card_tariffgroup']."', 't','$ttcard', '".$this->agiconfig['cid_auto_create_card_credit_limit']."', '0', '1', '".$this->config['global']['base_currency']."'";
					if($this ->groupe_mode){ 
						$QUERY_FIELS .= ", id_group";
						$QUERY_VALUES .= " , '$this->group_id'";
					}
					
					$result = $this->instance_table -> Add_table ($this->DBHandle, $QUERY_VALUES, $QUERY_FIELS, 'cc_card', 'id');
					$this -> debug( INFO, $agi, __FILE__, __LINE__, "[CARDNUMBER: $card_gen]:[CARDID CREATED : $result]");

					//CREATE A CARD AND AN INSTANCE IN CC_CALLERID
					$QUERY_FIELS = 'cid, id_cc_card';
					$QUERY_VALUES = "'".$this->CallerID."','$result'";

					$result = $this->instance_table -> Add_table ($this->DBHandle, $QUERY_VALUES, $QUERY_FIELS, 'cc_callerid');
					if (!$result) {
						$this -> debug( ERROR, $agi, __FILE__, __LINE__, "[CALLERID CREATION ERROR TABLE cc_callerid]");
						$prompt="prepaid-auth-fail";
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
						$agi-> stream_file($prompt, '#');
						return -2;
					}

					$this->credit = $this->agiconfig['cid_auto_create_card_credit'];
					$this->tariff = $this->agiconfig['cid_auto_create_card_tariffgroup'];
					$this->active = 1;
					$isused = 0;
					$simultaccess = 0;
					$this->typepaid = $ttcard;
					$creditlimit = $this->agiconfig['cid_auto_create_card_credit_limit'];
					$language = 'en';
					$this->accountcode = $card_gen;
					if ($this->typepaid==1) $this->credit = $this->credit+$creditlimit;
				} else {

					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CID_CONTROL - STOP - NO CALLERID]");
					// $callerID_enable=1; -> we are checking later if the callerID/accountcode has been define if not ask for pincode
					if ($this -> agiconfig['cid_askpincode_ifnot_callerid']==1) {
						$this -> accountcode = '';
						$callerID_enable = 0;
					}
				}
			} else {
				// We found a card for this callerID

				$this->credit 				= $result[0][3];
				$this->tariff 				= $result[0][4];
				$this->active 				= $result[0][5];
				$isused 					= $result[0][6];
				$simultaccess 				= $result[0][7];
				$this->typepaid 			= $result[0][8];
				$creditlimit 				= $result[0][9];
				$language 					= $result[0][10];
				$this->accountcode 			= $result[0][11];
				$this->username             = $result[0][11];
				$this->removeinterprefix 	= $result[0][12];
				$this->redial 				= $result[0][13];
				$this->enableexpire 		= $result[0][14];
				$this->expirationdate 		= $result[0][15];
				$this->expiredays 			= $result[0][16];
				$this->nbused 				= $result[0][17];
				$this->firstusedate 		= $result[0][18];
				$this->creationdate 		= $result[0][19];
				$this->currency 			= $result[0][20];
				$this->cardholder_lastname 	= $result[0][21];
				$this->cardholder_firstname = $result[0][22];
				$this->cardholder_email 	= $result[0][23];
				$this->cardholder_uipass 	= $result[0][24];
				$this->id_campaign 			= $result[0][25];
				$this->id_card 				= $result[0][26];
				$this->useralias 			= $result[0][27];
				$this->status 				= $result[0][28];
				$this->voicemail			= ($result[0][29] && $result[0][30]) ? 1 : 0;
				$this->restriction			= $result[0][31];
				if ($this->typepaid==1) $this->credit = $this->credit+$creditlimit;

				// CHECK IF CALLERID ACTIVATED
				if( $result[0][2] != "t" && $result[0][2] != "1" ) 	$prompt = "prepaid-auth-fail";

				// CHECK credit < min_credit_2call / you have zero balance
				if(!$this -> enough_credit_to_call()) $prompt = "prepaid-no-enough-credit-stop";
				// CHECK activated=t / CARD NOT ACTIVE, CONTACT CUSTOMER SUPPORT
				if( $this->status != "1") 	$prompt = "prepaid-auth-fail";	// not expired but inactive.. probably not yet sold.. find better prompt
				// CHECK IF THE CARD IS USED
				if (($isused>0) && ($simultaccess!=1))	$prompt="prepaid-card-in-use";
				// CHECK FOR EXPIRATION  -  enableexpire ( 0 : none, 1 : expire date, 2 : expire days since first use, 3 : expire days since creation)
				if ($this->enableexpire>0){
					if ($this->enableexpire==1  && $this->expirationdate!='00000000000000' && strlen($this->expirationdate)>5) {
						// expire date
						if (intval($this->expirationdate-time())<0) // CARD EXPIRED :(
							$prompt = "prepaid-card-expired";

					} elseif ($this->enableexpire==2  && $this->firstusedate!='00000000000000' && strlen($this->firstusedate)>5 && ($this->expiredays>0)) {
						// expire days since first use
						$date_will_expire = $this->firstusedate+(60*60*24*$this->expiredays);
						if (intval($date_will_expire-time())<0) // CARD EXPIRED :(
						$prompt = "prepaid-card-expired";

					} elseif ($this->enableexpire==3  && $this->creationdate!='00000000000000' && strlen($this->creationdate)>5 && ($this->expiredays>0)) {
						// expire days since creation
						$date_will_expire = $this->creationdate+(60*60*24*$this->expiredays);
						if (intval($date_will_expire-time())<0)	// CARD EXPIRED :(
							$prompt = "prepaid-card-expired";
					}
				}

				if (strlen($prompt)>0) {
					$agi-> stream_file($prompt, '#'); // Added because was missing the prompt
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, 'prompt:'.strtoupper($prompt));

					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[ERROR CHECK CARD : $prompt (cardnumber:".$this->cardnumber.")]");
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - Refill with vouchert]");

					if ($this->agiconfig['jump_voucher_if_min_credit']==1 && !$this -> enough_credit_to_call()) {

						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher] ");
						$vou_res = $this -> refill_card_with_voucher($agi,2);
						if ($vou_res==1) {
							return 0;
						} else {
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher fail] ");
						}
					}
					if ($prompt == "prepaid-no-enough-credit-stop" && $this->agiconfig['notenoughcredit_cardnumber']==1) {
						$this->accountcode=''; $callerID_enable=0;
						$this->agiconfig['cid_auto_assign_card_to_cid']=0;
						if ($this->agiconfig['notenoughcredit_assign_newcardnumber_cid']==1) $this -> ask_other_cardnumber=1;
					} else {
						return -2;
					}
				}

			} // elseif We -> found a card for this callerID

		} else {
			// NO CALLERID AUTHENTICATION
			$callerID_enable=0;
		}

		// 		 -%-%-%-%-%-%-		CHECK IF WE CAN AUTHENTICATE THROUGH THE "ACCOUNTCODE" 	-%-%-%-%-%-%-

		$prompt_entercardnum= "prepaid-enter-pin-number";
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, ' - Account code - '.$this->accountcode);
		if (strlen ($this->accountcode)>=1) {
			$this->username = $this -> cardnumber = $this->accountcode;
			for ($i=0;$i<=0;$i++){

				if ($callerID_enable!=1 || !is_numeric($this->CallerID) || $this->CallerID<=0){

					$QUERY =  "SELECT credit, tariff, activated, inuse, simultaccess, typepaid, ";
					$QUERY .=  "creditlimit, language, removeinterprefix, redial, enableexpire, UNIX_TIMESTAMP(expirationdate), expiredays, nbused, UNIX_TIMESTAMP(firstusedate), UNIX_TIMESTAMP(cc_card.creationdate), cc_card.currency, cc_card.lastname, cc_card.firstname, cc_card.email, cc_card.uipass, cc_card.id_campaign, cc_card.id, useralias, status, voicemail_permitted, voicemail_activated , cc_card.restriction FROM cc_card ";

					$QUERY .=  "LEFT JOIN cc_tariffgroup ON tariff=cc_tariffgroup.id WHERE username='".$this->cardnumber."'";
					$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
					
					if( !is_array($result)) {
						$prompt="prepaid-auth-fail";
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
						$res = -2;
						break;
					} else {
						// -%-%-%-	WE ARE GOING TO CHECK IF THE CALLERID IS CORRECT FOR THIS CARD	-%-%-%-
						if ($this->agiconfig['callerid_authentication_over_cardnumber']==1) {

							if (!is_numeric($this->CallerID) && $this->CallerID<=0) {
								$res = -2;
								break;
							}

							$QUERY = " SELECT cid, id_cc_card, activated FROM cc_callerid "
									." WHERE cc_callerid.cid='".$this->CallerID."' AND cc_callerid.id_cc_card='".$result[0][22]."'";
							$result_check_cid = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, $result_check_cid);

							if( !is_array($result_check_cid)) {
								$prompt="prepaid-auth-fail";
								$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
								$res = -2;
								break;
							}
						}
					}

					$this->credit 				= $result[0][0];
					$this->tariff 				= $result[0][1];
					$this->active 				= $result[0][2];
					$isused 					= $result[0][3];
					$simultaccess 				= $result[0][4];
					$this->typepaid 			= $result[0][5];
					$creditlimit 				= $result[0][6];
					$language 					= $result[0][7];
					$this->removeinterprefix 	= $result[0][8];
					$this->redial 				= $result[0][9];
					$this->enableexpire 		= $result[0][10];
					$this->expirationdate 		= $result[0][11];
					$this->expiredays 			= $result[0][12];
					$this->nbused 				= $result[0][13];
					$this->firstusedate 		= $result[0][14];
					$this->creationdate 		= $result[0][15];
					$this->currency 			= $result[0][16];
					$this->cardholder_lastname 	= $result[0][17];
					$this->cardholder_firstname = $result[0][18];
					$this->cardholder_email 	= $result[0][19];
					$this->cardholder_uipass 	= $result[0][20];
					$this->id_campaign  		= $result[0][21];
					$this->id_card  			= $result[0][22];
					$this->useralias 			= $result[0][23];
					$this->status 				= $result[0][24];
					$this->voicemail		= ($result[0][25] && $result[0][26]) ? 1 : 0;
					$this->restriction			= $result[0][27];

					if ($this->typepaid==1) $this->credit = $this->credit+$creditlimit;
				}

				if (strlen($language)==2 && !($this->languageselected>=1)){

					if($this->agiconfig['asterisk_version'] == "1_2")
					{
						$lg_var_set = 'LANGUAGE()';
					}
					else
					{
						$lg_var_set = 'CHANNEL(language)';
					}
					$agi -> set_variable($lg_var_set, $language);
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SET $lg_var_set $language]");
					$this ->current_language=$language;
				}

				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[credit=".$this->credit." :: tariff=".$this->tariff." :: status=".$this->status." :: isused=$isused :: simultaccess=$simultaccess :: typepaid=".$this->typepaid." :: creditlimit=$creditlimit :: language=$language]");

				$prompt = '';
				// CHECK credit > min_credit_2call / you have zero balance
				if( !$this -> enough_credit_to_call() ) $prompt = "prepaid-no-enough-credit-stop";
				// CHECK activated=t / CARD NOT ACTIVE, CONTACT CUSTOMER SUPPORT
				if( $this->status != "1") 	$prompt = "prepaid-auth-fail";	// not expired but inactive.. probably not yet sold.. find better prompt
				// CHECK IF THE CARD IS USED
				if (($isused>0) && ($simultaccess!=1))	$prompt="prepaid-card-in-use";
				// CHECK FOR EXPIRATION  -  enableexpire ( 0 : none, 1 : expire date, 2 : expire days since first use, 3 : expire days since creation)
				if ($this->enableexpire>0){
					if ($this->enableexpire==1  && $this->expirationdate!='00000000000000' && strlen($this->expirationdate)>5){
						// expire date
						if (intval($this->expirationdate-time())<0) // CARD EXPIRED :(
						$prompt = "prepaid-card-expired";
					}elseif ($this->enableexpire==2  && $this->firstusedate!='00000000000000' && strlen($this->firstusedate)>5 && ($this->expiredays>0)){
					// expire days since first use
						$date_will_expire = $this->firstusedate+(60*60*24*$this->expiredays);
						if (intval($date_will_expire-time())<0) // CARD EXPIRED :(
						$prompt = "prepaid-card-expired";

					}elseif ($this->enableexpire==3  && $this->creationdate!='00000000000000' && strlen($this->creationdate)>5 && ($this->expiredays>0)){
						// expire days since creation
						$date_will_expire = $this->creationdate+(60*60*24*$this->expiredays);
						if (intval($date_will_expire-time())<0)	// CARD EXPIRED :(
							$prompt = "prepaid-card-expired";
					}
				}

				if (strlen($prompt)>0){
					$agi-> stream_file($prompt, '#'); // Added because was missing the prompt
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, 'prompt:'.strtoupper($prompt));

					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[ERROR CHECK CARD : $prompt (cardnumber:".$this->cardnumber.")]");

					if ($this->agiconfig['jump_voucher_if_min_credit']==1 && $prompt == "prepaid-no-enough-credit-stop"){
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher] ");
						$vou_res = $this -> refill_card_with_voucher($agi,2);
						if ($vou_res==1){
							return 0;
						}else {
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOTENOUGHCREDIT - refill_card_withvoucher fail] ");
						}
					}
					if ($prompt == "prepaid-no-enough-credit-stop" && $this->agiconfig['notenoughcredit_cardnumber']==1) {
						$this->accountcode='';
						$this->agiconfig['cid_auto_assign_card_to_cid']=0;
						if ($this->agiconfig['notenoughcredit_assign_newcardnumber_cid']==1) $this -> ask_other_cardnumber=1;
					}else{
						return -2;
					}
				}

			} // For end
		}elseif ($callerID_enable==0){

			// 		  -%-%-%-%-%-%-		IF NOT PREVIOUS WE WILL ASK THE CARDNUMBER AND AUTHENTICATE ACCORDINGLY 	-%-%-%-%-%-%-
			for ($retries = 0; $retries < 3; $retries++) {
				if (($retries>0) && (strlen($prompt)>0)){
					$agi-> stream_file($prompt, '#');
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
				}
				if ($res < 0) {
					$res = -1;
					break;
				}

				$res = 0;
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "Requesting DTMF, CARDNUMBER_LENGTH_MAX ".CARDNUMBER_LENGTH_MAX);
				$res_dtmf = $agi->get_data($prompt_entercardnum, 6000, CARDNUMBER_LENGTH_MAX);
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "RES DTMF : ".$res_dtmf ["result"]);
				$this->cardnumber = $res_dtmf ["result"];

				if ($this->CC_TESTING) $this->cardnumber="2222222222";
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "CARDNUMBER ::> ".$this->cardnumber);

				if ( !isset($this->cardnumber) || strlen($this->cardnumber) == 0) {
					$prompt = "prepaid-no-card-entered";
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
					continue;
				}

				if ( strlen($this->cardnumber) > CARDNUMBER_LENGTH_MAX || strlen($this->cardnumber) < CARDNUMBER_LENGTH_MIN) {
					$prompt = "prepaid-invalid-digits";
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
					continue;
				}
				$this->accountcode = $this->username = $this->cardnumber;

				$QUERY =  "SELECT credit, tariff, activated, inuse, simultaccess, typepaid, ";
				$QUERY .=  "creditlimit, language, removeinterprefix, redial, enableexpire, UNIX_TIMESTAMP(expirationdate), expiredays, nbused, UNIX_TIMESTAMP(firstusedate), UNIX_TIMESTAMP(cc_card.creationdate), cc_card.currency, cc_card.lastname, cc_card.firstname, cc_card.email, cc_card.uipass, cc_card.id, cc_card.id_campaign, cc_card.id, useralias, status, voicemail_permitted, voicemail_activated , cc_card.restriction FROM cc_card "."LEFT JOIN cc_tariffgroup ON tariff=cc_tariffgroup.id WHERE username='".$this->cardnumber."'";

				$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, print_r($result,true));

				if( !is_array($result)) {
					$prompt="prepaid-auth-fail";
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
					continue;
				}else{
					// 		  -%-%-%-	WE ARE GOING TO CHECK IF THE CALLERID IS CORRECT FOR THIS CARD	-%-%-%-
					if ($this->agiconfig['callerid_authentication_over_cardnumber']==1){

						if (!is_numeric($this->CallerID) && $this->CallerID<=0){
							$prompt="prepaid-auth-fail";
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
							continue;
						}

						$QUERY = " SELECT cid, id_cc_card, activated FROM cc_callerid "
								." WHERE cc_callerid.cid='".$this->CallerID."' AND cc_callerid.id_cc_card='".$result[0][23]."'";

						$result_check_cid = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, print_r($result_check_cid,true));
						
						if( !is_array($result_check_cid)) {
							$prompt="prepaid-auth-fail";
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
							continue;
						}
					}
				}

				$this->credit = $result[0][0];
				$this->tariff = $result[0][1];
				$this->active = $result[0][2];
				$isused = $result[0][3];
				$simultaccess = $result[0][4];
				$this->typepaid = $result[0][5];
				$creditlimit = $result[0][6];
				$language = $result[0][7];
				$this->removeinterprefix = $result[0][8];
				$this->redial = $result[0][9];
				$this->enableexpire = $result[0][10];
				$this->expirationdate = $result[0][11];
				$this->expiredays = $result[0][12];
				$this->nbused = $result[0][13];
				$this->firstusedate = $result[0][14];
				$this->creationdate = $result[0][15];
				$this->currency = $result[0][16];
				$this->cardholder_lastname = $result[0][17];
				$this->cardholder_firstname = $result[0][18];
				$this->cardholder_email = $result[0][19];
				$this->cardholder_uipass = $result[0][20];
				$the_card_id = $result[0][21];
				$this->id_campaign  = $result[0][22];
				$this->id_card  = $result[0][23];
				$this->useralias = $result[0][24];
				$this->status = $result[0][25];
				$this->voicemail = ($result[0][26] && $result[0][27]) ? 1 : 0;
				$this->restriction = $result[0][28];
				if ($this->typepaid==1) $this->credit = $this->credit+$creditlimit;

				if (strlen($language)==2  && !($this->languageselected>=1))
				{
					// http://www.voip-info.org/wiki/index.php?page=Asterisk+cmd+SetLanguage
					// Set(CHANNEL(language)=<lang>) 1_4 & Set(LANGUAGE()=language) 1_2

					if($this->agiconfig['asterisk_version'] == "1_2")
					{
						$lg_var_set = 'LANGUAGE()';
					}
					else
					{
						$lg_var_set = 'CHANNEL(language)';
					}
					$agi -> set_variable($lg_var_set, $language);
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SET $lg_var_set $language]");
				}
				$prompt = '';
				// CHECK credit > min_credit_2call / you have zero balance
				if( !$this -> enough_credit_to_call() ) $prompt = "prepaid-no-enough-credit-stop";
				// CHECK activated=t / CARD NOT ACTIVE, CONTACT CUSTOMER SUPPORT
				if( $this->status != "1") 	$prompt = "prepaid-auth-fail";	// not expired but inactive.. probably not yet sold.. find better prompt
				// CHECK IF THE CARD IS USED
				if (($isused>0) && ($simultaccess!=1))	$prompt="prepaid-card-in-use";
				// CHECK FOR EXPIRATION  -  enableexpire ( 0 : none, 1 : expire date, 2 : expire days since first use, 3 : expire days since creation)
				if ($this->enableexpire>0){
					if ($this->enableexpire==1  && $this->expirationdate!='00000000000000' && strlen($this->expirationdate)>5){
						// expire date
						if (intval($this->expirationdate-time())<0) // CARD EXPIRED :(
						$prompt = "prepaid-card-expired";

					}elseif ($this->enableexpire==2  && $this->firstusedate!='00000000000000' && strlen($this->firstusedate)>5 && ($this->expiredays>0)){
						// expire days since first use
						$date_will_expire = $this->firstusedate+(60*60*24*$this->expiredays);
						if (intval($date_will_expire-time())<0) // CARD EXPIRED :(
						$prompt = "prepaid-card-expired";

					}elseif ($this->enableexpire==3  && $this->creationdate!='00000000000000' && strlen($this->creationdate)>5 && ($this->expiredays>0)){
						// expire days since creation
						$date_will_expire = $this->creationdate+(60*60*24*$this->expiredays);
						if (intval($date_will_expire-time())<0)	// CARD EXPIRED :(
						$prompt = "prepaid-card-expired";
					}
				}

				//CREATE AN INSTANCE IN CC_CALLERID
				if ($this->agiconfig['cid_enable']==1 && $this->agiconfig['cid_auto_assign_card_to_cid']==1 && is_numeric($this->CallerID) && $this->CallerID>0 && $this -> ask_other_cardnumber!=1){

					$QUERY = "SELECT count(*) FROM cc_callerid WHERE id_cc_card='$the_card_id'";
					$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 1);
					
					// CHECK IF THE AMOUNT OF CALLERID IS LESS THAN THE LIMIT
					if ($result[0][0] < $this->config["webcustomerui"]['limit_callerid']) {
						
						$QUERY_FIELS = 'cid, id_cc_card';
						$QUERY_VALUES = "'".$this->CallerID."','$the_card_id'";
						
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CREATE AN INSTANCE IN CC_CALLERID -  QUERY_VALUES:$QUERY_VALUES, QUERY_FIELS:$QUERY_FIELS]");
						$result = $this->instance_table -> Add_table ($this->DBHandle, $QUERY_VALUES, $QUERY_FIELS, 'cc_callerid');
						
						if (!$result){
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[CALLERID CREATION ERROR TABLE cc_callerid]");
							$prompt="prepaid-auth-fail";
							$this -> debug( DEBUG, $agi, __FILE__, __LINE__, strtoupper($prompt));
							$agi-> stream_file($prompt, '#');
							return -2;
						}
					} else {
						$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NOT ADDING NEW CID IN CC_CALLERID : CID LIMIT]");
					}
				}

				//UPDATE THE CARD ASSIGN TO THIS CC_CALLERID
				if ($this->agiconfig['notenoughcredit_assign_newcardnumber_cid']==1 && strlen($this->CallerID)>1 && $this -> ask_other_cardnumber==1){
					$this -> ask_other_cardnumber=0;
					$QUERY = "UPDATE cc_callerid SET id_cc_card='$the_card_id' WHERE cid='".$this->CallerID."'";
					$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
				}

				if (strlen($prompt)>0){
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[ERROR CHECK CARD : $prompt (cardnumber:".$this->cardnumber.")]");
					$res = -2;
					break;
				}
				break;
			}//end for
		}else{
			$res = -2;
		}//end IF
		if (($retries < 3) && $res==0) {
			
			$this -> callingcard_acct_start_inuse($agi,1);
			
			if ($this->agiconfig['say_balance_after_auth']==1){
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[A2Billing] SAY BALANCE : $this->credit \n");
				$this -> fct_say_balance ($agi, $this->credit);
			}


		} else if ($res == -2 ) {
			$agi-> stream_file($prompt, '#');
		} else {
			$res = -1;
		}

		return $res;
	}


	function callingcard_ivr_authenticate_light (&$error_msg,$simbalance) {
		
		$res=0;
		$QUERY = "SELECT credit, tariff, activated, inuse, simultaccess, typepaid, ";
		$QUERY .= "creditlimit, language, removeinterprefix, redial, enableexpire, UNIX_TIMESTAMP(expirationdate), expiredays, nbused, UNIX_TIMESTAMP(firstusedate), UNIX_TIMESTAMP(cc_card.creationdate), cc_card.currency, cc_card.lastname, cc_card.firstname, cc_card.email, cc_card.uipass, cc_card.id_campaign, status, voicemail_permitted, voicemail_activated, cc_card.restriction FROM cc_card ";
		$QUERY .= "LEFT JOIN cc_tariffgroup ON tariff=cc_tariffgroup.id WHERE username='".$this->cardnumber."'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		
		if( !is_array($result)) {
			$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Authentication Failed !!!").'</b></font><br>';
			return 0;
		}
		//If we receive a positive value from the rate simulator, we simulate with that initial balance. If we receive <=0 we use the value retrieved from the account
           	if ($simbalance>0) {
                        $this -> credit = $simbalance;
                        } else {
				$this->credit = $result[0][0];
			}
		$this->tariff = $result[0][1];
		$this->active = $result[0][2];
		$isused = $result[0][3];
		$simultaccess = $result[0][4];
		$this->typepaid = $result[0][5];
		$creditlimit = $result[0][6];
		$language = $result[0][7];
		$this->removeinterprefix = $result[0][8];
		$this->redial = $result[0][9];
		$this->enableexpire = $result[0][10];
		$this->expirationdate = $result[0][11];
		$this->expiredays = $result[0][12];
		$this->nbused = $result[0][13];
		$this->firstusedate = $result[0][14];
		$this->creationdate = $result[0][15];
		$this->currency = $result[0][16];
		$this->cardholder_lastname = $result[0][17];
		$this->cardholder_firstname = $result[0][18];
		$this->cardholder_email = $result[0][19];
		$this->cardholder_uipass = $result[0][20];
		$this->id_campaign  = $result[0][21];
		$this->status  = $result[0][22];
		$this->voicemail = ($result[0][23] && $result[0][24]) ? 1 : 0;
		$this->restriction			= $result[0][25];

		if ($this->typepaid==1) $this->credit = $this->credit+$creditlimit;

		// CHECK IF ENOUGH CREDIT TO CALL
		if( $this->credit <= $this->agiconfig['min_credit_2call'] && $this -> typepaid==0){
			$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Not enough credit to call !!!").'</b></font><br>';
			return 0;
		}
		// CHECK POSTPAY
		if( $this->typepaid==1 && $this->credit <= -$creditlimit && $creditlimit!=0){
			$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Not enough credit to call !!!").'</b></font><br>';
			return 0;
		}

		// CHECK activated=t / CARD NOT ACTIVE, CONTACT CUSTOMER SUPPORT
		if( $this->status != "1"){
			$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Card is not active!!!").'</b></font><br>';
			return 0;
		}

		// CHECK IF THE CARD IS USED
		if (($isused>0) && ($simultaccess!=1)){
			$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Card is actually in use!!!").'</b></font><br>';
			return 0;
		}

		// CHECK FOR EXPIRATION  -  enableexpire ( 0 : none, 1 : expire date, 2 : expire days since first use, 3 : expire days since creation)
		if ($this->enableexpire>0){
			if ($this->enableexpire==1  && $this->expirationdate!='00000000000000' && strlen($this->expirationdate)>5){
				// expire date
				if (intval($this->expirationdate-time())<0){ // CARD EXPIRED :(
					$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Card have expired!!!").'</b></font><br>';
					return 0;
				}

			}elseif ($this->enableexpire==2  && $this->firstusedate!='00000000000000' && strlen($this->firstusedate)>5 && ($this->expiredays>0)){
				// expire days since first use
				$date_will_expire = $this->firstusedate+(60*60*24*$this->expiredays);
				if (intval($date_will_expire-time())<0){ // CARD EXPIRED :(
				$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Card have expired!!!").'</b></font><br>';
				return 0;
			}

			}elseif ($this->enableexpire==3  && $this->creationdate!='00000000000000' && strlen($this->creationdate)>5 && ($this->expiredays>0)){
				// expire days since creation
				$date_will_expire = $this->creationdate+(60*60*24*$this->expiredays);
				if (intval($date_will_expire-time())<0){ // CARD EXPIRED :(
					$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("Error : Card have expired!!!").'</b></font><br>';
					return 0;
				}
			}
		}

		return 1;
	}
	
	
	/*
	 * Function deck_switch
	 * to switch the Callplan from a customer : callplan_deck_minute_threshold
	 *
	 */
	function deck_switch($agi)
	{
		if (strpos($this->agiconfig['callplan_deck_minute_threshold'], ',') === false) 
			return false;
		
		$arr_splitable_deck = explode(",", $this->agiconfig['callplan_deck_minute_threshold']);
		
		foreach ($arr_splitable_deck as $arr_value) {
		
			$arr_value = trim ($arr_value);
			$arr_value_explode = explode(":", $arr_value,2);
			if (count($arr_value_explode) > 1){
				if (is_numeric($arr_value_explode[0]) && is_numeric($arr_value_explode[1]) ){
					$arr_value_deck_callplan[] = $arr_value_explode[0];
					$arr_value_deck_minute[] = $arr_value_explode[1];
				}
			}else{
				if (is_numeric($arr_value)){
					$arr_value_deck_callplan[] = $arr_value;
					$arr_value_deck_minute[] = 0;
				}
			}
		}
		// We have $arr_value_deck_callplan with 1, 2, 3 & we have $arr_value_deck_minute with 5, 1, 0
		if (count($arr_value_deck_callplan) == 0)
			return false;
		
		$QUERY = "SELECT sum(sessiontime), count(*) FROM cc_call WHERE card_id='".$this->id_card."'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[DECK SWITCH - Start]".print_r($result, true));
		$sessiontime_for_card = $result[0][0];
		$calls_for_card = $result[0][1];
		
		$find_deck = false;
		$accumul_seconds = 0;
		for ($ind_deck = 0 ; $ind_deck < count($arr_value_deck_callplan) ; $ind_deck++){
			$accumul_seconds += $arr_value_deck_minute[$ind_deck];
			
			if ($arr_value_deck_callplan[$ind_deck] == $this->tariff) {
				if (is_numeric($arr_value_deck_callplan[$ind_deck+1])) {
					$find_deck = true;
				} else {
					$find_deck = false;
				}
				break;
			}
		}
		
		$ind_deck = $ind_deck + 1;
		if ($find_deck) {
			// Check if the sum sessiontime call is more the the accumulation of the parameters seconds & that the amount of calls made is upper than the deck level
			if (($sessiontime_for_card  > $accumul_seconds) && ($calls_for_card > $ind_deck)) {
				// UPDATE CARD
				$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[DECK SWITCH] : UPDATE CARD TO CALLPLAN ID = ".$arr_value_deck_callplan[$ind_deck]);
				$QUERY = "UPDATE cc_card SET tariff='".$arr_value_deck_callplan[$ind_deck]."' WHERE id='".$this->id_card."'";
				$result = $this -> instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
				
				$this->tariff = $arr_value_deck_callplan[$ind_deck];
			}
		}
		return true;
	}
	
	
	/*
	 * Function DbConnect
	 * Returns: true / false if connection has been established
	 */
	function DbConnect()
	{
		$ADODB_CACHE_DIR = '/tmp';
		/*	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;	*/
		require_once('adodb/adodb.inc.php');

		if ($this->config['database']['dbtype'] == "postgres"){
			$datasource = 'pgsql://'.$this->config['database']['user'].':'.$this->config['database']['password'].'@'.$this->config['database']['hostname'].'/'.$this->config['database']['dbname'];
		}else{
			$datasource = 'mysqli://'.$this->config['database']['user'].':'.$this->config['database']['password'].'@'.$this->config['database']['hostname'].'/'.$this->config['database']['dbname'];
		}
		$this->DBHandle = NewADOConnection($datasource);
		if (!$this->DBHandle) die("Connection failed");
		
		if ($this->config['database']['dbtype'] == "mysqli") {
			$this->DBHandle -> Execute('SET AUTOCOMMIT=1');
		}
		
		return true;
	}
	
	/*
     * Function DbReConnect
     * Returns: true / false if connection has been established
     */
	function DbReConnect($agi){
		$res = $this->DBHandle -> Execute("select 1");
		if (!$res) {
		   	$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[DB CONNECTION LOST] - RECONNECT ATTEMPT");	
		   	$this->DBHandle -> Close();
			if ($this->config['database']['dbtype'] == "postgres"){
				$datasource = 'pgsql://'.$this->config['database']['user'].':'.$this->config['database']['password'].'@'.$this->config['database']['hostname'].'/'.$this->config['database']['dbname'];
			}else{
            	$datasource = 'mysqli://'.$this->config['database']['user'].':'.$this->config['database']['password'].'@'.$this->config['database']['hostname'].'/'.$this->config['database']['dbname'];
			}
			$count=1;$sleep=1;
			while ((!$res)&&($count<5)){
				$this->DBHandle = NewADOConnection($datasource);
				if (!$this->DBHandle) {
					$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[DB CONNECTION LOST]- RECONNECT FAILED ,ATTEMPT $count sleep for $sleep ");
					$count+=1;$sleep=$sleep*2;
					sleep($sleep);
				} else { 
					break;
				}
			}
			if (!$this->DBHandle) {
				$this -> debug( FATAL, $agi, __FILE__, __LINE__, "[DB CONNECTION LOST] CDR NOT POSTED");
				die("Reconnection failed");
			}
			if ($this->config['database']['dbtype'] == "mysqli") {
				$this->DBHandle -> Execute('SET AUTOCOMMIT=1');
			}
			
			$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[NO DB CONNECTION] - RECONNECT OK]");

		} else {
			$res -> Close();
		}
		return true;
	}

	/*
	 * Function DbDisconnect
	 */
	function DbDisconnect()
	{
		$this -> DBHandle -> disconnect();
	}


	/*
	 * function splitable_data
	 * used by parameter like interval_len_cardnumber : 8-10, 12-18, 20
	 * it will build an array with the different interval
	 */
	function splitable_data ($splitable_value){

		$arr_splitable_value = explode(",", $splitable_value);
		foreach ($arr_splitable_value as $arr_value){
			$arr_value = trim ($arr_value);
			$arr_value_explode = explode("-", $arr_value,2);
			if (count($arr_value_explode)>1){
				if (is_numeric($arr_value_explode[0]) && is_numeric($arr_value_explode[1]) && $arr_value_explode[0] < $arr_value_explode[1] ){
					for ($kk=$arr_value_explode[0];$kk<=$arr_value_explode[1];$kk++){
						$arr_value_to_import[] = $kk;
					}
				}elseif (is_numeric($arr_value_explode[0])){
					$arr_value_to_import[] = $arr_value_explode[0];
				}elseif (is_numeric($arr_value_explode[1])){
					$arr_value_to_import[] = $arr_value_explode[1];
				}

			}else{
				$arr_value_to_import[] = $arr_value_explode[0];
			}
		}

		$arr_value_to_import = array_unique($arr_value_to_import);
		sort($arr_value_to_import);
		return $arr_value_to_import;
	}

	function save_redial_number($agi, $number){
		if(($this->mode == 'did') || ($this->mode == 'callback')){
		    return;
		}
		$QUERY = "UPDATE cc_card SET redial = '{$number}' WHERE username='".$this->accountcode."'";
		$result = $this->instance_table -> SQLExec ($this->DBHandle, $QUERY, 0);
		$this -> debug( DEBUG, $agi, __FILE__, __LINE__, "[SAVING DESTINATION FOR REDIAL: SQL: {$QUERY}]:[result: {$result}]");
	}
	
	function run_dial($agi, $dialstr)
	{
		if($this->agiconfig['asterisk_version'] == "1_6") {
			$dialstr = str_replace("|", ',', $dialstr);
		}
		// Run dial command
		$res_dial = $agi->exec("DIAL $dialstr");
		
		return $res_dial;
	}
	
};

