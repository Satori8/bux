<?php if(!isset($_SESSION)){session_start();}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=cp1251">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta name="Author" content="supreme-garden.ru">
	<link rel="stylesheet" type="text/css" href="style2.css">
	<link rel="icon" href="/favicon.ico" type="image/x-icon"/>
	<title><?php echo strtoupper($_SERVER["HTTP_HOST"]);?> | Автоматический серфинг YouTube</title>
</head>

<body style="border:0;" oncontextmenu="javascript:return false">
<?php

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	require('../config.php');
	require('../funciones.php');

	$username = uc($_SESSION["userLog"]);
	$date_now = strtotime(DATE("d.m.Y"));

	$sql_pay = mysql_query("SELECT `id` FROM `tb_ads_autoyoutube_visits` WHERE `username`='$username' AND `type`='visit' AND `date`>'$date_now'") or die(mysql_error());
	$kolvo_s = mysql_num_rows($sql_pay);

	$sql_sum = mysql_query("SELECT sum(`money`) FROM `tb_ads_autoyoutube_visits` WHERE `username`='$username' AND `type`='visit' AND `date`>'$date_now'") or die(mysql_error());
	$sum_s = mysql_result($sql_sum,0,0);
	@mysql_close();
}
?>
<table border="0" width="100%" height="100%">
<tr><td align="center">На данный момент видеороликов<br>для просмотра нет.</td></tr>
<?php
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
echo '<tr><td align="center"><b>Статистика за сегодня ('.DATE("d.m.Y").'):</b><br>Заработано: '.$sum_s.' руб.<br>Просмотрено ссылок: '.$kolvo_s.'</td></tr>';
}
?>
<tr><td align="center" valign="center"><br><b><a href="/advertise.php?ads=auto_serf_you" class="sub-blue160" target="_blank">Добавить ролик в Авто-серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></a></b></td></tr>
</table>
</body>
</html>