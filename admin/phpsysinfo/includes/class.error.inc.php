<?php
/***************************************************************************
 *   Copyright (C) 2006 by phpSysInfo - A PHP System Information Script    *
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

// $Id: class.error.inc.php,v 1.9 2007/02/11 15:57:17 bigmichi1 Exp $

class Error
{
    // Array which holds the error messages
    public $arrErrorList 	= array();
    // current number of errors encountered
    public $errors 		= 0;

    /**
    *
    *  addError()
    *
    *  @param	strCommand	string		Command, which cause the Error
    *  @param	strMessage	string		additional Message, to describe the Error
    *  @param	intLine		integer		on which line the Error occours
    *  @param	strFile		string		in which File the Error occours
    *
    *  @return	-
    *
    **/
    public function addError( $strCommand, $strMessage, $intLine, $strFile )
    {
        $this->arrErrorList[$this->errors]['command'] = $strCommand;
        $this->arrErrorList[$this->errors]['message'] = $strMessage;
        $this->arrErrorList[$this->errors]['line']    = $intLine;
        $this->arrErrorList[$this->errors]['file']    = basename( $strFile );
        $this->errors++;
    }

    /**
    *
    *  addWarning()
    *
    *  @param	strMessage	string		Warning message to display
    *
    *  @return	-
    *
    **/
    public function addWarning( $strMessage )
    {
        $this->arrErrorList[$this->errors]['command'] = "WARN";
        $this->arrErrorList[$this->errors]['message'] = $strMessage;
        $this->errors++;
    }

    /**
    *
    * ErrorsAsHTML()
    *
    * @param	-
    *
    * @return	string		string which contains a HTML table which can be used to echo out the errors
    *
    **/
    public function ErrorsAsHTML()
    {
        $strHTMLString = "";
        $strWARNString = "";
        $strHTMLhead = "<table width=\"100%\" border=\"0\">\n"
                . "\t<tr>\n"
                . "\t\t<td><font size=\"-1\"><b>File</b></font></td>\n"
                . "\t\t<td><font size=\"-1\"><b>Line</b></font></td>\n"
                . "\t\t<td><font size=\"-1\"><b>Command</b></font></td>\n"
                . "\t\t<td><font size=\"-1\"><b>Message</b></font></td>\n"
                . "\t</tr>\n";
        $strHTMLfoot = "</table>\n";

        if ($this->errors > 0) {
            foreach ($this->arrErrorList as $arrLine) {
                if ($arrLine['command'] == "WARN") {
                    $strWARNString .= "<font size=\"-1\"><b>WARNING: " . str_replace( "\n", "<br>", htmlspecialchars( $arrLine['message'] ) ) . "</b></font><br>\n";
                } else {
                    $strHTMLString .= "\t<tr>\n"
                            . "\t\t<td><font size=\"-1\">" . htmlspecialchars( $arrLine['file'] ) . "</font></td>\n"
                            . "\t\t<td><font size=\"-1\">" . $arrLine['line'] . "</font></td>\n"
                            . "\t\t<td><font size=\"-1\">" . htmlspecialchars( $arrLine['command'] ) . "</font></td>\n"
                            . "\t\t<td><font size=\"-1\">" . str_replace( "\n", "<br>", htmlspecialchars( $arrLine['message'] ) ) . "</font></td>\n"
                            . "\t</tr>\n";
                }
            }
        }

        if ( !empty( $strHTMLString ) ) {
            $strHTMLString = $strWARNString . $strHTMLhead . $strHTMLString . $strHTMLfoot;
        } else {
            $strHTMLString = $strWARNString;
        }

        return $strHTMLString;
    }

    /**
    *
    * ErrorsExist()
    *
    * @param	-
    *
    * @return 	true	there are errors logged
    *		false	no errors logged
    *
    **/
    public function ErrorsExist()
    {
        if ($this->errors > 0) {
            return true;
        } else {
            return false;
        }
    }
}
