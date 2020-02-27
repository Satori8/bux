<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	global $ajax_json;
	$message_text = false;
	$errfile = str_ireplace(ROOT_DIR, false, $errfile);
	switch($errno) {
		case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
		case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
		case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
		default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
	}
	exit(my_json_encode("ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($result_text, $message_text) {
	global $ajax_json;
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_ireplace(array("'","`"), "", $mensaje);
	$mensaje = str_ireplace('"', "&#34;", $mensaje);
	$mensaje = str_ireplace("?", "&#063;", $mensaje);
	$mensaje = str_ireplace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), ENT_QUOTES, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), ENT_QUOTES, "CP1251");
	$mensaje = str_ireplace("  ", " ", $mensaje);
	$mensaje = str_ireplace("&&", "&", $mensaje);
	$mensaje = str_ireplace("&#063;", "?", $mensaje);
	$mensaje = str_ireplace("&amp;", "&", $mensaje);

	return $mensaje;
}

$message_text="<script>
  var status_form = select_status = 0;
  
  function js_post(e, link, zapros, type_in = 'script', elem = '') {
  e_js_post = $(e);
  
  if(status_form == '0'){
    status_form = 1;
    $.ajax({      	
      url: link, type: 'POST', data: zapros, 
	  //dataType: type_in,
      error: function (infa){status_form = 0;
	  console.log(infa);},
      success: function (infa){
	  var data = JSON.parse(infa);
		$(e).show().html(data['html']);
		function func_close() {
  		$('#LoadModal').modalpopup('close');
		}setTimeout(func_close, 2000);
		
	  status_form = 0;
	 // console.log(infa);
      }
    });
  }
    
  return false;
  
}
</script>";

