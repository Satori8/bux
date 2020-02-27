<?php

//require_once('.zsecurity.php');

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("\\","",$mensaje);
	if (get_magic_quotes_gpc()) { $mensaje = stripslashes($mensaje); }
	$mensaje = str_replace("'"," ",$mensaje);
	$mensaje = str_replace(";"," ",$mensaje);
	$mensaje = str_replace("$"," ",$mensaje);
	$mensaje = str_replace("<"," ",$mensaje);
	$mensaje = str_replace(">"," ",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	return $mensaje;
}

?><script type="text/javascript" src="forum/js/forum.js"></script><?php

$status_arr = array('','Админ','Модератор','Консультант','Забанен');

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

$smileskod = array(
	':D',':)',':(',':heap:',':ooi:',':so:',':surp:',':ag:',':ir:',':oops:',':P',':cry:',':rage:',':B',':roll:',':wink:',':yes:',':bot:',':z)',
	':arrow:',':vip:',':Heppy:',':think:',':bye:',':roul:',':pst:',':o:',':closed:',':cens:',':tani:',':appl:',':idnk:',':sing:',':shock:',':tgu:',
	':res:',':alc:',':lam:',':box:',':tom:',':lol:',':vill:',':idea:',':oops:',':E:',':sex:',':horns:',':love:',':poz:',':roza:',':meg:',':dj:',
	':rul:',':offln:',':sp:',':stapp:',':girl:',':heart:',':kiss:',':spam:',':party:',':ser:',':eam:',':gift:',':adore:',':pie:',':egg:',':cnrt:',
	':oftop:',':foo:',':mob:',':hoo:',':tog:',':pnk:',':pati:',':-({|=:',':haaw:',':angel:',':kil:',':died:',':cof:',':fruit:',':tease:',':evil:',
	':exc:',':niah:',':Head:',':gl:',':granat:',':gans:',':user:',':ny:',':mvol:',':boat:',':phone:',':cop:',':smok:',':bic:',':ban:',':bar:'
);


if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_u = mysql_query("SELECT `forum_status` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_u)>0) {
		$row_u = mysql_fetch_row($sql_u);

		$user_f_status = $row_u["0"];

		$sql_s = mysql_query("SELECT * FROM `tb_forum_s` WHERE `id_status`='$user_f_status'");
		if(mysql_num_rows($sql_s)>0) {
			$row_s = mysql_fetch_array($sql_s);

			$moder_post = $row_s["moder_post"];
			$ban_users = $row_s["ban_users"];
			$unban_users = $row_s["unban_users"];
			$add_theme = $row_s["add_theme"];
			$del_theme = $row_s["del_theme"];
			$close_theme = $row_s["close_theme"];
			$open_theme = $row_s["open_theme"];
			$re_theme = $row_s["re_theme"];
			$add_post = $row_s["add_post"];
			$del_post = $row_s["del_post"];
			$edit_post = $row_s["edit_post"];
		}else{
			$moder_post = 0;
			$ban_users = 0;
			$unban_users = 0;
			$add_theme = 0;
			$del_theme = 0;
			$close_theme = 0;
			$open_theme = 0;
			$re_theme = 0;
			$add_post = 0;
			$del_post = 0;
			$edit_post = 0;
		}
	}
}else{
	$moder_post = 0;
	$ban_users = 0;
	$unban_users = 0;
	$add_theme = 0;
	$del_theme = 0;
	$close_theme = 0;
	$open_theme = 0;
	$re_theme = 0;
	$add_post = 0;
	$del_post = 0;
	$edit_post = 0;
}

if(count($_GET)>0 ) {
	require("navigator.php");
	require_once("bbcode/bbcode.lib.php");
	$id_pr = (isset($_GET["pr"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["pr"]))) ? intval(limpiar(trim($_GET["pr"]))) : false;
	$id_t = (isset($_GET["th"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["th"]))) ? intval(limpiar(trim($_GET["th"]))) : false;
	$id_p = (isset($_GET["del"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["del"]))) ? intval(limpiar(trim($_GET["del"]))) : false;
	$post_edit = (isset($_GET["post"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["post"]))) ? intval(limpiar(trim($_GET["post"]))) : false;
	$page = (isset($_GET["p"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["p"]))) ? intval(trim($_GET["p"])) : "1";

	if($ban_users==1 | $unban_users==1 | $del_theme==1 | $close_theme==1 | $open_theme==1) {
		echo '<script type="text/javascript" src="forum/js/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="forum/js/jqpooop.js"></script>';
	}


	if(isset($_GET["pr"]) && isset($_GET["th"]) && isset($_GET["post"])) {

		if($id_pr==false | $id_t==false | $post_edit==false) {
			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script> ';
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
		}else{
			require("edit_post.php");
			include("footer.php");
			exit();
		}

	}elseif(isset($_GET["th"]) && $id_t!=false) {

		require("posts.php");

	}elseif(isset($_GET["pr"]) && $id_pr!=false) {

		require("themes.php");
	}
}else{

	echo '<table class="forum" align="center" border="0">';
	echo '<thead><tr><th></th><th align="left" width="80%" nowrap="nowrap">Форум</th><th align="center" nowrap="nowrap">Темы</th><th align="center" nowrap="nowrap">Последний пост</th><th></th></tr></thead>';

	echo '<tbody>';
	$sql_r = mysql_query("SELECT * FROM `tb_forum_r` ORDER BY `id` ASC");
	if(mysql_num_rows($sql_r)>0) {
		while ($row_r = mysql_fetch_array($sql_r)) {
			echo '<tr align="left"><td colspan="4" class="forum_r">'.$row_r["razdel"].'</td><td class="forum_r"></td></tr>';

			$sql_pr = mysql_query("SELECT * FROM `tb_forum_pr` WHERE `ident_r`='".$row_r["id"]."' ORDER BY `id` ASC");
			if(mysql_num_rows($sql_pr)>0) {
				while ($row_pr = mysql_fetch_array($sql_pr)) {
					echo '<tr>';
						echo '<td width="28"><img src="/forum/style/img/groupe.png" alt="" width="26" height="26" align="absmiddle" /></td>';
						echo '<td><a class="prtitle" href="'.$_SERVER["PHP_SELF"].'?pr='.$row_pr["id"].'">'.$row_pr["title"].'<br><span class="ntitle">'.$row_pr["opis"].'</span></a></td>';
						echo '<td class="ct">'.$row_pr["count_t"].'</td>';

						if(DATE("d.m.Y", $row_pr["lpost_date"])==DATE("d.m.Y")) {
							$lpost_date = DATE("Сегодня, в H:i",$row_pr["lpost_date"]);
						}else{
							$lpost_date = DATE("d.m.Y",$row_pr["lpost_date"]);
						}
						echo '<td nowrap="nowrap">';
							if($row_pr["lpost_t"]!=false) echo '<a class="lpostb" href="'.$_SERVER["PHP_SELF"].'?th='.$row_pr["lpost_t_id"].'&p=-1#post-'.$row_pr["lpost_p_id"].'">'.$row_pr["lpost_t"].'<br><span class="nlpost">'.$lpost_date.'</span><br><span class="ulpost">'.$row_pr["lpost_user"].'</span></a>';
						echo '</td>';

						echo '<td>';
							if($moder_post==1 && $row_pr["moder"]>0) echo '<img src="/forum/style/img/eye.png" alt="" width="16" height="16" align="absmiddle" />';
						echo '</td>';
					echo '</tr>';
				}
			}
		}
	}

	echo '</tbody>';

	echo '</table>';
}

?>