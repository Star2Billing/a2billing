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
// $Id: xml.php,v 1.11 2008/06/05 18:49:47 bigmichi1 Exp $
//
define('APP_ROOT', dirname(__FILE__));
define('IN_PHPSYSINFO', true);
require_once (APP_ROOT . '/includes/common_functions.php'); // Set of common functions used through out the app

/**
 * Check for the SimpleXML fuction. We need this for almost everything.
 * Even our error class needs this to output the errors.
 * Because of that this check uses a custom error function that will
 * return a hard coded XML file (with headers).
 */
checkForExtensions();
$error = Error::singleton();
if (!file_exists(APP_ROOT . '/config.php')) {
  $error->addError('file_exists(config.php)', 'config.php does not exist in the phpsysinfo directory.');
} else {
  require_once (APP_ROOT . '/config.php'); // get the config file
  
}
$plugin = (isset($_GET['plugin'])) ? basename(htmlspecialchars($_GET['plugin'])) : "";
if ($plugin == "complete") {
  $plugin_request = false;
  $completexml = true;
} elseif ($plugin != "") {
  $plugins = explode(",", PSI_PLUGINS);
  if (in_array($plugin, $plugins)) {
    $plugin_request = true;
    $completexml = false;
  } else {
    $plugin_request = false;
    $completexml = false;
  }
} else {
  $plugin_request = false;
  $completexml = false;
}
if (!$plugin_request) {
  // Figure out which OS we are running on, and detect support
  if (file_exists(APP_ROOT . '/includes/os/class.' . PHP_OS . '.inc.php')) {
    require_once (APP_ROOT . '/includes/os/class.' . PHP_OS . '.inc.php');
  } else {
    $error->addError('include(class.' . PHP_OS . '.php.inc)', PHP_OS . ' is not currently supported');
  }
  if (!extension_loaded('pcre')) {
    $error->addError('extension_loaded(pcre)', 'phpsysinfo requires the pcre module for php to work');
  }
  if (sensorProgram !== false) {
    $sensor_program = basename(sensorProgram);
    if (!file_exists(APP_ROOT . '/includes/mb/class.' . sensorProgram . '.inc.php')) {
      define('PSI_MBINFO', false);
      $error->addError('include(class.' . htmlspecialchars(sensorProgram, ENT_QUOTES) . '.inc.php)', 'specified sensor program is not supported');
    } else {
      require_once (APP_ROOT . '/includes/mb/class.' . sensorProgram . '.inc.php');
      define('PSI_MBINFO', true);
    }
  } else {
    define('PSI_MBINFO', false);
  }
  if (hddTemp !== false) {
    if (hddTemp != "tcp" && hddTemp != "suid") {
      $error->addError('include(class.hddtemp.inc.php)', 'bad configuration in config.php for $hddtemp_avail');
      define('PSI_HDDTEMP', false);
    } else {
      require_once (APP_ROOT . '/includes/mb/class.hddtemp.inc.php');
      define('PSI_HDDTEMP', true);
    }
  } else {
    define('PSI_HDDTEMP', false);
  }
  if (upsProgram !== false) {
    $ups_program = basename(upsProgram);
    if (!file_exists(APP_ROOT . '/includes/ups/class.' . upsProgram . '.inc.php')) {
      define('PSI_UPSINFO', false);
      $error->addError('include(class.' . htmlspecialchars(upsProgram, ENT_QUOTES) . '.inc.php)', 'specified UPS program is not supported');
    } else {
      require_once (APP_ROOT . '/includes/ups/class.' . upsProgram . '.inc.php');
      define('PSI_UPSINFO', true);
      define('PSI_UPSINFO_APCUPSD_UPS_LIST', apcupsdUpsList);
    }
  } else {
    define('PSI_UPSINFO', false);
  }
  if ($error->ErrorsExist()) {
    header("Content-Type: text/xml\n\n");
    echo $error->ErrorsAsXML();
    exit;
  }
}
// Create the XML file
require_once (APP_ROOT . '/includes/xml.class.php');
($plugin_request) ? $xml = new xml($plugin, $completexml) : $xml = new xml("", $completexml);
$xml->buildXml();
$xml->printXml();
