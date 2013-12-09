
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



ALTER TABLE cc_did_destination CHANGE destination destination VARCHAR(120) NOT NULL;


DROP TABLE IF EXISTS cc_call_archive;
CREATE TABLE IF NOT EXISTS cc_call_archive (
    id bigint(20) NOT NULL auto_increment,
    sessionid varchar(40) collate utf8_bin NOT NULL,
    uniqueid varchar(30) collate utf8_bin NOT NULL,
    card_id bigint(20) NOT NULL,
    nasipaddress varchar(30) collate utf8_bin NOT NULL,
    starttime timestamp NOT NULL default CURRENT_TIMESTAMP,
    stoptime timestamp NOT NULL default '0000-00-00 00:00:00',
    sessiontime int(11) default NULL,
    calledstation varchar(30) collate utf8_bin NOT NULL,
    sessionbill float default NULL,
    id_tariffgroup int(11) default NULL,
    id_tariffplan int(11) default NULL,
    id_ratecard int(11) default NULL,
    id_trunk int(11) default NULL,
    sipiax int(11) default '0',
    src varchar(40) collate utf8_bin NOT NULL,
    id_did int(11) default NULL,
    buycost decimal(15,5) default '0.00000',
    id_card_package_offer int(11) default '0',
    real_sessiontime int(11) default NULL,
    dnid varchar(40) collate utf8_bin NOT NULL,
    terminatecauseid int(1) default '1',
    destination int(11) default '0',
    PRIMARY KEY  (id),
    KEY starttime (starttime),
    KEY calledstation (calledstation),
    KEY terminatecauseid (terminatecauseid)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Archive Calls', 'archive_call_prior_x_month', '24', 'A cront can be enabled in order to archive your CDRs, this setting allow to define prior which month it will archive', 0, NULL, 'backup');
 

ALTER TABLE cc_logpayment ADD agent_id BIGINT NULL ;
ALTER TABLE cc_logrefill ADD agent_id BIGINT NULL ;


ALTER TABLE `cc_ratecard` CHANGE `destination` `destination` BIGINT( 20 ) NULL DEFAULT '0';


UPDATE cc_version SET version = '1.4.5';



