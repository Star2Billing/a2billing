{include file="header.tpl"}
{if ($popupwindow == 0)}
<div id="left-sidebar">
<div id="leftmenu-top">
<div id="leftmenu-down">
<div id="leftmenu-middle">

<ul id="nav">

	{if ($ACXMYACCOUNT > 0) }
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "0")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" >&nbsp; <strong>{php} echo gettext("MY ACCOUNT");{/php}</strong></a></div></li>
	<div class="tohide"
	{if ($section =="0")}
	style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="agentinfo.php?section=0">{php} echo gettext("Account information");{/php}</a></li>
				<li><a href="A2B_entity_password.php?stitle=Password&section=0">{php} echo gettext("Password");{/php}</a></li>
		</ul></li>
	</ul>
	</div>
	{/if}
	
	{if ($ACXCUSTOMER > 0) }
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "1")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" >&nbsp; <strong>{php} echo gettext("CUSTOMERS");{/php}</strong></a></div></li>
	<div class="tohide"
	{if ($section =="1")}
	style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1">{php} echo gettext("List Customers");{/php}</a></li>
				{if ($ACXCALLREPORT > 0) }
				<li><a href="card-history.php?atmenu=cardhistory&stitle=Card+History&section=1">{php} echo gettext("Card History");{/php}</a></li>
				{/if}
				{if ($ACXVOIPCONF > 0) }
				<li><a href="A2B_entity_friend.php?section=1">{php} echo gettext("VOIP Config");{/php}</a></li>
				{/if}
		</ul></li>
	</ul>
	</div>
	{/if}
	
	
	{if ($ACXSIGNUP > 0) }
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "8")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" >&nbsp; <strong>{php} echo gettext("SIGNUP");{/php}</strong></a></div></li>
	<div class="tohide"
	{if ($section =="8")}
	style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="A2B_signup_agent.php?atmenu=generatesignup&stitle=Signup&section=8">{php} echo gettext("Generate Signup Url");{/php}</a></li>
				<li><a href="A2B_entity_secret.php?atmenu=changesecret&stitle=Card+History&section=8">{php} echo gettext("Change Secret");{/php}</a></li>
		</ul></li>
	</ul>
	</div>
	{/if}



	{if ($ACXBILLING > 0)}
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img2"
	{if ($section =="2")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	 onmouseover="this.style.cursor='hand';" >&nbsp; <strong>{php} echo gettext("BILLING");{/php}</strong></a></div></li>
	<div class="tohide"
	{if ($section =="2")}
	style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_moneysituation.php?atmenu=moneysituation&section=2">{php} echo gettext("Account balance");{/php}</a></li>
				<li><a href="A2B_entity_logrefill_agent.php?atmenu=payment&section=2">{php} echo gettext("Own Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment_agent.php?atmenu=payment&section=2">{php} echo gettext("Own Payments");{/php}</a></li>
				<li><a href="A2B_entity_logrefill.php?atmenu=payment&section=2">{php} echo gettext("Customer's Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment.php?atmenu=payment&section=2">{php} echo gettext("Customer's Payment");{/php}</a></li>
				<li><a href="A2B_entity_paymentlog.php?stitle=Payment_log&section=2">{php} echo gettext("Payment Log");{/php}</a></li>
				<li><a href="A2B_entity_commission.php?stitle=Commission&section=2">{php} echo gettext("Commission");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXRATECARD > 0)}
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img3"
	{if ($section =="3")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	  onmouseover="this.style.cursor='hand';" > &nbsp;<strong>{php} echo gettext("RATECARD");{/php}</strong></a></div></li>
		<div class="tohide"
	{if ($section =="3")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_def_ratecard.php?atmenu=ratecard&stitle=RateCard&section=3">{php} echo gettext("Browse Rates");{/php} </a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXCALLREPORT > 0)}
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img5"
	{if ($section == "6")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" > &nbsp;<strong>{php} echo gettext("CALL REPORT");{/php}</strong></a></div></li>
		<div class="tohide"
	{if ($section =="6")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
					<li><a href="call-log-customers.php?stitle=Call_Report_Customers&nodisplay=1&posted=1&section=6">{php} echo gettext("CDR Report");{/php}</a></li>
					<li><a href="call-last-month.php?section=6">{php} echo gettext("Monthly Traffic");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}
	
	{if ($ACXSUPPORT  > 0)}
	<li>
	<div class="toggle_menu">
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img8"
	{if ($section == "7")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" >&nbsp; <strong>{php} echo gettext("SUPPORT");{/php}</strong></a></div></li>
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


<table width="100%">
<tr>
	<td>
		<a href="PP_intro.php?language=english" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/gb.gif" border="0" title="English" alt="English"></a>
		<a href="PP_intro.php?language=brazilian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
		<a href="PP_intro.php?language=spanish" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
		<a href="PP_intro.php?language=romanian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/ro.gif" border="0" title="Romanian"alt="Romanian"></a>
		<a href="PP_intro.php?language=french" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/fr.gif" border="0" title="French" alt="French"></a>
	</td>
</tr>
</table>


</div>

<div id="main-content">
<br/>

{else}
<div>
{/if}
