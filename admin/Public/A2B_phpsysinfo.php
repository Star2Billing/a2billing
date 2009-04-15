<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");


if (! has_rights (ACX_MAINTENANCE)) { 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

check_demo_mode_intro();


// #### HEADER SECTION
$smarty->display('main.tpl');

?>
<br>

<center>

<iframe src ="../phpsysinfo/" width="1000" height="800">
</iframe>

</center>
<?php


// #### FOOTER SECTION
$smarty->display('footer.tpl');

