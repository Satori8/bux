<?php
if (!(DEFINED('CABINET')) | !(DEFINED('PAY_VISITS_AJAX'))) {
	exit(my_json_encode('ERROR', 'Access denied!'));
}

function ListStatus($index = false)
{
	global $mysqli;
	$_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE = array();
	($_obfuscate_DRw8GD4oIxcHJBkvIgQpC1sWIT8SHgE = $mysqli->query('SELECT `id`,`rang`,`r_ot` FROM `tb_config_rang` WHERE `id`>\'1\' ORDER BY `id` ASC')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $_obfuscate_DRw8GD4oIxcHJBkvIgQpC1sWIT8SHgE->num_rows) {
		$_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE[0] = "Все" . ' ' . "пользователи" . ' ' . "проекта";

		while ($_obfuscate_DTEdFRMGCBEaQCoQPiknMBoWHRkdEiI = $_obfuscate_DRw8GD4oIxcHJBkvIgQpC1sWIT8SHgE->fetch_assoc()) {
			$_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE[$_obfuscate_DTEdFRMGCBEaQCoQPiknMBoWHRkdEiI['id']] = "С" . ' ' . "рейтингом" . ' <b>' . number_format($_obfuscate_DTEdFRMGCBEaQCoQPiknMBoWHRkdEiI['r_ot'], 0, '.', ' ') . '</b> ' . "и" . ' ' . "более" . ' ' . "баллов" . ' (<b>' . $_obfuscate_DTEdFRMGCBEaQCoQPiknMBoWHRkdEiI['rang'] . '</b>)';
		}

		$_obfuscate_DRw8GD4oIxcHJBkvIgQpC1sWIT8SHgE->free();
	}
	 else {
		$_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE[0] = "Все" . ' ' . "пользователи" . ' ' . "проекта";
		$_obfuscate_DRw8GD4oIxcHJBkvIgQpC1sWIT8SHgE->free();
	}

	return (($index !== false) && isset($_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE[$index]) ? $_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE[$index] : $_obfuscate_DQoSMEAmFx4GJ1seKhIuJyIFBCQyDAE);
}

$mysqli->query('UPDATE `tb_ads_pay_vis` SET `status`=\'3\',`date_end`=\'' . time() . '\' WHERE `status`=\'1\' AND `balance`<`price_adv`') or die(my_json_encode('ERROR', $mysqli->error));
$mysqli->query('UPDATE `tb_ads_pay_vis` SET `status`=\'1\' WHERE `status`=\'3\' AND `balance`>=`price_adv`') or die(my_json_encode('ERROR', $mysqli->error));
$hide_httpref_arr = array("Нет", "Да");
$color_arr = array("Нет", "Да");
$revisit_arr = array("Каждые" . ' 24 ' . "часа", "Каждые" . ' 48 ' . "часов", '1 ' . "раз" . ' ' . "в" . ' ' . "месяц");
$uniq_ip_arr = array("Нет", "Да" . ' (100% ' . "совпадение" . ')', "Усиленный" . ' ' . "по" . ' ' . "маске" . ' ' . "до" . ' 2 ' . "чисел" . ' (255.255.X.X)');
$datereg_user_arr = array(0 => "Все" . ' ' . "пользователи" . ' ' . "проекта", 3 => '3 ' . "дня" . ' ' . "с" . ' ' . "момента" . ' ' . "регистрации", 7 => '7 ' . "дней" . ' ' . "с" . ' ' . "момента" . ' ' . "регистрации", 30 => '1 ' . "месяц" . ' ' . "с" . ' ' . "момента" . ' ' . "регистрации", 90 => '3 ' . "месяца" . ' ' . "с" . ' ' . "момента" . ' ' . "регистрации", 180 => '6 ' . "месяцев" . ' ' . "с" . ' ' . "момента" . ' ' . "регистрации", 365 => '1 ' . "год" . ' ' . "с" . ' ' . "момента" . ' ' . "регистрации");
$no_ref_arr = array("Все" . ' ' . "пользователи" . ' ' . "проекта", "Пользователям" . ' ' . "без" . ' ' . "реферера" . ' ' . "на" . ' ' . ucfirst($_SERVER['HTTP_HOST']) . '');
$sex_user_arr = array("Все" . ' ' . "пользователи" . ' ' . "проекта", "Только" . ' ' . "мужчины", "Только" . ' ' . "женщины");
$to_ref_arr = array("Все" . ' ' . "пользователи" . ' ' . "проекта", "Рефералам" . ' 1-' . "го" . ' ' . "уровня", "Рефералам" . ' ' . "всех" . ' ' . "уровней");
$content_arr = array("Нет", "Да");
$max_entrys_stat = 30;
$start_pos = 0;

