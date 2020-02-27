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

function my_json_encode($ajax_json, $result_text, $message_text, $cnt_text=false) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text), "cnt_text" => iconv("CP1251", "UTF-8", $cnt_text))) : $message_text;
}

function count_text($count, $text1, $text2, $text3, $text) {
	if($count>0) {
		if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
			return "$count $text1";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "$count $text2"; break;
				case 2: case 3: case 4: return "$count $text3"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "$count $text1"; break;
			}
		}
	}
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
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

function GetAvatar($user_id) {
    $sql_us = mysql_query("SELECT `avatar` FROM `tb_users` WHERE `id`='" . $user_id . "'");
    if(mysql_num_rows($sql_us)>0) {
        $row_us = mysql_fetch_assoc($sql_us);
        return $row_us["avatar"];
    }
    return "no.png";
}

function InfoUser($user_id, $user_name) {
    global $ajax_json;
    $sql = mysql_query("SELECT `id`,`username`,`avatar`,`reiting` FROM `tb_users` WHERE `id`='" . $user_id . "' AND `username`='" . $user_name . "'") or die(($ajax_json == "json" ? my_json_encode($ajax_json, "ERROR", mysql_error()) : mysql_error()) );
    if(mysql_num_rows($sql)>0) {
        $row = mysql_fetch_assoc($sql);
        $info_user["id"] = $row["id"];
        $info_user["username"] = $row["username"];
        $info_user["avatar"] = "<a href=\"/wall?uid=" . $row["id"] . "\" target=\"_blank\"><img class=\"avatar\" src=\"/avatar/" . $row["avatar"] . "\" style=\"width:50px; height:50px\" border=\"0\" alt=\"avatar\" title=\"Перейти на стену пользователя " . $row["username"] . "\" /></a>";
        $info_user["reiting"] = $row["reiting"];
    }else{
        $info_user["id"] = $user_id;
        $info_user["username"] = $user_name;
        $info_user["avatar"] = "<img class=\"avatar\" src=\"/avatar/no.png\" style=\"width:50px; height:50px\" border=\"0\" alt=\"avatar\" title=\"\" />";
        $info_user["reiting"] = false;
    }
    return $info_user;
}

function InfoStatus($_Status_Arr, $user_reiting){
		if($user_reiting > 2147483647) $user_reiting = 2147483647;
		for($i=1; $i<=count($_Status_Arr); $i++) {
			if($_Status_Arr[$i]["r_ot"] <= $user_reiting && $_Status_Arr[$i]["r_do"] >= floor($user_reiting)) {
				$InfoSatus["id"] = $i;
				$InfoSatus["rang"] = $_Status_Arr[$i]["rang"];
				$break;
			}
		}

		$InfoSatus["id"] = isset($InfoSatus["id"]) ? $InfoSatus["id"] : 1;
		$InfoSatus["rang"] = isset($InfoSatus["rang"]) ? $InfoSatus["rang"] : $_Status_Arr[$InfoSatus["id"]]["rang"];

		return $InfoSatus;
	}

