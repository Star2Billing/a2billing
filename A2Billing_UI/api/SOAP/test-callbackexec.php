<?php
/***************************************************************************
 *
 * test-callbackexec.php : PHP A2Billing - Test Callback
 * Written for PHP 4.x & PHP 5.X versions.
 *
 * A2Billing -- Asterisk billing solution.
 * Copyright (C) 2004, 2007 Belaid Arezqui <areski _atl_ gmail com>
 *
 * See http://www.asterisk2billing.org for more information about
 * the A2Billing project. 
 * Please submit bug reports, patches, etc to <areski _atl_ gmail com>
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 ****************************************************************************/

/***************************************************************************
 *
 * USAGE : http://domainname/A2Billing_UI/api/SOAP/test-callbackexec.php   
 *
 * http://localhost/~areski/svn/a2billing/trunk/A2Billing_UI/api/SOAP/test-callbackexec.php   
 *
 ****************************************************************************/


include ("../../lib/defines.php");
require('SOAP/Client.php');

$security_key = API_SECURITY_KEY;


$endpoint = 'http://localhost/~areski/svn/a2billing/trunk/A2Billing_UI/api/SOAP/callback-exec.php';
// ADD ON THE SPEC SECURITY KEY

$callback = new SOAP_Client($endpoint);




//	#############   Request CallBack   #############
echo "<hr>#############   Request CallBack   #############   </hr>";
$method = 'Request';   

// array('in' => array('security_key' => 'string', 'pn_callingparty' => 'string', 'pn_calledparty' => 'string', 'callerid' => 'string', 'callback_time' => 'string', 'uniqueid' => 'string'),
//                   'out' => array('id' => 'string', 'result' => 'string', 'details' => 'string')
$params = array('security_key' => md5($security_key), 'pn_callingparty' => '34650784355', 'pn_calledparty' => '5633434', 'callerid' => '34650555555', 'callback_time' => '', 'uniqueid' => '');

$ans = $callback -> call($method, $params);

print_r($ans);

$insert_id_callback = $ans[0];

//$insert_id_callback = '47'; // ??

//	#############   Check Status   #############   
echo "<hr>#############   Check Status  #############   </hr>";
$method = 'Status';
$insert_id_callback = 1;
// array('in' => array('security_key' => 'string', 'id' => 'string'),
//				'out' => array('uniqueid' => 'string', 'result' => 'string', 'details' => 'string')
$params = array('security_key' => md5($security_key), 'id' => $insert_id_callback);

$ans = $callback -> call($method, $params);

print_r($ans);

exit;
?>
