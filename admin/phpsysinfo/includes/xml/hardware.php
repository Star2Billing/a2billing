<?php 
/***************************************************************************
 *   Copyright (C) 2006 by phpSysInfo - A PHP System Information Script    *
 *   http://phpsysinfo.sourceforge.net/                                    *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

// $Id: hardware.php,v 1.36 2007/01/21 11:13:51 bigmichi1 Exp $

function xml_hardware() {
	global $sysinfo;
	global $text;
	
	$strPcidevices = ""; $strIdedevices = ""; $strUsbdevices = ""; $strScsidevices = "";
	
	$arrSys = $sysinfo->cpu_info();
	
	$arrBuf = finddups( $sysinfo->pci() );
	if( count( $arrBuf ) ) {
		for( $i = 0, $max = sizeof($arrBuf); $i < $max; $i++ ) {
			if( $arrBuf[$i] ) {
				$strPcidevices .= "      <Device><Name>" . htmlspecialchars( chop( $arrBuf[$i] ), ENT_QUOTES ) . "</Name></Device>\n";
			}
		}
	}
	
	$arrBuf = $sysinfo->ide();
	if( count( $arrBuf ) ) {
		foreach( $arrBuf as $strKey => $arrValue ) {
			$strIdedevices .= "      <Device>\n<Name>" . htmlspecialchars( $strKey . ': ' . $arrValue['model'], ENT_QUOTES ) . "</Name>\n";
			if( isset( $arrValue['capacity'] ) ) {
				$strIdedevices .= '<Capacity>' . htmlspecialchars( $arrValue['capacity'], ENT_QUOTES ) . '</Capacity>';
			}
		$strIdedevices .= "</Device>\n";
		} 
	} 
	
	$arrBuf = $sysinfo->scsi();
	if( count( $arrBuf ) ) {
		foreach( $arrBuf as $strKey => $arrValue ) {
			$strScsidevices .= "<Device>\n";
			if( $strKey >= '0' && $strKey <= '9' ) {
				$strScsidevices .= "      <Name>" . htmlspecialchars( $arrValue['model'], ENT_QUOTES ) . "</Name>\n";
			} else {
				$strScsidevices .= "      <Name>" . htmlspecialchars( $strKey . ': ' . $arrValue['model'], ENT_QUOTES ) . "</Name>\n";
			}
			if( isset( $arrrValue['capacity'])) {
				$strScsidevices .= '<Capacity>' . htmlspecialchars( $arrValue['capacity'], ENT_QUOTES ) . '</Capacity>';
			}
			$strScsidevices .= "</Device>\n";
		}
	}
	
	$arrBuf = finddups( $sysinfo->usb() );
	if( count( $arrBuf ) ) {
		for( $i = 0, $max = sizeof( $arrBuf ); $i < $max; $i++ ) {
			if( $arrBuf[$i] ) {
				$strUsbdevices .= "      <Device><Name>" . htmlspecialchars( chop( $arrBuf[$i] ), ENT_QUOTES ) . "</Name></Device>\n";
			}
		}
	}
	
	$_text = "  <Hardware>\n";
	$_text .= "    <CPU>\n";
	if( isset( $arrSys['cpus'] ) ) {
		$_text .= "      <Number>" . htmlspecialchars( $arrSys['cpus'], ENT_QUOTES ) . "</Number>\n";
	}
	if( isset( $arrSys['model'] ) ) {
		$_text .= "      <Model>" . htmlspecialchars( $arrSys['model'], ENT_QUOTES ) . "</Model>\n";
	}
	if( isset( $arrSys['temp'] ) ) {
		$_text .= "      <Cputemp>" . htmlspecialchars( $arrSys['temp'], ENT_QUOTES ) . "</Cputemp>\n";
	}
	if( isset( $arrSys['cpuspeed'] ) ) {
		$_text .= "      <Cpuspeed>" . htmlspecialchars( $arrSys['cpuspeed'], ENT_QUOTES ) . "</Cpuspeed>\n";
	}
	if( isset( $arrSys['busspeed'] ) ) {
		$_text .= "      <Busspeed>" . htmlspecialchars( $arrSys['busspeed'], ENT_QUOTES ) . "</Busspeed>\n";
	}
	if( isset( $arrSys['cache'] ) ) {
		$_text .= "      <Cache>" . htmlspecialchars( $arrSys['cache'], ENT_QUOTES ) . "</Cache>\n";
	}
	if( isset( $arrSys['bogomips'] ) ) {
		$_text .= "      <Bogomips>" . htmlspecialchars( $arrSys['bogomips'], ENT_QUOTES ) . "</Bogomips>\n";
	}
	$_text .= "    </CPU>\n";
	$_text .= "    <PCI>\n";
	if( $strPcidevices) {
		$_text .= $strPcidevices;
	}
	$_text .= "    </PCI>\n";
	$_text .= "    <IDE>\n";
	if( $strIdedevices) {
		$_text .= $strIdedevices;
	}
	$_text .= "    </IDE>\n";
	$_text .= "    <SCSI>\n";
	if( $strScsidevices) {
		$_text .= $strScsidevices;
	}
	$_text .= "    </SCSI>\n";
	$_text .= "    <USB>\n";
	if($strUsbdevices) {
		$_text .= $strUsbdevices;
	}
	$_text .= "    </USB>\n";
	$_text .= "  </Hardware>\n";

    return $_text;
} 

function html_hardware () {
	global $XPath;
	global $text;
	
	$strPcidevices = ""; $strIdedevices = ""; $strUsbdevices = ""; $strScsidevices = "";
	
	$textdir = direction();
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/Hardware/PCI" ) ); $i < $max; $i++ ) {
		if( $XPath->match( "/phpsysinfo/Hardware/PCI/Device[" . $i . "]/Name" ) ) {
			$strPcidevices .= "<tr><td valign=\"top\"><font size=\"-1\">-</font></td><td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/PCI/Device[" . $i . "]/Name" ) . "</font></td></tr>";
		}
	}
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/Hardware/IDE" ) ); $i < $max; $i++ ) {
		if( $XPath->match( "/phpsysinfo/Hardware/IDE/Device[" . $i . "]" ) ) {
			$strIdedevices .= "<tr><td valign=\"top\"><font size=\"-1\">-</font></td><td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/IDE/Device[" . $i . "]/Name" );
			if( $XPath->match( "/phpsysinfo/Hardware/IDE/Device[" . $i . "]/Capacity" ) ) {
				$strIdedevices .= " (" . $text['capacity'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Hardware/IDE/Device[" . $i . "]/Capacity" ) / 2 ) . ")";
			}
			$strIdedevices .=  "</font></td></tr>";
		}
	}
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/Hardware/SCSI" ) ); $i < $max; $i++ ) {
		if( $XPath->match( "/phpsysinfo/Hardware/SCSI/Device[" . $i . "]" ) ) {
			$strScsidevices .= "<tr><td valign=\"top\"><font size=\"-1\">-</font></td><td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/SCSI/Device[" . $i . "]/Name" );
			if( $XPath->match( "/phpsysinfo/Hardware/SCSI/Device[" . $i . "]/Capacity" ) ) {
				$strScsidevices .= " (" . $text['capacity'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Hardware/SCSI/Device[" . $i . "]/Capacity" ) / 2 ) . ")";
			}
			$strScsidevices .=  "</font></td></tr>";
		}
	}
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/Hardware/USB" ) ); $i < $max; $i++ ) {
		if( $XPath->match( "/phpsysinfo/Hardware/USB/Device[" . $i . "]/Name" )) {
			$strUsbdevices .= "<tr><td valign=\"top\"><font size=\"-1\">-</font></td><td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/USB/Device[" . $i . "]/Name" ) . "</font></td></tr>";
		}
	}
	
	$_text = "<table border=\"0\" width=\"100%\" align=\"center\">\n";
	if( $XPath->match( "/phpsysinfo/Hardware/CPU/Number" ) ) {
		$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['numcpu'] . "</font></td>\n    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/CPU/Number" ) . "</font></td>\n  </tr>\n";
	} 
	if( $XPath->match( "/phpsysinfo/Hardware/CPU/Model" ) ) {
		$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['cpumodel'] . "</font></td>\n    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/CPU/Model" );
		if( $XPath->match( "/phpsysinfo/Hardware/CPU/Cputemp" ) ) {
			$_text .= "&nbsp;@&nbsp;" . temperature( $XPath->getData( "/phpsysinfo/Hardware/CPU/Cputemp" ) );
		}
		$_text .= "</font></td>\n  </tr>\n";
	}
	if( $XPath->match( "/phpsysinfo/Hardware/CPU/Cpuspeed" ) ) {
		$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['cpuspeed'] . "</font></td>\n    <td><font size=\"-1\">" . format_speed( $XPath->getData( "/phpsysinfo/Hardware/CPU/Cpuspeed" ) ) . "</font></td>\n  </tr>\n";
	}
	if( $XPath->match( "/phpsysinfo/Hardware/CPU/Busspeed" ) ) {
		$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['busspeed'] . "</font></td>\n    <td><font size=\"-1\">" . format_speed( $XPath->getData( "/phpsysinfo/Hardware/CPU/Busspeed" ) ) . "</font></td>\n  </tr>\n";
	}
	if( $XPath->match("/phpsysinfo/Hardware/CPU/Cache" ) ) {
		$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['cache'] . "</font></td>\n    <td><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Hardware/CPU/Cache" ) ) . "</font></td>\n  </tr>\n";
	}
	if( $XPath->match( "/phpsysinfo/Hardware/CPU/Bogomips" ) ) {
		$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['bogomips'] . "</font></td>\n    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Hardware/CPU/Bogomips" ) . "</font></td>\n  </tr>\n";
	}
	$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['pci'] . "</font></td>\n    <td>";
	
	if( $strPcidevices) {
		$_text .= "<table>" . $strPcidevices . "</table>";
	} else {
		$_text .= "<font size=\"-1\"><i>" . $text['none'] . "</i></font>";
	}
	$_text .= "</td>\n  </tr>\n";
	
	$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['ide'] . "</font></td>\n    <td>";
	if( $strIdedevices ) {
		$_text .= "<table>" . $strIdedevices . "</table>";
	} else {
		$_text .= "<font size=\"-1\"><i>" . $text['none'] . "</i></font>";
	}
	$_text .= "</td>\n  </tr>\n";
	
	$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['scsi'] . "</font></td>\n    <td>";
	if( $strScsidevices ) {
		$_text .= "<table>" . $strScsidevices . "</table></td>\n  </tr>";
	} else {
		$_text .= "<font size=\"-1\"><i>" . $text['none'] . "</i></font>";
	}
	
	$_text .= "  <tr>\n    <td valign=\"top\"><font size=\"-1\">" . $text['usb'] . "</font></td>\n    <td>";
	if( $strUsbdevices) {
		$_text .= "<table>" . $strUsbdevices . "</table></td>\n  </tr>";
	} else {
		$_text .= "<font size=\"-1\"><i>" . $text['none'] . "</i></font>";
	}
	
	$_text .= "</table>";
	
	return $_text;
} 
?>
