<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_signup_agent.inc");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_ADMINISTRATOR)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}

?>
<style type="text/css">
	 #sexyBG { display: none; position: absolute; background: #000; opacity: 0.5; -moz-opacity: 0.5; -khtml-opacity: 0.5; filter: alpha(opacity=40); width: 100%; height: 100%; top: 0; left: 0; z-index: 99; }
	#sexyBOX { padding-top:15px;display: none; position: absolute; background: #FCFCFC; color: #333; font-size: 14px; text-align: center; border: 1px solid #111; top: 200px; z-index: 100; }
	.sexyX { margin-top:15px; font-size: 12px; color: #636D61; padding: 4px 0; border-top: 1px solid #636D61; background: #BDE5F8; }
</style>
<script type="text/javascript">
var msg = '<?php echo gettext('click outside box to close'); ?>';
function getId(v) { return(document.getElementById(v)); }
function style(v) { return(getId(v).style); }
function agent(v) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0)); }
function isset(v) { return((typeof(v)=='undefined' || v.length==0)?false:true); }
function XYwin(v) { var z=Array($('#page-wrap').innerHeight()-2,$('#page-wrap').width()); return(isset(v)?z[v]:z); }

function sexyTOG() { document.onclick=function(){ style('sexyBG').display='none'; style('sexyBOX').display='none'; document.onclick=function(){}; }; }
function sexyBOX(v,b) { setTimeout("sexyTOG()",100); style('sexyBG').height=XYwin(0)+'px'; style('sexyBG').display='block'; getId('sexyBOX').innerHTML=v+'<div class="sexyX">('+msg+')'+"<\/div>"; style('sexyBOX').left=Math.round((XYwin(1)-b)/2)+'px'; style('sexyBOX').width=b+'px'; style('sexyBOX').display='block'; }
</script>
<?php
$HD_Form -> setDBHandler (DbConnect());


$HD_Form -> init();


if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl'); ?>
<div id="sexyBG"></div>
<div id="sexyBOX" onmousedown="document.onclick=function(){};" onmouseup="setTimeout('sexyTOG()',1);"></div>
<?php 

// #### HELP SECTION
echo $CC_help_signup_agent;

// #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### FOOTER SECTION
$smarty->display('footer.tpl');

