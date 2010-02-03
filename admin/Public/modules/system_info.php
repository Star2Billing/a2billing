<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
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
include_once ('../../lib/admin.defines.php');
include_once ('../../lib/admin.module.access.php');

if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}


function execute_program ($strProgramname, $strArgs = '', $booErrorRep = true ) {
	$strProgram = find_program($strProgramname);
	// see if we've gotten a |, if we have we need to do patch checking on the cmd
	if( $strArgs ) {
		$arrArgs = split( ' ', $strArgs );
		for( $i = 0; $i < count( $arrArgs ); $i++ ) {
			if ( $arrArgs[$i] == '|' ) {
				$strCmd = $arrArgs[$i + 1];
				$strNewcmd = find_program( $strCmd );
				$strArgs = ereg_replace( "\| " . $strCmd, "| " . $strNewcmd, $strArgs );
			}
		}
	}
	// no proc_open() below php 4.3
	if( function_exists( 'proc_open' ) ) {
		$descriptorspec = array(
			0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
			1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
			2 => array("pipe", "w")   // stderr is a pipe that the child will write to
		);
		$process = proc_open( $strProgram . " " . $strArgs, $descriptorspec, $pipes );
		if( is_resource( $process ) ) {
			while( !feof( $pipes[1] ) ) {
				$strBuffer .= fgets( $pipes[1], 1024 );
			}
			fclose( $pipes[1] );
		}
		$return_value = proc_close( $process );
	} else {
		if( $fp = popen( "(" . $strProgram . " " . $strArgs . " > /dev/null) 3>&1 1>&2 2>&3", 'r' ) ) {
			pclose( $fp );
		}
		if( $fp = popen( $strProgram . " " . $strArgs, 'r' ) ) {
			while( ! feof( $fp ) ) {
				$strBuffer .= fgets( $fp, 4096 );
			}
			$return_value = pclose( $fp );
		}
	}
	$strBuffer = trim( $strBuffer );	
	
	return $strBuffer;
	}	


// Find a system program.  Do path checking
function find_program ($strProgram) {
	global $addpaths;
	
	$arrPath = array( '/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin' );
	if( isset( $addpaths ) && is_array( $addpaths ) ) {
		$arrPath = array_merge( $arrPath, $addpaths );
	}
	if ( function_exists( "is_executable" ) ) {
		foreach ( $arrPath as $strPath ) {
			$strProgrammpath = $strPath . "/" . $strProgram;
			if( is_executable( $strProgrammpath ) ) {
				return $strProgrammpath;
			}
		}
	} else {
		return strpos( $strProgram, '.exe' );
	}
}



$distro_info = execute_program('lsb_release','-d 2> /dev/null', false);  // We have the '2> /dev/null' because Ubuntu gives an error on this command which causes the distro to be unknown
$info_tmp = split(':', $distro_info, 2);
$OS = trim($info_tmp[1]);
$OS_img = split(' ', $OS);

$info_tmp = split(" - ", COPYRIGHT);
$UI = $info_tmp[0].' '.$info_tmp[1];

$UI_path = '';
$info_tmp = split('/', $_SERVER["SCRIPT_FILENAME"]);
foreach($info_tmp as $value){
	if($value != 'admin')
		$UI_path .= $value . '/';
	else
		break;
}

$DBHandle = DbConnect();
$rs = $DBHandle -> Execute('SELECT VERSION();');
$rs = $rs -> FetchRow();
$info_tmp = split('-', $rs[0], 2);
$mysql = $info_tmp[0];

$rs = $DBHandle -> Execute('SELECT * FROM cc_version;');
$rs = $rs -> FetchRow();
$database = $rs[0];

$asterisk = str_replace('_','.',ASTERISK_VERSION);
$php = phpversion();
$ip_address = $_SERVER['REMOTE_ADDR'];
$server_ip_address = $_SERVER['SERVER_ADDR'];
$server_name = $_SERVER['SERVER_NAME'];

?>

<?php echo gettext("Operation System Version");?>&nbsp;:&nbsp;<img height="15" src="templates/default/images/OSicon/<?php echo $OS_img[0]; ?>.png">&nbsp;<?php echo $OS; ?><br/>
<?php echo gettext("Asterisk Version");?>&nbsp;:&nbsp;<?php echo $asterisk; ?><br/>
<?php echo gettext("PHP Version");?>&nbsp;:&nbsp;<?php echo $php; ?><br/>

<?php echo gettext("A2B DataBase Version");?>&nbsp;:&nbsp;<?php echo $database; ?><br/>
<?php echo gettext("User Interface");?>&nbsp;:&nbsp;<img height="15" src="templates/default/images/favicon.ico">&nbsp;<?php echo $UI; ?><br/>
<?php echo gettext("User Interface Path");?>&nbsp;:&nbsp;<?php echo $UI_path; ?><br/><br/>
<?php echo gettext("Server Name");?>&nbsp;:&nbsp;<font style="text-decoration: underline"><?php echo $server_name; ?></font><br/>
<?php echo gettext("Server Ip Address");?>&nbsp;:&nbsp;<font style="text-decoration: underline"><?php echo $server_ip_address; ?></font><br/>
<?php echo gettext("You Ip Address");?>&nbsp;:&nbsp;<font style="text-decoration: underline"><?php echo $ip_address; ?></font><br/>

<?php echo gettext("MYSQL");?>&nbsp;:&nbsp;<?php echo $mysql; ?><br/>


