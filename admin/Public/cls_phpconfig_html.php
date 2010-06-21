<?php

/**
 *
 * phpconfig HTML output
 * HTML for phpconfig application
 * For this output to display correctly call the following
 * functions in order:
 *    OC_HTML_doHtmlHeader()
 *    OC_HTML_doSideMenu()
 *    <your content goes here>
 *    OC_HTML_doRightMenu()  **only if $_OC_HTML_3pane = TRUE
 *    OC_HTML_doHtmlFooter()
 *
 * Defaults to left menu only.  if you require a right menu
 * set $_OC_HTML_3pane = TRUE and include OC_HTML_doRightMenu()
 * at the appropriate time.
 *
 *
 * phpconfig HTML:,v 1.0 2003/07/03 17:19:37
 * Authors: Dave Packham <dave.packham@utah.edu>
 *          Rob Birkinshaw <robert.birkinshaw@utah.edu>
 */

// add for a2billing 
include_once ("../lib/admin.defines.php");
include_once ("../lib/admin.module.access.php");
include_once ("../lib/admin.smarty.php");

if (! has_rights (ACX_ADMINISTRATOR)){ 
	Header ("HTTP/1.0 401 Unauthorized");
	Header ("Location: PP_error.php?c=accessdenied");	   
	die();	   
}

class Open_Conf_HTML
{

    // for application specific defaults
    // use the phpconfig_init.php file //

var $_OC_HTML_images_dir = "/var/web/html/images/";
var $_OC_HTML_logo = "logo.gif";
var $_OC_HTML_title = "phpconfig for Asterisk PBX";
var $_OC_HTML_page_title = "phpconfig for Asterisk PBX";
var $_OC_HTML_description = "The Open Source PBX";
var $_OC_HTML_keywords = "PBX Asterisk";
var $_OC_HTML_footer_text = "Created by p0lar, Dave Packham & Rob Birkinshaw";
var $_OC_HTML_webmaster = "http://www.asterisk.org";
var $_OC_HTML_disclaimer = "http://www.asterisk.org";
var $_OC_HTML_logo_link = "http://www.asterisk.org";
var $_OC_HTML_textarea_rows = 30;
var $_OC_HTML_3pane = FALSE;

/// SHOULD NOT CHANGE ///
var $_OC_HTML_menu_list = array(""=>"");
var $_OC_HTML_right_menu_list = array(""=>"");
var $_OC_HTML_headerbar = array(""=>"");
var $_OC_HTML_colspan = "3";


//////////////////////////////////////////////////////////
/**
 * Access functions
 * Set Open Conf HTML Global variables
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $newvalue
 * return:
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////
    function OC_HTML_setTitle($newvalue) { $this->_OC_HTML_title=$newvalue; }
    function OC_HTML_setLogo($newvalue) { $this->_OC_HTML_logo=$newvalue; }
    function OC_HTML_setPageTitle($newvalue) { $this->_OC_HTML_page_title=$newvalue; }
    function OC_HTML_setDescription($newvalue) { $this->_OC_HTML_description=$newvalue; }
    function OC_HTML_setKeywords($newvalue) { $this->_OC_HTML_keywords=$newvalue; }
    function OC_HTML_setWebmaster($newvalue) { $this->_OC_HTML_webmaster=$newvalue; }
    function OC_HTML_setDisclaimer($newvalue) { $this->_OC_HTML_disclaimer=$newvalue; }
    function OC_HTML_setLogoLink($newvalue) { $this->_OC_HTML_logo_link=$newvalue; }
    function OC_HTML_setMenuList($newvalue) { $this->_OC_HTML_menu_list=$newvalue; }
    function OC_HTML_setRightMenuList($newvalue) { $this->_OC_HTML_right_menu_list=$newvalue; }
    function OC_HTML_setHeaderBar($newvalue) { $this->_OC_HTML_headerbar=($newvalue); }
    function OC_HTML_setFooterText($newvalue) { $this->_OC_HTML_footer_text=$newvalue; }
    function OC_HTML_setTextareaRows($newvalue) { $this->_OC_HTML_textarea_rows=$newvalue; }
    function OC_HTML_setImagesDir($newvalue)
    {
        $this->_OC_HTML_images_dir=$newvalue . "/";
    }
    function OC_HTML_set3Pane($newvalue)
    {
        $this->_OC_HTML_3pane = $newvalue;
        $this->_OC_HTML_colspan = "5";
    }



 //////////////////////////////////////////////////////////
 /**
  * OC_HTML_doHtmlHeading
  * <h2> heading
  *
  * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
  * param:     $heading string
  * return:
  * date:      2003-07-03
  *
  */
//////////////////////////////////////////////////////////
    function OC_HTML_doHtmlHeading($heading)
    {
        ?>
            <h2><?php echo $heading?></h2>
        <?php
    }

//////////////////////////////////////////////////////////
/**
 * OC_HTML_doHtmlUrl
 * Creates a url with passed name and link
 *
 * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
 * param:     $url string
 *            $name string, display for URL
 * return:
 * date:      2003-07-03
 *
 */
//////////////////////////////////////////////////////////
    function OC_HTML_doHtmlUrl($url, $name)
    { // output URL as link and br
        ?>
            <br><a href="<?php echo $url?>"><?php echo$name?></a><br>
        <?php
    }

