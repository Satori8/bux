<?php

$task_cat_arr = array(
	1 => "Клики", 
	2 => "Регистрация без активности", 
	3 => "Регистрация с активностью", 
	4 => "Постинг в форум", 
	5 => "Бонусы", 
	6 => "Голосование", 
	7 => "Загрузка файлов", 
	8 => "Прочее",
	
	9=> "YouTube",
	10=> "Социальные сети",
	11=> "Написать статью",
	13 => "Играть в игры",
	14 => "Инвестировать",
	15 => "Стать моим рефералом на ".$_SERVER['HTTP_HOST']."",
	16 => "Перевод кредитов/баллов",
	18 => "Forex",
	19 => "Мобильные устройства",
	20 => "Работа с капчей",
	21 => "Работа с криптовалютами",
	22 => "Экономические Игры/Фермы",
	23 => "Зарубежные сайты"
);

if(function_exists('cnt_text')===false) {
	function cnt_text($count, $text1, $text2, $text3, $text) {
		if($count>=0) {
			if( ($count>=10) && ($count<=20) ) {
				return "$count $text1";
			}else{
				switch(substr($count, -1, 1)){
					case 1: return "$count $text2"; break;
					case 2: case 3: case 4: return "$count $text3"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: return "$count $text1"; break;
				}
			}
		}
	}
}

$sql_task = mysql_query("SELECT * FROM `tb_ads_task` WHERE `status`='pay' AND `vip`>'0' AND `totals`>'0' ORDER BY `vip` DESC LIMIT 5");
$all_task = mysql_num_rows($sql_task);
if($all_task > 0) {
	echo '<table class="tables" style="margin-bottom:25px;">';
	echo '<thead><tr><th colspan="3" align="center">VIP задания</th></tr></thead>';

	while($row_task = mysql_fetch_assoc($sql_task)) {
		echo '<tr>';
			echo '<td align="center" width="40"><img src="images/otyn/ad-vip.png" alt=""><span title="VIP задание" style="float:none; margin:0; padding:0;"></span></td>';
			echo '<td align="left">';
				echo '<span style="float:left;">';
	echo '<a href="/view_task.php?page=task&rid='.$row_task["id"].'" class="tip"><span style="color:#006699;" class="classic">'.$row_task["zdtext"].'</span>
					<font style="color:#006699;">'.$row_task["zdname"].'</font></a><br>';
					echo '<span style="font-size: 11px; color: #89A688;">'.$row_task["zdurl"].'</span><br>';
					echo '<span style="font-size: 11px; color: #114C5B; line-height: 1.6;">№'.$row_task["id"].': '.(isset($task_cat_arr[$row_task["zdtype"]]) ? $task_cat_arr[$row_task["zdtype"]] : "Прочее").'</span>';
					
				echo '<i><a style="color:#89A688; border-bottom: 1px dotted;" href="/wall?uid='.$row_task["user_id"].'" title="Стена автора задания" target="_blank">&nbsp;[ '.$row_task["username"].' ]</a></i>';
				$username = mysql_num_rows(mysql_query("SELECT * FROM tb_online WHERE username='".$row_task["username"]."'"));
				if($username==1) echo '<i><span title="Автор задания онлайн" style="cursor: help;color: #03c03c; font-size: 11px;"> On-line</span> </i>';
				else{ echo '';}
					
					if($row_task["date_add"] > (time()-24*60*60)) echo '<span style="color:red; margin-left:5px;">Новое!</span>';
				echo '</span>';
				if($row_task["zdre"]>0) echo '<span style="cursor: help; color: #9966cc; float: right; margin-top: 30px;" title="Можно выполнять 1 раз в '.$row_task["zdre"].'ч."">[Многоразовое] </span>';
				if($row_task["country_targ"]==1) {
					echo '<span style="float:right; margin-top:23px;"><img src="img/flags/ru.gif" border="0" width="16" height="12" alt="" align="absmiddle" title="Выполнение задания доступно только России" /></span>';
				}elseif($row_task["country_targ"]==2) {
					echo '<span style="float:right; margin-top:23px;"><img src="img/flags/ua.gif" border="0" width="16" height="12" alt="" align="absmiddle" title="Выполнение задания доступно только Украины" /></span>';
				}						
			echo '</td>';
			echo '<td align="center" width="90">';
				echo '<div style="color:#363636;">'.$row_task["zdprice"].' руб.</div>';
				echo '<div style="margin-top:7px;"><span style="color:green;">'.$row_task["goods"].'</span> - <span style="color:red;">'.$row_task["bads"].'</span> - <span style="color:#1E90FF;">'.$row_task["wait"].'</span><br>';
		?>
		<div style="margin-top:7px;"><span style="color:green;"><span class='rating<?=intval($row_task["reiting"]); ?>' title='Рейтинг'></span></div>
		<?php
			echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}

?>