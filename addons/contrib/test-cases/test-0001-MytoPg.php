#!/usr/bin/php -q
## test cases for function My_to_Pg() and function Parse_helper()
<?php
/**
* Test cases for Class::MytoPg
*
* Copyright (C) 2009 Steve Dommett <steve@st4vs.net> and A2Billing
*
* Please submit bug reports, patches, etc to http://www.a2billing.org/
* and,  ideally,  assign the ticket to 'stavros'.
*
* This is released under the terms of the GNU Lesser General Public License v2.1
* A copy of which is available from http://www.gnu.org/copyleft/lesser.html
*
* @category   Unit-tests
* @package    MytoPg
* @author     Steve Dommett <steve@st4vs.net>
* @copyright  2009 Steve Dommett <steve@st4vs.net> and A2Billing
* @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version    CVS: $Id:$
* @since      File available since Release 1.4
*
*/

include_once (dirname(__FILE__)."/lib/Class.Table.php");
include (dirname(__FILE__)."/lib/Class.A2Billing.php");
include (dirname(__FILE__)."/lib/Misc.php");
include (dirname(__FILE__)."/lib/interface/constants.php");

$instance_table = new Table();
$idconfig = 1;
$mode = 'standard';
$A2B = new A2Billing();
$A2B -> load_conf($agi, NULL, 0, $idconfig);
$A2B -> mode = $mode;
$global_instance_table = new Table();
$A2B -> set_instance_table($global_instance_table);

$instance_table = new Table();
if (!$A2B -> DbConnect()) {
    exit;
}

define ("WRITELOG_QUERY", true);
$instance_table = new Table();
$A2B -> set_instance_table ($instance_table);

$table = new Table();
//$table -> debug_st = 1;
$mytopg = new MytoPg(0);	// debug level
$WHERE = 'id < \'100\'';
$WHEREREP = 'id < \'100\'';
$WWHERE = "WHERE $WHERE";
$WWHEREREP = "WHERE $WHEREREP";
$i = 0;

// the tests

//          0123 4567 8901
$teststr = "   '\"all\"'  ";
$t[$i++] = array("Parse_helper() Quote matching 1",
'$mytopg->Parse_helper("quote", $teststr, 0, $dbg, $d);',
'9', $teststr);

$t[$i++] = array("Parse_helper() Quote matching 2",
'$mytopg->Parse_helper("quote", $teststr, 3, $dbg, $d);',
'9', $teststr);

$t[$i++] = array("Parse_helper() Quote matching 3",
'$mytopg->Parse_helper("quote", $teststr, 4, $dbg, $d);',
'8', $teststr);

$t[$i++] = array("Parse_helper() Quote matching 4",
'$mytopg->Parse_helper("quote", $teststr, 5, $dbg, $d);',
'11', $teststr);

$t[$i++] = array("Parse_helper() Quote matching 5",
'$mytopg->Parse_helper("quote", $teststr, 8, $dbg, $d);',
'11', $teststr);

//          0123 4567 89012 3 4567 8 901234567 890123456 78901234 56789012 456 789
$teststr = "   '\"all\"'  '\\\"all\\\"'  ()(( \"('hello'\" )((\('\"\(all\))\"')\)))";
$t[$i++] = array("Parse_helper() Complex quote matching 1",
'$mytopg->Parse_helper("quote", $teststr, 4, $dbg, $d);',
'8', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 2",
'$mytopg->Parse_helper("quote", $teststr, 5, $dbg, $d);',
'28', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 3",
'$mytopg->Parse_helper("quote", $teststr, 8, $dbg, $d);',
'28', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 4",
'$mytopg->Parse_helper("quote", $teststr, 9, $dbg, $d);',
'12', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 5",
'$mytopg->Parse_helper("quote", $teststr, 10, $dbg, $d);',
'20', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 6",
'$mytopg->Parse_helper("quote", $teststr, 12, $dbg, $d);',
'20', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 7",
'$mytopg->Parse_helper("quote", $teststr, 13, $dbg, $d);',
'30', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 8",
'$mytopg->Parse_helper("quote", $teststr, 14, $dbg, $d);',
'30', $teststr);

