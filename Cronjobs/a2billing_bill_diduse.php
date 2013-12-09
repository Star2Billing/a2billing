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
 *            a2billing_bill_diduse.php
 *
 *  Usage : this script will browse all the DID that are reserve and check if the customer need to pay for it
 *	bill them or warn them per email to know if they want to pay in order to keep their DIDs
 *
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
    crontab -e
    0 2 * * * php /usr/local/a2billing/Cronjobs/a2billing_bill_diduse.php

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
    define("PID", "/var/run/a2billing/a2billing_bill_diduse_pid.php");
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

if ($A2B->config["database"]['dbtype'] == "postgres") {
    $UNIX_TIMESTAMP = "date_part('epoch',";
} else {
    $UNIX_TIMESTAMP = "UNIX_TIMESTAMP(";
}

write_log(LOGFILE_CRONT_BILL_DIDUSE, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH DIDUSE BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_BILL_DIDUSE, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}
//$A2B -> DBHandle
$instance_table = new Table();

// CHECK THE CARD WITH DID'S
$QUERY = "SELECT id_did, reservationdate, month_payed, fixrate, cc_card.id, credit, email, did, typepaid, creditlimit, reminded " .
         " FROM (cc_did_use INNER JOIN cc_card on cc_card.id=id_cc_card) INNER JOIN cc_did ON (id_did=cc_did.id) " .
         " WHERE ( releasedate IS NULL OR releasedate < '1984-01-01 00:00:00') AND cc_did_use.activated=1 AND cc_did.billingtype <> '3' " .
         " ORDER BY cc_card.id ASC";

if ($verbose_level >= 1)
    echo "==> SELECT CARD WIHT DID'S QUERY : $QUERY\n";
$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);

if ($verbose_level >= 1)
    print_r($result);

if (!is_array($result)) {
    if ($verbose_level >= 1)
        echo "[No DID in use to run the DIDBilling recurring service]\n";
    write_log(LOGFILE_CRONT_BILL_DIDUSE, basename(__FILE__) . ' line:' . __LINE__ . "[No DID in use to run the DIDBilling recurring service]");
    exit ();
}

$oneday = 60 * 60 * 24;
// count day that user have to recharge his account
$daytopay = $A2B->config['global']['didbilling_daytopay'];
if ($verbose_level >= 1)
    echo "daytopay=$daytopay \n";

