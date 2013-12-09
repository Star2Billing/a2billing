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

// $Id: class.lmsensors.inc.php,v 1.19 2007/02/18 19:11:31 bigmichi1 Exp $
if (!defined('IN_PHPSYSINFO')) {
    die("No Hacking");
}

require_once(APP_ROOT . "/includes/common_functions.php");

class mbinfo
{
  public $lines;

  public function mbinfo()
  {
   $lines = execute_program("sensors", "");
   // Martijn Stolk: Dirty fix for misinterpreted output of sensors,
   // where info could come on next line when the label is too long.
   $lines = str_replace(":\n", ":", $lines);
   $lines = str_replace("\n\n", "\n", $lines);
   $this->lines = explode("\n", $lines);
  }

  public function temperature()
  {
    $ar_buf = array();
    $results = array();

    $sensors_value = $this->lines;

    foreach ($sensors_value as $line) {
      $data = array();
      if (preg_match("/(.*):(.*)\((.*)=(.*),(.*)=(.*)\)(.*)/", $line, $data)) ;
      elseif (preg_match("/(.*):(.*)\((.*)=(.*)\)(.*)/", $line, $data)) ;
      else (preg_match("/(.*):(.*)/", $line, $data));
      if (count($data) > 1) {
        $temp = substr(trim($data[2]), -1);
        switch ($temp) {
          case "C";
          case "F":
            array_push($ar_buf, $line);
            break;
        }
      }
    }

    $i = 0;
    foreach ($ar_buf as $line) {
      unset($data);
      if (preg_match("/(.*):(.*).C[ ]*\((.*)=(.*).C,(.*)=(.*).C\)(.*)\)/", $line, $data)) ;
      elseif (preg_match("/(.*):(.*).C[ ]*\((.*)=(.*).C,(.*)=(.*).C\)(.*)/", $line, $data)) ;
      elseif (preg_match("/(.*):(.*).C[ ]*\((.*)=(.*).C\)(.*)/", $line, $data)) ;
      else (preg_match("/(.*):(.*).C/", $line, $data));

      $results[$i]['label'] = $data[1];
      $results[$i]['value'] = trim($data[2]);
      if ( isset( $data[6] ) && trim( $data[2] ) > trim( $data[6] ) ) {
        $results[$i]['limit'] = "+75";
        $results[$i]['perce'] = "+75";
      } else {
        $results[$i]['limit'] = isset($data[4]) ? trim($data[4]) : "+75";
        $results[$i]['perce'] = isset($data[6]) ? trim($data[6]) : "+75";
      }
      if ($results[$i]['limit'] < $results[$i]['perce']) {
         $results[$i]['limit'] = $results[$i]['perce'];
       }
      $i++;
    }

    asort($results);

    return array_values($results);
  }

  public function fans()
  {
    $ar_buf = array();
    $results = array();

    $sensors_value = $this->lines;

    foreach ($sensors_value as $line) {
      $data = array();
      if (preg_match("/(.*):(.*)\((.*)=(.*),(.*)=(.*)\)(.*)/", $line, $data));
      elseif (preg_match("/(.*):(.*)\((.*)=(.*)\)(.*)/", $line, $data));
      else preg_match("/(.*):(.*)/", $line, $data);

      if (count($data) > 1) {
        $temp = explode(" ", trim($data[2]));
        if (count($temp) == 1)
          $temp = explode("\xb0", trim($data[2]));
    if (isset($temp[1])) {
          switch ($temp[1]) {
            case "RPM":
              array_push($ar_buf, $line);
              break;
          }
    }
      }
    }

    $i = 0;
    foreach ($ar_buf as $line) {
      unset($data);
      if (preg_match("/(.*):(.*) RPM  \((.*)=(.*) RPM,(.*)=(.*)\)(.*)\)/", $line, $data));
      elseif (preg_match("/(.*):(.*) RPM  \((.*)=(.*) RPM,(.*)=(.*)\)(.*)/", $line, $data));
      elseif (preg_match("/(.*):(.*) RPM  \((.*)=(.*) RPM\)(.*)/", $line, $data));
      else preg_match("/(.*):(.*) RPM", $line, $data);

      $results[$i]['label'] = trim($data[1]);
      $results[$i]['value'] = trim($data[2]);
      $results[$i]['min'] = isset($data[4]) ? trim($data[4]) : 0;
      $i++;
    }

    asort($results);

    return array_values($results);
  }

  public function voltage()
  {
    $ar_buf = array();
    $results = array();

    $sensors_value = $this->lines;

    foreach ($sensors_value as $line) {
      $data = array();
      if (preg_match("/(.*):(.*)\((.*)=(.*),(.*)=(.*)\)(.*)/", $line, $data));
      else preg_match("/(.*):(.*)/", $line, $data);

      if (count($data) > 1) {
        $temp = explode(" ", trim($data[2]));
        if (count($temp) == 1)
          $temp = explode("\xb0", trim($data[2]));
        if (isset($temp[1])) {
          switch ($temp[1]) {
            case "V":
              array_push($ar_buf, $line);
              break;
      }
        }
      }
    }

    $i = 0;
    foreach ($ar_buf as $line) {
      unset($data);
      if (preg_match("/(.*):(.*) V  \((.*)=(.*) V,(.*)=(.*) V\)(.*)\)/", $line, $data));
      elseif (preg_match("/(.*):(.*) V  \((.*)=(.*) V,(.*)=(.*) V\)(.*)/", $line, $data));
      else preg_match("/(.*):(.*) V$/", $line, $data);
      if (isset($data[1])) {
        $results[$i]['label'] = trim($data[1]);
        $results[$i]['value'] = trim($data[2]);
        $results[$i]['min'] = isset($data[4]) ? trim($data[4]) : 0;
        $results[$i]['max'] = isset($data[6]) ? trim($data[6]) : 0;
        $i++;
      }
    }

    return $results;
  }
}
