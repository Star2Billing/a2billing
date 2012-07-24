
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L. 
 * @author      Hironobu Suzuki <hironobu@interdb.jp> / Belaid Arezqui <areski@gmail.com>
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

--
-- A2Billing database script - Update database for Postgres
-- 
--

\set ON_ERROR_STOP ON;

-- Wrap the whole update in a transaction so everything is reverted upon failure
BEGIN;

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Language field', 'field_language', '1', 'Enable The Language Field -  Yes 1 - No 0.', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Currency field', 'field_currency', '1', 'Enable The Currency Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Last Name Field', 'field_lastname', '1', 'Enable The Last Name Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('First Name Field', 'field_firstname', '1', 'Enable The First Name Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Address Field', 'field_address', '1', 'Enable The Address Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('City Field', 'field_city', '1', 'Enable The City Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('State Field', 'field_state', '1', 'Enable The State Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Country Field', 'field_country', '1', 'Enable The Country Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Zipcode Field', 'field_zipcode', '1', 'Enable The Zipcode Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Timezone Field', 'field_id_timezone', '1', 'Enable The Timezone Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Phone Field', 'field_phone', '1', 'Enable The Phone Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Fax Field', 'field_fax', '1', 'Enable The Fax Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Company Name Field', 'field_company', '1', 'Enable The Company Name Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Company Website Field', 'field_company_website', '1', 'Enable The Company Website Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('VAT Registration Number Field', 'field_VAT_RN', '1', 'Enable The VAT Registration Number Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Traffic Field', 'field_traffic', '1', 'Enable The Traffic Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');

INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES ('Traffic Target Field', 'field_traffic_target', '1', 'Enable The Traffic Target Field - Yes 1 - No 0. ', '1', 'yes,no', 'signup');


-- fix Realtime Bug, Permit have to be after Deny
--ALTER TABLE cc_sip_buddies MODIFY COLUMN permit varchar(95) AFTER deny;
CREATE TABLE _cc_iax_buddies (
 id                          serial not null,
 id_cc_card                   integer                 not null default 0,
 name                         character varying(80)   not null default ''::character varying,
 type                         character varying(6)    not null default 'friend'::character varying,
 username                     character varying(80)   not null default ''::character varying,
 accountcode                  character varying(20),   
 regexten                     character varying(20),   
 callerid                     character varying(80),   
 amaflags                     character varying(7),    
 secret                       character varying(80),   
 disallow                     character varying(100)  default 'all'::character varying,
 allow                        character varying(100)  default 'gsm,ulaw,alaw'::character varying,
 host                         character varying(31)   not null default ''::character varying,
 qualify                      character varying(7)    not null default 'yes'::character varying,
 context                      character varying(80),   
 defaultip                    character varying(15),   
 language                     character varying(2),   
 deny                         character varying(95),   
 permit                       character varying(95),   
 mask                         character varying(95),   
 port                         character varying(5)    not null default ''::character varying,
 regseconds                   integer                 not null default 0,
 ipaddr                       character varying(15)   not null default ''::character varying,
 trunk                        character varying(3)    default 'no'::character varying,
 dbsecret                     character varying(40)   not null default ''::character varying,
 regcontext                   character varying(40)   not null default ''::character varying,
 sourceaddress                character varying(20)   not null default ''::character varying,
 mohinterpret                 character varying(20)   not null default ''::character varying,
 mohsuggest                   character varying(20)   not null default ''::character varying,
 inkeys                       character varying(40)   not null default ''::character varying,
 outkey                       character varying(40)   not null default ''::character varying,
 cid_number                   character varying(40)   not null default ''::character varying,
 sendani                      character varying(10)   not null default ''::character varying,
 fullname                     character varying(40)   not null default ''::character varying,
 auth                         character varying(20)   not null default ''::character varying,
 maxauthreq                   character varying(15)   not null default ''::character varying,
 encryption                   character varying(20)   not null default ''::character varying,
 transfer                     character varying(10)   not null default ''::character varying,
 jitterbuffer                 character varying(10)   not null default ''::character varying,
 forcejitterbuffer            character varying(10)   not null default ''::character varying,
 codecpriority                character varying(40)   not null default ''::character varying,
 qualifysmoothing             character varying(10)   not null default ''::character varying,
 qualifyfreqok                character varying(10)   not null default ''::character varying,
 qualifyfreqnotok             character varying(10)   not null default ''::character varying,
 timezone                     character varying(20)   not null default ''::character varying,
 adsi                         character varying(10)   not null default ''::character varying,
 setvar                       character varying(200)  not null default ''::character varying,
 requirecalltoken             character varying(20)   not null default ''::character varying,
 maxcallnumbers               character varying(10)   not null default ''::character varying,
 maxcallnumbers_nonvalidated  character varying(10)   not null default ''::character varying,
 Unique (name)
);

INSERT INTO _cc_iax_buddies 
(id,id_cc_card, name, type, username, accountcode, regexten, callerid, amaflags, secret, disallow, allow,
 host, qualify, context, defaultip, language, deny, permit, mask, port, regseconds, ipaddr, trunk, dbsecret,
 regcontext, sourceaddress, mohinterpret, mohsuggest, inkeys, outkey, cid_number, sendani, fullname,
 auth, maxauthreq, encryption, transfer, jitterbuffer, forcejitterbuffer, codecpriority, qualifysmoothing,
 qualifyfreqok, qualifyfreqnotok, timezone, adsi, setvar, requirecalltoken, maxcallnumbers,
 maxcallnumbers_nonvalidated)
SELECT id,id_cc_card, name, type, username, accountcode, regexten, callerid, amaflags, secret, disallow, allow,
 host, qualify, context, defaultip, language, deny, permit, mask, port, regseconds, ipaddr, trunk, dbsecret,
 regcontext, sourceaddress, mohinterpret, mohsuggest, inkeys, outkey, cid_number, sendani, fullname,
 auth, maxauthreq, encryption, transfer, jitterbuffer, forcejitterbuffer, codecpriority, qualifysmoothing,
 qualifyfreqok, qualifyfreqnotok, timezone, adsi, setvar, requirecalltoken, maxcallnumbers,
 maxcallnumbers_nonvalidated FROM cc_iax_buddies ORDER BY id;


ALTER TABLE cc_iax_buddies RENAME TO cc_iax_buddies_tmp;
ALTER TABLE _cc_iax_buddies RENAME TO cc_iax_buddies;
DROP TABLE cc_iax_buddies_tmp;

ALTER TABLE cc_iax_buddies ADD PRIMARY KEY (id);
CREATE INDEX cc_iax_buddies_name ON cc_iax_buddies USING btree(name);
CREATE INDEX cc_iax_buddies_host ON cc_iax_buddies USING btree(host);
CREATE INDEX cc_iax_buddies_ipaddr ON cc_iax_buddies USING btree(ipaddr);
CREATE INDEX cc_iax_buddies_port ON cc_iax_buddies USING btree(port);
CREATE INDEX iax_friend_hp_index ON cc_iax_buddies (host, port);
CREATE INDEX iax_friend_ip_index ON cc_iax_buddies (ipaddr, port);
CREATE INDEX iax_friend_nh_index ON cc_iax_buddies (name, host);
CREATE INDEX iax_friend_nip_index ON cc_iax_buddies (name, ipaddr, port);

--ALTER TABLE cc_iax_buddies MODIFY COLUMN permit varchar(95) AFTER deny;
DROP  VIEW cc_sip_buddies_empty;

CREATE TABLE _cc_sip_buddies (
 id               serial NOT NULL,
 id_cc_card       integer not null default 0,
 name             character varying(80)  not null default ''::character varying,
 type             character varying(6)   not null default 'friend'::character varying,
 username         character varying(80)  not null default ''::character varying,
 accountcode      character varying(20),
 regexten         character varying(20), 
 callerid         character varying(80), 
 amaflags         character varying(7), 
 secret           character varying(80),
 md5secret        character varying(80), 
 nat              character varying(3)   not null default 'yes'::character varying,
 dtmfmode         character varying(7)   not null default 'RFC2833'::character varying,
 disallow         character varying(100) default 'all'::character varying,
 allow            character varying(100) default 'gsm,ulaw,alaw'::character varying,
 host             character varying(31)  not null default ''::character varying,
 qualify          character varying(7)   not null default 'yes'::character varying,
 canreinvite      character varying(20)  default 'yes'::character varying,
 callgroup        character varying(10),
 context          character varying(80), 
 defaultip        character varying(15), 
 fromuser         character varying(80), 
 fromdomain       character varying(80), 
 insecure         character varying(20), 
 language         character varying(2), 
 mailbox          character varying(50), 
 deny             character varying(95), 
 permit           character varying(95),
 mask             character varying(95), 
 pickupgroup      character varying(10), 
 port             character varying(5)   not null default ''::character varying,
 restrictcid      character varying(1),
 rtptimeout       character varying(3), 
 rtpholdtimeout   character varying(3), 
 musiconhold      character varying(100), 
 regseconds       integer                not null default 0,
 ipaddr           character varying(15)  not null default ''::character varying,
 cancallforward   character varying(3)   default 'yes'::character varying,
 fullcontact      character varying(80),
 setvar           character varying(100) not null default ''::character varying,
 regserver        character varying(20),
 lastms           character varying(11), 
 defaultuser      character varying(40)  not null default ''::character varying,
 auth             character varying(10)  not null default ''::character varying,
 subscribemwi     character varying(10)  default ''::character varying,
 vmexten          character varying(20)  not null default ''::character varying,
 cid_number       character varying(40)  not null default ''::character varying,
 callingpres      character varying(20)  not null default ''::character varying,
 usereqphone      character varying(10)  not null default ''::character varying,
 incominglimit    character varying(10)  not null default ''::character varying,
 subscribecontext character varying(40)  not null default ''::character varying,
 musicclass       character varying(20)  not null default ''::character varying,
 mohsuggest       character varying(20)  not null default ''::character varying,
 allowtransfer    character varying(20)  not null default ''::character varying,
 autoframing      character varying(10)  not null default ''::character varying,
 maxcallbitrate   character varying(15)  not null default ''::character varying,
 outboundproxy    character varying(40)  not null default ''::character varying,
 rtpkeepalive     character varying(15)  not null default ''::character varying,
 Unique (name)
);

INSERT INTO _cc_sip_buddies (id,id_cc_card, name, type, username, accountcode, regexten, callerid, amaflags, secret,
 md5secret, nat, dtmfmode, disallow, allow, host, qualify, canreinvite, callgroup, context, defaultip,
 fromuser, fromdomain, insecure, language, mailbox, deny, permit, mask, pickupgroup, port, 
restrictcid, rtptimeout, rtpholdtimeout,  musiconhold, regseconds, ipaddr, cancallforward, 
fullcontact, setvar, regserver, lastms, defaultuser, auth, subscribemwi, vmexten, cid_number, 
callingpres, usereqphone, incominglimit, subscribecontext, musicclass, mohsuggest, allowtransfer, 
autoframing, maxcallbitrate, outboundproxy, rtpkeepalive)
SELECT  id,id_cc_card, name, type, username, accountcode, regexten, callerid, amaflags, secret,
 md5secret, nat, dtmfmode, disallow, allow, host, qualify, canreinvite, callgroup, context, defaultip,
 fromuser, fromdomain, insecure, language, mailbox, deny, permit, mask, pickupgroup, port, 
restrictcid, rtptimeout, rtpholdtimeout,  musiconhold, regseconds, ipaddr, cancallforward, 
fullcontact, setvar, regserver, lastms, defaultuser, auth, subscribemwi, vmexten, cid_number, 
callingpres, usereqphone, incominglimit, subscribecontext, musicclass, mohsuggest, allowtransfer, 
autoframing, maxcallbitrate, outboundproxy, rtpkeepalive 
	FROM cc_sip_buddies ORDER BY id;

ALTER TABLE cc_sip_buddies RENAME TO cc_sip_buddies_tmp;
ALTER TABLE _cc_sip_buddies RENAME TO cc_sip_buddies;
DROP TABLE cc_sip_buddies_tmp;

ALTER TABLE cc_sip_buddies ADD PRIMARY KEY (id);
CREATE INDEX sip_friend_hp_index ON cc_sip_buddies (host, port);
CREATE INDEX sip_friend_ip_index ON cc_sip_buddies (ipaddr, port);

CREATE INDEX cc_sip_buddies_name ON cc_sip_buddies USING btree(name);
CREATE INDEX cc_sip_buddies_host ON cc_sip_buddies USING btree(host);
CREATE INDEX cc_sip_buddies_ipaddr ON cc_sip_buddies USING btree(ipaddr);
CREATE INDEX cc_sip_buddies_port ON cc_sip_buddies USING btree(port);

CREATE OR REPLACE VIEW cc_sip_buddies_empty AS
  SELECT id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, canreinvite, context,
  DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, md5secret, nat, permit,
  deny, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, ''::text as secret,
  type, username, disallow, allow, musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar
  FROM cc_sip_buddies;



-- Locking features
ALTER TABLE cc_card ADD COLUMN block SMALLINT NOT NULL DEFAULT 0;
ALTER TABLE cc_card ADD COLUMN lock_pin VARCHAR(15) NULL DEFAULT NULL;
ALTER TABLE cc_card ADD COLUMN lock_date timestamp NULL;


INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES( 'IVR Locking option', 'ivr_enable_locking_option', '0', 
	'Enable the IVR which allow the users to lock their account with an extra lock code.', 1, 'yes,no', 'agi-conf1');

INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES( 'IVR Account Information', 'ivr_enable_account_information', '0', 
	'Enable the IVR which allow the users to retrieve different information about their account.', 1, 'yes,no', 'agi-conf1');

INSERT INTO cc_config ( config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
VALUES( 'IVR Speed Dial', 'ivr_enable_ivr_speeddial', '0', 'Enable the IVR which allow the users add speed dial.', 1, 'yes,no', 'agi-conf1');


ALTER TABLE cc_templatemail ALTER COLUMN messagetext TYPE VARCHAR(3000);
ALTER TABLE cc_templatemail ALTER COLUMN messagetext SET DEFAULT NULL;
ALTER TABLE cc_templatemail ALTER COLUMN messagehtml TYPE VARCHAR(3000);
ALTER TABLE cc_templatemail ALTER COLUMN messagehtml SET DEFAULT NULL;

ALTER TABLE cc_card_group ALTER COLUMN description TYPE VARCHAR(400);
ALTER TABLE cc_card_group ALTER COLUMN description SET DEFAULT NULL;

-- MySQL is "ALTER TABLE cc_config CHANGE config_description config_description VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;"
ALTER TABLE cc_config ALTER  config_description DROP NOT NULL;
ALTER TABLE cc_config ALTER COLUMN config_description SET DEFAULT NULL;
  
UPDATE cc_version SET version = '1.7.0';

-- Commit the whole update;  psql will automatically rollback if we failed at any point
COMMIT;


