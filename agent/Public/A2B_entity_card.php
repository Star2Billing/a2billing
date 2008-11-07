<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_card.inc");
include ("../lib/agent.smarty.php");



if (! has_rights (ACX_CUSTOMER)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());

//SECURTY CHECK FOR AGENT

if ($form_action != "list" && isset($id)) {
	if(!empty($id)&& $id>0){
		$table_agent_security = new Table("cc_card", " id_agent");
		$clause_agent_security = "id= ".$id;
		$result_security= $table_agent_security -> Get_list ($HD_Form -> DBHandle, $clause_agent_security, null, null, null, null, null, null);
		if ( $result_security[0][0] !=$_SESSION['agent_id'] ) { 
			Header ("HTTP/1.0 401 Unauthorized");
			Header ("Location: PP_error.php?c=accessdenied");	   
			die();	   
		}
	}
}
$HD_Form -> init();



/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('popup_select', 'popup_formname', 'popup_fieldname', 'upd_inuse', 'upd_status', 'upd_language', 'upd_tariff', 'upd_credit', 'upd_credittype', 'upd_simultaccess', 'upd_currency', 'upd_typepaid', 'upd_creditlimit', 'upd_enableexpire', 'upd_expirationdate', 'upd_expiredays', 'upd_runservice', 'upd_runservice', 'batchupdate', 'check', 'type', 'mode', 'addcredit', 'cardnumber','description','refill_type'));
// CHECK IF REQUEST OF BATCH UPDATE
if ($batchupdate == 1 && is_array($check)) {
	
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
		// Mode 2 - Equal - Add - Subtract
		}elseif($mode["$ind_field"]==2){
			if (!isset($type["$ind_field"])){		
				$SQL_UPDATE .= " $myfield='".$$ind_field."'";
			}else{
				if ($type["$ind_field"] == 1){
					$SQL_UPDATE .= " $myfield='".$$ind_field."'";					
				}elseif ($type["$ind_field"] == 2){
					$SQL_UPDATE .= " $myfield = $myfield +'".$$ind_field."'";
				}else{
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
	
	if (! $res = $HD_Form -> DBHandle -> Execute($SQL_UPDATE)) {
		$update_msg = '<center><font color="red"><b>'.gettext('Could not perform the batch update!').'</b></font></center>';
	} else {
		$update_msg = '<center><font color="green"><b>'.gettext('The batch update has been successfully perform!').'</b></font></center>';
	}
	
}
/********************************* END BATCH UPDATE ***********************************/


if (($form_action == "addcredit") && ($addcredit>0 || $addcredit<0) && ($id>0 || $cardnumber>0)) {
	
	$instance_table = new Table("cc_card", "username, id");
	
	if ($cardnumber>0){
		/* CHECK IF THE CARDNUMBER IS ON THE DATABASE */			
		$FG_TABLE_CLAUSE_card = "username='".$cardnumber;
		$list_tariff_card = $instance_table -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);			
		if ($cardnumber == $list_tariff_card[0][0]) $id = $list_tariff_card[0][1];
		
	}
	if ($id>0){
		
		$instance_check_card_agent = new Table("cc_card", " id_agent");
		$FG_TABLE_CLAUSE_check = "id= ".$id;
		$list_check= $instance_check_card_agent -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_check, null, null, null, null, null, null);
		if ( $list_check[0][0] ==$_SESSION['agent_id'] ) { 
			
				
			//chech if enought credit
			$instance_table_agent = new Table("cc_agent", "credit, currency");
			$FG_TABLE_CLAUSE_AGENT = "id = ".$_SESSION['agent_id'] ;
			$agent_info = $instance_table_agent -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_AGENT, null, null, null, null, null, null);			
			$credit_agent = $agent_info[0][0];
			  
			if($credit_agent>=$addcredit){
				
		   //Substract credit for agent
			$param_update_agent = "credit = credit - '".$addcredit."'";
			$instance_table_agent -> Update_table ($HD_Form -> DBHandle, $param_update_agent, $FG_TABLE_CLAUSE_AGENT, $func_table = null);	
			
		   // Add credit to Customer	
			$param_update .= "credit = credit + '".$addcredit."'";
			if ($HD_Form->FG_DEBUG == 1)  echo "<br><hr> $param_update";	
			
			$FG_EDITION_CLAUSE = " id='$id'" ; // AND id_agent=".$_SESSION['agent_id'];
			
			if ($HD_Form->FG_DEBUG == 1)  echo "<br>-----<br>$param_update<br>$FG_EDITION_CLAUSE";			
			 $instance_table -> Update_table ($HD_Form -> DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
			
			$update_msg ='<b><font color="green">'.gettext("Refill executed ").'</font></b>';	
			
			$field_insert = "date, credit, card_id, description, refill_type";
			$value_insert = "now(), '$addcredit', '$id','$description','$refill_type'";
			$instance_sub_table = new Table("cc_logrefill", $field_insert);
			$result_query = $instance_sub_table -> Add_table ($HD_Form -> DBHandle, $value_insert, null, null);	
			
			if (!$result_query ){		
				$update_msg ="<b>".$instance_sub_table -> errstr."</b>";	
			}
				
				
			}else{
					
				$currencies_list = get_currencies();
		
				if (!isset($currencies_list[strtoupper($agent_info [0][1])][2]) || !is_numeric($currencies_list[strtoupper($agent_info [0][1])][2])) $mycur = 1;
				else $mycur = $currencies_list[strtoupper($agent_info [0][1])][2];
				$credit_cur = $agent_info[0][0] / $mycur;
				$credit_cur = round($credit_cur,3);
				
				$update_msg ='<b> <font color="red">'.gettext("You don't have enough credit to do this refill. You have ").$credit_cur.' '.$agent_info[0][1].' </font></b>';	
			}
		}else{
				$update_msg ='<b><font color="red">'.gettext("Impossible to refill this card ").'</font></b>';	
		}
	}
}

