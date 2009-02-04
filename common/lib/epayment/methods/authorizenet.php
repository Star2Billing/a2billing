<?php

include("./lib/epayment/includes/methods/authorizenet.php");

class authorizenet {
    var $code, $title, $description, $enabled;
    var $authorizeTable;

	// class constructor
    function authorizenet() {
		global $order;

		$this->authorizeTable = new Table;
		$this->code = 'authorizenet';
		$this->title = MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION;
		$this->enabled = ((MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True') ? true : false);
		// $this->enabled = true;
		// echo MODULE_PAYMENT_AUTHORIZENET_STATUS;
		$this->sort_order = 0;

		$this->form_action_url = AUTHORIZE_PAYMENT_URL;
    }

	// Authorize.net utility functions
	// DISCLAIMER:
	//     This code is distributed in the hope that it will be useful, but without any warranty;
	//     without even the implied warranty of merchantability or fitness for a particular purpose.

	// Main Interfaces:
	//
	// function InsertFP ($loginid, $txnkey, $amount, $sequence) - Insert HTML form elements required for SIM
	// function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp) - Returns Fingerprint.

	// compute HMAC-MD5
	// Uses PHP mhash extension. Pl sure to enable the extension
	// function hmac ($key, $data) {
	//   return (bin2hex (mhash(MHASH_MD5, $data, $key)));
	//}

	// Thanks is lance from http://www.php.net/manual/en/function.mhash.php
	//lance_rushing at hot* spamfree *mail dot com
	//27-Nov-2002 09:36
	//
	//Want to Create a md5 HMAC, but don't have hmash installed?
	//
	//Use this:

	function hmac ($key, $data)
	{
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing

		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key  = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;

		return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}
	// end code from lance (resume authorize.net code)

	// Calculate and return fingerprint
	// Use when you need control on the HTML output
	function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp, $currency = "") {
		return ($this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
	}

	// Inserts the hidden variables in the HTML FORM required for SIM
	// Invokes hmac function to calculate fingerprint.
	function InsertFP ($loginid, $txnkey, $amount, $sequence, $currency = "") {
		$tstamp = time ();
		$fingerprint = $this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

		$str = tep_draw_hidden_field('x_fp_sequence', $sequence) .
			tep_draw_hidden_field('x_fp_timestamp', $tstamp) .
			//tep_draw_hidden_field('x_tran_key', $txnkey) .
			tep_draw_hidden_field('x_fp_hash', $fingerprint);

		return $str;
	}
	// end authorize.net code

	// class methods
    function update_status() {
      global $order;
      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_AUTHORIZENET_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_AUTHORIZENET_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.authorizenet_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.authorizenet_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < 3) {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < 10) {' . "\n" .
            '      error_message = error_message + "' . MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $order;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }
      $order = new order();
      $today = getdate();
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }
      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER,
                                                 'field' => tep_draw_input_field('authorizenet_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER,
                                                 'field' => tep_draw_input_field('authorizenet_cc_number')),
                                           array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES,
                                                 'field' => tep_draw_pull_down_menu('authorizenet_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('authorizenet_cc_expires_year', $expires_year))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $_POST;

      include('./lib/epayment/classes/cc_validation.php');

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['authorizenet_cc_number'], $_POST['authorizenet_cc_expires_month'], $_POST['authorizenet_cc_expires_year']);
      $error = '';
      switch ($result) {
        case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
      }

      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&authorizenet_cc_owner=' . urlencode($_POST['authorizenet_cc_owner']) . '&authorizenet_cc_expires_month=' . $_POST['authorizenet_cc_expires_month'] . '&authorizenet_cc_expires_year=' . $_POST['authorizenet_cc_expires_year'];
		$payment_error_return .= '&amount=' . $_POST['amount'].'&item_name=' . $_POST['item_name'].'&item_number=' . $_POST['item_number'];

        tep_redirect(tep_href_link("checkout_payment.php", $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    function confirmation() {
      global $_POST;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER,
                                                    'field' => $_POST['authorizenet_cc_owner']),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER,
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES,
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['authorizenet_cc_expires_month'], 1, '20' . $_POST['authorizenet_cc_expires_year'])))));

      return $confirmation;
    }

    function process_button($transactionID = 0, $key = "") {
      global $_SERVER, $order, $customer_id;

      $sequence = $transactionID;
      $x_line_item = "Balance<|>Card Balance<|>1<|>".number_format($order->info['total'], 2)."<|>N";

      $process_button_string = tep_draw_hidden_field('x_Login', MODULE_PAYMENT_AUTHORIZENET_LOGIN) .
                               tep_draw_hidden_field('x_Card_Num', $this->cc_card_number) .
                               tep_draw_hidden_field('x_Exp_Date', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               tep_draw_hidden_field('x_Amount', number_format($order->info['total'], 2)) .
                               tep_draw_hidden_field('x_relay_response', "TRUE") .
                               tep_draw_hidden_field('x_Relay_URL', tep_href_link("checkout_process.php?sess_id=".session_id()."&transactionID=".$transactionID."&key=".$key, '', 'SSL', false)) .
                               tep_draw_hidden_field('x_Method', ((MODULE_PAYMENT_AUTHORIZENET_METHOD == 'Credit Card') ? 'CC' : 'ECHECK')) .
                               tep_draw_hidden_field('x_Version', '3.0') .
                               tep_draw_hidden_field('session_id', session_id()) .
                               tep_draw_hidden_field('x_Cust_ID', $_SESSION["pr_login"]) .
                               tep_draw_hidden_field('x_Email_Customer', ((MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER == 'True') ? 'TRUE': 'FALSE')) .
                               tep_draw_hidden_field('x_first_name', $order->billing['firstname']) .
                               tep_draw_hidden_field('x_last_name', $order->billing['lastname']) .
                               tep_draw_hidden_field('x_address', $order->billing['street_address']) .
                               tep_draw_hidden_field('x_city', $order->billing['city']) .
                               tep_draw_hidden_field('x_state', $order->billing['state']) .
                               tep_draw_hidden_field('x_zip', $order->billing['postcode']) .
                               tep_draw_hidden_field('x_country', $order->billing['country']['title']) .
                               tep_draw_hidden_field('x_phone', $order->customer['telephone']) .
                               tep_draw_hidden_field('x_email', $order->customer['email_address']) .

                               //tep_draw_hidden_field('x_line_item', $x_line_item) .
                               tep_draw_hidden_field('x_invoice_num', $sequence) .
                               tep_draw_hidden_field('x_decription', "This is the invoice for purchasing balance at A2Billing.") .

                               tep_draw_hidden_field('x_ship_to_first_name', $order->delivery['firstname']) .
                               tep_draw_hidden_field('x_ship_to_last_name', $order->delivery['lastname']) .
                               tep_draw_hidden_field('x_ship_to_address', $order->delivery['street_address']) .
                               tep_draw_hidden_field('x_ship_to_city', $order->delivery['city']) .
                               tep_draw_hidden_field('x_ship_to_state', $order->delivery['state']) .
                               tep_draw_hidden_field('x_ship_to_zip', $order->delivery['postcode']) .
                               tep_draw_hidden_field('x_ship_to_country', $order->delivery['country']['title']) .
                               tep_draw_hidden_field('x_Customer_IP', $_SERVER['REMOTE_ADDR']) .
                               $this->InsertFP(MODULE_PAYMENT_AUTHORIZENET_LOGIN, MODULE_PAYMENT_AUTHORIZENET_TXNKEY, number_format($order->info['total'], 2), $sequence);
      if (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') $process_button_string .= tep_draw_hidden_field('x_Test_Request', 'TRUE');

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
    }

    function before_process() {
      global $_POST;

      if ($_POST['x_response_code'] == '1') return;
      if ($_POST['x_response_code'] == '2') {
        tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode(MODULE_PAYMENT_AUTHORIZENET_TEXT_DECLINED_MESSAGE), 'SSL', true, false));
      }
      // Code 3 is an error - but anything else is an error too (IMHO)
      tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode(MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE), 'SSL', true, false));
    }
    
    function get_OrderStatus()
    {
        if ($_POST['x_response_code'] == "")
        {
            return -2;
        }
        switch($_POST['x_response_code'])
        {
            case "1":
                return 2;
            break;
            case "2":
                return -2;
            break;
            default:
                return -2;
            break;
        }
    }
    
    function get_CurrentCurrency()
    {
        return "USD";
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function keys() {
      return array('MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER');
    }
  }
?>
