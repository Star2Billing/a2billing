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

include_once ("../../lib/admin.defines.php");
include_once ("../../lib/admin.module.access.php");

if (!has_rights(ACX_DASHBOARD)) {
	Header("HTTP/1.0 401 Unauthorized");
	Header("Location: PP_error.php?c=accessdenied");
	die();
}

$DEBUG_MODULE = FALSE;
getpost_ifset(array (
	'type','view_type'
));


$news = array();
$newsWEB = @file_get_contents('http://www.asterisk2billing.org/news.php');
if($newsWEB != null){
	$newsWEB = (explode('<br />',nl2br($newsWEB)));
	foreach($newsWEB as $value){
			$value = explode('|',$value);
			if(strlen($value[0]) > 1)
				$news[] = $value;
	}
}

$all = $news;
$today = array();
$month = array();

foreach($news as $key => $new){
	$link = substr($new[0], strpos($new[0], 'http://')-1);
	$link = substr($link, strpos(strrev($link), '/') );
	$news[$key][0] = str_replace($link, "<a href={$link}>$link</a>", $new[0]);

	$part[$key] = split ('[0-9]{1,2}[a-z]{2,3}[,]', $new[1]);
	$tmp = split ('AM|PM', $part[$key][0]);

	$news[$key]['hour'] = trim($tmp[0]);
	$news[$key]['meridiem'] = (ereg('AM',$part[$key][0])? 'AM':'PM');
	$news[$key]['month'] = trim($tmp[1]);
	$news[$key]['day'] = substr($new[1],(strlen($part[$key][0])));
	$news[$key]['day'] = substr($new[1],(strlen($part[$key][0])), strpos($news[$key]['day'], ',')-2);
	$news[$key]['year'] = trim($part[$key][1]);
	
	if($news[$key]['year'] == date('Y') && $news[$key]['month'] == date('M'))
	{
		$month[] = $news[$key];
		
		if($news[$key]['month'] == date('M'))
		{
			$today[] = $news[$key];
		}
	}
}

?>
<center><b><?php echo gettext("News A2Billing"); ?></b></center><br/>
<center><?php echo gettext("All"); ?> &nbsp;<input id="view_news" type="radio"  name="view_news" value="day" onChange="ajaxFunction('all');" checked> &nbsp;
<?php echo gettext("Today"); ?> &nbsp;<input id="view_news" type="radio" name="view_news" value="today" onChange="ajaxFunction('today');">
<?php echo gettext("Month"); ?> &nbsp;<input id="view_news" type="radio" name="view_news" value="month" onChange="ajaxFunction('month');"></center> <br/>


<div id="all">
<?php foreach($news as $new){ ?>
	--<?php echo($new[1]); ?><br />
	&emsp;&emsp;<?php echo($new[0]); ?><br />
<? } ?>
</div>

<div id="today" class="visibility:hidden">
<?php foreach($today as $new){ ?>
	--<?php echo($new[1]); ?><br />
	&emsp;&emsp;<?php echo($new[0]); ?><br />
<? } ?>
</div>

<div id="month" class="visibility:hidden">
<?php foreach($month as $new){ ?>
	--<?php echo($new[1]); ?><br />
	&emsp;&emsp;<?php echo($new[0]); ?><br />
<? } ?>
</div>


<script id="source" language="javascript" type="text/javascript">
function ajaxFunction(view){
	var ajaxRequest;  // The variable that makes Ajax possible!
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Please update your browser!");
				return false;
			}
		}
	}

	switch (view)
	{
		case 'all':
		  document.getElementById('all').style.visibility = "visible";
		  document.getElementById('today').style.visibility = "hidden";
		  document.getElementById('month').style.visibility = "hidden";
		  break;
		case 'today':
		  document.getElementById('all').style.visibility = "hidden";
		  document.getElementById('today').style.visibility = "visible";
		  document.getElementById('month').style.visibility = "hidden";
		  break;
		case 'month':
		  document.getElementById('all').style.visibility = "hidden";
		  document.getElementById('today').style.visibility = "hidden";
		  document.getElementById('month').style.visibility = "visible";
		  break;
		default:
		  document.getElementById('all').style.visibility = "visible";
		  document.getElementById('today').style.visibility = "hidden";
		  document.getElementById('month').style.visibility = "hidden";
		break;
	}
	
	ajaxRequest.send(null);
}
</script>