$t[$i++] = array("Parse_helper() Complex quote matching 6",
'$mytopg->Parse_helper("quote", $teststr, 11, $dbg, $d);',
'20', $teststr);

$t[$i++] = array("Parse_helper() Bracket matching 1",
'$mytopg->Parse_helper("brace", $teststr, 0, $dbg, $d);',
'24', $teststr);

$t[$i++] = array("Parse_helper() Bracket matching 2",
'$mytopg->Parse_helper("brace", $teststr, 23, $dbg, $d);',
'24', $teststr);

$t[$i++] = array("Parse_helper() Bracket matching 3",
'$mytopg->Parse_helper("brace", $teststr, 24, $dbg, $d);',
'60', $teststr);  // yes this ignores the first ) as it would take nesting -ve

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 1",
'$mytopg->Parse_helper("brace", $teststr, 25, $dbg, $d);',
'60', $teststr);

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 2",
'$mytopg->Parse_helper("brace", $teststr, 26, $dbg, $d);',
'39', $teststr);

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 3",
'$mytopg->Parse_helper("brace", $teststr, 27, $dbg, $d);',
'59', $teststr);

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 4",
'$mytopg->Parse_helper("brace", $teststr, 28, $dbg, $d);',
'59', $teststr);

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 5",
'$mytopg->Parse_helper("brace", $teststr, 29, $dbg, $d);',
'53', $teststr);

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 6",
'$mytopg->Parse_helper("brace", $teststr, 30, $dbg, $d);',
'60', $teststr); // this starts in a quote, and goes double negative

$t[$i++] = array("Parse_helper() Complex bracket, quote and escaping matching 7",
'$mytopg->Parse_helper("brace", $teststr, 30, $dbg, $d);',
'60', $teststr);

$teststr = "'', '', '', '', '', '', '', '', '', '', ADDDATE( CURRENT_TIMESTAMP, INTERVAL  SECOND ), '', '', '30000'";
$t[$i++] = array("Parse_helper() quoted? 1",
'$mytopg->Parse_helper("quoted", $teststr, 0, $dbg, $d);',
'1', $teststr);

$teststr = "'', '', '', '', '', '', '', '', '', '', ADDDATE( CURRENT_TIMESTAMP, INTERVAL  SECOND ), '', '', '30000'";
$t[$i++] = array("Parse_helper() quoted? 2",
'$mytopg->Parse_helper("quoted", $teststr, 40, $dbg, $d);',
'0', $teststr);

$t[$i++] = array('MytoPg(): No rewrites of a query not needing them',
"SELECT count (*) FROM cc_call WHERE id = '34';",
"SELECT count (*) FROM cc_call WHERE id = '34';");

$t[$i++] = array("MytoPg(): isquoted (REGEXP)",
"REGEXP",
"~*");

$t[$i++] = array("MytoPg(): isquoted ('REGEXP')",
"'REGEXP'",
"'REGEXP'");

$t[$i++] = array('MytoPg(): isquoted ("REGEXP")',
'"REGEXP"',
'"REGEXP"');

$t[$i++] = array("MytoPg(): Complex isquoted ('\"REGEXP\"')",
"'\"REGEXP\"'",
"'\"REGEXP\"'");

$t[$i++] = array('MytoPg(): isquoted ("\'REGEXP\'")',
'"\'REGEXP\'"',
'"\'REGEXP\'"');

$t[$i++] = array('MytoPg(): REGEXP match operator',
"SELECT count (*) FROM cc_prefix WHERE destination REGEXP '^44';",
"SELECT count (*) FROM cc_prefix WHERE destination ~* '^44';");

$t[$i++] = array('MytoPg(): Simple CONCAT',
"SELECT (CONCAT('1','2'),CONCAT('3','4','5'),CONCAT(CONCAT('6','7'),'8','9'));",
"SELECT (('1' || '2'),('3' || '4' || '5'),(('6' || '7') || '8' || '9'));");

