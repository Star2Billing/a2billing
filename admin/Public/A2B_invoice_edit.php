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
include ("../lib/support/classes/invoice.php");
include ("../lib/support/classes/invoiceItem.php");

if (! has_rights (ACX_INVOICING)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('date','id','action','price','description','vat','idc'));

if (empty($id)) {
	Header ("Location: A2B_entity_invoice.php?atmenu=payment&section=13");
}

$error_msg ='';
if (!empty($action)) {
	switch ($action) {
		case 'add':
			if(empty($date) || strtotime($date)===FALSE){
				$error_msg.= gettext("Date inserted is invalid, it must respect a date format YYYY-MM-DD HH:MM:SS (time is optional).<br/>");
			}
			if( !is_numeric($vat)){
				$error_msg.= gettext("VAT inserted is invalid, it must be a number. Check the format.<br/>");
			}
			if(empty($price) || !is_numeric($price)){
				$error_msg .= gettext("Amount inserted is invalid, it must be a number. Check the format.");
			}
			if(!empty($error_msg)) break;
			$DBHandle = DbConnect();
			$invoice = new Invoice($id);
			$invoice->insertInvoiceItem($description,$price,$vat);
			Header ("Location: A2B_invoice_edit.php?"."id=".$id);
			break;
		case 'edit':
	 		if(!empty($idc) && is_numeric($idc)){
				$DBHandle = DbConnect();
				$instance_sub_table = new Table("cc_invoice_item", "*");
				$result=$instance_sub_table -> Get_list($DBHandle, "id = $idc" );
				if(!is_array($result) || (sizeof($result)==0)){
					 Header ("Location: A2B_invoice_edit.php?"."id=".$id);
				}else{
					$description=$result[0]['description'];
					$vat=$result[0]['VAT'];
					$price=$result[0]['price'];
					$date =$result[0]['date'];
				}
	 		}
			break;
		case 'delete':
			if(!empty($idc) && is_numeric($idc)){
				$DBHandle  = DbConnect();
				$instance_sub_table = new Table("cc_invoice_item", "*");
				$instance_sub_table -> Delete_Selected($DBHandle, "id = $idc" );
			}
			Header ("Location: A2B_invoice_edit.php?"."id=".$id);
			break;
			
	   	case 'update':
			if(!empty($idc) && is_numeric($idc)){
				if(empty($date) || strtotime($date)===FALSE){
					$error_msg.= gettext("Date inserted is invalid, it must respect a date format YYYY-MM-DD HH:MM:SS (time is optional).<br/>");
				}
				if( !is_numeric($vat)){
					$error_msg.= gettext("VAT inserted is invalid, it must be a number. Check the format.<br/>");
				}
				if(empty($price) || !is_numeric($price)){
					$error_msg .= gettext("Amount inserted is invalid, it must be a number. Check the format.");
				}
				if(!empty($error_msg)) break;
				$DBHandle = DbConnect();
				$instance_sub_table = new Table("cc_invoice_item", "*");
				$instance_sub_table -> Update_table($DBHandle,"date='$date',description='$description',price='$price',vat='$vat'", "id = $idc" );
				Header ("Location: A2B_invoice_edit.php?"."id=".$id);
				
	 		}
			break;
	}
}


$invoice = new invoice($id);
$table_card = new Table("cc_card","vat");
$result_vat = $table_card->Get_list(DbConnect(),"id=".$invoice->getCard());
$card_vat =  $result_vat[0][0];
$items = $invoice->loadItems();


$smarty->display('main.tpl');

