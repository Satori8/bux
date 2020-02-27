<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

$users_24h = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users` WHERE `joindate2`>='".(time()-24*60*60)."'"));

mysql_query("UPDATE `tb_statistics` SET `users_24h`='$users_24h'") or die(mysql_error());

mysql_close();

?>
