<?php /* Smarty version 2.6.13, created on 2007-05-31 11:30:35
         compiled from index.tpl */ ?>
<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/favicon.ico">
	<link rel="icon" href="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/animated_favicon1.gif" type="image/gif">
	
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

<style TEXT="test/css">
<!-- 
.form_enter {
	font-family: Arial, Helvetica, Sans-Serif;
	font-size: 11px;
	font-weight: bold;
	color: #FF9900;
	border: 1px solid #C1C1C1;
}
-->
</style>
'; ?>


<table width="100%" height="75%">
<tr align="center" valign="middle">
<td>
	<form name="form" method="POST" action="userinfo.php" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">

	<?php if (( $this->_tpl_vars['error'] == 1 )): ?>
	  	

		<font face="Arial, Helvetica, Sans-serif" size="2" color="red">
			<b>AUTHENTICATION REFUSED, please check your user/password!</b>
		</font>
    <?php elseif (( $this->_tpl_vars['error'] == 2 )): ?>
        <font face="Arial, Helvetica, Sans-serif" size="2" color="red">
			<b>INACTIVE ACCOUNT, Please activate your account!</b>
		</font>
    <?php elseif (( $this->_tpl_vars['error'] == 3 )): ?>
        <font face="Arial, Helvetica, Sans-serif" size="2" color="red">
			<b>BLOCKED ACCOUNT, Please contact your administrator!</b>
		</font>
    <?php endif; ?>
    <br><br>

	<table style="border: 1px solid #C1C1C1">
	<tr>
		<td class="form_enter" align="center">
			<img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/icon_arrow_orange.gif" width="15" height="15">
			<font size="3" color="red" ><b> AUTHENTICATION</b></font>
		</td>
	</tr>
	<tr>
		<td style="padding: 5px, 5px, 5px, 5px" bgcolor="#EDF3FF">
			<table border="0" cellpadding="0" cellspacing="10">
			<tr align="center">
				<td rowspan="3" style="padding-left: 8px; padding-right: 8px"><img src="templates/<?php echo $this->_tpl_vars['SKIN_NAME']; ?>
/images/password.png"></td>
				<td></td>
				<td align="left"><font size="2" face="Arial, Helvetica, Sans-Serif"><b>User:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_login"></td>
			</tr>
			<tr align="center">
				<td></td>
				<td align="left"><font face="Arial, Helvetica, Sans-Serif" size="2"><b>Password:</b></font></td>
				<td><input class="form_input_text" type="password" name="pr_password"></td>
			</tr>
			<tr align="center">
				<td></td>
				<td></td>
				<td><input type="submit" name="submit" value="LOGIN" class="form_input_button"></td>
			</tr>

            <tr align="center">
                <td colspan=3><font class="fontstyle_007">Forgot your password? Click <a href="forgotpassword.php">here</a></font>.</td>
            </tr>
			<?php if (( $this->_tpl_vars['SIGNUPLINK'] != "" )): ?>
			<tr align="left">
                <td colspan=3><font class="fontstyle_007">To sign up click <a href="<?php echo $this->_tpl_vars['SIGNUPLINK']; ?>
">here</a></font>.</td>
            </tr>
			<?php endif; ?>
			</table>
		</td>
	</tr>
      	</table>
	</form>
</td>
</tr>
</table>
<?php echo '
<script LANGUAGE="JavaScript">
	document.form.pr_login.focus();
</script>
'; ?>
