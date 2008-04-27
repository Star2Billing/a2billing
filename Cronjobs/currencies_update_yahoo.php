#!/usr/bin/php -q
<?php 
/***************************************************************************
 *            currencies_update_yahoo.php
 *
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
	crontab -e
	0 6 * * * php /var/lib/asterisk/agi-bin/libs_a2billing/crontjob/currencies_update_yahoo.php
	
	field	 allowed values
	-----	 --------------
	minute	 		0-59
	hour		 	0-23
	day of month	1-31
	month	 		1-12 (or names, see below)
	day of week	 	0-7 (0 or 7 is Sun, or use names)
	
	The sample above will run the script every day at 6AM
	

****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
//dl("pgsql.so"); // remove "extension= pgsql.so !


include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/Misc.php");


$FG_DEBUG=0;


$A2B = new A2Billing();

// SELECT THE FILES TO LOAD THE CONFIGURATION
$A2B -> load_conf($agi, DEFAULT_A2BILLING_CONFIG, 1);	


// DEFINE FOR THE DATABASE CONNECTION
define ("BASE_CURRENCY", strtoupper($A2B->config["global"]['base_currency']));



$A2B -> load_conf($agi, NULL, 0, $idconfig);
//$A2B -> log_file = $A2B -> config["log-files"]['cront_currencies_update'];
//$A2B -> write_log("[START CURRENCY UPDATE]", 0);
write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__."[#### START CURRENCY UPDATE ####]");

if (!$A2B -> DbConnect()){
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}


$instance_table = new Table();
$A2B -> set_instance_table ($instance_table);
$return = $A2B -> currencies_update_yahoo($A2B -> DBHandle, $A2B -> instance_table);
write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__.$return, 0);

?>
