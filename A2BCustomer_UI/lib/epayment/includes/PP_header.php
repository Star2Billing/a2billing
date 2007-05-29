<?php
	include (tep_href_link("lib/company_info.php","", 'SSL', false, false));

	define ("WEBUI_DATE", 'Release : 31 July 2006');
	define ("WEBUI_VERSION", 'Asterisk2Billing - Version 1.2.3 svn-trunk (BrainCoral)- ');
?>
<html><head>
<link rel="shortcut icon" href="<?php echo tep_href_link("images/favicon.ico","", 'SSL', false, false);?>">
<link rel="icon" href="<?php echo tep_href_link("images/animated_favicon1.gif","", 'SSL', false, false);?>" type="image/gif">

<title>..:: <?php echo CCMAINTITLE; ?> ::..</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo tep_href_link("Css/Css_Ale.css","", 'SSL', false, false);?>" rel="stylesheet" type="text/css">
<link href="<?php echo tep_href_link("Css/menu.css","", 'SSL', false, false);?>" rel="stylesheet" type="text/css">
<link href="<?php echo tep_href_link("Css/style-def.css","", 'SSL', false, false);?>" rel="stylesheet" type="text/css">

<script language="JavaScript">
<!--
var mywin
var prevdiv="dummydiv"
function imgidclick(imgID,divID)
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
			document.all(imgID).src="Css/kicons/viewmag.png";			
		}
		else
		{			
			document.all(divID).style.display="None";			
			document.all(imgID).src="Css/kicons/help.png";			
		}
	}else{
		if 	(document.getElementById(divID).style.display == "none" )
		{			
			document.getElementById(divID).style.display="";			
			document.getElementById(imgID).src="Css/kicons/viewmag.png";
		}
		else
		{			
			document.getElementById(divID).style.display="None";
			document.getElementById(imgID).src="Css/kicons/help.png";			
		}
	}

	window.event.cancelBubble=true;
}


//-->
</script>
</head>
<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p class="version" align="right"><?php echo WEBUI_VERSION.WEBUI_DATE; ?></p>
<br>
