{include file="header.tpl"}


{if ($popupwindow == 0)}
	<div id="top_menu">
		<ul id="menu_horizontal">
			<li class="topmenu-left-button" style="border:none;">
				<div style="width:100%;height:100%;text-align:center;" >
					<a href="PP_intro.php">
							<strong> {php} echo gettext("HOME");{/php}</strong>&nbsp;
						<img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/house.png">
					</a>
				</div>
			</li>
			{if ($ACXDASHBOARD > 0) }
			<li class="topmenu-left-button" >
				<div style="width:100%;height:100%;text-align:center;" >
					<a href="dashboard.php" >
						<strong> {php} echo gettext("DASHBOARD");{/php}</strong>&nbsp;
						<img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/chart_bar.png">
					</a>
				</div>
			</li>
			{/if}
			<li class="topmenu-left-button">
				<div style="width:100%;height:100%;text-align:center;" >
					 <a href="A2B_notification.php" >
						<strong > {php} echo gettext("NOTIFICATION");{/php}</strong>&nbsp;
					<img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/email.png">
					{if ($NEW_NOTIFICATION > 0) }
						<strong style="font-size:8px; color:red;"> NEW</strong>
					{else}
						<strong style="font-size:8px;">&nbsp;</strong>
					{/if}
					  </a>
				</div>
			</li>
			<li class="topmenu-right-button" style="border-right:none;">
				<div style="width:90%;height:100%;text-align:center;" >
					<a href="logout.php?logout=true" target="_top"><font color="#EC3F41"><b>&nbsp;&nbsp;{php} echo gettext("LOGOUT");{/php}</b></font>
					<img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/logout.png"> </a>
				</div>
			</li>
		</ul>

	</div>

{/if}

{if ($popupwindow == 0)}
<div id="left-sidebar">
<div id="leftmenu-top">
<div id="leftmenu-down">
<div id="leftmenu-middle">

