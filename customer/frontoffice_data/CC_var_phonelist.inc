<?php
include("CC_var_base.inc");
if (isset($id)) $old_id = $id; 

getpost_ifset(array('id', 'numbertodial', 'id_cc_campaign', 'name', 'activated', 'last_attempt', 'inuse', 'additionalinfo'));

//require (LANGUAGE_DIR.FILENAME_PREDICTIVEDIALER);

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;


$type_access=1;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME="cc_phonelist";

// name used to descripbe the instance of this module
$FG_INSTANCE_NAME="PhoneList";

// name used to reffer to this class, used by the link AT_var_customers.inc
$FG_CLASS_NAME="phonelist";



$actived_list = array(); $actived_list["1"]  = array( "Active", "1"); $actived_list["0"]  = array( "Inactive", "0");
$yesno_list = array(); $yesno_list["1"]  = array( "Yes", "1"); $yesno_list["0"]  = array( "No", "0");


		
// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array();



$FG_TABLE_COL[]=array (gettext("PHONENUMBER"), "numbertodial", "20%", "center", "sort", "26");
$FG_TABLE_COL[]=array (gettext("NAME"), "name", "20%", "center", "sort", "26");
$FG_TABLE_COL[]=array (gettext("LAST ATTEMPT"), "last_attempt", "15%", "center", "sort", "16", "", "", "", "", "", "display_dateformat");
$FG_TABLE_COL[]=array (gettext("CONNECTED TO"), "inuse", "10%", "center", "sort", "", "list", $yesno_list);
$FG_TABLE_COL[]=array (gettext("NUM TRIALS"), "num_trials_done", "5%", "center", "sort");
$FG_TABLE_COL[]=array (gettext("CALLED MINUTE"), "secondusedreal", "10%", "center", "SORT", "30", "", "", "", "", "", "display_minute");


// This Variable store the argument for the SQL query
$FG_COL_QUERY='numbertodial, name, last_attempt, inuse, num_trials_done, secondusedreal';


$FG_TABLE_DEFAULT_ORDER = "inuse";
$FG_TABLE_DEFAULT_SENS = "DESC, last_attempt DESC";

// Number of column in the html table
$FG_NB_TABLE_COL=count($FG_TABLE_COL);


//Clause to execute on the table
//$FG_TABLE_CLAUSE = "site_id IN (select id from site where webmaster_id='$webmaster_id')";
$FG_TABLE_CLAUSE = "";


// The variable FG_TABLE_ID define the database field to use for the deletion and edition
$FG_TABLE_ID="id";

//ADD AUTOMATICLY THE ID TO YOUR SELECTED FIELDS
$FG_COL_QUERY.=", $FG_TABLE_ID";



// The variable  Where the edition will target (link)
$FG_EDITION_LINK="CC_entity_edit_phonelist.php?form_action=ask-edit&atmenu=$FG_CLASS_NAME&id=";

$FG_EDITION=false;

// The variable  Where the edition will target (link)
$FG_DELETION_LINK="CC_entity_edit_phonelist.php?form_action=ask-delete&atmenu=$FG_CLASS_NAME&id=";

// The variable  would define we want process to the deletion of this record
$FG_DELETION=false;



// The variable LIMITE_DISPLAY define the limit of record to display by page
$FG_LIMITE_DISPLAY=10;


// The variable  would define if you want have a link for the insert page in the view/edit page
$FG_LINK_ADD=false;

// The variable  Where the edition will target (link)
$FG_INSERT_LINK="P2E_entity_edit.php?form_action=ask-add&atmenu=$FG_CLASS_NAME";


// The variable  would define if you want use a search engine in this module
$FG_SEARCH_ENGINE=false;




//This variable will store the total number of column
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION) $FG_TOTAL_TABLE_COL++;


//This variable define the Title of the HTML table
$FG_HTML_TABLE_TITLE=gettext("- PHONELIST List -");


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


$FG_TABLE_EDITION = array ();
$FG_TABLE_COMMENT = array ();


    

$FG_TABLE_EDITION[]=array (gettext("Phone Number"),
			   "numbertodial",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=40",	
			   "0",	
			   gettext("Insert the phonenumber"),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]= gettext("Enter here the phonenumber you want to call");


$FG_TABLE_EDITION[]=array (gettext("CAMPAIGN"),
			   "id_cc_campaign",
			   '$value',	
			   "SELECT",	
			   "", "", "",	
			   "sql",		
			   "cc_campaign",	
			   "campaign_name, id",		
			   "", "", "%1");
			   
$FG_TABLE_COMMENT[]= "";


$FG_TABLE_EDITION[]=array (gettext("NAME"),
			   "name",		
			   '$value',	
			   "INPUT",	
			   "size=30 maxlength=20",	
			   "",	
			   gettext("Insert the name "),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]= "";




if ($form_action=="ask-add"){	
	$begin_date = date("Y");
	$begin_date_plus = date("Y")+25;	
	$end_date = date("-m-d H:i:s");
	$comp_date = "value='".$begin_date.$end_date."'";
	$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
}

$FG_TABLE_EDITION[]=array (gettext("LAST ATTEMPT DATE"),
			   "last_attempt",		
			   '$value',	
			   "INPUT",	
			   "size=40 maxlength=40  $comp_date",	
			   "10",	
			   gettext("Insert the last attempt date"),
			   "" , "", "",	"", "", "", "", "res_display_dateformat" ); // , "", "res_display_dateformat"