if ($form_action == "addcredit")	$form_action='list';


if ($id!="" || !is_null($id)){	
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');



if ($popup_select){
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


// #### HELP SECTION
if ($form_action=='list' && !($popup_select>=1)){
echo $CC_help_list_customer;


?>
<script language="JavaScript" src="javascript/card.js"></script>


<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("REFILL");?> </font></a></center>
	<div class="tohide" style="display:none;">
	<form NAME="theForm">
	   <table width="90%" border="0" align="center">
        <tr>
		   <td align="left" width="5%"><img src="<?php echo KICON_PATH; ?>/pipe.gif">
		   </td>
          <td align="left" width="35%" class="bgcolor_001">
           	<table>
			<tr><td align="center">
			   <?php echo gettext("CARD ID");?>	 :<input class="form_input_text" name="choose_list" onfocus="clear_textbox2();" size="18" maxlength="16" value="enter ID Card">
				<a href="#" onclick="window.open('A2B_entity_card.php?nodisplay=1&popup_select=1&popup_formname=theForm&popup_fieldname=choose_list' , 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
					   <?php echo gettext("or");?>
			</td></tr>
			<tr><td align="center">
				&nbsp; <?php echo gettext("CARDNUMBER");?>&nbsp;:<input class="form_input_text" name="cardnumber" onfocus="clear_textbox();" size="18" maxlength="16" value="enter cardnumber">
			</td></tr>
			</table>
		</td>
		<td  class="bgcolor_001" align="center">	
			<table>
				<tr>
					<td>
						<?php echo gettext("CREDIT");?>&nbsp;:
					</td>
					<td>
						<input class="form_enter" name="addcredit" size="18" maxlength="6" value=""> <?php echo strtoupper($A2B->config['global']['base_currency']); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo gettext("DESCRIPTION");?>&nbsp;:
					</td>
					<td>
						<textarea class="form_input_textarea" name="description" cols="40" rows="4"></textarea> 
					</td>
				</tr>
				<tr>
					<td>
						<?php echo gettext("TYPE");?>&nbsp;:
					</td>
					<td>
						<select name="refill_type" size="1"  class="form_input_select">
							<?php 
								$list_type = Constants::getRefillType_List();
								foreach ($list_type as $type){
							?>
							<option value="<?php echo $type[1] ?>"><?php echo $type[0] ?> </option>
							<?php
								} ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input class="form_input_button" 
				TYPE="button" VALUE="<?php echo gettext("ADD CREDIT TO THE SELECTED CARD");?>" onClick="openURL('<?php echo $_SERVER['PHP_SELF']?>?form_action=addcredit&stitle=Card_Refilled&current_page=<?php echo $current_page?>&order=<?php echo $order?>&sens=<?php echo $sens?>&id=')">
        	
					</td>
				</tr>
			</table>			
			
        </td>
        </tr>
        
      </table>
      </form>
	</div>
</div>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH CARDS");?> </font></a></center>
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
<b>&nbsp;<?php echo $HD_Form -> FG_NB_RECORD ?> <?php echo gettext("cards selected!"); ?>&nbsp;<?php echo gettext("Use the options below to batch update the selected cards.");?></b>
	   <table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
        <tbody>
		<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
		<INPUT type="hidden" name="batchupdate" value="1">
		<tr>		
          <td align="left" class="bgcolor_001" >
		  		<input name="check[upd_inuse]" type="checkbox" <?php if ($check["upd_inuse"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				1)&nbsp;<?php echo gettext("INUSE"); ?>&nbsp;: 
				<select NAME="upd_inuse" size="1" class="form_input_select">
				<?php 
					foreach($inuse_list as $key => $cur_value) {											
				?>
					<option value='<?php echo $cur_value[1] ?>'  <?php if ($upd_inuse==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
				<?php } ?>			
		    </select>
		  </td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_status]" type="checkbox" <?php if ($check["upd_status"]=="on") echo "checked"?> >
		  </td>
		  <td align="left" class="bgcolor_001">
			  	2)&nbsp;<?php echo gettext("STATUS");?>&nbsp;:
				<select NAME="upd_status" size="1" class="form_input_select">
					<?php					 
				  	 foreach ($cardstatus_list as $key => $cur_value){ 						 
					?>
						<option value='<?php echo $cur_value[1] ?>' <?php if ($upd_status==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>                        
					<?php } ?>
				</select><br/>
		  </td>
		</tr>

		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_language]" type="checkbox" <?php if ($check["upd_language"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				3)&nbsp;<?php echo gettext("LANGUAGE");?>&nbsp;: 
				<select NAME="upd_language" size="1" class="form_input_select">
				<?php 
					foreach($language_list as $key => $cur_value) {											
				?>
					<option value='<?php echo $cur_value[1] ?>' <?php if ($upd_language==$cur_value[1]) echo 'selected="selected"'?>><?php echo $cur_value[0] ?></option>
				<?php } ?>			
		    </select>
		  </td>
		</tr>
		<tr>		
          <td align="left"  class="bgcolor_001">
		  	<input name="check[upd_tariff]" type="checkbox" <?php if ($check["upd_tariff"]=="on") echo "checked"?> >
		  </td>
		  <td align="left" class="bgcolor_001">
			  	4)&nbsp;<?php echo gettext("TARIFF");?>&nbsp;:
				<select NAME="upd_tariff" size="1" class="form_input_select">
					<?php					 
				  	 foreach ($list_tariff as $recordset){ 						 
					?>
						<option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_tariff==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>                        
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
			  	5)&nbsp;<?php echo gettext("CREDIT");?>&nbsp;:
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
		  		<input name="check[upd_simultaccess]" type="checkbox" <?php if ($check["upd_simultaccess"]=="on") echo "checked"?>>
		  </td>
		  <td align="left" class="bgcolor_001">	
				6)&nbsp;<?php echo gettext("ACCESS");?>&nbsp;: 
				<select NAME="upd_simultaccess" size="1" class="form_input_select">
					<option value='0'  <?php if ($upd_simultaccess==0) echo 'selected="selected"'?>><?php echo gettext("INDIVIDUAL ACCESS");?></option>
					<option value='1'  <?php if ($upd_simultaccess==1) echo 'selected="selected"'?>><?php echo gettext("SIMULTANEOUS ACCESS");?></option>
		    </select>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_currency]" type="checkbox" <?php if ($check["upd_currency"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				7)&nbsp;<?php echo gettext("CURRENCY");?>&nbsp;:
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
          <td align="left"  class="bgcolor_001">
		  		<input name="check[upd_typepaid]" type="checkbox" <?php if ($check["upd_typepaid"]=="on") echo "checked"?>>
		  </td>
		  <td align="left" class="bgcolor_001">	
				8)&nbsp;<?php echo gettext("CARD TYPE");?>&nbsp;:
				<select NAME="upd_typepaid" size="1" class="form_input_select" >
					<option value='0'  <?php if ($upd_typepaid==0) echo 'selected="selected"'?>><?php echo gettext("PREPAID CARD");?></option>
					<option value='1'  <?php if ($upd_typepaid==1) echo 'selected="selected"'?>><?php echo gettext("POSTPAY CARD");?></option>
		    </select>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_creditlimit]" type="checkbox" <?php if ($check["upd_creditlimit"]=="on") echo "checked"?>>
				<input name="mode[upd_creditlimit]" type="hidden" value="2">
		  </td>
		  <td align="left"  class="bgcolor_001">
				9)&nbsp;<?php echo gettext("LIMIT CREDIT OF POSTPAY");?>&nbsp;:
				 	<input class="form_input_text" name="upd_creditlimit" size="10" maxlength="10"  value="<?php if (isset($upd_creditlimit)) echo $upd_creditlimit; else echo '0';?>" >
				<font class="version">
				<input type="radio" NAME="type[upd_creditlimit]" value="1" <?php if((!isset($type[upd_creditlimit]))|| ($type[upd_creditlimit]==1) ){?>checked<?php }?>> <?php echo gettext("Equals");?>
				<input type="radio" NAME="type[upd_creditlimit]" value="2" <?php if($type[upd_creditlimit]==2){?>checked<?php }?>><?php echo gettext("Add");?>
				<input type="radio" NAME="type[upd_creditlimit]" value="3" <?php if($type[upd_creditlimit]==3){?>checked<?php }?>> <?php echo gettext("Subtract");?>
				</font>
		  </td>
		</tr>
		<tr>
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_enableexpire]" type="checkbox" <?php if ($check["upd_enableexpire"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				10)&nbsp;<?php echo gettext("ENABLE EXPIRE");?>&nbsp;: 
				<select name="upd_enableexpire" class="form_input_select" >
					<option value="0"  <?php if ($upd_enableexpire==0) echo 'selected="selected"'?>> <?php echo gettext("NO EXPIRY");?></option>
					<option value="1"  <?php if ($upd_enableexpire==1) echo 'selected="selected"'?>> <?php echo gettext("EXPIRE DATE");?></option>
					<option value="2"  <?php if ($upd_enableexpire==2) echo 'selected="selected"'?>> <?php echo gettext("EXPIRE DAYS SINCE FIRST USE");?></option>
					<option value="3"  <?php if ($upd_enableexpire==3) echo 'selected="selected"'?>> <?php echo gettext("EXPIRE DAYS SINCE CREATION");?></option>
				</select>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_expirationdate]" type="checkbox" <?php if ($check["upd_expirationdate"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				<?php 
					$begin_date = date("Y");
					$begin_date_plus = date("Y")+10;	
					$end_date = date("-m-d H:i:s");
					$comp_date = "value='".$begin_date.$end_date."'";
					$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
				?>
				11)&nbsp;<?php echo gettext("EXPIRY DATE");?>&nbsp;:
				 <input class="form_input_text"  name="upd_expirationdate" size="20" maxlength="30" <?php echo $comp_date_plus; ?>> <font class="version"><?php echo gettext("(Format YYYY-MM-DD HH:MM:SS)");?></font>
		  </td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  		<input name="check[upd_expiredays]" type="checkbox" <?php if ($check["upd_expiredays"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				12)&nbsp;<?php echo gettext("EXPIRATION DAYS");?>&nbsp;: 
				<input class="form_input_text"  name="upd_expiredays" size="10" maxlength="6" value="<?php if (isset($upd_expiredays)) echo $upd_expiredays; else echo '0';?>">
				<br/>
		</td>
		</tr>
		<tr>		
          <td align="left" class="bgcolor_001">
		  	<input name="check[upd_runservice]" type="checkbox" <?php if ($check["upd_runservice"]=="on") echo "checked"?>>
		  </td>
		  <td align="left"  class="bgcolor_001">
				 13)&nbsp;<?php echo gettext("RUN SERVICE");?>&nbsp;: 	
				<font class="version">
				<input type="radio" NAME="type[upd_runservice]" value="1" <?php if((!isset($type[upd_runservice]))|| ($type[upd_runservice]=='1') ){?>checked<?php }?>>
				<?php echo gettext("Yes");?> <input type="radio" NAME="type[upd_runservice]" value="0" <?php if($type[upd_runservice]=='0'){?>checked<?php }?>><?php echo gettext("No");?>
				</font>
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
} // END if ($form_action == "list")
?>


