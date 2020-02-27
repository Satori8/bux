<?php

function ProcRefRB($username, $merch_amount) {
	if($username != false) {
		$sql_user = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$username'") or die(mysql_error());
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$my_referer_1 = $row_user["referer"];

			if($my_referer_1 != false) {
				$sql_ref_1 = mysql_query("SELECT `id`,`reiting` FROM `tb_users` WHERE `username`='$my_referer_1'") or die(mysql_error());
				if(mysql_num_rows($sql_ref_1)>0) {
					$row_ref_1 = mysql_fetch_assoc($sql_ref_1);
					$id_ref_1 = $row_ref_1["id"];
					$reit_ref_1 = $row_ref_1["reiting"];

					$sql_rang_1 = mysql_query("SELECT `id`,`balance_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='".floor($reit_ref_1)."' AND `balance_1`>'0'") or die(mysql_error());
					if(mysql_num_rows($sql_rang_1)>0) {
						$row_rang_1 = mysql_fetch_assoc($sql_rang_1);
						$bal_proc_1 = $row_rang_1["balance_1"];
						$bonus_balance_1 = $merch_amount * ($bal_proc_1/100);

						if($bonus_balance_1 > 0.01) {
							mysql_query("UPDATE `tb_users` SET `money`=`money`+'$bonus_balance_1' WHERE `username`='$my_referer_1'") or die(mysql_error());

							mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
							VALUES('1','0','$my_referer_1','$id_ref_1','".DATE("d.m.Y H:i",time())."','".time()."','$bonus_balance_1','Бонус ($bal_proc_1%) от пополнения рекламного баланса рефералом $username','Зачислено','prihod')") or die(mysql_error());
						}
					}
				}
			}
		}
	}
}

function BonusSurf($username, $money_pay) {
	$sql_s = mysql_query("SELECT `id` FROM `tb_config` WHERE `item`='bonus_surf_status' AND `price`='1'") or die(mysql_error());
	if($username!=false && mysql_num_rows($sql_s)>0) {
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_money' AND `howmany`='1'") or die(mysql_error());
		$bonus_surf_cnt_money = number_format(mysql_result($sql,0,0), 2, ".", "");

		if($money_pay >= $bonus_surf_cnt_money) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_visits' AND `howmany`='1'") or die(mysql_error());
			$bonus_surf_cnt_visits = number_format(mysql_result($sql,0,0), 0, ".", "");

			$bonus_surf = floor($money_pay/$bonus_surf_cnt_money) * $bonus_surf_cnt_visits;

			if($bonus_surf > 0) {
				mysql_query("UPDATE `tb_users` SET `bonus_surf`=`bonus_surf`+'$bonus_surf' WHERE `username`='$username'") or die(mysql_error());
			}
		}
	}
}

