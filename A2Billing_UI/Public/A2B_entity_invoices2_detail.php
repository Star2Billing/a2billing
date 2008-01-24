<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/smarty.php");
include ("../lib/A2B_invoice.php");

if (! has_rights (ACX_INVOICING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

$verbose_level = 0;

$payment_status_list = array();
$payment_status_list["0"] = array( gettext("UNPAID"), "0");
$payment_status_list["1"] = array( gettext("SENT-UNPAID"), "1");
$payment_status_list["2"] = array( gettext("SENT-PAID"),  "2");
$payment_status_list["3"] = array( gettext("PAID"),  "3");

getpost_ifset(array(
	
	'invoice_type', // (1: preview, 2: existing invoice)
	
	// used when invoice_type = 1
	'cardid',
	'billcalls',		// ('on', '') show calls
	'billcharges',		// ('on', '') show charges
	 		
	// used when invoice_type = 2
	'id', 		   		// invoice id	
	
	'exporttype',		// ('html','pdf','email')
	'payment_status',	// set the new status
	'templatefile'		// template file to use.
	));

// Because, in order to display pdf, there shall be no html displayed
if ($exporttype == 'pdf')
	$verbose_level = 0;

// Connect to Database
$DBHandle = DbConnect();

try {
	// Create Invoice object
	$invoice = new A2B_Invoice($DBHandle, $verbose_level);
	
	// Collect Data
	switch ($invoice_type) {
		case "":
		case 1:
			$invoice->RetrieveInformation($cardid, ($billcalls == 'on'), ($billcharges == 'on'), date('Y-m-d H:i:s'));
			$invoice->CreateInvoice();
			$invoice_type_name = 'outstanding';
			break;
			
		case 2:
			$invoice->LoadInvoice($id);
			
			// Change Payment Status if posted so
			if($payment_status != "")
			{
				$QUERY = "UPDATE cc_invoice SET payment_status ='$payment_status' WHERE id='$id'"; 
				$DBHandle -> Execute($QUERY);
				if ($verbose_level >= 1)
					echo "\nQUERY_INVOICESTATUS: ".$QUERY;
			}
			
			$invoice_type_name = 'billed';
			break;
			
		default:
			throw new Exception("invoice_type unknown");
	}
	
	// Display Invoice (& Commands) 
	switch ($exporttype) {
		case 'html':
			$invoice->DisplayHTML($smarty, $invoice_type_name, $templatefile);
			
			// Display Commands on billed invoice
			if ($invoice_type == 2) {
				?>
				<form  method="post" action="A2B_entity_invoices2_detail.php?id=$id&invoice_type=2&exporttype=html&templatefile=$templatefile">
					<select NAME="payment_status" size="1" class="form_input_select">
					<?php foreach($payment_status_list as $data) { ?>
						<option value='<?php echo $data[1] ?>' <?php  if ($invoice->payment_status == $data[1]) {?>selected<?php } ?>>
							<?php echo $data[0]." - $invoice->payment_status"; ?>
						</option>
					<?php } ?>
					</select>&nbsp;
					<input type="submit" class="form_input_button" name="submit" value="Update">
				</form>
				<?php
			}
			break;
		case 'pdf':
			$invoice->DisplayPDF($smarty, $invoice_type_name, $templatefile);
			break;
		case 'email':
			$invoice->SendEMail($smarty, $templatefile);
			break;
	}
}

catch (Exception $e) {
	echo "\nInvoice Error: ".$e;
}
?>
