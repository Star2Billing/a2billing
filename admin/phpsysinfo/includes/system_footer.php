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

// $Id: system_footer.php,v 1.51.4.1 2007/08/19 09:22:21 xqus Exp $

if( ! defined( 'IN_PHPSYSINFO' ) ) {
	die( "No Hacking" );
}

$arrDirection = direction();

if( ! $hide_picklist ) {
	echo "<center>\n";
	$update_form = "<form method=\"POST\" action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\">\n" . "\t" . $text['template'] . ":&nbsp;\n" . "\t<select name=\"template\">\n";
	
	$resDir = opendir( APP_ROOT . '/templates/' );
	while( false !== ( $strFile = readdir( $resDir ) ) ) {
		if( $strFile != 'CVS' && $strFile[0] != '.' && is_dir( APP_ROOT . '/templates/' . $strFile ) ) {
			$arrFilelist[] = $strFile;
		}
	}
	closedir( $resDir );
	asort( $arrFilelist );
	foreach( $arrFilelist as $strVal ) {
		if( $_COOKIE['template'] == $strVal ) {
			$update_form .= "\t\t<option value=\"" . $strVal . "\" SELECTED>" . $strVal . "</option>\n";
		} else {
			$update_form .= "\t\t<option value=\"" . $strVal . "\">" . $strVal . "</option>\n";
		}
	}
	$update_form .= "\t\t<option value=\"xml\">XML</option>\n";
	$update_form .= "\t\t<option value=\"wml\">WML</option>\n";
	$update_form .= "\t\t<option value=\"random\"";
	if( $_COOKIE['template'] == 'random' ) {
		$update_form .= " SELECTED";
	}
	$update_form .= ">random</option>\n";
	$update_form .= "\t</select>\n";
	
	$update_form .= "\t&nbsp;&nbsp;" . $text['language'] . ":&nbsp;\n" . "\t<select name=\"lng\">\n";
	unset( $arrFilelist );
	$resDir = opendir( APP_ROOT . "/includes/lang/" );
	while( false !== ( $strFile = readdir( $resDir ) ) ) {
		if ( $strFile[0] != '.' && is_file( APP_ROOT . "/includes/lang/" . $strFile ) && preg_match( "/\.php$/", $strFile ) ) {
			$arrFilelist[] = preg_replace("/\.php$/", "", $strFile );
		}
	}
	closedir($resDir);
	asort( $arrFilelist );
	foreach( $arrFilelist as $strVal ) {
		if( $_COOKIE['lng'] == $strVal ) {
			$update_form .= "\t\t<option value=\"" . $strVal . "\" SELECTED>" . $strVal . "</option>\n";
		} else {
			$update_form .= "\t\t<option value=\"" . $strVal . "\">" . $strVal . "</option>\n";
		}
	}
	$update_form .= "\t\t<option value=\"browser\"";
	if( $_COOKIE['lng'] == "browser" ) {
		$update_form .= " SELECTED";
	}
	$update_form .= ">browser default</option>\n\t</select>\n";

	$update_form .= "\t<input type=\"submit\" value=\"" . $text['submit'] . "\">\n" . "</form>\n";
	echo $update_form;
	echo "</center>\n";
} else {
	echo "<br>\n";
}

echo "<hr>\n";
echo "<table width=\"100%\">\n\t<tr>\n";
echo "\t\t<td align=\"" . $arrDirection['left'] . "\"><font size=\"-1\">" . $text['created'] . "&nbsp;<a href=\"http://phpsysinfo.sourceforge.net\" target=\"_blank\">phpSysInfo-" . $VERSION . "</a>&nbsp;" . strftime( $text['gen_time'], time() ) . "</font></td>\n";
echo "\t\t<td align=\"" . $arrDirection['right'] . "\"><font size=\"-1\">" . round( ( array_sum( explode( " ", microtime() ) ) - $startTime ), 4 ). " sec</font></td>\n";
echo "\t</tr>\n</table>\n";
echo "<br>\n</body>\n</html>\n";

?>
