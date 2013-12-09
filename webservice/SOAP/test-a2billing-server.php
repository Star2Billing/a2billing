<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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
 * 	USAGE : http://localhost/webservice/SOAP/test-callbackexec.php
 */

include '../lib/admin.defines.php';
require 'SOAP/Client.php';

$security_key = API_SECURITY_KEY;

$endpoint = 'http://localhost/~areski/svn/asterisk2billing/trunk/webservice/SOAP/soap-a2billing-server.php';

// Create SOAP Client
$callback = new SOAP_Client($endpoint);

echo "<hr>#############   GET COUNTRIES   ############# <br/><hr/> <pre>";
$method = 'Get_Countries';

$params = array('security_key' => md5($security_key));
$ans = $callback -> call($method, $params);
print_r(unserialize($ans[0]));
exit;

echo "<hr>#############   Get ProvisioningList   ############# <br/><hr/> <pre>";
$method = 'Get_ProvisioningList';

$params = array('security_key' => md5($security_key), 'provisioning_uri' => "http://www.call-labs.com/provisioning.txt");
$ans = $callback -> call($method, $params);
print_r(unserialize($ans[0]));

echo "<hr>#############   GET LANGUAGE   ############# <br/><hr/> <pre>";
$method = 'Get_Languages';

$params = array('security_key' => md5($security_key));
$ans = $callback -> call($method, $params);
print_r(unserialize($ans[0]));

exit;
