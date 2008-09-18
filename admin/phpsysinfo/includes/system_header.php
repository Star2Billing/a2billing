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

// $Id: system_header.php,v 1.30 2007/02/11 15:57:17 bigmichi1 Exp $

if( ! defined( 'IN_PHPSYSINFO' ) ) {
	die( "No Hacking" );
}

setlocale( LC_ALL, $text['locale'] );
global $XPath;

header( "Cache-Control: no-cache, must-revalidate" );
if( ! isset( $charset ) ) {
	$charset = "iso-8859-1";
} 
header( "Content-Type: text/html; charset=" . $charset );

echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
echo "<html>\n";
echo created_by();

echo "<head>\n";
echo "\t<title>" . $text['title'], " -- ", $XPath->getData('/phpsysinfo/Vitals/Hostname'), " --</title>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $charset . "\">";
if( file_exists( APP_ROOT . "/templates/" . $template . "/" . $template . ".css" ) ) {
	echo "\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $webpath . "templates/" . $template . "/" . $template . ".css\">\n";
}
echo "</head>\n";

if( file_exists( APP_ROOT . "/templates/" . $template . "/images/" . $template . "_background.gif" ) ) {
	echo "<body background=\"" . $webpath . "templates/" . $template . "/images/" . $template . "_background.gif\">";
} else {
	echo "<body>\n";
}

?>
