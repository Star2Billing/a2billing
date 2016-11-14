/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
* This file is part of A2Billing (http://www.a2billing.net/)
*
* A2Billing, Commercial Open Source Telecom Billing platform,
* powered by Star2billing S.L. <http://www.star2billing.com/>
*
* @copyright Copyright (C) 2004-2012 - Star2billing S.L.
* @author Belaid Arezqui <areski@gmail.com>
* @license http://www.fsf.org/licensing/licenses/agpl-3.0.html
* @package A2Billing
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
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
**/

-- Update Version
UPDATE cc_version SET version = '2.3.0';

-- transaction_key
UPDATE cc_config SET config_value = concat(
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0),
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0),
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0),
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)
) WHERE id=75;

-- SELECT * FROM cc_config WHERE id=75;

-- api_security_key
UPDATE cc_config SET config_value = concat(
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0),
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0),
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0),
    lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0)
) WHERE id=103;

-- SELECT * FROM cc_config WHERE id=103;

-- Fix cc_ratecard where destination is not long enough
ALTER TABLE `cc_ratecard` CHANGE `destination` `destination` bigint(20) NOT NULL;
