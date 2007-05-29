<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/smarty.php");
include ("../lib/epayment/classes/payment.php");
include ("../lib/epayment/classes/objectinfo.php");
include ("../lib/epayment/classes/table_block.php");
include ("../lib/epayment/classes/box.php");
include ("../lib/epayment/includes/general.php");
include ("../lib/epayment/includes/html_output.php");


if (! has_rights (ACX_BILLING)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

/***********************************************************************************/
$nowDate = date("m/d/y");
$message = "";
if($_GET["result"]=="success")
{
	$message = gettext("Record updated successfully");
}
$instance_sub_table = new Table("cc_payment_methods", "payment_filename");
if (isset($_GET["id"]))
{
    $paymentMethodID = $_GET["id"];
}
else
{
    exit(gettext("Payment module ID not found"));
}
$QUERY = " id = ".$paymentMethodID;
$DBHandle  = DbConnect();
$return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
$paymentMethod = substr($return[0][0], 0, strrpos($return[0][0], '.'));

$instance_sub_table = new Table("cc_configuration", "payment_filename");
$QUERY = " active = 't'";

$return = null;
//$return = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);


///**************************************************

$action = (isset($_GET['action']) ? $_GET['action'] : '');
  if (tep_not_null($action))
  {
    switch ($action)
    {
      case 'save':
        while (list($key, $value) = each($_POST['configuration']))
        {
          $QUERY = "update cc_configuration set configuration_value = '" . $value . "' where configuration_key = '" . $key . "'";
          $instance_sub_table -> Update_table($DBHandle, "configuration_value = '" . $value . "'","configuration_key = '" . $key . "'" );          
        }
        tep_redirect("A2B_entity_payment_settings.php?".'method=' . $paymentMethod."&id=".$_GET['id']."&result=success");
      break;
    }
  }

///**************************************************


$payment_modules = new payment($paymentMethod);
$module_keys = $payment_modules->keys();

$keys_extra = array();
$instance_sub_table = new Table("cc_configuration", "configuration_title, configuration_value, configuration_description, use_function, set_function");


for ($j=0, $k=sizeof($module_keys); $j<$k; $j++)
{
    $QUERY = " configuration_key = '" . $module_keys[$j] . "'";
    $key_value = $instance_sub_table -> Get_list($DBHandle, $QUERY, 0);
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
while (list($key, $value) = each($mInfo->keys))
{
    $keys .= '<b>' . $value['title'] . '</b><br>' . $value['description'] . '<br>';
    if ($value['set_function'])
    {
        eval('$keys .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
    }
    else
    {
        $keys .= tep_draw_input_field('configuration[' . $key . ']', $value['value']);
    }
    $keys .= '<br><br>';
}

$keys = substr($keys, 0, strrpos($keys, '<br><br>'));
$heading[] = array('text' => '<b>' . $mInfo->title . '</b>');
$contents = array('form' => tep_draw_form('modules', "A2B_entity_payment_settings.php?".'method=' . $paymentMethod . '&action=save&id='.$_GET["id"]));
$contents[] = array('text' => $keys);
$contents[] = array('align' => 'center', 'text' => '<br><input type=submit name=submitbutton value=Update class=form_input_button> <a href="A2B_entity_payment_configuration.php?atmenu=payment"><input type="button" name="cancelbutton" value="Cancel" class="form_input_button"></a>');

// #### HEADER SECTION
$smarty->display('main.tpl');

echo $CC_help_payment_config;

// #### PAYMENT METHOD SECTION
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



<?

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
