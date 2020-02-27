<?php

function ActionRef($money_pay, $user_name) {
	global $mysqli;

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

	$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status' AND `howmany`='1'") or die($mysqli->error);
	$site_action_status = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
	$sql->free();

	if($site_action_status == 1) {
		$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_paymin' AND `howmany`='1'") or die($mysqli->error);
		$site_action_paymin = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 2, ".", "") : false;
		$sql->free();

		if($money_pay >= $site_action_paymin && $user_name != false) {
			$sql_user = $mysqli->query("SELECT `id`,`username`,`referer`,`referer2` FROM `tb_users` WHERE `username`='$user_name' AND `username`!='Admin'") or die($mysqli->error);
			if($sql_user->num_rows > 0) {
				$row_user = $sql_user->fetch_assoc();
				$user_id = $row_user["id"];
				$user_name = $row_user["username"];
				$user_referer_1 = $row_user["referer"];
				$user_referer_2 = $row_user["referer2"];
				$sql_user->free();

				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_payads' AND `howmany`='1'") or die($mysqli->error);
				$site_action_payads = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
				$sql->free();

				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_date_ref' AND `howmany`='1'") or die($mysqli->error);
				$site_action_date_ref = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
				$sql->free();

				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status_ref' AND `howmany`='1'") or die($mysqli->error);
				$site_action_status_ref = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
				$sql->free();

				$sql = $mysqli->query("SELECT `r_ot` FROM `tb_config_rang` WHERE `id`='$site_action_status_ref'") or die($mysqli->error);
				$ref_reit_ot = $sql->num_rows > 0 ? number_format($sql->fetch_object()->r_ot, 0, ".", "") : false;
				$sql->free();

				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_addreit' AND `howmany`='1'") or die($mysqli->error);
				$site_action_addreit = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
				$sql->free();

				//$_CntGetRef = floor($money_pay/$site_action_payads);
				$_CntGetRef = floor(bcdiv($money_pay, $site_action_payads));
				$_AddReit = floor(bcdiv($money_pay, $site_action_paymin)) * $site_action_addreit;

				$sql_ref = $mysqli->query("SELECT `id`,`username` FROM `tb_users` WHERE `username`!='$user_name' AND `referer`='Admin' AND `user_status`='0' AND `freeus`='0' AND `ban_date`='0' AND `not_get_ref_a`='0' AND `reiting`>='$ref_reit_ot' AND `lastlogdate2`>='".(time()-$site_action_date_ref*24*60*60)."' LIMIT $_CntGetRef") or die($mysqli->error);
				$cnt_ref = $sql_ref->num_rows;
				if($cnt_ref > 0) {
					while ($row_ref = $sql_ref->fetch_assoc()) {
						$mysqli->query("UPDATE `tb_users` SET `statusref`='10', `referer`='$user_name', `referer2`='$user_referer_1', `referer3`='$user_referer_2' WHERE `username`='".$row_ref["username"]."'") or die($mysqli->error);
						$mysqli->query("UPDATE `tb_users` SET `referer2`='$user_name', `referer3`='$user_referer_1' WHERE `referer`='".$row_ref["username"]."'") or die($mysqli->error);
						$mysqli->query("UPDATE `tb_users` SET `referer3`='$user_name' WHERE `referer2`='".$row_ref["username"]."'") or die($mysqli->error);
					}
					$sql_ref->free();

					$mysqli->query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
					VALUES('$user_name','Система','Системное сообщение','Здравствуйте, <b>$user_name</b>.<br>В рамках акции <b>Рефералы всем</b>, Вы получили ".CntTxtAR($cnt_ref, "реферала", "реферала", "рефералов")." и ".CntTxtAR($_AddReit, "балл", "балла", "баллов")." рейтинга в качестве приза от администрации проекта, за потраченные на рекламу <b>$money_pay</b> руб.<br>Посмотреть список рефералов Вы можете на странице <a href=\"/referals1.php\" target=\"_blank\">мои рефералы</a>.','0','".time()."','127.0.0.1')") or die($mysqli->error);


					$sql_r1 = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `referer`='$user_name'") or die($mysqli->error);
					$referals1 = $sql_r1->num_rows;
					$sql_r1->free();

					$sql_r2 = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `referer2`='$user_name'") or die($mysqli->error);
					$referals2 = $sql_r2->num_rows;
					$sql_r2->free();

					$sql_r3 = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `referer3`='$user_name'") or die($mysqli->error);
					$referals3 = $sql_r3->num_rows;
					$sql_r3->free();

					$mysqli->query("UPDATE `tb_users` SET `reiting`=`reiting`+'$_AddReit', `referals`='$referals1', `referals2`='$referals2', `referals3`='$referals3' WHERE `username`='$user_name'") or die($mysqli->error);


					$sql_r1 = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `referer`='Admin'") or die($mysqli->error);
					$referals1 = $sql_r1->num_rows;
					$sql_r1->free();

					$sql_r2 = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `referer2`='Admin'") or die($mysqli->error);
					$referals2 = $sql_r2->num_rows;
					$sql_r2->free();

					$sql_r3 = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `referer3`='Admin'") or die($mysqli->error);
					$referals3 = $sql_r3->num_rows;
					$sql_r3->free();

					$mysqli->query("UPDATE `tb_users` SET `referals`='$referals1', `referals2`='$referals2', `referals3`='$referals3' WHERE `username`='Admin'") or die($mysqli->error);
				}
			}else{
				$sql_user->free();
			}
		}	
	}
}

