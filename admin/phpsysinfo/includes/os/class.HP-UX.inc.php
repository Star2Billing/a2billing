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

// $Id: class.HP-UX.inc.php,v 1.20 2007/02/18 18:59:54 bigmichi1 Exp $

class sysinfo
{
  // get our apache SERVER_NAME or vhost
  public function vhostname ()
  {
    if (! ($result = getenv('SERVER_NAME'))) {
      $result = 'N.A.';
    }

    return $result;
  }
  // get the IP address of our vhost name
  public function vip_addr ()
  {
    return gethostbyname($this->vhostname());
  }
  // get our canonical hostname
  public function chostname ()
  {
    return execute_program('hostname');
  }
  // get the IP address of our canonical hostname
  public function ip_addr ()
  {
    if (!($result = getenv('SERVER_ADDR'))) {
      $result = gethostbyname($this->chostname());
    }

    return $result;
  }

  public function kernel ()
  {
    return execute_program('uname', '-srvm');
  }

  public function uptime ()
  {
    $result = 0;
    $ar_buf = array();

    $buf = execute_program('uptime');
    if (preg_match("/up (\d+) days,\s*(\d+):(\d+),/", $buf, $ar_buf)) {
      $min = $ar_buf[3];
      $hours = $ar_buf[2];
      $days = $ar_buf[1];
      $result = $days * 86400 + $hours * 3600 + $min * 60;
    }

    return $result;
  }

  public function users ()
  {
    $who = preg_split('/=/', execute_program('who', '-q'));
    $result = $who[1];

    return $result;
  }

  public function loadavg ($bar = false)
  {
    $ar_buf = array();

    $buf = execute_program('uptime');

    if (preg_match("/average: (.*), (.*), (.*)$/", $buf, $ar_buf)) {
      $results['avg'] = array($ar_buf[1], $ar_buf[2], $ar_buf[3]);
    } else {
      $results['avg'] = array('N.A.', 'N.A.', 'N.A.');
    }

    return $results;
  }

  public function cpu_info ()
  {
    $results = array();
    $ar_buf = array();

    $bufr = rfts( '/proc/cpuinfo' );
    if ($bufr != "ERROR") {
      $bufe = explode( "\n", $bufr );
      foreach ($bufe as $buf) {
        list($key, $value) = preg_split('/\s+:\s+/', trim($buf), 2);
        // All of the tags here are highly architecture dependant.
        // the only way I could reconstruct them for machines I don't
        // have is to browse the kernel source.  So if your arch isn't
        // supported, tell me you want it written in.
        switch ($key) {
          case 'model name':
            $results['model'] = $value;
            break;
          case 'cpu MHz':
            $results['cpuspeed'] = sprintf('%.2f', $value);
            break;
          case 'cycle frequency [Hz]': // For Alpha arch - 2.2.x
            $results['cpuspeed'] = sprintf('%.2f', $value / 1000000);
            break;
          case 'clock': // For PPC arch (damn borked POS)
            $results['cpuspeed'] = sprintf('%.2f', $value);
            break;
          case 'cpu': // For PPC arch (damn borked POS)
            $results['model'] = $value;
            break;
          case 'revision': // For PPC arch (damn borked POS)
            $results['model'] .= ' ( rev: ' . $value . ')';
            break;
          case 'cpu model': // For Alpha arch - 2.2.x
            $results['model'] .= ' (' . $value . ')';
            break;
          case 'cache size':
            $results['cache'] = $value;
            break;
          case 'bogomips':
            $results['bogomips'] += $value;
            break;
          case 'BogoMIPS': // For alpha arch - 2.2.x
            $results['bogomips'] += $value;
            break;
          case 'BogoMips': // For sparc arch
            $results['bogomips'] += $value;
            break;
          case 'cpus detected': // For Alpha arch - 2.2.x
            $results['cpus'] += $value;
            break;
          case 'system type': // Alpha arch - 2.2.x
            $results['model'] .= ', ' . $value . ' ';
            break;
          case 'platform string': // Alpha arch - 2.2.x
            $results['model'] .= ' (' . $value . ')';
            break;
          case 'processor':
            $results['cpus'] += 1;
            break;
        }
      }
      fclose($fd);
    }

    $keys = array_keys($results);
    $keys2be = array('model', 'cpuspeed', 'cache', 'bogomips', 'cpus');

    while ($ar_buf = each($keys2be)) {
      if (! in_array($ar_buf[1], $keys)) {
        $results[$ar_buf[1]] = 'N.A.';
      }
    }

    return $results;
  }

