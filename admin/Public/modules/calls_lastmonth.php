<?php
include_once ("../../lib/admin.defines.php");
include_once ("../../lib/admin.module.access.php");

if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

getpost_ifset(array (
	'type'
));

$temp = date("Y-m-01");
$datetime = new DateTime($temp);
$datetime_end = new DateTime($temp);
$datetime_end->modify("+1 month");
$datetime->modify("-5 month");
$checkdate = $datetime->format("Y-m-d");
$datetime->modify("-15 day");
$begin_date_graphe = $checkdate = $datetime->format("Y-m-d");
$end_date_graphe = $datetime_end->format("Y-m-01");
$mingraph = strtotime($begin_date_graphe);
$maxgraph = strtotime($end_date_graphe);
if(!empty($type)){
    $DBHandle = DbConnect();
    $table = new Table('cc_call', '*');
    $format = "";
    switch ($type) {
	case 'call_answer':
	    $QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01'))*1000 AS this_month, count(*) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 1 GROUP BY this_month ORDER BY this_month;";
	    break;
	case 'call_incomplet':
	    $QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01'))*1000 AS this_month, count(*) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid != 1 GROUP BY this_month ORDER BY this_month;";
	    break;
	case 'call_times':
	    $format = 'time';
	    $QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01'))*1000 AS this_month, sum(sessiontime) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
	    break;
	case 'call_sell':
	    $format = 'time';
	    $QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01'))*1000 AS this_month, sum(sessionbill) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
	    break;
	case 'call_buy':
	    $format = 'money';
	    $QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01'))*1000 AS this_month, sum(buycost) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
	    break;
	case 'call_profit':
	    $format = 'money';
	    $QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01'))*1000 AS this_month, sum(sessionbill)-sum(buycost) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
	    break;
    }
    
    $result_graph = $table->SQLExec($DBHandle, $QUERY);
    $max = 0;
    $data = array();
    if (is_array($result_graph)) {
	    for ($i = 0; $i < count($result_graph); $i++) {
		    $max = max($max,$result_graph[$i][1]);
		    $data[]= array((int)$result_graph[$i][0],floatval($result_graph[$i][1]));
	    }
    }
    echo json_encode(array('max'=> floatval($max), 'data'=>$data ,'format' => $format));
    die();
}


?>


<input id="call_answer" type="radio" class="update_calls_graph" name="mode_call" value="answered">&nbsp; <?php echo gettext("ANSWERED CALL BY MONTH"); ?><br/>
<input id="call_incomplet" type="radio" class="update_calls_graph" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("INCOMPLET CALL BY MONTH"); ?><br/>
<input id="call_times" type="radio" class="update_calls_graph" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("TIMES CALLS BY MONTH"); ?><br/>
<input id="call_sell" type="radio" class="update_calls_graph" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("AMOUNT SELL BY MONTH"); ?><br/>
<input id="call_buy" type="radio" class="update_calls_graph" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("AMOUNT BUY BY MONTH"); ?><br/>
<input id="call_profit" type="radio"  class="update_calls_graph" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("AMOUNT PROFIT BY MONTH"); ?><br/>
<br/>
<div id="call_graph" class="dashgraph" style="margin-left: auto;margin-right: auto;"></div>
<script id="source" language="javascript" type="text/javascript">

$(document).ready(function () {
var format = "";
var width= Math.min($("#call_graph").parent("div").width(),$("#call_graph").parent("div").innerWidth());
$("#call_graph").width(width-10);
$("#call_graph").height(Math.floor(width/2));


    $('.update_calls_graph').click(function () {
	$.getJSON("modules/calls_lastmonth.php", { type: this.id },
			  function(data){
				    var graph_max = data.max;
				    var graph_data = data.data;
				    format = data.format;
				    plot_graph_calls(graph_data,graph_max);
			     });

       });

function plot_graph_calls(data,max){
    var d= data;
    var max_data = (max+5-(max%5));
    $.plot($("#call_graph"), [
				{
				    data: d,
				    bars: { show: true,
						barWidth: <?php echo 28*24 * 60 * 60 * 1000; ?>,
						align: "centered"
				    }
				}
				 ],
			    {   xaxis: {
				    mode: "time",
					timeformat: "%b",
				    ticks :6,
					min : <?php echo $mingraph."000" ?>,
					max : <?php echo $maxgraph."000" ?>
				  },
				  yaxis: {
				  max:max_data,
				  minTickSize: 1,
				  tickDecimals:0
				  },selection: { mode: "y" },
				 grid: { hoverable: true,clickable: true}
				  });

	}


   $('#call_profit').click();
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
    $("#call_graph").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                   
                    if (format=="time"){
                    	var y = item.datapoint[1].toFixed(0);
                    	var hour= Math.floor(y/3600);
                    	var min= Math.floor(y/60)%60;
                    	var sec= y%60;
                    	showTooltip(item.pageX, item.pageY, hour+"h "+min+"m "+sec+"s<br/>("+y+" sec)");
                    }else if(format=="money"){
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

