<?php
/***************************************************************************
 *
 * Class.FormHandler.php : FormHandler - PHP : Handle, Form Generator (FG) for A2Billing
 * Written for PHP 4.x & PHP 5.X versions.
 *
 * A2Billing -- Billing solution for use with Asterisk(tm).
 * Copyright (C) 2004, 2009 Belaid Arezqui <areski _atl_ gmail com>
 *
 * See http://www.a2billing.org for more information about
 * the A2Billing project.
 * Please submit bug reports, patches, etc to <areski _atl_ gmail com>
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 ****************************************************************************/
include (dirname(__FILE__) . "/Class.FormBO.php");

class FormHandler
{
	private static $Instance;
	var $_action = '';
	var $_vars = null;
	var $_processed = array();
	var $DBHandle;
	var $VALID_SQL_REG_EXP = true;
	var $RESULT_QUERY = false;

	var $pqp;


	/* CONFIG THE VIEWER : CV */
	var $CV_TOPVIEWER = '';
	var $CV_NO_FIELDS = "THERE IS NO RECORD !";
	var $CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = true;
	var $CV_TITLE_TEXT = '';
	var $CV_TEXT_TITLE_ABOVE_TABLE = "DIRECTORY";
	var $CV_DISPLAY_FILTER_ABOVE_TABLE = true;
	var $CV_FILTER_ABOVE_TABLE_PARAM = "?id=";
	var $CV_FOLLOWPARAMETERS = '';
	var $CV_DO_ARCHIVE_ALL = false;


	var $CV_DISPLAY_RECORD_LIMIT = true;
	var $CV_DISPLAY_BROWSE_PAGE = true;

	var $CV_CURRENT_PAGE = 0;

	var $FG_VIEW_TABLE_WITDH = '100%';
	var $FG_ACTION_SIZE_COLUMN = '25%';
	/**
    * Sets the debug output (1 = low, 2 = Normal, 3 = High). Default value is "0" .
    * @public	-	@type integer
    */
	var $FG_DEBUG = 0;

	/**
    * Sets the table name.
    * @public	-	@type string
    */
	var $FG_TABLE_NAME="";

	/**
    * Sets the table name used for count.
    * @public	-	@type string
    */
	var $FG_TABLE_NAME_COUNT="";


	/**
    * Sets the instance_name, used to descripbe the name of the element your are managing
    * @public	-	@type string
    */
	var $FG_INSTANCE_NAME="";

	/**
    * Sets the main clause - Clause to execute on the table
    * @public	-	@type string
    */
	var $FG_TABLE_CLAUSE = "";

	/**
    * Sets the table list you will need to feed the SELECT from element
    * @public	-	@type array - ( String to display, value to save)
    */
	var $tablelist = array();

	/**
    * ARRAY with the list of element to display in the ViewData page
    * @public	-	@type array
    */
	var $FG_TABLE_COL = array();

	/**
    * Sets the fieldname of the SQL query to display in the ViewData page, ie: "id, name, mail"
    * @public	-	@type string
    */
	var $FG_COL_QUERY = "";

    /**
    * Keep the number of column  -  Number of column in the html table
    * @public	-	@type integer
    */
	var $FG_NB_TABLE_COL=0;
	var $FG_TOTAL_TABLE_COL=0;


	/**
    * Keep the ID of the table
    * @public	-	@type string
    */
	var $FG_TABLE_ID = 'id';

	/*
	 * Adding record button in list view
	 */
	var $FG_LIST_ADDING_BUTTON=false;
	var $FG_LIST_ADDING_BUTTON_LINK= '';
	var $FG_LIST_ADDING_BUTTON_IMG = '';
	var $FG_LIST_ADDING_BUTTON_MSG = '';
	var $FG_LIST_ADDING_BUTTON_ALT = '';

	/**
    * Sets if we want a colum "ACTION" to EDIT or to DELETE
    * @public	-	@type boolean
    */
	var $FG_ADDITION=false;
	var $FG_DELETION=false;
	var $FG_INFO=false;
	var $FG_EDITION=false;
	var $FG_OTHER_BUTTON1=false;
	var $FG_OTHER_BUTTON2=false;
	var $FG_OTHER_BUTTON3=false;
	var $FG_OTHER_BUTTON4=false;
	var $FG_OTHER_BUTTON5=false;


	/**
    * Keep the link for the action (EDIT & DELETE)
    * @public	-	@type string
    */
	var $FG_EDITION_LINK	= '';
	var $FG_DELETION_LINK	= '';
	var $FG_DELETION_FORBIDDEN_ID	= array();
	var $FG_INFO_LINK='';
	var $FG_OTHER_BUTTON1_LINK	= '';
	var $FG_OTHER_BUTTON2_LINK	= '';
	var $FG_OTHER_BUTTON3_LINK	= '';
	var $FG_OTHER_BUTTON4_LINK	= '';
	var $FG_OTHER_BUTTON5_LINK	= '';

	var $FG_EDITION_IMG	= 'edit.png';
	var $FG_DELETION_IMG= 'delete.png';
	var $FG_INFO_IMG='info.png';

	var $FG_OTHER_BUTTON1_IMG = '';
	var $FG_OTHER_BUTTON2_IMG = '';
	var $FG_OTHER_BUTTON3_IMG = '';
	var $FG_OTHER_BUTTON4_IMG = '';
	var $FG_OTHER_BUTTON5_IMG = '';

	var $FG_OTHER_BUTTON1_FORBIDDEN_KEY	= array();
	var $FG_OTHER_BUTTON2_FORBIDDEN_KEY	= array();
	var $FG_OTHER_BUTTON3_FORBIDDEN_KEY	= array();
	var $FG_OTHER_BUTTON4_FORBIDDEN_KEY	= array();
	var $FG_OTHER_BUTTON5_FORBIDDEN_KEY	= array();

	var $FG_EDIT_PAGE_CONFIRM_BUTTON	= '';
	var $FG_DELETE_PAGE_CONFIRM_BUTTON	= '';
	var $FG_ADD_PAGE_CONFIRM_BUTTON		= '';

	/**
    * Sets the number of record to show by page
    * @public	-	@type integer
    */
	var $FG_LIMITE_DISPLAY=10;
	var $SQL_GROUP = null;

	/**
    * Sets the variable to control the View Module
    * @public	-	@type integer
    */
	var $FG_STITLE = '';
	var $FG_CURRENT_PAGE  = 0;
	var $FG_ORDER = '';
	var $FG_SENS = '';

	var $FG_NB_RECORD_MAX  = 0;
	var $FG_NB_RECORD  = 0;

	/**
	* Sets the variables to control the Apply filter
	* @public  - @type string
	*/
	var $FG_FILTER_FORM_ACTION = 'list';

	var $FG_FILTER_APPLY = false;
	var $FG_FILTERTYPE = 'INPUT'; // INPUT :: SELECT :: POPUPVALUE
	var $FG_FILTERFIELD = '';
	var $FG_FILTERFIELDNAME = '';
	var $FG_FILTERPOPUP = array('CC_entity_card.php?popup_select=1&', ", 'CardNumberSelection','width=550,height=350,top=20,left=100'");

	// SECOND FILTER
	var $FG_FILTER_APPLY2 = false;
	var $FG_FILTERTYPE2 = 'INPUT'; // INPUT :: SELECT :: POPUPVALUE
	var $FG_FILTERFIELD2 = '';
	var $FG_FILTERFIELDNAME2 = '';
	var $FG_FILTERPOPUP2 = array();


	/**
	* Sets the variables to control the search filter
	* @public  - @type boolean , array , string
	*/
	var $FG_FILTER_SEARCH_FORM = false;

	var $FG_FILTER_SEARCH_1_TIME = false;
	var $FG_FILTER_SEARCH_1_TIME_TEXT = '';
	var $FG_FILTER_SEARCH_1_TIME_FIELD = 'creationdate';

	var $FG_FILTER_SEARCH_1_TIME_BIS = false;
	var $FG_FILTER_SEARCH_1_TIME_TEXT_BIS = '';
	var $FG_FILTER_SEARCH_1_TIME_FIELD_BIS = '';

	var $FG_FILTER_SEARCH_3_TIME = false;
	var $FG_FILTER_SEARCH_3_TIME_TEXT = '';
	var $FG_FILTER_SEARCH_3_TIME_FIELD = 'creationdate';

	var $FG_FILTER_SEARCH_FORM_1C = array();
	var $FG_FILTER_SEARCH_FORM_2C = array();
	var $FG_FILTER_SEARCH_FORM_SELECT = array();
	var $FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
	var $FG_FILTER_SEARCH_TOP_TEXT = "";
	var $FG_FILTER_SEARCH_SESSION_NAME = '';
	var $FG_FILTER_SEARCH_DELETE_ALL = true;


	/**
	* Sets the variable to define if we want a splitable field into the form
	* @public  - @type void , string (fieldname)
	* ie : the value of a splitable field might be something like 12-14 or 15;16;17 and it will make multiple insert
	* according to the values/ranges defined.
	*/
	var $FG_SPLITABLE_FIELD = '';

	/**
	* Sets the variables to control the top search filter
	* @public  - @type void , string
	*/
	var $FG_TOP_FILTER_VALUE = 0;
	var $FG_TOP_FILTER_NAME = '';

	/**
	* Sets the variables to control the CSV export
	* @public  - @type boolean
	*/
	var $FG_EXPORT_CSV = false;
    var $FG_EXPORT_XML = false;
	var $FG_EXPORT_SESSION_VAR = '';

	/**
    * Sets the fieldname of the SQL query for Export e.g:name, mail"
    * @public	-	@type string
    */
    var $FG_EXPORT_FIELD_LIST = "";

	/**
     * Sets the TEXT to display above the records displayed
     * @public   -  @string
     */
	var $FG_INTRO_TEXT="You can browse through our #FG_INSTANCE_NAME# and modify their different properties<br>";


	/**
     * Sets the ALT TEXT after mouse over the bouton
     * @public   -  @string
     */

	var $FG_DELETE_ALT = "Delete this record";
	var $FG_EDIT_ALT = "Edit this record";
	var $FG_INFO_ALT = "Info on this record";
	var $FG_OTHER_BUTTON1_ALT = '';
	var $FG_OTHER_BUTTON2_ALT = '';
	var $FG_OTHER_BUTTON3_ALT = '';
	var $FG_OTHER_BUTTON4_ALT = '';
	var $FG_OTHER_BUTTON5_ALT = '';

	var $FG_OTHER_BUTTON1_HTML_CLASS = '';
	var $FG_OTHER_BUTTON2_HTML_CLASS = '';
	var $FG_OTHER_BUTTON3_HTML_CLASS = '';
	var $FG_OTHER_BUTTON4_HTML_CLASS = '';
	var $FG_OTHER_BUTTON5_HTML_CLASS = '';

	var $FG_OTHER_BUTTON1_HTML_ID = '';
	var $FG_OTHER_BUTTON2_HTML_ID = '';
	var $FG_OTHER_BUTTON3_HTML_ID = '';
	var $FG_OTHER_BUTTON4_HTML_ID = '';
	var $FG_OTHER_BUTTON5_HTML_ID = '';

	var $FG_OTHER_BUTTON1_CONDITION = '';
	var $FG_OTHER_BUTTON2_CONDITION = '';
	var $FG_OTHER_BUTTON3_CONDITION = '';
	var $FG_OTHER_BUTTON4_CONDITION = '';
	var $FG_OTHER_BUTTON5_CONDITION = '';

	var $FG_EDITION_CONDITION = '';
	var $FG_DELETION_CONDITION = '';

	//	-------------------- DATA FOR THE EDITION --------------------

	/**
    * ARRAY with the list of element to EDIT/REMOVE/ADD in the edit page
    * @public	-	@type array
    */
	var $FG_TABLE_EDITION = array ();
	var $FG_TABLE_ADITION = array ();

	/**
    * ARRAY with the comment below each fields
    * @public	-	@type array
    */
	var $FG_TABLE_COMMENT = array ();

	/**
    * ARRAY with the regular expression to check the form
    * @public	-	@type array
    */
	var $FG_regular = array();

	/**
    * Array that will contain the field where the regularexpression check have found errors
    * @public	-	@type array
    */
	var $FG_fit_expression = array();

	/**
    * Set the fields  for the EDIT/ADD query
    * @public	-	@type string
    */
	var $FG_QUERY_EDITION='';
	var $FG_QUERY_ADITION='';


	/**
    * Set the width  of the column to the EDIT FORM
    * @public	-	@type string
    */
	var $FG_TABLE_EDITION_WIDTH = '122';

