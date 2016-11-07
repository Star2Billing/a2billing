<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id', 'lastname', 'firstname', 'email', 'address','city', 'state', 'country', 'zipcode','id_timezone', 'phone', 'fax','message', 'voicemail_activated'));


$HD_Form = new FormHandler("cc_card","Card");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_HTML_TABLE_WIDTH = "60%";
$HD_Form -> FG_EDITION_CLAUSE = " id = ".$_SESSION["card_id"];

$HD_Form -> FG_INTRO_TEXT_EDITION = '';

$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_ADDITION = false;


$HD_Form -> AddEditElement (gettext("LASTNAME"),
			   "lastname",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=50",
			   "11",
			   gettext("Insert the lastname of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");

$HD_Form -> AddEditElement (gettext("FIRSTNAME"),
			   "firstname",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=50",
			   "11",
			   gettext("Insert the firstname of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("ADDRESS"),
			   "address",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=100",
			   "0",
			   gettext("Insert the address of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("CITY"),
			   "city",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=40",
			   "0",
			   gettext("Insert the city"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("STATE/PROVINCE"),
			   "state",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=40",
			   "11",
			   gettext("Insert the state"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("COUNTRY"),
			   "country",
			   "",
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_country",
			   "countryname, countrycode",
			   "", "", "%1", "", "", "", '' );


$HD_Form -> AddEditElement (gettext("ZIP/POSTAL CODE"),
			   "zipcode",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=20",
			   "0",
			   gettext("Insert the zipcode"),
			   "" , "", "",	"", "", "" , "NO", "");

$HD_Form -> AddEditElement (gettext("TIMEZONE"),
			   "id_timezone",
			   '',
			   "SELECT",
			   "", "", "",
			   "sql",
			   "cc_timezone",
			   "gmtzone, id",
			   "", "", "%1", "", "", "", "" );

$HD_Form -> AddEditElement (gettext("PHONE NUMBER"),
			   "phone",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=20",
			   "7",
			   gettext("Insert the phone number of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");


$HD_Form -> AddEditElement (gettext("FAX NUMBER"),
			   "fax",
			   '$value',
			   "INPUT",
			   "size=30 maxlength=20",
			   "",
			   gettext("Insert the fax number of the customers"),
			   "" , "", "",	"", "", "" , "NO", "");

if (ACT_VOICEMAIL) {
	$HD_Form -> AddEditElement (gettext("ACTIVE VOICEMAIL SERVICE"),
				   "voicemail_activated",
				   '0',
				   "RADIOBUTTON",
				   "",
				   "",
				   gettext("Customer can choose to enable the voicemail for this card"),
				   "" , "", "", "Yes :1, - No:0", "", "" , "", gettext("Customer can enable the voicemail for this card."));
}

$add_fields_voicemail = '';
if (ACT_VOICEMAIL)
	$add_fields_voicemail = ', voicemail_activated ';


$HD_Form -> FieldEditElement ('lastname, firstname, address, city, state, country, zipcode,id_timezone, phone, fax '.$add_fields_voicemail);

$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily update your information clicking on the button.");

$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your record has been updated.")."<br>";

$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?message=success";



