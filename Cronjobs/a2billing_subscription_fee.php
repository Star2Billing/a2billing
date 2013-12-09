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
 *            a2billing_subscription_fee.php
 *
 *  Purpose: manage the monthly services subscription
 *  Fri Feb 27 14:17:10 2007
 *  Copyright  2007  User : Areski
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
    crontab -e
    0 6 * * * php /usr/local/a2billing/Cronjobs/a2billing_subscription_fee.php

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
    define("PID", "/var/run/a2billing/a2billing_subscription_fee_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING
$pH= new ProcessHandler();
if ($pH->isActive()) {
        die(); // Already running!
        } else {
                $pH->activate();
                }

$verbose_level = 1;

$groupcard = 5000;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}

$instance_table = new Table();

/*
    Pay_Status :
        0 : First USE
        1 : Billed
        2 : Paid
        3 : UnPaid
*/

$QUERY = 'SELECT count(*) FROM cc_card INNER JOIN cc_card_subscription ON cc_card.id = cc_card_subscription.id_cc_card INNER JOIN cc_subscription_service ON cc_card_subscription.id_subscription_fee=cc_subscription_service.id' .
' WHERE cc_subscription_service.status=1 AND cc_card_subscription.startdate < NOW() AND (cc_card_subscription.stopdate = "0000-00-00 00:00:00" OR cc_card_subscription.stopdate > NOW())'.
' AND cc_subscription_service.startdate < NOW() AND (cc_subscription_service.stopdate = "0000-00-00 00:00:00" OR cc_subscription_service.stopdate > NOW()) AND cc_card_subscription.paid_status !=3';

$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax = (ceil($nb_card / $groupcard));
if ($verbose_level >= 1)
    echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card > 0)) {
    if ($verbose_level >= 1)
        echo "[No card to run the Subscription service]\n";
    write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the Subscription Feeservice]");
    exit ();
}

$billdaybefor_anniversary = $A2B->config['global']['subscription_bill_days_before_anniversary'];

$currencies_list = get_currencies($A2B->DBHandle);

$service_array = array();

