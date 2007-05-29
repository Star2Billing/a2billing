<?php
include ("../lib/defines.php");
include ("../lib/module.access.php");
include ("../lib/Form/Class.FormHandler.inc.php");
include ("../lib/smarty.php");


if (! has_rights (ACX_ADMINISTRATOR)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

getpost_ifset(array('nb', 'view_log', 'filter'));


/***********************************************************************************/


// #### HEADER SECTION
$smarty->display('main.tpl');

// #### HELP SECTION
echo $CC_help_logfile;
?>
<br>

<center>
<?php

function array2drop_down($name, $currentvalue, $arr_value){
	echo '<SELECT name="'.$name.'" class="form_enter">';
		if (is_array($arr_value) && count($arr_value)>=1){
			foreach ($arr_value as $ind => $value){
				if ($ind!=$currentvalue){
					echo '<option value="'.$ind.'">'.$value.'</option>';
				}else{
					echo '<option value="'.$ind.'" selected="selected">'.$value.'</option>';
				}
			}
		}
	echo '</SELECT>';
}

/*
$directory = '/var/log/asterisk/agi/';
$d = dir($directory);

while(false!==($entry=$d->read()))
{
	if(is_file($directory.$entry) && $entry!='.' && $entry!='..')
		$arr_log[] = $directory.$entry;
}
$d->close();
sort($arr_log);
*/

//$arr_log[0] = '/var/log/asterisk/a2billing-daemon-callback.log';
//$arr_log[1] = '/var/log/asterisk/a2billing-webcallback.log';


//$directory = '/var/log/asterisk/';
$directory = '/var/log/asterisk/';
$d = dir($directory);

while(false!==($entry=$d->read()))
{
	if(is_file($directory.$entry) && $entry!='.' && $entry!='..')
		$arr_log[] = $directory.$entry;
}
$d->close();


foreach($A2B->config["log-files"] as $log_file){
	if (strlen(trim($log_file))>1){
		$arr_log[] = $log_file;
	}	
}
sort($arr_log);

$arr_nb = array(25=>25, 50=>50, 100=>100, 250=>250, 500=>500, 1000=>1000, 2500=>2500);
$nb = $nb?$nb:50;
?>

<form method="get">
<?php echo gettext("Browse log file")?>&nbsp; : <?=array2drop_down('view_log', $view_log, $arr_log)?> - 
<?=array2drop_down('nb', $nb, $arr_nb)?>

<?php echo gettext("Filter")?> : <input class="form_enter" name="filter" size="20" maxlength="30" value="<?php echo $filter; ?>">

<input class="form_enter" style="border: 2px outset rgb(204, 51, 0);" value=" Submit Query " type="submit">
</form>
<hr/>
</center>
<?php
echo $_GET['view_log']."<hr>";

if(isset($_GET['view_log']))
{
	$f = $arr_log[$_GET['view_log']];
	$arr = stat($f);
	echo '<title>'.$f.'</title>';
	echo '<font size="3"><pre>';
	//echo '<a href="view-source:'.WEBROOT.'/log/'.$f.'" target="_new">'.$f.'</a> ['.compute_size($arr['size']).'] last modified: '.date('r', $arr['mtime'])."\n\n";
	echo '<b><a href="view-source:'.WEBROOT.'/log/'.$f.'" target="_new">'.$f.'</a> ['.($arr['size']).'] last modified: '.date('r', $arr['mtime'])."</b>\n\n";

	$arr = file($f);
	$arr = array_reverse($arr);
	$i = 0;
	foreach($arr as $k=>$v)
	{
		$v = trim($v);
		if(!empty($v))
		{
			$i++;			
			if (strlen($filter)>0){
				$pos1 = stripos($v, $filter);
				if ($pos1 !== false) {
					$arr_tmp[] = $v;
				}
			}else{
				$arr_tmp[] = $v;
			}			
			//echo $v."\n";
		}
		if($i>=$nb) break;
	}
	$arr_tmp = array_reverse($arr_tmp);
	foreach($arr_tmp as $v)
		echo $v."\n";
	//debug($arr_tmp);
	/*
	$fp = fopen($arr_log[$_GET['view_log']], 'r');
	while(!feof($fp))
	{
		$line = fgets($fp);
		$line = trim($line);
		if(!empty($line)) echo $line."\n";
		
	}
	fclose($fp);
	*/
	echo '</pre></font>';
}


// #### FOOTER SECTION
$smarty->display('footer.tpl');

?>