?>
<table class="invoice_table" >
	<tr class="form_invoice_head">
	    <td width="75%"><font color="#FFFFFF"><?php echo gettext("INVOICE: "); ?></font><font color="#FFFFFF"><b><?php echo $invoice->getTitle();  ?></b></font></td>
	    <td width="25%"><font color="#FFFFFF"><?php echo gettext("REF: "); ?> </font><font color="#EE6564"> <?php echo $invoice->getReference(); ?></font></td>
	</tr>
	<tr>
		<td>
		&nbsp;
		</td>
	</tr>
	<tr>
		<td >
		 <font style="font-weight:bold; " ><?php echo gettext("FOR : "); ?></font>  <?php echo $invoice->getUsernames();  ?>

		</td>
		<td>
		<font style="font-weight:bold; " ><?php echo gettext("DATE : "); ?></font>  <?php echo $invoice->getDate();  ?>
		</td>
	</tr>
	<tr>
		<td>
		 <?php if($invoice->getStatusDisplay()==0) $color="color:#5FA631;";
		 	   else $color="color:#EE6564;"    ?>
		 <font style="font-weight:bold;" ><?php echo gettext("STATUS : "); ?></font> <font style="<?php echo $color; ?>" >  <?php echo $invoice->getStatusDisplay($invoice->getStatus());  ?> </font>
		 </td>
	</tr>
	<tr>
		<td colspan="2">
		<?php if($invoice->getPaidStatusDisplay()==0) $color="color:#EE6564;";
		 	   else $color="color:#5FA631;"    ?>
		 <font style="font-weight:bold;" ><?php echo gettext("PAID STATUS : "); ?></font> <font style="<?php echo $color; ?>" > <?php echo $invoice->getPaidStatusDisplay($invoice->getPaidStatus());  ?> </font>

		</td>
	</tr>
	<tr>
		<td colspan="2">
		<br/>
		<font style="font-weight:bold; " ><?php echo gettext("DESCRIPTION : "); ?></font>  <br/> <?php echo $invoice->getDescription();  ?></td>
	</tr>
	
	<tr >
    <td colspan="2">
    	<table width="100%" cellspacing="10">
			<tr>
			  <th  width="10%">
			      &nbsp;
			  </th>
			  <th  width="35%">
			  	&nbsp;
			  </th>
			  <th align="right" width="17%">
			  	<font style="font-weight:bold; " >     
			  		<?php echo gettext("PRICE EXCL. VAT"); ?>
			  	</font>
			  </th>
			  <th align="right" width="10%">
			  	<font style="font-weight:bold; " >     
			  		<?php echo gettext("VAT"); ?>
			  	</font>
			  </th>
			   <th align="right" width="17%">
			  	<font style="font-weight:bold; " >     
			  		<?php echo gettext("PRICE INCL. VAT"); ?>
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
				<td align="right">
					<?php echo number_format(round($item->getVAT(),2),2)." %" ?>
				</td>
				<td align="right">
					<?php echo number_format(round($item->getPrice()*(1+($item->getVAT()/100)),2),2)." ".strtoupper(BASE_CURRENCY); ?>
				</td>
				<td align="center">
					<a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=edit&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/edit.png" title="<?php echo gettext("Edit Item") ?>" alt="<?php echo gettext("Edit Item") ?>" border="0"></a>
					<a href="<?php echo $PHP_SELF ?>?id=<?php echo $id; ?>&action=delete&idc=<?php echo $item->getId();?>"><img src="<?php echo Images_Path ?>/delete.png" title="<?php echo gettext("Delete Item") ?>" alt="<?php echo gettext("Delete Item") ?>" border="0"></a>
				</td>
			</tr>  
			 <?php } ?>	
			 
			 
			<tr>
	    	 	<td colspan="6">
	    	 		&nbsp;
	    	 	</td>
    	 	</tr>	 
    	<?php
		$price_without_vat = 0;
		$price_with_vat = 0;
		$vat_array = array();
    	foreach ($items as $item){  
    	 	$price_without_vat = $price_without_vat + $item->getPrice();
    		$price_with_vat = $price_with_vat + ($item->getPrice()*(1+($item->getVAT()/100)));
    		if(array_key_exists("".$item->getVAT(),$vat_array)){
    			$vat_array[$item->getVAT()] = $vat_array[$item->getVAT()] + $item->getPrice()*($item->getVAT()/100) ;
    		}else{
    			$vat_array[$item->getVAT()] =  $item->getPrice()*($item->getVAT()/100) ;
    		}
    	 } 
    	
    	 ?>
    	 	<tr>
	    	 	<td colspan="2">
	    	 		&nbsp;
	    	 	</td>
	    	 	<td colspan="2" align="right">
	    	 		<?php echo gettext("TOTAL EXCL. VAT") ?>&nbsp;:
	    	 	</td>
	    	 	<td align="right" >
	    	 		<?php echo number_format(round($price_without_vat,2),2)." ".strtoupper(BASE_CURRENCY); ?>
	    	 	</td>
	    	 	<td >
	    	 		&nbsp;
	    	 	</td>
    	 	</tr>
    	 	<?php foreach ($vat_array as $key => $val) { ?>
    	 		
    	 	<tr>
	    	 	<td colspan="2">
	    	 		&nbsp;
	    	 	</td>
	    	 	<td colspan="2" align="right">
	    	 		<?php echo gettext("TOTAL VAT ($key%)") ?>&nbsp;:
	    	 	</td>
	    	 	<td align="right" >
	    	 		<?php echo number_format(round($val,2),2)." ".strtoupper(BASE_CURRENCY); ?>
	    	 	</td>
	    	 	<td >
	    	 		&nbsp;
	    	 	</td>
    	 	</tr>
    	 	
    	 	<?php } ?>
    	 	<tr>
	    	 	<td colspan="2">
	    	 		&nbsp;
	    	 	</td>
	    	 	<td colspan="2" align="right">
	    	 		<?php echo gettext("TOTAL INCL. VAT") ?>&nbsp;:
	    	 	</td>
	    	 	<td align="right">
	    	 		<?php echo number_format(round($price_with_vat,2),2)." ".strtoupper(BASE_CURRENCY); ?>
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
<?php if(!empty($error_msg)){ ?>
	<div class="msg_error" style="width:70%; margin-left:auto;margin-right:auto;">
		<?php echo $error_msg ?>
	</div>
<?php } ?>
  <form action="<?php echo $PHP_SELF.'?id='.$invoice->getId(); ?>" method="post" >
 	<input id="action" type="hidden" name="action" value="<?php if(!empty($idc)) echo "update"; else echo "add" ?>"/>
	<input id="idc" type="hidden" name="idc" value="<?php if(!empty($idc)) echo $idc;?>"/>
	<table class="invoice_table">
		<tr class="form_invoice_head">
	    	<td colspan="2" align="center"><font color="#FFFFFF"><?php echo gettext("ADD INVOICE ITEM "); ?></font></td>
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
			 <input type="text" class="form_input_text" name="date" size="20" maxlength="20" <?php if(!empty($date)) echo 'value="'.$date.'"';?>/>
			 </td>
		</tr>
		<tr>
			<td ><font style="font-weight:bold; " ><?php echo gettext("AMOUNT : "); ?>
			 </td>
			 <td>
			 <input type="text" class="form_input_text" name="price" size="10" maxlength="10" <?php if(!empty($price)) echo 'value="'.$price.'"';?>/>
			 </td>
		</tr>
		<tr>
			<td ><font style="font-weight:bold; " ><?php echo gettext("VAT : "); ?>
			 </td>
			 <td>
			 <input type="text" class="form_input_text" name="vat" size="5" maxlength="5" <?php if(!empty($vat)) echo 'value="'.$vat.'"'; else echo 'value="'.$card_vat.'"';?> />
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

