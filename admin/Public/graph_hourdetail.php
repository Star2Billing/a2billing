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
include_once (dirname(__FILE__) . "/jpgraph_lib/jpgraph.php");
include_once (dirname(__FILE__) . "/jpgraph_lib/jpgraph_line.php");
include_once (dirname(__FILE__) . "/jpgraph_lib/jpgraph_bar.php");
include_once (dirname(__FILE__) . "/../lib/admin.module.access.php");

if (!has_rights(ACX_CALL_REPORT)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$FG_DEBUG = 0;


getpost_ifset(array('typegraph', 'min_call', 'fromstatsday_sday', 'days_compare', 'fromstatsmonth_sday', 'dsttype', 'srctype', 'clidtype', 'channel', 'resulttype', 'dst', 'src', 'clid', 'userfieldtype', 'userfield', 'accountcodetype', 'accountcode', 'hourinterval', 'customer', 'entercustomer', 'enterprovider', 'entertrunk'));


if (!($hourinterval >= 0) && ($hourinterval <= 23))
	exit ();

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME = "cc_call t1 LEFT OUTER JOIN cc_trunk t3 ON t1.id_trunk = t3.id_trunk";

$DBHandle = DbConnect();

// The variable Var_col would define the col that we want show in your table
// First Name of the column in the html page, second name of the field
$FG_TABLE_COL = array ();

$FG_TABLE_DEFAULT_ORDER = "starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";

$FG_COL_QUERY_GRAPH = 'starttime, sessiontime';

if ($FG_DEBUG == 3)
	echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY_GRAPH";

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

$SQLcmd = do_field($SQLcmd, 'dst', 'calledstation');

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
}

$date_clause = '';

$min_call = intval($min_call);
if (($min_call != 0) && ($min_call != 1))
	$min_call = 0;

if (!isset ($fromstatsday_sday)) {
	$fromstatsday_sday = date("d");
	$fromstatsmonth_sday = date("Y-m");
}

$hourintervalplus = $hourinterval +1;

if (isset ($fromstatsday_sday) && isset ($fromstatsmonth_sday))
	$date_clause .= " AND t1.starttime < '$fromstatsmonth_sday-$fromstatsday_sday " .
	$hourintervalplus . ":00:00' AND t1.starttime >= '$fromstatsmonth_sday-$fromstatsday_sday " . $hourinterval . ":00:00' ";

//-- $date_clause=" AND calldate < date'$fromstatsmonth_sday-$fromstatsday_sday'+ INTERVAL '1 DAY' AND calldate >= '$fromstatsmonth_sday-$fromstatsday_sday 12:00:00'";

if (strpos($SQLcmd, 'WHERE') > 0) {
	$FG_TABLE_CLAUSE = substr($SQLcmd, 6) . $date_clause;
}
elseif (strpos($date_clause, 'AND') > 0) {
	$FG_TABLE_CLAUSE = substr($date_clause, 5);
}

$list_total = $instance_table_graph->Get_list($DBHandle, $FG_TABLE_CLAUSE, 't1.starttime', 'ASC', null, null, null, null);

/**************************************/

$nbcall = count($list_total);

$mycall_min[0] = 0;
$mycall_dur[0] = 0;
/* 
	WE WILL BUILD DIFFERENT TABLES, 
mycall_min FOR THE STARTDATE (MIN) 
mycall_dur FOR THE DURATION OF EACH CALLS (IN SECS)
mycall_minsec_start FOR THE EXACT START OF THE CALL AND END
mycall_minsec_start[i][0] - START DATE (MINSEC)1843		18em Minutes 43 sec
mycall_minsec_start[i][1] - END DATE   (MINSEC)2210		22em Minutes 10 sec

*/
for ($i = 1; $i <= $nbcall; $i++) {
	$mycall_min[$i] = substr($list_total[$i -1][0], 14, 2);
	$mycall_minsec_start[$i][0] = substr($list_total[$i -1][0], 14, 2) . substr($list_total[$i -1][0], 17, 2);
	$mycall_dur[$i] = $list_total[$i -1][1];

	$nx_sec_report = 0;
	$nx_sec = substr($list_total[$i -1][0], 17, 2) + ($mycall_dur[$i] % 60);
	$nx_sec_report = intval($nx_sec / 60);
	$nx_sec = $nx_sec % 60;

	$nx_min = substr($list_total[$i -1][0], 14, 2) + intval($mycall_dur[$i] / 60) + $nx_sec_report;
	if ($nx_min > 59) {
		$nx_min = 59;
		$nx_sec = 59;
	}

	$mycall_minsec_start[$i][1] = sprintf("%02d", $nx_min) . sprintf("%02d", $nx_sec);

	//if ($i==10) break;
}

