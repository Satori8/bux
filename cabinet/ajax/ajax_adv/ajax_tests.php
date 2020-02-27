<?php
error_reporting(E_ALL);
if(!DEFINED("TESTS_AJAX")) {die ("Hacking attempt!");}
if($type_ads!="tests") {die ("Hacking attempt!");}
require_once(DOC_ROOT."/bbcode/bbcode.lib.php");

$geo_cod_arr = array(
	 1 => 'RU',  2 => 'UA',  3 => 'BY',  4 => 'MD',  5 => 'KZ',  6 => 'AM',  7 => 'UZ',  8 => 'LV',  9 => 'DE', 10 => 'GE', 
	11 => 'LT', 12 => 'FR', 13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 
	21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 29 => 'IR', 30 => 'GR', 
	31 => 'TR', 32 => 'PL', 33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO'
);

function myErrorHandler($errno, $errstr, $errfile, $errline, $json_result) {
	switch ($errno) {
		case(1): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Fatal error[$errno]: $errstr in line $errline")); break;
		case(2): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Warning[$errno]: $errstr in line $errline")); break;
		case(8): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Notice[$errno]: $errstr in line $errline")); break;
		default: $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "[$errno] $errstr in line $errline")); break;
	}
	exit(json_encode_cp1251($json_result));
}
$set_error_handler = set_error_handler('myErrorHandler', E_ALL);

mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `status`='3' AND `balance`>=`cena_advs`");

