<?php

// To sort charge type in correct category
$chargetype_list = array();
$chargetype_list[1] = gettext("Connection charge for DID setup");
$chargetype_list[2] = gettext("Monthly Charge for DID use");
$chargetype_list[3] = gettext("Subscription fee");
$chargetype_list[4] = gettext("Extra charge");
$chargetype_list[5] = gettext("Product Purchase");
$chargetype_list[6] = gettext("Refund");

Class	A2B_Invoice {

	////
	// System
	////
		var		$verbose_level;
		var		$instance_table;
		var		$DB_Handle;
		var		$base_currency;
		var		$currencies_list;
		
	////
	//	Initial Information for generation
	//////
		// Additional filters for Invoice menu
		// filter_***_op:
		// 1: is
		// 2: begins with
		// 3: contains
		// 4: ends with
		// 5: different from
		var		$filter_provider;
		var		$filter_trunk;
		var		$filter_destination;
		var		$filter_destination_op;
		var 	$filter_source;
		var		$filter_source_op;
	////
	//	Collected information
	//////
		// Will be computed according to previous invoices
		var		$cover_call_startdate;
		var		$cover_call_enddate;
		var		$cover_charge_startdate;
		var		$cover_charge_enddate;
		var		$previous_balance;

		// Information about invoiced customer
		var		$customer_cardid;
		var		$customer_username;
		var		$customer_creation_date;
		var		$customer_VAT;
		var		$customer_creationdate;
		var		$customer_lastname;
		var		$customer_firstname;
		var		$customer_address;
		var		$customer_city;
		var		$customer_state;
		var		$customer_country;
		var		$customer_zipcode;
		var		$customer_phone;
		var		$customer_email;
		var		$customer_fax;
		var		$customer_invoicetemplate;
		var		$customer_outstandingtemplate;
		var		$customer_currentbalance;
		var		$customer_currency;
		
		// List of Calls Grouped by destination
		// [n][0]: destination
		// [n][1]: totaltime
		// [n][2]: totalsellcost
		// [n][3]: nbcall
		// [n][4]: totalbuycost
		var		$list_total_destination;
		
		// List of Charges
		// [n][0]:	id
		// [n][1]:	id_cc_card
		// [n][2]:	iduser
		// [n][3]: 	creationdate
		// [n][4]:	amount
		// [n][5]:	currency
		// [n][6]:	chargetype
		// [n][7]:	description
		// [n][8]:	id_cc_did
		// [n][9]:	cover_to
		// [n][10]: cover_from
		// [n][11]: id_cc_card_subscription
		// [n][12]: cc_card_subscription -> product name
		// [n][13]: cc_subscription_fee -> label
		var		$list_total_charge;
	////
	// 	Generated Invoice
	//////
		// Total
		var 	$invoice_currency;
		var		$invoice_subtotal;
		var		$invoice_tax;
		var		$invoice_total;
				
		// List of invoice items
		// [n][0]:  invoicesection
		// [n][1]:  designation
		// [n][2]:  sub_designation
		// [n][3]:  start_date
		// [n][4]:  end_date
		// [n][5]:  bill_date
		// [n][6]:  calltime
		// [n][7]:  nbcalls
		// [n][8]:  quantity
		// [n][9]:  buy_price
		// [n][10]: price	
		var		$list_items;
		var		$list_category_items; 	// List items, once sorted. 3 dims array	
	////
	// Billed Invoice (official)
	//////
		// Storage
		var		$billedinvoice_id;
		var		$billedinvoice_default_template;		
		var		$billedinvoice_filename;	// PDF file
		var		$billedinvoice_creationdate;

		// Payment status:
		// 0: UNPAID
		// 1: SENT-UNPAID
		// 2: SENT-PAID
		// 3: PAID
		var		$billedinvoice_paymentstatus;
		
		
		
////
////	Initialisation & Global Methods
////	
	function __construct($DB_Handle, $verbose_level=0) {		
		
		$this->DB_Handle 			= $DB_Handle;
		$this->instance_table		= new Table();
		$this->verbose_level		= $verbose_level;
		$this->currencies_list		= get_currencies($this->DB_Handle);
		
		global $A2B;				
		$this->base_currency		= $A2B->config['global']['base_currency'];	
	}
	
	function RetrieveInformation($customer_cardid, $billcalls, $billcharges, $enddate) {
				
		$this->ReadCardInfo($customer_cardid);
		$this->FindCoverDates($billcalls, $billcharges, $enddate);
		$this->ListCalls();
		$this->ListCharges();		
	}
////
////	Gathering Information
////
	//	Look for older invoices to start from 
	//	base on customer_cardid 
	//	generate cover_(call/charge)_(start/end)date
	function FindCoverDates($billcalls, $billcharges, $enddate) {
		
		if (! isset($this->customer_cardid))
			throw new Exception('Unable to find cover dates since customer_cardid not set');
			
		// Here we have to check for the Last Invoice date to set the Cover Start date. 
		// if a user dont have a Last invocie then we have to Set the Cover Start date to it Creation Date.
		$query_billdate = "SELECT CASE WHEN max(cover_call_enddate)   is NULL THEN '0001-01-01 00:01:00' ELSE max(cover_call_enddate)   END, ".
						         "CASE WHEN max(cover_charge_enddate) is NULL THEN '0001-01-01 00:01:00' ELSE max(cover_charge_enddate) END  ".
						   		 "FROM cc_invoice WHERE cardid='$this->customer_cardid'";
		if ($this->verbose_level>=1) echo "\nQUERY_BILLDATE = $query_billdate";
		
		$resdate = $this->instance_table -> SQLExec ($this->DB_Handle, $query_billdate);
		if ($this->verbose_level >= 2) print_r($resdate);
		
		if (!is_array($resdate))
			throw new Exception('Unable to find cover dates');
					
		// Call Dates
		$this->cover_call_startdate = ($resdate[0][0] != "0001-01-01 00:01:00") ? 
				$resdate[0][0] 					// Customer Last Invoice Date
			:	$this->customer_creationdate; 	// Customer Creation Date	
	
		$this->cover_call_enddate = ($billcalls == "Yes") ?
				$enddate
			:	$this->cover_call_startdate;
				
		// Charge Dates
		$this->cover_charge_startdate = ($resdate[0][1] != "0001-01-01 00:01:00") ?
			 	$resdate[0][1]					// Customer Last Invoice Date
			:	$this->customer_creationdate;	// Customer Creation Date
				
		$this->cover_charge_enddate = ($billcharges == "Yes") ?
				$enddate
			:	$this->cover_charge_startdate;
		
		// Display Result
		if($this->verbose_level >= 1)	{
			echo "\n Cover Calls for '$this->customer_username', Start Date:".$this->cover_call_startdate.', End Date:'.$this->cover_call_enddate;
			echo "\n Cover Charges for '$this->customer_username', Start Date:".$this->cover_charge_startdate.', End Date:'.$this->cover_charge_enddate;
		}
		
		// If Last Invoice for Call and Dates is the same, then we can get balance of previous invoice
		if (($this->cover_call_startdate == $this->cover_charge_startdate) && ($this->cover_call_startdate != $this->customer_creationdate)) {

			$query_lastbalance = "SELECT	current_balance".
								" FROM		cc_invoice".
						   		" WHERE 	cardid='$this->customer_cardid'".
						   		" ORDER BY 	cover_call_enddate DESC".
						   		" LIMIT		1";
			if ($this->verbose_level>=1) echo "\nQUERY_LASTBALANCE = $query_lastbalance";
			
			$reslastbalance = $this->instance_table -> SQLExec ($this->DB_Handle, $query_lastbalance);
			if ($this->verbose_level >= 2) print_r($reslastbalance);
			
			if (is_array($reslastbalance) && count($reslastbalance) == 1)
				$previous_balance = $reslastbalance[0][0];
			else
				throw new Exception('Unable to find Previous Invoice');
		}
	}
	
	// List calls according to:
	//  * filters
	//	* billing period
	//	* customer
	// Group by destination	
	function ListCalls() {
		
		if (! $this->IsCoveringCalls())	return;
		
		$Query_Destinations  = "SELECT ca.destination, sum(ca.sessiontime), sum(ca.sessionbill), count(*), sum(ca.buycost) FROM cc_call ca";
		
		if (isset($this->filter_provider))
			$Query_Destinations .= " JOIN cc_trunk tr USING(id_trunk)";
					
		$Query_Destinations .= " WHERE ca.starttime >= '$this->cover_call_startdate' AND ca.starttime < '$this->cover_call_enddate'";
		
		if (isset($this->customer_username))
			$Query_Destinations .= " AND ca.username='$this->customer_username'";
			
		if (isset($this->filter_trunk))
			$Query_Destinations .= " AND ca.id_trunk='$this->filter_trunk'";
			
		if (isset($this->filter_provider))
			$Query_Destinations .= "$Query_Destinations .= tr.id_provider='$this->filter_provider'";
			
		if (isset($this->filter_destination))
			switch ($this->filter_destination_op) {                         
	                case 1: $Query_Destinations .= " AND ca.calledstation = '".    $this->filter_destination. "'"; break;
	                case 2: $Query_Destinations .= " AND ca.calledstation LIKE '". $this->filter_destination."%'"; break;
 	                case 4: $Query_Destinations .= " AND ca.calledstation LIKE '%".$this->filter_destination. "'"; break;
 	                case 5: $Query_Destinations .= " AND ca.calledstation <> '".   $this->filter_destination. "'"; break;
	                default:
	                case 3: $Query_Destinations .= " AND ca.calledstation LIKE '%".$this->filter_destination."%'"; break;
 	    	}
 	    	
		if (isset($this->filter_source))
			switch ($this->filter_source_op) {
	                case 1: $Query_Destinations .= " AND ca.src = '".    $this->filter_source. "'"; break;
	                case 2: $Query_Destinations .= " AND ca.src LIKE '". $this->filter_source."%'"; break;
 	                case 4: $Query_Destinations .= " AND ca.src LIKE '%".$this->filter_source. "'"; break;
 	                case 5: $Query_Destinations .= " AND ca.src <> '".   $this->filter_source. "'"; break;
	                default:
	                case 3: $Query_Destinations .= " AND ca.src LIKE '%".$this->filter_source."%'"; break;
 	    	}
 	    												  
		$Query_Destinations .= " GROUP BY destination";
		if($this->verbose_level >= 1)	echo "\n Query_Destinations = $Query_Destinations";
		
		$this->list_total_destination = $this->instance_table -> SQLExec ($this->DB_Handle, $Query_Destinations);
		if (!is_array($this->list_total_destination))
			throw new Exception('Unable to list calls');
		if ($this->verbose_level >= 2)	print_r($this->list_total_destination); 
		
		if ($this->verbose_level >= 1)	echo "\n Number of Destinations for '$this->customer_username' Found: ".count($this->list_total_destination);
	}
	
	// List Charges
	function ListCharges() {

		if (! $this->IsCoveringCharges())	return;
		
		$QUERY_CHARGE = "SELECT cc.id, cc.id_cc_card, cc.iduser, cc.creationdate, cc.amount, cc.currency, cc.chargetype,  cc.description, ".
							  " cc.id_cc_did, cc.cover_to, cc.cover_from, cc.id_cc_card_subscription, cs.product_name, cf.label".
					    " FROM cc_charge AS cc".
					    " LEFT OUTER JOIN cc_card_subscription AS cs ON cs.id = cc.id_cc_card_subscription".
					    " LEFT OUTER JOIN cc_subscription_fee AS cf ON cf.id = cs.id_subscription_fee".
						" WHERE creationdate >= '$this->cover_charge_startdate' AND creationdate < '$this->cover_charge_enddate'";
		if (isset($this->customer_cardid))
			$QUERY_CHARGE .= " AND cc.id_cc_card='$this->customer_cardid'";
		if ($this->verbose_level >= 1)	echo "\n QUERY_CHARGE = $QUERY_CHARGE";
			
		$this->list_total_charge = $this->instance_table -> SQLExec ($this->DB_Handle, $QUERY_CHARGE, 1);

		if (! is_array($this->list_total_charge))
			throw new Exception('Unable to list charges');
		if ($this->verbose_level >= 2)	print_r($this->list_total_charge); 
		
		if ($this->verbose_level >= 1)	echo "\n Number of Charge for '$this->customer_username' Found: ".count($this->list_total_charge);
	}
	
	// Retrieve customer information from database, 
	// with $customer_cardid, if doesn't exist
	// with	$customer_username, if doesn't exist, exit	
	function ReadCardInfo($customer_cardid, $customer_username) {
		
		$QUERY = "SELECT creationdate, lastname, firstname, address, city, state, co.countryname, zipcode, phone, email, fax, vat, username, ca.id, template_invoice, template_outstanding, credit, currency".
				" FROM cc_card AS ca JOIN cc_country AS co ON ca.country = co.countrycode ";

		// If this invoice is not for only one customer
		if (isset($customer_cardid) && $customer_cardid != '')
			$QUERY .= "WHERE ca.id = '$customer_cardid'";
		elseif (isset($customer_username) && $customer_username != '')
			$QUERY .= "WHERE ca.username = '$customer_username'";
		else	
			throw new Exception('Unable to retrieve information on customer since his id is unknown'); 
			
		if ($this->verbose_level >= 1) echo "\nQUERY_CARD = $QUERY";
		
		$rescard = $this->instance_table->SQLExec($this->DB_Handle, $QUERY);

		if (!is_array($rescard) || count($rescard) != 1) 
			throw new Exception("Cannot find card with id=".$customer_cardid);
		
		if ($this->verbose_level >= 2)	print_r($rescard);
		
		$this->customer_creationdate		= $rescard[0][0];
		$this->customer_lastname			= $rescard[0][1];
		$this->customer_firstname			= $rescard[0][2];
		$this->customer_address				= $rescard[0][3];
		$this->customer_city				= $rescard[0][4];
		$this->customer_state 				= $rescard[0][5];
		$this->customer_country 			= $rescard[0][6];
		$this->customer_zipcode 			= $rescard[0][7];
		$this->customer_phone 				= $rescard[0][8];
		$this->customer_email 				= $rescard[0][9];
		$this->customer_fax 				= $rescard[0][10];
		$this->customer_VAT 				= $rescard[0][11];
		$this->customer_username			= $rescard[0][12];
		$this->customer_cardid				= $rescard[0][13];
		$this->customer_invoicetemplate		= $rescard[0][14];
		$this->customer_outstandingtemplate	= $rescard[0][15];
		$this->customer_currentbalance 		= $rescard[0][16];
		$this->customer_currency	 		= $rescard[0][17];		
	}
////
////	Piece of information about gathered information
////	All of them return booleans
////
	// Means that it can be billed
	function IsOfficial() {
		return (	!(isset($this->filter_provider) ||	isset($this->filter_trunk) 	||	isset($this->filter_destination) ||	isset($this->filter_source)) 
				&&	isset($this->customer_username)
				);
	}

	// Calls are covered 
	function IsCoveringCalls() {
		return (isset($this->cover_call_startdate) && isset($this->cover_call_enddate) && ($this->cover_call_startdate != $this->cover_call_enddate));
	}
	
	// Charges are covered
	function IsCoveringCharges() {
		return (isset($this->cover_charge_startdate) && isset($this->cover_charge_enddate) && ($this->cover_charge_startdate != $this->cover_charge_enddate));
	}
	
	// Is it a billed invoice ?
	function IsBilled() {
		return (isset($this->billedinvoice_id));
	}
	
////
////	Generate Invoice
////
	// Create an invoice from current information:
	//  * card
	//	* list of calls
	//	* list of charges
	//	* invoice_currency	
	function CreateInvoice($invoice_currency = '') {
			
		$this->ResetInvoice($invoice_currency);
		$this->GenerateCallItems();
		$this->GenerateChargeItems();		
		$this->ComputeTotal();
		
		$this->SortItems();
	}

	// Prepare the invoice
	function ResetInvoice($invoice_currency) {
	
		if ($invoice_currency != '') {
			
			$this->invoice_currency = $invoice_currency;
			if ($this->verbose_level >=1)	echo "\n Using custom currency";
			
		} elseif (isset($this->customer_currency) && $this->customer_currency!='') {
			
			$this->invoice_currency = $this->customer_currency;
			if ($this->verbose_level >=1)	echo "\n Using customer currency";
			
		} else {
			
			global $A2B;					
			$this->invoice_currency	= $A2B->config['global']['base_currency'];
			if ($this->verbose_level >=1)	echo "\n Using base currency";
		}
		
		
		if ($this->verbose_level >=1) echo "\nInvoice Currency is : $this->invoice_currency";
		
		$this->list_items 	= Array();
	}
	
	// Add Call Items 
	function GenerateCallItems() {
			
		if (! $this->IsCoveringCalls())	{
			
			if ($this->verbose_level >= 1) echo "\nCalls not covered. Skipping CallItems generation.";
			return;
		}
		
		$nb_items = count($this->list_items);
		
		//Get the calls destination wise and calculate total cost			
		if (is_array($this->list_total_destination)) {
			
			foreach ($this->list_total_destination as $data)
				$this->list_items[] = Array(
											gettext('Calls'),	// invoicesection
											$data[0],			// designation
											'',					// sub_designation
											'',					// start_date
											'',					// end_date
											'',					// bill_date
											$data[1],			// calltime		
											$data[3],			// nbcalls
											1, 					// quantity
											convert_currency($this->currencies_list, $data[4], strtoupper($this->base_currency), strtoupper($this->invoice_currency)),		// buy_price 
											convert_currency($this->currencies_list, $data[2], strtoupper($this->base_currency), strtoupper($this->invoice_currency))			// price
											);

		} else throw new Exception('Unable to generate Call Items because Calls were not listed');
		
		if ($this->verbose_level >= 1)	echo "\n".(count($this->list_items) - $nb_items).' call items generated';
	}

	// Add Charge Items 
	function GenerateChargeItems() {
	
		if (! $this->IsCoveringCharges())	{
			
			if ($this->verbose_level >= 1) echo "\nCharges not covered. Skipping ChargeItems generation.";
			return;
		}
		
		global $chargetype_list;
		
		$nb_items = count($this->list_items);
		
		//Get the calls destination wise and calculate total cost			
		if (is_array($this->list_total_charge)) {
			
			foreach ($this->list_total_charge as $data) {
				
				 $chargeitem = Array(
											$chargetype_list[$data[6]],				// invoicesection
											($data[11] > 0)? $data[13] : $data[7],	// designation
											($data[11] > 0)? $data[12] : '',		// sub_designation
											$data[10],								// start_date
											$data[9],								// end_date
											$data[3],								// bill_date
											0,										// calltime		
											0,										// nbcalls
											1, 										// quantity
											0,										// buy_price 
											convert_currency($this->currencies_list, $data[4], strtoupper($data[5]), strtoupper($this->invoice_currency))			// price
											);

				// look for identical item
				$found = false;
				foreach ($this->list_items as &$item)
					if ($found = ($item[0] == $chargeitem[0] 
								&& $item[1] == $chargeitem[1] 
								&& $item[3] == $chargeitem[3] 
								&& $item[4] == $chargeitem[4] 
								&& $item[9] == $chargeitem[9]
								&& $item[10]== $chargeitem[10])) 
						{
							$item[2] = '';
							$item[8]++;
														
							if ($this->verbose_level >= 2) {
		
								echo "\n #Similar Item: ";
								print_r($item);
							}						
							break;
						}
										
				if (! $found) {
					
					if ($this->verbose_level >= 2) {
						
						echo "\n #Item: ";
						print_r($chargeitem);
					}					
					$this->list_items[] = $chargeitem;
				}
			}
											
		} else throw new Exception('Unable to generate Call Items because Calls were not listed');
		
		if ($this->verbose_level >= 1)	echo "\n".(count($this->list_items) - $nb_items).' charge items generated';
	}

	// Sort Items according to their category: generate $list_category_items with $list_items
	function SortItems() {

		$this->list_category_items = Array();
		
		foreach($this->list_items as &$item) {
			
			// name fiels
			list($item['invoicesection'],$item['designation'],$item['sub_designation'],$item['start_date'],$item['end_date'],
			$item['bill_date'],$item['calltime'],$item['nbcalls'],$item['quantity'],$item['buy_price'], $item['price']) = $item;
			
			// sort it in correct category
			$this->list_category_items[$item[0]][] = $item; 
		}
		
		if ($this->verbose_level >= 3)	print_r($this->list_category_items);
	}
	
	// Sum all invoice Items. Compute tax
	function ComputeTotal() {
		
		$this->invoice_subtotal	= 0;
		
		foreach($this->list_items as $item) 
			$this->invoice_subtotal += $item[8] * $item[10];	// quantity * price 
				
		$this->invoice_tax		= $this->invoice_subtotal * $this->customer_VAT / 100;
		$this->invoice_total	= $this->invoice_subtotal + $this->invoice_tax;

		if ($this->verbose_level >=1)
				echo "\nTotal $this->invoice_subtotal + VAT( $this->customer_VAT % ) : $this->invoice_tax = $this->invoice_total $this->invoice_currency";
	}

////
////	Bill Invoice
////
	//	Write Invoice & Items
	//	If minimal amount is reached
	function BillInvoice($enable_minimal_amount = false, $minimal_amount = 0, $custom_template = '') {
		
		if ($enable_minimal_amount == true && $this->invoice_total < $minimal_amount)	{
			
			if ($this->verbose_level >=1)	echo "\nAmount $this->invoice_total <  $minimal_amount , skipping this invoice";
			return;
		}
		
		if (! $this->IsOfficial())
			throw new Exception('Cannot bill invoice that filters calls');
				
		$this->billedinvoice_id = 0;
		$this->billedinvoice_filename = '';
		$this->billedinvoice_paymentstatus = 0;
		$this->billedinvoice_creationdate = date('c');
		$this->billedinvoice_default_template = ($custom_template == '')? $this->customer_invoicetemplate : $custom_template;		
		
		if ($this->verbose_level >= 1)	echo "\n Template for this invoice is:".$this->billedinvoice_default_template;
		
		$this->WriteInvoice();
	}
	
	//	Write Invoice in Database
	function WriteInvoice() {
		
		if (! $this->IsBilled())
			throw new Exception('Cannot write an invoice that is not billed');
		
		// Here we have to Create a Insert Statement to insert Records into the Invoices Table.
		$Query_Invoices = "INSERT INTO cc_invoice (".
			" cardid, invoicecreated_date, amount, tax, total, filename, payment_status,".
			" cover_call_startdate, cover_call_enddate, cover_charge_startdate, cover_charge_enddate,".
			" currency, previous_balance, current_balance, templatefile, ".
			" username, lastname, firstname, address, city, state, country, ".
			" zipcode, phone, email, fax, vat ".
			" ) VALUES (".
			"'$this->customer_cardid', '$this->billedinvoice_creationdate', $this->invoice_subtotal, $this->invoice_tax, $this->invoice_total, '$this->billedinvoice_filename', $this->billedinvoice_paymentstatus,".
			"'$this->cover_call_startdate', '$this->cover_call_enddate', '$this->cover_charge_startdate', '$this->cover_charge_enddate',".
			"'$this->invoice_currency', '$this->previous_balance', $this->customer_currentbalance, '$this->billedinvoice_default_template',".
			"'$this->customer_username', '$this->customer_lastname', '$this->customer_firstname', '$this->customer_address', '$this->customer_city', '$this->customer_state', '$this->customer_country',".
			"'$this->customer_zipcode','$this->customer_phone','$this->customer_email','$this->customer_fax','$this->customer_VAT');";			
		if ($this->verbose_level >= 1) echo "\n Query_Write_Invoices = $Query_Invoices \n";
		
		if (! $this->instance_table -> SQLExec ($this->DB_Handle, $Query_Invoices, 0))
			throw new Exception('Failed to write invoice in Database');
		
		$QUERY = "Select Max(id) from cc_invoice";
		$result = $this->instance_table -> SQLExec ($this->DB_Handle, $QUERY);
		
		if (! is_array($result) || count($result) == 0)
			throw new Exception('Unable to find invoice again');			
		
		$this->billedinvoice_id = $result[0][0];

		if (count($this->list_items)) {
			$QUERY = "INSERT INTO cc_invoice_items(invoiceid, invoicesection, designation, sub_designation, start_date, end_date, bill_date, calltime, nbcalls, quantity, buy_price, price) VALUES ";
	
			$first = true;
			foreach($this->list_items as $item) {
				
				if (! $first) $QUERY .= ", ";			
				$QUERY .= "('$this->billedinvoice_id','$item[0]','$item[1]','$item[2]','$item[3]','$item[4]','$item[5]','$item[6]','$item[7]','$item[8]','$item[9]','$item[10]')";
				$first = false;			
			}
			if ($this->verbose_level >= 2)	echo "\n QUERY_WRITE_ITEMS = $QUERY";
		
			if (! $this->instance_table-> SQLExec ($this->DB_Handle, $QUERY,0))
				throw new Exception('Failed to write invoice items in Database');	
		} else if ($this->verbose_level >= 1) echo "\n no items in this invoice";
		
		if ($this->verbose_level >= 1)	echo "\n ################################################################################# \n\n";
	}
////
////	Load Billed Invoice
////
	// Fills its attributes according to database
	function LoadInvoice($invoice_id) {

		$this->billedinvoice_id = $invoice_id;
		$this->list_total_charge = NULL;
		$this->list_total_destination = NULL;
		
		// First get info about invoice
		$QUERY = "SELECT cardid, invoicecreated_date, amount, tax, total, filename, payment_status,".
			" cover_call_startdate, cover_call_enddate, cover_charge_startdate, cover_charge_enddate,".
			" currency, previous_balance, current_balance, templatefile, ".
			" username, lastname, firstname, address, city, state, country, ".
			" zipcode, phone, email, fax, vat ".
			" FROM cc_invoice WHERE id = $invoice_id";
		if ($this->verbose_level>=1) echo "\nQUERY_INVOICE = $QUERY";
		
		$resinvoice = $this->instance_table -> SQLExec ($this-> DB_Handle, $QUERY);
		if (!is_array($resinvoice) || count($resinvoice) != 1)
			throw new Exception("\nInvoice with id=".$this->invoice_id." not found.");
		if ($this->verbose_level>=2) print_r($resinvoice);
		
		// TODO: réecrire avec list(...) = $resinvoice[0]
		$this->customer_cardid					= $resinvoice[0][0];
		$this->billedinvoice_creationdate		= $resinvoice[0][1];
		$this->invoice_subtotal					= $resinvoice[0][2];
		$this->invoice_tax						= $resinvoice[0][3];
		$this->invoice_total					= $resinvoice[0][4];
		$this->billedinvoice_filename			= $resinvoice[0][5];
		$this->billedinvoice_paymentstatus		= $resinvoice[0][6];
		$this->cover_call_startdate				= $resinvoice[0][7];
		$this->cover_call_enddate				= $resinvoice[0][8];
		$this->cover_charge_startdate			= $resinvoice[0][9];
		$this->cover_charge_enddate				= $resinvoice[0][10];
		$this->invoice_currency					= $resinvoice[0][11];
		$this->previous_balance					= $resinvoice[0][12];
		$this->customer_currentbalance			= $resinvoice[0][13];
		$this->billedinvoice_default_template	= $resinvoice[0]['templatefile'];
		$this->customer_username				= $resinvoice[0][15];
		$this->customer_lastname				= $resinvoice[0][16];
		$this->customer_firstname				= $resinvoice[0][17];
		$this->customer_address					= $resinvoice[0][18];
		$this->customer_city					= $resinvoice[0][19];
		$this->customer_state					= $resinvoice[0][20];
		$this->customer_country					= $resinvoice[0][21];
		$this->customer_zipcode					= $resinvoice[0][22];
		$this->customer_phone					= $resinvoice[0][23];
		$this->customer_email					= $resinvoice[0][24];
		$this->customer_fax						= $resinvoice[0][25];
		$this->customer_VAT						= $resinvoice[0][26];
		
		// get invoice items
		$QUERY = "SELECT invoicesection, designation, sub_designation, start_date, end_date, bill_date, calltime, nbcalls, quantity, buy_price, price".
				" FROM cc_invoice_items WHERE invoiceid = $this->billedinvoice_id";
		if ($this->verbose_level >= 1)	echo "\n QUERY_READ_ITEMS = $QUERY";
				
		$this->list_items = $this->instance_table-> SQLExec ($this->DB_Handle, $QUERY);
				
		if (!is_array($this->list_items))
			throw new Exception("Cannot list invoice items for invoice id=".$this->billedinvoice_id);
		if ($this->verbose_level >= 2)	print_r($this->list_items);
		
		// Sort items
		$this->SortItems();
	}
////
////	Display Invoice
////
	// Gives default values if some fiels are left blank
	function GetTemplateFullPath($invoicetype, $template) {
		
		global	$A2B;
		
		if ($template == '') {
			if ($this->billedinvoice_default_template != '')
				$template = $this->billedinvoice_default_template;
			else
				switch ($invoicetype) {
					case 'billed':
						$template = $this->customer_invoicetemplate;
						break;
					case 'outstanding':
						$template = $this->customer_outstandingtemplate;
						break;
					default:
						throw new Exception('Unable to choose a template file');			
				}			
		}
				
		switch ($invoicetype) {
			case 'billed':
				return $A2B->config['global']['invoice_template_path'].$template;
			case 'outstanding':
				return $A2B->config['global']['outstanding_template_path'].$template;
			case 'sales':
				return $A2B->config['global']['sales_template_path'].$template;
			default:
				throw new Exception('Unknown invoice type');			
		}
	}
	
	function DisplayHTML($smarty, $invoicetype, $template = '') {
		
		$smarty->assign("invoice", $this);
		$smarty->debugging = ($this->verbose_level);
		
		$template_path = $this->GetTemplateFullPath($invoicetype, $template);
		if ($this->verbose_level >= 1)
			echo "\nTemplate Path: ".$template_path;
		
		$smarty->display($template_path);
	}
	
	function GetHTML($smarty, $invoicetype, $template = '') {
		
		$smarty->assign("invoice", $this);
		$smarty->debugging = ($this->verbose_level);
		
		$template_path = $this->GetTemplateFullPath($invoicetype, $template);
		if ($this->verbose_level >= 1)
			echo "\nTemplate Path: ".$template_path;
		
		return $smarty->fetch($template_path);
	}
	
	function GetPDF($smarty, $invoicetype, $template = '') {
		
		require_once('pdf-invoices/html2pdf/html2fpdf.php');
		
		$pdf = new HTML2FPDF();
		$pdf -> DisplayPreferences('HideWindowUI');
		$pdf -> AddPage();
		$pdf -> WriteHTML($this->GetHTML($smarty, $invoicetype, $template));	
		
		return $pdf->Output('Invoice_'.date("d/m/Y-H:i").'.pdf', 'S');
	}
	
	function DisplayPDF($smarty, $invoicetype, $template) {
		
		require_once('pdf-invoices/html2pdf/html2fpdf.php');
		
		$pdf = new HTML2FPDF();
		$pdf -> DisplayPreferences('HideWindowUI');
		$pdf -> AddPage();
		$pdf -> WriteHTML($this->GetHTML($smarty, $invoicetype, $template));	
		
		// TODO: find a better name
		return $pdf->Output('Invoice_'.date("d/m/Y-H:i").'.pdf', 'I');
	}
	
	function SendEMail($smarty, $template = '') {
		
		// Render Invoice to a PDF
		$stream = $this->GetPDF($smarty, 'billed', $template);
		
		// Get Mail template
		$QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='invoice' ";
		if($this->verbose_level >= 1)
			echo "\nQuery Mail Template : $QUERY";
		
		$res = $this->instance_table -> SQLExec ($this->DB_Handle, $QUERY);
		if (!is_array($res) || count($res) != 1)
			throw new Exception("\nUnable to find mail template=".$this->invoice_id." not found.");
		
		list($mailtype, $from, $fromname, $subject, $messagetext, $messagehtml) = $res [0];
		
		// Sent Email	
		$ok = send_email_attachment($from, $this->customer_email, $subject, $messagetext,'Invoice_'.date("d/m/Y-H:i").'.pdf', $stream );
		
		if ($this->invoice_id) {

			// Write it in invoice history
			$currentdate = date("Y-m-d h:i:s");
			
			$QUERY = "INSERT INTO cc_invoice_history (invoiceid,invoicesent_date,invoicestatus) VALUES('$this->invoice_id', '$currentdate', '".(($ok)?1:0)."')";
			$this->instance_table -> SQLExec ($this->DB_Handle, $QUERY,0);
			
			if($this->verbose_level >= 1)
				echo "\nWrite Invoice History : $QUERY";
		}	
		return (($ok)? true : false);
	}
}