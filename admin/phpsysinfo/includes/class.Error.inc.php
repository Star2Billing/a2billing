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
// $Id: class.Error.inc.php,v 1.9 2008/05/31 20:27:52 bigmichi1 Exp $
//
class Error {
  /**
   * holds the instance of this class
   *
   * @access private
   * @static
   * @var object
   */
  private static $instance;
  /**
   * holds the error messages
   *
   * @access private
   * @var array
   */
  private $arrErrorList;
  /**
   * current number ob errors
   *
   * @access private
   * @var integer
   */
  private $errors;
  /**
   * initalize some used vars
   *
   * @access private
   */
  private function __construct() {
    $this->errors = 0;
    $this->arrErrorList = array();
  }
  /**
   * Singleton function
   *
   * @access public
   * @return object instance of the class
   */
  public static function singleton() {
    if (!isset(self::$instance)) {
      $c = __CLASS__;
      self::$instance = new $c;
    }
    return self::$instance;
  }
  /**
   * triggers an error when somebody tries to clone the object
   *
   * @access public
   */
  public function __clone() {
    trigger_error("Can't be cloned", E_USER_ERROR);
  }
  /**
   * adds an error to the internal list
   *
   * @access public
   * @param string Command, which cause the Error
   * @param string additional Message, to describe the Error
   *
   */
  public function addError($strCommand, $strMessage) {
    $this->arrErrorList[$this->errors]['command'] = $strCommand;
    $this->arrErrorList[$this->errors]['message'] = $this->trace($strMessage);
    $this->errors++;
  }
  /**
   * adds a waraning to the internal list
   *
   * @access public
   * @param string Warning message to display
   *
   */
  public function addWarning($strMessage) {
    $this->arrErrorList[$this->errors]['command'] = "WARN";
    $this->arrErrorList[$this->errors]['message'] = $strMessage;
    $this->errors++;
  }
  /**
   * converts the internal error and warning list in a html table
   *
   * @access public
   * @return string contains a HTML table which can be used to echo out the errors
   *
   */
  public function ErrorsAsHTML() {
    $strHTMLString = "";
    $strWARNString = "";
    $strHTMLhead = "<table width=\"100%\" border=\"0\">\n" . "\t<tr>\n" . "\t\t<td><font size=\"-1\"><b>Command</b></font></td>\n" . "\t\t<td><font size=\"-1\"><b>Message</b></font></td>\n" . "\t</tr>\n";
    $strHTMLfoot = "</table>\n";
    if ($this->errors > 0) {
      foreach($this->arrErrorList as $arrLine) {
        if ($arrLine['command'] == "WARN") {
          $strWARNString.= "<font size=\"-1\"><b>WARNING: " . str_replace("\n", "<br/>", htmlspecialchars($arrLine['message'])) . "</b></font><br/>\n";
        } else {
          $strHTMLString.= "\t<tr>\n" . "\t\t<td><font size=\"-1\">" . htmlspecialchars($arrLine['command']) . "</font></td>\n" . "\t\t<td><font size=\"-1\">" . str_replace("\n", "<br/>", $arrLine['message']) . "</font></td>\n" . "\t</tr>\n";
        }
      }
    }
    if (!empty($strHTMLString)) {
      $strHTMLString = $strWARNString . $strHTMLhead . $strHTMLString . $strHTMLfoot;
    } else {
      $strHTMLString = $strWARNString;
    }
    return $strHTMLString;
  }
  /**
   * converts the internal error and warning list to a XML file
   *
   * @access public
   * @return XML data containing the errors
   *
   */
  public function ErrorsAsXML() {
    $xml = simplexml_load_string("<?xml version='1.0'?>\n<phpsysinfo></phpsysinfo>");
    $generation = $xml->addChild('Generation');
    $generation->addAttribute('version', PSI_VERSION);
    $generation->addAttribute('timestamp', time());
    if ($this->errors > 0) {
      foreach($this->arrErrorList as $arrLine) {
        $error = $xml->addChild('Error');
        $error->addChild('Function', $arrLine['command']);
        $error->addChild('Message', $arrLine['message']);
      }
    }
    return $xml->asXML();
  }
  /**
   * add the errors to an existing xml document
   *
   * @access public
   * @param SimpleXMLObject reference existing simplexmlobject to which errors are added if present
   *
   */
  public function ErrorsAddToXML(&$xml) {
    if ($this->errors > 0) {
      $xmlerr = $xml->addChild('Errors');
      foreach($this->arrErrorList as $arrLine) {
        $error = $xmlerr->addChild('Error');
        $error->addChild('Function', utf8_encode(trim(htmlspecialchars($arrLine['command']))));
        $error->addChild('Message', utf8_encode(trim(htmlspecialchars($arrLine['message']))));
      }
    }
  }
  /**
   * check if errors exists
   *
   * @access public
   * @return boolean true if are errors logged, false if not
   *
   */
  public function ErrorsExist() {
    if ($this->errors > 0) {
      return true;
    } else {
      return false;
    }
  }
  /**
   * generate a function backtrace for error diagnostic, function is genearally based on code submitted in the php reference page
   *
   * @param string additional message to display
   * @return string formatted string of the backtrace
   */
  private function trace($strMessage) {
    $arrTrace = array_reverse(debug_backtrace());
    $strFunc = '';
    $strBacktrace = htmlspecialchars($strMessage) . "\n\n";
    foreach($arrTrace as $val) {
      // avoid the last line, which says the error is from the error class
      if ($val == $arrTrace[count($arrTrace) -1]) {
        break;
      }
      $strBacktrace.= str_replace(APP_ROOT, ".", $val['file']) . ' on line ' . $val['line'];
      if ($strFunc) {
        $strBacktrace.= ' in function ' . $strFunc;
      }
      if ($val['function'] == 'include' || $val['function'] == 'require' || $val['function'] == 'include_once' || $val['function'] == 'require_once') {
        $strFunc = '';
      } else {
        $strFunc = $val['function'] . '(';
        if (isset($val['args'][0])) {
          $strFunc.= ' ';
          $strComma = '';
          foreach($val['args'] as $val) {
            $strFunc.= $strComma . $this->print_var($val);
            $strComma = ', ';
          }
          $strFunc.= ' ';
        }
        $strFunc.= ')';
      }
      $strBacktrace.= "\n";
    }
    return $strBacktrace;
  }
  /**
   * convert some special vars into better readable output
   *
   * @param mixed value, which should be formatted
   * @return string formatted string
   */
  private function print_var($var) {
    if (is_string($var)) {
      return ('"' . str_replace(array("\x00", "\x0a", "\x0d", "\x1a", "\x09"), array('\0', '\n', '\r', '\Z', '\t'), $var) . '"');
    } elseif (is_bool($var)) {
      if ($var) {
        return ('true');
      } else {
        return ('false');
      }
    } elseif (is_array($var)) {
      $strResult = 'array( ';
      $strComma = '';
      foreach($var as $key => $val) {
        $strResult.= $strComma . $this->print_var($key) . ' => ' . $this->print_var($val);
        $strComma = ', ';
      }
      $strResult.= ' )';
      return ($strResult);
    }
    // anything else, just let php try to print it
    return (var_export($var, true));
  }
}
?>
