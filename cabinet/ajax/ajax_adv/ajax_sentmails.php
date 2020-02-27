<?php
if(!DEFINED("SENTMAILS_AJAX")) {die ("Hacking attempt!");}

if($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("0");
		}elseif($status == 1) {
			mysql_query("UPDATE `tb_ads_emails` SET `status`='2' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-play" title="Запустить рассылку" onClick="play_pause('.$row["id"].', \'sentmails\');"></span>';
			exit();
		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_emails` SET `status`='1' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-pause" title="Приостановить рассылку" onClick="play_pause('.$row["id"].', \'sentmails\');"></span>';
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
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}elseif($status == 1 | $status == 2) {
			exit("Удаление возможно только после завершения рассылки.");
		}elseif($status == 3) {
			mysql_query("DELETE FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}else{
			exit("ERROR");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "save") {
	$subject = (isset($_POST["subject"])) ? limitatexto(limpiarez($_POST["subject"]), 255) : false;
	$message = (isset($_POST["message"])) ? limitatexto(limpiarez($_POST["message"]), 1024) : false;

	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($subject==false) {
			echo 'Не заполнено поле тема сообщения.';
			exit("");
		}elseif($message==false) {
			echo 'Не заполнено поле текст сообщения.';
			exit("");
		}else{
			mysql_query("UPDATE `tb_ads_emails` SET `subject`='$subject', `message`='$message', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("Счётчик этой площадки уже равен 0");
		}else{
			mysql_query("UPDATE `tb_ads_emails` SET `count`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_sent_mails' AND `howmany`='1'");
		$cena_sent_mails = mysql_result($sql,0,0);

		$cena = $cena_sent_mails * (100-$cab_skidka)/100;
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

			if($status=="0" | $status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_emails` SET `status`='1', `method_pay`='10', `count`='0', `sent`='0', `nosent`='0', `last_id`='0', `date`='".time()."', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay','Оплата рассылки на e-mail ID:$id','Списано','rashod')") or die(mysql_error());

				@stat_pay('sent_mails', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
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