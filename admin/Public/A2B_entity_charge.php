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


include_once ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include_once ("../lib/Form/Class.FormHandler.inc.php");
include_once ("../lib/admin.smarty.php");
include_once ("./form_data/FG_var_charge.inc");

if (!has_rights(ACX_BILLING)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form_c->setDBHandler(DbConnect());

$HD_Form_c->init();

// To fix internal links due $_SERVER["PHP_SELF"] from parent include that fakes them
if ($wantinclude == 1) {
	$HD_Form_c->FG_EDITION_LINK = "A2B_entity_charge.php?form_action=ask-edit&id=";
	$HD_Form_c->FG_DELETION_LINK = "A2B_entity_charge.php?form_action=ask-delete&id=";
}

if ($id != "" || !is_null($id)) {
	$HD_Form_c->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form_c->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
	$form_action = "list"; //ask-add
if (!isset ($action))
	$action = $form_action;

$list = $HD_Form_c->perform_action($form_action);

if ($wantinclude != 1) {
	// #### HEADER SECTION
	$smarty->display('main.tpl');

	// #### HELP SECTION
	echo $CC_help_edit_charge;
}

// #### TOP SECTION PAGE
$HD_Form_c->create_toppage($form_action);

$HD_Form_c->create_form($form_action, $list, $id = null);

if ($wantinclude != 1) {
	$smarty->display('footer.tpl');
}

