<?php
getpost_ifset(array('id', 'customers_id', 'customers_name', 'customers_email_address', 'item_name', 'item_id', 'item_quantity', 'payment_method','cc_type', 'cc_owner','cc_number', 'cc_expires', 'orders_status', 'last_modified', 'date_purchased','orders_date_finished', 'orders_amount', 'currency', 'currency_value'));


$HD_Form = new FormHandler("cc_payments","Transactions");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";



$HD_Form -> AddViewElement(gettext("ID"), "id", "4%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ORDER DATE"), "date_purchased", "10%", "center", "sort", "19", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("CARDID"), "customers_id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("NAME"), "customers_name", "10%", "center", "sort");
//$HD_Form -> AddViewElement(gettext("EMAIL"), "customers_email_address", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ITEM NAME"), "item_name", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ITEM ID"), "item_id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("METHOD"), "payment_method", "9%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CC NUMBER"), "cc_number", "10%", "center", "sort");
//$HD_Form -> AddViewElement(gettext("CC EXPIRES"), "cc_expires", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("STATUS"), "orders_status", "7%", "center", "sort","15","lie","cc_payments_status","status_name","status_id='%id'","%1");
$HD_Form -> AddViewElement(gettext("AMOUNT"), "orders_amount", "8%", "center", "SORT", "30");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("CURRENCY")."\">".gettext("CUR")."</acronym>", "currency", "5%", "center", "sort", "");
$HD_Form -> AddViewElement(gettext("VALUE"), "currency_value", "5%", "center", "SORT", "30");
// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id, date_purchased, customers_id, customers_name, item_name, item_id, payment_method, cc_number, orders_status, orders_amount, currency, currency_value');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = true;


// TODO integrate in Framework
if ($form_action=="ask-add"){
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

$did_regexpress = '9';
if ($form_action=="ask-edit" || $form_action=="edit"){
	$did_regexpress = '13';
}

$HD_Form -> AddEditElement (gettext("CUSTOMER ID"),
	"customers_id",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Customer ID"),
	"" , "", "", "", "" , "", "" ,"Insert Customer ID");

$HD_Form -> AddEditElement (gettext("NAME"),
	"customers_name",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Customer Name"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Customer Name"));

$HD_Form -> AddEditElement (gettext("EMAIL"),
	"customers_email_address",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Customer EMAIL"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Customer EMAIL"));

$HD_Form -> AddEditElement (gettext("ITEM NAME"),
	"item_name",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Item Name"),
	"" , "", "", "", "" , "", "" ,"Insert Item Name");

$HD_Form -> AddEditElement (gettext("ITEM ID"),
	"item_id",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the ITEM ID"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Item Id"));

$HD_Form -> AddEditElement (gettext("ITEM QUANTITY"),
	"item_quantity",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Item Quantity"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Item Quantity"));

$HD_Form -> AddEditElement (gettext("PAYMENT METHOD"),
	"payment_method",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert Payment Method"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Payment Method"));

$HD_Form -> AddEditElement (gettext("CC TYPE"),
	"cc_type",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("CREDIT CARD TYPE"),
	"" , "", "", "", "" , "", "" ,gettext("CREDIT CARD TYPE"));

$HD_Form -> AddEditElement (gettext("CC OWNER"),
	"cc_owner",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Credit Card Owner"),
	"" , "", "", "", "" , "", "" ,gettext("Credit Card Owner"));

$HD_Form -> AddEditElement (gettext("cc_number"),
	"cc_number",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,gettext("Credit Card Number"));

$HD_Form -> AddEditElement (gettext("CC EXPIRY"),
	"cc_expires",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");

    $HD_Form -> AddEditElement (gettext("STATUS"),
	"orders_status",
	'$value',
	"SELECT",
	"", "", "",
	"sql",
	"cc_payments_status",
	"status_name, status_id",
	"", "", "%1","", "");

$HD_Form -> AddEditElement (gettext("DATE MODIFIED"),
	"last_modified",
	'$value',
	"INPUT",
	"size=30 maxlength=30 $comp_date readonly",
	"",
	"",
	"" , "", "", "", "" , "", "res_display_dateformat" ,"");

$HD_Form -> AddEditElement (gettext("DATE PURCHASED"),
	"date_purchased",
	'$value',
	"INPUT",
	"size=30 maxlength=30",
	"",
	gettext("Insert the date purchased"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Date Purchased"));

$HD_Form -> AddEditElement (gettext("ORDER FINISH DATE"),
	"orders_date_finished",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Order finish date"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Order finish date"));

$HD_Form -> AddEditElement (gettext("CURRENCY"),
	"currency",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Currency"),
	"" , "", "", "", "" , "", "" ,gettext("Insert Currency"));

$HD_Form -> AddEditElement (gettext("CURRENCY VALUE"),
	"currency_value",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Currency Value"),
	"" , "", "", "", "" , "", "" ,gettext("Insert currency value"));

$HD_Form -> AddEditElement (gettext("AMOUNT"),
	"orders_amount",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	gettext("Insert the Order amount"),
	"" , "", "", "", "" , "", "" ,gettext("Insert order amount"));



$HD_Form -> FieldEditElement ('customers_id,customers_name,customers_email_address,item_name, item_id,item_quantity,payment_method,cc_type,cc_owner,cc_number, cc_expires,orders_status,last_modified,date_purchased,orders_date_finished, currency,currency_value,orders_amount');


// Set the filter variables
$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'transaction_id';
$HD_Form -> FG_FILTERFIELDNAME = 'transaction ID';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="id like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.". <br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = "../Images/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
