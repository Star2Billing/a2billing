<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

/*
 * display_ratecard.php : A2Billing display ratecard API

    Usage :

        open_url ("http://localhost/webservice/display_ratecard.php?key=0951aa29a67836b860b0865bc495225c&page_url=localhost/index.php&field_to_display=t1.destination,t1.dialprefix,t1.rateinitial&column_name=Destination,Prefix,Rate/Min&field_type=,,money&".$_SERVER['QUERY_STRING']);

        see attached example : sample_display_ratecard.php

    Variable to set rate display option :

        ?key
        &ratecardid  "dispaly only this ratecard
        $tariffgroupid "dispaly only this Call plan
        &css_url
        &nb_display_lines (maximum lignes per page)
        &filter (coutryname,prefix)
        // removed &field_to_display i.e (countryname,sellingrate=money,buyrate=money, etc...)
        &field_type i.e ( ,money,money) (date or money ) is used for display
        &column_name      i.e (countryname,sellingrate,buyrate, etc...)
        &browse_letter  yes or no (A, B, C)
        &prefix_select i.e 32 (only prefix start by 32)
        &currency_select "cirency code i.e USD"
        &page_url i.e http://mysite.com/rates.php
        &merge_form (0 or 1) 1 for merge form search and 1 seaparated search form by default 0
        &fullhtmlpage (0 or 1)

        &resulttitle : tible that will show up above the rates array : can set to &nbsp; to not display anything
        &lcr : (0 or 1) to enable or disable the LCR, by default 0

 ****************************************************************************/

include 'lib/admin.defines.php';

// The wrapper variables for security
$security_key = API_SECURITY_KEY;

// The name of the log file
$logfile = API_LOGFILE;

// recipient email to send the alarm
$email_alarm = EMAIL_ADMIN;

$FG_DEBUG = 0;

$caching_query = 1800; // caching for 30 minutes

getpost_ifset(array( 'key', 'tariffgroupid', 'ratecardid', 'css_url', 'nb_display_lines', 'filter' ,'field_to_display', 'column_name',
                     'field_type', 'browse_letter', 'prefix_select', 'page_url', 'resulttitle', 'current_page', 'order', 'sens',
                     'choose_currency', 'choose_country', 'letter', 'searchpre', 'currency_select', 'merge_form', 'fullhtmlpage', 'lcr'));

$ip_remote = getenv('REMOTE_ADDR');
$mail_content = "[" . date("Y/m/d G:i:s", mktime()) . "] " . "Request asked from:$ip_remote with key:$key \n";

// CHECK KEY
if ($FG_DEBUG > 0)
    echo "<br> md5(" . md5($security_key) . ") !== $key";

if ((!isset ($_SESSION["access_display"]) || !$_SESSION["access_display"]) && (md5($security_key) !== $key || strlen($security_key) == 0)) {
    a2b_mail($email_alarm, "ALARM : RATE CARD API - CODE_ERROR 2", $mail_content);
    if ($FG_DEBUG > 0)
        echo ("[" . date("Y/m/d G:i:s", mktime()) . "] " . "[$productid] - CODE_ERROR 2" . "\n");
    echo ("400 Bad Request");
    $_SESSION["access_display"] = 0;
    exit ();
} else {
    $_SESSION["access_display"] = 1;
}

if (!isset($order)) $order = '';
if (!isset($sens)) $sens = '';
if (!isset($letter)) $letter = '';

//set  default values if not isset vars

if (!isset ($nb_display_lines) || strlen($nb_display_lines) == 0)
    $nb_display_lines = 1;

//if (!isset($field_to_display) || strlen($field_to_display)==0) $field_to_display="t1.destination,t1.dialprefix,t1.rateinitial";
if (!isset ($resulttitle) || strlen($resulttitle) == 0)
    $resulttitle = "Rate list";

if (!isset ($filter) || strlen($filter) == 0)
    $filter = "countryname,prefix";

if (!isset ($field_type) || strlen($field_type) == 0)
    $field_type = ",,money";

//if (!isset($column_name) || strlen($column_name)==0) $column_name="Destination,Prefix,Rate/Min";
if (!isset ($browse_letter) || strlen($browse_letter) == 0)
    $browse_letter = "yes";

