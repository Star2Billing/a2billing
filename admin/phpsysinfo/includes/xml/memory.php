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

// $Id: memory.php,v 1.18 2007/02/08 20:16:25 bigmichi1 Exp $

//
// xml_memory()
//
function xml_memory () {
	global $sysinfo;
	
	$arrMem = $sysinfo->memory();
	$i = 0;
	
	$_text = "  <Memory>\n"
		. "    <Free>" . htmlspecialchars( $arrMem['ram']['free'], ENT_QUOTES ) . "</Free>\n"
		. "    <Used>" . htmlspecialchars( $arrMem['ram']['used'], ENT_QUOTES ) . "</Used>\n"
		. "    <Total>" . htmlspecialchars( $arrMem['ram']['total'], ENT_QUOTES ) . "</Total>\n"
		. "    <Percent>" . htmlspecialchars( $arrMem['ram']['percent'], ENT_QUOTES ) . "</Percent>\n";
	
	if( isset( $arrMem['ram']['app_percent'] ) ) {
		$_text .= "    <App>" . htmlspecialchars( $arrMem['ram']['app'], ENT_QUOTES ) . "</App>\n    <AppPercent>" . htmlspecialchars( $arrMem['ram']['app_percent'], ENT_QUOTES ) . "</AppPercent>\n";
	}
	if( isset( $arrMem['ram']['buffers_percent'] ) ) {
		$_text .= "    <Buffers>" . htmlspecialchars( $arrMem['ram']['buffers'], ENT_QUOTES ) . "</Buffers>\n    <BuffersPercent>" . htmlspecialchars( $arrMem['ram']['buffers_percent'], ENT_QUOTES ) . "</BuffersPercent>\n";
	}
	if( isset( $arrMem['ram']['cached_percent'] ) ) {
		$_text .= "    <Cached>" . htmlspecialchars( $arrMem['ram']['cached'], ENT_QUOTES ) . "</Cached>\n    <CachedPercent>" . htmlspecialchars( $arrMem['ram']['cached_percent'], ENT_QUOTES ) . "</CachedPercent>\n";
	}
	
	$_text .= "  </Memory>\n";
	
	$_text .= "  <Swap>\n";
	if( isset( $arrMem['swap']['total'] ) && $arrMem['swap']['total'] > 0 ) {
		$_text .= "    <Free>" . htmlspecialchars( $arrMem['swap']['free'], ENT_QUOTES ) . "</Free>\n"
			. "    <Used>" . htmlspecialchars( $arrMem['swap']['used'], ENT_QUOTES ) . "</Used>\n"
			. "    <Total>" . htmlspecialchars( $arrMem['swap']['total'], ENT_QUOTES ) . "</Total>\n"
			. "    <Percent>" . htmlspecialchars( $arrMem['swap']['percent'], ENT_QUOTES ) . "</Percent>\n";
	}
	$_text .= "  </Swap>\n";
	
	$_text .= "  <Swapdevices>\n";
	foreach( $arrMem['devswap'] as $arrDevice) {
		$_text .="    <Mount>\n"
			. "     <MountPointID>" . htmlspecialchars( $i++, ENT_QUOTES ) . "</MountPointID>\n"
			. "     <Type>Swap</Type>"
			. "     <Device><Name>" . htmlspecialchars( $arrDevice['dev'], ENT_QUOTES ) . "</Name></Device>\n"
			. "     <Percent>" . htmlspecialchars( $arrDevice['percent'], ENT_QUOTES ) . "</Percent>\n"
			. "     <Free>" . htmlspecialchars( $arrDevice['free'], ENT_QUOTES ) . "</Free>\n"
			. "     <Used>" . htmlspecialchars( $arrDevice['used'], ENT_QUOTES ) . "</Used>\n"
			. "     <Size>" . htmlspecialchars( $arrDevice['total'], ENT_QUOTES ) . "</Size>\n"
			. "    </Mount>\n";
	}
	$_text .= "  </Swapdevices>\n";
	
	return $_text;
}

