<?php
$pagetitle="Просмотр сайтов";
include('header.php');
require('config.php');

//if(($my_ym_purse!= "0" or $my_qw_purse!= "0" or $my_pm_purse!= "0" or $my_py_purse!= "0" or  $my_mb_purse!= "0" or  $my_sb_purse!= "0" or  $my_ac_purse!= "0" or  $my_me_purse!= "0" or  $my_vs_purse!= "0" or  $my_ms_purse!= "0" or  $my_be_purse!= "0" or  $my_mt_purse!= "0" or  $my_mg_purse!= "0" or $my_tl_purse!= "0") and $avatar!= "no.png" and $db_time!="0"){
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	if(($my_wm_purse!= "" or $my_ym_purse!= "" or $my_qw_purse!= "" or $my_pm_purse!= "" or $my_py_purse!= "" or  $my_mb_purse!= "" or  $my_sb_purse!= "" or  $my_ac_purse!= "" or  $my_me_purse!= "" or  $my_vs_purse!= "" or  $my_ms_purse!= "" or  $my_be_purse!= "" or  $my_mt_purse!= "" or  $my_mg_purse!= "" or $my_tl_purse!= "") and $db_time!="0"){
	if(!isset($_SESSION["ANTI_BOT"])) {
		if(!DEFINED("ANTI_BOT")) DEFINE("ANTI_BOT", true);
		include("anti_bot.php");
		include("footer.php");
		exit();
	}
	}else{
echo '<span class="msg-warning">Для работы вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше имя (которое будет отображатся на стене)<br> указать дату рождения <br> указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span></span>';
include("footer.php");
		exit();
}
}

$sql = mysql_query("SELECT count(id) FROM `tb_ads_dlink` WHERE `status`='1'  AND ( `totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') )");
$serf_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_youtube` WHERE `status`='1'  AND ( `totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') )");
$youtube_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_auto` WHERE `status`='1'  AND `totals`>'0'");
$aut_serf_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_mails` WHERE `status`='1'");
$mails_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_tests` WHERE `status`='1'  AND `balance`>=`cena_advs`");
$tests_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_pay_vis` WHERE `status`='1'  AND `balance`>=`price_adv`");
$pay_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_task` WHERE `status`='pay'  AND `totals`>'0'");
$tack_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_ads_autoyoutube` WHERE `status`='1'  AND `totals`>'0'");
$autvto_serf_all = mysql_result($sql,0,0);

