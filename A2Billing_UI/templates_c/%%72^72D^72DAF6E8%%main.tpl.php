<?php /* Smarty version 2.6.13, created on 2007-05-31 11:58:49
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'checkseleted', 'main.tpl', 460, false),)), $this); ?>
<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/favicon.ico">
	<link rel="icon" href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/animated_favicon1.gif" type="image/gif">
	<title>..:: <?php echo $this->_tpl_vars['CCMAINTITLE']; ?>
 ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/css/main.css" rel="stylesheet" type="text/css">
	<link href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/css/menu.css" rel="stylesheet" type="text/css">
	<link href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/css/style-def.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="./javascript/jquery/jquery.js"></script>
	<script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
	<script type="text/javascript" src="./javascript/jquery/ilogger.js"></script>
	<script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>
	
</HEAD>
<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<p class="version" align="right"><?php echo $this->_tpl_vars['WEBUI_VERSION']; ?>
 - <?php echo $this->_tpl_vars['WEBUI_DATE']; ?>
<br><br><br>Logged-in as: <b><?php echo $this->_tpl_vars['adminname']; ?>
</b></p>
<br>

<DIV border="0" width="1000px">
<?php if (( $this->_tpl_vars['popupwindow'] == 0 )): ?>
<div class="divleft">


<div id="nav_before"></div>
<ul id="nav">

	<?php if (( $this->_tpl_vars['ACXCUSTOMER'] > 0 )): ?>
	<div class="toggle_menu">
	<li>
	<a href="#" class="toggle_menu" target="_self"><img id="img1"  
	<?php if (( $this->_tpl_vars['section'] == '1' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?>
 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("CUSTOMERS"); ?></strong></a></li>
	<div class="tohide" 
	<?php if (( $this->_tpl_vars['section'] == '1' )): ?>
	style="">
	<?php else: ?>
	style="display:none;">
	<?php endif; ?>
	<ul>
		<li><ul>
				<li><a href="A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1"><?php  echo gettext("List Customers"); ?></a></li>
				<li><a href="A2B_entity_card.php?form_action=ask-add&atmenu=card&stitle=Card&section=1"><?php  echo gettext("Create Customers"); ?></a></li>
                <li><a href="CC_card_import.php?atmenu=card&stitle=Card&section=1"><?php  echo gettext("Import Customers"); ?></a></li>
				<li><a href="A2B_entity_card_multi.php?stitle=Card&section=1"><?php  echo gettext("Generate Customers"); ?></a></li>
				<li><a href="A2B_entity_friend.php?atmenu=sipfriend&stitle=SIP+Friends&section=1"><?php  echo gettext("List SIP-FRIEND"); ?></a></li>
				<li><a href="A2B_entity_friend.php?form_action=ask-add&atmenu=sipfriend&stitle=SIP+Friends&section=1"><?php  echo gettext("Create SIP-FRIEND"); ?></a></li>
				<li><a href="A2B_entity_friend.php?atmenu=iaxfriend&stitle=IAX+Friends&section=1"><?php  echo gettext("List IAX-FRIEND"); ?></a></li>
				<li><a href="A2B_entity_friend.php?form_action=ask-add&atmenu=iaxfriend&stitle=IAX+Friends&section=1"><?php  echo gettext("Create IAX-FRIEND"); ?></a></li>
				<li><a href="A2B_entity_callerid.php?atmenu=callerid&stitle=CallerID&section=1"><?php  echo gettext("List CallerID"); ?></a></li>
				<li><a href="A2B_entity_speeddial.php?atmenu=speeddial&stitle=Speed+Dial&section=1"><?php  echo gettext("List Speed Dial"); ?></a></li>
				<li><a href="A2B_entity_speeddial.php?form_action=ask-add&atmenu=speeddial&stitle=Speed+Dial&section=1"><?php  echo gettext("Create Speed Dial"); ?></a></li>
		</ul></li>
	</ul>
	</div>
	</div>
	<?php endif; ?>
		
	<?php if (( $this->_tpl_vars['ACXBILLING'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img2" 
	<?php if (( $this->_tpl_vars['section'] == '2' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?>
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("BILLING"); ?></strong></a></li>
	<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '2' )): ?>
	
	style="">
	<?php else: ?>
	style="display:none;">	
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_payment_configuration.php?atmenu=payment&section=2"><?php  echo gettext("View Payment Methods"); ?></a></li>
                <li><a href="A2B_entity_transactions.php?atmenu=payment&stitle=Solde&section=2"><?php  echo gettext("View Transactions"); ?></a></li>				
				<li><a href="A2B_entity_moneysituation.php?atmenu=moneysituation&stitle=Money_Situation&section=2"><?php  echo gettext("View money situation"); ?></a></li>
				<li><a href="A2B_entity_payment.php?atmenu=payment&stitle=Solde&section=2"><?php  echo gettext("View Payment"); ?></a></li>
				<li><a href="A2B_entity_payment.php?stitle=Payment_add&form_action=ask-add&section=2"><?php  echo gettext("Add new Payment"); ?></a></li>				
				<li><a href="A2B_entity_voucher.php?stitle=Voucher&section=2"><?php  echo gettext("List Voucher"); ?></a></li>
				<li><a href="A2B_entity_voucher.php?stitle=Voucher_add&form_action=ask-add&section=2"><?php  echo gettext("Create Voucher"); ?></a></li>
				<li><a href="A2B_entity_voucher_multi.php?stitle=Voucher_Generate&section=2"><?php  echo gettext("Generate Vouchers"); ?></a></li>
				<li><a href="A2B_currencies.php?section=2"><?php  echo gettext("Currency List"); ?></a></li>
				<li><a href="A2B_entity_charge.php?atmenu=charge&stitle=Charge&form_action=list&section=2"><?php  echo gettext("List Charge"); ?></a></li>
				<li><a href="A2B_entity_charge.php?form_action=ask-add&atmenu=charge&stitle=Charge&section=2"><?php  echo gettext("Add Charge"); ?></a></li>
				<li><a href="A2B_entity_ecommerce.php?atmenu=ecommerce&stitle=E-Commerce&section=2"><?php  echo gettext("List E-Product"); ?></a></li>
				<li><a href="A2B_entity_ecommerce.php?form_action=ask-add&atmenu=ecommerce&stitle=E-Commerce&section=2"><?php  echo gettext("Add E-Product"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXRATECARD'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img3" 
	<?php if (( $this->_tpl_vars['section'] == '3' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?>
	  onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong><?php  echo gettext("RATECARD"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '3' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_tariffgroup.php?form_action=ask-add&atmenu=tariffgroup&stitle=Tariff+Group&section=3"><?php  echo gettext("Create Call Plan"); ?></a></li>
				<li><a href="A2B_entity_tariffgroup.php?atmenu=tariffgroup&stitle=TariffGroup&section=3"><?php  echo gettext("List Call Plan"); ?></a></li>
				<li><a href="A2B_entity_tariffplan.php?atmenu=tariffplan&stitle=Tariffplan&section=3"><?php  echo gettext("List RateCard"); ?></a></li>
				<li><a href="A2B_entity_tariffplan.php?form_action=ask-add&atmenu=tariffplan&stitle=RateCard&section=3"><?php  echo gettext("Create new RateCard"); ?></a></li>
				<li><a href="A2B_entity_def_ratecard.php?atmenu=ratecard&stitle=RateCard&section=3"><?php  echo gettext("Browse Rates"); ?></a></li>
				<li><a href="A2B_entity_def_ratecard.php?form_action=ask-add&atmenu=ratecard&stitle=RateCard&section=3"><?php  echo gettext("Add Rate"); ?></a></li>
				<li><a href="CC_ratecard_import.php?atmenu=ratecard&stitle=RateCard&section=3"><?php  echo gettext("Import RateCard"); ?></a></li>
				<li><a href="CC_entity_sim_ratecard.php?atmenu=ratecard&stitle=Ratecard+Simulator&section=3"><?php  echo gettext("Ratecard Simulator"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXPACKAGEOFFER'] > 0 )): ?>	
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img3_3" 
	<?php if (( $this->_tpl_vars['section'] == '3_3' )): ?>	
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"	
	<?php endif; ?>
	  onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong><?php  echo gettext("PACKAGE OFFER"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '3_3' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>				
				<li><a href="A2B_entity_package.php?atmenu=prefixe&stitle=Prefix&section=3_3"><?php  echo gettext("List Offer Package"); ?></a></li>
				<li><a href="A2B_entity_package.php?form_action=ask-add&atmenu=prefixe&stitle=Prefix&section=3_3"><?php  echo gettext("Add Offer Package"); ?></a></li>
				<li><a href="A2B_detail_package.php?section=3_3"><?php  echo gettext("Details Package"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXOUTBOUNDCID'] > 0 )): ?>	
	<div class="toggle_menu">	
	<li><a href="#" class="toggle_menu" target="_self"><img id="img3_5" 
	<?php if (( $this->_tpl_vars['section'] == '3_5' )): ?>	
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"	
	<?php endif; ?>
	  onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong><?php  echo gettext("OUTBOUND CID"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '3_5' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_outbound_cidgroup.php?form_action=ask-add&atmenu=cidgroup&stitle=CIDGroup&section=3_5"><?php  echo gettext("Create CIDGroup"); ?></a></li>
				<li><a href="A2B_entity_outbound_cidgroup.php?atmenu=cidgroup&stitle=CIDGroup&section=3_5"><?php  echo gettext("List CIDGroup"); ?></a></li>
				<li><a href="A2B_entity_outbound_cid.php?form_action=ask-add&atmenu=cidgroup&stitle=CIDGroup&section=3_5"><?php  echo gettext("Add CID"); ?></a></li>
				<li><a href="A2B_entity_outbound_cid.php?atmenu=cid&stitle=CID&section=3_5"><?php  echo gettext("List CID's"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXTRUNK'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img4"  
	<?php if (( $this->_tpl_vars['section'] == '4' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong><?php  echo gettext("TRUNK"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '4' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_trunk.php?stitle=Trunk&section=4"><?php  echo gettext("List Trunk"); ?></a></li>
				<li><a href="A2B_entity_trunk.php?stitle=Trunk&form_action=ask-add&section=4"><?php  echo gettext("Add Trunk"); ?></a></li>
				<li><a href="A2B_entity_provider.php?stitle=Provider&section=4"><?php  echo gettext("List Provider"); ?></a></li>
				<li><a href="A2B_entity_provider.php?stitle=Provider&form_action=ask-add&section=4"><?php  echo gettext("Create Provider"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXDID'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img41"  
	<?php if (( $this->_tpl_vars['section'] == '5' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?>
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong><?php  echo gettext("DID"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '5' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_didgroup.php?stitle=DID+Group&section=5"><?php  echo gettext("List DID Group"); ?></a>
				<li><a href="A2B_entity_didgroup.php?stitle=DID+Group&form_action=ask-add&section=5"><?php  echo gettext("Add DID Group"); ?></a></li>
				<li><a href="A2B_entity_did.php?stitle=DID&section=5"><?php  echo gettext("List DID"); ?></a></li>
				<li><a href="A2B_entity_did.php?stitle=DID&form_action=ask-add&section=5"><?php  echo gettext("Add DID"); ?></a></li>
                <li><a href="A2B_entity_did_import.php?stitle=DID&section=5"><?php  echo gettext("Import DID"); ?></a></li>
				<li><a href="A2B_entity_did_destination.php?stitle=DID+Destination&section=5"><?php  echo gettext("List Destination"); ?></a></li>
				<li><a href="A2B_entity_did_destination.php?stitle=DID+Destination&form_action=ask-add&section=5"><?php  echo gettext("Add Destination"); ?></a></li>
				<li><a href="A2B_entity_did_billing.php?atmenu=did_billing&stitle=DID+BILLING&section=5"><?php  echo gettext("DID Billing"); ?></a></li>
				<li><a href="A2B_entity_did_use.php?atmenu=did_use&stitle=DID+USE&section=5"><?php  echo gettext("DID Usage"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXCALLREPORT'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img5" 
	<?php if (( $this->_tpl_vars['section'] == '6' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9"> &nbsp;<strong><?php  echo gettext("CALL REPORT"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '6' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
					<li><a href="call-log-customers.php?stitle=Call_Report_Customers&nodisplay=1&posted=1&section=6"><?php  echo gettext("CDR Report"); ?></a></li>
					<li><a href="call-comp.php?section=6"><?php  echo gettext("Calls Compare"); ?></a></li>
					<li><a href="call-last-month.php?section=6"><?php  echo gettext("Monthly Traffic"); ?></a></li>
					<li><a href="call-daily-load.php?section=6"><?php  echo gettext("Daily Load"); ?></a></li>
					<li><a href="call-count-reporting.php?stitle=Call_Reporting&nodisplay=1&posted=1&section=6"><?php  echo gettext("Report"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXINVOICING'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img2" 
	<?php if (( $this->_tpl_vars['section'] == '13' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?>
	 onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("INVOICES"); ?></strong></a></li>
	<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '13' )): ?>
	style="">
	<?php else: ?>
	style="display:none;">	
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_view_invoice.php?atmenu=payment&stitle=Solde&section=13"><?php  echo gettext("View Invoices"); ?></a></li>
				<li><a href="A2B_entity_create_invoice.php?atmenu=payment&stitle=Solde&section=13"><?php  echo gettext("Create Invoices"); ?></a></li>
				<li><a href="invoices.php?stitle=Invoice&nodisplay=1&section=13"><?php  echo gettext("Invoice"); ?></a></li>
				<li><a href="invoices_customer.php?stitle=Invoice&nodisplay=1&section=13"><?php  echo gettext("Invoices Customer"); ?></a></li>
				<li><a href="A2B_entity_invoices.php?atmenu=payment&stitle=Solde&section=13&invoicetype=billed"><?php  echo gettext("View Billed Invoices"); ?></a></li>
				<li><a href="A2B_entity_invoices.php?atmenu=payment&stitle=Solde&section=13&invoicetype=unbilled"><?php  echo gettext("View UnBilled Invoices"); ?></a></li>				
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXCRONTSERVICE'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img9" 
	<?php if (( $this->_tpl_vars['section'] == '7' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("RECURRING SERVICE"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '7' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_autorefill.php?stitle=Auto+Refill&section=7"><?php  echo gettext("AutoRefill Report"); ?></a></li>
				<li><a href="A2B_entity_service.php?stitle=Recurring+Service&section=7"><?php  echo gettext("List Recurring Service"); ?></a></li>
				<li><a href="A2B_entity_service.php?stitle=Recurring+Service&form_action=ask-add&section=7"><?php  echo gettext("Add Recurring Service"); ?></a></li>
				<li><a href="A2B_entity_alarm.php?stitle=Alarm&section=7"> <?php  echo gettext("List Alarm"); ?></a></li>
				<li><a href="A2B_entity_alarm.php?stitle=Alarm&form_action=ask-add&section=7"><?php  echo gettext("Add Alarm"); ?></a></li>
				<li><a href="A2B_entity_subscription.php?stitle=Subscription&section=7"><?php  echo gettext("List Subscription"); ?></a></li>
				<li><a href="A2B_entity_subscription.php?stitle=Subscription&form_action=ask-add&section=7"><?php  echo gettext("Add Subscription"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	
	<?php if (( $this->_tpl_vars['ACXCALLBACK'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img10" 
	<?php if (( $this->_tpl_vars['section'] == '12' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("CALLBACK"); ?></strong></a></li>
	<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '12' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_callback.php?section=12"><?php  echo gettext("Show Callbacks"); ?></a></li>
				<li><a href="A2B_entity_callback.php?form_action=ask-add&section=12"><?php  echo gettext("Add new Callback"); ?></a></li>
				<li><a href="A2B_entity_server_group.php?section=12"><?php  echo gettext("Show Server Group"); ?></a></li>
				<li><a href="A2B_entity_server_group.php?form_action=ask-add&section=12"><?php  echo gettext("Add Server Group"); ?></a></li>
				<li><a href="A2B_entity_server.php?section=12"><?php  echo gettext("Show Server"); ?></a></li>
				<li><a href="A2B_entity_server.php?form_action=ask-add&section=12"><?php  echo gettext("Add Server"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	

	<?php if (( $this->_tpl_vars['ACXMISC'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img6" 
	<?php if (( $this->_tpl_vars['section'] == '8' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?>
	onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("MISC"); ?></strong></a></li>
	<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '8' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_mailtemplate.php?atmenu=mailtemplate&stitle=Mail+Tempalte&section=8"><?php  echo gettext("Show mail template"); ?></a></li>
				<li><a href="A2B_entity_mailtemplate.php?form_action=ask-add&atmenu=mailtemplate&stitle=Mail+Tempalte&section=8"><?php  echo gettext("Create mail template"); ?></a></li>
				<li><a href="A2B_entity_prefix.php?atmenu=prefixe&stitle=Prefix&section=8"><?php  echo gettext("Browse Prefix"); ?></a></li>
				<li><a href="A2B_entity_prefix.php?form_action=ask-add&atmenu=prefixe&stitle=Prefix&section=8"><?php  echo gettext("Add Prefix"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXADMINISTRATOR'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img7" 
	<?php if (( $this->_tpl_vars['section'] == '10' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("ADMINISTRATOR"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '10' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=0&stitle=Administrator+management&section=10"><?php  echo gettext("Show Administrator"); ?></a></li>
				<li><a href="A2B_entity_user.php?form_action=ask-add&atmenu=user&groupID=0&stitle=Administrator+management&section=10"><?php  echo gettext("Add Administrator"); ?></a></li>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=1&stitle=ACL+Admin+management&section=10"><?php  echo gettext("Show ACL Admin"); ?></a></li>
				<li><a href="A2B_entity_user.php?form_action=ask-add&atmenu=user&groupID=1&stitle=ACL+Admin+management&section=10"><?php  echo gettext("Add ACL Admin"); ?></a></li>
				<li><a href="A2B_entity_backup.php?form_action=ask-add&section=10"><?php  echo gettext("Database Backup"); ?></a></li>
				<li><a href="A2B_entity_restore.php?section=10"><?php  echo gettext("Database Restore"); ?></a></li>
				<li><a href="A2B_logfile.php?section=10"><?php  echo gettext("Watch Log files"); ?></a></li>
				<li><a href="A2B_entity_log_viewer.php?section=10"><?php  echo gettext("System Log"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXFILEMANAGER'] > 0 )): ?>
	<div class="toggle_menu">
	<li><a href="#" class="toggle_menu" target="_self"><img id="img8" 
	<?php if (( $this->_tpl_vars['section'] == '11' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("FILE MANAGER"); ?></strong></a></li>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '11' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="CC_musiconhold.php?section=11"><?php  echo gettext("MusicOnHold"); ?></a></li>
				<li><a href="CC_upload.php?section=11"><?php  echo gettext("Standard File"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	</div>
	<?php endif; ?>

	<li><a href="#" target="_self"></a></a></li>
	<ul>
		<li><ul>
		<li><a href="logout.php?logout=true" target="_top"><font color="#DD0000"><b>&nbsp;&nbsp;<?php  echo gettext("LOGOUT"); ?></b></font></a></li>
		</ul></li>
	</ul>

</ul>
<div id="nav_after"></div>
<br>
<table>
<tr>
	<td>
		<a href="PP_intro.php?language=english" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gb.gif" border="0" title="English" alt="English"></a>
	</td>
</tr>
<tr>
		<td>
			<form action="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
" method="post">
				<select name="cssname" class="form_input_select" >
					<option value="default" <?php echo smarty_function_checkseleted(array('file' => 'default'), $this);?>
>Default</option>
				</select>
				<input type="submit" value="Change" class="form_input_button" >
			</form>
		</td>
	</tr>
</table>


</div>

<div class="divright">

<?php else: ?>
<div>
<?php endif; ?>