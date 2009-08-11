<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Rachid <rachid.belaid@gmail.com>
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

class Connection {

	private static $DBHandler;
	private static $MytoPgklass;

	private function __construct() {

		$ADODB_CACHE_DIR = '/tmp';
		/*	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;	*/

		if (DB_TYPE == "postgres") {
			$datasource = 'pgsql://' . USER . ':' . PASS . '@' . HOST . '/' . DBNAME;
		} else {
			$datasource = 'mysql://' . USER . ':' . PASS . '@' . HOST . '/' . DBNAME;
		}
		
		$DBHandle = NewADOConnection($datasource);
		if (!$DBHandle)
			die("Connection failed");

		if (DB_TYPE == "mysqli") {
			$DBHandle->Execute('SET AUTOCOMMIT=1');
		}
		if (DB_TYPE == "mysqli" || DB_TYPE == "mysql") {
			$DBHandle->Execute("SET NAMES 'UTF8'");
		}

		self :: $DBHandler = $DBHandle;
	}

	static function GetDBHandler() {
		if (empty (self :: $DBHandler)) {
			$connection = new Connection();
		}
		return self :: $DBHandler;
	}

	static function CleanExecute($QUERY) {
		if (empty (self :: $DBHandler)) {
			$connection = new Connection();
		} else {
			$connection = self :: $DBHandler;
		}

		if (DB_TYPE == "postgres") {
			if (empty (self :: $MytoPgklass)) {
				self :: $MytoPgklass = new MytoPg(0);
			}

			// convert MySQLisms to be Postgres compatible
			self :: $MytoPgklass -> My_to_Pg($QUERY);
		}
		
		return $connection -> Execute($QUERY);
	}

}

