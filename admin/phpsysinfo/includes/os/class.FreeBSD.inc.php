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

// $Id: class.FreeBSD.inc.php,v 1.17 2006/04/18 16:22:26 bigmichi1 Exp $
if (!defined('IN_PHPSYSINFO')) {
    die("No Hacking");
}

require_once(APP_ROOT . '/includes/os/class.BSD.common.inc.php');

class sysinfo extends bsd_common
{
  public $cpu_regexp   = "";
  public $scsi_regexp1 = "";
  public $scsi_regexp2 = "";
  public $cpu_regexp2  = "";

  // Our contstructor
  // this function is run on the initialization of this class
  public function sysinfo ()
  {
    $this->bsd_common();
    $this->cpu_regexp = "CPU: (.*) \((.*)-MHz (.*)\)";
    $this->scsi_regexp1 = "^(.*): <(.*)> .*SCSI.*device";
    $this->scsi_regexp2 = "^(da[0-9]): (.*)MB ";
    $this->cpu_regexp2 = "/(.*) ([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+)/";
  }

  public function get_sys_ticks ()
  {
    $s = explode(' ', $this->grab_key('kern.boottime'));
    $a = preg_replace('/{ /', '', $s[3]);
    $sys_ticks = time() - $a;

    return $sys_ticks;
  }

  public function network ()
  {
    $netstat = execute_program('netstat', '-nibd | grep Link');
    $lines = preg_split("/\n/", $netstat);
    $results = array();
    for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i]);
      if (!empty($ar_buf[0])) {
        $results[$ar_buf[0]] = array();

        if (strlen($ar_buf[3]) < 15) {
          $results[$ar_buf[0]]['rx_bytes'] = $ar_buf[5];
          $results[$ar_buf[0]]['rx_packets'] = $ar_buf[3];
          $results[$ar_buf[0]]['rx_errs'] = $ar_buf[4];
          $results[$ar_buf[0]]['rx_drop'] = $ar_buf[10];

          $results[$ar_buf[0]]['tx_bytes'] = $ar_buf[8];
          $results[$ar_buf[0]]['tx_packets'] = $ar_buf[6];
          $results[$ar_buf[0]]['tx_errs'] = $ar_buf[7];
          $results[$ar_buf[0]]['tx_drop'] = $ar_buf[10];

          $results[$ar_buf[0]]['errs'] = $ar_buf[4] + $ar_buf[7];
          $results[$ar_buf[0]]['drop'] = $ar_buf[10];
        } else {
          $results[$ar_buf[0]]['rx_bytes'] = $ar_buf[6];
          $results[$ar_buf[0]]['rx_packets'] = $ar_buf[4];
          $results[$ar_buf[0]]['rx_errs'] = $ar_buf[5];
          $results[$ar_buf[0]]['rx_drop'] = $ar_buf[11];

          $results[$ar_buf[0]]['tx_bytes'] = $ar_buf[9];
          $results[$ar_buf[0]]['tx_packets'] = $ar_buf[7];
          $results[$ar_buf[0]]['tx_errs'] = $ar_buf[8];
          $results[$ar_buf[0]]['tx_drop'] = $ar_buf[11];

          $results[$ar_buf[0]]['errs'] = $ar_buf[5] + $ar_buf[8];
          $results[$ar_buf[0]]['drop'] = $ar_buf[11];
        }
      }
    }

    return $results;
  }

  public function distroicon ()
  {
    $result = 'FreeBSD.png';

    return($result);
  }

  public function memory_additional($results)
  {
    $pagesize = $this->grab_key("hw.pagesize");
    $results['ram']['cached'] = $this->grab_key("vm.stats.vm.v_cache_count") * $pagesize / 1024;
    $results['ram']['cached_percent'] = round( $results['ram']['cached'] * 100 / $results['ram']['total']);
    $results['ram']['app'] = $this->grab_key("vm.stats.vm.v_active_count") * $pagesize / 1024;
    $results['ram']['app_percent'] = round( $results['ram']['app'] * 100 / $results['ram']['total']);
    $results['ram']['buffers'] = $results['ram']['used'] - $results['ram']['app'] - $results['ram']['cached'];
    $results['ram']['buffers_percent'] = round( $results['ram']['buffers'] * 100 / $results['ram']['total']);

    return $results;
  }
}
