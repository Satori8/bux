<?php

require_once ("bbcode/bbcode.lib.php");
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои оплачиваемые задания</h5>';		
echo '<a href="'.$_SERVER["PHP_SELF"].'?option=add_task" title="Создать новое задание"><span class="proc-btn"><center><img style="margin-top: -8px;" src="img/add.png" border="0" alt="" align="middle" title="Создать новое задание" />Создать новое задание</center></span></a>';

?>

<link rel="stylesheet" type="text/css" href="/style/task.css?v=1.02" />
<div id="load" style="display: none;"></div>
<script src="/js/js_task.js?v=1.021"></script>
  
  <script type="text/javascript" language="JavaScript">
var LoadBlock = false;
var s_h = false;
var new_id = false;

function LoadInfo(id, type, op, token) {
	if(s_h==(id + op)) {
		s_h = false; $("#task_info"+id).hide(); $("#mess_info"+id).html("");
	} else if(!LoadBlock){
		if(s_h && new_id && id!=new_id) {$("#task_info"+new_id).hide(); $("#mess_info"+new_id).html("");}

		$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_task_up.php", dataType: 'json', data: { 'id':id, 'type':type, 'op':op, 'token':token }, 
			error: function(request, status, errortext) {
				LoadBlock = false; $("#loading").slideToggle(); alert("Ошибка Ajax! \n"+status+"\n"+errortext); 
				console.log(status, errortext, request);
			},
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) { 
				LoadBlock = false; $("#loading").slideToggle();
				var result = (data.result!=undefined) ? data.result : data;
				var message = (data.message!=undefined) ? data.message : data;
				new_id = id; s_h = id + op;

				if(result == "OK") {
					$("#task_info"+id).css("display", "");
					$("#mess_info"+id).html(message);
				} else if(result == "ERROR") {
					$("#task_info"+id).css("display", "");
					$("#mess_info"+id).html('<span class="msg-error" style="margin:0 5px;">'+message+'</span>');
				} else {
					$("#task_info"+id).css("display", "");
					$("#mess_info"+id).html('<span class="msg-w" style="margin:0 5px;">Не удалось обработать запрос!<br>'+message+'</span>');
				}
			}
		});
	}

	return false;
}

function FuncAdv(id, type, op, token, title_win, close_win) {
	if(!LoadBlock){
		$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_task_up.php", dataType: 'json', 
			data: { 'id':id, 'type':type, 'op':op, 'token':token, 'plan_add':$.trim($("#plan_add"+id).val()), 'int_autoup':$.trim($("#int_autoup"+id).val()), 'cnt_autoup':$.trim($("#cnt_autoup"+id).val()) }, 
			error: function(request, status, errortext) { LoadBlock = false; $("#loading").slideToggle(); alert("Ошибка Ajax! \n"+status+"\n"+errortext); console.log(status, errortext, request); },
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false; $("#loading").slideToggle();
				var result = data.result!=undefined ? data.result : data;
				var message = data.message!=undefined ? data.message : data;
				var status = data.status!=undefined ? data.status : data;
				title_win = (!title_win | result!="OK") ? "Ошибка" : title_win;

				if (result == "OK") { 
					if(status) $("#status-"+id).html(status);
					if($("div").is(".box-modal") && message) {
						$(".box-modal-title").html(title_win);
						$(".box-modal-content").html(StatusMsg(result, message));
					} else if(message) {
						ModalStart(title_win, (title_win=="Ошибка" ? StatusMsg('ERROR', message) : message), 550, true, false, false);
					}
				} else { 
					if($("div").is(".box-modal") && message) {
						$(".box-modal-title").html(title_win);
						$(".box-modal-content").html(StatusMsg(result, message));
					} else if(message) {
						ModalStart(title_win, StatusMsg(result, message), 550, true, false, false);
					}
				}
			}
		});
	}

	return false;
}

</script>

