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

if (! has_rights ( ACX_CALL_REPORT )) {
    Header ( "HTTP/1.0 401 Unauthorized" );
    Header ( "Location: PP_error.php?c=accessdenied" );
    die ();
}

getpost_ifset ( array ('customer', 'sellrate', 'buyrate', 'entercustomer','entercustomer_num', 'enterprovider', 'entertariffgroup', 'entertrunk', 'enterratecard', 'posted', 'Period', 'frommonth', 'fromstatsmonth', 'tomonth', 'tostatsmonth', 'fromday', 'fromstatsday_sday', 'fromstatsmonth_sday', 'today', 'tostatsday_sday', 'tostatsmonth_sday', 'fromtime', 'totime', 'fromstatsday_hour', 'tostatsday_hour', 'fromstatsday_min', 'tostatsday_min', 'dsttype', 'srctype', 'dnidtype', 'clidtype', 'channel', 'resulttype', 'stitle', 'atmenu', 'current_page', 'order', 'sens', 'dst', 'src', 'dnid', 'clid', 'choose_currency', 'terminatecauseid', 'choose_calltype', 'download', 'file') );

if (($download == "file") && $file) {

    if (strpos($file, '/') !== false) exit;

    $value_de = base64_decode ( $file );
    $pos = strpos($value_de, '../');
    if ($pos === false) {
        $dl_full = MONITOR_PATH . "/" . $value_de;
        $dl_name = $value_de;

        if (!file_exists ($dl_full)) {
            echo gettext ("ERROR: Cannot download file " . $dl_full . ", it does not exist.<br>");
            exit ();
        }

        header ( "Content-Type: application/octet-stream" );
        header ( "Content-Disposition: attachment; filename=$dl_name" );
        header ( "Content-Length: " . filesize ( $dl_full ) );
        header ( "Accept-Ranges: bytes" );
        header ( "Pragma: no-cache" );
        header ( "Expires: 0" );
        header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
        header ( "Content-transfer-encoding: binary" );

        @readfile ( $dl_full );
        exit ();
    }
}

$dialstatus_list = Constants::getDialStatusList ();

if (! isset ( $current_page ) || ($current_page == "")) {
    $current_page = 0;
}

// this variable specifie the debug type (0 => nothing, 1 => sql result, 2 => boucle checking, 3 other value checking)
$FG_DEBUG = 0;

// The variable FG_TABLE_NAME define the table name to use
$FG_TABLE_NAME = "cc_call t1 LEFT OUTER JOIN cc_trunk t3 ON t1.id_trunk = t3.id_trunk LEFT OUTER JOIN cc_ratecard t4 ON t1.id_ratecard = t4.id";

// THIS VARIABLE DEFINE THE COLOR OF THE HEAD TABLE
$FG_TABLE_ALTERNATE_ROW_COLOR [] = "#FFFFFF";
$FG_TABLE_ALTERNATE_ROW_COLOR [] = "#F2F8FF";

$yesno = array ();
$yesno ["1"] = array ("Yes", "1" );
$yesno ["0"] = array ("No", "0" );

// 0 = NORMAL CALL ; 1 = VOIP CALL (SIP/IAX) ; 2= DIDCALL + TRUNK ; 3 = VOIP CALL DID ; 4 = CALLBACK call
$list_calltype = array ();
$list_calltype ["0"] = array (gettext("STANDARD"), "0" );
$list_calltype ["1"] = array (gettext("SIP/IAX"), "1" );
$list_calltype ["2"] = array (gettext("DIDCALL"), "2" );
$list_calltype ["3"] = array (gettext("DID_VOIP"), "3" );
$list_calltype ["4"] = array (gettext("CALLBACK"), "4" );
$list_calltype ["5"] = array (gettext("PREDICT"), "5" );
$list_calltype ["6"] = array (gettext("AUTO DIALER"), "6" );
$list_calltype ["7"] = array (gettext("DID-ALEG"), "7" );

$FG_TABLE_DEFAULT_ORDER = "t1.starttime";
$FG_TABLE_DEFAULT_SENS = "DESC";

$DBHandle = DbConnect ();

$FG_TABLE_COL = array ();
$FG_TABLE_COL [] = array (gettext ( "Date" ), "starttime", "10%", "center", "SORT", "19", "", "", "", "", "", "display_dateformat" );
$FG_TABLE_COL [] = array (gettext ( "CallerID" ), "src", "7%", "center", "SORT", "30" );
$FG_TABLE_COL [] = array (gettext ( "DNID" ), "dnid", "7%", "center", "SORT", "30" );
$FG_TABLE_COL [] = array (gettext ( "Phone Number" ), "calledstation", "10%", "center", "SORT", "30", "", "", "", "", "", "" );
$FG_TABLE_COL [] = array (gettext ( "Destination" ), "dest","10%", "center", "SORT", "15", "lie", "cc_prefix", "destination,prefix", "prefix='%id'", "%1" );
$FG_TABLE_COL [] = array (gettext ( "Buy Rate" ), "buyrate", "6%", "center", "SORT", "30", "", "", "", "", "", "display_2bill" );
$FG_TABLE_COL [] = array (gettext ( "Sell Rate" ), "rateinitial", "6%", "center", "SORT", "30", "", "", "", "", "", "display_2bill" );
$FG_TABLE_COL [] = array (gettext ( "Duration" ), "sessiontime", "5%", "center", "SORT", "30", "", "", "", "", "", "display_minute" );
$FG_TABLE_COL [] = array (gettext ( "Account" ), "card_id", "6%", "center", "sort", "", "lie_link", "cc_card", "username,id", "id='%id'", "%1", "", "A2B_entity_card.php" );
$FG_TABLE_COL [] = array (gettext ( "Trunk" ), "trunkcode", "6%", "center", "SORT", "30" );
$FG_TABLE_COL [] = array ('<acronym title="' . gettext ( "Terminate Cause" ) . '">' . gettext ( "TC" ) . '</acronym>', "terminatecauseid", "7%", "center", "SORT", "", "list", $dialstatus_list );
$FG_TABLE_COL [] = array (gettext ( "CallType" ), "sipiax", "6%", "center", "SORT", "", "list", $list_calltype );
$FG_TABLE_COL [] = array (gettext ( "Buy" ), "buycost", "7%", "center", "SORT", "30", "", "", "", "", "", "display_2bill" );
$FG_TABLE_COL [] = array (gettext ( "Sell" ), "sessionbill", "7%", "center", "SORT", "30", "", "", "", "", "", "display_2bill" );
$FG_TABLE_COL [] = array (gettext ( "Margin" ), "margin", "7%", "center", "SORT", "30", "", "", "", "", "", "display_2dec_percentage" );
$FG_TABLE_COL [] = array (gettext ( "Markup" ), "markup", "7%", "center", "SORT", "30", "", "", "", "", "", "display_2dec_percentage" );

