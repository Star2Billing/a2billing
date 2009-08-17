<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.smarty.php");

getpost_ifset(array (
	'error'
));

$smarty -> assign("error", $error);
$smarty -> display('index.tpl');

