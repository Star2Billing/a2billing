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


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_MAIL)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array (
	'id',
	'languages',
	'subject',
	'mailtext',
	'translate_data',
	'id_language',
	'mailtype'
));

$handle = DbConnect();
$instance_table = new Table();

// #### HEADER SECTION
$smarty->display('main.tpl');


if (isset ($translate_data) && $translate_data == 'translate') {
	//print check_translated($id, $languages);
	if (check_translated($id, $languages, $mailtype)) {
		update_translation($id, $languages, $subject, $mailtext, $mailtype);
	} else {
		insert_translation($id, $languages, $subject, $mailtext, $mailtype);
	}
}

// Query to get mail template information
$QUERY = "SELECT id, mailtype, subject, messagetext, id_language FROM cc_templatemail WHERE mailtype = '$mailtype'";
if (isset ($languages))
	$QUERY .= " and id_language = '$languages'";
$mail = $instance_table->SQLExec($handle, $QUERY);


// #### HELP SECTION
echo $CC_help_list_misc;

// Query to get all languages with ids
$QUERY = "SELECT code, name FROM cc_iso639 ORDER BY code";
$result = $instance_table->SQLExec($handle, $QUERY);
if (is_array($result)) {
	$num_cur = count($result);
	for ($i = 0; $i < $num_cur; $i++) {
		$languages_list[$result[$i][0]] = array (
			0 => $result[$i][0],
			1 => $result[$i][1]
		);
	}
}

?>
<FORM name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" METHOD="POST">
<input name="mailtype" value="<?php echo $mailtype; ?>" type="hidden">

<table cellspacing="2" class="addform_table1">
	 <TBODY>
		<TR>
			<TD width="%25" valign="middle" class="form_head"> <?php echo gettext('Language');?> </TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" class="text">
				<select NAME="languages" size="1" class="form_input_select" onChange="form.submit()">
				<?php 
					foreach($languages_list as $key => $lang_value) {											
				?>
				<option value='<?php echo $lang_value[0];?>' 
					<?php 
					if($mail[0][4] != ''){
						if($lang_value[0]==$mail[0][4]){print "selected";}
					}else{ 
						if($lang_value[0]==$languages){print "selected";}	
					}?>><?php echo $lang_value[1]; ?></option>
				<?php }?>
				</select>
				<span class="liens">
			</span> 
			</TD>
		</TR>

		<TR>
			<TD width="%25" valign="middle" class="form_head"> <?php echo gettext('Subject');?> </TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" class="text">
			<INPUT class="form_input_text" name="subject"  size=30 maxlength=30 value="<?php echo $mail[0][2]?>">
			<span class="liens">
			</span> 
			</TD>
		</TR>

		<TR>
			<TD width="%25" valign="middle" class="form_head"> <?php echo gettext('Mail Text');?> </TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" class="text">
			<TEXTAREA class="form_input_textarea" name="mailtext" cols=60 rows=12><?php echo $mail[0][3]?></TEXTAREA> 
			<span class="liens">
			</span> 
			</TD>
		</TR>
</table>	
	  <TABLE cellspacing="0" class="editform_table8">
		<tr>
		 <td colspan="2" class="editform_dotted_line">&nbsp; </td>
		</tr>

		<tr>
			<td width="50%" class="text_azul"><span class="tableBodyRight"><?php echo gettext('Once you have completed the form above, click on the Translate button.');?></span></td>
			<td width="50%" align="right" class="text">
		<input class="form_input_button" TYPE="submit" name="translate_data" VALUE="translate">
			</td>
		</tr>

	  </TABLE>
	  <INPUT type="hidden" name="id" value="<?php echo $id?>">
	</form>		

<?php 

// #### FOOTER SECTION
$smarty->display('footer.tpl');

