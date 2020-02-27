<?php

require('config.php');
$sql_task = mysql_query("SELECT * FROM `tb_ads_task` WHERE `status`='pay' AND `vip`>'0' AND `totals`>'0' ORDER BY `vip` DESC LIMIT 5");
$all_task = mysql_num_rows($sql_task);
if($all_task > 0) {
	echo '<table class="tables" style="margin-bottom:15px;">';
	echo '<thead><tr><th colspan="'.$all_task.'" class="top" align="center" >VIP задания</th></tr></thead>';

	echo '<tr>';
		while($row_task = mysql_fetch_assoc($sql_task)) {
		    
			echo '<td align="left" valign="top" width="'.floor(100/$all_task).'%" style="padding:15px;/*border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;*/box-shadow: inset 0 0 20px #007fff;-moz-box-shadow:inset 0 0 20px #855e42;-webkit-box-shadow: inset 0 0 20px #007fff;text-wrap:normal;word-wrap:break-word;background-color:ghostwhite;">';
				echo 'Задание <b>№ '.$row_task["id"].'</b><br>';
				echo '<a href="/view_task.php?page=task&rid='.$row_task["id"].'">'.$row_task["zdname"].'</a><br>';
				echo 'Рейтинг:&nbsp;<b>'.round($row_task["reiting"],2).'</b> Комментариев:&nbsp;<b>'.$row_task["all_coments"].'</b> Оплата:&nbsp;<b>'.$row_task["zdprice"].'&nbsp;руб.</b>';
			echo '</td>';
			
		}
	echo '</tr>';
	
	echo '</table>';
}

?>
