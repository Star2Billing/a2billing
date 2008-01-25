<?php
//error_reporting(E_ALL);

if ($printable != 'yes') {
   require('html2pdf/html2fpdf.php');

   ob_start();
}

?>
<h1>TEST h1</h1>
<h3>test h3</h3>
<FONT STYLE="font-size:35pt">my font 1</font><br>
<font size="2">my font 2</font><br>
<font size="3">my font 3</font><br>
<font size="4">my font 4</font><br>

<table width="100%">
<tr>
<td width="5%" align="left"><img src="images/security.png"/> </td>
<td align="left" width="50%">&nbsp; </td> 
<td align="right">  </td>
</tr>
</table>


<table width="100%"  bgcolor="#f3f3f3">

	<tr>
		<td width="75%">&nbsp;
		
		</td>		
		<td width="25%">
<br/>
<font color="#000000" face="verdana" size="3">
Arezqui Belaid <br>
NIF :00X269595P <br>
Street valground 45, 2-5 <br><br>
<b>Phone : +34 650 784355</b>
</font>
			
		</td>
	</tr>
</table>




<br><hr width="350"><br><br>

<table width="100%">
<tr>
<td align="left"> -- </td>
<td align="center"  bgcolor="#fff1d1"><font color="#000000" face="verdana" size="5"> <b>B I L L I N G &nbsp;&nbsp; S E R V I C E</b> </td> 
</tr>
</table>

<br><br>


	<!-- TABLE GLOBAL //-->
	<table border="0" cellpadding="2" cellspacing="1" width="75%" align="center">
	
		<!-- TABLE TITLE //-->
		<tr bgcolor="#b3a3a3">
			<td align="center" width="45%"><font color="#ffffff" face="verdana" size="1"><b>DESTINATION</b></font></td>
			<td align="center"><font color="#ffffff" face="verdana" size="1"><b>NB CALLS</b></font></td>
			<td align="center"><font color="#ffffff" face="verdana" size="1"><b>DURATION</b></font></td>		
			<td align="center"><font color="#ffffff" face="verdana" size="1"><b>COST</b></font></td>
		</tr>
		<tr>
			<td align="right" colspan="4"></td>			
		</tr>
		
		<!-- DESTINATION BEGIN -->		
		<tr>
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">Internation calls</font></td>
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4057</font></td>                
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">21317:17</font></td>		
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4879 Dollars</font></td>                        	
		</tr>
		<!-- DESTINATION END -->		
		
		<!-- DESTINATION BEGIN -->		
		<tr>
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">National calls</font></td>
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">13</font></td>                
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">213:17</font></td>		
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">579 Dollars</font></td>                        	
		</tr>
		<!-- DESTINATION END -->	

		<!-- DESTINATION BEGIN -->		
		<tr>
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">Long Distance calls</font></td>
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">307</font></td>                
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">217:17</font></td>		
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">539 Dollars</font></td>                        	
		</tr>
		<!-- DESTINATION END -->

		<!-- DESTINATION BEGIN -->		
		<tr>
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">Internation calls</font></td>
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4057</font></td>                
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">21317:17</font></td>		
			<td align="center" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4879 Dollars</font></td>                        	
		</tr>
		<!-- DESTINATION END -->	

		<!-- DESTINATION BEGIN -->		
		<tr>
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">Internation calls</font></td>
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4057</font></td>                
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">21317:17</font></td>		
			<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4879 Dollars</font></td>                        	
		</tr>
		<!-- DESTINATION END -->
		
	
		<!-- TOTAL -->
		<tr>
			<td align="right" colspan="4"></td>			
		</tr>
		<tr bgcolor="#737373">
			<td align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>TOTAL</b></font></td>
			<td align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>4057</b></font></td>			
			<td align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>21317:17 </b></font></td>
			<td align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>4879 Dollars</b></font></td>                        
		</tr>
		<tr>
			<td align="right" colspan="24"></td>			
		</tr>
		<tr bgcolor="#535353">
			<td align="center" nowrap="nowrap"  bgcolor="#ffffff"></td>
			<td align="center" nowrap="nowrap"  bgcolor="#ffffff"></td>
			<td align="center" nowrap="nowrap" colspan="2" background="topshade.png"><font color="#ffffff" face="verdana" size="3"><b>TOTAL TO PAY (DOLLARS) : 4879 </b></font></td> 	                    
		</tr>
		<!-- END TOTAL -->

	</table>
	<!-- END TABLE GLOBAL //-->


  <br>






