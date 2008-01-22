<?php

/**
 * @file
 * Functions that need to be loaded on every request.
 */

/**
 * Sets doc root
 */
function setARIRoot() {

  $found = 0;
  if (isset($_SERVER['PHP_SELF'])) {
    if ($_SERVER['PHP_SELF']!='') {
      $_SESSION['ARI_ROOT'] = $_SERVER['PHP_SELF'];
    }
  }
  
  if (!$found) {
    $_SESSION['ARI_ROOT'] = "index.php";
  }
}

/**
 * Return a arguments.
 *
 * @param $args
 *   The name of the array being acted upon.
 * @param $name
 *   The name of the variable to return.
 * @return
 *   The value of the variable.
 */
function getArgument($args, $name) {

  return isset($args[$name]) ? $args[$name] : '';
}

/*
 * Gets top level directory names 
 *
 * @param $path
 *   directory to search
 * @param $filter
 *   string to use as a filter to match files to return
 * @return $directories
 *   directories found
 */
function getDirectories($path,$filter) {

  $directories = array();

  if (is_dir($path)) {

    $dh = opendir($path);
    while (false!== ($item = readdir($dh))) {
      if($item!="." && $item!="..") {

        $path = fixPathSlash($path);
        $directory = $path;
        $directory = appendPath($directory,$item);

        if (is_dir($directory)) {

          $found = 0;
          if ($filter) {
            if (strpos($directory,$filter)) {
              $found = 1;
            }
          } else {
            $found = 1;
          }
          if ($found) {
            $directories[count($directories) + 1] = $directory;
          }
        }
      }
    } 
  }

  return $directories;
}

/*
 * Gets file names recursively 6 folders deep
 *
 * @param $path
 *   directory to search
 * @param $filter
 *   string to use as a filter to match files to return
 * @param $recursive_max
 *   max number of sub folders to search
 * @param $recursive_count
 *   current sub folder count
 * @return $files
 *   files found
 */
function getFiles($path,$filter,$recursive_max,$recursive_count) {

  $files = array();

  if (@is_dir($path) && @is_readable($path)) {
    $dh = opendir($path);
    while (false!== ($item = readdir($dh))) {
      if($item!="." && $item!="..") {

        $path = fixPathSlash($path);
        $msg_path = appendPath($path,$item);

        $fileCount++;
        if ($fileCount>3000) {
          $_SESSION['ari_error'] 
            .= _("To many files in $msg_path Not all files processed") . "<br>";
          return;
        }

        if ($recursive_count<$recursive_max && is_dir($msg_path)) {

          $dirCount++;
          if ($dirCount>10) {
            $_SESSION['ari_error'] 
              .= sprintf(_("To many directories in %s Not all files processed"),$msg_path) . "<br>";
            return;
          }

          $count = $recursive_count + 1;
          $path_files = getFiles($msg_path,$filter,$recursive_max,$count);
          $files = array_merge($files,$path_files);
        } 
        else {
          $found = 0;
          if ($filter) {
            if (strpos($msg_path,$filter)) {
              $found = 1;
            }
          } else {
            $found = 1;
          }
          if ($found) {
            $files[count($files) + 1] = $msg_path;
          }
        }
      }
    } 
  }

  return $files;
}

/* Utilities */

/**
 * Fixes the path for a trailing slash
 *
 * @param $path
 *   path to append
 * @return $ret
 *   path to returned
 */
function fixPathSlash($path) {

  $ret = $path;

  $slash = '';
  if (!preg_match('/\/$/',$path)) {
    $slash = '/';
  } 
  $ret .= $slash;

  return $ret; 
}

/**
 * Appends folder to end of path
 *
 * @param $path
 *   path to append
 * @param $folder
 *   folder to append to path
 * @return $ret
 *   path to returned
 */
function appendPath($path,$folder) {

  $ret = $path;

  $m = '';
  if (!preg_match('/\/$/',$path)) {
    $m = '/';
  } 
  $ret .= $m . $folder; 

  return $ret;
}

/**
 * Get Date format 
 *
 * @param $timestamp
 *   timestamp to be converted
 */
function getDateFormat($timestamp) {
  return date('Y-m-d', $timestamp);
}

/**
 * Get time format 
 *
 * @param $timestamp
 *   timestamp to be converted
 */
function getTimeFormat($timestamp) {   
  return date('G:i:s', $timestamp);
}

/* */

/**
 * Checks ARI dependencies
 */
function checkDependencies() {

  // check for PHP
  if (!version_compare(phpversion(), '4.3', '>=')) {
    echo _("ARI requires a version of PHP 4.3 or later");
    exit();
  }

  // check for PEAR
  $include_path = ini_get('include_path');
  $buf = split(':|,',$include_path);

  $found = 0;
  foreach ($buf as $path) {
    $path = fixPathSlash($path);
    $pear_check_path = $path . "DB.php";
    if (is_file($pear_check_path)) {
      $found = 1;
      break;
    }
  }

  if (!$found) {
    echo _("PHP PEAR must be installed.  Visit http://pear.php.net for help with installation.");
    exit();
  }
}

/**
 * Starts the session
 */
function startARISession() {

  if (!isset($_SESSION['ari_user']) ) {

    // start a new session for the user 
    ini_set('session.name', 'ARI');             // prevent session name clashes
    ini_set('session.gc_maxlifetime', '3900');  // make the session timeout a long time
    set_time_limit(360);
    session_start();
  }
}

/**
 * Bootstrap
 *
 * Loads critical variables needed for every page request
 *
 */
function bootstrap() {

  // set error reporting
  error_reporting (E_ALL & ~ E_NOTICE);  
}

/**
 * Set HTTP headers in preparation for a page response.
 *
 * TODO: Figure out caching
 */
function ariPageHeader() {

  bootstrap();
}

/**
 * Perform end-of-request tasks.
 *
 * This function sets the page cache if appropriate, and allows modules to
 * react to the closing of the page by calling hook_exit().
 */
function ariPageFooter() {

}

/**
 * Includes and run functions
 */

include_once("./includes/lang.php");
$language = new Language();
$language->set();

checkDependencies();
startARISession();
setARIRoot();

include_once("./includes/main.conf.php");
include_once("./version.php");
include_once("./includes/crypt.php"); 
include_once("./includes/login.php");


?>