<?php 
	include (dirname(__FILE__)."/../lib/company_info.php");
?>
<html><head>
<link rel="shortcut icon" href="../Images/favicon.ico">
<link rel="icon" href="../Images/animated_favicon1.gif" type="image/gif">

<title>..:: <?php echo CCMAINTITLE; ?> ::..</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../Css/Css_Ale.css" rel="stylesheet" type="text/css">
<link href="../Css/menu.css" rel="stylesheet" type="text/css">

<script language="JavaScript">
<!--
var mywin
var prevdiv="dummydiv"
function imgidclick(imgID,divID, imgbase, imgchange)
{
	
	var agt=navigator.userAgent.toLowerCase();
    // *** BROWSER VERSION ***
    // Note: On IE5, these return 4, so use is_ie5up to detect IE5.
    var is_major = parseInt(navigator.appVersion);
    var is_minor = parseFloat(navigator.appVersion);

    // Note: Opera and WebTV spoof Navigator.  We do strict client detection.
    // If you want to allow spoofing, take out the tests for opera and webtv.
    var is_nav  = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1)
                && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1)
                && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1));
	var is_ie     = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
	
	
	if (is_ie){			
		if 	(document.all(divID).style.display == "none" )		
		{		
			document.all(divID).style.display="";			
			document.all(imgID).src="../Css/kicons/"+imgchange;			
		}
		else
		{			
			document.all(divID).style.display="None";			
			document.all(imgID).src="../Css/kicons/"+imgbase;			
		}
		window.event.cancelBubble=true;
	}else{
		if 	(document.getElementById(divID).style.display == "none" )
		{			
			document.getElementById(divID).style.display="";			
			document.getElementById(imgID).src="../Css/kicons/"+imgchange;
		}
		else
		{			
			document.getElementById(divID).style.display="None";
			document.getElementById(imgID).src="../Css/kicons/"+imgbase;			
		}
	}
}
//-->
</script>
</head>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br>
