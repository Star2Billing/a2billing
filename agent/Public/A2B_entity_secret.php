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


include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/agent.smarty.php");



if (! has_rights (ACX_SIGNUP)){
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");
	   die();
}



/***********************************************************************************/


getpost_ifset(array('NewSecret'));



$DBHandle  = DbConnect();
//////$instance_sub_table = new Table('cc_callerid');
/////$QUERY = "INSERT INTO cc_callerid (id_cc_card, cid) VALUES ('".$_SESSION["card_id"]."', '".$add_callerid."')";
//////$result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

if($form_action=="ask-update")
{
    $instance_sub_table = new Table('cc_agent',"id");
    $QUERY = "UPDATE cc_agent SET secret= '".$NewSecret."' WHERE ( ID = ".$_SESSION["agent_id"]."  ) ";
    $result = $instance_sub_table -> SQLExec ($DBHandle, $QUERY, 0);
}

$instance_table_agent_secret = new Table("cc_agent ", "secret");
$list_agent_secret = $instance_table_agent_secret  -> Get_list ($DBHandle, "id=".$_SESSION['agent_id'], "id", "ASC", null, null, null, null);
$secret = $list_agent_secret[0][0];

// #### HEADER SECTION
$smarty->display( 'main.tpl');

// #### HELP SECTION
echo $CC_help_secret_change."<br>";


if ($form_action=="ask-update")
{
	

if(is_array($result_check)){

?>
	<script language="JavaScript">
	alert("<?php echo gettext("Your secret is updated successfully.")?>");
	</script>
<?php
}else
{
?>
	<script language="JavaScript">
	alert("<?php echo gettext("Your secret is not updated Correctly.")?>");
	</script>

<?php
} }
?>
<br>
<form method="post" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-update"?>" name="frmPass">
<center>
<table class="changepassword_maintable" align=center width="300">
<tr class="bgcolor_009">
    <td align=left colspan=2><b><font color="#ffffff">- <?php echo gettext("Change Secret")?>&nbsp; -</b></td>
</tr>
<tr>
    <td align=left colspan=2>&nbsp;</td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("Old Secret")?>&nbsp; :</font></td>
    <td align=left>&nbsp;<?php echo $secret?></td>
</tr>
<tr>
    <td align=right><font class="fontstyle_002"><?php echo gettext("New Secret")?>&nbsp; :</font></td>
    <td align=left><input name="NewSecret" type="password" class="form_input_text" ></td>
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

?>