function board_status($reiting){
	$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='".$reiting."' AND `r_do`>='".floor($reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_assoc($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_assoc($sql_rang);
	}
	return '<span style="cursor:help; color: #FF0000;" title="Статус">'.$row_rang["rang"].'</span>';
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
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");
		require(ROOT_DIR."/merchant/func_mysql.php");
		require_once(ROOT_DIR."/method_pay/method_pay_sys.php");
		$system_pay[10] = "С основного счета";

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
		$page = ( isset($_POST["page"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["page"]))) ) ? htmlspecialchars(trim($_POST["page"])) : false;
		$type = ( isset($_POST["type"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["type"]))) ) ? htmlspecialchars(trim($_POST["type"])) : false;
		$my_lastiplog = getRealIP();
		$min_money_add = "3.00";

		$sql_user = mysql_query("SELECT `id`,`username`,`money`,`money_inv`,`reiting`,`investor` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_money_ob = $row_user["money"];
			$user_money_inv = $row_user["money_inv"];
			$user_reiting = $row_user["reiting"];
			$user_investor = $row_user["investor"];
		}else{
			$result_text = "ERROR"; $message_text = "Пользователь не идентифицирован.";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


		if(
		($option == "LoadPage" && ($page == "Buy" | $page == "Sell" | $page == "Birj")) | 
		$option == "BuyShares" | $option == "SellShares" | $option == "DelBirj" | $option == "BuyBirj" | $option == "Balance"
		) {
			$sql_b = mysql_query("SELECT `id`,`why`,`date` FROM `tb_black_users` WHERE `name`='$user_name' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_b)>0) {
				$row_b = mysql_fetch_assoc($sql_b);

				$result_text = "ERROR";
				$message_text = 'Ваш аккаунт заблокирован!<br>Операции с акциями не доступны.<br>Причина блокировки: '.$row_b["why"].'<br>Дата блокировки: '.$row_b["date"].'';
				if($option == "DelBirj" | $option == "BuyBirj") $message_text = str_replace("<br>", "\n", $message_text);
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

			$sql_reit = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='min_reit_for_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$min_reit_for_shares = number_format(mysql_result($sql_reit,0,0), 0, ".", "");

			if($user_reiting < $min_reit_for_shares) {
				$result_text = "ERROR";
				$message_text = "Для покупки/продажи акций Ваш рейтинг должен быть не ниже ".count_text($min_reit_for_shares, "баллов", "балла", "баллов", "").".";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}

		if($option == "LoadPage") {
			if($page == "Info") {
				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$all_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='ost_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$ost_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares_buy'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$all_shares_buy = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='pay_all_dividend'");
				$pay_all_dividend = number_format(mysql_result($sql,0,0), 2, ".", "`");

				for($i=1; $i<=8; $i++) {
					$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='$i'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$proc_dividend[$i] = number_format(mysql_result($sql,0,0), 0, ".", "");
				}

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_one_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$cena_one_shares = number_format(mysql_result($sql,0,0), 2, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='min_reit_for_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$min_reit_for_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='$min_reit_for_shares' AND `r_do`>='".floor($min_reit_for_shares)."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_rang)>0) {
					$row_rang = mysql_fetch_assoc($sql_rang);
				}else{
					$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$row_rang = mysql_fetch_assoc($sql_rang);
				}
				$to_rang_inv = $row_rang["rang"];

				$message_text = "";
				$message_text.= '<div style="margin:0 auto; background:#fdefda; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#114C5B;">';
					$message_text.= '<div align="center" style="padding-bottom:10px;"><img src="/img/invest/icon-invest-manager.jpg" alt="" title="" border="0" align="absmiddle" width="480" /></div>';
					$message_text.= 'Что такое инвестиции в данный проект? Инвесторы это соучредители проекта, которым нет необходимости открывать подобный сайт. Инвесторы как и администрация получают процент с рекламодателей, и чем больше рекламы заказано тем выше доход. Все акционеры  получают свой процент сразу после оплаты любой рекламы рекламодателем (не распространяется на оплачиваемые задания и тесты).<br><br>';
					$message_text.= 'В настоящее время мы активно развиваемся, улучшаем и дополняем наш рекламный сервис. Тем самым открываем перед инвесторами новые возможности для увеличения заработка.<br>';
					$message_text.= 'Инвестиции в <b style="color:coral;">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> позволят Вам превратить свое хобби в высоко-прибыльную работу, которая приведет каждого из наших партнеров к финансовой независимости.<br><br>';
					$message_text.= 'Акционер (инвестор) может купить любое количество акций доступных для продажи, по цене <b>'.$cena_one_shares.'</b> руб. за одну акцию. ';
					$message_text.= 'Стать акционером сейчас может каждый желающий пользователь проекта со статусом <b>'.mb_strtoupper($to_rang_inv, "CP1251").'</b> и выше, но эта возможность будет жестко ограничена числом выпускаемых акций. ';
					$message_text.= 'Предупреждаем сразу, если вам что-то не понятно не надо покупать акции, проект обратно акции принимать не будет, но будет предоставлена возможность выставить акцию на продажу на биржу акций.<br><br>';
					$message_text.= '<span class="text-red">Вниманиe! Для покупки акций необходимо пополнить баланс инвестора на вкладке "Баланс". Баланс можно пополнить через любую доступную платежную систему.</span><br><br>';
					$message_text.= 'На инвесторов в полной мере распространяются все <a href="/tos.php" target="_blank">правила проекта</a>.<br><br>';

					$message_text.= '<table class="tables_inv" style="margin:1px auto;">';
					$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Всего выпущено акций</b></td>';
						$message_text.= '<td align="center"><b>'.number_format($all_shares, 0, ".", "`").'</b> шт.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Акций у системы</b>, (доступные для покупки)</td>';
						$message_text.= '<td align="center"><b>'.number_format($ost_shares, 0, ".", "`").'</b> шт.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Акций у инвесторов</b></td>';
						$message_text.= '<td align="center"><b>'.number_format($all_shares_buy, 0, ".", "`").'</b> шт.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Выплачено инвесторам</b></td>';
						$message_text.= '<td align="center"><span style="color:green;"><b>'.$pay_all_dividend.'</b> руб.</span></td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(Серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>, письма, тесты, пакет рекламы, оплачиваемые посещения)</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[1], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(авто-серфинг, Авто-серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>)</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[2], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(задания - поднятие в списке, поднятие в VIP блок)</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[3], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(аукцион и биржа рефералов [с дохода от системы])</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[5], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(реф-стена подарки на стене)</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[6], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(быстрые сообщения, бегущая строка, рассылка пользователям)</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[7], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					//$message_text.= '<tr>';
						//$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(бонус-удача, доска почета)</td>';
						//$message_text.= '<td align="center"><b>'.number_format($proc_dividend[8], 0, ".", "`").'</b> %.</td>';
					//$message_text.= '</tr>';
					
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Процент прибыли от акций</b><br>(остальная реклама)</td>';
						$message_text.= '<td align="center"><b>'.number_format($proc_dividend[4], 0, ".", "`").'</b> %.</td>';
					$message_text.= '</tr>';
					$message_text.= '</tbody>';
					$message_text.= '</table>';
				$message_text.= '</div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($page == "Stat") {
				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$all_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				if($all_shares==0) {
					$result_text = "ERROR";
					$message_text = 'Выпуска акций еще не было!';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

				for($i=1; $i<=4; $i++) {
					$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='$i'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$proc_dividend[$i] = number_format(mysql_result($sql,0,0), 0, ".", "");
				}

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_one_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$cena_one_shares = number_format(mysql_result($sql,0,0), 2, ".", "");

				$sql_i = mysql_query("SELECT * FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					$row_i = mysql_fetch_assoc($sql_i);
					$my_count_shares = $row_i["count_shares"];
					$my_dohod_v = $row_i["dohod_v"];
					$my_dohod_s = $row_i["dohod_s"];
					$my_dohod_all = $row_i["dohod_all"];
				}else{
					$my_count_shares = 0;
					$my_dohod_v = 0;
					$my_dohod_s = 0;
					$my_dohod_all = 0;
				}

				$type_ads_arr = array(
					'dlink' => 'Серфинг',
					'youtube' => 'Серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>', 
					'autoserf' => 'Авто-серфинг', 
					'autoserfyou' => 'Авто-серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>', 
					'mails' => 'Письма', 
					'pay_visits' => 'Оплачиваемые посещения',
					'kontext' => 'Контекст. реклама', 
					'statlink' => 'Стат. ссылки', 
					'banners' => 'Баннеры', 
					'txtob' => 'Текст. объявления', 
					'frmlink' => 'Ссылки во фрейме', 
					'auc' => 'Аукцион',
                      'ref_birj' => 'Биржа рефералов',
                      'gifts_pay' => 'Подарки на стене',
                    'ref_wall' => 'Реф-стена',
                     'mail_user' => 'Рассылка пользователям',	
					'bstroka' => 'Бегущая строка',
					'psevdo' => 'Псевдо ссылки', 
					'rekcep' => 'Рекл. цепочка', 
					'sent_emails' => 'Рассылка на е-mail', 
					'articles' => 'Каталог статей', 
					'pay_row' => 'Платная строка',
					'pay_mes' => 'Быстрые сообщения', 
					
					'tests_pay' => 'Тесты', 
					'task_up' => 'Задания - поднятие в списке', 
					'task_vip' => 'Задания - поднятие в VIP блок',
					'packet' => 'Пакеты рекламы',
				);
				$type_ads_key_arr = array_keys($type_ads_arr);
				$type_ads_key_arr = implode("','", $type_ads_key_arr);
				$day_week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
				$day_week_arr_ru = array("Вс","Пн","Вт","Ср","Чт","Пт","Сб");
				$day_month_arr_ru = array("","Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь");
				$date_start = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
				$date_end = strtotime(DATE("d.m.Y"));
				$period = (24*60*60);
				$ITOGO_ADS["mon"] = 0;
				$ITOGO_ADS["tue"] = 0;
				$ITOGO_ADS["wed"] = 0;
				$ITOGO_ADS["thu"] = 0;
				$ITOGO_ADS["fri"] = 0;
				$ITOGO_ADS["sat"] = 0;
				$ITOGO_ADS["sun"] = 0;
				$ITOGO_ADS["MONTH"] = 0;
				$ITOGO_ADS["WEEK"] = 0;

				$ITOGO_ADS_D["mon"] = 0;
				$ITOGO_ADS_D["tue"] = 0;
				$ITOGO_ADS_D["wed"] = 0;
				$ITOGO_ADS_D["thu"] = 0;
				$ITOGO_ADS_D["fri"] = 0;
				$ITOGO_ADS_D["sat"] = 0;
				$ITOGO_ADS_D["sun"] = 0;
				$ITOGO_ADS_D["MONTH"] = 0;
				$ITOGO_ADS_D["WEEK"] = 0;

				$message_text = '';
				$message_text.= '<h3 class="sp" style="margin-top:1px;">Общая статистика заказов рекламы</h3>';
				$message_text.= '<table class="tables" style="margin-top:1px;">';
				$message_text.= '<thead><tr>';
					$message_text.= '<th></th>';
					$message_text.= '<th style="font-size:12px;">'.$day_month_arr_ru[DATE("n", time())].'</th>';
					$message_text.= '<th style="font-size:12px;">За неделю</th>';
					for($i=$date_start; $i<=$date_end; $i=$i+$period) {
						if(DATE("w", $i)==DATE("w")) {
							$message_text.= '<th style="font-size:12px; background: #b163f9;">'.$day_week_arr_ru[strtolower(DATE("w", $i))].'<br>'.DATE("d.m", $i).'</th>';
						}else{
							$message_text.= '<th style="font-size:12px;">'.$day_week_arr_ru[strtolower(DATE("w", $i))].'<br>'.DATE("d.m", $i).'</th>';
						}
					}
				$message_text.= '</tr>';
				$message_text.= '</thead>';
				$message_text.= '<tbody>';
				$sql_stat = mysql_query("SELECT * FROM `tb_ads_stat` WHERE `type` IN('$type_ads_key_arr') ORDER BY `id` ASC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_stat)>0) {
					while ($row_stat = mysql_fetch_assoc($sql_stat)) {
						$message_text.= '<tr>';
							$message_text.= '<td align="left" height="25" nowrap="nowrap" style="background-color:#FFFACD; padding:2px 1px 2px 6px;"><span style="color: #27408B; font-size:12px">'.$type_ads_arr[$row_stat["type"]].'</span></td>';
							$message_text.= '<td align="center" style="background-color:#D1EEEE;"><span style="color:black; padding:2px 1px; font-size:11px;">'.($row_stat["sum_month"]==0 ? "-" : number_format($row_stat["sum_month"],2,".","")).'</span></td>';
							$message_text.= '<td align="center" style="background-color:#D1EEEE;"><span style="color:black; padding:2px 1px; font-size:11px;">'.($row_stat["sum_week"]==0 ? "-" : number_format($row_stat["sum_week"],2,".","")).'</span></td>';
							for($i=$date_start; $i<=$date_end; $i=$i+$period) {
								if(DATE("w", $i)==DATE("w")) {
									$message_text.= '<td align="center" style="background-color:#E8E8E8; padding:2px 1px; font-size:11px;"><span style="color:green;">'.($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]==0 ? "-" : number_format($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]],2,".","")).'</span></td>';
								}else{
									$message_text.= '<td align="center" style="padding:2px 1px; font-size:11px;"><span>'.($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]==0 ? "-" : number_format($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]],2,".","")).'</span></td>';
								}

								$ITOGO_ADS[$day_week_arr_en[strtolower(DATE("w", $i))]] += $row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]];
								if($row_stat["type"]=="dlink" | $row_stat["type"]=="mails" | $row_stat["type"]=="tests_pay") {
									$ITOGO_ADS_D[$day_week_arr_en[strtolower(DATE("w", $i))]] += ($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]*$proc_dividend[1]/$all_shares);

								}elseif($row_stat["type"]=="autoserf" | $row_stat["type"]=="autoserfyou") {
									$ITOGO_ADS_D[$day_week_arr_en[strtolower(DATE("w", $i))]] += ($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]*$proc_dividend[2]/$all_shares);

								}elseif($row_stat["type"]=="task_up" | $row_stat["type"]=="task_vip") {
									$ITOGO_ADS_D[$day_week_arr_en[strtolower(DATE("w", $i))]] += ($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]*$proc_dividend[3]/$all_shares);

								}else{
									$ITOGO_ADS_D[$day_week_arr_en[strtolower(DATE("w", $i))]] += ($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]*$proc_dividend[4]/$all_shares);
								}
							}

							$ITOGO_ADS["MONTH"] += $row_stat["sum_month"];
							$ITOGO_ADS["WEEK"] += $row_stat["sum_week"];
							if($row_stat["type"]=="dlink" | $row_stat["type"]=="mails" | $row_stat["type"]=="tests_pay") {
								$ITOGO_ADS_D["MONTH"] += ($row_stat["sum_month"]*$proc_dividend[1]/$all_shares);
								$ITOGO_ADS_D["WEEK"] += ($row_stat["sum_week"]*$proc_dividend[1]/$all_shares);

							}elseif($row_stat["type"]=="autoserf" | $row_stat["type"]=="autoserfyou") {
								$ITOGO_ADS_D["MONTH"] += ($row_stat["sum_month"]*$proc_dividend[2]/$all_shares);
								$ITOGO_ADS_D["WEEK"] += ($row_stat["sum_week"]*$proc_dividend[2]/$all_shares);

							}elseif($row_stat["type"]=="task_up" | $row_stat["type"]=="task_vip") {
								$ITOGO_ADS_D["MONTH"] += ($row_stat["sum_month"]*$proc_dividend[3]/$all_shares);
								$ITOGO_ADS_D["WEEK"] += ($row_stat["sum_week"]*$proc_dividend[3]/$all_shares);

							}else{
								$ITOGO_ADS_D["MONTH"] += ($row_stat["sum_month"]*$proc_dividend[4]/$all_shares);
								$ITOGO_ADS_D["WEEK"] += ($row_stat["sum_week"]*$proc_dividend[4]/$all_shares);
							}

							$message_text.= '</tr>';
					}

					$message_text.= '<tr>';
						$message_text.= '<td align="right" style="background-color:#CDCDB4; padding:2px 5px;"><span style="color: #A52A2A; font-size:12px;">Итого:</span></td>';
						$message_text.= '<td align="center" style="background-color:#CDCDB4; padding:2px 1px;"><span style="color: #A52A2A; font-size:12px;">'.number_format($ITOGO_ADS["MONTH"],2,".","").'</span></td>';
						$message_text.= '<td align="center" style="background-color:#CDCDB4; padding:2px 1px;"><span style="color: #A52A2A; font-size:12px;">'.number_format($ITOGO_ADS["WEEK"],2,".","").'</span></td>';
						for($i=$date_start; $i<=$date_end; $i=$i+$period) {
							$message_text.= '<td align="center" style="background-color:#CDCDB4; padding:2px 1px;"><span style="color: #A52A2A; font-size:12px">'.number_format($ITOGO_ADS[$day_week_arr_en[strtolower(DATE("w", $i))]], 2, ".", "").'</span></td>';
						}
					$message_text.= '</tr>';

					$message_text.= '<tr>';
						$message_text.= '<td align="right" style="background-color:#CDCDB4; padding:2px 5px;"><span style="color: #A52A2A; font-size:12px">Доходность акций:</span></td>';
						$message_text.= '<td align="center" style="background-color:#CDCDB4; padding:2px 1px;"><span style="color: #A52A2A; font-size:12px">'.number_format(($ITOGO_ADS_D["MONTH"]*12)/$cena_one_shares,2,".","").'%</span></td>';
						$message_text.= '<td align="center" style="background-color:#CDCDB4; padding:2px 1px;"><span style="color: #A52A2A; font-size:12px">'.number_format(($ITOGO_ADS_D["WEEK"]*365)/($cena_one_shares*7),2,".","").'%</span></td>';
						for($i=$date_start; $i<=$date_end; $i=$i+$period) {
							$message_text.= '<td align="center" style="background-color:#CDCDB4; padding:2px 1px;"><span style="color: #A52A2A; font-size:12px">'.number_format(($ITOGO_ADS_D[$day_week_arr_en[strtolower(DATE("w", $i))]]*365)/$cena_one_shares, 2, ".", "").'%</span></td>';
						}
					$message_text.= '</tr>';
				}
				$message_text.= '</tbody>';
				$message_text.= '</table><br>';

				$message_text.= '<table class="tables_inv" style="margin:1px auto;">';
				$message_text.= '<thead><tr align="center"><th colspan="2">Ваша статистика</th></tr></thead>';
				$message_text.= '<tbody>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Количество акций у вас</b></td>';
					$message_text.= '<td align="center" width="140"><b>'.number_format($my_count_shares, 0, ".", "`").'</b> шт.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Доход за сегодня</b></td>';
					$message_text.= '<td align="center"><b>'.($my_dohod_s>0 ? number_format($my_dohod_s, 6, ".", "`") : number_format($my_dohod_s, 2, ".", "`")).'</b> руб.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Доход за вчера</b></td>';
					$message_text.= '<td align="center"><b>'.($my_dohod_v>0 ? number_format($my_dohod_v, 6, ".", "`") : number_format($my_dohod_v, 2, ".", "`")).'</b> руб.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Общий доход</b></td>';
					$message_text.= '<td align="center"><b>'.($my_dohod_all>0 ? number_format($my_dohod_all, 6, ".", "`") : number_format($my_dohod_all, 2, ".", "`")).'</b> руб.</td>';
				$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table><br>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));


			}elseif($page == "Buy") {
				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$all_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares_buy'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$all_shares_buy = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='ost_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$ost_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_one_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$cena_one_shares = number_format(mysql_result($sql,0,0), 2, ".", "");

				$sql_i = mysql_query("SELECT `count_shares` FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					$row_i = mysql_fetch_assoc($sql_i);
					$my_count_shares = $row_i["count_shares"];
				}else{
					$my_count_shares = 0;
				}

				$message_text = '';
				$message_text.= '<div id="HistorySys"><table class="tables_inv" style="margin:1px auto;">';
				$message_text.= '<thead><tr align="center"><th colspan="2">Информация</th></tr></thead>';
				$message_text.= '<tbody>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Всего выпущено акций</b></td>';
					$message_text.= '<td align="center"><b>'.number_format($all_shares, 0, ".", "`").'</b> шт.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Доступно для покупки</b></td>';
					$message_text.= '<td align="center"><b id="SysCntShares">'.number_format($ost_shares, 0, ".", "`").'</b> шт.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Акций у инвесторов</b></td>';
					$message_text.= '<td align="center"><b id="InvCntShares">'.number_format($all_shares_buy, 0, ".", "`").'</b> шт.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Акций у вас</b></td>';
					$message_text.= '<td align="center" width="140"><b id="MyCntShares">'.number_format($my_count_shares, 0, ".", "`").'</b> шт.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Стоимость одной акции</b></td>';
					$message_text.= '<td align="center" width="140"><b>'.number_format($cena_one_shares, 2, ".", "`").'</b> руб.</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left"><b>Ваш баланс инвестора</b></td>';
					$message_text.= '<td align="center" width="140"><b>'.number_format($user_money_inv, 2, ".", "`").'</b> руб.</td>';
				$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$message_text.= '<h3 class="sp" style="margin-top:15px;">Покупка акций</h3>';
				$message_text.= '<div id="BuyForm"><table id="newform" class="tables_inv" style="margin-top:1px; margin-bottom:10px;">';
				$message_text.= '<tbody>';
				$message_text.= '<tr>';
					$message_text.= '<td align="left" nowrap="nowrap" width="440" style="padding:2px 10px 2px 5px; border-right:none;"><b>Укажите количество акций которые Вы хотите приобрести</b></td>';
					$message_text.= '<td align="center" width="100" style="border-left:none; border-right:none;"><input type="text" id="count_buy" value="100" maxlength="11" class="ok12" autocomplete="off" style="text-align:center; width:95px;"></td>';
					$message_text.= '<td align="left" style="border-left:none;"><span onClick="BuyShares();" class="proc-btn" style="float:none;text-align: center;">Купить</span></td>';
				$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$message_text.= '<div id="info-msg-buy" style="display:none;"></div>';

				$message_text.= '<h3 class="sp" style="margin-top:20px;">История покупок акций у системы</h3>';
				$message_text.= '<div id="HistoryBuy"><table class="tables_inv" style="margin:1px auto;">';
				$message_text.= '<thead><tr align="center"><th>Дата операции</th><th>Количество акций</th><th>Сумма операции</th></tr></thead>';
				$message_text.= '<tbody>';
				$sql_h = mysql_query("SELECT * FROM `tb_invest_history` WHERE `username`='$user_name' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_h)>0) {
					while ($row_h = mysql_fetch_assoc($sql_h)) {
						$message_text.= '<tr align="center">';
							$message_text.= '<td><b>'.DATE("d.m.Yг. H:i", $row_h["time_op"]).'</b></td>';
							$message_text.= '<td><b>'.$row_h["count_shares"].'</b> шт.</td>';
							$message_text.= '<td><b>'.number_format($row_h["money"], 2, ".", "`").'</b> руб.</td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr align="center"><td colspan="3"><b>Нет данных</b></td></tr>';
				}
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$message_text.= '<h3 class="sp" style="margin-top:20px;">История покупок акций на бирже</h3>';
				$message_text.= '<div id="HistoryBuyBirj"><table class="tables_inv" style="margin:1px auto;">';
				$message_text.= '<thead><tr align="center"><th>Продавец</th><th>Дата операции</th><th>Количество акций</th><th>Сумма операции</th></tr></thead>';
				$message_text.= '<tbody>';
				$sql_h = mysql_query("SELECT * FROM `tb_invest_birj_history` WHERE `buyer_name`='$user_name' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_h)>0) {
					while ($row_h = mysql_fetch_assoc($sql_h)) {
						$message_text.= '<tr align="center">';
							$message_text.= '<td><b>'.$row_h["seller_name"].'</b></td>';
							$message_text.= '<td><b>'.DATE("d.m.Yг. H:i", $row_h["time_op"]).'</b></td>';
							$message_text.= '<td><b>'.$row_h["count_shares"].'</b> шт.</td>';
							$message_text.= '<td><b>'.number_format($row_h["money"], 2, ".", "`").'</b> руб.</td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr align="center"><td colspan="4"><b>Нет данных</b></td></tr>';
				}
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
				
				}elseif ($page == 'Top10') {
					$message_text = false;
					$message_text.= '<h3 class="sp" style="margin-top:10px;">ТОП 10</h3>';
				$message_text .= "<table class=\"tables\" style=\"margin:1px auto;\" id=\"newform\">";
                    $message_text .= "<thead><tr>";
                    $message_text .= "<th align=\"center\" colspan=\"2\">Инвестор</th>";
                    $message_text .= "<th align=\"center\">Доход с акций</th>";
                    $message_text .= "<th align=\"center\">Количество акций</th>";
                    $message_text .= "</thead></tr>";
                    $message_text .= "<tbody>";
                    $sql_i = mysql_query("SELECT * FROM `tb_invest_users` WHERE `username`!='Admin' ORDER BY `count_shares` DESC, `dohod_all` DESC LIMIT 10") or die( my_json_encode($ajax_json, "ERROR", mysql_error()) );
                    if(mysql_num_rows($sql_i)>0) {
						
                        while( $row_i = mysql_fetch_assoc($sql_i) ) {
							$sql_igr_us=mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$row_i['username']."'"));	
                            $InfoUser = infouser($row_i["id"], $row_i["username"]);
                            $message_text .= "<tr>";
                            $message_text .= "<td style=\"text-align:center; width:70px; border-right:none; padding:2px 5px;\"><a href=\"/wall?uid=".$sql_igr_us["id"]."\" target=\"_blank]\"><img src=\"avatar/".$sql_igr_us["avatar"]."\" class=\"avatar\" style=\"width:50px; height:50px\" border=\"0\" alt=\"avatar\" title=\"Перейти на стену пользователя ".$row_i["username"]."\"></a></td>";
                            $message_text .= "<td style=\"text-align:left; border-left:none; padding-left:0px;\">";
                            $message_text .= "<div class=\"rw_login\" style=\"text-align:left;\"><span style=\"color:#696969;\">Логин:</span> " . $row_i["username"] . "</div>";
                            $message_text .= "<div class=\"rw_status\" style=\"text-align:left; margin-top:1px; font-weight:bold;\"><span style=\"color:#696969;\">Статус:</span> " . board_status($sql_igr_us["reiting"]) . "</div>";
                            $message_text .= "</td>";
                            $message_text .= "<td align=\"center\"><b class=\"text-grey\">" . number_format($row_i["dohod_all"], 2, ".", "`") . "</b></td>";
                            $message_text .= "<td align=\"center\"><b class=\"text-blue\">" . $row_i["count_shares"] . "</b></td>";
                            $message_text .= "</tr>";
                        }
                    //}
					}else{
						$message_text.= '<table class="tables" style="margin:1px auto;">';
				$message_text.= '<tbody>';
					$message_text.= '<tr align="center"><td colspan="4"><b>Нет данных</b></td></tr>';
					$message_text .= "</tbody>";
                    $message_text .= "</table>";
				}

                    $message_text .= "</tbody>";
                    $message_text .= "</table>";
                    $result_text = "OK";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
				
			}elseif($page == "Sell") {
				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_min_shares_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$cena_min_shares_birj = number_format(mysql_result($sql,0,0), 2, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_max_shares_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$cena_max_shares_birj = number_format(mysql_result($sql,0,0), 2, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='comis_sys_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$comis_sys_birj = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql_i = mysql_query("SELECT `count_shares` FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					$row_i = mysql_fetch_assoc($sql_i);
					$my_count_shares = $row_i["count_shares"];
				}else{
					$my_count_shares = 0;
				}

				$message_text = "";
				$message_text.= '<div style="margin:0 auto; background:#f5e3ca; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#114C5B;">';
					$message_text.= 'Внимание! Все операции по продаже акций осуществляются между пользователями проекта на бирже акций. ';
					$message_text.= 'Комиссия сайта составляет '.$comis_sys_birj.'%, удерживается с продавца в момент покупки акций инвестором на бирже акций. ';
					$message_text.= 'Доход на продаваемые акции <u>начисляется</u> в независимости от того находятся Ваши акции на бирже или нет.';
				$message_text.= '</div>';

				$message_text.= '<h3 class="sp" style="margin-top:15px;">Продажа акций</h3>';
				if($my_count_shares>0) {
					$message_text.= '<div id="SellForm"><table id="newform" class="tables_inv" style="margin-top:1px; margin-bottom:10px;">';
					$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left" width="430" nowrap="nowrap" style="padding:2px 10px 2px 5px; border-right:none;"><b>Укажите количество акций которые Вы хотите продать</b></td>';
						$message_text.= '<td align="center" width="100" style="border-left:none;"><input type="text" id="count_sell" value="0" maxlength="11" class="ok12" autocomplete="off" style="text-align:center; width:95px;"></td>';
						$message_text.= '<td align="center" rowspan="2"><span onClick="SellShares();" class="sub-orange" style="float:none;">Продать</span></td>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left" width="330" nowrap="nowrap" style="padding:2px 10px 2px 5px; border-right:none;"><b>Укажите стоиомость за 1 акцию</b>, [ от '.$cena_min_shares_birj.' до '.$cena_max_shares_birj.' руб.]</td>';
						$message_text.= '<td align="center" width="100" style="border-left:none;"><input type="text" id="cena_one_sell" value="0.00" maxlength="11" class="ok12" autocomplete="off" style="text-align:center; width:95px;"></td>';
					$message_text.= '</tr>';
					$message_text.= '</tbody>';
					$message_text.= '</table></div>';
				}else{
					$message_text.= '<span class="msg-w">У вас нет акций для продажи</span>';
				}

				$message_text.= '<div id="info-msg-sell" style="display:none;"></div>';

				$message_text.= '<h3 class="sp" style="margin-top:20px;">История продаж акций на бирже</h3>';
				$message_text.= '<div id="HistoryBuy"><table class="tables_inv" style="margin:1px auto;">';
				$message_text.= '<thead><tr align="center"><th>Покупатель</th><th>Дата операции</th><th>Количество акций</th><th>Сумма операции</th><th>Сумма зачисления</th></tr></thead>';
				$message_text.= '<tbody>';
				$sql_h = mysql_query("SELECT * FROM `tb_invest_birj_history` WHERE `seller_name`='$user_name' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_h)>0) {
					while ($row_h = mysql_fetch_assoc($sql_h)) {
						$message_text.= '<tr align="center">';
							$message_text.= '<td><b>'.$row_h["buyer_name"].'</b></td>';
							$message_text.= '<td><b>'.DATE("d.m.Yг. H:i", $row_h["time_op"]).'</b></td>';
							$message_text.= '<td><b>'.$row_h["count_shares"].'</b> шт.</td>';
							$message_text.= '<td><b>'.number_format($row_h["money"], 2, ".", "`").'</b> руб.</td>';
							$message_text.= '<td><b>'.number_format($row_h["money_seller"], 2, ".", "`").'</b> руб.</td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr align="center"><td colspan="5"><b>Нет данных</b></td></tr>';
				}
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));


			}elseif($page == "Birj") {
				$message_text = "";
				$message_text.= '<div style="margin:0 auto; background:#f5e3ca; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#114C5B;">';
					$message_text.= 'Внимание! Все операции на бирже осуществляются между пользователями проекта. ';
				$message_text.= '</div>';

				$message_text.= '<h3 class="sp" style="margin-top:20px;">Акции на бирже выставленные инвесторами</h3>';
				$message_text.= '<div id="BirjTab"><table class="tables_inv" style="margin:1px auto;">';
				$message_text.= '<thead><tr align="center"><th>Продавец</th><th>Количество акций</th><th>Цена за акцию</th><th>Общая стоимость</th><th></th></tr></thead>';
				$message_text.= '<tbody>';
				$sql_birj = mysql_query("SELECT * FROM `tb_invest_birj` ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_birj)>0) {
					while ($row_birj = mysql_fetch_assoc($sql_birj)) {
						$message_text.= '<tr align="center" id="trbirj'.$row_birj["id"].'">';
							$message_text.= '<td><b>'.$row_birj["seller_name"].'</b></td>';
							$message_text.= '<td><b>'.$row_birj["count_shares"].'</b> шт.</td>';
							$message_text.= '<td><b>'.number_format($row_birj["money_one"], 2, ".", "`").'</b> руб.</td>';
							$message_text.= '<td><b>'.number_format($row_birj["money_sum"], 2, ".", "`").'</b> руб.</td>';
							if(strtolower($row_birj["seller_name"])==strtolower($user_name)) {
								$message_text.= '<td style="height:32px;"><span class="workcomp" style="float:none;" onClick="DelBirj(\''.$row_birj["id"].'\');" title="Снять с продажи"></span></td>';
							}else{
								$message_text.= '<td style="height:32px;"><span class="invest_shopping" onClick="BuyBirj(\''.$row_birj["id"].'\');" title="Купить акции"></span></td>';
							}
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr align="center"><td colspan="5"><b>В продаже нет акций</b></td></tr>';
				}
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));


			}elseif($page == "News") {
				require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

				$message_text = "";
				$message_text.= '<h3 class="sp" style="margin-top:1px;"><b>Новости инвестиционной программы</b></h3>';
				$message_text.= '<table class="tables" style="margin:1px auto;">';
				$message_text.= '<tbody>';
				$sql = mysql_query("SELECT * FROM `tb_invest_news` ORDER BY `id` DESC LIMIT 10") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql)>0) {
					while ($row = mysql_fetch_assoc($sql)) {
						$description = new bbcode($row["description"]);
						$description = $description->get_html();
						$message_text.= '<tr align="center" id="IdDel'.$row["id"].'">';
							$message_text.= '<td align="justify" style="padding:2px 0px;">';
								$message_text.= '<div style="color:#9E003F; background-color:#FFEC82; padding:3px 7px;">'.DATE("d.m.Yг. H:i", $row["time"]).'</div>';
								$message_text.= '<div style=" padding:2px 5px; display:block; margin-top:3px; margin-bottom:3px; color:#008B8B; font-size:14px;">'.$row["title"].'</div>';
								$message_text.= '<div style=" padding:2px 5px; display:block; font-size:12px;">'.$description.'</div>';
							$message_text.= '</td>';
						$message_text.= '</tr>';
					}
				}else{
					$message_text.= '<tr align="center"><td><b>Новости не найдены!</b></td></tr>';
				}
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));


			}elseif($page == "NewMsg") {
				$message_text = "";
				$message_text.= '<h3 class="sp"><b>Форма отправки сообщения Администрации сайта</b></h3>';
				$message_text.= '<div id="FormMsg"><table class="tables" style="margin:1px auto;">';

				$message_text.= '<table class="tables" id="newform">';
				$message_text.= '<thead><tr align="center">';
					$message_text.= '<th width="180">Параметр</a>';
					$message_text.= '<th>Значение</a>';
				$message_text.= '</thead></tr>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>Тема сообщения</b></td>';
						$message_text.= '<td align="left"><input type="text" id="title" value="" maxlength="100" class="ok" autocomplete="off" onKeyDown="$(this).attr(\'class\', \'ok\');" /></td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td colspan="3"><b>Текст сообщения &darr;</b></td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td colspan="2">';
							$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">Ж</span>';
							$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">К</span>';
							$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">Ч</span>';
							$message_text.= '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
							$message_text.= '<br>';
							$message_text.= '<div style="display: block; clear:both; padding-top:4px">';
								$message_text.= '<textarea id="description" class="ok" style="height:170px; width:99.2%;" onKeyDown="$(this).attr(\'class\', \'ok\');"></textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table></div>';

				$message_text.= '<div align="center"><span onClick="SentMailAdmin();" class="sub-blue160" style="float:none; width:160px;">Отправить сообщение</span></div>';

				$message_text.= '<div id="info-msg-mail" style="display:none;"></div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));


			}elseif($page == "Balance") {
				
				////////////////////
				$sql_bon_inv_status = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_status'");
				$bon_inv_status = (mysql_num_rows($sql_bon_inv_status)>0 && mysql_result($sql_bon_inv_status,0,0)>0 ) ? mysql_result($sql_bon_inv_status,0,0) : 0;
				
				$sql_bon_inv_summa = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_summa'");
				$bon_inv_summa = (mysql_num_rows($sql_bon_inv_summa)>0 && mysql_result($sql_bon_inv_summa,0,0)>0 ) ? mysql_result($sql_bon_inv_summa,0,0) : 0;
				
				$sql_bon_inv_money = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_money'");
				$bon_inv_money = (mysql_num_rows($sql_bon_inv_money)>0 && mysql_result($sql_bon_inv_money,0,0)>0 ) ? mysql_result($sql_bon_inv_money,0,0) : 0;
				
				$sql_bon_inv_reiting = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_reiting'");
				$bon_inv_reiting = (mysql_num_rows($sql_bon_inv_reiting)>0 && mysql_result($sql_bon_inv_reiting,0,0)>0 ) ? mysql_result($sql_bon_inv_reiting,0,0) : 0;
			/////////////////////	
			
				$message_text = false;
				$message_text.= '<div style="margin:0 auto; background:#fbf3ef; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#FF0000; margin-bottom:20px;">';
					$message_text.= 'Внимание! Баланс инвестора необходим только для покупки акций у системы и на бирже. Баланс можно пополнить через любую доступную платежную систему.';
				$message_text.= '</div>';

				$message_text.= '<div style="font-size:14px; margin-bottom:0px;">Ваш баланс инвестора: <span id="balance_inv" style="font-weight:bold; font-size:15px;">'.number_format($user_money_inv, 2, ".", "`").'</span> руб.</div><br>';

				if($bon_inv_status==1){
	$message_text.= '<div class="text-grey" style="padding:15px;border: 3px solid #ff7f50;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;box-shadow: inset 0 0 20px #893f45;-moz-box-shadow:inset 0 0 20px #855e42;-webkit-box-shadow: inset 0 0 20px #ff7f50;text-wrap:normal;word-wrap:break-word;background-color: #fbf3ef;text-align: center;">';
    $message_text.= '<div class="text-red"><b>Внимание!</b></div>';
    $message_text.= '<div>При пополнении баланса инвестора на сумму <span style="color:red;"><b>'.$bon_inv_summa.'</b> руб.</span> и более одним платежом получите бонус:</div>';
    $message_text.= '<div class="text-red">+<b>'.$bon_inv_money.'</b> руб. на баланс инвестора</div>';
    $message_text.= '<div class="text-red">+<b>'.$bon_inv_reiting.'</b> баллов рейтинга</div>';
	$message_text.= '<div class="text-red"><b>Бонус зачисляется через пополнения с внешних платежных систем<br/> конвертация с основного счета не учитывается!</b><br/></div>';
	$message_text.= '</div>';
				}
				
				$message_text.= '<h3 class="sp">Форма пополнения баланса инвестора</h3>';

				$message_text.= '<div id="FormAddBalance">';
					$message_text.= '<table class="tables" style="margin:1px auto;" id="newform">';
					$message_text.= '<thead><tr align="center">';
						$message_text.= '<th width="200">Параметр</a>';
						$message_text.= '<th>Значение</a>';
					$message_text.= '</thead></tr>';
					$message_text.= '<tbody>';
						$message_text.= '<tr>';
							$message_text.= '<td align="left"><b>Введите сумму</b></td>';
							$message_text.= '<td align="left"><input type="text" id="money_add" value="" placeholder="1000.00" class="ok12" style="text-align:center;" autocomplete="off" onChange="MoneyValid();" onKeydowm="MoneyValid();" onKeyup="MoneyValid();"><span style="padding-left:10px;">[ минимум '.$min_money_add.' руб. ]</span></td>';
						$message_text.= '</tr>';
						$message_text.= '<tr>';
							$message_text.= '<td align="left"><b>Выберите способ оплаты</b></td>';
							$message_text.= '<td align="left">';
								$message_text.= '<select id="method_pay">';
									require_once(ROOT_DIR."/method_pay/method_pay_form_inv.php");
								$message_text.= '</select>';
							$message_text.= '</td>';
						$message_text.= '</tr>';
					$message_text.= '</tbody>';
					$message_text.= '</table>';
					$message_text.= '<div align="center"><span onClick="AddBalance(\'SetBalance\');" class="proc-btn" style="float:none; width:160px;">Далее</span></div>';
				$message_text.= '</div>';
				$message_text.= '<div id="info-msg-bal" style="display:none;"></div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));


			}else{
				$result_text = "ERROR";
				$message_text = "Страница не найдена";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}


		}elseif($option == "SetBalance") {
			$money_add = (isset($_POST["money_add"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))))) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;
			$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
			$message_text = false;
			
			$sql_p = mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                $site_wmr = mysql_result($sql_p,0,0);

			$system_pay[10] = "С основного счета";

			if($method_pay == false) {
				$result_text = "ERROR";
				$message_text.= '<span class="msg-error">Не верно указан способ оплаты.</span>';

			}elseif($money_add < $min_money_add) {
				$result_text = "ERROR";
				$message_text.= '<span class="msg-error">Минимальная сумма пополнения '.$min_money_add.' руб.</span>';

			}else{
				@require_once(ROOT_DIR."/curs/curs.php");
				$money_add_usd = number_format(round(($money_add/$CURS_USD),2),2,".","");

				$merch_tran_id = mysql_result(mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'"),0,0);

				mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("DELETE FROM `tb_invest_money_in` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_check = mysql_query("SELECT `id` FROM `tb_invest_money_in` WHERE `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_check)>0) {
					$id_zakaz = mysql_result($sql_check,0,0);

					mysql_query("UPDATE `tb_invest_money_in` SET `merch_tran_id`='$merch_tran_id', `method_pay`='$method_pay', `date`='".DATE("d.m.Y H:i", time())."', `time`='".time()."', `ip`='$my_lastiplog', `money`='$money_add', `money_usd`='$money_add_usd' WHERE `id`='$id_zakaz'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}else{
					mysql_query("INSERT INTO `tb_invest_money_in` (`status`, `merch_tran_id`, `method_pay`, `username`, `date`, `time`, `ip`, `money`, `money_usd`) 
					VALUES('0', '$merch_tran_id', '$method_pay', '$user_name', '".DATE("d.m.Y H:i", time())."', '".time()."', '$my_lastiplog', '$money_add', '$money_add_usd')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_invest_money_in`") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$id_zakaz = mysql_result($sql_last_id,0,0);
				}

				$shp_item = "40";
				$inv_desc = "Пополнение баланса инвестора $user_name (ID:$user_id)";
				$inv_desc_utf8 = iconv("CP1251", "UTF-8", "Пополнение баланса инвестора $user_name (ID:$user_id)");
				$inv_desc_en = "Popolnenie balansa: investor $user_name (ID:$user_id)";
				$money_add = number_format($money_add,2,".","");

				$message_text.= '<span class="msg-ok" style="margin-bottom:0px;">Ваш заказ принят и будет выполнен автоматически после оплаты</span>';
				$message_text.= '<table class="tables">';
					$message_text.= '<tr><td><b>Счет №:</b></td><td>'.$merch_tran_id.'</td></tr>';
					$message_text.= '<tr><td><b>Назначение платежа:</b></td><td>Пополнение баланса инвестора '.$user_name.' (#id '.$user_id.')</td></tr>';
					$message_text.= '<tr><td><b>Способ оплаты:</b></td><td><b>'.$system_pay[$method_pay].'</b>, счет необходимо оплатить в течении 24 часов</td></tr>';

					if($method_pay==8) {
						if(($money_add*0.005)<0.01) {$money_add_ym = $money_add + 0.01;}else{$money_add_ym = number_format(($money_add*1.005),2,".","");}

						$message_text.= '<tr><td><b>Сумма к зачислению:</b></td><td><b style="color:#76B15D;">'.number_format($money_add,2,".","`").'</b> <b>руб.</b></td></tr>';
						$message_text.= '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#76B15D;">'.number_format($money_add_ym,2,".","`").'</b> <b>руб.</b></td></tr>';

					}elseif($method_pay==7) {
						$message_text.= '<tr><td><b>Сумма к зачислению:</b></td><td><b style="color:#76B15D;">'.number_format($money_add,2,".","`").'</b> <b>руб.</b></td></tr>';
						$message_text.= '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#76B15D;">'.number_format($money_add_usd,2,".","`").'</b> <b>USD</b></td></tr>';

					}elseif($method_pay==3) {
						$money_add_w1 = number_format(($money_add * 1.05), 2, ".", "");

						$message_text.= '<tr><td><b>Сумма к зачислению:</b></td><td><b style="color:#76B15D;">'.number_format($money_add,2,".","`").'</b> <b>руб.</b></td></tr>';
						$message_text.= '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#76B15D;">'.number_format($money_add_w1,2,".","`").'</b> <b>руб.</b></td></tr>';

					}else{
						$message_text.= '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#76B15D;">'.number_format($money_add,2,".","`").'</b> <b>руб.</b></td></tr>';
					}

					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="border-right:none;">';
							if($method_pay == 10) {
								$message_text.= '<span onClick="AddBalance(\'InBalance\', \''.$id_zakaz.'\');" class="proc-btn" style="float:none;">Оплатить</span>';
							}else{
								require_once(ROOT_DIR."/method_pay/method_pay_json.php");
							}
						$message_text.= '</td>';
						$message_text.= '<td align="center" style="border-left:none;">';
							$message_text.= '<span onClick="ChangeBalance();" class="proc-btn" style="float:right; display:inline-block;">Изменить</span>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
				$message_text.= '</table>';

				$message_text.= '<div id="info-msg-pay" style="display:none;"></div>';

				$result_text = "OK";
			}

			exit(my_json_encode($ajax_json, $result_text, $message_text));


		}elseif($option == "InBalance") {
			$id_pay = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? htmlspecialchars(trim($_POST["id"])) : false;
			$message_text = false;
			$cnt_text = $user_money_inv;

			$sql_id = mysql_query("SELECT `id`,`money` FROM `tb_invest_money_in` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_id)>0) {
				$row = mysql_fetch_assoc($sql_id);
				$money_pay = $row["money"];

				if($user_money_ob >= $money_pay) {
					mysql_query("UPDATE `tb_users` SET `money_inv`=`money_inv`+'$money_pay', `money`=`money`-'$money_pay' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("UPDATE `tb_invest_money_in` SET `status`='1', `date`='".DATE("d.m.Y H:i", time())."', `time`='".time()."' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$user_name' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$money_pay','Пополнение баланса инвестора с рекламного счета','Зачислено','prihod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$cnt_text_ib = number_format(($cnt_text+$money_pay), 2, ".", "`");
					$cnt_text_ob = p_floor(($user_money_ob-$money_pay), 4);

					stat_pay("money_invest", $money_pay);

					$cnt_text = '{"cnt_ib":"'.$cnt_text_ib.'", "cnt_ob":"'.$cnt_text_ob.'"}';

					$message_text.= '<span class="msg-ok">Баланс инвестора успешно пополнен</span>';
					$result_text = "OK";
				}else{
					$result_text = "ERROR";
					$message_text.= '<span class="msg-error">На вашем основном счете недостаточно средств для пополнения баланса инвестора</span>';

				}
			}else{
				$result_text = "ERROR";
				$message_text.= '<span class="msg-error">Счет не найден</span>';
			}

			exit(my_json_encode($ajax_json, $result_text, $message_text, $cnt_text));


		}elseif($option == "BuyShares") {
			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_one_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cena_one_shares = number_format(mysql_result($sql,0,0),2,".","");

			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$all_shares = number_format(mysql_result($sql,0,0),0,".","");

			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='ost_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$ost_shares = number_format(mysql_result($sql,0,0),0,".","");

			$count_buy = ( isset($_POST["count_buy"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["count_buy"])) ) ? htmlspecialchars(trim($_POST["count_buy"])) : false;
			$cena_buy = number_format( ($count_buy * $cena_one_shares), 2, ".", "");

			if($ost_shares < 1) {
				$result_text = "ERROR"; $message_text = "На данный момент нет акций для приобретения!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($count_buy < 1) {
				$result_text = "ERROR"; $message_text = "Минимум для покупки 1 акция!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($count_buy > $ost_shares) {
				$result_text = "ERROR"; $message_text = "На данный момент можно приобрести только ".count_text($ost_shares, "акций", "акцию", "акции", "").".";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($cena_buy > $user_money_inv) {
				$result_text = "ERROR"; $message_text = 'На Вашем счету недостаточно средств для покупки '.count_text($count_buy, "акций", "акции", "акций", "").'.<br>Необходимо <span id="Balance" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">пополнить баланс инвестора.</span> Требуемая сумма '.number_format($cena_buy, 2, ".", "`").' руб. ';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				if($user_investor==0) {
					$sql = mysql_query("SELECT * FROM `tb_invest_welcome`") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					if(mysql_num_rows($sql)>0) {
						$row = mysql_fetch_assoc($sql);
						$title = isset($row["title_text"]) ? trim($row["title_text"]) : false;
						$description = isset($row["desc_text"]) ? trim($row["desc_text"]) : false;

						if($title != false && $description != false) {
							mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
							VALUES('$user_name','Система','$title','$description','0','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
				}

				mysql_query("UPDATE `tb_users` SET `money_inv`=`money_inv`-'$cena_buy', `investor`='1' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_invest_history` (`username`,`count_shares`,`money`,`date_op`,`time_op`) 
				VALUES('$user_name','$count_buy','$cena_buy','".DATE("d.m.Y H:i")."','".time()."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$cena_buy','Покупка акций у системы: $count_buy шт.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("UPDATE `tb_invest_config` SET `price`=`price`-'$count_buy' WHERE `item`='ost_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'$count_buy' WHERE `item`='all_shares_buy'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'$cena_buy' WHERE `item`='money_shares_buy'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_i = mysql_query("SELECT `id` FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					mysql_query("UPDATE `tb_invest_users` SET `count_shares`=`count_shares`+'$count_buy', `time_buy`='".time()."', `date_buy`='".DATE("d.m.Y H:i")."' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}else{
					mysql_query("INSERT INTO `tb_invest_users` (`username`,`count_shares`,`time_buy`,`date_buy`) 
					VALUES('$user_name','$count_buy','".time()."','".DATE("d.m.Y H:i")."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares_buy'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$all_shares_buy = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='ost_shares'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$ost_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql_i = mysql_query("SELECT `count_shares` FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					$row_i = mysql_fetch_assoc($sql_i);
					$my_count_shares = $row_i["count_shares"];
				}else{
					$my_count_shares = 0;
				}

				$result_text = "OK";
				$message_text = "Вы успешно приобрели ".count_text($count_buy, "акций", "акцию", "акции", "").".";
				$message_text = '{"SysCntShares":"'.$ost_shares.'", "InvCntShares":"'.$all_shares_buy.'", "MyCntShares":"'.$my_count_shares.'", "MsgTxt":"'.$message_text.'"}';

				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

		}elseif($option == "SellShares") {
			$count_sell = ( isset($_POST["count_sell"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["count_sell"])) ) ? htmlspecialchars(trim($_POST["count_sell"])) : false;
			$cena_one_sell = ( isset($_POST["cena_one_sell"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", htmlspecialchars($_POST["cena_one_sell"]))) ) ) ? abs(str_replace(",", ".", htmlspecialchars($_POST["cena_one_sell"]))) : false;

			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_min_shares_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cena_min_shares_birj = number_format(mysql_result($sql,0,0), 2, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_max_shares_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cena_max_shares_birj = number_format(mysql_result($sql,0,0), 2, ".", "");

			$sql_i = mysql_query("SELECT `count_shares` FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_i)>0) {
				$row_i = mysql_fetch_assoc($sql_i);
				$my_count_shares = $row_i["count_shares"];
			}else{
				$my_count_shares = 0;
			}

			$sql_birj = mysql_query("SELECT sum(`count_shares`) FROM `tb_invest_birj` WHERE `seller_name`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$my_count_shares_in_birj = mysql_result($sql_birj,0,0);

			if($count_sell==false) {
				$result_text = "ERROR"; $message_text = "Укажите количество акций для продажи!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($my_count_shares < 1) {
				$result_text = "ERROR"; $message_text = "У Вас нет акций для продажи!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($count_sell < 1) {
				$result_text = "ERROR"; $message_text = "Минимум для продажи 1 акция!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($count_sell > $my_count_shares) {
				$result_text = "ERROR"; $message_text = "У вас нет столько акций!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($count_sell > ($my_count_shares-$my_count_shares_in_birj)) {
				$result_text = "ERROR"; $message_text = "Вы можете выставить на продажу только ".count_text(($my_count_shares-$my_count_shares_in_birj), "акций", "акцию", "акции", "").", так как Вы уже выставили на биржу ".count_text($my_count_shares_in_birj, "акций", "акцию", "акции", "").".";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($cena_one_sell == false) {
				$result_text = "ERROR"; $message_text = "Укажите стоимость за 1 акцию!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($cena_one_sell < $cena_min_shares_birj | $cena_one_sell > $cena_max_shares_birj) {
				$result_text = "ERROR"; $message_text = "Стоимость за 1 акцию должна быть в пределах от $cena_min_shares_birj до $cena_max_shares_birj руб.";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				$cena_sell = number_format(($count_sell * $cena_one_sell), 2, ".", "");

				mysql_query("INSERT INTO `tb_invest_birj` (`seller_name`,`count_shares`,`money_one`,`money_sum`,`date_op`,`time_op`,`ip`) 
				VALUES('$user_name','$count_sell','$cena_one_sell','$cena_sell','".DATE("d.m.Y H:i")."','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$result_text = "OK";
				$message_text = "Вы успешно выставили на продажу ".count_text($count_sell, "акций", "акцию", "акции", "")." на общую сумму $cena_sell руб.";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}


		}elseif($option == "LoadHistory" && $type == "Buy") {
			$message_text = "";
			$message_text.= '<table class="tables_inv" style="margin:1px auto;">';
			$message_text.= '<thead><tr align="center"><th>Дата операции</th><th>Количество акций</th><th>Сумма операции</th></tr></thead>';
			$message_text.= '<tbody>';
			$sql_h = mysql_query("SELECT * FROM `tb_invest_history` WHERE `username`='$user_name' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_h)>0) {
				while ($row_h = mysql_fetch_assoc($sql_h)) {
					$message_text.= '<tr align="center">';
						$message_text.= '<td><b>'.DATE("d.m.Yг. H:i", $row_h["time_op"]).'</b></td>';
						$message_text.= '<td><b>'.$row_h["count_shares"].'</b> шт.</td>';
						$message_text.= '<td><b>'.number_format($row_h["money"], 2, ".", "`").'</b> руб.</td>';
					$message_text.= '</tr>';
				}
			}else{
				$message_text.= '<tr align="center"><td colspan="3"><b>Нет данных</b></td></tr>';
			}
			$message_text.= '</tbody>';
			$message_text.= '</table>';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));


		}elseif($option == "DelBirj") {
			$id_dell = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? htmlspecialchars(trim($_POST["id"])) : false;

			$sql_birj = mysql_query("SELECT `id`,`seller_name` FROM `tb_invest_birj` WHERE `id`='$id_dell'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_birj)>0) {
				$row_birj = mysql_fetch_assoc($sql_birj);

				if(strtolower($row_birj["seller_name"])==strtolower($user_name)) {
					mysql_query("DELETE FROM `tb_invest_birj` WHERE `id`='".$row_birj["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$result_text = "OK"; $message_text = "Вы успешно сняли акции с продажи!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					$result_text = "ERROR"; $message_text = "Это не Ваши акции, снятие с биржи не возможно!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}else{
				$result_text = "ERROR"; $message_text = "Ошибка удаления акций с биржи!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

		}elseif($option == "BuyBirj") {
			$id_buy = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? htmlspecialchars(trim($_POST["id"])) : false;

			$sql_birj = mysql_query("SELECT * FROM `tb_invest_birj` WHERE `id`='$id_buy'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_birj)>0) {
				$row_birj = mysql_fetch_assoc($sql_birj);

				if(strtolower($row_birj["seller_name"])==strtolower($user_name)) {
					$result_text = "ERROR"; $message_text = "Операция не возможна! Вы не можете купить акции у самого себя.";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif($user_money_inv < $row_birj["money_sum"]) {
					$result_text = "ERROR"; $message_text = "На Вашем счету недостаточно средств для покупки акций.\nНеобходимо пополнить баланс инвестора. Требуемая сумма ".number_format($row_birj["money_sum"], 2, ".", "`")." руб. ";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}else{
					$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='comis_sys_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$comis_sys_birj = number_format(mysql_result($sql,0,0), 0, ".", "");

					$money_add_seller = ($row_birj["money_sum"] * (100-$comis_sys_birj)/100);
					$money_add_seller = number_format($money_add_seller, 2, ".", "");

					mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_add_seller' WHERE `username`='".$row_birj["seller_name"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("UPDATE `tb_invest_users` SET `count_shares`=`count_shares`-'".$row_birj["count_shares"]."' WHERE `username`='".$row_birj["seller_name"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','".$row_birj["seller_name"]."','0','".DATE("d.m.Y H:i")."','".time()."','$money_add_seller','Зачисление средств от продажи акций на бирже, ID:".$row_birj["id"]."','Зачислено','prihod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("DELETE FROM `tb_invest_users` WHERE `username`='".$row_birj["seller_name"]."' AND `count_shares`='0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("UPDATE `tb_users` SET `money_inv`=`money_inv`-'".$row_birj["money_sum"]."', `investor`='1' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$sql_i = mysql_query("SELECT `id` FROM `tb_invest_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					if(mysql_num_rows($sql_i)>0) {
						mysql_query("UPDATE `tb_invest_users` SET `count_shares`=`count_shares`+'".$row_birj["count_shares"]."', `time_buy`='".time()."', `date_buy`='".DATE("d.m.Y H:i")."' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					}else{
						mysql_query("INSERT INTO `tb_invest_users` (`username`,`count_shares`,`time_buy`,`date_buy`) 
						VALUES('$user_name','".$row_birj["count_shares"]."','".time()."','".DATE("d.m.Y H:i")."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					}
					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_birj["money_sum"]."','Покупка акций на бирже: ".$row_birj["count_shares"]." шт.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("INSERT INTO `tb_invest_birj_history` (`seller_name`,`buyer_name`,`count_shares`,`money`,`money_seller`,`date_op`,`time_op`,`ip`) 
					VALUES('".$row_birj["seller_name"]."','$user_name','".$row_birj["count_shares"]."','".$row_birj["money_sum"]."','$money_add_seller','".DATE("d.m.Y H:i")."','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'".$row_birj["count_shares"]."' WHERE `item`='count_shares_buy_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'".$row_birj["money_sum"]."' WHERE `item`='money_shares_buy_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'".($row_birj["money_sum"]-$money_add_seller)."' WHERE `item`='money_sys_birj'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("DELETE FROM `tb_invest_birj` WHERE `id`='".$row_birj["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					if($user_investor==0) {
						$sql = mysql_query("SELECT * FROM `tb_invest_welcome`") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						if(mysql_num_rows($sql)>0) {
							$row = mysql_fetch_assoc($sql);
							$title = isset($row["title_text"]) ? trim($row["title_text"]) : false;
							$description = isset($row["desc_text"]) ? trim($row["desc_text"]) : false;

							if($title != false && $description != false) {
								mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
								VALUES('$user_name','Система','$title','$description','0','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}

					$result_text = "OK";
					$message_text = "Вы успешно приобрели ".count_text($row_birj["count_shares"], "акций", "акцию", "акции", "")." у инвестора ".$row_birj["seller_name"].".";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}else{
				$result_text = "ERROR"; $message_text = "Ошибка акции не найдены!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}


		}elseif($option == "SentMailAdmin") {
			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 100) : false;
			$description = (isset($_POST["description"])) ? limpiarez($_POST["description"]) : false;
			if (get_magic_quotes_gpc()) { $description = stripslashes($description); }

			if($title==false) {
				$result_text = "ERROR"; $message_text = "Вы не указали тему сообщения!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($description==false) {
				$result_text = "ERROR"; $message_text = "Вы не указали текст сообщения!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
				VALUES('Admin','$user_name','$title','$description','0','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$result_text = "OK";
				$message_text = "Ваше сообщение успешно отправлено администрации сайта!";
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
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>