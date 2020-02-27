<?php
$pagetitle="Чёрный список сайтов, запрещённых на ".strtoupper($_SERVER["HTTP_HOST"]);
include('header.php');
require("navigator/navigator.php");
require('config.php');

$sql = mysql_query("SELECT `id` FROM `tb_black_sites`");
$all_sites = mysql_num_rows($sql);

echo '<div align="justify">';
	echo '<img class="img-left" src="/img/warning_.png" alt="" title="" />Ниже представлен список сайтов, запрещённых к просмотру на '.strtoupper($_SERVER["HTTP_HOST"]).'. По разным причинам, администрация проекта считает эти сайты опасными для просмотра пользователями. Эти сайты могут содержать опасные вирусы, противозаконные материалы, воровать пароли, или же нарушать правила. Мы настоятельно рекомендуем не посещать эти сайты.';
echo '</div><br><br>';

$perpage = 30;
$count = $all_sites;
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

echo '<table class="tables">';
echo '<thead>';
	echo '<tr align="center">';
		echo '<th class="top" colspan="2">Запрещённый URL-адрес</th>';
		echo '<th class="top">Причина запрета</th>';
	echo '</tr>';
echo '</thead>';

$sql = mysql_query("SELECT * FROM `tb_black_sites` ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="left">';
			echo '<td width="18"><img src="/img/warn.png" style="margin:0px;" align="middle" /></td>';
			echo '<td width="45%">'.$row["domen"].'</td>';
			echo '<td width="55%">'.$row["cause"].'</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center"><td colspan="3">В черном списке еще нет сайтов!</td></tr>';
}
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

include('footer.php');?>