<?
//$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_vip'");
//$task_cena_vip = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql_n = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
	$nacenka_task = mysql_result($sql_n,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='limit_min_task' AND `howmany`='1'");
    $limit_min_task = mysql_result($sql,0,0);
	
$s_key_task = "TUlnli^&*@if%Yl957kj630n(*7p0UFn#*hglkj?t";

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	

	while ($row = mysql_fetch_array($sql)) {
	    
	//$sql_num = mysql_fetch_array(mysql_query("select  num from (SELECT @num:=@num+1 AS num,id,date_up,status FROM (SELECT @num:= 0) v,tb_ads_task ORDER BY date_up DESC) zz where zz.id='".$row['id']."' "));
	$us_v = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='wait' or `status`='dorab') &&`ident`='".$row['id']."'"));
	$sql_wait_z=mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' &&`ident`='".$row['id']."'");
	$us_wait_z = mysql_num_rows($sql_wait_z);
		$token_form_up = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."FormUp".$s_key_task));
		
		
	if($row["status"]=="pay" && $row["date_up"]>0) {
			$position = mysql_result(mysql_query( "SELECT COUNT(*) FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='".$row["id"]."')" ),0,0)+1;
		}else{
			unset($position);
		}
?>

<table id="task-block<?=$row["id"];?>" class="adv-serf" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>
      <td class="normal" valign="middle" width="15px">
	 <? if($row['status']=='pay'){?>
        <span id="pause<?=$row["id"];?>" class="serfcontrol-play" title="Остановить задание" onclick="js_post(this, '../ajax/ajax_task.php', 'func=z_pause&id=<?=$row["id"];?>');return false;"></span>
	<?}elseif($row['status']=='pause' or $row['status']=='wait'){?>
		<span id="pause<?=$row["id"];?>" class="serfcontrol-pause" title="Запустить задание" onclick="js_post(this, '../ajax/ajax_task.php', 'func=z_play&id=<?=$row["id"];?>');return false;"></span>
	 <?}?>
	 </td>
      <td class="normal" valign="top" style="position: relative;">      
       
        <?
		echo '<a href="'.$_SERVER["PHP_SELF"].'?option=task_stat&rid='.$row["id"].'" style="">'.$row["zdname"].'</a><br>';
		$zd_price_n=$row["zdprice"]+($row["zdprice"]/100*$nacenka_task);
        echo'<span class="serfinfotext">
          № '.$row["id"].'&nbsp;&nbsp;
          Цена: '.$zd_price_n.' руб.&nbsp;&nbsp;
          Оплата исполнителю: '.$row["zdprice"].' руб.
        </span><br>';
        
        echo '<span class="desctext">Категория:&nbsp;';
		if($row['zdtype']=='1'){
			echo 'Клики.';
		}elseif($row['zdtype']=='2'){
			echo 'Регистрация без активности.';
		}elseif($row['zdtype']=='3'){
			echo 'Регистрация с активностью.';
		}elseif($row['zdtype']=='4'){
			echo 'Постинг в форум.';
		}elseif($row['zdtype']=='5'){
			echo 'Постинг в блоги.';
		}elseif($row['zdtype']=='6'){
			echo 'Голосование.';
		}elseif($row['zdtype']=='7'){
			echo 'Загрузка файлов.';
		}elseif($row['zdtype']=='8'){
			echo 'Прочее.';
		}elseif($row['zdtype']=='9'){
			echo 'YouTube';
		}elseif($row['zdtype']=='10'){
			echo 'Социальные сети';
		}elseif($row['zdtype']=='11'){
			echo 'Написать статью';
		}elseif($row['zdtype']=='12'){
			echo 'Оставить отзыв';
		}elseif($row['zdtype']=='13'){
			echo 'Играть в игры';
		}elseif($row['zdtype']=='14'){
			echo 'Инвестировать';
		}elseif($row['zdtype']=='15'){
			echo 'Стать моим рефералом на '.$_SERVER["HTTP_HOST"].'';
		}elseif($row['zdtype']=='16'){
			echo 'Перевод кредитов';
		/*}elseif($row['zdtype']=='17'){
			echo 'От Вашего реферера';*/
		}elseif($row['zdtype']=='18'){
			echo 'Forex';
		}elseif($row['zdtype']=='19'){
			echo 'Мобильные устройства';
		}elseif($row['zdtype']=='20'){
			echo 'Работа с капчей';
		}elseif($row['zdtype']=='21'){
			echo 'Работа с криптовалютами';
		}elseif($row['zdtype']=='22'){
			echo 'Экономические Игры/Фермы';
		}elseif($row['zdtype']=='23'){
			echo 'Зарубежные сайты';
		}

		echo '&nbsp;&nbsp;Время на выполнение:&nbsp;';
		if($row['time']=='1'){
			echo '1 час';
		}elseif($row['time']=='2'){
			echo '3 часа';
		}elseif($row['time']=='3'){
			echo '12 часов';
		}elseif($row['time']=='4'){
			echo '24 часа';
		}elseif($row['time']=='5'){
			echo '3 дня';
		}elseif($row['time']=='6'){
			echo '7 дней';
		}elseif($row['time']=='7'){
			echo '14 дней';
		}elseif($row['time']=='8'){
			echo '30 дней';
		}
		
		echo'<br><span class="serfinfotext">';
			if($row['zdre']=='0'){
		echo'Один пользователь — одно выполнение</span>';
			}else{
		echo'Многократно&nbsp;';
			
		if($row['zdre']=='3'){
			echo '(1 раз в 3 часа)';
		}elseif($row['zdre']=='6'){
			echo '(1 раз в 6 часов)';
		}elseif($row['zdre']=='12'){
			echo '(1 раз в 12 часов)';
		}elseif($row['zdre']=='24'){
			echo '(1 раз в 24 часа)';
		}elseif($row['zdre']=='48'){
			echo '(1 раз в 48 часов)';
		}elseif($row['zdre']=='72'){
			echo '(1 раз в 72 часа)';
		}
				
		echo'</span>';
			}
		echo '&nbsp;&nbsp;</span><br>';
		$user = mysql_fetch_array(mysql_query("SELECT id, username FROM `tb_users` WHERE `username`='".$username."'"));
		
		$us_play = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='wait' or `status`='dorab') &&`ident`='".$row['id']."'"));
		$us_ok = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='good' or `status`='good_auto') && `ident`='".$row['id']."'"));
		$us_no = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='bad' && `ident`='".$row['id']."'"));
		$us_izb = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='favorite' && `rek_id`='".$user['id']."'"));
		$us_bl = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='BL' && `rek_id`='".$user['id']."'"));
		$us_otz = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `coment`!='' && `ident`='".$row['id']."'"));
		$task_pay=$row['totals'];
		$sql_d = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
		$nacenka_task = mysql_result($sql_d,0,0);
		$bal_z=($row['zdprice']+(($row['zdprice']/100)*$nacenka_task))*$row['totals'];
		$bal_mno=($row['zdprice']+(($row['zdprice']/100)*$nacenka_task));
		
		?>
		<a class="workstatus-yes" href="#" title="Всего одобрено" onclick="popup_w('Пользователи, которым оплачены выполнения за все время!', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=good', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_ok;?></font></a>
		<a class="workstatus-wait" href="#" title="Сейчас выполняются" onclick="popup_w('Начали выполнять, либо подали отчет!', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=wait', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_play;?></font></a>
		<a class="workstatus-no" href="#" title="Всего отклонено" onclick="popup_w('Пользователи, которым отказано в оплате за все время!', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=bad', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_no;?></font></a>		
		<a class="workstatus-otziv" href="#" title="Отзывов о задании" onclick="popup_w('Отзывы о задании', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=otziv', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_otz;?></font></a>
		<a class="workstatus-pay" href="#" title="Количество пользователей, которые могут выполнить данное задание" onclick="popup_w('Количество пользователей, которые могут выполнить данное задание', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=pay', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$task_pay;?></font></a>
		<a class="workstatus-izbr" href="#" title="Добавили в избранное" onclick="popup_w('Добавили в избранное', false, 650, 'func=stata-task&id=<?=$user["id"];?>&mode=izbr', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_izb;?></font></a>
		<a class="workstatus-bl" href="#" title="Добавили в BL" onclick="popup_w('Добавили в BL', false, 650, 'func=stata-task&id=<?=$user["id"];?>&mode=bl', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_bl;?></font></a>
		<a class="workstatus-stat" href="#" title="Cтатистика задания" onclick="popup_w('Статистика задания', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=stat', '../ajax/ajax_task.php');return false;"><font color="#ffffff">&#10026;</font></a>

		<?
		//if($us_wait_z=='0')
		//if($us_wait_z=='0') {
		//if($row["wait"]>0) 
		if($us_wait_z=='0') {
			echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod-no" title="Выполнений нет" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">Выполнений нет</span>';
					//echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod" title="Проверить выполнения!" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">Выполнено:&nbsp;<b>['.$row["wait"].']</b></span>';
				}elseif($us_wait_z>='1') {
					echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod" title="Проверить выполнения!" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">Выполнено:&nbsp;<b>['.$row["wait"].']</b></span>';
					//echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod-no" title="Выполнений нет" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">Выполнений нет</span>';
				}
		
		if($row['status']=='pay'){?>
		<a class="scon-backmoney" title="Вернуть средства на рекламный счет и удалить его" style="display: none; float:right;" onclick="popup_w('Подтверждение!!', false, 500, 'func=z_vozvrat&l=../ajax/ajax_task.php&sum=<? echo $row['totals']*$row['zdprice'];?>&f=z_vozvrat&id=<?=$row["id"];?>&hash=4afe32e7ed14644e2ed39271d6c05d09', '../ajax/ajax_task.php');return false;"></a>
		
		<a style="display: none;" class="scon-perevod" onclick="show_window('#perevod-<?=$row["id"];?>');return false;" title="Перевести средства на другую площадку"></a>
		
        <!--<a class="scon-edit" style="display: none;" title="Редактировать задание" onclick="popup_w('Редактирование площадки № <?=$row["id"];?>', false, 600, 'func=edit-task&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;"></a>-->
		<a class="scon-edit" style="display: none;" href="/ads_task.php?option=edit_task&amp;rid=<?=$row["id"];?>" title="Редактировать задание"></a>
		
		<? }elseif($row['status']=='pause' or $row['status']=='wait'){?>
		<a class="scon-backmoney" title="Вернуть средства на рекламный счет и удалить его" style="float:right;" onclick="popup_w('Подтверждение!!', false, 500, 'func=z_vozvrat&l=../ajax/ajax_task.php&sum=<?echo $row['totals']*$row['zdprice'];?>&f=z_vozvrat&id=<?=$row["id"];?>&hash=4afe32e7ed14644e2ed39271d6c05d09', '../ajax/ajax_task.php');return false;"></a>

		 <a class="scon-perevod" onclick="show_window('#perevod-<?=$row["id"];?>');return false;" title="Перевести средства на другую площадку"></a>
		
        <!--<a class="scon-edit" title="Редактировать задание" onclick="popup_w('Редактирование площадки № <?=$row["id"];?>', false, 600, 'func=edit-task&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;"></a>-->
		<a class="scon-edit" href="/ads_task.php?option=edit_task&amp;rid=<?=$row["id"];?>" title="Редактировать задание"></a>
		<?}?>
		
		<?php
		//if(isset($position) && $position>0 && $position<100) {
					echo '<span id="task_up'.$row["id"].'" class="adv-down" title="Позиция задания в общем списке выдачи: '.$position.'" onClick="LoadInfo('.$row["id"].', \'task\', \'form_up\', \''.$token_form_up.'\');">'.$position.'</span>';
				//}else{
					//echo '<span id="task_up'.$row["id"].'" class="adv-up" title="Поднять задание в списке" onClick="LoadInfo('.$row["id"].', \'task\', \'form_up\', \''.$token_form_up.'\');">&uarr;</span>';
				//}
				?>
		

       <!--<span class="scon-up tipi" onclick="show_window('#taskup-<?=$row["id"];?>');return false;" title="Поднять задания в списке"></span>-->
	<!-- <span class="scon-up tipi" id="task_up'.$row["id"].'" onClick="LoadInfo(<?=$row["id"];?>, 'task', 'form_up', '<?=$token_form_up;?>');"></span>-->

        <span class="scon-vip tipi" onclick="show_window('#taskvip-<?=$row["id"];?>');return false;" title="Разместить в VIP-блоке"></span>
        <span class="scon-color tipi" onclick="show_window('#taskcolor-<?=$row["id"];?>');return false;" title="Выделить цветом"></span>
        <a class="scon-view" onclick="show_window('#view-<?=$row["id"];?>');return false;" title="Подробная информация"></a>
        <a class="scon-url tipi" onclick="show_window('#tasurl-<?=$row["id"];?>');return false;" title="Ссылка на задание"></a>
               
      </font></td>
      <td class="budget" style="width:65px;">
     
         <a class="add-budget" onclick="show_window('#moneyadd-<?=$row["id"];?>');return false;" title="Пополнить бюджет"></a>
         <span id="money<?=$row["id"];?>" class="desctext">
         <font style="color:#267F00;"><?echo $bal_z;?></font>
         </span>
       
      </td>
    </tr>

	<?php
		echo '<tr id="task_info'.$row["id"].'" style="display: none">';
			echo '<td align="center" colspan="4" class="ext-text"><div id="mess_info'.$row["id"].'"></div></td>';
		echo '</tr>';
	?>

    <tr id="view-<?=$row["id"];?>" style="display: none;">
      <td class="ext-viptask" colspan="3" style="text-align: left; padding: 2px 10px; background: #f9f9f9;">
        <span class="letter-subtitle">Описание задания:</span>
		<font color="#545454"><?=$row['zdtext'];?></font>
		<span class="letter-subtitle">Что нужно указать для выполнения задания:</span>
		<font color="#C80000">
		<? if($row['zdquest']==null){
		echo $row['zotch'];
		}else{
		echo 'Контрольный вопрос: '.$row['zdquest'];
		}
		?>
		</font>
		<span class="letter-subtitle">Ссылка для перехода:</span>
		<font color="#545454"><?=$row['zdurl'];?></font>  
      </td>
	</tr>
	
	<tr id="comp<?=$row["id"];?>" style="display: none;"><td class="ext-comp" colspan="3"></td></tr>
		<script language="JavaScript">
			function makePrice<?=$row["id"];?>()
			{
			var count = document.getElementById("plan_<?=$row["id"];?>").value;
			var price = count * <?=$bal_mno;?>;
			document.getElementById("taskprice_<?=$row["id"];?>").value = price;
			}
		</script>
	<tr id="moneyadd-<?=$row["id"];?>" style="display: none">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;" id="prob<?=$row["id"];?>">
	    Количество выполнений (Мин. 3):
		
		<div class="sum"><input type="text" class="summ" name="plan" id="plan_<?=$row["id"];?>" maxlength="6" size="4" value="1" autocomplete="off" oninput="makePrice<?=$row["id"];?>();"></div>
		
		<input name="mess-text" id="mess-text-<?=$row["id"];?>" type="hidden" value="Вы уверены что хотите пополнить баланс площадки ID: <?=$row["id"];?>?">
		<input name="system" id="inp-system-<?=$row["id"];?>" type="hidden" value="0">
		<input id="money_kl_<?=$row["id"];?>" type="hidden" value="1" ><br>
		Cумма пополнения: <input id="taskprice_<?=$row["id"];?>" type="number" align='center' value='<?=$bal_mno;?>' style="width: 5em;  border-radius: 3px;" disabled oninput="makeGoods();"> руб.
		<br><br>

		<span class="btn-line"><input type="submit" class="proc-btn" value="Оплатить с рекл. счета" onclick="funcjs['go_balans'](0,<?=$row["id"];?>,'4afe32e7ed14644e2ed39271d6c05d09','../ajax/ajax_task.php');"></span>
		<span class="btn-line"><input type="submit" class="proc-btn" value="Оплатить с основного счета" onclick="funcjs['go_balans'](1,<?=$row["id"];?>,'4afe32e7ed14644e2ed39271d6c05d09','../ajax/ajax_task.php');"></span>	
		<br>
	  </td>
	</tr>
	
	
	<tr id="perevod-<?=$row["id"];?>" style="display: none">
