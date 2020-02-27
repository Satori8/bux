<?php
require_once('.zsecurity.php');
$pagetitle="Вопросы (Оплачиваемые ответы)";
include('header.php');
?>

<script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function setChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script>

<?php
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$username = uc($_SESSION["userLog"]);
}

$sql_b = mysql_query("SELECT * FROM `tb_ban_users` WHERE `name`='$username' ORDER BY `id` DESC");
		if(mysql_num_rows($sql_b)>0) {
			$row_b = mysql_fetch_array($sql_b);
			$prichina = $row_b["why"];
			$kogda = $row_b["date"];

			echo '<span class="msg-error">Ваш аккаунт заблокирован! Некоторые разделы сайта для вас не доступны!<br><u>Причина блокировки</u>: '.$row_b["why"].'<br><u>Дата блокировки</u>: '.$row_b["date"].'</span>';
			
			include('footer.php');
			exit();
		}

$laip = getRealIP();
$form_disabled = false;
if(isset($_GET["op"]) && isset($_GET["id"])) {
	require('config.php');

	echo '<script type="text/javascript" src="scripts/showhide3.js"></script>';

	$op = isset($_GET["op"]) ? limpiar($_GET["op"]) : false;
	$id = isset($_GET["id"]) ? intval(limpiar($_GET["id"])) : 0;

	function limpiarez($mensaje){
		$mensaje = htmlspecialchars(trim($mensaje));
		$mensaje = str_replace("'"," ",$mensaje);
		$mensaje = str_replace(";"," ",$mensaje);
		$mensaje = str_replace("$"," ",$mensaje);
		$mensaje = str_replace("<"," ",$mensaje);
		$mensaje = str_replace(">"," ",$mensaje);
		$mensaje = str_replace("&amp amp ","&amp;",$mensaje);
		return $mensaje;
	}

	if($op=="view" && $id>0) {

		$sql = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `id`='$id' AND `status`='1' AND `totals`>0");
		if(mysql_num_rows($sql) > 0) {
			$row = mysql_fetch_assoc($sql);

			$id = $row["id"];
			$plan = $row["plan"];
			$totals = $row["totals"];
			$url = $row["url"];
			$description = $row["description"];
			$com_all = $row["comments"];
			$rz = $row["rz"];
			$var1 = $row["var1"];
			$var2 = $row["var2"];
			$var3 = $row["var3"];
			$var4 = $row["var4"];
			$var5 = $row["var5"];
			$var_ok = $row["var_ok"];
			$moneys = $row["money"];

			echo '<span id="adv-title-bl" class="adv-title-open_1" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">Внимание!</span>';
		        echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                        echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="center"> ';
			echo 'Администрация&nbsp <b>'.ucfirst($_SERVER["HTTP_HOST"]).'</b> не несет ответственности за возможный ущерб, нанесенный Вам или Вашему компьютеру, в результате выполнения задания. Мы настоятельно рекомендуем Вам использовать лицензионные копии антивирусных продуктов. Пожалуйста, если Вы обнаружили вирус в каком-нибудь из вопросов, сообщите службе технической поддержки. Если информация подтвердится, мы немедленно удалим это задание из списка.<br />';
			echo '</div>';
                        echo '</div>';
                       

			$sql_p = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_pay'");
			$pay_users = mysql_result($sql_p,0,0);
                        
			echo '<table class="tables">';
                        echo '<tbody>';
	                echo '<tr>';
		        echo '<td align="left"><b>№'.$id.'</b></b></td>';
                        echo '</td>';
	                echo '</tr>';
			echo "<tr><td>За правильный ответ на Ваш счет будет начислено: <b style=\"color:#1F7D1F;\">".round($pay_users,3)." руб.</b></td></tr>";
			echo "<tr><td><b>Вопрос:</b><br>$description<br></td></tr>";
			//echo "<tr><td><a href='.$url.' target=\"newwin\"><input type="submit" value="Ссылка на сайт" class="submit"></b></a> с правильным ответом! (откроется на новой странице)</td></tr>";
                        echo '<td align="left"><a href='.$url.' target=\"newwin\"><input type="submit" value="Ссылка на сайт" class="submit"></a> с правильным ответом! (откроется на новой странице)</td>';
                       echo '</tr>';
  echo '</tbody>';
echo '</table>';

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
				if(isset($_POST["answer"])) {
					$sql_chek = mysql_query("SELECT `id` FROM `tb_questions_users` WHERE `username`='$username' AND `type`='load' AND `ident`='$id'");
					if(mysql_num_rows($sql_chek) > 0) {
						echo '<fieldset class="errorp">Ошибка! Вы уже отвечали на этот вопрос!</fieldset><br>';
					}elseif($form_disabled!="disabled"){
						if(preg_match("|^[\d]*$|",trim($_POST["answer"]))) {
							$answer = isset($_POST["answer"]) ? intval($_POST["answer"]) : false;

							if($answer != $var_ok) {
								mysql_query("UPDATE `tb_users` SET `money`=`money`-'$pay_users' WHERE `username`='$username'") or die(mysql_error());
								mysql_query("INSERT INTO `tb_questions_users` (`username`, `type`, `ident`, `date`, `var`, `status`, `pay`) VALUES('$username', 'load', '$id', '".time()."', '$answer', 'bad', '0.00')") or die(mysql_error());

								echo '<fieldset class="errorp">Ошибка! Вы указали не правильный ответ, с Вашего счета снято '.$pay_users.' руб.!</fieldset><br>';
							}else{
								mysql_query("UPDATE `tb_ads_questions` SET `totals`=`totals`-1 WHERE `id`='$id'") or die(mysql_error());
								mysql_query("INSERT INTO `tb_questions_users` (`username`, `type`, `ident`, `date`, `var`, `status`, `pay`) VALUES('$username', 'load', '$id', '".time()."', '$answer', 'good', '$pay_users')") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `visits_qst`=`visits_qst`+'1', `money`=`money`+'$pay_users' WHERE `username`='$username'") or die(mysql_error());

								### START СБОР СТАТИСТИКИ ####
								$DAY_S = strtolower(DATE("D"));
								stats_users($username, $DAY_S, 'quest');
								### END СБОР СТАТИСТИКИ ######

								echo '<fieldset class="okp">Ответ принят! На Ваш счет зачислено <span style="color:#FF0000;">'.$pay_users.' руб.</span>!</fieldset><br>';
							}
						}
					}else{
						echo '<fieldset class="errorp">Ошибка! По условиям геотаргетинга Оплата за скачивание не предусмотрена!</fieldset><br>';
					}
				}else{
                                      echo '<table class="tables">';
        echo '<tbody>';
	echo '<tr>';
					echo "<tr><td><b>Варианты ответов</b> (Выберите правильный)<b>:</b></td></tr>";
					echo '<form action="'.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&amp;id='.intval(limpiar($_GET["id"])).'" method="post">';
					echo "<tr><td><input type=\"radio\" name=\"answer\" value=\"1\" /> $var1</td></tr>";
					echo "<tr><td><input type=\"radio\" name=\"answer\" value=\"2\" /> $var2</td></tr>";
					echo "<tr><td><input type=\"radio\" name=\"answer\" value=\"3\" /> $var3</td></tr>";
					echo "<tr><td><input type=\"radio\" name=\"answer\" value=\"4\" /> $var4</td></tr>";
					echo "<tr><td><input type=\"radio\" name=\"answer\" value=\"5\" /> $var5</td></tr>";
					echo "<tr><td><input type=\"submit\" value=\"Ответить\" class=\"sub-blue160\" $form_disabled></td></tr>";
					echo "</form>";
				}
			}else{
				echo '<fieldset class="errorp">Для получения оплаты за выполнение задания необходимо авторизоваться!</fieldset><br>';
			}
			echo '<tr>';
		echo '<td align="left">Заказано/Осталось: '.$plan.'/'.$totals.'</td>';
                 echo '</td>';
	echo '</tr>';
echo '</tbody>';
echo '</table>';
 
			echo '<br><a href="javascript:open(\'claims\');" style="border-right:1px solid #FFFFFF; border-bottom:1px solid #FFFFFF; color:#FFFFFF; padding:1px; margin:1px; background:#339933;"><b>Подать жалобу &gt;&gt;</b></a><br><br>';
			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
				echo '<form id="claims" style="display:none;" action="'.$_SERVER['PHP_SELF'].'?op=claims&amp;id='.intval(limpiar($_GET["id"])).'" method="POST">';
				echo '<b>Укажите причину Вашей претензии:</b><br>';
				echo '<textarea style="width:50%;" rows="3" input name="claims_text" maxlength="100" autocomplete="off" value=""></textarea><br>';
				echo '<input type="submit" value="Отправить"><br><br><hr align="left" style="width:50%;"><br>';
				echo "</form>";
			}else{
				echo '<fieldset id="claims" style="display:none;" class="errorp">Для того чтобы подать жалобу необходимо авторизоваться и выполнить задание!</fieldset><br>';
			}

			echo '<br><a href="javascript:open(\'comments\');" style="border-right:1px solid #FFFFFF; border-bottom:1px solid #FFFFFF; color:#FFFFFF; padding:2px; margin:2px; background:#339933;"><b>Добавить коментарий &gt;&gt;</b></a><br><br>';
			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
				echo '<form id="comments" style="display:none;" action="'.$_SERVER['PHP_SELF'].'?op=comments&amp;id='.intval(limpiar($_GET["id"])).'" method="POST">';
				echo '<b>Ваш комментарий:</b><br>';
				echo '<textarea style="width:50%;" rows="3" input name="comments_text" maxlength="100" autocomplete="off" value=""></textarea><br>';
				echo '<b>Ваша оценка:</b>';
				echo '<input type="radio" name="rz" value="1">1&nbsp;&nbsp;';
				echo '<input type="radio" name="rz" value="2">2&nbsp;&nbsp;';
				echo '<input type="radio" name="rz" value="3">3&nbsp;&nbsp;';
				echo '<input type="radio" name="rz" value="4">4&nbsp;&nbsp;';
				echo '<input type="radio" name="rz" value="5" checked>5<br>';
				echo '<input type="submit" value="Добавить"><br>';
				echo "</form>";
			}else{
				echo '<fieldset id="comments" style="display:none;" class="errorp">Для того чтобы оставить коментарий необходимо авторизоваться и выполнить задание!</fieldset><br>';
			}

			$tabla = mysql_query("SELECT * FROM `tb_questions_users` WHERE `type`='comments' AND `ident`='$id' ORDER BY `id` DESC LIMIT 20");
			if(mysql_num_rows($tabla)>0) {
				echo '<table border="0" cellspacing="4" cellpadding="4" style="border-top:1px solid #000; border-bottom:1px solid #000; border-collapse: collapse; background: #DBDBDB; padding:20px;">';
				echo '<tr><td colspan="2"><b>Последние комментарии:</b><br>(всего комментариев: '.$com_all.', <a href="#">показать все</a>)</td></tr>';
				echo '<tr><td colspan="2">Рейтинг задания: <b>'.$rz.'</b></td></tr>';

				while ($row_t = mysql_fetch_array($tabla)) {
					echo '<tr><td><i><b>'.$row_t["username"].'</b> (оценка '.$row_t["rz"].')</i></b><br><i>'.DATE("d.m.Y H:i:s",$row_t["date"]).'</i></td><td><textarea style="width:450px; margin-bottom:5px;" rows="2" value="" readonly>'.$row_t["text"].'</textarea></td></tr>';
				}
				echo '</table>';
			}
		}else{
			echo '<fieldset class="errorp">Ошибка! Такого задания нет!</fieldset><br>';
			include('footer.php');
			exit();
		}
	}

	if($op=="claims" && $id>0) {
		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
			if(isset($_POST["claims_text"]) && count($_POST)>0) {
				$claims_text = (isset($_POST["claims_text"])) ? limpiarez($_POST["claims_text"]) : false;

				if(empty($claims_text) OR strlen($claims_text)<20) {
					echo '<fieldset class="errorp">Ошибка! Не указана причина притензии, либо указана не корректно и содержит менее 20 символов!</fieldset><br>';
				}else{
					$sql_chek = mysql_query("SELECT `id` FROM `tb_questions_users` WHERE `username`='$username' AND `type`='load' AND `ident`='$id'");
					if(mysql_num_rows($sql_chek) > 0) {
						$sql_chek = mysql_query("SELECT `id` FROM `tb_questions_users` WHERE `username`='$username' AND `type`='claims' AND `ident`='$id'");
						if(mysql_num_rows($sql_chek) > 0) {
							echo '<fieldset class="errorp">Ошибка! Вы уже подавали жалобу на это задание!</fieldset><br>';
						}else{
							mysql_query("INSERT INTO `tb_questions_users` (`username`, `type`, `ident`, `text`, `date`, `var`, `status`, `pay`) VALUES('$username', 'claims', '$id', '$claims_text', '".time()."', '0', 'claims', '0.000')") or die(mysql_error());
							mysql_query("UPDATE `tb_ads_questions` SET `claims`=`claims`+1 WHERE `id`='$id'") or die(mysql_error());

							echo '<fieldset class="okp">Жалоба успешно отправлена! Если информация подтвердится задание будет удалено!</fieldset><br>';
						}
					}else{
						echo '<fieldset class="errorp">Ошибка! Для того чтобы подать жалобу необходимо сначала выполнить задание!</fieldset><br>';
					}
				}
			}else{
				echo '<script type="text/javascript">location.replace("/'.$_SERVER['PHP_SELF'].'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/'.$_SERVER['PHP_SELF'].'"></noscript>';
			}
		}else{
			echo '<fieldset class="errorp">Для того чтобы подать жалобу необходимо авторизоваться и выполнить задание!</fieldset><br>';
		}
	}


	if($op=="comments" && $id>0) {
		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
			if(isset($_POST["comments_text"]) && count($_POST)>0) {
				$comments_text = (isset($_POST["comments_text"])) ? limpiarez($_POST["comments_text"]) : false;
				$rz = isset($_POST["rz"]) ? intval(limpiarez($_POST["rz"])) : "0";
				if($rz>5) $rz="5";
				elseif($rz<1) $rz="1";

				if(empty($comments_text) OR strlen($comments_text)<2) {
					echo '<fieldset class="errorp">Ошибка! Не указан текст коментария, либо указан не корректно и содержит менее 2 символов!</fieldset><br>';
				}else{
					$sql_chek = mysql_query("SELECT `id` FROM `tb_questions_users` WHERE `username`='$username' AND `type`='load' AND `ident`='$id'");
					if(mysql_num_rows($sql_chek) > 0) {
						$sql_chek = mysql_query("SELECT `id` FROM `tb_questions_users` WHERE `username`='$username' AND `type`='comments' AND `ident`='$id'");
						if(mysql_num_rows($sql_chek) > 0) {
							echo '<fieldset class="errorp">Ошибка! Вы уже оставляли коментарий на это задание!</fieldset><br>';
						}else{
							$sql_rz = mysql_query("SELECT `comments`, `rz` FROM `tb_ads_questions` WHERE `id`='$id'");
							$row_rz = mysql_fetch_assoc($sql_rz);
								$com_tab = $row_rz["comments"];
								$rz_tab = $row_rz["rz"];

								if(floatval($rz_tab)==0) $new_rz = $rz;
								else $new_rz = ($rz_tab + $rz)/2;


							mysql_query("INSERT INTO `tb_questions_users` (`username`, `type`, `ident`, `rz`, `text`, `date`, `var`, `status`, `pay`) VALUES('$username', 'comments', '$id', '$rz', '$comments_text', '".time()."', '0', 'comments', '0.000')") or die(mysql_error());
							mysql_query("UPDATE `tb_ads_questions` SET `comments`=`comments`+1, `rz`='$new_rz' WHERE `id`='$id'") or die(mysql_error());

							echo '<fieldset class="okp">Коментарий успешно добавлен!</fieldset><br>';
						}
					}else{
						echo '<fieldset class="errorp">Ошибка! Для того чтобы оставить коментарий необходимо сначала выполнить задание!</fieldset><br>';
					}
				}
			}else{
				echo '<script type="text/javascript">location.replace("'.$_SERVER['PHP_SELF'].'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER['PHP_SELF'].'"></noscript>';
			}
		}else{
			echo '<fieldset class="errorp">Для того чтобы оставить коментарий необходимо авторизоваться и выполнить задание!</fieldset><br>';
		}
	}
}else{
	require('config.php');

	$KOL_ZP = "20";
	$kolvo = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_questions` WHERE `status`='1' AND `claims`<='10' "));
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
			elseif ($sort==2) $s_tab = "rz";
			elseif ($sort==3) $s_tab = "totals";
			else $s_tab = "id";
		}else{
			$s_tab = "id";
			$sort = "1";
		}
	}else{
		$s_tab = "id";
		$sort = "1";
	}

	function format_table($i, $v, $p, $t, $rz, $d){
		echo '<tr>';
		echo '<td align="center">'.$i.'</td>';
		if ($t<1) mysql_query("UPDATE `tb_ads_questions` SET `status`='3' WHERE `id`='$i'") or die(mysql_error());
		if(!$v)	{
			echo '<td><a href="'.$_SERVER['PHP_SELF'].'?op=view&amp;id='.$i.'">'.limitatexto($d,50).' ... &gt;&gt;</a></td>';
		}else{
			echo '<td><del><a href="'.$_SERVER['PHP_SELF'].'?op=view&amp;id='.$i.'">'.limitatexto($d,50).' ... &gt;&gt;</a></del></td>';
		}
		echo '<td align="center">'.$rz.'</td>';
		echo '<td align="center">'.$t.'</td>';
	}

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$sql = mysql_query("SELECT `ident` FROM `tb_questions_users` WHERE `username`='$username' AND `type`='load'");
		if(mysql_num_rows($sql) > 0) {
			while($row = mysql_fetch_assoc($sql)) $visited[($row['ident'])] = true;
		}else{
			$visited = array();
		}
	}

	echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">Платные вопросы</span>';
		        echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                        echo '<div style="color:#0000ff; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="center"> ';
		echo 'На этой странице отображены вопросы, за <u>правильные ответы</u> на которые, Вы <u>получите деньги</u> на свой счет. Для ответа на вопрос отведена только одна попытка. За <u>неверный ответ</u> с Вашего счета будет <u>списана сумма</u>, равная стоимости правильного ответа. Для ответа на вопрос отведена только одна попытка.<br />';
	echo '</div>';
        echo '</div>';
	echo '<br><br>';

        echo '<table class="tables" border="0" align="center" style="width:100%; margin:0 auto;" id="newform">';
        echo '<tr align="center">';
        echo '<td valign="top" style="border-left:none; border-right:none;">';
	echo '<a href="/advertise.php?ads=quest"><input type="submit" value="Разместить свой вопрос" class="sub-blue160"></a>';
        echo '</table>';
        echo '<br><br>';

	$tabla = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `status`='1' AND `claims`<='10' ORDER BY $s_tab DESC LIMIT $start,$KOL_ZP");
	if(mysql_num_rows($tabla)>0) {
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=1&amp;page='.$page.'" class="tabl"><b>№</b><img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top">Вопрос</th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=2&amp;page='.$page.'" class="tabl"><b>Рейтинг</b><img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
			echo '<th class="top"><a href="'.$_SERVER['PHP_SELF'].'?sort=3&amp;page='.$page.'" class="tabl"><b>Осталось</b><img src="img/s_d.gif" width="7" align="middle" border="0" alt="" /></a></th>';
		echo '</tr></thead>';

		while($links_row = mysql_fetch_assoc($tabla)) {
			if(isset($visited[($links_row['id'])])) $is_visited = true;
			else $is_visited = false;

			format_table(
				$links_row["id"],
				$is_visited,
				$links_row["plan"],
				$links_row["totals"],
				$links_row["rz"],
				$links_row["description"]
			);
		}
		echo "</table>";
	}else{
		echo '<center><b style="color:#FF0000;">На данный момент нет оплачиваемых вопросов!</b></center>';
	}


	if($num_pages>0) {
		echo '<br><b>Страницы:</b>&nbsp;';
		for($i=1;$i<=$num_pages;$i++) {
			if($i == $page)	echo ' <u><font color="red"><b>'.$i.'</b></font></u> ';
			else echo ' <a href="'.$_SERVER['PHP_SELF'].'?sort='.$sort.'&amp;page='.$i.'"><b>'.$i.'</b></a> ';
		}
	}
}

include('footer.php');?>