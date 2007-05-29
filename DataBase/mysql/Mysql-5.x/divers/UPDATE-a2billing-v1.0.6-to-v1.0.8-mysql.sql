--
-- A2Billing database - update database schema - v1.0.6 to update to v1.0.8
--

/* 
To create the database : 

mysql -u root -p"root password" < UPDATE-a2billing-v1.0.6-to-v1.0.8-mysql.sql

*/

 
--
--	MYSQL 5.x - MYSQL 5.x - MYSQL 5.x - MYSQL 5.x - MYSQL 5.x
--
--
-- Predictive Dialer update  database - Create database schema
--



CREATE TABLE cc_campaign (
    id INT NOT NULL AUTO_INCREMENT,    
    campaign_name CHAR(50) NOT NULL,
    creationdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    startingdate TIMESTAMP, 
    expirationdate TIMESTAMP,
    description MEDIUMTEXT,
    id_trunk INT DEFAULT 0,
    secondusedreal INT DEFAULT 0,
    nb_callmade INT DEFAULT 0,
    enable INT DEFAULT 0 NOT NULL,	
    PRIMARY KEY (id),
    UNIQUE cons_phonelistname (campaign_name)
);


CREATE TABLE cc_phonelist (
    id INT NOT NULL AUTO_INCREMENT,
    id_cc_campaign INT DEFAULT 0 NOT NULL,
    numbertodial CHAR(50) NOT NULL,
    name CHAR(60) NOT NULL,
    inuse INT DEFAULT 0,
    enable INT DEFAULT 1 NOT NULL,    
    num_trials_done INT DEFAULT 0,
    creationdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,	
    last_attempt TIMESTAMP,
    secondusedreal INT DEFAULT 0,
    additionalinfo MEDIUMTEXT,
    PRIMARY KEY (id)
);
CREATE INDEX ind_cc_phonelist_numbertodial ON cc_phonelist (numbertodial);


ALTER TABLE cc_card ADD COLUMN id_campaign INT;
ALTER TABLE cc_card ADD COLUMN num_trials_done BIGINT;
ALTER TABLE cc_card ALTER COLUMN num_trials_done SET DEFAULT 0;
UPDATE cc_card SET num_trials_done = '0';
ALTER TABLE cc_card ADD COLUMN callback CHAR(50);