<script>
 funcjs['go_perevod'] = function(id, url) {
	        
	popup_w(
	  'Подтверждение!!', 
	  false, 
	  500, 
	  'func=z_perevod&l='+url+'&id='+id+'&plan='+$('#planz_'+id).val()+'&z_popoln='+$('#z_popoln_'+id).val(), 
	  '../ajax/ajax_task.php'
	);
	        
	return false;
	          	
  }	
 
</script>	

	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;" id="prob<?=$row["id"];?>">
	  	<?
		
		echo '<select name="z_popoln_'.$row["id"].'" id="z_popoln_'.$row["id"].'" class="ok">
				<option value="0">Не выбрано</option>';
			
			$sql_p = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' and id!='".$row['id']."' ORDER BY `id` ASC");
					while ($row_p = mysql_fetch_array($sql_p)) {
							//echo'<option value="'.$row_p['id'].'">ID:'.$row_p['id'].'->'.$row_p['zdname'].'->'.$row_p['zdprice'].' руб./1 выполнение</option>';
							$z_cen=$row_p['zdprice']+$row_p['zdprice']/100*$nacenka_task;
							echo'<option value="'.$row_p['id'].'">ID:'.$row_p['id'].'->'.$z_cen.' руб./1 выполнение</option>';
					}
			
		echo '</select><br>';
