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
include ("./form_data/FG_var_friend.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CUSTOMER)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}


$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('upd_callerid', 'upd_context', 'batchupdate', 'check', 'type', 'mode'));


// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {
	$SQL_REFILL="";
	$HD_Form->prepare_list_subselection('list');

	// Array ( [upd_simultaccess] => on [upd_currency] => on )
	$loop_pass=0;
	$SQL_UPDATE = '';
	foreach ($check as $ind_field => $ind_val) {
		//echo "<br>::> $ind_field -";
		$myfield = substr($ind_field,4);
		if ($loop_pass!=0) $SQL_UPDATE.=',';

		// Standard update mode
		if (!isset($mode["$ind_field"]) || $mode["$ind_field"]==1) {
			if (!isset($type["$ind_field"])) {
				$SQL_UPDATE .= " $myfield='".$$ind_field."'";
			} else {
				$SQL_UPDATE .= " $myfield='".$type["$ind_field"]."'";
			}
		// Mode 2 - Equal - Add - Subtract
		} elseif($mode["$ind_field"]==2) {
			if (!isset($type["$ind_field"])) {
				$SQL_UPDATE .= " $myfield='".$$ind_field."'";
			} else {
				if ($type["$ind_field"] == 1) {
					$SQL_UPDATE .= " $myfield='".$$ind_field."'";
				} elseif ($type["$ind_field"] == 2) {
					$SQL_UPDATE .= " $myfield = $myfield +'".$$ind_field."'";
				} else {
					$SQL_UPDATE .= " $myfield = $myfield -'".$$ind_field."'";
				}
			}
		}
		$loop_pass++;
	}

	$SQL_UPDATE = "UPDATE $HD_Form->FG_TABLE_NAME SET $SQL_UPDATE";
	if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) {
		$SQL_UPDATE .= ' WHERE ';
		$SQL_UPDATE .= $HD_Form->FG_TABLE_CLAUSE;
	}
	$update_msg_error = '<center><font color="red"><b>'.gettext('Could not perform the batch update!').'</b></font></center>';

	if (!$HD_Form -> DBHandle -> Execute("begin")){
		$update_msg = $update_msg_error;
	} else {

		if(!$HD_Form -> DBHandle -> Execute($SQL_UPDATE)){
			$update_msg = $update_msg_error;
		}
		if (! $res = $HD_Form -> DBHandle -> Execute("commit")) {
			$update_msg = '<center><font color="green"><b>'.gettext('The batch update has been successfully perform!').'</b></font></center>';
		}

	};
}

/********************************* ADD SIP / IAX FRIEND ***********************************/
getpost_ifset(array("id_cc_card", "cardnumber", "useralias"));


if ( (isset ($id_cc_card) && (is_numeric($id_cc_card)  != "")) && ( $form_action == "add_sip" || $form_action == "add_iax") ) {
	
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
	$HD_Form->_vars = array_merge((array)$_GET, (array)$_POST);
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
	
	if(!USE_REALTIME) {
	?>
		  <table width="<?php echo $HD_Form -> FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" >	  
			<TR><TD  align="center"> <?php echo gettext("Link to Generate on SIP/IAX Friends")?> &nbsp;:&nbsp;
			</TD></TR>
			<TR><TD  align="center"> 
			<b><?php echo gettext("Realtime not active, you have to use the conf file for your system"); ?></b>
			</TD></TR>
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
	} else { ?>
		<center><a href="<?php  echo "CC_generate_friend_file.php?action=reload";?>"><img src="<?php echo Images_Path;?>/icon_refresh.gif"/>
			<?php echo gettext("Reload Asterisk"); ?></a>
		</center>
	<?php 
	}
} else {
	echo $CC_help_sipfriend_edit;
}

if ($form_action=='list') {
?>
<div align="center">
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
</div>

<br/>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("BATCH UPDATE");?> </font></a></center>
	<div class="tohide" style="display:none;">

<center>
<b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("cards selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected cards.");?></b>
	   <table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
        <tbody>
		<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<INPUT type="hidden" name="batchupdate" value="1">
		<tr>
          <td align="left" class="bgcolor_001" >
		  		<input name="check[upd_callerid]" type="checkbox" <?php if ($check["upd_callerid"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				1)&nbsp;<?php echo gettext("CallerID"); ?>&nbsp;:
				<input class="form_input_text"  name="upd_callerid" size="30" maxlength="40" value="<?php if (isset($upd_callerid)) echo $upd_callerid;?>">
				<br/>
		  </td>
		</tr>

		<tr>
          <td align="left" class="bgcolor_001" >
		  		<input name="check[upd_context]" type="checkbox" <?php if ($check["upd_context"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				2)&nbsp;<?php echo gettext("Context"); ?>&nbsp;:
				<input class="form_input_text"  name="upd_context" size="30" maxlength="40" value="<?php if (isset($upd_context)) echo $upd_context;?>">
				<br/>
		  </td>
		</tr>

		<tr>
			<td align="right" class="bgcolor_001"></td>
		 	<td align="right"  class="bgcolor_001">
				<input class="form_input_button"  value=" <?php echo gettext("BATCH UPDATE VOIP SETTINGS");?>  " type="submit">
        	</td>
		</tr>
		</form>
		</table>
</center>
	</div>
</div>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->

<?php
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;


// #### FOOTER SECTION
$smarty->display('footer.tpl');



