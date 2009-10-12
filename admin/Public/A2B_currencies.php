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
include ("./form_data/FG_var_currencies.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_BILLING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('updatecurrency'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

/********************************* BATCH UPDATE CURRENCY TABLE ***********************************/
$A2B -> DBHandle = $HD_Form -> DBHandle;
if ($updatecurrency == 1) {
	
	check_demo_mode();
	
	$instance_table = new Table();
	$A2B -> set_instance_table ($instance_table);
	$return = currencies_update_yahoo($A2B -> DBHandle, $A2B -> instance_table);
	$update_msg = '<center><font color="green"><b>'.$return.'</b></font></center>';
}

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);	
}


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);



// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_currency;

?>
<div align="center">
<table align="center" border="0" width="65%"  cellspacing="1" cellpadding="2">
	<form name="updateForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
	<INPUT type="hidden" name="updatecurrency" value="1">
	<tr>
	  <td align="center"  class="bgcolor_001">
		&nbsp;<?php echo gettext("THE CURRENCY LIST IS BASED FROM YAHOO FINANCE"); ?>&nbsp;: 
			<input class="form_input_button"  value=" <?php echo gettext("CLICK HERE TO UPDATE NOW");?>  " type="submit">
		</td>
	</tr>
	</form>
</table>
</div>
<?php

if (isset($update_msg) && strlen($update_msg)>0) echo $update_msg; 

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');


