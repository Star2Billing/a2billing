<?php

getpost_ifset(array('id', 'id_card', 'date','title','description'));
   
 
if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_receipt LEFT JOIN cc_card ON cc_card.id=id_card LEFT JOIN cc_receipt_item ON cc_receipt_item.id_receipt=cc_receipt.id ","Receipt");
} else {
	$HD_Form = new FormHandler("cc_receipt","Receipt");
}

$HD_Form -> FG_TABLE_ID = 'cc_receipt.id';
$HD_Form -> FG_TABLE_CLAUSE = " 1 GROUP BY cc_receipt.id";
$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form -> FG_FK_DELETE_ALLOWED = true;
$HD_Form -> FG_FK_DELETE_CONFIRM = true;
$HD_Form -> FG_FK_WARNONLY = true;
$HD_Form -> FG_FK_TABLENAMES = array("cc_receipt_item");
$HD_Form -> FG_FK_EDITION_CLAUSE = array(" id_receipt ");
 
$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_receipt_view.php?id=";
$HD_Form -> FG_INFO_ALT = "VIEW";
$HD_Form -> FG_INFO_IMG = "info.png";



$filter_amount_with_vat ='SUM(cc_receipt_item.price)';
$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_receipt.id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "10%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("DATE"), "date", "15%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("TITLE"), "title", "21%", "center", "sort");
$HD_Form -> AddViewElement(gettext("AMOUNT INCL VAT"), "price", "10%", "center", "sort","", "", "", "", "", "", "display_2bill");
$HD_Form -> FieldViewElement ("cc_receipt.id, username, cc_receipt.date,title,$filter_amount_with_vat");


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO RECEIPT CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