<?php  if ( isset($_SESSION["is_sip_iax_change"]) && $_SESSION["is_sip_iax_change"]){ ?>
	  <table width="<?php echo $HD_Form -> FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0" >	  
		<TR><TD style="border-bottom: medium dotted #ED2525" align="center"> <?php echo gettext("Changes detected on SIP/IAX Friends");?></TD></TR>
		<TR><FORM NAME="sipfriend">
            <td height="31" class="bgcolor_013" style="padding-left: 5px; padding-right: 3px;" align="center">			
			<font color=white><b>
			<?php  if ( isset($_SESSION["is_sip_changed"]) && $_SESSION["is_sip_changed"] ){ ?>
			SIP : <input class="form_input_button"  TYPE="button" VALUE="<?php echo gettext("GENERATE ADDITIONAL_A2BILLING_SIP.CONF");?>"
			onClick="self.location.href='./CC_generate_friend_file.php?atmenu=sipfriend';">
			<?php }
			if ( isset($_SESSION["is_iax_changed"]) && $_SESSION["is_iax_changed"] ){ ?>
			IAX : <input class="form_input_button"  TYPE="button" VALUE="<?php echo gettext("GENERATE ADDITIONAL_A2BILLING_IAX.CONF");?>"
			onClick="self.location.href='./CC_generate_friend_file.php?atmenu=iaxfriend';">
			<?php } ?>	
			</b></font></td></FORM>
        </TR>
</table>
<?php  } // endif is_sip_iax_change

}elseif (!($popup_select>=1)) echo $CC_help_create_customer;


