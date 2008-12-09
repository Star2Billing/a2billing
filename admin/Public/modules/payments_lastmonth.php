<?php

$DBHandle  = DbConnect();
$table = new Table('cc_logpayment','*');

$temp = date("Y-m-01");
$datetime = new DateTime($temp);
$datetime_end = new DateTime($temp);
$datetime_end->modify("+1 month");
$datetime->modify("-11 month");
$checkdate= $datetime->format("Y-m-d");
$datetime->modify("-15 day");
$begin_date_graphe = $checkdate= $datetime->format("Y-m-d");
$end_date_graphe = $datetime_end->format("Y-m-01");
$mingraph = strtotime($begin_date_graphe);
$maxgraph = strtotime($end_date_graphe);
$QUERY_GRAPH_PAYMENT_COUNT = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) ) , count( * ) , DATE_FORMAT( date, '%M' ) FROM cc_logpayment WHERE date >= TIMESTAMP( '$checkdate' ) AND date <=CURRENT_TIMESTAMP GROUP BY MONTH( date ) ORDER BY date;";
$result_graph_payment_count = $table -> SQLExec($DBHandle,$QUERY_GRAPH_PAYMENT_COUNT);
$QUERY_GRAPH_PAYMENT_AMOUNT = "SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) ) , SUM( payment ) , DATE_FORMAT( date, '%M' ) FROM cc_logpayment WHERE date >= TIMESTAMP( '2008-01-01' ) AND date <=CURRENT_TIMESTAMP GROUP BY MONTH(date) ORDER BY date;";
$result_graph_payment_amount = $table -> SQLExec($DBHandle,$QUERY_GRAPH_PAYMENT_AMOUNT);
?>










<input id="payments_count" type="radio" name="mode_paym" value="count">&nbsp; <?php echo gettext("NUMBERS OF PAYMENTS BY MONTH"); ?><br/>
<input id="payments_amount" type="radio" name="mode_paym" value="amount">&nbsp; <?php echo gettext("AMOUNT OF PAYMENTS BY MONTH"); ?><br/>
<br/>
<div id="payments_graph" class="dashgraph" style="width:310px;height:160px;margin-left: auto;margin-right: auto;"></div>

<?php 
//Creationdate data treatment
  	 $val_count= "[";
  	 $max_count=0;
  	 if(is_array($result_graph_payment_count)){
		 for($i=0;$i<count($result_graph_payment_count);$i++) {
		 	$max_count = max($max_count,$result_graph_payment_count[$i][1]);
		   $val_count.="[".$result_graph_payment_count[$i][0]."000,".$result_graph_payment_count[$i][1]."]";
		   if($i<count($result_graph_payment_count)-1){ 
		   	$val_count.=",";
		   }
		 }
  	 }
	 $val_count.="]";
//expiration data treatment	 
	 $max_amount=0;
	 $val_amount= "[";
  	 if(is_array($result_graph_payment_amount)){
		 for($i=0;$i<count($result_graph_payment_amount);$i++) {
		 	$max_amount = max($max_amount,$result_graph_payment_amount[$i][1]);
		   	$val_amount.="[".$result_graph_payment_amount[$i][0]."000,".$result_graph_payment_amount[$i][1]."]";
			if($i<count($result_graph_payment_amount)-1){ 
		   	$val_amount.=",";
		   }
		 }
  	 }
	 $val_amount.="]";
	 ?>
	 
	 
<script id="source" language="javascript" type="text/javascript">
  	
$(document).ready(function () {
var width= Math.min($("#payments_count").parent("div").width(),$("#payments_count").parent("div").innerWidth());
$("#payments_count").width(width-10);
$("#payments_count").height(Math.floor(width/2));
  	
	$('#payments_count').click(function () {
	
		var d = <?php echo $val_count ?>;
    	$.plot($("#payments_graph"), [
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
							    ticks :12,
						   		min : <?php echo $mingraph."000" ?>,
						   		max : <?php echo $maxgraph."000" ?>
							  }, 
							  yaxis: {
							  max:<?php echo ($max_count+5-($max_count%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
	
	        });
	$('#payments_amount').click(function () {
			var d = <?php echo $val_amount ?>;
    		$.plot($("#payments_graph"), [
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
							    ticks :12,
						   		min : <?php echo $mingraph."000" ?>,
						   		max : <?php echo $maxgraph."000" ?>
							  }, 
							  yaxis: {
							  max:<?php echo ($max_amount+5-($max_amount%5)); ?>,
							  minTickSize: 1,
							  tickDecimals:0
							  },selection: { mode: "y" },
						         grid: { hoverable: true,clickable: true}
							  });
				
			
	        });
	        
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
