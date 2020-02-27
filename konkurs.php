<?php
require_once('.zsecurity.php');
$pagetitle = "Конкурсы";
include('header.php');
require('config.php');

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='status'");
$konk_ads_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_start'");
$konk_ads_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_end'");
$konk_ads_date_end = mysql_result($sql,0,0);

### КОНКУРС РЕКЛАМОДАТЕЛЕЙ №2 ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='status'");
$konk_ads_big_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_start'");
$konk_ads_big_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_end'");
$konk_ads_big_date_end = mysql_result($sql,0,0);
### КОНКУРС РЕКЛАМОДАТЕЛЕЙ №2 ###

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='date_start'");
$konk_ref_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='date_end'");
$konk_ref_date_end = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='status'");
$konk_ref_status = mysql_result($sql,0,0);


$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='status'");
$konk_click_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_start'");
$konk_click_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_end'");
$konk_click_date_end = mysql_result($sql,0,0);

### КОНКУРС ТЕСТОВ ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='status'");
$konk_test_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_start'");
$konk_test_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_end'");
$konk_test_date_end = mysql_result($sql,0,0);
### КОНКУРС ТЕСТОВ ###

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='status'");
$konk_youtub_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_start'");
$konk_youtub_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_end'");
$konk_youtub_date_end = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
$konk_task_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
$konk_task_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
$konk_task_date_end = mysql_result($sql,0,0);

### КОНКУРС ПОСЕТИТЕЛЕЙ ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='status'");
$konk_hit_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='date_start'");
$konk_hit_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='date_end'");
$konk_hit_date_end = mysql_result($sql,0,0);
### КОНКУРС ПОСЕТИТЕЛЕЙ ###

### КОНКУРС ПО РАЗМЕЩЕНИЮ ССЫЛОК В СЕРФИНГЕ ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='status'");
$konk_serf_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_start'");
$konk_serf_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_end'");
$konk_serf_date_end = mysql_result($sql,0,0);
### КОНКУРС ПО РАЗМЕЩЕНИЮ ССЫЛОК В СЕРФИНГЕ ###

### КОМПЛЕКСНЫЙ КОНКУРС ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'");
$konk_complex_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'");
$konk_complex_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'");
$konk_complex_date_end = mysql_result($sql,0,0);
### КОМПЛЕКСНЫЙ КОНКУРС ###

### КОНКУРС По оплате заданий ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='status'");
$konk_ads_task_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='date_start'");
$konk_ads_task_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='date_end'");
$konk_ads_task_date_end = mysql_result($sql,0,0);
### КОНКУРС По оплате заданий ###

### КОНКУРС ПО ЗАРАБОТКУ РЕФЕРЕРУ ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='status'");
$konk_clic_ref_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='date_start'");
$konk_clic_ref_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='date_end'");
$konk_clic_ref_date_end = mysql_result($sql,0,0);
### КОНКУРС ПО ЗАРАБОТКУ РЕФЕРЕРУ ###

### КОНКУРС ПО ЗАРАБОТКУ РЕФЕРЕРУ ПРИЗЫ РЕФЕРАЛАМ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='status'");
$konk_best_ref_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_start'");
$konk_best_ref_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_end'");
$konk_best_ref_date_end = mysql_result($sql,0,0);
### КОНКУРС ПО ЗАРАБОТКУ РЕФЕРЕРУ ПРИЗЫ РЕФЕРАЛАМ ###

