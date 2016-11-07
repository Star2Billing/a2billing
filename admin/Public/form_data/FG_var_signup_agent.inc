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


getpost_ifset(array('id', 'id_agent', 'id_tariff_group', 'code','id_group'));


$HD_Form = new FormHandler("cc_agent_signup","SIGNUP KEY");

$HD_Form -> FG_TABLE_ID = 'cc_agent_signup.id';

$HD_Form -> FG_DEBUG = 0;

$HD_Form ->FG_LIST_ADDING_BUTTON1 = true;
$HD_Form ->FG_LIST_ADDING_BUTTON_LINK1 = "A2B_signup_agent.php?atmenu=user&section=".$_SESSION["menu_section"];
$HD_Form ->FG_LIST_ADDING_BUTTON_ALT1 = $HD_Form ->FG_LIST_ADDING_BUTTON_MSG1 = gettext("Add Signup KEY");
$HD_Form ->FG_LIST_ADDING_BUTTON_IMG1 = Images_Path ."/key_add.png" ;
$HD_Form -> FG_DELETION = true;


$HD_Form -> AddViewElement(gettext("KEY"), "code", "25%", "center", "sort", "","", "", "", "", "", "");
$HD_Form -> AddViewElement(gettext("AGENT"), "id_agent", "25%", "center", "sort", "", "lie", "cc_agent", "firstname, lastname , login", "id='%id'", "%1 %2 (%3)","");
$HD_Form -> AddViewElement(gettext("CALL PLAN"), "id_tariffgroup","20%","center", "sort", "", "lie", "cc_tariffgroup", "tariffgroupname", "id='%id'", "%1","");
$HD_Form -> AddViewElement(gettext("GROUP"), "id_group", "20%", "center", "sort", "", "lie", "cc_card_group", "name", "id='%id'", "%1","");

$HD_Form -> FieldViewElement ('code, id_agent, id_tariffgroup ,id_group');




$HD_Form -> CV_NO_FIELDS  = gettext("THERE IS NO ".strtoupper($HD_Form->FG_INSTANCE_NAME)." CREATED!");
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_TEXT_TITLE_ABOVE_TABLE = '';
$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = false;
$HD_Form -> FG_OTHER_BUTTON1 = true;
$HD_Form -> FG_OTHER_BUTTON1_ALT = 'URL';
$HD_Form -> FG_OTHER_BUTTON1_IMG = Images_Path ."/link.png";
$HD_Form -> FG_OTHER_BUTTON1_LINK = "javascript:elegantBOX('".$A2B->config['signup']['urlcustomerinterface']."signup.php?key=|col0|',400);";

$HD_Form -> FG_GO_LINK_AFTER_ACTION_DELETE = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);