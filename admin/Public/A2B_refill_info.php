<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_CUSTOMER)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('id'));

if (empty($id)) {
	header("Location: A2B_entity_logrefill.php?atmenu=payment&section=10");
}

$DBHandle  = DbConnect();

$refill_table = new Table('cc_logrefill','*');
$refill_clause = "id = ".$id;
$refill_result = $refill_table -> Get_list($DBHandle, $refill_clause, 0);
$refill = $refill_result[0];

if (empty($refill)) {
	header("Location: A2B_entity_logrefill.php?atmenu=payment&section=10");
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
   			<?php echo gettext("REFILL INFO") ?>
   		</th>	
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("ACCOUNT NUMBER") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo linktocustomer_id($refill['card_id']);?> 
		</td>
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("AMOUNT") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $refill['credit']." ".strtoupper(BASE_CURRENCY);?> 
		</td>
   </tr>
   	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("CREATION DATE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $refill['date']?> 
		</td>
	</tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("REFILL TYPE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php 
			$list_type = Constants::getRefillType_List();
			echo $list_type[$refill['refill_type']][0];?> 
		</td>
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("DESCRIPTION ") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $refill['description']?> 
		</td>
	</tr>
   					
 </table>
 <br/>
<div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
 	<a class="cssbutton_big"  href="A2B_entity_logrefill.php?atmenu=payment&section=10">
		<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>
		<?php echo gettext("REFILLS LIST"); ?>
	</a>
</div>
<?php 

$smarty->display( 'footer.tpl');

