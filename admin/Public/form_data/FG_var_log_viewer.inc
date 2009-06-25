<?php

getpost_ifset(array('id', 'loglevel', 'enteradmin', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'stitle', 'atmenu', 'current_page', 'order', 'sens','popup_select'));

$HD_Form = new FormHandler("cc_system_log csl LEFT JOIN cc_ui_authen cua ON csl.iduser = cua.userid", "Log");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "creationdate";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";

$condition_ary = array();
$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($Period=="Month"){
	if ($frommonth && isset($fromstatsmonth)) $condition_ary[] = "UNIX_TIMESTAMP(csl.creationdate) >= UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth))
	{
		$condition_ary[] = "UNIX_TIMESTAMP(csl.creationdate) <= UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')";
	}
}else{
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) 
	{
		$condition_ary[] = "UNIX_TIMESTAMP(csl.creationdate) >= UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday))
	{
		$condition_ary[] = " UNIX_TIMESTAMP(csl.creationdate) <= UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
	}
}

if($enteradmin != "")
{
	$condition_ary[] = "csl.iduser = '$enteradmin'";
}	
if($loglevel != "" && $loglevel > 0)
{
	$condition_ary[] = " csl.loglevel = '$loglevel'";
}

$HD_Form -> FG_TABLE_CLAUSE = implode(' AND ', $condition_ary);

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
$HD_Form -> FG_ADDITION = false;
$HD_Form -> FG_INFO = true;
$HD_Form -> FG_INFO_LINK = "A2B_log_activity_info.php?id=";


$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;



