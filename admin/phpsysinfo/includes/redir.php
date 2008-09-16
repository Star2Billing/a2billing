<?php
/***************************************************************************
*   Copyright (C) 2008 by phpSysInfo - A PHP System Information Script    *
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
//
// $Id: redir.php,v 1.1 2008/05/27 14:43:27 bigmichi1 Exp $
//
$display = isset($_GET['disp']) ? $_GET['disp'] : "";
switch ($display) {
  case "static":
    header("Location: xml.php");
    die();
  break;
  case "dynamic":
  break;
  default:
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
    echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
    echo "  <head>\n";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
    echo "    <meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
    echo "    <title>Redirection</title>\n";
    echo "    <noscript>\n";
    echo "      <meta http-equiv=\"refresh\" content=\"2; URL=index.php?disp=static\">\n";
    echo "    </noscript>\n";
    echo "    <script type=\"text/JavaScript\" language=\"JavaScript\">\n";
    echo "      <!--\n";
    echo "      var sTargetURL = \"index.php?disp=dynamic\";\n";
    echo "      function doRedirect() {\n";
    echo "        setTimeout( \"window.location.href = sTargetURL\", 2*1000 );\n";
    echo "      }\n";
    echo "      //-->\n";
    echo "    </script>\n";
    echo "    <script type=\"text/JavaScript\" language=\"JavaScript1.1\">\n";
    echo "      <!--\n";
    echo "      function doRedirect() {\n";
    echo "        window.location.replace( sTargetURL );\n";
    echo "      }\n";
    echo "      doRedirect();\n";
    echo "      //-->\n";
    echo "    </script>\n";
    echo "  </head>\n";
    echo "  <body onload=\"doRedirect()\">\n";
    echo "    <p>Loading <a href=\"index.php?disp=static\">redirection target</a></p>\n";
    echo "    <p>In approx. 2 seconds the redirection target page should load.<br/>\n";
    echo "    If it doesn't please select the link above.</p>\n";
    echo "  </body>\n";
    echo "</html>\n";
    die();
}
?>
