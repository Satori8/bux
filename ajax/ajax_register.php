<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("CABINET")) DEFINE("CABINET", true);
//sleep(0);

$js_result = array("result" => "", "message" => "");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/funcemail.php");
	require(ROOT_DIR."/cabinet/cab_func.php");
	require(ROOT_DIR."/merchant/func_mysql.php");
	require(ROOT_DIR."/recaptcha/config_recaptcha.php");
	require(ROOT_DIR."/recaptcha/lib/autoload.php");

	function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
		$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
		$message_text = false;
		$errfile = str_replace($_SERVER["DOCUMENT_ROOT"], "", $errfile);
		switch ($errno) {
			case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
			case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
			case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
			default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
		}
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}
	$set_error_handler = set_error_handler('myErrorHandler', E_ALL);

	function UserStatus($reiting){
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='$reiting' AND `r_do`>='".floor($reiting)."'");
		if(mysql_num_rows($sql_rang)>0) {
			$row_rang = mysql_fetch_assoc($sql_rang);
		}else{
			$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
			$row_rang = mysql_fetch_assoc($sql_rang);
		}
		return '<span style="cursor:pointer; color:#FF0000;" title="Статус">'.$row_rang["rang"].'</span>';
	}

	function isValidLogin($login) {
		$pattern = "/^[a-zA-Z][a-zA-Z0-9-_\.]{3,20}$/";
		return preg_match($pattern, trim($login));
	} 

	function isValidEmail($email) {
		$pattern = "/^(([\w-\s]+)|([\w-]+(?:\.[\w-]+)*)|([\w-\s]+)([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i";
		return preg_match($pattern, trim($email));
	} 

	function isValidCode($code, $length) {
		$pattern = "/^[a-fA-F0-9]{".$length."}$/";
		return preg_match($pattern, trim($code));
	} 

	function isValidText($mensaje) {
		$mensaje = stripslashes(trim($mensaje));
		$mensaje = strip_tags(trim($mensaje));
		$mensaje = str_replace("<", "", $mensaje);
		$mensaje = str_replace(">", "", $mensaje);
		$mensaje = str_replace(";", "", $mensaje);
		$mensaje = str_replace("'", "", $mensaje);
		$mensaje = str_replace("`", "", $mensaje);
		$mensaje = str_replace('"', "", $mensaje);
		$mensaje = str_replace("$", "", $mensaje);
		$mensaje = str_replace("&", "", $mensaje);
		$mensaje = str_replace("?", "", $mensaje);
		return trim(iconv("UTF-8", "CP1251//TRANSLIT", trim($mensaje)));
	}

	function gen_email_code($number) {
		$arr_symvol = array('a','b','c','d','e','f','1','2','3','4','5','6','7','8','9','0');
		$code = array();
		for($i = 0; $i < $number; $i++) {
			$code[] = $arr_symvol[mt_rand(0, (count($arr_symvol)-1))];
		}
		if(is_array($code) && count($code)==$number) return implode("", $code);
	}

	function gen_pin_code($number) {
		$arr_symvol = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
		$code = array();
		for($i = 0; $i < $number; $i++) {
			$code[] = $arr_symvol[mt_rand(0, (count($arr_symvol)-1))];
		}
		if(is_array($code) && count($code)==$number) return implode("", $code);
	}

	function gen_pass($number) {
		$arr_symvol = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
		$code = array();
		for($i = 0; $i < $number; $i++) {
			$code[] = $arr_symvol[mt_rand(0, (count($arr_symvol)-1))];
		}
		if(is_array($code) && count($code)==$number) return implode("", $code);
	}

	function InfoStatus($_Status_Arr, $user_reiting){
		if($user_reiting > 2147483647) $user_reiting = 2147483647;
		for($i=1; $i<=count($_Status_Arr); $i++) {
			if($_Status_Arr[$i]["r_ot"] <= $user_reiting && $_Status_Arr[$i]["r_do"] >= floor($user_reiting)) {
				$InfoSatus["id"] = $i;
				$InfoSatus["rang"] = $_Status_Arr[$i]["rang"];
				$break;
			}
		}

		$InfoSatus["id"] = isset($InfoSatus["id"]) ? $InfoSatus["id"] : 1;
		$InfoSatus["rang"] = isset($InfoSatus["rang"]) ? $InfoSatus["rang"] : $_Status_Arr[$InfoSatus["id"]]["rang"];

		return $InfoSatus;
	}

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$result_text = "ERROR";
		$message_text = "Вы уже зарегистрированы!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);

	/*if(!isset($siteKey) | !isset($secret) | ( isset($siteKey) && isset($secret) && ($siteKey == false | $secret == false) ) ) {
		$result_text = "ERROR";
		$message_text = "ERROR: Нет ключей!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
	*/
	}
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["op"])) ) ? limpiarez($_POST["op"]) : false;
		$ref_id = ( isset($_SESSION["r"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["r"])) ) ? intval(trim($_SESSION["r"])) : false;
		$http_ref = (isset($_SESSION["http_ref"]) && $_SESSION["http_ref"]!=false && stripos($_SESSION["http_ref"], $_SERVER["HTTP_HOST"])===false ) ? limpiar($_SESSION["http_ref"]) : false;
		$token_sess = (isset($_SESSION["token_reg"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["token_reg"]))) ? strtoupper(limpiarez($_SESSION["token_reg"])) : false;
		$token_post = (isset($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_POST["token"]))) ? strtoupper(limpiarez($_POST["token"])) : false;
		
		$login_post = (isset($_POST["login_user"])) ? isValidText($_POST["login_user"]) : false;
		$email_post = (isset($_POST["email_user"])) ? isValidText($_POST["email_user"]) : false;
		$code_post = (isset($_POST["ver_code"])) ? isValidText($_POST["ver_code"]) : false;
		$ip = (isset($_POST["ip"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["ip"])) ? limpiarez($_POST["ip"]) : false);
		//$captcha_post = isset($_POST["recaptcha"]) ? isValidText($_POST["recaptcha"]) : false;
		$captcha_sess = (isset($_SESSION["captcha_site_sess"]) && preg_match("/^[0-9a-fA-F]{32}\$/", trim($_SESSION["captcha_site_sess"])) ? strtolower(htmlspecialchars(trim($_SESSION["captcha_site_sess"]))) : false);
	    $captcha_post = (isset($_POST["captcha_str"]) && preg_match("/^[1-8]{1}\$/", trim($_POST["captcha_str"])) ? strtolower(md5(htmlspecialchars(trim($_POST["captcha_str"])))) : false);
				if( isset($_SESSION["captcha_site_sess"]) ) {
					unset($_SESSION["captcha_site_sess"]);
				}

		$ob_sogl = (isset($_POST["ob_sogl"]) && preg_match("|^[0-1]{1}$|", intval(trim($_POST["ob_sogl"])))) ? intval(trim($_POST["ob_sogl"])) : false;
		$code_sess = ( isset($_SESSION["code_reg"]) && preg_match("|^[0-9a-fA-F]{6}$|", trim($_SESSION["code_reg"])) ) ? isValidText($_SESSION["code_reg"]) : false;
		$email_sess = ( isset($_SESSION["email_reg"]) && isValidEmail(isValidText($_SESSION["email_reg"])) ) ? isValidText($_SESSION["email_reg"]) : false;

		$ip = getRealIP();
		$ip_arr = explode(".", $ip);
		$ip_m[1] = isset($ip_arr[0]) ? $ip_arr[0] : false;
		$ip_m[2] = isset($ip_arr[1]) ? $ip_arr[1] : false;
		$ip_m[3] = isset($ip_arr[2]) ? $ip_arr[2] : false;
		$ip_m[4] = isset($ip_arr[3]) ? $ip_arr[3] : false;
		$ip_m_4 = $ip_m[1].".".$ip_m[2].".".$ip_m[3].".".$ip_m[4];
		$ip_m_3 = $ip_m[1].".".$ip_m[2].".".$ip_m[3].".";
		$ip_m_2 = $ip_m[1].".".$ip_m[2].".";

		$login_user = ( isset($login_post) && $login_post != false && isValidLogin($login_post) ) ? $login_post : false;
		$email_user = ( isset($email_post) && $email_post != false && isValidEmail($email_post) ) ? $email_post : false;
		$email_use = ( isset($email_post) && $email_post != false && email($email_post) ) ? $email_post : false;
		$code_user = ( isset($code_post) && $code_post != false && isValidCode($code_post, 6) ) ? $code_post : false;
		$country_code = ( isset($country_code) && $country_code != false && isValidEmail($country_code) ) ? $country_code : false;
		$statusref = (isset($_SESSION["statusref"]) && filter_var($_SESSION["statusref"], FILTER_VALIDATE_FLOAT)==6) ? "6" : "0";

		if($option=="LoadVerif") {
			if(isset($_SESSION["check_code_reg"])) unset($_SESSION["check_code_reg"]);
			
			$sql_b_ip_4 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_4'");
			$sql_b_ip_3 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_3'");
			$sql_b_ip_2 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_2'");
			
			if(!isset($_SESSION["token_reg"]) | $token_sess == false) {
				$result_text = "ERROR"; 
				$message_text = "Время сессии истекло. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($token_sess == false | $token_post == false | $token_sess != $token_post) {
				$result_text = "ERROR"; 
				$message_text = "Нарушен процесс регистрации. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($ob_sogl =! 1) {
				$result_text = "ERROR"; 
				$message_text = "Наобходимо дать согласие на обработку персональных данных.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($email_post == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(!isValidEmail($email_post) | $email_user == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не верно указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($email_use == false) {
                $result_text = "ERROR"; 
				$message_text = "Регистрация с одноразовых почтовых сервисов запрещена!!!.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}else{
				$result_text = "OK"; 
				$message_text = '<span class="warning-info" style="font-weight:normal;">';
				$message_text.= 'Для продолжения регистрации необходимо проверить Ваш E-mail.<br>';
				$message_text.= 'Если письмо не приходит посмотрите папку СПАМ или попробуйте <a onClick="" style="cursor:pointer;">ввести другую почту</a>.<br>';
				$message_text.= 'Нажмите кнопку <b>"Отправить код"</b> чтобы получить код на E-mail,<br> если вы уже получили код на E-mail нажмите кнопку <b>"Ввести код"</b>.';
				$message_text.= '<div id="SendVerif" style="margin-top:10px;">';
					$message_text.= '<span onClick="GetCode();" class="sub-blue" style="float:none; display: inline-block; margin:3px;">Отправить код</span>';
					$message_text.= '<span onClick="EnterCode();" class="sub-blue" style="float:none; display: inline-block; margin:3px;">Ввести код</span>';
				$message_text.= '</div>';
				$message_text.= '</span>';
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}

		}elseif($option=="GetCode") {
			if(isset($_SESSION["check_code_reg"])) unset($_SESSION["check_code_reg"]);

			if(!isset($_SESSION["token_reg"]) | $token_sess == false) {
				$result_text = "ERROR"; 
				$message_text = "Время сессии истекло. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($token_sess == false | $token_post == false | $token_sess != $token_post) {
				$result_text = "ERROR"; 
				$message_text = "Нарушен процесс регистрации. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($ob_sogl =! 1) {
				$result_text = "ERROR"; 
				$message_text = "Наобходимо дать согласие на обработку персональных данных.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($email_post == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(!isValidEmail($email_post) | $email_user == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не верно указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($email_use == false) {
                $result_text = "ERROR"; 
				$message_text = "Регистрация с одноразовых почтовых сервисов запрещена!!!.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif( $email_sess != false && strtolower($email_user)==strtolower($email_sess) ) {
				$result_text = "ERROR"; 
				$message_text = "Вы уже отправляли код на этот E-mail.<br>";
				$message_text.= "Если письмо не пришло в течение часа, проверьте папку спам<br>";
				$message_text.= "или попробуйте использовать другой E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}else{
				$sql_b_ip_4 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_4'");
				$sql_b_ip_3 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_3'");
				$sql_b_ip_2 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_2'");

				if(mysql_num_rows($sql_b_ip_4)>0 | mysql_num_rows($sql_b_ip_3)>0 | mysql_num_rows($sql_b_ip_2)>0) {
					$result_text = "ERROR"; $message_text = "Регистрация не возможна! IP адрес $ip в черном списке.";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);
				}

				$sql_check_email = mysql_query("SELECT `id` FROM `tb_users` WHERE `email`='$email_user'");
				$sql_check_ip = mysql_query("SELECT `id` FROM `tb_users` WHERE `ip`='$ip' OR `lastiplog`='$ip'");

				if(mysql_num_rows($sql_check_ip)>0) {
					$result_text = "ERROR"; 
					$message_text = "С Вашего ip адреса уже была регистрация!";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);
				}elseif(mysql_num_rows($sql_check_email)>0) {
					$result_text = "ERROR"; 
					$message_text = "Пользователь с указанным E-mail уже зарегистрирован!";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);
				}else{
					$email_code = ( $code_sess!=false && $email_sess!=false && strtolower($email_user)==strtolower($email_sess) ) ? $code_sess : gen_email_code(6);

					include(ROOT_DIR."/geoip/geoipcity.inc");
						$gi = geoip_open(ROOT_DIR."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
						$record = @geoip_record_by_addr($gi, $ip);
						@geoip_close($gi);
						$country_code = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : false;
						$country_name = function_exists('get_country')!==false ? get_country($country_code) : false;
						
					mysql_query("DELETE FROM `tb_log_reg` WHERE `time`<'".(time()-1*60*60)."'");

					$sql_code = mysql_query("SELECT `ver_code` FROM `tb_log_reg` WHERE `email`='$email_user'");
					if(mysql_num_rows($sql_code)>0) {
						mysql_query("UPDATE `tb_log_reg` SET `date`='".DATE("d.m.Y H:i:s", time())."', `time`='".time()."', `ver_code`='$email_code', `ip`='$ip', `country_cod`='$country_code' WHERE `email`='$email_user'");
					}else{
						mysql_query("INSERT INTO `tb_log_reg` (`date`,`time`,`email`,`ver_code`,`ip`,`country_cod`) 
						VALUES('".DATE("d.m.Y H:i:s", time())."','".time()."','$email_user','$email_code','$ip','$country_code')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
					}
					
					require_once($_SERVER['DOCUMENT_ROOT'].'/class/email.conf.php');
			    require_once($_SERVER['DOCUMENT_ROOT'].'/class/smtp.class.php');
          
					$var = array('{CODE}','{EMAIL}');
          $zamena = array($email_code,$email_user);
	        $msgtext = str_replace($var, $zamena, $email_temp["rega_1"]);
					
					$mail_out = new mailPHP();
             
					if(!$mail_error = $mail_out->send($email_user, 'Пользователь', iconv("CP1251", "UTF-8", 'Проверка E-mail на проекте '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext))) {
						$_SESSION["code_reg"] = $email_code;
						$_SESSION["email_reg"] = $email_user;

						$result_text = "OK"; 
						$message_text = "";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);
					}else{
						$result_text = "ERROR"; 
						$message_text = "Не удалось отправить сообщение на указанный E-mail. Повторите попытку позже.";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);
					}
				}
			}

		}elseif($option=="CheckCode") {
			if(isset($_SESSION["check_code_reg"])) unset($_SESSION["check_code_reg"]);

			if(!isset($_SESSION["token_reg"]) | $token_sess == false) {
				$result_text = "ERROR"; 
				$message_text = "Время сессии истекло. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($token_sess == false | $token_post == false | $token_sess != $token_post) {
				$result_text = "ERROR"; 
				$message_text = "Нарушен процесс регистрации. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($ob_sogl =! 1) {
				$result_text = "ERROR"; 
				$message_text = "Наобходимо дать согласие на обработку персональных данных.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($email_post == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(!isValidEmail($email_post) | $email_user == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не верно указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($email_use == false) {
                $result_text = "ERROR"; 
				$message_text = "Регистрация с одноразовых почтовых сервисов запрещена!!!.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($code_post==false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не указали проверочный код!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(!isValidCode($code_post, 6) | $code_user == false) {
				$result_text = "ERROR"; 
				$message_text = "Проверочный код указан не верно!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			/*}elseif($country_code == false) {
				$result_text = "ERROR"; 
				$message_text = "Не определена страна регистрация не возможна!".";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);*/

			}else{
				mysql_query("DELETE FROM `tb_log_reg` WHERE `time`<'".(time()-1*60*60)."'");

				$sql_check_email = mysql_query("SELECT `id` FROM `tb_users` WHERE `email`='$email_user'");
				$sql_check_code = mysql_query("SELECT `ver_code` FROM `tb_log_reg` WHERE `email`='$email_user' AND `ver_code`='$code_user'");
				$sql_country_code = mysql_query("SELECT `country_cod` FROM `tb_log_reg` WHERE `email`='$email_user' AND `country_cod`='$country_code'");

				if(mysql_num_rows($sql_check_email)>0) {
					$result_text = "ERROR"; 
					$message_text = "Пользователь с указанным E-mail уже зарегистрирован!";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);

				}elseif(mysql_num_rows($sql_check_code)==0) {
					$result_text = "ERROR"; 
					$message_text = "Проверочный код указан не верно!!!";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);
					
				}elseif(mysql_num_rows($sql_country_code)>0) {
					$result_text = "ERROR"; 
					$message_text = "Страна не определена регистрация не возможна!!!";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);

				}elseif(mysql_num_rows($sql_check_code)>0) {
					//mysql_query("DELETE FROM `tb_log_reg` WHERE `email`='$email_user' AND `ver_code`='$code_user'");

					$_SESSION["check_code_reg"] = "OK";
					$_SESSION["email_reg"] = $email_user;

					$result_text = "OK"; 
					$message_text = "";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);
				}
			}


		}elseif($option=="LoadRef") {
			if(isset($_SESSION["check_code_reg"])) unset($_SESSION["check_code_reg"]);

			if($ref_id !== false) {
				$sql_ref = mysql_query("SELECT `id`,`username`,`reiting`,`avatar`,`ref_back_all` FROM `tb_users` WHERE `id`='$ref_id'");
				if(mysql_num_rows($sql_ref)>0) {
					$row_ref = mysql_fetch_assoc($sql_ref);
					$ref_id = $row_ref["id"];
					$my_referer_1 = $row_ref["username"];
					$reiting_ref = $row_ref["reiting"];
					$avatar_ref = $row_ref["avatar"];
					$auto_refback_ref = $row_ref["ref_back_all"];
					$_SESSION["r"] = $ref_id;
				}else{
					$ref_id = false;
					$my_referer_1 = false;
					$reiting_ref = false;
					$avatar_ref = false;
					$auto_refback_ref = false;
					if(isset($_SESSION["r"]) && intval($_SESSION["r"])!=0) unset($_SESSION["r"]);
				}
			}else{
				$ref_id = false;
				$my_referer_1 = false;
				$reiting_ref = false;
				$avatar_ref = false;
				$auto_refback_ref = false;
				if(isset($_SESSION["r"]) && intval($_SESSION["r"])!=0) unset($_SESSION["r"]);
			}

			if($ref_id == false) {
				$message_text = false;
				$message_text_rw = false;
				$message_text_arr_rw = false;

				$message_text.= '<td align="left" style="height:26px;"><b>Ваш реферер</b></td>';
				$message_text.= '<td align="left" style="height:26px;">Вы регистрируетесь без реферера</td>';

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cnt_reg' AND `howmany`='1'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
				$ref_wall_cnt_reg = mysql_num_rows($sql)>0 ? number_format(mysql_result($sql,0,0), 0, ".", "") : 10;

				$sql_rw = mysql_query("SELECT `user_id`,`user_name`,`user_comment` FROM `tb_ref_wall` ORDER BY `id` DESC LIMIT $ref_wall_cnt_reg");
				$all_rw = mysql_num_rows($sql_rw);
				if($all_rw > 0) {
					$sql_s = mysql_query("SELECT `id`,`rang`,`r_ot`,`r_do` FROM `tb_config_rang` ORDER BY `id` ASC") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
					if(mysql_num_rows($sql_s)>0) {
						while ($row_s = mysql_fetch_assoc($sql_s)) {
							$_Status_Arr[$row_s["id"]] = array('rang' => $row_s["rang"], 'r_ot' => $row_s["r_ot"], 'r_do' => $row_s["r_do"]);
						}
					}
					while ($row_rw = mysql_fetch_assoc($sql_rw)) {
					    $count_all_konk = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='" . $row_rw["user_name"] . "' AND `date_s`<='" . time() . "' AND `date_e`>='" . time() . "'")));
                        $count_all_bonus = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `status`='1' AND `username`='" . $row_rw["user_name"] . "'")));
						$sql_user = mysql_query("SELECT `id`,`username`,`reiting`,`ref_back_all`,`avatar` FROM `tb_users` WHERE `id`='".$row_rw["user_id"]."'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
						if(mysql_num_rows($sql_user)>0) {
							$row_user = mysql_fetch_assoc($sql_user);
							$InfoStatus = InfoStatus($_Status_Arr, $row_user["reiting"]);
							$message_text_arr_rw.= '<div class="rw_avatar_small">';
								$message_text_arr_rw.= '<img onClick="ChangeRef(\''.$row_user["id"].'\'); return false;" src="/avatar/'.$row_user["avatar"].'" alt="" style="cursor:pointer;" />';
								$message_text_arr_rw.= '<div class="info-rw">';
									$message_text_arr_rw.= 'Стать рефералом <b>'.$row_user["username"].'</b>';
									$message_text_arr_rw.= '<div align="center">Статус: <b>'.$InfoStatus["rang"].'</b> Рейтинг: <b>'.p_floor($row_user["reiting"], 2).'</b></div>';
									$message_text_arr_rw.= '<div align="center">Авто-рефбек: <b>'.$row_user["ref_back_all"].'</b>%</div>';
									$message_text_arr_rw.= '<div align="center">Конкурсы: '.($count_all_konk > 0 ? '<span style="color: #46a2ff">Всего: '.$count_all_konk.'' : '<span style="color: #C80000"><b>0</b></span>').'</div>';
									$message_text_arr_rw.= '<div align="center">Бонусов: '.($count_all_bonus > 0 ? '<span style="color: #46a2ff">Всего: '.$count_all_bonus.'' : '<span style="color: #C80000"><b>0</b></span>').'</div>';
								$message_text_arr_rw.= '</div>';
							$message_text_arr_rw.= '</div>';
						}
					}
					if(isset($message_text_arr_rw) && $message_text_arr_rw != false) {
						$message_text_rw.= '<td align="center" colspan="2" style="padding:5px 5px 5px 7px; text-align:center; color: #114C5B;">';
							$message_text_rw.= '<div style="margin-bottom:6px;">Может подберёте себе реферера?</div>';
							$message_text_rw.= $message_text_arr_rw;
							if($all_rw > $ref_wall_cnt_reg) $message_text_rw.= '<div style="margin-top:6px;"><a href="/ref_wall" target="_blank">Показать еще</a></div>';
						$message_text_rw.= '</td>';
					}
				}

				$result_text = "OK";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text), "message_rw" => iconv("CP1251", "UTF-8", $message_text_rw))) : $message_text;
				exit($js_result);
			}else{
				$result_text = "OK";
				 $count_all_konk = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='" . $my_referer_1 . "' AND `date_s`<='" . time() . "' AND `date_e`>='" . time() . "'")));
                        $count_all_bonus = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `status`='1' AND `username`='" . $my_referer_1 . "'")));

				$message_text = '<td align="left" style="height:26px;"><b>Ваш реферер</b></td>';
				$message_text.= '<td align="left" style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; font-weight:bold; color:#696969;">';
					$message_text.= '<div style="float:left;"><a href="/wall?uid='.$ref_id.'" target="_blank"><img src="/avatar/'.$avatar_ref.'" class="avatar" width="60" height="60" border="0" title="Перейти на стену пользователя '.$my_referer_1.'" align="absmiddle" alt="" /></a></div>';
					$message_text.= '<div style="float:left; padding:5px 10px 0px 5px;">Логин: <b style="color:#0080EC;">'.ucfirst($my_referer_1).'</b><br>ID: <b style="color:#1C1C1C;">'.$ref_id.'</b><br>Статус: <b>'.UserStatus($reiting_ref).'</b><br>Авто-рефбек: '.($auto_refback_ref>0 ? '<span style="color:#2E8B57; font-weight:bold;">'.$auto_refback_ref.'%</span>' : '<span style="color:#FF0000; font-weight:bold;">0%</span>').'<br>Активные конкурсы: '.($count_all_konk > 0 ? '<span style="color: #7f5bc5">Всего: '.$count_all_konk.'</span>' : '<span style="color: #C80000"><b>0</b></span>').'<br>Активные бонусы: '.($count_all_bonus > 0 ? '<span style="color: #7f5bc5">Всего: '.$count_all_bonus.'</span>' : '<span style="color: #C80000"><b>0</b></span>').'</div>';
				$message_text.= '</td>';

				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}

		}elseif($option=="ChangeRef") {
			if(isset($_SESSION["check_code_reg"])) unset($_SESSION["check_code_reg"]);

			$ref_id = ( isset($_POST["id_rw"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_rw"])) ) ? intval(trim($_POST["id_rw"])) : false;

			if($ref_id !== false) {
				$sql_ref = mysql_query("SELECT `id` FROM `tb_users` WHERE `id`='$ref_id'");
				if(mysql_num_rows($sql_ref)>0) {
					$row_ref = mysql_fetch_assoc($sql_ref);
					$ref_id = $row_ref["id"];
					$_SESSION["r"] = $ref_id;
					$_SESSION["r_rw"] = $ref_id;
					$_SESSION["statusref"] = 6;
				}else{
					$ref_id = false;
					$_SESSION["statusref"] = 0;
					if(isset($_SESSION["r"]) && intval($_SESSION["r"])!=0) unset($_SESSION["r"]);
				}
			}else{
				$ref_id = false;
				$_SESSION["statusref"] = 0;
				if(isset($_SESSION["r"]) && intval($_SESSION["r"])!=0) unset($_SESSION["r"]);
			}

			$result_text = "OK"; 
			$message_text = "$ref_id";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);


		}elseif($option=="Register") {
			if(!isset($_SESSION["token_reg"]) | $token_sess == false) {
				$result_text = "ERROR"; 
				$message_text = "Время сессии истекло. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($token_sess == false | $token_post == false | $token_sess != $token_post) {
				$result_text = "ERROR"; 
				$message_text = "Нарушен процесс регистрации. Обновите страницу!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($ob_sogl =! 1) {
				$result_text = "ERROR"; 
				$message_text = "Наобходимо дать согласие на обработку персональных данных.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($login_post == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не указали Логин!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(!isValidLogin($login_post) | $login_user == false) {
				$result_text = "Ошибка"; 
				$message_text = 'Логин должен состоять только из латинских букв и цифр не менее 4 символов.';
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($email_post == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(!isValidEmail($email_post) | $email_user == false) {
				$result_text = "ERROR"; 
				$message_text = "Вы не верно указали E-mail.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
				
			}elseif($email_use == false) {
                $result_text = "ERROR"; 
				$message_text = "Регистрация с одноразовых почтовых сервисов запрещена!!!.";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif(
				$email_sess == false | strtolower($email_user)!=strtolower($email_sess) | 
				!isset($_SESSION["check_code_reg"]) | 
				(isset($_SESSION["check_code_reg"]) && isValidText($_SESSION["check_code_reg"])!="OK")
			) {
				$result_text = "ERROR"; 
				$message_text = "Нарушен процесс регистрации!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);

			}elseif($captcha_sess == false | $captcha_post == false) {
			if($captcha_sess != $captcha_post) {
				//$ReCaptcha = new \ReCaptcha\ReCaptcha($secret);
				//$ReSponse = $ReCaptcha->verify($captcha_post, $ip);
				
				$sql_b_ip_4 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_4'");
					$sql_b_ip_3 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_3'");
					$sql_b_ip_2 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_2'");

					if(mysql_num_rows($sql_b_ip_4)>0 | mysql_num_rows($sql_b_ip_3)>0 | mysql_num_rows($sql_b_ip_2)>0) {
						$result_text = "ERROR"; $message_text = "Регистрация не возможна! IP адрес $ip в черном списке.";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);
					}

				//if($ReSponse->isSuccess()) {
					$sql_check_login = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$login_user'");
					$sql_check_email = mysql_query("SELECT `id` FROM `tb_users` WHERE `email`='$email_user'");
					$sql_check_ip = mysql_query("SELECT `id` FROM `tb_users` WHERE `ip`='$ip' OR `lastiplog`='$ip'");
					
					if(mysql_num_rows($sql_check_ip)>0) {
						$result_text = "ERROR"; 
						$message_text = "С Вашего ip адреса уже была регистрация!";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);

					}elseif(mysql_num_rows($sql_check_login)>0) {
						$result_text = "ERROR"; 
						$message_text = "Пользователь с указанным Логином уже зарегистрирован!";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);

					}elseif(mysql_num_rows($sql_check_email)>0) {
						$result_text = "ERROR"; 
						$message_text = "Пользователь с указанным E-mail уже зарегистрирован!";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);

					}else{
						$my_referer_1 = false; $my_referer_2 = false; $my_referer_3 = false; $ref_back_all = 0;
						$password_user = gen_pass(mt_rand(6,10));
						$pin_code_user = gen_pin_code(mt_rand(7,9));

						include(ROOT_DIR."/geoip/geoipcity.inc");
						$gi = geoip_open(ROOT_DIR."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
						$record = @geoip_record_by_addr($gi, $ip);
						@geoip_close($gi);
						$country_code = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : false;
						$country_name = function_exists('get_country')!==false ? get_country($country_code) : false;

						if($ref_id != false) {
							$sql_r1 = mysql_query("SELECT * FROM `tb_users` WHERE `id`='$ref_id'");
							if(mysql_num_rows($sql_r1)>0) {
								$row_r1 = mysql_fetch_assoc($sql_r1);
								$id_referer_1 = $row_r1["id"];
								$my_referer_1 = $row_r1["username"];
								$my_referer_2 = $row_r1["referer"];
								$ref_back_all = $row_r1["ref_back_all"];
								$welcome_ref = $row_r1["welcome_ref"];
								$ref_bonus_add = $row_r1["ref_bonus_add"];

								$sql_reit = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref' AND `howmany`='1'");
								$reit_ref = ( mysql_num_rows($sql_reit)>0 /*&& $statusref!=6*/ ) ? mysql_result($sql_reit,0,0) : "0";

								$referals_1 = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$my_referer_1'")) + 1;
								mysql_query("UPDATE `tb_users` SET `referals`='$referals_1', `reiting`=`reiting`+'$reit_ref' WHERE `username`='$my_referer_1'");

								if($my_referer_2 != false) {
									$referals_2 = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$my_referer_2'")) + 1;
									mysql_query("UPDATE `tb_users` SET `referals2`='$referals_2' WHERE `username`='$my_referer_2'");

									$sql_r2 = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$my_referer_2'");
									if(mysql_num_rows($sql_r2)>0) {
										$row_r2 = mysql_fetch_assoc($sql_r2);
										$my_referer_3 = $row_r2["referer"];
											
										if($my_referer_3 != false) {
											$referals_3 = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$my_referer_3'")) + 1;
											mysql_query("UPDATE `tb_users` SET `referals3`='$referals_3' WHERE `username`='$my_referer_3'");
										}
					}
				}
			}
		}
					
				

						$sql_bonus_reg = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_user' AND `howmany`='1'");
						$bonus_reg = mysql_num_rows($sql_bonus_reg)>0 ? mysql_result($sql_bonus_reg,0,0) : 0;

						mysql_query("INSERT INTO `tb_users` (`username`,`password`,`pass_oper`,`ip`,`email`,`email_ok`,`email_sent`,`referer`,`referer2`,`referer3`,`statusref`,`country`,`country_cod`,`http_ref`,`ref_back`,`joindate`,`joindate2`) 
						VALUES('$login_user','$password_user','$pin_code_user','$ip','$email_user','1','1','$my_referer_1','$my_referer_2','$my_referer_3','$statusref','$country_name','$country_code','$http_ref','$ref_back_all','".DATE("d.m.Y")."','".time()."')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
						$id_user = mysql_result(mysql_query("SELECT LAST_INSERT_ID() FROM `tb_users`"),0,0);

						mysql_query("DELETE FROM `tb_log_reg` WHERE `email`='$email_user'");

						### START СБОР СТАТИСТИКИ ####
						mysql_query("UPDATE `tb_statistics` SET `users_all`=`users_all`+'1', `users_s`=`users_s`+'1', `users_24h`=`users_24h`+'1' WHERE `id`='1'");
						stats_users_reg($login_user);
						stats_users_reg_copilka($login_user);
						stats_users_reg_pay($login_user);
						### END СБОР СТАТИСТИКИ ######

						### РЕФ-СТЕНА ###
						if($my_referer_1 != false && $statusref == 6) {
							mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
							VALUES('$my_referer_1','Система','Системное сообщение','У вас новый реферал $login_user. Зарегистирован с Реф-Стены','0','".time()."','$ip')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
						}
						### РЕФ-СТЕНА ###

						### БОНУС ЗА РЕГИСТРАЦИЮ ОТ АДМИНА ###
						if(isset($bonus_reg) && $bonus_reg>0) {
							mysql_query("UPDATE `tb_users` SET `money`=`money`+'$bonus_reg' WHERE `username`='$login_user'");

							mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
							VALUES('1','$login_user','$id_user','".DATE("d.m.Y H:i", time())."','".time()."','$bonus_reg','Бонус от Администрации за регистрацию','Зачислено','prihod')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
						}
						### БОНУС ЗА РЕГИСТРАЦИЮ ОТ АДМИНА ###

						### ПРИВЕТСТВИЕ ОТ РЕФЕРЕРА ###
						if(isset($welcome_ref) && trim($welcome_ref) != false) {
							mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
							VALUES('$login_user','$my_referer_1','Приветствие от реферера','$welcome_ref','0','".time()."','$ip')");
						}
						### ПРИВЕТСТВИЕ ОТ РЕФЕРЕРА ###

						### РЕФ-БОНУС ЗА РЕГИСТРАЦИЮ ###
						if($my_referer_1 != false /*&& $statusref != 6*/) {
							$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='1' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_r_b_1)>0) {
								$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

								mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
								VALUES('-1','$login_user','0','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')");
							}
						}
						### РЕФ-БОНУС ЗА РЕГИСТРАЦИЮ ###

						### РЕФ-КОНКУРС РЕФЕРАЛОВ ###
						if($my_referer_1 != false && $my_referer_2 != false /*&& $statusref != 6*/) {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='3' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								if($row_t["limit2_kon"]==0) {
								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$my_referer_1' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$my_referer_1' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$my_referer_1','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')");
								}
							}
						}

							if($my_referer_3 != false) {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='3' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									if($row_t["limit2_kon"]==0) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$my_referer_1' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$my_referer_1' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$my_referer_1','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')");
									}
								}
							}
						}
					}
						### РЕФ-КОНКУРС РЕФЕРАЛОВ ###

						### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ ###
						if($my_referer_1 != false && $my_referer_2 != false && $statusref != 7) {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$my_referer_1' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'7' WHERE `username`='$my_referer_1' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$my_referer_1','1','".$row_t["id"]."','".$row_t["type_kon"]."','7')");
								}
							}

							if($my_referer_3 != false) {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$my_referer_1' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'7' WHERE `username`='$my_referer_1' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$my_referer_1','2','".$row_t["id"]."','".$row_t["type_kon"]."','7')");
									}
								}
							}
						}
						### РЕФ-КОНКУРС КОМПЛЕКСНЫЙ ###

						if(isset($_SESSION["r"])) 		unset($_SESSION["r"]);
						if(isset($_SESSION["r_rw"])) 		unset($_SESSION["r_rw"]);
						if(isset($_SESSION["statusref"])) 	unset($_SESSION["statusref"]);
						if(isset($_SESSION["token_reg"])) 	unset($_SESSION["token_reg"]);
						if(isset($_SESSION["code_reg"])) 	unset($_SESSION["code_reg"]);
						if(isset($_SESSION["email_reg"])) 	unset($_SESSION["email_reg"]);
						if(isset($_SESSION["check_code_reg"])) 	unset($_SESSION["check_code_reg"]);

						require_once($_SERVER['DOCUMENT_ROOT'].'/class/email.conf.php');
			    require_once($_SERVER['DOCUMENT_ROOT'].'/class/smtp.class.php');
			    
			    $var = array('{ID}','{EMAIL}','{LOGIN}','{PASS}','{PIN}','{IP}');
          $zamena = array($id_user, $email_user, $login_user, $password_user, $pin_code_user, $ip);
	        $msgtext = str_replace($var, $zamena, $email_temp["rega_2"]);
					
					$mail_out = new mailPHP();   
					$mail_error = $mail_out->send($email_user, $login_user, iconv("CP1251", "UTF-8", 'Регистрация на проекте '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext));

						$result_text = "OK"; 
						$message_text = '<div style="margin:0px auto; background:#F0F8FF; padding:0px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:center; color:#114C5B;">';
							$message_text.= '<div style="padding:5px;"><span class="msg-ok"">Регистрация прошла успешно!</span></div>';
							$message_text.= '<div style="padding:5px;"><b>Логин: <span style="color:#4876FF;">'.$login_user.'</span></b></div>';
							$message_text.= '<div style="padding:5px;"><b>E-mail: <span style="color:#2E8B57;">'.$email_user.'</span></b></div>';
							$message_text.= '<div style="padding:5px;"><b>Пароль: <span style="color:#1C1C1C;">'.$password_user.'</span></b></div>';
							$message_text.= '<div style="padding:5px;"><b>Пароль для операций: <span style="color:#1C1C1C;">'.$pin_code_user.'</span></b></div>';
							$message_text.= '<div style="color:#AF0032; padding:10px; font-weight:bold;">Посетите свой аккаунт в течение 24-х часов иначе он будет удалён из системы.</div>';
						$message_text.= '</div>';
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);
					}
				}else{
		$result_text = "ERROR";
		$message_text = "Каптча введена не верно!.";
		$js_result = ($ajax_json == "json" ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text);
		exit($js_result);
		return 1;
			}
			}else{
		$result_text = "ERROR";
		$message_text = "Каптча введена не верно!.";
		$js_result = ($ajax_json == "json" ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text);
		exit($js_result);
		return 1;
			}

		}else{
			$result_text = "ERROR";
			$message_text = "NO OPTION";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}
	//}
}else{
	$result_text = "ERROR";
	$message_text = "Не корректный запрос!";
	$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
	exit($js_result);
}

?>