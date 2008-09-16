<?php

getpost_ifset(array('id', 'product_name', 'description', 'credit', 'language', 'tariff', 'id_didgroup', 'activated', 'simultaccess', 'currency','typepaid', 'creditlimit', 'runservice', 'expirationdate', 'enableexpire', 'expiredays', 'mailtype', 'sip_friend', 'iax_friend','popup_select'));

$plang='en';

$HD_Form = new FormHandler("cc_ecommerce_product","Ecommerce Product");

$HD_Form -> FG_DEBUG = 0;



$language_list = Constants::getLanguagesList();
$simultaccess_list = Constants::getSimultAccessList();
$currency_list=getCurrenciesList();
$currency_list_r = getCurrenciesRateList();
$currency_list_key = getCurrenciesKeyList();
$typepaid_list = Constants::getPaidTypeList();
$expire_list = Constants::getExpirationList();



$inuse_list = array();
for ($k=0;$k<=40;$k++)
	$inuse_list["$k"]  = array( gettext("currently in use")." : $k", "$k");

$actived_list = Constants::getActivationTrueFalseList();

$yesno = Constants::getYesNoList();


$HD_Form -> AddViewElement(gettext("ID"), "id", "4%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PRODUCT_NAME"), "product_name", "13%", "center", "sort");
$HD_Form -> AddViewElement(gettext("creationdate"), "creationdate", "10%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "9%", "center", "sort", "", "", "", "", "", "", "");
$HD_Form -> AddViewElement(gettext("CALLPLAN"), "tariff", "10%", "center", "sort", "15", "lie", "cc_tariffgroup", "tariffgroupname", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("LG"), "language", "3%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CURRENCY"), "currency", "7%", "center", "sort", "", "list", $currency_list_r);
$HD_Form -> AddViewElement(gettext("SIP"), "sip_friend", "3%", "center", "sort", "", "list", $yesno);
$HD_Form -> AddViewElement(gettext("IAX"), "iax_friend", "3%", "center", "sort", "", "list", $yesno);



$HD_Form -> FieldViewElement ('id, product_name, creationdate, credit, tariff, language, currency, sip_friend, iax_friend');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;
$HD_Form -> FG_EXPORT_CSV = true;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;




$HD_Form -> AddEditElement (gettext("PRODUCT_NAME"),
			   "product_name",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=30",
			   "11",
			   gettext("Insert the product_name"),
			   "" , "", "",	"", "", "no" , "", "");



$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
			   "description",
			   '',
			   "TEXTAREA",
			   "cols=50 rows=4",
			   "",
			   gettext("Insert the description"),
			   "" , "", "",	"", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("CREDIT"),
			   "credit",
			   "",
			   "INPUT",
			   "size=30 maxlength=30",
			   "12",
			   gettext("Insert the number of credit"),
			   "" , "", "",	"", "" , "", "", "1.50 credit = 1.50 ".BASE_CURRENCY);



$HD_Form -> AddEditElement (gettext("LANGUAGE"),
				   "language",
				   "",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $language_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("CALLPLAN"),
				   "tariff",
				   "",
				   "SELECT",
				   "", "", "",
				   "sql",
				   "cc_tariffgroup",
				   "tariffgroupname, id",
				   "", "", "%1" , "", "");


$HD_Form -> AddEditElement (gettext("DIDGROUP"),
				   "id_didgroup",
				   "",
				   "SELECT",
				   "", "", "NOT DEFINED",	
				   "sql",		
				   "cc_didgroup",	
				   "didgroupname, id",		
				   "", "", "%1", "<OPTION  value='-1'>".gettext("NOT DEFINED")."</OPTION>", "", "");



$HD_Form -> AddEditElement (gettext("ACTIVATED"),
				   "activated",		
				   't',
				   "RADIOBUTTON",	
				   "",	
				   "",	
				   gettext("Choose if you want to activate this card"),
				   "" , "", "","Yes :t, - No:f", "", "" , "", "");


