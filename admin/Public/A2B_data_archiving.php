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


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");

if (! has_rights (ACX_MAINTENANCE)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

check_demo_mode();

$HD_Form = new FormHandler("cc_card","Customer");
$HD_Form -> setDBHandler (DbConnect());
$HD_Form -> init();

$HD_Form -> FG_DEBUG = 0;
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";
$HD_Form -> FG_FILTER_SEARCH_SESSION_NAME = 'entity_archiving_selection';
$language_list = array();
$language_list["0"] = array( gettext("ENGLISH"), "en");
$language_list["1"] = array( gettext("SPANISH"), "es");
$language_list["2"] = array( gettext("FRENCH"),  "fr");

$language_list_r = array();
$language_list_r["0"] = array("en", gettext("ENGLISH"));
$language_list_r["1"] = array("es", gettext("SPANISH"));
$language_list_r["2"] = array("fr", gettext("FRENCH"));


$simultaccess_list = array();
$simultaccess_list["0"] = array( gettext("INDIVIDUAL ACCESS"), "0");
$simultaccess_list["1"] = array( gettext("SIMULTANEOUS ACCESS"), "1");

$simultaccess_list_r = array();
$simultaccess_list_r["0"] = array( "0", gettext("INDIVIDUAL ACCESS"));
$simultaccess_list_r["1"] = array( "1", gettext("SIMULTANEOUS ACCESS"));


$currency_list = array();
$currency_list_r = array();
$indcur=0;

$currencies_list = get_currencies();
foreach($currencies_list as $key => $cur_value) {
	$currency_list[$key]  = array( $cur_value[1].' ('.$cur_value[2].')', $key);
	$currency_list_r[$key]  = array( $key, $cur_value[1]);
	$currency_list_key[$key][0] = $key;
}


$cardstatus_list = array();
$cardstatus_list["0"]  = array( gettext("CANCELLED"), "0");
$cardstatus_list["1"]  = array( gettext("ACTIVE"), "1");
$cardstatus_list["2"]  = array( gettext("NEW"), "2");
$cardstatus_list["3"]  = array( gettext("WAITING-MAILCONFIRMATION"), "3");
$cardstatus_list["4"]  = array( gettext("RESERVED"), "4");
$cardstatus_list["5"]  = array( gettext("EXPIRED"), "5");

$cardstatus_list_r = array();
$cardstatus_list_r["0"]  = array("0", gettext("CANCELLED"));
$cardstatus_list_r["1"]  = array("1", gettext("ACTIVE"));
$cardstatus_list_r["2"]  = array("2", gettext("NEW"));
$cardstatus_list_r["3"]  = array("3", gettext("WAITING-MAILCONFIRMATION"));
$cardstatus_list_r["4"]  = array("4", gettext("RESERVED"));
$cardstatus_list_r["5"]  = array("5", gettext("EXPIRED"));


$cardstatus_list_acronym = array();
$cardstatus_list_acronym["0"]  = array( gettext("<acronym title=\"CANCELLED\">".gettext("CANCEL")."</acronym>"), "0");
$cardstatus_list_acronym["1"]  = array( gettext("<acronym title=\"ACTIVE\">".gettext("ACTIV")."</acronym>"), "1");
$cardstatus_list_acronym["2"]  = array( gettext("<acronym title=\"NEW\">".gettext("NEW")."</acronym>"), "2");
$cardstatus_list_acronym["3"]  = array( gettext("<acronym title=\"WAITING-MAILCONFIRMATION\">".gettext("WAIT")."</acronym>"), "3");
$cardstatus_list_acronym["4"]  = array( gettext("<acronym title=\"RESERVED\">".gettext("RESERV")."</acronym>"), "4");
$cardstatus_list_acronym["5"]  = array( gettext("<acronym title=\"EXPIRED\">".gettext("EXPIR")."</acronym>"), "5");


$typepaid_list = array();
$typepaid_list["0"]  = array( gettext("PREPAID CARD"), "0");
$typepaid_list["1"]  = array( gettext("POSTPAY CARD"), "1");

$expire_list = array();
$expire_list["0"]  = array( gettext("NO EXPIRY"), "0");
$expire_list["1"]  = array( gettext("EXPIRE DATE"), "1");
$expire_list["2"]  = array( gettext("EXPIRE DAYS SINCE FIRST USE"), "2");
$expire_list["3"]  = array( gettext("EXPIRE DAYS SINCE CREATION"), "3");


$actived_list = array();
$actived_list["t"] = array( gettext("On"), "t");
$actived_list["f"] = array( gettext("Off"), "f");

$yesno = array();
$yesno["1"] = array( gettext("Yes"), "1");
$yesno["0"] = array( gettext("No"), "0");

$invoiceday_list = array();
for ($k=0;$k<=28;$k++)
	$invoiceday_list["$k"]  = array( "$k", "$k");

$HD_Form -> CV_DISPLAY_FILTER_ABOVE_TABLE = FALSE;
$HD_Form -> CV_DISPLAY_LINE_TITLE_ABOVE_TABLE = false;
$HD_Form -> CV_DO_ARCHIVE_ALL = true;
$HD_Form -> AddViewElement(gettext("ID"), "id", "3%", "center", "sort");
$HD_Form -> AddViewElement(gettext("ACCOUNT NUMBER"), "username", "20%", "center", "sort", "", "30", "", "", "", "", "linktocustomer");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("BALANCE")."\">".gettext("BA")."</acronym>", "credit", "5%", "center", "sort", "", "", "", "", "", "", "display_2dec");
$HD_Form -> AddViewElement(gettext("LASTNAME"), "lastname", "10%", "center", "sort", "15");
$HD_Form -> AddViewElement(gettext("STATUS"), "status", "8%", "center", "sort", "", "list", $cardstatus_list_acronym);
$HD_Form -> AddViewElement(gettext("LG"), "language", "10%", "center", "sort");
$HD_Form -> AddViewElement(gettext("USE"), "inuse", "8%", "center", "sort");
$HD_Form -> AddViewElement("<acronym title=\"".gettext("CURRENCY")."\">".gettext("CUR")."</acronym>", "currency", "8%", "center", "sort", "", "list", $currency_list_key);
$HD_Form -> AddViewElement(gettext("SIP"), "sip_buddy", "10%", "center", "sort", "", "list", $yesno);
$HD_Form -> AddViewElement(gettext("IAX"), "iax_buddy", "10%", "center", "sort", "", "list", $yesno);
$HD_Form -> AddViewElement("<acronym title=\"AMOUNT OF CALL DONE\">".gettext("ACD")."</acronym>", "nbused", "10%", "center", "sort");
$FG_COL_QUERY='id, username, credit, lastname, status, language, inuse, currency, sip_buddy, iax_buddy, nbused';

