#!/usr/bin/php -q
<?php 
/***************************************************************************
 *            a2billing_autorefill.php
 *
 *  Fri June 29 2006
 *  A2Billing Copyright  2006 
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
	crontab -e
	0 10 21 * * php /var/lib/asterisk/agi-bin/libs_a2billing/crontjob/a2billing_autorefill.php
	
	field	 allowed values
	-----	 --------------
	minute	 		0-59
	hour		 	0-23
	day of month	1-31
	month	 		1-12 (or names, see below)
	day of week	 	0-7 (0 or 7 is Sun, or use names)
	
	The sample above will run the script every 21 of each month at 10AM
	
****************************************************************************/
set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
//dl("pgsql.so"); // remove "extension= pgsql.so !	

include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include (dirname(__FILE__)."/lib/Misc.php");

$verbose_level=0;

$groupcard=5000;


$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[#### BATCH BEGIN ####]");

if (!$A2B -> DbConnect()){				
			echo "[Cannot connect to the database]\n";
			write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
			exit;						
}
//$A2B -> DBHandle
$instance_table = new Table();


// CHECK NUMBER OF CARD
$QUERY = 'SELECT count(*) FROM cc_card WHERE autorefill=1 AND initialbalance>0 AND credit<initialbalance';

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax=(ceil($nb_card/$groupcard));
if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card>0)){
		if ($verbose_level>=1) echo "[No card to run the Auto Refill]\n";
		write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[No card to run the Auto Refill]");
		exit();
}


if ($A2B->config["database"]['dbtype'] == "postgres"){		
	$UNIX_TIMESTAMP = "date_part('epoch',";
}else{
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[Number of card found : $nb_card]");


$totalcardperform = 0;
$totalcredit = 0;
		
	
	

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[Analyze cards to apply Auto Refill]");

// BROWSE THROUGH THE CARD TO APPLY THE AUTO REFILL
for ($page = 0; $page < $nbpagemax; $page++) {
	
	$sql = "SELECT id, username, credit, initialbalance, initialbalance-credit as refillof FROM cc_card WHERE autorefill=1 AND initialbalance>0 AND credit<initialbalance ORDER BY id ";
	if ($A2B->config["database"]['dbtype'] == "postgres"){
		$sql .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
	}else{
		$sql .= " LIMIT ".$page*$groupcard.", $groupcard";
	}
	if ($verbose_level>=1) echo "==> SELECT CARD QUERY : $sql\n";
	$result_card = $instance_table -> SQLExec ($A2B -> DBHandle, $sql);
	
	foreach ($result_card as $mycard){
		
		if ($verbose_level>=1) print_r ($mycard);
		if ($verbose_level>=1) echo "------>>>  ID = ".$mycard[0]." - CARD =".$mycard[1]." - BALANCE =".$mycard[2]." - REFILLOF =".$mycard[4]." \n";	
		
		$QUERY = "UPDATE cc_card SET credit=credit+".$mycard[4]." WHERE id=".$mycard[0];
		$totalcredit += $mycard[4];
		
		$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
		if ($verbose_level>=1) echo "==> UPDATE CARD QUERY: 	$QUERY\n";
		$totalcardperform ++;
		
		
		// INSERT LOG REFILL INTO THE DATABASE
		$QUERY = "INSERT INTO cc_logrefill (credit, card_id, date) VALUES ('$mycard[4]', '$mycard[0]', now())";	
		$result_insert = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
		if ($verbose_level>=1) echo "==> INSERT LOG REFILL QUERY=$QUERY\n";
		
		//exit();
	}
	// Little bit of rest
	sleep(15);
}

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[Auto Refill finish]");



// INSERT REPORT SERVICE INTO THE DATABASE
$QUERY = "INSERT INTO cc_autorefill_report (totalcardperform, totalcredit, daterun) ".
		 "VALUES ('$totalcardperform', '$totalcredit', now())";		
$result_insert = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
if ($verbose_level>=1) echo "==> INSERT SERVICE REPORT QUERY=$QUERY\n";

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']");



// SEND REPORT
if (strlen($A2B->config["webui"]["email_admin"])>4 && eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $A2B->config["webui"]["email_admin"])){
	$mail_content = "AUTO REFILL";
	$mail_content .= "\n\nTotal card updated = ".$totalcardperform;
	$mail_content .= "\nTotal credit added = ".$totalcredit;
	mail($A2B->config["webui"]["email_admin"], "A2BILLING AUTO REFILL : REPORT", $mail_content);			
	if ($verbose_level>=1) echo "MAIL CONTENT (".$A2B->config["webui"]["email_admin"].") : $mail_content\n";
}


if ($verbose_level>=1) echo "#### END AUTO REFILL \n";
write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__).' line:'.__LINE__."[#### AUTO REFILL PROCESS END ####]");
	
?>
