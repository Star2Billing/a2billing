<?php /* Smarty version 2.6.25-dev, created on 2010-10-25 13:05:37
         compiled from main.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if (( $this->_tpl_vars['popupwindow'] == 0 )): ?>
<?php if (( $this->_tpl_vars['EXPORT'] == 0 )): ?>
<div id="left-sidebar">
<div id="leftmenu-top">
<div id="leftmenu-down">
<div id="leftmenu-middle">

	
<ul id="nav">

	<div class="toggle_menu"><li><a href="userinfo.php"><strong><?php  echo gettext("ACCOUNT INFO"); ?></strong></a></li></div>
	
	<?php if ($this->_tpl_vars['ACXVOICEMAIL'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_voicemail.php"><strong><?php  echo gettext("VOICEMAIL"); ?></strong></a></li></div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['ACXSIP_IAX'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_sipiax_info.php"><strong><?php  echo gettext("SIP/IAX INFO"); ?></strong></a></li></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['ACXCALL_HISTORY'] > 0): ?>
	<div class="toggle_menu"><li><a href="call-history.php"><strong><?php  echo gettext("CALL HISTORY"); ?></strong></a></li></div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['ACXPAYMENT_HISTORY'] > 0): ?>
	<div class="toggle_menu"><li><a href="payment-history.php"><strong><?php  echo gettext("PAYMENT HISTORY"); ?></strong></a></li></div>
	<?php endif; ?>
	

	<?php if ($this->_tpl_vars['ACXVOUCHER'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_voucher.php?form_action=list"><strong><?php  echo gettext("VOUCHERS"); ?></strong></a></li></div>
	<?php endif; ?>


	<?php if ($this->_tpl_vars['ACXINVOICES'] > 0): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img5"
	<?php if (( $this->_tpl_vars['section'] == '5' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("INVOICES"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '5' )): ?>
		style="">
	<?php else: ?>
	style="display:none;">
	<?php endif; ?>
	<ul>
		<li><ul>
				<li><a href="A2B_entity_receipt.php?section=5"><strong><?php  echo gettext("View Receipts"); ?></strong></a></li>
				<li><a href="A2B_entity_invoice.php?section=5"><strong><?php  echo gettext("View Invoices"); ?></strong></a></li>
				<li><a href="A2B_billing_preview.php?section=5"><strong><?php  echo gettext("Preview Next Billing"); ?></strong></a></li>
		</ul></li>
	</ul>
	</div>
	<?php endif; ?>


	<?php if ($this->_tpl_vars['ACXDID'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_did.php?form_action=list"><strong><?php  echo gettext("DID"); ?></strong></a></li></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['ACXSPEED_DIAL'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_speeddial.php?atmenu=speeddial&stitle=Speed+Dial"><strong><?php  echo gettext("SPEED DIAL"); ?></strong></a></li></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['ACXRATECARD'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_ratecard.php?form_action=list"><strong><?php  echo gettext("RATECARD"); ?></strong></a></li></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['ACXSIMULATOR'] > 0): ?>
	<div class="toggle_menu"><li><a href="simulator.php"><strong><?php  echo gettext("SIMULATOR"); ?></strong></a></li></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['ACXCALL_BACK'] > 0): ?>
	<div class="toggle_menu"><li><a href="callback.php"><strong><?php  echo gettext("CALLBACK"); ?></strong></a></li></div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['ACXCALLER_ID'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_callerid.php?atmenu=callerid&stitle=CallerID"><strong><?php  echo gettext("ADD CALLER ID"); ?></strong></a></li></div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['ACXPASSWORD'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_entity_password.php?atmenu=password&form_action=ask-edit&stitle=Password"><strong><?php  echo gettext("PASSWORD"); ?></strong></a></li></div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['ACXSUPPORT'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_support.php"><strong><?php  echo gettext("SUPPORT"); ?></strong></a></li></div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['ACXNOTIFICATION'] > 0): ?>
	<div class="toggle_menu"><li><a href="A2B_notification.php?form_action=ask-edit"><strong><?php  echo gettext("NOTIFICATION"); ?></strong></a></li></div>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['ACXAUTODIALER'] > 0): ?>
	<div class="toggle_menu"><li>
	<a href="javascript:;" class="toggle_menu" target="_self"> <div> <div id="menutitlebutton"> <img id="img10"
	<?php if (( $this->_tpl_vars['section'] == '10' )): ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
	<?php else: ?>
	src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
	<?php endif; ?> onmouseover="this.style.cursor='hand';" ></div> <div id="menutitlesection"><strong><?php  echo gettext("AUTO DIALLER"); ?></strong></div></div></a></li></div>
		<div class="tohide"
	<?php if (( $this->_tpl_vars['section'] == '10' )): ?>
		style="">
	<?php else: ?>
	style="display:none;">
	<?php endif; ?>
	<ul>
		<li><ul>
				<li><a href="A2B_entity_campaign.php?section=10"><?php  echo gettext("Campaign's"); ?></a></li>
				<li><a href="A2B_entity_phonebook.php?section=10"><?php  echo gettext("Phone Book"); ?></a></li>
				<li><a href="A2B_entity_phonenumber.php?section=10"><?php  echo gettext("Phone Number"); ?></a></li>
				<li><a href="A2B_phonelist_import.php?section=10"><?php  echo gettext("Import Phone List"); ?></a></li>
		</ul></li>
	</ul>
	</div>
	<?php endif; ?>

</ul>

<br/>
<ul id="nav"><li>
	<ul><li><a href="logout.php?logout=true" target="_top"><img style="vertical-align:bottom;" src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/logout.png"> <font color="#DD0000"><STRONG>&nbsp;&nbsp;<?php  echo gettext("LOGOUT"); ?></STRONG></font> </a></li></ul>
</li></ul>

</div>
</div>
</div>


<table width="90%" cellspacing="15">
<tr>
   <td>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=english"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gb.gif" border="0" title="English" alt="English"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=spanish"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=french"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/fr.gif" border="0" title="French" alt="French"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=german"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/de.gif" border="0" title="German" alt="German"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=portuguese"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/pt.gif" border="0" title="Portuguese" alt="Portuguese"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=brazilian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=italian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/it.gif" border="0" title="Italian" alt="Italian"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=romanian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ro.gif" border="0" title="Romanian"alt="Romanian"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=chinese"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/cn.gif" border="0" title="Chinese" alt="Chinese"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=polish"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/pl.gif" border="0" title="Polish" alt="Polish"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=russian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ru.gif" border="0" title="russian" alt="russian"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=turkish"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/tr.gif" border="0" title="Turkish" alt="Turkish"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=urdu"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/pk.gif" border="0" title="Urdu" alt="Urdu"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=ukrainian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ua.gif" border="0" title="Ukrainian" alt="Ukrainian"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=farsi"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ir.gif" border="0" title="Farsi" alt="Farsi"></a>
		<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?ui_language=greek"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gr.gif" border="0" title="Greek" alt="Greek"></a>
   </td>
</tr>


</table>


</div>

<div id="main-content">
<br/>
<?php else: ?>
<div>
<?php endif; ?>
<?php else: ?>
<div>
<?php endif; ?>


<?php echo $this->_tpl_vars['MAIN_MSG']; ?>
