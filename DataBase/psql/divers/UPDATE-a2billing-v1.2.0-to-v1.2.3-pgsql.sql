


-- inital balance : it would be used by the cron in order to refill automatically each month
ALTER TABLE cc_card ADD COLUMN initialbalance numeric(15,5);
ALTER TABLE cc_card ALTER COLUMN initialbalance SET DEFAULT 0;
UPDATE cc_card SET initialbalance = '0';

-- invoiceday : day of the month when the customer invoice need to be created 
ALTER TABLE cc_card ADD COLUMN invoiceday integer;
ALTER TABLE cc_card ALTER COLUMN invoiceday SET DEFAULT 1;
UPDATE cc_card SET invoiceday = '1';

-- autorefill : define if the automatic refill will be permorfed on this card
ALTER TABLE cc_card ADD COLUMN autorefill integer;
ALTER TABLE cc_card ALTER COLUMN autorefill SET DEFAULT 0;
UPDATE cc_card SET autorefill = '0';

-- Auto Refill Report Table	
CREATE TABLE cc_autorefill_report (
	id bigserial NOT NULL,
	daterun timestamp(0) without time zone DEFAULT now(),
	totalcardperform integer,
	totalcredit double precision
);
ALTER TABLE ONLY cc_autorefill_report
ADD CONSTRAINT cc_autorefill_report_pkey PRIMARY KEY (id);


-- Add Buying cost into the reporting
ALTER TABLE cc_call ADD COLUMN buyrate numeric(15,5);
ALTER TABLE cc_call ALTER COLUMN buyrate SET DEFAULT 0;

ALTER TABLE cc_call ADD COLUMN buycost numeric(15,5);
ALTER TABLE cc_call ALTER COLUMN buycost SET DEFAULT 0;







ALTER TABLE cc_card ADD COLUMN loginkey text;
ALTER TABLE cc_card ADD COLUMN activatedbyuser boolean DEFAULT false NOT NULL;

INSERT INTO cc_templatemail VALUES ('forgetpassword', 'info@call-labs.com', 'Call-Labs', 'Login Information', 'Your login information is as below:

Your account is $card_gen

Your password is $password

Your cardalias is $cardalias

http://call-labs.com/A2BCustomer_UI/


Kind regards,
Call Labs
', '');

INSERT INTO cc_templatemail VALUES ('signupconfirmed', 'info@call-labs.com', 'Call-Labs', 'SIGNUP CONFIRMATION', 'Thank you for registering with us

Please make sure you active your account by making payment to us either by
credit card, wire transfer, money order, cheque, and western union money
transfer, money Gram, and Pay pal.

Your account is $card_gen

Your password is $password

To go to your account :
http://call-labs.com/A2BCustomer_UI/

Kind regards,
Call Labs
', '');