function ActionRef($money_pay, $user_name) {
	function CntTxtAR($count, $text1, $text2, $text3) {
		if($count>0) {
			if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
				return "<b>$count</b> $text3";
			}else{
				switch(substr($count, -1, 1)){
					case 1: return "<b>$count</b> $text1"; break;
					case 2: case 3: case 4: return "<b>$count</b> $text2"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b> $text3"; break;
				}
			}
		}
	}

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status' AND `howmany`='1'") or die(mysql_error());
	$site_action_status = number_format(mysql_result($sql,0,0), 0, ".", "");

	if($site_action_status == 1) {
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_paymin' AND `howmany`='1'") or die(mysql_error());
		$site_action_paymin = number_format(mysql_result($sql,0,0), 2, ".", "");

		if($money_pay >= $site_action_paymin && $user_name != false) {
			$sql_user = mysql_query("SELECT `id`,`username`,`referer`,`referer2` FROM `tb_users` WHERE `username`='$user_name' AND `username`!='Admin'") or die(mysql_error());
			if(mysql_num_rows($sql_user)>0) {
				$row_user = mysql_fetch_assoc($sql_user);
				$user_id = $row_user["id"];
				$user_name = $row_user["username"];
				$user_referer_1 = $row_user["referer"];
				$user_referer_2 = $row_user["referer2"];

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_payads' AND `howmany`='1'") or die(mysql_error());
				$site_action_payads = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_date_ref' AND `howmany`='1'") or die(mysql_error());
				$site_action_date_ref = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status_ref' AND `howmany`='1'") or die(mysql_error());
				$site_action_status_ref = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `r_ot` FROM `tb_config_rang` WHERE `id`='$site_action_status_ref'") or die(mysql_error());
				$ref_reit_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_addreit' AND `howmany`='1'");
				$site_action_addreit = number_format(mysql_result($sql,0,0), 0, ".", "");

				//$_CntGetRef = floor($money_pay/$site_action_payads);
				$_CntGetRef = floor(bcdiv($money_pay, $site_action_payads));
				$_AddReit = floor(bcdiv($money_pay, $site_action_paymin)) * $site_action_addreit;

				$sql_ref = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`!='$user_name' AND `referer`='Admin' AND `user_status`='0' AND `not_get_ref_a`='0' AND `ban_date`='0' AND `reiting`>='$ref_reit_ot' AND `lastlogdate2`>='".(time()-$site_action_date_ref*24*60*60)."' LIMIT $_CntGetRef") or die(mysql_error());
				$cnt_ref = mysql_numrows($sql_ref);
				if($cnt_ref > 0) {
					while ($row_ref = mysql_fetch_assoc($sql_ref)) {
						mysql_query("UPDATE `tb_users` SET `statusref`='10', `referer`='$user_name', `referer2`='$user_referer_1', `referer3`='$user_referer_2' WHERE `username`='".$row_ref["username"]."'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `referer2`='$user_name', `referer3`='$user_referer_1' WHERE `referer`='".$row_ref["username"]."'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `referer3`='$user_name' WHERE `referer2`='".$row_ref["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
					VALUES('$user_name','Система','Системное сообщение','Здравствуйте, <b>$user_name</b>.<br>В рамках акции <b>Рефералы всем</b>, Вы получили ".CntTxtAR($_AddReit, "реферала", "реферала", "рефералов")." и ".CntTxtAR($_AddReit, "балл", "балла", "баллов")." рейтинга в качестве приза от администрации проекта, за потраченные на рекламу <b>$money_pay</b> руб.<br>Посмотреть список рефералов Вы можете на странице <a href=\"/referals1.php\" target=\"_blank\">мои рефералы</a>.','0','".time()."','127.0.0.1')") or die(mysql_error());


					$sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$user_name'");
					$referals1 = mysql_num_rows($sql_r1);

					$sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$user_name'");
					$referals2 = mysql_num_rows($sql_r2);

					$sql_r3 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$user_name'");
					$referals3 = mysql_num_rows($sql_r3);

					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$_AddReit', `referals`='$referals1', `referals2`='$referals2', `referals3`='$referals3' WHERE `username`='$user_name'") or die(mysql_error());


					$sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='Admin'");
					$referals1 = mysql_num_rows($sql_r1);

					$sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='Admin'");
					$referals2 = mysql_num_rows($sql_r2);

					$sql_r3 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='Admin'");
					$referals3 = mysql_num_rows($sql_r3);

					mysql_query("UPDATE `tb_users` SET `referals`='$referals1', `referals2`='$referals2', `referals3`='$referals3' WHERE `username`='Admin'") or die(mysql_error());
				}
			}
		}	
	}
}

function PartnerSet($username, $type_ads, $start_cena, $plan, $type_banner=false) {
	$sql_user = mysql_query("SELECT `id`,`username`,`referer` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_assoc($sql_user);
		$username = $row_user["username"];
		$my_referer_1 = $row_user["referer"];

		$sql_p = mysql_query("SELECT * FROM `tb_users_partner` WHERE `username`='$username'");
		if(mysql_num_rows($sql_p)>0) {
			$row_p = mysql_fetch_assoc($sql_p);
			if($type_ads=="p_b") {
				$type_percent = $row_p["$type_ads"."$type_banner"];
				$type_ads_tab = "$type_ads"."$type_banner";
			}elseif($type_ads=="p_packet_") {
				$type_percent = $row_p["$type_ads"."$plan"];
				$type_ads_tab = "$type_ads"."$plan";
			}else{
				$type_percent = $row_p["$type_ads"];
				$type_ads_tab = "$type_ads";
			}

			if($type_ads=="p_packet_") {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'");
			}else{
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
			}
			$partner_max_percent = mysql_result($sql,0,0);

			if($type_ads=="p_packet_") {
				$sql = mysql_query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day_pack'");
			}else{
				$sql = mysql_query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day'");
			}
			if(mysql_num_rows($sql)>0) {
				$row_pd = mysql_fetch_assoc($sql);
				$partner_count_day = $row_pd["howmany"];
				$partner_count_per = $row_pd["price"];
			}else{
				$partner_count_day = 1;
				$partner_count_per = 1;
			}

			if($type_ads=="p_packet_") {
				$add_per = $partner_count_per;
			}else{
				$add_per = floor($plan/$partner_count_day * $partner_count_per);
			}
			$add_percent_user = floor($type_percent + $add_per);
			if($add_percent_user>$partner_max_percent) $add_percent_user = $partner_max_percent;

			$sql_up_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username' AND `".$type_ads_tab."`>'0'");
			if(mysql_num_rows($sql_up_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `".$type_ads_tab."`='$add_percent_user' WHERE `username`='$username'") or die(mysql_error());
			}
		}


		$sql_p = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$my_referer_1'");
		if(mysql_num_rows($sql_p)>0 && $my_referer_1 != false) {
			if($type_ads=="p_b") {
				$type_ads_tab = "$type_ads"."$type_banner";
			}elseif($type_ads=="p_packet_") {
				$type_ads_tab = "$type_ads"."$plan";
			}else{
				$type_ads_tab = "$type_ads";
			}

			if($type_ads=="p_packet_") {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'");
			}else{
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
			}
			$partner_max_percent = mysql_result($sql,0,0);

			$sql_up = mysql_query("SELECT `".$type_ads_tab."`,`discount_partner` FROM `tb_users_partner` WHERE `username`='$my_referer_1' AND `".$type_ads_tab."`>'0'");
			if(mysql_num_rows($sql_up)>0) {
				$row_up = mysql_fetch_assoc($sql_up);
				$discount_referer = $row_up["discount_partner"];
				$ref_type_percent = ($row_up["$type_ads_tab"] * (100 - $discount_referer)/100);

				$money_add_referer = floatval($start_cena / 100 * $ref_type_percent);
				$moneys_poteri = ($start_cena / 100) * ($partner_max_percent * (100 - $discount_referer)/100) - $money_add_referer;

				mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_add_referer' WHERE `username`='$my_referer_1'") or die(mysql_error());
				mysql_query("UPDATE `tb_users_partner` SET `moneys`=`moneys`+'$money_add_referer', `moneys_p`=`moneys_p`+'$moneys_poteri' WHERE `username`='$my_referer_1'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_partner` (`time`, `username`, `referer`, `type`, `money`, `percent`) 
				VALUES('".time()."','$username','$my_referer_1','".$type_ads_tab."','$money_add_referer', '$ref_type_percent')") or die("Error".mysql_error());
			}else{
				$sql_up_2 = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$my_referer_1'");
				if(mysql_num_rows($sql_up_2)>0) {
					$moneys_poteri = floatval($start_cena / 100 * $partner_max_percent);
					mysql_query("UPDATE `tb_users_partner` SET `moneys_p`=`moneys_p`+'$moneys_poteri' WHERE `username`='$my_referer_1'") or die(mysql_error());
				}else{
					$moneys_poteri = 0;
					mysql_query("INSERT INTO `tb_users_partner` (`username`,`moneys_p`) 
					VALUES('$my_referer_1','$moneys_poteri')") or die("Error".mysql_error());
				}
			}
		}
	}
}

