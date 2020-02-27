<?php
$pagetitle = "Статистика проекта";
include('header.php');
require('config.php');

$month_ru_arr = array("", "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
$yes_no_arr = array('<span style="color:#FF0000;">Откл.</spna>', '<span style="color:green;">Авто</spna>', '<span style="color:#006699;">Вручную</span>');

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
$dlink_cena_hits_bs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_active' AND `howmany`='1'");
$dlink_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_ot' AND `howmany`='1'");
$dlink_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_do' AND `howmany`='1'");
$dlink_timer_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_timer' AND `howmany`='1'");
$dlink_cena_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_hits' AND `howmany`='1'");
$youtube_cena_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_active' AND `howmany`='1'");
$youtube_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_ot' AND `howmany`='1'");
$youtube_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_do' AND `howmany`='1'");
$youtube_timer_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_timer' AND `howmany`='1'");
$youtube_cena_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
$timer_aserf_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
$timer_aserf_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_timer_aserf' AND `howmany`='1'");
$cena_timer_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='1'");
$cena_mails_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='3'");
$cena_mails_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
$cena_mails_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
$cena_mails_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
$cena_mails_gotosite = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
$cena_task = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_quest' AND `howmany`='1'");
$tests_cena_quest = number_format(mysql_result($sql,0,0), 4, ".", "");

$tests_cena_hit_min = number_format($tests_cena_hit/(1+$tests_nacenka/100), 4, ".", "");
$tests_cena_hit_max = number_format(($tests_cena_hit_min + 2*$tests_cena_quest/(1+$tests_nacenka/100)), 4, ".", "");

//$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_max' AND `eps`='wm'");
			//$pay_max[wm] = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		$r_c_1_arr[] = $row["c_1"];
		$r_c_2_arr[] = $row["c_2"];
		$r_c_3_arr[] = $row["c_3"];
		//$r_c_4_arr[] = $row["c_4"];
		//$r_c_5_arr[] = $row["c_5"];
		$r_m_1_arr[] = $row["m_1"];
		$r_m_2_arr[] = $row["m_2"];
		$r_m_3_arr[] = $row["m_3"];
		//$r_m_4_arr[] = $row["m_4"];
		//$r_m_5_arr[] = $row["m_5"];
		$r_t_1_arr[] = $row["t_1"];
		$r_t_2_arr[] = $row["t_2"];
		$r_t_3_arr[] = $row["t_3"];
		//$r_t_4_arr[] = $row["t_4"];
		//$r_t_5_arr[] = $row["t_5"];
		$r_test_1_arr[] = $row["test_1"];
		$r_test_2_arr[] = $row["test_2"];
		$r_test_3_arr[] = $row["test_3"];
		//$r_test_4_arr[] = $row["test_4"];
		//$r_test_5_arr[] = $row["test_5"];
		$r_youtube_1_arr[] = $row["youtube_1"];
		$r_youtube_2_arr[] = $row["youtube_2"];
		$r_youtube_3_arr[] = $row["youtube_3"];
		//$r_youtube_4_arr[] = $row["youtube_4"];
		//$r_youtube_5_arr[] = $row["youtube_5"];
	}
}

$cena_click_max_dlink = ($dlink_cena_hits + $dlink_cena_active + abs($dlink_timer_do-$dlink_timer_ot) * $dlink_cena_timer);
$cena_click_max_youtube = ($youtube_cena_hits + $youtube_cena_active + abs($youtube_timer_do-$youtube_timer_ot) * $youtube_cena_timer);
$cena_click_max_bserf = ($dlink_cena_hits_bs + $dlink_cena_active + abs($dlink_timer_do-$dlink_timer_ot) * $dlink_cena_timer);
$cena_click_max_aserf = ($cena_hits_aserf + abs($timer_aserf_do-$timer_aserf_ot) * $cena_timer_aserf);
$cena_click_max_mails = ($cena_mails_1 + $cena_mails_color + $cena_mails_active + $cena_mails_gotosite);

$sql_paywait = mysql_query("SELECT `id` FROM `tb_history` WHERE `status_pay`='0' AND `method` IN ('WebMoney','YandexMoney','PerfectMoney','Payeer','Qiwi','Mobile','SberBank','PayPal') AND `status`='' AND `tipo`='0' ORDER BY `id` ASC");
$paywait = mysql_num_rows($sql_paywait);

