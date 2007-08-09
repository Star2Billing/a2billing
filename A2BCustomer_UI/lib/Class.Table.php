<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* This is a SQL layer to handle Database standard queries :
* Listing, Edit, Delete, Add 
*
* PHP versions 4 and 5
*
* A2Billing -- Asterisk billing solution.
* Copyright (C) 2004-2007 : A2Billing
*
* See http://www.asterisk2billing.org for more information about
* the A2Billing project. 
* Please submit bug reports, patches, etc to <areski _atl_ gmail com>
*
* This software is released under the terms of the GNU Lesser General Public License v2.1
* A copy of which is available from http://www.gnu.org/copyleft/lesser.html
*
* @category   Database
* @package    Table
* @author     Arezqui Belaid <areski _atl_ gmail com>
* @copyright  2004-2007 The A2Billing Group
* @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version    CVS: $Id:$
* @since      File available since Release 1.0
*
*/




/**
* Class Table used to abstract Database queries and processing
*
* @category   Database
* @package    Table
* @author     Arezqui Belaid <areski _atl_ gmail com>
* @copyright  2004-2007 The A2Billing Group
* @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version    CVS: $Id:$
* @since      File available since Release 1.0
*/

class Table {
	
	var $fields = "*";
	var $table  = "";
	var $errstr = "";
	var $debug_st 			= 0;
	var $debug_stop_add 	= 0;
	var $debug_stop_update 	= 0;
	var $debug_stop_delete 	= 0;
	var $sp                 = "`"; //bound_caract
	var $start_message_debug = '<table width="100%" align="right" style="float : left;"><tr><td>QUERY:';
	var $end_message_debug = '</td></tr></table><br><br><br>';
	
    var $FK_TABLES ;
    var $FK_EDITION_CLAUSE ;
    // FALSE if you want to delete the dependent Records, TRUE if you want to update
    // Dependent Records to -1
    var $FK_DELETE_OR_UPDATE = true;
    var $FK_ID_VALUE = 0;

	
	/* CONSTRUCTOR */
	function Table ($table = null, $liste_fields = null,  $fk_Tables = null, $fk_Fields = null, $id_Value = null, $fk_del_upd = true)
	{
		$this -> table = $table;
		$this -> fields = $liste_fields;
		
		if ((count($fk_Tables) == count($fk_Fields)) && (count($fk_Fields) > 0)) {
			$this -> FK_TABLES = $fk_Tables;
			$this -> FK_EDITION_CLAUSE = $fk_Fields;
			$this -> FK_DELETE_OR_UPDATE = $fk_del_upd;
			$this -> FK_ID_VALUE = $id_Value;
		}
	}



	/* MODIFY PROPRIETY*/
	function Define_fields ($liste_fields )
	{
		$this -> fields = $liste_fields;
	}


	function Define_table ($table)
	{
		$this -> table = $table;
	}



	// If $select is not supplied then function check numrows
	// so expect a SELECT query.
	
