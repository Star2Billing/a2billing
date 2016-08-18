<?php
/* * *****************************************************************************
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Roman Davydov (http://www.openvoip.co)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * ***************************************************************************** */

include '../lib/admin.defines.php';
include '../lib/admin.module.access.php';
include '../lib/admin.smarty.php';

if (!has_rights(ACX_CALL_REPORT)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

getpost_ifset(array('posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'fromtime', 'totime', 'fromstatsday_hour', 'tostatsday_hour', 'fromstatsday_min', 'tostatsday_min', 'cidtype', 'order', 'sens', 'cid'));

if (!isset($current_page) || ($current_page == "")) {
    $current_page = 0;
}

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME = "cc_auth_failures_log t1 LEFT JOIN cc_card t2 ON t1.id_card = t2.id";

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR[] = "#F2F8FF";

$yesno = array();
$yesno["1"] = array("Yes", "1");
$yesno["0"] = array("No", "0");

$DBHandle = DbConnect();

$FG_TABLE_COL = array();
$FG_TABLE_COL[] = array(gettext("DATE"), "created_at", "15%", "center", "SORT", "100");
$FG_TABLE_COL[] = array(gettext("CALLER ID"), "cid", "10%", "center", "SORT", "100");
$FG_TABLE_COL[] = array(gettext("REASON"), "reason", "15%", "center", "SORT", "100");
$FG_TABLE_COL[] = array(gettext("LOG"), "dump", "50%", "left", "SORT", "", "", "", "", "", "", "display_pre");
$FG_TABLE_COL[] = array(gettext("CUSTOMER"), "username", "10%", "center", "SORT", "30", "", "", "", "", "", "linktocustomer");

$FG_TABLE_DEFAULT_ORDER = "created_at";
$FG_TABLE_DEFAULT_SENS = "DESC";

// This Variable store the argument for the SQL query
$FG_COL_QUERY = 't1.created_at as created_at, t1.cid as cid, t1.reason as reason, t1.dump as dump, t2.username as username';

$FG_LIMITE_DISPLAY = 1000;
$FG_NB_TABLE_COL = count($FG_TABLE_COL);
$FG_EDITION = false;
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;

$FG_HTML_TABLE_TITLE = gettext(" - AUTH FAIL REPORT - ");
$FG_HTML_TABLE_WIDTH = '80%';

if ($FG_DEBUG == 3)
    echo "<br>Table : $FG_TABLE_NAME  	- 	Col_query : $FG_COL_QUERY";
$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
$instance_table_graph = new Table($FG_TABLE_NAME);

if (is_null($order) || is_null($sens)) {
    $order = $FG_TABLE_DEFAULT_ORDER;
    $sens = $FG_TABLE_DEFAULT_SENS;
}

if ($posted == 1) {
    $SQLcmd = '';
    $SQLcmd = do_field($SQLcmd, 'cid', 'cid');
}

$date_clause = '';
normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
if ($fromday && isset($fromstatsday_sday) && isset($fromstatsmonth_sday)) {
    if ($fromtime) {
        $date_clause.=" AND t1.created_at >= '$fromstatsmonth_sday-$fromstatsday_sday $fromstatsday_hour:$fromstatsday_min'";
    } else {
        $date_clause.=" AND t1.created_at >= '$fromstatsmonth_sday-$fromstatsday_sday'";
    }
}
if ($today && isset($tostatsday_sday) && isset($tostatsmonth_sday)) {
    if ($totime) {
        $date_clause.=" AND t1.created_at <= '$tostatsmonth_sday-" . sprintf("%02d", intval($tostatsday_sday)/* +1 */) . " $tostatsday_hour:$tostatsday_min:59'";
    } else {
        $date_clause.=" AND t1.created_at <= '$tostatsmonth_sday-" . sprintf("%02d", intval($tostatsday_sday)/* +1 */) . " 23:59:59'";
    }
}

if (strpos($SQLcmd, 'WHERE') > 0) {
    $FG_TABLE_CLAUSE = substr($SQLcmd, 6) . $date_clause;
} elseif (strpos($date_clause, 'AND') > 0) {
    $FG_TABLE_CLAUSE = substr($date_clause, 5);
}

if (!isset($FG_TABLE_CLAUSE) || strlen($FG_TABLE_CLAUSE) == 0) {
    $cc_yearmonth = sprintf("%04d-%02d-%02d", date("Y"), date("n"), date("d"));
    $FG_TABLE_CLAUSE = " t1.created_at >= '$cc_yearmonth'";
}

$list = $instance_table->Get_list($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page * $FG_LIMITE_DISPLAY);

$smarty->display('main.tpl');

?>

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
<div align="center">
    <FORM METHOD=POST name="myForm" ACTION="<?php echo $PHP_SELF ?>?s=1&t=0&order=<?php echo $order ?>&sens=<?php echo $sens ?>&current_page=<?php echo $current_page ?>">
        <INPUT TYPE="hidden" NAME="posted" value=1>
        <INPUT TYPE="hidden" NAME="current_page" value=0>
        <TABLE class="bar-status" width="85%" border="0" cellspacing="1" cellpadding="2" align="center">
            <tr>
                <td align="left" class="bgcolor_004">
                    <font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("DATE"); ?></font>
                </td>
                <td align="left" class="bgcolor_005">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr><td class="fontstyle_searchoptions">
                                <input type="checkbox" name="fromday" value="true" <?php if ($fromday) { ?>checked<?php } ?>> <?php echo gettext("From"); ?> :
                                <select name="fromstatsday_sday" class="form_input_select">
                                    <?php
                                    for ($i = 1; $i <= 31; $i++) {
                                        if ($fromstatsday_sday == sprintf("%02d", $i))
                                            $selected = "selected";
                                        else
                                            $selected = "";
                                        echo '<option value="' . sprintf("%02d", $i) . "\"$selected>" . sprintf("%02d", $i) . '</option>';
                                    }
                                    ?>
                                </select>
                                <select name="fromstatsmonth_sday" class="form_input_select">
                                    <?php
                                    $monthname = array(gettext("January"), gettext("February"), gettext("March"), gettext("April"), gettext("May"), gettext("June"), gettext("July"), gettext("August"), gettext("September"), gettext("October"), gettext("November"), gettext("December"));
                                    $year_actual = date("Y");
                                    for ($i = $year_actual; $i >= $year_actual - 1; $i--) {
                                        if ($year_actual == $i) {
                                            $monthnumber = date("n") - 1; // Month number without lead 0.
                                        } else {
                                            $monthnumber = 11;
                                        }
                                        for ($j = $monthnumber; $j >= 0; $j--) {
                                            $month_formated = sprintf("%02d", $j + 1);
                                            if ($fromstatsmonth_sday == "$i-$month_formated")
                                                $selected = "selected";
                                            else
                                                $selected = "";
                                            echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <br/>
                                <input type="checkbox" name="fromtime" value="true" <?php if ($fromtime) { ?>checked<?php } ?>>
                                <?php echo gettext("Time :") ?>
                                <select name="fromstatsday_hour" class="form_input_select">
                                    <?php
                                    for ($i = 0; $i <= 23; $i++) {
                                        if ($fromstatsday_hour == sprintf("%02d", $i)) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                        echo '<option value="' . sprintf("%02d", $i) . "\"$selected>" . sprintf("%02d", $i) . '</option>';
                                    }
                                    ?>
                                </select>
                                :
                                <select name="fromstatsday_min" class="form_input_select">
                                    <?php
                                    for ($i = 0; $i < 60; $i = $i + 5) {
                                        if ($fromstatsday_min == sprintf("%02d", $i)) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                        echo '<option value="' . sprintf("%02d", $i) . "\"$selected>" . sprintf("%02d", $i) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td><td class="fontstyle_searchoptions">
                                <input type="checkbox" name="today" value="true" <?php if ($today) { ?>checked<?php } ?>>
                                <?php echo gettext("To"); ?>  :
                                <select name="tostatsday_sday" class="form_input_select">
                                    <?php
                                    for ($i = 1; $i <= 31; $i++) {
                                        if ($tostatsday_sday == sprintf("%02d", $i)) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                        echo '<option value="' . sprintf("%02d", $i) . "\"$selected>" . sprintf("%02d", $i) . '</option>';
                                    }
                                    ?>
                                </select>
                                <select name="tostatsmonth_sday" class="form_input_select">
                                    <?php
                                    $year_actual = date("Y");
                                    for ($i = $year_actual; $i >= $year_actual - 1; $i--) {
                                        if ($year_actual == $i) {
                                            $monthnumber = date("n") - 1; // Month number without lead 0.
                                        } else {
                                            $monthnumber = 11;
                                        }
                                        for ($j = $monthnumber; $j >= 0; $j--) {
                                            $month_formated = sprintf("%02d", $j + 1);
                                            if ($tostatsmonth_sday == "$i-$month_formated")
                                                $selected = "selected";
                                            else
                                                $selected = "";
                                            echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <br/>
                                <input type="checkbox" name="totime" value="true" <?php if ($totime) { ?>checked<?php } ?>>
                                <?php echo gettext("Time :") ?>
                                <select name="tostatsday_hour" class="form_input_select">
                                    <?php
                                    for ($i = 0; $i <= 23; $i++) {
                                        if ($tostatsday_hour == sprintf("%02d", $i)) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                        echo '<option value="' . sprintf("%02d", $i) . "\"$selected>" . sprintf("%02d", $i) . '</option>';
                                    }
                                    ?>
                                </select>
                                :
                                <select name="tostatsday_min" class="form_input_select">
                                    <?php
                                    for ($i = 0; $i < 60; $i = $i + 5) {
                                        if ($tostatsday_min == sprintf("%02d", $i)) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        }
                                        echo '<option value="' . sprintf("%02d", $i) . "\"$selected>" . sprintf("%02d", $i) . '</option>';
                                    }
                                    ?>
                                </select>
                            </td></tr></table>
                </td>
            </tr>
            <tr>
                <td class="bgcolor_002" align="left">
                    <font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext("CALLER ID"); ?></font>
                </td>
                <td class="bgcolor_003" align="left">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr><td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="cid" value="<?php echo $cid ?>" class="form_input_text"></td>
                            <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="cidtype" value="1" <?php if ((!isset($cidtype)) || ($cidtype == 1)) { ?>checked<?php } ?>><?php echo gettext("Exact"); ?></td>
                            <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="cidtype" value="2" <?php if ($cidtype == 2) { ?>checked<?php } ?>><?php echo gettext("Begins with"); ?></td>
                            <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="cidtype" value="3" <?php if ($cidtype == 3) { ?>checked<?php } ?>><?php echo gettext("Contains"); ?></td>
                            <td class="fontstyle_searchoptions" align="center" ><input type="radio" NAME="cidtype" value="4" <?php if ($cidtype == 4) { ?>checked<?php } ?>><?php echo gettext("Ends with"); ?></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td class="bgcolor_004" align="left" > </td>
                <td class="bgcolor_005" align="center" >
                    <input type="image"  name="image16" align="top" border="0" src="<?php echo Images_Path; ?>/button-search.gif"/>
                </td>
            </tr>
        </table>
    </FORM>

    <!-- ** ** ** ** ** Part to display data ** ** ** ** ** -->

    <br/>
    <center>
        <?php echo gettext("Number of failed auth attempts") . ': ' . (is_array($list) ? count($list) : 0 ); ?>
    </center>
    <br/>

    <table width="<?php echo $FG_HTML_TABLE_WIDTH ?>" border="0" align="center" cellpadding="0" cellspacing="0">
        <TR bgcolor="#ffffff">
            <TD  class="bgcolor_021" height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px">
                <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                    <TBODY>
                        <TR>
                            <TD><SPAN  class="fontstyle_003"><?php echo $FG_HTML_TABLE_TITLE ?></SPAN></TD>
                        </TR>
                    </TBODY>
                </TABLE>
            </TD>
        </TR>
        <TR>
            <TD>
                <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                    <TR  class="bgcolor_008">
                        <TD width="<?php echo $FG_ACTION_SIZE_COLUMN ?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"></TD>

                        <?php
                        if (is_array($list) && count($list) > 0) {

                            for ($i = 0; $i < $FG_NB_TABLE_COL; $i++) {
                                ?>

                                <TD width="<?php echo $FG_TABLE_COL[$i][2] ?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px">
                            <center><strong>
                                    <?php if (strtoupper($FG_TABLE_COL[$i][4]) == "SORT") { ?>
                                        <a href="<?php
                                        echo $PHP_SELF . "?$customer&s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=" . $FG_TABLE_COL[$i][1] . "&sens=";
                                        if ($sens == "ASC") {
                                            echo"DESC";
                                        } else {
                                            echo"ASC";
                                        }
                                        echo "&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&cidtype=$cidtype&cid=$cid";
                                        ?>">
                                            <span class="liens"><?php } ?>
                                            <?php echo $FG_TABLE_COL[$i][0] ?>
                                            <?php if ($order == $FG_TABLE_COL[$i][1] && $sens == "ASC") { ?>
                                                &nbsp;<img src="<?php echo Images_Path; ?>/icon_up_12x12.GIF" width="12" height="12" border="0">
                                            <?php } elseif ($order == $FG_TABLE_COL[$i][1] && $sens == "DESC") { ?>
                                                &nbsp;<img src="<?php echo Images_Path; ?>/icon_down_12x12.GIF" width="12" height="12" border="0">
                                            <?php } ?>
                                    <?php if (strtoupper($FG_TABLE_COL[$i][4]) == "SORT") { ?>
                                            </span></a>
                    <?php } ?>
                                </strong></center></TD>
            <?php } ?>
            </TR>
            <?php
            $ligne_number = 0;
            //print_r($list);
            foreach ($list as $recordset) {
                $ligne_number++;
                ?>

                <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number % 2] ?>"  onMouseOver="bgColor = '#C4FFD7'" onMouseOut="bgColor = '<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number % 2] ?>'">
                    <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3] ?>" class=tableBody><?php echo $ligne_number + $current_page * $FG_LIMITE_DISPLAY . ".&nbsp;"; ?></TD>

                    <?php for ($i = 0; $i < $FG_NB_TABLE_COL; $i++) { ?>

                        <?php
                        if ($FG_TABLE_COL[$i][6] == "lie") {

                            $instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
                            $sub_clause = str_replace("%id", $recordset[$i], $FG_TABLE_COL[$i][9]);
                            $select_list = $instance_sub_table->Get_list($DBHandle, $sub_clause, null, null, null, null, null, null);

                            $field_list_sun = preg_split('/,/', $FG_TABLE_COL[$i][8]);
                            $record_display = $FG_TABLE_COL[$i][10];

                            for ($l = 1; $l <= count($field_list_sun); $l++) {
                                $record_display = str_replace("%$l", $select_list[0][$l - 1], $record_display);
                            }
                        } elseif ($FG_TABLE_COL[$i][6] == "list") {
                            $select_list = $FG_TABLE_COL[$i][7];
                            $record_display = $select_list[$recordset[$i]][0];
                        } else {
                            $record_display = $recordset[$i];
                        }

                        if (is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])) {
                            $record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5] - 3) . "";
                        }
                        ?>
                        <TD vAlign=top align="<?php echo $FG_TABLE_COL[$i][3] ?>" class=tableBody><?php
                            if (isset($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11]) > 1) {
                                call_user_func($FG_TABLE_COL[$i][11], $record_display);
                            } else {
                                echo stripslashes($record_display);
                            }
                            ?></TD>
        <?php } ?>

                </TR>
                <?php
            }//foreach ($list as $recordset)
            if ($ligne_number < $FG_LIMITE_DISPLAY)
                $ligne_number_end = $ligne_number + 2;
            while ($ligne_number < $ligne_number_end) {
                $ligne_number++;
                ?>
                <TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR[$ligne_number % 2] ?>">
                    <?php for ($i = 0; $i < $FG_NB_TABLE_COL; $i++) {
                        ?>
                        <TD vAlign=top class=tableBody>&nbsp;</TD>
        <?php } ?>
                    <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>
                </TR>

                <?php
            } //END_WHILE
        } else {
            echo gettext("No data found !!!");
        }//end_if
        ?>
    </TABLE></td>
</tr>
<TR bgcolor="#ffffff">
    <TD  class="bgcolor_005" height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px">
        <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
            <TBODY>
                <TR>
                    <TD align="right"><SPAN  class="fontstyle_003" >
                        </SPAN>
                    </TD>
            </TBODY>
        </TABLE></TD>
</TR>
</table>

</div>

<?php
$smarty->display('footer.tpl');
