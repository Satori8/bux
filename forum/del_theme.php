<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
error_reporting (E_ALL);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {

	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$id_t = (isset($_GET["th"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["th"]))) ? intval(limpiar(trim($_GET["th"]))) : false;
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$sql = mysql_query("SELECT `forum_status` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_row($sql);

		$user_f_status = $row["0"];

		$sql_s = mysql_query("SELECT `id` FROM `tb_forum_s` WHERE `id_status`='$user_f_status' AND `del_theme`='1'");
		if(mysql_num_rows($sql_s)>0) {

			$sql_t = mysql_query("SELECT `ident_pr` FROM `tb_forum_t` WHERE `id`='$id_t'");
			if(mysql_numrows($sql_t)>0) {
				$row_t = mysql_fetch_row($sql_t);
				$id_pr = $row_t["0"];

				mysql_query("DELETE FROM `tb_forum_t` WHERE `id`='$id_t'") or die(mysql_error());
				mysql_query("DELETE FROM `tb_forum_p` WHERE `ident_t`='$id_t'") or die(mysql_error());

				$sql_p = mysql_query("SELECT `id` FROM `tb_forum_p` WHERE `moder`='0' AND `ident_pr`='$id_pr'");
				$count_to_moder = mysql_numrows($sql_p);

				$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `ident_pr`='$id_pr' ORDER BY `date` DESC LIMIT 1");
				if(mysql_num_rows($sql_p)>0) {
					$row_p = mysql_fetch_array($sql_p);
					$id_p = $row_p["id"];
					$id_t = $row_p["ident_t"];
					$title_t = $row_p["title"];
					$date_t = $row_p["date"];
					$username_t = $row_p["username"];

					mysql_query("UPDATE `tb_forum_pr` SET `moder`='$count_to_moder',`count_t`=`count_t`-'1',`lpost_t`='$title_t', `lpost_t_id`='$id_t', `lpost_user`='$username_t', `lpost_date`='$date_t', `lpost_p_id`='$id_p' WHERE `id`='$id_pr'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_forum_pr` SET `moder`='$count_to_moder',`count_t`='0',`lpost_t`='', `lpost_t_id`='0', `lpost_user`='', `lpost_date`='0', `lpost_p_id`='0' WHERE `id`='$id_pr'") or die(mysql_error());
				}

				echo '<script type="text/javascript">location.replace("/forum.php?pr='.$id_pr.'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/forum.php?pr='.$id_pr.'"></noscript>';
			}
		}
	}

	if(!isset($id_pr)) {
		echo '<script type="text/javascript">location.replace("'.getenv("HTTP_REFERER").'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.getenv("HTTP_REFERER").'"></noscript>';
	}
}else{
	echo '<script type="text/javascript">location.replace("/forum.php");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/forum.php"></noscript>';
}
?>
