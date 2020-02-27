<?php
@session_start();
error_reporting (E_ALL);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	
	<link rel="stylesheet" type="text/css" href="/style/frame.css">
	<title><?php $host=$_SERVER["HTTP_HOST"]; echo strtoupper(str_replace("www."," ",$host));?> | Просмотр рекламных писем</title>

	<?php
	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

		$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(strtolower(stripslashes(trim($_SESSION["userLog"])))) : false;
		$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
		$hash_answ = ( isset($_GET["hash"]) && preg_match("/^[a-zA-Z0-9\$\!\/]{32,48}$/i", trim($_GET["hash"]))==1 ) ? limpiar(trim($_GET["hash"])) : false;
		$laip = getRealIP();
		$suc_ok = false;

		if($id!=false && $hash_answ!=false) {
			$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1'");
			if(mysql_num_rows($sql_id)>0) {

				$row_id = mysql_fetch_array($sql_id);

				$rek_name = $row_id["username"];
				$title = $row_id["title"];
				$url = $row_id["url"];
				$description = $row_id["description"];
				$question = $row_id["question"];
				$answer_t = $row_id["answer_t"];
				$answer_f_1 = $row_id["answer_f_1"];
				$answer_f_2 = $row_id["answer_f_2"];
				$plan = $row_id["plan"];
				$tarif = $row_id["tarif"];
				$color = $row_id["color"];
				$active = $row_id["active"];
				$gotosite = $row_id["gotosite"];
				$mailsre = $row_id["mailsre"];
				$totals = $row_id["totals"];

				if($mailsre==0) {
					$next_time = (time() + 30*24*60*60);
				}else{
					$next_time = time();
				}

				$sql_v = mysql_query("SELECT `id` FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `ident`='$id' AND `date`>=UNIX_TIMESTAMP()-24*60*60 ORDER BY `id` DESC") or die(mysql_error());
				if(mysql_num_rows($sql_v)<1) {

					if($hash_answ==false | $hash_answ!=md5("$answer_t+2205+$username")) {
						mysql_query("INSERT INTO `tb_ads_mails_visits` (`username`,`ident`,`type`,`time`,`date`,`ip`, `money`,`test`) 
						VALUES('$username','$id','1','".time()."','".time()."','$laip','0','Неверно введен ответ на контрольный вопрос')") or die(mysql_error());

						echo "<head><body>";
						echo '<b style="color: #FF0000;">Ошибка! Вы указали неверный ответ! Письмо Вам не засчитано! Сегодня письмо не будет доступно Вам для просмотра!</b>';
						exit("</body></html>");
					}

					if($tarif==1) {
						$timer=60;
					}elseif($tarif==2) {
						$timer=40;
					}elseif($tarif==3) {
						$timer=20;
					}else{
						$timer=20;
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

			$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1' AND `totals`>'0'");
			if(mysql_num_rows($sql_id)>0) {
				$row_id = mysql_fetch_array($sql_id);

				$title = $row_id["title"];
				$url = $row_id["url"];
				$description = $row_id["description"];
				$question = $row_id["question"];
				$answer_t = $row_id["answer_t"];
				$answer_f_1 = $row_id["answer_f_1"];
				$answer_f_2 = $row_id["answer_f_2"];
				$plan = $row_id["plan"];
				$tarif = $row_id["tarif"];
				$color = $row_id["color"];
				$active = $row_id["active"];
				$gotosite = $row_id["gotosite"];
				$mailsre = $row_id["mailsre"];
				$totals = $row_id["totals"];

				if($mailsre==0) {
					$next_time = "2147483647";
				}else{
					$next_time = time();
				}

				if($totals>0) {
					$sql_price = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='".$tarif."'");
					$cena_mails = mysql_result($sql_price,0,0);

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
					$cena_mails_color = mysql_result($sql,0,0);

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
					$cena_mails_active = mysql_result($sql,0,0);

					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
					$cena_mails_gotosite = mysql_result($sql,0,0);


					$add_money = $cena_mails + ($color * $cena_mails_color) + ($active * $cena_mails_active) + ($gotosite * $cena_mails_gotosite) ;

					$sql_ref = mysql_query("SELECT `referer`,`referer2`,`referer3`,`referer4`,`referer5`,`ban_date` FROM `tb_users` WHERE `username`='$username'");
					$row_r = mysql_fetch_array($sql_ref);
						$my_referer_1 = $row_r["referer"];
						$my_referer_2 = $row_r["referer2"];
						$my_referer_3 = $row_r["referer3"];
						$my_referer_4 = $row_r["referer4"];
						$my_referer_5= $row_r["referer5"];
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
							
							if($my_referer_4 != "" ) {
							$sql_r_4 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_4'");
							$row_r_4 = mysql_fetch_array($sql_r_4);
							$reiting_4 = $row_r_4["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_4' AND `r_do`>='".floor($reiting_4)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_array($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_array($sql_rang);
							}
							$cena_mails_ref_4 = $row_rang["m_4"];
							$add_money_r_4 = ($add_money*$cena_mails_ref_4/100);

							if($my_referer_5 != "" ) {
								$sql_r_5 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_5'");
								$row_r_5 = mysql_fetch_array($sql_r_5);
								$reiting_5 = $row_r_5["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_5' AND `r_do`>='".floor($reiting_5)."'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$cena_mails_ref_5 = $row_rang["m_5"];
								$add_money_r_5 = ($add_money*$cena_mails_ref_5/100);
							}else{
								$add_money_r_5=0;
							}
						}else{
							$add_money_r_2=0;
							$add_money_r_3=0;
							$add_money_r_4=0;
							$add_money_r_5=0;
						}
					}else{
						$add_money_r_1=0;
						$add_money_r_2=0;
						$add_money_r_3=0;
						$add_money_r_4=0;
						$add_money_r_5=0;
					}
				}
			}

					$sql_v = mysql_query("SELECT * FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `ident`='$id' ORDER BY `id` DESC") or die(mysql_error());
					if(mysql_num_rows($sql_v)>0) {
						$rowv = mysql_fetch_array($sql_v);

						if(($rowv["date"]+24*60*60) < time()) {
							mysql_query("UPDATE `tb_ads_mails_visits` SET `time`='".time()."', `date`='$next_time', `ip`='$laip', `money`=`money`+'$add_money', `test`='Рефотчисления - $add_money_r_1; $add_money_r_2; $add_money_r_3;' WHERE `username`='$username' AND `type`='1' AND `ident`='$id'") or die(mysql_error());
						}else{
							echo '<b style="color: #FF0000;">Ошибка! Просмотр не засчитан, Вы уже просматривали это письмо в течении 24 часов!</b>';
							exit();
						}
					}else{
						mysql_query("INSERT INTO `tb_ads_mails_visits` (`username`,`ident`,`type`,`time`,`date`,`ip`, `money`,`test`) 
						VALUES('$username','$id','1','".time()."','$next_time','$laip','$add_money','Рефотчисления - $add_money_r_1; $add_money_r_2; $add_money_r_3;')") or die(mysql_error());

					}
                    
					mysql_query("UPDATE `tb_ads_mails` SET `totals`=`totals`-'1', `members`=`members`+'1', `outside`=`outside`+'1' WHERE `id`='$id' AND `status`='1'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_mails', `visits_m`=`visits_m`+'1', `money_m`=`money_m`+'$add_money', `money`=`money`+'$add_money' WHERE `username`='$username'") or die(mysql_error());

					### START СБОР СТАТИСТИКИ ####
					stats_users($username, strtolower(DATE("D")), 'mails');
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

						if($my_referer_2 != "" ) {
							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
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

							if($my_referer_3 != "" ) {
								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
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
								
							if($my_referer_4 != false) {
									mysql_query("UPDATE `tb_users` SET `referalvisits4`=`referalvisits4`+'1', `refmoney4`=`refmoney4`+'$add_money_r_4', `money`=`money`+'$add_money_r_4' WHERE `username`='$my_referer_4'") or die(mysql_error());
									$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_4' AND `type`='ref4'");
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_4', `sum_week`=`sum_week`+'$add_money_r_4', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_4' WHERE `username`='$my_referer_4' AND `type`='ref4'") or die(mysql_error());
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_4','ref4','$add_money_r_4','$add_money_r_4','$add_money_r_4')") or die(mysql_error());
				            }

									if($my_referer_5 != false) {
										mysql_query("UPDATE `tb_users` SET `referalvisits5`=`referalvisits5`+'1', `refmoney5`=`refmoney5`+'$add_money_r_5', `money`=`money`+'$add_money_r_5' WHERE `username`='$my_referer_5'") or die(mysql_error());
										$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_5' AND `type`='ref5'");
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_5', `sum_week`=`sum_week`+'$add_money_r_5', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_5' WHERE `username`='$my_referer_5' AND `type`='ref5'") or die(mysql_error());
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_5','ref5','$add_money_r_5','$add_money_r_5','$add_money_r_5')") or die(mysql_error());
				            }
									}
								}
							}
						}
					}else{
						$add_money_r_1=0;
						$add_money_r_2=0;
						$add_money_r_3=0;
						$add_money_r_4=0;
						$add_money_r_5=0;
					}


					$suc_ok=true;

					if($gotosite==1) {echo '<script type="text/javascript"> top.document.location.href = "'.$url.'"; </script>';}
				}else{
					unset($_SESSION["endtmr"]);
					mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `id`='$id' AND `status`='1' AND `totals`<'1'") or die(mysql_error());
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

			mysql_query("INSERT INTO `tb_ads_mails_visits` (`username`,`ident`,`type`,`time`,`date`,`ip`, `money`,`test`) 
			VALUES('$username','$id','1','".time()."','$next_time','$laip','0','Неверный ввод капчи')") or die(mysql_error());

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
			echo '<td width="460" height="50" style="background: #F9F9F9; padding-left: 10px;">';
				if($suc_ok==true) {
					echo '<span style="color: #289639; font-family: Verdana; font-size: 14px;">Спасибо. Просмотр засчитан!</span><br /><span style="color: #444; font-family: Verdana; font-size: 14px;">Вам зачислено</span><span style="color: #289639; font-family: Verdana; font-size: 14px;"><b> '.$add_money.' </b></span><span style="color: #444; font-family: Verdana; font-size: 14px;">руб.</span>';
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
			echo '<td height="50" style="background: #F9F9F9;"><span style="color: #444; font-family: Verdana; font-size: 13px;">Описание: '.$title.'<br>Адрес сайта:</b> <a href="'.$url.'" target="_blank" title="Перейти на сайт">'.$url.'</a></span></td>';
		echo '</tr>';
		echo '<tr><td colspan="2" height="99%"><iframe src="'.$url.'" height="100%" width="100%" hspace="0" vspace="0" frameborder="3" scrolling="auto"></iframe></td></tr>';
		echo '</table>';
	}
}else{
	exit("<b style=\"color: #FF0000;\">Для получения оплаты необходимо авторизоваться!</b>");
}
?>

</body>
</html>