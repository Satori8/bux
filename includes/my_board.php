<script type="text/javascript">
	function add_to_ref(id, user){ 
		if (confirm('Вы уверены, что хотите стать рефералом пользователя '+user+'?')) {
			$.ajax({
				type: "POST", url: "/ajax/ajax_add_to_referer.php", data: {"id" : id, "user" : user}, 
				success: function(data) { alert(data); $('#board_noref').html(''); $('#board_noref_w').html(''); } 
			}); 

		}
	}
</script>
<?php
function board_status($reiting){
	$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='".$reiting."' AND `r_do`>='".floor($reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_assoc($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_assoc($sql_rang);
	}
	return '<span style="cursor:help; color: #FF0000;" title="Статус">'.$row_rang["rang"].'</span>';
}

$sql_b = mysql_query("SELECT `user_id`,`user_name`,`comment`,`count`,`count_now` FROM `tb_board` WHERE `lider`='1' LIMIT 1");
if(mysql_num_rows($sql_b)>0) {
	$row_b = mysql_fetch_assoc($sql_b);
	$board_user_id = $row_b["user_id"];
	$board_user_name = $row_b["user_name"];
	$board_user_comment = $row_b["comment"];
	$board_user_count = $row_b["count"];
	$board_user_count_now = $row_b["count_now"];

	$sql_us = mysql_query("SELECT `avatar`,`reiting`,`ref_back_all`,`country_cod` FROM `tb_users` WHERE `id`='$board_user_id'");
	if(mysql_num_rows($sql_us)>0) {
		$row_us = mysql_fetch_assoc($sql_us);
		$user_avatar = $row_us["avatar"];
		$user_reiting = $row_us["reiting"];
		$user_ref_back_all = $row_us["ref_back_all"];
		$user_country_cod = $row_us["country_cod"];

		echo '<div id="UserBoard" style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; font-weight:bold;"><table class="tables_inv" style="padding:0px; width:100%; margin:0 auto; margin-top:5px;">';
		echo '<tr align="center">';
			echo '<td colspan="3" style="padding:6px;">';
				if($user_country_cod != false) echo '<img src="img/flags/'.strtolower($user_country_cod).'.gif" border="0" alt="" title="'.get_country($user_country_cod).'" style="margin:0px 10px 0px; padding:0;" align="absmiddle" />';
				echo '<span style="color:#ab0606;">'.$board_user_name.'</span>';
				echo '<span style="color:#808080; cursor:help;" title="Авто-рефбек"> - '.$user_ref_back_all.'%</span>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="30%"><span style="font-size:16px; cursor:help;" title="Общая сумма ставок">&uarr; '.$board_user_count.'</span></td>';
			echo '<td width="40%"><a href="/wall?uid='.$board_user_id.'" title="Стена пользователя '.$board_user_name.'"><img src="avatar/'.$user_avatar.'" class="avatar_board" width="65" height="65" border="0" title="Стена пользователя '.$board_user_name.'" align="middle" alt="" /></a></td>';
			echo '<td width="30%"><span style="font-size:16px; cursor:help;" title="Ставка на текущий момент">'.$board_user_count_now.' &darr;</span></td>';
		echo '</tr>';

		if($board_user_comment!=false) echo '<tr align="center"><td colspan="3" style="padding:4px;">'.$board_user_comment.'</td></tr>';
		
		$sql_all_s = mysql_query("SELECT sum(count) FROM `tb_board` WHERE `status`='0' ORDER BY `count` DESC, `time` DESC");
	$all_count = mysql_result($sql_all_s,0,0);
	
	$sql_c = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_comis'");
	$cena_board_comis = mysql_result($sql_c,0,0);

	$pay_to_user = $all_count * ($cena_board_comis/100);
	$pay_to_user = round($pay_to_user,2);

		echo '<tr align="center">';
			echo '<td colspan="3" style="padding:4px;">';
				echo board_status($user_reiting);
				echo '<span style="color:'.($user_reiting>0 ? "#864200;" : "#FF0000;").' cursor:help;" title="Рейтинг">&nbsp;&nbsp;'.($user_reiting>0 ? "+" : "-").abs(round($user_reiting,2)).'</span>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="center" id="board_noref">';
			echo '<td colspan="3" style="padding:4px;"><a style="cursor:pointer;" onclick="add_to_ref(\''.$board_user_id.'\', \''.$board_user_name.'\');">&raquo; Вступить в мою команду &laquo;</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td colspan="3" style="padding:4px;"><a style="cursor:pointer;" href="/wall?uid='.$board_user_id.'">&raquo; Найдете меня здесь &laquo;</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
		echo '<td colspan="3" style="padding:4px;"><b style="letter-spacing:normal;">Призовой фонд: <span class="text-red">'.number_format($pay_to_user, 2, ".", "").' руб.</span></b></td>';
		echo '</tr>';
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='board_bonus' AND `howmany`='1'");
		$sum_dch = mysql_result($sql,0,0);
		echo '<tr align="center">';
			echo '<td colspan="3" style="padding:4px;"><a href="/board"><div style="color: #FF0000;">Накопительный бонус: '.round($sum_dch,2).' руб. </div></a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td colspan="3" style="padding:4px;"><a href="/board"><div style="color: #FF0000;">&raquo; Хочу сюда &laquo;</div></a></td>';
		echo '</tr>';
		echo '</table></div>';
	}
}else{
	echo '<div style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; font-weight:bold;">';
	echo '<table style="padding-top:0px; width:98%; margin:0 auto;">';
	echo '<tr align="center">';
		echo '<td><a href="/board"><img src="avatar/no.png" class="avatar_board" border="0" align="absmiddle" alt="" /><br><br><div style="color: #FF0000;">&raquo; Хочу сюда &laquo;</div></a></td>';
	echo '</tr>';
	echo '</table></div>';
}

?>