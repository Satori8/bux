<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$text_false_otv="";
$no_form=0; $kolvo="";
$ost=0;

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$zdre = $row["zdre"];
	$rek_name = $row["username"];
	$rek_id = $row["user_id"];
	$zdprice = $row["zdprice"];
	$zdurl = $row["zdurl"];
	$zdcheck = $row["zdcheck"];
	$zdotv = $row["zdotv"];
	$country_targ = $row["country_targ"];
	$mailwm = $row["mailwm"];
	$wmid_task = $row["wmid"];

	if($my_rep_task <= -10) {
		echo '<span class="msg-error">�� �� ������ ��������� �������! ���� ��������� �����������&nbsp;&nbsp;'.$my_rep_task.'</span>';
		include('footer.php');
		exit();
	}

	$check_user = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name' AND `user_name`='$username'");
	if(mysql_num_rows($check_user)) {
		echo '<span class="msg-error">�� �� ������ ��������� ������� ����� �������������, ��� ��� ������������� �������� ��� ��������� ��� �������!</span>';
		include('footer.php');
		exit();
	}elseif(strtolower($rek_name)==strtolower($username)) {
		echo '<fieldset class="errorp">�� �� ������ ��������� ���� �������!<br><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}elseif($country_targ==2 && strtolower($my_country)!="ua") {
		echo '<fieldset class="errorp">������! �� �������� ���-���������� ������� �� �������� ��� ���������� � ������!&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}elseif($country_targ==1 && strtolower($my_country)!="ru") {
		echo '<fieldset class="errorp">������! �� �������� ���-���������� ������� �� �������� ��� ���������� � ������!&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}else


	if(count($_POST>0) && isset($_POST["ctext"])) {

		$ctext = (isset($_POST["ctext"])) ? limpiarez($_POST["ctext"]) : false;

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_p)>0) {
			$row_p = mysql_fetch_array($sql_p);

			$ost = ($row_p["date_end"] + ($zdre * 60 * 60) );
			$ost = ( $ost - time() );

			if(isset($_POST["ctext"])) {
				if($row_p["status"]=="" | $row_p["status"]=="dorab") {
					if($ctext==false) {
						echo '<span class="msg-error">�� �� ������� ���������� (�� �������� �������) ��� �������� ������� ��������������!</span>';
					}else{
						if($zdcheck==2) {

							if(strtolower($zdotv)==strtolower($ctext)) {

								$sql_rt = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
								$reit_task = mysql_result($sql_rt,0,0);

								mysql_query("UPDATE `tb_ads_task_pay` SET `status`='good', `rek_id`='$rek_id', `user_id`='$partnerid', `ctext`='$ctext', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
								mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`-'1', `goods`=`goods`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task',  `visits_t`=`visits_t`+1, `money`=`money`+'$zdprice', `money_t`=`money_t`+'$zdprice' WHERE `username`='$username'") or die(mysql_error());

								#### ��������� ����������� ###
								$cnt_unique_rep = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `user_name`='$username' AND `ident`='$rid'"));
								if($cnt_unique_rep==1) mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`+'1' WHERE `username`='$username'") or die(mysql_error());
								#### ��������� ����������� ###

								#### ������ ������ ###
								stat_pay("task_pay", $zdprice);
								invest_stat($zdprice, 2);
								#### ������ ������ ###

								### START ���� ���������� ####
								$DAY_S = strtolower(DATE("D"));
								stats_users($username, $DAY_S, 'task');
								### END ���� ���������� ######

								#### ������� �������������� ###
								konkurs_ads_new($wmid_task, $rek_name, $zdprice);
								#### ������� �������������� ###

								### �����-������� ����������� NEW ###
								if($country_targ == 0 && strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
									$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
									$konk_complex_status = mysql_result($sql,0,0);

									if($konk_complex_status==1) {
										$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
										$konk_complex_date_start = mysql_result($sql,0,0);

										$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
										$konk_complex_date_end = mysql_result($sql,0,0);

										$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".($zdre==0 ? "task" : "task_re")."'") or die(mysql_error());
										$konk_complex_point = mysql_result($sql,0,0);

										if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
											mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username'") or die(mysql_error());
										}
									}
								}
								### �����-������� ����������� NEW ###

								#### NEW ������� �� ���������� ������� NEW ###
								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
								$konk_task_status = mysql_result($sql,0,0);

								if($konk_task_status==1) {
									$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
									$konk_task_date_start = mysql_result($sql,0,0);

									$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
									$konk_task_date_end = mysql_result($sql,0,0);

									if($konk_task_date_end>=time() && $konk_task_date_start<time()) {
										mysql_query("UPDATE `tb_users` SET `konkurs_task`=`konkurs_task`+'1' WHERE `username`='$username'") or die(mysql_error());
									}
								}
								#### NEW ������� �� ���������� ������� NEW ###

								$sql_r = mysql_query("SELECT `referer`,`referer2`,`referer3` FROM `tb_users` WHERE `username`='$username'");
								$row_r = mysql_fetch_row($sql_r);

								$my_referer_1 = $row_r["0"];
								$my_referer_2 = $row_r["1"];
								$my_referer_3 = $row_r["2"];

								$add_money_r_1=0; $add_money_r_2=0; $add_money_r_3=0;

								if($my_referer_1!="") {
									$sql_r_1 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
									$row_r_1 = mysql_fetch_array($sql_r_1);
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
									mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$username'") or die(mysql_error());

									### ���������� ������� I ��. ###
									if($country_targ=="0") {
										$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
										if(mysql_num_rows($sql_t)>0) {
											$row_t = mysql_fetch_array($sql_t);

											$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
											if(mysql_num_rows($sql_kon) > 0) {
												mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
											}else{
												mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
												VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
											}
										}
									}
									### ���������� ������� I ��. ###

									### ���������� ����������� I ��. ###
									if($country_targ=="0") {
										$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
										if(mysql_num_rows($sql_t)>0) {
											$row_t = mysql_fetch_array($sql_t);

											if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

											$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
											if(mysql_num_rows($sql_kon) > 0) {
												mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
											}else{
												mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
												VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
											}
										}
									}
									### ���������� ����������� I ��. ###

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
										mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_2', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());

										### ���������� ������� II ��. ###
										if($country_targ=="0") {
											$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
											if(mysql_num_rows($sql_t)>0) {
												$row_t = mysql_fetch_array($sql_t);

												$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
												if(mysql_num_rows($sql_kon) > 0) {
													mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
												}else{
													mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
													VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
												}
											}
										}
										### ���������� ������� II ��. ###

										### ���������� ����������� II ��. ###
										if($country_targ=="0") {
											$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
											if(mysql_num_rows($sql_t)>0) {
												$row_t = mysql_fetch_array($sql_t);

												if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

												$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
												if(mysql_num_rows($sql_kon) > 0) {
													mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
												}else{
													mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
													VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
												}
											}
										}
										### ���������� ����������� II ��. ###

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
											mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_3', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());

											### ���������� ������� III ��. ###
											if($country_targ=="0") {
												$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
												if(mysql_num_rows($sql_t)>0) {
													$row_t = mysql_fetch_array($sql_t);

													$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
													if(mysql_num_rows($sql_kon) > 0) {
														mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
													}else{
														mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
														VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
													}
												}
											}
											### ���������� ������� III ��. ###

											### ���������� ����������� III ��. ###
											if($country_targ=="0") {
												$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
												if(mysql_num_rows($sql_t)>0) {
													$row_t = mysql_fetch_array($sql_t);

													if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

													$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
													if(mysql_num_rows($sql_kon) > 0) {
														mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
													}else{
														mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
														VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
													}
												}
											}
											### ���������� ����������� III ��. ###
										}
									}
								}

								### ��������� �� ������� NEW ###
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

											$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `ident`='".$row_r_b_1["id"]."' AND `type`='4' ORDER BY `id` DESC LIMIT 1");
											if(mysql_num_rows($sql_r_b_stat_1)>0) {
												$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

												if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
													$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
													$money_ureferera_nado = round($money_ureferera_nado, 2);

													if($money_ref_1 >= $money_ureferera_nado) {
														mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
														mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

														mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$username'") or die(mysql_error());
														mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());

														mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
														VALUES('1','$username','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','���-����� �� �������� $my_referer_1 �� ".$row_r_b_1["count_nado"]." ����������� �������','���������','rashod')") or die(mysql_error());

														mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
														VALUES('1','$my_referer_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','���-����� �������� $username �� ".$row_r_b_1["count_nado"]." ����������� �������','�������','rashod')") or die(mysql_error());

														if(trim($row_r_b_1["description"])!=false) {
															mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
															VALUES('$username','�������','���-����� �� �������� $my_referer_1 �� ".$row_r_b_1["count_nado"]." ������ � ��������','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
														}
													}else{
														mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
													}
												}else{
													mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
												}
											}else{
												mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
												VALUES('-1','$username','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(mysql_error());
											}
										}
									}
								}
								### ��������� �� ������� NEW ###

								$sql_rek = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$rek_name'");
								$row_rek = mysql_fetch_row($sql_rek);

								$referer_rek = $row_rek["0"];

								if($referer_rek!="") {
									$sql_cr = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_rek' AND `howmany`='1'");
									$ref_proc_rek = mysql_result($sql_cr,0,0);

									$add_balans_rek = round(($zdprice * $ref_proc_rek / 100), 6);

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'$add_balans_rek', `ref_money_rek`=`ref_money_rek`+'$add_balans_rek' WHERE `username`='$referer_rek'") or die(mysql_error());
								}

							}else{
								if($row_p["kol_otv"]>=2) {
									$kolvo = (2-$row_p["kol_otv"]);
									if($kolvo==2) {$kolvo_t="��������� ������ �� ������ ��� 2 ����";}
									if($kolvo==1) {$kolvo_t="��������� ������ �� ������ ��� 1 ���";}
									if(!$kolvo) {$kolvo_t="����� ������� ����������."; $no_form=1;}
									$text_false_otv = "<div align=\"center\" style=\"color:#FF0000; font-weight:bold;\">����� ������������!<br>".$kolvo_t.".</div>";

									if($kolvo==0) {
										mysql_query("UPDATE `tb_ads_task_pay` SET `status`='bad', `rek_id`='$rek_id', `user_id`='$partnerid', `ctext`='$ctext', `kol_otv`=`kol_otv`+'1', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
										mysql_query("UPDATE `tb_ads_task` SET `bads`=`bads`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());

										#### ��������� ����������� ###
										mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`-'1' WHERE `username`='$username'") or die(mysql_error());
										#### ��������� ����������� ###
									}
								}else{
									$kolvo = (2-$row_p["kol_otv"]);
									if($kolvo==2) {$kolvo_t="��������� ������ �� ������ ��� 2 ����";}
									if($kolvo==1) {$kolvo_t="��������� ������ �� ������ ��� 1 ���";}
									if(!$kolvo) {$kolvo_t="����� ������� ����������."; $no_form=1;}
									$text_false_otv = "<div align=\"center\" style=\"color:#FF0000; font-weight:bold;\">����� ������������!<br>".$kolvo_t.".</div>";

									mysql_query("UPDATE `tb_ads_task_pay` SET `ctext`='$ctext', `rek_id`='$rek_id', `user_id`='$partnerid', `kol_otv`=`kol_otv`+'1', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
								}

							}
						}else{
							mysql_query("UPDATE `tb_ads_task_pay` SET `status`='wait', `rek_id`='$rek_id', `user_id`='$partnerid', `ctext`='$ctext', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
							mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`-'1',`wait`=`wait`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
						}
					}
				}else{
					if($ost >= 0 && $row_p["status"]!="") echo '<fieldset class="errorp">������! �� ��� ��������� ��� �������.</fieldset>';
					if($zdre==0 && $row_p["status"]!="") echo '<fieldset class="errorp">������! �� ��� ��������� ��� �������.</fieldset>';
				}
			}else{
				if($zdre>0) {
					if($ost >= 0 && $row_p["status"]!=""){
						echo '<fieldset class="errorp">������! �� ��� ��������� ��� �������.</fieldset>';
					}
				}else{
					if($row_p["status"]!="") echo '<fieldset class="errorp">������! �� ��� ��������� ��� �������.</fieldset>';
				}
			}
		}else{
			if($zdre==0) echo '<fieldset class="errorp">������! �� ��� �� ������ ��������� ��� �������!</fieldset>';
		}
			if( ($zdre>0 && mysql_num_rows($sql_p)<1 ) | ($zdre!=0 && mysql_num_rows($sql_p)<2 && $ost<=0) ) {
			echo '<fieldset class="errorp">������! �� ��� �� ������ ��������� ��� �������!</fieldset>';
		}
	}


	echo '<table class="tables">';
		echo '<tr align="center" height="30px"><th align="center" colspan="2" class="top">������������ �������</th></tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>��������:</b></td>';
			echo '<td>&nbsp;'.str_replace("\r\n","<br>", $row["zdname"]).'</td>';
		echo '</tr>';

		$sql_u = mysql_query("SELECT `id`,`username`,`reiting`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `username`='".$row["username"]."'");
		if(mysql_num_rows($sql_u)>0) {
			$row_u = mysql_fetch_array($sql_u);

			$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];
			$info_wall = '<a href="/wall?uid='.$row_u["id"].'" target="_blank"><img src="../img/wall20.png" title="����� ������������ '.$row_u["username"].'" width="20" border="0" align="absmiddle" />';
			if($wall_com>0) {
				$info_wall.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
			}elseif($wall_com<0) {
				$info_wall.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
			}else{
				$info_wall.= '<b style="color:#000000;">0</b></a>';
			}

			$info_user = ''.$row_u["username"].' <img src="img/reiting.png" border="0" alt="" align="middle" title="�������" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> <a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&task_search=4&task_name='.$row_u["id"].'"><img src="img/view.png" border="0" alt="���������� ��� ������� ����� �������������" align="middle" title="���������� ��� ������� ����� �������������" alt="���������� ��� ������� ����� �������������" style="margin:0; padding:0;" /></a>&nbsp;&nbsp;'.$info_wall.'&nbsp;&nbsp;[<a href="javascript: void(0);" onclick="add_bl(\''.$row_u["id"].'\');" style="color:#000; font-weight: bold;" title="��������� ������������ '.$row_u["username"].' � ������ ������ (Black List)">BL</a>]';
		}else{
			$info_user = ''.$row["user_name"].' <span style="color:#FF0000;">������������� ������</span>';
		}

		echo '<tr>';
			echo '<td width="120" align="right" height="30px"><b>�������������:</b></td>';
			echo '<td><table width="100%"><tr><td width="400">'.$info_user.'</td>';
			echo '</tr></table></td>';

		echo '</tr>';

		echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
		echo '<tr>';
			echo '<td colspan="3">'.$row["zdtext"].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="3"><br>�� ���������� ����� ������� �� ��������: <b>'.$row["zdprice"].' ���.</b></td>';
		echo '</tr>';

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_p)>0) {
			$row_p = mysql_fetch_array($sql_p);
			if($row_p["status"]=="" | $row_p["status"]=="dorab") {
				echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
				if($no_form==1) {
					echo '<tr>';
						echo '<td colspan="3" height="50px">'.$text_false_otv.'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td colspan="3" height="30px" style=" padding:5px;">�� ������ �������� <a href="#">���� ����������� �� ���� �������</a>.</td>';
					echo '</tr>';
				}else{
					echo '<tr>';
						if($row["zdcheck"]==2) {
							echo '<td colspan="3" height="50px">'.$text_false_otv.''.$row["zdquest"].'</td>';
						}else{
							echo '<td colspan="3" height="50px">������� ���������� (�� �������� �������) ��� �������� ������� ��������������:</td>';
						}
					echo '</tr>';
					echo '<form action="" method="POST">';
					echo '<tr>';
						echo '<td colspan="3" height="30px" style=" padding:5px;"><textarea rows="7" name="ctext" style="width:99%; -moz-border-radius: 5px; border-radius: 5px;"></textarea></td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td colspan="3">��� IP-�����: '.$ip.', ������� ����� '.DATE("d.m.Y�. H:i").'</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td colspan="3" height="30px" align="center"><input type="submit" class="submit" value="���������"></td>';
					echo '</tr>';
					echo '</form>';
				}
			}else{
				if($row["zdre"]>0) {
					$ost = ($row_p["date_end"] + ($row["zdre"] * 60 * 60) );
					$ost = ( $ost - time() );

					if($ost > 0){
						$d = floor($ost/86400);
						$h = floor( ($ost - ($d * 86400)) / 3600);
						$i = floor( ($ost - ($d * 86400) - ($h * 3600)) / 60 );
						$s = floor($ost - ($d * 86400) - ($h * 3600) - ($i * 60));

						if($d==0)
							$dn="����";
						elseif($d==1)
							$dn="����";
						elseif($d==2)
							$dn="���";
						elseif($d==3)
							$dn="���";
						else{ $dn=""; }

						if($h>=0) {$hn="�����";}else{$hn="";}
						if(($h>1 && $h<5) | $h>20) {$hn="����";}
						if($h==1 | $h==21) {$hn="���";}

						if($i>0) {$mn="������";}else{$mn="";}
						if($i==1 | $i==21 | $i==31 | $i==41 | $i==51) {$mn="������";}
						if( ($i<21 && $i>4) | $i==0 | $i===20 | $i==30 | $i==40 | $i==50 | ($i>24 && $i<30) | ($i>34 && $i<40) | ($i>44 && $i<50) | ($i>54 && $i<60)) {$mn="�����";}

						if($s>0) {$sn="�������";}else{$sn="";}
						if($s==1 | $s==21 | $s==31 | $s==41 | $s==51) {$sn="�������";}
						if( ($s<21 && $s>4) | $s==0 | $s===20 | $s==30 | $s==40 | $s==50 | ($s>24 && $s<30) | ($s>34 && $s<40) | ($s>44 && $s<50) | ($s>54 && $s<60)) {$sn="������";}

						if($s>0) {$date_next = "<b>$s</b> $sn";}
						if($i>0) {$date_next = "<b>$i</b> $mn <b>$s</b> $sn";}
						if($h>0) {$date_next = "<b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
						if($d>0) {$date_next = "<b>$d</b> $dn <b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
						if($h==0 && $i==0 && $s==0) {$date_next = "<b>$d</b> $dn";}

						if($no_form==1) {
							echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
							echo '<tr>';
								echo '<td colspan="3" height="50px">'.$text_false_otv.'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td colspan="3" height="30px" style=" padding:5px;">�� ������ �������� <a href="#">���� ����������� �� ���� �������</a>.</td>';
							echo '</tr>';
						}else{
							echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">���������� �������</th></tr>';
							echo '<tr>';
								echo '<td colspan="3" height="40px">�� ��� ��������� ��� �������. ��������� ���������� ����� ������� ����� �������� ����� '.$date_next.'</td>';
							echo '</tr>';
							echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
							echo '<tr>';
								echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="ctask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="������ �����"></form></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="submit" class="submit" value="������� � ������ �������"></form></td>';
							echo '</tr>';
						}
					}else{
						echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">���������� �������</th></tr>';
						echo '<tr>';
							echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET" target="_blank"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="gotask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="������ ����������"></form></td>';
						echo '</tr>';
						echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
						echo '<tr>';
							echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="ctask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="��������� ����������"></form></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="submit" class="submit" value="������� � ������ �������"></form></td>';
						echo '</tr>';
					}
				}else{
					if($no_form==1) {
						echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
						echo '<tr>';
							echo '<td colspan="3" height="50px">'.$text_false_otv.'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td colspan="3" height="30px" style=" padding:5px;">�� ������ �������� <a href="#">���� ����������� �� ���� �������</a>.</td>';
						echo '</tr>';
					}else{
						echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">���������� �������</th></tr>';
						echo '<tr>';
							echo '<td colspan="3" height="40px">�� ��� ��������� ��� �������.'; if($row_p["status"]=="wait") { echo '������� ������� �������� ��������������, � ������� 5 ����.';} echo '<br>�� ������ �������� <a href="javascript: void(0);" onclick="add_coment(\''.$rid.'\');">���� ����������� �� ���� �������</a>.</td>';
						echo '</tr>';
						echo '<tr align="center" height="30px"><th align="center" colspan="3" class="top">�������� �������</th></tr>';
						echo '<tr>';
							echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="ctask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="������ �����"></form></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td align="center" colspan="3"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="submit" class="submit" value="������� � ������ �������"></form></td>';
						echo '</tr>';
					}
				}
			}
		}else{
			echo '<tr height="30px"><th align="center" colspan="2" class="top">���������� �������</th></tr>';
			echo '<tr>';
				echo '<td align="center" colspan="2"><form action="'.$_SERVER["PHP_SELF"].'" method="GET" target="_blank"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="gotask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="������ ����������"></form></td>';
			echo '</tr>';
			echo '<tr height="30px"><th align="center" colspan="2" class="top">�������� �������</th></tr>';
			echo '<tr align="center">';
				echo '<td colspan="2" align="center"><div align="center" style="text-align:center; width:320px; margin:0 auto;">';
					echo '<form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="ctask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" style="float: left;" value="������ �����"></form>';
					//echo '<form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="dell"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" style="float: left;" value="���������� �� ����������"></form>';
					echo '<form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="submit" class="submit" style="float: left;" value="������� � ������ �������"></form>';
				echo '</div></td>';
			echo '</tr>';
		}

	echo '</table>';
	include('footer.php');
	exit();
}else{
	echo '<fieldset class="errorp">������! ������ ������� ���, ���� ��� �� �������!&nbsp;&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
	include('footer.php');
	exit();
}
?>