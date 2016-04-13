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

include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/customer.smarty.php';
include 'lib/epayment/includes/configure.php';
include 'lib/epayment/includes/html_output.php';
include './lib/epayment/includes/general.php';

if (!has_rights(ACX_ACCESS)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$inst_table = new Table();

$QUERY = "SELECT username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, " .
"freetimetocall, label, packagetype, billingtype, startday, id_cc_package_offer, cc_card.id, currency,cc_card.useralias,UNIX_TIMESTAMP(cc_card.creationdate) creationdate  FROM cc_card " .
"LEFT JOIN cc_tariffgroup ON cc_tariffgroup.id=cc_card.tariff LEFT JOIN cc_package_offer ON cc_package_offer.id=cc_tariffgroup.id_cc_package_offer " .
"LEFT JOIN cc_card_group ON cc_card_group.id=cc_card.id_group WHERE username = '" . $_SESSION["pr_login"] .
"' AND uipass = '" . $_SESSION["pr_password"] . "'";

$DBHandle = DbConnect();

$customer_res = $inst_table -> SQLExec($DBHandle, $QUERY);

if (!$customer_res || !is_array($customer_res)) {
    echo gettext("Error loading your account information!");
    exit ();
}

$customer_info = $customer_res[0];
if ($customer_info[14] != "1" && $customer_info[14] != "8") {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$customer = $_SESSION["pr_login"];

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'dsttype', 'sourcetype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'clid','subscribe'));

$currencies_list = get_currencies();

$two_currency = false;
if (!isset ($currencies_list[strtoupper($customer_info[22])][2]) || !is_numeric($currencies_list[strtoupper($customer_info[22])][2])) {
    $mycur = 1;
} else {
    $mycur = $currencies_list[strtoupper($customer_info[22])][2];
    $display_currency = strtoupper($customer_info[22]);
    if (strtoupper($customer_info[22]) != strtoupper(BASE_CURRENCY))
        $two_currency = true;
}

$credit_cur = $customer_info[1] / $mycur;
$credit_cur = round($credit_cur, 3);
$useralias = $customer_info['useralias'];
$creation_date = $customer_info['creationdate'];
$username = $customer_info['username'];

$smarty->display('main.tpl');
?>

<div>

<table  class="tablebackgroundblue" align="center" >
<tr>
    <td><img src="<?php echo KICON_PATH ?>/personal.gif" align="left" class="kikipic"/></td>
    <td width="50%"><font class="fontstyle_002">
    <?php echo gettext("LAST NAME");?> :</font>  <font class="fontstyle_007"><?php echo $customer_info[2]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("FIRST NAME");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[3]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("EMAIL");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[10]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("PHONE");?> :</font><font class="fontstyle_007"> <?php echo $customer_info[9]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("FAX");?> :</font><font class="fontstyle_007"> <?php echo $customer_info[11]; ?></font>
    </td>
    <td width="50%">
    <font class="fontstyle_002"><?php echo gettext("ADDRESS");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[4]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("ZIP CODE");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[8]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("CITY");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[5]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("STATE");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[6]; ?></font>
    <br/><font class="fontstyle_002"><?php echo gettext("COUNTRY");?> :</font> <font class="fontstyle_007"><?php echo $customer_info[7]; ?></font>
    </td>
</tr>

<tr>
    <td></td>
    <td align="right">
        &nbsp;
    </td>
    <td align="right">
        <?php if (has_rights (ACX_PERSONALINFO)) { ?>
        <a href="A2B_entity_card.php?atmenu=password&form_action=ask-edit&stitle=Personal+Information"><span class="cssbutton"><font color="red"><?php echo gettext("EDIT PERSONAL INFORMATION");?></font></span></a>
        <?php } ?>
    </td>
</tr>
</table>

<br>
<table style="width:70%;margin:0 auto;"  align="center" >
<tr>
    <td align="center">
        <table width="80%" align="center" class="tablebackgroundcamel">
        <tr>
            <td></td>
            <td width="50%">
            <br><font class="fontstyle_002"><?php echo gettext("CARD NUMBER");?> :</font><font class="fontstyle_007"> <?php echo $customer_info[0]; ?></font>
            <br><br>
            </td>
            <td width="50%">
            <br><font class="fontstyle_002"><?php echo gettext("BALANCE REMAINING");?> :</font><font class="fontstyle_007"> <?php echo $credit_cur.' '.$customer_info[22]; ?> </font>
            <br><br>
            </td>
            <?php
                if ($customer_info[15] > 0) {
                    $freetimetocall_used = $A2B->FT2C_used_seconds($DBHandle, $customer_info[21], $customer_info[20], $customer_info[18], $customer_info[19]);?>
            </tr><tr><td /><td width="50%">
            <font class="fontstyle_002"><?php echo gettext("CALLING PACKAGE");?> :</font><br><font class="fontstyle_007"> <?php echo $customer_info[16]; ?> </font>
            </td>
            <td width="50%">
                <font class="fontstyle_002">
                    <?php
                    if (($customer_info[17]==0) || ($customer_info[17]==1)) {
                        echo gettext("PACKAGE MINUTES REMAINING");?> :</font><br><font class="fontstyle_007"> <?php printf ("%d:%02d of %d:%02d",intval(($customer_info[15]-$freetimetocall_used) / 60),($customer_info[15]-$freetimetocall_used) % 60,intval($customer_info[15]/60),$customer_info[15] % 60);
                    } else {
                        echo gettext("PACKAGE MINUTES USED");?> :</font><br><font class="fontstyle_007"> <?php printf ("%d:%02d",intval($freetimetocall_used / 60),$freetimetocall_used % 60);
                    }
                    ?>
                </font>
            </tr><tr><td /><td width="50%" /><td width="50%" />
            <?php }?>
            <td valign="bottom" align="right"><img src="<?php echo KICON_PATH ?>/help_index.gif" class="kikipic"></td>
        </tr>
        </table>
    </td>
</tr>
</table>
<?php
if (!empty($subscribe)) {
     if ($subscribe=="true") {
     ?>
<div class="msg_success" style="width:70%;margin:0 auto;" ><?php echo gettext("Your subscription for an automique refill success"); ?>	</div>

<?php } else { ?>
    <div class="msg_error" style="width:70%;margin:0 auto;"><?php echo gettext("Your subscription for an automique refill faild"); ?>	</div>
    <?php
   }
}
?>

<?php if ($A2B->config["epayment_method"]['enable']) { ?>

<br>

<?php
    echo $PAYMENT_METHOD;
?>

<table style="width:80%;margin:0 auto;" cellspacing="0"  align="center" >
    <tr background="<?php echo Images_Path; ?>/background_cells.gif" >
        <TD  valign="top" align="right" class="tableBodyRight"   >
            <font size="2"><?php echo gettext("Click below to buy credit : ");?> </font>
        </TD>
        <td class="tableBodyRight" >

            <?php

$arr_purchase_amount = preg_split("/:/", EPAYMENT_PURCHASE_AMOUNT);
if (!is_array($arr_purchase_amount)) {
    $to_echo = 10;
} else {
    if ($two_currency) {
        $purchase_amounts_convert = array ();
        for ($i = 0; $i < count($arr_purchase_amount); $i++) {
            $purchase_amounts_convert[$i] = round($arr_purchase_amount[$i] / $mycur, 2);
        }
        $to_echo = join(" - ", $purchase_amounts_convert);

        echo $to_echo;
?>
            <font size="2">
            <?php echo $display_currency; ?> </font>
            <br/>
            <?php } ?>
            <?php echo join(" - ", $arr_purchase_amount); ?>
            <font size="2"><?php echo strtoupper(BASE_CURRENCY);?> </font>
            <?php } ?>

        </TD>
    </tr>
    <tr>
        <td align="center" colspan="2" class="tableBodyRight" >
            <form action="checkout_payment.php" method="post" target="_blank">
                <input type="submit" class="form_input_button" value="<?php echo gettext("BUY NOW");?>">
                <br>
            </form>
        </td>
    </tr>
</table>

<br/>
<table style="width:80%;margin:0 auto;" cellspacing="0"  align="center" >
    <?php
    if ($A2B->config['epayment_method']['paypal_subscription_enabled']==1) {
        $vat= $_SESSION['vat'];
         $amount_subscribe = $A2B->config['epayment_method']['paypal_subscription_amount'];
        ?>
    <tr background="<?php echo Images_Path; ?>/background_cells.gif" >
        <TD  valign="top" align="right" class="tableBodyRight"   >
            <font size="2"><?php echo gettext("Click below to subscribe an automated refill : ");?> </font>
        </TD>
        <td class="tableBodyRight" >
            <?php
            $head_desc= $amount_subscribe." ".strtoupper(BASE_CURRENCY);
             if($vat>0)$head_desc .= " + ".(($vat/100)*$amount_subscribe)." ".strtoupper(BASE_CURRENCY)." of ".gettext("VAT")."";
             echo $head_desc;
             echo " (".gettext("for each")." ".$A2B->config['epayment_method']['paypal_subscription_period_number']." ";
             switch (strtoupper($A2B->config['epayment_method']['paypal_subscription_time_period'])) {
             case "D": echo gettext("Days");
                 ;
                 break;
             case "M": echo gettext("Months");
                 break;
             case "Y": echo gettext("Years");
                 break;
             default:
                 break;
             }
             echo ")";
           ?>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2" class="tableBodyRight" >
            <img src="<?php echo Images_Path ?>/payments_paypal.gif" />
        </td>
    </tr>

    <?php
        $desc = gettext("Automated refill")." ".$A2B->config['epayment_method']['paypal_subscription_amount']." ".strtoupper(BASE_CURRENCY);
        if($vat>0)$desc .= " + ".(($vat/100)*$amount_subscribe)." ".strtoupper(BASE_CURRENCY)." of ".gettext("VAT");
        $amount_subscribe = $amount_subscribe +(($vat/100)*$amount_subscribe);
        $key = securitykey(EPAYMENT_TRANSACTION_KEY, $username."^".$_SESSION["card_id"]."^".$useralias."^".$creation_date);
        $link= tep_href_link("A2B_recurring_payment.php?id=".$_SESSION["card_id"]."&key=".$key, '', 'SSL');
        $link_return= tep_href_link("userinfo.php?subscribe=true", '', 'SSL');
        $link_cancel= tep_href_link("userinfo.php?subscribe=false", '', 'SSL');
    ?>

    <tr>
        <td align="center" colspan="2" class="tableBodyRight" >
            <form name="_xclick" action="<?php echo PAYPAL_PAYMENT_URL?>" method="post">
            <input type="hidden" name="cmd" value="_xclick-subscriptions">
            <input type="hidden" name="business" value="<?php echo $A2B->config['epayment_method']['paypal_subscription_account']?>">
            <input type="hidden" name="currency_code" value="<?php echo strtoupper(BASE_CURRENCY);?>">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="notify_url" value="<?php echo $link?>">
            <input type="hidden" name="return" value="<?php echo $link_return?>">
            <input type="hidden" name="cancel_return" value="<?php echo $link_cancel?>">
            <input type="hidden" name="item_name" value="<?php echo $desc?>">
            <input type="hidden" name="a3" value="<?php echo $amount_subscribe?>">
            <input type="hidden" name="p3" value="<?php echo $A2B->config['epayment_method']['paypal_subscription_period_number']?>">
            <input type="hidden" name="t3" value="<?php echo $A2B->config['epayment_method']['paypal_subscription_time_period']?>">
            <input type="hidden" name="src" value="1">
            <input type="hidden" name="sra" value="1">
            <input type="submit" class="form_input_button" value="<?php echo gettext("SUBSCRIPTION");?>">
            </form>
        </td>
    </tr>

    <?php } ?>
</table>

<?php } else { ?>
<br></br><br></br>

<?php } ?>
</div>

<?php
$smarty->display('footer.tpl');
