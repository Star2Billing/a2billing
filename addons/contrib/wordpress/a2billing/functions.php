<?php

/*
   * @return string
   * @param string $url
   * @desc Return string content from a remote file
*/

function a2b_open_url($url)
{
    $ch = curl_init();

    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);

    ob_start();

    curl_exec ($ch);
    curl_close ($ch);
    $string = ob_get_contents();

    ob_end_clean();

    return $string;
}

function display_to_table ($content)
{
    $content = str_replace('"', '', $content);
    $lines = explode("\n", $content);

    $to_display = date().'<table width="70%" border="0" align="center"> <tr> <th>Destination</th> <th>Prefix</th> <th>Price</th> </tr>';

    $count_line = 0;

    foreach ($lines as $line) {

        $line = trim($line);
        if ($count_line > 500)
            break;

        if (strlen($line) > 0) {
            $count_line ++;
            $to_display .= "<tr>";
            $elements = explode(',', $line);
            if (isset($elements[0]))
                $to_display .= "<td>".$elements[0]."</td>";
            if (isset($elements[1]))
                $to_display .= "<td>".$elements[1]."</td>";
            if (isset($elements[2]))
                $to_display .= "<td>".$elements[2]."</td>";
            $to_display .= "</tr>";
        }
    }
    $to_display .= '</table>'.date();

    return $to_display;

}

function get_curPageURL($show_get = 0)
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }

    $pos = strpos($pageURL, '?');

    if ($show_get || ($pos === false))
        return $pageURL;

    $pageURL = substr($pageURL, 0, strpos($pageURL, '?'));

    return $pageURL;
}

function curPageName()
{
    return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
