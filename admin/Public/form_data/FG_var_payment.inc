<?php

getpost_ifset(array('id', 'card_id', 'date', 'payment','description','added_refill','refill_type'));


if (!isset($form_action) || $form_action=="list"){
	$HD_Form = new FormHandler("cc_logpayment LEFT JOIN cc_card ON cc_card.id=card_id","payment");
}else{
	$HD_Form = new FormHandler("cc_logpayment","payment");
}

$HD_Form -> FG_TABLE_ID = 'cc_logpayment.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_payment.php?form_action=ask-add&section=2";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Payment");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/money_add.png" ;

$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_logpayment.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT"), "username", "20%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement(gettext("PAYMENT DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("PAYMENT AMOUNT"), "payment", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "23%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PAYMENT TYPE"),"payment_type", "7%", "center", "sort", "", "list", $list_refill_type);
$HD_Form -> AddViewElement(gettext("REFILL"), "id_logrefill", "20%", "center", "sort", "", "lie_link", "cc_logrefill", "credit, id", "id='%id'", "%1 ".BASE_CURRENCY." (id:%2)", "", "A2B_entity_logrefill.php");

$HD_Form -> FieldViewElement ('cc_logpayment.id, username, date, payment,description ,payment_type,id_logrefill');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

if ($form_action=="ask-add" && !empty($card_id)){	
	$id_val = "value='$card_id'";
}

$HD_Form -> AddEditElement (gettext("CUSTOMER ID"),
			   "card_id",		
			   "$value",	
			   "POPUPVALUE",	
			   "size=30 maxlength=50 ".$id_val,	
			   "4",	
			   gettext("Insert the Customer ID "),
			    "" , "", "", "", "", 				
				"A2B_entity_card.php?popup_select=1&"
				, ", 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1'" ,
				gettext("Select the Customer ID"));
			   
if ($form_action=="ask-add"){	
	$begin_date = date("Y");
	$end_date = date("-m-d H:i:s");
	$comp_date=$begin_date.$end_date;
	$comp_date="value='$comp_date'";
}

$HD_Form -> AddEditElement (gettext("PAYMENT DATE"),
			   "date",		
			   '$value',	
			   "INPUT",	
			   "size=40 READONLY maxlength=40  $comp_date",	
			   "10",	
			   gettext("Insert the current date"),
			   "" , "", "",	"", "", "", "", "" );

$HD_Form -> AddEditElement (gettext("PAYMENT AMOUNT"),
			   "payment",		
			   '$value',	
			   "INPUT",	
			   "size=60 maxlength=10",	
			   "12",	
			   gettext("Insert the payment amount"),
			   "" , "", "",	"", "" , "", "", gettext("Enter the amount in the currency base : ").BASE_CURRENCY );


$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'',
	"TEXTAREA",
	"cols=50 rows=4",
	"",
	gettext("Insert the description"),
	"" , "", "", "", "" , "", "", "");

if ($form_action!="ask-edit" && $form_action!="edit") {	
	$HD_Form -> AddEditElement (gettext("CREATE ASSOCIATE REFILL"),
				   "added_refill",
				   '0',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Choose if you want to create a refill with this payment account"),
				   "" , "", "" , "Yes :1, - No:0", "", "" , "", gettext("Refill card account"));
}

$HD_Form -> AddEditElement (gettext("REFILL TYPE"),
                           "payment_type",
                           '$value',
                           "SELECT",
                           "",
                           "",
                           "",
                           "list" , "", "",     "", $list_refill_type, "%1", "", gettext("Define type for payment and refill,if created.") );


if ($form_action!="ask-edit" && $form_action!="edit") {	
	$HD_Form -> FieldEditElement ('card_id, date, payment, description,payment_type');
} else {
	$HD_Form -> FieldEditElement ('card_id, date, payment, description, added_refill, payment_type');
}



$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)){
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your ".$HD_Form->FG_INSTANCE_NAME);
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$HD_Form->FG_INSTANCE_NAME.", click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$HD_Form->FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$HD_Form->FG_INSTANCE_NAME." has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue.");

$HD_Form -> FG_ADDITIONAL_FUNCTION_AFTER_ADD = 'create_refill';

$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

