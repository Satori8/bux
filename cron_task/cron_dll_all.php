<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/merchant/func_mysql.php");
require(ROOT_DIR."/funciones.php");
require("func_cache.php");

### ¿‚ÚÓÁ‡ÔÛÒÍ ‚ÒÂÈ ÂÍÎ‡Ï˚ ###

$time_d=time()-24*60*60*14;
mysql_query("UPDATE `tb_ads_dlink` SET `status`='1', `data_pauza`='0' WHERE `status`='2' && `data_pauza`<'".$time_d."' order by date desc");

$time_y=time()-24*60*60*14;
mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `data_pauza`='0' WHERE `status`='2' && `data_pauza`<'".$time_y."' order by date desc");
/*
$time_m=time()-24*60*60*14;
$mysqli->query("UPDATE `tb_ads_mails` SET `status`='1', `data_pauza`='0' WHERE `status`='2' && `data_pauza`<'".$time_m."' order by date desc");

$time_k=time()-24*60*60*14;
$mysqli->query("UPDATE `tb_ads_kontext` SET `status`='1', `data_pauza`='0' WHERE `status`='2' && `data_pauza`<'".$time_k."' order by date desc");

$time_t=time()-24*60*60*14;
$mysqli->query("UPDATE `tb_ads_tests` SET `status`='1', `data_pauza`='0' WHERE `status`='2' && `data_pauza`<'".$time_t."' order by date desc");

$time_a=time()-24*60*60*14;
$mysqli->query("UPDATE `tb_ads_auto` SET `status`='1', `data_pauza`='0' WHERE `status`='2' && `data_pauza`<'".$time_a."' order by date desc");
*/
### ¿‚ÚÓÁ‡ÔÛÒÍ ‚ÒÂÈ ÂÍÎ‡Ï˚ ###

