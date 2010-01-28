
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


ALTER TABLE `cc_subscription_fee` ADD `startdate` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
ADD `stopdate` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';

RENAME TABLE `cc_subscription_fee`  TO `cc_subscription_service` ;
ALTER TABLE `cc_card_subscription` ADD `paid_status` TINYINT NOT NULL ;
ALTER TABLE `cc_card_subscription` ADD `last_run` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `cc_card_subscription` ADD `next_billing_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `cc_card_subscription` ADD `limit_pay_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';


INSERT INTO cc_config (config_title, config_key, config_value, config_description, config_valuetype, config_listvalues, config_group_title) VALUES ('Days to bill before month anniversary', 'subscription_bill_days_before_anniversary', '3', 'Numbers of days to bill a subscription service before the month anniversary', 0, NULL, 'global');


INSERT INTO cc_templatemail (id_language, mailtype, fromemail, fromname, subject, messagetext)
    VALUES	('en', 'subscription_paid', 'info@mydomainname.com', 'COMPANY NAME', 'Subscription notification - $subscription_label$ ($subscription_id$)', 'BALANCE REMAINING $balance_remaining$ $base_currency$\n\nAn automatic taking away of : $subscription_fee$ $base_currency$ has been carry out of your account to pay your service ($subscription_label$)\n\nMonthly cost for DID : $did_cost$ $base_currency$\n\n'),
		('en', 'subscription_unpaid', 'info@mydomainname.com', 'COMPANY NAME', 'Subscription notification - $subscription_label$ ($subscription_id$)', 'BALANCE REMAINING $balance_remaining$ $base_currency$\n\nYour credit is not enough to pay your service subscription ($subscription_label$), the monthly cost is : $subscription_fee$ $base_currency$\n\nYou have $days_remaining$ days to pay the invoice (REF: $invoice_ref$ ) or your accoun will automatically deactivated until you paid your invoice \n\n'),
		('en', 'subscription_disable_card', 'info@mydomainname.com', 'COMPANY NAME', 'Card deactivated - unpaid service $subscription_label$ ($subscription_id$)', 'The account has been automatically deactivated until you paid your invoice !\n\n');



UPDATE cc_version SET version = '1.6.0';



