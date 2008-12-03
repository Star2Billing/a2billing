#!/usr/bin/php -q
<?php
/***************************************************************************
 *            a2billing_invoice_cront.php
 *
 *  13 April 2007
 *  Purpose: To greate invoices for Each User.
 *  Copyright  2007  User : Belaid Arezqui
 *  ADD THIS SCRIPT IN A CRONTAB JOB
 *
 *  The sample above will run the script every day of each month at 6AM
	crontab -e
	0 6 1 * * php /var/lib/asterisk/agi-bin/libs_a2billing/crontjob/a2billing_invoice_cront.php
	
	
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

include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/interface/constants.php");
include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include (dirname(__FILE__)."/lib/Misc.php");
include (dirname(__FILE__)."/lib/A2B_invoice.php");

//Flag to show the debuging information
$verbose_level=2;

$groupcard = 5000;

// User's choice
//$sendemail = '"No'; 			// ('Yes'/'No'). disabled because smarty is needed
$enableminimalamount = 'off';	// ('on'/'off') if total <  $minimalamount, won't bill invoice
$minimalamount = 0;				// if $enableminimalamount == 'on'. in invoice currency.  
$customtemplate = ''; 			// '' = customer default
$choose_currency == ''; 		// '' = customer default
$billcalls = 'on';				// ('on'/'off')
$billcharges = 'on';			// ('on'/'off')
$nowdate = date('Y-m-d H:i:s');
$billday = date("j");		// 

$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);

write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__).' line:'.__LINE__."[#### CRONT INVOICE BEGIN ####]");

if (!$A2B -> DbConnect()){				
	echo "[Cannot connect to the database]\n";
	write_log(LOGFILE_CRONT_INVOICE, basename(__FILE__).' line:'.__LINE__."[Cannot connect to the database]");
	exit;
}

$instance_table = new Table();
$currencies_list = get_currencies($A2B -> DBHandle);


// CHECK COUNT OF CARD ON WHICH APPLY THE SERVICE
$QUERY = "SELECT count(*) FROM cc_card ";
$result = $instance_table -> SQLExec ($A2B -> DBHandle, $QUERY);

$nb_card = $result[0][0];

$nbpagemax = (ceil($nb_card/$groupcard));

if ($verbose_level>=1) echo "===> NB_CARD : $nb_card - NBPAGEMAX:$nbpagemax\n";

if (!($nb_card>0)){
	if ($verbose_level>=1) echo "[No card to create the Invoice]\n";		
	exit();
}		

$invoice = new A2B_Invoice($A2B -> DBHandle, $verbose_level);

for ($page = 0; $page < $nbpagemax; $page++) 
{
	if ($verbose_level >= 1)  echo "$page <= $nbpagemax \n";
	
	$Query_Customers = "SELECT id, invoiceday FROM cc_card";
	
	if ($A2B->config["database"]['dbtype'] == "postgres")
	{
		$Query_Customers .= " LIMIT $groupcard OFFSET ".$page*$groupcard;
	}
	else
	{
		$Query_Customers .= " LIMIT ".$page*$groupcard.", $groupcard";
	}
	$resmax = $instance_table -> SQLExec ($A2B ->DBHandle, $Query_Customers);
	
	if (is_array($resmax)){
		$numrow = count($resmax);
		if($verbose_level >= 2) print_r($resmax[0]);
	}else{
		$numrow = 0;
	}
	
	if($verbose_level >= 1) echo "\n Total Customers Found: ".$numrow;
	
	if ($numrow == 0) {
		if ($verbose_level>=1) echo "\n[No card to create the Invoice]\n";			
		exit();			
	}else{
		foreach($resmax as $Customer){
			
			$invoiceday = (is_numeric ($Customer[1]) && $Customer[1]>=1) ? $Customer[1] : 1;
			
			if ($billday != $invoiceday) {
				if ($verbose_level>=1)	echo "\nIt is not Customer ".$Customer[0]."	billday";
				continue;
			}
			
			$invoice->RetrieveInformation($Customer[0], $billcalls == "on", $billcharges == "on", $nowdate);
			$invoice->CreateInvoice($choose_currency);			
			$invoice->BillInvoice(($enableminimalamount == 'on'), $minimalamount, $customtemplate);

			// disabled because smarty is needed
			//if ($sendemail == 'Yes')
			//	$invoice->SendEMail($smarty);
		}
	}
}

?>
