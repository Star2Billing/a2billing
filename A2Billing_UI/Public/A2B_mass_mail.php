<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/smarty.php");

if (! has_rights (ACX_MISC)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}
$DBHandle  = DbConnect();
$instance_table = new Table();
/***********************************************************************************/
getpost_ifset(array('subject', 'message','atmenu','submit'));
if(isset($submit)){
	$error = FALSE;
	$error_msg = '';

	$group_id = intval($HTTP_POST_VARS[POST_GROUPS_URL]);

		$QUERY = "Select email from cc_card";
		$res_ALOC  = $instance_table->SQLExec ($DBHandle, $QUERY);		
		$i = 0;
		foreach($res_ALOC as $val){
			if($val[0] != '' ){
				$bcc_list[$i] =  $val[0];
				$i++;
			}
		}
		include('../lib/emailer.php');

		//
		// Let's do some checking to make sure that mass mail functions
		// are working in win32 versions of php.
		//
		$board_config['smtp_delivery'] = 0;
		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer($board_config['smtp_delivery']);
	
		$emailer->from(ADMIN_EMAIL);
		$emailer->replyto(ADMIN_EMAIL);

		for ($i = 0; $i < count($bcc_list); $i++)
		{
			$emailer->bcc($bcc_list[$i]);
		}

		$email_headers = 'X-AntiAbuse: Board servername - Asterisk 2 billing\n';
		$email_headers .= 'X-AntiAbuse: User_id - 1\n';
		$email_headers .= 'X-AntiAbuse: Username - atif ali\n';
		$email_headers .= 'X-AntiAbuse: User IP - 192.168.1.241\n';

		$emailer->use_template($message);
		$emailer->email_address(ADMIN_EMAIL);
		$emailer->set_subject($subject);
		$emailer->extra_headers($email_headers);

		$emailer->assign_vars(array(
			'SITENAME' => 'a2billing', 
			'BOARD_EMAIL' => ADMIN_EMAIL, 
			'MESSAGE' => 'Hey it is a message, just to watch working')
		);
		$result = $emailer->send();
		$emailer->reset();
}
// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_mass_mail;

// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) 
	$_SESSION["menu"] = $_GET["menu"];

if(isset($submit)){
	echo "<div align='center'>";
		if($result){
			echo gettext("The e-mail has been sent.");		
		}else{
			echo gettext("There is some error sending e-mail, please try later.");
		}	
	echo "</div>";
}

if(isset($atmenu) && $atmenu=="massmail"){
?>
<FORM action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="mass_mail"> 
<table class="editform_table1" cellspacing="2">
       <TR> 		
			<TD width="%25" valign="middle" class="form_head">SUBJECT</TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
		        <INPUT class="form_input_text" name="subject"  size="30" maxlength="80" value=""><span class="liens"></span>&nbsp; 
		     </TD>
       </TR>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head">MESSAGETEXT</TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<textarea class="form_input_textarea" name="message"  cols="70" rows="10""></textarea> 
					<span class="liens"></span>&nbsp; </TD>
         </TR>
         
  </TABLE>
	<TABLE cellspacing="0" class="editform_table8">
		<tr>
		 <td colspan="2" style="border-bottom: medium dotted rgb(102, 119, 102);">&nbsp; </td>
		</tr>
		<tr>
			<td align="right">
			<input class="form_input_button" name="submit"  TYPE="submit" VALUE="<?=gettext("EMAIL");?>"></td>
		</tr>

	  </TABLE>
     </FORM>
<? }
	
// #### FOOTER SECTION
$smarty->display('footer.tpl');
		
?>