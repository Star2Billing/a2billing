{include file="header.tpl"}


{if ($popupwindow == 0)}
<div id="left-sidebar">
<div id="leftmenu-top">
<div id="leftmenu-down">
<div id="leftmenu-middle">

<ul id="nav">
	<li>
	<a href="PP_intro.php" target="_top"><img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/house.png"> <b>&nbsp;&nbsp;{php} echo gettext("HOME");{/php}</b> </a>
	</li>
	{if ($ACXMYACCOUNT > 0) }
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "0")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" >&nbsp; <strong>{php} echo gettext("MY ACCOUNT");{/php}</strong></a></li></div>
	<div class="tohide"
	{if ($section =="4")}
	style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="agentinfo.php?section=4">{php} echo gettext("Account information");{/php}</a></li>
				<li><a href="A2B_entity_password.php?section=4">{php} echo gettext("Password");{/php}</a></li>
				<li><a href="A2B_entity_remittance_request.php?section=4">{php} echo gettext("Historic Remittance");{/php}</a></li>
		</ul></li>
	</ul>
	</div>
	{/if}

	{if ($ACXCUSTOMER > 0) }
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img1"
	{if ($section == "1")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("CUSTOMERS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="1")}
		style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="A2B_entity_card.php?section=1">{php} echo gettext("List Customers");{/php}</a></li>
				<li><a href="A2B_entity_callerid.php?section=1">{php} echo gettext("Caller-ID");{/php}</a></li>
				{if ($ACXCALLREPORT > 0) }
				<li><a href="card-history.php?section=1">{php} echo gettext("Card History");{/php}</a></li>
				{/if}
				{if ($ACXVOIPCONF > 0) }
				<li><a href="A2B_entity_friend.php?section=1">{php} echo gettext("VOIP Config");{/php}</a></li>
				{/if}
		</ul></li>
	</ul>
	</div>
	{/if}


	{if ($ACXSIGNUP > 0) }
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img8"
	{if ($section == "8")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("SIGNUP");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="8")}
		style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="A2B_entity_signup_agent.php?section=8">{php} echo gettext("Signup Url List");{/php}</a></li>
				<li><a href="A2B_signup_agent.php?section=8">{php} echo gettext("Add New Signup Url");{/php}</a></li>
		</ul></li>
	</ul>
	</div>
	{/if}


	{if ($ACXBILLING > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img2"
	{if ($section == "2")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("BILLING");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="2")}
		style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_moneysituation.php?section=2">{php} echo gettext("Account balance");{/php}</a></li>
				<li><a href="A2B_entity_logrefill_agent.php?section=2">{php} echo gettext("Own Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment_agent.php?section=2">{php} echo gettext("Own Payments");{/php}</a></li>
				<li><a href="A2B_entity_logrefill.php?section=2">{php} echo gettext("Customer's Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment.php?section=2">{php} echo gettext("Customer's Payment");{/php}</a></li>
				<li><a href="A2B_entity_paymentlog.php?section=2">{php} echo gettext("Payment Log");{/php}</a></li>
				<li><a href="A2B_entity_commission.php?section=2">{php} echo gettext("Commission");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXRATECARD > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img3"
	{if ($section == "3")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("RATECARD");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="3")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_def_ratecard.php?section=3">{php} echo gettext("Browse Rates");{/php} </a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXCALLREPORT > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img6"
	{if ($section == "6")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("CALL REPORT");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="6")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
					<li><a href="call-log-customers.php?nodisplay=1&posted=1&section=6">{php} echo gettext("CDR Report");{/php}</a></li>
					<li><a href="call-last-month.php?section=6">{php} echo gettext("Monthly Traffic");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXSUPPORT  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img7"
	{if ($section == "7")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("SUPPORT");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="7")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_ticket.php?section=7">{php} echo gettext("Customer Tickets");{/php}</a></li>
				<li><a href="A2B_support.php">{php} echo gettext("View and Create Tickets");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


</ul>
</br>
<ul id="nav">
	<li>
	<a href="logout.php?logout=true" target="_top"><img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/logout.png"> <font color="#DD0000"><b>&nbsp;&nbsp;{php} echo gettext("LOGOUT");{/php}</b></font> </a>
	</li>
</ul>

</div>
</div>
</div>


<table width="100%" cellspacing="15">
<tr>
	<td>
		<a href="PP_intro.php?ui_language=english" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/gb.gif" border="0" title="English" alt="English"></a>
		<a href="PP_intro.php?ui_language=brazilian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
		<a href="PP_intro.php?ui_language=romanian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/ro.gif" border="0" title="Romanian" alt="Romanian"></a>
		<a href="PP_intro.php?ui_language=french" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/fr.gif" border="0" title="French" alt="French"></a>
		<a href="PP_intro.php?ui_language=spanish" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
		<a href="PP_intro.php?ui_language=greek" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/gr.gif" border="0" title="Greek" alt="Greek"></a>
		<a href="PP_intro.php?ui_language=italian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/it.gif" border="0" title="Italian" alt="Italian"></a>
		<a href="PP_intro.php?ui_language=chinese" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/cn.gif" border="0" title="Chinese" alt="Chinese"></a>
	</td>
</tr>
</table>

<div id="osx-modal-content">
	<div id="osx-modal-title">Dear A2Billing Administrator</div>
	<div id="osx-modal-data">
		<h2>Licence Violation!</h2>
		<p>Thank you for using A2Billing. However, we have detected that you have edited the Author’s names, Copyright or licensing information in the A2Billing Management Interface.</p>
		<p>The <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target="_blank">AGPL 3</a> license under which you are allowed to use A2Billing requires that the original copyright and license must be displayed and kept intact. Without this information being displayed, you do not have a right to use the software.</p>
		<p>However, if it is important to you that the Author’s names, Copyright and License information is not displayed, possibly for publicity purposes; then we can offer you additional permissions to use and convey A2Billing, with these items removed, for a fee that will be used to help sponsor the continued development of A2Billing.</p>
		<p>For more information, please go to <a target="_blank" href="http://www.asterisk2billing.org/pricing/rebranding/">http://www.asterisk2billing.org/pricing/rebranding/</a>.</p>
		<p>Yours,<br/>
		The A2Billing Team<br/>
		Star2Billing S.L</p>
		<p><button class="simplemodal-close">Close</button></p>
	</div>
</div>


</div>

<div id="main-content">
<br/>
{else}
<div>
{/if}

{if ($LCMODAL  > 0)}
<script type="text/javascript">
    loadLicenceModal();
</script>
{/if}

{$MAIN_MSG}

