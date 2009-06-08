<?php
Class Connection {
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

