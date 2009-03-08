<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/{$SKIN_NAME}/images/favicon.ico">
	<link rel="icon" href="templates/{$SKIN_NAME}/images/animated_favicon1.gif" type="image/gif">
	<title>..:: {$CCMAINTITLE} ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="templates/{$SKIN_NAME}/css/main.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/menu.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/style-def.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
	<script type="text/javascript" src="./javascript/jquery/ilogger.js"></script>
	<script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>

</HEAD>
<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<p class="version" align="right">{$WEBUI_VERSION} - {$WEBUI_DATE}<br><br><br>

{if ($adminname) }
Logged-in as: <b>{$adminname}</b></p>
<br>
{/if}
<DIV border="0" width="1000px">
{if ($popupwindow == 0)}
<div class="divleft">


<div id="nav_before"></div>
<ul id="nav">



	{if ($ACXMYACCOUNT > 0) }
	<div class="toggle_menu">
	<li>
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "0")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("MY ACCOUNT");{/php}</strong></a></li>
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
	</div>
	{/if}
	
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
				<li><a href="A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1">{php} echo gettext("List Customers");{/php}</a></li>
				<li><a href="card-history.php?atmenu=cardhistory&stitle=Card+History&section=1">{php} echo gettext("Card History");{/php}</a></li>
				{if ($ACXVOIPCONF > 0) }
				<li><a href="A2B_entity_friend.php?atmenu=sip&section=1">{php} echo gettext("VOIP Config");{/php}</a></li>
				{/if}
		</ul></li>
	</ul>
	</div>
	</div>
	{/if}
	
	
	{if ($ACXSIGNUP > 0) }
	<div class="toggle_menu">
	<li>
	<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
	{if ($section == "8")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if}
 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("SIGNUP");{/php}</strong></a></li>
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
				<li><a href="A2B_entity_moneysituation.php?atmenu=moneysituation&section=2">{php} echo gettext("Account balance");{/php}</a></li>
				<li><a href="A2B_entity_logrefill_agent.php?atmenu=payment&section=2">{php} echo gettext("Own Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment_agent.php?atmenu=payment&section=2">{php} echo gettext("Own Payments");{/php}</a></li>
				<li><a href="A2B_entity_logrefill.php?atmenu=payment&section=2">{php} echo gettext("Customer's Refills");{/php}</a></li>
				<li><a href="A2B_entity_payment.php?atmenu=payment&section=2">{php} echo gettext("Customer's Payment");{/php}</a></li>
				<li><a href="A2B_entity_paymentlog.php?stitle=Payment_log&section=2">{php} echo gettext("Payment Log");{/php}</a></li>
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
				<li><a href="A2B_entity_def_ratecard.php?atmenu=ratecard&stitle=RateCard&section=3">{php} echo gettext("Browse Rates");{/php}</a></li>
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
					<li><a href="call-log-customers.php?stitle=Call_Report_Customers&nodisplay=1&posted=1&section=6">{php} echo gettext("CDR Report");{/php}</a></li>
					<li><a href="call-last-month.php?section=6">{php} echo gettext("Monthly Traffic");{/php}</a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	{/if}
	
	{if ($ACXSUPPORT  > 0)}
	<div class="toggle_menu">
	<li><a href="javascript:;" class="toggle_menu" target="_self"><img id="img8"
	{if ($section == "7")}
	src="templates/{$SKIN_NAME}/images/minus.gif"
	{else}
	src="templates/{$SKIN_NAME}/images/plus.gif"
	{/if} onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("SUPPORT");{/php}</strong></a></li>
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
	</div>
	{/if}
	


	<li><a href="#" target="_self"></a></a></li>
	<ul>
		<li><ul>
		<li><a href="logout.php?logout=true" target="_top"><font color="#DD0000"><b>&nbsp;&nbsp;{php} echo gettext("LOGOUT");{/php}</b></font></a></li>
		</ul></li>
	</ul>

</ul>
<div id="nav_after"></div>



</div>

<div class="divright">

{else}
<div>
{/if}
