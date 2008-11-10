<?php

getpost_ifset(array('id', 'login', 'passwd', 'active', 'location', 'id_tariffgroup', 'options', 'credit', 'climit', 
'currency', 'locale', 'commission', 'vat', 'banner', 'language', 'perms', 'lastname', 'firstname', 'address', 'city', 'state', 
'country', 'zipcode', 'phone', 'email', 'fax'));


$FG_INSTANCE_NAME = gettext("Agent");
$HD_Form = new FormHandler("cc_agent",$FG_INSTANCE_NAME);

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = " id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " datecreation";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";


$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_entity_agent.php?form_action=ask-add&groupID=0&section=10";
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add AGENT");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/user_red.png" ;


$HD_Form -> FG_TABLE_CLAUSE = $FG_TABLE_CLAUSE;

$language_list = Constants::getLanguagesList();


$right_list = array();
$right_list["1"] = array( gettext("CUSTOMERS"), "1");
$right_list["2"] = array( gettext("BILLING"), "2");
$right_list["3"] = array( gettext("RATECARD"), "4");
$right_list["4"] = array( gettext("CALL REPORT"), "8");
$right_list["5"] = array( gettext("MY ACCOUNT"), "16");

$len_right_list = count($right_list);

$yesno = Constants::getYesNoList();


$currency_list=getCurrenciesList();
$currency_list_r = getCurrenciesRateList();
$currency_list_key = getCurrenciesKeyList() ;

$HD_Form -> AddViewElement(gettext("ID"), "id", "7%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE"), "datecreation", "13%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("LOGIN"), "login", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("PASSWORD"), "passwd", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("LASTNAME"), "lastname", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CREDIT"), "credit", "10%", "center", "sort");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("CURRENCY")."\">".gettext("CUR")."</acronym>", "currency", "5%", "center", "sort", "", "list", $currency_list_key);
$HD_Form -> AddViewElement(gettext("ACTIVE"), "active", "3%", "center", "sort", "", "list", $yesno);

$HD_Form -> FieldViewElement ('id, datecreation, login, passwd, lastname, credit, currency, active');

