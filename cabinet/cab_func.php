<?php
if(!DEFINED("CABINET")) {
	if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
		$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Hacking attempt!"));
		exit(json_encode_cp1251($json_result));
	}else{
		exit("Hacking attempt!");
	}
}

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

/*function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace('"', "&#34;", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	//$mensaje = mysql_real_escape_string(trim($mensaje));
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
}*/

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), NULL, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
	$mensaje = str_replace("?","&#063;",$mensaje);
	$mensaje = str_replace(">","&#062;",$mensaje);
	$mensaje = str_replace("<","&#060;",$mensaje);
	$mensaje = str_replace("'","&#039;",$mensaje);
	$mensaje = str_replace("`","&#096;",$mensaje);
	$mensaje = str_replace("$","&#036;",$mensaje);
	$mensaje = str_replace('"',"&#034;",$mensaje);
	$mensaje = str_replace("  "," ",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	$mensaje = str_replace("&amp;amp;","&",$mensaje);
	$mensaje = str_replace("&&","&",$mensaje);
	$mensaje = str_replace("http://http://","http://",$mensaje);
	$mensaje = str_replace("https://https://","https://",$mensaje);
	$mensaje = str_replace("&#063;","?",$mensaje);
	return $mensaje;
}


function auth_log_pass($check_ip_agent=false) {
	$user_check = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? trim($_SESSION["userLog"]) : false;
	$pass_check = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? trim($_SESSION["userPas"]) : false;

	if($user_check!=false && $pass_check!=false) {
		$sql_check = mysql_query("SELECT `id`,`username`,`password`,`referer`,`referer2`,`referer3`,`referer4`,`referer5`,`wmid`,`wm_purse`,`money_rb`,`money_rek`,`lastiplog`,`block_agent`,`agent`,`ban_date` FROM `tb_users` WHERE `username`='$user_check'");
		if(mysql_num_rows($sql_check)>0) {
			$row_check = mysql_fetch_assoc($sql_check);

			$user_id = $row_check["id"];
			$user_log = $row_check["username"];
			$user_pas = $row_check["password"];
			$user_pas_md5 = md5($user_pas);
			$user_referer_1 = $row_check["referer"];
			$user_referer_2 = $row_check["referer2"];
			$user_referer_3 = $row_check["referer3"];
			$user_referer_4 = $row_check["referer4"];
			$user_referer_5 = $row_check["referer5"];
			$user_wmid = $row_check["wmid"];
			$user_wm_purse = $row_check["wm_purse"];
			$user_money_rb = $row_check["money_rb"];
			$user_money_rek = $row_check["money_rek"];
			$user_lastiplog = $row_check["lastiplog"];
			$user_block_agent = $row_check["block_agent"];
			$user_agent = $row_check["agent"];
			$user_ban_date = $row_check["ban_date"];

			if($check_ip_agent!=false) {
				if($user_check!=$user_log | $pass_check!=$user_pas_md5) {
					if(count($_SESSION)>0) {
						foreach($_SESSION as $key => $val) {
							unset($_SESSION["$key"]);
						}
					}
					if($check_ip_agent!=2) {
						echo '<script type="text/javascript">location.replace("//'.$_SERVER["HTTP_HOST"].'/err_log.php?s=1");</script>';
						echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=//'.$_SERVER["HTTP_HOST"].'/err_log.php?s=1"></noscript>';
					}
					$result["status"] = "FALSE";
				}else{
					$result["status"] = "TRUE";
				}
			}else{
				$result["status"] = "TRUE";
			}

			$result["user_id"] = $user_id;
			$result["user_log"] = $user_log;
			$result["user_pas"] = $user_pas;
			$result["user_pas_md5"] = $user_pas_md5;
			$result["user_referer_1"] = $user_referer_1;
			$result["user_referer_2"] = $user_referer_2;
			$result["user_referer_3"] = $user_referer_3;
			$result["user_referer_4"] = $user_referer_4;
			$result["user_referer_5"] = $user_referer_5;
			$result["user_wmid"] = $user_wmid;
			$result["user_wm_purse"] = $user_wm_purse;
			$result["user_money_rb"] = $user_money_rb;
			$result["user_money_rek"] = $user_money_rek;
			$result["user_lastiplog"] = $user_lastiplog;
			$result["user_block_agent"] = $user_block_agent;
			$result["user_agent"] = $user_agent;
			$result["user_ban_date"] = $user_ban_date;
		}else{
			$result["status"] = "FALSE";
		}
	}else{
		$result["status"] = "FALSE";
	}

	return $result;
}

function count_text($count, $text1, $text2, $text3, $text) {
	if($count>0) {
		if( ($count>=10) && ($count<=20) ) {
			return "$count $text1";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "$count $text2"; break;
				case 2: case 3: case 4: return "$count $text3"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "$count $text1"; break;
			}
		}
	}else{
		return "$text";
	}
}

function adv_date_ost($ost) {
	if($ost>0) {
		$days = floor($ost/86400);
		$hours = floor(($ost - ($days * 86400))/3600);

		if($days>0) {
			$days = ceil($ost/86400);
			$hours = 0;
		}elseif($days==0) {
			if(ceil(($ost - ($days * 86400))/3600)==24) {
				$days = 1;
				$hours = 0;
			}else{
				$hours = ceil(($ost - ($days * 86400))/3600);
			}
		}

		if($days>0) {
			if(($days>=10)&&($days<=20)) {
				$d = "дней";
			}else{
				switch(substr($days, -1, 1)){
					case 1: $d = "день"; break;
					case 2: case 3: case 4: $d = "дня"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: $d = "дней"; break;
				}
			}
		}else{
			$days = ""; $d = "";
		}

		if($hours>0) {
			if(($hours>=10)&&($hours<=20)) {
				$h = "часов";
			}else{
				switch(substr($hours, -1, 1)) {
					case 1: $h = "час"; break;
					case 2: case 3: case 4: $h = "часа"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: $h = "часов"; break;
				}
			}
		}else{
			$hours = ""; $h = "";
		}

		return "$days $d $hours $h";
	}else{
		return "Пополнить";
	}
}

?>