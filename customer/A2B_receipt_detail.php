<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/customer.smarty.php");
include ("./lib/support/classes/receipt.php");
include ("./lib/support/classes/receiptItem.php");

if (! has_rights (ACX_INVOICES)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('id'));

if (empty($id))
{
Header ("Location: A2B_entity_receipt.php?atmenu=payment&section=13");
}


$receipt = new receipt($id);
if($receipt->getCard() != $_SESSION["card_id"]){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}
$items = $receipt->loadDetailledItems();
//load customer
$DBHandle  = DbConnect();

$smarty->display('main.tpl');

//Currencies check
$curr = $_SESSION['currency'];
$currencies_list = get_currencies();
if (!isset($currencies_list[strtoupper($curr)][2]) || !is_numeric($currencies_list[strtoupper($curr)][2])) {$mycur = 1;$display_curr=strtoupper(BASE_CURRENCY);}
else {$mycur = $currencies_list[strtoupper($curr)][2];$display_curr=strtoupper($curr);}

function amount_convert($amount){
	global $mycur;
	return $amount/$mycur;
}

?>

<div class="receipt-wrapper">
  <table class="receipt-table">
  <thead>
  <tr class="one">  
    <td class="one">
     <h1><?php echo gettext("RECEIPT DETAIL"); ?></h1>
     
    </td>
  </tr>
  <tr class="two">
    <td colspan="3" class="receipt-details">
      <table class="receipt-details"> 
        <tbody><tr>
          <td class="one">
            <strong><?php echo gettext("Date"); ?></strong>
            <div><?php echo $receipt->getDate() ?></div>
          </td>

          <td class="three">
           <strong>Client number</strong>
            <div><?php echo $_SESSION['pr_login'] ?></div>
          </td>
                 </tr>       
      </tbody></table>
    </td>
  </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="3" class="items">
        <table class="items">
          <tbody>
          <tr class="one">
	          <th style="text-align:left;" width="20%"><?php echo gettext("Date"); ?></th>
	          <th class="description" width="60%"><?php echo gettext("Description"); ?></th>
	          <th width="20%" ><?php echo gettext("Cost"); ?></th>
          </tr>
          <?php 
          $i=0;
          foreach ($items as $item){ ?>
			<tr style="vertical-align:top;" class="<?php if($i%2==0) echo "odd"; else echo "even";?>" >
				<td style="text-align:left;">
					<?php echo $item->getDate(); ?>
				</td>
				<td class="description">
					<?php echo $item->getDescription(); ?>
				</td>
				<td align="right">
					<?php echo number_format(round(amount_convert($item->getPrice()),6),6); ?>
				</td>
			</tr>  
			 <?php  $i++;} ?>	
          
          
        </tbody></table>        
      </td>
    </tr>
    <?php
		$price= 0;
    	foreach ($items as $item){  
    	 	$price = $price + $item->getPrice();
    	 } 
    	 ?>
    <tr>
      <td colspan="3">
        <table class="total">
         <tbody>
         <tr class="inctotal">
           <td class="one"></td>
           <td class="two"><?php echo gettext("Total :") ?></td>
           <td class="three"><div class="inctotal"><div class="inctotal inner"><?php echo number_format(ceil(amount_convert(ceil($price*100)/100)*100)/100,2)." $display_curr"; ?></div></div></td>
         </tr>
        </tbody></table>
      </td>
    </tr>
    
  </tbody>
  
  </table></div>



