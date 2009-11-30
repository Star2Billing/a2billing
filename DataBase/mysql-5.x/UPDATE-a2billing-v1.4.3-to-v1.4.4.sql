
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



CREATE VIEW cc_callplan_lcr AS
	SELECT cc_ratecard.destination, cc_ratecard.dialprefix, cc_ratecard.buyrate, cc_ratecard.rateinitial, cc_ratecard.startdate, cc_ratecard.stopdate, cc_ratecard.initblock, cc_ratecard.connectcharge, cc_ratecard.id_trunk , cc_ratecard.idtariffplan , cc_ratecard.id, cc_tariffgroup.id AS tariffgroup_id
	FROM cc_tariffgroup 
	RIGHT JOIN cc_tariffgroup_plan ON cc_tariffgroup_plan.idtariffgroup=cc_tariffgroup.id 
	INNER JOIN cc_tariffplan ON (cc_tariffplan.id=cc_tariffgroup_plan.idtariffplan ) 
	LEFT JOIN cc_ratecard ON cc_ratecard.idtariffplan=cc_tariffplan.id;


-- New Agent commission module
ALTER TABLE cc_agent ADD com_balance DECIMAL( 15, 5 ) NOT NULL;
ALTER TABLE cc_agent_commission DROP paid_status ;
ALTER TABLE cc_agent_commission ADD commission_type TINYINT NOT NULL ;
ALTER TABLE cc_agent_commission ADD commission_percent DECIMAL( 10, 4 ) NOT NULL ;
INSERT INTO cc_config ( config_title , config_key , config_value , config_description , config_valuetype , config_listvalues , config_group_title) 
VALUES ('Authorize Remittance Request', 'remittance_request', '1', 'Enable or disable the link which allow agent to submit a remittance request', '0', 'yes,no', 'webagentui');


ALTER TABLE cc_agent ADD threshold_remittance DECIMAL( 15, 5 ) NOT NULL ;
ALTER TABLE cc_agent ADD bank_info MEDIUMTEXT NULL ;

CREATE TABLE cc_remittance_request (
	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	id_agent BIGINT NOT NULL ,
	amount DECIMAL( 15, 5 ) NOT NULL ,
	type TINYINT NOT NULL,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
	status TINYINT NOT NULL DEFAULT '0',
	description MEDIUMTEXT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--notifiction link to the record
ALTER TABLE `cc_notification` ADD `link_id` BIGINT NULL ,
ADD `link_type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NULL;