$FG_TABLE_COMMENT[]= gettext("please respect the format YYYY-MM-DD HH:MM:SS. For instance, '2004-12-31 00:00:00'");




$FG_TABLE_EDITION[]=array (gettext("ENABLE"),
				   "enable",		
				   't',
				   "RADIOBUTTON",	
				   "",	
				   "",	
				   gettext("Choose if this phonenumber would be enable or not"),
				   "" , "", "Yes :1, - No:0",	"", "" );
$FG_TABLE_COMMENT[]= "";



$FG_TABLE_EDITION[]=array (gettext("ADDITIONALINFO"),
			   "additionalinfo",		
			   '',	
			   "TEXTAREA",	
			   "cols=50 rows=4",
			   "",	
			   gettext("Insert additional information"),
			   "" , "", "",	"", "" );
$FG_TABLE_COMMENT[]= "";


if ($form_action!="ask-add" && $form_action!="add"){	

$FG_TABLE_EDITION[]=array (gettext("INUSE"),
				   "inuse",		
				   't',
				   "RADIOBUTTON",	
				   "",	
				   "",	
				   gettext("Choose the status of this phonenumber"),
				   "" , "", "In use :1, - Not in use :0",	"", "" );
$FG_TABLE_COMMENT[]= "";

}


// 0 : nom de la table qui fait le liens entre les deux
// 1 : foreign key
// 2 : external table name which with we do the link
// 3 :				the field of this table which will appear
// 4 : sql to select all the instance of this external table correspong to the id of the current table
//
// 6 : how to display in the select form

// 7 : si on a encore une clé externe (3eme de la liste des champ de selection)
// 8 : table su laquel on lie
// 9 : champ qu'on demande (numero - 1 !!!)
// 10 : Format d'affichage
// 11 : Clause pour selectionner l'instance


// This Variable store the argument for the SQL query
$FG_QUERY_EDITION='numbertodial, id_cc_campaign, name, last_attempt, enable, additionalinfo';


if ($form_action!="ask-add" && $form_action!="add") $FG_QUERY_EDITION .= ', inuse';
    




// THE FIRST COLUMN WIDTH OF THE EDITION TABLE
// $FG_TABLE_EDITION_WIDTH = "122";


// NUMBER OF COLUMN IN THE EDITION HTML TABLE
$FG_NB_TABLE_EDITION = count($FG_TABLE_EDITION);


//Clause to execute on the table
//$FG_EDITION_CLAUSE = " id='%id' AND site_id IN (select id from site where webmaster_id='$webmaster_id')";
$FG_EDITION_CLAUSE = " id='%id'";


//This variable define the text that you should put over the table edition.
//To explain how the edition is working ;)
$FG_INTRO_TEXT_EDITION = gettext("You can modify, through the following form, the different properties of your ").$FG_INSTANCE_NAME."<br>";



///////////////////////////////////////////////////////////////////////
//		   INFORMATION ABOUT THE DELETION		     //
///////////////////////////////////////////////////////////////////////

$FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this ".$FG_INSTANCE_NAME.", click on the delete button.");

$FG_INTRO_TEXT_DELETION = gettext("One ".$FG_INSTANCE_NAME." of the %table table has been deleted. <br> (Record with the id :")." %id)";


///////////////////////////////////////////////////////////////////////
//		   INFORMATION ABOUT THE ADDITION		     //
///////////////////////////////////////////////////////////////////////

$FG_INTRO_TEXT_ADD = gettext("you can add easily a new ".$FG_INSTANCE_NAME.".<br>Fill the following fields and confirm by clicking on the button add.");

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





// if yes that allow your form to edit the form after added succesfully a instance
// in the case if you don't have the same option in the edition and the adding option
$FG_ADITION_GO_EDITION = "no";
$FG_ADITION_GO_EDITION_MESSAGE = gettext("The destination has been created correctly.");




// NUMBER OF COLUMN IN THE ADDITION HTML TABLE
$FG_NB_TABLE_ADITION = count($FG_TABLE_ADITION);



//This variable define the text that you should put over the table edition.
//To explain how the edition is working ;)
//$FG_INTRO_TEXT_ADITION = "You can insert easily new $FG_INSTANCE_NAME, <br>fill the following form and click on the add button<br>";
$FG_INTRO_TEXT_ADITION = gettext("Add a NEW ")."\"$FG_INSTANCE_NAME\".";

$FG_TEXT_ADITION_CONFIRMATION = gettext("Your new ".$FG_INSTANCE_NAME." has been inserted. <br>");


$FG_TEXT_ERROR_DUPLICATION = "ERROR_DUPLICATION !";




$FG_BUTTON_ADITION_SRC  = "../Images/cormfirmboton.gif";


$FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Setup those values to create the new ".$FG_INSTANCE_NAME);


$FG_BUTTON_EDITION_SRC  = "../Images/cormfirmboton.gif";


$FG_BUTTON_EDITION_BOTTOM_TEXT = gettext("Made your change to edit the current ".$FG_INSTANCE_NAME);


$FG_GO_LINK_AFTER_ACTION = "CC_entity_phonelist.php?atmenu=phonelist&stitle=PhoneList&id=";

$FG_GO_LINK_AFTER_ACTION_DELETE = $FG_GO_LINK_AFTER_ACTION;


?>
