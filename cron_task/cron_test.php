<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

mysql_query("INSERT INTO `tb_test` (`text`) 
VALUES('".DATE("d.m.Y H:i:s")." | ".time()."')") or die(mysql_error());

@mysql_close();
?>