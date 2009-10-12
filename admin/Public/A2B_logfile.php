<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,   
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 * 
 * @copyright   Copyright (C) 2004-2009 - Star2billing S.L. 
 * @author      Belaid Arezqui <areski@gmail.com>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 * @package     A2Billing
 *
 * Software License Agreement (GNU Affero General Public License)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * 
**/


include ("../lib/admin.defines.php");
include ("../lib/admin.module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/admin.smarty.php");

if (!has_rights(ACX_MAINTENANCE)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

check_demo_mode();

getpost_ifset(array (
	'nb',
	'view_log',
	'filter'
));

// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_logfile;
?>
<br>
<center>
<?php


function array2drop_down($name, $currentvalue, $arr_value) {
	echo '<SELECT name="' . $name . '" class="form_enter">';
	if (is_array($arr_value) && count($arr_value) >= 1) {
		foreach ($arr_value as $ind => $value) {
			if ($ind != $currentvalue) {
				echo '<option value="' . $ind . '">' . $value . '</option>';
			} else {
				echo '<option value="' . $ind . '" selected="selected">' . $value . '</option>';
			}
		}
	}
	echo '</SELECT>';
}

$directory = '/var/log/asterisk/';
$d = dir($directory);

while (false !== ($entry = $d->read())) {
	if (is_file($directory . $entry) && $entry != '.' && $entry != '..')
		$arr_log[] = $directory . $entry;
}
$d->close();

foreach ($A2B->config["log-files"] as $log_file) {
	if (strlen(trim($log_file)) > 1) {
		$arr_log[] = $log_file;
	}
}
sort($arr_log);

$arr_nb = array (
	25 => 25,
	50 => 50,
	100 => 100,
	250 => 250,
	500 => 500,
	1000 => 1000,
	2500 => 2500
);
$nb = $nb ? $nb : 50;

?>

<form method="get">
<?php echo gettext("Browse log file")?>&nbsp; : <?php echo array2drop_down('view_log', $view_log, $arr_log)?> - 
<?php echo array2drop_down('nb', $nb, $arr_nb)?>

<?php echo gettext("Filter")?> : <input class="form_enter" name="filter" size="20" maxlength="30" value="<?php echo $filter; ?>">

<input class="form_enter" style="border: 2px outset rgb(204, 51, 0);" value=" Submit Query " type="submit">
</form>
<hr/>
</center>
<?php

if(isset($view_log)) {
	$f = $arr_log[$view_log];
	$arr = stat($f);
	echo '<title>'.$f.'</title>';
	echo '<font size="3"><pre>';
	//echo '<a href="view-source:'.WEBROOT.'/log/'.$f.'" target="_new">'.$f.'</a> ['.compute_size($arr['size']).'] last modified: '.date('r', $arr['mtime'])."\n\n";
	echo '<b><a href="view-source:'.WEBROOT.'/log/'.$f.'" target="_new">'.$f.'</a> ['.($arr['size']).'] last modified: '.date('r', $arr['mtime'])."</b>\n\n";

	$arr = file($f);
	$arr = array_reverse($arr);
	$i = 0;
	foreach($arr as $k=>$v) {
		$v = trim($v);
		if(!empty($v)) {
			$i++;			
			if (strlen($filter)>0) {
				$pos1 = stripos($v, $filter);
				if ($pos1 !== false) {
					$arr_tmp[] = $v;
				}
			} else {
				$arr_tmp[] = $v;
			}			
			//echo $v."\n";
		}
		if($i>=$nb) break;
	}
	$arr_tmp = array_reverse($arr_tmp);
	foreach($arr_tmp as $v)
		echo $v."\n";
	
	echo '</pre></font>';
}


// #### FOOTER SECTION
$smarty->display('footer.tpl');

