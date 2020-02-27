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
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
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

function GetHost2($url) {
	$host = str_replace("www.www.","www.", trim($url));
	$host = parse_url($host);
	$host = isset($host["host"]) ? $host["host"] : array_shift(explode('/', $host["path"], 2));
	if(in_array("www", explode(".", $host))) {
		$just_domain = explode("www.", $host);
		return $just_domain[1];
	}else{
		return $host;
	}
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
		$my_lastiplog = getRealIP();

		$sql_user = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];

			if($option == "AddBL") {
				$user_bl = (isset($_POST["user_bl"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["user_bl"]))) ? htmlspecialchars(trim($_POST["user_bl"])) : false;

				$serf_usr = ( isset($_POST["serf_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["serf_usr"])) ) ? intval(trim($_POST["serf_usr"])) : false;
				$aserf_usr = ( isset($_POST["aserf_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["aserf_usr"])) ) ? intval(trim($_POST["aserf_usr"])) : false;
				$mails_usr = ( isset($_POST["mails_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["mails_usr"])) ) ? intval(trim($_POST["mails_usr"])) : false;
				$tests_usr = ( isset($_POST["tests_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tests_usr"])) ) ? intval(trim($_POST["tests_usr"])) : false;
				$tasks_usr = ( isset($_POST["tasks_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tasks_usr"])) ) ? intval(trim($_POST["tasks_usr"])) : false;
				$chat = ( isset($_POST["chat"]) && preg_match("|^[0-1]{1}$|", trim($_POST["chat"])) ) ? intval(trim($_POST["chat"])) : false;
				$out_mail = ( isset($_POST["out_mail"]) && preg_match("|^[0-1]{1}$|", trim($_POST["out_mail"])) ) ? intval(trim($_POST["out_mail"])) : false;

				$serf_adv = ( isset($_POST["serf_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["serf_adv"])) ) ? intval(trim($_POST["serf_adv"])) : false;
				$aserf_adv = ( isset($_POST["aserf_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["aserf_adv"])) ) ? intval(trim($_POST["aserf_adv"])) : false;
				$mails_adv = ( isset($_POST["mails_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["mails_adv"])) ) ? intval(trim($_POST["mails_adv"])) : false;
				$tests_adv = ( isset($_POST["tests_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tests_adv"])) ) ? intval(trim($_POST["tests_adv"])) : false;
				$tasks_adv = ( isset($_POST["tasks_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tasks_adv"])) ) ? intval(trim($_POST["tasks_adv"])) : false;
				$auction = ( isset($_POST["auction"]) && preg_match("|^[0-1]{1}$|", trim($_POST["auction"])) ) ? intval(trim($_POST["auction"])) : false;
				$birja = ( isset($_POST["birja"]) && preg_match("|^[0-1]{1}$|", trim($_POST["birja"])) ) ? intval(trim($_POST["birja"])) : false;

				$check_usr = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='$user_bl'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$check_usr_bl = mysql_query("SELECT `id` FROM `tb_black_list` WHERE `user_name`='$user_name' AND `user_bl`='$user_bl' AND `type`='usr_adv'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				if($user_bl == false) {
					$result_text = "ERROR"; $message_text = "Необходимо указать логин пользователя!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

				$user_id_bl = mysql_num_rows($check_usr)>0 ? mysql_result($check_usr,0,0) : false;
				$user_bl = mysql_num_rows($check_usr)>0 ? mysql_result($check_usr,0,1) : $user_bl;

				if(mysql_num_rows($check_usr)==0) {
					$result_text = "ERROR"; $message_text = "Пользователь <u>$user_bl</u> не зарегистрирован на проекте!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(strtolower($user_name) == strtolower($user_bl)) {
					$result_text = "ERROR"; $message_text = "Запрещено добавлять себя в черный список!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(
					$serf_usr == false && $aserf_usr == false && $mails_usr == false && $tests_usr == false && 
					$tasks_usr == false && $chat == false && $out_mail == false && 
					$serf_adv == false && $aserf_adv == false && $mails_adv == false && $tests_adv == false && 
					$tasks_adv == false && $auction == false && $birja == false
				) {
					$result_text = "ERROR"; $message_text = "Необходимо указать хотя бы один тип блокировки!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(mysql_num_rows($check_usr_bl)>0) {
					$result_text = "ERROR"; $message_text = "Пользователь <u>$user_bl</u> уже находится в черном списке!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}else{
					mysql_query("INSERT INTO `tb_black_list` (`user_name`, `user_bl`, `user_id_bl`, `type`, `serf_usr`, `aserf_usr`, `mails_usr`, `tests_usr`, `tasks_usr`, `serf_adv`, `aserf_adv`, `mails_adv`, `tests_adv`, `tasks_adv`, `chat`, `out_mail`, `auction`, `birja`, `date`, `ip`) 
					VALUES('$user_name', '$user_bl', '$user_id_bl', 'usr_adv', '$serf_usr', '$aserf_usr', '$mails_usr', '$tests_usr', '$tasks_usr', '$serf_adv', '$aserf_adv', '$mails_adv', '$tests_adv', '$tasks_adv', '$chat', '$out_mail', '$auction', '$birja', '".DATE("d.m.Yг. H:i", time())."', '$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$result_text = "OK"; $message_text = "Пользователь <u>$user_bl</u> успешно добавлен в ЧС!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($option == "LoadBL") {
				$result_text = "OK";
				$message_text = "";

				$sql_bl = mysql_query("SELECT * FROM `tb_black_list` WHERE `user_name`='$user_name' AND `type`='usr_adv' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_bl)>0) {
					while ($row_bl = mysql_fetch_assoc($sql_bl)) {
						$message_text.= '<tr id="trdel_a_'.$row_bl["id"].'">';
							$message_text.= '<td class="chs fleft" rowspan="2"><b>'.$row_bl["user_bl"].'</b><br><br>'.$row_bl["date"].'</td>';
							$message_text.= '<td class="chs"><span id="chs_e_1_'.$row_bl["id"].'" class="'.($row_bl["serf_usr"]==1 ? "chs_act_1" : "chs_1").'" onClick="EditChs(1, '.$row_bl["id"].');" title="Скрыть для пользователя Ваш серфинг"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_2_'.$row_bl["id"].'" class="'.($row_bl["aserf_usr"]==1 ? "chs_act_2" : "chs_2").'" onClick="EditChs(2, '.$row_bl["id"].');" title="Скрыть для пользователя Ваш авто-серфинг"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_3_'.$row_bl["id"].'" class="'.($row_bl["mails_usr"]==1 ? "chs_act_3" : "chs_3").'" onClick="EditChs(3, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши письма"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_4_'.$row_bl["id"].'" class="'.($row_bl["tests_usr"]==1 ? "chs_act_4" : "chs_4").'" onClick="EditChs(4, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши тесты"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_5_'.$row_bl["id"].'" class="'.($row_bl["tasks_usr"]==1 ? "chs_act_5" : "chs_5").'" onClick="EditChs(5, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши задания"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_6_'.$row_bl["id"].'" class="'.($row_bl["chat"]==1 ? "chs_act_6" : "chs_6").'" onClick="EditChs(6, '.$row_bl["id"].');" title="Скрыть от Вас его сообщения в чате"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_7_'.$row_bl["id"].'" class="'.($row_bl["out_mail"]==1 ? "chs_act_7" : "chs_7").'" onClick="EditChs(7, '.$row_bl["id"].');" title="Запретить отправку сообщений Вам на внутреннюю почту"></td>';
							$message_text.= '<td class="chs" rowspan="2"><span class="sub-blue160" onClick="DelBL(\''.$row_bl["id"].'\', \''.$row_bl["user_bl"].'\');" style="float:none; margin: 0 auto;" title="Удалить из ЧС">Удалить из ЧС</span></td>';
						$message_text.= '</tr>';
						$message_text.= '<tr id="trdel_b_'.$row_bl["id"].'">';
							$message_text.= '<td class="chs"><span id="chs_e_8_'.$row_bl["id"].'" class="'.($row_bl["serf_adv"]==1 ? "chs_act_8" : "chs_8").'" onClick="EditChs(8, '.$row_bl["id"].');" title="Скрыть для Вас серфинг от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_9_'.$row_bl["id"].'" class="'.($row_bl["aserf_adv"]==1 ? "chs_act_9" : "chs_9").'" onClick="EditChs(9, '.$row_bl["id"].');" title="Скрыть для Вас авто-серфинг от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_10_'.$row_bl["id"].'" class="'.($row_bl["mails_adv"]==1 ? "chs_act_10" : "chs_10").'" onClick="EditChs(10, '.$row_bl["id"].');" title="Скрыть для Вас письма от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_11_'.$row_bl["id"].'" class="'.($row_bl["tests_adv"]==1 ? "chs_act_11" : "chs_11").'" onClick="EditChs(11, '.$row_bl["id"].');" title="Скрыть для Вас тесты от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_12_'.$row_bl["id"].'" class="'.($row_bl["tasks_adv"]==1 ? "chs_act_12" : "chs_12").'" onClick="EditChs(12, '.$row_bl["id"].');" title="Скрыть для Вас задания от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_13_'.$row_bl["id"].'" class="'.($row_bl["auction"]==1 ? "chs_act_13" : "chs_13").'" onClick="EditChs(13, '.$row_bl["id"].');" title="Скрыть для Вас на аукционе рефералов от пользователя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_14_'.$row_bl["id"].'" class="'.($row_bl["birja"]==1 ? "chs_act_14" : "chs_14").'" onClick="EditChs(14, '.$row_bl["id"].');" title="Скрыть для Вас на бирже рефералов от пользователя"></td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr>';
						$message_text.= '<td class="chs fleft" colspan="9"><b>Нет данных</b></td>';
					$message_text.= '</tr>';
				}

				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($option == "EditBL") {
				$id_bl = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? htmlspecialchars(trim($_POST["id"])) : false;

				$serf_usr = ( isset($_POST["serf_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["serf_usr"])) ) ? intval(trim($_POST["serf_usr"])) : false;
				$aserf_usr = ( isset($_POST["aserf_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["aserf_usr"])) ) ? intval(trim($_POST["aserf_usr"])) : false;
				$mails_usr = ( isset($_POST["mails_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["mails_usr"])) ) ? intval(trim($_POST["mails_usr"])) : false;
				$tests_usr = ( isset($_POST["tests_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tests_usr"])) ) ? intval(trim($_POST["tests_usr"])) : false;
				$tasks_usr = ( isset($_POST["tasks_usr"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tasks_usr"])) ) ? intval(trim($_POST["tasks_usr"])) : false;
				$chat = ( isset($_POST["chat"]) && preg_match("|^[0-1]{1}$|", trim($_POST["chat"])) ) ? intval(trim($_POST["chat"])) : false;
				$out_mail = ( isset($_POST["out_mail"]) && preg_match("|^[0-1]{1}$|", trim($_POST["out_mail"])) ) ? intval(trim($_POST["out_mail"])) : false;

				$serf_adv = ( isset($_POST["serf_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["serf_adv"])) ) ? intval(trim($_POST["serf_adv"])) : false;
				$aserf_adv = ( isset($_POST["aserf_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["aserf_adv"])) ) ? intval(trim($_POST["aserf_adv"])) : false;
				$mails_adv = ( isset($_POST["mails_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["mails_adv"])) ) ? intval(trim($_POST["mails_adv"])) : false;
				$tests_adv = ( isset($_POST["tests_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tests_adv"])) ) ? intval(trim($_POST["tests_adv"])) : false;
				$tasks_adv = ( isset($_POST["tasks_adv"]) && preg_match("|^[0-1]{1}$|", trim($_POST["tasks_adv"])) ) ? intval(trim($_POST["tasks_adv"])) : false;
				$auction = ( isset($_POST["auction"]) && preg_match("|^[0-1]{1}$|", trim($_POST["auction"])) ) ? intval(trim($_POST["auction"])) : false;
				$birja = ( isset($_POST["birja"]) && preg_match("|^[0-1]{1}$|", trim($_POST["birja"])) ) ? intval(trim($_POST["birja"])) : false;

				$check_id_bl = mysql_query("SELECT `id` FROM `tb_black_list` WHERE `id`='$id_bl' AND `user_name`='$user_name' AND `type`='usr_adv'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($check_id_bl)==0) {
					$result_text = "ERROR"; $message_text = "Нет данных о блокировке с ID: $id_bl";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(
					$serf_usr == false && $aserf_usr == false && $mails_usr == false && $tests_usr == false && 
					$tasks_usr == false && $chat == false && $out_mail == false && 
					$serf_adv == false && $aserf_adv == false && $mails_adv == false && $tests_adv == false && 
					$tasks_adv == false && $auction == false && $birja == false
				) {
					$result_text = "ERROR"; $message_text = "Необходимо указать хотя бы один тип блокировки!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}else{
					mysql_query("UPDATE `tb_black_list` SET 
						`serf_usr`='$serf_usr', `aserf_usr`='$aserf_usr', `mails_usr`='$mails_usr', `tests_usr`='$tests_usr', `tasks_usr`='$tasks_usr', 
						`serf_adv`='$serf_adv', `aserf_adv`='$aserf_adv', `mails_adv`='$mails_adv', `tests_adv`='$tests_adv', `tasks_adv`='$tasks_adv', 
						`chat`='$chat', `out_mail`='$out_mail', `auction`='$auction', `birja`='$birja', `ip`='$my_lastiplog' 
					WHERE `id`='$id_bl' AND `user_name`='$user_name' AND `type`='usr_adv'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$result_text = "OK"; $message_text = "";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($option == "SearchBL") {
				$user_bl = (isset($_POST["user_search"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["user_search"]))) ? htmlspecialchars(trim($_POST["user_search"])) : false;

				$result_text = "OK";
				$message_text = "";

				$sql_bl = mysql_query("SELECT * FROM `tb_black_list` WHERE `user_name`='$user_name' AND `user_bl`='$user_bl' AND `type`='usr_adv' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_bl)>0) {
					while ($row_bl = mysql_fetch_assoc($sql_bl)) {
						$message_text.= '<tr id="trdel_a_'.$row_bl["id"].'">';
							$message_text.= '<td class="chs fleft" rowspan="2"><b>'.$row_bl["user_bl"].'</b><br><br>'.$row_bl["date"].'</td>';
							$message_text.= '<td class="chs"><span id="chs_e_1_'.$row_bl["id"].'" class="'.($row_bl["serf_usr"]==1 ? "chs_act_1" : "chs_1").'" onClick="EditChs(1, '.$row_bl["id"].');" title="Скрыть для пользователя Ваш серфинг"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_2_'.$row_bl["id"].'" class="'.($row_bl["aserf_usr"]==1 ? "chs_act_2" : "chs_2").'" onClick="EditChs(2, '.$row_bl["id"].');" title="Скрыть для пользователя Ваш авто-серфинг"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_3_'.$row_bl["id"].'" class="'.($row_bl["mails_usr"]==1 ? "chs_act_3" : "chs_3").'" onClick="EditChs(3, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши письма"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_4_'.$row_bl["id"].'" class="'.($row_bl["tests_usr"]==1 ? "chs_act_4" : "chs_4").'" onClick="EditChs(4, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши тесты"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_5_'.$row_bl["id"].'" class="'.($row_bl["tasks_usr"]==1 ? "chs_act_5" : "chs_5").'" onClick="EditChs(5, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши задания"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_6_'.$row_bl["id"].'" class="'.($row_bl["chat"]==1 ? "chs_act_6" : "chs_6").'" onClick="EditChs(6, '.$row_bl["id"].');" title="Скрыть от Вас его сообщения в чате"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_7_'.$row_bl["id"].'" class="'.($row_bl["out_mail"]==1 ? "chs_act_7" : "chs_7").'" onClick="EditChs(7, '.$row_bl["id"].');" title="Запретить отправку сообщений Вам на внутреннюю почту"></td>';
							$message_text.= '<td class="chs" rowspan="2"><span class="sub-orange160" onClick="DelBL(\''.$row_bl["id"].'\', \''.$row_bl["user_bl"].'\');" style="float:none; margin: 0 auto;" title="Удалить из ЧС">Удалить из ЧС</span></td>';
						$message_text.= '</tr>';
						$message_text.= '<tr id="trdel_b_'.$row_bl["id"].'">';
							$message_text.= '<td class="chs"><span id="chs_e_8_'.$row_bl["id"].'" class="'.($row_bl["serf_adv"]==1 ? "chs_act_8" : "chs_8").'" onClick="EditChs(8, '.$row_bl["id"].');" title="Скрыть для Вас серфинг от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_9_'.$row_bl["id"].'" class="'.($row_bl["aserf_adv"]==1 ? "chs_act_9" : "chs_9").'" onClick="EditChs(9, '.$row_bl["id"].');" title="Скрыть для Вас авто-серфинг от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_10_'.$row_bl["id"].'" class="'.($row_bl["mails_adv"]==1 ? "chs_act_10" : "chs_10").'" onClick="EditChs(10, '.$row_bl["id"].');" title="Скрыть для Вас письма от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_11_'.$row_bl["id"].'" class="'.($row_bl["tests_adv"]==1 ? "chs_act_11" : "chs_11").'" onClick="EditChs(11, '.$row_bl["id"].');" title="Скрыть для Вас тесты от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_12_'.$row_bl["id"].'" class="'.($row_bl["tasks_adv"]==1 ? "chs_act_12" : "chs_12").'" onClick="EditChs(12, '.$row_bl["id"].');" title="Скрыть для Вас задания от рекламодателя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_13_'.$row_bl["id"].'" class="'.($row_bl["auction"]==1 ? "chs_act_13" : "chs_13").'" onClick="EditChs(13, '.$row_bl["id"].');" title="Скрыть для Вас на аукционе рефералов от пользователя"></td>';
							$message_text.= '<td class="chs"><span id="chs_e_14_'.$row_bl["id"].'" class="'.($row_bl["birja"]==1 ? "chs_act_14" : "chs_14").'" onClick="EditChs(14, '.$row_bl["id"].');" title="Скрыть для Вас на бирже рефералов от пользователя"></td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr>';
						$message_text.= '<td class="chs fleft" colspan="9"><b>Пользователь '.$user_bl.' не найден!</b></td>';
					$message_text.= '</tr>';
				}

				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($option == "DelBL") {
				$id_bl = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? htmlspecialchars(trim($_POST["id"])) : false;

				$sql_bl = mysql_query("SELECT `id` FROM `tb_black_list` WHERE `id`='$id_bl' AND `user_name`='$user_name' AND `type`='usr_adv' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_bl)>0) {
					mysql_query("DELETE FROM `tb_black_list` WHERE `id`='$id_bl' AND `user_name`='$user_name' AND `type`='usr_adv'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$result_text = "OK"; $message_text = "Удалено";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					$result_text = "ERROR"; $message_text = "Ошибка! Нет блокировки с ID: $id_bl";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($option == "AddUrlBL") {
				$url_bl = (isset($_POST["url_bl"]) && GetHost2(htmlspecialchars($_POST["url_bl"]))!==false) ? GetHost2(htmlspecialchars($_POST["url_bl"])) : false;

				$check_url_bl = mysql_query("SELECT `id` FROM `tb_black_list` WHERE `user_name`='$user_name' AND `type`='url' AND `url`='$url_bl'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				if($url_bl == false) {
					$result_text = "ERROR"; $message_text = "Необходимо указать URL сайта!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(count(explode(".", $url_bl))<2) {
					$result_text = "ERROR"; $message_text = "URL сайта указано не корректно!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(mysql_num_rows($check_url_bl)>0) {
					$result_text = "ERROR"; $message_text = "URL сайта <u>$url_bl</u> уже находится в черном списке!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}else{
					mysql_query("INSERT INTO `tb_black_list` (`user_name`, `user_bl`, `type`, `url`, `date`, `ip`) 
					VALUES('$user_name', '', 'url', '$url_bl', '".DATE("d.m.Yг. H:i", time())."', '$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$result_text = "OK"; $message_text = "URL сайта <u>$url_bl</u> успешно добавлено в ЧС!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($option == "LoadUrlBL") {
				$result_text = "OK";
				$message_text = "";

				$sql_bl = mysql_query("SELECT * FROM `tb_black_list` WHERE `user_name`='$user_name' AND `type`='url' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_bl)>0) {
					while ($row_bl = mysql_fetch_assoc($sql_bl)) {
						$message_text.= '<tr id="trdel_'.$row_bl["id"].'">';
							$message_text.= '<td class="chs fleft" style="text-align:left;"><b>'.$row_bl["url"].'</b></td>';
							$message_text.= '<td class="chs"><span class="sub-blue160" onClick="DelUrlBL(\''.$row_bl["id"].'\', \''.$row_bl["url"].'\');" style="float:none; margin: 0 auto;" title="Удалить из ЧС">Удалить из ЧС</span></td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr>';
						$message_text.= '<td class="chs fleft" colspan="2"><b>Нет данных</b></td>';
					$message_text.= '</tr>';
				}

				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($option == "SearchUrlBL") {
				$url_bl = (isset($_POST["url_search"]) && GetHost2(htmlspecialchars($_POST["url_search"]))!==false) ? GetHost2(htmlspecialchars($_POST["url_search"])) : false;

				if($url_bl == false) {
					$result_text = "ERROR"; $message_text = "Необходимо указать URL сайта!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif(count(explode(".", $url_bl))<2) {
					$result_text = "ERROR"; $message_text = "URL сайта указано не корректно!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}else{
					$result_text = "OK";
					$message_text = "";

					$sql_bl = mysql_query("SELECT * FROM `tb_black_list` WHERE `user_name`='$user_name' AND `type`='url' AND `url`='$url_bl' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					if(mysql_num_rows($sql_bl)>0) {
						while ($row_bl = mysql_fetch_assoc($sql_bl)) {
							$message_text.= '<tr id="trdel_'.$row_bl["id"].'">';
								$message_text.= '<td class="chs fleft" style="text-align:left;"><b>'.$row_bl["url"].'</b></td>';
								$message_text.= '<td class="chs"><span class="sub-blue160" onClick="DelUrlBL(\''.$row_bl["id"].'\', \''.$row_bl["url"].'\');" style="float:none; margin: 0 auto;" title="Удалить из ЧС">Удалить из ЧС</span></td>';
							$message_text.= '</tr>';
						}
					}else{
						$message_text.= '<tr>';
							$message_text.= '<td class="chs fleft" colspan="9"><b>URL сайта '.$url_bl.' не найдено!</b></td>';
						$message_text.= '</tr>';
					}

					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($option == "DelUrlBL") {
				$id_bl = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? htmlspecialchars(trim($_POST["id"])) : false;

				$sql_bl = mysql_query("SELECT `id` FROM `tb_black_list` WHERE `id`='$id_bl' AND `user_name`='$user_name' AND `type`='url' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_bl)>0) {
					mysql_query("DELETE FROM `tb_black_list` WHERE `id`='$id_bl' AND `user_name`='$user_name' AND `type`='url'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$result_text = "OK"; $message_text = "Удалено";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					$result_text = "ERROR"; $message_text = "Ошибка! Нет блокировки с ID: $id_bl";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}else{
				$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = "Пользователь не идентифицирован.";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "Пользователь не идентифицирован.";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>