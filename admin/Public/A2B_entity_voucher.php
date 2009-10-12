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
include ("./form_data/FG_var_voucher.inc");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_BILLING)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array (
	'popup_select',
	'popup_formname',
	'popup_fieldname',
	'upd_tag',
	'upd_currency',
	'upd_credit',
	'upd_activated',
	'upd_used',
	'upd_credittype',
	'batchupdate',
	'check',
	'type',
	'mode'
));

// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {

	$HD_Form->prepare_list_subselection('list');

	// Array ( [upd_simultaccess] => on [upd_currency] => on )	
	$loop_pass = 0;
	$SQL_UPDATE = '';
	foreach ($check as $ind_field => $ind_val) {
		//echo "<br>::> $ind_field -";
		$myfield = substr($ind_field, 4);
		if ($loop_pass != 0)
			$SQL_UPDATE .= ',';

		// Standard update mode
		if (!isset ($mode["$ind_field"]) || $mode["$ind_field"] == 1) {
			if (!isset ($type["$ind_field"])) {
				$SQL_UPDATE .= " $myfield='" . $$ind_field . "'";
			} else {
				$SQL_UPDATE .= " $myfield='" . $type["$ind_field"] . "'";
			}
			// Mode 2 - Equal - Add - Subtract
		}
		elseif ($mode["$ind_field"] == 2) {
			if (!isset ($type["$ind_field"])) {
				$SQL_UPDATE .= " $myfield='" . $$ind_field . "'";
			} else {
				if ($type["$ind_field"] == 1) {
					$SQL_UPDATE .= " $myfield='" . $$ind_field . "'";
				}
				elseif ($type["$ind_field"] == 2) {
					$SQL_UPDATE .= " $myfield = $myfield +'" . $$ind_field . "'";
				} else {
					$SQL_UPDATE .= " $myfield = $myfield -'" . $$ind_field . "'";
				}
			}
		}
		$loop_pass++;
	}

	$SQL_UPDATE = "UPDATE $HD_Form->FG_TABLE_NAME SET $SQL_UPDATE";
	if (strlen($HD_Form->FG_TABLE_CLAUSE) > 1) {
		$SQL_UPDATE .= ' WHERE ';
		$SQL_UPDATE .= $HD_Form->FG_TABLE_CLAUSE;
	}
	if (!$res = $HD_Form->DBHandle->Execute($SQL_UPDATE)) {
		$update_msg = '<center><font color="red"><b>' . gettext('Could not perform the batch update!') . '</b></font></center>';
	} else {
		$update_msg = '<center><font color="green"><b>' . gettext('The batch update has been successfully perform!') . '</b></font></center>';
	}

}
/********************************* END BATCH UPDATE ***********************************/

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
if ($form_action == 'list')
	echo $CC_help_list_voucher;
else
	echo $CC_help_create_voucher;
	


?>
<script language="JavaScript" src="javascript/card.js"></script>


<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH VOUCHERS");?> </font></a></center>
	<div class="tohide" style="display:none;">

<?php
// #### CREATE SEARCH FORM
if ($form_action == "list"){
	$HD_Form -> create_search_form();
}
?>

	</div>
</div>

<?php

/********************************* BATCH UPDATE ***********************************/
if ($form_action == "list" && (!($popup_select>=1))	){
	
	$instance_table_tariff = new Table("cc_tariffgroup", "id, tariffgroupname");
	$FG_TABLE_CLAUSE = "";
	$list_tariff = $instance_table_tariff -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE, "tariffgroupname", "ASC", null, null, null, null);
	$nb_tariff = count($list_tariff);
	
?>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("BATCH UPDATE");?> </font></a></center>
	<div class="tohide" style="display:none;">

<center>
<b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("vouchers selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected vouchers.");?></b>
	   <table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
        <tbody>
		<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<INPUT type="hidden" name="batchupdate" value="1">
		<tr>		
          <td align="left" class="bgcolor_001" >
		  		<input name="check[upd_used]" type="checkbox" <?php if ($check["upd_used"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				1)&nbsp;<?php echo gettext("USED"); ?>&nbsp;: 
				<select NAME="upd_used" size="1" class="form_input_select">
				<?php 
					foreach($used_list as $key => $cur_value) {											
				?>
					<option value='<?php echo $cur_value[1] ?>'  <?php if ($upd_inuse==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
				<?php } ?>			
		    </select>
		  </td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_activated]" type="checkbox" <?php if ($check["upd_activated"]=="on") echo "checked"?> >
		  </td>
		  <td align="left" class="bgcolor_001">
			  	2)&nbsp;<?php echo gettext("ACTIVATED");?>&nbsp;:
				<select NAME="upd_activated" size="1" class="form_input_select">
					<?php					 
				  	 foreach ($actived_list as $key => $cur_value){ 						 
					?>
						<option value='<?php echo $cur_value[1] ?>' <?php if ($upd_status==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
					<?php } ?>
				</select><br/>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_credit]" type="checkbox" <?php if ($check["upd_credit"]=="on") echo "checked"?>>
				<input name="mode[upd_credit]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
			  	3)&nbsp;<?php echo gettext("CREDIT");?>&nbsp;:
					<input class="form_input_text" name="upd_credit" size="10" maxlength="10"  value="<?php if (isset($upd_credit)) echo $upd_credit; else echo '0';?>">
				<font class="version">
				<input type="radio" NAME="type[upd_credit]" value="1" <?php if((!isset($type["upd_credit"]))|| ($type["upd_credit"]==1) ){?>checked<?php }?>><?php echo gettext("Equals");?>
				<input type="radio" NAME="type[upd_credit]" value="2" <?php if($type["upd_credit"]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_credit]" value="3" <?php if($type["upd_credit"]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_currency]" type="checkbox" <?php if ($check["upd_currency"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				4)&nbsp;<?php echo gettext("CURRENCY");?>&nbsp;:
				<select NAME="upd_currency" size="1" class="form_input_select">
				<?php 
					foreach($currencies_list as $key => $cur_value) {											
				?>
					<option value='<?php echo $key ?>'  <?php if ($upd_currency==$key) echo 'selected="selected"'?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?></option>
				<?php } ?>			
		    </select>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_tag]" type="checkbox" <?php if ($check["upd_tag"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				5)&nbsp;<?php echo gettext("TAG");?>&nbsp;: 
				<input class="form_input_text"  name="upd_tag" size="10" maxlength="6" value="<?php echo $upd_tag; ?>">
				<br/>
		</td>
		</tr>
		<tr>		
			<td align="right" class="bgcolor_001"></td>
		 	<td align="right"  class="bgcolor_001">
				<input class="form_input_button"  value=" <?php echo gettext("BATCH UPDATE VOUCHER");?>  " type="submit">
        	</td>
		</tr>
		</form>
		</table>
</center>
	</div>
</div>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<?php
} // END if ($form_action == "list")



if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;


// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM  $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";



// #### FOOTER SECTION
$smarty->display('footer.tpl');


