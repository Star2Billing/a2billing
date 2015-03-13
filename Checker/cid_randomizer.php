#!/usr/bin/php -q
<?php

/**
 * CallerID randomizer checker.
 * It gets CID randomly from file but never repeats CID until ALL CIDs were used.
 * 
 * How to use: exten => s,n,AGI(cid_randomizer.php,PATH/TO/FILE/WITH/CIDS); one CID per line
 * 
 * @author Roman Davydov <openvoip.co@gmail.com>
 * @license http://openvoip.co Free (just keep reference to my name and my site)
 * 
 */

error_reporting(E_ALL ^ E_NOTICE);
require_once('phpagi.php');

$agi = new AGI();
$filename = ($argc > 1 ? $argv[1] : '');

if (!file_exists($filename)) {
    $agi->verbose("CID filename not found");
    exit;
}

if ($cid = getNextNumber($filename)) {
    if (preg_match("/x/i", $cid)) {
        for ($i = 0; $i < strlen($cid); $i++) {
            switch (strtolower($cid[$i])) {
                case 'x':
                    $cid[$i] = rand(0, 9);
                    break;
                case 'z':
                    $cid[$i] = rand(1, 9);
                    break;
                case 'n':
                    $cid[$i] = rand(2, 9);
                    break;
            }
        }
    }
    $agi->verbose("New CID: " . $cid);
    $agi->set_variable('CALLERID(num)', $cid);
}

// go back to the dialplan
exit;


// functions

function getNextNumber($filename) {
    // check the cache
    $modified = filemtime($filename);
    $cache_filename = '/tmp/' . basename($filename) . '_' . ($modified === false ? '' : date('U', $modified));
    $cache = array();
    if (file_exists($cache_filename) && is_readable($cache_filename)) {
        $cache = unserialize(file_get_contents($cache_filename));
        if (!is_array($cache)) {
            $cache = array();
        }
    }
    
    // getting data
    $data = file_get_contents($filename);
    $number = null;
    if ($data !== false && strlen($data) > 0) {
        $cids = preg_split("/(\n)|(\r\n)|(\n\r)/m", $data);
        if (is_array($cids) && count($cids) > 0) {
            $cids = array_map('trim', $cids);
            $cids = array_filter($cids);
            $cidsn = count($cids);
            if ($cidsn > 0) {
                while(true) {
                    $tmp_number = $cids[rand(0, $cidsn - 1)];
                    if (!in_array($tmp_number, $cache)) {
                        $cache[] = $tmp_number;
                        $number = $tmp_number;
                        break;
                    } else {
                        if (count($cache) >= $cidsn) {
                         $cache = array($tmp_number);
                         $number = $tmp_number;
                         break;
                        }
                    }
                }
            }
        }
    }
    
    // update cache
    if ($number != null) {
        file_put_contents($cache_filename, serialize($cache));
    }

    return $number;
}