function UserStatus($reiting){
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='$reiting' AND `r_do`>='".floor($reiting)."'");
		if(mysql_num_rows($sql_rang)>0) {
			$row_rang = mysql_fetch_assoc($sql_rang);
		}else{
			$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id`");
			$row_rang = mysql_fetch_assoc($sql_rang);
		}
		return '<span style="cursor:pointer; color:#FF0000;" title="Статус">'.$row_rang["rang"].'</span>';
	}
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_cena' AND `howmany`='1'");
$shans_bonus_cena = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_reit' AND `howmany`='1'");
$shans_bonus_reit = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_reit_igr' AND `howmany`='1'");
$shans_bonus_reit_igr = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_sum' AND `howmany`='1'");
$shans_bonus_sum = mysql_result($sql,0,0);

$summa_igr=($shans_bonus_cena*50)/100*$shans_bonus_sum;

$sql_igr=mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='active'");
$shans=mysql_fetch_array($sql_igr);


	if(mysql_num_rows($sql_igr)==0){
	
			mysql_query("INSERT INTO `tb_bonus_shans_igr` (`user_priz`,`time`,`sum`,`status`,`ost`) VALUES('','".time()."','".$summa_igr."','active','50')") or die(mysql_error());
			$sql_igr_ok=mysql_fetch_array(mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='active'"));
		
		for($i=1; $i<=50; $i++){
		
			mysql_query("INSERT INTO `tb_bonus_shans` (`igr_shans`,`username`,`num`,`time`,`status`,`sum`) VALUES('".$sql_igr_ok['id']."','','".$i."','','active','".$shans_bonus_cena."')") or die(mysql_error());
			
		}
	}
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_cena' AND `howmany`='1'");
$shans_bonus_cena = mysql_result($sql,0,0);

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlentities(stripslashes(trim($_SESSION["userPas"]))) : false;

	$id = (isset($_POST["id"]) && is_string($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? intval(limpiarez($_POST["id"])) : false;
	$option = ( isset($_POST["op"]) && is_string($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["op"])) ) ? limpiarez($_POST["op"]) : false;
	$token_post = (isset($_POST["token"]) && is_string($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", limpiarez($_POST["token"]))) ? strtolower(limpiarez($_POST["token"])) : false;
	$security_key = "jbmiym,oi5//bmiyt";

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$sql_user = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='$username' AND md5(`password`)='$user_pass'");
		if(mysql_num_rows($sql_user) > 0) {
			$row_user = mysql_fetch_array($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
		}

		
	}
	
	if($option == "LoadBC") {
	    $token_check = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."LoadBC".$security_key));

		if($token_post == false | $token_post != $token_check) {
			$result_text = "ERROR";
			$message_text = "Ошибка доступа, обновите страницу!".$token_check."-".$token_post;
			exit(my_json_encode($result_text, $message_text));
		}else{
		    
		    $token_stat_ref1 = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."confirm_pay".$security_key));
		
$message_text='';
$message_text.= '<table class="tables">';
$message_text.= '<thead>';
$message_text.= '<tr>';
$message_text.= '<th style="text-align:center; width:80px;">Билет №</th>';
$message_text.= '<th style="text-align:center;" colspan="2">Пользователь</th>';
$message_text.= '<th style="text-align:center; width:180px;"></th>';
$message_text.= '</tr></thead>';
$sql_igr_ok=mysql_fetch_array(mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='active'"));		
$sql = mysql_query("SELECT * FROM `tb_bonus_shans` WHERE igr_shans='".$sql_igr_ok['id']."'  ORDER BY id ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		$user_name_i = $row["username"];
		$sum = $row["sum"];
		$num = $row["num"];

		if($user_name_i!=null){
		$sql_igr_us=mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$user_name_i'"));	
	$message_text.= '<tr id="sc-'.$num.'">';
$message_text.= '<td style="text-align:center;"><span class="sans">'.$num.'</span></td>';
$message_text.= '<td style="text-align:center; width:70px; border-right:none; padding:2px 5px;"><a href="/wall?uid='.$sql_igr_us['id'].'" target="_blank"><img src="avatar/'.$sql_igr_us['avatar'].'" class="avatar" style="width:50px; height:50px" border="0" alt="avatar" title="Перейти на стену пользователя '.$user_name_i.'"></a></td>';
$message_text.= '<td style="text-align:left; border-left:none; padding-left:0px;" colspan="2">';
$message_text.= '<div class="rw_login" style="text-align:left; margin-top:-3px; color:#000;"><span style="color:#696969;">ID:</span> '.$sql_igr_us['id'].'</div>';
$message_text.= '<div class="rw_login" style="text-align:left;"><span style="color:#696969;">Логин:</span> '.$user_name_i.'</div>';
$message_text.= '<div class="rw_status" style="text-align:left; margin-top:1px; font-weight:bold;"><span style="color:#696969;">Статус:</span> '.UserStatus($sql_igr_us["reiting"]).'</div>';
$message_text.= '</td>';
$message_text.= '</tr>';
		}else{
	$message_text.= '<tr id="sc-'.$num.'">';
$message_text.= '<td style="text-align:center;"><span class="sans">'.$num.'</span></td>';
$message_text.= '<td style="text-align:center;" colspan="2"></td>';
//echo '<td style="text-align:center;"><span class="sd_sub big blue" onclick="js_post(this, \'../ajax/ajax_shans.php\', \'func=buy_save&amp;id='.$num.'\');return false;">Мне повезёт</span></td>';
$message_text.= '<td style="text-align:center;"><span class="sd_sub big blue" onclick="FuncBC(\''.$num.'\', \'confirm_pay\', \''.$token_stat_ref1.'\', \'Подтверждение\');">Мне повезёт</span></span></td>';
$message_text.= '</tr>';
		}

	}
}
$message_text.= '</table>';

$result_text = "OK";
			exit(my_json_encode($result_text, $message_text));
		}			
	}elseif($option == "LoadBCH") {
	    
		$token_check = strtolower(md5($user_id.strtolower($user_name).$_SERVER["HTTP_HOST"]."LoadBCH".$security_key));

		if($token_post == false | $token_post != $token_check) {
			$result_text = "ERROR";
			$message_text = "Ошибка доступа, обновите страницу!!";
			exit(my_json_encode($result_text, $message_text));
		}else{
$message_text='';			
$message_text.= '<span class="msg-ok" style="margin:40px auto 10px;">Завершенные игры, последние 15 результатов</span>';
$message_text.= '<table class="tables">';
$message_text.= '<thead>';
$message_text.= '<tr>';
$message_text.= '<th style="text-align:center;" colspan="2">Пользователь</th>';
$message_text.= '<th style="text-align:center; width:120px;">Бонус-Удача №</th>';
$message_text.= '<th style="text-align:center; width:100px;">Билет №</th>';
$message_text.= '<th style="text-align:center; width:120px;">Дата</th>';
$message_text.= '<th style="text-align:center; width:120px;">Джекпот</th>';
$message_text.= '</tr>';
$message_text.= '</thead>';
$sql_igr_pos=mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='over' ORDER BY id DESC LIMIT 15");		
if(mysql_num_rows($sql)>0) {
	while ($row_p = mysql_fetch_array($sql_igr_pos)) {
	    $igr_pos = $row_p["id"];
		$user_priz = $row_p["user_priz"];
		$time = $row_p["time"];
		$sum = $row_p["sum"];
$sql_igr_us=mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$user_priz'"));	
$sql_sl=mysql_fetch_array(mysql_query("SELECT * FROM `tb_bonus_shans` WHERE `bonus`='ok' && igr_shans='".$row_p['id']."'"));
$message_text.= '<tr>';
$message_text.= '<td style="text-align:center; width:70px; border-right:none; padding:2px 5px;"><a href="/wall?uid='.$sql_igr_us['id'].'" target="_blank"><img src="avatar/'.$sql_igr_us['avatar'].'" class="avatar" style="width:50px; height:50px" border="0" alt="avatar" title="Перейти на стену пользователя BadBoy"></a></td>';
$message_text.= '<td style="text-align:left; border-left:none; padding-left:0px;">';
$message_text.= '<div class="rw_login" style="text-align:left; margin-top:-3px; color:#000;"><span style="color:#696969;">ID:</span> '.$sql_igr_us['id'].'</div>';
$message_text.= '<div class="rw_login" style="text-align:left;"><span style="color:#696969;">Логин:</span> '.$user_priz.'</div>';
$message_text.= '<div class="rw_status" style="text-align:left; margin-top:1px; font-weight:bold;"><span style="color:#696969;">Статус:</span> '.UserStatus($sql_igr_us["reiting"]).'</div>';
$message_text.= '</td>';
$message_text.= '<td style="text-align:center;"><b>'.$igr_pos.'</b></td>';
$message_text.= '<td style="text-align:center;"><b>'.$sql_sl['num'].'</b></td>';
$message_text.= '<td style="text-align:center;">'.date("Y-m-d H:i:s",$time).'</td>';
$message_text.= '<td style="text-align:center;"><span class="text-green"><b>'.$sum.'</b> руб.</span></td>';
$message_text.= '</tr>';
	}
}
$message_text.= '</table>';

$result_text = "OK";
			exit(my_json_encode($result_text, $message_text));
}
	}elseif($option == "confirm_pay") {
	$message_text='';	
$message_text.= '<div style="text-align:center; margin:10px 0px 15px 0px; line-height:18px;">С вашего <b>рекламного</b> счета будет списана сумма в размере <b class="text-green">'.$shans_bonus_cena.'</b> руб.<br>Купить билет <b>№'.$id.'</b>?</div>';
$message_text.= '<div style="text-align:center;"><span onclick="js_post(this, \'/ajax/ajax_shans.php\', \'func=buy_save&amp;id='.$id.'\', \'Информация\');"><span class="sd_sub green" style="min-width:30px;">Да</span></span>';
$message_text.= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onclick=\"$('#LoadModal').modalpopup('close'); return false;\">Нет</span></div>";

$result_text = "OK";
			exit(my_json_encode($result_text, $message_text));

	}else{
		$result_text = "ERROR"; $message_text = "Option[$option] not found...";
		exit(my_json_encode($result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX ответа.";
exit(my_json_encode($result_text, $message_text));

?>