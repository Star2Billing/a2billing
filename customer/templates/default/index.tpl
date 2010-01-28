<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>MyAccount «  Raytel Communications</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<link href="templates/default/css/main.css" rel="stylesheet" type="text/css">
<link href="templates/default/css/menu.css" rel="stylesheet" type="text/css">
<link href="templates/default/css/style-def.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>

<link rel="stylesheet" href="myaccount_files/style.css" type="text/css" media="screen">

</head>
<body>



<div class="main">
		<div class="main-width">

			<div class="main-bgr">

			<div class="header">

				<div class="logo">
					<div class="indent">
						<h1 onclick="location.href='http://localhost/~areski/PHP-Dev/WORDPRESS/wordpress/'">Raytel Communications</h1>
					</div>
				</div>

				<div class="search">
					<div class="indent">
					</div>
				</div>

				<div class="main-menu">
					<div class="menu"><ul><li class="page_item page-item-18"><a href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/account-center" title="Account Center"><span><span>Account Center</span></span></a></li><li class="page_item page-item-12"><a href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/contact"
title="Contact"><span><span>Contact</span></span></a></li><li class="page_item page-item-28"><a href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/faq"
title="FAQ"><span><span>FAQ</span></span></a></li><li class="page_item page-item-38 current_page_item"><a
href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/myaccount" title="MyAccount"><span><span>MyAccount</span></span></a></li><li
class="page_item page-item-23"><a href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/products"
title="Products"><span><span>Products</span></span></a></li><li class="page_item page-item-30"><a
href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/promos" title="Promos"><span><span>Promos</span></span></a></li><li
class="page_item page-item-25"><a href="http://localhost/%7Eareski/PHP-Dev/WORDPRESS/wordpress/rates" title="Rates"><span><span>Rates</span></span></a></li></ul></div>
				</div>

			</div>

			<div class="content">

<div class="column-center mainpage">



<div class="border-top"><div class="border-bot"><div
class="border-left"><div class="border-right">

<BR/>





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

	<br/><br/><br/><br/><br/><br/>

    <div class="login-border-up">
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


<br/><br/><br/><br/><br/><br/><br/><br/><br/>





</div></div>
</div></div></div></div>

</div>

</div>


</div></div>

</div>


</body></html>