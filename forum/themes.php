<?php

if(count($_POST)>0 && isset($_POST["addtheme"])) {
	require("add_theme.php");
}


$sql_pr = mysql_query("SELECT * FROM `tb_forum_pr` WHERE `id`='$id_pr' ORDER BY `id` ASC");
if(mysql_num_rows($sql_pr)>0) {
	$row_pr = mysql_fetch_array($sql_pr);
	$status_pr = $row_pr["status"];

	$perpage = 20;
	$sql_t = mysql_query("SELECT `id` FROM `tb_forum_t` WHERE `ident_r`='".$row_pr["ident_r"]."' AND `ident_pr`='$id_pr'");
	$count = mysql_numrows($sql_t);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["p"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["p"]))) ? intval(trim($_GET["p"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	echo '<div class="forumpath"><a href="'.$_SERVER["PHP_SELF"].'">Форумы</a><a href="'.$_SERVER["PHP_SELF"].'?pr='.$row_pr["id"].'">'.$row_pr["title"].'</a></div>';

	universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 9, '&p=', "?pr=$id_pr");

	echo '<table class="forum" align="center" border="0">';
	echo '<thead><tr><th></th><th align="left" width="80%" nowrap="nowrap">Темы</th><th align="center" nowrap="nowrap">Ответов</th><th nowrap="nowrap">Последний пост</th><th align="center" nowrap="nowrap">Тема создана</th><th></th></tr></thead>';

	echo '<tbody>';

	$sql_t = mysql_query("SELECT * FROM `tb_forum_t` WHERE `ident_r`='".$row_pr["ident_r"]."' AND `ident_pr`='$id_pr' ORDER BY `lpost_date` DESC LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql_t)>0) {
		while ($row_t = mysql_fetch_array($sql_t)) {
			echo '<tr>';
				if($row_t["status"]==0) {
					echo '<td width="28"><img src="/forum/style/img/post.png" alt="" width="24" height="24" align="absmiddle" /></td>';
				}else{
					echo '<td width="28"><img src="/forum/style/img/post-close.png" alt="" width="24" height="24" align="absmiddle" /></td>';
				}
				echo '<td><a class="prtitle" href="'.$_SERVER["PHP_SELF"].'?th='.$row_t["id"].'">'.$row_t["title"].'<br><span class="ntitle">'.$row_t["opis"].'</span></a></td>';
				echo '<td class="ct">'.$row_t["count_otv"].'</td>';

				if(DATE("d.m.Y", $row_t["lpost_date"])==DATE("d.m.Y")) {
					$lpost_date = DATE("Сегодня, в H:i",$row_t["lpost_date"]);
				}else{
					$lpost_date = DATE("d.m.Y",$row_t["lpost_date"]);
				}
				echo '<td>';
					echo '<a class="lpost" href="'.$_SERVER["PHP_SELF"].'?th='.$row_t["id"].'&p=-1#post-'.$row_t["lpost_p_id"].'">'.$row_t["lpost_user"].'<br><span class="nlpost">'.$lpost_date.'</span></a>';
				echo '</td>';

				echo '<td class="ct">'.DATE("d.m.Y", $row_t["date"]).'</td>';

				echo '<td>';
					if($moder_post==1 && $row_t["moder"]>0) echo '<img src="/forum/style/img/eye.png" alt="" width="16" height="16" align="absmiddle" />';
				echo '</td>';
			echo '</tr>';
		}
	}

	echo '</tbody>';
	echo '</table>';

	universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 9, '&p=', "?pr=$id_pr");

	if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
		if($status_pr[0]!=0) {
			echo '<div class="foruminfo">Создавать новые темы и комментировать могут только зарегистрированные пользователи.</div>';
		}
	}else{
		if($add_theme==1 && $status_pr[$user_f_status]==1) {
			require("form_add_theme.php");
		}else{
			echo '<div class="foruminfo">У вас нет полномочий создавать новые темы.</div>';
		}
	}
}
?>