<?php 
session_start();
error_reporting(0);
$allowed_symbols[1] = "0123456789+-";
$captcha_type = 1;
$keystring = genkeystring();
if( !isset($_SERVER["HTTP_REFERER"]) | (isset($_SERVER["HTTP_REFERER"]) && clean_url($_SERVER["HTTP_REFERER"]) != clean_url($_SERVER["HTTP_HOST"])) ) {
    $keystring = false;
}

require_once("captcha_site/captcha.php");
$captcha = new GEN_CAPTCHA($keystring);
$CaptchaKeyString = $captcha->getKeyString();
if( $CaptchaKeyString != false ) {
    $_SESSION["captcha_site_sess"] = md5($captcha->getKeyString());
}else{
    if( isset($_SESSION["captcha_site_sess"]) ) {
        unset($_SESSION["captcha_site_sess"]);
    }

}

function clean_url($url){
    if( $url === false ) {
        return NULL;
    }

    $url = str_replace("http://", "", $url);
    $url = str_replace("https://", "", $url);
    if( strtolower(substr($url, 0, 4)) == "www." ) {
        $url = substr($url, 4);
    }

    $url = explode("/", $url);
    $url = reset($url);
    $url = explode(":", $url);
    $url = reset($url);
    return $url;
}

function genKeyString(){
    $znak_arr = array( "+", "-" );
    $sign_symbols = $znak_arr[mt_rand(0, count($znak_arr) - 1)];
    if( $sign_symbols == "+" ) {
        $first_symbols = mt_rand(1, 7);
        $last_symbols = mt_rand(1, 8 - $first_symbols);
    }else{
        if( $sign_symbols == "-" ) {
            $first_symbols = mt_rand(2, 8);
            $last_symbols = mt_rand(1, $first_symbols - 1);
        }

    }

    $keystring = $first_symbols . $sign_symbols . $last_symbols;
    return $keystring;
}

echo genKeyString()
?>