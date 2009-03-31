<table width='100%'>
	<tr>
		<td align='left'>
		<h1>Outstanding</h1><br>
		<h3>Client # {$invoice->customer_username}</h3><br>
		</td>
		<td align='right'>
		LOGO
		</td>		
	</tr>
</table>
<br>

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
{assign var='sectiontotal' value=0}
{if $key == 'Calls'}
	<h2>Calls</h2>	
		<table width="100%">
		  <tr>
		    <th width="70%" align="left">Destination</th>
		    <th width="10%">Number of calls</th>
		    <th width="10%">Total time</th>
		    <th width="10%">Price</th>		    
		  </tr>
	
		{section name=inner loop=$item}
			<tr>
		    	<td>{ $item[inner].designation }</td>		    	
		    	<td align="right">{ $item[inner].nbcalls }</td>
		    	<td align="right">{math equation="temps/60" temps=$item[inner].calltime format="%d"}:{ $item[inner].calltime%60 }</td>
		    	<td align="right">{ $item[inner].price } {$invoice->invoice_currency}</td>
			</tr>
			{assign var=sectiontotal value=`$sectiontotal+$item[inner].price*$item[inner].quantity`}
		{/section}
		</table>
		<table width="100%"><tr>
			<td><h3>Subtotal</h3></td>
			<td align=right>{$sectiontotal}{ $invoice->invoice_currency }</td>
		</tr></table>
{elseif $key == 'Subscription fee'}
	<h2>Subscription</h2>
	<table width="100%">
	  <tr>
	    <th width="40%" align="left">Name</th>
	    <th width="10%">From</th>
	    <th width="10%">To</th>
	    <th width="25%">Unit price</th>
	    <th width="5%">Quantity</th>
	    <th width="10%">Line total</th>		    
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
	    <th width="25%">Unit price</th>
	    <th width="5%">Quantity</th>
	    <th width="10%">Line total</th>		    
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
{if $invoice->invoice_tax}
	<tr> <td width="80%" align='right'>Subtotal</td> <td align="right">  {$invoice->invoice_subtotal} {$invoice->invoice_currency}</td></tr>
	<tr> <td width="80%" align='right'>VAT ({$invoice->customer_VAT}%)</td> <td align="right"> {$invoice->invoice_tax} {$invoice->invoice_currency}</td></tr>
{/if}
	<tr> <td width="80%" align='right'><STRONG>Total</STRONG></td> <td align="right"><STRONG> {$invoice->invoice_total} {$invoice->invoice_currency}</STRONG></td></tr>

</table>