for ($k = 0; $k <= count($mycall_minsec_start); $k++) {

	if (is_numeric($fluctuation[$mycall_minsec_start[$k][0]])) {
		$fluctuation[$mycall_minsec_start[$k][0]]++;
	} else {
		$fluctuation[$mycall_minsec_start[$k][0]] = 1;
	}
	if (is_numeric($fluctuation[$mycall_minsec_start[$k][1]])) {
		$fluctuation[$mycall_minsec_start[$k][1]]--;
	} else {
		$fluctuation[$mycall_minsec_start[$k][1]] = -1;
	}
}

ksort($fluctuation);

$maxload = 1;
$load = 0;
while (list ($key, $val) = each($fluctuation)) {
	//echo "<br>$key => $val\n";  
	$load = $load + $val;
	if (is_numeric($key))
		$fluctuation_load[substr($key, 0, 2) . ':' . substr($key, 2, 2)] = $load;
	//echo "<br>:: ".$load;
	if ($load > $maxload) {
		$maxload = $load;

	}
}
//print_r($fluctuation_load);
//print_r(array_keys($fluctuation_load));

function recursif_count_load($ind, $table, $load) {
	$maxload = $load;
	$current_start = $table[$ind][0];
	$current_end = $table[$ind][1];

	for ($k = $ind +1; $k <= count($table); $k++) {
		if ($table[$k][0] <= $current_end) {
			$load = recursif_count_load($k, $table, $load +1);
			if ($load > $maxload)
				$maxload = $load;
		} else {
			break;
		}
	}
	if ($k < count($table))
		$load = recursif_count_load($k, $table, $load);
	if ($load > $maxload)
		$maxload = $load;
	return $maxload;
}

// Some data
for ($j = 0; $j <= 59; $j++) {
	if ($j == -1)
		$datax[] = '';
	else
		$datax[] = sprintf("%02d", $j);

}

sort($mycall_min);

$lineSetWeight = 500 / $nbcall;
for ($k = 1; $k <= $nbcall; $k++) {
	$mycall_dur[$k] = intval($mycall_dur[$k] / 60) + 1;

	for ($j = 0; $j <= 59; $j++) {
		if ($j == -1) {
			$datay[$k][] = '';
		} else {
			if ($j == $mycall_min[$k]) {
				$datay[$k][] = $k * 1;
			}
			elseif ($j > $mycall_min[$k]) { // CHECK SESSIONTIME

				if (($mycall_min[$k] + $mycall_dur[$k]) >= $j)
					$datay[$k][] = $k * 1;
				else
					$datay[$k][] = '';

			} else { // FILL WITH BLANK
				$datay[$k][] = '';
			}
		}
	}
}

$myrgb = new RGB();
foreach ($myrgb->rgb_table as $minimecolor) {
	$table_colors[] = $minimecolor;
}

/*****************************************************/
/* 		  2 GRAPH - FLUCTUATION & WATCH TRAFFIC	 	 */
/*****************************************************/

