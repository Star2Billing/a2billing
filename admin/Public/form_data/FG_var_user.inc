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



getpost_ifset(array('id', 'groupID', 'userid', 'login','country','city', 'pwd_encoded', 'perms', 'name', 'direction', 'zipcode', 'state', 'phone', 'fax', 'popup_select', 'popup_formname', 'popup_fieldname', 'email'));


if ($groupID=="0") {
	$FG_INSTANCE_NAME="Administrator";
	$ADDING_BUTTON_LINK1 = "A2B_entity_user.php?form_action=ask-add&groupID=0&section=".$_SESSION["menu_section"];
	$ADDING_BUTTON_ALT1 = gettext("Add ADMINISTRATOR");
	$ADDING_BUTTON_IMG1 = Images_Path ."/user_gray.png" ;
} elseif ($groupID=="1") {
	$FG_INSTANCE_NAME="Sub-administrator";
	$ADDING_BUTTON_LINK1 = "A2B_entity_user.php?form_action=ask-add&groupID=1&section=".$_SESSION["menu_section"];
	$ADDING_BUTTON_ALT1 =  gettext("Add ADMIN ACL");
	$ADDING_BUTTON_IMG1 = Images_Path ."/user_orange.png" ;
} else {
	$FG_INSTANCE_NAME="Users";
}

$HD_Form = new FormHandler("cc_ui_authen",$FG_INSTANCE_NAME);


$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID = " userid";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = " datecreation";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = $ADDING_BUTTON_LINK1;
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = $ADDING_BUTTON_ALT1;
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = $ADDING_BUTTON_IMG1;


if (is_numeric($groupID)) {
	$FG_TABLE_CLAUSE = "groupid='$groupID'";
} 
//else select all

$HD_Form -> FG_TABLE_CLAUSE = $FG_TABLE_CLAUSE;

$right_list = array();
$right_list["1"] = array( gettext("CUSTOMERS"), "1");
$right_list["2"] = array( gettext("BILLING"), "2");
$right_list["3"] = array( gettext("RATECARD"), "4");
$right_list["4"] = array( gettext("TRUNK"), "8");
$right_list["5"] = array( gettext("CALL REPORT"), "16");
$right_list["6"] = array( gettext("CRONT SERVICE"), "32");
$right_list["7"] = array( gettext("AGENT / ADMIN"), "64");
$right_list["8"] = array( gettext("MAINTENANCE"), "128");
$right_list["9"] = array( gettext("MAIL"), "256");
$right_list["10"] = array( gettext("DID"), "512");
$right_list["11"] = array( gettext("CALL BACK"), "1024");
$right_list["12"] = array( gettext("OUTBOUND CID"), "2048");
$right_list["13"] = array( gettext("PACKAGE OFFER"), "4096");
$right_list["14"] = array( gettext("PREDICTIVE DIALER"), "8192");
$right_list["15"] = array( gettext("INVOICES"), "16384");
$right_list["16"] = array( gettext("SUPPORT"), "32768");
$right_list["17"] = array( gettext("DASHBOARD"), "65536");
$right_list["18"] = array( gettext("SYSTEM SETTINGS"), "131072");
$right_list["19"] = array( gettext("EDIT/DELETE REFILLS"), "262144");
$right_list["20"] = array( gettext("EDIT/DELETE PAYMENTS"), "524288");
$right_list["21"] = array( gettext("EDIT/DELETE CUSTOMERS"), "1048576");
$right_list["22"] = array( gettext("DELETE NOTIFICATIONS"), "2097152");
$right_list["23"] = array( gettext("DELETE CDR"), "4194304");
$right_list["24"] = array( gettext("EDIT/DELETE ADMIN"), "8388608");
$right_list["25"] = array( gettext("EDIT/DELETE AGENT"), "16777216");

$len_right_list = count($right_list);


$HD_Form -> AddViewElement(gettext("USERID"), "UserID", "14%", "center", "sort");
$HD_Form -> AddViewElement(gettext("DATE"), "datecreation", "24%", "center", "sort", "19", "", "", "", "", "", "display_dateformat");
$HD_Form -> AddViewElement(gettext("LOGIN"), "login", "24%", "center", "sort");

$HD_Form -> FieldViewElement ('userid, datecreation, login');

