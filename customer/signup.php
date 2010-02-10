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


include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");

if (!isset ($form_action))
	$form_action = "ask-add";

if (!isset ($action))
	$action = $form_action;


// PROVISION TEST DATA
if (false) {
	$_POST["lastname"] = $_POST["firstname"] = $_POST["address"] = $_POST["city"] = $_POST["state"] = $_POST["country"] = 'SIGN-' . MDP_STRING(5) . '-' . MDP_NUMERIC(3);
	$_POST["email"] = MDP_STRING(10) . '@sign-up.com';
	$_POST["zipcode"] = $_POST["phone"] = '12345667789';
}

include ("./form_data/FG_var_signup.inc");
include ("./lib/customer.smarty.php");

if (!$A2B->config["signup"]['enable_signup'])
	exit;

getpost_ifset(array ('subscriber_signup'));

if (!is_numeric($subscriber_signup)) {
	//check subscriber_signup
	$table_check_subscriber = new Table("cc_subscription_signup", "COUNT(*)");
	$clause_check_subscriber = "";
	$result_check_subscriber = $table_check_subscriber->Get_list(DbConnect(), $clause_check_subscriber);
	$check_subscriber = $result_check_subscriber[0][0];
	if ($check_subscriber > 0) {
		Header("Location: signup_service.php");
		die();
	}
}


$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

if ($id != "" || !is_null($id)) {
	$HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

$list = $HD_Form->perform_action($form_action);


if ($form_action == "add") {
	unset ($_SESSION["cardnumber_signup"]);
	$_SESSION["language_code"] = $_POST["language"];
	$_SESSION["cardnumber_signup"] = $maxi;
	$_SESSION["id_signup"] = $HD_Form->RESULT_QUERY;
	Header("Location: signup_confirmation.php");
}

// #### HEADER SECTION
$smarty->display('signup_header.tpl');

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list, $id = null);

// #### FOOTER SECTION
$smarty->display('signup_footer.tpl');