function BonusSurf($username, $money_pay) {
	global $mysqli;

	$sql = $mysqli->query("SELECT `id` FROM `tb_config` WHERE `item`='bonus_surf_status' AND `price`='1'") or die($mysqli->error);
	if($username != false && $sql->num_rows > 0) {
		$sql->free();

		$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_money' AND `howmany`='1'") or die($mysqli->error);
		$bonus_surf_cnt_money = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 2, ".", "") : false;
		$sql->free();

		if($money_pay >= $bonus_surf_cnt_money) {
			$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_visits' AND `howmany`='1'") or die($mysqli->error);
			$bonus_surf_cnt_visits = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
			$sql->free();

			$bonus_surf = floor($money_pay/$bonus_surf_cnt_money) * $bonus_surf_cnt_visits;

			if($bonus_surf > 0) {
				$mysqli->query("UPDATE `tb_users` SET `bonus_surf`=`bonus_surf`+'$bonus_surf' WHERE `username`='$username'") or die($mysqli->error);
			}
		}
	}else{
		$sql->free();
	}
}

function ProcRefRB($username, $merch_amount) {
	global $mysqli;

	if($username != false) {
		$sql_user = $mysqli->query("SELECT `referer` FROM `tb_users` WHERE `username`='$username'") or die($mysqli->error);
		if($sql_user->num_rows > 0) {
			$row_user = $sql_user->fetch_assoc();
			$my_referer_1 = $row_user["referer"];
			$sql_user->free();

			if($my_referer_1 != false) {
				$sql_ref_1 = $mysqli->query("SELECT `id`,`reiting` FROM `tb_users` WHERE `username`='$my_referer_1'") or die($mysqli->error);
				if($sql_ref_1->num_rows > 0) {
					$row_ref_1 = $sql_ref_1->fetch_assoc();
					$id_ref_1 = $row_ref_1["id"];
					$reit_ref_1 = $row_ref_1["reiting"];
					$sql_ref_1->free();

					$sql_rang_1 = $mysqli->query("SELECT `id`,`balance_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='".floor($reit_ref_1)."' AND `balance_1`>'0'") or die($mysqli->error);
					if($sql_rang_1->num_rows > 0) {
						$row_rang_1 = $sql_rang_1->fetch_assoc();
						$bal_proc_1 = $row_rang_1["balance_1"];
						$sql_rang_1->free();
						$bonus_balance_1 = $merch_amount * ($bal_proc_1/100);

						if($bonus_balance_1 > 0.01) {
							$mysqli->query("UPDATE `tb_users` SET `money`=`money`+'$bonus_balance_1' WHERE `username`='$my_referer_1'") or die($mysqli->error);

							$mysqli->query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
							VALUES('1','0','$my_referer_1','$id_ref_1','".DATE("d.m.Y H:i",time())."','".time()."','$bonus_balance_1','Бонус ($bal_proc_1%) от пополнения рекламного баланса рефералом $username','Зачислено','prihod')") or die($mysqli->error);
						}
					}else{
						$sql_rang_1->free();
					}
				}else{
					$sql_ref_1->free();
				}
			}
		}else{
			$sql_user->free();
		}
	}
}

