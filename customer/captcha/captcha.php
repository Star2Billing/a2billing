<?php



include("../lib/customer.defines.php");

$code = MDP_STRING(6);
$_SESSION["captcha_code"] = $code;
$seed = MDP_NUMERIC(6);

$captcha_gd = 1;

if ($captcha_gd)
{
	include('captcha_gd.php');
}
else
{
	include('captcha_non_gd.php');
}

$captcha = new captcha();
$captcha->execute($code, $seed);	




