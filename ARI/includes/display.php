<?php

/**
 * @file
 * Functions common to display
 */

/**
  * Display 
  */
class Display {

  /**
   * display constructor 
   */
  function Display() {
  }

  /**
   * display text header
   *
   * @param $text
   *   Header text to display
   */
  function displayHeaderText($text) {

    $ret = "<h2>" . $text . "</h2>
            <br>";

    return $ret;
  }

  /**
   * displays header line 
   */
  function displayLine() {

    $ret = "
      <div id='line'>
        <div class='spacer'></div>
        <div class='spacer'></div>
      </div>
      <br>";

    return $ret;
  }
}  

/**
  * DisplaySearch  
  */
class DisplaySearch extends Display {

  /**
   * Constructor
   */
  function DisplaySearch() {
  }

  /**
   * displays search controls
   *
   * @param $align
   *   where to align the control
   * @param $q
   *   search query
   * @param $focus
   *   whether to focus control on this block
   */
  function displaySearchBlock($align,$m,$q,$url_opts,$focus) {

    // align
    if ($align=='center') {
      $alignText = "class='bar_center'";
    }
    else {
      $alignText = "class='bar_left'";
    }

    // url options
    foreach ($url_opts as $key => $value) {
      $option_text .= "<input type=hidden name=" . $key . " value=" . $value . ">";
    }

    // build
    $ret .= "<div " . $alignText . ">
               <form class='bar' action='" . $_SESSION['ARI_ROOT'] . "' method='GET' name='search'>
                 <input type=hidden name=m value=" . $m . ">	
                 <input type=text name=q size=40 value='" . $q . "' maxlength=256>
                 " . $option_text . " 
                 <input type=hidden name=start value=0>	
                 <input type=submit name=btnS value='" . _("Search") . "'>
               </form>
             </div>";

    if ($focus=="true") {	// search block loaded twice usually so only allow javascript to be loaded on the top block
      $ret .= "<script type='text/javascript'> 
                 <!-- 
                 if (document.search) { 
                   document.search.q.focus(); 
                 } 
                 // -->                
               </script>";
    }

    return $ret;
  }

  /**
   * displays info bar
   *
   * @param $controls
   *   controls for the page on the bar
   * @param $q
   *   search query
   * @param $start
   *   start number of current page
   * @param $span
   *   number of items on current page
   * @param $total
   *   total number of records found by current search
   */
  function displayInfoBarBlock($controls,$q,$start,$span,$total) {

    if ($total<$span) { 
      $span = $total; 
    }
    $start_count = ($total>0)?$start+1:$start;
    $span_count = ($start+$span>$total)?$total:$start+$span;

    if ($controls) {
      $left_text = $controls;
    }
    elseif ($q != NULL) {
      $left_text = "<small><small>" . _("Searched for") . " <u>" . $q . "</u></small></small>";
    }

    if ($span<$total) {
      $right_text = "<small><small>" . sprintf(_("Results %d - %d of %d"),$start_count,$span_count,$total) . "</small></small>";
    } else {
      $right_text = "<small><small>" . sprintf(_("Results %d"),$total) . "</small></small>";
    }

    $ret .= "
      <table id='navbar' width='100%'>
        <tr>
          <td>
           " . $left_text . "
         </td>
         <td align='right'>
           " . $right_text ."
         </td>
       </tr>
     </table>";

    return $ret;
  }

  /**
   * displays navigation bar
   *
   * @param $q
   *   search query
   * @param $start
   *   start number of current page
   * @param $span
   *   number of items on current page
   * @param $total
   *   total number of records found by current search
   */
  function displayNavigationBlock($m,$q,$url_opts,$start,$span,$total) {

    $start = $start=='' ? 0 : $start ;
    $span = $span=='' ? 15 : $span ;

    $total_pages = ceil($total/$span);
    $start_page = floor($start/$span);

    // if more than ten pages start at this page minus ten otherwise start at zero
    $begin = ($start_page>10)?($start_page-10):0;
    // if more than ten pages then stop at this page plus ten otherwise stop at last page
    $end = ($start_page>8)?($start_page+10):10;

    // url
    $unicode_q = urlencode($q);  // encode search string

    foreach ($url_opts as $key => $value) {
      $option_text .= "&" . $key . "=" . $value;
    }

    $url = $_SESSION['ARI_ROOT'] . "?m=" . $m . "&q=" . $unicode_q . $option_text;

    // build
    if ($start_page!=0) {
      $start_page_text = "<a href='" . $url . "&start=0'><small>" . _("First") . "</a>&nbsp;</small>
			<a href=" . $url . "&start=" . ($start-$span) . "><small><</a>&nbsp;</small>";
    }

    for($next_page=$begin;($next_page<$total_pages)&&($next_page<$end);$next_page++) {
      if ($next_page == $start_page) {
          $middle_page_text .= "<small>" . ($next_page+1) . "&nbsp;</small>";
      } else {
          $middle_page_text .= "<a href='" . $url . "&start=" . ($next_page*$span) . "'><small>" . ($next_page+1) . "</a>&nbsp;</small>";
      }
    }
    if ( ($start_page != $total_pages-1)  && ($total != 0) ) {
          $end_page_text = "<a href='" . $url . "&start=" . ($start+$span) . "'><small>></a>&nbsp;</small>
                          <a href='" . $url . "&start=" . ($total_pages-1)*$span  . "'><small>" . _("Last") . "</a>&nbsp;</small>";
    }

    $ret .= "<div class='bar_center'>
              " . $start_page_text . "
              " . $middle_page_text . "
              " . $end_page_text . "
             </div>";

    return $ret;
  }
}  


?>