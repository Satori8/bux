<?php

$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$partnerid'"));
$pages_count = ceil($count / $perpage);

if ($page > $pages_count) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos=0;

$sql = mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$partnerid' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
$all_bl = mysql_num_rows($sql);

echo '<br><table class="tables">';
	echo '<thead><tr>';
	echo '<th align="left" class="top"># id, логин рекламодателя</th>';
	echo '<th align="center" class="top">Активных заданий у рекламодателя</th>';
	echo '<th align="center" class="top">[удалить]</th>';
	echo '</tr></thead>';

if($all_bl>0) {
	while ($row = mysql_fetch_array($sql)) {
		$sql_t = mysql_query("SELECT * FROM `tb_ads_task` WHERE `user_id`='".$row["rek_id"]."' AND `status`='pay' AND `totals`>'0'");
		$all_task = mysql_num_rows($sql_t);

		$sql_u = mysql_query("SELECT `id`,`username`,`reiting`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `id`='".$row["rek_id"]."'");
		if(mysql_num_rows($sql_u)>0) {
			$row_u = mysql_fetch_array($sql_u);

			$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];
			$info_wall = '<a href="/wall?uid='.$row_u["id"].'" target="_blank"><img src="../img/wall20.png" title="Стена пользователя '.$row_u["username"].'" width="20" border="0" align="absmiddle" />';
			if($wall_com>0) {
				$info_wall.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
			}elseif($wall_com<0) {
				$info_wall.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
			}else{
				$info_wall.= '<b style="color:#000000;">0</b></a>';
			}

			$info_user = '<b>'.$row_u["username"].'</b> <img src="img/reiting.png" border="0" alt="" align="middle" title="Рейтинг" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> '.$info_wall.'';
		}else{
			$info_user = '<span style="color:#FF0000;">Рекламодатель удален</span>';
		}

		echo '<tr>';

		echo '<td align="left" style="padding-left:10px;">'.$info_user.'</td>';

		echo '<td align="center"><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&task_search=4&task_name='.$row_u["id"].'"><b>'.$all_task.'</b></a></td>';
		echo '<td align="center"><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&amp;op='.limpiar($_GET["op"]).'&amp;bl=del&amp;bid='.$row["rek_id"].'"><img src="img/close.gif" border="0" alt="" align="middle" title="Удалить рекламодателя из Black List" /></a></td>';
		echo '</tr>';
	}
	echo '<tr>';
		echo '<td colspan="3">Найдено записей <b>'.$count.'</b>, показано <b>'.$all_bl.'</b><br>';
		if($count>$perpage) {echo '<div align="left"><b>Страницы:</b> '; universal_link_bar($page, $count, $pages_count, 8, $sort, $sort_z, $type, $task_search, $task_name, $task_auto, $task_price); echo '</div>';}
		echo '</td>';
	echo '</tr>';
}else{
	echo '<tr><td align="center" colspan="3">У Вас нет рекламодателей в черном списке</td></tr>';
}
echo '</table><br>';

?>