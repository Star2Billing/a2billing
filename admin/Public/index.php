<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.smarty.php");

$smarty->assign("error", $_GET["error"]);
$smarty->display('index.tpl');

?>
