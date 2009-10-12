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


include_once (dirname(__FILE__) . "/../lib/admin.defines.php");
include_once (dirname(__FILE__) . "/../lib/admin.module.access.php");
include_once (dirname(__FILE__) . "/jpgraph_lib/jpgraph.php");
include_once (dirname(__FILE__) . "/jpgraph_lib/jpgraph_bar.php");

if (!has_rights(ACX_CALL_REPORT)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$FG_DEBUG = 0;

getpost_ifset(array('min_call', 'fromstatsday_sday', 'days_compare', 'fromstatsmonth_sday', 'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'dst', 'src', 'clid', 'userfieldtype', 'userfield', 'accountcodetype', 'accountcode', 'customer', 'entercustomer', 'enterprovider', 'entertrunk', 'enterratecard', 'entertariffgroup'));


$FG_TABLE_NAME="cc_call t1 LEFT OUTER JOIN cc_trunk t3 ON t1.id_trunk = t3.id_trunk";

$DBHandle  = DbConnect();

$FG_TABLE_COL = array();
$FG_TABLE_COL[]=array (gettext("Calldate"), "starttime", "15%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat");
$FG_TABLE_COL[]=array (gettext("CalledNumber"), "calledstation", "15%", "center", "SORT", "30", "", "", "", "", "", "remove_prefix");
$FG_TABLE_COL[]=array (gettext("Destination"), "destination", "10%", "center", "SORT", "15", "lie", "cc_prefix", "destination", "id='%id'", "%1");
$FG_TABLE_COL[]=array (gettext("Duration"), "sessiontime", "7%", "center", "SORT", "30", "", "", "", "", "", "display_minute");
$FG_TABLE_COL[]=array (gettext("CardUsed"), "card_id", "11%", "center", "SORT", "", "30", "", "", "", "", "linktocustomer");
$FG_TABLE_COL[]=array (gettext("terminatecauseid"), "terminatecauseid", "10%", "center", "SORT", "30");
$FG_TABLE_COL[]=array (gettext("IAX/SIP"), "sipiax", "6%", "center", "SORT",  "", "list", $yesno);
$FG_TABLE_COL[]=array (gettext("InitialRate"), "calledrate", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2dec");
$FG_TABLE_COL[]=array (gettext("Cost"), "sessionbill", "10%", "center", "SORT", "30", "", "", "", "", "", "display_2bill");



$FG_TABLE_DEFAULT_ORDER = "t1.starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";

$FG_COL_QUERY_GRAPH = 't1.starttime, t1.sessiontime';

$FG_LIMITE_DISPLAY = 100;
$FG_NB_TABLE_COL = count($FG_TABLE_COL);

$instance_table_graph = new Table($FG_TABLE_NAME, $FG_COL_QUERY_GRAPH);

if (is_null($order) || is_null($sens)) {
	$order = $FG_TABLE_DEFAULT_ORDER;
	$sens = $FG_TABLE_DEFAULT_SENS;
}

getpost_ifset(array (
	'before',
	'after'
));

$SQLcmd = '';
$SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');

if ($before) {
	if (strpos($SQLcmd, 'WHERE') > 0) {
		$SQLcmd = "$SQLcmd AND ";
	} else {
		$SQLcmd = "$SQLcmd WHERE ";
	}
	$SQLcmd = "$SQLcmd starttime <'" . $before . "'";
}
if ($after) {
	if (strpos($SQLcmd, 'WHERE') > 0) {
		$SQLcmd = "$SQLcmd AND ";
	} else {
		$SQLcmd = "$SQLcmd WHERE ";
	}
	$SQLcmd = "$SQLcmd starttime >'" . $after . "'";
}

$date_clause = '';

$min_call = intval($min_call);
if (($min_call != 0) && ($min_call != 1))
	$min_call = 0;

if (!isset ($fromstatsday_sday)) {
	$fromstatsday_sday = date("d");
	$fromstatsmonth_sday = date("Y-m");
}

if (isset ($customer) && ($customer > 0)) {
	if (strlen($SQLcmd) > 0)
		$SQLcmd .= " AND ";
	else
		$SQLcmd .= " WHERE ";
	$SQLcmd .= " card_id='$customer' ";
} else {
	if (isset ($entercustomer) && ($entercustomer > 0)) {
		if (strlen($SQLcmd) > 0)
			$SQLcmd .= " AND ";
		else
			$SQLcmd .= " WHERE ";
		$SQLcmd .= " card_id='$entercustomer' ";
	}
}
if ($_SESSION["is_admin"] == 1) {
	if (isset ($enterprovider) && $enterprovider > 0) {
		if (strlen($SQLcmd) > 0)
			$SQLcmd .= " AND ";
		else
			$SQLcmd .= " WHERE ";
		$SQLcmd .= " t3.id_provider = '$enterprovider' ";
	}
	if (isset ($entertrunk) && $entertrunk > 0) {
		if (strlen($SQLcmd) > 0)
			$SQLcmd .= " AND ";
		else
			$SQLcmd .= " WHERE ";
		$SQLcmd .= " t3.id_trunk = '$entertrunk' ";
	}
	if (isset ($entertariffgroup) && $entertariffgroup > 0) {
		if (strlen($SQLcmd) > 0)
			$SQLcmd .= " AND ";
		else
			$SQLcmd .= " WHERE ";
		$SQLcmd .= "t1.id_tariffgroup = '$entertariffgroup'";
	}
	if (isset ($enterratecard) && $enterratecard > 0) {
		if (strlen($SQLcmd) > 0)
			$SQLcmd .= " AND ";
		else
			$SQLcmd .= " WHERE ";
		$SQLcmd .= "t1.id_ratecard = '$enterratecard'";
	}
}

if (DB_TYPE == "postgres") {
	if (isset ($fromstatsday_sday) && isset ($fromstatsmonth_sday))
		$date_clause .= " AND starttime < date'$fromstatsmonth_sday-$fromstatsday_sday'+ INTERVAL '1 DAY' AND starttime >= '$fromstatsmonth_sday-$fromstatsday_sday'";
} else {
	if (isset ($fromstatsday_sday) && isset ($fromstatsmonth_sday))
		$date_clause .= " AND starttime < ADDDATE('$fromstatsmonth_sday-$fromstatsday_sday',INTERVAL 1 DAY) AND starttime >= '$fromstatsmonth_sday-$fromstatsday_sday'";
}

//-- $date_clause=" AND calldate < date'$fromstatsmonth_sday-$fromstatsday_sday'+ INTERVAL '1 DAY' AND calldate >= '$fromstatsmonth_sday-$fromstatsday_sday 12:00:00'";

if (strpos($SQLcmd, 'WHERE') > 0) {
	$FG_TABLE_CLAUSE = substr($SQLcmd, 6) . $date_clause;
}
elseif (strpos($date_clause, 'AND') > 0) {
	$FG_TABLE_CLAUSE = substr($date_clause, 5);
}

$list_total = $instance_table_graph->Get_list($DBHandle, $FG_TABLE_CLAUSE, 't1.starttime', 'ASC', null, null, null, null);

/**************************************/

$table_graph = array ();
$table_graph_hours = array ();
$numm = 0;
foreach ($list_total as $recordset) {
	$numm++;
	$mydate = substr($recordset[0], 0, 10);
	$mydate_hours = substr($recordset[0], 0, 13);
	//echo "$mydate<br>";
	if (is_array($table_graph_hours[$mydate_hours])) {
		$table_graph_hours[$mydate_hours][0]++;
		$table_graph_hours[$mydate_hours][1] = $table_graph_hours[$mydate_hours][1] + $recordset[1];
	} else {
		$table_graph_hours[$mydate_hours][0] = 1;
		$table_graph_hours[$mydate_hours][1] = $recordset[1];
	}

	if (is_array($table_graph[$mydate])) {
		$table_graph[$mydate][0]++;
		$table_graph[$mydate][1] = $table_graph[$mydate][1] + $recordset[1];
	} else {
		$table_graph[$mydate][0] = 1;
		$table_graph[$mydate][1] = $recordset[1];
	}
}

//print_r($table_graph_hours);
//exit();

$mmax = 0;
$totalcall == 0;
$totalminutes = 0;
foreach ($table_graph as $tkey => $data) {
	if ($mmax < $data[1])
		$mmax = $data[1];
	$totalcall += $data[0];
	$totalminutes += $data[1];
}

/************************************************/

$datax1 = array_keys($table_graph_hours);
$datay1 = array_values($table_graph_hours);

//$days_compare // 3
$nbday = 0; // in tableau_value and tableau_hours to select the day in which you store the data
//$min_call=0; // min_call variable : 0 > get the number of call 1 > number minutes

$table_subtitle[] = gettext("Statistic : Load by hours");
$table_subtitle[] = gettext("Statistic : Minutes by Hours");

$table_colors[] = "yellow@0.3";
$table_colors[] = "purple@0.3";
$table_colors[] = "green@0.3";
$table_colors[] = "blue@0.3";
$table_colors[] = "red@0.3";

$jour = substr($datax1[0], 8, 2); //le jour courant 
$legend[0] = substr($datax1[0], 0, 10); //l

//print_r ($table_graph_hours);
// Create the graph to compare the day
// extract all minutes/nb call for each hours 
foreach ($table_graph_hours as $key => $value) {

	$jour_suivant = substr($key, 8, 2);

	if ($jour_suivant != $jour) {
		$nbday++;
		$legend[$nbday] = substr($key, 0, 10);
		$jour = $jour_suivant;
	}

	$heure = intval(substr($key, 11, 2));

	if ($min_call == 0)
		$div = 1;
	else
		$div = 60;

	$tableau_value[$nbday][$heure] = $value[$min_call] / $div;
}

// je remplie les cases vide par des 0
for ($i = 0; $i <= $nbday; $i++)
	for ($j = 0; $j < 24; $j++)
		if (!isset ($tableau_value[$i][$j]))
			$tableau_value[$i][$j] = 0;

//Je remplace les 0 par null pour pour les heures 
$i = 23;
while ($tableau_value[$nbday][$i] == 0) {
	$tableau_value[$nbday][$i] = null;
	$i--;
}

foreach ($datay1 as $tkey => $data) {
	$dataz1[] = $data[1];
	$dataz2[] = $data[0];

}
/*$datay1 = array(2,6,7,12,13,18);
echo "<br>nb x1:".count($datax1);
echo "<br>nb z1:".count($dataz1);
print_r($datax1);
echo "<br><br>";
print_r($dataz1);
echo "<br><br>";
print_r($datay1);*/

//print_r($dataz1);
//$dataz1 = array(2,6,7,12,13,2,6,7,12,13,2,6,7,12,13,2,6,7,12,13,2,6,7,12,13);
//print_r($dataz1);
//$datax1 = array(5,12,12,19,25,20);

// Setup the graph
$graph = new Graph(750, 450);
$graph->SetMargin(40, 40, 45, 90); //droit,gauche,haut,bas
$graph->SetMarginColor('white');
//$graph->SetScale("linlin");
$graph->SetScale("textlin");
$graph->yaxis->scale->SetGrace(3);

// Hide the frame around the graph
$graph->SetFrame(false);

// Setup title
$graph->title->Set("Graphic");
//$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);

// Note: requires jpgraph 1.12p or higher
$graph->SetBackgroundGradient('#FFFFFF', '#CDDEFF:0.8', GRAD_HOR, BGRAD_PLOT);
$graph->tabtitle->Set($table_subtitle[$min_call]);
$graph->tabtitle->SetWidth(TABTITLE_WIDTHFULL);

// Enable X and Y Grid
$graph->xgrid->Show();
$graph->xgrid->SetColor('gray@0.5');
$graph->ygrid->SetColor('gray@0.5');

$graph->yaxis->HideZeroLabel();
$graph->xaxis->HideZeroLabel();
$graph->ygrid->SetFill(true, '#EFEFEF@0.5', '#CDDEFF@0.5');

//$graph->xaxis->SetTickLabels($tableau_hours[0]);

// initialisaton fixe de AXE X
$tableau_hours[0] = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
$graph->xaxis->SetTickLabels($tableau_hours[0]);

// Setup X-scale
//$graph->xaxis->SetTickLabels($tableau_hours[0]);
$graph->xaxis->SetLabelAngle(90);

// Format the legend box
$graph->legend->SetColor('navy');
$graph->legend->SetFillColor('gray@0.8');
$graph->legend->SetLineWeight(1);
//$graph->legend->SetFont(FF_ARIAL,FS_BOLD,8);
$graph->legend->SetShadow('gray@0.4', 3);
$graph->legend->SetAbsPos(15, 130, 'right', 'bottom');

for ($indgraph = 0; $indgraph <= $nbday; $indgraph++) {

	$bplot[$indgraph] = new BarPlot($tableau_value[$indgraph]);

	$bplot[$indgraph]->SetColor($table_colors[$indgraph]);
	$bplot[$indgraph]->SetWeight(2);
	$bplot[$indgraph]->SetFillColor('orange');
	$bplot[$indgraph]->SetShadow();
	$bplot[$indgraph]->value->Show();

	$bplot[$indgraph]->SetLegend($legend[$indgraph]);

	$graph->Add($bplot[$indgraph]);

}

// Output the graph
$graph->Stroke();

