<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

include '../lib/agent.defines.php';
include '../lib/agent.module.access.php';
include '../lib/Form/Class.FormHandler.inc.php';
include '../lib/agent.smarty.php';

if (! has_rights (ACX_ACCESS)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('NewPassword','OldPassword'));

$DBHandle  = DbConnect();

if ($form_action=="ask-modif") {

    check_demo_mode();

    $instance_sub_table = new Table('cc_agent',"id");
    $check_old_pwd = "id = '".$_SESSION["agent_id"]."' AND passwd = '$OldPassword'";
    $result_check=$instance_sub_table -> Get_list ($DBHandle,$check_old_pwd);
    if (is_array($result_check)) {
        $QUERY = "UPDATE cc_agent SET passwd= '".$NewPassword."' WHERE ( ID = ".$_SESSION["agent_id"]."  ) ";
        $result = $instance_sub_table -> SQLExec ($DBHandle, $QUERY, 0);
    }
}

$smarty->display( 'main.tpl');

echo $CC_help_password_change."<br>";

?>
<script language="JavaScript">
function CheckPassword()
{
    if (document.frmPass.NewPassword.value =='') {
        alert('<?php echo gettext("No value in New Password entered")?>');
        document.frmPass.NewPassword.focus();

        return false;
    }
    if (document.frmPass.CNewPassword.value =='') {
        alert('<?php echo gettext("No Value in Confirm New Password entered")?>');
        document.frmPass.CNewPassword.focus();

        return false;
    }
    if (document.frmPass.NewPassword.value.length < 5) {
        alert('<?php echo gettext("Password length should be greater than or equal to 5")?>');
        document.frmPass.NewPassword.focus();

        return false;
    }
    if (document.frmPass.CNewPassword.value != document.frmPass.NewPassword.value) {
        alert('<?php echo gettext("Value mismatch, New Password should be equal to Confirm New Password")?>');
        document.frmPass.NewPassword.focus();

        return false;
    }

    return true;
}
</script>

<?php
if ($form_action=="ask-modif") {

if (is_array($result_check)) {

?>
    <script language="JavaScript">
    alert("<?php echo gettext("Your password is updated successfully.")?>");
    </script>
<?php
} else {
?>
    <script language="JavaScript">
    alert("<?php echo gettext("Your old password is wrong.")?>");
    </script>

<?php
} }
?>
<br>
<form method="post" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-modif"?>" name="frmPass">
<center>
<table class="changepassword_maintable" align=center width="300">
<tr class="bgcolor_009">
    <td align=left colspan=2><b><font color="#ffffff">- <?php echo gettext("Change Password")?>&nbsp; -</b></td>
</tr>
<tr>
    <td align="center" colspan=2>&nbsp;<p class="liens"><?php echo gettext("Do not use \" or = characters in your password");?></p></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("Old Password")?>&nbsp; :</font></td>
    <td align=left><input name="OldPassword" type="password" class="form_input_text" ></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("New Password")?>&nbsp; :</font></td>
    <td align=left><input name="NewPassword" type="password" class="form_input_text" ></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("Confirm Password")?>&nbsp; :</font></td>
    <td align=left><input name="CNewPassword" type="password" class="form_input_text" ></td>
</tr>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>
<tr>
    <td align=center colspan=2 ><input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Save")?>&nbsp;" class="form_input_button" onclick="return CheckPassword();" >&nbsp;&nbsp;<input type="reset" name="resetPassword" value="&nbsp;Reset&nbsp;" class="form_input_button" > </td>
</tr>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>

</table>
</center>
<script language="JavaScript">

document.frmPass.NewPassword.focus();

</script>
</form>

<br><br><br>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');
