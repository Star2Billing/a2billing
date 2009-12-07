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

function create_help($text) {
	$help = '
	<div class="toggle_show2hide">
	<div class="tohide" style="display:visible;">
	<div class="msg_info">' . $text . '
	<a href="#" target="_self" class="hide_help" style="float:right;"><img class="toggle_show2hide" src="' . Images_Path . '/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
	</div></div></div>';
	return $help;

}

if (SHOW_HELP) {

	$CC_help_webphone = create_help(gettext("From here, you can use the web based screen phone. You need microphone and speakers on your PC."));

	$CC_help_balance_customer = create_help(gettext("All calls are listed below. Search by month, day or status. Additionally, you can check the rate and price."));
	
	$CC_help_support = create_help(gettext("On this page, you can open a support ticket and consult the status of your existing ticket."));
	
	$CC_help_card = create_help(gettext("Personal information.") . '<br>' . gettext("You can update your personal information here."));

	$CC_help_notification = create_help(gettext("Notification settings.") . '<br>' . gettext("You can update your notification settings here."));
	
	$CC_help_simulator_rateengine = create_help(gettext("Simulate the calling process to discover the cost per minute of a call, and the number of minutes you can call that number with your current credit."));

	$CC_help_sipiax_info = create_help(gettext("Configuration information for SIP and IAX Client. You can simply copy and paste it in your configuration files and do necessary modifications."));

	$CC_help_password_change = create_help(gettext("On this page you will be able to change your password, You have to enter the New Password and Confirm it."));

	$CC_help_ratecard = create_help(gettext("View Rates"));

	$CC_help_view_payment = create_help(gettext("Payment history - Record of payments made."));
	
	$CC_help_voicemail = create_help(gettext("Voicemail - The section below allows you to see all your voicemail, listen to them and move them into other folders."));

	$CC_help_list_voucher = create_help(gettext("Enter your voucher number to top up your card."));

	$CC_help_campaign = create_help(gettext("This section will allow you to create and edit campaign. ") .
	gettext("A campaign will be attached to a user in order to let him use the predictive-dialer option. ") .
	gettext("Predictive dialer will browse all the phone numbers from the campaign and perform outgoing calls."));

	$CC_help_phonelist = create_help(gettext("Phonelist are all the phone numbers attached to a campaign. You can add, remove and edit the phone numbers."));

	$CC_help_view_invoice = create_help(gettext("Invoice history - The section below allows you to see and pay the invoices that you have to pay."));

	$CC_help_view_receipt = create_help(gettext("Receipt history - The section below allows you to see the receipt that you received. you can see in them the summary of some withdrawal"));

	$CC_help_phonebook = create_help(gettext("Phonebook are set of phone numbers. You can add, remove and edit the phonebook. You can also associate phonebook to a campaign in the Campaign section"));

	$CC_help_list_did = create_help(gettext("Select the country below where you would like a DID, select a DID from the list and enter the destination you would like to assign it to."));

	$CC_help_release_did = create_help(gettext("After confirmation, the release of the did will be done immediately and you will not be monthly charged any more."));

	$CC_help_speeddial = create_help(gettext("Map single digit to your most dialed numbers."));
	
	$CC_help_callback = create_help(gettext("Callback : Entre your phone number and the phone number you wish to call."));

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

$CALL_LABS = '
<table width="70%" align="center">
	<tr>
		<TD width="%75" valign="top" align="center" class="tableBodyRight" background="' . Images_Path . '/background_cells.gif" >
				Global VoIP termination (A-Z)  to over 400 worldwide destinations!<br>
				Visit Call-Labs at <a href="http://www.call-labs.com/" target="_blank">http://www.call-labs.com/</a><br/>
		</TD>
		<TD width="%25" valign="middle" align="center" class="tableBodyRight" background="' . Images_Path . '/background_cells.gif" >
				<a href="http://www.call-labs.com/" target="_blank"><img src="' . Images_Path . '/call-labs.com.png" alt="call-labs"/></a>
		</TD>
	</tr>
</table>';


