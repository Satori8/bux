<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$sql = mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$id = $row["id"];

	mysql_query("UPDATE `tb_ads_task` SET `status`='pause', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());

	echo '<fieldset class="okp">Задание остановлено!</fieldset>';

	echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view");</script>';
	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view">';

	
	exit();
}else{
	echo '<fieldset class="errorp">Ошибка! У Вас нет такого задания!</fieldset>';
	
	exit();
}
?>