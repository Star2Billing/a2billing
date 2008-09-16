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
// $Id: class.mdstat.inc.php,v 1.2 2008/05/31 21:03:28 bigmichi1 Exp $
//
class mdstat extends psi_plugin {
  private $filecontent;
  private $result;
  private $mdstat_location = "/proc/mdstat";
  public function __construct() {
    parent::__construct(__CLASS__);
    if (rfts($this->mdstat_location, $buffer)) {
      $this->filecontent = explode("\n", $buffer);
    } else {
      $this->filecontent = array();
    }
  }
  public function execute() {
    if (empty($this->filecontent)) {
      $this->result = array();
      return;
    }
    // get the supported types
    if (preg_match('/[a-zA-Z]* : (\[([a-z0-9])*\]([ \n]))+/', $this->filecontent[0], $res)) {
      $parts = explode(" : ", $res[0]);
      $parts = explode(" ", $parts[1]);
      $count = 0;
      foreach($parts as $types) {
        if (trim($types) != "") {
          $this->result['supported_types'][$count++] = substr(trim($types), 1, -1);
        }
      }
    }
    // get disks
    $count = 2;
    do {
      $parts = explode(" : ", $this->filecontent[$count]);
      $dev = trim($parts[0]);
      if (count($parts) == 2) {
        $details = explode(' ', $parts[1]);
        if (!strstr($details[0], 'inactive')) {
          $this->result['devices'][$dev]['level'] = $details[1];
        }
        $this->result['devices'][$dev]['status'] = $details[0];
        for ($i = 2;$i < (count($details) -2);$i++) {
          preg_match('/(([a-z0-9])+)(\[([0-9]+)\])(\([SF ]\))?/', trim($details[$i]), $partition);
          if (count($partition) == 5 || count($partition) == 6) {
            $this->result['devices'][$dev]['partitions'][$partition[1]]['raid_index'] = substr(trim($partition[3]), 1, -1);
            $this->result['devices'][$dev]['partitions'][$partition[1]]['status'] = (isset($partition[5]) ? (str_replace(array("(", ")"), array("", ""), trim($partition[5]))) : " ");
          }
        }
        $count++;
        $optionline = $this->filecontent[$count-1] . $this->filecontent[$count];
        if ($pos = strpos($optionline, "k chunk")) {
          $this->result['devices'][$dev]['chunk_size'] = trim(substr($optionline, $pos-3, 3));
        } else {
          $this->result['devices'][$dev]['chunk_size'] = -1;
        }
        if ($pos = strpos($optionline, "super non-persistent")) {
          $this->result['devices'][$dev]['pers_superblock'] = 0;
        } else {
          $this->result['devices'][$dev]['pers_superblock'] = 1;
        }
        if ($pos = strpos($optionline, "algorithm")) {
          $this->result['devices'][$dev]['algorithm'] = trim(substr($optionline, $pos+9, 2));
        } else {
          $this->result['devices'][$dev]['algorithm'] = -1;
        }
        if (preg_match('/(\[[0-9]?\/[0-9]\])/', $optionline, $res)) {
          $slashpos = strpos($res[0], '/');
          $this->result['devices'][$dev]['registered'] = substr($res[0], 1, $slashpos-1);
          $this->result['devices'][$dev]['active'] = substr($res[0], $slashpos+1, strlen($res[0]) -$slashpos-2);
        } else {
          $this->result['devices'][$dev]['registered'] = -1;
          $this->result['devices'][$dev]['active'] = -1;
        }
        if (preg_match(('/([a-z]*=[0-9]+%)/'), $optionline, $res)) {
          list($this->result['devices'][$dev]['action']['name'], $this->result['devices'][$dev]['action']['percent']) = explode("=", str_replace("%", "", $res[0]));
          if (preg_match(('/([a-z]*=[0-9\.]+[a-z]+)/'), $this->filecontent[$count+1], $res)) {
            $time = explode("=", $res[0]);
            list($this->result['devices'][$dev]['action']['finish_time'], $this->result['devices'][$dev]['action']['finish_unit']) = sscanf($time[1], '%f%s');
          } else {
            $this->result['devices'][$dev]['action']['finish_time'] = -1;
            $this->result['devices'][$dev]['action']['finish_unit'] = -1;
          }
        } else {
          $this->result['devices'][$dev]['action']['name'] = -1;
          $this->result['devices'][$dev]['action']['percent'] = -1;
          $this->result['devices'][$dev]['action']['finish_time'] = -1;
          $this->result['devices'][$dev]['action']['finish_unit'] = -1;
        }
      } else {
        $count++;
      }
    }
    while (count($this->filecontent) > $count);
    $lastline = $this->filecontent[count($this->filecontent) -2];
    if (strpos($lastline, "unused devices") !== false) {
      $parts = explode(":", $lastline);
      $this->result['unused_devs'] = trim(str_replace(array("<", ">"), array("", ""), $parts[1]));
    } else {
      $this->result['unused_devs'] = -1;
    }
  }
  public function xml() {
    $rootelement = "plugin_" . __CLASS__;
    $xml = simplexml_load_string("<?xml version='1.0'?>\n<" . $rootelement . "/>");
    if (empty($this->result)) {
      return $xml;
    }
    $sup = $xml->addChild("Supported_Types");
    foreach($this->result['supported_types'] as $type) {
      $typ = $sup->addChild("Type", $type);
      $typ->addChild("Name", $typ);
    }
    foreach($this->result['devices'] as $key => $device) {
      $dev = $xml->addChild("Device");
      $dev->addChild("Device_Name", $key);
      $dev->addChild("Level", $device["level"]);
      $dev->addChild("Disk_Status", $device["status"]);
      $dev->addChild("Chunk_Size", $device["chunk_size"]);
      $dev->addChild("Persistend_Superblock", $device["pers_superblock"]);
      $dev->addChild("Algorithm", $device["algorithm"]);
      $dev->addChild("Disks_Registered", $device["registered"]);
      $dev->addChild("Disks_Active", $device["active"]);
      $action = $dev->addChild("Action");
      $action->addChild("Percent", $device['action']['percent']);
      $action->addChild("Name", $device['action']['name']);
      $action->addChild("Time_To_Finish", $device['action']['finish_time']);
      $action->addChild("Time_Unit", $device['action']['finish_unit']);
      $disks = $dev->addChild("Disks");
      foreach($device['partitions'] as $diskkey => $disk) {
        $disktemp = $disks->addChild("Disk");
        $disktemp->addChild("Name", $diskkey);
        $disktemp->addChild("Status", $disk['status']);
        $disktemp->addChild("Index", $disk['raid_index']);
      }
    }
    $xml->addChild("Unused_Devices", $this->result['unused_devs']);
    return $xml;
  }
}
?>
