<?php
if(!DEFINED("WORK_INFO")) {die ("Hacking attempt!");}

$count_serf = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='1' AND ( `totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) "));
$count_aserf = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_auto` WHERE `status`='1' AND `totals`>'0'"));
$count_mails = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`='1' AND `totals`>'0'"));
$count_task = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `status`='pay' AND `totals`>'0'"));
$count_tests = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `status`='1' AND `balance`>=`cena_advs`"));

echo '<table class="tables" style="margin:5px auto;">';
echo '<thead>';
echo '<tr>';
	echo '<th align="center" width="20%">Серфинг</th>';
	echo '<th align="center" width="20%">Автосерфинг</th>';
	echo '<th align="center" width="20%">Письма</th>';
	echo '<th align="center" width="20%">Тесты</th>';
	echo '<th align="center" width="20%">Задания</th>';
echo '</tr>';
echo '<thead>';
echo '<tbody>';
echo '<tr>';
	echo '<td align="center">'.$count_serf.' шт.</td>';
	echo '<td align="center">'.$count_aserf.' шт.</td>';
	echo '<td align="center">'.$count_mails.' шт.</td>';
	echo '<td align="center">'.$count_tests.' шт.</td>';
	echo '<td align="center">'.$count_task.' шт.</td>';
echo '</tr>';
echo '<tbody>';
echo '</table>';

?>