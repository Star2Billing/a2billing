<?php
include(dirname(__FILE__).'/../includes/methods/moneybookers.php');

class moneybookers {
    var $code, $title, $description, $enabled;

	// class constructor
    function moneybookers() {
		global $order;
		
		$this->code = 'moneybookers';
		$this->title = MODULE_PAYMENT_MONEYBOOKERS_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_MONEYBOOKERS_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_MONEYBOOKERS_STATUS == 'True') ? true : false);
		// $this->enabled = true;

		$my_actionurl = 'https://www.moneybookers.com/app/payment.pl';
		
		if  (strlen(MODULE_PAYMENT_MONEYBOOKERS_REFID) <= '5')
		{
			$my_actionurl = $my_actionurl . '?rid=811621' ;
		}
		else
		{
			$my_actionurl = $my_actionurl . '?rid=' . MODULE_PAYMENT_MONEYBOOKERS_REFID;
		}
		
		$this->form_action_url = $my_actionurl;
	}

	// class methods
    function javascript_validation() {
    	return false;
    }

    function selection() {
      	return array('id' => $this->code, 'module' => $this->title);
    }

    function pre_confirmation_check() {
		return false;
    }

    function confirmation() {
		return false;
    }

    function process_button($transactionID = 0, $key= "") {
		global $order, $currencies, $currency;
		
		$my_language = MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE;
	
		$my_currency = strtoupper($GLOBALS['A2B']->config['global']['base_currency']);

		if (!in_array($my_currency, array('EUR', 'USD', 'GBP', 'HKD', 'SGD', 'JPY', 'CAD', 'AUD', 'CHF', 'DKK', 'SEK', 'NOK', 'ILS', 'MYR', 'NZD', 'TWD', 'THB', 'CZK', 'HUF', 'SKK', 'ISK', 'INR'))) {
			$my_currency = 'USD';
		}
		
		$currencyObject = new currencies();
		$amount_toprocess = number_format($order->info['total'] , $currencyObject->get_decimal_places($my_currency));
		$amount_toprocess = str_replace(',', '.', $amount_toprocess);
		
		$process_button_string = tep_draw_hidden_field('pay_to_email', MODULE_PAYMENT_MONEYBOOKERS_ID) .
								tep_draw_hidden_field('language', $my_language) .
								tep_draw_hidden_field('amount', $amount_toprocess) .
								tep_draw_hidden_field('currency', $my_currency) .
								tep_draw_hidden_field('detail1_description', STORE_NAME) .
								tep_draw_hidden_field('detail1_text', 'Order - ' . date('d. M Y - H:i')) .
								tep_draw_hidden_field('firstname', $order->billing['firstname']) .
								tep_draw_hidden_field('lastname', $order->billing['lastname'] ) .
								tep_draw_hidden_field('address', $order->billing['street_address']) .
								tep_draw_hidden_field('postal_code', $order->billing['postcode']) .
								tep_draw_hidden_field('city', $order->billing['city']) .
								tep_draw_hidden_field('country', $order->billing['country']['moneybookers']) .
								tep_draw_hidden_field('pay_from_email', $order->customer['email_address']); 
								if($transactionID != 0) {
									$process_button_string .= tep_draw_hidden_field('transaction_id', $transactionID);
								}
								$process_button_string .= tep_draw_hidden_field('status_url', tep_href_link("checkout_process.php?sess_id=".session_id()."&transactionID=".$transactionID."&key=".$key, '', 'SSL')) .
								tep_draw_hidden_field('return_url', tep_href_link("userinfo.php", '', 'SSL')) .
								tep_draw_hidden_field('cancel_url', tep_href_link("checkout_payment.php", '', 'SSL'));
		return $process_button_string;
    }

   
    function get_CurrentCurrency()
    {
     
        $my_currency = MODULE_PAYMENT_MONEYBOOKERS_CURRENCY;
        $base_currency = strtoupper($GLOBALS['A2B']->config['global']['base_currency']);
        if($my_currency =='Selected Currency' && in_array($base_currency, array('EUR', 'USD', 'GBP', 'HKD', 'SGD', 'JPY', 'CAD', 'AUD', 'CHF', 'DKK', 'SEK', 'NOK', 'ILS', 'MYR', 'NZD', 'TWD', 'THB', 'CZK', 'HUF', 'SKK', 'ISK', 'INR')) ){
        	$my_currency = $base_currency;
        }
        elseif (!in_array($my_currency,  array('EUR', 'USD', 'GBP', 'HKD', 'SGD', 'JPY', 'CAD', 'AUD', 'CHF', 'DKK', 'SEK', 'NOK', 'ILS', 'MYR', 'NZD', 'TWD', 'THB', 'CZK', 'HUF', 'SKK', 'ISK', 'INR'))) {
			$my_currency = 'USD';
		}
        return $my_currency;
    }
	
    function before_process()
    {
        return;
    }
	
    function get_OrderStatus()
    {
        // status of the transaction :
        // Failed= -2
        // Canceled = -1;
        // Pending = 0
        // Scheduled = 1
        // Processed = 2
        if ($_POST['status'] != "")
        {
            return $_POST['status'];
        }
        else
        {
            return -2;
        }
    }


    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable moneybookers Module', 'MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', 'Do you want to accept moneybookers payments?', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('E-Mail Address', 'MODULE_PAYMENT_MONEYBOOKERS_ID', '', 'The eMail address to use for the moneybookers service', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Referral ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', '', 'Your personal Referral ID from moneybookers.com', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Language', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', '6', '6', 'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ', now())");
    }

    function remove() {
		tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key IN ('" . implode("', '", $this->keys()) . "')");
    }

function keys() {
		//return array('MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER');
		return array('MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE' );
    }
  }
?>
