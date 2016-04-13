<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2015 - Star2billing S.L.
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

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_CRONT_SERVICE)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('id', 'displayheader', 'displayfooter', 'popup_select'));

$FG_DEBUG = 0;

$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F2EE";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FCFBFB";

$FG_TABLE_COL=array();

$FG_TABLE_COL[]=array (gettext("DATE"), "date", "30%", "center", "sort", "30", "", "", "", "", "", "display_dateformat");
$FG_TABLE_COL[]=array (gettext("ACCOUNT DEDUCTED"), "totalcardperform", "20%", "center", "sort");
$FG_TABLE_COL[]=array (gettext("TOTAL CREDIT"), "totalcredit", "20%", "center", "sort");

$FG_NB_TABLE_COL = count($FG_TABLE_COL);

if (!isset ($current_page) || ($current_page == "")) {
    $current_page = 0;
}

$DBHandle = DbConnect();

// SERVICE INFO
$QUERY = "SELECT id, name, numberofrun, datelastrun, totalcredit, totalcardperform from cc_service WHERE id='$id'";
$res = $DBHandle->Execute($QUERY);
if ($res) {
    $num = $res->RecordCount();

    for ($i = 0; $i < $num; $i++) {
        $list_service[] = $res->fetchRow();
    }
}

// LIST REFILL
$QUERY = "SELECT  t3.daterun, t3.totalcardperform, t3.totalcredit from cc_service_report as t3 WHERE t3.cc_service_id='$id'";

if ($A2B->config["database"]['dbtype'] == 'postgres')
    $QUERY .= " ORDER BY t3.id DESC LIMIT 25 OFFSET 0";
else
    $QUERY .= " ORDER BY t3.id DESC LIMIT 0, 25";

if ($FG_DEBUG > 0)
    echo $QUERY;

$res = $DBHandle->Execute($QUERY);
if ($res) {
    $num = $res->RecordCount();

    for ($i = 0; $i < $num; $i++) {
        $list[] = $res->fetchRow();
    }
}

$smarty->display('main.tpl');

?>

<center><b><?php echo gettext("SERVICE NAME")?>&nbsp; :	<?php echo $list_service [0][1] ?></b>
<br>
<?php echo gettext("EXECUTED ")." : ".$list_service [0][2].' '.gettext("TIME(S)") ?>
<?php echo " <br>".gettext("Last executed date")." : ".$list_service [0][3] ?>
<?php echo " <br>".gettext("TOTAL CREDIT")." : ".$list_service [0][4].' '.BASE_CURRENCY ?>
<?php echo " - ".gettext("TOTAL ACCOUNT DEDUCTED")." : ".$list_service [0][5] ?>

</center>
      <table width="100%">
      <TR>
          <TD style="border-bottom: medium dotted #667766">&nbsp; </TD>
        </TR>
      </table>

       <table cellPadding=2 cellSpacing=2 width="100%" align=center><tr><td align=center>
      <?php
                $color="red";
                $ttitle=gettext("SERVICE REPORT");

                  if ((count($list )>0) && is_array($list )) {
      ?>

      <div class="pscroll">
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
        <TR>
          <TD>
          <TABLE border=0 cellPadding=1 cellSpacing=1 width="100%">
                   <TR bgcolor=<?php echo $color?>>
                      <TD align=center colspan=<?php echo $FG_NB_TABLE_COL?>> <?php echo $ttitle?></TD>
                </TR>

                <TR class="form_head">
                  <?php for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {
                    ?>
                  <TD width="<?php echo $FG_TABLE_COL[$i][2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px">
                    <strong>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
                    <span class="white_link"><?php  } ?>
                    <?php echo $FG_TABLE_COL[$i][0]?>
                    <?php if ($order==$FG_TABLE_COL[$i][1] && $sens=="ASC") {?>
                    &nbsp;<img src="<?php echo Images_Path;?>/icon_up_12x12.GIF" width="12" height="12" border="0">
                    <?php } elseif ($order==$FG_TABLE_COL[$i][1] && $sens=="DESC") {?>
                    &nbsp;<img src="<?php echo Images_Path;?>/icon_down_12x12.GIF" width="12" height="12" border="0">
                    <?php }?>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
                    </span>
                    <?php }?>
                    </strong></TD>
                   <?php } ?>
                </TR>

                <?php

                       $ligne_number=0;

                       foreach ($list as $recordset) {
                         $ligne_number++;
                ?>

                    <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>"  onmouseover="bgColor='#FFDEA6'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>'">

                          <?php
                              for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {

                                if ($FG_TABLE_COL[$i][6]=="lie") {

                                    $instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
                                    $sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);
                                    $select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null);

                                    $field_list_sun = preg_split('/,/',$FG_TABLE_COL[$i][8]);
                                    $record_display = $FG_TABLE_COL[$i][10];

                                    for ($l=1;$l<=count($field_list_sun);$l++) {
                                        $record_display = str_replace("%$l", $select_list[0][$l-1], $record_display);
                                    }

                                } elseif ($FG_TABLE_COL[$i][6]=="list") {

                                    $select_list = $FG_TABLE_COL[$i][7];
                                    $record_display = $select_list[$recordset[$i]][0];

                                } else {
                                        $record_display = $recordset[$i];
                                }

                                if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ) {
                                    $record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."...";

                                }
                          ?>
                          <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php
                         $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2];
                         if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1) {
                                 call_user_func($FG_TABLE_COL[$i][11], $record_display);
                         } else {
                                 echo stripslashes($record_display);
                         }
                         ?></TD>
                         <?php  } ?>
                         <?php  if ($_SESSION["is_admin"]==1 && 1==3) { 	?>
                         <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>" class=tableBody><?php  echo findkey($list_reseller, $recordset[2]);?></TD>
                         <?php  } ?>

                    </TR>
                <?php
                     }//foreach ($list as $recordset)
                     if ($ligne_number < $FG_LIMITE_DISPLAY) {
                         $ligne_number++;
                ?>
                    <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number%2]?>">
                          <?php for ($i=0;$i<$FG_NB_TABLE_COL-1;$i++) {
                          ?>
                          <TD vAlign=top class=tableBody>&nbsp;</TD>
                          <?php  } ?>
                          <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>
                    </TR>

                <?php
                     } //END_WHILE

                 ?>
            </TABLE></td>
        </tr>
        <TR>
            <TD>
            </TD>
        </TR>
      </table>
 </div>

<?php
    } else {
?>

    <br/></br>
    <table width="100%" border="0" align="center" class="bgcolor_006">
        <tr>
          <td align="center">
            <?php echo gettext("NOTHING FOUND")?>&nbsp; !<br/>
        </td>
        </tr>
    </table>

<br/><br/>

</div>
<?php
}//end_if
?>

</td></tr></table>

<?php

if ($displayfooter!="0") {
    $smarty->display('footer.tpl');
}
