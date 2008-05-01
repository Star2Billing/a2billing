<?php
/*
  $Id: worldpay.php,v MS1a 2003/04/06 21:30
  Author : Graeme Conkie (graeme@conkie.net)
  Title: WorldPay Payment Callback Module V4.0 Version 1.6

  Revisions:
  
Paulz added minor changes to enable control of 'Payment Zone' added function update_status
Version MS1a Cleaned up code, moved static English to language file to allow for bi-lingual use,
        Now posting language code to WP, Redirect on failure now to Checkout Payment,
Reduced re-direct time to 8 seconds, added MD5, made callback dynamic
NOTE: YOU MUST CHANGE THE CALLBACK URL IN WP ADMIN TO <wpdisplay item="MC_callback">
Version 1.4 Removes boxes to prevent users from clicking away before update,
Fixes currency for Yen,
Redirects to Checkout_Process after 10 seconds or click by user
Version 1.3 Fixes problem with Multi Currency
Version 1.2 Added Sort Order and Default order status to work with snapshots after 14 Jan 2003
Version 1.1 Added Worldpay Pre-Authorisation ability
Version 1.0 Initial Payment Module

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003
  Released under the GNU General Public License
*/

  class worldpay {
    var $code, $title, $description, $enabled;

// class constructor
    function worldpay() {
    global $order;
    
      
      $this->code = 'worldpay';
      $this->title = MODULE_PAYMENT_WORLDPAY_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_WORLDPAY_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_WORLDPAY_SORT_ORDER;
      //Modify to a2billing framework : initialize to true
      //$this->enabled = ((MODULE_PAYMENT_WORLDPAY_STATUS == 'True') ? true : false);
      $this->enabled =true;
      if ((int)MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = 'https://select.worldpay.com/wcc/purchase';

      }

      // class methods
      function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_WORLDPAY_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_WORLDPAY_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

// class methods
      function javascript_validation() {
        return false;
      }

      function selection() {
        return array('id' => $this->code,
                     'module' => $this->title);
      }

      function pre_confirmation_check() {
        return false;
      }

      function confirmation() {
        return false;
      }

      function process_button() {
// Ian-san: Need to declare language_id global here 6/4/2003:
      global $HTTP_POST_VARS, $languages_id, $shipping_cost, $total_cost, $shipping_selected, $shipping_method, $currencies, $currency, $customer_id , $order;
      $worldpay_url = tep_session_name() . '=' . tep_session_id();

// Multi Currency - Graeme Conkie ver 1.3 - Set up variable
// Added decimal point code - contributed by Ian Davidson (Feb 08,2003) - For Yen currency
      $OrderAmt = number_format($order->info['total'] * $currencies->get_value($currency), $currencies->get_decimal_places($currency), '.', '') ;

// Multi Currency - ver 1.3
      $process_button_string =
      tep_draw_hidden_field('instId', MODULE_PAYMENT_WORLDPAY_ID) .
      tep_draw_hidden_field('currency', $currency) .
      tep_draw_hidden_field('desc', 'Purchase from '.STORE_NAME) .

// Send URL and session name - contributed by Nick Vermeulen 08 Feb, 2003
      tep_draw_hidden_field('cartId', $worldpay_url ) .

// Assign Multi Currency Variable to Amount
      tep_draw_hidden_field('amount', $OrderAmt) ;

// Pre Auth Mod 3/1/2002 - Graeme Conkie
      if (MODULE_PAYMENT_WORLDPAY_USEPREAUTH == 'True') $process_button_string .= tep_draw_hidden_field('authMode', MODULE_PAYMENT_WORLDPAY_PREAUTH);

// Ian-san: Create callback and language links here 6/4/2003:
      $callback_url = tep_href_link(FILENAME_WPCALLBACK);
    //  $callback_url = tep_href_link(FILENAME_WPCALLBACK, '', (ENABLE_SSL ? 'SSL' : 'NONSSL'), true);
      $worldpay_callback = explode('http://', $callback_url);
      if (substr_count($callback_url,"https://") > 0) {
          $worldpay_callback = explode('https://', $callback_url);
      }
      $language_code_raw = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id ='$languages_id'");
      $language_code_array = tep_db_fetch_array($language_code_raw);
      $language_code = $language_code_array['code'];

      $address = htmlspecialchars($order->customer['street_address'] . "\n" . $order->customer['suburb'] . "\n" . $order->customer['city'] . "\n" . $order->customer['state'], ENT_QUOTES);
      $process_button_string .=
        tep_draw_hidden_field('testMode', MODULE_PAYMENT_WORLDPAY_MODE) .
        tep_draw_hidden_field('name', $order->customer['firstname'] . ' ' . $order->customer['lastname']) .
        tep_draw_hidden_field('address', $address) .
        tep_draw_hidden_field('postcode', $order->customer['postcode']) .
        tep_draw_hidden_field('country', $order->customer['country']['iso_code_2']) .
        tep_draw_hidden_field('tel', $order->customer['telephone']) .
        tep_draw_hidden_field('myvar', 'Y') .
        tep_draw_hidden_field('fax', $order->customer['fax']) .
        tep_draw_hidden_field('email', $order->customer['email_address']) .

// Ian-san: Added dynamic callback and languages link here 6/4/2003:
        tep_draw_hidden_field('lang', $language_code) .
        tep_draw_hidden_field('MC_callback', $worldpay_callback[1]) .
        tep_draw_hidden_field('MC_oscsid', $oscSid);

// Ian-san: Added MD5 here 6/4/2003:
      if (MODULE_PAYMENT_WORLDPAY_USEMD5 == '1') {
        $md5_signature_fields = 'amount:language:email';
        $md5_signature = MODULE_PAYMENT_WORLDPAY_MD5KEY . ':' . (number_format($order->info['total'] * $currencies->get_value($currency), $currencies->get_decimal_places($currency), '.', '')) . ':' . $language_code . ':' . $order->customer['email_address'];
        $md5_signature_md5 = md5($md5_signature);

        $process_button_string .= tep_draw_hidden_field('signatureFields', $md5_signature_fields ) .
                                  tep_draw_hidden_field('signature',$md5_signature_md5);
      }
        return $process_button_string ;
      }

      function before_process() {
        global $HTTP_POST_VARS;
      }

      function after_process() {
        return false;
      }

      function output_error() {
        return false;
      }

      function check() {
        if (!isset($this->_check)) {
          $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WORLDPAY_STATUS'");
          $this->_check = tep_db_num_rows($check_query);
        }
        return $this->_check;
      }

      function install() {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable WorldPay Module', 'MODULE_PAYMENT_WORLDPAY_STATUS', 'True', 'Do you want to accept WorldPay payments?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Worldpay Installation ID', 'MODULE_PAYMENT_WORLDPAY_ID', '00000', 'Your WorldPay Select Junior ID', '6', '2', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mode', 'MODULE_PAYMENT_WORLDPAY_MODE', '100', 'The mode you are working in (100 = Test Mode Accept, 101 = Test Mode Decline, 0 = Live)', '6', '5', now())");

// Ian-san: Added MD5 here 6/4/2003:
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Use MD5', 'MODULE_PAYMENT_WORLDPAY_USEMD5', '0', 'Use MD5 encyption for transactions? (1 = Yes, 0 = No)', '6', '4', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('MD5 secret key', 'MODULE_PAYMENT_WORLDPAY_MD5KEY', '', 'MD5 secret key. Must also be entered into Worldpay installation config', '6', '5', now())");

// Pre Auth Mod - Graeme Conkie 13/1/2003
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_WORLDPAY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Use Pre-Authorisation?', 'MODULE_PAYMENT_WORLDPAY_USEPREAUTH', 'False', 'Do you want to pre-authorise payments? Default=False. You need to request this from WorldPay before using it.', '6', '3', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Pre-Auth', 'MODULE_PAYMENT_WORLDPAY_PREAUTH', 'A', 'The mode you are working in (A = Pay Now, E = Pre Auth). Ignored if Use PreAuth is False.', '6', '4', now())");
// Paulz zone control 04/04/2004        
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_WORLDPAY_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
// Ian-san: Added MD5 here 6/4/2003:
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WORLDPAY_USEMD5'");
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WORLDPAY_MD5KEY'");
      }

      function remove() {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
      }

      function keys() {
        return array('MODULE_PAYMENT_WORLDPAY_STATUS', 'MODULE_PAYMENT_WORLDPAY_ID','MODULE_PAYMENT_WORLDPAY_MODE','MODULE_PAYMENT_WORLDPAY_USEPREAUTH','MODULE_PAYMENT_WORLDPAY_PREAUTH','MODULE_PAYMENT_WORLDPAY_ZONE','MODULE_PAYMENT_WORLDPAY_SORT_ORDER','MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID');
      }
    }
?>
