<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/{$SKIN_NAME}/images/favicon.ico">
	<link rel="icon" href="templates/{$SKIN_NAME}/images/animated_favicon1.gif" type="image/gif">
	<title>..:: {$CCMAINTITLE} ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="templates/{$SKIN_NAME}/css/main.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/menu.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/style-def.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="./javascript/jquery/jquery.js"></script>
	<script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
	<script type="text/javascript" src="./javascript/jquery/ilogger.js"></script>
	<script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>

</HEAD>
<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<p class="version" align="right">{$WEBUI_VERSION} - {$WEBUI_DATE}<br><br><br>Logged-in as: <b>{$adminname}</b></p>
<br>

<DIV border="0" width="1000px">
{if ($popupwindow == 0)}
<div class="divleft">


<div id="nav_before"></div>
<ul id="nav">
  
  
  	{if ($ACXCUSTOMER > 0) }
	<div class="toggle_menu">
	<li>
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "1")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("CUSTOMERS");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="1")}
	style="">
	{else}
	style="display:none;">
	{/if}
	<ul>
		<li><ul>
				<li><a href="A2B_entity_card.php?section=1">{php} echo gettext("Customers");{/php}</a></li>
				<li><a href="A2B_entity_card_group.php?section=1">{php} echo gettext("Customer Group");{/php}</a></li>
                <li><a href="CC_card_import.php?section=1">{php} echo gettext("Import Customers");{/php}</a></li>
				<li><a href="A2B_entity_friend.php?atmenu=sip&section=1">{php} echo gettext("SIP-FRIEND");{/php}</a></li>
				<li><a href="A2B_entity_friend.php?atmenu=iax&section=1">{php} echo gettext("IAX-FRIEND");{/php}</a></li>
				<li><a href="A2B_entity_callerid.php?atmenu=callerid&section=1">{php} echo gettext("CallerID");{/php}</a></li>
				<li><a href="A2B_entity_speeddial.php?atmenu=speeddial&section=1">{php} echo gettext("Speed Dial");{/php}</a></li>
				<li><a href="A2B_entity_statuslog.php?atmenu=statuslog&section=1">{php} echo gettext("Customer Status Log");{/php}</a></li>
				<li><a href="card-history.php?atmenu=cardhistory&section=1">{php} echo gettext("Customer History");{/php}</a></li>
				<li><a href="A2B_notifications.php?section=1">{php} echo gettext("Notification");{/php}</a></li>
		</ul></li>
	</ul>
	</div>
	</div>
	{/if}

	{if ($ACXBILLING > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img2"
	{if ($section =="2")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("BILLING");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="2")}

	style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_payment_configuration.php?atmenu=payment&section=2">{php} echo gettext("View Payment Methods");{/php}</a></li>
                <li><a href="A2B_entity_transactions.php?atmenu=payment&section=2">{php} echo gettext("View Transactions");{/php}</a></li>
				<li><a href="A2B_entity_moneysituation.php?atmenu=moneysituation&section=2">{php} echo gettext("View money situation");{/php}</a></li>
				<li><a href="A2B_entity_logrefill.php?atmenu=payment&section=2">{php} echo gettext("Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment.php?atmenu=payment&section=2">{php} echo gettext("Payments");{/php}</a></li>
				<li><a href="A2B_entity_paymentlog.php?section=2">{php} echo gettext("E-Payment Log");{/php}</a></li>
				<li><a href="A2B_entity_voucher.php?section=2">{php} echo gettext("Vouchers");{/php}</a></li>
				<li><a href="A2B_currencies.php?section=2">{php} echo gettext("Currency List");{/php}</a></li>
				<li><a href="A2B_entity_charge.php?section=2">{php} echo gettext("Charges");{/php}</a></li>
				<li><a href="A2B_entity_ecommerce.php?atmenu=ecommerce&section=2">{php} echo gettext("E-Products");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXRATECARD > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img3"
	{if ($section =="3")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	  onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong>{php} echo gettext("RATECARD");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="3")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_tariffgroup.php?atmenu=tariffgroup&section=3">{php} echo gettext("Call Plan");{/php}</a></li>
				<li><a href="A2B_entity_tariffplan.php?atmenu=tariffplan&section=3">{php} echo gettext("RateCards");{/php}</a></li>
				<li><a href="A2B_entity_def_ratecard.php?atmenu=ratecard&section=3">{php} echo gettext("Rates");{/php}</a></li>
				<li><a href="CC_ratecard_import.php?atmenu=ratecard&section=3">{php} echo gettext("Import RateCard");{/php}</a></li>
				<li><a href="CC_ratecard_merging.php?atmenu=ratecard&section=3">{php} echo gettext("Merge RateCard");{/php}</a></li>
				<li><a href="CC_entity_sim_ratecard.php?atmenu=ratecard&section=3">{php} echo gettext("Ratecard Simulator");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXPACKAGEOFFER > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img3_3"
	{if ($section =="3_3")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	  onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong>{php} echo gettext("PACKAGE OFFER");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="3_3")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_package_group.php?atmenu=prefixe&section=3_3">{php} echo gettext("Group Packages");{/php}</a></li>
				<li><a href="A2B_entity_package.php?atmenu=prefixe&section=3_3">{php} echo gettext("Offer Packages");{/php}</a></li>
				<li><a href="A2B_detail_package.php?section=3_3">{php} echo gettext("Details Package");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXOUTBOUNDCID > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img3_5"
	{if ($section =="3_5")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	  onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong>{php} echo gettext("OUTBOUND CID");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="3_5")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_outbound_cidgroup.php?atmenu=cidgroup&section=3_5">{php} echo gettext("CIDGroup");{/php}</a></li>
				<li><a href="A2B_entity_outbound_cid.php?atmenu=cid&section=3_5">{php} echo gettext("CID's");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXTRUNK > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img4"
	{if ($section =="4")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong>{php} echo gettext("TRUNK");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="4")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_trunk.php?section=4">{php} echo gettext("Trunks");{/php}</a></li>
				<li><a href="A2B_entity_provider.php?section=4">{php} echo gettext("Providers");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXDID > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img41"
	{if ($section == "5")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong>{php} echo gettext("DID");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="5")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_didgroup.php?section=5">{php} echo gettext("DID Group");{/php}</a>
				<li><a href="A2B_entity_did.php?section=5">{php} echo gettext("DID's");{/php}</a></li>
				<li><a href="A2B_entity_didx.php?section=5">{php} echo gettext("Add DID from DIDX");{/php}</a></li>
				<li><a href="A2B_entity_did_import.php?section=5">{php} echo gettext("Import DID");{/php}</a></li>
				<li><a href="A2B_entity_did_destination.php?section=5">{php} echo gettext("List Destination");{/php}</a></li>
				<li><a href="A2B_entity_did_billing.php?atmenu=did_billing&section=5">{php} echo gettext("DID Billing");{/php}</a></li>
				<li><a href="A2B_entity_did_use.php?atmenu=did_use&section=5">{php} echo gettext("DID Usage");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXCALLREPORT > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img5"
	{if ($section == "6")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong>{php} echo gettext("CALL REPORT");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="6")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
					<li><a href="call-log-customers.php?nodisplay=1&posted=1&section=6">{php} echo gettext("CDR Report");{/php}</a></li>
					<li><a href="call-comp.php?section=6">{php} echo gettext("Calls Compare");{/php}</a></li>
					<li><a href="call-last-month.php?section=6">{php} echo gettext("Monthly Traffic");{/php}</a></li>
					<li><a href="call-daily-load.php?section=6">{php} echo gettext("Daily Load");{/php}</a></li>
					<li><a href="call-count-reporting.php?nodisplay=1&posted=1&section=6">{php} echo gettext("Report");{/php}</a></li>
					<li><a href="A2B_trunk_report.php?section=6">{php} echo gettext("Trunk Report");{/php}</a></li>
					<li><a href="call-dnid.php?nodisplay=1&posted=1&section=6">{php} echo gettext("DNID Report");{/php}</a></li>
					<li><a href="call-pnl-report.php?section=6">{php} echo gettext("PNL Report");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXINVOICING > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img2"
	{if ($section =="13")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("INVOICES");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="13")}
	style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_view_invoice.php?atmenu=payment&section=13">{php} echo gettext("View Invoices");{/php}</a></li>
				<li><a href="A2B_entity_create_invoice.php?atmenu=payment&section=13">{php} echo gettext("Create Invoices");{/php}</a></li>
				<li><a href="invoices.php?nodisplay=1&section=13">{php} echo gettext("Invoice");{/php}</a></li>
				<li><a href="invoices_customer.php?nodisplay=1&section=13">{php} echo gettext("Invoices Customer");{/php}</a></li>
				<li><a href="A2B_entity_invoices.php?atmenu=payment&section=13&invoicetype=billed">{php} echo gettext("View Billed Invoices");{/php}</a></li>
				<li><a href="A2B_entity_invoices.php?atmenu=payment&section=13&invoicetype=unbilled">{php} echo gettext("View UnBilled Invoices");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXINVOICING2 > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img2"
	{if ($section =="14")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("INVOICES2");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="14")}
	style="">
	{else}
	style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_invoices2_bill.php?atmenu=payment&section=14">{php} echo gettext("Bill Invoices");{/php}</a></li>
				<li><a href="A2B_entity_invoices2_period.php?atmenu=payment&section=14">{php} echo gettext("Billed per period");{/php}</a></li>
				<li><a href="A2B_entity_invoices2_card.php?invoicetype=billed&atmenu=payment&section=14">{php} echo gettext("Billed per customer");{/php}</a></li>
				<li><a href="A2B_entity_invoices2_card.php?invoicetype=unbilled&atmenu=payment&section=14">{php} echo gettext("Unbilled Invoices");{/php}</a></li>
				<li><a href="A2B_entity_invoices2_sales.php?atmenu=payment&section=14">{php} echo gettext("Sales");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}


	{if ($ACXCRONTSERVICE  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img9"
	{if ($section =="7")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("RECURRING SERVICE");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="7")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_autorefill.php?section=7">{php} echo gettext("AutoRefill Report");{/php}</a></li>
				<li><a href="A2B_entity_service.php?section=7">{php} echo gettext("Recurring Services");{/php}</a></li>
				<li><a href="A2B_entity_alarm.php?section=7"> {php} echo gettext("Alarms");{/php}</a></li>
				<li><a href="A2B_entity_subscription.php?section=7">{php} echo gettext("Subscriptions");{/php}</a></li>
				<li><a href="A2B_entity_subscriber.php?section=7">{php} echo gettext("Subscribers");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	
	{if ($ACXSUPPORT  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img8"
	{if ($section == "13")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("SUPPORT");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="17")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="CC_ticket.php?section=17">{php} echo gettext("Tickets");{/php}</a></li>
				<li><a href="CC_support.php?section=17">{php} echo gettext("Support Box's");{/php}</a></li>
				<li><a href="CC_support_component.php?section=17">{php} echo gettext("Components");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXCALLBACK  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img10"
	{if ($section =="12")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("CALLBACK");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="12")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_callback.php?section=12">{php} echo gettext("Callbacks");{/php}</a></li>
				<li><a href="A2B_entity_server_group.php?section=12">{php} echo gettext("Server Group's");{/php}</a></li>
				<li><a href="A2B_entity_server.php?section=12">{php} echo gettext("Server");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXPREDICTIVEDIALER  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img16"
	{if ($section =="16")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("AUTO DIALER");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="16")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_campaign.php?section=16">{php} echo gettext("Campaign's");{/php}</a></li>
				<li><a href="A2B_entity_campaign_config.php?section=16">{php} echo gettext("Campaign Configs");{/php}</a></li>
				<li><a href="A2B_entity_phonebook.php?section=16">{php} echo gettext("Phone Book");{/php}</a></li>
				<li><a href="A2B_entity_phonenumber.php?section=16">{php} echo gettext("Phone Number");{/php}</a></li>
				<li><a href="A2B_phonelist_import.php?section=16">{php} echo gettext("Import Phone List");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXMISC  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img8"
	{if ($section =="8")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
	onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("MISC");{/php}</strong></a></li>
	<div class="tohide"
	{if ($section =="8")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_mailtemplate.php?atmenu=mailtemplate&section=8&languages=en">{php} echo gettext("Mail templates");{/php}</a></li>
				<li><a href="A2B_entity_prefix.php?atmenu=prefixe&section=8">{php} echo gettext("Prefixes");{/php}</a></li>
				<li><a href="A2B_entity_config_group.php?form_action=list&atmenu=configgroup&section=8">{php} echo gettext("List Global  Config");{/php}</a></li>
				<li><a href="A2B_entity_config_generate_confirm.php">{php} echo gettext("ADD agi-conf");{/php}</a></li>
				<li><a href="A2B_entity_config.php?form_action=list&atmenu=config&section=8">{php} echo gettext("List Configuration");{/php}</a></li>
				<li><a href="A2B_mass_mail.php?section=8">{php} echo gettext("Mass Mail");{/php}</a></li>
				<li><a href="A2B_data_archiving.php?section=8">{php} echo gettext("Archiving");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	

	{if ($ACXADMINISTRATOR  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img10"
	{if ($section =="10")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("AGENT / ADMIN");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="10")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_agent.php?atmenu=user&groupID=0&section=10">{php} echo gettext("Agents");{/php}</a></li>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=0&section=10">{php} echo gettext("Administrators");{/php}</a></li>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=1&section=10">{php} echo gettext("ACL Admin's");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}

	{if ($ACXMAINTENANCE  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img11"
	{if ($section == "11")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("MAINTENANCE");{/php}</strong></a></li>
		<div class="tohide"
	{if ($section =="11")}
		style="">
	{else}
		style="display:none;">
	{/if}
		<ul>
			<li><ul>
				<li><a href="A2B_entity_log_viewer.php?section=11">{php} echo gettext("Users Activity");{/php}</a></li>
				<li><a href="A2B_entity_backup.php?form_action=ask-add&section=11">{php} echo gettext("Database Backup");{/php}</a></li>
				<li><a href="A2B_entity_restore.php?section=11">{php} echo gettext("Database Restore");{/php}</a></li>
				<li><a href="A2B_logfile.php?section=11">{php} echo gettext("Watch Log files");{/php}</a></li>
				<li><a href="CC_musiconhold.php?section=11">{php} echo gettext("MusicOnHold");{/php}</a></li>
				<li><a href="CC_upload.php?section=11">{php} echo gettext("Upload File");{/php}</a></li>
				<li><a href="A2B_asteriskinfo.php?section=11">{php} echo "Asterisk Info";{/php}</a></li>
				<li><a href="A2B_phpsysinfo.php?section=11">{php} echo "phpSysInfo";{/php}</a></li>
				<li><a href="A2B_phpinfo.php?section=11">{php} echo "phpInfo";{/php}</a></li>
				<li><a href="phpconfig.php?dir=/etc/asterisk&section=11">{php} echo gettext("Asterisk config");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	
	{/if}
	
	
	<li><a href="#" target="_self"></a></a></li>
		<ul><li><a href="A2B_entity_password.php?atmenu=password&form_action=ask-edit"><strong>{php} echo gettext("PASSWORD");{/php}</strong></a></li></ul>
  	<li><a href="#" target="_self"></a></a></li>
  	

	<li><a href="#" target="_self"></a></a></li>
	<ul>
		<li><ul>
		<li><a href="logout.php?logout=true" target="_top"><font color="#DD0000"><b>&nbsp;&nbsp;{php} echo gettext("LOGOUT");{/php}</b></font></a></li>
		</ul></li>
	</ul>

</ul>
<div id="nav_after"></div>
<br>
<table>
<tr>
	<td>
		<a href="PP_intro.php?language=english" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/gb.gif" border="0" title="English" alt="English"></a>
		<a href="PP_intro.php?language=brazilian" target="_parent"><img src="templates/{$SKIN_NAME}/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
	</td>
</tr>
<tr>
		<td>
			<form action="{$PAGE_SELF}" method="post">
				<select name="cssname" class="form_input_select" >
					<option value="default" {checkseleted file="default"}>Default</option>
				</select>
				<input type="submit" value="Change" class="form_input_button" >
			</form>
		</td>
	</tr>
</table>


</div>

<div class="divright">

{else}
<div>
{/if}