  public function pci ()
  {
    $results = array();

    $bufr = rfts( '/proc/pci' );
    if ($bufr != "ERROR") {
      $bufe = explode( "\n", $bufr );
      foreach ($bufe as $buf) {
        if (preg_match('/Bus/', $buf)) {
          $device = true;
          continue;
        }

        if ($device) {
          list($key, $value) = preg_split('/: /', $buf, 2);

          if (!preg_match('/bridge/i', $key) && !preg_match('/USB/i', $key)) {
            $results[] = preg_replace('/\([^\)]+\)\.$/', '', trim($value));
          }
          $device = false;
        }
      }
    }
    asort($results);

    return $results;
  }

  public function ide ()
  {
    $results = array();

    $bufd = gdc( '/proc/ide' );

    foreach ($bufd as $file) {
      if (preg_match('/^hd/', $file)) {
        $results[$file] = array();
        // Check if device is CD-ROM (CD-ROM capacity shows as 1024 GB)
    $buf = rfts( "/proc/ide/" . $file . "/media", 1 );
    if ($buf != "ERROR") {
          $results[$file]['media'] = trim( $buf );
          if ($results[$file]['media'] == 'disk') {
            $results[$file]['media'] = 'Hard Disk';
          }
          if ($results[$file]['media'] == 'cdrom') {
            $results[$file]['media'] = 'CD-ROM';
          }
        }

    $buf = rfts( "/proc/ide/" . $file . "/model", 1 );
    if ($buf != "ERROR") {
          $results[$file]['model'] = trim( $buf );
          if (preg_match('/WDC/', $results[$file]['model'])) {
            $results[$file]['manufacture'] = 'Western Digital';
          } elseif (preg_match('/IBM/', $results[$file]['model'])) {
            $results[$file]['manufacture'] = 'IBM';
          } elseif (preg_match('/FUJITSU/', $results[$file]['model'])) {
            $results[$file]['manufacture'] = 'Fujitsu';
          } else {
            $results[$file]['manufacture'] = 'Unknown';
          }
        }

    $buf = rfts( "/proc/ide/" . $file . "/capacity", 1 );
    if ($buf != "ERROR") {
          $results[$file]['capacity'] = trim( $buf );
          if ($results[$file]['media'] == 'CD-ROM') {
            unset($results[$file]['capacity']);
          }
        }
      }
    }
    asort($results);

    return $results;
  }

  public function scsi ()
  {
    $results = array();
    $dev_vendor = '';
    $dev_model = '';
    $dev_rev = '';
    $dev_type = '';
    $s = 1;

    $bufr = rfts( '/proc/scsi/scsi' );
    if ($bufr != "ERROR") {
      $bufe = explode( "\n", $bufr );
      foreach ($bufe as $buf) {
        if (preg_match('/Vendor/', $buf)) {
          preg_match('/Vendor: (.*) Model: (.*) Rev: (.*)/i', $buf, $dev);
          list($key, $value) = preg_split('/: /', $buf, 2);
          $dev_str = $value;
          $get_type = 1;
          continue;
        }

        if ($get_type) {
          preg_match('/Type:\s+(\S+)/i', $buf, $dev_type);
          $results[$s]['model'] = "$dev[1] $dev[2] ($dev_type[1])";
          $results[$s]['media'] = "Hard Disk";
          $s++;
          $get_type = 0;
        }
      }
    }
    asort($results);

    return $results;
  }

  public function usb ()
  {
    $results = array();
    $devstring = 0;
    $devnum = -1;

    $bufr = rfts( '/proc/bus/usb/devices' );
    if ($bufr != "ERROR") {
      $bufe = explode( "\n", $bufr );
      foreach ($bufe as $buf) {
        if (preg_match('/^T/', $buf)) {
          $devnum += 1;
        }
        if (preg_match('/^S/', $buf)) {
          $devstring = 1;
        }

        if ($devstring) {
          list($key, $value) = preg_split('/: /', $buf, 2);
          list($key, $value2) = preg_split('/=/', $value, 2);
          $results[$devnum] .= " " . trim($value2);
          $devstring = 0;
        }
      }
    }

    return $results;
  }

  public function sbus ()
  {
    $results = array();
    $_results[0] = "";
    // TODO. Nothing here yet. Move along.
    $results = $_results;

    return $results;
  }

