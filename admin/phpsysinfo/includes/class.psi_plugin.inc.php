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
// $Id: class.psi_plugin.inc.php,v 1.2 2008/06/01 15:49:41 bigmichi1 Exp $
//
abstract class psi_plugin implements psi_plugin_interface {
  private $plugin_name = "";
  private $plugin_base = "";
  protected $plugin_config = array();
  protected $global_error = "";
  public function __construct($plugin_name = "") {
    if (trim($plugin_name) != "") {
      $this->global_error = Error::Singleton();
      $this->plugin_name = $plugin_name;
      $this->plugin_base = "./plugins/" . $this->plugin_name . "/";
      $this->checkfiles();
      $this->getconfig();
    } else {
      $this->global_error->addError("__construct()", "Parent constructor called without Plugin-Name!");
    }
  }
  private function getconfig() {
    $filename = $this->plugin_base . $this->plugin_name . ".ini";
    if (file_exists($filename)) {
      $this->plugin_config = parse_ini_file($filename, true);
    }
  }
  private function checkfiles() {
    if (!file_exists($this->plugin_base . "js/" . $this->plugin_name . ".js")) {
      $this->global_error->addError("file_exists(" . $this->plugin_base . "js/" . $this->plugin_name . ".js)", "JS-File for Plugin '" . $this->plugin_name . "' is missing!");
    }
    if (!file_exists($this->plugin_base . "lang/en.xml")) {
      $this->global_error->addError("file_exists(" . $this->plugin_base . "lang/en.xml)", "At least an english translation must exist for the plugin!");
    }
  }
}
?>