if (LINK_AUDIO_FILE) {
    $FG_TABLE_COL [] = array ("", "uniqueid", "1%", "center", "", "30", "", "", "", "", "", "linkonmonitorfile" );
}

if (has_rights (ACX_DELETE_CDR)) {
    $FG_TABLE_COL [] = array ("", "id", "1%", "center", "", "30", "", "", "", "", "", "linkdelete_cdr" );
}

// This Variable store the argument for the SQL query
$FG_COL_QUERY = 't1.starttime, t1.src, t1.dnid, t1.calledstation, t1.destination AS dest, t4.buyrate, t4.rateinitial, t1.sessiontime, t1.card_id, t3.trunkcode, t1.terminatecauseid, t1.sipiax, t1.buycost, t1.sessionbill, case when t1.sessionbill!=0 then ((t1.sessionbill-t1.buycost)/t1.sessionbill)*100 else NULL end as margin,case when t1.buycost!=0 then ((t1.sessionbill-t1.buycost)/t1.buycost)*100 else NULL end as markup';

if (LINK_AUDIO_FILE) {
    $FG_COL_QUERY .= ', t1.uniqueid';
}
if (has_rights (ACX_DELETE_CDR)) {
    $FG_COL_QUERY .= ', t1.id';
}
$FG_COL_QUERY_GRAPH = 't1.callstart, t1.duration';

$FG_LIMITE_DISPLAY = 25;
$FG_NB_TABLE_COL = count ( $FG_TABLE_COL );
$FG_EDITION = true;
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
if ($FG_DELETION || $FG_EDITION)
    $FG_TOTAL_TABLE_COL ++;

$FG_HTML_TABLE_TITLE = gettext ( " - Call Logs - " );
$FG_HTML_TABLE_WIDTH = '98%';

$instance_table = new Table ( $FG_TABLE_NAME, $FG_COL_QUERY );

if (is_null ( $order ) || is_null ( $sens )) {
    $order = $FG_TABLE_DEFAULT_ORDER;
    $sens = $FG_TABLE_DEFAULT_SENS;
}

if ($posted == 1) {
    $SQLcmd = '';
    $SQLcmd = do_field ( $SQLcmd, 'src', 'src' );
    $SQLcmd = do_field ( $SQLcmd, 'dst', 'calledstation' );
    $SQLcmd = do_field ( $SQLcmd, 'dnid', 'dnid' );
}

$date_clause = '';

normalize_day_of_month($fromstatsday_sday, $fromstatsmonth_sday, 1);
normalize_day_of_month($tostatsday_sday, $tostatsmonth_sday, 1);
// Date Clause
if ($fromday && isset ( $fromstatsday_sday ) && isset ( $fromstatsmonth_sday )) {
    if ($fromtime) {
        $date_clause .= " AND t1.starttime >= ('$fromstatsmonth_sday-$fromstatsday_sday $fromstatsday_hour:$fromstatsday_min')";
    } else {
        $date_clause .= " AND t1.starttime >= ('$fromstatsmonth_sday-$fromstatsday_sday')";
    }
}
if ($today && isset ( $tostatsday_sday ) && isset ( $tostatsmonth_sday )) {
    if ($totime) {
        $date_clause .= " AND t1.starttime <= ('$tostatsmonth_sday-" . sprintf ( "%02d", intval ( $tostatsday_sday )/*+1*/) . " $tostatsday_hour:$tostatsday_min:59')";
    } else {
        $date_clause .= " AND t1.starttime <= ('$tostatsmonth_sday-" . sprintf ( "%02d", intval ( $tostatsday_sday )/*+1*/) . " 23:59:59')";
    }
}

if (strpos ( $SQLcmd, 'WHERE' ) > 0) {
    $FG_TABLE_CLAUSE = substr ( $SQLcmd, 6 ) . $date_clause;
} elseif (strpos ( $date_clause, 'AND' ) > 0) {
    $FG_TABLE_CLAUSE = substr ( $date_clause, 5 );
}

if (! isset ( $FG_TABLE_CLAUSE ) || strlen ( $FG_TABLE_CLAUSE ) == 0) {
    $cc_yearmonth = sprintf ( "%04d-%02d-%02d", date ( "Y" ), date ( "n" ), date ( "d" ) );
    $FG_TABLE_CLAUSE = " t1.starttime >= ('$cc_yearmonth')";
}

if (isset ( $customer ) && ($customer > 0)) {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= "t1.card_id='$customer'";
} else {
    if (isset ( $entercustomer ) && ($entercustomer > 0)) {
        if (strlen ( $FG_TABLE_CLAUSE ) > 0)
            $FG_TABLE_CLAUSE .= " AND ";
        $FG_TABLE_CLAUSE .= "t1.card_id='$entercustomer'";
    } elseif (isset ( $entercustomer_num ) && ($entercustomer_num > 0)) {
        $res = $DBHandle -> Execute ("select id from cc_card where username=".$entercustomer_num);
        if ($res) {
            if ($res->RecordCount ()) {
                $row =$res -> fetchRow();
                if (strlen ( $FG_TABLE_CLAUSE ) > 0)	$FG_TABLE_CLAUSE .= " AND ";
                $FG_TABLE_CLAUSE .= "t1.card_id='$row[0]'";
            }
        }
    }
}

if (isset ( $enterprovider ) && $enterprovider > 0) {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= "t3.id_provider = '$enterprovider'";
}
if (isset ( $entertrunk ) && $entertrunk > 0) {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= "t3.id_trunk = '$entertrunk'";
}
if (isset ( $entertariffgroup ) && $entertariffgroup > 0) {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= "t1.id_tariffgroup = '$entertariffgroup'";
}
if (isset ( $enterratecard ) && $enterratecard > 0) {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= "t1.id_ratecard = '$enterratecard'";
}

if (isset ( $choose_calltype ) && ($choose_calltype != - 1)) {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " t1.sipiax='$choose_calltype' ";
}

