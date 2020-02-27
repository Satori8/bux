<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

### START СБОР СТАТИСТИКИ ####
mysql_query("UPDATE `tb_users_stat` SET `".strtolower(DATE("D"))."`='0'") or die(mysql_error());
mysql_query("UPDATE `tb_ads_stat` SET `".strtolower(DATE("D"))."`='0'") or die(mysql_error());
mysql_query("UPDATE `tb_ref_stat` SET `".strtolower(DATE("D"))."`='0'") or die(mysql_error());
mysql_query("UPDATE `tb_pay_stat` SET `".strtolower(DATE("D"))."`='0'") or die(mysql_error());

mysql_query("UPDATE `tb_invest_users` SET `dohod_v`=`dohod_s`");
mysql_query("UPDATE `tb_invest_users` SET `dohod_s`='0'");
### END СБОР СТАТИСТИКИ ######

mysql_query("UPDATE `tb_users` SET `vac1`='0' WHERE `vac1`<'".time()."'");
mysql_query("UPDATE `tb_users` SET `vac2`='0' WHERE `vac2`<'".time()."'");
mysql_query("UPDATE `tb_users` SET `bonus_surf`='0' WHERE `bonus_surf`!='0'");
mysql_query("DELETE FROM `tb_users` WHERE `joindate2`<'".(time()-1*24*60*60)."' AND `lastlogdate2`='0'") or die(mysql_error());
/*
### КОЛ-ВО РЕКЛАМОДАТЕЛЕЙ ###
$count_advs_1 = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users` WHERE `money_rek`>'0'"));
$count_advs_2 = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_wmid` WHERE `wmid` NOT IN (SELECT `wmid` FROM `tb_users`)"));
mysql_query("UPDATE `tb_statistics` SET `count_advs`='".($count_advs_1+$count_advs_2)."'") or die(mysql_error());
### КОЛ-ВО РЕКЛАМОДАТЕЛЕЙ ###
*/
mysql_query("UPDATE `tb_users` SET `dohod_r_v`=`dohod_r_s`, `dohod_r_s`='0'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_youtube` SET `limit_d_now`=`limit_d` WHERE `status`>'0'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_dlink` SET `limit_d_now`=`limit_d` WHERE `status`>'0'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_auto` SET `limits_now`=`limits` WHERE `status`>'0'") or die(mysql_error());

mysql_query("UPDATE `tb_statistics` SET `users_v`=`users_s`") or die(mysql_error());
mysql_query("UPDATE `tb_statistics` SET `users_s`='0'") or die(mysql_error());


$sql = mysql_query("SELECT * FROM `tb_board` WHERE `status`='0' ORDER BY `count` DESC, `time` DESC LIMIT 1");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_assoc($sql);

	$sql_all_s = mysql_query("SELECT sum(count) FROM `tb_board` WHERE `status`='0' ORDER BY `count` DESC, `time` DESC");
	$all_count = mysql_result($sql_all_s,0,0);

	$sql_c = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_comis'");
	$cena_board_comis = mysql_result($sql_c,0,0);

	$pay_to_user = $all_count * ($cena_board_comis/100);
	$pay_to_user = round($pay_to_user,2);

	mysql_query("UPDATE `tb_board` SET `status`='1', `money`='$pay_to_user' WHERE `id`='".$row["id"]."'");
	mysql_query("UPDATE `tb_board` SET `status`='2' WHERE `status`='0'");

	mysql_query("UPDATE `tb_users` SET `money`=`money`+'$pay_to_user' WHERE `username`='".$row["user_name"]."'");
	mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
	VALUES('".$row["user_name"]."','".DATE("d.m.Y H:i",time())."','".time()."','$pay_to_user','Приз за ежедневный конкурс на самого Почетного участника системы','Зачислено','prihod','3')") or die(mysql_error());
}


@mysql_close();
?>