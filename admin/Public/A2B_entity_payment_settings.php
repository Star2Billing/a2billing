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
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");
include ("../lib/epayment/classes/payment.php");
include ("../lib/epayment/classes/objectinfo.php");
include ("../lib/epayment/classes/table_block.php");
include ("../lib/epayment/classes/box.php");
include ("../lib/epayment/includes/general.php");
include ("../lib/epayment/includes/html_output.php");

if (!has_rights(ACX_BILLING)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('action', 'configuration', 'id', 'configuration', 'result'));


$nowDate = date("m/d/y");
$message = "";
if ($result == "success") {
	$message = gettext("Record updated successfully");
}
$instance_sub_table = new Table("cc_payment_methods", "payment_filename");
if (!empty ($id)) {
	$paymentMethodID = $id;
} else {
	exit (gettext("Payment module ID not found"));
}

$QUERY = " id = " . $paymentMethodID;
$DBHandle = DbConnect();
$return = $instance_sub_table->Get_list($DBHandle, $QUERY, 0);
$paymentMethod = substr($return[0][0], 0, strrpos($return[0][0], '.'));

$instance_sub_table = new Table("cc_configuration", "payment_filename");
$QUERY = " active = 't'";

$return = null;

if (tep_not_null($action)) {
	switch ($action) {
		case 'save' :
			while (list ($key, $value) = each($configuration)) {
				if ($key == 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC') {
					$value = join($value, ', ');
				}
				$instance_sub_table->Update_table($DBHandle, "configuration_value = '" . $value . "'", "configuration_key = '" . $key . "'");
			}
			tep_redirect("A2B_entity_payment_settings.php?" . 'method=' . $paymentMethod . "&id=" . $id . "&result=success");
			break;
	}
}


$payment_modules = new payment($paymentMethod);
$GLOBALS['paypal']->enabled = true;
$GLOBALS['moneybookers']->enabled = true;
$GLOBALS['authorizenet']->enabled = true;
$GLOBALS['worldpay']->enabled = true;
$GLOBALS['plugnpay']->enabled = true;
$GLOBALS['iridium']->enabled = true;
$module_keys = $payment_modules->keys();

$keys_extra = array ();
$instance_sub_table = new Table("cc_configuration", "configuration_title, configuration_value, configuration_description, use_function, set_function");

for ($j = 0, $k = sizeof($module_keys); $j < $k; $j++) {
	$QUERY_CLAUSE = " configuration_key = '" . $module_keys[$j] . "'";
	$key_value = $instance_sub_table->Get_list($DBHandle, $QUERY_CLAUSE, 0);
	$keys_extra[$module_keys[$j]]['title'] = $key_value[0]['configuration_title'];
	$keys_extra[$module_keys[$j]]['value'] = $key_value[0]['configuration_value'];
	$keys_extra[$module_keys[$j]]['description'] = $key_value[0]['configuration_description'];
	$keys_extra[$module_keys[$j]]['use_function'] = $key_value[0]['use_function'];
	$keys_extra[$module_keys[$j]]['set_function'] = $key_value[0]['set_function'];
}

$module_info['keys'] = $keys_extra;
$mInfo = new objectInfo($module_info);

$keys = '';
reset($mInfo->keys);
while (list ($key, $value) = each($mInfo->keys)) {
	$keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
	if ($value['set_function']) {
		eval ('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
	} else {
		$keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
	}
	$keys .= '<br><br>';
}

$keys = substr($keys, 0, strrpos($keys, '<br><br>'));
$heading[] = array (
	'text' => '<b>' . $mInfo->title . '</b>'
);
$contents = array (
	'form' => tep_draw_form('modules',
	"A2B_entity_payment_settings.php?" . 'method=' . $paymentMethod . '&action=save&id=' . $id
));
$contents[] = array (
	'text' => $keys
);
$contents[] = array (
	'align' => 'center',
	'text' => '<br><input type=submit name=submitbutton value=Update class=form_input_button> <a href="A2B_entity_payment_configuration.php?atmenu=payment"><input type="button" name="cancelbutton" value="Cancel" class="form_input_button"></a>'
);

$smarty->display('main.tpl');

echo $CC_help_payment_config;


echo $PAYMENT_METHOD;

?>

<table class="epayment_conf_table">
<tr class="form_head">
    <td><font color="#FFFFFF"><?php echo gettext("CONFIGURATION"); ?></font></td>
</tr>
<tr >
    <td><font color="Green"><b><?php echo $message ?></b></font></td>
</tr>

    <tr>
        <?php
             if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
             echo '            <td width="25%" valign="top">' . "\n";

             $box = new box;
             echo $box->infoBox($heading, $contents);
             echo '            </td>' . "\n";
             }
        ?>
    </tr>
</table>



<?php

$smarty->display('footer.tpl');