$t[$i++] = array('MytoPg(): Complex nested CONCAT with quotes, brackets and escapes',
"SELECT (CONCAT('1(','2('),CONCAT('3(','4(','\"5\"'),CONCAT(CONCAT('6)','7()'),'8\"()\"','9)(()'));",
"SELECT (('1(' || '2('),('3(' || '4(' || '\"5\"'),(('6)' || '7()') || '8\"()\"' || '9)(()'));");

$t[$i++] = array('MytoPg(): ADDDATE (1,3 params)',
"SELECT ADDDATE( CURRENT_TIMESTAMP, INTERVAL 30 SECOND);",
"SELECT ( CURRENT_TIMESTAMP + INTERVAL '30 SECOND');");

$t[$i++] = array('MytoPg(): SUBDATE (1,1 param)',
"SELECT SUBDATE(CURRENT_TIMESTAMP,30);",
"SELECT (CURRENT_TIMESTAMP - INTERVAL '30 DAYS');");

$t[$i++] = array('MytoPg(): SUBDATE (1,3 param)',
"SELECT DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY);",
"SELECT (CURRENT_DATE - INTERVAL '30 DAY');");

$t[$i++] = array('MytoPg(): DATE_SUB(1,3 param)',
"SELECT now() >= DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY);",
"SELECT now() >= (CURRENT_DATE - INTERVAL '5 DAY');");

$t[$i++] = array('MytoPg(): ADDDATE (1,3 params) with CAST',
"SELECT ADDDATE( '2008-01-01', INTERVAL 30 SECOND);",
"SELECT ( '2008-01-01'::date + INTERVAL '30 SECOND');");

$t[$i++] = array('MytoPg(): SUBDATE (1,1 param) with CAST',
"SELECT SUBDATE('2008-01-01 00:00:00',30);",
"SELECT ('2008-01-01 00:00:00'::timestamp - INTERVAL '30 DAYS');");

$t[$i++] = array("MytoPg(): datetime('now','localtime')",
"SELECT datetime('now','localtime');",
"SELECT (now());");

$t[$i++] = array('MytoPg(): SUBSTRING(time|date,1,10) -> cast ::date',
"SELECT substring(starttime,1,10) FROM cc_call $WWHERE;",
"SELECT (starttime::date) FROM cc_call $WWHEREREP;");

$t[$i++] = array('MytoPg(): SUBSTRING(time|date,1,10) -> cast ::date',
"SELECT SUBSTRING(t1.date_consumption , 1 , 10 ) FROM cc_card_package_offer AS t1;",
"SELECT (t1.date_consumption::date) FROM cc_card_package_offer AS t1;");

$t[$i++] = array('MytoPg(): SUBSTRING(time|date,0,8) -> add cast ::text',
"SELECT SUBSTRING(t1.date_consumption, 0, 8 ) FROM cc_card_package_offer AS t1;",
"SELECT SUBSTRING(t1.date_consumption::text, 0, 8 ) FROM cc_card_package_offer AS t1;");

$t[$i++] = array('MytoPg(): SUBSTRING(time|date,1,19) -> add cast ::timestamp',
"SELECT SUBSTRING(t1.date_consumption, 1, 19) FROM cc_card_package_offer AS t1;",
"SELECT (t1.date_consumption::timestamp) FROM cc_card_package_offer AS t1;");

$t[$i++] = array('MytoPg(): Complex nested REGEXP, REPLACE, and \'.\'',
"-- SELECT NOT '0' REGEXP REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(CONCAT('^', '_XXX(911|999|112)', '$'), 'X', '[0-9]'), 'Z', '[1-9]'), 'N', '[2-9]'), '.', '.+'), '_', '');",
"-- SELECT NOT '0' ~* REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(('^' ||  '_XXX(911|999|112)' ||  '$'), 'X', '[0-9]', 'g'), 'Z', '[1-9]', 'g'), 'N', '[2-9]', 'g'), E'\\\\.', E'\\\\.+', 'g'), '_', '', 'g');");

