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
include ("./form_data/FG_var_phonenumber.inc");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_PREDICTIVE_DIALER)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array (
	'action',
	'campaign',
	'check', 'type', 'mode', 'batchupdate',
	'upd_id_phonebook', 'upd_number', 'upd_name', 'upd_amount', 'upd_status'
));

if (!empty ($action) && !empty ($campaign) && is_numeric($campaign) && ($action == "run" || $action == "hold" || $action == "stop")) {
	$DBHandle = DbConnect();
	$status = 0;
	if ($action == "stop")
		$status = 2;
	elseif ($action == "hold") 
		$status = 1;
	
	$table = new Table();
	$table->SQLExec($DBHandle, "UPDATE cc_campaign_phonestatus SET status = $status WHERE id_phonenumber =$id AND id_campaign = $campaign ");
	
	Header("Location: A2B_entity_phonenumber.php?form_action=ask-edit&id=$id");
}

$HD_Form->setDBHandler(DbConnect());

$HD_Form->init();

/********************************* BATCH UPDATE ***********************************/

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


if ($id != "" || !is_null($id)) {
	$HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
	$form_action = "list"; //ask-add
if (!isset ($action))
	$action = $form_action;

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_phonelist;

?>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH PHONENUMBER");?> </font></a><?php if(!empty($_SESSION['entity_phonenumber_selection'])){ ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?> </center>
	<div class="tohide" style="display:none;">
<?php
// #### CREATE SEARCH FORM
if ($form_action == "list") {
	$HD_Form -> create_search_form();
}
?>
	</div>
</div>

<?php
/********************************* BATCH UPDATE ***********************************/
if ( $form_action == "list" && (!($popup_select>=1)) ) {
		
	$instance_table_tariff = new Table("cc_phonebook", "id, name");
	$FG_TABLE_CLAUSE = "";
	$list_phonebook = $instance_table_tariff -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "name", "ASC", null, null, null, null);
	$nb_phonebook = count($list_phonebook);
	
	$actived_list = Constants::getActivationList();
	
?>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("BATCH UPDATE");?> </font></a></center>
	<div class="tohide" style="display:none;">

<center>
<b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("phonenumbers selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected phonenumbers.");?></b>
	   <table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
        <tbody>
		<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<INPUT type="hidden" name="batchupdate" value="1">
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_id_phonebook]" type="checkbox" <?php if ($check["upd_id_phonebook"]=="on") echo "checked"?> >
		  </td>
		  <td align="left" class="bgcolor_001">
			  	1)&nbsp;<?php echo gettext("Phonebook");?>&nbsp;:
				<select NAME="upd_id_phonebook" size="1" class="form_input_select">
				<?php foreach ($list_phonebook as $key => $cur_value) { ?>
					<option value='<?php echo $cur_value[0] ?>' <?php if ($upd_status==$cur_value[0]) echo 'selected="selected"'?>><?php echo $cur_value[1] ?></option>                        
				<?php } ?>
				</select><br/>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001" >
		  		<input name="check[upd_number]" type="checkbox" <?php if ($check["upd_number"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				2)&nbsp;<?php echo gettext("Phonenumber"); ?>&nbsp;: 
				<input class="form_input_text"  name="upd_number" size="15" maxlength="15">
				<br/>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001" >
		  		<input name="check[upd_name]" type="checkbox" <?php if ($check["upd_name"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				3)&nbsp;<?php echo gettext("Name"); ?>&nbsp;: 
				<input class="form_input_text"  name="upd_name" size="15" maxlength="15">
				<br/>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_amount]" type="checkbox" <?php if ($check["upd_amount"]=="on") echo "checked"?>>
				<input name="mode[upd_amount]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">
				4)&nbsp;<?php echo gettext("Amount");?>&nbsp;:
				 	<input class="form_input_text" name="upd_amount" size="10" maxlength="10"  value="<?php if (isset($upd_amount)) echo $upd_amount; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_amount]" value="1" <?php if((!isset($type[upd_amount]))|| ($type[upd_amount]==1) ){?>checked<?php }?>> <?php echo gettext("Equals");?>
				<input type="radio" NAME="type[upd_amount]" value="2" <?php if($type[upd_amount]==2){?>checked<?php }?>><?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_amount]" value="3" <?php if($type[upd_amount]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
		  </td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_status]" type="checkbox" <?php if ($check["upd_status"]=="on") echo "checked"?> >
		  </td>
		  <td align="left" class="bgcolor_001">
			  5)&nbsp;<?php echo gettext("Status");?>&nbsp;:
				<select NAME="upd_status" size="1" class="form_input_select">
				<?php foreach ($actived_list as $key => $cur_value) { ?>
					<option value='<?php echo $cur_value[1] ?>' <?php if ($upd_status==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>                        
				<?php } ?>
				</select><br/>
		  </td>
		</tr>
		
		<tr>		
			<td align="right" class="bgcolor_001"></td>
		 	<td align="right"  class="bgcolor_001">
				<input class="form_input_button"  value=" <?php echo gettext("BATCH UPDATE CARD");?>  " type="submit">
        	</td>
		</tr>
		</form>
		</table>
</center>
	</div>
</div>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<?php

}elseif (!($popup_select>=1)) echo $CC_help_create_customer;