if (!isset ($prefix_select) || strlen($prefix_select) == 0)
    $prefix_select = "";

if (!isset ($currency_select) || strlen($currency_select) == 0) {
    $currency_select = true;
} else {
    $choose_currency = $currency_select;
}
if (!isset ($css_url) || strlen($css_url) == 0)
    $css_url = substr("http://" . $_SERVER['HTTP_HOST'] . filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL), 0, strlen("http://" . $_SERVER['HTTP_HOST'] . filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL)) - 31) . "webservice/css/api_ratecard.css";

if (!isset ($merge_form) || strlen($merge_form) == 0)
    $merge_form = 0;

if (!isset ($fullhtmlpage) || strlen($fullhtmlpage) == 0)
    $fullhtmlpage = 1;

if (!isset ($page_url) || strlen($page_url) == 0) {
    echo "Error : need to define page_url !!!";
    exit;
} else {
    $page_url = urldecode ($page_url);
}
if ((substr($page_url, 0, 7) != 'http://') && (substr($page_url, 0, 8) != 'https://')) {
    echo "Error : page_url need to start by http:// or https:// ";
    exit;
}

if (!isset ($lcr) || strlen($lcr) == 0)
    $lcr = 0;

$parameter_to_send = "column_name=$column_name&field_type=$field_type&filter=$filter&resulttitle=$resulttitle&";
if (strpos($page_url, '?') === false) {
    if (strpos($page_url, 'column_name') === false)
        $page_url .= '?'.$parameter_to_send;
} else {
    if (strpos($page_url, 'column_name') === false)
        $page_url .= '&'.$parameter_to_send;
}

$page_url_encode = urlencode($page_url);

function add_clause(& $sqlclause, $addclause)
{
    if (strlen($sqlclause) == 0)
        $sqlclause = $addclause;
    else
        $sqlclause .= " AND " . $addclause;
}

/* 	ENABLE LCR
    SELECT t1.destination, min(t1.rateinitial), t1.dialprefix FROM cc_ratecard t1, cc_tariffplan t4, cc_tariffgroup t5,
    cc_tariffgroup_plan t6
    WHERE t4.id = t6.idtariffplan AND t6.idtariffplan=t1.idtariffplan AND t6.idtariffgroup = '3'
    GROUP BY t1.dialprefix
     *
    SELECT DISTINCT t1.id, t1.destination,t1.dialprefix,t1.rateinitial
    FROM cc_ratecard t1, cc_tariffplan t4, cc_tariffgroup t5, cc_tariffgroup_plan t6
    WHERE t4.id = t6.idtariffplan AND t6.idtariffplan=t1.idtariffplan AND t6.idtariffgroup = '3'
    AND t1.rateinitial = (SELECT min(f1.rateinitial) FROM cc_ratecard f1, cc_tariffplan t4, cc_tariffgroup t5, cc_tariffgroup_plan t6
    WHERE t4.id = t6.idtariffplan AND t6.idtariffplan=t1.idtariffplan AND t6.idtariffgroup = '3' AND t1.dialprefix=f1.dialprefix)
*/
if ($lcr) {
    $field_to_display = "t7.destination, t1.dialprefix, min(t1.rateinitial)";
    $sql_group = ' GROUP BY t1.dialprefix';
} else {
    $field_to_display = "t7.destination, t1.dialprefix, t1.rateinitial";
    $sql_group = null;
}

//end set default
$field_to_display = trim($field_to_display);
$field_type = trim($field_type);
$field      = explode(",", $field_to_display);
$type       = explode(",", $field_type);
$column     = explode(",", $column_name);
$fltr       = explode(",", $filter);

if (!isset ($current_page) || ($current_page == "")) {
    $current_page = 0;
}

$FILTER_COUNTRY = false;
$FILTER_PREFIX = false;
$DISPLAY_LETTER = false;

