<?php
// session_name("FORGOT");
// session_start();

include ("./lib/customer.defines.php");

getpost_ifset(array('pr_email','action'));

$FG_DEBUG = 0;
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
        $QUERY = "SELECT id,username, lastname, firstname, email, uipass, useralias FROM cc_card WHERE email='".$pr_email."' ";

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
            foreach ($list as $recordset)
            {
            	list($id_card,$username, $lastname, $firstname, $email, $uipass, $cardalias) = $recordset;
				
            	if ($FG_DEBUG == 1) echo "<br># $username, $lastname, $firstname, $email, $uipass, $credit, $cardalias #</br>";
		try {
                    $mail = new Mail(Mail::$TYPE_FORGETPASSWORD, $id_card);
                    $mail->send();
                } catch (A2bMailException $e) {
                    echo "<br>".gettext("Error : No email Template Found");
                    exit();
                }

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

if(strlen(RETURN_URL_DISTANT_FORGETPASSWORD)>1 && $show_message){
	Header ("Location: $URL_CALLBACK_FORGETPASSWORD?error=$error");
	die();
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
<link href="templates/default/css/menu.css" rel="stylesheet" type="text/css">
<link href="templates/default/css/style-def.css" rel="stylesheet" type="text/css">
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
<div id="login-wrapper" class="login-border-up">
	<div class="login-border-down">
	<div class="login-border-center">
	<form name="form" method="POST" action="forgotpassword.php?action=email" onsubmit="return test()">
	<input type="hidden" name="done" value="submit_log">

    <?php if($show_message == false){ ?>
	<table width="100%" cellspacing="6">
        <tr>
		<td class="login-title">
			 FORGOT YOUR PASSWORD?
		</td>
	</tr>
        <tr>
            <td width="100%" align="center" >
			<table>
			<tr align="center">
				<td align="left"><font face="Arial, Helvetica, Sans-Serif" size="2"><b>Email:</b></font></td>
				<td><input class="form_input_text" type="text" name="pr_email" size="32"></td>
			</tr>
			<tr align="right" >
				<td colspan="3" style="padding-top:10px;"><input type="submit" name="submit" value="SUBMIT" class="form_input_button"></td>
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
			
			
			<table width="100%" >
			<tr><td colspan="2" ></td></tr>
			<tr>
			<td>
			
			<b>
			<?php echo $login_message;?></b>
			
			</td></tr>
                        <tr><td colspan="2" ></td></tr>
			</table>
			
			
			   
    <?php } ?>
	</form>


        </div>
        </div>
        </div>

</body>
</html>
