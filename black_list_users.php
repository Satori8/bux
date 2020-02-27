<?php
$pagetitle="Список заблокированных пользователей";
include('header.php');
require("navigator/navigator.php");
require('config.php');

$perpage = 30;
$sql_p = mysql_query("SELECT `id` FROM `tb_black_users`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

$sql = mysql_query("SELECT * FROM `tb_black_users` ORDER BY `id` DESC LIMIT $start_pos,$perpage");
$all_users = mysql_num_rows($sql);

echo '<div align="justify">';
	echo 'Для того что бы снять Бан, вам нужно пополнить свой рекламный счет на 15.00 руб., после чего сообщить Администратору о желании разблокировать аккаунт.';
	echo '<br>При повторном нарушении правил проекта, WMID пользователя будет внесен в черный список.';
	echo '<br>На оплату штрафа дается 5 дней, после чего аккаунт будет удален. Администрация оставляет за собой право, удалять аккаунты нарушителей незамедлительно.';
echo '</div>';

echo '<table style="margin:0; padding:0; margin-bottom:10px; margin-top:15px;"><tr>';
echo '<td valign="top">';
	echo 'Пользователей заблокировано всего: <b>'.$count.'</b><br>Показано записей на странице: <b>'.$all_users.'</b> из <b>'.$count.'</b>';
echo '</td>';
echo '</table>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

echo '<table class="tables">';
echo '<thead><tr align="center">';
	echo '<th>#</th>';
	echo '<th>Логин</th>';
	echo '<th>Причина блокировки</th>';
	echo '<th>Дата блокировки</th>';
echo '</tr></thead>';

if($all_users>0) {
	$i=$start_pos;

	while ($row = mysql_fetch_array($sql)) {
		$i++;

		echo '<tr align="center">';
			echo '<td>'.$i.'</td>';
			echo '<td><b>'.$row["name"].'</b></td>';
			echo '<td>'.$row["why"].'</td>';
			echo '<td>'.DATE("d.m.Y H:i", $row["time"]).'</td>';
		echo '</tr>';
	}
	echo '</table>';
}else{
	echo '<tr>';
		echo '<td colspan="4" align="center"><b>Заблокированных пользователей нет</b></td>';
	echo '</tr>';
	echo '</table>';
}
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

include('footer.php');
?>