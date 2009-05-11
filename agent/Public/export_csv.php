<?php
include ("../lib/agent.defines.php");
require_once ("../lib/iam_csvdump.php");
include ("../lib/agent.module.access.php");

if (!has_rights(ACX_CALL_REPORT) && !has_rights(ACX_CUSTOMER)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array ( 'var_export', 'var_export_type' ));

if (strlen($var_export) == 0) {
	$var_export = 'pr_sql_export';
}

/*
// DEBUG 
echo "var_export = $var_export <br>";
echo "SESSION var_export =".$_SESSION[$var_export]."</br>";
echo "var_export_type = $var_export_type <br>";
exit; */

#  Set the parameters: SQL Query, hostname, databasename, dbuser and password
$dumpfile = new iam_csvdump;

#  Call the CSV Dumping function and THAT'S IT!!!!  A file named dump.csv is sent to the user for download

if (strlen($_SESSION[$var_export]) < 10) {
	echo gettext("ERROR CSV EXPORT");
} else {
	$log = new Logger();
	if (strcmp($var_export_type, "type_csv") == 0) {
		$myfileName = "Dump_" . date("Y-m-d");
		$log->insertLog($_SESSION["admin_id"], 2, "FILE EXPORTED", "A File in CSV Format is exported by User, File Name= " . $myfileName . ".csv", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], '');
		$dumpfile->dump($_SESSION[$var_export], $myfileName, "csv", DBNAME, USER, PASS, HOST, DB_TYPE);
	}
	elseif (strcmp($var_export_type, "type_xml") == 0) {
		$myfileName = "Dump_" . date("Y-m-d");
		$log->insertLog($_SESSION["admin_id"], 2, "FILE EXPORTED", "A File in XML Format is exported by User, File Name= " . $myfileName . ".xml", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], '');
		$dumpfile->dump($_SESSION[$var_export], $myfileName, "xml", DBNAME, USER, PASS, HOST, DB_TYPE);
	}
	$log = null;
}

