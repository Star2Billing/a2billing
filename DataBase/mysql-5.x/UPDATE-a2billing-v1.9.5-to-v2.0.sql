
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
**/


DELETE FROM cc_payment_methods WHERE payment_method = 'iridium';

-- Add max concurrent call
ALTER TABLE cc_card ADD max_concurrent int(11) NOT NULL DEFAULT '10';
ALTER TABLE cc_did ADD max_concurrent int(11) NOT NULL DEFAULT '10';

-- Change nat field to 30 chars
ALTER TABLE  cc_sip_buddies CHANGE nat  nat CHAR(30) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT 'yes';


UPDATE cc_config SET config_description='Asterisk Version Information, 1_1, 1_2, 1_4, 1_6, 1_8 1_10 1_11' WHERE config_key='asterisk_version';

-- Update Version
UPDATE cc_version SET version = '2.0';
