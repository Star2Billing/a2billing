<?php

getpost_ifset(array('id', 'status', 'id_cc_card', 'updated_date', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'stitle', 'atmenu', 'current_page', 'order', 'sens','popup_select'));


$HD_Form = new FormHandler("cc_status_log sl", "Status Log");


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "updated_date";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$date_clause = "";
if (DB_TYPE == "postgres"){		
	$UNIX_TIMESTAMP = "";
}else{
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
if ($Period=="Month"){
	if ($frommonth && isset($fromstatsmonth)) $date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth))
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
	}
}else{
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) 
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday))
	{
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
	}
}

if($date_clause != "")
{
	$HD_Form -> FG_TABLE_CLAUSE.=" ".$date_clause;
}

if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0 && strlen($id_cc_card)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";

if($id_cc_card != "")
{
	$HD_Form -> FG_TABLE_CLAUSE.=" sl.id_cc_card = '$id_cc_card'";
}	

$cardstatus_list = array();
$cardstatus_list["0"]  = array( gettext("CANCELLED"), "0");
$cardstatus_list["1"]  = array( gettext("ACTIVE"), "1");
$cardstatus_list["2"]  = array( gettext("NEW"), "2");
$cardstatus_list["3"]  = array( gettext("WAITING-MAILCONFIRMATION"), "3");
$cardstatus_list["4"]  = array( gettext("RESERVED"), "4");
$cardstatus_list["5"]  = array( gettext("EXPIRED"), "5");



$HD_Form -> AddViewElement(gettext("ID CARD"), "id_cc_card", "10%", "center", "sort");
$HD_Form -> AddViewElement("CARDNUMBER", "id_cc_card", "20%", "center", "sort", "", "lie_link", "cc_card", "username,id", "id='%id'", "%1", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("LAST NAME"), "id_cc_card", "20%", "center", "sort", "15", "lie", "cc_card", "lastname", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "20%", "center", "sort", "", "list", $cardstatus_list);

$HD_Form -> AddViewElement(gettext("DATE"), "updated_date", "20%", "center", "sort", "16");

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id_cc_card, id_cc_card, id_cc_card, status, updated_date');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO STATUS LOG AVAILABLE!");
$HD_Form -> FG_INTRO_TEXT_EDITION = "";
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;

$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
