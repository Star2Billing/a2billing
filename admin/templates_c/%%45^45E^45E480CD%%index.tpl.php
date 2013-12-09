<?php /* Smarty version 2.6.25-dev, created on 2013-11-17 18:31:54
         compiled from index.tpl */ ?>
<HTML>
<HEAD>
	<link rel="shortcut icon" href="images/ico/a2billing-icon-32x32.ico">
	<title>..:: <?php echo $this->_tpl_vars['CCMAINTITLE']; ?>
 ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<?php if (( $this->_tpl_vars['CSS_NAME'] != "" && $this->_tpl_vars['CSS_NAME'] != 'default' )): ?>
			   <link href="templates/default/css/<?php echo $this->_tpl_vars['CSS_NAME']; ?>
.css" rel="stylesheet" type="text/css">
		<?php else: ?>
			   <link href="templates/default/css/main.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/menu.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/style-def.css" rel="stylesheet" type="text/css">
		<?php endif; ?>
        <script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<?php echo '
<script LANGUAGE="JavaScript">
<!--
	function test()
	{
		if(document.form.pr_login.value=="" || document.form.pr_password.value=="")
		{
			alert("You must enter an user and a password!");
			return false;
		}
		else
		{
			return true;
		}
	}
-->
</script>

'; ?>


	<form name="form" method="POST" action="PP_intro.php" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">

	
	<div id="login-wrapper" class="login-border-up">
	<div class="login-border-down">
	<div class="login-border-center">
	<center>
	<table border="0" cellpadding="3" cellspacing="12">
	<tr>
		<td class="login-title" colspan="2">
			 <?php  echo gettext("AUTHENTICATION"); ?>
		</td>
	</tr>
	<tr>
		<td ><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/kicons/lock_bg.png"></td>
		<td align="center" style="padding-right: 10px">
			<table width="90%">
			<tr align="center">
				<td align="left"><font size="2" face="Arial, Helvetica, Sans-Serif"><b><?php  echo gettext("User"); ?>:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_login" size="15"></td>
			</tr>
			<tr align="center">
				<td align="left"><font face="Arial, Helvetica, Sans-Serif" size="2"><b><?php  echo gettext("Password"); ?>:</b></font></td>
				<td><input class="form_input_text" type="password" name="pr_password" size="15"></td>
			</tr>
            <tr >
                <td colspan="2"> &nbsp;</td>
            </tr>
			<tr align="right" >
            <td>
                <select name="ui_language"  id="ui_language" class="icon-menu form_input_select">
                    <option style="background-image:url(templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gb.gif);" value="english" <?php  if(LANGUAGE=="english") echo "selected"; ?> >English</option>
                    <option style="background-image:url(templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/br.gif);" value="brazilian" <?php  if(LANGUAGE=="brazilian") echo "selected"; ?>>Brazilian</option>
                    <option style="background-image:url(templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/ro.gif);" value="romanian" <?php  if(LANGUAGE=="romanian") echo "selected"; ?> >Romanian</option>
                    <option style="background-image:url(templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/fr.gif);" value="french" <?php  if(LANGUAGE=="french") echo "selected"; ?> >French</option>
                    <option style="background-image:url(templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/flags/gr.gif);" value="greek" <?php  if(LANGUAGE=="greek") echo "selected"; ?> >Greek</option>
                </select>
            </td>
			<td><input type="submit" name="submit" value="<?php  echo gettext("LOGIN"); ?>" class="form_input_button"></td>
			</tr>           

			</table>
		</td>
	</tr>
  	</table>
  	</center>
  	</div>
  	</div>
  	
  	<div style="color:#BC2222;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:bold;padding-left:10px;" >
  	<?php if (( $this->_tpl_vars['error'] == 1 )): ?>
			<?php  echo gettext("AUTHENTICATION REFUSED, please check your user/password!"); ?>
    <?php elseif (( $this->_tpl_vars['error'] == 2 )): ?>
			<?php  echo gettext("INACTIVE ACCOUNT, Please activate your account!"); ?>
    <?php elseif (( $this->_tpl_vars['error'] == 3 )): ?>
			<?php  echo gettext("BLOCKED ACCOUNT, Please contact the administrator!"); ?>
    <?php endif; ?>
    </div>
    <div id="footer_index"><div style=" border: solid 1px #F4F4F4; text-align:center;"><?php echo $this->_tpl_vars['COPYRIGHT']; ?>
</div></div>
    
  	</div>
  	
  	
	</form>
<?php echo '
<script LANGUAGE="JavaScript">
	document.form.pr_login.focus();
        $("#ui_language").change(function () {
          self.location.href= "index.php?ui_language="+$("#ui_language option:selected").val();
        });
</script>
'; ?>


