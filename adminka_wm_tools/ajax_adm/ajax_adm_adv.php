<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
sleep(0);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {

	require(DOC_ROOT."/config.php");
	require(DOC_ROOT."/funciones.php");
	require(DOC_ROOT."/merchant/func_mysql.php");
	require(DOC_ROOT."/merchant/func_cache.php");

	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiar($_POST["id"])))) ? intval(limpiar($_POST["id"])) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiar($_POST["op"])) ) ? limpiar($_POST["op"]) : false;
	$type_ads = ( isset($_POST["type"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", limpiar($_POST["type"])) ) ? limpiar($_POST["type"]) : false;
	$laip = getRealIP();

	if($type_ads!=false && $type_ads=="dlink") {

		if($option == "play_pause") {
			$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$status = $row["status"];
				$nolimit = $row["nolimit"];

				if($status == 1) {
					if($nolimit!=0) {
						exit("BEZLIMIT");
					}else{
						mysql_query("UPDATE `tb_ads_dlink` SET `status`='2' WHERE `id`='$id'") or die(mysql_error());
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'dlink\');"></span>';
						exit();
					}
				}elseif($status == 2) {
					if($nolimit!=0) {
						exit("BEZLIMIT");
					}else{
						mysql_query("UPDATE `tb_ads_dlink` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());
						echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'dlink\');"></span>';
						exit();
					}
				}elseif($status == 3) {
					if($nolimit!=0) {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
						exit();
					}else{
						exit("0");
					}
				}else{
					exit("ERROR");
				}
			}else{
				exit("ERRORNOID");
			}
		}
		exit();
	}elseif($type_ads!=false && $type_ads=="youtube") {

		if($option == "play_pause") {
			$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$status = $row["status"];
				$nolimit = $row["nolimit"];

				if($status == 1) {
					if($nolimit!=0) {
						exit("BEZLIMIT");
					}else{
						mysql_query("UPDATE `tb_ads_youtube` SET `status`='2' WHERE `id`='$id'") or die(mysql_error());
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'youtube\');"></span>';
						exit();
					}
				}elseif($status == 2) {
					if($nolimit!=0) {
						exit("BEZLIMIT");
					}else{
						mysql_query("UPDATE `tb_ads_youtube` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());
						echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'youtube\');"></span>';
						exit();
					}
				}elseif($status == 3) {
					if($nolimit!=0) {
						echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="alert_nostart();"></span>';
						exit();
					}else{
						exit("0");
					}
				}else{
					exit("ERROR");
				}
			}else{
				exit("ERRORNOID");
			}
		}
		exit();
	}elseif($type_ads!=false && $type_ads=="banserf") {

		if($option == "play_pause") {
			$sql = mysql_query("SELECT * FROM `tb_ads_bs` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$status = $row["status"];

				if($status == 1) {
					mysql_query("UPDATE `tb_ads_bs` SET `status`='2' WHERE `id`='$id'") or die(mysql_error());
					echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'banserf\');"></span>';
					exit();
				}elseif($status == 2) {
					mysql_query("UPDATE `tb_ads_bs` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());
					echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'banserf\');"></span>';
					exit();
				}elseif($status == 3) {
					exit("0");
				}else{
					exit("ERROR");
				}
			}else{
				exit("ERRORNOID");
			}
		}
		exit();

	}elseif($type_ads!=false && $type_ads=="autoserf") {

		if($option == "play_pause") {
			$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$status = $row["status"];

				if($status == 1) {
					mysql_query("UPDATE `tb_ads_auto` SET `status`='2' WHERE `id`='$id'") or die(mysql_error());
					echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'autoserf\');"></span>';
					exit();
				}elseif($status == 2) {
					mysql_query("UPDATE `tb_ads_auto` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());
					echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'autoserf\');"></span>';
					exit();
				}elseif($status == 3) {
					exit("0");
				}else{
					exit("ERROR");
				}
			}else{
				exit("ERRORNOID");
			}
		}
		exit();

	}elseif($type_ads!=false && $type_ads=="mails") {

		if($option == "play_pause") {
			$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$status = $row["status"];

				if($status == 1) {
					mysql_query("UPDATE `tb_ads_mails` SET `status`='2' WHERE `id`='$id'") or die(mysql_error());
					echo '<span class="adv-play" title="Запустить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
					exit();
				}elseif($status == 2) {
					mysql_query("UPDATE `tb_ads_mails` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());
					echo '<span class="adv-pause" title="Приостановить показ рекламной площадки" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
					exit();
				}elseif($status == 3) {
					exit("0");
				}else{
					exit("ERROR");
				}
			}else{
				exit("ERRORNOID");
			}
		}
		exit();

	}else{
		exit("Ошибка! Не удалось обработать запрос!");
	}
}else{
	exit("Ошибка! Не удалось обработать запрос!");
}

?>