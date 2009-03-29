<?php

getpost_ifset(array('voucher'));


$HD_Form = new FormHandler("cc_voucher","Voucher");

$HD_Form -> FG_DEBUG = 0;

$HD_Form -> FG_VIEW_TABLE_WITDH = "65%";

$HD_Form -> AddViewElement(gettext("VOUCHER"), "voucher", "13%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext(CURRENCY), "currency", "7%", "center", "sort");


$HD_Form -> FieldViewElement ('voucher, credit, currency');

$HD_Form -> FG_TABLE_CLAUSE = "usedcardnumber='".$_SESSION["pr_login"]."'";

$HD_Form -> CV_NO_FIELDS  = gettext("No vouchers have been used!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_EXPORT_CSV = false;

$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;

// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = false;
$HD_Form -> FG_FILTERFIELD = 'voucher';
$HD_Form -> FG_FILTERFIELDNAME = 'voucher';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="voucher like '$filterprefix%'";
}
