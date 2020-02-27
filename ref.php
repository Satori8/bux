<?php
require_once('.zsecurity.php');
$pagetitle="Продать рефералов на бирже";
include('header.php');
error_reporting(E_ERROR | E_PARSE);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	$username = uc($_SESSION["userLog"]);

	if(isset($_GET["option"])) {
		if(limpiar($_GET["option"])=="stat") {

			echo '<a href="refbirj.php?page=1">Перейти на биржу рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'">Список рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'?option=stat">Статистика продаж</a><br /><br />';

			require('config.php');

			$KOL_ZP = "20";
			$kolvo = mysql_num_rows(mysql_query("SELECT id FROM tb_refview WHERE name='$username'"));
			$num_pages = ceil($kolvo/$KOL_ZP);

			if(isset($_GET["page"])) {
				if(preg_match("|^[\d]*$|",trim($_GET["page"]))) {
					$page = (int)limpiar($_GET["page"]);
					if($page>0) {$start = abs(($page - 1) * $KOL_ZP);}else{$page = "1"; $start = "0";}
				}else{
					$page = "1"; $start = "0";
				}
			}else{
				$start = "0"; $page = "1";
			}

			$tabla = mysql_query("SELECT * FROM tb_refview WHERE name='$username' ORDER BY id DESC LIMIT $start,$KOL_ZP");
			if(mysql_num_rows($tabla)>0) {

				echo '<table class="tables">';
				echo '<tr align="center">';
				echo '<th class="top">Дата / Время продажи</th>';
				echo '<th class="top">Реферал</th>';
				echo '<th class="top">Цена</th>';
				echo '<th class="top">Покупатель</th>';
				echo '</tr>';

				while ($row = mysql_fetch_array($tabla)) {
					echo '<tr align="center">';
					echo '<td>'.$row["date"].'</td>';
					echo '<td>'.$row["ref"].'</td>';
					echo '<td>'.$row["cena"].'</td>';
					echo '<td>'.$row["namep"].'</td>';
					echo '</tr>';
				}
				echo '</table>';
			}else{
				echo '<center><font color="#FF0000"><b>Вы не продали ни одного реферала!</b></font></center>';
			}

			if($num_pages>0) {
				echo '<br><b>Страницы:</b>&nbsp;';
				for($i=1;$i<=$num_pages;$i++) {
					if($i == $page)	echo ' <u><font color="red"><b>'.$i.'</b></font></u> ';
					else echo ' <a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'&amp;option=stat"><b>'.$i.'</b></a> ';
				}
			}
		}


		if(limpiar($_GET["option"])=="dell") {

			echo '<a href="refbirj.php?page=1">Перейти на биржу рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'">Список рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'?option=stat">Статистика продаж</a> <br />';

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


		if(limpiar($_GET["option"])=="addbirj") {

		echo '<a href="refbirj.php?page=1">Перейти на биржу рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'?option=stat">Статистика продаж</a> | <a href="'.$_SERVER['PHP_SELF'].'">Список рефералов</a><br /><br />';

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

				if(preg_match("|^[\d.]*$|",str_replace(",",".",trim($_POST["cena"])))) {
					$cena = htmlspecialchars(trim($_POST["cena"]));
					$cena = str_replace(",",".",$cena);
					$cena = round($cena,2);

					if($cena < 5) {
						echo '<span class="msg-error">Минимальная цена для продажи должна быть более 5 руб.!</span>';
						include('footer.php');
						exit();
					}
				}else{
					echo '<span class="msg-error">Не корректо указана цена реферала. Она должна быть больше 15руб. и разделитель дробной части - .!</span>';
					include('footer.php');
					exit();
				}


				require('config.php');
				$sql_tab = mysql_query("SELECT username,referer,visits,lastlogdate2 FROM tb_users WHERE id='$id'");
				$row_tab = mysql_fetch_array($sql_tab);
					$user_tab = $row_tab["username"];
					$referer_tab = $row_tab["referer"];
					$visits_tab = $row_tab["visits"];
					$lastlogdate_tab = $row_tab["lastlogdate2"];
					$activdate = date(time() - (5 * 24 * 3600));

				if(strtolower($referer_tab)!=strtolower($username)) {

					mysql_query("INSERT into tb_black_users (name, why, link, ip, date) VALUES('$username','Мошенничество на бирже рефералов','','$laip','".DATE("d.m.Yг. в H:i")."')") or die(mysql_error());

					echo '<span class="msg-error">Вы не можете продать не своего реферала. Ваш аккаунт заблокирован!</span>';
					include('footer.php');
					exit();
				}

				$sql_a = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `username`='$username' AND `referal`='$user_tab'");
				if(mysql_num_rows($sql_a)>0) {
					echo '<fieldset class="errorp">Ошибка! Реферал уже выставлен на аукцион рефералов!</span>';
					include('footer.php');
					exit();
				}


				$check_birj = mysql_query("SELECT id FROM tb_refbirj WHERE ref='$user_tab'");
				if(mysql_num_rows($check_birj)>0) {
					echo '<span class="msg-error">Реферал - <u>'.$user_tab.'</u> уже есть выставлен на продажу!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
					include('footer.php');
					exit();
				}

				$check_auc = mysql_query("SELECT * FROM tb_auction WHERE refname='$user_tab' and active='1'");
				if(mysql_num_rows($check_auc) > 0) {
					echo '<span class="msg-error">Реферал - <u>'.$user_tab.'</u> уже есть выставлен на Аукцион!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
					include('footer.php');
					exit();
				}

				if($visits_tab<300) {
					echo '<span class="msg-error">Продажа не возможна. Реферал - <u>'.$user_tab.'</u> просмотрел менее 300 ссылок!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
					include('footer.php');
					exit();
				}

				if($activdate>$lastlogdate_tab) {
					echo '<span class="msg-error">Продажа не возможна. Реферал - <u>'.$user_tab.'</u> является не активным последний вход был более чем 3 дней назад! Запрещено продавать не активных рефералов!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
					include('footer.php');
					exit();
				}


				mysql_query("INSERT INTO tb_refbirj (date, date2, ref, name, cena) VALUES('".DATE("d.m.Y")."','".time()."','$user_tab','$username','$cena')") or die(mysql_error());

				echo '<span class="msg-ok">Реферал - <u>'.$user_tab.'</u> выставлен на продажу!<br /><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'">&lt;&lt; Вернуться назад</a></span>';
			}else{
				echo '<script type="text/javascript">location.replace("'.$_SERVER['PHP_SELF'].'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER['PHP_SELF'].'"></noscript>';
			}
		}
	}else{

		echo '<a href="refbirj.php?page=1">Перейти на биржу рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'">Список рефералов</a> | <a href="'.$_SERVER['PHP_SELF'].'?option=stat">Статистика продаж</a><br /><br />';

		require('config.php');

		$KOL_ZP = "20";
		$kolvo = mysql_num_rows(mysql_query("SELECT id FROM tb_users WHERE referer='$username'"));
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
				if($sort==1) $s_tab = "id";
				elseif ($sort==2) $s_tab = "username";
				elseif ($sort==3) $s_tab = "visits";
				elseif ($sort==4) $s_tab = "referals";
				elseif ($sort==5) $s_tab = "joindate2";
				elseif ($sort==6) $s_tab = "lastlogdate2";
				else $s_tab = "id";
			}else{
				$s_tab = "id";
				$sort = "1";
			}
		}else{
			$s_tab = "id";
			$sort = "1";
		}

		$tabla = mysql_query("SELECT * FROM tb_users WHERE referer='$username' ORDER BY $s_tab DESC LIMIT $start,$KOL_ZP");
		if(mysql_num_rows($tabla)>0) {
			echo '<table class="tables">';
			echo '<thead><tr>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=1&amp;page='.$page.'" class="tabl">ID<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a>, <a href="'.$_SERVER['PHP_SELF'].'?sort=2&amp;page='.$page.'" class="tabl">Логин<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=3&amp;page='.$page.'" class="tabl">Статистика<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=4&amp;page='.$page.'" class="tabl">Рефералы<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top" nowrap="nowrap"><a href="'.$_SERVER['PHP_SELF'].'?sort=5&amp;page='.$page.'" class="tabl">Рег-я<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a> | <a href="'.$_SERVER['PHP_SELF'].'?sort=6&amp;page='.$page.'" class="tabl">Посл.вход<img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top" width="150px"><font color="#FF0000">Цена</font></th>';
			echo '</tr></thead>';

			while ($row = mysql_fetch_array($tabla)) {
				$sql_birj = mysql_query("SELECT * FROM tb_refbirj WHERE ref='{$row["username"]}'");
				$row_birj = mysql_fetch_array($sql_birj);

				$sql_a = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `username`='$username' AND `referal`='{$row["username"]}'");


				if($row_birj["ref"]!="") {
					$hifo='<form method="post" action="'.$_SERVER['PHP_SELF'].'?page='.$page.'&amp;option=dell" id="newform"><input type="hidden" name="id" value="'.$row_birj["id"].'">'.$row_birj["cena"].' руб.<input type="submit" class="sub-blue" value="Снять"></form>';
				}elseif(mysql_num_rows($sql_a)>0){
					$hifo='-';
				}else{
					$hifo='<form method="post" action="'.$_SERVER['PHP_SELF'].'?page='.$page.'&amp;option=addbirj" id="newform"><input type="text" name="cena" value="0.00" class="ok" style="width:40px; text-align:right;"><input type="hidden" name="id" value="'.$row["id"].'"> <input type="submit" class="kn" value="Продать"></form>';
				}

				echo '<tr align="center">';

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
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Авто-серф.:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits_a"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Письма:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits_m"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Задания:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["visits_t"],0,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;" nowrap="nowrap">Реклама:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["money_rek"],2,".","'").'</td></tr>';
					echo '</table>';
				echo '</td>';




				echo '<td>'.$row["referals"].' - '.$row["referals2"].' - '.$row["referals3"].'</td>';

				echo '<td>';
					echo DATE("d.m.Yг. H:i",$row["joindate2"]).'<br>';

					if(DATE("d.m.Y",$row["lastlogdate2"])==DATE("d.m.Y",time())) {
						echo '<span style="color:green;">Сегодня, '.DATE("H:i",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] < (time()-7*24*60*60)) {
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. H:i",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] == 0) {
						echo '<span style="color:#FF0000;">не активен</span>';
					}elseif($row["lastlogdate2"] > (time()-7*24*60*60)) {
						echo '<span style="color:green;">'.DATE("d.m.Yг. H:i",$row["lastlogdate2"]).'</span>';
					}else{
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. H:i",$row["lastlogdate2"]).'</span>';
					}
				echo '</td>';
				echo '<td>'.$hifo.'</td>';
				echo '</tr>';
			}
			echo "</table>";
		}else{
			echo '<center><font color="#FF0000"><b>У Вас нет рефералов!</b></font></center>';
		}

		if($num_pages>0) {
			echo '<br><b>Страницы:</b>&nbsp;';
			for($i=1;$i<=$num_pages;$i++) {
				if($i == $page)	echo ' <u><font color="red"><b>'.$i.'</b></font></u> ';
				else echo ' <a href="'.$_SERVER['PHP_SELF'].'?sort='.$sort.'&amp;page='.$i.'"><b>'.$i.'</b></a> ';
			}
		}

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_birj_comis_proc'");
		$ref_birj_comis_proc = number_format(mysql_result($sql,0,0), 0, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_birj_comis_min'");
		$ref_birj_comis_min = number_format(mysql_result($sql,0,0), 2, ".", "");

		echo '<br /><br /><font color="#FF0000">*</font> - При продаже реферала на бирже будет удержана комиссия системы - <b>'.$ref_birj_comis_proc.'</b>%, но не менее <b>'.$ref_birj_comis_min.'</b> руб.<br />';
	}
}


include('footer.php');?>