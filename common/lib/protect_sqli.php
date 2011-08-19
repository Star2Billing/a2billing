<?php


function array_map_recursive( $func, $arr ) {
    $newArr = array();
    foreach( $arr as $key => $value ) {
        $newArr[ $key ] = ( is_array( $value ) ? array_map_recursive( $func, $value ) : $func( $value ) );
    }
    return $newArr;
}

// Clean up POST, GET, and COOKIES vars.
if (!get_magic_quotes_gpc())
{
    $_POST = array_map_recursive('stripslashes',$_POST);
    $_GET  = array_map_recursive('stripslashes', $_GET);
    $_COOKIE  = array_map_recursive('stripslashes', $_COOKIE);
}

if ( function_exists('mysql_real_escape_string'))
{
    $_POST = array_map_recursive('mysql_real_escape_string',$_POST);
    $_GET  = array_map_recursive('mysql_real_escape_string', $_GET);
    $_COOKIE  = array_map_recursive('mysql_real_escape_string', $_COOKIE);
}
else
{
    $_POST = array_map_recursive('addslashes',$_POST);
    $_GET  = array_map_recursive('addslashes', $_GET);
    $_COOKIE  = array_map_recursive('addslashes', $_COOKIE);
}




