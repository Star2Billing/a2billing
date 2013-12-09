
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


create index idtariffplan_index on cc_ratecard (idtariffplan);


UPDATE cc_config SET config_title='DID Billing Days to pay', config_description='Define the amount of days you want to give to the user before releasing its DIDs' WHERE config_key='didbilling_daytopay ';


-- Add new field for VT provisioning
ALTER TABLE cc_card_group ADD provisioning VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_bin NULL;


-- New setting for Base_country and Base_language
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Base Country', 'base_country', 'USA', 'Define the country code in 3 letters where you are located (ISO 3166-1 : "USA" for United States)', 0, '', 'global');
INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES('Base Language', 'base_language', 'en', 'Define your language code in 2 letters (ISO 639 : "en" for English)', 0, '', 'global');



-- Change lenght of field for provisioning system
ALTER TABLE cc_card_group CHANGE name name varchar( 50 );
ALTER TABLE cc_trunk CHANGE trunkcode trunkcode varchar( 50 );


-- change lenght on Notification
ALTER TABLE cc_notification CHANGE key_value key_value VARCHAR( 255 );



-- IAX Friends update

CREATE INDEX iax_friend_nh_index on cc_iax_buddies (name, host);
CREATE INDEX iax_friend_nip_index on cc_iax_buddies (name, ipaddr, port);
CREATE INDEX iax_friend_ip_index on cc_iax_buddies (ipaddr, port);
CREATE INDEX iax_friend_hp_index on cc_iax_buddies (host, port);


ALTER TABLE cc_iax_buddies
	DROP callgroup,
	DROP canreinvite,
	DROP dtmfmode,
	DROP fromuser,
	DROP fromdomain,
	DROP insecure,
	DROP mailbox,
	DROP md5secret,
	DROP nat,
	DROP pickupgroup,
	DROP restrictcid,
	DROP rtptimeout,
	DROP rtpholdtimeout,
	DROP musiconhold,
	DROP cancallforward;


ALTER TABLE cc_iax_buddies 
	ADD dbsecret varchar(40) NOT NULL default '',
	ADD regcontext varchar(40) NOT NULL default '',
	ADD sourceaddress varchar(20) NOT NULL default '',
	ADD mohinterpret varchar(20) NOT NULL default '', 
	ADD mohsuggest varchar(20) NOT NULL default '', 
	ADD inkeys varchar(40) NOT NULL default '', 
	ADD outkey varchar(40) NOT NULL default '', 
	ADD cid_number varchar(40) NOT NULL default '', 
	ADD sendani varchar(10) NOT NULL default '', 
	ADD fullname varchar(40) NOT NULL default '', 
	ADD auth varchar(20) NOT NULL default '', 
	ADD maxauthreq varchar(15) NOT NULL default '', 
	ADD encryption varchar(20) NOT NULL default '', 
	ADD transfer varchar(10) NOT NULL default '', 
	ADD jitterbuffer varchar(10) NOT NULL default '', 
	ADD forcejitterbuffer varchar(10) NOT NULL default '', 
	ADD codecpriority varchar(40) NOT NULL default '', 
	ADD qualifysmoothing varchar(10) NOT NULL default '', 
	ADD qualifyfreqok varchar(10) NOT NULL default '', 
	ADD qualifyfreqnotok varchar(10) NOT NULL default '', 
	ADD timezone varchar(20) NOT NULL default '', 
	ADD adsi varchar(10) NOT NULL default '', 
	ADD setvar varchar(200) NOT NULL default '';

-- Add IAX security settings / not support by realtime
ALTER TABLE cc_iax_buddies 
	ADD requirecalltoken varchar(20) NOT NULL default '',
	ADD maxcallnumbers varchar(10) NOT NULL default '',
	ADD maxcallnumbers_nonvalidated varchar(10) NOT NULL default '';


-- SIP Friends update

CREATE INDEX sip_friend_hp_index on cc_sip_buddies (host, port);
CREATE INDEX sip_friend_ip_index on cc_sip_buddies (ipaddr, port);


ALTER TABLE cc_sip_buddies
	ADD defaultuser varchar(40) NOT NULL default '',
	ADD auth varchar(10) NOT NULL default '',
	ADD subscribemwi varchar(10) NOT NULL default '', -- yes/no
	ADD vmexten varchar(20) NOT NULL default '',
	ADD cid_number varchar(40) NOT NULL default '',
	ADD callingpres varchar(20) NOT NULL default '',
	ADD usereqphone varchar(10) NOT NULL default '',
	ADD incominglimit varchar(10) NOT NULL default '',
	ADD subscribecontext varchar(40) NOT NULL default '',
	ADD musicclass varchar(20) NOT NULL default '',
	ADD mohsuggest varchar(20) NOT NULL default '',
	ADD allowtransfer varchar(20) NOT NULL default '',
	ADD autoframing varchar(10) NOT NULL default '', -- yes/no
	ADD maxcallbitrate varchar(15) NOT NULL default '',
	ADD outboundproxy varchar(40) NOT NULL default '',
--  ADD regserver varchar(20) NOT NULL default '',
	ADD rtpkeepalive varchar(15) NOT NULL default '';



-- ADD A2Billing Version into the Database 
CREATE TABLE cc_version (
    version varchar(30) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO cc_version (version) VALUES ('1.4.3');

UPDATE cc_version SET version = '1.4.3';
