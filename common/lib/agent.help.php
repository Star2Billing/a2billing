<?php

function help_wiki_link($wiki_article_name)
{
	return gettext("For further information please consult").' <a target="_blank" href="http://trac.asterisk2billing.org/cgi-bin/trac.cgi/wiki/1-4-'.$wiki_article_name.'">'.gettext("the online documention").'</a>.<br/>';
}


if (SHOW_HELP) {

$CC_help_agent ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kdmconfig.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("Agent list who have access to the Agent interface.").'
<br/>'.help_wiki_link('ShowAgent').'<br/>
</div></div></div>
</div></div>';

$CC_help_sipfriend_list ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<div class="w2">
'.gettext("Voip Config will create a SIP or IAX entry on the Asterisk server, so that a customer can set up a SIP or IAX client to connect directly to the asterisk server without the need to enter an account and pin each time a call is made. When done, click on the CONFIRM DATA button, then click reload to apply the changes on the Asterisk server.</br>")
.gettext("The customer must then enter the URL/IP address of the asterisk server into the SIP/IAX client and use the account number and secret as the username and password.").'
<br/>'.help_wiki_link('ListSIPFriend').'
</div></div></div>
</div></div>';


$CC_help_sipfriend_reload ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<div class="w2">
'.gettext("Click reload to commit changes to Asterisk").'<br>
<br/><br/>
</div></div></div>
</div></div>';


$CC_help_generate_signup='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Generate a specific crypted URL and to configure signup with a customer group and call plan.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_password_change ='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	
	<div class="w2">
<table width="90%">
<tr>
<td width="100%">
'.gettext("On this page you will be able to change your password, You have to enter the New Password and Confirm it.").'
<br>&nbsp;
</td>
</tr>
</table>
</div></div></div>
&nbsp;
';

$CC_help_secret_change ='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	
	<div class="w2">
<table width="90%">
<tr>
<td width="100%">
'.gettext("On this page you will be able to change your Secret used to crypt your generated signup URL, You have to enter the New Secret and Confirm it.").'
<br>&nbsp;
</td>
</tr>
</table>
</div></div></div>
&nbsp;
';
	
$CC_help_list_customer='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic" />
	<div class="w2">'.gettext("Customers are listed below by card number. Each row corresponds to one customer, along with information such as their call plan, credit remaining, etc.</br>")
.gettext("The SIP and IAX buttons create SIP and IAX entries to allow direct VoIP connections to the Asterisk server without further authentication.").'
	<br/>'.help_wiki_link('ListCustomers').'
</div></div></div>
</div></div>';

$CC_help_view_payment ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Payment history - The section below allows you to add payments against a customer. Note that this does not change the balance on the card. Click refill under customer list to top-up a card.").'
<br/>'.help_wiki_link('ViewPayments').'
</div></div></div>
</div></div>';

$CC_help_money_situation ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2">
'.gettext("This screen shows refills and payments made against each account, along with the current credit on each card. The initial amount of credit applied to the card is not included. The amount owing is calculated by subtracting payments from refills").'

<br/>'.help_wiki_link('MoneySituation').'
</div></div></div>
</div></div>';

$CC_help_view_refill_agent ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Agents Refill history - The section below allows you to see your refill").'
<br/>'.help_wiki_link('ViewAgentRefill').'
</div></div></div>
</div></div>';

$CC_help_view_payment_agent ='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/gnome-finance.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Agent Payment history - The section below allows you to browse your payments").'
<br/>'.help_wiki_link('ViewPayments').'
</div></div></div>
</div></div>';

$CC_help_support_list='<div class="toggle_show2hide">
<a href="#" target="_self"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/kthememgr.gif" class="kikipic"/>
	<div class="w2" style="width:800px">'
.gettext("You can see here, all tickets created. You can also add a new ticket for one customer.").'
<br/><br/><br/>
</div></div></div>
</div></div>
';

} //ENDIF SHOW_HELP


if (!isset($disable_load_conf) || !($disable_load_conf)) {
	
	$DBHandle = DbConnect();
	$instance_table = new Table();
	$QUERY = "SELECT configuration_key FROM cc_configuration where configuration_key in ('MODULE_PAYMENT_AUTHORIZENET_STATUS','MODULE_PAYMENT_PAYPAL_STATUS','MODULE_PAYMENT_MONEYBOOKERS_STATUS','MODULE_PAYMENT_WORLDPAY_STATUS','MODULE_PAYMENT_PLUGNPAY_STATUS') AND configuration_value='True'";
	$payment_methods  = $instance_table->SQLExec ($DBHandle, $QUERY);
	$show_logo = '';
	for ($index = 0; $index < sizeof($payment_methods); $index++) {
		if( $payment_methods[$index][0] == "MODULE_PAYMENT_PAYPAL_STATUS") {
			$show_logo .= '<a href="https://www.paypal.com/en/mrb/pal=PGSJEXAEXKTBU" target="_blank"><img src="'.KICON_PATH.'/paypal_logo.gif" alt="Paypal"/></a> &nbsp; ';
		} elseif( $payment_methods[$index][0] == "MODULE_PAYMENT_AUTHORIZENET_STATUS") {
			$show_logo .= '<a href="http://authorize.net/" target="_blank"><img src="'.KICON_PATH.'/authorize.gif" alt="Authorize.net"/></a> &nbsp; ';
		} elseif( $payment_methods[$index][0] == "MODULE_PAYMENT_MONEYBOOKERS_STATUS") {
			$show_logo .= '<a href="https://www.moneybookers.com/app/?rid=811621" target="_blank"><img src="'.KICON_PATH.'/moneybookers.gif" alt="Moneybookers"/></a> &nbsp; ';
		} elseif( $payment_methods[$index][0] == "MODULE_PAYMENT_WORLDPAY_STATUS") {
			$show_logo .= '<a href="http://www.worldpay.com/" target="_blank"><img src="'.KICON_PATH.'/worldpay.gif" alt="worldpay.com"/></a> &nbsp; ';
		} elseif( $payment_methods[$index][0] == "MODULE_PAYMENT_PLUGNPAY_STATUS") {
			$show_logo .= '<a href="http://www.plugnpay.com/" target="_blank"><img src="'.KICON_PATH.'/plugnpay.png" alt="plugnpay.com"/></a> &nbsp; ';
		}
	}
	$PAYMENT_METHOD ='<table width="70%" align="center"><tr><TD valign="top" align="center" class="tableBodyRight">'.$show_logo.'</td></tr></table>';
}

