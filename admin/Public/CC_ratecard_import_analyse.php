<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/admin.smarty.php");

set_time_limit(0);

if (!has_rights(ACX_RATECARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

check_demo_mode();

getpost_ifset(array ('tariffplan', 'trunk', 'search_sources', 'task', 'status', 'currencytype', 'uploadedfile_name', 'uploadedfile_name'));

$tariffplanval = preg_split('/-:-/', $tariffplan);
if (!is_numeric($tariffplanval[0])) {
	echo gettext("No tariffplan defined !");
	exit ();
}

$trunkval = preg_split('/-:-/', $trunk);
if (!is_numeric($trunkval[0])) {
	echo gettext("No Trunk defined !");
	exit ();
}

if ($search_sources != 'nochange') {
	$fieldtoimport = preg_split("/\t/", $search_sources);
	$fieldtoimport_sql = str_replace("\t", ", ", $search_sources);
	$fieldtoimport_sql = trim($fieldtoimport_sql);
	if (strlen($fieldtoimport_sql) > 0)
		$fieldtoimport_sql = ', ' . $fieldtoimport_sql;
}

$fixfield[0] = "IDTariffplan (KEY)";
$fixfield[1] = "Outbound Trunk";

$field[0] = "Dialprefix";
$field[1] = "Destination Country";
$field[2] = "Rate Initial";

$FG_DEBUG = 0;

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$my_max_file_size = (int) MY_MAX_FILE_SIZE_IMPORT;

if ($FG_DEBUG == 1)
	echo "<br> Task :: $task";

if ($task == 'upload') {
	$the_file_name = $_FILES['the_file']['name'];
	$the_file_type = $_FILES['the_file']['type'];
	$the_file = $_FILES['the_file']['tmp_name'];

	if (count($_FILES) > 0) {
		$errortext = validate_upload($the_file, $the_file_type);
		if ($errortext != "" || $errortext != false) {
			echo $errortext;
			exit;
		}
		$new_filename = "/tmp/" . MDP(6) . ".csv";
		if (file_exists($new_filename)) {
			echo $_FILES["file"]["name"] . " already exists. ";
		} else {
			if (!move_uploaded_file($_FILES["the_file"]["tmp_name"], $new_filename)) {
				echo gettext("File Save Failed, FILE=" . $new_filename);
			}
		}
		$the_file = $new_filename;
	} else {
		$the_file_type = $uploadedfile_type;
		$the_file = $uploadedfile_name;
	}
	
	if ($FG_DEBUG == 1)
		echo "<br> THE_FILE:$the_file <br>THE_FILE_TYPE:$the_file_type";
	
	$fp = fopen($the_file, "r");
	if (!$fp) {
		echo gettext('Error: Failed to open the file.');
		exit ();
	}

	$nb_imported = 0;
	$nb_to_import = 0;
	$DBHandle = DbConnect();

	while (!feof($fp)) {

		//if ($nb_imported==1000) break;
		$ligneoriginal = fgets($fp, 4096); /* On se dplace d'une ligne */
		$ligneoriginal = trim($ligneoriginal);

		// strip out ' and " and, with the exception of dialprefix field,
		// substitute , for . to allow European style floats, eg: 0,1 == 0.1
		$ligne = str_replace(array ( '"', "'" ), '', $ligneoriginal);
		$val = preg_split('/[;,]/', $ligne);
		
		if ($status != "ok") {
			if ($currencytype == "cent") {
				$val[2] = $val[2] / 100;
			}
			break;
		}
		if (substr($ligne, 0, 1) != '#' && $val[2] != '' && strlen($val[2]) > 0)
		{
			$FG_ADITION_SECOND_ADD_TABLE = 'cc_ratecard';
			$FG_ADITION_SECOND_ADD_FIELDS = 'idtariffplan, id_trunk, dialprefix, destination, rateinitial'; //$fieldtoimport_sql
			$FG_ADITION_SECOND_ADD_FIELDS_PREFIX = 'prefix, destination';					
			if ($currencytype == "cent") {
				$val[2] = $val[2] / 100;
			}
            
			$FG_ADITION_SECOND_ADD_VALUE = "'" . $tariffplanval[0] . "', '" . $trunkval[0] . "', '" . $val[0] . "', '" . intval($val[0]) . "', '" . $val[2] . "'";
			
			for ($k = 0; $k < count($fieldtoimport); $k++) {
				if (!empty ($val[$k +3]) || $val[$k +3] == '0') {
					if ($fieldtoimport[$k] == "startdate" && ($val[$k +3] == '0' || $val[$k +3] == ''))
						continue;
					if ($fieldtoimport[$k] == "stopdate" && ($val[$k +3] == '0' || $val[$k +3] == ''))
						continue;

					if ($fieldtoimport[$k] == "buyrate" || $fieldtoimport[$k] == "connectcharge" || $fieldtoimport[$k] == "disconnectcharge") {
						if ($currencytype == "cent") {
							$val[$k +3] = $val[$k +3] / 100;
						}
					}
					$FG_ADITION_SECOND_ADD_FIELDS .= ', ' . $fieldtoimport[$k];

					if (is_numeric($val[$k +3])) {
						$FG_ADITION_SECOND_ADD_VALUE .= ", " . $val[$k +3] . "";
					} else {
						$FG_ADITION_SECOND_ADD_VALUE .= ", '" . trim($val[$k +3]) . "'";
					}

					if ($fieldtoimport[$k] == "startdate")
						$find_stardate = 1;
					if ($fieldtoimport[$k] == "stopdate")
						$find_stopdate = 1;
				}
			}

			if ($find_stardate != 1) {
				$begin_date = date("Y");
				$end_date = date("-m-d H:i:s");
				$FG_ADITION_SECOND_ADD_FIELDS .= ', startdate';
				$FG_ADITION_SECOND_ADD_VALUE .= ", '" . $begin_date . $end_date . "'";
			}

			if ($find_stopdate != 1) {
				$begin_date_plus = date("Y") + 10;
				$end_date = date("-m-d H:i:s");
				$FG_ADITION_SECOND_ADD_FIELDS .= ', stopdate';
				$FG_ADITION_SECOND_ADD_VALUE .= ", '" . $begin_date_plus . $end_date . "'";
			}
			if (intval($val[0]) > 0) {
				$FG_ADITION_SECOND_ADD_VALUE_PREFIX = "'" . intval($val[0]) . "', '" . $val[1] . "'";
				$TT_QUERY_PREFIX = "REPLACE INTO cc_prefix " . $FG_ADITION_SECOND_ADD_TABLE_PREFIX . " (" . $FG_ADITION_SECOND_ADD_FIELDS_PREFIX . ") values (" . $FG_ADITION_SECOND_ADD_VALUE_PREFIX . ") ";
                $DBHandle->Execute($TT_QUERY_PREFIX);
			}
			
			$TT_QUERY .= "INSERT INTO " . $FG_ADITION_SECOND_ADD_TABLE . " (" . $FG_ADITION_SECOND_ADD_FIELDS . ") values (" . $FG_ADITION_SECOND_ADD_VALUE . ") ";
			$nb_to_import++;
		}
		
		if ($TT_QUERY != '' && strlen($TT_QUERY) > 0 && ($nb_to_import == 1)) {
			$nb_to_import = 0;
			$result_query = $DBHandle->Execute($TT_QUERY);
			
			if ($result_query) {
				$nb_imported = $nb_imported +1;
			} else {
				$buffer_error .= $ligneoriginal . '<br/>';
			}
			$TT_QUERY = '';
		}

	} // END WHILE EOF

	if ($TT_QUERY != '' && strlen($TT_QUERY) > 0 && ($nb_to_import > 0)) {
		$result_query = @ $DBHandle->Execute($TT_QUERY);
		if ($result_query)
			$nb_imported = $nb_imported + $nb_to_import;
	}
}

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
function sendtoupload(form) {
    document.forms["myform"].elements["task"].value = "upload";	
	document.forms["myform"].submit();
}
//-->
</script>


<?php
if ($status=="ok") {
	echo $CC_help_import_ratecard_confirm;
} else {
	echo $CC_help_import_ratecard_analyse;
}
?>

<center>	
<?php  if ($status!="ok"){ ?> 

<?php echo gettext("The first line of your import is previewed below, please check to ensure that every is correct")?>.
	
<table align="center" border="0" cellpadding="2" cellspacing="2" width="300">
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
</table>


<br></br>
<table width="95%" border="0" cellspacing="2" align="center" class="records">
  <form name="myform" enctype="multipart/form-data" action="CC_ratecard_import_analyse.php" method="post" >
	<INPUT type="hidden" name="tariffplan" value="<?php echo $tariffplan?>">
	<INPUT type="hidden" name="trunk" value="<?php echo $trunk?>">
	<INPUT type="hidden" name="currencytype" value="<?php echo $currencytype?>">
	<INPUT type="hidden" name="search_sources" value="<?php echo $search_sources?>">
	<INPUT TYPE="hidden" VALUE="<?php echo $tag?>" NAME="tag">
	
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
	
<?php } else { ?>

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

<?php } ?>
<center>

<br>

<?php
if($uploadedfile_name != "") {
	unlink($uploadedfile_name);
}
$smarty->display('footer.tpl');

