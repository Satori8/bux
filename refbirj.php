<?php
require_once('.zsecurity.php');
require_once('./merchant/func_mysql.php');
$pagetitle="Биржа рефералов";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{

	$username = uc($_SESSION["userLog"]);

if(isset($_GET["option"])) {

	if(limpiar($_GET["option"])=="payok") {

		if(isset($_POST["id"])) {

			if(preg_match("|^[\d]*$|",trim($_POST["id"]))) {
				$id = (int)htmlspecialchars($_POST["id"]);
				if($id < 1) {
					echo '<span class="msg-error">Такого реферала нет!</span>';
					include('footer.php');
					exit();
				}
			}else{
				echo '<span class="msg-error">Такого реферала нет!</span>';
				include('footer.php');
				exit();
			}

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_birj_comis_proc'");
			$ref_birj_comis_proc = number_format(mysql_result($sql,0,0), 0, ".", "");

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_birj_comis_min'");
			$ref_birj_comis_min = number_format(mysql_result($sql,0,0), 2, ".", "");


			$sql_birj = mysql_query("SELECT * FROM tb_refbirj WHERE id='$id'");
			$row_birj = mysql_fetch_array($sql_birj);
				$referal = $row_birj["ref"];
				$cenarefa = $row_birj["cena"];
				$prodavec = $row_birj["name"];

			$sql_user = mysql_query("SELECT `referer`, `referer2`, `money_rb` FROM `tb_users` WHERE `username`='$username'");
			$row_user = mysql_fetch_array($sql_user);
				$my_referer = $row_user["referer"];
				$my_referer2 = $row_user["referer2"];
				$balance = $row_user["money_rb"];

			if ($my_referer=="$referal") {
				echo '<span class="msg-error">Извините но этого реферала нельзя купить. Так как вы являетесь его реферером!</span>';
				include('footer.php');
				exit();
			}

			if($balance<$cenarefa) {
				echo '<span class="msg-error">На вашем счету недостаточно средств для покупки этого реферала! <a href="money_add.php">Пополнить баланс &gt;&gt;</a></span>';
				include('footer.php');
				exit();
			}

			if($referal==$username) {
				echo '<span class="msg-error">Вы не можете купить данного реферала!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
				include('footer.php');
				exit();
			}

			if($id==NULL|$username==NULL|$cenarefa==NULL|$prodavec==NULL) {
				echo '<span class="msg-error">Невозможно купить реферала!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
				include('footer.php');
				exit();
			}

			$comisia = ($cenarefa * $ref_birj_comis_proc/100);
			if($comisia < $ref_birj_comis_min) {
				$comisia = $ref_birj_comis_min;
			}else{
				$comisia = number_format($comisia, 2, ".", "");
			}
			$summa = number_format(($cenarefa - $comisia), 2, ".", "");

			//Операции с покупателем
			mysql_query("UPDATE `tb_users` SET `referer`='$username', `referer2`='$my_referer', `referer3`='$my_referer2', `statusref`='birja' WHERE `username`='$referal'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `referer2`='$username', `referer3`='$my_referer' WHERE `referer`='$referal'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `referer3`='$username' WHERE `referer2`='$referal'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `referals`=`referals`+1, `money_rb`=`money_rb`-'$cenarefa', `ref_birj_k`=`ref_birj_k`+'1' WHERE `username`='$username'") or die(mysql_error());

			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('$username','".DATE("d.m.Y H:i",time())."','".time()."','$cenarefa','Покупка реферала $referal на бирже','Списано','prihod')") or die(mysql_error());
			
			stat_pay('ref_birj', $comisia);
			invest_stat($comisia, 5);

			//Операции с продавцом
			mysql_query("UPDATE `tb_users` SET `referals`=`referals`-1, `money`=`money`+'$summa', `ref_birj_p`=`ref_birj_p`+'1' WHERE `username`='$prodavec'") or die(mysql_error());

			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('$prodavec','".DATE("d.m.Y H:i",time())."','".time()."','$summa','Зачисление средств от продажи реферала $referal на бирже','Зачислено','prihod')") or die(mysql_error());

			// Удаляем с продажи реферала
			mysql_query("DELETE FROM `tb_refbirj` WHERE `id`='$id'") or die(mysql_error());

			// Записываем покупку реферала в базу
			mysql_query("INSERT INTO `tb_refview` (`date`, `time`, `ref`, `name`, `namep`, `cena`) 
			VALUES('".DATE("d.m.Y H:i")."','".time()."','$referal','$prodavec','$username','$cenarefa')") or die(mysql_error());

			echo '<span class="msg-ok">Реферал - <u>'.$referal.'</u> успешно приобретен!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
		}else{
			echo '<script type="text/javascript">location.replace("'.$_SERVER['PHP_SELF'].'");</script>';
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER['PHP_SELF'].'"></noscript>';
		}
	}

		if(limpiar($_GET["option"])=="dell") {

			if(isset($_POST["id"])) {

				if(preg_match("|^[\d]*$|",trim($_POST["id"]))) {
					$id = (int)htmlspecialchars($_POST["id"]);
					if($id < 1) {
						echo '<span class="msg-error">Такого реферала нет!</span>';
						include('footer.php');
						exit();
					}
				}else{
					echo '<span class="msg-error">Такого реферала нет!</span>';
					include('footer.php');
					exit();
				}

				require('config.php');
				$sql_dl = mysql_query("SELECT * FROM tb_refbirj WHERE id='$id'");
				$row_dl = mysql_fetch_array($sql_dl);
				$prodavec = $row_dl["name"];

				if($prodavec!=$username) {
					echo '<span class="msg-error">Вы не можете снять с продажи данного реферала!<br /><a href="'.$_SERVER['PHP_SELF'].'">&lt;&lt; Вернуться назад</a></span>';
					include('footer.php');
					exit();
				}else{
					mysql_query("DELETE FROM tb_refbirj WHERE id='$id'") or die(mysql_error());
					echo '<span class="msg-ok">Реферал - <u>'.$row_dl["ref"].'</u> снят с продажи!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
				}

			}
		}
}else{

		echo 'Уважаемые пользователи.<br />';
		echo 'На бирже рефералов проекта '.strtoupper($_SERVER["HTTP_HOST"]).' Вы можете покупать рефералов у других пользователей, а также получать дополнительный доход, продавая своих рефералов.<br />';
		echo '<a href="ref.php?page=1">Выставить рефералов на продажу &gt;&gt;</a><br><br>';
		echo '<u>Все операции на бирже рефералов проходят между пользователями проекта.</u><br /><br />';

		require('config.php');

		$KOL_ZP = "20";
		$kolvo = mysql_num_rows(mysql_query("SELECT id FROM tb_refbirj"));
		$num_pages = ceil($kolvo/$KOL_ZP);

		if(isset($_GET["page"])) {
			if(preg_match("|^[\d]*$|",$_GET['page'])) {
				$page = (int)limpiar($_GET["page"]);
				if($page>0) {$start = abs(($page - 1) * $KOL_ZP);}else{$page = "1"; $start = "0";}
			}else{
				$page = "1"; $start = "0";
			}
		}else{
			$start = "0"; $page = "1";
		}

		if(isset($_GET["sort"])) {
			if(preg_match("|^[\d]*$|",$_GET["sort"])) {
				$sort = (int)limpiar($_GET["sort"]);
				if($sort==1) $s_tab = "tb_refbirj.id";
				elseif ($sort==2) $s_tab = "tb_refbirj.ref";
				elseif ($sort==3) $s_tab = "tb_users.visits";
				elseif ($sort==4) $s_tab = "tb_users.referals";
				elseif ($sort==5) $s_tab = "tb_users.joindate2";
				elseif ($sort==6) $s_tab = "tb_users.lastlogdate2";
				elseif ($sort==7) $s_tab = "tb_refbirj.name";
				elseif ($sort==8) $s_tab = "tb_refbirj.cena";
				else $s_tab = "id";
			}else{
				$s_tab = "tb_refbirj.id";
				$sort = "1";
			}
		}else{
			$s_tab = "tb_refbirj.id";
			$sort = "1";
		}

		$username = uc($_SESSION["userLog"]);

		$tabla = mysql_query("SELECT tb_refbirj.id as rid, tb_refbirj.ref, tb_refbirj.name, tb_refbirj.cena, tb_users.*  FROM tb_refbirj,tb_users WHERE tb_users.username=tb_refbirj.ref ORDER BY $s_tab DESC LIMIT $start,$KOL_ZP");

		if(mysql_num_rows($tabla)>0) {
			echo '<table class="tables">';
			echo '<thead><tr>';
			//echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=1&amp;page='.$page.'" class="tabl">№<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=2&amp;page='.$page.'" class="tabl">Логин<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=3&amp;page='.$page.'" class="tabl">Статистика<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top" nowrap="nowrap"><a href="'.$_SERVER['PHP_SELF'].'?sort=4&amp;page='.$page.'" class="tabl">Рефералы<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top" nowrap="nowrap"><a href="'.$_SERVER['PHP_SELF'].'?sort=5&amp;page='.$page.'" class="tabl">Рег-я<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a> | <a href="'.$_SERVER['PHP_SELF'].'?sort=6&amp;page='.$page.'" class="tabl">Посл.вход<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=7&amp;page='.$page.'" class="tabl">Продавец<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=8&amp;page='.$page.'" class="tabl">Цена<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '</tr></thead>';

			while ($row = mysql_fetch_array($tabla)) {

				if($username==$row["name"]) {
					$hifo='<form method="post" action="'.$_SERVER['PHP_SELF'].'?page='.$page.'&option=dell"><input type="hidden" name="id" value="'.$row["rid"].'"><input type="submit" value="Снять" class="sub-blue"></form>';
				}else{
					$hifo='<form method="post" action="'.$_SERVER['PHP_SELF'].'?page='.$page.'&option=payok"><input type="hidden" name="id" value="'.$row["rid"].'"><input type="submit" value="Купить" class="sub-blue"></form>';
				}

				echo '<tr align="center">';
				//echo '<td>'.$row["rid"].'</td>';

				$wall_com = $row["wall_com_p"] - $row["wall_com_o"];

				$info_user = '<img src="img/reiting.png" border="0" alt="" align="middle" title="Рейтинг" style="margin:0; padding:0;" />&nbsp;<span style="color:blue;">'.round($row["reiting"], 2).'</span>,&nbsp;';
				$info_user.= '<a href="/wall?uid='.$row["id"].'" target="_blank"><img src="img/wall20.png" title="Стена пользователя '.$row["username"].'" width="18" border="0" align="absmiddle" />';

				if($wall_com>0) {
					$info_user.= '&nbsp;<b style="color:#008000;">+ '.$wall_com.'</b></a>';
				}elseif($wall_com<0) {
					$info_user.= '&nbsp;<b style="color:#FF0000;">'.$wall_com.'</b></a>';
				}else{
					$info_user.= '&nbsp;<b style="color:#000000;">0</b></a>';
				}

				echo '<td>';
					echo 'ID:<b>'.$row["id"].'<br>'.$row["username"].'</b><br>'.$info_user.'';
					if($row["http_ref"]!=false) {echo '<br>Пришел с <a href="http://'.$row["http_ref"].'/" target="_blank">'.$row["http_ref"].'</a>';}




				echo '</td>';

				echo '<td>';
					echo '<table align="center" border="0" width="100%" class="notables" style="border:0px; margin:0px; padding:0px;"><tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Серфинг:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Авто-серф</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits_a"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Письма:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits_m"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Задания:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits_t"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;" nowrap="nowrap">Реклама:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["money_rek"],2,".","'").'</td></tr>';
					echo '</table>';

					$token_ajax = md5($username.$row["username"].$row["id"]);
					echo '<span onclick="ShowHideStats(\''.$row["id"].'\', \''.$token_ajax.'\')" style="cursor: pointer;"><img src="/img/icon-stats.png" border="0" alt="" title="Посмотреть расширенную статистику реферала '.$row["username"].'" /></span>';

				echo '</td>';

				echo '<td>'.$row["referals"].'-'.$row["referals2"].'-'.$row["referals3"].'</td>';

				echo '<td>';
					echo DATE("d.m.Yг. H:i",$row["joindate2"]).'<br>';

					if(DATE("d.m.Y",$row["lastlogdate2"])==DATE("d.m.Y",time())) {
						echo '<span style="color:green;">Сегодня, '.DATE("",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] < (time()-7*24*60*60)) {
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг.",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] == 0) {
						echo '<span style="color:#FF0000;">не активен</span>';
					}elseif($row["lastlogdate2"] > (time()-7*24*60*60)) {
						echo '<span style="color:green;">'.DATE("d.m.Yг. H:i",$row["lastlogdate2"]).'</span>';
					}else{
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. H:i",$row["lastlogdate2"]).'</span>';
					}
				echo '</td>';

				echo '<td>'.$row["name"].'</td>';
				echo '<td>'.round($row["cena"],2).'&nbsp;руб.&nbsp;&nbsp;'.$hifo.'</td>';

				echo '</tr>';

				echo '<tr align="center"><td colspan="6" id="usersstat'.$row["id"].'" style="display: none;"></td></tr>';
			}

			echo "</table>";
		}else{
			echo '<center><font color="#FF0000"><b>В продаже нет рефералов!</b></font></center>';
		}


		if($num_pages>0) {
			echo '<br><b>Страницы:</b>&nbsp;';
			for($i=1;$i<=$num_pages;$i++) {
				if($i == $page)	echo ' <u><font color="red"><b>'.$i.'</b></font></u> ';
				else echo ' <a href="'.$_SERVER['PHP_SELF'].'?sort='.$sort.'&amp;page='.$i.'"><b>'.$i.'</b></a> ';
			}
		}
	}
}


include('footer.php');?>