<ul id="nav">

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
				<li><a href="A2B_entity_card.php?section=1">{php} echo gettext("Add :: Search");{/php}</a></li>
                <li><a href="CC_card_import.php?section=1">{php} echo gettext("Import");{/php}</a></li>
				<li><a href="A2B_entity_friend.php?atmenu=sip&section=1">{php} echo gettext("VoIP Settings");{/php}</a></li>
				<li><a href="A2B_entity_callerid.php?atmenu=callerid&section=1">{php} echo gettext("Caller-ID");{/php}</a></li>
				<li><a href="A2B_notifications.php?section=1">{php} echo gettext("Credit Notification");{/php}</a></li>
				<li><a href="A2B_entity_card_group.php?section=1">{php} echo gettext("Groups");{/php}</a></li>
				<li><a href="A2B_entity_card_seria.php?section=1">{php} echo gettext("Card series");{/php}</a></li>
				<li><a href="A2B_entity_speeddial.php?atmenu=speeddial&section=1">{php} echo gettext("Speed Dial");{/php}</a></li>
				<li><a href="card-history.php?atmenu=cardhistory&section=1">{php} echo gettext("History");{/php}</a></li>
				<li><a href="A2B_entity_statuslog.php?atmenu=statuslog&section=1">{php} echo gettext("Status");{/php}</a></li>
		</ul></li>
	</ul>
	</div>
	{/if}

	{if ($ACXADMINISTRATOR  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img2"
	{if ($section == "2")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("AGENTS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="2")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_agent.php?atmenu=user&section=2">{php} echo gettext("Add :: Search");{/php}</a></li>
				<li><a href="A2B_entity_signup_agent.php?atmenu=user&section=2">{php} echo gettext("Signup URLs");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXADMINISTRATOR  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img3"
	{if ($section == "3")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("ADMINS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="3")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=0&section=3">{php} echo gettext("Add :: Search");{/php}</a></li>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=1&section=3">{php} echo gettext("Access Control");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXSUPPORT > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img4"
	{if ($section == "4")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("SUPPORT");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="4")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="CC_ticket.php?section=4">{php} echo gettext("Customer Tickets");{/php}</a></li>
				<li><a href="A2B_ticket_agent.php?section=4">{php} echo gettext("Agent Tickets");{/php}</a></li>
				<li><a href="CC_support_component.php?section=4">{php} echo gettext("Ticket Components");{/php}</a></li>
				<li><a href="CC_support.php?section=4">{php} echo gettext("Support Boxes");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXCALLREPORT > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img5"
	{if ($section == "5")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("CALL REPORTS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="5")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
					<li><a href="call-log-customers.php?nodisplay=1&posted=1&section=5">{php} echo gettext("CDRs");{/php}</a></li>
					<li><a href="call-count-reporting.php?nodisplay=1&posted=1&section=5">{php} echo gettext("Call Count");{/php}</a></li>
					<li><a href="A2B_trunk_report.php?section=5">{php} echo gettext("Trunk");{/php}</a></li>
					<li><a href="call-dnid.php?nodisplay=1&posted=1&section=5">{php} echo gettext("DNID");{/php}</a></li>
					<li><a href="call-pnl-report.php?section=5">{php} echo gettext("PNL");{/php}</a></li>
					<li><a href="call-comp.php?section=5">{php} echo gettext("Compare Calls");{/php}</a></li>
					<li><a href="call-daily-load.php?section=5">{php} echo gettext("Daily Traffic");{/php}</a></li>
					<li><a href="call-last-month.php?section=5">{php} echo gettext("Monthly Traffic");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXRATECARD > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img6"
	{if ($section == "6")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("RATES");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="6")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_tariffgroup.php?atmenu=tariffgroup&section=6">{php} echo gettext("Call Plan");{/php}</a></li>
				<li><a href="A2B_entity_tariffplan.php?atmenu=tariffplan&section=6">{php} echo gettext("RateCards");{/php}</a></li>
				<li><a href="CC_ratecard_import.php?atmenu=ratecard&section=6">»» {php} echo gettext("Import");{/php}</a></li>
				<li><a href="CC_ratecard_merging.php?atmenu=ratecard&section=6">»» {php} echo gettext("Merge");{/php}</a></li>
				<li><a href="CC_entity_sim_ratecard.php?atmenu=ratecard&section=6">»» {php} echo gettext("Simulator");{/php}</a></li>
				<li><a href="A2B_entity_def_ratecard.php?atmenu=ratecard&section=6">{php} echo gettext("Rates");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXTRUNK > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img7"
	{if ($section == "7")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("PROVIDERS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="7")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_provider.php?section=7">{php} echo gettext("Providers");{/php}</a></li>
				<li><a href="A2B_entity_trunk.php?section=7">{php} echo gettext("Trunks");{/php}</a></li>
				<li><a href="A2B_entity_prefix.php?section=7">{php} echo gettext("Prefixes");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXDID > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img8"
	{if ($section == "8")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("INBOUND DID");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="8")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_did.php?section=8">{php} echo gettext("Add :: Search");{/php}</a></li>
				<li><a href="A2B_entity_didgroup.php?section=8">{php} echo gettext("Groups");{/php}</a>
				<li><a href="A2B_entity_did_destination.php?section=8">{php} echo gettext("Destination");{/php}</a></li>
				<li><a href="A2B_entity_did_import.php?section=8">{php} echo gettext("Import [CSV]");{/php}</a></li>
				<li><a href="A2B_entity_didx.php?section=8">{php} echo gettext("Import [DIDX]");{/php}</a></li>
				<li><a href="A2B_entity_did_use.php?atmenu=did_use&section=8">{php} echo gettext("Usage");{/php}</a></li>
				<li><a href="A2B_entity_did_billing.php?atmenu=did_billing&section=8">{php} echo gettext("Billing");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXOUTBOUNDCID > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img9"
	{if ($section == "9")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("OUTBOUND CID");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="9")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_outbound_cid.php?atmenu=cid&section=9">{php} echo gettext("Add");{/php}</a></li>
				<li><a href="A2B_entity_outbound_cidgroup.php?atmenu=cidgroup&section=9">{php} echo gettext("Groups");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}



	{if ($ACXBILLING > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img10"
	{if ($section == "10")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("BILLING");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="10")}
		style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_voucher.php?section=10">{php} echo gettext("Vouchers");{/php}</a></li>
				<li><a href="A2B_entity_moneysituation.php?atmenu=moneysituation&section=10">{php} echo gettext("Customers Balance");{/php}</a></li>
                <li><a href="A2B_entity_transactions.php?atmenu=payment&section=10">»» {php} echo gettext("Transactions");{/php}</a></li>
				<li><a href="A2B_entity_billing_customer.php?atmenu=payment&section=10">»» {php} echo gettext("Billings");{/php}</a></li>
				<li><a href="A2B_entity_logrefill.php?atmenu=payment&section=10">»» {php} echo gettext("Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment.php?atmenu=payment&section=10">»» {php} echo gettext("Payments");{/php}</a></li>
				<li><a href="A2B_entity_paymentlog.php?section=10">»» {php} echo gettext("E-Payment Log");{/php}</a></li>
				<li><a href="A2B_entity_charge.php?section=10">»» {php} echo gettext("Charges");{/php}</a></li>
				<li><a href="A2B_entity_agentsituation.php?atmenu=agentsituation&section=10">{php} echo gettext("Agents Balance");{/php}</a></li>
				<li><a href="A2B_entity_commission_agent.php?atmenu=payment&section=10">»» {php} echo gettext("Commissions");{/php}</a></li>
				<li><a href="A2B_entity_remittance_request.php?atmenu=payment&section=10">»» {php} echo gettext("Remittance Request");{/php}</a></li>
				<li><a href="A2B_entity_transactions_agent.php?atmenu=payment&section=10">»» {php} echo gettext("Transactions");{/php}</a></li>
				<li><a href="A2B_entity_logrefill_agent.php?atmenu=payment&section=10">»» {php} echo gettext("Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment_agent.php?atmenu=payment&section=10">»» {php} echo gettext("Payments");{/php}</a></li>
				<li><a href="A2B_entity_paymentlog_agent.php?section=10">»» {php} echo gettext("E-Payment Log");{/php}</a></li>
				<li><a href="A2B_entity_payment_configuration.php?atmenu=payment&section=10">{php} echo gettext("Payment Methods");{/php}</a></li>
				<li><a href="A2B_currencies.php?section=10">{php} echo gettext("Currency List");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXINVOICING > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img11"
	{if ($section == "11")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("INVOICES");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="11")}
		style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_receipt.php?atmenu=payment&section=11">{php} echo gettext("Receipts");{/php}</a></li>
				<li><a href="A2B_entity_invoice.php?atmenu=payment&section=11">{php} echo gettext("Invoices");{/php}</a></li>
				<li><a href="A2B_entity_invoice_conf.php?atmenu=payment&section=11">»» {php} echo gettext("Configuration");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXPACKAGEOFFER > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img12"
	{if ($section == "12")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("PACKAGE OFFER");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="12")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_package.php?atmenu=package&section=12">{php} echo gettext("Add");{/php}</a></li>
				<li><a href="A2B_detail_package.php?section=12">{php} echo gettext("Details");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXCRONTSERVICE  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img13"
	{if ($section == "13")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("RECUR SERVICE");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="13")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_service.php?section=13">{php} echo gettext("Account Service");{/php}</a></li>
				<li><a href="A2B_entity_subscription.php?section=13">{php} echo gettext("Subscriptions Service");{/php}</a></li>
				<li><a href="A2B_entity_subscriber_signup.php?section=13">{php} echo gettext("Subscriptions SIGNUP");{/php}</a></li>
				<li><a href="A2B_entity_subscriber.php?section=13">{php} echo gettext("Subscribers");{/php}</a></li>
				<li><a href="A2B_entity_autorefill.php?section=13">{php} echo gettext("AutoRefill Report");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXCALLBACK  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img14"
	{if ($section == "14")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("CALLBACK");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="14")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_callback.php?section=14">{php} echo gettext("Add");{/php}</a></li>
				<li><a href="A2B_entity_server_group.php?section=14">{php} echo gettext("Server Group");{/php}</a></li>
				<li><a href="A2B_entity_server.php?section=14">{php} echo gettext("Server");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}

	{if ($ACXPREDICTIVEDIALER  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img15"
	{if ($section == "15")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("CAMPAIGNS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="15")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_campaign.php?section=15">{php} echo gettext("Autodialer");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXMAINTENANCE  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img16"
	{if ($section == "16")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("MAINTENANCE");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="16")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_alarm.php?section=16"> {php} echo gettext("Alarms");{/php}</a></li>
				<li><a href="A2B_entity_log_viewer.php?section=16">{php} echo gettext("Users Activity");{/php}</a></li>
				<li><a href="A2B_entity_backup.php?form_action=ask-add&section=16">{php} echo gettext("Database Backup");{/php}</a></li>
				<li><a href="A2B_entity_restore.php?section=16">{php} echo gettext("Database Restore");{/php}</a></li>
				<li><a href="CC_musiconhold.php?section=16">{php} echo gettext("MusicOnHold");{/php}</a></li>
				<li><a href="CC_upload.php?section=16">{php} echo gettext("Upload File");{/php}</a></li>
				<li><a href="A2B_logfile.php?section=16">{php} echo gettext("Watch Log files");{/php}</a></li>
				<li><a href="A2B_data_archiving.php?section=16">{php} echo gettext("Archiving");{/php}</a></li>
				<li><a href="A2B_asteriskinfo.php?section=16">{php} echo "Asterisk Info";{/php}</a></li>
				<li><a href="A2B_phpsysinfo.php?section=16">{php} echo "phpSysInfo";{/php}</a></li>
				<li><a href="A2B_phpinfo.php?section=16">{php} echo "phpInfo";{/php}</a></li>
				<li><a href="A2B_entity_monitor.php?section=16"> {php} echo gettext("Monitoring");{/php}</a></li>
			</ul></li>
		</ul>
	</div>

	{/if}

	{if ($ACXMAIL  > 0)}
	<!-- Disabled Mail feature -->
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img17"
	{if ($section == "17")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("MAIL");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="17")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_mailtemplate.php?atmenu=mailtemplate&section=17&languages=en">{php} echo gettext("Mail templates");{/php}</a></li>
				<li><a href="A2B_mass_mail.php?section=17">{php} echo gettext("Mass Mail");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	{/if}


	{if ($ACXSETTING  > 0)}
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img18"
	{if ($section == "18")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong>{php} echo gettext("SYSTEM SETTINGS");{/php}</strong></div></div></a></li></div>
		<div class="tohide"
	{if ($section =="18")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_config.php?form_action=list&atmenu=config&section=18">{php} echo gettext("Global List");{/php}</a></li>
				<li><a href="A2B_entity_config_group.php?form_action=list&atmenu=configgroup&section=18">{php} echo gettext("Group List");{/php}</a></li>
				<li><a href="A2B_entity_config_generate_confirm.php?section=18">{php} echo gettext("Add agi-conf");{/php}</a></li>
				<li><a href="phpconfig.php?dir=/etc/asterisk&section=18">{php} echo gettext("* Config Editor");{/php}</a></li>
				{if ($ASTERISK_GUI_LINK)}
					<li><a href="http://{$HTTP_HOST}:8088/asterisk/static/config/index.html" target="_blank">{php} echo gettext("Asterisk GUI");{/php}</a></li>
				{/if}
			</ul></li>
		</ul>
	</div>

	{/if}

</ul>

<br/>
<ul id="nav"><li>
	<ul><li><a href="A2B_entity_password.php?atmenu=password&form_action=ask-edit"><strong>{php} echo gettext("Change Password");{/php}</strong> <img style="vertical-align:bottom;" src="templates/{$SKIN_NAME}/images/key.png"> </a></li></ul>
</li></ul>

</div>
</div>
</div>



<table width="100%" cellspacing="15">
<tr>
	<td>
		<a href="PP_intro.php?ui_language=english" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/gb.gif" border="0" title="English" alt="English"></a>
		<a href="PP_intro.php?ui_language=brazilian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
		<a href="PP_intro.php?ui_language=romanian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/ro.gif" border="0" title="Romanian"alt="Romanian"></a>
		<a href="PP_intro.php?ui_language=french" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/fr.gif" border="0" title="French" alt="French"></a>
		<a href="PP_intro.php?ui_language=spanish" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
		<a href="PP_intro.php?ui_language=greek" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/gr.gif" border="0" title="Greek" alt="Greek"></a>
		<a href="PP_intro.php?ui_language=italian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/it.gif" border="0" title="Italian" alt="Italian"></a>
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

