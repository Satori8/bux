<?php
if(!DEFINED("MAILS_AJAX")) {die ("Hacking attempt!");}
mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date_end`='".time()."' WHERE `status`>'0'  AND (`totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());

if($option == "pay_adv_up") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1' AND (`date_up`='0' OR `date_up`<'".(time()-60)."') AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_uplist' AND `howmany`='1'");
		$mails_cena_uplist = number_format(mysql_result($sql,0,0), 2, ".", "");

		if($row["up_list"]>0) {
			$position = mysql_result(mysql_query("SELECT COUNT(*) FROM `tb_ads_mails` WHERE `status`='1' AND `date_up`>(SELECT `date_up` FROM `tb_ads_mails` WHERE `id`='$id')" ),0,0)+1;
		}else{
			$position = 0;
		}

		if($money_user_rb>=$mails_cena_uplist && ($position>1 | $position==0)) {
			mysql_query("UPDATE `tb_ads_mails` SET `date_up`='".time()."' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$mails_cena_uplist', `money_rek`=`money_rek`+'$mails_cena_uplist' WHERE `username`='$username'") or die(mysql_error());
			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
			VALUES('$username','".DATE("d.m.Yг. H:i")."','$mails_cena_uplist','Поднятие ссылки в списке писем ID:$id','Списано','rashod')") or die(mysql_error());

			exit("OK");
		}
	}

}elseif($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$nolimit = $row["nolimit"];

		if($status == 0) {
			exit("0");
		}elseif($status == 1) {
			if($nolimit!=0) {
				exit("BEZLIMIT");
			}else{
				mysql_query("UPDATE `tb_ads_mails` SET `status`='2' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
				exit();
			}
		}elseif($status == 2) {
			if($nolimit!=0) {
				exit("BEZLIMIT");
			}else{
				mysql_query("UPDATE `tb_ads_mails` SET `status`='1' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
				exit();
			}
		}elseif($status == 3) {
			if($nolimit!=0) {
				echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart_rc();"></span>';
				exit();
			}else{
				exit("0");
			}
		}else{
			exit("ERROR");
		}
	}else{
		exit("ERRORNOID");
	}

}elseif($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}elseif($status == 1 | $status == 2) {
			exit("Удаление возможно только после окончания показа.");
		}elseif($status == 3) {
			mysql_query("DELETE FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}else{
			exit("ERROR");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("Счётчик этой площадки уже равен 0");
		}else{
			mysql_query("UPDATE `tb_ads_mails` SET `outside`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "save") {
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_ot' AND `howmany`='1'");
	$mails_timer_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_do' AND `howmany`='1'");
	$mails_timer_do = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_status' AND `howmany`='1'");
	$mails_nolimit_status = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_timer' AND `howmany`='1'");
	$mails_nolimit_timer = number_format(mysql_result($sql,0,0), 0, ".", "");

	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 255) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 5000) : false;
	if (get_magic_quotes_gpc()) {$description = stripslashes($description);}
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),300) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,3}$|", trim($_POST["timer"])) && intval(limpiarez(trim($_POST["timer"]))) >= $mails_timer_ot && intval(limpiarez(trim($_POST["timer"]))) <= $mails_timer_do ) ? intval(limpiarez(trim($_POST["timer"]))) : false;
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
	$question = (isset($_POST["question"])) ? limitatexto(limpiarez($_POST["question"]), 255) : false;
	$answer_t = (isset($_POST["answer_t"])) ? limitatexto(limpiarez($_POST["answer_t"]), 255) : false;
	$answer_f_1 = (isset($_POST["answer_f_1"])) ? limitatexto(limpiarez($_POST["answer_f_1"]), 255) : false;
	$answer_f_2 = (isset($_POST["answer_f_2"])) ? limitatexto(limpiarez($_POST["answer_f_2"]), 255) : false;

	$nolimit = (isset($_POST["nolimit"]) && (intval($_POST["nolimit"])==0 | intval($_POST["nolimit"])==1)) ? intval(trim($_POST["nolimit"])) : "0";
	$active = (isset($_POST["active"]) && (intval($_POST["active"])==0 | intval($_POST["active"])==1)) ? intval(trim($_POST["active"])) : "0";
	$gotosite = (isset($_POST["gotosite"]) && (intval($_POST["gotosite"])==0 | intval($_POST["gotosite"])==1)) ? intval(trim($_POST["gotosite"])) : "0";
	$up_list = (isset($_POST["up_list"]) && (intval($_POST["up_list"])>=0 | intval($_POST["up_list"])<=1)) ? intval(trim($_POST["up_list"])) : "0";
	$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])>=0 | intval($_POST["revisit"])<=2)) ? intval(trim($_POST["revisit"])) : "0";
	$new_users = (isset($_POST["new_users"]) && (intval($_POST["new_users"])>=0 | intval($_POST["new_users"])<=1)) ? intval($_POST["new_users"]) : "0";
	$unic_ip = (isset($_POST["unic_ip"]) && (intval($_POST["unic_ip"])>=0 | intval($_POST["unic_ip"])<=2)) ? intval($_POST["unic_ip"]) : "0";
	$no_ref = (isset($_POST["no_ref"]) && (intval($_POST["no_ref"])>=0 | intval($_POST["no_ref"])<=1)) ? intval($_POST["no_ref"]) : "0";
	$sex_adv = (isset($_POST["sex_adv"]) && (intval($_POST["sex_adv"])>=0 | intval($_POST["sex_adv"])<=2)) ? intval($_POST["sex_adv"]) : "0";
	$to_ref = (isset($_POST["to_ref"]) && (intval($_POST["to_ref"])>=0 | intval($_POST["to_ref"])<=2)) ? intval($_POST["to_ref"]) : "0";
	$wm_check = (isset($_POST["wm_check"]) && preg_match("|^[0-1]{1}$|", trim($_POST["wm_check"])) ) ? intval(trim($_POST["wm_check"])) : "0";

	$laip = getRealIP();
	$black_url = @getHost($url);

	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
	$geo_cod_arr = array(
		1  => 'RU', 2  => 'UA', 3  => 'BY', 4  => 'MD', 5  => 'KZ', 6  => 'AM', 7  => 'UZ', 8  => 'LV', 
		9  => 'DE', 10 => 'GE',	11 => 'LT', 12 => 'FR', 13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 
		17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 
		25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 29 => 'IR', 30 => 'GR', 31 => 'TR', 32 => 'PL', 
		33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO'
	);
	$geo_name_arr_ru = array(
		'RU' => 'Россия', 	'UA' => 'Украина', 	'BY' => 'Белоруссия', 	'MD' => 'Молдавия', 	'KZ' => 'Казахстан', 	'AM' => 'Армения', 
		'UZ' => 'Узбекистан',	'LV' => 'Латвия',	'DE' => 'Германия', 	'GE' => 'Грузия', 	'LT' => 'Литва', 	'FR' => 'Франция', 
		'AZ' => 'Азербайджан', 	'US' => 'США', 		'VN' => 'Вьетнам', 	'PT' => 'Португалия', 	'GB' => 'Англия', 	'BE' => 'Бельгия', 
		'ES' => 'Испания', 	'CN' => 'Китай',	'TJ' => 'Таджикистан',  'EE' => 'Эстония', 	'IT' => 'Италия', 	'KG' => 'Киргизия',
		'IL' => 'Израиль', 	'CA' => 'Канада', 	'TM' => 'Туркменистан', 'BG' => 'Болгария',	'IR' => 'Иран', 	'GR' => 'Греция', 
		'TR' => 'Турция', 	'PL' => 'Польша',	'FI' => 'Финляндия', 	'EG' => 'Египет', 	'SE' => 'Швеция', 	'RO' => 'Румыния'
	);
	if(is_array($country)) {
		foreach($country as $key => $val) {
			if(array_search($val, $geo_cod_arr)) {
				$id_country = array_search($val, $geo_cod_arr);
				$country_arr[] = $val;
			}
		}
	}
	$country = isset($country_arr) ? trim(strtoupper(implode(', ', $country_arr))) : false;


	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status==1 | $status==2) {
			$nolimit = $row["nolimit"];
			$timer = $row["timer"];
			$revisit = $row["revisit"];
			$color = $row["color"];
			$active = $row["active"];
			$gotosite = $row["gotosite"];
			$unic_ip = $row["unic_ip"];
			$new_users = $row["new_users"];
			$no_ref = $row["no_ref"];
			$sex_adv = $row["sex_adv"];
			$to_ref = $row["to_ref"];
		}

		$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
		if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
			$row_bl = mysql_fetch_array($sql_bl);
			echo 'Сайт '.$row_bl["domen"].' заблокирован и занесен в черный список проекта '.strtoupper($_SERVER["HTTP_HOST"]).' Причина: '.$row_bl["cause"].'';
			exit();
		}elseif($url==false | $url=="http://" | $url=="https://") {
			echo 'Не указана ссылка на сайт!';
			exit();
		}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
			echo 'Не верно указана ссылка на сайт!';
			exit();
		}elseif(is_url($url)!="true") {
			echo 'Не верно указана ссылка на сайт!';
			exit("");
		}elseif($title==false) {
			echo 'Не заполнено поле заголовок письма.';
			exit("");
		}elseif($description==false) {
			echo 'Не заполнено поле содержание письма.';
			exit("");
		}elseif($question==false) {
			echo 'Не заполнено поле контрольный вопрос.';
			exit("");
		}elseif($answer_t==false) {
			echo 'Не заполнено поле вариант ответа (правильный).';
			exit("");
		}elseif($answer_f_1==false) {
			echo 'Не заполнено поле вариант ответа (ложный).';
			exit("");
		}elseif($answer_f_2==false) {
			echo 'Не заполнено поле вариант ответа (ложный).';
			exit("");
		}elseif($timer==false) {
			echo 'Время просмотра должно быть в пределах от '.$mails_timer_ot.' сек. до '.$mails_timer_do.' сек.';
			exit("");
		}else{
			if($status == 0 | $status == 3) {
				if($nolimit>0 && $mails_nolimit_status==1) {
					$plan = 1000000;
					$timer = $mails_nolimit_timer;
					$gotosite = 1;
					$color = 1;
					$active = 0;
					$revisit = 2;
					$unic_ip = 0;
					$new_users = 0;
					$no_ref = 0;
					$sex_adv = 0;
					$to_ref = 0;
					$nolimitdate = (time() + 30*24*60*60);
				}else{
					$nolimitdate = 0;
				}

				mysql_query("UPDATE `tb_ads_mails` SET 
					`wm_check`='$wm_check',`title`='$title',`url`='$url',`description`='$description',`question`='$question',`answer_t`='$answer_t',
					`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',`plan`='$plan',`totals`='$plan',`members`='0',`outside`='0',
					`color`='$color',`active`='$active',`gotosite`='$gotosite',`geo_targ`='$country',`revisit`='$revisit',
					`timer`='$timer',`nolimit`='$nolimitdate',`new_users`='$new_users',`unic_ip`='$unic_ip',`no_ref`='$no_ref',
					`sex_adv`='$sex_adv',`to_ref`='$to_ref',`ip`='$laip' 
				WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				exit("OK");

			}elseif($status == 1 | $status == 2) {

				mysql_query("UPDATE `tb_ads_mails` SET 
					`wm_check`='$wm_check',`title`='$title',`url`='$url',`description`='$description',`question`='$question',`answer_t`='$answer_t',
					`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',`geo_targ`='$country',`ip`='$laip' 
				WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				exit("OK");

			}else{
				exit("ERROR");
			}
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$nolimit = $row["nolimit"];

		$plan = (isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiarez($_POST["plan"]))))) ? abs(intval(limpiarez($_POST["plan"]))) : false;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_hit' AND `howmany`='1'");
		$mails_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_min_hits' AND `howmany`='1'");
		$mails_min_hits = number_format(mysql_result($sql,0,0), 0, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_color' AND `howmany`='1'");
		$mails_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_active' AND `howmany`='1'");
		$mails_cena_active = number_format(mysql_result($sql,0,0), 4, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_ot' AND `howmany`='1'");
		$mails_timer_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_do' AND `howmany`='1'");
		$mails_timer_do = number_format(mysql_result($sql,0,0), 0, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_timer' AND `howmany`='1'");
		$mails_cena_timer = number_format(mysql_result($sql,0,0), 4, ".", "");

		for($i=1; $i<=2; $i++) {
			$mails_cena_revisit[0] = 0;
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_revisit' AND `howmany`='$i'");
			$mails_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
		}

		for($i=1; $i<=2; $i++) {
			$mails_cena_unic_ip[0] = 0;
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_unic_ip' AND `howmany`='$i'");
			$mails_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
		}

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_gotosite' AND `howmany`='1'");
		$mails_cena_gotosite = number_format(mysql_result($sql,0,0), 4, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_status' AND `howmany`='1'");
		$mails_nolimit_status = number_format(mysql_result($sql,0,0), 0, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_cena' AND `howmany`='1'");
		$mails_nolimit_cena = number_format(mysql_result($sql,0,0), 2, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_timer' AND `howmany`='1'");
		$mails_nolimit_timer = number_format(mysql_result($sql,0,0), 0, ".", "");

		if($nolimit>0 && $mails_nolimit_status==0) {
			exit("Функция безлимитная реклама на данный момент не доступна! Пополнение баланса не возможно, попробуйте позже.");
		}

		if($row["nolimit"]!=0) {
			if($plan == 30) {
				$cena = $mails_nolimit_cena;
				$nolimitdate = 30*24*60*60;
			}else{
				exit("ERROR2");
			}
		}else{
			if($plan<$mails_min_hits) {
				exit("ERROR1");
			}else{
				$cena = $plan * ( $mails_cena_hit + ($mails_cena_timer * ($row["timer"] - $mails_timer_ot)) + ($row["active"] * $mails_cena_active) + ($row["color"] * $mails_cena_color) + $mails_cena_revisit[$row["revisit"]] + $mails_cena_unic_ip[$row["unic_ip"]] + ($row["gotosite"] * $mails_cena_gotosite) );
			}
		}
		$cena = $cena * (100-$cab_skidka)/100;
		$money_pay = number_format($cena, 2, ".", "");

		if($money_user_rb<$money_pay) {
			exit("ERROR2");
		}else{

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
			$reit_rek = mysql_result($sql,0,0);
			$reit_add_1 = floor($money_pay/10) * $reit_rek;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
			$reit_ref_rek = mysql_result($sql,0,0);
			$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;


			if($status=="0") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				//mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				if($row["nolimit"]!=0) {
					mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `method_pay`='10',`date`='".time()."',`nolimit`='".(time() + $nolimitdate)."',`plan`='$plan', `totals`='$plan', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `method_pay`='10',`date`='".time()."',`plan`='$plan', `totals`='$plan', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса писем ID:$id','Списано','rashod')") or die(mysql_error());

				@stat_pay('mails', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 1);

				exit("OK");

			}elseif($status=="1" | $status=="2") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				//mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				if($row["nolimit"]!=0) {
					mysql_query("UPDATE `tb_ads_mails` SET `method_pay`='10', `date`='".time()."', `nolimit`=`nolimit`+'$nolimitdate', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_mails` SET `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса писем ID:$id','Списано','rashod')") or die(mysql_error());

				@stat_pay('mails', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 1);

				exit("OK");

			}elseif($status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				//mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				if($row["nolimit"]!=0) {
					mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `wm_sent`='0', `method_pay`='10',`nolimit`='".(time() + $nolimitdate)."',`plan`='$plan', `totals`='$plan', `members`='0', `outside`='0',`ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `wm_sent`='0', `method_pay`='10',`plan`='$plan', `totals`='$plan', `members`='0', `outside`='0',`ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса писем ID:$id','Списано','rashod')") or die(mysql_error());

				@stat_pay('mails', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 1);

				exit("OK");


			}else{
				exit("Ошибка! Не удалось обработать запрос!");
			}
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}
}

?>