<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");
sleep(0);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? trim($_SESSION["userLog"]) : false;
	$referal_id = (isset($_POST["us_id"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_POST["us_id"])))) ? intval($_POST["us_id"]) : false;
	$token = (isset($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_POST["token"]))) ? trim($_POST["token"]) : false;

	//mysql_query("INSERT INTO `tb_test` (`text`) VALUES('AJAX_YES: $username; $referal_id; $token')");

	//$sql_user = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$referal_id' AND `referer`='$username'");
	$sql_user = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$referal_id' ") or die(mysql_error());
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_assoc($sql_user);
		$referal_id = $row_user["id"];
		$referal_name = $row_user["username"];
	}else{
		exit("ERROR");
	}

	$day_week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
	$day_week_arr_ru = array("ВС","ПН","ВТ","СР","ЧТ","ПТ","СБ");
	$date_start = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
	$date_end = strtotime(DATE("d.m.Y"));
	$period = (24*60*60);
	$type_arr = array(
		'serf' => '<span style="color: #16AA10;">Серфинг</span>', 
		'auto_serf' => '<span style="color: #CD5C5C;">Авто-серфинг</span>', 
		'ban_serf' => '<span style="color: #FA8072;">Баннерный серфинг</span>', 
		'fls' => '<span style="color: #8A2BE2;">Скачивания</span>', 
		'quest' => '<span style="color: #F1B05B;">Вопросы</span>', 
                'tests' => '<span style="color: #42aaff;">ТЕСТЫ</span>',
		'mails' => '<span style="color: #07AF9B;">Письма</span>', 
		'task' => '<span style="color: #FD0606;">Задания</span>',
		'you' => '<span style="color: #0000ff;">YOUTUBE-СЕРФИНГ</span>',
		'autoyoutube_ser' => '<span style="color: #CD5C5C;">АВТО-СЕРФИНГ YOUTUBE</span>',
		'pay_visits' => '<span style="color: #0000ff;">Оплачиваемые посещения</span>'
	);
	$type_arr_color = array(
		'serf' => 'style="color: #16AA10;"', 
		'auto_serf' => 'style="color: #CD5C5C;"', 
		'ban_serf' => 'style="color: #FA8072;"', 
		'fls' => 'style="color: #8A2BE2;"', 
		'quest' => 'style="color: #F1B05B;"', 
                'tests' => 'style="color: #42aaff;"', 
		'mails' => 'style="color: #07AF9B;"', 
		'task' => 'style="color: #FD0606;"',
		'you' => 'style="color: #0000ff;"',
		'autoyoutube_ser' => 'style="color: #CD5C5C"',
		'pay_visits' => 'style="color: #0000ff;"'
	);

/*
	echo '<table class="tables" style="margin-top:5px;">';
		echo '<thead><tr><th>Статистика пользователя <b>'.$referal_name.'</b></th></tr></thead>';
	echo '</table>';
*/
	echo '<table class="tables" style="margin-top:1px;">';
	echo '<thead><tr>';
		echo '<th></th>';
		echo '<th style="width:12%;">Всего</th>';
		//echo '<th style="width:10%;">За месяц</th>';
		for($i=$date_start; $i<=$date_end; $i=$i+$period) {
			if(DATE("d.m.Y", $i)=="26.10.2014") $i = $i + 60*60;
			if(DATE("w", $i)==DATE("w")) echo '<th style="width:8%; background: #0000FF;">'.$day_week_arr_ru[strtolower(DATE("w", $i))].'</th>';
			else echo '<th style="width:8%;">'.$day_week_arr_ru[strtolower(DATE("w", $i))].'</th>';
		}
		echo '</tr>';
	echo '</thead>';
	$sql_stat_us = mysql_query("SELECT * FROM `tb_users_stat` WHERE `username`='$referal_name' ORDER BY `id` ASC");
	if(mysql_num_rows($sql_stat_us)>0) {
		while ($row_stat_us = mysql_fetch_array($sql_stat_us)) {
			$style_color = $type_arr_color[$row_stat_us["type"]];

			echo '<tr>';
				echo '<td align="center" '.$style_color.'>'.$type_arr[$row_stat_us["type"]].'</td>';
				echo '<td align="center" '.$style_color.'>'.$row_stat_us["all_views"].'</td>';
				//echo '<td align="center" '.$style_color.'>'.$row_stat_us["month"].'</td>';
				for($i=$date_start; $i<=$date_end; $i=$i+$period) {
					if(DATE("d.m.Y", $i)=="26.10.2014") $i = $i + 60*60;
					echo '<td align="center" '.$style_color.'>'.$row_stat_us[$day_week_arr_en[strtolower(DATE("w", $i))]].'</td>';
				}
			echo '</tr>';
		}
	}
	echo '</table><br>';
}else{
	echo '<span class="msg-error">Необходимо авторизоваться!</span>';
}

?>