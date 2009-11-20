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

function help_wiki_link($wiki_article_name) {
	return gettext("For further information please consult") . ' <a target="_blank" href="http://trac.asterisk2billing.org/cgi-bin/trac.cgi/wiki/1-4-' . $wiki_article_name . '">' . gettext("the online documention") . '</a>.<br/>';
}

function create_help($text, $wiki = null) {
	if (!empty ($wiki))
		$wiki_text = help_wiki_link($wiki);
	else
		$wiki_text = "";
	$help = '
	<div class="toggle_show2hide">
	<div class="tohide" style="display:visible;">
	<div class="msg_info">' . $text . '
	<br/>' . $wiki_text . '<a href="#" target="_self" class="hide_help" style="float:right;"><img class="toggle_show2hide" src="' . KICON_PATH . '/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
	</div></div></div>';
	return $help;

}

if (SHOW_HELP) {

	$CC_help_agent = create_help(gettext("Agent list who have access to the Agent interface."), 'ShowAgent');

	$CC_help_agent_info = create_help(gettext("Personal information.") . '<br>' . gettext("You can update your personal information here."));

	$CC_help_sipfriend_list = create_help(gettext("Voip Config will create a SIP or IAX entry on the Asterisk server, so that a customer can set up a SIP or IAX client to connect directly to the asterisk server without the need to enter an account and pin each time a call is made. When done, click on the CONFIRM DATA button, then click reload to apply the changes on the Asterisk server.</br>") .
	gettext("The customer must then enter the URL/IP address of the asterisk server into the SIP/IAX client and use the account number and secret as the username and password."), 'ListSIPFriend');

	$CC_help_sipfriend_reload = create_help(gettext("Click reload to commit changes to Asterisk"));

	$CC_help_generate_signup = create_help(gettext("Generate a specific crypted URL and to configure signup with a customer group and call plan."));

	$CC_help_password_change = create_help(gettext("On this page you will be able to change your password, You have to enter the New Password and Confirm it."));
	
	$CC_help_remittance_request = create_help(gettext("On this page you will be able to create a remittance Remittance Request according to the commission accrued on your account.If the commission accrued is higher than a predefined threshold then it will be possible to ask a transfer on your balance or by a funds transfer."));

	$CC_help_secret_change = create_help(gettext("On this page you will be able to change your Secret used to crypt your generated signup URL, You have to enter the New Secret and Confirm it."));

	$CC_help_list_customer = create_help(gettext("Customers are listed below by card number. Each row corresponds to one customer, along with information such as their call plan, credit remaining, etc.</br>") .
	gettext("The SIP and IAX buttons create SIP and IAX entries to allow direct VoIP connections to the Asterisk server without further authentication."), 'ListCustomers');

	$CC_help_view_payment = create_help(gettext("Payment history - The section below allows you to add payments against a customer. Note that this does not change the balance on the card. Click refill under customer list to top-up a card."), 'ViewPayments');

	$CC_help_money_situation = create_help(gettext("This screen shows refills and payments made against each account, along with the current credit on each card. The initial amount of credit applied to the card is not included. The amount owing is calculated by subtracting payments from refills"), 'MoneySituation');

	$CC_help_view_refill_agent = create_help(gettext("Agents Refill history - The section below allows you to see your refill"), 'ViewAgentRefill');

	$CC_help_view_payment_agent = create_help(gettext("Agent Payment history - The section below allows you to browse your payments"), 'ViewPayments');

	$CC_help_support_list = create_help(gettext("You can see here, all tickets created. You can also add a new ticket for one customer."));

	$CC_help_signup_agent = create_help(gettext("This shows a list of all signup key create for this agent, this key is used to identify the default paramater for the subscription on the signup page"));

	$CC_help_callerid_list = create_help(gettext("Set the caller ID so that the customer calling in is authenticated on the basis of the callerID rather than with the account number"), 'ListCallerID');

} //ENDIF SHOW_HELP

if (!isset ($disable_load_conf) || !($disable_load_conf)) {

	$DBHandle = DbConnect();
	$instance_table = new Table();
	$QUERY = "SELECT configuration_key FROM cc_configuration where configuration_key in ('MODULE_PAYMENT_AUTHORIZENET_STATUS','MODULE_PAYMENT_PAYPAL_STATUS','MODULE_PAYMENT_MONEYBOOKERS_STATUS','MODULE_PAYMENT_WORLDPAY_STATUS','MODULE_PAYMENT_PLUGNPAY_STATUS') AND configuration_value='True'";
	$payment_methods = $instance_table->SQLExec($DBHandle, $QUERY);
	$show_logo = '';
	for ($index = 0; $index < sizeof($payment_methods); $index++) {
		if ($payment_methods[$index][0] == "MODULE_PAYMENT_PAYPAL_STATUS") {
			$show_logo .= '<a href="https://www.paypal.com/en/mrb/pal=PGSJEXAEXKTBU" target="_blank"><img src="' . KICON_PATH . '/paypal_logo.gif" alt="Paypal"/></a> &nbsp; ';
			//} elseif( $payment_methods[$index][0] == "MODULE_PAYMENT_AUTHORIZENET_STATUS") {
			//	$show_logo .= '<a href="http://authorize.net/" target="_blank"><img src="'.KICON_PATH.'/authorize.gif" alt="Authorize.net"/></a> &nbsp; ';
		}
		elseif ($payment_methods[$index][0] == "MODULE_PAYMENT_MONEYBOOKERS_STATUS") {
			$show_logo .= '<a href="https://www.moneybookers.com/app/?rid=811621" target="_blank"><img src="' . KICON_PATH . '/moneybookers.gif" alt="Moneybookers"/></a> &nbsp; ';
			//} elseif( $payment_methods[$index][0] == "MODULE_PAYMENT_WORLDPAY_STATUS") {
			//	$show_logo .= '<a href="http://www.worldpay.com/" target="_blank"><img src="'.KICON_PATH.'/worldpay.gif" alt="worldpay.com"/></a> &nbsp; ';
		}
		elseif ($payment_methods[$index][0] == "MODULE_PAYMENT_PLUGNPAY_STATUS") {
			$show_logo .= '<a href="http://www.plugnpay.com/" target="_blank"><img src="' . KICON_PATH . '/plugnpay.png" alt="plugnpay.com"/></a> &nbsp; ';
		}
	}
	$PAYMENT_METHOD = '<table style="width:70%;margin:0 auto;" align="center" ><tr><TD valign="top" align="center" class="tableBodyRight">' . $show_logo . '</td></tr></table>';
}


