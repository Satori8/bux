<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../'));
require(ROOT_DIR."/config_mysqli.php");

$mysqli->query("DELETE FROM `tb_chat_mess` WHERE `status`='0' AND `time`<'".(time() - 3*24*60*60)."'") or die($mysqli->error);
$mysqli->query("DELETE FROM `tb_chat_mess` WHERE `status`='2' AND `time`<'".(time() - 3*24*60*60)."'") or die($mysqli->error);

$sql = $mysqli->query("SELECT `id`,`user_name` FROM `tb_chat_users` WHERE `user_status`='-1' AND `ban_time_end`<'".time()."'") or die($mysqli->error);
if($sql->num_rows > 0) {
	while ($row = $sql->fetch_assoc()) {
		$id = $row["id"];
		$user_name = $row["user_name"];

		$mysqli->query("UPDATE `tb_chat_users` SET `user_status`='0', `banned_user`='', `ban_info`='', `ban_period`='0', `ban_time`='0', `ban_time_end`='0' WHERE `id`='$id'") or die($mysqli->error);
		$mysqli->query("UPDATE `tb_chat_mess` SET `user_status`='0' WHERE `user_name`='$user_name'") or die($mysqli->error);
		$mysqli->query("UPDATE `tb_chat_online` SET `user_status`='0' WHERE `user_name`='$user_name'") or die($mysqli->error);
	}
}

$sql->free();
$mysqli->close();
?>