$t[$i++] = array('MytoPg(): RAND()',
"SELECT * FROM cc_call $WWHERE ORDER BY RAND();",
"SELECT * FROM cc_call $WWHEREREP ORDER BY RANDOM();");

$t[$i++] = array('MytoPg(): LIMIT X,X',
"SELECT * FROM cc_call $WWHERE LIMIT 5,3;",
"SELECT * FROM cc_call $WWHEREREP LIMIT 3 OFFSET 5;");

$t[$i++] = array('MytoPg(): LIMIT X , X ',
"SELECT * FROM cc_call $WWHERE ORDER BY RAND() LIMIT 25 , 30 ;",
"SELECT * FROM cc_call $WWHEREREP ORDER BY RANDOM() LIMIT 30  OFFSET  25 ;");

$t[$i++] = array("MytoPg(): TIMESTAMP",
"UPDATE cc_call WHERE SUBSTRING(date,1,10) <= TIMEDIFF(abc,TIMESTAMP('2009-01-18 16:45:00'))",
"UPDATE cc_call WHERE (date::date) <= (abc - ('2009-01-18 16:45:00'::timestamp))");

$t[$i++] = array("MytoPg(): A2B_trunk_report.php ALOC query",
"SELECT (SUM( TIME_TO_SEC( TIMEDIFF( c.stoptime, c.starttime ) ) /60) / count( c.id )) AS ALOC, count( c.id ) AS total_calls FROM cc_call AS c $WWHERE",
"SELECT (SUM( EXTRACT(EPOCH FROM  ( c.stoptime -  c.starttime ) ) /60) / count( c.id )) AS ALOC, count( c.id ) AS total_calls FROM cc_call AS c $WWHEREREP");

$t[$i++] = array("MytoPg(): A2B_trunk_report.php CIC query",
"SELECT count( c.id ) AS CIC FROM cc_call c WHERE TIME_TO_SEC( TIMEDIFF(stoptime, starttime))/60 <= 10 AND $WHERE",
"SELECT count( c.id ) AS CIC FROM cc_call c WHERE EXTRACT(EPOCH FROM  (stoptime -  starttime))/60 <= 10 AND $WHEREREP");

$t[$i++] = array("MytoPg(): ADDDATE(1,3) from call-daily-load.php date clause",
"SELECT ADDDATE('2008-12-31' ,INTERVAL 1 DAY) ;",
"SELECT ('2008-12-31'::date  + INTERVAL '1 DAY') ;");

$t[$i++] = array("MytoPg(): AGI/a2billing.php callback spool",
"INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout ) VALUES ('', '', '', '', '', '', '', '', '', '', ADDDATE( CURRENT_TIMESTAMP, INTERVAL 1 SECOND ), '', '', '')",
"INSERT INTO cc_callback_spool (uniqueid, status, server_ip, num_attempt, channel, exten, context, priority, variable, id_server_group, callback_time, account, callerid, timeout ) VALUES ('', '', '', '', '', '', '', '', '', '', ( CURRENT_TIMESTAMP + INTERVAL '1 SECOND'), '', '', '')");

$t[$i++] = array("MytoPg(): admin/api/SOAP/callback-exec.php time clause",
"SELECT '', '', '', '', '', '', '', '', '', '', ADDDATE( CURRENT_TIMESTAMP, INTERVAL 1 SECOND ), '', '', '30000'",
"SELECT '', '', '', '', '', '', '', '', '', '', ( CURRENT_TIMESTAMP + INTERVAL '1 SECOND'), '', '', '30000'");

$t[$i++] = array("MytoPg(): admin/Public/graph_hourdetail.php date clause",
" AND t1.starttime < '12-12 12:00:00' AND t1.starttime >= '12-12 00:00:00' ",
" AND t1.starttime < '12-12 12:00:00' AND t1.starttime >= '12-12 00:00:00' ");

