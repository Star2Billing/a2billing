#!/usr/bin/php -q
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
 * @contributor Steve Dommett <steve@st4vs.net> 
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

include (dirname(__FILE__) . "/lib/admin.defines.php");

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

$verbose_level = 0;
$groupcard = 5000;

$min_credit = 5;

write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
	exit;
}

$instance_table = new Table();

// CHECK AMOUNT OF CARD ON WHICH APPLY THE CHECK ACCOUNT SERVICE
$QUERY = "SELECT count(*) FROM cc_card WHERE status=1 AND credit < $min_credit AND email <> ''";
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

	$sql = "SELECT id, credit, username, email FROM cc_card WHERE status=1 AND credit < $min_credit AND email <> '' ORDER BY id";
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
			echo "------>>>  ID = " . $mycard[0] . " - CARD =" . $mycard[2] . " - BALANCE =" . $mycard[1]. " - EMAIL =" . $mycard[3] . " \n";
		
		if (strlen($mycard[3]) > 5) {
	        try {
	            $mail = new Mail(Mail::$TYPE_REMINDERCALL, $mycard[0]);
	            $mail -> send();
	        } catch (A2bMailException $e) {
	        	if ($verbose_level >= 1)
	        		echo "[Sent mail failed : $e]";
	        	write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
	        }
		}
	}
	// Little bit of rest
	sleep(15);
}

if ($verbose_level >= 1)
	echo "#### END RECURRING CHECK ACCOUNT \n";
write_log(LOGFILE_CRONT_CHECKACCOUNT, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH PROCESS END ####]");

