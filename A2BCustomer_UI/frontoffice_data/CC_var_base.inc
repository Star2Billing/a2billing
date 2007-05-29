<?php

// setup the webmaster id here
if (isset($id)) $old_id = $id; // some config file will use the cc_var_base as a reference document, but will need to keep the real value of id


// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_HEAD_COLOR = "#D1D9E7";


$FG_TABLE_EXTERN_COLOR = "#7F99CC"; //#CC0033 (Rouge)
$FG_TABLE_INTERN_COLOR = "#EDF3FF"; //#FFEAFF (Rose)


// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME='';

// name used to descripbe the instance of this module
$FG_INSTANCE_NAME='';

// name used to reffer to this class, used by the link AT_var_customers.inc
$FG_CLASS_NAME='';


// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#EEEEEE";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#CCCCCC";

// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array();


$FG_TABLE_DEFAULT_ORDER = "id";
$FG_TABLE_DEFAULT_SENS = "ASC";


// This Variable store the argument for the SQL query
$FG_COL_QUERY='';



// Number of column in the html table
$FG_NB_TABLE_COL=count($FG_TABLE_COL);


//Clause to execute on the table
$FG_TABLE_CLAUSE = "";


// The variable FG_TABLE_ID define the database field to use for the deletion and edition
$FG_TABLE_ID="id";

//ADD AUTOMATICLY THE ID TO YOUR SELECTED FIELDS
$FG_COL_QUERY.=", $FG_TABLE_ID";



// The variable $FG_EDITION define if you want process to the edition of the database record
$FG_EDITION=true;

// The variable  Where the edition will target (link)
$FG_EDITION_LINK="CC_entity_edit.php?form_action=ask-edit&atmenu=$FG_CLASS_NAME&id=";

// The variable  would define we want process to the deletion of this record
$FG_DELETION=true;

// This varaible define the size of the action column % or px
$FG_ACTION_SIZE_COLUMN="15%";

// The variable  Where the edition will target (link)
$FG_DELETION_LINK="CC_entity_edit.php?form_action=ask-delete&atmenu=$FG_CLASS_NAME&id=";

// The variable LIMITE_DISPLAY define the limit of record to display by page
$FG_LIMITE_DISPLAY=13;


// The variable  would define if you want have a link for the insert page in the view/edit page
$FG_LINK_ADD=true;

// The variable  Where the edition will target (link)
$FG_INSERT_LINK="CC_entity_edit.php?form_action=ask-add&atmenu=$FG_CLASS_NAME";


// The variable  would define if you want use a search engine in this module
$FG_SEARCH_ENGINE=false;



// The variable $FG_EDITION_HELP define if you want display a boutton to access to the help
$FG_EDITION_HELP=true;

// The variable  FG_EDITION_HELP_LINK and FG_EDITION_HELP_LINK_TITLE define the link  for the help
$FG_EDITION_HELP_LINK="href=\"#\" onclick=\"MM_openBrWindow('CC_help.php?id=5','Help','width=450,height=350')\"";
$FG_EDITION_HELP_LINK_TITLE = gettext("Information about edition");





//This variable will store the total number of column
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;


//This variable define the Title of the HTML table
$FG_HTML_TABLE_TITLE=" - $FG_INSTANCE_NAME List - ";

//This variable define the width of the HTML table
$FG_HTML_TABLE_WIDTH="95%";


//This variable define a text that you should put over the table list.
//To introduce the module working and utility ;)
$FG_INTRO_TEXT = gettext("You can browse through our ".$FG_INSTANCE_NAME." and modify their different properties<br>");


// TEXT
$FG_DELETE_ALT = gettext("Delete this ".$FG_INSTANCE_NAME);
$FG_EDIT_ALT = gettext("Edit this ".$FG_INSTANCE_NAME);



///////////////////////////////////////////////////////////////////////
//		   INFORMATION ABOUT THE EDITION		     //
///////////////////////////////////////////////////////////////////////


// THE VARIABLE $FG_TABLE_EDITION WOULD DEFINE THE COL THAT WE WANT SHOW IN YOUR EDITION TABLE 
// 0. NAME OF THE COLUMN IN THE HTML PAGE, 
// 1. NAME OF THE FIELD
// 2. VALUE OF THE FIELD
// 3. THE TYPE OF THE FIELD (INPUT/SELECT/TEXTAREA)
	// 4. THE PROPERTY OF THIS FIELD
	// 5. REGEXPRES TO CHECK THE VALUE
	//    "^.{3}$": A STRING WITH EXACTLY 3 CHARACTERS. 
	//     ^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$  : EMAIL ADRESSE
	// 6. ERROR MASSAGE

