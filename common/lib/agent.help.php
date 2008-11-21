<?php

function help_wiki_link($wiki_article_name)
{
	return gettext("For further information please consult").' <a target="_blank" href="http://trac.asterisk2billing.org/cgi-bin/trac.cgi/wiki/1-4-'.$wiki_article_name.'">'.gettext("the online documention").'</a>.<br/>';
}


if (SHOW_HELP){

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

	
$CC_help_generate_signup='
<div class="toggle_show2hide">
<a href="#" target="_self" class="toggle_menu"><img class="toggle_show2hide" src="'.KICON_PATH.'/toggle_hide2show_on.png" onmouseover="this.style.cursor=\'hand\';" HEIGHT="16"> </a>
<div class="tohide" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/vcard.gif" class="kikipic"/>
	<div class="w2"><br>
'.gettext("Generate a spécific crypted URL and to configure signup with a customer group and call plan.").'
<br/>
</div></div></div>
</div></div>';

$CC_help_password_change ='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/connect_to_network.gif" width="48" height="48" class="kikipic"/>
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

} //ENDIF SHOW_HELP


