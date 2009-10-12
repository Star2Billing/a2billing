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


include_once ("../../lib/admin.defines.php");
include_once ("../../lib/admin.module.access.php");

if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$DEBUG_MODULE = FALSE;
getpost_ifset(array (
	'type','view_type'
));

$temp = date("Y-m-01");
$datetime = new DateTime($temp);
$datetime_end = new DateTime($temp);
$datetime_end->modify("+1 month");
$datetime->modify("-11 month");
$checkdate_month= $datetime->format("Y-m-d");
$datetime->modify("-15 day");
$begin_date_graphe =  $datetime->format("Y-m-d");
$end_date_graphe = $datetime_end->format("Y-m-01");
$mingraph_month = strtotime($begin_date_graphe);
$maxgraph_month = strtotime($end_date_graphe);

//day view
$temp = date("Y-m-d");
$datetime = new DateTime($temp);
$datetime_end = new DateTime($temp);
$datetime_end->modify("+1 day");
$datetime->modify("-7 day");
$checkdate_day = $datetime->format("Y-m-d");
$datetime->modify("-12 hour");
$begin_date_graphe =  $datetime->format("Y-m-d HH");
$end_date_graphe = $datetime_end->format("Y-m-d");
$mingraph_day = strtotime($begin_date_graphe);
$maxgraph_day = strtotime($end_date_graphe);


if(!empty($type)) {
    $format='';
    $DBHandle = DbConnect();
    $table = new Table('cc_logpayment','*');
    switch ($type) {
		case 'payments_count':
		    if($view_type == "month"){
			$QUERY = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) )*1000 AS this_month, count( * )  FROM cc_logpayment WHERE date >= TIMESTAMP( '$checkdate_month' ) AND date <=CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    }else{
			$QUERY = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-%d' ) )*1000 AS this_day, count( * )  FROM cc_logpayment WHERE date >= TIMESTAMP( '$checkdate_day' ) AND date <=CURRENT_TIMESTAMP GROUP BY this_day ORDER BY this_day;";
		    }
		    break;
		case 'payments_amount':
		    if($view_type == "month"){
			$QUERY = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) )*1000 AS this_month , SUM( payment )  FROM cc_logpayment WHERE date >= TIMESTAMP( '$checkdate_month' ) AND date <=CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    }else{
			$QUERY = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-%d' ) )*1000 AS this_day , SUM( payment )  FROM cc_logpayment WHERE date >= TIMESTAMP( '$checkdate_day' ) AND date <=CURRENT_TIMESTAMP GROUP BY this_day ORDER BY this_day;";
		    }
		    $format='money';
		    break;
    }

    $result_graph = $table->SQLExec($DBHandle, $QUERY);
    $max = 0;
    $data = array();
    if (is_array($result_graph)) {
	    for ($i = 0; $i < count($result_graph); $i++) {
		    $max = max($max,$result_graph[$i][1]);
		    $data[]= array($result_graph[$i][0],floatval($result_graph[$i][1]));
	    }
    }
    $response = array('max'=> floatval($max), 'data'=>$data ,'format' => $format);
    if($DEBUG_MODULE) $response['query'] = $QUERY;
    echo json_encode($response);
    die();
}
?>

<center><b><?php echo gettext("Report by"); ?></b></center><br/>
<center><?php echo gettext("Days"); ?> &nbsp;<input id="view_payment_day" type="radio" class="period_payments_graph" name="view_payment" value="day" > &nbsp;
<?php echo gettext("Months"); ?> &nbsp;<input id="view_payment_month" type="radio" class="period_payments_graph" name="view_payment" value="month"></center> <br/>
<b><?php echo gettext("Customer type :"); ?></b><br/>
<input id="payments_count" type="radio" name="mode_paym" class="update_payments_graph" value="count">&nbsp; <?php echo gettext("Total Number of Payments"); ?><br/>
<input id="payments_amount" type="radio" name="mode_paym" class="update_payments_graph" value="amount">&nbsp; <?php echo gettext("Total Payment Amount"); ?><br/>
<br/>
<div id="payments_graph" class="dashgraph" style="margin-left: auto;margin-right: auto;"></div>
	 
<script id="source" language="javascript" type="text/javascript">
$(document).ready(function () {
var format = "";
var period_val="";
var x_format = "";

var width= Math.min($("#payments_graph").parent("div").width(),$("#payments_graph").parent("div").innerWidth());
$("#payments_graph").width(width-10);
$("#payments_graph").height(Math.floor(width/2));


$('.update_payments_graph').click(function () {
    $.getJSON("modules/payments_lastmonth.php", { type: this.id, view_type : period_val },
		      function(data){
				<?php if($DEBUG_MODULE)echo "alert(data.query);alert(data.data);"?>
				var graph_max = data.max;
				var graph_data = new Array();
				for (i = 0; i < data.data.length; i++) {
				    graph_data[i] = new Array();
				    graph_data[i][0]= parseInt(data.data[i][0]);
				    graph_data[i][1]= data.data[i][1]
				 }
				format = data.format;
				plot_graph_payments(graph_data,graph_max);
			 });

   });

$('.period_payments_graph').change(function () {
    period_val = $(this).val();
    if($(this).val() == "month" ) x_format ="%b";
    else x_format ="%d-%m";
    $('.update_payments_graph:checked').click();
   });

$('#view_payment_day').click();
$('#view_payment_day').change();

function plot_graph_payments(data,max){
    var d= data;
    var max_data = (max+5-(max%5));
    var min_month = <?php echo $mingraph_month."000" ?>;
    var max_month = <?php echo $maxgraph_month."000" ?>;
    var min_day = <?php echo $mingraph_day."000" ?>;
    var max_day = <?php echo $maxgraph_day."000" ?>;
    if(period_val=="month"){
	var min_graph = min_month;
	var max_graph = max_month;
	var bar_width = 28*24 * 60 * 60 * 1000;
    }else{
	var min_graph = min_day;
	var max_graph = max_day;
	var bar_width = 24 * 60 * 60 * 1000;
    }

    $.plot($("#payments_graph"), [
				{
				    data: d,
				    bars: { show: true,
						barWidth: bar_width,
						align: "centered"
				    }
				}
				 ],
			    {   xaxis: {
				    mode: "time",
				    timeformat: x_format,
				    ticks :6,
					min : min_graph,
					max : max_graph
				  },
				  yaxis: {
				  max:max_data,
				  minTickSize: 1,
				  tickDecimals:0
				  },selection: { mode: "y" },
				 grid: { hoverable: true,clickable: true}
				  });

	}
 $('#payments_count').click();	     
 
   function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#payments_graph").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
		    if(format=="money"){
                    	 var y = item.datapoint[1].toFixed(2);
                    	 showTooltip(item.pageX, item.pageY, y+" <?php echo $A2B->config["global"]["base_currency"];?>");
                    }else{
                    	var y = item.datapoint[1].toFixed(0);
                    	showTooltip(item.pageX, item.pageY, y);
                    }
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
    });
            
});

</script>

