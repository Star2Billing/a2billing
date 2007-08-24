<?php
$verbose_level = 0;

$plang='en';
getpost_ifset(array(
	// Restrict
	'oncard',				// filter on cardid. if oncard is set, the filtercustomer shouldn't be used. cardid column is hidden
	
	// Filters
	'filtercustomer',		// filter on customer (username)
	
	'frommonth',			// enable start month filter (on invoice creation date)
	'tomonth',				// enable end month filter
	'fromstatsmonth',		// filtered start month
	'tostatsmonth',			// filtered end month
	
	'filtertotaloperator',	// filter condition on amount ('equal', 'greater', 'less', 'greaterthanequal', 'lessthanequal')
	'filtertotal',			// filtered amount
	
	'inclusivecalls',		// invoice has to bill calls ('on', '')
	'inclusivecharges',		// invoice has to bill charges ('on', '') 
	
	'exporttype',			// format to display invoice details ("pdf", "html")
	'templatefile'			// Smarty template to use
	));

// Static tables
$status_list = array();
$status_list['0'] = array( gettext('Failed'), '0');
$status_list['1'] = array( gettext('Successful'), '1');
$status_list['']  = array( gettext('Not Sent'), '');

$payment_status_list = array();
$payment_status_list['0'] = array( gettext('UNPAID'), '0');
$payment_status_list['1'] = array( gettext('SENT-UNPAID'), '1');
$payment_status_list['2'] = array( gettext('SENT-PAID'), '2');
$payment_status_list['3'] = array( gettext('PAID'), '3');

$totaloperators_table = Array( 
	'equal' 			=> '= ',
	'greater' 			=> '> ',
	'less' 				=> '< ',
	'greaterthanequal'	=> '>= ',
	'lessthanequal'		=> '<= '	
	);

// Set default value
if ($exporttype == '')
	$exporttype = 'html'; 

// Create FormHandler object
$HD_Form = new FormHandler("cc_card card, cc_invoice inv LEFT JOIN (SELECT * FROM cc_invoice_history GROUP BY invoiceid HAVING Max(invoicesent_date)) as his ON inv.ID = his.invoiceid ","Invoice");
$HD_Form -> FG_TABLE_CLAUSE = " card.id = inv.cardid ";

// Filter on cardid
if (isset($oncard)  &&  ($oncard>0))
{
	$HD_Form -> FG_TABLE_CLAUSE .= " AND card.id = '$oncard' ";	
}

// Filter on username
if (isset($entercustomer)  &&  ($entercustomer>0))
{
	$HD_Form -> FG_TABLE_CLAUSE .= " AND card.username = '$filtercustomer' ";	
}

// Filter on amount
if (isset($totaloperators_table[$filtertotaloperator]) && $filtertotal!="")	{	// if posted & different from 'any'
	
	$HD_Form -> FG_TABLE_CLAUSE .= ' AND total '.$totaloperators_table[$filtertotaloperator].' '.$filtertotal;
}

// Filter on inclusions
if ($inclusivecalls == 'on')
	$HD_Form -> FG_TABLE_CLAUSE .= ' AND cover_call_startdate <> cover_call_enddate';

if ($inclusivecharges == 'on')
	$HD_Form -> FG_TABLE_CLAUSE .= ' AND cover_charge_startdate <> cover_charge_enddate';
	
// Filter on date
if (DB_TYPE == 'postgres'){
	 	$UNIX_TIMESTAMP = '';
}else{
		$UNIX_TIMESTAMP = 'UNIX_TIMESTAMP';
}

if ($frommonth && isset($fromstatsmonth))
{
	$HD_Form -> FG_TABLE_CLAUSE .= " AND $UNIX_TIMESTAMP(invoicecreated_date) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
}

if ($tomonth && isset($tostatsmonth))
{
	$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
	$HD_Form -> FG_TABLE_CLAUSE .= " AND $UNIX_TIMESTAMP(invoicecreated_date) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
}
	
