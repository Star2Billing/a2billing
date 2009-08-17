<?php
$disable_load_conf = true;

include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");


getpost_ifset(array (
	'error',
	'password',
	'username'
));

$smarty -> assign("error", $error);
$password = base64_decode($password);

$smarty -> assign("username", $username);
$smarty -> assign("password", $password);

$smarty -> display('index.tpl');

