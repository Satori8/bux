<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<br><b style="color:#FF0000; font-size:12px;">Ошибка! Для доступа к этой странице необходимо авторизоваться!</b>';
}else{
	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$id_t = (isset($_GET["th"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["th"]))) ? intval(limpiar(trim($_GET["th"]))) : false;
	$page = (isset($_GET["p"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["p"]))) ? intval(trim($_GET["p"])) : "1";
	$add_ban = (isset($_GET["add_ban"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["add_ban"]))) ? intval(limpiar(trim($_GET["add_ban"]))) : false;

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$sql = mysql_query("SELECT `forum_status` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_row($sql);

		$user_f_status = $row["0"];

		$sql_s = mysql_query("SELECT `ban_users` FROM `tb_forum_s` WHERE `id_status`='$user_f_status'");
		if(mysql_num_rows($sql_s)>0) {
			$row_s = mysql_fetch_array($sql_s);

			$ban_users = $row_s["ban_users"];
		}else{
			$ban_users = 0;
		}

		if($ban_users==0) {
			echo '<br><b style="color:#FF0000; font-size:12px;">Ошибка! У вас нет полномочий для блокировки пользователей!</b>';
		}else{
			$sql_u = mysql_query("SELECT `username`,`forum_status` FROM `tb_users` WHERE `id`='$add_ban'");
			if(mysql_num_rows($sql_u)>0) {
				$row_u = mysql_fetch_row($sql_u);

				$ban_user_name = $row_u["0"];
				$ban_user_status = $row_u["1"];

				if($ban_user_name==$username) {
					echo '<br><b style="color:#FF0000; font-size:12px;">Ошибка! У вас нет полномочий для блокировки пользоватя!</b>';
				}elseif($ban_user_status==4) {
					echo '<br><b style="color:#FF0000; font-size:12px;">Ошибка! Пользователь уже заблокирован!</b>';
				}else{
					echo '<form action="" method="post" onSubmit="return saveform();" onkeypress="if((event.ctrlKey) &amp;&amp; ((event.keyCode == 0xA)||(event.keyCode == 0xD))){this.submit();}">';
					echo '<table width="90%" border="0" cellspacing="1" cellpadding="2" style="margin:0 auto; padding-top:10px;">';
						echo '<input type="hidden" name="add_ban" value="'.$add_ban.'">';
						echo '<tr><td>Логин пользователя: <b>'.$ban_user_name.'</b></td></tr>';
						echo '<tr><td>Причина блокировки:<br><textarea  cols="" rows="5" name="forum_b_p" id="forum_b_p" style="width: 94%; background-color: #FBFCCC;"></textarea></td></tr>';
						echo '<tr><td>Длительность блокировки: <input type="text" name="forum_b_e"  id="forum_b_e" value="30" maxlength="10" style="text-align:right; width:30px"> дней</td></tr>';
						echo '<tr><td align="center" style="padding-top:20px;"><input type="submit" name="submit" value="Заблокировать пользователя" style="background: #2087DB; color: #FFFFFF; padding: 0px; padding: 3px 10px; font-size: 12px; border: 1px solid #5656E4;"></td></tr>';
					echo '</table>';
					echo '</form>';
				}
			}else{
				echo '<br><b style="color:#FF0000; font-size:12px;">Ошибка! Пользователь не определен!</b>';
			}
		}
	}else{
		echo '<br><b style="color:#FF0000; font-size:12px;">Ошибка! Для доступа к этой странице необходимо авторизоваться!</b>';
	}


}

?>