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

if($_GET["op"]!='edit' && $_GET["id"]==null){

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
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'" title="Редактировать ссылку"></a>';
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
}else{
///////////////
$id_edd=$_GET["id"];
$sql_edd = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `username`='$username' && `id`='$id_edd'");
if(mysql_num_rows($sql_edd)>0) {
$row_edd = mysql_fetch_array($sql_edd);
if($row_edd['var_ok']=='1'){
$checked_1='checked="checked"';
}elseif($row_edd['var_ok']=='2'){
$checked_2='checked="checked"';
}elseif($row_edd['var_ok']=='3'){
$checked_3='checked="checked"';
}elseif($row_edd['var_ok']=='4'){
$checked_4='checked="checked"';
}elseif($row_edd['var_ok']=='5'){
$checked_5='checked="checked"';
}

if(isset($_POST["url"]) && isset($_POST["varok"]) && isset($_POST["var1"]) && isset($_POST["var2"]) && isset($_POST["var3"]) && isset($_POST["var4"]) && isset($_POST["var5"]) && isset($_POST["description"])){
if($_POST['url']==null or $_POST['url']=='0'){
echo '<span class="msg-w">Вы не ввели URL сайта!</span>';
}elseif($_POST['description']==null or $_POST['description']=='0'){
echo '<span class="msg-w">Вы не указали вопрос!</span>';
}elseif($_POST['var1']==null or $_POST['var2']==null or $_POST['var3']==null or $_POST['var4']==null or $_POST['var5']==null){
echo '<span class="msg-w">Заполните все варианты ответов!</span>';
}elseif($_POST['var1']==$_POST['var2'] or $_POST['var1']==$_POST['var3'] or $_POST['var1']==$_POST['var4'] or $_POST['var1']==$_POST['var5'] or $_POST['var2']==$_POST['var3'] or $_POST['var2']==$_POST['var4'] or $_POST['var2']==$_POST['var5'] or $_POST['var3']==$_POST['var4'] or $_POST['var3']==$_POST['var5'] or $_POST['var4']==$_POST['var5']){
echo '<span class="msg-w">Нельзя указывать одинаковые варианты ответов!</span>';
}elseif($_POST['varok']==null or $_POST['varok']<1 or $_POST['varok']>5){
echo '<span class="msg-w">Неправильно указали номер правильного ответа!</span>';
}else{
mysql_query("UPDATE `tb_ads_questions` SET `url`='$_POST[url]', `description`='$_POST[description]', `var1`='$_POST[var1]', `var2`='$_POST[var2]', `var3`='$_POST[var3]', `var4`='$_POST[var4]', `var5`='$_POST[var5]', `var_ok`='$_POST[varok]' WHERE `id`='$id_edd' && `username`='$username'") or die(mysql_error());
echo '<span class="msg-ok" style="margin-bottom:0px;">Платный вопрос успешно отредактирован!</span><br>';
$server_1=$_SERVER['SERVER_NAME'];
echo '<script>setTimeout(function(){location.replace("https://'.$server_1.'/cabinet_ads?ads=vopros");}, 2000);</script>';
exit();
}
}
	echo '<form method="post" action="" name="formzakaz" id="newform">';
	echo '<table class="tables">';
	echo '<thead><th colspan="2" class="top">Форма редактирования платного вопроса №'.$id_edd.'</th></thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="200"><b>URL сайта (ссылка):</b></td>';
			echo '<td><input type="text" name="url" maxlength="160" value="'.$row_edd['url'].'" class="ok"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Ваш вопрос:</b><br>(Максимум 255 символов)</td>';
			echo '<td>';
				echo '<textarea name="description" value="">'.$row_edd['description'].'</textarea>';
			echo '</td>';
		echo '</tr>'; 
		echo '<tr>';
			echo '<td><b>Введите 5 вариантов ответов:</b><br>один из которых должен быть правильным</td>';
			echo '<td>';
				echo '<input type="text" name="var1" maxlength="100" value="'.$row_edd['var1'].'" class="ok"><br>';
				echo '<input type="text" name="var2" maxlength="100" value="'.$row_edd['var2'].'" class="ok"><br>';
				echo '<input type="text" name="var3" maxlength="100" value="'.$row_edd['var3'].'" class="ok"><br>';
				echo '<input type="text" name="var4" maxlength="100" value="'.$row_edd['var4'].'" class="ok"><br>';
				echo '<input type="text" name="var5" maxlength="100" value="'.$row_edd['var5'].'" class="ok"><br>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Укажите номер правильного ответа:</b></td>';
			echo '<td>';
				echo '1-<input type="radio" name="varok" value="1" '.$checked_1.' style="margin:0px; background:none;">&nbsp;&nbsp;&nbsp;';
				echo '2-<input type="radio" name="varok" value="2" '.$checked_2.' style="margin:0px; background:none;">&nbsp;&nbsp;&nbsp;';
				echo '3-<input type="radio" name="varok" value="3" '.$checked_3.' style="margin:0px; background:none;">&nbsp;&nbsp;&nbsp;';
				echo '4-<input type="radio" name="varok" value="4" '.$checked_4.' style="margin:0px; background:none;">&nbsp;&nbsp;&nbsp;';
				echo '5-<input type="radio" name="varok" value="5" '.$checked_5.' style="margin:0px; background:none;">';
			echo '</td>';
		echo '</tr>';
			echo '<tr>';
			echo '<td colspan="2" align="center"><input type="submit" value="Cохранить изменения" class="sub-blue160" style="float:none;" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</form>';




}else{
echo '<span class="msg-w">Это не Ваш платный вопрос!</span>';
}
///////////////
}
echo '<div align="center"><a class="sub-blue160" href="/advertise.php?ads=quest" style="width:160px; margin-top:20px; float:none;">Разместить платный вопрос</a></div>';
?>