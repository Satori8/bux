<?php
@session_start();
error_reporting (E_ALL);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta name="Author" content="supreme-garden.ru">
	<link rel="stylesheet" type="text/css" href="/style/frame.css">
	<title><?php $host=$_SERVER["HTTP_HOST"]; echo strtoupper(str_replace("www."," ",$host));?> | Просмотр рекламных писем</title>

	<?php
	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date_end`='".time()."' WHERE `status`>'0'  AND (`totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());

		$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", htmlspecialchars(stripslashes(trim($_SESSION["userPas"]))))) ? htmlspecialchars(stripslashes(trim($_SESSION["userPas"]))) : false;
		$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
		$hash_answ = ( isset($_GET["hash"]) && preg_match("/^[a-zA-Z0-9\$\!\/]{32,48}$/i", trim($_GET["hash"]))==1 ) ? limpiar(trim($_GET["hash"])) : false;
		$laip = getRealIP();
		$suc_ok = false;

		if($id!=false && $hash_answ!=false) {
			$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1' AND (`totals`>'0' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(mysql_error());
			if(mysql_num_rows($sql_id)>0) {
				$rowid = mysql_fetch_array($sql_id);

				$rek_name = $rowid["username"];
				$title = $rowid["title"];
				$url = $rowid["url"];
				$description = $rowid["description"];
				$question = $rowid["question"];
				$answer_t = $rowid["answer_t"];
				$answer_f_1 = $rowid["answer_f_1"];
				$answer_f_2 = $rowid["answer_f_2"];
				$timer = $rowid["timer"];
				$plan = $rowid["plan"];
				$active = $rowid["active"];
				$gotosite = $rowid["gotosite"];
				$revisit = $rowid["revisit"];
				$totals = $rowid["totals"];

				if($revisit==0) {
					$time_next = (time() + 1*24*60*60);
				}elseif($revisit==1) {
					$time_next = (time() + 2*24*60*60);
				}elseif($revisit==2) {
					$time_next = (time() + 30*24*60*60);
				}else{
					$time_next = (time() + 1*24*60*60);
				}

				$sql_v = mysql_query("SELECT `id` FROM `tb_ads_mails_visits` WHERE `user_name`='$username' AND `ident`='$id' AND `time_next`>='".time()."' ORDER BY `id` DESC") or die(mysql_error());
				if(mysql_num_rows($sql_v)<1) {

					if($hash_answ==false | $hash_answ!=md5($answer_t."2205".strtolower($username))) {
						mysql_query("INSERT INTO `tb_ads_mails_visits` (`status`,`ident`,`time`,`time_next`,`user_name`,`ip`, `money`) 
						VALUES('-1','$id','".time()."','$time_next','$username','$laip','0')") or die(mysql_error());

						echo "<head><body>";
						echo '<b style="color: #FF0000;">Ошибка! Вы указали неверный ответ! Письмо Вам не засчитано! Сегодня письмо не будет доступно Вам для просмотра!</b>';
						exit("</body></html>");
					}

					if(count($_POST)<1) {$_SESSION["endtmr"] = ($timer + time());}
				}else{
					echo "<head><body>";
					echo '<b style="color: #FF0000;">Ошибка! Вы уже просматривали это письмо в течении 24 часов!</b>';
					exit("</body></html>");
				}
			}else{
				echo "<head><body>";
				echo '<script type="text/javascript">location.replace("/");</script><noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
				exit("</body></html>");
			}
		}else{
			echo "<head><body>";
			echo '<script type="text/javascript">location.replace("/");</script><noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
			exit("</body></html>");
		}
		?><script type="text/javascript" language="JavaScript">
		var counter=1+parseInt(<?php echo $timer;?>);
		var flag=0;

		function do_count(){
			if(typeof flag == "undefined") {
				return flag;
			}
			if(flag==0) {
				counter--;
				document.getElementById('begin').innerHTML='';
				document.getElementById('load').innerHTML='';
			}
			if(flag==1) {
				document.getElementById('begin').innerHTML='<br>&nbsp;[Если остановился таймер, нажмите ЗДЕСЬ]';
			}
			if(counter>=0) {
				document.getElementById('tmr').innerHTML='&nbsp;<span style="color:#555555;">Пожалуйста ждите</span>&nbsp;&nbsp;<span style="color:#289639; font-size: 17px;">'+counter+'</span>&nbsp;&nbsp;<span style="color:#555555;">секунд</span>&nbsp;...';
				document.getElementById('load').innerHTML='';
				setTimeout("do_count()",1000);
			}
			if(counter<0){
				document.getElementById('tmr').innerHTML='';
				document.getElementById('load').innerHTML='';
				document.getElementById('begin').innerHTML='';
				document.getElementById("capcha").style.display='block';
			}
		}
		window.onblur = new Function('this.flag = <?=$active;?>;');
		window.onfocus = new Function('this.flag = 0;');
		window.parent.document.title="<?php echo strtoupper(str_replace("www."," ",$host));?> | Просмотр рекламного письма - <?php echo $title;?>";
		</script><?php
	}
	?>