echo '<table class="tables" style="margin:5px auto;">';
echo '<thead>';
echo '<tr>';
echo '<th align="center" width="10.6%">Серфинг</th>';
echo '<th align="center" width="10.6%">Серфинг<br/><span style="color: #ffffff;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></th>';
echo '<th align="center" width="10.6%">Автосерфинг</th>';
echo '<th align="center" width="10.6%">Автосерфинг<br/><span style="color: #ffffff;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></th>';
echo '<th align="center" width="10.6%">Письма</th>';
echo '<th align="center" width="10.6%">Тесты</th>';
echo '<th align="center" width="10.6%">Посещения</th>';
echo '<th align="center" width="10.6%">Задания</th>';
echo '</tr>';
echo '</thead>';
echo '<thead></thead>';
echo '<tbody>';
echo '<tr>';
echo '<td align="center">'.number_format($serf_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($youtube_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($aut_serf_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($autvto_serf_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($mails_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($tests_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($pay_all,0,".","`").' шт.</td>';
echo '<td align="center">'.number_format($tack_all,0,".","`").' шт.</td>';
echo '</tr>';
echo '</tbody>';
echo '<tbody></tbody>';
echo '</table>';
echo '<br>';

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	
	echo '<script type="text/javascript">var start_us = true;</script>';
}else{
	echo '<script type="text/javascript">var start_us = false;</script>';
}
echo '<script type="text/javascript" src="/js/js_surfing.js?v=1.01"></script>';

include('includes/stat_links.php');
echo '<br>';

include_once('includes/text_links.php');
echo '<br>';

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	include("includes/vip_task_new.php");
}

$sql = mysql_query("SELECT `kolvo` FROM `tb_ads_date` WHERE `data`='".DATE("d.m.Y", time())."'");
$add_dl_s = mysql_num_rows($sql)>0 ? intval(mysql_result($sql,0,0)) : 0;

$sql = mysql_query("SELECT `kolvo` FROM `tb_ads_date` WHERE `data`='".DATE("d.m.Y", (time()-24*60*60))."'");
$add_dl_v = mysql_num_rows($sql)>0 ? intval(mysql_result($sql,0,0)) : 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_nacenka' AND `howmany`='1'");
$dlink_nacenka = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits_vip' AND `howmany`='1'");
$dlink_min_hits_vip = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
$dlink_cena[1] = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
$dlink_cena[2] = mysql_result($sql,0,0);

$dlink_cena[-1] = $dlink_cena[1];
$dlink_cena[3] = $dlink_cena[1];
$dlink_cena[4] = $dlink_cena[2];
$dlink_cena[10] = $dlink_cena[1];

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_active' AND `howmany`='1'");
$dlink_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_ot' AND `howmany`='1'");
$dlink_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_timer' AND `howmany`='1'");
$dlink_cena_timer = mysql_result($sql,0,0);

echo '<span style="font-weight: bold;">Динамические ссылки:</span><br>';
echo '<b>Добавлено сегодня:<span style="color:#2929ff"><strong> '.$add_dl_s.' </strong></span>шт. Добавлено вчера:<span style="color:#2929ff"><strong> '.$add_dl_v.' </strong></span>шт.</b><br>';
echo '<br>';

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
	$my_ref_back = $my_ref_back;
	$my_country = $my_country;
	$my_lastiplog_ex = explode(".", $laip);
	$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";

	if($my_referer_1 != false) {
		$sql_ref_1 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
		if(mysql_num_rows($sql_ref_1)>0) {
			$row_ref_1 = mysql_fetch_assoc($sql_ref_1);
			$reit_ref_1 = $row_ref_1["reiting"];

			$sql_rang_1 = mysql_query("SELECT `c_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='".floor($reit_ref_1)."'");
			$ref_proc_1 = mysql_num_rows($sql_rang_1)>0 ? mysql_result($sql_rang_1,0,0) : 0;
		}else{
			$my_ref_back = 0; $ref_proc_1 = 0;
		}
	}else{
		$my_ref_back = 0; $ref_proc_1 = 0;
	}
}else{
	$username = false;
	$my_ref_back = 0; $ref_proc_1 = 0;
	$my_fl18 = 0;

	$my_lastiplog = $laip;
	$my_lastiplog_ex = false;
	$my_joindate = false;
	$my_referer_1 = false;
	$my_referer_2 = false;
	$my_referer_3 = false;
	
	$my_sex = false;
	$my_country = false;
	$my_vip_serf = 1;
	$my_ban_serf = 1;
}
//if(($my_ym_purse!= "0" or $my_qw_purse!= "0" or $my_pm_purse!= "0" or $my_py_purse!= "0" or  $my_mb_purse!= "0" or  $my_sb_purse!= "0" or  $my_ac_purse!= "0" or  $my_me_purse!= "0" or  $my_vs_purse!= "0" or  $my_ms_purse!= "0" or  $my_be_purse!= "0" or  $my_mt_purse!= "0" or  $my_mg_purse!= "0" or $my_tl_purse!= "0") and $avatar!= "no.png" and $db_time!="0"){
function format_serf(
	$id, $rek_name, $type_serf, $active, $color, $content, $timer, $nolimit, $plan, $members, $url, $title, $description, $urlbanner_load, $claims, 
	$username, $my_ref_back, $ref_proc_1, $dlink_nacenka, $dlink_cena_hits, $dlink_cena_active, $dlink_cena_timer, $dlink_timer_ot, 
	$virus_id,$my_country 
) {
	$content_img = $content==1 ? '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-right:5px;" src="/img/18+.png" align="absmiddle" />' : false;
	$status_color[0] = 'class="blocks1"';
	$status_color[1] = 'class="blocks2"';
	$status_active[0] = '<img src="img/goserf.png" alt="" title="Стандартный просмотр" width="16" height="16" style="margin:1px 0; padding:0;" />';
	$status_active[1] = '<img src="img/active.png" alt="" title="Просмотр ссылки только в активном окне" width="16" height="16" style="margin:1px 0; padding:0;" />';

	$dlink_click_us = ($dlink_cena_hits + $active * $dlink_cena_active + abs($timer-$dlink_timer_ot) * $dlink_cena_timer);
	$my_ref_back = ( $dlink_click_us * ($ref_proc_1/100) * ($my_ref_back/100) );
	$dlink_click_us = round(p_floor(($dlink_click_us + $my_ref_back), 4), 4);
	
	echo '<tr id="tr'.$id.'" '.($type_serf==10 ? 'style="display:none;"' : 'style="display:;"').'>';
		echo '<td class="td-serf" style="text-align:center; width:18px; padding-left:10px;">';
			echo $status_active[$active];
			if($claims>=10) {
				echo '<img src="img/protect_red.png" alt="" title="Реклама опасна для просмотра" width="16" height="16" style="margin:1px 0; padding:0;" />';
			}else{
				echo '<img src="img/protect_green.png" alt="" title="Реклама безопасна для просмотра" width="16" height="16" style="margin:1px 0; padding:0;" />';
			}
			if($type_serf==2 | $type_serf==4) {
				echo '<img src="https://www.google.com/s2/favicons?domain='.@gethost($url).'" width="16" height="16" border="0" alt="" title="" style="margin:2px 0; padding:0;" align="absmiddle" />';
				echo $content_img;
			}
		echo '</td>';

		echo '<td id="'.$id.'" align="left" class="td-serfm" style="cursor:pointer;">';
			echo '<div id="serf_none_'.$id.'" '.($username==false ? 'onclick="StartSurfing(\''.$id.'\', \''.$url.'\');"' : false).'>';
				if($type_serf==2 | $type_serf==4) {
					echo '<img style="cursor: pointer;" src="'.($urlbanner_load==false ? $description : $urlbanner_load).'" width="468" height="60" border="0" alt="" title="" />';
				}else{
					echo $content_img;
					echo '<img src="https://www.google.com/s2/favicons?domain='.@gethost($url).'" width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-right:5px;" align="absmiddle" />';

					echo '<a '.$status_color[$color].' style="cursor: pointer; font-weight:normal;" target="_blank">';
						echo '<span '.$status_color[$color].'>'.$title.'</span><br>';
						echo '<span style="color:#89A688;">'.$description.'</span>';
					echo '</a>';
				}
			echo '</div>';
		echo '</td>';

		echo '<td nowrap="nowrap" class="td-serf" style="width:100px; text-align:right; padding-right:10px;">';
			if($nolimit>0) {
				echo '<span title="Заказано до '.DATE("d.m.Y H:i", $nolimit).'" style="cursor: help; font-size: 11px; color: #89A688;">('.DATE("d.m.Y", $nolimit).')</span>&nbsp;';
			}else{
				echo '<span title="Осталось визитов '.number_format(($plan - $members), 0, ".", "").'" style="cursor: help; font-size: 11px; color: #89A688;">('.number_format(($plan - $members), 0, ".", "`").')</span>&nbsp;';
			}
			if($username!=false) {
				echo '<span title="Стоимость просмотра '.$dlink_click_us.' руб." style="cursor:help; color:#135B11;">'.$dlink_click_us.'</span><br>';
			}else{
				echo '<br>';
			}
			if($username!=false) {
				echo '<span class="workcomp" id="claims'.$id.'" onClick="ShowHideInfo(\'claims_'.$id.'\');" title="Пожаловаться на рекламу"></span>';
			}

			echo '<span class="workvir" id="id_virus'.$virus_id.'" onClick="TestVirus(\''.$virus_id.'\', \''.$url.'\');" title="Проверить ссылку на вирусы" target="_blank"></span>';

			if($type_serf==3 | $type_serf==4) {
                        echo '<span class="vipserf" title="Реклама VIP"></span>';
                        }

			echo '<span class="workquest" onClick="ShowHideInfo(\'info_serf_'.$id.'\');" title="Подробная информация" target="_blank"></span>';
		echo '</td>';
	echo '</tr>';

	echo '<tr id="info_serf_'.$id.'" style="display: none;">';
		echo '<td colspan="3" align="left" class="td-serf" style="padding-left:30px; color:#696969;">';
			echo 'Сайт: <a href="'.$url.'" target="_blank">'.$url.'</a><br>';
			if($nolimit>0) {
				echo 'Заказано до: '.DATE("d.m.Y H:i", $nolimit).'.<br>';
				echo 'Осталось: '.date_ost(($nolimit-time()), 1).'.<br>';
			}else{
				echo 'Заказано: '.number_format($plan, 0, ".", " ").'.<br>';
				echo 'Осталось визитов: '.number_format(($plan - $members), 0, ".", " ").'.<br>';
			}
			echo 'Просмотрели: '.number_format($members, 0, ".", " ").'.<br>';
			echo (trim($rek_name)!=false ? "Рекламодатель: <b>$rek_name</b><br>": false);
			echo 'Время просмотра: '.$timer.'. Жалобы: '.$claims.'.';
		echo '</td>';
	echo '</tr>';

	echo '<tr id="claims_'.$id.'" style="display:none;">';
		echo '<td colspan="3" align="center" class="td-serf" style="color: #FFFFFF; background-color: #FF9966; padding: 5px 0px; font: 12px Tahoma, Arial, sans-serif;">';
			echo '<div id="info-claims-'.$id.'" style="display:none;"></div>';
			echo '<div id="formclaims'.$id.'"><table style="width:100%; margin:0; padding:0;">';
			echo '<tr>';
				echo '<td align="center" width="100" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;">Текст жалобы:</td>';
				echo '<td align="center" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><div id="newform"><input type="text" id="claimstext'.$id.'" maxlength="100" value="" class="ok" style="margin:0; padding:1px 5px;" /></div></td>';
				echo '<td align="center" width="120" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><span onClick="AddClaims(\''.$id.'\', \'serf\');" class="sub-red" style="float:none;">Отправить</span></td>';
			echo '</tr>';
			echo '</table></div>';
		echo '</td>';
	echo '</tr>';
}

function format_psevdo($id, $color, $plan, $date, $date_end, $url, $description, $members, $virus_id){
	$status_color[0] = 'class="blocks1"';
	$status_color[1] = 'class="blocks2"';

	echo '<tr>';
		echo '<td class="td-serf" valign="top" style="text-align:center; width:18px; padding-left:10px;">';
			echo '<img src="img/protect_green.png" alt="" title="Реклама безопасна для просмотра" width="16" height="16" style="margin:1px 0; padding:0;" />';
		echo '</td>';

		echo '<td align="left" valign="top" class="td-serf">';
			echo '<img src="https://www.google.com/s2/favicons?domain='.@gethost($url).'" width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-right:5px;" align="absmiddle" />';
			echo '<a onClick="StartPsevdo(\'/viewp_sites.php?id='.$id.'\');" '.$status_color[$color].' style="cursor: pointer; font-weight:normal;" target="_blank">'.$description.'</a>';

			echo '<div id="info_psevdo_'.$id.'" style="display: none; margin:0 20px; color:#778899;">';
				echo 'Сайт: <a href="'.$url.'" target="_blank">'.$url.'</a><br>';
				echo 'Заказано дней: '.number_format($plan,0,".","`").'.<br>';
				echo 'Дата заказа: '.DATE("d.m.Y H:i", $date).'.<br>';
				echo 'Заказано до: '.DATE("d.m.Y H:i", $date_end).'.<br>';
				echo 'Осталось: '.date_ost(($date_end-time()), 1).'.<br>';
				echo 'Просмотры: '.number_format($members,0,"."," ");
			echo '</div>';
		echo '</td>';

		echo '<td class="td-serf" nowrap="nowrap" valign="top" style="text-align:right; width:40px; padding-right:10px;">';
			echo '<span class="workvir" id="id_virus'.$virus_id.'" onClick="TestVirus(\''.$virus_id.'\', \''.$url.'\');" title="Проверить ссылку на вирусы" target="_blank"></span>';
			echo '<span class="workquest" onClick="ShowHideInfo(\'info_psevdo_'.$id.'\');" title="Подробная информация" target="_blank"></span>';
		echo '</td>';
	echo '</tr>';
}

$count_sites = 0; $count_sites_p = 0; $virus_id = 0; $rnd_limit = mt_rand(13,16);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	
	$sql_links = mysql_query("SELECT * FROM `tb_ads_dlink` 
		WHERE `status`='1' AND ( `totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) 
		AND ( 
			( `type_serf` IN ('-1','1','2','3','4','10') AND '$my_vip_serf'>'0' AND '$my_ban_serf'>'0' ) OR 
			( `type_serf` IN ('-1','1','2','10') OR (`type_serf`='3' AND '$my_vip_serf'>'0') ) OR 
			( `type_serf` IN ('-1','1','2','10') OR (`type_serf`='4' AND '$my_ban_serf'>'0') ) 
		)
		AND (`limit_d`='0' OR (`limit_d`>'0' AND `limit_d_now`>'0') ) 
		AND (`limit_h`='0' OR (`limit_h`>'0' AND `limit_h_now`>'0') ) 
		AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%' OR `username`='$username') 
		AND (`sex_adv`='0' OR `sex_adv`='$my_sex' OR `username`='$username') 
		AND (`new_users`='0' OR (`new_users`='1' AND '$my_joindate'>='".(time()-7*86400)."') OR `username`='$username' ) 
		AND (`no_ref`='0' OR (`no_ref`='1' AND '$my_referer_1'='') OR `username`='$username' ) 
		AND (`content`='0' OR (`content`='1' AND '$my_fl18'='0') OR `username`='$username' ) 
		AND (`to_ref`='0' 
			OR (`to_ref`='1' AND `username`!='' AND (`username`='$username' OR `username`='$my_referer_1') ) 
			OR (`to_ref`='2' AND `username`!='' AND (`username`='$username' OR `username`='$my_referer_1' OR `username`='$my_referer_2' OR `username`='$my_referer_3' OR `username`='$my_referer_4' OR `username`='$my_referer_5') ) 
		) 
		AND `id` NOT IN (
			SELECT `ident` FROM `tb_ads_dlink_visits` 
			WHERE `time_next`>='".time()."' 
			AND (
				   ( `tb_ads_dlink`.`unic_ip`='0' AND `username`='$username' ) 
				OR ( `tb_ads_dlink`.`unic_ip`='1' AND (`username`='$username' OR `ip`='$my_lastiplog') ) 
				OR ( `tb_ads_dlink`.`unic_ip`='2' AND (`username`='$username' OR `ip` LIKE '%$my_lastiplog_ex%') ) 
			)
		) 
		ORDER BY `up_list` DESC, `id` DESC
	");
}else{
	$sql_links = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE 
		`status`='1' AND ( `totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) AND 
		(`limit_d`='0' OR (`limit_d`>'0' AND `limit_d_now`>'0') ) AND (`limit_h`='0' OR (`limit_h`>'0' AND `limit_h_now`>'0') ) 
		ORDER BY `up_list` DESC, `id` DESC
	");
}

if(mysql_num_rows($sql_links)>0) {
	echo '<table class="table-serf" style="margin-bottom:15px;">';
	echo '<tbody>';
		echo '<tr><td align="center" class="td-serf" colspan="3"><h2 class="sp" style="margin:0; padding:0;">Зарабатывай, просматривая сайты рекламодателей</h2></td></tr>';

		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
			if( isset($my_vip_serf) && isset($my_ban_serf) ) {
                              $all_vip_serf = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='1' AND `type_serf`='3'"));
			       $all_ban_serf = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='1' AND `type_serf`='4'"));
				echo '<tr>';
					echo '<td align="center" class="td-serf" colspan="3">';
						echo '<span class="warning-info1" style="margin-bottom:0; font-weight:normal;">';
							if($my_vip_serf<=0) {
								echo '<div>Для заработка на ссылках в VIP серфинге, Вам необходимо разместить ссылку в VIP серфинге не менее чем на '.$dlink_min_hits_vip.' просмотров и Вам откроется доступ на 200 просмотров.</div>';
                                                                echo '<div>Всего Ссылки в VIP серфинге: - <b>'.$all_vip_serf.'</b></div>';
							}else{
								echo '<div>Ссылки в VIP серфинге: Вам осталось просмотров - <b>'.$my_vip_serf.'</b></div>';
                                                                echo '<div>Всего Ссылки в VIP серфинге: - <b>'.$all_vip_serf.'</b></div>';
							}
							if($my_ban_serf<=0) {
								echo '<div style="margin-top:5px;">Для заработка на баннерах в VIP серфинге, Вам необходимо разместить ссылку или баннер в VIP серфинге не менее чем на '.$dlink_min_hits_vip.' просмотров и Вам откроется доступ на 200 просмотров.</div>';
                                                                echo '<div style="margin-top:5px;">Всего Баннеров в VIP серфинге: - <b>'.$all_ban_serf.'</b></div>';
							}else{
								echo '<div style="margin-top:5px;">Баннеры в VIP серфинге: Вам осталось просмотров - <b>'.$my_ban_serf.'</b></div>';
                                                                echo '<div style="margin-top:5px;">Всего Баннеров в VIP серфинге: - <b>'.$all_ban_serf.'</b></div>';
							}
							if($my_vip_serf<=0 | $my_ban_serf<=0) {
								echo '<div style="margin-top:5px;"><u>Оплату рекламы необходимо производить только с рекламного счета!</u></div>';
								echo '<div style="margin-top:2px;"><span onClick="document.location.href=\'/advertise.php?ads=dlink\'" target="_blank" style="cursor:pointer; color:red;">&gt;&gt; Перейти к заказу VIP серфинга и активировать доступ &lt;&lt;</span></div>';
							}
						echo '</span>';
					echo '</td>';
				echo '<tr>';
			}
		}
		
		
		while($links_row = mysql_fetch_assoc($sql_links)) {
			if($links_row["type_serf"]!=10) $count_sites++;
			$virus_id++;

			format_serf(
				$links_row["id"], 
				$links_row["username"],
				$links_row["type_serf"], 
				$links_row["active"], 
				$links_row["color"], 
				$links_row["content"], 
				$links_row["timer"], 
				$links_row["nolimit"], 
				$links_row["plan"], 
				$links_row["members"], 
				$links_row["url"], 
				$links_row["title"], 
				$links_row["description"], 
				$links_row["urlbanner_load"], 
				$links_row["claims"], 

				$username, $my_ref_back, $ref_proc_1, 
				$dlink_nacenka, $dlink_cena[$links_row["type_serf"]], $dlink_cena_active, $dlink_cena_timer, $dlink_timer_ot, 
				$virus_id
			);
		}
	echo '</tbody>';
	echo '</table>';
}

if($count_sites==0) {
	echo '<div align="center" style="margin-bottom:15px;">';
		echo '<div style="background: url(img/light.png) no-repeat top center; font-size: 14px; color: #ab0606; text-align: center; padding-top: 120px; margin: 20px auto 15px; display: block;">Доступных к просмотру рекламных ссылок нет.<br>Заходите позже</div>';
		echo '<div align="center" onClick="document.location.href=\'/advertise.php?ads=dlink\';" class="proc-btn">Разместить ссылку</div>';
	echo '</div>';
}

$sql_links = mysql_query("SELECT * FROM `tb_ads_psevdo` WHERE `status`='1' AND `date_end`>'".time()."' ORDER BY `id` DESC");
if(mysql_num_rows($sql_links)>0) {
	echo '<table class="table-serf" style="margin-bottom:15px;">';
	echo '<tbody>';
		echo '<tr><td align="center" class="td-serf" colspan="3"><h2 class="sp" style="margin:0; padding:0;">Псевдо-динамические ссылки (не оплачиваются)</h2></td></tr>';

		while($links_row = mysql_fetch_assoc($sql_links)) {
			$count_sites_p++;
			$virus_id++;

			format_psevdo(
				$links_row["id"], 
				$links_row["color"], 
				$links_row["plan"], 
				$links_row["date"], 
				$links_row["date_end"], 
				$links_row["url"], 
				$links_row["description"], 
				$links_row["members"],
				$virus_id
			);
		}
	echo '</tbody>';
	echo '</table>';
}

if($count_sites!=0 | $count_sites_p!=0) {
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

	echo '<table align="center" style="width:auto; margin:0 auto;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="18" align="center"><img src="/img/18+.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
			echo '<td align="left">На сайте присутствуют материалы для взрослых</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
}

//}else{
//echo '<span class="msg-warning">Для работы вам надо заполнить профиль указать дату рождения <br>установить аватар и указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span>';
//}
//}
include("footer.php");
?>