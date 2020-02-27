<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}
require_once(DOC_ROOT."/bbcode/bbcode.lib.php");

mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `status`='3' AND `balance`>=`cena_advs`");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_comis_del' AND `howmany`='1'");
$tests_comis_del = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_min_pay' AND `howmany`='1'");
$tests_min_pay = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_quest' AND `howmany`='1'");
$tests_cena_quest = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_color' AND `howmany`='1'");
$tests_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

for($i=1; $i<=4; $i++) {
	$tests_cena_revisit[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_revisit' AND `howmany`='$i'");
	$tests_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

for($i=1; $i<=2; $i++) {
	$tests_cena_unic_ip[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_unic_ip' AND `howmany`='$i'");
	$tests_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

if(isset($ads) && isset($id) && isset($op) && $ads=="tests" && $op!=false) {

	if($op=="edit" && $id>0) {
		if(!DEFINED("TESTS_EDIT")) DEFINE("TESTS_EDIT", true);
		include("tests_edit.php");

	}elseif($op=="statistics" && $id>0) {
		if(!DEFINED("TESTS_STAT")) DEFINE("TESTS_STAT", true);
		include("tests_stat.php");

	}elseif($op=="blacklist") {
		if(!DEFINED("TESTS_BL")) DEFINE("TESTS_BL", true);
		include("tests_blacklist.php");

	}else{
		echo '<span class="msg-error">ERROR</span>';
	}
	include(DOC_ROOT."/footer.php");
	exit();
}

?><script type="text/javascript" language="JavaScript">

function GoTopTest() {
	$("html, body").animate({scrollTop: $("#TopTest").offset().top-410}, 700);
}

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
			type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), data: { 'op':op, 'type':'tests', 'id':id }, 
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
						if(data.message) { $("#mess-info"+id).html(data.message); }
						else { $("#mess-info"+id).html('<span class="msg-error">Ошибка обработки данных!</span>'); }
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

function PayAds(id, type){
	var money_add = $.trim($("#money_add").val());
	money_add = str_replace(",", ".", money_add);
	money_add = money_add.match(/(\d+(\.)?(\d){0,2})?/);
	money_add = money_add[0] ? money_add[0] : '';
	$("#money_add").val(money_add);
	money_add = number_format(money_add, 2, ".", "");

	if (id != undefined && type != undefined) {
		if (money_add < <?=$tests_min_pay;?>) {
			$("#info-msg-addmoney").show();
			$("#info-msg-addmoney").html('<span class="msg-error">Минимальная сумма пополнения - <?=$tests_min_pay;?> руб.</span>');
			setTimeout(function() {$("#info-msg-addmoney").fadeOut("slow")}, 3000); clearTimeout();
			return false;
		} else {
			$.ajax({
				type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), 
				data: {'op':'pay', 'type':type, 'id':id, 'money_add':money_add}, 
				dataType: 'json',
				beforeSend: function() { $("#loading").slideToggle(); }, 
				error: function() {
					$("#loading").slideToggle();
					$("#info-msg-addmoney").show();
					$("#info-msg-addmoney").html('<span class="msg-error">Ошибка обработки данных! Сообщите Администрации сайта.</span>');
					setTimeout(function() {$("#info-msg-addmoney").fadeOut("slow")}, 10000); clearTimeout();
					return false;
				}, 
				success: function(data) {
					$("#loading").slideToggle();

					if (data.result == "OK") {
						$("#info-msg-addmoney").show();
						$("#S_H_M"+id).attr("class", "add-money");
						if(data.status) $("#playpauseimg"+id).html(data.status); 
						if(data.totals) $("#count_totals"+id).html(data.totals); 
						if(data.balance) $("#count_balance"+id).html(data.balance); 
						if(data.goods_out) $("#g_stat"+id).html(data.goods_out); 
						if(data.bads_out) $("#b_stat"+id).html(data.bads_out); 

						$("#info-msg-addmoney").html('<span class="msg-ok">Бюджет рекламной площадки успешно пополнен!</span>');

						setTimeout(function() {
							$("#info-msg-addmoney").hide();
							$("#S_H_M"+id).click();
							//document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=$ads";?>";
						}, 1000);

						return false;
					} else {
						if(data.message) {
							$("#info-msg-addmoney").show();
							$("#info-msg-addmoney").html('<span class="msg-error">' + data.message + '</span>');
							setTimeout(function() {$("#info-msg-addmoney").fadeOut("slow")}, 5000); clearTimeout();
							return false;
						} else {
							$("#info-msg-addmoney").show();
							$("#info-msg-addmoney").html('<span class="msg-error">Ошибка обработки данных!</span>');
							setTimeout(function() {$("#info-msg-addmoney").fadeOut("slow")}, 5000); clearTimeout();
							return false;
						}
					}
				}
			});
		}
	}
}

function ViewClaims(id, type, op) {
	$.ajax({
		type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), 
		data: { 'op': op, 'type': type, 'id': id }, 
		dataType: 'json', 
		success: function(data) {
			if (data.result=="OK" && data.message) {
				$("#LoadModal").html(data.message).show();
				StartModal();
				WinHeight = Math.ceil($.trim($(window).height()));
				ModalHeight = Math.ceil($.trim($("#table-content").height()));
				if((ModalHeight+100) >= WinHeight) ModalHeight = WinHeight-100;
				$("#table-content").css("height", ModalHeight+"px");
			}else{
				alert(data.message);
			}
		}
	});
}

function StartModal() {
	$("#LoadModal").modalpopup({
		closeOnEsc: false,
		closeOnOverlayClick: false,
		beforeClose: function(data, el) {
			$("#LoadModal").hide();
			return true;
		}
	});
}

</script><?php

echo '<table class="tables">';
echo '<tr><td align="center" style="background:#F0F8FF; border:none; padding:10px; color:#E32636; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);">При удалении тестов (возврате средств) система взымает '.$tests_comis_del.'% от возвращаемой суммы.<br>Суммы менее 1.00 руб. не возвращаются.<br>При удалении заблокированной площадки средства не возвращаются!!!</td></tr>';
echo '</table><br>';

echo '<div id="TopTest"></div>';

echo '<div align="center"><a class="sub-black" href="?ads='.$ads.'&op=blacklist" style="float:none;">Чёрный список</a></div><br>';

echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои тесты</h5>';

$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `username`='$username' ORDER BY `id` DESC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab" id="newform">';

	while ($row = mysql_fetch_array($sql)) {

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1" valign="top" style="padding-top:10px;">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="PlayPause('.$row["id"].', \'tests\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="PlayPause('.$row["id"].', \'tests\');"></span>';
					}elseif($row["status"]=="3") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="4") {
						echo '<span class="adv-block" title="Рекламная площадка заблокирована" onClick="alert(\'Рекламная площадка заблокирована. Необходимо внести изменения!\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				//echo '<span class="title-text">'.$row["title"].'</span><br>';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank" title="'.$row["url"].'"><span class="title-text">'.$row["title"].'</span></a><br>';

				echo '<span class="info-text">';
					echo '№&nbsp;'.$row["id"].'&nbsp;&nbsp;Цена за тест:&nbsp;'.$row["cena_advs"].'&nbsp;руб.&nbsp;&nbsp;';
					echo 'Осталось:&nbsp;<span id="count_totals'.$row["id"].'">'.number_format(floor(bcdiv($row["balance"],$row["cena_advs"])), 0, ".", "`").'</span></br>';
					echo 'Пройдено:&nbsp;<span id="g_stat'.$row["id"].'" title="Пройдено">'.number_format($row["goods_out"], 0, ".", "`").'</span>&nbsp;|&nbsp;';
					echo 'Провалено:&nbsp;<span id="b_stat'.$row["id"].'" title="Провалено">'.number_format($row["bads_out"], 0, ".", "`").'</span>';
					if($row["claims"] > 0 ) {
						echo '&nbsp;&nbsp;<span id="claims-'.$row["id"].'" onClick="ViewClaims(\''.$row["id"].'\', \'tests\', \'ViewClaims\');" title="Просмотреть жалобы, поданные пользователями проекта" style="color:#FF0000; cursor:pointer;">Жалобы:&nbsp;<b>'.number_format($row["claims"], 0, ".", "`").'</b></span>';
					}
				echo '</span>';

				if($row["status"]=="4") {
					echo '<div class="warning-info" style="font-weight:normal;">'.$row["msg_lock"].'<br>После внесения изменений сообщите об этом администрации по <a href="/newmsg.php?name=Admin&subject=Разблокировка теста №'.$row["id"].'" target="_blank" title="Написать сообщение администрации">внутренней почте</a> для разблокировки. <u>Не забудьте указать № теста.</u></div>';
				}

				echo '<span class="adv-dell" title="Удалить тест" onClick="DelAds('.$row["id"].', \'tests\');"></span>';
				echo '<span class="adv-edit" title="Редактировать тест" onClick="LoadInfo('.$row["id"].', \'go_edit\');"></span>';
				echo '<span class="adv-erase" title="Сброс статистики" onClick="ClearStat('.$row["id"].', \'tests\', '.$row["goods_out"].', '.$row["bads_out"].');"></span>';
				echo '<a class="adv-statistics" href="?ads='.$ads.'&op=statistics&id='.$row["id"].'" title="Список исполнителей"></a>';
				echo '<span class="adv-info" title="Посмотреть подробное описание" onClick="LoadInfo('.$row["id"].', \'get_info\');"></span>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0" | $row["balance"]==0) {
					echo '<a id="S_H_M'.$row["id"].'" class="add-money-no" title="Пополнить рекламный бюджет" onClick="LoadInfo('.$row["id"].', \'get_add_money\');">';
						echo '<span id="count_balance'.$row["id"].'">Пополнить</span>';
					echo '</a>';
				}else{
					echo '<a id="S_H_M'.$row["id"].'" class="add-money" title="Пополнить рекламный бюджет" onClick="LoadInfo('.$row["id"].', \'get_add_money\');">';
						echo '<span id="count_balance'.$row["id"].'">'.( ($row["balance"]<1 && $row["balance"]!=0) ? number_format($row["balance"], 4, ".", "`") : number_format($row["balance"], 2, ".", "`") ).'</span>';
					echo '</a>';
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

	?><script language="JavaScript">GoTopTest();</script><?php
}else{
	echo '<span class="msg-w">У вас нет своих размещённых тестов</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads='.$ads.'\'">Разместить тест</span></div>';
?>