/*### NEW KONKURS ЛУЧШИЙ РЕФЕРЕР ###
function konkurs_clic_ref($REFERER, $money){
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='status'");
	$konk_clic_ref_status = mysql_result($sql,0,0);

	if($konk_clic_ref_status==1) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='date_start'");
		$konk_clic_ref_date_start = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='date_end'");
		$konk_clic_ref_date_end = mysql_result($sql,0,0);

		if($konk_clic_ref_date_end>=time() && $konk_clic_ref_date_start<=time()) {
			mysql_query("UPDATE `tb_users` SET `konkurs_clic_ref`=`konkurs_clic_ref`+'$money' WHERE `username`='$REFERER' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ");
		}
	}
}
### NEW KONKURS ЛУЧШИЙ РЕФЕРЕР ###

### NEW KONKURS ЛУЧШИЙ РЕФЕРАЛ ###
function konkurs_best_ref($USERNAME, $money_ref){
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='status'");
	$konk_best_ref_status = mysql_result($sql,0,0);

	if($konk_best_ref_status==1) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_start'");
		$konk_best_ref_date_start = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_end'");
		$konk_best_ref_date_end = mysql_result($sql,0,0);

		if($konk_best_ref_date_end>=time() && $konk_best_ref_date_start<=time()) {
			mysql_query("UPDATE `tb_users` SET `konkurs_best_ref`=`konkurs_best_ref`+'$money_ref' WHERE `username`='$USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ");
		}
	}
}
### NEW KONKURS ЛУЧШИЙ РЕФЕРАЛ ###
/*
function invest_stat($summa, $type=false) {
	$sql_inv = mysql_query("SELECT * FROM `tb_invest_users` WHERE `count_shares`>'0'");
	if(mysql_num_rows($sql_inv)>0) {

		$sql_inv_site = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'");
		$site_count_shares = mysql_result($sql_inv_site,0,0);

		if($type != false) {
			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='$type'");
			$proc_dividend = number_format(mysql_result($sql,0,0), 0, ".", "");
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='4'");
			$proc_dividend = number_format(mysql_result($sql,0,0), 0, ".", "");
		}

		while ($row_inv = mysql_fetch_assoc($sql_inv)) {
			$user_inv = $row_inv["username"];
			$user_count_shares = $row_inv["count_shares"];

			$money_add_stat = ($summa * ($proc_dividend/100) * $user_count_shares / $site_count_shares);
			$money_add_stat = number_format($money_add_stat, 6, ".", "");

			mysql_query("UPDATE `tb_invest_users` SET `dohod_s`=`dohod_s`+'$money_add_stat', `dohod_all`=`dohod_all`+'$money_add_stat' WHERE `username`='$user_inv'");
			mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'$money_add_stat' WHERE `item`='pay_all_dividend'");

			mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_add_stat' WHERE `username`='$user_inv'");

			$sql_inv_stat = mysql_query("SELECT * FROM `tb_invest_stat` WHERE `username`='$user_inv' AND `date`='".DATE("d.m.Y")."'");
			if(mysql_num_rows($sql_inv_stat)>0) {
				mysql_query("UPDATE `tb_invest_stat` SET `money`=`money`+'$money_add_stat' WHERE `username`='$user_inv' AND `date`='".DATE("d.m.Y")."'");
			}else{
				mysql_query("INSERT INTO `tb_invest_stat` (`username`,`date`,`time`,`money`) 
				VALUES('$user_inv', '".DATE("d.m.Y")."', '".strtotime(DATE("d.m.Y"))."', '$money_add_stat')");
			}
		}
	}
}
*/
function invest_stat($summa, $type=false) {
	$sql_inv = mysql_query("SELECT * FROM `tb_invest_users` WHERE `count_shares`>'0'");
	if(mysql_num_rows($sql_inv)>0) {

		$sql_inv_site = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'");
		$site_count_shares = mysql_result($sql_inv_site,0,0);

		if($type != false) {
			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='$type'");
			$proc_dividend = number_format(mysql_result($sql,0,0), 0, ".", "");
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='7'");
			$proc_dividend = number_format(mysql_result($sql,0,0), 0, ".", "");
		}

		while ($row_inv = mysql_fetch_assoc($sql_inv)) {
			$user_inv = $row_inv["username"];
			$user_count_shares = $row_inv["count_shares"];

			$money_add_stat = ($summa * ($proc_dividend/100) * $user_count_shares / $site_count_shares);
			$money_add_stat = number_format($money_add_stat, 6, ".", "");

			mysql_query("UPDATE `tb_invest_users` SET `dohod_s`=`dohod_s`+'$money_add_stat', `dohod_all`=`dohod_all`+'$money_add_stat' WHERE `username`='$user_inv'");
			mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'$money_add_stat' WHERE `item`='pay_all_dividend'");

			mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_add_stat' WHERE `username`='$user_inv'");
			
			//mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) 
				//VALUES('$user_inv', '".DATE("d.m.Y H:i")."', '$money_add_stat', 'Процент от заказа рекламы ', 'Зачислено', 'invest')") or die(mysql_error());


			$sql_inv_stat = mysql_query("SELECT * FROM `tb_invest_stat` WHERE `username`='$user_inv' AND `date`='".DATE("d.m.Y")."'");
			if(mysql_num_rows($sql_inv_stat)>0) {
				mysql_query("UPDATE `tb_invest_stat` SET `money`=`money`+'$money_add_stat' WHERE `username`='$user_inv' AND `date`='".DATE("d.m.Y")."'");
			}else{
				mysql_query("INSERT INTO `tb_invest_stat` (`username`,`date`,`time`,`money`) 
				VALUES('$user_inv', '".DATE("d.m.Y")."', '".strtotime(DATE("d.m.Y"))."', '$money_add_stat')");
			}
		}
	}
}

function stat_pay($TYPE, $MONEY){
	$sql_stat_us = mysql_query("SELECT `id` FROM `tb_ads_stat` WHERE `type`='$TYPE' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_stat_us)>0) {
		$row_stat = mysql_fetch_row($sql_stat_us);

		mysql_query("UPDATE `tb_ads_stat` SET `sum_all`=`sum_all`+'$MONEY', `sum_year`=`sum_year`+'$MONEY', `sum_month`=`sum_month`+'$MONEY', `sum_week`=`sum_week`+'$MONEY', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$MONEY' WHERE `type`='$TYPE'") or die(mysql_error());
	}else{
		mysql_query("INSERT INTO `tb_ads_stat` (`type`,`sum_all`,`sum_year`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) 
		VALUES('$TYPE','$MONEY','$MONEY','$MONEY','$MONEY','$MONEY')") or die(mysql_error());
	}
}

### NEW KONKURS ADVERTISE ###
function konkurs_ads_new($wmid, $username, $money_pay){
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='status'");
	$konk_ads_status = mysql_result($sql,0,0);

	if($konk_ads_status==1) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_start'");
		$konk_ads_date_start = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_end'");
		$konk_ads_date_end = mysql_result($sql,0,0);

		if($konk_ads_date_end>=time() && $konk_ads_date_start<=time()) {
			mysql_query("UPDATE `tb_users` SET `konkurs_ads`=`konkurs_ads`+'$money_pay' WHERE (`username`='$username' OR (`wmid`!='' AND `wmid`='$wmid')) AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ");
		}
	}

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='status'");
	$konk_ads_big_status = mysql_result($sql,0,0);

	if($konk_ads_big_status==1) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_start'");
		$konk_ads_big_date_start = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_end'");
		$konk_ads_big_date_end = mysql_result($sql,0,0);

		if($konk_ads_big_date_end>=time() && $konk_ads_big_date_start<=time()) {
			mysql_query("UPDATE `tb_users` SET `konkurs_ads_big`=`konkurs_ads_big`+'$money_pay' WHERE (`username`='$username' OR (`wmid`!='' AND `wmid`='$wmid')) AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ");
		}
	}
}
### NEW KONKURS ADVERTISE ###

