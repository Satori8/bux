<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

$sql_p = mysql_query("SELECT `sitewmid` FROM `tb_site` WHERE `id`='1'");
$site_wmid = mysql_result($sql_p,0,0);

### START ЕЖЕДНЕВНЫЙ КОНКРУРС ПОСЕТИТЕЛЕЙ ###
$konk_hit_ed_status = mysql_result(mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='konk_hit_ed_status' AND `howmany`='1'"),0,0);
if($konk_hit_ed_status==1) {
	$konk_hit_ed_count_prize = mysql_result(mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='konk_hit_ed_count_prize' AND `howmany`='1'"),0,0);

	for($i=1; $i<=$konk_hit_ed_count_prize; $i++) {
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='konk_hit_ed_prize' AND `howmany`='$i'");
		$konk_hit_ed_prize[$i] = mysql_result($sql,0,0);
	}

	$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_ed_hit` ORDER BY `ident` DESC LIMIT 1");
	if(mysql_num_rows($sql)>0) {
		$k_nomer = intval(mysql_result($sql,0,0)) + 1;
	}else{
		$k_nomer = 1;
	}

	$mesto=0;
	$sql = mysql_query("SELECT `username`,`konkurs_hit` FROM `tb_users` WHERE `wmid`!='$site_wmid' ORDER BY `konkurs_hit` DESC limit $konk_hit_ed_count_prize");
	while($row = mysql_fetch_array($sql)) {
		$mesto++;
		if($row["konkurs_hit"]>0) {
			$prize = ($konk_hit_ed_prize[$mesto] * $row["konkurs_hit"]);
		}else{
			$prize = 0;
		}

		mysql_query("INSERT INTO `tb_konkurs_ed_hit` (`ident`,`date`,`username`,`count_prize`,`kolvo`,`summa`,`mesto`,`statuspay`) 
		VALUES('$k_nomer','".(time()-10)."','".$row["username"]."','$konk_hit_ed_count_prize','".$row["konkurs_hit"]."','$prize','$mesto','1')") or die(mysql_error());

		mysql_query("UPDATE `tb_users` SET `konkurs_hit`='0'") or die(mysql_error());

		if($prize>0) {
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$prize' WHERE `username`='".$row["username"]."'") or die(mysql_error());

			mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) 
			VALUES('".$row["username"]."','".DATE("d.m.Yг. в H:i")."','$prize','Приз за $mesto место в ежедневном конкурсе посетителей №$k_nomer','Зачислено на рекл. счет','prihod')") or die(mysql_error());
		}
	}

}
### END ЕЖЕДНЕВНЫЙ КОНКРУРС ПОСЕТИТЕЛЕЙ ###

?>