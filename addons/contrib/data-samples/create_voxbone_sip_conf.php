#!/usr/bin/php -q
<?php
/***************************************************************************
 *
 *            create_voxbone_sip_conf.php
 *
 *
 *  25 July 2008
 *  Purpose: create sip.conf file to configure VoxBone DID and grant their IPs
 *
 *  USAGE : ./create_voxbone_sip_conf.php > voxbone_sip.conf
 *
****************************************************************************/

// LOAD Voxbone IPs
$lines = file('voxbone_IPs.txt');
$context = "a2billing-did";

// Loop through the voxbone_IPs file
foreach ($lines as $cur_ip) {
    $cur_ip = trim($cur_ip);
    echo "[$cur_ip]\nhost = $cur_ip\ntype = friend\ninsecure = very\ncontext = $context\ncanreinvite=no\n\n";
}
