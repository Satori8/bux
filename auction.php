<?php
$pagetitle="АУКЦИОН РЕФЕРАЛОВ";
require_once('.zsecurity.php');
require_once('./merchant/func_mysql.php');
include('header.php');
?>
<script type="text/javascript" src="scripts/showhide4.js"></script>

<?php
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="errorp">Ошибка! Для доступа к этой странице необходимо авторизоваться!</fieldset>';
}else{
	require('config.php');

		$domen = ucfirst($_SERVER['HTTP_HOST']);
		$domen = str_replace("wm","WM", $domen);
		$domen = str_replace("ru","RU", $domen);

		$attestat[100]='<font color="#000"><img src="/img/att/att_100.ico" alt="" align="absmiddle" border="0" /> Аттестат Псевдонима</font>';
		$attestat[110]='<font color="green"><img src="/img/att/att_110.ico" alt="" align="absmiddle" border="0" /> Формальный Аттестат</font>';
		$attestat[120]='<font color="green"><img src="/img/att/att_120.ico" alt="" align="absmiddle" border="0" /> Начальный Аттестат</font>';
		$attestat[130]='<font color="green"><img src="/img/att/att_130.ico" alt="" align="absmiddle" border="0" /> Персональный Аттестат</font>';
		$attestat[135]='<font color="green"><img src="/img/att/att_135.ico" alt="" align="absmiddle" border="0" /> Аттестат Продавца</font>';
		$attestat[136]='<font color="green"><img src="/img/att/att_136.ico" alt="" align="absmiddle" border="0" /> Аттестат Capitaller</font>';
		$attestat[140]='<font color="green"><img src="/img/att/att_140.ico" alt="" align="absmiddle" border="0" /> Аттестат Разработчика</font>';
		$attestat[150]='<font color="green"><img src="/img/att/att_150.ico" alt="" align="absmiddle" border="0" /> Аттестат Регистратора</font>';
		$attestat[170]='<font color="green"><img src="/img/att/att_170.ico" alt="" align="absmiddle" border="0" /> Аттестат Гаранта</font>';
		$attestat[190]='<font color="green"><img src="/img/att/att_190.ico" alt="" align="absmiddle" border="0" /> Аттестат Сервиса</font>';
		$attestat[300]='<font color="green"><img src="/img/att/att_300.ico" alt="" align="absmiddle" border="0" /> Аттестат Оператора</font>';
		$attestat[0]='<font color="red">Неопредлен</font>';
		$attestat[1]='<font color="red">Неопредлен</font>';
		$attestat[-1]='<font color="red">Неопредлен</font>';


		// Аукцион
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time' AND `howmany`='1'");
		$auc_time = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_end_add' AND `howmany`='1'");
		$auc_time_end_add = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_add' AND `howmany`='1'");
		$auc_time_add = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_comis' AND `howmany`='1'");
		$auc_comis = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_click_user' AND `howmany`='1'");
		$auc_limit_click_user = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_last_user' AND `howmany`='1'");
		$auc_limit_activ_last_user = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_max' AND `howmany`='1'");
		$auc_max = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_all_user' AND `howmany`='1'");
		$auc_limit_activ_all_user = mysql_result($sql,0,0);
		// END Аукцион

		echo '<div align="left">';
			echo '<img src="img/help.ico" align="absmiddle" title="Правила проведения аукциона!" alt="" border="0" /><b> <a href="javascript: void(0);" id="opislink1" style="cursor:help;">Правила проведения аукциона!</a></b><br>';
		echo '</div>';
		echo '<div style="display: none; width:auto; text-align:justify; margin:0px 0px 0px 0px; padding:5px 10px 5px 10px; border:1px solid #CCC; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;" id="opis1">';
			echo '<div align="center" style="font-weight: bold;">Правила проведения аукциона</div><br>';
			echo 'На аукционе рефералов проекта <a href="/"><b>'.$domen.'</b></a> лоты выставляют САМИ ПОЛЬЗОВАТЕЛИ!<br>';
			echo 'Аукцион рефералов на проекте <a href="/"><b>'.$domen.'</b></a> проводится по принципу "Скандинавского аукциона" с той лишь разницей, что победитель <b>НЕ</b> оплачивает в конце сумму всех ставок.<br>';
			echo 'Если в данный момент проводится несколько аукционов, в списке они сортируются по времени окончания. Чем меньше времени остается до окончания проведения аукциона, тем выше он в списке.<br>';
			echo 'Пользователи, уличенные в сговоре, а также, угрожающие другим участникам аукциона, будут оштрафованы и лишены всех рефералов, купленных на аукционе!!!<br><br>';
			echo '<b>Создание аукциона</b><br><br>';
			echo 'Создать аукцион, т.е. выставить реферала, можно со страницы списка своих рефералов.<br>Существует несколько ограничений на создание аукциона:<br>';
			echo '1. На аукцион можно выставить только того реферала, который сделал <b>'.$auc_limit_click_user.'</b> и больше кликов, с момента регистрации на проекте прошло <b>'.$auc_limit_activ_all_user.'</b> и более дней, а также последний вход в аккаунт был не позже <b>'.$auc_limit_activ_last_user.'</b> дней.<br>';
			echo '2. Одновременно может проходить не более <b>'.$auc_max.'</b> аукционов<br>';
			echo '3. Размер ставки может быть от <b>1.00</b> до <b>10.00</b> руб. Шаг - <b>1.00</b> руб. Размер ставки устанавливает продавец.<br>';
			echo '4. Комиссия проекта за проведение аукциона составляет <b>'.$auc_comis.'%</b> от суммы ставок.<br>';
			echo 'В описании лота запрещено размещать ЛЮБУЮ информацию, не относящуюся к данному лоту. За нарушение этого пункта лот будет незамедлительно удален без возмещения средств. К владельцу лота будут применены санкции, вплоть до блокировки на проекте, без выплаты вложенных/заработанных средств.<br><br>';
			echo '<b>Проведение аукциона</b><br><br>';
			echo 'Во избежании случаев мошенничества и других недоразумений, мы выводим такую информацию о реферале, выставленном на аукцион, как количество кликов реферала, дату регистрации на проекте, дату последней активности на проекте и количество рефералов, рейтинг.<br>';
			//echo 'Участвовать в торгах на аукционе могут только те пользователи, чей аттестат в системе WMT не ниже формального.<br>';
			echo 'Размер ставки на каждом конкретном аукционе устанавливает продавец.<br>';
			echo 'Оплата ставки происходит со счета на проекте <a href="/"><b>'.$domen.'</b></a>.<br>';
			echo 'Изначально, время проведения аукциона устанавливается равным  <b>'.$auc_time.'</b> минут(у/ы). Если в течении этого времени не будет сделано ни одной ставки, аукцион считается завершенным.<br>';
			echo 'Если ставка сделана менее чем за <b>'.$auc_time_end_add.'</b> минут(у/ы) до конца аукциона, время проведения аукциона увеличивается на <b>'.$auc_time_add.'</b> минут(у/ы).<br>';
			echo 'Если после Вас ставку делает другой пользователь, Ваша ставка сгорает. Деньги на счет <b>НЕ</b> возвращаются.<br>';
			echo 'Вы <b>НЕ</b> сможете сделать ставку, если Вы являетесь лидером аукциона, т.е. Ваша ставка - последняя.<br>';
			echo 'Также, Вы <b>НЕ</b> сможете сделать ставку, если Вы сами являетесь рефералом, выставленным на этот аукцион.<br>';
			echo 'Стоит отметить, что таймер на странице аукциона статичен. Т.е. пользователю показывается примерное время до окончания аукциона.<br><br>';
			echo 'Все необходимые зачисления будут сделаны в течение 1 часа после завершения аукциона<br>';
			echo '<b style="color:#FF0000;">Внимание!<br>';
			echo 'Если у Вас есть хоть малейшее сомнение в понимании работы аукциона, лучше воздержитесь от участия в торгах! Выясните сначала все подробно...</b><br><br>';
			echo 'Во всем остальном никаких ограничений нет.<br>';
			echo '<b>Успехов Вам и удачи в торгах!</b>';
		echo '</div>';
		echo '<div align="left">Перед тем как принять участие в аукционе, убедитесь, что ВЫ внимательно прочитали правила проведения аукциона!</div><br><br>';

		mysql_query("DELETE FROM `tb_auction` WHERE `status`='1' AND `timer_end`<='".time()."' AND `kolstv`='0' AND `lider`=''") or die(mysql_error());

		$sql = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `timer_end`<='".time()."'");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {

				$id = $row["id"];
				$user_s = $row["username"];
				$referal = $row["referal"];
				$timer_end = $row["timer_end"];
				$stavka = $row["stavka"];
				$lider = $row["lider"];
				$kolstv = $row["kolstv"];

				if($timer_end<time()) {

					$money_add = ($stavka * $kolstv * (100 - $auc_comis)/100);
					$money_sys = ($stavka * $kolstv * $auc_comis/100);

					mysql_query("UPDATE `tb_auction` SET `status`='0', `date_end`='".time()."', `summa`='$money_add', `proc`='$money_sys' WHERE `id`='$id' AND `status`='1' AND `timer_end`<='".time()."'") or die(mysql_error());

					mysql_query("UPDATE `tb_users` SET `referals`=`referals`-'1', `money`=`money`+'$money_add' WHERE `username`='$user_s'") or die(mysql_error());
					mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$user_s','".DATE("d.m.Yг. в H:i")."','$money_add','Доход от продажи реферала в Аукционе №$id','Зачислено','prihod')") or die(mysql_error());

					mysql_query("UPDATE `tb_users` SET `referals`=`referals`+'1' WHERE `username`='$lider'") or die(mysql_error());
					
					stat_pay('auc', $money_sys);
					invest_stat($money_sys, 5);

					$sql_user = mysql_query("SELECT `referer`, `referer2` FROM `tb_users` WHERE `username`='$lider'");
					$row_user = mysql_fetch_array($sql_user);
						$my_referer1 = $row_user["referer"];
						$my_referer2 = $row_user["referer2"];

					mysql_query("UPDATE `tb_users` SET `referer`='$lider', `referer2`='$my_referer1', `referer3`='$my_referer2', `statusref`='auction' WHERE `username`='$referal'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `referer2`='$lider', `referer3`='$my_referer1' WHERE `referer`='$referal'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `referer3`='$lider' WHERE `referer2`='$referal'") or die(mysql_error());
				}
			}
		}


		if(count($_POST)>0 && isset($_POST["id"])) {
			$id = (isset($_POST["id"]) && preg_match("|^[\d]*$|", limpiar(trim($_POST["id"])))) ? intval(limpiar(trim($_POST["id"]))) : false;

			if($id!=false) {
				$sql = mysql_query("SELECT * FROM `tb_auction` WHERE `id`='$id'");
				if(mysql_num_rows($sql)>0) {
					$row = mysql_fetch_array($sql);

					$id = $row["id"];
					$status = $row["status"];
					$user_s = $row["username"];
					$referal = $row["referal"];
					$timer_end = $row["timer_end"];
					$stavka = $row["stavka"];
					$lider = $row["lider"];

					if($status==0)
						echo '<fieldset class="errorp">Ошибка! Аукцион #'.$id.' уже завершен! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					elseif(strtolower($user_s)==strtolower($username))
						echo '<fieldset class="errorp">Ошибка! Вы не можете делать ставки в своем аукционе! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					elseif(strtolower($referal)==strtolower($username))
						echo '<fieldset class="errorp">Ошибка! Вы не можете делать ставки в этом аукционе! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					elseif(strtolower($lider)==strtolower($username))
						echo '<fieldset class="errorp">Ошибка! Вы не можете сделать ставку, так как вы являетесь лидером в этом аукционе! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					elseif($timer_end<time())
						echo '<fieldset class="errorp">Ошибка! Аукцион #'.$id.' уже завершен! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					elseif($stavka>$money_rb)
						echo '<fieldset class="errorp">Ошибка! На вашем рекламном счету недостаточно средств для совершния ставки в аукционе #'.$id.'! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					else{
						if( ($timer_end-time()) < ($auc_time_end_add*60) ) {
							$new_timer = ($timer_end + ($auc_time_add * 60));
						}else{
							$new_timer = $timer_end;
						}

						mysql_query("UPDATE `tb_auction` SET `timer_end`='$new_timer', `kolstv`=`kolstv`+'1', `lider`='$username' WHERE `id`='$id' AND `status`='1' AND `timer_end`>='".time()."' AND `lider`!='$username'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$stavka' WHERE `username`='$username'") or die(mysql_error());
						mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username','".DATE("d.m.Yг. в H:i")."','$stavka','Ставка в аукционе №$id','Снято','rashod')") or die(mysql_error());

						echo '<fieldset class="okp">Ваша ставка принята! Теперь вы лидер в аукционе №'.$id.' <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';

					}
				}else{
					echo '<fieldset class="errorp">Ошибка! Такого аукциона нет! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';
				}
			}else{
				echo '<fieldset class="errorp">Ошибка! Такого аукциона нет! <a href="'.$_SERVER["PHP_SELF"].'">Перейти к аукционам!</a></fieldset>';
			}
		}else{


			$sql = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `timer_end`>='".time()."' ORDER BY `timer_end` ASC LIMIT $auc_max");
			if(mysql_num_rows($sql)>0) {

				echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="" value=""><input type="submit" class="submit" value="Обновить список"></form></div><br><br>';

				echo '<script type="text/javascript" src="../scripts/auction.js"></script>';

				while ($row = mysql_fetch_array($sql)) {

					$sql_u = mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$row["referal"]."'");
					$row_u = mysql_fetch_array($sql_u);

					$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];
					$info_wall = '<a href="/wall?uid='.$row_u["id"].'" target="_blank"><img src="img/wall20.png" title="Стена пользователя '.$row_u["username"].'" width="20" border="0" align="absmiddle" />';
					if($wall_com>0) {
						$info_wall.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
					}elseif($wall_com<0) {
						$info_wall.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
					}else{
						$info_wall.= '<b style="color:#000000;">0</b></a>';
					}


					echo '<div align="center" style="padding: 0px 0px 20px 0px; margin: 0px 10px 20px 10px; border-bottom: 2px dotted #669966;">';
					echo '<table class="tables">';
					echo '<thead><tr align="center"><th align="center" colspan="3" class="top">Аукцион #'.$row["id"].' (продавец: '.$row["username"].')</th></tr></thead>';
					echo '<tr>';
						echo '<td width="60%" style="padding:5px;">';
						echo '<table class="tables">';
							echo '<tr><td align="left" style="padding-right:20px;" colspan="2"><u>Информация о реферале:</u></td></tr>';
							echo '<tr><td align="left">ID:</td><td align="left">#'.$row_u["id"].'</td></tr>';
							echo '<tr><td align="left">Логин:</td><td align="left">'.$row_u["username"].'</td></tr>';
							echo '<tr><td align="left">Рейтинг:</td><td align="left"><img src="img/reiting.png" border="0" alt="" align="absmiddle" title="Рейтинг" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> '.$info_wall.'</td></tr>';
							echo '<tr><td align="left">Дата регистрации:</td><td align="left">'.DATE("d.m.Yг. H:i", $row_u["joindate2"]).'</td></tr>';
							if(DATE("d.m.Y", $row_u["lastlogdate2"])==DATE("d.m.Y")) {
								echo '<tr><td align="left">Дата последнего входа:</td><td align="left"><span style="color:green;">'.DATE("d.m.Yг. H:i", $row_u["lastlogdate2"]).'</span></td></tr>';
							}else{
								echo '<tr><td align="left">Дата последнего входа:</td><td align="left"><span>'.DATE("d.m.Yг. H:i", $row_u["lastlogdate2"]).'</span></td></tr>';
							}
							echo '<tr><td align="left">Аттестат в системе WMT:</td><td align="left">'.$attestat[$row_u["attestat"]].'</td></tr>';
							if($row_u["referals"]>0) {
								echo '<tr><td align="left">Рефералы:</td><td align="left"><span style="color:#FF0000;">'.$row_u["referals"].'</span> - <span style="color:#FF0000;">'.$row_u["referals2"].'</span> - <span style="color:#FF0000;">'.$row_u["referals3"].'</span></td></tr>';
							}else{
								echo '<tr><td align="left">Рефералы:</td><td align="left">'.$row_u["referals"].' - '.$row_u["referals2"].' - '.$row_u["referals3"].'</td></tr>';
							}
							echo '<tr><td align="left">Клики:</td><td align="left">'.number_format($row_u["visits"],0,".","'").'</td></tr>';
							echo '<tr><td align="left">Автосерфинг:</td><td align="left">'.number_format($row_u["visits_a"],0,".","'").'</td></tr>';
							echo '<tr><td align="left">Задания:</td><td align="left">'.number_format($row_u["visits_t"],0,".","'").'</td></tr>';
							echo '<tr><td align="left">Реклама:</td><td align="left">'.number_format($row_u["money_rek"],2,".","'").' руб.</td></tr>';
							if($row["inforef"]!="") {echo '<tr><td align="left">Доп. информация от продавца:</td><td></td></tr><tr><td align="left" colspan="2"><i>'.$row["inforef"].'</i></td></tr>';}
							echo '</table>';
						echo '</td>';
						echo '<td align="center" width="40%" style="padding:5px;">';
							echo '<table border="0" cellspacing="1" cellpadding="0">';
							echo '<tr><td colspan="2"><img src="avatar/'.$row_u["avatar"].'" width="80" height="80" border="0" align="middle" alt="" /><br></td></tr>';

							echo '<tr><td align="left" colspan="2" style="padding-top:10px;"><u>Информация  об аукционе:</u></td></tr>';
							echo '<tr><td align="left">Размер ставки:</td><td align="left"><b style="color: #FF0000;">'.number_format($row["stavka"],2,".","'").' руб.</b></td></tr>';
							if($row["kolstv"]>0) {
								echo '<tr><td align="left">Количество ставок:</td><td align="left"><b style="color:#FF0000;">'.number_format($row["kolstv"],0,".","'").'</b></td></tr>';
								echo '<tr><td align="left">Лидер аукциона:</td><td align="left"><b style="color:#FF0000;">'.$row["lider"].'</b></td></tr>';
							}else{
								echo '<tr><td colspan="2" align="left">Ставок еще небыло!</td></tr>';
							}

							echo '<tr><td colspan="2"><br><b>Окончание аукциона через:</b></td></tr>';
							echo '<tr><td colspan="2"><span style="font-size:150%; font-weight:bold; color:blue;" class="end_time">'.DATE("i:s", ($row["timer_end"]-time())).'</SPAN>&nbsp;мин.</td></tr>';
							echo '<tr><td colspan="2"><div id="form"><form method="POST" action=""><input type="hidden" name="id" value="'.$row["id"].'"><input type="submit" class="submit" value="Сделать ставку"></form></div></td></tr>';
							echo '</table>';
						echo '</td>';
					echo '</tr>';
					echo '</table>';
					echo '</div>';
				}
				echo '<script type="text/javascript">timer_init();</script>';
			}else{
				echo '<div align="center" style="color:#FF0000; font-weight:bold;">В даный момент активных аукционов нет.</div><br><br>';
				echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="" value=""><input type="submit" class="submit" value="Обновить список"></form></div><br><br>';
			}

			$sql_all = mysql_query("SELECT `username` FROM `tb_online` WHERE `username`!='' AND `page` LIKE '%".$_SERVER["PHP_SELF"]."%'") or die(mysql_error());
			$online_game = mysql_num_rows($sql_all);

			echo '<table align="center" border="0" width="100%" cellspacing="1" cellpadding="2">';
			echo '<tr><td colspan="5">Пользователи на аукционе: (<b>'.$online_game.'</b>):&nbsp;';
				while ($row_o = mysql_fetch_array($sql_all)) {
					echo '<span style="color:blue;">'.$row_o["username"].',</span> ';
				}
			echo '</td></tr>';
			echo "</table><br>";

			$sql = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='0' ORDER BY `date_end` DESC LIMIT 10");
			if(mysql_num_rows($sql)>0) {
				echo '<br><table class="tables">';
				echo '<thead><tr align="center"><td align="left" colspan="7">Последние 10 состоявшихся аукционов:</td></tr>';
				echo '<tr>';
					echo '<th align="center" class="top">#</th>';
					echo '<th align="center" class="top">Продавец</th>';
					echo '<th align="center" class="top">Победитель</th>';
					echo '<th align="center" class="top">Размер ставки</th>';
					echo '<th align="center" class="top">Кол-во ставок</th>';
					echo '<th align="center" class="top">Сумма</th>';
					echo '<th align="center" class="top">Завершен</th>';
				echo '</tr></thead>';

				while ($row = mysql_fetch_array($sql)) {
					echo '<tr align="center">';
						echo '<td align="center">'.$row["id"].'</td>';
						echo '<td align="center">'.$row["username"].'</td>';
						echo '<td align="center">'.$row["lider"].'</td>';
						echo '<td align="center">'.$row["stavka"].'</td>';
						echo '<td align="center">'.$row["kolstv"].'</td>';
						echo '<td align="center">'.number_format(($row["stavka"]*$row["kolstv"]),2,".","'").'</td>';
						echo '<td align="center">'.DATE("d.m.Yг. H:i", $row["timer_end"]).'</td>';
					echo '</tr>';
				}
				echo '</table>';
			}


			$sql = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='0' ORDER BY `kolstv` DESC LIMIT 10");
			if(mysql_num_rows($sql)>0) {
				echo '<br><table class="tables">';
				echo '<thead><tr align="center"><td align="left" colspan="7">10 самых крупных аукционов:</td></tr>';
				echo '<tr>';
					echo '<th align="center" class="top">#</th>';
					echo '<th align="center" class="top">Продавец</th>';
					echo '<th align="center" class="top">Победитель</th>';
					echo '<th align="center" class="top">Размер ставки</th>';
					echo '<th align="center" class="top">Кол-во ставок</th>';
					echo '<th align="center" class="top">Сумма</th>';
					echo '<th align="center" class="top">Завершен</th>';
				echo '</tr></thead>';

				while ($row = mysql_fetch_array($sql)) {
					echo '<tr align="center">';
						echo '<td align="center">'.$row["id"].'</td>';
						echo '<td align="center">'.$row["username"].'</td>';
						echo '<td align="center">'.$row["lider"].'</td>';
						echo '<td align="center">'.$row["stavka"].'</td>';
						echo '<td align="center">'.$row["kolstv"].'</td>';
						echo '<td align="center">'.number_format(($row["stavka"]*$row["kolstv"]),2,".","'").'</td>';
						echo '<td align="center">'.DATE("d.m.Yг. H:i", $row["timer_end"]).'</td>';
					echo '</tr>';
				}
				echo '</table>';
			}
		}

	}
include('footer.php');?>