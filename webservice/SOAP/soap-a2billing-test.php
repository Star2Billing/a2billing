<?php

include ("../lib/Class.SOAP-function.php");


$security_key = md5(API_SECURITY_KEY);

$webservice = new SOAP_A2Billing();



$method = 'Get_CustomerGroups';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);



$method = 'Get_Currencies';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);



$method = 'Get_Countries';
echo "\n\nTEST Method : $method \n\n... press key to test\n";
$response = trim(fgets(STDIN));
$arr_result = $webservice -> $method ($security_key);
print_r ($arr_result);
