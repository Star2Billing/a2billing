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

// $Id: mbinfo.php,v 1.20 2007/02/18 19:11:31 bigmichi1 Exp $

function xml_mbinfo() {
	global $text;
	global $mbinfo;
	
	$_text = "";
	
	$arrBuff = $mbinfo->temperature();
	$_text = "  <MBinfo>\n";
	if( sizeof($arrBuff ) > 0 ) {
		$_text .= "    <Temperature>\n";
		foreach( $arrBuff as $arrValue ) {
			$_text .= "     <Item>\n";
			$_text .= "      <Label>" . htmlspecialchars( $arrValue['label'], ENT_QUOTES ) . "</Label>\n";
			$_text .= "      <Value>" . htmlspecialchars( $arrValue['value'], ENT_QUOTES ) . "</Value>\n";
			$_text .= "      <Limit>" . htmlspecialchars( $arrValue['limit'], ENT_QUOTES ) . "</Limit>\n";
			$_text .= "     </Item>\n";
		}
		$_text .= "    </Temperature>\n";
	}
	
	$arrBuff = $mbinfo->fans();
	if( sizeof( $arrBuff ) > 0 ) {
		$_text .= "    <Fans>\n";
		foreach( $arrBuff as $arrValue ) {
			$_text .= "     <Item>\n";
			$_text .= "      <Label>" . htmlspecialchars( $arrValue['label'], ENT_QUOTES ) . "</Label>\n";
			$_text .= "      <Value>" . htmlspecialchars( $arrValue['value'], ENT_QUOTES ) . "</Value>\n";
			$_text .= "      <Min>" . htmlspecialchars( $arrValue['min'], ENT_QUOTES ) . "</Min>\n";
			$_text .= "     </Item>\n";
		}
		$_text .= "    </Fans>\n";
	}
	
	$arrBuff = $mbinfo->voltage();
	if( sizeof( $arrBuff ) > 0 ) {
		$_text .= "    <Voltage>\n";
		foreach( $arrBuff as $arrValue ) {
			$_text .= "     <Item>\n";
			$_text .= "      <Label>" . htmlspecialchars( $arrValue['label'], ENT_QUOTES ) . "</Label>\n";
			$_text .= "      <Value>" . htmlspecialchars( $arrValue['value'], ENT_QUOTES ) . "</Value>\n";
			$_text .= "      <Min>" . htmlspecialchars( $arrValue['min'], ENT_QUOTES ) . "</Min>\n";
			$_text .= "      <Max>" . htmlspecialchars( $arrValue['max'], ENT_QUOTES ) . "</Max>\n";
			$_text .= "     </Item>\n";
		}
		$_text .= "    </Voltage>\n";
	}
	$_text .= "  </MBinfo>\n";
	
	return $_text;
}

function html_mbtemp() {
	global $text;
	global $XPath;
	
	$textdir = direction();
	$scale_factor = 2;
	
	$_text = "  <tr>\n"
		. "    <td><font size=\"-1\"><b>" . $text['s_label'] . "</b></font></td>\n"
		. "    <td><font size=\"-1\"><b>" . $text['s_value'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['s_limit'] . "</b></font></td>\n"
		. "  </tr>\n";
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/MBinfo/Temperature" ) ); $i < $max; $i++ ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Label" ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">";
		if( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Value" ) == 0) {
			$_text .= "Unknown - Not connected?";
		} else {
			$_text .= create_bargraph( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Value" ), $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Limit" ), $scale_factor );
		}
		$_text .= temperature( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Value" ) ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . temperature( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Limit" ) ) . "</font></td>\n"
			. "  </tr>\n";
	}
	
	return $_text;  
}

