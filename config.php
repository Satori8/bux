<?php

$bd_host = "localhost";
$bd_base = "base";
$bd_user = "user";
$bd_pass = "password";

$_HTTPS = (isset($_SERVER["HTTPS"]) | (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && strtolower(trim($_SERVER["HTTP_X_FORWARDED_PROTO"])) == "https")) ? true : false;
//$url = (isset($_HTTPS) && $_HTTPS == true) ? "https://".$_SERVER["HTTP_HOST"]."/" : "http://".$_SERVER["HTTP_HOST"]."/";
$url = (isset($_HTTPS) && $_HTTPS == true) ? "https://".$_SERVER["HTTP_HOST"]."/" : 0;
$URL_ID_WM_LOGIN =(isset($_HTTPS) && $_HTTPS == true) ? strtolower("B753AAD6-43D1-4BAA-B95D-A9A00040C92F") : ("34CADC0F-3086-4390-8899-A9A00042087C");

if(!isset($connect_status)) {
	if(!@mysql_connect($bd_host, $bd_user, $bd_pass)) {
		die('<body style="width:100%; background:none; height: 80%; position:absolute;"><span style="width:50%; margin:100px auto; font: bold 1.6em serif; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display: block; padding:20px 0 20px 0;">Ошибка! Нет соединения с базой данных!</span></html>');
	}else{
		$connect_status=true;
	}
	if(!@mysql_select_db($bd_base)) {
		die('<body style="width:100%; background:none; height: 80%; position:absolute;"><span style="width:50%; margin:100px auto; font: bold 1.6em serif; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display: block; padding:20px 0 20px 0;">Ошибка! Нет соединения с базой данных!</span></html>');
		$connect_status=true;
	}

	if(isset($mysql_queries)) {$mysql_queries++;}else{$mysql_queries=1;}
}

mysql_query("set names 'cp1251'");
mysql_query("set character_set_client='cp1251'");
mysql_query("set character_set_results='cp1251'");
mysql_query("set collation_connection='cp1251_general_ci'");

?>