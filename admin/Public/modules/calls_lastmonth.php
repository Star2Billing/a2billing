<?php
include_once ("../../lib/admin.defines.php");
include_once ("../../lib/admin.module.access.php");

$DBHandle = DbConnect();
$table = Table::getInstance('cc_call', '*');

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
$QUERY_GRAPH_CALL_ANSWERED = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')) AS this_month, count(*) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 1 GROUP BY this_month ORDER BY this_month;";
$result_graph_calls_answered = $table->SQLExec($DBHandle, $QUERY_GRAPH_CALL_ANSWERED);
$QUERY_GRAPH_CALL_INCOMPLET = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')) AS this_month, count(*) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid != 1 GROUP BY this_month ORDER BY this_month;";
$result_graph_calls_incomplet = $table->SQLExec($DBHandle, $QUERY_GRAPH_CALL_INCOMPLET);
$QUERY_GRAPH_CALL_TIMES = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')) AS this_month, sum(sessiontime) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
$result_graph_calls_times = $table->SQLExec($DBHandle, $QUERY_GRAPH_CALL_TIMES);
$QUERY_GRAPH_CALL_SELL = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')) AS this_month, sum(sessionbill) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
$result_graph_calls_sell = $table->SQLExec($DBHandle, $QUERY_GRAPH_CALL_SELL);
$QUERY_GRAPH_CALL_BUY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')) AS this_month, sum(buycost) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
$result_graph_calls_buy = $table->SQLExec($DBHandle, $QUERY_GRAPH_CALL_BUY);
?>


<input id="call_answer" type="radio" name="mode_call" value="answered">&nbsp; <?php echo gettext("ANSWERED CALL BY MONTH"); ?><br/>
<input id="call_incomplet" type="radio" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("INCOMPLET CALL BY MONTH"); ?><br/>
<input id="call_times" type="radio" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("TIMES CALLS BY MONTH"); ?><br/>
<input id="call_sell" type="radio" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("AMOUNT SELL BY MONTH"); ?><br/>
<input id="call_buy" type="radio" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("AMOUNT BUY BY MONTH"); ?><br/>
<br/>
<div id="call_graph" class="dashgraph" style="margin-left: auto;margin-right: auto;"></div>

<?php

//Creationdate data treatment
$val_answer = "[";
$max_answer = 0;
if (is_array($result_graph_calls_answered)) {
	for ($i = 0; $i < count($result_graph_calls_answered); $i++) {
		$max_answer = max($max_answer, $result_graph_calls_answered[$i][1]);
		$val_answer .= "[" . $result_graph_calls_answered[$i][0] . "000," . $result_graph_calls_answered[$i][1] . "]";
		if ($i < count($result_graph_calls_answered) - 1) {
			$val_answer .= ",";
		}
	}
}
$val_answer .= "]";
//expiration data treatment	 
$max_incomplet = 0;
$val_incomplet = "[";
if (is_array($result_graph_calls_incomplet)) {
	for ($i = 0; $i < count($result_graph_calls_incomplet); $i++) {
		$max_incomplet = max($max_incomplet, $result_graph_calls_incomplet[$i][1]);
		$val_incomplet .= "[" . $result_graph_calls_incomplet[$i][0] . "000," . $result_graph_calls_incomplet[$i][1] . "]";
		if ($i < count($result_graph_calls_incomplet) - 1) {
			$val_incomplet .= ",";
		}
	}
}
$val_incomplet .= "]";

$max_times = 0;
$val_times = "[";
if (is_array($result_graph_calls_times)) {
	for ($i = 0; $i < count($result_graph_calls_times); $i++) {
		$max_times = max($max_times, $result_graph_calls_times[$i][1]);
		$val_times .= "[" . $result_graph_calls_times[$i][0] . "000," . $result_graph_calls_times[$i][1] . "]";
		if ($i < count($result_graph_calls_times) - 1) {
			$val_times .= ",";
		}
	}
}
$val_times .= "]";

$max_sell = 0;
$val_sell = "[";
if (is_array($result_graph_calls_sell)) {
	for ($i = 0; $i < count($result_graph_calls_sell); $i++) {
		$max_sell = max($max_sell, $result_graph_calls_sell[$i][1]);
		$val_sell .= "[" . $result_graph_calls_sell[$i][0] . "000," . $result_graph_calls_sell[$i][1] . "]";
		if ($i < count($result_graph_calls_sell) - 1) {
			$val_sell .= ",";
		}
	}
}
$val_sell .= "]";

$max_buy = 0;
$val_buy = "[";
if (is_array($result_graph_calls_buy)) {
	for ($i = 0; $i < count($result_graph_calls_buy); $i++) {
		$max_buy = max($max_buy, $result_graph_calls_buy[$i][1]);
		$val_buy .= "[" . $result_graph_calls_buy[$i][0] . "000," . $result_graph_calls_buy[$i][1] . "]";
		if ($i < count($result_graph_calls_buy) - 1) {
			$val_buy .= ",";
		}
	}
}
$val_buy .= "]";
?>
<script id="source" language="javascript" type="text/javascript">
var format = "";  	
$(document).ready(function () {
var width= Math.min($("#call_graph").parent("div").width(),$("#call_graph").parent("div").innerWidth());
$("#call_graph").width(width-10);
$("#call_graph").height(Math.floor(width/2));
	$('#call_answer').click(function () {
		format ="";
		var d = <?php echo $val_answer ?>;
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
							  max:<?php echo ($max_answer+5-($max_answer%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
	
	        });
	$('#call_incomplet').click(function () {
			format ="";
			var d = <?php echo $val_incomplet ?>;
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
							  max:<?php echo ($max_incomplet+5-($max_incomplet%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
				
			
	        });
	$('#call_sell').click(function () {
			format ="money";
			var d = <?php echo $val_sell ?>;
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
							  max:<?php echo ($max_sell+5-($max_sell%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
				
			
	        });
	$('#call_buy').click(function () {
			format ="money";
			var d = <?php echo $val_buy ?>;
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
							  max:<?php echo ($max_buy+5-($max_buy%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
				
			
	        });
	$('#call_times').click(function () {
			format ="times";
			var d = <?php echo $val_times ?>;
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
							  max:<?php echo ($max_times+5-($max_times%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
				
			
	        });
	        
   $('#call_answer').click();
 
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
                    }if(format=="money"){
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

