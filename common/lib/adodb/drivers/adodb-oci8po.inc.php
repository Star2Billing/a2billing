<?php
/*
v4.991 16 Oct 2008  (c) 2000-2008 John Lim. All rights reserved.
  Released under both BSD license and Lesser GPL library license.
  Whenever there is any discrepancy between the two licenses,
  the BSD license will take precedence.

  Latest version is available at http://adodb.sourceforge.net

  Portable version of oci8 driver, to make it more similar to other database drivers.
  The main differences are

   1. that the OCI_ASSOC names are in lowercase instead of uppercase.
   2. bind variables are mapped using ? instead of :<bindvar>

   Should some emulation of RecordCount() be implemented?

*/

// security - hide paths
if (!defined('ADODB_DIR')) die();

include_once(ADODB_DIR.'/drivers/adodb-oci8.inc.php');

class ADODB_oci8po extends ADODB_oci8
{
    public $databaseType = 'oci8po';
    public $dataProvider = 'oci8';
    public $metaColumnsSQL = "select lower(cname),coltype,width, SCALE, PRECISION, NULLS, DEFAULTVAL from col where tname='%s' order by colno"; //changed by smondino@users.sourceforge. net
    public $metaTablesSQL = "select lower(table_name),table_type from cat where table_type in ('TABLE','VIEW')";

    public function ADODB_oci8po()
    {
        $this->_hasOCIFetchStatement = ADODB_PHPVER >= 0x4200;
        # oci8po does not support adodb extension: adodb_movenext()
    }

    public function Param($name)
    {
        return '?';
    }

    public function Prepare($sql,$cursor=false)
    {
        $sqlarr = explode('?',$sql);
        $sql = $sqlarr[0];
        for ($i = 1, $max = sizeof($sqlarr); $i < $max; $i++) {
            $sql .=  ':'.($i-1) . $sqlarr[$i];
        }

        return ADODB_oci8::Prepare($sql,$cursor);
    }

    // emulate handling of parameters ? ?, replacing with :bind0 :bind1
    public function _query($sql,$inputarr)
    {
        if (is_array($inputarr)) {
            $i = 0;
            if (is_array($sql)) {
                foreach ($inputarr as $v) {
                    $arr['bind'.$i++] = $v;
                }
            } else {
                $sqlarr = explode('?',$sql);
                $sql = $sqlarr[0];
                foreach ($inputarr as $k => $v) {
                    $sql .=  ":$k" . $sqlarr[++$i];
                }
            }
        }

        return ADODB_oci8::_query($sql,$inputarr);
    }
}

/*--------------------------------------------------------------------------------------
         Class Name: Recordset
--------------------------------------------------------------------------------------*/

class ADORecordset_oci8po extends ADORecordset_oci8
{
    public $databaseType = 'oci8po';

    public function ADORecordset_oci8po($queryID,$mode=false)
    {
        $this->ADORecordset_oci8($queryID,$mode);
    }

    public function Fields($colname)
    {
        if ($this->fetchMode & OCI_ASSOC) return $this->fields[$colname];

        if (!$this->bind) {
            $this->bind = array();
            for ($i=0; $i < $this->_numOfFields; $i++) {
                $o = $this->FetchField($i);
                $this->bind[strtoupper($o->name)] = $i;
            }
        }

         return $this->fields[$this->bind[strtoupper($colname)]];
    }

    // lowercase field names...
    function &_FetchField($fieldOffset = -1)
    {
         $fld = new ADOFieldObject;
          $fieldOffset += 1;
         $fld->name = strtolower(OCIcolumnname($this->_queryID, $fieldOffset));
         $fld->type = OCIcolumntype($this->_queryID, $fieldOffset);
         $fld->max_length = OCIcolumnsize($this->_queryID, $fieldOffset);
         if ($fld->type == 'NUMBER') {
             //$p = OCIColumnPrecision($this->_queryID, $fieldOffset);
            $sc = OCIColumnScale($this->_queryID, $fieldOffset);
            if ($sc == 0) $fld->type = 'INT';
         }

         return $fld;
    }
    /*
    public function MoveNext()
    {
        if (@OCIfetchinto($this->_queryID,$this->fields,$this->fetchMode)) {
            $this->_currentRow += 1;

            return true;
        }
        if (!$this->EOF) {
            $this->_currentRow += 1;
            $this->EOF = true;
        }

        return false;
    }*/

    // 10% speedup to move MoveNext to child class
    public function MoveNext()
    {
        if (@OCIfetchinto($this->_queryID,$this->fields,$this->fetchMode)) {
        global $ADODB_ANSI_PADDING_OFF;
            $this->_currentRow++;

            if ($this->fetchMode & OCI_ASSOC) $this->_updatefields();
            if (!empty($ADODB_ANSI_PADDING_OFF)) {
                foreach ($this->fields as $k => $v) {
                    if (is_string($v)) $this->fields[$k] = rtrim($v);
                }
            }

            return true;
        }
        if (!$this->EOF) {
            $this->EOF = true;
            $this->_currentRow++;
        }

        return false;
    }

    /* Optimize SelectLimit() by using OCIFetch() instead of OCIFetchInto() */
    function &GetArrayLimit($nrows,$offset=-1)
    {
        if ($offset <= 0) {
            $arr = $this->GetArray($nrows);

            return $arr;
        }
        for ($i=1; $i < $offset; $i++)
            if (!@OCIFetch($this->_queryID)) {
                $arr = array();

                return $arr;
            }
        if (!@OCIfetchinto($this->_queryID,$this->fields,$this->fetchMode)) {
            $arr = array();

            return $arr;
        }
        if ($this->fetchMode & OCI_ASSOC) $this->_updatefields();
        $results = array();
        $cnt = 0;
        while (!$this->EOF && $nrows != $cnt) {
            $results[$cnt++] = $this->fields;
            $this->MoveNext();
        }

        return $results;
    }

    // Create associative array
    public function _updatefields()
    {
        if (ADODB_ASSOC_CASE == 2) return; // native

        $arr = array();
        $lowercase = (ADODB_ASSOC_CASE == 0);

        foreach ($this->fields as $k => $v) {
            if (is_integer($k)) $arr[$k] = $v;
            else {
                if ($lowercase)
                    $arr[strtolower($k)] = $v;
                else
                    $arr[strtoupper($k)] = $v;
            }
        }
        $this->fields = $arr;
    }

    public function _fetch()
    {
        $ret = @OCIfetchinto($this->_queryID,$this->fields,$this->fetchMode);
        if ($ret) {
        global $ADODB_ANSI_PADDING_OFF;

                if ($this->fetchMode & OCI_ASSOC) $this->_updatefields();
                if (!empty($ADODB_ANSI_PADDING_OFF)) {
                    foreach ($this->fields as $k => $v) {
                        if (is_string($v)) $this->fields[$k] = rtrim($v);
                    }
                }
        }

        return $ret;
    }

}