$FG_ASR_CIC_CLAUSE = $FG_TABLE_CLAUSE;

//To select just terminatecauseid=ANSWER
if (! isset ( $terminatecauseid )) {
    $terminatecauseid = "ANSWER";
}
if ($terminatecauseid == "ANSWER") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=1) ";
}
if ($terminatecauseid == "INCOMPLET") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid !=1) ";
}
if ($terminatecauseid == "CONGESTION") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=5) ";
}
if ($terminatecauseid == "NOANSWER") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=3) ";
}
if ($terminatecauseid == "BUSY") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=2) ";
}
if ($terminatecauseid == "CHANUNAVAIL") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=6) ";
}
if ($terminatecauseid == "CANCEL") {
    if (strlen ( $FG_TABLE_CLAUSE ) > 0)
        $FG_TABLE_CLAUSE .= " AND ";
    $FG_TABLE_CLAUSE .= " (t1.terminatecauseid=4) ";
}

if (! $nodisplay) {
    $list = $instance_table->Get_list ( $DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page * $FG_LIMITE_DISPLAY );
}

// EXPORT
$FG_EXPORT_SESSION_VAR = "pr_export_entity_call";

// Query Preparation for the Export Functionality
$_SESSION [$FG_EXPORT_SESSION_VAR] = "SELECT $FG_COL_QUERY FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE";

if (! is_null ( $order ) && ($order != '') && ! is_null ( $sens ) && ($sens != '')) {
    $_SESSION [$FG_EXPORT_SESSION_VAR] .= " ORDER BY $order $sense";
}

/************************/
$QUERY = "SELECT DATE(t1.starttime) AS day, sum(t1.sessiontime) AS calltime, sum(t1.sessionbill) AS cost, count(*) as nbcall,
            sum(t1.buycost) AS buy, sum(case when t1.sessiontime>0 then 1 else 0 end) as success_calls
            FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE GROUP BY day ORDER BY day"; //extract(DAY from calldate)

if (! $nodisplay) {
    $res = $DBHandle->Execute ( $QUERY );
    if ($res) {
        $num = $res->RecordCount ();
        for ($i = 0; $i < $num; $i ++) {
            $list_total_day [] = $res->fetchRow ();
        }
    }

    if ($FG_DEBUG == 3)
        echo "<br>Clause : $FG_TABLE_CLAUSE";

    $nb_record = $instance_table->Table_count ( $DBHandle, $FG_TABLE_CLAUSE );
    if ($FG_DEBUG >= 1)
        var_dump ( $list );

}

if ($nb_record <= $FG_LIMITE_DISPLAY) {
    $nb_record_max = 1;
} else {
    if ($nb_record % $FG_LIMITE_DISPLAY == 0) {
        $nb_record_max = (intval ( $nb_record / $FG_LIMITE_DISPLAY ));
    } else {
        $nb_record_max = (intval ( $nb_record / $FG_LIMITE_DISPLAY ) + 1);
    }
}

if ($FG_DEBUG == 3)
    echo "<br>Nb_record : $nb_record";
if ($FG_DEBUG == 3)
    echo "<br>Nb_record_max : $nb_record_max";

$smarty->display ( 'main.tpl' );

?>

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
<center>
<FORM METHOD=POST name="myForm"
    ACTION="<?php
    echo $PHP_SELF?>?s=1&t=0&order=<?php
    echo $order?>&sens=<?php
    echo $sens?>&current_page=<?php
    echo $current_page?>">
<INPUT TYPE="hidden" NAME="posted" value=1> <INPUT TYPE="hidden"
    NAME="current_page" value=0>
