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

function clear_html_scripts_tags($text) {
	$text = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $text);
	$text = preg_replace("'<script[^>]*?>.*?</script>'si", "", $text);
	$text = preg_replace("#<a\s+href=['|\"]?([^\#]+?)['|\"]?\s.*>(?!<img).+?</a>#i","",$text);
	$text = preg_replace("#http://[^\s]+#i", "", $text);
	$text = preg_replace("#www\.[-\d\w\._&\?=%]+#i", "", $text);
	$text = str_replace("\n\n", "\n", $text);
	$text = strip_tags(($text));
	return $text;
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

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
		$my_lastiplog = getRealIP();

		$sql_user = mysql_query("SELECT `id`,`username`,`money_rb`,`reiting`,`avatar`,`ref_back_all`,`country_cod` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_money_rb = $row_user["money_rb"];
			$user_reiting = $row_user["reiting"];
			$user_avatar = $row_user["avatar"];
			$user_ref_back_all = $row_user["ref_back_all"];
			$user_country_cod = $row_user["country_cod"];
		}else{
			if(isset($_SESSION)) session_destroy();

			$result_text = "ERROR"; $message_text = "Пользователь не идентифицирован!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

		if($option == "SetBoard") {
			$stavka = ( isset($_POST["stavka"]) && preg_match("|^[\d]{1,11}$|", str_replace(",", ".", trim($_POST["stavka"]))) ) ? abs(intval(str_replace(",", ".", trim($_POST["stavka"])))) : false;
			$comment = (isset($_POST["comment"])) ? limpiarez(clear_html_scripts_tags($_POST["comment"])) : false;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_add'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cena_board_add = number_format(mysql_result($sql,0,0), 2, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cena_board_comm = number_format(mysql_result($sql,0,0), 2, ".", "");

			$sql = mysql_query("SELECT `user_id`,`count`,`count_now` FROM `tb_board` WHERE `lider`='1' LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_assoc($sql);
				$user_id_b = $row["user_id"];
				$count_b = $row["count"];
				$count_now_b = $row["count_now"];
			}else{
				$user_id_b = false;
				$count_b = false;
				$count_now_b = false;
			}

			if($comment != false) { $sum_pay = ($stavka + $cena_board_comm); }else{ $sum_pay = $stavka; }

			if($user_id == $user_id_b) {
				$result_text = "ERROR"; $message_text = "Ваш аватар уже размещен на доске почета!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($stavka == false) {
				$result_text = "ERROR"; $message_text = "Укажите сумму ставки, ставка должна быть целым положительным числом!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($stavka < $cena_board_add) {
				$result_text = "ERROR"; $message_text = "Минимальная ставка $cena_board_add руб.";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_money_rb < $sum_pay) {
				$result_text = "ERROR"; $message_text = "На вашем рекламном счету недостаточно средств!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($count_now_b >= $stavka) {
				$result_text = "ERROR"; $message_text = "Ваша ставка не перебивает ставку лидера!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_reit'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$cena_board_reit = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$cena_board_reit', `kon_board_m`=`kon_board_m`+'1', `data_board`='".time()."', `money_rb`=`money_rb`-'$sum_pay' WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sum_pay_k=($sum_pay/100)*10;
				mysql_query("UPDATE `tb_config` SET `price`=`price`+'$sum_pay_k' WHERE `item`='board_bonus'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$sum_pay','Ставка в конкурсе (Доска почета)','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				
				//stat_pay('board', $cena_board_comm);

				mysql_query("UPDATE `tb_board` SET `lider`='0',`count_now`='0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_c = mysql_query("SELECT `id` FROM `tb_board` WHERE `status`='0' AND `user_name`='$user_name' AND `date`='".DATE("d.m.Y")."' ORDER BY `id` DESC ") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_c)>0) {
					$row_c = mysql_fetch_assoc($sql_c);
					mysql_query("UPDATE `tb_board` SET `lider`='1', `comment`='$comment', `time`='".time()."', `time_end`='".(time()+5*60)."', `count`=`count`+'$stavka', `count_now`='$stavka' WHERE `id`='".$row_c["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}else{
					mysql_query("INSERT INTO `tb_board` (`status`,`lider`,`user_id`,`user_name`,`comment`,`date`,`time`,`time_end`,`count`,`count_now`,`ip`) 
					VALUES('0','1','$user_id','$user_name','$comment','".DATE("d.m.Y")."','".time()."','".(time()+5*60)."','$stavka','$stavka','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}

				$result_text = "OK";
				$message_text = "Ваша ставка ".number_format($stavka, 2, ".", "")." руб. принята! Вы успешно размещены на доске почёта";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}


		}elseif($option == "LoadLiders") {
			$message_text = false;
			$message_text.= '<table class="tables_inv">';
			$message_text.= '<thead>';
				$message_text.= '<tr align="center">';
					$message_text.= '<th>#</th>';
					$message_text.= '<th>Логин</th>';
					$message_text.= '<th>Общая сумма ставок (руб.)</th>';
					$message_text.= '<th>Дата последней ставки</th>';
				$message_text.= '</tr>';
			$message_text.= '</thead>';
			$message_text.= '<tbody>';
				$sql = mysql_query("SELECT * FROM `tb_board` WHERE `status`='0' AND `date`='".DATE("d.m.Y")."' ORDER BY `count` DESC, `time` DESC LIMIT 10") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql)>0) {
					$number = 0;
					while ($row = mysql_fetch_assoc($sql)) {
						$number++;
						$message_text.= '<tr align="center">';
							$message_text.= '<td style="font-weight:'.($number==1 ? "bold" : "normal").'">'.$number.'</td>';
							$message_text.= '<td style="font-weight:'.($number==1 ? "bold" : "normal").'"><a href="/wall?uid='.$row["user_id"].'" target="_blank">'.$row["user_name"].'</a></td>';
							$message_text.= '<td style="font-weight:'.($number==1 ? "bold" : "normal").'"><span style="color: #FF0000;">'.$row["count"].'</span></td>';
							$message_text.= '<td style="font-weight:'.($number==1 ? "bold" : "normal").'">'.DATE("d.m.Y H:i:s", $row["time"]).'</td>';
						$message_text.= '</tr>';
					}
				}
			$message_text.= '<tbody>';
			$message_text.= '</table>';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));


		}elseif($option == "LoadBoard") {
			$message_text = false;
			$sql_all_s = mysql_query("SELECT sum(count) FROM `tb_board` WHERE `status`='0' ORDER BY `count` DESC, `time` DESC");
	        $all_count = mysql_result($sql_all_s,0,0);
	        
	        $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='board_bonus' AND `howmany`='1'");
		$sum_dch = mysql_result($sql,0,0);

			$sql_b = mysql_query("SELECT `user_id`,`user_name`,`comment`,`count`,`count_now` FROM `tb_board` WHERE `lider`='1' LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_b)>0) {
				$row_b = mysql_fetch_assoc($sql_b);
				$board_user_id = $row_b["user_id"];
				$board_user_name = $row_b["user_name"];
				$board_user_comment = $row_b["comment"];
				$board_user_count = $row_b["count"];
				$board_user_count_now = $row_b["count_now"];

				$message_text.= '<table class="tables_inv" style="padding:0px; width:100%; margin:0 auto; margin-top:5px;">';
				$message_text.= '<tr align="center">';
					$message_text.= '<td colspan="3" style="padding:6px;">';
						//if($user_country_cod != false) $message_text.= '<img src="img/flags/'.strtolower($user_country_cod).'.gif" border="0" alt="" title="'.get_country($user_country_cod).'" style="margin:0px 10px 0px; padding:0;" align="absmiddle" />';
						$message_text.= '<span style="color:#ab0606;">'.$board_user_name.'</span>';
						$message_text.= '<span style="color:#808080; cursor:help;" title="Авто-рефбек"> - '.$user_ref_back_all.'%</span>';
					$message_text.= '</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr align="center">';
					$message_text.= '<td width="30%"><span style="font-size:16px; cursor:help;" title="Общая сумма ставок">&uarr; '.$board_user_count.'</span></td>';
					$message_text.= '<td width="40%"><a href="/wall?uid='.$board_user_id.'" title="Стена пользователя '.$board_user_name.'"><img src="avatar/'.$user_avatar.'" class="avatar_board" width="65" height="65" border="0" title="Стена пользователя '.$board_user_name.'" align="middle" alt="" /></a></td>';
					$message_text.= '<td width="30%"><span style="font-size:16px; cursor:help;" title="Ставка на текущий момент">'.$board_user_count_now.' &darr;</span></td>';
				$message_text.= '</tr>';
				if($board_user_comment!=false) $message_text.= '<tr align="center"><td colspan="3" style="padding:4px;">'.$board_user_comment.'</td></tr>';
				$message_text.= '<tr align="center">';
					$message_text.= '<td colspan="3" style="padding:4px;">';
						$message_text.= board_status($user_reiting);
						$message_text.= '<span style="color:'.($user_reiting>0 ? "#2E8B57;" : "#FF0000;").' cursor:help;" title="Рейтинг">&nbsp;&nbsp;'.($user_reiting>0 ? "+" : "-").abs(round($user_reiting,2)).'</span>';
					$message_text.= '</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr align="center" id="board_noref">';
					$message_text.= '<td colspan="3" style="padding:4px;"><a style="cursor:pointer;" onclick="add_to_ref(\''.$board_user_id.'\', \''.$board_user_name.'\');">&raquo; Вступить в мою команду &laquo;</a></td>';
				$message_text.= '</tr>';
				$message_text.= '<tr align="center">';
					$message_text.= '<td colspan="3" style="padding:4px;"><a style="color:#0A92BF; cursor:pointer;" href="/wall?uid='.$board_user_id.'">&raquo; Найдете меня здесь &laquo;</a></td>';
				$message_text.= '</tr>';
					$message_text.= '<tr align="center">';
		$message_text.= '<td colspan="3" style="padding:4px;"><b style="letter-spacing:normal;">Призовой фонд: <span class="text-red">'.number_format($all_count, 2, ".", "").' руб.</span></b></td>';
		$message_text.= '</tr>';
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='board_bonus' AND `howmany`='1'");
		$sum_dch = mysql_result($sql,0,0);
		$message_text.= '<tr align="center">';
			$message_text.= '<td colspan="3" style="padding:4px;"><a href="/board"><div style="color: #FF0000;">Накопительный бонус: '.round($sum_dch,2).' руб. </div></a></td>';
		$message_text.= '</tr>';
				$message_text.= '<tr align="center">';
					$message_text.= '<td colspan="3" style="padding:4px;"><a href="/board"><div style="color: #FF0000;">&raquo; Хочу сюда &laquo;</div></a></td>';
				$message_text.= '</tr>';
				$message_text.= '</table>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "ERROR LOAD BOARD!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

		}else{
			$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "Необходимо авторизоваться!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>