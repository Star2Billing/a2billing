<?php
exit();
include ("lib/customer.defines.php");
include ("lib/customer.module.access.php");
include ("lib/customer.smarty.php");
include ("lib/Class.RateEngine.php");	 
include ("lib/phpagi/phpagi-asmanager.php");
include ("frontoffice_data/CC_var_phonelist.inc");


getpost_ifset(array('callback', 'called', 'calling'));

if (!$A2B->config["webcustomerui"]['predictivedialer']) exit();

$FG_DEBUG = 0;


if (! has_rights (ACX_ACCESS)){ 
	   Header ("HTTP/1.0 401 Unauthorized");
	   Header ("Location: PP_error.php?c=accessdenied");	   
	   die();	   
}


$QUERY = "SELECT  username, credit, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, lastuse, activated, status, callback FROM cc_card WHERE username = '".$_SESSION["pr_login"]."' AND uipass = '".$_SESSION["pr_password"]."'";

$DBHandle_max = DbConnect();
$numrow = 0;
$resmax = $DBHandle_max -> Execute($QUERY);
if ($resmax)
	$numrow = $resmax -> RecordCount();

if ($numrow == 0) exit();
$customer_info =$resmax -> fetchRow();

if($customer_info [14] != "1" ) {
	exit();
}



if ($callback){
	//$called='0013256474747';
	if (strlen($called)>2){
			$A2B -> cardnumber = $_SESSION["pr_login"];
			
			$dialstr = $called;
			
			$as = new AGI_AsteriskManager();
			
			// && CONNECTING  connect($server=NULL, $username=NULL, $secret=NULL)
			
			$res = $as->connect(MANAGER_HOST,MANAGER_USERNAME,MANAGER_SECRET);
			if	($res){
				
				$channel= $dialstr;
				$exten = $calling;
				$context = $A2B -> config["callback"]['context_preditctivedialer'];
				$priority = 1;
				$timeout = $A2B -> config["callback"]['timeout']*1000;
				$application='';
				$callerid=$A2B -> config["callback"]['callerid'];
				$account=$_SESSION["pr_login"];

				$res = $as->Originate($channel, $exten, $context, $priority, $application, $data, $timeout, $callerid, $variable, $account, $async, $actionid);
				if($res["Response"]=='Error'){
						$error_msg = "<font face='Arial, Helvetica, sans-serif' size='2' color='red'><b>".gettext("Error : The system cannot call you back, please inform the administrator")."  !!!</b></font><br>";
				}
				
				
				
				// && DISCONNECTING	
				$as->disconnect();
						
			
			}else{
					$error_msg= gettext("Cannot connect to the asterisk manager!")."<br>".gettext("Please check the manager configuration...");
				
			}
			
	}else{
		$error_msg = "<font face='Arial, Helvetica, sans-serif' size='2' color='red'><b>".gettext("Error : You have to specify your phonenumber and the number you wish to call")." !!!</b></font><br>";
	
	}
}

$customer = $_SESSION["pr_login"];

$DBHandle  = DbConnect();


if ($FG_DEBUG == 3) echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);

if ( is_null ($order) || is_null($sens) ){
	$order = $FG_TABLE_DEFAULT_ORDER;
	$sens  = $FG_TABLE_DEFAULT_SENS;
}

if ($FG_DEBUG == 3) $instance_table -> debug_st =1;
$list = $instance_table -> Get_list ($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page*$FG_LIMITE_DISPLAY);
if ($FG_DEBUG == 3) echo "<br>Clause : $FG_TABLE_CLAUSE";
$nb_record = $instance_table -> Table_count ($DBHandle, $FG_TABLE_CLAUSE);
if ($FG_DEBUG >= 1) var_dump ($list);



if ($nb_record<=$FG_LIMITE_DISPLAY){ 
	$nb_record_max=1;
}else{ 
	if ($nb_record % $FG_LIMITE_DISPLAY == 0){
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY));
	}else{
		$nb_record_max=(intval($nb_record/$FG_LIMITE_DISPLAY)+1);
	}	
}

if ($FG_DEBUG == 3) echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3) echo "<br>Nb_record_max : $nb_record_max";

?>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

//-->
</script>

<?php
	$smarty->display( 'main.tpl');
?>

