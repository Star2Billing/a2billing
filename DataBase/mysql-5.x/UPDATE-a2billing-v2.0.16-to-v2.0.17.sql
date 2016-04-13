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

-- Add default account code setting
DELETE FROM cc_config
    WHERE config_key IN ('default_accountcode', 'default_accountcode_all');

INSERT INTO cc_config SET
    config_title       = 'Default Accountcode',
    config_key         = 'default_accountcode',
    config_value       = '',
    config_description = 'The accountcode to apply to all calls using this AGI config. See default_accountcode_all for more control',
    config_valuetype   = 0,
    config_listvalues  = NULL,
    config_group_title = 'agi-conf1';

INSERT INTO cc_config SET
    config_title       = 'Default Accountcode Behaviour',
    config_key         = 'default_accountcode_all',
    config_value       = '0',
    config_description = 'Use the default accountcode for all calls, even if they have an accountcode already?',
    config_valuetype   = '1',
    config_listvalues  = 'yes,no',
    config_group_title = 'agi-conf1';

-- Update Version
UPDATE cc_version SET version = '2.0.17';
