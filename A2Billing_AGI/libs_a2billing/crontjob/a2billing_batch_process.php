#!/usr/bin/php -q
<?php 
/***************************************************************************
 *            a2billing_batch_process.php
 *
 *  Fri Oct 28 11:51:08 2005
 *  Copyright  2005  User
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
	crontab -e
	0 12 * * * php /var/lib/asterisk/agi-bin/libs_a2billing/crontjob/a2billing_batch_process.php
	
	field	 allowed values
	-----	 --------------
	minute	 		0-59
	hour		 	0-23
	day of month	1-31
	month	 		1-12 (or names, see below)
	day of week	 	0-7 (0 or 7 is Sun, or use names)

	
****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
//dl("pgsql.so"); // remove "extension= pgsql.so !

include_once (dirname(__FILE__)."/../db_php_lib/Class.Table.php");
include (dirname(__FILE__)."/../Class.A2Billing.php");
include (dirname(__FILE__)."/../Misc.php");

$verbose_level=0;
$groupcard=5000;

if ($A2B->config["database"]['dbtype'] == "postgres"){
	$UNIX_TIMESTAMP = "date_part('epoch',";
}else{
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[#### BATCH BEGIN ####]");

if (!$A2B -> DbConnect()){				
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}
//$A2B -> DBHandle
$instance_table = new Table();


// CHECK AMOUNT OF CARD ON WHICH APPLY THE SERVICE
$QUERY = 'SELECT count(*) FROM cc_card WHERE runservice=1';

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax=(intval($nb_card/$groupcard));
if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card>0)){
	if ($verbose_level>=1) echo "[No card to run the Recurring service]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[No card to run the Recurring service]");
	exit();
}



// CHECK THE SERVICES
$QUERY = 'SELECT id, name, amount, period, rule, daynumber, stopmode, maxnumbercycle, status, numberofrun, datecreate, 
$UNIX_TIMESTAMP datelastrun, emailreport, totalcredit,totalcardperform FROM cc_service WHERE status=1';

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);

if ($verbose_level>=1) print_r ($result);

if( !is_array($result)) {
	echo "[No Recurring service to run]\n";
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[ No Recurring service to run]");
	exit();
}
// 0 id, 1 name, 2 amount, 3 period, 4 rule, 5 daynumber, 6 stopmode,  7 maxnumbercycle, 8 status, 9 numberofrun, 
// 10 datecreate, 11 datelastrun, 12 emailreport, 13 totalcredit, 14 totalcardperform 

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Number of card found : $nb_card]");

$oneday = 60*60*24;

// mail variable for user notification

// BROWSE THROUGH THE SERVICES 
foreach ($result as $myservice) {

	$totalcardperform = 0;
	$totalcredit = 0;
	$timestamp_lastsend = strtotime($myservice[11]);  // 4 aug 1PM
	$datewish = time()- (intval($myservice[3]) * $oneday) - 1800; //minus 30 min   4 aug 1:29PM
	
	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service : ".$myservice[1]." ]");
	
	if ($verbose_level>=1) echo "------>>>   TIME STAMP $datewish < $timestamp_lastsend \n";		
	// Comment if you dont wish to check time of the service running - testing
	 if ($myservice[4]!=3)  if ($datewish < $timestamp_lastsend){        write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service in the Date range : not to run ]"); continue; }


	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service analyze cards on which to apply service ]");
	// BROWSE THROUGH THE CARD TO APPLY THE SERVICE 
	for ($page = 0; $page <= $nbpagemax; $page++) {
		
		$sql = "SELECT id, credit, nbservice, $UNIX_TIMESTAMP lastuse), username, $UNIX_TIMESTAMP servicelastrun), email FROM cc_card WHERE firstusedate IS NOT NULL AND firstusedate>0 AND runservice=1 ORDER BY id  ";
		if ($A2B->config["database"]['dbtype'] == "postgres"){
			$sql .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
		}else{
			$sql .= " LIMIT ".$page*$groupcard.", $groupcard";
		}
		if ($verbose_level>=1) echo "==> SELECT CARD QUERY : $sql\n";
		$result_card = $instance_table -> SQLExec ($A2B -> DBHandle, $sql);
	
		foreach ($result_card as $mycard){
			if ($verbose_level>=1) print_r ($mycard);
			if ($verbose_level>=1) echo "------>>>  ID = ".$mycard[0]." - CARD =".$mycard[4]." - BALANCE =".$mycard[1]." \n";	

			// RULE 3 : Apply the period to card - card last run date >= period
			if ($myservice[4]==3){
				
				$timestamp_servicelastrun = $mycard[5];	 // 4 aug 1PM		
				
				//$datewish = time()- (intval($myservice[5]) * $oneday) - 1800; //minus 30 min   4 aug 1:29PM
				// DATEWISH - already - $datewish = time()- (intval($myservice[3]) * $oneday) - 1800;
				// echo "timestamp_servicelastrun=$timestamp_servicelastrun - mycard_5=$mycard[5] - datewish:$datewish\n";
				if ( ($datewish < $timestamp_servicelastrun) ) {
					if ($verbose_level>=1) echo "#### CARD : NOT - Apply the period to card - card last run date >= period :".$myservice[3]." day(s)\n";
					continue;
				}
				if ($verbose_level>=1) echo "#### CARD : Apply the period to card - card last run date >= period :".$myservice[3]." day(s)\n";
			}
			if ($verbose_level>=1) echo "#### CARD : Apply the period to card - card last run date >= period :".$myservice[3]." day(s)\n";
			if ( ($myservice[4]==1)  || ($myservice[4]==2) ){
				
				$timestamp_lastuse = strtotime($mycard[3]);  // 4 aug 1PM
				$datewish = time()- (intval($myservice[5]) * $oneday) - 1800; //minus 30 min   4 aug 1:29PM
				
				$temp = $datewish < $timestamp_lastuse;					
				if ($verbose_level>=1) echo "------>>>   TIME STAMP $datewish < $timestamp_lastuse = $temp \n";		
				
				// RULE 1 : "User didnt use card since %nextfield% day(s)"
				if ($verbose_level>=1) echo "RULE 1 : User didnt use card since %nextfield% day(s)\n";
				if ( ($myservice[4]==1) && ($datewish < $timestamp_lastuse) && ($myservice[5]>0) ) {
					if ($verbose_level>=1) echo "#### CARD : card used since ".$myservice[5]." day(s)\n";
					continue;
				}
				
				// RULE 2 : "User use the card in the last %nextfield% day(s)"
				if ($verbose_level>=1) echo "RULE 2 : User use the card in the last %nextfield% day(s)\n";
				if ( ($myservice[4]==2) && ($datewish > $timestamp_lastuse) && ($myservice[5]>0) ) {
					if ($verbose_level>=1) echo "#### CARD : User didnt use the card in the last ".$myservice[5]." day(s)\n";
					continue;
				}					
				
			}
			// RULE 0 : NO RULES :D
			
			// CHECK if NBSERVICE > MAXNUMBERCYCLE  && STOPMODE Max number of cycle reach
			if ($mycard[2]>$myservice[7] && $myservice[6]==2) continue;
			
			
			// CHECK if CREDIT <= 0 && STOPMODE Account balance below zero
			if ( $mycard[1]<=0 && $myservice[6]==1 ) continue;
			
			
			$QUERY = "UPDATE cc_card SET nbservice=nbservice+1, credit=credit-'".$myservice[2]."' WHERE id=".$mycard[0];	
			$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
			if ($verbose_level>=1) echo "==> UPDATE CARD QUERY: 	$QUERY\n";
			$totalcardperform ++;
			$totalcredit += $myservice[2];
			//exit();
		}
		// Little bit of rest
		sleep(15);
	}

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service finish]");
	
	// INSERT REPORT SERVICE INTO THE DATABASE
	$QUERY = "INSERT INTO cc_service_report (cc_service_id, totalcardperform, totalcredit, daterun) ".
			 "VALUES ('".$myservice[0]."', '$totalcardperform', '$totalcredit', now())";		
	$result_insert = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
	if ($verbose_level>=1) echo "==> INSERT SERVICE REPORT QUERY=$QUERY\n";

	write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']");
	
	// UPDATE THE SERVICE		
	$QUERY = "UPDATE cc_service SET datelastrun=now(), numberofrun=numberofrun+1, totalcardperform=totalcardperform+".$totalcardperform.
			 ", totalcredit = totalcredit + '".$totalcredit."' WHERE id=".$myservice[0];	
	$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
	if ($verbose_level>=1) echo "==> SERVICE UPDATE QUERY: 	$QUERY\n";
	
	
	// SEND REPORT
	if (strlen($myservice[12])>0){
		$mail_content = "SERVICE NAME = ".$myservice[1];
		$mail_content .= "\n\nTotal card updated = ".$totalcardperform;
		$mail_content .= "\nTotal credit removed = ".$totalcredit;
		mail($myservice[12], "A2BILLING RECURSING SERVICES : REPORT", $mail_content);
	}

} // END FOREACH SERVICES

if ($verbose_level>=1) echo "#### END RECURRING SERVICES \n";
write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__).' line:'.__LINE__."[#### BATCH PROCESS END ####]");
	
?>
