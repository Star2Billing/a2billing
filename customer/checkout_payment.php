<?php
include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./lib/epayment/classes/payment.php");
include ("./lib/epayment/classes/order.php");
include ("./lib/epayment/classes/currencies.php");
include ("./lib/epayment/includes/general.php");
include ("./lib/epayment/includes/html_output.php");
include ("./lib/epayment/includes/sessions.php");
include ("./lib/epayment/includes/loadconfiguration.php");
include ("./lib/epayment/includes/configure.php");
include ("./lib/customer.smarty.php");

if (! has_rights (ACX_ACCESS)){
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}
$HD_Form = new FormHandler("cc_payment_methods","payment_method");

getpost_ifset(array('amount','item_name','item_number','currency_code'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

// #### HEADER SECTION
$smarty->display( 'main.tpl');

$HD_Form -> create_toppage ($form_action);

$payment_modules = new payment;
$order = new order($amount);

?>
<script language="javascript"><!--
var selected;

function selectRowEffect(object, buttonSelect) {
	if (!selected) {
		if (document.getElementById) {
			selected = document.getElementById('defaultSelected');
		} else {
			selected = document.all['defaultSelected'];
		}
	}

	if (selected) selected.className = 'moduleRow';
	object.className = 'moduleRowSelected';
	selected = object;

	// one button is not an array
	if (document.checkout_payment.payment[0]) {
		document.checkout_payment.payment[buttonSelect].checked=true;
	} else {
		document.checkout_payment.payment.checked=true;
	}
}

function rowOverEffect(object) {
	if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
	if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>

<?php echo $payment_modules->javascript_validation(); ?>

<br>
<center>
<?php 
	echo $PAYMENT_METHOD;
?>
<br>
<form name="checkout_payment" action="checkout_amount.php" method="post" onsubmit="return check_form();">
    <input name="amount" type=hidden value="<?php echo $amount?>">
    <input name="item_name" type=hidden value="<?php echo $item_name?>">
    <input name="item_number" type=hidden value="<?php echo $item_number?>">

    <table width="80%" cellspacing="0" cellpadding="2" align=center>
    <?php
	if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
  		write_log(LOGFILE_EPAYMENT, basename(__FILE__).' line:'.__LINE__." ERROR ".$error['title']." ".$error['error']);
	?>
      <tr>
        <td ><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" ><b><?php echo tep_output_string_protected($error['title']); ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBoxNotice">
          <tr class="infoBoxNoticeContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('clear.gif', '10', '1'); ?></td>
                <td class="main" width="100%" valign="top"><?php echo tep_output_string_protected($error['error']); ?></td>
                <td><?php echo tep_draw_separator('clear.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('clear.gif', '100%', '10'); ?></td>
      </tr>
      </table>
<?php
  }
?>
    <table class="infoBox" width="80%" cellspacing="0" cellpadding="2" align=center>

<?php
  $selection = $payment_modules->selection();
  
  if (sizeof($selection) > 1) {
?>

              <tr height=10>
                <td class="infoBoxHeading">&nbsp;</td>
                <td class="infoBoxHeading"  width="50%" valign="top"><?php echo "Payment Method"; ?></td>
                <td class="infoBoxHeading" width="50%" valign="top" align="right"><b><?php echo "Please Select"; ?></b><br></td>

                <td class="infoBoxHeading">&nbsp;</td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td>&nbsp;</td>
                <td class="main" width="100%" colspan="2"><?php echo "This is currently the only payment method available to use on this order."; ?></td>
                <td>&nbsp;</td>
              </tr>

<?php
  }

  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
?>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if ( ($selection[$i]['id'] == $payment) || ($n == 1) ) {
      echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
    } else {
      echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
    }
?>
                    <td width="10">&nbsp;</td>
                    <td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b></td>
                    <td class="main" align="right">
<?php
    if (sizeof($selection) > 1) {
      echo tep_draw_radio_field('payment', $selection[$i]['id']);
    } else {
      echo tep_draw_hidden_field('payment', $selection[$i]['id']);
    }
?>
                    </td>
                    <td width="10">&nbsp;</td>
                  </tr>
<?php
    if (isset($selection[$i]['error'])) {
?>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                    <td width="10">&nbsp;</td>
                  </tr>
<?php
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
?>
                  <tr>
                    <td width="10">&nbsp;</td>
                    <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
<?php
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
                      <tr>
                        <td width="10">&nbsp;</td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                        <td>&nbsp;</td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        <td width="10">&nbsp;</td>
                      </tr>
<?php
      }
?>
                    </table></td>
                    <td width="10">&nbsp;</td>
                  </tr>

<?php
    }
?>

                </table></td>
                <td>&nbsp;</td>
              </tr>
<?php
    $radio_buttons++;
  }
?>
      </table>
      <br>
      <?php  if (sizeof($selection) > 0){ ?>
      <table class="infoBox" width="80%" cellspacing="0" cellpadding="2" align=center>
          <tr height="20">
          <td  align=left class="main"> <b>Continue Checkout Procedure</b><br>to confirm this order. 

          </td>
          <td align=right halign=center >
            <input type="image" src="<?php echo Images_Path;?>/button_continue.gif" alt="Continue" border="0" title="Continue">
             &nbsp;</td>
          </tr>
         </table>
         <?php } ?>
     </form>

<?php 
// #### FOOTER SECTION
$smarty->display( 'footer.tpl');
?>
