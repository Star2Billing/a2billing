<?php
include ("lib/defines.php");
include ("lib/module.access.php");
include ("lib/smarty.php");


$smarty->assign("error", $_GET["error"]);
$password = base64_decode($_GET["password"]);

$smarty->assign("username", $_GET["username"]);
$smarty->assign("password", $password);

$smarty->display('index.tpl');

?>
