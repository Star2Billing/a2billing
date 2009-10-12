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



/*
 * 	USAGE : http://domainname/webservice/SOAP/test-callbackexec.php
 */ 

include ("../lib/admin.defines.php");
require('SOAP/Client.php');

$security_key = API_SECURITY_KEY;


$endpoint = 'http://localhost/~areski/svn/a2billing/trunk/webservice/SOAP/callback-exec.php';
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


//	#############   Check Status   #############   
echo "<hr>#############   Check Status  #############   </hr>";
$method = 'Status';
//$insert_id_callback = 1;
// array('in' => array('security_key' => 'string', 'id' => 'string'),
//				'out' => array('uniqueid' => 'string', 'result' => 'string', 'details' => 'string')
$params = array('security_key' => md5($security_key), 'id' => $insert_id_callback);

$ans = $callback -> call($method, $params);

print_r($ans);


