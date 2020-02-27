<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
//sleep(1);

$_ARR_EPS = array('wm', 'ym', 'pm', 'pe', 'qw', 'ac', "me", "vs", "ms", "be", "mt", "mg", "tl");
$_ARR_EPS_TXT = array(
	'wm' => '<img src="/img/wm16x16.png" 		alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#006699;">WebMoney</span>', 
	'ym' => '<img src="/img/yandexmoney16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#DE1200;">Я</span>ндекс.Деньги', 
	'pm' => '<img src="/img/perfectmoney16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#DE1200;">PerfectMoney</span>', 
	'pe' => '<img src="/img/payeer16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span>', 
	'qw' => '<img src="/img/qiwi16x16.png" 		alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#FC8000;">QIWI кошелек</span>', 
	'ac' => '<img src="/img/advcash_18x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span>',
	'me' => '<img src="/img/me16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span>',
	'vs' => '<img src="/img/vs16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#1c0cf5">VISA</span>',
	'ms' => '<img src="/img/ms16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span>',
	'be' => '<img src="/img/be16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#0f1010">Би</span><span style="color:#e0b806">ла</span><span style="color:#0f1010">йн</span>',
	'mt' => '<img src="/img/mt16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#ff0000; font-weight:bold;">МТС</span>',
	'mg' => '<img src="/img/mg16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#049C64">Мега</span><span style="color:#00649e">фон</span>',
	'tl' => '<img src="/img/tl16x16.png" 	alt="" title="" border="0" align="absmiddle" style="margin:2px 6px 2px 0px; padding:0px;" /><span style="color:#0c0c0c">TELE2</span>'
);
$_ARR_EPS_TAB = array('wm'=>'WebMoney', 'ym'=>'YandexMoney', 'pm'=>'PerfectMoney', 'pe'=>'Payeer', 'qw'=>'Qiwi', 'ac'=>'AdvCash', 'me'=>'MAESTRO', 'vs'=>'VISA', 'ms'=>'MasterCard', 'be'=>'Beeline', 'mt'=>'MTS', 'mg'=>'Megaphone', 'tl'=>'TELE2');

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

