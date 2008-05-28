<?php
include ("../lib/agent.defines.php");
include ("../lib/agent.smarty.php");

$smarty->assign("error", $_GET["error"]);

$smarty->display('index.tpl');

?>
