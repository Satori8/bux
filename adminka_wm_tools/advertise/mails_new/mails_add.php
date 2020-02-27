<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Добавление оплачиваемого письма</b></h3>';

mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date_end`='".time()."' WHERE `status`>'0' AND (`totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_hit' AND `howmany`='1'");
$mails_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nacenka' AND `howmany`='1'");
$mails_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

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

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_uplist' AND `howmany`='1'");
$mails_cena_uplist = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_status' AND `howmany`='1'");
$mails_nolimit_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_cena' AND `howmany`='1'");
$mails_nolimit_cena = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_timer' AND `howmany`='1'");
$mails_nolimit_timer = number_format(mysql_result($sql,0,0), 0, ".", "");

?><script type="text/javascript">
function ShowHideBlock(id) {
	if (gebi("adv-title"+id).className == 'adv-title-open') {
		gebi("adv-title"+id).className = 'adv-title-close';
	} else {
		gebi("adv-title"+id).className = 'adv-title-open';
	}
	$("#adv-block"+id).slideToggle("fast");
}

function SetChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script><?php

function limpiarez($mensaje){
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace("  ", " ", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));

	$mensaje = htmlspecialchars(trim($mensaje), ENT_QUOTES, "CP1251");
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);
	return $mensaje;
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

	$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_array($sql_id);
		$plan = $row["plan"];

		mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
        
		echo '<span id="info-msg" class="msg-ok">Реклама успешно размещена!</span>';
	}else{
		echo '<span id="info-msg" class="msg-error">Ошибка! Реклама не найдена.</span>';
	}

	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'")\', 2000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'"></noscript>';
	exit();
}

