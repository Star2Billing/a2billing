<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");
include ("../lib/A2B_invoice.php");

session_start();

getpost_ifset(array(

	'billcalls',		// ('on', '') count calls
	'billcharges',		// ('on', '') count charges

	'entercustomer',	// filter on username  
	'enterprovider', 	// filter on provider id
	'entertrunk', 		// filter on trunk id
	'posted',			// ('1', '') if posted
	
	'period', // ('Month', 'Day')	choose date filter
	
	// used when period = 'Month'
	'frommonth', 		// enable from filter
	'fromstatsmonth',	// from date
	'tomonth', 			// enable to filter
	'tostatsmonth', 	// to date
	
	// used when period = 'Day'
	'fromday', 				// enable from filter
	'fromstatsmonth_sday', 	// from month - year
	'fromstatsday_sday', 	// from day	
	'fromstatsmonth_shour',	// from hour
	'fromstatsmonth_smin', 	// from min 
	
	'today', 				// enable to filter
	'tostatsmonth_sday',	// to month - year
	'tostatsday_sday', 		// to day	
	'tostatsmonth_shour', 	// to hour
	'tostatsmonth_smin', 	// to min
	
	// filter 
	'dsttype', 				// condition on dst
	'dst',					// destination filter
	
	'srctype',				// condition on src
	'src',					// source filter 
	
	'choose_currency',
	'exporttype',			// ('html', 'pdf') view result as
	'templatefile'			// name of Smarty template to use to display result 
	));

$verbose_level = 0;
$nowdate = date('Y-m-d H:i:s');

$DBHandle  = DbConnect();
	
// because we cannot display debug & PDF on the same page
if ($exporttype != 'pdf')
	$smarty->display('main.tpl');
else
	$verbose_level = 0; 

if ($posted == 1) {
	
	// Create Invoice object
	$invoice = new A2B_Invoice($DBHandle, $verbose_level);
	
	// Configure Invoice
	if ($enterprovider != "")
		$invoice->filter_provider	= $enterprovider;
	if ($entertrunk != "")
		$invoice->filter_trunk		= $entertrunk;

	// Filter on Customer
	if (isset($entercustomer) && $entercustomer != "") {

		$invoice->ReadCardInfo('', $entercustomer);
		$invoice->FindCoverDates(($billcalls == 'on'),($billcharges == 'on'), $nowdate);
	}
	$invoice->FindCoverDates(($billcalls == 'on'),($billcharges == 'on'), $nowdate);
	// Filter on source & destination
	if ($src != "") {
		$invoice->filter_source			= $src;
		$invoice->filter_source_op		= $srctype;		
	}
	
	if ($dst != "") {
		$invoice->filter_destination	= $dst;
		$invoice->filter_destination_op	= $dsttype;			
	}
		
	// Filter on Time
	switch ($period) {
		case 'Month':
			if ($frommonth)
				$invoice->cover_charge_startdate = $fromstatsmonth.'-01';
			elseif (!isset($invoice->cover_charge_startdate))	
				$invoice->cover_charge_startdate = '2001-01-01';
				
			if ($tomonth)
				$invoice->cover_charge_enddate   = $tostatsmonth.'-31';
			elseif (!isset($invoice->cover_charge_enddate))	
				$invoice->cover_charge_enddate = $nowdate;						
			break;
		case 'Day':
			if ($fromday)
				$invoice->cover_charge_startdate = "$fromstatsmonth_sday-$fromstatsday_sday $fromstatsmonth_shour:$fromstatsmonth_smin:00";
			elseif (!isset($invoice->cover_charge_startdate))	
				$invoice->cover_charge_startdate = '2001-01-01';	
			
			if ($today)
				$invoice->cover_charge_enddate   = "$tostatsmonth_sday-$tostatsday_sday $tostatsmonth_shour:$tostatsmonth_smin:59";
			elseif (!isset($invoice->cover_charge_enddate))	
				$invoice->cover_charge_enddate = $nowdate;				
			break;
		default:
			echo "\nUnknow Value for period: ".$period;
			break;			
	}
	$invoice->cover_call_startdate = $invoice->cover_charge_startdate;
	$invoice->cover_call_enddate   = $invoice->cover_charge_enddate;
	
	if ($billcalls != 'on')		$invoice->cover_call_enddate = $invoice->cover_call_startdate;
	if ($billcharges != 'on')	$invoice->cover_charge_enddate = $invoice->cover_charge_startdate;
	
	// Now generate Invoice
	$invoice->ListCalls();
	$invoice->ListCharges();
	$invoice->CreateInvoice($choose_currency);
	
} else { // Set default values
	$billcalls = 'on';
	$billcharges = 'on';
}

