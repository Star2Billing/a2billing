
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
**/

-- Update Version
UPDATE cc_version SET version = '2.0.12';

CREATE OR REPLACE VIEW cc_sip_buddies_empty AS
     SELECT id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, canreinvite, context, DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, '' as md5secret, nat, deny, permit, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, '' as secret, type, username, disallow, allow, musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar, regserver, lastms, defaultuser, auth, subscribemwi, vmexten, cid_number, callingpres, usereqphone, incominglimit, subscribecontext, musicclass, mohsuggest, allowtransfer, autoframing, maxcallbitrate, outboundproxy, rtpkeepalive, useragent, callbackextension
     FROM cc_sip_buddies;
