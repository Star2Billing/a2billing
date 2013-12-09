<?php

// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// $Id: class.Darwin.inc.php,v 1.33 2006/06/14 16:36:34 bigmichi1 Exp $
if (!defined('IN_PHPSYSINFO')) {
    die("No Hacking");
}

require_once(APP_ROOT . '/includes/os/class.BSD.common.inc.php');

$error->addWarning("The Darwin version of phpSysInfo is work in progress, some things currently don't work");

class sysinfo extends bsd_common
{
  public $cpu_regexp;
  public $scsi_regexp;

  public $parser;
  // Our contstructor
  // this function is run on the initialization of this class
  public function sysinfo ()
  {
    // $this->cpu_regexp = "CPU: (.*) \((.*)-MHz (.*)\)";
    // $this->scsi_regexp1 = "^(.*): <(.*)> .*SCSI.*device";
    $this->cpu_regexp2 = "/(.*) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+)/";
    $this->parser = new Parser();
  }

  public function grab_key ($key)
  {
    $s = execute_program('sysctl', $key);
    $s = preg_replace('/' . $key . ': /', '', $s);
    $s = preg_replace('/' . $key . ' = /', '', $s); // fix Apple set keys

    return $s;
  }

  public function grab_ioreg ($key)
  {
    $s = execute_program('ioreg', '-cls "' . $key . '" | grep "' . $key . '"'); //ioreg -cls "$key" | grep "$key"
    $s = preg_replace('/\|/', '', $s);
    $s = preg_replace('/\+\-\o/', '', $s);
    $s = preg_replace('/[ ]+/', '', $s);
    $s = preg_replace('/<[^>]+>/', '', $s); // remove possible XML conflicts

    return $s;
  }

  public function get_sys_ticks ()
  {
    $a = execute_program('sysctl', '-n kern.boottime'); // get boottime (value in seconds)
    $sys_ticks = time() - $a;

    return $sys_ticks;
  }

  public function cpu_info ()
  {
    $results = array();
    // $results['model'] = $this->grab_key('hw.model'); // need to expand this somehow...
    // $results['model'] = $this->grab_key('hw.machine');
    $results['model'] = preg_replace('/Processor type: /', '', execute_program('hostinfo', '| grep "Processor type"')); // get processor type
    $results['cpus'] = $this->grab_key('hw.ncpu');
    $results['cpuspeed'] = round($this->grab_key('hw.cpufrequency') / 1000000); // return cpu speed - Mhz
    $results['busspeed'] = round($this->grab_key('hw.busfrequency') / 1000000); // return bus speed - Mhz
    $results['cache'] = round($this->grab_key('hw.l2cachesize') / 1024); // return l2 cache

    if (($this->grab_key('hw.model') == "PowerMac3,6") && ($results['cpus'] == "2")) { $results['model'] = 'Dual G4 - (PowerPC 7450)';} // is Dual G4
    if (($this->grab_key('hw.model') == "PowerMac7,2") && ($results['cpus'] == "2")) { $results['model'] = 'Dual G5 - (PowerPC 970)';} // is Dual G5
    if (($this->grab_key('hw.model') == "PowerMac1,1") && ($results['cpus'] == "1")) { $results['model'] = 'B&W G3 - (PowerPC 750)';} // is B&W G3

    return $results;
  }
  // get the pci device information out of ioreg
  public function pci ()
  {
    $results = array();
    $s = $this->grab_ioreg('IOPCIDevice');

    $lines = preg_split("/\n/", $s);
    for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i], 19);
      $results[$i] = $ar_buf[0];
    }
    asort($results);

    return array_values(array_unique($results));
  }
  // get the ide device information out of ioreg
  public function ide ()
  {
    $results = array();
    // ioreg | grep "Media  <class IOMedia>"
    $s = $this->grab_ioreg('IOATABlockStorageDevice');

    $lines = preg_split("/\n/", $s);
    $j = 0;
    for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
      $ar_buf = preg_split("/\/\//", $lines[$i], 19);

      if ( isset( $ar_buf[1] ) && $ar_buf[1] == 'class IOMedia' && preg_match('/Media/', $ar_buf[0])) {
        $results[$j++]['model'] = $ar_buf[0];
      }
    }
    asort($results);

    return array_values(array_unique($results));
  }

  public function memory ()
  {
    $s = $this->grab_key('hw.memsize');

    $results['ram'] = array();
    $results['swap'] = array();
    $results['devswap'] = array();

    $pstat = execute_program('vm_stat'); // use darwin's vm_stat
    $lines = preg_split("/\n/", $pstat);
    for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i], 19);

      if ($i == 1) {
        $results['ram']['free'] = $ar_buf[2] * 4; // calculate free memory from page sizes (each page = 4MB)
      }
    }

    $results['ram']['total'] = $s / 1024;
    $results['ram']['shared'] = 0;
    $results['ram']['buffers'] = 0;
    $results['ram']['used'] = $results['ram']['total'] - $results['ram']['free'];
    $results['ram']['cached'] = 0;

    $results['ram']['percent'] = round(($results['ram']['used'] * 100) / $results['ram']['total']);
    // need to fix the swap info...
    // meanwhile silence and / or disable the swap information
    $pstat = execute_program('swapinfo', '-k', false);
    if ($pstat != "ERROR") {
        $lines = preg_split("/\n/", $pstat);

        for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
          $ar_buf = preg_split("/\s+/", $lines[$i], 6);

          if ($i == 0) {
            $results['swap']['total'] = 0;
            $results['swap']['used'] = 0;
            $results['swap']['free'] = 0;
          } else {
            $results['swap']['total'] = $results['swap']['total'] + $ar_buf[1];
            $results['swap']['used'] = $results['swap']['used'] + $ar_buf[2];
            $results['swap']['free'] = $results['swap']['free'] + $ar_buf[3];
          }
        }
        $results['swap']['percent'] = round(($results['swap']['used'] * 100) / $results['swap']['total']);
    }

    return $results;
  }

  public function network ()
  {
    $netstat = execute_program('netstat', '-nbdi | cut -c1-24,42- | grep Link');
    $lines = preg_split("/\n/", $netstat);
    $results = array();
    for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i], 10);
      if (!empty($ar_buf[0])) {
        $results[$ar_buf[0]] = array();

        $results[$ar_buf[0]]['rx_bytes'] = $ar_buf[5];
        $results[$ar_buf[0]]['rx_packets'] = $ar_buf[3];
        $results[$ar_buf[0]]['rx_errs'] = $ar_buf[4];
        $results[$ar_buf[0]]['rx_drop'] = isset( $ar_buf[10] ) ? $ar_buf[10] : 0;

        $results[$ar_buf[0]]['tx_bytes'] = $ar_buf[8];
        $results[$ar_buf[0]]['tx_packets'] = $ar_buf[6];
        $results[$ar_buf[0]]['tx_errs'] = $ar_buf[7];
        $results[$ar_buf[0]]['tx_drop'] = isset( $ar_buf[10] ) ? $ar_buf[10] : 0;

        $results[$ar_buf[0]]['errs'] = $ar_buf[4] + $ar_buf[7];
        $results[$ar_buf[0]]['drop'] = isset( $ar_buf[10] ) ? $ar_buf[10] : 0;
      }
    }

    return $results;
  }

  public function distroicon ()
  {
    $result = 'Darwin.png';

    return($result);
  }

}