$HD_Form -> FieldViewElement ($FG_COL_QUERY);


$HD_Form -> CV_NO_FIELDS  = gettext("NO CUSTOMER SEARCHED!");
$HD_Form -> FG_LIMITE_DISPLAY = 30;

$HD_Form -> FG_FILTER_SEARCH_FORM = true;
$HD_Form -> FG_FILTER_SEARCH_TOP_TEXT = gettext('Define specific criteria to search for cards created.');
$HD_Form -> FG_FILTER_SEARCH_1_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT = gettext('Creation date');


$HD_Form -> FG_FILTER_SEARCH_1_TIME_BIS = true;
$HD_Form -> FG_FILTER_SEARCH_1_TIME_TEXT_BIS = gettext('FIRST USE DATE');
$HD_Form -> FG_FILTER_SEARCH_1_TIME_FIELD_BIS = 'firstusedate';

$HD_Form -> FG_FILTER_SEARCH_3_TIME = true;
$HD_Form -> FG_FILTER_SEARCH_3_TIME_TEXT = gettext('Select customer created more than');
$HD_Form -> FG_FILTER_SEARCH_3_TIME_FIELD = 'creationdate';

//Select card older than : 3 Months, 4 Months, 5.... 12 Months
$HD_Form -> AddSearchElement_C1(gettext("ACCOUNT NUMBER"), 'username','usernametype');
$HD_Form -> AddSearchElement_C1(gettext("LASTNAME"),'lastname','lastnametype');
$HD_Form -> AddSearchElement_C1(gettext("LOGIN"),'useralias','useraliastype');
$HD_Form -> AddSearchElement_C1(gettext("MACADDRESS"),'mac_addr','macaddresstype');
$HD_Form -> AddSearchElement_C1(gettext("EMAIL"),'email','emailtype');
$HD_Form -> AddSearchElement_C2(gettext("CUSTOMER ID (SERIAL)"),'id1','id1type','id2','id2type','id');
$HD_Form -> AddSearchElement_C2(gettext("CREDIT"),'credit1','credit1type','credit2','credit2type','credit');
$HD_Form -> AddSearchElement_C2(gettext("INUSE"),'inuse1','inuse1type','inuse2','inuse2type','inuse');