</head>

<?php
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	echo '<body onselectstart="return false" oncontextmenu="return false" onLoad="do_count();">';
}else{
	echo '<body>';
}

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {

	if(isset($_SESSION["endtmr"]) && intval($_SESSION["endtmr"]) <= time()) {

		if(isset($_POST["id"]) && isset($_POST["answer"]) && isset($_POST["num"]) && isset($_SESSION["captcha_keystring"]) && md5(strtoupper(limpiar($_POST["answer"])))==md5(strtoupper($_SESSION["captcha_keystring"])) && strtoupper(limpiar($_POST["num"]))==strtoupper(md5($_SESSION["codes"]))) {
			unset($_SESSION["captcha_keystring"]);
			unset($_SESSION["hash1"]);
			unset($_SESSION["hash2"]);
			unset($_SESSION["codes"]);
			unset($_SESSION["endtmr"]);
			
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_mails' AND `howmany`='1'");
                    $reit_mails = mysql_result($sql,0,0);

			$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1' AND (`totals`>'0' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(mysql_error());
			if(mysql_num_rows($sql_id)>0) {
				$rowid = mysql_fetch_array($sql_id);

				$title = $rowid["title"];
				$url = $rowid["url"];
				$description = $rowid["description"];
				$question = $rowid["question"];
				$answer_t = $rowid["answer_t"];
				$answer_f_1 = $rowid["answer_f_1"];
				$answer_f_2 = $rowid["answer_f_2"];
				$timer = $rowid["timer"];
				$plan = $rowid["plan"];
				$active = $rowid["active"];
				$gotosite = $rowid["gotosite"];
				$revisit = $rowid["revisit"];
				$totals = $rowid["totals"];

				if($revisit==0) {
					$time_next = (time() + 1*24*60*60);
				}elseif($revisit==1) {
					$time_next = (time() + 2*24*60*60);
				}elseif($revisit==2) {
					$time_next = (time() + 30*24*60*60);
				}else{
					$time_next = (time() + 1*24*60*60);
				}

				if($totals>0) {
				    
					
					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_hit' AND `howmany`='1'");
					$mails_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_nacenka' AND `howmany`='1'");
					$mails_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_active' AND `howmany`='1'");
					$mails_cena_active = number_format(mysql_result($sql,0,0), 4, ".", "");

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_timer_ot' AND `howmany`='1'");
					$mails_timer_ot = number_format(mysql_result($sql,0,0), 0, ".", "");
					
					//$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
					//$cena_mails_gotosite = mysql_result($sql,0,0);

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='mails_cena_timer' AND `howmany`='1'");
					$mails_cena_timer = number_format(mysql_result($sql,0,0), 4, ".", "");
					
					//$add_money = $mails_cena_hit + ($active * $mails_cena_active) + ($gotosite * $cena_mails_gotosite) ;
					$add_money = $cena_click = ($mails_cena_hit + $active * $mails_cena_active + abs($timer-$mails_timer_ot) * $mails_cena_timer);

					$sql_ref = mysql_query("SELECT `referer`,`referer2`,`referer3`,`referer4`,`referer5`,`ban_date` FROM `tb_users` WHERE `username`='$username'");
					$row_r = mysql_fetch_array($sql_ref);
						 
			            $my_referer_1 = $row_r["referer"];
						$my_referer_2 = $row_r["referer2"];
						$my_referer_3 = $row_r["referer3"];
						
						$my_ban_date = $row_r["ban_date"];
			 

					if($my_referer_1 != "" ) {
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
						$cena_mails_ref_1 = $row_rang["m_1"];
						$add_money_r_1 = ($add_money*$cena_mails_ref_1/100);

						if($my_referer_2 != "" ) {
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
							$cena_mails_ref_2 = $row_rang["m_2"];
							$add_money_r_2 = ($add_money*$cena_mails_ref_2/100);
							
							if($my_referer_3 != "" ) {
							$sql_r_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_3'");
							$row_r_3 = mysql_fetch_array($sql_r_3);
							$reiting_3 = $row_r_3["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_3' AND `r_do`>='".floor($reiting_3)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_array($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_array($sql_rang);
							}
							$cena_mails_ref_3 = $row_rang["m_3"];
							$add_money_r_3 = ($add_money*$cena_mails_ref_3/100);
							}else{
								$add_money_r_3=0;
							}
						}else{
							$add_money_r_2=0;
							$add_money_r_3=0;
						}
					}else{
						$add_money_r_1=0;
						$add_money_r_2=0;
						$add_money_r_3=0;
						
					}
				//}
			//}


					$sql_v = mysql_query("SELECT * FROM `tb_ads_mails_visits` WHERE `user_name`='$username' AND `ident`='$id' ORDER BY `id` DESC") or die(mysql_error());
					if(mysql_num_rows($sql_v)>0) {
						$rowv = mysql_fetch_array($sql_v);

						if($rowv["time_next"] > time()) {
							echo '<b style="color: #FF0000;">Ошибка! Просмотр не засчитан, Вы уже просматривали это письмо в течении 24 часов!</b>';
							exit();
						}else{
							mysql_query("UPDATE `tb_ads_mails_visits` SET `time`='".time()."',`time_next`='$time_next', `ip`='$laip', `money`='$cena_click' WHERE `user_name`='$username' AND `ident`='$id'") or die(mysql_error());
						}
					}else{
						mysql_query("INSERT INTO `tb_ads_mails_visits` (`status`,`ident`,`time`,`time_next`,`user_name`,`ip`, `money`) 
						VALUES('1','$id','".time()."','$time_next','$username','$laip','$cena_click')") or die(mysql_error());
					}
                    
					mysql_query("UPDATE `tb_ads_mails` SET `totals`=`totals`-'1', `members`=`members`+'1', `outside`=`outside`+'1' WHERE `id`='$id' AND `status`='1'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_mails', `visits_m`=`visits_m`+'1', `money_m`=`money_m`+'$cena_click', `money`=`money`+'$cena_click' WHERE `username`='$username'") or die(mysql_error());

					### START СБОР СТАТИСТИКИ ####
					$DAY_S = strtolower(DATE("D"));
					stats_users($username, $DAY_S, 'mails', $cena_click);
					### END СБОР СТАТИСТИКИ ######

					### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
					if(strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
						$konk_complex_status = mysql_result($sql,0,0);

						if($konk_complex_status==1) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
							$konk_complex_date_start = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
							$konk_complex_date_end = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_mail'") or die(mysql_error());
							$konk_complex_point = mysql_result($sql,0,0);

							if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
								mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die(mysql_error());
							}
						}
					}
					### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###

					if($my_referer_1 != "" ) {
						mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$username'") or die(mysql_error());
						konkurs_clic_ref($my_referer_1, $add_money_r_1);
					 konkurs_best_ref($username, $add_money_r_1);
						$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(mysql_error());
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(mysql_error());
				 }

						### РЕФКОНКУРС КОМПЛЕКСНЫЙ I ур. ###
						if(strtolower($rek_name)!=strtolower($username)) {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'2' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','2')") or die(mysql_error());
								}
							}
						}
						### РЕФКОНКУРС КОМПЛЕКСНЫЙ I ур. ###
						
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					if(strtolower($rek_name)!=strtolower($username)) {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='6' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							//if($row_t["limit_kon_sum"]!=0 && ($my_visits+$my_visits_bs+$add_money_r_1)==$row_t["limit_kon_sum"]) {
							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_1' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
				//}
					### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###

						if($my_referer_2 != "" ) {
							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());
							konkurs_clic_ref($my_referer_2, $add_money_r_2);
					        konkurs_best_ref($username, $add_money_r_2);
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_2' AND `type`='ref2'");
	                       if(mysql_num_rows($sql_stat_re)>0) {
	                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `type`='ref2'") or die(mysql_error());
				            }else{
					           mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_2','ref2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(mysql_error());
				         }

							### РЕФКОНКУРС КОМПЛЕКСНЫЙ II ур. ###
							if(strtolower($rek_name)!=strtolower($username)) {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'2' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','2')") or die(mysql_error());
									}
								}
							}
							### РЕФКОНКУРС КОМПЛЕКСНЫЙ II ур. ###
							
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###
						if(strtolower($rek_name)!=strtolower($username)) {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_2)==$row_t["limit_kon_sum"]) {
								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_2' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
					//}
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###

							if($my_referer_3 != "" ) {
								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());
								konkurs_clic_ref($my_referer_3, $add_money_r_3);
					            konkurs_best_ref($username, $add_money_r_3);
								$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_3' AND `type`='ref3'");
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `type`='ref3'") or die(mysql_error());
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_3','ref3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(mysql_error());
				            }

								### РЕФКОНКУРС КОМПЛЕКСНЫЙ III ур. ###
								if(strtolower($rek_name)!=strtolower($username)) {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
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
								### РЕФКОНКУРС КОМПЛЕКСНЫЙ III ур. ###
								### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							if(strtolower($rek_name)!=strtolower($username)) {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_3' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
								
							
							}
						}
					}else{
						$add_money_r_1=0;
						$add_money_r_2=0;
						$add_money_r_3=0;
						//$add_money_r_4=0;
						//$add_money_r_5=0;
					}
					
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
					if($my_referer_1 != false) {
						$sql_r_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
						if(mysql_num_rows($sql_r_1)>0) {
							$row_r_1 = mysql_fetch_assoc($sql_r_1);
							$user_id_ref_1 = $row_r_1["id"];
							$money_ref_1 = $row_r_1["money_rb"];
							
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='6' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `ident`='".$row_r_b_1["id"]."' AND `type`='6' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if((number_format($row_r_b_stat_1["stat_info"],2,".","`"))==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$username','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Зачислено','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $username за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$username','Система','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$username','$add_money_r_1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
				}
					}else{
						$add_money_r_1=0;
						$add_money_r_2=0;
						$add_money_r_3=0;
						//$add_money_r_4=0;
						//$add_money_r_5=0;
					}
					
					$suc_ok = true;
					if($gotosite==1) {
						echo '<script type="text/javascript"> setTimeout(\'top.document.location.href = "'.str_replace("&amp;", "&", $url).'"\', 2000); </script>';
						echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.str_replace("&amp;", "&", $url).'"></noscript>';
					}
				}else{
					unset($_SESSION["endtmr"]);
					mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `id`='$id' AND `status`='1'  AND ( `totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());
					echo '<b style="color: #FF0000;">Ошибка! Такой ссылки не существует!</b>';
					exit("</body></html>");
				}
			}else{
				unset($_SESSION["endtmr"]);
				echo '<b style="color: #FF0000;">Ошибка! Такой ссылки не существует!</b>';
				exit("</body></html>");
			}
		}else{
			unset($_SESSION["captcha_keystring"]);
			unset($_SESSION["hash1"]);
			unset($_SESSION["hash2"]);
			unset($_SESSION["codes"]);
			unset($_SESSION["endtmr"]);

			mysql_query("INSERT INTO `tb_ads_mails_visits` (`status`,`ident`,`time_next`,`user_name`,`ip`, `money`) 
			VALUES('-1','$id','$time_next','$username','$laip','0')") or die(mysql_error());

			echo '<b style="color: #FF0000;">Ошибка! Просмотр не засчитан, будьте внимательней при выборе!</b>';
			exit("</body></html>");
		}
	}


	if(isset($_GET["id"]) && isset($_GET["hash"]) && preg_match("/^[a-zA-Z0-9\$\!\/]{32,48}$/i", trim($_GET["hash"]))==1) {
		function get_codes1($num,$len) {
			for($y=0;$y<$num;$y++) {
				$codes[]=substr(strtoupper(md5(mt_rand(1000,1000000000))),0,$len);
			}
			return $codes;
		}
		function get_codes2($num) {
			for($y=0;$y<$num;$y++) {
				$codes[]=mt_rand(123456789,999999999999999999);
			}
			return $codes;
		}

		$codes1 = get_codes1(5,3);
		$codes2 = get_codes2(5);

		$rnd_nomer = mt_rand(0,count($codes1)-1);

		unset($_SESSION["captcha_keystring"]);
		unset($_SESSION["codes"]);

		$_SESSION["captcha_keystring"] = $codes1[$rnd_nomer];
		$_SESSION["codes"] = $codes2[$rnd_nomer];
		$endtmr = (isset($_SESSION["endtmr"])) ? $_SESSION["endtmr"] : false;
		$_SESSION["hash2"] = md5(limpiar($id.$plan.$timer.$username.$endtmr));

		echo '<table width="100%" height="100%" cellspacing="0" cellpadding="0" style="margin: 0 auto; padding:0 auto;">';
		echo '<tr>';
			echo '<td width="543" height="65" style="background: #F9F9F9; padding-left: 10px;">';
				if($suc_ok==true) {
					echo '<span style="color: #289639; font-family: Verdana; font-size: 14px;">Спасибо. Просмотр засчитан!</span><br /><span style="color: #444; font-family: Verdana; font-size: 14px;">Вам зачислено</span><span style="color: #289639; font-family: Verdana; font-size: 14px;"><b> '.$cena_click.' </b></span><span style="color: #444; font-family: Verdana; font-size: 14px;">руб.</span>';
				}else{
					echo '<span id="tmr" style="font-weight: normal; font-family: Verdana; font-size: 14px;"></span><span id="load" style="color: #444; font-weight: normal;">Загрузка сайта, ждите&nbsp;...</span><span id="begin" style="color: #C80000;"></span>';
				}
				echo '<table border="0" cellpadding="0" cellspacing="0" id="capcha" style="background: #F9F9F9; display: none;">';
				echo '<tr><td rowspan="2" style="padding-right: 15px;"><img src="/cap/?'.session_name().'='.session_id().'" id="captcha-image" align="middle" alt="" />&nbsp;<a href="javascript:void(0);" onclick="document.getElementById(\'captcha-image\').src = \'/cap/?'.session_name().'='.session_id().')?rid=\' + Math.random();"><img src="/img/refresh.png" alt="" border="0" align="middle" title="Обновить изображение" /></a><td colspan="5"><span style="color: #444; font-family: Verdana; font-size: 13px;">Выберите правильный вариант:</span></td></tr>';
				echo '<tr>';
					$k_num=0;
					foreach($codes1 as $c):
						echo '<td>';
						echo '<form action="" method="POST" target="_self">';
						echo '<input type="hidden" name="id" value="'.$id.'">';
						echo '<input type="hidden" name="cap" value="0">';
						echo '<input type="hidden" name="answer" value="'.$c.'">';
						echo '<input type="hidden" name="num" value="'.md5($codes2["$k_num"]).'">';
						echo '<input type="submit" value="'.$c.'" class="submit">';
						echo '</form>';
						echo '</td>';
						$k_num++;
					endforeach;
				echo '</tr></table>';
			echo '</td>';
			echo '<td height="65" style="background: #F9F9F9;"><span style="color: #444; font-family: Verdana; font-size: 13px;">Описание: '.$title.'<br>Адрес сайта:</b> <a href="'.$url.'" target="_blank" title="Перейти на сайт">'.$url.'</a></span></td>';
		echo '</tr>';
		echo '<tr><td colspan="2" height="99%"><iframe src="'.$url.'" height="100%" width="100%" hspace="0" vspace="0" frameborder="3" scrolling="auto"></iframe></td></tr>';
		echo '<tr>';
		    echo '<td colspan="2" align="center" style="bottom: 0px; height: 60px; width: 468px; background: rgba(76, 86, 202, 0.56); margin: 10px; margin-bottom: 1px; line-height: 40px; text-align: center; position: fixed; border: 1px solid #9E9A9A; left: 50%; margin-left: -250px;">';
		        include('includes/banner468x60.php');
		    echo '</td>';
		echo '</tr>';
		echo '</table>';
	}
}else{
	exit("<b style=\"color: #FF0000;\">Для получения оплаты необходимо авторизоваться!</b>");
}


?>

</body>
</html>