<?php
//
// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// $Id: class.mbm5.inc.php,v 1.7 2007/02/18 19:11:31 bigmichi1 Exp $

class mbinfo
{
    public $buf_label;
    public $buf_value;

    public function mbinfo()
    {
        $buffer = rfts( APP_ROOT . "/data/MBM5.csv" );
        if ( strpos( $buffer, ";") === false ) {
            $delim = ",";
        } else {
            $delim = ";";
        }
        $buffer = preg_split("/\n/", $buffer );
        $this->buf_label = preg_split("/".$delim."/", $buffer[0] );
        $this->buf_value = preg_split("/".$delim."/", $buffer[1] );
    }

    public function temperature()
    {
        $results = array();
        $intCount = 0;

        for ($intPosi = 3; $intPosi < 6; $intPosi++) {
            $results[$intCount]['label'] = $this->buf_label[$intPosi];
            $results[$intCount]['value'] = $this->buf_value[$intPosi];
            $results[$intCount]['limit'] = '70.0';
            $intCount++;
        }

        return $results;
    }

    public function fans()
    {
        $results = array();
        $intCount = 0;

        for ($intPosi = 13; $intPosi < 16; $intPosi++) {
            $results[$intCount]['label'] = $this->buf_label[$intPosi];
            $results[$intCount]['value'] = $this->buf_value[$intPosi];
            $results[$intCount]['min'] = '3000';
            $intCount++;
        }

        return $results;
    }

    public function voltage()
    {
        $results = array();
        $intCount = 0;

        for ($intPosi = 6; $intPosi < 13; $intPosi++) {
            $results[$intCount]['label'] = $this->buf_label[$intPosi];
            $results[$intCount]['value'] = $this->buf_value[$intPosi];
            $results[$intCount]['min'] = '0.00';
            $results[$intCount]['max'] = '0.00';
            $intCount++;
        }

        return $results;
    }
}
