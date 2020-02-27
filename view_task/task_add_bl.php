<?php
session_start();
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Для доступа к этой странице необходимо авторизоваться!</b>';
}else{
	require('../config.php');
	require('../funciones.php');

	$username = uc($_SESSION["userLog"]);
	$rek_id = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;


	$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$username'");
	$user_id = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `user_id`='$rek_id' AND `status`='pay' AND `totals`>'0'");
	$all_task = mysql_num_rows($sql);
	if($all_task>0) {
        	$row = mysql_fetch_array($sql);
		$rid = $row["id"];

		$sql_p = mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `type`='BL' AND `rek_id`='$rek_id'");
		$all_users_add_bl = mysql_num_rows($sql_p);

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$user_id' AND `rek_id`='$rek_id'");
		if(mysql_num_rows($sql_p)>0) {
			echo '<b style="color:#FF0000; font-size:12px;">Вы уже добавили этого пользователя в Black List!</b>';
		}else{
			$sql_u = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$rek_id'");
			if(mysql_num_rows($sql_u)>0) {
				$row_u = mysql_fetch_array($sql_u);
				$info_user = '<b>'.$row_u["username"].'</b>';
			}else{
				$info_user = ''.$row["user_name"].' <span style="color:#FF0000;">Рекламодатель удален</span>';
			}

			echo '<form action="/view_task.php?page=task&rid='.$row["id"].'" method="post">';
			echo '<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td valign="top">';
			echo '<input type="hidden" name="rid" value="'.$rid.'">';
			echo '<input type="hidden" name="bl" value="add">';
			echo 'Если Вам не понравилось выполнять задания данного рекламодателя, занесите его в Black List, и Вы больше не будете видеть задания этого рекламодателя в списке заданий.<br><br>';

			echo 'Пользователь: '.$info_user.'<br><br>';
			echo 'Оплачиваемых заданий у этого рекламодателя: <b>'.$all_task.'</b> [<a href="/view_task.php?page=task&task_search=4&task_name='.$row_u["id"].'">подробнее</a>]<br><br>';
			echo 'Пользователей, у которых этот рекламодатель в BL: '.$all_users_add_bl.'<br><br>';

			$sql_p = mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `type`='favorite' AND `user_id`='$user_id' AND `rek_id`='$rek_id'");
			if(mysql_num_rows($sql_p)>0) {
				echo '<b>Внимание!</b> Этот рекламодатель находится у Вас в избранном. При добавлении его в BL, он будет автоматически удален из избранного!<br><br>';
			}

			echo '<center><input type="submit" name="submit" value="Добавить в Black List" class="proc-btn"></center>';
			echo '</td></tr>';
			echo '</table>';
			echo '</form>';
		}
	}else{
		echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Такого задания нет!</b>';
	}
}
?>