
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
 * 
**/


ALTER TABLE cc_callback_spool CHANGE variable variable VARCHAR( 300 ) DEFAULT NULL;

-- fix various uses of ISO-3166-1 alpha-2 rather than alpha-3
UPDATE cc_country SET countrycode='BVT' WHERE countrycode='BV';
UPDATE cc_country SET countrycode='IOT' WHERE countrycode='IO';
UPDATE cc_country SET countrycode='HMD' WHERE countrycode='HM';
UPDATE cc_country SET countrycode='PCN' WHERE countrycode='PN';
UPDATE cc_country SET countrycode='SGS' WHERE countrycode='GS';
UPDATE cc_country SET countrycode='SJM' WHERE countrycode='SJ';
UPDATE cc_country SET countrycode='TLS' WHERE countrycode='TL';
UPDATE cc_country SET countrycode='UMI' WHERE countrycode='UM';
UPDATE cc_country SET countrycode='ESH' WHERE countrycode='EH';

-- integrate changes from ISO-3166-1 newsletters V-1 to V-12
UPDATE cc_country SET countryname='Lao People''s Democratic Republic' WHERE countrycode='LAO';
UPDATE cc_country SET countryname='Timor-Leste', countryprefix='670' WHERE countrycode='TLS';
UPDATE cc_country SET countryprefix='0' WHERE countrycode='TMP';
