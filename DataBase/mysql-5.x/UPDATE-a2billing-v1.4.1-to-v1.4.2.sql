 CREATE TABLE cc_message_agent (
    id BIGINT NOT NULL AUTO_INCREMENT ,
    id_agent INT NOT NULL ,
    message LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin NULL ,
    type TINYINT NOT NULL DEFAULT '0' ,
    logo TINYINT NOT NULL DEFAULT '1',
    order_display INT NOT NULL ,
    PRIMARY KEY ( id )
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

