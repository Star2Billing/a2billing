<?php

include(dirname(__FILE__).'/../includes/methods/plugnpay.php');

class plugnpay {
    var $code, $title, $description, $enabled, $sort_order;
    var $accepted_cc, $card_types, $allowed_types;

	// class constructor
    function plugnpay() {
      $this->code = 'plugnpay';
      $this->title = MODULE_PAYMENT_PLUGNPAY_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_PLUGNPAY_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PLUGNPAY_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PLUGNPAY_STATUS == 'True') ? true : false);
      $this->accepted_cc = MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC;

      //array for credit card selection
      $this->card_types = array('Amex' => MODULE_PAYMENT_PLUGNPAY_TEXT_AMEX,
                                'Mastercard' => MODULE_PAYMENT_PLUGNPAY_TEXT_MASTERCARD,
                                'Discover' => MODULE_PAYMENT_PLUGNPAY_TEXT_DISCOVER,
                                'Visa' => MODULE_PAYMENT_PLUGNPAY_TEXT_VISA);
				
      $this->allowed_types = array();

      // Credit card pulldown list
      $cc_array = explode(', ', MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC);
      while (list($key, $value) = each($cc_array)) {
        $this->allowed_types[$value] = $this->card_types[$value];
      }

      // Processing via PlugnPay API 
      //$this->form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false);
      $this->form_action_url = tep_href_link('checkout_process.php', '', 'SSL', false);
      //$this->form_action_url = PLUGNPAY_PAYMENT_URL;
    }

	// class methods

	//concatenate to get CC images
	function get_cc_images() {
		$cc_images = '';
		reset($this->allowed_types);
		while (list($key, $value) = each($this->allowed_types)) {
			$cc_images .= tep_image(DIR_WS_ICONS . $key . '.gif', $value);
		}
		return $cc_images;
	}

	function javascript_validation() {
		#      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
		#            '    var cc_owner = document.checkout_payment.plugnpay_cc_owner.value;' . "\n" .
		#            '    var cc_number = document.checkout_payment.plugnpay_cc_number.value;' . "\n" .
		#            '    var cc_cvv = document.checkout_payment.cvv.value;' . "\n" .
		#            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
		#            '      error_message = error_message + "' . MODULE_PAYMENT_PLUGNPAY_TEXT_JS_CC_OWNER . '";' . "\n" .
		#            '      error = 1;' . "\n" .
		#            '    }' . "\n" .
		#            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
		#            '      error_message = error_message + "' . MODULE_PAYMENT_PLUGNPAY_TEXT_JS_CC_NUMBER . '";' . "\n" .
		#            '      error = 1;' . "\n" .
		#            '    }' . "\n" .
		#            '    if (cc_cvv != "" && cc_cvv.length < "3") {' . "\n".
		#            '      error_message = error_message + "' . MODULE_PAYMENT_PLUGNPAY_TEXT_JS_CC_CVV . '";' . "\n" .
		#            '      error = 1;' . "\n" .
		#            '    }' . "\n" .
		#            '  }' . "\n";
		#
      return $js;
    }

    function selection() {
      global $order;

      reset($this->allowed_types);
      while (list($key, $value) = each($this->allowed_types)) {
        $card_menu[] = array('id' => $key, 'text' => $value);
      }

      if (MODULE_PAYMENT_PLUGNPAY_PAYMETHOD == 'onlinecheck') {
        # set accttype menu
        $accttype_menu[] = array('id' => 'checking', 'text' => 'checking');
        $accttype_menu[] = array('id' => 'savings', 'text' => 'savings');

        # set paytype menu
        $paytype_menu[] = array('id' => 'credit_card', 'text' => 'Credit Card');
        $paytype_menu[] = array('id' => 'echeck', 'text' => 'Electronic Check');
      }

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      if ((MODULE_PAYMENT_PLUGNPAY_PAYMETHOD == 'onlinecheck') && (MODULE_PAYMENT_PLUGNPAY_CVV == 'no')) {
	$selection = array('id' => $this->code,
		           'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images() . '&nbsp; or Electronic Check',
		           'fields' => array(
                                             // credit & echeck selection
                                             array('title' => '<b>Select Your Method Of Payment:</b>',
			                           'field' => ''),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_PAYTYPE,
			                           'field' => tep_draw_pull_down_menu('plugnpay_paytype', $paytype_menu)),
                                             // credit card stuff here
                                             array('title' => '&nbsp;<p><b>Credit Card Info:</b>',
			                           'field' => ''),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_TYPE,
			                           'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER,
			                           'field' => tep_draw_input_field('plugnpay_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER,
			                           'field' => tep_draw_input_field('plugnpay_cc_number')),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES,
			                           'field' => tep_draw_pull_down_menu('plugnpay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('plugnpay_cc_expires_year', $expires_year)),
                                            // echeck stuff here
                                             array('title' => '&nbsp;<p><b>Electronic Checking Info:</b>',
			                           'field' => ''),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ACCTTYPE,
			                           'field' => tep_draw_pull_down_menu('plugnpay_echeck_accttype', $accttype_menu)),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ROUTINGNUM,
			                            'field' => tep_draw_input_field('plugnpay_echeck_routingnum','',"SIZE=12, MAXLENGTH=9")),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ACCOUNTNUM,
			                           'field' => tep_draw_input_field('plugnpay_echeck_accountnum','',"SIZE=12")),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_CHECKNUM,
			                           'field' => tep_draw_input_field('plugnpay_echeck_checknum','',"SIZE=6"))
		                            ));
      }
      else if ((MODULE_PAYMENT_PLUGNPAY_PAYMETHOD == 'onlinecheck') && (MODULE_PAYMENT_PLUGNPAY_CVV == 'yes')) {
	$selection = array('id' => $this->code,
		           'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images() . '&nbsp; or Electronic Check',
		           'fields' => array(
                                             // credit & echeck selection
                                             array('title' => '<b>Select Your Method Of Payment:</b>',
			                           'field' => ''),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_PAYTYPE,
			                           'field' => tep_draw_pull_down_menu('plugnpay_paytype', $paytype_menu)),
                                             // credit card stuff here
                                             array('title' => '&nbsp;<p><b>Credit Card Info:</b>',
			                           'field' => ''),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_TYPE,
			                           'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER,
			                           'field' => tep_draw_input_field('plugnpay_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER,
			                           'field' => tep_draw_input_field('plugnpay_cc_number')),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES,
			                           'field' => tep_draw_pull_down_menu('plugnpay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('plugnpay_cc_expires_year', $expires_year)),
                                             array('title' => 'CVV number ' . ' ' .'<a href="javascript:window.open(\'' . 'cvv.php' . '\')">' . '<u><i>' . '(' . MODULE_PAYMENT_PLUGNPAY_TEXT_CVV_LINK . ')' . '</i></u></a>',
			'field' => tep_draw_input_field('cvv','',"SIZE=4, MAXLENGTH=4")),
                                             // echeck stuff here
                                             array('title' => '&nbsp;<p><b>Electronic Checking Info:</b>',
			                           'field' => ''),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ACCTTYPE,
			                           'field' => tep_draw_pull_down_menu('plugnpay_echeck_accttype', $accttype_menu)),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ROUTINGNUM,
			                            'field' => tep_draw_input_field('plugnpay_echeck_routingnum','',"SIZE=12, MAXLENGTH=9")),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ACCOUNTNUM,
			                           'field' => tep_draw_input_field('plugnpay_echeck_accountnum','',"SIZE=12")),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_CHECKNUM,
			                           'field' => tep_draw_input_field('plugnpay_echeck_checknum','',"SIZE=6"))
		                            ));
      }
      else if (MODULE_PAYMENT_PLUGNPAY_CVV == 'no') {
	$selection = array('id' => $this->code,
		           'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images(),
		           'fields' => array(array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_TYPE,
			                           'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER,
			                            'field' => tep_draw_input_field('plugnpay_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER,
			                           'field' => tep_draw_input_field('plugnpay_cc_number')),
		                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES,
			                           'field' => tep_draw_pull_down_menu('plugnpay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('plugnpay_cc_expires_year', $expires_year))
                                            ));
      }
      else {
        $selection = array('id' => $this->code,
                           'module' => $this->title . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $this->get_cc_images(),
                           'fields' => array(array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_TYPE,
                                                   'field' => tep_draw_pull_down_menu('credit_card_type', $card_menu)),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER,
                                                   'field' => tep_draw_input_field('plugnpay_cc_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER,
                                                   'field' => tep_draw_input_field('plugnpay_cc_number')),
                                             array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES,
                                                   'field' => tep_draw_pull_down_menu('plugnpay_cc_expires_month', $expires_month) . '&nbsp;' . tep_draw_pull_down_menu('plugnpay_cc_expires_year', $expires_year)),
                                             array('title' => 'CVV number ' . ' ' .'<a href="#" onclick="javascript:window.open(\'' . 'cvv.php' . '\', \'CardNumberSelection\',\'width=600,height=280,top=20,left=100,scrollbars=1\');">' . '<u><i>' . '(' . MODULE_PAYMENT_PLUGNPAY_TEXT_CVV_LINK . ')' . '</i></u></a>',
'field' => tep_draw_input_field('cvv','',"SIZE=4, MAXLENGTH=4"))
                                            ));
      }

      return $selection;
    }

    function pre_confirmation_check() {
      global $_POST, $cvv;

      if ((MODULE_PAYMENT_PLUGNPAY_PAYMETHOD == 'onlinecheck') && ($_POST['plugnpay_paytype'] != 'credit_card')) {
        $this->plugnpay_paytype = $_POST['plugnpay_paytype'];
        $this->echeck_accttype = $_POST['plugnpay_echeck_accttype'];
        $this->echeck_accountnum = $_POST['plugnpay_echeck_accountnum'];
        $this->echeck_routingnum = $_POST['plugnpay_echeck_routingnum'];
        $this->echeck_checknum = $_POST['plugnpay_echeck_checknum'];
      }
      else {
        # Note: section assumes the payment method is credit card
        include(dirname(__FILE__).'/../classes/cc_validation.php');
        $cc_validation = new cc_validation();
        $result = $cc_validation->validate($_POST['plugnpay_cc_number'], $_POST['plugnpay_cc_expires_month'], $_POST['plugnpay_cc_expires_year'], $_POST['cvv'], $_POST['credit_card_type']);
        
        $error = '';
        echo $result;
        
        switch ($result) {
          case -1:
            $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc_validation->cc_number, 0, 4));
            break;
          case -2:
          case -3:
          case -4:
            $error = TEXT_CCVAL_ERROR_INVALID_DATE;
            break;
          case -5:
            $error = TEXT_CCVAL_ERROR_CARD_TYPE_MISMATCH;
            break;
          case -6;
            $error = TEXT_CCVAL_ERROR_CVV_LENGTH;
            break; 
          case false:
            $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
            break;
        }
      if ( ($result == false) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&authorizenet_cc_owner=' . urlencode($_POST['authorizenet_cc_owner']) . '&authorizenet_cc_expires_month=' . $_POST['authorizenet_cc_expires_month'] . '&authorizenet_cc_expires_year=' . $_POST['authorizenet_cc_expires_year'];
		$payment_error_return .= '&amount=' . $_POST['amount'].'&item_name=' . $_POST['item_name'].'&item_number=' . $_POST['item_number'];
		$payment_error_return .= '&item_id='.$_POST['item_id'].'&item_type='.$_POST['item_type'];
        tep_redirect(tep_href_link("checkout_payment.php", $payment_error_return, 'SSL', true, false));
      }

        $this->cc_card_type = $cc_validation->cc_type;
        $this->cc_card_number = $cc_validation->cc_number;
        $this->cc_expiry_month = $cc_validation->cc_expiry_month;
        $this->cc_expiry_year = $cc_validation->cc_expiry_year;
        $card_cvv = $_POST['cvv'];
      }
    }

    function confirmation() {
      global $_POST, $card_cvv;

      if ((MODULE_PAYMENT_PLUGNPAY_PAYMETHOD == 'onlinecheck') && ($this->plugnpay_paytype == 'echeck')) {
        $confirmation = array('title' => $this->title . ': Electronic Check Payments',
                              'fields' => array(array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ACCTTYPE,
                                                      'field' => $this->echeck_accttype),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ROUTINGNUM,
                                                      'field' => $this->echeck_routingnum),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_ACCOUNTNUM,
                                                      'field' => $this->echeck_accountnum),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ECHECK_CHECKNUM,
                                                      'field' => $this->echeck_checknum)
                                                ));
      }
      else if (MODULE_PAYMENT_PLUGNPAY_CVV == 'no') {
        $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                              'fields' => array(array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER,
                                                      'field' => $_POST['plugnpay_cc_owner']),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER,
                                                      'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES,
                                                      'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['plugnpay_cc_expires_month'], 1, '20' . $_POST['plugnpay_cc_expires_year'])))));
      }  
      else {
        $card_cvv=$_POST['cvv'];
        $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                              'fields' => array(array('title' => 'CVV number',
                                                      'field' => $_POST['cvv']),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_OWNER,
                                                      'field' => $_POST['plugnpay_cc_owner']),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_NUMBER,
                                                      'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                                array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_CREDIT_CARD_EXPIRES,
                                                      'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['plugnpay_cc_expires_month'], 1, '20' . $_POST['plugnpay_cc_expires_year'])))));
        $card_cvv=$_POST['cvv'];
      }
      return $confirmation;
    }

    function process_button($transactionID = 0, $key = "")  {
      // Change made by using PlugnPay API Connection
      $card_cvv=$_POST['cvv'];
	  
	  $process_button_string = tep_draw_hidden_field('credit_card_type', $_POST['credit_card_type']) . 
      						   tep_draw_hidden_field('card_owner', $_POST['plugnpay_cc_owner']) .  
      						   tep_draw_hidden_field('card_cvv', $_POST['cvv']) .  
      						   tep_draw_hidden_field('transactionID', $transactionID) .  
      						   tep_draw_hidden_field('key', $key) .   
      						   tep_draw_hidden_field('sess_id', tep_session_id()) . 
                               tep_draw_hidden_field('card_number', $this->cc_card_number) .
                               tep_draw_hidden_field('card_exp', $this->cc_expiry_month . substr($this->cc_expiry_year, -2));

      $process_button_string .= tep_draw_hidden_field(tep_session_name(), tep_session_id());
      return $process_button_string;
    }

    function before_process() {
      global $response;
      # Note: $response is an array that holds various pieces if cURL response info
      #       $response[0] will hold the entire response string from the pnpremote.cgi script

      ## Note: Enable this code to record the response string to a text file for debug purposes
      if (MODULE_PAYMENT_PLUGNPAY_TESTMODE == 'Test And Debug') {
        $filename = './plugnpay_debug.txt';
        $fp = fopen($filename, "a");
        $write = fputs($fp, "POSTAUTH: $response[0]\n\n");
        fclose($fp);
      }

      parse_str($response[0]);

      if($FinalStatus == 'success') {
        tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$insert_id . "'"); //Remove order
        #tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode('SUCCESSFUL - ORDER APPROVED'), 'SSL', true, false));  // uncomment this line for testing.
      }
      else if($FinalStatus == 'badcard') {
        tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode('Your authorization was declined.  Please try another card.') . urlencode(" -- $MErrMsg"), 'SSL', true, false));
      }
      else if($FinalStatus == 'fraud') {
        tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode('Your transaction was rejected.  Please contact the merchant for ordering assistance.') . urlencode(" -- $MErrMsg"), 'SSL', true, false));
      }
      else if($FinalStatus == 'problem') {
        tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode('There was an error processing your transaction.  Please contact the merchant for ordering assistance.') . urlencode(" -- $MErrMsg"), 'SSL', true, false));
      }
      else {
        if ($response[0] == '') {
          tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode("There was an unspecified error processing your transaction.<br>Received empty cURL response - check cURL connectivity to PnP server.") . urlencode(" -- $MErrMsg"), 'SSL', true, false));
        }
        else {
          tep_redirect(tep_href_link("checkout_payment.php", 'error_message=' . urlencode("There was an unspecified error processing your transaction.") . urlencode(" -- $MErrMsg"), 'SSL', true, false));
        }
      }
    }
    
    function get_OrderStatus()
    {
        global $pnp_transaction_array;
        
        if ($pnp_transaction_array['FinalStatus'] == "success"){
			return 2;
		} elseif ($pnp_transaction_array['FinalStatus'] == "badcard") {
			return -2;
		} elseif ($pnp_transaction_array['FinalStatus'] == "fraud") {
			return -2;
		} elseif ($pnp_transaction_array['FinalStatus'] == "problem") {
			return -1;
		} else {
		    // this should not happen
		    return -1;
		}
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $_GET;

      $error = array('title' => MODULE_PAYMENT_PLUGNPAY_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));
      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PLUGNPAY_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PlugnPay Module', 'MODULE_PAYMENT_PLUGNPAY_STATUS', 'True', 'Do you want to accept payments through PlugnPay?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Login Username', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'Your Login Name', 'Enter your PlugnPay account username', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'Enter Your Email Address', 'The email address you want PlugnPay conformations sent to', '6', '0', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('cURL Setup', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'Not Compiled', 'Whether cURL is compiled into PHP or not.  Windows users, select not compiled.', '6', '0', 'tep_cfg_select_option(array(\'Not Compiled\', \'Compiled\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('cURL Path', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'The Path To cURL', 'For Not Compiled mode only, input path to the cURL binary (i.e. c:/curl/curl)', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Test And Debug\', \'Production\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Require CVV', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'yes', 'Ask For CVV information', '6', '0', 'tep_cfg_select_option(array(\'yes\', \'no\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Method', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'credit', 'Transaction method used for processing orders.<br><b>NOTE:</b> Selecting \'onlinecheck\' assumes you\'ll offer \'credit\' as well.', '6', '0', 'tep_cfg_select_option(array(\'credit\', \'onlinecheck\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Authorization Type', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'authpostauth', 'Credit card processing mode', '6', '0', 'tep_cfg_select_option(array(\'authpostauth\', \'authonly\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order Of Display', 'MODULE_PAYMENT_PLUGNPAY_SORT_ORDER', '1', 'The order in which this payment type is dislayed. Lowest is displayed first.', '6', '0' , now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Customer Notifications', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'yes', 'Should PlugnPay not email a receipt to the customer?', '6', '0', 'tep_cfg_select_option(array(\'yes\', \'no\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accepted Credit Cards', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC', 'Mastercard, Visa', 'The credit cards you currently accept', '6', '0', '_selectOptions(array(\'Amex\',\'Discover\', \'Mastercard\', \'Visa\'), ', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }

    function keys() {
      return array('MODULE_PAYMENT_PLUGNPAY_STATUS', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC');
    }
	
	function old_keys() {
      return array('MODULE_PAYMENT_PLUGNPAY_STATUS', 'MODULE_PAYMENT_PLUGNPAY_LOGIN', 'MODULE_PAYMENT_PLUGNPAY_PUBLISHER_EMAIL', 'MODULE_PAYMENT_PLUGNPAY_CURL', 'MODULE_PAYMENT_PLUGNPAY_CURL_PATH', 'MODULE_PAYMENT_PLUGNPAY_TESTMODE', 'MODULE_PAYMENT_PLUGNPAY_CVV', 'MODULE_PAYMENT_PLUGNPAY_PAYMETHOD', 'MODULE_PAYMENT_PLUGNPAY_CCMODE', 'MODULE_PAYMENT_PLUGNPAY_SORT_ORDER', 'MODULE_PAYMENT_PLUGNPAY_DONTSNDMAIL', 'MODULE_PAYMENT_PLUGNPAY_ACCEPTED_CC');
    }
    
    function get_CurrentCurrency()
    {
        $my_currency = strtoupper($GLOBALS['A2B']->config['global']['base_currency']);
        return $my_currency;
    }

  }

// PlugnPay Consolidated Credit Card Checkbox Implementation
// Code from UPS Choice v1.7
function _selectOptions($select_array, $key_value, $key = '') {
  for ($i=0; $i<(sizeof($select_array)); $i++) {
    $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
    $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
    $key_values = explode(", ", $key_value);
    if (in_array($select_array[$i], $key_values)) $string .= ' checked="checked"';
    $string .= '> ' . $select_array[$i];
  } 
  return $string;
}


