<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Список онлайн посетителей</b></h3>';

mysql_query("DELETE FROM `tb_online` WHERE `date`<'".(time()-180)."'") or die(mysql_error());

$sql_user = mysql_query("SELECT * FROM `tb_online` WHERE `username`!='' ORDER BY `date` DESC");
$sql_guest = mysql_query("SELECT * FROM `tb_online` WHERE `username`='' ORDER BY `date` DESC");

$count_online = number_format((mysql_num_rows($sql_user) + mysql_num_rows($sql_guest)), 0, ".", "`");

echo '<div style="font-weight:bold; margin-top:10px;">Всего on-line: '.$count_online.'</div>';

echo '<table class="tables" style="margin:2px auto;">';
echo '<thead><tr align="center">';
	echo '<th width="200">Логин юзера</th>';
	echo '<th width="150">Время активности</th>';
	echo '<th width="150">IP</th>';
	echo '<th>Страница</th>';
echo '</tr></thead>';

echo '<tbody>';
if(mysql_num_rows($sql_user)>0) {
	while($row = mysql_fetch_assoc($sql_user)) {
		echo '<tr align="center">';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["username"]!=false ? "<b>".$row["username"]."</b>" : "-").'</td>';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["username"]!=false ? "<b>".DATE("H:i:s", $row["date"]-5*60)."</b>" : DATE("H:i:s", $row["date"]-5*60)).'</td>';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["username"]!=false ? "<b>".$row["ip"]."</b>" : $row["ip"]).'</td>';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["pagetitle"]!=false ? "<b>".$row["pagetitle"]."</b>" : "-").'</td>';
		echo '</tr>';
	}
}
if(mysql_num_rows($sql_guest)>0) {
	while($row = mysql_fetch_assoc($sql_guest)) {
		echo '<tr align="center">';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["username"]!=false ? "<b>".$row["username"]."</b>" : "-").'</td>';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.DATE("H:i:s", $row["date"]-5*60).'</td>';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["username"]!=false ? "<b>".$row["ip"]."</b>" : $row["ip"]).'</td>';
			echo '<td align="center" style="padding:0px 5px; margin:0px 5px;">'.($row["pagetitle"]!=false ? $row["pagetitle"] : "-").'</td>';
		echo '</tr>';
	}
}
echo '</tbody>';

echo '</table>';

?>