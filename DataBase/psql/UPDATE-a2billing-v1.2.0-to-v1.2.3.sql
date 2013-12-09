
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


\set ON_ERROR_STOP ON;

ALTER TABLE cc_card ALTER COLUMN credit TYPE DECIMAL( 15, 5 );
ALTER TABLE cc_card ALTER COLUMN credit SET DEFAULT '0';


ALTER TABLE cc_card ADD COLUMN initialbalance DECIMAL(15,5);
ALTER TABLE cc_card ALTER COLUMN initialbalance SET DEFAULT 0;
UPDATE cc_card SET initialbalance = '0';

-- invoiceday : day of the month when the customer invoice need to be created
ALTER TABLE cc_card ADD COLUMN invoiceday INT;
ALTER TABLE cc_card ALTER COLUMN invoiceday SET DEFAULT 1;
UPDATE cc_card SET invoiceday = '1';

-- autorefill : define if the automatic refill will be permorfed on this card
ALTER TABLE cc_card ADD COLUMN autorefill INT;
ALTER TABLE cc_card ALTER COLUMN autorefill SET DEFAULT 0;
UPDATE cc_card SET autorefill = '0';


-- Auto Refill Report Table
CREATE TABLE cc_autorefill_report (
	id 						BIGSERIAL NOT NULL,
	daterun 					TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	totalcardperform 				INT,
	totalcredit 					DECIMAL(15,5)
);
ALTER TABLE ONLY cc_autorefill_report
ADD CONSTRAINT cc_autorefill_report_pkey PRIMARY KEY (id);



-- Add Buying cost into the reporting
ALTER TABLE cc_call ADD COLUMN buyrate DECIMAL(15,5);
ALTER TABLE cc_call ALTER COLUMN buyrate SET DEFAULT 0;

ALTER TABLE cc_call ADD COLUMN buycost DECIMAL(15,5);
ALTER TABLE cc_call ALTER COLUMN buycost SET DEFAULT 0;




ALTER TABLE cc_card ADD COLUMN loginkey CHAR(40);
ALTER TABLE cc_card ADD COLUMN activatedbyuser char(1) DEFAULT 'f' NOT NULL;

INSERT INTO cc_templatemail VALUES ('forgetpassword', 'info@YourDomain.com', 'YourDomain', 'Login Information', 'Your login information is as below:

Your account is $card_gen

Your password is $password

Your cardalias is $cardalias

http://YourDomain.com/A2BCustomer_UI/


Kind regards,
YourDomain
', '');

INSERT INTO cc_templatemail VALUES ('signupconfirmed', 'info@YourDomain.com', 'YourDomain', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is $card_gen

Your password is $password

To go to your account :
http://YourDomain.com/A2BCustomer_UI/

Kind regards,
YourDomain
', '');
