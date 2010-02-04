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

	$CC_help_mail_notifications = create_help(gettext("The Notification component is responsible for informing the Customer, via e-mail, that the account has reached a minimum credit."), 'Notification');

	$CC_help_notifications = create_help(gettext("Notification: You can see below all notifications received about some event."), 'Notificationbox');

	$CC_help_list_seria = create_help(gettext("This page shows the series list.") . '<br>' . gettext("Series & serials is used for the creation of a card range specifically for accounting purposes instead of card id."), 'ListSeria');

	$CC_help_list_group = create_help(gettext("This page shows a group list.") . gettext("The Group field is used for grouping customers for quick search, batch update and reporting."), 'ListGroup');

	$CC_help_generate_signup = create_help(gettext("Generate a specific crypted URL to configure signup with a customer group and call plan."));

	$CC_help_list_customer = create_help(gettext("Customers are listed below by account number. Each row corresponds to one customer, along with information such as their call plan, credit remaining, etc.</br>") . gettext("The SIP and IAX buttons create SIP and IAX entries to allow direct VoIP connections to the Asterisk server without further authentication."), 'ListCustomers');

	$CC_help_import_customer = create_help(gettext("Import Customers from a CSV file."), 'ImportCustomers');

	$CC_help_info_customer = create_help(gettext("Customer information."), 'ListCustomers');

	$CC_help_refill_customer = create_help(gettext("Top up the account by selecting or typing in the account number directly, and enter the amount of credit to apply, then click ADD to confirm."), 'RefillCustomer');

	$CC_help_create_customer = create_help(gettext("Create and edit the properties of each customer. Click <b>CONFIRM DATA</b> at the bottom of the page to save changes."), 'CreateCustomer');

	$CC_help_generate_customer = create_help(gettext("Bulk create customers in a single step. <br> Set the properties of the batch such as initial credit, account type and currency, then click on the GENERATE CUSTOMERS button to create the batch."), 'GenerateCustomers');

	$CC_help_sipfriend_list = create_help(gettext("SIP and IAX Config will create a SIP or IAX entry on the Asterisk server, so that a customer can set up a SIP or IAX client to connect directly to the asterisk server without the need to enter an account and pin each time a call is made. When done, click on the CONFIRM DATA button, then click reload to apply the changes on the Asterisk server.</br>") .
	gettext("The customer must then enter the URL/IP address of the asterisk server into the SIP/IAX client, and use the Account Number and Secret word as the username and password."), 'ListSIPFriend');

	$CC_help_sipfriend_reload = create_help(gettext("Click reload to commit changes to Asterisk"));

	$CC_help_sipfriend_edit = create_help(gettext("Each SIP/IAX client is identified by a number of parameters.</br></br>") .
	gettext("More details on how to configure clients are on the Wiki") . ' -> <a href="http://voip-info.org/wiki-Asterisk+config+sip.conf" target="_blank">sip.conf</a> &
<a href="http://voip-info.org/wiki-Asterisk+config+iax.conf" target="_blank">iax.conf</a>', 'EditFriend');

	$CC_help_callerid_list = create_help(gettext("Set the caller ID so that the customer calling in is authenticated on the basis of the callerID rather than with the account number"), 'ListCallerID');

	$CC_help_money_situation = create_help(gettext("This screen shows refills and payments made against each account, along with the current credit on each account. The initial amount of credit applied to the account is not included. The amount owing is calculated by subtracting payments from refills"), 'MoneySituation');

	$CC_help_view_commission_agent = create_help(gettext("Agents Commission history - The section below allows you to add commissions against an agent. Normally the commissions are generated automatically by the customer's payment."), 'ViewCommissions');
	
	$CC_help_view_remittance_agent = create_help(gettext("Agents Remittance request history - The section below allows you to confirm or refuse remittance request of an agent. The remittance reques are generated automatically by the agent."), 'ViewRemittance');
	
	$CC_help_view_payment = create_help(gettext("Payment history - The section below allows you to add payments against a customer. Note that this does not change the balance on the account. Click on 'create associate refill' when you create a payment to top-up an account."), 'ViewPayments');

	$CC_help_view_payment_agent = create_help(gettext("Agent Payment history - The section below allows you to add payments against an agent. Note that this does not change the balance on the account. Click on 'create associate refill' when you create  a payment to top-up an account."), 'ViewPayments');

	$CC_help_view_billing_customer = create_help(gettext("Billing history - The section below allows you to see all billing generated by the batch for the customers, or also to run a billing manually for a specific customer. Note that this creates one invoice associated with the created billing."), 'ViewPayments');

	$CC_help_view_billing_agent = create_help(gettext("Billing history - The section below allows you see all billing generate by the batch for the agents , or also to run a billing manualy for a specific agent. Note that this create one invoice associate to the created billing."), 'ViewPayments');

	$CC_help_view_refill = create_help(gettext("Refill history - The section below allows you to add refills against a customer. Note that this changes the balance on the account"), 'ViewPayments');

	$CC_help_view_invoice = create_help(gettext("Invoice history - The section below allows you to see and create invoices against a customer. Only the closed invoice can be seen on the customer interface"), 'ViewInvoices');

	$CC_help_view_receipt = create_help(gettext("Receipt history - The section below allows you to see and create receipt against a customer.Only the closed receipt can be see in the customer interface. Receipts are only an information for the user and aren't used in the balance of the system"), 'ViewInvoices');

	$CC_help_view_refill_agent = create_help(gettext("Agents Refill history - The section below allows you to add refills against an agent. Note that this changes the balance on the account."), 'ViewAgentRefill');

	$CC_help_view_paypal = create_help(gettext("Paypal History - The section below shows all paypal receipts."), 'ViewPaypal');

	$CC_help_add_payment = create_help(gettext("Add payments to a customer's account!"), 'AddPayment');

	$CC_help_list_tariffgroup = create_help(gettext("List of Call Plans, a Call Plan is a collection of ratecards. You can click on edit to add new ratecards to the Call Plan"), 'ListCallPlan');

	$CC_help_add_tariffgroup = create_help(gettext("A Call Plan is a collection of ratecards.") .
	gettext("The system will choose the most appropriate rate according to the Call Plan settings (LCR or LCD).<br/>") .
	gettext("LCR : Least Cost Routing - Find the trunk with the cheapest carrier cost. (buying rate)<br>") .
	gettext("LCD : Least Cost Dialing - Find the trunk with the cheapest retail rate (selling rate)"), 'AddCallPlan');

	$CC_help_list_ratecard = create_help(gettext("List ratecards that have been created!<br>Ensure that a ratecard is added into the call plan under 'List Ratecard'"), 'ListRatecard');

	$CC_help_edit_ratecard = create_help(gettext("A ratecard is a set of rates defined and applied according to the dialling prefix, for instance 441 & 442 : UK Landline.") . '<br/>' .
	gettext("Each ratecard may have as many rates as you wish, however, if a dialling prefix cannot be matched when a call is made, then the call will be terminated.") . '<br/>' .
	gettext('A ratecard has a "start date", an "expiry date" and a you can define a default trunk, but if no trunk is defined, the ratecard default trunk will be used.'), 'EditRatecard');

	$CC_help_def_ratecard = create_help(gettext("Please select a ratecard and click on search to browse the different rates/dialing prefix of the selected ratecard."), 'Rate');

	$CC_help_sim_ratecard = create_help(gettext('Please select an account, then enter the number you wish to call and press the "SIMULATE" button.'), 'RatecardSimulator');

	$CC_help_rate = create_help(gettext("Please fill in the fields below to set up the rate for each destination."), 'Rate');

	$CC_help_import_ratecard = create_help(gettext("This section is a utility to import ratecards from a CSV file.") . "<br>" .
	gettext('Define the ratecard name, the trunk to use and the fields that you wish to include from your csv files. Finally, select the csv files and click on the "Import Ratecard" button.'), 'ImportRatecard');

	$CC_help_import_phonebook = create_help(gettext("This section is a utility to import Phonebooks from a CSV file.") . "<br>" .
	gettext('Define the phonebook name, the user to use and the fields that you wish to include from your csv files. Finally, select the csv files and click on the "Import Phonebook" button.'), 'ImportPhoneBook');

	$CC_help_import_ratecard_analyse = create_help(gettext('This is the second step of the import ratecard! <br>') .
	gettext('The first line of your csv files has been read and the values are displayed below according to the fields') .
	gettext('you decided to import on the ratecard! You can check the values and if there are correct,') .
	gettext('please select the same file and click on "Continue to Import the Ratecard" button...'), 'ImportRatecardAnalyse');

	$CC_help_import_ratecard_confirm = create_help(gettext('Ratecard comfirmation page. <br>') .
	gettext('Import results, how many new rates have been imported, and the line numbers of the CSV files that generated errors.'), 'ImportRatecardConfirm');

	$CC_help_trunk_list = create_help(gettext("Trunk List.") . '<br/>' . gettext("Trunks can be modified by clicking the edit button"), 'ListTrunk');

	$CC_help_trunk_edit = create_help(gettext("Trunks are used to terminate the call!<br>") .
	gettext("The trunk and ratecard is selected by the rating engine on the basis of the dialed digits.") .
	gettext("The trunk is used to dial out from your asterisk box which can be a zaptel interface or a voip provider."), 'EditTrunk');

	$CC_help_admin_list = create_help(gettext("Administrators - this shows a list of all the Administrators who have access to the Administrator interface."), 'ShowAdministrator');

	$CC_help_list_log = create_help(gettext("The system log helps you track all events on your application. Log levels are the Importance Levels for the events - 1 is lowest level and 3 is highest level. 1 is used for Login, Logout and Page Visit. 2 is used for Add, Import, Export. 3 is for Update and Delete."), 'SystemLog');

	$CC_help_status_log = create_help(gettext("Status logs help you to keep track of the status of all customers. The status can be 'New, Active, Cancelled, Reserved, Waiting-MailConfirmation and Expired."), 'StatusLog');

	$CC_help_admin_edit = create_help(gettext("Add administrator."), 'EditAdministrator');

	$CC_help_list_voucher = create_help(gettext("Listed below are the vouchers created on the system,.<br/>") .
	gettext("Each row corresponds to a voucher and shows it's status, value and currency..") .
	gettext("Create a single voucher, defining such properties as credit, tag, currency etc, click confirm when finished. <br/> The customer applies voucher credits to their account via the customer interface or via an IVR menu."), 'ListVoucher');

	$CC_help_generate_voucher = create_help(gettext("Bulk generate a batch of vouchers, defining such properties as credit and currency etc, click Generate Vouchers when finished.<br/>The customer applies voucher credit to their account via the customer interface."), 'GenerateVouchers');

	$CC_help_list_service = create_help(gettext("Recurring services that decrement an account at timed intervals."), 'ListRecurringService');

	$CC_help_list_autorefill = create_help(gettext("Auto Refill report."), 'AutoRefillReport');

	$CC_help_edit_service = create_help(gettext("Utility to apply a scheduled action on the account.<br>") .
	gettext("For example if you want to remove 10 cents everyday on each single account, it can be defined here, alternatively, if you now want to remove 1 credit every week but only 7 times on each account, the different rules/parameters below will define this."), 'EditRecurringService');

	$CC_help_list_cidgroup = create_help(gettext("CID Group list. CID can be chosen by customers through the customer interface."), 'ListCIDGroup');

	$CC_help_list_cid = create_help(gettext("Outbound CID list. CID can be added by customers through the customer interface."), 'ListCIDs');

	$CC_help_edit_cidgroup = create_help(gettext("CID group offers customers a group of CID numbers which can be selected for a ratecard for outgoing calls"), 'EditCIDGroup');

	$CC_help_edit_cid = create_help(gettext("Outbound CID offers customers a number which will be selected randomly for a ratecard for outgoing calls"), 'EditCID');

	$CC_help_currency = create_help(gettext("Currency data is automatically updated from Yahoo Financial.") .
	'<br>' . gettext("For more information please visit the website http://finance.yahoo.com.") .
	'<br>' . gettext("The list below is based on your currency :") . ' <b>' . BASE_CURRENCY . '</b>', 'CurrencyList');

	$CC_help_list_didgroup = create_help(gettext("DID (or DDI) Group list. DID can be chosen by customers through the customer interface."), 'ListDIDGroup');

	$CC_help_edit_didgroup = create_help(gettext("DID group offers customers a group of DID numbers which can be selected by the customer"), 'EditDIDGroup');

	$CC_help_list_did = create_help(gettext("DID number list with destinations."), 'ListDID');

	$CC_help_edit_did = create_help(gettext("DID can be assigned to a customer to re-route calls to a SIP/IAX client or a PSTN number. The Priority sets the order in which the calls are to be routed to allow for failover or follow-me."), 'EditDID');

	$CC_help_import_did = create_help(gettext("You can import lists of DIDs using a CSV file."), 'ImportDID');

	$CC_help_list_did_use = create_help(gettext("List the DIDs currently in use with the customer id and their destination number <br/> You can use the search option to show the usage of a given DID or all DIDs"), 'DIDUsage');

	$CC_help_release_did = create_help(gettext("Releasing DID put it in free stat and the user will not be monthly charged any more.."), 'ReleaseDID');

	$CC_help_edit_charge = create_help(gettext("Extra charges allow the billing of one-off or re-occurring monthly charges. These may be used as setup or service charges, etc...") .
	gettext("Charges will appear to the user with the description you attach. Each charge that you create for a user will decrement his account."), 'AddCharge');

	$CC_help_list_did_billing = create_help(gettext("DID list and billing list.") .
	gettext("You will see which customers have used your DIDs in past months and the traffic (amount of seconds)."), 'DIDBilling');

	$CC_help_list_misc = create_help(gettext("Configure the mail template below.") . '<br>' .
	gettext("A Reminder email can be sent (see a2billing.conf) to customers having low credit, a confirmation mail can be sent to customers after their signup, etc..."), 'ShowMailTemplates');

	$CC_help_campaign = create_help(gettext("This section will allow you to create and edit campaigns. A campaign will be attached to a user in order to let him use the predictive dialler option. Predictive dialler will browse all the phone numbers from the campaign and perform outgoing calls."));

	$CC_help_campaign_config = create_help(gettext("This section will allow you to create and edit campaign configs."));

	$CC_help_phonelist = create_help(gettext("Phone Number - here you will find a list of all the phone numbers attached to a campaign. You can add, remove and edit the phone numbers."));

	$CC_help_phonebook = create_help(gettext("A Phonebook is a set of phone numbers. You can add, remove and edit the phonebook. You can also associate phonebooks to a campaign in the Campaign section"));

	$CC_help_provider = create_help(gettext("This section will allow you to create and edit VOIP Providers for reporting purposes. ") .
	gettext("A provider is the company/person that provides you with termination."), 'ListProvider');

	$CC_help_database_restore = create_help(gettext("This section will allow you to restore or download an existing database backup. The restore process will delete the existing database and import the new one. You can also upload a database backup that you previously downloaded (make sure to use the same file format)."), 'DatabaseRestore');

	$CC_help_database_backup = create_help(gettext("This section will allow you to backup an existing database context. The Backup process will export the whole database, so you can restore it later..."), 'DatabaseBackup');

	$CC_help_ecommerce = create_help(gettext("This section will allow you to define the E-Commerce Production Setting.") .
	'<br>' . gettext("This will be used by E-Commerce API to find out how we want the new account to be created."), 'ListE-Product');

	$CC_help_speeddial = create_help(gettext("This section allows you to define the Speed dials for the customer.") . '<br>' .
	gettext("A Speed Dial will be entered on the IVR in order to make a shortcut to their preferred dialled phone number."), 'ListSpeeddial');

	$CC_help_list_prefix = create_help(gettext("Prefix list with destinations."), 'BrowsePrefix');

	$CC_help_edit_prefix = create_help(gettext("Prefixe can be assigned to a Ratecard"), 'EditPrefix');

	$CC_help_edit_alarm = create_help(gettext("Utility to apply a scheduled monitor on trunks.<br>") .
	gettext("For example if you want to monitor ASR (answer seize ratio) or ALOC (average length of call) everyday on each single trunk, it can be defined here, the different parameters below will define the rules to apply the alarm."), 'EditAlarm');

	$CC_help_list_alarm = create_help(gettext("Alarms that monitors trunks at timed intervals."), 'ListAlarm');

	$CC_help_list_monitoring = create_help(gettext("IVR Monitoring, an extension should be configured for the admin to call and monitor through an IVR some important data from your system."), 'ListMonitoring');

	$CC_help_logfile = create_help(gettext("Browse your server log files.") . '<br/>' .
	gettext("This tool can be used to extract and present information from various logfiles."), 'WatchLogFiles');

	$CC_help_callback = create_help(gettext("Callback will offer you an easy way to connect any phone to our Asterisk platform.
Browse here the pending and completed callbacks. You will see that different parameters determine the callback, the way to reach the user, the time when we need to call him, the result of the last attempts, etc..."), 'ShowCallbacks');

	$CC_help_offer_package = create_help(gettext("PACKAGES SYSTEM - FREE MINUTES, etc..."), 'OfferPackage');

	$CC_help_list_subscription = create_help(gettext("SUBSCRIPTION FEE - You can bill the user  in a monthly, weekly or any time period for being subscribed on your service. The fee amount is defined here and the period through the cront configuration."), 'ListSubscription');

	$CC_help_list_subscriber = create_help(gettext("SUBSCRIBER - You can make customers subscribe for any subscription and for a certain time."));

	$CC_help_subscriber_signup = create_help(gettext("SIGNUP SUBSCRIBER - You can make create a list of subscribers that the customers can subscribe."));

	$CC_help_server = create_help(gettext("Servers are used by the callback system through the asterisk manager in order to initiate the callback and outbound calls for your customers. You can add/modify the callback server to be used here. The AGI and callback modes need to be installed on those machines."), 'ShowServer');

	$CC_help_server_group = create_help(gettext("Server Groups define the set of servers that are going to be used by the callback system. A callback is bound to a server group which will be used to dispatch the callback requests."), 'ShowServerGroup');

	$CC_help_transaction = create_help(gettext("You can view all the transactions through the different epayment configured systems (Paypal, MoneyBookers, etc...). "), 'ViewTransactions');

	$CC_help_payment_config = create_help(gettext("You can configure your epayment method here. It helps you to enable or disable the payment method. You can define the currency settings."));

	$CC_help_list_payment_methods = create_help(gettext("Epayment methods help you to collect payments from your customers."));

	$CC_help_add_agi_confx = create_help(gettext("This action will generate agi-conf2 as a global configuration along with a list of all configurations."));

	$CC_help_list_global_config = create_help(gettext("Here is a list of all configuration groups. You can pick one and see its members"));

	$CC_help_list_configuration = create_help(gettext("Here you can see and edit the different A2Billing settings."));

	$CC_help_payment_log = create_help(gettext("Payment log with status, payment methods , owner and creation date."));

	$CC_help_mass_mail = create_help(gettext("Here you can email a message to all of your users. To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients. If you are emailing a large group of people please be patient after submitting and do not stop the page halfway through. It is normal for a mass emailing to take a long time and you will be notified when the script has completed."));

	$CC_help_support_list = create_help(gettext("You can see here, all tickets created. You can also add a new ticket for one customer."));

	$CC_help_support_list_agent = create_help(gettext("You can see here, all tickets created by Agents. You can also add a new ticket for one Agent."));

	$CC_help_support_component = create_help(gettext("Here you can see all components which you use to handle the support task. You can use components to categorise the subject of the ticket. i.e : Tarification, Payment"));

	$CC_help_support_box = create_help(gettext("You can see here the Support Box, you need at least one support box to start support activity."));

	$CC_help_data_archive = create_help(gettext("Here you can archive the data. The Default listing will show you the previous 3 months data. But you can also search the data and archive it."));

	$CC_help_agent = create_help(gettext("Agents - this shows a list of all of the Agents who have access to the Agent interface"), 'ShowAgent');

	$CC_help_signup_agent = create_help(gettext("This shows a list of all signup key create for the Agents, this key is used to identify the default paramater for the subscription on the signup page"));

} //ENDIF SHOW_HELP

$SPOT['PAYPAL'] = '<a href="https://www.paypal.com/en/mrb/pal=PGSJEXAEXKTBU" target="_blank"><img src="' . KICON_PATH . '/paypal_logo.gif" alt="Paypal"/></a>';
$SPOT['MONEYBOOKERS'] = '<a href="https://www.moneybookers.com/app/?rid=811621" target="_blank"><img src="' . KICON_PATH . '/moneybookers.gif" alt="Moneybookers"/></a>';
//$SPOT['AUTHORIZENET'] = '<a href="http://authorize.net/" target="_blank"><img src="'.KICON_PATH.'/authorize.gif" alt="Authorize.net"/></a>';
$SPOT['AUTHORIZENET'] = '';
//$SPOT['WORLDPAY'] = '<a href="http://www.worldpay.com/" target="_blank"><img src="'.KICON_PATH.'/worldpay.gif" alt="worldpay.com"/></a>';
$SPOT['WORLDPAY'] = '';
$SPOT['PLUGNPAY'] = '<a href="http://www.plugnpay.com/" target="_blank"><img src="' . KICON_PATH . '/plugnpay.png" alt="plugnpay.com"/></a>';
$PAYMENT_METHOD = '
<table width="100%" align="center">
	<tr>
		<TD valign="top" align="center" class="tableBodyRight">
			' . $SPOT['PAYPAL'] . '
			&nbsp;&nbsp; &nbsp;
			' . $SPOT['MONEYBOOKERS'] . '
			&nbsp;&nbsp; &nbsp;
			' . $SPOT['AUTHORIZENET'] . '
			&nbsp;&nbsp; &nbsp;
			' . $SPOT['PLUGNPAY'] . '
			&nbsp;&nbsp; &nbsp;
			' . $SPOT['WORLDPAY'] . '
		</td>
	</tr>
</table>';

$CALL_LABS = '
<div align="center">
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
</table>
</div>';

