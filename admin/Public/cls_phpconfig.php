<?php

/**
 *
 * Asterisk configuration file interface
 * Used as an aid in viewing and editing Asterisk confiuration
 * files.
 *
 *
 *
 *
 * phpconfig:,v 1.0 2003/07/03 17:19:37
 * Authors: Dave Packham <dave.packham@utah.edu>
 *          Rob Birkinshaw <robert.birkinshaw@utah.edu>
 */

class Open_Conf
{
    // for application specific defaults
    // use the phpconfig_init.php file //

    //// DEFAULTS ////
    // remark symbol in conf file
    var $_OC_remark = ";";
    // temporary directory
    var $_OC_temp_dir = "/tmp";
    // temporary file prefix
    var $_OC_temp_prefix = "conf-";
    // executable to read conf files
    var $_OC_reset_cmd = "/bin/asterisk.reload";
    var $_OC_access_file = "/etc/asterisk/manager.conf";
    // directory of configuration files
    var $_OC_conf_dir = "/etc/asterisk";
    // expression defining valid configuration files
    var $_OC_conf_filter = "/.conf\$/";
    // values expression
    var $_OC_value_exp = "/^[^=;]*=>?\s*(\d[^\,\;]*)\s*[\,\;]*.*[\r\n]\$/";
    // values expression replacement string
    var $_OC_value_exp_replace = "\$1";
    // assignment operators in conf files (ex. = or =>)
    var $_OC_assignment_oper = "/^(=>?)|,*\$/";


