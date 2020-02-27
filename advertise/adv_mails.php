<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}

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
//	$mensaje = str_replace("\\", "", $mensaje);

	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);
	$mensaje = str_replace("&amp;quot;","&quot;",$mensaje);
	return $mensaje;
}

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

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_user = mysql_query("SELECT `wmid`,`wm_purse`,`money_rb` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_row($sql_user);
		$wmid_user = $row_user["0"];
		$wmr_user = $row_user["1"];
		$money_user = $row_user["2"];
	}else{
		$username = false;
		$wmid_user = false;
		$wmr_user = false;
		$money_user = false;

		echo '<span class="msg-error">Пользователь не найден.</span>';
		include('footer.php');
		exit();
	}

}else{
	$username = false;
	$wmid_user = false;
	$wmr_user = false;
	$money_user = false;
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
		echo '<span class="msg-error">Для оплаты с рекламного счета необходимо авторизоваться!</span>';
		include('footer.php');
		exit();
	}else{
		$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

		$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_assoc($sql_id);
			$plan = $row["plan"];
			$money_pay = $row["money"];
			$merch_tran_id = $row["merch_tran_id"];

			if($money_user>=$money_pay) {

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($my_referer!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer'") or die(mysql_error());}
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());

				//mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Yг. H:i")."','$money_pay',  'Оплата рекламы рекламные письма - $plan шт.','Списано','rashod')") or die(mysql_error());

				stat_pay("mails", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 1);
				BonusSurf($username, $money_pay);

				echo '<span class="msg-ok">Ваша реклама успешно размещена!<br>Спасибо, что пользуетесь услугами нашего сервиса</span>';
				include('footer.php');
				exit();
			}else{
				echo '<span class="msg-error">На вашем рекламном счете недостаточно средств для оплаты заказа!</span>';
				include('footer.php');
				exit();
			}
		}else{
			echo '<span class="msg-error">Заказа рекламы с №'.$id_pay.' не существует, либо заказ уже был оплачен!</span>';
			include('footer.php');
			exit();
		}
	}
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

	$method_pay = (isset($_POST["method_pay"])) ? intval(trim($_POST["method_pay"])) : "1";
	$tosaccept = (isset($_POST["tosaccept"]) && (intval($_POST["tosaccept"])==0 | intval($_POST["tosaccept"])==1)) ? intval(trim($_POST["tosaccept"])) : "0";
	$wm_check = (isset($_POST["wm_check"]) && preg_match("|^[0-1]{1}$|", trim($_POST["wm_check"])) ) ? intval(trim($_POST["wm_check"])) : "0";
	$laip = getRealIP();
	$black_url = @getHost($url);

	if($username==false) $to_ref = 0;

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
	}elseif($tosaccept==0) {
		echo '<span class="msg-error">Необходимо прочитать правила размещения рекламы и согласиться с ними.</span>';
	}else{
		$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "Wallet One";
$system_pay[4] = "InterKassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "YandexMoney";
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[21] = "AdvCash";
		$system_pay[10] = "Рекламный счет";

		$color_to[0] = "Нет";
		$color_to[1] = $nolimit>0 ? "Да" : "Да (".number_format(($plan * $color * $mails_cena_color),4,".","")." руб.)";

		$up_list_to[0] = "Нет";
		$up_list_to[1] = $nolimit>0 ? "Да" : "Да (".number_format(($up_list * $mails_cena_uplist),4,".","")." руб.)";

		$gotosite_to[0] = "Нет";
		$gotosite_to[1] = $nolimit>0 ? "Да" : "Да (".number_format(($plan * $gotosite * $mails_cena_gotosite),4,".","")." руб.)";

		$content_to[0] = "Нет";
		$content_to[1] = "Да";

		$wm_check_to[0]="Все пользователи проекта";
		$wm_check_to[1]="Только пользователям с подтверждённым WMID";

		$new_users_to[0] = "Все пользователи проекта";
		$new_users_to[1] = "Да, до 7 дней с момента регистрации";

		$unic_ip_to[0] = "Нет";
		$unic_ip_to[1] = $nolimit>0 ? "Да, 100% совпадение" : "Да, 100% совпадение &mdash; (".number_format(($plan * $mails_cena_unic_ip[1]),4,".","")." руб.)";
		$unic_ip_to[2] = $nolimit>0 ? "Да, усиленный по маске до 2 чисел (255.255.X.X)" : "Да, усиленный по маске до 2 чисел (255.255.X.X) &mdash; (".number_format(($plan * $mails_cena_unic_ip[2]),4,".","")." руб.)";

		$revisit_to[0] = $nolimit>0 ? "Доставляется всем каждые 24 часа" : "Доставляется всем каждые 24 часа";
		$revisit_to[1] = $nolimit>0 ? "Доставляется всем каждые 48 часов" : "Доставляется всем каждые 48 часов (".number_format(($plan*$mails_cena_revisit[1]),4,".","")." руб.)";
		$revisit_to[2] = $nolimit>0 ? "Доставляется всем 1 раз в месяц" : "Доставляется всем 1 раз в месяц (".number_format(($plan*$mails_cena_revisit[2]),4,".","")." руб.)";

		$no_ref_to[0] = "Все пользователи проекта";
		$no_ref_to[1] = "Да";

		$sex_adv_to[0] = "Все пользователи проекта";
		$sex_adv_to[1] = "Только мужчины";
		$sex_adv_to[2] = "Только женщины";

		$to_ref_to[0] = "Все пользователи проекта";
		$to_ref_to[1] = "Рефералам 1-го уровня";
		$to_ref_to[2] = "Рефералам всех уровней";

		$active_to[0] = "Нет";
		$active_to[1] = $nolimit>0 ? "Да" : "Да (".number_format(($plan * $active * $mails_cena_active),4,".","")." руб.)";

		if($timer>$mails_timer_ot) {
			$timer_to = $nolimit>0 ? "Да" : "$timer (".number_format(($plan * ($timer-$mails_timer_ot) * $mails_cena_timer),4,".","")." руб.)";
		}else{
			$timer_to = $nolimit>0 ? "$timer" : "$timer (0.00 руб.)";
		}

		$precio = $nolimit>0 ? $mails_nolimit_cena : ( ($up_list * $mails_cena_uplist) + $plan * ($mails_cena_hit + ($mails_cena_timer * ($timer-$mails_timer_ot)) + ($active * $mails_cena_active) + ($color * $mails_cena_color) + $mails_cena_revisit[$revisit] + $mails_cena_unic_ip[$unic_ip] + ($gotosite * $mails_cena_gotosite)) );
		$summa = number_format(($precio * (100-$cab_skidka)/100),2,".","");

	        mysql_query("DELETE FROM `tb_ads_mails` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+1 WHERE `id`='1'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
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

			require_once("bbcode/bbcode.lib.php");
			$description_to = new bbcode($description_to);
			$description_to = $description_to->get_html();
			$description_to = str_replace("&amp;", "&", $description_to);
		}else{
			exit("ERROR NO ID");
		}
		
		echo '<br><span class="msg-ok" style="margin-bottom:0px;">Ваш заказ принят и будет выполнен автоматически после оплаты!</span>';
		echo '<table class="tables">';
			echo '<tr><td align="left" width="220">Счет № (ID)</td><td align="left">'.number_format($merch_tran_id, 0,".", "").' ('.$id_zakaz.')</td></tr>';
			echo '<tr><td>Заголовок письма</td><td>'.$title.'</td></tr>';
			echo '<tr><td>URL сайта</td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td>Содержание письма</td><td>'.$description_to.'</td></tr>';
			echo '<tr><td>Контрольный вопрос к письму:</td><td>'.$question.'</td></tr>';
			echo '<tr><td>Вариант ответа <span style="color: #009125;">(правильный)</span></td><td>'.$answer_t.'</td></tr>';
			echo '<tr><td>Вариант ответа <span style="color: #FF0000;">(ложный)</span></td><td>'.$answer_f_1.'</td></tr>';
			echo '<tr><td>Вариант ответа <span style="color: #FF0000;">(ложный)</span></td><td>'.$answer_f_2.'</td></tr>';
			echo '<tr><td>Количество просмотров</td><td>'.(($nolimit>0 && $mails_nolimit_status==1) ? "Не ограничено" : "$plan (".number_format(($plan * $mails_cena_hit),2,".","")." руб.)").'</td></tr>';
			echo '<tr><td>Показ пользователям c подтвержденным WMID</td><td>'.$wm_check_to[$wm_check].'</td></tr>';
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
			if(isset($cab_text)) echo "$cab_text";
			echo '<tr><td><b>Способ оплаты</b></td><td><b>'.$system_pay[$method_pay].'</b>, счет необходимо оплатить в течении 24 часов</td></tr>';
			@require_once("".$_SERVER['DOCUMENT_ROOT']."/curs/curs.php");
				$money_add_usd = number_format(round(($summa/$CURS_USD),2),2,".","");
				
			if($method_pay==8) {
				if(($summa*0.005)<0.01) {$money_add_ym = $summa + 0.01;}else{$money_add_ym = number_format(($summa*1.005),2,".","");}

				echo '<tr><td><b>Стоимость заказа:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>руб.</b></td></tr>';
				echo '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_ym,2,".","`").'</b> <b>руб.</b></td></tr>';
			}elseif($method_pay==7) {
						echo '<tr><td><b>Стоимость заказа:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>руб.</b></td></tr>';
						echo '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_usd,2,".","`").'</b> <b>USD</b></td></tr>';
			}else{
				echo '<tr><td><b>Сумма к оплате:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>руб.</b></td></tr>';
			}
		echo '</table>';


		$shp_item = "11";
		$inv_desc = "Оплата рекламы: рекламные письма, план:$plan, счет:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: rek mails, plan:$plan, order:$merch_tran_id";
		$money_add = number_format($summa,2,".","");
		require_once(DOC_ROOT."/method_pay/method_pay.php");

		include("footer.php");
		exit();
	}
}
?><script type="text/javascript" language="JavaScript">
	function gebi(id){
		return document.getElementById(id);
	}

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
		if (document.forms["formzakaz"].tosaccept.checked == false) {
			alert('Если вы прочитали правила, с ними нужно согласиться');
			return false;
		}         
		document.forms["formzakaz"].submit();
		return true;
	}

	function number_format(number, decimals, dec_point, thousands_sep) {
		var minus = "";
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		if(number < 0){
			minus = "-";
			number = number*-1;
		}
		var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
			var k = Math.pow(10, prec);
			return '' + (Math.round(n * k) / k).toFixed(prec);
		};
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return minus + s.join(dec);
	}

	function PlanChange(){
		var nolimit = $.trim($("#nolimit").val()) ? $.trim($("#nolimit").val()) : 0;
		var timer = $.trim($("#timer").val());
		var plan = $.trim($("#plan").val());
		var color = $.trim($("#color").val());
		var active = $.trim($("#active").val());
		var revisit = $.trim($("#revisit").val());
		var unic_ip = $.trim($("#unic_ip").val());
		var gotosite = $.trim($("#gotosite").val());
		var up_list = $.trim($("#up_list").val());
		var rprice = <?=$mails_cena_hit;?>;

		if(timer < <?=$mails_timer_ot;?>) timer = <?=$mails_timer_ot;?>;
		if(timer > <?=$mails_timer_do;?>) timer = <?=$mails_timer_do;?>;

		if(timer > <?=$mails_timer_ot;?>) rprice += (timer - <?=$mails_timer_ot;?>) * <?=$mails_cena_timer;?>;

		if (color == 1) rprice += <?=$mails_cena_color;?>;
		if (active == 1) rprice += <?=$mails_cena_active;?>;
		if (revisit == 1) {
			rprice += <?=$mails_cena_revisit[1];?>;
		} else if (revisit == 2) {
			rprice += <?=$mails_cena_revisit[2];?>;
		}
		if (unic_ip == 1) {
			rprice += <?=$mails_cena_unic_ip[1];?>;
		} else if (unic_ip == 2) {
			rprice += <?=$mails_cena_unic_ip[2];?>;
		}
		if (gotosite == 1) rprice += <?=$mails_cena_gotosite;?>;

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

			var rprice_sum = (rprice * plan);
			if (up_list == 1) rprice_sum += <?=$mails_cena_uplist;?>

			$("#price_one_t").html("Стоимость одного просмотра");
			$("#price_one_s").html('<span style="color:#228B22;">' + number_format(rprice, 4, '.', ' ') + ' руб.</span> (без учета вашей накопительной скидки)');
			$("#price_one_tr").attr("style", "");
		}

		$("#price_t").html("Итого к оплате");
		$("#price_s").html('<span style="color:#FF0000;">' + number_format(rprice_sum, 2, '.', ' ') + '</span> (без учета вашей накопительной скидки)');
		$("#price_s1").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s2").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s3").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s4").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s5").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s6").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s7").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s8").html('<span style="color:#f6f9f6;">' + number_format((rprice_sum/60), 2, '.', ' ') + '</span>');
		$("#price_s9").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
		$("#price_s10").html('<span style="color:#f6f9f6;">' + number_format(rprice_sum, 2, '.', ' ') + '</span>');
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

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:6px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 2px #A27AE5; text-align:left; padding-left:50px;">Оплачиваемые письма - что это?</span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo 'Оплачиваемые письма на <b style="color:#3A5FCD">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; это возможность заинтересовать огромную аудиторию людей и привлечь их на рекламируемый ресурс. ';
		echo 'В письмах можно более полно и красочно описать все достоинства и преимущества вашего сайта, тем самым донести до читателей намного больше информации чем в любой другой рекламной кампании. ';
		echo 'Чтобы убедится в том что пользователь прочитал письмо, он должен ответить на контрольный вопрос содержащийся в тексте письма. Просмотр сайта - подтверждается проверочным кодом.';
		echo '<br>';
	echo '</div>';

	if($mails_nolimit_status==1) {
		echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 2px #A27AE5; text-align:left; padding-left:50px; margin-bottom:3px;">Безлимитная реклама</span>';
		echo '<div id="adv-block-bl" style="display:block; padding:5px; text-align:center; background-color:#FFFFFF;">';
			echo '<span class="videlenie" style="margin-bottom:0; font-weight:normal;">';
		echo '<b>Безлимитная реклама:</b> на <b>30</b> дней - <b>'.number_format($mails_nolimit_cena,2,".","`").' руб.</b> (Доступно для прочтения 1 раз в месяц каждому пользователю проекта, а так же новым зарегистрирующимся в течении 30 дней)';			echo '</span>';
		echo '</div>';
	}

	echo '<span id="adv-title-rules" class="adv-title-close" onclick="ShowHideBlock(\'-rules\');" style="border-top:solid 2px #A27AE5; text-align:left; padding-left:50px; margin-bottom:0px;">Правила</span>';
		echo '<div id="adv-block-rules" style="display:block; padding:5px; text-align:center; background-color:#FFFFFF;">';
		include('includes/tos_mails.php');
	echo '</div>';
