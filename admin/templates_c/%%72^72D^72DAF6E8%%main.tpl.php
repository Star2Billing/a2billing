<?php /* Smarty version 2.6.25-dev, created on 2014-05-06 11:44:22
         compiled from main.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php if (( $this->_tpl_vars['popupwindow'] == 0 )): ?>
	<div id="top_menu">
		<ul id="menu_horizontal">
			<li class="topmenu-left-button" style="border:none;">
				<div style="width:100%;height:100%;text-align:center;" >
					<a href="PP_intro.php"> 
							<strong> <?php  echo gettext("HOME"); ?></strong>&nbsp;
						<img style="vertical-align:bottom;" src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/house.png"> 
					</a>
				</div>
			</li>
			<?php if (( $this->_tpl_vars['ACXDASHBOARD'] > 0 )): ?>
			<li class="topmenu-left-button" >
				<div style="width:100%;height:100%;text-align:center;" >
					<a href="dashboard.php" > 
						<strong> <?php  echo gettext("DASHBOARD"); ?></strong>&nbsp;
						<img style="vertical-align:bottom;" src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/chart_bar.png"> 
					</a>
				</div>
			</li>
			<?php endif; ?>
			<li class="topmenu-left-button">
				<div style="width:100%;height:100%;text-align:center;" >
					 <a href="A2B_notification.php" > 
						<strong > <?php  echo gettext("NOTIFICATION"); ?></strong>&nbsp;
					<img style="vertical-align:bottom;" src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/email.png"> 
					<?php if (( $this->_tpl_vars['NEW_NOTIFICATION'] > 0 )): ?>
						<strong style="font-size:8px; color:red;"> NEW</strong>
					<?php else: ?>
						<strong style="font-size:8px;">&nbsp;</strong>
					<?php endif; ?>
					  </a>
				</div>
			</li>
			<li class="topmenu-right-button" style="border-right:none;">
				<div style="width:90%;height:100%;text-align:center;" >
					<a href="logout.php?logout=true" target="_top"><font color="#EC3F41"><b>&nbsp;&nbsp;<?php  echo gettext("LOGOUT"); ?></b></font>
					<img style="vertical-align:bottom;" src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/logout.png"> </a>
				</div>
			</li>
		</ul>

	</div>
	
<?php endif; ?>

<?php if (( $this->_tpl_vars['popupwindow'] == 0 )): ?>
<div id="left-sidebar">
<div id="leftmenu-top">
<div id="leftmenu-down">
<div id="leftmenu-middle">

<ul id="nav">
  
  	<?php if (( $this->_tpl_vars['ACXCUSTOMER'] > 0 )): ?>
  	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img1"
	<?php if (( $this->_tpl_vars['section'] == '1' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("CUSTOMERS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '1' )): ?>
		style="">
	<?php else: ?>
	style="display:none;">
	<?php endif; ?>
	<ul>
		<li><ul>
				<li><a href="A2B_entity_card.php?section=1"><?php  echo gettext("Add :: Search"); ?></a></li>
                <li><a href="CC_card_import.php?section=1"><?php  echo gettext("Import"); ?></a></li>
				<li><a href="A2B_entity_friend.php?atmenu=sip&section=1"><?php  echo gettext("VoIP Settings"); ?></a></li>
				<li><a href="A2B_entity_callerid.php?atmenu=callerid&section=1"><?php  echo gettext("Caller-ID"); ?></a></li>
				<li><a href="A2B_notifications.php?section=1"><?php  echo gettext("Credit Notification"); ?></a></li>
				<li><a href="A2B_entity_card_group.php?section=1"><?php  echo gettext("Groups"); ?></a></li>
				<li><a href="A2B_entity_card_seria.php?section=1"><?php  echo gettext("Card series"); ?></a></li>
				<li><a href="A2B_entity_speeddial.php?atmenu=speeddial&section=1"><?php  echo gettext("Speed Dial"); ?></a></li>
				<li><a href="card-history.php?atmenu=cardhistory&section=1"><?php  echo gettext("History"); ?></a></li>
				<li><a href="A2B_entity_statuslog.php?atmenu=statuslog&section=1"><?php  echo gettext("Status"); ?></a></li>
		</ul></li>
	</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXADMINISTRATOR'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img2"
	<?php if (( $this->_tpl_vars['section'] == '2' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("AGENTS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '2' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_agent.php?atmenu=user&section=2"><?php  echo gettext("Add :: Search"); ?></a></li>
				<li><a href="A2B_entity_signup_agent.php?atmenu=user&section=2"><?php  echo gettext("Signup URLs"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>


	<?php if (( $this->_tpl_vars['ACXADMINISTRATOR'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img3"
	<?php if (( $this->_tpl_vars['section'] == '3' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("ADMINS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '3' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=0&section=3"><?php  echo gettext("Add :: Search"); ?></a></li>
				<li><a href="A2B_entity_user.php?atmenu=user&groupID=1&section=3"><?php  echo gettext("Access Control"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXSUPPORT'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img4"
	<?php if (( $this->_tpl_vars['section'] == '4' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("SUPPORT"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '4' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="CC_ticket.php?section=4"><?php  echo gettext("Customer Tickets"); ?></a></li>
				<li><a href="A2B_ticket_agent.php?section=4"><?php  echo gettext("Agent Tickets"); ?></a></li>
				<li><a href="CC_support_component.php?section=4"><?php  echo gettext("Ticket Components"); ?></a></li>
				<li><a href="CC_support.php?section=4"><?php  echo gettext("Support Boxes"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXCALLREPORT'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img5"
	<?php if (( $this->_tpl_vars['section'] == '5' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("CALL REPORTS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '5' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
					<li><a href="call-log-customers.php?nodisplay=1&posted=1&section=5"><?php  echo gettext("CDRs"); ?></a></li>
					<li><a href="call-count-reporting.php?nodisplay=1&posted=1&section=5"><?php  echo gettext("Call Count"); ?></a></li>
					<li><a href="A2B_trunk_report.php?section=5"><?php  echo gettext("Trunk"); ?></a></li>
					<li><a href="call-dnid.php?nodisplay=1&posted=1&section=5"><?php  echo gettext("DNID"); ?></a></li>
					<li><a href="call-pnl-report.php?section=5"><?php  echo gettext("PNL"); ?></a></li>
					<li><a href="call-comp.php?section=5"><?php  echo gettext("Compare Calls"); ?></a></li>
					<li><a href="call-daily-load.php?section=5"><?php  echo gettext("Daily Traffic"); ?></a></li>
					<li><a href="call-last-month.php?section=5"><?php  echo gettext("Monthly Traffic"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXRATECARD'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img6"
	<?php if (( $this->_tpl_vars['section'] == '6' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("RATES"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '6' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_tariffgroup.php?atmenu=tariffgroup&section=6"><?php  echo gettext("Call Plan"); ?></a></li>
				<li><a href="A2B_entity_tariffplan.php?atmenu=tariffplan&section=6"><?php  echo gettext("RateCards"); ?></a></li>
				<li><a href="CC_ratecard_import.php?atmenu=ratecard&section=6">»» <?php  echo gettext("Import"); ?></a></li>
				<li><a href="CC_ratecard_merging.php?atmenu=ratecard&section=6">»» <?php  echo gettext("Merge"); ?></a></li>
				<li><a href="CC_entity_sim_ratecard.php?atmenu=ratecard&section=6">»» <?php  echo gettext("Simulator"); ?></a></li>
				<li><a href="A2B_entity_def_ratecard.php?atmenu=ratecard&section=6"><?php  echo gettext("Rates"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXTRUNK'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img7"
	<?php if (( $this->_tpl_vars['section'] == '7' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("PROVIDERS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '7' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_provider.php?section=7"><?php  echo gettext("Providers"); ?></a></li>
				<li><a href="A2B_entity_trunk.php?section=7"><?php  echo gettext("Trunks"); ?></a></li>
				<li><a href="A2B_entity_prefix.php?section=7"><?php  echo gettext("Prefixes"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXDID'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img8"
	<?php if (( $this->_tpl_vars['section'] == '8' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("INBOUND DID"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '8' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_did.php?section=8"><?php  echo gettext("Add :: Search"); ?></a></li>
				<li><a href="A2B_entity_didgroup.php?section=8"><?php  echo gettext("Groups"); ?></a>
				<li><a href="A2B_entity_did_destination.php?section=8"><?php  echo gettext("Destination"); ?></a></li>
				<li><a href="A2B_entity_did_import.php?section=8"><?php  echo gettext("Import [CSV]"); ?></a></li>
				<li><a href="A2B_entity_didx.php?section=8"><?php  echo gettext("Import [DIDX]"); ?></a></li>
				<li><a href="A2B_entity_did_use.php?atmenu=did_use&section=8"><?php  echo gettext("Usage"); ?></a></li>
				<li><a href="A2B_entity_did_billing.php?atmenu=did_billing&section=8"><?php  echo gettext("Billing"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>
	

	<?php if (( $this->_tpl_vars['ACXOUTBOUNDCID'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img9"
	<?php if (( $this->_tpl_vars['section'] == '9' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("OUTBOUND CID"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '9' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_outbound_cid.php?atmenu=cid&section=9"><?php  echo gettext("Add"); ?></a></li>
				<li><a href="A2B_entity_outbound_cidgroup.php?atmenu=cidgroup&section=9"><?php  echo gettext("Groups"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>


	
	<?php if (( $this->_tpl_vars['ACXBILLING'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img10"
	<?php if (( $this->_tpl_vars['section'] == '10' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("BILLING"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '10' )): ?>
		style="">
	<?php else: ?>
	style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_voucher.php?section=10"><?php  echo gettext("Vouchers"); ?></a></li>
				<li><a href="A2B_entity_moneysituation.php?atmenu=moneysituation&section=10"><?php  echo gettext("Customers Balance"); ?></a></li>
                <li><a href="A2B_entity_transactions.php?atmenu=payment&section=10">»» <?php  echo gettext("Transactions"); ?></a></li>
				<li><a href="A2B_entity_billing_customer.php?atmenu=payment&section=10">»» <?php  echo gettext("Billings"); ?></a></li>
				<li><a href="A2B_entity_logrefill.php?atmenu=payment&section=10">»» <?php  echo gettext("Refills"); ?></a></li>
				<li><a href="A2B_entity_payment.php?atmenu=payment&section=10">»» <?php  echo gettext("Payments"); ?></a></li>
				<li><a href="A2B_entity_paymentlog.php?section=10">»» <?php  echo gettext("E-Payment Log"); ?></a></li>
				<li><a href="A2B_entity_charge.php?section=10">»» <?php  echo gettext("Charges"); ?></a></li>
				<li><a href="A2B_entity_agentsituation.php?atmenu=agentsituation&section=10"><?php  echo gettext("Agents Balance"); ?></a></li>
				<li><a href="A2B_entity_commission_agent.php?atmenu=payment&section=10">»» <?php  echo gettext("Commissions"); ?></a></li>
				<li><a href="A2B_entity_remittance_request.php?atmenu=payment&section=10">»» <?php  echo gettext("Remittance Request"); ?></a></li>
				<li><a href="A2B_entity_transactions_agent.php?atmenu=payment&section=10">»» <?php  echo gettext("Transactions"); ?></a></li>
				<li><a href="A2B_entity_logrefill_agent.php?atmenu=payment&section=10">»» <?php  echo gettext("Refills"); ?></a></li>
				<li><a href="A2B_entity_payment_agent.php?atmenu=payment&section=10">»» <?php  echo gettext("Payments"); ?></a></li>
				<li><a href="A2B_entity_paymentlog_agent.php?section=10">»» <?php  echo gettext("E-Payment Log"); ?></a></li>
				<li><a href="A2B_entity_payment_configuration.php?atmenu=payment&section=10"><?php  echo gettext("Payment Methods"); ?></a></li>
				<li><a href="A2B_currencies.php?section=10"><?php  echo gettext("Currency List"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>


	<?php if (( $this->_tpl_vars['ACXINVOICING'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img11"
	<?php if (( $this->_tpl_vars['section'] == '11' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("INVOICES"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '11' )): ?>
		style="">
	<?php else: ?>
	style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_receipt.php?atmenu=payment&section=11"><?php  echo gettext("Receipts"); ?></a></li>
				<li><a href="A2B_entity_invoice.php?atmenu=payment&section=11"><?php  echo gettext("Invoices"); ?></a></li>
				<li><a href="A2B_entity_invoice_conf.php?atmenu=payment&section=11">»» <?php  echo gettext("Configuration"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	
	<?php if (( $this->_tpl_vars['ACXPACKAGEOFFER'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img12"
	<?php if (( $this->_tpl_vars['section'] == '12' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("PACKAGE OFFER"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '12' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_package.php?atmenu=package&section=12"><?php  echo gettext("Add"); ?></a></li>
				<li><a href="A2B_detail_package.php?section=12"><?php  echo gettext("Details"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>
	

	<?php if (( $this->_tpl_vars['ACXCRONTSERVICE'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img13"
	<?php if (( $this->_tpl_vars['section'] == '13' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("RECUR SERVICE"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '13' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_service.php?section=13"><?php  echo gettext("Account Service"); ?></a></li>
				<li><a href="A2B_entity_subscription.php?section=13"><?php  echo gettext("Subscriptions Service"); ?></a></li>
				<li><a href="A2B_entity_subscriber_signup.php?section=13"><?php  echo gettext("Subscriptions SIGNUP"); ?></a></li>
				<li><a href="A2B_entity_subscriber.php?section=13"><?php  echo gettext("Subscribers"); ?></a></li>
				<li><a href="A2B_entity_autorefill.php?section=13"><?php  echo gettext("AutoRefill Report"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>
	

	<?php if (( $this->_tpl_vars['ACXCALLBACK'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img14"
	<?php if (( $this->_tpl_vars['section'] == '14' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("CALLBACK"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '14' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_callback.php?section=14"><?php  echo gettext("Add"); ?></a></li>
				<li><a href="A2B_entity_server_group.php?section=14"><?php  echo gettext("Server Group"); ?></a></li>
				<li><a href="A2B_entity_server.php?section=14"><?php  echo gettext("Server"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['ACXPREDICTIVEDIALER'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img15"
	<?php if (( $this->_tpl_vars['section'] == '15' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("CAMPAIGNS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '15' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_campaign.php?section=15"><?php  echo gettext("Add"); ?></a></li>
				<li><a href="A2B_entity_campaign_config.php?section=15"><?php  echo gettext("Config"); ?></a></li>
				<li><a href="A2B_entity_phonebook.php?section=15"><?php  echo gettext("Phone Book"); ?></a></li>
				<li><a href="A2B_entity_phonenumber.php?section=15">»» <?php  echo gettext("Add Number"); ?></a></li>
				<li><a href="A2B_phonelist_import.php?section=15">»» <?php  echo gettext("Import"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	
	<?php if (( $this->_tpl_vars['ACXMAINTENANCE'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img16"
	<?php if (( $this->_tpl_vars['section'] == '16' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("MAINTENANCE"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '16' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_alarm.php?section=16"> <?php  echo gettext("Alarms"); ?></a></li>
				<li><a href="A2B_entity_log_viewer.php?section=16"><?php  echo gettext("Users Activity"); ?></a></li>
				<li><a href="A2B_entity_backup.php?form_action=ask-add&section=16"><?php  echo gettext("Database Backup"); ?></a></li>
				<li><a href="A2B_entity_restore.php?section=16"><?php  echo gettext("Database Restore"); ?></a></li>
				<li><a href="CC_musiconhold.php?section=16"><?php  echo gettext("MusicOnHold"); ?></a></li>
				<li><a href="CC_upload.php?section=16"><?php  echo gettext("Upload File"); ?></a></li>
				<li><a href="A2B_logfile.php?section=16"><?php  echo gettext("Watch Log files"); ?></a></li>
				<li><a href="A2B_data_archiving.php?section=16"><?php  echo gettext("Archiving"); ?></a></li>
				<li><a href="A2B_asteriskinfo.php?section=16"><?php  echo "Asterisk Info"; ?></a></li>
				<li><a href="A2B_phpsysinfo.php?section=16"><?php  echo "phpSysInfo"; ?></a></li>
				<li><a href="A2B_phpinfo.php?section=16"><?php  echo "phpInfo"; ?></a></li>
				<li><a href="A2B_entity_monitor.php?section=16"> <?php  echo gettext("Monitoring"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	
	<?php endif; ?>
	
	<?php if (( $this->_tpl_vars['ACXMAIL'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img17"
	<?php if (( $this->_tpl_vars['section'] == '17' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("MAIL"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '17' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_mailtemplate.php?atmenu=mailtemplate&section=17&languages=en"><?php  echo gettext("Mail templates"); ?></a></li>
				<li><a href="A2B_mass_mail.php?section=17"><?php  echo gettext("Mass Mail"); ?></a></li>
			</ul></li>
		</ul>
	</div>
	<?php endif; ?>

	
	<?php if (( $this->_tpl_vars['ACXSETTING'] > 0 )): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img18"
	<?php if (( $this->_tpl_vars['section'] == '18' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("SYSTEM SETTINGS"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '18' )): ?>
		style="">
	<?php else: ?>
		style="display:none;">
	<?php endif; ?>
		<ul>
			<li><ul>
				<li><a href="A2B_entity_config.php?form_action=list&atmenu=config&section=18"><?php  echo gettext("Global List"); ?></a></li>
				<li><a href="A2B_entity_config_group.php?form_action=list&atmenu=configgroup&section=18"><?php  echo gettext("Group List"); ?></a></li>
				<li><a href="A2B_entity_config_generate_confirm.php?section=18"><?php  echo gettext("Add agi-conf"); ?></a></li>
				<li><a href="phpconfig.php?dir=/etc/asterisk&section=18"><?php  echo gettext("* Config Editor"); ?></a></li>
				<?php if (( $this->_tpl_vars['ASTERISK_GUI_LINK'] )): ?>
					<li><a href="http://<?php echo $this->_tpl_vars['HTTP_HOST']; ?>
:8088/asterisk/static/config/index.html" target="_blank"><?php  echo gettext("Asterisk GUI"); ?></a></li>
				<?php endif; ?>
			</ul></li>
		</ul>
	</div>
	
	<?php endif; ?>
	
</ul>
	
<br/>
<ul id="nav"><li>
	<ul><li><a href="A2B_entity_password.php?atmenu=password&form_action=ask-edit"><strong><?php  echo gettext("Change Password"); ?></strong> <img style="vertical-align:bottom;" src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/key.png"> </a></li></ul>
</li></ul>

</div>
</div>
</div>



<table width="100%" cellspacing="15">
<tr>
	<td>
		<a href="PP_intro.php?ui_language=english" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gb.gif" border="0" title="English" alt="English"></a>
		<a href="PP_intro.php?ui_language=brazilian" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
		<a href="PP_intro.php?ui_language=romanian" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ro.gif" border="0" title="Romanian"alt="Romanian"></a>
		<a href="PP_intro.php?ui_language=french" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/fr.gif" border="0" title="French" alt="French"></a>
		<a href="PP_intro.php?ui_language=spanish" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
		<a href="PP_intro.php?ui_language=greek" target="_parent"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gr.gif" border="0" title="Greek" alt="Greek"></a>
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
		<p>For more information, please go to <a target="_blank" href="http://www.star2billing.com/licensing">http://www.star2billing.com/licensing</a>.</p>
		<p>Yours,<br/>
		The A2Billing Team<br/>
		Star2Billing S.L</p>
		<p><button class="simplemodal-close">Close</button></p>
	</div>
</div>


</div>

<div id="main-content">
<br/>
<?php else: ?>
<div>
<?php endif; ?>

<?php if (( $this->_tpl_vars['LCMODAL'] > 0 )): ?>
<script type="text/javascript">
    loadLicenceModal();
</script>
<?php endif; ?>
 
<?php echo $this->_tpl_vars['MAIN_MSG']; ?>

