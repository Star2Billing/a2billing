<?php /* Smarty version 2.6.13, created on 2007-05-31 12:02:53
         compiled from main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'checkseleted', 'main.tpl', 151, false),)), $this); ?>
<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/animated_favicon1.ico">
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
</p>
<br>

<DIV border=0 width="1000">


<?php if (( $this->_tpl_vars['popupwindow'] == 0 )):  if (( $this->_tpl_vars['EXPORT'] == 0 )): ?>
<div class="divleft">

	<div id="nav_before"></div>
	<ul id="nav">
		
		
		
		<div>
		<ul><li><a href="userinfo.php?section=1"><strong><?php  echo gettext("ACCOUNT INFO"); ?></strong></a></li></ul>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['sipiaxinfo'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_sipiax_info.php?section=1"><strong><?php  echo gettext("SIP/IAX INFO"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['cdr'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="call-history.php?section=2"><strong><?php  echo gettext("CALL HISTORY"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['voucher'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_voucher.php?form_action=list&section=3"><strong><?php  echo gettext("VOUCHER"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<li><a href="#" target="_self"></a></li>
		</div>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['invoice'] == 1): ?>
		<div class="toggle_menu">
		<li>
		<a href="#" class="toggle_menu" target="_self"><img id="img1"  
		<?php if (( $this->_tpl_vars['section'] == '4' )): ?>
		src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/minus.gif"
		<?php else: ?>
		src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/plus.gif"
		<?php endif; ?>
	 	onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong><?php  echo gettext("INVOICES"); ?></strong></a></li>
		<div class="tohide" 
		<?php if (( $this->_tpl_vars['section'] == '4' )): ?>
		style="">
		<?php else: ?>
		style="display:none;">
		<?php endif; ?>
		<ul>
			<li><ul>
					<li><a href="A2B_entity_call_details.php?section=4"><strong><?php  echo gettext("Invoice Details"); ?></strong></a></li>
					<li><a href="A2B_entity_view_invoice.php?section=4"><strong><?php  echo gettext("View Invoices"); ?></strong></a></li>
					<li><a href="invoices_customer.php?section=4"><strong><?php  echo gettext("Current Invoice"); ?></strong></a></li>
			</ul></li>
		</ul>
		</div>
		</div>
		<?php endif; ?>
		

		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['did'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_did.php?form_action=list&section=5"><strong><?php  echo gettext("DID"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['speeddial'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_speeddial.php?atmenu=speeddial&stitle=Speed+Dial&section=6"><strong><?php  echo gettext("SPEED DIAL"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['ratecard'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_ratecard.php?form_action=list&section=7"><strong><?php  echo gettext("RATECARD"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['simulator'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="simulator.php?section=8"><strong><?php  echo gettext("SIMULATOR"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['callback'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="callback.php?section=9"><strong><?php  echo gettext("CALLBACK"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['webphone'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="webphone.php?section=11"><strong><?php  echo gettext("WEB-PHONE"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['callerid'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_callerid.php?atmenu=callerid&stitle=CallerID&section=12"><strong><?php  echo gettext("ADD CALLER ID"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['A2Bconfig']['webcustomerui']['password'] == 1): ?>
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_password.php?atmenu=password&form_action=ask-edit&stitle=Password&section=13"><strong><?php  echo gettext("PASSWORD"); ?></strong></a></li></ul>
		<?php endif; ?>
		
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="logout.php?logout=true" target="_parent"><font color="#DD0000"><strong><?php  echo gettext("LOGOUT"); ?></strong></font></a></li></ul>

	</ul>
	<div id="nav_after"></div>

	<table width="150">
	<tr>
	   <td>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=espanol"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=english"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gb.gif" border="0" title="English" alt="English"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=french"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/fr.gif" border="0" title="French" alt="French"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=romanian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ro.gif" border="0" title="Romanian"alt="Romanian"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=chinese"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/cn.gif" border="0" title="Chinese" alt="Chinese"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=polish"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/pl.gif" border="0" title="Polish" alt="Polish"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=italian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/it.gif" border="0" title="Italian" alt="Italian"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=russian"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ru.gif" border="0" title="russian" alt="russian"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=turkish"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/tr.gif" border="0" title="Turkish" alt="Turkish"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=portuguese"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/pt.gif" border="0" title="Portuguese" alt="Portuguese"></a>
			<a href="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
?language=urdu"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/pk.gif" border="0" title="Urdu" alt="Urdu"></a>
	   </td>
	</tr>
	<tr>
		<td>
			<form action="<?php echo $this->_tpl_vars['PAGE_SELF']; ?>
" method="post">
				<select name="cssname" class="form_input_select" >
					<option value="default" <?php echo smarty_function_checkseleted(array('file' => 'default'), $this);?>
>Default</option>
					<option value="design1" <?php echo smarty_function_checkseleted(array('file' => 'design1'), $this);?>
>Design 1</option>
				</select>
				<input type="submit" value="Change" class="form_input_button" >
			</form>
		</td>
	</tr>
	</table>


</div>
<div class="divright">
<?php endif;  endif; ?>