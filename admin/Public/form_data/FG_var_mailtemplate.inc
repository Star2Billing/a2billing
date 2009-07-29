<?php

getpost_ifset(array('id', 'IDCust', 'mailtype', 'fromemail', 'fromname', 'subject', 'messagetext','languages','id_language'));


// Get Latest id from the table
$latest_id = mailtemplate_latest_id();

$HD_Form = new FormHandler("cc_templatemail", gettext("mail template"));

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";





$typemail_list = array();
$typemail_list["reminder"]  		= array( gettext("Reminder"), Mail::$TYPE_REMINDER);
$typemail_list["signup"]    		= array( gettext("Signup"),  Mail::$TYPE_SIGNUP);
$typemail_list["forgetpassword"]  	= array( gettext("Forget password"), Mail::$TYPE_FORGETPASSWORD);
$typemail_list["signupconfirmed"]  	= array( gettext("Signup confirmed"), Mail::$TYPE_SIGNUPCONFIRM);
$typemail_list["payment"]  			= array( gettext("Payment Confirmation"), Mail::$TYPE_PAYMENT);
$typemail_list["invoice"]  			= array( gettext("Invoice"), Mail::$TYPE_INVOICE);
$typemail_list["epaymentverify"]  	= array( gettext("EPayment Warning"), Mail::$TYPE_EPAYMENTVERIFY);
$typemail_list["template1"] 		= array( gettext("template1"), "template1");
$typemail_list["template2"] 		= array( gettext("template2"), "template2");
$typemail_list["template3"] 		= array( gettext("template3"), "template3");
$typemail_list["template4"] 		= array( gettext("template4"), "template4");
$typemail_list["template5"] 		= array( gettext("template5"), "template5");
$typemail_list["template6"] 		= array( gettext("template6"), "template6");
$typemail_list["template7"] 		= array( gettext("template7"), "template7");
$typemail_list["template8"] 		= array( gettext("template8"), "template8");
$typemail_list["template9"] 		= array( gettext("template9"), "template9");


if (($popup_select>=1)) {
	$HD_Form -> AddViewElement(gettext("ID MAIL"), "id", "10%", "center", "sort");
}
$HD_Form -> AddViewElement(gettext("MAILTYPE"), "mailtype", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FROMEMAIL"), "fromemail", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("SUBJECT"), "subject", "30%", "center", "sort");


if (($popup_select>=1)) {
	$HD_Form -> FieldViewElement ('id,mailtype, fromemail, subject');
}else{
	$HD_Form -> FieldViewElement ('mailtype, fromemail, subject');
}


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

if (($popup_select>=1)) {
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;'.gettext("SELECT TEXT").'&gt;</font>';
	$HD_Form -> FG_OTHER_BUTTON1_IMG = '';

	if ($popup_select==1){
		$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|col0|');";
	}

}else{
	$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
	$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_mailtemplate.php?form_action=ask-add&atmenu=mailtemplate&section=".$_SESSION["menu_section"];
	$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add MAIL TEMPLATE");
	$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/email_add.png" ;
	
	$HD_Form -> FG_ADDITION = true;
	$HD_Form -> FG_EDITION = true;
	$HD_Form -> FG_DELETION = true;
	
	// Translation
	$HD_Form -> FG_OTHER_BUTTON2 = true;
	$HD_Form -> FG_OTHER_BUTTON2_LINK= "A2B_entity_translate.php?form_action=ask-add&id=|param|&languages=$languages";
	$HD_Form -> FG_OTHER_BUTTON2_ALT = 'Translate';
}
$HD_Form -> AddEditElement (gettext("MAIL TYPE"),
	"mailtype",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $typemail_list, "%1", "", "");
	
$HD_Form -> AddEditElement (gettext("FROM EMAIL"),
	"fromemail",
	'',
	"INPUT",
	"size=30 maxlength=60",
	"1",
	gettext("Insert the user fromemail"),
	"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("FROM NAME"),
	"fromname",
	'',
	"INPUT",
	"size=30 maxlength=60",
	"",
	gettext("Insert the user fromname"),
	"" , "", "", "", "" , "", "", "");
$HD_Form -> AddEditElement (gettext("SUBJECT"),
	"subject",
	'',
	"INPUT",
	"size=30 maxlength=80",
	"",
	gettext("Insert the user subject"),
	"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("MESSAGE TEXT"),
	"messagetext",
	'',
	"TEXTAREA",
	"cols=70 rows=10",
	"",
	gettext("Insert the user subject"),
	"" , "", "", "", "" , "", "", "");

$HD_Form -> FieldEditElement ('mailtype, fromemail, fromname, subject, messagetext');

$HD_Form ->FG_QUERY_ADITION_HIDDEN_FIELDS = "id";
$HD_Form ->FG_QUERY_ADITION_HIDDEN_VALUE  = "$latest_id";


$HD_Form ->FG_QUERY_EDITION_HIDDEN_FIELDS = "id_language";
$HD_Form ->FG_QUERY_EDITION_HIDDEN_VALUE  = "$id_language";

$HD_Form -> FG_EDITION_CLAUSE = "id ='%id' ";
if($id_language != '' || !is_null($id_language)) {
	$HD_Form -> FG_EDITION_CLAUSE .= "AND id_language = '$id_language'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the contents of the email template below.");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this").$HD_Form->FG_INSTANCE_NAME." ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a new mail template");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";

$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("The followings tags will be replaced in the message by the value in the database.").'<br/>';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags for EPayment Warning email').': </u><br/> ' .
		'<b>$time$</b> : time of payment <br/>' .
		'<b>$paymentgateway$</b>: '.gettext('display payment gateway').'<br/>' .
		'<b>$itemAmount$</b>: '.gettext('amount of payment').'<br/>';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags for Payment email').': </u><br/> ' .
		'<b>$itemName$</b>: '.gettext('name of item paid').'<br/> ' .
		'<b>$itemID$</b> : '.gettext('id of customer').'<br/> ' .
		'<b>$paymentMethod$</b> : '.gettext('methode of payment').'<br/> ' .
		'<b>$paymentStatus$</b> : '.gettext('status of payment').'<br/>' .
		'<b>$itemAmount$</b>: '.gettext('amount of payment').'<br/>';
$HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT .= '<u>'.gettext('Tags used in all emails').': </u><br/> ' .
		'<b>$email$</b>: '.gettext('email of the customer').'<br/>' .
		'<b>$firstname$</b>: '.gettext('firstname of the customer').'<br/>' .
		'<b>$lastname$</b>: '.gettext('lastname of the customer').'<br/>' .
		'<b>$credit$</b>: '.gettext('credit of the customer in the system currency').'<br/>' .
		'<b>$creditcurrency$</b>: '.gettext('credit of the customer in the own currency').'<br/>' .
		'<b>$currency$</b>: '.gettext('currency of the customer').'<br/>' .
		'<b>$cardnumber$</b>: '.gettext('card number of the customer').'<br/>' .
		'<b>$password$</b>: '.gettext('password of the customer').'<br/>' .
		'<b>$login$</b>: '.gettext('login of the customer').'<br/>' .
		'<b>$credit_notification$</b>: '.gettext('credit notification of the customer').'<br/>' .
		'<b>$base_currency$</b>: '.gettext('base currency of system').'<br/>';
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT ;


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";