//
// html_memory()
//
function html_memory () {
	global $XPath;
	global $text;
	
	$textdir = direction();
	$scale_factor = 2;
	
	$strRam = create_bargraph( $XPath->getData( "/phpsysinfo/Memory/Used" ), $XPath->getData( "/phpsysinfo/Memory/Total" ), $scale_factor );
	$strRam .= "&nbsp;&nbsp;" . $XPath->getData( "/phpsysinfo/Memory/Percent" ) . "% ";
	
	if( $XPath->match( "/phpsysinfo/Swap/Total" ) ) {
		$strSwap = create_bargraph( $XPath->getData( "/phpsysinfo/Swap/Used" ), $XPath->getData( "/phpsysinfo/Swap/Total" ), $scale_factor );
		$strSwap .= "&nbsp;&nbsp;" . $XPath->getData( "/phpsysinfo/Swap/Percent" ) . "% ";
	}
	
	if( $XPath->match( "/phpsysinfo/Memory/AppPercent" ) ) {
		$strApp = create_bargraph( $XPath->getData( "/phpsysinfo/Memory/App" ), $XPath->getData( "/phpsysinfo/Memory/Total" ), $scale_factor );
		$strApp .= "&nbsp;&nbsp;" . $XPath->getData( "/phpsysinfo/Memory/AppPercent" ) . "% ";
	}
	if( $XPath->match( "/phpsysinfo/Memory/BuffersPercent" ) ) {
		$strBuffers = create_bargraph( $XPath->getData( "/phpsysinfo/Memory/Buffers" ), $XPath->getData( "/phpsysinfo/Memory/Total" ), $scale_factor);
		$strBuffers .= "&nbsp;&nbsp;" . $XPath->getData( "/phpsysinfo/Memory/BuffersPercent" ) . "% ";
	}
	if( $XPath->match( "/phpsysinfo/Memory/CachedPercent" ) ) {
		$strCached = create_bargraph( $XPath->getData( "/phpsysinfo/Memory/Cached" ), $XPath->getData( "/phpsysinfo/Memory/Total" ), $scale_factor);
		$strCached .= "&nbsp;&nbsp;" . $XPath->getData( "/phpsysinfo/Memory/CachedPercent" ) . "% ";
	}
	
	$_text = "<table border=\"0\" width=\"100%\" align=\"center\">\n"
		. "  <tr>\n"
		. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['type'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['percent'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['free'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['used'] . "</b></font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['size'] . "</b></font></td>\n"
		. "  </tr>\n"
		. "  <tr>\n"
		. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $text['phymem'] . "</font></td>\n"
		. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $strRam . "</font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Free" ) ) . "</font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Used" ) ) . "</font></td>\n"
		. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Total" ) ) . "</font></td>\n"
		. "  </tr>\n";
	if( isset( $strApp ) ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">- " . $text['app'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $strApp . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">&nbsp;</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/App" ) ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">&nbsp;</font></td>\n"
			. "  </tr>\n";
	}
	if( isset( $strBuffers ) ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">- " . $text['buffers'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $strBuffers . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">&nbsp;</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Buffers" ) ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">&nbsp;</font></td>\n"
			. "  </tr>\n";
	}
	
	if( isset( $strCached ) ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">- " . $text['cached'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $strCached . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">&nbsp;</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Cached" ) ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">&nbsp;</font></td>\n"
			. "  </tr>\n";
	}
	
	if( isset( $strSwap ) ) {
		$_text .= "  <tr>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $text['swap'] . "</font></td>\n"
			. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $strSwap . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Swap/Free" ) ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Swap/Used" ) ) . "</font></td>\n"
			. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/Swap/Total" ) ) . "</font></td>\n"
			. "  </tr>\n";
	}
	
	if( ($max = sizeof( $XPath->getDataParts( "/phpsysinfo/Swapdevices" ) ) ) > 2 ) {
		for( $i = 1; $i < $max; $i++ ) {
			$strSwapdev = create_bargraph( $XPath->getData( "/phpsysinfo/Swapdevices/Mount[" . $i . "]/Used" ), $XPath->getData( "/phpsysinfo/Swapdevices/Mount[" . $i . "]/Size" ), $scale_factor );
			$strSwapdev .= "&nbsp;&nbsp;" . $XPath->getData( "/phpsysinfo/Swapdevices/Mount[" . $i . "]/Percent" ) . "% ";
			$_text .= "  <tr>\n"
				. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"> - " . $XPath->getData( "/phpsysinfo/Swapdevices/Mount[" . $i . "]/Device/Name" ) . "</font></td>\n"
				. "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $strSwapdev . "</font></td>\n"
				. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData("/phpsysinfo/Swapdevices/Mount[" . $i . "]/Free" ) ) . "</font></td>\n"
				. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData("/phpsysinfo/Swapdevices/Mount[" . $i . "]/Used" ) ) . "</font></td>\n"
				. "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData("/phpsysinfo/Swapdevices/Mount[" . $i . "]/Size" ) ) . "</font></td>\n"
				. "  </tr>\n";
		}
	}
	$_text .= "</table>";
	
	return $_text;
}

function wml_memory() {
	global $XPath;
	global $text;
	
	$_text = "<card id=\"memory\" title=\"" . $text['memusage'] . "\">\n"
		. "<p>" . $text['phymem'] . ":<br/>\n"
		. "- " . $text['free'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Free" ) ) . "<br/>\n"
		. "- " . $text['used'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Used" ) ) . "<br/>\n"
		. "- " . $text['size'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Total" ) ) . "</p>\n";
	if( $XPath->match( "/phpsysinfo/Memory/App" ) ) {
		$_text .= "<p>" . $text['app'] . ":<br/>\n"
			. "- " . $text['used'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/App" ) ) . "</p>\n";
	}
	if( $XPath->match( "/phpsysinfo/Memory/Cached" ) ) {
		$_text .= "<p>" . $text['cached'] . ":<br/>\n"
			. "- " . $text['used'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Cached" ) ) . "</p>\n";
	}
	if( $XPath->match( "/phpsysinfo/Memory/Buffers" ) ) {
		$_text .= "<p>" . $text['buffers'] . ":<br/>\n"
			. "- " . $text['used'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Memory/Buffers" ) ) . "</p>\n";
	}
	if( $XPath->match( "/phpsysinfo/Swap/Total" ) ) {
		$_text .= "<p>" . $text['swap'] . ":<br/>\n"
			. "- " . $text['free'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Swap/Free" ) ) . "<br/>\n"
			. "- " . $text['used'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Swap/Used" ) ) . "<br/>\n"
			. "- " . $text['size'] . ": " . format_bytesize( $XPath->getData( "/phpsysinfo/Swap/Total" ) ) . "</p>\n";
	}
	$_text .= "</card>\n";
	
	return $_text;
}
?>
