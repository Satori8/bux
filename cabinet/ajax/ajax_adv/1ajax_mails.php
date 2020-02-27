<?php
if(!DEFINED("MAILS_AJAX")) {die ("Hacking attempt!");}

mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

if($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("0");
		}elseif($status == 1) {
			mysql_query("UPDATE `tb_ads_mails` SET `status`='2' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
			exit();
		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_mails` SET `status`='1' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
			exit();
		}elseif($status == 3) {
			exit("0");
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
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 255) : false;

	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 5000) : false;
	if (get_magic_quotes_gpc()) {$description = stripslashes($description);}

	$question = (isset($_POST["question"])) ? limitatexto(limpiarez($_POST["question"]), 255) : false;
	$answer_t = (isset($_POST["answer_t"])) ? limitatexto(limpiarez($_POST["answer_t"]), 255) : false;
	$answer_f_1 = (isset($_POST["answer_f_1"])) ? limitatexto(limpiarez($_POST["answer_f_1"]), 255) : false;
	$answer_f_2 = (isset($_POST["answer_f_2"])) ? limitatexto(limpiarez($_POST["answer_f_2"]), 255) : false;

	$tarif = (isset($_POST["tarif"]) && (intval($_POST["tarif"])==1 | intval($_POST["tarif"])==2 | intval($_POST["tarif"])==3)) ? intval(limpiarez($_POST["tarif"])) : "2";
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(limpiarez($_POST["color"])) : "0";
	$active = (isset($_POST["active"]) && (intval($_POST["active"])==0 | intval($_POST["active"])==1)) ? intval(limpiarez($_POST["active"])) : "0";
	$gotosite = (isset($_POST["gotosite"]) && (intval($_POST["gotosite"])==0 | intval($_POST["gotosite"])==1)) ? intval(limpiarez($_POST["gotosite"])) : "0";
	$mailsre = (isset($_POST["mailsre"]) && (intval($_POST["mailsre"])==0 | intval($_POST["mailsre"])==1)) ? intval(limpiarez($_POST["mailsre"])) : "0";
	$black_url = getHost($url);

	$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status==1 | $status==2) {
			$tarif = $row["tarif"];
			$color = $row["color"];
			$active = $row["active"];
			$gotosite = $row["gotosite"];
			$mailsre = $row["mailsre"];
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
		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url) != false) {
			exit(SFB_YANDEX($url));
		}else{
			if($tarif!=1 && $tarif!=2 && $tarif!=3) {
				echo 'Не верно указан тариф.';
				exit("");
			}else{
				mysql_query("UPDATE `tb_ads_mails` SET `title`='$title',`url`='$url',`description`='$description',`question`='$question',`answer_t`='$answer_t',`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',`tarif`='$tarif',`color`='$color',`active`='$active',`gotosite`='$gotosite',`mailsre`='$mailsre',`ip`='$laip' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				exit("OK");
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

		$plan = (isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiarez($_POST["plan"]))))) ? abs(intval(limpiarez($_POST["plan"]))) : false;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='".$row["tarif"]."'");
		$cena_mails = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
		$cena_mails_color = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
		$cena_mails_active = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
		$cena_mails_gotosite = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_mails' AND `howmany`='1'");
		$nacenka_mails = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_mails' AND `howmany`='1'");
		$min_mails = mysql_result($sql,0,0);

		$cena = $plan * ($cena_mails + ($row["color"] * $cena_mails_color) + ($row["active"] * $cena_mails_active) + ($row["gotosite"] * $cena_mails_gotosite)) * ($nacenka_mails+100)/100;
		$cena = $cena * (100-$cab_skidka)/100;
		$money_pay = number_format($cena, 2, ".", "");

		if($plan<$min_mails) {
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

			if($status=="0" | $status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `method_pay`='10',`date`='".time()."',`plan`='$plan', `totals`='$plan', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса писем ID:$id','Списано','rashod')") or die(mysql_error());

				@stat_pay('mails', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 1);
				ActionRef(number_format($money_pay,2,".",""), $username);

				exit("OK");

			}elseif($status=="1" | $status=="2") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				mysql_query("UPDATE `tb_ads_mails` SET `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса писем ID:$id','Списано','rashod')") or die(mysql_error());

				@stat_pay('mails', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 1);
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