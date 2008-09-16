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
// $Id: class.Darwin.inc.php,v 1.41 2008/06/05 18:42:30 bigmichi1 Exp $
//
if (!defined('IN_PHPSYSINFO')) {
  die("No Hacking");
}
require_once (APP_ROOT . '/includes/os/class.BSD.common.inc.php');
$error->addWarning("The Darwin version of phpSysInfo is work in progress, some things currently don't work");
class sysinfo extends bsd_common {
  private $debug = debug;
  private $parser;
  // Our contstructor
  // this function is run on the initialization of this class
  public function __construct() {
    $this->set_cpuregexp1 = ("CPU: (.*) \((.*)-MHz (.*)\)");
    $this->set_cpuregexp2("/(.*) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+)/");
    $this->set_scsiregexp1("^(.*): <(.*)> .*SCSI.*device");
    $this->parser = new Parser();
  }
  private function grab_key($key) {
    if (execute_program('sysctl', $key, $s, $this->debug)) {
      $s = ereg_replace($key . ': ', '', $s);
      $s = ereg_replace($key . ' = ', '', $s); // fix Apple set keys
      return $s;
    } else {
      return '';
    }
  }
  private function grab_ioreg($key) {
    if (execute_program('ioreg', '-cls "' . $key . '" | grep "' . $key . '"', $s, $this->debug)) { //ioreg -cls "$key" | grep "$key"
      $s = ereg_replace('\|', '', $s);
      $s = ereg_replace('\+\-\o', '', $s);
      $s = ereg_replace('[ ]+', '', $s);
      $s = ereg_replace('<[^>]+>', '', $s); // remove possible XML conflicts
      return $s;
    } else {
      return '';
    }
  }
  protected function get_sys_ticks() {
    if (execute_program('sysctl', '-n kern.boottime', $a, $this->debug)) {
      // get boottime (value in seconds)
      $sys_ticks = time() -$a;
      return $sys_ticks;
    } else {
      return 'N.A.';
    }
  }
  public function cpu_info() {
    $results = array();
    // $results['model'] = $this->grab_key('hw.model'); // need to expand this somehow...
    // $results['model'] = $this->grab_key('hw.machine');
    if (!execute_program('hostinfo', '| grep "Processor type"', $buf, $this->debug)) {
      $buf = 'N.A.';
    }
    $results['model'] = ereg_replace('Processor type: ', '', $buf); // get processor type
    $results['cpus'] = $this->grab_key('hw.ncpu');
    $results['cpuspeed'] = round($this->grab_key('hw.cpufrequency') /1000000); // return cpu speed - Mhz
    $results['busspeed'] = round($this->grab_key('hw.busfrequency') /1000000); // return bus speed - Mhz
    $results['cache'] = round($this->grab_key('hw.l2cachesize') /1024); // return l2 cache
    if (($this->grab_key('hw.model') == "PowerMac3,6") && ($results['cpus'] == "2")) {
      $results['model'] = 'Dual G4 - (PowerPC 7450)';
    } // is Dual G4
    if (($this->grab_key('hw.model') == "PowerMac7,2") && ($results['cpus'] == "2")) {
      $results['model'] = 'Dual G5 - (PowerPC 970)';
    } // is Dual G5
    if (($this->grab_key('hw.model') == "PowerMac1,1") && ($results['cpus'] == "1")) {
      $results['model'] = 'B&W G3 - (PowerPC 750)';
    } // is B&W G3
    return $results;
  }
  // get the pci device information out of ioreg
  public function pci() {
    $results = array();
    $s = $this->grab_ioreg('IOPCIDevice');
    $lines = split("\n", $s);
    for ($i = 0, $max = sizeof($lines);$i < $max;$i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i], 19);
      $results[$i] = $ar_buf[0];
    }
    asort($results);
    return array_values(array_unique($results));
  }
  // get the ide device information out of ioreg
  public function ide() {
    $results = array();
    // ioreg | grep "Media  <class IOMedia>"
    $s = $this->grab_ioreg('IOATABlockStorageDevice');
    $lines = split("\n", $s);
    $j = 0;
    for ($i = 0, $max = sizeof($lines);$i < $max;$i++) {
      $ar_buf = preg_split("/\/\//", $lines[$i], 19);
      if (isset($ar_buf[1]) && $ar_buf[1] == 'class IOMedia' && preg_match('/Media/', $ar_buf[0])) {
        $results[$j++]['model'] = $ar_buf[0];
      }
    }
    asort($results);
    return array_values(array_unique($results));
  }
  public function memory() {
    $s = $this->grab_key('hw.memsize');
    $results['ram'] = array();
    $results['swap'] = array();
    $results['devswap'] = array();
    if (!execute_program('vm_stat', '', $pstat, $this->debug)) {
      $pstat = '';
    }
    $lines = split("\n", $pstat);
    for ($i = 0, $max = sizeof($lines);$i < $max;$i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i], 19);
      if ($i == 1) {
        // calculate free memory from page sizes (each page = 4MB)
        $results['ram']['free'] = $ar_buf[2]*4;
      }
    }
    $results['ram']['total'] = $s/1024;
    $results['ram']['shared'] = 0;
    $results['ram']['buffers'] = 0;
    $results['ram']['used'] = $results['ram']['total']-$results['ram']['free'];
    $results['ram']['cached'] = 0;
    $results['ram']['percent'] = round(($results['ram']['used']*100) /$results['ram']['total']);
    // need to fix the swap info...
    // meanwhile silence and / or disable the swap information
    if (execute_program('swapinfo', '-k', $pstat, $this->debug)) {
      $lines = split("\n", $pstat);
      for ($i = 0, $max = sizeof($lines);$i < $max;$i++) {
        $ar_buf = preg_split("/\s+/", $lines[$i], 6);
        if ($i == 0) {
          $results['swap']['total'] = 0;
          $results['swap']['used'] = 0;
          $results['swap']['free'] = 0;
          $results['swap']['percent'] = 0;
        } else {
          $results['swap']['total'] = $results['swap']['total']+$ar_buf[1];
          $results['swap']['used'] = $results['swap']['used']+$ar_buf[2];
          $results['swap']['free'] = $results['swap']['free']+$ar_buf[3];
          $results['swap']['percent'] = ceil(($results['swap']['used']*100) /(($results['swap']['total'] <= 0) ? 1 : $results['swap']['total']));
        }
      }
    }
    return $results;
  }
  public function network() {
    if (execute_program('netstat', '-nbdi | cut -c1-24,42- | grep Link', $netstat, $this->debug)) {
      $lines = split("\n", $netstat);
      $results = array();
      for ($i = 0, $max = sizeof($lines);$i < $max;$i++) {
        $ar_buf = preg_split("/\s+/", $lines[$i], 10);
        if (!empty($ar_buf[0])) {
          $results[$ar_buf[0]] = array();
          $results[$ar_buf[0]]['rx_bytes'] = $ar_buf[5];
          $results[$ar_buf[0]]['tx_bytes'] = $ar_buf[8];
          $results[$ar_buf[0]]['errs'] = $ar_buf[4]+$ar_buf[7];
          $results[$ar_buf[0]]['drop'] = isset($ar_buf[10]) ? $ar_buf[10] : 0;
        }
      }
      return $results;
    } else {
      return array();
    }
  }
  public function distroicon() {
    $result = 'Darwin.png';
    return ($result);
  }
}
?>
