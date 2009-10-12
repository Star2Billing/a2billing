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
include ("../lib/config_functions.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_config.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_ACXSETTING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
if ($form_action=='list') echo $CC_help_add_agi_confx;
else echo $CC_help_add_agi_confx;


// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);


$link = "A2B_entity_config.php?form_action=list&atmenu=config&stitle=Configuration&section=8&agi_conf=true";

$config_group = array();
$config_group  = agi_confx_title(); // calling function  to generate agi-conf(title_number)
$group_title = $config_group[0];
$group_description = $config_group[2];

?>
<table width="92%" align="center" class="bar-status">
	<tr>
		<td>
			<table width="100%" style="border:1px solid">
			<thead>
				<tr>
					<td colspan="2"  class="bgcolor_005"><font style="color:#FFFFFF;padding-left:3px"><strong><?php echo gettext("Group Configurations");?></strong></font></td>
				</tr>
			</thead>
			<tbody>
				<tr class="form_head">
					<th class="tableBody" style="padding: 2px;" align="center"><?php echo gettext("Title")?></th>
					<th class="tableBody" style="padding: 2px;" align="center"><?php echo gettext("Description")?></th>
				</tr>
				<tr bgcolor="#FCFBFB"  onmouseover="bgColor='#FFDEA6'" onMouseOut="bgColor='#FCFBFB'">
					<td class="tableBody"><?php echo $group_title?></td>
					<td class="tableBody"><?php echo $group_description?></td>
				</tr>
			</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td width="70%">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" style="border:1px solid">
			<thead>
				<tr>
					<td colspan="5" class="bgcolor_005"><font style="color:#FFFFFF;padding-left:3px"><strong><?php echo gettext("List of Configurations - We will create base on ")." AGI-CONF".$config_group[3]?> </strong></font></td>
				</tr>
			</thead>
			<tbody>
				<tr class="form_head">
					<th align="center" width="15%" class="tableBody" style="padding: 2px;" ><?php echo gettext("Title")?></th>
					<th class="tableBody" style="padding: 2px;" align="center" width="10%"><?php echo gettext("Key")?></th>
					<th class="tableBody" style="padding: 2px;" align="center" width="10%"><?php echo gettext("Value")?></th>
					<th class="tableBody" style="padding: 2px;" align="center" width="50%"><?php echo gettext("Description")?></th>
					<th class="tableBody" style="padding: 2px;" align="center" width="5%"><?php echo gettext("Group")?></th>
				</tr>
<?php
$instance_table = new Table();
$QUERY = "SELECT config_title, config_key, config_value, config_description FROM cc_config WHERE config_group_title = 'agi-conf".$config_group[3]."' ORDER BY id LIMIT 10"; 					
$config  = $instance_table->SQLExec ($HD_Form -> DBHandle, $QUERY);	

$i=0;
foreach($config as $values) {
	$config_title = $values[0]; 
	$config_key = $values[1]; 
	$config_value = $values[2]; 
	$config_description = $values[3]; 
	if($i % 2 == 0) {
		$bgcolor = "bgColor='#FCFBFB'"; 
	} else {
		$bgcolor = "bgColor='#F2F2EE'";	
	}
?>				
				<tr <?php echo $bgcolor?> onmouseover="bgColor='#FFDEA6'" onMouseOut="<?php echo $bgcolor?>">
					<td align="left" class="tableBody"><?php echo $config_title?></td>
					<td align="left" class="tableBody"><?php echo $config_key?></td>
					<td align="left" class="tableBody"><?php echo $config_value?></td>
					<td align="left" class="tableBody"><?php echo $config_description?></td>
					<td align="left" class="tableBody"><?php echo $group_title?></td>
				</tr>
<?php 
	$i++;
}
?>				
			</tbody>
			</table>
		</td>
	</tr>
	<br>
	<?php 
	$text = gettext("CREATE");
	$group_title = $text." ".strtoupper($group_title);
	?>
	<tr>
		<td align="right">
		<form name="theform">
		<input class="form_input_button" 
				TYPE="button" VALUE="<?php echo $group_title;?>" onClick="window.open('<?php echo $link?>')">
		</form></td>
	</tr>
</table>

<?php

// #### FOOTER SECTION
$smarty->display('footer.tpl');

