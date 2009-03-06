#!/usr/bin/php -q
<?php

/***************************************************************************
 *            a2billing_batch_process_alt.php
 * 
 *  Mar 03 2009
 *  Copyright  2005  Arheops & Areski Belaid
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 * 
 *  Description : 
 *  This script will take care of the recurring service. This script is alternative script.
 *  It use more RAM and more sql power, but offer significal speedup when used on large database.
 *
 *
	crontab -e
	0 12 * * * php /usr/local/a2billing/Cronjobs/a2billing_batch_process_alt.php
	
	field	 allowed values
	-----	 --------------
	minute	 		0-59
	hour		 	0-23
	day of month		1-31
	month	 		1-12 (or names, see below)
	day of week	 	0-7 (0 or 7 is Sun, or use names)

	
****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include_once (dirname(__FILE__) . "/lib/Class.Table.php");
include (dirname(__FILE__) . "/lib/interface/constants.php");
include (dirname(__FILE__) . "/lib/Class.A2Billing.php");
include (dirname(__FILE__) . "/lib/Misc.php");

$verbose_level = 1;
$groupcard = 5000;
$time_checks = 20; #number of minute to check with. i.e if time1-time2< $time_checks minutes, consider it is equal.
# this value must be greater then script run time. used only when checked if service msut be run.
$run = 1; #set to 0 if u want to just report, no updates. must be set to 1 on productional

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

if ($A2B->config["database"]['dbtype'] == "postgres") {
	$UNIX_TIMESTAMP = "date_part('epoch',";
} else {
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
	exit;
}

$instance_table = new Table();

$oneday = 60 * 60 * 24;

// CHECK THE SERVICES
$QUERY = "SELECT DISTINCT id, name, amount, period, rule, daynumber, stopmode, maxnumbercycle, status, numberofrun, datecreate, $UNIX_TIMESTAMP datelastrun), emailreport, totalcredit,totalcardperform,dialplan,operate_mode,use_group FROM cc_service WHERE status=1 AND  $UNIX_TIMESTAMP cc_service.datelastrun)<$UNIX_TIMESTAMP CURRENT_TIMESTAMP) - $oneday  + $time_checks *60  ORDER BY id DESC";
if ($verbose_level >= 1)
	echo $QUERY;
$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);
if ($verbose_level >= 1)
	print_r($result);

if (!is_array($result)) {
	echo "[No Recurring service to run]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[ No Recurring service to run]");
	exit ();
}
// 0 id, 1 name, 2 amount, 3 period, 4 rule, 5 daynumber, 6 stopmode,  7 maxnumbercycle, 8 status, 9 numberofrun, 
// 10 datecreate, 11 datelastrun, 12 emailreport, 13 totalcredit, 14 totalcardperform 
// 15 dialplan 16 operate_mode

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Number of card found : $nb_card]");

// mail variable for user notification

// BROWSE THROUGH THE SERVICES 
foreach ($result as $myservice) {

	$totalcardperform = 0;
	$totalcredit = 0;
	$timestamp_lastsend = $myservice[11]; // 4 aug 1PM

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Service : " . $myservice[1] . " ]");
	$filters = "";
	$service_name = $myservice[1];
	$period = $myservice[3];
	$rule = $myservice[4];
	$rule_day = $myservice[5];
	$stopmode = $myservice[6];
	$maxnumbercycle = $myservice[7];
	$dialplan = $myservice[15];
	$operate_mode = $myservice[16];
	$use_group = $myservice[17];
	$amount = $myservice[2];
	$filter = "";
	if ($verbose_level >= 1)
		echo "[ rule $rule  $rule_day ]";
			
	// RULES  
	if ($rule == 3) {
		$filter .= " -- card last run date <= period
		 		AND $UNIX_TIMESTAMP servicelastrun) <= $UNIX_TIMESTAMP CURRENT_TIMESTAMP) - $oneday * $period ";
	}
	if (($rule == 1) && ($rule_day > 0)) {
		$filter .= " -- Apply service if card NO used in last y days
				AND $UNIX_TIMESTAMP lastuse) < $UNIX_TIMESTAMP CURRENT_TIMESTAMP) - $oneday ";
	}
	if (($rule == 2) && ($rule_day > 0)) {
		$filter .= " -- Apply service if card used in last y days
		                AND $UNIX_TIMESTAMP lastuse) >= $UNIX_TIMESTAMP CURRENT_TIMESTAMP) - $oneday ";
	}
	//stopmode variants
	if ($stopmode == 2) {
		$filter .= " -- NBSERVICE <= MAXNUMBERCYCLE  STOPMODE Max number of cycle reach
				AND nbservice <= " . $myservice[7];
	}
	if ($stopmode == 1) {
		$filter .= " -- CREDIT <= 0 STOPMODE Account balance below zero
		                AND credit>0 ";
	}
	// dialplan
	if ($dialplan > 0) {
		$filter .= " -- dialplan check
				AND tariff = $dialplan ";
	}
	$sql = "";
	if ($use_group == 0) {
		$sql = "SELECT id, credit, nbservice, lastuse, username, servicelastrun, email
				 		FROM cc_card , cc_cardgroup_service WHERE id_group = id_card_group AND id_service = " . $myservice[0] . 
						" AND firstusedate IS NOT NULL AND firstusedate>'1984-01-01 00:00:00' AND runservice=1 $filter";
	} else {
		$sql = "SELECT id, credit, nbservice, lastuse, username, servicelastrun, email
		                 FROM cc_card where firstusedate IS NOT NULL AND firstusedate>'1984-01-01 00:00:00' AND runservice=1  $filter";
	}
	if ($verbose_level >= 1)
		echo "==> SELECT CARD QUERY : $sql\n";

	$result_card = $instance_table->SQLExec($A2B->DBHandle, $sql);

	foreach ($result_card as $mycard) {
		if ($verbose_level >= 1)
			print_r($mycard);
		$card_id = $mycard[0];
		if ($verbose_level >= 1)
			echo "------>>>  ID = $card_id - CARD =" . $mycard[4] . " - BALANCE =" . $mycard[1] . " \n";

		// UPDATE THE CARD CREDIT AND SERVICE LAST RUN
		$refill_amount = 0;
		if ($operate_mode == 1) {
			$credit_sql = " case when credit<$amount and credit >0  then 0 when credit<=0 then credit else credit-$amount end ";
			$current_amount = $mycard[1];
			if ($current_amount > $amount) {
				$refill_amount = $amount;
			} else {
				if ($current_amount > 0) {
					$refill_amount = $current_amount;
				}
			}
		} else {
			$credit_sql = " credit-amount ";
			$$refill_amount = $amount;
		}
		if ($refill_amount > 0) {
			$QUERY = "INSERT INTO cc_logrefill (credit,card_id,description,refill_type) VALUES (-$refill_amount,$card_id,'Recurrent $service_name ',2)";
			if ($verbose_level >= 1)
				echo "==> CARD REFILL QUERY:    $QUERY\n";
			if ($run) {
				$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
			}
			$totalcredit += $refill_amount;
		}
		$QUERY = "UPDATE cc_card SET nbservice=nbservice+1, credit= $credit_sql, servicelastrun=now() WHERE id=" . $mycard[0];
		if ($run) {
			$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
		}
		if ($verbose_level >= 1)
			echo "==> UPDATE CARD QUERY: 	$QUERY\n";
		$totalcardperform++;
	}

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Service finish]");

	// INSERT REPORT SERVICE INTO THE DATABASE
	$QUERY = "INSERT INTO cc_service_report (cc_service_id, totalcardperform, totalcredit, daterun) " .
	"VALUES ('" . $myservice[0] . "', '$totalcardperform', '$totalcredit', now())";
	if ($run) {
		$result_insert = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
	}
	if ($verbose_level >= 1)
		echo "==> INSERT SERVICE REPORT QUERY=$QUERY\n";

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']");

	// UPDATE THE SERVICE		
	$QUERY = "UPDATE cc_service SET datelastrun=now(), numberofrun=numberofrun+1, totalcardperform=totalcardperform+" . $totalcardperform .
	", totalcredit = totalcredit + '" . $totalcredit . "' WHERE id=" . $myservice[0];
	if ($run) {
		$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
	}
	if ($verbose_level >= 1)
		echo "==> SERVICE UPDATE QUERY: 	$QUERY\n";

	// SEND REPORT
	if (strlen($myservice[12]) > 0) {
		$mail_content = "SERVICE NAME = " . $myservice[1];
		$mail_content .= "\n\nTotal card updated = " . $totalcardperform;
		$mail_content .= "\nTotal credit removed = " . $totalcredit;
		mail($myservice[12], "RECURRING SERVICES : REPORT", $mail_content);
	}

} // END FOREACH SERVICES

if ($verbose_level >= 1)
	echo "#### END RECURRING SERVICES \n";

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH PROCESS END ####]");



