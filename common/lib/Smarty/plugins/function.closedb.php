<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {closedb} function plugin
 *
 * Type:     function<br>
 * Name:     Get Lang<br>
 * Date:     March 2, 2006<br>
 * Purpose:  Take languave specific texts from database to display
 * @link     To be attached with osdate package and topied to Smarty/plugins directory
 * @author   Vijay Nair <vijay@nairvijay.com>
 * @version  1.0
 * @param    Text_to_check Text for Language Check
 * @return   string 
 */
 
function smarty_function_closedb($params, &$smarty )
{  global $db;
	$db->disconnect();
}

/* vim: set expandtab: */

?>