	/**
    * Keep the number of the column into EDIT FORM
    * @public	-	@type integer
    */
	var $FG_NB_TABLE_EDITION = 0;
	var $FG_NB_TABLE_ADITION = 0;


	/**
    * Set the SQL Clause for the edition
    * @public	-	@type string
    */
	var $FG_EDITION_CLAUSE = " id='%id' ";

	/**
    * Set the HIDDED VALUE for the edition/addition
	* to insert some values that you do not want to display into the Form but as an hidden field
	* FG_QUERY_EDITION_HIDDEN_FIELDS = "field1, field2"
	* FG_QUERY_EDITION_HIDDEN_VALUE = "value1, value2"
	* FG_QUERY_ADITION_HIDDEN_FIELDS = "field1, field2"
	* FG_QUERY_ADITION_HIDDEN_VALUE = "value1, value2"
	* FG_QUERY_SQL_HIDDEN = ',field1, field2';
    * @public	-	@type string
    */
	var $FG_QUERY_EDITION_HIDDEN_FIELDS = '';
	var $FG_QUERY_EDITION_HIDDEN_VALUE  = '';
	var $FG_QUERY_ADITION_HIDDEN_FIELDS = '';
	var $FG_QUERY_ADITION_HIDDEN_VALUE  = '';
	var $FG_QUERY_SQL_HIDDEN = '';

	var $FG_EDITION_HIDDEN_PARAM = '';
	var $FG_EDITION_HIDDEN_PARAM_VALUE  = '';
	var $FG_ADITION_HIDDEN_PARAM = '';
	var $FG_ADITION_HIDDEN_PARAM_VALUE  = '';

	/**
    * Set the EXTRA HIDDED VALUES for the edition/addition
    * @public	-	@type array
    */
	var $FG_QUERY_EXTRA_HIDDED = '';

     /**
     * Set the Hidden value for the edition/addition
     * It helps to generate the values for the sip and iax account
     * if this variable is set from the calling file, then framwork will not build values for it.
     * Set its length to 0 if you want framework to generate the values and provide required values
     * into the POST data.
     */

     var $FG_QUERY_ADITION_SIP_IAX_VALUE = '';


	/**
    * Sets the link where to go after an ACTION (EDIT/DELETE/ADD)
    * @public	-	@type string
    */
	var $FG_GO_LINK_AFTER_ACTION;
	var $FG_GO_LINK_AFTER_ACTION_ADD;
	var $FG_GO_LINK_AFTER_ACTION_DELETE;
	var $FG_GO_LINK_AFTER_ACTION_EDIT;


	/** ####################################################
     * if yes that allow your form to edit the form after added succesfully a instance
	 * in the case if you don't have the same option in the edition and the adding option
     * @public   -  @string
    */

	var $FG_ADITION_GO_EDITION = "no";

	var $FG_ADITION_GO_EDITION_MESSAGE = "The document has been created correctly. Now, you can define the different tariff that you want to associate.";


	// ------------------- ## MESSAGE SECTION  ## -------------------

	var $FG_INTRO_TEXT_EDITION="You can modify, through the following form, the different properties of your #FG_INSTANCE_NAME#<br>";

	var $FG_INTRO_TEXT_ASK_DELETION = "If you really want remove this #FG_INSTANCE_NAME#, click on the delete button.";

	var $FG_INTRO_TEXT_DELETION = "A #FG_INSTANCE_NAME# has been deleted!";

	var $FG_INTRO_TEXT_ADD = "you can add easily a new #FG_INSTANCE_NAME#.<br>Fill the following fields and confirm by clicking on the button add.";

	var $FG_INTRO_TEXT_ADITION = "Add a \"#FG_INSTANCE_NAME#\" now.";

	var $FG_TEXT_ADITION_CONFIRMATION = "Your new #FG_INSTANCE_NAME# has been inserted. <br>";

	var $FG_TEXT_ADITION_ERROR = '<font color="Red"> Your new #FG_INSTANCE_NAME# has not been inserted. </font><br> ';

	var $FG_TEXT_ERROR_DUPLICATION = "You cannot choose more than one !";


	// ------------------- ## BUTTON/IMAGE SECTION  ## -------------------
	var $FG_BUTTON_ADITION_SRC  = "Images_Path/en/continue_boton.gif";
	var $FG_BUTTON_EDITION_SRC  = "Images_Path/en/continue_boton.gif";

	var $FG_BUTTON_ADITION_BOTTOM_TEXT = "";

	var $FG_BUTTON_EDITION_BOTTOM_TEXT = "";

	var $FG_ADDITIONAL_FUNCTION_BEFORE_ADD = '';
	var $FG_ADDITIONAL_FUNCTION_AFTER_ADD = '';
	var $FG_ADDITIONAL_FUNCTION_BEFORE_DELETE = '';
	var $FG_ADDITIONAL_FUNCTION_AFTER_DELETE = '';
	var $FG_ADDITIONAL_FUNCTION_BEFORE_EDITION = '';
	var $FG_ADDITIONAL_FUNCTION_AFTER_EDITION = '';

	var $FG_TABLE_ALTERNATE_ROW_COLOR = array();

	var $FG_TABLE_DEFAULT_ORDER = "id";
	var $FG_TABLE_DEFAULT_SENS = "ASC";

    // Delete Foreign Keys or not
    // if it is set to true and confirm flag is true confirm box will be showed.
    var $FG_FK_DELETE_ALLOWED = false;

	// if it is set to true and Allowed flag is true all dependent records will be deleted.
	var $FG_FK_DELETE = false;

    // Foreign Key Tables
    var $FG_FK_TABLENAMES = array();

    //Foreign Key Field Names
    var $FG_FK_EDITION_CLAUSE = array();

    //Foreign Key Delete Message Display, it will display the confirm delete dialog if there is some
    //some detail table exists. depends on the values of FG_FK_DELETE_ALLOWED
    var $FG_FK_DELETE_CONFIRM = false;

    //Foreign Key Records Count
    var $FG_FK_RECORDS_COUNT = 0;

    //Foreign Key Exists so Warn only not to delete ,,Boolean
    var $FG_FK_WARNONLY  = false;

    //is Child Records exists
    var $FG_ISCHILDS = true;

    // Delete Message for FK
    var $FG_FK_DELETE_MESSAGE = "Are you sure to delete all records connected to this instance.";

    //To enable Disable Selection List
    var $FG_DISPLAY_SELECT  = false;

    //Selection List Field Name to get from Database
    var $FG_SELECT_FIELDNAME  = "";

	// Configuration Key value Field Name
    var $FG_CONF_VALUE_FIELDNAME  = "";

	// For Pre Selected Delete
    // Pre Selected Records Count
    var $FG_PRE_COUNT = 0;

    //*****************************
	//This variable define the width of the HTML table
	var $FG_HTML_TABLE_WIDTH="95%";

	// text for multi-page navigation.
	var $lang = array('strfirst' => '&lt;&lt; First', 'strprev' => '&lt; Prev', 'strnext' => 'Next &gt;', 'strlast' => 'Last &gt;&gt;' );

	var $logger = null;

	var $FG_ENABLE_LOG = ENABLE_LOG;

    // CSRF TOKEN
    var $FG_CSRF_STATUS           = true;
    var $FG_CSRF_TOKEN_SALT       = CSRF_SALT;
    var $FG_CSRF_TOKEN_KEY        = null;
    var $FG_CSRF_TOKEN            = null;
    var $FG_CSRF_FIELD            = 'csrf_token';
    var $FG_FORM_UNIQID_FIELD     = 'form_id';


	// ----------------------------------------------
	// CLASS CONSTRUCTOR : FormHandler
	//	@public
	//	@returns void
	//	@ $tablename + $instance_name
	// ----------------------------------------------

	function __construct ($tablename=null, $instance_name=null, $action=null, $tablename_count=null)
	{
		Console::log('Construct FormHandler');
		Console::logMemory($this, 'FormHandler Class : Line '.__LINE__);
		Console::logSpeed('FormHandler Class : Line '.__LINE__);
		self :: $Instance = $this;
		$this -> FG_TABLE_NAME = $tablename;
		$this -> FG_INSTANCE_NAME = $instance_name;
		$this -> FG_TABLE_NAME_COUNT = $tablename_count;

	  	if ($this->FG_DEBUG) echo "".$this -> Host."";

		$this -> set_regular_expression();

		$this ->_action = $action ? $action : filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

        // If anti CSRF protection is enabled
        if ($this->FG_CSRF_STATUS == true) {
            // Initializing anti csrf token (Generate a key, concat it with salt and hash it)
            $this -> FG_CSRF_TOKEN_KEY = $this->genCsrfTokenKey();
            $this -> FG_CSRF_TOKEN = $this->FG_CSRF_TOKEN_SALT.$this->FG_CSRF_TOKEN_KEY;
            $this -> FG_CSRF_TOKEN = hash('SHA256', $this->FG_CSRF_TOKEN);
            $this -> FG_FORM_UNIQID = uniqid();
            // print $this -> FG_FORM_UNIQID;
            // echo "<br/>------_POST-------<br/>";
            // print_r($_POST);
            // echo "<br/>-------_SESSION------<br/>";
            // print_r($_SESSION);

            $this -> FG_FORM_RECEIVED_UNIQID = $_POST[$this->FG_FORM_UNIQID_FIELD];
            $this -> FG_FORM_RECEIVED_TOKEN = $_POST[$this->FG_CSRF_FIELD];
            $this -> FG_CSRF_RECEIVED_TOKEN = $_SESSION['CSRF_TOKEN'][$this->FG_FORM_RECEIVED_UNIQID];
            $_SESSION['CSRF_TOKEN'][$this->FG_FORM_UNIQID] = $this->FG_CSRF_TOKEN;
            // echo "<br/>------_SESSION::-------<br/>";
            // print_r($_SESSION);

            if ($this->FG_DEBUG) {
                echo 'FG_FORM_UNIQID : '.$this->FG_FORM_UNIQID.'<br />';
                echo 'CSRF NEW TOKEN : '.$this->FG_CSRF_TOKEN.'<br />';
                echo 'CSRF RECEIVED TOKEN : '.$this->FG_CSRF_RECEIVED_TOKEN.'<br />';
            }
            if (!empty($_POST)) {
                // Check CSRF
                if (!$this -> FG_CSRF_RECEIVED_TOKEN or
                    ($this -> FG_CSRF_RECEIVED_TOKEN != $this -> FG_FORM_RECEIVED_TOKEN)){
                    echo "CSRF Error!";
                    exit();
                } else {
                    //Remove key from the session
                    // echo "Remove key from the session";
                    unset($_SESSION['CSRF_TOKEN'][$this->FG_FORM_RECEIVED_UNIQID]);
                }
            }
        }

		$this->_vars = array_merge((array)$_GET, (array)$_POST);

		$this -> def_list();

        //initializing variables with gettext
		$this -> CV_NO_FIELDS = gettext("No data found!");
        $this -> CV_TEXT_TITLE_ABOVE_TABLE = gettext("DIRECTORY");
        $this -> CV_TITLE_TEXT = $instance_name.' '. gettext("list");
        $this -> FG_FILTER_SEARCH_TOP_TEXT = gettext("Define criteria to make a precise search");
        $this -> FG_INTRO_TEXT = gettext("You can browse through our")." #FG_INSTANCE_NAME# ".gettext("and modify their different properties").'<br>';
        $this -> FG_DELETE_ALT = gettext("Delete this record");
	    $this -> FG_EDIT_ALT = gettext("Edit this record");
        $this -> FG_ADITION_GO_EDITION_MESSAGE = gettext("The document has been created correctly. Now, you can define the different tariff that you want to associate.");
        $this -> FG_INTRO_TEXT_EDITION = gettext("You can modify, through the following form, the different properties of your")." #FG_INSTANCE_NAME#".'<br>';
        $this -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." #FG_INSTANCE_NAME#, ".gettext("Click on the delete button.");
        $this -> FG_INTRO_TEXT_DELETION = gettext("One")." #FG_INSTANCE_NAME# ".gettext("has been deleted!");

        $this -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." #FG_INSTANCE_NAME#.<br>".gettext("Fill the following fields and confirm by clicking on the button add.");
        $this -> FG_INTRO_TEXT_ADITION = gettext("Add a")." \"#FG_INSTANCE_NAME#\" ".gettext("now.");
        $this -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." #FG_INSTANCE_NAME# ".gettext("has been inserted.".'<br>');
        $this -> FG_TEXT_ADITION_ERROR = '<font color="Red">'.gettext("Your new")." #FG_INSTANCE_NAME# ".gettext("hasn't been inserted.").'<br>'."</font>";
        $this -> FG_TEXT_ERROR_DUPLICATION = gettext("You cannot choose more than one !");

        $this -> FG_FK_DELETE_MESSAGE = gettext("Are you sure to delete all records connected to this instance.");

		$this -> FG_EDIT_PAGE_CONFIRM_BUTTON	= gettext("CONFIRM DATA");
		$this -> FG_DELETE_PAGE_CONFIRM_BUTTON	= gettext('DELETE');
		$this -> FG_ADD_PAGE_CONFIRM_BUTTON		= gettext('CONFIRM DATA');

		if($this -> FG_ENABLE_LOG == 1) {
			$this -> logger = new Logger();
		}
	}


