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

DROP TABLE IF EXISTS cc_call_archive;
CREATE TABLE cc_call_archive (
    id BIGSERIAL NOT NULL,
    sessionid varchar(40)  NOT NULL,
    uniqueid varchar(30) NOT NULL,
    card_id bigint NOT NULL,
    nasipaddress varchar(30) NOT NULL,
    starttime timestamp without time zone NOT NULL default NOW(),
--    stoptime timestamp without time zone NOT NULL default '0000-00-00 00:00:00',
    stoptime timestamp without time zone NOT NULL default '1970-01-01 00:00:00',
    sessiontime int default NULL,
    calledstation varchar(30) NOT NULL,
    sessionbill float default NULL,
    id_tariffgroup int default NULL,
    id_tariffplan int default NULL,
    id_ratecard int default NULL,
    id_trunk int default NULL,
    sipiax int default 0,
    src varchar(40) NOT NULL,
    id_did int default NULL,
    buycost decimal(15,5) default '0.00000',
    id_card_package_offer int default 0,
    real_sessiontime int default NULL,
    dnid varchar(40) NOT NULL,
    terminatecauseid smallint default 1,
    destination int default 0,
    PRIMARY KEY  (id)
);

CREATE INDEX cc_call_archive_idx_starttime ON cc_call_archive (starttime);
CREATE INDEX cc_call_archive_idx_calledstation ON cc_call_archive (calledstation);
CREATE INDEX cc_call_archive_idx_terminatecauseid ON cc_call_archive (terminatecauseid);


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Archive Calls', 'archive_call_prior_x_month', '24', 'A cront can be enabled in order to archive your CDRs, this setting allow to define prior which month it will archive', 0, NULL, 'backup');
 

ALTER TABLE cc_logpayment ADD COLUMN agent_id BIGINT NULL;
ALTER TABLE cc_logrefill ADD COLUMN agent_id BIGINT NULL;

-- for change type of destination@cc_ratecard
DROP VIEW cc_callplan_lcr;
DROP TRIGGER cc_ratecard_validate_regex ON cc_ratecard;
ALTER TABLE cc_ratecard ALTER COLUMN destination TYPE BIGINT;
ALTER TABLE cc_ratecard ALTER COLUMN destination SET NOT NULL;
ALTER TABLE cc_ratecard ALTER COLUMN destination SET DEFAULT 0;
CREATE TRIGGER cc_ratecard_validate_regex BEFORE INSERT OR UPDATE ON cc_ratecard FOR EACH ROW EXECUTE PROCEDURE cc_ratecard_validate_regex();

CREATE VIEW cc_callplan_lcr AS
	SELECT cc_ratecard.id, cc_prefix.destination, cc_ratecard.dialprefix, cc_ratecard.buyrate, cc_ratecard.rateinitial, cc_ratecard.startdate, cc_ratecard.stopdate, cc_ratecard.initblock, cc_ratecard.connectcharge, cc_ratecard.id_trunk , cc_ratecard.idtariffplan , cc_ratecard.id as ratecard_id, cc_tariffgroup.id AS tariffgroup_id
	
	FROM cc_tariffgroup 
	RIGHT JOIN cc_tariffgroup_plan ON cc_tariffgroup_plan.idtariffgroup=cc_tariffgroup.id 
	INNER JOIN cc_tariffplan ON (cc_tariffplan.id=cc_tariffgroup_plan.idtariffplan ) 
	LEFT JOIN cc_ratecard ON cc_ratecard.idtariffplan=cc_tariffplan.id
	LEFT JOIN cc_prefix ON prefix=cc_ratecard.destination
	WHERE cc_ratecard.id IS NOT NULL;


UPDATE cc_version SET version = '1.4.5';


-- Commit the whole update;  psql will automatically rollback if we failed at any point
COMMIT;