// 7.  IF THE FIELD TYPE IS A SELECT,  DEFINE LIST OR SQL
// 8.  IF SQL,		THE TABLE NAME
// 9. IF SQL,		THE FIELDS  : Three MAXIMUM IN ORDER (NAME, VALUE, ...other that we need for the display) ;)
// 10. IF SQL,		THE CLAUSE
// 11. IF LIST,		THE NAME OF THE LIST
// 12. IF LIST,		DISPLAY : %1 : (%2)



$FG_TABLE_EDITION[]=array (gettext("NAME"),
			   "name",		
			   '$value',	
			   "INPUT",	
			   "readonly size=60 maxlength=100",	
			   "3",	
			   gettext("Insert the user Name"),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array (gettext("FIRSTNAME"),
			   "firstname",		
			   '$value',	
			   "INPUT",	
			   "readonly size=60 maxlength=100",	
			   "3",	
			   gettext("Insert the firstname"),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]="";
  
$FG_TABLE_EDITION[]=array (gettext("EMAIL"),
			   "email",		
			   '$value',	
			   "INPUT",	
			   "size=60 maxlength=80",	
			   "1",	
			   gettext("Insert the email address of the webmaster"),
			   "" , "", "",	"", "" );

$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array (gettext("COMPANY"),
			   "company",		
			   '$value',	
			   "INPUT",	
			   "size=60 maxlength=100",	
			   "",	
			   gettext("Insert the name of your company"),
			   "" , "", "",	"", "" );

$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array (gettext("ADDRESS"),
			   "addresse",		
			   '$value',	
			   "INPUT",	
			   "size=60 maxlength=150",	
			   "3",	
			   gettext("Insert the Addresse"),
			   "" , "", "",	"", "" );

$FG_TABLE_COMMENT[]="";


$FG_TABLE_EDITION[]=array (gettext("CITY"),
			   "city",		
			   '$value',	
			   "INPUT",	
			   "size=60 maxlength=50",	
			   "3",	
			   gettext("Insert the City"),
			   "" , "", "",	"", "" );

$FG_TABLE_COMMENT[]="";



$FG_TABLE_EDITION[]=array (gettext("ZIPCODE"),
			   "zipcode",		
			   '$value',	
			   "INPUT",	
			   "size=16 maxlength=16",	
			   "3",
			   gettext("Insert the zipcode"),
			   "" , "", "",	"", "" );

$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array ("PHONE",	
			   "phone",		
			   '$value',	
			   "INPUT",	
			   "size=16 maxlength=16",	
			   "7",	
			   gettext("Insert the webmaster Phonenumber"),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array (gettext("FAX"),
			   "fax",		
			   '$value',	
			   "INPUT",	
			   "size=40 maxlength=40",	
			   "",	
			   gettext("Insert the webmaster Fax number"),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array (gettext("COUNTRY"),
			   "country_id",	
			   '$value',	
			   "SELECT",	
			   "",				
			   "",		
			   "",	
			   "sql",		
			   "country",	
			   "label, id",		
			   "", "", "%1");
$FG_TABLE_COMMENT[]="";

$FG_TABLE_EDITION[]=array (gettext("NEWSLETTERS"),
			   "newsletters",		
			   'f',
			   "RADIOBUTTON",	
			   "",	
			   "",	
			   gettext("Choose if the user will receive the newsletters"),
			   "" , "", "Yes:t,No:f",	"", "" );

$FG_TABLE_COMMENT[] = gettext("I acknowledge to receive informations.");



						
// This Variable store the argument for the SQL query
$FG_QUERY_EDITION='name, firstname, email, company, addresse, city, zipcode, phone, fax, country_id, newsletters';

// THE FIRST COLUMN WIDTH OF THE EDITION TABLE
$FG_TABLE_EDITION_WIDTH = "650";


// NUMBER OF COLUMN IN THE EDITION HTML TABLE
$FG_NB_TABLE_EDITION = count($FG_TABLE_EDITION);





//Clause to execute on the table
$FG_EDITION_CLAUSE = " id='%id'";

//This variable define the text that you should put over the table edition.
//To explain how the edition is working ;)
$FG_INTRO_TEXT_EDITION= '<p align="center"><font size="2"><strong><font color="#990000" size="5">'.gettext("Webmaster\'s lounge").' </font></strong></font></p><p align="center"><font size="2"><strong> </strong></font></p>'.gettext("Here are the personal data that you entered when you subscribed to Phone2Enter. <br>You can modify them, except your first name and your last name. <p><br>Check the correctness of your postal address, so that you will receive the cheques used to pay your commissions.").' </p>';

//$FG_INTRO_TEXT_EDITION="You can easily modify, through the following form, your personal data<br>";



///////////////////////////////////////////////////////////////////////
//		   INFORMATION ABOUT THE DELETION		     //
///////////////////////////////////////////////////////////////////////

$FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$FG_INSTANCE_NAME.", click on the delete button.");

$FG_INTRO_TEXT_DELETION = gettext("One ".$FG_INSTANCE_NAME." of the %table table has been deleted. <br> (Record with the id :")." %id)";


///////////////////////////////////////////////////////////////////////
//		   INFORMATION ABOUT THE ADDITION		     //
///////////////////////////////////////////////////////////////////////

$FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the add button.");

// INSERT INTO "entity" ("id", "pid", "erpid", "name", "contact", "adress", "city", "state", "zipcode", "phone", "fax", "datecreation", "dateancienne", "login", "passwd", "comments", "hotlineemail", "entity_type_id") VALUES (NEXTVAL('id_entity'::text), '', NULL, 'Kitty', 'Kitty', NULL, NULL, NULL, NULL, NULL, NULL, '2003-02-25', '2003-02-25', NULL, NULL, NULL, NULL, '2') 




// THE VARIABLE $FG_TABLE_EDITION WOULD DEFINE THE COL THAT WE WANT SHOW IN YOUR EDITION TABLE 
// 0. NAME OF THE COLUMN IN THE HTML PAGE, 
// 1. NAME OF THE FIELD
// 2. VALUE OF THE FIELD
// 3. THE TYPE OF THE FIELD (INPUT/SELECT/TEXTAREA)
	// 4. THE PROPERTY OF THIS FIELD
	// 5. REGEXPRES TO CHECK THE VALUE
	//    "^.{3}$": A STRING WITH EXACTLY 3 CHARACTERS. 
	//     ^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$  : EMAIL ADRESSE
	// 6. ERROR MASSAGE

// 7.  IF THE FIELD TYPE IS A SELECT,  DEFINE LIST OR SQL
// 8.  IF SQL,		THE TABLE NAME
// 9. IF SQL,		THE FIELDS  : TWO MAXIMUM IN ORDER (NAME, VALUE) ;)
// 10. IF SQL,		THE CLAUSE
// 11. IF LIST,		THE NAME OF THE LIST

//$FG_TABLE_EDITION[]=array ("Name",	"name",			'$value',	"INPUT",	"size=50 maxlength=100",	"^.{4}",	"Insert only caracters alphanumeric",		
//					"" ,		"",		"",		"",				"" );


$FG_TABLE_ADITION = $FG_TABLE_EDITION;


// This Variable store the argument for the SQL query
//$FG_QUERY_EDITION ='name, contact, entity_type_id, comments';
$FG_QUERY_ADITION = $FG_QUERY_EDITION;


// You should sometimes define some arguments that aren't defined inside the form but that you need for the insert

$FG_QUERY_ADITION_HIDDEN_FIELDS = "";
$FG_QUERY_ADITION_HIDDEN_VALUE  = "";


// THE FIRST COLUMN WIDTH OF THE ADDITION TABLE
$FG_TABLE_ADITION_WIDTH = $FG_TABLE_EDITION_WIDTH;


// NUMBER OF COLUMN IN THE ADDITION HTML TABLE
$FG_NB_TABLE_ADITION = count($FG_TABLE_ADITION);

//This variable define the text that you should put over the table edition.
//To explain how the edition is working ;)
$FG_INTRO_TEXT_ADITION = gettext("You can insert easily new ".$FG_INSTANCE_NAME.", <br>fill the following form and click on the add button<br>");

$FG_BUTTON_ADITION_SRC  = "../Images/btn_Add_94x20.gif";


$FG_BUTTON_EDITION_BOTTOM_TEXT = gettext("Once you have completed the form above, you'll be able to choose the BILLING PACK that best fits your content and your customers. Click on the *CONTINUE* when ready.");

$FG_BUTTON_EDITION_SRC  = "../Images/btn_Update_94x20.gif";

$FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$FG_INSTANCE_NAME." has been inserted. <br>Thanks to trust to these system !");


?>
