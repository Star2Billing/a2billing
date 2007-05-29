<?php
/*
  $Id: order.php,v 1.33 2003/06/09 22:25:35 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class order {
    var $info, $totals, $products, $customer, $delivery, $content_type;

    function order($order_amount = '')
    {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      if (isset($order_amount) && !is_null($order_amount))
      {
          $this->query($order_amount);
      }
    }

    function query($order_amount)
    {
        global $languages_id;
	$numrow = 0;

        $QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, currency FROM cc_card WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";

        $DBHandle_max  = DbConnect();
        $resmax = $DBHandle_max -> Execute($QUERY);
	if ($resmax)
	        $numrow = $resmax -> RecordCount( );

        if ($numrow == 0) exit();
        $customer_info =$resmax -> fetchRow();
        if( $customer_info [13] != "t" && $customer_info [13] != "1" )
        {
            exit();
        }

        $order = $customer_info;


      $this->info = array('currency' => isset($A2B->config["paypal"]['currency_code'])?$A2B->config["paypal"]['currency_code']:null,
                          'currency_value' => $order['currency_value'],
                          'payment_method' => $order['payment_method'],
                          'cc_type' => $order['cc_type'],
                          'cc_owner' => $order['cc_owner'],
                          'cc_number' => $order['cc_number'],
                          'cc_expires' => $order['cc_expires'],
                          'date_purchased' => '',
                          'orders_status' => '',
                          'last_modified' => '',
                          'total' => strip_tags($order_amount),
                          'shipping_method' => ((substr($shipping_method['title'], -1) == ':') ? substr(strip_tags($shipping_method['title']), 0, -1) : strip_tags($shipping_method['title'])));

      $this->customer = array('id' => $order['customers_id'],
                              'name' => $order['username'],
                              'company' => '',
                              'street_address' => $order['address'],
                              'suburb' => '',
                              'city' => $order['city'],
                              'postcode' => $order['zipcode'],
                              'state' => $order['state'],
                              'country' => $order['country'],
                              'format_id' => '',
                              'telephone' => $order['telephone'],
                              'email_address' => $order['email']);

      $this->delivery = array('name' => $order['username'],
                              'company' => '',
                              'street_address' => $order['address'],
                              'suburb' => '',
                              'city' => $order['city'],
                              'postcode' => $order['zipcode'],
                              'state' => $order['state'],
                              'country' => $order['country'],
                              'format_id' => '');

      if (empty($this->delivery['name']) && empty($this->delivery['street_address']))
      {
        $this->delivery = false;
      }

      $this->billing = array('firstname' => $order['firstname'],
                             'lastname' => $order['lastname'],
                             'name' => $order['firstname'],
                             'company' => '',
                             'street_address' => $order['address'],
                             'suburb' => '',
                             'city' => $order['city'],
                             'postcode' => $order['zipcode'],
                             'state' => $order['state'],
                             'country' => $order['country'],
                             'format_id' => '');

          
    }
  }
?>
