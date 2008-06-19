<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_def_ratecard.inc");
include ("../lib/agent.smarty.php");

if (! has_rights (ACX_RATECARD)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();	   
}

getpost_ifset(array('popup_select', 'popup_formname', 'popup_fieldname','posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'removeallrate', 'removetariffplan', 'definecredit', 'IDCust', 'mytariff_id', 'destination', 'dialprefix', 'buyrate1', 'buyrate2', 'buyrate1type', 'buyrate2type', 'rateinitial1', 'rateinitial2', 'rateinitial1type', 'rateinitial2type', 'id_trunk', "check", "type", "mode"));

/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('batchupdate', 'upd_id_trunk', 'upd_idtariffplan', 'upd_buyrate', 'upd_buyrateinitblock', 'upd_buyrateincrement', 'upd_rateinitial', 'upd_initblock', 'upd_billingblock', 'upd_connectcharge', 'upd_disconnectcharge', 'upd_inuse', 'upd_activated', 'upd_language', 'upd_tariff', 'upd_credit', 'upd_credittype', 'upd_simultaccess', 'upd_currency', 'upd_typepaid', 'upd_creditlimit', 'upd_enableexpire', 'upd_expirationdate', 'upd_expiredays', 'upd_runservice', "filterprefix",'upd_rounding_calltime' ,'upd_rounding_threshold' ,'upd_additional_block_charge' ,'upd_additional_block_charge_time','upd_tag'));



if ($form_action=="add" || $form_action=="edit" || $form_action=="delete"){
	$form_action = "list";
}

/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)){

	$HD_Form->prepare_list_subselection('list');
	
	// Array ( [upd_simultaccess] => on [upd_currency] => on )
	$loop_pass=0;
	$SQL_UPDATE = '';
	foreach ($check as $ind_field => $ind_val){
		//echo "<br>::> $ind_field -";
		$myfield = substr($ind_field,4);
		if ($loop_pass!=0) $SQL_UPDATE.=',';
		
		// Standard update mode
		if (!isset($mode["$ind_field"]) || $mode["$ind_field"]==1){		
			if (!isset($type["$ind_field"])){		
				$SQL_UPDATE .= " $myfield='".$$ind_field."'";
			}else{
				$SQL_UPDATE .= " $myfield='".$type["$ind_field"]."'";
			}
		// Mode 2 - Equal - Add - Substract
		}elseif($mode["$ind_field"]==2){
			if (!isset($type["$ind_field"])){
				$SQL_UPDATE .= " $myfield='".$$ind_field."'";
			}else{
				if ($type["$ind_field"] == 1){
					$SQL_UPDATE .= " $myfield='".$$ind_field."'";					
				}elseif ($type["$ind_field"] == 2){
					if (substr($$ind_field,-1) == "%") {
						$SQL_UPDATE .= " $myfield = ROUND($myfield + (($myfield * ".substr($$ind_field,0,-1).") / 100)+0.00005,4)";
					} else {
						$SQL_UPDATE .= " $myfield = $myfield +'".$$ind_field."'";
					}
				}else{
					if (substr($$ind_field,-1) == "%") {
						$SQL_UPDATE .= " $myfield = ROUND($myfield - (($myfield * ".substr($$ind_field,0,-1).") / 100)+0.00005,4)";
					} else {
						$SQL_UPDATE .= " $myfield = $myfield -'".$$ind_field."'";
					}
				}
			}
		}

		$loop_pass++;
	}

	$SQL_UPDATE = "UPDATE $HD_Form->FG_TABLE_NAME SET $SQL_UPDATE";	
	if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) {
		$SQL_UPDATE .= ' WHERE ';
		$SQL_UPDATE .= $HD_Form->FG_TABLE_CLAUSE;
		if ($_SESSION['def_ratecard'] != null)
		{		
			$SQL_UPDATE .= ' AND '.$_SESSION['def_ratecard'];
		}
	}else{
		$SQL_UPDATE .= ' WHERE '.$_SESSION['def_ratecard'];
	}	
	if (! $res = $HD_Form -> DBHandle -> query($SQL_UPDATE))		$update_msg = "<center><font color=\"red\"><b>".gettext("Could not perform the batch update")."!</b></font></center>";		
	else		$update_msg = "<center><font color=\"green\"><b>".gettext("The batch update has been successfully perform")." !</b></font></center>";		

}
//echo "FG_TABLE_NAME=$HD_Form->FG_TABLE_NAME :: FG_TABLE_CLAUSE=$HD_Form->FG_TABLE_CLAUSE<br>";
/********************************* END BATCH UPDATE ***********************************/



