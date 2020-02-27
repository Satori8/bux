<?php

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && $re_theme==1) {

	$to_prazdel = (isset($_POST["to_prazdel"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["to_prazdel"]))) ? intval(limpiar(trim($_POST["to_prazdel"]))) : false;

	if($to_prazdel!=false && $id_t!=false) {

		$sql_pr = mysql_query("SELECT `ident_r` FROM `tb_forum_pr` WHERE `id`='$to_prazdel'");
		if(mysql_numrows($sql_pr)>0) {
			$row_pr = mysql_fetch_row($sql_pr);
			$id_r = $row_pr["0"];

			$sql_t = mysql_query("SELECT * FROM `tb_forum_t` WHERE `id`='$id_t'");
			if(mysql_numrows($sql_t)>0) {
				$row_t = mysql_fetch_array($sql_t);
				$id_old_pr = $row_t["ident_pr"];

				mysql_query("UPDATE `tb_forum_t` SET `ident_r`='$id_r',`ident_pr`='$to_prazdel' WHERE `id`='$id_t'") or die(mysql_error());
				mysql_query("UPDATE `tb_forum_p` SET `ident_r`='$id_r',`ident_pr`='$to_prazdel' WHERE `ident_t`='$id_t'") or die(mysql_error());

				$sql_p = mysql_query("SELECT `id` FROM `tb_forum_p` WHERE `moder`='0' AND `ident_pr`='$to_prazdel'");
				$count_to_moder_new = mysql_numrows($sql_p);

				mysql_query("UPDATE `tb_forum_pr` SET `moder`=`moder`+'$count_to_moder_new',`count_t`=`count_t`+'1' WHERE `id`='$to_prazdel'") or die(mysql_error());
				mysql_query("UPDATE `tb_forum_pr` SET `moder`=`moder`-'$count_to_moder_new',`count_t`=`count_t`-'1' WHERE `id`='$id_old_pr'") or die(mysql_error());

				$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `ident_pr`='$to_prazdel' ORDER BY `date` DESC LIMIT 1");
				if(mysql_num_rows($sql_p)>0) {
					$row_p = mysql_fetch_array($sql_p);
					$id_p = $row_p["id"];
					$id_t = $row_p["ident_t"];
					$title_t = $row_p["title"];
					$date_t = $row_p["date"];
					$username_t = $row_p["username"];

					mysql_query("UPDATE `tb_forum_pr` SET `lpost_t`='$title_t', `lpost_t_id`='$id_t', `lpost_user`='$username_t', `lpost_date`='$date_t', `lpost_p_id`='$id_p' WHERE `id`='$to_prazdel'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_forum_pr` SET `lpost_t`='', `lpost_t_id`='0', `lpost_user`='', `lpost_date`='0', `lpost_p_id`='0' WHERE `id`='$to_prazdel'") or die(mysql_error());
				}
				$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `ident_pr`='$id_old_pr' ORDER BY `date` DESC LIMIT 1");
				if(mysql_num_rows($sql_p)>0) {
					$row_p = mysql_fetch_array($sql_p);
					$id_p = $row_p["id"];
					$id_t = $row_p["ident_t"];
					$title_t = $row_p["title"];
					$date_t = $row_p["date"];
					$username_t = $row_p["username"];

					mysql_query("UPDATE `tb_forum_pr` SET `lpost_t`='$title_t', `lpost_t_id`='$id_t', `lpost_user`='$username_t', `lpost_date`='$date_t', `lpost_p_id`='$id_p' WHERE `id`='$id_old_pr'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_forum_pr` SET `lpost_t`='', `lpost_t_id`='0', `lpost_user`='', `lpost_date`='0', `lpost_p_id`='0' WHERE `id`='$id_old_pr'") or die(mysql_error());
				}
			}
		}
	}

	echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?th='.$id_t.'");</script> ';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?th='.$id_t.'"></noscript>';
}else{
	echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
}
?>

