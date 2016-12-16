<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
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

include './lib/customer.defines.php';
include './lib/customer.module.access.php';
include './lib/customer.smarty.php';
include './lib/support/classes/invoice.php';
include './lib/support/classes/invoiceItem.php';
include './lib/support/classes/receipt.php';
include './lib/support/classes/receiptItem.php';

if (! has_rights (ACX_INVOICES)) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

if (empty($_SESSION["card_id"])) {
    Header ("HTTP/1.0 401 Unauthorized");
    Header ("Location: PP_error.php?c=accessdenied");
    die();
}

$DBHandle  = DbConnect();

$card_table = new Table('cc_card','vat,typepaid,credit');
$card_clause = "id = ".$_SESSION["card_id"];
$card_result = $card_table -> Get_list($DBHandle, $card_clause, 0);

if(!is_array($card_result)||empty($card_result[0]['vat'])||!is_numeric($card_result[0]['vat'])) $vat=0;
    else $vat = $card_result[0][0];
if(!is_array($card_result)||empty($card_result[0]['typepaid'])||!is_numeric($card_result[0]['typepaid'])) $typepaid=0;
    else $typepaid = $card_result[0][1];
if(!is_array($card_result)||empty($card_result[0]['credit'])||!is_numeric($card_result[0]['credit'])) $credit=0;
    else $credit = $card_result[0][2];
//find the last billing

$billing_table = new Table('cc_billing_customer','id,date');
$clause_last_billing = "id_card = ".$_SESSION["card_id"];
$result = $billing_table -> Get_list($DBHandle, $clause_last_billing,"date","desc");
$call_table = new Table('cc_call','COALESCE(SUM(sessionbill),0)' );
$clause_call_billing ="card_id = ".$_SESSION["card_id"]." AND ";
$clause_charge = "id_cc_card = ".$_SESSION["card_id"]." AND ";
$desc_billing="";
$desc_billing_postpaid="";
$start_date =null;
if (is_array($result) && !empty($result[0][0])) {
    $clause_call_billing .= "stoptime >= '" .$result[0][1]."' AND ";
    $clause_charge .= "creationdate >= '".$result[0][1]."' AND  ";
    $desc_billing = gettext("Cost of calls between the "). display_GMT($result[0][1], $_SESSION["gmtoffset"], 1) ." and ". display_GMT(gmdate("Y/m/d H:i:s"), $_SESSION["gmtoffset"], 1) ;
    $desc_billing_postpaid="Amount for periode between the ".date("Y-m-d",strptime($result[0][1]))." and $date_bill";
    $start_date = $result[0][1];
} else {
    $desc_billing = gettext("Cost of calls before the "). display_GMT(gmdate("Y/m/d H:i:s"), $_SESSION["gmtoffset"], 1) ;
}
$clause_call_billing .= "stoptime < NOW() ";
$clause_charge .= "creationdate < NOW() ";
$result_calls =  $call_table -> Get_list($DBHandle, $clause_call_billing);
$receipt_items = array();

// COMMON BEHAVIOUR FOR PREPAID AND POSTPAID ... GENERATE A RECEIPT FOR THE CALLS OF THE MONTH
if (is_array($result_calls)) {
    $item = new ReceiptItem(null, $desc_billing, gmdate("Y/m/d H:i:s"), $result_calls[0][0], 'CALLS');
    $receipt_items[]= $item;
}

// GENERATE RECEIPT FOR CHARGE ALREADY CHARGED

$table_charge = new Table("cc_charge", "*");
$result =  $table_charge -> Get_list($DBHandle, $clause_charge." AND charged_status = 1");
    if (is_array($result)) {
        foreach ($result as $charge) {
        $item = new ReceiptItem(null, gettext("CHARGE :").$charge['description'], $charge['creationdate'], $charge['amount'], 'CHARGE');
        $receipt_items[]= $item;
        }
    }
 // GENERATE RECEIPT FOR CHARGE NOT CHARGED YET
 $table_charge = new Table("cc_charge", "*");
 $result =  $table_charge -> Get_list($DBHandle, $clause_charge." AND charged_status = 0 AND invoiced_status = 0");
    if (is_array($result) && sizeof($result)>0) {
        foreach ($result as $charge) {
            $item = new InvoiceItem(null, gettext("CHARGE :").$charge['description'], $charge['creationdate'], $charge['amount'],$vat, 'CHARGE');
            $invoice_items[]= $item;
        }

    }
 // behaviour postpaid

    if ($typepaid==1 && $credit<0) {
        //GENERATE AN INVOICE TO COMPLETE THE BALANCE
    $amount = abs($credit);
    $item = new InvoiceItem(null, $desc_billing_postpaid, gmdate("Y/m/d H:i:s"), $amount,$vat, 'POSTPAID');
    $invoice_items[]= $item;
    }

