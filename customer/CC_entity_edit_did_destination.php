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


include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("frontoffice_data/CC_var_did_destination.inc");
include ("lib/regular_express.inc");
include ("lib/customer.smarty.php");



if (! has_rights (ACX_DID)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}



$FG_DEBUG = 0;


if ( (!isset ($id) || (is_numeric($id)  == "")) && ( $form_action != "ask-add" && $form_action != "add") ){	
		exit ("<center><b>Error : ID <br> ($PHP_SELF)</b>");
	}

$VALID_SQL_REG_EXP = true;

$DBHandle  = DbConnect();


$instance_table = new Table($FG_TABLE_NAME, $FG_QUERY_EDITION);
if ($id!="" || !is_null($id)){
	$FG_EDITION_CLAUSE = str_replace("%id", "$id", $FG_EDITION_CLAUSE);
}


if ($form_action == "add-content"){

	if ($voipstation==true) $voip_prefix="99";
	
	$table_split = preg_split("/:/",$FG_TABLE_EDITION[$sub_action][1]);
	
	$instance_sub_table = new Table($table_split[0], $table_split[1].", ".$table_split[5]);
	$result_query = $instance_sub_table -> Add_table ($DBHandle, "'$voip_prefix".addslashes(trim($$table_split[1]))."', '".addslashes(trim($id))."'", null, null);	
	if (!$result_query ){
		
			//echo "<br><b>OOOOOOOOOO".$instance_sub_table -> errstr."</b><br>";				
			$findme   = 'duplicate';
			$pos_find = strpos($instance_sub_table -> errstr, $findme);	
					
			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.

			if ($pos_find === false) {		
				echo $instance_sub_table -> errstr;	
			}else{
				//echo $FG_TEXT_ERROR_DUPLICATION;
				$alarm_db_error_duplication = true;				
			}			
	}

}


if ($form_action == "del-content"){
	
	$table_split = preg_split("/:/",$FG_TABLE_EDITION[$sub_action][1]);
	
	$instance_sub_table = new Table($table_split[0], $table_split[1].", ".$table_split[5]);	
	$SPLIT_FG_DELETE_CLAUSE = $table_split[1]."='".trim($$table_split[1])."' AND ".$table_split[5]."='".trim($id)."'";	
	$instance_sub_table -> Delete_table ($DBHandle, $SPLIT_FG_DELETE_CLAUSE, $func_table = null);	
		
}


if ($form_action == "add"){
	
	for($i=0;$i<$FG_NB_TABLE_ADITION;$i++){ 
	
		$pos = strpos($FG_TABLE_ADITION[$i][1], ":");
		if (!$pos){
		
				$fields_name = $FG_TABLE_ADITION[$i][1];
				$regexp = $FG_TABLE_ADITION[$i][5];
				
				//echo "--> $regexp - ".$FG_TABLE_ADITION[$i][12]." ---> ".$$fields_name."<br>";

				if (is_numeric($regexp) && !($FG_TABLE_ADITION[$i][12]=="no" && $$fields_name=="") ){
					$fit_expression[$i] = preg_match( "/" . $regular[$regexp][0] . "/" , $$fields_name);
					if ($FG_DEBUG == 1)  echo "<br>->  ".$regular[$regexp][0]." , ".$$fields_name;
					if (!$fit_expression[$i]){
						$VALID_SQL_REG_EXP = false;
						$form_action="ask-add";						
					}
				}
				
				if ($FG_DEBUG == 1) echo "<br>$fields_name : ".$$fields_name;
				if (!is_null($$fields_name) && ($$fields_name!="") && ($FG_TABLE_ADITION[$i][4]!="disabled") ){
					if ($i>0) $param_add_fields .= ", ";
					
					$param_add_fields .= "$fields_name";
					if ($i>0) $param_add_value .= ", ";
					$param_add_value .= "'".addslashes(trim($$fields_name))."'";
				}
				
		}
	}
	
	if (!is_null($FG_QUERY_ADITION_HIDDEN_FIELDS) && $FG_QUERY_ADITION_HIDDEN_FIELDS!=""){
		
		if ($i>0) $param_add_fields .= ", ";		
		$param_add_fields .= $FG_QUERY_ADITION_HIDDEN_FIELDS;
		if ($i>0) $param_add_value .= ", ";
		$param_add_value  .= $FG_QUERY_ADITION_HIDDEN_VALUE;
		
	}
	
	
	if ($FG_DEBUG == 1)  echo "<br><hr> $param_add_fields";	
	if ($FG_DEBUG == 1)  echo "<br><hr> $param_add_value";	
	
	$FG_TABLE_ID = "id";
	if ($VALID_SQL_REG_EXP) $result_query=$instance_table -> Add_table ($DBHandle, $param_add_value, $param_add_fields, null, $FG_TABLE_ID);
	if (!$result_query ){
		
			//--echo "<br><b>".$instance_table -> errstr."</b><br>";				
			$findme   = 'duplicate';
			$pos_find = strpos($instance_sub_table -> errstr, $findme);	
					
			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.

			if ($pos_find === false) {		
				//-- echo $instance_sub_table -> errstr;	
			}else{
				//echo $FG_TEXT_ERROR_DUPLICATION;
				$alarm_db_error_duplication = true;				
			}
			
	}else{
			
			if ($FG_ADITION_GO_EDITION == "yes"){
				$form_action="ask-edit";				
				$FG_ADITION_GO_EDITION = "yes-done";
			}
			$id = $result_query;
			
							

	}
	
	if ( ($VALID_SQL_REG_EXP) && (isset($FG_GO_LINK_AFTER_ACTION))){
		Header ("Location: $FG_GO_LINK_AFTER_ACTION".$id);
	}
}//end if add



