<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}
require_once(DOC_ROOT."/bbcode/bbcode.lib.php");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_articles' AND `howmany`='1'");
$cena_articles = number_format(mysql_result($sql,0,0), 2, ".", "");

if(isset($ads) && isset($id) && isset($op) && $ads=="articles" && $op!=false) {

	if($op=="edit" && $id>0) {
		if(!DEFINED("ARTICLES_EDIT")) DEFINE("ARTICLES_EDIT", true);
		include("articles_edit.php");
		echo '</div></div></div>';
		include(DOC_ROOT."/footer.php");
		exit();
	}else{
		echo '<span class="msg-error">ERROR</span>';
	}
	echo '</div></div></div>';
	include(DOC_ROOT."/footer.php");
	exit();
}

?><script type="text/javascript" language="JavaScript">
var s_h = false;
var new_id = false;

function LoadInfo(id, op) {
	if(s_h==(id + op)) {
		s_h = false;
		$("#load-info"+id).hide();
		$("#mess-info"+id).html("");
	} else {
		if(s_h && new_id) {
			$("#load-info"+new_id).hide();
			$("#mess-info"+new_id).html("");
		}
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), data: { 'op':op, 'type':'articles', 'id':id }, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				new_id = id; s_h = id + op;

				$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);
				$("#load-info"+id).show(); 
				$("#mess-info"+id).html('<span class="msg-error">Ошибка обработки данных! Сообщите Администрации сайта.</span>');
				return false;
			}, 
			success: function(data) { 
				$("#loading").slideToggle();

				if(op == "go_edit") {
					new_id = id; s_h = id;

					if (data.result == "OK") {
						document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=$ads&op=edit&id=";?>"+id;
					} else {
						if(data.message) { alert(data.message); }
						else { alert("Ошибка обработки данных!"); }
					}
				} else {
					new_id = id; s_h = id + op;

					$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);
					$("#load-info"+id).show();

					if (data.result == "OK") {
						if(op == "get_add_money") {

							$("#S_H_M"+id).html('<img src="/img/ok.gif" alt="" title="Рекламная площадка оплачена" border="0" width="32" />');
							$("#S_H_M"+id).removeAttr("class");
							$("#S_H_M"+id).removeAttr("title");
							$("#S_H_M"+id).removeAttr("onClick");
							$("#playpauseimg"+id).html('<span class="adv-postmoder" title="Рекламная площадка на модерации"></span>'); 
							$("#mess-info"+id).html('<span class="msg-ok">Бюджет рекламной площадки успешно пополнен!</span>');

							setTimeout(function() {
								$("#info-msg-addmoney").html("").hide();
								$("#load-info"+id).hide();
								s_h = false; new_id = false;
							}, 1000);
						}else{
							if(data.message) { $("#mess-info"+id).html(data.message); }
							else { $("#mess-info"+id).html('<span class="msg-error">Ошибка обработки данных!</span>'); }
						}
					} else {
						if(data.message) { $("#mess-info"+id).html('<span class="msg-w">' + data.message + '</span>'); }
						else { $("#mess-info"+id).html('<span class="msg-w">Ошибка обработки данных!</span>'); }
					}
				}
			}
		});
	}
	return false;
}

function pay_adv_up(id, type) {
	$.ajax({
		type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv_up', 'type':type, 'id':id}, 
		dataType: 'json',
		beforeSend: function() { $("#loading").slideToggle(); }, 
		error: function() {
			$("#loading").slideToggle();
			new_id = id; s_h = id + op;

			$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);
			$("#load-info"+id).show(); 
			$("#mess-info"+id).html('<span class="msg-error">Ошибка обработки данных! Сообщите Администрации сайта.</span>');
			return false;
		}, 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();

			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
			else alert(message);
		}
	});
}

</script><?php

echo '<table class="tables">';
echo '<tr><td align="center" style="background:#f5debd; border:none; padding:10px; color:#E32636; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);">После оплаты ваша статья попадает на модерацию.</td></tr>';
echo '</table><br>';

echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои статьи</h5>';

$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `username`='$username' ORDER BY `id` DESC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab" id="newform">';

	while ($row = mysql_fetch_assoc($sql)) {
		if($row["status"]==1 && $row["up_list"]>0) {
			$position = mysql_result(mysql_query( "SELECT COUNT(*) FROM `tb_ads_articles` WHERE `status`='1' AND `up_list`>(SELECT `up_list` FROM `tb_ads_articles` WHERE `id`='".$row["id"]."')" ),0,0)+1;
		}else{
			unset($position);
		}

		$sum = number_format(($cena_articles * (100-$cab_skidka)/100), 2, ".", "");

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1" style="">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0") {
						echo '<span class="adv-moder" title="Рекламная площадка ожидает модерации" onClick="alert(\'Рекламная площадка ожидает модерации. Для отправки на модерацию необходимо оплатить!\');""></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="Рекламная площадка активна"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-postmoder" title="Рекламная площадка на модерации"></span>';
					}elseif($row["status"]=="4") {
						echo '<span class="adv-block" title="Рекламная площадка заблокирована" onClick="alert(\'Рекламная площадка заблокирована. Необходимо внести изменения!\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank" title="'.$row["url"].'"><span class="title-text">'.$row["title"].'</span></a><br>';

				echo '<span class="info-text">';
					echo '№&nbsp;'.$row["id"].'&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["views"].'</span>';
				echo '</span>';

				if($row["status"]=="4") {
					echo '<div class="warning-info" style="font-weight:normal;">'.$row["msg_lock"].'<br>После внесения изменений сообщите об этом администрации по <a href="/newmsg.php?name=Admin&subject=Разблокировка статьи №'.$row["id"].'" target="_blank" title="Написать сообщение администрации">внутренней почте</a> для разблокировки. <u>Не забудьте указать № статьи.</u></div>';
				}

				echo '<span class="adv-dell" title="Удалить статью" onClick="DelAds('.$row["id"].', \'articles\');"></span>';
				echo '<span class="adv-edit" title="Редактировать статью" onClick="LoadInfo('.$row["id"].', \'go_edit\');"></span>';

				if($row["status"]==1) {
					if(isset($position) && $position>0 && $position<100) echo '<span class="adv-down" title="Позиция статьи в списке каталога: '.$position.'" onClick="LoadInfo(\''.$row["id"].'\', \'get_up\');">'.$position.'</span>';
					else echo '<span class="adv-up" title="Поднять в списке" onClick="LoadInfo(\''.$row["id"].'\', \'get_up\');">&uarr;</span>';
				}

				echo '<span class="adv-erase" title="Сброс статистики" onClick="clear_stat('.$row["id"].', \'articles\', '.$row["views"].');"></span>';
				echo '<span class="adv-info" title="Предварительный просмотр статьи" onClick="LoadInfo('.$row["id"].', \'get_info\');"></span>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0") {
					echo '<a id="S_H_M'.$row["id"].'" class="add-money-no" title="Пополнить рекламный бюджет" onClick="if(!confirm(\'Вы подтверждаете оплату рекламной площадки № '.$row["id"].' на сумму '.$sum.' руб. (c учетом скидки) ? \')) {return false;}else{LoadInfo('.$row["id"].', \'get_add_money\'); return false;}">';
						echo '<span id="count_balance'.$row["id"].'">Оплатить</span>';
					echo '</a>';
				}else{
					echo '<img src="/img/ok.gif" alt="" title="Рекламная площадка оплачена" border="0" width="32" />';
				}
			echo '</td>';
		echo '</tr>';

		echo '<tr id="load-info'.$row["id"].'" style="display: none">';
			echo '<td align="center" colspan="3" class="ext-text">';
				echo '<div id="mess-info'.$row["id"].'"></div>';
			echo '</td>';
		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">У вас нет своих размещённых статей</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads='.$ads.'\'">Добавить статью</span></div>';
?>