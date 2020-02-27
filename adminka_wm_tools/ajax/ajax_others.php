<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("DOC_ROOT"))  DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("ROOT_DIR"))  DEFINE("ROOT_DIR", DOC_ROOT);
if(!DEFINED("ADMINKA"))   DEFINE("ADMINKA", true);
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	global $ajax_json, $message_text;

	$errfile = str_ireplace(ROOT_DIR, false, $errfile);
	switch($errno) {
		case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
		case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
		case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
		default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
	}
	exit(my_json_encode("ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_ireplace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function arrIconv($from, $to, $obj){
	if(is_array($obj) | is_object($obj)) {
		foreach ($obj as &$val){
			$val = arrIconv($from, $to, $val);
		}
		return $obj;
        }else{
		return iconv($from, $to, $obj);
	}
}

function my_json_encode($result_text, $message_text) {
	global $ajax_json;
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => arrIconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function escape($value) {
	global $mysqli;
	return $mysqli->real_escape_string($value);
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_ireplace(array("`", "$", "&&", "  "), array("'", "&#036;", "&", " "), $mensaje);

	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars($mensaje, ENT_QUOTES, "CP1251", false));
	$mensaje = htmlspecialchars($mensaje, ENT_QUOTES, "CP1251", false);

	return trim($mensaje);
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config_mysqli.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/merchant/func_mysqli.php");

	$user_name = (isset($_SESSION["userLog_a"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog_a"]))) ? escape(htmlentities(stripslashes(trim($_SESSION["userLog_a"])))) : false;
	$user_pass = (isset($_SESSION["userPas_a"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas_a"]))) ? escape(htmlentities(stripslashes(trim($_SESSION["userPas_a"])))) : false;
	$my_lastiplog = getRealIP();

	$id = (isset($_POST["id"]) && is_string($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? escape(intval(limpiarez($_POST["id"]))) : false;
	$option = ( isset($_POST["op"]) && is_string($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,30}$|", limpiarez($_POST["op"])) ) ? limpiarez($_POST["op"]) : false;
	$type = ( isset($_POST["type"]) && is_string($_POST["type"]) && preg_match("|^[a-zA-Z0-9\-_]{1,30}$|", limpiarez($_POST["type"])) ) ? limpiarez($_POST["type"]) : false;
	$token_post = (isset($_POST["token"]) && is_string($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", limpiarez($_POST["token"]))) ? strtolower(limpiarez($_POST["token"])) : false;
	$security_key = "AsDiModI*N^I&uwK(*An#*hg@if%YST630nlkj7p0U?";

	if(isset($_SESSION["userLog"], $_SESSION["userPas"])) {
		$sql_user = $mysqli->query("SELECT `id`,`username`,`wmid`,`wm_purse`,`user_status` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode("ERROR", $mysqli->error));
		if($sql_user->num_rows > 0) {
			$row_user = $sql_user->fetch_assoc();
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_wm_id = $row_user["wmid"];
			$user_wm_purse = $row_user["wm_purse"];
			$user_status = $row_user["user_status"];
			$sql_user->free();

			if($user_status != 1) {
				$result_text = "ERROR";
				$message_text = "Вы не являетесь администратором, доступ к этому разделу закрыт!";
				exit(my_json_encode($result_text, $message_text));
			}
		}else{
			$sql_user->free();
			if(isset($_SESSION)) session_destroy();

			$result_text = "ERROR";
			$message_text = '<script>setTimeout(function(){location.href = "/login";},3000);</script>';
			$message_text.= "Пользователь не идентифицирован!";
			exit(my_json_encode($result_text, $message_text));
		}
	}else{
		if(isset($_SESSION)) session_destroy();

		$result_text = "ERROR";
		$message_text = '<script>setTimeout(function(){location.href = "/login";},3000);</script>';
		$message_text.= "Необходимо авторизоваться!";
		exit(my_json_encode($result_text, $message_text));
	}

	if($type == "notif-not-active-users") {
		if(!DEFINED("NOTIF_NOT_ACTIVE_USERS")) DEFINE("NOTIF_NOT_ACTIVE_USERS", true);
		include_once("ajax_other/notif_not_active_users.php");

	}else{
		$result_text = "ERROR"; $message_text = "Тип функции [$type] не определен.";
		exit(my_json_encode($result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($result_text, $message_text));
}

$result_text = "ERROR"; $message_text = "Нет корректного AJAX ответа.";
exit(my_json_encode($result_text, $message_text));

?>