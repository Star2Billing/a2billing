
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


create index idtariffplan_index on cc_ratecard (idtariffplan);


UPDATE cc_config SET config_title='DID Billing Days to pay', config_description='Define the amount of days you want to give to the user before releasing its DIDs' WHERE config_key='didbilling_daytopay ';


-- Add new field for VT provisioning
ALTER TABLE cc_card_group ADD provisioning VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_bin NULL;


-- New setting for Base_country and Base_language
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Base Country', 'base_country', 'USA', 'Define the country code in 3 letters where you are located (ISO 3166-1 : "USA" for United States)', 0, '', 'global');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Base Language', 'base_language', 'en', 'Define your language code in 2 letters (ISO 639 : "en" for English)', 0, '', 'global');


