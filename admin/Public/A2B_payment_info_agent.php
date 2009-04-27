<?php
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
	header("Location: A2B_entity_payment_agent.php?atmenu=payment&section=10");
}


$DBHandle  = DbConnect();

$payment_table = Table::getInstance('cc_logpayment_agent','*');
$payment_clause = "id = ".$id;
$payment_result = $payment_table -> Get_list($DBHandle, $payment_clause, 0);
$payment = $payment_result[0];

if (empty($payment)) {
	header("Location: A2B_entity_payment_agent.php?atmenu=payment&section=10");
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
   			<?php echo gettext("PAYMENT INFO") ?>
   		</th>	
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("AGENT") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php 
			if (has_rights (ACX_ADMINISTRATOR)) { 
				echo linktoagent($payment['agent_id']);
			}else{
				echo nameofagent($payment['agent_id']);
			}	
			?> 
		</td>
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("AMOUNT") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $payment['payment']." ".strtoupper(BASE_CURRENCY);?> 
		</td>
   </tr>
   	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("CREATION DATE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $payment['date']?> 
		</td>
	</tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("PAYMENT TYPE") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php 
			$list_type = Constants::getRefillType_List();
			echo $list_type[$payment['payment_type']][0];?> 
		</td>
   </tr>
   <tr height="20px">
		<td  class="form_head">
			<?php echo gettext("DESCRIPTION ") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<?php echo $payment['description']?> 
		</td>
	</tr>
   	<?php if(!empty($payment['id_logrefill'])){ ?>
   	<tr height="20px">
		<td  class="form_head">
			<?php echo gettext("LINK REFILL") ?> :
		</td>
		<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
			<a href="A2B_refill_info_agent.php?id=<?php echo $payment['id_logrefill']?>"> <img src="<?php echo Images_Path."/link.png"?>" border="0" title="<?php echo gettext("Link to the refill")?>" alt="<?php echo  gettext("Link to the refill")?>"></a>
		</td>
	</tr>
   	<?php } ?>
   					
 </table>
 <br/>
<div style="width : 80%; text-align : right; margin-left:auto;margin-right:auto;" >
 	<a class="cssbutton_big"  href="A2B_entity_payment_agent.php?atmenu=payment&section=10">
		<img src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif"/>
		<?php echo gettext("PAYMENTS AGENT LIST"); ?>
	</a>
</div>
<?php 

$smarty->display( 'footer.tpl');

