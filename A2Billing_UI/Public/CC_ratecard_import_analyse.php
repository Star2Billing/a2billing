<?php
// Common includes
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/smarty.php");

set_time_limit(0);

if (! has_rights (ACX_RATECARD)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('tariffplan','trunk', 'search_sources', 'task', 'status','currencytype','uploadedfile_name','uploadedfile_name'));


//print_r ($_POST);
//print_r ($HTTP_POST_FILES);
 

$tariffplanval= split('-:-', $tariffplan);
if (!is_numeric($tariffplanval[0])){ 
	echo gettext("No tariffplan defined !"); 
	exit();
}

$trunkval= split('-:-', $trunk);
if (!is_numeric($trunkval[0])){ 
	echo gettext("No Trunk defined !"); 
	exit();
}

if ($search_sources!='nochange'){

	//echo "<br>---$search_sources";
	$fieldtoimport= split("\t", $search_sources);
	$fieldtoimport_sql = str_replace("\t", ", ", $search_sources);
	$fieldtoimport_sql = trim ($fieldtoimport_sql);
	if (strlen($fieldtoimport_sql)>0) $fieldtoimport_sql = ', '.$fieldtoimport_sql;
}

//echo "<br>---$fieldtoimport_sql<br>";
//print_r($fieldtoimport);


$fixfield[0]="IDTariffplan (KEY)";
$fixfield[1]="Outbound Trunk";

$field[0]="Dialprefix";
$field[1]="Destination Country";
$field[2]="Rate Initial";

$FG_DEBUG = 0;

if (DB_TYPE == "mysql"){
	$sp = "`";
}

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";
$Temps1 = time();
if ($FG_DEBUG == 1) echo "::::>> ".$the_file;
//INUTILE
$my_max_file_size = (int) MY_MAX_FILE_SIZE_IMPORT;