$HD_Form -> AddEditElement (gettext("SIMULTANEOUS ACCESS"),
				   "simultaccess",			
				   "",	
				   "SELECT",	
				   "",	
				   "",	
				   "", 
				   "list" , "", "",	"", $simultaccess_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("CURRENCY"),
				   "currency",			
				   "",	
				   "SELECT",	
				   "",	
				   "",	
				   "", 
				   "list" , "", "",	"", $currency_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("RUN SERVICE"),
				   "runservice",		
				   '1',
				   "RADIOBUTTON",	
				   "",	
				   "",	
				   gettext("Choose if you want to enable the service recurring on this card"),
				   "" , "", "", "Yes :1, - No:0",	"", "" , "", gettext("Define if you want to authorize the recurring service to apply on this card"));


$HD_Form -> AddEditElement (gettext("CARD TYPE"),
				   "typepaid",			
				   "",	
				   "SELECT",	
				   "",	
				   "",	
				   "", 
				   "list" , "", "",	"", $typepaid_list, "%1", "", "" );

$HD_Form -> AddEditElement (gettext("CREDIT LIMIT"),
			   "creditlimit",			
			   "",	
			   "INPUT",	
			   "size=20 maxlength=20",	
			   "4",	
			   gettext("Insert the credit limit"),
			   "" , "", "",	"", "", "", "NO-NULL", gettext("Credit limit is only used for POSTPAY card"));
				

$HD_Form -> AddEditElement (gettext("ENABLE EXPIRE"),
				   "enableexpire",
				   "",
				   "SELECT",
				   "",
				   "",
				   "",
				   "list" , "", "",	"", $expire_list, "%1", "", gettext("Define how do you want to enable the expiration over this card"));


$comp_zero = $comp_date_plus='';
if (isset($form_action) && $form_action=="ask-add"){	
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+10;	
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
	$comp_zero = "value='0'";
}

$HD_Form -> AddEditElement (gettext("EXPIRATION DATE"),
			   "expirationdate",
			   '$value',
			   "INPUT",
			   "size=40 maxlength=40  $comp_date_plus",
			   "10",
			   gettext("Insert the expiration date"),
			   "" , "", "",	"", "no", "NULL", "res_display_dateformat", gettext("Please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'" ));


$HD_Form -> AddEditElement (gettext("EXPIRATION DAYS"),
			   "expiredays",
			   "",
			   "INPUT",
			    "size=10 maxlength=6 $comp_zero",
			   "4",
			   gettext("Insert the number of days after which the card will be expired"),
			   "" , "", "",	"", "", "", "", gettext("The number of days after which the card will be expired"));



$HD_Form -> AddEditElement (gettext("MAIL TEMPLATE"),
			   "mailtype",	
			   "",	
			   "SELECT",	
			   "", "", "",	
			   "sql",		
			   "cc_templatemail",	
			   "subject, mailtype",		
			   "", "", "%2 (subject:%1)", "", "");


$HD_Form -> AddEditElement (gettext("SIP FRIEND"),
			   "sip_friend",			
			   "1",	
			   "RADIOBUTTON",	
			   "",	
			   "",	
			   gettext("Choose if you want to enable the sip account"),
			   "" , "", "", "Yes :1, - No:0",  "", "", "", "" );


$HD_Form -> AddEditElement (gettext("IAX FRIEND"),
			   "iax_friend",			
			   "1",	
			   "RADIOBUTTON",	
			   "",	
			   "",	
			   gettext("Choose if you want to enable the iax account"),
			   "" , "", "", "Yes :1, - No:0", "", "", "", "" );							


$HD_Form -> FieldEditElement ('product_name, description,	credit, language, tariff, id_didgroup, activated, simultaccess, currency, runservice, typepaid, creditlimit, enableexpire, expirationdate, expiredays, mailtype, sip_friend, iax_friend');


if (DB_TYPE == "postgres"){
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";	
}else{
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "creationdate ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now()";	
}




$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");





$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Once you have completed the form above, click on the CONTINUE button.");



$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
?>