<br><hr width="350"><br><br>

<table width="100%">
<tr>
<td align="left"> -- </td>
<td align="center"  bgcolor="#fff1d1"><font color="#000000" face="verdana" size="5"> <b>B I L L &nbsp;&nbsp;  E V O L U T I O N</b> </td> 
</tr>
</table>

<br><br>


<table width="50%" align="center">
<tr>
<td align="center">
<b>Consumitions + 18 % <br>(since last month)</b>
</td>
<td align="right">
---
</td>
</tr>
</table>





<br></br><br></br>



	<table border="0" cellpadding="2" cellspacing="1" width="75%" align="center">
	<tr>	
		<td align="center" bgcolor="#600101"></td>
    	<td colspan="4" align="center" bgcolor="#b72222"><font color="#ffffff" face="verdana" size="1"><b>CONSUMITION PER DAY</b></font></td>
    </tr>
	<tr bgcolor="#600101">
		<td align="right" bgcolor="#b72222"><font color="#ffffff" face="verdana" size="1"><b>DATE</b></font></td>
        <td align="center"><font color="#ffffff" face="verdana" size="1"><b>DURATION</b></font></td>

		<td align="center"><font color="#ffffff" face="verdana" size="1"><b>GRAPHIC</b></font></td>
		<td align="center"><font color="#ffffff" face="verdana" size="1"><b>CALLS</b></font></td>
		<td align="center"><font color="#ffffff" face="verdana" size="1"><b> <acronym title="Average Connection Time">ACT</acronym> </b></font></td>
                			
		<!-- LOOP -->
	</tr>
	<tr>
		<td align="right" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">2005-06-08</font></td>

		<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">21317:17 </font></td>
                
        <td bgcolor="#f2f8ff" >
			---
        </td>
        <td align="right" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">4057</font></td>
        <td align="right" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">05:15 </font></td>

                        	
	</tr>
	<!-- FIN DETAIL -->	
	<tr>
		<td align="right" bgcolor="#D2D8ED" nowrap="nowrap"><font color="#000000" face="verdana" size="1">2005-06-09</font></td>

		<td align="center" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">213:17 </font></td>
                
        <td bgcolor="#f2f8ff" >
			===
        </td>
        <td align="right" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">405</font></td>
        <td align="right" bgcolor="#f2f8ff" nowrap="nowrap"><font color="#000000" face="verdana" size="1">04:35 </font></td>

                        	
	</tr>
	<!-- FIN DETAIL -->	
	
				
				<!-- FIN BOUCLE -->

	<!-- TOTAL -->
	<tr bgcolor="#600101">
		<td align="right" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>TOTAL</b></font></td>
		<td colspan="2" align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>21317:17 </b></font></td>
		<td align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>4657</b></font></td>

		<td align="center" nowrap="nowrap"><font color="#ffffff" face="verdana" size="1"><b>05:15</b></font></td>                        
	</tr>
	<!-- FIN TOTAL -->

	</table>
	<!-- Fin Tableau Global //-->










<br><br><hr width="350"><br><br>

<table width="100%">
<tr>
<td align="left"> ---- </td>
<td align="center"  bgcolor="#fff1d1"><font color="#000000" face="verdana" size="5"> <b>C A L L S &nbsp;&nbsp;  D E T A I L</b> </td> 
</tr>
</table>

<br><br>


<center>Number of call : 33</center>


