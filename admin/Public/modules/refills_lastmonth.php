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
$datetime->modify("-11 month");
$checkdate= $datetime->format("Y-m-d");
$datetime->modify("-15 day");
$begin_date_graphe = $datetime->format("Y-m-d");
$end_date_graphe = $datetime_end->format("Y-m-01");
$mingraph = strtotime($begin_date_graphe);
$maxgraph = strtotime($end_date_graphe);

if (!empty($type)) {
    $format='';
    $DBHandle = DbConnect();
    $table = new Table('cc_logrefill','*');
    switch ($type) {
		case 'refills_count':
		    $QUERY = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) )*1000 AS this_month , count( * )  FROM cc_logrefill WHERE date >= TIMESTAMP( '$checkdate' ) AND date <=CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    break;
		case 'refills_amount':
		    $QUERY = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) )*1000 AS this_month , SUM( credit )  FROM cc_logrefill WHERE date >= TIMESTAMP( '$checkdate' ) AND date <=CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    $format='money';
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
    echo json_encode(array('max'=> floatval($max), 'data'=>$data , 'format'=>$format));
    die();
}
?>

<input id="refills_count" type="radio" name="mode_refill" class="update_refills_graph" value="count">&nbsp; <?php echo gettext("Number of Refills"); ?><br/>
<input id="refills_amount" type="radio" name="mode_refill" class="update_refills_graph" value="amount">&nbsp; <?php echo gettext("Total Amount of Refills"); ?><br/>
<br/>
<div id="refills_graph" class="dashgraph" style="margin-left: auto;margin-right: auto;"></div>
	 
<script id="source" language="javascript" type="text/javascript">
  	
$(document).ready(function () {
var format = "";
var width= Math.min($("#refills_graph").parent("div").width(),$("#refills_graph").parent("div").innerWidth());
$("#refills_graph").width(width-10);
$("#refills_graph").height(Math.floor(width/2));


$('.update_refills_graph').click(function () {
    $.getJSON("modules/refills_lastmonth.php", { type: this.id },
		      function(data){
				var graph_max = data.max;
				var graph_data = data.data;
				format = data.format;
				plot_graph_refills(graph_data,graph_max);
			 });

   });

function plot_graph_refills(data,max){
    var d=data;
    var max_data = (max+5-(max%5));
    $.plot($("#refills_graph"), [
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
 $('#refills_count').click();
 
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
    $("#refills_graph").bind("plothover", function (event, pos, item) {
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

