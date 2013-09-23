
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

alter table `cc_trunk`
	add column `minutes_per_day` int(11)   NULL DEFAULT '0' after `if_max_use`, 
	add column `attempt_statuses` varchar(255)  COLLATE utf8_bin NULL DEFAULT 'CHANUNAVAIL,CONGESTION' after `minutes_per_day`, 
	add column `attempt_condition` int(11) unsigned   NULL DEFAULT '0' after `attempt_statuses`, 
	add column `attempt_count` int(11) unsigned   NULL DEFAULT '0' after `attempt_condition`, COMMENT='',
	add column `priority` int(11)   NULL DEFAULT '0' after `attempt_count`, COMMENT=''
        add column `attempt_delay` int(11) DEFAULT '0' after `priority`, COMMENT='';

create table `cc_trunk_counter`(
	`id_trunk` int(10) unsigned NOT NULL   , 
	`calldate` date NOT NULL   , 
	`seconds` int(11) NULL  DEFAULT '0'  , 
	PRIMARY KEY (`id_trunk`,`calldate`) 
)Engine=MyISAM DEFAULT CHARSET='utf8';

alter table `cc_provider`
	add column `id_cc_card` int(11)   NOT NULL DEFAULT '0' after `description`, COMMENT='';

alter table `cc_ratecard`
	add column `is_disabled` int(1) unsigned   NOT NULL DEFAULT '0' after `destination`, COMMENT='';

alter table `cc_tariffplan` 
	add column `trunk_algo` int(10) unsigned   NULL DEFAULT '0' after `calleridprefix`, COMMENT='';

create table `cc_tariffplan_trunk`(
	`idtariffplan` int(11) NOT NULL, 
	`idtrunk` int(11) NOT NULL, 
	PRIMARY KEY (`idtariffplan`,`idtrunk`) 
)Engine=MyISAM DEFAULT CHARSET='utf8';

insert into cc_config
(config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title)
values
('AGI Sound', 'sound-beep', 'beep', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-dollar2', 'dollar2', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-menu_', 'menu_', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-month', 'month', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-num_', 'num_', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-account-firstused', 'prepaid-account-firstused', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-account-has-locked', 'prepaid-account-has-locked', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-account-nolocked', 'prepaid-account-nolocked', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-account_refill', 'prepaid-account_refill', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-assigned-speeddial', 'prepaid-assigned-speeddial', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-auth-fail', 'prepaid-auth-fail', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-call-duration', 'prepaid-call-duration', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-callfollowme', 'prepaid-callfollowme', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-card-expired', 'prepaid-card-expired', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-card-in-use', 'prepaid-card-in-use', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-cent2', 'prepaid-cent2', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-cost-call', 'prepaid-cost-call', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-dest-unreachable', 'prepaid-dest-unreachable', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-enter-cid', 'prepaid-enter-cid', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-enter-code-lock-account', 'prepaid-enter-code-lock-account', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-enter-dialcode', 'prepaid-enter-dialcode', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-enter-pin-lock', 'prepaid-enter-pin-lock', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-enter-pin-number', 'prepaid-enter-pin-number', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-enter-speeddial', 'prepaid-enter-speeddial', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-final', 'prepaid-final', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-invalid-digits', 'prepaid-invalid-digits', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-is-used-for', 'prepaid-is-used-for', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-isbusy', 'prepaid-isbusy', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-lastcall', 'prepaid-lastcall', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-locking-accepted', 'prepaid-locking-accepted', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-minute', 'prepaid-minute', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-minute2', 'prepaid-minute2', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-minutes', 'prepaid-minutes', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-no-call', 'prepaid-no-call', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-no-card-entered', 'prepaid-no-card-entered', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-no-dialcode', 'prepaid-no-dialcode', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-no-enough-credit', 'prepaid-no-enough-credit', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-no-enough-credit-stop', 'prepaid-no-enough-credit-stop', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-no-pin-lock', 'prepaid-no-pin-lock', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-noanswer', 'prepaid-noanswer', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-not-authorized-phonenumber', 'prepaid-not-authorized-phonenumber', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-of-free-package-calls', 'prepaid-of-free-package-calls', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-per-minutes', 'prepaid-per-minutes', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-phonenumber-to-speeddial', 'prepaid-phonenumber-to-speeddial', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-point', 'prepaid-point', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-press1-add-speeddial', 'prepaid-press1-add-speeddial', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-press1-change-speeddial', 'prepaid-press1-change-speeddial', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-press4-info', 'prepaid-press4-info', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-press5-lock', 'prepaid-press5-lock', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-press9-new-speeddial', 'prepaid-press9-new-speeddial', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-re-enter-press1-confirm', 'prepaid-re-enter-press1-confirm', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-refill_card_with_voucher', 'prepaid-refill_card_with_voucher', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-remaining', 'prepaid-remaining', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-second', 'prepaid-second', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-second2', 'prepaid-second2', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-seconds', 'prepaid-seconds', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-sipiax-enternumber', 'prepaid-sipiax-enternumber', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-sipiax-num-nomatch', 'prepaid-sipiax-num-nomatch', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-sipiax-press9', 'prepaid-sipiax-press9', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-speeddial-saved', 'prepaid-speeddial-saved', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-the-number-u-dialed-is', 'prepaid-the-number-u-dialed-is', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-the-phonenumber', 'prepaid-the-phonenumber', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-voucher_enter_number', 'prepaid-voucher_enter_number', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-you-have', 'prepaid-you-have', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-you-have-dialed', 'prepaid-you-have-dialed', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-prepaid-your-locking-is', 'prepaid-your-locking-is', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-seconds', 'seconds', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-this', 'this', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-vm-and', 'vm-and', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-voucher_does_not_exist', 'voucher_does_not_exist', 'Agi config specific sound', 0, NULL, 'agi-conf1'),
('AGI Sound', 'sound-weeks', 'weeks', 'Agi config specific sound', 0, NULL, 'agi-conf1');
