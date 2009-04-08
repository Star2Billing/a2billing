<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/{$SKIN_NAME}/images/favicon.ico">
	<link rel="icon" href="templates/{$SKIN_NAME}/images/animated_favicon1.gif" type="image/gif">
	
	<title>..:: {$CCMAINTITLE} ::..</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		{if ($CSS_NAME!="" && $CSS_NAME!="default")}
			   <link href="templates/default/css/{$CSS_NAME}.css" rel="stylesheet" type="text/css">
		{else}
			   <link href="templates/default/css/main.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/menu.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/style-def.css" rel="stylesheet" type="text/css">
		{/if}
			   
			
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

{literal}
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

{/literal}

	<form name="form" method="POST" action="PP_intro.php" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">

	{if ($error == 1)}
	  	

		<font face="Arial, Helvetica, Sans-serif" size="2" color="red">
			<b>AUTHENTICATION REFUSED, please check your user/password!</b>
		</font>
    {elseif ($error==2)}
        <font face="Arial, Helvetica, Sans-serif" size="2" color="red">
			<b>INACTIVE ACCOUNT, Please activate your account!</b>
		</font>
    {elseif ($error==3)}
        <font face="Arial, Helvetica, Sans-serif" size="2" color="red">
			<b>BLOCKED ACCOUNT, Please contact your administrator!</b>
		</font>
    {/if}
    <br><br>
	<div id="login-wrapper" class="login-border-up">
	<div class="login-border-down">
	<div class="login-border-center">
	<table>
	<tr>
		<td class="login-title">
			 AUTHENTICATION
		</td>
	</tr>
	<tr>
		<td style="padding: 5px, 5px, 5px, 5px" >
			<table>
			<tr align="center">
				<td rowspan="3" style="padding-left: 8px; padding-right: 8px"><img src="templates/{$SKIN_NAME}/images/password.png"></td>
				<td></td>
				<td align="left"><font size="2" face="Arial, Helvetica, Sans-Serif"><b>User:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_login"></td>
			</tr>
			<tr align="center">
				<td></td>
				<td align="left"><font face="Arial, Helvetica, Sans-Serif" size="2"><b>Password:</b></font></td>
				<td><input class="form_input_text" type="password" name="pr_password"></td>
			</tr>
			<tr align="right" >
				<td colspan="3" style="padding-top:10px;"><input type="submit" name="submit" value="LOGIN" class="form_input_button"></td>
			</tr>           

			</table>
		</td>
	</tr>
      	</table>
      	</div>
      	</div>
      	</div>
	</form>
{literal}
<script LANGUAGE="JavaScript">
	document.form.pr_login.focus();
</script>
{/literal}
