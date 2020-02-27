<?php

session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text, $status_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text), "status" => iconv("CP1251", "UTF-8", $status_text))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
//	$mensaje = str_replace('"', "&#34;", $mensaje);
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

function clear_html_tags($text) {
	$text = trim($text);
	$text = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", '', $text);
	$text = preg_replace("'<script[^>]*?>.*?</script>'si", "", $text);
	$text = preg_replace("#<a\s+href=['|\"]?([^\#]+?)['|\"]?\s.*>(?!<img).+?</a>#i", '', $text);
	$text = preg_replace("#http://[^\s]+#i", '', $text);
	$text = preg_replace("#https://[^\s]+#i", '', $text);
	$text = preg_replace("#www\.[-\d\w\._&\?=%]+#i", "", $text);
	return $text;
}

function LastTime($times){
	if(DATE("d.m.Y", $times) == DATE("d.m.Y")) {
		$LastTime = '<span class="knb-date-s" >Сегодня в <b>'.DATE("H:i", $times).'</b></span>';
	}elseif(DATE("d.m.Y", $times) == DATE("d.m.Y", (time()-24*60*60))) {
		$LastTime = '<span class="knb-date-v" >Вчера в <b>'.DATE("H:i", $times).'</b></span>';
	}else{
		$LastTime = '<span class="knb-date" >'.DATE("<b>d.m.Yг.</b> в <b>H:i</b>", $times).'</span>';
	}
	return $LastTime;
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
	exit(my_json_encode($ajax_json, "ERROR", $message_text, ""));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

function GenKey($number) {
	$arr = array('a','b','c','d','e','f',
	'g','h','i','j','k','l',
	'm','n','o','p','r','s',
	't','u','v','x','y','z',
	'A','B','C','D','E','F',
	'G','H','I','J','K','L',
	'M','N','O','P','R','S',
	'T','U','V','X','Y','Z',
	'1','2','3','4','5','6',
	'7','8','9','0');
	$pass = "";
	for($i = 0; $i < $number; $i++) {
		$index = rand(0, count($arr) - 1);
		$pass .= $arr[$index];
	 }
	return $pass;
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require_once(ROOT_DIR."/merchant/func_mysql.php");
	
	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
	$option = (isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
	$token_post = (isset($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_POST["token"]))) ? strtolower(limpiarez($_POST["token"])) : false;
	$knb_stavka = isset($_POST["knb_stavka"]) ? number_format(abs(str_replace(",", ".", trim($_POST["knb_stavka"]))), 2, ".", "") : false;
	$knb_cnt = (isset($_POST["knb_cnt"]) && preg_match("|^[\d]{1,1}$|", str_replace(",", ".", trim($_POST["knb_cnt"]))) ) ? abs(intval(str_replace(",", ".", trim($_POST["knb_cnt"])))) : false;
	$knb_obj = (isset($_POST["knb_obj"]) && preg_match("|^[\d]{1,1}$|", htmlspecialchars(trim($_POST["knb_obj"]))) ) ? intval(trim($_POST["knb_obj"])) : false;
	$knb_obj_play = (isset($_POST["knb_obj_play"]) && preg_match("|^[\d]{1,1}$|", htmlspecialchars(trim($_POST["knb_obj_play"]))) ) ? intval(trim($_POST["knb_obj_play"])) : false;
	if(is_array($_POST["id"])) {
		$knb_ids = array_filter($_POST["id"]);
	} else {
		$knb_id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
	}
	$my_lastiplog = getRealIP();
	$security_key = "IDFHJZ349809szY0zsohisHd38dhsldsif";// Secret Key (Секретный ключ. Внимание: Это уникальное поле !!!).

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$sql_user = mysql_query("SELECT `id`,`username`,`referer`,`money_rb`,`reiting`,`ban_date` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_referer = $row_user["referer"];
			$user_money_rb = $row_user["money_rb"];
			$user_reit = $row_user["reiting"];
			$user_ban_date = $row_user["ban_date"];
		}else{
			$user_id = false;
			$user_name = false;
			$user_money_rb = false;
			$user_referer = false;
			$user_reit = false;
			$user_ban_date = false;
			$user_status = false;
		}
	}else{
		$result_text = "ERROR"; $message_text = "Необходимо авторизоваться!";
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
	}

	
	if($option == "confirm-add") {
		$token_setknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."SetKNB".$security_key));
		$token_funcknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."FunKNB".$security_key));
		
		if($token_post == false || $token_post != $token_setknb){
		
			$result_text = "ERROR";
			$message_text = 'Ошибка: не верный токен, попробуйте позже!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}elseif($user_ban_date > 0) {
		
			$result_text = "ERROR";
			$message_text = 'Ваш аккаунт заблокирован, участие не возможно!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));

		}else{
			
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_min'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$game_knb_min = round(number_format(mysql_result($sql,0,0), 2, ".", ""),2);
			
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_max'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$game_knb_max = round(number_format(mysql_result($sql,0,0), 2, ".", ""),2);
			
			$sum_pay_knb = $knb_stavka * $knb_cnt;
			
			if ($knb_stavka <= 0) {
				exit(my_json_encode($ajax_json, "ERROR", "Укажите сумму ставки!", ""));
			}else if ($knb_stavka < $game_knb_min) {
				exit(my_json_encode($ajax_json, "ERROR", "Минимальная сумма ставки ".$game_knb_min." руб.", ""));
			}else if ($knb_stavka > $game_knb_max) {
				exit(my_json_encode($ajax_json, "ERROR", "Cумма вашей ставки очень велика!", ""));
			}else if ($knb_cnt <= 0 || $knb_cnt > 5) {
				exit(my_json_encode($ajax_json, "ERROR", "Укажите количество игр!", ""));
			}else if ($knb_obj <= 0 || $knb_obj > 4) {
				exit(my_json_encode($ajax_json, "ERROR", "Укажите предмет игры!", ""));
			}
			
			if ($knb_cnt == 1) {
				if ($knb_obj == 1) {
					$object = "Камень";$abc = "у";
				}else if ($knb_obj == 2) {
					$object = "Ножницы";$abc = "у";
				}else if ($knb_obj == 3) {
					$object = "Бумага";$abc = "у";
				}else if ($knb_obj == 4) {
					$object = "будет выбран автоматически системой";$abc = "ы";
				}
			}elseif ($knb_cnt >= 2) {
				$object = "будет выбран автоматически системой";$abc = "ы";
			}
		
			$result_text = "OK";
			$message_text = '<div style="text-align:center; margin:5px auto 10px; line-height:16px;">';
				$message_text.= '<div>Ставка: <span class="text-green"><b>'.$knb_stavka.'</b> руб.</span> Количество игр: <b class="text-blue">'.$knb_cnt.'</b></div>';
				$message_text.= '<div>Предмет: <b class="text-grey">'.$object.'</b></div>';
				$message_text.= '<div>С Вашего рекламного счёта будет списано: <span class="text-red"><b>'.$sum_pay_knb.'</b> руб.</span></div>';
				$message_text.= '<div style="margin:5px auto;"><b>Создать игр'.$abc.'?</b></div>';
			$message_text.= '</div>';
			$message_text.= '<div style="text-align:center;">';
				$message_text.= '<span class="sd_sub green" style="min-width:30px;" onClick="FuncKNB(false, \'pay-add\', \''.$token_funcknb.'\', \'Информация\');">Да</span>';
				$message_text.= '<span class="sd_sub red" style="min-width:30px;" onClick="$(\'#LoadModal\').modalpopup(\'close\'); return false;">Нет</span>';
			$message_text.= '</div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}
	}elseif($option == "pay-add") {
		$token_funcknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."FunKNB".$security_key));
		$info_text_knb = false;
		if ($knb_cnt == 1) $info_text_knb = "ы";
		$sum_pay_knb = $knb_stavka * $knb_cnt;
		
		if($token_post == false || $token_post != $token_funcknb) {
		
			$result_text = "ERROR";
			$message_text = 'Ошибка: не верный токен, попробуйте позже!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}elseif($user_ban_date > 0) {
		
			$result_text = "ERROR";
			$message_text = 'Ваш аккаунт заблокирован, участие не возможно!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));

		}elseif ($knb_stavka <= 0 || $knb_cnt < 0 || $knb_cnt > 5 || $knb_obj < 0 || $knb_obj > 4) {
		
			exit(my_json_encode($ajax_json, "ERROR", "Обнаружена подмена данных, попробуйте поставить ставку позже !", ""));
			
		}elseif ($user_money_rb < $sum_pay_knb) {
		
			$result_text = "ERROR";
			$message_text = 'На Вашем рекламном счету недостаточно средств для создания игр'.$info_text_knb.'.';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}else{
		
			$knbstavka = $knb_stavka;
			$knbcnt = $knb_cnt;
			$sumpayknb = $knb_stavka * $knb_cnt;
			$knbobj = $knb_obj;
			
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$sumpayknb' WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_reit'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$game_knb_reit = number_format(mysql_result($sql,0,0), 0, ".", "");
			if ($game_knb_reit > 0) {
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$game_knb_reit' WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			}

			if ($knbcnt == 1) {
			
				$key = GenKey(15);
				$time = time();
				
				mysql_query("INSERT INTO `tb_game_knb_play` (`keypass`,`time_on`,`time_ne`,`sum`,`obj_on`,`obj_ne`,`ip_on`,`ip_ne`,`status`,`user_id_on`,`user_id_ne`,`user_name_on`,`user_name_ne`) 
				VALUES('".$key."','".$time."','','".$knbstavka."','','','".$my_lastiplog."','','1','".$user_id."','','".$user_name."','')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
				$id_game = mysql_insert_id();
			
				if ($knbobj == 4) $knbobj = rand(1,3);
				if ($knbobj == 1) {
					$predmet = "Камень";
				} else if ($knbobj == 2) {
					$predmet = "Ножницы";
				} else if ($knbobj == 3) {
					$predmet = "Бумага";
				}
				
				$obj = md5("ID:".$id_game.", Login:".$user_name.", Date:".DATE("d.m.Y H:i:s", $time).", Predmet:".$predmet.", Key:".$key."");
				
				mysql_query("UPDATE `tb_game_knb_play` SET `obj_on`='$obj' WHERE `id`='$id_game'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				
			}else if ($knbcnt >= 2) {
			
				$x=0;
				$idgame1 = false;$idgame2 = false;$idgame3 = false;$idgame4 = false;$idgame5 = false;
				
				while ($x<$knbcnt) {
					$x++;
					$key = GenKey(15);
					$time = time();
					
					mysql_query("INSERT INTO `tb_game_knb_play` (`keypass`,`time_on`,`time_ne`,`sum`,`obj_on`,`obj_ne`,`ip_on`,`ip_ne`,`status`,`user_id_on`,`user_id_ne`,`user_name_on`,`user_name_ne`) 
					VALUES('".$key."','".$time."','','".$knbstavka."','','','".$my_lastiplog."','','1','".$user_id."','','".$user_name."','')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
					$idgame = mysql_insert_id();
					
					$knbobj = rand(1,3);
					if ($knbobj == 1) {
						$predmet = "Камень";
					} else if ($knbobj == 2) {
						$predmet = "Ножницы";
					} else if ($knbobj == 3) {
						$predmet = "Бумага";
					}
					
					${"idgame$x"} = $idgame;
					$obj = md5("ID:".$idgame.", Login:".$user_name.", Date:".DATE("d.m.Y H:i:s", $time).", Predmet:".$predmet.", Key:".$key."");
					mysql_query("UPDATE `tb_game_knb_play` SET `obj_on`='".$obj."' WHERE `id`='".$idgame."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
					
				 }
				 
				$id_game = $idgame1." ".$idgame2." ".$idgame3." ".$idgame4." ".$idgame5;

			}
		
			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$sumpayknb','Создание $knb_cnt игр$info_text_knb КНБ [ID: $id_game], списание с рекламного счета.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			
			$result_text = "OK";
			$sql = mysql_query("SELECT money_rb, money  FROM `tb_users` WHERE `id`='".$user_id."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$mysql = mysql_fetch_assoc($sql);
			$message_text = '<span class="msg-ok" style="margin:3px auto; padding:10px;"><script>GoTab("knb-list"); $("#knb_stavka").val(\''.$knbstavka.'\'); $("#knb_cnt").val(\''.$knb_cnt.'\'); $("#knb_obj").val(\'1\'); SelObj(); $("#my_bal_os").html(\''.$mysql["money"].'\'); $("#my_bal_rs").html(\''.$mysql["money_rb"].'\'); setTimeout(function(){if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");},2000);</script>Игра успешно создана и размещена!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));

		}
	}elseif($option == "game-play") {
		$token_playknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$knb_id."PlayKNB".$security_key));

		if($token_post == false || $token_post != $token_playknb) {
		
			$result_text = "ERROR";
			$message_text = 'Ошибка: не верный токен, попробуйте позже!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}elseif($user_ban_date > 0) {
		
			$result_text = "ERROR";
			$message_text = 'Ваш аккаунт заблокирован, участие не возможно!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));

		}elseif ($knb_stavka <= 0 || $knb_cnt < 0 || $knb_cnt > 1 || $knb_obj < 0 || $knb_obj > 4) {
		
			exit(my_json_encode($ajax_json, "ERROR", "Обнаружена подмена данных, попробуйте поставить ставку позже !", ""));
			
		}elseif ($knb_obj_play == "" || $knb_obj_play > 4) {
		
			$result_text = "ERROR";
			$message_text = 'Укажите предмет игры!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}elseif ($user_money_rb < $knb_stavka) {
		
			$result_text = "ERROR";
			$message_text = 'На Вашем рекламном счету недостаточно средств.';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
			
		}else{
			
			$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `id`='$knb_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$infostav = mysql_fetch_assoc($sql);
			
			if ($user_money_rb < $infostav["sum"]) exit(my_json_encode($ajax_json, "ERROR", "Упс, вышла такая нестыковочка, будь осторожней !", ""));
			
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".$infostav["sum"]."' WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$infostav["sum"]."','Ставка в игре КНБ [ID: ".$infostav["id"]."], списание с рекламного счета','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			
			mysql_query("UPDATE `tb_game_knb_stats` SET `number`=`number`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			
			$obj_1 = md5("ID:".$infostav["id"].", Login:".$infostav["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $infostav["time_on"]).", Predmet:Камень, Key:".$infostav["keypass"]."");
			$obj_2 = md5("ID:".$infostav["id"].", Login:".$infostav["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $infostav["time_on"]).", Predmet:Ножницы, Key:".$infostav["keypass"]."");
			$obj_3 = md5("ID:".$infostav["id"].", Login:".$infostav["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $infostav["time_on"]).", Predmet:Бумага, Key:".$infostav["keypass"]."");
			if ($infostav["obj_on"] == $obj_1) {
				$on_the_stavka = 1;
			} else if ($infostav["obj_on"] == $obj_2) {
				$on_the_stavka = 2;
			}else if ($infostav["obj_on"] == $obj_3) {
				$on_the_stavka = 3;
			}
			$otvok = false;
			if ($knb_obj_play == 4) $knb_obj_play = rand(1,3);
			if ($on_the_stavka == 1 && $knb_obj_play == 1 || $on_the_stavka == 2 && $knb_obj_play == 2 || $on_the_stavka == 3 && $knb_obj_play == 3) {
				$otvok = 1;
			}else if ($on_the_stavka == 1 && $knb_obj_play == 2 || $on_the_stavka == 2 && $knb_obj_play == 3 || $on_the_stavka == 3 && $knb_obj_play == 1) {
				$otvok = 2;
			}else if ($knb_obj_play == 1 && $on_the_stavka == 2 || $knb_obj_play == 2 && $on_the_stavka == 3 || $knb_obj_play == 3 && $on_the_stavka == 1) {
				$otvok = 3;
			}
			
			$time = time();
			if ($knb_obj_play == 1) {
				$predmet = "Камень";
			} else if ($knb_obj_play == 2) {
				$predmet = "Ножницы";
			} else if ($knb_obj_play == 3) {
				$predmet = "Бумага";
			}				
			$obj_ne = md5("ID:".$infostav["id"].", Login:".$user_name.", Date:".DATE("d.m.Y H:i:s", $time).", Predmet:".$predmet.", Key:".$infostav["keypass"]."");
			mysql_query("UPDATE `tb_game_knb_play` SET `status`='2', `time_ne`='".$time."', `ip_ne`='".$my_lastiplog."', `user_id_ne`='".$user_id."', `user_name_ne`='".$user_name."', `obj_ne`='".$obj_ne."', `winner`='".$otvok."' WHERE `id`='".$infostav["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			
			$informer = false;
			if ($otvok == 1) {
				mysql_query("UPDATE `tb_game_knb_stats` SET `draws`=`draws`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'".$infostav["sum"]."' WHERE `id`='".$user_id."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$infostav["sum"]."','Возврат средств с игры КНБ [ID: ".$infostav["id"].", Ничья], зачисление на рекламный счет.','Зачислено','popoln')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'".$infostav["sum"]."' WHERE `id`='".$infostav["user_id_on"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','".$infostav["user_name_on"]."','".$infostav["user_id_on"]."','".DATE("d.m.Y H:i")."','".time()."','".$infostav["sum"]."','Возврат средств с игры КНБ [ID: ".$infostav["id"].", Ничья], зачисление на рекламный счет.','Зачислено','popoln')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$informer = '<span class="text-grey"><b>Ничья !</b></span>';
			}else if ($otvok == 2) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_comis'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$game_knb_comis = number_format(mysql_result($sql,0,0), 0, ".", "");
				$sum_poll = $infostav["sum"] + $infostav["sum"];
				$summ_poll = $sum_poll / 100 * $game_knb_comis;
				$summ_poll = $sum_poll - $summ_poll;
				mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$summ_poll."' WHERE `id`='".$infostav["user_id_on"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				mysql_query("UPDATE `tb_game_knb_stats` SET `defeats`=`defeats`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_victory'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$game_knb_victory = number_format(mysql_result($sql,0,0), 0, ".", "");
				if ($game_knb_victory > 0) {
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$game_knb_victory' WHERE `id`='".$infostav["user_id_on"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				}
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','".$infostav["user_name_on"]."','".$infostav["user_id_on"]."','".DATE("d.m.Y H:i")."','".time()."','".$summ_poll."','Победа в игре КНБ [ID: ".$infostav["id"]."], зачисление на основной счет.','Зачислено','popoln')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$informer = '<span class="text-red">Вы<br><b>проиграли</b></span>';
			}else if ($otvok == 3) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_comis'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$game_knb_comis = number_format(mysql_result($sql,0,0), 0, ".", "");
				$sum_poll = $infostav["sum"] + $infostav["sum"];
				$summ_poll = $sum_poll / 100 * $game_knb_comis;
				$summ_poll = $sum_poll - $summ_poll;
				mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$summ_poll."' WHERE `id`='".$user_id."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				mysql_query("UPDATE `tb_game_knb_stats` SET `victories`=`victories`+'1', `sum_victories`=`sum_victories`+'".$summ_poll."' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_victory'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$game_knb_victory = number_format(mysql_result($sql,0,0), 0, ".", "");
				if ($game_knb_victory > 0) {
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$game_knb_victory' WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				}
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$summ_poll."','Победа в игре КНБ [ID: ".$infostav["id"]."], зачисление на основной счет.','Зачислено','popoln')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				$informer = '<span class="text-green">Вы<br><b>выиграли</b></span>';
			}

			$result_text = "OK";
			$sql = mysql_query("SELECT money_rb, money  FROM `tb_users` WHERE `id`='".$user_id."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$mysql = mysql_fetch_assoc($sql);
			$message_text= '<script>$("#my_bal_os").html(\''.$mysql["money"].'\'); $("#my_bal_rs").html(\''.$mysql["money_rb"].'\'); $("#knb-status-'.$knb_id.'").html(\''.$informer.'\'); setTimeout(function(){$("#knb-'.$knb_id.'").fadeOut("50", function() {$(this).remove();}); $("#load-knb-script").html("");},2000);</script>';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));

		}
	}elseif($option == "knb-list") {
		
		$post_id = false;
		$message_text = false;
		
		$message_text.= '<h3 class="sp" style="font-weight:bold;">Список активных игр</h3>';
		$message_text.= '<table class="tables-game" id="knb-table-list">';
			$message_text.= '<thead><tr>';
				$message_text.= '<th>ID</th>';
				$message_text.= '<th style="width:30px;">MD5</th><th style="width:120px;">Пользователь</th>';
				$message_text.= '<th style="width:60px;">Ставка</th><th style="width:120px;">Дата</th>';
				$message_text.= '<th style="width:150px;">Предмет</th><th style="width:120px;">Действие</th>';
			$message_text.= '</tr></thead>';
			$message_text.= '<tbody>';
			
				$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `status`='1' ORDER BY `id` ASC LIMIT 100") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				if(mysql_num_rows($sql)>0) {
					$i = 0;
					while ($row = mysql_fetch_assoc($sql)) {
						$i++;
						
						$message_text.= '<tr class="knb-tr" data-id="'.$row["id"].'" id="knb-'.$row["id"].'"><td align="center">'.$row["id"].'</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<span class="protect-md5" title="Контроль невмешательства MD5" onClick="FuncKNB(\''.$row["id"].'\', \'knb-md5\', false, \'Контроль невмешательства MD5\', \'500\');"></span>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<a href="/wall?uid='.$row["user_id_on"].'" target="_blank" title="Перейти на стену пользователя '.$row["user_name_on"].'"" style="font-weight:bold;">'.$row["user_name_on"].'</a>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<b class="text-green">'.p_floor($row["sum"],2,'.','`').'</b>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<div title="Дата создания игры">';
									$message_text.= LastTime($row["time_on"]);
								$message_text.= '</div>';
							$message_text.= '</td>';
							$message_text.= '<td align="center"><div>';
								if ($row["user_id_on"] == $user_id) {

									$obj_1 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Камень, Key:".$row["keypass"]."");
									$obj_2 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Ножницы, Key:".$row["keypass"]."");
									$obj_3 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Бумага, Key:".$row["keypass"]."");
				
									if ($row["obj_on"] == $obj_1) {
										$knb_rock = 'act';$knb_scissors = 'nohover';$knb_paper = 'nohover';$knb_help = 'nohover';
									} else if ($row["obj_on"] == $obj_2) {
										$knb_rock = 'nohover';$knb_scissors = 'act';$knb_paper = 'nohover';$knb_help = 'nohover';
									}else if ($row["obj_on"] == $obj_3) {
										$knb_rock = 'nohover';$knb_scissors = 'nohover';$knb_paper = 'act';$knb_help = 'nohover';
									} else {
										$knb_rock = 'nohover';$knb_scissors = 'nohover';$knb_paper = 'nohover';$knb_help = 'act';
									}
									
									$message_text.= '<div class="knb-opacity">';
										$message_text.= '<span class="game-object knb-rock '.$knb_rock.'" title="Камень"></span>';
										$message_text.= '<span class="game-object knb-scissors '.$knb_scissors.'" title="Ножницы"></span>';
										$message_text.= '<span class="game-object knb-paper '.$knb_paper.'" title="Бумага"></span>';
										$message_text.= '<span class="game-object knb-help '.$knb_help.'" title="Автовыбор"></span>';
									$message_text.= '</div>';
								}else{
									$message_text.= '<span class="game-object knb-rock" title="Камень" onClick="SelObjPlay(this, \''.$row["id"].'\', \'1\');"></span>';
									$message_text.= '<span class="game-object knb-scissors" title="Ножницы" onClick="SelObjPlay(this, \''.$row["id"].'\', \'2\');"></span>';
									$message_text.= '<span class="game-object knb-paper" title="Бумага" onClick="SelObjPlay(this, \''.$row["id"].'\', \'3\');"></span>';
									$message_text.= '<span class="game-object knb-help" title="Автовыбор" onClick="SelObjPlay(this, \''.$row["id"].'\', \'4\');"></span>';
									$message_text.= '<input type="hidden" id="knb_obj_play_'.$row["id"].'" value="">';
								}
							$message_text.= '</div></td>';
							$message_text.= '<td align="center" id="knb-status-'.$row["id"].'">';
								if ($row["user_id_on"] == $user_id) {
									$token_cancelknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$row["id"]."СancelKNB".$security_key));
									
									$message_text.= '<span class="sd_sub red" style="width:70px;" onClick="FuncKNB(\''.$row["id"].'\', \'confirm-cancel\', \''.$token_cancelknb.'\', \'Подтверждение\', \'500\');">Отменить</span>';
								}else{
									$token_playknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$row["id"]."PlayKNB".$security_key));
									
									$message_text.= '<span class="sd_sub green" style="width:70px;" onClick="FuncKNB(\''.$row["id"].'\', \'game-play\', \''.$token_playknb.'\');">Играть</span>';
								}
							$message_text.= '</td>';
						$message_text.= '</tr>';
						
						$post_id = $row["id"];
					}
				}else{
				$message_text.= '<tr id="knb-warning">';
					$message_text.= '<td colspan="7" style="text-align:center; padding:3px;"><span class="msg-w" style="margin:0 auto;">На данный момент созданных игр нет</span></td>';
				$message_text.= '</tr>';
				}
				
			$message_text.= '</tbody>';
		$message_text.= '</table>';
		if ($post_id == false) { $info_id = 'false'; }else{ $info_id = $post_id; }
		$message_text.= '<script>load_knb_id = '.$info_id.';</script>';
		
		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
		
	}elseif($option == "confirm-cancel") {
		
		$token_cancelknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$knb_id."СancelKNB".$security_key));
		
		if($token_post == false || $token_post != $token_cancelknb){
		
			$result_text = "ERROR";
			$message_text = 'Ошибка: не верный токен, попробуйте позже!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
		
		}else{
			
			$token_yescancelknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$knb_id."YesСancelKNB".$security_key));
			
			$result_text = "OK";
			
			$message_text = '<div style="text-align:center; margin:5px auto 10px; line-height:16px;">';
				$message_text.= '<div>Вы уверены что хотите отменить игру ID:<b class="text-grey">'.$knb_id.'</b>?</div>';
			$message_text.= '</div>';
			$message_text.= '<div style="text-align:center;">';
				$message_text.= '<span class="sd_sub green" style="min-width:30px;" onClick="FuncKNB(\''.$knb_id.'\', \'yes-cancel\', \''.$token_yescancelknb.'\', \'Информация\');">Да</span>';
				$message_text.= '<span class="sd_sub red" style="min-width:30px;" onClick="$(\'#LoadModal\').modalpopup(\'close\'); return false;">Нет</span>';
			$message_text.= '</div>';
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
		
		}
	}elseif($option == "yes-cancel") {
	
		$token_yescancelknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$knb_id."YesСancelKNB".$security_key));
		
		if($token_post == false || $token_post != $token_yescancelknb){
		
			$result_text = "ERROR";
			$message_text = 'Ошибка: не верный токен, попробуйте позже!';
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
		
		}else{

			$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `id`='$knb_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$mysql = mysql_fetch_assoc($sql);
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'".$mysql["sum"]."' WHERE `id`='".$mysql["user_id_on"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			mysql_query("DELETE FROM `tb_game_knb_play` WHERE `id`='$knb_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			
			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$mysql["sum"]."','Удаление игры КНБ [ID: $knb_id], зачисление на рекламный счет.','Зачислено','popoln')") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));

			$result_text = "OK";
			$sql = mysql_query("SELECT money_rb, money  FROM `tb_users` WHERE `id`='".$user_id."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			$mysql = mysql_fetch_assoc($sql);
			$message_text ='<span class="msg-ok" style="margin:3px auto; padding:10px;"><script>$("#my_bal_os").html(\''.$mysql["money"].'\'); $("#my_bal_rs").html(\''.$mysql["money_rb"].'\'); $("#knb-'.$knb_id.'").remove(); setTimeout(function(){if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");},1000);</script>Игра ID:<b>'.$knb_id.'</b> успешно отменена!</span>';
		
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
		
		}
	}elseif($option == "knb-check") {
		
		$message_text = array();
		foreach($knb_ids as $key => $value)
		{
			$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `id`='$value' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_assoc($sql);
				if($row["status"] == 2){
					if ($row["winner"] == 1){
						$status = 3;
					}else if ($row["winner"] == 2){
						$status = 2;
					}else if ($row["winner"] == 3){
						$status = 1;
					}
					$message_text[] = array(
					  "id" => $row["id"],
					  "status" => $status,
					  "u_name_1" => $row["user_name_on"],
					  "u_name_2" => $row["user_name_ne"]
					);
				}
			} else {
				$message_text[] = array(
					"id" => $value,
					"status" => -1,
					"u_name_1" => "",
					"u_name_2" => ""
				);
			}
		} 
		
		$result_text = "OK";
		exit(($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => $message_text, "status" => iconv("CP1251", "UTF-8", ""))) : $message_text);
		
	}elseif($option == "knb-check-new") {
	
		$result_text = "OK";
		
		$post_id = "";
		$message_text = false;
		
		$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `status`='1' AND `id`>'$knb_id' ORDER BY `id` ASC LIMIT 100") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		if(mysql_num_rows($sql)>0) {
			$i = 0;
			while ($row = mysql_fetch_assoc($sql)) {
				$i++;
						
				$message_text.= '<tr class="knb-tr" data-id="'.$row["id"].'" id="knb-'.$row["id"].'"><td align="center">'.$row["id"].'</td>';
					$message_text.= '<td align="center">';
						$message_text.= '<span class="protect-md5" title="Контроль невмешательства MD5" onClick="FuncKNB(\''.$row["id"].'\', \'knb-md5\', false, \'Контроль невмешательства MD5\', \'500\');"></span>';
					$message_text.= '</td>';
					$message_text.= '<td align="center">';
						$message_text.= '<a href="/wall?uid='.$row["user_id_on"].'" target="_blank" title="Перейти на стену пользователя '.$row["user_name_on"].'"" style="font-weight:bold;">'.$row["user_name_on"].'</a>';
					$message_text.= '</td>';
					$message_text.= '<td align="center">';
						$message_text.= '<b class="text-green">'.p_floor($row["sum"],2,'.','`').'</b>';
					$message_text.= '</td>';
					$message_text.= '<td align="center">';
						$message_text.= '<div title="Дата создания игры">';
							$message_text.= LastTime($row["time_on"]);
						$message_text.= '</div>';
					$message_text.= '</td>';
					$message_text.= '<td align="center"><div>';
						if ($row["user_id_on"] == $user_id) {

							$obj_1 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Камень, Key:".$row["keypass"]."");
							$obj_2 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Ножницы, Key:".$row["keypass"]."");
							$obj_3 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Бумага, Key:".$row["keypass"]."");
				
							if ($row["obj_on"] == $obj_1) {
								$knb_rock = 'act';$knb_scissors = 'nohover';$knb_paper = 'nohover';$knb_help = 'nohover';
							} else if ($row["obj_on"] == $obj_2) {
								$knb_rock = 'nohover';$knb_scissors = 'act';$knb_paper = 'nohover';$knb_help = 'nohover';
							}else if ($row["obj_on"] == $obj_3) {
								$knb_rock = 'nohover';$knb_scissors = 'nohover';$knb_paper = 'act';$knb_help = 'nohover';
							} else {
								$knb_rock = 'nohover';$knb_scissors = 'nohover';$knb_paper = 'nohover';$knb_help = 'act';
							}
									
							$message_text.= '<div class="knb-opacity">';
								$message_text.= '<span class="game-object knb-rock '.$knb_rock.'" title="Камень"></span>';
								$message_text.= '<span class="game-object knb-scissors '.$knb_scissors.'" title="Ножницы"></span>';
								$message_text.= '<span class="game-object knb-paper '.$knb_paper.'" title="Бумага"></span>';
								$message_text.= '<span class="game-object knb-help '.$knb_help.'" title="Автовыбор"></span>';
							$message_text.= '</div>';
						}else{
							$message_text.= '<span class="game-object knb-rock" title="Камень" onClick="SelObjPlay(this, \''.$row["id"].'\', \'1\');"></span>';
							$message_text.= '<span class="game-object knb-scissors" title="Ножницы" onClick="SelObjPlay(this, \''.$row["id"].'\', \'2\');"></span>';
							$message_text.= '<span class="game-object knb-paper" title="Бумага" onClick="SelObjPlay(this, \''.$row["id"].'\', \'3\');"></span>';
							$message_text.= '<span class="game-object knb-help" title="Автовыбор" onClick="SelObjPlay(this, \''.$row["id"].'\', \'4\');"></span>';
							$message_text.= '<input type="hidden" id="knb_obj_play_'.$row["id"].'" value="">';
						}
					$message_text.= '</div></td>';
					$message_text.= '<td align="center" id="knb-status-'.$row["id"].'">';
						if ($row["user_id_on"] == $user_id) {
							$token_cancelknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$row["id"]."СancelKNB".$security_key));
									
							$message_text.= '<span class="sd_sub red" style="width:70px;" onClick="FuncKNB(\''.$row["id"].'\', \'confirm-cancel\', \''.$token_cancelknb.'\', \'Подтверждение\', \'500\');">Отменить</span>';
						} else {
							$token_playknb = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"].$row["id"]."PlayKNB".$security_key));
									
							$message_text.= '<span class="sd_sub green" style="width:70px;" onClick="FuncKNB(\''.$row["id"].'\', \'game-play\', \''.$token_playknb.'\');">Играть</span>';
						}
					$message_text.= '</td>';
				$message_text.= '</tr>';
						
				$post_id = $row["id"];
			}
		}

		exit(my_json_encode($ajax_json, $result_text, $message_text, $post_id));
		
	}elseif($option == "knb-mystat") {
	
		$message_text = false;
		
		$message_text.= '<h3 class="sp" style="font-weight:bold;">Список 100 последних Ваших игр</h3>';
		$message_text.= '<table class="tables-game" id="knb-table-list">';
			$message_text.= '<thead><tr>';
				$message_text.= '<th>ID</th>';
				$message_text.= '<th style="width:30px;">MD5</th><th style="width:120px;">Соперник</th>';
				$message_text.= '<th style="width:90px;">Предмет</th><th style="width:90px;">Ваш предмет</th>';
				$message_text.= '<th style="width:60px;">Ставка</th><th style="width:120px;">Дата</th>';
				$message_text.= '<th style="width:90px;">Исход</th>';
			$message_text.= '</tr></thead>';
			$message_text.= '<tbody>';
				$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `status`='2' AND `user_id_on`='$user_id' OR `user_id_ne`='$user_id' ORDER BY `id` DESC LIMIT 100") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				if(mysql_num_rows($sql)>0) {
					$i = 0;
					while ($row = mysql_fetch_assoc($sql)) {
						
						$message_text.= '<tr>';
							$message_text.= '<td align="center">'.$row["id"].'</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<span class="protect-md5" title="Контроль невмешательства MD5" onClick="FuncKNB(\''.$row["id"].'\', \'knb-md5\', false, \'Контроль невмешательства MD5\', \'500\');"></span>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								if ($user_id == $row["user_id_ne"]){
									$message_text.= '<a href="/wall?uid='.$row["user_id_on"].'" target="_blank" title="Перейти на стену пользователя '.$row["user_name_on"].'" style="font-weight:bold;">'.$row["user_name_on"].'</a>';
								} else {
									$message_text.= '<a href="/wall?uid='.$row["user_id_ne"].'" target="_blank" title="Перейти на стену пользователя '.$row["user_name_ne"].'" style="font-weight:bold;">'.$row["user_name_ne"].'</a>';
								}
							$message_text.= '</td>';
							
							$obj_on_1 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Камень, Key:".$row["keypass"]."");
							$obj_on_2 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Ножницы, Key:".$row["keypass"]."");
							$obj_on_3 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Бумага, Key:".$row["keypass"]."");
							$obj_ne_1 = md5("ID:".$row["id"].", Login:".$row["user_name_ne"].", Date:".DATE("d.m.Y H:i:s", $row["time_ne"]).", Predmet:Камень, Key:".$row["keypass"]."");
							$obj_ne_2 = md5("ID:".$row["id"].", Login:".$row["user_name_ne"].", Date:".DATE("d.m.Y H:i:s", $row["time_ne"]).", Predmet:Ножницы, Key:".$row["keypass"]."");
							$obj_ne_3 = md5("ID:".$row["id"].", Login:".$row["user_name_ne"].", Date:".DATE("d.m.Y H:i:s", $row["time_ne"]).", Predmet:Бумага, Key:".$row["keypass"]."");
							if ($row["obj_on"] == $obj_on_1) {
								$knb_on = 'Камень';
							} else if ($row["obj_on"] == $obj_on_2) {
								$knb_on = 'Ножницы';
							}else if ($row["obj_on"] == $obj_on_3) {
								$knb_on = 'Бумага';
							} else {
								$knb_on = 'Ошибка: Базы-Данных';
							}
							if ($row["obj_ne"] == $obj_ne_1) {
								$knb_ne = 'Камень';
							} else if ($row["obj_ne"] == $obj_ne_2) {
								$knb_ne = 'Ножницы';
							}else if ($row["obj_ne"] == $obj_ne_3) {
								$knb_ne = 'Бумага';
							} else {
								$knb_ne = 'Ошибка: Базы-Данных';
							}
							
							if ($user_id == $row["user_id_ne"]){
								$on_stavka = $knb_on;
							} else {
								$on_stavka = $knb_ne;
							}
							if ($user_id == $row["user_id_on"]){
								$my_stavka = $knb_on;
							} else {
								$my_stavka = $knb_ne;
							}
							
							$message_text.= '<td align="center">'.$on_stavka.'</td>';
							$message_text.= '<td align="center">'.$my_stavka.'</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<b class="text-green">'.p_floor($row["sum"],2,'.','`').'</b>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<div title="Дата создания игры">';
									$message_text.= LastTime($row["time_on"]);
								$message_text.= '</div>';
								$message_text.= '<div title="Дата завершения игры">';
									$message_text.= LastTime($row["time_ne"]);
								$message_text.= '</div>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								if ($row["winner"] == 1){
									$message_text.= '<span class="text-grey"><b>Ничья</b></span>';
								} else if ($row["winner"] == 2 && $user_id == $row["user_id_ne"] || $row["winner"] == 3 && $user_id == $row["user_id_on"]){
									$message_text.= '<span class="text-red"><b>Поражение</b></span>';
								}else {
									$message_text.= '<span class="text-green" style="font-size:11.5px;"><b>Победа</b></span>';
								}
							$message_text.= '</td>';
						$message_text.= '</tr>';
						
					}	
				}else{
				$message_text.= '<tr id="knb-warning">';
					$message_text.= '<td colspan="8" style="text-align:center; padding:3px;"><span class="msg-w" style="margin:0 auto;">На данный момент сыграных игр нет</span></td>';
				$message_text.= '</tr>';
				}
			$message_text.= '</tbody>';
		$message_text.= '</table>';
		$message_text.= '<script>load_knb_id = false;</script>';
		
		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
	
	}elseif($option == "knb-last") {
	
		$message_text = false;
		
		$message_text.= '<h3 class="sp" style="font-weight:bold;">Список 100 последних игр</h3>';
		$message_text.= '<table class="tables-game" id="knb-table-list">';
			$message_text.= '<thead><tr>';
				$message_text.= '<th>ID</th>';
				$message_text.= '<th style="width:30px;">MD5</th><th style="width:120px;">Создал</th>';
				$message_text.= '<th style="width:70px;">Предмет</th><th style="width:120px;">Сыграл</th>';
				$message_text.= '<th style="width:70px;">Предмет</th><th style="width:60px;">Ставка</th>';
				$message_text.= '<th style="width:120px;">Дата</th>';
			$message_text.= '</tr></thead>';
			$message_text.= '<tbody>';
				$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `status`='2' ORDER BY `id` DESC LIMIT 100") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
				if(mysql_num_rows($sql)>0) {
					$i = 0;
					while ($row = mysql_fetch_assoc($sql)) {
						
						$message_text.= '<tr id="knb-'.$row["id"].'">';
							$message_text.= '<td align="center">'.$row["id"].'</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<span class="protect-md5" title="Контроль невмешательства MD5" onClick="FuncKNB(\''.$row["id"].'\', \'knb-md5\', false, \'Контроль невмешательства MD5\', \'500\');"></span>';
							$message_text.= '</td>';
							$message_text.= '<td  align="center">';
								$message_text.= '<a href="/wall?uid='.$row["user_id_on"].'" target="_blank" title="Перейти на стену пользователя '.$row["user_name_on"].'" style="font-weight:bold;">'.$row["user_name_on"].'</a>';
							$message_text.= '</td>';
							
							
							$obj_on_1 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Камень, Key:".$row["keypass"]."");
							$obj_on_2 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Ножницы, Key:".$row["keypass"]."");
							$obj_on_3 = md5("ID:".$row["id"].", Login:".$row["user_name_on"].", Date:".DATE("d.m.Y H:i:s", $row["time_on"]).", Predmet:Бумага, Key:".$row["keypass"]."");
							$obj_ne_1 = md5("ID:".$row["id"].", Login:".$row["user_name_ne"].", Date:".DATE("d.m.Y H:i:s", $row["time_ne"]).", Predmet:Камень, Key:".$row["keypass"]."");
							$obj_ne_2 = md5("ID:".$row["id"].", Login:".$row["user_name_ne"].", Date:".DATE("d.m.Y H:i:s", $row["time_ne"]).", Predmet:Ножницы, Key:".$row["keypass"]."");
							$obj_ne_3 = md5("ID:".$row["id"].", Login:".$row["user_name_ne"].", Date:".DATE("d.m.Y H:i:s", $row["time_ne"]).", Predmet:Бумага, Key:".$row["keypass"]."");
							if ($row["obj_on"] == $obj_on_1) {
								$knb_on = 'Камень';
							} else if ($row["obj_on"] == $obj_on_2) {
								$knb_on = 'Ножницы';
							}else if ($row["obj_on"] == $obj_on_3) {
								$knb_on = 'Бумага';
							} else {
								$knb_on = 'Ошибка: Базы-Данных';
							}
							if ($row["obj_ne"] == $obj_ne_1) {
								$knb_ne = 'Камень';
							} else if ($row["obj_ne"] == $obj_ne_2) {
								$knb_ne = 'Ножницы';
							}else if ($row["obj_ne"] == $obj_ne_3) {
								$knb_ne = 'Бумага';
							} else {
								$knb_ne = 'Ошибка: Базы-Данных';
							}
							
							$position_game_1 = false;
							$position_game_2 = false;
							if ($row["winner"] == 2) {
								$position_game_1 = 'class="knb-winner"';
							} else if ($row["winner"] == 3) {
								$position_game_2 = 'class="knb-winner"';
							}
							
							$message_text.= '<td '.$position_game_1.' align="center">'.$knb_on.'</td>';
							$message_text.= '<td  align="center">';
								$message_text.= '<a href="/wall?uid='.$row["user_id_ne"].'" target="_blank" title="Перейти на стену пользователя '.$row["user_name_ne"].'" style="font-weight:bold;">'.$row["user_name_ne"].'</a>';
							$message_text.= '</td>';
							$message_text.= '<td '.$position_game_2.' align="center">'.$knb_ne.'</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<b class="text-green">'.p_floor($row["sum"],2,'.','`').'</b>';
							$message_text.= '</td>';
							$message_text.= '<td align="center">';
								$message_text.= '<div title="Дата создания игры">';
									$message_text.= LastTime($row["time_on"]);
								$message_text.= '</div>';
								$message_text.= '<div title="Дата завершения игры">';
									$message_text.= LastTime($row["time_ne"]);
								$message_text.= '</div>';
							$message_text.= '</td>';
						$message_text.= '</tr>';
						
					}	
				}else{
				$message_text.= '<tr id="knb-warning">';
					$message_text.= '<td colspan="8" style="text-align:center; padding:3px;"><span class="msg-w" style="margin:0 auto;">На данный момент сыграных игр нет</span></td>';
				$message_text.= '</tr>';
				}
			$message_text.= '</tbody>';
		$message_text.= '</table>';
		$message_text.= '<script>load_knb_id = false;</script>';
		
		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
	
	}elseif($option == "knb-stat") {

		$sql = mysql_query("SELECT * FROM `tb_game_knb_stats` WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		$infostat = mysql_fetch_assoc($sql);
		
		$message_text = false;
		
		$message_text.= '<h3 class="sp" style="font-weight:bold;">Статистика игр за все время</h3>';
		$message_text.= '<table class="tables-stat"><tbody>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left"><b>Всего сыграно игр<b></td>';
				$message_text.= '<td align="right"><span class="text-blue"><b>'.$infostat["number"].'</b> шт.</span></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left"><b>Побед<b></td>';
				$message_text.= '<td align="right"><span class="text-green"><b>'.$infostat["victories"].'</b> шт.</span></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left"><b>Поражений<b></td>';
				$message_text.= '<td align="right"><span class="text-red"><b>'.$infostat["defeats"].'</b> шт.</span></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left"><b>Ничьих<b></td>';
				$message_text.= '<td align="right"><span class="text-grey"><b>'.$infostat["draws"].'</b> шт.</span></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left"><b>Сумма выиграшей<b></td>';
				$message_text.= '<td align="right"><span class="text-red"><b style="font-size:15px;">'.p_floor($infostat["sum_victories"],4,'.','`').'</b> руб.</span></td>';
			$message_text.= '</tr>';
		$message_text.= '</tbody></table>';
		
		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
	
	}elseif($option == "knb-rules") {
	
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_comis'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		$game_knb_comis = number_format(mysql_result($sql,0,0), 0, ".", "");
		
		$game_knb_com = 100 - $game_knb_comis;
		$summ = rand(10,20);
		$sums = $summ + $summ;
		$sum_com_site = $sums / 100 * $game_knb_comis;
		$sum_win = $sums - $sum_com_site;
		$task_knb = '<b>'.$summ.'</b>+<b>'.$summ.'</b>=<b>'.$sums.'</b> руб. (100%). Размер выигрыша: <b>'.$sum_win.'</b> руб. ('.$game_knb_com.'%), где <b>'.$game_knb_comis.'%</b> это комиссия сайта, что составляет <b>'.$sum_com_site.'</b> руб.';
		
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_victory'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		$game_knb_victory = number_format(mysql_result($sql,0,0), 0, ".", "");
			
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='game_knb_reit'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		$game_knb_reit = number_format(mysql_result($sql,0,0), 0, ".", "");

		$message_text = false;

			$message_text.= '<div class="knb-rules">';
				$message_text.= '<div class="knb-rules-top">';
					$message_text.= '<b>Описание:</b>';
				$message_text.= '</div>';
				$message_text.= '<div class="knb-rules-line">Вам доступно 3 предмета: <b>камень, ножницы и бумага.</b></div>';
				$message_text.= '<div class="knb-rules-line">Победитель определяется по следующим правилам:</div>';
				$message_text.= '<div class="knb-rules-line">&nbsp; - Камень побеждает ножницы («камень затупляет или ломает ножницы»)</div>';
				$message_text.= '<div class="knb-rules-line">&nbsp; - Ножницы побеждают бумагу («ножницы разрезают бумагу»)</div>';
				$message_text.= '<div class="knb-rules-line">&nbsp; - Бумага побеждает камень («бумага заворачивает камень»)</div>';
				$message_text.= '<div class="knb-rules-line">Если игроки показали одинаковый знак, то засчитывается ничья и ставки возвращаются игрокам.</div>';
				$message_text.= '<div class="knb-rules-line">Комиссия системы составляет <b>'.$game_knb_comis.'%</b>, в случае выиграша.</div>';
				$message_text.= '<div class="knb-rules-line">Например, создана игра со ставкой в <b>'.$summ.'</b> руб.</div>';
				$message_text.= '<div class="knb-rules-line">Банк игры: '.$task_knb.'</div>';
				if($game_knb_reit > 0) {
					$message_text.= '<div class="knb-rules-line">За каждую игру начисляется рейтинг (только участнику создавшему ставку): <img src="img/reiting.png" border="0" alt="" title="Рейтинг" align="absmiddle" style="margin:0; padding:0;">&nbsp;<span style="color:green; font-weight:normal; font-size:16px;">+'.$game_knb_reit.'</span>.</div>';
				}
				if($game_knb_victory > 0) {
					$message_text.= '<div class="knb-rules-line">За каждую победу начисляется рейтинг: <img src="img/reiting.png" border="0" alt="" title="Рейтинг" align="absmiddle" style="margin:0; padding:0;">&nbsp;<span style="color:green; font-weight:normal; font-size:16px;">+'.$game_knb_victory.'</span>.</div>';
				}
				$message_text.= '<div class="knb-rules-top"><b>Правила:</b></div>';
				$message_text.= '<div class="knb-rules-line"><b>1.</b> Оплата ставок в игре производится с <i>рекламного счёта</i>.</div>';
				$message_text.= '<div class="knb-rules-line"><b>2.</b> Сумма выигрыша зачисляется на <i>основной счёт</i>, и доступна для вывода в любое время.</div>';
				$message_text.= '<div class="knb-rules-line"><b>3.</b> Во всех играх КНБ есть MD5 контроль невмешательства.</div>';
				$message_text.= '<div class="knb-rules-line"><b>4.</b> Если у Вас есть сомнения или Вы не поняли правил игры, то откажитесь от участия в игре.</div>';
				$message_text.= '<div class="knb-rules-line"><b>5.</b> Администрация не несёт ни какой ответственности при потери Вами средств и времени, участие в игре - это Ваше личное желание. Если Вы в чем-то не уверены, то просто не играйте.</div>';
				$message_text.= '<div class="knb-rules-top"><b>MD5 контроль невмешательства:</b></div>';
				$message_text.= '<div class="knb-rules-line"><a href="http://ru.wikipedia.org/wiki/Md5" target="_blank">MD5</a> - 128-битный алгоритм хеширования, разработанный профессором Рональдом Л. Ривестом из Массачусетского технологического института (Massachusetts Institute of Technology, MIT) в 1991 году. Предназначен для создания «отпечатков» или дайджестов сообщения произвольной длины и последующей проверки их подлинности.</div>';
				$message_text.= '<div class="knb-rules-line">Когда первый игрок создают свою игру, система автоматически генерирует 16-значный ключ. При отображении списка игр, Вы можете отобразить хеш MD5 из этого ключа и значений игрока. После того, как Вы сыграли в выбранную игру, ключ становится доступен, и Вы сможете проверить результат игры. Для этого переходим в раздел "Моя статистика игр", нажимаем на значок MD5, в выпадающем окне копируем MD5 Текст. Переходим в любой сервис, где возможно преобразовать текст в хеш MD5. После чего, сравниваем результаты.</div>';
			$message_text.= '</div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
	
	}elseif($option == "knb-md5") {
	
		$message_text = false;
		
		$sql = mysql_query("SELECT * FROM `tb_game_knb_play` WHERE `id`='".$knb_id."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error(), ""));
		if(mysql_num_rows($sql)>0) {
		
			while ($row = mysql_fetch_assoc($sql)) {
		
				$message_text.= '<div id="newform" style="margin:5px 15px; line-height:20px;">';
					$message_text.= '<div><b>ID игры:</b> '.$row["id"].'</b></div>';
					$message_text.= '<div style="padding-top:5px;"><b>MD5 Hash:</b> '.$row["obj_on"].'</div>';
					if ($row["status"] == 2) {
					$message_text.= '<div style="padding-top:10px;"><b>MD5 Текст:</b><br>';
						$obj_1 = md5("ID:".$row['id'].", Login:".$row['user_name_on'].", Date:".DATE("d.m.Y H:i:s", $row['time_on']).", Predmet:Камень, Key:".$row['keypass']."");
						$obj_2 = md5("ID:".$row['id'].", Login:".$row['user_name_on'].", Date:".DATE("d.m.Y H:i:s", $row['time_on']).", Predmet:Ножницы, Key:".$row['keypass']."");
						$obj_3 = md5("ID:".$row['id'].", Login:".$row['user_name_on'].", Date:".DATE("d.m.Y H:i:s", $row['time_on']).", Predmet:Бумага, Key:".$row['keypass']."");
						if ($row['obj_on'] == $obj_1) {
							$obj = "ID:".$row['id'].", Login:".$row['user_name_on'].", Date:".DATE("d.m.Y H:i:s", $row['time_on']).", Predmet:Камень, Key:".$row['keypass']."";
						}else if ($row['obj_on'] == $obj_2) {
							$obj = "ID:".$row['id'].", Login:".$row['user_name_on'].", Date:".DATE("d.m.Y H:i:s", $row['time_on']).", Predmet:Ножницы, Key:".$row['keypass']."";
						}else if ($row['obj_on'] == $obj_3) {
							$obj = "ID:".$row['id'].", Login:".$row['user_name_on'].", Date:".DATE("d.m.Y H:i:s", $row['time_on']).", Predmet:Бумага, Key:".$row['keypass']."";
						} 
						$message_text.= '<textarea onClick="this.select()" readonly="readonly" class="ok" style="height:auto; padding:5px 10px;">'.$obj.'</textarea>';
					$message_text.= '</div>';
					}
				$message_text.= '</div>';
		
			}
			
			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
		
		}else exit(my_json_encode($ajax_json, "ERROR", "Не возможно загруть HASH параметры !", ""));
	
	}else{
		$result_text = "ERROR"; $message_text = "ERROR: NO OPTION"; $status_text = "";
		exit(my_json_encode($ajax_json, $result_text, $message_text, $status_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text, ""));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text, ""));

?>