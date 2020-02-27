<?php
$pagetitle = "Лидеры Проекта";
include('header.php');
require('config.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
	
}else{
    
echo '<div align="center">Самые активные по количеству рефералов 1 ур.:</div>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">Логин</th>';
	echo '<th class="top">Денег на счету</th>';
	echo '<th class="top">Рефералов 1ур.</th>';
echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM tb_users WHERE `id`!='1' ORDER BY referals DESC limit 7");
	while($stat=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td width="33%">'.$stat["username"].'</td>';
		echo '<td width="33%">'.round($stat["money"],2).' руб.</td>';
		echo '<td width="33%">'.$stat["referals"].'</td>';
		echo '</tr>';
	}
echo '</table><br /><br />';


echo '<div align="center">Самые активные по количеству рефералов 2 ур.:</div>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">Логин</th>';
	echo '<th class="top">Денег на счету</th>';
	echo '<th class="top">Рефералов 2ур.</th>';
echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM tb_users WHERE `id`!='1' ORDER BY referals2 DESC limit 7");
	while($stat=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td width="33%">'.$stat["username"].'</td>';
		echo '<td width="33%">'.round($stat["money"],2).' руб.</td>';
		echo '<td width="33%">'.$stat["referals2"].'</td>';
		echo '</tr>';
	}
echo '</table><br /><br />';



echo '<div align="center">Десятка активных новичков:</div>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">Логин</th>';
	echo '<th class="top">Реферер</th>';
	echo '<th class="top">Кликов</th>';
echo '</tr></thead>';
	$sql = mysql_query("SELECT * FROM tb_users WHERE `id`!='1' AND visits>0 ORDER BY id DESC limit 10");
	while($stat=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td width="33%">'.$stat["username"].'</td>';
		echo '<td width="33%">'.$stat["referer"].'</td>';
		echo '<td width="33%">'.$stat["visits"].'</td>';
		echo '</tr>';
	}
echo '</table><br /><br />';

}
include('footer.php');?>