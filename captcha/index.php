<?php
session_start();
error_reporting (0);

function clean_url($url) {
	if ($url === false) return;
	$url = str_replace("http://", "", $url);
	$url = str_replace("https://", "", $url);
	if(strtolower(substr($url, 0, 4)) == "www.")  $url = substr($url, 4);
	$url = explode("/", $url);
	$url = reset($url);
	$url = explode(":", $url);
	$url = reset($url);
	return $url;
}

$allowed_symbols[1] = "0123456789+-";
$allowed_symbols[2] = "23456789abcdeghkmnpqsuvxyz";
$captcha_type = ( isset($_SESSION["captcha_type_serf"]) && preg_match("|^[1-2]{1}$|", trim($_SESSION["captcha_type_serf"])) ) ? trim($_SESSION["captcha_type_serf"]) : false;
$keystring = ( isset($_SESSION["captcha_serf_sess"]) && $captcha_type!==false && preg_match("|^[$allowed_symbols[$captcha_type]]*$|", trim($_SESSION["captcha_serf_sess"])) ) ? strtolower(trim($_SESSION["captcha_serf_sess"])) : false;

if( 
	!isset($_SESSION["captcha_load"]) | !isset($_SERVER["HTTP_REFERER"]) | 
	(isset($_SERVER["HTTP_REFERER"]) && clean_url($_SERVER["HTTP_REFERER"]) != clean_url($_SERVER["HTTP_HOST"])) 
){
	$keystring = false;
	$captcha_type = mt_rand(1,2);
}

require_once("captcha_serf/captcha.php");
$captcha = new GEN_CAPTCHA($keystring);

if(isset($_SESSION["captcha_load"])) unset($_SESSION["captcha_load"]);

$CaptchaKeyString = $captcha->getKeyString();
if($CaptchaKeyString!=false) {
	$_SESSION["captcha_serf_sess"] = md5($captcha->getKeyString());
}else{
	if(isset($_SESSION["captcha_type_serf"])) unset($_SESSION["captcha_type_serf"]);
	if(isset($_SESSION["captcha_serf_sess"])) unset($_SESSION["captcha_serf_sess"]);
}
?>