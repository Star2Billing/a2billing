<?php
// session_name("FORGOT");
// session_start();

include ("./lib/defines.php");

getpost_ifset(array('pr_email','action'));
$error = 0; //$error = 0 No Error; $error=1 No such User; $error = 2 Wrong Action
$show_message = false;
$login_message = "";

if(isset($pr_email) && isset($action)) {
    if($action == "email") {
		
		if (!isset($_SESSION["date_forgot"]) || (time()-$_SESSION["date_forgot"]) > 60) {
			$_SESSION["date_forgot"]=time();
		} else {
			sleep(3);
			echo gettext("Please wait 1 minutes before making any other request for the forgot password!");
			exit();
		}
        $show_message = true;
        $DBHandle  = DbConnect();
        $QUERY = "SELECT mailtype, fromemail, fromname, subject, messagetext, messagehtml FROM cc_templatemail WHERE mailtype='forgetpassword' ";
		
		$num = 0;
        $res = $DBHandle -> Execute($QUERY);
		if ($res)
	        $num = $res -> RecordCount();
		
        if (!$num) exit();
        for($i=0;$i<$num;$i++) {
        	$listtemplate[] = $res->fetchRow();
        }

        list($mailtype, $from, $fromname, $subject, $messagetext, $messagehtml) = $listtemplate [0];
        if ($FG_DEBUG == 1) {
            echo "<br><b>mailtype : </b>$mailtype</br><b>from:</b> $from</br><b>fromname :</b> $fromname</br><b>subject</b> : $subject</br><b>ContentTemplate:</b></br><pre>$messagetext</pre></br><hr>";
        }
        $QUERY = "SELECT username, lastname, firstname, email, uipass, useralias FROM cc_card WHERE email='".$pr_email."' ";

        $res = $DBHandle -> Execute($QUERY);
	$num = 0;
	if ($res)
	        $num = $res -> RecordCount();

        if (!$num) {
            $error = 1;
			sleep(4);
        }
        if($error == 0) {
            for($i=0;$i<$num;$i++) {
            	$list[] = $res->fetchRow();
            }
            $keepmessagetext = $messagetext;
            foreach ($list as $recordset)
            {
            	$messagetext = $keepmessagetext;
            	list($username, $lastname, $firstname, $email, $uipass, $cardalias) = $recordset;
				
            	if ($FG_DEBUG == 1) echo "<br># $username, $lastname, $firstname, $email, $uipass, $credit, $cardalias #</br>";
				
				$messagetext = str_replace('$cardalias', $cardalias, $messagetext);
            	$messagetext = str_replace('$card_gen', $username, $messagetext);
            	$messagetext = str_replace('$password', $uipass, $messagetext);
				
				a2b_mail ($recordset[3], $subject, $messagetext, $from, $fromname);
            }
        }
    }
    else
    {
        $error = 2;
    }
}
else
{
    $error = 3;
}

switch($error)
{
    case 0:
        $login_message = gettext("Your login information email has been sent to you.");
    break;
    case 1:
        $login_message = gettext("No such login exists.");
    break;
    case 2:
        $login_message = gettext("Invalid Action.");
    break;
    case 3:
        $login_message = gettext("Please provide your email address to get your login information.");
    break;
}


 ?>
<html>
<head>
<link rel="shortcut icon" href="<?php echo Images_Path_Main ?>/favicon.ico">
<link rel="icon" href="<?php echo Images_Path_Main ?>/animated_favicon1.gif" type="image/gif">

<title>..:: <?php echo CCMAINTITLE; ?> ::..</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet"  href="templates/default/css/main.css">
<script LANGUAGE="JavaScript">
<!--
	function test()
	{
		if(document.form.pr_email.value=="")
		{
			alert("<?php echo gettext("You must enter an email address!")?>");
			return false;
		}
		else
		{
			return true;
		}
	}
-->
</script>

</head>

<body onload="document.form.pr_email.focus()">
<br></br>
<table width="100%" height="75%">
<tr align="center" valign="middle">
<td>
	<form name="form" method="POST" action="forgotpassword.php?action=email" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">

  	<?php if (isset($_GET["error"]) && $_GET["error"]==1) { ?>
		<font class="error_message">
			<?php echo gettext("AUTHENTICATION REFUSED, please check your user/password!")?>
		</font>
	<?php } ?><br><br>
    <?php if($show_message== false){ ?>
	<table  class="forgetpassword_maintable">
	<tr>
		<td align="center" class="forgetpassword_subtable">
			<img src="<?php echo Images_Path_Main ?>/icon_arrow_orange.gif" width="15" height="15">
			 <?php echo gettext("Forgot your password?")?>
		</td>
	</tr>
	<tr>
		<td class="forgetpassword_box">
			<table border="0" cellpadding="0" cellspacing="10">
			<tr align="center">
				<td rowspan="3" class="forgetpassword_image">&nbsp;&nbsp;</td>
				<td></td>
				<td align="left"><font size="2" face="Arial, Helvetica, Sans-Serif"><b><?php echo gettext("Email")?>:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_email" size="32"></td>
			</tr>
			<tr align="center">
				<td></td>
				<td></td>
				<td><input type="submit" name="submit" value="<?php echo gettext("SUBMIT")?>" class="form_input_button"></td>
			</tr>
			</table>
		</td>
	</tr>
    </table>

   <?php
   }
   else
   {
   ?>
			<center>
			
			<br></br><br></br>
			
			<table class="bgcolor_007"  width="400px">
			<tr><td colspan="2" ></td></tr>
			<tr><td colspan="2" ></td></tr>
			<tr>
			<td class="bgcolor_006">
			<img src="<?php echo Images_Path_Main ?>/kicons/khelpcenter.gif"/></td>
			<td class="bgcolor_006">
			
			<b>
			<?php echo $login_message;?></b>
			
			</td></tr>
			<tr><td colspan="2" ></td></tr>
			<tr><td colspan="2" ></td></tr>
			</table>
			
			<br></br><br></br>
			
			</center>
			   
    <?php } ?>
	</form>

</td>
</tr>
</table>


<br></br><br></br>

</body>
</html>
