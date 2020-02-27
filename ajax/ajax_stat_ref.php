<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	global $ajax_json;
	$message_text = false;
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
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($result_text, $message_text) {
	global $ajax_json;
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

/*function escape($value) {
	global $mysqli;
	return $mysqli->real_escape_string($value);
}
*/
function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_ireplace(array("'","`"), "", $mensaje);
	$mensaje = str_ireplace('"', "&#34;", $mensaje);
	$mensaje = str_ireplace("?", "&#063;", $mensaje);
	$mensaje = str_ireplace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

//	$mensaje = str_ireplace("\\", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), ENT_QUOTES, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), ENT_QUOTES, "CP1251");
	$mensaje = str_ireplace("  ", " ", $mensaje);
	$mensaje = str_ireplace("&&", "&", $mensaje);
	$mensaje = str_ireplace("&#063;", "?", $mensaje);
	$mensaje = str_ireplace("&amp;", "&", $mensaje);

	return $mensaje;
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlentities(stripslashes(trim($_SESSION["userPas"]))) : false;

	$id = (isset($_POST["id"]) && is_string($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? intval(limpiarez($_POST["id"])) : false;
	$option = ( isset($_POST["op"]) && is_string($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["op"])) ) ? limpiarez($_POST["op"]) : false;
	$token_post = (isset($_POST["token"]) && is_string($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", limpiarez($_POST["token"]))) ? strtolower(limpiarez($_POST["token"])) : false;
	$security_key = "jbmiym,oi5//bmiyt";

	//if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$sql_user = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode("ERROR", mysql_error()));
		if(mysql_num_rows($sql_user) > 0) {
			$row_user = mysql_fetch_array($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];

			//$sql_user->free();
		}else{
			//$sql_user->free();

		}
	}else{
		$result_text = "ERROR"; $message_text = "Необходимо авторизоваться!";
		exit(my_json_encode($result_text, $message_text));
	}

	if($option == "StatRefCashL1" | $option == "StatRefCashL2" | $option == "StatRefCashL3" | $option == "StatRefCashL4" | $option == "StatRefCashL5") {
		if($option == "StatRefCashL1") {
			$ref_level = 1;
			$ref_level_z = 'ref1';
		}elseif($option == "StatRefCashL2") {
			$ref_level = 2;
			$ref_level_z = 'ref2';
		}elseif($option == "StatRefCashL3") {
			$ref_level = 3;
			$ref_level_z = 'ref3';
		}elseif($option == "StatRefCashL4") {
			$ref_level = 4;
			$ref_level_z = 'ref4';
		}elseif($option == "StatRefCashL5") {
			$ref_level = 5;
			$ref_level_z = 'ref5';
		}

		$token_check = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."StatRefL$ref_level".$security_key));

		if($token_post == false | $token_post != $token_check) {
			$result_text = "ERROR";
			$message_text = "Ошибка доступа, обновите страницу!";
			exit(my_json_encode($result_text, $message_text));
		}else{
			$message_text = false;
			$week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
			$week_arr_ru = array("Вс","Пн","Вт","Ср","Чт","Пт","Сб");
			$month_arr_ru = array("","Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
			$week_ru = array();
			$week_en = array();
			$date_s = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
			$date_e = strtotime(DATE("d.m.Y"));
			$period = (24*60*60);
			$color_arr = array('#508ED3','#009126','#ED5A62','#FF8000','#ED5A62','#FF8000');

			for($i=$date_s; $i<=$date_e; $i=$i+$period) {
				$week_ru[] = $week_arr_ru[strtolower(DATE("w", $i))];
				$week_en[] = $week_arr_en[strtolower(DATE("w", $i))];
			}
			$data_graff = array();

			$sql_stat = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$user_name' AND `type`='$ref_level_z'");
			$numss=mysql_num_rows($sql_stat);
			if($numss > 0) {
				$StatPay = mysql_fetch_array($sql_stat);
			}else{
				$StatPay = array();
			}
			//$sql_stat->free();

			for($i=0; $i<count($week_en); $i++) {
				$data_graff[] = (isset($StatPay[$week_en[$i]]) && $StatPay[$week_en[$i]]>0) ? round($StatPay[$week_en[$i]], 6) : 0;
			}
			$data_graff = implode(", ", $data_graff);
			$series = "{name: 'Рефералы $ref_level уровня', data: [$data_graff], color: '".$color_arr[$ref_level]."'}";
			$categories = isset($week_ru) ? "'".implode("', '", $week_ru)."'" : false;

			$message_text.= '<table class="tables" style="margin:3px auto 1px; width:99%;">';
			$message_text.= '<thead>';
			$message_text.= '<tr>';
				$message_text.= '<th style="">'.$month_arr_ru[DATE("n", time())].'</th>';
				$message_text.= '<th style="">За неделю</th>';
				for($i=$date_s; $i<=$date_e; $i=$i+$period) {
					if(DATE("w", $i)==DATE("w")) {
						$message_text.= '<th style="background:#007FFF; width:10%;">'.$week_arr_ru[strtolower(DATE("w", $i))].'</th>';
					}else{
						$message_text.= '<th style="width:10%;">'.$week_arr_ru[strtolower(DATE("w", $i))].'</th>';
					}
				}
			$message_text.= '</tr>';
			$message_text.= '</thead>';
			$message_text.= '<tbody>';
			$message_text.= '<tr>';
				$message_text.= '<td align="center"><b>'.((isset($StatPay["sum_month"]) && $StatPay["sum_month"]>0) ? '<span class="text-green">'.round($StatPay["sum_month"], 4).'</span>' : 0).'</b></td>';
				$message_text.= '<td align="center"><b>'.((isset($StatPay["sum_week"]) && $StatPay["sum_week"]>0) ? '<span class="text-green">'.round($StatPay["sum_week"], 4).'</span>' : 0).'</b></td>';
				for($i=0; $i<count($week_en); $i++) {
					$message_text.= '<td align="center">'.((isset($StatPay[$week_en[$i]]) && $StatPay[$week_en[$i]]>0) ? '<span class="text-grey">'.round($StatPay[$week_en[$i]], 4).'</span>' : 0).'</td>';
				}
			$message_text.= '</tr>';
			$message_text.= '</tbody>';
			$message_text.= '</table>';

			$message_text.= '<h3 class="sp" style="border:none; font-size:14px; text-align:center; margin-top:15px;">График дохода от рефералов '.$ref_level.' уровня.</h3>';
			$message_text.= '<div style="width:670px; height:250px; margin:0px auto;" id="Statref"></div>';
			$message_text.= "<script type=\"text/javascript\" language=\"JavaScript\">
			Highcharts.chart(\"Statref\", {
				chart: 	{ backgroundColor: false, type: 'line', width: 670, height: 250 }, 
				credits: { enabled: false }, title: { text: '' }, subtitle: { text: '' }, 
				xAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, categories: [$categories] }, 
				yAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, title: { text: '' } }, 
				legend: { enabled: false, layout: 'vertical', align: 'right', verticalAlign: 'middle' }, 
				series: [$series]
			});
			</script>";

			$result_text = "OK";
			exit(my_json_encode($result_text, $message_text));
		}

	}else{
		$result_text = "ERROR"; $message_text = "Option[$option] not found...";
		exit(my_json_encode($result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX ответа.";
exit(my_json_encode($result_text, $message_text));

?>