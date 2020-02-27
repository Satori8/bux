<?php
$pagetitle = "Партнерская программа для статической рекламы";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
	$partner_max_percent = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_active' AND `howmany`='1'");
	$partner_active = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day'");
	if(mysql_num_rows($sql)>0) {
		$row_pd = mysql_fetch_array($sql);
		$partner_count_day = $row_pd["howmany"];
		$partner_count_per = $row_pd["price"];
	}else{
		$partner_count_day = 1;
		$partner_count_per = 1;
	}

	echo '<h1 class="sp" style="color: #ab0606; text-align:center; margin:0; margin-top:20px;">Партнерское вознаграждение составляет до '.$partner_max_percent.'%</h1>';
	echo '<div align="center">от суммы заказов рекламы, оплаченных с рекламного счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте, вашими рефералами</div>';
	echo '<h2 class="sp" style="text-align:left;">Партнерское вознаграждение начисляется от заказов:</h2>';
		echo '1. <a href="/advertise.php?ads=stat_links" title="Статических ссылок">Статических ссылок</a><br>';
		echo '2. <a href="/advertise.php?ads=txt_ob" title="Текстовых объявлений">Текстовых объявлений</a><br>';
		echo '3. <a href="/advertise.php?ads=banners" title="Статических баннеров 468х60">Статических баннеров (468х60)</a><br>';
		echo '4. <a href="/advertise.php?ads=banners" title="Статических баннеров 200х300">Статических баннеров (200х300)</a><br>';
		echo '5. <a href="/advertise.php?ads=banners" title="Статических баннеров 100x100">Статических баннеров (100х100)</a><br>';
		echo '6. <a href="/advertise.php?ads=frm_links" title="Ссылок во фрейме">Ссылок во фрейме</a><br>';
		echo '7. <a href="/advertise.php?ads=psevdo" title="Псевдо-динамических ссылок">Псевдо-динамических ссылок</a><br><br>';

	echo '<h5 class="sp">Как стать участником партнерской программы?</h5>';
	echo '<div style="text-align:justify;">Чтобы участвовать в партнерской программе, нужно всего лишь активировать ее. Для активации потребуется всего лишь <b>'.$partner_active.' баллов рейтинга</b> (активация всего 1 раз для каждого вида рекламы), при этом ваше партнерское вознаграждение сразу повышается с 0 до 1%. После активации вы можете повышать процент партнерского вознаграждения, размещая рекламу на нашем сайте.</div>';
	
	echo '<h5 class="sp">Какой процент я буду получать?</h5>';
	echo '<div style="text-align:justify;">Процент вашего вознаграждения напрямую зависит от вашего конкретного заказа. <b>'.$partner_count_day.'</b> дня заказа, равны <b>'.$partner_count_per.'%</b> получаемому (пожизненно) от заказов рефералов, оплаченных с рекламного счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте.<br><br>Например: Вы разместили рекламу на <b>'.($partner_count_day*2).'</b> дней - ваш партнерский процент равен <b>'.($partner_count_per*2).'%</b>. Вы пожизненно получаете <b>'.($partner_count_per*2).'%</b> от общей суммы заказа ваших рефералов. (У рефералов естественно ничего не отнимается, Вы получите свои проценты за счет системы).<br><br>Все ваши заказы суммируются и соответственно суммируется и увеличивается ваш партнерский процент. Таким образом, чем больше вы рекламируете, тем больший процент вы получаете от заказов рефералов. Максимально возможное вознаграждение <b>'.$partner_max_percent.'%</b>.</div>';

	echo '<h5 class="sp" style="color:#FF0000;">Важно!</h5>';
	echo '<div style="text-align:justify;">Для активации требуется <b>'.$partner_active.' баллов рейтинга</b> (активация всего 1 раз для каждого вида рекламы). Для <b>увеличения партнерского вознаграждения</b> рекламу необходимо оплачивать с <b>рекламного счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте</b>. Вознаграждение будет начисляться на ваш основной счет и в любой момент будет доступно для вывода из системы.</div>';
	echo '<br><br>';

	if(isset($_GET["active"]) && limpiar($_GET["active"])=="1") {
		if($p_sl>0) {
			echo '<span class="msg-error">Для статических ссылок партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_sl`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_sl`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">У Вас недостаточно баллов рейтинга для активации партнерского вознаграждения!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="2") {
		if($p_txt>0) {
			echo '<span class="msg-error">Для текстовых объявлений партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_txt`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_txt`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">У Вас недостаточно баллов рейтинга для активации партнерского вознаграждения!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="3") {
		if($p_b468x60>0) {
			echo '<span class="msg-error">Для баннеров 468x60 партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_b468x60`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_b468x60`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">У Вас недостаточно баллов рейтинга для активации партнерского вознаграждения!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="4") {
		if($p_b100x100>0) {
			echo '<span class="msg-error">Для баннеров 100x100 партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_b100x100`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_b100x100`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">У Вас недостаточно баллов рейтинга для активации партнерского вознаграждения!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="5") {
		if($p_b200x300>0) {
			echo '<span class="msg-error">Для баннеров 200x300 партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_b200x300`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_b200x300`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
		echo '<span class="msg-error">У Вас недостаточно баллов рейтинга для активации партнерского вознаграждения!</span>';
	}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="6") {
		if($p_frm>0) {
			echo '<span class="msg-error">Для ссылок во фрейме партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_frm`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_frm`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">У Вас недостаточно баллов рейтинга для активации партнерского вознаграждения!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="7") {
		if($p_psd>0) {
			echo '<span class="msg-error">Для псевдо-динамических ссылок партнерская программа уже активирована!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_psd`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_psd`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">У Вас недостаточно баллов рейтинга на счете для активации партнерского вознаграждения!</span>';
		}
	}

	$sql_p = mysql_query("SELECT * FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_array($sql_p);

		$p_sl = $row_p["p_sl"];
		$p_txt = $row_p["p_txt"];
		$p_b468x60 = $row_p["p_b468x60"];
		$p_b200x300 = $row_p["p_b200x300"];
		$p_b100x100 = $row_p["p_b100x100"];
		$p_frm = $row_p["p_frm"];
		$p_psd = $row_p["p_psd"];
		$discount_partner = $row_p["discount_partner"];
	}else{
		$p_sl = 0;
		$p_txt = 0;
		$p_b468x60 = 0;
		$p_b200x300 = 0;
		$p_b100x100 = 0;
		$p_frm = 0;
		$p_psd = 0;
		$discount_partner = 0;
	}

	echo '<a name="tab"></a><table class="tables_inv">';
	echo '<thead><tr>';
		echo '<th>Партнерская программа</th>';
		echo '<th>Процент от заказов рефералов</th>';
		echo '<th></th>';
	echo '</tr></thead>';

	echo '<tbody>';
		echo '<tr>';
			echo '<td>Статические ссылки</td>';
			if($p_sl>0) {
				echo '<td align="center"><b style="color: green;">'.$p_sl.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=stat_links">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=1">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>Текстовые объявления</td>';
			if($p_txt>0) {
				echo '<td align="center"><b style="color: green;">'.$p_txt.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=txt_ob">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=2">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
		}
		echo '</tr>';
		echo '<tr>';
			echo '<td>Баннеры 468x60</td>';
			if($p_b468x60>0) {
				echo '<td align="center"><b style="color: green;">'.$p_b468x60.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=banners">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=3">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>Баннеры 100х100</td>';
			if($p_b100x100>0) {
				echo '<td align="center"><b style="color: green;">'.$p_b100x100.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=banners">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=4">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>Баннеры 200х300</td>';
			if($p_b200x300>0) {
				echo '<td align="center"><b style="color: green;">'.$p_b200x300.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=banners">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=5">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>Ссылки во фрейме</td>';
			if($p_frm>0) {
				echo '<td align="center"><b style="color: green;">'.$p_frm.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=frm_links">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=6">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>Псевдо-динамические ссылки</td>';
			if($p_psd>0) {
				echo '<td align="center"><b style="color: green;">'.$p_psd.'%</b> от заказа рефералов</td>';
				echo '<td align="center"><a href="/advertise.php?ads=psevdo">Увеличить %</a></td>';
			}else{
				echo '<td align="center">[0%] Не активирована</td>';
				echo '<td align="center"><a href="?active=7">Активировать за <b>'.$partner_active.'</b> баллов рейтинга</a></td>';
			}
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

}

include('footer.php');?>