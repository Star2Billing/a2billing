-- New payment Gateway
INSERT INTO cc_payment_methods (id, payment_method, payment_filename) VALUES(5, 'iridium', 'iridium.php');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description)
VALUES ('MerchantID', 'MODULE_PAYMENT_IRIDIUM_MERCHANTID', 'yourMerchantId', 'Your Mechant Id provided by Iridium');
INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description)
VALUES ('Password', 'MODULE_PAYMENT_IRIDIUM_PASSWORD', 'Password', 'password for Iridium merchant');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description)
VALUES ('PaymentProcessor', 'MODULE_PAYMENT_IRIDIUM_GATEWAY', 'PaymentGateway URL ', 'Enter payment gateway URL');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description)
VALUES ('PaymentProcessorPort', 'MODULE_PAYMENT_IRIDIUM_GATEWAY_PORT', 'PaymentGateway Port ', 'Enter payment gateway port');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function)
VALUES ('Transaction Currency', 'MODULE_PAYMENT_IRIDIUM_CURRENCY', 'Selected Currency', 'The default currency for the payment transactions', 'tep_cfg_select_option(array(\'Selected Currency\',\'EUR\', \'USD\', \'GBP\', \'HKD\', \'SGD\', \'JPY\', \'CAD\', \'AUD\', \'CHF\', \'DKK\', \'SEK\', \'NOK\', \'ILS\', \'MYR\', \'NZD\', \'TWD\', \'THB\', \'CZK\', \'HUF\', \'SKK\', \'ISK\', \'INR\'), ');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function)
VALUES ('Transaction Language', 'MODULE_PAYMENT_IRIDIUM_LANGUAGE', 'Selected Language', 'The default language for the payment transactions', 'tep_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ');

INSERT INTO cc_configuration (configuration_title, configuration_key, configuration_value, configuration_description, set_function)
VALUES ('Enable iridium Module', 'MODULE_PAYMENT_IRIDIUM_STATUS', 'False', 'Do you want to accept Iridium payments?','tep_cfg_select_option(array(\'True\', \'False\'), ');


