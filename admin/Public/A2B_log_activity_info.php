<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_ADMINISTRATOR)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('id'));

if (empty($id)) {
	header("Location: A2B_entity_log_viewer.php?section=16");
}


$DBHandle  = DbConnect();

$log_table = Table::getInstance('cc_system_log','*');
$log_clause = "id = ".$id;
$log_result = $log_table -> Get_list($DBHandle, $log_clause, 0);
$log = $log_result[0];

if (empty($log)) {
	header("Location: A2B_entity_log_viewer.php?section=16");
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
   			<?php echo gettext("LOG ACTIVITY INFO") ?>
   		</th>	
   </tr>
   <tr height="20px">
		<td  class="form_head" width="30%">
			<?php echo gettext("ID") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php  echo $log['id'];?> 
		</td>
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("USER") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo nameofadmin($log['iduser']);?> 
		</td>
   </tr>
   	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("LOG-LEVEL") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['loglevel']?> 
		</td>
	</tr>
		<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("ACTION") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['action']?> 
		</td>
	</tr>
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("DESCRIPTION") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['description']?> 
		</td>
	</tr>
   	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("TABLENAME") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['tablename']?> 
		</td>
	</tr>	
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("IPADDRESS") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['ipaddress']?> 
		</td>
	</tr>
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("CREATION DATE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['creationdate']?> 
		</td>
	</tr>
	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("DATA") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $log['data']?> 
		</td>
	</tr>		
 </table>
 <br/>
<div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
 	<a class="cssbutton_big"  href="A2B_entity_log_viewer.php?section=16">
		<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>
		<?php echo gettext("LOG LIST"); ?>
	</a>
</div>
<?php 

$smarty->display( 'footer.tpl');

