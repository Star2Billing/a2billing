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
// $Id: class.mbm5.inc.php,v 1.10 2008/05/25 12:24:19 bigmichi1 Exp $
//
class mbinfo {
  private $buf_label;
  private $buf_value;
  function __construct() {
    rfts(APP_ROOT . "/data/MBM5.csv", $buffer);
    if (strpos($buffer, ";") === false) {
      $delim = ",";
    } else {
      $delim = ";";
    }
    $buffer = split("\n", $buffer);
    $this->buf_label = split($delim, substr($buffer[0], 0, -2));
    $this->buf_value = split($delim, substr($buffer[1], 0, -2));
  }
  public function temperature() {
    $results = array();
    $intCount = 0;
    for ($intPosi = 3;$intPosi < 6;$intPosi++) {
      if ($this->buf_value[$intPosi] == 0) {
        continue;
      }
      $results[$intCount]['label'] = $this->buf_label[$intPosi];
      preg_match("/([0-9\.])*/", str_replace(",", ".", $this->buf_value[$intPosi]), $hits);
      $results[$intCount]['value'] = $hits[0];
      $results[$intCount]['limit'] = '70.0';
      $intCount++;
    }
    return $results;
  }
  public function fans() {
    $results = array();
    $intCount = 0;
    for ($intPosi = 13;$intPosi < 16;$intPosi++) {
      if (!isset($this->buf_value[$intPosi])) {
        continue;
      }
      $results[$intCount]['label'] = $this->buf_label[$intPosi];
      preg_match("/([0-9\.])*/", str_replace(",", ".", $this->buf_value[$intPosi]), $hits);
      $results[$intCount]['value'] = $hits[0];
      $results[$intCount]['min'] = '3000';
      $intCount++;
    }
    return $results;
  }
  public function voltage() {
    $results = array();
    $intCount = 0;
    for ($intPosi = 6;$intPosi < 13;$intPosi++) {
      if ($this->buf_value[$intPosi] == 0) {
        continue;
      }
      $results[$intCount]['label'] = $this->buf_label[$intPosi];
      preg_match("/([0-9\.])*/", str_replace(",", ".", $this->buf_value[$intPosi]), $hits);
      $results[$intCount]['value'] = $hits[0];
      $results[$intCount]['min'] = '0.00';
      $results[$intCount]['max'] = '0.00';
      $intCount++;
    }
    return $results;
  }
}
?>
