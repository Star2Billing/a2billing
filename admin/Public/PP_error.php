<?php
include ("../lib/admin.defines.php");
session_destroy();
getpost_ifset(array('err_type','c'));

if (!isset($err_type)) {
	$err_type = 0;
}

//Error Type == 0 Mean Critical Error dont need to show left menu.
//Error Type == 1 Mean User generated error.and it will show menu to him too.
include ("../lib/admin.smarty.php");
if($err_type == 0) {
	$smarty->display('header.tpl');
} else {
	include ("../lib/admin.module.access.php");
	$smarty->display('main.tpl');
}


if (!isset($c))	$c="0";

$error["0"] 			= gettext("ERROR : ACCESS REFUSED");
$error["syst"] 			= gettext("Sorry a problem occur on our system, please try later!");
$error["errorpage"] 	= gettext("There is an error on this page!");
$error["accessdenied"] 	= gettext("Sorry, you don t have access to this page !");
$error["construction"] 	= gettext("Sorry, this page is in construction !");
$error["ERR-0001"] 		= gettext("Invalid User Id !");
$error["ERR-0002"] 		= gettext("No such card number found. Please check your card number!");

?>

<div id="login-wrapper" class="login-border-up">
	<div class="login-border-down">
	<div class="login-border-center">
	<table>
	<tr>
		<td class="login-title" colspan="2">
			<font size="3"> <?php echo gettext("ERROR PAGE");?> </font>
		</td>
	</tr>
	<tr>
		<td width="70px" align="center">
			<img src="<?php echo KICON_PATH;?>/system-config-rootpassword.gif"> 
		</td>
		<td align="center">
			<b><font size="2"><?php echo $error[$c]?></font></b>
		</td>
	</tr>           

	</tr>
      	</table>
      	</div>
      	</div>
      	<div style="text-align:right;padding-right:10px;" >
	      	<a href="index.php" ><?php echo gettext("GO TO LOGIN PAGE"); ?>&nbsp;<img src="<?php echo Images_Path; ?>/key_go.png"> </a>
      	</div>
	</div>
	


<?php
	if($err_type == 0) {
	?>
	</div>
	</div>
<?php 
} else {
	$smarty->display('footer.tpl');
}
?>