if ($id!="" || !is_null($id)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


if (is_string ($tariffgroup) && strlen(trim($tariffgroup))>0) {		
	list($mytariffgroup_id, $mytariffgroupname, $mytariffgrouplcrtype) = split('-:-', $tariffgroup);
	$_SESSION["mytariffgroup_id"]= $mytariffgroup_id;
	$_SESSION["mytariffgroupname"]= $mytariffgroupname;
	$_SESSION["tariffgrouplcrtype"]= $mytariffgrouplcrtype;
} else {
	$mytariffgroup_id = $_SESSION["mytariffgroup_id"];
	$mytariffgroupname = $_SESSION["mytariffgroupname"];
	$mytariffgrouplcrtype = $_SESSION["tariffgrouplcrtype"];
}



if ( ($form_action == "list") &&  ($HD_Form->FG_FILTER_SEARCH_FORM) && ($_POST['posted_search'] == 1 ) && is_numeric($mytariffgroup_id)) {
	if (!empty($HD_Form->FG_TABLE_CLAUSE)) $HD_Form->FG_TABLE_CLAUSE .= ' AND ';
	
	$HD_Form->FG_TABLE_CLAUSE = "idtariffplan='$mytariff_id'";
}

$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if (!$popup_select) {
	if (($form_action == 'ask-add') || ($form_action == 'ask-edit')) echo $CC_help_add_rate;
} else {
	echo $CC_help_def_ratecard;
}
// DISPLAY THE UPDATE MESSAGE
if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 

if ($popup_select) {
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
if(!$popup_select){
?>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH RATES");?> </font></a></center>
	<div class="tohide" style="display:none;">
<?php
// #### CREATE SEARCH FORM
if ($form_action == "list"){
	$HD_Form -> create_search_form();
}
?>
	</div>
</div>
<?php }

/********************************* BATCH UPDATE ***********************************/
if ($form_action == "list" && !$popup_select){
	
	$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");
	$FG_TABLE_CLAUSE = "";
	$list_tariffname = $instance_table_tariffname  -> Get_list ($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
	$nb_tariffname = count($list_tariffname);
	

	$instance_table_trunk = new Table("cc_trunk", "id_trunk, trunkcode, providerip");
	$FG_TABLE_CLAUSE = "";
	$list_trunk = $instance_table_trunk -> Get_list ($HD_Form->DBHandle, $FG_TABLE_CLAUSE, "trunkcode", "ASC", null, null, null, null);
	$nb_trunk = count($list_trunk);
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
		<INPUT type="hidden" name="atmenu" value="<?php echo $_GET['atmenu']?>">
		<INPUT type="hidden" name="popup_select" value="<?php echo $_GET['popup_select']?>">
		<INPUT type="hidden" name="popup_formname" value="<?php echo $_GET['popup_formname']?>">
		<INPUT type="hidden" name="popup_fieldname" value="<?php echo $_GET['popup_fieldname']?>">
		<INPUT type="hidden" name="form_action" value="<?php echo $_GET['form_action']?>">
		<INPUT type="hidden" name="filterprefix" value="<?php echo $_GET['filterprefix']?>">
		<INPUT type="hidden" name="filterfield" value="<?php echo $_GET['filterfield']?>">
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_id_trunk]" type="checkbox" <?php if ($check["upd_id_trunk"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				<font class="fontstyle_009">1) <?php echo gettext("TRUNK");?> :</font> 
				<select NAME="upd_id_trunk" size="1" class="form_enter" >
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
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_buyrate]" type="checkbox" <?php if ($check["upd_buyrate"]=="on") echo "checked"?>>
				<input name="mode[upd_buyrate]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
			  <font class="fontstyle_009">	3) <?php echo gettext("BUYING RATE");?> :</font>
					<input class="form_input_text" name="upd_buyrate" size="10" maxlength="10"  value="<?php if (isset($upd_buyrate)) echo $upd_buyrate; else echo '0';?>">
				<font class="version">
				<input type="radio" NAME="type[upd_buyrate]" value="1" <?php if((!isset($type["upd_buyrate"]))|| ($type["upd_buyrate"]==1) ){?>checked<?php }?>><?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_buyrate]" value="2" <?php if($type["upd_buyrate"]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_buyrate]" value="3" <?php if($type["upd_buyrate"]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_buyrateinitblock]" type="checkbox" <?php if ($check["upd_buyrateinitblock"]=="on") echo "checked"?>>
				<input name="mode[upd_buyrateinitblock]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">
			  	<font class="fontstyle_009">4) <?php echo gettext("BUYRATE MIN DURATION");?> :</font>
					<input class="form_input_text" name="upd_buyrateinitblock" size="10" maxlength="10" value="<?php if (isset($upd_buyrateinitblock)) echo $upd_buyrateinitblock; else echo '0';?>">
				<font class="version">
				<input type="radio" NAME="type[upd_buyrateinitblock]" value="1" <?php if((!isset($type["upd_buyrateinitblock"]))|| ($type["upd_buyrateinitblock"]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_buyrateinitblock]" value="2" <?php if($type["upd_buyrateinitblock"]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_buyrateinitblock]" value="3" <?php if($type["upd_buyrateinitblock"]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_buyrateincrement]" type="checkbox" <?php if ($check["upd_buyrateincrement"]=="on") echo "checked"?>>
				<input name="mode[upd_buyrateincrement]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
			  	<font class="fontstyle_009">5) <?php echo gettext("BUYRATE BILLING BLOCK");?> :</font>
					<input class="form_input_text" name="upd_buyrateincrement" size="10" maxlength="10"  value="<?php if (isset($upd_buyrateincrement)) echo $upd_buyrateincrement; else echo '0';?>">
				<font class="version">
				<input type="radio" NAME="type[upd_buyrateincrement]" value="1" <?php if((!isset($type["upd_buyrateincrement"]))|| ($type["upd_buyrateincrement"]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_buyrateincrement]" value="2" <?php if($type["upd_buyrateincrement"]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_buyrateincrement]" value="3" <?php if($type["upd_buyrateincrement"]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_rateinitial]" type="checkbox" <?php if ($check["upd_rateinitial"]=="on") echo "checked"?>>
				<input name="mode[upd_rateinitial]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">
				<font class="fontstyle_009">6) <?php echo gettext("SELLING RATE");?> :</font>
				 	<input class="form_input_text" name="upd_rateinitial" size="10" maxlength="10" value="<?php if (isset($upd_rateinitial)) echo $upd_rateinitial; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_rateinitial]" value="1" <?php if((!isset($type[upd_rateinitial]))|| ($type[upd_rateinitial]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_rateinitial]" value="2" <?php if($type[upd_rateinitial]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_rateinitial]" value="3" <?php if($type[upd_rateinitial]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_initblock]" type="checkbox" <?php if ($check["upd_initblock"]=="on") echo "checked"?>>
				<input name="mode[upd_initblock]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">7) <?php echo gettext("SELLRATE MIN DURATION");?>  :</font>
				 	<input class="form_input_text" name="upd_initblock" size="10" maxlength="10"  value="<?php if (isset($upd_initblock)) echo $upd_initblock; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_initblock]" value="1" <?php if((!isset($type[upd_initblock]))|| ($type[upd_initblock]==1) ){?>checked<?php }?>>  <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_initblock]" value="2" <?php if($type[upd_initblock]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_initblock]" value="3" <?php if($type[upd_initblock]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_billingblock]" type="checkbox" <?php if ($check["upd_billingblock"]=="on") echo "checked"?>>
				<input name="mode[upd_billingblock]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">8) <?php echo gettext("SELLRATE BILLING BLOCK");?>  :</font>
				 	<input class="form_input_text" name="upd_billingblock" size="10" maxlength="10" value="<?php if (isset($upd_billingblock)) echo $upd_billingblock; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_billingblock]" value="1" <?php if((!isset($type[upd_billingblock]))|| ($type[upd_billingblock]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_billingblock]" value="2" <?php if($type[upd_billingblock]==2){?>checked<?php }?>><?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_billingblock]" value="3" <?php if($type[upd_billingblock]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_connectcharge]" type="checkbox" <?php if ($check["upd_connectcharge"]=="on") echo "checked"?>>
				<input name="mode[upd_connectcharge]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">
				<font class="fontstyle_009">9) <?php echo gettext("CONNECT CHARGE");?>  :</font>
				 	<input class="form_input_text" name="upd_connectcharge" size="10" maxlength="10"  value="<?php if (isset($upd_connectcharge)) echo $upd_connectcharge; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_connectcharge]" value="1" <?php if((!isset($type[upd_connectcharge]))|| ($type[upd_connectcharge]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_connectcharge]" value="2" <?php if($type[upd_connectcharge]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_connectcharge]" value="3" <?php if($type[upd_connectcharge]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_disconnectcharge]" type="checkbox" <?php if ($check["upd_disconnectcharge"]=="on") echo "checked"?>>
				<input name="mode[upd_disconnectcharge]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">10) <?php echo gettext("DISCONNECT CHARGE");?> :</font>
				 	<input class="form_input_text" name="upd_disconnectcharge" size="10" maxlength="10"  value="<?php if (isset($upd_disconnectcharge)) echo $upd_disconnectcharge; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_disconnectcharge]" value="1" <?php if((!isset($type[upd_disconnectcharge]))|| ($type[upd_disconnectcharge]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_disconnectcharge]" value="2" <?php if($type[upd_disconnectcharge]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_disconnectcharge]" value="3" <?php if($type[upd_disconnectcharge]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_rounding_calltime]" type="checkbox" <?php if ($check["upd_rounding_calltime"]=="on") echo "checked"?>>
				<input name="mode[upd_rounding_calltime]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">11) <?php echo gettext("ROUNDING CALLTIME");?> :</font>
				 	<input class="form_input_text" name="upd_rounding_calltime" size="10" maxlength="10"  value="<?php if (isset($upd_rounding_calltime)) echo $upd_rounding_calltime; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_rounding_calltime]" value="1" <?php if((!isset($type[upd_rounding_calltime]))|| ($type[upd_rounding_calltime]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_rounding_calltime]" value="2" <?php if($type[upd_rounding_calltime]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_rounding_calltime]" value="3" <?php if($type[upd_rounding_calltime]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_rounding_threshold]" type="checkbox" <?php if ($check["upd_rounding_threshold"]=="on") echo "checked"?>>
				<input name="mode[upd_rounding_threshold]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">12) <?php echo gettext("ROUNDING THRESHOLD");?> :</font>
				 	<input class="form_input_text" name="upd_rounding_threshold" size="10" maxlength="10"  value="<?php if (isset($upd_rounding_threshold)) echo $upd_rounding_threshold; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_rounding_threshold]" value="1" <?php if((!isset($type[upd_rounding_threshold]))|| ($type[upd_roundingthreshold]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_rounding_threshold]" value="2" <?php if($type[upd_rounding_threshold]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_rounding_threshold]" value="3" <?php if($type[upd_rounding_threshold]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_additional_block_charge]" type="checkbox" <?php if ($check["upd_additional_block_charge"]=="on") echo "checked"?>>
				<input name="mode[upd_additional_block_charge]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">13) <?php echo gettext("ADDITIONAL BLOCK CHARGE");?> :</font>
				 	<input class="form_input_text" name="upd_additional_block_charge" size="10" maxlength="10"  value="<?php if (isset($upd_additional_block_charge)) echo $upd_additional_block_charge; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_additional_block_charge]" value="1" <?php if((!isset($type[upd_additional_block_charge]))|| ($type[upd_additional_block_charge]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_additional_block_charge]" value="2" <?php if($type[upd_additional_block_charge]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_additional_block_charge]" value="3" <?php if($type[upd_additional_block_charge]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_additional_block_charge_time]" type="checkbox" <?php if ($check["upd_additional_block_charge_time"]=="on") echo "checked"?>>
				<input name="mode[upd_additional_block_charge_time]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">	
				
				<font class="fontstyle_009">14) <?php echo gettext("ADDITIONAL BLOCK CHARGE TIME");?> :</font>
				 	<input class="form_input_text" name="upd_additional_block_charge_time" size="10" maxlength="10"  value="<?php if (isset($upd_additional_block_charge_time)) echo $upd_additional_block_charge_time; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_additional_block_charge_time]" value="1" <?php if((!isset($type[upd_additional_block_charge_time]))|| ($type[upd_additional_block_charge_time]==1) ){?>checked<?php }?>> <?php echo gettext("Equal");?>
				<input type="radio" NAME="type[upd_additional_block_charge_time]" value="2" <?php if($type[upd_additional_block_charge_time]==2){?>checked<?php }?>> <?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_additional_block_charge_time]" value="3" <?php if($type[upd_additional_block_charge_time]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
			</td>
		</tr>
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
} // END if ($form_action == "list")
?>


<br>
<?php

// Weird hack to create a select form
if ($form_action == "list" && !$popup_select) $HD_Form -> create_select_form_agent();


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;


// Code for the Export Functionality
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";



// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