// at last, display Clause
if ($verbose_level >= 2)
	echo "\nFG_TABLE_CLAUSE: ".$HD_Form -> FG_TABLE_CLAUSE;
	
// Misc
$HD_Form -> FG_TABLE_ID = "inv.id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "inv.invoicecreated_date";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
$HD_Form -> FG_DEBUG = $verbose_level;
$HD_Form -> FG_LIMITE_DISPLAY = 10;

$HD_Form -> FG_FILTER_APPLY = false;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'cardnumber';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

// View
$HD_Form -> AddViewElement(gettext("ID"), "id", "4%", "center", "sort");
if (!isset($oncard))
	$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "username", "4%", "center", "sort");
$HD_Form -> AddViewElement(gettext("INVOICEDATE"), "invoicecreated_date", "6%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("COVERCALLSTART"), "call_startdate", "6%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("COVERCALLEND"), "call_enddate", "6%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("COVERCHARGESTART"), "charge_startdate", "6%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("COVERCHARGEEND"), "charge_enddate", "6%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("SENTDATE"), "invoicesent_date", "6%", "center", "sort", "22", "", "", "", "", "", "display_dateonly");
$HD_Form -> AddViewElement(gettext("EMAIL"), "invoicestatus", "4%", "center", "sort", "", "list", $status_list);
$HD_Form -> AddViewElement(gettext("PAYMENTSTATUS"), "payment_status", "4%", "center", "sort", "", "list", $payment_status_list);
$HD_Form -> AddViewElement(gettext("AMOUNT"), "total", "3%", "center", "sort", "30");
$HD_Form -> AddViewElement(gettext("CURRENCY"), "currency", "3%", "center", "sort", "30");

$HD_Form -> FieldViewElement ('inv.id, card.username,'.((isset($oncard))?'':'invoicecreated_date,	').'
	(CASE WHEN cover_call_startdate = cover_call_enddate 	 THEN NULL ELSE cover_call_startdate 	END) AS call_startdate,
	(CASE WHEN cover_call_startdate = cover_call_enddate 	 THEN NULL ELSE cover_call_enddate   	END) AS call_enddate,
	(CASE WHEN cover_charge_startdate = cover_charge_enddate THEN NULL ELSE cover_charge_startdate	END) AS charge_startdate,
	(CASE WHEN cover_charge_startdate = cover_charge_enddate THEN NULL ELSE cover_charge_enddate	END) AS charge_enddate,
	invoicesent_date, invoicestatus, inv.payment_status, inv.total, inv.currency');

//	Action buttons
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_LINK="javascript:;\" Onclick = \"OpenInvoice('A2B_entity_invoices2_detail.php?id=|param|&invoice_type=2', searchform)";
$HD_Form -> FG_OTHER_BUTTON1_ALT = 'Details';
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path.'/details.gif';

$HD_Form -> FG_OTHER_BUTTON2_LINK="javascript:;\" Onclick = \"Sendmail('A2B_entity_invoices2_detail.php?id=|param|&invoice_type=2&exporttype=email', searchform)";
$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_ALT = 'Send';
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path.'/email03.gif';

$HD_Form -> FG_ACTION_SIZE_COLUMN = '10%';
?>
<script type="text/javascript">
	<!--
	function OpenInvoice(URL, form) {
		
		var exporttyp	= '';
		var template	= '';
		
		for (var i=0; i<form.exporttype.length;i++) {
			if (form.exporttype[i].checked) {
				exporttyp = form.exporttype[i].value;	  
			}
		}
		
		template = form.templatefile.options[form.templatefile.selectedIndex].value;	
	
		window.open(URL + '&exporttype='+ exporttyp + '&templatefile=' + template);
	}
	//-->
	<!--
	function Sendmail(URL, form) {
		
		var template	= 'VIDE';
		
		template = form.templatefile.options[form.templatefile.selectedIndex].value;	
	
		window.open(URL + '&templatefile=' + template);
	}
	//-->
</script>
<?php