if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 

// #### TOP SECTION PAGE
$HD_Form->create_toppage($form_action);

$HD_Form->create_form($form_action, $list);

if ($form_action = "ask_edit") {
	$DBHandle = DbConnect();
	$instance_table = new Table();

	$QUERY_PHONENUMBERS = 'SELECT cc_campaign.id, cc_campaign.name,cc_campaign.status, cc_campaign.startingdate <= CURRENT_TIMESTAMP AS started ,cc_campaign.expirationdate <= CURRENT_TIMESTAMP AS expired  FROM cc_phonenumber , cc_phonebook , cc_campaign_phonebook, cc_campaign WHERE ';
	//JOIN CLAUSE
	$QUERY_PHONENUMBERS .= 'cc_phonenumber.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_phonebook = cc_phonebook.id AND cc_campaign_phonebook.id_campaign = cc_campaign.id ';
	//CAMPAIGN CLAUSE
	if ($id != null) // Exclude if null otherwise kills the list query
		$QUERY_PHONENUMBERS .= 'AND cc_phonenumber.id= ' . $id; // I've no idea under what conditions this should be included. Please review!
	$result = $instance_table->SQLExec($DBHandle, $QUERY_PHONENUMBERS);
	if ($result) {
		?>
	 	<br/>
	 	<br/>
		<table width="100%" class="editform_table1" >
		<tr>
			<th>
				<?php echo gettext("CAMPAIGN") ?> 
			</th>
			<th>
				<?php echo gettext("INFO") ?> 
			</th>
			<th>
				<?php echo gettext("STATUS") ?> 
			</th>
			<th>
				<?php echo gettext("ACTION") ?> 
			</th>
		</tr>
		
		<?php 
		foreach ($result as $phone){
			$query = "SELECT id_callback, status FROM cc_campaign_phonestatus WHERE id_campaign = $phone[0] AND id_phonenumber = $id";
			$res = $instance_table -> SQLExec ($DBHandle, $query);
         ?>
		<tr>
			<td class="form_head" align="center" width="20%" >
			 <?php echo $phone['name'] ?>
			</td>
			<td  class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="40%" >
			 <?php 
			 	if ($phone['expired']) echo gettext("EXPIRED");
				else if ($phone['started']) {
					if($res) echo gettext("STARTED AND IN PROCESS");
					else echo gettext("STARTED BUT NOT IN PROCESS : check the batch");
				}else echo gettext("NOT STARTED");
				
			 ?>
			</td>
			<td  class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="10%" >
			 	<?php 
			 	if($res) {
			 		if($res[0]['status']==0) echo gettext("RUN");
			 		elseif ($res[0]['status']==1) echo gettext("HOLD");
			 		else echo gettext("STOP");
			 	}else echo gettext("NO STATUS");
			 	?>
			</td>
			<td  class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" align="center" width="30%" >
			 	&nbsp;
			 	<?php 
			 	if($res) {
			 	?>
			 		<a href="<?php echo "A2B_entity_phonenumber.php?action=run&id=$id&campaign=$phone[0]&section=16"?>"> <img src="<?php echo Images_Path."/control_play.png" ?>" border="0" title="<?php echo "RUN"?>" alt="<?php echo "RUN"?>"></a>
			 		<a href="<?php echo "A2B_entity_phonenumber.php?action=hold&id=$id&campaign=$phone[0]&section=16"?>"> <img src="<?php echo Images_Path."/control_pause.png" ?>" border="0" title="<?php echo "PAUSE"?>" alt="<?php echo "PAUSE"?>"></a>
			 		<a href="<?php echo "A2B_entity_phonenumber.php?action=stop&id=$id&campaign=$phone[0]&section=16"?>"> <img src="<?php echo Images_Path."/control_stop.png" ?>" border="0" title="<?php echo "STOP"?>" alt="<?php echo "STOP"?>"></a>
			
				<?php } ?>
			</td>
		</tr>
		
		<?php 	
		}
		
		?>
		</table>
		<?php 
		
	}
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');

