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


include ("./lib/customer.defines.php");
include ("./lib/customer.module.access.php");
include ("./lib/Form/Class.FormHandler.inc.php");
include ("./form_data/FG_var_ratecard.inc");
include ("./lib/customer.smarty.php");


if (! has_rights (ACX_RATECARD)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('letter', 'posted_search'));

$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

if (strlen($letter)==1) $HD_Form -> FG_TABLE_CLAUSE .= " AND (SUBSTRING(destination,1,1)='".strtolower($letter)."' OR SUBSTRING(destination,1,1)='".$letter."')"; // sort by first letter

$FG_LIMITE_DISPLAY=10;
if (isset($mydisplaylimit) && (is_numeric($mydisplaylimit) || ($mydisplaylimit=='ALL'))) {
	if ($mydisplaylimit=='ALL') {
		$FG_LIMITE_DISPLAY=5000;
	} else {
		$FG_LIMITE_DISPLAY=$mydisplaylimit;
	}
}

if ($id!="" || !is_null($id)) {
	$HD_Form -> FG_EDITION_CLAUSE = str_replace("%id", "$id", $HD_Form -> FG_EDITION_CLAUSE);
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;


if ( ($form_action == "list") &&  ($HD_Form->FG_FILTER_SEARCH_FORM) && ($posted_search == 1 ) && isset($mytariff_id) ) {
	$HD_Form->FG_TABLE_CLAUSE = "idtariffplan='$mytariff_id'";
}

$list = $HD_Form -> perform_action($form_action);


// #### HEADER SECTION
$smarty->display('main.tpl');


// #### HELP SECTION
if ($form_action == 'list') {
    echo $CC_help_ratecard.'';
}

 // #### TOP SECTION PAGE
$HD_Form -> create_toppage ($form_action);

?>
<br/>
<center>
    <table width="80%" border=0 cellspacing=1 cellpadding=3 bgcolor="#000033" align="center">
        <tr>
       <td bgcolor="#EEEEEE" width="100%" valign="top" align="center" class="bb2">
	   		  <a href="A2B_entity_ratecard.php?form_action=list&letter="><?php echo gettext("NONE")?></a> -
              <a href="A2B_entity_ratecard.php?form_action=list&letter=A">A</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=B">B</a> -          
              <a href="A2B_entity_ratecard.php?form_action=list&letter=C">C</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=D">D</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=E">E</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=F">F</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=G">G</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=H">H</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=I">I</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=J">J</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=K">K</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=L">L</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=M">M</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=N">N</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=O">O</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=P">P</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=Q">Q</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=R">R</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=S">S</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=T">T</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=U">U</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=V">V</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=W">W</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=X">X</a> -       
              <a href="A2B_entity_ratecard.php?form_action=list&letter=Y">Y</a> - 
              <a href="A2B_entity_ratecard.php?form_action=list&letter=Z">Z</a>      
       </td>
        </tr>
    </table>
</center>
<?php   


$HD_Form -> create_form ($form_action, $list, $id=null) ;

// #### CREATE SEARCH FORM
if ($form_action == "list") {
	$HD_Form -> create_search_form();
}


// #### FOOTER SECTION
$smarty->display('footer.tpl');

