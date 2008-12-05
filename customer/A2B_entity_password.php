<?php
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");


if (!has_rights (ACX_PASSWORD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

/***********************************************************************************/


getpost_ifset(array('NewPassword','OldPassword'));


$DBHandle  = DbConnect();

if($form_action=="ask-update") {
    $instance_sub_table = new Table('cc_card',"id");
    $check_old_pwd = "id = '".$_SESSION["card_id"]."' AND uipass = '$OldPassword'";
    $result_check=$instance_sub_table -> Get_list ($DBHandle,$check_old_pwd);
    if(is_array($result_check)) {
	    $QUERY = "UPDATE cc_card SET  uipass= '".$NewPassword."' WHERE ( ID = ".$_SESSION["card_id"]." ) ";
	    $result = $instance_sub_table -> SQLExec ($DBHandle, $QUERY, 0);
	    // update Session password
	    $_SESSION["pr_password"] = $NewPassword;
    }
}
// #### HEADER SECTION
$smarty->display( 'main.tpl');

// #### HELP SECTION
echo $CC_help_password_change."<br>";

?>
<script language="JavaScript">
function CheckPassword()
{
    if(document.frmPass.NewPassword.value =='')
    {
        alert('<?php echo gettext("No value in New Password entered")?>');
        document.frmPass.NewPassword.focus();
        return false;
    }
    if(document.frmPass.CNewPassword.value =='')
    {
        alert('<?php echo gettext("No Value in Confirm New Password entered")?>');
        document.frmPass.CNewPassword.focus();
        return false;
    }
    if(document.frmPass.NewPassword.value.length < 5)
    {
        alert('<?php echo gettext("Password length should be greater than or equal to 5")?>');
        document.frmPass.NewPassword.focus();
        return false;
    }
    if(document.frmPass.CNewPassword.value != document.frmPass.NewPassword.value)
    {
        alert('<?php echo gettext("Value mismatch, New Password should be equal to Confirm New Password")?>');
        document.frmPass.NewPassword.focus();
        return false;
    }

    return true;
}
</script>

<br>
<center>
<?php
if ($form_action=="ask-update") {
	if(is_array($result_check)) {
		echo '<font color="green">'.gettext("Your password is updated successfully.").'</font><br>';
	} else {
		echo '<font color="red">'.gettext("Your old password is wrong.").'</font><br>';
	}
}
?>

<form method="post" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-update"?>" name="frmPass">
<table class="changepassword_maintable" align=center>
<tr class="bgcolor_009">
    <td align=left colspan=2><b><font color="#ffffff">- <?php echo gettext("Change Password")?>&nbsp; -</b></td>
</tr>
<tr>
    <td align=left colspan=2>&nbsp;</td>
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
<br>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');

