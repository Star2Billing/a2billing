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
// $Id: class.apcupsd.inc.php,v 1.11 2008/05/31 20:13:29 bigmichi1 Exp $
//
// This class was created by Artem Volk ( artvolk at mail dot ru )
class upsinfo {
  private $output;
  public function __construct() {
    $output = Array();
    $upses = explode(',', PSI_UPSINFO_APCUPSD_UPS_LIST);
    $temp = '';
    for ($i = 0;$i < count($upses);$i++) {
      execute_program('apcaccess', 'status ' . trim($upses[$i]), $temp);
      if (isset($temp) && !empty($temp)) {
        $this->output[$i] = $temp;
      }
    }
  }
  public function info() {
    if (isset($this->output) && count($this->output) > 0) {
      $results = Array();
      for ($i = 0;$i < count($this->output);$i++) {
        // General info
        if (preg_match('/^UPSNAME\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['name'] = trim($data[1]);
        } else {
          $results[$i]['name'] = '';
        }
        if (preg_match('/^MODEL\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['model'] = trim($data[1]);
        } else {
          $results[$i]['model'] = '';
        }
        if (preg_match('/^UPSMODE\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['mode'] = trim($data[1]);
        } else {
          $results[$i]['mode'] = '';
        }
        if (preg_match('/^STARTTIME\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['start_time'] = trim($data[1]);
        } else {
          $results[$i]['start_time'] = '';
        }
        if (preg_match('/^STATUS\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['status'] = trim($data[1]);
        } else {
          $results[$i]['status'] = '';
        }
        if (preg_match('/^ITEMP\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['temperature'] = trim($data[1]);
        } else {
          $results[$i]['temperature'] = '';
        }
        // Outages
        if (preg_match('/^NUMXFERS\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['outages_count'] = trim($data[1]);
        } else {
          $results[$i]['outages_count'] = '';
        }
        if (preg_match('/^LASTXFER\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['last_outage'] = trim($data[1]);
        } else {
          $results[$i]['last_outage'] = '';
        }
        if (preg_match('/^XOFFBATT\s*:\s*(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['last_outage_finish'] = trim($data[1]);
        } else {
          $results[$i]['last_outage_finish'] = '';
        }
        // Line
        if (preg_match('/^LINEV\s*:\s*(\d*\.\d*)(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['line_voltage'] = trim($data[1]);
        } else {
          $results[$i]['line_voltage'] = '';
        }
        if (preg_match('/^LOADPCT\s*:\s*(\d*\.\d*)(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['load_percent'] = trim($data[1]);
        } else {
          $results[$i]['load_percent'] = '';
        }
        // Battery
        if (preg_match('/^BATTV\s*:\s*(\d*\.\d*)(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['battery_voltage'] = trim($data[1]);
        } else {
          $results[$i]['battery_voltage'] = '';
        }
        if (preg_match('/^BCHARGE\s*:\s*(\d*\.\d*)(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['battery_charge_percent'] = trim($data[1]);
        } else {
          $results[$i]['battery_charge_percent'] = '';
        }
        if (preg_match('/^TIMELEFT\s*:\s*(\d*\.\d*)(.*)$/m', $this->output[$i], $data)) {
          $results[$i]['time_left_minutes'] = trim($data[1]);
        } else {
          $results[$i]['time_left_minutes'] = '';
        }
      }
      return $results;
    }
  }
}
?>
