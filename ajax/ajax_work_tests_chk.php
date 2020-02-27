<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("CABINET")) DEFINE("CABINET", true);
sleep(0);

$json_result = array();
$json_result["result"] = "";
$json_result["status"] = "";
$json_result["message"] = "";

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/cabinet/cab_func.php");
	require(ROOT_DIR."/merchant/func_mysql.php");

	function myErrorHandler($errno, $errstr, $errfile, $errline, $json_result) {
		switch ($errno) {
			case(1): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "<br><br><span class='msg-error'>Fatal error[$errno]: $errstr in line $errline</span>")); break;
			case(2): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "<br><br><span class='msg-error'>Warning[$errno]: $errstr in line $errline</span>")); break;
			case(8): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "<br><br><span class='msg-error'>Notice[$errno]: $errstr in line $errline</span>")); break;
			default: $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "<br><br><span class='msg-error'>[$errno] $errstr in line $errline</span>")); break;
		}
		exit(json_encode_cp1251($json_result));
	}
	$set_error_handler = set_error_handler('myErrorHandler', E_ALL);

	$auth_user = auth_log_pass(2);

	if($auth_user["status"] == "FALSE") {
		if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
			$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Необходимо авторизоваться!</span>'));
			exit(json_encode_cp1251($json_result));
		}else{
			exit("Необходимо авторизоваться!");
		}

	}elseif($auth_user["status"] == "TRUE") {
		$user_id = $auth_user["user_id"];
		$username = $auth_user["user_log"];
		$money_user_rb = $auth_user["user_money_rb"];
		$money_user_rek = $auth_user["user_money_rek"];
		$my_referer_1 = $auth_user["user_referer_1"];
		$my_referer_2 = $auth_user["user_referer_2"];
		$my_referer_3 = $auth_user["user_referer_3"];
		$wmid_user = $auth_user["user_wmid"];
		$wmr_user = $auth_user["user_wm_purse"];
		$my_ban_date = $auth_user["user_ban_date"];

		$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? intval(limpiarez($_POST["id"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["op"])) ) ? limpiarez($_POST["op"]) : false;
		$answer_get[1] = ( isset($_POST["answer1"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", limpiarez($_POST["answer1"]))==1 ) ? limpiarez($_POST["answer1"]) : false;
		$answer_get[2] = ( isset($_POST["answer2"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", limpiarez($_POST["answer2"]))==1 ) ? limpiarez($_POST["answer2"]) : false;
		$answer_get[3] = ( isset($_POST["answer3"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", limpiarez($_POST["answer3"]))==1 ) ? limpiarez($_POST["answer3"]) : false;
		$answer_get[4] = ( isset($_POST["answer4"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", limpiarez($_POST["answer4"]))==1 ) ? limpiarez($_POST["answer4"]) : false;
		$answer_get[5] = ( isset($_POST["answer5"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", limpiarez($_POST["answer5"]))==1 ) ? limpiarez($_POST["answer5"]) : false;
		$token_get = ( isset($_POST["token"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", limpiarez($_POST["token"]))==1 ) ? limpiarez($_POST["token"]) : false;
		$my_lastiplog = getRealIP();
		$my_lastiplog_ex = explode(".", $my_lastiplog);
		$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";

		mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
		mysql_query("DELETE FROM `tb_ads_tests_visits` WHERE `time_next`<'".(time()-3*24*60*60)."'");

		if($option=="check") {
			$sql_test = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `status`='1' AND `balance`>=`cena_advs`");
			if(mysql_num_rows($sql_test)>0) {
				$row_test = mysql_fetch_assoc($sql_test);
				$revisit = $row_test["revisit"];
				$unic_ip_user = $row_test["unic_ip_user"];
				$questions = unserialize($row_test["questions"]);
				$answers = unserialize($row_test["answers"]);
				$cena_user = $row_test["cena_user"];
				$cena_advs = $row_test["cena_advs"];
				$rek_wmid = $row_test["wmid"];
				$rek_name = $row_test["username"];
				$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();

				$sql_ban = mysql_query("SELECT `id` FROM `tb_ads_tests_bl` WHERE `user_name`='$username' AND `rek_name`='$rek_name'");
				if(mysql_num_rows($sql_ban)>0) {
					$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Вы не можете выполнить этот тест.<br>Рекламодатель запретил вам выполнять его тесты (черный список).</span>'));
					exit(json_encode_cp1251($json_result));
				}

				$time_start = (isset($_SESSION["start_tests"]["$id"]) && intval($_SESSION["start_tests"]["$id"]) < time() ) ? intval($_SESSION["start_tests"]["$id"]) : false;

				if($revisit==4) {
					$time_next = (time() + 30*24*60*60);
				}elseif($revisit==3) {
					$time_next = (time() + 14*24*60*60);
				}elseif($revisit==2) {
					$time_next = (time() + 7*24*60*60);
				}elseif($revisit==1) {
					$time_next = (time() + 3*24*60*60);
				}else{
					$time_next = (time() + 1*24*60*60);
				}

				if($unic_ip_user==1) {
					$sql_visit = mysql_query("SELECT `user_name` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>'".time()."' AND (`user_name`='$username' OR `ip`='$my_lastiplog') ORDER BY `id` DESC LIMIT 1");
				}elseif($unic_ip_user==2) {
					$sql_visit = mysql_query("SELECT `user_name` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>'".time()."' AND (`user_name`='$username' OR `ip` LIKE '%$my_lastiplog_ex%') ORDER BY `id` DESC LIMIT 1");
				}else{
					$sql_visit = mysql_query("SELECT `user_name` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>'".time()."' AND `user_name`='$username' ORDER BY `id` DESC LIMIT 1");
				}
				if(mysql_num_rows($sql_visit)>0) {
					if($unic_ip_user==0) {
						$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Вы уже проходили этот тест за требуемый период!</span>'));
					}else{
						$row_visit = mysql_fetch_assoc($sql_visit);

						if(strtolower($row_visit["user_name"])==strtolower($username)) {
							$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Вы уже проходили этот тест за требуемый период!</span>'));
						}else{
							$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Тест на данный момент недоступен, попробуйте позже!</span>'));
						}
					}
					exit(json_encode_cp1251($json_result));

				}else{
					mysql_query("DELETE FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `user_name`='$username' AND `time_next`<'".time()."'");

					$_ERROR_ANSW = 0;
					for($i=1; $i<=count($questions); $i++) {
						$answer_tab[$i] = md5(mb_strtolower($answers[$i][1].$username.session_id().$_SERVER["HTTP_HOST"].$id."SecurityCheckAnsw"));
						if($answer_tab[$i]!=$answer_get[$i]) $_ERROR_ANSW++;
					}

					if($_ERROR_ANSW > 0) {
						mysql_query("UPDATE `tb_ads_tests` SET `bads`=`bads`+'1',`bads_out`=`bads_out`+'1' WHERE `id`='$id' AND `status`='1'");

						mysql_query("INSERT INTO `tb_ads_tests_visits` (`status`,`ident`,`time_start`,`time_end`,`time_next`,`user_name`,`user_id`,`ip`,`money`) 
						VALUES('-1','$id','$time_start','".time()."','$time_next','$username','$user_id','$my_lastiplog','0')");

						if(isset($_SESSION["start_tests"]["$id"])) unset($_SESSION["start_tests"]["$id"]);

						$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error"><span style="font-size:16px">Тест не пройден!</span><br>Вы ошиблись с ответами, попробуйте в другой раз...</span>'));
						exit(json_encode_cp1251($json_result));

					}elseif($time_start==false) {
						$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error"><span style="font-size:16px">Тест не пройден!</span><br>Нарушен порядок прохождения теста.</span>'));
						exit(json_encode_cp1251($json_result));

					}else{
						$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
						$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

						$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_reiting' AND `howmany`='1'");
						$tests_reiting = mysql_result($sql,0,0);


						$sql_user = mysql_query("SELECT `id`,`referer`,`referer2`,`referer3`,`referer4`,`referer5`,`ref_back` FROM `tb_users` WHERE `username`='$username'");
						if(mysql_num_rows($sql_user)>0) {
							$row_user = mysql_fetch_assoc($sql_user);
							$my_referer_1 = $row_user["referer"];
							$my_referer_2 = $row_user["referer2"];
							$my_referer_3 = $row_user["referer3"];
							//$my_referer_4 = $row_user["referer4"];
							//$my_referer_5 = $row_user["referer5"];
							$my_ref_back = trim($my_referer_1)!=false ? $row_user["ref_back"] : 0;
						}else{
							$my_referer_1 = false;
							$my_referer_2 = false;
							$my_referer_3 = false;
							//$my_referer_4 = false;
							//$my_referer_5 = false;
							$my_ref_back = 0;
						}

						if ($my_referer_1 != false) {
					$sql_ref_1 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");

					if (0 < mysql_num_rows($sql_ref_1)) {
						$reit_ref_1 = mysql_result($sql_ref_1, 0, 0);
						$sql_rang_1 = mysql_query("SELECT `test_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='" . floor($reit_ref_1) . "'");

						if (0 < mysql_num_rows($sql_rang_1)) {
							$ref_proc_1 = mysql_result($sql_rang_1, 0, 0);
						}
						else {
							$ref_proc_1 = 0;
						}
					}
					else {
						$my_ref_back = 0;
						$my_referer_1 = false;
						$ref_proc_1 = 0;
						$my_referer_2 = false;
						$ref_proc_2 = 0;
						$my_referer_3 = false;
						$ref_proc_3 = 0;
						//$my_referer_4 = false;
						//$ref_proc_4 = 0;
						//$my_referer_5 = false;
						//$ref_proc_5 = 0;
					}

					if ($my_referer_2 != false) {
						$sql_ref_2 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");

						if (0 < mysql_num_rows($sql_ref_2)) {
							$reit_ref_2 = mysql_result($sql_ref_2, 0, 0);
							$sql_rang_2 = mysql_query("SELECT `test_2` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_2' AND `r_do`>='" . floor($reit_ref_2) . "'");

							if (0 < mysql_num_rows($sql_rang_2)) {
								$ref_proc_2 = mysql_result($sql_rang_2, 0, 0);
							}
							else {
								$ref_proc_2 = 0;
							}
						}
						else {
							$my_referer_2 = false;
							$ref_proc_2 = 0;
							$my_referer_3 = false;
							$ref_proc_3 = 0;
							//$my_referer_4 = false;
						    //$ref_proc_4 = 0;
						    //$my_referer_5 = false;
						    //$ref_proc_5 = 0;
						}
						
						if ($my_referer_3 != false) {
						$sql_ref_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_3'");

						if (0 < mysql_num_rows($sql_ref_3)) {
							$reit_ref_3 = mysql_result($sql_ref_3, 0, 0);
							$sql_rang_3 = mysql_query("SELECT `test_3` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_2' AND `r_do`>='" . floor($reit_ref_3) . "'");

							if (0 < mysql_num_rows($sql_rang_3)) {
								$ref_proc_3 = mysql_result($sql_rang_3, 0, 0);
							}
							else {
								$ref_proc_3 = 0;
							}
						}
						else {
							//$my_referer_2 = false;
							//$ref_proc_2 = 0;
							$my_referer_3 = false;
							$ref_proc_3 = 0;
							//$my_referer_4 = false;
						    //$ref_proc_4 = 0;
						    //$my_referer_5 = false;
						    //$ref_proc_5 = 0;
						}
						
						
					
					}
					else {
						//$my_referer_2 = false;
						//$ref_proc_2 = 0;
						$my_referer_3 = false;
						$ref_proc_3 = 0;
							
					}
					}
					else {
						$my_referer_2 = false;
						$ref_proc_2 = 0;
						$my_referer_3 = false;
						$ref_proc_3 = 0;
							
					}
				}
				else {
					$my_ref_back = 0;
					$my_referer_1 = false;
					$ref_proc_1 = 0;
					$my_referer_2 = false;
					$ref_proc_2 = 0;
					$my_referer_3 = false;
					$ref_proc_3 = 0;
						
				}

						$_SumNac = ($cena_user * $tests_nacenka / 100);
						$my_ref_back = ( $_SumNac * ($ref_proc_1/100) * ($my_ref_back/100) );

						$add_money = number_format(($cena_user + $my_ref_back), 4, ".", "");

						$add_money_r_1 = number_format(($_SumNac * ($ref_proc_1/100) - $my_ref_back), 4, ".", "");
						$add_money_r_2 = number_format(($_SumNac * ($ref_proc_2/100)), 4, ".", "");
						$add_money_r_3 = number_format(($_SumNac * ($ref_proc_3/100)), 4, ".", "");
						//$add_money_r_4 = number_format($_SumNac * ($ref_proc_4/100), 4, ".", "");
						//$add_money_r_5 = number_format($_SumNac * ($ref_proc_5/100), 4, ".", "");
						
						$add_money_ref_1 = number_format(($_SumNac * ($ref_proc_1/100)), 4, ".", "");
						$add_money_ref_2 = number_format(($_SumNac * ($ref_proc_2/100)), 4, ".", "");
						$add_money_ref_3 = number_format(($_SumNac * ($ref_proc_3/100)), 4, ".", "");
						//$add_money_ref_4 = number_format(($_SumNac * ($ref_proc_4/100)), 4, ".", "");
						//$add_money_ref_5 = number_format(($_SumNac * ($ref_proc_5/100)), 4, ".", "");

						if(isset($_SESSION["start_tests"]["$id"])) unset($_SESSION["start_tests"]["$id"]);

						mysql_query("UPDATE `tb_ads_tests` SET `goods`=`goods`+'1',`goods_out`=`goods_out`+'1', `balance`=`balance`-'$cena_advs' WHERE `id`='$id'");

						mysql_query("INSERT INTO `tb_ads_tests_visits` (`status`,`ident`,`time_start`,`time_end`,`time_next`,`user_name`,`user_id`,`ip`,`money`) 
						VALUES('1','$id','$time_start','".time()."','$time_next','$username','$user_id','$my_lastiplog','$add_money')");

						mysql_query("UPDATE `tb_users` SET `money`=`money`+'$add_money',`reiting`=`reiting`+'$tests_reiting',`visits_tests`=`visits_tests`+'1',`money_tests`=`money_tests`+'$add_money' WHERE `username`='$username'");

						### РЕФ-ОТЧИСЛЕНИЯ ###
						if($my_referer_1 != false) {
							mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1',`money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'");
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							konkurs_clic_ref($my_referer_1, $add_money_ref_1);
					        konkurs_best_ref($username, $add_money_ref_1);

					$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

							if($my_referer_2 != false) {
								mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2',`money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'");
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_2', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								konkurs_clic_ref($my_referer_2, $add_money_ref_2);
					             konkurs_best_ref($username, $add_money_ref_2);
								
									$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_2' AND `type`='ref2'");
	                       if(mysql_num_rows($sql_stat_re)>0) {
	                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `type`='ref2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }else{
					           mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_2','ref2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				         }

								if($my_referer_3 != false) {
									mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3',`money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'");
									mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_2', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									konkurs_clic_ref($my_referer_3, $add_money_ref_3);
					                konkurs_best_ref($username, $add_money_ref_3);
									
									$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_3' AND `type`='ref3'");
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `type`='ref3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_3','ref3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }
								
								
								}
							}
						}
						### РЕФ-ОТЧИСЛЕНИЯ ###

						### START СБОР СТАТИСТИКИ ####
						stats_users($username, strtolower(DATE("D")), 'tests');
						### END СБОР СТАТИСТИКИ ######

						#### КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
						konkurs_ads_new($rek_wmid, $rek_name, $cena_advs);
						#### КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###

						#### ИНВЕСТ ПРОЕКТ ###
						stat_pay("tests_pay", $cena_advs);
						invest_stat($cena_advs, 1);
						#### ИНВЕСТ ПРОЕКТ ###
						
						if ((count($geo_targ) == 0) && (strtolower($rek_name) != strtolower($username)) && ($my_ban_date == 0)) {
					($sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='status'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$konk_test_status = mysql_result($sql, 0, 0);

					if ($konk_test_status == 1) {
						($sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_start'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_test_date_start = mysql_result($sql, 0, 0);
						($sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_end'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_test_date_end = mysql_result($sql, 0, 0);

						if ((time() <= $konk_test_date_end) && ($konk_test_date_start <= time())) {
							mysql_query("UPDATE `tb_users` SET `konkurs_test`=`konkurs_test`+'1' WHERE `username`='$username'") || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
				}

						### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
						if(count($geo_targ)==0 && strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'");
							$konk_complex_status = mysql_result($sql,0,0);

							if($konk_complex_status==1) {
								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'");
								$konk_complex_date_start = mysql_result($sql,0,0);

								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'");
								$konk_complex_date_end = mysql_result($sql,0,0);

								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_test'");
								$konk_complex_point = mysql_result($sql,0,0);

								if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
									mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)");
								}
							}
						}
						### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###

						### РЕФКОНКУРС КОМПЛЕКСНЫЙ ###
						if($my_referer_1 != false && count($geo_targ)==0 && strtolower($rek_name)!=strtolower($username)) {
							$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_reiting_konk' AND `howmany`='1'");
							$add_ball_kon = number_format(mysql_result($sql,0,0), 0, ".", "");

							$sql_rk_1 = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_rk_1)>0) {
								$row_rk_1 = mysql_fetch_array($sql_rk_1);

								$sql_srk_1 = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `ident`='".$row_rk_1["id"]."' AND `type`='".$row_rk_1["type_kon"]."'");
								if(mysql_num_rows($sql_srk_1) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `ident`='".$row_rk_1["id"]."' AND `type`='".$row_rk_1["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`ident`,`type`,`kolvo`) 
									VALUES('$username','".$row_rk_1["id"]."','".$row_rk_1["type_kon"]."','$add_ball_kon')") or die(mysql_error());
								}
							}
							
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					//if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
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
					//}
				//}
					### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					
							if($my_referer_2 != false) {
								$sql_rk_2 = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_rk_2)>0) {
									$row_rk_2 = mysql_fetch_array($sql_rk_2);

									$sql_srk_2 = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_rk_2["id"]."' AND `type`='".$row_rk_2["type_kon"]."'");
									if(mysql_num_rows($sql_srk_2) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_rk_2["id"]."' AND `type`='".$row_rk_2["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','2','".$row_rk_2["id"]."','".$row_rk_2["type_kon"]."','$add_ball_kon')") or die(mysql_error());
									}
								}
								
								### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###
						//if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
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
						//}
					//}
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###

								if($my_referer_3 != false) {
									$sql_rk_3 = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_rk_3)>0) {
										$row_rk_3 = mysql_fetch_array($sql_rk_3);

										$sql_srk_3 = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_rk_3["id"]."' AND `type`='".$row_rk_3["type_kon"]."'");
										if(mysql_num_rows($sql_srk_3) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_rk_3["id"]."' AND `type`='".$row_rk_3["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$username','3','".$row_rk_3["id"]."','".$row_rk_3["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								//}
							//}
						//}
						### РЕФКОНКУРС КОМПЛЕКСНЫЙ ###
						
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							//if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
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
			}

						$json_result = array("result" => "OK", "message" => iconv("CP1251", "UTF-8", '<br><br><div align="center"><img src="/images/bank.gif" alt="" title="" align="absmiddle" border="0" /><br><span style="color:#008B00; font-size:12px; line-height:1.6em"><span style="font-size:16px">Тест выполнен на отлично!</span><br>Плата за выполнение ('.$add_money.' руб.) зачислена.</span></div>'));
						exit(json_encode_cp1251($json_result));
					}
				}
			}else{
				$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Тест на данный момент недоступен, попробуйте позже!</span>'));
				exit(json_encode_cp1251($json_result));
			}

		}else{
			if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
				$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">ERROR! NO OPTION!</span>'));
				exit(json_encode_cp1251($json_result));
			}else{
				exit('<br><br><span class="msg-error">ERROR! NO OPTION!</span>');
			}
		}
	}else{
		if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
			$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Необходимо авторизоваться!</span>'));
			exit(json_encode_cp1251($json_result));
		}else{
			exit('<br><br><span class="msg-error">Необходимо авторизоваться!</span>');
		}
	}
}else{
	if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
		$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", '<br><br><span class="msg-error">Ошибка! Не корректный запрос!</span>'));
		exit(json_encode_cp1251($json_result));
	}else{
		exit('<br><br><span class="msg-error">Ошибка! Не корректный запрос!</span>');
	}
}

?>