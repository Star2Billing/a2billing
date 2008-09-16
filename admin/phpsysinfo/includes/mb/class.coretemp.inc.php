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
// $Id: class.coretemp.inc.php,v 1.2 2008/06/01 15:52:51 bigmichi1 Exp $
//
// This class was created by William Johansson ( radar at radhuset dot org )
class mbinfo {
  function temperature() {
    $results = array();
    $smp = 1;
    execute_program('sysctl', '-n kern.smp.cpus', $smp);
    for ($i = 0;$i < $smp;$i++) {
      $temp = 0;
      if (execute_program('sysctl', '-n dev.cpu.' . $i . '.temperature', $temp)) {
        $results[$i]['label'] = "CPU " . ($i+1);
        $results[$i]['value'] = $temp;
        $results[$i]['limit'] = '70.0';
        $results[$i]['percent'] = $results[$i]['value']*100/$results[$i]['limit'];
      }
    }
    return $results;
  }
  function fans() {
    return null;
  }
  function voltage() {
    return null;
  }
}
?>
