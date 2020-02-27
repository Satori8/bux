<?php
error_reporting(0);
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}
echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои платные вопросы</h5>';

mysql_query("UPDATE `tb_ads_questions` SET `status`='3' WHERE `status`='1' AND `totals`='0'") or die(mysql_error());

$sql1 = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_min'");
$vopros_min = number_format(mysql_result($sql1,0,0),0,".","");
$sql2 = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_color'");
$vopros_color = number_format(mysql_result($sql2,0,0),2,".","");
$sql3 = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_price'");
$vopros_cena = number_format(mysql_result($sql3,0,0),2,".","");

$sql = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';
	?>
	<script type="text/javascript" language="JavaScript">
			
			function obsch(z){
				var plan = parseFloat($(z).val().split(',').join('.').split('-').join(''));
				var cena_link=parseFloat($(z).attr('data-cena_vopr'));
				var cena_color=parseFloat($(z).attr('data-hzsho'));
				$('#'+$(z).attr('data-sum_price')).html(number_format((plan * cena_link+cena_color), 2, '.', ' ') + ' руб.');
			}
			 
			</script>
	<?php
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				if($row["status"]=="3") {
				    echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_start('.$row["id"].', \'vopros\')"></span>';
				}elseif($row["status"]=="1") {
				    echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="alert_stop('.$row["id"].', \'vopros\')"></span>'; 	
				}else{
				}
			echo '</td>';
			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["description"].'</a><br>';
				echo '<span class="info-text">';
					echo '№:&nbsp;'.$row["id"].'';
					if($row["status"]>"0") 
					echo '&nbsp;&nbsp;Заказано просмотров:&nbsp;'.$row["plan"];
					echo '&nbsp;&nbsp;Осталось:&nbsp;'.$row["totals"];
				echo '</span>';
				echo '<span class="adv-dell" title="Удалить ссылку" onClick="alert_delete('.$row["id"].', \'vopros\')"></span>';
			echo '</td>';

		echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="3") {
					echo '<a class="add-money-no" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">Пополнить</a>';
				}elseif($row["status"]=="1"){
					//echo '<a title="Для пополнение остановите площадку">Для пополнение остановите площадку</a>';
				//}else{
				   echo '<a class="add-money-no" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">'.$row["totals"].'</a>';
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr id="adv_addmoney'.$row["id"].'" style="display: none">';

			?>
			<?php
			
$row_color=$row["color"];
			echo '<td align="center" colspan="3" class="ext-text">';
				echo 'Укажите количество платных вопросов, которое хотите добавить к рекламной площадке<br>(Минимум '.$vopros_min.')';
				echo '<input type="text" data-cena_vopr="'.$vopros_cena.'" data-hzsho="'.($row_color * $vopros_color).'" maxlength="10" data-sum_price="price'.$row["id"].'_1" id="plan'.$row["id"].'" value="" class="payadv" onChange="obsch(this);" onKeyUp="obsch(this);" />'; 
				echo 'Стоимость:';  
				echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 руб.</span>';
				echo '<center><span onClick="alert_popoln('.$row["id"].', \'vopros\', \'plan'.$row["id"].'\')" class="sub-green" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></center>';
				echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';
			echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}else{     
	echo '<span class="msg-w">У вас нет своих размещённых платных вопросов!</span>';
}

echo '<div align="center"><a class="sub-blue160" href="/advertise.php?ads=quest" style="width:160px; margin-top:20px; float:none;">Разместить платный вопрос</a></div>';
?>