	function SQLExec ($DBHandle, $QUERY, $select = 1)
	{
		if ($this -> debug_st) echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		$res = $DBHandle -> Execute($QUERY);
		
		if (!$res) {
			$this -> errstr = "Could not do a select count on the table '".$this -> table."'";
			return false;
		}
		
		if ($select) {
			$num = $res -> RecordCount();
			if ($num==0) {
				return Array();
			}
			
			for($i=0;$i<$num;$i++) {
				$row [] =$res -> fetchRow();
			}
			
			return ($row);
		}
		return true;
	}
	
	
	function Get_list ($DBHandle, $clause=null, $order=null, $sens=null, $field_order_letter=null, $letters = null, $limite=null, $current_record=NULL, $sql_group= NULL)
	{	
		$sql = 'SELECT '.$this -> fields.' FROM '.trim($this -> table);
		
		$sql_clause='';
		if ($clause!='') {
			$sql_clause=' WHERE '.$clause;
		}
		
		$sqlletters = "";
		if (!is_null ($letters) && (ereg("^[A-Za-z]+$", $letters)) && !is_null ($field_order_letter) && ($field_order_letter!='')) {
			$sql_letters= ' (".$field_order_letter." LIKE \''.strtolower($letters).'%\') ';
			
			if ($sql_clause != "") {
				$sql_clause .= " AND ";
			} else {
				$sql_clause .= " WHERE ";
			}
		}
		
		$sql_orderby = '';
		if (  !is_null ($order) && ($order!='') && !is_null ($sens) && ($sens!='') ) {
			$sql_orderby = " ORDER BY $sp".$order."$sp $sens";
		}
		
		if (!is_null ($limite) && (is_numeric($limite)) && !is_null ($current_record) && (is_numeric($current_record)) ) {
			if (DB_TYPE == "postgres") {
				$sql_limit = " LIMIT $limite OFFSET $current_record";
			} else {
				$sql_limit = " LIMIT $current_record,$limite";
			}
		}
		
		$QUERY = $sql.$sql_clause.$sql_group.$sql_orderby.$sql_limit;
		//print $QUERY;
		
		if ($this -> debug_st) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		}
		
		$res = $DBHandle -> Execute($QUERY);
		if (!$res) {
			$this -> errstr = "Could not do a select on the table '".$this -> table."'";
			return (false);
		}
		$num = $res -> RecordCount();
		if ($num==0) {
			return 0;
		}
		
