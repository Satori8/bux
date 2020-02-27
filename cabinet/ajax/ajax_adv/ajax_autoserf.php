<?php
if(!DEFINED("AUTOSERF_AJAX")) {die ("Hacking attempt!");}

mysql_query("UPDATE `tb_ads_auto` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

if($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$limits_now = $row["limits_now"];

		if($status == 0 | $status == 3) {
			exit("0");
		}elseif($status == 1) {
			if($limits_now<1) {
				exit("NOLIMIT");
			}else{
				mysql_query("UPDATE `tb_ads_auto` SET `status`='2' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'autoserf\');"></span>';
				exit();
			}
		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_auto` SET `status`='1' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			if($limits_now<1) {
				exit("NOLIMIT");
			}else{
				echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'autoserf\');"></span>';
				exit();
			}
		}else{
			exit("ERROR");
		}
	}else{
		exit("ERRORNOID");
	}

}elseif($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}elseif($status == 1 | $status == 2) {
			exit("Удаление возможно только после окончания показа.");
		}elseif($status == 3 && $row["totals"]>0) {
			exit("Удаление возможно только после окончания показа.");
		}elseif($status == 3 && $row["totals"]<1) {
			mysql_query("DELETE FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}else{
			exit("ERROR");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "save") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 80) : false;
	$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,3}$|", limpiarez($_POST["timer"])) ) ? intval(limpiarez($_POST["timer"])) : false;
	$limits = ( isset($_POST["limits"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["limits"])) ) ? intval(limpiarez($_POST["limits"])) : false;
	$black_url = @getHost($url);
	
	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
	$geo_cod_arr = array(
		1  => 'RU', 2  => 'UA', 3  => 'BY', 4  => 'MD', 
		5  => 'KZ', 6  => 'AM', 7  => 'UZ', 8  => 'LV', 
		9  => 'DE', 10 => 'GE',	11 => 'LT', 12 => 'FR', 
		13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 
		17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 
		21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 
		25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 
		29 => 'IR', 30 => 'GR', 31 => 'TR', 32 => 'PL', 
		33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO',
	);
	$geo_name_arr = array(
		1  => 'Россия', 	2  => 'Украина', 	3  => 'Белорусия', 	4  => 'Молдавия',
 		5  => 'Казахстан', 	6  => 'Армения', 	7  => 'Узбекистан', 	8  => 'Латвия',
		9  => 'Германия', 	10 => 'Грузия', 	11 => 'Литва', 		12 => 'Франция', 
		13 => 'Азербайджан', 	14 => 'США', 		15 => 'Вьетнам', 	16 => 'Португалия',
		17 => 'Англия', 	18 => 'Бельгия', 	19 => 'Испания', 	20 => 'Китай',
		21 => 'Таджикистан', 	22 => 'Эстония', 	23 => 'Италия', 	24 => 'Киргизия',
		25 => 'Израиль', 	26 => 'Канада', 	27 => 'Туркменистан', 	28 => 'Болгария',
		29 => 'Иран', 		30 => 'Греция', 	31 => 'Турция', 	32 => 'Польша',
		33 => 'Финляндия', 	34 => 'Египет', 	35 => 'Швеция', 	36 => 'Румыния',
	);
	if(is_array($country)) {
		foreach($country as $key => $val) {
			if(array_search($val, $geo_cod_arr)) {
				$id_country = array_search($val, $geo_cod_arr);
				$country_arr[] = $val;
				$country_arr_ru[] = $geo_name_arr[$id_country];
			}
		}
	}
	$country = isset($country_arr) ? trim(strtoupper(implode(', ', $country_arr))) : false;

	$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

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
		}elseif($description==false) {
			echo 'Не заполнено поле Описание ссылки.';
			exit("");
		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url) != false) {
			exit(SFB_YANDEX($url));
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
			$timer_aserf_ot = mysql_result($sql,0,0);

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
			$timer_aserf_do = mysql_result($sql,0,0);

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_hits_aserf' AND `howmany`='1'");
			$min_hits_aserf = mysql_result($sql,0,0);

			if($limits<0) {
				exit("Ограничение количества показов в сутки должно быть положительным");
			}elseif($limits!=0 && $limits<$min_hits_aserf) {
				exit("Ограничение количества показов в сутки должно быть не менее $min_hits_aserf либо 0-без ограничений");
			}elseif($status == 0 | $status == 3) {
				if($timer<$timer_aserf_ot | $timer>$timer_aserf_do) {
					exit("Время просмотра должно быть в пределах от $timer_aserf_ot до $timer_aserf_do сек.");
				}else{
					if($limits==0) $limits = $row["plan"];

					mysql_query("UPDATE `tb_ads_auto` SET `geo_targ`='$country',`url`='$url', `description`='$description', `timer`='$timer', `limits`='$limits', `limits_now`='$limits', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
					exit("OK");
				}

			}elseif($status == 1 | $status == 2) {
				if($timer<$timer_aserf_ot) {
					$timer = $timer_aserf_ot;
				}elseif($timer>$timer_aserf_do) {
					$timer = $timer_aserf_do;
				}
				if($limits==0) $limits = $row["plan"];

				mysql_query("UPDATE `tb_ads_auto` SET `geo_targ`='$country',`url`='$url', `description`='$description', `timer`='$timer', `limits`='$limits', `limits_now`='$limits', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				exit("OK");

			}else{
				exit("ERROR");
			}
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("Счётчик этой площадки уже равен 0");
		}else{
			mysql_query("UPDATE `tb_ads_auto` SET `outside`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$limits = $row["limits"];
		$plan = (isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiarez($_POST["plan"]))))) ? abs(intval(limpiarez($_POST["plan"]))) : false;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
		$cena_hits_aserf = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_hits_aserf' AND `howmany`='1'");
		$nacenka_hits_aserf = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_hits_aserf' AND `howmany`='1'");
		$min_hits_aserf = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
		$timer_aserf_ot = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
		$timer_aserf_do = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_timer_aserf' AND `howmany`='1'");
		$cena_timer_aserf = mysql_result($sql,0,0);

		$cena = $plan * ( (($row["timer"] - $timer_aserf_ot) * $cena_timer_aserf + $cena_hits_aserf) * (100+$nacenka_hits_aserf)/100 );
		$cena = ($cena* (100-$cab_skidka)/100);
		$money_pay = number_format($cena, 2, ".", "");

		if($plan<$min_hits_aserf) {
			exit("ERROR1");
		}elseif($money_user_rb<$money_pay) {
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

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_auto` SET `status`='1', `method_pay`='10', `date`='".time()."', `plan`='$plan', `totals`='$plan', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса ссылки в авто-серфинге ID:$id','Списано','rashod')") or die(mysql_error());

				if($limits==0) mysql_query("UPDATE `tb_ads_auto` SET `limits`=`plan`, `limits_now`=`plan` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				@stat_pay('autoserf', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 2);
				ActionRef(number_format($money_pay,2,".",""), $username);

				exit("OK");

			}elseif($status=="1" | $status=="2") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_auto` SET `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса ссылки в авто-серфинге ID:$id','Списано','rashod')") or die(mysql_error());

				if($limits==0) mysql_query("UPDATE `tb_ads_auto` SET `limits`=`plan` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				@stat_pay('autoserf', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 2);
				ActionRef(number_format($money_pay,2,".",""), $username);

				exit("OK");

			}elseif($status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_auto` SET `status`='1', `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса ссылки в авто-серфинге ID:$id','Списано','rashod')") or die(mysql_error());

				if($limits==0) mysql_query("UPDATE `tb_ads_auto` SET `limits`=`plan` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				@stat_pay('autoserf', $money_pay, 2);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay);
				ActionRef(number_format($money_pay,2,".",""), $username);

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