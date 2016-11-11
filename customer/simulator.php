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
include 'lib/Class.RateEngine.php';
include 'lib/customer.smarty.php';

if (!has_rights(ACX_SIMULATOR)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, id, tariff, currency FROM cc_card WHERE username = '" . $_SESSION["pr_login"] . "' AND uipass = '" . $_SESSION["pr_password"] . "'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max->Execute($QUERY);
if ($resmax)
    $numrow = $resmax->RecordCount();

if ($numrow == 0)
    exit ();
$customer_info = $resmax->fetchRow();

if ($customer_info[14] != "1" && $customer_info[14] != "8") {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('posted', 'tariffplan', 'balance', 'id_cc_card', 'called'));

$id_cc_card = $customer_info[15];
$tariffplan = $customer_info[16];
$balance = $customer_info[1];
$currency = $customer_info[17];
$QUERY = "SELECT value from cc_currencies where currency='$currency'";
$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max->Execute($QUERY);
if ($resmax)
    $numrow = $resmax->RecordCount();

if ($numrow == 0)
    exit ();
$currency_value = $resmax->fetchRow()[0];

$FG_DEBUG = 0;
$DBHandle = DbConnect();

if ($called && $id_cc_card) {

    $calling = $called;

    if (strlen($calling) > 2 && is_numeric($calling)) {

        $A2B->DBHandle = DbConnect();
        $instance_table = new Table();
        $A2B->set_instance_table($instance_table);
        $num = 0;

        $result = $A2B->instance_table->SQLExec($A2B->DBHandle, "SELECT username, tariff FROM cc_card where id='$customer_info[15]'");
        if (!is_array($result) || count($result) == 0) {
            echo gettext("Error card !!!");
            exit ();
        }

        $A2B->cardnumber = $result[0][0];
        $A2B->credit = $balance;
        if ($FG_DEBUG == 1)
            echo "cardnumber = " . $result[0][0] . " - balance=$balance<br>";

        if ($A2B->callingcard_ivr_authenticate_light($error_msg)) {
            if ($FG_DEBUG == 1)
                $RateEngine->debug_st = 1;

            $RateEngine = new RateEngine();
            $RateEngine->webui = 1;

            $A2B->agiconfig['accountcode'] = $A2B->cardnumber;
            $A2B->agiconfig['use_dnid'] = 1;
            $A2B->agiconfig['say_timetocall'] = 0;
            $A2B->dnid = $A2B->destination = $calling;
            if ($A2B->removeinterprefix)
                $A2B->destination = $A2B->apply_rules($A2B->destination);

            $resfindrate = $RateEngine->rate_engine_findrates($A2B, $A2B->destination, $result[0][1]);
            if ($FG_DEBUG == 1)
                echo "resfindrate=$resfindrate";

            // IF FIND RATE
            if ($resfindrate != 0) {
                $res_all_calcultimeout = $RateEngine->rate_engine_all_calcultimeout($A2B, $A2B->credit);
                if ($FG_DEBUG == 1)
                    print_r($RateEngine->ratecard_obj);
            }

        }

    }
}

/**************************************************************/

$instance_table_tariffname = new Table("cc_tariffplan", "id, tariffname");
$FG_TABLE_CLAUSE = "";
$list_tariffname = $instance_table_tariffname->Get_list($DBHandle, $FG_TABLE_CLAUSE, "tariffname", "ASC", null, null, null, null);
$nb_tariffname = count($list_tariffname);

$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_simulator_rateengine;

?>

<center>

<?php echo $error_msg; ?>

<br><br>
<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
    <TR>
      <TD style="border-bottom: medium dotted #8888CC" colspan="2"> <B><?php echo gettext("Simulator");?></B></TD>
    </TR>
    <FORM NAME="theFormFilter" action="<?php echo $PHP_SELF?>">
    <tr>
        <td height="31" class="bgcolor_009" style="padding-left: 5px;">
                <br>
                <font class="fontstyle_008"><?php echo gettext("Enter the number you wish to call");?>&nbsp;:</font>
                <INPUT type="text" name="called" value="<?php echo $called;?>" class="form_input_text">
                <br>
                <?php if (false) { ?>
                <br>
                <font color="white"><b><?php echo gettext("YOUR BALANCE");?>&nbsp;:</b></font>
                <INPUT type="text" name="balance" value="<?php if (!isset($balance)) echo "10"; else echo $balance;?>" class="form_input_text">
                <?php } ?>
                <br><br>

        </td>
        <td height="31" class="bgcolor_009" style="padding-left: 5px;">
            <span class="bar-search">
            <input type="submit" class="form_input_button" value="<?php echo gettext("SEARCH");?>">
            </span></td>
    </tr>
    </FORM>
</table>

<?php if ( (is_array($RateEngine->ratecard_obj)) && (!empty($RateEngine->ratecard_obj)) ) {

if ($FG_DEBUG == 1) print_r($RateEngine->ratecard_obj);

$arr_ratecard = array('idtariffgroup', 'cc_tariffgroup_plan.idtariffplan', 'tariffname', 'Destination', 'cc_ratecard.id' , 'dialprefix', 'destination', 'buyrate', 'buyrateinitblock', 'buyrateincrement', 'Cost per minute', 'initblock', 'billingblock', 'connectcharge', 'disconnectcharge', 'stepchargea', 'chargea', 'timechargea', 'billingblocka', 'stepchargeb', 'chargeb', 'timechargeb', 'billingblockb', 'stepchargec', 'chargec', 'timechargec', 'billingblockc', 'tp_id_trunk', 'tp_trunk', 'providertech', 'tp_providerip', 'tp_removeprefix');

$FG_TABLE_ALTERNATE_ROW_COLOR[0]='#CDC9C9';
$FG_TABLE_ALTERNATE_ROW_COLOR[1]='#EEE9E9';
?>
 <br>
      <table width="65%" border="0" align="center" cellpadding="0" cellspacing="0">

        <TR>
          <TD style="border-bottom: medium dotted #FF4444" colspan="2"> <B><font color="red" size="3"><?php echo gettext("Simulator found a rate for your destination");?></font></B></TD>
        </TR>

        <?php if (count($RateEngine->ratecard_obj)>1) { ?>
        <TR>
            <td height="15" class="bgcolor_010" style="padding-left: 5px;" colspan="2">
                <font class="fontstyle_008"><?php echo gettext("We found several destinations:");?></font>
            </td>
        </TR>
        <?php } ?>

        <?php
        for ($j=0;$j<count($RateEngine->ratecard_obj);$j++) {

            $result = $A2B->instance_table -> SQLExec ($A2B -> DBHandle,
                "SELECT destination FROM cc_prefix where prefix='".$RateEngine->ratecard_obj[$j][5]."'");
            if (is_array($result)){
                $destination = $result[0][0];
            }
        ?>
            <TR>
              <td height="15" bgcolor="" style="padding-left: 5px;" colspan="2">

            </td>
            </TR>
            <TR>
                <td height="15" class="bgcolor_011" style="padding-left: 5px;" colspan="2">
                    <b><?php echo gettext("DESTINATION");?>&nbsp;:#<?php echo $j+1;?></b>
                </td>
            </TR>
            <tr>
                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px;">
                    <b><?php echo gettext("CallTime available");?></b>
                </td>
                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px;">
                    <?php echo display_minute($RateEngine->ratecard_obj[$j]['timeout']);?> <?php echo gettext("Minutes");?>
                </td>
            </tr>

            <tr>
                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[0]?>" style="padding-left: 5px;"><b><?php echo $arr_ratecard[3];?></b></td>
                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[0]?>" style="padding-left: 5px;">
                    <?php echo $destination;?>
                </td>
            </tr>

            <tr>
                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px;">
                    <b><?php echo $arr_ratecard[10];?></b>
                </td>
                <td height="15" bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>" style="padding-left: 5px;">
                    <?php echo round($RateEngine->ratecard_obj[$j][12]/$currency_value,4) ;?> <?php echo $currency; ?>
                </td>
            </tr>

        <?php } ?>

      </table>
      <div align="center">
        <?php  } ?>

        <?php  if (count($RateEngine->ratecard_obj)==0) {
        if ($called) {
        ?>
        <span style="font-weight: bold">	<img src="<?php echo Images_Path_Main ?>/kicons/button_cancel.gif" alt="a" width="32" height="32"/> <?php echo gettext("The number, you have entered, is not correct!");?>  </span>
        <?php  } ?>
        <?php  } ?>

        <br><br><br><br>
      </div>

</center>

<?php

$smarty->display( 'footer.tpl');