 //////////////////////////////////////////////////////////
 /**
  * OC_HTML_doSideMenu
  * left menu for page
  *
  * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
  * param:     _OC_HTML_menu_list associative array(name=>link), optional menu
  * return:
  * date:      2003-07-03
  *
  */
//////////////////////////////////////////////////////////
    function OC_HTML_doSideMenu()
    {
        if (func_num_args() == 1)
        {
            $this->_OC_HTML_menu_list = func_get_arg (0);
        }
        ?>
            <td valign="top">
            <table width="100%" cellpadding="2" cellspacing="2">
        <?php
            foreach ($this->_OC_HTML_menu_list as $name => $value)
            {
                echo "<tr><td><a href=\"" . $value . "\" class=\"menuSide\">" . $name . "</a></td></tr>\n";
            }
        ?>
            <tr><td>&nbsp;</td></tr>
            </table>
            </td><td>&nbsp;</td><td>
        <?php
    }

  //////////////////////////////////////////////////////////
  /**
   * OC_HTML_doRightMenu
   * right side menu for page
   *
   * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
   * param:
   * return:
   * date:      2003-07-03
   *
   */
//////////////////////////////////////////////////////////
    function OC_HTML_doRightMenu()
    {
        ?>
            </td><td>&nbsp;</td>
            <td valign="top">
            <table width="100%" cellpadding="2" cellspacing="2">
        <?php
        foreach ($this->_OC_HTML_right_menu_list as $name => $value)
        {
            echo "<tr><td><a href=\"" . $value . "\" class=\"menuSide\">" . $name . "</a></td></tr>\n";
        }
        ?>
            <tr><td>&nbsp;</td></tr>
            </table>
        <?php
    }

//////////////////////////////////////////////////////////
/**
   * OC_HTML_doHtmlHeader
   * header for page
   *
   * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
   * param:     _OC_HTML_headerbar associative array(name=>link), optional menu
   * return:
   * date:      2003-07-03
   *
   */
//////////////////////////////////////////////////////////
    function OC_HTML_doHtmlHeader()
    {
        if (func_num_args() == 1)
        {
            $this->_OC_HTML_headerbar = func_get_arg (0);
        }
        ?>
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
            <html>
            <head>
            <title><?php echo $this->_OC_HTML_title ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            <meta name="description" content="<?php echo $this->_OC_HTML_description ?>">
            <meta name="keywords" content="<?php echo $this->_OC_HTML_keywords ?>">
            <style type="text/css">
            <!--
            body {
                    font-family: Arial, Helvetica, sans-serif;
                    background-color: #FFFFFF;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                margin-left: 5px;
                    font-size: 12px;
                    color: #000000;
            }
            .StyleUDiv {
                width: 70;
                position:absolute;
                top:7px;
                left:38px;
                Padding:0pt;
                z-index:11;
                visibility: visible
            }
            A:link {  color: #CC0000;text-decoration: none; }
            A:visited { color: #333333;text-decoration: none; }
            A:Hover { color:#990000;  text-decoration: underline; }
            .menuTop {
                background-color: #CCCCCC;
                border: 1px solid #000000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                text-decoration: none;
                font-weight: bold;
            }
            .fields {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                color: #000000;
            }
            .deptTitle {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 22px;
                font-weight: bolder;
                color: #000000;
                letter-spacing: 5px;
                padding: 5px;
            }
            .menuSide {
                background-color: #FFFFFF;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                text-decoration: none;
            }
            .greyFooter {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                color: #FFFFFF;
                background-color: #333333;
                text-align: center;
                padding: 4px 2px 6px;
            }
            -->
            </style>
            </head>
            <body><img src="<?php echo  $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="10" height="17">
            <table width="100%" border="0" cellspacing="5" cellpadding="0">
                <tr>
                    <td colspan="<?php echo  $this->_OC_HTML_colspan ?>">
                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center"><span class="deptTitle"><?php echo  $this->_OC_HTML_page_title ?></span></td>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="menuTop">
                <tr align="center">
                <td width = "125"><img src="<?php echo  $this->_OC_HTML_images_dir ?>spacer.gif" width="125" height="22" alt="spacer"></td><?php $this->OC_HTML_doHeaderBar() ?><td></td>
                </tr>
                <tr>
                <td></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="80" height="1"></td>
                </tr>
            </table></td>
            </tr>
            <tr>
        <?php
    }
//////////////////////////////////////////////////////////
/**
   * OC_HTML_doHtmlHeader_first_aid_kit
   * header for page
   *
   * author:    Areski
   * param:     _OC_HTML_headerbar associative array(name=>link), optional menu
   * return:
   * date:      2005-07-11
   *
   */
//////////////////////////////////////////////////////////
    function OC_HTML_doHtmlHeader_a2billing($smarty)
    {
        if (func_num_args() == 1)
        {
            //$this->_OC_HTML_headerbar = func_get_arg (0);
        }
		
		// #### HEADER SECTION
		$smarty->display('main.tpl');
		
		
        ?>
        <style type="text/css">
            <!--
            .StyleUDiv {
                width: 70;
                position:absolute;
                top:7px;
                left:38px;
                Padding:0pt;
                z-index:11;
                visibility: visible
            }
            .menuTop {
                background-color: #CCCCCC;
                border: 1px solid #000000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                text-decoration: none;
                font-weight: bold;
            }
            .fields {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                color: #000000;
            }
            .deptTitle {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 22px;
                font-weight: bolder;
                color: #000000;
                letter-spacing: 5px;
                padding: 5px;
            }
            .menuSide {
                background-color: #FFFFFF;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                text-decoration: none;
            }
            .greyFooter {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                color: #FFFFFF;
                background-color: #333333;
                text-align: center;
                padding: 4px 2px 6px;
            }
            .greyFooter {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                color: #FFFFFF;
                background-color: #333333;
                text-align: center;
                padding: 4px 2px 6px;
            }
            textarea {overflow-x: hidden; overflow-y: scroll}
            -->
            </style>
			
            <table width="100%" border="0" cellspacing="5" cellpadding="0">
                <tr>
                    <td colspan="<?php echo  $this->_OC_HTML_colspan ?>">
                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center"><span class="deptTitle"><?php echo  $this->_OC_HTML_page_title ?></span></td>
                </tr>
            </table>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="menuTop">
                <tr align="center">
                <td width = "125"><img src="<?php echo  $this->_OC_HTML_images_dir ?>spacer.gif" width="125" height="22" alt="spacer"></td><?php $this->OC_HTML_doHeaderBar() ?><td></td>
                </tr>
                <tr>
                <td></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="50" height="1"></td>
                <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="80" height="1"></td>
                </tr>
            </table></td>
            </tr>
            <tr>
        <?php
    }



 //////////////////////////////////////////////////////////
 /**
    * OC_HTML_doHtmlFooter
    * footer for page
    *
    * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
    * param:
    * return:
    * date:      2003-07-03
    *
    */
//////////////////////////////////////////////////////////
    function OC_HTML_doHtmlFooter()
    {
    ?>
        <!-- page footer -->
            </td>
            </tr>
            <tr>
            <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="125" height="1"></td>
            <td></td>
            <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="540" height="1"></td>
            <?php if($this->_OC_HTML_3pane) echo "<td></td><td><img src=\"$this->_OC_HTML_images_dir" . "spacer.gif\" alt=\"spacer\" width=\"125\" height=\"1\"></td>" ?>
            </tr>
            <tr>
            <td colspan="<?php echo $this->_OC_HTML_colspan ?>" align="center"><img src="<?php echo $this->_OC_HTML_images_dir ?>grey_block.gif" alt="Separator" width="600" height="1"></td>
            </tr>
            <tr>
            <td colspan="<?php echo $this->_OC_HTML_colspan ?>" align="center"><a href="<?php echo $this->_OC_HTML_webmaster ?>" class="menuSide">Webmaster</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_OC_HTML_disclaimer ?>" class="menuSide">Disclaimer</a></td>
            </tr>
            <tr>
            <td colspan="<?php echo $this->_OC_HTML_colspan ?>" class="greyFooter"><?php echo $this->_OC_HTML_footer_text ?></td>
            </tr>
        </table>
        <div id="UDiv" class="StyleUDiv"><a href="<?php echo $this->_OC_HTML_logo_link ?>"><img src="<?php echo $this->_OC_HTML_images_dir . $this->_OC_HTML_logo ?>" width="70" height="64" border="0" alt="Logo"></a>
        </div>
        </body>
        </html>
    <?php
    }
	
	
 //////////////////////////////////////////////////////////
 /**
    * OC_HTML_doHtmlFooter_a2billing
    * footer for page
    *
    * author:    Areski
    * param:
    * return:
    * date:      2005-07-11
    *
    */
//////////////////////////////////////////////////////////
    function OC_HTML_doHtmlFooter_a2billing($smarty)
    {
		
		?>
		 <!-- page footer -->
            </td>
            </tr>
            <tr>
            <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="125" height="1"></td>
            <td></td>
            <td><img src="<?php echo $this->_OC_HTML_images_dir ?>spacer.gif" alt="spacer" width="540" height="1"></td>
            <?php if($this->_OC_HTML_3pane) echo "<td></td><td><img src=\"$this->_OC_HTML_images_dir" . "spacer.gif\" alt=\"spacer\" width=\"125\" height=\"1\"></td>" ?>
            </tr>
            
        </table>
    	<?php
		$smarty->display('footer.tpl');
    }	
	

//////////////////////////////////////////////////////////
 /**
    * OC_HTML_doHeaderBar
    * build header bar menu
    *
    * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
    * param:
    * return:
    * date:      2003-07-03
    *
    */
//////////////////////////////////////////////////////////
    function OC_HTML_doHeaderBar()
    {
        foreach ($this->_OC_HTML_headerbar as $name => $value)
        {
            echo "<td><a href=\"" . $value . "\">" . $name . "</a></td>\n";
        }
    }

//////////////////////////////////////////////////////////
 /**
    * OC_HTML_doYesNo
    * yes/no radio button control
    *
    * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
    * param:
    * return:
    * date:      2003-07-03
    *
    */
//////////////////////////////////////////////////////////
    function OC_HTML_doYesNo($group,$value)
    {
        if($value == 1)
        {
            echo "<input type=\"radio\" name=\"" . $group . "\" value=\"1\" checked > Yes\n";
            echo "<input type=\"radio\" name=\"" . $group . "\" value=\"0\" > No\n";
        }
        else
        {
            echo "<input type=\"radio\" name=\"" . $group . "\" value=\"1\" > Yes\n";
            echo "<input type=\"radio\" name=\"" . $group . "\" value=\"0\" checked > No\n";
        }
    }


  //////////////////////////////////////////////////////////
   /**
      * OC_HTML_showTimestat
      * time statistics for page.  Call this at the end of the
      * script
      *
      * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
      * param:     $start microtime(), obtained at first of script
      * return:
      * date:      2003-07-03
      *
      */
//////////////////////////////////////////////////////////
    function OC_HTML_showTimestat($start)
    {
        $end = microtime();
        $start = explode(" ",$start);
        $end = explode(" ",$end);
        $diff = ($end[0] + $end[1]) - ($start[0] + $start[1]);
        return round($diff,4);
    }

  //////////////////////////////////////////////////////////
   /**
      * OC_HTML_doConfigurationForm
      * textarea box and submit button used to edit conf file
      * post the md5 checksum for file update operation
      * post the section for update
      * insert javascript for find feature (finds text string in textarea)
      * i was only able to do this with Internet Explorer
      *
      * author:    Rob Birkinshaw rbirkinshaw@netcom.utah.edu
      * param:     $file string, name of conf file
      *            $section string, name of section to display
      *            $theItems array, contains each line of section
      *            $md5 string, md5() of the file before editing
      * return:
      * date:      2003-07-03
      *
      */
//////////////////////////////////////////////////////////
    function OC_HTML_doConfigurationForm($file,$section,$theItems,$md5)
    {
	$action = $_SERVER['PHP_SELF']."?file=$file";
        ?>

            <form  name="section_form" method="post" action="<?php echo $action ?>">

            <input type=hidden name="themd5" value="<?php echo $md5 ?>">

            <input type=hidden name="updateSection" value="<?php echo $section ?>">

            <h2><?php echo "Edit: $section" ?></h2>

            <textarea name="section_text" style="width: 100%" rows="<?php echo $this->_OC_HTML_textarea_rows ?>" wrap="off"><?php foreach($theItems as $item) print "$item";?></textarea> <br>

            <input type="Submit" class="form_input_button" name="tryUpdate" value="Update">

            </form>

            <script language="JavaScript">
            <!--
            var TRange=null

            function findString (str) {

            var strFound;

            if (navigator.appName.indexOf("Microsoft")!=-1) {

            // EXPLORER-SPECIFIC CODE

            if (TRange!=null) {
                TRange.collapse(false)
                strFound=TRange.findText(str)
            if (strFound) TRange.select()
            }
            if (TRange==null || strFound==0) {
                TRange=self.document.section_form.section_text.createTextRange()
                strFound=TRange.findText(str)
            if (strFound) TRange.select()
            }
            if (!strFound) alert ("String '"+str+"' not found!")
            }
            else alert ("Browser does not support this feature.")
            }
            //-->
            </script>

        <?php
    }
}

?>