if ($typegraph == 'fluctuation') {
	// Setup the graph

	$width_graph = 750;

	if (count($fluctuation_load) > 200) {
		$multi_width = intval(count($fluctuation_load) / 90);
		$width_graph = $width_graph * $multi_width;
	}
	$graph = new Graph($width_graph, 450);
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
	$graph->tabtitle->Set("$fromstatsmonth_sday-$fromstatsday_sday Hourly Graph - FROM $hourinterval to $hourintervalplus - NBCALLS $nbcall - " . "MAX LOAD = $maxload");
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

	$graph->xaxis->SetTickLabels(array_keys($fluctuation_load));

	// Setup X-scale
	//$graph->xaxis->SetTickLabels($tableau_hours[0]);
	$graph->xaxis->SetLabelAngle(90);

	// Format the legend box
	$graph->legend->SetColor('firebrick1');

	$graph->legend->SetFillColor('gray@0.8');
	$graph->legend->SetLineWeight(2);
	$graph->legend->SetShadow('gray@0.4', 3);
	$graph->legend->SetPos(0.1, 0.15, 'left', 'left');
	$graph->legend->SetMarkAbsSize(1);
	$graph->legend->SetFont(FF_FONT1, FS_BOLD);

	$indgraph = 0;

	$bplot[$indgraph] = new BarPlot(array_values($fluctuation_load));

	//$bplot[$indgraph]->SetColor($table_colors[$indgraph]);
	$bplot[$indgraph]->SetWeight(1);
	$bplot[$indgraph]->SetFillColor('orange');
	$bplot[$indgraph]->SetShadow('black', 1, 1);

	$bplot[$indgraph]->value->Show();
	$bplot[$indgraph]->SetLegend("MAX LOAD = $maxload");

	$graph->Add($bplot[$indgraph]);
	$indgraph++;

	// Output the graph
	$graph->Stroke();

} else {

	$graph = new Graph(750, 800);
	$graph->SetMargin(60, 40, 45, 90); //droit,gauche,haut,bas
	$graph->SetMarginColor('white');
	//$graph->SetScale("linlin");
	$graph->SetScale("textlin");
	$graph->yaxis->scale->SetGrace(1, 1);

	// Hide the frame around the graph
	$graph->SetFrame(false);

	// Setup title
	$graph->title->Set("Graphic");
	//$graph->title->SetFont(FF_VERDANA,FS_BOLD,14);

	// Note: requires jpgraph 1.12p or higher
	$graph->SetBackgroundGradient('#FFFFFF', '#CDDEFF:0.8', GRAD_HOR, BGRAD_PLOT);
	$graph->tabtitle->Set("$fromstatsmonth_sday-$fromstatsday_sday Hourly Graph - FROM $hourinterval to $hourintervalplus - NBCALLS $nbcall - " . "MAX LOAD = $maxload");
	$graph->tabtitle->SetWidth(TABTITLE_WIDTHFULL);

	//$graph->yaxis->Hide();
	// Enable X and Y Grid
	//$graph->xgrid->Show();
	$graph->xgrid->SetColor('gray@0.5');
	$graph->ygrid->SetColor('gray@0.5');

	//$graph->yaxis->HideZeroLabel();
	$graph->xaxis->HideZeroLabel();
	$graph->ygrid->SetFill(true, '#EFEFEF@0.5', '#CDDEFF@0.5');

	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetLabelAngle(90);

	$graph->yaxis->HideFirstLastLabel();
	//$graph->yaxis->HideLine();
	$graph->yaxis->HideTicks();
	$graph->yaxis->SetLabelFormatString('%1d call');
	$graph->yaxis->SetTextLabelInterval(2);

	// Format the legend box
	$graph->legend->SetColor('firebrick1');

	$graph->legend->SetFillColor('gray@0.8');
	$graph->legend->SetLineWeight(2);
	$graph->legend->SetShadow('gray@0.4', 3);
	$graph->legend->SetPos(0.2, 0.2, 'left', 'center');
	$graph->legend->SetMarkAbsSize(1);
	$graph->legend->SetFont(FF_FONT1, FS_BOLD);

	for ($i = 1; $i <= count($datay); $i++) {

		// Create the first line
		$p1[$i] = new LinePlot($datay[$i]);
		$p1[$i]->SetColor($table_colors[($i +20) % 436]);
		$p1[$i]->SetCenter();
		$p1[$i]->SetWeight($lineSetWeight);
		if ($i == 1)
			$p1[$i]->SetLegend("MAX LOAD = $maxload");
		$graph->Add($p1[$i]);

	}

	// Output line
	$graph->Stroke();

} //END IF (typegraph)


