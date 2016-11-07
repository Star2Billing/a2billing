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


getpost_ifset(array('IDCust', 'id', 'id_cc_card', 'cid', 'activated'));


$HD_Form = new FormHandler("cc_callerid", gettext("CallerID"));

$HD_Form -> FG_DEBUG = 0;

$cnts = new Constants();

$actived_list = $cnts->getActivationTrueFalseList();
$yesno = $cnts->getYesNoList();


$HD_Form -> AddViewElement(gettext("CALLERID"), "cid", "25%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACTIVATED"), "activated", "25%", "center", "sort", "26");


$HD_Form -> FieldViewElement ('cid, activated');

$HD_Form -> CV_TITLE_TEXT = gettext("CallerID list");
$HD_Form -> CV_NO_FIELDS  = gettext("There are no Caller IDs created");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 10;
$HD_Form -> FG_VIEW_TABLE_WITDH = '70%';


// My Code for Where Cluase
if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
    $HD_Form -> FG_TABLE_CLAUSE.=" AND ";

$HD_Form -> FG_TABLE_CLAUSE.="id_cc_card =".$_SESSION["card_id"];

if (strlen($HD_Form -> FG_EDITION_CLAUSE)>0)
    $HD_Form -> FG_EDITION_CLAUSE.=" AND ";

$HD_Form -> FG_EDITION_CLAUSE.="id_cc_card =".$_SESSION["card_id"];


$HD_Form -> FG_ADDITION = true;
$HD_Form -> FG_EDITION = true;
$HD_Form -> FG_DELETION = true;
$HD_Form -> FG_OTHER_BUTTON1 = false;


$HD_Form -> AddEditElement (gettext("CALLERID"),
			   "cid",
			   '$value',
			   "INPUT",
			   "size=20 maxlength=30",
			   "18",
			   gettext("Insert the CallerID"),
			   "" , "", "",	"", "" , "", "", "");


$HD_Form -> AddEditElement (gettext("ACTIVATED"),
			   "activated",
			   't',
			   "RADIOBUTTON",
			   "",
			   "",
			   gettext("Choose if you want to activate this card"),
			   "" , "", "", "Yes :t, - No:f", "", "", "", "" );



$HD_Form -> FieldEditElement ('cid, activated');



if (isset($filterprefix)  &&  (strlen($filterprefix)>0)) {
    if (strlen($HD_Form -> FG_TABLE_CLAUSE)>0)
        $HD_Form -> FG_TABLE_CLAUSE.=" AND ";

    $HD_Form -> FG_TABLE_CLAUSE.="cid like '$filterprefix%'";
}



$HD_Form -> FG_INTRO_TEXT_EDITION= gettext("You can modify, through the following form, the different properties of your")." ".$HD_Form->FG_INSTANCE_NAME;
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");

$HD_Form -> FG_OTHER_BUTTON1_LINK="A2B_entity_card?form_action=ask-edit&stitle=Customers_Card&id=";
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path."/icon-card.png";

$HD_Form -> FG_INTRO_TEXT_ADITION = gettext("You can add your home phone or cellphone number below to allow you to call in, be automatically recognized, and use your telephony services without having to enter your PIN.");
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted.")."<br>";

$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path."/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Made your change to edit this callerid.");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";



