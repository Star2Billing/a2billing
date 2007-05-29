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


include (dirname(__FILE__)."/../Class.A2Billing.php");
include (dirname(__FILE__)."/../db_php_lib/Class.Table.php");
include (dirname(__FILE__)."/../Misc.php");


$FG_DEBUG=0;


$A2B = new A2Billing();

// SELECT THE FILES TO LOAD THE CONFIGURATION
$A2B -> load_conf($agi, DEFAULT_A2BILLING_CONFIG, 1);	


// DEFINE FOR THE DATABASE CONNECTION
define ("BASE_CURRENCY", strtoupper($A2B->config["global"]['base_currency']));

// get in a csv file USD to EUR and USD to CAD
// http://finance.yahoo.com/d/quotes.csv?s=USDEUR=X+USDCAD=X&f=l1


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

$QUERY =  "SELECT id,currency,basecurrency FROM cc_currencies ORDER BY id";
$result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY);
	
$url = "http://finance.yahoo.com/d/quotes.csv?s=";

/* result[index_result][field] */

$index_base_currency = 0;

if (is_array($result)){
	$num_cur = count($result);
	write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__."[CURRENCIES TO UPDATE = $num_cur]", 0);
	for ($i=0;$i<$num_cur;$i++){
		if ($FG_DEBUG >= 1) echo $result[$i][0].' - '.$result[$i][1].' - '.$result[$i][2]."\n";
		// Finish and add termination ? 
		if ($i+1 == $num_cur) $url .= BASE_CURRENCY.$result[$i][1]."=X&f=l1";
		else $url .= BASE_CURRENCY.$result[$i][1]."=X+";
		
		// Check what is the index of BASE_CURRENCY to save it 
		if (strcasecmp(BASE_CURRENCY, $result[$i][1]) == 0) {
			$index_base_currency = $result[$i][0];
		}
	}
	
	// Create the script to get the currencies
	exec("wget '".$url."' -O /tmp/currencies.cvs  2>&1", $output);
	if ($FG_DEBUG >= 1) echo "wget '".$url."' -O /tmp/currencies.cvs";
	
	// get the file with the currencies to update the database
	$currencies = file("/tmp/currencies.cvs");
	
	// update database
	foreach ($currencies as $currency){
		
		$currency = trim($currency);
		
		if (!is_numeric($currency)){ 
			continue; 
		}
		$id++;
		// if the currency is BASE_CURRENCY the set to 1
		if ($id == $index_base_currency) $currency = 1;
		
		if ($currency!=0) $currency=1/$currency;
		$QUERY="UPDATE cc_currencies SET value=".$currency;
		
		if (BASE_CURRENCY != $result[$i][2]){
			$QUERY .= ", basecurrency='".BASE_CURRENCY."'";
		}
		$QUERY .= " , lastupdate = CURRENT_TIMESTAMP WHERE id =".$id;
		
		$result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $QUERY, 0);
		if ($FG_DEBUG >= 1) echo "$QUERY \n"; 
		//if ($id == 5) exit;
	}
	write_log(LOGFILE_CRONT_CURRENCY_UPDATE, basename(__FILE__).' line:'.__LINE__."[CURRENCIES UPDATED !!!]", 0);
}

?>
