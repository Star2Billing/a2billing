<?php

function array_map_recursive( $func, $arr )
{
    $newArr = array();
    if (!$arr) {
        return $newArr;
    }
    foreach ($arr as $key => $value) {
        $newArr[ $key ] = ( is_array( $value ) ? array_map_recursive( $func, $value ) : $func( $value ) );
    }

    return $newArr;
}

function recursive_filter($arr)
{
    $newArr = array();
    foreach ($arr as $key => $value) {
        if (is_array($value)) {
            $newArr[ $key ] = recursive_filter( $value );
        } else {
            if (filter_var($value, FILTER_SANITIZE_STRING) !== false) {
                $newArr[ $key ] = $value;
            } else {
                $newArr[ $key ] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        }
    }

    return $newArr;
}

// Clean up POST, GET, and COOKIES vars.
if (!get_magic_quotes_gpc()) {
    $_POST = array_map_recursive('stripslashes',$_POST);
    $_GET  = array_map_recursive('stripslashes', $_GET);
    $_COOKIE  = array_map_recursive('stripslashes', $_COOKIE);
}

if (!function_exists('mysql_real_escape_string')) {
    // $_POST = array_map_recursive('mysql_real_escape_string',$_POST);
    // $_GET  = array_map_recursive('mysql_real_escape_string', $_GET);
    // $_COOKIE  = array_map_recursive('mysql_real_escape_string', $_COOKIE);
} else {
    $_POST = array_map_recursive('addslashes',$_POST);
    $_GET  = array_map_recursive('addslashes', $_GET);
    $_COOKIE  = array_map_recursive('addslashes', $_COOKIE);
}

$_POST = recursive_filter($_POST);
$_GET  = recursive_filter($_GET);
$_COOKIE  = recursive_filter($_COOKIE);
