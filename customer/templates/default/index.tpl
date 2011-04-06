<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/{$SKIN_NAME}/images/a2billing-icon-32x32.ico">
	<title>..:: {$CCMAINTITLE} ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		{if ($CSS_NAME!="" && $CSS_NAME!="default")}
			   <link href="templates/default/css/{$CSS_NAME}.css" rel="stylesheet" type="text/css">
		{else}
			   <link href="templates/default/css/main.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/menu.css" rel="stylesheet" type="text/css">
			   <link href="templates/default/css/style-def.css" rel="stylesheet" type="text/css">
		{/if}
         <script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

{literal}
<script LANGUAGE="JavaScript">
<!--
	function test()
	{
		if(document.form.pr_login.value=="" || document.form.pr_password.value=="")
		{
			alert("You must enter an user and a password!" + document.form.pr_password.value);
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

	<form name="form" method="POST" action="userinfo.php" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">

	  	
    <div id="login-wrapper" class="login-border-up">
	<div class="login-border-down">
	<div class="login-border-center">
	<center>
	<table border="0" cellpadding="3" cellspacing="12">
	<tr>
		<td class="login-title" colspan="2">
			 {php} echo gettext("AUTHENTICATION");{/php}
		</td>
	</tr>
	<tr>
		<td ><img src="templates/{$SKIN_NAME}/images/kicons/lock_bg.png"></td>
		<td align="center" style="padding-right: 10px">
			<table width="90%">
			<tr align="center">
				<td align="left"><font size="2" face="Arial, Helvetica, Sans-Serif"><b>{php} echo gettext("User");{/php}:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_login" size="15" value="{$username}"></td>
			</tr>
			<tr align="center">
				<td align="left"><font face="Arial, Helvetica, Sans-Serif" size="2"><b>{php} echo gettext("Password");{/php}:</b></font></td>
				<td><input class="form_input_text" type="password" name="pr_password" size="15" value="{$password}"></td>
			</tr>
			</tr><tr >
                <td colspan="2"> &nbsp;</td>
            </tr>
			<tr align="right" >
                <td>
                    <select name="ui_language"  id="ui_language" class="icon-menu form_input_select">
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/gb.gif);" value="english" {php} if(LANGUAGE=="english") echo "selected";{/php} >English</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/es.gif);" value="spanish" {php} if(LANGUAGE=="spanish") echo "selected";{/php} >Spanish</option>Român
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/fr.gif);" value="french" {php} if(LANGUAGE=="french") echo "selected";{/php} >French</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/de.gif);" value="german" {php} if(LANGUAGE=="german") echo "selected";{/php} >German</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/pt.gif);" value="portuguese" {php} if(LANGUAGE=="portuguese") echo "selected";{/php} >Portuguese</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/br.gif);" value="brazilian" {php} if(LANGUAGE=="brazilian") echo "selected";{/php}>Brazilian</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/it.gif);" value="italian" {php} if(LANGUAGE=="italian") echo "selected";{/php} >Italian</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/cn.gif);" value="chinese" {php} if(LANGUAGE=="chinese") echo "selected";{/php} >Chinese</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/ro.gif);" value="romanian" {php} if(LANGUAGE=="romanian") echo "selected";{/php} >Romanian</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/pl.gif);" value="polish" {php} if(LANGUAGE=="polish") echo "selected";{/php} >Polish</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/ru.gif);" value="russian" {php} if(LANGUAGE=="russian") echo "selected";{/php} >Russian</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/tr.gif);" value="turkish" {php} if(LANGUAGE=="turkish") echo "selected";{/php} >Turkish</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/pk.gif);" value="urdu" {php} if(LANGUAGE=="urdu") echo "selected";{/php} >Urdu</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/ua.gif);" value="ukrainian" {php} if(LANGUAGE=="ukrainian") echo "selected";{/php} >Ukrainian</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/gr.gif);" value="greek" {php} if(LANGUAGE=="greek") echo "selected";{/php} >Greek</option>
                        <option style="background-image:url(templates/{$SKIN_NAME}/images/flags/id.gif);" value="indonesian" {php} if(LANGUAGE=="indonesian") echo "selected";{/php} >Indonesian</option>
                    </select>
                </td>
				<td><input type="submit" name="submit" value="{php} echo gettext("LOGIN");{/php}" class="form_input_button"></td>
			</tr>
			
			</table>
		</td>
	</tr>
	<tr align="center">
		<td colspan="2"><font class="fontstyle_007">{php} echo gettext("Forgot your password ?");{/php} <a href="forgotpassword.php">{php} echo gettext("Click here");{/php}</a></font>.</td>
    </tr>
	<tr align="center">
        <td colspan="2"><font class="fontstyle_007">{php} echo gettext("To sign up");{/php} <a href="signup.php">{php} echo gettext("Click here");{/php}</a></font>.</td>
    </tr>    
  	</table>
  	</center>
  	</div>
  	</div>
  	
  	<div style="color:#BC2222;font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:bold;padding-left:10px;" >
  	{if ($error == 1)}
		{php} echo gettext("AUTHENTICATION REFUSED : please check your user/password!");{/php}
    {elseif ($error==2)}
		{php} echo gettext("INACTIVE ACCOUNT : Your account need to be activated!");{/php}
    {elseif ($error==3)}
		{php} echo gettext("BLOCKED ACCOUNT : Please contact the administrator!");{/php}
    {elseif ($error==4)}
		{php} echo gettext("NEW ACCOUNT : Your account has not been validate yet!");{/php}
    {/if}
    </div>
    <div id="footer_index"><div style=" border: solid 1px #F4F4F4; text-align:center;">{$COPYRIGHT}</div></div>
    
  	</div>
	</form>
{literal}
<script LANGUAGE="JavaScript">
	//document.form.pr_login.focus();
        $("#ui_language").change(function () {
          self.location.href= "index.php?ui_language="+$("#ui_language option:selected").val();
        });
</script>
{/literal}
