<?php

/*
v4.991 16 Oct 2008  (c) 2000-2008 John Lim (jlim#natsoft.com). All rights reserved.
  Released under both BSD license and Lesser GPL library license.
  Whenever there is any discrepancy between the two licenses,
  the BSD license will take precedence.
  Set tabs to 8.

*/

class ADODB_pdo_mssql extends ADODB_pdo
{
    public $hasTop = 'top';
    public $sysDate = 'convert(datetime,convert(char,GetDate(),102),102)';
    public $sysTimeStamp = 'GetDate()';

    public function _init($parentDriver)
    {

        $parentDriver->hasTransactions = false; ## <<< BUG IN PDO mssql driver
        $parentDriver->_bindInputArray = false;
        $parentDriver->hasInsertID = true;
    }

    public function ServerInfo()
    {
        return ADOConnection::ServerInfo();
    }

    public function SelectLimit($sql,$nrows=-1,$offset=-1,$inputarr=false,$secs2cache=0)
    {
        $ret = ADOConnection::SelectLimit($sql,$nrows,$offset,$inputarr,$secs2cache);

        return $ret;
    }

    public function SetTransactionMode( $transaction_mode )
    {
        $this->_transmode  = $transaction_mode;
        if (empty($transaction_mode)) {
            $this->Execute('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

            return;
        }
        if (!stristr($transaction_mode,'isolation')) $transaction_mode = 'ISOLATION LEVEL '.$transaction_mode;
        $this->Execute("SET TRANSACTION ".$transaction_mode);
    }

    public function MetaTables()
    {
        return false;
    }

    public function MetaColumns()
    {
        return false;
    }

}
