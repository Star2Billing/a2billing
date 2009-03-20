<?php

getpost_ifset(array('id', 'displayheader', 'type','popup_select','popup_formname','popup_fieldname'));


// type : refill or payment
$HD_Form -> FG_DEBUG = 0;

if ($type=='payment'){
	$HD_Form = new FormHandler("cc_logpayment as t2","Payment");
	$HD_Form -> FG_TABLE_CLAUSE = "t2.card_id = $id";
	$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

	//SELECT t3.date, t3.credit, t3.reseller_id from cc_logrefill as t3 WHERE t3.card_id='1'
	//SELECT t2.date, t2.payment, t2.reseller_id from cc_logpayment as t2 WHERE t2.card_id='1'
	$HD_Form -> AddViewElement(gettext("DATE"), "date", "50%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
	$HD_Form -> AddViewElement(gettext("AMOUNT"), "payment", "20%", "center", "sort");

	$HD_Form -> FieldViewElement ('t2.date, t2.payment, t2.reseller_id');
}else{
	$filter_amount_with_vat ='SUM(cc_invoice_item.price*(1+(cc_invoice_item.vat/100)))';
	$HD_Form = new FormHandler("cc_invoice LEFT JOIN cc_invoice_item ON cc_invoice_item.id_invoice=cc_invoice.id ","Invoice");
	$HD_Form -> FG_TABLE_ID = 'cc_invoice.id';
	$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
	$HD_Form -> FG_TABLE_CLAUSE = "cc_invoice.id_card = $id GROUP BY cc_invoice.id";
	//SELECT t3.date, t3.credit, t3.reseller_id from cc_logrefill as t3 WHERE t3.card_id='1'
	//SELECT t2.date, t2.payment, t2.reseller_id from cc_logpayment as t2 WHERE t2.card_id='1'
	$HD_Form -> AddViewElement(gettext("DATE"), "date", "50%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
	$HD_Form -> AddViewElement(gettext("AMOUNT INCL VAT"), "price", "10%", "center", "sort","", "", "", "", "", "", "display_2bill");
	$HD_Form -> FieldViewElement ("cc_invoice.date, $filter_amount_with_vat");
}



$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO").strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> CV_FOLLOWPARAMETERS = "&popup_select=".$_GET['popup_select']."&id=$id&type=$type";
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;



?>
