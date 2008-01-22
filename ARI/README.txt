Developed by Dan Littlejohn of Littlejohn Consulting.
  www.littlejohnconsulting.com

Released under the GPL.

Send bug reports, requests to dan@littlejohnconsulting.com

+++

Misc notes

ARI Project Page
  www.littlejohnconsulting.com?q=ari

Coding standard
 * class - CamelCase (ie ClassName)
 * method camelCase (ie methodName)
 * variable underscore (ie variable_name)
 * constant UNDERSCORE (ie CONSTANT_NAME)

Requirements
  PHP4 (but PHP5 is not yet supported)
  PHP PEAR
  asterisk 1.2 or later
  apache or apache2
  asterisk manager - at a mininum need command access

security
  for security all the files in ./recordings/include should be locked down in the web browser
    so they cannot be viewed.

voicemail email links - For those who would like to include a link to ARI in the voicemail email and set the correct login (mailbox) you can do so as:

  http://< ip address >/recordings/index.php?login=< login >
 
    replace 
      < ip address > with the server dns or ip
      < login > with the login or mailbox

+++

Module API

odules can be added or removed from ARI.

API

must include these methods.

rank - weights were the module menu item will appear in the navigation window
init - initialize the module.  Database access should first appear here and not in the constructor
navMenu - side navigation menu item
display - main module page content

example

<?php

/**
 * @file
 * Functions for the interface to the help page
 */

/**
  * Class for new_module
  */
class NewModule {

  /*
   * rank (for prioritizing modules)
   */
  function rank() {

    $rank = 50;
    return $rank;
  }

  /*
   * init
   */
  function init() {
  }

  /*
   * Adds menu item to nav menu
   *
   * @param $args
   *   Common arguments
   */
  function navMenu($args) {

    // put if statement in return string, because do not know $logout until page is built
    $ret .= "
      <?php if ($logout !='') { ?>
        <p><small><small><a href='" . $_SERVER['PHP_SELF'] . "?m=NewModule&f=display'>" . _("new_module") . "</a></small></small></p>
      <?php } ?>";

    return $ret;
  }

  /*
   * Displays stats page
   *
   * @param $args
   *   Common arguments
   */
  function display($args) {

    // build page content
    $ret .= checkErrorMessage();

    $ret .= $display->displayHeaderText("new_module");
    $ret .= $display->displayLine();

    return $ret;
  }

}


?>


