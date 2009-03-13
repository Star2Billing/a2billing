#!/usr/bin/php -q
<?php

/***************************************************************************
 *            a2billing_invoice_cront.php
 *
 *  Purpose: To greate the invoices.
 *  Copyright  2009  @ Belaid Arezqui
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  The sample above will run the script every day of each month at 6AM
	crontab -e
	0 6 1 * * php /usr/local/a2billing/Cronjobs/a2billing_invoice_cront.php
	
	
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
//dl("pgsql.so"); // remove "extension= pgsql.so !

include_once (dirname(__FILE__) . "/lib/Class.Table.php");
include (dirname(__FILE__) . "/lib/interface/constants.php");
include (dirname(__FILE__) . "/lib/Class.A2Billing.php");
include (dirname(__FILE__) . "/lib/Misc.php");

//Flag to show the debuging information
$verbose_level = 0;

$groupcard = 5000;

$A2B = new A2Billing();
$A2B->load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[#### CRONT INVOICE BEGIN ####]");

if (!$A2B->DbConnect()) {
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[Cannot connect to the database]");
	exit;
}

$instance_table = new Table();
$currencies_list = get_currencies($A2B->DBHandle);

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
	write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the Invoice Billing service]");
	exit ();
}

if ($verbose_level >= 1)
	echo ("[Invoice Billing Service analyze cards on which to apply service]");
write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[Invoice Billing Service analyze cards on which to apply service]");

for ($page = 0; $page < $nbpagemax; $page++) {
	if ($verbose_level >= 1)
		echo "$page <= $nbpagemax \n";
	$Query_Customers = "SELECT id, creationdate, firstusedate, expirationdate, enableexpire, expiredays, username, vat, invoiceday FROM cc_card ";

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
	if ($verbose_level >= 1)
		echo "\n Total Customers Found: " . $numrow;

	if ($numrow == 0) {
		if ($verbose_level >= 1)
			echo "\n[No card to run the Invoice Billing Service]\n";
		write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__) . ' line:' . __LINE__ . "[No card to run the Invoice Billing service]");
		exit ();

	} else {

		foreach ($resmax as $Customer) {

			// Check if this is the correct date to generate the invoice	
			$invoiceday = (is_numeric($Customer[8]) && $Customer[8] >= 1) ? $Customer[8] : 1;
			if ($verbose_level >= 1)
				echo "\n Invoiceday = $invoiceday  -  Invoiceday db = " . $Customer[8];

			// the value of invoiceday is between 1..28, dont make sense to bill customer on 29, 30, 31
			if (date("j", time()) != $invoiceday || $invoiceday > 28) {
				if ($verbose_level >= 1)
					echo "\n We dont create an invoice today for this customer : " . $Customer[6];
				continue;
			}

			// Here we have to check for the Last Invoice date to set the Cover Start date. 
			// if a user dont have a Last invocie then we have to Set the Cover Start date to it Creation Date.
			$query_billdate = "SELECT CASE WHEN max(cover_enddate) is NULL THEN '0000-00-00 00:00:00' ELSE max(cover_enddate) END FROM cc_invoices WHERE cardid='$Customer[0]'";
			if ($verbose_level >= 1)
				echo "\nQUERY_BILLDATE = $query_billdate";

			$resdate = $instance_table->SQLExec($A2B->DBHandle, $query_billdate);
			if ($verbose_level >= 2)
				print_r($resdate);
			if (is_array($resdate) && count($resdate) > 0 && $result[0][0] != "0000-00-00 00:00:00") {
				// Customer Last Invoice Date
				$cover_startdate = $resdate[0][0];
			} else {
				// Customer Creation Date			
				$cover_startdate = $Customer[1];
			}
			if ($verbose_level >= 1)
				echo "\n Cover Start Date for '$Customer[6]': " . $cover_startdate;

			$FG_TABLE_CLAUSE = " t1.username='$Customer[6]' AND t1.starttime > '$cover_startdate'";

			// init totalcost
			$totalcost = 0;
			$totaltax = 0;
			$totalcall = 0;
			$totalminutes = 0;
			$totalcharge = 0;

			//************************************* CALLS SECTION *************************************************
			//$Query_Destinations = "SELECT destination, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) AS nbcall FROM cc_call t1 WHERE (t1.sipiax<>2 AND t1.sipiax<>3) AND ".$FG_TABLE_CLAUSE." GROUP BY destination";		
			$Query_Destinations = "SELECT destination, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) AS nbcall FROM cc_call t1 WHERE " .
			$FG_TABLE_CLAUSE . " GROUP BY destination";
			$list_total_destination = $instance_table->SQLExec($A2B->DBHandle, $Query_Destinations);
			if (is_array($list_total_destination)) {
				$num = count($list_total_destination);
			} else {
				$num = 0;
			}

			if ($verbose_level >= 1) {
				echo "\n Query_Destinations = $Query_Destinations";
				echo "\n Number of Destinatios for '$Customer[6]' Found: " . $num;
			}

			//Get the calls destination wise and calculate total cost			
			if (is_array($list_total_destination) && count($list_total_destination) > 0) {
				foreach ($list_total_destination as $data) {
					$totalcall += $data[3];
					$totalminutes += $data[1];
					$totalcost += $data[2];
				}
			}
			if ($verbose_level >= 1) {
				echo "\n AFTER DESTINATION : totalcall = $totalcall - totalminutes = $totalminutes - totalcost = $totalcost ";
			}

			//************************************* CHARGE SECTION *************************************************
			// chargetype : 1 - connection charge for DID setup, 2 - Montly charge for DID use, 3 - Subscription fee, 4 - Extra Charge, etc...
			$FG_TABLE_CLAUSE = " id_cc_card='$Customer[0]' AND creationdate > '$cover_startdate'";
			$QUERY_CHARGE = "SELECT id, id_cc_card, iduser, creationdate, amount, chargetype, description, id_cc_did, currency, id_cc_subscription_fee FROM cc_charge" .
			" WHERE $FG_TABLE_CLAUSE";
			$list_total_charge = $instance_table->SQLExec($A2B->DBHandle, $QUERY_CHARGE, 1);
			$num = 0;
			$num = count($list_total_charge);
			if ($verbose_level >= 1) {
				echo "\n QUERY_CHARGE = $QUERY_CHARGE";
				echo "\n Number of Charge for '$Customer[6]' Found: " . $num;
			}

			//Get the calls destination wise and calculate total cost			
			if (is_array($list_total_charge) && count($list_total_charge) > 0) {
				foreach ($list_total_charge as $data) {
					$charge_amount = $data[4];
					$charge_currency = $data[8];
					$base_currency = $A2B->config['global']['base_currency'];
					$charge_converted = convert_currency($currencies_list, $charge_amount, strtoupper($charge_currency), strtoupper($base_currency));
					if ($verbose_level >= 1) {
						echo "\n charge_amount = $charge_amount - charge_currency = $charge_currency " .
						" - charge_converted=$charge_converted - base_currency=$base_currency";
					}
					$totalcharge += 1;
					$totalcost += $charge_converted;
				}
			}
			if ($verbose_level >= 1) {
				echo "\n AFTER DESTINATION : totalcharge = $totalcharge - totalcost = $totalcost";
			}

			//************************************* INSERT INVOICE *************************************************			
			if ($Customer[7] > 0 && $totalcost > 0) {
				$totaltax = ($totalcost / 100) * $Customer[7];
			}

			// Here we have to Create a Insert Statement to insert Records into the Invoices Table.
			$Query_Invoices = "INSERT INTO cc_invoices (cardid, orderref, invoicecreated_date, cover_startdate, cover_enddate, amount, tax, total, invoicetype," .
			"filename) VALUES ('$Customer[0]', NULL, NOW(), '$cover_startdate', NOW(), $totalcost, $totaltax, $totalcost + $totaltax, NULL, NULL)";
			$instance_table->SQLExec($A2B->DBHandle, $Query_Invoices);

			if ($verbose_level >= 1) {
				echo "\n Total Cost for '$Customer[0]': " . $totalcost;
				echo "\n Query_Invoices=$Query_Invoices \n";
				echo "\n ################################################################################# \n\n";
			}

		} // END foreach($resmax as $Customer)
	}
}