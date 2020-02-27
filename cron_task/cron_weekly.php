<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

### START яанп ярюрхярхйх ####
mysql_query("UPDATE `tb_ads_stat` SET `sum_week`='0'") or die(mysql_error());
mysql_query("UPDATE `tb_ref_stat` SET `sum_week`='0'") or die(mysql_error());
### END яанп ярюрхярхйх ######

@mysql_close();
?>