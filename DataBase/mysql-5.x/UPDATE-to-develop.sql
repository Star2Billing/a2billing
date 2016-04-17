
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * 2016 copyright Roman Davydov <openvoip.co@gmail.com>
**/

-- ICustomer bundle specific SQL changes

alter table `cc_version`
    add primary key(`version`);

-- Openvoip.co customization SQL changes

alter table `cc_trunk`
    add column `minutes_per_day` int(11)   NULL DEFAULT '0' after `if_max_use`,
    add column `attempt_statuses` varchar(255)  COLLATE utf8_bin NULL DEFAULT 'CHANUNAVAIL,CONGESTION' after `minutes_per_day`,
    add column `attempt_condition` int(11) unsigned   NULL DEFAULT '0' after `attempt_statuses`,
    add column `attempt_count` int(11) unsigned   NULL DEFAULT '0' after `attempt_condition`,
    add column `priority` int(11)   NULL DEFAULT '0' after `attempt_count`,
    add column `attempt_delay` int(11) DEFAULT '0' after `priority`,
    add column `calls_per_day` int(11) DEFAULT '0' after `attempt_delay`,
        add column `trunk_GMT` int(11) DEFAULT '0' after `calls_per_day`,
    change `removeprefix` `removeprefix` varchar (2048)  NULL;

CREATE TABLE `cc_trunk_counter` (                 
    `id_trunk` int(10) unsigned NOT NULL,           
    `calldate` date NOT NULL,                       
    `seconds` int(11) DEFAULT '0',                  
    `last_call_time` int(11) NOT NULL DEFAULT '0',  
    `success_calls` int(11) DEFAULT '0',            
    `minutes` int(11) DEFAULT '0',                  
    PRIMARY KEY (`id_trunk`,`calldate`)             
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

alter table `cc_provider`
    add column `id_cc_card` int(11)   NOT NULL DEFAULT '0' after `description`;

alter table `cc_ratecard`
    add column `is_disabled` int(1) unsigned   NOT NULL DEFAULT '0' after `destination`;

alter table `cc_tariffplan` 
    add column `trunk_algo` int(10) unsigned   NULL DEFAULT '0' after `calleridprefix`,
        add column `algo_break_hc` varchar (1024)  NULL  after `trunk_algo`;

create table `cc_tariffplan_trunk`(
    `idtariffplan` int(11) NOT NULL,
    `idtrunk` int(11) NOT NULL,
    PRIMARY KEY (`idtariffplan`,`idtrunk`)
)Engine=MyISAM DEFAULT CHARSET='utf8';


DELIMITER $$
DROP FUNCTION IF EXISTS `a2billing`.`getCurDateByTrunkTZ`$$
CREATE FUNCTION `a2billing`.`getCurDateByTrunkTZ`(id_trunk INT)
RETURNS DATE DETERMINISTIC
BEGIN
    declare server_GMT varchar(10);
    declare trunk_GMT varchar(10);
    
    -- getting server GMT
    select config_value into server_GMT from cc_config where config_key = 'server_GMT' and config_group_title = 'global' and (config_value REGEXP "^(GMT)?(\\-|\\+)[[:digit:]]{1,2}\\:[[:digit:]]{1,2}$") limit 1;
    IF (ISNULL(server_GMT)) THEN
        set server_GMT = '+00:00';
    ELSE
        IF SUBSTRING(server_GMT, 1, 3) LIKE 'gmt' THEN
            set server_GMT = SUBSTRING(server_GMT, 4);
        END IF;
    END IF;

    -- getting trunk GMT
    select tz.gmttime into trunk_GMT from cc_timezone tz where tz.id = (select t.trunk_GMT from cc_trunk t where t.id_trunk = id_trunk limit 1) limit 1;
    IF (ISNULL(trunk_GMT)) THEN
        set trunk_GMT = '+00:00';
    ELSE
        IF SUBSTRING(trunk_GMT, 1, 3) LIKE 'gmt' THEN
            set trunk_GMT = SUBSTRING(trunk_GMT, 4);
        END IF;
    END IF;

    -- adjusting current date
    return DATE(CONVERT_TZ(NOW(), server_GMT, trunk_GMT));

END$$
DELIMITER ;

-- Reccuring service improvement, added new condition
alter table cc_service add `condition5x` float NOT NULL DEFAULT '0';

-- Insert new config parameter allowed_groups
insert into cc_config
(config_title, config_key, config_description, config_valuetype, config_group_title)
values
('Allowed card groups', 'allowed_groups', 'The list of allowed card groups to be authenticated. Just list groups IDs with commas, like "1,2,3,4". Allows all if empty', '0', 'agi-conf1');

-- Insert new config parameter customer_history_log
insert into cc_config
(config_title, config_key, config_description, config_valuetype, config_value, config_listvalues, config_group_title)
values
('Log to customer history', 'customer_history_log', 'Enable logging to cusomter history', '1', '1', 'yes,no', 'agi-conf1');

-- END Openvoip.co customization SQL changes

