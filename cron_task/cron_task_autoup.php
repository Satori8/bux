<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../'));
require(ROOT_DIR."/config_mysqli.php");

$sql_aup = $mysqli->query("SELECT `id`,`ident`,`int_autoup` FROM `tb_ads_task_up` WHERE `cnt_autoup`>'0' AND `time_next`<='".time()."' AND `ident` IN (SELECT `id` FROM `tb_ads_task` WHERE `status`='pay' AND `totals`>'0') ORDER BY `time_change` ASC") or die($mysqli->error);
if($sql_aup->num_rows > 0) {
	while ($row_up = $sql_aup->fetch_assoc()) {
		$id = $row_up["id"];
		$ident = $row_up["ident"];
		$int_autoup = $row_up["int_autoup"];
		$time_now = strtotime(DATE("d.m.Y H:i", time()));

		$mysqli->query("UPDATE `tb_ads_task` SET `date_up`='".time()."' WHERE `id`='$ident'") or die($mysqli->error);
		$mysqli->query("UPDATE `tb_ads_task_up` SET `cnt_autoup`=`cnt_autoup`-'1', `time_last`='$time_now', `time_next`='".($time_now+$int_autoup)."' WHERE `id`='$id'") or die($mysqli->error);
	}
	$sql_aup->free();
}else{
	$sql_aup->free();
}

$mysqli->close();
?>