function html_mbfans() {
	global $text;
	global $XPath;
	
	$textdir = direction();
	$booShowfans = false;
	
	$_text ="<table width=\"100%\">\n";
	$_text .= "  <tr>\n"
		. "    <td><font size=\"-1\"><b>" . $text['s_label'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\"><font size=\"-1\"><b>" . $text['s_value'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\"><font size=\"-1\"><b>" . $text['s_min'] . "</b></font></td>\n"
		. "  </tr>\n";
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/MBinfo/Fans" ) ); $i < $max; $i++ ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Fans/Item[" . $i . "]/Label" ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . round( $XPath->getData( "/phpsysinfo/MBinfo/Fans/Item[" . $i . "]/Value" ) ) . " " . $text['rpm_mark'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Fans/Item[" . $i . "]/Min" ) . " " . $text['rpm_mark'] . "</font></td>\n"
			. "  </tr>\n";
		if( round( $XPath->getData( "/phpsysinfo/MBinfo/Fans/Item[" . $i . "]/Value" ) ) > 0 ) { 
			$booShowfans = true;
		}
	}
	$_text .= "</table>\n";
	
	if( ! $booShowfans ) {
		$_text = "";
	}
	
	return $_text;
}

function html_mbvoltage() {
	global $text;
	global $XPath;
	
	$textdir = direction();
	
	$_text = "<table width=\"100%\">\n";
	$_text .= "  <tr>\n"
		. "    <td><font size=\"-1\"><b>" . $text['s_label'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\"><font size=\"-1\"><b>" . $text['s_value'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\"><font size=\"-1\"><b>" . $text['s_min'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\"><font size=\"-1\"><b>" . $text['s_max'] . "</b></font></td>\n"
		. "  </tr>\n";
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/MBinfo/Voltage" ) ); $i < $max; $i++ ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Voltage/Item[" . $i . "]/Label" ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Voltage/Item[" . $i . "]/Value" ) . " " . $text['voltage_mark'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Voltage/Item[" . $i . "]/Min" ) . " " . $text['voltage_mark'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/MBinfo/Voltage/Item[" . $i . "]/Max" ) . " " . $text['voltage_mark'] . "</font></td>\n"
			. "  </tr>\n";
	}
	
	$_text .= "</table>\n";
	
	return $_text;  
}

function wml_mbtemp() {
	global $XPath;
	
	$_text = "";
	
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/MBinfo/Temperature" ) ); $i < $max; $i++ ) {
		$_text .= "<p>" . $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Label" ) . ": ";
		if( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Value" ) == 0 ) {
			$_text .= "Unknown - Not connected?</p>";
		} else {
			$_text .= "&nbsp;" . str_replace( "&deg;", "", temperature( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Value" ) ) ) . "</p>\n";
		}
	}
	
	return $_text;
}

function wml_mbfans() {
	global $text;
	global $XPath;
  
	$_text = "<card id=\"fans\" title=\"" . $text['fans'] . "\">\n";
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/MBinfo/Fans" ) ); $i < $max; $i++ ) {
		$_text .= "<p>" . $XPath->getData( "/phpsysinfo/MBinfo/Fans/Item[" . $i . "]/Label" ) . ": " . round( $XPath->getData( "/phpsysinfo/MBinfo/Temperature/Item[" . $i . "]/Value" ) ) . "&nbsp;" . $text['rpm_mark'] . "</p>\n";
	}
	$_text .= "</card>\n";
	
	return $_text;  
}

function wml_mbvoltage() {
	global $text;
	global $XPath;
	
	$_text = "<card id=\"volt\" title=\"" . $text['voltage'] . "\">\n";
	for( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/MBinfo/Voltage" ) ); $i < $max; $i++ ) {
		$_text .= "<p>" . $XPath->getData( "/phpsysinfo/MBinfo/Voltage/Item[" . $i . "]/Label" ) . ": " . $XPath->getData( "/phpsysinfo/MBinfo/Voltage/Item[" . $i . "]/Value" ) . "&nbsp;" . $text['voltage_mark'] . "</p>\n";
	}
	$_text .= "</card>\n";
	
	return $_text;  
}
?>
