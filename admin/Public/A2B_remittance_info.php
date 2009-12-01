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
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_BILLING)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('id'));

if (empty($id)) {
	header("Location: A2B_entity_logrefill.php?atmenu=payment&section=10");
}

$DBHandle  = DbConnect();

$remittance_table = new Table('cc_remittance_request','*');
$remittance_clause = "id = ".$id;
$remittance_result = $remittance_table -> Get_list($DBHandle, $remittance_clause, 0);
$remittance = $remittance_result[0];

if (empty($remittance)) {
	header("Location: A2B_entity_remittance_request.php?atmenu=payment&section=10");
}

// #### HEADER SECTION
$smarty->display('main.tpl');

?>
<br/>
<br/>
<br/>
<table style="width : 80%;" class="editform_table1">
   <tr>
   		<th colspan="2" background="../Public/templates/default/images/background_cells.gif">
   			<?php echo gettext("REMITTANCE INFO") ?>
   		</th>	
   </tr>
   <tr height="20px">
		<td  class="form_head">
            <?php echo gettext("AGENT") ?> :
        </td>
        <td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
            <?php 
            if (has_rights (ACX_ADMINISTRATOR)) { 
                echo linktoagent($remittance['id_agent']);
            }else{
                echo nameofagent($remittance['id_agent']);
            }   
            ?>  
        </td>
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("AMOUNT") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $remittance['amount']." ".strtoupper(BASE_CURRENCY);?> 
		</td>
   </tr>
   	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("CREATION DATE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $remittance['date']?> 
		</td>
	</tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("REMITTANCE TYPE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php 
			$list_type = Constants::getRemittanceType_List();
			echo $list_type[$remittance['type']][0];?> 
		</td>
   </tr>
    <tr height="20px">
        <td  class="form_head">
            <?php echo gettext("REMITTANCE STATUS") ?> :
        </td>
        <td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
            <?php 
            $list_type = Constants::getRemittanceStatus_List();
            echo $list_type[$remittance['status']][0];?> 
        </td>
   </tr>
        					
 </table>
 <br/>
<div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
 	<a class="cssbutton_big"  href="A2B_entity_remittance_request.php?atmenu=payment&section=10">
		<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>
		<?php echo gettext("REMITTANCE LIST"); ?>
	</a>
</div>
<?php 

$smarty->display( 'footer.tpl');

