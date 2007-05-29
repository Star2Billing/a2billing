<?php 
include ("../lib/defines.php");
include ("../lib/module.access.php");

?>
<html>
<head>
	<link rel="shortcut icon" href="../Images/favicon.ico" >
	<link rel="icon" href="../Images/animated_favicon1.gif" type="image/gif" >
	<title>..:: :<?php echo CCMAINTITLE; ?>: ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>


<?php 
if (SHOW_TOP_FRAME){
?>
<frameset rows="70,*" cols="*" framespacing="0" frameborder="NO" border="0">
	<frame src="PP_top.php" name="TopFrame" scrolling="NO">
<?php 
}
?>
	<frameset rows="*" cols="180,*" framespacing="0" frameborder="NO" border="0">
		<frame src="PP_menu.php" name="leftFrame" scrolling="NO" noresize>
		<frame src="PP_intro.php?sectiontitle=Intro" name="mainFrame">
	</frameset>
<?php 
if (SHOW_TOP_FRAME){
?>
</frameset>
<?php 
}
?>

</html>
