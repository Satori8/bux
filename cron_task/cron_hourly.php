<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

mysql_query("UPDATE `tb_ads_dlink` SET `limit_h_now`=`limit_h` WHERE `status`!='0'") or die(mysql_error());

$site_wmid = mysql_result(mysql_query("SELECT `sitewmid` FROM `tb_site` WHERE `id`='1'"),0,0);

function count_text($count, $text1, $text2, $text3, $text) {
	if($count>0) {
		if( ($count>=10) && ($count<=20) ) {
			return "<b>$count</b>$text1";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "<b>$count</b>$text2"; break;
				case 2: case 3: case 4: return "<b>$count</b>$text3"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b>$text1"; break;
			}
		}
	}
}

### NEW ������� �� ����������� ��������� NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='status'");
$konk_ref_status = mysql_result($sql,0,0);

if($konk_ref_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='date_start'");
	$konk_ref_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='date_end'");
	$konk_ref_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='autostart'");
	$konk_ref_autostart = mysql_result($sql,0,0);


	if($konk_ref_date_start<=time() && $konk_ref_date_end>=time()) {
		### �������� �������� ����� ###
		$sql_new = mysql_query("SELECT `id`,`username` FROM `tb_users`");
		if(mysql_num_rows($sql_new)) {
			$sql_m_1 = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_click'");
			$konk_ref_min_click = mysql_result($sql_m_1,0,0);

			$sql_m_2 = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_day_act'");
			$konk_ref_min_day_act = mysql_result($sql_m_2,0,0);

			while ($row_new = mysql_fetch_array($sql_new)){
				$id_new = $row_new['id'];  
				$username_new = $row_new['username'];
				$count_ref_new = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `tb_users` WHERE `referer`='$username_new' AND `joindate2`>='$konk_ref_date_start' AND `joindate2`<='$konk_ref_date_end' AND (`visits`+`visits_bs`)>='$konk_ref_min_click' AND `lastlogdate2`>='".(time()-$konk_ref_min_day_act*24*60*60)."'"), 0, 0);

				mysql_query("UPDATE `tb_users` SET `konkurs_ref`='$count_ref_new' WHERE `id`='".$id_new."'") or die(mysql_error());
			}
		}  
		### �������� �������� ����� ###
	}

	if(($konk_ref_date_end-10)<=time() && $konk_ref_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_ref'");
		$konk_ref_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='all_count_prize'");
		$konk_ref_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='type_prize'");
		$konk_ref_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=3; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='prizes' AND `howmany`='$y'");
			$konk_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ref' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_ref_all_count_prize; $i++) {
				if($konk_ref_prizes[$y][$i]==0) {
					if($y==1) { $_Ref_Prizes[$y][$i] = false; $_Ref_Prizes_Txt[$y][$i] = false; }
					if($y==2) { $_Ref_Prizes[$y][$i] = false; $_Ref_Prizes_Txt[$y][$i] = false; }
					if($y==3) { $_Ref_Prizes[$y][$i] = false; $_Ref_Prizes_Txt[$y][$i] = false; }
				}else{
					if($y==1) { $_Ref_Prizes[$y][$i] = number_format($konk_ref_prizes[$y][$i],2,".",""); $_Ref_Prizes_Txt[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", ""); }
					if($y==2) { $_Ref_Prizes[$y][$i] = number_format($konk_ref_prizes[$y][$i],0,".",""); $_Ref_Prizes_Txt[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", ""); }
					if($y==3) { $_Ref_Prizes[$y][$i] = number_format($konk_ref_prizes[$y][$i],0,".",""); $_Ref_Prizes_Txt[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", ""); }
				}
			}
		}

		for($i=0; $i<$konk_ref_all_count_prize; $i++) {
			$_Ref_Prizes_Txt_New[$i] = $_Ref_Prizes_Txt[1][$i]."|".$_Ref_Prizes_Txt[2][$i]."|".$_Ref_Prizes_Txt[3][$i];
			$_Ref_Prizes_Txt_New[$i] = explode("|", $_Ref_Prizes_Txt_New[$i]);
			$_Ref_Prizes_Txt_New[$i] = trim(implode(" ", $_Ref_Prizes_Txt_New[$i]));
			$_Ref_Prizes_Txt_New[$i] = str_replace("  ", " ", $_Ref_Prizes_Txt_New[$i]);
			$_Ref_Prizes_Txt_New[$i] = str_replace(" ", "<br>+", $_Ref_Prizes_Txt_New[$i]);
		}

		$_Prizes_Cash = $konk_ref_type_prize[0]==1 ? 1 : false;
		$_Prizes_Reit = $konk_ref_type_prize[1]==1 ? 1 : false;
		$_Prizes_Refs = $konk_ref_type_prize[2]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ref` DESC limit $konk_ref_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_array($sql)) {
				$_MESTO++;
				if($row["konkurs_ref"]>=$konk_ref_min) {
					$_Prizes_Txt_Tab = $_Ref_Prizes_Txt_New[$_MESTO-1];
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_Prizes_Txt_Tab = "�� ��������� ������� ��������";
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Ref_Prizes[1][$_MESTO-1]>0 ? $_Ref_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = number_format($_ADD_MONEY, 2, ".", "");
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Ref_Prizes[2][$_MESTO-1]>0 ? $_Ref_Prizes[2][$_MESTO-1] : 0;
				}

				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Ref_Prizes[3][$_MESTO-1]>0 ? $_Ref_Prizes[3][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = false;
				$_ADD_USERS_TAB.= ($_ADD_MONEY>0 ? "`money`=`money`+'$_ADD_MONEY' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REIT>0 ? "`reiting`=`reiting`+'$_ADD_REIT' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REFERALS>0 ? "`referals`=`referals`+'$_ADD_REFERALS' " : false);
				$_ADD_USERS_TAB = str_replace(" ", ", ", trim($_ADD_USERS_TAB));

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','ref','$konk_ref_date_start','$konk_ref_date_end','$konk_ref_all_count_prize','".$row["username"]."','".$row["konkurs_ref"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_PRIZES_USERS==1) {
					if($_ADD_MONEY>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� �������� ��������� <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_ref`='0'") or die(mysql_error());

		if($konk_ref_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='ref' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='ref' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_ref_date_end-$konk_ref_date_start)."' WHERE `type`='ref' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='ref' AND `item`='status'");
		}
	}
}
### NEW ������� �� ����������� ��������� NEW ###


### NEW ������� �������������� �1 NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='status'");
$konk_ads_status = mysql_result($sql,0,0);

if($konk_ads_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_start'");
	$konk_ads_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_end'");
	$konk_ads_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='autostart'");
	$konk_ads_autostart = mysql_result($sql,0,0);

	if(($konk_ads_date_end-10)<=time() && $konk_ads_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='min_do'");
		$konk_ads_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='all_count_prize'");
		$konk_ads_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='type_prize'");
		$konk_ads_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=5; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='prizes' AND `howmany`='$y'");
			$konk_ads_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_all_count_prize; $i++) {
				if($konk_ads_prizes[$y][$i]==0) {
					if($y==1) { $_Ads_Prizes[$y][$i] = false; $_Ads_Prizes_Txt[$y][$i] = false; }
					if($y==2) { $_Ads_Prizes[$y][$i] = false; $_Ads_Prizes_Txt[$y][$i] = false; }
					if($y==3) { $_Ads_Prizes[$y][$i] = false; $_Ads_Prizes_Txt[$y][$i] = false; }
					if($y==4) { $_Ads_Prizes[$y][$i] = false; $_Ads_Prizes_Txt[$y][$i] = false; }
					if($y==5) { $_Ads_Prizes[$y][$i] = false; $_Ads_Prizes_Txt[$y][$i] = false; }
				}else{
					if($y==1) { $_Ads_Prizes[$y][$i] = number_format($konk_ads_prizes[$y][$i],2,".",""); $_Ads_Prizes_Txt[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;��&nbsp;���.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;���.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;���.&nbsp;����", ""); }
					if($y==2) { $_Ads_Prizes[$y][$i] = number_format($konk_ads_prizes[$y][$i],2,".",""); $_Ads_Prizes_Txt[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;��&nbsp;����.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;����.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;����.&nbsp;����", ""); }
					if($y==3) { $_Ads_Prizes[$y][$i] = number_format($konk_ads_prizes[$y][$i],0,".",""); $_Ads_Prizes_Txt[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", ""); }
					if($y==4) { $_Ads_Prizes[$y][$i] = number_format($konk_ads_prizes[$y][$i],0,".",""); $_Ads_Prizes_Txt[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", ""); }
					if($y==5) { $_Ads_Prizes[$y][$i] = number_format($konk_ads_prizes[$y][$i],0,".",""); $_Ads_Prizes_Txt[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "%&nbsp;��&nbsp;�����������&nbsp;�����&nbsp;", "%&nbsp;��&nbsp;�����������&nbsp;�����&nbsp;", "%&nbsp;��&nbsp;�����������&nbsp;�����&nbsp;", ""); }
				}
			}
		}

		for($i=0; $i<$konk_ads_all_count_prize; $i++) {
			$_Ads_Prizes_Txt_New[$i] = $_Ads_Prizes_Txt[1][$i]."|".$_Ads_Prizes_Txt[2][$i]."|".$_Ads_Prizes_Txt[5][$i]."|".$_Ads_Prizes_Txt[3][$i]."|".$_Ads_Prizes_Txt[4][$i];
			$_Ads_Prizes_Txt_New[$i] = explode("|", $_Ads_Prizes_Txt_New[$i]);
			$_Ads_Prizes_Txt_New[$i] = trim(implode(" ", $_Ads_Prizes_Txt_New[$i]));
			$_Ads_Prizes_Txt_New[$i] = str_replace("  ", " ", $_Ads_Prizes_Txt_New[$i]);
			$_Ads_Prizes_Txt_New[$i] = str_replace(" ", "<br>+", $_Ads_Prizes_Txt_New[$i]);
		}

		$_Prizes_Cash = $konk_ads_type_prize[0]==1 ? 1 : false;
		$_Prizes_Cash_Rb = $konk_ads_type_prize[1]==1 ? 1 : false;
		$_Prizes_Reit = $konk_ads_type_prize[2]==1 ? 1 : false;
		$_Prizes_Refs = $konk_ads_type_prize[3]==1 ? 1 : false;
		$_Prizes_Proc = $konk_ads_type_prize[4]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads` DESC limit $konk_ads_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_MONEY_RB = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_array($sql)) {
				$_MESTO++;
				if($row["konkurs_ads"]>=$konk_ads_min) {
					$_Prizes_Txt_Tab = $_Ads_Prizes_Txt_New[$_MESTO-1];
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_Prizes_Txt_Tab = "�� ��������� ������� ��������";
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1 | $_Prizes_Proc==1) {
					$_ADD_MONEY = ($_Ads_Prizes[1][$_MESTO-1] + $row["konkurs_ads"]*($_Ads_Prizes[5][$_MESTO-1]/100));
					$_ADD_MONEY = number_format($_ADD_MONEY, 2, ".", "");
				}

				if($_Prizes_Cash_Rb==1) {
					$_ADD_MONEY_RB = $_Ads_Prizes[2][$_MESTO-1]>0 ? $_Ads_Prizes[2][$_MESTO-1] : 0;
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Ads_Prizes[3][$_MESTO-1]>0 ? $_Ads_Prizes[3][$_MESTO-1] : 0;
				}

				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Ads_Prizes[4][$_MESTO-1]>0 ? $_Ads_Prizes[4][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = false;
				$_ADD_USERS_TAB.= ($_ADD_MONEY>0 ? "`money`=`money`+'$_ADD_MONEY' " : false);
				$_ADD_USERS_TAB.= ($_ADD_MONEY_RB>0 ? "`money_rb`=`money_rb`+'$_ADD_MONEY_RB' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REIT>0 ? "`reiting`=`reiting`+'$_ADD_REIT' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REFERALS>0 ? "`referals`=`referals`+'$_ADD_REFERALS' " : false);
				$_ADD_USERS_TAB = str_replace(" ", ", ", trim($_ADD_USERS_TAB));

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','ads','$konk_ads_date_start','$konk_ads_date_end','$konk_ads_all_count_prize','".$row["username"]."','".$row["konkurs_ads"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_PRIZES_USERS==1) {
					if($_ADD_MONEY>0 | $_ADD_MONEY_RB>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� �������������� <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_ads`='0'") or die(mysql_error());

		if($konk_ads_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='ads' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='ads' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_ads_date_end-$konk_ads_date_start)."' WHERE `type`='ads' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='ads' AND `item`='status'");
		}
	}
}
### NEW ������� �������������� �1 NEW ###

### NEW ������� �������������� �2 NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='status'");
$konk_ads_big_status = mysql_result($sql,0,0);

if($konk_ads_big_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_start'");
	$konk_ads_big_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_end'");
	$konk_ads_big_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='autostart'");
	$konk_ads_big_autostart = mysql_result($sql,0,0);

	if(($konk_ads_big_date_end-10)<=time() && $konk_ads_big_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='min_do'");
		$konk_ads_big_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='all_count_prize'");
		$konk_ads_big_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='type_prize'");
		$konk_ads_big_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=5; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='prizes' AND `howmany`='$y'");
			$konk_ads_big_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads_big' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_big_all_count_prize; $i++) {
				if($konk_ads_big_prizes[$y][$i]==0) {
					$_Ads_Big_Prizes[$y][$i] = 0;
				}else{
					if($y==1) { $_Ads_Big_Prizes[$y][$i] = number_format($konk_ads_big_prizes[$y][$i],2,".",""); }
					if($y==2) { $_Ads_Big_Prizes[$y][$i] = number_format($konk_ads_big_prizes[$y][$i],2,".",""); }
					if($y==3) { $_Ads_Big_Prizes[$y][$i] = number_format($konk_ads_big_prizes[$y][$i],0,".",""); }
					if($y==4) { $_Ads_Big_Prizes[$y][$i] = number_format($konk_ads_big_prizes[$y][$i],0,".",""); }
					if($y==5) { $_Ads_Big_Prizes[$y][$i] = number_format($konk_ads_big_prizes[$y][$i],0,".",""); }
				}
			}
		}

		$_Prizes_Cash_1 = $konk_ads_big_type_prize[0]==1 ? 1 : false;
		$_Prizes_Cash_Rb_1 = $konk_ads_big_type_prize[1]==1 ? 1 : false;
		$_Prizes_Cash_2 = $konk_ads_big_type_prize[2]==1 ? 1 : false;
		$_Prizes_Cash_Rb_2 = $konk_ads_big_type_prize[3]==1 ? 1 : false;
		$_Prizes_Reit = $konk_ads_big_type_prize[4]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC limit $konk_ads_big_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_MONEY_RB = 0;
			$_ADD_REIT = 0;

			while($row = mysql_fetch_assoc($sql)) {
				$_MESTO++;
				if($row["konkurs_ads_big"]>=$konk_ads_big_min) {
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash_1==1 | $_Prizes_Cash_2==1) {
					$_ADD_MONEY_1 = $_Ads_Big_Prizes[1][$_MESTO-1]>0 ? $_Ads_Big_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY_2 = $_Ads_Big_Prizes[3][$_MESTO-1]>0 ? ($_Ads_Big_Prizes[3][$_MESTO-1] * $row["konkurs_ads_big"]/100) : 0;
					$_ADD_MONEY = round(($_ADD_MONEY_1+$_ADD_MONEY_2), 2);
				}

				if($_Prizes_Cash_Rb_1==1 | $_Prizes_Cash_Rb_2==1) {
					$_ADD_MONEY_RB_1 = $_Ads_Big_Prizes[2][$_MESTO-1]>0 ? $_Ads_Big_Prizes[2][$_MESTO-1] : 0;
					$_ADD_MONEY_RB_2 = $_Ads_Big_Prizes[4][$_MESTO-1]>0 ? ($_Ads_Big_Prizes[4][$_MESTO-1] * $row["konkurs_ads_big"]/100) : 0;
					$_ADD_MONEY_RB = round(($_ADD_MONEY_RB_1+$_ADD_MONEY_RB_2), 2);
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Ads_Big_Prizes[5][$_MESTO-1]>0 ? number_format($_Ads_Big_Prizes[5][$_MESTO-1], 0, ".", "") : 0;
				}

				$_ADD_USERS_TAB = array();
				$_Prizes_Txt_Tab = array();

				if($_ADD_MONEY>0) 	$_ADD_USERS_TAB[].= "`money`=`money`+'$_ADD_MONEY'";
				if($_ADD_MONEY_RB>0) 	$_ADD_USERS_TAB[].= "`money_rb`=`money_rb`+'$_ADD_MONEY_RB'";
				if($_ADD_REIT>0) 	$_ADD_USERS_TAB[].= "`reiting`=`reiting`+'$_ADD_REIT'";

				if($_ADD_MONEY>0) 	$_Prizes_Txt_Tab[].= "<b>$_ADD_MONEY</b>&nbsp;���.[���.&nbsp;����]";
				if($_ADD_MONEY_RB>0) 	$_Prizes_Txt_Tab[].= "<b>$_ADD_MONEY_RB</b>&nbsp;���.[����.&nbsp;����]";
				if($_ADD_REIT>0) 	$_Prizes_Txt_Tab[].= count_text($_ADD_REIT, "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

				$_ADD_USERS_TAB = ( is_array($_ADD_USERS_TAB) && count($_ADD_USERS_TAB)>0 && $_ADD_PRIZES_USERS==1 ) ? implode(", ", $_ADD_USERS_TAB) : false;
				$_Prizes_Txt_Tab = ( is_array($_Prizes_Txt_Tab) && count($_Prizes_Txt_Tab)>0 && $_ADD_PRIZES_USERS==1 ) ? implode("<br>+", $_Prizes_Txt_Tab) : "�� ��������� ������� ��������";

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','ads_big','$konk_ads_big_date_start','$konk_ads_big_date_end','$konk_ads_big_all_count_prize','".$row["username"]."','".$row["konkurs_ads_big"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_USERS_TAB!==false) {
					if($_ADD_MONEY>0 | $_ADD_MONEY_RB>0 | $_ADD_REIT>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','".($_ADD_MONEY+$_ADD_MONEY_RB)."','���� �� <b>$_MESTO</b> ����� � �������� ��������������[2] <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_ads_big`='0'") or die(mysql_error());

		if($konk_ads_big_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='ads_big' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='ads_big' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_ads_big_date_end-$konk_ads_big_date_start)."' WHERE `type`='ads_big' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='ads_big' AND `item`='status'");
		}
	}
}
### NEW ������� �������������� �2 NEW ###

### NEW ������� �������� NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='status'");
$konk_click_status = mysql_result($sql,0,0);

if($konk_click_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_start'");
	$konk_click_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_end'");
	$konk_click_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='autostart'");
	$konk_click_autostart = mysql_result($sql,0,0);

	if(($konk_click_date_end-10)<=time() && $konk_click_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='min_do'");
		$konk_click_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='all_count_prize'");
		$konk_click_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='type_prize'");
		$konk_click_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=3; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='prizes' AND `howmany`='$y'");
			$konk_click_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='click' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_click_all_count_prize; $i++) {
				if($konk_click_prizes[$y][$i]==0) {
					if($y==1) { $_Click_Prizes[$y][$i] = false; $_Click_Prizes_Txt[$y][$i] = false; }
					if($y==2) { $_Click_Prizes[$y][$i] = false; $_Click_Prizes_Txt[$y][$i] = false; }
					if($y==3) { $_Click_Prizes[$y][$i] = false; $_Click_Prizes_Txt[$y][$i] = false; }
				}else{
					if($y==1) { $_Click_Prizes[$y][$i] = number_format($konk_click_prizes[$y][$i],2,".",""); $_Click_Prizes_Txt[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", ""); }
					if($y==2) { $_Click_Prizes[$y][$i] = number_format($konk_click_prizes[$y][$i],0,".",""); $_Click_Prizes_Txt[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", ""); }
					if($y==3) { $_Click_Prizes[$y][$i] = number_format($konk_click_prizes[$y][$i],0,".",""); $_Click_Prizes_Txt[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", ""); }
				}
			}
		}

		for($i=0; $i<$konk_click_all_count_prize; $i++) {
			$_Click_Prizes_Txt_New[$i] = $_Click_Prizes_Txt[1][$i]."|".$_Click_Prizes_Txt[2][$i]."|".$_Click_Prizes_Txt[3][$i];
			$_Click_Prizes_Txt_New[$i] = explode("|", $_Click_Prizes_Txt_New[$i]);
			$_Click_Prizes_Txt_New[$i] = trim(implode(" ", $_Click_Prizes_Txt_New[$i]));
			$_Click_Prizes_Txt_New[$i] = str_replace("  ", " ", $_Click_Prizes_Txt_New[$i]);
			$_Click_Prizes_Txt_New[$i] = str_replace(" ", "<br>+", $_Click_Prizes_Txt_New[$i]);
		}

		$_Prizes_Cash = $konk_click_type_prize[0]==1 ? 1 : false;
		$_Prizes_Reit = $konk_click_type_prize[1]==1 ? 1 : false;
		$_Prizes_Refs = $konk_click_type_prize[2]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_click` DESC limit $konk_click_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_array($sql)) {
				$_MESTO++;
				if($row["konkurs_click"]>=$konk_click_min) {
					$_Prizes_Txt_Tab = $_Click_Prizes_Txt_New[$_MESTO-1];
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_Prizes_Txt_Tab = "�� ��������� ������� ��������";
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Click_Prizes[1][$_MESTO-1]>0 ? $_Click_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = number_format($_ADD_MONEY, 2, ".", "");
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Click_Prizes[2][$_MESTO-1]>0 ? $_Click_Prizes[2][$_MESTO-1] : 0;
				}

				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Click_Prizes[3][$_MESTO-1]>0 ? $_Click_Prizes[3][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = false;
				$_ADD_USERS_TAB.= ($_ADD_MONEY>0 ? "`money`=`money`+'$_ADD_MONEY' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REIT>0 ? "`reiting`=`reiting`+'$_ADD_REIT' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REFERALS>0 ? "`referals`=`referals`+'$_ADD_REFERALS' " : false);
				$_ADD_USERS_TAB = str_replace(" ", ", ", trim($_ADD_USERS_TAB));

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','click','$konk_click_date_start','$konk_click_date_end','$konk_click_all_count_prize','".$row["username"]."','".$row["konkurs_click"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_PRIZES_USERS==1) {
					if($_ADD_MONEY>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� �������� <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_click`='0'") or die(mysql_error());

		if($konk_click_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='click' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='click' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_click_date_end-$konk_click_date_start)."' WHERE `type`='click' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='click' AND `item`='status'");
		}
	}
}
### NEW ������� �������� NEW ###

### NEW ������� ������ NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='status'");
$konk_test_status = mysql_result($sql,0,0);

if($konk_test_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_start'");
	$konk_test_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_end'");
	$konk_test_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='autostart'");
	$konk_test_autostart = mysql_result($sql,0,0);

	if(($konk_test_date_end-10)<=time() && $konk_test_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='min_do'");
		$konk_test_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='all_count_prize'");
		$konk_test_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='type_prize'");
		$konk_test_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=3; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='prizes' AND `howmany`='$y'");
			$konk_test_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='test' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_test_all_count_prize; $i++) {
				if($konk_test_prizes[$y][$i]==0) {
					if($y==1) { $_Test_Prizes[$y][$i] = false; $_Test_Prizes_Txt[$y][$i] = false; }
					if($y==2) { $_Test_Prizes[$y][$i] = false; $_Test_Prizes_Txt[$y][$i] = false; }
					if($y==3) { $_Test_Prizes[$y][$i] = false; $_Test_Prizes_Txt[$y][$i] = false; }
				}else{
					if($y==1) { $_Test_Prizes[$y][$i] = number_format($konk_test_prizes[$y][$i],2,".",""); $_Test_Prizes_Txt[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", ""); }
					if($y==2) { $_Test_Prizes[$y][$i] = number_format($konk_test_prizes[$y][$i],0,".",""); $_Test_Prizes_Txt[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", ""); }
					if($y==3) { $_Test_Prizes[$y][$i] = number_format($konk_test_prizes[$y][$i],0,".",""); $_Test_Prizes_Txt[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", ""); }
				}
			}
		}

		for($i=0; $i<$konk_test_all_count_prize; $i++) {
			$_Test_Prizes_Txt_New[$i] = $_Test_Prizes_Txt[1][$i]."|".$_Test_Prizes_Txt[2][$i]."|".$_Test_Prizes_Txt[3][$i];
			$_Test_Prizes_Txt_New[$i] = explode("|", $_Test_Prizes_Txt_New[$i]);
			$_Test_Prizes_Txt_New[$i] = trim(implode(" ", $_Test_Prizes_Txt_New[$i]));
			$_Test_Prizes_Txt_New[$i] = str_replace("  ", " ", $_Test_Prizes_Txt_New[$i]);
			$_Test_Prizes_Txt_New[$i] = str_replace(" ", "<br>+", $_Test_Prizes_Txt_New[$i]);
		}

		$_Prizes_Cash = $konk_test_type_prize[0]==1 ? 1 : false;
		$_Prizes_Reit = $konk_test_type_prize[1]==1 ? 1 : false;
		$_Prizes_Refs = $konk_test_type_prize[2]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_test` DESC limit $konk_test_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_array($sql)) {
				$_MESTO++;
				if($row["konkurs_test"]>=$konk_test_min) {
					$_Prizes_Txt_Tab = $_Test_Prizes_Txt_New[$_MESTO-1];
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_Prizes_Txt_Tab = "�� ��������� ������� ��������";
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Test_Prizes[1][$_MESTO-1]>0 ? $_Test_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = number_format($_ADD_MONEY, 2, ".", "");
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Test_Prizes[2][$_MESTO-1]>0 ? $_Test_Prizes[2][$_MESTO-1] : 0;
				}

				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Test_Prizes[3][$_MESTO-1]>0 ? $_Test_Prizes[3][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = false;
				$_ADD_USERS_TAB.= ($_ADD_MONEY>0 ? "`money`=`money`+'$_ADD_MONEY' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REIT>0 ? "`reiting`=`reiting`+'$_ADD_REIT' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REFERALS>0 ? "`referals`=`referals`+'$_ADD_REFERALS' " : false);
				$_ADD_USERS_TAB = str_replace(" ", ", ", trim($_ADD_USERS_TAB));

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','test','$konk_test_date_start','$konk_test_date_end','$konk_test_all_count_prize','".$row["username"]."','".$row["konkurs_test"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_PRIZES_USERS==1) {
					if($_ADD_MONEY>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}
					

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� �� ����������� ������ <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_test`='0'") or die(mysql_error());

		if($konk_test_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='test' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='test' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_test_date_end-$konk_test_date_start)."' WHERE `type`='test' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='test' AND `item`='status'");
		}
	}
}
### NEW ������� ������ NEW ###

### NEW ������� YOUTUBE NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='status'");
$konk_youtub_status = mysql_result($sql,0,0);

if($konk_youtub_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_start'");
	$konk_youtub_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_end'");
	$konk_youtub_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='autostart'");
	$konk_youtub_autostart = mysql_result($sql,0,0);

	if(($konk_youtub_date_end-10)<=time() && $konk_youtub_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='min_do'");
		$konk_youtub_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='all_count_prize'");
		$konk_youtub_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='type_prize'");
		$konk_youtub_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=3; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='prizes' AND `howmany`='$y'");
			$konk_youtub_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='youtub' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_youtub_all_count_prize; $i++) {
				if($konk_youtub_prizes[$y][$i]==0) {
					if($y==1) { $_Youtub_Prizes[$y][$i] = false; $_Youtub_Prizes_Txt[$y][$i] = false; }
					if($y==2) { $_Youtub_Prizes[$y][$i] = false; $_Youtub_Prizes_Txt[$y][$i] = false; }
					if($y==3) { $_Youtub_Prizes[$y][$i] = false; $_Youtub_Prizes_Txt[$y][$i] = false; }
				}else{
					if($y==1) { $_Youtub_Prizes[$y][$i] = number_format($konk_youtub_prizes[$y][$i],2,".",""); $_Youtub_Prizes_Txt[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", ""); }
					if($y==2) { $_Youtub_Prizes[$y][$i] = number_format($konk_youtub_prizes[$y][$i],0,".",""); $_Youtub_Prizes_Txt[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", ""); }
					if($y==3) { $_Youtub_Prizes[$y][$i] = number_format($konk_youtub_prizes[$y][$i],0,".",""); $_Youtub_Prizes_Txt[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", ""); }
				}
			}
		}

		for($i=0; $i<$konk_youtub_all_count_prize; $i++) {
			$_Youtub_Prizes_Txt_New[$i] = $_Youtub_Prizes_Txt[1][$i]."|".$_Youtub_Prizes_Txt[2][$i]."|".$_Youtub_Prizes_Txt[3][$i];
			$_Youtub_Prizes_Txt_New[$i] = explode("|", $_Youtub_Prizes_Txt_New[$i]);
			$_Youtub_Prizes_Txt_New[$i] = trim(implode(" ", $_Youtub_Prizes_Txt_New[$i]));
			$_Youtub_Prizes_Txt_New[$i] = str_replace("  ", " ", $_Youtub_Prizes_Txt_New[$i]);
			$_Youtub_Prizes_Txt_New[$i] = str_replace(" ", "<br>+", $_Youtub_Prizes_Txt_New[$i]);
		}

		$_Prizes_Cash = $konk_youtub_type_prize[0]==1 ? 1 : false;
		$_Prizes_Reit = $konk_youtub_type_prize[1]==1 ? 1 : false;
		$_Prizes_Refs = $konk_youtub_type_prize[2]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_youtub` DESC limit $konk_youtub_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_array($sql)) {
				$_MESTO++;
				if($row["konkurs_youtub"]>=$konk_youtub_min) {
					$_Prizes_Txt_Tab = $_Youtub_Prizes_Txt_New[$_MESTO-1];
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_Prizes_Txt_Tab = "�� ��������� ������� ��������";
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Youtub_Prizes[1][$_MESTO-1]>0 ? $_Youtub_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = number_format($_ADD_MONEY, 2, ".", "");
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Youtub_Prizes[2][$_MESTO-1]>0 ? $_Youtub_Prizes[2][$_MESTO-1] : 0;
				}

				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Youtub_Prizes[3][$_MESTO-1]>0 ? $_Youtub_Prizes[3][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = false;
				$_ADD_USERS_TAB.= ($_ADD_MONEY>0 ? "`money`=`money`+'$_ADD_MONEY' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REIT>0 ? "`reiting`=`reiting`+'$_ADD_REIT' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REFERALS>0 ? "`referals`=`referals`+'$_ADD_REFERALS' " : false);
				$_ADD_USERS_TAB = str_replace(" ", ", ", trim($_ADD_USERS_TAB));

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','youtub','$konk_youtub_date_start','$konk_youtub_date_end','$konk_youtub_all_count_prize','".$row["username"]."','".$row["konkurs_youtub"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_PRIZES_USERS==1) {
					if($_ADD_MONEY>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� YouTube <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_youtub`='0'") or die(mysql_error());

		if($konk_youtub_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='youtub' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='youtub' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_youtub_date_end-$konk_youtub_date_start)."' WHERE `type`='youtub' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='youtub' AND `item`='status'");
		}
	}
}
### NEW ������� YOUTUBE NEW ###

### NEW ������� �� ���������� ������� NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
$konk_task_status = mysql_result($sql,0,0);

if($konk_task_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
	$konk_task_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
	$konk_task_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='autostart'");
	$konk_task_autostart = mysql_result($sql,0,0);

	if(($konk_task_date_end-10)<=time() && $konk_task_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='min_do'");
		$konk_task_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='all_count_prize'");
		$konk_task_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='type_prize'");
		$konk_task_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=3; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='prizes' AND `howmany`='$y'");
			$konk_task_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='task' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_task_all_count_prize; $i++) {
				if($konk_task_prizes[$y][$i]==0) {
					if($y==1) { $_Task_Prizes[$y][$i] = false; $_Task_Prizes_Txt[$y][$i] = false; }
					if($y==2) { $_Task_Prizes[$y][$i] = false; $_Task_Prizes_Txt[$y][$i] = false; }
					if($y==3) { $_Task_Prizes[$y][$i] = false; $_Task_Prizes_Txt[$y][$i] = false; }
				}else{
					if($y==1) { $_Task_Prizes[$y][$i] = number_format($konk_task_prizes[$y][$i],2,".",""); $_Task_Prizes_Txt[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", ""); }
					if($y==2) { $_Task_Prizes[$y][$i] = number_format($konk_task_prizes[$y][$i],0,".",""); $_Task_Prizes_Txt[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", ""); }
					if($y==3) { $_Task_Prizes[$y][$i] = number_format($konk_task_prizes[$y][$i],0,".",""); $_Task_Prizes_Txt[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", ""); }
				}
			}
		}

		for($i=0; $i<$konk_task_all_count_prize; $i++) {
			$_Task_Prizes_Txt_New[$i] = $_Task_Prizes_Txt[1][$i]."|".$_Task_Prizes_Txt[2][$i]."|".$_Task_Prizes_Txt[3][$i];
			$_Task_Prizes_Txt_New[$i] = explode("|", $_Task_Prizes_Txt_New[$i]);
			$_Task_Prizes_Txt_New[$i] = trim(implode(" ", $_Task_Prizes_Txt_New[$i]));
			$_Task_Prizes_Txt_New[$i] = str_replace("  ", " ", $_Task_Prizes_Txt_New[$i]);
			$_Task_Prizes_Txt_New[$i] = str_replace(" ", "<br>+", $_Task_Prizes_Txt_New[$i]);
		}

		$_Prizes_Cash = $konk_task_type_prize[0]==1 ? 1 : false;
		$_Prizes_Reit = $konk_task_type_prize[1]==1 ? 1 : false;
		$_Prizes_Refs = $konk_task_type_prize[2]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_task` DESC limit $konk_task_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_array($sql)) {
				$_MESTO++;
				if($row["konkurs_task"]>=$konk_task_min) {
					$_Prizes_Txt_Tab = $_Task_Prizes_Txt_New[$_MESTO-1];
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_Prizes_Txt_Tab = "�� ��������� ������� ��������";
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Task_Prizes[1][$_MESTO-1]>0 ? $_Task_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = number_format($_ADD_MONEY, 2, ".", "");
				}

				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Task_Prizes[2][$_MESTO-1]>0 ? $_Task_Prizes[2][$_MESTO-1] : 0;
				}

				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Task_Prizes[3][$_MESTO-1]>0 ? $_Task_Prizes[3][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = false;
				$_ADD_USERS_TAB.= ($_ADD_MONEY>0 ? "`money`=`money`+'$_ADD_MONEY' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REIT>0 ? "`reiting`=`reiting`+'$_ADD_REIT' " : false);
				$_ADD_USERS_TAB.= ($_ADD_REFERALS>0 ? "`referals`=`referals`+'$_ADD_REFERALS' " : false);
				$_ADD_USERS_TAB = str_replace(" ", ", ", trim($_ADD_USERS_TAB));

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','task','$konk_task_date_start','$konk_task_date_end','$konk_task_all_count_prize','".$row["username"]."','".$row["konkurs_task"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_PRIZES_USERS==1) {
					if($_ADD_MONEY>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� �� ���������� ������� <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_task`='0'") or die(mysql_error());

		if($konk_task_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='task' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='task' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_task_date_end-$konk_task_date_start)."' WHERE `type`='task' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='task' AND `item`='status'");
		}
	}
}
### NEW ������� �� ���������� ������� NEW ###

### NEW ����������� ������� NEW ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'");
$konk_complex_status = mysql_result($sql,0,0);

if($konk_complex_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'");
	$konk_complex_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'");
	$konk_complex_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='autostart'");
	$konk_complex_autostart = mysql_result($sql,0,0);

	if(($konk_complex_date_end-10)<=time() && $konk_complex_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='min_do'");
		$konk_complex_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='all_count_prize'");
		$konk_complex_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='type_prize'");
		$konk_complex_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=4; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='prizes' AND `howmany`='$y'");
			$konk_complex_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='complex' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=4; $y++) {
			for($i=0; $i<$konk_complex_all_count_prize; $i++) {
				if($konk_complex_prizes[$y][$i]==0) {
					$_Complex_Prizes[$y][$i] = 0;
				}else{
					if($y==1) { $_Complex_Prizes[$y][$i] = number_format($konk_complex_prizes[$y][$i],4,".",""); }
					if($y==2) { $_Complex_Prizes[$y][$i] = number_format($konk_complex_prizes[$y][$i],4,".",""); }
					if($y==3) { $_Complex_Prizes[$y][$i] = number_format($konk_complex_prizes[$y][$i],0,".",""); }
					if($y==4) { $_Complex_Prizes[$y][$i] = number_format($konk_complex_prizes[$y][$i],0,".",""); }
				}
			}
		}

		$_Prizes_Cash    = $konk_complex_type_prize[0]==1 ? 1 : false;
		$_Prizes_Cash_Rb = $konk_complex_type_prize[1]==1 ? 1 : false;
		$_Prizes_Reit    = $konk_complex_type_prize[2]==1 ? 1 : false;
		$_Prizes_Refs    = $konk_complex_type_prize[3]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_complex` DESC limit $konk_complex_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_MONEY_RB = 0;
			$_ADD_REIT = 0;
			$_ADD_REFERALS = 0;

			while($row = mysql_fetch_assoc($sql)) {
				$_MESTO++;
				if($row["konkurs_complex"]>=$konk_complex_min) {
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Complex_Prizes[1][$_MESTO-1]>0 ? $_Complex_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = round($_ADD_MONEY, 4);
				}
				if($_Prizes_Cash_Rb==1) {
					$_ADD_MONEY_RB = $_Complex_Prizes[2][$_MESTO-1]>0 ? $_Complex_Prizes[2][$_MESTO-1] : 0;
					$_ADD_MONEY_RB = round($_ADD_MONEY_RB, 4);
				}
				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Complex_Prizes[3][$_MESTO-1]>0 ? $_Complex_Prizes[3][$_MESTO-1] : 0;
				}
				if($_Prizes_Refs==1) {
					$_ADD_REFERALS = $_Complex_Prizes[4][$_MESTO-1]>0 ? $_Complex_Prizes[4][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = array();
				$_Prizes_Txt_Tab = array();

				if($_ADD_MONEY>0) 	$_ADD_USERS_TAB[].= "`money`=`money`+'$_ADD_MONEY'";
				if($_ADD_MONEY_RB>0) 	$_ADD_USERS_TAB[].= "`money_rb`=`money_rb`+'$_ADD_MONEY_RB'";
				if($_ADD_REIT>0) 	$_ADD_USERS_TAB[].= "`reiting`=`reiting`+'$_ADD_REIT'";
				if($_ADD_REFERALS>0) 	$_ADD_USERS_TAB[].= "`referals`=`referals`+'$_ADD_REFERALS'";

				if($_ADD_MONEY>0) 	$_Prizes_Txt_Tab[].= "<b>$_ADD_MONEY</b>&nbsp;���.[���.&nbsp;����]";
				if($_ADD_MONEY_RB>0) 	$_Prizes_Txt_Tab[].= "<b>$_ADD_MONEY_RB</b>&nbsp;���.[����.&nbsp;����]";
				if($_ADD_REIT>0) 	$_Prizes_Txt_Tab[].= count_text($_ADD_REIT, "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
				if($_ADD_REFERALS>0) 	$_Prizes_Txt_Tab[].= count_text($_ADD_REFERALS, "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");

				$_ADD_USERS_TAB = ( is_array($_ADD_USERS_TAB) && count($_ADD_USERS_TAB)>0 && $_ADD_PRIZES_USERS==1 ) ? implode(", ", $_ADD_USERS_TAB) : false;
				$_Prizes_Txt_Tab = ( is_array($_Prizes_Txt_Tab) && count($_Prizes_Txt_Tab)>0 && $_ADD_PRIZES_USERS==1 ) ? implode("<br>+", $_Prizes_Txt_Tab) : "�� ��������� ������� ��������";

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','complex','$konk_complex_date_start','$konk_complex_date_end','$konk_complex_all_count_prize','".$row["username"]."','".$row["konkurs_complex"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_USERS_TAB!==false) {
					if($_ADD_MONEY>0 | $_ADD_MONEY_RB>0 | $_ADD_REIT>0 | $_ADD_REFERALS>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','".($_ADD_MONEY+$_ADD_MONEY_RB)."','���� �� <b>$_MESTO</b> ����� � ����������� �������� <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_assoc($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_complex`='0'") or die(mysql_error());

		if($konk_complex_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='complex' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='complex' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_complex_date_end-$konk_complex_date_start)."' WHERE `type`='complex' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='complex' AND `item`='status'");
		}
	}
}
### NEW ����������� ������� NEW ###

### NEW ������� �� ���������� ������ � �������� ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='status'");
$konk_serf_status = mysql_result($sql,0,0);

if($konk_serf_status==1) {
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_start'");
	$konk_serf_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_end'");
	$konk_serf_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='autostart'");
	$konk_serf_autostart = mysql_result($sql,0,0);

	if(($konk_serf_date_end-10)<=time() && $konk_serf_date_start<=time()) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='min_do'");
		$konk_serf_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='all_count_prize'");
		$konk_serf_all_count_prize = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='type_prize'");
		$konk_serf_type_prize = explode("; ", mysql_result($sql,0,0));

		for($y=1; $y<=3; $y++) {
			$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='prizes' AND `howmany`='$y'");
			$konk_serf_prizes[$y] = explode("; ", mysql_result($sql,0,0));
		}

		$sql = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='serf' ORDER BY `ident` DESC LIMIT 1");
		$k_nomer = mysql_num_rows($sql)>0 ? (intval(mysql_result($sql,0,0)) + 1) : 1;

		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_serf_all_count_prize; $i++) {
				if($konk_serf_prizes[$y][$i]==0) {
					$_Serf_Prizes[$y][$i] = 0;
				}else{
					if($y==1) { $_Serf_Prizes[$y][$i] = number_format($konk_serf_prizes[$y][$i],2,".",""); }
					if($y==2) { $_Serf_Prizes[$y][$i] = number_format($konk_serf_prizes[$y][$i],2,".",""); }
					if($y==3) { $_Serf_Prizes[$y][$i] = number_format($konk_serf_prizes[$y][$i],0,".",""); }
				}
			}
		}

		$_Prizes_Cash    = $konk_serf_type_prize[0]==1 ? 1 : false;
		$_Prizes_Cash_Rb = $konk_serf_type_prize[1]==1 ? 1 : false;
		$_Prizes_Reit    = $konk_serf_type_prize[2]==1 ? 1 : false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_serf` DESC limit $konk_serf_all_count_prize");
		if(mysql_num_rows($sql)>0) {
			$_MESTO = 0;
			$_ADD_MONEY = 0;
			$_ADD_MONEY_RB = 0;
			$_ADD_REIT = 0;
			//$_ADD_REFERALS = 0;

			while($row = mysql_fetch_assoc($sql)) {
				$_MESTO++;
				if($row["konkurs_serf"]>=$konk_serf_min) {
					$_ADD_PRIZES_USERS = 1;
				}else{
					$_ADD_PRIZES_USERS = 0;
				}

				if($_Prizes_Cash==1) {
					$_ADD_MONEY = $_Serf_Prizes[1][$_MESTO-1]>0 ? $_Serf_Prizes[1][$_MESTO-1] : 0;
					$_ADD_MONEY = round($_ADD_MONEY, 2);
				}
				if($_Prizes_Cash_Rb==1) {
					$_ADD_MONEY_RB = $_Serf_Prizes[2][$_MESTO-1]>0 ? $_Serf_Prizes[2][$_MESTO-1] : 0;
					$_ADD_MONEY_RB = round($_ADD_MONEY_RB, 2);
				}
				if($_Prizes_Reit==1) {
					$_ADD_REIT = $_Serf_Prizes[3][$_MESTO-1]>0 ? $_Serf_Prizes[3][$_MESTO-1] : 0;
				}

				$_ADD_USERS_TAB = array();
				$_Prizes_Txt_Tab = array();

				if($_ADD_MONEY>0) 	$_ADD_USERS_TAB[].= "`money`=`money`+'$_ADD_MONEY'";
				if($_ADD_MONEY_RB>0) 	$_ADD_USERS_TAB[].= "`money_rb`=`money_rb`+'$_ADD_MONEY_RB'";
				if($_ADD_REIT>0) 	$_ADD_USERS_TAB[].= "`reiting`=`reiting`+'$_ADD_REIT'";
				
				if($_ADD_MONEY>0) 	$_Prizes_Txt_Tab[].= "<b>$_ADD_MONEY</b>&nbsp;���.[���.&nbsp;����]";
				if($_ADD_MONEY_RB>0) 	$_Prizes_Txt_Tab[].= "<b>$_ADD_MONEY_RB</b>&nbsp;���.[����.&nbsp;����]";
				if($_ADD_REIT>0) 	$_Prizes_Txt_Tab[].= count_text($_ADD_REIT, "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

				$_ADD_USERS_TAB = ( is_array($_ADD_USERS_TAB) && count($_ADD_USERS_TAB)>0 && $_ADD_PRIZES_USERS==1 ) ? implode(", ", $_ADD_USERS_TAB) : false;
				$_Prizes_Txt_Tab = ( is_array($_Prizes_Txt_Tab) && count($_Prizes_Txt_Tab)>0 && $_ADD_PRIZES_USERS==1 ) ? implode("<br>+", $_Prizes_Txt_Tab) : "�� ��������� ������� ��������";

				mysql_query("INSERT INTO `tb_konkurs_rezult` (`ident`,`type`,`date_s`,`date_e`,`count_priz`,`username`,`kolvo`,`summa_array`,`mesto`,`statuspay`) 
				VALUES('$k_nomer','serf','$konk_serf_date_start','$konk_serf_date_end','$konk_serf_all_count_prize','".$row["username"]."','".$row["konkurs_serf"]."','$_Prizes_Txt_Tab','$_MESTO','1')") or die(mysql_error());

				if($_ADD_USERS_TAB!==false) {
					if($_ADD_MONEY>0 | $_ADD_MONEY_RB>0 | $_ADD_REIT>0) {
						mysql_query("UPDATE `tb_users` SET $_ADD_USERS_TAB WHERE `username`='".$row["username"]."'") or die(mysql_error());
					}

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`,`type`) 
					VALUES('1','".$row["username"]."','".$row["id"]."','".DATE("d.m.Y H:i")."','".time()."','$_ADD_MONEY','���� �� <b>$_MESTO</b> ����� � �������� �� ���������� ������ � �������� <b>�$k_nomer</b><br>$_Prizes_Txt_Tab','���������','prihod','1')") or die(mysql_error());

					/*$sql_check_ref = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`!='".$row["username"]."' AND `referer`='' AND `user_status`='0' AND `not_get_ref`='0' AND `freeus`='0' AND `ban_date`='0' ORDER BY `lastlogdate2` DESC LIMIT $_ADD_REFERALS") or die(mysql_error());
					if(mysql_num_rows($sql_check_ref)>0) {
						while($row_check_ref = mysql_fetch_array($sql_check_ref)) {
							mysql_query("UPDATE `tb_users` SET `statusref`='-1', `referer`='".$row["username"]."', `referer2`='".$row["referer"]."', `referer3`='".$row["referer2"]."' WHERE `username`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer2`='".$row["username"]."', `referer3`='".$row["referer"]."' WHERE `referer`='".$row_check_ref["username"]."'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `referer3`='".$row["username"]."' WHERE `referer2`='".$row_check_ref["username"]."'") or die(mysql_error());
						}
					}*/
				}
			}
		}

		mysql_query("UPDATE `tb_users` SET `konkurs_serf`='0'") or die(mysql_error());

		if($konk_serf_autostart==1) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='serf' AND `item`='status'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='".strtotime(DATE("d.m.Y"))."' WHERE `type`='serf' AND `item`='date_start'");
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`=`price`+'".abs($konk_serf_date_end-$konk_serf_date_start)."' WHERE `type`='serf' AND `item`='date_end'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='serf' AND `item`='status'");
		}
	}
}
### NEW ������� �� ���������� ������ � �������� ###

### �������� �� ��������� ###
$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `date_e`<='".time()."' ORDER BY `id` ASC");
if(mysql_num_rows($sql_t)>0) {
	while ($row_t = mysql_fetch_array($sql_t)) {
		for($i=1; $i<=$row_t["count_kon"]; $i++){
			$p[$i] = $row_t["p$i"];
		}

		$sql_s = mysql_query("SELECT `id`,`username`, SUM(`kolvo`) as `sum_kolvo` FROM `tb_refkonkurs_stat` WHERE `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."' GROUP BY `username` HAVING `sum_kolvo`>='".$row_t["limit_kon"]."' AND `sum_kolvo`>='".$row_t["limit_kon_sum"]."' ORDER BY `sum_kolvo` DESC LIMIT ".$row_t["count_kon"]." ");
		$count_s = mysql_num_rows($sql_s);
		$m=0;
		if($count_s>0) {
			while ($row_s = mysql_fetch_array($sql_s)) {
				$m++;
				mysql_query("UPDATE `tb_users` SET `money`=`money`+'$p[$m]' WHERE `username`='".$row_s["username"]."'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`,`type`) 
				VALUES('".$row_s["username"]."', '".DATE("d.m.Y�. H:i")."', '$p[$m]',  '���� �� $m ����� � �������� � ".$row_t["id"]." �� �������� ".$row_t["username"]."','���������','prihod','2')") or die(mysql_error());
			}
		}
		mysql_query("UPDATE `tb_refkonkurs` SET `status`='2' WHERE `id`='".$row_t["id"]."'") or die(mysql_error());

		if($count_s<$row_t["count_kon"]) {
			$m_l = $row_t["count_kon"];
			$m_f = $count_s;
			$money_back = 0;

			for($i=$m_l; $i>$m_f; $i--){
				$money_back = ($money_back + $p[$i]);
			}

			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back' WHERE `username`='".$row_t["username"]."'") or die(mysql_error());
			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
			VALUES('".$row_t["username"]."', '".DATE("d.m.Y�. H:i")."', '$money_back',  '������� ������� �� ������� ��� ���������','���������','prihod')") or die(mysql_error());
		}
	}
}
### �������� �� ��������� ###



@mysql_close();
?>