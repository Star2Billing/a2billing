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
 *            a2billing_invoice_cront.php
 *
 *  Purpose: To generate invoices and for each user.
 *  Copyright  2009  User : Belaid Arezqui
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  The sample above will run the script every day of each month at 6AM
    crontab -e
    0 6 * * * php /usr/local/a2billing/Cronjobs/a2billing_invoice_cront.php

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
include (dirname(__FILE__) . "/lib/ProcessHandler.php");

if (!defined('PID')) {
    define("PID", "/var/run/a2billing/a2billing_batch_billing_pid.php");
}

// CHECK IF THE CRONT PROCESS IS ALREADY RUNNING
$pH= new ProcessHandler();
if ($pH->isActive()) {
        die(); // Already running!
        } else {
                $pH->activate();
                }

//Flag to show the debuging information
$verbose_level = 0;

$groupcard = 5000;
$oneday = 24 * 60 * 60;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log (LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[#### CRONT BILLING BEGIN ####]");

if (!$A2B->DbConnect()) {
    echo "[Cannot connect to the database]\n";
    write_log (LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
    exit;
}

$instance_table = new Table();

// CHECK COUNT OF CARD ON WHICH APPLY THE SERVICE
$QUERY = 'SELECT count(*) FROM cc_card';

$result = $instance_table->SQLExec($A2B->DBHandle, $QUERY);
$nb_card = $result[0][0];
$nbpagemax = (ceil($nb_card / $groupcard));

if ($verbose_level >= 1)
    echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card > 0)) {
    if ($verbose_level >= 1)
        echo "[No card to run the Invoice Billing Service]\n";
    write_log (LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the Invoice Billing service]");
    exit ();
}

if ($verbose_level >= 1)
    echo ("[Invoice Billing Service analyze cards on which to apply billing]");
write_log (LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[Invoice Billing Service analyze cards on which to apply billing]");

for ($page = 0; $page < $nbpagemax; $page++) {
    if ($verbose_level >= 1)
        echo "$page <= $nbpagemax \n";
    $Query_Customers = "SELECT id, vat, invoiceday, typepaid, credit FROM cc_card";

    if ($A2B->config["database"]['dbtype'] == "postgres") {
        $Query_Customers .= " LIMIT $groupcard OFFSET " . $page * $groupcard;
    } else {
        $Query_Customers .= " LIMIT " . $page * $groupcard . ", $groupcard";
    }

    $resmax = $instance_table->SQLExec($A2B->DBHandle, $Query_Customers);

    if (is_array($resmax)) {
        $numrow = count($resmax);
        if ($verbose_level >= 2)
            print_r($resmax[0]);
    } else {
        $numrow = 0;
    }

    if ($numrow == 0) {
        if ($verbose_level >= 1)
            echo "\n[No card to run the Invoice Billing Service]\n";
        write_log (LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the Invoice Billing service]");
        exit ();

    } else {

        foreach ($resmax as $Customer) {
            $invoiceday = (is_numeric($Customer['invoiceday']) && $Customer['invoiceday'] >= 1 && $Customer['invoiceday'] <= 28) ? $Customer['invoiceday'] : 1;
            if ($verbose_level >= 1)
                echo "\n Invoiceday = $invoiceday  -  Invoiceday db = " . $Customer['invoiceday'];

            // the value of invoiceday is between 1..28, dont make sense to bill customer on 29, 30, 31
            if (date("j", time()) != $invoiceday) {
                if ($verbose_level >= 1)
                    echo "\n We dont create an invoice today for this customer : " . $Customer['invoiceday'];
                continue;
            }

            //find the last billing
            $card_id = $Customer['id'];
            $date_now = date("Y-m-d");
            if (empty ($Customer['vat']) || !is_numeric($Customer['vat']))
                $vat = 0;
            else
                $vat = $Customer['vat'];

            // FIND THE LAST BILLING
            $billing_table = new Table('cc_billing_customer', 'id, date, id_invoice');
            $clause_last_billing = "id_card = " . $card_id;
            $result = $billing_table->Get_list($A2B->DBHandle, $clause_last_billing, "date", "desc");

            $call_table = new Table('cc_call', ' COALESCE(SUM(sessionbill),0)');
            $clause_call_billing = "card_id = " . $card_id . " AND ";
            $clause_charge = "id_cc_card = " . $card_id . " AND ";
            $desc_billing = "";
            $desc_billing_postpaid = "";
            $start_date = null;
            $lastbilling_invoice = null;
            if (is_array($result) && !empty ($result[0][0])) {
                if ($verbose_level >= 1)
                    echo "\n Find the last billing -> Id card : " . $result[0][0];

                $clause_call_billing .= "stoptime >= '" . $result[0][1] . "' AND ";
                $clause_charge .= "creationdate >= '" . $result[0][1] . "' AND  ";
                $desc_billing = "Calls cost between the " . $result[0][1] . " and " . $date_now;
                $desc_billing_postpaid = "Amount for period between the " .date("Y-m-d", strtotime($result[0][1]) + $oneday). " and " . $date_now;
                $start_date = $result[0][1];
                $lastbilling_invoice = $result[0][2];
            } else {
                $desc_billing = "Calls cost before the " . $date_now;
                $desc_billing_postpaid = "Amount for period before the " . $date_now;
            }

            // RETRIEVE THE LAST POSTPAID AMOUNT -SUM OF ALL INVOICE ITEMS UNPAID FOR A POSTPAID USER
            $lastpostpaid_amount = 0;
            $query_table = "cc_billing_customer LEFT JOIN cc_invoice ON cc_billing_customer.id_invoice = cc_invoice.id ";
            $query_table .= "LEFT JOIN (SELECT st1.id_invoice, TRUNCATE(SUM(st1.price),2) as total_price FROM cc_invoice_item AS st1 WHERE st1.type_ext ='POSTPAID' GROUP BY st1.id_invoice ) as items ON items.id_invoice = cc_invoice.id";
            $invoice_table = new Table($query_table,'SUM( items.total_price) as total');
            $lastinvoice_clause = "cc_billing_customer.id_card = $card_id AND cc_invoice.paid_status=0";
            $result_lastinvoice = $invoice_table ->Get_list($A2B->DBHandle, $lastinvoice_clause);
            if (is_array($result_lastinvoice) && !empty($result_lastinvoice[0][0])) {
                $lastpostpaid_amount = $result_lastinvoice [0][0];
            }

            // INSERT CUSTOMER BILLING
            $field_insert = "id_card";
            $value_insert = " '$card_id'";
            if (!empty ($start_date)) {
                $field_insert .= ", start_date";
                $value_insert .= ", '".$start_date."'";
            }
            $instance_table = new Table("cc_billing_customer", $field_insert);
            $id_billing = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
            if ($verbose_level >= 2)
                    echo "\n Add billing -> Id card : " . $value_insert;

            $clause_call_billing .= "stoptime < '" . $date_now . "' ";
            $clause_charge .= "creationdate < '" . $date_now . "' ";
            $result = $call_table->Get_list($A2B->DBHandle, $clause_call_billing);

            // COMMON BEHAVIOUR FOR PREPAID AND POSTPAID -> GENERATE A RECEIPT FOR THE CALLS OF THE LAST PERIOD
            if (is_array($result) && is_numeric($result[0][0])) {
                $amount_calls = $result[0][0];
                $amount_calls = ceil($amount_calls * 100) / 100;
                /// create receipt
                $field_insert = "id_card, title, description,status";
                $title = gettext("SUMMARY OF CALLS");
                $description = gettext("Summary of the calls charged since the last billing");
                $value_insert = "  '$card_id', '$title','$description',1";
                $instance_table = new Table("cc_receipt", $field_insert);
                $id_receipt = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                if ($verbose_level >= 2)
                        echo "\n Add Receipt for the call of the last period :> " . $value_insert;

                if (!empty ($id_receipt) && is_numeric($id_receipt)) {
                    $description = $desc_billing;
                    $field_insert = " id_receipt, price, description, id_ext, type_ext";
                    $instance_table = new Table("cc_receipt_item", $field_insert);
                    $value_insert = " '$id_receipt', '$amount_calls','$description','" . $id_billing . "','CALLS'";
                    $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                    if ($verbose_level >= 2)
                        echo "\n Add Receipt Items for the call of the last period :> " . $value_insert;
                }
            }

            // GENERATE RECEIPT FOR CHARGE ALREADY PAID
            $table_charge = new Table("cc_charge", "*");
            $result = $table_charge->Get_list($A2B->DBHandle, $clause_charge . " AND charged_status = 1");
            if (is_array($result)) {
                $field_insert = " id_card, title, description, status";
                $title = gettext("SUMMARY OF CHARGE");
                $description = gettext("Summary of the paid charges since the last billing.");
                $value_insert = " '$card_id', '$title', '$description', 1";
                $instance_table = new Table("cc_receipt", $field_insert);
                $id_receipt = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                if ($verbose_level >= 2)
                    echo "\n Add Receipt for the charges already paid :> " . $value_insert;

                if (!empty ($id_receipt) && is_numeric($id_receipt)) {
                    foreach ($result as $charge) {
                        $description = gettext("CHARGE :") . $charge['description'];
                        $amount = $charge['amount'];
                        $field_insert = "date, id_receipt, price, description, id_ext, type_ext";
                        $instance_table = new Table("cc_receipt_item", $field_insert);
                        $value_insert = " '" . $charge['creationdate'] . "' , '$id_receipt', '$amount','$description','" . $charge['id'] . "','CHARGE'";
                        $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                        if ($verbose_level >= 2)
                            echo "\n Add Receipt Items for the charges already paid :> " . $value_insert;
                    }
                }
            }
            $total =0;
            $total_vat =0;
            // GENERATE INVOICE FOR CHARGE NOT YET CHARGED
            $table_charge = new Table("cc_charge", "*");
            $result = $table_charge->Get_list($A2B->DBHandle, $clause_charge . " AND charged_status = 0 AND invoiced_status = 0");
            $last_invoice = null;
            if (is_array($result) && sizeof($result) > 0) {
                $reference = generate_invoice_reference();
                $field_insert = "id_card, title, reference, description, status, paid_status";
                $title = gettext("BILLING");
                $description = gettext("Invoice for the unpaid charges since the last billing.") . " " . $desc_billing_postpaid;
                $invoice_title = $title;
                $invoice_reference =$reference;
                $invoice_description = $description;
                $value_insert = " '$card_id', '$title', '$reference', '$description', 1, 0";
                $instance_table = new Table("cc_invoice", $field_insert);
                $id_invoice = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                if ($verbose_level >= 2)
                    echo "\n Add Invoice for the unpaid charges :> " . $value_insert;

                if (!empty ($id_invoice) && is_numeric($id_invoice)) {
                    $last_invoice = $id_invoice;
                    foreach ($result as $charge) {
                        $description = gettext("CHARGE :") . $charge['description'];
                        $amount = $charge['amount'];
                        $total = $total + $amount;
                        $total_vat =$total_vat + round($amount *(1+($vat/100)),2);
                        $field_insert = "date, id_invoice, price, vat, description, id_ext, type_ext";
                        $instance_table = new Table("cc_invoice_item", $field_insert);
                        $value_insert = " '" . $charge['creationdate'] . "' , '$id_invoice', '$amount', '$vat', '$description', '" . $charge['id'] . "', 'CHARGE'";
                        $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                        if ($verbose_level >= 2)
                            echo "\n Add Invoice Items for the unpaid charges :> " . $value_insert;
                    }
                }
            }

            // POSTPAID BILLING
            if ($Customer['typepaid'] == 1 && is_numeric($Customer['credit']) && ($Customer['credit']+$lastpostpaid_amount) < 0) {
                // GENERATE AN INVOICE TO COMPLETE THE BALANCE
                if (!empty($last_invoice)) {
                    $id_invoice = $last_invoice;
                } else {
                    $reference = generate_invoice_reference();
                    $field_insert = " id_card, title, reference, description, status, paid_status";
                    $title = gettext("BILLING");
                    $description = gettext("Invoice for POSTPAID");
                    $invoice_title = $title;
                    $invoice_reference =$reference;
                    $invoice_description = $description;
                    $value_insert = " '$card_id', '$title','$reference','$description',1,0";
                    $instance_table = new Table("cc_invoice", $field_insert);
                    $id_invoice = $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                    if ($verbose_level >= 2)
                        echo "\n Add Invoice :> " . $value_insert;
                }
                if (!empty ($id_invoice) && is_numeric($id_invoice)) {
                    $last_invoice = $id_invoice;
                    $description = $desc_billing_postpaid;
                    $amount = abs($Customer['credit']+$lastpostpaid_amount);
                    $total = $total + $amount;
                    $total_vat =$total_vat + round($amount *(1+($vat/100)),2);
                    $field_insert = " id_invoice, price, vat, description, id_ext, type_ext";
                    $instance_table = new Table("cc_invoice_item", $field_insert);
                    $value_insert = " '$id_invoice', '$amount','$vat','$description','" . $id_billing . "','POSTPAID'";
                    $instance_table->Add_table($A2B->DBHandle, $value_insert, null, null, "id");
                    if ($verbose_level >= 2)
                        echo "\n Add Invoice Item :> " . $value_insert;
                }
            }

            if (!empty($last_invoice)) {
                $param_update_billing = "id_invoice = '".$last_invoice."'";
                $clause_update_billing = " id= ".$id_billing;
                $billing_table ->Update_table($A2B->DBHandle,$param_update_billing,$clause_update_billing);
                if ($verbose_level >= 2)
                    echo "\n Update Billing :> " . $param_update_billing . " WHERE " . $clause_update_billing;
            }

            // Send a mail for invoice to pay
            if (!empty($last_invoice)) {
                $total = round($total,2);
                try {
                    $mail = new Mail(Mail::$TYPE_INVOICE_TO_PAY, $card_id);
                    $mail->replaceInEmail(Mail::$INVOICE_REFERENCE_KEY, $invoice_reference);
                    $mail->replaceInEmail(Mail::$INVOICE_TITLE_KEY, $invoice_title);
                    $mail->replaceInEmail(Mail::$INVOICE_DESCRIPTION_KEY, $invoice_description);
                    $mail->replaceInEmail(Mail::$INVOICE_TOTAL_KEY, $total);
                    $mail->replaceInEmail(Mail::$INVOICE_TOTAL_VAT_KEY, $total_vat);
                    $mail->send();
                    if ($verbose_level >= 2)
                        echo "\n Email sent for invoice to pay, card id :> ".$card_id;
                } catch (A2bMailException $e) {
                    $error_msg = $e->getMessage();
                    if ($verbose_level >= 1)
                        echo "Sent mail error : ".$error_msg;
                }
            }

            if ($verbose_level >= 2)
                echo "\n Go to next Customer";

        } // END foreach($resmax as $Customer)
    }
}

if ($verbose_level >= 1)
    echo "------- CRONT BILLING END ------- \n";

write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "------- CRONT BILLING END -------");
