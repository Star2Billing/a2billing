<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_MAIL)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('id','languages','subject','mailtext','translate_data','id_language'));

	$handle = DbConnect();
	$instance_table = new Table();

/***********************************************************************************/
// #### HEADER SECTION
$smarty->display('main.tpl');
	
if(isset($translate_data) && $translate_data == 'translate'){
//print check_translated($id, $languages);
		if(check_translated($id, $languages)){
			update_translation($id, $languages, $subject, $mailtext);
		}else{
			insert_translation($id, $languages, $subject, $mailtext);
		}
}
	// Query to get mail template information
	$QUERY =  "SELECT id,mailtype,subject,messagetext,id_language from cc_templatemail where id = $id";
	if(isset($languages))
		$QUERY .= " and id_language = '$languages'";

	$mail = $instance_table -> SQLExec ($handle, $QUERY);


// #### HELP SECTION
echo $CC_help_list_misc;

	// Query to get all languages with ids
	$QUERY =  "SELECT code,name from cc_iso639 order by code";
	$result = $instance_table -> SQLExec ($handle, $QUERY);
	if (is_array($result)){
		$num_cur = count($result);
		for ($i=0;$i<$num_cur;$i++){
			$languages_list[$result[$i][0]] = array (0 => $result[$i][0], 1 => $result[$i][1]);
		}
	}
?>
<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<table cellspacing="2" class="addform_table1">
	 <TBODY>
		<TR>
			<TD width="%25" valign="middle" class="form_head"> 		Language 		</TD>  
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
			<TD width="%25" valign="middle" class="form_head"> 		Subject 		</TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" class="text">
			<INPUT class="form_input_text" name="subject"  size=30 maxlength=30 value="<?php echo $mail[0][2]?>">
			<span class="liens">
			</span> 
			</TD>
		</TR>

		<TR>
			<TD width="%25" valign="middle" class="form_head"> 		Mail Text 		</TD>  
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
			<td width="50%" class="text_azul"><span class="tableBodyRight">Once you have completed the form above, click on the Translate button.</span></td>
			<td width="50%" align="right" class="text">
		<input class="form_input_button" TYPE="submit" name="translate_data" VALUE="translate">
			</td>
		</tr>

	  </TABLE>
	  <INPUT type="hidden" name="id" value="<?php echo $id?>">
	</form>		

<?php 
// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];


// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
