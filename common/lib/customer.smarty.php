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

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

define( 'FULL_PATH', dirname(__FILE__) . '/' );
define( 'SMARTY_DIR', FULL_PATH . '/smarty/' );
define( 'TEMPLATE_DIR',  './templates/' );
define( 'TEMPLATE_C_DIR', './templates_c/' );

require_once SMARTY_DIR . 'Smarty.class.php';

$smarty = new Smarty;


$skin_name = $_SESSION["stylefile"];


$smarty->template_dir = TEMPLATE_DIR . $skin_name.'/';
$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->plugins_dir= "./plugins/";

$smarty->assign("TEXTCONTACT", TEXTCONTACT);
$smarty->assign("EMAILCONTACT", EMAILCONTACT);
$smarty->assign("COPYRIGHT", COPYRIGHT);
$smarty->assign("CCMAINTITLE", CCMAINTITLE);
$smarty->assign("SIGNUPLINK", SIGNUP_LINK);

$smarty->assign("ACXPASSWORD", $ACXPASSWORD);
$smarty->assign("ACXSIP_IAX", $ACXSIP_IAX);
$smarty->assign("ACXCALL_HISTORY", $ACXCALL_HISTORY);
$smarty->assign("ACXPAYMENT_HISTORY", $ACXPAYMENT_HISTORY);
$smarty->assign("ACXVOUCHER", $ACXVOUCHER);
$smarty->assign("ACXINVOICES", $ACXINVOICES);
$smarty->assign("ACXDID", $ACXDID);
$smarty->assign("ACXSPEED_DIAL", $ACXSPEED_DIAL);
$smarty->assign("ACXRATECARD", $ACXRATECARD);
$smarty->assign("ACXSIMULATOR", $ACXSIMULATOR);
$smarty->assign("ACXWEB_PHONE", $ACXWEB_PHONE);
$smarty->assign("ACXCALL_BACK", $ACXCALL_BACK);
$smarty->assign("ACXCALLER_ID", $ACXCALLER_ID);
$smarty->assign("ACXSUPPORT", $ACXSUPPORT);
$smarty->assign("ACXNOTIFICATION", $ACXNOTIFICATION);
$smarty->assign("ACXAUTODIALER", $ACXAUTODIALER);
$smarty->assign("ACXVOICEMAIL", $ACXVOICEMAIL);


if($exporttype != "" && $exporttype != "html") {
	$smarty->assign("EXPORT", 1);
} else {
	$smarty->assign("EXPORT", 0);
}

getpost_ifset(array('section'));

if(!empty($section)) {
	$_SESSION["menu_section"] = $section;
} else {
	$section = $_SESSION["menu_section"];
}
$smarty->assign("section", $section);

$smarty->assign("SKIN_NAME", $skin_name);
// if it is a pop window
if (!is_numeric($popup_select)) {
	$popup_select=0;
}
// for menu
$smarty->assign("popupwindow", $popup_select);

if (!empty($msg)) {
	switch($msg){
		case "nodemo": 	$smarty->assign("MAIN_MSG", '<center><b><font color="red">'.gettext("This option is not available on the Demo!").'</font></b></center><br>');
	}
}


// OPTION FOR THE MENU
$smarty->assign("A2Bconfig", $A2B->config);


$smarty->assign("PAGE_SELF", $PHP_SELF);