if ($form_action == "edit"){

	for($i=0;$i<$FG_NB_TABLE_EDITION;$i++){ 
		
		$pos = strpos($FG_TABLE_EDITION[$i][1], ":");		
		if (!$pos){
		
				$fields_name = $FG_TABLE_EDITION[$i][1];								

				$regexp = $FG_TABLE_EDITION[$i][5];
				
				if (is_numeric($regexp) && !($FG_TABLE_ADITION[$i][12]=="no" && $$fields_name=="") ){
					$fit_expression[$i] = preg_match( "/" . $regular[$regexp][0] . "/" , $$fields_name);
					if ($FG_DEBUG == 1)  echo "<br>->  ".$regular[$regexp][0]." , ".$$fields_name;
					if (!$fit_expression[$i]){
						$VALID_SQL_REG_EXP = false;
						$form_action="ask-edit";						
					}
				}
				
				if ($FG_DEBUG == 1) echo "<br>$fields_name : ".$$fields_name;
				if ($i>0) $param_update .= ", ";				
				$param_update .= "$fields_name = '".addslashes(trim($$fields_name))."'";

		}
		
	}
	if ($FG_DEBUG == 1)  echo "<br><hr> $param_update";	
	
	if ($VALID_SQL_REG_EXP) $instance_table -> Update_table ($DBHandle, $param_update, $FG_EDITION_CLAUSE, $func_table = null);
		
	if ( ($VALID_SQL_REG_EXP) && (isset($FG_GO_LINK_AFTER_ACTION))){
		Header ("Location: $FG_GO_LINK_AFTER_ACTION".$id);
	}
	
}


if ($form_action == "delete"){
	
	
	$res_delete = $instance_table -> Delete_table ($DBHandle, $FG_EDITION_CLAUSE, $func_table = null);
	if (!$res_delete){  echo gettext("error deletion");
	}else{
		  
		  
	}
	
	$FG_INTRO_TEXT_DELETION = str_replace("%id", "$id", $FG_INTRO_TEXT_DELETION);
	$FG_INTRO_TEXT_DELETION = str_replace("%table", "$FG_TABLE_NAME", $FG_INTRO_TEXT_DELETION);
	
	if (isset($FG_GO_LINK_AFTER_ACTION)){
		Header ("Location: $FG_GO_LINK_AFTER_ACTION_DELETE".$id);
	}
	
}

if ( $form_action == "edit" || $form_action == "ask-delete" || $form_action == "ask-edit" || $form_action == "add-content" || $form_action == "del-content" ){
	
	if ($FG_DEBUG >= 2) { echo "FG_EDITION_CLAUSE:$FG_EDITION_CLAUSE"; }
	$list = $instance_table -> Get_list ($DBHandle, $FG_EDITION_CLAUSE, null, null, null, null, 1, 0);
	if ($FG_DEBUG >= 2) { echo "<br>"; print_r ($list);}
	
}
?>

<?php
$smarty->display( 'main.tpl');
?>



<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function sendto(action, record, field_inst, instance){
  document.myForm.form_action.value = action;
  document.myForm.sub_action.value = record;
  document.myForm.elements[field_inst].value = instance;
  myForm.submit();
}

function sendtolittle(direction){
  myForm.action=direction;
  myForm.submit();

}

//-->
</script>

          
<?php
	echo $CC_help_edit_did;
