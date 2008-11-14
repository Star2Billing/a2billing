
-- Never too late to add some indexes :D

ALTER TABLE cc_call ADD INDEX ( username );
ALTER TABLE cc_call ADD INDEX ( starttime );
ALTER TABLE cc_call ADD INDEX ( terminatecause );
ALTER TABLE cc_call ADD INDEX ( calledstation );


ALTER TABLE cc_card ADD INDEX ( creationdate );
ALTER TABLE cc_card ADD INDEX ( username );



OPTIMIZE TABLE cc_card;
OPTIMIZE TABLE cc_call;