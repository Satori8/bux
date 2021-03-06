<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
//sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace('"', "&#34;", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	//$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));
//	$mensaje = str_replace("\\", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), NULL, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
	$mensaje = str_replace("  ", " ", $mensaje);
	$mensaje = str_replace("&amp amp ", "&", $mensaje);
	$mensaje = str_replace("&amp;amp;", "&", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("&#063;", "?", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);

	return $mensaje;
}

function desc_bb($desc) {
	$desc = new bbcode($desc);
	$desc = $desc->get_html();
	$desc = str_replace("&amp;", "&", $desc);
	return $desc;
}

function count_text($count, $text1, $text2, $text3) {
	if($count>=0) {
		if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
			return "$count $text3";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "$count $text1"; break;
				case 2: case 3: case 4: return "$count $text2"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "$count $text3"; break;
			}
		}
	}
}

function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
	$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
	$message_text = false;
	$errfile = str_replace(ROOT_DIR, "", $errfile);
	switch($errno) {
		case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
		case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
		case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
		default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
	}
	$message_text = "$message_text";
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/merchant/func_mysql.php");
	require_once(ROOT_DIR."/method_pay/method_pay_sys.php");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlentities(stripslashes(trim($_SESSION["userPas"]))) : false;
	$my_lastiplog = getRealIP();

	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? intval(limpiarez($_POST["id"])) : false;
	$type_ads = ( isset($_POST["type"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", limpiarez($_POST["type"])) ) ? limpiarez($_POST["type"]) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;

	$cab_skidka = 0; $cab_text = false;
	$user_id = false; $user_wmid = false; $user_wmr = false; $user_money_rb = false; $user_money_rek = false;
	$user_referer_1 = false; $user_referer_2 = false; $user_referer_3 = false;

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$sql_user = mysql_query("SELECT `id`,`username`,`wmid`,`wm_purse`,`money_rb`,`money_rek`,`referer`,`referer2`,`referer3` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_wmid = $row_user["wmid"];
			$user_wmr = $row_user["wm_purse"];
			$user_money_rb = $row_user["money_rb"];
			$user_money_rek = $row_user["money_rek"];
			$user_referer_1 = $row_user["referer"];
			$user_referer_2 = $row_user["referer2"];
			$user_referer_3 = $row_user["referer3"];

			$sql_cab = mysql_query("SELECT * FROM `tb_cabinet` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_cab)>0) {
				$row_cab = mysql_fetch_assoc($sql_cab);

				if($row_cab["status"] == 1 && $user_money_rek >= $row_cab["start_sum"]) {
					$cab_skidka = $row_cab["start_proc"] + (floor(($user_money_rek - $row_cab["start_sum"])/$row_cab["shag_sum"]) * $row_cab["shag_proc"]);
					if($cab_skidka > $row_cab["max_proc"]) $cab_skidka = $row_cab["max_proc"];
					$cab_text = '<tr><td align="left">���� ������ �������������:</td><td align="left">'.$cab_skidka.' %</td></tr>';
				}
			}
		}else{
			$result_text = "ERROR"; $message_text = "������������ �� ���������������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$user_name = false;
	}

	if($type_ads == "sent_emails") {
		if(!DEFINED("SENT_EMAILS_AJAX")) DEFINE("SENT_EMAILS_AJAX", true);
		include_once("ajax_json/ajax_adv_sent_emails.php");
		exit();

	}elseif($type_ads == "articles") {
		if(!DEFINED("ARTICLES_AJAX")) DEFINE("ARTICLES_AJAX", true);
		include_once("ajax_json/ajax_adv_articles.php");
		exit();
	 
	}elseif($type_ads == "quick_mess") {
		if(!DEFINED("PAY_QUICK_MESS")) DEFINE("PAY_QUICK_MESS", true);
		include_once("ajax_json/ajax_adv_quick_mess.php");
		exit();
 
	}elseif($type_ads == "pay_row") {
		if(!DEFINED("PAY_ROW_AJAX")) DEFINE("PAY_ROW_AJAX", true);
		include_once("ajax_json/ajax_adv_pay_row.php");
		exit();

	}else{
		$result_text = "ERROR"; $message_text = "ERROR: ��� ������� �� ���������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "��� ����������� AJAX �������.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>