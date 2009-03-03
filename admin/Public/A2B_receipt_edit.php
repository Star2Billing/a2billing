<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
include ("../lib/support/classes/receipt.php");
include ("../lib/support/classes/receiptItem.php");

if (! has_rights (ACX_INVOICING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('id','action','price','description','idc'));

if (empty($id)) {
	Header ("Location: A2B_entity_receipt.php?atmenu=payment&section=13");
}

if (!empty($action)) {
	switch ($action) {
		case 'add':
			$DBHandle = DbConnect();
			$receipt = new Receipt($id);
			$receipt->insertReceiptItem($description,$price);
			Header ("Location: A2B_receipt_edit.php?"."id=".$id);
			break;
		case 'edit':
	 		if(!empty($idc) && is_numeric($idc)){
				$DBHandle = DbConnect();
				$instance_sub_table = new Table("cc_receipt_item", "*");
				$result=$instance_sub_table -> Get_list($DBHandle, "id = $idc" );
				if(!is_array($result) || (sizeof($result)==0)){
					 Header ("Location: A2B_receipt_edit.php?"."id=".$id);
				}else{
					$description=$result[0]['description'];
					$price=$result[0]['price'];
					$date =$result[0]['date'];
				}
	 		}
			break;
		case 'delete':
			if(!empty($idc) && is_numeric($idc)){
				$DBHandle  = DbConnect();
				$instance_sub_table = new Table("cc_receipt_item", "*");
				$instance_sub_table -> Delete_Selected($DBHandle, "id = $idc" );
			}
			Header ("Location: A2B_receipt_edit.php?"."id=".$id);
			break;
	}
}


$receipt = new Receipt($id);
$items = $receipt->loadItems();


$smarty->display('main.tpl');

?>
<table class="invoice_table" >
	<tr class="form_invoice_head">
	    <td width="75%"><font color="#FFFFFF"><?php echo gettext("RECEIPT: "); ?></font><font color="#FFFFFF"><b><?php echo $receipt->getTitle();  ?></b></font></td>
	</tr>
	<tr>
		<td>
		&nbsp;
		</td>
	</tr>
	<tr>
		<td >
		 <font style="font-weight:bold; " ><?php echo gettext("FOR : "); ?></font>  <?php echo $receipt->getUsernames();  ?>

		</td>
		<td>
		<font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?></font>  <?php echo $receipt->getDate();  ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<br/>
		<font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?></font>  <br/> <?php echo $receipt->getDescription();  ?></td>
	</tr>
	
	<tr >
    <td colspan="2">
    	<table width="100%" cellspacing="10">
			<tr>
			  <th  width="20%">
			      &nbsp;
			  </th>
			  <th  width="50%">
			  	&nbsp;
			  </th>
			  <th align="right" width="20%">
			  	<font style="font-weight:bold; " >     
			  		<?php echo gettext("PRICE"); ?>
			  	</font>
			  </th>
			  <th  width="10%">
			  &nbsp;
			  </th>
			</tr> 
			
			<?php foreach ($items as $item){ ?>
			<tr style="vertical-align:top;" >
				<td>
					<?php echo $item->getDate(); ?>
				</td>
				<td >
					<?php echo $item->getDescription(); ?>
				</td>
				<td align="right">
					<?php echo number_format(round($item->getPrice(),2),2)." ".strtoupper(BASE_CURRENCY); ?>
				</td>
				<td align="center">
					<a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=edit&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/edit.png" title="<?php echo gettext("Edit Item") ?>" alt="<?php echo gettext("Edit Item") ?>" border="0"></a>
					<a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=delete&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/delete.png" title="<?php echo gettext("Delete Item") ?>" alt="<?php echo gettext("Delete Item") ?>" border="0"></a>
				</td>
			</tr>  
			 <?php } ?>	
			 
			 
			<tr>
	    	 	<td colspan="4">
	    	 		&nbsp;
	    	 	</td>
    	 	</tr>	 
    	<?php
		$price = 0;
    	foreach ($items as $item){  
    	 	$price = $price + $item->getPrice();
    	 } 
    	
    	 ?>
    	 	<tr>
	    	 	<td >
	    	 		&nbsp;
	    	 	</td>
	    	 	<td  align="right">
	    	 		<?php echo gettext("TOTAL") ?>&nbsp;:
	    	 	</td>
	    	 	<td align="right" >
	    	 		<?php echo money_format('%.2n',round($price,2)); ?>
	    	 	</td>
	    	 	<td >
	    	 		&nbsp;
	    	 	</td>
    	 	</tr>
    	</table>
	</td>
	</tr>
</table>
<br/>

  <form action="<?php echo $PHP_SELF.'?id='.$receipt->getId(); ?>" method="post" >
 	<input id="action" type="hidden" name="action" value="<?php if(!empty($idc)) echo "edit"; else echo "add" ?>"/>
	<input id="idc" type="hidden" name="idc" value="<?php if(!empty($idc)) echo $idc;?>"/>
	<table class="invoice_table">
		<tr class="form_invoice_head">
	    	<td colspan="2" align="center"><font color="#FFFFFF"><?php echo gettext("ADD RECEIPT ITEM "); ?></font></td>
		</tr>
		<tr >
	    	<td colspan="2">&nbsp;</td>
		</tr>
		<?php
			if(empty($date)){
				$date = date("Y-m-d H:i:s");
			}
		?>
		<tr>
			<td ><font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?>
			 </td>
			 <td>
			 <input type="text" class="form_input_text" name="price" size="20" maxlength="20" <?php if(!empty($date)) echo 'value="'.$date.'"';?>/>
			 </td>
		</tr>
		<tr>
			<td ><font style="font-weight:bold; " ><?php echo gettext("PRICE : "); ?>
			 </td>
			 <td>
			 <input type="text" class="form_input_text" name="price" size="10" maxlength="10" <?php if(!empty($price)) echo 'value="'.$price.'"';?>/>
			 </td>
		</tr>
		<tr>
			<td ><font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?>
			 </td>
			<td>
			 <textarea class="form_input_textarea" name="description" cols="50" rows="5"><?php if(!empty($description)) echo $description ;?></textarea>
			 </td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input class="form_input_button" type="submit" value="<?php if(!empty($idc)) echo gettext("UPDATE"); else echo gettext("ADD"); ?>"/>
			 </td>
		</tr>

	</table>
  </form>

