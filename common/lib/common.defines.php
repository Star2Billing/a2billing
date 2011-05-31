<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2011 - Star2billing S.L.
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



// SETTINGS FOR DATABASE CONNECTION
define ("HOST", isset($A2B->config['database']['hostname'])?$A2B->config['database']['hostname']:null);
define ("PORT", isset($A2B->config['database']['port'])?$A2B->config['database']['port']:null);
define ("USER", isset($A2B->config['database']['user'])?$A2B->config['database']['user']:null);
define ("PASS", isset($A2B->config['database']['password'])?$A2B->config['database']['password']:null);
define ("DBNAME", isset($A2B->config['database']['dbname'])?$A2B->config['database']['dbname']:null);
define ("DB_TYPE", isset($A2B->config['database']['dbtype'])?$A2B->config['database']['dbtype']:null);

// SETTINGS FOR SMTP
define ("SMTP_SERVER", isset($A2B->config['global']['smtp_server'])?$A2B->config['global']['smtp_server']:null);
define ("SMTP_HOST", isset($A2B->config['global']['smtp_host'])?$A2B->config['global']['smtp_host']:null);
define ("SMTP_USERNAME", isset($A2B->config['global']['smtp_username'])?$A2B->config['global']['smtp_username']:null);
define ("SMTP_PASSWORD", isset($A2B->config['global']['smtp_password'])?$A2B->config['global']['smtp_password']:null);
define ("SMTP_PORT", isset($A2B->config['global']['smtp_port'])?$A2B->config['global']['smtp_port']:'25');
define ("SMTP_SECURE", isset($A2B->config['global']['smtp_secure'])?$A2B->config['global']['smtp_secure']:null);

// SETTING FOR REALTIME
define ("USE_REALTIME", isset($A2B->config['global']['use_realtime'])?$A2B->config['global']['use_realtime']:0);


// SIP IAX FRIEND CREATION
define ("FRIEND_TYPE", isset($A2B->config['peer_friend']['type'])?$A2B->config['peer_friend']['type']:null);
define ("FRIEND_ALLOW", isset($A2B->config['peer_friend']['allow'])?$A2B->config['peer_friend']['allow']:null);
define ("FRIEND_CONTEXT", isset($A2B->config['peer_friend']['context'])?$A2B->config['peer_friend']['context']:null);
define ("FRIEND_NAT", isset($A2B->config['peer_friend']['nat'])?$A2B->config['peer_friend']['nat']:null);
define ("FRIEND_AMAFLAGS", isset($A2B->config['peer_friend']['amaflags'])?$A2B->config['peer_friend']['amaflags']:null);
define ("FRIEND_QUALIFY", isset($A2B->config['peer_friend']['qualify'])?$A2B->config['peer_friend']['qualify']:null);
define ("FRIEND_HOST", isset($A2B->config['peer_friend']['host'])?$A2B->config['peer_friend']['host']:null);
define ("FRIEND_DTMFMODE", isset($A2B->config['peer_friend']['dtmfmode'])?$A2B->config['peer_friend']['dtmfmode']:null);

//DIDX.NET API
define ("DIDX_ID", isset($A2B->config['webui']['didx_id'])?$A2B->config['webui']['didx_id']:null);
define ("DIDX_PASS", isset($A2B->config['webui']['didx_pass'])?$A2B->config['webui']['didx_pass']:null);
define ("DIDX_MIN_RATING", isset($A2B->config['webui']['didx_min_rating'])?$A2B->config['webui']['didx_min_rating']:null);
define ("DIDX_SITE", "api.didx.net");
define ("DIDX_RING_TO", isset($A2B->config['webui']['didx_ring_to'])?$A2B->config['webui']['didx_ring_to']:null);

define ("API_LOGFILE", isset($A2B->config['webui']['api_logfile'])?$A2B->config['webui']['api_logfile']:"/var/log/a2billing/");

// BUDDY ASTERISK FILES
define ("BUDDY_SIP_FILE", isset($A2B->config['webui']['buddy_sip_file'])?$A2B->config['webui']['buddy_sip_file']:null);
define ("BUDDY_IAX_FILE", isset($A2B->config['webui']['buddy_iax_file'])?$A2B->config['webui']['buddy_iax_file']:null);

// VOICEMAIL
define ("ACT_VOICEMAIL", false);

// SHOW DONATION
define ("SHOW_DONATION", true);

// AGI
define ("ASTERISK_VERSION", isset($A2B->config['agi-conf1']['asterisk_version'])?$A2B->config['agi-conf1']['asterisk_version']:'1_4');


// Iridium info
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR1", gettext("Address line 1:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR2", gettext("Address line 2:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_ADDR3", gettext("Address line 3:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_POSTCODE", gettext("Postcode:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_COUNTRY", gettext("Country:"));
define ("MODULE_PAYMENT_IRIDIUM_TEXT_CREDIT_CARD_TELEPHONE", gettext("Telephone:"));


# define the amount of emails you want to send per period. If 0, batch processing
# is disabled and messages are sent out as fast as possible
define("MAILQUEUE_BATCH_SIZE", 0);

# define the length of one batch processing period, in seconds (3600 is an hour)
define("MAILQUEUE_BATCH_PERIOD", 3600);

# to avoid overloading the server that sends your email, you can add a little delay
# between messages that will spread the load of sending
# you will need to find a good value for your own server
# value is in seconds (or you can play with the autothrottle below)
define('MAILQUEUE_THROTTLE', 0);



/*
 *		GLOBAL USED VARIABLE
 */
$PHP_SELF = $_SERVER["PHP_SELF"];

$CURRENT_DATETIME = date("Y-m-d H:i:s");

// Store script start time
$_START_TIME = time();
mt_start();


// A2BILLING COPYRIGHT & CONTACT
define ("TEXTCONTACT", gettext("This software has been created by Areski under AGPL licence. For futher information, feel free to contact me:"));
define ("EMAILCONTACT", "areski@gmail.com");

// A2BILLING INFO  
define ("COPYRIGHT", "A2Billing 1.9.4 (Cuprum), A2Billing is a ".'<a href="http://www.star2billing.com/solutions/voip-billing/" target="_blank">voip billing software</a>' . " licensed under the ".'<a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html" target="_blank">AGPL 3</a>' . ". <br/>" . "Copyright (C) 2004-2011 - Star2billing S.L. <a href=\"http://www.star2billing.com\" target=\"_blank\">http://www.star2billing.com/</a>");

define ("CCMAINTITLE", gettext("A2Billing Portal"));




/*
 *		CONNECT / DISCONNECT DATABASE
 */ 
function DbConnect()
{
	return Connection::GetDBHandler();
}

function DbDisconnect($DBHandle)
{
	$DBHandle ->disconnect();
}

