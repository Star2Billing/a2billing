<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/smarty.php");


if (! has_rights (ACX_MISC)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form = new FormHandler("cc_card");
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_card_selection';
$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$instance_cus_table = new Table("cc_card","email, id");
$cardstatus_list_r = array();
$cardstatus_list_r["0"]  = array("0", gettext("CANCELLED"));
$cardstatus_list_r["1"]  = array("1", gettext("ACTIVE"));
$cardstatus_list_r["2"]  = array("2", gettext("NEW"));
$cardstatus_list_r["3"]  = array("3", gettext("WAITING-MAILCONFIRMATION"));
$cardstatus_list_r["4"]  = array("4", gettext("RESERVED"));
$cardstatus_list_r["5"]  = array("5", gettext("EXPIRED"));

$currency_list_r = array();
$currencies_list = get_currencies();
foreach($currencies_list as $key => $cur_value) {
	$currency_list_r[$key]  = array( $key, $cur_value[1]);
}

$simultaccess_list_r = array();
$simultaccess_list_r["0"] = array( "0", gettext("INDIVIDUAL ACCESS"));
$simultaccess_list_r["1"] = array( "1", gettext("SIMULTANEOUS ACCESS"));

$language_list_r = array();
$language_list_r["0"] = array("en", gettext("ENGLISH"));
$language_list_r["1"] = array("es", gettext("SPANISH"));
$language_list_r["2"] = array("fr", gettext("FRENCH"));
	
$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for cards created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('Creation date / Month');
$HD_Form -> FG_FILTER_SEARCH_2_TIME_TEXT = gettext('Creation date / Day');
$HD_Form -> FG_FILTER_SEARCH_2_TIME_FIELD = 'creationdate';
$HD_Form -> AddSearchElement_C1(gettext("CARDNUMBER"), 'username','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("CARDALIAS"),'useralias','useraliastype');
$HD_Form -> AddSearchElement_C1(gettext("MACADDRESS"),'mac_addr','macaddresstype');
$HD_Form -> AddSearchElement_C1(gettext("EMAIL"),'email','emailtype');
$HD_Form -> AddSearchElement_C2(gettext("CARDID (SERIAL)"),'id1','id1type','id2','id2type','id');
$HD_Form -> AddSearchElement_C2(gettext("CREDIT"),'credit1','credit1type','credit2','credit2type','credit');
$HD_Form -> AddSearchElement_C2(gettext("INUSE"),'inuse1','inuse1type','inuse2','inuse2type','inuse');

$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT TARIFF"), "cc_tariffgroup", "id, tariffgroupname, id", "", "tariffgroupname", "ASC", "tariff");
$HD_Form -> AddSearchElement_Select(gettext("SELECT STATUS"), null, null, null, null,null , "status", 0, $cardstatus_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT ACCESS"), null, null, null, null, null, "simultaccess", 0, $simultaccess_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT CURRENCY"), null, null, null, null, null, "currency", 0, $currency_list_r);
$HD_Form -> prepare_list_subselection('list');
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$nb_customer = 0;

if(!empty($HD_Form -> FG_TABLE_CLAUSE)) {
	$HD_Form -> FG_TABLE_CLAUSE .= " AND email <> ''";
	$list_customer = $instance_cus_table -> Get_list ($HD_Form -> DBHandle, $HD_Form -> FG_TABLE_CLAUSE, null, null, null, null, null, null);			
} else {
	$sql_clause = "email <> ''";
	$list_customer = $instance_cus_table -> Get_list ($HD_Form -> DBHandle, $sql_clause);
}

$nb_customer = sizeof($list_customer);
$DBHandle  = DbConnect();
$instance_table = new Table();

/***********************************************************************************/
getpost_ifset(array('subject', 'message','atmenu','submit','hd_email', 'total_customer'));


if(isset($submit)) {
	$error = FALSE;
	$error_msg = '';
	$group_id = intval($HTTP_POST_VARS[POST_GROUPS_URL]);
	
	if(isset($hd_email) && !empty($hd_email)){
		$bcc_list  = $hd_email;		
	}else{
		$QUERY = "Select email from cc_card WHERE email <> ''";
		$res_ALOC  = $instance_table->SQLExec ($HD_Form -> DBHandle, $QUERY);		
		foreach($res_ALOC as $key => $val){
			if($val[0] != '' ){
				$bcc_list[$key] =  $val[0];
			}
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

	$emailer->from(EMAIL_ADMIN);
	$emailer->replyto(EMAIL_ADMIN);

	for ($i = 0; $i < count($bcc_list); $i++)
	{
		$emailer->bcc($bcc_list[$i]);
	}

	$email_headers = 'X-AntiAbuse: Board servername - Asterisk 2 billing\n';
	$email_headers .= 'X-AntiAbuse: User_id - 1\n';
	$email_headers .= 'X-AntiAbuse: Username - Areski\n';
	$email_headers .= 'X-AntiAbuse: User IP - 192.168.1.241\n';

	$emailer->use_template($message);
	$emailer->email_address(EMAIL_ADMIN);
	$emailer->set_subject($subject);
	$emailer->extra_headers($email_headers);

	$emailer->assign_vars(array(
		'SITENAME' => 'a2billing', 
		'BOARD_EMAIL' => EMAIL_ADMIN, 
		'MESSAGE' => 'Hey it is a message, just to watch working')
	);
	$result = $emailer->send();
	$emailer->reset();
}
// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_mass_mail;

if(!isset($submit)) {
?>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH CUSTOMERS");?> </font></a></center>
	<div class="tohide" style="display:none;">

<?php
// #### CREATE SEARCH FORM
	$HD_Form -> create_search_form();
?>

	</div>
</div>

<?php 
	}

// #### CREATE FORM OR LIST
if (strlen($_GET["menu"])>0) {
	$_SESSION["menu"] = $_GET["menu"];
}
?>
<FORM action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="mass_mail"> 
	<table class="editform_table1" cellspacing="2">
<?php
if(isset($submit)){
		if($result){
?>
		<TR> 
		 <td align="center" colspan="2"><?php echo gettext("The e-mail has been sent to "); echo $total_customer; echo gettext(" customer(s)!")?></td>
		</TR>
	<?php }else{?>
		<tr> 
		 <td align="center" colspan="2"><?php echo gettext("There is some error sending e-mail, please try later.");?></td>
	   </tr>
	<?php }?>	
	
<?php 
	} else {
		if(is_array($list_customer) || $nb_customer > 1) {
?>
	<tr> 
		<td><span class="viewhandler_span1">&nbsp;</span></td>
		<td align="right"> <span class="viewhandler_span1"><?php echo $nb_customer;?> <?php echo gettext("Record(s)");?></span></td>
	</tr>
<?php
	if(!empty($HD_Form -> FG_TABLE_CLAUSE) && is_array($list_customer)) {
?>
       <TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("TO");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
		    <?php $link_to_customer = CUSTOMER_UI_URL; 
		    	if(is_array($list_customer)){
					for($key=0; $key < $nb_customer && $key <= 19; $key++){
						echo "<a href=A2B_entity_card.php?form_action=ask-edit&id=".$list_customer[$key][1]." target=\"_blank\">".$list_customer[$key][0]."</a>";
						if($key + 1 != $nb_customer) echo " ,&nbsp;";
							echo "<input type=\"hidden\" name=\"hd_email[]\" value=".$list_customer[$key][0].">";
						if($key == 19){
							echo "<br><a href=\"A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1\" target=\"_blank\">".gettext("Click on list customer to see them all")."</a>";
						}
					}
				}?><span class="liens"></span>&nbsp;<br>
		     </TD>
       </TR>
<?php 
	}
?>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("SUBJECT");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<INPUT class="form_input_text" name="subject"  size="30" maxlength="80" value=""><span class="liens"></span>&nbsp; 
			 </TD>
		</TR>
		<TR> 		
			<TD width="%25" valign="middle" class="form_head"><?php echo gettext("MESSAGE");?></TD>  
			<TD width="%75" valign="top" class="tableBodyRight" background="../Public/templates/default/images/background_cells.gif" >
				<textarea class="form_input_textarea" name="message"  cols="70" rows="10""></textarea> 
					<span class="liens"></span>&nbsp; </TD>
		 </TR>
		 
		<tr>
		 <td colspan="2" style="border-bottom: medium dotted rgb(102, 119, 102);">&nbsp; </td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align="right">
			<input class="form_input_button" name="submit"  TYPE="submit" VALUE="<?=gettext("EMAIL");?>"></td>
		</tr>
			<? }else{?>
		<tr>
			 <td colspan="2" align="center"><?php echo gettext("No Record Found!");?></td>
		</tr>
		<?php }
		}
		?>
		</table>
		<input type = "hidden" name="total_customer" value="<?=$nb_customer?>">
	</FORM>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
