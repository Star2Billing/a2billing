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
include ("./form_data/FG_var_def_ratecard.inc");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_RATECARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('package','popup_select', 'popup_formname', 'popup_fieldname','posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'removeallrate', 'removetariffplan', 'definecredit', 'IDCust', 'mytariff_id', 'destination', 'dialprefix', 'buyrate1', 'buyrate2', 'buyrate1type', 'buyrate2type', 'rateinitial1', 'rateinitial2', 'rateinitial1type', 'rateinitial2type', 'id_trunk', "check", "type", "mode"));


/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array ( 'batchupdate', 'upd_id_trunk', 'upd_idtariffplan', 'upd_id_outbound_cidgroup', 'upd_tag', 'upd_inuse', 'upd_activated', 'upd_language',
	'upd_tariff', 'upd_credit', 'upd_credittype', 'upd_simultaccess', 'upd_currency', 'upd_typepaid', 'upd_creditlimit', 'upd_enableexpire', 'upd_expirationdate',
	'upd_expiredays', 'upd_runservice', 'filterprefix', 'filterfield'
));

$update_fields = array (
	"upd_buyrate",
	"upd_buyrateinitblock",
	"upd_buyrateincrement",
	"upd_rateinitial",
	"upd_initblock",
	"upd_billingblock",
	"upd_connectcharge",
	"upd_disconnectcharge",
	"upd_rounding_calltime",
	"upd_rounding_threshold",
	"upd_additional_block_charge",
	"upd_additional_block_charge_time"
);
$update_fields_info = array (
	"BUYING RATE",
	"BUYRATE MIN DURATION",
	"BUYRATE BILLING BLOCK",
	"SELLING RATE",
	"SELLRATE MIN DURATION",
	"SELLRATE BILLING BLOCK",
	"CONNECT CHARGE",
	"DISCONNECT CHARGE",
	"ROUNDING CALLTIME",
	"ROUNDING THRESHOLD",
	"ADDITIONAL BLOCK CHARGE",
	"ADDITIONAL BLOCK CHARGE TIME"
);
$charges_abc = array ();
$charges_abc_info = array ();
if (ADVANCED_MODE) {
	$charges_abc = array (
		"upd_stepchargea",
		"upd_chargea",
		"upd_timechargea",
		"upd_stepchargeb",
		"upd_chargeb",
		"upd_timechargeb",
		"upd_stepchargec",
		"upd_chargec",
		"upd_timechargec",
		"upd_announce_time_correction"
	);
	$charges_abc_info = array (
		"ENTRANCE CHARGE A",
		"COST A",
		"TIME FOR A",
		"ENTRANCE CHARGE B",
		"COST B",
		"TIME FOR B",
		"ENTRANCE CHARGE C",
		"COST C",
		"TIME FOR C",
		"ANNOUNCE TIME CORRECTION"
	);
};

getpost_ifset($update_fields);

if (ADVANCED_MODE) {
	getpost_ifset($charges_abc);
};

/***********************************************************************************/

$HD_Form->setDBHandler(DbConnect());
$HD_Form->init();

// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {

	check_demo_mode();

	$HD_Form->prepare_list_subselection('list');

	// Array ( [upd_simultaccess] => on [upd_currency] => on )
	$loop_pass = 0;
	$SQL_UPDATE = '';
	$PREFIX_FIELD = 'cc_ratecard.';
	
	foreach ($check as $ind_field => $ind_val) {
		//echo "<br>::> $ind_field -";
		$myfield = substr($ind_field, 4);
		if ($loop_pass != 0)
			$SQL_UPDATE .= ',';

		// Standard update mode
		if (!isset ($mode["$ind_field"]) || $mode["$ind_field"] == 1) {
			if (!isset ($type["$ind_field"])) {
				$SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $$ind_field . "'";
			} else {
				$SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $type["$ind_field"] . "'";
			}
			// Mode 2 - Equal - Add - Substract
		}
		elseif ($mode["$ind_field"] == 2) {
			if (!isset ($type["$ind_field"])) {
				$SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $$ind_field . "'";
			} else {
				if ($type["$ind_field"] == 1) {
					$SQL_UPDATE .= " $PREFIX_FIELD$myfield='" . $$ind_field . "'";
				}
				elseif ($type["$ind_field"] == 2) {
					if (substr($$ind_field, -1) == "%") {
						$SQL_UPDATE .= " $PREFIX_FIELD$myfield = ROUND($PREFIX_FIELD$myfield + (($PREFIX_FIELD$myfield * " . substr($$ind_field, 0, -1) . ") / 100)+0.00005,4)";
					} else {
						$SQL_UPDATE .= " $PREFIX_FIELD$myfield = $PREFIX_FIELD$myfield +'" . $$ind_field . "'";
					}
				} else {
					if (substr($$ind_field, -1) == "%") {
						$SQL_UPDATE .= " $PREFIX_FIELD$myfield = ROUND($PREFIX_FIELD$myfield - (($PREFIX_FIELD$myfield * " . substr($$ind_field, 0, -1) . ") / 100)+0.00005,4)";
					} else {
						$SQL_UPDATE .= " $PREFIX_FIELD$myfield = $PREFIX_FIELD$myfield -'" . $$ind_field . "'";
					}
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
	$instance_table = new Table();
	$res = $instance_table->ExecuteQuery($HD_Form->DBHandle, $SQL_UPDATE);
	if (!$res)
		$update_msg = "<center><font color=\"red\"><b>" . gettext("Could not perform the batch update") . "!</b></font></center>";
	else
		$update_msg = "<center><font color=\"green\"><b>" . gettext("The batch update has been successfully perform") . " !</b></font></center>";

}
/********************************* END BATCH UPDATE ***********************************/

if ($id != "" || !is_null($id)) {
	$HD_Form->FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form->FG_EDITION_CLAUSE);
}

if (!isset ($form_action))
	$form_action = "list"; //ask-add
if (!isset ($action))
	$action = $form_action;

if ($form_action != "list") {
	check_demo_mode();
}

if (is_string($tariffgroup) && strlen(trim($tariffgroup)) > 0) {
	list ($mytariffgroup_id, $mytariffgroupname, $mytariffgrouplcrtype) = preg_split('/-:-/', $tariffgroup);
	$_SESSION["mytariffgroup_id"] = $mytariffgroup_id;
	$_SESSION["mytariffgroupname"] = $mytariffgroupname;
	$_SESSION["tariffgrouplcrtype"] = $mytariffgrouplcrtype;
} else {
	$mytariffgroup_id = $_SESSION["mytariffgroup_id"];
	$mytariffgroupname = $_SESSION["mytariffgroupname"];
	$mytariffgrouplcrtype = $_SESSION["tariffgrouplcrtype"];
}

if (($form_action == "list") && ($HD_Form->FG_FILTER_SEARCH_FORM) && ($_POST['posted_search'] == 1) && is_numeric($mytariffgroup_id)) {
	if (!empty ($HD_Form->FG_TABLE_CLAUSE))
		$HD_Form->FG_TABLE_CLAUSE .= ' AND ';

	$HD_Form->FG_TABLE_CLAUSE = "idtariffplan='$mytariff_id'";
}

$list = $HD_Form->perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if (!$popup_select) {
	if (($form_action == 'ask-add') || ($form_action == 'ask-edit'))
		echo $CC_help_rate;
	else
		echo $CC_help_def_ratecard;
}

// DISPLAY THE UPDATE MESSAGE
if (isset ($update_msg) && strlen($update_msg) > 0)
	echo $update_msg;

if ($popup_select && empty($package) && !is_numeric($package) ) {
?>
<SCRIPT LANGUAGE="javascript">
<!-- Begin
function sendValue(selvalue){
	window.opener.document.<?php echo $popup_formname ?>.<?php echo $popup_fieldname ?>.value = selvalue;
	window.close();
}
// End -->
</script>
<?php

}


if ($popup_select && is_numeric($package)) {
$HD_Form-> CV_FOLLOWPARAMETERS .= "&package=".$package;
?>
<SCRIPT LANGUAGE="javascript">
<!-- Begin
function sendValue(selvalue){
	 // redirect browser to the grabbed value (hopefully a URL)
	window.opener.location.href= <?php echo '"A2B_package_manage_rates.php?id='.$package.'&addrate="'; ?>+selvalue;
}
// End -->
</script>
<?php
}


if (!$popup_select) {
	// #### CREATE SEARCH FORM
	if ($form_action == "list") {
		$HD_Form->create_search_form();
	}
}

/********************************* BATCH UPDATE ***********************************/
if ($form_action == "list" && !$popup_select) {

	$instance_table = new Table("cc_tariffplan", "id, tariffname");
	$FG_TABLE_CLAUSE = "";
	$list_tariffname = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
	$nb_tariffname = count($list_tariffname);

	$instance_table = new Table("cc_trunk", "id_trunk, trunkcode, providerip");
	$FG_TABLE_CLAUSE = "";
	$list_trunk = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "trunkcode", "ASC", null, null, null, null);
	$nb_trunk = count($list_trunk);
	
	$instance_table = new Table("cc_outbound_cid_group", "id, group_name");
	$FG_TABLE_CLAUSE = "";
	$list_cid_group = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "group_name", "ASC", null, null, null, null);
	$nb_cid_group = count($list_cid_group);
	
	
	// disable Batch update if LCR Export
	if(empty($_SESSION['def_ratecard_tariffgroup'])) {
		
?>


<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("BATCH UPDATE");?> </font></a></center>
	<div class="tohide" style="display:none;">
	
<center>
<b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("rates selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected rates.");?></b>
	   <table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
		<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<INPUT type="hidden" name="batchupdate" value="1">
		<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
		<INPUT type="hidden" name="popup_select" value="<?php echo $popup_select?>">
		<INPUT type="hidden" name="popup_formname" value="<?php echo $popup_formname?>">
		<INPUT type="hidden" name="popup_fieldname" value="<?php echo $popup_fieldname?>">
		<INPUT type="hidden" name="form_action" value="<?php echo $form_action?>">
		<INPUT type="hidden" name="filterprefix" value="<?php echo $filterprefix?>">
		<INPUT type="hidden" name="filterfield" value="<?php echo $filterfield?>">
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_id_trunk]" type="checkbox" <?php if ($check["upd_id_trunk"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				<font class="fontstyle_009">1) <?php echo gettext("TRUNK");?> :</font> 
				<select NAME="upd_id_trunk" size="1" class="form_enter" >
					<OPTION  value="-1" selected><?php echo gettext("NOT DEFINED");?></OPTION>
					<?php
					 foreach ($list_trunk as $recordset){ 						 
					?>
						<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_trunk==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1].' ('.$recordset[2].')'?></option>                        
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_idtariffplan]" type="checkbox" <?php if ($check["upd_idtariffplan"]=="on") echo "checked"?> >
		  </td>
		  <td align="left"  class="bgcolor_001">
			  <font class="fontstyle_009">	2) <?php echo gettext("RATECARD");?> :</font>
				<select NAME="upd_idtariffplan" size="1" class="form_enter" >
					<?php					 
				  	 foreach ($list_tariffname as $recordset){ 						 
					?>
						<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_idtariffplan==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
					<?php 	 }
					?>
				</select>
				<br/>
			</td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_id_outbound_cidgroup]" type="checkbox" <?php if ($check["upd_id_outbound_cidgroup"]=="on") echo "checked"?> >
		  </td>
		  <td align="left"  class="bgcolor_001">
			  <font class="fontstyle_009">	3) <?php echo gettext("CIDGroup");?> :</font>
				<select NAME="upd_id_outbound_cidgroup" size="1" class="form_enter" >
					<OPTION  value="-1" selected><?php echo gettext("NOT DEFINED");?></OPTION>
					<?php					 
				  	 foreach ($list_cid_group as $recordset){ 						 
					?>
						<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_outbound_cidgroup==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
					<?php 	 }
					?>
				</select>
				<br/>
			</td>
		</tr>
				
				</font>
			</td>
		</tr>
		 <?php
           $index=0;
           foreach ( $update_fields as $value) {
           ?>
          <td align="left" class="bgcolor_001">
                                <input name="check[<?php echo $value;?>]" type="checkbox" <?php if ($check[$value]=="on") echo "checked"?>>
                                <input name="mode[<?php echo $value;?>]" type="hidden" value="2">
                  </td>
                  <td align="left"  class="bgcolor_001">

                                <font class="fontstyle_009"><?php echo ($index + 4).") ".gettext($update_fields_info[$index]);?> :</font>
                                        <input class="form_input_text" name="<?php echo $value;?>" size="10" maxlength="10"  value="<?php if (isset(${$value})) echo ${$value}; else echo '0';?>" >
                                <font class="version">
                                <input type="radio" NAME="type[<?php echo $value;?>]" value="1" <?php if((!isset($type[$value]))|| ($type[$value]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
                                <input type="radio" NAME="type[<?php echo $value;?>]" value="2" <?php if($type[$value]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
                                <input type="radio" NAME="type[<?php echo $value;?>]" value="3" <?php if($type[$value]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
                                </font>
                        </td>
               </tr>
            <?php $index=$index+1;
			}?>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_tag]" type="checkbox" <?php if ($check["upd_tag"]=="on") echo "checked"?>>
				<input name="mode[upd_tag]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">15) <?php echo gettext("TAG");?> :</font>
				 	<input class="form_input_text" name="upd_tag" size="20"  value="<?php if (isset($upd_tag)) echo $upd_tag; else echo '';?>" >
			</td>
		</tr>

		<tr>
           <?php
           $index=0;
           foreach ( $charges_abc as $value) {
           ?>
          <td align="left" class="bgcolor_001">
                                <input name="check[<?php echo $value;?>]" type="checkbox" <?php if ($check[$value]=="on") echo "checked"?>>
                                <input name="mode[<?php echo $value;?>]" type="hidden" value="2">
                  </td>
                  <td align="left"  class="bgcolor_001">

                                <font class="fontstyle_009"><?php echo ($index+16).") ".gettext($charges_abc_info[$index]);?> :</font>
                                        <input class="form_input_text" name="<?php echo $value;?>" size="10" maxlength="10"  value="<?php if (isset(${$value})) echo ${$value}; else echo '0';?>" >
                                <font class="version">
                                <input type="radio" NAME="type[<?php echo $value;?>]" value="1" <?php if((!isset($type[$value]))|| ($type[$value]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
                                <input type="radio" NAME="type[<?php echo $value;?>]" value="2" <?php if($type[$value]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
                                <input type="radio" NAME="type[<?php echo $value;?>]" value="3" <?php if($type[$value]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
                                </font>
                        </td>
               </tr>
               <?php $index=$index+1;
               }?>
		<tr>		
			<td align="right" class="bgcolor_001">
			</td>
		 	<td align="right"  class="bgcolor_001">
				<input class="form_input_button"  value=" <?php echo gettext("BATCH UPDATE RATECARD");?> " type="submit">
        	</td>
		</tr>
		</form>
		</table>
</center>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
	</div>
</div>

<?php

	} // disable Batch update if LCR Export

} // END if ($form_action == "list")


/********************************* BATCH ASSIGNED ***********************************/
if ($popup_select) { 

	$instance_table = new Table("cc_prefix GROUP BY destination", "destination");
	$FG_TABLE_CLAUSE = "";
	$list_destination = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, null, "ASC", null, null, null, null);
	$destination = $list_destination[0];

	$instance_table = new Table("cc_tariffplan", "id, tariffname");
	$FG_TABLE_CLAUSE = "";
	$list_tariffname = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
	$nb_tariffname = count($list_tariffname);

	$instance_table = new Table("cc_trunk", "id_trunk, trunkcode, providerip");
	$FG_TABLE_CLAUSE = "";
	$list_trunk = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "trunkcode", "ASC", null, null, null, null);
	$nb_trunk = count($list_trunk);
	
	$instance_table = new Table("cc_outbound_cid_group", "id, group_name");
	$FG_TABLE_CLAUSE = "";
	$list_cid_group = $instance_table->Get_list($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "group_name", "ASC", null, null, null, null);
	$nb_cid_group = count($list_cid_group);

?>

<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("BATCH ASSIGNED");?> </font></a></center>
	<div class="tohide" style="display:none;">
<center>

<b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("rates selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected rates.");?></b>
	   <table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
		<form name="assignForm" action="javascript:;" method="post">
		<INPUT type="hidden" name="batchupdate" value="1">
		<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
		<INPUT type="hidden" name="popup_select" value="<?php echo $popup_select?>">
		<INPUT type="hidden" name="popup_formname" value="<?php echo $popup_formname?>">
		<INPUT type="hidden" name="popup_fieldname" value="<?php echo $popup_fieldname?>">
		<INPUT type="hidden" name="form_action" value="<?php echo $form_action?>">
		<INPUT type="hidden" name="filterprefix" value="<?php echo $filterprefix?>">
		<INPUT type="hidden" name="filterfield" value="<?php echo $filterfield?>">
		<INPUT type="hidden" name="addbatchrate" value="1">
		
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check" type="checkbox">
		  </td>
		  <td align="left"  class="bgcolor_001">
				<font class="fontstyle_009">1) <?php echo gettext("TRUNK");?> :</font> 
				<select NAME="assign_id_trunk" size="1" class="form_enter" >
					<?php
					 foreach ($list_trunk as $recordset){ 						 
					?>
						<option class=input value='<?php echo $recordset[0]?>'><?php echo $recordset[1].' ('.$recordset[2].')'?></option>                        
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check" type="checkbox">
		  </td>
		  <td align="left"  class="bgcolor_001">
			  <font class="fontstyle_009">	2) <?php echo gettext("RATECARD");?> :</font>
				<select NAME="assign_idtariffplan" size="1" class="form_enter" >
					<?php					 
				  	 foreach ($list_tariffname as $recordset){ 						 
					?>
						<option class=input value='<?php echo $recordset[0]?>'><?php echo $recordset[1]?></option>
					<?php 	 }
					?>
				</select>
				<br/>
			</td>
		</tr>
		
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check" type="checkbox">
				<input name="mode[upd_tag]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">3) <?php echo gettext("TAG");?> :</font>
				<input class="form_input_text" name="assign_tag" size="20">
		  </td>
		</tr>
		
		<tr>
          <td align="left" valign="top" class="bgcolor_001">
				<input name="check" type="checkbox">
				<input name="mode" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">
				<font class="fontstyle_009">4) <?php echo gettext("PREFIX");?> :</font>
						<input class="form_input_text" name="assign_prefix" size="20"><br />
				<font class="version">
				<input type="radio" NAME="rbPrefix" value="1" checked> <?php echo gettext("Exact");?>
				<input type="radio" NAME="rbPrefix" value="2"> <?php echo gettext("Begins with");?>
				<input type="radio" NAME="rbPrefix" value="3"> <?php echo gettext("Contains");?>
				<input type="radio" NAME="rbPrefix" value="4"> <?php echo gettext("Ends with");?>
				<input type="radio" NAME="rbPrefix" value="5"> <?php echo gettext("Expression");?>
				</font>
				<br />
				<font class="fontstyle_009">
					<?php echo gettext("With 'Expression' you can define a range of prefixes. '32484-32487' adds all prefixes between 32484 and 32487. '32484,32386,32488' would add only the individual prefixes listed.");?>
				</font>
			</td>
		</tr>
		<tr>		
			<td align="right" class="bgcolor_001">
			</td>
		 	<td align="right"  class="bgcolor_001">
				<input onclick="javascript:sendOpener();" class="form_input_button"  value=" <?php echo gettext("BATCH ASSIGNED");?> " type="submit">
        	</td>
		</tr>
		</form>
		</table>
