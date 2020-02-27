<?php
$pagetitle="Просмотр рекламных писем";
include('header.php');
require('config.php');
@session_start();

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}
if(($my_wm_purse!= "" or $my_ym_purse!= "" or $my_qw_purse!= "" or $my_pm_purse!= "" or $my_py_purse!= "" or  $my_mb_purse!= "" or  $my_sb_purse!= "" or  $my_ac_purse!= "" or  $my_me_purse!= "" or  $my_vs_purse!= "" or  $my_ms_purse!= "" or  $my_be_purse!= "" or  $my_mt_purse!= "" or  $my_mg_purse!= "" or $my_tl_purse!= "") and $db_time!="0"){
?><script type="text/javascript">
function ShowHide(id) {
	if(document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
	return false;
}

function ShowHideInfo(id) {
	if($("#"+id).css("display") == "none") {
		$("#"+id).css("display", "");
	} else {
		$("#"+id).css("display", "none");
	}
}

function AddClaims(id, type) {
	var tm;
	var claimstext = $.trim($("#claimstext"+id).val());

	function hidemsg() {
		$("#info-claims-"+id).slideToggle("slow");
		if (tm) clearTimeout(tm);
	}

	if(claimstext.length<10) {
		$("#info-claims-"+id).show().html('Укажите минимум 10 символов текста для жалобы.<br>');
		tm = setTimeout(function() {hidemsg()}, 3000);
		return false;
	} else {
		$.ajax({
			type: "POST", url: "/ajax/ajax_claims.php?rnd="+Math.random(), data: {'id':id, 'type':type, 'claimstext':claimstext}, 
			dataType: 'json', beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				$("#info-claims-"+id).show().html('Ошибка обработки данных! Если ошибка повторяется, сообщите Администрации сайта.<br>');
				tm = setTimeout(function() {hidemsg()}, 5000);
				return false;
			}, 
			success: function(data) {
				$("#loading").slideToggle();
				$("#info-claims-"+id).html("");

				if (data.result == "OK") {
					$("#formclaims"+id).show().html(data.message);
					tm = setTimeout(function() {$("#claims"+id).click()}, 5000);
					return false;
				} else {
					if(data.message) {
						$("#info-claims-"+id).show().html(data.message);
						tm = setTimeout(function() {hidemsg()}, 3000);
						return false;
					} else {
						$("#info-claims-"+id).show().html("Ошибка обработки данных!");
						tm = setTimeout(function() {hidemsg()}, 3000);
						return false;
					}
				}
			}
		});
	}
}

function test_virus(id, tid, op) { 
	$.ajax({  
		type: "POST", url: "/ajax/test_virus.php", data: { 'op' : $.trim(op), 'id' : $.trim(id) }, 
		beforeSend: function(){	$('#loading').show(); }, success: function(data) { $('#loading').hide(); $('#t_virus'+tid).hide(); alert(data); }  
	});
}

function start_surf(url, id) {
	$("#"+id).css("cursor","default").html('<div style="text-align:center; color:#2E8B57; font-size:14px; display:block; margin:0 auto; padding:0;">Спасибо за визит!</div>');
	if($("#info_serf_"+id).css("display")!="none") $("#info_serf_"+id).css("display", "none");
	if($("#claims_"+id).css("display")!="none") $("#claims_"+id).css("display", "none");
	ws = window.open(url).focus();
	return false;
}

</script><?php


$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

$my_lastiplog_ex = explode(".", $my_lastiplog);
$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_hit' AND `howmany`='1'");
$mails_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nacenka' AND `howmany`='1'");
$mails_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_active' AND `howmany`='1'");
$mails_cena_active = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_ot' AND `howmany`='1'");
$mails_timer_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_timer' AND `howmany`='1'");
$mails_cena_timer = number_format(mysql_result($sql,0,0), 4, ".", "");

if($id!=false) {
	$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1'");
	if(mysql_num_rows($sql_id)>0) {
		$rowid = mysql_fetch_array($sql_id);

		$sql_v = mysql_query("SELECT `id` FROM `tb_ads_mails_visits` WHERE `ident`='$id' AND `time_next`>='".time()."' AND `user_name`='$username' ORDER BY `id` DESC") or die(mysql_error());
		if(mysql_num_rows($sql_v)>0) {
			echo '<span class="msg-error">Вы уже просматривали это письмо в течении 24 часов!</span><br>';
		}else{
			$title = $rowid["title"];
			$url = $rowid["url"];
			$description = $rowid["description"];
			$question = $rowid["question"];
			$answer_t = $rowid["answer_t"];
			$answer_f_1 = $rowid["answer_f_1"];
			$answer_f_2 = $rowid["answer_f_2"];
			$plan = $rowid["plan"];
			$revisit = $rowid["revisit"];
			$totals = $rowid["totals"];

			if($revisit==0) {
				$time_next = (time() + 1*24*60*60);
			}elseif($revisit==1) {
				$time_next = (time() + 2*24*60*60);
			}elseif($revisit==2) {
				$time_next = (time() + 30*24*60*60);
			}else{
				$time_next = (time() + 1*24*60*60);
			}

			if(count($_POST)>0) {
				$answer = ( isset($_POST["answer"]) && preg_match("/^[a-zA-Z0-9\$\!\/]{32,48}$/i", trim($_POST["answer"]))==1 ) ? limpiar(trim($_POST["answer"])) : false;

				if($answer!=false && $answer==md5($answer_t."2205".strtolower($username))) {
					echo '<script type="text/javascript">location.replace("http://'.$_SERVER["HTTP_HOST"].'/view_mails_new.php?id='.$id.'&hash='.$answer.'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=http://'.$_SERVER["HTTP_HOST"].'/view_mails_newphp?id='.$id.'&hash='.$answer.'"></noscript>';
					include('footer.php');
					exit();
				}else{
					mysql_query("INSERT INTO `tb_ads_mails_visits` (`status`,`ident`,`time`,`time_next`,`user_name`,`ip`, `money`) 
					VALUES('-1','$id','".time()."','$time_next','$username','$ip','0')") or die(mysql_error());

					echo '<span class="msg-error">Вы указали неверный ответ! Письмо Вам не засчитано! Сегодня письмо не будет доступно Вам для просмотра!</span><br>';
					include('footer.php');
					exit();
				}
			}

			$answers = array($answer_t, $answer_f_1, $answer_f_2);
			shuffle($answers);

			require_once ("bbcode/bbcode.lib.php");
			$description = new bbcode($description);
			$description = $description->get_html();
			$description = str_replace("&amp;", "&", $description);

			echo '<table class="tables" style="box-shadow: 1px 2px 4px 3px rgba(0, 0, 0, 0.4);">';
			echo '<thead><tr><th align="center">'.$title.'</th></tr></thead>';

			echo '<tr><td align="left">';
				echo '<h2 class="sp" style="margin-top:5px">Содержание письма:</h2>';
				echo "$description<br><br>";

				echo '<div style="font-size: 12px; color:#89A688; text-align:center;">Будет произведён переход на сайт<br>'.$url.'</div><br>';
			echo '</td></tr>';

			echo '<thead><tr><th align="center">Контрольный вопрос</th></tr></thead>';

			echo '<tr><td align="center">';
				echo '<h2 class="sp" style="margin-top:5px">Для прочтения письма ответьте на вопрос:</h2><br>'.$question.'<br><br>';

				echo '<form method="POST" action="">';
				echo '<table class="tables" style="width:auto; margin:0 auto;">';
					echo '<input type="hidden" name="id" value="'.$id.'">';
					echo '<tr><td align="right" width="10" style="border:none;"><input type="radio" name="answer" value="'.md5($answers[0]."2205".strtolower($username)).'"></td><td style="border:none;">'.$answers[0].'</td></tr>';
					echo '<tr><td align="right" width="10" style="border:none;"><input type="radio" name="answer" value="'.md5($answers[1]."2205".strtolower($username)).'"></td><td style="border:none;">'.$answers[1].'</td></tr>';
					echo '<tr><td align="right" width="10" style="border:none;"><input type="radio" name="answer" value="'.md5($answers[2]."2205".strtolower($username)).'"></td><td style="border:none;">'.$answers[2].'</td></tr>';
				echo '</table><br>';
				echo '<div align="center"><input type="submit" class="proc-btn" value="Подтвердить ответ"></div>';
				echo '</form><br>';

			echo '</td></tr>';
			echo '</table><br><br>';
		}
	}else{
		echo '<span class="msg-error">Рекламное письмо не найдено.</span><br>';
	}

	include('footer.php');
	exit();
}

function format_mails(
	$id, $title, $url, $nolimit, $timer, $color, $active, $plan, $totals, $members, $claims, 
	$username, $my_ref_back, $ref_proc_1, $mails_cena_hit, $mails_nacenka, $mails_cena_active, $mails_timer_ot, $mails_cena_timer, $virus_id
){
	//$mails_cena_hit = ( $mails_cena_hit / (($mails_nacenka+100)/100) );
	//$mails_cena_timer = ( $mails_cena_timer / (($mails_nacenka+100)/100) );
	//$mails_cena_active = ( $mails_cena_active / (($mails_nacenka+100)/100) );

	$cena_click = ($mails_cena_hit + $active * $mails_cena_active + abs($timer-$mails_timer_ot) * $mails_cena_timer);
	//$my_ref_back = ( $cena_click * ($my_ref_back/100) );

	//$cena_click = ($cena_click + $my_ref_back);
	//$cena_click = number_format($cena_click, 4, ".", "");

	$status_color[0] = 'class="blocks1"';
	$status_color[1] = 'class="blocks2"';
	$status_active[0] = '<img src="img/goserf.png" alt="" title="Стандартный просмотр" width="16" height="16" style="margin:1px 0; padding:0;" />';
	$status_active[1] = '<img src="img/active.png" alt="" title="Просмотр ссылки только в активном окне" width="16" height="16" style="margin:1px 0; padding:0;" />';

	echo '<tr id="serf_none'.$id.'">';
		echo '<td class="td-serf" valign="top" style="text-align:center; width:18px; padding-left:10px;">';
			echo $status_active[$active];
			if($claims>10) {
				echo '<img src="img/protect_red.png" alt="" title="Пожаловаться на рекламу" width="16" height="16" style="margin:1px 0; padding:0;" />';
			}else{
				echo '<img src="img/protect_green.png" alt="" title="Пожаловаться на рекламу" width="16" height="16" style="margin:1px 0; padding:0;" />';
			}
		echo '</td>';

		echo '<td align="left" valign="top" class="td-serf" style="cursor:pointer;">';
			//echo '<div>';
				echo '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-right:5px;" src="https://www.google.com/s2/favicons?domain='.@gethost($url).'" align="absmiddle" />';
				echo '<a onClick="start_surf(\'?id='.$id.'\', \''.$id.'\');" '.$status_color[$color].' style="cursor: pointer; font-weight:normal;">'.$title.'<br>';
					echo '<span style="color:#89A688;">'.@gethost($url).'</span>';
				echo '</a>';
			//echo '</div>';

			echo '<div id="info_'.$id.'" style="display: none; margin:0 20px; color:#778899;">';
				echo 'Сайт: <a href="'.$url.'" target="_blank">'.$url.'</a><br>';
				if($nolimit>0) {
					echo 'Заказано до: '.DATE("d.m.Y H:i", $nolimit).'.<br>';
					echo 'Осталось: '.date_ost(($nolimit-time()), 1).'.<br>';
				}else{
					echo 'Заказано: '.number_format($plan, 0, ".", "`").'.<br>';
					echo 'Осталось визитов: '.number_format($totals, 0, ".", "`").'.<br>';
				}
				echo 'Прочитали: '.number_format($members, 0, ".", "`").'.<br>';
				echo 'Таймер: '.$timer.'. Жалобы: '.$claims.'.';
			echo '</div>';
		echo '</td>';
		

		echo '<td class="td-serf" nowrap="nowrap" valign="top" style="text-align:right; width:110px; padding-right:10px;">';
			if($nolimit>0) {
				echo '<span title="Заказано до '.DATE("d.m.Y H:i", $nolimit).'" style="cursor: help; font-size: 11px; color: #89A688;">('.DATE("d.m.Y", $nolimit).')</span>&nbsp;';
			}else{
				echo '<span title="Осталось визитов '.number_format($totals, 0, ".", " ").'" style="cursor: help; font-size: 11px; color: #89A688;">('.number_format($totals, 0, ".", "`").')</span>&nbsp;';
			}
			echo '<span title="Стоимость просмотра '.$cena_click.' руб." style="cursor:help; color:#135B11;">'.$cena_click.'</span><br>';
			echo '<a class="workcomp" id="claims'.$id.'" onClick="ShowHideInfo(\'claims_'.$id.'\');" title="Пожаловаться на рекламу"></a>';
			//echo '<a class="workcomp" href="/add_claims_m.php?id='.$id.'" onClick="add_claims(\''.$id.'\');" title="Пожаловаться на рекламу" target="_blank"></a>';
			echo '<a id="t_virus'.$virus_id.'" class="workvir" onClick="test_virus(\''.$id.'\', \''.$virus_id.'\', \'mails\');" title="Проверить ссылку на вирусы" target="_blank"></a>';
			echo '<a class="workquest" onClick="ShowHide(\'info_'.$id.'\');" title="Подробная информация" target="_blank"></a>';
		echo '</td>';
	echo '</tr>';
	
	
		
		echo '<tr id="claims_'.$id.'"  colspan="3" style="display:none;">';
		echo '<td colspan="3" align="center" class="td-serf" style="color: #FFFFFF; background-color: #FF9966; padding: 5px 0px; font: 12px Tahoma, Arial, sans-serif;">';
			echo '<div id="info-claims-'.$id.'" style="display:none;"></div>';
			echo '<div id="formclaims'.$id.'"><table style="width:100%; margin:0; padding:0;">';
			echo '<tr>';
				echo '<td align="center" width="100" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;">Текст жалобы:</td>';
				echo '<td align="center" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><div id="newform"><input type="text" id="claimstext'.$id.'" maxlength="100" value="" class="ok" style="margin:0; padding:1px 5px;" /></div></td>';
				echo '<td align="center" width="120" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><span onClick="AddClaims(\''.$id.'\', \'mails\');" class="sub-red" style="float:none;">Отправить</span></td>';
			echo '</tr>';
			echo '</table></div>';
		echo '</td>';
	echo '</tr>';

}

$count_sites = 0;
$virus_id = 0;

$sql_mails = mysql_query("SELECT * FROM `tb_ads_mails` 
	WHERE `status`='1' AND ( `totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) 
	AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') 
	AND (`sex_adv`='0' OR `sex_adv`='$my_sex') 
	AND (`new_users`='0' OR (`new_users`='1' AND '$my_joindate'>='".(time()-7*86400)."') ) 
	AND (`no_ref`='0' OR (`no_ref`='1' AND '$my_referer_1'>='') ) 
	AND (`to_ref`='0' 
		OR (`to_ref`='1' AND `username`!='' AND `username`='$my_referer_1') 
		OR (`to_ref`='2' AND `username`!='' AND (`username`='$my_referer_1' OR `username`='$my_referer_2' OR `username`='$my_referer_3') ) 
	) 
	AND `id` NOT IN (
		SELECT `ident` FROM `tb_ads_mails_visits` 
		WHERE `time_next`>='".time()."' 
		AND (
			   ( `tb_ads_mails`.`unic_ip`='0' AND `user_name`='$username' ) 
			OR ( `tb_ads_mails`.`unic_ip`='1' AND (`user_name`='$username' OR `ip`='$my_lastiplog') ) 
			OR ( `tb_ads_mails`.`unic_ip`='2' AND (`user_name`='$username' OR `ip` LIKE '%$my_lastiplog_ex%') ) 
		)
	) 
	AND `username` NOT IN (SELECT `user_name` FROM `tb_black_list` WHERE `user_bl`='$username' AND `type`='usr_adv' AND `mails_usr`='1') 
	AND `username` NOT IN (SELECT `user_bl` FROM `tb_black_list` WHERE `user_name`='$username' AND `type`='usr_adv' AND `mails_adv`='1') 

	ORDER BY `date_up` DESC, `id` DESC
");

include('includes/stat_links.php');
echo '<br>';

//include("includes/vip_task_new.php");

include('includes/text_links.php');
echo '<br>';

if(mysql_num_rows($sql_mails)>0) {
	echo '<table class="table-serf"><tbody>';
	echo '<tr>';
		echo '<td align="center" class="td-serf" colspan="3">';
			echo '<h2 class="sp" style="margin:0; padding:0;">Зарабатывай, читая письма рекламодателей</h2>';
		echo '</td>';
		//echo '<td align="center" class="td-serf" style="border-left: none;"><a href="faq.php?ads=cat_3_1#cat3_1.3" target="_blank" style="cursor: help;" title="Помощь по разделу"><img style="margin:0; padding:0;" src="images/help_help.png" width="16" height="16" /></a></td>';
	echo '</tr>';

	while($row_mails = mysql_fetch_assoc($sql_mails)) {
		$count_sites++;
		$virus_id++;

		format_mails(
			$row_mails["id"], 
			$row_mails["title"], 
			$row_mails["url"], 
			$row_mails["nolimit"], 
			$row_mails["timer"], 
			$row_mails["color"], 
			$row_mails["active"], 
			$row_mails["plan"], 
			$row_mails["totals"], 
			$row_mails["members"], 
			$row_mails["claims"], 
			$username, $my_ref_back, $ref_proc_1, $mails_cena_hit, $mails_nacenka, $mails_cena_active, $mails_timer_ot, $mails_cena_timer, $virus_id
		);
	}

	echo '</tbody></table>';
}

if($count_sites==0) {
	echo '<span align="center" style="background: url(img/light.png) no-repeat top center; font-size: 14px; color: #ab0606; text-align: center; padding-top: 120px; margin-top: 20px; display: block;">Доступных к просмотру рекламных писем нет.<br>Заходите позже<br><br>';
	echo '<div align="center"><span onClick="document.location.href=\'/advertise.php?ads=rek_mails\'" class="proc-btn">Разместить письмо</span></div><br><br>';
}else{
	echo '<br>';
	echo '<table align="center" style="width:auto; margin:0 auto;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="16" align="center"><img src="img/goserf.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
			echo '<td align="left">Обычный просмотр сайта</td>';
			echo '<td width="50"></td>';
			echo '<td width="16" align="center"><img src="img/active.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
			echo '<td align="left">Просмотр сайта только в активном окне</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="16" align="center"><img src="img/protect_green.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
			echo '<td align="left">Реклама безопасна для просмотра</td>';
			echo '<td width="50"></td>';
			echo '<td width="16" align="center"><img src="img/protect_red.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
			echo '<td align="left">Реклама опасна для просмотра</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
}
}else{
echo '<span class="msg-warning">Для работы вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше имя (которое будет отображатся на стене)<br> указать дату рождения <br> указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span></span>';
}

include("footer.php");?>