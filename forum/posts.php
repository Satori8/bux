<?php

if(isset($_GET["del"]) && $id_p!=false) {
	include("del_post.php");

}elseif(count($_POST)>0 && isset($_POST["addcomm"])) {
	include_once("add_post.php");

}elseif(count($_POST)>0 && isset($_POST["add_ban"])) {
	include("add_ban.php");

}elseif(count($_POST)>0 && isset($_POST["to_prazdel"])) {
	include("re_theme.php");

}


$sql_t = mysql_query("SELECT * FROM `tb_forum_t` WHERE `id`='$id_t'");
if(mysql_numrows($sql_t)>0) {
	$row_t = mysql_fetch_array($sql_t);
	$title_t = $row_t["title"];
	$id_t = $row_t["id"];
	$id_r = $row_t["ident_r"];
	$id_pr = $row_t["ident_pr"];


	$perpage = 15;
	$sql_p = mysql_query("SELECT `id` FROM `tb_forum_p` WHERE `ident_pr`='$id_pr' AND `ident_t`='$id_t'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["p"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["p"]))) ? intval(trim($_GET["p"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;


	$sql_pr = mysql_query("SELECT `title` FROM `tb_forum_pr` WHERE `id`='$id_pr'");
	if(mysql_num_rows($sql_pr)>0) {
		$row_pr = mysql_fetch_row($sql_pr);
		$title_pr = $row_pr["0"];


		echo '<div class="forumpath"><a href="'.$_SERVER["PHP_SELF"].'">Форумы</a><a href="'.$_SERVER["PHP_SELF"].'?pr='.$id_pr.'">'.$title_pr.'</a><a href="'.$_SERVER["PHP_SELF"].'?th='.$row_t["id"].'">'.$row_t["title"].'</a></div>';

		if($del_theme==1 | $close_theme==1 | $open_theme==1 | $re_theme==1) {
			echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">';
			echo '<tr>';
				if($re_theme==1) {
					echo '<td align="left">';
						echo '<form method="POST" action="">';
							echo '<select name="to_prazdel" style="float: left;">';
								$sql_pr_to = mysql_query("SELECT `id`,`title` FROM `tb_forum_pr` ORDER BY `id` ASC");
								if(mysql_num_rows($sql_pr_to)>0) {
									while ($row_pr_to = mysql_fetch_row($sql_pr_to)) {
										echo '<option value="'.$row_pr_to["0"].'" '.("$id_pr" == $row_pr_to["0"] ? 'selected="selected"' : false).'>'.$row_pr_to["1"].'</option>';
									}
								}
							echo '</select>';
							echo '<input type="submit" value="Переместить" class="sub-blue" style="float: left; margin:2px 0px 0px 2px; padding-bottom:4px;">';
						echo '</form>';
					echo '</td>';
				}
				echo '</td>';
				echo '<td align="right">';
					if($del_theme==1) echo '<span class="sub-red" onClick="del_theme('.$id_t.'); return false;">Удалить тему</span>';
					if($row_t["status"]==0 && $close_theme==1) {
						echo '<span class="sub-blue" onClick="close_theme('.$id_t.'); return false;">Закрыть тему</span>';
					}elseif($row_t["status"]==1 && $open_theme==1) {
						echo '<span class="sub-green" onClick="open_theme('.$id_t.'); return false;">Открыть тему</span>';
					}
				echo '</td>';
			echo '</tr>';
			echo '</table>';
		}


		universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 9, '&p=', "?th=$id_t");

		echo '<table class="forum" align="center" border="0">';
		echo '<thead><tr><th align="left" colspan="2" nowrap="nowrap">&nbsp;<b>Тема:</b>&nbsp;&nbsp;'.$row_t["title"].'</th></tr></thead>';

		echo '<tbody>';

		$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `ident_pr`='$id_pr' AND `ident_t`='$id_t' ORDER BY `date` ASC LIMIT $start_pos,$perpage");
		if(mysql_num_rows($sql_p)>0) {
			while ($row_p = mysql_fetch_array($sql_p)) {

				$post_to = new bbcode($row_p["text"]);
				$post_to = $post_to->get_html();
				$post_to = str_replace("'",'"',$post_to);
				$post_to = str_replace("`",'"',$post_to);


				$sql_u = mysql_query("SELECT `id`,`reiting`,`avatar`,`wall_com_p`,`wall_com_o`,`forum_mess`,`forum_status` FROM `tb_users` WHERE `username`='".$row_p["username"]."'");
				if(mysql_num_rows($sql_u)>0) {
					$row_u = mysql_fetch_row($sql_u);
					$wall_com = $row_u["3"] - $row_u["4"];

					if($wall_com>0) {
						$wall_com = '<b style="color:#008000;">+'.$wall_com.'</b>';
					}elseif($wall_com<0) {
						$wall_com = '<b style="color:#FF0000;">'.$wall_com.'</b>';
					}else{
						$wall_com = '<b style="color:#000000;">0</b>';
					}

					$info_user = '<b>'.$row_p["username"].'</b> <img src="img/reiting.png" title="Опыт" alt="" border="0" align="absmiddle" width="16" height="16" /> <span style="color:blue;">'.round($row_u["1"], 2).'</span> ';
					$info_user.= '<a href="/wall.php?uid='.$row_u["0"].'" target="_blank"><img src="img/wall20.png" title="Стена пользователя '.$row_p["username"].'" alt="" width="16" height="16" border="0" align="absmiddle" />'.$wall_com.'</a>';
					$av_user = '<img src="/avatar/'.$row_u["2"].'" border="0" alt="" align="middle" width="80" height="80" />';
				}else{
					$info_user = ''.$row_p["username"].' <span style="color:#FF0000;">Пользователь удален</span>';
					$av_user = '<img src="/avatar/no.png" border="0" alt="" align="middle" width="80" height="80" />';
				}

				if(DATE("d.m.Y", $row_p["date"])==DATE("d.m.Y")) {
					$post_date = DATE("Сегодня, в H:i",$row_p["date"]);
				}else{
					$post_date = DATE("d.m.Y H:i",$row_p["date"]);
				}


				echo '<tr>';
					echo '<td class="messtitle" colspan="2" nowrap="nowrap" valign="middle">';
						echo '<span class="spanleft">'.$info_user.'</span>';
						echo '<span class="spanright">'.$post_date.'</span>';
						echo '<a name="post-'.$row_p["id"].'"></a>';
					echo '</td>';
				echo '</tr>';

				echo '<tr>';
					echo '<td rowspan="2" valign="top" style="border: 1px;">';
						echo "$av_user";
						echo '<span class="forum-posts">'.@$row_u["5"].'</span>';
						if(isset($row_u["6"]) && $row_u["6"]>0) echo '<span class="status-'.@$row_u["6"].'">'.@$status_arr[@$row_u["6"]].'&nbsp;&nbsp;</span>';
					echo '</td>';
					echo '<td valign="top" width="90%" style="border: none;">';
						echo '<div class="forummess">'.$post_to.'</div>';
					echo '</td>';
				echo '</tr>';

				echo '<tr>';
					echo '<td valign="bottom" colspan="2">';
						if($moder_post==1 && $row_p["moder"]==0) echo '<span class="sub-black-l" onClick="moder_post('.$row_p["id"].'); return false;" id="modpost'.$row_p["id"].'"><img src="/forum/style/img/eye.png" alt="" width="16" height="16" align="middle" style="margin:1px; padding:1px;" /></span>';
						if($username!=false ) {
							if($username!=$row_p["username"] && isset($row_u["6"]) && $row_u["6"]!=1 && $ban_users==1) {
								if(isset($row_u["6"]) && $row_u["6"]==4 && $unban_users==1) {
									echo '<span class="sub-black" onClick="del_ban('.$row_u["0"].'); return false;">Снять бан</span>';
								}else{
									echo '<span class="sub-black" onClick="add_ban('.$id_t.','.$row_u["0"].'); return false;">Забанить</span>';
								}
							}

							if(($row_p["username"]==$username && $row_t["status"]==0) | ($moder_post==1)) {
								if(($row_p["status"]==0 && $del_post==1 && $row_p["moder"]==0) | ($row_p["status"]==0 && $moder_post==1)) echo '<span class="sub-red" onClick="delpost('.$id_t.','.$page.','.$row_p["id"].'); return false;">Удалить</span>';
								if(($edit_post==1 && $row_p["moder"]==0) | ($moder_post==1)) echo '<a class="sub-blue" href="'.$_SERVER["PHP_SELF"].'?pr='.$id_pr.'&th='.$id_t.'&post='.$row_p["id"].'">Изменить</a>';
							}
							if($row_t["status"]==0 && $add_post==1) echo '<span class="sub-green" onmouseover="copy_quote();" onClick="add_quote(\''.$row_p["username"].'\'); return false;">Цитировать</span>';
						}
					echo '</td>';
				echo '</tr>';
			}
		}

		echo '</tbody>';

		echo '</table>';

		universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 9, '&p=', "?th=$id_t");

		echo '<div class="forumpath"><a href="#top">Вверх</a></div>';


		if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
			echo '<div class="foruminfo">Создавать новые темы и комментировать могут только зарегистрированные пользователи.</div>';
		}else{

			if($row_t["status"]==1) {
				echo '<div class="info_ok">Тема закрыта для комментирования.</div>';
			}elseif($add_post!=1) {
				echo '<div class="foruminfo">У вас нет полномочий оставлять комментарии.</div>';
			}else{
				require("form_add_post.php");
			}
		}
	}
}
?>