$HD_Form -> CV_NO_FIELDS  = gettext("NO")." ".strtoupper($HD_Form->FG_INSTANCE_NAME)." ".gettext("HAVE BEEN CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;


if (!$popup_select) {
	$HD_Form -> FG_ADDITION = true;	
	$HD_Form -> FG_INFO = true;
	$HD_Form -> FG_INFO_LINK = "A2B_admin_info.php?groupID=$groupID&id=";
}

if (has_rights (ACX_MODIFY_ADMINS) && !($popup_select)) {
	$HD_Form -> FG_DELETION = true;
	$HD_Form -> FG_EDITION = true;
	
	if ($form_action!="ask-add" && $form_action!="add") {
		$HD_Form -> AddEditElement (gettext("USERID"),
			"userid",
			'$value',
			"INPUT",
			"size=8 READONLY maxlength=6",
			"4",
			gettext("Insert the userid"),
			"" , "", "", "", "", "", "", "" );
	}
	
	$HD_Form -> AddEditElement (gettext("LOGIN"),
		"login",
		'$value',
		"INPUT",
		"size=30 maxlength=12",
		"3",
		gettext("Insert the username"),
		"" , "", "", "", "" , "", "", "");
		
	$HD_Form -> AddEditElement (gettext("PASSWORD"),
		"pwd_encoded",
		'$value',
		"INPUT",
		"type=password size=30 maxlength=15",
		"3",
		gettext("Insert the Reseller password"),
		"" , "", "", "", "", "", "", "");
	
	if ($groupID!="0") {
		$HD_Form -> AddEditElement (gettext("PERMISSION"),
			"perms",
			'$value',
			"SELECT",
			"size=\"$len_right_list\" multiple=\"multiple\"",
			"", "",
			"list", "", "description, id", "", $right_list, "%1" , "",
			gettext("To select several rights in the above ListBox, hold the CTRL key down while clicking the items") );
	}
	
	
	$HD_Form -> AddEditElement (gettext("NAME"),
		"name",
		'$value',
		"INPUT",
		"size=60 maxlength=50",
		"",
		gettext("Insert the name"),
		"" , "", "", "", "", "", "", "");
	
	$HD_Form -> AddEditElement (gettext("ADDRESS"),
		"direction",
		'$value',
		"INPUT",
		"size=60 maxlength=250",
		"",
		gettext("Insert the direction"),
		"" , "", "", "", "", "", "", "");
	$HD_Form -> AddEditElement (gettext("CITY"),
		"city",
		'$value',
		"INPUT",
		"size=30 maxlength=30",
		"",
		gettext("Insert the country"),
		"" , "", "", "", "" , "", "", "");
	
	$HD_Form -> AddEditElement (gettext("ZIPCODE"),
		"zipcode",
		'$value',
		"INPUT",
		"size=10 maxlength=7",
		"",
		gettext("Insert the zip code"),
		"" , "", "", "", "", "", "", "");
	
	$HD_Form -> AddEditElement (gettext("STATE"),
		"state",
		'$value',
		"INPUT",
		"size=30 maxlength=30",
		"",
		gettext("Insert the state"),
		"" , "", "", "", "" , "", "", "");
	$HD_Form -> AddEditElement (gettext("COUNTRY"),
		"country",
		'$value',
		"INPUT",
		"size=30 maxlength=30",
		"",
		gettext("Insert the country"),
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
	$HD_Form -> AddEditElement (gettext("EMAIL"),
		"email",
		'$value',
		"INPUT",
		"size=30 maxlength=70",
		"",
		gettext("Insert the email"),
		"" , "", "", "", "" , "", "", "");
		
	
	if (($groupID=="2") || ($groupID=="3")) {
		if ($groupID=="2") {
			$HD_Form -> AddEditElement ("UserID",
				"a",
				'$value',
				"SELECT",
				"","", "Insert the iva",
				"sql" ,
				"Customer",
				"AnaCust, IDCust",
				"", "", "%2 (%1)", "", "");
		} else {
			$HD_Form -> AddEditElement ("ResellerID",
				"a",
				'$value',
				"SELECT",
				"","", "Insert the iva",
				"sql" ,
				"secauth",
				"login, userID",
				"groupID='1'", "", "%2 (%1)", "", "" );
		}
	}
	
	if ($form_action!="ask-add" && $form_action!="add") {
	        $FG_QUERY_EDITION='userid, ';
	}
	
	$FG_QUERY_EDITION .= 'login, ';
	
	$FG_QUERY_EDITION .='pwd_encoded,  ';
	
	if ($groupID!="0") {
		$FG_QUERY_EDITION .= 'perms, ';
	}
	
	$FG_QUERY_EDITION .= 'name, direction,city, zipcode, state,country, phone, fax,email';
	
	$HD_Form -> FieldEditElement ($FG_QUERY_EDITION);
	
	$HD_Form -> FG_EDITION_CLAUSE = " userid='%id'";
	
	if (is_numeric($groupID)) {
		$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS .= "groupID";
		$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  .= "$groupID";
	
		$HD_Form -> FG_QUERY_EDITION_HIDDEN_FIELDS  = "groupID";
		$HD_Form -> FG_QUERY_EDITION_HIDDEN_VALUE   = "$groupID";
	
		if ($groupID=="0") {
			$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS .= ", perms";
			$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  .= ", 5242879";
	
			$HD_Form -> FG_QUERY_EDITION_HIDDEN_FIELDS .= ", perms";
			$HD_Form -> FG_QUERY_EDITION_HIDDEN_VALUE  .= ", 5242879";
		}
	} else {
		$HD_Form -> FG_QUERY_ADITION_HIDDEN_FIELDS .= "groupID";
		$HD_Form -> FG_QUERY_ADITION_HIDDEN_VALUE  .= "1";
	}
	
	if (($popup_select>=1)){
		$HD_Form -> FG_OTHER_BUTTON1 = true;
		$HD_Form -> FG_OTHER_BUTTON1_ALT = '<font color="red">&lt;select&gt;</font>';
		$HD_Form -> FG_OTHER_BUTTON1_IMG = '';
		
		if ($popup_select==1) {
			$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:sendValue('|param|');";
		}
	}
	
	$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("Modify the properties of the")." ".$HD_Form->FG_INSTANCE_NAME;
	$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
	$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");
	
	
	$HD_Form -> FG_INTRO_TEXT_ADITION = '';
	$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";
	
	
	$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
	$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");
	
	
	$HD_Form -> FG_GO_LINK_AFTER_ACTION = $_SERVER['PHP_SELF']."?atmenu=user&groupID=$groupID&stitle=Administrator+management&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = $_SERVER['PHP_SELF']."?atmenu=user&groupID=$groupID&stitle=Administrator+management&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = $_SERVER['PHP_SELF']."?atmenu=user&groupID=$groupID&stitle=Administrator+management&id=";
	$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = $_SERVER['PHP_SELF']."?atmenu=user&groupID=$groupID&stitle=Administrator+management&id=";
}

