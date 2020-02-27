<?php

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {

	$id_r = (isset($_POST["id_r"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_r"]))) ? intval(limpiar(trim($_POST["id_r"]))) : false;
	$id_pr = (isset($_POST["id_pr"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pr"]))) ? intval(limpiar(trim($_POST["id_pr"]))) : false;
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),100) : false;
	$opis = (isset($_POST["opis"])) ? limitatexto(limpiarez($_POST["opis"]),100) : false;

	$post = (isset($_POST["post"])) ? trim($_POST["post"]) : false;
	$post = str_replace("'", '"', $post);
	$post = str_replace("`", '"', $post);
	$post = str_replace("\\", "", $post);
	if (get_magic_quotes_gpc()) {$post = stripslashes($post);}

	$sql_r = mysql_query("SELECT `id` FROM `tb_forum_r` WHERE `id`='$id_r'");
	if(mysql_num_rows($sql_r)>0) {
		$sql_pr = mysql_query("SELECT `id`,`status` FROM `tb_forum_pr` WHERE `id`='$id_pr' AND `ident_r`='$id_r' ORDER BY `id` ASC");
		if(mysql_num_rows($sql_pr)>0) {
			$row_pr = mysql_fetch_array($sql_pr);

			$status_pr = $row_pr["status"];

			if($add_theme==1 && $status_pr[$user_f_status]!=1) {
				echo '<div class="foruminfo">У вас нет полномочий создавать новые темы.</div>';
			}elseif($title==false) {
				echo '<div class="info_error">Необходимо указать название темы!</div>';
			}elseif($opis==false) {
				echo '<div class="info_error">Необходимо указать краткое описание темы!</div>';
			}elseif($post==false) {
				echo '<div class="info_error">Необходимо указать сообщение для темы!</div>';
			}else{
				if($moder_post==1) {$moder_post2=0;}else{$moder_post2=1;}

$rowl = mysql_query("SELECT price FROM tb_config WHERE item='rait_forum'") or die(mysql_error());
$rowl = mysql_fetch_row($rowl); 
$rr_price=$rowl[0];

				mysql_query("INSERT INTO `tb_forum_t` (`status`,`moder`,`username`,`ident_r`,`ident_pr`,`title`,`opis`,`count_otv`,`date`,`lpost_user`,`lpost_date`) VALUES('0','$moder_post2','$username','$id_r','$id_pr','$title','$opis','0','".time()."','$username','".time()."')") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `forum_mess`=`forum_mess`+'1', `reiting`=`reiting`+'$rr_price' WHERE `username`='$username'") or die(mysql_error());

				$sql_t_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_forum_t`");
				$last_t_id = mysql_result($sql_t_id,0,0);

				mysql_query("INSERT INTO `tb_forum_p` (`status`,`moder`,`username`,`ident_r`,`ident_pr`,`ident_t`,`title`,`text`,`date`) VALUES('1','$moder_post','$username','$id_r','$id_pr','$last_t_id','$title','$post','".time()."')") or die(mysql_error());

				$sql_p_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_forum_p`");
				$last_p_id = mysql_result($sql_p_id,0,0);


				mysql_query("UPDATE `tb_forum_pr` SET `moder`=`moder`+'$moder_post2',`count_t`=`count_t`+'1', `lpost_t`='$title', `lpost_t_id`='$last_t_id', `lpost_user`='$username', `lpost_date`='".time()."',`lpost_p_id`='$last_p_id' WHERE `id`='$id_pr' AND `ident_r`='$id_r'") or die(mysql_error());
				mysql_query("UPDATE `tb_forum_t` SET `lpost_p_id`='$last_p_id' WHERE `id`='$last_t_id'") or die(mysql_error());

				//echo '<div class="info_ok">Тема успешно добавлена!</div>';
				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?pr='.$id_pr.'");</script> ';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?pr='.$id_pr.'"></noscript>';
			}
		}
	}else{
	}
}else{
	echo '<div class="foruminfo">Создавать новые темы и комментировать могут только зарегистрированные пользователи.</div>';
}

?>
