
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
 * Contributed by Milan Benicek, SIPhone s.r.o., milan.benicek@siphone.cz
 *
**/


ALTER TABLE cc_did ADD aleg_timeinterval text collate utf8_bin;

ALTER TABLE cc_did ADD aleg_carrier_connect_charge_offp DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD aleg_carrier_cost_min_offp DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD aleg_retail_connect_charge_offp DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD aleg_retail_cost_min_offp DECIMAL( 15, 5 ) NOT NULL DEFAULT '0';

ALTER TABLE cc_did ADD aleg_carrier_initblock_offp int(11) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD aleg_carrier_increment_offp int(11) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD aleg_retail_initblock_offp int(11) NOT NULL DEFAULT '0';
ALTER TABLE cc_did ADD aleg_retail_increment_offp int(11) NOT NULL DEFAULT '0';


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
('LCR Mode', 'lcr_mode', '0', 'LCR Mode<br>0: Classic (Search the longer prefix of all ratecards, then LCR with all ratecards with this prefix)<br>1: Provider (LCR with the longer available prefix for every ratecard independently)<br>', 0, '', 'agi-conf1');

DELETE FROM cc_config WHERE config_key = 'currency_cents_association';
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES
('Currency Cents Association', 'currency_cents_association', 'usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit', 'Define all the audio (without file extensions) that you want to play according to currency for the cents (use , to separate, ie "usd:prepaid-cents,eur:prepaid-cents,gbp:prepaid-pence,all:credit").', 0, '', 'agi-conf1');

UPDATE cc_config SET config_description='Asterisk Version Information, 1_1, 1_2, 1_4, 1_6, 1_8' WHERE config_key='asterisk_version';



-- Prepare fields for IPV6
ALTER TABLE  cc_sip_buddies CHANGE  DEFAULTip  DEFAULTip CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
ALTER TABLE  cc_sip_buddies CHANGE  host  host VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE  cc_sip_buddies CHANGE  ipaddr  ipaddr CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  '';

ALTER TABLE  cc_iax_buddies CHANGE  ipaddr  ipaddr CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  '';
ALTER TABLE  cc_iax_buddies CHANGE  host  host VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;
ALTER TABLE  cc_iax_buddies CHANGE  DEFAULTip  DEFAULTip CHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
ALTER TABLE  cc_iax_buddies CHANGE  sourceaddress  sourceaddress VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  '';

-- Fix for long did destination
ALTER TABLE cc_call CHANGE calledstation calledstation VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;


-- Update Version
UPDATE cc_version SET version = '1.9.5';