if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 



// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);
if (!$popup_select && $form_action == "ask-add"){
?>
<table width="70%" align="center" cellpadding="2" cellspacing="0">
	<script language="javascript">
	function submitform()
	{
		document.cardform.submit();
	}
	</script>
	<form action="A2B_entity_card.php?form_action=ask-add&atmenu=card&stitle=Card&section=1" method="post" name="cardform">
	<tr>
		<td class="viewhandler_filter_td1">
		<span>		
			
			<font class="viewhandler_filter_on"><?php echo gettext("Change the Card Number Length")?> :</font>
			<select name="cardnumberlenght_list" size="1" class="form_input_select" onChange="submitform()">
			<?php 
			//for($i=CARDNUMBER_LENGTH_MIN;$i<=CARDNUMBER_LENGTH_MAX;$i++) {
			
			foreach ($A2B -> cardnumber_range as $value){
			?>
				<option value='<?php echo $value ?>' 
				<?php if ($value == $cardnumberlenght_list) echo "selected";
				?>> <?php echo $value." ".gettext("Digits");?> </option>
				
			<?php
			}
			?>						
			</select>
		</span>
		</td>	
	</tr>
	</form>
</table>

<?php
}
// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];
if ($form_action=='ask-edit')
{
	$inst_table = new Table("cc_card", "useralias, uipass");
	$FG_TABLE_CLAUSE = "id = $id";
	$list_card_info = $inst_table -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE);			
	$username = $list_card_info[0][0];
	$password = base64_encode($list_card_info[0][1]);
	$link = CUSTOMER_UI_URL;
	echo "<div align=\"right\" style=\"padding-right:20px;\"><a href=\"$link?username=$username&password=$password\" target=\"_blank\">GO TO CUSTOMER ACCOUNT</a></div>";
}

$HD_Form -> create_form ($form_action, $list, $id=null) ;


// Code for the Export Functionality
//* Query Preparation.
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";



// #### FOOTER SECTION
if (!($popup_select>=1)) $smarty->display('footer.tpl');

?>
