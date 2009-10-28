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


if (! has_rights (ACX_BILLING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('choose_list', 'addcredit', 'gen_id', 'cardnum', 'choose_currency', 'expirationdate', 'addcredit','tag_list'));



$HD_Form -> setDBHandler (DbConnect());

$HD_Form -> FG_FILTER_SEARCH_FORM = false;
$HD_Form -> FG_EDITION = false;
$HD_Form -> FG_DELETION = false;
$HD_Form -> FG_OTHER_BUTTON1 = false;
$HD_Form -> FG_OTHER_BUTTON2 = false;
$HD_Form -> FG_FILTER_APPLY = false;
$HD_Form -> FG_LIST_ADDING_BUTTON1 = false;
$HD_Form -> FG_LIST_ADDING_BUTTON2 = false;

$nbvoucher = $choose_list;

if ($nbvoucher>0) {
	
		check_demo_mode();
	
		$FG_ADITION_SECOND_ADD_TABLE  = "cc_voucher";		
		$FG_ADITION_SECOND_ADD_FIELDS = "voucher, credit, activated, tag, currency, expirationdate";
		$instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, $FG_ADITION_SECOND_ADD_FIELDS);
				
		$gen_id = time();
		$_SESSION["IDfilter"]=$tag_list;
		
		for ($k=0;$k < $nbvoucher;$k++){
			$vouchernum = generate_unique_value($FG_ADITION_SECOND_ADD_TABLE, LEN_VOUCHER, 'voucher');
			$FG_ADITION_SECOND_ADD_VALUE  = "'$vouchernum', '$addcredit', 't', '$tag_list', '$choose_currency', '$expirationdate'";
			
			$result_query = $instance_sub_table -> Add_table ($HD_Form -> DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null);
		}
}


if (!isset($_SESSION["IDfilter"])) $_SESSION["IDfilter"]='NODEFINED';
$HD_Form -> FG_TABLE_CLAUSE = "tag='".$_SESSION["IDfilter"]."'";


$HD_Form -> init();

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');
// #### HELP SECTION
echo $CC_help_generate_voucher;

?>
  	<div align="center">  	  
	   <table align="center" class="bgcolor_001" border="0" width="65%">
        <tbody><tr>
		<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <td align="left" width="75%">
           		
			  	<strong>1)</strong> 
				<select name="choose_list" size="1" class="form_input_select">
						<option value=""><?php echo gettext("Choose the number of vouchers to create");?></option>
						<option class="input" value="5"><?php echo gettext("5 Voucher");?></option>
						<option class="input" value="10"><?php echo gettext("10 Vouchers");?></option>
						<option class="input" value="50"><?php echo gettext("50 Vouchers");?></option>
						<option class="input" value="100"><?php echo gettext("100 Vouchers");?></option>
						<option class="input" value="200"><?php echo gettext("200 Vouchers");?></option>
						<option class="input" value="500"><?php echo gettext("500 Vouchers");?></option>
					</select>
					<br/>

			  	<strong>2)</strong>
				<?php echo gettext("Amount of credit");?> : 	<input class="form_input_text" name="addcredit" size="10" maxlength="10" >
				<br/>

				
				<strong>3)</strong> 
				<select NAME="choose_currency" size="1" class="form_input_select">
					<?php 
					foreach($currencies_list as $key => $cur_value) {											
				?>
					<option value='<?php echo $key ?>'><?php echo $cur_value[1].' ('.$cur_value[2].')' ?></option>
				<?php } ?>		
				   </select>
				<br/>
				
				
				<?php 
					$begin_date = date("Y");
					$begin_date_plus = date("Y")+10;	
					$end_date = date("-m-d H:i:s");
					$comp_date = "value='".$begin_date.$end_date."'";
					$comp_date_plus = "value='".$begin_date_plus.$end_date."'";
				?>
				<strong>4)</strong>
				<?php echo gettext("Expiration date");?> : <input class="form_input_text"  name="expirationdate" size="40" maxlength="40" <?php echo $comp_date_plus; ?>> <?php echo gettext("(respect the format YYYY-MM-DD HH:MM:SS)");?>
				<br/>
				<strong>5)</strong>
				<?php echo gettext("Tag");?> : <input class="form_input_text"  name="tag_list" size="40" maxlength="40" > 
			
							
		</td>	
		<td align="left" valign="bottom"> 
				<input class="form_input_button" value=" GENERATE VOUCHER " type="submit"> 
        </td>
		 </form>
        </tr>
      </tbody></table>
	  <br>
	</div>    

<?php

$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;


$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";


// #### FOOTER SECTION
$smarty->display('footer.tpl');

