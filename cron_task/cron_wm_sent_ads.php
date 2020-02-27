<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/auto_pay_req/wmxml.inc.php");

$domen = "https://lilacbux.com";
$subject = "<b>Уведомление об окончании рекламной компании</b>";
$message = "Здравствуйте!\n$subject:\n---------------------------\n";

$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		if($row["type_serf"]==2 | $row["type_serf"]==4) {
			$message = "Здравствуйте!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "Ваша ссылка в баннерном серфинге успешно завершила свой показ на сайте $domen\n";
			$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
			$message.= "URL сайта: ".$row["url"]."\n";
			$message.= "URL баннера: ".$row["description"]."\n\n";
			$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=dlink \n\n";
			$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=dlinks \n\n";
		}else{
			$message = "Здравствуйте!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "Ваша динамическая ссылка успешно завершила свой показ на сайте $domen\n";
			$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
			$message.= "URL сайта: ".$row["url"]."\n";
			$message.= "Заголовок: ".$row["title"]."\n";
			$message.= "Описание: ".$row["description"]."\n\n";
			$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=dlink \n\n";
			$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=dlinks \n\n";
		}

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_dlink` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		if($row["type_serf"]==2 | $row["type_serf"]==4) {
			$message = "Здравствуйте!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "Ваша ссылка в баннерном серфинге успешно завершила свой показ на сайте $domen\n";
			$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
			$message.= "URL сайта: ".$row["url"]."\n";
			$message.= "URL баннера: ".$row["description"]."\n\n";
			$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=youtube \n\n";
			$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=youtube \n\n";
		}else{
			$message = "Здравствуйте!\n$subject:\n";
			$message.= "---------------------------\n";
			$message.= "Ваш видеоролик YouTube успешно завершил свой показ на сайте $domen\n";
			$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
			$message.= "URL сайта: ".$row["url"]."\n";
			$message.= "Заголовок: ".$row["title"]."\n";
			$message.= "Описание: ".$row["description"]."\n\n";
			$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=youtube \n\n";
			$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=youtube \n\n";
		}

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_youtube` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_psevdo` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша псевдо-динамическая ссылка успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=psevdo \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=psevdo \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_psevdo` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша ссылка в авто-серфинге успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=autoserf \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=auto_serf \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_auto` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}


$sql = mysql_query("SELECT * FROM `tb_ads_slink` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша статическая ссылка успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=statlink \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=stat_links \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_slink` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}


$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша ссылка в контекстной рекламе успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Заголовок: ".$row["title"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=kontext \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=kontext \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_kontext` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваш баннер успешно завершил свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "URL баннера: ".$row["urlbanner"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=banners \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=banners \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_banner` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_txt` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваше текстовое объявление успешно завершило свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=txtob \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=txt_ob \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_txt` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_frm` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша ссылка ссылка во фрейме успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=frmlink \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=frm_links \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_frm` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}


$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваше рекламное письмо успешно завершило свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=mails \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=rek_mails \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_mails` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша рассылка на e-mail пользователям сайта $domen успешно завершена.\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "Тема сообщения: ".$row["subject"]."\n";
		$message.= "Текст сообщения: ".$row["message"]."\n\n";
		$message.= "Вы можете заказать новую рассылку на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=sent_emails \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_emails` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша ссылка в каталоге успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Заголовок: ".$row["title"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=catalog \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=catalog \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_catalog` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_beg_stroka` WHERE `status`='3' AND `wm_sent`='0' AND `wmid`!='' ORDER BY `id` ASC LIMIT 5");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		$message = "Здравствуйте!\n$subject:\n";
		$message.= "---------------------------\n";
		$message.= "Ваша ссылка в бегущей строке успешно завершила свой показ на сайте $domen\n";
		$message.= "ID рекламной плащадки: <b>".$row["id"]."</b>\n";
		$message.= "URL сайта: ".$row["url"]."\n";
		$message.= "Описание: ".$row["description"]."\n\n";
		$message.= "Если Вы зарегистрированный пользователь и размещали рекламу из своего аккаунта, то Вы можете продлить свою рекламную компанию через кабинет рекламодателя, для этого перейдите по ссылке - $domen/cabinet_ads?ads=begstroka \n\n";
		$message.= "Если Вы незарегистрированный пользователь, то Вы можете заказать новую рекламу на странице заказа, для этого перейдите по ссылке - $domen/advertise.php?ads=beg_stroka \n\n";

		$message.= "Благодарим Вас за использование нашего сервиса.\n";
		$message.= "---------------------------\n\n";
		$message.= "<i>Это автоматическое информационное сообщение, отвечать на него не обязательно.</i>\n";
		$message.= "<i>С уважением, Администрация сайта</i> $domen";

		if(preg_match("|^[\d]{12}$|", trim($row["wmid"]))) {
			$res_wmt = _WMXML6($row["wmid"], $message, $subject);

			if($res_wmt["retval"]==0) {
				mysql_query("UPDATE `tb_ads_beg_stroka` SET `wm_sent`='1' WHERE `id`='".$row["id"]."'") or die(mysql_error());
			}
		}
		unset($message);
	}
}

@mysql_close();
?>