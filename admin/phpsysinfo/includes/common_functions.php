<?php
/***************************************************************************
*   Copyright (C) 2008 by phpSysInfo - A PHP System Information Script    *
*   http://phpsysinfo.sourceforge.net/                                    *
*                                                                         *
*   This program is free software; you can redistribute it and/or modify  *
*   it under the terms of the GNU General Public License as published by  *
*   the Free Software Foundation; either version 2 of the License, or     *
*   (at your option) any later version.                                   *
*                                                                         *
*   This program is distributed in the hope that it will be useful,       *
*   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
*   GNU General Public License for more details.                          *
*                                                                         *
*   You should have received a copy of the GNU General Public License     *
*   along with this program; if not, write to the                         *
*   Free Software Foundation, Inc.,                                       *
*   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
***************************************************************************/
//
// $Id: common_functions.php,v 1.79 2008/06/05 18:57:51 bigmichi1 Exp $
//
// Version number
define('PSI_VERSION', '3.0-rc6');
// usefull during development
// error_reporting(E_ALL|E_NOTICE|E_STRICT);
// Find a system program.  Do path checking
function find_program($strProgram) {
  global $addpaths;
  $arrPath = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
  if (addPaths !== false) {
    $addpaths = explode(',', addPaths);
    $arrPath = array_merge($arrPath, $addpaths);
  }
  if (function_exists("is_executable")) {
    foreach($arrPath as $strPath) {
      $strProgrammpath = $strPath . "/" . $strProgram;
      if (is_executable($strProgrammpath)) {
        return $strProgrammpath;
      }
    }
  } else {
    return strpos($strProgram, '.exe');
  }
}
// Execute a system program. return a trim()'d result.
// does very crude pipe checking.  you need ' | ' for it to work
// ie $program = execute_program('netstat', '-anp | grep LIST');
// NOT $program = execute_program('netstat', '-anp|grep LIST');
function execute_program($strProgramname, $strArgs = '', &$strBuffer, $booErrorRep = true) {
  $error = Error::singleton();
  $strBuffer = '';
  $strError = '';
  $strProgram = find_program($strProgramname);
  if (!$strProgram) {
    if ($booErrorRep) {
      $error->addError('find_program(' . $strProgramname . ')', 'program not found on the machine');
    }
    return false;
  }
  // see if we've gotten a |, if we have we need to do patch checking on the cmd
  if ($strArgs) {
    $arrArgs = split(' ', $strArgs);
    for ($i = 0;$i < count($arrArgs);$i++) {
      if ($arrArgs[$i] == '|') {
        $strCmd = $arrArgs[$i+1];
        $strNewcmd = find_program($strCmd);
        $strArgs = ereg_replace("\| " . $strCmd, "| " . $strNewcmd, $strArgs);
      }
    }
  }
  // no proc_open() below php 4.3
  $descriptorspec = array(0 => array("pipe", "r"), // stdin is a pipe that the child will read from
  1 => array("pipe", "w"), // stdout is a pipe that the child will write to
  2 => array("pipe", "w") // stderr is a pipe that the child will write to
  );
  $process = proc_open($strProgram . " " . $strArgs, $descriptorspec, $pipes);
  if (is_resource($process)) {
    while (!feof($pipes[1])) {
      $strBuffer.= fgets($pipes[1], 1024);
    }
    fclose($pipes[1]);
    while (!feof($pipes[2])) {
      $strError.= fgets($pipes[2], 1024);
    }
    fclose($pipes[2]);
  }
  $return_value = proc_close($process);
  $strError = trim($strError);
  $strBuffer = trim($strBuffer);
  //if( ! empty( $strError ) || $return_value <> 0 ) {
  if (!empty($strError)) {
    if ($booErrorRep) {
      $error->addError($strProgram, $strError . "\nReturn value: " . $return_value);
    }
    return false;
  }
  return true;
}
// Check if a string exist in the global $hide_mounts.
// Return true if this is the case.
function hide_mount($strMount) {
  global $hide_mounts;
  if (isset($hide_mounts) && is_array($hide_mounts) && in_array($strMount, $hide_mounts)) {
    return true;
  } else {
    return false;
  }
}
// Check if a string exist in the global $hide_fstypes.
// Return true if this is the case.
function hide_fstype($strFSType) {
  global $hide_fstypes;
  if (isset($hide_fstypes) && is_array($hide_fstypes) && in_array($strFSType, $hide_fstypes)) {
    return true;
  } else {
    return false;
  }
}
// find duplicate entrys and count them, show this value befor the duplicated name
function finddups($arrInput) {
  $arrResult = array();
  if (is_array($arrInput)) {
    $arrBuffer = array_count_values($arrInput);
    foreach($arrBuffer as $strKey => $intValue) {
      if ($intValue > 1) {
        $arrResult[] = "(" . $intValue . "x) " . $strKey;
      } else {
        $arrResult[] = $strKey;
      }
    }
  }
  return $arrResult;
}
function rfts($strFileName, &$strRet, $intLines = 0, $intBytes = 4096, $booErrorRep = true) {
  $error = Error::singleton();
  $strFile = "";
  $intCurLine = 1;
  if (file_exists($strFileName)) {
    if ($fd = fopen($strFileName, 'r')) {
      while (!feof($fd)) {
        $strFile.= fgets($fd, $intBytes);
        if ($intLines <= $intCurLine && $intLines != 0) {
          break;
        } else {
          $intCurLine++;
        }
      }
      fclose($fd);
      $strRet = $strFile;
    } else {
      if ($booErrorRep) {
        $error->addError('fopen(' . $strFileName . ')', 'file can not read by phpsysinfo');
      }
      return false;
    }
  } else {
    if ($booErrorRep) {
      $error->addError('file_exists(' . $strFileName . ')', 'the file does not exist on your machine');
    }
    return false;
  }
  return true;
}
function gdc($strPath, $booErrorRep = true) {
  $error = Error::singleton();
  $arrDirectoryContent = array();
  if (is_dir($strPath)) {
    if ($handle = opendir($strPath)) {
      while (($strFile = readdir($handle)) !== false) {
        if ($strFile != "." && $strFile != ".." && $strFile != "CVS") {
          $arrDirectoryContent[] = $strFile;
        }
      }
      closedir($handle);
    } else {
      if ($booErrorRep) {
        $error->addError('opendir(' . $strPath . ')', 'directory can not be read by phpsysinfo');
      }
    }
  } else {
    if ($booErrorRep) {
      $error->addError('is_dir(' . $strPath . ')', 'directory does not exist on your machine');
    }
  }
  return $arrDirectoryContent;
}
function __autoload($class_name) {
  $dirs = array('/plugins/' . $class_name . '/', '/includes/');
  foreach($dirs as $dir) {
    if (file_exists(APP_ROOT . $dir . 'class.' . $class_name . '.inc.php')) {
      require_once (APP_ROOT . $dir . 'class.' . $class_name . '.inc.php');
      return;
    }
  }
}
/**
 * Check for the SimpleXML fuction.
 *
 * We need that extension for almost everything,
 * even our error class needs this to output the errors.
 * Because of that this function willreturn a hard coded
 * XML string (with headers) if the SimpleXML extension isn't loaded.
 * Then it will terminate the script.
 * See bug #1787137
 *
 * @access public
 *
 */
function checkForExtensions() {
  $extensions = array('simplexml', 'pcre', 'xml');
  $text = "";
  $error = false;
  $text.= "<?xml version='1.0'?>\n";
  $text.= "<phpsysinfo>\n";
  $text.= "  <Error>\n";
  foreach($extensions as $extension) {
    if (!extension_loaded($extension)) {
      $text.= "    <Function>checkForExtensions</Function>\n";
      $text.= "    <Message>phpSysInfo requires the " . $extension . " extension to php in order to work properly.</Message>\n";
      $error = true;
    }
  }
  $text.= "  </Error>\n";
  $text.= "</phpsysinfo>";
  if ($error) {
    header("Content-Type: text/xml\n\n");
    echo $text;
    die();
  }
}
?>
