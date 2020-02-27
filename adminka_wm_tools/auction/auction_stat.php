<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Статистика аукциона:</b></h3>';

$sql = mysql_query("SELECT `id` FROM `tb_auction` WHERE `status`='1' AND `timer_end`>'".time()."'");
$act_auc = mysql_num_rows($sql);

$sql = mysql_query("SELECT `id` FROM `tb_auction` WHERE `status`='0'");
$all_auc = mysql_num_rows($sql);

$sql = mysql_query("SELECT `id`,`kolstv`,`stavka` FROM `tb_auction` WHERE `status`='0' ORDER BY `kolstv` DESC");
if(mysql_num_rows($sql) > 0) {
	$row = mysql_fetch_array($sql);
	$id_a = $row["0"];
	$kolstv_a = $row["1"];
	$sum_max = $kolstv_a * $row["2"];
}else{
	$id_a = false;
	$kolstv_a = false;
	$sum_max = false;
}


$sql = mysql_query("SELECT sum(`summa`) FROM `tb_auction` WHERE `status`='0'");
$sum_stav = mysql_result($sql,0,0);

$sql = mysql_query("SELECT sum(`proc`) FROM `tb_auction` WHERE `status`='0'");
$doxod_sys = mysql_result($sql,0,0);

echo '<table>';
echo '<tr><th width="230" align="left">Активных лотов:</th><td><b style="color:#000; font-size:115%;">'.number_format($act_auc,0,".","`").'</b></td></tr>';
echo '<tr><th width="230" align="left">Завершенных лотов:</th><td><b style="color:#000; font-size:115%;">'.number_format($all_auc,0,".","`").'</b></td></tr>';
if($id_a==true) {
	echo '<tr><th width="230" align="left">Максимальное кол-во ставок:</th><td><b style="color:#000; font-size:115%;">'.$kolstv_a.'</b> в аукционе #<b>'.$id_a.'</b> на сумму <b>'.number_format($sum_max,2,".","`").'</b> руб.</td></tr>';
}else{
	echo '<tr><th width="230" align="left">Максимальное кол-во ставок:</th><td>Аукционы еще не проводились</td></tr>';
}
echo '<tr><th width="230" align="left">Заработали пользователи:</th><td><b style="color:#000; font-size:115%;">'.number_format($sum_stav,2,".","`").' руб.</b></td></tr>';
echo '<tr><th width="230" align="left">Доход системы:</th><td><b style="color:#000; font-size:115%;">'.number_format($doxod_sys,2,".","`").' руб.</b></td></tr>';

echo '</table>';

?>