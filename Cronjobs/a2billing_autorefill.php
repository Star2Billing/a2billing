#!/usr/bin/php -q
<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

/***************************************************************************
 *            a2billing_autorefill.php
 *
 *  Fri June 29 2006
 *  A2Billing Copyright  2006
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
    crontab -e
    0 10 21 * * php /usr/local/a2billing/Cronjobs/a2billing_autorefill.php

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

include (dirname(__FILE__) . "/lib/admin.defines.php");
include (dirname(__FILE__) . "/lib/ProcessHandler.php");

if (!defined('PID')) {
    define("PID", "/var/run/a2billing/a2billing_autorefill_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING
$pH= new ProcessHandler();
if ($pH->isActive()) {
        die(); // Already running!
        } else {
                $pH->activate();
                }

$verbose_level = 0;
$groupcard = 5000;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}
//$A2B -> DBHandle
$instance_table = new Table();

// CHECK NUMBER OF CARD
$QUERY = 'SELECT count(*) FROM cc_card WHERE autorefill=1 AND ((typepaid=0 AND initialbalance>0 AND credit<initialbalance) OR (typepaid=1))';

$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax = (ceil($nb_card / $groupcard));
if ($verbose_level >= 1)
    echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card > 0)) {
    if ($verbose_level >= 1)
        echo "[No card to run the Auto Refill]\n";
    write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the Auto Refill]");
    exit ();
}

if ($A2B->config["database"]['dbtype'] == "postgres") {
    $UNIX_TIMESTAMP = "date_part('epoch',";
} else {
    $UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[Number of card found : $nb_card]");

$totalcardperform = 0;
$totalcredit = 0;

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[Analyze cards to apply Auto Refill]");

// BROWSE THROUGH THE CARD TO APPLY THE AUTO REFILL
for ($page = 0; $page < $nbpagemax; $page++) {

    $sql = "SELECT id, username, credit, initialbalance, initialbalance-credit as refillof FROM cc_card WHERE autorefill=1 AND ((typepaid=0 AND initialbalance>0 AND credit<initialbalance) OR (typepaid=1)) ORDER BY id ";
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
            echo "------>>>  ID = " . $mycard[0] . " - CARD =" . $mycard[1] . " - BALANCE =" . $mycard[2] . " - REFILLOF =" . $mycard[4] . " \n";

        $QUERY = "UPDATE cc_card SET credit=credit+" . $mycard[4] . " WHERE id=" . $mycard[0];
        $totalcredit += $mycard[4];

        $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
        if ($verbose_level >= 1)
            echo "==> UPDATE CARD QUERY: 	$QUERY\n";
        $totalcardperform++;

        // INSERT LOG REFILL INTO THE DATABASE
        $QUERY = "INSERT INTO cc_logrefill (credit, card_id, date) VALUES ('$mycard[4]', '$mycard[0]', now())";
        $result_insert = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
        if ($verbose_level >= 1)
            echo "==> INSERT LOG REFILL QUERY=$QUERY\n";

        //exit();
    }
    // Little bit of rest
    sleep(15);
}

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[Auto Refill finish]");

// INSERT REPORT SERVICE INTO THE DATABASE
$QUERY = "INSERT INTO cc_autorefill_report (totalcardperform, totalcredit, daterun) " .
"VALUES ('$totalcardperform', '$totalcredit', now())";
$result_insert = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
if ($verbose_level >= 1)
    echo "==> INSERT SERVICE REPORT QUERY=$QUERY\n";

write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[Service report : 'totalcardperform=$totalcardperform', 'totalcredit=$totalcredit']");

// SEND REPORT
if (strlen($A2B->config["webui"]["email_admin"]) > 4 && preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $A2B->config["webui"]["email_admin"])) {
    $mail_subject = "A2BILLING AUTO REFILL : REPORT";

    $mail_content = "AUTO REFILL";
    $mail_content .= "\n\nTotal card updated = " . $totalcardperform;
    $mail_content .= "\nTotal credit added = " . $totalcredit;

    try {
        $mail = new Mail(null, null, null, $mail_content, $mail_subject);
        $mail -> send($A2B->config["webui"]["email_admin"]);
        if ($verbose_level >= 1)
            echo "MAIL CONTENT (" . $A2B->config["webui"]["email_admin"] . ") : $mail_content\n";
    } catch (A2bMailException $e) {
        if ($verbose_level >= 1)
            echo "[Sent mail failed : $e]";
        write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
    }
}

if ($verbose_level >= 1)
    echo "#### END AUTO REFILL \n";
write_log(LOGFILE_CRONT_AUTOREFILL, basename(__FILE__) . ' line:' . __LINE__ . "[#### AUTO REFILL PROCESS END ####]");