$t[$i++] = array("MytoPg(): admin/Public/call-daily-load.php date clause",
"SELECT * FROM cc_call AS t1 $WWHERE AND t1.starttime < ADDDATE('2008-01-01',INTERVAL 1 DAY) AND t1.starttime >= '2008-01-01'",
"SELECT * FROM cc_call AS t1 $WWHEREREP AND t1.starttime < ('2008-01-01'::date + INTERVAL '1 DAY') AND t1.starttime >= '2008-01-01'");

$t[$i++] = array("MytoPg(): admin/Public/call-comp.php date clause with ADD/SUBDATE and INTERVAL",
"SELECT * FROM cc_call AS t1 $WWHERE AND t1.starttime < ADDDATE('2008-01-01',INTERVAL 1 DAY) AND t1.starttime >= SUBDATE('2008-01-01', INTERVAL 1 DAY)",
"SELECT * FROM cc_call AS t1 $WWHEREREP AND t1.starttime < ('2008-01-01'::date + INTERVAL '1 DAY') AND t1.starttime >= ('2008-01-01'::date - INTERVAL '1 DAY')");

$t[$i++] = array("MytoPg(): common/lib/Form/Class.FormHandler.inc.php's TIMESTAMP() cast",
"SELECT TIMESTAMP('2009-01-15') >= TIMESTAMP('2008-01-01')",
"SELECT ('2009-01-15'::timestamp) >= ('2008-01-01'::timestamp)");

$t[$i++] = array("MytoPg(): common/lib/Class.A2Billing.php's UNIX_TIMESTAMP() cast",
"SELECT * FROM cc_card $WWHERE AND UNIX_TIMESTAMP(expirationdate) >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP)",
"SELECT * FROM cc_card $WWHEREREP AND date_part('epoch',expirationdate) >= date_part('epoch',CURRENT_TIMESTAMP)");

$t[$i++] = array("MytoPg(): common/lib/Class.A2Billing.php's UNIX_TIMESTAMP() cast 2",
"SELECT * FROM cc_card WHERE UNIX_TIMESTAMP(cc_card.creationdate) >= UNIX_TIMESTAMP(CURRENT_TIMESTAMP)",
"SELECT * FROM cc_card WHERE date_part('epoch',cc_card.creationdate) >= date_part('epoch',CURRENT_TIMESTAMP)");

$t[$i++] = array("MytoPg(): admin/public/modules/refills_lastmonth.php's DATE_FORMAT(x, '%Y-%m-01')",
"SELECT DATE_FORMAT( date, '%Y-%m-01' ) FROM cc_logrefill;",
"SELECT (date_trunc('month', date)::date) FROM cc_logrefill;");

$t[$i++] = array("MytoPg(): admin/public/modules/refills_lastmonth.php's MONTH(date)",
"SELECT MONTH(date) FROM cc_logrefill GROUP BY MONTH( date ) ORDER BY MONTH(date);",
"SELECT date_part('MONTH',date) FROM cc_logrefill GROUP BY date_part('MONTH', date ) ORDER BY date_part('MONTH',date);");

$t[$i++] = array("MytoPg(): admin/public/modules/refills_lastmonth.php's whole query",
"SELECT UNIX_TIMESTAMP( DATE_FORMAT( date, '%Y-%m-01' ) ) , count( * )  FROM cc_logrefill WHERE date >= TIMESTAMP( '2000-01-01' ) AND date <=CURRENT_TIMESTAMP GROUP BY MONTH( date ),date ORDER BY date;",
"SELECT date_part('epoch', (date_trunc('month', date)::date) ) , count( * )  FROM cc_logrefill WHERE date >= ( '2000-01-01' ::timestamp) AND date <=CURRENT_TIMESTAMP GROUP BY date_part('MONTH', date ),date ORDER BY date;");

$t[$i++] = array("MytoPg(): admin/Public/call-count-reporting.php's use of literal dates",
"select UNIX_TIMESTAMP('2008-06-01');",
"select date_part('epoch','2008-06-01'::date);");

$t[$i++] = array("MytoPg(): admin/Public/call-count-reporting.php's use of literal dates",
"select UNIX_TIMESTAMP('2008-06-01 23:59:59');",
"select date_part('epoch','2008-06-01 23:59:59'::timestamp);");

