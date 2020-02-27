<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}
mysql_query("UPDATE `tb_ads_dlink` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND ( (`totals`<'1' AND `nolimit`='0') OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(mysql_error());

echo '<div id="LoadModal" style="display:none;"></div>';

if(isset($ads) && isset($id) && isset($op) && $ads=="dlink" && $op=="edit" && $id>0) {
	if(!DEFINED("DLINK_EDIT")) DEFINE("DLINK_EDIT", true);
	include("dlink_edit.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

if(isset($ads) && isset($id) && isset($op) && $ads=="dlink" && $op=="statistics" && $id>0) {
	if(!DEFINED("DLINK_STAT")) DEFINE("DLINK_STAT", true);

	include(DOC_ROOT."/geoip/geoipcity.inc");
	include(DOC_ROOT."/geoip/geoipregionvars.php");
	$gi = geoip_open(DOC_ROOT."/geoip/GeoLiteCity.dat",GEOIP_STANDARD);

	include("dlink_stat.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои рекламные ссылки в серфинге</h5>';

#### STANDART ####
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
$dlink_cena_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
$dlink_cena_hits_bs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_nacenka' AND `howmany`='1'");
$dlink_nacenka = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits' AND `howmany`='1'");
$dlink_min_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits_vip' AND `howmany`='1'");
$dlink_min_hits_vip = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_color' AND `howmany`='1'");
$dlink_cena_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_active' AND `howmany`='1'");
$dlink_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_ot' AND `howmany`='1'");
$dlink_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_do' AND `howmany`='1'");
$dlink_timer_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_timer' AND `howmany`='1'");
$dlink_cena_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_uplist' AND `howmany`='1'");
$dlink_cena_uplist = mysql_result($sql,0,0);

$dlink_cena_revisit[0] = 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='1'");
$dlink_cena_revisit[1] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='2'");
$dlink_cena_revisit[2] = mysql_result($sql,0,0);

$dlink_cena_unic_ip[0] = 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='1'");
$dlink_cena_unic_ip[1] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='2'");
$dlink_cena_unic_ip[2] = mysql_result($sql,0,0);
#### STANDART ####

#### Безлимит ####
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='1'");
$dlink_cena_nolimit[1] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='2'");
$dlink_cena_nolimit[2] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='3'");
$dlink_cena_nolimit[3] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='4'");
$dlink_cena_nolimit[4] = mysql_result($sql,0,0);
#### Безлимит ####

?><script type="text/javascript" language="JavaScript">
function pay_adv(id, type, bezlimit, type_serf) {
	var plan = $.trim($('#plan'+id).val());
	plan = str_replace(",", ".", plan);
	plan = str_replace("-", "", plan);
	plan = plan.replace(",", ".");
	rplan = parseInt(plan);
	if (type_serf == 3 | type_serf == 4) var min_hits = <?=$dlink_min_hits_vip;?>;
	else var min_hits = <?=$dlink_min_hits;?>;

	if (plan == '') {
		if(bezlimit == 1) {
			gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Введите необходимое количество недель(1, 2, 3 недели или 4 - один месяц)</span>";
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
			if (rplan != 1 && rplan != 2 && rplan != 3 && rplan != 4) {
				gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество недель должно быть 1, 2, 3 или 4(один месяц)</span>";
				gebi("imfo-msg-addmoney"+id).style.display = 'block';
				setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 4000); clearTimeout();
				return false;
			}
		} else {
			if (rplan < min_hits) {
				gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество просмотров должно быть не менее " + min_hits + "</span>";
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
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество просмотров должно быть не менее " + min_hits + "</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
					return false;
				} else if (data == "ERROR2") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>Количество недель должно быть 1, 2, 3 или 4(один месяц)</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 4000); clearTimeout();
					return false;
				} else if (data == "ERROR3") {
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
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-w'>Не удалось обработать запрос! " + data + "</span>";
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

$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `username`='$username' AND ytflag='0' AND `type_serf`!='10' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		if($row["type_serf"]==1) {
			$cena_hits = $dlink_cena_hits;
			$min_hits = $dlink_min_hits;
		}elseif($row["type_serf"]==2) {
			$cena_hits = $dlink_cena_hits_bs;
			$min_hits = $dlink_min_hits;
		}elseif($row["type_serf"]==3) {
			$cena_hits = $dlink_cena_hits;
			$min_hits = $dlink_min_hits_vip;
		}elseif($row["type_serf"]==4) {
			$cena_hits = $dlink_cena_hits_bs;
			$min_hits = $dlink_min_hits_vip;
		}elseif($row["type_serf"]==5) {
			$cena_hits = $dlink_cena_hits;
			$min_hits = $dlink_min_hits;
		}else{
			$cena_hits = $dlink_cena_hits;
			$min_hits = $dlink_min_hits;
		}

		if($row["status"]==1 && $row["up_list"]>0) {
			$position = mysql_result(mysql_query( "SELECT COUNT(*) FROM `tb_ads_dlink` WHERE `status`='1' AND `up_list`>(SELECT `up_list` FROM `tb_ads_dlink` WHERE `id`='".$row["id"]."')" ),0,0)+1;
		}else{
			unset($position);
		}

		$cena_one = ($cena_hits + ($dlink_cena_timer * ($row["timer"]-$dlink_timer_ot)) + ($row["active"] * $dlink_cena_active)) * ($dlink_nacenka + 100)/100 + ( ($row["color"] * $dlink_cena_color) + $dlink_cena_revisit[$row["revisit"]] + $dlink_cena_unic_ip[$row["unic_ip"]] );
		$cena_one = round(number_format($cena_one, 6, ".", ""), 6);

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0" | $row["status"]=="3") {
						if($row["nolimit"]!=0) {
							echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart_rc();"></span>';
						}else{
							echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
						}
					}elseif($row["type_serf"]==3 | $row["type_serf"]==4) {
							echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="alert_nopause();"></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'dlink\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'dlink\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				if($row["type_serf"]==2 | $row["type_serf"]==4) {
					echo '<a class="adv" href="'.$row["url"].'" target="_blank"><img class="mini_banner" src="'.($row["urlbanner_load"]!=false ? $row["urlbanner_load"] : $row["description"]).'" width="250" height="32" alt="preview" /><br><span class="desc-text">'.$row["url"].'</span></a><br>';
				}else{
					echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["title"].'<br><span class="desc-text">'.$row["description"].'</span></a><br>';
				}

				echo '<span class="info-text">';
					if($row["nolimit"]!=0) {
						echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Безлимит&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["outside"].'</span>';
					}else{
						echo '№:&nbsp;'.$row["id"].'&nbsp;&nbsp;Клик:&nbsp;'.$cena_one.'&nbsp;руб.&nbsp;&nbsp;Просмотров:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["outside"].'</span>';
					}
				echo '</span>';

				if($row["claims"] > 0 ) {
					echo '&nbsp;&nbsp;<span id="claims-'.$row["id"].'" class="info-text" onClick="ViewClaims(\''.$row["id"].'\', \'dlink\', \'ViewClaims\');" title="Просмотреть жалобы, поданные пользователями проекта" style="color:#FF0000; cursor:pointer;">Жалобы:&nbsp;<b>'.number_format($row["claims"], 0, ".", "`").'</b></span>';
				}

				echo '<span class="adv-dell" title="Удалить ссылку" onClick="alert_delete('.$row["id"].', \'dlink\');"></span>';
				//if($row["status"]==1) {
					//echo '<span class="adv-edit" title="Редактировать ссылку" onClick="alert_nostart_edit();"></span>';
					///}elseif($row["status"]=="2") {
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'#goto" title="Редактировать ссылку"></a>';
					//}
				if($row["status"]==1) {
					if(isset($position) && $position>0 && $position<100) echo '<span class="adv-down" title="Позиция ссылки в списке серфинга: '.$position.'" onClick="show_up_list(\'advup'.$row["id"].'\');">'.$position.'</span>';
					else echo '<span class="adv-up" title="Поднять в списке" onClick="show_up_list(\'advup'.$row["id"].'\');">&uarr;</span>';
				}
				
				if($row["type_serf"]==3 | $row["type_serf"]==4) {
                        echo '<span class="adv-vip" title="Реклама VIP"></span>';
                        }

				echo '<span class="adv-erase" title="Сброс статистики" onClick="clear_stat('.$row["id"].', \'dlink\', '.$row["outside"].');"></span>';
				echo '<a class="adv-statistics" href="?ads='.$ads.'&op=statistics&id='.$row["id"].'#goto" title="Статистика просмотра"></a>';
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
				echo 'Вы можете разместить свою ссылку выше остальных в списке серфинга. Ссылка будет сохранять свою позицию до тех пор, пока другой пользователь не поднимет свою ссылку. ';
				echo 'В таком случае ваша ссылка будет опускаться ниже. В связи с этим, невозможно однозначно сказать, как долго ваша ссылка продержится на первых позициях. Стоимость поднятия составляет <b>'.$dlink_cena_uplist.'</b> руб. Эта сумма будет снята с рекламного счета.';
				echo '</div>';
				echo '<center><span onClick="pay_adv_up('.$row["id"].', \'dlink\');" class="sub-red" style="float:none;" title="Поднять">Поднять</span></center>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="adv_addmoney'.$row["id"].'" style="display: none">';

			?><script type="text/javascript" language="JavaScript">
			function obsch<?php echo $row["id"];?>(bezlimit){
				gebi("imfo-msg-addmoney"+<?php echo $row["id"];?>).style.display = 'none';
				var plan<?php echo $row["id"];?> = gebi('plan<?php echo $row["id"];?>').value;
				plan<?php echo $row["id"];?> = str_replace(",", ".", plan<?php echo $row["id"];?>);
				plan<?php echo $row["id"];?> = str_replace("-", "", plan<?php echo $row["id"];?>);
				if (bezlimit == 1) {
					if(plan<?php echo $row["id"];?> == 1) {
						var cena_link<?php echo $row["id"];?> = <?php echo $dlink_cena_nolimit[1];?>;
					} else if(plan<?php echo $row["id"];?> == 2) {
						var cena_link<?php echo $row["id"];?> = <?php echo $dlink_cena_nolimit[2];?>;
					} else if(plan<?php echo $row["id"];?> == 3) {
						var cena_link<?php echo $row["id"];?> = <?php echo $dlink_cena_nolimit[3];?>;
					} else if(plan<?php echo $row["id"];?> == 4) {
						var cena_link<?php echo $row["id"];?> = <?php echo $dlink_cena_nolimit[4];?>;
					} else {
						gebi("imfo-msg-addmoney"+<?php echo $row["id"];?>).innerHTML = "<span class='msg-error'>Количество недель должно быть 1, 2, 3 или 4(один месяц)</span>";
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
					echo '<b>Вы оплачиваете безлимитную рекламу.</b><br>Укажите количество недель, которые вы хотите внести в бюджет рекламной площадки<br>(1 неделя, 2 недели, 3 недели, 4 - один месяц)';
					echo '<input type="text" maxlength="10" id="plan'.$row["id"].'" value="" class="payadv" onChange="obsch'.$row["id"].'(1);" onKeyUp="obsch'.$row["id"].'(1);" />';
					echo 'Стоимость:';
					echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 руб.</span>';
					if($cab_status==1 && $cab_skidka>0) echo '<div style="color:#708090;">(с учетом вашей скидки '.$cab_skidka.'% это составит <span id="price'.$row["id"].'_2">0.00 руб.</span>)</div>';
					echo '<center><span onClick="pay_adv('.$row["id"].', \'dlink\', 1, '.$row["type_serf"].');" class="sub-green" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></center>';
					echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';
				}else{
					echo 'Укажите количество просмотров, которые вы хотите внести в бюджет рекламной площадки<br>(Минимум '.count_text($min_hits, "просмотров", "просмотр", "просмотра", "").')';
					echo '<input type="text" maxlength="10" id="plan'.$row["id"].'" value="" class="payadv" onChange="obsch'.$row["id"].'(0);" onKeyUp="obsch'.$row["id"].'(0);" />';
					echo 'Стоимость:';
					echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 руб.</span>';
					if($cab_status==1 && $cab_skidka>0) echo '<div style="color:#708090;">(с учетом вашей скидки '.$cab_skidka.'% это составит <span id="price'.$row["id"].'_2">0.00 руб.</span>)</div>';
					echo '<center><span onClick="pay_adv('.$row["id"].', \'dlink\', 0, '.$row["type_serf"].');" class="sub-green" style="float:none;" title="Пополнить бюджет площадки">Оплатить</span></center>';
					echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';
				}
			echo '</td>';

		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">У вас нет своих размещённых ссылок в серфинге</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=dlink\'">Разместить ссылку</span></div>';
?>