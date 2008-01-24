<?php
include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/smarty.php");
include ("../lib/invoice.php");

if (! has_rights (ACX_INVOICING)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('customer', 'posted', 'Period', 'cardid','exporttype','choose_billperiod','id','invoice_type'));
if ($invoice_type == "")
{
	$invoice_type = 1;
}
if ($invoice_type == 1)
{
	if($cardid == "" )
	{
		exit("Invalid ID");
	}
}
if ( $invoice_type == 2)
{
	if(($id == "" || !is_numeric($id)))
	{
		exit(gettext("Invalid ID"));
	}
}
if ($invoice_type == 1)
{
	$ok = EmailInvoice($cardid, 1);
}
else
{
	$ok = EmailInvoice($id, 2);
	$invoice_id = $id;
	if($ok)
	{
		$issent = 1;
	}
	$instance_table = new Table();
	$currentdate = date("Y-m-d h:i:s");
	$QUERY = "INSERT INTO cc_invoice_history (invoiceid,invoicesent_date,invoicestatus) VALUES('$invoice_id', '$currentdate', '$issent')";
	$instance_table -> SQLExec (DbConnect(), $QUERY);
}


$smarty->display('main.tpl');
if($ok) 
{ 
?>
	<br><br>
	<table align="center" width="415" style="border:1px solid orange;">	
	<tr>
	<td width="407" height="20" bgcolor="#000066"><font color="#FFFFFF" face="Verdana, Arial, Helvetica, sans-serif"><b><?php echo gettext("Message")?></b></font></td>
	</tr>
	<tr>
	<td height="82" align="center" valign="middle"><font color="#000066" face="Verdana, Arial, Helvetica, sans-serif"><?php echo gettext("Congratulations!!! Email sent successfully to")." ".$info_customer[0][10]?>&nbsp;<?php echo $email_to;?>.</font></td>
	</tr>
	<tr>
	<td align="center"><FORM>
<INPUT type="button" value="&nbsp;Back&nbsp;" onClick="history.back()" class="form_input_button">
</FORM></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	</table><br><br><br>
	
<?php
}
else
{ 
?>
<br><br>
<table align="center" width="415" style="border:1px solid orange;">	
	<tr>
	<td width="407" height="20" bgcolor="#000066"><font color="#FFFFFF" face="Verdana, Arial, Helvetica, sans-serif"><b><?php echo gettext("Message")?></b></font></td>
	</tr>
	<tr>
	<td height="82" align="center" valign="middle"><font color="#000066" face="Verdana, Arial, Helvetica, sans-serif"><?php echo gettext("Sorry!!! Email sending failed to")?>&nbsp;<?php echo $email_to;?>.</font></td>
	</tr>
	<tr>
	<td align="center">
	<FORM>
	<INPUT type="button" value="&nbsp;Back&nbsp;" onClick="history.back()" class="form_input_button">
	</FORM>
</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	</table><br><br><br>
	
<?php
	
} 
$smarty->display('footer.tpl');
 

?>
