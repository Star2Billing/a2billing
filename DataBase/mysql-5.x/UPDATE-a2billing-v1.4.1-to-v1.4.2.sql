
CREATE TABLE cc_message_agent (
    id BIGINT NOT NULL AUTO_INCREMENT ,
    id_agent INT NOT NULL ,
    message LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
    type TINYINT NOT NULL DEFAULT '0' ,
    logo TINYINT NOT NULL DEFAULT '1',
    order_display INT NOT NULL ,
    PRIMARY KEY ( id )
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


UPDATE cc_config SET config_value = 'PREPAID',
    config_description = 'billing type of the new card( value : POSTPAID or PREPAID) .',
    config_listvalues = 'PREPAID,POSTPAID' WHERE config_key ='cid_auto_create_card_typepaid';
UPDATE config SET config_value = '1' WHERE config_key ='cid_auto_create_card_credit_limit';
UPDATE config SET config_value = '1' WHERE config_key ='cid_auto_create_card_tariffgroup';