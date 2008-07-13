<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_card.inc");
include ("../lib/smarty.php");

if (! has_rights (ACX_CUSTOMER)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}


/***********************************************************************************/

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$HD_Form->FG_DEBUG = 0;

/********************************* BATCH UPDATE ***********************************/
getpost_ifset(array('addcredit', 'cardnumber'));

if (($form_action == "addcredit") && ($addcredit>0 || $addcredit<0) && ($id>0 || $cardnumber>0)){

	$instance_table = new Table("cc_card", "username, id");
	
	if ($cardnumber>0){
		/* CHECK IF THE CARDNUMBER IS ON THE DATABASE */			
		$FG_TABLE_CLAUSE_card = "username='{$cardnumber}'";
		$card = $instance_table -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_card, null, null, null, null, null, null);			
		if ($cardnumber == $card[0][0]) $id = $card[0][1];
	}

	if ($id>0){
		
		$param_update .= "credit = credit + {$addcredit}";
		if ($HD_Form->FG_DEBUG == 1)  echo "<br><hr> {$param_update}";	
		
		$FG_EDITION_CLAUSE = " id='$id'";
		
		if ($HD_Form->FG_DEBUG == 1)  echo "<br>-----<br>{$param_update}<br>{$FG_EDITION_CLAUSE}";			
		$instance_table -> Update_table ($HD_Form -> DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
		
		$field_insert = "date, credit, card_id";
		$value_insert = "now(), '$addcredit', '$id'";
		$instance_sub_table = new Table("cc_logrefill", $field_insert);
		$result_query = $instance_sub_table -> Add_table ($HD_Form->DBHandle, $value_insert, null, null);	
		
		if (!$result_query ){		
			$update_msg ="<b>{$instance_sub_table->errstr}</b>";	
		}
	}
}

if (isset($update_msg) && (strlen($update_msg) > 0) && ($HD_Form->FG_DEBUG == 1)) echo $update_msg; 
header("Location: A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