if ($exporttype != 'pdf') {
	
	$templatepath = $A2B->config['global']['sales_template_path'];
	
?>
<center>
<FORM name="myForm"  METHOD=POST ACTION="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo $current_page?>">
<INPUT TYPE="hidden" NAME="posted" value=1>
	<table class="bar-status" width="95%" border="0" cellspacing="1" cellpadding="2" align="center">
		<tbody>
			<tr>
				<td align="left" valign="top" class="bgcolor_004">
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CUSTOMERS");?></font>
				</td>				
				<td class="bgcolor_005" align="left" >
					<?php echo gettext("Enter the cardnumber");?>: <INPUT TYPE="text" NAME="entercustomer" value="<?php echo $entercustomer?>" class="form_input_text">
					<a href="#" onclick="window.open('A2B_entity_card.php?popup_select=2&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path;?>/icon_arrow_orange.gif"></a>
				</td>
			</tr>			
			<tr>
        		<td class="bgcolor_002" align="left">
					<input type="radio" name="period" value="Month" <?php  if (($period=="Month") || !isset($period)){ ?>checked="checked" <?php  } ?>>
					<font class="fontstyle_003"><?php echo gettext("SELECT MONTH");?></font>
				</td>
      			<td class="bgcolor_003" align="left" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="bgcolor_003"><tr>
						<td width="50%" class="fontstyle_searchoptions">
			  				<input type="checkbox" name="<?php echo gettext("frommonth");?>" value="true" <?php  if ($frommonth){ ?>checked<?php }?>>
							<?php echo gettext("From");?> : 
							<select name="fromstatsmonth" class="form_input_select">
							<?php 	$year_actual = date("Y");  	
								for ($i=$year_actual;$i >= $year_actual-1;$i--)
								{		   
								   $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
								   if ($year_actual==$i){
										$monthnumber = date("n")-1; // Month number without lead 0.
								   }else{
										$monthnumber=11;
								   }		   
								   for ($j=$monthnumber;$j>=0;$j--){
											$month_formated = sprintf("%02d",$j+1);
								   			if ($fromstatsmonth=="$i-$month_formated"){$selected="selected";}else{$selected="";}
											echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
								   }
								}
							?>
							</select>
						</td>
						<td class="fontstyle_searchoptions">
							<input type="checkbox" name="tomonth" value="true" <?php  if ($tomonth){ ?>checked<?php }?>> 
							<?php echo gettext("To");?> : 
							<select name="tostatsmonth" class="form_input_select">
							<?php 	
								$year_actual = date("Y");  	
								for ($i=$year_actual;$i >= $year_actual-1;$i--)
								{		   
								   $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
								   if ($year_actual==$i){
										$monthnumber = date("n")-1; // Month number without lead 0.
								   }else{
										$monthnumber=11;
								   }		   
								   for ($j=$monthnumber;$j>=0;$j--){	
											$month_formated = sprintf("%02d",$j+1);
								   			if ($tostatsmonth=="$i-$month_formated"){$selected="selected";}else{$selected="";}
											echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
								   }
								}
							?>
							</select>
						</td>
					</tr></table>
	  			</td>
    		</tr>
			<tr>
        		<td align="left" class="bgcolor_004">
					<input type="radio" name="period" value="Day" <?php  if ($period=="Day"){ ?>checked="checked" <?php  } ?>>
					<font class="fontstyle_003"><?php echo gettext("SELECT DAY");?></font>
				</td>
      			<td align="left" class="bgcolor_005">
					<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="bgcolor_005"><tr>
						<td width="50%" class="fontstyle_searchoptions">
			  				<input type="checkbox" name="fromday" value="true" <?php  if ($fromday){ ?>checked<?php }?>>
			  				<?php echo gettext("From");?> :				  				
							<select name="fromstatsday_sday" class="form_input_select">
							<?php  
							for ($i=1;$i<=31;$i++){
								if ($fromstatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
								echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
							}
							?>	
							</select>								
						 	<select name="fromstatsmonth_sday" class="form_input_select">
							<?php 	
							$year_actual = date("Y");  	
							for ($i=$year_actual;$i >= $year_actual-1;$i--)
							{		   
								   $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
								   if ($year_actual==$i){
										$monthnumber = date("n")-1; // Month number without lead 0.
								   }else{
										$monthnumber=11;
								   }		   
								   for ($j=$monthnumber;$j>=0;$j--){	
											$month_formated = sprintf("%02d",$j+1);
								   			if ($fromstatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
											echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
								   }
							}
							?>
							</select>								
							<select name="fromstatsmonth_shour" class="form_input_select">
							<?php  
							if (strlen($fromstatsmonth_shour)==0) $fromstatsmonth_shour='0';
							for ($i=0;$i<=23;$i++){	
								if ($fromstatsmonth_shour==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
								echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
							}
							?>					
							</select>
							:
							<select name="fromstatsmonth_smin" class="form_input_select">
							<?php  
							if (strlen($fromstatsmonth_smin)==0) $fromstatsmonth_smin='0';
							for ($i=0;$i<=59;$i++){	
								if ($fromstatsmonth_smin==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
								echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
							}
							?>
							</select>
						</td>
						<td class="fontstyle_searchoptions">
							<input type="checkbox" name="today" value="true" <?php  if ($today){ ?>checked<?php }?>> To : 
							<select name="tostatsday_sday" class="form_input_select">
							<?php  
							for ($i=1;$i<=31;$i++){
								if ($tostatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
								echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
							}
							?>						
							</select>								
						 	<select name="tostatsmonth_sday" class="form_input_select">
							<?php 	
							$year_actual = date("Y");  	
							for ($i=$year_actual;$i >= $year_actual-1;$i--)
							{		   
								   $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
								   if ($year_actual==$i){
										$monthnumber = date("n")-1; // Month number without lead 0.
								   }else{
										$monthnumber=11;
								   }		   
								   for ($j=$monthnumber;$j>=0;$j--){	
											$month_formated = sprintf("%02d",$j+1);
								   			if ($tostatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
											echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
								   }
							}
							?>
							</select>								
							<select name="tostatsmonth_shour" class="form_input_select">
							<?php  
								if (strlen($tostatsmonth_shour)==0) $tostatsmonth_shour='23';
								for ($i=0;$i<=23;$i++){	
									if ($tostatsmonth_shour==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
									echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
								}
							?>					
							</select>
							:
							<select name="tostatsmonth_smin" class="form_input_select">
							<?php  
								if (strlen($tostatsmonth_smin)==0) $tostatsmonth_smin='59';
								for ($i=0;$i<=59;$i++){	
									if ($tostatsmonth_smin==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}						
									echo '<option value="'.sprintf("%02d",$i)."\" $selected>".sprintf("%02d",$i).'</option>';
								}
							?>					
							</select>
						</td>
					</tr></table>
	  			</td>
    		</tr>
    		<tr />
    		<tr>
				<td class="bgcolor_002" align="left">			
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CALLS");?></font>
				</td>
				<td class="bgcolor_005" align="left" valign="bottom">		
					<input type="checkbox" name="billcalls" <?php if ($billcalls == "on") echo 'checked'; ?>>		
					<?php echo gettext("Count Calls");?>
				</td>				
    		<tr>
				<td class="bgcolor_004" align="left">			
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CONNECTION");?></font>
				</td>
				<td align="left" class="bgcolor_005">
						<?php echo gettext("Provider");?>: <INPUT TYPE="text" NAME="enterprovider" value="<?php echo $enterprovider?>" size="4" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_provider.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterprovider' , 'ProviderSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path ;?>/icon_arrow_orange.gif"></a>
						<?php echo gettext("Trunk");?>: <INPUT TYPE="text" NAME="entertrunk" value="<?php echo $entertrunk?>" size="4" class="form_input_text">
						<a href="#" onclick="window.open('A2B_entity_trunk.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertrunk' , 'TrunkSelection','width=550,height=330,top=20,left=100,scrollbars=1');"><img src="<?php echo Images_Path ;?>/icon_arrow_orange.gif"></a>
				</td>
			</tr>							
			<tr>
				<td class="bgcolor_002" align="left">			
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("DESTINATION");?></font>
				</td>
				<td class="bgcolor_003" align="left" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst" value="<?php echo $dst?>" class="form_input_text"></td>
							<td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="dsttype" value="1" <?php if((!isset($dsttype))||($dsttype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
							<td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="dsttype" value="2" <?php if($dsttype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
							<td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="dsttype" value="3" <?php if($dsttype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
							<td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="dsttype" value="4" <?php if($dsttype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
							<td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="dsttype" value="5" <?php if($dsttype==5){?>checked<?php }?>><?php echo gettext("Is Not");?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="left"  class="bgcolor_004">
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("SOURCE");?></font>
				</td>
				<td class="bgcolor_005" align="left" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0" >
						<tr>
							<td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="src" value="<?php echo "$src";?>" class="form_input_text"></td>
							<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="1" <?php if((!isset($srctype))||($srctype==1)){?>checked<?php }?>><?php echo gettext("Exact");?></td>
							<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="2" <?php if($srctype==2){?>checked<?php }?>><?php echo gettext("Begins with");?></td>
							<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="3" <?php if($srctype==3){?>checked<?php }?>><?php echo gettext("Contains");?></td>
							<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="4" <?php if($srctype==4){?>checked<?php }?>><?php echo gettext("Ends with");?></td>
							<td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="srctype" value="5" <?php if($srctype==5){?>checked<?php }?>><?php echo gettext("Is Not");?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr />
			<tr>
				<td class="bgcolor_002" align="left">			
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CHARGES");?></font>
				</td>
				<td class="bgcolor_005" align="left" valign="bottom">		
					<input type="checkbox" name="billcharges" <?php if ($billcharges == "on") echo 'checked'; ?>>		
					<?php echo gettext("Count Charges");?>
				</td>				
    		</tr>
    		<tr />
			<tr>
        		<td class="bgcolor_002" align="left" >
					<font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("OPTIONS");?></font>
				</td>

				<td class="bgcolor_003" align="center" >
					<table width="100%" border="0" cellspacing="0" cellpadding="0">						
						<tr class="bgcolor_005">
							<td  class="fontstyle_searchoptions">
								<?php echo gettext("EXPORT FORMAT");?> : 
							</td>
							<td  class="fontstyle_searchoptions">
								<?php echo gettext("See Invoice in HTML");?> <input type="radio" NAME="exporttype" value="html" <?php if((!isset($exporttype))||($exporttype=="html")){?>checked<?php }?>>
								<?php echo gettext("or Export PDF");?> <input type="radio" NAME="exporttype" value="pdf" <?php if($exporttype=="pdf"){?>checked<?php }?>>					
							</td>
						</tr>				
						<tr class="bgcolor_003">
							<td  class="fontstyle_searchoptions">
								<?php echo gettext("CURRENCY");?> :
							</td>
							<td  class="fontstyle_searchoptions">
								<select NAME="choose_currency" size="1" class="form_input_select" >
								<?php
									$currencies_list = get_currencies();
									foreach($currencies_list as $key => $cur_value) {
								?>
									<option value='<?php echo $key ?>' <?php if (($choose_currency==$key) || (!isset($choose_currency) && $key==strtoupper(BASE_CURRENCY))){?>selected<?php } ?>><?php echo $cur_value[1].' ('.$cur_value[2].')' ?>
									</option>
								<?php 	} ?>			
								</select>
							</td>
						</tr>
						<tr class="bgcolor_005">
							<td  class="fontstyle_searchoptions">
								<?php echo gettext("TEMPLATE");?> :
							</td>
							<td class="fontstyle_searchoptions">
								<select name="templatefile" class="form_input_select" >
									<?php
										$dir = opendir ('./templates/default/'.$templatepath);
								        while (false !== ($file = readdir($dir))) {
								                if (strpos($file, '.tpl',1)) {
								                    echo "<option value=\"$file\" ".($templatefile == $file? 'selected ':'').'> '.substr($file,0,-4).' </option>'; 
								                }
								        }
								        closedir($dir);
									?>
								</select>				
							</td>
						</tr>
					</table>
	  			</td>
    		</tr>
			<tr>
				<td class="bgcolor_004" align="left">						
				</td>
				<td class="bgcolor_005" align="left" >
					<center>
						<input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path;?>/button-search.gif" />						
					</center>	
				</td>
			</tr>			
		</tbody>
	</table>
</FORM>
</center>
<?php
}
if ($posted == 1) 	  				
	switch($exporttype) {
		case 'html':
			$invoice->DisplayHTML($smarty, 'sales', $templatefile);
			break;
		case 'pdf':
			$invoice->DisplayPDF($smarty, 'sales', $templatefile);
			break;
	}
?>
