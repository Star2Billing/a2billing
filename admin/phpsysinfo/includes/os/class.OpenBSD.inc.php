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

// $Id: class.OpenBSD.inc.php,v 1.21 2006/04/18 17:46:15 bigmichi1 Exp $
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
    $this->cpu_regexp = "^cpu(.*) (.*) MHz";
    $this->scsi_regexp1 = "^(.*) at scsibus.*: <(.*)> .*";
    $this->scsi_regexp2 = "^(da[0-9]): (.*)MB ";
    $this->cpu_regexp2 = "/(.*),(.*),(.*),(.*),(.*)/";
    $this->pci_regexp1 = '/(.*) at pci[0-9] .* "(.*)"/';
    $this->pci_regexp2 = '/"(.*)" (.*).* at [.0-9]+ irq/';
  }

  public function get_sys_ticks ()
  {
    $a = $this->grab_key('kern.boottime');
    $sys_ticks = time() - $a;

    return $sys_ticks;
  }

  public function network ()
  {
    $netstat_b = execute_program('netstat', '-nbdi | cut -c1-25,44- | grep Link | grep -v \'* \'');
    $netstat_n = execute_program('netstat', '-ndi | cut -c1-25,44- | grep Link | grep -v \'* \'');
    $lines_b = preg_split("/\n/", $netstat_b);
    $lines_n = preg_split("/\n/", $netstat_n);
    $results = array();
    for ($i = 0, $max = sizeof($lines_b); $i < $max; $i++) {
      $ar_buf_b = preg_split("/\s+/", $lines_b[$i]);
      $ar_buf_n = preg_split("/\s+/", $lines_n[$i]);
      if (!empty($ar_buf_b[0]) && !empty($ar_buf_n[3])) {
        $results[$ar_buf_b[0]] = array();

        $results[$ar_buf_b[0]]['rx_bytes'] = $ar_buf_b[3];
        $results[$ar_buf_b[0]]['rx_packets'] = $ar_buf_n[3];
        $results[$ar_buf_b[0]]['rx_errs'] = $ar_buf_n[4];
        $results[$ar_buf_b[0]]['rx_drop'] = $ar_buf_n[8];

        $results[$ar_buf_b[0]]['tx_bytes'] = $ar_buf_b[4];
        $results[$ar_buf_b[0]]['tx_packets'] = $ar_buf_n[5];
        $results[$ar_buf_b[0]]['tx_errs'] = $ar_buf_n[6];
        $results[$ar_buf_b[0]]['tx_drop'] = $ar_buf_n[8];

        $results[$ar_buf_b[0]]['errs'] = $ar_buf_n[4] + $ar_buf_n[6];
        $results[$ar_buf_b[0]]['drop'] = $ar_buf_n[8];
      }
    }

    return $results;
  }
  // get the ide device information out of dmesg
  public function ide ()
  {
    $results = array();

    $s = 0;
    for ($i = 0, $max = count($this->read_dmesg()); $i < $max; $i++) {
      $buf = $this->dmesg[$i];
      if (preg_match('/^(.*) at pciide[0-9] (.*): <(.*)>/', $buf, $ar_buf)) {
        $s = $ar_buf[1];
        $results[$s]['model'] = $ar_buf[3];
        $results[$s]['media'] = 'Hard Disk';
        // now loop again and find the capacity
        for ($j = 0, $max1 = count($this->read_dmesg()); $j < $max1; $j++) {
          $buf_n = $this->dmesg[$j];
          if (preg_match("/^($s): (.*), (.*), (.*)MB, .*$/", $buf_n, $ar_buf_n)) {
            $results[$s]['capacity'] = $ar_buf_n[4] * 2048 * 1.049;;
          }
        }
      }
    }
    asort($results);

    return $results;
  }

  public function distroicon ()
  {
    $result = 'OpenBSD.png';

    return($result);
  }

}
