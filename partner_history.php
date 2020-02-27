<?php
$pagetitle = "История партнерских начислений";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
	//$username="Admin";

	$sql_p = mysql_query("SELECT `discount_partner`,`moneys`,`moneys_p` FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_row($sql_p);
		$discount_partner = $row_p["0"];
		$moneys_partner = $row_p["1"];
		$moneys_partner_p = $row_p["2"];
		$moneys_partner_m =  $moneys_partner + $moneys_partner_p;
	}else{
		$discount_partner = false;
		$moneys_partner = false;
		$moneys_partner_p = false;
		$moneys_partner_m = false;
	}

	echo '<br>';
	echo '<div align="left">Всего партнерских начислений на сумму: <b>'.$moneys_partner.' руб.</b></div>';
	echo '<div align="left">При максимальном проценте вы могли бы получить: <b>'.$moneys_partner_m.' руб.</b></div>';
	echo '<div align="left">Потеряно прибыли на сумму: <b>'.$moneys_partner_p.' руб.</b></div><br>';

	require("navigator/navigator.php");

	$perpage = 25;
	$sql_p = mysql_query("SELECT `id` FROM `tb_partner` WHERE `referer`='$username'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	echo '<h3 class="sp">Здесь Вы можете посмотреть историю ваших партнерских начислений</h3>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '?page=', "");
	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th>Дата</th>';
		echo '<th>Реферал</th>';
		echo '<th>Партнерская программа</th>';
		echo '<th>Ваш %</th>';
		echo '<th>Начислено</th>';
	echo '</tr></thead>';

	$sql_p = mysql_query("SELECT * FROM `tb_partner` WHERE `referer`='$username' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql_p)>0) {
		echo '<tbody>';

		while($row_p = mysql_fetch_array($sql_p)) {
			echo '<tr align="center">';
				echo '<td>'.DATE("d.m.Y H:i", $row_p["time"]).'</td>';
				echo '<td>'.$row_p["username"].'</td>';

				if($row_p["type"]=="p_sl") {
					echo '<td>Статические ссылки</td>';
				}elseif($row_p["type"]=="p_txt"){
					echo '<td>Текстовые объявления</td>';
				}elseif($row_p["type"]=="p_b468x60"){
					echo '<td>Баннеры 468x60</td>';
				}elseif($row_p["type"]=="p_b200x300"){
					echo '<td>Баннеры 200x300</td>';
				}elseif($row_p["type"]=="p_b100x100"){
					echo '<td>Баннеры 100x100</td>';
				}elseif($row_p["type"]=="p_frm"){
					echo '<td>Ссылки во фрейме</td>';
				}elseif($row_p["type"]=="p_psd"){
					echo '<td>Псевдо-динамические ссылки</td>';
				}elseif($row_p["type"]=="p_packet_1"){
					echo '<td>Пакет рекламы №1</td>';
				}elseif($row_p["type"]=="p_packet_2"){
					echo '<td>Пакет рекламы №2</td>';
				}elseif($row_p["type"]=="p_packet_3"){
					echo '<td>Пакет рекламы №3</td>';
				}elseif($row_p["type"]=="p_packet_4"){
					echo '<td>Пакет рекламы №4</td>';
				}elseif($row_p["type"]=="p_packet_5"){
					echo '<td>Пакет рекламы №5</td>';
				}else{
					echo '<td>Не определена</td>';
				}

				echo '<td>'.$row_p["percent"].' %</td>';
				echo '<td>'.number_format($row_p["money"],2,".","").' руб.</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	}else{
		echo '<tbody>';
			echo '<tr align="center"><td colspan="5"><b>Партнерских отчислений на Ваш счет еще не было</b></td></tr>';
		echo '</tbody>';
	}

	echo '</table>';
	if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '?page=', "");
}

include('footer.php');?>