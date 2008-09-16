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
// $Id: index.php,v 1.168 2008/06/05 18:49:46 bigmichi1 Exp $
//
if (PHP_VERSION < 5.2) {
  die("PHP 5.2 or greater is required!!!");
}
define('APP_ROOT', dirname(__FILE__));
require_once ('./includes/common_functions.php');
checkForExtensions();
$error = Error::singleton();
if (!is_readable('./config.php')) {
  $error->addError('file_exists(config.php)', 'config.php does not exist or is not readable by the webserver in the phpsysinfo directory.');
} else {
  require_once ('./config.php'); // get the config file
  
}
if ($error->ErrorsExist()) {
  echo $error->ErrorsAsHTML();
  exit;
}
//redirection part
include ('./includes/redir.php');
//checking config.php setting for template, if not supportet set phpsysinfo.css as default
$template = template;
if (!file_exists('templates/' . $template)) {
  $template = 'phpsysinfo.css';
}
// checking config.php setting for language, if not supported set en as default
if (!defined('lang')) {
  define('lang', 'en');
}
$lang = lang . ".xml";
if (!file_exists('language/' . $lang)) {
  $lang = 'en.xml';
}
$plugins = explode(",", PSI_PLUGINS);
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
echo "  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
echo "  <head>\n";
echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";
echo "    <meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />\n";
echo "    <meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
foreach($plugins as $plugin) {
  $filename = "/plugins/" . $plugin . "/css/" . $plugin . ".css";
  if (file_exists(APP_ROOT . $filename)) {
    echo "    <link type=\"text/css\" rel=\"stylesheet\" href=\"." . $filename . "\" />\n";
  }
}
$dirlist = gdc('./templates/');
sort($dirlist);
$tpl_option_list = "";
foreach($dirlist as $file) {
  $tpl_ext = substr($file, strlen($file) -4);
  $tpl_name = substr($file, 0, strlen($file) -4);
  if ($tpl_ext == ".css") {
    if ($tpl_name . ".css" == $template) {
      $tpl_option_list.= "          <option value=\"" . $tpl_name . "\" selected=\"selected\">" . $tpl_name . "</option>\n";
      echo "    <link type=\"text/css\" rel=\"stylesheet\" href=\"./templates/" . $tpl_name . $tpl_ext . "\" title=\"" . $tpl_name . "\"/>\n";
    } else {
      $tpl_option_list.= "          <option value=\"" . $tpl_name . "\">" . $tpl_name . "</option>\n";
      echo "    <link type=\"text/css\" rel=\"alternate stylesheet\" href=\"./templates/" . $tpl_name . $tpl_ext . "\" title=\"" . $tpl_name . "\"/>\n";
    }
  }
}
echo "    <link type=\"text/css\" rel=\"stylesheet\" href=\"./templates/plugin/nyroModal.full.css\" />\n";
echo "    <script type=\"text/JavaScript\" src=\"./js/jquery.pack.js\"></script>\n";
echo "    <script type=\"text/JavaScript\" src=\"./js/jquery.tablesorter.pack.js\"></script>\n";
echo "    <script type=\"text/JavaScript\" src=\"./js/jquery.mousewheel.pack.js\"></script>\n";
echo "    <script type=\"text/JavaScript\" src=\"./js/jquery.color.pack.js\"></script>\n";
echo "    <script type=\"text/JavaScript\" src=\"./js/jquery.nyroModal.pack.js\"></script>\n";
echo "    <script type=\"text/JavaScript\" src=\"./phpsysinfo.js\"></script>\n";
foreach($plugins as $plugin) {
  $filename = "./plugins/" . $plugin . "/js/" . $plugin . ".js";
  if (file_exists($filename)) {
    echo "    <script type=\"text/JavaScript\" src=\"" . $filename . "\"></script>\n";
  }
}
echo "    <title>Loading... please wait!</title>\n";
echo "  </head>\n";
echo "  <body>\n";
echo "    <div id=\"loader\">\n";
echo "      <h1>Loading... please wait!</h1>\n";
echo "    </div>\n";
echo "    <div id=\"errors\" style=\"display: none; width: 940px\">\n";
echo "      <div id=\"errorlist\">\n";
echo "        <h2>Oh, I'm sorry. Something seems to be wrong.</h2>\n";
echo "      </div>\n";
echo "    </div>\n";
echo "    <div id=\"container\" style=\"display: none;\">\n";
echo "      <h1 id=\"title\"><a href=\"#errors\" class=\"nyroModal\"><img id=\"warn\" style=\"vertical-align: middle; display:none; border:0px;\" src=\"./gfx/attention.gif\" alt=\"warning\" /></a></h1>\n";
echo "      <div id=\"select\">\n";
echo "        <span lang='044'>Template</span>\n";
echo "        <select id=\"template\" name=\"template\">\n";
echo $tpl_option_list;
echo "        </select>\n";
echo "        <span lang='045'>Language</span>\n";
echo "        <select id=\"lang\" name=\"lang\">\n";
$dirlist = gdc('./language/');
sort($dirlist);
foreach($dirlist as $file) {
  $lang_ext = substr($file, strlen($file) -4);
  $lang_name = substr($file, 0, strlen($file) -4);
  if ($lang_ext == ".xml") {
    if ($lang_name . ".xml" == $lang) {
      echo "          <option value=\"" . $lang_name . "\" selected=\"selected\">" . $lang_name . "</option>\n";
    } else {
      echo "          <option value=\"" . $lang_name . "\">" . $lang_name . "</option>\n";
    }
  }
}
echo "        </select>\n";
echo "      </div>\n";
echo "      <div id=\"vitals\">\n";
echo "        <h2 lang=\"002\">System vitals</h2>\n";
echo "        <table class=\"stripeMe\" id=\"vitalsTable\" cellspacing=\"0\"></table>\n";
echo "      </div>\n";
echo "      <div id=\"hardware\">\n";
echo "        <h2 lang=\"010\">Hardware Information</h2>\n";
echo "        <table class=\"stripeMe\" id=\"cpuTable\" cellspacing=\"0\"></table>\n";
echo "        <h3 style=\"cursor: pointer\" id=\"sPci\"><img src=\"./gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /><span lang=\"017\">PCI devices</span></h3>\n";
echo "        <h3 style=\"cursor: pointer; display: none;\" id=\"hPci\"><img src=\"./gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /><span lang=\"017\">PCI devices</span></h3>\n";
echo "        <table id=\"pciTable\" cellspacing=\"0\" style=\"display: none;\"></table>\n";
echo "        <h3 class=\"odd\" style=\"cursor: pointer\" id=\"sIde\"><img src=\"./gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /><span lang=\"018\">IDE devices</span></h3>\n";
echo "        <h3 class=\"odd\" style=\"cursor: pointer; display: none;\" id=\"hIde\"><img src=\"./gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /><span lang=\"018\">IDE devices</span></h3>\n";
echo "        <table class=\"odd\" id=\"ideTable\" cellspacing=\"0\" style=\"display: none;\"></table>\n";
echo "        <h3 style=\"cursor: pointer\" id=\"sScsi\"><img src=\"./gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /><span lang=\"019\">SCSI devices</span></h3>\n";
echo "        <h3 style=\"cursor: pointer; display: none;\" id=\"hScsi\"><img src=\"./gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /><span lang=\"019\">SCSI device</span></h3>\n";
echo "        <table id=\"scsiTable\" cellspacing=\"0\" style=\"display: none;\"></table>\n";
echo "        <h3 class=\"odd\" style=\"cursor: pointer\" id=\"sUsb\"><img src=\"./gfx/bullet_toggle_plus.png\" alt=\"plus\" style=\"vertical-align:middle;\" /><span lang=\"020\">USB devices</span></h3>\n";
echo "        <h3 class=\"odd\" style=\"cursor: pointer; display: none;\" id=\"hUsb\"><img src=\"./gfx/bullet_toggle_minus.png\" alt=\"minus\" style=\"vertical-align:middle;\" /><span lang=\"020\">USB devices</span></h3>\n";
echo "        <table class=\"odd\" id=\"usbTable\" cellspacing=\"0\" style=\"display: none;\"></table>\n";
echo "      </div>\n";
echo "      <div id=\"memory\">\n";
echo "        <h2 lang=\"027\">Memory Usage</h2>\n";
echo "        <table class=\"stripeMe\" id=\"memoryTable\" cellspacing=\"0\"></table>\n";
echo "        <table id=\"MemTable\" cellspacing=\"0\" style=\"display: none;width:100%;\"></table>\n";
echo "        <table class=\"stripeMe\" id=\"swapTable\" cellspacing=\"0\"></table>\n";
echo "        <table class=\"odd\" id=\"swapdevTable\" cellspacing=\"0\" style=\"display: none;\"></table>\n";
echo "      </div>\n";
echo "      <div id=\"filesystem\">\n";
echo "        <h2 lang=\"030\">Mounted Filesystems</h2>\n";
echo "        <table class=\"stripeMe\" id=\"filesystemTable\" cellspacing=\"0\"></table>\n";
echo "      </div>\n";
echo "      <div id=\"network\">\n";
echo "        <h2 lang=\"021\">Network Usage</h2>\n";
echo "        <table class=\"stripeMe\" id=\"networkTable\" cellspacing=\"0\"></table>\n";
echo "      </div>\n";
echo "      <div id=\"voltage\" style=\"display: none;\">\n";
echo "        <h2 lang=\"052\">Voltage</h2>\n";
echo "        <table class=\"stripeMe\" id=\"voltageTable\" cellspacing=\"0\">\n";
echo "          <tr><th lang=\"059\">Label</th><th class=\"right\" lang=\"052\">Voltage</th><th class=\"right\" lang=\"055\" style=\"width: 60px;\">Min</th><th class=\"right\" lang=\"056\" style=\"width: 60px;\">Max</th></tr>\n";
echo "        </table>\n";
echo "      </div>\n";
echo "      <div id=\"temp\" style=\"display: none;\">\n";
echo "        <h2 lang=\"051\">Temperature</h2>\n";
echo "        <table class=\"stripeMe\" id=\"tempTable\" cellspacing=\"0\">\n";
echo "          <tr><th lang=\"059\">Label</th><th class=\"right\" lang=\"054\" style=\"width: 60px;\">Value</th><th class=\"right\" lang=\"058\" style=\"width: 60px;\">Limit</th></tr>\n";
echo "        </table>\n";
echo "      </div>\n";
echo "      <div id=\"fan\" style=\"display: none;\">\n";
echo "        <h2 lang=\"053\">Fan</h2>\n";
echo "        <table class=\"stripeMe\" id=\"fanTable\" cellspacing=\"0\">\n";
echo "          <tr><th lang=\"059\">Label</th><th class=\"right\" lang=\"054\" style=\"width: 60px;\">Value</th><th class=\"right\" lang=\"055\" style=\"width: 60px;\">Min</th></tr>\n";
echo "        </table>\n";
echo "      </div>\n";
echo "      <div id=\"ups\" style=\"display: none;\">\n";
echo "        <h2 lang=\"068\">UPS information</h2>\n";
echo "        <table class=\"stripeMe\" id=\"upsTable\" cellspacing=\"0\">\n";
echo "          <tr><td></td><td style=\"width: 250px;\"></td></tr>\n";
echo "        </table>\n";
echo "      </div>\n";
foreach($plugins as $plugin) {
  echo "      <div id=\"plugin_" . $plugin . "\" style=\"display:none;float:left;margin:10px 0pt 0pt 10px;padding: 1px;\">\n";
  echo "      </div>\n";
}
echo "      <div id=\"footer\">\n";
echo "        <span lang=\"047\">Generated by</span>&nbsp;<a href=\"http://phpsysinfo.sourceforge.net/\">phpSysInfo&nbsp;-&nbsp;<span id=\"version\"></span></a>\n";
echo "      </div>\n";
echo "    </div>\n";
echo "  </body>\n";
echo "</html>\n";
