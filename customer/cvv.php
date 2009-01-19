<?php
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");


if (! has_rights (ACX_ACCESS)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();
}

?>
<html><head>
	<link rel="shortcut icon" href="images/favicon.ico" >
	<link rel="icon" href="images/animated_favicon1.gif" type="image/gif" >
	<title>..:: CVV Number Examples ::..</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="569" id="AutoNumber1" height="223">
  <tr>
    <td><img border="0" src="<?php echo Images_Path; ?>/cvv.jpg" width="569" height="223"></td>
  </tr>
</table>

</body>

</html>