// BROWSE THROUGH THE CARD TO APPLY THE DID USAGE
$last_idcard = null;
$new_card = true;
$last_invoice = null;
foreach ($result as $mydids) {

    if ($last_idcard != $mydids[4]) {
        $new_card = true;
        $last_idcard = $mydids[4];
    } else {
        $new_card = false;
    }

    // mail variable for user notification
    $user_mail_adrr = '';
    $mail_user = false;

    if ($verbose_level >= 1) {
        print_r($mydids);
        echo "------>>>  ID DID = " . $mydids[0] . " - MONTHLY RATE = " . $mydids[3] . "ID CARD = " . $mydids[4] . " -BALANCE =" . $mycard[5] . " \n";
    }

    $day_remaining = 0;
    // $mydids[1] -> reservationdate
    $diff_reservation_daytopay = (strtotime($mydids[1])) - (intval($daytopay) * $oneday); // diff : reservationdate - daytopay : ie reserved 15Sept - day to pay 5 :> 10 days of diff
    // $timestamp_datetopay : 10 Septembre
    $timestamp_datetopay = mktime(date('H', $diff_reservation_daytopay), date("i", $diff_reservation_daytopay), date("s", $diff_reservation_daytopay), date("m", $diff_reservation_daytopay) + $mydids[2], date("d", $diff_reservation_daytopay), date("Y", $diff_reservation_daytopay));

    $day_remaining = time() - $timestamp_datetopay;

    if ($verbose_level >= 1) {
        echo "Time now :" . time() . " - timestamp_datetopay=$timestamp_datetopay\n";
        echo "day_remaining=$day_remaining <=" . (intval($daytopay) * $oneday) . "\n";
    }

    if ($day_remaining >= 0) {
        if ($day_remaining <= (intval($daytopay) * $oneday)) {

            //type of user prepaid
            if ($mydids['reminded'] == 0) {
                // THE USER HAVE TO PAY FOR HIS DID NOW

                if (($mydids['credit'] + $mydids['typepaid'] * $mydids['creditlimit']) >= $mydids['fixrate']) {

                    // USER HAVE ENOUGH CREDIT TO PAY FOR THE DID
                    $QUERY = "UPDATE cc_card SET credit = credit - '" . $mydids[3] . "' WHERE id=" . $mydids[4];
                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                    if ($verbose_level >= 1)
                        echo "==> UPDATE CARD QUERY: 	$QUERY\n";

                    $QUERY = "UPDATE cc_did_use set month_payed = month_payed + 1 WHERE id_did = '" . $mydids[0] .
                             "' AND activated = 1 AND ( releasedate IS NULL OR releasedate < '1984-01-01 00:00:00') ";
                    if ($verbose_level >= 1)
                        echo "==> UPDATE DID USE QUERY: 	$QUERY\n";
                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

                    $QUERY = "INSERT INTO cc_charge (id_cc_card, amount, description, chargetype, id_cc_did, charged_status) VALUES ('" .
                                $mydids[4] . "', '" . $mydids[3] . "', '" . $mydids[7] . "', '2','" . $mydids[0] . "',1)";

                    if ($verbose_level >= 1)
                        echo "==> INSERT CHARGE QUERY: 	$QUERY\n";

                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

                    $mail_user = true;
                    $mail = new Mail(Mail::$TYPE_DID_PAID,$mydids[4] );
                    $mail -> replaceInEmail(Mail::$BALANCE_REMAINING_KEY,$mydids[5] - $mydids[3]);
                    $mail -> replaceInEmail(Mail::$DID_NUMBER_KEY,$mydids[7]);
                    $mail -> replaceInEmail(Mail::$DID_COST_KEY,$mydids[3]);

                } else {
                    // USER DONT HAVE ENOUGH CREDIT TO PAY FOR THE DID - WE WILL WARN HIM

                    $reference = generate_invoice_reference();

                    //CREATE INVOICE If a new card then just an invoice item in the last invoice
                    if ($new_card) {
                        $field_insert = "date, id_card, title, reference, description, status, paid_status";
                        $date = date("Y-m-d h:i:s");
                        $card_id = $last_idcard;
                        $title = gettext("DID INVOICE REMINDER");
                        $description = "Your credit was not enough to pay yours DID numbers automatically.\n";
                        $description .= "You have " . date("d", $day_remaining) . " days to pay this invoice (REF: $reference ) or the DID will be automatically released \n\n";
                        $value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,0";
                        $instance_table = new Table("cc_invoice", $field_insert);
                        if ($verbose_level >= 1)
                            echo "INSERT INVOICE : $field_insert =>	$value_insert \n";
                        $id_invoice = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                        $last_invoice = $id_invoice;
                    }

                    if (!empty ($last_invoice) && is_numeric($last_invoice)) {
                        $description = "DID number (" . $mydids[7] . ")";
                        $amount = $mydids[3];
                        $vat = 0;
                        $field_insert = "date, id_invoice, price, vat, description, id_ext, type_ext";
                        $instance_table = new Table("cc_invoice_item", $field_insert);
                        $value_insert = " '$date' , '$last_invoice', '$amount','$vat','$description','" . $mydids[0] . "','DID'";
                        if ($verbose_level >= 1)
                            echo "INSERT INVOICE ITEM : $field_insert =>	$value_insert \n";
                        $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                    }

                    $mail_user = true;
                    $mail = new Mail(Mail::$TYPE_DID_UNPAID, $mydids[4]);
                    $mail -> replaceInEmail(Mail::$DAY_REMAINING_KEY,date("d", $day_remaining));
                    $mail -> replaceInEmail(Mail::$INVOICE_REF_KEY,$reference);
                    $mail -> replaceInEmail(Mail::$DID_NUMBER_KEY,$mydids[7]);
                    $mail -> replaceInEmail(Mail::$DID_COST_KEY,$mydids[3]);
                    $mail -> replaceInEmail(Mail::$BALANCE_REMAINING_KEY, $mydids[5]);

                    //insert charge
                    $QUERY = "INSERT INTO cc_charge (id_cc_card, amount, description, chargetype, id_cc_did, invoiced_status) VALUES ('" .
                                $mydids[4] . "', '" . $mydids[3] . "', '" . $mydids[7] . "','2','" . $mydids[0] . "','1')";
                    if ($verbose_level >= 1)
                        echo "==> INSERT CHARGE QUERY: 	$QUERY\n";
                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

                    //update did_use
                    $QUERY = "UPDATE cc_did_use set reminded = 1 WHERE id_did = '" . $mydids[0] . "' and activated = 1";
                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                    if ($verbose_level >= 1)
                        echo "==> UPDATE DID USE QUERY: $QUERY\n";
                }
            }

        } else {
            // RELEASE THE DID
            $QUERY = "UPDATE cc_did set iduser = 0, reserved = 0 WHERE id='" . $mydids[0] . "'";
            if ($verbose_level >= 1)
                echo "==> UPDATE DID QUERY: 	$QUERY\n";
            $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

            $QUERY = "UPDATE cc_did_use set releasedate = now() WHERE id_did = '" . $mydids[0] . "' and activated = 1";
            $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
            if ($verbose_level >= 1)
                echo "==> UPDATE DID USE QUERY: 	$QUERY\n";

            $QUERY = "INSERT INTO cc_did_use (activated, id_did) VALUES ('0','" . $mydids[0] . "')";
            $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
            if ($verbose_level >= 1)
                echo "==> INSERT NEW DID USE QUERY: 	$QUERY\n";

            $QUERY = "DELETE FROM cc_did_destination WHERE id_cc_did =" . $mydids[0];
            $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
            if ($verbose_level >= 1)
                echo "==> DELETEDID did_destination QUERY: 	$QUERY\n";

            $mail_user = true;
            $mail = new Mail(Mail::$TYPE_DID_RELEASED,$mydids[4] );
            $mail -> replaceInEmail(Mail::$DID_NUMBER_KEY,$mydids[7]);
            $mail -> replaceInEmail(Mail::$DID_COST_KEY,$mydids[3]);
            $mail -> replaceInEmail(Mail::$BALANCE_REMAINING_KEY, $mydids[5]);
        }
    }

    $user_mail_adrr = $mydids[6];
    $user_card_id = $mydids[4];

    if (!is_null($mail )&& $mail_user && strlen($user_mail_adrr) > 5) {
        try {
            $mail -> send($user_mail_adrr);
        } catch (A2bMailException $e) {
            if ($verbose_level >= 1)
                echo "[Sent mail failed : $e]";
            write_log(LOGFILE_CRONT_BILL_DIDUSE, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
        }
    }

}
write_log(LOGFILE_CRONT_BILL_DIDUSE, basename(__FILE__) . ' line:' . __LINE__ . "[Service DIDUSE finish]");

if ($verbose_level >= 1)
    echo "#### END RECURRING SERVICES \n";

write_log(LOGFILE_CRONT_BILL_DIDUSE, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH DIDUSE  PROCESS END ####]");
