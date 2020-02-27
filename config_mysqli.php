<?php
if(!DEFINED("HTTP_HOST")) DEFINE("HTTP_HOST", $_SERVER["HTTP_HOST"]);

if(!DEFINED("DB_HOST")) DEFINE("DB_HOST", "localhost");
if(!DEFINED("DB_USER")) DEFINE("DB_USER", "user");
if(!DEFINED("DB_PASS")) DEFINE("DB_PASS", "password");
if(!DEFINED("DB_NAME")) DEFINE("DB_NAME", "base");

$_HTTPS = (isset($_SERVER["HTTPS"]) | (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && strtolower(trim($_SERVER["HTTP_X_FORWARDED_PROTO"])) == "https")) ? true : false;
$_url = (isset($_HTTPS) && $_HTTPS == true) ? "https://".$_SERVER["HTTP_HOST"]."/" : "http://".$_SERVER["HTTP_HOST"]."/";
//$url = (isset($_HTTPS) && $_HTTPS == true) ? "https://".$_SERVER["HTTP_HOST"]."/" : 0;
$URL_ID_WM_LOGIN =(isset($_HTTPS) && $_HTTPS == true) ? strtolower("B753AAD6-43D1-4BAA-B95D-A9A00040C92F") : ("34CADC0F-3086-4390-8899-A9A00042087C");

if(!isset($mysqli)) $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if($mysqli->connect_errno) {
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
		echo "Нет соединения с базой данных!<br>".str_ireplace(array(DB_NAME, DB_HOST, DB_USER, "(using password: YES)", "(using password: NO)"), array("DataBase","Host","User","",""), $mysqli->connect_error);
	}else{
		echo '<!DOCTYPE html>';
		echo '<html lang="ru" style="width:100%; height:100%;">';
			echo '<head><meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1251"><title>'.strtoupper(HTTP_HOST).' | Ошибка соединения!</title></head>';
			echo '<body style="width:100%; background:none; margin:0; padding:0; font: 20px/24px Tahoma, Arial, sans-serif;">';
				echo '<div style="width:50%; margin:100px auto; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color:#EE6363; display:block; padding:15px 10px;">';
					echo "Нет соединения с базой данных!<br>".str_ireplace(array(DB_NAME, DB_HOST, DB_USER, "(using password: YES)", "(using password: NO)"), array("DataBase","Host","User","",""), $mysqli->connect_error);
				echo '</div>';
			echo '</body>';
		echo '</html>';
	}
	exit();
}else{
	$mysqli->query("SET NAMES 'cp1251'");
	$mysqli->query("SET CHARACTER SET 'cp1251'");
	$mysqli->query("SET character_set_client='cp1251'");
	$mysqli->query("SET character_set_results='cp1251'");
	$mysqli->query("SET collation_connection='cp1251_general_ci'");
}

if(isset($mysql_queries)) {
	if(!isset($mysqli)) $mysql_queries++;
}else{
	$mysql_queries=1;
}

?>