if ($FG_DEBUG == 1) echo "<br> Task :: $task";
if ($task=='upload'){

	//---------------------------------------------------------
	//		 Effacer tout les fichiers du repertoire cache.
	//---------------------------------------------------------

	$the_file_name = $_FILES['the_file']['name'];
	$the_file_type = $_FILES['the_file']['type'];
	$the_file = $_FILES['the_file']['tmp_name'];

	if(count($_FILES) > 0)
	{
		$errortext = validate_upload($the_file, $the_file_type);	
		if ($errortext != "" || $errortext  != false)	
		{
			echo $errortext;
			exit;
		}
		$new_filename = "/tmp/".MDP(6).".csv";
		if (file_exists($new_filename))
		{
			echo $_FILES["file"]["name"] . " already exists. ";
		}
		else
		{
			if(!move_uploaded_file($_FILES["the_file"]["tmp_name"],	$new_filename))
			{
			    echo gettext("File Save Failed, FILE=".$new_filename);
			}
		}
		$the_file = $new_filename;
	}
	else
	{		
		$the_file_type = $uploadedfile_type;
		$the_file = $uploadedfile_name;
	}
	
	if ($FG_DEBUG == 1) echo "<br> FILE  ::> ".$the_file_name;
	if ($FG_DEBUG == 1) echo "<br> THE_FILE:$the_file <br>THE_FILE_TYPE:$the_file_type";	
	
	$fp = fopen($the_file,  "r");  
	if (!$fp){  /* THE FILE DOESN'T EXIST */ 
		echo  gettext('Error: Failed to open the file.'); 
		exit(); 
	} 
	
	$chaine1 = '"\'';
	
	$nb_imported=0;
	$nb_to_import=0;
	$DBHandle  = DbConnect();
    
	while (!feof($fp)){ 
		
		//if ($nb_imported==1000) break;
		$ligneoriginal = fgets($fp,4096);  /* On se dplace d'une ligne */   
		$ligneoriginal = trim ($ligneoriginal);
		$ligneoriginal = strtolower($ligneoriginal);
		
		
		for ($i = 0; $i < strlen($chaine1); $i++)   
			$ligne = str_replace($chaine1[$i], ' ', $ligneoriginal);
		
		$ligne = str_replace(',', '.', $ligne);
		$val= split('[;:]', $ligne);
		$val[0]=str_replace('"', '', $val[0]); //DH
		$val[1]=str_replace('"', '', $val[1]); //DH
		$val[2]=str_replace('"', '', $val[2]); //DH
		$val[0]=str_replace("'", '', $val[0]); //DH
		$val[1]=str_replace("'", '', $val[1]); //DH
		$val[2]=str_replace("'", '', $val[2]); //DH
		
		if ($status!="ok"){ 
			if($currencytype == "cent")
			{
				$val[2] = $val[2] / 100;
			}
			break;
		}
		//if ($val[2]!='' && strlen($val[2])>0){
		if (substr($ligne,0,1)!='#' && substr($ligne,0,2)!='"#' && $val[2]!='' && strlen($val[2])>0){
			
			$FG_ADITION_SECOND_ADD_TABLE  = 'cc_ratecard';		
			$FG_ADITION_SECOND_ADD_FIELDS = 'idtariffplan, id_trunk, dialprefix, destination, rateinitial'; //$fieldtoimport_sql				
			if($currencytype == "cent")
			{
			$val[2] = $val[2] / 100;
			}
			
			$FG_ADITION_SECOND_ADD_VALUE  = "'".$tariffplanval[0]."', '".$trunkval[0]."', '".$val[0]."', '".$val[1]."', '".$val[2]."'"; //, '".$val[5]."', '".$val[6]."', '".$val[7]."', '".$val[8]."', '".$val[9]."', '".$val[10]."', '".$val[11]."', '".$val[12]."', '".$val[13]."', '".$val[14]."', '".$val[15]."', '".$val[16]."', '".$val[17]."', '".$val[18]."', '".$val[19]."', '".$val[20]."', '".$val[21]."'";
			
			
			
			for ($k=0;$k<count($fieldtoimport);$k++){
				
				if (!empty($val[$k+3]) || $val[$k+3]=='0')
				{
					$val[$k+3]=str_replace('"', '', $val[$k+3]); //DH
					$val[$k+3]=str_replace("'", '', $val[$k+3]); //DH
					
					if ($fieldtoimport[$k]=="startdate" && ($val[$k+3]=='0' || $val[$k+3]=='')) continue;
					if ($fieldtoimport[$k]=="stopdate" && ($val[$k+3]=='0' || $val[$k+3]=='')) continue;
					
					if ($fieldtoimport[$k]=="buyrate" || $fieldtoimport[$k]=="connectcharge" || $fieldtoimport[$k]=="disconnectcharge" )
					{
						if($currencytype == "cent")
						{
							$val[$k+3] = $val[$k+3] / 100;
						}
						
					} 
					$FG_ADITION_SECOND_ADD_FIELDS .= ', '.$fieldtoimport[$k];
					
					if (is_numeric($val[$k+3])) {
						$FG_ADITION_SECOND_ADD_VALUE .= ", ".$val[$k+3]."";
					}else{
						$FG_ADITION_SECOND_ADD_VALUE .= ", '".$val[$k+3]."'";
					}
					
					if ($fieldtoimport[$k]=="startdate") $find_stardate = 1;
					if ($fieldtoimport[$k]=="stopdate") $find_stopdate = 1;
				}
			}
			 
			if ($find_stardate!=1){
				$begin_date = date("Y");	
				$end_date = date("-m-d H:i:s");					
				$FG_ADITION_SECOND_ADD_FIELDS .= ', startdate';
				$FG_ADITION_SECOND_ADD_VALUE .= ", '".$begin_date.$end_date."'";
			}
			 
			if ($find_stopdate!=1){
				$begin_date_plus = date("Y")+10;	
				$end_date = date("-m-d H:i:s");					
				$FG_ADITION_SECOND_ADD_FIELDS .= ', stopdate';
				$FG_ADITION_SECOND_ADD_VALUE .= ", '".$begin_date_plus.$end_date."'";
			}
				
			$TT_QUERY .= "INSERT INTO $sp".$FG_ADITION_SECOND_ADD_TABLE."$sp (".$FG_ADITION_SECOND_ADD_FIELDS.") values (".trim ($FG_ADITION_SECOND_ADD_VALUE).") ";
			
			$nb_to_import++;
		}
		
		if ($TT_QUERY!='' && strlen($TT_QUERY)>0 && ($nb_to_import==1) ){
			
			$nb_to_import=0;
			$result_query =  $DBHandle -> Execute($TT_QUERY);
			//echo "<br>TT_QUERY:".$TT_QUERY;
			//echo "<br>ERROR:".$DBHandle -> Error;
			//echo "<br>RESULT_QUERY:".$result_query;
			
			if ($result_query){ 
				$nb_imported = $nb_imported + 1;
			}else{$buffer_error.= $ligneoriginal.'<br/>';}
			$TT_QUERY='';
		}
		
	} // END WHILE EOF
	
	
	if ($TT_QUERY!='' && strlen($TT_QUERY)>0 && ($nb_to_import>0) ){
		$result_query = @ $DBHandle -> Execute($TT_QUERY);
		if ($result_query) $nb_imported = $nb_imported + $nb_to_import;				
	}
}

