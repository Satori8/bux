<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}

mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

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

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='1'");
$cena_mails[1] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='2'");
$cena_mails[2] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='3'");
$cena_mails[3] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
$cena_mails_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
$cena_mails_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
$cena_mails_gotosite = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_mails' AND `howmany`='1'");
$nacenka_mails = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_mails' AND `howmany`='1'");
$min_mails = mysql_result($sql,0,0);

?><script type="text/javascript" language="JavaScript">
function pay_adv(id, type) {
	var plan = $.trim($('#plan'+id).val());
	plan = str_replace(",", ".", plan);
	plan = str_replace("-", "", plan);
	plan = plan.replace(",", ".");
	rplan = parseInt(plan);

	if (plan == '') {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Введите необходимое количество просмотров</span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
		return false;
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
	} else if (rplan < <?=$min_mails;?>) {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество просмотров должно быть не менее <?=$min_mails;?></span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
		return false;
	} else {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv', 'type':type, 'id':id, 'plan':plan }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();

				if (data == "ERROR1") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество просмотров должно быть не менее <?=$min_mails;?></span>";
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
</script><?php

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		$cena_one = ($cena_mails[$row["tarif"]] + ($row["color"] * $cena_mails_color) + ($row["active"] * $cena_mails_active) + ($row["gotosite"] * $cena_mails_gotosite)) * ($nacenka_mails+100)/100;
		$cena_one = round(number_format($cena_one, 6, ".", ""), 6);

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0" | $row["status"]=="3") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["title"].'</a><br>';

				echo '<span class="info-text">';
					echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Клик:&nbsp;'.$cena_one.'&nbsp;руб.&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["outside"].'</span>';
				echo '</span>';

				echo '<span class="adv-dell" title="Удалить письмо" onClick="alert_delete('.$row["id"].', \'mails\');"></span>';
				echo '<a class="adv-statistics" href="?ads='.$ads.'&op=statistics&id='.$row["id"].'#goto" title="Статистика просмотра"></a>';
				echo '<span class="adv-erase" title="Сброс статистики" onClick="clear_stat('.$row["id"].', \'mails\', '.$row["outside"].');"></span>';
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'#goto" title="Редактировать письмо"></a>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0" | $row["status"]=="3") {
					echo '<a class="add-money-no" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">Пополнить</a>';
				}else{
					echo '<a class="add-money" title="Пополнить рекламный бюджет" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">'.$row["totals"].'</a>';
				}
			echo '</td>';
		echo '</tr>';

		echo '<tr id="adv_addmoney'.$row["id"].'" style="display: none">';

			?><script type="text/javascript" language="JavaScript">
			function obsch<?php echo $row["id"];?>(){
				var plan<?php echo $row["id"];?> = gebi('plan<?php echo $row["id"];?>').value;
				plan<?php echo $row["id"];?> = str_replace(",", ".", plan<?php echo $row["id"];?>);
				plan<?php echo $row["id"];?> = str_replace("-", "", plan<?php echo $row["id"];?>);

				var cena_one<?php echo $row["id"];?> = <?php echo $cena_one;?>;
				gebi('price<?php echo $row["id"];?>_1').innerHTML = number_format((plan<?php echo $row["id"];?> * cena_one<?php echo $row["id"];?>), 2, '.', ' ') + ' руб.';
				gebi('price<?php echo $row["id"];?>_2').innerHTML = number_format((plan<?php echo $row["id"];?> * cena_one<?php echo $row["id"];?> * <?php echo ((100-$cab_skidka)/100);?>), 2, '.', ' ') + ' руб.';
			}
			</script><?php

			echo '<td align="center" colspan="3" class="ext-text">';
				echo 'Укажите количество просмотров, которые вы хотите внести в бюджет рекламной площадки<br>(Минимум '.count_text($min_mails, "просмотров", "просмотр", "просмотра", "").')';
				echo '<input type="text" maxlength="10" id="plan'.$row["id"].'" value="" class="payadv" onChange="obsch'.$row["id"].'();" onKeyUp="obsch'.$row["id"].'();" />';
				echo 'Стоимость:';
				echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 руб.</span>';
				if($cab_status==1 && $cab_skidka>0) echo '<div style="color:#708090;">(с учетом вашей скидки '.$cab_skidka.'% это составит <span id="price'.$row["id"].'_2">0.00 руб.</span>)</div>';
				echo '<center><span onClick="pay_adv('.$row["id"].', \'mails\');" class="sub-green" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></center>';
				echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';
			echo '</td>';

		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">У вас нет своих размещённых писем</span>';
}

echo '<div align="center"><a class="sub-blue160" href="/advertise.php?ads=rek_mails" style="width:160px; margin-top:20px; float:none;">Разместить письмо</a></div>';
?>