<?php

if (SHOW_HELP){

$CC_help_webphone='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/stock_landline-phone.gif" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr>
<td width="50%">'.gettext("From here, you can use the web based screen phone. You need microphone and speakers on your PC.").
'</br></br>
</td>
</tr>
</table>
</div></div></div>';

$CC_help_balance_customer='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/gnome-finance.gif" width="48" height="48" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr>
<td width="100%">

'.gettext("All calls are listed below. Search by month, day or status. Additionally, you can check the rate and price.").' 
<br></br>
</td>
</tr>
</table>
</div></div></div>&nbsp;';

$CC_help_card='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/personal.gif" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr>
<td width="100%">
'.gettext("Personal information.").'<br>
'.gettext("You can update your personal information here.").'<br>
<br></td>
</tr>
</table>
</div></div></div>
';

$CC_help_simulator_rateengine='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/connect_to_network.gif" width="48" height="48" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr>
<td width="100%">
'.gettext("Simulate the calling process to discover the cost per minute of a call, and the number of minutes you can call that number with your current credit.").'
</td>
</tr>
</table>
</div></div></div>
&nbsp;
';

$CC_help_sipiax_info='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/connect_to_network.gif" width="48" height="48" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr>
<td width="100%">'.gettext("Configuration information for SIP and IAX Client. You can simply copy and paste it in your configuration files and can do neccessory modifications.").'<br>
<br></td>
</tr>
</table>
</div></div></div>
&nbsp;
';

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

$CC_help_ratecard ='
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/connect_to_network.gif" width="48" height="48" class="kikipic"/>
	<div class="w2">
<table width="90%"><tr><td width="100%">'.
gettext("Here you can view your ratecards").
'<br>&nbsp;
</td></tr></table>
</div></div></div>
';


$CC_help_list_voucher = '
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/vcard.gif" width="50" height="50" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr height="55px">
<td width="100%">
'.gettext("Enter your voucher number to top up your card.").'
<br>&nbsp;
</td>
</tr>
</table>
</div></div></div>
';


$CC_help_list_did = '
<div id="div1000" style="display:visible;">
<div id="kiblue_header"><div class="w4">
	<img src="'.KICON_PATH.'/vcard.gif" width="50" height="50" class="kikipic"/>
	<div class="w2">
<table width="90%">
<tr height="55px">
<td width="100%">
'.gettext("Select the country below where you would like a DID, select a DID from the list and enter the destination you would like to assign it to.").'
<br>&nbsp;
</td>
</tr>
</table>
</div></div></div><br>
';


$CC_help_release_did ='
<a href="#" target="_self"  onclick="imgidclick(\'img1000\',\'div1000\',\'help.png\',\'viewmag.png\');"><img id="img1000" src="'.KICON_PATH.'/viewmag.png" onmouseover="this.style.cursor=\'hand\';" WIDTH="16" HEIGHT="16"></a>
<div id="div1000" style="display:visible;">
<div id="kiki"><div class="w1">
	<img src="'.KICON_PATH.'/connect_to_network.gif" class="kikipic"/>
	<div class="w2">
	<br/>'
	.gettext("WARNING !  <br> after confirmation, the release of the did will be done immediately and you will not be monthly charged any more.").'<br/>

<br/>
</div></div></div>
</div>';

} //ENDIF SHOW_HELP


$SPOT['PAYPAL'] 		= '<a href="https://www.paypal.com/es/mrb/pal=PGSJEXAEXKTBU" target="_blank"><img src="'.KICON_PATH.'/paypal_logo.gif" alt="Paypal"/></a>';
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
