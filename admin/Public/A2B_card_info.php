<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");



if (! has_rights (ACX_CUSTOMER)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

/***********************************************************************************/

getpost_ifset(array('id'));
 //Redirect if $id is empty

if (empty($id)) {
	header("Location: A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}
$DBHandle  = DbConnect();



$card_table = new Table('cc_card','*');
$card_clause = "id = ".$id;
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);
$card = $card_result[0];

if (empty($card)) {
	header("Location: A2B_entity_card.php?atmenu=card&stitle=Customers_Card&section=1");
}

// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_info_customer;

$inst_table = new Table("cc_card", "useralias, uipass");
$FG_TABLE_CLAUSE = "id = $id";
$list_card_info = $inst_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE);			
$username = $list_card_info[0][0];
$password = base64_encode($list_card_info[0][1]);
$link = CUSTOMER_UI_URL;
echo "<div align=\"right\" style=\"padding-right:20px;\"><a href=\"$link?username=$username&password=$password\" target=\"_blank\">GO TO CUSTOMER ACCOUNT</a></div>";
?>


<table width="800px" >	
	<tr>
		<td valign="top" width="50%" >
			<table width="100%" class="editform_table1"  >
				<tr>
					<th colspan="2">
				 		<?php echo gettext("CUSTOMER INFO") ?>
				 	</th>
				 
				</tr>
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("LAST NAME") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['lastname']?> 
					</td>
					
					
				</tr>
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("FIRST NAME") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['firstname']?> 
					</td>
					
					
				
				</tr>
				
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("ADDRESS") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['address']?> 
					</td>
					
					
				</tr>
				
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("ZIP CODE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['zipcode']?> 
					</td>
				</tr>
				
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("CITY") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['city']?> 
					</td>
					
				</tr>
				
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("STATE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['state']?> 
					</td>
					
					
				</tr>
				
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("COUNTRY") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['country']?> 
					</td>
					
				</tr>
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("EMAIL") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%"><td>
						<?php echo $card['email']?> 
					</td>
					
				</tr>
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("PHONE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['phone']?> 
					</td>
				</tr>
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("FAX") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['fax']?> 
					</td>
				</tr>
			</table>
		</td>
	
		<td valign="top" width="50%" >
			<table width="100%" class="editform_table1">	
			   <tr>
			   		<th colspan="2">
			   			<?php echo gettext("CARD INFO") ?>
			   		</th>	
			   </tr>
			   <tr height="20px">
					<td  class="form_head">
						<?php echo gettext("CARD NUMBER") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['username']?> 
					</td>
			   </tr>
			   <tr height="20px">
					<td  class="form_head">
						<?php echo gettext("WEB ALIAS") ?>
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['useralias']?> 
					</td>
			   </tr>
			   <tr height="20px">
					<td  class="form_head">
						<?php echo gettext("WEB PASSWORD") ?>
					</td>
					<td class="tableBodyRight">
						<?php echo $card['uipass']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("LANGUAGE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['language']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("STATUS") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php 
						$list_status = Constants::getCardStatus_List();
						echo $list_status[$card['status']][0];?> 
					</td>
				</tr>
			   	<tr height="20px">	
					<td  class="form_head">
						<?php echo gettext("CREATION DATE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['creationdate']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("EXPIRATION DATE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['expirationdate']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("FIRST USE DATE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['firstusedate']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("LAST USE DATE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['lastuse']?> 
					</td>
				</tr>
	  			<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("CALLBACK") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['callback']?> 
					</td>
				</tr>							
			 </table>
		</td>
	</tr>
</table>

<br/>

<table width="100%">	
	<tr>
		<td valign="top" width="50%" >
			<table width="100%" class="editform_table1"  >
				<tr>
					<th colspan="2">
				 		<?php echo gettext("COMPANY INFO") ?>
				 	</th>
				 
				</tr>
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("COMPANY NAME") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['company_name']?> 
					</td>
				</tr>
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("COMPANY WEBSITE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['company_website']?> 
					</td>
				
				</tr>
				
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("VAT REGISTRATION NUMBER") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['VAT_RN']?> 
					</td>
					
					
				</tr>
				
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("TRAFFIC PER MONTH") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['traffic']?> 
					</td>
				</tr>
				
				<tr  height="20px">
					<td  class="form_head">
						<?php echo gettext("TARGET TRAFIC") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['traffic_target']?> 
					</td>
					
				</tr>
			</table>
		</td>
	
		<td valign="top" width="50%" >
			<table width="100%" class="editform_table1">	
			   <tr>
			   		<th colspan="2">
			   			<?php echo gettext("ACCOUNT INFO") ?>
			   		</th>	
			   </tr>
			   <tr height="20px">
					<td  class="form_head">
						<?php echo gettext("BALANCE") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						&nbsp;<?php echo $card['credit']?> 
					</td>
				</tr>
				<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("CURRENCY") ?>
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['currency']?> 
					</td>
			  	</tr>
			   <tr height="20px">
					<td  class="form_head">
						<?php echo gettext("CREDIT LIMIT") ?>
					</td>
					<td class="tableBodyRight">
						<?php echo $card['creditlimit']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("AUTOREFILL") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['autorefill']?> 
					</td>
				</tr>
			   	<tr height="20px">
					<td  class="form_head">
						<?php echo gettext("INVOICE DAY") ?> :
					</td>
					<td class="tableBodyRight"  background="../Public/templates/default/images/background_cells.gif" width="70%">
						<?php echo $card['invoiceday']?> 
					</td>
				</tr>
			 </table>
		</td>
	</tr>
</table>

<?php

$payment_table = new Table('cc_logpayment','*');
$payment_clause = "card_id = ".$id;
$payment_result = $payment_table -> Get_list($DBHandle, $payment_clause, 'date', 'DESC', NULL, NULL, 10, 0);
if(sizeof($payment_result)>0) {
?>
<table class="toppage_maintable">
	<tr>
		<td height="20" align="center"> 
			<font class="toppage_maintable_text">						  
			  <?php echo gettext("10 Last Customer's Payment"); ?>		  <br/>
			</font>
		</td>
	</tr>
</table>

<table width="100%"  cellspacing="2" cellpadding="2" border="0">

	<tr class="form_head">
		<td class="tableBody"  width="15%" align="center" style="padding: 2px;">
		 <?php echo gettext("ID"); ?>
		</td>
		<td class="tableBody"  width="20%" align="center" style="padding: 2px;">
		<?php echo gettext("PAYMENT DATE"); ?>
		</td>
		<td class="tableBody"  width="15%" align="center" style="padding: 2px;">
		<?php echo gettext("PAYMENT AMOUNT"); ?>
		</td>
		<td class="tableBody"  width="30%" align="center" style="padding: 2px;">
		<?php echo gettext("DESCRIPTION"); ?>
		</td>
		<td class="tableBody"  width="15%" align="center" style="padding: 2px;">
		ID REFILL
		</td>
		
	</tr>
 
	<?php 
		$i=0;
		foreach ($payment_result as $payment) {
			if($i%2==0) $bg="#fcfbfb";
			else  $bg="#f2f2ee";
	?>
			<tr bgcolor="<?php echo $bg; ?>"  >
				<td class="tableBody" align="center">
				  <?php echo $payment['id']; ?>
				</td>
			
				<td class="tableBody" align="center">
				  <?php echo $payment['date']; ?>
				</td>
			
				<td class="tableBody"  align="center">
				  <?php echo $payment['payment']; ?>
				</td>
				<td class="tableBody"  align="center">
				  <?php echo $payment['description']; ?>
				</td>
				<td class="tableBody"  align="center">
				  <?php echo $payment['id_logrefill']; ?>
				</td>
			</tr>
		<?php 
		$i++;	
		}
		?>
</table>
<?php 
}
?>


<?php

$refill_table = new Table('cc_logrefill','*');
$refill_clause = "card_id = ".$id;
$refill_result = $refill_table -> Get_list($DBHandle, $refill_clause, 'date', 'DESC', NULL, NULL, 10, 0);

if(sizeof($refill_result)>0) {
?>
<table class="toppage_maintable">
	<tr>
		<td height="20" align="center"> 
			<font class="toppage_maintable_text">						  
			 <?php echo gettext("10 Last Customer's Refill"); ?>			  <br/>
			</font>
		</td>
	</tr>
</table>

<table width="100%"  cellspacing="2" cellpadding="2" border="0">

	<tr class="form_head">
		<td class="tableBody"  width="15%" align="center" style="padding: 2px;">
		 <?php echo gettext("ID"); ?>
		</td>
		<td class="tableBody"  width="20%" align="center" style="padding: 2px;">
		 <?php echo gettext("REFILL DATE"); ?> 
		</td>
		<td class="tableBody"  width="15%" align="center" style="padding: 2px;">
		 <?php echo gettext("REFILL AMOUNT"); ?>
		</td>
		<td class="tableBody"  width="40%" align="center" style="padding: 2px;">
		 <?php echo gettext("DESCRIPTION"); ?>
		</td>
		
	</tr>
 
	<?php 
		$i=0;
		foreach ($refill_result as $refill) {
			if($i%2==0) $bg="#fcfbfb";
			else  $bg="#f2f2ee";
	?>
			<tr bgcolor="<?php echo $bg; ?>"  >
				<td class="tableBody" align="center">
				  <?php echo $refill['id']; ?>
				</td>
			
				<td class="tableBody" align="center">
				  <?php echo $refill['date']; ?>
				</td>
			
				<td class="tableBody"  align="center">
				  <?php echo $refill['credit']; ?>
				</td>
				<td class="tableBody"  align="center">
				  <?php echo $refill['description']; ?>
				</td>
				
			</tr>
		<?php 
		$i++;	
		}
		?>
</table>
<?php 
}
?>