echo '</div>';


echo '<form method="post" action="" name="formzakaz" onSubmit="return SbmFormB(); return false;" id="newform">';
echo '<table class="tables">';
	echo '<thead><tr><th>Параметр</th><th>Значение</th></tr></thead>';
	echo '<tbody>';
		if($mails_nolimit_status==1) {
			echo '<tr>';
				echo '<td align="left" width="240">Безлимитная реклама</td>';
				echo '<td>';
					echo '<select name="nolimit" id="nolimit" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" selected="selected">Нет</option>';
						echo '<option value="1">Да, на 30 дней &mdash; '.number_format($mails_nolimit_cena,2,".","`").' руб.</option>';
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
				echo '<span id="count1" style="display: block; float:right; font-size:11px; color:#696969; margin-top:3px; margin-right:3px;">Осталось символов: 5000</span>';
			echo '</td>';
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
	echo '<table class="tables">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" height="25">Размещение в начале списка</td>';
			echo '<td align="left">';
				echo '<div id="bl_up_list_i"><select name="up_list" id="up_list" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да '.($mails_cena_uplist>0 ? "(+ ".$mails_cena_uplist." руб.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_up_list_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" width="240" height="25">Технология доставки письма</td>';
			echo '<td align="left">';
				echo '<div id="bl_revisit_i"><select name="revisit" id="revisit" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Доставляется всем каждые 24 часа</option>';
					echo '<option value="1">Доставляется всем каждые 48 часов '.($mails_cena_revisit[1]>0 ? "(+ ".p_floor($mails_cena_revisit[1], 4)." руб.)" : false).'</option>';
					echo '<option value="2">Доставляется всем 1 раз в месяц '.($mails_cena_revisit[2]>0 ? "(+ ".p_floor($mails_cena_revisit[2], 4)." руб.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_revisit_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Выделить письмо</td>';
			echo '<td align="left">';
				echo '<div id="bl_color_i"><select name="color" id="color" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да '.($mails_cena_color>0 ? "(+ ".p_floor($mails_cena_color, 4)." руб.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_color_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Активное окно</td>';
			echo '<td align="left">';
				echo '<div id="bl_active_i"><select name="active" id="active" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да '.($mails_cena_active>0 ? "(+ ".p_floor($mails_cena_active, 4)." руб.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_active_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Последующий переход на сайт</td>';
			echo '<td align="left">';
				echo '<div id="bl_gotosite_i"><select name="gotosite" id="gotosite" onChange="PlanChange();" onClick="PlanChange();" >';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да '.($mails_cena_gotosite>0 ? "(+ ".p_floor($mails_cena_gotosite, 4)." руб.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_gotosite_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">Уникальный IP</td>';
			echo '<td align="left">';
				echo '<div id="bl_unic_ip_i"><select name="unic_ip" id="unic_ip" onChange="PlanChange();" onClick="PlanChange();">';
					echo '<option value="0" selected="selected">Нет</option>';
					echo '<option value="1">Да, 100% совпадение '.($mails_cena_unic_ip[1]>0 ? "&mdash; (+ ".p_floor($mails_cena_unic_ip[1], 4)." руб.)" : false).'</option>';
					echo '<option value="2">Усиленный по маске до 2 чисел (255.255.X.X)  '.($mails_cena_unic_ip[2]>0 ? "&mdash; (+ ".p_floor($mails_cena_unic_ip[2], 4)." руб.)" : false).'</option>';
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
	echo '<table class="tables">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td colspan="2" align="center" style="border-right:none;"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>Отметить все</center></a></td>';
		echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>Снять все</center></a></td>';
	echo '</tr>';
	include(DOC_ROOT."/advertise/func_geotarg.php");
	echo '</tbody>';
	echo '</table>';
	//echo '</div>';

	//echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">Информация и способ оплаты</span>';
	//echo '<div id="adv-block3" style="display:block;">';
	echo '<table class="tables">';
	//echo '<tbody>';
		//echo '<tr>';
			//echo '<td align="left" width="240"><b>Способ оплаты</b></td>';
			//echo '<td align="left">';
				//echo '<select name="method_pay">';
					//require_once("".DOC_ROOT."/method_pay/method_pay_form.php");
				//echo '</select>';
			//echo '</td>';
		//echo '</tr>';
		echo '<tr id="price_one_tr">';
			echo '<td id="price_one_t"></td>';
			echo '<td id="price_one_s"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td id="price_t"></td>';
			echo '<td id="price_s"></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '<table class="tables">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="center"><input type="checkbox" name="tosaccept" value="1" />С <a href="/tos.php?ads=tos_4" target="_blank" title="Откроются в новом окне"><b>Правилами</b></a> размещения рекламы полностью согласен(на)</td>';
		echo '</tr>';
		echo '</tbody>';
	echo '</table>';
	
//echo '</div>';
	echo '<div class="blok" style="text-align:center;">';
	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">Выбрать способ оплаты</span>';
	echo '<div id="adv-block3" style="display:block;">';
	//	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		echo '<button id="method_pay"  name="method_pay" value="10" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-rs1">';
      echo '<div><div><div><span class="line-green"><span id="price_s1"></span> руб.</span></div></div></div>';
	  echo '</div> </button>';
		//}else{
			
	//}
    if($site_pay_wm!=1) {
        echo '<div class="cash-wm1">';
    	  echo '<div class="cash-wm1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="1" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-wm1">';
    echo '<div><div><div><span class="line-green"><span id="price_s2"></span> руб. (+0.8%)</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_ym!=1) {
        echo '<div class="cash-yd1">';
    	  echo '<div class="cash-yd1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="8" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-yd1">';
    echo '<div><div><div><span class="line-green"><span id="price_s3"></span> руб.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_robo!=1) {
        echo '<div class="cash-rb1">';
    	  echo '<div class="cash-rb1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="2" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-rb1">';
    echo '<div><div><div><span class="line-green"><span id="price_s4"></span> руб.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_mega!=1) {
    	  echo '<div class="cash-ik1">';
    	  echo '<div class="cash-ik1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="11" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-ik1">';
    echo '<div><div><div><span class="line-green"><span id="price_s5"></span> руб.</span></div></div></div>';
	echo '</div> </button>';
	}
  
  if($site_pay_qw!=1) {
    	  echo '<div class="cash-qw1">';
    	  echo '<div class="cash-qw1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="6" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-qw1">';
    echo '<div><div><div><span class="line-green"><span id="price_s6"></span> руб.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_payeer!=1) {
    	  echo '<div class="cash-pr1">';
    	  echo '<div class="cash-pr1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="5" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-pr1">';
    echo '<div><div><div><span class="line-green"><span id="price_s7"></span> руб.</span></div></div></div>';
	echo '</div> </button>';
	}
  
  if($site_pay_pm!=1) {
    	  echo '<div class="cash-pm1">';
    	  echo '<div class="cash-pm1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
	}else{
     echo '<button id="method_pay" name="method_pay" value="7" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-pm1" >';
     echo '<div><div><div><span class="line-green"><span id="price_s8"></span> USD</span></div></div></div>';
	 echo '</div> </button>';
	 //echo '</div>';
}
	
	if($site_pay_advcash!=1) {
    	  echo '<div class="cash-ah1">';
    	  echo '<div class="cash-ah1">';
    	  echo '<div><div><div<span class="line-red">Временно отключен</span></div></div></div>';
    	  echo '</div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="9" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="cash-ah1">';
    echo '<div><div><div><span class="line-green"><span id="price_s10"></span> руб.</span></div></div></div>';
	echo '</div> </button>';
	}
	//echo '<table class="tables">';
	//echo '<tbody>';
		//echo '<tr>';
			//echo '<td align="center"><input type="checkbox" name="tosaccept" value="1" />С <a href="/tos.php?ads=tos_4" target="_blank" title="Откроются в новом окне"><b>Правилами</b></a> размещения рекламы полностью согласен(на)</td>';
		//echo '</tr>';
		//echo '<tr>';
			//echo '<td align="center"><input type="submit" value="Оформить заказ" class="submit" /></td>';
		//echo '</tr>';
	//echo '</tbody>';
	//echo '</table>';

	echo '</form>';

?>

<script language="JavaScript"> PlanChange();</script>