<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}

if(isset($ads) && isset($id) && isset($op) && $ads=="rekcep" && $op=="edit" && $id>0) {
	if(!DEFINED("REKCEP_EDIT")) DEFINE("REKCEP_EDIT", true);
	include("rekcep_edit.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои ссылки в рекламной цепочке</h5>';

$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_rek_cep' and howmany='1'");
$cena_rek_cep = mysql_result($sql,0,0);

$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_color_rek_cep' and howmany='1'");
$cena_color_rek_cep = mysql_result($sql,0,0);


?><script type="text/javascript" language="JavaScript">
function pay_adv(id, type, sum) {
	if (confirm("Вы подтверждаете оплату рекламной площадку № "+id+" на сумму "+sum+" руб. (c учетом скидки) ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv', 'type':type, 'id':id }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();

				if (data == "ERROR") {
					alert("На вашем рекламном счету недостаточно средств!");
					return false;
				} else if (data == "OK") {
					alert("Рекламная площадка успешно оплачена!");
					document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
					return false;
				}else {
					alert("Не удалось обработать запрос!");
					return false;
				}
			}
		});
	}
}
</script><?php

$sql = mysql_query("SELECT * FROM `tb_ads_rc` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		$sum = number_format((($cena_rek_cep + $row["color"] * $cena_color_rek_cep) * (100-$cab_skidka)/100), 2, ".", "");

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				if($row["status"]=="0") {
					echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart_rc();"></span>';
				}elseif($row["status"]=="1") {
					echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="alert_nopause();"></span>';
				}else{
					echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart_rc();"></span>';
				}
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["description"].'</a><br>';
				echo '<span class="info-text">';
					echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["view"].'</span>';
				echo '</span>';
				echo '<span class="adv-dell" title="Удалить ссылку" onClick="alert_delete('.$row["id"].', \'rekcep\');"></span>';
				echo '<span class="adv-erase" title="Сброс статистики" onClick="clear_stat('.$row["id"].', \'rekcep\', '.$row["view"].');"></span>';
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'#goto" title="Редактировать ссылку"></a>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0") {
					echo '<a class="add-money-no" title="Оплатить рекламную площадку" onClick="pay_adv('.$row["id"].', \'rekcep\', '.$sum.');">Оплатить</a>';
				}else{
					echo '<img src="/img/ok.gif" alt="" title="Рекламная площадка оплачена" border="0" width="32" />';
				}
			echo '</td>';
		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">У вас нет своих размещённых ссылок в рекламной цепочке</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=rek_cep\'">Разместить ссылку</span></div>';
?>