<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/



// session_name("FORGOT");
// session_start();

include ("lib/customer.defines.php");
include ("lib/customer.smarty.php");

getpost_ifset(array ('pr_email', 'action'));

$FG_DEBUG = 0;
$error = 0; //$error = 0 No Error; $error=1 No such User; $error = 2 Wrong Action
$show_message = false;
$login_message = "";

if (isset ($pr_email) && isset ($action)) {
	if ($action == "email") {

		if (!isset ($_SESSION["date_forgot"]) || (time() - $_SESSION["date_forgot"]) > 60) {
			$_SESSION["date_forgot"] = time();
		} else {
			sleep(3);
			echo gettext("Please wait 1 minutes before making any other request for the forgot password!");
			exit ();
		}
		$show_message = true;
		$DBHandle = DbConnect();
		$QUERY = "SELECT id,username, lastname, firstname, email, uipass, useralias FROM cc_card WHERE email='" . $pr_email . "' ";

		$res = $DBHandle->Execute($QUERY);
		$num = 0;
		if ($res)
			$num = $res->RecordCount();

		if (!$num) {
			$error = 1;
			sleep(4);
		}
		if ($error == 0) {
			for ($i = 0; $i < $num; $i++) {
				$list[] = $res->fetchRow();
			}
			foreach ($list as $recordset) {
				list ($id_card, $username, $lastname, $firstname, $email, $uipass, $cardalias) = $recordset;

				if ($FG_DEBUG == 1)
					echo "<br># $username, $lastname, $firstname, $email, $uipass, $credit, $cardalias #</br>";
				try {
					$mail = new Mail(Mail :: $TYPE_FORGETPASSWORD, $id_card);
					$mail -> send();
				} catch (A2bMailException $e) {
					echo "<br>" . gettext("Error : Mail sender");
					exit ();
				}
			}
		}
	} else {
		$error = 2;
	}
} else {
	$error = 3;
}

if (strlen(RETURN_URL_DISTANT_FORGETPASSWORD) > 1 && $show_message) {
	Header("Location: $URL_CALLBACK_FORGETPASSWORD?error=$error");
	die();
}

switch ($error) {
	case 0 :
		$login_message = gettext("Your login information email has been sent to you.");
		break;
	case 1 :
		$login_message = gettext("No such login exists.");
		break;
	case 2 :
		$login_message = gettext("Invalid Action.");
		break;
	case 3 :
		$login_message = gettext("Please provide your email address to get your login information.");
		break;
}

$smarty->display('header.tpl');

?>

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


<div class="block-updesign">

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
   } else {
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

</div>

<?php

$smarty->display('footer.tpl');


