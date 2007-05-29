<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_voucher.inc");
include ("../lib/smarty.php");

	
if (! has_rights (ACX_BILLING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('choose_list', 'addcredit', 'gen_id', 'cardnum', 'choose_currency', 'expirationdate', 'addcredit'));



/***********************************************************************************/
$HD_Form -> setDBHandler (DbConnect());






$nbcard = $choose_list;

if ($nbcard>0){
		
		$FG_ADITION_SECOND_ADD_TABLE  = "cc_voucher";		
		//$FG_ADITION_SECOND_ADD_FIELDS = "username, useralias, credit, tariff, activated, lastname, firstname, email, address, city, state, country, zipcode, phone, userpass, simultaccess, currency, typepaid , creditlimit, enableexpire, expirationdate, expiredays";
		$FG_ADITION_SECOND_ADD_FIELDS = "voucher, credit, activated, tag, currency, expirationdate";
		$instance_sub_table = new Table($FG_ADITION_SECOND_ADD_TABLE, $FG_ADITION_SECOND_ADD_FIELDS);
				
		$gen_id = time();
		$_SESSION["IDfilter"]=$gen_id;
		
		
		$creditlimit = is_numeric($creditlimit) ? $creditlimit : 0;
		//echo "::> $choose_simultaccess, $choose_currency, $choose_typepaid, $creditlimit";
		for ($k=0;$k<$nbcard;$k++){
			$vouchernum = gen_card($FG_ADITION_SECOND_ADD_TABLE, LEN_VOUCHER, voucher);
			if (!is_numeric($addcredit)) $addcredit=0;
			$FG_ADITION_SECOND_ADD_VALUE  = "'$vouchernum', '$addcredit', 't', '$gen_id', '$choose_currency', '$expirationdate'";
			
			$result_query = $instance_sub_table -> Add_table ($HD_Form -> DBHandle, $FG_ADITION_SECOND_ADD_VALUE, null, null);			
		}

}


if (!isset($_SESSION["IDfilter"])) $_SESSION["IDfilter"]='NODEFINED';
$HD_Form -> FG_TABLE_CLAUSE = "tag='".$_SESSION["IDfilter"]."'";




$HD_Form -> init();


if ($id!="" || !is_null($id)){	
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
  	  	  
	   <table align="center" class="bgcolor_001" border="0" width="65%">
        <tbody><tr>
		<form name="theForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <td align="left" width="75%">
           
		   
		   		
			  	<strong>1)</strong> 
				<select name="choose_list" size="1" class="form_input_select">
						<option value=""><?php echo gettext("Choose the number of vouchers to create");?></option>
						<option class="input" value="1"><?php echo gettext("1 Voucher");?></option>
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
							
		</td>	
		<td align="left" valign="bottom"> 
		
				
				<input class="form_input_button" value=" GENERATE VOUCHER " type="submit"> 


          
        </td>
		 </form>
        </tr>
      </tbody></table>
	  <br>
	    

<?php


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


// #### CREATE FORM OR LIST
//$HD_Form -> CV_TOPVIEWER = "menu";
if (strlen($_GET["menu"])>0) $_SESSION["menu"] = $_GET["menu"];

$HD_Form -> create_form ($form_action, $list, $id=null) ;


$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR]= "SELECT ".$HD_Form -> FG_EXPORT_FIELD_LIST." FROM $HD_Form->FG_TABLE_NAME";
if (strlen($HD_Form->FG_TABLE_CLAUSE)>1) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR] .= " WHERE $HD_Form->FG_TABLE_CLAUSE ";
if (!is_null ($HD_Form->FG_ORDER) && ($HD_Form->FG_ORDER!='') && !is_null ($HD_Form->FG_SENS) && ($HD_Form->FG_SENS!='')) 
	$_SESSION[$HD_Form->FG_EXPORT_SESSION_VAR].= " ORDER BY $HD_Form->FG_ORDER $HD_Form->FG_SENS";




// #### FOOTER SECTION
$smarty->display('footer.tpl');


?>
