<?php


$DBHandle  = DbConnect();
$table = new Table('cc_call','*');

$temp = date("Y-m-01");
$datetime = new DateTime($temp);
$datetime_end = new DateTime($temp);
$datetime_end->modify("+1 month");
$datetime->modify("-5 month");
$checkdate= $datetime->format("Y-m-d");
$end_date_graphe = $datetime_end->format("Y-m-01");
$mingraph = strtotime($checkdate);
$maxgraph = strtotime($end_date_graphe);
$QUERY_GRAPH_CALL_ANSWERED = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')), count(*) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid = 1 GROUP BY MONTH(starttime) ORDER BY starttime;";
$result_graph_calls_answered = $table -> SQLExec($DBHandle,$QUERY_GRAPH_CALL_ANSWERED);
$QUERY_GRAPH_CALL_INCOMPLET = "SELECT UNIX_TIMESTAMP(DATE_FORMAT(starttime,'%Y-%m-01')), count(*) FROM cc_call WHERE starttime>= TIMESTAMP('$checkdate') AND starttime <= CURRENT_TIMESTAMP AND terminatecauseid != 1 GROUP BY MONTH(starttime) ORDER BY starttime;";
$result_graph_calls_incomplet = $table -> SQLExec($DBHandle,$QUERY_GRAPH_CALL_INCOMPLET);
?>


<input id="call_answer" type="radio" name="mode_call" value="answered">&nbsp; <?php echo gettext("ANSWERED CALL BY MONTH"); ?><br/>
<input id="call_incomplet" type="radio" name="mode_call" value="incomplet">&nbsp; <?php echo gettext("INCOMPLET CALL BY MONTH"); ?><br/>
<br/>
<div id="call_graph" class="dashgraph" style="width:310px;height:170px;margin-left: auto;margin-right: auto;"></div>

<?php 
//Creationdate data treatment
  	 $val_answer= "[";
  	 $max_answer=0;
  	 if(is_array($result_graph_calls_answered)){
		 for($i=0;$i<count($result_graph_calls_answered);$i++) {
		 	$max_answer = max($max_answer,$result_graph_calls_answered[$i][1]);
		   $val_answer.="[".$result_graph_calls_answered[$i][0]."000,".$result_graph_calls_answered[$i][1]."]";
		   if($i<count($result_graph_calls_answered)-1){ 
		   	$val_answer.=",";
		   }
		 }
  	 }
	 $val_answer.="]";
//expiration data treatment	 
	 $max_incomplet=0;
	 $val_incomplet= "[";
  	 if(is_array($result_graph_calls_incomplet)){
		 for($i=0;$i<count($result_graph_calls_incomplet);$i++) {
		 	$max_incomplet = max($max_incomplet,$result_graph_calls_incomplet[$i][1]);
		   	$val_incomplet.="[".$result_graph_calls_incomplet[$i][0]."000,".$result_graph_calls_incomplet[$i][1]."]";
			if($i<count($result_graph_calls_incomplet)-1){ 
		   	$val_incomplet.=",";
		   }
		 }
  	 }
	 $val_incomplet.="]";
	 
	 
	 ?>
<script id="source" language="javascript" type="text/javascript">
  	
$(document).ready(function () {
	$('#call_answer').click(function () {
	
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
