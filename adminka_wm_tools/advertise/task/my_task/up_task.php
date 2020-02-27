<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$sql = mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$rid = $row["id"];

	mysql_query("UPDATE `tb_ads_task` SET `date_up`='".time()."', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
}else{
	echo '<fieldset class="errorp">Ошибка! У Вас нет такого задания!</fieldset>';
	exit();
}
?>