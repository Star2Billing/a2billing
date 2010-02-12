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
include ("../lib/config_functions.php");
include ("./form_data/FG_var_config.inc");
include ("../lib/admin.smarty.php");


if (!has_rights (ACX_ACXSETTING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();


if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}


if (!isset($form_action))  $form_action="list";
if (!isset($action)) $action = $form_action;

if($form_action != "list")
	check_demo_mode();

$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_list_configuration;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

if($form_action == "list") {

?>
<br>
<script language="javascript">
function go(URL)
{
	if ( Check() )
	{
		document.searchform.action = URL;		
		alert(document.searchform.action);
		document.searchform.submit();
	}
}	

function Check()
{
	if(document.searchform.filterradio[1].value == "payment")	
	{
		if (document.searchform.paymenttext.value < 0)
		{
			alert("Payment amount cannot be less than Zero.");
			document.searchform.paymenttext.focus();
			return false;
		}
	}	
	return true;
}
</script>
<form name="searchform" id="searchform" method="post" action="A2B_entity_config.php">
	<input type="hidden" name="searchenabled" value="yes">
	<input type="hidden" name="posted" value="1">
	
	<table class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
	
		<tr>
			<td width="19%" align="left" valign="top" class="bgcolor_004">
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("VALUE");?></font>
			</td>
			
			<td align="left"  class="bgcolor_003">
			<table>
				<tr>
					<td width="25%" align="left" valign="top">
						<input class="form_input_text" name="filterValue" size="20">
					</td>
					
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbValue" value="1" checked> <?php echo gettext("Exact");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbValue" value="2"> <?php echo gettext("Begins with");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbValue" value="3"> <?php echo gettext("Contains");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbValue" value="4"> <?php echo gettext("Ends with");?>
						</font>
					</td>
				</td>
			</table>
			</td>
		</tr>

		<tr>
			<td width="19%" align="left" valign="top" class="bgcolor_004">					
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("KEY");?></font>
			</td>
			
			<td align="left"  class="bgcolor_005">
			<table>
				<tr>
					<td width="25%" align="left" valign="top">
						<input class="form_input_text" name="filterKey" size="20">
					</td>

					
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbKey" value="1" checked> <?php echo gettext("Exact");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbKey" value="2"> <?php echo gettext("Begins with");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbKey" value="3"> <?php echo gettext("Contains");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbKey" value="4"> <?php echo gettext("Ends with");?>
						</font>
					</td>
				</td>
			</table>
			</td>
		</tr>

		<tr>
			<td width="19%" align="left" valign="top" class="bgcolor_004">
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("DESCRIPTION");?></font>
			</td>
			
			<td align="left"  class="bgcolor_003">
			<table>
				<tr>
					<td width="25%" align="left" valign="top">
						<input class="form_input_text" name="filterDescription" size="20">
					</td>
					
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbDesc" value="1" checked> <?php echo gettext("Exact");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbDesc" value="2"> <?php echo gettext("Begins with");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbDesc" value="3"> <?php echo gettext("Contains");?>
						</font>
					</td>
					<td width="10%">
						<font class="version">
						<input type="radio" NAME="rbDesc" value="4"> <?php echo gettext("Ends with");?>
						</font>
					</td>
				</td>
			</table>
			</td>
		</tr>


		<tr>
			<td width="19%" align="left" valign="top" class="bgcolor_004">					
				<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SELECT GROUP");?></font>
			</td>				
			<td width="81%" align="left" class="bgcolor_005">
			<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
			  <td class="fontstyle_searchoptions">
			  <?php
				$instance_table = new Table();
				$QUERY = "SELECT * from cc_config_group"; 					
				$list_total_groups  = $instance_table->SQLExec ($HD_Form -> DBHandle, $QUERY);		
			   ?>
			<select name="groupselect" class="form_input_select">
			<option value="-1" ><?php echo gettext("Select Group");?></option>
			<?php 
			foreach($list_total_groups as $groupname){
			?>
			<option value="<?php echo $groupname[1]?>" <?php if($groupselect == $groupname[1] || $groupname[1] == $_SESSION['grpselect']) echo "selected"?>><?php echo $groupname[1]?></option>
			<?php 
			}
			?>
			</select>
				</td>					
			</tr></table></td>
		</tr>			
	
		<tr>
			<td class="bgcolor_002" align="left">&nbsp;</td>
			<td class="bgcolor_003" align="left">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td class="fontstyle_searchoptions">					<div align="center"><span class="bgcolor_005">
			      <input type="image"  name="image16" align="left" border="0" src="<?php echo Images_Path;?>/button-search.gif" />
			        </span> </div></td>
				</tr>
				</table>
			</td>
		</tr>
	</table>
</FORM>
</center>

<?php 
}

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');


