<?php
$pagetitle = "Оплачиваемые посещения";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"], $_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	if(($my_ym_purse!= "0" or $my_qw_purse!= "0" or $my_pm_purse!= "0" or $my_py_purse!= "0" or  $my_mb_purse!= "0" or  $my_sb_purse!= "0" or  $my_ac_purse!= "0" or  $my_me_purse!= "0" or  $my_vs_purse!= "0" or  $my_ms_purse!= "0" or  $my_be_purse!= "0" or  $my_mt_purse!= "0" or  $my_mg_purse!= "0" or $my_tl_purse!= "0") and $db_time!="0"){
	
	echo "<script src=\"/js/js_work_pay_visits.js?v=1.00\"></script>\r\n";
	
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
	include_once("includes/stat_links.php"); echo '<br>';

	require(ROOT_DIR."/config_mysqli.php");
	include_once(ROOT_DIR."/geoip/geoipcity.inc");
	$gi = geoip_open(ROOT_DIR."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
	$security_key = "lilacbux^(*pay_visit7&&9unjhu78*";

	function SqlConfig($item, $howmany=1, $decimals=false){
		global $mysqli;

		$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='$item' AND `howmany`='$howmany'") or die($mysqli->error);
		$price = $sql->num_rows > 0 ? $sql->fetch_object()->price : die("Error: item['$item'] or howmany['$howmany'] not found in table `tb_config`");
		$sql->free();

		return ($decimals!==false && is_numeric($price)) ? round($price, $decimals) : $price;
	}
	$pvis_comis_sys = SqlConfig('pvis_comis_sys', 1, 0);

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
	$record = @geoip_record_by_addr($gi, $laip);
	$my_country = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : $my_country;
	$my_ref_back = $my_ref_back;
	$my_lastiplog = $laip;
	$my_lastiplog_ex = explode(".", $my_lastiplog);
	$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";
	$my_day_reg = floor((time() - $my_joindate)/(24*60*60));
	$my_no_ref = $my_referer_1==false ? 1 : 0;

	if($my_referer_1 != false) {
		$sql = $mysqli->query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_1'") or die($mysqli->error);
		if($sql->num_rows > 0) {
			$reit_ref[1] = $sql->fetch_object()->reiting;
			$sql->free();

			$sql = $mysqli->query("SELECT `pv_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref[1]' AND `r_do`>='".floor($reit_ref[1])."'") or die($mysqli->error);
			$ref_proc[1] = $sql->num_rows > 0 ? $sql->fetch_object()->pv_1 : 0;
			$sql->free();
		}else{
			$sql->free();
			$my_ref_back = 0; $ref_proc[1] = 0;
		}
	}else{
		$my_ref_back = 0; $ref_proc[1] = 0;
	}

	function ListWork($row_adv) {
		global $pvis_comis_sys, $ref_proc, $my_ref_back, $security_key, $username, $my_lastiplog, $my_lastiplog_ex;

		$cnt_totals = floor(bcdiv($row_adv["balance"], $row_adv["price_adv"]));
		$token_work = strtolower(md5($row_adv["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-start-work".$security_key));
		$token_claims = strtolower(md5($row_adv["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-claims-form".$security_key));

		$cena_hit_user = ($row_adv["price_adv"]/(1+$pvis_comis_sys/100));

		$_SumNac = ($row_adv["price_adv"] - $cena_hit_user);
		$user_ref_back = ($_SumNac * ($ref_proc[1]/100) * ($my_ref_back/100) );
		$cena_pv = ($cena_hit_user + $user_ref_back);
		$cena_pv = my_num_format($cena_pv, 4, ".", "", 2);

		echo '<tr id="tr-adv-'.$row_adv["id"].'">';
			echo '<td><span class="img-work click-blue" onClick="SHBlock(\'tr-info-'.$row_adv["id"].'\');" title="Подробная информация"></span></td>';

			echo '<td class="td-work" id="td-work-'.$row_adv["id"].'" data-id="'.$row_adv["id"].'" data-op="start-work" data-token="'.$token_work.'">';
				echo '<img class="favicon" src="//www.google.com/s2/favicons?domain='.gethost($row_adv["url"]).'" alt="" title="" />';
				echo '<span class="'.($row_adv["color"]==1 ? "text-title-hl" : "text-title").'" title="'.$row_adv["url"].'">'.$row_adv["title"].'</span><br>';
				echo '<span class="text-desc">'.$row_adv["description"].'</span>';
			echo '</td>';

			echo '<td>';
				echo '<div>';
					echo '<span class="text-totals" title="Осталось визитов">['.number_format($cnt_totals, 0, ".", "").']</span>';
					echo '<span class="text-price" title="Стоимость просмотра '.$cena_pv.' руб.">'.$cena_pv.'</span>';
				echo '</div>';
				echo '<div style="margin-top:5px;">';
					echo '<span class="workcomp" onClick="FuncWork('.$row_adv["id"].', \'claims-form\', \''.$token_claims.'\', \'modal\', 525, \'Жалоба на рекламную площадку ID:<b>'.$row_adv["id"].'</b>\');" title="Пожаловаться на рекламу"></span>';
					echo '<span id="id-vir-'.$row_adv["id"].'" class="workvir" onClick="CheckVir($(this).attr(\'id\'), \''.$row_adv["url"].'\');" title="Проверить ссылку на вирусы"></span>';
					//echo '<span class="workquest" onClick="SHBlock(\'tr-info-'.$row_adv["id"].'\');" title="Подробная информация"></span>';
					if($row_adv["content"]==1) echo '<span class="plus18" title="На сайте присутствуют материалы для взрослых"></span>';
				echo '</div>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="tr-info-'.$row_adv["id"].'" style="display:none;">';
			echo '<td colspan="3" class="td-info">';
				echo '<div>ID: <b>'.$row_adv["id"].'</b></div>';
				echo '<div>Сайт: <a>'.$row_adv["url"].'</a></div>';
				echo '<div>'.($row_adv["username"]!=false ? '<span style="padding-right:7px;">Рекламодатель: <b>'.$row_adv["username"].'</b></span>' : false).'<span style="padding-right:7px;">Дата размещения: '.DATE("d.m.Y в H:i", $row_adv["date"]).'</span></div>';
				echo '<div><span style="padding-right:7px;">Посещений: <b>'.number_format($row_adv["cnt_visits_now"], 0, ".", "`").'</b></span><span style="padding-right:7px;">Осталось: <b>'.number_format($cnt_totals, 0, ".", "`").'</b></span></div>';
				echo '<div>Жалобы: '.($row_adv["cnt_claims"]>0 ? '<b class="text-red">'.$row_adv["cnt_claims"].'</b>' : '<b>'.$row_adv["cnt_claims"].'</b>').'</div>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="tr-hide-'.$row_adv["id"].'" style="display:none;"></tr>';
	}

	$sql_adv = $mysqli->query("SELECT * FROM `tb_ads_pay_vis` WHERE `status`='1' AND `balance`>=`price_adv` 
		AND `date_reg_user`<='$my_day_reg' AND `reit_user`<='$my_id_rang' AND `no_ref`<='$my_no_ref' 
		AND (`sex_user`='0' OR `sex_user`='$my_sex') 
		AND (`to_ref`='0' 
			OR (`to_ref`='1' AND `username`!='' AND (`username`='$my_referer_1') ) 
			OR (`to_ref`='2' AND `username`!='' AND (`username`='$my_referer_1' OR `username`='$my_referer_2' OR `username`='$my_referer_3') ) 
		) 
		AND (`content`='0' OR `content`!='$my_fl18') 
		AND (`geo_targ`='' OR `geo_targ` REGEXP '$my_country') 
		AND `id` NOT IN (
			SELECT `ident` FROM `tb_ads_pay_visits` WHERE `status`='1' AND `time_next`>='".time()."' AND (
				(`tb_ads_pay_vis`.`uniq_ip`='0' AND `user_name`='$username') 
			     OR (`tb_ads_pay_vis`.`uniq_ip`='1' AND (`user_name`='$username' OR `ip`='$my_lastiplog')) 
			     OR (`tb_ads_pay_vis`.`uniq_ip`='2' AND (`user_name`='$username' OR `ip` REGEXP '$my_lastiplog_ex'))
			)
		)
	ORDER BY `date_up` DESC") or die($mysqli->error);

	if($sql_adv->num_rows > 0) {
		echo '<table class="tab-work" style="margin-top:10px;">';
		echo '<tbody>';
		echo '<tr><td class="td-top" colspan="3"><h2 class="sp" style="margin:0; width:760px; padding:0;">Зарабатывай, посещая сайты рекламодателей</h2></td></tr>';

		while ($row_adv = $sql_adv->fetch_assoc()) {
			ListWork($row_adv);
		}

		echo '</tbody>';
		echo '</table>';
	}else{
		echo '<div align="center" style="margin-bottom:15px;">';
			echo '<div style="background: url(img/light.png) no-repeat top center; font-size: 14px; color: #ab0606; text-align: center; padding-top: 120px; margin: 20px auto 15px; display: block;">Доступных к просмотру рекламных ссылок нет.<br>Заходите позже</div>';
			echo '<div align="center" onClick="document.location.href=\'/advertise.php?ads=pay_visits\';" class="proc-btn">Разместить ссылку</div>';
		echo '</div>';
	}
	$sql_adv->free();

	$mysqli->close();
	
	}else{
echo '<span class="msg-warning">Для работы вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше имя (которое будет отображатся на стене)<br> указать дату рождения <br> указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span></span>';
}
//}
}
include(ROOT_DIR."/footer.php");
?>