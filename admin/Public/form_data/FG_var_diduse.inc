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



getpost_ifset(array('did', 'inuse', 'actionbtn', 'atmenu', 'stitle', 'order', 'sens', 'current_page', 'posted'));

$HD_Form = new FormHandler("cc_did_use", "DID in use");

$HD_Form -> FG_DEBUG = 0;
if ($posted){
	
	if (isset($did) && strlen($did)>0) {
		if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
		$HD_Form -> FG_TABLE_CLAUSE.="id_did = '$did'";
	}

	if ($inuse) {
		if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
		$HD_Form -> FG_TABLE_CLAUSE.=" (releasedate IS NULL OR releasedate < '1984-01-01 00:00:00') and activated=1";
		$HD_Form -> FG_OTHER_BUTTON1 = true;
		$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_did_use.php?posted=$posted&order=$order&sens=$sens&current_page=$current_page&did=$did&inuse=$inuse&atmenu=document&stitle=Document&actionbtn=release_did&did=|col0|";
		$HD_Form -> FG_OTHER_BUTTON1_IMG =Images_Path . "/icon-release.gif";
		$HD_Form -> FG_OTHER_BUTTON1_ALT=gettext("Release this did");
	} else {
		$HD_Form -> FG_TABLE_DEFAULT_ORDER = "reservationdate";
		$HD_Form -> FG_TABLE_DEFAULT_SENS = "DESC";
		$HD_Form -> FG_OTHER_BUTTON1 = false;
	}
} else {
	if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0) $HD_Form -> FG_TABLE_CLAUSE.=" AND ";
	$HD_Form -> FG_TABLE_CLAUSE.=" (releasedate IS NULL OR releasedate < '1984-01-01 00:00:00') and activated=1";
	$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id_did";
	$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
	$HD_Form -> FG_OTHER_BUTTON1 = true;
	$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_did_use.php?posted=$posted&order=$order&sens=$sens&current_page=$current_page&did=$did&inuse=$inuse&atmenu=document&stitle=Document&actionbtn=release_did&did=|col0|";
	$HD_Form -> FG_OTHER_BUTTON1_IMG =Images_Path . "/icon-release.gif";
	$HD_Form -> FG_OTHER_BUTTON1_ALT=gettext("Release this did");
}


$HD_Form -> FG_TABLE_ID="cc_did_use.id";

$actived_list = array();
$actived_list["1"] = array( gettext("Reserved"), "1");
$actived_list["0"] = array( gettext("Free"), "0");

$HD_Form -> AddViewElement(gettext("ID DID"), "id_did", "6%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DID"), "id_did", "6%", "center", "sort", "25", "lie", "cc_did", "did", "id='%id'", "%1");
$HD_Form -> AddViewElement(gettext("ID card"), "id_cc_card", "6%", "center", "sort");
$HD_Form -> AddViewElement(gettext("CARDNUMBER"), "id_cc_card", "6%", "center", "sort", "25", "lie", "cc_card", "username", "id='%id'", "%1");

$HD_Form -> AddViewElement(gettext("RESERVATION DATE"), "reservationdate", "20%", "center", "sort", "", "", "", "", "", "", "");
$HD_Form -> AddViewElement(gettext("LIBERATION DATE"), "releasedate", "20%", "center", "sort", "", "", "", "", "", "", "");
$HD_Form -> AddViewElement(gettext("STATUS"), "activated", "5%", "center", "sort", "", "list", $actived_list);

// added a parameter to append  FG_TABLE_ID  ( by default ) or disable 0.
$HD_Form -> FieldViewElement ('id_did, id_did, id_cc_card, id_cc_card, reservationdate, releasedate, activated');


$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO DID IN USE!");  
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> CV_FOLLOWPARAMETERS ="&posted=$posted&did=$did&inuse=$inuse";

$HD_Form -> FG_ADDITION = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_FILTER_APPLY = false;


