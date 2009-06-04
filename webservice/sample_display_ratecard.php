<?php

/*
   * @return string
   * @param string $url
   * @desc Return string content from a remote file
*/

function open_url($url)
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



$private_key = "Ae87v56zzl34v";
$private_key_md5 = md5($private_key);

$api_url = "http://localhost/~areski/svn/asterisk2billing/trunk/webservice/display_ratecard.php?" .
			"key=$private_key_md5" .
			"&page_url=http://localhost/~areski/svn/asterisk2billing/trunk/webservice/sample_display_ratecard.php" .
			"&field_to_display=t1.destination,t1.dialprefix,t1.rateinitial" .
			"&column_name=Destination,Prefix,Rate/Min&field_type=,,money" .
			"&".$_SERVER['QUERY_STRING'];



// ----------------- USAGE ------------------------- 

#usage:
$content = open_url ($api_url);
print ($content);