$HD_Form -> CV_NO_FIELDS  = gettext("NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("HAVE BEEN CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;



$HD_Form -> AddEditElement (gettext("LOGIN"),
		"login",
		'$value',
		"INPUT",
		"size=30 maxlength=12",
		"3",
		gettext("Insert the login"),
		"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("PASSWORD"),
		"passwd",
		'$value',
		"INPUT",
		"size=30 maxlength=15",
		"3",
		gettext("Insert the password"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("PERMISSION"),
		"perms",
		'$value',
		"SELECT",
		"size=\"$len_right_list\" multiple=\"multiple\"",
		"", "",
		"list", "", "description, id", "", $right_list, "%1" , "",
		gettext("To select several rights in the above ListBox, hold the CTRL key down while clicking the items") );

$HD_Form -> AddEditElement (gettext("ACTIVE"),
	   "active",
	   '1',
	   "RADIOBUTTON",
	   "",
	   "",
	   gettext("Choose if you want to active the agent"),
	   "" , "", "" , "Yes :1, - No:0",  "", "" , "", gettext("Enable agent"));

$HD_Form -> AddEditElement (gettext("BALANCE"),
	   "credit",
	   "",
	   "INPUT",
	   "size=30 maxlength=30",
	   "12",
	   gettext("Insert the number of credit"),
	   "" , "", "", "", "", "", "", gettext("currency : ").strtoupper(BASE_CURRENCY) );
	   
$HD_Form -> AddEditElement (gettext("CREDIT LIMIT"),
	   "climit",
	   "",
	   "INPUT",
	   "size=20 maxlength=20",
	   "12",
	   gettext("Insert the credit limit"),
	   "" , "", "",	"", "", "", "NO-NULL", gettext("Credit limit"));

$HD_Form -> AddEditElement (gettext("CURRENCY"),
	   "currency",
	   "",
	   "SELECT",
	   "",
	   "", "", "list" , "", "",	"", $currency_list, "%1", "", gettext("Currency used at the customer end.") );

$HD_Form -> AddEditElement (gettext("COMMISSION"),
	   "commission",
	   "",
	   "INPUT",
	   "size=20 maxlength=20",
	   "12",
	   gettext("Insert the commision"),
	   "" , "", "",	"", "", "", "NO-NULL", gettext("Commission"));

$HD_Form -> AddEditElement (gettext("VAT"),
	   "vat",
	   "",
	   "INPUT",
	   "size=20 maxlength=20",
	   "12",
	   gettext("Insert the VAT"),
	   "" , "", "",	"", "", "", "NO-NULL", gettext("VAT"));

$HD_Form -> AddEditElement (gettext("LANGUAGE"),
	   "language",
	   "",
	   "SELECT",
	   "",
	   "",
	   "",
	   "list" , "", "",	"", $language_list, "%1", "", "" );


$HD_Form -> AddEditElement (gettext("LAST NAME"),
		"lastname",
		'$value',
		"INPUT",
		"size=60 maxlength=50",
		"",
		gettext("Insert the lastname"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("FIRST NAME"),
		"firstname",
		'$value',
		"INPUT",
		"size=60 maxlength=50",
		"",
		gettext("Insert the firstname"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("ADDRESS"),
		"address",
		'$value',
		"INPUT",
		"size=60 maxlength=250",
		"",
		gettext("Insert the address"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("CITY"),
		"city",
		'$value',
		"INPUT",
		"size=20 maxlength=20",
		"",
		gettext("Insert the city"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("COUNTRY"),
		"country",
		'$value',
		"INPUT",
		"size=20 maxlength=20",
		"",
		gettext("Insert the country"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("ZIPCODE"),
		"zipcode",
		'$value',
		"INPUT",
		"size=10 maxlength=7",
		"",
		gettext("Insert the zip code"),
		"" , "", "", "", "", "", "", "");

$HD_Form -> AddEditElement (gettext("EMAIL"),
		"email",
		'$value',
		"INPUT",
		"size=30 maxlength=30",
		"",
		gettext("Insert the state"),
		"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("PHONE"),
		"phone",
		'$value',
		"INPUT",
		"size=30 maxlength=20",
		"",
		gettext("Insert the telephone number"),
		"" , "", "", "", "" , "", "", "");

$HD_Form -> AddEditElement (gettext("FAX"),
		"fax",
		'$value',
		"INPUT",
		"size=30 maxlength=20",
		"",
		gettext("Insert the fax number"),
		"" , "", "", "", "" , "", "", "");
		
	
	
$DBHandle_max  = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute("SELECT * FROM cc_tariffgroup");
if ( $resmax )
	$numrow = $resmax -> RecordCount( );

if ($numrow>0){ 
	$HD_Form -> AddEditElement (gettext("CALL PLAN"),
		"tariffgroup",	
		'$value',	
		"SELECT",		
		'size="10" multiple="multiple"',
		"",		
		"",	
		"sql",		
		"call_tariff",	
		"label, id",		
		"", "", "%1 : (%2)", "", "", "cc_agent_tariffgroup:id_tariffgroup:cc_tariffgroup LEFT JOIN cc_agent_tariffgroup ON cc_tariffgroup.id=cc_agent_tariffgroup.id_tariffgroup:DISTINCT cc_tariffgroup.tariffgroupname, cc_tariffgroup.id: cc_agent_tariffgroup.id_agent = %id:id_agent:%2 - (%1)::::%1:id='%1'");
}

$FG_QUERY_EDITION = 'login, passwd, perms, active, credit, climit, currency, commission, vat, language,  lastname, firstname, address, city, state, 
country, zipcode, email, phone,  fax';

$HD_Form -> FieldEditElement ($FG_QUERY_EDITION);

$HD_Form -> FG_EDITION_CLAUSE = " id='%id'";


if (DB_TYPE == "postgres") {
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "";
} else {
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS = "datecreation ";
	$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  = "now() ";
}

$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the properties of the")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";