$_ARR_EPS = array("wm", "ym", "pm", "pe", "qw", "sb", "ac", "me", "vs", "ms", "be", "mt", "mg", "tl");
foreach($_ARR_EPS as $val) {
	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_status' AND `eps`='$val'") or die(mysql_error());
	$pay_status[$val] = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_min' AND `eps`='$val'") or die(mysql_error());
	$pay_min[$val] = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_max' AND `eps`='$val'") or die(mysql_error());
			$pay_max[$val] = number_format(mysql_result($sql,0,0), 2, ".", "");
}

echo '<table class="tables_inv">';

echo '<thead><tr><th align="center" colspan="2">Общая нформация</th></tr></thead>';
echo '<tr>';
	echo '<td align="left" width="40%">Дата открытия проекта</td>';
	echo '<td align="left" width="60%">';
		echo DATE("j", strtotime($start_date)).' ';
		$n_d = intval(DATE("n", strtotime($start_date)));
		echo $month_ru_arr[$n_d].' ';
		echo DATE("Yг.", strtotime($start_date)).' ';
		echo '('.site_work($start_time,2).')';
	echo '</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Реф. система</td>';
	echo '<td align="left">III уровня</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Всего пользователей</td>';
	echo '<td align="left">'.number_format($users_all,0,"."," ").'</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Новых сегодня</td>';
	echo '<td align="left">'.number_format($users_new_s,0,"."," ").'</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Новых вчера</td>';
	echo '<td align="left">'.number_format($users_new_v,0,"."," ").'</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Просмотрено сайтов</td>';
	echo '<td align="left">'.number_format($sumvisits,0,"."," ").'</td>';
echo '</tr>';

echo '<thead><tr><th align="center" colspan="2">Заработок</th></tr></thead>';
echo '<tr>';
	echo '<td align="left">Серфинг</td>';
	echo '<td align="left">от '.number_format($dlink_cena_hits,4,".","").' &mdash; до '.number_format($cena_click_max_dlink,4,".","").' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Серфинг <b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b</td>';
	echo '<td align="left">от '.number_format($youtube_cena_hits,4,".","").' &mdash; до '.number_format($cena_click_max_youtube,4,".","").' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Баннерный серфинг</td>';
	echo '<td align="left">от '.number_format($dlink_cena_hits_bs,4,".","").' &mdash; до '.number_format($cena_click_max_bserf,4,".","").' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Авто-серфинг</td>';
	echo '<td align="left">от '.number_format($cena_hits_aserf,4,".","").' &mdash; до '.number_format($cena_click_max_aserf,4,".","").' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Письма</td>';
	echo '<td align="left">от '.number_format($cena_mails_3,4,".","").' &mdash; до '.number_format($cena_click_max_mails,4,".","").' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Тесты</td>';
	echo '<td align="left">от '.$tests_cena_hit_min.' &mdash; до '.$tests_cena_hit_max.' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Задания</td>';
	echo '<td align="left">от '.number_format($cena_task,4,".","").' руб.</td>';
echo '</tr>';

echo '<thead><tr><th align="center" colspan="2">Доход от рефералов I,II,III уровня</th></tr></thead>';
echo '<tr>';
	echo '<td align="left">Серфинг, баннерный серфинг</td>';
	echo '<td align="left">от '.$r_c_1_arr[0].'-'.$r_c_2_arr[0].'-'.$r_c_3_arr[0].'% &mdash; до '.$r_c_1_arr[9].'-'.$r_c_2_arr[9].'-'.$r_c_3_arr[9].'%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b</td>';
	echo '<td align="left">от '.$r_youtube_1_arr[0].'-'.$r_youtube_2_arr[0].'-'.$r_youtube_3_arr[0].'% &mdash; до '.$r_youtube_1_arr[9].'-'.$r_youtube_2_arr[9].'-'.$r_youtube_3_arr[9].'%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Письма</td>';
	echo '<td align="left">от '.$r_m_1_arr[0].'-'.$r_m_2_arr[0].'-'.$r_m_3_arr[0].'% &mdash; до '.$r_m_1_arr[9].'-'.$r_m_2_arr[9].'-'.$r_m_3_arr[9].'%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Тесты</td>';
	echo '<td align="left">от '.$r_test_1_arr[0].'-'.$r_test_2_arr[0].'-'.$r_test_3_arr[0].'% &mdash; до '.$r_test_1_arr[9].'-'.$r_test_2_arr[9].'-'.$r_test_3_arr[9].'%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Задания</td>';
	echo '<td align="left">от '.$r_t_1_arr[0].'-'.$r_t_2_arr[0].'-'.$r_t_3_arr[0].'% &mdash; до '.$r_t_1_arr[9].'-'.$r_t_2_arr[9].'-'.$r_t_3_arr[9].'%</td>';
