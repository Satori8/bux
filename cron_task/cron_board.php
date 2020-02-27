<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

mysql_query("UPDATE `tb_board` SET `time_end`='".(time()+5*60)."', `count_now`=`count_now`-'1' WHERE `lider`='1' AND `time_end`<'".time()."' AND `count_now`>'0' LIMIT 1 ") or die(mysql_error());


$board_sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='board_bonus_status' AND `howmany`='1'");
	$board_bonus_status = mysql_result($board_sql,0,0);
	$bonus_sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='board_bonus' AND `howmany`='1'");
	$bonus=mysql_result($bonus_sql,0,0);
	$time_on= DATE("d.m.Y", strtotime(DATE("d.m.Y"))-2592000);
	$time_ds= DATE("d.m.Y", strtotime(DATE("d.m.Y")));
	$time_in=DATE("d", strtotime(DATE("d.m.Y")));
	//echo $time_in;
if($board_bonus_status==0 && $time_in==1){

	$time_d= strtotime(DATE("d.m.Y"))-2592000;
		$sqla = mysql_query("SELECT * FROM `tb_users` WHERE kon_board_m>0 ORDER BY `kon_board_m` DESC, `data_board` DESC LIMIT 1");
			$rowa = mysql_fetch_assoc($sqla);
			
			$sql = mysql_query("SELECT * FROM `tb_board` WHERE user_name='".$rowa['username']."' ORDER BY `id` DESC LIMIT 1");
				$row = mysql_fetch_assoc($sql); 
	
	
	
	mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$bonus."' WHERE `username`='".$row['user_name']."'") or die(mysql_error());
	mysql_query("UPDATE `tb_board` SET `status_pr`='".$bonus."', `date_pr`='".$time_ds."' WHERE `user_name`='".$row['user_name']."' && `id`='".$row['id']."'") or die(mysql_error());
	
mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["user_name"]."','".$row["user_id"]."','".DATE("d.m.Y H:i")."','".time()."','$bonus','За победу в конкурсе накопительного бонуса на доске почета.','Зачислено','prihod','3')") or die(mysql_error());
					
mysql_query("UPDATE `tb_users` SET `kon_board_m`='0', `data_board`='0'") or die(mysql_error());					
mysql_query("UPDATE `tb_config` SET `price`='0' WHERE `item`='board_bonus'") or die(mysql_error());
mysql_query("UPDATE `tb_config` SET `price`='1' WHERE `item`='board_bonus_status' && `price`='0'") or die(mysql_error());
}

if($time_in>1){
mysql_query("UPDATE `tb_config` SET `price`='0' WHERE `item`='board_bonus_status' && `price`='1'") or die(mysql_error());
}

### START ОЧИСТКА ВП САЙТА ####
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mail_vp_config'");
$mail_vp_config = round(number_format(mysql_result($sql,0,0), 2, ".", ""),2);
$time_mail=time()-($mail_vp_config*(24*60*60));
mysql_query("DELETE FROM `tb_mail_out` WHERE `date`<='".$time_mail."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_mail_in` WHERE `date`<='".$time_mail."'") or die(mysql_error());
### END ОЧИСТКА ВП САЙТА ######

@mysql_close();
?>