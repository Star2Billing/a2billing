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
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_INVOICING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


/***********************************************************************************/

$DBHandle  = DbConnect();
if($form_action=="ask-update") {
	
	getpost_ifset(array('company_name','address','zipcode','country','city','phone','fax','email','vat','web','display_account'));
	
	$table_invoice_conf= new Table("cc_invoice_conf");
	$param_update_conf = "value ='".$company_name."'";
	$clause_update_conf = "key_val = 'company_name'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);

	$param_update_conf = "value ='".$address."'";
	$clause_update_conf = "key_val = 'address'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$zipcode."'";
	$clause_update_conf = "key_val = 'zipcode'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$country."'";
	$clause_update_conf = "key_val = 'country'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$city."'";
	$clause_update_conf = "key_val = 'city'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$phone."'";
	$clause_update_conf = "key_val = 'phone'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$fax."'";
	$clause_update_conf = "key_val = 'fax'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$phone."'";
	$clause_update_conf = "key_val = 'phone'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$email."'";
	$clause_update_conf = "key_val = 'email'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$vat."'";
	$clause_update_conf = "key_val = 'vat'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$web."'";
	$clause_update_conf = "key_val = 'web'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
	$param_update_conf = "value ='".$display_account."'";
	$clause_update_conf = "key_val = 'display_account'";
	$table_invoice_conf -> Update_table ($DBHandle, $param_update_conf, $clause_update_conf, $func_table = null);
	
}

// #### HEADER SECTION
$smarty->display( 'main.tpl');


$table_invoice_conf= new Table("cc_invoice_conf","value");
$clause_update_conf = "key_val = 'company_name'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$company_name=$result[0][0];

$clause_update_conf = "key_val = 'address'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$address=$result[0][0];

$clause_update_conf = "key_val = 'zipcode'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$zipcode=$result[0][0];

$clause_update_conf = "key_val = 'country'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$country=$result[0][0];

$clause_update_conf = "key_val = 'city'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$city=$result[0][0];

$clause_update_conf = "key_val = 'phone'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$phone=$result[0][0];

$clause_update_conf = "key_val = 'fax'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$fax=$result[0][0];

$clause_update_conf = "key_val = 'phone'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$phone=$result[0][0];

$clause_update_conf = "key_val = 'email'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$email=$result[0][0];

$clause_update_conf = "key_val = 'vat'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$vat=$result[0][0];

$clause_update_conf = "key_val = 'web'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$web=$result[0][0];

$clause_update_conf = "key_val = 'display_account'";
$result=$table_invoice_conf -> Get_list($DBHandle, $clause_update_conf);
$display_account=$result[0][0];

?>

<br>
<form method="post" action="<?php  echo $_SERVER["PHP_SELF"]."?form_action=ask-update"?>" name="frmPass">
<table width="100%">
	<tr>
		<td align="center" valign="middle">
			<?php echo gettext("Here you can configure information that you want to use to generate the invoice") ?>
		</td>
	</tr>	
</table>
<br/>
<table class="editform_table1" cellspacing="2">
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Company Name")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="company_name" type="text" class="form_input_text" <?php if(!empty($company_name)) echo 'value="'.$company_name.'"';?> > <br/>
    	<?php echo gettext("Insert your company name"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Address")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="address" type="text" class="form_input_text" <?php if(!empty($address)) echo 'value="'.$address.'"';?> > <br/>
    	<?php echo gettext("Insert your address"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Zip Code")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="zipcode" type="text" class="form_input_text" <?php if(!empty($zipcode)) echo 'value="'.$zipcode.'"';?> > <br/>
    	<?php echo gettext("Insert your zip code"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("City")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="city" type="text" class="form_input_text" <?php if(!empty($city)) echo 'value="'.$city.'"';?> > <br/>
    	<?php echo gettext("Insert your city"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Phone number")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="phone" type="text" class="form_input_text" <?php if(!empty($phone)) echo 'value="'.$phone.'"';?> > <br/>
    	<?php echo gettext("Insert your phone number"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Fax number")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="fax" type="text" class="form_input_text" <?php if(!empty($fax)) echo 'value="'.$fax.'"';?> > <br/>
    	<?php echo gettext("Insert your fax number"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Email")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="email" type="text" class="form_input_text" <?php if(!empty($email)) echo 'value="'.$email.'"';?> > <br/>
    	<?php echo gettext("Insert your email"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Web Site")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="web" type="text" class="form_input_text" <?php if(!empty($web)) echo 'value="'.$web.'"';?> > <br/>
    	<?php echo gettext("Insert your Web site"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("VAT number")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
    	<input name="vat" type="text" class="form_input_text" <?php if(!empty($vat)) echo 'value="'.$vat.'"';?> > <br/>
    	<?php echo gettext("Insert your vat number"); ?>
    </td>
</tr>
<tr>
    <td class="form_head" width="25%" valign="middle">
    	<?php echo gettext("Display Account number")?>&nbsp; :
    </td>
    <td class="tableBodyRight" width="75%" valign="top" background="../Public/templates/default/images/background_cells.gif">
        <select name="display_account">
        	<option value="1" <?php if($display_account==1) echo "selected"; ?> > <?php echo gettext("YES")?></option>
        	<option value="0" <?php if($display_account==0) echo "selected"; ?> ><?php echo gettext("NO")?></option>
        </select>
    	<?php echo gettext("Choose if you want display the account number on the invoices"); ?>
    </td>
</tr>

<tr>
    <td align=right colspan=2 ><input type="submit" name="submitPassword" value="&nbsp;<?php echo gettext("Save")?>&nbsp;" class="form_input_button" onclick="return CheckPassword();" ></td>
</tr>

</table>
</form>
<br>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
