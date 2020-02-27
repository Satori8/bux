<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require($_SERVER["DOCUMENT_ROOT"]."/funciones.php");
require($_SERVER["DOCUMENT_ROOT"]."/merchant/func_cache.php");

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(htmlspecialchars(trim($_GET["id"]))) : false;
$laip = getRealIP();

if($id != false) {
	require($_SERVER["DOCUMENT_ROOT"]."/config.php");

	mysql_query("DELETE FROM `tb_ads_kontext_visits` WHERE `visit_time`<='".time()."'") or die(mysql_error());

	$sql_id = mysql_query("SELECT `id`,`url` FROM `tb_ads_kontext` WHERE `id`='$id' AND `status`='1' AND `totals`>'0'");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_row($sql_id);
		$id = $row["0"];
		$url_site = $row["1"];

		$sql_v = mysql_query("SELECT `visit_time` FROM `tb_ads_kontext_visits` WHERE `ident`='$id' AND `ip`='$laip'");
		if(mysql_num_rows($sql_v)>0) {
			$row_v = mysql_fetch_row($sql_v);

			if($row_v["0"] < time()) {
				mysql_query("UPDATE `tb_ads_kontext_visits` SET `visit_time`='".(time()+24*60*60)."' WHERE `ident`='$id' AND `ip`='$laip'") or die(mysql_error());

				mysql_query("UPDATE `tb_ads_kontext` SET `totals`=`totals`-'1', `views`=`views`+'1', `outside`=`outside`+'1' WHERE `id`='$id'") or die(mysql_error());;
			}
		}else{
			mysql_query("INSERT INTO `tb_ads_kontext_visits` (`ident`,`visit_time`,`ip`) 
			VALUES('$id','".(time()+24*60*60)."','$laip')") or die(mysql_error());

			mysql_query("UPDATE `tb_ads_kontext` SET `totals`=`totals`-'1', `views`=`views`+'1', `outside`=`outside`+'1' WHERE `id`='$id'") or die(mysql_error());;
		}
		mysql_query("UPDATE `tb_ads_kontext` SET `status`='3' WHERE `status`='1' AND `totals`<'1' ") or die(mysql_error());

		cache_kontext();
		mysql_close();
		echo '<script type="text/javascript">location.replace("'.$url_site.'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$url_site.'"></noscript>';
	}else{
		cache_kontext();
		mysql_close();
		echo '<script type="text/javascript">location.replace("/");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
	}
}else{
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
}
?>