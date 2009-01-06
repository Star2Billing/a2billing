#!/usr/bin/php -q
<?php 
/***************************************************************************
 *            a2billing_subscription_fee.php
 *
 *  Fri Feb 27 14:17:10 2007 (in the train from Jemappes to Bruxelles)
 *  Copyright  2007  User : Areski
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
	crontab -e
	0 6 1 * * php /usr/local/a2billing/Cronjobs/a2billing_subscription_fee.php
	
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

include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/interface/constants.php");
include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include (dirname(__FILE__)."/lib/Misc.php");

$verbose_level=0;

$groupcard=5000;


$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

if ($A2B->config["database"]['dbtype'] == "postgres"){
	$UNIX_TIMESTAMP = "date_part('epoch',";
}else{
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[#### BATCH BEGIN ####]");

if (!$A2B -> DbConnect()){				
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;						
}

$instance_table = new Table();


// CHECK AMOUNT OF CARD ON WHICH APPLY THE SERVICE
//$QUERY = 'SELECT count(*) FROM cc_card LEFT JOIN cc_subscription_fee ON cc_card.id_subscription_fee=cc_subscription_fee.id WHERE cc_subscription_fee.status=1';

$QUERY = 'SELECT count(*) FROM cc_card_subscription JOIN cc_subscription_fee ON cc_card_subscription.id_subscription_fee=cc_subscription_fee.id'.
 		 ' WHERE cc_subscription_fee.status=1 AND startdate < NOW() AND (stopdate = "0000-00-00 00:00:00" OR stopdate > NOW())';

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax=(ceil($nb_card/$groupcard));
if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card>0)){
	if ($verbose_level>=1) echo "[No card to run the Subscription Fee service]\n";
	write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[No card to run the Subscription Feeservice]");
	exit();
}


// CHECK THE SUBSCRIPTION SERVICES
$QUERY = 'SELECT id, label, fee, currency, emailreport FROM cc_subscription_fee WHERE status=1 ORDER BY id ';

$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);

if ($verbose_level>=1) print_r ($result);

if( !is_array($result)) {
		echo "[No Recurring service to run]\n";
		write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[ No Recurring service to run]");
		exit();
}

write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[Number of card found : $nb_card]");

$oneday = 60*60*24;

$currencies_list = get_currencies($A2B -> DBHandle);

// BROWSE THROUGH THE SERVICES 
foreach ($result as $myservice) {

	$totalcardperform = 0;
	$totalcredit = 0;
	$totalcredit_converted = 0;
	
	$myservice_id = $myservice[0];
	$myservice_label = $myservice[1];
	$myservice_fee = $myservice[2];
	$myservice_cur = $myservice[3];
	
	write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[Subscription Fee Service No ".$myservice_id." analyze cards on which to apply service ]");
	// BROWSE THROUGH THE CARD TO APPLY THE SUBSCRIPTION FEE SERVICE 
	for ($page = 0; $page < $nbpagemax; $page++) {
		
		$sql = "SELECT cc_card.id, credit, currency, username, email FROM cc_card JOIN cc_card_subscription ON cc_card.id = cc_card_subscription.id_cc_card ".
			   "WHERE id_subscription_fee='$myservice_id' AND startdate < NOW() AND (stopdate = '0000-00-00 00:00:00' OR stopdate > NOW()) ORDER BY id ";

		if ($A2B->config["database"]['dbtype'] == "postgres"){
			$sql .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
		}else{
			$sql .= " LIMIT ".$page*$groupcard.", $groupcard";
		}
		if ($verbose_level>=1) echo "==> SELECT CARD QUERY : $sql\n";
		$result_card = $instance_table -> SQLExec ($A2B -> DBHandle, $sql);
		
		foreach ($result_card as $mycard){
			if ($verbose_level>=1) print_r ($mycard);
			if ($verbose_level>=1) echo "------>>>  ID = ".$mycard[0]." - CARD =".$mycard[3]." - BALANCE =".$mycard[1]." \n";	
			
			$amount_converted = convert_currency ($currencies_list, $myservice_fee, strtoupper($myservice_cur), strtoupper($mycard[2]));
			
			if ($verbose_level>=1) echo "AMOUNT TO REMOVE FROM THE CARD ->".$amount_converted;
			if (abs($amount_converted) > 0){	// CHECK IF WE HAVE AN AMOUNT TO REMOVE
				$QUERY = "UPDATE cc_card SET credit=credit-'".$myservice_fee."' WHERE id=".$mycard[0];	
				$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
				if ($verbose_level>=1) echo "==> UPDATE CARD QUERY: 	$QUERY\n";
				
				// ADD A CHARGE
				$QUERY = "INSERT INTO cc_charge (id_cc_card, id_cc_subscription_fee, chargetype, amount, currency, description) ".
						 "VALUES ('".$mycard[0]."', '$myservice_id', '3', '$amount_converted', '".strtoupper($mycard[2])."','".$mycard[5].' - '.$myservice_label."')";
				$result_insert = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
				if ($verbose_level>=1) echo "==> INSERT CHARGE QUERY=$QUERY\n";
				
				$totalcardperform ++;
				$totalcredit += $myservice_fee;
				$totalcredit_converted += $amount_converted;
			}
		}
		
		// Little bit of rest
		sleep(15);
	}
	
	write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[Service finish]");
	
	write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']");
	if ($verbose_level>=1) echo "[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']";
	
	// UPDATE THE SERVICE		
	$QUERY = "UPDATE cc_subscription_fee SET datelastrun=now(), numberofrun=numberofrun+1, totalcardperform=totalcardperform+".$totalcardperform.
			 ", totalcredit = totalcredit + '$totalcredit' WHERE id=$myservice_id";	
	$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY, 0);
	if ($verbose_level>=1) echo "==> SERVICE UPDATE QUERY: 	$QUERY\n";
	
	
	// SEND REPORT
	if (strlen($myservice[4])>0){
		$mail_content = "SUBSCRIPTION SERVICE NAME = ".$myservice[1];
		$mail_content .= "\n\nTotal card updated = ".$totalcardperform;
		$mail_content .= "\nTotal credit removed = ".$totalcredit;
		mail($myservice[4], "A2BILLING SUBSCRIPTION SERVICES : REPORT", $mail_content);
	}

} // END FOREACH SERVICES


if ($verbose_level>=1) echo "#### END SUBSCRIPTION SERVICES \n";
write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__).' line:'.__LINE__."[#### BATCH PROCESS END ####]");
	
	
