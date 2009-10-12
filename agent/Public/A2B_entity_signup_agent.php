<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/


include ("../lib/agent.defines.php");
include ("../lib/agent.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_signup_agent.inc");
include ("../lib/agent.smarty.php");

if (! has_rights (ACX_SIGNUP)){ 
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

