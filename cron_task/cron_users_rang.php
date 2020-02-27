<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

$sql_rang = mysql_query("SELECT `id`,`r_ot`,`r_do` FROM `tb_config_rang` ORDER BY `id` ASC") or die(mysql_error());
if(mysql_num_rows($sql_rang)>0) {
	while ($row_rang = mysql_fetch_assoc($sql_rang)){
		$sql_users = mysql_query("SELECT `id` FROM `tb_users` WHERE `reiting`>='".$row_rang["r_ot"]."' AND `reiting`<='".$row_rang["r_do"]."'");
		$cnt_users = mysql_num_rows($sql_users);

		mysql_query("UPDATE `tb_config_rang` SET `cnt_users`='$cnt_users' WHERE `id`='".$row_rang["id"]."'") or die(mysql_error());
	}
}

@mysql_close();
?>