function PartnerSet($username, $type_ads, $start_cena, $plan, $type_banner=false) {
	global $mysqli;

	$sql_user = $mysqli->query("SELECT `id`,`username`,`referer` FROM `tb_users` WHERE `username`='$username'") or die($mysqli->error);
	if($sql_user->num_rows > 0) {
		$row_user = $sql_user->fetch_assoc();
		$username = $row_user["username"];
		$my_referer_1 = $row_user["referer"];
		$sql_user->free();

		$sql_p = $mysqli->query("SELECT * FROM `tb_users_partner` WHERE `username`='$username'") or die($mysqli->error);
		if($sql_p->num_rows > 0) {
			$row_p = $sql_p->fetch_assoc();
			$sql_p->free();

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
				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'") or die($mysqli->error);
			}else{
				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'") or die($mysqli->error);
			}
			$partner_max_percent = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
			$sql->free();

			if($type_ads=="p_packet_") {
				$sql = $mysqli->query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day_pack'") or die($mysqli->error);
			}else{
				$sql = $mysqli->query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day'") or die($mysqli->error);
			}
			if($sql->num_rows > 0) {
				$row_pd = $sql->fetch_assoc();
				$partner_count_day = $row_pd["howmany"];
				$partner_count_per = $row_pd["price"];
			}else{
				$partner_count_day = 1;
				$partner_count_per = 1;
			}
			$sql->free();

			if($type_ads=="p_packet_") {
				$add_per = $partner_count_per;
			}else{
				$add_per = floor($plan/$partner_count_day * $partner_count_per);
			}
			$add_percent_user = floor($type_percent + $add_per);
			if($add_percent_user>$partner_max_percent) $add_percent_user = $partner_max_percent;

			$sql_up_p = $mysqli->query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username' AND `".$type_ads_tab."`>'0'") or die($mysqli->error);
			if($sql_up_p->num_rows > 0) {
				$mysqli->query("UPDATE `tb_users_partner` SET `".$type_ads_tab."`='$add_percent_user' WHERE `username`='$username'") or die($mysqli->error);
			}
			$sql_up_p->free();
		}


		$sql_p = $mysqli->query("SELECT `id` FROM `tb_users` WHERE `username`='$my_referer_1'") or die($mysqli->error);
		if($sql_p->num_rows > 0 && $my_referer_1 != false) {
			if($type_ads=="p_b") {
				$type_ads_tab = "$type_ads"."$type_banner";
			}elseif($type_ads=="p_packet_") {
				$type_ads_tab = "$type_ads"."$plan";
			}else{
				$type_ads_tab = "$type_ads";
			}

			if($type_ads=="p_packet_") {
				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'") or die($mysqli->error);
			}else{
				$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'") or die($mysqli->error);
			}
			$partner_max_percent = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
			$sql->free();

			$sql_up = $mysqli->query("SELECT `".$type_ads_tab."`,`discount_partner` FROM `tb_users_partner` WHERE `username`='$my_referer_1' AND `".$type_ads_tab."`>'0'") or die($mysqli->error);
			if($sql_up->num_rows > 0) {
				$row_up = $sql_up->fetch_assoc();
				$sql_up->free();

				$discount_referer = $row_up["discount_partner"];
				$ref_type_percent = ($row_up["$type_ads_tab"] * (100 - $discount_referer)/100);

				$money_add_referer = floatval($start_cena / 100 * $ref_type_percent);
				$moneys_poteri = ($start_cena / 100) * ($partner_max_percent * (100 - $discount_referer)/100) - $money_add_referer;

				$mysqli->query("UPDATE `tb_users` SET `money`=`money`+'$money_add_referer' WHERE `username`='$my_referer_1'") or die($mysqli->error);
				$mysqli->query("UPDATE `tb_users_partner` SET `moneys`=`moneys`+'$money_add_referer', `moneys_p`=`moneys_p`+'$moneys_poteri' WHERE `username`='$my_referer_1'") or die($mysqli->error);

				$mysqli->query("INSERT INTO `tb_partner` (`time`, `username`, `referer`, `type`, `money`, `percent`) 
				VALUES('".time()."','$username','$my_referer_1','".$type_ads_tab."','$money_add_referer', '$ref_type_percent')") or die($mysqli->error);
			}else{
				$sql_up_2 = $mysqli->query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$my_referer_1'") or die($mysqli->error);
				if($sql_up_2->num_rows > 0) {
					$moneys_poteri = floatval($start_cena / 100 * $partner_max_percent);
					$mysqli->query("UPDATE `tb_users_partner` SET `moneys_p`=`moneys_p`+'$moneys_poteri' WHERE `username`='$my_referer_1'") or die($mysqli->error);
				}else{
					$moneys_poteri = 0;
					$mysqli->query("INSERT INTO `tb_users_partner` (`username`,`moneys_p`) 
					VALUES('$my_referer_1','$moneys_poteri')") or die($mysqli->error);
				}

				$sql_up->free();
				$sql_up_2->free();
			}
		}
	}
}

function invest_stat($summa, $type=false) {
	global $mysqli;

	$sql_inv = $mysqli->query("SELECT * FROM `tb_invest_users` WHERE `count_shares`>'0'") or die($mysqli->error);
	if($sql_inv->num_rows > 0) {
		$sql = $mysqli->query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'") or die($mysqli->error);
		$site_count_shares = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
		$sql->free();

		if($type != false) {
			$sql = $mysqli->query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='$type'") or die($mysqli->error);
			$proc_dividend = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
			$sql->free();
		}else{
			$sql = $mysqli->query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='7'") or die($mysqli->error);
			$proc_dividend = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
			$sql->free();
		}

		while ($row_inv = $sql_inv->fetch_assoc()) {
			$user_inv = $row_inv["username"];
			$user_count_shares = $row_inv["count_shares"];

			$money_add_stat = ($summa * ($proc_dividend/100) * $user_count_shares / $site_count_shares);
			$money_add_stat = number_format($money_add_stat, 6, ".", "");

			$mysqli->query("UPDATE `tb_invest_users` SET `dohod_s`=`dohod_s`+'$money_add_stat', `dohod_all`=`dohod_all`+'$money_add_stat' WHERE `username`='$user_inv'") or die($mysqli->error);
			$mysqli->query("UPDATE `tb_invest_config` SET `price`=`price`+'$money_add_stat' WHERE `item`='pay_all_dividend'") or die($mysqli->error);

			$mysqli->query("UPDATE `tb_users` SET `money`=`money`+'$money_add_stat' WHERE `username`='$user_inv'") or die($mysqli->error);

			$sql_stat = $mysqli->query("SELECT `id` FROM `tb_invest_stat` WHERE `username`='$user_inv' AND `date`='".DATE("d.m.Y")."'") or die($mysqli->error);
			if($sql_stat->num_rows > 0) {
				$mysqli->query("UPDATE `tb_invest_stat` SET `money`=`money`+'$money_add_stat' WHERE `username`='$user_inv' AND `date`='".DATE("d.m.Y")."'") or die($mysqli->error);
			}else{
				$mysqli->query("INSERT INTO `tb_invest_stat` (`username`,`date`,`time`,`money`) 
				VALUES('$user_inv', '".DATE("d.m.Y")."', '".strtotime(DATE("d.m.Y"))."', '$money_add_stat')") or die($mysqli->error);
			}
			$sql_stat->free();
		}

		$sql_inv->free();
	}else{
		$sql_inv->free();
	}
}

function PayStat($type, $money){
	$sql = $mysqli->query("SELECT `id` FROM `tb_pay_stat` WHERE `type`='$type' ORDER BY `id` DESC LIMIT 1") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$mysqli->query("UPDATE `tb_pay_stat` SET `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$money' WHERE `type`='$type'") or die($mysqli->error);
	}else{
		$mysqli->query("INSERT INTO `tb_pay_stat` (`type`,`".strtolower(DATE("D"))."`) 
		VALUES('$type','$money')") or die($mysqli->error);
	}
	$sql->free();
}

function stat_pay($type, $money){
	global $mysqli;

	$sql = $mysqli->query("SELECT `id` FROM `tb_ads_stat` WHERE `type`='$type' ORDER BY `id` DESC LIMIT 1") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$mysqli->query("UPDATE `tb_ads_stat` SET `sum_all`=`sum_all`+'$money', `sum_year`=`sum_year`+'$money', `sum_month`=`sum_month`+'$money', `sum_week`=`sum_week`+'$money', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$money' WHERE `type`='$type'") or die($mysqli->error);
	}else{
		$mysqli->query("INSERT INTO `tb_ads_stat` (`type`,`sum_all`,`sum_year`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) 
		VALUES('$type','$money','$money','$money','$money','$money')") or die($mysqli->error);
	}
	$sql->free();
}

### KONKURS CNT_SURFING ###
function KonkursCntSurf($username){
	global $mysqli;

	$sql = $mysqli->query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='cnt_surf' AND `item`='status'") or die($mysqli->error);
	$konk_cnt_surf_status = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
	$sql->free();

	if($konk_cnt_surf_status==1) {
		$sql = $mysqli->query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='cnt_surf' AND `item`='date_start'") or die($mysqli->error);
		$konk_cnt_surf_date_start = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
		$sql->free();

		$sql = $mysqli->query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='cnt_surf' AND `item`='date_end'") or die($mysqli->error);
		$konk_cnt_surf_date_end = $sql->num_rows > 0 ? number_format($sql->fetch_object()->price, 0, ".", "") : false;
		$sql->free();

		if($konk_cnt_surf_date_end>=time() && $konk_cnt_surf_date_start<=time()) {
			$mysqli->query("UPDATE `tb_users` SET `konkurs_cnt_surf`=`konkurs_cnt_surf`+'1' WHERE `username`='$username' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die($mysqli->error);
		}
	}
}
### KONKURS CNT_SURFING ###

### NEW KONKURS ADVERTISE ###
function konkurs_ads_new($wmid, $username, $money_pay){
	global $mysqli;

	$sql = $mysqli->query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='status' AND `price`='1'") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$sql_start = $mysqli->query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_start' AND `price`<='".time()."'") or die($mysqli->error);
		$sql_end = $mysqli->query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_end' AND `price`>='".time()."'") or die($mysqli->error);

		if($sql_start->num_rows > 0 && $sql_end->num_rows > 0) {
			$mysqli->query("UPDATE `tb_users` SET `konkurs_ads`=`konkurs_ads`+'$money_pay' WHERE (`username`='$username' OR (`wmid`!='' AND `wmid`='$wmid')) AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ") or die($mysqli->error);
		}

		$sql_start->free();
		$sql_end->free();
	}
	$sql->free();

	$sql = $mysqli->query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='status' AND `price`='1'") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$sql_start = $mysqli->query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_start' AND `price`<='".time()."'") or die($mysqli->error);
		$sql_end = $mysqli->query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_end' AND `price`>='".time()."'") or die($mysqli->error);

		if($sql_start->num_rows > 0 && $sql_end->num_rows > 0) {
			$mysqli->query("UPDATE `tb_users` SET `konkurs_ads_big`=`konkurs_ads_big`+'$money_pay' WHERE (`username`='$username' OR (`wmid`!='' AND `wmid`='$wmid')) AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ") or die($mysqli->error);
		}

		$sql_start->free();
		$sql_end->free();
	}
	$sql->free();
}
### NEW KONKURS ADVERTISE ###