?>
	  <?php if ($form_action=="ask-edit" || $form_action=="edit" || $form_action == "add-content" || $form_action == "del-content"){ ?>
	  
      <blockquote>
        <div align="center"><b>
		  <?php  if ($FG_ADITION_GO_EDITION == "yes-done") echo '<font color="#FF0000">'.$FG_ADITION_GO_EDITION_MESSAGE.'</font><br><br>'; ?>
          <?php  if ($alarm_db_error_duplication){ 
		  			echo '<font color="#FF0000">'.$FG_TEXT_ERROR_DUPLICATION.'</font>';
			 }else{	
			 		//echo $FG_INTRO_TEXT_EDITION;
			 }
		  ?>
          </b></div>
</blockquote>      

    		
			<TABLE width="95%" border=0 align="center" cellPadding=2 cellSpacing=2>
                <FORM action=<?php echo $PHP_SELF?> method=post name="myForm"> 
                  <INPUT type="hidden" name="id" value="<?php echo $id?>">
                  <INPUT type="hidden" name="form_action" value="edit">
				  <INPUT type="hidden" name="sub_action" value="">
                  <INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
				  <INPUT type="hidden" name="stitle" value="<?php echo $stitle?>">	
				  <?php
						if (!is_null($FG_QUERY_ADITION_HIDDEN_FIELDS) && $FG_QUERY_ADITION_HIDDEN_FIELDS!=""){
							
							$split_hidden_fields = preg_split("/,/",trim($FG_QUERY_ADITION_HIDDEN_FIELDS));
							$split_hidden_fields_value = preg_split("/,/",trim($FG_QUERY_ADITION_HIDDEN_VALUE));

							for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields);$cur_hidden++){
									echo "<INPUT class=\"form_enter\" type=\"hidden\" name=\"".trim($split_hidden_fields[$cur_hidden])."\" value=\"".trim($split_hidden_fields_value[$cur_hidden])."\">\n";
							}
							
						}
					?>			  
                  <TBODY>
                    <?php for($i=0;$i<$FG_NB_TABLE_EDITION;$i++){ 
						$pos = strpos($FG_TABLE_EDITION[$i][1], ":");
						if (!$pos){

					?>
                    <TR> 
					
                      <TD width="%25" valign="top" class="form_head"><strong><?php echo $FG_TABLE_EDITION[$i][0]?></strong></TD>
					  
                      <TD width="%75" valign="top" class="tableBodyRight" bgcolor="#CCCCCC">
                        <?php 
								if ($FG_DEBUG == 1) print($FG_TABLE_EDITION[$i][3]);
						  		if (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("INPUT"))
								{								
						  ?>
                        <INPUT class="form_input_text" name=<?php echo $FG_TABLE_EDITION[$i][1]?>  <?php echo $FG_TABLE_EDITION[$i][4]?> value="<?php if($VALID_SQL_REG_EXP){ echo stripslashes($list[0][$i]); }else{ echo $$FG_TABLE_ADITION[$i][1]; }?>"> 
                        <?php 
						  		}elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("TEXTAREA"))
								{
						  ?>
                        <textarea class="form_input_textarea" name=<?php echo $FG_TABLE_EDITION[$i][1]?>  <?php echo $FG_TABLE_EDITION[$i][4]?>><?php if($VALID_SQL_REG_EXP){ echo stripslashes($list[0][$i]); }else{ echo $$FG_TABLE_ADITION[$i][1]; }?></textarea> 
                        <?php 	
								}elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("SELECT"))
								{
								
										if ($FG_DEBUG == 1) 
										{ echo "<br> TYPE DE SELECT :".$FG_TABLE_EDITION[$i][7];
										}
											
										if (strtoupper ($FG_TABLE_EDITION[$i][7])==strtoupper ("SQL"))
										{
																					
												$instance_sub_table = new Table($FG_TABLE_EDITION[$i][8], $FG_TABLE_EDITION[$i][9]);
												$select_list = $instance_sub_table -> Get_list ($DBHandle, $FG_TABLE_EDITION[$i][10], null, null, null, null, null, null);
												
												if ($FG_DEBUG >= 2) { echo "<br>"; print_r($select_list);}
											
										 }elseif (strtoupper ($FG_TABLE_EDITION[$i][7])==strtoupper ("LIST"))
										 {
												
												$select_list = $FG_TABLE_EDITION[$i][11];
												if ($FG_DEBUG >= 2) { echo "<br>"; print_r($select_list);}
												//$select_list_nb = count($select_list);
										 }
						  if ($FG_DEBUG >= 2) print_r ($list);			 
						  if ($FG_DEBUG >= 2) echo "<br>#$i<br>::>".$VALID_SQL_REG_EXP;
						  if ($FG_DEBUG >= 2) echo "<br><br>::>".$list[0][$i];
						  if ($FG_DEBUG >= 2) echo "<br><br>::>".$$FG_TABLE_ADITION[$i][1];											
						  ?>
                        <SELECT name=<?php echo $FG_TABLE_EDITION[$i][1]?> class="form_input_select">
                          <?php
										 if (count($select_list)>0)
										 {
												 $select_number=0;
												 
												 foreach ($select_list as $select_recordset){ 
													 $select_number++;
						  
							  ?>
                          <OPTION  value=<?php echo $select_recordset[1]?> <?php 
						  
						  
						  if($VALID_SQL_REG_EXP){ if (strcmp($list[0][$i],$select_recordset[1])==0){ echo "selected"; } }else{ if (strcmp($$FG_TABLE_ADITION[$i][1],$select_recordset[1])==0){ echo "selected"; } }
						   ?>>
						   <?php 						   
						   		if ($FG_TABLE_EDITION[$i][12] != ""){
				
							   		$value_display = $FG_TABLE_EDITION[$i][12];
									$nb_recor_k = count($select_recordset);
																		
									for ($k=1;$k<=$nb_recor_k;$k++){
														$value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
									}
									
								}else{
									$value_display = $select_recordset[0];								
								}
							?>
                          <?php echo $value_display ?>
                          </OPTION>
                          <?php 
												 }// END_FOREACH
										  }else{
													echo gettext("No data found !!!");
										  }//END_IF
							  ?>
                        </SELECT>
                        <?php     }elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("RADIOBUTTON")){
								
										 
												$radio_table = preg_split("/,/",trim($FG_TABLE_EDITION[$i][9]));
												
												foreach ($radio_table as $radio_instance){
													$radio_composant = preg_split("/:/",$radio_instance);																								
													echo $radio_composant[0];
													echo ' <input class="form_enter" type="radio" name="'.$FG_TABLE_EDITION[$i][1].'" value="'.$radio_composant[1].'" ';
													
													if($VALID_SQL_REG_EXP){ 
														$know_is_checked = stripslashes($list[0][$i]);
													}else{ 
														$know_is_checked = $$FG_TABLE_EDITION[$i][1];
													}
													
													if ($know_is_checked==$radio_composant[1]){
														echo "checked";
													}
													echo ">";
													
												}								
						
                               }//END_IF (RADIOBUTTON)  
							   
						  ?>
                        <span class="liens"> 
                        <?php 						
							if (!$fit_expression[$i]  &&  isset($fit_expression[$i]) ){
							
								echo "<br>".$FG_TABLE_EDITION[$i][6]." - ".$regular[$FG_TABLE_EDITION[$i][5]][1];								
							}
							   
						  ?>
                        </span><br>
                        <?php  echo $FG_TABLE_COMMENT[$i];?>
                        &nbsp; </TD>
                    </TR>
                    <?php 					
							}else{
								
							  if (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("SELECT"))
							  {
								//"app_virtuel_content:content_id:content:id IN (select content_id from app_virtuel_content where app_virtuel_id = %id)",
								$table_split = preg_split("/:/",$FG_TABLE_EDITION[$i][1]);								
						
					?>
                    <TR> 
					  <!-- ******************** PARTIE EXTERN : SELECT ***************** -->
                      <TD width="122" class="form_head"><?php echo $FG_TABLE_EDITION[$i][0]?></TD>
					  
                     <TD align="center" valign="top" class="tableBodyRight" bgcolor="#CCCCCC"><br>
                         
						 <!-- Table with list instance already inserted -->
                        <table width="300" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EDF3FF">
                          <TR bgcolor="#ffffff"> 
                            <TD height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px" class="form_head"> 
                              <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                <TBODY>
                                  <TR> 
                                    <TD class="form_head"><?php echo $FG_TABLE_EDITION[$i][0]?> LIST </TD>                                    
                                  </TR>
                                </TBODY>
                              </TABLE></TD>
                          </TR>
                          <TR> 
                            <TD> <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                <TBODY>
                                  <TR> 
                                    <TD bgColor=#e1e1e1 colSpan=<?php echo $FG_TOTAL_TABLE_COL?> height=1><IMG height=1 src="../Images/clear.gif" width=1></TD>
                                  </TR>
                                  <?php 
// "app_virtuel_content:content_id:content:label, id:id IN (select content_id from app_virtuel_content where app_virtuel_id = %id)",	
			$SPLIT_CLAUSE = str_replace("%id", "$id", $table_split[4]);
			$SPLIT_CLAUSE2 = str_replace("%id", "$id", $table_split[12]);
		

			$instance_sub_table = new Table($table_split[2], $table_split[3]);
			$split_select_list = $instance_sub_table -> Get_list ($DBHandle, $SPLIT_CLAUSE, null, null, null, null, null, null);			
			
	if (!is_array($split_select_list)){	
		$num=0;
	}else{	
		$num = count($split_select_list);
	}
	
	
	
	if($num>0)
	{	
	for($j=0;$j<$num;$j++)
	  {
			if (is_numeric($table_split[7])){
																	
					$instance_sub_sub_table = new Table($table_split[8], $table_split[9]);
					$SUB_TABLE_SPLIT_CLAUSE = str_replace("%1", $split_select_list[$j][$table_split[7]], $table_split[11] );
					$sub_table_split_select_list = $instance_sub_sub_table -> Get_list ($DBHandle, $SUB_TABLE_SPLIT_CLAUSE, null, null, null, null, null, null);
					$split_select_list[$j][$table_split[7]] = $sub_table_split_select_list[0][0];
			}	
			
	?>
                                  <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>'"> 
                                    <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody> 
                                      <font face="Verdana" size="2">
                                      <b><?php echo $split_select_list[$j][$table_split[7]]?></b> : <?php echo $split_select_list[$j][0]?>
                                      </font> </TD>
                                    <TD align="center" vAlign=top class=tableBodyRight> 
                                      <input onClick="sendto('del-content','<?php echo $i?>','<?php echo $table_split[1]?>','<?php echo $split_select_list[$j][1]?>');" alt="Remove this <?php echo $FG_TABLE_EDITION[$i][0]?>" border=0 height=11 hspace=2 id=submit33 name=submit33 src="../Images/icon-del.gif" type=image width=33 value="add-split"> 
                                    </TD>
                                  </TR>
                                  <?php 
	  }//end_for
	}else{
			?>
                                  <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>'"> 
                                    <TD colspan="2" align="<?php echo $FG_TABLE_COL[$i][3]?>" vAlign=top class=tableBody> 
                                      <div align="center" class="liens">No <?php echo $FG_TABLE_EDITION[$i][0]?></div></TD>
                                  </TR>
                                  <?php 
	}
	?>
                                  <TR> 
                                    <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1 src="../Images/clear.gif" width=1></TD>
                                  </TR>
                                </TBODY>
                              </TABLE></td>
                          </tr>
                          <TR bgcolor="#ffffff"> 
                            <TD bgcolor="#AAAAAA"  height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
                              <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                <TBODY>
                                  <TR> 
                                    <TD height="4" align="right"></TD>
                                </TBODY>
                              </TABLE></TD>
                          </TR>
                        </table><br>
</TD>
                    </TR>
					<?php
						$split_select_list = $instance_sub_table -> Get_list ($DBHandle, $SPLIT_CLAUSE2, null, null, null, null, null, null);
						if (count($split_select_list)>0){
					?>
                    <TR>
					  <!-- *******************   Select to ADD new instances  ****************************** -->					  
                      <TD class="form_head">&nbsp;</TD>
                      <TD align="center" valign="top" bgcolor="#CCCCCC" class="tableBodyRight"><br>
                        <TABLE width="300" height=50 border=0 align="center" cellPadding=0 cellSpacing=0>
<TBODY>
                            <TR> 
                              <TD bgColor=#7f99cc colSpan=3 height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 5px" class="form_head">
                                <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                  <TBODY>
                                    <TR> 
                                      <TD class="form_head"><?php gettext("Add a new ");?> <?php echo $FG_TABLE_EDITION[$i][0]?></TD>
                                    </TR>
                                  </TBODY>
                                </TABLE></TD>
                            </TR>
                            <TR> 
                              <TD class="form_head"> <IMG height=1 src="../Images/clear.gif" width=1> 
                              </TD>
                              <TD bgColor=#F3F3F3 style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px"> 
                                
								<TABLE width="97%" border=0 align="center" cellPadding=0 cellSpacing=0>
								
                                  <TBODY>
                                    <TR> 
                                      <TD width="122" class="tableBody"><?php echo $FG_TABLE_EDITION[$i][0]?></TD>
                                      <TD width="516"><div align="center"> 
									  
                                          <SELECT name=<?php echo $table_split[1]?> class="form_input_select">
                                            <?php
										 
										 
										 if (count($split_select_list)>0)
										 {
												 $select_number=0;
						 
												 foreach ($split_select_list as $select_recordset){ 
													 $select_number++;
													 
													 if ($table_split[6]!="" && !is_null($table_split[6])){
													 
													 		if (is_numeric($table_split[7])){
																
																$instance_sub_sub_table = new Table($table_split[8], $table_split[9]);
																//echo "*******" . $table_split[7]."\n<br>";
																//echo "*******" . $select_recordset[$table_split[7]]."\n<br>";
																//print_r($select_recordset);
																
																
																$SUB_TABLE_SPLIT_CLAUSE = str_replace("%1", $select_recordset[$table_split[7]], $table_split[11] );
																$sub_table_split_select_list = $instance_sub_sub_table -> Get_list ($DBHandle, $SUB_TABLE_SPLIT_CLAUSE, null, null, null, null, null, null);
																//print_r($sub_table_split_select_list);
																
																$select_recordset[$table_split[7]] = $sub_table_split_select_list[0][0];
															}													 
													 
															 $value_display = $table_split[6];
															 $nb_recor_k = count($select_recordset);
															 for ($k=1;$k<=$nb_recor_k;$k++){
																	$value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
															 }
													 }else{													 	
															$value_display  = $select_recordset[0];
													 }
										
											  ?>
                                            <OPTION  value=<?php echo $select_recordset[1]?>> 
                                            <?php echo $value_display?>
                                            </OPTION>
                                            <?php 
												 }// END_FOREACH
										  }else{
													echo gettext("No data found !!!");
										  }//END_IF				
							  ?>
                                          </SELECT>
                                        </div></TD>
                                    </TR>
                                    <TR> 
                                      <TD width="122">&nbsp;</TD>
                                      <TD width="316"></TD>
                                    </TR>
                                    <TR> 
                                      <TD colspan="2" align="center">
									  	
										<input onClick="sendto('add-content','<?php echo $i?>');" alt="add new a <?php echo $FG_TABLE_EDITION[$i][0]?>" border=0 height=20 hspace=2 id=submit32 name=submit3 src="../Images/btn_Add_94x20.gif" type=image width=94 value="add-split">
                                      </TD>
                                    </TR>
                                    <TR> 
                                      <TD colSpan=2 height=4></TD>
                                    </TR>
                                    <TR> 
                                      <TD colSpan=2> <div align="right"></div></TD>
                                    </TR>
                                  </TBODY>
								 
                                </TABLE></TD>
                              <TD class="form_head"><IMG height=1 src="../Images/clear.gif" width=1> 
                              </TD>
                            </TR>
                            <TR> 
                              <TD colSpan=3 class="form_head"><IMG height=1 src="../Images/clear.gif" width=1></TD>
                            </TR>
                          </TBODY>
                        </TABLE>
                        <br>
                        <br>
                        <hr size="0"></TD>
                    </TR>
					<?php } ?>
					<?php  }elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("INSERT")){
								$table_split = spreg_plit("/:/",$FG_TABLE_EDITION[$i][1]);
					?>
                    <TR> 
					  <!-- ******************** PARTIE EXTERN : INSERT ***************** -->
					  
                      <TD width="122" class="form_head"><?php echo $FG_TABLE_EDITION[$i][0]?></TD>					  
                      <TD align="center" valign="top" class="tableBodyRight" bgcolor="#CCCCCC"><br>
                         
						 <!-- Table with list instance already inserted -->
                        <table width="300" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EDF3FF">
                          <TR bgcolor="#ffffff"> 
                            <TD height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px" class="form_head"> 
                              <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                <TBODY>
                                  <TR> 
                                    <TD class="form_head"><?php echo $FG_TABLE_EDITION[$i][0]?>&nbsp;<?php echo gettext("LIST")?>  </TD>                                    
                                  </TR>
                                </TBODY>
                              </TABLE></TD>
                          </TR>
                          <TR> 
                            <TD> <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                <TBODY>
                                  <TR> 
                                    <TD bgColor=#e1e1e1 colSpan=<?php echo $FG_TOTAL_TABLE_COL?> height=1><IMG height=1 src="../Images/clear.gif" width=1></TD>
                                  </TR>
                                  <?php 
			$SPLIT_CLAUSE = str_replace("%id", "$id", $table_split[4]);
			$instance_sub_table = new Table($table_split[2], $table_split[3]);
			$split_select_list = $instance_sub_table -> Get_list ($DBHandle, $SPLIT_CLAUSE, null, null, null, null, null, null);			
			
	if (!is_array($split_select_list)){	
		$num=0;
	}else{	
		$num = count($split_select_list);
	}
	
	
	
	if($num>0)
	{	
	for($j=0;$j<$num;$j++)
	  {
	  
			if (is_numeric($table_split[7])){
																	
					/*$instance_sub_sub_table = new Table($table_split[8], $table_split[9]);
					//echo "*******" . $table_split[7]."\n<br>";
					//echo "*******" . $select_recordset[$table_split[7]]."\n<br>";
					//print_r($select_recordset);					
					
					$SUB_TABLE_SPLIT_CLAUSE = str_replace("%1", $split_select_list[$j][$table_split[7]], $table_split[11] );
					$sub_table_split_select_list = $instance_sub_sub_table -> Get_list ($DBHandle, $SUB_TABLE_SPLIT_CLAUSE, null, null, null, null, null, null);
					//print_r($sub_table_split_select_list);
					
					$split_select_list[$j][$table_split[7]] = $sub_table_split_select_list[0][0];*/
			}	
			
	?>
                                  <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>'"> 
                                    <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody> 
                                      <font face="Verdana" size="2">
                                      <b><?php echo $split_select_list[$j][$table_split[7]]?></b> : <?php echo $split_select_list[$j][0]?>
                                      </font> </TD>
                                    <TD align="center" vAlign=top class=tableBodyRight> 
                                      <input onClick="sendto('del-content','<?php echo $i?>','<?php echo $table_split[1]?>','<?php echo $split_select_list[$j][1]?>');" alt="Remove this <?php echo $FG_TABLE_EDITION[$i][0]?>" border=0 height=11 hspace=2 id=submit33 name=submit33 src="../Images/icon-del.gif" type=image width=33 value="add-split"> 
                                    </TD>
                                  </TR>
                                  <?php 
	  }//end_for
	}else{
			?>
                                  <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$j%2]?>'"> 
                                    <TD colspan="2" align="<?php echo $FG_TABLE_COL[$i][3]?>" vAlign=top class=tableBody> 
                                      <div align="center" class="liens"><?php echo gettext("No")?> <?php echo $FG_TABLE_EDITION[$i][0]?></div></TD>
                                  </TR>
                                  <?php 
	}
	?>
                                  <TR> 
                                    <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1 src="../Images/clear.gif" width=1></TD>
                                  </TR>
                                </TBODY>
                              </TABLE></td>
                          </tr>
                          <TR bgcolor="#ffffff"> 
                            <TD bgcolor="#AAAAAA"  height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
                              <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                <TBODY>
                                  <TR> 
                                    <TD height="4" align="right"></TD>
                                </TBODY>
                              </TABLE></TD>
                          </TR>
                        </table><br>
