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

if (! has_rights (ACX_ADMINISTRATOR)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id', 'group_title', 'group_description','agi_conf', 'groupdelete'));


$setbool = false;
$handle = DbConnect();
$instance_table = new Table();

if ( !empty($groupdelete) && substr($groupdelete,0,8) == "agi-conf" && $groupdelete != "agi-conf1" && strlen($groupdelete) < 11) {
	$QUERY =  "DELETE FROM cc_config_group WHERE group_title='$groupdelete'";
	$result = $instance_table -> SQLExec ($handle, $QUERY);
	$QUERY =  "DELETE FROM cc_config WHERE config_group_title='$groupdelete'";
	$result = $instance_table -> SQLExec ($handle, $QUERY);

	$msg = $groupdelete.' : '."Group deleted !";
	$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="green"><b>'.$msg.'</b></font><br>';
}


$HD_Form = new FormHandler("cc_config_group","configuration group");

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_ORDER = "id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";


$HD_Form -> AddViewElement(gettext("TITLE"), "group_title", "20%", "center");
$HD_Form -> AddViewElement(gettext("DESCRIPTION"), "group_description", "60%", "left");


$HD_Form -> FieldViewElement ('group_title, group_description');

$HD_Form -> CV_NO_FIELDS  = gettext("THERE ARE NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_LIMITE_DISPLAY = 15;

$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_ADDITION = false;
$HD_Form -> FG_DELETION = false;

$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_LINK = "A2B_entity_config.php?groupselect=|col0|";
$HD_Form -> FG_OTHER_BUTTON1_ALT = gettext('DETAILS');

$HD_Form -> FG_OTHER_BUTTON2 = true;
$HD_Form -> FG_OTHER_BUTTON2_LINK = "A2B_entity_config_group.php?groupdelete=|col0|";
$HD_Form -> FG_OTHER_BUTTON2_IMG = Images_Path."/delete.png";
$HD_Form -> FG_OTHER_BUTTON2_HTML_ID = "|col0|";
$HD_Form -> FG_OTHER_BUTTON2_CONDITION = 'substr("|col0|",0,8) == "agi-conf" && "|col0|" != "agi-conf1"';
$HD_Form -> FG_OTHER_BUTTON2_ALT = gettext('DELETE THIS AGI CONF');

$HD_Form -> FG_INTRO_TEXT_EDITION = '';
$HD_Form -> FG_INTRO_TEXT_ASK_DELETION = gettext("If you really want remove this")." ".$HD_Form->FG_INSTANCE_NAME.", ".gettext("click on the delete button.");
$HD_Form -> FG_INTRO_TEXT_ADD = gettext("you can add easily a new")." ".$HD_Form->FG_INSTANCE_NAME.".<br>".gettext("Fill the following fields and confirm by clicking on the button add.");


$HD_Form -> FG_INTRO_TEXT_ADITION = '';
$HD_Form -> FG_TEXT_ADITION_CONFIRMATION = gettext("Your new")." ".$HD_Form->FG_INSTANCE_NAME." ".gettext("has been inserted. <br>");


$HD_Form -> FG_BUTTON_EDITION_SRC = $HD_Form -> FG_BUTTON_ADITION_SRC  = Images_Path . "/cormfirmboton.gif";
$HD_Form -> FG_BUTTON_EDITION_BOTTOM_TEXT = $HD_Form -> FG_BUTTON_ADITION_BOTTOM_TEXT = gettext("Click 'Confirm Data' to continue");


$HD_Form -> FG_GO_LINK_AFTER_ACTION_ADD = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_EDIT = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";
$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)."?atmenu=document&stitle=Document&wh=AC&id=";