for ($i = 0; $i < count($fltr); $i++) {
    switch ($fltr[$i]) {
        case "countryname" :
            $FILTER_COUNTRY = true;
            if (isset ($choose_country) && strlen($choose_country) != 0) {
                add_clause($FG_TABLE_CLAUSE, "t7.destination REGEXP '^$choose_country'");
                #$current_page = 0;
            }
            break;
        case "prefix" :
            $FILTER_PREFIX = true;
            if (isset ($searchpre) && strlen($searchpre) != 0) {
                add_clause($FG_TABLE_CLAUSE, "t1.dialprefix REGEXP '^$searchpre'");
                #$current_page = 0;
            }
            break;
    }
}

if (isset ($browse_letter) && strtoupper($browse_letter) == "YES") {
    $DISPLAY_LETTER = true;
}

if (isset ($letter) && strlen($letter) != 0) {
    add_clause($FG_TABLE_CLAUSE, "t7.destination REGEXP '^[" . strtolower($letter) . strtoupper($letter) . "]'");
}

if (isset ($tariffgroupid) && strlen($tariffgroupid) != 0) {
    $FG_TABLE_NAME = "cc_ratecard t1, cc_tariffplan t4, cc_tariffgroup t5, cc_tariffgroup_plan t6, cc_prefix t7";
    add_clause($FG_TABLE_CLAUSE, "t4.id = t6.idtariffplan AND t6.idtariffplan=t1.idtariffplan AND t6.idtariffgroup = '$tariffgroupid' AND t7.prefix=t1.destination");
} else {
    $FG_TABLE_NAME = "cc_ratecard t1, cc_prefix t7";
    add_clause($FG_TABLE_CLAUSE, "t7.prefix=t1.destination");

    if (isset ($ratecardid) && strlen($ratecardid) != 0) {
        $FG_TABLE_NAME = "cc_ratecard t1, cc_tariffplan t4";
        add_clause($FG_TABLE_CLAUSE, "t4.id = '$ratecardid' AND t1.idtariffplan = t4.id AND t7.prefix=t1.destination");
    }
}

if ($FILTER_COUNTRY || $DISPLAY_LETTER) {
    $nb_display_lines = 100;
    $FG_LIMITE_DISPLAY = $nb_display_lines;
}

$FG_DEBUG = 0;
$DBHandle = DbConnect();

$FG_TABLE_COL = array ();

if (count($column) == count($field) && count($field) == count($type) && count($column) != 0) {
    for ($i = 0; $i < count($column); $i++) {
        switch ($type[$i]) {
            case "money" :
                $bill = "display_2bill";
                break;
            case "date" :
                $bill = "display_dateformat";
                break;
            default :
                $bill = "";
        }
        $FG_TABLE_COL[] = array (
            gettext($column[$i]
        ), $field[$i], (100 / count($column)) . "%", "center", "sort", "", "", "", "", "", "", $bill);
    }
}

$FG_COL_QUERY = 'DISTINCT ' . $field_to_display;

$FG_TABLE_DEFAULT_ORDER = $field[0];
$FG_TABLE_DEFAULT_SENS = "ASC";

$FG_LIMITE_DISPLAY = $nb_display_lines;
$FG_NB_TABLE_COL = count($FG_TABLE_COL);
$FG_TOTAL_TABLE_COL = $FG_NB_TABLE_COL;
$FG_HTML_TABLE_TITLE = gettext($resulttitle);

if ($FG_DEBUG == 3)
    echo "<br>Table : $FG_TABLE_NAME - Col_query : $FG_COL_QUERY";

if (is_null($order) || is_null($sens)) {
    $order = $FG_TABLE_DEFAULT_ORDER;
    $sens = $FG_TABLE_DEFAULT_SENS;
}

$instance_table = new Table($FG_TABLE_NAME, $FG_COL_QUERY);
$list = $instance_table->Get_list($DBHandle, $FG_TABLE_CLAUSE, $order, $sens, null, null, $FG_LIMITE_DISPLAY, $current_page * $FG_LIMITE_DISPLAY, $sql_group, $caching_query);

if ($FILTER_COUNTRY) {
    $QUERY = 'SELECT DISTINCT destination FROM cc_prefix ORDER BY destination LIMIT 0, 1000';
    $country_list = $instance_table->SQLExec($DBHandle, $QUERY, 1, $caching_query);
}

