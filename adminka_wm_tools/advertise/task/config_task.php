<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Настройки заданий</b></h3>';

if(count($_POST)>0) {
	$limit_min_task = (isset($_POST["limit_min_task"]) && abs(intval(trim($_POST["limit_min_task"])))>0) ? abs(intval(trim($_POST["limit_min_task"]))) : 1;
	$task_cena_pay = isset($_POST["task_cena_pay"]) ? abs(round(floatval(str_ireplace(",", ".", trim($_POST["task_cena_pay"]))), 2)) : 0.01;

	$task_comis_add = isset($_POST["task_comis_add"]) ? abs(round(intval(str_ireplace(",", ".", trim($_POST["task_comis_add"]))), 2)) : 20;
	$task_comis_del = isset($_POST["task_comis_del"]) ? abs(round(intval(str_ireplace(",", ".", trim($_POST["task_comis_del"]))), 2)) : 15;

	$task_cena_vip = isset($_POST["task_cena_vip"]) ? abs(round(floatval(str_ireplace(",", ".", trim($_POST["task_cena_vip"]))), 2)) : 1;
	$task_max_vip = isset($_POST["task_max_vip"]) ? abs(round(floatval(str_ireplace(",", ".", trim($_POST["task_max_vip"]))), 2)) : 3;

	$task_cena_up = isset($_POST["task_cena_up"]) ? abs(round(floatval(str_ireplace(",", ".", trim($_POST["task_cena_up"]))), 2)) : 1;
	$task_sale_up = isset($_POST["task_sale_up"]) ? abs(round(floatval(str_ireplace(",", ".", trim($_POST["task_sale_up"]))), 2)) : false;

	mysql_query("UPDATE `tb_config` SET `price`='$limit_min_task' WHERE `item`='limit_min_task' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$task_cena_pay' WHERE `item`='cena_task' AND `howmany`='1'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$task_comis_add' WHERE `item`='nacenka_task' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$task_comis_del' WHERE `item`='task_comis_del' AND `howmany`='1'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$task_cena_vip' WHERE `item`='task_cena_vip'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$task_max_vip' WHERE `item`='task_max_vip'") or die(mysql_error());

	mysql_query("UPDATE `tb_config` SET `price`='$task_cena_up' WHERE `item`='task_cena_up'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$task_sale_up' WHERE `item`='task_sale_up'") or die(mysql_error());

	$save_ok = true;
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='limit_min_task' AND `howmany`='1'");
$limit_min_task = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
$task_cena_pay = number_format(mysql_result($sql,0,0), 2, ".", "");


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
$task_comis_add = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_comis_del' AND `howmany`='1'");
$task_comis_del = number_format(mysql_result($sql,0,0), 0, ".", "");


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_vip'");
$task_cena_vip = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_max_vip'");
$task_max_vip = number_format(mysql_result($sql,0,0), 0, ".", "");


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_up'");
$task_cena_up = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_sale_up'");
$task_sale_up = number_format(mysql_result($sql,0,0), 0, ".", "");


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table class="tables" style="width:600px;">';
	echo '<thead><tr><th>Параметр</th><th width="120">Значение</th></tr></thead>';
	echo '<tr>';
		echo '<td><b>Минимальная цена за выполнение задания:</b>, руб.</td>';
		echo '<td><input type="text" name="task_cena_pay" value="'.$task_cena_pay.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Минимальное кол-во заданий для заказа</b>, шт.</td>';
		echo '<td><input type="text" name="limit_min_task" value="'.$limit_min_task.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Стоимость размещения задания в VIP блоке:</b>, руб.</td>';
		echo '<td><input type="text" name="task_cena_vip" value="'.$task_cena_vip.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Максимальное количество заданий в VIP блоке:</b>, шт.</td>';
		echo '<td><input type="text" name="task_max_vip" value="'.$task_max_vip.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Стоимость поднятия задания:</b>, руб.</td>';
		echo '<td><input type="text" name="task_cena_up" value="'.$task_cena_up.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Скидка при оплате за 100 и более авто-поднятий</b>, %.</td>';
		echo '<td><input type="text" name="task_sale_up" value="'.$task_sale_up.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
		echo '<td><b>Комиссия системы (размещение задания)</b>, %.</td>';
		echo '<td><input type="text" name="task_comis_add" value="'.$task_comis_add.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Комиссия системы (удаление задания)</b>, %.</td>';
		echo '<td><input type="text" name="task_comis_del" value="'.$task_comis_del.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="Cохранить изменения" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

if(count($_POST)>0 && isset($save_ok)) {
	echo '<span id="info-msg" class="msg-ok">Изменения успешно сохранены!</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 50);
		HideMsg("info-msg", 1500);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

?>