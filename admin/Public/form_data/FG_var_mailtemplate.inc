<?php

getpost_ifset(array('id', 'IDCust', 'mailtype', 'fromemail', 'fromname', 'subject', 'messagetext','languages','id_language'));


// Get Latest id from the table
$latest_id = mailtemplate_latest_id();

$HD_Form = new FormHandler("cc_templatemail","mailtemplate");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";



$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_mailtemplate.php?form_action=ask-add&atmenu=mailtemplate&section=8";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add MAIL TEMPLATE");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/email_add.png" ;


$typemail_list = array();
$typemail_list["reminder"]  		= array( gettext("Reminder"), "reminder");
$typemail_list["signup"]    		= array( gettext("Signup"), "signup");
$typemail_list["forgetpassword"]  	= array( gettext("Forget password"), "forgetpassword");
$typemail_list["signupconfirmed"]  	= array( gettext("Signup confirmed"), "signupconfirmed");
$typemail_list["payment"]  		= array( gettext("Payment Confirmation"), "payment");
$typemail_list["invoice"]  		= array( gettext("Invoice"), "invoice");
$typemail_list["epaymentverify"]  	= array( gettext("EPayment Warning"), "epaymentverify");
$typemail_list["template1"] 		= array( gettext("template1"), "template1");
$typemail_list["template2"] 		= array( gettext("template2"), "template2");
$typemail_list["template3"] 		= array( gettext("template3"), "template3");
$typemail_list["template4"] 		= array( gettext("template4"), "template4");
$typemail_list["template5"] 		= array( gettext("template5"), "template5");
$typemail_list["template6"] 		= array( gettext("template6"), "template6");
$typemail_list["template7"] 		= array( gettext("template7"), "template7");
$typemail_list["template8"] 		= array( gettext("template8"), "template8");
$typemail_list["template9"] 		= array( gettext("template9"), "template9");



$HD_Form -> AddViewElement(gettext("MAILTYPE"), "mailtype", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("FROMEMAIL"), "fromemail", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("SUBJECT"), "subject", "40%", "center", "sort");

$HD_Form -> FieldViewElement ('mailtype, fromemail, subject');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;

// Translation
$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_LINK= "A2B_entity_translate.php?form_action=ask-add&id=|param|&languages=$languages";
$HD_Form -> FG_OTHER_BUTTON2_ALT = 'Translate';

$HD_Form -> AddEditElement (gettext("MAILTYPE"),
	"mailtype",
	'$value',
	"SELECT",
	"",
	"",
	"",
	"list" , "", "", "", $typemail_list, "%1", "", "");
	
$HD_Form -> AddEditElement (gettext("FROMEMAIL"),
	"fromemail",
	'',
	"INPUT",
	"size=30 maxlength=60",
	"1",
	gettext("Insert the user fromemail"),
	"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("FROMNAME"),
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

$HD_Form -> AddEditElement (gettext("MESSAGETEXT"),
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
if($id_language != '' || !is_null($id_language)){
	$HD_Form -> FG_EDITION_CLAUSE .= "AND id_language = '$id_language'";
}


$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the contents of the email template below.");
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this").$HD_Form->FG_INSTANCE_NAME." ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("Add a new mail template");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Then MailType is signupconfirmed, some variables can be set within the email that will be replaced when the email is sent. Variables are:")." \$name; \$card_gen; \$password ; \$cardalias ; \$loginkey ; \$email.";


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&languages=en&id=";

