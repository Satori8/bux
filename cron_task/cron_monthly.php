<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

### START ���� ���������� ####
mysql_query("UPDATE `tb_users_stat` SET `month`='0'") or die(mysql_error());
mysql_query("UPDATE `tb_ads_stat` SET `sum_month`='0'") or die(mysql_error());
mysql_query("UPDATE `tb_ref_stat` SET `sum_month`='0'") or die(mysql_error());
### END ���� ���������� ######

@mysql_close();
?>