if($option == "BanUser") {
	$user_id_ban = ( isset($_POST["iduser"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["iduser"])) ) ? abs(intval(limpiarez($_POST["iduser"]))) : 0;

	$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$user_id_ban'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$user_id_ban = $row["id"];
		$user_name_ban = $row["username"];

		if($user_id_ban==$user_id) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Зачем добавлять самого себя в черный список?");
			exit(json_encode_cp1251($json_result));
		}else{

			$sql_b = mysql_query("SELECT `id` FROM `tb_ads_tests_bl` WHERE `user_id`='$user_id_ban' AND `rek_id`='$user_id'");
			if(mysql_num_rows($sql_b)>0) {
				$json_result["result"] = "ERROR";
				$json_result["message"] = iconv("CP1251", "UTF-8", "Пользователь $user_name_ban уже в черном списке.");
				exit(json_encode_cp1251($json_result));
			}else{
				mysql_query("INSERT INTO `tb_ads_tests_bl` (`date`,`user_id`,`user_name`,`rek_id`,`rek_name`,`ip`) 
				VALUES('".DATE("d.m.Y H:i:s")."','$user_id_ban','$user_name_ban','$user_id','$username','$laip')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				$json_result["result"] = "OK";
				$json_result["message"] = iconv("CP1251", "UTF-8", "Пользователь $user_name_ban успешно добавлен в черный список.");
				exit(json_encode_cp1251($json_result));
			}
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Пользователь с ID:$user_id_ban не найден!");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "UnBanUser") {
	$user_id_ban = ( isset($_POST["iduser"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["iduser"])) ) ? abs(intval(limpiarez($_POST["iduser"]))) : 0;

	$sql = mysql_query("SELECT `id`,`user_id`,`user_name` FROM `tb_ads_tests_bl` WHERE `user_id`='$user_id_ban' AND `rek_id`='$user_id'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$user_id_ban = $row["user_id"];
		$user_name_ban = $row["user_name"];

		mysql_query("DELETE FROM `tb_ads_tests_bl` WHERE `user_id`='$user_id_ban' AND `rek_id`='$user_id'");

		$count_bl = mysql_num_rows(mysql_query("SELECT `id`,`user_id`,`user_name` FROM `tb_ads_tests_bl` WHERE `rek_id`='$user_id'"));

		$json_result["result"] = "OK";
		$json_result["message"] = iconv("CP1251", "UTF-8", "$count_bl");
		exit(json_encode_cp1251($json_result));
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Пользователь с ID:$user_id_ban в черном списке не найден!");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status == 0) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Для запуска, необходимо пополнить рекламный бюджет!");
			exit(json_encode_cp1251($json_result));

		}elseif($status == 1) {
			mysql_query("UPDATE `tb_ads_tests` SET `status`='2', `date_edit`='".time()."' WHERE `id`='$id' AND `username`='$username'");

			$json_result["result"] = "OK";
			$json_result["status"] = iconv("CP1251", "UTF-8", '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="PlayPause('.$row["id"].', \'tests\');"></span>');
			exit(json_encode_cp1251($json_result));

		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `id`='$id' AND `username`='$username'");

			$json_result["result"] = "OK";
			$json_result["status"] = iconv("CP1251", "UTF-8", '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="PlayPause('.$row["id"].', \'tests\');"></span>');
			exit(json_encode_cp1251($json_result));

		}elseif($status == 3) {
			$json_result["result"] = "OK";
			$json_result["status"] = iconv("CP1251", "UTF-8", '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>');
			$json_result["message"] = iconv("CP1251", "UTF-8", "Для запуска, необходимо пополнить рекламный бюджет!");
			exit(json_encode_cp1251($json_result));

		}elseif($status == 4) {
			$json_result["result"] = "OK";
			$json_result["status"] = iconv("CP1251", "UTF-8", '<span class="adv-block" title="Рекламная площадка заблокирована" onClick="alert(\'Рекламная площадка заблокирована. Необходимо внести изменения!\');"></span>');
			$json_result["message"] = iconv("CP1251", "UTF-8", "Рекламная площадка заблокирована. Необходимо внести изменения!");
			exit(json_encode_cp1251($json_result));

		}else{
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Ошибка! Статус не определен.");
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "ViewClaims") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];

		$message_text = "";
		$message_text.= '<div class="box-modal" id="ModalClaims" style="text-align:justify; width:900px;">';
			$message_text.= '<div class="box-modal-title">Просмотр жалоб на тест №'.$id.'</div>';
			$message_text.= '<div class="box-modal-close modalpopup-close"></div>';
			$message_text.= '<div class="box-modal-content" style="margin:1px; padding:5px 3px; font-size:11px;">';

				$message_text.= '<table class="tables" style="margin:0 auto; padding:0;">';
				$message_text.= '<thead><tr align="center">';
					$message_text.= '<th width="120" align="center" style="padding:4px 5px;">Логин</th><th width="120" align="center" style="padding:4px 5px;">Дата</th><th width="100" align="center" style="padding:4px 5px;">IP</th><th align="center" style="padding:4px 5px;">Текст жалобы</th>';
				$message_text.= '</thead></tr>';
				$message_text.= '</table>';

				$message_text.= '<div id="table-content" style="overflow:auto;">';
					$message_text.= '<table class="tables" style="margin:0 auto; padding:0;">';
					$sql = mysql_query("SELECT * FROM `tb_ads_claims` WHERE `ident`='$id' AND `type`='tests' ORDER BY `id` DESC");
					if(mysql_num_rows($sql)>0) {
						while ($row = mysql_fetch_assoc($sql)) {
							$message_text.= '<tr id="claims-'.$row["id"].'" align="center">';
								$message_text.= '<td width="120" style="padding:2px 5px;">'.$row["username"].'</td>';
								$message_text.= '<td width="120" style="padding:2px 5px;">'.DATE("d.m.Yг. H:i", $row["time"]).'</td>';
								$message_text.= '<td width="100" style="padding:2px 5px;">'.$row["ip"].'</td>';
								$message_text.= '<td style="padding:2px 5px;">'.$row["claims"].'</td>';
							$message_text.= '</tr>';
						}
					}else{
						$message_text.= '<tr align="center"><td colspan="3"><b>Жалобы не обнаружены</b></td></tr>';
					}
					$message_text.= '</table>';
				$message_text.= '</div>';

			$message_text.= '</div>';
		$message_text.= '</div>';

		$json_result["result"] = "OK";
		$json_result["message"] = iconv("CP1251", "UTF-8", $message_text);
		exit(json_encode_cp1251($json_result));

	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$balance = $row["balance"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");

			$json_result["result"] = "OK";
			exit(json_encode_cp1251($json_result));

		}elseif($status == 1) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Тест, прежде чем удалить, необходимо поставить на паузу!");
			exit(json_encode_cp1251($json_result));

		}elseif($status == 2) {
			if($balance>=1) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_comis_del' AND `howmany`='1'");
				$tests_comis_del = number_format(mysql_result($sql,0,0), 0, ".", "");

				$money_back = ($balance - $balance * ($tests_comis_del/100));
				$money_back = number_format($money_back, 2, ".", "");

				mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back', `money_rek`=`money_rek`-'$money_back'  WHERE `username`='$username'");
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$username','$user_id','$wmr_user','".DATE("d.m.Y H:i")."','".time()."','$money_back','Возврат средств с рекламной площадки (Тесты, ID:$id)', 'Зачислено', 'reklama')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				$json_result["result"] = "OK";
				$json_result["message"] = iconv("CP1251", "UTF-8", "Рекламная площадка №$id успешно удалена.\nНа Ваш рекламный счет зачислено $money_back руб.");
				exit(json_encode_cp1251($json_result));
			}else{
				mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");

				$json_result["result"] = "OK";
				exit(json_encode_cp1251($json_result));
			}

		}elseif($status == 3 | $status == 4) {
			mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");

			$json_result["result"] = "OK";
			exit(json_encode_cp1251($json_result));
		}else{
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Ошибка! Статус не определен.");
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "go_edit") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status==1) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Перед редактированием необходимо приостановить рекламную кампанию");
			exit(json_encode_cp1251($json_result));
		}else{
			$json_result["result"] = "OK";
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}
	
}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$goods_out = $row["goods_out"];
		$bads_out = $row["bads_out"];

		if($status == 0 | ($goods_out == 0 && $bads_out == 0)) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Счётчик этой площадки уже равен 0");
			exit(json_encode_cp1251($json_result));
		}else{
			mysql_query("UPDATE `tb_ads_tests` SET `goods_out`='0', `bads_out`='0', `date_edit`='".time()."', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'");

			$json_result["result"] = "OK";
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "get_info") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$description = new bbcode($row["description"]);
		$description = $description->get_html();
		$description = str_replace("&amp;", "&", $description);
		$url = $row["url"];
		$questions = unserialize($row["questions"]);
		$answers = unserialize($row["answers"]);
		$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();

		$revisit_tab[0] = "доступно всем каждые 24 часа";
		$revisit_tab[1] = "доступно всем каждые 3 дня";
		$revisit_tab[2] = "доступно всем каждую неделю";
		$revisit_tab[3] = "доступно всем каждые 2 недели";
		$revisit_tab[4] = "доступно всем каждый месяц";
		$color_tab[0] = "нет";
		$color_tab[1] = "да";
		$unic_ip_user_tab[0] = "нет";
		$unic_ip_user_tab[1] = "да, 100% совпадение";
		$unic_ip_user_tab[2] = "усиленный по маске до 2 чисел (255.255.X.X)";
		$date_reg_user_tab[0] = "все пользователи проекта";
		$date_reg_user_tab[1] = "до 7 дней с момента регистрации";
		$date_reg_user_tab[2] = "от 7 дней с момента регистрации";
		$date_reg_user_tab[3] = "от 30 дней с момента регистрации";
		$date_reg_user_tab[4] = "от 90 дней с момента регистрации";
		$sex_user_tab[0] = "все пользователи проекта";
		$sex_user_tab[1] = "только мужчины";
		$sex_user_tab[2] = "только женщины";

		$text_json = '<div align="justify" style="margin:auto 5px; color: #333333;">';
			$text_json.= '<h3 class="sp" style="margin-top:0px;">Инструкция для тестирования:</h3>';
			$text_json.= "$description<br><br>";
			$text_json.= '<h3 class="sp" style="margin-top:0px;">Ссылка для перехода:</h3>';
			$text_json.= "$url<br><br>";

			for($i=1; $i<=count($questions); $i++){
				$text_json.= '<h3 class="sp" style="margin-top:0px;">Вопрос №'.$i.':</h3>';
				$text_json.= "$questions[$i]<br>";
				$text_json.= '<div style="margin-left:20px;"><u>Ответы:</u><br>';
				for($y=1; $y<=3; $y++){
					$text_json.= '<span style="color: '.($y==1 ? "#009125;" : "#FF0000").'">'.$answers[$i][$y].'</span>'.($y!=3 ? "<br>" : "").'';
				}
				$text_json.= '</div><br>';
			}

			$text_json.= '<h3 class="sp" style="margin-top:0px;">Настройки:</h3>';
			$text_json.= 'Технология тестирования: <b>'.$revisit_tab[$row["revisit"]].'</b><br>';
			$text_json.= 'Выделить тест: <b>'.$color_tab[$row["color"]].'</b><br>';
			$text_json.= 'Уникальный IP: <b>'.$unic_ip_user_tab[$row["unic_ip_user"]].'</b><br>';
			$text_json.= 'По дате регистрации: <b>'.$date_reg_user_tab[$row["date_reg_user"]].'</b><br>';
			$text_json.= 'По половому признаку: <b>'.$sex_user_tab[$row["sex_user"]].'</b><br>';
			$text_json.= 'Гео-таргетинг:&nbsp;';

			if(count($geo_targ)>0) {
				for($i=0; $i<count($geo_targ); $i++){
					$text_json.= '&nbsp;<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($geo_targ[$i]).'.gif" alt="" align="absmiddle" style="margin:0; padding:0; padding-left:1px;" />';
				}
			}else{
				$text_json.= '<b>все страны</b>';
			}
		$text_json.= '</div>';

		$json_result["result"] = "OK";
		$json_result["message"] = iconv("CP1251", "UTF-8", $text_json);
		exit(json_encode_cp1251($json_result));
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "get_add_money") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_min_pay' AND `howmany`='1'");
		$tests_min_pay = number_format(mysql_result($sql,0,0), 2, ".", "");

		$text_json = 'Укажите сумму, которую вы хотите внести в бюджет рекламной площадки<br>(Минимум '.count_text($tests_min_pay, "рублей", "рубль", "рубля", "").')';
		$text_json.= '<input type="text" maxlength="10" id="money_add" value="100.00" class="payadv" onChange="PayAds();" onKeyUp="PayAds();" />';
		$text_json.= '<div align="center"><span onClick="PayAds(\''.$row["id"].'\', \'tests\');" class="sub-green" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></div>';
		$text_json.= '<div id="info-msg-addmoney" style="display: none"></div>';

		$json_result["result"] = "OK";
		$json_result["message"] = iconv("CP1251", "UTF-8", $text_json);
		exit(json_encode_cp1251($json_result));
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "save") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 55) : false;
		$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 2000) : false;
		$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
		if (get_magic_quotes_gpc()) { $description = stripslashes($description); }
		$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])>=0 && intval($_POST["revisit"])<=4)) ? intval(limpiarez($_POST["revisit"])) : "0";
		$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(limpiarez($_POST["color"])) : "0";
		$unic_ip_user = (isset($_POST["unic_ip_user"]) && (intval($_POST["unic_ip_user"])>=0 && intval($_POST["unic_ip_user"])<=2)) ? intval($_POST["unic_ip_user"]) : "0";
		$date_reg_user = (isset($_POST["date_reg_user"]) && (intval($_POST["date_reg_user"])>=0 && intval($_POST["date_reg_user"])<=4)) ? intval($_POST["date_reg_user"]) : "0";
		$sex_user = ( isset($_POST["sex_user"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["sex_user"])) && intval(limpiarez($_POST["sex_user"]))>=0 && intval(limpiarez($_POST["sex_user"]))<=2 ) ? abs(intval(limpiarez($_POST["sex_user"]))) : 0;
		$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
		$black_url = getHost($url);

		for($i=1; $i<=5; $i++) { $quest[$i] = (isset($_POST["quest$i"])) ? limitatexto(limpiarez($_POST["quest$i"]), 300) : false;}
		for($i=1; $i<=5; $i++) {
			for($y=1; $y<=3; $y++) {
				$answ[$i][$y] = (isset($_POST["answ$i$y"])) ? limitatexto(limpiarez($_POST["answ$i$y"]), 30) : false;
			}
		}

		if(is_array($country)) {
			foreach($country as $key => $val) {
				if(array_search($val, $geo_cod_arr)) {
					$id_country = array_search($val, $geo_cod_arr);
					$country_arr[] = $val;
				}
			}
		}
		$country = isset($country_arr) ? trim(strtoupper(implode(", ", $country_arr))) : false;

		if($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") {
			$quest[4] = ""; $answ[4][1] = ""; $answ[4][2] = ""; $answ[4][3] = "";
		}
		if($quest[5]=="" | $answ[5][1]=="" | $answ[5][2]=="" | $answ[5][3]=="") {
			$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
		}
		if( ($quest[5]!="" && $answ[5][1]!="" && $answ[5][2]!="" && $answ[5][3]!="") && ($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") ) {
			$quest[4] = $quest[5]; $answ[4][1] = $answ[5][1]; $answ[4][2] = $answ[5][2]; $answ[4][3] = $answ[5][3];
			$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
		}

		$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

		if($title=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали заголовок теста!");
			exit(json_encode_cp1251($json_result));

		}elseif($description=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не описали инструкцию к выполнению теста!");
			exit(json_encode_cp1251($json_result));

		}elseif(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
			$row_bl = mysql_fetch_assoc($sql_bl);

			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Сайт ".$row_bl["domen"]." заблокирован и занесен в черный список проекта ".strtoupper($_SERVER["HTTP_HOST"])." Причина: ".$row_bl["cause"]."");
			exit(json_encode_cp1251($json_result));

		}elseif($url==false | $url=="http://" | $url=="https://") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали URL-адрес сайта!");
			exit(json_encode_cp1251($json_result));

		}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы неверно указали URL-адрес сайта!");
			exit(json_encode_cp1251($json_result));

		}elseif($quest[1]=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали первый вопрос!");
			exit(json_encode_cp1251($json_result));

		}elseif($answ[1][1]=="" | $answ[1][2]=="" | $answ[1][3]=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали варианты ответа на первый вопрос!");
			exit(json_encode_cp1251($json_result));

		}elseif($quest[2]=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали второй вопрос!");
			exit(json_encode_cp1251($json_result));

		}elseif($answ[2][1]=="" | $answ[2][2]=="" | $answ[2][3]=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали варианты ответа на второй вопрос!");
			exit(json_encode_cp1251($json_result));

		}elseif($quest[3]=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали третий вопрос!");
			exit(json_encode_cp1251($json_result));

		}elseif($answ[3][1]=="" | $answ[3][2]=="" | $answ[3][3]=="") {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали варианты ответа на третий вопрос!");
			exit(json_encode_cp1251($json_result));

		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", SFB_YANDEX($url));
			exit(json_encode_cp1251($json_result));

		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
			$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
			$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_quest' AND `howmany`='1'");
			$tests_cena_quest = number_format(mysql_result($sql,0,0), 4, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_color' AND `howmany`='1'");
			$tests_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

			for($i=1; $i<=4; $i++) {
				$tests_cena_revisit[0] = 0;
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_revisit' AND `howmany`='$i'");
				$tests_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
			}

			for($i=1; $i<=2; $i++) {
				$tests_cena_unic_ip[0] = 0;
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_unic_ip' AND `howmany`='$i'");
				$tests_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
			}

			$summa_dd = 0;
			if($quest[4]!="") $summa_dd+= $tests_cena_quest;
			if($quest[5]!="") $summa_dd+= $tests_cena_quest;

			$cena_user = ($tests_cena_hit + $summa_dd) / (($tests_nacenka+100)/100);
			$cena_advs = ($tests_cena_hit + $summa_dd + $tests_cena_revisit[$revisit] + $tests_cena_color * $color + $tests_cena_unic_ip[$unic_ip_user]);

			$cena_user = number_format($cena_user, 4, ".", "");
			$cena_advs = number_format($cena_advs, 4, ".", "");

			if($quest[4]=="") unset($quest[4], $answ[4]);
			if($quest[5]=="") unset($quest[5], $answ[5]);

			$questions = serialize($quest);
			$answers = serialize($answ);

			mysql_query("DELETE FROM `tb_ads_tests` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'");

			mysql_query("UPDATE `tb_ads_tests` SET 
				`date_edit`='".time()."',`title`='$title',`description`='$description',`url`='$url',`questions`='$questions',`answers`='$answers',
				`geo_targ`='$country',`revisit`='$revisit',`color`='$color',`date_reg_user`='$date_reg_user',
				`unic_ip_user`='$unic_ip_user',`sex_user`='$sex_user',`cena_user`='$cena_user',`cena_advs`='$cena_advs',`ip`='$laip' 
			WHERE `id`='$id' AND `username`='$username'");

			if($revisit != $row["revisit"]) {
				$sql_v = mysql_query("SELECT `id` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>='".time()."'");
				if(mysql_num_rows($sql_v)>0) {
					while($row_v = mysql_fetch_assoc($sql_v)) {
						if($revisit==0) {
							$time_next = (1*24*60*60);
						}elseif($revisit==1) {
							$time_next = (3*24*60*60);
						}elseif($revisit==2) {
							$time_next = (7*24*60*60);
						}elseif($revisit==3) {
							$time_next = (14*24*60*60);
						}elseif($revisit==4) {
							$time_next = (30*24*60*60);
						}else{
							$time_next = (1*24*60*60);
						}

						mysql_query("UPDATE `tb_ads_tests_visits` SET `time_next`=`time_end`+'$time_next' WHERE `id`='".$row_v["id"]."'");
					}
				}
			}

			exit(json_encode_cp1251(array("result" => "OK")));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "pay") {
	$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$cena_advs = $row["cena_advs"];
		$balance = $row["balance"];
		$goods_out = $row["goods_out"];
		$bads_out = $row["bads_out"];
		$merch_tran_id = $row["merch_tran_id"];
		$money_pay = ( isset($_POST["money_add"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_min_pay' AND `howmany`='1'");
		$tests_min_pay = number_format(mysql_result($sql,0,0), 2, ".", "");

		if($money_pay<$tests_min_pay) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Минимальная сумма пополнения - $tests_min_pay руб.");
			exit(json_encode_cp1251($json_result));

		}elseif($money_user_rb<$money_pay) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "На Вашем рекламном счету недостаточно средств!");
			exit(json_encode_cp1251($json_result));

		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
			$reit_rek = mysql_result($sql,0,0);
			$reit_add_1 = floor(bcdiv($money_pay, 10)) * $reit_rek;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
			$reit_ref_rek = mysql_result($sql,0,0);
			$reit_add_2 = floor(bcdiv($money_pay, 10)) * $reit_ref_rek;

			if($status=="0") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'");}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'");
				mysql_query("UPDATE `tb_ads_tests` SET `status`='1',`wm_sent`='0',`method_pay`='10',`date_edit`='".time()."',`money`='$money_pay',`balance`='$money_pay' WHERE `id`='$id' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1");
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$username','$user_id','$wmr_user','".DATE("d.m.Y H:i")."','".time()."','$money_pay','Пополнение баланса рекламной площадки (Тесты, ID:$id)', 'Оплачено', 'reklama')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				stat_pay("tests", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);

				$balance = ( isset($balance) && ($balance+$money_pay)<1 ) ? number_format(($balance+$money_pay), 4, ".", "") : number_format(($balance+$money_pay), 2, ".", "");
				$totals = number_format(floor(bcdiv($balance,$cena_advs)), 0, ".", "`");
				$json_result = array(
						"result" => "OK", 
						"status" => iconv("CP1251", "UTF-8", '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="PlayPause('.$row["id"].', \'tests\');"></span>'), 
						"totals" => "$totals", 
						"balance" => "".($balance < 1 ? number_format($balance, 4, ".", "`") : number_format($balance, 2, ".", "`"))."", 
						"goods_out" => "".number_format($goods_out, 0, ".", "`")."", 
						"bads_out" => "".number_format($bads_out, 0, ".", "`").""
				 );
				exit(json_encode_cp1251($json_result));

			}elseif($status=="1" | $status=="2" | $status=="4") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'");}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'");
				mysql_query("UPDATE `tb_ads_tests` SET `wm_sent`='0',`method_pay`='10',`date_edit`='".time()."',`money`=`money`+'$money_pay',`balance`=`balance`+'$money_pay' WHERE `id`='$id' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1");
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$username','$user_id','$wmr_user','".DATE("d.m.Y H:i")."','".time()."','$money_pay','Пополнение баланса рекламной площадки (Тесты, ID:$id)', 'Оплачено', 'reklama')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				stat_pay("tests", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);

				$balance = ( isset($balance) && ($balance+$money_pay)<1 ) ? number_format(($balance+$money_pay), 4, ".", "") : number_format(($balance+$money_pay), 2, ".", "");
				$totals = number_format(floor(bcdiv($balance,$cena_advs)), 0, ".", "`");
				$json_result = array(
						"result" => "OK", 
						"status" => "", 
						"totals" => "$totals", 
						"balance" => "".($balance < 1 ? number_format($balance, 4, ".", "`") : number_format($balance, 2, ".", "`"))."", 
						"goods_out" => "".number_format($goods_out, 0, ".", "`")."", 
						"bads_out" => "".number_format($bads_out, 0, ".", "`").""
				 );
				exit(json_encode_cp1251($json_result));

			}elseif($status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'");}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'");
				mysql_query("UPDATE `tb_ads_tests` SET `status`='1',`wm_sent`='0',`method_pay`='10',`date_edit`='".time()."',`money`=`money`+'$money_pay',`balance`=`balance`+'$money_pay' WHERE `id`='$id' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1");
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$username','$user_id','$wmr_user','".DATE("d.m.Y H:i")."','".time()."','$money_pay','Пополнение баланса рекламной площадки (Тесты, ID:$id)', 'Оплачено', 'reklama')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

				stat_pay("tests", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);

				$balance = ( isset($balance) && ($balance+$money_pay)<1 ) ? number_format(($balance+$money_pay), 4, ".", "") : number_format(($balance+$money_pay), 2, ".", "");
				$totals = number_format(floor(bcdiv($balance,$cena_advs)), 0, ".", "`");
				$json_result = array(
						"result" => "OK", 
						"status" => iconv("CP1251", "UTF-8", '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="PlayPause('.$row["id"].', \'tests\');"></span>'), 
						"totals" => "$totals", 
						"balance" => "".($balance < 1 ? number_format($balance, 4, ".", "`") : number_format($balance, 2, ".", "`"))."", 
						"goods_out" => "".number_format($goods_out, 0, ".", "`")."", 
						"bads_out" => "".number_format($bads_out, 0, ".", "`").""
				 );
				exit(json_encode_cp1251($json_result));

			}else{
				$json_result["result"] = "ERROR";
				$json_result["message"] = iconv("CP1251", "UTF-8", "Ошибка! Статус не определен.");
				exit(json_encode_cp1251($json_result));
			}
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}else{
	$json_result["result"] = "ERROR";
	$json_result["message"] = iconv("CP1251", "UTF-8", "ERROR! NO OPTION!");
	exit(json_encode_cp1251($json_result));
}

?>