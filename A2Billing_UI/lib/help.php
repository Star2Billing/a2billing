<?php

if (SHOW_HELP){
	
$CC_help_invoices_unbilled='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic" />
	<div class="w2">'.gettext("This allows you to see the outstanding of each customer and to mail it.").'
	<br/>
</div></div></div>
</div></div>';

$CC_help_invoices_card='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic" />
	<div class="w2">'.gettext("This page allows you to see the money situation of each client.</br>")
.gettext("You can what invoices were billed for them and see the payments they made").'
	<br/>
</div></div></div>
</div></div>';
	
$CC_help_invoices_period='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic" />
	<div class="w2">'.gettext("This page allows you to view and send billed invoices.</br>")
.gettext("You can filter on date, customer and choose in which format you want to see them").'
	<br/>
</div></div></div>
</div></div>';

$CC_help_bill_invoice='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic" />
	<div class="w2">'.gettext("This page allows you to store and email invoices for a range of customers.</br>")
.gettext("You can choose if you want to mail this bill and wether you want to count charges and calls or not ").'
	<br/>
</div></div></div>
</div></div>';

$CC_help_list_customer='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic" />
	<div class="w2">'.gettext("Customers are listed below by card number. Each row corresponds to one customer, along with information such as their call plan, credit remaining, etc.</br>")
.gettext("The SIP and IAX buttons create SIP and IAX entries to allow direct VoIP connections to the Asterisk server without further authentication.").'
	<br/>
</div></div></div>
</div></div>';

$CC_help_refill_customer='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/pipe.gif" class="kikipic"/>
	<div class="w2">
'.gettext("Top up cards on this screen by selecting or typing in the card number directly, and enter the amount of credit to apply, then click ADD to confirm.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_create_customer='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Create and edit the properties of each customer. Click <b>CONFIRM DATA</b> at the bottom of the page to save changes.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_generate_customer='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2">
'.gettext("Bulk create cards and customers in a single step. <br> Set the properties of the batch such as initial credit, card type and currency, then click on the GENERATE CARDS button to create the batch.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_sipfriend_list ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/network_local.gif" class="kikipic"/>
	<div class="w2">
'.gettext("SIP and IAX friend will create a SIP or IAX entry on the Asterisk server, so that a customer can set up a SIP or IAX client to connect directly to the asterisk server without the need to enter an account and pin each time a call is made. When done, click on the CONFIRM DATA button, then click reload to apply the changes on the Asterisk server.</br>")
.gettext("The customer must then enter the URL/IP address of the asterisk server into the SIP/IAX client, and use the Card Number and Secret as the username and password.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_sipfriend_reload ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/network_local.gif" class="kikipic"/>
	<div class="w2">
'.gettext("Click reload to commit changes to Asterisk").'<br>
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_sipfriend_edit ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/network_local.gif" class="kikipic"/>
	<div class="w2">

'.gettext("Each SIP/IAX client is identified by a number of paremeters.</br></br>")
.gettext("More details on how to configure clients are on the Wiki").' -> <a href="http://voip-info.org/wiki-Asterisk+config+sip.conf" target="_blank">sip.conf</a> &
<a href="http://voip-info.org/wiki-Asterisk+config+iax.conf" target="_blank">iax.conf</a>
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_callerid_list ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
  <img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
  <div class="w2"> 
'.gettext("Set the caller ID so that the customer calling in is authenticated on the basis of the callerID rather than with account number").'<br>
<br/><br/>
</div></div></div>
</div></div>';


$CC_help_money_situation ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2">
'.gettext("This screen shows refills and payments made against each account, along with the current credit on each card. The initial amount of credit applied to the card is not included. The amount owing is calculated by subtracting payments from refills").'

<br/>
</div></div></div>
</div></div>';

$CC_help_view_payment ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Payment history - The section below allows you to add payments against a customer. Note that this does not change the balance on the card. Click refill under customer list to top-up a card.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_view_paypal ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/paypal.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Paypal History - The section below shows all paypal receipts.").'
<a href="https://www.paypal.com/en/mrb/pal=PGSJEXAEXKTBU">PayPal</a>
<br/><br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_add_payment ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2"><br/>
&nbsp; &nbsp;'.gettext("Add payments to a customer's account!").'
<br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_list_tariffgroup ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/network.gif" class="kikipic"/>
	<div class="w2"><br/>'.gettext("List of Call Plans, a Call Plan is a collection of rate cards, click edit to add ratecards to the Call Plan").'
<br/><br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_add_tariffgroup ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/network.gif" class="kikipic"/>
	<div class="w2"> 
'.gettext("A Call Plan is a collection of ratecards.")
.gettext("The calling card system will choose the most appropriate rate according to the Call Plan settings (LCR or LCD).<br/>")
.gettext("LCR : Least Cost Routing - Find the trunk with the cheapest carrier cost. (buying rate)<br>")
.gettext("LCD : Least Cost Dialing - Find the trunk with the cheapest retail rate (selling rate)").'
<br/>
</div></div></div>
</div></div>';

$CC_help_list_ratecard ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread_ksp.gif" class="kikipic"/>
	<div class="w2"><br/> '.gettext("List ratecards that have been created!<br>Ensure that the rate card is added into the call plan under 'List Ratecard'").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_edit_ratecard ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread_ksp.gif" class="kikipic"/>
	<div class="w2"> '.gettext("Set the properties and attributes of the ratecard").'<br/>'
	.gettext("A ratecard is set of rates defined and applied according to the dialing prefix, for instance 441 & 442 : UK Landline.").'<br>'
	.gettext("Each ratecard may have has many rates as you wish, however, if a dialing prefix cannot be matched when a call is made, then the call will be terminated.").'<br>'
	.gettext('A ratecard has a "start date", an "expiry date" and a you can define a default trunk, but if no trunk is defined, the ratecard default trunk will be used.').'
<br/>
</div></div></div>
</div></div>';

$CC_help_def_ratecard ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread_ksp.gif" class="kikipic"/>
	<div class="w2"> </br>'.gettext("Please select a ratecard and click on search to browse the different rates/dialing prefix of the selected ratecard.").'<br/>

<br/>
</div></div></div>
</div></div>';

$CC_help_sim_ratecard ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread_ksp.gif" class="kikipic"/>
	<div class="w2"><br>'.gettext('Please select a calling card, enter the number you wish to call and press the "SIMULATE" button.').'<br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_add_rate ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread_ksp.gif" class="kikipic"/>
	<div class="w2"><br>'
    .gettext("Please fill in the fields below to set up the rate for each destination.").'
<br><br>
</div></div></div>
</div></div>';

$CC_help_import_ratecard ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/spreadsheet.gif" class="kikipic"/>
	<div class="w2">'
    .gettext("This section is a utility to import ratecards from a CSV file.<br>")
	.gettext('Define the ratecard name, the trunk to use and the fields that you wish to include from your csv files. Finally, select the csv files and click on the "Import Ratecard" button.').'
	<br>
	<br>
</div></div></div>
</div></div>';

$CC_help_import_ratecard_analyse ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/spreadsheet.gif" class="kikipic"/>
	<div class="w2">'
    .gettext('This is the second step of the import ratecard! <br>')
	.gettext('The first line of your csv files has been read and the values are displayed below according to the fields')
	.gettext('you decided to import on the ratecard! You can check the values and if there are correct,')
    .gettext('please select the same file and click on "Continue to Import the Ratecard" button...').'
	<br><br>
</div></div></div>
</div></div>';

$CC_help_import_ratecard_confirm ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/spreadsheet.gif" class="kikipic"/>
	<div class="w2">.'
    .gettext('Ratecard comfirmation page. <br>')
	.gettext('Import results, how many new rates have been imported, and the line numbers of the CSV files that generated errors.').'
	<br><br>
</div></div></div>
</div></div>';

$CC_help_trunk_list ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/hwbrowser.gif" class="kikipic"/>
	<div class="w2">
'.gettext("Trunk List.").'
 <br/>
'.gettext("Trunks can be modified by clicking the edit button").'
 <br/>
 
<br/>
</div></div></div>
</div></div>';

$CC_help_trunk_edit ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/hwbrowser.gif" class="kikipic"/>
	<div class="w2">
'.gettext("Trunks are used to terminate the call!<br>")
.gettext("The trunk and ratecard is selected by the rating engine on the basis of the dialed digits.")
.gettext("The trunk is used to dial out from your asterisk box which can be a zaptel interface or a voip provider.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_admin_list ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kdmconfig.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Administrator list who have access to the calling card administrative interface.").'
<br/><br/>
</div></div></div>
</div></div>';
$CC_help_list_log ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kdmconfig.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("System log help you to keep track and event all event happening in your application. Log Level are the Importance Levels for the Events which are logged. '1' is lowest level and '3' is highest level. 1 if for Login, Logout, Page Visit, 2 if for Add, Import, Export. and 3 is for update and Delete.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_status_log ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kdmconfig.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Status log help you to keep track of all status of customers. These status can be 'New, Active, Cancelled, Reserved, Waiting-MailConfirmation and Expired.").'
<br/><br/>
</div></div></div>
</div></div>';


$CC_help_admin_edit ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kdmconfig.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("Add administrator.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_list_voucher='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("Listed below are the vouchers created on the system,.<br/>")
.gettext("Each row corresponds to a voucher and shows it's status, value and currency..").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_create_voucher='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2">
<br/>'
.gettext("Create a single voucher, defining such properties as credit, tag, currency etc, click confirm when finished. <br/> The customer applies voucher credit to their card via the customer interface or via an IVR menu.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_generate_voucher='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Bulk generate a batch of vouchers, defining such properties as credit and currency etc, click Generate Vouchers when finished.<br/>The customer applies voucher credit to their card via the customer interface. ").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_list_service ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/system-config-date.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Re-occuring services that decrement a card at timed intervals.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_edit_service ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/system-config-date.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Utility to apply a scheduled action on the card.<br>")
.gettext("For example if you want to remove 10 cents everyday on each single card, it can be defined here, alternatively, if you now want to remove 1 credit every week but only 7 times on each card, the different rules/parameters below will define this.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_list_cidgroup ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>
	'.gettext("CID Group list. CID can be chosen by customers through the customer interface.").'<br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_list_cid ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>
	'.gettext("Outbound CID list. CID can be added by customers through the customer interface.").'<br/>
<br/>
</div></div></div>
</div></div>';


$CC_help_edit_cidgroup ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
<br/>'
.gettext("CID group offers customers a group of CID numbers which can be selected for a ratecard for outgoing calls").'<br>
<br/>
</div></div></div>
</div></div>';

$CC_help_edit_cid ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
<br/>'
.gettext("Outbound CID offers customers a number which will be selected randomly for a ratecard for outgoing calls").'<br>
<br/>
</div></div></div>
</div></div>';

$CC_help_currency ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/favorites.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Currency data are automaticaly updated from Yahoo Financial.")
.gettext("<br>For more information please visite the website http://finance.yahoo.com.")
.gettext("<br>The list below is based over your currency base :").' <b>'.BASE_CURRENCY.'</b>'
.'<br>
</div></div></div>
</div></div>';

$CC_help_list_didgroup ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>
	'.gettext("DID (or DDI) Group list. DID can be chosen by customers through the customer interface.").'<br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_edit_didgroup ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
<br/>'
.gettext("DID group offers customers a group of DID numbers which can be selected by the customer").'<br>
<br/>
</div></div></div>
</div></div>';

$CC_help_list_did ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("DID number list with destinations.").'<br/>

<br/>
</div></div></div>
</div></div>';

$CC_help_edit_did ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
<br/>'
.gettext("DID can be assigned to a card to re-route calls to a SIP/IAX client or a PSTN number. The Priority sets the order in which the calls are to be routed to allow for failover or follow-me.").'<br>
</div></div></div>
</div></div>';

$CC_help_list_did_use ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("List the DIDs currently in use with the card id and their destination number <br/> You can use the search option to show the usage of a given DID or all DIDs").'<br/>
</div></div></div>
</div></div>';

$CC_help_release_did ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Releasing DID put it in free stat and the user will not be monthly charged any more..").'<br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_edit_charge ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/wi0124-48.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Extra Charges are to allow the billing of one-off or re-occurring monthly charges. These may be used as setup or service charges, etc...")
.gettext("Charges will appear to the user with the description you attach. Each charge that you create for a user will decrement his account.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_list_did_billing ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">'
	.gettext("DID list and billing list. ")
    .gettext("You will see which cards have used your DIDs in past months and the traffic (amount of seconds).").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_list_misc ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kate.gif" class="kikipic"/>
	<div class="w2">
		'.gettext("The MISC module allow new customers to register automatically and use the system immediately.")
    	.gettext(' Click here <a target="_blank" href="../signup/"><b>Signup Pages</b></a> to access the signup page.')
    	.gettext(" A mail is automatically sent when a new signup is completed. Configure the mail template below.<br>")
    	.gettext("A Reminder email can be sent (see a2billing.conf) to customers having low credit.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_campaign ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/yast_remote.gif" class="kikipic"/>
	<div class="w2">'
	.gettext("This section will allow you to create and edit campaign. ")
    .gettext("A campaign will be attached to a user in order to let him use the predictive-dialer option. ")
    .gettext("Predictive dialer will browse all the phone numbers from the campaign and perform outgoing calls.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_phonelist ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/yast_PhoneTTOffhook.gif" class="kikipic"/>
	<div class="w2"><br/>'
	.gettext("Phonelist are all the phone numbers attached to a campaign. You can add, remove and edit the phone numbers.").'
	<br/><br/>
</div></div></div>
</div></div>';

$CC_help_provider ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/yast_remote.gif" class="kikipic"/>
	<div class="w2"><br/>'
	.gettext("This section will allow you to create and edit VOIP Providers for reporting purposes. ")
    .gettext("A provider is the company/person that provides you with termination.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_database_restore ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
<img src="'.KICON_PATH.'/yast_HD.gif" class="kikipic"/>
        <div class="w2">'
        .gettext("This section will allow you to restore or download an existing database backup. ")
        .gettext("The restore proccess will delete the existing database and import the new one ...")
        .gettext("Also you can upload a database backup that you previously downloaded , ")
        .gettext("but be sure that is correct and use the same file format.")
        .gettext("The process of restore can take some time , during that time no calls will be accepted.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_database_backup='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
<img src="'.KICON_PATH.'/yast_HD.gif" class="kikipic"/>
        <div class="w2">'
		.gettext("This section will allow you to backup an existing database context. ")
		.gettext("Backup proccess will export whole database , so you can restore later... <br/>")
		.gettext("The process of backup can take some time, during that time some calls will not be accepted.").'
		<br/>
</div></div></div>
</div></div>';
				
$CC_help_ecommerce ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/yast_multihead.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("This section will allow you to define the E-Commerce Production Setting.")
	.gettext("<br>This will be use by E-Commerce API to find out how the new card have to be created.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_speeddial ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/stock_init.gif" class="kikipic"/>
	<div class="w2">'
	.gettext("This section allows you to define the Speed dials for the customer. <br>")
	.gettext("A Speed Dial will be entered on the IVR in order to make a shortcut to their preferred dialed phone number.").'
<br/><br/>
</div></div></div>
</div></div>';

$CC_help_list_prefix='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Prefix list with destinations.").'<br/>
<br/>
</div></div></div>
</div></div>';

$CC_help_edit_prefix ='
<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
<br/>'
.gettext("Prefixe can be assigned to a Ratecard").'<br>
<br/>
</div></div></div>
</div></div>';

$CC_help_edit_alarm='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/system-config-date.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Utility to apply a scheduled monitor on trunks.<br>")
.gettext("For example if you want to monitor ASR (answer seize ratio) or ALOC (average length of call) everyday on each single trunk, it can be defined here, the different parameters below will define the rules to apply the alarm.").'
<br/>
</div></div></div>
</div></div>
';

$CC_help_list_alarm='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/system-config-date.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("Alarms that monitors trunks at timed intervals.").'
<br/><br>
</div></div></div>
</div></div>
';


$CC_help_logfile='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/cache.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Browse for log file.<br> Use to locate the log file on a remote Web server.<br>It can generate combined reports for all logs. This tool can be use for extraction and presentation of information from various logfiles.").'
<br/>
</div></div></div>
</div></div>
';

$CC_help_callback='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/cache.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Callback will offer you an easy way to connect any phone to our Asterisk platform.
We handle a spool with all the callbacks that need to be running and you might be able to view here all the pending and performed callback with their current status. Different parameters determine the callback, the way to reach the user, the time when we need to call him, the result of the last attempts, etc...").'
<br/>
</div></div></div>
</div></div>
';

$CC_help_offer_package='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kthememgr.gif" class="kikipic"/>
	<div class="w2">'
.gettext("PACKAGES SYSTEM - FREE MINUTES, etc...").'
<br/><br/><br/>
</div></div></div>
</div></div>
';

$CC_help_list_subscription='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/config-date.gif" class="kikipic"/>
	<div class="w2">'
.gettext("SUBSCRITION FEE - You can bill in a monthly, weekly or anytime period the user for being subscribed on your service. The fee amount is defined here and the period through the cront configuration.").'
<br/><br/><br/>
</div></div></div>
</div></div>
';

$CC_help_list_subscriber='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/config-date.gif" class="kikipic"/>
	<div class="w2">'
.gettext("SUSCRIBER - You can make customers subscribe for any subscription and for a certain time.").'
<br/><br/><br/>
</div></div></div>
</div></div>
';

$CC_help_server='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/network_local.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Server are used by the callback system through the asterisk manager in order to initiate the callback and outbound a call to your customers. You can add/modify the callback server here that are going to be use here. The AGI and callback mode need to be install on those machine.").'
<br/>
</div></div></div>
</div></div>
';

$CC_help_server_group='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/yast_multihead.gif" class="kikipic"/>
	<div class="w2">'
.gettext("Group of server define the set of servers that are going to be used by the callback system. A callback is bound to a group of server, those server will be used to dispatch the callback requests.").'
<br/><br/>
</div></div></div>
</div></div>
';

$CC_help_transaction='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread.gif" class="kikipic"/>
	<div class="w2">'
.gettext("You can view all the transactions proceed through the different epayment system configured (Paypal, MoneyBookers, etc...). ").'
<br/><br/></br>
</div></div></div>
</div></div>
';

$CC_help_payment_config ='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("You can configure your epayment method here. It helps you to enable or disable the payment method. You can define the currency settings.").'
<br/><br/>
</div></div></div>
</div></div>
';
$CC_help_list_payment_methods = '<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kspread.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("Epayment methods help you to collect payments from your customers.").'
<br/><br/><br>
</div></div></div>
</div></div>
'; 

$CC_help_add_agi_confx = '<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("This action will generate agi-conf2 as a global configuration along with all list of configurations.").'
<br/><br/><br>
</div></div></div>
</div></div>
'; 

$CC_help_payment_log = '<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("Payment log with status, payment methods , owner and creation date.").'
<br/><br/><br>
</div></div></div>
</div></div>
'; 

$CC_help_mass_mail = '<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2"><br>'
.gettext("Here you can email a message to all of your users. To do this, an email will be sent out to the administrative email address supplied, with a blind carbon copy sent to all recipients. If you are emailing a large group of people please be patient after submitting and do not stop the page halfway through. It is normal for a mass emailing to take a long time and you will be notified when the script has completed.").'
<br/><br/><br>
</div></div></div>
</div></div>
'; 

} //ENDIF SHOW_HELP

$SPOT['PAYPAL'] 		= '<a href="https://www.paypal.com/en/mrb/pal=PGSJEXAEXKTBU" target="_blank"><img src="'.KICON_PATH.'/paypal_logo.gif" alt="Paypal"/></a>';
$SPOT['MONEYBOOKERS'] 	= '<a href="https://www.moneybookers.com/app/?rid=811621" target="_blank"><img src="'.KICON_PATH.'/moneybookers.gif" alt="Moneybookers"/></a>';
$SPOT['AUTHORIZENET'] 	= '<a href="http://authorize.net/" target="_blank"><img src="'.KICON_PATH.'/authorize.gif" alt="Authorize.net"/></a>';

$PAYMENT_METHOD ='
<table width="70%" align="center">
	<tr>
		<TD valign="top" align="center" class="tableBodyRight">
			'.$SPOT['PAYPAL'].'
			&nbsp;&nbsp; &nbsp; 
			'.$SPOT['MONEYBOOKERS'].'
			&nbsp;&nbsp; &nbsp;
			'.$SPOT['AUTHORIZENET'].'
		</td>
	</tr>
</table>';


$CALL_LABS ='
<table width="70%" align="center">
	<tr>
		<TD width="%75" valign="top" align="center" class="tableBodyRight" background="'.Images_Path.'/background_cells.gif" >
				Global VoIP termination (A-Z)  to over 400 worldwide destinations!<br>
				Visit Call-Labs at <a href="http://www.call-labs.com/" target="_blank">http://www.call-labs.com/</a><br/>
		</TD>
		<TD width="%25" valign="middle" align="center" class="tableBodyRight" background="'.Images_Path.'/background_cells.gif" >
				<a href="http://www.call-labs.com/" target="_blank"><img src="'.Images_Path.'/call-labs.com.png" alt="call-labs"/></a>
		</TD>
	</tr>
</table>';


?>
