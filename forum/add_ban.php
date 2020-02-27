<?php

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {

	$add_ban = (isset($_POST["add_ban"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["add_ban"]))) ? intval(limpiar(trim($_POST["add_ban"]))) : false;
	$forum_b_p = (isset($_POST["forum_b_p"])) ? limitatexto(limpiarez($_POST["forum_b_p"]),250) : false;
	$forum_b_e = (isset($_POST["forum_b_e"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["forum_b_e"])) && intval(limpiar(trim($_POST["forum_b_e"])))>0) ? intval(limpiar(trim($_POST["forum_b_e"]))) : "30";


	if($ban_users==0) {
		echo '<div class="foruminfo">Ошибка! У вас нет полномочий для блокировки пользователей.</div>';
	}else{
		$sql_b = mysql_query("SELECT `username`,`forum_status` FROM `tb_users` WHERE `id`='$add_ban'");
		if(mysql_num_rows($sql_b)>0) {
			$row_b = mysql_fetch_row($sql_b);

			$ban_user_name = $row_b["0"];
			$ban_user_status = $row_b["1"];

			if($ban_user_name==$username) {
				echo '<div class="foruminfo">Ошибка! У вас нет полномочий для блокировки пользоватя.</div>';
			}elseif($forum_b_p==false) {
				echo '<div class="foruminfo">Ошибка! Не указана причина блокировки.</div>';
			}elseif($ban_user_status==4) {
				echo '<div class="foruminfo">Ошибка! Пользователь уже заблокирован.</div>';
			}else{
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ban' AND `howmany`='1'");
				$reit_ban = mysql_result($sql,0,0);

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$reit_ban', `forum_status`='4',`forum_b_u`='$username',`forum_b_p`='$forum_b_p',`forum_b_e`='".(time()+$forum_b_e*24*60*60)."' WHERE `id`='$add_ban'") or die(mysql_error());

				if($page!=1) {$p_add="&p=$page";}else{$p_add="";}
				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?th='.$id_t.$p_add.'");</script> ';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="10;URL='.$_SERVER["PHP_SELF"].'?th='.$id_t.$p_add.'"></noscript>';
				exit();
			}
		}else{
			echo '<div class="foruminfo">Ошибка! Пользователь не определен.</div>';
		}
	}
}else{
	echo '<div class="foruminfo">Ошибка! Для доступа к этой странице необходимо авторизоваться.</div>';
}
?>