function ads_wmid($wmid_user, $wmr_user, $username, $money_pay){
	global $mysqli;

	$sql = $mysqli->query("SELECT `id` FROM `tb_ads_wmid` WHERE `wmid`='$wmid_user'") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$mysqli->query("UPDATE `tb_ads_wmid` SET `date_e`='".time()."',`wmr`='$wmr_user',`username`='$username',`zakazov`=`zakazov`+'1',`moneys`=`moneys`+'$money_pay' WHERE `wmid`='$wmid_user'") or die($mysqli->error);
	}else{
		$mysqli->query("INSERT INTO `tb_ads_wmid` (`date_s`,`date_e`,`wmid`,`wmr`,`username`,`zakazov`,`moneys`) 
		VALUES('".time()."','".time()."','$wmid_user','$wmr_user','$username','1','$money_pay')") or die($mysqli->error);
	}
	$sql->free();
}

function ads_date() {
	global $mysqli;

	$sql = $mysqli->query("SELECT `id` FROM `tb_ads_date` WHERE `data`='".DATE("d.m.Y")."'") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$mysqli->query("UPDATE `tb_ads_date` SET `kolvo`=`kolvo`+'1' WHERE `data`='".DATE("d.m.Y")."'") or die($mysqli->error);
	}else{
		$mysqli->query("INSERT INTO `tb_ads_date` (`data`,`kolvo`) VALUES('".DATE("d.m.Y")."','1')") or die($mysqli->error);
	}
	$sql->free();

	$sql = $mysqli->query("SELECT `id` FROM `tb_ads_date`") or die($mysqli->error);
	$cnt_dell = intval($sql->num_rows - 7);
	if($cnt_dell > 0) {
		$mysqli->query("DELETE FROM `tb_ads_date` ORDER BY `id` ASC LIMIT $cnt_dell") or die($mysqli->error);
	}

	$sql->free();
}

function StatsUsers($user_name, $day, $type) {
	global $mysqli;

	$sql = $mysqli->query("SELECT `id` FROM `tb_users_stat` WHERE `username`='$user_name' AND `type`='$type'") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$id_stat = $sql->fetch_object()->id;
		$mysqli->query("UPDATE `tb_users_stat` SET `all_views`=`all_views`+'1', `month`=`month`+'1', `".$day."`=`".$day."`+'1' WHERE `id`='$id_stat'") or die($mysqli->error);
	}else{
		$mysqli->query("INSERT INTO `tb_users_stat` (`all_views`,`month`,`username`,`type`, `".$day."`) VALUES('1','1','$user_name','$type','1')") or die($mysqli->error);
	}
	$sql->free();
}

?>