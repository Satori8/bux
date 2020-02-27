<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


echo '<b>Оплачиваемые задания:</b><br /><br />';

echo '
	<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=add_task"><img src="../../img/add.png" border="0" alt="" align="middle" title="Создать новое задание" /></a>
	<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=add_task" title="Создать новое задание">Создать новое задание</a><br><br>
';

$date_s = strtotime(DATE("d.m.Y"));
$date_v = strtotime(DATE("d.m.Y",(time()-24*60*60)));

echo '<table align="center" border="0" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #000;">';
	echo '<tr bgcolor="#7EC0EE">';
	echo '<th align="center" colspan="2" style="border: 1px solid #000;">Название</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="80">Статус</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="80">Последняя активность</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="90">Задания</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="80">Баланс</th>';
	echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {

	$sql_n = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
	$nacenka_task = mysql_result($sql_n,0,0);

	while ($row = mysql_fetch_array($sql)) {
		echo '<tr>';
		echo '<td align="center" width="45">'.$row["id"].'</td>';
		echo '<td align="left" style="padding:5px;">';
			echo '<table width="100%" border="0">';
			echo '<tr>';
				echo '<td align="left" width="100%">
					<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=task_stat&amp;rid='.$row["id"].'">'.$row["zdname"].'</a><br>
					Рейтинг:&nbsp;<b>'.round($row["reiting"],2).'</b>&nbsp;|&nbsp;Всего проголосовало:&nbsp;<b>'.$row["all_coments"].'</b><br><br>
					[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=edit_task&amp;rid='.$row["id"].'">редактировать</a>]&nbsp;
					[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=addmoney_task&amp;rid='.$row["id"].'">пополнить&nbsp;баланс</a>]&nbsp;';

					if($row["status"]=="wait")
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'if(!confirm("Вы точно хотите удалить задание?")) return false;\'>удалить</a>]';
					elseif($row["status"]=="pause" && $row["date_act"]>(time()-7*24*60*60))
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'alert("Чтобы удалить задание, его необходимо остановить и подождать 7 дней. Если на задание не будет жалоб, то задание можно будет удалить и баланс задания будет переведен на баланс аккаунта."); return false;\'>удалить</a>]';
					elseif($row["status"]=="pay" | $row["date_act"]>(time()-7*24*60*60))
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'alert("Чтобы удалить задание, его необходимо остановить и подождать 7 дней. Если на задание не будет жалоб, то задание можно будет удалить и баланс задания будет переведен на баланс аккаунта."); return false;\'>удалить</a>]';
					else{
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'if(!confirm("Вы точно хотите удалить задание?")) return false;\'>удалить</a>]';
					}
				echo '</td>';

				if($row["zdre"]==3)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="Задание можно выполнять каждые 3 часа" /></td>';
				elseif($row["zdre"]==6)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="Задание можно выполнять каждые 6 часов" /></td>';
				elseif($row["zdre"]==12)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="Задание можно выполнять каждые 12 часов" /></td>';
				elseif($row["zdre"]==24)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="Задание можно выполнять каждые 24 часа (1 сутки)" /></td>';
				elseif($row["zdre"]==48)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="Задание можно выполнять каждые 48 часов (2-е суток)" /></td>';
				elseif($row["zdre"]==72)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="Задание можно выполнять каждые 72 часа (3-е суток)" /></td>';
				else{
					echo '';
				}

				echo '<td>';
					if( $row["date_up"] < (time() - 1*60*60) ) {
						echo '<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=up_task&amp;rid='.$row["id"].'"><img src="../../img/up_task.png" border="0" alt="Задание было поднято '.DATE("d.m.Yг. в H:i:s", $row["date_up"]).'" align="middle" title="Задание было поднято '.DATE("d.m.Yг. в H:i:s", $row["date_up"]).'" /></a>';
					}else{
						echo '<img src="../../img/up_task.png" border="0" alt="Задание было поднято '.DATE("d.m.Yг. в H:i:s", $row["date_up"]).'" align="middle" title="Задание было поднято '.DATE("d.m.Yг. в H:i:s", $row["date_up"]).'" />';
					}
				echo '</td>';

			echo '</tr>';
			echo '</table>';
		echo '</td>';

		if($row["status"]=="end")
			echo '<td align="center"><span style="color: #FF0000;">Не активно</span><br>[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=active_task&amp;rid='.$row["id"].'">Запустить</a>]</td>';
		elseif($row["status"]=="pause" | $row["status"]=="wait")
			echo '<td align="center"><span style="color: #FF0000;">Не активно</span><br>[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=active_task&amp;rid='.$row["id"].'">Запустить</a>]</td>';
		elseif($row["status"]=="pay")
			echo '<td align="center"><span style="color: #006400;">Активно</span><br>[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=pause_task&amp;rid='.$row["id"].'">Пауза</a>]</td>';
		else {echo '<td></td>';}

		echo '<td align="center">'.DATE("d.m.Y",$row["date_act"]).'<br>'.DATE("H:i",$row["date_act"]).'</td>';
		echo '<td align="center"><a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=task_get&amp;rid='.$row["id"].'">Выполнено:&nbsp;'.$row["goods"].'</a><br>Осталось:&nbsp;'.$row["totals"].'<br><a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=task_mod&amp;rid='.$row["id"].'">Заявок:&nbsp;'.$row["wait"].'</td>';
		echo '<td align="center">'.number_format(( ($row["totals"] * $row["zdprice"]) * (100 + $nacenka_task) / 100 ),2,".","'").' руб.</td>';
		echo '</tr>';
	}
}else{
	echo '<tr><td align="center" colspan="6">Созданных заданий нет!</td></tr>';
}
echo '</table>';

echo '<br><br><b style="color:#FF0000;">*</b> - Поднятие задания возможно 1 раз в час. Стоимость одного поднятия 0.25р.<br>';

?>