for ($page = 0; $page < $nbpagemax; $page++) {

    $sql = 'SELECT cc_card.id card_id ,cc_subscription_service.id service_id, cc_subscription_service.label, cc_subscription_service.fee, cc_subscription_service.emailreport,DATE(cc_card_subscription.startdate) startdate , cc_card_subscription.paid_status , cc_card_subscription.last_run, cc_card_subscription.next_billing_date , cc_card_subscription.limit_pay_date , cc_card_subscription.id card_subscription_id, cc_card_subscription.product_name product_name'.
    ' FROM cc_card INNER JOIN cc_card_subscription ON cc_card.id = cc_card_subscription.id_cc_card  INNER JOIN cc_subscription_service ON cc_card_subscription.id_subscription_fee=cc_subscription_service.id '  .
    ' WHERE cc_subscription_service.status=1 AND cc_card_subscription.startdate < NOW() AND (cc_card_subscription.stopdate = "0000-00-00 00:00:00" OR cc_card_subscription.stopdate > NOW())'.
    ' AND cc_subscription_service.startdate < NOW() AND (cc_subscription_service.stopdate = "0000-00-00 00:00:00" OR cc_subscription_service.stopdate > NOW()) AND cc_card_subscription.paid_status !=3'.
    ' ORDER BY cc_card.id';

    if ($A2B->config["database"]['dbtype'] == "postgres") {
        $sql .= " LIMIT $groupcard OFFSET " . $page * $groupcard;
    } else {
        $sql .= " LIMIT " . $page * $groupcard . ", $groupcard";
    }

    $result_subscriptions = $instance_table->SQLExec($A2B->DBHandle, $sql);

    foreach ($result_subscriptions as $subscription) {
        $service_id = $subscription['service_id'];

        if (!is_array($service_array[$service_id])) $service_array[$service_id] = array("totalcardperform" => 0 , "totalcredit" => 0 );

        $action = "";

        switch ($subscription['paid_status']) {

            case 0:
                //firstuse : billed
                $action = "bill";
                $unix_startdate = strtotime($subscription['startdate']);
                $day_now = date("j");
                $last_run = date("Y-m-d");
                $day_startdate = date("j",$unix_startdate);
                $month_startdate = date("m",$unix_startdate);
                $year_startdate= date("Y",$unix_startdate);
                $lastday_of_startdate_month = lastDayOfMonth($month_startdate,$year_startdate,"j");

                $next_bill_date = strtotime("01-$month_startdate-$year_startdate + 1 month");
                $lastday_of_next_month= lastDayOfMonth(date("m",$next_bill_date),date("Y",$next_bill_date),"j");

                $limite_pay_date = date("Y-m-d",strtotime(" + $billdaybefor_anniversary day")) ;

                if ($day_startdate>$lastday_of_next_month) {
                    $next_limite_pay_date = date ("$lastday_of_next_month-m-Y" ,$next_bill_date);
                } else {
                    $next_limite_pay_date = date ("$day_startdate-m-Y" ,$next_bill_date);
                }

                $next_bill_date = date("Y-m-d",strtotime("$next_limite_pay_date - $billdaybefor_anniversary day")) ;
                break;

            case 1:
                // billed : check if out of date -> unpaid
                // date('m',strtotime($mycard['last_run']));
                $unix_limit = strtotime($subscription['limit_pay_date']);
                $unix_now = strtotime(date("d-m-Y"));

                if ($unix_now>$unix_limit) {
                    $action = "unpaid";
                }

                break;

            case 2:
                // paid : check if the system have to bill it again
                $unix_bill_time = strtotime($subscription['next_billing_date']);
                $unix_now = strtotime(date("d-m-Y"));
                if ($unix_now>=$unix_bill_time) {
                    $action = "bill";

                    $unix_startdate = strtotime($subscription['startdate']);
                    $last_run = date("Y-m-d");

                    $day_startdate = date("j",$unix_startdate);
                    $month_lastbill_date = date("m",$unix_bill_time);
                    $year_lastbill_date = date("Y",$unix_bill_time);
                    $lastday_of_next_billmonth = lastDayOfMonth($month_lastbill_date,$year_lastbill_date,"j");

                    $next_bill_date = strtotime("01-$month_lastbill_date-$year_lastbill_date + 1 month");
                    $lastday_of_next_month= lastDayOfMonth(date("m",$next_bill_date),date("Y",$next_bill_date),"j");

                    $limite_pay_date = date("Y-m-d",strtotime(" + $billdaybefor_anniversary day")) ;

                    if ($day_startdate>$lastday_of_next_month) {
                        $next_limite_pay_date = date ("$lastday_of_next_month-m-Y" ,$next_bill_date);
                    } else {
                        $next_limite_pay_date = date ("$day_startdate-m-Y" ,$next_bill_date);
                    }

                    $next_bill_date = date("Y-m-d",strtotime("$next_limite_pay_date - $billdaybefor_anniversary day")) ;

                }
                break;

            default:
                continue;
                break;
        }

        switch ($action) {

            case "bill" :
                //select card
                $table_card = new Table('cc_card','*');
                $card_clause = "id = ".$subscription['card_id'];
                $result_card = $table_card -> Get_list($A2B->DBHandle, $card_clause);

                if (!is_array($result_card))
                    break;
                else
                    $card = $result_card[0];

                if (($card['credit'] + $card['typepaid'] * $card['creditlimit']) >= $subscription['fee']) {

                    // USER HAVE ENOUGH CREDIT TO PAY FOR THE DID
                    $service_array[$service_id]['totalcardperform']++;
                    $service_array[$service_id]['totalcredit']+= $subscription['fee'];

                    $QUERY = "UPDATE cc_card SET credit=credit-'" . $subscription['fee'] . "' WHERE id=" . $card['id'];
                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                    if ($verbose_level >= 1)
                        echo "==> UPDATE CARD QUERY: 	$QUERY\n";

                    $QUERY = "INSERT INTO cc_charge (id_cc_card, amount, chargetype, id_cc_card_subscription, charged_status, description) VALUES ('" .
                                $card['id'] . "', '" . $subscription['fee']  . "', '3','" . $subscription['card_subscription_id'] . "',1, '" . $subscription['product_name'] . "')";
                    if ($verbose_level >= 1)
                        echo "==> INSERT CHARGE QUERY: 	$QUERY\n";

                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                    $QUERY = "UPDATE cc_card_subscription SET paid_status = 2 WHERE id=" . $subscription['card_subscription_id'];
                    if ($verbose_level >= 1)
                        echo "==> UPDATE SUBSCRIPTION QUERY: 	$QUERY\n";

                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                    $mail = new Mail(Mail::$TYPE_SUBSCRIPTION_PAID,$card['id'] );
                    $mail -> replaceInEmail(Mail::$SUBSCRIPTION_FEE,$subscription['fee']);
                    $mail -> replaceInEmail(Mail::$SUBSCRIPTION_ID,$subscription['id']);
                    $mail -> replaceInEmail(Mail::$SUBSCRIPTION_LABEL,$subscription['product_name']);
                    //update status to paid

                    try {
                        $mail -> send();
                    } catch (A2bMailException $e) {
                        if ($verbose_level >= 1)
                            echo "[Sent mail failed : $e]";
                        write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
                    }

                } else {

                    $reference = generate_invoice_reference();

                    //CREATE INVOICE If a new card then just an invoice item in the last invoice
                    $field_insert = "date, id_card, title, reference, description, status, paid_status";
                    $date = date("Y-m-d h:i:s");
                    $card_id = $card['id'];
                    $title = gettext("SUBSCRIPTION INVOICE REMINDER");
                    $description = "Your credit was not enough to pay yours subscription automatically.\n";
                    $description .= "You have $billdaybefor_anniversary days to pay this invoice (REF: $reference ) or the account will be automatically disactived \n\n";
                    $value_insert = " '$date' , '$card_id', '$title','$reference','$description',1,0";
                    $instance_table = new Table("cc_invoice", $field_insert);

                    if ($verbose_level >= 1)
                        echo "INSERT INVOICE : $field_insert =>	$value_insert \n";
                    $id_invoice = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");

                    if (!empty ($id_invoice) && is_numeric($id_invoice)) {
                        $description = "Subscription (" . $subscription['product_name'] . ")";
                        $amount = $subscription['fee'];
                        $vat = 0;
                        $field_insert = "date, id_invoice, price, vat, description, id_ext, type_ext";
                        $instance_table = new Table("cc_invoice_item", $field_insert);
                        $value_insert = " '$date' , '$id_invoice', '$amount','$vat','$description','" . $subscription['card_subscription_id'] . "','SUBSCR'";
                        if ($verbose_level >= 1)
                            echo "INSERT INVOICE ITEM : $field_insert =>	$value_insert \n";
                        $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                    }

                    $mail = new Mail(Mail::$TYPE_SUBSCRIPTION_UNPAID, $card['id'] );
                    $mail -> replaceInEmail(Mail::$DAY_REMAINING_KEY, $billdaybefor_anniversary );
                    $mail -> replaceInEmail(Mail::$INVOICE_REF_KEY, $reference);
                    $mail -> replaceInEmail(Mail::$SUBSCRIPTION_FEE, $subscription['fee']);
                    $mail -> replaceInEmail(Mail::$SUBSCRIPTION_ID, $subscription['id']);
                    $mail -> replaceInEmail(Mail::$SUBSCRIPTION_LABEL, $subscription['product_name']);
                    //insert charge
                    $QUERY = "INSERT INTO cc_charge (id_cc_card, amount, chargetype, id_cc_card_subscription, invoiced_status, description) VALUES ('" . $card['id'] . "', '" . $subscription['fee']  . "', '3','" . $subscription['card_subscription_id'] . "',1, '" . $subscription['product_name'] . "')";
                    if ($verbose_level >= 1)
                        echo "==> INSERT CHARGE QUERY: 	$QUERY\n";
                    $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                    $QUERY = "UPDATE cc_card_subscription SET paid_status = 1 WHERE id=" . $subscription['card_subscription_id'];
                    if ($verbose_level >= 1)
                        echo "==> UPDATE SUBSCRIPTION QUERY : $QUERY\n";
                    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

                    try {
                        $mail -> send();
                    } catch (A2bMailException $e) {
                        if ($verbose_level >= 1)
                            echo "[Sent mail failed : $e]";
                        write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
                    }
                }
                $QUERY = "UPDATE cc_card_subscription SET last_run = '$last_run', next_billing_date = '$next_bill_date', limit_pay_date = '$limite_pay_date' WHERE id=" . $subscription['card_subscription_id'];
                if ($verbose_level >= 1)
                        echo "==> UPDATE SUBSCRIPTION QUERY : 	$QUERY\n";
                $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);

                break;

            case "unpaid" :
                // block the card
                $QUERY = "UPDATE cc_card SET status = 8 WHERE id=" . $subscription['card_id'];
                $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                if ($verbose_level >= 1)
                    echo "==> UPDATE CARD QUERY: 	$QUERY\n";

                $QUERY = "UPDATE cc_card_subscription SET paid_status = 3 WHERE id=" . $subscription['card_subscription_id'];
                if ($verbose_level >= 1)
                    echo "==> UPDATE SUBSCRIPTION QUERY: 	$QUERY\n";
                $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
                if ($verbose_level >= 1)
                    echo "==> UPDATE CARD QUERY: 	$QUERY\n";
                $mail = new Mail(Mail::$TYPE_SUBSCRIPTION_DISABLE_CARD, $subscription('card_id'));
                $mail -> replaceInEmail(Mail::$SUBSCRIPTION_FEE, $subscription['fee']);
                $mail -> replaceInEmail(Mail::$SUBSCRIPTION_ID, $subscription['id']);
                $mail -> replaceInEmail(Mail::$SUBSCRIPTION_LABEL, $subscription['product_name']);
                try {
                    $mail -> send();
                } catch (A2bMailException $e) {
                    if ($verbose_level >= 1)
                        echo "[Sent mail failed : $e]";
                    write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[Sent mail failed : $e]");
                }
                break;
        }

    }

    sleep(10);
}

// UPDATE THE SERVICE
foreach ($service_array as $key => $value) {
        $QUERY = "UPDATE cc_subscription_service SET datelastrun=now(), numberofrun=numberofrun+1, totalcardperform=totalcardperform+" . $value['totalcardperform'] .
                ", totalcredit = totalcredit + '".$value['totalcredit'] ."' WHERE id=$key";
    $result = $instance_table->SQLExec($A2B->DBHandle, $QUERY, 0);
}

if ($verbose_level >= 1)
    echo "#### END SUBSCRIPTION SERVICES \n";

write_log(LOGFILE_CRONT_SUBSCRIPTIONFEE, basename(__FILE__) . ' line:' . __LINE__ . "[#### BATCH PROCESS END ####]");
