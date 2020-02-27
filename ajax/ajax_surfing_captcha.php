<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "", "message" => "");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

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
	//require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");

	function genSerfAnsw1() {
		$znak_arr = array("+", "-");
		$sign_symbols = $znak_arr[mt_rand(0,count($znak_arr)-1)];

		if($sign_symbols=="+") {
			$first_symbols = mt_rand(1,7);
			$last_symbols = mt_rand(1,(8-$first_symbols));
		} else if($sign_symbols=="-") {
			$first_symbols = mt_rand(2,8);
			$last_symbols = mt_rand(1,($first_symbols-1));
		}
		$keystring = $first_symbols.$sign_symbols.$last_symbols;
		return $keystring;
	}

	function genSerfAnsw2($allowed_symbols, $count_answers) {
		$answers_arr = array();
		$length_allowed = strlen($allowed_symbols)-1;
		while(true) {
			for($i=0; $i<$count_answers; $i++){
				$answers_arr[] = $allowed_symbols{mt_rand(0,$length_allowed)}.$allowed_symbols{mt_rand(0,$length_allowed)}.$allowed_symbols{mt_rand(0,$length_allowed)};
			}
			$answers_arr = array_unique($answers_arr);
			if(count($answers_arr)>=$count_answers) {
				return $answers_arr;
				break;
			}
		}
		return false;
	}

	function ClearSerfSess() {
		if(isset($_SESSION["id_adv_serf"])) unset($_SESSION["id_adv_serf"]);
		if(isset($_SESSION["token_serf"])) unset($_SESSION["token_serf"]);
		if(isset($_SESSION["time_end_serf"])) unset($_SESSION["time_end_serf"]);
		if(isset($_SESSION["captcha_type_serf"])) unset($_SESSION["captcha_type_serf"]);
		if(isset($_SESSION["captcha_serf_sess"])) unset($_SESSION["captcha_serf_sess"]);
		return false;
	}

	####################
	if(isset($_SESSION["captcha_type_serf"])) unset($_SESSION["captcha_type_serf"]);
	if(isset($_SESSION["captcha_serf_sess"])) unset($_SESSION["captcha_serf_sess"]);
	####################

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$id_adv_post = ( isset($_POST["id_adv"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_adv"])) ) ? htmlspecialchars(trim($_POST["id_adv"])) : false;
		$id_adv_sess = ( isset($_SESSION["id_adv_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["id_adv_serf"])) ) ? htmlspecialchars(trim($_SESSION["id_adv_serf"])) : false;
		$id_adv_local = ( isset($_POST["id_adv_l"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_adv_l"])) ) ? htmlspecialchars(trim($_POST["id_adv_l"])) : false;
		$token_post = ( isset($_POST["token"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_POST["token"])) ) ? htmlspecialchars(trim($_POST["token"])) : false;
		$token_sess = ( isset($_SESSION["token_serf"]) && preg_match("/^[0-9a-fA-F]{32}$/", trim($_SESSION["token_serf"])) ) ? htmlspecialchars(trim($_SESSION["token_serf"])) : false;
		$time_end_serf = ( isset($_SESSION["time_end_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["time_end_serf"])) ) ? htmlspecialchars(trim($_SESSION["time_end_serf"])) : false;
		$captcha_type = mt_rand(1,2);

		if($id_adv_post==false | $id_adv_sess==false) {
			ClearSerfSess();
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Нарушен механизм просмотра серфинга.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif( $id_adv_post!=$id_adv_sess | ($id_adv_local!=false && $id_adv_local!=$id_adv_post && $id_adv_local!=$id_adv_sess) ) {
			ClearSerfSess();
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Вы открыли ещё одну ссылку, смотреть можно только по одной.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($token_post==false | $token_sess==false | $token_post!=$token_sess) {
			ClearSerfSess();
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Нарушен механизм просмотра серфинга.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($time_end_serf==false | $time_end_serf>time()) {
			ClearSerfSess();
			$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Сайт не был просмотрен в течение достаточного количества секунд.</span></div>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}else{
			if($captcha_type==1) {
				$allowed_symbols = "0123456789+-";
				$answers_arr = genSerfAnsw1();

				$_SESSION["captcha_load"] = 1;
				$_SESSION["captcha_type_serf"] = $captcha_type;
				$_SESSION["captcha_serf_sess"] = $answers_arr;

				$answer_session = ( isset($_SESSION["captcha_serf_sess"]) && preg_match("|^[$allowed_symbols]*$|", trim($_SESSION["captcha_serf_sess"])) ) ? strtolower(trim($_SESSION["captcha_serf_sess"])) : false;

				if($answer_session==false | $answers_arr==false) {
					$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Действие не произведено. [код:1]</span></div>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					$result_text = "OK";
					$message_text = '<div class="block-captcha" onclick="LoadCaptcha(); return false;"><img src="/captcha/?'.session_name().'='.session_id().'&sid='.mt_rand().'" align="absmiddle" alt="" title="Если символы не разборчивые, нажмите на капчу для обновления!" /></div>';
					$message_text.= '<div class="block-answers" style="padding-left:2px;"><img src="/style/icon-serf/icon-ravno.png" alt="равно" title="равно" border="0" align="absmiddle" /></div>';
					$message_text.= '<div class="block-answers" style="padding-left:0px;">';
						$message_text.= '<div style="margin:0; padding:0;">';
						for($i=1; $i<=8; $i++) {
							$verify_code = strrev(md5(md5($i).$token_sess));
							$message_text.= '<span class="answers" onclick="CheckVerify(\''.$verify_code.'\'); return false;">'.$i.'</span>';
							if($i==4) $message_text.= '</div><div style="margin:0; padding:0;">';
						}
						$message_text.= '</div>';
					$message_text.= '</div>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($captcha_type==2) {
				$allowed_symbols = "23456789abcdeghkmnpqsuvxyz";
				$count_answers = 6; #кратное 2
				$answers_arr = array_slice(genSerfAnsw2($allowed_symbols, $count_answers), 0, $count_answers);
				$answers_arr = is_array($answers_arr) && count($answers_arr)==$count_answers ? $answers_arr : false;

				$_SESSION["captcha_load"] = 1;
				$_SESSION["captcha_type_serf"] = $captcha_type;
				$_SESSION["captcha_serf_sess"] = $answers_arr[0];

				$answer_session = ( isset($_SESSION["captcha_serf_sess"]) && preg_match("|^[$allowed_symbols]*$|", trim($_SESSION["captcha_serf_sess"])) ) ? strtolower(trim($_SESSION["captcha_serf_sess"])) : false;

				if($answer_session==false | $answers_arr==false) {
					$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Действие не произведено. [код:1]</span></div>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					shuffle($answers_arr);

					$result_text = "OK";
					$message_text = '<div class="block-captcha" onclick="LoadCaptcha(); return false;"><img src="//'.$_SERVER["HTTP_HOST"].'/captcha/?'.session_name().'='.session_id().'&sid='.mt_rand().'" align="absmiddle" alt="" title="Если символы не разборчивые, нажмите на капчу для обновления!" /></div>';
					$message_text.= '<div class="block-answers">';
						$message_text.= '<div style="margin:0; padding:0;">';
						for($i=0; $i<$count_answers; $i++) {
							$verify_code = strrev(md5(md5($answers_arr[$i]).$token_sess));
							$message_text.= '<span class="answers-big" onclick="CheckVerify(\''.$verify_code.'\'); return false;">'.strtolower($answers_arr[$i]).'</span>';
							if(($i+1)==($count_answers/2)) $message_text.= '</div><div style="margin:0; padding:0;">';
						}
						$message_text.= '</div>';
					$message_text.= '</div>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}else{
				$_SESSION["captcha_load"] = 0;
				$result_text = "ERROR"; $message_text = '<div class="block-error">Ошибка!<span>Действие не произведено. [код:2]</span></div>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
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