    /*
    * Generate a csrf token
    */
    private function genCsrfTokenKey() {
        $token1 = microtime();
        $token2 = uniqid(null, true);
        $token3 = session_id();
        $token4 = mt_rand();

        return base64_encode($token1.$token2.$token3.$token4);
    }

	static function GetInstance() {
		if (empty (self :: $Instance)) {
			$Instance = null;
		}
		return self :: $Instance;
	}

	function setDBHandler  ($DBHandle=null)
	{
		Console::log('FormHandler -> setDBHandler');
		Console::logMemory($this, 'FormHandler -> setDBHandler : Line '.__LINE__);
		Console::logSpeed('FormHandler -> setDBHandler : Line '.__LINE__);

		$this->DBHandle = $DBHandle;
	}

	/**
     * Perform the execution of some actions to prepare the form generation
     * @public
     */
	function init ()
	{
		$processed = $this->getProcessed();

		Console::log('FormHandler -> init');
		Console::logMemory($this, 'FormHandler -> init : Line '.__LINE__);
		Console::logSpeed('FormHandler -> init : Line '.__LINE__);

		global $_SERVER;


		if ($processed['section']!="") {
			$section = $processed['section'];
			$_SESSION["menu_section"] = intval($section);
		} else {
			$section = intval($_SESSION["menu_section"]);
		}
		$ext_link ='';
		if (is_numeric($processed['current_page']))$ext_link.="&current_page=".$processed['current_page'];
		if (!empty($processed['order']) && !empty($processed['sens']))$ext_link.="&order=".$processed['order']."&sens=".$processed['sens'];
		$this -> FG_EDITION_LINK	= filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?form_action=ask-edit".$ext_link."&id=";
		$this -> FG_DELETION_LINK	= filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?form_action=ask-delete".$ext_link."&id=";

		$this -> FG_DELETE_ALT = gettext("Delete this ").$this -> FG_INSTANCE_NAME;
		$this -> FG_EDIT_ALT = gettext("Edit this ").$this -> FG_INSTANCE_NAME;

		$this -> FG_INTRO_TEXT 	= str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_INTRO_TEXT);
		$this -> FG_INTRO_TEXT_EDITION 	= str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_INTRO_TEXT_EDITION);
		$this -> FG_INTRO_TEXT_ASK_DELETION = str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_INTRO_TEXT_ASK_DELETION);
		$this -> FG_INTRO_TEXT_DELETION	= str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_INTRO_TEXT_DELETION);
		$this -> FG_INTRO_TEXT_ADD = str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_INTRO_TEXT_ADD);
		$this -> FG_INTRO_TEXT_ADITION 	= str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_INTRO_TEXT_ADITION);
		$this -> FG_TEXT_ADITION_CONFIRMATION = str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_TEXT_ADITION_CONFIRMATION);
		$this -> FG_TEXT_ADITION_ERROR = str_replace('#FG_INSTANCE_NAME#', $this -> FG_INSTANCE_NAME, $this -> FG_TEXT_ADITION_ERROR);
		$this -> FG_FILTER_SEARCH_TOP_TEXT = gettext("Define criteria to make a precise search");

		$this -> FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F2EE";
		$this -> FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FCFBFB";

		$this -> FG_TOTAL_TABLE_COL = $this -> FG_NB_TABLE_COL;
		if ($this -> FG_DELETION || $this -> FG_INFO || $this -> FG_EDITION || $this -> FG_OTHER_BUTTON1 || $this -> FG_OTHER_BUTTON2 || $this -> FG_OTHER_BUTTON3 || $this -> FG_OTHER_BUTTON4 || $this -> FG_OTHER_BUTTON5) {
			$this -> FG_TOTAL_TABLE_COL++;
		}
	}

	/**
     * Define the list
     * @public
     */
	function def_list ()
	{
		Console::log('FormHandler -> def_list');
		Console::logMemory($this, 'FormHandler -> def_list : Line '.__LINE__);
		Console::logSpeed('FormHandler -> def_list : Line '.__LINE__);

		$this -> tablelist['status_list']["1"] = array( gettext("INSERTED"), "1");
		$this -> tablelist['status_list']["2"] = array( gettext("ENABLE"), "2");
		$this -> tablelist['status_list']["3"] = array( gettext("DISABLE"), "3");
		$this -> tablelist['status_list']["4"] = array( gettext("FREE"), "4");
	}

	function &getProcessed()
	{
		foreach ($this->_vars as $key => $value) {
			if (!$this->_processed[$key] or empty($this->_processed[$key])) {
				$this->_processed[$key] = sanitize_data($value);
				if($key=='username') {
					//rebuild the search parameter to filter character to format card number
					$filtered_char = array(" ", "-", "_","(",")","+");
					$this->_processed[$key]= str_replace($filtered_char, "", $this->_processed[$key]);
				}
				if($key=='pwd_encoded' && !empty($value)) {
					$this->_processed[$key] = hash("whirlpool", $this->_processed[$key]);
				}
			}
		}
		return $this->_processed;
	}

	function sanitize_tag($input)
	{
		$search = array(
		    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		);

	    $output = preg_replace($search, '', $input);
	    return $output;
	}

	// ----------------------------------------------
    // RECIPIENT METHODS
    // ----------------------------------------------

	/**
     * Adds a "element" to the FG_TABLE_COL.  Returns void.
     * @public
	 * @ 1. $displayname
	 * @ 2. $fieldname
	 * @ 3. $colpercentage
	 * @ 4. $textalign
	 * @ 5 .$sort
	 * @ 6. $char_limit
	 * @ 7. $lie_type ("lie", "list") , where lie is used for sql. ( TODO : any reason to keep lie instead of sql ?.)
	 * @ 8. $lie_with (SQL query with the tag '%1' || a defined list: $tablelist["nbcode"] )
	 * OLD
	 * @ 8. $lie_with tablename
	 * @ 9. $lie_fieldname
	 * @ 10. $lie_clause
	 * @ 11. $lie_display
	 * @ 12. $function render
     */

	function AddViewElement($displayname, $fieldname, $colpercentage, $textalign='center', $sort='sort', $char_limit = null, $lie_type = null, $lie_with = null, $lie_fieldname = null, $lie_clause = null, $lie_display = null, $myfunc = null, $link_file = null) {
        	$cur = count($this->FG_TABLE_COL);

		$this->FG_TABLE_COL[$cur] = array($displayname, $fieldname, $colpercentage, $textalign, $sort, $char_limit, $lie_type, $lie_with, $lie_fieldname , $lie_clause , $lie_display, $myfunc , $link_file);

		$this->FG_NB_TABLE_COL = count($this->FG_TABLE_COL);
	}

    //----------------------------------------------------
    // Method to Add the Field which will be included in the export file
    //----------------------------------------------------
    /*
        Add Field to FG_EXPORT_COL array, Returns Void
        *fieldname is the Field Name which will be included in the export file

    */

    function FieldExportElement($fieldname)
    {
        if(strlen($fieldname)>0)
        {
             $this->FG_EXPORT_FIELD_LIST = $fieldname;
        }
    }


	/**
     * Sets Query fieldnames for the View module
     * @public
	 * @ $col_query	, option to append id ( by default )
     */

	function FieldViewElement ($fieldname,$add_id = 1) {
		$this->FG_COL_QUERY = $fieldname;
		// For each query we need to have the ID at the lenght FG_NB_TABLE_COL
		if ($add_id)	$this->FG_COL_QUERY .= ", ".$this->FG_TABLE_ID;
	}


	function Is_EDITION()
	{
		$this->FG_EDITION = true;
	}

	function Is_DELETION()
	{
		$this->FG_DELETION = true;
	}

	function Is_INFO()
	{
		$this->FG_INFO = true;
	}

	/**
     * Sets the TEXT to display above the records displayed
     * @public   -  @string
     */
	function set_toptext ($text=null)
	{
		if (isset($text)){
			$this->FG_INTRO_TEXT= gettext("You can browse through our ").$text.gettext(" and modify their different properties<br>");
		}else{
			$this->FG_INTRO_TEXT= gettext("You can browse through our ").$this->FG_INSTANCE_NAME.gettext(" and modify their different properties<br>");
		}
	}


	/**
     * Sets the HIDDEN value for the FORM
     * @public   -  @string
     */
	function set_hidden_value ($query_adition_hidden_fields, $query_adition_hidden_value, $query_sql_hidden)
	{
	 	$this->FG_QUERY_ADITION_HIDDEN_FIELDS = $query_adition_hidden_fields;
		$this->FG_QUERY_ADITION_HIDDEN_VALUE = $query_adition_hidden_value;
		$this->FG_QUERY_SQL_HIDDEN = $query_sql_hidden;
	}

	/**
     * Sets the ALT of the button view
     * @public   -  @string
     */
	function set_alttext ($alttext_edit=null, $alttext_delete=null)
	{
		if (isset($alttext_edit)) {
			$this->FG_EDIT_ALT = gettext("Edit this ").$this->FG_INSTANCE_NAME;
		} else {
			$this->FG_EDIT_ALT= $alttext_edit;
		}

		if (isset($alttext_delete)) {
			$this->FG_DELETE_ALT = gettext("Delete this ").$this->FG_INSTANCE_NAME;
		}else{
			$this->FG_DELETE_ALT = $alttext_delete;
		}
	}





	// ----------------------------------------------
    // METHOD FOR THE EDITION
    // ----------------------------------------------

	/**
     * Adds a "element" to the FG_TABLE_EDITION array.  Returns void.
     * @public
	 * @.0 $displayname - name of the column for the current field
	 * @.1 $fieldname - name of the field to edit
	 * @.2 $defaultvalue - value of the field
	 * @.3 $fieldtype - type of edition (INPUT / SELECT / TEXTAREA / RADIOBUTTON/ CHECKBOX/ SUBFORM /...)		##
	 * @.4 $fieldproperty - property of the field (ie: "size=6 maxlength=6")
	 * @.5 $regexpr_nb the regexp number (check set_regular_expression function), used to this is this match with the value introduced
	 * @.6 $error_message - set the error message
	 * @.7 $type_selectfield - if the fieldtype = SELECT, set the type of field feed  (LIST or SQL)
	 * @.8 $feed_selectfield - if the fieldtype = SELECT, [define a sql to feed it] OR [define a array to use]
	 * @.9 $displayformat_selectfield - if the fieldtype = SELECT and fieldname of sql > 1 is useful to define the format to show the data (ie: "%1 : (%2)")
	 * @.10 $config_radiobouttonfield - if the fieldtype = RADIOBUTTON : config format - valuename1 :value1, valuename2 :value2,...  (ie: "Yes :t, - No:f")
	 * @.12 $check_emptyvalue - ("no" or "yes") if "no" we we check the regularexpression only if a value has been entered
	 * @.13 $attach2table - yes
	 * @.14 $attach2table_conf - "doc_tariff:call_tariff_id:call_tariff:webm_retention, id, country_id:id IN (select call_tariff_id from doc_tariff where document_id = %id) AND cttype='PHONE':document_id:%1 - (%3):2:country:label, id:%1:id='%1'"
	 * @.END $comment - set a comment to display below the field
     */

	 /*
	// THE VARIABLE $FG_TABLE_EDITION WOULD DEFINE THE COL THAT WE WANT SHOW IN YOUR EDITION TABLE
	// 0. NAME OF THE COLUMN IN THE HTML PAGE,
	// 1. NAME OF THE FIELD
	// 2. VALUE OF THE FIELD
	// 3. THE TYPE OF THE FIELD (INPUT/SELECT/TEXTAREA)
	// 4. THE PROPERTY OF THIS FIELD
	// 5. REGEXPRES TO CHECK THE VALUE
	//    "^.{3}$": A STRING WITH EXACTLY 3 CHARACTERS.
	//     ^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$  : EMAIL ADRESSE
	// 6. ERROR MESSAGE // Used IF SELECT for ask-add as option with value -1
	// 7.  IF THE FIELD TYPE IS A SELECT,  DEFINE LIST OR SQL
	// 8.  IF SQL,		THE TABLE NAME
	// 9.  IF SQL,		THE FIELDS  : Three MAXIMUM IN ORDER (NAME, VALUE, ...other that we need for the display) ;)
	// 10. IF SQL,		THE CLAUSE
	// 11. IF LIST,		THE NAME OF THE LIST
	// 12. IF LIST,		DISPLAY : %1 : (%2) ; IF SELECT , show the content of that field
	// 13. CHECK_EMPTYVALUE - ("no" or "yes") if "no" we we check the regularexpression only if a value has been entered - if NO-NULL, if the value is
	// 	 					  not entered the field will not be include in the update/addition query
	// 14. COMMENT ( that is not included in FG_TABLE_EDITION or FG_TABLE_ADITION )
	// 15. SQL CUSTOM QUERY : customer SQL   or   function to display the edit input
	// 16. DISPLAYINPUT_DEFAULTSELECT : IF INPUT : FUNCTION TO DISPLAY THE VALUE OF THE FIELD ; IF SELECT IT WILL DISPLAY THE OPTION PER DEFAUTL, ie:
	//									'<OPTION  value="-1" selected>NOT DEFINED</OPTION>'
	// 17. COMMENT ABOVE : this will insert a comment line above the edition line, useful to separate section and to provide some detailed instruction
	 */

	function AddEditElement($displayname, $fieldname, $defaultvalue, $fieldtype, $fieldproperty, $regexpr_nb, $error_message, $type_selectfield,
		$lie_tablename, $lie_tablefield, $lie_clause, $listname, $displayformat_selectfield, $check_emptyvalue , $comment, $custom_query = null,
		$displayinput_defaultselect = null, $comment_above = null, $field_enabled = true){
		if( strtoupper($fieldtype)=="LABEL" && (strtoupper($_GET['form_action']) == "EDIT" ||strtoupper($_POST['form_action'])== "EDIT") ){
		 return;
		}
		if($field_enabled==true)
		{
			$cur = count($this->FG_TABLE_EDITION);
			$this->FG_TABLE_EDITION[$cur] = array ( $displayname, $fieldname, $defaultvalue, $fieldtype, $fieldproperty, $regexpr_nb, $error_message,
							$type_selectfield, $lie_tablename, $lie_tablefield, $lie_clause, $listname, $displayformat_selectfield, $check_emptyvalue,
							$custom_query, $displayinput_defaultselect, $comment_above);
			$this->FG_TABLE_COMMENT[$cur] = $comment;
			$this->FG_TABLE_ADITION[$cur] = $this->FG_TABLE_EDITION[$cur];
			$this->FG_NB_TABLE_ADITION = $this->FG_NB_TABLE_EDITION = count($this->FG_TABLE_EDITION);
		}
	}

	/**
     * Sets Search form fieldnames for the view module
     * @public
	 * @ $displayname , $fieldname, $fieldvar
     */
	function AddSearchElement_C1($displayname, $fieldname, $fieldvar)
	{
		$cur = count($this->FG_FILTER_SEARCH_FORM_1C);
		$this->FG_FILTER_SEARCH_FORM_1C[$cur] = array($displayname, $fieldname, $fieldvar);
	}

	function AddSearchElement_C2($displayname, $fieldname1 , $fielvar1 , $fieldname2 , $fielvar2, $sqlfield)
	{
		$cur = count($this->FG_FILTER_SEARCH_FORM_2C);
		$this->FG_FILTER_SEARCH_FORM_2C[$cur] = array($displayname, $fieldname1 , $fielvar1 , $fieldname2 , $fielvar2, $sqlfield);
	}

	/**
     * Sets Search form select rows for the view module
     * @public
	 * @ $displayname , SQL or array to fill select and the name of select box
     */
	function AddSearchElement_Select($displayname, $table = null, $fields = null, $clause = null,
			$order = null ,$sens = null , $select_name, $sql_type = 1, $array_content = null,$search_table=null){

		$cur = count($this->FG_FILTER_SEARCH_FORM_SELECT);

		if ($sql_type) {
			$sql = array($table, $fields, $clause, $order ,$sens);
			$this->FG_FILTER_SEARCH_FORM_SELECT[$cur] = array($displayname, $sql, $select_name,null,$search_table);
		} else {
			$this->FG_FILTER_SEARCH_FORM_SELECT[$cur] = array($displayname, 0, $select_name, $array_content,null);
		}
	}


	/**
     * Sets Query fieldnames for the Edit/ADD module
     * @public
	 * @ $col_query
     */
	function FieldEditElement ($fieldname)
	{
		if($this->FG_DISPLAY_SELECT == true)
		{
			if(strlen($this->FG_SELECT_FIELDNAME)>0)
			{
				$fieldname.= ", ".$this->FG_SELECT_FIELDNAME;
			}
		}
		$this->FG_QUERY_EDITION = $fieldname;
		$this->FG_QUERY_ADITION = $fieldname;
	}


	function set_regular_expression()
	{
		// 0.  A STRING WITH EXACTLY 3 CHARACTERS.
		$this -> FG_regular[]  = array(	"^.{3}",
						gettext("(at least 3 characters)"));

		// 1.  EMAIL ADRESSE
		$this -> FG_regular[]  = array(	"^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$",
						gettext("(must match email structure. Example : name@domain.com)"));

		// 2 . IF AT LEAST FIVE SUCCESSIVE CHARACTERS APPEAR AT THE END OF THE STRING.
		$this -> FG_regular[]  = array(	".{5}$",
						gettext("(at least 5 successive characters appear at the end of this string)"));

		// 3. IF AT LEAST 4 CHARACTERS
		$this -> FG_regular[]  = array(	".{4}",
						gettext("(at least 4 characters)"));

		// 4
		$this -> FG_regular[]  = array(	"^[0-9]+$",
						gettext("(number format)"));

		// 5
		$this -> FG_regular[]  = array(	"^([0-9]{4})[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$",
						"(YYYY-MM-DD)");

		// 6
		$this -> FG_regular[]  = array(	"^[0-9]{8,}$"	,
						gettext("(only number with more that 8 digits)"));

		// 7
		$this -> FG_regular[]  = array(	"^[0-9][ .0-9\/\-]{6,}[0-9]$"	,
						gettext("(at least 8 digits using . or - or the space key)"));

		// 8
		$this -> FG_regular[]  = array(	".{5}",
						gettext("network adress format"));

		// 9
		$this -> FG_regular[]  = array(	"^.{1}",
						gettext("at least 1 character"));

		// 10
		$this -> FG_regular[]  = array(    "^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$"   ,
		                        "(YYYY-MM-DD HH:MM:SS)");

		// 11
		$this -> FG_regular[]  = array(    "^.{2}",
					gettext("(AT LEAST 2 CARACTERS)"));

		// 12
		$this -> FG_regular[]  = array(    "^(-){0,1}[0-9]+(\.){0,1}[0-9]*$"       ,
					gettext("(NUMBER FORMAT WITH/WITHOUT DECIMAL, use '.' for decimal)"));

		// 13  - RATECARD
		$this -> FG_regular[]  = array(    "^(defaultprefix|[-,0-9]+|_[-[.[.][.].]0-9XZN(){}|.,_]+)$",
					"(NUMBER FORMAT OR 'defaultprefix' OR ASTERISK/POSIX REGEX FORMAT)");

		// 14  - DNID PREFIX FOR RATECARD
		$this -> FG_regular[]  = array(    "^(all|[0-9]+)$",
		                        "(NUMBER FORMAT OR 'all')");

		// 15 - RATECARD TIME
		$this -> FG_regular[]  = array(    "^([0-9]{2}):([0-9]{2})$"   ,
		                        "(HH:MM)");

		// 16  TEXT > 15 caract
		$this -> FG_regular[]  = array(	".{15}",
						gettext("You must write something."));

		// 17  TEXT > 15 caract
		$this -> FG_regular[]  = array(	".{8}",
						gettext("8 characters alphanumeric"));

		// 18 - CALLERID - PhoneNumber
		$this -> FG_regular[]  = array(    "^(\+|[0-9]{1})[0-9]+$"   ,
		                        "Phone Number format");
		// 19 - CAPTCHAIMAGE - Alpahnumeric
		$this -> FG_regular[]  = array("^(".strtoupper($_SESSION["captcha_code"]).")|(".strtolower($_SESSION["captcha_code"]).")$",
						gettext("(at least 6 Alphanumeric characters)"));
		//20 TIME
		$this -> FG_regular[]  = array(    "^([0-9]{2}):([0-9]{2}):([0-9]{2})$"   ,
		                        "(HH:MM:SS)");
						// check_select
		// TO check if a select have a value different -1
		// 21 -> Check percent more of 0 and under 100
		$this -> FG_regular[]  = array( "^100$|^(([0-9]){0,2})((\.)([0-9]*))?$"  ,
					gettext("(PERCENT FORMAT WITH/WITHOUT DECIMAL, use '.' for decimal and don't use '%' character. e.g.: 12.4 )"));

	}


	// ----------------------------------------------
    // FUNCTION FOR THE FORM
    // ----------------------------------------------

	function do_field_duration($sql,$fld, $fldsql)
	{
  		$processed = $this->getProcessed();

  		$fldtype = $fld.'type';

  		if (isset($processed[$fld]) && ($processed[$fld]!='')){
            if (strpos($sql,'WHERE') > 0){
                $sql = "$sql AND ";
            }else{
                $sql = "$sql WHERE ";
            }
			$sql = "$sql $fldsql";
			if (isset ($processed[$fldtype])){
                switch ($processed[$fldtype]) {
					case 1:	$sql = "$sql ='".$processed[$fld]."'";  break;
					case 2: $sql = "$sql <= '".$processed[$fld]."'";  break;
					case 3: $sql = "$sql < '".$processed[$fld]."'";  break;
					case 4: $sql = "$sql > '".$processed[$fld]."'";  break;
					case 5: $sql = "$sql >= '".$processed[$fld]."'";  break;
				}
            }else{ $sql = "$sql = '".$processed[$fld]."'"; }
		}
		return $sql;
	}

	function do_field($sql,$fld, $simple=0,$processed=null,$search_table=null)
	{
  		$fldtype = $fld.'type';
        if (empty($processed)) {
        	$processed = $this->getProcessed();
        }

        if (isset($processed[$fld]) && ($processed[$fld]!='')){
			if (strpos($sql,'WHERE') > 0){
                $sql = "$sql AND ";
            }else{
                $sql = "$sql WHERE ";
            }
            if(empty($search_table)){
				$sql = "$sql $fld";
            }else{
            	$sql = "$sql $search_table.$fld";
            }
	        if (DB_TYPE == "postgres"){
		 		$LIKE = "ILIKE";
		 		$CONVERT ="";
			}else{
				$LIKE = "LIKE";
				$CONVERT =" COLLATE utf8_unicode_ci";
			}

			if ($simple==0){
                if (isset ($processed[$fldtype])){
					switch ($processed[$fldtype]) {
						case 1:	$sql = "$sql='".$processed[$fld]."'";  break;
						case 2: $sql = "$sql $LIKE '".$processed[$fld]."%'".$CONVERT;  break;
						case 3: $sql = "$sql $LIKE '%".$processed[$fld]."%'".$CONVERT;  break;
						case 4: $sql = "$sql $LIKE '%".$processed[$fld]."'".$CONVERT;
					}
				}else{
					$sql = "$sql $LIKE '%".$processed[$fld]."%'".$CONVERT;
				}
			}else{
				$sql = "$sql ='".$processed[$fld]."'";
			}
		}
		return $sql;
	}

	/**
     * Function to execture the appropriate action
     * @public
     */
	function perform_action (&$form_action){
		//security check

		switch ($form_action) {
			case "ask-add":
			case "add":
			   if(!$this->FG_ADDITION){
			   		Header ("Location: ". filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL));
			   		die();
			   	}
			   break;
			case "ask-edit":
			case "edit":
				 if(!$this->FG_EDITION){
			   		Header ("Location: ". filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL));
			   		die();
			   	}
			   break;
			case "ask-del-confirm":
			case "ask-delete":
			case "delete":
			   if(!$this->FG_DELETION){
			   		Header ("Location: ". filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL));
			   		die();
			   }
			   break;
		}
		switch ($form_action) {
			case "add":
			   $this -> perform_add($form_action);
			   break;
			case "edit":
			   $this -> perform_edit($form_action);
			   break;
			case "delete":
			   $this -> perform_delete($form_action);
			   break;
		}

		$processed = $this->getProcessed();  //$processed['firstname']

		if ($form_action == "ask-delete" && in_array($processed['id'],$this->FG_DELETION_FORBIDDEN_ID) ){
			if(!empty($this->FG_GO_LINK_AFTER_ACTION_DELETE)){
				Header ("Location: ".$this->FG_GO_LINK_AFTER_ACTION_DELETE.$processed['id']);
			}else{
				Header ("Location: ". filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL));
			}
			die();
		}

		if ( $form_action == "list" || $form_action == "edit" || $form_action == "ask-delete" ||
			 $form_action == "ask-edit" || $form_action == "add-content" || $form_action == "del-content" || $form_action == "ask-del-confirm") {
			include_once (FSROOT."lib/Class.Table.php");

			$this->FG_ORDER = $processed['order'];
			$this->FG_SENS = $processed['sens'];
			$this -> CV_CURRENT_PAGE = $processed['current_page'];

			$session_limit = $this->FG_TABLE_NAME."-displaylimit";
			if (isset($_SESSION[$session_limit]) && is_numeric($_SESSION[$session_limit])){
				$this -> FG_LIMITE_DISPLAY = $_SESSION[$session_limit];
			}

            /* Add CSRF protection */
            if ($this->FG_CSRF_STATUS == true) {
                switch ($form_action) {
                    case 'add':
                    case 'edit':
                    case 'delete':
                        if ($this->_processed[$this->FG_CSRF_RECEIVED_FIELD] != $this->FG_CSRF_RECEIVED_TOKEN) {
                            Header ("Location: ". filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL));
                            die();
                        }
                    break;
                }
            }

			if (isset($processed['mydisplaylimit']) && (is_numeric($processed['mydisplaylimit']) || ($processed['mydisplaylimit']=='ALL'))){
				if ($processed['mydisplaylimit']=='ALL'){
					$this -> FG_LIMITE_DISPLAY = 5000;
				}else{
					$this -> FG_LIMITE_DISPLAY = $processed['mydisplaylimit'];
				}
				   $_SESSION[$this->FG_TABLE_NAME."-displaylimit"]=$this -> FG_LIMITE_DISPLAY;
			}

			if ( $this->FG_ORDER == "" || $this->FG_SENS == "" ) {
				$this->FG_ORDER = $this -> FG_TABLE_DEFAULT_ORDER;
				$this->FG_SENS  = $this -> FG_TABLE_DEFAULT_SENS;
			}

			if ( $form_action == "list" ) {
				$sql_calc_found_rows = '';
				if (DB_TYPE != "postgres") {
					$sql_calc_found_rows = 'SQL_CALC_FOUND_ROWS';
				}
				$instance_table = new Table($this -> FG_TABLE_NAME, "$sql_calc_found_rows ".$this -> FG_COL_QUERY, null, null, null, true, $this -> FG_TABLE_NAME_COUNT);

				$this->prepare_list_subselection($form_action);

				// Code here to call the Delete Selected items Fucntion
				if (isset($processed['deleteselected'])) {
					$this -> Delete_Selected();
				}

				if ($this->FG_DEBUG >= 2) {
					echo "FG_CLAUSE:$this->FG_CLAUSE";
					echo "FG_ORDER = ".$this->FG_ORDER."<br>";
					echo "FG_SENS = ".$this->FG_SENS."<br>";
					echo "FG_LIMITE_DISPLAY = ".$this -> FG_LIMITE_DISPLAY."<br>";
					echo "CV_CURRENT_PAGE = ".$this -> CV_CURRENT_PAGE."<br>";
				}

				$list = $instance_table -> Get_list ($this -> DBHandle, $this -> FG_TABLE_CLAUSE, $this->FG_ORDER, $this->FG_SENS, null, null,
													 $this -> FG_LIMITE_DISPLAY, $this -> CV_CURRENT_PAGE * $this -> FG_LIMITE_DISPLAY, $this -> SQL_GROUP);
				if ($this->FG_DEBUG == 3) echo "<br>Clause : ".$this -> FG_TABLE_CLAUSE;
				if (DB_TYPE == "postgres") {
					$this -> FG_NB_RECORD = $instance_table -> Table_count ($this -> DBHandle, $this -> FG_TABLE_CLAUSE);
				} else {
					$res_count = $instance_table -> SQLExec($this->DBHandle, "SELECT FOUND_ROWS() as count", 1);
					$this -> FG_NB_RECORD = $res_count[0][0];
				}

				if ($this->FG_DEBUG >= 1) var_dump ($list);

				if ($this -> FG_NB_RECORD <=$this -> FG_LIMITE_DISPLAY){
					$this -> FG_NB_RECORD_MAX = 1;
				}else{
					$this -> FG_NB_RECORD_MAX = ceil($this -> FG_NB_RECORD / $this -> FG_LIMITE_DISPLAY);
				}

				if ($this->FG_DEBUG == 3) echo "<br>Nb_record : ".$this -> FG_NB_RECORD ;
				if ($this->FG_DEBUG == 3) echo "<br>Nb_record_max : ".$this -> FG_NB_RECORD_MAX ;

			} else {

				$instance_table = new Table($this->FG_TABLE_NAME, $this->FG_QUERY_EDITION);
				$list = $instance_table -> Get_list ($this->DBHandle, $this->FG_EDITION_CLAUSE, null, null, null, null, 1, 0);

				//PATCH TO CLEAN THE IMPORT OF PASSWORD FROM THE DATABASE
				if ( substr_count($this->FG_QUERY_EDITION,"pwd_encoded")>0 ) {
					$tab_field = explode(',',  $this->FG_QUERY_EDITION ) ;
					for ($i=0;$i< count($tab_field);$i++){
						if(trim($tab_field[$i])=="pwd_encoded") {
							$list[0][$i]="";
						}
					}
				}

				if (isset($list[0]["pwd_encoded"])) {
					$list[0]["pwd_encoded"] = "";
				}
			}

			if ($this->FG_DEBUG >= 2) print_r ($list);
		}

		return $list;

	}

	/**
     * Function to prepare the clause from the session filter
     * @public
     */
	function prepare_list_subselection($form_action)
	{

		$processed = $this->getProcessed();

		if ( $form_action == "list" && $this->FG_FILTER_SEARCH_FORM) {

			if (isset($processed['cancelsearch']) && ($processed['cancelsearch'] == true)){
				$_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME] = '';
			}

			// RETRIEVE THE CONTENT OF THE SEARCH SESSION AND
			if (strlen($_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME])>5 && ($processed['posted_search'] != 1 )){
				$element_arr = preg_split("/\|/", $_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME]);
				foreach ($element_arr as $val_element_arr){
					$pos = strpos($val_element_arr, '=');
					if ($pos !== false) {
						$entity_name = substr($val_element_arr,0,$pos);
						$entity_value = substr($val_element_arr,$pos+1);
						$this->_processed[$entity_name]=$entity_value;
					}
				}
			}

			if (($processed['posted_search'] != 1 && isset($_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME]) && strlen($_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME])>10 )){
				$arr_session_var = preg_split("/\|/", $_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME]);
				foreach ($arr_session_var as $arr_val){
					list($namevar,$valuevar) = preg_split("/=/", $arr_val);
					$this->_processed[$namevar]=$valuevar;
					$processed[$namevar]=$valuevar;
					$_POST[$namevar]=$valuevar;
				}
				$processed['posted_search'] = 1;
			}

			// Search Form On
			if (($processed['posted_search'] == 1 )) {

				$this->_processed[fromstatsday_sday] = normalize_day_of_month($processed[fromstatsday_sday], $processed[fromstatsmonth_sday],1);
				$this->_processed[tostatsday_sday] = normalize_day_of_month($processed[tostatsday_sday], $processed[tostatsmonth_sday],1);
				$this->_processed[fromstatsday_sday_bis] = normalize_day_of_month($processed[fromstatsday_sday_bis], $processed[fromstatsmonth_sday_bis],1);
				$this->_processed[tostatsday_sday_bis] = normalize_day_of_month($processed[tostatsday_sday_bis], $processed[tostatsmonth_sday_bis],1);

				$SQLcmd = '';

				$search_parameters = "Period=$processed[Period]|frommonth=$processed[frommonth]|fromstatsmonth=$processed[fromstatsmonth]|tomonth=$processed[tomonth]";
				$search_parameters .= "|tostatsmonth=$processed[tostatsmonth]|fromday=$processed[fromday]|fromstatsday_sday=$processed[fromstatsday_sday]";
				$search_parameters .= "|fromstatsmonth_sday=$processed[fromstatsmonth_sday]|today=$processed[today]|tostatsday_sday=$processed[tostatsday_sday]";
				$search_parameters .= "|tostatsmonth_sday=$processed[tostatsmonth_sday]";
				$search_parameters .= "|Period_bis=$processed[Period_bis]|frommonth_bis=$processed[frommonth_bis]|fromstatsmonth_bis=$processed[fromstatsmonth_bis]|tomonth_bis=$processed[tomonth_bis]";
				$search_parameters .= "|tostatsmonth_bis=$processed[tostatsmonth_bis]|fromday_bis=$processed[fromday_bis]|fromstatsday_sday_bis=$processed[fromstatsday_sday_bis]";
				$search_parameters .= "|fromstatsmonth_sday_bis=$processed[fromstatsmonth_sday_bis]|today_bis=$processed[today_bis]|tostatsday_sday_bis=$processed[tostatsday_sday_bis]";
				$search_parameters .= "|tostatsmonth_sday_bis=$processed[tostatsmonth_sday_bis]";

				foreach ($this->FG_FILTER_SEARCH_FORM_1C as $r){
					$search_parameters .= "|$r[1]=".$processed[$r[1]]."|$r[2]=".$processed[$r[2]];
					$SQLcmd = $this->do_field($SQLcmd, $r[1], 0, $processed);
				}

				foreach ($this->FG_FILTER_SEARCH_FORM_2C as $r){
					$search_parameters .= "|$r[1]=".$processed[$r[1]]."|$r[2]=".$processed[$r[2]];
					$search_parameters .= "|$r[3]=".$processed[$r[3]]."|$r[4]=".$processed[$r[4]];
					$SQLcmd = $this->do_field_duration($SQLcmd, $r[1], $r[5]);
					$SQLcmd = $this->do_field_duration($SQLcmd, $r[3], $r[5]);
				}

				foreach ($this->FG_FILTER_SEARCH_FORM_SELECT as $r){
					$search_parameters .= "|$r[2]=".$processed[$r[2]];
					$SQLcmd = $this->do_field($SQLcmd, $r[2], 1, null, $r[4]);
				}

				$_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME] = $search_parameters;

				$date_clause = '';

				if ($processed[fromday] && isset($processed[fromstatsday_sday]) && isset($processed[fromstatsmonth_sday]))
					$date_clause.=" AND ".$this->FG_FILTER_SEARCH_1_TIME_FIELD." >= TIMESTAMP('$processed[fromstatsmonth_sday]-$processed[fromstatsday_sday]')";
				if ($processed[today] && isset($processed[tostatsday_sday]) && isset($processed[tostatsmonth_sday]))
					$date_clause.=" AND ".$this->FG_FILTER_SEARCH_1_TIME_FIELD." <= TIMESTAMP('$processed[tostatsmonth_sday]-".sprintf("%02d",intval($processed[tostatsday_sday])/*+1*/)." 23:59:59')";


				if ($processed[Period]=="month_older_rad"){
					$from_month = $processed[month_earlier];
					$date_clause .= " AND DATE_SUB(NOW(),INTERVAL $from_month MONTH) > ".$this->FG_FILTER_SEARCH_3_TIME_FIELD."";
				}

				//BIS FIELD
				if ($processed[fromday_bis] && isset($processed[fromstatsday_sday_bis]) && isset($processed[fromstatsmonth_sday_bis]))
					$date_clause.=" AND ".$this->FG_FILTER_SEARCH_1_TIME_FIELD_BIS." >= TIMESTAMP('$processed[fromstatsmonth_sday_bis]-$processed[fromstatsday_sday_bis]')";
				if ($processed[today_bis] && isset($processed[tostatsday_sday_bis]) && isset($processed[tostatsmonth_sday_bis]))
					$date_clause.=" AND ".$this->FG_FILTER_SEARCH_1_TIME_FIELD_BIS." <= TIMESTAMP('$processed[tostatsmonth_sday_bis]-".sprintf("%02d",intval($processed[tostatsday_sday_bis])/*+1*/)." 23:59:59')";


				if ($processed[Period_bis]=="month_older_rad") {
					$from_month = $processed[month_earlier_bis];
					$date_clause .= " AND DATE_SUB(NOW(),INTERVAL $from_month MONTH) > ".$this->FG_FILTER_SEARCH_3_TIME_FIELD_BIS."";
				}


				if (strpos($SQLcmd, 'WHERE') > 0) {
					if (strlen($this->FG_TABLE_CLAUSE)>0) $this->FG_TABLE_CLAUSE .=" AND ";
					$this -> FG_TABLE_CLAUSE .= substr($SQLcmd,6).$date_clause;
				}elseif (strpos($date_clause, 'AND') > 0){
					if (strlen($this->FG_TABLE_CLAUSE)>0) $this->FG_TABLE_CLAUSE .=" AND ";
					$this -> FG_TABLE_CLAUSE .= substr($date_clause,5);
				}
			}
		}
	}

    /****************************************
    Function to delete all pre selected records,
    This Function Gets the selected records and delete them from DB
    ******************************************/
    function Delete_Selected()
    {
        //if ( $form_action == "list" && $this->FG_FILTER_SEARCH_FORM)
        {
            $instance_table = new Table($this -> FG_TABLE_NAME, $this -> FG_COL_QUERY);
			$result = $instance_table -> Delete_Selected ($this -> DBHandle, $this -> FG_TABLE_CLAUSE, $this->FG_ORDER, $this->FG_SENS, null, null,
			                        					  $this -> FG_LIMITE_DISPLAY, $this -> CV_CURRENT_PAGE * $this -> FG_LIMITE_DISPLAY, $this -> SQL_GROUP);
        }
    }

	/**
     * Function to perform the add action after inserting all data in required fields
     * @public
     */
	function perform_add (&$form_action)
	{
		include_once (FSROOT."lib/Class.Table.php");

		$processed = $this->getProcessed();  //$processed['firstname']
		$this->VALID_SQL_REG_EXP = true;

		for($i=0; $i < $this->FG_NB_TABLE_ADITION; $i++) {

			$pos = strpos($this->FG_TABLE_ADITION[$i][14], ":"); // SQL CUSTOM QUERY
			$pos_mul = strpos($this->FG_TABLE_ADITION[$i][4], "multiple");

			if (!$pos) {

				$fields_name = $this->FG_TABLE_ADITION[$i][1];
				$regexp = $this->FG_TABLE_ADITION[$i][5];

				// FIND THE MULTIPLE SELECT
				if ($pos_mul && is_array($processed[$fields_name])) {
					$total_mult_select=0;
					foreach ($processed[$fields_name] as $value) {
							$total_mult_select += $value;
					}

					if ($this->FG_DEBUG == 1) echo "<br>$fields_name : ".$total_mult_select;

					if ($i>0) $param_add_fields .= ", ";
					$param_add_fields .= $fields_name;
					if ($i>0) $param_add_value .= ", ";
					$param_add_value .= "'".addslashes(trim($total_mult_select))."'";

				} else {
					// NO MULTIPLE SELECT

					// CHECK ACCORDING TO THE REGULAR EXPRESSION DEFINED
					if (is_numeric($regexp) && !(strtoupper(substr($this->FG_TABLE_ADITION[$i][13],0,2))=="NO" && $processed[$fields_name]=="")) {
						$this-> FG_fit_expression[$i] = preg_match ('/'.$this->FG_regular[$regexp][0].'/' , $processed[$fields_name]);
						if ($this->FG_DEBUG == 1)  echo "<br>->  $fields_name => ".$this->FG_regular[$regexp][0]." , ".$processed[$fields_name];
						if (!$this-> FG_fit_expression[$i]){
							$this->VALID_SQL_REG_EXP = false;
							$form_action="ask-add";
						}
					} elseif ($regexp == "check_select") {
						// FOR SELECT FIELD WE HAVE THE check_select THAT WILL ENSURE WE DEFINE A VALUE FOR THE SELECTABLE FIELD
						if ($processed[$fields_name]==-1) {
							$this-> FG_fit_expression[$i] = false;
							$this->VALID_SQL_REG_EXP = false;
							$form_action="ask-add";
						}
					}
					// CHECK IF THIS IS A SPLITABLE FIELD LIKE 012-014 OR 15;16;17
				 	if ($fields_name == $this -> FG_SPLITABLE_FIELD && substr($processed[$fields_name],0,1) != '_' ) {
				 		$splitable_value = $processed[$fields_name];
						$arr_splitable_value = explode(",", $splitable_value);
						foreach ($arr_splitable_value as $arr_value) {
							$arr_value = trim ($arr_value);
							$arr_value_explode = explode("-", $arr_value,2);
							if (count($arr_value_explode)>1) {
								if (is_numeric($arr_value_explode[0]) && is_numeric($arr_value_explode[1]) && $arr_value_explode[0] < $arr_value_explode[1] ){
									$kk=strlen($arr_value_explode[0])-strlen(ltrim($arr_value_explode[0],'0'));
									$prefix=substr($arr_value_explode[0],0,$kk);
									for ($kk=$arr_value_explode[0];$kk<=$arr_value_explode[1];$kk++) {
										$arr_value_to_import[] = $prefix.$kk;
									}
								} elseif (is_numeric($arr_value_explode[0])) {
									$arr_value_to_import[] = $arr_value_explode[0];
								} elseif (is_numeric($arr_value_explode[1])) {
									$arr_value_to_import[] = $arr_value_explode[1];
								}
							} else {
								$arr_value_to_import[] = $arr_value_explode[0];
							}
						}

						if (!is_null($processed[$fields_name]) && ($processed[$fields_name]!="") && ($this->FG_TABLE_ADITION[$i][4]!="disabled") ){
							if ($i>0) $param_add_fields .= ", ";
							$param_add_fields .= str_replace('myfrom_', '', $fields_name);
							if ($i>0) $param_add_value .= ", ";
							$param_add_value .= "'%TAGPREFIX%'";
						}
					} else {
						if ($this->FG_DEBUG == 1) echo "<br>$fields_name : ".$processed[$fields_name];
						if (!is_null($processed[$fields_name]) && ($processed[$fields_name]!="") && ($this->FG_TABLE_ADITION[$i][4]!="disabled") ){
							if (strtoupper ($this->FG_TABLE_ADITION[$i][3]) != strtoupper("CAPTCHAIMAGE"))
							{
								if ($i>0) $param_add_fields .= ", ";
									$param_add_fields .= str_replace('myfrom_', '', $fields_name);
								if ($i>0) $param_add_value .= ", ";
									$param_add_value .= "'".addslashes(trim($processed[$fields_name]))."'";
							}
						}
					}
				}
			}
		}

		if (!is_null($this->FG_QUERY_ADITION_HIDDEN_FIELDS) && $this->FG_QUERY_ADITION_HIDDEN_FIELDS!=""){
			if ($i>0) $param_add_fields .= ", ";
			$param_add_fields .= $this->FG_QUERY_ADITION_HIDDEN_FIELDS;
			if ($i>0) $param_add_value .= ", ";
			$split_hidden_fields_value = preg_split("/,/",trim($this->FG_QUERY_ADITION_HIDDEN_VALUE));
			for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields_value);$cur_hidden++){
				$param_add_value .= "'".trim($split_hidden_fields_value[$cur_hidden])."'" ;
				if($cur_hidden<count($split_hidden_fields_value)-1)$param_add_value.=",";
			}
		}

		if ($this->FG_DEBUG == 1)  echo "<br><hr> $param_add_fields";
		if ($this->FG_DEBUG == 1)  echo "<br><hr> $param_add_value";

		$res_funct = true;

		// CALL DEFINED FUNCTION BEFORE THE ADDITION

		if (strlen($this->FG_ADDITIONAL_FUNCTION_BEFORE_ADD)>0 && ($this->VALID_SQL_REG_EXP))
				$res_funct = call_user_func(array('FormBO', $this->FG_ADDITIONAL_FUNCTION_BEFORE_ADD));

		if ($res_funct) {

			$instance_table = new Table($this->FG_TABLE_NAME, $param_add_fields);
			// CHECK IF WE HAD FOUND A SPLITABLE FIELD THEN WE MIGHT HAVE %TAGPREFIX%
			if (strpos($param_add_value, '%TAGPREFIX%')) {
				foreach ($arr_value_to_import as $current_value) {
					$param_add_value_replaced = str_replace("%TAGPREFIX%", $current_value, $param_add_value);
					if ($this->VALID_SQL_REG_EXP) $this -> RESULT_QUERY = $instance_table -> Add_table ($this->DBHandle, $param_add_value_replaced, null, null, $this->FG_TABLE_ID);
				}
			} else {
				if ($this->VALID_SQL_REG_EXP) $this -> RESULT_QUERY = $instance_table -> Add_table ($this->DBHandle, $param_add_value, null, null, $this->FG_TABLE_ID);
			}
			if($this -> FG_ENABLE_LOG == 1) {
				$this -> logger -> insertLog_Add($_SESSION["admin_id"], 2, "NEW ".strtoupper($this->FG_INSTANCE_NAME)." CREATED" , "User added a new record in database", $this->FG_TABLE_NAME, $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], $param_add_fields, $param_add_value);
			}
			// CALL DEFINED FUNCTION AFTER THE ACTION ADDITION
			if (strlen($this->FG_ADDITIONAL_FUNCTION_AFTER_ADD)>0 && ($this->VALID_SQL_REG_EXP))
				$res_funct = call_user_func(array('FormBO', $this->FG_ADDITIONAL_FUNCTION_AFTER_ADD));
			if ($this->FG_ADITION_GO_EDITION == "yes"){
				$form_action="ask-edit";
				$this->FG_ADITION_GO_EDITION = "yes-done";
			}
			$id = $this -> RESULT_QUERY;
			if ( !empty($id) && ($this->VALID_SQL_REG_EXP) && (isset($this->FG_GO_LINK_AFTER_ACTION_ADD))){
				if ($this->FG_DEBUG == 1)  echo "<br> GOTO ; ".$this->FG_GO_LINK_AFTER_ACTION_ADD.$id;
				//echo "<br> GOTO ; ".$this->FG_GO_LINK_AFTER_ACTION_ADD.$id;
				Header ("Location: ".$this->FG_GO_LINK_AFTER_ACTION_ADD.$id);
			}
		}
	}



	/**
     * Function to edit the fields
     * @public
     */
	function perform_edit (&$form_action)
	{
		include_once (FSROOT."lib/Class.Table.php");

		$processed = $this->getProcessed();  //$processed['firstname']

		$this->VALID_SQL_REG_EXP = true;

		$instance_table = new Table($this->FG_TABLE_NAME, $this->FG_QUERY_EDITION);

		if ($processed['id']!="" || !is_null($processed['id'])){
			$this->FG_EDITION_CLAUSE = str_replace("%id", $processed['id'], $this->FG_EDITION_CLAUSE);
		}

		for($i=0;$i<$this->FG_NB_TABLE_EDITION;$i++) {

			$pos = strpos($this->FG_TABLE_EDITION[$i][14], ":"); // SQL CUSTOM QUERY
			$pos_mul = strpos($this->FG_TABLE_EDITION[$i][4], "multiple");
			if (!$pos){
				$fields_name = $this->FG_TABLE_EDITION[$i][1];
				$regexp = $this->FG_TABLE_EDITION[$i][5];

				if ($pos_mul && is_array($processed[$fields_name])){
					$total_mult_select=0;
					foreach ($processed[$fields_name] as $value){
						$total_mult_select += $value;
					}
					if ($this->FG_DEBUG == 1) echo "<br>$fields_name : ".$total_mult_select;
					if ($i>0) $param_update .= ", ";
					$param_update .= "$fields_name = '".addslashes(trim($total_mult_select))."'";
				} else {
					if (is_numeric($regexp) && !(strtoupper(substr($this->FG_TABLE_ADITION[$i][13],0,2))=="NO" && $processed[$fields_name]=="") ) {
						$this-> FG_fit_expression[$i] = preg_match ('/'.$this->FG_regular[$regexp][0].'/' , $processed[$fields_name]);
						if ($this->FG_DEBUG == 1)  echo "<br>-> $i)  ".$this->FG_regular[$regexp][0]." , ".$processed[$fields_name];
						if (!$this-> FG_fit_expression[$i]){
							$this->VALID_SQL_REG_EXP = false;
							if ($this->FG_DEBUG == 1)  echo "<br>-> $i) Error Match";
							$form_action="ask-edit";
						}
					}

					if ($this->FG_DEBUG == 1) echo "<br>$fields_name : ".$processed[$fields_name];
					if ($i>0 && $this->FG_TABLE_EDITION[$i][3]!= "SPAN") $param_update .= ", ";
					if (empty($processed[$fields_name]) && strtoupper(substr($this->FG_TABLE_ADITION[$i][13],3,4))=="NULL"){
						$param_update .= $fields_name." = NULL ";
					} else {
						if($this->FG_TABLE_EDITION[$i][3]!= "SPAN") {
							$param_update .= $fields_name." = '".addslashes(trim($processed[$fields_name]))."' ";
						}
					}
				}

			} else {
				if (strtoupper ($this->FG_TABLE_EDITION[$i][3])==strtoupper ("CHECKBOX")) {
					$table_split = preg_split("/:/",$this->FG_TABLE_EDITION[$i][1]);
					$checkbox_data = $table_split[0];	//doc_tariff
					$instance_sub_table = new Table($table_split[0], $table_split[1].", ".$table_split[5]);
					$SPLIT_FG_DELETE_CLAUSE = $table_split[5]."='".trim($processed['id'])."'";
					$instance_sub_table -> Delete_table ($this -> DBHandle, $SPLIT_FG_DELETE_CLAUSE, $func_table = null);

					if (!is_array($processed[$checkbox_data])) {
						$snum=0;
						$this -> VALID_SQL_REG_EXP = false;
						$this-> FG_fit_expression[$i] = false;
					} else {
						$snum = count($processed[$checkbox_data]);
					}

					$checkbox_data_tab = $processed[$checkbox_data];
					for($j=0;$j<$snum;$j++){
						$this -> RESULT_QUERY = $instance_sub_table -> Add_table ($this-> DBHandle, "'".addslashes(trim($checkbox_data_tab[$j]))."', '".addslashes(trim($processed['id']))."'", null, null);
						if (!$this -> RESULT_QUERY) {
							$findme   = 'duplicate';
							$pos_find = strpos($instance_sub_table -> errstr, $findme);

							// Note our use of ===.  Simply == would not work as expected
							// because the position of 'a' was the 0th (first) character.
							if ($pos_find === false) {
								echo $instance_sub_table -> errstr;
							} else {
								//echo $FG_TEXT_ERROR_DUPLICATION;
								$alarm_db_error_duplication = true;
							}
						}
					}
				}
			}
		}

		if (!is_null($this->FG_QUERY_EDITION_HIDDEN_FIELDS) && $this->FG_QUERY_EDITION_HIDDEN_FIELDS!=""){

			$table_split_field = preg_split("/,/",$this->FG_QUERY_EDITION_HIDDEN_FIELDS);
			$table_split_value = preg_split("/,/",$this->FG_QUERY_EDITION_HIDDEN_VALUE);

			for($k=0;$k<count($table_split_field);$k++){
				$param_update .= ", ";
				$param_update .= "$table_split_field[$k] = '".addslashes(trim($table_split_value[$k]))."'";
			}
		}

		if (strlen($this->FG_ADDITIONAL_FUNCTION_BEFORE_EDITION)>0 && ($this->VALID_SQL_REG_EXP))
				$res_funct = call_user_func(array('FormBO', $this->FG_ADDITIONAL_FUNCTION_BEFORE_EDITION));

		if ($this->FG_DEBUG == 1) {
			echo "<br><hr> PARAM_UPDATE: $param_update<br>".$this->FG_EDITION_CLAUSE;
        }

		if ($this->VALID_SQL_REG_EXP) {
			$this -> RESULT_QUERY = $instance_table -> Update_table ($this->DBHandle, $param_update, $this->FG_EDITION_CLAUSE, $func_table = null);
        }

		if($this -> FG_ENABLE_LOG == 1)
			$this -> logger -> insertLog_Update($_SESSION["admin_id"], 3, "A ".strtoupper($this->FG_INSTANCE_NAME)." UPDATED" , "A RECORD IS UPDATED, EDITION CALUSE USED IS ".$this->FG_EDITION_CLAUSE, $this->FG_TABLE_NAME, $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], $param_update);

		if ($this->FG_DEBUG == 1) echo $this -> RESULT_QUERY;
			// CALL DEFINED FUNCTION AFTER THE ACTION ADDITION
			if (strlen($this->FG_ADDITIONAL_FUNCTION_AFTER_EDITION)>0 && ($this->VALID_SQL_REG_EXP))
				$res_funct = call_user_func(array('FormBO', $this->FG_ADDITIONAL_FUNCTION_AFTER_EDITION));

		if (($this->VALID_SQL_REG_EXP) && (isset($this->FG_GO_LINK_AFTER_ACTION_EDIT))) {
			if ($this->FG_DEBUG == 1)  echo "<br> GOTO ; ".$this->FG_GO_LINK_AFTER_ACTION_EDIT.$processed['id'];
			$ext_link ='';
			if(is_numeric($processed['current_page']))$ext_link.="&current_page=".$processed['current_page'];
			if(!empty($processed['order']) && !empty($processed['sens']))$ext_link.="&order=".$processed['order']."&sens=".$processed['sens'];
			Header ("Location: ".$this->FG_GO_LINK_AFTER_ACTION_EDIT.$processed['id'].$ext_link);
		}
	}


	/**
     * Function to delete a record
     * @public
     */
	function perform_delete (&$form_action)
	{
		include_once (FSROOT."lib/Class.Table.php");

		if (strlen($this -> FG_ADDITIONAL_FUNCTION_AFTER_DELETE) > 0)
		$res_funct = call_user_func(array('FormBO', $this->FG_ADDITIONAL_FUNCTION_AFTER_DELETE));
		$processed = $this->getProcessed();  //$processed['firstname']
		$this->VALID_SQL_REG_EXP = true;

        $instance_table = null;
        $tableCount = count($this -> FG_FK_TABLENAMES);
        $clauseCount = count($this -> FG_FK_EDITION_CLAUSE);

        if(($tableCount == $clauseCount) && $clauseCount > 0 && $this-> FG_FK_DELETE_ALLOWED) {
            if ($processed['id']!="" || !is_null($processed['id'])) {
                $instance_table = new Table($this->FG_TABLE_NAME, $this->FG_QUERY_EDITION, $this -> FG_FK_TABLENAMES, $this -> FG_FK_EDITION_CLAUSE, $processed['id'], $this -> FG_FK_WARNONLY);
            }
        } else {
		    $instance_table = new Table($this->FG_TABLE_NAME, $this->FG_QUERY_EDITION);
        }
		$instance_table->FK_DELETE = ($this->FG_FK_WARNONLY ? false : true);

		if ($processed['id']!="" || !is_null($processed['id'])){
			$this->FG_EDITION_CLAUSE = str_replace("%id", $processed['id'], $this->FG_EDITION_CLAUSE);
		}

		$this -> RESULT_QUERY = $instance_table -> Delete_table ($this->DBHandle, $this->FG_EDITION_CLAUSE, $func_table = null);
		if($this -> FG_ENABLE_LOG == 1) {
			$this -> logger -> insertLog($_SESSION["admin_id"], 3, "A ".strtoupper($this->FG_INSTANCE_NAME)." DELETED" , "A RECORD IS DELETED, EDITION CLAUSE USED IS ".$this->FG_EDITION_CLAUSE, $this->FG_TABLE_NAME, $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], $param_update);
		}
		if (!$this -> RESULT_QUERY)  echo gettext("error deletion");

		$this->FG_INTRO_TEXT_DELETION = str_replace("%id", $processed['id'], $this->FG_INTRO_TEXT_DELETION);
		$this->FG_INTRO_TEXT_DELETION = str_replace("%table", $this->FG_TABLE_NAME, $this->FG_INTRO_TEXT_DELETION);
		if (isset($this->FG_GO_LINK_AFTER_ACTION_DELETE)) {
			if ($this->FG_DEBUG == 1)  echo "<br> GOTO ; ".$this->FG_GO_LINK_AFTER_ACTION_DELETE.$processed['id'];
			if( $this->FG_GO_LINK_AFTER_ACTION_DELETE){
				$ext_link ='';
				if(is_numeric($processed['current_page']))$ext_link="&current_page=".$processed['current_page'];
				if(!empty($processed['order']) && !empty($processed['sens']))$ext_link.="&order=".$processed['order']."&sens=".$processed['sens'];
				if(substr($this->FG_GO_LINK_AFTER_ACTION_DELETE,-3)=="id="){
					Header ("Location: ".$this->FG_GO_LINK_AFTER_ACTION_DELETE.$processed['id'].$ext_link);
				}else{
					Header ("Location: ".$this->FG_GO_LINK_AFTER_ACTION_DELETE.$ext_link);
				}
			}
		}

	}

    /*
      Function to check for the Dependent Data
    */
    function isFKDataExists()
    {
        $processed = $this->getProcessed();
        $tableCount = count($this -> FG_FK_TABLENAMES);
        $clauseCount = count($this -> FG_FK_EDITION_CLAUSE);
        $rowcount = 0;
        if(($tableCount == $clauseCount) && $clauseCount > 0) {
            for($i = 0; $i < $tableCount; $i++)
            {
                if ($processed['id']!="" || !is_null($processed['id'])) {
                    $instance_table = new Table($this -> FG_FK_TABLENAMES[$i]);
                    $rowcount = $rowcount + $instance_table -> Table_count ($this->DBHandle, $this -> FG_FK_EDITION_CLAUSE[$i], $processed['id']);
                }
            }
        }
        $this -> FG_FK_RECORDS_COUNT = $rowcount;
        return ($rowcount > 0);
    }

	/**
	* Function to add_content
	* @public
	*/
	function perform_add_content($sub_action,$id)
	{
		$processed = $this->getProcessed();
		$table_split = preg_split("/:/",$this->FG_TABLE_EDITION[$sub_action][14]);
		$instance_sub_table = new Table($table_split[0], $table_split[1].", ".$table_split[5]);

		if (is_array($processed[$table_split[1]])) {
			foreach($processed[$table_split[1]] as $value) {
				if (empty($table_split[12]) || preg_match ('/'.$this->FG_regular[$table_split[12]][0].'/', $value)){
					// RESPECT REGULAR EXPRESSION
					$result_query = $instance_sub_table -> Add_table ($this->DBHandle, "'".addslashes(trim($value))."', '".addslashes(trim($id))."'", null, null);

					if (!$result_query) {
						$findme   = 'duplicate';
						$pos_find = strpos($instance_sub_table -> errstr, $findme);

						if ($pos_find === false) {
							echo $instance_sub_table -> errstr;
						} else {
							$alarm_db_error_duplication = true;
						}
					}
				}
			}
		} else {
			$value = $processed[$table_split[1]];
			if (empty($table_split[12]) || preg_match ('/'.$this->FG_regular[$table_split[12]][0].'/', $value)) {
				// RESPECT REGULAR EXPRESSION
				$result_query = $instance_sub_table -> Add_table ($this->DBHandle, "'".addslashes(trim($value))."', '".addslashes(trim($id))."'", null, null);

				if (!$result_query) {

					$findme   = 'duplicate';
					$pos_find = strpos($instance_sub_table -> errstr, $findme);

					if ($pos_find === false) {
						echo $instance_sub_table -> errstr;
					} else {
						$alarm_db_error_duplication = true;
					}
				}
			}
		}
		;
	}


	/**
	* Function to del_content
	* @public
	*/
	function perform_del_content($sub_action,$id)
	{
		$processed = $this->getProcessed();
		$table_split = preg_split("/:/", $this->FG_TABLE_EDITION[$sub_action][14]);
		if(array_key_exists($table_split[1].'_hidden', $processed)){
			$value = trim($processed[$table_split[1].'_hidden']);
		} else {
			$value = trim($processed[$table_split[1]]);
		}
		$instance_sub_table = new Table($table_split[0], $table_split[1].", ".$table_split[5]);
		$SPLIT_FG_DELETE_CLAUSE = $table_split[1]."='".$value."' AND ".$table_split[5]."='".trim($id)."'";
		$instance_sub_table -> Delete_table ($this->DBHandle, $SPLIT_FG_DELETE_CLAUSE, $func_table = null);
	}


	/**
     * Function to create the top page section
     * @public
     */
	function create_toppage ($form_action)
	{
		$processed = $this->getProcessed();
		if ($form_action=="ask-edit" || $form_action=="edit" || $form_action == "add-content" ||
			$form_action == "del-content"){ ?>
			<table class="toppage_maintable">
				<tr><td height="20"  align="center">
						<font class="toppage_maintable_text">
						  <?php
						  	if ($this->FG_ADITION_GO_EDITION == "yes-done") echo '<font class="toppage_maintable_editmsg">'.$this->FG_ADITION_GO_EDITION_MESSAGE.'</font><br><br>';
							if ($alarm_db_error_duplication){
								echo '<font class="toppage_maintable_editmsg">'.gettext("ERROR_DUPLICATION").' ::'.$this->FG_TEXT_ERROR_DUPLICATION.'</font>';
							}else{
								echo $this->FG_INTRO_TEXT_EDITION;
							}
						  ?>
						  <br>
						</font>
				</td></tr>
			</table>
	  <?php
	  	} elseif ($form_action=="ask-add") {
	  		if (strlen($this->FG_INTRO_TEXT_ADITION)>1){
     		?>
				<table class="toppage_askedit">
				  <tr>
				  <td height="40">
					<td height="48" align="center" valign="middle" class="textnegrita"><p>
						 <font class="fontstyle_002">
					 <?php echo $this->FG_INTRO_TEXT_ADITION?> </font></p></td>
				  </tr>
			  	</table>
			<?php
		  	} else {
				echo '<br>';
			}
	  	}
	}


	/**
     * CREATE_ACTIONFINISH : Function to display result
     * @public
     */
	function create_actionfinish ($form_action)
	{
		$processed = $this->getProcessed();
		?>
		<TABLE  cellSpacing=2  class="toppage_actionfinish">
			<TR>
        	<TD class="form_head">
				  <?php if ($form_action == "delete") { ?>
				  <?php echo $this->FG_INSTANCE_NAME?> Deletion
				  <?php }elseif ($form_action == "add"){ ?>
				  INSERT NEW <?php echo $this->FG_INSTANCE_NAME?>
				  <?php  } ?>
            </TD>
            </TR><TR>
            <TD width="516" valign="top" class="tdstyle_001"> <br>
				<div align="center"><strong>
				<?php
					if ($form_action == "delete") {
				 		echo $this->FG_INTRO_TEXT_DELETION;
					} elseif ($form_action == "add") {
					 	if(!empty($this -> RESULT_QUERY)) {
					 		echo $this->FG_TEXT_ADITION_CONFIRMATION;
					 	} else {
					 		echo $this->FG_TEXT_ADITION_ERROR;
					 	}
					}
				?>
           		</strong></div>
				<br>
			</TD>
            </TR>
		</TABLE>
		<br><br><br><br><br>
		<?php
	}

	/**
     *  CREATE_CUSTOM : Function to display a custom message using form_action
     *  @public		TODO : maybe is better to allow use a string as parameter
     */
	function create_custom($form_action)
	{
		$processed = $this->getProcessed();
		?>

		<TABLE width="85%" class="toppage_customaction">
		<TBODY>
		<TR>
			<TD class="form_head">
			</TD>
		 </TR>
		  <TR>
		    <TD width="516" valign="top" class="tdstyle_001"> <br>
		    	<div align="center"><strong><?php echo $form_action ?><?php echo gettext("Done");?>
			</strong></div>
			<br>
			</TD>
			</TR>
		</TBODY>
              </TABLE>
			<br><br><br><br><br>
		<?php
	}


	/**
     *  CREATE_CUSTOM : Function to display a custom message using form_action
     *  @public		TODO : maybe is better to allow use a string as parameter
     */
	 function create_select_form()
	 {
	 	$processed = $this->getProcessed();
	 	include_once (FSROOT."lib/Class.Table.php");
		$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");
		$FG_TABLE_CLAUSE = "";
		$list_tariffname = $instance_table_tariffname  -> Get_list ($this->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);

		$instance_table_tariffgroup = new Table("cc_tariffgroup", "id, tariffgroupname, lcrtype");
		$FG_TABLE_CLAUSE = "";
		$list_tariffgroup = $instance_table_tariffgroup  -> Get_list ($this->DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);

	 ?>
	<center>
	<?php  if (!empty($this->FG_TOP_FILTER_NAME)) echo "<font size=\"1\">$this->FG_TOP_FILTER_NAME</font><br>"; ?>

	<!-- ** ** ** ** ** Part for the select form  ** ** ** ** ** -->
	<FORM METHOD="POST" ACTION="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
	<INPUT TYPE="hidden" NAME="posted" value="1">
	<INPUT TYPE="hidden" NAME="current_page" value="0">
    <?php
        if ($this->FG_CSRF_STATUS == true) {
    ?>
        <INPUT type="hidden" name="<?php echo $this->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $this->FG_FORM_UNIQID; ?>" />
        <INPUT type="hidden" name="<?php echo $this->FG_CSRF_FIELD ?>" value="<?php echo $this->FG_CSRF_TOKEN; ?>" />
    <?php
        }
    ?>

		<table class="form_selectform" cellspacing="1">

			<tr>
				<td align="left" valign="top" class="form_selectform_td1">
					&nbsp;&nbsp;<?php echo gettext("Export Call Plan with LCR");?>
				</td>
				<td class="bgcolor_005" align="left">
				<table class="form_selectform_table1"><tr>
					<td width="50%" align="center">&nbsp;&nbsp;
						<select NAME="tariffgroup" size="1"  class="form_input_select" width=250">
							<option value=''><?php echo gettext("Choose a call plan");?></option>
							<?php
							 foreach ($list_tariffgroup as $recordset){
							?>
								<option class=input value='<?php  echo $recordset[0]."-:-".$recordset[1]."-:-".$recordset[2]?>' <?php if ($recordset[0]==$this->FG_TOP_FILTER_VALUE2) echo "selected";?>><?php echo $recordset[1]?></option>
							<?php 	 }
							?>
						</select>

					</td>
					<td class="form_selectform_table1_td1">
						<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path_Main;?>/button-search.gif" />
						<?php
						if(!empty($_SESSION['def_ratecard_tariffgroup'])) { ?>
                    	- <a href="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?cancelsearch_callplanlcr=true"><font color="red"><b><img src="<?php echo KICON_PATH; ?>/button_cancel.gif" height="16"> Cancel Search</b></font></a>&nbsp;
                    <?php } ?>
	  				</td>

				</tr></table>
				</td>
			</tr>

		</table>
	</FORM>
</center>
	<?php
	}




	/**
     *  create_select_form_client() : Function to display a select list on form
     *  @public		TODO : maybe is better to allow use a string as parameter
     */
	 function create_select_form_client($table_cluase = "")
	 {
	 	$processed = $this->getProcessed();
	 	include_once (FSROOT."lib/Class.Table.php");
		$instance_table_tariffname = new Table("cc_tariffplan, cc_tariffgroup_plan", "id, tariffname");
		$FG_TABLE_CLAUSE = $table_cluase;

		$list_tariffname = $instance_table_tariffname  -> Get_list ($this->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);

	 ?>
	<center>
	  <?php  if (is_string ($this->FG_TOP_FILTER_NAME)) echo "<font size=\"3\">".gettext("THE CURRENT RATECARD")." : <b>$this->FG_TOP_FILTER_NAME</b></font>"; ?>
		<br><br>&nbsp;
	  <!-- ** ** ** ** ** Part for the select form  ** ** ** ** ** -->

		<table class="form_selectform" >
			<FORM METHOD="POST" ACTION="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
				<INPUT TYPE="hidden" NAME="posted" value="1">
				<INPUT TYPE="hidden" NAME="current_page" value="0">

                <?php
                    if ($this->FG_CSRF_STATUS == true) {
                ?>
                    <INPUT type="hidden" name="<?php echo $this->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $this->FG_FORM_UNIQID; ?>" />
                    <INPUT type="hidden" name="<?php echo $this->FG_CSRF_FIELD ?>" value="<?php echo $this->FG_CSRF_TOKEN; ?>" />
                <?php
                    }
                ?>

			<tr>
				<td align="left" valign="top" class="form_selectform_td1">
					&nbsp;&nbsp;<?php echo gettext("R A T E C A R D");?>
				</td>
				<td class="bgcolor_005" align="left" bgcolor="#acbdee">
				<table class="form_selectform_table1"><tr>
					<td width="50%" align="center">&nbsp;&nbsp;
						<select NAME="tariffplan" size="1"  class="form_input_select">
								<option value=''><?php echo gettext("Choose a ratecard");?></option>

								<?php
								 foreach ($list_tariffname as $recordset){
								?>
									<option class=input value='<?php  echo $recordset[0]."-:-".$recordset[1]?>' <?php if ($recordset[0]==$this->FG_TOP_FILTER_VALUE) echo "selected";?>><?php echo $recordset[1]?></option>
								<?php 	 }
								?>
						</select>
					</td>
					<td class="form_selectform_table1_td1" >
					<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
	  			</td>

				</tr></table></td>
			</tr>
			</FORM>
		</table>
</center>
	<?php
	}

	/**
	* Function to create the search form
	* @public
	*/
	function create_search_form()
	{
		Console::logSpeed('Time taken to get to line '.__LINE__);
		$processed = $this->getProcessed();

		$cur = 0;
		foreach ($this->FG_FILTER_SEARCH_FORM_SELECT as $select) {
			// 	If is a sql_type
			if ($select[1]) {
				$instance_table = new Table($select[1][0], $select[1][1]);
				$list = $instance_table -> Get_list ($this -> DBHandle, $select[1][2], $select[1][3], $select[1][4],
												null, null, null, null);
				$this->FG_FILTER_SEARCH_FORM_SELECT[$cur][1] = $list;
			}else{
				$this->FG_FILTER_SEARCH_FORM_SELECT[$cur][1] = $select[3];
			}
			$cur++;
		}
		include ("Class.SearchHandler.inc.php");
	}

	/**
     * Function to create the form
     * @public
     */
	function create_form ($form_action, $list, $id=null)
	{
		Console::logSpeed('Time taken to get to line '.__LINE__);
		include_once (FSROOT."lib/Class.Table.php");
		$processed = $this->getProcessed();

		$id = $processed['id'];
		$atmenu = $processed['atmenu'];
		$stitle = $processed['stitle'];
		$ratesort = $processed['ratesort'];
		$sub_action = $processed['sub_action'];

		switch ($form_action) {
			case "add-content":
				$this->perform_add_content($sub_action,$id);
				include('Class.FormHandler.EditForm.inc.php');
				break;

			case "del-content":
				$this->perform_del_content($sub_action,$id);
				include('Class.FormHandler.EditForm.inc.php');
				break;

			case "ask-edit":
			case "edit":
				include('Class.FormHandler.EditForm.inc.php');
				break;

			case "ask-add":
				include('Class.FormHandler.AddForm.inc.php');
				break;

			case "ask-delete":
            case "ask-del-confirm":
				if (strlen($this -> FG_ADDITIONAL_FUNCTION_BEFORE_DELETE) > 0)
			   	$res_funct = call_user_func(array('FormBO', $this->FG_ADDITIONAL_FUNCTION_BEFORE_DELETE));
				include('Class.FormHandler.DelForm.inc.php');
				break;

			case "list":
				include('Class.ViewHandler.inc.php');
				break;

			case "delete":
			case "add":
				$this -> create_actionfinish($form_action);
				break;

			default:
				$this -> create_custom($form_action);
			}
	}



	/**
	 * Do multi-page navigation.  Displays the prev, next and page options.
	 * @param $page the page currently viewed
	 * @param $pages the maximum number of pages
	 * @param $url the url to refer to with the page number inserted
	 * @param $max_width the number of pages to make available at any one time (default = 20)
	 */
	function printPages($page, $pages, $url, $max_width = 20)
	{
		Console::logSpeed('Time taken to get to line '.__LINE__);
		global $lang;
		$window = 8;

		if ($page < 0 || $page > $pages) return;
		if ($pages < 0) return;
		if ($max_width <= 0) return;

		if ($pages > 1) {
			//echo "<center><p>\n";
			if ($page != 1) {
				$temp = str_replace('%s', 1-1, $url);
				echo "<a class=\"pagenav\" href=\"{$temp}\">{$this->lang['strfirst']}</a>\n";
				$temp = str_replace('%s', $page - 1-1, $url);
				echo "<a class=\"pagenav\" href=\"{$temp}\">{$this->lang['strprev']}</a>\n";
			}

			if ($page <= $window) {
				$min_page = 1;
				$max_page = min(2 * $window, $pages);
			}
			elseif ($page > $window && $pages >= $page + $window) {
				$min_page = ($page - $window) + 1;
				$max_page = $page + $window;
			}
			else {
				$min_page = ($page - (2 * $window - ($pages - $page))) + 1;
				$max_page = $pages;
			}

			// Make sure min_page is always at least 1
			// and max_page is never greater than $pages
			$min_page = max($min_page, 1);
			$max_page = min($max_page, $pages);

			for ($i = $min_page; $i <= $max_page; $i++) {
				$temp = str_replace('%s', $i-1, $url);
				if ($i != $page) echo "<a class=\"pagenav\" href=\"{$temp}\">$i</a>\n";
				else echo "$i\n";
			}
			if ($page != $pages) {
				$temp = str_replace('%s', $page + 1-1, $url);
				echo "<a class=\"pagenav\" href=\"{$temp}\">{$this->lang['strnext']}</a>\n";
				$temp = str_replace('%s', $pages-1, $url);
				echo "<a class=\"pagenav\" href=\"{$temp}\">{$this->lang['strlast']}</a>\n";
			}
		}
	}

}