if(count($_POST)>0) {
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

	$method_pay = 0;
	$laip = getRealIP();
	$black_url = getHost($url);

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

	if($nolimit>0 && $mails_nolimit_status==1) {
		$plan = 1000000;
		$timer = $mails_nolimit_timer;
		$gotosite = 1;
		$up_list = 1;
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


	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
	if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row = mysql_fetch_assoc($sql_bl);
		echo '<span class="msg-error">Сайт <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> заблокирован и занесен в черный список проекта '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>Причина: '.$row["cause"].'</span>';
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">Не указана ссылка на сайт!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">Не верно указана ссылка на сайт!</span>';
	}elseif(is_url($url)!="true") {
		echo is_url($url);
	}elseif($title==false) {
		echo '<span class="msg-error">Не заполнено поле Заголовок письма.</span>';
	}elseif($description==false) {
		echo '<span class="msg-error">Не заполнено поле Содержание письма.</span>';
	}elseif($question==false) {
		echo '<span class="msg-error">Не заполнено поле Контрольный вопрос.</span>';
	}elseif($answer_t==false) {
		echo '<span class="msg-error">Не заполнено поле вариант ответа (правильный).</span>';
	}elseif($answer_f_1==false) {
		echo '<span class="msg-error">Не заполнено поле вариант ответа (ложный).</span>';
	}elseif($answer_f_2==false) {
		echo '<span class="msg-error">Не заполнено поле вариант ответа (ложный).</span>';
	}elseif($timer==false) {
		echo '<span class="msg-error">Время просмотра должно быть в пределах от '.$mails_timer_ot.' сек. до '.$mails_timer_do.' сек.</span>';
	}elseif($plan<$mails_min_hits) {
		echo '<span class="msg-error">Минимальный заказ - '.$mails_min_hits.' писем.</span>';
	}else{
		$color_to[0] = "Нет";
		$color_to[1] = "Да";

		$up_list_to[0] = "Нет";
		$up_list_to[1] = "Да";

		$gotosite_to[0] = "Нет";
		$gotosite_to[1] = "Да";

		$content_to[0] = "Нет";
		$content_to[1] = "Да";

		$new_users_to[0] = "Все пользователи проекта";
		$new_users_to[1] = "Да, до 7 дней с момента регистрации";

		$unic_ip_to[0] = "Нет";
		$unic_ip_to[1] = "Да, 100% совпадение";
		$unic_ip_to[2] = "Да, усиленный по маске до 2 чисел (255.255.X.X)";

		$revisit_to[0] = "Доставляется всем каждые 24 часа";
		$revisit_to[1] = "Доставляется всем каждые 48 часов";
		$revisit_to[2] = "Доставляется всем 1 раз в месяц";

		$no_ref_to[0] = "Все пользователи проекта";
		$no_ref_to[1] = "Да";

		$sex_adv_to[0] = "Все пользователи проекта";
		$sex_adv_to[1] = "Только мужчины";
		$sex_adv_to[2] = "Только женщины";

		$to_ref_to[0] = "Все пользователи проекта";
		$to_ref_to[1] = "Рефералам 1-го уровня";
		$to_ref_to[2] = "Рефералам всех уровней";

		$active_to[0] = "Нет";
		$active_to[1] = "Да";


		$precio = $nolimit>0 ? $mails_nolimit_cena : ( ($up_list * $mails_cena_uplist) + $plan * ($mails_cena_hit + ($mails_cena_timer * ($timer-$mails_timer_ot)) + ($active * $mails_cena_active) + ($color * $mails_cena_color) + $mails_cena_revisit[$revisit] + $mails_cena_unic_ip[$unic_ip] + ($gotosite * $mails_cena_gotosite)) );
		$summa = number_format($precio,2,".","");

	        mysql_query("DELETE FROM `tb_ads_mails` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+1 WHERE `id`='1'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			$id_zakaz = mysql_result($sql_check,0,0);
			$date_up = (isset($up_list) && $up_list==1) ? time() : $id_zakaz;

			mysql_query("UPDATE `tb_ads_mails` SET 
				`wm_check`='$wm_check', `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`date`='".time()."',`date_up`='".time()."',`wmid`='$wmid_user',
				`username`='$username',`title`='$title',`url`='$url',`description`='$description',`question`='$question',
				`answer_t`='$answer_t',`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',`plan`='$plan',`totals`='$plan',
				`color`='$color',`active`='$active',`gotosite`='$gotosite',`up_list`='$up_list',`geo_targ`='$country',
				`revisit`='$revisit',`timer`='$timer',`nolimit`='$nolimitdate',`new_users`='$new_users',`unic_ip`='$unic_ip',
				`no_ref`='$no_ref',`sex_adv`='$sex_adv',`to_ref`='$to_ref',`money`='$summa',`ip`='$laip' 
			WHERE `id`='$id_zakaz' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_mails` (`status`,`wm_check`,`session_ident`,`merch_tran_id`,`method_pay`,`date`,`wmid`,`username`,`title`,`url`,`description`,`question`,`answer_t`,`answer_f_1`,`answer_f_2`,`plan`,`totals`,`color`,`active`,`gotosite`,`up_list`,`geo_targ`,`revisit`,`timer`,`nolimit`,`new_users`,`unic_ip`,`no_ref`,`sex_adv`,`to_ref`,`money`,`ip`) 
			VALUES('0','$wm_check','".session_id()."','$merch_tran_id','$method_pay','".time()."','$wmid_user','$username','$title','$url','$description','$question','$answer_t','$answer_f_1','$answer_f_2','$plan','$plan','$color','$active','$gotosite','$up_list','$country','$revisit','$timer','$nolimitdate','$new_users','$unic_ip','$no_ref','$sex_adv','$to_ref','$summa','$laip')") or die(mysql_error());

			$id_zakaz = mysql_result(mysql_query("SELECT LAST_INSERT_ID() FROM `tb_ads_mails`"),0,0);
			$date_up = (isset($up_list) && $up_list==1) ? time() : $id_zakaz;

			mysql_query("UPDATE `tb_ads_mails` SET `date_up`='$id_zakaz' WHERE `id`='$id_zakaz'") or die(mysql_error());
		}

        	$sql_id = mysql_query("SELECT `id`,`description`,`geo_targ` FROM `tb_ads_mails` WHERE `id`='$id_zakaz'") or die(mysql_error());
		if(mysql_num_rows($sql_id)>0) {
			$row_id = mysql_fetch_assoc($sql_id);
		        $id_zakaz = $row_id["id"];
		        $description_to = $row_id["description"];
			$geo_targ = (isset($row_id["geo_targ"]) && trim($row_id["geo_targ"])!=false) ? explode(", ", $row_id["geo_targ"]) : array();

			require_once($_SERVER["DOCUMENT_ROOT"]."/bbcode/bbcode.lib.php");
			$description_to = new bbcode($description_to);
			$description_to = $description_to->get_html();
			$description_to = str_replace("&amp;", "&", $description_to);
		}else{
			exit("ERROR NO ID");
		}
		
		echo '<span class="msg-ok" style="margin-bottom:0px;">Предварительный просмотр</span>';
		echo '<table class="tables" style="margin:0; padding:0;">';
			echo '<tr><td align="left" width="220">Счет № (ID)</td><td align="left">'.number_format($merch_tran_id, 0,".", "").' ('.$id_zakaz.')</td></tr>';
			echo '<tr><td>Заголовок письма</td><td>'.$title.'</td></tr>';
			echo '<tr><td>URL сайта</td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td>Содержание письма</td><td>'.$description_to.'</td></tr>';
			echo '<tr><td>Контрольный вопрос к письму:</td><td>'.$question.'</td></tr>';
			echo '<tr><td>Вариант ответа <span style="color: #009125;">(правильный)</span></td><td>'.$answer_t.'</td></tr>';
			echo '<tr><td>Вариант ответа <span style="color: #FF0000;">(ложный)</span></td><td>'.$answer_f_1.'</td></tr>';
			echo '<tr><td>Вариант ответа <span style="color: #FF0000;">(ложный)</span></td><td>'.$answer_f_2.'</td></tr>';
			echo '<tr><td>Количество просмотров</td><td>'.(($nolimit>0 && $mails_nolimit_status==1) ? "Не ограничено" : "$plan").'</td></tr>';
			echo '<tr><td>Размещение в начале списка</td><td>'.$up_list_to[$up_list].'</td></tr>';
			echo '<tr><td>Выделение цветом</td><td>'.$color_to[$color].'</td></tr>';
			echo '<tr><td>Активное окно</td><td>'.$active_to[$active].'</td></tr>';
			echo '<tr><td>Последующий переход на сайт</td><td>'.$gotosite_to[$gotosite].'</td></tr>';
			echo '<tr><td>Технология доставки письма</td><td>'.$revisit_to[$revisit].'</td></tr>';
			echo '<tr><td>Уникальный IP</td><td>'.$unic_ip_to[$unic_ip].'</td></tr>';
			echo '<tr><td>Показывать только новичкам</td><td>'.$new_users_to[$new_users].'</td></tr>';
			echo '<tr><td>Показ пользователям без реферера  на '.$_SERVER["HTTP_HOST"].'</td><td>'.$no_ref_to[$no_ref].'</td></tr>';
			echo '<tr><td>По половому признаку</td><td>'.$sex_adv_to[$sex_adv].'</td></tr>';
			echo '<tr><td>Показывать только рефералам</td><td>'.$to_ref_to[$to_ref].'</td></tr>';
			echo '<tr>';
				echo '<td align="left">Геотаргетинг</td>';
				echo '<td>';
					if(count($geo_targ)>0) {
						for($i=0; $i<count($geo_targ); $i++){
							echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($geo_targ[$i]).'.gif" alt="'.$geo_name_arr_ru[strtoupper($geo_targ[$i])].'" title="'.$geo_name_arr_ru[strtoupper($geo_targ[$i])].'" align="absmiddle" style="margin:0; padding:0; padding-left:1px;" /> ';
						}
					}else{
						echo 'все страны';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr><td align="center" colspan="2"><form action="" method="post"><input type="hidden" name="id_pay" value="'.$id_zakaz.'"><input type="submit" class="sub-blue160" value="Добавить"></form></td></tr>';
		echo '</table>';

		exit();
	}
}

?><script type="text/javascript" language="JavaScript">
	function SbmFormB() {
		if ( document.forms["formzakaz"].title.value == '' ) {
			alert('Вы не указали заголовок ссылки');
			document.forms["formzakaz"].title.style.background = "#FFDBDB";
			document.forms["formzakaz"].title.focus();
			return false;
		}
		if ( document.forms["formzakaz"].url.value == '' | document.forms["formzakaz"].url.value == 'http://' | document.forms["formzakaz"].url.value == 'https://' ) {
			alert('Вы не указали URL-адрес сайта');
			document.forms["formzakaz"].url.style.background = "#FFDBDB";
			document.forms["formzakaz"].url.focus();
			return false;
		}
		if ( document.forms["formzakaz"].description.value == '' ) {
			alert('Вы не указали краткое описание ссылки');
			document.forms["formzakaz"].description.style.background = "#FFDBDB";
			document.forms["formzakaz"].description.focus();
			return false;
		}
		if ( document.forms["formzakaz"].question.value == '' ) {
			alert('Вы не указали Контрольный вопрос');
			document.forms["formzakaz"].description.style.background = "#FFDBDB";
			document.forms["formzakaz"].description.focus();
			return false;
		}
		if (document.forms['formzakaz'].answer_t.value == '') {
			alert('Вы не указали вариант ответа (правильный)');
			document.forms["formzakaz"].answer_t.style.background = "#FFDBDB";
			document.forms["formzakaz"].answer_t.focus();
			return false;
		}
	        if (document.forms['formzakaz'].answer_f_1.value == '') {
			alert('Вы не указали вариант ответа (ложный)');
			document.forms["formzakaz"].answer_f_1.style.background = "#FFDBDB";
			document.forms["formzakaz"].answer_f_1.focus();
			return false;
		}
	        if (document.forms['formzakaz'].answer_f_2.value == '') {
			alert('Вы не указали вариант ответа (ложный)');
			document.forms["formzakaz"].answer_f_2.style.background = "#FFDBDB";
			document.forms["formzakaz"].answer_f_2.focus();
			return false;
		}
		if ( document.forms["formzakaz"].nolimit.value == '0' && ( document.forms["formzakaz"].plan.value == '' | document.forms["formzakaz"].plan.value < <?=$mails_min_hits;?>) ) {
			alert('Вы не указали количество просмотров, минимум <?=$mails_min_hits;?>');
			document.forms["formzakaz"].plan.style.background = "#FFDBDB";
			document.forms["formzakaz"].plan.focus();
			return false;
		}
		if ( document.forms["formzakaz"].timer.value == '' | document.forms["formzakaz"].timer.value < <?=$mails_timer_ot;?> | document.forms["formzakaz"].timer.value > <?=$mails_timer_do;?> ) {
			alert('Время просмотра должно быть в пределах от <?=$mails_timer_ot;?> сек. до <?=$mails_timer_do;?> сек.');
			document.forms["formzakaz"].timer.style.background = "#FFDBDB";
			document.forms["formzakaz"].timer.focus();
			return false;
		}
		document.forms["formzakaz"].submit();
		return true;
	}

	function PlanChange(){
		var nolimit = $.trim($("#nolimit").val()) ? $.trim($("#nolimit").val()) : 0;

		if(nolimit>0) {
			$("#bl_plan_t").html("<b>Не ограничено</b>");
			$("#bl_plan_i").attr("style", "display:none;");

			$("#bl_timer_t").html("<b><?=$mails_nolimit_timer;?></b> секунд");
			$("#bl_timer_i").attr("style", "display:none;");

			$("#bl_up_list_t").html("<b>Да</b>");
			$("#bl_up_list_i").attr("style", "display:none;");

			$("#bl_color_t").html("<b>Да</b>");
			$("#bl_color_i").attr("style", "display:none;");

			$("#bl_active_t").html("<b>Нет</b>");
			$("#bl_active_i").attr("style", "display:none;");

			$("#bl_gotosite_t").html("<b>Да</b>");
			$("#bl_gotosite_i").attr("style", "display:none;");

			$("#bl_revisit_t").html("<b>Доставляется всем 1 раз в месяц</b>");
			$("#bl_revisit_i").attr("style", "display:none;");

			$("#bl_unic_ip_t").html("<b>Нет</b>");
			$("#bl_unic_ip_i").attr("style", "display:none;");

			$("#bl_new_users_t").html("<b>Все пользователи проекта</b>");
			$("#bl_new_users_i").attr("style", "display:none;");

			$("#bl_no_ref_t").html("<b>Все пользователи проекта</b>");
			$("#bl_no_ref_i").attr("style", "display:none;");

			$("#bl_sex_adv_t").html("<b>Все пользователи проекта</b>");
			$("#bl_sex_adv_i").attr("style", "display:none;");

			$("#bl_to_ref_t").html("<b>Все пользователи проекта</b>");
			$("#bl_to_ref_i").attr("style", "display:none;");

			$("#price_one_t").html("");
			$("#price_one_s").html("");
			$("#price_one_tr").attr("style", "display:none;");

			var rprice_sum = <?=$mails_nolimit_cena;?>;
		}else{
			$("#bl_plan_t").html("");
			$("#bl_plan_i").attr("style", "");

			$("#bl_timer_t").html("");
			$("#bl_timer_i").attr("style", "");

			$("#bl_up_list_t").html("");
			$("#bl_up_list_i").attr("style", "");

			$("#bl_color_t").html("");
			$("#bl_color_i").attr("style", "");

			$("#bl_gotosite_t").html("");
			$("#bl_gotosite_i").attr("style", "");

			$("#bl_active_t").html("");
			$("#bl_active_i").attr("style", "");

			$("#bl_revisit_t").html("");
			$("#bl_revisit_i").attr("style", "");

			$("#bl_unic_ip_t").html("");
			$("#bl_unic_ip_i").attr("style", "");

			$("#bl_new_users_t").html("");
			$("#bl_new_users_i").attr("style", "");

			$("#bl_no_ref_t").html("");
			$("#bl_no_ref_i").attr("style", "");

			$("#bl_sex_adv_t").html("");
			$("#bl_sex_adv_i").attr("style", "");

			$("#bl_to_ref_t").html("");
			$("#bl_to_ref_i").attr("style", "");
		}
	}

	function descchange(id, elem, count_s) {
		if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
		$("#count"+id).html("Осталось символов: " +(count_s-elem.value.length));
	}

	function InsertTags(text1, text2, descId) {
		var textarea = gebi(descId);
		if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
			var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;
			if (temp_length == 0) {
				caretPos.moveStart("character", -text2.length);
				caretPos.moveEnd("character", -text2.length);
				caretPos.select();
			} else {
				textarea.focus(caretPos);
			}
		} else if (typeof(textarea.selectionStart) != "undefined") {
			var begin = textarea.value.substr(0, textarea.selectionStart);
			var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
			var end = textarea.value.substr(textarea.selectionEnd);
			var newCursorPos = textarea.selectionStart;
			var scrollPos = textarea.scrollTop;
			textarea.value = begin + text1 + selection + text2 + end;
			if (textarea.setSelectionRange) {
				if (selection.length == 0) {
					textarea.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
				} else {
					textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
				}
				textarea.focus();
			}
			textarea.scrollTop = scrollPos;
		} else {
			textarea.value += text1 + text2;
			textarea.focus(textarea.value.length - 1);
		}
	}
</script><?php

echo '<form method="post" action="" name="formzakaz" onSubmit="return SbmFormB(); return false;" id="newform">';
echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<thead><tr><th>Параметр</th><th>Значение</th></tr></thead>';
	echo '<tbody>';
		if($mails_nolimit_status==1) {
			echo '<tr>';
				echo '<td align="left" width="240">Безлимитная реклама</td>';
				echo '<td>';
					echo '<select name="nolimit" id="nolimit" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" selected="selected">Нет</option>';
						echo '<option value="1">Да, на 30 дней</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';
		}
		echo '<tr>';
			echo '<td align="left" width="240"><b>Заголовок письма</b></td>';
			echo '<td align="left"><input class="ok" name="title" maxlength="255" value="" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" style="border-bottom:none;"><b>Содержание письма &darr;</b></td>';
			echo '<td align="left" style="border-bottom:none;">';
				echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">Ж</span>';
				echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">К</span>';
				echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">Ч</span>';
				echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'description\'); return false;">ST</span>';
				echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
				echo '<span id="count1" style="display: block; float:right; font-size:11px; color:#696969; margin-top:3px; margin-right:15px;">Осталось символов: 5000</span>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="2" style="border-top:none;">';
				echo '<textarea id="description" name="description" class="ok" style="height:120px; width:98.5%;" onKeyup="descchange(\'1\', this, \'5000\');" onKeydown="descchange(\'1\', this, \'5000\'); this.style.background=\'#FFFFFF\';" onClick="descchange(\'1\', this, \'5000\');"></textarea>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Контрольный вопрос к письму</td>';
			echo '<td align="left"><input class="ok" name="question" maxlength="255" value="" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Вариант ответа <span style="color: #009125;">(правильный)</span></td>';
			echo '<td align="left"><input class="ok" style="color: #009125;" name="answer_t" maxlength="255" value="" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Вариант ответа <span style="color: #FF0000;">(ложный)</span></td>';
			echo '<td align="left"><input class="ok" style="color: #FF0000;" name="answer_f_1" maxlength="255" value="" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Вариант ответа <span style="color: #FF0000;">(ложный)</span></td>';
			echo '<td align="left"><input class="ok" style="color: #FF0000;" name="answer_f_2" maxlength="255" value="" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>URL сайта</b></td>';
			echo '<td align="left"><input class="ok" name="url" maxlength="300" value="" type="text" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="31"><b>Показ рекламы</b></td>';
			echo '<td>';
				echo '<select id="wm_check" name="wm_check">';
					echo '<option value="0">Всем пользователям проекта</option>';
					echo '<option value="1">Только пользователям с подтверждённым WMID</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Количество просмотров</td>';
			echo '<td align="left">';
				echo '<div id="bl_plan_i"><input name="plan" id="plan" maxlength="7" value="1000" class="ok12" style="text-align:right;" type="text" autocomplete="off" onChange="PlanChange();" onKeyUp="PlanChange();" onkeydown="this.style.background=\'#FFFFFF\';"></div>';
				echo '<div id="bl_plan_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Время просмотра</td>';
			echo '<td>';
				echo '<div id="bl_timer_i"><input type="text" name="timer" id="timer" maxlength="3" value="20" class="ok12" style="text-align:right;" onChange="PlanChange();" onKeyUp="PlanChange();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(от '.$mails_timer_ot.' до '.$mails_timer_do.' сек.)</div>';
				echo '<div id="bl_timer_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<span id="adv-title1" class="adv-title-close" onclick="ShowHideBlock(1);">Дополнительные настройки</span>';
	echo '<div id="adv-block1" style="display:none;">';
	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" height="25">Размещение в начале списка</td>';
			echo '<td align="left">';
				echo '<div id="bl_up_list_i"><select name="up_list" id="up_list" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да</option>';
				echo '</select></div>';
				echo '<div id="bl_up_list_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" width="240" height="25">Технология доставки письма</td>';
			echo '<td align="left">';
				echo '<div id="bl_revisit_i"><select name="revisit" id="revisit" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Доставляется всем каждые 24 часа</option>';
					echo '<option value="1">Доставляется всем каждые 48 часов</option>';
					echo '<option value="2">Доставляется всем 1 раз в месяц</option>';
				echo '</select></div>';
				echo '<div id="bl_revisit_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Выделить письмо</td>';
			echo '<td align="left">';
				echo '<div id="bl_color_i"><select name="color" id="color" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да</option>';
				echo '</select></div>';
				echo '<div id="bl_color_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Активное окно</td>';
			echo '<td align="left">';
				echo '<div id="bl_active_i"><select name="active" id="active" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да</option>';
				echo '</select></div>';
				echo '<div id="bl_active_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Последующий переход на сайт</td>';
			echo '<td align="left">';
				echo '<div id="bl_gotosite_i"><select name="gotosite" id="gotosite" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да</option>';
				echo '</select></div>';
				echo '<div id="bl_gotosite_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Уникальный IP</td>';
			echo '<td align="left">';
				echo '<div id="bl_unic_ip_i"><select name="unic_ip" id="unic_ip" onChange="PlanChange();" onClick="PlanChange();">';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да, 100% совпадение</option>';
					echo '<option value="2">Усиленный по маске до 2 чисел (255.255.X.X)</option>';
				echo '</select></div>';
				echo '<div id="bl_unic_ip_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Показывать только новичкам</td>';
			echo '<td align="left">';
				echo '<div id="bl_new_users_i"><select id="new_users" name="new_users" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
					echo '<option value="0" selected="selected">Все пользователи проекта</option>';
					echo '<option value="1">Да, до 7 дней с момента регистрации</option>';
				echo '</select></div>';
				echo '<div id="bl_new_users_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">По наличию реферера</td>';
			echo '<td align="left">';
				echo '<div id="bl_no_ref_i"><select id="no_ref" name="no_ref" onChange="PlanChange();" onClick="PlanChange();">';
					echo '<option value="0" selected="selected">Все пользователи проекта</option>';
					echo '<option value="1">Пользователям без реферера на '.$_SERVER["HTTP_HOST"].'</option>';
				echo '</select></div>';
				echo '<div id="bl_no_ref_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">По половому признаку</td>';
			echo '<td align="left">';
				echo '<div id="bl_sex_adv_i"><select id="sex_adv" name="sex_adv" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
					echo '<option value="0" selected="selected">Все пользователи проекта</option>';
					echo '<option value="1">Только мужчины</option>';
					echo '<option value="2">Только женщины</option>';
				echo '</select></div>';
				echo '<div id="bl_sex_adv_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Показывать только рефералам</td>';
			echo '<td align="left">';
				echo '<div id="bl_to_ref_i"><select id="to_ref" name="to_ref" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
					echo '<option value="0" selected="selected">Все пользователи проекта</option>';
					echo '<option value="1">Рефералам 1-го уровня</option>';
					echo '<option value="2">Рефералам всех уровней</option>';
				echo '</select></div>';
				echo '<div id="bl_to_ref_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<span id="adv-title2" class="adv-title-close" onclick="ShowHideBlock(2);">Настройки геотаргетинга</span>';
	echo '<div id="adv-block2" style="display:none;">';
	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td colspan="2" align="center" style="border-right:none;"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>Отметить все</center></a></td>';
		echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>Снять все</center></a></td>';
	echo '</tr>';
	include(DOC_ROOT."/advertise/func_geotarg.php");
	echo '</tbody>';
	echo '</table>';
	echo '</div>';

	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="center"><input type="submit" value="Сохранить" class="sub-blue160" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '</form>';

?>

<script language="JavaScript"> PlanChange();</script>