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
include ("./form_data/FG_var_did.inc");
include ("../lib/admin.smarty.php");
require ("../lib/didx.php");

if (! has_rights (ACX_DID)) {
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array('country', 'area', 'nxx', 'number', 'ID', 'rating'));


function unavailable() {
	return "Sorry, the DID selection service is currently unavailable because of uplink error, please come back and try again later or contact us about available DIDs (include country and area codes to your request).";
}

function getcountry($arr, $selected=0, $id=0) {
	if($arr === false)
		return unavailable();
	$res = "<select class=\"form_input_select\" name=\"country\" onchange=\"this.form.elements['area'].value=0; this.form.elements['nxx'].value=0; this.form.elements['number'].value=0; this.form.submit();\">\n";
	$res .= "<option value=\"0\">Select a Country</option>\n";
	if($arr) {
		$i = 0;
		foreach($arr as $c) {
			if($i++ == 0)
				continue;
			$res .= "<option value=\"$c[1]/$c[2]/$c[0]\"";
			if($c[1] == $selected && $c[2] == $id)
				$res .= " selected";
			$res .= ">$c[0] ($c[1])</option>\n";
		}
	}
	$res .= "</select>";
	return $res;
}

function getarea($arr, $selected=0) {
	if($arr === false)
		return unavailable();
	$res = "<select class=\"form_input_select\" name=\"area\" onchange=\"this.form.elements['nxx'].value=0; this.form.elements['number'].value=0; this.form.submit();\">\n";
	$res .= "<option value=\"0\">Select an area code</option>\n";
	if($arr) {
		$i = 0;
		foreach($arr as $c) {
			if($i++ == 0)
				continue;
			$c[1] = iconv("ISO-8859-1", "UTF-8", $c[1]);
			$res .= "<option value=\"$c[0]\"";
			if($c[0] == $selected)
				$res .= " selected";
			$res .= ">$c[0] - $c[1]</option>\n";
		}
	}
	$res .= "</select>";
	return $res;
}

function getnxx($arr, $selected=0) {
	if($arr === false)
		return unavailable();
	$res = "<select class=\"form_input_select\" name=\"nxx\" onchange=\"this.form.elements['number'].value=0; this.form.submit();\">\n";
	$res .= "<option value=\"0\">Select rate center</option>\n";
	if($arr) {
		$i = 0;
		foreach($arr as $c) {
			if($i++ == 0)
				continue;
			$res .= "<option value=\"$c[4]\"";
			if($c[4] == $selected)
				$res .= " selected";
			$res .= ">1$c[2]$c[4]</option>\n";
		}
	}
	$res .= "</select>";
	return $res;
}

function getnumber($arr, $country, $selected=0) {
	if($arr === false)
		return unavailable();
	$res = "<select class=\"form_input_select\" name=\"number\" onchange=\"this.form.submit();\">\n";
	$res .= "<option value=\"0\">Select a number</option>\n";
	if($arr) {
		$i = 0;
		foreach($arr as $c) {
			if($i++ == 0)
				continue;
			$res .= "<option value=\"$c[0]\"";
			if($c[0] == $selected)
				$res .= " selected";
			$res .= ">$c[0] ($c[1]/$c[2])</option>\n";
		}
	}
	$res .= "</select>";
	return $res;
}

$HD_Form -> setDBHandler (DbConnect());

ini_set("precision", "16");
$didx = new didx();

if($form_action == "purchase" || $form_action == "add") {
	if($form_action == "purchase")
		$form_action = "ask-add";
	$HD_Form -> init();
	$list = $HD_Form -> perform_action($form_action);
	$smarty->display('main.tpl');

        $res = $didx->BuyDIDByNumber($did,"$did@".DIDX_RING_TO);
        if($res < 0)
                echo "Error $res while setting up the DID $did.";
	else {			
		echo "The DID $did successfully purchased on DIDX";
		// #### TOP SECTION PAGE
		$HD_Form -> create_toppage ($form_action);

		$HD_Form -> create_form ($form_action, $list, $id=null) ;
	}
} else {

	$smarty->display('main.tpl');

	echo $CC_help_list_did;
	
	if (!empty($country))
		list($country, $ID, $countryname) = sscanf($country, "%d/%d/%[^[]]");
	if (empty($area))
		$area = 0;
	if (empty($nxx))
		$nxx = 0;
	if (empty($number))
		$number = 0;
	if (empty($ID))
		$ID = 0;
	if (empty($rating))
		$rating = MIN_RATING;
	$country_arr=$area_arr=$nxx_arr=$number_arr=0;
	
	$country_arr = $didx->getDIDCountry($rating);
	if($country) {
		$area_arr = $didx->getDIDArea($country, $rating, 10, "", "", "", $ID);
	}
	if($country && $area) {
		if($country == 1)
			$nxx_arr = $didx->getAvailableRatedNXX($country, $area, $rating);
		if($country != 1 || $nxx) {
			if($nxx == 0)
				$nxx = '';
			if($area < 0)
				$area = '';
			$number_arr = $didx->getAvailableRatedDIDSbyCountryCode($country.$area.$nxx, $rating ,10,20,"","","",$ID);
		}
	}
?>
<div align="center">
<table>
<tr><td>
<FORM action="<?php echo $_SERVER['PHP_SELF']; ?> " method="get">
<?php
$res = "<select class=\"form_input_select\" name=\"rating\" onchange=\"this.form.submit();\">\n";
for($i=0; $i<=9;$i++) {
	$res .= "<option value=\"$i\"";
	if($i == $rating)
		$res .= " selected";
	$res .= ">".$i."</option>\n";
}
$res .= "</select>\n";
echo $res;
?>
Minimal <a href="http://www.didx.net/rating" target="new">DID vendor rating</a>
<br />
<?php echo getcountry($country_arr, $country, $ID);?>
<br />
<?php echo getarea($area_arr, $area);?>
<br />
<?php if($country==1) echo getnxx($nxx_arr, $nxx)."<br />\n"; else echo '<input type="hidden" name="nxx" value="0">'; ?>
<?php echo getnumber($number_arr, $country, $number);?>
<br />
</form>
</td></tr>
</table>
</div>
<?php

if($number) {
	$vr = $didx->GetCostOfDIDByNumber($number);
	$number_info = $didx->getDIDMinutesInfo($number);
	$number_info['Vendor rating'] = $vr[3];
	echo "<h3>DID $number parameters:</h3><br />";
	echo "<ul>";
	foreach($number_info as $key => $value) {
		echo "<li>$key:&nbsp;$value</li>";
	}
	echo "</ul>";
	$instance_table = new Table("cc_country");
	if($countryname == "USA")
		$countryname = "United States";
	$QUERY = "select id from cc_country where countryname like '".$countryname."%'";
	$countryinfo = $instance_table -> SQLExec ($HD_Form -> DBHandle, $QUERY);
?>
<form method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="form_action" value="purchase">
<input type="hidden" name="did" value="<?php echo $number; ?>">
<input type="hidden" name="fixrate" value="<?php echo $number_info["Monthly Price"]; ?>">
<input type="hidden" name="id_cc_country" value="<?php echo $countryinfo[0][0]; ?>">
<input type="submit" name="submit" value="Buy DID <?php echo $number; ?>" style="border: 2px outset rgb(204, 51, 0);">
</form>

<?php
	}
}

// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
