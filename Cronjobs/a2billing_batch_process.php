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
 *            a2billing_batch_process_alt.php
 *
 *  Copyright  2009 @  Arheops & Areski Belaid
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  Description :
 *  This script will take care of the recurring service.
 *
 *
    crontab -e
    0 12 * * * php /usr/local/a2billing/Cronjobs/a2billing_batch_process.php

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

include (dirname(__FILE__) . "/lib/admin.defines.php");
include (dirname(__FILE__) . "/lib/ProcessHandler.php");

if (!defined('PID')) {
    define("PID", "/var/run/a2billing/a2billing_batch_process_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING
$pH= new ProcessHandler();
if ($pH->isActive()) {
        die(); // Already running!
        } else {
                $pH->activate();
                }

$verbose_level = 0;
$groupcard = 1000;
$groupwait = 10; // number of second to wait if more then  groupcard updates done
$time_checks = 20; // number of minute to check with. i.e if time1-time2< $time_checks minutes, consider it is equal.
// this value must be greater then script run time. used only when checked if service msut be run.
$run = 1; // set to 0 if u want to just report, no updates. must be set to 1 on productional

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}

$instance_table = new Table();

$oneday = 60 * 60 * 24;
$service_lastrun = "AND UNIX_TIMESTAMP(cc_service.datelastrun) < UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - $oneday  + $time_checks *60 ";

// CHECK THE SERVICES
$QUERY = "SELECT DISTINCT id, name, amount, period, rule, daynumber, stopmode, maxnumbercycle, status, numberofrun, datecreate, " .
        "UNIX_TIMESTAMP(datelastrun), emailreport, totalcredit, totalcardperform, dialplan, operate_mode, use_group " .
        "FROM cc_service " .
        "WHERE status=1 $service_lastrun ORDER BY id DESC";
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
// 10 datecreate, 11 datelastrun, 12 emailreport, 13 totalcredit, 14 totalcardperform, 15 dialplan 16 operate_mode

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Number of card found : $nb_card]");

// mail variable for user notification

// BROWSE THROUGH THE SERVICES
foreach ($result as $myservice) {

    $totalcardperform = 0;
    $totalcredit = 0;
    $timestamp_lastsend = $myservice[11]; // 4 aug 1PM

    write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Service : " . $myservice[1] . " ]");
    $filters 		= '';
    $service_name 	= $myservice[1];
    $period 		= $myservice[3];
    $rule 			= $myservice[4];
    $rule_day 		= $myservice[5];
    $stopmode 		= $myservice[6];
    $maxnumbercycle = $myservice[7];
    $dialplan		= $myservice[15];
    $operate_mode	= $myservice[16];
    $use_group		= $myservice[17];
    $amount			= $myservice[2];
    $filter			= '';
    if ($verbose_level >= 1)
        echo "[ rule $rule  $rule_day ]";

    // RULES
    if ($rule == 3) {
        $filter .= " -- card last run date <= period
                 AND UNIX_TIMESTAMP(servicelastrun) <= UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - $oneday * $period \n";
    }
    if (($rule == 1) && ($rule_day > 0)) {
        $filter .= " -- Apply service if card NO used in last y days
                AND UNIX_TIMESTAMP(lastuse) < UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - $oneday \n";
    }
    if (($rule == 2) && ($rule_day > 0)) {
        $filter .= " -- Apply service if card used in last y days
                        AND UNIX_TIMESTAMP(lastuse) >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - $oneday \n";
    }
    //stopmode variants
    if ($stopmode == 2) {
        $filter .= " -- NBSERVICE <= MAXNUMBERCYCLE  STOPMODE Max number of cycle reach
                AND nbservice <= " . $myservice[7] ."\n";
    }
    if ($stopmode == 1) {
        $filter .= " -- CREDIT <= 0 STOPMODE Account balance below zero
                        AND credit>0 \n";
    }
    // dialplan
    if ($dialplan > 0) {
        $filter .= " -- dialplan check
                AND tariff = $dialplan \n";
    }
    $sql = "";
    $first_usedate = " AND firstusedate IS NOT NULL AND firstusedate>'1984-01-01 00:00:00'";
    if ($use_group == 0) {
        $sql = "SELECT id, credit, nbservice, lastuse, username, servicelastrun, email
                         FROM cc_card , cc_cardgroup_service WHERE id_group = id_card_group AND id_service = " . $myservice[0] .
                        " $first_usedate AND runservice=1 $filter \n";
    } else {
        $sql = "SELECT id, credit, nbservice, lastuse, username, servicelastrun, email
                         FROM cc_card WHERE runservice=1 $first_usedate $filter \n";
    }

    if ($verbose_level >= 1)
        echo "==> SELECT CARD QUERY : $sql\n";

    $result_card = $instance_table->SQLExec($A2B->DBHandle, $sql);
    $instance_table->SQLExec($A2B->DBHandle, "begin;");
    $query_count=0;

    foreach ($result_card as $mycard) {

        $query_count=$query_count+1;

        if ($query_count>=$groupcard) {
            $instance_table->SQLExec($A2B->DBHandle, "commit");
            if ($verbose_level >= 1)
                            echo "------>|< commit & wait \n";
            sleep($groupwait);
            $instance_table->SQLExec($A2B->DBHandle, "begin;");
            $query_count=0;
        }

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
            $credit_sql = " credit-$amount ";
            $refill_amount = $amount;
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

    $instance_table->SQLExec($A2B->DBHandle, "commit");

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

    if ($run)
        $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

    if ($verbose_level >= 1)
        echo "==> SERVICE UPDATE QUERY: 	$QUERY\n";

    // SEND REPORT
    if (strlen($myservice[12]) > 0) {
        $mail_subject = "RECURRING SERVICES : REPORT";

        $mail_content = "SERVICE NAME = " . $myservice[1];
        $mail_content .= "\n\nTotal card updated = " . $totalcardperform;
        $mail_content .= "\nTotal credit removed = " . $totalcredit;

        try {
            $mail = new Mail(null, null, null, $mail_content, $mail_subject);
            $mail -> send($myservice[12]);
        } catch (A2bMailException $e) {
            if ($verbose_level >= 1)
                echo "[Sent mail failed : $e]";
            write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
        }
    }

} // END FOREACH SERVICES

if ($verbose_level >= 1)
    echo "#### END RECURRING SERVICES \n";

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH PROCESS END ####]");