$QUERY = "SELECT count(*) FROM (SELECT DISTINCT t7.destination, t1.dialprefix, t1.rateinitial FROM $FG_TABLE_NAME WHERE $FG_TABLE_CLAUSE" . $sql_group . ") as setprefix ";
$list_nrecord = $instance_table->SQLExec($DBHandle, $QUERY, 1, $caching_query);
$nb_record = $list_nrecord[0][0];

if ($nb_record <= $FG_LIMITE_DISPLAY) {
    $nb_record_max = 1;
} else {
    if ($nb_record % $FG_LIMITE_DISPLAY == 0) {
        $nb_record_max = (intval($nb_record / $FG_LIMITE_DISPLAY));
    } else {
        $nb_record_max = (intval($nb_record / $FG_LIMITE_DISPLAY) + 1);
    }
}
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function Search(Source)
{
    if (Source == 'btn01') {
        if (document.a2b_rate_form.merge_form.value == 0) {
            document.a2b_rate_form.searchpre.value = "";
        }
    }
    if (Source == 'btn02') {
        if (document.a2b_rate_form.merge_form.value == 0) {
            <?php if ($FILTER_COUNTRY) { ?>
            var index = document.a2b_rate_form.choose_country.selectedIndex;
            document.a2b_rate_form.choose_country.options[index].value="";
            <?php } ?>
        }
    }
    document.a2b_rate_form.submit();
}
//-->
</script>

<?php

if ($fullhtmlpage) { ?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo $css_url;?>" rel="stylesheet" type="text/css">
</head>
<body>
<div>
<?php } ?>

<!-- ** ** ** ** ** Part for the research ** ** ** ** ** -->
    <FORM METHOD="GET" name="a2b_rate_form" action="<?php echo "$page_url"; ?>">

    <INPUT TYPE="hidden" NAME="order" value=<?php echo $order; ?>>
    <INPUT TYPE="hidden" NAME="sens" value=<?php echo $sens; ?>>
    <INPUT TYPE="hidden" NAME="current_page" value=<?php echo $current_page; ?>>
    <INPUT TYPE="hidden" NAME="css_url" value=<?php echo $css_url; ?>>
    <INPUT TYPE="hidden" NAME="page_url" value=<?php echo $page_url_encode; ?>>
    <INPUT TYPE="hidden" NAME="merge_form" value=<?php echo $merge_form; ?>>
    <div class="a2b_rate_search">
        <?php if ($FILTER_COUNTRY) { ?>
        <div class="searchelement"  align="left">
            <select NAME="choose_country" class="a2b_rate_select" >
            <option value="" <?php if (!isset($choose_country)) {?>selected<?php } ?>><?php echo gettext("Select a destination");?></option>
            <?php
                foreach ($country_list as $country) {?>
                    <option value='<?php echo $country[0] ?>' <?php if ($choose_country==$country[0]) {?>selected<?php } ?>><?php echo $country[0] ?><br>
                    </option>
                <?php 	} ?></select><INPUT name="btn01" type="button"  align="top" value="Search" class="a2b_rate_button" onclick="JavaScript:Search('btn01');"/>
        </div>
        <?php } else { ?>
             <INPUT TYPE="hidden" NAME="choose_country" value="">
        <?php } ?>
        <?php if ($DISPLAY_LETTER) { ?>
        <div class="a2b_rate_searchelement"  align="left">
            <?php echo gettext("select the first letter of the destination you are looking for");?><br>
            <?php for ($i=65;$i<=90;$i++) {
                 $x = chr($i);
                if ($merge_form) {
                     echo "<a href=\"$page_url"."letter=$x&order=$order&sens=$sens&choose_currency=$choose_currency&searchpre=$searchpre&choose_country=$choose_country&css_url=$css_url&page_url=$page_url_encode\">$x</a> ";
                } else {
                    echo "<a href=\"$page_url"."letter=$x&order=$order&sens=$sens&choose_currency=$choose_currency&css_url=$css_url&page_url=$page_url_encode\">$x</a> ";
                }
            }?></font>
        </div>
        <?php } if ($FILTER_PREFIX) { ?>
        <div class="a2b_rate_searchelement"  align="left">
            <?php echo gettext("Enter dial code"); ?><br>
            <INPUT TYPE="text" name="searchpre" class="a2b_rate_textfield" value="<?php echo $searchpre; ?>"/>
            <INPUT name="btn02" type="button"  align="top" value="Search" class="a2b_rate_button" onclick="JavaScript:Search('btn02');"/>
        </div>
        <?php } if ($currency_select) { ?>
        <div class="a2b_rate_searchelement"  align="left">
            <?php echo gettext("Select a currency");?><br>
            <select NAME="choose_currency" class="a2b_rate_select">
                <?php
                $currencies_list = get_currencies();
                foreach ($currencies_list as $key => $cur_value) {?>
                <option value="<?php echo $key ?>" <?php if (("$choose_currency"=="$key") || (!isset($choose_currency) && $key==strtoupper(BASE_CURRENCY)) ) {?>selected<?php } ?>><?php echo $cur_value[1] ?>
                </option>
                <?php 	} ?>
                </select>
                <input name="btn01" type="button"  align="top" value="Search" class="a2b_rate_button" onclick="JavaScript:Search('btn03');"/>
        </div>
        <?php } ?>
        <div class="a2b_rate_searchelement" align="left">
        </div>
    </div>
    </FORM>

    <BR/>
