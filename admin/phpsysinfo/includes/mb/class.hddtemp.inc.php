<?php

// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// $Id: class.hddtemp.inc.php,v 1.7 2007/01/21 13:17:20 bigmichi1 Exp $

class hddtemp {
	
	function temperature($hddtemp_avail) {
		$ar_buf = array();
		$results = array();
		switch ($hddtemp_avail) {
			case "tcp":
				// Timo van Roermund: connect to the hddtemp daemon, use a 5 second timeout.
				$fp = fsockopen('localhost', 7634, $errno, $errstr, 5);
				// if connected, read the output of the hddtemp daemon
				if ($fp) {
					// read output of the daemon
					$lines = '';
					while (!feof($fp)) {
						$lines .= fread($fp, 1024);
					}
					// close the connection
					fclose($fp);
				} else {
					die("HDDTemp error: " . $errno . ", " . $errstr);
				}
				$lines = str_replace("||", "|\n|", $lines);
				$ar_buf = explode("\n", $lines);
				break;
			case "suid":
				$strDrives = "";
				$strContent = rfts( "/proc/diskstats", 0, 4096, false );
				if( $strContent != "ERROR" ) {
					$arrContent = explode( "\n", $strContent );
					foreach( $arrContent as $strLine ) {
						preg_match( "/^\s(.*)\s([a-z]*)\s(.*)/", $strLine, $arrSplit );
						if( !empty( $arrSplit[2] ) ) {
						    $strDrive = '/dev/' . $arrSplit[2];
						    if( file_exists( $strDrive ) ) {
							$strDrives = $strDrives . $strDrive . ' ';
						    }							
						}
					}
				} else {
				    $strContent = rfts( "/proc/partitions", 0, 4096, false );
				    if( $strContent != "ERROR" ) {
					$arrContent = explode( "\n", $strContent );
					foreach( $arrContent as $strLine ) {
						if( !preg_match( "/^\s(.*)\s([\/a-z0-9]*(\/disc))\s(.*)/", $strLine, $arrSplit ) ) {
						    preg_match( "/^\s(.*)\s([a-z]*)\s(.*)/", $strLine, $arrSplit );
						}
						if( !empty( $arrSplit[2] ) ) {
						    $strDrive = '/dev/' . $arrSplit[2];
						    if( file_exists( $strDrive ) ) {
							$strDrives = $strDrives . $strDrive . ' ';
						    }
						}
					}
				    }
				}
				
				if( trim( $strDrives ) == "" ) {
					return array();
				}

				$hddtemp_value = execute_program("hddtemp", $strDrives);
				$hddtemp_value = explode("\n", $hddtemp_value);
				foreach($hddtemp_value as $line) {
					$temp = preg_split("/:\s/", $line, 3);
					if(count($temp) == 3 && preg_match("/^[0-9]/", $temp[2])) {
						list($temp[2], $temp[3]) = (preg_split("/\s/", $temp[2]));
						array_push( $ar_buf, "|" . implode("|", $temp) . "|");
					}
				}
				break;
			default:
				die("Bad hddtemp configuration in config.php");
		}
		
		// Timo van Roermund: parse the info from the hddtemp daemon.
		$i = 0;
		foreach($ar_buf as $line) {
			$data = array();
			if (preg_match("/\|(.*)\|(.*)\|(.*)\|(.*)\|/", $line, $data)) {
				if( trim($data[3]) != "ERR" ) {
					// get the info we need
					$results[$i]['label'] = $data[1];
					$results[$i]['value'] = $data[3];
					$results[$i]['model'] = $data[2];
					$i++;
				}
			}
		}
		
		return $results;
	}
}
?>
