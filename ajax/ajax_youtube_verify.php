<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "", "message" => "");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
//sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
	$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
	$message_text = false;
	$errfile = str_replace(ROOT_DIR, "", $errfile);
	switch($errno) {
		case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
		case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
		case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
		default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
	}
	$message_text = '<div class="block-error">'.$message_text.'</div>';
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");

		mysql_query("UPDATE `tb_ads_youtube` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND ( (`totals`<'1' AND `nolimit`='0') OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
		$my_lastiplog = getRealIP();

		$id_adv_post = ( isset($_POST["id_adv"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_adv"])) ) ? htmlspecialchars(trim($_POST["id_adv"])) : false;
		$id_adv_sess = ( isset($_SESSION["id_adv_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["id_adv_serf"])) ) ? htmlspecialchars(trim($_SESSION["id_adv_serf"])) : false;
		$id_adv_local = ( isset($_POST["id_adv_l"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_adv_l"])) ) ? htmlspecialchars(trim($_POST["id_adv_l"])) : false;

		$token_post = ( isset($_POST["token"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_POST["token"])) ) ? htmlspecialchars(trim($_POST["token"])) : false;
		$token_sess = ( isset($_SESSION["token_serf"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_SESSION["token_serf"])) ) ? htmlspecialchars(trim($_SESSION["token_serf"])) : false;

		$captcha_type = ( isset($_SESSION["captcha_type_serf"]) && preg_match("|^[1-2]{1}$|", trim($_SESSION["captcha_type_serf"])) ) ? htmlspecialchars(trim($_SESSION["captcha_type_serf"])) : false;
		$code_post = ( isset($_POST["code"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_POST["code"])) ) ? htmlspecialchars(trim($_POST["code"])) : false;
		$code_sess = ( isset($_SESSION["captcha_serf_sess"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_SESSION["captcha_serf_sess"])) ) ? htmlspecialchars(trim($_SESSION["captcha_serf_sess"])) : false;
		$code_verif = strrev(md5($code_sess.$token_sess));

		$time_end_serf = ( isset($_SESSION["time_end_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["time_end_serf"])) ) ? htmlspecialchars(trim($_SESSION["time_end_serf"])) : false;

		if(isset($_SESSION["id_adv_serf"])) unset($_SESSION["id_adv_serf"]);
		if(isset($_SESSION["token_serf"])) unset($_SESSION["token_serf"]);
		if(isset($_SESSION["time_end_serf"])) unset($_SESSION["time_end_serf"]);
		if(isset($_SESSION["captcha_type_serf"])) unset($_SESSION["captcha_type_serf"]);
		if(isset($_SESSION["captcha_serf_sess"])) unset($_SESSION["captcha_serf_sess"]);

		$sql_user = mysql_query("SELECT * FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$username = $row_user["username"];
			$my_wmid = $row_user["wmid"];
			$my_referer_1 = $row_user["referer"];
			$my_referer_2 = $my_referer_1!=false ? $row_user["referer2"] : false;
			$my_referer_3 = $my_referer_2!=false ? $row_user["referer3"] : false;
			//$my_referer_4 = $my_referer_3!=false ? $row_user["referer4"] : false;
			//$my_referer_5 = $my_referer_4!=false ? $row_user["referer5"] : false;
			$my_country = $row_user["country_cod"];
			$my_joindate = $row_user["joindate2"];
			//$my_ban_serf = $row_user["ban_serf"];
			$my_youtube_serf = $row_user["youtube_serf"];
			$my_ref_back = ($my_referer_1 != false ? $row_user["ref_back"] : 0);
			$my_visits = isset($row_user["visits_you"]) ? $row_user["visits_you"] : 0;
			//$my_visits_bs = isset($row_user["visits_bs"]) ? $row_user["visits_bs"] : 0;
			$my_ban_date = $row_user["ban_date"];
			$my_frm_position = $row_user["frm_pos"];
			$my_agent = md5(strtolower($row_user["agent"]));
			//$my_wm_check = $row_user["wm_verif"];
			$now_agent = isset($_SERVER["HTTP_USER_AGENT"]) ? md5(strtolower($_SERVER["HTTP_USER_AGENT"])) : false;
			$my_lastiplog_ex = explode(".", $my_lastiplog);
			$my_lastiplog_ex = (isset($my_lastiplog_ex[0]) && isset($my_lastiplog_ex[1])) ? $my_lastiplog_ex[0].".".$my_lastiplog_ex[1]."." : false;

			if($my_agent!=$now_agent) {
				if(isset($_SESSION)) session_destroy();

				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Пользователь не идентифицирован.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Пользователь не идентифицирован.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

		$sql_link = mysql_query("SELECT `id`,`status`,`username`,`type_serf`,`nolimit`,`active`,`timer`,`revisit`,`geo_targ` FROM `tb_ads_youtube` 
			WHERE `id`='$id_adv_post' AND `status`>'0' 
			AND (`totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) 
			AND (`limit_d`='0' OR (`limit_d`>'0' AND `limit_d_now`>'0') ) 
			AND (`limit_h`='0' OR (`limit_h`>'0' AND `limit_h_now`>'0') ) 
		") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		if(mysql_num_rows($sql_link)>0) {
			$row_link = mysql_fetch_assoc($sql_link);

			$id_adv_post = $row_link["id"];
			$status = $row_link["status"];
			$rek_name = $row_link["username"];
			$type_serf = $row_link["type_serf"];
			$active = $row_link["active"];
			$timer = $row_link["timer"];
			$revisit = $row_link["revisit"];
			$geo_targ = $row_link["geo_targ"];
			$nolimit = $row_link["nolimit"];

			if($revisit==0) {
				$time_next = (time() + 1*24*60*60);
			}elseif($revisit==1) {
				$time_next = (time() + 2*24*60*60);
			}elseif($revisit==2) {
				$time_next = (time() + 30*24*60*60);
			}else{
				$time_next = (time() + 1*24*60*60);
			}

			if($status==2) {
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Рекламная площадка не активна.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($status==3) {
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Бюджет рекламной площадки исчерпался.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($type_serf==3 && $my_youtube_serf<=0) {
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Недостаточно прав для просмотра.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($type_serf==4 && $my_ban_serf<=0) {
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Недостаточно прав для просмотра.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Рекламная площадка не доступна.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

		$sql_visit = mysql_query("SELECT `id` FROM `tb_ads_youtube_visits` WHERE `username`='$username' AND `ident`='$id_adv_post' AND `time_next`>='".time()."' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_visit)>0) {
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Вы уже просматривали эту ссылку за требуемый период.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			mysql_query("DELETE FROM `tb_ads_youtube_visits` WHERE `username`='$username' AND `ident`='$id_adv_post' AND `time_next`<'".time()."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		}

		if($id_adv_post==false | $id_adv_sess==false | $captcha_type==false | $code_post==false | $code_sess==false) {
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Нарушен механизм просмотра серфинга.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif( $id_adv_post!=$id_adv_sess | ($id_adv_local!=false && $id_adv_local!=$id_adv_post && $id_adv_local!=$id_adv_sess) ) {
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Вы открыли ещё одну ссылку, смотреть можно только по одной.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($token_post==false | $token_sess==false | $token_post!=$token_sess) {
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Нарушен механизм просмотра серфинга.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($time_end_serf==false | $time_end_serf>time()) {
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Сайт не был просмотрен в течение достаточного количества секунд.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($code_post!=$code_verif) {
			$sql_up_ins = mysql_query("SELECT `id` FROM `tb_ads_youtube_visits` WHERE `username`='$username' AND `ident`='$id_adv_post' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_up_ins)>0) {
				mysql_query("UPDATE `tb_ads_youtube_visits` SET `status`='0',`time`='".time()."',`time_next`='$time_next',`money`='0',`ip`='$my_lastiplog' WHERE `username`='$username' AND `ident`='$id_adv_post'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			}else{
				mysql_query("INSERT INTO `tb_ads_youtube_visits` (`status`,`ident`,`time`,`time_next`,`username`,`ip`, `money`) 
				VALUES('0','$id_adv_post','".time()."','$time_next','$username','$my_lastiplog','0')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			}

			if($captcha_type==1) {
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Не верно решена задача.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Вы не верно указали код с изображения.</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

		}elseif($code_post==$code_verif) {
			
			$ref_proc_1 = 0;
			$ref_proc_2 = 0;
			$ref_proc_3 = 0;
			//$ref_proc_4 = 0;
			//$ref_proc_5 = 0;
			

			/*if($my_referer_1 != false && $type_serf != 10) {
				$sql_ref_1 = mysql_query("SELECT `id`,`reiting`,`money_rb`,`referer` FROM `tb_users` WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				
				if(mysql_num_rows($sql_ref_1)>0) {
					$row_ref_1 = mysql_fetch_assoc($sql_ref_1);
					$user_id_ref_1 = $row_ref_1["id"];
					$reit_ref_1 = $row_ref_1["reiting"];
					$money_ref_1 = $row_ref_1["money_rb"];
					$my_referer_2 = $row_ref_1["referer"];
					($sql_rang_1 = mysql_query("SELECT `youtube_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='" . floor($reit_ref_1) . "'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$ref_proc_1 = (0 < mysql_num_rows($sql_rang_1) ? mysql_result($sql_rang_1, 0, 0) : 0);

						
					if($my_referer_2 != false) {
						$sql_ref_2 = mysql_query("SELECT `reiting`,`referer` FROM `tb_users` WHERE `username`='$my_referer_2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						
						if(mysql_num_rows($sql_ref_2)>0) {
							$row_ref_2 = mysql_fetch_assoc($sql_ref_2);
							$reit_ref_2 = $row_ref_2["reiting"];
							$my_referer_3 = $row_ref_2["referer"];
						    ($sql_rang_2 = mysql_query("SELECT `youtube_2` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_2' AND `r_do`>='" . floor($reit_ref_2) . "'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
							$ref_proc_2 = (0 < mysql_num_rows($sql_rang_2) ? mysql_result($sql_rang_2, 0, 0) : 0);

							
					if($my_referer_3 != false) {
						$sql_ref_3 = mysql_query("SELECT `reiting`,`referer` FROM `tb_users` WHERE `username`='$my_referer_3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						
						if(mysql_num_rows($sql_ref_3)>0) {
							$row_ref_3 = mysql_fetch_assoc($sql_ref_3);
							$reit_ref_3 = $row_ref_3["reiting"];
							$my_referer_4 = $row_ref_3["referer"];
						    ($sql_rang_3 = mysql_query("SELECT `youtube_3` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_3' AND `r_do`>='" . floor($reit_ref_3) . "'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
							$ref_proc_3= (0 < mysql_num_rows($sql_rang_3) ? mysql_result($sql_rang_3, 0, 0) : 0);

									
									
								}else{
									$my_referer_3 = false;
								}
							}
						}else{
							$my_referer_2 = false; $my_referer_3 = false;
						}
					}
				}else{
					$my_ref_back = 0; $my_referer_1 = false; $my_referer_2 = false; $my_referer_3 = false;
				}
			}*/
			if($my_referer_1 != false && $type_serf != 10) {
				$sql_ref_1 = mysql_query("SELECT `id`,`reiting`,`money_rb`,`referer` FROM `tb_users` WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_ref_1)>0) {
					$row_ref_1 = mysql_fetch_assoc($sql_ref_1);
					$user_id_ref_1 = $row_ref_1["id"];
					$reit_ref_1 = $row_ref_1["reiting"];
					$money_ref_1 = $row_ref_1["money_rb"];
					$my_referer_2 = $row_ref_1["referer"];

					$sql_rang_1 = mysql_query("SELECT `youtube_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='".floor($reit_ref_1)."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$ref_proc_1 = mysql_num_rows($sql_rang_1)>0 ? mysql_result($sql_rang_1,0,0) : 0;

					if($my_referer_2 != false) {
						$sql_ref_2 = mysql_query("SELECT `reiting`,`referer` FROM `tb_users` WHERE `username`='$my_referer_2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						if(mysql_num_rows($sql_ref_2)>0) {
							$row_ref_2 = mysql_fetch_assoc($sql_ref_2);
							$reit_ref_2 = $row_ref_2["reiting"];
							$my_referer_3 = $row_ref_2["referer"];

							$sql_rang_2 = mysql_query("SELECT `youtube_2` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_2' AND `r_do`>='".floor($reit_ref_2)."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							$ref_proc_2 = mysql_num_rows($sql_rang_2)>0 ? mysql_result($sql_rang_2,0,0) : 0;

							if($my_referer_3 != false) {
								$sql_ref_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_ref_3)>0) {
									$row_ref_3 = mysql_fetch_assoc($sql_ref_3);
									$reit_ref_3 = $row_ref_3["reiting"];

									$sql_rang_3 = mysql_query("SELECT `youtube_3` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_3' AND `r_do`>='".floor($reit_ref_3)."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									$ref_proc_3 = mysql_num_rows($sql_rang_3)>0 ? mysql_result($sql_rang_3,0,0) : 0;
								}else{
									$my_referer_3 = false;
								}
							}
						}else{
							$my_referer_2 = false; $my_referer_3 = false;
						}
					}
				}else{
					$my_ref_back = 0; $my_referer_1 = false; $my_referer_2 = false; $my_referer_3 = false;
				}
			}
			

			($sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_you' AND `howmany`='1'")) || exit(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$reiting_serf = mysql_result($sql, 0, 0);
			
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_nacenka' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$youtube_nacenka = mysql_result($sql,0,0);

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='".($type_serf==2 | $type_serf==4 ? "youtube_cena_hits_bs" : "youtube_cena_hits")."' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$youtube_cena_hits = mysql_result($sql,0,0);

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_active' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$youtube_cena_active = mysql_result($sql,0,0);

			
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_ot' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$youtube_timer_ot = mysql_result($sql,0,0);

				//$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_do' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				//$youtube_timer_do = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_timer' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$youtube_cena_timer = mysql_result($sql,0,0);
			

			
				$youtube_click_us = ( $youtube_cena_hits + $active * $youtube_cena_active + abs($timer-$youtube_timer_ot) * $youtube_cena_timer);
			

				$my_ref_back = $youtube_click_us * $ref_proc_1 / 100 * $my_ref_back / 100;
			if(0<$my_ref_back) {
                        $youtube_click_us_see = "(".round(p_floor($youtube_click_us, 4), 4)." + ".round(p_floor($my_ref_back, 4), 4)." рефбек)";
						$youtube_click_us_mee = (round(p_floor($youtube_click_us, 4), 4))+(round(p_floor($my_ref_back, 4), 4));
                    }else{
                        $youtube_click_us_see = "".round(p_floor($youtube_click_us, 4), 4)."";
						$youtube_click_us_mee = round(p_floor($youtube_click_us, 4), 4);
                    }
			$youtube_click_us = round(p_floor($youtube_click_us + $my_ref_back, 4), 4);
			$add_money_r_1 = round(p_floor(($youtube_click_us * ($ref_proc_1 / 100)) - $my_ref_back, 4), 4);
			$add_money_r_2 = round(p_floor($youtube_click_us * ($ref_proc_2 / 100), 4), 4);
			$add_money_r_3 = round(p_floor($youtube_click_us * ($ref_proc_3 / 100), 4), 4);
			//$add_money_r_4 = round(p_floor($youtube_click_us * ($ref_proc_4 / 100), 4), 4);
			//$add_money_r_5 = round(p_floor($youtube_click_us * ($ref_proc_5 / 100), 4), 4);
			
			$youtube_click_ref = ( $youtube_cena_hits + $active * $youtube_cena_active + abs($timer-$youtube_timer_ot) * $youtube_cena_timer);
			$youtube_click_ref = round(p_floor($youtube_click_ref, 4), 4);
			$add_money_ref_1 = round(p_floor(($youtube_click_ref * ($ref_proc_1 / 100)), 4), 4);
			$add_money_ref_2 = round(p_floor($youtube_click_ref * ($ref_proc_2 / 100), 4), 4);
			$add_money_ref_3 = round(p_floor($youtube_click_ref * ($ref_proc_3 / 100), 4), 4);
			//$add_money_ref_4 = round(p_floor($youtube_click_ref * ($ref_proc_4 / 100), 4), 4);
			//$add_money_ref_5 = round(p_floor($youtube_click_ref * ($ref_proc_5 / 100), 4), 4);
			
			//if (0 < $my_ref_back) {
			//$youtube_click_us_see = '(' . round( p_floor( $youtube_click_us, 4 ), 4 ) . ' + ' . round( p_floor( $my_ref_back, 4 ), 4 ) . ' рефбек)';
			//}

			$sql_up_ins = mysql_query("SELECT `id` FROM `tb_ads_youtube_visits` WHERE `username`='$username' AND `ident`='$id_adv_post' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_up_ins)>0) {
				mysql_query("UPDATE `tb_ads_youtube_visits` SET `status`='1',`time`='".time()."',`time_next`='$time_next',`money`='$youtube_click_us',`ip`='$my_lastiplog' WHERE `username`='$username' AND `ident`='$id_adv_post'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			}else{
				mysql_query("INSERT INTO `tb_ads_youtube_visits` (`status`,`ident`,`time`,`time_next`,`username`,`ip`, `money`) 
				VALUES('1','$id_adv_post','".time()."','$time_next','$username','$my_lastiplog','$youtube_click_us')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			}

			if($type_serf==10) {
				$youtube_click_us = 0;
				$reiting_serf = 0; $add_money_r_1 = 0; $add_money_r_2 = 0; $add_money_r_3 = 0;

				$sql_ac = mysql_query("SELECT `id` FROM `tb_auto_clicker` WHERE `ident`='$id_adv_post' AND `username`='$username' ORDER BY `id` DESC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_ac)>0) {
					mysql_query("UPDATE `tb_auto_clicker` SET `time`='".time()."', `ip`='$my_lastiplog' WHERE `username`='$username' AND `ident`='$id_adv_post'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}else{
					mysql_query("INSERT INTO `tb_auto_clicker` (`ident`,`time`,`ip`,`username`) 
					VALUES('$id_adv_post','".time()."','$my_lastiplog','$username')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}

				$sql_ch1 = mysql_query("SELECT `id` FROM `tb_black_users` WHERE `name`='$username' AND `why`='Использование автокликера'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_ch1)==0) {
					mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`) 
					VALUES ('$username','Использование автокликера','$my_lastiplog','".DATE("d.m.Y H:i")."', '".time()."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='$username' AND `ban_date`='0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				}

				if(preg_match("|^[\d]{12}$|", trim($my_wmid))!==false) {
					$sql_ch2 = mysql_query("SELECT `id` FROM `tb_black_wmid` WHERE `wmid`='$my_wmid'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					if(mysql_num_rows($sql_ch2)==0) {
						mysql_query("INSERT INTO `tb_black_wmid` (`wmid`,`reason`,`date`,`ip`) 
						VALUES ('$my_wmid','Использование автокликера','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					}
				}

				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка! Использование автокликера.<span><b>Ваш аккаунт заблокирован.</b></span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$totals_tab = ($nolimit==0 | $nolimit<time()) ? "`totals`=`totals`-'1', " : false;
				$youtube_click_us + $add_money_r_1 + $add_money_r_2 + $add_money_r_3;

				//mysql_query("UPDATE `tb_ads_youtube`  SET `totals`=`totals`-'1',`members`=`members`+'1',`outside`=`outside`+'1', `pay_users`=`pay_users`+'$pay_users_tab' WHERE `id`='$id_adv_post' AND `status`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("UPDATE `tb_ads_youtube` SET $totals_tab `members`=`members`+'1',`outside`=`outside`+'1' WHERE `id`='$id_adv_post' AND `status`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("UPDATE `tb_ads_youtube`  SET `limit_h_now`=`limit_h_now`-'1' WHERE `id`='$id_adv_post' AND `status`='1' AND `limit_h`>'0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("UPDATE `tb_ads_youtube`  SET `limit_d_now`=`limit_d_now`-'1' WHERE `id`='$id_adv_post' AND `status`='1' AND `limit_d`>'0'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("UPDATE `tb_statistics` SET `hitov`=`hitov`+'1' WHERE `id`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				if($type_serf==3 && $my_youtube_serf>0) {
					$vip_serf_tab = ", `youtube_serf`=`youtube_serf`-'1'";
				}elseif($type_serf==4 && $my_ban_serf>0) {
					$vip_serf_tab = ", `ban_serf`=`ban_serf`-'1'";
				}else{
					$vip_serf_tab = false;
				}

				if($type_serf==2 | $type_serf==4) {
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reiting_serf', `visits_bs`=`visits_bs`+'1', `money`=`money`+'$youtube_click_us', `money_bs`=`money_bs`+'$youtube_click_us' $vip_serf_tab WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					### START СБОР СТАТИСТИКИ ####
					stats_users($username, strtolower(DATE("D")), "ban_serf");
					### END СБОР СТАТИСТИКИ ######
				}else{
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reiting_serf', `visits_you`=`visits_you`+'1', `money`=`money`+'$youtube_click_us', `money_you`=`money_you`+'$youtube_click_us' $vip_serf_tab WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					### START СБОР СТАТИСТИКИ ####
					stats_users($username, strtolower(DATE("D")), "you");
					### END СБОР СТАТИСТИКИ ######
				}
				
				### РЕФ-ОТЧИСЛЕНИЯ ###
				if($my_referer_1 != false && $my_ban_date == 0) {
					mysql_query("UPDATE `tb_users` SET `referalvisits`=`referalvisits`+'1', `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$username'");
					konkurs_clic_ref($my_referer_1, $add_money_ref_1);
					 konkurs_best_ref($username, $add_money_ref_1);
					$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

					if($my_referer_2 != false) {
						mysql_query("UPDATE `tb_users` SET `referal2visits`=`referal2visits`+'1', `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
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
							mysql_query("UPDATE `tb_users` SET `referal3visits`=`referal3visits`+'1', `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_3', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));							
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

				### АДМИН-КОНКУРС КЛИКЕРОВ NEW ###
				if (($geo_targ == false) && (strtolower($rek_name) != strtolower($username)) && ($my_ban_date == 0)) {
					($sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='status'")) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$konk_youtub_status = mysql_result($sql, 0, 0);

					if ($konk_youtub_status == 1) {
						($sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_start'")) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_youtub_date_start = mysql_result($sql, 0, 0);
						($sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_end'")) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_youtub_date_end = mysql_result($sql, 0, 0);

						if ((time() <= $konk_youtub_date_end) && ($konk_youtub_date_start <= time())) {
							mysql_query("UPDATE `tb_users` SET `konkurs_youtub`=`konkurs_youtub`+'1' WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
				}
				### АДМИН-КОНКУРС КЛИКЕРОВ NEW ###
				
				### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
				if($geo_targ == false && strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
					$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					$konk_complex_status = mysql_result($sql,0,0);

					if($konk_complex_status==1) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_complex_date_start = mysql_result($sql,0,0);

						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_complex_date_end = mysql_result($sql,0,0);

						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_youtub'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$konk_complex_point = mysql_result($sql,0,0);

						if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
							mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
				}
				### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###

                    if($my_referer_1 != false && $my_ban_date == 0) {

					### РЕФ-КОНКУРС КЛИКЕРОВ I ур. ###
					if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='5' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
					### РЕФ-КОНКУРС КЛИКЕРОВ I ур. ###
					
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
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
				

					/*### РЕФ-БОНУСЫ ЗА КЛИКИ NEW ###
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='5' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `ident`='".$row_r_b_1["id"]."' AND `type`='3' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$username','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." кликов в серфинге','Зачислено','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $username за ".$row_r_b_1["count_nado"]." кликов в серфинге','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$username','Система','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." кликов в серфинге','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$username','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
					### РЕФ-БОНУСЫ ЗА КЛИКИ NEW ###*/
					
					### РЕФ-БОНУСЫ ЗА ПРОСМОТР ВИДЕОРОЛИКОВ NEW ###
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='5' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `ident`='".$row_r_b_1["id"]."' AND `type`='5' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$username','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." просмотр видеороликов в YouTube серфинге','Зачислено','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $username за ".$row_r_b_1["count_nado"]." просмотр видеороликов в YouTube серфинге','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$username','Система','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." просмотр видеороликов в YouTube серфинге','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$username','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
					### РЕФ-БОНУСЫ ЗА ПРОСМОТР ВИДЕОРОЛИКОВ NEW ###
					
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
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

					### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ I ур. ###
					if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
					### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ I ур. ###

					if($my_referer_2 != false) {
						### РЕФ-КОНКУРС КЛИКЕРОВ II ур. ###
						if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='5' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
						### РЕФ-КОНКУРС КЛИКЕРОВ II ур. ###

						### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ II ур. ###
						if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
						### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ II ур. ###
						
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###
						if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
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

						if($my_referer_3 != false) {
							### РЕФ-КОНКУРС КЛИКЕРОВ III ур. ###
							if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='5' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
							### РЕФ-КОНКУРС КЛИКЕРОВ III ур. ###

							### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ III ур. ###
							if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
							### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ III ур. ###
							
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							if($geo_targ == false && strtolower($rek_name) != strtolower($username)) {
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
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							
							
						}
					}
				}
			}


			$result_text = "OK"; $message_text = '<div class="block-success">Спасибо за посещение!<span>Оплата за просмотр <b>'.$youtube_click_us_see.' руб.</b> зачислена.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Действие не произведено. [код:3]</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Пользователь не идентифицирован.</span></div>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Не корректный запрос.</span></div>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

?>