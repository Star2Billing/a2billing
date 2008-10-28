<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

define( 'FULL_PATH', dirname(__FILE__) . '/' );
define( 'SMARTY_DIR', FULL_PATH . '/Smarty/' );
define( 'TEMPLATE_DIR',  './templates/' );
define( 'TEMPLATE_C_DIR', './templates_c/' );

require_once SMARTY_DIR . 'Smarty.class.php';

$smarty = new Smarty;


$skin_name = $_SESSION["stylefile"];


//$smarty->template_dir = TEMPLATE_DIR . $skin_name.'/';
$smarty->template_dir = TEMPLATE_DIR . $skin_name.'/';

$smarty->compile_dir = TEMPLATE_C_DIR;
$smarty->plugins_dir= "./plugins/";

$smarty->assign("TEXTCONTACT", TEXTCONTACT);
$smarty->assign("EMAILCONTACT", EMAILCONTACT);
$smarty->assign("COPYRIGHT", COPYRIGHT);
$smarty->assign("CCMAINTITLE", CCMAINTITLE);
$smarty->assign("WEBUI_VERSION", WEBUI_VERSION);
$smarty->assign("WEBUI_DATE", WEBUI_DATE);
$smarty->assign("SIGNUPLINK", SIGNUP_LINK);

$smarty->assign("ACXPASSWORD", $ACXPASSWORD);
$smarty->assign("ACXSIP_IAX", $ACXSIP_IAX);
$smarty->assign("ACXCALL_HISTORY", $ACXCALL_HISTORY);
$smarty->assign("ACXPAYMENT_HISTORY", $ACXPAYMENT_HISTORY);
$smarty->assign("ACXVOUCHER", $ACXVOUCHER);
$smarty->assign("ACXINVOICES", $ACXINVOICES);
$smarty->assign("ACXDID", $ACXDID);
$smarty->assign("ACXSPEED_DIAL", $ACXSPEED_DIAL);
$smarty->assign("ACXRATECARD", $ACXRATECARD);
$smarty->assign("ACXSIMULATOR", $ACXSIMULATOR);
$smarty->assign("ACXWEB_PHONE", $ACXWEB_PHONE);
$smarty->assign("ACXCALL_BACK", $ACXCALL_BACK);
$smarty->assign("ACXCALLER_ID", $ACXCALLER_ID);
$smarty->assign("ACXSUPPORT", $ACXSUPPORT);
$smarty->assign("ACXNOTIFICATION", $ACXNOTIFICATION);
$smarty->assign("ACXAUTODIALER", $ACXAUTODIALER);






if($exporttype != "" && $exporttype != "html")
{
	$smarty->assign("EXPORT", 1);
}
else
{
	$smarty->assign("EXPORT", 0);
}

if($_GET["section"]!="")
{	
	$section = $_GET["section"];
	$_SESSION["menu_section"] = $section;
}
else
{	
	$section = $_SESSION["menu_section"];
}
$smarty->assign("section", $section);

$smarty->assign("SKIN_NAME", $skin_name);
// if it is a pop window
if (!is_numeric($popup_select))
{
	$popup_select=0;
}
// for menu
$smarty->assign("popupwindow", $popup_select);


// OPTION FOR THE MENU
$smarty->assign("A2Bconfig", $A2B->config);



$smarty->assign("PAGE_SELF", $PHP_SELF);

?>
