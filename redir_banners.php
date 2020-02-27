<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require($_SERVER["DOCUMENT_ROOT"]."/merchant/func_cache.php");

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(htmlspecialchars(trim($_GET["id"]))) : false;

if($id != false) {
	require($_SERVER["DOCUMENT_ROOT"]."/config.php");

	mysql_query("UPDATE `tb_ads_banner` SET `status`='3' WHERE `status`='1' AND `date_end`<'".time()."' ") or die(mysql_error());

	$sql_id = mysql_query("SELECT `id`,`url` FROM `tb_ads_banner` WHERE `id`='$id' AND `status`='1'");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_row($sql_id);
		$id = $row["0"];
		$url_site = $row["1"];

		mysql_query("UPDATE `tb_ads_banner` SET `members`=`members`+'1', `outside`=`outside`+'1' WHERE `id`='$id'") or die(mysql_error());;
		cache_banners();
		mysql_close();

		echo '<script type="text/javascript">location.replace("'.$url_site.'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$url_site.'"></noscript>';
	}else{
		cache_banners();
		mysql_close();

		echo '<script type="text/javascript">location.replace("/");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
	}
}else{
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
}
?>