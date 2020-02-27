<?php
session_start();
//error_reporting (E_ALL);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=cp1251">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta name="Author" content="supreme-garden.ru">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="favicon.png" />
	<title><?php echo strtoupper($_SERVER["HTTP_HOST"]);?> | Автоматический серфинг</title>
</head>

<body oncontextmenu="javascript:return false" onUnLoad="window.parent.opener.i--" onload="do_count();">

<script type="text/javascript" src="../scripts/self_a.js"></script>
<noscript><meta http-equiv="refresh" content="0;url=nojs.php"></noscript>

<?php

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	require('../config.php');
	require('../funciones.php');

	mysql_query("UPDATE `tb_ads_auto` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

	$username = uc($_SESSION["userLog"]);
	$laip = getRealIP();
	
	$sql_user = mysql_query("SELECT * FROM tb_users WHERE username='$username'");
	$row_user = mysql_fetch_array($sql_user);
	$my_country = $row_user["country_cod"];

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_as' AND `howmany`='1'");
	$reit_as = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
	$cena_hits_aserf = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
	$timer_aserf_ot = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_timer_aserf' AND `howmany`='1'");
	$cena_timer_aserf = mysql_result($sql,0,0);

	if(isset($_GET["st"]) && isset($_GET["b"]) && intval($_GET["b"])==1) {
		$id = (isset($_GET["st"])) ? intval($_GET["st"]) : false;

		if($id>0) {
			$sql_id = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `status`='1' AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') AND `totals`>'0' AND `limits_now`>'0'");
			if(mysql_num_rows($sql_id)>0) {
				$sql_v = mysql_query("SELECT * FROM `tb_ads_auto_visits` WHERE `username`='$username' AND `type`='claims' AND `ident`='$id'") or die(mysql_error());
				if(mysql_num_rows($sql_v)>0) {
					mysql_query("UPDATE `tb_ads_auto` SET `claims`=`claims`+1 WHERE `id`='$id' AND `status`='1'") or die(mysql_error());
					mysql_query("UPDATE `tb_ads_auto_visits` SET `date`='".time()."', `ip`='$laip', `money`='0.0000' WHERE `username`='$username' AND `type`='claims' AND `ident`='$id'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_auto` SET `claims`=`claims`+1 WHERE `id`='$id' AND `status`='1' AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%')") or die(mysql_error());
					mysql_query("INSERT INTO `tb_ads_auto_visits` (`username`, `type`, `ident`, `date`, `ip`, `money`) VALUES('$username', 'claims', '".$id."', '".time()."', '$laip', '0.0000')") or die(mysql_error());
				}
			}
		}
	}

	if(isset($_GET["st"]) && !isset($_GET["b"])) {
		$id = (isset($_GET["st"])) ? intval($_GET["st"]) : false;

		if($id>0 && isset($_SESSION["endtmr_auto"]) && intval($_SESSION["endtmr_auto"]) <= time()) {

			$sql_id = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `status`='1' AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') AND `totals`>'0' AND `limits_now`>'0'");
			if(mysql_num_rows($sql_id)>0) {
				$rowid = mysql_fetch_array($sql_id);

				$rek_name = $rowid["username"];
				$plan = $rowid["plan"];
				$members = $rowid["members"];
				$timer = $rowid["timer"];
				$url = $rowid["url"];
				$description = $rowid["description"];
				$_SESSION["hash1"] = md5(limpiar($id.$plan.$members.$timer.$_SESSION["endtmr_auto"]));

				$cena_click = round((abs($timer - $timer_aserf_ot) * $cena_timer_aserf + $cena_hits_aserf),4);

				$sql_user = mysql_query("SELECT `ban_date` FROM `tb_users` WHERE `username`='$username'");
				$row_user = mysql_fetch_assoc($sql_user);
				if(mysql_num_rows($sql_user)>0) {
					$my_ban_date = $row_user["ban_date"];
				}else{
					$my_ban_date = 0;
				}

				$sql_v = mysql_query("SELECT * FROM `tb_ads_auto_visits` WHERE `username`='$username' AND `type`='visit' AND `ident`='$id'") or die(mysql_error());
				if(mysql_num_rows($sql_v)>0) {
					$rowv = mysql_fetch_array($sql_v);

					$date_visit = $rowv["date"];
					$crok1 = time();
					$crok2 = ($date_visit + (24 * 60 * 60));

					if($crok1 >= $crok2) {
						mysql_query("UPDATE `tb_ads_auto` SET `limits_now`=`limits_now`-'1', `totals`=`totals`-'1', `members`=`members`+'1', `outside`=`outside`+'1' WHERE `id`='$id' AND `status`='1' AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') AND `totals`>'0' AND `limits_now`>'0'") or die(mysql_error());
						mysql_query("UPDATE `tb_ads_auto_visits` SET `date`='".time()."', `ip`='$laip', `money`='$cena_click' WHERE `username`='$username' AND `type`='visit' AND `ident`='$id'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `visits_a`=`visits_a`+'1', `reiting`=`reiting`+'$reit_as', `money`=`money`+'$cena_click', `money_a`=`money_a`+'$cena_click' WHERE `username`='$username'") or die(mysql_error());

						### START СБОР СТАТИСТИКИ ####
						stats_users($username, strtolower(DATE("D")), 'auto_serf');
						### END СБОР СТАТИСТИКИ ######

						### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
						if(strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
							$konk_complex_status = mysql_result($sql,0,0);

							if($konk_complex_status==1) {
								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
								$konk_complex_date_start = mysql_result($sql,0,0);

								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
								$konk_complex_date_end = mysql_result($sql,0,0);

								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_aserf'") or die(mysql_error());
								$konk_complex_point = mysql_result($sql,0,0);

								if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
									mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die(mysql_error());
								}
							}
						}
						### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
					}else{
						echo '<script>window.parent.frames["site"].location.replace("/");</script>';
					}
				}else{
					mysql_query("UPDATE `tb_ads_auto` SET `limits_now`=`limits_now`-'1', `totals`=`totals`-'1', `members`=`members`+'1', `outside`=`outside`+'1' WHERE `id`='$id' AND `status`='1' AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') AND `totals`>'0' AND `limits_now`>'0'") or die(mysql_error());
					mysql_query("INSERT INTO `tb_ads_auto_visits` (`username`, `type`, `ident`, `date`, `ip`, `money`) VALUES('$username', 'visit', '".$id."', '".time()."', '$laip', '$cena_click')") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `visits_a`=`visits_a`+'1', `reiting`=`reiting`+'$reit_as', `money`=`money`+'$cena_click', `money_a`=`money_a`+'$cena_click' WHERE `username`='$username'") or die(mysql_error());

					### START СБОР СТАТИСТИКИ ####
					stats_users($username, strtolower(DATE("D")), 'auto_serf');
					### END СБОР СТАТИСТИКИ ######

					### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
					if(strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
						$konk_complex_status = mysql_result($sql,0,0);

						if($konk_complex_status==1) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
							$konk_complex_date_start = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
							$konk_complex_date_end = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_aserf'") or die(mysql_error());
							$konk_complex_point = mysql_result($sql,0,0);

							if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
								mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die(mysql_error());
							}
						}
					}
					### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###

					echo '<script>window.parent.frames["site"].location.replace("/");</script>';
				}
			}else{
				echo '<script>window.parent.frames["site"].location.replace("/");</script>';
			}
		}else{
			echo '<script>window.parent.frames["site"].location.replace("/");</script>';
		}
	}

	$sql = mysql_query("SELECT `id`,`timer`,`url`,`description` FROM `tb_ads_auto` WHERE `status`='1' AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') AND `totals`>'0' AND `limits_now`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_auto_visits` WHERE `username`='$username' AND `date`>='".(time()-24*60*60)."') ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$id = $row["id"];
		$timer = $row["timer"];
		$url = $row["url"];
		$description = $row["description"];

		$cena_click = round((abs($timer - $timer_aserf_ot) * $cena_timer_aserf + $cena_hits_aserf),4);
		$date_now = strtotime(DATE("d.m.Y"));

		$sql_pay = mysql_query("SELECT `id` FROM `tb_ads_auto_visits` WHERE `username`='$username' AND `type`='visit' AND `date`>'$date_now'") or die(mysql_error());
		$kolvo_s = mysql_num_rows($sql_pay);
		?>

		<script type="text/javascript" language="JavaScript">
			var counter=1+parseInt(<?php echo $timer;?>);
			var flag=0;

			function do_count(){
				<?php $_SESSION["endtmr_auto"] = ($timer + time());?>
				if(flag==0) {
					counter--;
					document.getElementById('begin').innerHTML='';
				}
				if(flag==1) {
					document.getElementById('begin').innerHTML='[Запустить таймер]';
				}
				if(counter>=0) {
					document.getElementById('tmr').innerHTML=''+counter+'';
					setTimeout("do_count()",1000);
				}
				if(counter<0){
					window.parent.frames['head'].location.replace("<?php echo $_SERVER['PHP_SELF'];?>?st=<?php echo $id;?>");
				}
			}
		</script>

		<table border="1" style="width: 100%; height: 72px; border-collapse: collapse; border: 1px solid #000; border-bottom:1px;" cellpadding="0" cellspacing="0">
		<tr>
		<th width="160" height="15px" align="center" bgcolor="#D1EEEE" style="border-bottom: 1 px solid #94FFB8;">Таймер</th>
		<th width="220" height="15px" align="center" bgcolor="#D1EEEE" style="border-bottom: 1 px solid; #94FFB8">Статистика</th>
		<th height="15px" align="center" bgcolor="#D1EEEE" style="border-bottom: 1 px solid; #94FFB8">Информация о ссылке</th>
		<th width="220" height="15px" align="center" bgcolor="#D1EEEE" style="border-bottom: 1 px solid; #94FFB8">Действия</th>
		</tr>
		<tr>
			<td align="center" style="border-bottom: 1 px solid #94FFB8;">Ждите <span id="tmr" style="color: #FF0000;"></span> секунд <span id="begin" style="color: #ff0000;"></span></td>
			<td style="font-size: 80%; padding: 0 5px; 0 5px;">Стоимость просмотра: <b style="color: #000;"><?php echo $cena_click;?></b> руб.<br>Просмотрено ссылок <?php echo DATE("d.m.Yг.");?>: <b style="color: #000;"><?php echo $kolvo_s;?></b></td>
			<td style="font-size: 80%; padding: 0 5px; 0 5px;"><b>Описание:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $description;?><br><b>Адрес сайта:</b>&nbsp;&nbsp;<a href="<?php echo $row["url"];?>" target="_blank"><?php echo $url;?></a></td>
			<td style="font-size: 80%; padding: 0 5px; 0 5px;"><a href="<?php echo $url;?>" target="_blank">Открыть ссылку в новом окне</a><br><a href="auto_s.php?st=<?php echo $id;?>&amp;b=1" onmouseover="window.status=''; return true" onmouseout="window.status=''; return false">Заблокировать ссылку</a></td>
		</tr>
		</table>

		<script type="text/javascript" language="JavaScript">
			window.parent.document.title="<?php echo strtoupper($_SERVER["HTTP_HOST"]);?> | Автоматический серфинг | <?php echo $description;?>";
			window.parent.frames['site'].location.replace("<?php echo $url;?>");
			window.parent.frames['head'].onblur = new Function('this.flag = 0;');
			window.parent.frames['head'].onfocus = new Function('this.flag = 0;');
		</script>
		<?php
	}else{
		echo '<script type="text/javascript" language="JavaScript">window.parent.frames["site"].location.replace("auto_no.php");</script>';
	}
	@mysql_close();
}else{
	echo '<br><center><b style="color: #FF0000;">Ошибка! Для просмотра ссылок в авто-серфинге необходимо авторизоваться.</b></center>';
	echo '<script type="text/javascript" language="JavaScript">window.parent.frames["site"].location.replace("auto_no.php");</script>';
}
?>

</body>
</html>