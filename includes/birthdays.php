<?php

$sql = mysql_query("SELECT `id`,`username`,`avatar` FROM `tb_users` WHERE `db_date` LIKE '%".DATE("d.m.", time())."%'");
$all_birthdays = mysql_num_rows($sql);
if($all_birthdays>0) {
	//echo '<div style="margin:5px; text-align:center; color:#007FFF; font-weight: bold;">Дни рождения сегодня</div>';
	echo '<div style="margin:5px; text-align:center; color:#007FFF;">Всего: '.$all_birthdays.'</div>';

	echo '<div style="display:block; overflow:auto; max-height:260px;">';
	echo '<table class="tables">';
		while ($row_db = mysql_fetch_assoc($sql)){
			$db_id = $row_db["id"];
			$db_user_name = $row_db["username"];
			$db_avatar = $row_db["avatar"];

			echo '<tr>';
				echo '<td align="center" style="border-right:none; width:35px;"><a href="/wall?uid='.$db_id.'" target="_blank"><img src="avatar/'.$db_avatar.'" class="avatar_small" alt="" /></a></td>';
				echo '<td align="left" style="border-left:none; padding-left:0px;"><a href="/wall?uid='.$db_id.'" target="_blank" style="color:#0080EC; font-weight:bold;">'.$db_user_name.'</a></td>';
			echo '</tr>';
		}
	echo '</table>';
	echo '</div>';
}else{
	echo '<div style="margin:5px; text-align:center; color:#ab0606; font-weight: bold;">Дней рождения сегодня нет</div>';
}

?>