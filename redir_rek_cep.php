<?php
$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(htmlspecialchars(trim($_GET["id"]))) : false;
if($id!=false) {
	require_once('config.php');
	require_once("".$_SERVER['DOCUMENT_ROOT']."/merchant/func_cache.php");

	$sql_id = mysql_query("SELECT `url` FROM `tb_ads_rc` WHERE `id`='$id' AND `status`='1'");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_row($sql_id);
		$url_site = $row["0"];

		mysql_query("UPDATE `tb_ads_rc` SET `view`=`view`+'1' WHERE `id`='$id'");
		cache_rek_cep();
		mysql_close();

		echo '<script type="text/javascript">location.replace("'.$url_site.'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$url_site.'"></noscript>';
	}else{
		echo '<script type="text/javascript">location.replace("/");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
	}
}else{
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/"></noscript>';
}
?>