<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `status`='pay'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$rek_name = $row["username"];

	echo '<table align="center" border="0" width="100%" cellspacing="3" cellpadding="3" style="line-height : 1.5em; border-collapse: collapse; border: 1px solid #1E90FF;">';
		echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">Оплачиваемое задание</th></tr>';
		echo '<tr bgcolor="#ADD8E6">';
			echo '<td width="200" align="right" height="30px"><b>Название:</b></td>';
			echo '<td>&nbsp;'.str_replace("\r\n","<br>", $row["zdname"]).'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#AFEEEE">';
			echo '<td width="200" align="right" height="30px"><b>Тип задания:</b></td>';
			if($row["zdtype"]==1)
				echo '<td>Клики</td>';
			elseif($row["zdtype"]==2)
				echo '<td>Регистрация без активности</td>';
			elseif($row["zdtype"]==3)
				echo '<td>Регистрация с активностью</td>';
			elseif($row["zdtype"]==4)
				echo '<td>Постинг в форум</td>';
			elseif($row["zdtype"]==5)
				echo '<td>Постинг в блоги</td>';
			elseif($row["zdtype"]==6)
				echo '<td>Голосование</td>';
			elseif($row["zdtype"]==7)
				echo '<td>Загрузка файлов</td>';
			elseif($row["zdtype"]==9)
				echo '<td>Социальные сети</td>';
			elseif($row["zdtype"]==10)
				echo '<td>YouTube</td>';
			elseif($row["zdtype"]==11)
				echo '<td>Работа с капчей</td>';
			elseif($row["zdtype"]==8)
				echo '<td>Прочее</td>';
			else{
				echo '<td></td>';
			}
		echo '</tr>';

		$sql_u = mysql_query("SELECT `id`,`username`,`reiting` FROM `tb_users` WHERE `username`='".$row["username"]."'");
		if(mysql_num_rows($sql_u)>0) {
			$row_u = mysql_fetch_array($sql_u);
			$info_user = ''.$row_u["username"].' <img src="../../img/reiting.png" border="0" alt="" align="middle" title="Рейтинг" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> <a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&task_search=4&task_name='.$row_u["id"].'"><img src="../../img/view.png" border="0" alt="Посмотреть все задания этого рекламодателя" align="middle" title="Посмотреть все задания этого рекламодателя" alt="Посмотреть все задания этого рекламодателя" style="margin:0; padding:0;" /></a>&nbsp;&nbsp;[<a href="javascript: void(0);" onclick="add_bl(\''.$row_u["id"].'\');" style="color:#000; font-weight: bold;" title="поместить пользователя '.$row_u["username"].' в черный список (Black List)">BL</a>]';
		}else{
			$info_user = ''.$row["user_name"].' <span style="color:#FF0000;">Рекламодатель удален</span>';
		}

		echo '<tr bgcolor="#ADD8E6">';
			echo '<td width="200" align="right" height="30px"><b>Рекламодатель:</b></td>';
			echo '<td>'.$info_user.'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#AFEEEE">';
			echo '<td width="200" align="right"><b>Рейтинг:</b></td>';
			echo '<td>'.round($row["reiting"], 2).' (проголосовало '.$row["all_coments"].')&nbsp;&nbsp;<span style="color:green;">'.$row["goods"].'</span> - <span style="color:red;">'.$row["bads"].'</span> - <span style="color:black;">'.$row["wait"].'</span></td>';
		echo '</tr>';
		echo '<tr bgcolor="#ADD8E6">';
			echo '<td width="200" align="right" height="30px"><b>Повтор задания:</b></td>';
			if($row["zdre"]==3)
				echo '<td>&nbsp;Задание можно выполнять каждые 3 часа</td>';
			elseif($row["zdre"]==6)
				echo '<td>&nbsp;Задание можно выполнять каждые 6 часов</td>';
			elseif($row["zdre"]==12)
				echo '<td>&nbsp;Задание можно выполнять каждые 12 часов</td>';
			elseif($row["zdre"]==24)
				echo '<td>&nbsp;Задание можно выполнять каждые 24 часа (1 сутки)</td>';
			elseif($row["zdre"]==48)
				echo '<td>&nbsp;Задание можно выполнять каждые 48 часов (2-е суток)</td>';
			elseif($row["zdre"]==72)
				echo '<td>&nbsp;Задание можно выполнять каждые 72 часа (3-е суток)</td>';
			else{
				echo '<td>&nbsp;НЕТ</td>';
			}
		echo '</tr>';
		echo '<tr bgcolor="#AFEEEE">';
			echo '<td width="200" align="right" height="30px"><b>Проверка задания:</b></td>';
			if($row["zdcheck"]==1) {echo '<td>&nbsp;Проверка в ручном режиме рекламодателем в течение 5 дней</td>';}else{echo '<td>&nbsp;Указание контрольного слова</td>';}
		echo '</tr>';
		echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">Описание задания</th></tr>';
		echo '<tr bgcolor="#ADD8E6">';
			echo '<td colspan="2">'.$row["zdtext"].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#AFEEEE">';
			echo '<td colspan="2"><br>За выполнение этого задания оплата: <b>'.$row["zdprice"].' руб.</b></td>';
		echo '</tr>';

		echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">Комментарии других пользователей</th></tr>';
		echo '<tr bgcolor="#AFEEEE"><td colspan="2">Комментариев всего: <b>'.$row["all_coments"].'</b>'; if($row["all_coments"]>20) echo ', показаны последние 20'; echo '</td></tr>';
		echo '</table>';

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `ident`='$rid' AND `type`='task' AND (`coment`!='' OR `ocenka`>0) ORDER by `id` DESC LIMIT 20");
		if(mysql_num_rows($sql_p)>0) {

			function smile($mes) {
				for($i=0; $i<=37; $i++) {
					$mes = str_ireplace("<br><br>", "<br>", $mes);
					$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-".$i.".gif\" alt=\"\" align=\"middle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
				}
				return $mes;
			}

			echo '<table width="100%" border="0" style="border-collapse: collapse; border: 1px solid #1E90FF;">';
			while($row_p = mysql_fetch_assoc($sql_p)) {
				$sql_u = mysql_query("SELECT `id`,`username`,`reiting`,`avatar` FROM `tb_users` WHERE `username`='".$row_p["user_name"]."'");
				if(mysql_num_rows($sql_u)>0) {
					$row_u = mysql_fetch_array($sql_u);
					$info_user = '['.DATE("d.m.Yг. H:i", $row_p["date_com"]).'] <b>'.$row_u["username"].'</b> <img src="../../img/reiting.png" border="0" alt="" align="middle" title="Рейтинг" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span>, оценка <b>'.$row_p["ocenka"].'</b>';
					$avatar = '<img src="../avatar/'.$row_u["avatar"].'" border="0" alt="" align="middle" />';
				}else{
					$info_user = '['.DATE("d.m.Yг. H:i", $row_p["date_com"]).'] <b>'.$row["user_name"].'</b> <span style="color:#FF0000;">Пользователь удален</span>, оценка <b>'.$row_p["ocenka"].'</b>';
					$avatar = '<img src="../avatar/no.png" border="0" alt="" align="middle" />';
				}

				echo '<tr bgcolor="#AFEEEE"><td align="center" width="85" style="border: 1px solid #1E90FF;">'.$avatar.'</td><td valign="top" style="border: 1px solid #1E90FF; padding-left:10px;">'.$info_user.'<br><br>'.smile($row_p["coment"]).'</td></tr>';
			}
			echo '</table>';
		}

	exit();
}else{
	echo '<fieldset class="errorp">Ошибка! Такого задания нет, либо оно не активно!&nbsp;&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">&lt;&lt; Вернуться назад</a></fieldset>';
	
	exit();
}

?>