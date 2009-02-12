<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/customer.smarty.php");
include ("./lib/support/classes/invoice.php");
include ("./lib/support/classes/invoiceItem.php");

if (! has_rights (ACX_INVOICES)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}


getpost_ifset(array('id'));

if (empty($id))
{
Header ("Location: A2B_entity_invoice.php?atmenu=payment&section=13");
}


$invoice = new invoice($id);
if($invoice->getCard() != $_SESSION["card_id"]){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}
$items = $invoice->loadDetailledItems();
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

<div class="invoice-wrapper">
  <table class="invoice-table">
  <thead>
  <tr class="one">  
    <td class="one">
     <h1><?php echo gettext("INVOICE DETAIL"); ?></h1>
     
    </td>
  </tr>
  <tr class="two">
    <td colspan="3" class="invoice-details">
      <table class="invoice-details"> 
        <tbody><tr>
          <td class="one">
            <strong><?php echo gettext("Date"); ?></strong>
            <div><?php echo $invoice->getDate() ?></div>
          </td>
          <td class="two">
            <strong><?php echo gettext("Invoice number"); ?></strong>
            <div><?php echo $invoice->getReference() ?></div>
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
	          <th style="text-align:left;"><?php echo gettext("Date"); ?></th>
	          <th class="description"><?php echo gettext("Description"); ?></th>
	          <th><?php echo gettext("Cost excl. VAT"); ?></th>
	          <th><?php echo gettext("VAT"); ?></th>
	          <th><?php echo gettext("Cost incl. VAT"); ?></th>
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
				<td align="right">
					<?php echo number_format(round($item->getVAT(),2),2)."%"; ?>
				</td>
				<td align="right">
					<?php echo number_format(round(amount_convert($item->getPrice())*(1+($item->getVAT()/100)),6),6); ?>
				</td>
			</tr>  
			 <?php  $i++;} ?>	
          
          
        </tbody></table>        
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
      <td colspan="3">
        <table class="total">
         <tbody><tr class="extotal">
           <td class="one"></td>
           <td class="two"><?php echo gettext("Subtotal excl. VAT:"); ?></td>
           <td class="three"><?php echo number_format(ceil(amount_convert(ceil($price_without_vat*100)/100)*100)/100,2)." $display_curr"; ?></td>
         </tr>
         	
         <?php foreach ($vat_array as $key => $val) { ?>
                 <tr class="vat">
                   <td class="one"></td>
                   <td class="two"><?php echo gettext("VAT $key%:") ?></td>
                   <td class="three"><?php echo number_format(round(amount_convert($val),2),2)." $display_curr"; ?></td>
                 </tr> 
         <?php } ?>
         <tr class="inctotal">
           <td class="one"></td>
           <td class="two"><?php echo gettext("Total incl. VAT:") ?></td>
           <td class="three"><div class="inctotal"><div class="inctotal inner"><?php echo number_format(ceil(amount_convert(ceil($price_with_vat*100)/100)*100)/100,2)." $display_curr"; ?></div></div></td>
         </tr>
        </tbody></table>
      </td>
    </tr>
    
  </tbody>
  
  </table></div>



