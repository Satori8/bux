<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$id = $row["id"];
	$wait = $row["wait"];
	$totals = $row["totals"];
	$zdprice = $row["zdprice"];
	$rek_name = $row["username"];

	if($row["status"]=="wait" && $row["wait"]<=0  && $row["totals"] <= 0) {

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id'") or die(mysql_error());
		echo '<fieldset class="okp">Задание #'.$id.' успешно удалено!</fieldset>';

	}elseif( $row["wait"] > 0 ) {

		echo '<fieldset class="errorp">Ошибка! Чтобы удалить задание необходимо проверить поданные заявки на проверку выполнения задания!</fieldset>';

	}elseif($row["totals"] > 0 ) {

		$sql_a = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
		$nacenka_task = mysql_result($sql_a,0,0);

		$money_back = ( (100 + $nacenka_task) / 100) * ($totals * $zdprice);

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id'") or die(mysql_error());
		mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_back', `money_rek`=`money_rek`-'$money_back' WHERE `username`='$rek_name'") or die(mysql_error());

		echo '<fieldset class="okp">Задание #'.$id.' успешно удалено!</fieldset>';

	}elseif( $row["totals"] <= 0 ) {

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id'") or die(mysql_error());
		echo '<fieldset class="okp">Задание #'.$id.' успешно удалено!</fieldset>';

	}else{
		echo '<fieldset class="errorp">Ошибка!</fieldset>';
	}
}else{
	echo '<fieldset class="errorp">Ошибка! Такого задания нет!</fieldset>';
}
?>
