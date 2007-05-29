<?php

// Load the language module

//---if ($lang != "") $module_i18n_language = $lang;
//---include_once (LIBDIR."module.i18n.php");

	// Set the language in $lang var
//---	$lang = $module_i18n_language;

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