$HD_Form -> FG_FILTER_SEARCH_FORM_SELECT_TEXT = '';
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT TARIFF"), "cc_tariffgroup", "id, tariffgroupname, id", "", "tariffgroupname", "ASC", "tariff");
$HD_Form -> AddSearchElement_Select(gettext("SELECT STATUS"), null, null, null, null,null , "status", 0, $cardstatus_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT ACCESS"), null, null, null, null, null, "simultaccess", 0, $simultaccess_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT GROUP"), "cc_card_group", "id, name", "", "name", "ASC", "id_group");
$HD_Form -> AddSearchElement_Select(gettext("SELECT CURRENCY"), null, null, null, null, null, "currency", 0, $currency_list_r);
$HD_Form -> AddSearchElement_Select(gettext("SELECT LANGUAGE"), null, null, null, null, null, "language", 0, $language_list_r);

$HD_Form -> prepare_list_subselection('list');
$HD_Form -> FG_TABLE_ID="id";
$HD_Form -> FG_TABLE_DEFAULT_SENS = "ASC";

$nb_customer = 0;

/***********************************************************************************/
getpost_ifset(array('archive', 'id'));

if(isset($archive) && !empty($archive)){
	$condition = $HD_Form -> FG_TABLE_CLAUSE;
    if (strlen($condition) && strpos($condition,'WHERE') === false){
        $condition = " WHERE $condition";
    }
    echo "condition : $condition";
    $rec = archive_data($condition, "card");
    if($rec > 0)
        $archive_message = "The data has been successfully archived";
}

if (!isset($form_action))  $form_action="list"; //ask-add
if (!isset($action)) $action = $form_action;

$list = $HD_Form -> perform_action($form_action);

// #### HEADER SECTION
$smarty->display('main.tpl');
echo $CC_help_data_archive;

if(!isset($submit)){?>
<script language="JavaScript" src="javascript/card.js"></script>
<div class="toggle_hide2show">
<center><a href="#" target="_self" class="toggle_menu"><img class="toggle_hide2show" src="<?php echo KICON_PATH; ?>/toggle_hide2show.png" onmouseover="this.style.cursor='hand';" HEIGHT="16"> <font class="fontstyle_002"><?php echo gettext("SEARCH CUSTOMERS");?> </font></a><?php if(!empty($_SESSION['entity_archiving_selection'])){ ?>&nbsp;(<font style="color:#EE6564;" > <?php echo gettext("search activated"); ?> </font> ) <?php } ?></center>
	<div class="tohide" style="display:none;">

<?php
$HD_Form -> create_search_form();
?>

	</div>
</div>

<?php }

?>
<center>
<FORM name="frm_archive" id="frm_archive" method="post" action="A2B_call_archiving.php">
<table class="bar-status" width="50%" border="0" cellspacing="1" cellpadding="2" align="center">
			<tbody>			
			<tr>
				<td width="30%" align="left" valign="top" class="bgcolor_004">					
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("ARCHIVING OPTIONS");?></font>
				</td>				
				<td width="70%" align="CENTER" class="bgcolor_005">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"><tr>
				  <td class="fontstyle_searchoptions">
				<select name="archiveselect" class="form_input_select" onchange="form.submit();">
				<option value="" ><?php echo gettext("Customer Archiving");?></option>
				<option value="" ><?php echo gettext("Calls Archiving");?></option>
				</select>
					</td>					
				</tr></table></td>
			</tr>			
		</tbody></table>
</FORM>
</center>

<?php 	

if(isset($archive) && !empty($archive)){
	$HD_Form -> CV_NO_FIELDS = "";
	print "<div align=\"center\">".$archive_message."</div>";
}	
$HD_Form -> create_form ($form_action, $list, $id=null);


$smarty->display('footer.tpl');

