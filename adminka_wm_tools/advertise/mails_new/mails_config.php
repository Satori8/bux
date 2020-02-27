<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0; border:none;"><b>Настройки оплачиваемых писем</b></h3>';

if(count($_POST)>0) {
	$mails_cena_hit = (isset($_POST["cena_hit"]) && floatval(abs(trim($_POST["cena_hit"])))>0) ? number_format(floatval(abs(trim($_POST["cena_hit"]))),4,".","") : 0;
	$mails_cena_active = (isset($_POST["cena_active"]) && floatval(abs(trim($_POST["cena_active"])))>0) ? number_format(floatval(abs(trim($_POST["cena_active"]))),4,".","") : 0;
	$mails_cena_timer = (isset($_POST["cena_timer"]) && floatval(abs(trim($_POST["cena_timer"])))>0) ? number_format(floatval(abs(trim($_POST["cena_timer"]))),4,".","") : 0;
	$mails_timer_ot = (isset($_POST["timer_ot"]) && intval(trim($_POST["timer_ot"]))>0) ? number_format(intval(trim($_POST["timer_ot"])),0,".","") : 0;
	$mails_timer_do = (isset($_POST["timer_do"]) && intval(trim($_POST["timer_do"]))>0) ? number_format(intval(trim($_POST["timer_do"])),0,".","") : 0;
	$mails_cena_gotosite = (isset($_POST["cena_gotosite"]) && floatval(abs(trim($_POST["cena_gotosite"])))>0) ? number_format(floatval(abs(trim($_POST["cena_gotosite"]))),4,".","") : 0;
	$mails_cena_uplist = (isset($_POST["cena_uplist"]) && floatval(abs(trim($_POST["cena_uplist"])))>0) ? number_format(floatval(abs(trim($_POST["cena_uplist"]))),2,".","") : 0;
	$mails_cena_color = (isset($_POST["cena_color"]) && floatval(abs(trim($_POST["cena_color"])))>0) ? number_format(floatval(abs(trim($_POST["cena_color"]))),4,".","") : 0;
	$mails_cena_unic_ip[1] = (isset($_POST["cena_unic_ip_1"]) && floatval(abs(trim($_POST["cena_unic_ip_1"])))>0) ? number_format(floatval(abs(trim($_POST["cena_unic_ip_1"]))),4,".","") : 0;
	$mails_cena_unic_ip[2] = (isset($_POST["cena_unic_ip_2"]) && floatval(abs(trim($_POST["cena_unic_ip_2"])))>0) ? number_format(floatval(abs(trim($_POST["cena_unic_ip_2"]))),4,".","") : 0;
	$mails_cena_revisit[1] = (isset($_POST["cena_revisit_1"]) && floatval(abs(trim($_POST["cena_revisit_1"])))>0) ? number_format(floatval(abs(trim($_POST["cena_revisit_1"]))),4,".","") : 0;
	$mails_cena_revisit[2] = (isset($_POST["cena_revisit_2"]) && floatval(abs(trim($_POST["cena_revisit_2"])))>0) ? number_format(floatval(abs(trim($_POST["cena_revisit_2"]))),4,".","") : 0;
	$mails_min_hits = (isset($_POST["min_hits"]) && intval(trim($_POST["min_hits"]))>0) ? number_format(intval(trim($_POST["min_hits"])),0,".","") : 0;
	$mails_nacenka = (isset($_POST["nacenka"]) && floatval(abs(trim($_POST["nacenka"])))>0) ? number_format(floatval(abs(trim($_POST["nacenka"]))),0,".","") : 0;
	$mails_nolimit_status = (isset($_POST["nolimit_status"]) && intval(trim($_POST["nolimit_status"]))>0) ? number_format(intval(trim($_POST["nolimit_status"])),0,".","") : 0;
	$mails_nolimit_cena = (isset($_POST["nolimit_cena"]) && floatval(abs(trim($_POST["nolimit_cena"])))>0) ? number_format(floatval(abs(trim($_POST["nolimit_cena"]))),2,".","") : 0;
	$mails_nolimit_timer = (isset($_POST["nolimit_timer"]) && intval(trim($_POST["nolimit_timer"]))>0) ? number_format(intval(trim($_POST["nolimit_timer"])),0,".","") : 0;

	if($mails_timer_ot>$mails_timer_do) {
		$mails_timer_do_n = $mails_timer_ot;
		$mails_timer_ot_n = $mails_timer_do;
		$mails_timer_ot = $mails_timer_ot_n;
		$mails_timer_do = $mails_timer_do_n;
	}

	if($mails_cena_hit==0) {
		echo '<span id="info-msg" class="msg-error">Цена за тест не может быть равна 0</span>';
	}elseif($mails_nolimit_timer>$mails_timer_do | $mails_nolimit_timer<$mails_timer_ot) {
		echo '<span id="info-msg" class="msg-error">Таймер для безлимита должен быть в пределах от '.$mails_timer_ot.' до '.$mails_timer_do.' сек.</span>';
	}else{
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_hit' WHERE `item`='mails_cena_hit' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_active' WHERE `item`='mails_cena_active' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_timer' WHERE `item`='mails_cena_timer' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_timer_ot' WHERE `item`='mails_timer_ot' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_timer_do' WHERE `item`='mails_timer_do' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_gotosite' WHERE `item`='mails_cena_gotosite' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_uplist' WHERE `item`='mails_cena_uplist' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_color' WHERE `item`='mails_cena_color' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_unic_ip[1]' WHERE `item`='mails_cena_unic_ip' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_unic_ip[2]' WHERE `item`='mails_cena_unic_ip' AND `howmany`='2'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_revisit[1]' WHERE `item`='mails_cena_revisit' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_cena_revisit[2]' WHERE `item`='mails_cena_revisit' AND `howmany`='2'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_min_hits' WHERE `item`='mails_min_hits' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_nacenka' WHERE `item`='mails_nacenka' AND `howmany`='1'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$mails_nolimit_status' WHERE `item`='mails_nolimit_status' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_nolimit_cena' WHERE `item`='mails_nolimit_cena' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$mails_nolimit_timer' WHERE `item`='mails_nolimit_timer' AND `howmany`='1'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok">Изменения успешно сохранены!</span>';
	}

	echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 3000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

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

?>

<script type="text/javascript">
function PlanChange(){
	var nacenka = $.trim($("#nacenka").val());
	var timer_ot = $.trim($("#timer_ot").val());
	var timer_do = $.trim($("#timer_do").val());
	var cena_hit = $.trim($("#cena_hit").val());
	var cena_active = $.trim($("#cena_active").val());
	var cena_timer = $.trim($("#cena_timer").val());
	var cena_gotosite = $.trim($("#cena_gotosite").val());
	var cena_uplist = $.trim($("#cena_uplist").val());
	var cena_color = $.trim($("#cena_color").val());
	var cena_unic_ip_1 = $.trim($("#cena_unic_ip_1").val());
	var cena_unic_ip_2 = $.trim($("#cena_unic_ip_2").val());
	var cena_revisit_1 = $.trim($("#cena_revisit_1").val());
	var cena_revisit_2 = $.trim($("#cena_revisit_2").val());

	nacenka = str_replace(",", ".", nacenka).match(/(\d+)?/);
	nacenka = nacenka[0] ? nacenka[0] : ''; $("#nacenka").val(nacenka);

	cena_hit = str_replace(",", ".", cena_hit).match(/(\d+(\.)?(\d){0,4})?/);
	cena_hit = cena_hit[0] ? cena_hit[0] : ''; $("#cena_hit").val(cena_hit);

	cena_active = str_replace(",", ".", cena_active).match(/(\d+(\.)?(\d){0,4})?/);
	cena_active = cena_active[0] ? cena_active[0] : ''; $("#cena_active").val(cena_active);

	cena_timer = str_replace(",", ".", cena_timer).match(/(\d+(\.)?(\d){0,4})?/);
	cena_timer = cena_timer[0] ? cena_timer[0] : ''; $("#cena_timer").val(cena_timer);

	cena_gotosite = str_replace(",", ".", cena_gotosite).match(/(\d+(\.)?(\d){0,4})?/);
	cena_gotosite = cena_gotosite[0] ? cena_gotosite[0] : ''; $("#cena_gotosite").val(cena_gotosite);

	cena_uplist = str_replace(",", ".", cena_uplist).match(/(\d+(\.)?(\d){0,2})?/);
	cena_uplist = cena_uplist[0] ? cena_uplist[0] : ''; $("#cena_uplist").val(cena_uplist);

	cena_color = str_replace(",", ".", cena_color).match(/(\d+(\.)?(\d){0,4})?/);
	cena_color = cena_color[0] ? cena_color[0] : ''; $("#cena_color").val(cena_color);

	cena_unic_ip_1 = str_replace(",", ".", cena_unic_ip_1).match(/(\d+(\.)?(\d){0,4})?/);
	cena_unic_ip_1 = cena_unic_ip_1[0] ? cena_unic_ip_1[0] : ''; $("#cena_unic_ip_1").val(cena_unic_ip_1);

	cena_unic_ip_2 = str_replace(",", ".", cena_unic_ip_2).match(/(\d+(\.)?(\d){0,4})?/);
	cena_unic_ip_2 = cena_unic_ip_2[0] ? cena_unic_ip_2[0] : ''; $("#cena_unic_ip_2").val(cena_unic_ip_2);

	cena_revisit_1 = str_replace(",", ".", cena_revisit_1).match(/(\d+(\.)?(\d){0,4})?/);
	cena_revisit_1 = cena_revisit_1[0] ? cena_revisit_1[0] : ''; $("#cena_revisit_1").val(cena_revisit_1);

	cena_revisit_2 = str_replace(",", ".", cena_revisit_2).match(/(\d+(\.)?(\d){0,4})?/);
	cena_revisit_2 = cena_revisit_2[0] ? cena_revisit_2[0] : ''; $("#cena_revisit_2").val(cena_revisit_2);

	cena_hit_user = cena_hit/(1+nacenka/100);
	cena_active_user = cena_active/(1+nacenka/100);
	cena_timer_user = cena_timer/(1+nacenka/100);

	min_pay_ads = (cena_hit * 1000);
	max_pay_ads = min_pay_ads;
	max_pay_ads += (cena_active * 1000);
	max_pay_ads += ((timer_do-timer_ot) * cena_timer * 1000);
	max_pay_ads += (cena_uplist * 1);
	max_pay_ads += (cena_gotosite * 1000);
	max_pay_ads += (cena_color * 1000);
	max_pay_ads += (cena_unic_ip_2 * 1000);
	max_pay_ads += (cena_revisit_2 * 1000);


	$("#cena_hit_user").html('<b style="color:#FF0000; font-size:12px">' +  number_format(cena_hit_user, 4, ".", "") + '</b>');
	$("#cena_active_user").html('<b style="color:#FF0000; font-size:12px">' +  number_format(cena_active_user, 4, ".", "") + '</b>');
	$("#cena_timer_user").html('<b style="color:#FF0000; font-size:12px">' +  number_format(cena_timer_user, 4, ".", "") + '</b>');
	$("#min_pay_ads").html('<span style="color:green; font-size:16px">' + number_format(min_pay_ads, 2, ".", "`") + '</span>');
	$("#max_pay_ads").html('<span style="color:green; font-size:16px">' + number_format(max_pay_ads, 2, ".", "`") + '</span>');
}
</script>

<?php
echo '<form method="post" action="" id="newform">';
echo '<table width="100%" style="margin:0px; padding:0px;">';
echo '<tr valign="top"><td width="50%">';
	echo '<h3 class="sp">Основные настройки</h3>';
	echo '<table style="margin:0px; padding:0px;">';
		echo '<thead><tr align="center"><th>Параметр</th><th width="110">Рекламодателю</th><th width="80">Пользователю</th></tr></thead>';
		echo '<tr>';
			echo '<td align="left"><b>Цена за 1 письмо</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_hit" name="cena_hit" value="'.$mails_cena_hit.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center" id="cena_hit_user"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за активное окно</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_active" name="cena_active" value="'.$mails_cena_active.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center" id="cena_active_user"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за таймер за 1 сек</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_timer" name="cena_timer" value="'.$mails_cena_timer.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center" id="cena_timer_user"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Таймер</b>, (сек.)</td>';
			echo '<td align="center">';
				echo '<input type="text" id="timer_ot" name="timer_ot" value="'.$mails_timer_ot.'" class="ok12" style="width:38px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off">';
				echo '&nbsp;-&nbsp;';
				echo '<input type="text" id="timer_do" name="timer_do" value="'.$mails_timer_do.'" class="ok12" style="width:38px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off">';
				echo '</td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Поднятие письма в списке</b>, (руб.)</td>';
			echo '<td align="center"><input type="text" id="cena_uplist" name="cena_uplist" value="'.$mails_cena_uplist.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за последующий переход на сайт</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_gotosite" name="cena_gotosite" value="'.$mails_cena_gotosite.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за выделение цветом</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_color" name="cena_color" value="'.$mails_cena_color.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за уникальный IP, 100% совпадение</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_unic_ip_1" name="cena_unic_ip_1" value="'.$mails_cena_unic_ip[1].'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за уникальный IP, по маске до 2 чисел</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_unic_ip_2" name="cena_unic_ip_2" value="'.$mails_cena_unic_ip[2].'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за доступно всем каждые 48 часов</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_revisit_1" name="cena_revisit_1" value="'.$mails_cena_revisit[1].'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Доплата за доступно всем каждый месяц</b>, (руб./письмо)</td>';
			echo '<td align="center"><input type="text" id="cena_revisit_2" name="cena_revisit_2" value="'.$mails_cena_revisit[2].'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td align="left"><b>Минимальное кол-во для заказа</b>, (шт.)</td>';
			echo '<td align="center"><input type="text" id="min_hits" name="min_hits" value="'.$mails_min_hits.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Комиссия сайта</b>, (%)</td>';
			echo '<td align="center"><input type="text" id="nacenka" name="nacenka" value="'.$mails_nacenka.'" class="ok12" style="width:100px; text-align:center; padding:1px 5px;" onKeydowm="PlanChange();" onKeyup="PlanChange();" autocomplete="off"></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="23"><b>Минимальная/Максимальная цена за 1000 писем</b>, руб.</td>';
			echo '<td align="center"><span id="min_pay_ads"></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span id="max_pay_ads"></span></td>';
			echo '<td align="center">&nbsp;-&nbsp;</td>';
		echo '</tr>';
	echo '</table>';
echo '</td>';

echo '<td width="50%" valign="top">';
	echo '<h3 class="sp">Настройки безлимитной рекламы</h3>';
	echo '<table style="margin:0px; padding:0px;">';
		echo '<tr align="center"><th>Параметр</th><th width="120">Значение</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>Включение/отключение функции</b></td>';
			echo '<td align="center">';
				echo '<select name="nolimit_status" class="ok12" style="width:131px; text-align:center;">';
					echo '<option value="0" '.("0" == $mails_nolimit_status ? 'selected="selected"' : false).' style="text-align:center;">Отключена</option>';
					echo '<option value="1" '.("1" == $mails_nolimit_status ? 'selected="selected"' : false).' style="text-align:center;">Активна</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>Цена за 1 месяц</b>, (руб.)</td>';
			echo '<td align="center"><input type="text" name="nolimit_cena" value="'.$mails_nolimit_cena.'" class="ok12" style="text-align:center; padding:1px 5px;" autocomplete="off"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Таймер</b>, (сек.)</td>';
			echo '<td align="center"><input type="text" id="nolimit_timer" name="nolimit_timer" value="'.$mails_nolimit_timer.'" class="ok12" style="text-align:center; padding:1px 5px;" autocomplete="off"></td>';
		echo '</tr>';
	echo '</table>';
echo '</td><tr>';

echo '<tr><td colspan="2" align="center"><input type="submit" value="Cохранить изменения" class="sub-blue160"></td></tr>';

echo '</table>';
echo '</form><br><br>';

?>

<script language="JavaScript">PlanChange();</script>