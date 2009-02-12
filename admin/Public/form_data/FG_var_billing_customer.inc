<?php

getpost_ifset(array('id', 'id_card', 'date'));


if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_billing_customer LEFT JOIN cc_card ON cc_card.id=id_card LEFT JOIN cc_invoice ON cc_invoice.id=id_invoice ","Billing");
} else {
	$HD_Form = new FormHandler("cc_billing_customer","Billing");
}

$HD_Form -> FG_TABLE_ID = 'cc_billing_customer.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_billing_customer.php?form_action=ask-add&section=2";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Billing");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/time_add.png" ;

$status_list = Constants::getBillingInvoiceStatusList();
$paid_status_list = Constants::getInvoicePaidStatusList();
$HD_Form -> AddViewElement(gettext("ID"), "cc_billing_customer.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "15%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("BILLING DATE"), "cc_billing_customer.date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("INVOICE"), "id_invoice", "20%", "center", "sort", "", "lie_link", "cc_invoice", "reference,id", "id='%id'", '<img src="'.Images_Path.'/link.png" border="0" title="'.gettext("Link to the invoice %1").'" alt="'.gettext("Link to the invoice %1").'" />' , "", "A2B_invoice_view.php");
$HD_Form -> AddViewElement(gettext("INVOICE PAID STATUS"), "cc_invoice.paid_status", "10%", "center", "sort", "", "list", $paid_status_list);
$HD_Form -> AddViewElement(gettext("INVOICE STATUS"), "cc_invoice.status", "10%", "center", "sort", "", "list", $status_list);
$HD_Form -> FieldViewElement ('cc_billing_customer.id, username, cc_billing_customer.date ,id_invoice,cc_invoice.paid_status,cc_invoice.status');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_DELETION = true;


$HD_Form -> AddEditElement (gettext("ID CUSTOMER"),
			   "id_card",		
			   "$value", "POPUPVALUE", "size=30 maxlength=50",	
			   "4",	gettext("Insert the Customer ID"),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&", ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Customer ID"));

if ($form_action=="ask-add"){	
	$comp_date = date("Y-m-d");
	$comp_date="value='$comp_date'";
}

$HD_Form -> AddEditElement (gettext("BILLING DATE"),
			   "date",		
			   '$value',	
			   "INPUT",	
			   "size=40 maxlength=40  $comp_date",	
			   "5",	
			   gettext("Insert the current date (YYYY-MM-DD)"),
			   "" , "", "",	"", "", "", "", "" );


$HD_Form -> FieldEditElement ('id_card, date');



$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'Account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");

$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'proccessing_billing_customer';

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



