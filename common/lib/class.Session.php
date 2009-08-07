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



// Create the trailing request and form session data
$session = "lang=".urlencode ($lang)."&login=".urlencode ($login);
$session_form = '<input type="Hidden" name="lang" value="'.$lang.'">
<input type="Hidden" name="login" value="'.$login.'">'."\n";

// Load the caching module
include_once (LIBDIR."module.cache.php");
	// Override caching ?
	$module_cache_override = "yes";


function db_connect () {
	global $link;
	if (! $link = pg_connect ("host=".PG_HOST." port=".PG_PORT.
		" user=".PG_USER." password=".PG_PASS." dbname=".PG_DBNAME)) exit;
}

function GM_db_connect () {
	global $GM_link;
	if (! $GM_link = pg_connect ("host=".GM_PG_HOST." port=".GM_PG_PORT.
		" user=".GM_PG_USER." password=".GM_PG_PASS." dbname=".GM_PG_DBNAME)) exit;
}


function db_close () {
	global $link;
	pg_close ($link);
}


function GM_db_close () {
	global $GM_link;
	pg_close ($GM_link);
}

function alert ($errstr) {
	if ($errstr != "") {
		echo "<script type=\"text/javascript\">\n<!--\n";
		echo "document.frames.top.menu.location.reload();\nalert ('";
		echo $errstr;
		echo "');\n//-->\n</script>\n";
	}
}

?>
