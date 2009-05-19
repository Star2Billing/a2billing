<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_ADMINISTRATOR)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('id','groupID'));

if(!is_numeric($groupID) || ($groupID != 0 && $groupID != 1)) $groupID =0;

if (empty($id)) {
	header("Location: A2B_entity_user.php?atmenu=user&groupID=$groupID&section=3");
}

$DBHandle  = DbConnect();

$admin_table = new Table('cc_ui_authen','*');
$admin_clause = "userid = ".$id;
$admin_result = $admin_table -> Get_list($DBHandle, $admin_clause, 0);
$admin = $admin_result[0];
print_r($admin);

if (empty($admin)) {
	header("Location: A2B_entity_user.php?atmenu=user&groupID=$groupID&section=3");
}

// #### HEADER SECTION
$smarty->display('main.tpl');
$lg_liste= Constants::getLanguages();
?>
<br/>
<br/>
<br/>
<table style="width : 80%;" class="editform_table1">
   <tr>
   		<th colspan="2" background="../Public/templates/default/images/background_cells.gif">
   			<?php echo gettext("ADMIN INFO") ?>
   		</th>	
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("LOGIN") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['login']?> 
		</td>
	</tr>
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("NAME") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['name']?> 
		</td>
	</tr>
	
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("ADDRESS") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['direction']?> 
		</td>
		
	</tr>
	
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("ZIP CODE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['zipcode']?> 
		</td>
	</tr>
	
	<tr  height="20px">
		<td  class="form_head">
			<?php echo gettext("CITY") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['city']?> 
		</td>
		
	</tr>
	
	<tr  height="20px">
		<td  class="form_head">
			<?php echo gettext("STATE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['state']?> 
		</td>
		
	</tr>
	
	<tr  height="20px">
		<td  class="form_head">
			<?php echo gettext("COUNTRY") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['country']?> 
		</td>
		
	</tr>
	<tr  height="20px">
		<td  class="form_head">
			<?php echo gettext("EMAIL") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['email']?> 
		</td>
		
	</tr>
	<tr  height="20px">
		<td  class="form_head">
			<?php echo gettext("PHONE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['phone']?> 
		</td>
	</tr>
	<tr  height="20px">
		<td  class="form_head">
			<?php echo gettext("FAX") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			&nbsp;<?php echo $admin['fax']?> 
		</td>
	</tr>
	
 </table>
 <br/>
<div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
 	<a class="cssbutton_big"  href="<?php echo "A2B_entity_user.php?atmenu=user&groupID=$groupID&section=3" ?>">
		<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>
		<?php echo gettext("AGENT LIST"); ?>
	</a>
</div>
<?php 

$smarty->display( 'footer.tpl');