</TD>
                    </TR>
                    <TR>
					  <!-- *******************   Select to ADD new instances  ****************************** -->					  
                      <TD class="form_head">&nbsp;</TD>
                      <TD align="center" valign="top" bgcolor="#CCCCCC" class="tableBodyRight"><br>
                        <TABLE width="300" height=50 border=0 align="center" cellPadding=0 cellSpacing=0>
<TBODY>
                            <TR> 
                              <TD bgColor=#7f99cc colSpan=3 height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 5px" class="form_head">
                                <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                  <TBODY>
                                    <TR> 
                                      <TD class="form_head"><?php gettext("Add a new");?>&nbsp; <?php echo $FG_TABLE_EDITION[$i][0]?></TD>
                                    </TR>
                                  </TBODY>
                                </TABLE></TD>
                            </TR>
                            <TR> 
                              <TD class="form_head"> <IMG height=1 src="../Images/clear.gif" width=1> 
                              </TD>
                              <TD bgColor=#F3F3F3 style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px"> 
                                
								<TABLE width="97%" border=0 align="center" cellPadding=0 cellSpacing=0>
								
                                  <TBODY>
                                    <TR> 
                                      <TD width="122" class="tableBody"></TD>
                                      <TD width="516"><div align="center"> 				
									      <input type="checkbox" name="voipstation" value="true">
                                         Voip Station
                                        </div></TD>
                                    </TR>
									<TR> 
                                      <TD width="122" class="tableBody"><?php echo $FG_TABLE_EDITION[$i][0]?></TD>
                                      <TD width="516"><div align="center"> 				
									      <INPUT TYPE="TEXT" name=<?php echo $table_split[1]?> class="form_input_text"  size="20" maxlength="20">
                                         
                                        </div></TD>
                                    </TR>
                                    <TR> 
                                      <TD width="122">&nbsp;</TD>
                                      <TD width="316"></TD>
                                    </TR>
                                    <TR> 
                                      <TD colspan="2" align="center">
									  	
										<input onClick="sendto('add-content','<?php echo $i?>');" alt="add new a <?php echo $FG_TABLE_EDITION[$i][0]?>" border=0 height=20 hspace=2 id=submit32 name=submit3 src="../Images/btn_Add_94x20.gif" type=image width=94 value="add-split">
                                      </TD>
                                    </TR>
                                    <TR> 
                                      <TD colSpan=2 height=4></TD>
                                    </TR>
                                    <TR> 
                                      <TD colSpan=2> <div align="right"></div></TD>
                                    </TR>
                                  </TBODY>
								 
                                </TABLE></TD>
                              <TD class="form_head"><IMG height=1 src="../Images/clear.gif" width=1> 
                              </TD>
                            </TR>
                            <TR> 
                              <TD colSpan=3 class="form_head"><IMG height=1 src="../Images/clear.gif" width=1></TD>
                            </TR>
                          </TBODY>
                        </TABLE>
                        <br>
                        <br>
                        <hr size="0"> </TD>
                    </TR>
					
					<?php  }elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("CHECKBOX")){
							
							$table_split = preg_split("/:/",$FG_TABLE_EDITION[$i][1]);
					?>
					<TR> 
					 <!-- ******************** PARTIE EXTERN : CHECKBOX ***************** -->
                     <TD width="122" class="form_head"><?php echo $FG_TABLE_EDITION[$i][0]?></TD>					  
                     <TD align="center" valign="top" class="tableBodyRight" bgcolor="#CCCCCC">
					    <br>
	<?php 
	$SPLIT_CLAUSE = str_replace("%id", "$id", $table_split[4]);
	


	$instance_sub_table = new Table($table_split[2], $table_split[3]);
	$split_select_list = $instance_sub_table -> Get_list ($DBHandle, $SPLIT_CLAUSE, null, null, null, null, null, null);			

	if (!is_array($split_select_list)){	
		$num=0;
	}else{	
		$num = count($split_select_list);
	}
	

	 ////////////////////////////////////////////////////////////////////////////////////////////////////////

	 
	 $split_select_list_tariff = $instance_sub_table -> Get_list ($DBHandle, null, null, null, null, null, null, null);
										 
	 if (count($split_select_list_tariff)>0)
	 {
			 $select_number=0;
			 

			
			  ?>				
			<TABLE width="400" height=50 border=0 align="center" cellPadding=0 cellSpacing=0>
			<TBODY>
                            <TR> 
                              <TD bgColor=#7f99cc colSpan=3 height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 5px" class="form_head">
                                <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                                  <TBODY>
                                    <TR> 
                                      <TD class="form_head"><?php echo gettext("LIST")?> &nbsp; <?php echo $FG_TABLE_EDITION[$i][0]?></TD>                                      
                                    </TR>
                                  </TBODY>
                                </TABLE></TD>
                            </TR>
                            <TR> 
                              <TD class="form_head"> <IMG height=1 src="../Images/clear.gif" width=1> 
                              </TD>
                              <TD bgColor=#F3F3F3 style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px"> 
								<TABLE width="97%" border=0 align="center" cellPadding=0 cellSpacing=0>
                                  <TBODY>
                                    
 <?php 
 	foreach ($split_select_list_tariff as $select_recordset){ 
				 $select_number++;
				 
				 if ($table_split[6]!="" && !is_null($table_split[6])){
				 
						if (is_numeric($table_split[7])){
							
							$instance_sub_sub_table = new Table($table_split[8], $table_split[9]);
							$SUB_TABLE_SPLIT_CLAUSE = str_replace("%1", $select_recordset[$table_split[7]], $table_split[11] );
							$sub_table_split_select_list_tariff = $instance_sub_sub_table -> Get_list ($DBHandle, $SUB_TABLE_SPLIT_CLAUSE, null, null, null, null, null, null);
							
							$select_recordset[$table_split[7]] = $sub_table_split_select_list_tariff[0][0];
						}													 
				 
						 $value_display = $table_split[6];
						 $nb_recor_k = count($select_recordset);
						 for ($k=1;$k<=$nb_recor_k;$k++){
								$value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
						 }
				 }else{													 	
						$value_display  = $select_recordset[0];
				 }
				 
				 
				 $checked_tariff=false;
				 if($num>0)
				 {	
					for($j=0;$j<$num;$j++)
					{
						if ($select_recordset[1]==$split_select_list[$j][1]) $checked_tariff=true;
					}					
				 }
					
?>
			<TR> 
				<TD class="tableBody"><input type="checkbox" name="<?php echo $table_split[0]?>[]" value="<?php echo $select_recordset[1]?>" <?php if ($checked_tariff) echo"checked";?>></TD>
				<TD>&nbsp; <?php echo $value_display?></TD>
			</TR>
<?php }// END_FOREACH?>
                                    <TR><TD width="30">&nbsp;</TD><TD width="316"></TD></TR>                                    
                                    <TR><TD colSpan=2 height=4></TD></TR>                                    
                                  </TBODY>
								 
                                </TABLE></TD>
                              <TD class="form_head"><IMG height=1 src="../Images/clear.gif" width=1> 
                              </TD>
                            </TR>
                            <TR> 
                              <TD colSpan=3 class="form_head"><IMG height=1 src="../Images/clear.gif" width=1></TD>
                            </TR>
                          </TBODY>
                        </TABLE>
			  
				
			  <?php 	       
	  }else{
				echo gettext("No data found !!!");
	  }?>
						
						
						
					 </TD>
                    </TR>
                    <?php   	  }// end if if (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("SELECT"))
							}// end if pos
						}//END_FOR ?>
                    
					<TR>                       
                      <TD colSpan=2  style="border-bottom: medium dotted #667766">&nbsp; </TD>
                    </TR>
					<TR> 
					  <TD colspan=2>
						<table>
							<tr>
								<td width="%95"  class="tableBodyRight"><?php echo $FG_BUTTON_EDITION_BOTTOM_TEXT?></td>
								<td width="%5" align="right"><input onClick="sendto('edit');"  border=0 hspace=2 id=submit3 name=submit32 src="<?php echo $FG_BUTTON_EDITION_SRC?>" type=image value="add-split"></td>
							</tr>
						</table>
                      </TD>
                    </TR>                    
                    <TR> 
                      <TD colSpan=2 height=4></TD>
                    </TR>
                    
					
                  </TBODY>
                </FORM>
              </TABLE>  <br>
      <br>
      <?php } ?>
      <?php if ($form_action=="ask-add"){ ?>
	  
    <br>
	  
	    <TABLE width="95%" border=0 align="center" cellPadding=2 cellSpacing=2 style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px">
                <FORM action=<?php echo $PHP_SELF?> id=form1 method=post name=form1>
                  <INPUT type="hidden" name="form_action" value="add">
				  
				  	<?php
						if (!is_null($FG_QUERY_ADITION_HIDDEN_FIELDS) && $FG_QUERY_ADITION_HIDDEN_FIELDS!=""){
							
							$split_hidden_fields = preg_split("/,/",trim($FG_QUERY_ADITION_HIDDEN_FIELDS));
							$split_hidden_fields_value = preg_split("/,/",trim($FG_QUERY_ADITION_HIDDEN_VALUE));

							for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields);$cur_hidden++){
									echo "<INPUT type=\"hidden\" name=\"".trim($split_hidden_fields[$cur_hidden])."\" value=\"".trim($split_hidden_fields_value[$cur_hidden])."\">\n";
							}
							
						}
					?>
				  
				  <INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
				  
                  <TBODY>
                    <?php for($i=0;$i<$FG_NB_TABLE_EDITION;$i++){ 
						//$FG_TABLE_COL[$i][1];			
						//$FG_TABLE_COL[]=array ("Name", "name", "20%");
						$pos = strpos($FG_TABLE_EDITION[$i][1], ":");
						if (!$pos){
					?>
                    <TR> 					  
                      <TD width="%25" valign="top" class="form_head">
                        <?php echo $FG_TABLE_ADITION[$i][0]?></TD>
                      <TD width="%75" valign="top" class="tableBodyRight" bgcolor="#CCCCCC"> 
                        <?php 
								if ($FG_DEBUG == 1) print($FG_TABLE_ADITION[$i][3]);
						  		if (strtoupper ($FG_TABLE_ADITION[$i][3])==strtoupper ("INPUT")){
						  ?>
                        <INPUT class="form_input_text" name=<?php echo $FG_TABLE_ADITION[$i][1]?>  <?php echo $FG_TABLE_ADITION[$i][4]?> value="<?php echo $$FG_TABLE_ADITION[$i][1]?>"> 
                        <?php 
						  		}elseif (strtoupper ($FG_TABLE_ADITION[$i][3])==strtoupper ("TEXTAREA")){
						  ?>
                        <textarea class="form_input_textarea" name=<?php echo $FG_TABLE_ADITION[$i][1]?> <?php echo $FG_TABLE_ADITION[$i][4]?>><?php echo $$FG_TABLE_ADITION[$i][1]?></textarea> 
                        <?php 	
								}elseif (strtoupper ($FG_TABLE_ADITION[$i][3])==strtoupper ("SELECT")){
								
											if ($FG_DEBUG == 1) { echo "<br> TYPE DE SELECT :".$FG_TABLE_ADITION[$i][7];}
											
											if (strtoupper ($FG_TABLE_ADITION[$i][7])==strtoupper ("SQL")){
																					
												$instance_sub_table = new Table($FG_TABLE_ADITION[$i][8], $FG_TABLE_ADITION[$i][9]);
												//echo "---".$FG_TABLE_ADITION[$i][13];
												$select_list = $instance_sub_table -> Get_list ($DBHandle, $FG_TABLE_ADITION[$i][10], $FG_TABLE_ADITION[$i][13], $FG_TABLE_ADITION[$i][14], null, null, null, null);
												
												if ($FG_DEBUG >= 2) { echo "<br>"; print_r($select_list);}
											
											}elseif (strtoupper ($FG_TABLE_ADITION[$i][7])==strtoupper ("LIST")){
																				
												$select_list = $FG_TABLE_ADITION[$i][11];
												//$select_list_nb = count($select_list);
											}
						  ?>
                        <SELECT class="form_input_select" name=<?php echo $FG_TABLE_ADITION[$i][1]?> <?php echo $FG_TABLE_ADITION[$i][4]?>>
                          <?php
										if (count($select_list)>0){
										  	 $select_number=0;
					 
										  	 foreach ($select_list as $select_recordset){ 
												 $select_number++;
												//echo $FG_TABLE_ADITION[$i][12]."\n\n----------------------------------------\n";
									
										   		if ($FG_TABLE_ADITION[$i][12] != ""){
				
														$value_display = $FG_TABLE_ADITION[$i][12];
														$nb_recor_k = count($select_recordset);
														for ($k=1;$k<=$nb_recor_k;$k++){
																			$value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
														}
												
												}else{
														$value_display = $select_recordset[0];								
												}
							?>
								
									<OPTION  value=<?php echo $select_recordset[1]?> <?php if ($$FG_TABLE_ADITION[$i][2]=="$select_recordset[1]"){?>selected<?php  } ?>><?php echo $value_display?></OPTION>
						<?php 
											 }// END_FOREACH
										 }else{
										  		echo gettext("No data found !!!");
										 }//END_IF				
							?>
                        </SELECT>
                        <?php     }elseif (strtoupper ($FG_TABLE_ADITION[$i][3])==strtoupper ("RADIOBUTTON")){
								
										 
												$radio_table = preg_split("/,/",trim($FG_TABLE_EDITION[$i][9]));
												
												foreach ($radio_table as $radio_instance){
													$radio_composant = preg_split("/:/",$radio_instance);
													echo $radio_composant[0];																								
													echo ' <input type="radio" name="'.$FG_TABLE_ADITION[$i][1].'" value="'.$radio_composant[1].'" ';
													if ($$FG_TABLE_ADITION[$i][1]==$radio_composant[1]){
														echo "checked";
													}
													echo ">";
													
												}								
												//  Yes <input type="radio" name="digitalized" value="t" checked>
												//  No<input type="radio" name="digitalized" value="f">
						
                               }//END_IF (RADIOBUTTON)  
							?>
						<span class="liens">
						 <?php 						
							if (!$fit_expression[$i]  &&  isset($fit_expression[$i]) ){
							
								echo "<br>".$FG_TABLE_ADITION[$i][6]." - ".$regular[$FG_TABLE_ADITION[$i][5]][1];								
							}
							   
						  ?>
                        </span> 
                        <?php  if (strlen($FG_TABLE_COMMENT[$i])>0){ echo "<br>".$FG_TABLE_COMMENT[$i]; }?>
                        &nbsp;</TD>
                    </TR>
                    <?php   	}
					
						}//END_FOR ?>
                    <TR>                       
                      <TD colSpan=2  style="border-bottom: medium dotted #667766">&nbsp; </TD>
                    </TR>
                    <TR> 
					  <TD colspan=2>
						<table>
							<tr>
								<td class="tableBodyRight" width="95%"><?php echo $FG_BUTTON_ADITION_BOTTOM_TEXT?></td>
								<td align="right" width="5%"><INPUT class="form_enter"  alt="Create a new <?php echo $FG_INSTANCE_NAME?>" border=0 hspace=2 id=submit4 name=submit2 src="<?php echo $FG_BUTTON_ADITION_SRC?>" type=image></td>
							</tr>
						</table>
                      </TD>
                    </TR>
                    <TR> 
                      <TD colSpan=2 height=4></TD>
                    </TR>
                    
                  </TBODY>
                </FORM>
              </TABLE><!--</TD>
            <TD bgColor=#7f99cc><IMG height=1 src="../Images/clear.gif" width=1> 
            </TD>
          </TR>
          <TR> 
            <TD bgColor=#7f99cc colSpan=3><IMG height=1 src="../Images/clear.gif" width=1></TD>
          </TR>
        </TBODY>
      </TABLE>-->
      <br> <br>
      <?php } ?>
      <?php if ($form_action=="ask-delete"){ ?>
      <blockquote> 
        <div align="center"><b> 
          <?php echo $FG_INTRO_TEXT_ASK_DELETION?>
          </b></div>
      </blockquote>
      <br> 
	  
			<TABLE width="85%" border=0 align="center" cellPadding=2 cellSpacing=2 style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px">
                  <FORM action=<?php echo $PHP_SELF?> id=form1 method=post name=form1>
                  <INPUT type="hidden" name="id" value="<?php echo $id?>">
				  <INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
                  <INPUT type="hidden" name="form_action" value="delete">
                  <TBODY>
                    <?php for($i=0;$i<$FG_NB_TABLE_EDITION;$i++) { ?>
                    <TR> 
                      <TD width="%25" class="form_head"> 
                        <?php echo $FG_TABLE_EDITION[$i][0]?>
                      </TD>                      
					  <TD width="%75" valign="top" class="tableBodyRight" bgcolor="#CCCCCC"> 
                        <?php 
								if ($FG_DEBUG == 1) print($FG_TABLE_EDITION[$i][3]);
						  		if (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("INPUT")){
						  ?>
                        <INPUT class="form_input_text" disabled name=<?php echo $FG_TABLE_EDITION[$i][1]?>  <?php echo $FG_TABLE_EDITION[$i][4]?> value="<?php echo stripslashes($list[0][$i])?>"> 
                        <?php 
						  		}elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("TEXTAREA")){
						  ?>
                        <TEXTAREA class="form_input_textarea" disabled name=<?php echo $FG_TABLE_EDITION[$i][1]?> <?php echo $FG_TABLE_EDITION[$i][4]?>><?php echo stripslashes($list[0][$i])?></textarea> 
                        <?php 	
								}elseif (strtoupper ($FG_TABLE_EDITION[$i][3])==strtoupper ("SELECT")){
								
											if ($FG_DEBUG == 1) { echo "<br> TYPE DE SELECT :".$FG_TABLE_EDITION[$i][7];}
											
											if (strtoupper ($FG_TABLE_EDITION[$i][7])==strtoupper ("SQL")){
																					
												$instance_sub_table = new Table($FG_TABLE_EDITION[$i][8], $FG_TABLE_EDITION[$i][9]);
												$select_list = $instance_sub_table -> Get_list ($DBHandle, $FG_TABLE_EDITION[$i][10], null, null, null, null, null, null);
												
												if ($FG_DEBUG >= 2) { echo "<br>"; print_r($select_list);}
											
											}elseif (strtoupper ($FG_TABLE_EDITION[$i][7])==strtoupper ("LIST")){
																				
												$select_list = $FG_TABLE_EDITION[$i][11];
												//$select_list_nb = count($select_list);
											}
						  ?>
                        <SELECT class="form_input_select" disabled name=<?php echo $FG_TABLE_EDITION[$i][1]?> >
                          <?php
										if (count($select_list)>0){
										  	 $select_number=0;
					 
										  	 foreach ($select_list as $select_recordset){ 
												 $select_number++;
												 //%1 : (%2)
												 if (!is_null($FG_TABLE_EDITION[$i][12]) && strlen($FG_TABLE_EDITION[$i][12])){
												 		$value_display =  $FG_TABLE_EDITION[$i][12];
														$nb_recor_k = count($select_recordset);
														for ($k=1;$k<=$nb_recor_k;$k++){
															$value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
														}
														
												 }else{
												 		$value_display = $select_recordset[0];
												 }
									
									?>
                          <OPTION  value=<?php echo $select_recordset[1]?> <?php if (strcmp($list[0][$i],$select_recordset[1])==0){ echo "selected"; } ?>> 
                          <?php echo $value_display?>
                          </OPTION>
                          <?php 
											 }// END_FOREACH
										 }else{
										  		echo gettext("No data found !!!");
										 }//END_IF				
							?>
                        </SELECT> 
                        <?php     }//END_IF (SELECT)						
						?>
                      </TD>
                    </TR>
                    <?php   }//END_FOR ?>
					<TR>                       
                      <TD colSpan=2  style="border-bottom: medium dotted #667766">&nbsp; </TD>
                    </TR>
                    <TR> 
                      <TD class="tableBodyRight"  width="95%"><?php echo $FG_BUTTON_DELETION_BOTTOM_TEXT?>
					  </TD>				                      
                      <TD align="right"  width="5%"> <INPUT class="form_input_button" title="<?php echo gettext("Remove this DID DESTINATION");?>" alt="<?php echo gettext("Remove this DID DESTINATION");?>" height=20 hspace=2 id=submit22 name=submit22 value="<?php echo gettext("Delete");?>" type="submit"></TD>
                    </TR>                    
                    
                  </TBODY>
                </FORM>
              </TABLE>
      <br> <br> 
      <?php } ?>
      <?php if ($form_action == "delete" || $form_action == "add"){ ?>
      <br><br>
        
 			  <TABLE width="85%" border=0 align="center" cellPadding=2 cellSpacing=2 style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px">              
                <TR> 
                    <TD class="form_head"> 
					  <?php if ($form_action == "delete") { ?>
					  <?php echo $FG_INSTANCE_NAME?> <?php gettext("Deletion");?>
					  <?php }elseif ($form_action == "add"){ ?>
					  <?php gettext("New")." ".$FG_INSTANCE_NAME." ".gettext("Inserted");?> 
					  <?php  } ?>
                    </TD>                    
                 </TR>
                 <TR> 
                    <TD width="516" valign="top" class="tableBodyRight" bgcolor="#CCCCCC"> <br>
						<div align="center"><strong><font size="3"> 
					<?php if ($form_action == "delete") { ?><?php echo $FG_INTRO_TEXT_DELETION?><?php }elseif ($form_action == "add"){ ?><?php echo $FG_TEXT_ADITION_CONFIRMATION?><?php  } ?>
                        
                        </font></strong></div>
						<br>
					</TD>
                  </TR>
              </TABLE>
              
	  <br><br><br><br><br>
      <?php } ?>
<?php

$smarty->display( 'footer.tpl');

