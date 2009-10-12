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



/***************************************************************************
 *
 * USAGE : http://domainname/webservice/SOAP/SOAP/soap-card-client.php
 *
 * http://localhost/~areski/svn/a2billing/trunk/webservice/SOAP/soap-card-client.php
 *
 ****************************************************************************/
 
include ("../lib/admin.defines.php");
require('SOAP/Client.php');


$security_key = API_SECURITY_KEY;
$endpoint = 'http://localhost/~areski/svn/a2billing/trunk/webservice/SOAP/soap-card-server.php';
// ADD ON THE SPEC SECURITY KEY
$card = new SOAP_Client($endpoint);


//	#############   Reservation_Card   #############   

echo "<hr>#############   Reservation_Card : $ans[2]  #############   </hr>";
$method = 'Reservation_Card';   

$params = array('security_key' => md5($security_key), 'transaction_code' => 'mytransaction_code', 'card_id' => '', 'cardnumber' => '8995713909' );

$ans = $card->call($method, $params);

print_r($ans);
exit;

//	#############   Activation_Card   #############   

echo "<hr>#############   Activation_CARD : $ans[2]  #############   </hr>";
$method = 'Activation_Card';   
//Activation_Card($security_key, $transaction_code, $card_id, $cardnumber)
$params = array('security_key' => md5($security_key), 'transaction_code' => 'mytransaction_code', 'card_id' => '', 'cardnumber' => '8995713909' );

$ans = $card->call($method, $params);

print_r($ans);
exit;


//	#############  Batch_Activation_Card   #############   

echo "<hr>#############   Batch_Activation_Card : $ans[2]  #############   </hr>";
$method = 'Batch_Activation_Card';   

$params = array('security_key' => md5($security_key), 'transaction_code' => 'mytransaction_code', 'begin_card_id' => '2', 
				'end_card_id' => '4');

$ans = $card->call($method, $params);

print_r($ans);
exit;













//	#############   CREATE_CARD   #############   

echo "<hr>#############   CREATE_CARD   #############   </hr>";
$method = 'Create_Card';   

$params = array('security_key' => md5($security_key), 'transaction_code' => 'mytransaction_code', 'account_number' => 'myaccount_number', 'tariff' => '1', 'uipass' => '', 'credit' => '10', 'language' => 'en', 
'activated' => '1', 'status' => '2' , 'simultaccess' => '0', 'currency' => 'USD', 'runservice' => '0', 'typepaid' => '1', 'creditlimit' => '0', 
'enableexpire' => '0', 'expirationdate' => '', 'expiredays' => '0', 'lastname' => 'Areski', 'firstname' => 'Areski', 'address' => 'my address', 
'city' => 'mycity', 'state' => 'mystate', 'country' => 'mycoutry', 'zipcode' => '1000', 'phone' => '646486411', 'fax' => '', 
'callerid_list' => '21345114', 'iax_friend' => '1', 'sip_friend' => '0');

$ans = $card->call($method, $params);

print_r($ans);
exit;
//	#############   REMOVE_CARD   #############   

echo "<hr>#############   REMOVE_CARD : $ans[2]  #############   </hr>";
$method = 'Remove_Card';   

$params = array('security_key' => md5($security_key), 'transaction_code' => 'mytransaction_code', 'account_number' => 'myaccount_number', 
				'cardnumber' => $ans[2]);

$ans = $card->call($method, $params);

print_r($ans);
exit;

