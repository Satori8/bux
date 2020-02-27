<?php

$bid = (isset($_GET["bid"])) ? intval($_GET["bid"]) : false;
$sql_f = mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$partnerid' AND `rek_id`='$bid'");
if (mysql_num_rows($sql_f) > 0) {
	mysql_query("DELETE FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$partnerid' AND `rek_id`='$bid'") or die(mysql_error());
}

?>
