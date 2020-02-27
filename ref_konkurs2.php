<?php

if(!isset($_GET["page"])) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
}elseif(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Ошибка! Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	function get_avatar($USER_NAME, $AVATAR=false) {
		$sql_us = mysql_query("SELECT `id`,`avatar` FROM `tb_users` WHERE `username`='$USER_NAME'");
		if(mysql_num_rows($sql_us)>0) {
			$row_us = mysql_fetch_row($sql_us);
			if($AVATAR!=false) {
				return '<a href="/wall.php?uid='.$row_us["0"].'" target="_blank" title="Перейти на стену пользователя '.$USER_NAME.'"><img class="avatar" src="/avatar/'.$row_us["1"].'" style="width:30px; height:30px" border="0" alt="" /></a>';
			}else{
				return '<a href="/wall.php?uid='.$row_us["0"].'" target="_blank" title="Перейти на стену пользователя '.$USER_NAME.'">'.$USER_NAME.'</a>';
			}
			//return $row_us["1"];
		}else{
			if($AVATAR!=false) {
				return '<img class="avatar" src="/avatar/no.png" style="width:30px; height:30px" border="0" alt="" />';
			}else{
				return ''.$USER_NAME.' - <span style="color:#FF0000;">пользователь удален</span>';
			}
			//return "no.png";
		}
	}

	require_once ("bbcode/bbcode.lib.php");

	if(isset($_GET["op"]) && limpiar($_GET["op"])=="statkonkurs") {
		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `status`>'0' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			switch(substr($row["time_kon"], -1, 1)){
				case 1: $d=$row["time_kon"]." день"; break;
				case 2: case 3: case 4: $d=$row["time_kon"]." дня"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." дней"; break;
			}

			$p = 0;
			for($i=1; $i<=$row["count_kon"]; $i++) {
				$p = $p + $row["p$i"];
			}

			$desc_kon = new bbcode($row["desc"]);
			$desc_kon = $desc_kon->get_html();

			echo '<h3 class="sp">Конкурс #'.$row["id"].' от реферера '.$row["username"].'</h3>';
			echo '<table class="tables">';
			echo '<thead><tr><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Тип конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo 'Выполнение заданий';
					}elseif($row["type_kon"]==2) {
						echo 'Выполнение оплачиваемых кликов';
					}elseif($row["type_kon"]==3) {
						echo 'Привлечение рефералов';
					}elseif($row["type_kon"]==4) {
						echo 'Комплексный (по баллам)';
					}elseif($row["type_kon"]==5) {
						echo 'Выполнение оплачеваемых просмотров';
					}elseif($row["type_kon"]==6) {
						echo 'Сумма заработанная для реферера';
					}else{
						echo '<b style="color: #FF0000;">Ошибка! Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Описание конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo 'Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса';
					}elseif($row["type_kon"]==2) {
						echo 'Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса';
					}elseif($row["type_kon"]==3) {
						echo 'Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса';
					}elseif($row["type_kon"]==4) {
						echo 'Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса.<br><br>Баллы рассчитываются по схеме:<br>';
						echo '-&nbsp;просмотр ссылки в серфинге - 1 балл;<br>';
						echo '-&nbsp;чтение оплачиваемого письма - 2 балла;<br>';
						echo '-&nbsp;прохождение теста - 3 балла;<br>';
						echo '-&nbsp;выполнение многоразового задания - 3 балла;<br>';
						echo '-&nbsp;выполнение одноразового задания - 5 баллов;<br>';
						echo '-&nbsp;приглашение реферала - 7 баллов.';
					}elseif($row["type_kon"]==5) {
						echo 'Призовые места займут те, кто больше посмотрит видеороликов, за время проведения конкурса';
					}elseif($row["type_kon"]==6) {
						echo 'Призовые места займут те, кто выполняя серфинг, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Количество призовых мест</b></td>';
				echo '<td colspan="2">'.$row["count_kon"].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Призы:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'руб.</b>, ';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Призовой фонд:</b></td>';
				echo '<td colspan="2">'.$p.' руб.</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td width="30%" align="right"><b>Для участия требуется выполнить заданий не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td width="30%" align="right"><b>Для участия требуется выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td width="30%" align="right"><b>Для участия требуется привлечь не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td width="30%" align="right"><b>Для участия требуется набрать баллов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td width="30%" align="right"><b>Для участия требуется посмотреть видеороликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия требуется заработать не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Глубина конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo 'для рефералов 1-го уровня';
					}else{
						echo 'для рефералов всех уровей<br>';
						echo 'в конкурсе рефереров участвуют рефералы до 3-го уровеня';
					}
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>Время проведения конкурса:</b></td>';
				echo '<td colspan="2">c '.DATE("d.m.Yг. H:i:s", $row["date_s"]).' по  '.DATE("d.m.Yг. H:i:s", $row["date_e"]).' ('.@$d.')</td>';
			echo '</tr>';

			echo '<tr>';
				if(time() < $row["date_s"]) {
					echo '<td width="30%" align="right" height="30px"><b>До начала конкурса:</b></td>';
				}else{
					echo '<td width="30%" align="right" height="30px"><b>До окончания конкурса:</b></td>';
				}

				if(time() > $row["date_e"]) {
					echo '<td colspan="2">конкурс завершен</td>';
				}elseif(time() < $row["date_s"]) {
					echo '<td colspan="2">'.date_ost($row["date_s"]-time()).'</td>';
				}else{
					echo '<td colspan="2">'.date_ost($row["date_e"]-time()).'</td>';
				}
			echo '</tr>';

			echo '<thead><tr><th align="center" colspan="3">Описание конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';
			echo '</table><br>';

			$sql_s = mysql_query("SELECT `username`,`referer`, SUM(`kolvo`) as `sum_kolvo` FROM `tb_refkonkurs_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_kon"]."' GROUP BY `username` ORDER BY SUM(`kolvo`) DESC LIMIT 100");
			$kol = mysql_num_rows($sql_s);
			if($kol > 0) {
				echo '<table class="tables">';
				echo '<thead><tr align="center"><th align="center" colspan="4">TOP 100 пользователей по конкурсу</th></tr></thead>';
				echo '<tr>';
					echo '<td colspan="4">Пользователей, участвующих в конкурсе: '.$kol.'</td>';
				echo '</tr>';

				echo '<thead><tr align="center">';
					echo '<th class="top">Место</th>';
					echo '<th class="top" colspan="2">Логин</th>';
					echo '<th class="top">';
						if($row["type_kon"]==1) {
							echo 'Выполнено заданий';
						}elseif($row["type_kon"]==2) {
							echo 'Сделано кликов';
						}elseif($row["type_kon"]==3) {
							echo 'Привлечено рефералов';
						}elseif($row["type_kon"]==4) {
							echo 'Набрано баллов';
						}elseif($row["type_kon"]==5) {
							echo 'Просмотренно видеороликов';
						}elseif($row["type_kon"]==6) {
							echo 'Заработал';
						}
					echo '</th>';
				echo '</tr></thead>';

				$i=0;
				while($row_s = mysql_fetch_array($sql_s)) {
					$i++; 
					if($row["type_kon"]==6) {
					    if($i<=$row["count_kon"] && $row_s["sum_kolvo"]>=$row["limit_kon_sum"]) {
					    $style1 = 'style="background-color:#fff3a4;"';
						$style2 = 'style="background-color:#fff3a4; border-right:none;"';
						$style3 = 'style="background-color:#fff3a4; border-left:none;"';
					
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}
					
					}elseif($i<=$row["count_kon"] && $row_s["sum_kolvo"]>=$row["limit_kon"]) {
						$style1 = 'style="background-color:#fff3a4;"';
						$style2 = 'style="background-color:#fff3a4; border-right:none;"';
						$style3 = 'style="background-color:#fff3a4; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>$i</b>" : "$i").'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo get_avatar($row_s["username"], "avatar");
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>';
							echo (strtolower($row_s["username"])==strtolower($username) ? "<b>".get_avatar($row_s["username"])."</b>" : get_avatar($row_s["username"]));
							if($row["glubina"]==1) echo '&nbsp;&nbsp;<div style="float:right; font-size:11px; color:#9C9C9C;">реферал '.str_replace("0", "1", $row_s["referer"]).' ур.</div>';
						echo '</td>';
        				if($row["type_kon"]==6) {
						echo '<td align="center" width="45%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>".$row_s["sum_kolvo"]."</b>" : $row_s["sum_kolvo"]).'</td>';
						//}elseif($row["type_kon"]==3) {
						//echo '<td align="center" width="45%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>".$row_s["sum_kolvo"]."</b>" : $row_s["sum_kolvo"]).'</td>';
						}else{
        					echo '<td align="center" width="45%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>".number_format($row_s["sum_kolvo"],0,'.','`')."</b>" : number_format($row_s["sum_kolvo"],0,'.','`')).'</td>';
					echo '</tr>';
						}
				}

				echo '</table>';
			}
		}else{
			echo '<span class="msg-error">Ошибка!<br>Конкурс не найден.</span>';
		}
	}else{
		echo '<br>';
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:normal;">Конкурс от реферера 1-ого уровня <b>'.$my_referer_1.'</b></h5>';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center">№</th>';
			echo '<th align="center">Тип конкурса</th>';
			echo '<th align="center">Начало</th>';
			echo '<th align="center">Окончание</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`>'0' AND `username`='$my_referer_1' ORDER BY `id` DESC");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {
				echo '<tr>';

					switch($row["count_kon"]){
						case 1: $m="место"; break;
						case 2: case 3: case 4: $m="места"; break;
						case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19:  case 20:$m="мест"; break;
					}

					$p = 0;
					for($i=1; $i<=$row["count_kon"]; $i++) {
						$p = $p + $row["p$i"];
					}

					echo '<td align="center">'.$row["id"].'</td>';

					if($row["type_kon"]=="1") {
						$type_kon="<b>Выполнение заданий</b>";
					}elseif($row["type_kon"]=="2") {
						$type_kon="<b>Выполнение оплачиваемых кликов</b>";
					}elseif($row["type_kon"]=="3") {
						$type_kon="<b>Привлечение рефералов</b>";
					}elseif($row["type_kon"]=="4") {
						$type_kon="<b>Комплексный (по баллам)</b>";
					}elseif($row["type_kon"]=="5") {
						$type_kon="<b>Выполнение оплачеваемых просмотров </b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>Сумма заработанная для реферера </b>";
					}else{
						$type_kon='<b style="color:#FF0000;">Тип конкурса не определен!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">Активен, идет конкурс</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">статистика</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">Конкурс завершен, итоги подведены, призы зачислены</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">статистика</a>]';
					}else{
						$status="";
						$statistik = "";
					}

					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', призовой фонд <b>'.$p.' руб.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

					if($row["date_s"]==0) {
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
					}else{
						echo '<td align="center">'.DATE("d.m.Y", $row["date_s"]).'<br>'.DATE("H:i", $row["date_s"]).'</td>';
						echo '<td align="center">'.DATE("d.m.Y", $row["date_e"]).'<br>'.DATE("H:i:s", $row["date_e"]).'</td>';
					}
				echo '</tr>';
			}
		}else{
			echo '<tr>';
				echo '<td colspan="4" align="center"><b>Конкурсы не найдены!</b></td>';
			echo '</tr>';
		}
		echo '</table>';

		echo '<br>';
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:normal;">Конкурс от реферера 2-ого уровня <b>'.$my_referer_2.'</b></h5>';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center">№</th>';
			echo '<th align="center">Тип конкурса</th>';
			echo '<th align="center">Начало</th>';
			echo '<th align="center">Окончание</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`>'0' AND `username`='$my_referer_2' ORDER BY `id` DESC");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {
				echo '<tr>';

					switch($row["count_kon"]){
						case 1: $m="место"; break;
						case 2: case 3: case 4: $m="места"; break;
						case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19:  case 20:$m="мест"; break;
					}

					$p = 0;
					for($i=1; $i<=$row["count_kon"]; $i++) {
						$p = $p + $row["p$i"];
					}

					echo '<td align="center">'.$row["id"].'</td>';

					if($row["type_kon"]=="1") {
						$type_kon="<b>Выполнение заданий</b>";
					}elseif($row["type_kon"]=="2") {
						$type_kon="<b>Выполнение оплачиваемых кликов</b>";
					}elseif($row["type_kon"]=="3") {
						$type_kon="<b>Привлечение рефералов</b>";
					}elseif($row["type_kon"]=="4") {
						$type_kon="<b>Комплексный (по баллам)</b>";
					}elseif($row["type_kon"]=="5") {
						$type_kon="<b>Выполнения оплачеваемых просмотров</b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>Сумма заработанная для реферера </b>";
					}else{
						$type_kon='<b style="color:#FF0000;">Тип конкурса не определен!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">Активен, идет конкурс</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">статистика</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">Конкурс завершен, итоги подведены, призы зачислены</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">статистика</a>]';
					}else{
						$status="";
						$statistik = "";
					}

					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', призовой фонд <b>'.$p.' руб.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

					if($row["date_s"]==0) {
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
					}else{
						echo '<td align="center">'.DATE("d.m.Y", $row["date_s"]).'<br>'.DATE("H:i", $row["date_s"]).'</td>';
						echo '<td align="center">'.DATE("d.m.Y", $row["date_e"]).'<br>'.DATE("H:i:s", $row["date_e"]).'</td>';
					}
				echo '</tr>';
			}
		}else{
			echo '<tr>';
				echo '<td colspan="4" align="center"><b>Конкурсы не найдены!</b></td>';
			echo '</tr>';
		}
		echo '</table>';

		echo '<br>';
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:normal;">Конкурс от реферера 3-ого уровня <b>'.$my_referer_3.'</b></h5>';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center">№</th>';
			echo '<th align="center">Тип конкурса</th>';
			echo '<th align="center">Начало</th>';
			echo '<th align="center">Окончание</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`>'0' AND `username`='$my_referer_3' ORDER BY `id` DESC");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {
				echo '<tr>';

					switch($row["count_kon"]){
						case 1: $m="место"; break;
						case 2: case 3: case 4: $m="места"; break;
						case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19:  case 20:$m="мест"; break;
					}

					$p = 0;
					for($i=1; $i<=$row["count_kon"]; $i++) {
						$p = $p + $row["p$i"];
					}

					echo '<td align="center">'.$row["id"].'</td>';

					if($row["type_kon"]=="1") {
						$type_kon="<b>Выполнение заданий</b>";
					}elseif($row["type_kon"]=="2") {
						$type_kon="<b>Выполнение оплачиваемых кликов</b>";
					}elseif($row["type_kon"]=="3") {
						$type_kon="<b>Привлечение рефералов</b>";
					}elseif($row["type_kon"]=="4") {
						$type_kon="<b>Комплексный (по баллам)</b>";
					}elseif($row["type_kon"]=="5") {
						$type_kon="<b>Выполнение оплачеваемых просмотров</b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>Сумма заработанная для реферера </b>";
					}else{
						$type_kon='<b style="color:#FF0000;">Тип конкурса не определен!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">Активен, идет конкурс</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">статистика</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">Конкурс завершен, итоги подведены, призы зачислены</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">статистика</a>]';
					}else{
						$status="";
						$statistik = "";
					}

					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', призовой фонд <b>'.$p.' руб.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

					if($row["date_s"]==0) {
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
					}else{
						echo '<td align="center">'.DATE("d.m.Y", $row["date_s"]).'<br>'.DATE("H:i", $row["date_s"]).'</td>';
						echo '<td align="center">'.DATE("d.m.Y", $row["date_e"]).'<br>'.DATE("H:i:s", $row["date_e"]).'</td>';
					}
				echo '</tr>';
			}
		}else{
			echo '<tr>';
				echo '<td colspan="4" align="center"><b>Конкурсы не найдены!</b></td>';
			echo '</tr>';
		}
		echo '</table>';

	}
}
?>