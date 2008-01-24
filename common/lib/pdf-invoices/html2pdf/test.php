<?php


if ($printable != 'yes') {
   require('html2fpdf.php');
   
   $html = "coucouc";


   
   $pdf = new HTML2FPDF();
	//echo "------";
	$pdf -> DisplayPreferences('HideWindowUI');
	
	$pdf -> AddPage();
	$pdf -> WriteHTML($html);
	
	
	//-- $html = ob_get_contents();
	$pdf->Output('doc.pdf', 'I');
}



?>
