<?php
include ("lib/customer.defines.php");
session_destroy();
include ("lib/customer.smarty.php");

$smarty->display('header.tpl');

getpost_ifset(array('c'));

if (!isset($c))	$c="0";

$error["0"] = gettext("ERROR : ACCESS REFUSED");
$error["syst"] = gettext("Sorry a problem occur on our system, please try later!");
$error["errorpage"] = gettext("There is an error on this page!");
$error["accessdenied"] = gettext("Sorry, you don t have access to this page !");
$error["construction"] = gettext("Sorry, this page is in construction !");

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
			<img src="<?php echo KICON_PATH;?>/system-config-rootpassword.png"> 
		</td>
		<td align="center">
			<b><font size="é"><?php echo $error[$c]?></font></b>
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
	


	</div>
	</div>

