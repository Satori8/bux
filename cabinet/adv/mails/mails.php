<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}
mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date_end`='".time()."' WHERE `status`>'0'  AND (`totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());

?><script type="text/javascript" language="JavaScript">
function GoTop() {
	$("html, body").animate({scrollTop: $("#block-page").offset().top-35}, 700);
}
GoTop();
</script><?php

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_hit' AND `howmany`='1'");
$mails_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nacenka' AND `howmany`='1'");
$mails_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_min_hits' AND `howmany`='1'");
$mails_min_hits = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_color' AND `howmany`='1'");
$mails_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_active' AND `howmany`='1'");
$mails_cena_active = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_ot' AND `howmany`='1'");
$mails_timer_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_do' AND `howmany`='1'");
$mails_timer_do = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_timer' AND `howmany`='1'");
$mails_cena_timer = number_format(mysql_result($sql,0,0), 4, ".", "");

for($i=1; $i<=2; $i++) {
	$mails_cena_revisit[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_revisit' AND `howmany`='$i'");
	$mails_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

for($i=1; $i<=2; $i++) {
	$mails_cena_unic_ip[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_unic_ip' AND `howmany`='$i'");
	$mails_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_gotosite' AND `howmany`='1'");
$mails_cena_gotosite = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_uplist' AND `howmany`='1'");
$mails_cena_uplist = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_status' AND `howmany`='1'");
$mails_nolimit_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_cena' AND `howmany`='1'");
$mails_nolimit_cena = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nolimit_timer' AND `howmany`='1'");
$mails_nolimit_timer = number_format(mysql_result($sql,0,0), 0, ".", "");


if(isset($ads) && isset($id) && isset($op) && $ads=="mails" && $op=="edit" && $id>0) {
	if(!DEFINED("MAILS_EDIT")) DEFINE("MAILS_EDIT", true);
	include("mails_edit.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

if(isset($ads) && isset($id) && isset($op) && $ads=="mails" && $op=="statistics" && $id>0) {
	if(!DEFINED("MAILS_STAT")) DEFINE("MAILS_STAT", true);

	include(DOC_ROOT."/geoip/geoipcity.inc");
	include(DOC_ROOT."/geoip/geoipregionvars.php");
	$gi = geoip_open(DOC_ROOT."/geoip/GeoLiteCity.dat",GEOIP_STANDARD);

	include("mails_stat.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои рекламные письма</h5>';

?><script type="text/javascript" language="JavaScript">

function pay_adv(id, type, bezlimit) {
	var plan = $.trim($('#plan'+id).val());
	plan = str_replace(",", ".", plan);
	plan = str_replace("-", "", plan);
	plan = plan.replace(",", ".");
	rplan = parseInt(plan);

	if (plan == '') {
		if(bezlimit == 1) {
			gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Введите количество дней равное 30</span>";
			gebi("imfo-msg-addmoney"+id).style.display = 'block';
			setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 4000); clearTimeout();
			return false;
		}else{
			gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Введите необходимое количество просмотров</span>";
			gebi("imfo-msg-addmoney"+id).style.display = 'block';
			setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
			return false;
		}
	} else if (isNaN(plan)) {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Значение должно быть числовым</span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
		return false;
	} else if (plan != rplan) {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Значение должно быть целым числом</span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
		return false;
	} else {
		if(bezlimit == 1) {
			if (rplan != 30) {
				gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество дней должно быть равное 30</span>";
				gebi("imfo-msg-addmoney"+id).style.display = 'block';
				setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 4000); clearTimeout();
				return false;
			}
		} else {
			if (rplan < <?=$mails_min_hits;?>) {
				gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество просмотров должно быть не менее <?=$mails_min_hits;?></span>";
				gebi("imfo-msg-addmoney"+id).style.display = 'block';
				setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
				return false;
			}
		}
		

		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv', 'type':type, 'id':id, 'plan':plan }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();

				if (data == "ERROR1") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество просмотров должно быть не менее <?=$mails_min_hits;?></span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
					return false;
				} else if (data == "ERROR2") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>На вашем рекламном счету недостаточно средств!</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
					return false;
				} else if (data == "OK") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-ok'>Бюджет рекламной площадки успешно пополнен!</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {
						$("#imfo-msg-addmoney"+id).fadeOut('slow'); 
						document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
					}, 1000); clearTimeout();
					return false;
				}else {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-w'>Не удалось обработать запрос " + data + "!</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					return false;
				}
			}
		});
	}

}

