<?php
if(!DEFINED("PAY_ROW_AJAX")) {die ("Hacking attempt!");}

if($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id' AND (`status`='0' OR `status`='1') AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_pay_row` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_pay_row();

			exit("OK");

		}elseif($status == 1) {
			exit("Удаление возможно только после окончания показа.");

		}else{
			exit("ERROR");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "save") {
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 60) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$black_url = @getHost($url);

	$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id' AND (`status`='0' OR `status`='1') AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
		if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
			$row_bl = mysql_fetch_assoc($sql_bl);
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
		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
			exit(SFB_YANDEX($url));
		}else{
			mysql_query("UPDATE `tb_ads_pay_row` SET  `url`='$url', `description`='$description', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_pay_row();

			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id' AND (`status`='0' OR `status`='1') AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("Счётчик этой площадки уже равен 0");
		}else{
			mysql_query("UPDATE `tb_ads_pay_row` SET `outside`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_pay_row();

			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id' AND `status`='0' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_pay_row' AND `howmany`='1'");
		$cena_pay_row = number_format(mysql_result($sql,0,0), 2, ".", "");

		$cena = ($cena_pay_row * (100-$cab_skidka)/100);
		$money_pay = number_format($cena, 2, ".", "");

		if($money_user_rb<$money_pay) {
			exit("ERROR");
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
			$reit_rek = mysql_result($sql,0,0);
			$reit_add_1 = floor($money_pay/10) * $reit_rek;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
			$reit_ref_rek = mysql_result($sql,0,0);
			$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

			if($status=="0") {
				if($my_referer_1 != false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_pay_row` SET `status`='1', `method_pay`='10', `date`='".time()."', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Оплата рекламы (платная строка, ID:$id)','Списано','rashod')") or die(mysql_error());

				stat_pay('pay_row', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				ActionRef(number_format($money_pay,2,".",""), $username);
				cache_pay_row();

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