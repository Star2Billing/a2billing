<html>
<head><title>Asterisk2Billing :: JIAXClient applet</title></head>
<body>

<!--"CONVERTED_APPLET"-->
<!-- HTML CONVERTER -->
<OBJECT 
    classid = "clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
    codebase = "http://java.sun.com/products/plugin/autodl/jinstall-1_4-windows-i586.cab#Version=1,4,0,0"
    WIDTH = 270 HEIGHT = 240 >
    
    <PARAM NAME = "type" VALUE = "application/x-java-applet;version=1.4">
    <PARAM NAME = "scriptable" VALUE = "false">
    <PARAM NAME = "CODE" VALUE="IAXTest.class">
    <PARAM NAME = ARCHIVE VALUE="jiaxtest.jar" >
    <PARAM NAME = INPUT VALUE ="0">
    <PARAM NAME = OUTPUT VALUE ="0">
	<PARAM NAME="NUMBER" VALUE="">
	<PARAM NAME="NUMBER" VALUE="<?php echo $_POST['webphone_number'];?>">
	<PARAM NAME="USER" VALUE="<?php echo $_POST['webphone_user'];?>">
	<PARAM NAME="PASS" VALUE="<?php echo $_POST['webphone_secret'];?>">
	<PARAM NAME="HOST" VALUE="<?php echo $_POST['webphone_server'];?>">
	<PARAM NAME="REGISTER" VALUE="true">

    <COMMENT>
	<EMBED 
            type = "application/x-java-applet;version=1.4" \
            WIDTH = 270 \
            HEIGHT = 240 \
            CODE ="IAXTest.class" \
            ARCHIVE ="jiaxtest.jar" \
            INPUT ="0" \
            OUTPUT ="0" \
			NUMBER ="<?php echo $_POST['webphone_number'];?>" \
			USER ="<?php echo $_POST['webphone_user'];?>" \
			PASS ="<?php echo $_POST['webphone_secret'];?>" \
			HOST ="<?php echo $_POST['webphone_server'];?>" \
			REGISTER = true \		
	    scriptable = false \
	    pluginspage = "http://java.sun.com/products/plugin/index.html#download">
	    <NOEMBED>
            
            </NOEMBED>
	</EMBED>
    </COMMENT>
</OBJECT>

<!--
<APPLET WIDTH = 270 HEIGHT = 240>
<PARAM NAME = "CODE" VALUE="IAXTest.class">
<PARAM NAME = ARCHIVE VALUE="jiaxtest.jar" >
<PARAM NAME = INPUT VALUE ="0">
<PARAM NAME = OUTPUT VALUE ="0">


</APPLET>
-->


<!--"END_CONVERTED_APPLET"-->


<hr />
</body>
</html>
