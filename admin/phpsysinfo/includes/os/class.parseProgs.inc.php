<?php
/***************************************************************************
*   Copyright (C) 2006 by phpSysInfo - A PHP System Information Script    *
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
// $Id: class.parseProgs.inc.php,v 1.22 2008/05/25 11:17:26 bigmichi1 Exp $
class Parser {
  private $debug = debug;
  private $df_param = "";
  public function add_df_param($param) {
    $this->df_param = $this->df_param . " " . $param;
  }
  public function parse_lspci() {
    $arrResults = array();
    if (execute_program("lspci", "", $strBuff, $this->debug)) {
      $arrLines = split("\n", $strBuff);
      foreach($arrLines as $strLine) {
        list($strAddr, $strName) = explode(' ', trim($strLine), 2);
        $strName = preg_replace('/\(.*\)/', '', $strName);
        $arrResults[] = $strName;
      }
    }
    if (empty($arrResults)) {
      return false;
    } else {
      asort($arrResults);
      return $arrResults;
    }
  }
  public function parse_pciconf() {
    $arrResults = array();
    $intS = 0;
    if (execute_program("pciconf", "-lv", $strBuff, $this->debug)) {
      $arrLines = explode("\n", $strBuff);
      foreach($arrLines as $strLine) {
        if (preg_match("/(.*) = '(.*)'/", $strLine, $arrParts)) {
          if (trim($arrParts[1]) == "vendor") {
            $arrResults[$intS] = trim($arrParts[2]);
          } elseif (trim($arrParts[1]) == "device") {
            $arrResults[$intS].= " - " . trim($arrParts[2]);
            $intS++;
          }
        }
      }
    }
    if (empty($arrResults)) {
      return false;
    } else {
      asort($arrResults);
      return $arrResults;
    }
  }
  public function parse_filesystems() {
    $results = array();
    $j = 0;
    if (execute_program('df', '-k' . $this->df_param, $df, $this->debug) || !empty($df)) {
      $df = preg_split("/\n/", $df, -1, PREG_SPLIT_NO_EMPTY);
      natsort($df);
      if (showInodes) {
        if (execute_program('df', '-i' . $this->df_param, $df2, $this->debug) || !empty($df)) {
          $df2 = preg_split("/\n/", $df2, -1, PREG_SPLIT_NO_EMPTY);
          // Store inode use% in an associative array (df_inodes) for later use
          foreach($df2 as $df2_line) {
            if (preg_match("/^(\S+).*\s([0-9]+)%/", $df2_line, $inode_buf)) {
              $df_inodes[$inode_buf[1]] = $inode_buf[2];
            }
          }
          unset($df2, $df2_line, $inode_buf);
        }
      }
      if (execute_program('mount', '', $mount, $this->debug)) {
        $mount = preg_split("/\n/", $mount, -1, PREG_SPLIT_NO_EMPTY);
        foreach($mount as $mount_line) {
          if (preg_match("/\S+ on (\S+) type (.*) \((.*)\)/", $mount_line, $mount_buf)) {
            $mount_parm[$mount_buf[1]]['fstype'] = $mount_buf[2];
            $mount_parm[$mount_buf[1]]['options'] = $mount_buf[3];
          } elseif (preg_match("/\S+ (.*) on (\S+) \((.*)\)/", $mount_line, $mount_buf)) {
            $mount_parm[$mount_buf[2]]['fstype'] = $mount_buf[1];
            $mount_parm[$mount_buf[2]]['options'] = $mount_buf[3];
          } elseif (preg_match("/\S+ on (\S+) \((\S+)(,\s(.*))?\)/", $mount_line, $mount_buf)) {
            $mount_parm[$mount_buf[1]]['fstype'] = $mount_buf[2];
            $mount_parm[$mount_buf[1]]['options'] = isset($mount_buf[4]) ? $mount_buf[4] : '';
          }
        }
        unset($mount, $mount_line, $mount_buf);
        foreach($df as $df_line) {
          $df_buf1 = preg_split("/(\%\s)/", $df_line, 2);
          if (count($df_buf1) != 2) {
            continue;
          }
          preg_match("/(.*)(\s+)(([0-9]+)(\s+)([0-9]+)(\s+)([0-9]+)(\s+)([0-9]+)$)/", $df_buf1[0], $df_buf2);
          $df_buf = array($df_buf2[1], $df_buf2[4], $df_buf2[6], $df_buf2[8], $df_buf2[10], $df_buf1[1]);
          if (count($df_buf) == 6) {
            $df_buf[5] = trim($df_buf[5]);
            if (hide_mount($df_buf[5])) {
              continue;
            }
            $df_buf[0] = trim(str_replace("\$", "\\$", $df_buf[0]));
            if (hide_fstype($mount_parm[$df_buf[5]]['fstype'])) {
              continue;
            }
            if (!showBind && stristr($mount_parm[$df_buf[5]]['options'], "bind")) {
              continue;
            }
            $results[$j] = array();
            $results[$j]['disk'] = str_replace("\\$", "\$", $df_buf[0]);
            $results[$j]['size'] = $df_buf[1];
            $results[$j]['used'] = $df_buf[2];
            $results[$j]['free'] = $df_buf[3];
            if ($results[$j]['used'] < 0) {
              $results[$j]['size'] = $results[$j]['free'];
              $results[$j]['free'] = 0;
              $results[$j]['used'] = $results[$j]['size'];
            }
            if ($results[$j]['size'] == 0) {
              continue;
            }
            $results[$j]['percent'] = round(($results[$j]['used']*100) /$results[$j]['size']);
            $results[$j]['mount'] = $df_buf[5];
            $results[$j]['fstype'] = $mount_parm[$df_buf[5]]['fstype'];
            $results[$j]['options'] = $mount_parm[$df_buf[5]]['options'];
            if (showInodes && isset($df_inodes[$results[$j]['disk']])) {
              $results[$j]['inodes'] = $df_inodes[$results[$j]['disk']];
            }
            $j++;
          }
        }
        return $results;
      } else {
        return array();
      }
    } else {
      return array();
    }
  }
}
?>
