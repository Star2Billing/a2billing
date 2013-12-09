<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This file is part of A2Billing (http://www.a2billing.net/)
 *
 * A2Billing, Commercial Open Source Telecom Billing platform,
 * powered by Star2billing S.L. <http://www.star2billing.com/>
 *
 * @copyright   Copyright (C) 2004-2012 - Star2billing S.L.
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

include_once '../../lib/admin.defines.php';
include_once '../../lib/admin.module.access.php';

if (!has_rights(ACX_DASHBOARD)) {
    Header("HTTP/1.0 401 Unauthorized");
    Header("Location: PP_error.php?c=accessdenied");
    die();
}

$DEBUG_MODULE = FALSE;
getpost_ifset(array ('type','view_type'));

$news = array();

// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: foo=bar\r\n"
  )
);

$context = stream_context_create($opts);
$new_url = 'http://www.asterisk2billing.org/news_v2.php';
// Open the file using the HTTP headers set above
$news_content = file_get_contents($new_url, false, $context);

if ($news_content != null) {
    $news_content = (explode('<br />',nl2br($news_content)));
    foreach ($news_content as $value) {
            $value = explode('|',$value);
            if(strlen($value[0]) > 1)
                $news[] = $value;
    }
}

foreach ($news as $key => $new) {
    $link = substr($new[0], strpos($new[0], 'http://')-1);
    $link = strrev($link);
    $link = strrev(substr($link, strpos($link, ' ') ));
    $news[$key][0] = str_replace($link, "<a href={$link}>$link</a>", $new[0]);
}

$i = 0;
foreach ($news as $new) {
    $i++;
    if (($i % 2) == 0) {
        echo '<div class="dashbox_news">';
    } else {
        echo '<div class="dashbox_news2">';
    }
    echo $new[0]."<br/> <div align=\"right\"><i>".$new[1]."</i></div><br/>";
    echo "</div>";
    if ($i > 5) {
        break;
    }
}