if ($option == 'play-pause') {
	($sql = $mysqli->query('SELECT `id`,`status` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-play-pause' . $security_key));
		$token_playpause = $token_check;

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 0) {
			$result_text = 'ERROR';
			$message_text = '<div id="scr-' . $id . '"><script>$("#adv-status-' . $id . ' span").attr({"class":"adv-play", "title":"' . "Запустить" . ' ' . "показ" . ' ' . "рекламной" . ' ' . "площадки" . '"}); setTimeout(function(){$("#scr-' . $id . '").remove();},500);</script></div>';
			$message_text .= "Запуск" . ' ' . "не" . ' ' . "возможен" . ', ' . "необходимо" . ' ' . "пополнить" . ' ' . "баланс" . ' ' . "площадки" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 1) {
			$mysqli->query('UPDATE `tb_ads_pay_vis` SET `status`=\'2\',`date_edit`=\'' . time() . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
			$result_text = 'OK';
			$message_text = '<span onClick="FuncAdvCab(' . $id . ', \'play-pause\', \'' . $type_ads . '\', false, \'' . $token_playpause . '\');" class="adv-play" title="' . "Запустить" . ' ' . "показ" . ' ' . "рекламной" . ' ' . "площадки" . '"></span>';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 2) {
			$mysqli->query('UPDATE `tb_ads_pay_vis` SET `status`=\'1\',`date_edit`=\'' . time() . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
			$result_text = 'OK';
			$message_text = '<span onClick="FuncAdvCab(' . $id . ', \'play-pause\', \'' . $type_ads . '\', false, \'' . $token_playpause . '\');" class="adv-pause" title="' . "Приостановить" . ' ' . "показ" . ' ' . "рекламной" . ' ' . "площадки" . '"></span>';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 3) {
			$result_text = 'ERROR';
			$message_text = '<div id="scr-' . $id . '"><script>$("#adv-status-' . $id . ' span").attr({"class":"adv-play", "title":"' . "Запустить" . ' ' . "показ" . ' ' . "рекламной" . ' ' . "площадки" . '"}); setTimeout(function(){$("#scr-' . $id . '").remove();},500);</script></div>';
			$message_text .= "Запуск" . ' ' . "не" . ' ' . "возможен" . ', ' . "необходимо" . ' ' . "пополнить" . ' ' . "баланс" . ' ' . "площадки" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$result_text = 'ERROR';
		$message_text = "Статус" . '[' . $status . '] ' . "не" . ' ' . "определен" . '!';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'edit-adv') {
	($sql = $mysqli->query('SELECT `id`,`status` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-edit-adv' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 1) {
			$result_text = 'ERROR';
			$message_text = "Перед" . ' ' . "редактированием" . ', ' . "необходимо" . ' ' . "поставить" . ' ' . "площадку" . ' ' . "на" . ' ' . "паузу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$StatusArr = liststatus();

		$title = ((isset($_POST['title']) && is_string($_POST['title']) ? escape(limitatexto(limpiarez($_POST['title']), 60)) : false));

		$description = ((isset($_POST['description']) && is_string($_POST['description']) ? escape(limitatexto(limpiarez($_POST['description']), 80)) : false));

		$url = ((isset($_POST['url']) && is_string($_POST['url']) ? escape(limitatexto(limpiarez($_POST['url']), 300)) : false));

		$hide_httpref = ((isset($_POST['hide_httpref']) && preg_match('|^[0-1]{1}$|', intval($_POST['hide_httpref'])) ? intval($_POST['hide_httpref']) : 0));

		$color = ((isset($_POST['color']) && preg_match('|^[0-1]{1}$|', intval($_POST['color'])) ? intval($_POST['color']) : 0));

		$revisit = ((isset($_POST['revisit']) && preg_match('|^[0-2]{1}$|', intval($_POST['revisit'])) ? intval($_POST['revisit']) : 0));

		$uniq_ip = ((isset($_POST['uniq_ip']) && preg_match('|^[0-2]{1}$|', intval($_POST['uniq_ip'])) ? intval($_POST['uniq_ip']) : 0));

		$date_reg_user = ((isset($_POST['date_reg_user']) && (array_key_exists(intval($_POST['date_reg_user']), $datereg_user_arr) !== false) ? intval($_POST['date_reg_user']) : 0));

		$reit_user = ((isset($_POST['reit_user']) && (array_key_exists(intval($_POST['reit_user']), $StatusArr) !== false) ? intval($_POST['reit_user']) : 0));

		$no_ref = ((isset($_POST['no_ref']) && preg_match('|^[0-1]{1}$|', intval($_POST['no_ref'])) ? intval($_POST['no_ref']) : 0));

		$sex_user = ((isset($_POST['sex_user']) && preg_match('|^[0-2]{1}$|', intval($_POST['sex_user'])) ? intval($_POST['sex_user']) : 0));

		$to_ref = ((isset($_POST['to_ref']) && preg_match('|^[0-2]{1}$|', intval($_POST['to_ref'])) ? intval($_POST['to_ref']) : 0));

		$content = ((isset($_POST['content']) && preg_match('|^[0-1]{1}$|', intval($_POST['content'])) ? intval($_POST['content']) : 0));
		$country = ((isset($_POST['country']) ? CheckCountry($_POST['country'], $geo_code_name_arr) : array()));

		$country_code = ((isset($country) && is_array($country) && (count($country) != count($geo_code_name_arr)) ? implode(', ', array_keys($country)) : false));
		$black_url = StringUrl($url);
		($sql_bl = $mysqli->query('SELECT `domen` FROM `tb_black_sites` WHERE `domen` IN (' . $black_url . ')')) or die(my_json_encode('ERROR', $mysqli->error));
		$cnt_bl = $sql_bl->num_rows;

		if ($cnt_bl <= 0) {
			$sql_bl->free();
		}


		if ($title == false) {
			$result_text = 'ERROR';
			$message_text = "Вы" . ' ' . "не" . ' ' . "указали" . ' ' . "заголовок" . ' ' . "ссылки";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (strlen($title) < 5) {
			$result_text = 'ERROR';
			$message_text = "Заголовок" . ' ' . "ссылки" . ' ' . "должен" . ' ' . "содержать" . ' ' . "минимум" . ' 5 ' . "символов";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($description == false) {
			$result_text = 'ERROR';
			$message_text = "Вы" . ' ' . "не" . ' ' . "указали" . ' ' . "описание" . ' ' . "ссылки";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (strlen($description) < 5) {
			$result_text = 'ERROR';
			$message_text = "Описание" . ' ' . "ссылки" . ' ' . "должно" . ' ' . "содержать" . ' ' . "минимум" . ' 5 ' . "символов";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (($url == false) | ($url == 'http://') | ($url == 'https://')) {
			$result_text = 'ERROR';
			$message_text = "Вы" . ' ' . "не" . ' ' . "указали" . ' URL-' . "адрес" . ' ' . "сайта";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ((substr($url, 0, 7) != 'http://') && (substr($url, 0, 8) != 'https://')) {
			$result_text = 'ERROR';
			$message_text = 'URL-' . "адрес" . ' ' . "сайта" . ' ' . "указан" . ' ' . "неверно";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (is_url($url) != 'true') {
			$result_text = 'ERROR';
			$message_text = 'URL-' . "адрес" . ' ' . "сайта" . ' ' . "указан" . ' ' . "неверно" . ', ' . "возможно" . ' ' . "ссылка" . ' ' . "не" . ' ' . "существует";
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ((0 < $cnt_bl) && ($black_url != false)) {
			$row_bl = $sql_bl->fetch_assoc();
			$sql_bl->free();
			$result_text = 'ERROR';
			$message_text = "Сайт" . ' ' . $row_bl['domen'] . ' ' . "находится" . ' ' . "в" . ' ' . "черном" . ' ' . "списке" . ' ' . "проекта" . ' ' . strtoupper($_SERVER['HTTP_HOST']) . '';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ((@getHost($url) != $_SERVER['HTTP_HOST']) && (SFB_YANDEX($url) != false)) {
			$result_text = 'ERROR';
			$message_text = SFB_YANDEX($url);
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$pvis_cena_hit = SqlConfig('pvis_cena_hit', 1, 4);
		$pvis_cena_hideref = SqlConfig('pvis_cena_hideref', 1, 4);
		$pvis_cena_color = SqlConfig('pvis_cena_color', 1, 4);
		$pvis_cena_revisit[1] = SqlConfig('pvis_cena_revisit', 1, 4);
		$pvis_cena_revisit[2] = SqlConfig('pvis_cena_revisit', 2, 4);
		$pvis_cena_uniq_ip[1] = SqlConfig('pvis_cena_uniq_ip', 1, 4);
		$pvis_cena_uniq_ip[2] = SqlConfig('pvis_cena_uniq_ip', 2, 4);
		$pvis_cena_revisit[0] = 0;
		$pvis_cena_uniq_ip[0] = 0;
		$price_adv = $pvis_cena_hit + ($hide_httpref * $pvis_cena_hideref) + ($color * $pvis_cena_color) + $pvis_cena_revisit[$revisit] + $pvis_cena_uniq_ip[$uniq_ip];
		$price_adv = number_format($price_adv, 4, '.', '');
		$mysqli->query('UPDATE `tb_ads_pay_vis` SET `date_edit`=\'' . time() . '\',`title`=\'' . $title . '\',`description`=\'' . $description . '\',`url`=\'' . $url . '\',`hide_httpref`=\'' . $hide_httpref . '\',`color`=\'' . $color . '\',`revisit`=\'' . $revisit . '\',`uniq_ip`=\'' . $uniq_ip . '\',`date_reg_user`=\'' . $date_reg_user . '\',`reit_user`=\'' . $reit_user . '\',`no_ref`=\'' . $no_ref . '\',`sex_user`=\'' . $sex_user . '\',`to_ref`=\'' . $to_ref . '\',`content`=\'' . $content . '\',`geo_targ`=\'' . $country_code . '\',`price_adv`=\'' . $price_adv . '\',`ip`=\'' . $my_lastiplog . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		$result_text = 'OK';
		$message_text = '<script>setTimeout(function(){$.modalpopup("close"); location.href=\'?ads=' . $type_ads . '\';}, 1500);</script>';
		$message_text .= '<div class="msg-ok" style="margin-bottom:0px; line-height:20px;">' . "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "успешно" . ' ' . "отредактирована" . '!</div>';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'confirm-del') {
	($sql = $mysqli->query('SELECT `id`,`status`,`balance` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$balance = $row['balance'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-confirm-del' . $security_key));
		$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-del' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 1) {
			$result_text = 'ERROR';
			$message_text = "Перед" . ' ' . "удалением" . ', ' . "необходимо" . ' ' . "поставить" . ' ' . "площадку" . ' ' . "на" . ' ' . "паузу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$pvis_comis_del = SqlConfig('pvis_comis_del', 1, 0);
		$money_back = number_format(($balance * (100 - $pvis_comis_del)) / 100, 4, '.', '');
		$result_text = 'OK';
		$message_text = '<div style="text-align:center; margin:10px 0px 15px 0px; line-height:18px;">';
		$message_text .= "Вы" . ' ' . "уверены" . ' ' . "что" . ' ' . "хотите" . ' ' . "удалить" . ' ' . "рекламную" . ' ' . "площадку" . ' ' . "№" . '<b>' . $id . '</b></b>?' . ((1 <= $money_back ? '<br>' . "На" . ' ' . "Ваш" . ' ' . "рекламный" . ' ' . "счёт" . ' ' . "будет" . ' ' . "возвращено" . ' <b class="text-green">' . my_num_format($money_back, 4, '.', '`', 2) . '</b> ' . "руб" . '.' : false)) . '';
		$message_text .= '</div>';
		$message_text .= '<div style="text-align:center;">';
		$message_text .= '<span class="sd_sub green" style="min-width:30px;" onClick="FuncAdvCab(' . $id . ', \'del\', \'' . $type_ads . '\', false, \'' . $token_next . '\', \'modal\');">' . "Да" . '</span>';
		$message_text .= '<span class="sd_sub red" style="min-width:30px;" onClick="$(\'#LoadModal\').modalpopup(\'close\'); return false;">' . "Нет" . '</span>';
		$message_text .= '</div>';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'del') {
	($sql = $mysqli->query('SELECT `id`,`status`,`balance` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$balance = $row['balance'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-del' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status == 1) {
			$result_text = 'ERROR';
			$message_text = "Перед" . ' ' . "удалением" . ', ' . "необходимо" . ' ' . "поставить" . ' ' . "площадку" . ' ' . "на" . ' ' . "паузу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$pvis_comis_del = SqlConfig('pvis_comis_del', 1, 0);
		$money_back = number_format(($balance * (100 - $pvis_comis_del)) / 100, 4, '.', '');
		$mysqli->query('DELETE FROM `tb_ads_pay_vis` WHERE `id`=\'' . $id . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		$mysqli->query('DELETE FROM `tb_ads_pay_visits` WHERE `ident`=\'' . $id . '\'') or die(my_json_encode('ERROR', $mysqli->error));

		if (1 <= $money_back) {
			$mysqli->query('UPDATE `tb_users` SET `money_rb`=`money_rb`+\'' . $money_back . '\', `money_rek`=`money_rek`-\'' . $money_back . '\' WHERE `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
			$mysqli->query('INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) VALUES(\'1\',\'' . $user_name . '\',\'' . $user_id . '\',\'' . DATE('d.m.Y H:i') . '\',\'' . time() . '\',\'' . $money_back . '\',\'' . "Возврат" . ' ' . "средств" . ', ' . "удаление" . ' ' . "опл" . '. ' . "посещения" . ' ID:<b>' . $id . '</b>\',\'' . "Зачислено" . '\',\'prihod\')') or die(my_json_encode('ERROR', $mysqli->error));
		}


		$sum_user_ob = p_floor($user_money_ob, 4);
		$sum_user_rb = ((1 <= $money_back ? p_floor($user_money_rb + $money_back, 4) : p_floor($user_money_rb, 4)));
		$result_text = 'OK';
		$message_text = '<script>$("#tr-adv-' . $id . '").remove(); $("#tr-info-' . $id . '").remove(); $("#my_bal_ob").html(\'' . $sum_user_ob . '\'); $("#my_bal_rb").html(\'' . $sum_user_rb . '\'); setTimeout(function(){$.modalpopup("close");}, 5000);</script>';
		$message_text .= '<div class="msg-ok" style="margin-bottom:0px; line-height:20px;">' . "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "успешно" . ' ' . "удалена" . '.' . ((1 <= $money_back ? '<br>' . "На" . ' ' . "Ваш" . ' ' . "рекламный" . ' ' . "счёт" . ' ' . "зачислено" . ' <b>' . my_num_format($money_back, 4, '.', '`', 2) . '</b> ' . "руб" . '.' : false)) . '</div>';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if (($option == 'confirm-erase') | ($option == 'erase')) {
	($sql = $mysqli->query('SELECT `id`,`status`,`cnt_visits_now` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$cnt_visits_now = $row['cnt_visits_now'];
		$sql->free();

		if ($option == 'confirm-erase') {
			$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-confirm-erase' . $security_key));
		}
		 else {
			$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-erase' . $security_key));
		}

		$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-erase' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (($status == 0) | ($cnt_visits_now <= 0)) {
			$result_text = 'ERROR';
			$message_text = "Счетчик" . ' ' . "этой" . ' ' . "площадки" . ' ' . "уже" . ' ' . "равен" . ' ' . "нулю" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($option == 'confirm-erase') {
			$message_text = '<div style="text-align:center; margin:10px 0px 15px 0px; line-height:18px;">';
			$message_text .= "Сбросить" . ' ' . "статистику" . ' ' . "посещений" . ' ' . "рекламной" . ' ' . "площадки" . ' ID:<b>' . $id . '</b> ?';
			$message_text .= '</div>';
			$message_text .= '<div style="text-align:center;">';
			$message_text .= '<span class="sd_sub green" style="min-width:30px;" onClick="FuncAdvCab(' . $id . ', \'erase\', \'' . $type_ads . '\', false, \'' . $token_next . '\', \'modal\');">' . "Да" . '</span>';
			$message_text .= '<span class="sd_sub red" style="min-width:30px;" onClick="$(\'#LoadModal\').modalpopup(\'close\'); return false;">' . "Нет" . '</span>';
			$message_text .= '</div>';
		}
		 else {
			$mysqli->query('UPDATE `tb_ads_pay_vis` SET `cnt_visits_now`=\'0\',`date_edit`=\'' . time() . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
			$message_text = '<script>$("#adv-stat-' . $id . '").html("0"); $("#adv-erase-' . $id . '").remove(); setTimeout(function(){$.modalpopup("close");}, 5000);</script>';
			$message_text .= '<div class="msg-ok" style="margin-bottom:0px; line-height:20px;">' . "Статистика" . ' ' . "посещений" . ' ' . "успешно" . ' ' . "сброшена" . '.</div>';
		}

		$result_text = 'OK';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'info-adv') {
	($sql = $mysqli->query('SELECT * FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-info-adv' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$country_code_arr = (($row['geo_targ'] != false ? explode(', ', $row['geo_targ']) : array()));

		if (0 < count($country_code_arr)) {
			$geo_targ_txt = false;
			$i = 0;

			while ($i < count($country_code_arr)) {
				$geo_targ_txt .= '<img src="//' . $_SERVER['HTTP_HOST'] . '/img/flags/' . strtolower($country_code_arr[$i]) . '.gif" alt="' . $country_code_arr[$i] . '" title="' . $geo_code_name_arr[$country_code_arr[$i]] . '" align="absmiddle" style="margin:0; padding:0 2px 0;" />';
				++$i;
			}

			$geo_targ_txt = (($geo_targ_txt != false ? $geo_targ_txt : "Все" . ' ' . "страны"));
		}
		 else {
			$geo_targ_txt = "Все" . ' ' . "страны";
		}

		$message_text = '<div align="justify" style="margin:5px; color:#2F4F4F; font-size:11.5px;">';
		$message_text .= '<h3 class="sp" style="margin:0; font-weight:bold; font-size:12px;">' . "Площадка" . ':</h3>';
		$message_text .= '<b>' . "Заголовок" . ':</b> <span class="text-green">' . $row['title'] . '</span><br>';
		$message_text .= '<b>' . "Краткое" . ' ' . "описание" . ':</b> <span class="text-green">' . $row['description'] . '</span><br>';
		$message_text .= '<b>URL ' . "сайта" . ':</b> <a href="' . $row['url'] . '" target="_blank">' . $row['url'] . '</a>';
		$message_text .= '<h3 class="sp" style="margin:10px 0 0; font-weight:bold; font-size:12px;">' . "Настройки" . ':</h3>';
		$message_text .= "Скрытие" . ' HTTP_REFERER: <span class="text-green">' . $hide_httpref_arr[$row['hide_httpref']] . '</span><br>';
		$message_text .= "Выделение" . ' ' . "цветом" . ': <span class="text-green">' . $color_arr[$row['color']] . '</span><br>';
		$message_text .= "Доступно" . ' ' . "для" . ' ' . "просмотра" . ': <span class="text-green">' . $revisit_arr[$row['revisit']] . '</span><br>';
		$message_text .= "Уникальный" . ' IP: <span class="text-green">' . $uniq_ip_arr[$row['uniq_ip']] . '</span><br>';
		$message_text .= "По" . ' ' . "дате" . ' ' . "регистрации" . ': <span class="text-green">' . $datereg_user_arr[$row['date_reg_user']] . '</span><br>';
		$message_text .= "По" . ' ' . "рейтингу" . ' ' . "пользователя" . ': <span class="text-green">' . liststatus($row['reit_user']) . '</span><br>';
		$message_text .= "По" . ' ' . "наличию" . ' ' . "реферера" . ': <span class="text-green">' . $no_ref_arr[$row['no_ref']] . '</span><br>';
		$message_text .= "По" . ' ' . "половому" . ' ' . "признаку" . ': <span class="text-green">' . $sex_user_arr[$row['sex_user']] . '</span><br>';
		$message_text .= "Показывать" . ' ' . "только" . ' ' . "рефералам" . ': <span class="text-green">' . $to_ref_arr[$row['to_ref']] . '</span><br>';
		$message_text .= "Контент" . ' 18+: <span class="text-green">' . $content_arr[$row['content']] . '</span><br>';
		$message_text .= "Геотаргетинг" . ': <span class="text-green">' . $geo_targ_txt . '</span>';

		if (0 < $row['status']) {
			$message_text .= '<h3 class="sp" style="margin:10px 0 0; font-weight:bold; font-size:12px;">' . "Статистика" . ':</h3>';
			$message_text .= "Всего" . ' ' . "ссылка" . ' ' . "получила" . ' ' . "посещений" . ': <span class="text-grey"><b>' . number_format($row['cnt_visits_all'], 0, '.', '`') . '</b></span><br>';
			$message_text .= "Общая" . ' ' . "сумма" . ' ' . "пополнений" . ': <span class="text-green"><b>' . number_format($row['money'], 2, '.', '`') . '</b> ' . "руб" . '.</span>';
		}


		$message_text .= '</div>';
		$result_text = 'OK';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'info-bal') {
	($sql = $mysqli->query('SELECT `id` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-info-bal' . $security_key));
		$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-confirm-bal' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$pvis_min_pay = SqlConfig('pvis_min_pay', 1, 2);
		$pvis_max_pay = SqlConfig('pvis_max_pay', 1, 2);
		$message_text = '<div>' . "Укажите" . ' ' . "сумму" . ', ' . "которую" . ' ' . "вы" . ' ' . "хотите" . ' ' . "внести" . ' ' . "в" . ' ' . "бюджет" . ' ' . "рекламной" . ' ' . "площадки" . ' ID:<b>' . $id . '</b>.</div>';
		$message_text .= '<div>[ ' . "Минимум" . ' <span class="text-green"><b>' . number_format($pvis_min_pay, 2, '.', '`') . '</b> ' . "руб" . '.</span> ]</div>';
		$message_text .= '<form id="form-ins-money" method="POST" action="" onSubmit="FuncAdvCab(' . $id . ', \'confirm-bal\', \'' . $type_ads . '\', $(this).attr(\'id\'), \'' . $token_next . '\', \'modal\'); return false;">';
		$message_text .= '<input type="text" id="money_add" name="money_add" maxlength="10" value="100.00" step="any" min="' . $pvis_min_pay . '" max="' . $pvis_max_pay . '" class="payadv" required="required" autocomplete="off" onKeydowm="isMoney(this);" onKeyup="isMoney(this);">';
		$message_text .= '<input type="submit" value="' . "Оплатить" . '" class="sd_sub big orange">';
		$message_text .= '</form>';
		$result_text = 'OK';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'confirm-bal') {
	($sql = $mysqli->query('SELECT `id` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-confirm-bal' . $security_key));
		$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-add-bal' . $security_key));

		$money_pay = ((isset($_POST['money_add']) && preg_match('|^[+]?([\\d]{0,10})?(?:[\\.,][\\d]+)?$|', trim($_POST['money_add'])) ? number_format(floatval(str_ireplace(',', '.', $_POST['money_add'])), 2, '.', '') : false));
		$pvis_min_pay = SqlConfig('pvis_min_pay', 1, 2);
		$pvis_max_pay = SqlConfig('pvis_max_pay', 1, 2);

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (($money_pay == false) | ($money_pay == 0)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "указана" . ' ' . "сумма" . ' ' . "пополнения" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($money_pay < $pvis_min_pay) {
			$result_text = 'ERROR';
			$message_text = "Минимальная" . ' ' . "сумма" . ' ' . "пополнения" . ' <b>' . number_format($pvis_min_pay, 2, '.', ' ') . '</b> ' . "руб" . '.';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($pvis_max_pay < $money_pay) {
			$result_text = 'ERROR';
			$message_text = "Максимальная" . ' ' . "сумма" . ' ' . "пополнения" . ' <b>' . number_format($pvis_max_pay, 2, '.', ' ') . '</b> ' . "руб" . '.';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$message_text = '<div style="text-align:center; margin:10px 0px; line-height:20px;">';
		$message_text .= "С" . ' ' . "вашего" . ' <b><i>' . "рекламного" . '</i></b> ' . "счета" . ' ' . "будет" . ' ' . "списана" . ' ' . "сумма" . ' ' . "в" . ' ' . "размере" . ' <b class="text-green">' . number_format($money_pay, 2, '.', '`') . '</b> ' . "руб" . '.<br>';
		$message_text .= "Пополнить" . ' ' . "бюджет" . ' ' . "рекламной" . ' ' . "площадки" . ' ID:<b class="text-grey">' . $id . '</b>?';
		$message_text .= '</div>';
		$message_text .= '<div style="text-align:center;">';
		$message_text .= '<form id="form-add-money" method="POST" action="" style="display:inline-block;" onSubmit="FuncAdvCab(' . $id . ', \'add-bal\', \'' . $type_ads . '\', $(this).attr(\'id\'), \'' . $token_next . '\', \'modal\'); return false;">';
		$message_text .= '<input type="hidden" name="money_add" value="' . $money_pay . '" autocomplete="off">';
		$message_text .= '<input type="submit" value="' . "Да" . '" class="sd_sub green" style="min-width:64px;">';
		$message_text .= '</form>';
		$message_text .= '<span class="sd_sub red" style="min-width:30px;" onClick="$(\'#LoadModal\').modalpopup(\'close\'); return false;">' . "Нет" . '</span>';
		$message_text .= '</div>';
		$result_text = 'OK';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'add-bal') {
	($sql = $mysqli->query('SELECT `id`,`status`,`cnt_visits_now`,`price_adv`,`balance` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$cnt_visits_now = $row['cnt_visits_now'];
		$price_adv = $row['price_adv'];
		$balance = $row['balance'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-add-bal' . $security_key));

		$money_pay = ((isset($_POST['money_add']) && preg_match('|^[+]?([\\d]{0,10})?(?:[\\.,][\\d]+)?$|', trim($_POST['money_add'])) ? number_format(floatval(str_ireplace(',', '.', $_POST['money_add'])), 2, '.', '') : false));
		$pvis_min_pay = SqlConfig('pvis_min_pay', 1, 2);
		$pvis_max_pay = SqlConfig('pvis_max_pay', 1, 2);

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (($money_pay == false) | ($money_pay == 0)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "указана" . ' ' . "сумма" . ' ' . "пополнения" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($money_pay < $pvis_min_pay) {
			$result_text = 'ERROR';
			$message_text = "Минимальная" . ' ' . "сумма" . ' ' . "пополнения" . ' <b>' . number_format($pvis_min_pay, 2, '.', ' ') . '</b> ' . "руб" . '.';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($pvis_max_pay < $money_pay) {
			$result_text = 'ERROR';
			$message_text = "Максимальная" . ' ' . "сумма" . ' ' . "пополнения" . ' <b>' . number_format($pvis_max_pay, 2, '.', ' ') . '</b> ' . "руб" . '.';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($user_money_rb < $money_pay) {
			$result_text = 'ERROR';
			$message_text = "На" . ' ' . "Вашем" . ' ' . "рекламном" . ' ' . "счету" . ' ' . "недостаточно" . ' ' . "средств" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		($sql = $mysqli->query('SELECT `price` FROM `tb_config` WHERE `item`=\'reit_rek\'')) or die(my_json_encode('ERROR', $mysqli->error));
		$reit_rek = ((0 < $sql->num_rows ? $sql->fetch_object()->price : 0));
		$sql->free();
		($sql = $mysqli->query('SELECT `price` FROM `tb_config` WHERE `item`=\'reit_ref_rek\'')) or die(my_json_encode('ERROR', $mysqli->error));
		$reit_ref_rek = ((0 < $sql->num_rows ? $sql->fetch_object()->price : 0));
		$sql->free();
		$reit_add_1 = floor($money_pay / 10) * $reit_rek;
		$reit_add_2 = floor($money_pay / 10) * $reit_ref_rek;

		if ($user_referer_1 != false) {
			$mysqli->query('UPDATE `tb_users` SET `reiting`=`reiting`+\'' . $reit_add_2 . '\' WHERE `username`=\'' . $user_referer_1 . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		}


		$mysqli->query('UPDATE `tb_users` SET `reiting`=`reiting`+\'' . $reit_add_1 . '\',`money_rb`=`money_rb`-\'' . $money_pay . '\',`money_rek`=`money_rek`+\'' . $money_pay . '\' WHERE `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));

		if ($status == 0) {
			$mysqli->query('UPDATE `tb_ads_pay_vis` SET `status`=\'1\',`method_pay`=\'10\',`date_edit`=\'' . time() . '\',`balance`=`balance`+\'' . $money_pay . '\',`money`=\'' . $money_pay . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		}
		 else {
			$mysqli->query('UPDATE `tb_ads_pay_vis` SET `status`=\'1\',`method_pay`=\'10\',`date_edit`=\'' . time() . '\',`balance`=`balance`+\'' . $money_pay . '\',`money`=`money`+\'' . $money_pay . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		}

		$mysqli->query('INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) ' . "\r\n\t\t\t" . 'VALUES(\'1\',\'' . $user_name . '\',\'' . $user_id . '\',\'' . DATE('d.m.Y H:i') . '\',\'' . time() . '\',\'' . $money_pay . '\',\'' . "Пополнение" . ' ' . "баланса" . ' ' . "опл" . '. ' . "посещения" . ', ID:<b>' . $id . '</b>\',\'' . "Оплачено" . '\',\'reklama\')') or die(my_json_encode('ERROR', $mysqli->error));
		stat_pay('pay_visits', $money_pay);
		ads_wmid($user_wm_id, $user_wm_purse, $user_name, $money_pay);
		BonusSurf($user_name, $money_pay);
		$sum_user_ob = p_floor($user_money_ob, 4);
		$sum_user_rb = p_floor($user_money_rb - $money_pay, 4);
		$cnt_totals = floor(bcdiv($balance + $money_pay, $price_adv));
		$cnt_totals = number_format($cnt_totals, 0, '.', '`');
		$new_balance = my_num_format($balance + $money_pay, 4, '.', '`', 2);
		($sql_pos = $mysqli->query('SELECT COUNT(*) as `position` FROM `tb_ads_pay_vis` USE INDEX (`status_date_up`) WHERE `status`=\'1\' AND `date_up`>(SELECT `date_up` FROM `tb_ads_pay_vis` WHERE `id`=\'' . $id . '\')')) or die(my_json_encode('ERROR', $mysqli->error));
		$position = ((0 < $sql_pos->num_rows ? $sql_pos->fetch_object()->position + 1 : 1));
		$sql_pos->free();

		if (isset($position) && (0 < $position) && ($position < 100)) {
			$pos_class = 'adv-down';
			$pos_title = "Позиция" . ' ' . "ссылки" . ' ' . "в" . ' ' . "общем" . ' ' . "списке" . ' ' . "выдачи" . ': ' . $position;
			$pos_text = $position;
		}
		 else {
			$pos_class = 'adv-up';
			$pos_title = "Поднять" . ' ' . "в" . ' ' . "списке";
			$pos_text = '&uarr;';
		}

		$result_text = 'OK';
		$message_text = '<div id="scr-' . $id . '"><script>last_info = false; $("#my_bal_ob").html(\'' . $sum_user_ob . '\'); $("#my_bal_rb").html(\'' . $sum_user_rb . '\'); $("#adv-status-' . $id . ' span").attr({"class":"adv-pause", "title":"' . "Приостановить" . ' ' . "показ" . ' ' . "рекламной" . ' ' . "площадки" . '"}); $("#adv-stat-' . $id . '").html(\'' . $cnt_visits_now . '\'); $("#adv-totals-' . $id . '").html(\'' . $cnt_totals . '\'); $("#adv-bal-' . $id . '").attr("class", "add-money").html(\'' . $new_balance . '\'); $("#adv-up-' . $id . '").attr({class:"' . $pos_class . '", title:"' . $pos_title . '"}).html("' . $pos_text . '"); $(".tr-info").hide(); $("#text-info-' . $id . '").html(""); setTimeout(function(){$("#scr-' . $id . '").remove();},700); setTimeout(function(){$.modalpopup("close");}, 5000);</script></div>';
		$message_text .= '<div class="msg-ok" style="margin-bottom:0; line-height:20px;">' . "Баланс" . ' ' . "рекламной" . ' ' . "площадки" . ' ID:<b>' . $id . '</b> ' . "успешно" . ' ' . "пополнен" . '!</div>';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'info-up') {
	($sql = $mysqli->query('SELECT `id` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-info-up' . $security_key));
		$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-pay-up' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$pvis_cena_up = SqlConfig('pvis_cena_up', 1, 2);
		$result_text = 'OK';
		$message_text = "Ссылка" . ' ' . "будет" . ' ' . "поднята" . ' ' . "на" . ' ' . "первую" . ' ' . "позицию" . ' ' . "в" . ' ' . "списке" . ' ' . "оплачиваемых" . ' ' . "посещений" . '.<br>';
		$message_text .= "Стоимость" . ' ' . "поднятия" . ' ' . "составляет" . ' <span class="text-green"><b>' . number_format($pvis_cena_up, 2, '.', '`') . '</b> ' . "руб" . '.</span><br>' . "Эта" . ' ' . "сумма" . ' ' . "будет" . ' ' . "снята" . ' ' . "с" . ' ' . "Вашего" . ' ' . "рекламного" . ' ' . "счета" . '.';
		$message_text .= '<div style="text-align:center; margin:10px auto 0;">';
		$message_text .= '<span class="sd_sub big orange" onClick="FuncAdvCab(' . $id . ', \'pay-up\', \'' . $type_ads . '\', false, \'' . $token_next . '\', \'modal\');">' . "Поднять" . '</span>';
		$message_text .= '</div>';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'pay-up') {
	($sql = $mysqli->query('SELECT `id`,`status` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-pay-up' . $security_key));
		$pvis_cena_up = SqlConfig('pvis_cena_up', 1, 2);

		if ($status == 1) {
			($sql_pos = $mysqli->query('SELECT COUNT(*) as `position` FROM `tb_ads_pay_vis` USE INDEX (`status_date_up`) WHERE `status`=\'1\' AND `date_up`>(SELECT `date_up` FROM `tb_ads_pay_vis` WHERE `id`=\'' . $id . '\')')) or die(my_json_encode('ERROR', $mysqli->error));
			$position = ((0 < $sql_pos->num_rows ? $sql_pos->fetch_object()->position + 1 : 1));
			$sql_pos->free();
		}


		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($status != 1) {
			$result_text = 'ERROR';
			$message_text = "Для" . ' ' . "поднятия" . ' ' . "в" . ' ' . "списке" . ' ' . "ссылку" . ' ' . "необходимо" . ' ' . "запустить" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (isset($position) && ($position == 1)) {
			$result_text = 'ERROR';
			$message_text = "Нет" . ' ' . "необходимости" . ', ' . "Ваша" . ' ' . "ссылка" . ' ' . "уже" . ' ' . "находится" . ' ' . "на" . ' ' . "первой" . ' ' . "позиции" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if ($user_money_rb < $pvis_cena_up) {
			$result_text = 'ERROR';
			$message_text = "На" . ' ' . "Вашем" . ' ' . "рекламном" . ' ' . "счету" . ' ' . "недостаточно" . ' ' . "средств" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$mysqli->query('UPDATE `tb_users` SET `money_rb`=`money_rb`-\'' . $pvis_cena_up . '\' WHERE `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		$mysqli->query('UPDATE `tb_ads_pay_vis` SET `date_edit`=\'' . time() . '\',`date_up`=\'' . time() . '\' WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'') or die(my_json_encode('ERROR', $mysqli->error));
		$mysqli->query('INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) ' . "\r\n\t\t\t" . 'VALUES(\'1\',\'' . $user_name . '\',\'' . $user_id . '\',\'' . DATE('d.m.Y H:i') . '\',\'' . time() . '\',\'' . $pvis_cena_up . '\',\'' . "Поднятие" . ' ' . "в" . ' ' . "списке" . ' ' . "опл" . '. ' . "посещения" . ' ID:<b>' . $id . '</b>\',\'' . "Оплачено" . '\',\'reklama\')') or die(my_json_encode('ERROR', $mysqli->error));
		$sum_user_ob = p_floor($user_money_ob, 4);
		$sum_user_rb = p_floor($user_money_rb - $pvis_cena_up, 4);
		$pos_class = 'adv-down';
		$pos_title = "Позиция" . ' ' . "ссылки" . ' ' . "в" . ' ' . "общем" . ' ' . "списке" . ' ' . "выдачи" . ': 1';
		$pos_text = 1;
		$result_text = 'OK';
		$message_text = '<script>last_info = false; $("#my_bal_ob").html(\'' . $sum_user_ob . '\'); $("#my_bal_rb").html(\'' . $sum_user_rb . '\'); $("#adv-up-' . $id . '").attr({class:"' . $pos_class . '", title:"' . $pos_title . '"}).html("' . $pos_text . '"); $(".tr-info").hide(); $("#text-info-' . $id . '").html(""); setTimeout(function(){$.modalpopup("close");}, 5000);</script>';
		$message_text .= '<div class="msg-ok" style="margin-bottom:0; line-height:20px;">' . "Ссылка" . ' ID:<b>' . $id . '</b> ' . "успешно" . ' ' . "поднята" . ' ' . "на" . ' ' . "первую" . ' ' . "позицию" . '!</div>';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'stat-adv') {
	($sql = $mysqli->query('SELECT `id`,`status` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . 'token-stat-adv' . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$QUERY_STAT = 'SELECT `id`,`user_name`,`time`,`ip` FROM `tb_ads_pay_visits` USE INDEX (`stat_visits`) WHERE `ident`=\'' . $id . '\' AND `status`=\'1\'';
		($sql_v = $mysqli->query($QUERY_STAT)) or die(my_json_encode('ERROR', $mysqli->error));
		$count_entrys = $sql_v->num_rows;
		$sql_v->free();
		$count_pages = ceil($count_entrys / $max_entrys_stat);
		$page = 1;
		$last_id_stat = false;
		($sql_v = $mysqli->query($QUERY_STAT . ' ORDER BY `id` DESC LIMIT ' . $max_entrys_stat)) or die(my_json_encode('ERROR', $mysqli->error));

		if (0 < $sql_v->num_rows) {
			include_once ROOT_DIR . '/geoip/geoipcity.inc';
			$gi = geoip_open(ROOT_DIR . '/geoip/GeoLiteCity.dat', GEOIP_STANDARD);
			$message_text = '<script>var auto_lp = getCookie(\'' . $type_ads . '_auto_lp\')==1 ? true : false; $(document).ready(function(){if(auto_lp) $("#auto_lp").prop(\'checked\', true); $(".box-modal-content").on("scroll", function() {if(auto_lp && $("div").is("#load-pages")) {h_wt = ($(window).height() + $(window).scrollTop()); h_lp = ($("#load-pages").offset().top + $("#load-pages").innerHeight()); if(h_wt > h_lp) {FuncLP();}}});});</script>';
			$message_text .= '<div style="padding-bottom:10px;">' . "Здесь" . ' ' . "вы" . ' ' . "можете" . ' ' . "увидеть" . ' ' . "тех" . ', ' . "кто" . ' ' . "просматривал" . ' ' . "вашу" . ' ' . "рекламную" . ' ' . "площадку" . ', ' . "учтите" . ' ' . "что" . ' ' . "в" . ' ' . "списке" . ' ' . "отображаются" . ' ' . "записи" . ' ' . "которым" . ' ' . "не" . ' ' . "более" . ' ' . "одних" . ' ' . "суток" . '.</div>';
			$message_text .= '<div style="float:left; padding-bottom:5px;"><input type="checkbox" id="auto_lp" onClick="if($(this).prop(\'checked\')) {auto_lp = true; setCookie(\'' . $type_ads . '_auto_lp\', 1, \'/\', location.hostname);} else {auto_lp = false; setCookie(\'' . $type_ads . '_auto_lp\', 0, \'/\', location.hostname);}" style="height:16px; width:16px; margin:0px;"></div>';
			$message_text .= '<div style="float:left; padding-bottom:5px; padding-left:5px;">- ' . "автозагрузка" . ' ' . "данных" . ' ' . "при" . ' ' . "скролинге" . ' ' . "вниз" . '</div>';
			$message_text .= '<table class="tables" id="tab-stat-' . $id . '">';
			$message_text .= '<thead><tr align="center">';
			$message_text .= '<th width="33%">' . "Логин" . '</th>';
			$message_text .= '<th width="33%">' . "Дата" . '</th>';
			$message_text .= '<th width="34%">IP</th>';
			$message_text .= '</tr></thead>';
			$message_text .= '<tbody>';

			while ($row_v = $sql_v->fetch_assoc()) {
				$last_id_stat = $row_v['id'];
				$record = @geoip_record_by_addr($gi, $row_v['ip']);

				$country_code = ((isset($record->country_code) && ($record->country_code != false) ? $record->country_code : false));
				$country_name = (($country_code != false ? get_country($country_code) : false));
				$message_text .= '<tr id="tr-stat-' . $row_v['id'] . '">';
				$message_text .= '<td align="center"><span class="text-blue" style="cursor:help;" title="' . "Логин" . '">' . $row_v['user_name'] . '</span></td>';
				$message_text .= '<td align="center">';

				if (DATE('d.m.Y', $row_v['time']) == DATE('d.m.Y', time())) {
					$message_text .= '<span style="color:#006400;">' . "Сегодня" . '</span>, ' . DATE("в" . ' H:i', $row_v['time']);
				}
				 else if (DATE('d.m.Y', $row_v['time']) == DATE('d.m.Y', time() - (24 * 60 * 60))) {
					$message_text .= '<span style="color:#000080;">' . "Вчера" . '</span>, ' . DATE("в" . ' H:i', $row_v['time']);
				}
				 else {
					$message_text .= '<span style="color:#363636;">' . DATE('d.m.Y', $row_v['time']) . '</span> ' . DATE('H:i', $row_v['time']);
				}

				$message_text .= '</td>';
				$message_text .= '<td align="left" style="padding:2px 25px;">';

				if ($country_name != false) {
					$message_text .= '<img src="//' . $_SERVER['HTTP_HOST'] . '/img/flags/' . strtolower($country_code) . '.gif" alt="' . $country_name . '" title="' . $country_name . '" align="absmiddle" width="16" height="11" style="margin:0; padding:0 6px 0 0;" />';
				}


				$message_text .= $row_v['ip'];
				$message_text .= '</td>';
				$message_text .= '</tr>';
			}

			$message_text .= '</tbody>';
			$message_text .= '</table>';
		}
		 else {
			$message_text = '<div style="padding-bottom:10px;">' . "Здесь" . ' ' . "вы" . ' ' . "можете" . ' ' . "увидеть" . ' ' . "тех" . ', ' . "кто" . ' ' . "просматривал" . ' ' . "вашу" . ' ' . "рекламную" . ' ' . "площадку" . ', ' . "учтите" . ' ' . "что" . ' ' . "в" . ' ' . "списке" . ' ' . "отображаются" . ' ' . "записи" . ' ' . "которым" . ' ' . "не" . ' ' . "более" . ' ' . "одних" . ' ' . "суток" . '.</div>';
			$message_text .= '<table class="tables" id="tab-stat-' . $id . '">';
			$message_text .= '<thead><tr align="center">';
			$message_text .= '<th width="33%">' . "Логин" . '</th>';
			$message_text .= '<th width="33%">' . "Дата" . '</th>';
			$message_text .= '<th width="34%">IP</th>';
			$message_text .= '</tr></thead>';
			$message_text .= '<tbody>';
			$message_text .= '<tr><td align="center" colspan="3" style="padding:4px;"><b>' . "Нет" . ' ' . "данных" . ' ' . "для" . ' ' . "отображения" . '</b></td></tr>';
			$message_text .= '</tbody>';
			$message_text .= '</table>';
		}

		$sql_v->free();

		if ($max_entrys_stat < $count_entrys) {
			$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . $page . $last_id_stat . $max_entrys_stat . $security_key));
			$message_text .= '<div id="load-pages" data-id="' . $id . '" data-op="stat-adv-page" data-type="' . $type_ads . '" data-elid="tab-stat-' . $id . '" data-page="' . $page . '" data-lastid="' . $last_id_stat . '" data-token="' . $token_next . '" style="margin:10px auto 0;" onClick="FuncLP();">' . "Показать" . ' ' . "ещё" . '</div>';
		}


		$result_text = 'OK';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


if ($option == 'stat-adv-page') {
	$last_id_stat = ((isset($_POST['lastid']) && is_string($_POST['lastid']) && preg_match('|^[\\d]{1,11}$|', intval($_POST['lastid'])) ? escape(intval($_POST['lastid'])) : false));

	$last_page_stat = ((isset($_POST['page']) && is_string($_POST['page']) && preg_match('|^[\\d]{1,11}$|', intval($_POST['page'])) ? escape(intval($_POST['page'])) : false));
	($sql = $mysqli->query('SELECT `id`,`status` FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `id`=\'' . $id . '\' AND `username`=\'' . $user_name . '\'')) or die(my_json_encode('ERROR', $mysqli->error));

	if (0 < $sql->num_rows) {
		$row = $sql->fetch_assoc();
		$id = $row['id'];
		$status = $row['status'];
		$sql->free();
		$token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . $last_page_stat . $last_id_stat . $max_entrys_stat . $security_key));

		if (($token_post == false) | ($token_post != $token_check)) {
			$result_text = 'ERROR';
			$message_text = "Не" . ' ' . "верный" . ' ' . "токен" . ', ' . "обновите" . ' ' . "страницу" . '!';
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		if (($last_page_stat == false) | ($last_id_stat == false)) {
			$result_text = 'OK';
			$message_text = array('page' => '', 'lastid' => '', 'rows' => '');
			exit(my_json_encode($result_text, $message_text));
			return 1;
		}


		$QUERY_STAT = 'SELECT `id`,`user_name`,`time`,`ip` FROM `tb_ads_pay_visits` USE INDEX (`stat_visits`) WHERE `ident`=\'' . $id . '\' AND `status`=\'1\'';
		($sql_v = $mysqli->query($QUERY_STAT)) or die(my_json_encode('ERROR', $mysqli->error));
		$count_entrys = $sql_v->num_rows;
		$sql_v->free();
		$count_pages = ceil($count_entrys / $max_entrys_stat);
		$start_pos = intval($last_page_stat * $max_entrys_stat);
		$next_page_stat = false;
		$message_text = false;
		$now_rows = 0;
		($sql_v = $mysqli->query($QUERY_STAT . ' AND `id`<\'' . $last_id_stat . '\' ORDER BY `id` DESC LIMIT ' . $max_entrys_stat)) or die(my_json_encode('ERROR', $mysqli->error));

		if (0 < $sql_v->num_rows) {
			include_once ROOT_DIR . '/geoip/geoipcity.inc';
			$gi = geoip_open(ROOT_DIR . '/geoip/GeoLiteCity.dat', GEOIP_STANDARD);

			while ($row_v = $sql_v->fetch_assoc()) {
				++$now_rows;
				$last_id_stat = $row_v['id'];
				$record = @geoip_record_by_addr($gi, $row_v['ip']);

				$country_code = ((isset($record->country_code) && ($record->country_code != false) ? $record->country_code : false));
				$country_name = (($country_code != false ? get_country($country_code) : false));
				$message_text .= '<tr id="tr-stat-' . $row_v['id'] . '">';
				$message_text .= '<td align="center"><span class="text-blue" style="cursor:help;" title="' . "Логин" . '">' . $row_v['user_name'] . '</span></td>';
				$message_text .= '<td align="center">';

				if (DATE('d.m.Y', $row_v['time']) == DATE('d.m.Y', time())) {
					$message_text .= '<span style="color:#006400;">' . "Сегодня" . '</span>, ' . DATE("в" . ' H:i', $row_v['time']);
				}
				 else if (DATE('d.m.Y', $row_v['time']) == DATE('d.m.Y', time() - (24 * 60 * 60))) {
					$message_text .= '<span style="color:#000080;">' . "Вчера" . '</span>, ' . DATE("в" . ' H:i', $row_v['time']);
				}
				 else {
					$message_text .= '<span style="color:#363636;">' . DATE('d.m.Y', $row_v['time']) . '</span> ' . DATE('H:i', $row_v['time']);
				}

				$message_text .= '</td>';
				$message_text .= '<td align="left" style="padding:2px 25px;">';

				if ($country_name != false) {
					$message_text .= '<img src="//' . $_SERVER['HTTP_HOST'] . '/img/flags/' . strtolower($country_code) . '.gif" alt="' . $country_name . '" title="' . $country_name . '" align="absmiddle" width="16" height="11" style="margin:0; padding:0 6px 0 0;" />';
				}


				$message_text .= $row_v['ip'];
				$message_text .= '</td>';
				$message_text .= '</tr>';
			}

			$last_id_stat = (($max_entrys_stat <= $now_rows ? $last_id_stat : false));
			$next_page_stat = (($last_id_stat != false ? $last_page_stat + 1 : false));
		}
		 else {
			$last_id_stat = false;
			$next_page_stat = false;
			$message_text = false;
		}

		$sql_v->free();
		$token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER['HTTP_HOST'] . $next_page_stat . $last_id_stat . $max_entrys_stat . $security_key));
		$message_text = array('page' => $next_page_stat, 'lastid' => $last_id_stat, 'rows' => $message_text, 'token' => $token_next);
		$result_text = 'OK';
		exit(my_json_encode($result_text, $message_text));
		return 1;
	}


	$sql->free();
	$result_text = 'ERROR';
	$message_text = "Рекламная" . ' ' . "площадка" . ' ID:<b>' . $id . '</b> ' . "не" . ' ' . "найдена" . '!';
	exit(my_json_encode($result_text, $message_text));
	return 1;
}


$result_text = 'ERROR';
$message_text = 'Option [' . $option . '] not found...';
exit(my_json_encode($result_text, $message_text));
?>