$Temps2 = time();
$Temps = $Temps2 - $Temps1;
//echo "<br>".$Temps2;
//echo "<br>Script Time :".$Temps."<br>";


$smarty->display('main.tpl');	 

?>


<style type="text/css">
<!--
div.myscroll {
	align: left;
	height: 100px;
	width: 600px;
	overflow: auto;
	border: 1px solid #ddd;
	background-color: #FFFFFF;
	padding: 5px;
}
-->
</style>

<script type="text/javascript">
<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function sendtoupload(form){
	
    document.forms["myform"].elements["task"].value = "upload";	
	document.forms[0].submit();
}

//-->
</script>



<?php
if ($status=="ok"){
	echo $CC_help_import_ratecard_confirm;
}else{
	echo $CC_help_import_ratecard_analyse;
}
?>
	
<?php  if ($status!="ok"){?> 
	
<center>
<?php echo gettext("The first line of your import is previewed below, please check to ensure that every is correct")?>.
</center>
	
<table align=center border="0" cellpadding="2" cellspacing="2" width="300">
	<tbody>
		<tr class="form_head">                  					
		  <td class="tableBody" style="padding: 2px;" align="center" width="50%"> 
			<strong> <span class="white_link"><?php echo gettext("FIELD")?> </span> </strong>
		  </td>
		  <td class="tableBody" style="padding: 2px;" align="center" width="50%"> 
			<strong> <span class="white_link"><?php echo gettext("VALUE")?> </span> </strong>
		  </td>
		</tr>
		<tr bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[1]?>'">  
		 <td class="tableBody" align="left" valign="top"><font color="red"><b><?php echo strtoupper($fixfield[0])?></b></font></td>
		 <td class="tableBody" align="center" valign="top"><font color="red"><b><?php echo $tariffplanval[1]?> (<?php echo $tariffplanval[0]?>)</b></font></td>
		</tr>
		<tr bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[2]?>'">  
		 <td class="tableBody" align="left" valign="top"><font color="red"><b><?php echo strtoupper($fixfield[1])?></b></font></td>
		 <td class="tableBody" align="center" valign="top"><font color="red"><b><?php echo $trunkval[1]?> (<?php echo $trunkval[0]?>)</b></font></td>
		</tr>
		<?php  for ($i=0;$i<count($field);$i++){ ?>
		<tr bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[($i+1)%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[($i+1)%2]?>'">  
		 <td class="tableBody" align="left" valign="top"><b><?php echo strtoupper($field[$i])?></b></td>
		 <td class="tableBody" align="center" valign="top"><?php echo $val[$i]?></td>
		</tr>
		<?php  } ?>
		<?php  for ($i=0;$i<count($fieldtoimport);$i++){ ?>
		<tr bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[($i)%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[($i)%2]?>'">  
		 <td class="tableBody" align="left" valign="top"><b><?php echo strtoupper($fieldtoimport[$i])?></b></td>
		 <td class="tableBody" align="center" valign="top"><?php echo $val[$i+3]?></td>
		</tr>
		<?php  } ?>
		
	</tbody>
</table>


<br></br>
<table width="95%" border="0" cellspacing="2" align="center" class="records">
	
	  <form name="myform" enctype="multipart/form-data" action="CC_ratecard_import_analyse.php" method="post" >
		<INPUT type="hidden" name="tariffplan" value="<?php echo $tariffplan?>">
		<INPUT type="hidden" name="trunk" value="<?php echo $trunk?>">
		<INPUT type="hidden" name="currencytype" value="<?php echo $currencytype?>">
		<INPUT type="hidden" name="search_sources" value="<?php echo $search_sources?>">
		
		<tr> 
		  <td colspan="2"> 
			<div align="center"><span class="textcomment"> 
			   <?php echo gettext("Please check if the datas above are correct.")?> <br><b><?php echo gettext("If Yes")?></b>, <?php echo gettext("you can continue the import. Otherwise you must fix your csv file!")?>
			  </span></div>
		  </td>
		</tr>                
		<tr> 
		  <td colspan="2"> 
			<p align="center">
			  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $my_max_file_size?>">
			  <input type="hidden" name="task" value="upload">
			  <input type="hidden" name="status" value="ok">
			  <input type="hidden" name="uploadedfile_name" value="<?php echo $new_filename?>">
			  <input type="hidden" name="uploadedfile_type" value="<?php echo $the_file_type?>">
			  <input type="submit"  value="Continue to Import the RateCard" onFocus=this.select() class="form_input_button" name="submit1" onClick="sendtoupload(this.form);">
			  <br>
			  &nbsp; </p>
		  </td>
		</tr>
		
		<tr> 
		  <td  class="bgcolor_014" colspan="2"><b> 
			<?php echo $translate[P34_9]?>
			</b></td>
		</tr>
	   
	  </form>
	</table>
	
	<?php }else{ ?>
	
	</br>
	<table width="75%" border="0" cellspacing="2" align="center" class="records">
	
		<TR> 
			<TD style="border-bottom: medium dotted #ED2525" align="center">&nbsp;</TD>
		</TR>
		<tr> 
		  <td colspan="2" class="bgcolor_015" style="padding-left: 5px; padding-right: 3px;" align=center>
			<div align="center"><span class="textcomment"> 
			  <br>
			  <?php 
			  $log = new Logger();
			  $log -> insertLog($_SESSION["admin_id"], 2, "RATE CARD IMPORTED", $nb_imported." Ratecards Imported Successfully", '', $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'],'');
			  $log = null;
			  ?>
			  <?php echo gettext("Success")?>, <?php echo $nb_imported?> &nbsp; <?php echo gettext("new rates have been imported")?>.<br>
			  </span></div>
			  <br><br>
			  
			  <?php  if (!empty($buffer_error)){ ?>
			  <center>
				 <b><i><?php echo gettext("Line that has not been inserted")?>!</i></b>
				 <div class="myscroll">
					  <span style="color: red;">
					  <?php echo $buffer_error?> 
					  </span>  
				 </div>
				</center>
				<br>
			 <?php  } ?>
			 
		  </td>
		</tr>
	</table>
	
	<?php }?>
	<br>
	
<?php
if($uploadedfile_name != "")
{
	unlink($uploadedfile_name);
}
$smarty->display('footer.tpl');

?>
