<?php
$pagetitle = "Партнерская программа";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
	$partner_max_percent = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'");
	$partner_max_percent_pack = mysql_result($sql,0,0);

	$sql_p = mysql_query("SELECT `discount_partner`,`moneys`,`moneys_p` FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_row($sql_p);
		$discount_partner = $row_p["0"];
		$moneys_partner = $row_p["1"];
		$moneys_partner_p = $row_p["2"];
		$moneys_partner_m =  $moneys_partner + $moneys_partner_p;
	}else{
		$discount_partner = 0;
		$moneys_partner = 0;
		$moneys_partner_p = 0;
		$moneys_partner_m = 0;
	}
	
	//echo '<div align="center" style="margin:0 auto;"><a href="/reflinks.php"><img src="img/SG.gif" width="740" height="170" border="0" align="absmiddle"></a></div>';

	echo '<table align="center" style="width:100%; margin:0 auto; border-collapse: separate; border-spacing: 5px 5px;">';
	echo '<tr>';
		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">Партнерская программа для статической рекламы</h1>';
			echo '<div align="center">Партнерское вознаграждение составляет до '.$partner_max_percent.'% от суммы заказов рекламы, оплаченных с внутреннего счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте, вашими рефералами</div>';
			echo '<h2 class="sp" style="text-align:left;">Партнерское вознаграждение начисляется от заказов:</h2>';
			echo '1. <a href="/advertise.php?ads=stat_links" title="Статических ссылок">Статических ссылок</a><br>';
			echo '2. <a href="/advertise.php?ads=txt_ob" title="Текстовых объявлений">Текстовых объявлений</a><br>';
			echo '3. <a href="/advertise.php?ads=banners" title="Статических баннеров 468х60">Статических баннеров (468х60)</a><br>';
			echo '4. <a href="/advertise.php?ads=banners" title="Статических баннеров 200х300">Статических баннеров (200х300)</a><br>';
			echo '5. <a href="/advertise.php?ads=banners" title="Статических баннеров 100x100">Статических баннеров (100х100)</a><br>';
			echo '6. <a href="/advertise.php?ads=frm_links" title="Ссылок во фрейме">Ссылок во фрейме</a><br>';
			echo '7. <a href="/advertise.php?ads=psevdo" title="Псевдо-динамических ссылок">Псевдо-динамических ссылок</a><br><br>';

			echo '<div align="center" style=""><span onClick="window.open(\'partner_stat.php\'); return false;" class="proc-btn">Подробнее</span></div>';
		echo '</td>';

		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">Партнерская программа для рекламных пакетов</h1>';
			echo '<div align="center">Партнерское вознаграждение составляет до '.$partner_max_percent_pack.'% от суммы заказов рекламы, оплаченных с внутреннего счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте, вашими рефералами</div>';
			echo '<h2 class="sp" style="text-align:left;">Партнерское вознаграждение начисляется от заказов:</h2>';
			echo '1. <a href="/advertise.php?ads=packet&p=1" title="Пакета рекламы №1">Пакета рекламы №1</a><br>';
			echo '2. <a href="/advertise.php?ads=packet&p=2" title="Пакета рекламы №2">Пакета рекламы №2</a><br>';
			echo '3. <a href="/advertise.php?ads=packet&p=3" title="Пакета рекламы №3">Пакета рекламы №3</a><br>';
			echo '4. <a href="/advertise.php?ads=packet&p=4" title="Пакета рекламы №4">Пакета рекламы №4</a><br>';
			echo '5. <a href="/advertise.php?ads=packet&p=5" title="Пакета рекламы №5">Пакета рекламы №5</a><br><br><br><br>';
	
			echo '<div align="center" style=""><span onClick="window.open(\'partner_packet.php\'); return false;" class="proc-btn">Подробнее</span></div>';
		echo '</td>';
	echo '</tr>';

	echo '<tr>';
		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">Скидки рефералам 1-го уровня</h1>';
			echo '<div align="left">Завлекайте новых рефералов-рекламодателей выгодными условиями!<br>Стимулируйте увеличение заказов рекламы по партнерской программе!</div>';

			if($discount_partner!=false) echo '<br><div align="center">В настоящий момент установлена скидка: <b>'.$discount_partner.'%</b></div>';
			else echo '<br><br>';

			echo '<br><br><br><br>';

			echo '<div align="center" style=""><span onClick="window.open(\'partner_discount.php\'); return false;" class="proc-btn">Подробнее</span></div>';
		echo '</td>';

		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">История партнерских начислений</h1>';
			echo '<div align="left">Всего партнерских начислений на сумму: <b>'.$moneys_partner.' руб.</b></div>';
			echo '<div align="left">При максимальном проценте вы могли бы получить: <b>'.$moneys_partner_m.' руб.</b></div>';
			echo '<div align="left">Потеряно прибыли на сумму: <b>'.$moneys_partner_p.' руб.</b></div>';

			echo '<br><div align="center">Здесь Вы можете посмотреть историю ваших партнерских начислений</div>';
			echo '<br><br>';

			echo '<div align="center" style=""><span onClick="window.open(\'partner_history.php\'); return false;" class="proc-btn">Подробнее</span></div>';
		echo '</td>';
	echo '</tr>';
	echo '</table>';
}

include('footer.php');?>