    //// SHOULD NOT CHANGE THESE ////
    // fully qualified confFile
    var $_OC_conf_file = "";
    // md5 hash of the conf
    var $_OC_md5 = "";
    // valid configuration directories
    var $_OC_conf_dirs = array();
    // holds each line of conf file
    var $_OC_the_file = array();
    // stores each section, firstline and offset
    var $_OC_the_sections = array();
    // stores values (right side of "=" or "=>")
    var $_OC_the_values = array();


//////////////////////////////////////////////////////////
/**
 * Access functions
 * Set Open Conf Global variables
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $newvalue
 * return:
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////
    function OC_setRemark($newvalue) { $this->_OC_remark=$newvalue; }
    function OC_setTempDir($newvalue) { $this->_OC_temp_dir=$newvalue; }
    function OC_setTempPrefix($newvalue) { $this->_OC_temp_prefix=$newvalue; }
    function OC_setConfDir($newvalue,$confdirs)
    {
        // check that new conf direcotry exists in the
        // valid list of configuration directories
        foreach($confdirs as $thedir)
        {
            if ($newvalue == $thedir)
            {
                $this->_OC_conf_dir=$newvalue;
                return true;
            }
        }
        return false;
    }
    function OC_setConfFilter($newvalue) { $this->_OC_conf_filter=$newvalue; }
    function OC_setValueExp($newvalue) { $this->_OC_value_exp=$newvalue; }
    function OC_setValueExp_replace($newvalue) { $this->_OC_value_exp_replace=$newvalue; }
    function OC_setAssignmentOper($newvalue) { $this->_OC_assignment_oper=$newvalue; }
    function OC_setAccessFile($newvalue) { $this->_OC_access_file=$newvalue; }
    function OC_setResetCmd($newvalue) { $this->_OC_reset_cmd=$newvalue; }
    function OC_setConfFile($newvalue) { $this->_OC_conf_file=$newvalue; }
    function OC_setConfDirectories($newvalue)
    {
        // build directory name => link menu list
        foreach ($newvalue as $item)
        {
            $this->_OC_conf_dirs[$item] = $_SERVER['PHP_SELF'] . '?dir=' . $item;
        }
    }


//////////////////////////////////////////////////////////////
/**
 * OC_readConfFile
 * Reads conf file into array $_OC_the_file
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $confFile  string - Name of configuration file to read
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_readConfFile($confFile)
    {
    	if(!preg_match($this->_OC_conf_filter, $confFile))
		{
			exit();
		}
            
        $this->OC_setConfFile($this->_OC_conf_dir . '/' . basename($confFile));

        $this->_OC_the_file = array();

        if (! is_file($this->_OC_conf_file))
        {
            return(false);
        }

        $file = fopen($this->_OC_conf_file, "r");

        while (!feof($file))
        {
            $this->_OC_the_file[] = fgetc($file);
        }

        fclose($file);

        // generate MD5 hash of conf file to be used
        // with update operation
        $this->_OC_md5 = md5_file($this->_OC_conf_file);

        // build array with each section found in conf file
        if (! $this->_OC_readConfSections())
        {
            return(false);
        }

        return(true);
    }


//////////////////////////////////////////////////////////////
/**
 * _OC_readConfSections - Private function
 * Finds sections in the filearray and its line boundries.
 * Stored in $_OC_the_sections[]=array(section,firstline,offset)
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     none
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function _OC_readConfSections()
    {
        // first element of array is the entire conf file from line zero to EOF
        $this->_OC_the_sections[] = array(basename($this->_OC_conf_file),0,count($this->_OC_the_file));
        $linenumber = 0;
        foreach($this->_OC_the_file as $line)
        {
            // look for sections
            if(preg_match("/^\s*\[([^\]]*)\].*[\r\n]\$/", $line))
            {
                // parse for section text and add to section array
                $section = preg_replace("/^\s*\[([^\]]*)\].*[\r\n]\$/","\$1",$line);
                $this->_OC_the_sections[] = array(trim($section),$linenumber);
            }
            elseif ($linenumber == 0) // is file header (comments before first section)
            {
                $this->_OC_the_sections[] = array('Header',0);
            }

            $linenumber ++;
        } // foreach line in file


        // find firstline and offset for each section
        $sectionCount = count($this->_OC_the_sections);

        if ($sectionCount == 0)
        {
            return(false);
        }

        for ($i = 1; $i < $sectionCount; $i++)
        {
            if ($i == $sectionCount -1) //last section
            {
                $this->_OC_the_sections[$i][2] = count($this->_OC_the_file) - $this->_OC_the_sections[$i][1] -1;
            }
            else
            {
                $this->_OC_the_sections[$i][2] = $this->_OC_the_sections[$i+1][1] - $this->_OC_the_sections[$i][1];
            }
        }

        return(true);
    }


//////////////////////////////////////////////////////////////
/**
 * OC_getConfSectionItems
 * Reads supplied section into an array
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $inSection string, name of section to read
 * return:    array or false
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_getConfSectionItems($inSection)
    {
        foreach($this->_OC_the_sections as $record)
        {
    list($name,$firstLine, $offset) = $record;
    if ($name == $inSection)
    {
         $result = array_slice ($this->_OC_the_file, $firstLine,$offset);
         return $result;
    }
        } // foreach section

     return(false);

    }


//////////////////////////////////////////////////////////////
/**
 * OC_writeConfSection
 * Writes supplied section out to tempfile then to original file
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $originalMD5 string, md5 hash
 *            $updateSection string, section to update
 *            $section_text string, text to update section with
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_writeConfSection($originalMD5, $updateSection, $section_text)
    {
        // check if user has privs. to write
        $access_result = $this->OC_checkAccess($_SESSION['valid_user']);

        if (!$access_result)
        {
            return false;
        }

        // grab current hash for file to update
        // and compare to old hash, abort if different
        if ($originalMD5 != $this->_OC_md5)
        {
            die ('The file has changed between read and write.<br>Operation failed.<br>');
        }

        // parse textarea text back into an array
        $newSection = preg_split("/\r\n/",$section_text);

        // add linefeeds to each row and clean array
        for ($i = 0; $i < count($newSection); $i++)
        {
          $newSection[$i] = stripslashes($newSection[$i]) . "\n";
        }

        // clean up extra line from html textarea control
        if (strlen($newSection[count($newSection)-1]) == 1)
        {
            array_pop($newSection);
        }

        // splice in new section
        foreach($this->_OC_the_sections as $record)
        {
            list($name,$lineStart, $offset) = $record;
            if($name == $updateSection)
            {
                array_splice ($this->_OC_the_file, $lineStart, $offset, $newSection);
                break;
            }
        }

        // create and open temp file
        $tempfile = tempnam($this->_OC_temp_dir, $this->_OC_temp_prefix);

        if(! $tempfile)
        {
            return(false);
        }

        $transient = fopen($tempfile, "w");

        // write file array out to tempfile
        foreach ($this->_OC_the_file as $line)
        {
            fputs($transient, str_replace("\r\n", "\n", $line));
        }

        fclose($transient);

        // copy tempfile to origional file and remove tempfile
        $writing = copy($tempfile, $this->_OC_conf_file);
        unlink($tempfile);

        return($writing);
    }


//////////////////////////////////////////////////////////////
/**
 * OC_getConfFiles
 * Creates an array of configuration files for the current
 * configuration directory.
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $inSection string, name of section to read
 * return:    array of configuration files or false
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_getConfFiles()
    {
        if (! $dir = @opendir($this->_OC_conf_dir)) return (false);

            while (($file = readdir($dir)) !== false)
            {
                // ignore hidden files
                if(!preg_match("/^\./", $file))
                {
                    // ignore directories
                    if(! is_dir($this->_OC_conf_dir . '/' . $file))
                    {
                        // Match extensions
                        if(preg_match($this->_OC_conf_filter, $file))
                        {
                            $files[] = $file;
                        }
                    } // ignore dirs

                } // ignore hidden

            } //while read dir

            closedir($dir);

            if(is_array($files))
            {
                sort($files);
            }
            else
            {
                return(false);
            }

        return $files;
    }


//////////////////////////////////////////////////////////////
/**
 * OC_readConfValues
 * Finds values (value is defined by _OC_value_exp) in the filearray.
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $theItems array, list of item values
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_readConfValues($theItems)
    {
        $atemp = array();

        foreach($theItems as $line)
        {
            // filer on "value" defined by _OC_value_exp
            if(preg_match($this->_OC_value_exp, $line))
            {
                $atemp[] = preg_replace($this->_OC_value_exp,$this->_OC_value_exp_replace,$line);
            }
        }

        if (count($atemp) == 0)
        {
            return (false);
        }

        $this->_OC_the_values = array_unique($atemp);
        sort($this->_OC_the_values,SORT_STRING);
        reset($this->_OC_the_values);

        return(true);
    }


//////////////////////////////////////////////////////////////
/**
 * OC_checkAccess
 * Reads access file $_OC_access_file and checks if user exists
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $theItems array, list of item values
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_checkAccess($user)
    {
        $accessFile = array();

        if (! is_file($this->_OC_access_file))
        {
            echo 'Access file: ' .$this->_OC_access_file . 'not found!<br>';
            return(false);
        }
		
        $file = fopen($this->_OC_access_file, "r");

        while (!feof($file))
        {
            $accessFile[] = fgets($file);
        }

        fclose($file);		

        foreach ($accessFile as $line)
        {
            if (preg_match("/^\s*\[$user\].*[\r\n]\$/", $line))
            {
                return(true);
            }
        }

        echo 'User: ' . $user . ' does not have access to this feature.<br>';
        return(false);
    }

//////////////////////////////////////////////////////////////
/**
 * OC_checkValidUser
 * Checks is user has a valid session
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_checkValidUser()
    {
        if (!isset($_SESSION['valid_user']))
        {
            die( 'You are not logged in.<br>');
        }
        return(true);
    }

//////////////////////////////////////////////////////////////
/**
 * OC_checkIfAdministrator
 * Not used yet
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:
 * return:    boolean
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////////
    function OC_checkIfAdministrator()
    {
        if ( $_SESSION['is_admin'] )
        {
             return(true);
        }
        else
        {
             return(false);
        }
    }
}
?>