$t[$i++] = array("MytoPg(): Cast_date_part's timezone support",
"SELECT DATETIME('2009-01-01 00:00:00 +0100') <= DATETIME('2009-01-01 00:00:00 UTC'   )",
"SELECT ('2009-01-01 00:00:00 +0100'::timestamp) <= ('2009-01-01 00:00:00 UTC'::timestamp   )");

$t[$i++] = array("MytoPg(): Cast_date_part's timezone support and non-zero-padded times and dates",
"SELECT DATETIME('2009-1-1 0:0:0 GMT') <= DATETIME('2009-1-1 0:0:0 -1130')",
"SELECT ('2009-1-1 0:0:0 GMT'::timestamp) <= ('2009-1-1 0:0:0 -1130'::timestamp)");

#$t[$i++] = array("MytoPg():","","");

// queries starting with SELECT are passed to the SQL server for more testing
// to prevent this simply prefix with -- (or anything else but whitespace)

// When doing detailed debugging, get a better visual layout like this:
//  test-0001-MytoPg.php 2>&1 | sed -e 's/>>>>/\n/g'
//  (or for syslog:)  tail -F /var/log/messages | sed -e 's/>>>>/\n/g'
$dbg = 0;		// 0: CPU hogs,  1: overview,  2: detail 3:more 4:all
$times = 1;	// average time this many runs, >=30 for less jitter when optimising
$fail = 0;	// count the number of test failures
$pass = 0;	// count the number of test passes
$res = '';	// a temporary holder for SQL results
$total_time = 0;
for ($i = 0;  $i < sizeof($t);  $i++) {
    if ($dbg) print "\n";
    $time = 0;
    if (preg_match('/My_to_Pg|Parse_helper|mytopg/i',$t[$i][1])) {
        $teststr = $t[$i][3];
        for ($loop = 0; $loop < $times; $loop++) {
            $s = microtime(true);
            eval('$out = '.$t[$i][1]);
            $time += (microtime(true)-$s)*1000;
            if ($res === FALSE || $out != $t[$i][2]) {
                $loop++;
                break;
            }
        }
    } else {
        for ($loop = 0; $loop < $times; $loop++) {
            $out = $t[$i][1];
            $s = microtime(true);
            $mytopg->My_to_Pg($out);
            $time += (microtime(true)-$s)*1000;
            if ($out != $t[$i][2]) {
                $loop++;
                break;
            }
        }
    }
    $total_time += $time/$loop;

    $res = sprintf("%0.3f", $time/$loop)."ms\ttest $i: ".$t[$i][0];
    if ($out != $t[$i][2]) {
        $fail++;
        print "FAILED $res\n";
        if (preg_replace('/[[:space:]]+/', '', $out) == preg_replace('/[[:space:]]+/', '', $t[$i][2])) {
            print "But matches except for whitespace\n";
        }
        for ($j = 0; $j < 80 ; $j++) { print $j % 10; }
        if (preg_match('/My_to_Pg|Parse_helper|mytopg/i',$t[$i][1])) print "\n".$t[$i][3].' < test string\n';
        print "\n".$t[$i][1]."\n";
        print "".$out."\n\t != \n".$t[$i][2]."\n\n\n";
        if ($dbg) exit;

    } else {
        $pass++;
        print "PASSED $res";
        if (preg_match('/^\s*SELECT\s*/i', $out)) {
            $result = $A2B -> instance_table -> SQLExec ($A2B->DBHandle, $out, 1, 300);
            if ($A2B -> DBHandle -> ErrorNo() != 0) {
                print " (Running SQL query failed!)\n";
                print "$out\n";
                print $A2B -> DBHandle -> ErrorMsg() . "\n\n";
            } else {
                print "\n";
            }
        } else {
            print "\n";
        }
    }
    if ($dbg > 1) print "\n\n";
}

print ($fail?'FAILED!!! Only passed':'PASSED')." $pass/".($pass+$fail)." tests.\tTotal time: $total_time ms\n";

exit ($fail);