$type_kon = ( isset($_GET["type"])  && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|",  limpiar(htmlspecialchars(trim($_GET["type"])))) ) ? limpiar(htmlspecialchars(trim($_GET["type"]))) : "ads";

echo '<table class="tables">';
echo '<tr align="center">';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ref" '.($type_kon=="ref" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Лучший реферер</a></b>';
		echo ( ($konk_ref_status==1 && $konk_ref_date_start<=time() && $konk_ref_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ads" '.($type_kon=="ads" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Рекламодателей №1</a></b>';
		echo ( ($konk_ads_status==1 && $konk_ads_date_start<=time() && $konk_ads_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	echo '<td height="40" width="33.3%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ads_big" '.($type_kon=="ads_big" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Рекламодателей №2</a></b>';
		echo ( ($konk_ads_big_status==1 && $konk_ads_big_date_start<=time() && $konk_ads_big_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	
echo '</tr>';
echo '<tr align="center">';
echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=click" '.($type_kon=="click" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Кликеров</a></b>';
		echo ( ($konk_click_status==1 && $konk_click_date_start<=time() && $konk_click_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
    echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=task" '.($type_kon=="task" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Выполнение заданий</a></b>';
		echo ( ($konk_task_status==1 && $konk_task_date_start<=time() && $konk_task_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
    echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=youtub" '.($type_kon=="youtub" ? 'style="cursor:pointer; color:#FF0000;"' : false).'><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинг</a></b>';
		echo ( ($konk_youtub_status==1 && $konk_youtub_date_start<=time() && $konk_youtub_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	
	echo '</tr>';
echo '<tr align="center">';
//echo '<td height="40" width="25%" nowrap="nowrap">';
		//echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=test" '.($type_kon=="test" ? 'style="cursor:pointer; color:#FF0000;"' : false).'> Прохождение тестов</a></b>';
		//echo ( ($konk_test_status==1 && $konk_test_date_start<=time() && $konk_test_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	//echo '</td>';
	echo '<td height="40" width="25%" nowrap="nowrap" colspan="2">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=serf" '.($type_kon=="serf" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>По размещению ссылок в серфинге</a></b>';
		echo ( ($konk_serf_status==1 && $konk_serf_date_start<=time() && $konk_serf_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	echo '<td height="40" width="30%" nowrap="nowrap" colspan="3">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=complex" '.($type_kon=="complex" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Комплексный</a></b>';
		echo ( ($konk_complex_status==1 && $konk_complex_date_start<=time() && $konk_complex_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>'; 
	
	/*echo '<td height="40" width="30%" nowrap="nowrap" colspan="0">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=hit" '.($type_kon=="hit" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Посетителей</a></b>';
		echo ( ($konk_hit_status==1 && $konk_hit_date_start<=time() && $konk_hit_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>'; */
	
//	echo '</tr>';
	//echo '<tr align="center">';
	//echo '<td height="40" width="33.3%" nowrap="nowrap" >';
		//echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ads_task" '.($type_kon=="ads_task" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>По оплате заданий</a></b>';
		//echo ( ($konk_ads_task_status==1 && $konk_ads_task_date_start<=time() && $konk_ads_task_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	echo '</tr>';
	/*echo '<tr align="center">';
	echo '<td height="40" width="25%" nowrap="nowrap" >';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=clic_ref" '.($type_kon=="clic_ref" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Лучший реферер</a></b>';
		echo ( ($konk_clic_ref_status==1 && $konk_clic_ref_date_start<=time() && $konk_clic_ref_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=best_ref" '.($type_kon=="best_ref" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>Лучший реферал</a></b>';
		echo ( ($konk_best_ref_status==1 && $konk_best_ref_date_start<=time() && $konk_best_ref_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	
		//echo '</tr>';
	
	//echo '<tr align="center">';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=serf" '.($type_kon=="serf" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>По размещению ссылок в серфинге</a></b>';
		echo ( ($konk_serf_status==1 && $konk_serf_date_start<=time() && $konk_serf_date_end>=time()) ? '<div style="color:#ff7f50;">[Активный]</div>' : '<div style="color:#CD5C5C;">[Нет активного]</div>');
	echo '</td>';
	echo '</tr>';*/
echo '</table><br>';

$domen = ($_SERVER["HTTP_HOST"]);
$MY_USERNAME = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

function get_avatar($USER_WMID) {
	$sql_us = mysql_query("SELECT `avatar` FROM `tb_users` WHERE `wmid`='$USER_WMID'");
	if(mysql_num_rows($sql_us)>0) {
		$row_us = mysql_fetch_row($sql_us);
		return $row_us["0"];
	}else{
		return "no.png";
	}
}

function count_text($count, $text1, $text2, $text3, $text) {
	if($count>0) {
		if( ($count>=10) && ($count<=20) ) {
			return "<b>$count</b>$text1";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "<b>$count</b>$text2"; break;
				case 2: case 3: case 4: return "<b>$count</b>$text3"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b>$text1"; break;
			}
		}
	}
}


if($type_kon=="ads") {
	### КОНКУРС РЕКЛАМОДАТЕЛЕЙ №1 ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='title'");
	$konkurs_title_ads = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='min_do'");
	$konk_ads_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='all_count_prize'");
	$konk_ads_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='count_prize'");
	$konk_ads_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='type_prize'");
	$konk_ads_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ads_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ads_status==1 && $konk_ads_date_end>=(time()) && $konk_ads_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ads!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ads.'</h1>';

               
		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс рекламодателей</b></h3>';
		echo '<b>Условия проведения конкурса:</b> Для участия в конкурсе необходимо быть зарегистрированным пользователем проекта <b><a href="/">'.$domen.'</a></b>. В конкурсе учитывается сумма, потраченная на размещение любой рекламы и переведенная на счет проекта с рекламного счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте.<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_ads_all_count_prize.'</b><br>';
		echo 'Минимальная потраченная сумма для участия в конкурсе - <b>'.number_format($konk_ads_min,2,'.',' ').' руб.</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_all_count_prize; $i++) {
				if($konk_ads_prizes[$y][$i]==0) {
					$_Ads_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;на&nbsp;осн.&nbsp;счет", "&nbsp;руб.&nbsp;на&nbsp;осн.&nbsp;счет", "&nbsp;руб.&nbsp;на&nbsp;осн.&nbsp;счет", "");
					if($y==2) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;на&nbsp;рекл.&nbsp;счет", "&nbsp;руб.&nbsp;на&nbsp;рекл.&nbsp;счет", "&nbsp;руб.&nbsp;на&nbsp;рекл.&nbsp;счет", "");
					if($y==3) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
					if($y==4) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");
					if($y==5) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "%&nbsp;от&nbsp;потраченной&nbsp;суммы&nbsp;", "%&nbsp;от&nbsp;потраченной&nbsp;суммы&nbsp;", "%&nbsp;от&nbsp;потраченной&nbsp;суммы&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_ads_all_count_prize; $i++) {
			$_Ads_Prizes_New = $_Ads_Prizes[1][$i]."|".$_Ads_Prizes[2][$i]."|".$_Ads_Prizes[5][$i]."|".$_Ads_Prizes[3][$i]."|".$_Ads_Prizes[4][$i];
			$_Ads_Prizes_New = explode("|", $_Ads_Prizes_New);
			$_Ads_Prizes_New = trim(implode(" ", $_Ads_Prizes_New));
			$_Ads_Prizes_New = str_replace("  ", " ", $_Ads_Prizes_New);
			$_Ads_Prizes_New = str_replace(" ", "&nbsp;", $_Ads_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Ads_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_ads_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_ads_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">Место</th><th class="top" colspan="2">Пользователь</th><th class="top">Потрачено на рекламу</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ads`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ads_all_count_prize && $row["1"]>=$konk_ads_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],2,'.',' ').'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.$row["0"].'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],2,'.',' ').'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ads`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],2,'.',' ').'</b></td>';
						echo '</tr>';
					}
				}
			}

		echo '</table><br><br>';
	}


	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ads' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Потрачено на рекламу</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 2, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                          
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
echo '</div>';
	### КОНКУРС РЕКЛАМОДАТЕЛЕЙ №1 ###

}elseif($type_kon=="ads_big") {
	### КОНКУРС РЕКЛАМОДАТЕЛЕЙ №2 ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='title'");
	$konkurs_title_ads_big = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='min_do'");
	$konk_ads_big_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='all_count_prize'");
	$konk_ads_big_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='count_prize'");
	$konk_ads_big_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='type_prize'");
	$konk_ads_big_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ads_big_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ads_big_status==1 && $konk_ads_big_date_end>=(time()) && $konk_ads_big_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ads_big!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ads_big.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: Активный рекламодатель</b></h3>';
		echo '<b>Условия проведения конкурса:</b> Для участия в конкурсе необходимо быть зарегистрированным пользователем проекта <b><a href="/">'.$domen.'</a></b>. В конкурсе учитывается сумма, потраченная на размещение любой рекламы и переведенная на счет проекта с рекламного счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте.<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_ads_big_all_count_prize.'</b><br>';
		echo 'Минимальная потраченная сумма для участия в конкурсе - <b>'.number_format($konk_ads_big_min, 2, ".", "`").' руб.</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_big_all_count_prize; $i++) {
				if($konk_ads_big_prizes[$y][$i]==0) {
					$_Ads_Big_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],2, ".", "`"), " руб.[осн.счет]", " руб.[осн.счет]", " руб.[осн.счет]", "");
					if($y==2) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],2, ".", "`"), " руб.[рекл.счет]", " руб.[рекл.счет]", " руб.[рекл.счет]", "");
					if($y==3) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],0, ".", "`"), " % от потраченной суммы[осн.счет]", " % от потраченной суммы[осн.счет]", " % от потраченной суммы[осн.счет]", "");
					if($y==4) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],0, ".", "`"), " % от потраченной суммы[рекл.счет]", " % от потраченной суммы[рекл.счет]", " % от потраченной суммы[рекл.счет]", "");
					if($y==5) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],0, ".", "`"), " баллов", " балл", " балла", "");
				}
			}
		}

		for($i=0; $i<$konk_ads_big_all_count_prize; $i++) {
			$_Ads_Big_Prizes_New = array();
			for($y=1; $y<=count($_Ads_Big_Prizes); $y++) {
				if($_Ads_Big_Prizes[$y][$i]!=false) $_Ads_Big_Prizes_New[].= $_Ads_Big_Prizes[$y][$i];
			}
			$_Ads_Big_Prizes_New = implode(" +&nbsp;", $_Ads_Big_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Ads_Big_Prizes_New.'<br><br>';
		}

		echo '<b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_ads_big_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_ads_big_date_end-1).'';

		echo '<table class="tables_inv">';
			echo '<thead><tr align="center">';
				echo '<th>Место</th>';
				echo '<th colspan="2">Пользователь</th>';
				echo '<th>Потрачено на рекламу</th>';
				echo '<th>Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ads_big`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ads_big_all_count_prize && $row["konkurs_ads_big"]>=$konk_ads_big_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_ads_big_prizes[1][$idkonc-1] + $row["konkurs_ads_big"] * $konk_ads_big_prizes[3][$idkonc-1]/100);
						$sum_prize_rb = ($konk_ads_big_prizes[2][$idkonc-1] + $row["konkurs_ads_big"] * $konk_ads_big_prizes[4][$idkonc-1]/100);
						$sum_prize_reit = ($konk_ads_big_prizes[5][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.ucfirst($row["username"]).'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_big"],2,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ads_big`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_big"], 2, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads_big' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ads_big' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Потрачено на рекламу</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 2, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}

	### КОНКУРС РЕКЛАМОДАТЕЛЕЙ №2 ### 

}elseif($type_kon=="ads_task") {
	### Конкурс по оплате заданий ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='title'");
	$konkurs_title_ads_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='min_do'");
	$konk_ads_task_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='all_count_prize'");
	$konk_ads_task_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='count_prize'");
	$konk_ads_task_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='type_prize'");
	$konk_ads_task_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ads_task_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ads_task_status==1 && $konk_ads_task_date_end>=(time()) && $konk_ads_task_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ads_task!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ads_task.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс по оплате заданий</b></h3>';
		echo '<b>Условия проведения конкурса:</b> Для участия в конкурсе необходимо быть зарегистрированным пользователем проекта <b><a href="/">'.$domen.'</a></b>. В конкурсе учитывается сумма вознаграждений пользователям за выполненные и оплаченные задания в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b>. Задания подтвержденные, автоматически, роботом системы в конкурсе не учитываются!<br><br>';

		echo '<span style="color:#FF0000;">Внимание:</span> Заблокированные пользователи и рекламодатели в конкурсе не участвуют!!!<br><br>';
		
		echo 'Количество призовых мест - <b>'.$konk_ads_task_all_count_prize.'</b><br>';
		echo 'Минимальная сумма вознаграждений пользователям для участия в конкурсе - <b>'.number_format($konk_ads_task_min, 2, ".", "`").' руб.</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_task_all_count_prize; $i++) {
				if($konk_ads_task_prizes[$y][$i]==0) {
					$_Ads_Task_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],2, ".", "`"), " руб.[осн.счет]", " руб.[осн.счет]", " руб.[осн.счет]", "");
					if($y==2) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],2, ".", "`"), " руб.[рекл.счет]", " руб.[рекл.счет]", " руб.[рекл.счет]", "");
					if($y==3) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],0, ".", "`"), " % от потраченной суммы[осн.счет]", " % от потраченной суммы[осн.счет]", " % от потраченной суммы[осн.счет]", "");
					if($y==4) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],0, ".", "`"), " % от потраченной суммы[рекл.счет]", " % от потраченной суммы[рекл.счет]", " % от потраченной суммы[рекл.счет]", "");
					if($y==5) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],0, ".", "`"), " баллов", " балл", " балла", "");
				}
			}
		}

		for($i=0; $i<$konk_ads_task_all_count_prize; $i++) {
			$_Ads_Task_Prizes_New = array();
			for($y=1; $y<=count($_Ads_Task_Prizes); $y++) {
				if($_Ads_Task_Prizes[$y][$i]!=false) $_Ads_Task_Prizes_New[].= $_Ads_Task_Prizes[$y][$i];
			}
			$_Ads_Task_Prizes_New = implode(" +&nbsp;", $_Ads_Task_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Ads_Task_Prizes_New.'<br><br>';
		}

		echo '<b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_ads_task_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_ads_task_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>Место</th>';
				echo '<th colspan="2">Рекламодатель</th>';
				echo '<th>Сумма вознаграждений</th>';
				echo '<th>Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ads_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_task` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ads_task_all_count_prize && $row["konkurs_ads_task"]>=$konk_ads_task_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_ads_task_prizes[1][$idkonc-1] + $row["konkurs_ads_task"] * $konk_ads_task_prizes[3][$idkonc-1]/100);
						$sum_prize_rb = ($konk_ads_task_prizes[2][$idkonc-1] + $row["konkurs_ads_task"] * $konk_ads_task_prizes[4][$idkonc-1]/100);
						$sum_prize_reit = ($konk_ads_task_prizes[5][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.ucfirst($row["username"]).'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_task"],2,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ads_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_task` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_task"], 2, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads_task' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ads_task' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Рекламодатель</th>';
					echo '<th class="top">Сумма вознаграждений</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 2, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}

	### Конкурс по оплате заданий ### 
	
}elseif($type_kon=="ref") {

	### КОНКУРС АКТИВНЫХ РЕФЕРАЛОВ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='title'");
	$konkurs_title_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_ref'");
	$konk_ref_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_click'");
	$konk_ref_min_click = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_day_act'");
	$konk_ref_min_day_act = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='all_count_prize'");
	$konk_ref_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='count_prize'");
	$konk_ref_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='type_prize'");
	$konk_ref_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ref_status==1 && $konk_ref_date_end>=(time()) && $konk_ref_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ref!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ref.'</h1>';

               
		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: "Привлечение активных рефералов"</b></h3>';
		echo '<br><b>Описание и условия проведения конкурса.</b><br>В конкурсе учитываются только активные рефералы. Активным считается тот реферал, который зарегистрировался в период проведения конкурса и <b>сделал не менее '.$konk_ref_min_click.' оплачиваемых кликов по динамическим ссылкам</b>, а также <b>посещал аккаунт не позднее '.$konk_ref_min_day_act.' дней(дня) назад</b>.<br>';
		echo 'Таким образом, на момент окончания конкурса учитываются только те рефералы, которые соответствуют указанным выше условиям!<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_ref_all_count_prize.'</b><br>';
		echo 'Минимальное количество привлеченных активных рефералов для участия в конкурсе - <b>'.number_format($konk_ref_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_ref_all_count_prize; $i++) {
				if($konk_ref_prizes[$y][$i]==0) {
					$_Ref_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ref_Prizes[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "");
					if($y==2) $_Ref_Prizes[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],0,'.',' '), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
					if($y==3) $_Ref_Prizes[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],0,'.',' '), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_ref_all_count_prize; $i++) {
			$_Ref_Prizes_New = $_Ref_Prizes[1][$i]."|".$_Ref_Prizes[2][$i]."|".$_Ref_Prizes[3][$i];
			$_Ref_Prizes_New = explode("|", $_Ref_Prizes_New);
			$_Ref_Prizes_New = trim(implode(" ", $_Ref_Prizes_New));
			$_Ref_Prizes_New = str_replace("  ", " ", $_Ref_Prizes_New);
			$_Ref_Prizes_New = str_replace(" ", "&nbsp;+&nbsp;", $_Ref_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Ref_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_ref_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_ref_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">Место</th><th class="top" colspan="2">Пользователь</th><th class="top">Привлечено активных рефералов</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ref` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ref_all_count_prize && $row["1"]>=$konk_ref_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.$row["0"].'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ref` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ref' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ref' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Привлечено рефералов</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                         
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
  echo '</div>';
	### КОНКУРС ЛУЧШИЙ РЕФЕРЕР ###

}elseif($type_kon=="click") {

	### КОНКУРС КЛИКЕРОВ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='title'");
	$konkurs_title_click = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='min_do'");
	$konk_click_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='all_count_prize'");
	$konk_click_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='count_prize'");
	$konk_click_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='type_prize'");
	$konk_click_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='prizes' AND `howmany`='$y'");
		$konk_click_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_click_status==1 && $konk_click_date_end>=(time()) && $konk_click_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_click!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_click.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: Активный кликер</b></h3>';
		echo '<b>Условия проведения конкурса:</b> В конкурсе учитывается количество кликов по динамическим ссылкам в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b>.<br>Во избежание накрутки, клики по своим динамическим ссылкам не учитываются. ';
		echo 'Также для равноправного участия не учитываются клики по ссылкам с установленным таргетингом по странам, с настройками "показывать пользователям без реферера" и "показывать только новичкам".<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_click_all_count_prize.'</b><br>';
		echo 'Минимальное количество кликов для участия в конкурсе - <b>'.number_format($konk_click_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_click_all_count_prize; $i++) {
				if($konk_click_prizes[$y][$i]==0) {
					$_Click_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Click_Prizes[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "");
					if($y==2) $_Click_Prizes[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],0,'.',' '), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
					if($y==3) $_Click_Prizes[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],0,'.',' '), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_click_all_count_prize; $i++) {
			$_Click_Prizes_New = $_Click_Prizes[1][$i]."|".$_Click_Prizes[2][$i]."|".$_Click_Prizes[3][$i];
			$_Click_Prizes_New = explode("|", $_Click_Prizes_New);
			$_Click_Prizes_New = trim(implode(" ", $_Click_Prizes_New));
			$_Click_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Click_Prizes_New);
			$_Click_Prizes_New = str_replace(" ", " ", $_Click_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Click_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_click_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_click_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">Место</th><th class="top" colspan="2">Пользователь</th><th class="top">Кликов</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_click`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_click` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_click_all_count_prize && $row["1"]>=$konk_click_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_click`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_click` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='click' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='click' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Сделано кликов</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
echo '</div>';
	### КОНКУРС КЛИКЕРОВ ###

}elseif($type_kon=="test") {

	### КОНКУРС ТЕСТОВ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='title'");
	$konkurs_title_test = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='min_do'");
	$konk_test_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='all_count_prize'");
	$konk_test_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='count_prize'");
	$konk_test_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='type_prize'");
	$konk_test_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='prizes' AND `howmany`='$y'");
		$konk_test_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_test_status==1 && $konk_test_date_end>=(time()) && $konk_test_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_test!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_test.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: Прохождение тестов</b></h3>';
		echo '<b>Условия проведения конкурса:</b> В конкурсе учитывается количество пройденых тестов в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b><br><br>';

		echo 'Количество призовых мест - <b>'.$konk_test_all_count_prize.'</b><br>';
		echo 'Минимальное количество прохождений для участия в конкурсе - <b>'.number_format($konk_test_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_test_all_count_prize; $i++) {
				if($konk_test_prizes[$y][$i]==0) {
					$_Test_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Test_Prizes[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "");
					if($y==2) $_Test_Prizes[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],0,'.',' '), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
					if($y==3) $_Test_Prizes[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],0,'.',' '), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_test_all_count_prize; $i++) {
			$_Test_Prizes_New = $_Test_Prizes[1][$i]."|".$_Test_Prizes[2][$i]."|".$_Test_Prizes[3][$i];
			$_Test_Prizes_New = explode("|", $_Test_Prizes_New);
			$_Test_Prizes_New = trim(implode(" ", $_Test_Prizes_New));
			$_Test_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Test_Prizes_New);
			$_Test_Prizes_New = str_replace(" ", " ", $_Test_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Test_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_test_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_test_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">Место</th><th class="top" colspan="2">Пользователь</th><th class="top">Прохождений</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_test`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_test` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_test_all_count_prize && $row["1"]>=$konk_test_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_test`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_test` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='test' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='test' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Сделано кликов</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
echo '</div>';
	### КОНКУРС ТЕСТОВ ###
	
}elseif($type_kon=="youtub") {

	### КОНКУРС YOUTUBE ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='title'");
	$konkurs_title_youtub = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='min_do'");
	$konk_youtub_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='all_count_prize'");
	$konk_youtub_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='count_prize'");
	$konk_youtub_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='type_prize'");
	$konk_youtub_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='prizes' AND `howmany`='$y'");
		$konk_youtub_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_youtub_status==1 && $konk_youtub_date_end>=(time()) && $konk_youtub_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_youtub!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_youtub.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: Активный видео серфер</b></h3>';
		echo '<b>Условия проведения конкурса:</b> В конкурсе учитывается количество просмотров в видео серфинге YouTube в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b>.<br>Во избежание накрутки, просмотры своих видеороликов не учитываются. ';
		echo 'Также для равноправного участия не учитываются просмотры видеороликов с установленным таргетингом по странам, с настройками "показывать пользователям без реферера" и "показывать только новичкам".<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_youtub_all_count_prize.'</b><br>';
		echo 'Минимальное количество просмотров для участия в конкурсе - <b>'.number_format($konk_youtub_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_youtub_all_count_prize; $i++) {
				if($konk_youtub_prizes[$y][$i]==0) {
					$_Youtub_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Youtub_Prizes[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "");
					if($y==2) $_Youtub_Prizes[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],0,'.',' '), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
					if($y==3) $_Youtub_Prizes[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],0,'.',' '), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_youtub_all_count_prize; $i++) {
			$_Youtub_Prizes_New = $_Youtub_Prizes[1][$i]."|".$_Youtub_Prizes[2][$i]."|".$_Youtub_Prizes[3][$i];
			$_Youtub_Prizes_New = explode("|", $_Youtub_Prizes_New);
			$_Youtub_Prizes_New = trim(implode(" ", $_Youtub_Prizes_New));
			$_Youtub_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Youtub_Prizes_New);
			$_Youtub_Prizes_New = str_replace(" ", " ", $_Youtub_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Youtub_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_youtub_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_youtub_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">Место</th><th class="top" colspan="2">Пользователь</th><th class="top">Просмотров</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_youtub`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_youtub` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_youtub_all_count_prize && $row["1"]>=$konk_youtub_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_youtub`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_youtub` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='youtub' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='youtub' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Сделано просмотров</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
echo '</div>';
	### КОНКУРС YOUTUBE ###
	
}elseif($type_kon=="task") {

	### КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='title'");
	$konkurs_title_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='min_do'");
	$konk_task_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='all_count_prize'");
	$konk_task_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='count_prize'");
	$konk_task_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='type_prize'");
	$konk_task_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='prizes' AND `howmany`='$y'");
		$konk_task_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_task_status==1 && $konk_task_date_end>=(time()) && $konk_task_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_task!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_task.'</h1>';

                
		//echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс по выполнению заданий</b></h3>';
		//echo '<b>Условия проведения конкурса:</b> В конкурсе учитываются любые выполненные и оплаченные задания в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b><br><br>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс по выполнению заданий</b></h3>';
		echo '<b>Условия проведения конкурса:</b> В конкурсе учитываются любые выполненные и оплаченные задания в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b>, но не более 3-х заданий от одного рекламодателя в течении суток.<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_task_all_count_prize.'</b><br>';
		echo 'Минимальная сумма вознаграждений пользователям для участия в конкурсе - <b>'.number_format($konk_task_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_task_all_count_prize; $i++) {
				if($konk_task_prizes[$y][$i]==0) {
					$_Task_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Task_Prizes[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],2,'.',' '), "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "&nbsp;руб.&nbsp;", "");
					if($y==2) $_Task_Prizes[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],0,'.',' '), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
					if($y==3) $_Task_Prizes[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],0,'.',' '), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_task_all_count_prize; $i++) {
			$_Task_Prizes_New = $_Task_Prizes[1][$i]."|".$_Task_Prizes[2][$i]."|".$_Task_Prizes[3][$i];
			$_Task_Prizes_New = explode("|", $_Task_Prizes_New);
			$_Task_Prizes_New = trim(implode(" ", $_Task_Prizes_New));
			$_Task_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Task_Prizes_New);
			$_Task_Prizes_New = str_replace(" ", "", $_Task_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Task_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_task_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_task_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">Место</th><th class="top" colspan="2">Пользователь</th><th class="top">Выполнено заданий</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			//$sql = mysql_query("SELECT `id`, `username`,`konkurs_hit`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_hit` DESC limit 10");
			$sql = mysql_query("SELECT `username`,`konkurs_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_task` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_task_all_count_prize && $row["1"]>=$konk_task_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_task` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='task' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='task' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Выполнено заданий</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
echo '</div>';
	### КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ ###
/*	
}elseif($type_kon=="hit") {

	### КОНКУРС ПОСЕТИТЕЛЕЙ ###

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='title'");
	$konk_hit_title = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='min_do'");
	$konk_hit_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='all_count_prize'");
	$konk_hit_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='count_prize'");
	$konk_hit_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='type_prize'");
	$konk_hit_type_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='country'");
	$konk_hit_ed_country = explode("; ", mysql_result($sql,0,0));

	$geo_name_arr = array(
		'RU' => 'России', 	'UA' => 'Украины', 	'BY' => 'Белоруссии', 	'MD' => 'Молдавии', 	'KZ' => 'Казахстана', 	'AM' => 'Армении', 	'UZ' => 'Узбекистана',	'LV' => 'Латвии',
		'DE' => 'Германии', 	'GE' => 'Грузии', 	'LT' => 'Литвы', 	'FR' => 'Франции', 	'AZ' => 'Азербайджана', 'US' => 'США', 		'VN' => 'Вьетнама', 	'PT' => 'Португалии',
		'GB' => 'Англии', 	'BE' => 'Бельгии', 	'ES' => 'Испании', 	'CN' => 'Китая',	'TJ' => 'Таджикистана', 'EE' => 'Эстонии', 	'IT' => 'Италии', 	'KG' => 'Киргизии',
		'IL' => 'Израиля', 	'CA' => 'Канады', 	'TM' => 'Туркменистана','BG' => 'Болгарии',	'IR' => 'Ирана', 	'GR' => 'Греции', 	'TR' => 'Турции', 	'PL' => 'Польшы',
		'FI' => 'Финляндии', 	'EG' => 'Египета', 	'SE' => 'Швеции', 	'RO' => 'Румынии'
	);
	if(is_array($konk_hit_ed_country)) {
		foreach($konk_hit_ed_country as $key => $val) {
			$country_arr_ru[] = isset($geo_name_arr[$val]) ? $geo_name_arr[$val] : false;
		}
	}
	$konk_hit_ed_country_to = isset($country_arr_ru) ? trim(strtoupper(implode(', ', $country_arr_ru))) : false;

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='prizes' AND `howmany`='$y'");
		$konk_hit_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_hit_status==1 && $konk_hit_date_end>=(time()) && $konk_hit_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konk_hit_title!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konk_hit_title.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс посетителей</b></h3>';
		echo 'Для победы в конкурсе, просто привлекайте посетителей на наш сайт по вашей реферальной ссылке!<br>';
		echo 'Учитываются только реальные, уникальные в течении 24 часов переходы';

		if($konk_hit_ed_country_to!=false) { echo ' из '.$konk_hit_ed_country_to.'.<br><br>'; }else{ echo '.<br><br>'; }

		echo 'Количество призовых мест - <b>'.$konk_hit_all_count_prize.'</b><br>';
		echo 'Минимальное количество посетителей для участия в конкурсе - <b>'.number_format($konk_hit_min,0,'.',' ').'</b><br><br>';

		echo '<div id="newform" align="center">';
			echo '<b>Ваша реф. ссылка:</b> ';
			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
				echo '<input value="https://lilacbux.com?r='.$partnerid.'" class="ok" onClick="this.select();" readonly="readonly" style="width:180px; height:auto; margin: 5px; padding: 5px; text-align:center;"><br>';
			}else{
				echo '<b style="background-color:#FFBBFF;">для получения реф. ссылки необходимо авторизоваться</b><br><br>';
			}
		echo '</div>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_hit_all_count_prize; $i++) {
				if($y==1) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;руб.[осн.&nbsp;счет]", "&nbsp;руб.[осн.&nbsp;счет]", "&nbsp;руб.[осн.&nbsp;счет]", "") : false;
				if($y==2) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;руб.[рекл.&nbsp;счет]", "&nbsp;руб.[рекл.&nbsp;счет]", "&nbsp;руб.[рекл.&nbsp;счет]", "") : false;
				if($y==3) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;руб./посетитель[осн.&nbsp;счет]", "&nbsp;руб./посетитель[осн.&nbsp;счет]", "&nbsp;руб./посетитель[осн.&nbsp;счет]", "") : false;
				if($y==4) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;руб./посетитель[рекл.&nbsp;счет]", "&nbsp;руб./посетитель[рекл.&nbsp;счет]", "&nbsp;руб./посетитель[рекл.&nbsp;счет]", "") : false;
				if($y==5) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(number_format($konk_hit_prizes[$y][$i], 0, ".", ""), "&nbsp;баллов", "&nbsp;балл", "&nbsp;балла", "") : false;
			}
		}

		for($i=0; $i<$konk_hit_all_count_prize; $i++) {
			$_hit_Prizes_New = array();
			for($y=1; $y<=count($_hit_Prizes); $y++) {
				if($_hit_Prizes[$y][$i]!=false) $_hit_Prizes_New[].= $_hit_Prizes[$y][$i];
			}
			$_hit_Prizes_New = implode(" +&nbsp;", $_hit_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_hit_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_hit_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_hit_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th class="top">Место</th>';
				echo '<th class="top" colspan="2">Пользователь</th>';
				echo '<th class="top">Привлечено посетителей</th>';
				echo '<th class="top">Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_hit`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_hit` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_hit_all_count_prize && $row["konkurs_hit"]>=$konk_hit_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_hit_prizes[1][$idkonc-1] + $row["konkurs_hit"] * $konk_hit_prizes[3][$idkonc-1]);
						$sum_prize_rb = ($konk_hit_prizes[2][$idkonc-1] + $row["konkurs_hit"] * $konk_hit_prizes[4][$idkonc-1]);
						$sum_prize_reit = ($konk_hit_prizes[5][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,4)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,4)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="Перейти на стену юзера '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_hit"],0,'.',' ').'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_hit`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_hit` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="5" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_hit"], 0, ".", " ").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}
		echo '</table><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='hit' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='hit' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<span class="msg-w" style="margin:20px auto 10px;">Завершенные конкурсы, последние 5 результатов</span>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i:s", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Привлечено посетителей</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<span class="msg-w" style="margin:30px auto;">Конкурсы еще не проводились!</span>';
	}

	### КОНКУРС ПОСЕТИТЕЛЕЙ ###
*/
}elseif($type_kon=="complex") {

	### КОМПЛЕКСНЫЙ КОНКУРС ###
	$points_arr = array(
		"serf"    => "просмотр ссылки в серфинге",
		"youtub"    => "просмотр видеороликов в серфинге <span style=\"color: #3F3F3F;\">You</span><span style=\"border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;\">Tube</span>",
		"aserf"    => "просмотр ссылки в авто-серфинге",
		"mail"    => "чтение оплачиваемого письма", 
		"task"    => "выполнение одноразового задания", 
		"task_re" => "выполнение многоразового задания", 
		"test"    => "прохождение теста", 
		"refs"    => "привлечение реферала"
	);
	$points_key_arr = array_keys($points_arr);
	$points_val_arr = array_values($points_arr);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='title'");
	$konkurs_title_complex = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='min_do'");
	$konk_complex_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='all_count_prize'");
	$konk_complex_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='count_prize'");
	$konk_complex_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='type_prize'");
	$konk_complex_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=4; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='prizes' AND `howmany`='$y'");
		$konk_complex_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	foreach($points_key_arr as $key) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".$key."'") or die(mysql_error());
		$konk_complex_point[$key] = mysql_result($sql,0,0);
	}

	if($konk_complex_status==1 && $konk_complex_date_end>=(time()) && $konk_complex_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_complex!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_complex.'</h1>';

		//echo '<h3 class="sp" style="margin:0; padding:0;"><b>Комплексный конкурс</b></h3>';
		//echo '<b>Условия проведения конкурса:</b> Призовые места займут те, кто наберет наибольшее количество баллов в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b><br><br>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>Комплексный конкурс</b></h3>';
		echo '<b>Условия проведения конкурса:</b> Призовые места займут те, кто наберет наибольшее количество баллов в период проведения конкурса на проекте <b><a href="/">'.$domen.'</a></b>. При начислении баллов за выполнение заданий, учитывается не более 3-х заданий от одного рекламодателя в течении суток.<br><br>';

		
		echo '<b>Баллы начисляются по схеме:</b><br>';
		$_list_points = array();
		foreach($points_arr as $key => $val) {
			if(isset($konk_complex_point[$key]) && $konk_complex_point[$key]>0) $_list_points[] = "&nbsp;- ".$points_arr[$key].": ".count_text($konk_complex_point[$key], " баллов", " балл", " балла", "");
		}
		$_list_points = implode(";<br>", $_list_points);
		echo $_list_points.".<br><br>";

		echo '<span style="color:#FF0000;">Внимание:</span> баллы не начисляются по своей рекламе, по рекламе с установленным гео-таргетингом, также не начисляются заблокированным пользователям! За привлечение рефералов баллы начисляются только при условии активации аккаунта!<br><br>';

		echo 'Количество призовых мест - <b>'.$konk_complex_all_count_prize.'</b><br>';
		echo 'Минимальное количество баллов для участия в конкурсе - <b>'.number_format($konk_complex_min,0,'.',' ').'</b><br><br>';
		
		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=4; $y++) {
			for($i=0; $i<$konk_complex_all_count_prize; $i++) {
				if($y==1) $_Complex_Prizes[$y][$i] = count_text(round($konk_complex_prizes[$y][$i],4), "&nbsp;руб.[осн.&nbsp;счет]", "&nbsp;руб.[осн.&nbsp;счет]", "&nbsp;руб.[осн.&nbsp;счет]", "");
				if($y==2) $_Complex_Prizes[$y][$i] = count_text(round($konk_complex_prizes[$y][$i],4), "&nbsp;руб.[рекл.&nbsp;счет]", "&nbsp;руб.[рекл.&nbsp;счет]", "&nbsp;руб.[рекл.&nbsp;счет]", "");
				if($y==3) $_Complex_Prizes[$y][$i] = count_text(number_format($konk_complex_prizes[$y][$i], 0, ".", ""), "&nbsp;баллов", "&nbsp;балл", "&nbsp;балла", "");
				if($y==4) $_Complex_Prizes[$y][$i] = count_text(number_format($konk_complex_prizes[$y][$i], 0, ".", ""), "&nbsp;рефералов", "&nbsp;реферал", "&nbsp;реферала", "");
			}
		}

		for($i=0; $i<$konk_complex_all_count_prize; $i++) {
			$_Complex_Prizes_New = array();
			for($y=1; $y<=count($_Complex_Prizes); $y++) {
				if($_Complex_Prizes[$y][$i]!=false) $_Complex_Prizes_New[].= $_Complex_Prizes[$y][$i];
			}
			$_Complex_Prizes_New = implode(" +&nbsp;", $_Complex_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Complex_Prizes_New.'<br>';
		}

		echo '<br><b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_complex_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_complex_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>Место</th>';
				echo '<th colspan="2">Пользователь</th>';
				echo '<th>Набрано баллов</th>';
				echo '<th>Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_complex`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_complex` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_complex_all_count_prize && $row["konkurs_complex"]>=$konk_complex_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_complex_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_complex_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_complex_prizes[3][$idkonc-1]);
						$sum_prize_ref = ($konk_complex_prizes[4][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,4)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,4)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");
						if($sum_prize_ref>0) 	$sum_prizes[].= count_text(number_format($sum_prize_ref, 0, ".", ""), "&nbsp;рефералов&nbsp;", "&nbsp;реферал&nbsp;", "&nbsp;реферала&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+&nbsp;", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_complex"],0,'.',' ').'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_complex`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username`='$MY_USERNAME' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_complex` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="5" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_complex"], 0, ".", " ").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}
		echo '</table>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='complex' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 3");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["ident"];
			else $list_ident_arr.= "', '".$row_ident["ident"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='complex' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<span class="msg-ok" style="margin:30px auto 20px auto;">Завершенные конкурсы, последние 5 результатов</span>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Набрано баллов</th>';
					echo '<th class="top">Приз</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<span class="msg-w" style="margin:30px auto;">Конкурсы еще не проводились!</span>';
	}

}elseif($type_kon=="serf") {
	### КОНКУРС ПО РАЗМЕЩЕНИЮ ССЫЛОК В СЕРФИНГЕ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='title'");
	$konkurs_title_serf = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='min_do'");
	$konk_serf_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='all_count_prize'");
	$konk_serf_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='count_prize'");
	$konk_serf_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='type_prize'");
	$konk_serf_type_prize = explode("; ", mysql_result($sql,0,0));
	
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='timer_ot'");
    $konk_serf_timer = mysql_result($sql,0,0);

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='prizes' AND `howmany`='$y'");
		$konk_serf_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_serf_status==1 && $konk_serf_date_end>=(time()) && $konk_serf_date_start<(time())) {
	        if($konkurs_title_serf!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_serf.'</h1>';

			 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
			echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: По размещению ссылок в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге</b></h3><br/>';
		echo '<b>Условия проведения конкурса:</b> Для участия в конкурсе необходимо быть зарегистрированным пользователем проекта <b><a href="/">'.$domen.'</a></b>. В конкурсе учитывается количество размещенных ссылок/баннеров в серфинге и <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге в период проведение конкурса на проекте <b><a href="/">'.$domen.'</a></b>. Оплату рекламы можно производить как с рекламного счета так и с внешних платежных систем но при условии что реклама оформлена и оплачена после авторизации на проекте.<br><br>';

		echo '<span style="color:#FF0000;">Внимание:</span> Заблокированные пользователи в конкурсе не участвуют!!!<br><br>';
		
		echo '<span style="color:#FF0000;">Внимание:</span> В конкурсе участвуют ссылки с таймером от <b>'.number_format($konk_serf_timer,0,".","`").' сек.</b>!!!<br><br>';
		
		echo '<span style="color:#FF0000;">Внимание:</span> В конкурсе не участвуют ссылки с <b>Гео-таргетингом и Доп. Настройками</b>!!!<br><br>';
		
		echo 'Количество призовых мест - <b>'.$konk_serf_all_count_prize.'</b><br>';
		echo 'Минимальное количество размещенных ссылок/баннеров в серфинге для участия в конкурсе - <b>'.number_format($konk_serf_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_serf_all_count_prize; $i++) {
				if($konk_serf_prizes[$y][$i]==0) {
					$_Serf_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Serf_Prizes[$y][$i] = count_text(number_format($konk_serf_prizes[$y][$i],2, ".", "`"), " руб.[осн.счет]", " руб.[осн.счет]", " руб.[осн.счет]", "");
					if($y==2) $_Serf_Prizes[$y][$i] = count_text(number_format($konk_serf_prizes[$y][$i],2, ".", "`"), " руб.[рекл.счет]", " руб.[рекл.счет]", " руб.[рекл.счет]", "");
					if($y==3) $_Serf_Prizes[$y][$i] = count_text(number_format($konk_serf_prizes[$y][$i],0, ".", "`"), " баллов", " балл", " балла", "");
				}
			}
		}

		for($i=0; $i<$konk_serf_all_count_prize; $i++) {
			$_Serf_Prizes_New = array();
			for($y=1; $y<=count($_Serf_Prizes); $y++) {
				if($_Serf_Prizes[$y][$i]!=false) $_Serf_Prizes_New[].= $_Serf_Prizes[$y][$i];
			}
			$_Serf_Prizes_New = implode(" +&nbsp;", $_Serf_Prizes_New);

			echo '<div align="left" style="margin-bottom:6px;"><b style="color:coral;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Serf_Prizes_New.'</div>';
		}
		echo '<br>';

		echo '<b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_serf_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_serf_date_end-1).'';

		echo '<table class="tables_inv">';
			echo '<thead><tr align="center">';
				echo '<th>Место</th>';
				echo '<th colspan="2">Пользователь</th>';
				echo '<th>Размещено ссылок</th>';
				echo '<th>Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_serf`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_serf` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_serf_count_prize && $row["konkurs_serf"]>=$konk_serf_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_serf_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_serf_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_serf_prizes[3][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="Перейти на стену юзера '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_serf"],0,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_serf`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_serf"], 0, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='serf' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='serf' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Логин</th>';
					echo '<th class="top">Размещено ссылок</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
	
}elseif($type_kon=="clic_ref") {
	### КОНКУРС ДОХОД ОТ РЕФЕРАЛОВ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='title'");
	$konkurs_title_clic_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='min_do'");
	$konk_clic_ref_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='all_count_prize'");
	$konk_clic_ref_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='count_prize'");
	$konk_clic_ref_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='type_prize'");
	$konk_clic_ref_type_prize = explode("; ", mysql_result($sql,0,0));
	
	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='$y'");
		$konk_clic_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_clic_ref_status==1 && $konk_clic_ref_date_end>=(time()) && $konk_clic_ref_date_start<(time())) {
	        if($konkurs_title_clic_ref!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_clic_ref.'</h1>';

			 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="justify"> ';
			echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс "ЛУЧШИЙ РЕФЕРЕР"</b></h3><br/>';
		echo '<b>Условия проведения конкурса:</b> Для участия в конкурсе необходимо быть зарегистрированным пользователем проекта <b><a href="/">'.$domen.'</a></b>. Призовые места займут те рефереры, чьи рефералы принесут наибольший доход своему рефереру за время проведения конкурса. В конкурсе учитывается доход принесенный рефералами всех уровней с серфинга, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинга, писем, тестов и заданий.<br><br>';

		echo '<span style="color:#FF0000;">Внимание:</span> Заблокированные пользователи в конкурсе не участвуют!!!<br>Рефбэк в данном конкурсе не учитывается.<br><br>';
		
		echo 'Количество призовых мест - <b>'.$konk_clic_ref_all_count_prize.'</b><br>';
		echo 'Минимальная заработанная сумма рефералами I-Vур. для участия в конкурсе - <b>'.number_format($konk_clic_ref_min,0,".","`").'</b><br><br>';

		//echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_clic_ref_all_count_prize; $i++) {
				if($konk_clic_ref_prizes[$y][$i]==0) {
					$_Clic_ref_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Clic_ref_Prizes[$y][$i] = count_text(number_format($konk_clic_ref_prizes[$y][$i],2, ".", "`"), " руб.[осн.счет]", " руб.[осн.счет]", " руб.[осн.счет]", "");
					if($y==2) $_Clic_ref_Prizes[$y][$i] = count_text(number_format($konk_clic_ref_prizes[$y][$i],2, ".", "`"), " руб.[рекл.счет]", " руб.[рекл.счет]", " руб.[рекл.счет]", "");
					if($y==3) $_Clic_ref_Prizes[$y][$i] = count_text(number_format($konk_clic_ref_prizes[$y][$i],0, ".", "`"), " баллов", " балл", " балла", "");
				}
			}
		}
		
		$summ=0;
		$sum=0;
		$su=0;
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='1'");
		$count_all_konk_admin = explode("; ",mysql_result($sql,0,0));
		for($i=0;$i<count($count_all_konk_admin);$i++){ 
        $summ=$summ+$count_all_konk_admin[$i];
          }
			$sqlll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='2' ");
			$count_all_konk_ad = explode("; ",mysql_result($sqlll,0,0));
			for($i=0;$i<count($count_all_konk_ad);$i++){ 
            $sum=$sum+$count_all_konk_ad[$i];
            }
			$sqll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='3' ");
			$count_all_konk_a = explode("; ",mysql_result($sqll,0,0));
			for($i=0;$i<count($count_all_konk_a);$i++){ 
            $su=$su+$count_all_konk_a[$i];
            }
			
			echo '<div style="text-align:center; margin:1px auto 5px; line-height:20px;">';			
			echo '<div class="text-red" style="font-size:16px; font-weight:bold;">Призовой фонд:</div>';
			echo '<div><b class="text-blue" style="font-size:14px;color:#108;">'.number_format($summ,2,".","`").'</b>&nbsp;руб.[осн.&nbsp;счет]<span style="color:#CCC; padding:0 5px;">•</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($sum,2,".","`").'</b>&nbsp;руб.[рекл.&nbsp;счет]<span style="color:#CCC; padding:0 5px;">•</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($su,0,".","`").'</b>&nbsp;баллов</div>';
			echo '</div>';
			//echo '<br>';
			
			echo '<table style="margin:0 auto 15px;"><tr><td>';

		for($i=0; $i<$konk_clic_ref_all_count_prize; $i++) {
			$_Clic_ref_Prizes_New = array();
			for($y=1; $y<=count($_Clic_ref_Prizes); $y++) {
				if($_Clic_ref_Prizes[$y][$i]!=false) $_Clic_ref_Prizes_New[].= $_Clic_ref_Prizes[$y][$i];
			}
			$_Clic_ref_Prizes_New = implode(" +&nbsp;", $_Clic_ref_Prizes_New);
			
            echo '<div align="left" style="line-height:18px;"><b style="color:#108;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Clic_ref_Prizes_New.'</div>';
		}
		echo '</td></tr></table>';
		//echo '<br>';

		echo '<b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_clic_ref_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_clic_ref_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>Место</th>';
				echo '<th colspan="2">Пользователь</th>';
				echo '<th>Доход от рефералов</th>';
				echo '<th>Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_clic_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_clic_ref` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_clic_ref_count_prize && $row["konkurs_clic_ref"]>=$konk_clic_ref_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_clic_ref_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_clic_ref_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_clic_ref_prizes[3][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 2, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="Перейти на стену юзера '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_clic_ref"],6,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_clic_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_clic_ref"], 6, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='clic_ref' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='clic_ref' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Пользователь</th>';
					echo '<th class="top">Доход от рефералов</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
	
	### КОНКУРС ДОХОД ОТ РЕФЕРАЛОВ ###
	
}elseif($type_kon=="best_ref") {
	### КОНКУРС ДОХОД ОТ РЕФЕРАЛОВ ПРИЗЫ РЕФЕРАЛАМ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='title'");
	$konkurs_title_best_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='min_do'");
	$konk_best_ref_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='all_count_prize'");
	$konk_best_ref_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='count_prize'");
	$konk_best_ref_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='type_prize'");
	$konk_best_ref_type_prize = explode("; ", mysql_result($sql,0,0));
	
	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='$y'");
		$konk_best_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_best_ref_status==1 && $konk_best_ref_date_end>=(time()) && $konk_best_ref_date_start<(time())) {
	        if($konkurs_title_best_ref!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_best_ref.'</h1>';

			 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
			echo '<h3 class="sp" style="margin:0; padding:0;"><b>Конкурс: "ЛУЧШИЙ РЕФЕРАЛ"</b></h3><br/>';
		echo '<b>Условия проведения конкурса:</b> Для участия в конкурсе необходимо быть зарегистрированным пользователем проекта <b><a href="/">'.$domen.'</a></b> и иметь реферера как минимум I уровня. Призовые места займут те, кто принесёт наибольший доход своим реферерам за время проведения конкурса. В конкурсе учитывается доход принесенный реферерам всех уровней с серфинга, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинга, писем, тестов и заданий.<br><br>';

		echo '<span style="color:#FF0000;">Внимание:</span> Заблокированные пользователи в конкурсе не участвуют!!!<br>Рефбэк в данном конкурсе не учитывается.<br><br>';
		
		echo 'Количество призовых мест - <b>'.$konk_best_ref_all_count_prize.'</b><br>';
		echo 'Минимальная заработанная сумма для рефереров I-Vур. для участия в конкурсе - <b>'.number_format($konk_best_ref_min,0,".","`").'</b><br><br>';

		//echo '<b style="color:coral;"><u>Призы:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_best_ref_all_count_prize; $i++) {
				if($konk_best_ref_prizes[$y][$i]==0) {
					$_Best_ref_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Best_ref_Prizes[$y][$i] = count_text(number_format($konk_best_ref_prizes[$y][$i],2, ".", "`"), " руб.[осн.счет]", " руб.[осн.счет]", " руб.[осн.счет]", "");
					if($y==2) $_Best_ref_Prizes[$y][$i] = count_text(number_format($konk_best_ref_prizes[$y][$i],2, ".", "`"), " руб.[рекл.счет]", " руб.[рекл.счет]", " руб.[рекл.счет]", "");
					if($y==3) $_Best_ref_Prizes[$y][$i] = count_text(number_format($konk_best_ref_prizes[$y][$i],0, ".", "`"), " баллов", " балл", " балла", "");
				}
			}
		}
		$summ=0;
		$sum=0;
		$su=0;
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='1'");
		$count_all_konk_admin = explode("; ",mysql_result($sql,0,0));
		for($i=0;$i<count($count_all_konk_admin);$i++){ 
        $summ=$summ+$count_all_konk_admin[$i];
          }
			$sqlll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='2' ");
			$count_all_konk_ad = explode("; ",mysql_result($sqlll,0,0));
			for($i=0;$i<count($count_all_konk_ad);$i++){ 
            $sum=$sum+$count_all_konk_ad[$i];
            }
			$sqll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='3' ");
			$count_all_konk_a = explode("; ",mysql_result($sqll,0,0));
			for($i=0;$i<count($count_all_konk_a);$i++){ 
            $su=$su+$count_all_konk_a[$i];
            }
			
			echo '<div style="text-align:center; margin:1px auto 5px; line-height:20px;">';			
			echo '<div class="text-red" style="font-size:16px; font-weight:bold;">Призовой фонд:</div>';
			echo '<div><b class="text-blue" style="font-size:14px;color:#108;">'.number_format($summ,2,".","`").'</b>&nbsp;руб.[осн.&nbsp;счет]<span style="color:#CCC; padding:0 5px;">•</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($sum,2,".","`").'</b>&nbsp;руб.[рекл.&nbsp;счет]<span style="color:#CCC; padding:0 5px;">•</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($su,0,".","`").'</b>&nbsp;баллов</div>';
			echo '</div>';
			//echo '<br>';
			
			echo '<table style="margin:0 auto 15px;"><tr><td>';

		for($i=0; $i<$konk_best_ref_all_count_prize; $i++) {
			$_Best_ref_Prizes_New = array();
			for($y=1; $y<=count($_Best_ref_Prizes); $y++) {
				if($_Best_ref_Prizes[$y][$i]!=false) $_Best_ref_Prizes_New[].= $_Best_ref_Prizes[$y][$i];
			}
			$_Best_ref_Prizes_New = implode(" +&nbsp;", $_Best_ref_Prizes_New);

			echo '<div align="left" style="line-height:18px;"><b style="color:#108;">'.($i+1).' место:</b>&nbsp;&nbsp;'.$_Best_ref_Prizes_New.'</div>';
		}
		echo '</td></tr></table>';

		echo '<b>Период проведения:</b> c '.DATE("d.m.Yг. H:i", $konk_best_ref_date_start).' &mdash; '.DATE("d.m.Yг. H:i:s", $konk_best_ref_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>Место</th>';
				echo '<th colspan="2">Пользователь</th>';
				echo '<th>Сумма дохода реферерам</th>';
				echo '<th>Приз</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_best_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_best_ref` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_best_ref_count_prize && $row["konkurs_best_ref"]>=$konk_best_ref_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_best_ref_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_best_ref_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_best_ref_prizes[3][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;руб.[осн.&nbsp;счет]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;руб.[рекл.&nbsp;счет]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 2, ".", ""), "&nbsp;баллов&nbsp;", "&nbsp;балл&nbsp;", "&nbsp;балла&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="Перейти на стену юзера '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_best_ref"],6,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_best_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_best_ref` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_best_ref"], 6, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='best_ref' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='best_ref' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">Завершенные конкурсы, последние 5 результатов</span><br>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">Конкурс №'.$row["ident"].'</div>';
					echo '<div style="float:right">Период проведения: '.DATE("d.m.Yг. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Yг. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top">Пользователь</th>';
					echo '<th class="top">Сумма дохода реферерам</th>';
					echo '<th class="top">Призы</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">Конкурсы еще не проводились!</span><br>';
	}
	
	### КОНКУРС ДОХОД ОТ РЕФЕРАЛОВ ПРИЗЫ РЕФЕРАЛАМ ###
}

echo '</div>';
include('footer.php');?>