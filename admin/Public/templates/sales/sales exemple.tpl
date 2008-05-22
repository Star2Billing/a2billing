{if $invoice->cover_call_startdate == $invoice->cover_charge_startdate && $invoice->cover_call_enddate == $invoice->cover_charge_enddate}
Period from <STRONG>{$invoice->cover_call_startdate}</STRONG> to <STRONG>{$invoice->cover_call_enddate}</STRONG><br>
{else}
	{if $invoice->cover_call_startdate	!=	$invoice->cover_call_enddate}
	Calls from <STRONG>{$invoice->cover_call_startdate}</STRONG> to <STRONG>{$invoice->cover_call_enddate}</STRONG><br>
	{/if}	
	
	{if $invoice->cover_charge_startdate !=	$invoice->cover_charge_enddate}
	Charges from <STRONG>{$invoice->cover_charge_startdate}</STRONG> to <STRONG>{$invoice->cover_charge_enddate}</STRONG><br>
	{/if}
{/if}
{foreach key=key item=item from=$invoice->list_category_items}
<br>
{assign var='sectionsell' value=0}
{assign var='sectionbuy' value=0}
{assign var='sectionnbcall' value=0}
{assign var='sectionlength' value=0}
{if $key == 'Calls'}
	<h2>Calls</h2>	
		<table width="90%">
		  <tr>
		    <th width="50%" align="left">Destination</th>
		    <th width="10%">Nb calls</th>
		    <th width="10%">Total time</th>
		    <th width="10%">Buy</th>
		    <th width="10%">Sold</th>
		    <th width="10%">Margin</th>
		  </tr>
	
		{section name=inner loop=$item}
			<tr>
		    	<td>{ $item[inner].designation }</td>
		    	<td align="right">{ $item[inner].nbcalls }</td>
		    	<td align="right">{math equation="temps/60" temps=$item[inner].calltime format="%d"}:{ $item[inner].calltime%60 }</td>
		    	<td align="right">{ $item[inner].price } {$invoice->invoice_currency}</td>
		    	<td align="right">{ $item[inner].buy_price } {$invoice->invoice_currency}</td>
		    	<td align="right">{math equation="(s-b)*100/s" s=$item[inner].price b=$item[inner].buy_price format="%.2f"}%</td>
			</tr>
			{assign var=sectionnbcall value=`$sectionnbcall+$item[inner].nbcalls`}
			{assign var=sectionlength value=`$sectionlength+$item[inner].calltime`}
			{assign var=sectionsell value=`$sectionsell+$item[inner].price*$item[inner].quantity`}
			{assign var=sectionbuy value=`$sectionbuy+$item[inner].buy_price*$item[inner].quantity`}		
		{/section}
			<tr>
				<td><h3>Subtotal</h3></td>
				<td align=right><h3>{$sectionnbcall}</h3></td>
				<td align=right><h3>{math equation="temps/60" temps=$sectionlength format="%d"}:{ $sectionlength%60 }</h3></td>
				<td align=right><h3>{$sectionsell} { $invoice->invoice_currency }</h3></td>
				<td align=right><h3>{$sectionbuy} { $invoice->invoice_currency }</h3></td>		
				<td align=right><h3>{math equation="(s-b)*100/s" s=$sectionsell b=$sectionbuy format="%.2f"}%</h3></td>
			</tr>
		</table>
{elseif $key == 'Subscription fee'}
	<h2>Subscription fee</h2>
	<table width="100%">
	  <tr>
	    <th width="40%" align="left">Name</th>
	    <th width="10%">From</th>
	    <th width="10%">To</th>
	    <th width="25%">Unit Price</th>
	    <th width="5%">Quantity</th>
	    <th width="10%">Line Total</th>
	  </tr>

	{section name=inner loop=$item}
		<tr>
	    	<td>{ $item[inner].designation }<small>{ $item[inner].sub_designation }</small></td>		    	
	    	<td align="right">{ $item[inner].start_date }</td>
	    	<td align="right">{ $item[inner].end_date }</td>
	    	<td align="right">{ $item[inner].price } { $invoice->invoice_currency }</td>
	    	<td align="right">{ $item[inner].quantity }</td>
	    	<td align="right">{ $item[inner].price*$item[inner].quantity } { $invoice->invoice_currency }</td>
		</tr>
		{assign var=sectiontotal value=`$sectiontotal+$item[inner].price*$item[inner].quantity`}
	{/section}
	</table>
	<table width="100%"><tr>
		<td><h3>Subtotal</h3></td>
		<td align=right>{$sectiontotal} { $invoice->invoice_currency }</td>
	</tr></table>
{else}
	<h2>{$key}</h2>			
	<table width="100%">
	  <tr>
	    <th width="60%" align="left">Name</th>
	    <th width="25%">Unit Price</th>
	    <th width="5%">Quantity</th>
	    <th width="10%">Line Total</th>		    
	  </tr>

	{section name=inner loop=$item}
		<tr>
	    	<td>{ $item[inner].designation }<small>{ $item[inner].sub_designation }</small></td>		    	
	    	<td align="right">{ $item[inner].price } { $invoice->invoice_currency }</td>
	    	<td align="right">{ $item[inner].quantity }</td>
	    	<td align="right">{ $item[inner].price*$item[inner].quantity } { $invoice->invoice_currency }</td>
		</tr>
		{assign var=sectiontotal value=`$sectiontotal+$item[inner].price*$item[inner].quantity`}
	{/section}
	</table>
	<table width="100%"><tr>
		<td><h3>Subtotal</h3></td>
		<td align=right>{$sectiontotal} { $invoice->invoice_currency }</td>
	</tr></table>
{/if}
{/foreach}<br><br>


<table width="100%">
	<tr> <td width="80%" align='right'><STRONG>Total</STRONG></td> <td align="right"><STRONG> {$invoice->invoice_total} {$invoice->invoice_currency}</STRONG></td></tr>
</table>
