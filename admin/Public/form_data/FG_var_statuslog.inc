<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/



getpost_ifset(array('id', 'status', 'id_cc_card', 'updated_date', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'status', 'stitle', 'atmenu', 'current_page', 'order', 'sens','popup_select'));


$HD_Form = new FormHandler("cc_status_log sl LEFT JOIN cc_card ON cc_card.id = sl.id_cc_card", gettext("Status Log"));

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = "sl.id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "updated_date";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";


$date_clause = "";
if (DB_TYPE == "postgres") {
	$UNIX_TIMESTAMP = "";
} else {
	$UNIX_TIMESTAMP = "UNIX_TIMESTAMP";
}

$lastdayofmonth = date("t", strtotime($tostatsmonth.'-01'));
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($Period=="Month"){
	if ($frommonth && isset($fromstatsmonth)) $date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) >= $UNIX_TIMESTAMP('$fromstatsmonth-01')";
	if ($tomonth && isset($tostatsmonth)) {
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) <= $UNIX_TIMESTAMP('".$tostatsmonth."-$lastdayofmonth 23:59:59')"; 
	}
} else {
	if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) {
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) >= $UNIX_TIMESTAMP('$fromstatsmonth_sday-$fromstatsday_sday')";
	}
	if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) {
		if (strlen($date_clause)>0) $date_clause.=" AND ";
		$date_clause.=" $UNIX_TIMESTAMP(sl.updated_date) <= $UNIX_TIMESTAMP('$tostatsmonth_sday-".sprintf("%02d",intval($tostatsday_sday)/*+1*/)." 23:59:59')";
	}
}

if($date_clause != "") {
	$HD_Form -> FG_TABLE_CLAUSE.=" ".$date_clause;
}

if ($id_cc_card != "") {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
		$HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.=" sl.id_cc_card = '$id_cc_card'";
}	

if ($status >= 0) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
		$HD_Form -> FG_TABLE_CLAUSE.=" AND ";

	$HD_Form -> FG_TABLE_CLAUSE.=" sl.status = '$status'";
}	

$cardstatus_list = Constants::getCardStatus_List();

$HD_Form -> AddViewElement(gettext("ID CARD"), "id_cc_card", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "id_cc_card", "20%", "center", "sort", "", "lie_link", "cc_card", "username,id", "id='%id'", "%1", "", "A2B_entity_card.php");
$HD_Form -> AddViewElement(gettext("LAST NAME"), "id_cc_card", "20%", "center", "sort", "15", "lie", "cc_card", "lastname", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "20%", "center", "sort", "", "list", $cardstatus_list);
$HD_Form -> AddViewElement(gettext("DATE"), "updated_date", "20%", "center", "sort", "16");

$HD_Form -> FieldViewElement ('sl.id_cc_card, id_cc_card, id_cc_card, sl.status, sl.updated_date');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO STATUS LOG AVAILABLE!");
$HD_Form -> FG_INTRO_TEXT_EDITION = "";
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_ADDITION = false;

$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;

$HD_Form -> FG_FILTER_APPLY = true;
$HD_Form -> FG_FILTERFIELD = 'cardnumber';
$HD_Form -> FG_FILTERFIELDNAME = 'Account number';
$HD_Form -> FG_FILTER_FORM_ACTION = 'list';

if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.="username like '$filterprefix%'";
}