  public function network ()
  {
    $netstat = execute_program('netstat', '-ni | tail -n +2');
    $lines = preg_split("/\n/", $netstat);
    $results = array();
    for ($i = 0, $max = sizeof($lines); $i < $max; $i++) {
      $ar_buf = preg_split("/\s+/", $lines[$i]);
      if (!empty($ar_buf[0]) && !empty($ar_buf[3])) {
        $results[$ar_buf[0]] = array();

        $results[$ar_buf[0]]['rx_bytes'] = $ar_buf[4];
        $results[$ar_buf[0]]['rx_packets'] = $ar_buf[4];
        $results[$ar_buf[0]]['rx_errs'] = $ar_buf[5];
        $results[$ar_buf[0]]['rx_drop'] = $ar_buf[8];

        $results[$ar_buf[0]]['tx_bytes'] = $ar_buf[6];
        $results[$ar_buf[0]]['tx_packets'] = $ar_buf[6];
        $results[$ar_buf[0]]['tx_errs'] = $ar_buf[7];
        $results[$ar_buf[0]]['tx_drop'] = $ar_buf[8];

        $results[$ar_buf[0]]['errs'] = $ar_buf[5] + $ar_buf[7];
        $results[$ar_buf[0]]['drop'] = $ar_buf[8];
      }
    }

    return $results;
  }
  public function memory ()
  {
    $results['ram'] = array();
    $results['swap'] = array();
    $results['devswap'] = array();

    $bufr = rfts( '/proc/meminfo' );
    if ($bufr != "ERROR") {
      $bufe = explode( "\n", $bufr );
      foreach ($bufe as $buf) {
        if (preg_match('/Mem:\s+(.*)$/', $buf, $ar_buf)) {
          $ar_buf = preg_split('/\s+/', $ar_buf[1], 6);

          $results['ram']['total'] = $ar_buf[0] / 1024;
          $results['ram']['used'] = $ar_buf[1] / 1024;
          $results['ram']['free'] = $ar_buf[2] / 1024;
          $results['ram']['shared'] = $ar_buf[3] / 1024;
          $results['ram']['buffers'] = $ar_buf[4] / 1024;
          $results['ram']['cached'] = $ar_buf[5] / 1024;
          // I don't like this since buffers and cache really aren't
          // 'used' per say, but I get too many emails about it.
          $results['ram']['percent'] = round(($results['ram']['used'] * 100) / $results['ram']['total']);
        }

        if (preg_match('/Swap:\s+(.*)$/', $buf, $ar_buf)) {
          $ar_buf = preg_split('/\s+/', $ar_buf[1], 3);

          $results['swap']['total'] = $ar_buf[0] / 1024;
          $results['swap']['used'] = $ar_buf[1] / 1024;
          $results['swap']['free'] = $ar_buf[2] / 1024;
          $results['swap']['percent'] = round(($ar_buf[1] * 100) / $ar_buf[0]);
          // Get info on individual swap files
      $swaps = rfts( '/proc/swaps' );
      if ($swaps != "ERROR") {
            $swapdevs = preg_split("/\n/", $swaps);

            for ($i = 1, $max = (sizeof($swapdevs) - 1); $i < $max; $i++) {
              $ar_buf = preg_split('/\s+/', $swapdevs[$i], 6);
              $results['devswap'][$i - 1] = array();
              $results['devswap'][$i - 1]['dev'] = $ar_buf[0];
              $results['devswap'][$i - 1]['total'] = $ar_buf[2];
              $results['devswap'][$i - 1]['used'] = $ar_buf[3];
              $results['devswap'][$i - 1]['free'] = ($results['devswap'][$i - 1]['total'] - $results['devswap'][$i - 1]['used']);
              $results['devswap'][$i - 1]['percent'] = round(($ar_buf[3] * 100) / $ar_buf[2]);
            }
            break;
      }
        }
      }
    }

    return $results;
  }

  public function filesystems ()
  {
    $df = execute_program('df', '-kP');
    $mounts = preg_split("/\n/", $df);
    $fstype = array();

    $s = execute_program('mount', '-v');
    $lines = explode("\n", $s);

    $i = 0;
    while (list(, $line) = each($lines)) {
      $a = preg_split('/ /', $line);
      $fsdev[$a[0]] = $a[4];
    }

    for ($i = 1, $j = 0, $max = sizeof($mounts); $i < $max; $i++) {
      $ar_buf = preg_split("/\s+/", $mounts[$i], 6);

      if (hide_mount($ar_buf[5])) {
        continue;
      }

      $results[$j] = array();

      $results[$j]['disk'] = $ar_buf[0];
      $results[$j]['size'] = $ar_buf[1];
      $results[$j]['used'] = $ar_buf[2];
      $results[$j]['free'] = $ar_buf[3];
      $results[$j]['percent'] = $ar_buf[4];
      $results[$j]['mount'] = $ar_buf[5];
      ($fstype[$ar_buf[5]]) ? $results[$j]['fstype'] = $fstype[$ar_buf[5]] : $results[$j]['fstype'] = $fsdev[$ar_buf[0]];
      $j++;
    }

    return $results;
  }

  public function distro ()
  {
    $result = 'HP-UX';

    return($result);
  }

  public function distroicon ()
  {
    $result = 'unknown.png';

    return($result);
  }
}