### NEW КОНКУРС ПО РАЗМЕЩЕНИЮ ССЫЛОК В СЕРФИНГЕ ###
function konkurs_serf_new($wmid, $username, $money_pay){
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='status'");
	$konk_serf_status = mysql_result($sql,0,0);

	if($konk_serf_status==1) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_start'");
		$konk_serf_date_start = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_end'");
		$konk_serf_date_end = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='timer_ot'");
        $konk_serf_timer = mysql_result($sql,0,0);
		
		if($konk_serf_date_end>=time() && $konk_serf_date_start<=time()) {
			mysql_query("UPDATE `tb_users` SET `konkurs_serf`=`konkurs_serf`+'1' WHERE (`username`='$username' OR (`wmid`!='' AND `wmid`='$wmid')) AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ");
		}
	}
}
### NEW КОНКУРС ПО РАЗМЕЩЕНИЮ ССЫЛОК В СЕРФИНГЕ ###

### NEW KONKURS ADVERTISE TASK ###
function konkurs_ads_task_new($wmid, $username, $money_pay){
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='status'");
	$konk_ads_task_status = mysql_result($sql,0,0);

	if($konk_ads_task_status==1) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='date_start'");
		$konk_ads_task_date_start = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='date_end'");
		$konk_ads_task_date_end = mysql_result($sql,0,0);

		if($konk_ads_task_date_end>=time() && $konk_ads_task_date_start<=time()) {
			mysql_query("UPDATE `tb_users` SET `konkurs_ads_task`=`konkurs_ads_task`+'$money_pay' WHERE (`username`='$username' OR (`wmid`!='' AND `wmid`='$wmid')) AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ");
		}
	}
}
### NEW KONKURS ADVERTISE TASK ###

function ads_wmid($wmid_user, $wmr_user, $username, $money_pay){
	if(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_wmid` WHERE `wmid`='$wmid_user'")) > 0) {
		mysql_query("UPDATE `tb_ads_wmid` SET `date_e`='".time()."', `wmr`='$wmr_user',`username`='$username',`zakazov`=`zakazov`+'1',`moneys`=`moneys`+'$money_pay' WHERE `wmid`='$wmid_user'");
	}else{
		mysql_query("INSERT INTO `tb_ads_wmid` (`date_s`,`date_e`,`wmid`,`wmr`,`username`,`zakazov`,`moneys`) 
		VALUES('".time()."','".time()."','$wmid_user','$wmr_user','$username','1','$money_pay')");
	}
}

function ads_date() {
	if(mysql_num_rows(mysql_query("SELECT `data` FROM `tb_ads_date` WHERE `data`='".DATE("d.m.Y")."'")) > 0) {
		mysql_query("UPDATE `tb_ads_date` SET `kolvo`=`kolvo`+'1' WHERE `data`='".DATE("d.m.Y")."'");
	}else{
		mysql_query("INSERT INTO `tb_ads_date` (`data`,`kolvo`) VALUES('".DATE("d.m.Y")."','1')");
	}
}

?>