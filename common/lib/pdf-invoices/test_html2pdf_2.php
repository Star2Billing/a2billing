<?php
error_reporting(E_ALL);

if ($_GET["printable"] != 'yes') {
   require('html2pdf/html2fpdf.php');

   ob_start();
}

?>


<!-- FIN TITLE GLOBAL MINUTES //-->


<table width="100%">
<tr>
<td width="5%" align="left"><img src="images/ast-invoice.gif"/> </td>
<td align="left" width="50%">&nbsp; </td> 
<td align="right"> test</td>
</tr>
</table>

<table align="center" border="0" cellpadding="2" cellspacing="1" width="60%">

	<tr bgcolor="#600101">
		<td align="right" bgcolor="#b72222"><font color="#ffffff"><b>DATE</b></font></td>
        <td align="center"><font color="#ffffff"><b>DURATION</b></font></td>
		<td align="center"><font color="#ffffff"><b>GRAPHIC</b></font></td>
		<td align="center"><font color="#ffffff"><b>CALLS</b></font></td>
		<td align="center"><font color="#ffffff"><b>TOTALCOST</b></font></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#d2d8ed"></td>
		<td align="right" bgcolor="#f2f8ff"></td>
        <td align="right" bgcolor="#f2f8ff"></td>
        <td align="right" bgcolor="#f2f8ff"><font color="#000000" face="verdana" size="1">105</font></td>
        
		<td align="right" bgcolor="#f2f8ff"><font color="#000000" face="verdana" size="1">$ 2,280.54</font></td>
    </tr>
	
	
	<tr bgcolor="#600101">

		<td align="right"><font color="#ffffff"><b>TOTAL</b></font></td>
		<td colspan="2" align="center"><font color="#ffffff"><b>95:10 </b></font></td>
		<td align="center"><font color="#ffffff"><b>164</b></font></td>
		<td align="center"><font color="#ffffff"><b>$ 2,385.43</b></font></td>
	</tr>
</table>





<?php

if ($_GET["printable"] != 'yes') {

	$html = ob_get_contents();
	// delete output-Buffer
	ob_end_clean();
	
	$pdf = new HTML2FPDF();
	
	$pdf -> DisplayPreferences('HideWindowUI');
	
	$pdf -> AddPage();
	$pdf -> WriteHTML($html);
	
	$html = ob_get_contents();
	$pdf->Output('doc.pdf', 'I');
}
?>
