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

// $Id: filesystems.php,v 1.31 2007/02/08 20:16:25 bigmichi1 Exp $

//
// xml_filesystems()
//
function xml_filesystems ()
{
    global $sysinfo;
    global $show_mount_point;

    $arrFs = $sysinfo->filesystems();

    $_text = "  <FileSystem>\n";
    for ( $i = 0, $max = sizeof( $arrFs ); $i < $max; $i++ ) {
        $_text .= "    <Mount>\n";
        $_text .= "      <MountPointID>" . htmlspecialchars( $i, ENT_QUOTES ) . "</MountPointID>\n";

        if ($show_mount_point) {
            $_text .= "      <MountPoint>" . htmlspecialchars( $arrFs[$i]['mount'], ENT_QUOTES ) . "</MountPoint>\n";
        }

        $_text .= "      <Type>" . htmlspecialchars( $arrFs[$i]['fstype'], ENT_QUOTES ) . "</Type>\n"
                . "      <Device><Name>" . htmlspecialchars( $arrFs[$i]['disk'], ENT_QUOTES ) . "</Name></Device>\n"
                . "      <Percent>" . htmlspecialchars( $arrFs[$i]['percent'], ENT_QUOTES ) . "</Percent>\n"
                . "      <Free>" . htmlspecialchars( $arrFs[$i]['free'], ENT_QUOTES ) . "</Free>\n"
                . "      <Used>" . htmlspecialchars( $arrFs[$i]['used'], ENT_QUOTES ) . "</Used>\n"
                . "      <Size>" . htmlspecialchars( $arrFs[$i]['size'], ENT_QUOTES ) . "</Size>\n";
        if ( isset( $arrFs[$i]['options'] ) ) {
            $_text .= "      <Options>" . htmlspecialchars( $arrFs[$i]['options'], ENT_QUOTES ) . "</Options>\n";
        }
        if ( isset( $arrFs[$i]['inodes'] ) ) {
            $_text .= "      <Inodes>" . htmlspecialchars( $arrFs[$i]['inodes'], ENT_QUOTES ) . "</Inodes>\n";
        }
        $_text  .= "    </Mount>\n";
    }
    $_text .= "  </FileSystem>\n";

    return $_text;
}

//
// html_filesystems()
//
function html_filesystems ()
{
    global $XPath;
    global $text;
    global $show_mount_point;

    $textdir = direction();

    $arrSum = array("size" => 0, "used" => 0, "free" => 0);
    $arrCounteddevlist = array();
    $intScalefactor = 2;

    $_text  = "<table border=\"0\" width=\"100%\" align=\"center\">\n";
    $_text .= "  <tr>\n";
    if ($show_mount_point) {
        $_text .= "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['mount'] . "</b></font></td>\n";
    }
    $_text .= "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['type'] . "</b></font></td>\n"
            . "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['partition'] . "</b></font></td>\n"
            . "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['percent'] . "</b></font></td>\n"
            . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['free'] . "</b></font></td>\n"
            . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['used'] . "</b></font></td>\n"
            . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><b>" . $text['size'] . "</b></font></td>\n  </tr>\n";
    for ( $i = 1, $max = sizeof( $XPath->getDataParts( "/phpsysinfo/FileSystem" ) ); $i < $max; $i++ ) {
        if ( $XPath->match( "/phpsysinfo/FileSystem/Mount[" . $i . "]/MountPointID" ) ) {
            if ( ! $XPath->match( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Options" ) || ! stristr( $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Options" ), "bind" ) ) {
                if ( ! in_array( $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Device/Name" ), $arrCounteddevlist ) ) {
                    $arrSum['size'] += $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Size" );
                    $arrSum['used'] += $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Used" );
                    $arrSum['free'] += $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Free" );
                    if (PHP_OS != "WINNT") {
                        $arrCounteddevlist[] = $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Device/Name" );
                    }
                }
            }
            $_text .= "  <tr>\n";
            if ($show_mount_point) {
                $_text .= "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/MountPoint" ) . "</font></td>\n";
            }
            $_text .= "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Type" ) . "</font></td>\n"
                    . "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">" . $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Device/Name" ) . "</font></td>\n"
                    . "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">"
                    . create_bargraph( $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Used" ), $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Size" ), $intScalefactor, $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Type" ) )
                    . "&nbsp;" . $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Percent" ) . "%";
            if ( $XPath->match( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Inodes" ) ) {
                $_text .= " (" . $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Inodes" ) . "%)";
            }
            $_text .= "</font></td>\n"
                    . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Free" ) ) . "</font></td>\n"
                    . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Used" ) ) . "</font></td>\n"
                    . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Size" ) ) . "</font></td>\n"
                    . "  </tr>\n";
        }
    }
    $_text .= "  <tr>\n";
    if ($show_mount_point) {
        $_text .= "  <td colspan=\"3\" align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><i>" . $text['totals'] . " :&nbsp;&nbsp;</i></font></td>\n";
    } else {
        $_text .= "  <td colspan=\"2\" align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\"><i>" . $text['totals'] . " :&nbsp;&nbsp;</i></font></td>\n";
    }
    $_text .= "    <td align=\"" . $textdir['left'] . "\" valign=\"top\"><font size=\"-1\">"
        . create_bargraph( $arrSum['used'], $arrSum['size'], $intScalefactor )
        . "&nbsp;";
    if ($arrSum['size'] == 0) {
        $_text .= "0";
    } else {
        $_text .= round( 100 / $arrSum['size'] *  $arrSum['used'] );
    }
    $_text .= "%" .  "</font></td>\n"
        . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $arrSum['free'] ) . "</font></td>\n"
        . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $arrSum['used'] ) . "</font></td>\n"
        . "    <td align=\"" . $textdir['right'] . "\" valign=\"top\"><font size=\"-1\">" . format_bytesize( $arrSum['size'] ) . "</font></td>\n  </tr>\n"
        . "</table>\n";

    return $_text;
}

function wml_filesystem()
{
    global $XPath;
    global $text;
    global $show_mount_point;

    $_text = "<card id=\"filesystem\" title=\"" . $text['fs'] . "\">\n";
    for ( $i = 1; $i < sizeof( $XPath->getDataParts( "/phpsysinfo/FileSystem" ) ); $i++ ) {
        if ( $XPath->match( "/phpsysinfo/FileSystem/Mount[" . $i . "]/MountPointID" ) ) {
            $_text .= "<p>";
            if ($show_mount_point) {
                $_text .= $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/MountPoint" ) . "<br/>\n";
            } else {
                $_text .= $XPath->getData( "/phpsysinfo/FileSystem/Mount[" . $i . "]/Device/Name" ) . "<br/>\n";
            }
            $_text .= "- " . $text['free'] . ": " . format_bytesize( $XPath->getData("/phpsysinfo/FileSystem/Mount[" . $i . "]/Free" ) ) . "<br/>\n"
                    . "- " . $text['used'] . ": " . format_bytesize( $XPath->getData("/phpsysinfo/FileSystem/Mount[" . $i . "]/Used" ) ) . "<br/>\n"
                    . "- " . $text['size'] . ": " . format_bytesize( $XPath->getData("/phpsysinfo/FileSystem/Mount[" . $i . "]/Size" ) ) . "<br/>\n"
                    . "</p>\n";
        }
    }
    $_text .= "</card>\n";

    return $_text;
}