<!-- ** ** ** ** ** Part to display the ratecard ** ** ** ** ** -->

    <table width="100%" border=0 cellPadding=0 cellSpacing=0>
    <TR>
        <TD>
            <?php echo $FG_HTML_TABLE_TITLE; ?>
        </TD>
    </TR>
    <TR>
        <TD>
        <TABLE width="100%" border=0 cellPadding=0 cellSpacing=0>
            <TR>
            <?php
            if (is_array($list) && count($list)>0) {
                for ($i=0;$i<$FG_NB_TABLE_COL;$i++) { ?>
                    <TH width="<?php echo $FG_TABLE_COL[$i][2]?>" class="a2b_rate_table_title">
                    <center><strong>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
                        <a href="<?php  echo "$page_url"."&current_page=$current_page&order=".$FG_TABLE_COL[$i][1]."&sens=";if ($sens=="ASC") {echo"DESC";} else {echo"ASC";} echo "&choose_currency=$choose_currency&searchpre=$searchpre&choose_country=$choose_country&letter=$letter&css_url=$css_url&page_url=$page_url_encode";?>">
                    <?php  } ?>
                    <?php echo $FG_TABLE_COL[$i][0]?>
                    <?php  if (strtoupper($FG_TABLE_COL[$i][4])=="SORT") {?>
                        </a>
                    <?php }?>
                    </strong></center></TH>
                <?php } ?>
                </TR>
                <?php
                $alternate=0;
                foreach ($list as $recordset) {
                    $alternate = ($alternate+1) % 2;
                ?>
                <TR>
                <?php for ($i=0;$i<$FG_NB_TABLE_COL;$i++) {
                    $record_display = $recordset[$i];
                    if ( is_numeric($FG_TABLE_COL[$i][5]) && (strlen($record_display) > $FG_TABLE_COL[$i][5])  ) {
                        $record_display = substr($record_display, 0, $FG_TABLE_COL[$i][5]-3)."";
                    } ?>
                             <TD class="a2b_rate_tabletr_<?php echo $alternate;?>" vAlign=top align="<?php echo $FG_TABLE_COL[$i][3]?>"><?php
                    if (isset ($FG_TABLE_COL[$i][11]) && strlen($FG_TABLE_COL[$i][11])>1) {
                        call_user_func($FG_TABLE_COL[$i][11], $record_display);
                    } else {
                        echo stripslashes($record_display);
                    }?>
                    </TD>
                <?php  }?>
                </TR>
            <?php
                } //foreach ($list as $recordset)
            } else {
                echo gettext("No rate found !!!");
            }
            ?>
        </TABLE>
    </td>
    </tr>
    <TR>
    <TD>
        <?php
        $c_url = "$page_url"."order=$order&sens=$sens&current_page=%s&letter=$letter&choose_currency=$choose_currency&searchpre=$searchpre&choose_country=$choose_country&css_url=$css_url&page_url=$page_url_encode";
        printPages($current_page+1, $nb_record_max, $c_url);
        ?>
    </TD>
    </TR>
    </table>

<?php if ($fullhtmlpage) { ?>
</div>

<body>
</html>

<?php
}
