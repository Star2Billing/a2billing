<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_def_ratecard.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_RATECARD)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();	   
}


getpost_ifset(array('popup_select', 'popup_formname', 'popup_fieldname','posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'current_page', 'removeallrate', 'removetariffplan', 'definecredit', 'IDCust', 'mytariff_id', 'destination', 'dialprefix', 'buyrate1', 'buyrate2', 'buyrate1type', 'buyrate2type', 'rateinitial1', 'rateinitial2', 'rateinitial1type', 'rateinitial2type', 'id_trunk', "check", "type", "mode"));


/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('batchupdate', 'upd_id_trunk', 'upd_idtariffplan','upd_tag',
# TODO: check why??? we use folowing
 'upd_inuse', 'upd_activated', 'upd_language', 'upd_tariff', 'upd_credit', 'upd_credittype', 'upd_simultaccess', 'upd_currency', 'upd_typepaid', 'upd_creditlimit', 'upd_enableexpire', 'upd_expirationdate', 'upd_expiredays', 'upd_runservice', "filterprefix"));

$update_fields=array("upd_buyrate","upd_buyrateinitblock","upd_buyrateincrement","upd_rateinitial","upd_initblock",
			"upd_billingblock","upd_connectcharge","upd_disconnectcharge","upd_rounding_calltime","upd_rounding_threshold",
			"upd_additional_block_charge","upd_additional_block_charge_time");
$update_fields_info=array("BUYING RATE","BUYRATE MIN DURATION","BUYRATE BILLING BLOCK","SELLING RATE","SELLRATE MIN DURATION",
			"SELLRATE BILLING BLOCK","CONNECT CHARGE","DISCONNECT CHARGE","ROUNDING CALLTIME","ROUNDING THRESHOLD",
			"ADDITIONAL BLOCK CHARGE","ADDITIONAL BLOCK CHARGE TIME");
$charges_abc=array();
$charges_abc_info=array();
if (ADVANCED_MODE){
       $charges_abc=array("upd_stepchargea","upd_chargea","upd_timechargea","upd_stepchargeb","upd_chargeb","upd_timechargeb","upd_stepchargec","upd_chargec","upd_timechargec","upd_announce_time_correction");
       $charges_abc_info=array("ENTRANCE CHARGE A","COST A","TIME FOR A","ENTRANCE CHARGE B","COST B","TIME FOR B","ENTRANCE CHARGE C","COST C","TIME FOR C","ANNOUNCE TIME CORRECTION");
};


getpost_ifset($update_fields);

if (ADVANCED_MODE){
	getpost_ifset($charges_abc);
};


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
	  if(strlen($_SESSION['def_ratecard'])>1) {
		$SQL_UPDATE .= ' WHERE '.$_SESSION['def_ratecard'];
	  }
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
	
	/*
	SELECT t1.destination, min(t1.rateinitial), t1.dialprefix FROM cc_ratecard t1, cc_tariffplan t4, cc_tariffgroup t5, 
	cc_tariffgroup_plan t6 
	WHERE t4.id = t6.idtariffplan AND t6.idtariffplan=t1.idtariffplan AND t6.idtariffgroup = '3' 
	GROUP BY t1.dialprefix
	*/
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

                                <font class="fontstyle_009"><?php echo ($index+3).") ".gettext($update_fields_info[$index]);?> :</font>
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
} // END if ($form_action == "list")
?>


<br>
<?php

// Weird hack to create a select form
if ($form_action == "list" && !$popup_select) $HD_Form -> create_select_form();


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


