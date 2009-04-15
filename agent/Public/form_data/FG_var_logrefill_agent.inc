<?php

getpost_ifset(array('id', 'agent_id', 'date', 'credit','description'));


if (!isset($form_action) || $form_action=="list") {
	$HD_Form = new FormHandler("cc_logrefill_agent LEFT JOIN cc_agent ON cc_agent.id=agent_id","Refill Agent");
	$HD_Form -> FG_TABLE_CLAUSE = " cc_agent.id =".$_SESSION['agent_id'] ;
} else {
	$HD_Form = new FormHandler("cc_logrefill_agent","Refill Agent");
}

$HD_Form -> FG_TABLE_ID = 'cc_logrefill_agent.id';

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$list_refill_type=Constants::getRefillType_List();
$HD_Form -> AddViewElement(gettext("ID"), "cc_logrefill_agent.id", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("AGENT"), "creator", "10%", "center", "sort", "25", "lie", "cc_agent", "lastname, firstname", "id='%id'", "%1 %2");
$HD_Form -> AddViewElement(gettext("LOGIN"), "login", "10%", "center", "sort", "25", "lie", "cc_agent", "login", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("REFILL DATE"), "date", "20%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "description", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("REFILL TYPE"),"refill_type", "7%", "center", "sort", "", "list", $list_refill_type);
$HD_Form -> AddViewElement(gettext("REFILL AMOUNT"), "cc_logrefill.credit", "18%", "center", "sort");

$HD_Form -> FieldViewElement ('cc_logrefill_agent.id, agent_id,agent_id, date, description, cc_logrefill_agent.refill_type as refill_type, cc_logrefill_agent.credit');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!"); 
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_refill_info_agent.php?id=";



if (!($popup_select>=1))	 $HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'refill_agent_selection';
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for payments created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('DATE');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_FIELD = 'date';


