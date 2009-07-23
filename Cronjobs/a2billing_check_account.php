#!/usr/bin/php -q
<?php

/***************************************************************************
 *            a2billing_check_account.php
 *
 *  Purpose: To check all the accounts and send an notification email if the balance is less than the first argument.
 *  Copyright  2009 @ Belaid Arezqui
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  The sample above will run the script every day of each month at 6AM
	crontab -e
	0 * / 6 * * php /usr/local/a2billing/Cronjobs/a2billing_check_account.php
	
	
	field	 allowed values
	-----	 --------------
	minute	 0-59
	hour		 0-23
	day of month	 1-31
	month	 1-12 (or names, see below)
	day of week	 0-7 (0 or 7 is Sun, or use names)
	
****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include_once (dirname(__FILE__) . "/lib/Class.Table.php");
include (dirname(__FILE__) . "/lib/interface/constants.php");
include (dirname(__FILE__) . "/lib/Class.A2Billing.php");
include (dirname(__FILE__) . "/lib/Misc.php");

$verbose_level = 0;
$groupcard = 5000;

$min_credit = 5;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
	exit;
}
//$A2B -> DBHandle
$instance_table = new Table();

$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext FROM cc_templatemail WHERE mailtype='reminder' ";
$listtemplate = $instance_table->SQLExec($A2B->DBHandle, $QUERY);
if (!is_array(listtemplate)) {
	echo "[Cannot find a template mail for reminder]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot find a template mail for reminder]");
	exit;
}

list ($mailtype, $from, $fromname, $subject, $messagetext) = $listtemplate[0];
if ($FG_DEBUG == 1)
	echo "<br><b>mailtype : </b>$mailtype</br><b>from:</b> $from</br><b>fromname :</b> $fromname</br><b>subject</b> : $subject</br><b>ContentTemplate:</b></br><pre>$messagetext</pre></br><hr>";

// CHECK AMOUNT OF CARD ON WHICH APPLY THE CHECK ACCOUNT SERVICE
$QUERY = "SELECT count(*) FROM cc_card WHERE activated='1' AND credit < $min_credit";

$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax = (ceil($nb_card / $groupcard));
if ($verbose_level >= 1)
	echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card > 0)) {
	if ($verbose_level >= 1)
		echo "[No card to run the Recurring service]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the check account service]");
	exit ();
}

write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[Number of card found : $nb_card]");

// BROWSE THROUGH THE CARD TO APPLY THE CHECK ACCOUNT SERVICE 
for ($page = 0; $page < $nbpagemax; $page++) {

	$sql = "SELECT id, credit, username, email FROM cc_card WHERE activated='1' AND credit < $min_credit ORDER BY id  ";
	if ($A2B->config["database"]['dbtype'] == "postgres") {
		$sql .= " LIMIT $groupcard OFFSET " . $page * $groupcard;
	} else {
		$sql .= " LIMIT " . $page * $groupcard . ", $groupcard";
	}
	if ($verbose_level >= 1)
		echo "==> SELECT CARD QUERY : $sql\n";
	$result_card = $instance_table->SQLExec($A2B->DBHandle, $sql);

	foreach ($result_card as $mycard) {
		if ($verbose_level >= 1)
			print_r($mycard);
		if ($verbose_level >= 1)
			echo "------>>>  ID = " . $mycard[0] . " - CARD =" . $mycard[2] . " - BALANCE =" . $mycard[1] . " \n";

		// SEND NOTIFICATION
		if (strlen($mycard[3]) > 0) { // ADD CHECK EMAIL
			$mail_tile = $mail_content = "CREDIT LOW : You have less than $min_credit";
			mail($mycard[12], $mail_tile, $mail_content);
		}

	}
	// Little bit of rest
	sleep(15);
}

if ($verbose_level >= 1)
	echo "#### END RECURRING CHECK ACCOUNT \n";
write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH PROCESS END ####]");

