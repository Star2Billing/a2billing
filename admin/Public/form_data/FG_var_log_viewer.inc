<?php

getpost_ifset(array('id', 'loglevel', 'enteradmin', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'stitle', 'atmenu', 'current_page', 'order', 'sens','popup_select'));

$HD_Form = new FormHandler("cc_system_log csl LEFT JOIN cc_ui_authen cua ON csl.iduser = cua.userid", "Log");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "creationdate";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$date_clause = "";
if (DB_TYPE == "postgres"){		
	 	$UNIX_TIMESTAMP = "";
}else{
		$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
if ($Period=="Month"){
	if ($frommonth && isset($fromstatsmonth)) $date_clause.=" $UNIX_TIMESTAMP(csl.creationdate) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth))
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(csl.creationdate) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
	}
}else{
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) 
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(csl.creationdate) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday))
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(csl.creationdate) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
	}
}

if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0 && strlen($enteradmin)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
if($enteradmin != "")
{
	$HD_Form -> FG_TABLE_CLAUSE.=" csl.iduser = '$enteradmin'";
}	
if (strlen($enteradmin)>0 && strlen($loglevel)>0 && $loglevel > 0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
if($loglevel != "" && $loglevel > 0)
{
	$HD_Form -> FG_TABLE_CLAUSE.=" csl.loglevel = '$loglevel'";
}
if (strlen($loglevel)>0 && strlen($date_clause)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
if($date_clause != "")
{
	$HD_Form -> FG_TABLE_CLAUSE.=" ".$date_clause;
}
//$HD_Form -> FG_EDITION_CLAUSE = " id = $id";

$HD_Form -> AddViewElement(gettext("ID"), "id", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("LOGIN"), "login", "8%", "center", "sort");
$HD_Form -> AddViewElement(gettext("LOGLEVEL"), "loglevel", "10%", "center");
$HD_Form -> AddViewElement(gettext("ACTION"), "action", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("description"), "description", "15%", "center", "sort");
$HD_Form -> AddViewElement(gettext("TABLENAME"), "tablename", "12%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("PAGENAME"), "pagename", "5%", "center", "sort");
$HD_Form -> AddViewElement(gettext("IPADDRESS"), "ipaddress", "4%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE"), "creationdate", "14%", "center", "sort", "16");
//$HD_Form -> AddViewElement(gettext("DATA"), "data", "8%", "center", "SORT");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id, cua.login, loglevel, action, description, tablename, pagename, ipaddress, creationdate');
$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO LOG AVAILABLE1!");
$HD_Form -> FG_INTRO_TEXT_EDITION = "";
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

$HD_Form -> AddEditElement (gettext("LOGIN"),
	"login",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");
	
$HD_Form -> AddEditElement (gettext("LOG-LEVEL"),
	"loglevel",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");
	
$HD_Form -> AddEditElement (gettext("action"),
	"action",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");

$HD_Form -> AddEditElement (gettext("DESCRIPTION"),
	"description",
	'$value',
	"TEXTAREA",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");

$HD_Form -> AddEditElement (gettext("TABLENAME"),
	"tablename",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");
	
	$HD_Form -> AddEditElement (gettext("IPADDRESS"),
	"ipaddress",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");
	
	$HD_Form -> AddEditElement (gettext("CREATIONDATE"),
	"creationdate",
	'$value',
	"INPUT",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");
	
	$HD_Form -> AddEditElement (gettext("DATA"),
	"data",
	'$value',
	"TEXTAREA",
	"size=30 maxlength=30 readonly",
	"",
	"",
	"" , "", "", "", "" , "", "" ,"");
$HD_Form -> FieldEditElement ('login, loglevel, action, description,tablename,ipaddress,creationdate,data');
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_log_viewer.php?form_action=ask-edit&id=|param|";
$HD_Form -> FG_OTHER_BUTTON1_ALT = 'DETAILS';


$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=document&stitle=Document&wh=AC&id=";

