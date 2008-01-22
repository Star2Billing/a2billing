<?php

/*
 * AJAX page update script
 */
function ajaxRefreshScript($args) {

  global $AJAX_PAGE_REFRESH_TIME;

  $url_args = "?ajax_refresh=1&";
  foreach($args as $key => $value) {
    $url_args .= $key . "=" . $value . "&";
  }
  $url_args = substr($url_args, 0,strlen($url_args)-1);

  $ret = "
    <script type='text/javascript' language='javascript'>

      var http_request = false;

      function makeRequest(url, parameters) {

        http_request = false;

        if (window.XMLHttpRequest) { // Mozilla, Safari,...
          http_request = new XMLHttpRequest();
          if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/xml');
          }
        } 
        else if (window.ActiveXObject) { // IE
          try {
            http_request = new ActiveXObject('Msxml2.XMLHTTP');
          } 
          catch (e) {
            try {
              http_request = new ActiveXObject('Microsoft.XMLHTTP');
            } 
            catch (e) {}
          }
        }
        if (!http_request) {
          return false;
        }
        http_request.onreadystatechange = alertContents;
        http_request.open('GET', url + parameters, true);
        http_request.send(null);
      }

      function alertContents() {

        if (!http_request) {
          return;
        }

        if (http_request.readyState == 4) {
          if (http_request.status == 200) {

            var result = http_request.responseXML;
            if (!result.documentElement && http_request.responseStream) {
              result.load(http_request.responseStream);
            }

            var response = http_request.responseXML.documentElement;

            var nav_menu = '';
            if (response.getElementsByTagName('nav_menu')[0]) {
              nav_menu = response.getElementsByTagName('nav_menu')[0].firstChild.data;
            }
            var nav_submenu = '';
            if (response.getElementsByTagName('nav_submenu')[0]) {
              nav_submenu = response.getElementsByTagName('nav_submenu')[0].firstChild.data;
            }
            var content = '';
            if (response.getElementsByTagName('content')[0]) {
              content = response.getElementsByTagName('content')[0].firstChild.data;
            }

            if (nav_menu) {
              document.getElementById('nav_menu').innerHTML = '';
              document.getElementById('nav_menu').innerHTML = nav_menu;
            }
            if (nav_submenu) {
              document.getElementById('nav_submenu').innerHTML = '';
              document.getElementById('nav_submenu').innerHTML = nav_submenu;
            }
            if (content) {
              document.getElementById('content').innerHTML = '';
              document.getElementById('content').innerHTML = content;
            }
          } 
        }
      }

      function updatePage() {
        makeRequest('" . $_SESSION['ARI_ROOT'] . "', '" . $url_args . "');
      }

      // refresh time in 'minutes:seconds' (0 to inifinity) : (0 to 59)
      var refresh_time='" . $AJAX_PAGE_REFRESH_TIME . "';

      if (document.images){
        var limit=refresh_time.split(\":\");
        limit=limit[0]*60+limit[1]*1;
        var current = limit;
      }

      function beginRefresh(){
        
        if (!document.images) {
          return;
        }
        if (current==1) {
          updatePage();
          current = limit;
        }
        else { 
          current-=1;
        }

        setTimeout(\"beginRefresh()\",1000);
      }

      window.onload=beginRefresh;

    </script>";

  return $ret;
}


?>