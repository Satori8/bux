<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("CABINET")) DEFINE("CABINET", true);
sleep(0);

$js_result = array();
$js_result["result"] = "";
$js_result["status"] = "";
$js_result["message"] = "";
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/cabinet/cab_func.php");
	require(ROOT_DIR."/merchant/func_mysql.php");

	function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
		$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
		$errfile = str_replace($_SERVER["DOCUMENT_ROOT"], "", $errfile);

		switch ($errno) {
			case(1): $js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Fatal error[$errno]: $errstr in line $errline in $errfile")) : "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
			case(2): $js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Warning[$errno]: $errstr in line $errline in $errfile")) : "Warning[$errno]: $errstr in line $errline in $errfile"; break;
			case(8): $js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Notice[$errno]: $errstr in line $errline in $errfile")) : "Notice[$errno]: $errstr in line $errline in $errfile"; break;
			default: $js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "[$errno] $errstr in line $errline in $errfile")) : "[$errno] $errstr in line $errline in $errfile"; break;
		}
		exit(json_encode_cp1251($js_result));
	}
	$set_error_handler = set_error_handler('myErrorHandler', E_ALL);

	$auth_user = auth_log_pass(2);

	if($auth_user["status"] == "FALSE") {
		$js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Необходимо авторизоваться!")) : "Необходимо авторизоваться!";
		exit(json_encode_cp1251($js_result));

	}elseif($auth_user["status"] == "TRUE") {
		$user_id = $auth_user["user_id"];
		$username = $auth_user["user_log"];

		$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? intval(limpiarez($_POST["id"])) : false;
		$type = ( isset($_POST["type"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["type"])) ) ? limpiarez($_POST["type"]) : false;
		$claimstext = (isset($_POST["claimstext"])) ? limitatexto(limpiarez($_POST["claimstext"]), 100) : false;
		$my_lastiplog = getRealIP(); 	

		if($id!=false && $type=="serf") {
			$TABLES = "tb_ads_dlink";
		}elseif($id!=false && $type=="youtube") {
			$TABLES = "tb_ads_youtube";
		}elseif($id!=false && $type=="mails") {
			$TABLES = "tb_ads_mails";
		}elseif($id!=false && $type=="tests") {
			$TABLES = "tb_ads_tests";
		}elseif($id!=false && $type=="pay_visits") {
			$TABLES = "tb_ads_pay_vis";
		}else{
			$js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Тип рекламы не определен.<br>")) : "Тип рекламы не определен.<br>";
			exit(json_encode_cp1251($js_result));
		}

		$sql_ads = mysql_query("SELECT `id` FROM `".$TABLES."` WHERE `id`='$id' AND `status`='1'") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));
		if(mysql_num_rows($sql_ads)>0) {
			$row_ads = mysql_fetch_assoc($sql_ads);
			$id = $row_ads["id"];

			$sql_clms = mysql_query("SELECT `id` FROM `tb_ads_claims` WHERE `ident`='$id' AND `type`='$type' AND `username`='$username'") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));
			if(mysql_num_rows($sql_clms)>0) {
				$js_result = ($ajax_json=="json") ? array("result" => "OK", "message" => iconv("CP1251", "UTF-8", "Вы уже оставляли жалобу.")) : "Вы уже оставляли жалобу.";
				exit(json_encode_cp1251($js_result));

			}elseif(strlen($claimstext)<10) {
				$js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Укажите минимум 10 символов текста для жалобы.<br>")) : "Укажите минимум 10 символов текста для жалобы.<br>";
				exit(json_encode_cp1251($js_result));

			}else{
				mysql_query("INSERT INTO `tb_ads_claims` (`ident`,`type`,`username`,`claims`,`time`,`ip`) 
				VALUES('$id','$type','$username','$claimstext','".time()."','$my_lastiplog')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				mysql_query("UPDATE `".$TABLES."` SET `claims`=`claims`+'1' WHERE `id`='$id'") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				$js_result = ($ajax_json=="json") ? array("result" => "OK", "message" => iconv("CP1251", "UTF-8", "Жалоба отправлена.")) : "Жалоба отправлена.";
				exit(json_encode_cp1251($js_result));
			}
		}else{
			$js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Реклама на данный момент недоступна, попробуйте позже!")) : "Рекламная площадка на данный момент недоступен, попробуйте позже!";
			exit(json_encode_cp1251($js_result));
		}
	}else{
		$js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Необходимо авторизоваться!")) : "Необходимо авторизоваться!";
		exit(json_encode_cp1251($js_result));
	}
}else{
	$js_result = ($ajax_json=="json") ? array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Ошибка! Не корректный запрос.")) : "Ошибка! Не корректный запрос.";
	exit(json_encode_cp1251($js_result));
}

?>