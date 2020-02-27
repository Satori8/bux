<?php
$pagetitle="Активные пользователи по странам";
include('header.php');
require_once('.zsecurity.php');
require('config.php');

$sql = mysql_query("SELECT `country_cod`, count(`country_cod`) as `count_cc` FROM `tb_users` WHERE `country_cod`!='' GROUP BY `country_cod` ORDER BY `count_cc` DESC");
echo '<table class="tables_inv">';
echo '<thead><tr align="center">';
	echo '<th align="center">№</th>';
	echo '<th align="center">Флаг</th>';
	echo '<th align="left">Страна</th>';
	echo '<th width="20" nowrap="nowrap">чел.</th>';
	echo '<th align="right" width="60"></th>';
echo '</tr></thead>';
if (mysql_num_rows($sql) > 0) {
	$i = 0;
	while ($row = mysql_fetch_row($sql)) {
		$i++;
		$percent = $row["1"] * 100 / $users_all;
		if ($row["0"] == "") { $flag = ""; } else { $flag = $row["0"]; }
		echo '<tr>';
			echo '<td align="center" width="20" nowrap="nowrap"><span>'.$i.'</span></td>';
			echo '<td align="center" width="20" nowrap="nowrap"><img src="//'.$_SERVER['HTTP_HOST'].'/img/flags/'.strtolower($row["0"]).'.gif" width="'.( (strtolower($row["0"])=="a1" | strtolower($row["0"])=="a2") ? "18" : "16").'" border="0" alt="" valign="baseline" title="'.get_country($row["0"]).'" style="margin:0; padding:0;" /></td>';
			echo '<td align="left"><span>'.get_country($row["0"]).'</span></td>';
			echo '<td align="center"><span style="color:green;">'.$row["1"].'</span></td>';
			echo '<td align="center" nowrap="nowrap"><span style="color:blue;">'.round(number_format($percent,2,".","`"),2).'</span> %</td>';
		echo '</tr>';
	}
}
echo '</table>';

include('footer.php');?>