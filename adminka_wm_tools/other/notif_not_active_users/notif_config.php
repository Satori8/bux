<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Настройка рассылки сообщения на e-mail не активным пользователям</b></h3>';

require(ROOT_DIR."/config_mysqli.php");

$security_key = "AsDiModI*N^I&uwK(*An#*hg@if%YST630nlkj7p0U?";
$token_string = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."notif-config-save".$security_key));

$sql = $mysqli->query("SELECT * FROM `tb_notif_conf` WHERE `id`='1'") or die($mysqli->error);
if($sql->num_rows > 0) {
	$row = $sql->fetch_assoc();
	$notif_status = $row["status"];
	$notif_days_not_active = $row["days_not_active"];
	$notif_days_resending = $row["days_resending"];
	$notif_text_message = $row["text_message"];
}else{
	exit('<span class="msg-error">Нет данных!</span>');
}

$mysqli->close();
?>
<script>
var status_form = false;

function FuncNotif(op, type, form_id, token, title_win, width_win) {
	var datas = {}; datas['op'] = op || ''; datas['type'] = type || ''; datas['token'] = token || '';
	if(form_id) {var data_form = $("#"+form_id).serializeArray(); $.each(data_form, function(i, field) {datas[field.name] = $.trim(field.value);});}

	console.log(datas);

	if(!status_form){$.ajax({
		type:"POST", cache:false, url:"ajax/ajax_others.php", dataType:'json', data:datas, 
		error: function(request, status, errortext) {
			status_form = false; $("#loading").hide();
			ModalStart("Ошибка Ajax!", StatusMsg("ERROR", errortext+"<br>"+(request.status!=404 ? request.responseText : 'url ajax not found')), 500, true, false, false);
		}, 
		beforeSend: function() { status_form = true; $("input, textarea, select").blur(); $("#loading").show(); }, 
		success: function(data) {
			status_form = false; $("#loading").hide();
			var result = data.result || data;
			var message = data.message || data;
			width_win = width_win || "550";

			if (result == "OK") {
				title_win = title_win || "Информация";
				ModalStart(title_win, message, width_win, true, false, 3);
			} else { 
				title_win = title_win || "Ошибка";

				if($("div").is(".box-modal") && message) {
					$(".box-modal-title").html(title_win);
					$(".box-modal-content").html(StatusMsg(result, message));
					TmMod = setTimeout(function(){if($("div").is(".box-modal")) $.modalpopup("close");}, 7000);

				} else if(message) {
					ModalStart(title_win, StatusMsg(result, message), width_win, true, false, 7);
				}
			}
		}
	});}
	return false;
}

function CtrlEnter(event) {
	e = event || window.event;
	if((e.ctrlKey) && (e.keyCode == 10 | e.keyCode == 13)) {
		$("#SubMit").click();
	}
	return false;
}
</script>
<?php

echo '<div id="newform"><form id="form-notif" action="" method="POST" onSubmit="FuncNotif(\'notif-config-save\', \'notif-not-active-users\', $(this).attr(\'id\'), \''.$token_string.'\'); return false;" onKeyPress="CtrlEnter(event);">';
echo '<table class="tables">';
echo '<thead>';
	echo '<tr align="center">';
		echo '<th width="400">Параметр</th>';
		echo '<th>Значение</a>';
	echo '</tr>';
echo '</thead>';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>Статус функции</b></td>';
		echo '<td align="left">';
			echo '<select name="notif_status" style="width:125px; text-align:center; text-align-last:center;">';
				echo '<option value="0" '.($notif_status==0 ? 'selected="selected"' : false).'>Отключена</option>';
				echo '<option value="1" '.($notif_status==1 ? 'selected="selected"' : false).'>Включена</option>';
			echo '</select>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>Количество дней не активности для отправки уведомления на e-mail</b></td>';
		echo '<td align="left"><input type="number" name="days_not_active" value="'.$notif_days_not_active.'" min="5" max="1000" step="1" class="ok12" style="text-align:center;" autocomplete="off" required="required" /></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>Количество дней, для повторной отправки уведомления</b></td>';
		echo '<td align="left"><input type="number" name="days_resending" value="'.$notif_days_resending.'" min="3" max="1000" step="1" class="ok12" style="text-align:center;" autocomplete="off" required="required" /></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td colspan="2"><b>Текст сообщения &darr;</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td colspan="2" style="padding-right:2px;">';
			echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="InsertTags(\'[b]\',\'[/b]\', \'text_message\'); return false;">Ж</span>';
			echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="InsertTags(\'[i]\',\'[/i]\', \'text_message\'); return false;">К</span>';
			echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="InsertTags(\'[u]\',\'[/u]\', \'text_message\'); return false;">Ч</span>';
			echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="InsertTags(\'[s]\',\'[/s]\', \'text_message\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="InsertTags(\'[left]\',\'[/left]\', \'text_message\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="InsertTags(\'[center]\',\'[/center]\', \'text_message\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="InsertTags(\'[right]\',\'[/right]\', \'text_message\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="InsertTags(\'[justify]\',\'[/justify]\', \'text_message\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="InsertTags(\'[url]\',\'[/url]\', \'text_message\'); return false;">URL</span>';

			echo '<div style="display:block; clear:both; padding-top:4px">';
				echo '<textarea id="text_message" name="text_message" class="ok" style="height:150px; width:99%;" required="required">'.$notif_text_message.'</textarea>';
			echo '</div>';
			echo '<div style="padding:5px 0;">';
				echo '<b>Константы:</b> <b>%USER_NAME%</b> - будет заменено на логин пользователя; <b>%HTTP_HOST%</b> - будет заменено на <b>'.$_SERVER["HTTP_HOST"].'</b>.';
			echo '</div>';
		echo '</td>';
	echo '</tr>';
echo '</tbody>';
echo '</table>';

echo '<div align="center"><input id="SubMit" type="submit" value="Сохранить" class="sd_sub big green"></div>';

echo '</form></div>';

?>