<table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
<tbody>
                <tr bgcolor="#f0f0f0"> 
				  <td class="tableBodyRight" style="padding: 2px;" align="center" width="5%"></td>					
				  
                  				
				  
					
                  <td class="tableBody" style="padding: 2px;" align="center" width="15%"> 
                    <center><strong>    Calldate  
                                        </strong></center></td>

				   				
				  
					
                  <td class="tableBody" style="padding: 2px;" align="center" width="15%"> 
                    <center><strong> 
                                                          CalledNumber
                                        </strong></center></td>
				   				
				  
					
                  <td class="tableBody" style="padding: 2px;" align="center" width="15%"> 
                    <center><strong>   Destination 
                                        </strong></center></td>
				   				
				  
					
                  <td class="tableBody" style="padding: 2px;" align="center" width="7%"> 
                    <center><strong>            Duration 
                                        </strong></center></td>

				   				
				  
					
                  <td class="tableBody" style="padding: 2px;" align="center" width="11%"> 
                    <center><strong> 
                                                          CardUsed 
                                        </strong></center></td>
				   				
				  
				  
					
                  <td class="tableBody" style="padding: 2px;" align="center" width="10%"> 
                    <center><strong> 
                                                          Cost 
                                        
                                        </strong></center></td>
				   		
				   				   
                  
				   		
                </tr>

                <tr> 
                  <td colspan="10" bgcolor="#e1e1e1" height="1"></td>
                </tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">1.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-10 02:17</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3265888</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:07</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">9065679486</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.11</td>

				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">2.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-10 02:05</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">326521111</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:33</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.50</td>
				 		                   
					</tr>

								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">3.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-10 02:02</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">32658544</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">01:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.90</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">4.&nbsp;</td>

							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-10 01:56</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3265255</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">01:05</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>
				 		 						
						  
						                 		
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.98</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">5.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-10 01:52</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">32658529</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:36</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.02</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">6.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 23:35</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3265854</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:06</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">7.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 23:22</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3252</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:01</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>

				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">8.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:37</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">147584232</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Long distance</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:58</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						                 		
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.05</td>
				 		                   
					</tr>

								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">9.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:35</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">145782858</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Long distance</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">10.&nbsp;</td>

							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:35</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1478488</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Long distance</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">11.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:33</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3247747</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">International</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:30</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.08</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">12.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:30</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">324885</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						                 	
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">13.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:29</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3225455</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						                 	
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>

				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">14.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-06 00:23</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3265478</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">Local</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						                 	
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>

								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">15.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-05 17:59</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">326588</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:17</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>

				 		 						
						  
						               
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.75</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">16.&nbsp;</td>

							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-05 17:56</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3265123456</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:14</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						                 
						                 		 <td class="tableBody" align="center" valign="top">$ 0.75</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">17.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-05 17:51</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3265844</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:17</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.75</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">18.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-06-05 17:39</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">326523</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:06</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">1003485122</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.75</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">19.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-31 21:10</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">325652</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:04</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>
				 		 						
						  
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.75</td>

				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">20.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-25 22:06</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">32666895</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3764290222</td>
				 		 						
						  
						                 		
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>

								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">21.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-25 22:02</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">328555</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3764290222</td>

				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">22.&nbsp;</td>

							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-25 22:01</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">325588</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3764290222</td>
				 		 						
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">23.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-25 21:59</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3258844</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">belgium</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:05</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">3764290222</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.75</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#FFFFFF'" bgcolor="#ffffff"> 
						<td class="tableBody" align="" valign="top">24.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-23 20:15</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">111111588</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">buu</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:09</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>
				 		 						
						  
						                 		
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 18.45</td>
				 		                   
					</tr>
								
               		 <tr onmouseover="bgColor='#C4FFD7'" onmouseout="bgColor='#F2F8FF'" bgcolor="#f2f8ff"> 
						<td class="tableBody" align="" valign="top">25.&nbsp;</td>
							 
				  								
						  
						                 		 <td class="tableBody" align="center" valign="top">2005-05-23 19:54</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">11111125</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">buu</td>

				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">00:00</td>
				 		 						
						  
						                 		 <td class="tableBody" align="center" valign="top">2222222222</td>
				 		 						
						  
						  
						                 		 <td class="tableBody" align="center" valign="top">$ 0.00</td>

				 		                   
					</tr>
				                <tr> 
                  <td class="tableDivider" colspan="10"></td>
                </tr>
                <tr> 
                  <td class="tableDivider" colspan="10"> -- </td>
                </tr>
              </tbody>
            </table>


<!-- ** ** ** ** ** Part to display the GRAPHIC ** ** ** ** ** -->
<br><br><br><br><br><br><br><br>

<?php

if ($printable != 'yes') {

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
