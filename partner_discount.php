<?php
$pagetitle = "Партнерская программа (скидки рефералам I-го уровня)";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;

	if(isset($_POST["discount_partner"])) {
		$discount_partner = ( isset($_POST["discount_partner"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["discount_partner"])) ) ? intval(limpiar(trim($_POST["discount_partner"]))) : false;

		if($discount_partner>=0 && $discount_partner<=90) {
			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `discount_partner`='$discount_partner' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`discount_partner`) VALUES('$username','$discount_partner')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">Скидка должна быть в пределах от 0 до 90%</span>';
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
		$p_packet[1] = $row_p["p_packet_1"];
		$p_packet[2] = $row_p["p_packet_2"];
		$p_packet[3] = $row_p["p_packet_3"];
		$p_packet[4] = $row_p["p_packet_4"];
		$p_packet[5] = $row_p["p_packet_5"];
		$discount_partner = $row_p["discount_partner"];
	}else{
		$p_sl = 0;
		$p_txt = 0;
		$p_b468x60 = 0;
		$p_b200x300 = 0;
		$p_b100x100 = 0;
		$p_frm = 0;
		$p_psd = 0;
		$p_packet[1] = 0;
		$p_packet[2] = 0;
		$p_packet[3] = 0;
		$p_packet[4] = 0;
		$p_packet[5] = 0;
		$discount_partner = 0;
	}

	echo '<h1 class="sp" style="color: #ab0606; text-align:center; margin:0; margin-top:20px;">Завлекайте новых рефералов-рекламодателей выгодными условиями! Стимулируйте увеличение заказов рекламы по партнерской программе!</h1>';
	echo '<br>';
	echo 'Вы можете установить своим рефералам 1-го уровня бонус-скидку на размещение рекламы партнерской программы, тем самым стимулировать увеличение заказов рекламы вашими рефералами.';
	echo '<h5 class="sp" style="color:green">Как это работает?</h5>';
	echo 'Ваши рефералы 1-го уровня будут получать дополнительно, установленную Вами скидку на заказ рекламы в процентах от вашей прибыли по партнерской программе. Например: если Вы получаете бонус по партнерской программе 70% от заказа рекламы рефералом, то при установке скидки в 50% Вы получите отчисления 35% от заказа, а Ваш реферал получит скидку на заказ рекламы в 35%. ';
	echo '<h5 class="sp" style="color:red">Важно!</h5>';
	echo 'У Вас должна быть активирована партнерская программа.<br>';
	echo 'Скидка предоставляется на все активированные вами виды рекламы в партнерской программе.<br>';
	echo 'Скидка действует только при оплате заказанной рекламы рефералом с рекламного счета либо с внешней платежной системы но при условии что заказ рекламы оформлен после авторизации на проекте.<br><br>';

	echo '<form action="" method="POST" id="newform">';
	echo '<table class="tables_inv">';
		echo '<tr>';
			echo '<td align="center"><b>Установить скидку рефералам 1-го уровня</b></td>';
			echo '<td align="center" width="170"><input type="text" name="discount_partner" maxlength="2" value="'.$discount_partner.'" class="ok12" style="text-align:center; margin:2px; padding:3px; width:50px;" />&nbsp;&nbsp;&nbsp;(от 0 до 90%)</td>';
			echo '<td align="center" width="140"><input type="submit" class="proc-btn-t" style="float:none;" value="Сохранить"></td>';
		echo '</tr>';
	echo '</table>';
	echo '</form><br><br>';

	echo '<table class="tables_inv">';
	echo '<thead><tr>';
		echo '<th>Партнерская программа</th>';
		echo '<th>Общий % от заказов рефералов</th>';
		echo '<th>% скидки</th>';
		echo '<th>% прибыли</th>';
	echo '</tr></thead>';

	echo '<tbody>';
	echo '<tr>';
		echo '<td align="left">Статические ссылки</td>';
		echo '<td align="center"><b style="color: green;">'.$p_sl.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_sl/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_sl-($discount_partner*$p_sl/100)),2,".","").' %</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">Текстовые объявления</td>';
		echo '<td align="center"><b style="color: green;">'.$p_txt.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_txt/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_txt-($discount_partner*$p_txt/100)),2,".","").' %</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">Баннеры 468x60</td>';
		echo '<td align="center"><b style="color: green;">'.$p_b468x60.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_b468x60/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_b468x60-($discount_partner*$p_b468x60/100)),2,".","").' %</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">Баннеры 100х100</td>';
		echo '<td align="center"><b style="color: green;">'.$p_b100x100.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_b100x100/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_b100x100-($discount_partner*$p_b100x100/100)),2,".","").' %</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">Баннеры 200х300</td>';
		echo '<td align="center"><b style="color: green;">'.$p_b200x300.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_b200x300/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_b200x300-($discount_partner*$p_b200x300/100)),2,".","").' %</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">Ссылки во фрейме</td>';
		echo '<td align="center"><b style="color: green;">'.$p_frm.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_frm/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_frm-($discount_partner*$p_frm/100)),2,".","").' %</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">Псевдо-динамические ссылки</td>';
		echo '<td align="center"><b style="color: green;">'.$p_psd.'%</b> от заказа рефералов</td>';
		echo '<td align="center"><b>'.number_format(($discount_partner*$p_psd/100),2,".","").' %</b></td>';
		echo '<td align="center"><b>'.number_format(($p_psd-($discount_partner*$p_psd/100)),2,".","").' %</b></td>';
	echo '</tr>';

	for($i=1; $i<=5; $i++) {
		echo '<tr>';
			echo '<td align="left">Пакет рекламы №'.$i.'</td>';
			echo '<td align="center"><b style="color: green;">'.$p_packet[$i].'%</b> от заказа рефералов</td>';
			echo '<td align="center"><b>'.number_format(($discount_partner*$p_packet[$i]/100),2,".","").' %</b></td>';
			echo '<td align="center"><b>'.number_format(($p_packet[$i]-($discount_partner*$p_packet[$i]/100)),2,".","").' %</b></td>';
		echo '</tr>';
	}

	echo '</tbody>';
	echo '</table>';

}

include('footer.php');?>