function pay_adv_up(id, type) {
	$.ajax({
		type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv_up', 'type':type, 'id':id}, 
		beforeSend: function() { $('#loading').show(); }, 
		success: function(data) { 
			$('#loading').hide();
			if (data == "OK") document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
		}
	});
}
</script><?php

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		$cena_one = ( $mails_cena_hit + ($mails_cena_timer * ($row["timer"] - $mails_timer_ot)) + ($row["active"] * $mails_cena_active) + ($row["color"] * $mails_cena_color) + $mails_cena_revisit[$row["revisit"]] + $mails_cena_unic_ip[$row["unic_ip"]] + ($row["gotosite"] * $mails_cena_gotosite) );
		$cena_one = round(number_format($cena_one, 6, ".", ""), 6);

		if($row["status"]==1 && $row["up_list"]>0) {
			$position = mysql_result(mysql_query( "SELECT COUNT(*) FROM `tb_ads_mails` WHERE `status`='1' AND `date_up`>(SELECT `date_up` FROM `tb_ads_mails` WHERE `id`='".$row["id"]."')" ),0,0)+1;
		}else{
			unset($position);
		}

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0" | $row["status"]=="3") {
						if($row["nolimit"]!=0) {
							echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart_rc();"></span>';
						}else{
							echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
						}
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
					
					}elseif($row["status"]=="4") {
						echo '<span class="adv-block" title="Рекламная площадка заблокирована" onClick="alert(\'Рекламная площадка заблокирована. Необходимо внести изменения!\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["title"].'</a><br>';

				echo '<span class="info-text">';
					if($row["nolimit"]!=0) {
						echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Безлимит&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["outside"].'</span>';
					}else{
						echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Клик:&nbsp;'.$cena_one.'&nbsp;руб.&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["outside"].'</span>';
					}
					if($row["claims"] > 0 ) {
						echo '&nbsp;&nbsp;<span id="claims-'.$row["id"].'" onClick="ViewClaims(\''.$row["id"].'\', \'tests\', \'ViewClaims\');" title="Просмотреть жалобы, поданные пользователями проекта" style="color:#FF0000; cursor:pointer;">Жалобы:&nbsp;<b>'.number_format($row["claims"], 0, ".", "`").'</b></span>';
					}
				echo '</span>';
				
				if($row["status"]=="4") {
					echo '<div class="warning-info" style="font-weight:normal;"><span style="color:red">'.$row["msg_lock"].'</span><br>После внесения изменений сообщите об этом администрации по <a href="/newmsg.php?name=Admin&subject=Разблокировка письма №'.$row["id"].'" target="_blank" title="Написать сообщение администрации">внутренней почте</a> для разблокировки. <u>Не забудьте указать № Рекламного письма.</u></div>';
				}

				echo '<span class="adv-dell" title="Удалить письмо" onClick="alert_delete('.$row["id"].', \'mails\');"></span>';
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'" title="Редактировать письмо"></a>';

				if($row["status"]==1) {
					if(isset($position) && $position>0 && $position<100) echo '<span class="adv-down" title="Позиция ссылки в списке серфинга: '.$position.'" onClick="show_up_list(\'advup'.$row["id"].'\');">'.$position.'</span>';
					else echo '<span class="adv-up" title="Поднять в списке" onClick="show_up_list(\'advup'.$row["id"].'\');">&uarr;</span>';
				}

				echo '<a class="adv-statistics" href="?ads='.$ads.'&op=statistics&id='.$row["id"].'" title="Статистика просмотра"></a>';
				echo '<span class="adv-erase" title="Сброс статистики" onClick="clear_stat('.$row["id"].', \'mails\', '.$row["outside"].');"></span>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0" | $row["status"]=="3") {
					if($row["nolimit"]!=0) {
						echo '<a class="add-money-no" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">Оплатить</a>';
					}else{
						echo '<a class="add-money-no" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">Пополнить</a>';
					}
				}else{
					if($row["nolimit"]!=0) {
						echo '<a class="add-money" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">'.adv_date_ost(ceil($row["nolimit"]-time())).'</a>';
					}else{
						echo '<a class="add-money" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">'.$row["totals"].'</a>';
					}
				}
			echo '</td>';
		echo '</tr>';

		echo '<tr id="advup'.$row["id"].'" style="display: none">';
			echo '<td align="center" colspan="3" class="ext-text">';
				echo '<div align="justify" style="margin:auto 10px;">';
				echo 'Вы можете разместить свою ссылку выше остальных в списке оплачиваемы писем. Ссылка будет сохранять свою позицию до тех пор, пока другой пользователь не поднимет свою ссылку. ';
				echo 'В таком случае ваша ссылка будет опускаться ниже. В связи с этим, невозможно однозначно сказать, как долго ваша ссылка продержится на первых позициях. Стоимость поднятия составляет <b>'.$mails_cena_uplist.'</b> руб. Эта сумма будет снята с рекламного счета.<br /><br />';
				echo '</div>';
				echo '<center><span onClick="pay_adv_up('.$row["id"].', \'mails\');" class="sub-green92" style="float:none;" title="Поднять">Поднять</span></center>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="adv_addmoney'.$row["id"].'" style="display: none">';

			?><script type="text/javascript" language="JavaScript">
			function PlanChange<?php echo $row["id"];?>(bezlimit){
				var plan<?php echo $row["id"];?> = gebi('plan<?php echo $row["id"];?>').value;
				plan<?php echo $row["id"];?> = str_replace(",", ".", plan<?php echo $row["id"];?>);
				plan<?php echo $row["id"];?> = str_replace("-", "", plan<?php echo $row["id"];?>);

				if (bezlimit == 1) {
					if(plan<?php echo $row["id"];?> == 30) {
						var cena_link<?php echo $row["id"];?> = <?php echo $mails_nolimit_cena;?>;
					} else {
						gebi("imfo-msg-addmoney"+<?php echo $row["id"];?>).innerHTML = "<span class='msg-error'>Количество дней должно быть равное 30</span>";
						gebi("imfo-msg-addmoney"+<?php echo $row["id"];?>).style.display = 'block';
						return false;
					}

					gebi('price<?php echo $row["id"];?>_1').innerHTML = number_format(cena_link<?php echo $row["id"];?>, 2, '.', ' ') + ' руб.';
					gebi('price<?php echo $row["id"];?>_2').innerHTML = number_format((cena_link<?php echo $row["id"];?> * <?php echo ((100-$cab_skidka)/100);?>), 2, '.', ' ') + ' руб.';
				} else {
					var cena_link<?php echo $row["id"];?> = <?php echo $cena_one;?>;
					gebi('price<?php echo $row["id"];?>_1').innerHTML = number_format((plan<?php echo $row["id"];?> * cena_link<?php echo $row["id"];?>), 2, '.', ' ') + ' руб.';
					gebi('price<?php echo $row["id"];?>_2').innerHTML = number_format((plan<?php echo $row["id"];?> * cena_link<?php echo $row["id"];?> * <?php echo ((100-$cab_skidka)/100);?>), 2, '.', ' ') + ' руб.';
				}
			}
			</script><?php

			echo '<td align="center" colspan="3" class="ext-text">';
				if($row["nolimit"]!=0) {
					echo '<b>Вы оплачиваете безлимитную рекламу.</b><br>Количество дней, которое вы вносите в бюджет рекламной площадки<br>';
					echo '<input type="text" maxlength="10" id="plan'.$row["id"].'" value="30" class="payadv" onChange="PlanChange'.$row["id"].'(1);" onKeyUp="PlanChange'.$row["id"].'(1);" readonly="readonly" />';
					echo 'Стоимость:';
					echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 руб.</span>';
					if($cab_status==1 && $cab_skidka>0) echo '<div style="color:#708090;">(с учетом вашей скидки '.$cab_skidka.'% это составит <span id="price'.$row["id"].'_2">0.00 руб.</span>)</div>';
					echo '<center><span onClick="pay_adv('.$row["id"].', \'mails\', 1);" class="sub-orange92" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></center>';
					echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';

					?><script language="JavaScript">PlanChange<?php echo $row["id"];?>(1);</script><?php
				}else{
					echo 'Укажите количество просмотров, которые вы хотите внести в бюджет рекламной площадки<br>(Минимум '.count_text($mails_min_hits, "просмотров", "просмотр", "просмотра", "").')';
					echo '<input type="text" maxlength="10" id="plan'.$row["id"].'" value="" class="payadv" onChange="PlanChange'.$row["id"].'(0);" onKeyUp="PlanChange'.$row["id"].'(0);" />';
					echo 'Стоимость:';
					echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 руб.</span>';
					if($cab_status==1 && $cab_skidka>0) echo '<div style="color:#708090;">(с учетом вашей скидки '.$cab_skidka.'% это составит <span id="price'.$row["id"].'_2">0.00 руб.</span>)</div>';
					echo '<center><span onClick="pay_adv('.$row["id"].', \'mails\', 0);" class="sub-orange92" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></center>';
					echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';
				}
			echo '</td>';

		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">У вас нет своих размещённых писем</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=rek_mails\'">Разместить письмо</span></div>';
?>