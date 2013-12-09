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

// $Id: vitals.php,v 1.32 2007/02/18 18:59:54 bigmichi1 Exp $

// xml_vitals()

function xml_vitals ()
{
    global $sysinfo;
    global $loadbar;
    global $show_vhostname;

    $strLoadavg = "";
    $arrBuf = ( $loadbar ? $sysinfo->loadavg( $loadbar ) : $sysinfo->loadavg() );

    foreach ($arrBuf['avg'] as $strValue) {
        $strLoadavg .= $strValue . ' ';
    }

    $_text = "  <Vitals>\n"
        . "    <Hostname>" . htmlspecialchars( $show_vhostname ? $sysinfo->vhostname() : $sysinfo->chostname(), ENT_QUOTES ) . "</Hostname>\n"
        . "    <IPAddr>" . htmlspecialchars( $show_vhostname ? $sysinfo->vip_addr() : $sysinfo->ip_addr(), ENT_QUOTES ) . "</IPAddr>\n"
        . "    <Kernel>" . htmlspecialchars( $sysinfo->kernel(), ENT_QUOTES ) . "</Kernel>\n"
        . "    <Distro>" . htmlspecialchars( $sysinfo->distro(), ENT_QUOTES ) . "</Distro>\n"
        . "    <Distroicon>" . htmlspecialchars( $sysinfo->distroicon(), ENT_QUOTES ) . "</Distroicon>\n"
        . "    <Uptime>" . htmlspecialchars( $sysinfo->uptime(), ENT_QUOTES ) . "</Uptime>\n"
        . "    <Users>" . htmlspecialchars( $sysinfo->users(), ENT_QUOTES ) . "</Users>\n"
        . "    <LoadAvg>" . htmlspecialchars( trim( $strLoadavg ), ENT_QUOTES ) . "</LoadAvg>\n";
    if ( isset( $arrBuf['cpupercent'] ) ) {
        $_text .= "   <CPULoad>" . htmlspecialchars( round( $arrBuf['cpupercent'], 2 ), ENT_QUOTES ) . "</CPULoad>";
    }
    $_text .= "  </Vitals>\n";

    return $_text;
}

// html_vitals()
function html_vitals ()
{
    global $webpath;
    global $XPath;
    global $text;

    $textdir = direction();
    $scale_factor = 2;
    $strLoadbar = "";
    $uptime = "";

    if( $XPath->match( "/phpsysinfo/Vitals/CPULoad" ) )
        $strLoadbar = "<br>" . create_bargraph( $XPath->getData( "/phpsysinfo/Vitals/CPULoad" ), 100, $scale_factor ) . "&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/CPULoad" ) . "%";

    $_text = "<table border=\"0\" width=\"100%\" align=\"center\">\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['hostname'] . "</font></td>\n"
        . "    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Vitals/Hostname" ) . "</font></td>\n"
        . "  </tr>\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['ip'] . "</font></td>\n"
        . "    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Vitals/IPAddr" ) . "</font></td>\n"
        . "  </tr>\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['kversion'] . "</font></td>\n"
        . "    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Vitals/Kernel" ) . "</font></td>\n"
        . "  </tr>\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['dversion'] . "</font></td>\n"
        . "    <td><img width=\"16\" height=\"16\" alt=\"\" src=\"" . $webpath . "images/" . $XPath->getData( "/phpsysinfo/Vitals/Distroicon" ) . "\">&nbsp;<font size=\"-1\">" . $XPath->getData("/phpsysinfo/Vitals/Distro") . "</font></td>\n"
        . "  </tr>\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['uptime'] . "</font></td>\n"
        . "    <td><font size=\"-1\">" . uptime( $XPath->getData( "/phpsysinfo/Vitals/Uptime" ) ) . "</font></td>\n"
        . "  </tr>\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['users'] . "</font></td>\n"
        . "    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Vitals/Users" ) . "</font></td>\n"
        . "  </tr>\n"
        . "  <tr>\n"
        . "    <td valign=\"top\"><font size=\"-1\">" . $text['loadavg'] . "</font></td>\n"
        . "    <td><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/Vitals/LoadAvg" ) . $strLoadbar . "</font></td>\n"
        . "  </tr>\n"
        . "</table>\n";

    return $_text;
}

function wml_vitals ()
{
    global $XPath;
    global $text;

    $_text = "<card id=\"vitals\" title=\"" . $text['vitals']  . "\">\n"
        . "<p>" . $text['hostname'] . ":<br/>\n"
        . "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/Hostname" ) . "</p>\n"
        . "<p>" . $text['ip'] . ":<br/>\n"
        . "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/IPAddr" ) . "</p>\n"
        . "<p>" . $text['kversion'] . ":<br/>\n"
        . "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/Kernel" ) . "</p>\n"
        . "<p>" . $text['uptime'] . ":<br/>\n"
        . "-&nbsp;" . uptime( $XPath->getData( "/phpsysinfo/Vitals/Uptime" ) ) . "</p>\n"
        . "<p>" . $text['users'] . ":<br/>"
        . "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/Users" ) . "</p>\n"
        . "<p>" . $text['loadavg'] . ":<br/>"
        . "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/LoadAvg" ) . "</p>\n"
        . "</card>\n";

    return $_text;
}
