<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}

if(isset($ads) && isset($id) && isset($op) && $ads=="sentmails" && $op=="edit" && $id>0) {
	if(!DEFINED("SENTMAILS_EDIT")) DEFINE("SENTMAILS_EDIT", true);
	include("sentmails_edit.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои рассылки на e-mail</h5>';

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_sent_mails' AND `howmany`='1'");
$cena_sent_mails = mysql_result($sql,0,0);

?>

<script type="text/javascript" language="JavaScript">
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
					/*document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>"; */
					window.location.reload(true);
					return false;
				}else {
					alert("Не удалось обработать запрос!!!");
					return false;
				}
			}
		});
	}
}
</script>


<?php

$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		$sum = number_format(($cena_sent_mails * (100-$cab_skidka)/100), 2, ".", "");

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0") {
						echo '<span class="adv-play" title="Запустить рассылку на e-meail" onClick="alert_nostart_rc();"></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="Приостановить рассылку на e-meail" onClick="play_pause('.$row["id"].', \'sentmails\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="Запустить рассылку на e-meail" onClick="play_pause('.$row["id"].', \'sentmails\');"></span>';
					}elseif($row["status"]=="3") {
						echo '<span class="adv-play" title="Запустить рассылку на e-meail" onClick="play_pause('.$row["id"].', \'sentmails\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<span class="title-text">'.$row["subject"].'</span><br><span class="desc-text">'.$row["message"].'</span><br>';
				echo '<span class="info-text">';
					echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Отправлено писем:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["count"].'</span>';
				echo '</span>';
				echo '<span class="adv-dell" title="Удалить рассылку" onClick="alert_delete('.$row["id"].', \'sentmails\');"></span>';
				echo '<span class="adv-erase" title="Сброс статистики" onClick="clear_stat('.$row["id"].', \'sentmails\', '.$row["count"].');"></span>';
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'#goto" title="Редактировать площадку"></a>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0") {
					echo '<a class="add-money-no" title="Оплатить рекламную площадку" onClick="pay_adv('.$row["id"].', \'sentmails\', '.$sum.');">Оплатить</a>';
				}elseif($row["status"]=="3") {
					echo '<a class="add-money-no" title="Оплатить рекламную площадку" onClick="pay_adv('.$row["id"].', \'sentmails\', '.$sum.');">Оплатить</a>';
				}else{
					echo '<img src="/img/ok.gif" alt="" title="Рекламная площадка оплачена" border="0" width="32" />';
				}
			echo '</td>';
		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">У вас нет своих рассылок на e-mail</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=sent_emails\'">Создать рассылку</span></div>';
?>