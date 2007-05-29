<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {get_lang} function plugin
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

function smarty_function_get_cityname($params, &$smarty )
{
	global $db;
	$citycd = $params['city'];
	$statecd = $params['state'];
	$countrycd = $params['country'];
	$countycd = $params['county'];

	$sql = 'select name from ! where countrycode = ? and statecode = ? and code = ? ';

	if ($countycd != '') {
		$sql .= ' and countycode = '."'".$countycd."'";
	}

	$cityname = $db->getOne($sql, array( CITIES_TABLE, $countrycd, $statecd, $citycd ) );

	if ($cityname == '') $cityname = $citycd;

	return html_entity_decode($cityname);
}

/* vim: set expandtab: */

?>
