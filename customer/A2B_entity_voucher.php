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


include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_voucher.inc");
include ("./lib/customer.smarty.php");

if (! has_rights (ACX_VOUCHER)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();
$currencies_list = get_currencies();


if (strlen($voucher)>0) {
	
	if (is_numeric($voucher)) {
		
		sleep(2);
		$FG_VOUCHER_TABLE  = "cc_voucher";
		$FG_VOUCHER_FIELDS = "voucher, credit, activated, tag, currency, expirationdate";
		$instance_sub_table = new Table($FG_VOUCHER_TABLE, $FG_VOUCHER_FIELDS);

		$FG_TABLE_CLAUSE_VOUCHER = "expirationdate >= CURRENT_TIMESTAMP AND activated='t' AND voucher='$voucher'";

		$list_voucher = $instance_sub_table -> Get_list ($HD_Form -> DBHandle, $FG_TABLE_CLAUSE_VOUCHER, $order, $sens, null, null, $limite, $current_record);

		if ($list_voucher[0][0]==$voucher) {
			if (!isset ($currencies_list[strtoupper($list_voucher[0][4])][2])) {
				$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("System Error : the currency table is incomplete!").'</b></font><br><br>';
			} else {
				$add_credit = $list_voucher[0][1]*$currencies_list[strtoupper($list_voucher[0][4])][2];
				$QUERY = "UPDATE cc_voucher SET activated='f', usedcardnumber='".$_SESSION["pr_login"]."', usedate=now() WHERE voucher='".$voucher."'";
				$result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

				$QUERY = "UPDATE cc_card SET credit=credit+'".$add_credit."' WHERE username='".$_SESSION["pr_login"]."'";
				$result = $instance_sub_table -> SQLExec ($HD_Form -> DBHandle, $QUERY, 0);

				$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="green"><b>'.gettext("The voucher").'('.$voucher.') '.gettext("has been used, We added").' '.$add_credit.' '.gettext("credit on your account!").'</b></font><br><br>';
			}
		} else {
			$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("This voucher doesn't exist !").'</b></font><br><br>';
		}
	} else {
		$error_msg = '<font face="Arial, Helvetica, sans-serif" size="2" color="red"><b>'.gettext("The voucher should be a number !").'</b></font><br><br>';
	}
}

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display( 'main.tpl');

// #### HELP SECTION
if ($form_action=='list') {
    echo $CC_help_list_voucher;
}

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

?>

  <br>
	<center><?php echo $error_msg ?> </center>
	<center>
	   <table class="voucher_table1" align="center">
        <tbody><tr>
		<form name="theForm" action="A2B_entity_voucher.php">
          <td align="left" width="75%">
		  	<strong> <?php echo gettext("VOUCHER");?> :</strong>
			<input class="form_input_text" name="voucher" size="50" maxlength="40" >
			<br/>
		</td>
		<td align="left" valign="bottom">
		<input class="form_input_button"  value=" <?php echo gettext("USE VOUCHER");?> " type="submit">
        </td>
	 </form>
        </tr>
      </tbody></table></center>
	  <br>


<?php

// #### CREATE FORM OR LIST

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// Code for the Export Functionality
//* Query Preparation.
$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1)
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";

if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!=''))
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";


// #### FOOTER SECTION
$smarty->display('footer.tpl');

