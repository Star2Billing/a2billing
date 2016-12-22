#!/usr/bin/php -q
<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
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
 *            a2billing_alarm.php
 *
 *	Purpose : manage different Alarms
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
    crontab -e
    0 * * * * php //usr/local/a2billing/Cronjobs/a2billing_alarm.php

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
    define("PID", "/var/run/a2billing/a2billing_alarm_pid.php");
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

write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}
//$A2B -> DBHandle
$instance_table = new Table();

// CHECK THE ALARMS
$QUERY = "SELECT id, name, periode, type, `maxvalue`, minvalue, id_trunk, status, numberofrun, datecreate, datelastrun, emailreport " .
        "FROM cc_alarm WHERE status=1";

$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);

if ($verbose_level >= 1)
    print_r($result);

if (!is_array($result)) {
    echo "[No Alarm to run]\n";
    write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[ No Alarm to run]");
    exit ();
}
// 0 id, 1 name, 2 period, 3 type, 4 maxvalue, 5 minvalue, 6 id_trunk, 7 status, 8 numberofrun, 9 datecreate, 10 datelastrun, 11 emailreport

$oneday = 60 * 60 * 24;
$groupcalls = 5000;

// BROWSE THROUGH THE ALARMS
foreach ($result as $myalarm) {
    $SQL_CLAUSE = "";
    $timestamp_lastsend = strtotime($myalarm[10]);
    if ($verbose_level >= 3)
        echo "timestamp_lastsend = $timestamp_lastsend" . " ; now = " . time();

    //  1 "Daily", 2 "Weekly", 3 "Monthly"
    $run_alarm = false;

    // LITTLE TRICK TO MAKE IT WORKS WITH POSTGRES TOO AS THE VALUE WILL BE NULL
    if ($myalarm[10] == "0000-00-00 00:00:00") {
        $myalarm[10] = "";
        // WE WILL THEN COMPARE TO AN EMPTY STRING ""
    }

    switch ($myalarm[2]) {
        // Hourly
        case 1 :
            if (date("G", time()) != date("G", $timestamp_lastsend) || $myalarm[10] == "") {
                $run_alarm = true;
                $SQL_CLAUSE = "WHERE starttime BETWEEN '" . date("Y-m-j H:i:s", $timestamp_lastsend) . "' AND '" . date("Y-m-j H:i:s", time()) . "'";
            }
            if ($verbose_level >= 1)
                echo "\n\n TODAY :" . date("G", time()) . " LAST RUN DAY :" . date("G", $timestamp_lastsend);
            break;
            // Daily
        case 2 :
            if (date("j", time()) != date("j", $timestamp_lastsend) || $myalarm[10] == "") {
                $run_alarm = true;
                $SQL_CLAUSE = "WHERE starttime BETWEEN '" . date("Y-m-j", time()) . " 00:00:00' AND '" . date("Y-m-j", time()) . " 23:59:59'";
            }
            if ($verbose_level >= 1)
                echo "\n\n TODAY :" . date("j", time()) . " LAST RUN DAY :" . date("j", $timestamp_lastsend);
            break;
            //Weekly -> will run only monday and check if the week is not the same
        case 3 :
            if (((date("w", time()) == 1) && (date("W", time()) != date("W", $timestamp_lastsend))) || $myalarm[10] == "") {
                $run_alarm = true;
                $SQL_CLAUSE = "WHERE starttime BETWEEN '" . date("Y-m-j", (time() - ($oneday * 7))) . " 00:00:00' AND '" . date("Y-m-j", time()) . " 23:59:59'";
            }
            if ($verbose_level >= 1)
                echo "\n\n TODAY :" . date("w", time()) . " WEEK:" . date("W", time()) .
                " LAST RUN DAY :" . date("w", $timestamp_lastsend) . " LAST RUN WEEK :" . date("W", $timestamp_lastsend);
            break;
            //Monthly
        case 4 :
            if (((date("j", time()) == 1) && (date("m", time()) != date("m", $timestamp_lastsend))) || $myalarm[10] == "") {
                $run_alarm = true;
                $SQL_CLAUSE = "WHERE starttime BETWEEN '" . date("Y-m-j", (time() - ($oneday * date("t", time() - $oneday)))) . " 00:00:00' AND '" . date("Y-m-j", (time() - $oneday)) . " 23:59:59'";
            }
            if ($verbose_level >= 1)
                echo "\n\n THIS MONTH :" . date("m", time()) . " LAST RUN MONTH :" . date("m", $timestamp_lastsend);
            break;
    }

    if ($run_alarm) {

        if (isset ($myalarm[6]) && $myalarm[6] != "")
            $SQL_CLAUSE .= " AND id_trunk = '" . $myalarm[6] . "'";
        write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[Alarm : " . $myalarm[1] . " ]");

        $QUERY = "SELECT COUNT(*) FROM cc_call $SQL_CLAUSE";
        if ($verbose_level >= 1)
            echo "\n===> QUERY = $QUERY\n";
        $calls = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 1);
        $nb_card = $calls[0][0];
        $nbpagemax = (ceil($nb_card / $groupcalls));
        if ($verbose_level >= 1)
            echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

        if (!($nb_card > 0)) {
            if ($verbose_level >= 1)
                echo "[No call to run the Alarm Service]\n";
            write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[No call to run the Alarm service]");
            exit ();
        }

        $totalsuccess = 0;
        $totalfail = 0;
        $totaltime = 0;
        $max_fail = 0;
        $max = 0;
        $update = array ();
        for ($page = 0; $page < $nbpagemax; $page++) {
            // REST AFTER $groupcalls CARD HANDLED
            if ($page > 0)
                sleep(15);

            $QUERY = "SELECT CASE WHEN (terminatecauseid = 1) THEN 1 ELSE 0 END AS success," .
            "CASE WHEN (terminatecauseid = 1) THEN 0 ELSE 1 END AS fail,sessiontime FROM cc_call $SQL_CLAUSE";

            if ($A2B->config["database"]['dbtype'] == "postgres") {
                $QUERY .= " LIMIT $groupcard OFFSET " . $page * $groupcard;
            } else {
                $QUERY .= " LIMIT " . $page * $groupcard . ", $groupcard";
            }

            if ($verbose_level >= 1)
                echo "\n\n" . $QUERY;

            $res = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 1);
            for ($i = 0; $i < count($res); $i++) {
                $totalsuccess += $res[$i][0];
                $totalfail += $res[$i][1];
                $totaltime += $res[$i][2];
                // FIND THE CIC (Consecutive Incomplete Calls)
                if ($res[$i][1] == 1)
                    $max++;
                if ($res[$i][1] == 0) {
                    if ($max > $max_fail)
                        $max_fail = $max;
                    $max = 0;
                }
            }
            if ($max > $max_fail)
                $max_fail = $max;

        } // LOOP FOR THE CALLS

        if ($max_fail == 1)
            $max_fail = 0;

        $ASR = $totalsuccess / ($totalsuccess + $totalfail);
        $ALOC = $totaltime / $totalsuccess;

        $send_alarm = false;

        //$type_list   ["0" ALOC, "1" ASR, "2" CIC]
        switch ($myalarm[3]) {
            case 1 :
                if ($ALOC > $myalarm[4] || $ALOC < $myalarm[5]) {
                    $value = $ALOC;
                    $send_alarm = true;
                }
                $content = "\n\n The ALOC $ALOC seconds is outside the range max: " . $myalarm[4] . " min:" . $myalarm[5] . " defined in the alarm";
                break;
            case 2 :
                if ($ASR > $myalarm[4] || $ASR < $myalarm[5]) {
                    $value = $ASR;
                    $send_alarm = true;
                }
                $content = "\n\n The ASR " . $ASR . "% is outside the range max: " . $myalarm[4] . " min:" . $myalarm[5] . " defined in the alarm";
                break;

            case 3 :
                if ($max_fail >= $myalarm[4]) {
                    $value = $max_fail;
                    $send_alarm = true;
                }
                $content = "\n\n The Max Consecutive Incomplete Calls : " . $max_fail . " calls is greater than the max: " . $myalarm[4] . " defined in the alarm";
                break;
        }
        if ($verbose_level >= 1)
            echo "content = $content\n";
        write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[Alarm finish]");
        // INSERT REPORT ALARM INTO THE DATABASE
        $QUERY = "INSERT INTO cc_alarm_report (cc_alarm_id, calculatedvalue, daterun) " . "VALUES ('$myalarm[0]', '$value', now())";
        $result_insert = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
        if ($verbose_level >= 1)
            echo "\n\n ==> INSERT ALARM REPORT QUERY=$QUERY\n";

        write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[Alarm report : 'Alarm name=$myalarm[1]', 'Alarm type=$myalarm[3]', 'Calculated value=$value']");

        // UPDATE THE ALARM
        if ($send_alarm)
            $add_field = ", numberofalarm=numberofalarm+1";
        else
            $add_field = "";
        $QUERY = "UPDATE cc_alarm SET datelastrun=now(), numberofrun=numberofrun+1 $add_field WHERE id=" . $myalarm[0];
        $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
        if ($verbose_level >= 1)
            echo "\n\n ==> ALARM UPDATE QUERY: 	$QUERY\n";

        // SEND REPORT
        if (($send_alarm) && (strlen($myalarm[7]) > 0)) {
            $mail_subject = "A2BILLING ALARM : REPORT";

            $mail_content = "ALARM NAME = " . $myalarm[1];
            $mail_content .= $content;

            try {
                $mail = new Mail(null, null, null, $mail_content, $mail_subject);
                $mail -> send($myalarm[11]);
            } catch (A2bMailException $e) {
                if ($verbose_level >= 1)
                    echo "[Sent mail failed : $e]";
                write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
            }
        }

    } // IF ALARM

} // MAIN LOOP FOR THE ALARM

if ($verbose_level >= 1)
    echo "#### END ALARMS \n";
write_log(LOGFILE_CRONT_ALARM, basename(__FILE__) . ' line:' . __LINE__ . "[#### ALARM END ####]");
