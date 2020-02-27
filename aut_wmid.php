<?php
session_start();
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
require_once(ROOT_DIR."/.zsecurity.php");
require_once(ROOT_DIR."/funciones.php");
require(ROOT_DIR."/config.php");

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "cp1251");
	$mensaje = str_replace("'","",$mensaje);
	$mensaje = str_replace(";","",$mensaje);
	$mensaje = str_replace("$","",$mensaje);
	$mensaje = str_replace("<","",$mensaje);
	$mensaje = str_replace(">","",$mensaje);
	$mensaje = str_replace("&","",$mensaje);
	return $mensaje;
}

$sql = mysql_query("SELECT `sitewmid` FROM `tb_site` WHERE `id`='1'");
$site_wmid = mysql_num_rows($sql)>0 ? mysql_result($sql,0,0) : false;

if(!isset($URL_ID_WM_LOGIN)) exit("ERROR! URL ID NOT FOUND.");

if(!isset($_POST["WmLogin_WMID"])) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
	exit();
}else{
	$testticket=preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $_POST['WmLogin_Ticket']);

	if($_POST['WmLogin_UrlID']==$URL_ID_WM_LOGIN && $testticket==1) {
		$xml="<request>
			<siteHolder>".$site_wmid."</siteHolder>
			<user>".htmlspecialchars($_POST["WmLogin_WMID"])."</user>
			<ticket>".htmlspecialchars($_POST["WmLogin_Ticket"])."</ticket>
			<urlId>".$URL_ID_WM_LOGIN."</urlId>
			<authType>".htmlspecialchars($_POST["WmLogin_AuthType"])."</authType>
			<userAddress>".htmlspecialchars($_POST["WmLogin_UserAddress"])."</userAddress>
		</request>";

		$resxml = _GetAnswer_($xml);
		$xmlres = simplexml_load_string($resxml);

		if(!isset($xmlres) && trim($xmlres)==false) {
			exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">Не получен XML-ответ!</div>');
		}
		$result = strval($xmlres->attributes()->retval);

		if($result!=0) {
			exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">Ошибка! Вернитесь на <a href="/">главную</a> и попробуйте еще раз!</div>');
		}else{
			$wmid = ( isset($_POST["WmLogin_WMID"]) && preg_match("|^[\d]{12}$|", trim($_POST["WmLogin_WMID"])) ) ? htmlspecialchars(trim($_POST["WmLogin_WMID"])) : false;
			//$laip = isset($_POST["WmLogin_UserAddress"]) ? htmlspecialchars(trim($_POST["WmLogin_UserAddress"])) : false;
			$laip = getRealIP();
			$op = isset($_GET["op"]) ? limpiarez($_GET["op"]) : false;

			if($op!=false && $op=="register") {

				$_SESSION["WMID"] = $wmid;
				$_SESSION["IP"] = $laip;

				echo '<script type="text/javascript">location.replace("/register");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/register"></noscript>';

			}elseif($op!=false && ($op=="adminka" | $op=="moderator")) {

				$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`='$wmid' AND (`user_status`='1' OR `user_status`='2')") or die(mysql_error());
				if(mysql_num_rows($sql)>0) {
					$row = mysql_fetch_assoc($sql);

					$_SESSION["userID"] = $row["id"];
					$_SESSION["userLog"] = $row["username"];
					$_SESSION["userPas"] = md5($row["password"]);
					$_SESSION["userLog_a"] = $row["username"];
					$_SESSION["userPas_a"] = md5($row["password"]);
					$_SESSION["WMID"] = $row["wmid"];
					$_SESSION["IP"] = $laip;
					$agent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : false;
					$redirect_url = (isset($_SESSION["redirect_url"])) ? $_SESSION["redirect_url"] : "/index.php";

					$log_ip_aut = (isset($row["log_ip_aut"]) && trim($row["log_ip_aut"])!=false) ? explode(", ", $row["log_ip_aut"]) : array();
					if(end($log_ip_aut)!=$laip && $laip!=false) $log_ip_aut[] = $laip;
					$log_ip_aut = array_slice($log_ip_aut, -10);
					$log_ip_aut = implode(", ", $log_ip_aut);

					include(ROOT_DIR."/geoip/geoipcity.inc");
					$gi = geoip_open(ROOT_DIR."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
					$record = @geoip_record_by_addr($gi, $laip);
					@geoip_close($gi);
					$country_code = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : false;
					$country_name = function_exists('get_country')!==false ? get_country($country_code) : false;

					mysql_query("UPDATE `tb_users` SET 
						`country`='$country_name', `country_cod`='$country_code', 
						`lastlogdate`='".DATE("d.m.Y")."', `lastlogdate2`='".time()."', 
						`lastiplog`='$laip', `log_ip_aut`='$log_ip_aut', `agent`='$agent' 
					WHERE `wmid`='$wmid' AND `user_status`='1'") or die(mysql_error());

					echo '<script type="text/javascript">location.replace("'.$redirect_url.'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$redirect_url.'"></noscript>';
					exit();
				}else{
					exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">Доступ закрыт! Вы не являетесь '.($op=="moderator" ? "модератором": "администратором").'!</div>');
				}


			}elseif($op!=false && $op=="inv_aut") {
				$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='' AND `wmid`='$wmid' AND `investor`='1'") or die(mysql_error());
				if(mysql_num_rows($sql)>0) {
					$row = mysql_fetch_assoc($sql);

					$partnerid = $row["id"];
					$username = $row["username"];
					$my_referer_1 = $row["referer"];
					$my_referer_2 = $row["referer2"];
					$lastiplog = $row["lastiplog"];
					$tab_agent = $row["agent"];
					$block_agent = $row["block_agent"];
					$agent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : false;
					$redirect_url = (isset($_SESSION["redirect_url"])) ? $_SESSION["redirect_url"] : "/index.php";
					$attestat = $row["attestat"];

					if($row["ban_date"] > 0) {
						$message_text = "Ваш аккаунт заблокирован за нарушение правил проекта!";
						exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">'.$message_text.'</div>');
					}

					$_SESSION["partnerid"] = $partnerid;
					$_SESSION["userLog"] = $username;
					$_SESSION["userPas"] = md5($row["password"]);
					$_SESSION["WMID"] = $row["wmid"];
					$_SESSION["IP"] = $laip;
					SETCOOKIE("_user", $row["username"], (time()+31536000));
					SETCOOKIE("_pid", md5($row["id"]), (time()+31536000));

					$sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username'");
					$referals1 = mysql_num_rows($sql_r1);

					$sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$username'");
					$referals2 = mysql_num_rows($sql_r2);

					$sql_r3 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username'");
					$referals3 = mysql_num_rows($sql_r3);

					if( isset($row["wmid"]) && preg_match("|^[\d]{12}$|", trim($row["wmid"])) ) {
						include_once(ROOT_DIR."/auto_pay_req/wmxml.inc.php");
						$_RES_WM_11 = _WMXML11($row["wmid"]);
						$_ATTESTAT = isset($_RES_WM_11["att"]) ? $_RES_WM_11["att"] : $row["attestat"];
					}else{
						$_ATTESTAT = $row["attestat"];
					}

					include(ROOT_DIR."/geoip/geoipcity.inc");
					$gi = geoip_open(ROOT_DIR."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
					$record = @geoip_record_by_addr($gi, $laip);
					@geoip_close($gi);
					$country_code = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : false;
					$country_name = function_exists('get_country')!==false ? get_country($country_code) : false;

					$log_ip_aut = (isset($row["log_ip_aut"]) && trim($row["log_ip_aut"])!=false) ? explode(", ", $row["log_ip_aut"]) : array();
					if(end($log_ip_aut)!=$laip && $laip!=false) $log_ip_aut[] = $laip;
					$log_ip_aut = array_slice($log_ip_aut, -10);
					$log_ip_aut = implode(", ", $log_ip_aut);

					mysql_query("UPDATE `tb_users` SET 
						`attestat`='$_ATTESTAT', `country`='$country_name', `country_cod`='$country_code', 
						`referals`='$referals1', `referals2`='$referals2', `referals3`='$referals3', 
						`lastlogdate`='".DATE("d.m.Y")."', `lastlogdate2`='".time()."', 
						`lastiplog`='$laip', `log_ip_aut`='$log_ip_aut', `agent`='$agent', 
						`kol_log`=`kol_log`+'1' 
					WHERE `username`='$username'") or die(mysql_error());

					### БАН МУЛЬТОВ ###
					$_USER_T_ID = strtolower(md5($row["id"]));
					$_COOKIE_ID = (isset($_COOKIE["_pid"]) && preg_match("|^[0-9a-fA-F]{32}$|", htmlspecialchars(trim($_COOKIE["_pid"])))) ? htmlspecialchars(strtolower(trim($_COOKIE["_pid"]))) : false;

					if($_COOKIE_ID != false) {
						$sql_ban = mysql_query("SELECT `username` FROM `tb_users` WHERE md5(`id`)='$_COOKIE_ID'") or die(mysql_error());
						if(mysql_num_rows($sql_ban)>0) {
							$_COOKIE_NAME = mysql_result($sql_ban,0,0);

							if($_USER_T_ID != $_COOKIE_ID) {
								$sql_ch1 = mysql_query("SELECT `id` FROM `tb_black_users` WHERE `name`='$_COOKIE_NAME'") or die(mysql_error());
								if(mysql_num_rows($sql_ch1)==0) {
									mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`) 
									VALUES ('$_COOKIE_NAME','Мультиаккаунт ($_COOKIE_NAME, $username)','$laip','".DATE("d.m.Y H:i")."', '".time()."')") or die(mysql_error());

									mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='$_COOKIE_NAME' AND `ban_date`='0'") or die(mysql_error());
								}

								$sql_ch2 = mysql_query("SELECT `id` FROM `tb_black_users` WHERE `name`='$username'") or die(mysql_error());
								if(mysql_num_rows($sql_ch2)==0) {
									mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`) 
									VALUES ('$username','Мультиаккаунт ($_COOKIE_NAME, $username)','$laip','".DATE("d.m.Y H:i")."', '".time()."')") or die(mysql_error());

									mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='$username' AND `ban_date`='0'") or die(mysql_error());
								}
							}
						}
					}
					### БАН МУЛЬТОВ ###

					echo '<script type="text/javascript">location.replace("'.$redirect_url.'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$redirect_url.'"></noscript>';
					exit();
				}else{
					exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">Вы не являетесь инвестором. Доступ закрыт!</div>');
				}


			}elseif($op!=false && $op=="login") {

				$sql = mysql_query("SELECT * FROM `tb_users` WHERE `wmid`!='' AND `wmid`='$wmid'") or die(mysql_error());
				if(mysql_num_rows($sql)>0) {
					$row = mysql_fetch_assoc($sql);

					$user_id = $row["id"];
					$username = $row["username"];
					$my_referer_1 = $row["referer"];
					$my_referer_2 = $row["referer2"];
					$ref_bonus_get_1 = $row["ref_bonus_get_1"];
					$ref_bonus_get_2 = $row["ref_bonus_get_2"];
					$statusref = $row["statusref"];

					$lastiplog = $row["lastiplog"];
					$tab_agent = $row["agent"];
					$block_agent = $row["block_agent"];
					$attestat = $row["attestat"];

					if($row["ban_date"] > 0) {
						$message_text = "Ваш аккаунт заблокирован за нарушение правил проекта!";
						exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">'.$message_text.'</div>');
					}

					$_SESSION["userID"] = $user_id;
					$_SESSION["userLog"] = $username;
					$_SESSION["userPas"] = md5($row["password"]);
					if(isset($row["user_status"]) && intval($row["user_status"])==1) {
						$_SESSION["userLog_a"] = $username;
						$_SESSION["userPas_a"] = md5($row["password"]);
					}
					SETCOOKIE("_user", $username, (time()+7776000));
					SETCOOKIE("_pid", md5($user_id), (time()+7776000));

					$sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username'");
					$referals1 = mysql_num_rows($sql_r1);

					$sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$username'");
					$referals2 = mysql_num_rows($sql_r2);

					$sql_r3 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username'");
					$referals3 = mysql_num_rows($sql_r3);

					if( $row["lastlogdate2"] != 0 && $row["lastlogdate2"] < (time()-7*24*60*60) ) {
						$reit_add = mysql_result(mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_noactive' AND `howmany`='1'"),0,0);;
					}else{
						$reit_add = 0;
					}

					if( isset($row["wmid"]) && preg_match("|^[\d]{12}$|", trim($row["wmid"])) ) {
						include_once(ROOT_DIR."/auto_pay_req/wmxml.inc.php");
						$_RES_WM_11 = _WMXML11($row["wmid"]);

						if(isset($_RES_WM_11["wmids"]) && count($_RES_WM_11["wmids"])>0) {
							$_ALL_WMID_TAB = false;
							for($y=0; $y<count($_RES_WM_11["wmids"]); $y++) {
								$_ALL_WMID_TAB.= $_RES_WM_11["wmids"][$y]." ";
							}
							$_ALL_WMID_TAB = str_replace(" ","; ", trim($_ALL_WMID_TAB));
							$_ATTESTAT = isset($_RES_WM_11["att"]) ? $_RES_WM_11["att"] : $row["attestat"];
						}else{
							$_ALL_WMID_TAB = $row["wmid_all"];
							$_ATTESTAT = $row["attestat"];
						}
					}else{
						$_ALL_WMID_TAB = $row["wmid_all"];
						$_ATTESTAT = $row["attestat"];
					}

					include(ROOT_DIR."/geoip/geoipcity.inc");
					$gi = geoip_open(ROOT_DIR."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
					$record = @geoip_record_by_addr($gi, $laip);
					@geoip_close($gi);
					$country_code = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : false;
					$country_name = function_exists('get_country')!==false ? get_country($country_code) : false;

					$log_ip_aut = (isset($row["log_ip_aut"]) && trim($row["log_ip_aut"])!=false) ? explode(", ", $row["log_ip_aut"]) : array();
					if(end($log_ip_aut)!=$laip && $laip!=false) $log_ip_aut[] = $laip;
					$log_ip_aut = array_slice($log_ip_aut, -10);
					$log_ip_aut = implode(", ", $log_ip_aut);

					mysql_query("UPDATE `tb_users` SET 
						`attestat`='$_ATTESTAT', `wmid_all`='$_ALL_WMID_TAB', 
						`reiting`=`reiting`+'$reit_add', `country`='$country_name', `country_cod`='$country_code', 
						`referals`='$referals1', `referals2`='$referals2', `referals3`='$referals3', 
						`lastlogdate`='".DATE("d.m.Y")."', `lastlogdate2`='".time()."', 
						`lastiplog`='$laip', `log_ip_aut`='$log_ip_aut', 
						`agent`='".$_SERVER["HTTP_USER_AGENT"]."', `kol_log`=`kol_log`+'1' 
					WHERE `username`='$username'") or die(mysql_error());

					### БАН МУЛЬТОВ ###
					$_USER_T_ID = strtolower(md5($row["id"]));
					$_COOKIE_ID = (isset($_COOKIE["_pid"]) && preg_match("|^[0-9a-fA-F]{32}$|", htmlspecialchars(trim($_COOKIE["_pid"])))) ? htmlspecialchars(strtolower(trim($_COOKIE["_pid"]))) : false;

					if($_COOKIE_ID != false) {
						$sql_ban = mysql_query("SELECT `username` FROM `tb_users` WHERE md5(`id`)='$_COOKIE_ID'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
						if(mysql_num_rows($sql_ban)>0) {
							$_COOKIE_NAME = mysql_result($sql_ban,0,0);

							if($_USER_T_ID != $_COOKIE_ID) {
								$sql_ch1 = mysql_query("SELECT `id` FROM `tb_black_users` WHERE `name`='$_COOKIE_NAME'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
								if(mysql_num_rows($sql_ch1)==0) {
									mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`) 
									VALUES ('$_COOKIE_NAME','Мультиаккаунт ($_COOKIE_NAME, $username)','$laip','".DATE("d.m.Y H:i")."', '".time()."')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

									mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='$_COOKIE_NAME' AND `ban_date`='0'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
								}

								$sql_ch2 = mysql_query("SELECT `id` FROM `tb_black_users` WHERE `name`='$username'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
								if(mysql_num_rows($sql_ch2)==0) {
									mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`) 
									VALUES ('$username','Мультиаккаунт ($_COOKIE_NAME, $username)','$laip','".DATE("d.m.Y H:i")."', '".time()."')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

									mysql_query("UPDATE `tb_users` SET `ban_date`='".time()."' WHERE `username`='$username' AND `ban_date`='0'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
								}
							}
						}
					}
					### БАН МУЛЬТОВ ###

					### РЕФБОНУС ЗА РЕГИСТРАЦИЮ ###
					if($statusref==0 && $ref_bonus_get_1==0 && $row["lastlogdate2"]==0) {
						$sql_comis_sys_bon = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'");
						$comis_sys_bon = mysql_num_rows($sql_comis_sys_bon)>0 ? mysql_result($sql_comis_sys_bon,0,0) : 0;

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `type`='1' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							$sql_referer_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
							if(mysql_num_rows($sql_referer_1)>0) {
								$row_referer_1 = mysql_fetch_assoc($sql_referer_1);
								$id_rb_ref_1 = $row_referer_1["id"];
								$money_ref_1 = $row_referer_1["money_rb"];

								$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `id`='".$row_r_b_stat_1["ident"]."' AND `status`='1' AND `username`='$my_referer_1' AND `type_bon`='1' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_r_b_1)>0) {
									$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

									$money_ureferera_nado = ($row_r_b_stat_1["money"] * ($comis_sys_bon+100)/100);
									$money_ureferera_nado = round($money_ureferera_nado, 2);

									if($money_ref_1>=$money_ureferera_nado) {
										mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
										mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `date`='".time()."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

										mysql_query("UPDATE `tb_users` SET `ref_bonus_get_1`='1', `money`=`money`+'".$row_r_b_stat_1["money"]."' WHERE `username`='$username'") or die(mysql_error());
										mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());

										mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
										VALUES('$username','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_stat_1["money"]."','Реф-Бонус от реферера $my_referer_1 за регистрацию на проекте','Зачислено','rashod')") or die(mysql_error());

										mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
										VALUES('$my_referer_1','$id_rb_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $username за регистрацию на проекте','Списано','rashod')") or die(mysql_error());

										if(trim($row_r_b_1["description"])!=false) {
											mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
											VALUES('$username','Система','Реф-Бонус от реферера $my_referer_1 за регистрацию на проекте','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
										}
									}else{
										mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `date`='".time()."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
									}
								}
							}
						}
					}
					### РЕФБОНУС ЗА РЕГИСТРАЦИЮ ###

					### РЕФБОНУС ЗА РЕГИСТРАЦИЮ РЕФЕРАЛА ###
					if($statusref==0 && $ref_bonus_get_2==0 && $row["lastlogdate2"]==0) {
						if(!isset($comis_sys_bon)) {
							$sql_comis_sys_bon = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'");
							$comis_sys_bon = mysql_num_rows($sql_comis_sys_bon)>0 ? mysql_result($sql_comis_sys_bon,0,0) : 0;
						}

						$sql_r_b_stat_2 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$my_referer_1' AND `type`='2' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_2)>0) {
							$row_r_b_stat_2 = mysql_fetch_assoc($sql_r_b_stat_2);

							$sql_referer_1 = mysql_query("SELECT `id`,`ref_bonus_add` FROM `tb_users` WHERE `ref_bonus_add`='1' AND `username`='$my_referer_1'");
							if(mysql_num_rows($sql_referer_1)>0) {
								$row_referer_1 = mysql_fetch_assoc($sql_referer_1);
								$id_rb_ref_1 = $row_referer_1["ref_bonus_add"];

								$sql_referer_2 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_2'");
								if(mysql_num_rows($sql_referer_2)>0) {
									$row_referer_2 = mysql_fetch_assoc($sql_referer_2);
									$id_rb_ref_2 = $row_referer_2["id"];
									$money_ref_2 = $row_referer_2["money_rb"];

									$sql_r_b_2 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `id`='".$row_r_b_stat_2["ident"]."' AND `status`='1' AND `username`='$my_referer_2' AND `type_bon`='2' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_r_b_2)>0) {
										$row_r_b_2 = mysql_fetch_assoc($sql_r_b_2);

										$money_ureferera_nado = ($row_r_b_stat_2["money"] * ($comis_sys_bon+100)/100);
										$money_ureferera_nado = round($money_ureferera_nado, 2);

										if($money_ref_2>=$money_ureferera_nado) {
											mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_2["id"]."'") or die(mysql_error());
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `date`='".time()."' WHERE `id`='".$row_r_b_stat_2["id"]."'") or die(mysql_error());

											mysql_query("UPDATE `tb_users` SET `ref_bonus_get_2`='1' WHERE `username`='$username'") or die(mysql_error());
											mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_stat_2["money"]."' WHERE `username`='$my_referer_1'") or die(mysql_error());
											mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_2'") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('$my_referer_1','$id_rb_ref_1','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_stat_2["money"]."','Реф-Бонус от реферера $my_referer_2 за привлечение реферала (ID:$user_id)','Зачислено','rashod')") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('$my_referer_2','$id_rb_ref_2','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $my_referer_1 за привлечение реферала (ID:$user_id)','Списано','rashod')") or die(mysql_error());

											if(trim($row_r_b_2["description"])!=false) {
												mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
												VALUES('$my_referer_1','Система','Реф-Бонус от реферера $my_referer_2 за привлечение реферала (ID:$user_id)','".$row_r_b_2["description"]."','0','".time()."','0.0.0.0')");
											}
										}else{
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `date`='".time()."' WHERE `id`='".$row_r_b_stat_2["id"]."'") or die(mysql_error());
										}
									}
								}
							}
						}
					}
					### РЕФБОНУС ЗА РЕГИСТРАЦИЮ РЕФЕРАЛА ###

					echo '<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #2E8B57; display:block; padding:10px 20px;">Добро пожаловать на проект!<br>Сейчас Вы будете перемещены в Ваш аккаунт!</div>';

					echo ' <script type="text/javascript"> setTimeout(\'location.replace("/members.php")\', 250); </script> ';
					echo ' <noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL=/members.php"></noscript>';
				}else{
					exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">WMID#'.$wmid.' не зарегистрирован на проекте, либо Вы не указали его в своем профиле! Попробуйте войти в аккаунт, используя логин и пароль!</div>');
				}
			}else{
				echo '<script type="text/javascript">location.replace("/");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
				exit();
			}
		}
	}else{
		exit('<div style="position: fixed; left: 20%; top: 50%; right: 20%; font-size:18px; color:#FFF; text-align:center; text-shadow:1px 1px 1px #000; background-color: #EE6363; display:block; padding:10px 20px;">Ошибка при получении тикета!</div>');
	}
}

function _GetAnswer_($xml) {
	$ch2 = curl_init("https://login.wmtransfer.com/ws/authorize.xiface");
	curl_setopt($ch2, CURLOPT_HEADER, 0);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch2, CURLOPT_POST,1);
	curl_setopt($ch2, CURLOPT_POSTFIELDS, $xml);
	curl_setopt($ch2, CURLOPT_CAINFO, ROOT_DIR."/auto_pay_req/cert/WMunited.cer");
	curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, TRUE);

	$result = curl_exec($ch2);

	if(curl_errno($ch2)) echo "Curl Error number = ".curl_errno($ch2).", Error desc = ".curl_error($ch2)."<br>";
	curl_close($ch2); 
	return $result; 
}
?>