<?php

getpost_ifset(array('id', 'id_card', 'date','title','description'));
   
if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_invoice  LEFT JOIN cc_invoice_item ON cc_invoice_item.id_invoice=cc_invoice.id ","Invoice");
} else {
	$HD_Form = new FormHandler("cc_invoice","Invoice");
}

$HD_Form -> FG_TABLE_ID = 'cc_invoice.id';
$HD_Form -> FG_TABLE_CLAUSE = " id_card = ".$_SESSION["card_id"]." AND status = 1 GROUP BY cc_invoice.id";
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


 
$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_invoice_view.php?id=";
$HD_Form -> FG_INFO_ALT = "VIEW";
$HD_Form -> FG_INFO_IMG = "info.png";

$HD_Form -> FG_OTHER_BUTTON3_LINK = "checkout_payment.php?item_type=invoice&item_id=";
$HD_Form -> FG_OTHER_BUTTON3_IMG = Images_Path."/money.png";
$HD_Form -> FG_OTHER_BUTTON3 = true;
$HD_Form -> FG_OTHER_BUTTON3_CONDITION = '|col3| == "UNPAID"';


$status_list = Constants::getInvoicePaidStatusList();

//$filter_amount_without_vat ='SUM(cc_invoice_item.price)';
$filter_amount_with_vat ='SUM(cc_invoice_item.price*(1+(cc_invoice_item.vat/100)))';
$HD_Form -> AddViewElement(gettext("REFERENCE"), "cc_invoice.reference", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "paid_status", "5%", "center", "sort", "", "list", $status_list);
$HD_Form -> AddViewElement(gettext("AMOUNT INCL VAT"), "price", "10%", "center", "sort","", "", "", "", "", "", "display_2bill");
$HD_Form -> FieldViewElement ("cc_invoice.reference, cc_invoice.date,title,cc_invoice.paid_status,$filter_amount_with_vat");


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;





