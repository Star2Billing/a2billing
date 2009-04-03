<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/customer.smarty.php");
include ("./lib/support/classes/invoice.php");
include ("./lib/support/classes/invoiceItem.php");

if (! has_rights (ACX_INVOICES)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

if(empty($_SESSION["card_id"])) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

$DBHandle  = DbConnect();

//find the last billing 
$billing_table = new Table('cc_billing_customer','id,date');
$clause_last_billing = "id_card = ".$_SESSION["card_id"];
$result = $billing_table -> Get_list($DBHandle, $clause_last_billing,"date","desc");
$call_table = new Table('cc_call','*' );
$clause_call_billing ="card_id = ".$_SESSION["card_id"]." AND ";
$desc_billing="";
$start_date =null;
if(is_array($result) && !empty($result[0][0])) {
	$clause_call_billing .= "stoptime >= '" .$result[0][1]."' AND "; 
	$desc_billing = "Calls cost between the ".$result[0][1]." and ".date("Y-m-d H:i:s") ;
	$start_date = $result[0][1];
} else {
	$desc_billing = "Calls cost before the ".date("Y-m-d H:i:s") ;
}
$clause_call_billing .= "stoptime < '".date("Y-m-d H:i:s")."' ";
$result_calls =  $call_table -> Get_list($DBHandle, $clause_call_billing);
$items = array();
$i=0;
if(is_array($result_calls)) {
	/// create items
	foreach ($result_calls as $call) {
		$min = floor($call['sessiontime']/60);
		$sec= $call['sessiontime']%60;
		$item = new InvoiceItem(null,"CALL : ".$call['calledstation']." DURATION : ".$min." min ".$sec." sec",$call['starttime'],$call["sessionbill"],$_SESSION["vat"],true);
		$items[$i]= $item;
    	$i++;
	}
}	




$smarty->display('main.tpl');

$curr = $_SESSION['currency'];
$currencies_list = get_currencies();
if (!isset($currencies_list[strtoupper($curr)][2]) || !is_numeric($currencies_list[strtoupper($curr)][2])) {$mycur = 1;$display_curr=strtoupper(BASE_CURRENCY);}
else {$mycur = $currencies_list[strtoupper($curr)][2];$display_curr=strtoupper($curr);}

function amount_convert($amount) {
	global $mycur;
	return $amount/$mycur;
}

if(!$popup_select) {
?>
<a href="javascript:;" onClick="MM_openBrWindow('<?php echo $PHP_SELF ?>?popup_select=1','','scrollbars=yes,resizable=yes,width=700,height=500')" > <img src="./templates/default/images/printer.png" title="Print" alt="Print" border="0"></a>
&nbsp;&nbsp;
<?php
}
?>

<div class="invoice-wrapper">
  <table class="invoice-table">
  <thead>
  <tr class="one">  
    <td class="one">
     <h1><?php echo gettext("PREVIEW INVOICE"); ?></h1>
     
    </td>
  </tr>
  <tr class="two">
    <td colspan="3" class="invoice-details">
      <table class="invoice-details"> 
        <tbody><tr>
          <td class="one">
            <strong><?php echo gettext("Date"); ?></strong>
            <div><?php echo date("Y-m-d H:i:s") ?></div>
          </td>
          <td class="two">
            &nbsp;
          </td>
          <td class="three">
           <strong><?php echo gettext("Client number"); ?></strong>
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