function count_text($count, $text1, $text2, $text3, $text)
{
	if (0 <= $count) {
		if ((10 <= $count) && ($count <= 20)) {
			return $count . ' ' . $text1;
		}


		switch (substr($count, -1, 1)) {
		case 1:
			return $count . ' ' . $text2;
		case 2:

		case 3:

		case 4:
			return $count . ' ' . $text3;
		case 5:

		case 6:

		case 7:

		case 8:

		case 9:

		case 0:
			return $count . ' ' . $text1;
		}

		return;
	}


	return $text;
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
	$message_text = '<span class="msg-error">'.$message_text.'</span>';
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

function RESULT_PAY($ajax_json, $pay_status, $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse, $_ARR_EPS_TAB) {
	$message_text = false;

	if($code_result == "SUCCESS") {
		if($pay_status == 1) {
			mysql_query("UPDATE `tb_users` SET `money`=`money`-'$sum_amount', `money_out`=`money_out`+'$sum_amount' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`) 
			VALUES('1','$tranid','$user_name','$user_id','$user_purse','".DATE("d.m.Y H:i", time())."','".time()."','$sum_amount','$_ARR_EPS_TAB')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			
			$sql_stat_pay = mysql_query("SELECT * FROM `tb_pay_stat` WHERE `type`='$_ARR_EPS_TAB'");
	               if(mysql_num_rows($sql_stat_pay)>0) {
		               mysql_query("UPDATE `tb_pay_stat` SET `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$sum_amount' WHERE `type`='$_ARR_EPS_TAB'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_pay_stat` (`type`,`".strtolower(DATE("D"))."`) VALUES('$_ARR_EPS_TAB','$sum_amount')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }
				 
			$sql_history_pay = mysql_query("SELECT * FROM `tb_history_pay` WHERE `user`='$user_name'");
	               if(mysql_num_rows($sql_history_pay)>0) {
		               mysql_query("UPDATE `tb_history_pay` SET `time`='".time()."' WHERE `user`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_history_pay` (`user`,`time`) VALUES('$user_name','".time()."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

			stat_pay("money_out", $sum_amount);

			$result_text = "OK";
			$message_text.= '<div class="cash-success">';
				$message_text.= '<span>Выплата <b>'.$sum_amount.'</b> руб. на '.($eps=="me" | $eps=="vs" | $eps=="ms" ? "Вашу" : "Ваш").' '.($eps=="me" | $eps=="vs" | $eps=="ms" ? "карту" : ($eps=="be" | $eps=="mt" | $eps=="mg" | $eps=="tl" ? "телефон" : "кошелек")).' '.($eps=="be" | $eps=="mt" | $eps=="mg" | $eps=="tl" | $eps=="qw" ? "+$user_purse" : $user_purse).' успешно произведена!<br>Благодарим Вас за работу</span>';
				$message_text.= '<div class="bg-img"></div>';
				$message_text.= '<div style="font-weight:bold;">Ваше мнение имеет для нас большое значение.<br>Пожалуйста, оставьте свой отзыв о нашем проекте.</div>';
				$message_text .= "</div>";
				$message_text .= "<a href=\"https://advisor.wmtransfer.com/newfeedback.aspx?url=" . $_SERVER["HTTP_HOST"] . "\" target=\"_blank\" style=\"text-transform:none; font-size:16px;\"><div class=\"advisor\"><center><img src=\"https://lilacbux.com/img/webmoney.png\"></center></div></a>";
            $message_text .= "<a href=\"https://www.mywot.com/ru/scorecard/" . $_SERVER["HTTP_HOST"] . "\" target=\"_blank\" style=\"text-transform:none; font-size:16px;\"><div class=\"advisor\"><center><img src=\"https://lilacbux.com/img/wot.png\"></center></div></a>";

		}elseif($pay_status == 2) {
			mysql_query("UPDATE `tb_users` SET `money`=`money`-'$sum_amount' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`) 
			VALUES('0','$tranid','$user_name','$user_id','$user_purse','".DATE("d.m.Y H:i", time())."','".time()."','$sum_amount','$_ARR_EPS_TAB')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			
			$sql_history_pay = mysql_query("SELECT * FROM `tb_history_pay` WHERE `user`='$user_name'");
	               if(mysql_num_rows($sql_history_pay)>0) {
		               mysql_query("UPDATE `tb_history_pay` SET `time`='".time()."' WHERE `user`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_history_pay` (`user`,`time`) VALUES('$user_name','".time()."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

			$result_text = "OK";
			$message_text.= '<div class="cash-success">';
				$message_text.= '<span>Ваш заказ принят!<br>По мере поступления средств заказанная сумма <b>'.$sum_amount.'</b> руб. будет выплачена на '.($eps=="me" | $eps=="vs" | $eps=="ms" ? "Вашу" : "Ваш").' '.($eps=="me" | $eps=="vs" | $eps=="ms" ? "карту" : ($eps=="be" | $eps=="mt" | $eps=="mg" | $eps=="tl" ? "телефон" : "кошелек")).' '.($eps=="be" | $eps=="mt" | $eps=="mg" | $eps=="tl" | $eps=="qw" ? "+$user_purse" : $user_purse).'</span>';
				$message_text.= '<div style="font-weight:bold;">Ваше мнение имеет для нас большое значение.<br>Пожалуйста, оставьте свой отзыв о нашем проекте.</div>';
				//$message_text.= '<div style="margin-top:20px;"><a href="https://advisor.wmtransfer.com/newfeedback.aspx?url='.$_SERVER["HTTP_HOST"].'" target="_blank" style="text-transform:none; font-size:16px;">Оставить отзыв в WM Advisor</a></div>';
			$message_text.= '</div>';
			$message_text .= "<a href=\"https://advisor.wmtransfer.com/newfeedback.aspx?url=" . $_SERVER["HTTP_HOST"] . "\" target=\"_blank\" style=\"text-transform:none; font-size:16px;\"><div class=\"advisor\"><center><img src=\"https://lilacbux.com/img/webmoney.png\"></center></div></a>";
            $message_text .= "<a href=\"https://www.mywot.com/ru/scorecard/" . $_SERVER["HTTP_HOST"] . "\" target=\"_blank\" style=\"text-transform:none; font-size:16px;\"><div class=\"advisor\"><center><img src=\"https://lilacbux.com/img/wot.png\"></center></div></a>";
		}

		mysql_query("UPDATE `tb_statistics` SET `viplat`=`viplat`+'1', `sumpay`=`sumpay`+'$sum_amount' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	}else{
		$result_text = "ERROR";

		if($eps=="wm" && $code_result=="35") {
			$result_text = "ERROR35";

			$message_text.= '<span class="msg-error" style="text-align:left; text-shadow: 0 1px 1px rgba(0,0,0,0.7);">';
				$message_text.= 'Ошибка выплаты, код ошибки[35].<br>';
				$message_text.= 'Ваши действия:<br>';
				$message_text.= '1. Убедитесь что в вашем кипере разрешен приейм средств от неавторизованных корреспондентов.<br>';
				$message_text.= '2. Убедитесь что в вашем кипере не установлен запрет на прием средств от нашего WMID.<br>';
				$message_text.= '3. Проверьте статус обслуживания вашего кошелька в системе WebMoney.<br>';
				$message_text.= '4. Проверьте соответствует ли указанный кошелек в профиле Вашему кошельку.<br>';
				$message_text.= '5. Если все правильно? повторите попытку через некоторое время.<br>';
				$message_text.= '6. Если ошибки повторяются многократно, свяжитесь с администрацией.<br>';
			$message_text.= '</span>';

		}elseif($eps=="wm" && $code_result=="17") {
			$message_text.= '<span class="msg-error">Ошибка: недостаточно средств в кошельке для выплаты!<br>Попробуйте повторить запрос немного позже.</span>';

		}elseif($eps=="wm" && $code_result=="26") {
			$message_text.= '<span class="msg-error">Ошибка: в операции должны участвовать разные кошельки.</span>';
		}else{
			$message_text.= '<span class="msg-error">Ошибка выплаты! ['.$code_result.']</span>';
		}
	}

	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require_once(ROOT_DIR."/config.php");
		require_once(ROOT_DIR."/funciones.php");
		require_once(ROOT_DIR."/merchant/func_mysql.php");

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlentities(stripslashes(trim($_SESSION["userPas"]))) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
		$eps = ( isset($_POST["eps"]) && preg_match("|^[a-zA-Z0-9\-_]{1,10}$|", htmlspecialchars(trim($_POST["eps"]))) ) ? htmlspecialchars(trim($_POST["eps"])) : false;
		$sum_amount = ( isset($_POST["sum_amount"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["sum_amount"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["sum_amount"]))) : false;
		$cnt_post = ( isset($_POST["cnt_amount"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_POST["cnt_amount"])) ) ? htmlspecialchars(trim($_POST["cnt_amount"])) : false;
		$cnt_sess = ( isset($_SESSION["cnt_amount"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_SESSION["cnt_amount"])) ) ? htmlspecialchars(trim($_SESSION["cnt_amount"])) : false;
		$now_agent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : false;
		$my_lastiplog = getRealIP();

		$message_text = false;

		$sql_user = mysql_query("SELECT `id`,`username`,`reiting`,`money`,`agent`,`wm_purse`,`ym_purse`,`pm_purse`,`py_purse`,`qw_purse`,`sb_purse`,`ac_purse`,`me_purse`,`vs_purse`,`ms_purse`,`fio`,`be_purse`,`mt_purse`,`mg_purse`,`tl_purse` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_reit = $row_user['reiting'];
			$user_agent = $row_user["agent"];
			$user_money = $row_user["money"];
			$fio = $row_user["fio"];
			$user_purse["wm"] = $row_user["wm_purse"];
			$user_purse["ym"] = $row_user["ym_purse"];
			$user_purse["pm"] = $row_user["pm_purse"];
			$user_purse["pe"] = $row_user["py_purse"];
			$user_purse["qw"] = $row_user["qw_purse"];
			$user_purse["sb"] = $row_user["sb_purse"];
			$user_purse["ac"] = $row_user["ac_purse"];
			$user_purse["me"] = $row_user["me_purse"];
			$user_purse["vs"] = $row_user["vs_purse"];
			$user_purse["ms"] = $row_user["ms_purse"];
			$user_purse["be"] = $row_user["be_purse"];
			$user_purse["mt"] = $row_user["mt_purse"];
			$user_purse["mg"] = $row_user["mg_purse"];
			$user_purse["tl"] = $row_user["tl_purse"];
			
			$sql_rang = mysql_query('SELECT `max_pay` FROM `tb_config_rang` WHERE `r_ot`<=\'' . $user_reit . '\' AND `r_do`>=\'' . floor($user_reit) . '\'') or die(my_json_encode($ajax_json, 'ERROR', mysql_error()));

			if (mysql_num_rows($sql_rang)>0) {
				$row_rang = mysql_fetch_assoc($sql_rang);
			}else{
				$sql_rang = mysql_query('SELECT `max_pay` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1') or die(my_json_encode($ajax_json, 'ERROR', mysql_error()));
				$row_rang = mysql_fetch_assoc($sql_rang);
			}

			$user_max_pay = $row_rang['max_pay'];
			
			$sql_ran = mysql_query('SELECT `pay_min_click` FROM `tb_config_rang` WHERE `r_ot`<=\'' . $user_reit . '\' AND `r_do`>=\'' . floor($user_reit) . '\'') or die(my_json_encode($ajax_json, 'ERROR', mysql_error()));

			if (mysql_num_rows($sql_ran)>0) {
				$row_ran = mysql_fetch_assoc($sql_ran);
			}else{
				$sql_ran = mysql_query('SELECT `pay_min_click` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1') or die(my_json_encode($ajax_json, 'ERROR', mysql_error()));
				$row_ran = mysql_fetch_assoc($sql_ran);
			}
			
			$user_click_reit = $row_ran['pay_min_click'];
			
			$sql_stat_us = mysql_query('SELECT `' . strtolower(DATE('D')) . '` FROM `tb_users_stat_pay` WHERE `username`=\'' . $user_name . '\' AND `type`=\'serf_pay\'');
			if (mysql_num_rows($sql_stat_us)>0) {
					$row_stat_us = mysql_fetch_assoc($sql_stat_us);
					$user_click_day = $row_stat_us[strtolower(DATE('D'))];
				}else{
					$user_click_day = 0;
				}

			if($option == "order") {
				$cnt_unow = strtolower(md5(strtolower($user_name.session_id().$my_lastiplog.$user_agent.$_SERVER["HTTP_HOST"]."SP48915022")));
			}elseif($option == "convert") {
				$cnt_unow = strtolower(md5(strtolower($user_name.session_id().$my_lastiplog.$user_agent.$_SERVER["HTTP_HOST"].$eps.$sum_amount."SP48915022")));
			}else{
				$cnt_unow = false;
			}
		}else{
			if(isset($_SESSION)) session_destroy();
			$cnt_unow = false;

			$result_text = "ERROR"; $message_text = '<span class="msg-error">Пользователь не идентифицирован!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


		if($cnt_post==false | $cnt_sess==false | $cnt_unow==false | ($cnt_post!=$cnt_sess | $cnt_post!=$cnt_unow | $cnt_sess!=$cnt_unow)) {
			if(isset($_SESSION["cnt_amount"])) unset($_SESSION["cnt_amount"]);

			$result_text = "ERROR"; $message_text = '<span class="msg-error">Не удалось выполнить операцию.</span>';
			$result_text = "ERROR"; $message_text = '<span class="msg-error">CNT_P: '.$cnt_post.'<br>CNT_S: '.$cnt_sess.'<br>CNT_N: '.$cnt_unow.'</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			if(isset($_SESSION["cnt_amount"]) && $option == "convert") unset($_SESSION["cnt_amount"]);
		}

		//$sql_h1 = mysql_query("SELECT `time` FROM `tb_history_pay` WHERE `time`>='".(time()-24*60*60)."' AND `user`='$user_name' ORDER BY `id` DESC LIMIT 1");
		$sql_h1 = mysql_query("SELECT `time` FROM `tb_history` WHERE `status_pay`='1' AND `time`>='".(time()-24*60*60)."' AND `user`='$user_name' AND `status`='' AND `tipo`='0' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$sql_h2 = mysql_query("SELECT `time` FROM `tb_history` WHERE `status_pay`='0' AND `user`='$user_name' AND `status`='' AND `tipo`='0' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$sql_baned = mysql_query("SELECT `why`,`date` FROM `tb_black_users` WHERE `name`='$user_name' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$sql_bonus = mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `username`='$user_name' AND `id` IN (SELECT `ident` FROM `tb_refbonus_stat` WHERE `status`='0')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		if(mysql_num_rows($sql_baned) > 0) {
			$row_b = mysql_fetch_assoc($sql_baned);
			$result_text = "ERROR"; $message_text = '<span class="msg-error">Ваш аккаунт заблокирован!<br>Вы не можете вывести заработаные средства!<br>Причина блокировки: '.$row_b["why"].'<br>Дата блокировки: '.$row_b["date"].'</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif(mysql_num_rows($sql_h1) > 0) {
			$row_h1 = mysql_fetch_assoc($sql_h1);
			$result_text = "ERROR"; $message_text = '<span class="msg-w">Вывод денежных средств со счета нашего проекта можно осуществлять один раз в сутки.<br>Последний раз вы выводили средства '.DATE("d.m.Y в H:i", $row_h1["time"]).'<br>Следующий вывод средств возможно заказать не ранее '.DATE("d.m.Y в H:i", ($row_h1["time"]+24*60*60)).'</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif(mysql_num_rows($sql_h2) > 0) {
			$result_text = "ERROR"; $message_text = '<span class="msg-w">Ваш предыдущий заказ на вывод средств еще не обработан!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($sum_amount <= 0 | $sum_amount == false) {
			$result_text = "ERROR"; $message_text = '<span class="msg-error">Вы не указали сумму для вывода средств!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($sum_amount > $user_money) {
			$result_text = "ERROR"; $message_text = '<span class="msg-error">Вы указали сумму больше, чем у Вас есть на балансе!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		
		}else if ($user_max_pay < $sum_amount) {
			$result_text = 'ERROR'; $message_text = '<span class="msg-error">Максимальная сумма выплаты для вашего статуса '.$user_max_pay.' руб в сутки!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			
		}else if ($user_click_day < $user_click_reit) {
			$result_text = 'ERROR'; $message_text = '<span class="msg-error">Для вашего статуса минимальное количество кликов в серфинге '.count_text($user_click_reit, "кликов", "клик", "клика", '').' в день!<br>За сегодня вы сделали '.count_text($user_click_day, "кликов", "клик", "клика", '').'</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif(array_search($eps, $_ARR_EPS) === false) {
			$result_text = "ERROR"; $message_text = '<span class="msg-error">Не удалось определить платежную систему!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif(mysql_num_rows($sql_bonus)>0) {
			$result_text = "ERROR"; $message_text = '<span class="msg-w">У вас есть не погашенные бонусы рефералам, для получения выплаты погасите задолженность перед вашими рефералами.<br><a href="/ref_bonus.php">Перейти к оплате бонусов</a></span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

		if($option == "order" | $option == "convert") {
			
			if($user_purse[$eps] != false) {    
                    $eps_fuck = $eps == 'pe' ? 'py' : $eps;
                    
                        $sql_user_check = mysql_query("SELECT `id`, `username` FROM `tb_users` WHERE `username`!='".$user_name."' AND ".$eps_fuck."_purse='".$user_purse[$eps]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                        
                        if(mysql_num_rows($sql_user_check)>0) {
                          $arr_ban_name = '';  
                            
                          while ($row = mysql_fetch_array($sql_user_check)) {
                            mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`,`who_block`) 
				VALUES('".$row['username']."','Мультиаккаунт повторная регистрация (".$user_purse[$eps]." ".$user_name.")','', '".DATE("d.m.Y H:i")."','".time()."','system')") or die(mysql_error());

				mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='".$row['username']."'") or die(mysql_error());
			
                            $arr_ban_name = $arr_ban_name.$row['username'].' ';    
                          } 
                          
                           mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`,`who_block`) 
				VALUES('".$user_name."','Мультиаккаунт повторная регистрация (".$user_purse[$eps]." ".$arr_ban_name.")','', '".DATE("d.m.Y H:i")."','".time()."','system')") or die(mysql_error());

				mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='".$user_name."'") or die(mysql_error());
				
                        }
                        
                    }
					
			$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_status' AND `eps`='$eps'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$pay_status[$eps] = number_format(mysql_result($sql,0,0), 0, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_comis' AND `eps`='$eps'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$pay_comis[$eps] = number_format(mysql_result($sql,0,0), 2, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_min' AND `eps`='$eps'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$pay_min[$eps] = number_format(mysql_result($sql,0,0), 2, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_max' AND `eps`='$eps'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$pay_max[$eps] = number_format(mysql_result($sql,0,0), 2, ".", "");

			if($pay_status[$eps] == 0) {
				$result_text = "ERROR"; $message_text = '<span class="msg-error">Вывод средств по выбранному направлению временно не доступен!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_purse[$eps] == false) {
				$result_text = "PROFILE"; $message_text = '<span class="msg-error">Необходимо указать кошелек!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($pay_min[$eps] > $sum_amount) {
				$result_text = "ERROR"; $message_text = '<span class="msg-error">Минимальная сумма для выплаты по выбранному направлению '.$pay_min[$eps].' руб.</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($pay_max[$eps] < $sum_amount) {
				$result_text = "ERROR"; $message_text = '<span class="msg-error">Максимальная сумма для выплаты по выбранному направлению '.$pay_max[$eps].' руб.</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				if($pay_comis[$eps] > 0) {
					$sum_percent = round(($sum_amount * $pay_comis[$eps]/100), 2);
					if($sum_percent < 0.01) $sum_percent = 0.01;
				}else{ 
					$sum_percent = 0;
				}
				$sum_amount_to = p_floor(($sum_amount - $sum_percent), 2);

				if($eps=="pm") {
					require_once(ROOT_DIR."/curs/curs.php");
					$sum_amount_pm = p_floor(($sum_amount/$CURS_USD), 2);

					if($pay_comis[$eps] > 0) {
						$sum_percent_pm = round(($sum_amount_pm * $pay_comis[$eps]/100), 2);
						if($sum_percent_pm < 0.01) $sum_percent_pm = 0.01;
					}else{ 
						$sum_percent_pm = 0;
					}
					$sum_amount_pm_to = p_floor(($sum_amount_pm - $sum_percent_pm), 2);
				}

				$cnt_amount = strtolower(md5(strtolower($user_name.session_id().$my_lastiplog.$user_agent.$_SERVER["HTTP_HOST"].$eps.$sum_amount."SP48915022")));
				$_SESSION["cnt_amount"] = $cnt_amount;

				if($option == "order") {
					$message_text.= '<h2 class="sp" style="margin-top:0px; color:#ab0606; font-weight:bold; text-shadow: 0 1px 0 #FFFFFF, 1px 2px 2px #AAAAAA;">Подтвердите информацию о переводе</h2>';
					$message_text.= '<span class="cash-order">';
						$message_text.= '<span class="txt-l">Вывести:</span><span class="txt-r"><span style="font-size:22px;">'.$sum_amount.'</span><span style="font-size:14px; padding-left:5px;">руб</span></span>';
					$message_text.= '</span>';
					$message_text.= '<span class="cash-order">';
						$message_text.= '<span class="txt-l">Получите:</span><span class="txt-r">'.($eps=="pm" ? $sum_amount_pm_to : $sum_amount_to).'<span style="font-size:14px; padding-left:5px;">'.($eps=="pm" ? "$" : "руб").'</span></span>';
					$message_text.= '</span>';
					
					$message_text.= '<span class="cash-order">';
						$message_text.= '<span class="txt-l">На счет:</span><span class="txt-r">'.$user_purse[$eps].'</span>';
					$message_text.= '</span>';
					$message_text.= '<span class="cash-order" style="margin-bottom:10px;">';
						$message_text.= '<span class="txt-l">В системе:</span><span class="txt-r">'.$_ARR_EPS_TXT[$eps].'</span>';
					$message_text.= '</span>';
					$message_text.= '<div id="info-msg-cash" style="display: block; margin: 0px 0px 10px; text-align: center;">';
						$message_text.= '<input type="hidden" id="sum_amount" value="'.$sum_amount.'">';
						$message_text.= '<input type="hidden" id="sum_amountp" value="'.$sum_amount_to.'">';
						$message_text.= '<input type="hidden" id="cnt_amount" value="'.$cnt_amount.'">';
						$message_text.= '<span class="cash-order-btn" onClick="CashOut(\'convert\', \''.$eps.'\');">Выплатить</span>';
					$message_text.= '</div>';

					$result_text = "OK"; 
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif($option == "convert") {
					$site_wmr = mysql_result(mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'"),0,0) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$tranid   = mysql_result(mysql_query("SELECT `tranid` FROM `tb_statistics` WHERE `id`='1'"),0,0) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("UPDATE `tb_statistics` SET `tranid`=`tranid`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					if($pay_status[$eps] == 1) {
						if($eps == "wm") {
							require_once(ROOT_DIR."/auto_pay_req/wmxml.inc.php");

							$period = "0"; $pcode = ""; $wminvid = "0"; $onlyauth = "1";
							$desc = "Выплата с $url #$tranid пользователю $user_name. Благодарим Вас за работу!";
							$WM_RES = _WMXML2(time(), $site_wmr, $user_purse[$eps], $sum_amount_to, $period, $pcode, $desc, $wminvid, $onlyauth);
							$code_result = (isset($WM_RES["retval"]) && trim($WM_RES["retval"])==0) ? "SUCCESS" : (isset($WM_RES["retval"]) ? trim($WM_RES["retval"]) : "ERROR WM");

							RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
      
						}elseif($eps == "ym") {
							require_once(ROOT_DIR."/merchant/yandexmoney/ym_config.php");
							require_once(ROOT_DIR."/merchant/yandexmoney/ym_outresult.php");

							$comment = iconv("Windows-1251", "UTF-8", "Выплата с $url #$tranid пользователю $user_name. Благодарим Вас за работу!");
							$YM_API = new ymAPI(CLIENT_ID, REDIRECT_URL);
							$YM_SEND = $YM_API->requestPaymentP2P(TOKEN_YM, $user_purse[$eps], $sum_amount_to, $comment, $comment);
							$YM_RES = isset($YM_SEND["request_id"]) ? $YM_API->processPayment(TOKEN_YM, $YM_SEND["request_id"]) : array("status" => "ERROR_YM_1");
							$code_result = (isset($YM_RES["status"]) && strtolower($YM_RES["status"]) == "success") ? "SUCCESS" : (isset($YM_RES["status"]) ? $YM_RES["status"] : "ERROR_YM_2");

							RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);

						}elseif($eps == "pm") {
							require_once(ROOT_DIR."/merchant/perfectmoney/pm_config.php");
							$OPEN_URL_PM = fopen('https://perfectmoney.is/acct/confirm.asp?AccountID='.PM_MEMBER_ID.'&PassPhrase='.PM_PASSWORD.'&Payer_Account='.PM_MEMBER_USD.'&Payee_Account='.$user_purse[$eps].'&Amount='.$sum_amount_pm_to.'&PAY_IN=1&PAYMENT_ID='.$tranid.'', 'rb');

							if(!isset($OPEN_URL_PM)) {
								$code_result = "PERFECTMONEY: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$OUT_PM = "";
								WHILE(!feof($OPEN_URL_PM)) $OUT_PM.= fgets($OPEN_URL_PM);
								fclose($OPEN_URL_PM);
								$C_R_PM = preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $OUT_PM, $RESULT, PREG_SET_ORDER);

								if(!isset($C_R_PM)) {
									$code_result = "PERFECTMONEY: ERROR MATCH!";
									RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
								}else{
									$ARR_PM = ""; $KEY_PM = "ERROR";
									foreach($RESULT as $ITEM) {
										$KEY_PM = $ITEM[1];
										$ARR_PM[$KEY_PM] = $ITEM[2];
									}
									$code_result = (isset($KEY_PM) && trim($KEY_PM)!="ERROR") ? "SUCCESS" : "ERROR PM";
									RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
								}
							}

						}elseif($eps == "pe") {
							require_once(ROOT_DIR."/merchant/payeer/cpayeer.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);

							$AUT_CHECK = $PE_API->isAuth();

							if (isset($AUT_CHECK) && $AUT_CHECK=="1") {
								$PE_TRANSFER = $PE_API->transfer(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','to' => ''.$user_purse[$eps].'','comment' => 'Payment from '.$url.' #'.$tranid.' to account '.$user_name.'','anonim' => 'N'));
								$PE_RES = isset($PE_TRANSFER["historyId"]) ? $PE_TRANSFER["historyId"] : false;
								$code_result = (isset($PE_RES) && intval($PE_RES)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";

								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}

						}elseif($eps == "qw") {
                        $qw_purse='+'.$row_user["qw_purse"];
      
                            require_once(ROOT_DIR."/merchant/payeer/cpay.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '26808','param_ACCOUNT_NUMBER' => ''.$qw_purse.'','comment' => 'Payment from '.$url.' #'.$tranid.' to account '.$user_name.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}
							
						}elseif($eps == "me") {
      
                            require_once(ROOT_DIR."/merchant/payeer/cpay.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '57766314','param_ACCOUNT_NUMBER' => ''.$user_purse[$eps].'','param_CONTACT_PERSON' => 'N','comment' => 'Payment from '.$url.' #'.$tranid.' to account '.$user_name.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}
							
						}elseif($eps == "vs") {
      
                            require_once(ROOT_DIR."/merchant/payeer/cpayeer.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '57568699','param_ACCOUNT_NUMBER' => ''.$user_purse[$eps].'','param_CONTACT_PERSON' => 'N','comment' => 'Payment from '.$url.' #'.$tranid.' to account '.$user_name.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}
							
						}elseif($eps == "ms") {
      
                            require_once(ROOT_DIR."/merchant/payeer/cpay.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '57644634','param_ACCOUNT_NUMBER' => ''.$user_purse[$eps].'','param_CONTACT_PERSON' => 'N','comment' => 'Payment from '.$url.' #'.$tranid.' to account '.$user_name.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}
						}elseif($eps == "be") {							
							$be_purse='+'.$row_user["be_purse"];      
                            require_once(ROOT_DIR."/merchant/payeer/cpay.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '24898938','param_ACCOUNT_NUMBER' => ''.$be_purse.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}
							
						}elseif($eps == "mt") {						
							$mt_purse='+'.$row_user["mt_purse"];    
                            require_once(ROOT_DIR."/merchant/payeer/cpayeer.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '24899291','param_ACCOUNT_NUMBER' => ''.$mt_purse.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}	
							
						}elseif($eps == "mg") {							
							$mg_purse='+'.$row_user["mg_purse"];      
                            require_once(ROOT_DIR."/merchant/payeer/cpay.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '24899391','param_ACCOUNT_NUMBER' => ''.$mg_purse.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}
							
						}elseif($eps == "tl") {						    
						    $tl_purse='+'.$row_user["tl_purse"];     
                            require_once(ROOT_DIR."/merchant/payeer/cpay.php");
							require_once(ROOT_DIR."/merchant/payeer/payeer_config.php");
							$PE_API = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($PE_API->isAuth()){
								$PE_INITOUTPUT = $PE_API->initOutput(array('curIn' => 'RUB','sumOut' => ''.floatval($sum_amount_to).'','curOut' => 'RUB','ps' => '95877310','param_ACCOUNT_NUMBER' => ''.$tl_purse.''));
								$PE_RES = isset($PE_INITOUTPUT["historyId"]) ? $PE_INITOUTPUT["historyId"] : false;
								$historyId = $PE_API->output();
								$code_result = (isset($PE_INITOUTPUT) && intval($PE_INITOUTPUT)>0) ? "SUCCESS" : "ERROR: ID PAY NOT FOUND";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$code_result = "PAYEER: AUTORIZATION ERROR!";
								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}	
						}elseif($eps == "ac") {
							require_once(ROOT_DIR."/api/advcash/advcash_func.php");

							$desc = "Выплата с $url #$tranid (Логин:$user_name, ID:$user_id). Благодарим Вас за работу!";
							$AC_API = SendMoneyAC(floatval($sum_amount_to), $user_purse[$eps], $desc);
							$AC_RES = (isset($AC_API) && strtoupper($AC_API)=="SUCCESS") ? "OK" : $AC_API;

							if($AC_RES == "OK") {
								$code_result = (isset($AC_API) && strtoupper($AC_API)=="SUCCESS") ? "SUCCESS" : $AC_API;

								RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
							}else{
								$result_text = "ERROR"; $message_text = '<span class="msg-error">'.$AC_RES.'</span>';
								exit(my_json_encode($ajax_json, $result_text, $message_text));
							}

						}else{
							$result_text = "ERROR"; $message_text = '<span class="msg-error">ERROR: API для платежной системы не найдено!</span>';
							exit(my_json_encode($ajax_json, $result_text, $message_text));
						}

					}elseif($pay_status[$eps] == 2) {
						$code_result = "SUCCESS";
						RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);

					}else{
						$code_result = "ERROR SYSTEM PAY_STATUS!";
						RESULT_PAY($ajax_json, $pay_status[$eps], $eps, $tranid, $sum_amount, $code_result, $user_id, $user_name, $user_purse[$eps], $_ARR_EPS_TAB[$eps]);
					}
				}else{
					$result_text = "ERROR"; $message_text = '<span class="msg-error">ERROR: NO OPTION!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}
		}else{
			$result_text = "ERROR"; $message_text = '<span class="msg-error">ERROR: NO OPTION!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = '<span class="msg-error">Необходимо авторизоваться!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = '<span class="msg-error">Access denied!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = '<span class="msg-error">Нет корректного AJAX запроса.<span>';
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>