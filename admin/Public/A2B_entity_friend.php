<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_friend.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CUSTOMER)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}


$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


/********************************* ADD SIP / IAX FRIEND ***********************************/
getpost_ifset(array("id_cc_card", "cardnumber", "useralias"));

if ( (isset ($id_cc_card) && (is_numeric($id_cc_card)  != "")) && ( $form_action == "add_sip" || $form_action == "add_iax") ){

	
	$HD_Form -> FG_GO_LINK_AFTER_ACTION = "A2B_entity_card.php?atmenu=card&stitle=Customers_Card&id=";

	if ($form_action == "add_sip") { 
		$friend_param_update=" sip_buddy='1' ";
		if(!USE_REALTIME){
			$key = "sip_changed";
		}
	} else {
		$friend_param_update=" iax_buddy='1' ";
		if(!USE_REALTIME) {
			$key = "iax_changed";
		}
	}
	
	if(!USE_REALTIME) {
		$who= Notification::$ADMIN;$who_id=$_SESSION['admin_id']; 
		NotificationsDAO::AddNotification($key,Notification::$HIGH,$who,$who_id);
	}
	
	$instance_table_friend = new Table('cc_card');
	$instance_table_friend -> Update_table ($HD_Form -> DBHandle, $friend_param_update, "id='$id_cc_card'", $func_table = null);
	
	
	if ( $form_action == "add_sip" )	$TABLE_BUDDY = 'cc_sip_buddies';
	else 	$TABLE_BUDDY = 'cc_iax_buddies';
	
	$instance_table_friend = new Table($TABLE_BUDDY,'*');	
	$list_friend = $instance_table_friend -> Get_list ($HD_Form -> DBHandle, "id_cc_card='$id_cc_card'", null, null, null, null);
	
	if (is_array($list_friend) && count($list_friend)>0){ Header ("Location: ".$HD_Form->FG_GO_LINK_AFTER_ACTION); exit();}

	$form_action = "add";
	
	$_POST['accountcode'] = $_POST['username']= $_POST['name']= $_POST['cardnumber'] = $cardnumber;
	$_POST['allow'] = FRIEND_ALLOW;
	$_POST['context'] = FRIEND_CONTEXT;
	$_POST['nat'] = FRIEND_NAT;
	$_POST['amaflags'] = FRIEND_AMAFLAGS;
	$_POST['regexten'] = $cardnumber;
	$_POST['id_cc_card'] = $id_cc_card;
	$_POST['callerid'] = $useralias;
	$_POST['qualify'] = FRIEND_QUALIFY;
	$_POST['host'] = FRIEND_HOST;   
	$_POST['dtmfmode'] = FRIEND_DTMFMODE;
	$_POST['secret'] = MDP_NUMERIC(10);
	
	// for the getProcessed var
	$HD_Form->_vars = array_merge($_GET, $_POST);
}




$HD_Form -> FG_EDITION_LINK	= $_SERVER['PHP_SELF']."?form_action=ask-edit&atmenu=$atmenu&id=";
$HD_Form -> FG_DELETION_LINK = $_SERVER['PHP_SELF']."?form_action=ask-delete&atmenu=$atmenu&id=";


if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

if(!USE_REALTIME) {
	// CHECK THE ACTION AND SET THE IS_SIP_IAX_CHANGE IF WE ADD/EDIT/REMOVE A RECORD
	if ( $form_action == "add" || $form_action == "edit" || $form_action == "delete" ){
		if ($atmenu=='sip') {
			$key = "sip_changed";
	  	} else {
	  		$key = "iax_changed";
	  	}
		if($_SESSION["user_type"]=="ADMIN") {$who= Notification::$ADMIN;$id=$_SESSION['admin_id'];} 
		elseif ($_SESSION["user_type"]=="AGENT"){$who= Notification::$AGENT;$id=$_SESSION['agent_id'];}
		else {$who=Notification::$UNKNOWN;$id=-1;}
		NotificationsDAO::AddNotification($key,Notification::$HIGH,$who,$id);
	  	
	}
}

$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($form_action=='list') {
	echo $CC_help_sipfriend_list;
	?>
		  <table width="<?php echo $HD_Form -> FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" >	  
			<TR><TD  align="center"> <?php echo gettext("Link to Generate on SIP/IAX Friends")?></TD></TR>
			<TR><FORM NAME="sipfriend">
				<td height="31" style="padding-left: 5px; padding-right: 3px;" align="center" >			
				<b>
				SIP : <input class="form_input_button"  TYPE="button" VALUE=" <?php echo gettext("GENERATE ADDITIONAL_A2BILLING_SIP.CONF"); ?> " 
				onClick="self.location.href='./CC_generate_friend_file.php?atmenu=sipfriend';">
				IAX : <input class="form_input_button"  TYPE="button" VALUE=" <?php echo gettext("GENERATE ADDITIONAL_A2BILLING_IAX.CONF"); ?> " 
				onClick="self.location.href='./CC_generate_friend_file.php?atmenu=iaxfriend';">
				</b></td></FORM>
			</TR>
		   </table>
		   <br/>
	<?php  

} else {
	echo $CC_help_sipfriend_edit;
}

if ($form_action=='list') {
?>

<table width="40%" border="0" align="center" cellpadding="0" cellspacing="1">
	<tr>
	  <td  class="bgcolor_021">
	  <table width="100%" border="0" cellspacing="1" cellpadding="0">
	  	<form name="form1" method="post" action="">
		  <tr>
			<td bgcolor="#FFFFFF" class="fontstyle_006" width="100%">&nbsp;<?php echo gettext("CONFIGURATION TYPE")?> </td>
			<td bgcolor="#FFFFFF" class="fontstyle_006" align="center">
			   <select name="atmenu" id="col_configtype" onChange="window.document.form1.elements['PMChange'].value='Change';window.document.form1.submit();">
				 <option value="iax" <?php if($atmenu == "iax")echo "selected"?>><?php echo gettext("IAX")?></option>
				 <option value="sip" <?php if($atmenu == "sip")echo "selected"?>><?php echo gettext("SIP")?></option>
			   </select> 
			  <input name="PMChange" type="hidden" id="PMChange">
			                                      
			</td>
		  </tr>
		  </form>  
	  </table></td>
	</tr>
</table>

<?php
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;


// #### FOOTER SECTION
$smarty->display('footer.tpl');