		for( $i=0 ; $i<$num ; $i++ ) {
			$row [] =$res -> fetchRow();
		}
		return ($row);
	}
	
	
	function Table_count ($DBHandle, $clause=null, $id_Value = null)
	{
		$sql = 'SELECT count(*) FROM '.trim($this -> table);
		
		$sql_clause='';
		if ($clause!='') {
            if ($id_Value == null || $id_Value == '') {
			    $sql_clause=' WHERE '.$clause;
            } else {
                $sql_clause=' WHERE '.$clause." = ".$id_Value;
            }
        }
		
		$QUERY = $sql.$sql_clause;
		if ($this -> debug_st) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		}
		$res = $DBHandle -> Execute($QUERY);
		
		if (!$res) {
			$this -> errstr = "Could not do a select count on the table '".$this -> table."'";
			return (false);
		}
		$row =$res -> fetchRow();
		
		return ($row['0']);
	}
	
	
	function Add_table ($DBHandle, $value, $func_fields = null, $func_table = null, $id_name = null, $subquery = false) {
		
		if ($func_fields!="") {
			$this -> fields = $func_fields;
		}
		
		if ($func_table !="") {
			$this -> table = $func_table;
		}
		
		if ($subquery) {
			$QUERY = "INSERT INTO $sp".$this -> table."$sp (".$this -> fields.") (".trim ($value).")";	
		} else {
			$QUERY = "INSERT INTO $sp".$this -> table."$sp (".$this -> fields.") values (".trim ($value).")";
		}
		if ($this -> debug_st) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		}
		if ($this -> debug_stop_add) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug; exit(); 
		}
		$res = $DBHandle -> Execute($QUERY);

		if (!$res) {
			$this -> errstr = "Could not create a new instance in the table '".$this -> table."'";
			return (false);
		}
		
		// Fix that , make PEAR complaint
		if ($id_name!="") {
			
			if (DB_TYPE == "postgres") {
				
				$oid = $DBHandle -> Insert_ID();
				if ($oid <= 0 || $oid==''){ 
					return (true);
				}
				$sql = 'SELECT '.$id_name.' FROM '.$this -> table.' WHERE oid=\''.$oid.'\''; 
				$res = $DBHandle -> Execute($sql);
				if (!$res) {
					return (false);
				}
				$row [] =$res -> fetchRow();
				
				return $row[0][0]; 
				
			} else {
				$insertid = $DBHandle -> Insert_ID();
				if ($this -> debug_st) {
					echo "<br>mysql_insert_id :: $insertid";
				}
				return $insertid;
			}
		}
		
		return (true);
	}
	
	
	function Update_table ($DBHandle, $param_update, $clause, $func_table = null) {
		
		if ($param_update=="" || $clause=="") {
			echo "<br>Update parameters wasn't correctly defined.<br>Check the function call 'Update_table'.";
			return (false);
		}
		
		if ($func_table !="") {		
			$this -> table = $func_table;
		}
		
		$QUERY = "UPDATE $sp".$this -> table."$sp SET ".trim ($param_update)." WHERE ".trim ($clause);
		if ($this -> debug_st) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		}
		if ($this -> debug_stop_update) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug; exit();
		}
		$res = $DBHandle -> Execute($QUERY);
		if (!$res) {
			$this -> errstr = "Could not update the instances of the table '".$this -> table."'";
			return (false);
		}
		
		return (true);
	}
	
	
	
	function Delete_table ($DBHandle, $clause, $func_table = null)
	{
		if ($clause=="") {
			echo "<br>Delete parameters wasn't correctly defined.<br>Check the function call 'Update_table'.";
			return (false);
		}
		
		if ($func_table !="") {
			$this -> table = $func_table;
		}
		
        $countFK = count($this->FK_TABLES);
        for ($i = 0; $i < $countFK; $i++) {
            if ($this -> FK_DELETE_OR_UPDATE == false) {
            	$QUERY = "UPDATE $sp".$this -> FK_TABLES[$i]."$sp SET ".
							trim ($this -> FK_EDITION_CLAUSE[$i])." = -1 WHERE (".trim ($this -> FK_EDITION_CLAUSE[$i])." = ".$this -> FK_ID_VALUE." )";
            } else {
                $QUERY = "DELETE FROM $sp".$this -> FK_TABLES[$i].
							"$sp WHERE (".trim ($this -> FK_EDITION_CLAUSE[$i])." = ".$this -> FK_ID_VALUE." )";
            }
			if ($this -> debug_st) echo "<br>$QUERY";
            $res = $DBHandle -> Execute($QUERY);
        }
		
		$QUERY = "DELETE FROM $sp".$this -> table."$sp WHERE (".trim ($clause).")";
		if ($this -> debug_st) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		}
		if ($this -> debug_stop_delete) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug; exit();
		}
		$res = $DBHandle -> Execute($QUERY);
		if (!$res) {
			$this -> errstr = "Could not delete the instances of the table '".$this -> table."'";
			return (false);
		}
		return (true);
	}
	
	
    function Delete_Selected ($DBHandle, $clause=null, $order=null, $sens=null, $field_order_letter=null, $letters = null, $limite=null, $current_record=NULL, $sql_group= NULL)
	{
		$sql = 'DELETE FROM '.trim($this -> table);
		
		$sql_clause='';
		if ($clause!='') {
			$sql_clause=' WHERE '.$clause;
		}
		
		$sqlletters = "";
		if (!is_null ($letters) && (ereg("^[A-Za-z]+$", $letters)) && !is_null ($field_order_letter) && ($field_order_letter!='') ) {
			$sql_letters= ' (".$field_order_letter." LIKE \''.strtolower($letters).'%\') ';
			
			if ($sql_clause != "") {
				$sql_clause .= " AND ";
			} else {
				$sql_clause .= " WHERE ";
			}
		}
		
        $QUERY = $sql.$sql_clause;
		if ($this -> debug_st) {
			echo $this->start_message_debug.$QUERY.$this->end_message_debug;
		}
        $res = $DBHandle -> Execute($QUERY);
		if (!$res) {
			$this -> errstr = "Could not do a select on the table '".$this -> table."'";
			return (false);
		}
		return (true);
	}

};

?>