<TABLE class="bar-status" width="85%" border="0" cellspacing="1"
    cellpadding="2" align="center">
        <?php
        if ($_SESSION ["pr_groupID"] == 2 && is_numeric ( $_SESSION ["pr_IDCust"] )) {
            ?>
        <?php
        } else {
            ?>
        <tr>
        <td align="left" valign="top" class="bgcolor_004"><font
            class="fontstyle_003">&nbsp;&nbsp;<?php
            echo gettext ( "CUSTOMERS" );
            ?></font>
        </td>
        <td class="bgcolor_005" align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="fontstyle_searchoptions" width="700" valign="top">
                    <?php
            echo gettext ( "Enter the customer ID" );
            ?>: <INPUT TYPE="text"
                    NAME="entercustomer" value="<?php echo $entercustomer?>"
                    class="form_input_text"> <a href="#"
                    onclick="window.open('A2B_entity_card.php?popup_select=1&popup_formname=myForm&popup_fieldname=entercustomer' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img
                    src="<?php echo Images_Path; ?>/icon_arrow_orange.gif"></a>
                 <BR> OR <br>
                <?php echo gettext ( "Enter the customer number" );?>: <INPUT TYPE="text" NAME="entercustomer_num"
                    value="<?php echo $entercustomer_num?>" class="form_input_text"> <a href="#"
                                        onclick="window.open('A2B_entity_card.php?popup_select=2&popup_formname=myForm&popup_fieldname=entercustomer_num' , 'CardNumberSelection','scrollbars=1,width=550,height=330,top=20,left=100,scrollbars=1');"><img
                                        src="<?php echo Images_Path; ?>/icon_arrow_orange.gif"></a>
                </td>
                <td width="50%">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" class="fontstyle_searchoptions"><?php echo gettext ( "CallPlan" ); ?> :</td>
                        <td align="left" class="fontstyle_searchoptions"><INPUT TYPE="text" NAME="entertariffgroup" value="<?php echo $entertariffgroup?>" size="4" class="form_input_text">&nbsp;<a href="#" onclick="window.open('A2B_entity_tariffgroup.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertariffgroup' , 'CallPlanSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img
                            src="<?php echo Images_Path; ?>/icon_arrow_orange.gif"></a></td>
                        <td align="left" class="fontstyle_searchoptions"><?php echo gettext ( "Provider" ); ?> :

            <td align="left" class="fontstyle_searchoptions"><INPUT
                            TYPE="text" NAME="enterprovider"
                            value="<?php echo $enterprovider?>" size="4" class="form_input_text">&nbsp;<a href="#"
                            onclick="window.open('A2B_entity_provider.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterprovider' , 'ProviderSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img
                            src="<?php echo Images_Path; ?>/icon_arrow_orange.gif"></a></td>
                    </tr>
                    <tr>
                        <td align="left" class="fontstyle_searchoptions"><?php echo gettext ( "Trunk" ); ?> :</td>
                        <td align="left" class="fontstyle_searchoptions"><INPUT
                            TYPE="text" NAME="entertrunk" value="<?php
            echo $entertrunk?>"
                            size="4" class="form_input_text">&nbsp;<a href="#"
                            onclick="window.open('A2B_entity_trunk.php?popup_select=2&popup_formname=myForm&popup_fieldname=entertrunk' , 'TrunkSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img
                            src="<?php
            echo Images_Path;
            ?>/icon_arrow_orange.gif"></a></td>
                        <td align="left" class="fontstyle_searchoptions"><?php
            echo gettext ( "Rate" );
            ?> :</td>
                        <td align="left" class="fontstyle_searchoptions"><INPUT
                            TYPE="text" NAME="enterratecard"
                            value="<?php
            echo $enterratecard?>" size="4"
                            class="form_input_text">&nbsp;<a href="#"
                            onclick="window.open('A2B_entity_def_ratecard.php?popup_select=2&popup_formname=myForm&popup_fieldname=enterratecard' , 'RatecardSelection','scrollbars=1,width=550,height=330,top=20,left=100');"><img
                            src="<?php
            echo Images_Path;
            ?>/icon_arrow_orange.gif"></a></td>
                    </tr>
                </table>
                </td>
            </tr>

        </table>
        </td>
    </tr>
        <?php
        }
        ?>
    <tr>
        <td align="left" class="bgcolor_004"><font class="fontstyle_003">&nbsp;&nbsp;<?php echo gettext ( "DATE" ); ?></font>
        </td>
        <td align="left" class="bgcolor_005">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="fontstyle_searchoptions"><input type="checkbox"
                    name="fromday" value="true" <?php
                    if ($fromday) {
                        ?> checked
                    <?php
                    }
                    ?>> <?php
                    echo gettext ( "From" );
                    ?> :
                <select name="fromstatsday_sday" class="form_input_select">
                    <?php
                    for ($i = 1; $i <= 31; $i ++) {
                        if ($fromstatsday_sday == sprintf ( "%02d", $i ))
                            $selected = "selected";
                        else
                            $selected = "";
                        echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                    }
                    ?>
                </select> <select name="fromstatsmonth_sday"
                    class="form_input_select">
                <?php
                $year_actual = date ( "Y" );
                $monthname = array (gettext ( "January" ), gettext ( "February" ), gettext ( "March" ), gettext ( "April" ), gettext ( "May" ), gettext ( "June" ), gettext ( "July" ), gettext ( "August" ), gettext ( "September" ), gettext ( "October" ), gettext ( "November" ), gettext ( "December" ) );

                for ($i = $year_actual; $i >= $year_actual - 1; $i --) {
                    if ($year_actual == $i) {
                        $monthnumber = date ( "n" ) - 1; // Month number without lead 0.
                    } else {
                        $monthnumber = 11;
                    }
                    for ($j = $monthnumber; $j >= 0; $j --) {
                        $month_formated = sprintf ( "%02d", $j + 1 );
                        if ($fromstatsmonth_sday == "$i-$month_formated")
                            $selected = "selected";
                        else
                            $selected = "";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                    }
                }
                ?>
                </select> <br />
                <input type="checkbox" name="fromtime" value="true"
                    <?php
                    if ($fromtime) {
                        ?> checked <?php
                    }
                    ?>>
                <?php
                echo gettext ( "Time :" )?>
                <select name="fromstatsday_hour" class="form_input_select">
                <?php
                for ($i = 0; $i <= 23; $i ++) {
                    if ($fromstatsday_hour == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select> : <select name="fromstatsday_min"
                    class="form_input_select">
                <?php
                for ($i = 0; $i < 60; $i = $i + 5) {
                    if ($fromstatsday_min == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select></td>
                <td class="fontstyle_searchoptions"><input type="checkbox"
                    name="today" value="true" <?php
                    if ($today) {
                        ?> checked <?php
                    }
                    ?>>
                <?php
                echo gettext ( "To" );
                ?>  :
                <select name="tostatsday_sday" class="form_input_select">
                <?php
                for ($i = 1; $i <= 31; $i ++) {
                    if ($tostatsday_sday == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select> <select name="tostatsmonth_sday" class="form_input_select">
                <?php
                $year_actual = date ( "Y" );
                for ($i = $year_actual; $i >= $year_actual - 1; $i --) {
                    if ($year_actual == $i) {
                        $monthnumber = date ( "n" ) - 1; // Month number without lead 0.
                    } else {
                        $monthnumber = 11;
                    }
                    for ($j = $monthnumber; $j >= 0; $j --) {
                        $month_formated = sprintf ( "%02d", $j + 1 );
                        if ($tostatsmonth_sday == "$i-$month_formated")
                            $selected = "selected";
                        else
                            $selected = "";
                        echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";
                    }
                }
                ?>
                </select> <br />
                <input type="checkbox" name="totime" value="true"
                    <?php
                    if ($totime) {
                        ?> checked <?php
                    }
                    ?>>
                <?php
                echo gettext ( "Time :" )?>
                <select name="tostatsday_hour" class="form_input_select">
                <?php
                for ($i = 0; $i <= 23; $i ++) {
                    if ($tostatsday_hour == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select> : <select name="tostatsday_min" class="form_input_select">
                <?php
                for ($i = 0; $i < 60; $i = $i + 5) {
                    if ($tostatsday_min == sprintf ( "%02d", $i )) {
                        $selected = "selected";
                    } else {
                        $selected = "";
                    }
                    echo '<option value="' . sprintf ( "%02d", $i ) . "\"$selected>" . sprintf ( "%02d", $i ) . '</option>';
                }
                ?>
                </select></td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td class="bgcolor_002" align="left"><font class="fontstyle_003">&nbsp;&nbsp;<?php
        echo gettext ( "PHONENUMBER" );
        ?></font>
        </td>
        <td class="bgcolor_003" align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="dst"
                    value="<?php
                    echo $dst?>" class="form_input_text"></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dsttype" value="1"
                    <?php
                    if ((! isset ( $dsttype )) || ($dsttype == 1)) {
                        ?> checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Exact" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dsttype" value="2" <?php
                    if ($dsttype == 2) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Begins with" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dsttype" value="3" <?php
                    if ($dsttype == 3) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Contains" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dsttype" value="4" <?php
                    if ($dsttype == 4) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Ends with" );
                    ?></td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td align="left" class="bgcolor_004"><font class="fontstyle_003">&nbsp;&nbsp;<?php
        echo gettext ( "CALLERID" );
        ?></font>
        </td>
        <td class="bgcolor_005" align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="src"
                    value="<?php
                    echo "$src";
                    ?>" class="form_input_text"></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="srctype" value="1"
                    <?php
                    if ((! isset ( $srctype )) || ($srctype == 1)) {
                        ?> checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Exact" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="srctype" value="2" <?php
                    if ($srctype == 2) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Begins with" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="srctype" value="3" <?php
                    if ($srctype == 3) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Contains" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="srctype" value="4" <?php
                    if ($srctype == 4) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Ends with" );
                    ?></td>
            </tr>
        </table>
        </td>
    </tr>

    <tr>
        <td align="left" class="bgcolor_004"><font class="fontstyle_003">&nbsp;&nbsp;<?php
        echo gettext ( "DNID" );
        ?></font>
        </td>
        <td class="bgcolor_005" align="left">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="dnid"
                    value="<?php
                    echo "$dnid";
                    ?>" class="form_input_text"></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dnidtype" value="1"
                    <?php
                    if ((! isset ( $dnidtype )) || ($dnidtype == 1)) {
                        ?> checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Exact" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dnidtype" value="2" <?php
                    if ($dnidtype == 2) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Begins with" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dnidtype" value="3" <?php
                    if ($dnidtype == 3) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Contains" );
                    ?></td>
                <td class="fontstyle_searchoptions" align="center"><input
                    type="radio" NAME="dnidtype" value="4" <?php
                    if ($dnidtype == 4) {
                        ?>
                    checked <?php
                    }
                    ?>><?php
                    echo gettext ( "Ends with" );
                    ?></td>
            </tr>
        </table>
        </td>
    </tr>

    <!-- Select Calltype: -->
    <tr>
        <td class="bgcolor_002" align="left"><font class="fontstyle_003">&nbsp;&nbsp;<?php
        echo gettext ( "CALL TYPE" );
        ?></font></td>
        <td class="bgcolor_003" align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="fontstyle_searchoptions"><select NAME="choose_calltype"
                    size="1" class="form_input_select">
                    <option value='-1'
                        <?php
                        if (($choose_calltype == - 1) || (! isset ( $choose_calltype ))) {
                            ?>
                        selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'ALL CALLS' )?>
                                </option>
                            <?php
                            foreach ($list_calltype as $key => $cur_value) {
                                ?>
                                <option value='<?php
                                echo $cur_value [1]?>'
                        <?php
                                if ($choose_calltype == $cur_value [1]) {
                                    ?> selected <?php
                                }
                                ?>><?php
                                echo gettext ( $cur_value [0] )?>
                                </option>
                            <?php
                            }
                            ?>
                        </select></td>
            </tr>
        </table>
        </td>
    </tr>

    <!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->
    <tr>
        <td class="bgcolor_002" align="left"><font class="fontstyle_003">&nbsp;&nbsp;<?php
        echo gettext ( "OPTIONS" );
        ?></font></td>
        <td class="bgcolor_003" align="center">
        <div align="left">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" class="fontstyle_searchoptions">
                    <?php
                    echo gettext ( "SHOW CALLS" );
                    ?> :
               </td>
                <td width="80%" class="fontstyle_searchoptions"><select
                    NAME="terminatecauseid" size="1" class="form_input_select">
                    <option value='ANSWER'
                        <?php
                        if ((! isset ( $terminatecauseid )) || ($terminatecauseid == "ANSWER")) {
                            ?>
                        selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'ANSWERED' )?>
                            </option>

                    <option value='ALL' <?php
                    if ($terminatecauseid == "ALL") {
                        ?> selected
                        <?php
                    }
                    ?>><?php
                        echo gettext ( 'ALL' )?>
                            </option>

                    <option value='INCOMPLET'
                        <?php
                        if ($terminatecauseid == "INCOMPLET") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'NOT COMPLETED' )?>
                            </option>

                    <option value='CONGESTION'
                        <?php
                        if ($terminatecauseid == "CONGESTION") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'CONGESTIONED' )?>
                            </option>

                    <option value='BUSY' <?php
                    if ($terminatecauseid == "BUSY") {
                        ?>
                        selected <?php
                    }
                    ?>><?php
                        echo gettext ( 'BUSIED' )?>
                            </option>

                    <option value='NOANSWER'
                        <?php
                        if ($terminatecauseid == "NOANSWER") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'NOT ANSWERED' )?>
                            </option>

                    <option value='CHANUNAVAIL'
                        <?php
                        if ($terminatecauseid == "CHANUNAVAIL") {
                            ?> selected <?php
                        }
                        ?>><?php
                        echo gettext ( 'CHANNEL UNAVAILABLE' )?>
                            </option>

                    <option value='CANCEL' <?php
                    if ($terminatecauseid == "CANCEL") {
                        ?>
                        selected <?php
                    }
                    ?>><?php
                        echo gettext ( 'CANCELED' )?>
                            </option>

                </select></td>
            </tr>
            <tr class="bgcolor_005">
                <td class="fontstyle_searchoptions">
                    <?php
                    echo gettext ( "RESULT" );
                    ?> :
               </td>
                <td class="fontstyle_searchoptions">
                    <?php
                    echo gettext ( "mins" );
                    ?><input type="radio" NAME="resulttype"
                    value="min"
                    <?php
                    if ((! isset ( $resulttype )) || ($resulttype == "min")) {
                        ?> checked
                    <?php
                    }
                    ?>> - <?php
                    echo gettext ( "secs" )?> <input type="radio"
                    NAME="resulttype" value="sec" <?php
                    if ($resulttype == "sec") {
                        ?>
                    checked <?php
                    }
                    ?>></td>
            </tr>
            <tr>
                <td class="fontstyle_searchoptions">
                    <?php
                    echo gettext ( "CURRENCY" );
                    ?> :
                </td>
                <td class="fontstyle_searchoptions"><select NAME="choose_currency"
                    size="1" class="form_input_select">
                        <?php
                        $currencies_list = get_currencies ();
                        foreach ($currencies_list as $key => $cur_value) {
                            ?>
                            <option value='<?php
                            echo $key?>'
                        <?php
                            if (($choose_currency == $key) || (! isset ( $choose_currency ) && $key == strtoupper ( BASE_CURRENCY ))) {
                                ?>
                        selected <?php
                            }
                            ?>><?php
                            echo $cur_value [1] . ' (' . $cur_value [2] . ')'?>
                            </option>
                        <?php
                        }
                        ?>
                    </select></td>
            </tr>
        </table>

        </td>
    </tr>
    <!-- Select Option : to show just the Answered Calls or all calls, Result type, currencies... -->

    <tr>
        <td class="bgcolor_004" align="left"></td>
        <td class="bgcolor_005" align="center"><input type="image"
            name="image16" align="top" border="0"
            src="<?php
            echo Images_Path;
            ?>/button-search.gif" /></td>
    </tr>
</table>
</FORM>
</center>

<!-- ** ** ** ** ** Part to display the CDR ** ** ** ** ** -->

<center><?php
echo gettext ( "Number of call" );
?> : <?php
if (is_array ( $list ) && count ( $list ) > 0) {
    echo $nb_record;
} else {
    echo "0";
}
?></center>

<table width="<?php echo $FG_HTML_TABLE_WIDTH?>" border="0" align="center" cellpadding="0" cellspacing="0">
    <TR bgcolor="#ffffff">
        <TD class="bgcolor_021" height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px">
        <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
            <TR>
                <TD><SPAN class="fontstyle_003"><?php echo $FG_HTML_TABLE_TITLE?></SPAN></TD>
            </TR>
        </TABLE>
        </TD>
    </TR>
    <TR>
        <TD>
        <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
            <TR class="bgcolor_008">
                <TD width="<?php echo $FG_ACTION_SIZE_COLUMN?>" align=center class="tableBodyRight" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px"></TD>

                  <?php
                        if (is_array ( $list ) && count ( $list ) > 0) {

                            for ($i = 0; $i < $FG_NB_TABLE_COL; $i ++) {
                                ?>
                    <TD width="<?php echo $FG_TABLE_COL [$i] [2]?>" align=middle class="tableBody" style="PADDING-BOTTOM: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px; PADDING-TOP: 2px">
                        <center><strong>
                        <?php if (strtoupper ( $FG_TABLE_COL [$i] [4] ) == "SORT") { ?>
                        <a href="<?php
                                echo $PHP_SELF . "?entercustomer_num=$entercustomer_num&s=1&t=0&stitle=$stitle&atmenu=$atmenu&current_page=$current_page&order=" . $FG_TABLE_COL [$i] [1] . "&sens=";
                                if ($sens == "ASC") {
                                    echo "DESC";
                                } else {
                                    echo "ASC";
                                }
                                echo "&entercustomer=$entercustomer&enterprovider=$enterprovider&entertrunk=$entertrunk&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&srctype=$srctype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype";
                                    ?>">
<span class="liens"><?php
                                }
                                ?>
<?php echo $FG_TABLE_COL [$i] [0]?>
<?php if ($order == $FG_TABLE_COL [$i] [1] && $sens == "ASC") { ?>
&nbsp;<img src="<?php echo Images_Path; ?>/icon_up_12x12.GIF" width="12"
height="12" border="0">
<?php
 } elseif ($order == $FG_TABLE_COL [$i] [1] && $sens == "DESC") {
?>
&nbsp;<img src="<?php echo Images_Path; ?>/icon_down_12x12.GIF" width="12" height="12" border="0">
<?php } ?>
<?php if (strtoupper ( $FG_TABLE_COL [$i] [4] ) == "SORT") { ?>
</span></a>
<?php } ?>
</strong></center>
</TD>
   <?php } ?>
   <?php if ($FG_DELETION || $FG_EDITION) { ?>
   <?php } ?>
</TR>
<?php

$ligne_number = 0;
//print_r($list);
foreach ($list as $recordset) {
    $ligne_number ++;
    ?>

<TR bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$ligne_number % 2]?>" onMouseOver="bgColor='#C4FFD7'" onMouseOut="bgColor='<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$ligne_number % 2]?>'">
<TD vAlign=top align="<?php echo $FG_TABLE_COL [$i] [3]?>" class=tableBody><?php echo $ligne_number + $current_page * $FG_LIMITE_DISPLAY . ".&nbsp;"; ?></TD>

<?php for ($i = 0; $i < $FG_NB_TABLE_COL; $i ++) { ?>

    <?php if ($FG_TABLE_COL [$i] [6] == "lie") {

                    $instance_sub_table = new Table ( $FG_TABLE_COL [$i] [7], $FG_TABLE_COL [$i] [8] );
                    $sub_clause = str_replace ( "%id", $recordset [$i], $FG_TABLE_COL [$i] [9] );
                    $select_list = $instance_sub_table->Get_list ( $DBHandle, $sub_clause, null, null, null, null, null, null, null, 10);

                    $field_list_sun = preg_split('/,/', $FG_TABLE_COL [$i] [8] );
                    $record_display = $FG_TABLE_COL [$i] [10];

                    if (is_array($select_list)) {
                        for ($l = 1; $l <= count ( $field_list_sun ); $l ++) {
                            $record_display = str_replace ( "%$l", $select_list [0] [$l - 1], $record_display );
                        }
                    } else {
                        $record_display = $recordset [$i];
                    }
                } elseif ($FG_TABLE_COL[$i][6]=="lie_link") {
                    $instance_sub_table = new Table($FG_TABLE_COL[$i][7], $FG_TABLE_COL[$i][8]);
                    $sub_clause = str_replace ( "%id", $recordset [$i], $FG_TABLE_COL [$i] [9] );
                    $select_list = $instance_sub_table -> Get_list ($DBHandle, $sub_clause, null, null, null, null, null, null, null, 10);
                    if (is_array($select_list)) {
                        $field_list_sun = preg_split('/,/',$FG_TABLE_COL[$i][8]);
                        $record_display = $FG_TABLE_COL[$i][10];
                        $link = $FG_TABLE_COL[$i][12]."?form_action=ask-edit&id=".$select_list[0][1];
                        for ($l=1;$l<=count($field_list_sun);$l++) {
                            $val = str_replace("%$l", $select_list[0][$l-1], $record_display);
                            $record_display = "<a href='$link'>$val</a>";
                        }
                    } else {
                        $record_display="";
                    }
                } elseif ($FG_TABLE_COL [$i] [6] == "list") {
                    $select_list = $FG_TABLE_COL [$i] [7];
                    $record_display = $select_list [$recordset [$i]] [0];

                } else {
                    $record_display = $recordset [$i];
                }

                if (is_numeric ( $FG_TABLE_COL [$i] [5] ) && (strlen ( $record_display ) > $FG_TABLE_COL [$i] [5])) {
                    $record_display = substr ( $record_display, 0, $FG_TABLE_COL [$i] [5] - 3 ) . "";

                }

                ?>
        <TD vAlign=top align="<?php echo $FG_TABLE_COL [$i] [3]?>" class=tableBody><?php
            if (isset ( $FG_TABLE_COL [$i] [11] ) && strlen ( $FG_TABLE_COL [$i] [11] ) > 1) {
                call_user_func ( $FG_TABLE_COL [$i] [11], $record_display );
            } elseif (strlen($record_display)>0) {
                echo stripslashes ( $record_display );
            } else {
                echo '&nbsp;';
            }
        ?></TD>
     <?php } ?>

    </TR>
    <?php } //foreach ($list as $recordset)
        if ($ligne_number < $FG_LIMITE_DISPLAY)
            $ligne_number_end = $ligne_number + 2;
        while ($ligne_number < $ligne_number_end) {
            $ligne_number ++;
            ?>
        <TR
        bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$ligne_number % 2]?>">
        <?php for ($i = 0; $i < $FG_NB_TABLE_COL; $i ++) { ?>
         <TD vAlign=top class=tableBody>&nbsp;</TD>
         <?php } ?>
         <TD align="center" vAlign=top class=tableBodyRight>&nbsp;</TD>
        </TR>

        <?php } //END_WHILE

                } else {
                    echo gettext ( "No data found !!!" );
                } //end_if
        ?>
        </TABLE>
        </td>
    </tr>
    <TR bgcolor="#ffffff">
        <TD class="bgcolor_005" height="16" style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px">
            <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
                <TR>
                    <TD align="right"><SPAN class="fontstyle_003">
                    <?php if ($current_page > 0) { ?>
                    <img src="<?php echo Images_Path; ?>/fleche-g.gif"
                    width="5" height="10"> <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo ($current_page - 1)?><?php
                                    if (! is_null ( $letter ) && ($letter != "")) {
                                        echo "&letter=$letter";
                                    }
                                    echo "&entercustomer_num=$entercustomer_num&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&srctype=$srctype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype&entercustomer=$entercustomer&enterprovider=$enterprovider&entertrunk=$entertrunk";
                                    ?>">
                    <?php echo gettext ( "Previous" ); ?> </a> - <?php } ?><?php echo ($current_page + 1); ?> / <?php echo $nb_record_max; ?>
                    <?php if ($current_page < $nb_record_max - 1) { ?>
                    - <a href="<?php echo $PHP_SELF?>?s=1&t=0&order=<?php echo $order?>&sens=<?php echo $sens?>&current_page=<?php echo ($current_page + 1)?><?php
                                    if (! is_null ( $letter ) && ($letter != "")) {
                                        echo "&letter=$letter";
                                    }
                                    echo "&entercustomer_num=$entercustomer_num&posted=$posted&Period=$Period&frommonth=$frommonth&fromstatsmonth=$fromstatsmonth&tomonth=$tomonth&tostatsmonth=$tostatsmonth&fromday=$fromday&fromstatsday_sday=$fromstatsday_sday&fromstatsmonth_sday=$fromstatsmonth_sday&today=$today&tostatsday_sday=$tostatsday_sday&tostatsmonth_sday=$tostatsmonth_sday&dsttype=$dsttype&srctype=$srctype&clidtype=$clidtype&channel=$channel&resulttype=$resulttype&dst=$dst&src=$src&clid=$clid&terminatecauseid=$terminatecauseid&choose_calltype=$choose_calltype&entercustomer=$entercustomer&enterprovider=$enterprovider&entertrunk=$entertrunk";
                                    ?>">
                    <?php echo gettext ( "Next" ); ?></a> <img src="<?php echo Images_Path; ?>/fleche-d.gif" width="5" height="10"> </SPAN>
                    <?php } ?>
                  </TD>
        </TABLE>
        </TD>
    </TR>
</table>

<!-- ** ** ** ** ** Part to display the GRAPHIC ** ** ** ** ** -->
<br>

<?php
if (is_array ( $list_total_day ) && count ( $list_total_day ) > 0) {

    $mmax = 0;
    $totalcall == 0;
    $totalminutes = 0;
    $totalsuccess = 0;
    $totalfail = 0;
    foreach ($list_total_day as $data) {
        if ($mmax < $data [1])
            $mmax = $data [1];
        $totalcall += $data [3];
        $totalminutes += $data [1];
        $totalcost += $data [2];
        $totalbuycost += $data [4];
        $totalsuccess += $data [5];
    }
    $max_fail = 0;
?>

<?php
$profit = $totalcost - $totalbuycost;
$rand_num = rand(1,4);

// Show the donate button only 25% of the page display
if ($profit > 500 && $rand_num==4 && SHOW_DONATION) {
?>
<center>
<table align="center" width="50%" bgcolor="white" cellpadding="5" cellspacing="5" style="border: solid 1px">
    <tr>
        <td align="center">

            <center>
                <b><font color="#A00000"><?php echo gettext("Thanks to A2Billing, you have made a profit of over 500 euro !");?></font></b><BR>
                    <?php echo gettext("Support A2Billing by clicking on the Donate button below :");?>

                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="lc" value="US">
                    <input type="hidden" name="country" value="USA">
                    <input type="hidden" name="hosted_button_id" value="3769548">
                    <input type="image" src="https://www.paypal.com/en_US/ES/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Make Donation with PayPal">
                    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </center>
        </td>
    </tr>
</table>
</center>
<br>
<?php } ?>

<!-- END TITLE GLOBAL MINUTES //-->

<table border="0" cellspacing="0" cellpadding="0" width="95%">
    <tbody>
        <tr>
            <td bgcolor="#000000">
            <table border="0" cellspacing="1" cellpadding="2" width="100%">
                <tbody>
                    <tr>
                        <td align="center" class="bgcolor_019"></td>
                        <td class="bgcolor_020" align="center" colspan="10"><font
                            class="fontstyle_003"><?php echo gettext ( "TRAFFIC SUMMARY" ); ?></font></td>
                    </tr>
                    <tr class="bgcolor_019">
                        <td align="center" class="bgcolor_020"><font class="fontstyle_003"><?php echo gettext ( "DATE" ); ?></font></td>
                        <td align="center"><font class="fontstyle_003"><acronym
                            title="<?php echo gettext ( "DURATION" ); ?>"><?php	echo gettext ( "DUR" );	?></acronym></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "GRAPHIC" ); ?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "CALLS" ); ?></font></td>
                        <td align="center"><font class="fontstyle_003"><acronym
                            title="<?php echo gettext ( "AVERAGE LENGTH OF CALL" );	?>"><?php echo gettext ( "ALOC" );	?></acronym></font></td>
                        <td align="center"><font class="fontstyle_003"><acronym
                            title="<?php echo gettext ( "ANSWER SEIZE RATIO" );	?>"><?php echo gettext ( "ASR" ); ?></acronym></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "SELL" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "BUY" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "PROFIT" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "MARGIN" );	?></font></td>
                        <td align="center"><font class="fontstyle_003"><?php echo gettext ( "MARKUP" );	?></font></td>

                        <!-- LOOP -->
    <?php
    $i = 0;
    $j = 0;
    foreach ($list_total_day as $data) {
        $i = ($i + 1) % 2;
        $tmc = $data [1] / $data [3];

        if ((! isset ( $resulttype )) || ($resulttype == "min")) {
            $tmc = sprintf ( "%02d", intval ( $tmc / 60 ) ) . ":" . sprintf ( "%02d", intval ( $tmc % 60 ) );
        } else {

            $tmc = intval ( $tmc );
        }

        if ((! isset ( $resulttype )) || ($resulttype == "min")) {
            $minutes = sprintf ( "%02d", intval ( $data [1] / 60 ) ) . ":" . sprintf ( "%02d", intval ( $data [1] % 60 ) );
        } else {
            $minutes = $data [1];
        }
        if ($mmax > 0)
            $widthbar = intval ( ($data [1] / $mmax) * 150 );
        ?>
        </tr>
                    <tr>
                        <td align="right" class="sidenav" nowrap="nowrap"><font
                            class="fontstyle_003"><?php
        echo $data [0]?></font></td>
                        <td bgcolor="<?php
        echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php
        echo $minutes?> </font></td>
                        <td bgcolor="<?php
        echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="left" nowrap="nowrap" width="<?php
        echo $widthbar + 40?>">
                        <table cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td bgcolor="#e22424"><img
                                        src="<?php
        echo Images_Path;
        ?>/spacer.gif"
                                        width="<?php echo $widthbar?>" height="6"></td>
                                </tr>
                            </tbody>
                        </table>
                        </td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php echo $data [3]?></font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php echo $tmc?> </font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php display_2dec_percentage ( $data [5] * 100/ ($data [3]) )?> </font></td>
                        <!-- SELL -->
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php display_2bill ( $data [2] )?>
                        </font></td>
                        <!-- BUY -->
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php display_2bill ( $data [4] )?>
                        </font></td>
                        <!-- PROFIT -->
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php display_2bill ( $data [2] - $data [4] )?>
                        </font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php
                            if ($data [2] != 0) {
                                display_2dec_percentage ( (($data [2] - $data [4]) / $data [2]) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?>
                        </font></td>
                        <td bgcolor="<?php echo $FG_TABLE_ALTERNATE_ROW_COLOR [$i]?>"
                            align="right" nowrap="nowrap"><font class="fontstyle_006"><?php
                            if ($data [4] != 0) {
                                display_2dec_percentage ( (($data [2] - $data [4]) / $data [4]) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?>
                        </font></td>
                 <?php
                    $j ++;
                }

                if ((! isset ( $resulttype )) || ($resulttype == "min")) {
                    $total_tmc = sprintf ( "%02d", intval ( ($totalminutes / $totalcall) / 60 ) ) . ":" . sprintf ( "%02d", intval ( ($totalminutes / $totalcall) % 60 ) );
                    $totalminutes = sprintf ( "%02d", intval ( $totalminutes / 60 ) ) . ":" . sprintf ( "%02d", intval ( $totalminutes % 60 ) );
                } else {
                    $total_tmc = intval ( $totalminutes / $totalcall );
                }

                ?>
                </tr>
                    <!-- END DETAIL -->

                    <!-- END LOOP -->

                    <!-- TOTAL -->
                    <tr bgcolor="bgcolor_019">
                        <td align="right" nowrap="nowrap"><font class="fontstyle_003"><?php
                        echo gettext ( "TOTAL" );
                        ?></font></td>
                        <td align="center" nowrap="nowrap" colspan="2"><font
                            class="fontstyle_003"><?php echo $totalminutes?> </font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $totalcall?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php echo $total_tmc?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2dec_percentage ( $totalsuccess*100 / $totalcall )?> </font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill ( $totalcost )?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill ( $totalbuycost )?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php display_2bill ( $totalcost - $totalbuycost )?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php
                            if ($totalcost != 0) {
                                display_2dec_percentage ( (($totalcost - $totalbuycost) / $totalcost) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?></font></td>
                        <td align="center" nowrap="nowrap"><font class="fontstyle_003"><?php
                            if ($totalbuycost != 0) {
                                display_2dec_percentage ( (($totalcost - $totalbuycost) / $totalbuycost) * 100 );
                            } else {
                                echo "NULL";
                            }
                            ?></font></td>
                    </tr>
                    <!-- END TOTAL -->

                </tbody>
            </table>
            <!-- END ARRAY GLOBAL //--></td>
        </tr>
    </tbody>
</table>

<br>
<!-- SECTION EXPORT //--> &nbsp; &nbsp;
<a href="export_csv.php?var_export=<?php echo $FG_EXPORT_SESSION_VAR?>&var_export_type=type_csv" target="_blank"><img src="<?php echo Images_Path; ?>/excel.gif" border="0" height="30" /><?php echo gettext ( "Export CSV" ); ?></a>
- &nbsp; &nbsp;
<a href="export_csv.php?var_export=<?php echo $FG_EXPORT_SESSION_VAR?>&var_export_type=type_xml" target="_blank"><img src="<?php echo Images_Path; ?>/icons_xml.gif" border="0" height="32" /><?php echo gettext ( "Export XML" ); ?></a>

<?php } else { ?>
<center>
<h3><?php echo gettext ( "No calls in your selection");?>.</h3>
<?php  } ?>
</center>

<?php

$smarty->display('footer.tpl');
