<?php

if ($this->FG_FILTER_SEARCH_FORM) {

?>

<!-- ** ** ** ** ** Part for the research - ** ** ** ** ** -->
    <center>
        <b><?php echo $this -> FG_FILTER_SEARCH_TOP_TEXT?></b>
        <table class="searchhandler_table1">
        <FORM METHOD="POST" ACTION="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?s=<?php echo $processed['s']?>&t=<?php echo $processed['t']?>&order=<?php echo $processed['order']?>&sens=<?php echo $processed['sens']?>&current_page=<?php echo $processed['current_page']?>">
        <INPUT TYPE="hidden" NAME="posted_search" value="1">
        <INPUT TYPE="hidden" NAME="current_page" value="0">
        <?php
            if ($this->FG_CSRF_STATUS == true) {
        ?>
            <INPUT type="hidden" name="<?php echo $this->FG_FORM_UNIQID_FIELD ?>" value="<?php echo $this->FG_FORM_UNIQID; ?>" />
            <INPUT type="hidden" name="<?php echo $this->FG_CSRF_FIELD ?>" value="<?php echo $this->FG_CSRF_TOKEN; ?>" />
        <?php
            }
        ?>

        <?php if ($this -> FG_FILTER_SEARCH_1_TIME) { ?>
            <tr>
                <td align="left" class="bgcolor_002">
                    &nbsp;&nbsp;<font class="fontstyle_003"><?php echo $this-> FG_FILTER_SEARCH_1_TIME_TEXT?></font>
                </td>
                  <td align="left" class="bgcolor_003">
                    <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr><td class="fontstyle_searchoptions">
                      <input type="checkbox" name="fromday" value="true" <?php  if ($processed['fromday']) { ?>checked<?php }?>> <?php echo gettext("From :");?>
                    <select name="fromstatsday_sday" class="form_input_select">
                        <?php
                            for ($i=1;$i<=31;$i++) {
                                if ($processed['fromstatsday_sday']==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
                                echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                            }
                        ?>
                    </select>
                     <select name="fromstatsmonth_sday" class="form_input_select">
                    <?php
                        $monthname = array( gettext("January"), gettext("February"),gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));

                        $year_actual = date("Y");
                        for ($i = $year_actual ; $i >= $year_actual - 10 ; $i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber ; $j>=0 ; $j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                if ($processed['fromstatsmonth_sday']=="$i-$month_formated")
                                    $selected="selected";
                                else
                                    $selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    </td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
                    <input type="checkbox" name="today" value="true" <?php  if ($processed['today']) { ?>checked<?php }?>><?php echo gettext("To :");?>
                    <select name="tostatsday_sday" class="form_input_select">
                    <?php
                        for ($i=1;$i<=31;$i++) {
                            if ($processed['tostatsday_sday']==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
                            echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                        }
                    ?>
                    </select>
                     <select name="tostatsmonth_sday" class="form_input_select">
                    <?php
                        $year_actual = date("Y");
                        for ($i = $year_actual ; $i >= $year_actual - 10 ; $i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber ; $j>=0 ; $j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                if ($processed['tostatsmonth_sday']=="$i-$month_formated")
                                    $selected="selected";
                                else
                                    $selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    </td></tr></table>
                  </td>
            </tr>
        <?php } ?>

        <?php if ($this -> FG_FILTER_SEARCH_1_TIME_BIS) { ?>
            <tr>
                <td align="left" class="bgcolor_002">
                    &nbsp;&nbsp;<font class="fontstyle_003"><?php echo $this-> FG_FILTER_SEARCH_1_TIME_TEXT_BIS?></font>
                </td>
                  <td align="left" class="bgcolor_003">
                    <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr><td class="fontstyle_searchoptions">
                      <input type="checkbox" name="fromday_bis" value="true" <?php  if ($processed['fromday_bis']) { ?>checked<?php }?>> <?php echo gettext("From :");?>
                    <select name="fromstatsday_sday_bis" class="form_input_select">
                        <?php
                            for ($i=1;$i<=31;$i++) {
                                if ($processed['fromstatsday_sday_bis']==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
                                echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                            }
                        ?>
                    </select>
                     <select name="fromstatsmonth_sday_bis" class="form_input_select">
                    <?php
                        $year_actual = date("Y");
                        for ($i = $year_actual ; $i >= $year_actual - 10 ; $i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber ; $j>=0 ; $j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                if ($processed['fromstatsmonth_sday_bis']=="$i-$month_formated")
                                    $selected="selected";
                                else
                                    $selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    </td><td class="fontstyle_searchoptions">&nbsp;&nbsp;
                    <input type="checkbox" name="today_bis" value="true" <?php  if ($processed['today_bis']) { ?>checked<?php }?>><?php echo gettext("To :");?>
                    <select name="tostatsday_sday_bis" class="form_input_select">
                    <?php
                        for ($i=1;$i<=31;$i++) {
                            if ($processed['tostatsday_sday_bis']==sprintf("%02d",$i)) {$selected="selected";} else {$selected="";}
                            echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
                        }
                    ?>
                    </select>
                     <select name="tostatsmonth_sday_bis" class="form_input_select">
                    <?php
                        $year_actual = date("Y");
                        for ($i = $year_actual ; $i >= $year_actual - 10 ; $i--) {
                            if ($year_actual==$i) {
                                $monthnumber = date("n")-1; // Month number without lead 0.
                            } else {
                                $monthnumber=11;
                            }
                            for ($j=$monthnumber ; $j>=0 ; $j--) {
                                $month_formated = sprintf("%02d",$j+1);
                                if ($processed['tostatsmonth_sday_bis']=="$i-$month_formated")
                                    $selected="selected";
                                else
                                    $selected="";
                                echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                            }
                        }
                    ?>
                    </select>
                    </td></tr></table>
                  </td>
            </tr>
        <?php } ?>

        <?php if ($this -> FG_FILTER_SEARCH_3_TIME) { ?>
            <tr>
                <td align="left" class="bgcolor_002">

                    <font class="fontstyle_003"><?php echo $this-> FG_FILTER_SEARCH_3_TIME_TEXT?></font>
                </td>
                  <td align="left" class="bgcolor_003">
                    <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr><td class="fontstyle_searchoptions">&nbsp;
                    <select name="month_earlier" class="form_input_select">
                        <?php
                            for ($i=3 ; $i<=12 ; $i++) {
                                if ($processed['month_earlier']==$i) {$selected="selected";} else {$selected="";}
                                echo '<option value="'.$i."\"$selected>".$i.' Months</option>';
                            }
                        ?>
                    </select>
                    </td></tr></table>
                  </td>
            </tr>
        <?php } ?>

        <!-- compare with a value //-->
        <?php
        $nu = 0;
        foreach ($this->FG_FILTER_SEARCH_FORM_1C as $one_compare) {
        if ($nu%2 == 0) {
            $classleft="bgcolor_004";
            $classright="bgcolor_005";
        } else {
            $classleft="bgcolor_002";
            $classright="bgcolor_003";
        }
        $nu = $nu + 1;
        ?>
            <tr>
                <td class="<?php echo $classleft?>" align="left">
                    <font class="fontstyle_003">&nbsp;&nbsp;<?php echo $one_compare[0]?></font>
                </td>
                <td class="<?php echo $classright?>" align="left" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="<?php echo $one_compare[1]?>" value="<?php echo $processed[$one_compare[1]]?>" class="form_input_text"></td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $one_compare[2]?>" value="1" <?php if ((!isset($processed[$one_compare[2]]))||($processed[$one_compare[2]]==1)) {?>checked<?php }?>><?php echo gettext("Exact");?> </td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $one_compare[2]?>" value="2" <?php if ($processed[$one_compare[2]]==2) {?>checked<?php }?>> <?php echo gettext("Begins with");?></td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $one_compare[2]?>" value="3" <?php if ($processed[$one_compare[2]]==3) {?>checked<?php }?>> <?php echo gettext("Contains");?></td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $one_compare[2]?>" value="4" <?php if ($processed[$one_compare[2]]==4) {?>checked<?php }?>> <?php echo gettext("Ends with");?></td>
                </tr></table></td>
            </tr>

            <?php
            }
            ?>
            <!-- compare between 2 values //-->
            <?php
            $nu = 0;
            foreach ($this->FG_FILTER_SEARCH_FORM_2C as $two_compare) {
            if ($nu%2 == 0) {
                $classleft="bgcolor_004";
                $classright="bgcolor_005";
            } else {
                $classleft="bgcolor_002";
                $classright="bgcolor_003";
            }
            $nu = $nu + 1;
            ?>
            <tr>
                <td class="<?php echo $classleft?>" align="left">
                    <font class="fontstyle_003">&nbsp;&nbsp;<?php echo $two_compare[0]?></font>
                </td>
                <td class="<?php echo $classright?>" align="left">
                <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
                <td class="fontstyle_searchoptions">&nbsp;&nbsp;<INPUT TYPE="text" NAME="<?php echo $two_compare[1]?>" size="10" value="<?php echo $processed[$two_compare[1]]?>" class="form_input_text"></td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[2]?>" value="4" <?php if ($processed[$two_compare[2]]==4) {?>checked<?php }?>>&gt;</td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[2]?>" value="5" <?php if ($processed[$two_compare[2]]==5) {?>checked<?php }?>>&gt; =</td>
                <td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="<?php echo $two_compare[2]?>" value="1" <?php if ((!isset($processed[$two_compare[2]]))||($processed[$two_compare[2]]==1)) {?>checked<?php }?>> = </td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[2]?>" value="2" <?php if ($processed[$two_compare[2]]==2) {?>checked<?php }?>>&lt; =</td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[2]?>" value="3" <?php if ($processed[$two_compare[2]]==3) {?>checked<?php }?>>&lt;</td>
                <td width="5%" class="fontstyle_searchoptions" align="center" ></td>

                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="<?php echo $two_compare[3]?>" size="10" value="<?php echo $processed[$two_compare[3]]?>" class="form_input_text"></td>
                <td class="fontstyle_searchoptions" align="center"><input type="radio" NAME="<?php echo $two_compare[4]?>" value="4" <?php if ($processed[$two_compare[4]]==4) {?>checked<?php }?>>&gt;</td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[4]?>" value="5" <?php if ($processed[$two_compare[4]]==5) {?>checked<?php }?>>&gt; =</td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[4]?>" value="2" <?php if ($processed[$two_compare[4]]==1) {?>checked<?php }?>>&lt; =</td>
                <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="<?php echo $two_compare[4]?>" value="3" <?php if ($processed[$two_compare[4]]==3) {?>checked<?php }?>>&lt;</td>
                </tr></table>
                </td>
            </tr>

            <?php
            }
            ?>
            <?php
            if (is_array($this->FG_FILTER_SEARCH_FORM_SELECT) && count($this->FG_FILTER_SEARCH_FORM_SELECT) > 0) {
            ?>
            <!-- select box //-->
            <tr>
                <td class="bgcolor_002" align="left" >
                    <font class="fontstyle_003">&nbsp;&nbsp;<?php echo $this->FG_FILTER_SEARCH_FORM_SELECT_TEXT?></font>
                </td>
                <td class="bgcolor_003" align="left" >

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td>
                <?php
                foreach ($this->FG_FILTER_SEARCH_FORM_SELECT as $selects) {
                ?>
                    <select NAME="<?php echo $selects[2]?>" size="1" class="form_input_select">
                        <option value=''><?php echo $selects[0]?></option>
                <?php
                     foreach ($selects[1] as $recordset) {
                ?>
                        <option class=input value='<?php echo $recordset[0]?>'  <?php if ($processed[$selects[2]]==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]; if (strlen($recordset[2])>0) echo ' - '.$recordset[2]; ?></option>
                <?php 	 }
                ?>
                    </select>
                <?php
                }
                ?>
                </td>
                </tr>
                </table></td>
            </tr>
            <?php } ?>
            <tr>
                <td class="bgcolor_004" align="left"> </td>
                <td class="bgcolor_005" align="center">
                    <input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path_Main;?>/button-search.gif" />
                    <?php if (isset($_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME]) && strlen($_SESSION[$this->FG_FILTER_SEARCH_SESSION_NAME])>10 ) { ?>
                        - <a href="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?cancelsearch=true"><font color="red"><b><img src="<?php echo KICON_PATH; ?>/button_cancel.gif" height="16"> Cancel Search</b></font></a>&nbsp;
                        <?php if ($this -> FG_FILTER_SEARCH_DELETE_ALL) { ?>
                            - <a href="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>?deleteselected=true" onclick="return confirm('<?php echo "Are you sure to delete ".$this -> FG_NB_RECORD." selected records?";?>');"><font color="red"><b>Delete All</b></font></a>
                        <?php } ?>
                    <?php } ?>
                  </td>
            </tr>
        </tbody></table>
    </FORM>
</center>

<!-- ** ** ** ** ** End - Part for the research ** ** ** ** ** -->
<?php
}

    if ($this->FG_UPDATE_FORM) {
?>

<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->
<a href="#" target="_self"  onclick="imgidclick('img61000','div61000','kfind.gif','viewmag.gif');"><img id="img61000" src="<?php echo KICON_PATH; ?>/viewmag.gif" onmouseover="this.style.cursor='hand';" WIDTH="16" HEIGHT="16"></a>
<div id="div61000" style="display:visible;">

<br>
<center>
<b><?php echo gettext("There is");?>&nbsp;<?php echo $nb_record ?>&nbsp;<?php echo gettext("selected, use the option below if you are willing to make a batch updated of the selected cards.");?></b>
       <table cellspacing="1"  class="searchhandler_table4">
        <tbody>
        <form name="updateForm" action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)?>" method="post">
        <INPUT type="hidden" name="batchupdate" value="1">

        <tr>
          <td align="left" class="bgcolor_001">
                  <input name="check[upd_id_trunk]" type="checkbox" <?php if ($check["upd_id_trunk"]=="on") echo "checked"?>>
          </td>
          <td align="left"  class="searchhandler_table4_td1">
                <strong>1) TRUNK : </strong>
                <select NAME="upd_id_trunk" size="1" class="form_input_select">
                    <?php
                     foreach ($list_trunk as $recordset) {
                    ?>
                        <option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_id_trunk==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1].' ('.$recordset[2].')'?></option>
                    <?php 	 }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
              <input name="check[upd_idtariffplan]" type="checkbox" <?php if ($check["upd_idtariffplan"]=="on") echo "checked"?> >
          </td>
          <td align="left" class="searchhandler_table4_td1">

                  <strong>2) <?php echo gettext("RATECARD");?> :</strong>
                <select NAME="upd_idtariffplan" size="1" class="form_input_select">

                    <?php
                       foreach ($list_tariffname as $recordset) {
                    ?>
                        <option class=input value='<?php echo $recordset[0]?>'  <?php if ($upd_idtariffplan==$recordset[0]) echo 'selected="selected"'?>><?php echo $recordset[1]?></option>
                    <?php 	 }
                    ?>
                </select>
                <br/>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_buyrate]" type="checkbox" <?php if ($check["upd_buyrate"]=="on") echo "checked"?>>
                <input name="mode[upd_buyrate]" type="hidden" value="2">
          </td>
          <td align="left"  class="searchhandler_table4_td1">
                  <strong>3)&nbsp;<?php echo gettext("BUYRATE");?>&nbsp;:</strong>
                    <input class="form_input_text" name="upd_buyrate" size="10" maxlength="10" value="<?php if (isset($upd_buyrate)) echo $upd_buyrate; else echo '0';?>">
                <font class="version">
                <input type="radio" NAME="type[upd_buyrate]" value="1" <?php if ((!isset($type["upd_buyrate"]))|| ($type["upd_buyrate"]==1) ) {?>checked<?php }?>><?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_buyrate]" value="2" <?php if ($type["upd_buyrate"]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_buyrate]" value="3" <?php if ($type["upd_buyrate"]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_buyrateinitblock]" type="checkbox" <?php if ($check["upd_buyrateinitblock"]=="on") echo "checked"?>>
                <input name="mode[upd_buyrateinitblock]" type="hidden" value="2">
          </td>
          <td align="left" class="searchhandler_table4_td1">
                  <strong>4)&nbsp; <?php echo gettext("BUYRATEINITBLOCK");?>&nbsp;:</strong>
                    <input class="form_input_text" name="upd_buyrateinitblock" size="10" maxlength="10" value="<?php if (isset($upd_buyrateinitblock)) echo $upd_buyrateinitblock; else echo '0';?>">
                <font class="version">
                <input type="radio" NAME="type[upd_buyrateinitblock]" value="1" <?php if ((!isset($type["upd_buyrateinitblock"]))|| ($type["upd_buyrateinitblock"]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_buyrateinitblock]" value="2" <?php if ($type["upd_buyrateinitblock"]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_buyrateinitblock]" value="3" <?php if ($type["upd_buyrateinitblock"]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>

        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_buyrateincrement]" type="checkbox" <?php if ($check["upd_buyrateincrement"]=="on") echo "checked"?>>
                <input name="mode[upd_buyrateincrement]" type="hidden" value="2">
          </td>
          <td align="left"  class="searchhandler_table4_td1">
                  <strong>5) <?php echo gettext("BUYRATEINCREMENT");?>&nbsp;:</strong>
                    <input class="form_input_text" name="upd_buyrateincrement" size="10" maxlength="10"  value="<?php if (isset($upd_buyrateincrement)) echo $upd_buyrateincrement; else echo '0';?>">
                <font class="version">
                <input type="radio" NAME="type[upd_buyrateincrement]" value="1" <?php if ((!isset($type["upd_buyrateincrement"]))|| ($type["upd_buyrateincrement"]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_buyrateincrement]" value="2" <?php if ($type["upd_buyrateincrement"]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_buyrateincrement]" value="3" <?php if ($type["upd_buyrateincrement"]==3) {?>checked<?php }?>>  <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1" >
                  <input name="check[upd_rateinitial]" type="checkbox" <?php if ($check["upd_rateinitial"]=="on") echo "checked"?>>
                <input name="mode[upd_rateinitial]" type="hidden" value="2">
          </td>
          <td align="left" class="searchhandler_table4_td1">

                <strong>6)&nbsp;<?php echo gettext("RATE INITIAL");?>&nbsp;:</strong>
                     <input class="form_input_text" name="upd_rateinitial" size="10" maxlength="10"  value="<?php if (isset($upd_rateinitial)) echo $upd_rateinitial; else echo '0';?>" >
                <font class="version">
                <input type="radio" NAME="type[upd_rateinitial]" value="1" <?php if ((!isset($type[upd_rateinitial]))|| ($type[upd_rateinitial]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_rateinitial]" value="2" <?php if ($type[upd_rateinitial]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_rateinitial]" value="3" <?php if ($type[upd_rateinitial]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_initblock]" type="checkbox" <?php if ($check["upd_initblock"]=="on") echo "checked"?>>
                <input name="mode[upd_initblock]" type="hidden" value="2">
          </td>
          <td align="left" class="searchhandler_table4_td1">

                <strong>7)&nbsp;<?php echo gettext("MIN DURATION");?>&nbsp;:</strong>
                     <input class="form_enter" name="upd_initblock" size="10" maxlength="10" style="border: 2px inset rgb(204, 51, 0);" value="<?php if (isset($upd_initblock)) echo $upd_initblock; else echo '0';?>" >
                <font class="version">
                <input type="radio" NAME="type[upd_initblock]" value="1" <?php if ((!isset($type[upd_initblock]))|| ($type[upd_initblock]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_initblock]" value="2" <?php if ($type[upd_initblock]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_initblock]" value="3" <?php if ($type[upd_initblock]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_billingblock]" type="checkbox" <?php if ($check["upd_billingblock"]=="on") echo "checked"?>>
                <input name="mode[upd_billingblock]" type="hidden" value="2">
          </td>
          <td align="left" class="searchhandler_table4_td1">

                <strong>8)&nbsp;<?php echo gettext("BILLINGBLOCK");?>&nbsp;:</strong>
                     <input class="form_input_text" name="upd_billingblock" size="10" maxlength="10" style="border: 2px inset rgb(204, 51, 0);" value="<?php if (isset($upd_billingblock)) echo $upd_billingblock; else echo '0';?>" >
                <font class="version">
                <input type="radio" NAME="type[upd_billingblock]" value="1" <?php if ((!isset($type[upd_billingblock]))|| ($type[upd_billingblock]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_billingblock]" value="2" <?php if ($type[upd_billingblock]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_billingblock]" value="3" <?php if ($type[upd_billingblock]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_connectcharge]" type="checkbox" <?php if ($check["upd_connectcharge"]=="on") echo "checked"?>>
                <input name="mode[upd_connectcharge]" type="hidden" value="2">
          </td>
          <td align="left"  class="searchhandler_table4_td1">

                <strong>9)&nbsp;<?php echo gettext("CONNECTCHARGE");?>&nbsp;:</strong>
                     <input class="form_input_text" name="upd_connectcharge" size="10" maxlength="10" style="border: 2px inset rgb(204, 51, 0);" value="<?php if (isset($upd_connectcharge)) echo $upd_connectcharge; else echo '0';?>" >
                <font class="version">
                <input type="radio" NAME="type[upd_connectcharge]" value="1" <?php if ((!isset($type[upd_connectcharge]))|| ($type[upd_connectcharge]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_connectcharge]" value="2" <?php if ($type[upd_connectcharge]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_connectcharge]" value="3" <?php if ($type[upd_connectcharge]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
        <tr>
          <td align="left" class="searchhandler_table4_td1">
                  <input name="check[upd_disconnectcharge]" type="checkbox" <?php if ($check["upd_disconnectcharge"]=="on") echo "checked"?>>
                <input name="mode[upd_disconnectcharge]" type="hidden" value="2">
          </td>
          <td align="left" class="searchhandler_table4_td1">

                <strong>10)&nbsp;<?php echo gettext("DISCONNECTCHARGE");?>&nbsp;:</strong>
                     <input class="form_input_text" name="upd_disconnectcharge" size="10" maxlength="10" style="border: 2px inset rgb(204, 51, 0);" value="<?php if (isset($upd_disconnectcharge)) echo $upd_disconnectcharge; else echo '0';?>" >
                <font class="version">
                <input type="radio" NAME="type[upd_disconnectcharge]" value="1" <?php if ((!isset($type[upd_disconnectcharge]))|| ($type[upd_disconnectcharge]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>
                <input type="radio" NAME="type[upd_disconnectcharge]" value="2" <?php if ($type[upd_disconnectcharge]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                <input type="radio" NAME="type[upd_disconnectcharge]" value="3" <?php if ($type[upd_disconnectcharge]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                </font>
            </td>
        </tr>
<tr>
          <td align="left" class="searchhandler_table4_td1">
                                <input name="check[upd_disconnectcharge_after]" type="checkbox" <?php if ($check["upd_disconnectcharge_after"]=="on") echo "checked"?>>
                                <input name="mode[upd_disconnectcharge_after]" type="hidden" value="2">
                  </td>
                  <td align="left" class="searchhandler_table4_td1">

                                <strong>11)&nbsp;<?php echo gettext("DISCONNECT CHARGE THRESHOLD");?>&nbsp;:</strong>
                                        <input class="form_input_text" name="upd_disconnectcharge_after" size="10" maxlength="10" style="border: 2px inset rgb(204, 51, 0);" value="<?php if (isset($upd_disconnectcharge_after)) echo $upd_disconnectcharge_after; else echo '0';?>" >
                                <font class="version">
                                <input type="radio" NAME="type[upd_disconnectcharge_after]" value="1" <?php if ((!isset($type[upd_disconnectcharge_after]))|| ($type[upd_disconnectcharge_after]==1) ) {?>checked<?php }?>> <?php echo gettext("Equal");?>

                                <input type="radio" NAME="type[upd_disconnectcharge_after]" value="2" <?php if ($type[upd_disconnectcharge_after]==2) {?>checked<?php }?>> <?php echo gettext("Add");?>
                                <input type="radio" NAME="type[upd_disconnectcharge_after]" value="3" <?php if ($type[upd_disconnectcharge_after]==3) {?>checked<?php }?>> <?php echo gettext("Substract");?>
                                </font>
                        </td>
                </tr>

        <tr>
            <td align="right" class="searchhandler_table4_td1">
            </td>
             <td align="right" class="searchhandler_table4_td1">
                <input class="form_input_button" value="<?php gettext(" BATCH UPDATE RATECARD ");?>" type="submit">
            </td>
        </tr>

        </form>
      </table>
</center>
<!-- ** ** ** ** ** Part for the Update ** ** ** ** ** -->

<?php
}
