
SET ON_ERROR_STOP;

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
