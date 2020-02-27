<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require($_SERVER["DOCUMENT_ROOT"]."/merchant/func_cache.php");

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(htmlspecialchars(trim($_GET["id"]))) : false;

if($id != false) {
	require($_SERVER["DOCUMENT_ROOT"]."/config.php");

	$sql_id = mysql_query("SELECT `id`,`url` FROM `tb_ads_pay_row` WHERE `id`='$id' AND `status`='1'");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_assoc($sql_id);
		$id = $row["id"];
		$url_site = $row["url"];

		mysql_query("UPDATE `tb_ads_pay_row` SET `views`=`views`+'1', `outside`=`outside`+'1' WHERE `id`='$id'") or die(mysql_error());;
		cache_pay_row();
		mysql_close();

		echo '<script type="text/javascript">location.replace("'.$url_site.'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$url_site.'"></noscript>';
	}else{
		cache_pay_row();
		mysql_close();

		echo '<script type="text/javascript">location.replace("/");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
	}
}else{
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
}
?>