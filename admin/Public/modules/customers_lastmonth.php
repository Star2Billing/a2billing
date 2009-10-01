<?php
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
$checkdate_month = $datetime->format("Y-m-d");
$datetime->modify("-15 day");
$begin_date_graphe = $datetime->format("Y-m-d");
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
    $DBHandle = DbConnect();
    $table = new Table('cc_card', '*');
	switch ($type) {
		case 'card_creation':
		    if($view_type == "month"){
			$QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(creationdate,'%Y-%m-01'))*1000 AS this_month, count(*) FROM cc_card WHERE creationdate>= TIMESTAMP('$checkdate_month') AND creationdate <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    }else{
			$QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(creationdate,'%Y-%m-%d'))*1000 AS this_day, count(*) FROM cc_card WHERE creationdate>= TIMESTAMP('$checkdate_day') AND creationdate <= CURRENT_TIMESTAMP GROUP BY this_day ORDER BY this_day;";
		    }
		    break;
		case 'card_expiration':
		    if($view_type == "month"){
			$QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(expirationdate,'%Y-%m-01'))*1000 AS this_month, count(*) FROM cc_card WHERE expirationdate>= TIMESTAMP('$checkdate_month') AND expirationdate <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    }else{
			$QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(expirationdate,'%Y-%m-%d'))*1000 AS this_day, count(*) FROM cc_card WHERE expirationdate>= TIMESTAMP('$checkdate_day') AND expirationdate <= CURRENT_TIMESTAMP GROUP BY this_day ORDER BY this_day;";
		    }
		    break;
		case 'card_firstuse':
		    if($view_type == "month"){
			$QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(firstusedate,'%Y-%m-01'))*1000 AS this_month, count(*) FROM cc_card WHERE firstusedate>= TIMESTAMP('$checkdate') AND firstusedate <= CURRENT_TIMESTAMP GROUP BY this_month ORDER BY this_month;";
		    }else{
			$QUERY = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(firstusedate,'%Y-%m-%d'))*1000 AS this_day, count(*) FROM cc_card WHERE firstusedate>= TIMESTAMP('$checkdate_day') AND firstusedate <= CURRENT_TIMESTAMP GROUP BY this_day ORDER BY this_day;";
		    }
		    break;
    }
	
    $result_graph = $table->SQLExec($DBHandle, $QUERY);
    $max = 0;
    $data = array();
    if (is_array($result_graph)) {
	    for ($i = 0; $i < count($result_graph); $i++) {
		    $max = max($max,$result_graph[$i][1]);
		    $data[]= array($result_graph[$i][0],(int)$result_graph[$i][1]);
	    }
    }
    $response = array('max'=> floatval($max), 'data'=>$data );
    if($DEBUG_MODULE) $response['query'] = $QUERY;
    echo json_encode($response);
    die();
}
?>
<center><b><?php echo gettext("Report by"); ?></b></center><br/>
<center><?php echo gettext("Days"); ?> &nbsp;<input id="view_customer_day" type="radio" class="period_customers_graph" name="view_cust" value="day" > &nbsp;
<?php echo gettext("Months"); ?> &nbsp;<input id="view_customer_month" type="radio" class="period_customers_graph" name="view_cust" value="month"></center> <br/>
<b><?php echo gettext("Customer type :"); ?></b><br/>
<input id="card_creation" type="radio" class="update_customers_graph" name="mode_cust" value="CreationDate">&nbsp; <?php echo gettext("Customer Creation Date"); ?><br/>
<input id="card_expiration" type="radio" class="update_customers_graph" name="mode_cust" value="ExpirationDate">&nbsp; <?php echo gettext("Customer Expiry Date"); ?><br/>
<input id="card_firstuse" type="radio" class="update_customers_graph" name="mode_cust" value="FirstUse">&nbsp; <?php echo gettext("Customer First Used"); ?><br/>
<br/>
<div id="cust_graph" class="dashgraph" style="margin-left: auto;margin-right: auto;" ></div>
<script id="source" language="javascript" type="text/javascript">
$(document).ready(function () {
var width= Math.min($("#cust_graph").parent("div").width(),$("#cust_graph").parent("div").innerWidth());
$("#cust_graph").width(width-10);
$("#cust_graph").height(Math.floor(width/2));
var period_val="";
var x_format = "";
$('.update_customers_graph').click(function () {
	$.getJSON("modules/customers_lastmonth.php", { type: this.id,view_type : period_val  },
		  function(data){
			    <?php if($DEBUG_MODULE)echo "alert(data.query);alert(data.data);"?>
			    var graph_max = data.max;
			    var graph_data = new Array();
			    for (i = 0; i < data.data.length; i++) {
				graph_data[i] = new Array();
				graph_data[i][0]= parseInt(data.data[i][0]);
				graph_data[i][1]= data.data[i][1]
			     }
			    plot_graph_cust(graph_data,graph_max);
		     });

	});
$('.period_customers_graph').change(function () {
    period_val = $(this).val();
    if($(this).val() == "month" ) x_format ="%b";
    else x_format ="%d-%m";
    $('.update_customers_graph:checked').click();
   });

$('#view_customer_day').click();
$('#view_customer_day').change();
function plot_graph_cust(data,max){
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

    $.plot($("#cust_graph"), [
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


	$('#card_creation').click();

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
    $("#cust_graph").bind("plothover", function (event, pos, item) {
		if (item) {
        	if (previousPoint != item.datapoint) {
				previousPoint = item.datapoint;
                $("#tooltip").remove();
                var y = item.datapoint[1].toFixed(0);
                showTooltip(item.pageX, item.pageY, y);
        	}
        }
        else {
        	$("#tooltip").remove();
            previousPoint = null;            
		}
    });
});

</script>
