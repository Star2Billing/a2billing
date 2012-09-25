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
 *            a2billing_batch_autodialer.php
 *
 *	Purpose : to proceed the autodialer
 *  Fri Oct 21 11:51 2008
 *  Copyright  2008  A2Billing
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
    crontab -e
    * / 5 * * * * php /usr/local/a2billing/Cronjobs/a2billing_batch_autodialer.php

    field	 allowed values
    -----	 --------------
    minute	 		0-59
    hour		 	0-23
    day of month	1-31
    month	 		1-12 (or names, see below)
    day of week	 	0-7 (0 or 7 is Sun, or use names)

    #Run command every 5 minutes during 6-13 hours
    * / 5 6-13 * * mon-fri test.script    !!! no space between * / 5

****************************************************************************/

set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
//dl("pgsql.so"); // remove "extension= pgsql.so !

include (dirname(__FILE__) . "/lib/admin.defines.php");
include (dirname(__FILE__) . "/lib/Class.RateEngine.php");
include (dirname(__FILE__) . "/lib/ProcessHandler.php");

if (!defined('PID')) {
    define("PID", "/var/run/a2billing/a2billing_batch_autodialer_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING
$pH= new ProcessHandler();
if ($pH->isActive()) {
        die(); // Already running!
        } else {
                $pH->activate();
                }

$verbose_level = 1;

// time to wait between every send in callback queue
$timing = 6;
$group = 20;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}

if ($A2B->config["database"]['dbtype'] == "postgres") {
    $UNIX_TIMESTAMP = "date_part('epoch',";
} else {
    $UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

$tab_day = array (
    1 => 'monday',
    'tuesday',
    'wednesday',
    'thursday',
    'friday',
    'saturday',
    'sunday'
);
$num_day = date('N');
$name_day = $tab_day[$num_day];

$instance_table = new Table();

$QUERY_COUNT_PHONENUMBERS = 'SELECT count(*) FROM cc_phonenumber , cc_phonebook , cc_campaign_phonebook, cc_campaign WHERE ';
$QUERY_COUNT_PHONENUMBERS .= 'cc_phonenumber.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_campaign = cc_campaign.id ';
$QUERY_COUNT_PHONENUMBERS .= 'AND cc_campaign.status = 1 AND cc_campaign.startingdate <= CURRENT_TIMESTAMP AND cc_campaign.expirationdate > CURRENT_TIMESTAMP ';
//SCHEDULE CLAUSE
$QUERY_COUNT_PHONENUMBERS .= "AND cc_campaign.$name_day = 1 AND  cc_campaign.daily_start_time <= CURRENT_TIME  AND cc_campaign.daily_stop_time > CURRENT_TIME  ";

//NUMBER CLAUSE
$QUERY_COUNT_PHONENUMBERS .= 'AND cc_phonenumber.status = 1 ';

if ($verbose_level >= 1)
    echo "SQL QUERY: $QUERY_COUNT_PHONENUMBERS  \n";

$result_count_phonenumbers = $instance_table->SQLExec($A2B->DBHandle, $QUERY_COUNT_PHONENUMBERS);
if ($verbose_level >= 1)
    print_r($result_count_phonenumbers);

if ($result_count_phonenumbers[0][0] == 0) {
    if ($verbose_level >= 1)
        echo "[No phonenumbers to call now]\n";
    write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[No phonenumbers to call now]");
    exit ();
}

$nb_record = $result_count_phonenumbers[0][0];
$nbpage = (ceil($nb_record / $group));

$QUERY_PHONENUMBERS = 'SELECT cc_phonenumber.id as cc_phonenumber_id, cc_phonenumber.number, cc_campaign.id as cc_campaign_id, cc_campaign.frequency , cc_campaign.forward_number  ,cc_campaign.id_cid_group , cc_card.id , cc_card.tariff, cc_card.username FROM cc_phonenumber , cc_phonebook , cc_campaign_phonebook, cc_campaign, cc_card WHERE ';
$QUERY_PHONENUMBERS .= 'cc_phonenumber.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_campaign = cc_campaign.id AND cc_campaign.id_card = cc_card.id ';
$QUERY_PHONENUMBERS .= 'AND cc_campaign.status = 1 AND cc_campaign.startingdate <= CURRENT_TIMESTAMP AND cc_campaign.expirationdate > CURRENT_TIMESTAMP ';
//SCHEDULE CLAUSE
$QUERY_PHONENUMBERS .= "AND cc_campaign.$name_day = 1 AND  cc_campaign.daily_start_time <= CURRENT_TIME  AND cc_campaign.daily_stop_time > CURRENT_TIME  ";
//NUMBER CLAUSE
$QUERY_PHONENUMBERS .= 'AND cc_phonenumber.status = 1 ';

// BROWSE THROUGH THE CARD TO APPLY THE CHECK ACCOUNT SERVICE
for ($page = 0; $page < $nbpage; $page++) {

    if ($A2B->config["database"]['dbtype'] == "postgres") {
        $sql = $QUERY_PHONENUMBERS . " LIMIT $group OFFSET " . $page * $group;
    } else {
        $sql = $QUERY_PHONENUMBERS . " LIMIT " . $page * $group . ", $group";
    }

    if ($verbose_level >= 1)
        echo "==> SELECT QUERY : $sql\n";

    $result_phonenumbers = $instance_table->SQLExec($A2B->DBHandle, $sql);

    foreach ($result_phonenumbers as $phone) {

        if ($verbose_level >= 1)
            print_r($phone);

        // check the balance
        $query_balance = "SELECT cc_campaign_config.flatrate, cc_card.credit FROM  cc_card,cc_card_group,cc_campaignconf_cardgroup,cc_campaign_config WHERE cc_card.id = $phone[6] AND cc_card.id_group = cc_card_group.id AND cc_campaignconf_cardgroup.id_card_group = cc_card_group.id  AND cc_campaignconf_cardgroup.id_campaign_config = cc_campaign_config.id ";
        $result_balance = $instance_table->SQLExec($A2B->DBHandle, $query_balance);

        if ($verbose_level >= 1)
            echo "\n CHECK BALANCE :" . $query_balance;

        if ($result_balance) {
            if ($result_balance[0][1] < $result_balance[0][0]) {
                write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[ user $phone[8] don't have engouh credit ]");
                if ($verbose_level >= 1)
                    echo "\n[ Error : Can't send callback -> user $phone[8] don't have enough credit ]";
                continue;
            }

        } else {
            write_log(LOGFILE_CRONT_BATCH_PROCESS, basename(__FILE__) . ' line:' . __LINE__ . "[ user $phone[8] don't have a group correctly defined ]");
            if ($verbose_level >= 1)
                echo "\n[ Error : Can't send callback -> user $phone[8] don't have a group correctly defined ]";
            continue;
        }

        //test if you have to inject it again
        $frequency_sec = $phone['frequency'] * 60;
        $query_searche_phonestatus = "SELECT status, $UNIX_TIMESTAMP lastuse ) < $UNIX_TIMESTAMP CURRENT_TIMESTAMP) - $frequency_sec  FROM cc_campaign_phonestatus WHERE id_campaign = " . $phone[2] . " AND id_phonenumber = " . $phone[0];
        $result_search_phonestatus = $instance_table->SQLExec($A2B->DBHandle, $query_searche_phonestatus);

        if ($verbose_level >= 1)
            echo "\nSEARCH PHONESTATUS QUERY : " . $query_searche_phonestatus;
        if ($verbose_level >= 1)
            echo "\nSEARCH PHONESTATUS RESULT : " . print_r($result_search_phonestatus);

        //check callback spool
        $action = '';
        if ($result_search_phonestatus) {
            $action = "update";
            //Filter phone number holded and stoped
            if ($result_search_phonestatus[0][0] == 1 || $result_search_phonestatus[0][0] == 2)
                continue;
            if ($result_search_phonestatus[0][1] == 0) {
                if ($verbose_level >= 1)
                    echo "\n[  Can't send callback -> number $phone[1] is not in the frequency ]";
                continue;
            }

        } else {
            $action = "insert";
        }

        // Search Road...
        $A2B->set_instance_table($instance_table);
        $A2B->cardnumber = $phone["username"];
        $error_msg = '';

        if ($A2B->callingcard_ivr_authenticate_light($error_msg)) {

            $RateEngine = new RateEngine();
            $RateEngine->webui = 0;
            // LOOKUP RATE : FIND A RATE FOR THIS DESTINATION

            $A2B->agiconfig['accountcode'] = $phone["username"];
            $A2B->agiconfig['use_dnid'] = 1;
            $A2B->agiconfig['say_timetocall'] = 0;

            $A2B->dnid = $A2B->destination = $phone["number"];

            $resfindrate = $RateEngine->rate_engine_findrates($A2B, $phone["number"], $phone["tariff"]);

            // IF FIND RATE
            if ($resfindrate != 0) {
                $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
                if ($res_all_calcultimeout) {

                    // MAKE THE CALL
                    if ($RateEngine->ratecard_obj[0][34] != '-1') {
                        $usetrunk = 34;
                        $usetrunk_failover = 1;
                        $RateEngine->usedtrunk = $RateEngine->ratecard_obj[0][34];
                    } else {
                        $usetrunk = 29;
                        $RateEngine->usedtrunk = $RateEngine->ratecard_obj[0][29];
                        $usetrunk_failover = 0;
                    }

                    $prefix = $RateEngine->ratecard_obj[0][$usetrunk +1];
                    $tech = $RateEngine->ratecard_obj[0][$usetrunk +2];
                    $ipaddress = $RateEngine->ratecard_obj[0][$usetrunk +3];
                    $removeprefix = $RateEngine->ratecard_obj[0][$usetrunk +4];
                    $timeout = $RateEngine->ratecard_obj[0]['timeout'];
                    $failover_trunk = $RateEngine->ratecard_obj[0][40 + $usetrunk_failover];
                    $addparameter = $RateEngine->ratecard_obj[0][42 + $usetrunk_failover];

                    $destination = $phone["number"];
                    if (strncmp($destination, $removeprefix, strlen($removeprefix)) == 0)
                        $destination = substr($destination, strlen($removeprefix));

                    $pos_dialingnumber = strpos($ipaddress, '%dialingnumber%');
                    $ipaddress = str_replace("%cardnumber%", $A2B->cardnumber, $ipaddress);
                    $ipaddress = str_replace("%dialingnumber%", $prefix . $destination, $ipaddress);

                    if ($pos_dialingnumber !== false) {
                        $dialstr = "$tech/$ipaddress" . $dialparams;
                    } else {
                        if ($A2B->agiconfig['switchdialcommand'] == 1) {
                            $dialstr = "$tech/$prefix$destination@$ipaddress" . $dialparams;
                        } else {
                            $dialstr = "$tech/$ipaddress/$prefix$destination" . $dialparams;
                        }
                    }

                    //ADDITIONAL PARAMETER 			%dialingnumber%,	%cardnumber%
                    if (strlen($addparameter) > 0) {
                        $addparameter = str_replace("%cardnumber%", $A2B->cardnumber, $addparameter);
                        $addparameter = str_replace("%dialingnumber%", $prefix . $destination, $addparameter);
                        $dialstr .= $addparameter;
                    }

                    $channel = $dialstr;
                    $exten = 11;
                    $context = $A2B->config["callback"]['context_campaign_callback'];
                    $id_server_group = $A2B->config["callback"]['id_server_group'];
                    $priority = 1;
                    $timeout = $A2B->config["callback"]['timeout'] * 1000;
                    $application = '';
                    //default callerid
                    $callerid = '111111111';
                    $cidgroupid = $phone["id_cid_group"];
                    if ($A2B->config["database"]['dbtype'] == "postgres") {
                        $QUERY = "SELECT cid FROM cc_outbound_cid_list WHERE activated = 1 AND outbound_cid_group = $cidgroupid ORDER BY RANDOM() LIMIT 1";
                    } else {
                        $QUERY = "SELECT cid FROM cc_outbound_cid_list WHERE activated = 1 AND outbound_cid_group = $cidgroupid ORDER BY RAND() LIMIT 1";
                    }
                    $instance_cid_table = new Table();
                    echo "QUERY CID : " . $QUERY;
                    $cidresult = $instance_cid_table->SQLExec($A2B->DBHandle, $QUERY);
                    if (is_array($cidresult) && count($cidresult) > 0) {
                        $callerid = $cidresult[0][0];
                    }

                    $account = $_SESSION["pr_login"];

                    $uniqueid = MDP_NUMERIC(5) . '-' . MDP_STRING(7);
                    $status = 'PENDING';
                    $server_ip = 'localhost';
                    $num_attempt = 0;
                    $variable = "CALLED=$destination|USERNAME=$phone[8]|USERID=$phone[6]|CBID=$uniqueid|PHONENUMBER_ID=" . $phone['cc_phonenumber_id'] . "|CAMPAIGN_ID=" . $phone['cc_campaign_id'];

                    $res = $instance_table->Add_table($A2B->DBHandle, "'$uniqueid', '$status', '$server_ip', '$num_attempt', '$channel', '$exten', '$context', '$priority', '$variable', '$id_server_group',  now(), '$account', '$callerid', '30000'", "uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout", "cc_callback_spool", "id");

                    if (!$res) {
                        if ($verbose_level >= 1)
                            echo "[Cannot insert the callback request in the spool!]";
                    } else {
                        if ($verbose_level >= 1)
                            echo "[Your callback request has been queued correctly!]";

                        if ($action == "update")
                            $query = "UPDATE cc_campaign_phonestatus SET id_callback = '$uniqueid', lastuse = CURRENT_TIMESTAMP WHERE id_phonenumber =$phone[0] AND id_campaign = $phone[2] ";
                        else
                            $query = "INSERT INTO cc_campaign_phonestatus (id_phonenumber ,id_campaign ,id_callback ,status) VALUES ( $phone[0], $phone[2], $res , '0') ";

                        if ($verbose_level >= 1)
                            echo "\nINSERT PHONESTATUS QUERY : $query";
                        $res = $A2B->DBHandle->Execute($query);
                    }

                } else {
                    if ($verbose_level >= 1)
                        echo "Error : You don t have enough credit to call you back!";
                }
            } else {
                if ($verbose_level >= 1)
                    echo "Error : There is no route to call back your phonenumber!";
            }

        } else {
            if ($verbose_level >= 1)
                echo "Error : " . $error_msg;
        }

        // End Search Road....

    }

    if ($page != $nbpage -1)
        sleep($timing);

} // End For
