<?php
include ("lib/defines.php");
include ("lib/module.access.php");


if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

?>
<html><head>
	<link rel="shortcut icon" href="images/favicon.ico" >
	<link rel="icon" href="images/animated_favicon1.gif" type="image/gif" >
	<title>..:: :<?php echo CCMAINTITLE; ?>: ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

	<frameset rows="*" cols="150,*" framespacing="0" frameborder="NO" border="0">
	  <frame src="PP_menu.php" name="leftFrame" noresize>
	  <frame src="userinfo.php" name="mainFrame">
	</frameset>

</html>
