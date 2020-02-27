<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;
$up = (isset($_POST["up"]) && (intval($_POST["up"])==600 | intval($_POST["up"])==1200 | intval($_POST["up"])==1800 | intval($_POST["up"])==3600 | intval($_POST["up"])==7200 | intval($_POST["up"])==10800 | intval($_POST["up"])==21600 | intval($_POST["up"])==43200 | intval($_POST["up"])==86400 )) ? intval($_POST["up"]) : "8";
$sql = mysql_query("SELECT `id`,`date_up` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$id = $row["id"];

	//if($row["date_up"] < (time() - 1*60*60) ) {
		//if($money_rb>="0.25") {
			mysql_query("UPDATE `tb_ads_task` SET `$up`='$up', WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
			//mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'0.25' WHERE `username`='$username'") or die(mysql_error());

			echo '<span class="msg-ok">Функция авто-поднятия задания в списке активирована!</span>';
			
			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?option=task_view");</script>';
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?option=task_view">';

			include('footer.php');
			exit();
		//}else{
			//echo '<fieldset class="errorp">Ошибка! На Вашем рекламном счету недостаточно средств для поднятия задания в списке! Стоимость одного поднятия составляет - 0.25 руб.</fieldset>';
			//include('footer.php');
			//exit();
		//}
	//}else{
		//echo '<fieldset class="errorp">Ошибка! Поднятие задания возможно 1 раз в час. Стоимость одного поднятия 0.25р.</fieldset>';
		//include('footer.php');
		//exit();
	//}
}else{
	echo '<fieldset class="errorp">Ошибка! У Вас нет такого задания!</fieldset>';
	include('footer.php');
	exit();
}
?>