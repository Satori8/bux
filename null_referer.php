<?php
$pagetitle="Выкуп";
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
require_once(DOC_ROOT."/.zsecurity.php");
include(DOC_ROOT."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	require(DOC_ROOT."/config.php");

	function my_status($my_reiting){
		$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$my_reiting' AND `r_do`>='".floor($my_reiting)."'");
		if(mysql_num_rows($sql_rang)>0) {
			$row_rang = mysql_fetch_array($sql_rang);
		}else{
			$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
			$row_rang = mysql_fetch_array($sql_rang);
		}
		return '<span style="cursor:pointer; color: #006699;" title="Статус">'.$row_rang["rang"].'</span>';
	}

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='null_ref_cena_min' AND `howmany`='1'");
	$null_ref_cena_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='null_ref_comis' AND `howmany`='1'");
	$null_ref_comis = mysql_result($sql,0,0);

	?><script type="text/javascript">
	function gebi(id){
		return document.getElementById(id)
	}

	function str_replace(search, replace, subject) {
		return subject.split(search).join(replace);
	}

	function null_referer() {
		var price_v = $.trim($("#price_v").val());
		price_v = str_replace(",", ".", price_v);
		price_v = price_v.replace(",", ".");
		rprice_v = parseFloat(price_v);

		if (price_v == '') {
			gebi("info-msg-nr").innerHTML = '<span class="msg-error">Укажите сумму выкупа!</span>';
			gebi("info-msg-nr").style.display = 'block';
			setTimeout(function() {$("#info-msg-nr").fadeOut('slow')}, 2000); clearTimeout();
		} else if (isNaN(price_v) | price_v != rprice_v) {
			gebi("info-msg-nr").innerHTML = '<span class="msg-error">Значение должно быть числовым!</span>';
			gebi("info-msg-nr").style.display = 'block';
			setTimeout(function() {$("#info-msg-nr").fadeOut('slow')}, 2000); clearTimeout();
		} else if (rprice_v < <?=$null_ref_cena_min;?>) {
			gebi("info-msg-nr").innerHTML = '<span class="msg-error">Минимальная сумма выкупа <?=$null_ref_cena_min;?> руб.</span>';
			gebi("info-msg-nr").style.display = 'block';
			setTimeout(function() {$("#info-msg-nr").fadeOut('slow')}, 2000); clearTimeout();
		} else {

			$.ajax({
				type: "POST", url: "/ajax/ajax_null_referer.php", data: { 'op':'new_applic', 'price_v':price_v }, 
				beforeSend: function() { $('#loading').show(); }, 
				success: function(data) { 
					$('#loading').hide();

					if (data == "OK") {
						alert("Ваша заявка успешно создана. Ожидайте ее рассмотрения вашим реферером!");
						document.location.href = "<?=$_SERVER["PHP_SELF"];?>";
					} else {
						gebi("info-msg-nr").innerHTML = '<span class="msg-error">' + data + '</span>';
						gebi("info-msg-nr").style.display = 'block';
						setTimeout(function() {$("#info-msg-nr").fadeOut('slow')}, 3000); clearTimeout();
					}
				}
			});
		}
	}

	function dell_applic(id) {
		$.ajax({
			type: "POST", url: "/ajax/ajax_null_referer.php", data: { 'op':'dell_applic', 'id':id }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();
				if (data == "OK") $("#tr-null"+id).hide();
				else alert(data);
			}
		});
	}

	function good_applic(id) {
		if (confirm("Вы уверены, что хотите одобрить заявку?")) {
			$.ajax({
				type: "POST", url: "/ajax/ajax_null_referer.php", data: { 'op':'good_applic', 'id':id }, 
				beforeSend: function() { $('#loading').show(); }, 
				success: function(data) { 
					$('#loading').hide();
					if (data == "OK") $("#tr-null"+id).hide();
					else alert(data);
				}
			});
		}
	}

	function bad_applic(id) {
		var comment = window.prompt("Укажите причину","");
		$.ajax({
			type: "POST", url: "/ajax/ajax_null_referer.php", data: { 'op':'bad_applic', 'id':id, 'comment':comment }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();
				if (data == "OK") $("#tr-null"+id).hide();
				else alert(data);
			}
		});
	}

	</script><?php

	echo '<div align="justify">';
		echo 'Здесь вы можете предложить выкуп себя у рефера, либо рассматривать заявки ваших рефералов на выкуп.<br>';
		echo 'После подачи заявки на выкуп, с вашего рекламного счёта будет списана указанная вами сумма на выкуп, минимальная сумма '.number_format($null_ref_cena_min, 2, ".", "").' руб., пока ваш реферер не одобрил заявку, вы всегда можете отозвать заявку и вернуть деньги, реферер получит уведомление по ВП, в случае отказа, отказавшему придёт уведомление.<br>';
		echo 'При подтверждении реферал удаляется у реферера и реферер получает сумму на основной счёт указанную рефералом за минусом '.number_format($null_ref_comis, 0, ".", "").' % комиссии системы.';
	echo '</div><br><br>';

	echo '<table class="tables" style="border:2px solid #CCCCCC;">';
	echo '<tr>';
		echo '<td align="center" width="180"><b>Укажите сумму выкупа</b></td>';
		echo '<td align="center"><div id="newform"><input type="text" id="price_v" value="" class="ok" style="text-align:center;" /></div></td>';
		echo '<td align="center" width="165"><span onClick="null_referer();" class="proc-btn" style="float:none; width:160px;">Сделать предложение</span></td>';
	echo '</tr>';
	echo '</table>';
	echo '<br>';
	echo '<div id="info-msg-nr"></div><br>';

	echo '<table class="tables">';
	echo '<thead><tr align="center">';
		echo '<th colspan="3">Информация</th>';
		echo '<th>Дата</th>';
		echo '<th>Сумма выкупа</th>';
		echo '<th>Заявка</th>';
	echo '</tr></thead>';

	$sql_z = mysql_query("SELECT * FROM `tb_null_referer` WHERE `status`='0' AND (`username`='$username' OR `referer`='$username')");
	if(mysql_num_rows($sql_z)>0) {
		while ($row_z = mysql_fetch_array($sql_z)) {

			$sql_us = mysql_query("SELECT `id`,`username`,`reiting`,`avatar` FROM `tb_users` WHERE `username`='".$row_z["username"]."'");
			if(mysql_num_rows($sql_us)>0) {
				$row_us = mysql_fetch_row($sql_us);

				echo '<tr align="center" id="tr-null'.$row_z["id"].'">';
				echo '<td width="50px" style="border-right:none;">';
					echo '<img class="avatar" src="/avatar/'.$row_us["3"].'" width="50" height="50" border="0" alt="" title="avatar" />';
				echo '</td>';

				echo '<td style="border-left:none; border-right:none;" align="left">';
					echo '<span style="color: #808080;">ID:</span> <b>'.$row_us["0"].'</b>,&nbsp;';
					echo '<a href="/wall.php?uid='.$row_us["0"].'" title="Перейти на стену пользователя '.$row_us["1"].'" target="_blank"><span style="text-shadow: 1px 1px 1px #CCCCCC;"><b>'.$row_us["1"].'</b></span></a><br>';

					if($row_us["2"]<0) echo 'Рейтинг: <span style="color: #FF0000;">'.$row_us["2"].'</span>';
					else echo 'Рейтинг: <span style="cursor:pointer; color: #34A305;" title="Рейтинг">+'.$row_us["2"].'</span><br>';

					echo my_status($row_us["2"]);
				echo '</td>';

				echo '<td style="border-left:none;" align="left" width="20">';
					$token_ajax = md5($username.$row_us["1"].$row_us["0"]."token2205");
					echo '<span onclick="ShowHideStats(\''.$row_us["0"].'\', \''.$token_ajax.'\')" style="cursor: pointer;"><img src="/img/icon-stats.png" border="0" alt="" style="margin:0; padding:0;" align="absmiddle" title="Посмотреть расширенную статистику пользователя '.$row_us["1"].'" /></span><br>';
					echo '<a href="newmsg.php?name='.$row_us["1"].'"><img src="img/mail.gif" title="Написать сообщение пользователю '.$row_us["1"].'" border="0" align="absmiddle" /></a>';
				echo '</td>';

				echo '<td>';
					if(DATE("d.m.Y")==DATE("d.m.Y", $row_z["date_time"])) {
						echo '<span style="color: #009126;">Сегодня, в '.DATE("H:i", $row_z["date_time"]).'</span>';
					}else{
						echo DATE("d.m.Y в H:i", $row_z["date_time"]);
					}
				echo '</td>';

				echo '<td>'.$row_z["money"].' руб.</td>';

				echo '<td width="80">';
					if(strtolower($row_z["username"])==strtolower($username)) {
						echo '<span class="sub-orange" onClick="dell_applic(\''.$row_z["id"].'\');" style="float:none;">Отозвать</span>';

					}elseif(strtolower($row_z["referer"])==strtolower($username)) {
						echo '<span class="proc-btn" onClick="good_applic(\''.$row_z["id"].'\');" style="float:none;">Одобрить</span>';
						echo '<span class="sub-red" onClick="bad_applic(\''.$row_z["id"].'\');" style="float:none;">Отклонить</span>';
					}else{
						echo '';
					}
				echo '</td>';

				echo '</tr>';

				echo '<tr align="center"><td colspan="6" id="usersstat'.$row_us["0"].'" style="display: none;"></td></tr>';
			}
		}
	}else{
		echo '<tr><td align="center" colspan="6"><b>Заявок нет</b></td></tr>';
	}
	echo '</table>';
}

include('footer.php');?>