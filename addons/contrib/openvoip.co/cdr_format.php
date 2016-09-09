<?php
/*
INPUT:
"4528339244","000","375232569865","a2billing","000","SIP/4528339244-00000013","SIP/375232569865@carrier_15_1-0000000c;1","Dial","Local/375232569865@a2billing_test,60,HRrL(5400000:61000:30000)","2016-08-04 19:52:29","2016-08-04 19:52:30","2016-08-04 19:54:22",113,112,"BUSY","BILLING","A2B03-1470340349.43","375232569865"
"4528339244","000","375232569865","a2billing","000","SIP/4528339244-00000014","SIP/375232569865@carrier_15_2-0000000d;1","Dial","Local/375232569865@a2billing_test,60,HRrL(5400000:61000:30000)","2016-08-04 20:24:13",,"2016-08-04 20:24:13",45,38,"ANSWERED","BILLING","A2B03-1470340349.43","375232569865"
"4528339244","000","79187700476","a2billing","000","SIP/4528339244-00000015","SIP/79187700476@carrier_16_1-0000000e;1","Dial","Local/79187700476@a2billing_test,60,HRrL(5400000:61000:30000)","2016-08-09 20:03:38","2016-08-09 20:03:39","2016-08-09 20:03:57",19,18,"ANSWERED","BILLING","A2B03-1470773018.49","79187700476"

0. accountcode: What account number to use: Asterisk billing account, (string, 20 characters)
1. src: Caller*ID number (string, 80 characters)
2. dst: Destination extension (string, 80 characters)
3. dcontext: Destination context (string, 80 characters)
4. clid: Caller*ID with text (80 characters)
5. channel: Channel used (80 characters)
6. dstchannel: Destination channel if appropriate (80 characters)
7. lastapp: Last application if appropriate (80 characters)
8. lastdata: Last application data (arguments) (80 characters)
9. start: Start of call (date/time)
10. answer: Answer of call (date/time)
11. end: End of call (date/time)
12. duration: Total time in system, in seconds (integer)
13. billsec: Total time call is up, in seconds (integer)
14. disposition: What happened to the call: ANSWERED, NO ANSWER, BUSY, FAILED
15. amaflags: What flags to use: see amaflags::DOCUMENTATION, BILL, IGNORE etc, specified on a per channel basis like accountcode.
16. uniqueid: Unique Channel Identifier (32 characters)

OUTPUT:
FromT1 ToT1   Date       CallTime Duration   Dialed number           CarrierID+ITUT Number   Caller ID
048-20 036-03 12-31-2015 23:57:05 00:07:53 A 01173472630592          01573472630592          3108294615

MAPPING:
FromT1
091-00 - a2b01
092-00 - ab202
ToT1: extract carrier_gateway from dstchannelm for example carrier_5_1 = 005-01
CallTime: call start time
A - means ANSWERED, R - should mean that attempt made, but failed and next trunk attempted, " " (empty space) - other status
Dialed number - 23 symbols! for 011 - remove 011, for all - add carrier id (3 symbols for example 015)
CarrierID+ITUT Number - 23 symbols!
*/

$cdr_file = $argc >= 2 ? $argv[1] : "";
if (!file_exists($cdr_file))
    exit(1);

$handle = fopen($cdr_file, 'r');
if (!$handle)
    exit(1);

$csv2 = null;
do {
    if (empty($csv2)) {
        $csv1 = get_csv($handle);
    } else {
        $csv1 = $csv2;
    }
    $csv2 = get_csv($handle);
    if ($csv1 === false)
        break;

    process_cdr($csv1, $csv2);
} while (1);
fclose($handle);


function get_csv($handle) {
    $result = false;
    while(($line = fgets($handle)) !== false) {
        $csv = str_getcsv($line);
        if (!is_array($csv) || count($csv) < 17)
            continue;
        if ($csv[7] != 'Dial') // need only Dial app cdrs
            continue;
        $result = $csv;
        break;
    }
    return $result;
}

function process_cdr($csv, $next_csv) {
    if (!$csv)
        return;

    // detect from
    $from = '';
    if ($csv[0] == '4528339244') {
        $from = '091-00';
    } elseif ($csv[0] == '0744553513') {
        $from = '092-00';
    }

    // detect to, cdr format
    $carrier = '';
    $to = '';
    if (preg_match('/[A-Z0-9]+\/carrier\_(\d+)\_(\d+)/', $csv[6], $matches) || preg_match('/[A-Z0-9]+\/\d+\@carrier\_(\d+)\_(\d+)/', $csv[6], $matches)) {
        $carrier = $matches[1];
        $to = sprintf('%03d-%02d', $carrier, $matches[2]);
    }

    // detect date
    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $csv[9]);

    // detect duration
    $duration = gmdate("H:i:s", $csv[13]);

    // detect answer flag
    $flag = '';
    if ($csv[14] == 'ANSWERED') {
        $flag = 'A'; // answer
    } elseif ($next_csv && $csv[16] == $next_csv[16]) {
        $flag = 'R'; // retry, next one is the result
    }

    // detect dialed number
    $number = sprintf('%03d', $carrier) . preg_replace('/^011/', '', $csv[2]);

    // output data
    printf("%-6s %-6s %-10s %-8s %-8s %1s %-23s %-23s %-23s %s\r\n", $from, $to, $datetime->format('m-d-Y'), $datetime->format('H:i:s'), $duration, $flag, $csv[2], $number, $csv[1], $csv[16]);
}