<br>
	  <center>
	  <font color=red><b><?php echo $res['Message']; ?></b></font>
	  <?php echo $error_msg ?> <br>
	  <?php echo gettext("You can initiate the predictive dialer by clicking on the Place call button!");?>
	  </center>
	  
	  
	   <table align="center"  border="0" width="75%" class="bgcolor_006" >
        
		<form name="theForm" action=<?php echo $PHP_SELF;?> method="POST" >
		<INPUT type="hidden" name="callback" value="1">
		<tr  class="bgcolor_006">
		<td align="left" valign="bottom">
				<br/>
				<?php echo gettext("Your PhoneNumber / Location");?> :
				
				<input class="form_input_text" name="called" value="<?php echo $customer_info[15]; ?>" size="30" maxlength="40" readonly="readonly" >
				<br/><br/>
			</td>	
			<td align="center" valign="middle"> 
			<input class="form_input_button"  value="[ <?php echo gettext("Click here to place 10 calls");?> ]" type="submit">
			</td>
        </tr>
		</form>
      </table>
	  <br><br><br><br>
	  <center>
		<form name="theForm" action=<?php echo $PHP_SELF;?> method="POST" >
		<input class="form_input_button"  value="[ <?php echo gettext("Click here to see the last called persons and the current connect call");?> ]" type="submit">
	  	</form>
	  </center>
	   <?php	
				  if ((count($list)>0) && is_array($list)){
				  	 $ligne_number=0;
	  ?>
	  
	  	  
	 
      <table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
<TR> 
          <TD  height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
            <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
              <TBODY>
                <TR> 
                  <TD><SPAN style="COLOR: #000000; FONT-SIZE: 11px"><B><?php echo strtoupper($FG_HTML_TABLE_TITLE)?></B></SPAN></TD>
                  <TD align=right> </TD>
                </TR>
              </TBODY>
            </TABLE></TD>
        </TR>
        <TR> 
          <TD> 
		  	
			<TABLE border=0 cellPadding=5 cellSpacing=5 width="100%">
<TBODY>
                <TR class="form_head"> 
                  <?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
					?>					
                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px">
                    <strong> 
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
                    <a href="<?php  echo $PHP_SELF."?stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens="; if ($sens=="ASC"){echo "DESC";}else{echo "ASC";}?>">
                    <span class="white_link"><?php  } ?>
                    <?php echo $FG_TABLE_COL[$i][0]?>
                    <?php if ($order==$FG_TABLE_COL[$i][1] && $sens=="ASC"){?>
                    &nbsp;<img src="images/icon_up_12x12.GIF" width="12" height="12" border="0"> 
                    <?php }elseif ($order==$FG_TABLE_COL[$i][1] && $sens=="DESC"){?>
                    &nbsp;<img src="images/icon_down_12x12.GIF" width="12" height="12" border="0"> 
                    <?php }?>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT"){?>
                    </span></a> 
                    <?php }?>
                    </strong></TD>
				   <?php } ?>		
				   <?php if ($FG_DELETION || $FG_EDITION){ ?>
				   
                  <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"><strong>Action</strong></TD>
				   <?php } ?>		
                </TR>
                
				<?php				
				  	 foreach ($list as $recordset){ 
						 $ligne_number++;
				?>
				
               		 <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'">
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
							if ($FG_TABLE_COL[$i][6]=="lie"){

									$instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
									$sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);
									if ($FG_DEBUG == 3) $instance_sub_table -> debug_st =1;																																	
									$select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);
									
									
									$field_list_sun = split(',',$FG_TABLE_COL[$i][8]);
									$record_display = $FG_TABLE_COL[$i][10];
									
									for ($l=1;$l<=count($field_list_sun);$l++){										
										$record_display = str_replace("%$l", $select_list[0][$l-1], $record_display);	
									}
								
							}elseif ($FG_TABLE_COL[$i][6]=="list"){
									$select_list = $FG_TABLE_COL[$i][7];
									$record_display = $select_list[$recordset[$i]][0];
							
							}else{
									$record_display = $recordset[$i];
							}
							
							
							if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ){
								$record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5])."";  
															
							}
							
							
				 		 ?>
                 		 <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php
						 if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1){
						 		call_user_func($FG_TABLE_COL[$i][11], $record_display);
						 }else{
						 		echo stripslashes($record_display);
						 }						 
						 ?></TD>						 
				 		 <?php  } ?>
                  <?php if ($FG_DELETION || $FG_EDITION){ ?>
                  <TD align="center" vAlign=top class=tableBodyRight>
                    <?php if($FG_EDITION){ ?>                    
                    <a href="<?php echo $FG_EDITION_LINK?><?php echo $recordset[$FG_NB_TABLE_COL]?>&stitle=<?php echo $stitle?>" >
                    <img src="images/icon-edit.gif" border="0" alt="<?php echo $FG_EDIT_ALT?>" width="23" height="11"></a>
					<?php } ?>
					<?php if($FG_DELETION){?>
                    - 					 
					 <a href="<?php echo $FG_DELETION_LINK?><?php echo $recordset[$FG_NB_TABLE_COL]?>&stitle=<?php echo $stitle?>"><img src="images/icon-del.gif" alt="<?php echo $FG_DELETE_ALT?>" width="33" height="11" border="0"></a>
					 <?php } ?>
                  </TD>
				  <?php } ?>
					</TR>
				<?php
					 }//foreach ($list as $recordset)
					 while ($ligne_number < $FG_LIMITE_DISPLAY-5){
					 	$ligne_number++;
				?>
					<TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>">
				  		<?php for($i=0;$i<$FG_NB_TABLE_COL;$i++){ 
				 		 ?>
                 		 <TD vAlign=top class=tableBody>&nbsp;</TD>
				 		 <?php  } ?>
                 		 <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>				
					</TR>
									
				<?php					 
					 } //END_WHILE
					 
				 ?>
                <TR> 
                  <TD class=tableDivider colSpan=<?php echo $FG_TOTAL_TABLE_COL?>><IMG height=1
                              src="images/clear.gif" 
                              width=1></TD>
                </TR>               
              </TBODY>
            </TABLE>			
			
			</td>
        </tr>
         <TR > 
          <TD height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
			<TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
              <TBODY>
                <TR> 
                  <TD align="right"><span class="liens"><B> 
                    <?php if ($current_page>0){?>
                    <img src="images/fleche-g.gif" width="5" height="10"> <a href="<?php echo $PHP_SELF?>?stitle=<?php echo $stitle?>&atmenu=<?php echo $atmenu?>&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page-1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} ?>">
                    <?php echo gettext("Previous");?> </a> -
                    <?php }?>
                    <?php echo ($current_page+1);?> / <?php  echo $nb_record_max;?>
                    <?php if ($current_page<$nb_record_max-1){?>
                    - <a href="<?php echo $PHP_SELF?>?stitle=<?php echo $stitle?>&atmenu=<?php echo $atmenu?>&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php  echo ($current_page+1)?><?php  if (!is_null($letter) && ($letter!="")){ echo "&letter=$letter";} ?>">
                    <?php echo gettext("NEXT");?></a> <img src="images/fleche-d.gif" width="5" height="10">
                    </B></SPAN> 
                    <?php }?>
                  </TD>
              </TBODY>
            </TABLE></TD>
        </TR>
		<TR> 
          <TD style="border-bottom: medium dotted #667766">&nbsp; </TD>
        </TR>
      </table>
	  <?php 
				  }else{
				  ?>
				  
				   <br><br>
				  <table width="50%" border="0" align="center" class="bgcolor_006">
					<tbody><tr>
					  <td align="center">
						<?php echo gettext("NO");?> <?php echo strtoupper($FG_CLASS_NAME)?> <?php echo gettext("REGISTERED");?> !<br>
					</td>
					</tr>

				  </tbody></table>
				  <br><br>
				  
				  <?php 				  
				  }//end_if
	  ?>
	  
	  
	  
	  
	  
	  <br>
	  <?php if($FG_LINK_ADD){?>
	  <br>
      <TABLE width="75%" height=50 border=0 align="center" cellPadding=0 cellSpacing=0>
        <TBODY>
          <TR> 
            <TD bgColor=#7f99cc colSpan=3 height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 5px"> 
              <SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><B><?php echo gettext("INSERT a new");?> <?php echo gettext("PHONELIST");?></B></SPAN></TD>
          </TR>
          <TR> 
            <TD bgColor=#7f99cc> <IMG height=1 src="images/clear.gif" width=1> 
            </TD>
            <TD bgColor=#edf3ff style="PADDING-BOTTOM: 7px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 5px"> 
              <TABLE width="90%" border=0 align="center" cellPadding=0 cellSpacing=0>
				<FORM action=/alphar.asp id=frmSearch method=post name=frmSearch onsubmit="javascript:return validateSearch(this);">
                  <TBODY>
                    <TR align="center"> 
                      <TD colspan="2" class="tableBodyRight"><font size="2"><strong>If 
                        <?php echo gettext("You wish to insert a new");?> <?php echo gettext("PHONELIST");?>, <?php echo gettext("Click on Add button");?></strong></font><br>
                      </TD>
                    </TR>
                    <TR> 
                      <TD width="424"> <A href="#" onclick="MM_openBrWindow('P2E_help.php?id=5','Help','width=450,height=350')"> 
                        <IMG border=0 height=6 hspace=3 src="images/icon_arrow_4x6.gif" vspace=1 width=4>Information about the insertion</A></TD>
                      <TD width="161" align="right"><a href="<?php echo $FG_INSERT_LINK?>&stitle=<?php echo $stitle?>"><img src="images/btn_Add_94x20.gif" alt="<?php echo gettext("Insert a new");?> <?php echo gettext("PHONELIST");?>" width="94" height="20" border="0"></a> 
                      </TD>
                    </TR>
                  </TBODY>
                </FORM>
              </TABLE></TD>
            <TD bgColor=#7f99cc><IMG height=1 src="images/clear.gif" width=1> 
            </TD>
          </TR>
          <TR> 
            <TD bgColor=#7f99cc colSpan=3><IMG height=1 src="images/clear.gif" width=1></TD>
          </TR>
        </TBODY>
      </TABLE>
      <br>
	  
<?php
	}

	$smarty->display( 'footer.tpl');
?>