$smarty->display('main.tpl');

$curr = $_SESSION['currency'];
$currencies_list = get_currencies();
if (!isset($currencies_list[strtoupper($curr)][2]) || !is_numeric($currencies_list[strtoupper($curr)][2])) {$mycur = 1;$display_curr=strtoupper(BASE_CURRENCY);} else {$mycur = $currencies_list[strtoupper($curr)][2];$display_curr=strtoupper($curr);}

function amount_convert($amount)
{
    global $mycur;

    return $amount/$mycur;
}

?>

<div class="receipt-wrapper">
    <table class="receipt-table" style="border-bottom: dotted gray 1px;">
  <thead>
  <tr class="one">
    <td class="one">
     <h1><?php echo gettext("PREVIEW NEXT RECEIPT"); ?>
     <a href="javascript:;" onClick="MM_openBrWindow('A2B_receipt_preview_detail.php?popup_select=1','','scrollbars=yes,resizable=yes,width=700,height=500')" > <img src="./templates/default/images/info.png" title="Details" alt="Details" border="0"></a>
     </h1>
   </td>
  </tr>
  <tr class="two">
    <td colspan="3" class="receipt-details">
      <table class="receipt-details">
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
              <th style="text-align:left;" width="20%"><?php echo gettext("Date"); ?></th>
              <th class="description" width="60%"><?php echo gettext("Description"); ?></th>
              <th width="20%" ><?php echo gettext("Cost"); ?></th>
          </tr>
          <?php
          $i=0;
          foreach ($receipt_items as $item) { ?>
            <tr style="vertical-align:top;" class="<?php if($i%2==0) echo "odd"; else echo "even";?>" >
                <td style="text-align:left;">
                    <?php echo $item->getDate(); ?>
                </td>
                <td class="description">
                    <?php echo $item->getDescription(); ?>
                </td>
                <td align="right">
                    <?php echo number_format(ceil(amount_convert($item->getPrice())*100)/100,2)  ?>
                </td>
            </tr>
             <?php  $i++;} ?>

        </tbody></table>
      </td>
    </tr>

    <?php
        $price = 0;
        foreach ($receipt_items as $item) {
            $price = $price + $item->getPrice();
        }
    ?>

    <tr>
      <td colspan="3">
        <table class="total">
         <tbody>
         <tr class="inctotal">
           <td class="one"></td>
           <td class="two"><?php echo gettext("Total:") ?></td>
           <td class="three"><div class="inctotal"><div class="inctotal inner"><?php echo number_format(ceil(amount_convert($price)*100)/100,2)." $display_curr"; ?></div></div></td>
         </tr>
        </tbody></table>
      </td>
    </tr>
    <tr>
    <td colspan="3" class="additional-information">
      <div class="receipt-description">
    <?php echo gettext("Summary of the charge charged since the last billing."); ?>
    <br/>
    <?php echo gettext("Summary of calls since the last billing."); ?>
    <br/>
    <br/>
     </div></td>
    </tr>
  </tbody>
  </table></div>

<?php if (sizeof($invoice_items)>0) {?>
<div class="invoice-wrapper">
  <table class="invoice-table">
  <thead>
  <tr class="one">
    <td class="one">
     <h1><?php echo gettext("PREVIEW NEXT INVOICE"); ?></h1>

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
        </tr></tbody>
      </table>
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
          foreach ($invoice_items as $item) { ?>
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
        foreach ($invoice_items as $item) {
            $price_without_vat = $price_without_vat + $item->getPrice();
            $price_with_vat = $price_with_vat + ($item->getPrice()*(1+($item->getVAT()/100)));
            if (array_key_exists("".$item->getVAT(),$vat_array)) {
                $vat_array[$item->getVAT()] = $vat_array[$item->getVAT()] + $item->getPrice()*($item->getVAT()/100) ;
            } else {
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

         <?php
            foreach ($vat_array as $key => $val) {
          ?>
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
     <tr>
    <td colspan="3" class="additional-information">
      <div class="invoice-description">
      <?php echo gettext("This invoice is for some charges unpaid since the last billing, and for the negative balance.") ?>;
     </div></td>
    </tr>

  </tbody>
  </table></div>

<?php
}
// #### FOOTER SECTION
$smarty->display('footer.tpl');
