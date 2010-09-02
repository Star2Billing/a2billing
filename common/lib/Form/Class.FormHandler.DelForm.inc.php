<?php

$processed = $this->getProcessed();

if ($form_action == "ask-delete")
{
    if ($this -> isFKDataExists() == false)
    {
        //if($this-> FG_FK_DELETE_ALLOWED == true && $this->FG_FK_WARNONLY == false)
        {
            $this-> FG_FK_DELETE_ALLOWED = false;
            $this -> FG_ISCHILDS = false;
            $this-> FG_FK_WARNONLY = false;
            $this->FG_FK_DELETE_CONFIRM = false;
        }

    }

}
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function sendto(action, record, field_inst, instance){
  document.myForm.submit();
}
//-->
</script>

<?php
if ($this->FG_FK_DELETE_CONFIRM && $form_action == "ask-del-confirm" && $this-> FG_FK_DELETE_ALLOWED)
{ ?>

<FORM action=<?php echo $_SERVER['PHP_SELF']?> id="myForm" method="post" name="myForm">
	<INPUT type="hidden" name="id" value="<?php echo $id?>">
	<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
	<INPUT type="hidden" name="form_action" value="delete">
	<INPUT type="hidden" name="current_page" value="<?php echo $processed['current_page'];?>">
	<INPUT type="hidden" name="order" value="<?php echo $processed['order'];?>">
	<INPUT type="hidden" name="sens" value="<?php echo $processed['sens'];?>">
	<table cellspacing="2"  class="tablestyle_001">
    <tr>
        <td>
            <table cellspacing=0 class="delform_table2">
                <tr>
                    <td align=left class="delform_table2_td1"><?php echo gettext("Message");?></td>
                </tr>
                <tr >
                    <td class="bgcolor_006">&nbsp;</td>
                </tr>
                <tr height="50px">
                    <td align=center class="bgcolor_006">
                    <?php echo gettext("You have "). $processed["fkCount"]." dependent records.<br>" ?>
                    <?php echo $this -> FG_FK_DELETE_MESSAGE;?>
                    </td>
                </tr>
                <tr>
                    <td class="bgcolor_006">&nbsp;</td>
                </tr>
                <tr>
                    <td align=center class="bgcolor_006">
                        <INPUT title="Delete this record" alt="Delete this Record" hspace=2 id=submit22 name=submit22 src="<?php echo Images_Path_Main;?>/btn_Delete_94x20.gif" type="image">
                    </td>
                </tr>
                <tr height="5px">
                    <td class="bgcolor_006">&nbsp;</td>
                </tr>

            </table>
        </td>
    </tr>
    </table>


<?php
}
else
{
?>
<table cellspacing="3" class="delform_table3">
	<tr>
	  	<td class="delform_table3_td1" valign="top"> <span class="textnegrita"><?php echo $this->FG_INTRO_TEXT_ASK_DELETION?></span></td>
	</tr>
</table>
<FORM action=<?php echo $_SERVER['PHP_SELF']?> id="myForm" method="post" name="myForm">
	<INPUT type="hidden" name="id" value="<?php echo $id?>">
	<INPUT type="hidden" name="atmenu" value="<?php echo $atmenu?>">
    <INPUT type="hidden" name="fkCount" value="<?php echo $this -> FG_FK_RECORDS_COUNT;?>">
	<INPUT type="hidden" name="current_page" value="<?php echo $processed['current_page'];?>">
	<INPUT type="hidden" name="order" value="<?php echo $processed['order'];?>">
	<INPUT type="hidden" name="sens" value="<?php echo $processed['sens'];?>">
    <?php if ($this->FG_FK_DELETE_CONFIRM && $this-> FG_FK_DELETE_ALLOWED && $this -> FG_ISCHILDS){ ?>
	<INPUT type="hidden" name="form_action" value="ask-del-confirm">
    <?php }else { ?>
    <INPUT type="hidden" name="form_action" value="delete">
    <?php }  ?>

	<?php
	if (!is_null($this->FG_QUERY_EDITION_HIDDEN_FIELDS) && $this->FG_QUERY_EDITION_HIDDEN_FIELDS!=""){
		$split_hidden_fields = preg_split("/,/",trim($this->FG_QUERY_EDITION_HIDDEN_FIELDS));
		$split_hidden_fields_value = preg_split("/,/",trim($this->FG_QUERY_EDITION_HIDDEN_VALUE));

		for ($cur_hidden=0;$cur_hidden<count($split_hidden_fields);$cur_hidden++){
			echo "<INPUT type=\"hidden\" name=\"".trim($split_hidden_fields[$cur_hidden])."\" value=\"".trim($split_hidden_fields_value[$cur_hidden])."\">\n";
		}
	}
	?>

	<table cellspacing="2" class="tablestyle_001">
		<?php for($i=0;$i<$this->FG_NB_TABLE_EDITION;$i++){ ?>
		<TR>
			<TD width="25%" valign="middle" class="form_head">
				<?php echo $this->FG_TABLE_EDITION[$i][0]?>
			</TD>
			<TD valign="top" class="tablestyle_001">
				<?php
					if ($this->FG_DEBUG == 1) print($this->FG_TABLE_EDITION[$i][3]);
					$arr_input = array("INPUT", "POPUPVALUE", "POPUPVALUETIME", "POPUPDATETIME");					
					if (in_array(strtoupper ($this->FG_TABLE_EDITION[$i][3]), $arr_input)){
				?>
					<INPUT class="form_enter" readonly name=<?php echo $this->FG_TABLE_EDITION[$i][1]?>  <?php echo $this->FG_TABLE_EDITION[$i][4]?> value="<?php echo stripslashes($list[0][$i])?>">
				<?php
					}elseif (strtoupper ($this->FG_TABLE_EDITION[$i][3])==("TEXTAREA")){
				?>
					<TEXTAREA class="form_input_textarea" readonly name=<?php echo $this->FG_TABLE_EDITION[$i][1]?> <?php echo $this->FG_TABLE_EDITION[$i][4]?>><?php echo stripslashes($list[0][$i])?></textarea>
				<?php
					}elseif (strtoupper ($this->FG_TABLE_EDITION[$i][3])== ("SELECT")){
					if ($this->FG_DEBUG == 1) { echo "<br> TYPE DE SELECT :".$this->FG_TABLE_EDITION[$i][7];}
					if (strtoupper ($this->FG_TABLE_EDITION[$i][7])==strtoupper ("SQL")){
						$instance_sub_table = new Table($this->FG_TABLE_EDITION[$i][8], $this->FG_TABLE_EDITION[$i][9]);
						$select_list = $instance_sub_table -> Get_list ($this->DBHandle, $this->FG_TABLE_EDITION[$i][10], null, null, null, null, null, null);
						if ($this->FG_DEBUG >= 2) { echo "<br>"; print_r($select_list);}
					}elseif (strtoupper ($this->FG_TABLE_EDITION[$i][7])== ("LIST")){
						$select_list = $this->FG_TABLE_EDITION[$i][11];
					}
					?>
					<SELECT class="form_input_select" disabled name=<?php echo $this->FG_TABLE_EDITION[$i][1]?>>
						<?php
						if (count($select_list)>0){
							$select_number=0;
							foreach ($select_list as $select_recordset){
								$select_number++;
								//%1 : (%2)
								if (!is_null($this->FG_TABLE_EDITION[$i][12]) && strlen($this->FG_TABLE_EDITION[$i][12])){
									$value_display =  $this->FG_TABLE_EDITION[$i][12];
									$nb_recor_k = count($select_recordset);
									for ($k=1;$k<=$nb_recor_k;$k++){
										$value_display  = str_replace("%$k", $select_recordset[$k-1], $value_display );
									}

									}else{
										$value_display = $select_recordset[0];
									}
						?>
						<OPTION  value='<?php echo $select_recordset[1]?>' <?php if (strcmp($list[0][$i],$select_recordset[1])==0){ echo "selected"; } ?>>
							<?php echo $value_display?>
						</OPTION>
						<?php
							}// END_FOREACH
						}else{
							echo gettext("No data found !!!");
						}//END_IF
						?>
					</SELECT>
				 <?php   
					}elseif (strtoupper ($this->FG_TABLE_EDITION[$i][3])==strtoupper ("RADIOBUTTON")){
						$radio_table = preg_split("/,/",trim($this->FG_TABLE_EDITION[$i][10]));
						foreach ($radio_table as $radio_instance){
							$radio_composant = preg_split("/:/",$radio_instance);
							echo $radio_composant[0];
							echo ' <input class="form_enter" disabled type="radio" name="'.$this->FG_TABLE_EDITION[$i][1].'" value="'.$radio_composant[1].'" ';
							if($this->VALID_SQL_REG_EXP){ 
								$know_is_checked = stripslashes($list[0][$i]); 
							}else{ 
								$know_is_checked = $processed[$this->FG_TABLE_EDITION[$i][1]];  
							}
													
							if ($know_is_checked==$radio_composant[1]){
								echo "checked";
							}
							echo ">";
													
						}								
						//  Yes <input type="radio" name="digitalized" value="t" checked>
						//  No<input type="radio" name="digitalized" value="f">
						
                               		}//END_IF (RADIOBUTTON)  
							   
		  			?>
		  	</TD>
		</TR>
		<?php   }//END_FOR ?>
	</TABLE>


	<TABLE cellspacing="0" class="delform_table5">
		<tr height="2">
			<td colspan="2" style="border-bottom: medium dotted rgb(255, 119, 102);">&nbsp; </td>
		</tr>
		<tr>
		  <td width="50%" class="text_azul"><span class="tableBodyRight"><?php echo $this->FG_BUTTON_DELETION_BOTTOM_TEXT?></span></td>
		  <td width="50%" align="right" class="text">
			
				<a href="#" onClick="sendto('delete');"  class="cssbutton_big"><IMG src="<?php echo Images_Path_Main;?>/icon_arrow_orange.gif">
				<?php echo $this->FG_DELETE_PAGE_CONFIRM_BUTTON; ?> </a>
				
				<!--
				<INPUT title="<?php echo gettext("Remove this ");?> <?php echo $this->FG_INSTANCE_NAME; ?>" alt="<?php echo gettext("Remove this ");?> <?php echo $this->FG_INSTANCE_NAME; ?>" hspace=2 id=submit22 name=submit22 src="<?php echo Images_Path_Main;?>/btn_Delete_94x20.gif" type="image">
				-->
			</td>
		</tr>
	</TABLE>
</FORM>
<?php }?>
