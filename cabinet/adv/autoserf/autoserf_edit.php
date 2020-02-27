<?php
if(!DEFINED("AUTOSERF_EDIT")) {die ("Hacking attempt!");}

	echo '<a name="goto"></a>';
	echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование ссылки в авто-серфинге № '.$id.'</h5>';

	$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$plan = $row["plan"];
		$totals = $row["totals"];
		$timer = $row["timer"];
		$limits = $row["limits"];
		$url = $row["url"];
		$description = $row["description"];
		
		$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
		$cena_hits_aserf = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_hits_aserf' AND `howmany`='1'");
		$nacenka_hits_aserf = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
		$timer_aserf_ot = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
		$timer_aserf_do = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_timer_aserf' AND `howmany`='1'");
		$cena_timer_aserf = mysql_result($sql,0,0);

		?><script type="text/javascript" language="JavaScript">

		function save_ads(id, type, status) {
			$('#info-msg-cab').hide(); 
			var url = $.trim($('#url').val());
			var description = $.trim($('#description').val());
			var timer = $.trim($('#timer').val());
			var limits = $.trim($('#limits').val());
			var country = $('input[id="country[]"]:checked').map(function(){return $(this).val();}).get();
			rtimer = parseInt(timer);
			rlimits = parseInt(limits);
			if (limits == "") limits = 0;

			if ((url == '') | (url == 'http://') | (url == 'https://')) {
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали URL-адрес сайта!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
				focus_bg("url");
				return false;
			} else if (description == '') {
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали краткое описание ссылки!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
				focus_bg("description");
				return false;
			} else {
				if (status == 0 | status == 3) {
					if (timer == "" | timer == 0) {
						gebi("info-msg-cab").innerHTML = '<span class="msg-error">Укажите время просмотра!</span>';
						gebi("info-msg-cab").style.display = "";
						setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
						focus_bg("timer");
						return false;
					} else if (isNaN(timer)) {
						gebi("info-msg-cab").innerHTML = '<span class="msg-error">Время просмотра должно быть числовым!</span>';
						gebi("info-msg-cab").style.display = "";
						setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
						focus_bg("timer");
						return false;
					} else if (timer != rtimer) {
						gebi("info-msg-cab").innerHTML = '<span class="msg-error">Время просмотра должно быть целым числом!</span>';
						gebi("info-msg-cab").style.display = "";
						setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
						focus_bg("timer");
						return false;
					} else if (rtimer < <?=$timer_aserf_ot;?> | rtimer > <?=$timer_aserf_do;?>) {
						gebi("info-msg-cab").innerHTML = '<span class="msg-error">Время просмотра должно быть от <?=$timer_aserf_ot;?> до <?=$timer_aserf_do;?> сек.</span>';
						gebi("info-msg-cab").style.display = "";
						setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
						focus_bg("timer");
						return false;
					}
				}

				if (isNaN(limits)) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">Ограничение количества показов в сутки должно быть числовым!</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("limits");
					return false;
				} else if (limits != rlimits) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">Ограничение количества показов в сутки должно быть целым числом!</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("limits");
					return false;
				} else if (rlimits<0) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">Ограничение количества показов в сутки должно быть положительным!</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("limits");
					return false;
				} else {
					$.ajax({
						type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: {'op':'save', 'type':type, 'id':id, 'url':url, 'description':description, 'timer':timer, 'limits':limits, 'country[]':country }, 
						beforeSend: function() { $('#loading').show(); }, 
						success: function(data) { 
							$('#loading').hide(); 
							if (data == "OK") {
								document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>#goto";
							} else {
								gebi("info-msg-cab").innerHTML = '<span class="msg-error">' + data + '</span>';
								gebi("info-msg-cab").style.display = "";
								setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 5000); clearTimeout();
								return false;
							}
						}
					});
				}
			}
		}

		function obsch() {
			<?php
				if($status==0 | $status==3) {
			?>
			var timer = gebi('timer').value;
			var limits = gebi('limits').value;
			<?php
				}else{
			?>
			var timer = <?=$timer;?>;
			var limits = <?=$limits;?>;
			<?php
				}
			?>

			if(timer<<?=$timer_aserf_ot;?>) { timer = <?=$timer_aserf_ot;?>}
			if(timer><?=$timer_aserf_do;?>) { timer = <?=$timer_aserf_do;?>}

			var price = ( ((timer - <?=$timer_aserf_ot;?>) * <?=$cena_timer_aserf;?> + <?=$cena_hits_aserf;?>) * (100 + <?=$nacenka_hits_aserf;?>)/100 );
			gebi('price').innerHTML = '<span style="color:#228B22;"><b>' + number_format(price, 4, '.', ' ') + '</b> руб.</span>';
		}
		
		function ShowHideBlock(id) {
		    if($("#adv-title"+id).attr("class") == "adv-title-open") {
			    $("#adv-title"+id).attr("class", "adv-title-close")
		    } else {
			    $("#adv-title"+id).attr("class", "adv-title-open")
		    }
		    $("#adv-block"+id).slideToggle("slow");
	    }
		</script><?php

		echo '<div id="newform">';
		echo '<table class="tables">';
		echo '<thead><tr>';
			echo '<th class="top" width="250">Параметр</a>';
			echo '<th class="top">Значение</a>';
		echo '</thead></tr>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td><b>URL сайта (ссылка):</b></td>';
				echo '<td><input type="text" id="url" maxlength="300" value="'.$url.'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Описание ссылки:</b></td>';
				echo '<td><input type="text" id="description" maxlength="80" value="'.$description.'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Ограничение количества показов в сутки:</b></td>';
				if($limits==$plan) $limits = 0;
				echo '<td><input type="text" id="limits" maxlength="7" value="'.$limits.'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(0 - без ограничений)</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Время просмотра:</b></td>';
					echo '<td><input type="text" id="timer" maxlength="3" value="'.$timer.'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(от '.$timer_aserf_ot.' до '.$timer_aserf_do.' сек.)</td>';
			echo '</tr>';
			echo '<tr><td colspan=2><span id="adv-title-'.(count($geo_targ)>0 ? "open" : "close").'" class="adv-title-close" onclick="ShowHideBlock(2);">Настройки географического таргетинга</span></td></tr>';
	    echo '<tr><td colspan=2><div id="adv-block2" style="'.(count($geo_targ)>0 ? "display:;" : "display:none;").'"><table class="tables">';
	        echo '<tbody>';
		        echo '<tr>';
			       echo '<td colspan="2" align="center" style="border-right:none;"><a id="paste" onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>Отметить все</center></a></td>';
			       echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>Снять все</center></a></td>';
		        echo '</tr>';
		        include(DOC_ROOT."/advertise/func_geotarg_edit.php");
	        echo '</tbody>';
	        echo '</table>';
        echo '</div></td></tr>';
			echo '<tr>';
				echo '<td><b>Стоимость одного просмотра:</b></td>';
				echo '<td id="price"></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div><br>';

		echo '<div id="info-msg-cab"></div>';

		echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'autoserf\', '.$status.');" class="proc-btn" style="float:none; width:160px;">Сохранить</span></div>';

		?><script language="JavaScript">obsch();</script><?php
}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>