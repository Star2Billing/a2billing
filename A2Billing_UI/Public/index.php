<?php
include ("../lib/admin.defines.php");
include ("../lib/smarty.php");

$smarty->assign("error", $_GET["error"]);

$smarty->display('index.tpl');

?>
