<?php
if(!DEFINED("BANNERS_AJAX")) {die ("Hacking attempt!");}
require(DOC_ROOT."/advertise/func_load_banners.php");

if($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$date_end = $row["date_end"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_banners();
			exit("OK");

		}elseif($status == 1 && $date_end < time()) {
			mysql_query("DELETE FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_banners();
			exit("OK");

		}elseif($status == 1 && $date_end > time()) {
			exit("Удаление возможно только после окончания показа.");

		}elseif($status == 3) {
			mysql_query("DELETE FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_banners();
			exit("OK");

		}else{
			exit("ERROR");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "save") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$urlbanner = (isset($_POST["urlbanner"])) ? limitatexto(limpiarez($_POST["urlbanner"]), 300) : false;
	$black_url = getHost($url);
	$black_url_ban = getHost($urlbanner);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
	$sql_bl_ban = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url_ban'");

	$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$size_banner = $row["type"];

		$wh = explode("x", $size_banner);
		$w = $wh["0"];
		$h = $wh["1"];

		if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
			$row = mysql_fetch_array($sql_bl);
			echo 'Сайт '.$row["domen"].' заблокирован и занесен в черный список проекта '.strtoupper($_SERVER["HTTP_HOST"]).'! Причина: '.$row["cause"].'';
			exit();
		}elseif(mysql_num_rows($sql_bl_ban)>0 && $black_url_ban!=false) {
			$row_ban = mysql_fetch_array($sql_bl_ban);
			echo 'Сайт '.$row_ban["domen"].' заблокирован и занесен в черный список проекта '.strtoupper($_SERVER["HTTP_HOST"]).'! Причина: '.$row_ban["cause"].'';
			exit();
		}elseif($url==false | $url=="http://" | $url=="https://") {
			echo 'Не указана ссылка на сайт!';
			exit();
		}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
			echo 'Не верно указана ссылка на сайт!';
			exit();
		}elseif($urlbanner==false | $urlbanner=="http://" | $urlbanner=="https://") {
			echo 'Не указана ссылка на баннер!';
			exit();
		}elseif((substr($urlbanner, 0, 7) != "http://" && substr($urlbanner, 0, 8) != "https://")) {
			echo 'Не верно указана ссылка на баннер!';
			exit();
		}elseif(is_url($url)!="true") {
			echo is_url($url);
			exit();
		}elseif(is_url_img($urlbanner, "cabinet")!="true") {
			echo is_url_img($urlbanner, "cabinet");
			exit();
		}elseif(is_img_size($w, $h, $urlbanner, "cabinet")!="true") {
			echo is_img_size($w, $h, $urlbanner, "cabinet");
			exit();
		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
			exit(SFB_YANDEX($url));
		}elseif(@getHost($urlbanner)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($urlbanner) != false) {
			exit(SFB_YANDEX($urlbanner));
		}elseif(img_get_save_cab($urlbanner)!="true") {
			echo img_get_save_cab($urlbanner);
			exit();
		}else{
			$urlbanner_orig = $urlbanner;
			$urlbanner_load = img_get_save($urlbanner, 1);

			mysql_query("UPDATE `tb_ads_banner` SET `url`='$url', `urlbanner`='$urlbanner', `urlbanner_load`='$urlbanner_load', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_banners();
			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$date_end = $row["date_end"];

		if($status == 0) {
			exit("Счётчик этой площадки уже равен 0");
		}else{
			mysql_query("UPDATE `tb_ads_banner` SET `members`='0', `outside`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_banners();
			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$date_end = $row["date_end"];
		$size_banner = $row["type"];
		$plan = (isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiarez($_POST["plan"]))))) ? abs(intval(limpiarez($_POST["plan"]))) : false;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner".$size_banner."' AND `howmany`='1'");
		$cena_banner = mysql_result($sql,0,0);

		$cena = $plan * $cena_banner * (100-$cab_skidka)/100;
		$money_pay = number_format($cena, 2, ".", "");

		if($plan<1) {
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

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_banner` SET `status`='1', `method_pay`='10', `plan`='$plan', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса баннера $size_banner ID:$id','Списано','rashod')") or die(mysql_error());

				mysql_query("UPDATE `tb_ads_banner` SET `plan`=(`date_end`-`date`)/86400  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				@stat_pay('banners', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				ActionRef(number_format($money_pay,2,".",""), $username);
				cache_banners();

				exit("OK");

			}elseif($status=="1" && $date_end <= time()) {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_banner` SET `status`='1', `method_pay`='10', `plan`=`plan`+'$plan', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса баннера $size_banner ID:$id','Списано','rashod')") or die(mysql_error());

				mysql_query("UPDATE `tb_ads_banner` SET `plan`=(`date_end`-`date`)/86400  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				@stat_pay('banners', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				ActionRef(number_format($money_pay,2,".",""), $username);
				cache_banners();
				

				exit("OK");

			}elseif($status=="1" && $date_end > time()) {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_banner` SET `status`='1', `method_pay`='10', `plan`=`plan`+'$plan', `date_end`=`date_end`+'".($plan*24*60*60)."', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Пополнение баланса баннера $size_banner ID:$id','Списано','rashod')") or die(mysql_error());

				mysql_query("UPDATE `tb_ads_banner` SET `plan`=(`date_end`-`date`)/86400  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				@stat_pay('banners', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				ActionRef(number_format($money_pay,2,".",""), $username);
				cache_banners();

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