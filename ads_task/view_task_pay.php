<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

echo '<b>Статистика по выполнению задания #'.$rid.'</b><br />';

function universal_link_bar($page, $count, $pages_count, $show_link) {
	if ($pages_count == 1) return false;
		$sperator = ' &nbsp;';
		$style = 'style="font-weight: bold;"';
		$begin = $page - intval($show_link / 2);
		unset($show_dots);

		if ($pages_count <= $show_link + 1) $show_dots = 'no';
		if (($begin > 2) && !isset($show_dots) && ($pages_count - $show_link > 2)) {
			echo '<a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s=1> 1 </a> ';
		}

		for ($j = 0; $j < $page; $j++) {
			if (($begin + $show_link - $j > $pages_count) && ($pages_count-$show_link + $j > 0)) {
				$page_link = $pages_count - $show_link + $j;

				if (!isset($show_dots) && ($pages_count-$show_link > 1)) {
					echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.($page_link - 1).'><b>...</b></a> ';
					$show_dots = "no";
				}

				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.$page_link.'>'.$page_link.'</a> '.$sperator;
			} else continue;
		}

		for ($j = 0; $j <= $show_link; $j++) {
			$i = $begin + $j;

			if ($i < 1) { $show_link++; continue;}

			if (!isset($show_dots) && $begin > 1) {
				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.($i-1).'><b>...</b></a> ';
				$show_dots = "no";
			}

			if ($i > $pages_count) break;
			if ($i == $page) {
				echo ' <a '.$style.' ><b style="color:#e8e8e8; text-decoration:underline;">'.$i.'</b></a> ';
			}else{
				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.$i.'>'.$i.'</a> ';
			}

			if (($i != $pages_count) && ($j != $show_link)) echo $sperator;
			if (($j == $show_link) && ($i < $pages_count)) {
				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.($i+1).'><b>...</b></a> ';
		}
	}

	if ($begin + $show_link + 1 < $pages_count) {
		echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.$pages_count.'> '.$pages_count.' </a>';
	}
	return true;
}

$perpage = 30;
if (empty($_GET["s"]) || ($_GET["s"] <= 0)) {
	$page = 1;
}else{
	$page = intval($_GET["s"]);
}

