
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

alter table `cc_trunk`
	add column `minutes_per_day` int(11)   NULL DEFAULT '0' after `if_max_use`,
	add column `attempt_statuses` varchar(255)  COLLATE utf8_bin NULL DEFAULT 'CHANUNAVAIL,CONGESTION' after `minutes_per_day`,
	add column `attempt_condition` int(11) unsigned   NULL DEFAULT '0' after `attempt_statuses`,
	add column `attempt_count` int(11) unsigned   NULL DEFAULT '0' after `attempt_condition`,
	add column `priority` int(11)   NULL DEFAULT '0' after `attempt_count`,
	add column `attempt_delay` int(11) DEFAULT '0' after `priority`,
	add column `calls_per_day` int(11) DEFAULT '0' after `attempt_delay`,
	change `removeprefix` `removeprefix` varchar (2048)  NULL;

create table `cc_trunk_counter`(
	`id_trunk` int(10) unsigned NOT NULL,
	`calldate` date NOT NULL,
	`seconds` int(11) NULL  DEFAULT '0',
        `last_call_time` int(11) NOT NULL DEFAULT '0',
        `success_calls` int(11) DEFAULT '0',
	PRIMARY KEY (`id_trunk`,`calldate`)
)Engine=MyISAM DEFAULT CHARSET='utf8';

alter table `cc_provider`
	add column `id_cc_card` int(11)   NOT NULL DEFAULT '0' after `description`;

alter table `cc_ratecard`
	add column `is_disabled` int(1) unsigned   NOT NULL DEFAULT '0' after `destination`;

alter table `cc_tariffplan` 
	add column `trunk_algo` int(10) unsigned   NULL DEFAULT '0' after `calleridprefix`;

create table `cc_tariffplan_trunk`(
	`idtariffplan` int(11) NOT NULL,
	`idtrunk` int(11) NOT NULL,
	PRIMARY KEY (`idtariffplan`,`idtrunk`)
)Engine=MyISAM DEFAULT CHARSET='utf8';
