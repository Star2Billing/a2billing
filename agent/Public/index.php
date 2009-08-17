<?php
$disable_load_conf = true;

include ("../lib/agent.defines.php");
include ("../lib/agent.smarty.php");

getpost_ifset(array (
	'error'
));

$smarty -> assign("error", $error);
$smarty -> display('index.tpl');