$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`!='' AND `rek_name`='$username' AND `ident`='$rid'"));
$pages_count = ceil($count / $perpage);

if ($page > $pages_count) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos=0;

if($count>$perpage){
	echo '<div align="center"><b>Страницы:</b> '; universal_link_bar($page, $count, $pages_count, 8); echo '</div>';
}

echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th align="center" class="top">Пользователь</th>';
		echo '<th align="center" class="top" colspan="2">Статус</th>';

		echo '<th align="center" class="top">Информация</th>';
		echo '<th align="center" class="top">Ответ</th>';

/*
		echo '<th align="center" class="top">IP</th>';
		echo '<th align="center" class="top">Ответ</th>';
		echo '<th align="center" class="top">Начало / Конец</th>';
		echo '<th align="center" class="top">Цена</th>';
*/
	echo '</tr><thead>';

$sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`!='' AND `rek_name`='$username' AND `ident`='$rid' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {

		$sql_u = mysql_query("SELECT `id`,`username`,`reiting`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `username`='".$row["user_name"]."'");
		if(mysql_num_rows($sql_u)>0) {
			$row_u = mysql_fetch_array($sql_u);

			$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];
			$info_wall = '<a href="/wall?uid='.$row_u["id"].'" target="_blank"><img src="../img/wall20.png" title="Информация о пользователе '.$row_u["username"].'" width="20" border="0" align="absmiddle" />';
			if($wall_com>0) {
				$info_wall.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
			}elseif($wall_com<0) {
				$info_wall.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
			}else{
				$info_wall.= '<b style="color:#000000;">0</b></a>';
			}

			$info_user = '#'.$row_u["id"].'<br>'.$row_u["username"].'<br><img src="img/reiting.png" border="0" alt="" align="absmiddle" title="Рейтинг" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> '.$info_wall.'';
		}else{
			$info_user = '#'.$row["user_id"].'<br>'.$row["user_name"].'<br><span style="color:#FF0000;">Исполнитель</span>';
		}

		echo '<tr>';
		echo '<td align="center" style="width:100px;">'.$info_user.'</td>';

		if($row["status"]=="bad")
			echo '<td align="center" style="word-wrap: break-word; width:16px; border-right:0px solid #CCC;"><img src="img/no.png" border="0" width="16" alt="" align="middle" title="Выполнение не подтверждено" /></td><td align="left" style="border-left:0px solid #CCC;">Задание выполнено, но рекламодатель не подтвердил выполнение<br><b>'.$row["why"].'</b></td>';
		elseif($row["status"]=="good")
			echo '<td align="center" style="word-wrap: break-word; width:16px; border-right:0px solid #CCC;"><img src="img/yes.png" border="0" width="16" alt="" align="middle" title="Задание успешно выполнено" /></td><td align="left" style="border-left:0px solid #CCC;">Задание успешно выполнено</td>';
		elseif($row["status"]=="wait")
			echo '<td align="center" style="word-wrap: break-word; width:16px; border-right:0px solid #CCC;"><img src="img/help.png" border="0" width="16" alt="" align="middle" title="Задание выполнено и ожидает проверки" /></td><td align="left" style="border-left:0px solid #CCC;">Задание выполнено и ожидает проверки рекламодателем</td>';
		elseif($row["status"]=="dorab")
			echo '<td align="center" style="word-wrap: break-word; width:16px; border-right:0px solid #CCC;"><img src="img/help.png" border="0" width="16" alt="" align="middle" title="'.$row["why"].'" /></td><td align="left" style="border-left:0px solid #CCC;">Задание выполнено, но рекламодатель отправил на доработку</td>';
		else{
			echo '<td align="center" style="border-right:0px solid #CCC;></td><td style="border-left:0px solid #CCC;"></td>';
		}
		
/*
		echo '<td align="center">'.$row["ip"].'</td>';
		echo '<td align="center" style="word-wrap: break-word;">'.$row["ctext"].'</td>';
		echo '<td align="center">'.DATE("d.m.Yг. H:i", $row["date_start"]).'<br>'.DATE("d.m.Yг. H:i", $row["date_end"]).'</td>';

		if($row["status"]=="bad")
			echo '<td align="center">-</td>';
		elseif($row["status"]=="good")
			echo '<td align="center">'.$row["pay"].' руб.</td>';
		elseif($row["status"]=="wait")
			echo '<td align="center">'.$row["pay"].' руб.</td>';
		else{
			echo '<td align="center">-</td>';
		}

		if($row["ocenka"] > 0) {
			echo '<td align="center">'.$row["ocenka"].'</td>';
		}else{
			echo '<td align="center">не голосовал</td>';
		}
*/

		echo '<td align="left"><div style="word-wrap: break-word; width:210px">';
			echo '<b>IP:</b> '.$row["ip"].'<br>';
			echo '<b>Дата начала:</b> '.DATE("d.m.Y H:i", $row["date_start"]).'<br>';
			echo '<b>Дата окончания:</b> '.DATE("d.m.Y H:i", $row["date_end"]).'<br>';
			if($row["ocenka"] > 0) {
				echo '<b>Оценка:</b> '.$row["ocenka"];
			}else{
				echo '<b>Оценка:</b> не голосовал';
			}
       		echo '</div></td>';

		echo '<td align="center"><div style="word-wrap: break-word; width:200px">'.$row["ctext"].'</div></td>';


		echo '</tr>';
	}
}else{
	echo '<tr><td align="center" colspan="9">Задание еще не выполняли!</td></tr>';
}
echo '</table><br>';

if($count>$perpage){
	echo '<div align="center"><b>Страницы:</b> '; universal_link_bar($page, $count, $pages_count, 8); echo '</div>';
}

?>