echo '</tr>';

echo '<thead><tr><th align="center" colspan="2">Финансы</th></tr></thead>';
echo '<tr>';
	echo '<td align="left">Валюта проекта</td>';
	echo '<td align="left">WMR</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Платежные системы</td>';
	echo '<td align="left">';
		echo '<span style="color:#006699;">WebMoney</span>, ';
		echo '<span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span>, ';
		echo '<span style="color:#FC8000;">QIWI</span>, ';
		echo '<span style="color:#3498DB">Mega</span><span>Kassa</span>, ';
		echo '<span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span>, ';
		echo '<span style="color:#DE1200;">Я</span>ндекс.Деньги<br>';
		//echo '<span style="color:#000000">Wallet</span><span style="color:#DE1200">One</span>, ';
		echo '<span style="color:#3498DB">ROBO</span><span style="color:#DE1200">KASSA</span>, ';
		echo '<span style="color:#DE1200">PerfectMoney</span>, ';
		echo '<span style="color:#006699">Free</span><span style="color:#DE1200">Kassa</span>';
	echo '</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#006699;">WebMoney</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["wm"]].'</b></b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#DE1200;">Я</span>ндекс.Деньги</b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["ym"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#DE1200;">PerfectMoney</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["pm"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["pe"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["ac"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#FC8000;">QIWI кошелек</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["qw"]].'</b></td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["me"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#1c0cf5">VISA</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["vs"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["ms"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#0f1010">Би</span><span style="color:#e0b806">ла</span><span style="color:#0f1010">йн</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["be"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#ff0000; font-weight:bold;">МТС</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["mt"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#049C64">Мега</span><span style="color:#00649e">фон</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["mg"]].'</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Выплаты <span style="color:#0c0c0c">TELE2</span></b></td>';
	echo '<td align="left"><b>'.$yes_no_arr[$pay_status["tl"]].'</b></td>';
echo '</tr>';


echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:blue;">WebMoney</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["wm"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:blue;">WebMoney</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["wm"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#DE1200">Я</span>ндекс.Деньги</b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["ym"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#DE1200">Я</span>ндекс.Деньги</td>';
	echo '<td align="left"><b>'.number_format($pay_max["ym"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#DE1200;">PerfectMoney</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["pm"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#DE1200">PerfectMoney</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["pm"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["pe"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["pe"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["ac"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["ac"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#FC8000;">QIWI кошелек</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["qw"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#FC8000;">QIWI кошелек</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["qw"], 2, ".", "").' руб.</b></td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["me"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["me"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#1c0cf5">VISA</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["vs"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#1c0cf5">VISA</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["vs"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["ms"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["ms"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#0f1010">Би</span><span style="color:#e0b806">ла</span><span style="color:#0f1010">йн</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["be"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#0f1010">Би</span><span style="color:#e0b806">ла</span><span style="color:#0f1010">йн</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["be"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#ff0000; font-weight:bold;">МТС</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["mt"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#ff0000; font-weight:bold;">МТС</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["mt"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#049C64">Мега</span><span style="color:#00649e">фон</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["mg"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#049C64">Мега</span><span style="color:#00649e">фон</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["mg"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Минимум к выплате <span style="color:#0c0c0c">TELE2</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_min["tl"], 2, ".", "").' руб.</b></td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>Максимум к выплате <span style="color:#0c0c0c">TELE2</span></b></td>';
	echo '<td align="left"><b>'.number_format($pay_max["tl"], 2, ".", "").' руб.</b></td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left">Авто-размещение рекламы</td>';
	echo '<td align="left">Да</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Авто-рефбэк</td>';
	echo '<td align="left">Да</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Всего выплачено</td>';
	echo '<td align="left">'.number_format($sumpay,2,"."," ").' руб.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left">Количество выплат</td>';
	echo '<td align="left">'.number_format($payall,0,"."," ").'</td>';
echo '</tr>';

echo '</table>';


include('footer.php');
?>