</center>

<script language="javascript">
function sendOpener()
{
	if (document.assignForm.check[0].checked==true){
		var id_trunk = document.assignForm.assign_id_trunk.options[document.assignForm.assign_id_trunk.selectedIndex].value;
	}
	
	if (document.assignForm.check[1].checked==true){
		var id_tariffplan = document.assignForm.assign_idtariffplan.options[document.assignForm.assign_idtariffplan.selectedIndex].value;
	}

	if (document.assignForm.check[2].checked==true){
		var tag = document.assignForm.assign_tag.value;
	}

	if (document.assignForm.check[3].checked==true){
		for (var j=0;j<document.assignForm.rbPrefix.length;j++){
		   if (document.assignForm.rbPrefix[j].checked)
		      break;
		}
		var prefix = document.assignForm.assign_prefix.value+"&rbPrefix="+document.assignForm.rbPrefix[j].value;
	}
	window.opener.location.href = "A2B_package_manage_rates.php?id=<?php echo $package;?>&addbatchrate=true"+((id_trunk)? ('&id_trunk='+id_trunk):'')+((id_tariffplan)?('&id_tariffplan='+id_tariffplan):'')+((tag)? ('&tag='+tag):'')+((prefix)? ('&prefix='+prefix):'');
}
</script>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
	</div>
</div>

<?php
} // disable Batch update if not popup
/********************************* END BATCH ASSIGNED ***********************************/

?>


<?php

// Weird hack to create a select form
if ($form_action == "list" && !$popup_select) $HD_Form -> create_select_form();


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;


// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null($HD_Form->SQL_GROUP) && ($HD_Form->SQL_GROUP != ''))
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " $HD_Form->SQL_GROUP ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";

if (strpos($_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR], 'cc_callplan_lcr')===false) {
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] = str_replace('destination,', 'cc_prefix.destination,', $_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]);
}


// #### FOOTER SECTION
$smarty->display('footer.tpl');