mysql_query("UPDATE `tb_ads_dlink` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND ( (`totals`<'1' AND `nolimit`='0') OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_dlink` WHERE `status`='3' AND `method_pay`='-2'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_dlink` WHERE 
	( `status`='3' AND `date`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_dlink_visits` WHERE `ident` NOT IN (SELECT `id` FROM `tb_ads_dlink`)") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_dlink_visits` WHERE `time_next`<'".(time()-0*24*60*60)."'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_youtube` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND ( (`totals`<'1' AND `nolimit`='0') OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_youtube` WHERE `status`='3' AND `method_pay`='-2'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_youtube` WHERE 
	( `status`='3' AND `date`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_youtube_visits` WHERE `ident` NOT IN (SELECT `id` FROM `tb_ads_youtube`)") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_youtube_visits` WHERE `time_next`<'".(time()-0*24*60*60)."'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'3' AND `balance`<`cena_advs`") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_tests` WHERE 
	( `status`='3' AND `date_edit`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_tests_visits` WHERE `ident` NOT IN (SELECT `id` FROM `tb_ads_tests`)") or die(mysql_error());

mysql_query("UPDATE `tb_ads_auto` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_auto` WHERE 
	( `status`='3' AND `date`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_auto_visits` WHERE `ident` NOT IN (SELECT `id` FROM `tb_ads_auto`)") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_auto_visits` WHERE `date`<'".(time()-24*60*60)."'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_mails` WHERE 
	( `status`='3' AND `date`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_mails_visits` WHERE `ident` NOT IN (SELECT `id` FROM `tb_ads_mails`)") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_mails_visits` WHERE `time`<'".(time()-24*60*60)."'") or die(mysql_error());

mysql_query("UPDATE `tb_ads_banner` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_banner` WHERE 
	( `status`='3' AND `date_end`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("UPDATE `tb_ads_kat` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_kat` WHERE 
	( `status`='3' AND `date_end`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("UPDATE `tb_ads_slink` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_slink` WHERE 
	( `status`='3' AND `date_end`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("DELETE FROM `tb_ads_pay_row` WHERE 
	( `status`='3' AND `date`<'".(time()-15*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("DELETE FROM `tb_ads_quick_mess` WHERE 
	( `status`='3' AND `date`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("UPDATE `tb_ads_psevdo` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_psevdo` WHERE 
	( `status`='3' AND `date_end`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("UPDATE `tb_ads_kontext` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_kontext` WHERE 
	( `status`='3' AND `date`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

mysql_query("UPDATE `tb_ads_beg_stroka` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_beg_stroka` WHERE 
	( `status`='3' AND `date_end`<'".(time()-10*24*60*60)."' ) OR 
	( `status`>'1' AND `username`='' ) OR 
	( `status`>'1' AND `username`!='' AND `username` NOT IN (SELECT `username` FROM `tb_users`) ) 
") or die(mysql_error());

cache_stat_links();
cache_kontext();
cache_frm_links();
cache_txt_links();
cache_rek_cep();
cache_banners();
cache_beg_stroka();
cache_pay_row();
cache_quick_mess();
cache_stat_kat();

$sql_w = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' AND `type`='task' AND `date_end`<'".(time()-5*24*60*60)."' ORDER BY `id` ASC");
if(mysql_num_rows($sql_w) > 0) {

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
					$reit_task = mysql_result($sql,0,0);

	while($row_w = mysql_fetch_array($sql_w)) {

		$id = $row_w["id"];
		$ident = $row_w["ident"];
		$user_name = $row_w["user_name"];

		$sql_t = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$ident' AND `wait`>'0'");
		if(mysql_num_rows($sql_t) > 0) {

			$row_t = mysql_fetch_array($sql_t);
			$id_t = $row_t["id"];
			$zdprice = $row_t["zdprice"];
			$rek_name = $row_t["username"];
			$rek_wmid = $row_t["wmid"];
			$zdtype = $row_t["zdtype"];
			$country_targ = $row_t["country_targ"];
			$zdre = $row_t["zdre"];
			
			mysql_query("UPDATE `tb_ads_task` SET `goods`=`goods`+'1', `wait`=`wait`-'1' WHERE `id`='$id_t'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task', `visits_t`=`visits_t`+1, `money`=`money`+'$zdprice', `money_t`=`money_t`+'$zdprice' WHERE `username`='$user_name'") or die(mysql_error());
			mysql_query("UPDATE `tb_ads_task_pay` SET `status`='good', `pay`='$zdprice' WHERE `id`='$id'") or die(mysql_error());
			
			$sql_r = mysql_query("SELECT `referer`,`referer2`,`referer3`,`referer4`,`referer5`,`ban_date` FROM `tb_users` WHERE `username`='$user_name'");
					$row_r = mysql_fetch_assoc($sql_r);

					$my_referer_1 = $row_r["referer"];
					$my_referer_2 = $row_r["referer2"];
					$my_referer_3 = $row_r["referer3"];
					$my_referer_4 = $row_r["referer4"];
					$my_referer_5 = $row_r["referer5"];
					$user_ban_date = $row_r["ban_date"];

					$add_money_r_1 = 0;
					$add_money_r_2 = 0;
					$add_money_r_3 = 0;
					$add_money_r_4 = 0;
					$add_money_r_5 = 0;

			### ”¬≈ƒŒÃÀ≈Õ»≈ »—œŒÀÕ»“≈Àﬁ ####
			mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
			VALUES('0', 'good_auto', '$id_t', '$rek_name', '$user_name', '".time()."', '')") or die(mysql_error());
			### ”¬≈ƒŒÃÀ≈Õ»≈ »—œŒÀÕ»“≈Àﬁ ####
			
			### START —¡Œ– —“¿“»—“» » ####
			$DAY_S = strtolower(DATE("D"));
			stats_users($user_name, $DAY_S, 'task', $zdprice);
			### END —¡Œ– —“¿“»—“» » ######

			####  ŒÕ ”–— –≈ À¿ÃŒƒ¿“≈À≈… ###
			konkurs_ads_new($rek_wmid, $rek_name, $zdprice);
			####  ŒÕ ”–— –≈ À¿ÃŒƒ¿“≈À≈… ###
			
			####  ŒÕ ”–— œŒ ŒœÀ¿“≈ «¿ƒ¿Õ»… ###
					konkurs_ads_task_new($rek_wmid, $rek_name, $zdprice);
					####  ŒÕ ”–— œŒ ŒœÀ¿“≈ «¿ƒ¿Õ»… ###
					
					#### NEW  ŒÕ ”–— œŒ ¬€œŒÀÕ≈Õ»ﬁ «¿ƒ¿Õ»… NEW ###
					$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
					$konk_task_status = mysql_result($sql,0,0);

					if($konk_task_status==1) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
						$konk_task_date_start = mysql_result($sql,0,0);

						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
						$konk_task_date_end = mysql_result($sql,0,0);

						if($konk_task_date_end>=time() && $konk_task_date_start<time()) {
							mysql_query("UPDATE `tb_users` SET `konkurs_task`=`konkurs_task`+'1' WHERE `username`='$user_name' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)")or die(mysql_error());
						}
					}
					#### NEW  ŒÕ ”–— œŒ ¬€œŒÀÕ≈Õ»ﬁ «¿ƒ¿Õ»… NEW ###
					

			if($my_referer_1!=false) {
					$sql_r_1 = mysql_query("SELECT `id`,`money_rb`,`reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
					$row_r_1 = mysql_fetch_array($sql_r_1);
					$user_id_ref_1 = $row_r_1["id"];
					$money_rb_ref_1 = $row_r_1["money_rb"];
					$reiting_1 = $row_r_1["reiting"];

						$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_1' AND `r_do`>='".floor($reiting_1)."'");
						if(mysql_num_rows($sql_rang)>0) {
							$row_rang = mysql_fetch_array($sql_rang);
						}else{
							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
							$row_rang = mysql_fetch_array($sql_rang);
						}
						$ref_proc_task_1 = $row_rang["t_1"];

						$add_money_r_1 = p_floor( ($zdprice * $ref_proc_task_1 / 100), 6);

						mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$user_name'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");
							konkurs_clic_ref($my_referer_1, $add_money_r_1);
					 konkurs_best_ref($user_name, $add_money_r_1);
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

						### –≈‘  ŒÕ ”–— I Û. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
								}
							}
						}
						### –≈‘  ŒÕ ”–— I Û. ###

						### –≈‘  ŒÕ ”–— I Û. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
								}
							}
						}
						### –≈‘  ŒÕ ”–— I Û. ###
						
						### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” I Û. ###
					if($country_targ=="0") {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='6' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							//if($row_t["limit_kon_sum"]!=0 && ($my_visits+$my_visits_bs+$add_money_r_1)==$row_t["limit_kon_sum"]) {
							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
				//}
					### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” I Û. ###

						if($my_referer_2!="") {
							$sql_r_2 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
							$row_r_2 = mysql_fetch_array($sql_r_2);
							$reiting_2 = $row_r_2["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_2' AND `r_do`>='".floor($reiting_2)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_array($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_array($sql_rang);
							}
							$ref_proc_task_2 = $row_rang["t_2"];

							$add_money_r_2 = p_floor( ($zdprice * $ref_proc_task_2 / 100), 6);

							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_2' AND `type`='ref2'");
							konkurs_clic_ref($my_referer_2, $add_money_r_2);
					 konkurs_best_ref($user_name, $add_money_r_2);
	                       if(mysql_num_rows($sql_stat_re)>0) {
	                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `type`='ref2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }else{
					           mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_2','ref2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				         }

							### –≈‘  ŒÕ ”–— II Û. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
									}
								}
							}
							### –≈‘  ŒÕ ”–— II Û. ###

							### –≈‘  ŒÕ ”–— II Û. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
									}
								}
							}
							### –≈‘  ŒÕ ”–— II Û. ###
							
							### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” II Û. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_2)==$row_t["limit_kon_sum"]) {
								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_2' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
					//}
						### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” II Û. ###

							if($my_referer_3!="") {
								$sql_r_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
								$row_r_3 = mysql_fetch_array($sql_r_3);
								$reiting_3 = $row_r_3["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_3' AND `r_do`>='$reiting_3'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$ref_proc_task_3 = $row_rang["t_3"];

								$add_money_r_3 = p_floor( ($zdprice * $ref_proc_task_3 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());
								$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_3' AND `type`='ref3'");
								konkurs_clic_ref($my_referer_3, $add_money_r_3);
					 konkurs_best_ref($user_name, $add_money_r_3);
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `type`='ref3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_3','ref3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }

								### –≈‘  ŒÕ ”–— III Û. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### –≈‘  ŒÕ ”–— III Û. ###

								### –≈‘  ŒÕ ”–— III Û. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### –≈‘  ŒÕ ”–— III Û. ###
								### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” III Û. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_3' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
							//}
							
							if($my_referer_4!="") {
								$sql_r_4 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_3'");
								$row_r_4 = mysql_fetch_array($sql_r_4);
								$reiting_4 = $row_r_4["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_4' AND `r_do`>='$reiting_4'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$ref_proc_task_4 = $row_rang["t_4"];

								$add_money_r_4 = p_floor( ($zdprice * $ref_proc_task_4 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `refmoney4`=`refmoney4`+'$add_money_r_4', `money`=`money`+'$add_money_r_4' WHERE `username`='$my_referer_4'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_4' WHERE `username`='$my_referer_3'") or die(mysql_error());
								$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_4' AND `type`='ref4'");
								konkurs_clic_ref($my_referer_4, $add_money_r_4);
					 konkurs_best_ref($user_name, $add_money_r_4);
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_4', `sum_week`=`sum_week`+'$add_money_r_4', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_4' WHERE `username`='$my_referer_4' AND `type`='ref4'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_4','ref4','$add_money_r_4','$add_money_r_4','$add_money_r_4')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }

								### –≈‘  ŒÕ ”–— IV Û. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_4' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='4' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='4' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','4','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### –≈‘  ŒÕ ”–— IV Û. ###

								### –≈‘  ŒÕ ”–— IV Û. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_4' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='4' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='4' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer1`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','4','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### –≈‘  ŒÕ ”–— IV Û. ###
								
								### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” III Û. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_4' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='4' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_4' WHERE `username`='$user_name' AND `referer`='4' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','4','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_4')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
							//}
							
							if($my_referer_5!="") {
								$sql_r_5 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_4'");
								$row_r_5 = mysql_fetch_array($sql_r_5);
								$reiting_5 = $row_r_5["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_5' AND `r_do`>='$reiting_5'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$ref_proc_task_5 = $row_rang["t_5"];

								$add_money_r_5 = p_floor( ($zdprice * $ref_proc_task_5 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `refmoney5`=`refmoney5`+'$add_money_r_5', `money`=`money`+'$add_money_r_5' WHERE `username`='$my_referer_5'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_5' WHERE `username`='$my_referer_4'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_5' AND `type`='ref5'");
							konkurs_clic_ref($my_referer_5, $add_money_r_5);
					 konkurs_best_ref($user_name, $add_money_r_5);
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_5', `sum_week`=`sum_week`+'$add_money_r_5', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_5' WHERE `username`='$my_referer_5' AND `type`='ref5'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_5','ref5','$add_money_r_5','$add_money_r_5','$add_money_r_5')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }

								### –≈‘  ŒÕ ”–— V Û. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_5' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='5' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='5' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer1`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','5','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### –≈‘  ŒÕ ”–— V Û. ###

								### –≈‘  ŒÕ ”–— V Û. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_5' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='5' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='5' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','5','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### –≈‘  ŒÕ ”–— V Û. ###
								
								### –≈‘- ŒÕ ”–— «¿–¿¡Œ“ ¿ –≈‘≈–≈–” III Û. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_5' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='5' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_5' WHERE `username`='$user_name' AND `referer`='5' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','5','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_5')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
							}
						}
					}
				}
			}
								
								### –≈‘¡ŒÕ”—€ «¿ «¿ƒ¿Õ»ﬂ NEW ###
					if($my_referer_1 != false) {
						$sql_r_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
						if(mysql_num_rows($sql_r_1)>0) {
							$row_r_1 = mysql_fetch_assoc($sql_r_1);
							$user_id_ref_1 = $row_r_1["id"];
							$money_ref_1 = $row_r_1["money_rb"];

							$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='4' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_r_b_1)>0) {
								$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

								$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(mysql_error());
								$comis_sys_bon = mysql_result($sql_b,0,0);

								$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='4' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_r_b_stat_1)>0) {
									$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

									if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
										$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
										$money_ureferera_nado = round($money_ureferera_nado, 2);

										if($money_ref_1>=$money_ureferera_nado) {
											mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

											mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(mysql_error());
											mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$user_name','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','–ÂÙ-¡ÓÌÛÒ ÓÚ ÂÙÂÂ‡ $my_referer_1 Á‡ ".$row_r_b_1["count_nado"]." ‚˚ÔÓÎÌÂÌÌ˚ı Á‡‰‡ÌËÈ','«‡˜ËÒÎÂÌÓ','rashod')") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$my_referer_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','–ÂÙ-¡ÓÌÛÒ ÂÙÂ‡ÎÛ $user_name Á‡ ".$row_r_b_1["count_nado"]." ‚˚ÔÓÎÌÂÌÌ˚ı Á‡‰‡ÌËÈ','—ÔËÒ‡ÌÓ','rashod')") or die(mysql_error());

											if(trim($row_r_b_1["description"])!=false) {
												mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
												VALUES('$user_name','—ËÒÚÂÏ‡','–ÂÙ-¡ÓÌÛÒ ÓÚ ÂÙÂÂ‡ $my_referer_1 Á‡ ".$row_r_b_1["count_nado"]." ÍÎËÍÓ‚ ‚ ÒÂÙËÌ„Â','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
											}
										}else{
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
										}
									}else{
										mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
									}
								}else{
									mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
									VALUES('-1','$user_name','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(mysql_error());
								}
							}
						//}
					//}
					### –≈‘¡ŒÕ”—€ «¿ «¿ƒ¿Õ»ﬂ NEW ###
					
					### –≈‘-¡ŒÕ”—€ «¿ «¿–¿¡Œ“Œ  –≈‘≈–≈–” NEW ###
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='6' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='6' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if((number_format($row_r_b_stat_1["stat_info"],2,".","`"))==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','–ÂÙ-¡ÓÌÛÒ ÓÚ ÂÙÂÂ‡ $my_referer_1 Á‡ Á‡‡·ÓÚÓÍ ".number_format($row_r_b_1["count_nado"],2,".","`")." Û·.','«‡˜ËÒÎÂÌÓ','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','–ÂÙ-¡ÓÌÛÒ ÂÙÂ‡ÎÛ $user_name Á‡ Á‡‡·ÓÚÓÍ ".number_format($row_r_b_1["count_nado"],2,".","`")." Û·.','—ÔËÒ‡ÌÓ','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$user_name','—ËÒÚÂÏ‡','–ÂÙ-¡ÓÌÛÒ ÓÚ ÂÙÂÂ‡ $my_referer_1 Á‡ Á‡‡·ÓÚÓÍ ".number_format($row_r_b_1["count_nado"],2,".","`")." Û·.','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$user_name','$add_money_r_1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
					### –≈‘-¡ŒÕ”—€ «¿ «¿–¿¡Œ“Œ  –≈‘≈–≈–” NEW ###
				}
			}

			
		}
	}
}

mysql_query("DELETE FROM `tb_ads_task_stat` WHERE `date`!='".DATE("d.m.Y", time())."' AND `date`!='".DATE("d.m.Y", time()-24*3600)."'") or die(mysql_error());
mysql_query("UPDATE `tb_ads_task` SET `status`='pause' WHERE `status` NOT IN ('wait','pause','block') AND `totals`<='0'") or die(mysql_error());


mysql_close();
?>