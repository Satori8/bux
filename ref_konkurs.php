<?php

if(!isset($_GET["page"])) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
}elseif(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
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

	require_once ("config.php");
	require_once ("bbcode/bbcode.lib.php");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_reiting_konk' AND `howmany`='1'");
	$tests_reiting_konk = number_format(mysql_result($sql,0,0), 0, ".", "");


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

			echo '<h3 class="sp">Конкурс #'.$row["id"].' от реферера</h3>';
			echo '<table class="tables">';
			echo '<thead><tr><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo 'Выполнение заданий';
					}elseif($row["type_kon"]==2) {
						echo 'Выполнение оплачиваемых кликов';
					}elseif($row["type_kon"]==3) {
						echo 'Привлечение рефералов';
					}elseif($row["type_kon"]==4) {
						echo 'Комплексный по баллам';
					}elseif($row["type_kon"]==5) {
						echo 'Выполнение оплачиваемых просмотров видеороликов';
					}elseif($row["type_kon"]==6) {
						echo 'Сумма заработанная для реферера';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Описание конкурса:</b></td>';
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
						echo '-&nbsp;просмотр видеороликов в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 балл;<br>';
						echo '-&nbsp;чтение оплачиваемого письма - 2 балла;<br>';
						echo '-&nbsp;прохождение теста - '.$tests_reiting_konk.' балла;<br>';						
						echo '-&nbsp;выполнение многоразового задания - 3 балла;<br>';
						echo '-&nbsp;выполнение одноразового задания - 5 баллов;<br>';
						echo '-&nbsp;приглашение реферала - 7 баллов.';
					}elseif($row["type_kon"]==5) {
						echo 'Призовые места займут те, кто больше просмотрит оплачиваемых видеороликов, за время проведения конкурса';
					}elseif($row["type_kon"]==6) {
						echo 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге,письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Количество призовых мест</b></td>';
				echo '<td colspan="2">'.$row["count_kon"].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Призы:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'руб.</b>, ';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Призовой фонд:</b></td>';
				echo '<td colspan="2">'.$p.' руб.</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td align="right"><b>Для участия требуется выполнить заданий не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td align="right"><b>Для участия требуется выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td align="right"><b>Для участия требуется привлечь не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td align="right"><b>Для участия требуется набрать баллов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td align="right"><b>Для участия требуется посмотреть видеороликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==3) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия реферал должен выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit2_kon"].'</b></td>';
				echo '</tr>';
			}
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия требуется заработать не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>Глубина конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo 'для рефералов 1-го уровня';
					}else{
						echo 'для рефералов всех уровей<br>';
						echo 'в конкурсе рефереров участвуют все рефералы до 3-го уровеня';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Время проведения конкурса:</b></td>';
				echo '<td colspan="2">c '.DATE("d.m.Yг. H:i:s", $row["date_s"]).' по  '.DATE("d.m.Yг. H:i:s", $row["date_e"]).' ('.@$d.')</td>';
			echo '</tr>';
			echo '<tr>';
				if(time() < $row["date_s"]) {
					echo '<td align="right"><b>До начала конкурса:</b></td>';
				}else{
					echo '<td align="right"><b>До окончания конкурса:</b></td>';
				}

				if(time() > $row["date_e"]) {
					echo '<td colspan="2">конкурс завершен</td>';
				}elseif(time() < $row["date_s"]) {
					echo '<td colspan="2">'.date_ost($row["date_s"]-time()).'</td>';
				}else{
					echo '<td colspan="2">'.date_ost($row["date_e"]-time()).'</td>';
				}
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">Описание конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';
			echo '</table><br>';

			$sql_s = mysql_query("SELECT `username`, `referer`, SUM(`kolvo`) as `sum_kolvo` FROM `tb_refkonkurs_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_kon"]."' GROUP BY `username` ORDER BY SUM(`kolvo`) DESC LIMIT 100");
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
					/*if($row["type_kon"]==3) {
					echo '<th class="top">Привлечено рефералов</th>';
					}*/
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
					
					/*}elseif($row["type_kon"]==3) {
						if($i<=$row["count_kon"] && $row_s["sum_kolvo"]>=$row["limit2_kon"] && $row_s["sum_kolvo"]>=$row["limit_kon"]) {
						$style1 = 'style="background-color:#fff3a4;"';
						$style2 = 'style="background-color:#fff3a4; border-right:none;"';
						$style3 = 'style="background-color:#fff3a4; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}*/
					
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
							//echo '<img class="avatar" src="/avatar/'.get_avatar($row_s["username"]).'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
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
			echo '<span class="msg-error">Конкурс не найден.</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="editkonkurs3") {
		echo '<h3 class="sp">Редактирование конкурса для рефералов</h3>';

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			$type_kon = $row["type_kon"];
			$count_kon = $row["count_kon"];
			$time_kon = $row["time_kon"];
			$desc_kon = $row["desc"];

			switch(substr($row["time_kon"], -1, 1)){
				case 1: $d=$row["time_kon"]." день"; break;
				case 2: case 3: case 4: $d=$row["time_kon"]." дня"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." дней"; break;
			}

			?><script type="text/javascript" src="bbcode/xbb.js.php"></script>
			<script type="text/javascript">
				XBB.textarea_id = 'desc_kon';
				XBB.area_width = '98%';
				XBB.area_height = '200px';
				XBB.state = 'plain';
				XBB.lang = 'ru_cp1251';
				onload = function() {
				XBB.init();
			}
			</script><?php

			if(count($_POST)>0) {
				$desc_kon = isset($_POST["desc_kon"]) ? limitatexto(trim($_POST["desc_kon"]),1000) : false;
				if (get_magic_quotes_gpc()) { $desc_kon = stripslashes($desc_kon); }

				mysql_query("UPDATE `tb_refkonkurs` SET `desc`='$desc_kon',`ip`='$ip' WHERE `id`='$id' AND `username`='$username' AND `type_kon`='".$row["type_kon"]."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';
			}


			echo '<form action="" method="POST" id="newform">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo 'Выполнение заданий';
					}elseif($type_kon==2) {
						echo 'Выполнение оплачиваемых кликов';
					}elseif($type_kon==3) {
						echo 'Привлечение рефералов';
					}elseif($type_kon==4) {
						echo 'Комплексный (по баллам)';
					}elseif($type_kon==5) {
						echo 'Выполнение оплачевыемых просмотров видеороликов';
					}elseif($type_kon==6) {
						echo 'Сумма заработанная для реферера';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Описание конкурса:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo 'Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса';
					}elseif($type_kon==2) {
						echo 'Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса';
					}elseif($type_kon==3) {
						echo 'Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса';
					}elseif($type_kon==4) {
						echo 'Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса.<br><br>Баллы рассчитываются по схеме:<br>';
						echo '-&nbsp;просмотр ссылки в серфинге - 1 балл;<br>';
						echo '-&nbsp;просмотр видеороликов в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 балл;<br>';
						echo '-&nbsp;чтение оплачиваемого письма - 2 балла;<br>';
						echo '-&nbsp;выполнение многоразового задания - 3 балла;<br>';
						echo '-&nbsp;выполнение одноразового задания - 5 баллов;<br>';
						echo '-&nbsp;прохождение теста - '.$tests_reiting_konk.' балла;<br>';
						echo '-&nbsp;приглашение реферала - 7 баллов.';
					}elseif($type_kon==5) {
						echo 'Призовые места займут те, кто больше просмотрит оплачеваемых видеороликов, за время проведения конкурса';
					}elseif($type_kon==6) {
						echo 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Количество призовых мест</b></td>';
				echo '<td width="140px">'.$count_kon.'</td>';
				echo '<td align="left">Количество возможных победителей в конкурсе</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Время конкурса</b></td>';
				echo '<td>'.@$d.'</td>';
				echo '<td align="left">Срок проведения конкурса в днях</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td align="right"><b>Для участия требуется выполнить заданий не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td align="right"><b>Для участия требуется выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td align="right"><b>Для участия требуется привлечь не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td align="right"><b>Для участия требуется набрать баллов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td align="right"><b>Для участия требуется просметреть видеороликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==3) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия реферал должен выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit2_kon"].'</b></td>';
				echo '</tr>';
			}
			
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия требуется заработать не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>Глубина конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo 'для рефералов 1-го уровня';
					}else{
						echo 'для рефералов всех уровей<br>';
						echo 'в конкурсе рефереров участвуют все рефералы до 3-го уровеня';
					}
				echo '</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">Призовые места</th></tr></thead>';
			for($i=1; $i<=$count_kon; $i++){
				echo '<tr>';
					echo '<td align="right"><b>Приз за '.$i.' место:</b></td>';
					echo '<td>'.$row["p$i"].' руб.</td>';
					echo '<td align="left">Сумма, которую получит пользователь, если займет '.$i.' место</td>';
				echo '</tr>';
			}

			echo '<thead><tr align="center"><th align="center" colspan="3">Описание конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td align="right"><b>Текст сообщения:</b></td>';
				echo '<td colspan="2"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3"><textarea style="width:99%;height:200px;" name="desc_kon" id="desc_kon">'.$desc_kon.'</textarea></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3">Тут Вы можете указать любую информацию по конкурсу, которую увидят Ваши рефералы, если откроют описание конкурса. Даты проведения конкурса и призовые места указываются автоматически.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';
		}else{
			echo '<span class="msg-error">Конкурс не найден или не принадлежит Вам</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="editkonkurs2") {
		echo '<h3 class="sp">Редактирование конкурса для рефералов</h3>';

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			if($row["status"]==1) {
				echo '<span class="msg-error">Нельзя вносить изменения, если конкурс активен.</span>';
			}else{
				$type_kon = $row["type_kon"];
				$count_kon = $row["count_kon"];
				$time_kon = $row["time_kon"];
				$desc_kon = $row["desc"];

				switch(substr($row["time_kon"], -1, 1)){
					case 1: $d=$row["time_kon"]." день"; break;
					case 2: case 3: case 4: $d=$row["time_kon"]." дня"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." дней"; break;
				}

				?><script type="text/javascript" src="bbcode/xbb.js.php"></script>
				<script type="text/javascript">
					XBB.textarea_id = 'desc_kon';
					XBB.area_width = '98%';
					XBB.area_height = '200px';
					XBB.state = 'plain';
					XBB.lang = 'ru_cp1251';
					onload = function() {
					XBB.init();
				}
				</script><?php

				if(count($_POST)>0) {
					for($i=1; $i<=20; $i++){$p[$i]=0;}
					$s = "OK";

					for($i=1; $i<=$count_kon; $i++){
						$p[$i] = (isset($_POST["p$i"])) ? round(abs(floatval(str_replace(",",".",trim($_POST["p$i"])))),2) : 0;
					}

					for($i=1; $i<=$count_kon; $i++){
						if(isset($_POST["p$i"])) {
							if($p[$i]==0){
								echo '<span class="msg-error">Приз за '.$i.' место нулевой.</span>'; $s="NO";
								break;
							}
							if($p[$i] < $p[$i+1]) {
								echo '<span class="msg-error">Приз за '.($i+1).' место больше, чем за '.$i.'.</span>'; $s="NO";
								break;
							}
						}
					}

					if($s=="OK") {
						$limit_kon = ( isset($_POST["limit_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_kon"])) ) ? intval(limpiar(trim($_POST["limit_kon"]))) : "0";
						$limit2_kon = ( isset($_POST["limit2_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit2_kon"])) ) ? intval(limpiar(trim($_POST["limit2_kon"]))) : "0";
						$limit_kon_sum = ( isset($_POST["$limit_kon_sum"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["$limit_kon_sum"])) ) ? intval(limpiar(trim($_POST["$limit_kon_sum"]))) : "0";
						$desc_kon = isset($_POST["desc_kon"]) ? limitatexto(trim($_POST["desc_kon"]),1000) : false;
						if (get_magic_quotes_gpc()) { $desc_kon = stripslashes($desc_kon); }

						mysql_query("UPDATE `tb_refkonkurs` SET `limit_kon`='$limit_kon',`limit2_kon`='$limit2_kon',`limit_kon_sum`='$limit_kon_sum',`desc`='$desc_kon',`p1`='$p[1]',`p2`='$p[2]',`p3`='$p[3]',`p4`='$p[4]',`p5`='$p[5]',`p6`='$p[6]',`p7`='$p[7]',`p8`='$p[8]',`p9`='$p[9]',`p10`='$p[10]',`p11`='$p[11]',`p12`='$p[12]',`p13`='$p[13]',`p14`='$p[14]',`p15`='$p[15]',`p16`='$p[16]',`p17`='$p[17]',`p18`='$p[18]',`p19`='$p[19]',`p20`='$p[20]',`ip`='$ip' WHERE `id`='$id' AND `username`='$username' AND `type_kon`='".$row["type_kon"]."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

						echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
						echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';
					}
				}


				echo '<form action="" method="POST" id="newform">';
				echo '<table class="tables">';
				echo '<thead><tr align="center"><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
				echo '<tr>';
					echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
					echo '<td colspan="2">';
						if($type_kon==1) {
							echo 'Выполнение заданий';
						}elseif($type_kon==2) {
							echo 'Выполнение оплачиваемых кликов';
						}elseif($type_kon==3) {
							echo 'Привлечение рефералов';
						}elseif($type_kon==4) {
							echo 'Комплексный (по баллам)';
						}elseif($type_kon==5) {
							echo 'Выполнение оплачиваемых просмотров видеороликов';
						}elseif($type_kon==6) {
							echo 'Сумма заработанная для реферера';
						}else{
							echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>Описание конкурса:</b></td>';
					echo '<td colspan="2">';
						if($type_kon==1) {
							echo 'Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса';
						}elseif($type_kon==2) {
							echo 'Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса';
						}elseif($type_kon==3) {
							echo 'Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса';
						}elseif($type_kon==4) {
							echo 'Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса.<br><br>Баллы рассчитываются по схеме:<br>';
							echo '-&nbsp;просмотр ссылки в серфинге - 1 балл;<br>';
							echo '-&nbsp;просмотр видеороликов в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 балл;<br>';
							echo '-&nbsp;чтение оплачиваемого письма - 2 балла;<br>';
							echo '-&nbsp;выполнение многоразового задания - 3 балла;<br>';
							echo '-&nbsp;выполнение одноразового задания - 5 баллов;<br>';
							echo '-&nbsp;прохождение теста - '.$tests_reiting_konk.' балла;<br>';
							echo '-&nbsp;приглашение реферала - 7 баллов.';
						}elseif($type_kon==5) {
							echo 'Призовые места займут те, кто больше просмотрит оплачеваемых видеороликов, за время проведения конкурса';
						}elseif($type_kon==6) {
							echo 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.';
						}else{
							echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>Количество призовых мест</b></td>';
					echo '<td width="115px">'.$count_kon.'</td>';
					echo '<td align="left">Количество возможных победителей в конкурсе</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>Время конкурса</b></td>';
					echo '<td>'.@$d.'</td>';
					echo '<td align="left">Срок проведения конкурса в днях</td>';
				echo '</tr>';

				echo '<tr>';
					if($row["type_kon"]==1) {
						echo '<td align="right"><b>Для участия требуется выполнить заданий не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==2) {
						echo '<td align="right"><b>Для участия требуется выполнить кликов не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==3) {
						echo '<td align="right"><b>Для участия требуется привлечь не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==4) {
						echo '<td align="right"><b>Для участия требуется набрать баллов не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==5) {
						echo '<td align="right"><b>Для участия требуется посмотреть видеороликов не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}
					
				echo '</tr>';
				
				if($row["type_kon"]==3) {
					echo '<tr>';
						echo '<td align="right"><b>Для участия реферал должен выполнить кликов не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit2_kon" value="'.$row["limit2_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					echo '</tr>';
				}
				
				if($row["type_kon"]==6) {
					echo '<tr>';
						echo '<td align="right"><b>Для участия требуется заработать не менее:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit2_kon" value="'.$row["limit_kon_sum"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td align="right"><b>Глубина конкурса:</b></td>';
					echo '<td colspan="2">';
						echo '<input type="radio" name="glubina" value="0" checked="checked"> - для рефералов 1-го уровня<br>';
						echo '<input type="radio" name="glubina" value="1"> - для рефералов всех уровей<br>';
						echo 'в конкурсе рефереров участвуют все рефералы до 3-го уровеня';
					echo '</td>';
				echo '</tr>';

				echo '<thead><tr align="center"><th align="center" colspan="3">Призовые места</th></tr></thead>';
				for($i=1; $i<=$count_kon; $i++){
					echo '<tr>';
						echo '<td align="right"><b>Приз за '.$i.' место:</b></td>';
						echo '<td><input type="text" name="p'.$i.'" value="'.$row["p$i"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"> руб.</td>';
						echo '<td align="left">Сумма, которую получит пользователь, если займет '.$i.' место</td>';
					echo '</tr>';
				}

				echo '<thead><tr align="center"><th align="center" colspan="3">Описание конкурса</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right"><b>Текст сообщения:</b></td>';
					echo '<td colspan="2"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3"><textarea style="width:99%;height:200px;" name="desc_kon" id="desc_kon">'.$desc_kon.'</textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3">Тут Вы можете указать любую информацию по конкурсу, которую увидят Ваши рефералы, если откроют описание конкурса. Даты проведения конкурса и призовые места указываются автоматически.</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
				echo '</tr>';
				echo '</table>';
				echo '</form>';
			}
		}else{
			echo '<span class="msg-error">Конкурс не найден или не принадлежит Вам</span>';
		}

		include('footer.php');
		exit();

	}if(isset($_GET["op"]) && limpiar($_GET["op"])=="editkonkurs") {
		echo '<h3 class="sp">Редактирование конкурса для рефералов</h3>';

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			if($row["status"]==1) {
				echo '<span class="msg-error">Нельзя вносить изменения, если конкурс активен.</span>';
			}else{

				if(count($_POST)>0) {
					$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
					$type_kon = ( isset($_POST["type_kon"]) && preg_match("|^[\d]{1}$|", trim($_POST["type_kon"])) ) ? intval(limpiar(trim($_POST["type_kon"]))) : false;
					$count_kon = ( isset($_POST["count_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["count_kon"])) ) ? intval(limpiar(trim($_POST["count_kon"]))) : false;
					$time_kon = ( isset($_POST["time_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["time_kon"])) ) ? intval(limpiar(trim($_POST["time_kon"]))) : false;

					if($type_kon<1 && $type_kon>5) {
						echo '<span class="msg-error">Не выбран тип конкурса.</span>';
					}elseif($count_kon<3) {
						echo '<span class="msg-error">Количество призовых мест не может быть меньше 3.</span>';
					}elseif($count_kon>20) {
						echo '<span class="msg-error">Количество призовых мест не может быть больше 20.</span>';
					}elseif($time_kon<3) {
						echo '<span class="msg-error">Время проведения конкурса не может быть менее 3 дней.</span>';
					}elseif($time_kon>60) {
						echo '<span class="msg-error">Время проведения конкурса не может быть больше 60 дней.</span>';
					}else{
						mysql_query("UPDATE `tb_refkonkurs` SET `type_kon`='$type_kon', `count_kon`='$count_kon', `time_kon`='$time_kon' WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

						echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs2&id='.$id.'");</script>';
						echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs2&id='.$id.'"></noscript>';
					}
				}

				echo '<form action="" method="POST" id="newform">';
				echo '<input type="hidden" name="id" value="'.$id.'">';
				echo '<table class="tables">';
				echo '<thead><tr align="center"><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
				echo '<tr>';
					echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
					echo '<td colspan="2">';
						echo '<select name="type_kon" onChange="viewinfo(this.value);" onKeyUp="viewinfo(this.value);">';
							echo '<option value="0" disabled="disabled" style="color:#000; font-weight:bold;" '.('0' == $type_kon ? 'selected="selected"' : '').'>Выберите конкурс</option>';
							echo '<option value="1" '.('1' == $row["type_kon"] ? 'selected="selected"' : '').'>Выполнение заданий</option>';
							echo '<option value="2" '.('2' == $row["type_kon"] ? 'selected="selected"' : '').'>Выполнение оплачиваемых кликов</option>';
							echo '<option value="3" '.('3' == $row["type_kon"] ? 'selected="selected"' : '').'>Привлечение рефералов</option>';
							echo '<option value="4" '.('4' == $row["type_kon"] ? 'selected="selected"' : '').'>Комплексный (по баллам)</option>';
							echo '<option value="5" '.('5' == $row["type_kon"] ? 'selected="selected"' : '').'>Выполнение оплачевыемых просмотров видеороликов)</option>';
							echo '<option value="6" '.('6' == $row["type_kon"] ? 'selected="selected"' : '').'>Сумма заработанная для реферера)</option>';
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>Описание конкурса:</b></td>';
					echo '<td id="konkurs_info" colspan="2"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>Количество призовых мест</b></td>';
					echo '<td width="160px"><input type="text" name="count_kon" value="'.$row["count_kon"].'" class="ok12" maxlength="2" style="width:50px; text-align:center;"> (от 3 до 20)</td>';
					echo '<td align="left">Количество возможных победителей в конкурсе</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>Время конкурса</b></td>';
					echo '<td><input type="text" name="time_kon" value="'.$row["time_kon"].'" class="ok12" maxlength="2" style="width:50px; text-align:center;"> дней (от 3 до 60)</td>';
					echo '<td align="left">Срок проведения конкурса в днях</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
				echo '</tr>';
				echo '</table>';
				echo '</form>';

				?><script language="JavaScript">
					var konkursinfo = new Array();
					konkursinfo[0] = ''
					konkursinfo[1] = 'Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса'
					konkursinfo[2] = 'Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса'
					konkursinfo[3] = 'Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса'
					konkursinfo[4] = 'Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса.<br><br>Баллы рассчитываются по схеме:<br>-&nbsp;просмотр ссылки в серфинге - 1 балл;<br>-&nbsp;просмотр видеороликов в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 балл;<br>-&nbsp;чтение оплачиваемого письма - 2 балла;<br>-&nbsp;прохождение теста - 3 балла;<br>-&nbsp;выполнение многоразового задания - 3 балла;<br>-&nbsp;выполнение одноразового задания - 5 баллов;<br>-&nbsp;приглашение реферала - 7 баллов.'
					konkursinfo[5] = 'Призовые места займут те, кто больше посмотрит видеороликов, за время проведения конкурса'
					konkursinfo[6] = 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.'
						
					function gebi(id){
						return document.getElementById(id)
					}
					function viewinfo(i){
						if (konkursinfo[i]){
							gebi("konkurs_info").innerHTML = konkursinfo[i];
						}
					}
					viewinfo(<?=$row["type_kon"];?>);
				</script><?php
			}
		}else{
			echo '<span class="msg-error">Конкурс не найден или не принадлежит Вам</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="delkonkurs") {

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		mysql_query("DELETE FROM `tb_refkonkurs` WHERE `id`='$id' AND `status`!='1' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="addmoneykonkurs") {

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
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

			function day_w($m){
				$m = str_replace("Monday","Понедельник",$m);
				$m = str_replace("Tuesday","Вторник",$m);
				$m = str_replace("Wednesday","Среда",$m);
				$m = str_replace("Thursday","Четверг",$m);
				$m = str_replace("Friday","Пятница",$m);
				$m = str_replace("Saturday","Суббота",$m);
				$m = str_replace("Sunday","Воскресенье",$m);
				return $m;
			}

			$desc_kon = new bbcode($row["desc"]);
			$desc_kon = $desc_kon->get_html();
			$cena_kon = round(($p*1.1),2);

			$sql_b = mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$username' AND `type_kon`='".$row["type_kon"]."' ORDER BY `id` DESC");
			if(mysql_num_rows($sql_b)>0) {
				echo '<span class="msg-error">ВНИМАНИЕ!<br>У Вас уже есть активный конкурс, запустить новый конкурс можно только после того, как старый будет завершен.</span>';
			}else{
				if(count($_POST)>0) {
					$dstart = ( isset($_POST["dstart"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["dstart"])) ) ? intval(limpiar(trim($_POST["dstart"]))) : false;

					if($dstart!=false) {
						if($dstart<time()) {
							echo '<span class="msg-error">Неверная дата начала конкурса.</span>';
						}else{
							if($money_rb<$cena_kon) {
								echo '<span class="msg-error">Не хватает денег на балансе аккаунта для запуска конкурса. [<a href="/money_add.php" target="_blank">пополнить баланс аккаунта на '.p_ceil(($cena_kon-$money_rb),2).' руб.</a>]</span>';
							}else{
								mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena_kon' WHERE `username`='$username'") or die(mysql_error());
								mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username','".DATE("d.m.Yг. H:i")."','$cena_kon','Оплата конкурса для рефералов №$id','Снято','rashod')") or die(mysql_error());
								mysql_query("UPDATE `tb_refkonkurs` SET `status`='1', `date_s`='".$dstart."', `date_e`='".($dstart+$row["time_kon"]*24*60*60-1)."' WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

								echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
								echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';
							}
						}
					}else{
						echo '<span class="msg-error">Неверная дата начала конкурса.</span>';
					}
				}
			}

			echo '<h3 class="sp">Пополнение баланса и запуск конкурса для рефералов</h3>';
			echo '<form action="" method="POST" id="newform">';
			echo '<input type="hidden" name="id" value="'.$id.'">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
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
						echo 'Выполнение оплачиваемых просмотров видеороликов';
					}elseif($row["type_kon"]==6) {
						echo 'Сумма заработанная для реферера';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Описание конкурса:</b></td>';
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
						echo '-&nbsp;прохождение теста - '.$tests_reiting_konk.' балла;<br>';
						echo '-&nbsp;выполнение многоразового задания - 3 балла;<br>';
						echo '-&nbsp;выполнение одноразового задания - 5 баллов;<br>';
						
						echo '-&nbsp;приглашение реферала - 7 баллов.';
					}elseif($row["type_kon"]==5) {
						echo 'Призовые места займут те, кто больше просмотрит оплачеваемых видеороликов, за время проведения конкурса';
					}elseif($row["type_kon"]==6) {
						echo 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Количество призовых мест</b></td>';
				echo '<td>'.$row["count_kon"].'</td>';
				echo '<td align="left">Количество возможных победителей в конкурсе</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Время конкурса</b></td>';
				echo '<td>'.@$d.'</td>';
				echo '<td align="left">Срок проведения конкурса в днях</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Призы:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'руб.</b>, ';
					}
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td align="right"><b>Для участия требуется выполнить заданий не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td align="right"><b>Для участия требуется выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td align="right"><b>Для участия требуется привлечь не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td align="right"><b>Для участия требуется набрать баллов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td align="right"><b>Для участия требуется посмотреть видеороликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==3) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия реферал должен выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit2_kon"].'</b></td>';
				echo '</tr>';
			}
			
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия требуется заработать не менее:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>Глубина конкурса:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo 'для рефералов 1-го уровня';
					}else{
						echo 'для рефералов всех уровей<br>';
						echo 'в конкурсе рефереров участвуют все рефералы до 3-го уровеня';
					}
				echo '</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">Описание конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">Запуск конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td align="right"><b>Призовой фонд:</b></td>';
				echo '<td colspan="2">'.$p.' руб.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Комиссия системы:</b></td>';
				echo '<td colspan="2">10%</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Стоимость конкурса:</b></td>';
				if($money_rb<$cena_kon) {
					echo '<td><b>'.$cena_kon.' руб.</b> [<a href="/money_add.php" target="_blank" style="color: #FF0000;">пополнить баланс аккаунта на '.p_ceil(($cena_kon-$money_rb),2).' руб.</a>]</td>';
				}else{
					echo '<td><b>'.$cena_kon.' руб.</b></td>';
				}
				echo '<td align="left">Стоимость проведения конкурса с комиссией системы</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Начало конкурса:</b></td>';
				echo '<td>';
					echo '<select name="dstart">';
						$d1 = (strtotime(DATE("d.m.Y", time()))+24*60*60);
						$d2 = (strtotime(DATE("d.m.Y", time()))+10*24*60*60);
						$sh = (24*60*60);
						for($i=$d1; $i<=$d2; $i=$i+$sh) {
							echo '<option value="'.$i.'">'.day_w(DATE("d.m.Y l", $i)).'</option>';
						}
					echo '</select>';
				echo '</td>';
				echo '<td align="left">Укажите дату начала конкурса</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="Пополнить и запустить" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form><br>';
			echo '<p><b>ВНИМАНИЕ!</b> Внимательно проверьте все данные перед запуском конкурса, изменить их во время конкурса будет невозможно!</p>';
		}else{
			echo '<span class="msg-error">Конкурс не найден или не принадлежит Вам</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="add_konkurs") {

		for($i=1; $i<=20; $i++){$p[$i]=0;}
		$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
		$type_kon = ( isset($_POST["type_kon"]) && preg_match("|^[\d]{1}$|", trim($_POST["type_kon"])) ) ? intval(limpiar(trim($_POST["type_kon"]))) : false;
		$count_kon = ( isset($_POST["count_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["count_kon"])) ) ? intval(limpiar(trim($_POST["count_kon"]))) : false;
		$time_kon = ( isset($_POST["time_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["time_kon"])) ) ? intval(limpiar(trim($_POST["time_kon"]))) : false;
		$savekon = isset($_POST["savekon"]) ? limpiar(trim($_POST["savekon"])) : false;
		$limit_kon = ( isset($_POST["limit_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_kon"])) ) ? intval(limpiar(trim($_POST["limit_kon"]))) : "0";
		$limit2_kon = ( isset($_POST["limit2_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit2_kon"])) ) ? intval(limpiar(trim($_POST["limit2_kon"]))) : "0";
		$limit_kon_sum = ( isset($_POST["limit_kon_sum"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_kon_sum"])) ) ? intval(limpiar(trim($_POST["limit_kon_sum"]))) : "0";
		$desc_kon = isset($_POST["desc_kon"]) ? limitatexto(trim($_POST["desc_kon"]),1000) : false;
		$glubina = ( isset($_POST["glubina"]) && preg_match("|^[\d]{1}$|", trim($_POST["glubina"])) ) ? intval(limpiar(trim($_POST["glubina"]))) : "0";
		if (get_magic_quotes_gpc()) { $desc_kon = stripslashes($desc_kon); }

		for($i=1; $i<=$count_kon; $i++){
			$p[$i] = (isset($_POST["p$i"])) ? round(abs(floatval(str_replace(",",".",trim($_POST["p$i"])))),2) : 0;
		}

		if($time_kon >0) {
			switch(substr($time_kon, -1, 1)){
				case 1: $d="день"; break;
				case 2: case 3: case 4: $d="дня"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d="дней"; break;
			}
		}

		if(count($_POST)>0 && $type_kon>0 && $type_kon<=6 && $count_kon>=3 && $count_kon<=20 && $time_kon>=3 && $time_kon<=60) {
			$s = "OK";
			for($i=1; $i<=$count_kon; $i++){
				if(isset($_POST["p$i"])) {
					if($p[$i]==0){
						echo '<span class="msg-error">Приз за '.$i.' место нулевой.</span>'; $s="NO";
						break;
					}
					if($p[$i] < $p[$i+1]) {
						echo '<span class="msg-error">Приз за '.($i+1).' место больше, чем за '.$i.'.</span>'; $s="NO";
						break;
					}
				}
			}

			if($savekon=="savekonkurs2" && $s=="OK") {
				mysql_query("INSERT INTO `tb_refkonkurs` (`status`,`username`,`type_kon`,`glubina`,`date_s`,`date_e`,`count_kon`,`time_kon`,`limit_kon`,`limit2_kon`,`limit_kon_sum`,`desc`,`p1`,`p2`,`p3`,`p4`,`p5`,`p6`,`p7`,`p8`,`p9`,`p10`,`p11`,`p12`,`p13`,`p14`,`p15`,`p16`,`p17`,`p18`,`p19`,`p20`,`ip`) 
				VALUES('0','$username','$type_kon','$glubina','0','0','$count_kon','$time_kon','$limit_kon','$limit2_kon','$limit_kon_sum','$desc_kon','$p[1]','$p[2]','$p[3]','$p[4]','$p[5]','$p[6]','$p[7]','$p[8]','$p[9]','$p[10]','$p[11]','$p[12]','$p[13]','$p[14]','$p[15]','$p[16]','$p[17]','$p[18]','$p[19]','$p[20]','$ip')") or die(mysql_error());

				$sql = mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `username`='$username' ORDER BY `id` DESC");
				if(mysql_num_rows($sql)>0) {
					$id_kon = mysql_result($sql,0,0);

					echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=addmoneykonkurs&id='.$id_kon.'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=addmoneykonkurs&id='.$id_kon.'"></noscript>';
				}
			}

			?><script type="text/javascript" src="bbcode/xbb.js.php"></script>
			<script type="text/javascript">
				XBB.textarea_id = 'desc_kon';
				XBB.area_width = '98%';
				XBB.area_height = '200px';
				XBB.state = 'plain';
				XBB.lang = 'ru_cp1251';
				onload = function() {
				XBB.init();
			}
			</script><?php

			echo '<h3 class="sp">Создание нового конкурса для рефералов</h3>';
			echo '<form action="" method="POST" id="newform">';
				echo '<input type="hidden" name="savekon" value="savekonkurs2">';
				echo '<input type="hidden" name="type_kon" value="'.$type_kon.'">';
				echo '<input type="hidden" name="count_kon" value="'.$count_kon.'">';
				echo '<input type="hidden" name="time_kon" value="'.$time_kon.'">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo 'Выполнение заданий';
					}elseif($type_kon==2) {
						echo 'Выполнение оплачиваемых кликов';
					}elseif($type_kon==3) {
						echo 'Привлечение рефералов';
					}elseif($type_kon==4) {
						echo 'Комплексный (по баллам)';
					}elseif($type_kon==5) {
						echo 'Выполнение оплачиваемых просмотров видеороликов';
					}elseif($type_kon==6) {
						echo 'Сумма заработанная для реферера';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Описание конкурса:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo 'Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса';
					}elseif($type_kon==2) {
						echo 'Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса';
					}elseif($type_kon==3) {
						echo 'Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса';
					}elseif($type_kon==4) {
						echo 'Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса.<br><br>Баллы рассчитываются по схеме:<br>';
						echo '-&nbsp;просмотр ссылки в серфинге - 1 балл;<br>';
						echo '-&nbsp;просмотр видеороликов в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 балл;<br>';
						echo '-&nbsp;чтение оплачиваемого письма - 2 балла;<br>';
						echo '-&nbsp;прохождение теста - '.$tests_reiting_konk.' балла;<br>';
						echo '-&nbsp;выполнение многоразового задания - 3 балла;<br>';
						echo '-&nbsp;выполнение одноразового задания - 5 баллов;<br>';
						
						echo '-&nbsp;приглашение реферала - 7 баллов.';
					}elseif($type_kon==5) {
						echo 'Призовые места займут те, кто больше просмотрит оплачеваемых видеороликов, за время проведения конкурса';
					}elseif($type_kon==6) {
						echo 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.';
					}else{
						echo '<b style="color: #FF0000;">Не выбран тип конкурса</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Количество призовых мест</b></td>';
				echo '<td width="115px">'.$count_kon.'</td>';
				echo '<td align="left">Количество возможных победителей в конкурсе</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Время конкурса</b></td>';
				echo '<td>'.$time_kon.' '.@$d.'</td>';
				echo '<td align="left">Срок проведения конкурса в днях</td>';
			echo '</tr>';

			echo '<tr>';
				if($type_kon==1) {
					echo '<td align="right"><b>Для участия требуется выполнить заданий не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==2) {
					echo '<td align="right"><b>Для участия требуется выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==3) {
					echo '<td align="right"><b>Для участия требуется привлечь не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==4) {
					echo '<td align="right"><b>Для участия требуется набрать баллов не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==5) {
					echo '<td align="right"><b>Для участия требуется просмотреть видеороликов не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}
			
			echo '</tr>';
			
			if($type_kon==3) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия реферал должен выполнить кликов не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit2_kon" value="'.$limit2_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				echo '</tr>';
			}
			
			if($type_kon==6) {
				echo '<tr>';
					echo '<td align="right"><b>Для участия требуется заработать не менее:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon_sum" value="'.$limit_kon_sum.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>Глубина конкурса:</b></td>';
				echo '<td colspan="2">';
					echo '<input type="radio" name="glubina" value="0" checked="checked"> - для рефералов 1-го уровня<br>';
					echo '<input type="radio" name="glubina" value="1"> - для рефералов всех уровей<br>';
					echo 'в конкурсе рефереров участвуют все рефералы до 3-го уровеня';
				echo '</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">Призовые места</th></tr></thead>';
			for($i=1; $i<=$count_kon; $i++){
				echo '<tr>';
					echo '<td align="right"><b>Приз за '.$i.' место:</b></td>';
					echo '<td><input type="text" name="p'.$i.'" value="'.$p[$i].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"> руб.</td>';
					echo '<td align="left">Сумма, которую получит пользователь, если займет '.$i.' место</td>';
				echo '</tr>';
			}

			echo '<thead><tr align="center"><th align="center" colspan="3">Описание конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td align="right"><b>Текст сообщения:</b></td>';
				echo '<td colspan="2"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3"><textarea style="width:99%;height:200px;" name="desc_kon" id="desc_kon">'.$desc_kon.'</textarea></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3">Тут Вы можете указать любую информацию по конкурсу, которую увидят Ваши рефералы, если откроют описание конкурса. Даты проведения конкурса и призовые места указываются автоматически.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';
		}else{
			if(count($_POST)>0) {
				if($type_kon<1 && $type_kon>6) {
					echo '<span class="msg-error">Не выбран тип конкурса.</span>';
				}elseif($count_kon<3) {
					echo '<span class="msg-error">Количество призовых мест не может быть меньше 3.</span>';
					$count_kon = 10;
				}elseif($count_kon>20) {
					echo '<span class="msg-error">Количество призовых мест не может быть больше 20.</span>';
					$count_kon = 10;
				}elseif($time_kon<3) {
					echo '<span class="msg-error">Время проведения конкурса не может быть менее 3 дней.</span>';
					$time_kon = 7;
				}elseif($time_kon>60) {
					echo '<span class="msg-error">Время проведения конкурса не может быть больше 60 дней.</span>';
					$time_kon = 7;
				}
			}

			if($count_kon<3 | $count_kon>20) {
				$count_kon = 10;
			}
			if($time_kon<3 | $time_kon>60) {
				$time_kon = 7;
			}

			echo '<h3 class="sp">Создание нового конкурса для рефералов</h3>';
			echo '<form action="" method="POST" id="newform">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">Тип конкурса</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Тип конкурса:</b></td>';
				echo '<td colspan="2">';
					echo '<select name="type_kon" onChange="viewinfo(this.value);" onKeyUp="viewinfo(this.value);" style="">';
						echo '<option value="0" disabled="disabled" style="color:#000; font-weight:bold;" '.('0' == $type_kon ? 'selected="selected"' : '').'>Выберите конкурс</option>';
						echo '<option value="1" '.('1' == $type_kon ? 'selected="selected"' : '').'>Выполнение заданий</option>';
						echo '<option value="2" '.('2' == $type_kon ? 'selected="selected"' : '').'>Выполнение оплачиваемых кликов</option>';
						echo '<option value="3" '.('3' == $type_kon ? 'selected="selected"' : '').'>Привлечение рефералов</option>';
						echo '<option value="4" '.('4' == $type_kon ? 'selected="selected"' : '').'>Комплексный (по баллам)</option>';
						echo '<option value="5" '.('5' == $type_kon ? 'selected="selected"' : '').'>Выполнение оплачеваемых просмотров видеороликов</option>';
						echo '<option value="6" '.('6' == $type_kon ? 'selected="selected"' : '').'>Сумма заработанная для реферера</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Описание конкурса:</b></td>';
				echo '<td id="konkurs_info" colspan="2">&nbsp;</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Количество призовых мест</b></td>';
				echo '<td width="160px"><input type="text" name="count_kon" value="'.$count_kon.'" class="ok12" style="width:50px; text-align:center;" maxlength="2"> мест (от 3 до 20)</td>';
				echo '<td align="left">Количество возможных победителей в конкурсе</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>Время конкурса</b></td>';
				echo '<td><input type="text" name="time_kon" value="'.$time_kon.'" class="ok12" style="width:50px; text-align:center;" maxlength="2"> дней (от 3 до 60)</td>';
				echo '<td align="left">Срок проведения конкурса в днях</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';

			?><script language="JavaScript">
				var konkursinfo = new Array();
				konkursinfo[0] = ''
				konkursinfo[1] = 'Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса'
				konkursinfo[2] = 'Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса'
				konkursinfo[3] = 'Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса'
				konkursinfo[4] = 'Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса.<br><br>Баллы рассчитываются по схеме:<br>-&nbsp;просмотр ссылки в серфинге - 1 балл;<br>-&nbsp;просмотр видеороликов в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 балл;<br>-&nbsp;чтение оплачиваемого письма - 2 балла;<br>-&nbsp;выполнение многоразового задания - 3 балла;<br>-&nbsp;выполнение одноразового задания - 5 баллов;<br>-&nbsp;прохождение теста - <?=$tests_reiting_konk;?> балла;<br>-&nbsp;приглашение реферала - 7 баллов.'
				konkursinfo[5] = 'Призовые места займут те, кто больше посмотрит оплачеваемых видеороликов, за время проведения конкурса'
				konkursinfo[6] = 'Призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса.'

				function gebi(id){
					return document.getElementById(id)
				}
				function viewinfo(i){
					if (konkursinfo[i]){
						gebi("konkurs_info").innerHTML = konkursinfo[i];
					}
				}
			</script><?php
		}

	}else{
	    echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">Конкурсы для рефералов</span>';
		        echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                        echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f3e7d8" align="left"> ';
		//echo '<h3 class="sp" style="margin-bottom:3px;">На данный момент доступны для создания следующие конкурсы:</h3>';
		echo ' - <b>Выполнение заданий</b> (Призовые места займут те, кто выполнит наибольшее количество заданий за время проведения конкурса)<br>';
		echo ' - <b>Выполнение оплачиваемых кликов</b> (Призовые места займут те, кто больше выполнит оплачиваемых кликов, за время проведения конкурса)<br>';
		echo ' - <b>Привлечение рефералов</b> (Призовые места займут те, кто пригласит наибольшее количество рефералов за время проведения конкурса)<br>';
		echo ' - <b>Комплексный, по баллам</b> (Призовые места займут те, кто наберет наибольшее количество баллов за время проведения конкурса)<br>';
		echo ' - <b>Выполнение оплачиваемых просмотров видеороликов <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b> (Конкурс среди рефералов: кто больше посмотрит видеороликов <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>, за время проведения конкурса)<br><br>';
		echo ' - <b>Сумма заработанная для реферера</b> (призовые места займут те, кто выполняя серфинг, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, письма, тесты, задания заработает для своего реферера наибольшую сумму денег за время проведения конкурса)<br>';

		echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=add_konkurs"><img src="img/add.png" border="0" alt="" align="absmiddle" title="Создать новый конкурс для рефералов" /></a><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=add_konkurs" title="Создать новый конкурс для рефералов">Создать новый конкурс для рефералов</a><br><br>';
		echo '</div>';
                        echo '</div>';

		echo '<form action="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'" method="POST" id="newform">';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center" rowspan="2">№</th>';
			echo '<th align="center" rowspan="2">Тип конкурса</th>';
			echo '<th align="center">Начало</th>';
			echo '<th align="center">Окончание</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `username`='$username' ORDER BY `id` DESC");
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
						$type_kon="<b>Выполнение оплачеваемых просмотров видеороликов</b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>Сумма заработанная для реферера</b>";
					}else{
						$type_kon='<b style="color:#FF0000;">Тип конкурса не определен!</b>';
					}

					if($row["status"]==0) {
						$status='<span style="color: #808080;">Не активен, пополните баланс конкурса</span>';
						$edit = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs&id='.$row["id"].'">редактировать</a>]';
						$del = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=delkonkurs&id='.$row["id"].'" onclick=\'if(!confirm("Вы уверены, что хотите удалить конкурс?"))return false;\'>удалить</a>]';
						$addmoney = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=addmoneykonkurs&id='.$row["id"].'">пополнить баланс и запустить конкурс</a>]';
						$statistik = "";
					}elseif($row["status"]==1) {
						$status='<span style="color: #009200;">Активен, идет конкурс</span>';
						$edit = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs3&id='.$row["id"].'">редактировать</a>]';
						$del = "";
						$addmoney = "";
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'">статистика</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">Конкурс завершен, итоги подведены, призы зачислены</span>';
						$edit = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs&id='.$row["id"].'">редактировать</a>]';
						$del = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=delkonkurs&id='.$row["id"].'" onclick=\'if(!confirm("Вы уверены, что хотите удалить конкурс?"))return false;\'>удалить</a>]';
						$addmoney = "";
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'">статистика</a>]';
					}else{
						$status="";
						$edit = "";
						$del = "";
						$addmoney = "";
						$statistik = "";
					}

					echo '<td>'.$type_kon.'<br>['.$row["count_kon"].' '.$m.', призовой фонд <b>'.$p.' руб.</b>]<br>'.$status.'<br>'.$edit.' '.$del.' '.$addmoney.' '.$statistik.'</td>';

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