?>

	    Пополнить выбраное задание на такое количество заданий (Мин. 1):<br>Обратите внимание, остаток средств, будет зачислен на рекламный счет, за вычетом комиссии 20%.
		<div class="sum"><input type="text" class="summ" name="planz" id="planz_<?=$row["id"];?>" maxlength="6" size="4" value="1" autocomplete="off" oninput="makePrice<?=$row["id"];?>();"></div>
		<!--<div class="sum"><input type="text" class="summ" name="plan" id="plan_<?=$row["id"];?>" maxlength="6" size="4" value="1"></div>-->
		
		<input name="mess-text" id="mess-text-<?=$row["id"];?>" type="hidden" value="Вы уверены что хотите пополнить баланс площадки ID: <?=$row["id"];?>?">
		<input id="money_kl_<?=$row["id"];?>" type="hidden" value="1" ><br>
		<!--Cумма пополнения: <input id="taskprice_<?=$row["id"];?>" type="number" align='center' value='<?=$bal_mno;?>' style="width: 5em;  border-radius: 3px;" disabled oninput="makeGoods();"> руб.
		<br><br>-->
		
		<span class="btn-line"><input type="submit" class="proc-btn" value="Перевести" onclick="funcjs['go_perevod'](<?=$row["id"];?>,'../ajax/ajax_task.php');"></span>
			
		<br>
	  </td>
	</tr>
	
	
	<tr id="taskup-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    Задание будет поднято на первую строчку в своей категории и в общем поиске<br>Стоимость поднятия составляет 1<!--?=$ask_taskupam?--> руб.<br>
		<span class="proc-btn" onclick="popup_w('Подтверждение!!', false, 500, 'func=z_up&l=../ajax/ajax_task.php&d=Вы уверены что хотите поднять задание ID: <?=$row["id"];?>?&f=verx&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">Поднять задание вручную</span>
		<!--<hr>-->
		
	  </td>
	</tr>
	
	<tr id="taskvip-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    Задание будет размещено в VIP блоке, а также будет выделено в общем списке заданий особым значком VIP<br>Стоимость выделения значком VIP и размещение  в отдельном блоке составляет 3 руб.<br>
		<span class="proc-btn" onclick="popup_w('Подтверждение!!', false, 500, 'func=z_vip&l=../ajax/ajax_task.php&d=Вы уверены что хотите выделить значком VIP а так же разместить в VIP блоке, площадку ID: <?=$row["id"];?>?&f=vip&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">Выделить значком VIP</span>
	  </td>
	</tr>
	
	<tr id="taskperevod-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	   Перевести средства с этого задание на другое. Для этого укажите сумму перевода и выбирите одно из заданых в списке заданий:<br> 
		<span class="proc-btn" onclick="popup_w('Подтверждение!!', false, 500, 'func=z_vip&l=../ajax/ajax_task.php&d=Вы уверены что хотите выделить значком VIP а так же разместить в VIP блоке, площадку ID: <?=$row["id"];?>?&f=vip&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">Выделить значком VIP</span>
	  </td>
	</tr>
	
	<tr id="taskcolor-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    Задание будет выделено красным цветом<br>Стоимость выделения цветом составляет 1 руб. на 1 день<br>
	    <span class="proc-btn" onclick="popup_w('Подтверждение!!', false, 500, 'func=z_color&l=../ajax/ajax_task.php&d=Вы уверены что хотите выделить цветом, площадку ID: <?=$row["id"];?>?&f=color&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">Выделить цветом</span>
	  </td>
	</tr>
	
	<tr id="tasurl-<?=$row["id"];?>" style="display: none;">
      <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    Чтобы прорекламировать задание № <?=$row["id"];?> в серфинге - размещайте только эту ссылку!<br>
		<div style="border: 1px solid #cccccc; padding:5px; margin-top: 5px; margin-bottom:5px;">
		  <a href="/view_task.php?page=task&rid=<?=$row["id"];?>" target="_blank" title="Ссылка на ваше задание">https://<?=$_SERVER["SERVER_NAME"];?>/view_task.php?page=task&rid=<?=$row["id"];?></a>
		</div>
      </td>
    </tr>
	
	<tr>
      <td class="taskmsg" colspan="3" style="display: none; ">
	  </td>
    </tr>

	<tr id="otchet-<?=$row["id"];?>" class="otchet-new" style="display: none;">
	  <td class="text-otchet" id="text-otchet-<?=$row["id"];?>" colspan="3">

</td>
	</tr>

  </tbody></table>
<?
}
}else{
echo '<br><span class="msg-warning">У Вас нету заданий!</span>';
}
//}

?>