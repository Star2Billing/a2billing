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

function smarty_function_mylang($params, &$smarty )
{  global $db, $config;
   $mainkey = $params['mkey'];
   $subkey = $params['skey'];
   $escape = $params['escape'];
   $optlang= $_SESSION['opt_lang'];
   if ($subkey != '') {
	   $y = $db->getOne('select descr from ! where lang=? and mainkey= ? and subkey=?', array(LANGUAGE_TABLE, $optlang, $mainkey, $subkey));
   } else {
	   $y = $db->getOne('select descr from ! where lang=? and mainkey= ? ', array(LANGUAGE_TABLE, $optlang, $mainkey));
   }
   if (!$y) {
	   if ($subkey != '') {
		   $y = $db->getOne('select descr from ! where lang=? and mainkey= ? and subkey=?', array(LANGUAGE_TABLE, 'english', $mainkey, $subkey));
	   } else {
		   $y = $db->getOne('select descr from ! where lang=? and mainkey= ? ', array(LANGUAGE_TABLE, 'english', $mainkey));
	   }
   }
   $y = str_replace('SITENAME', $config['site_name'],$y);

   $y = str_replace('DATE_FORMAT', get_lang('DATE_FORMAT'),$y);
   $y = str_replace('DATE_TIME_FORMAT', get_lang('DATE_TIME_FORMAT'),$y);
   $y = str_replace('DISPLAY_DATE_FORMAT', get_lang('DISPLAY_DATE_FORMAT'),$y);

   if ($escape == 'url') {
      return urlencode(stripslashes(html_entity_decode($y)));
   } else {
	   return stripslashes(html_entity_decode($y));
   }
}

/* vim: set expandtab: */

?>
