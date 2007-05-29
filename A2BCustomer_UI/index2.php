<?php
include ("lib/defines.php");
include ("lib/module.access.php");
include (dirname(__FILE__)."/lib/company_info.php");


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
	<style type="text/css">
	<!--
		body {
		 scrollbar-face-color: #F8F8F8;
		 scrollbar-highlight-color: #A5A5A5;
		 scrollbar-3d-light-color: #E5E5E5;
		 scrollbar-shadow-color: #E5E5E5;
		 scrollbar-dark-shadow-color: #036;
		 SCROLLBAR-BASE-COLOR: #E5E5E5;
		 SCROLLBAR-ARROW-COLOR: #888888;
		}
	-->
	</style>
</head>



	<frameset rows="*" cols="150,*" framespacing="0" frameborder="NO" border="0">
	  <frame src="PP_menu.php" name="leftFrame" noresize>
	  <frame src="userinfo.php" name="mainFrame">
	</frameset>


</html>
