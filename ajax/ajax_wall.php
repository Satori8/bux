<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text, $title_page=false, $page=false, $cnt=false, $gift=false) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text), "title_page" => iconv("CP1251", "UTF-8", $title_page), "page" => intval($page), "cnt" => iconv("CP1251", "UTF-8", $cnt), "gift" => iconv("CP1251", "UTF-8", $gift))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
//	$mensaje = str_replace('"', "&#34;", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));
//	$mensaje = str_replace("\\", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), NULL, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
	$mensaje = str_replace("  ", " ", $mensaje);
	$mensaje = str_replace("&amp amp ", "&", $mensaje);
	$mensaje = str_replace("&amp;amp;", "&", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("&#063;", "?", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);
	//$pattern = '/\[img\].*\[\/img\]/';
  //$mensaje = preg_replace($pattern,'',$mensaje);

	return $mensaje;
}

function stripImages($text) {
$text = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(</a>)/i', '$1$3$5<br />', $text);
$text = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $text);
$text = preg_replace('/<img[^>]*>/i', '', $text);    
return $text;
}

function clear_html_tags($text) {
	$text = trim($text);
	$text = preg_replace("([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", '<span class="text-red">e-mail вырезан</span>', $text);
	$text = preg_replace("'<script[^>]*?>.*?</script>'si", "", $text);
	$text = preg_replace("<a\s+href=['|\"]?([^\#]+?)['|\"]?\s.*>(?!<img).+?</a>#i", '<span class="text-red">ссылка вырезана</span>', $text);
	$text = preg_replace("http://[^\s]+#i", '<span class="text-red">ссылка вырезана</span>', $text);
	$text = preg_replace("https://[^\s]+#i", '<span class="text-red">ссылка вырезана</span>', $text);
	$text = preg_replace("www\.[-\d\w\._&\?=%]+#i", "", $text);
	return $text;
}

function smile($mes) {
	$mes = str_ireplace("<br><br>", "<br>", $mes);

	for($i=0; $i<=50; $i++) {
		$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-$i.gif\" alt=\"\" align=\"absmiddle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
	}
	return $mes;
}

function InfoStatus($user_id, $user_reiting, $user_ban_date){
	$InfoSatus = array();
	$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

	$sql_rang = mysql_query("SELECT `id`,`rang`,`wall_comm` FROM `tb_config_rang` WHERE `r_ot`<='".$user_reiting."' AND `r_do`>='".floor($user_reiting)."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_assoc($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT `id`,`rang`,`wall_comm` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$row_rang = mysql_fetch_assoc($sql_rang);
	}

	$InfoSatus["id"] = $row_rang["id"];
	$InfoSatus["wall_comm"] = $user_ban_date>0 ? 0 : $row_rang["wall_comm"];
	if($user_ban_date>0 && $user_id!=1) {
		$InfoSatus["rang"] = '<span style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; cursor:help; color: #FF0000; font-weight:bold; font-size:14px;" class="tipi" title="Заблокирован за нарушение правил проекта!">ШТРАФНИК</span>';
	}else{
		$InfoSatus["rang"] = '<span style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; cursor:help; color: #FF0000; font-weight:normal;" class="tipi" title="Статус">'.$row_rang["rang"].'</span>';
	}

	return $InfoSatus;
}

function InfoUser($user_id, $username) {
	$sql_i = mysql_query("SELECT `id`,`username`,`avatar`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_i)>0) {
		$row_i = mysql_fetch_assoc($sql_i);
		$info_user["wall_com"] = ($row_i["wall_com_p"] - $row_i["wall_com_o"]);

		if($info_user["wall_com"]>0) {
			$info_user["wall_com"] = '<b class="text-green">'.$info_user["wall_com"].'</b>';
		}elseif($info_user["wall_com"]<0) {
			$info_user["wall_com"] = '<b class="text-red">-'.abs($info_user["wall_com"]).'</b>';
		}else{
			$info_user["wall_com"] = '<b class="text-grey">0</b>';
		}

		$info_user["id"] = $row_i["id"];
		$info_user["username"] = $row_i["username"];
		$info_user["avatar"] = '<span onClick="LoadWall(\''.$info_user["id"].'\', \'true\');"><img src="avatar/'.$row_i["avatar"].'" class="wall-avatar" alt="" title="Перейти на стену пользователя '.$info_user["username"].'" /></span>';
		$info_user["wall_info"] = '<span class="comm-author"><b>'.$info_user["username"].'</b>, ID:<b>'.$info_user["id"].'</b></span>';
		$info_user["wall_info"].= '<span class="bg-wall" onClick="LoadWall(\''.$info_user["id"].'\', \'true\');" title="Перейти на стену пользователя '.$info_user["username"].'">'.$info_user["wall_com"].'</span>';
	}else{
		$info_user["id"] = false;
		$info_user["username"] = false;
		$info_user["avatar"] = '<img src="avatar/no.png" class="wall-avatar" alt="" title="" />';
		$info_user["wall_info"] = '<span class="comm-author">'.$info_user["username"].'</span> [<span class="text-red">пользователь удален</span>]';
	}

	return $info_user;
}

function LastLogin($online_arr, $user_search, $lastlogdate, $vac1, $vac2){
	if(strtolower($user_search)=="admin") {
		$LastLogin = false;
	}elseif(array_search($user_search, $online_arr)!==false) {
		$LastLogin = '<span style="color:coral; text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;">Online</span>';
	}else{
		if($vac1 > 0 && $vac1 > time()) {
			$LastLogin = '<span style="color:#D2691E;">в отпуске до '.DATE("d.m.Yг.", $vac1).'</span>';
		}elseif($lastlogdate == 0) {
			$LastLogin = '<span style="color:#FF0000;">не активен</span>';
		}elseif(DATE("d.m.Y", $lastlogdate) == DATE("d.m.Y")) {
			$LastLogin = '<span style="color:coral;">Сегодня в '.DATE("H:i", $lastlogdate).'</span>';
		}elseif(DATE("d.m.Y", $lastlogdate) == DATE("d.m.Y", (time()-24*60*60))) {
			$LastLogin = '<span style="color:#006699;">Вчера в '.DATE("H:i", $lastlogdate).'</span>';
		}else{
			$LastLogin = DATE("d.m.Yг. в H:i", $lastlogdate);
		}
	}
	return $LastLogin;
}

function SeachForm() {
	$SeachForm = false;
	$SeachForm.= '<div class="seach-form">';
		$SeachForm.= '<input id="seach-user-wall" type="text" value="" placeholder="Для поиска введите ID или Логин пользователя" maxlength="30" autocomplete="off" />';
		$SeachForm.= '<div class="seach-result"></div>';
	$SeachForm.= '</div>';
	return $SeachForm;
}

function ListComm($row_com, $InfoUser, $StatusDelCom, $StatusEditCom, $StatusAddAnsw, $StatusDelAnsw, $StatusEditAnsw, $user_name, $security_key) {
	$token_comm_edit = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."F_COMM_EDIT".$security_key));
	$token_comm_del  = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."COMM_DEL".$security_key));
	$token_answ_add  = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."F_ANSW_ADD".$security_key));
	$token_answ_del  = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."ANSW_DEL".$security_key));
	$token_answ_edit = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."F_ANSW_EDIT".$security_key));

	$ListComm = false;
	$ListComm.= '<table class="tables" style="margin:0px 0px 10px; border:1px solid #DDD; table-layout: fixed; width:100%;" id="wall-comment-'.$row_com["id"].'">';
	$ListComm.= '<tbody>';
	$ListComm.= '<tr>';
		$ListComm.= '<td style="border-top: 0px solid #CCC; border-right:none; padding:4px 5px 3px 5px;">';
			$ListComm.= '<span class="comm-'.($row_com["ocenka"]>0 ? "plus" : "minus").'"></span>';
			$ListComm.= '<span class="comm-info">'.$InfoUser["wall_info"].'</span>';
			$ListComm.= '<span class="comm-time">'.DATE("H:i d.m.Yг.", $row_com["time"]).'</span>';
		$ListComm.= '</td>';
	$ListComm.= '</tr>';
	$ListComm.= '<tr>';
		$ListComm.= '<td align="justify" valign="top" style="border:none; background:#FFFFF0; padding:6px 6px 6px 2px;">';
			$ListComm.= '<div style="float: left; margin-left:7px;">'.$InfoUser["avatar"].'</div>';
			if($StatusDelCom==1 | $StatusEditCom==1 | $StatusAddAnsw==1) {
				$ListComm.= '<div style="float: right;">';
					if($StatusDelCom==1)  $ListComm.= '<span onClick="DelComWall(\''.$row_com["id"].'\', \''.$token_comm_del.'\');" class="f-del" title="Удалить отзыв"></span>';
					if($StatusEditCom==1) $ListComm.= '<span onClick="LoadForm(\''.$row_com["id"].'\', \''.$row_com["user_id"].'\', \'FormEditCom\', \''.$token_comm_edit.'\');" class="f-edit" title="Редактировать отзыв"></span>';
					if($StatusAddAnsw==1) $ListComm.= '<span id="sub-comm-'.$row_com["id"].'" onClick="LoadForm(\''.$row_com["id"].'\', \''.$row_com["user_id"].'\', \'FormAddAnsw\', \''.$token_answ_add.'\');" class="f-addcom" title="Ответить на отзыв" style="display:'.(trim($row_com["comment_answ"])==false ? "block" : "none").';"></span>';
				$ListComm.= '</div>';
			}

			$ListComm.= '<div style="margin-left:73px; margin-right: '.(3+$StatusDelCom*20 + $StatusEditCom*20 + $StatusAddAnsw*20).'px; word-wrap: break-word;">';
				$ListComm.= smile(desc_bb($row_com["comment"]));
				$ListComm.= '<div id="wall-answ-'.$row_com["id"].'">';
					if(trim($row_com["comment_answ"]) != false) {
						$ListComm.= '<div class="wall_answer">';
							if($StatusDelAnsw==1 | $StatusEditAnsw==1) {
								$ListComm.= '<div align="right" style="float: right;">';
									if($StatusDelAnsw==1) $ListComm.= '<span onClick="DelAnswWall(\''.$row_com["id"].'\', \''.$token_answ_del.'\');" class="f-del" title="Удалить ответ"></span>';
									if($StatusEditAnsw==1) $ListComm.= '<span onClick="LoadForm(\''.$row_com["id"].'\', \''.$row_com["user_id"].'\', \'FormEditAnsw\', \''.$token_answ_edit.'\');" class="f-edit" title="Редактировать ответ на отзыв"></span>';
								$ListComm.= '</div>';
							}
							$ListComm.= '<span class="wall_answer_title">Ответ владельца аккаунта:</span>';
							$ListComm.= '<span class="wall_answer_text">'.smile(desc_bb($row_com["comment_answ"])).'</span>';
						$ListComm.= '</div>';
					}
				$ListComm.= '</div>';
			$ListComm.= '</div>';
		$ListComm.= '</td>';
	$ListComm.= '</tr>';
	$ListComm.= '</tbody>';
	$ListComm.= '</table>';

	return $ListComm;
}

function ListGifts($FOLDER_GIFTS, $row_gift, $user_name, $user_status) {
	$ListGifts = false;
	$ListGifts.= '<div class="wall-gift">';
		$ListGifts.= '<img src="'.$FOLDER_GIFTS.$row_gift["img_gift"].'" alt="" />';
		if($user_status==1 | $row_gift["privat_gift"]==0 | ($row_gift["privat_gift"]==1 && strtolower($row_gift["user_name"])==strtolower($user_name)) | ($row_gift["privat_gift"]==1 && strtolower($row_gift["user_name_gift"])==strtolower($user_name))) {
			$ListGifts.= '<div class="info-gift">';
				$ListGifts.= '<div style="height:18px;"><div class="author-gift">'.$row_gift["user_name_gift"].'</div><div class="date-gift">'.DATE("d.m.Y в H:i", $row_gift["time"]).'</div></div>';
				if(trim($row_gift["comment"])!=false) $ListGifts.= '<div class="comm-gift">'.nl2br(trim($row_gift["comment"])).'</div>';
			$ListGifts.= '</div>';
		}
	$ListGifts.= '</div>';

	return $ListGifts;
}

function BoxModal($title, $content, $width=false, $height=false) {
	$BoxModal = false;
	$BoxModal.= '<div class="box-modal" style="text-align:justify; '.($width!=false ? "width: $width;" : false).' '.($height!=false ? "min-height: $height;" : false).'">';
		$BoxModal.= '<div class="box-modal-title">'.$title.'</div>';
		$BoxModal.= '<div class="box-modal-close modalpopup-close"></div>';
		$BoxModal.= '<div class="box-modal-content" style="margin:0 auto; padding:8px 12px; font-size:13px; text-align:justify;">';
			$BoxModal.= $content;
		$BoxModal.= '</div>';
	$BoxModal.= '</div>';
	return $BoxModal;
}

function desc_bb($desc) {
	$desc = new bbcode($desc);
	$desc = $desc->get_html();
	$desc = str_replace("&amp;", "&", $desc);
	return $desc;
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
	$message_text = "$message_text";
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");
	require_once(ROOT_DIR."/merchant/func_mysql.php");
	require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
	$wall_user_id = ( isset($_POST["uid"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["uid"]))) ) ? intval(trim($_POST["uid"])) : false;
	$token_post = (isset($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_POST["token"]))) ? strtolower(limpiarez($_POST["token"])) : false;
	$my_lastiplog = getRealIP();
	$security_key = "Ulnli^&*@if%Ylkj630n(*7p0UFn#*hglkj?";
	$perpage = 20;
	$LIMIT_SEACH = 100;
	$COUNT_GIFTS = 1060;
	$wallcom_count_text = 500;
	$gift_count_text = 200;
	$FOLDER_GIFTS = "/img/gifts/";
	$sex_arr = array(1 => "Мужчина", 2 => "Женщина");
	$status_razdel = 1;

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$sql_user = mysql_query("SELECT `id`,`username`,`imay`,`referer`,`money_rb`,`reiting`,`user_status`,`ban_date` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_imay = $row_user["imay"];
			$user_money_rb = $row_user["money_rb"];
			$user_referer = $row_user["referer"];
			$user_reit = $row_user["reiting"];
			$user_ban_date = $row_user["ban_date"];
			$user_status = $row_user["user_status"];
		}else{
			$user_id = false;
			$user_name = false;
			$user_imay = false;
			$user_money_rb = false;
			$user_referer = false;
			$user_reit = false;
			$user_ban_date = false;
			$user_status = false;
		}
	}else{
		$user_id = false;
		$user_name = false;
		$user_imay = false;
		$user_money_rb = false;
		$user_referer = false;
		$user_reit = false;
		$user_ban_date = false;
		$user_status = false;
	}

	if($status_razdel==0 && $user_status!=1) {
		$result_text = "ERROR-ID";
		$message_text = '<span class="msg-w">Обновление ПО</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

	if($option == "LoadWall") {
		$message_text = false;

		$sql = mysql_query("SELECT * FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$wall_user_imay = $row["imay"];
			$token_stat = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."STAT".$security_key));
			$token_comm = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."COMM".$security_key));
			$token_add_ref = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ADD_REF".$security_key));
			$token_gift = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_ADD_GIFT".$security_key));
			$token_konk_info = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."KONK_INFO".$security_key));
			$token_bonus_info = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."BONUS_INFO".$security_key));
			$token_info_edit = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_EDIT_INFO".$security_key));
			$InfoStatus = InfoStatus($row["id"], $row["reiting"], $row["ban_date"]);
			$count_all_task = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `username`='".$row["username"]."' AND `status`='pay' AND `totals`>'0'")));
			$count_all_konk = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='".$row["username"]."' AND `date_s`<='".time()."' AND `date_e`>='".time()."'")));
			$count_all_bonus = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `status`='1' AND `username`='".$row["username"]."'")));
			$count_all_konk_adm = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_konkurs_rezult` WHERE `username`='".$row["username"]."' AND `summa_array`!='Не выполнены условия конкурса'")));
			$count_all_konk_adm_kon = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_history` WHERE `user`='".$row["username"]."' AND `type`='1'")));
			$count_all_konk_ref_kon = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_history` WHERE `user`='".$row["username"]."' AND `type`='2'")));
			$count_all_konk_board_kon = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_history` WHERE `user`='".$row["username"]."' AND `type`='3'")));

			$My_InfoStatus = ($user_name!=false) ? InfoStatus($user_id, $user_reit, $user_ban_date) : array("wall_comm" => "0");
			$StatusAddCom = ($user_name!=false && strtolower($user_name)!=strtolower($wall_user_name) && $My_InfoStatus["wall_comm"]==1) ? 1 : 0;
			
			//$refview = mysql_query("SELECT * FROM `tb_refview` WHERE `name`='".$row["username"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			//$prodawec = mysql_fetch_array($refview);
			
			//$refvie = mysql_query("SELECT * FROM `tb_refview` WHERE `namep`='".$row["username"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			//$pocupatel = mysql_fetch_array($refvie);
			
			$refview = mysql_query("SELECT sum(cena) FROM `tb_refview` WHERE `name`='".$row["username"]."' ");
	        $prodawec = mysql_result($refview,0,0);
			
			$refvie = mysql_query("SELECT sum(cena) FROM `tb_refview` WHERE `namep`='".$row["username"]."' ");
	        $pocupatel = mysql_result($refvie,0,0);

			$top_mesto = 0;
			$sql_r = mysql_query("SELECT `id` FROM `tb_users` WHERE `id`!='1' ORDER BY `reiting` DESC");
			if(mysql_num_rows($sql_r)>0) {
				while ($row_r = mysql_fetch_assoc($sql_r)) {
					$top_mesto++;
					if($row_r["id"]==$wall_user_id) break;
				}
			}

			$sql_o = mysql_query("SELECT `username` FROM `tb_online` WHERE `username`='$wall_user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_o)>0) {
				while ($row_o = mysql_fetch_assoc($sql_o)) { $online_arr[] = strtolower($row_o["username"]); }
			}else{
				$online_arr = array();
			}

			$message_text.= SeachForm();

			$message_text.= '<span id="cat-title-info" class="cat-title-open" onClick="ShowHideBlock(\'info\'); return false;">Информация о пользователе</span>';
			$message_text.= '<div id="cat-block-info" style="display:block;">';
			if( strtolower($user_name) == strtolower($wall_user_name) ) {
            $message_text .= "<div class=\"wall-userinfo_ref\">Ссылка для серфинга и заданий:&emsp;<input style=\"color:#FF0000;width:360px;text-align:center;margin:3px;padding:3px;type=\" text\"=\"\" value=\"https://".$_SERVER['HTTP_HOST']."/wall?uid=".$row["id"]."\" onfocus=\"this.select();\" readonly=\"\" class=\"ok\"></div>";
            $message_text .= "</br>";
            }else{
            }
				$message_text.= '<table class="tab-wall">';
				$message_text.= '<tbody>';
				$message_text.= '<tr>';
					$message_text.= '<td align="center" valign="top" width="100">';
						$message_text.= '<img class="avatar" src="/avatar/'.$row["avatar"].'" alt="" title="" style="margin-left:5px;" />';
						if($row["id"]!=1) {
							$message_text.= '<a href="/status.php" class="user-status-'.$InfoStatus["id"].'" title="Рейтинг: '.p_floor($row["reiting"], 2).'">'.p_floor($row["reiting"], 0).'</a>';

							$dostig = false;

							if($row["visits"]>=20000) {
								$dostig.= '<div class="progress_style stat_5" title="Кликер (Изумруд)"><img src="/img/progress/clicker.png"></div>';
							}elseif($row["visits"]>=10000) {
								$dostig.= '<div class="progress_style stat_4" title="Кликер (Платина)"><img src="/img/progress/clicker.png"></div>';
							}elseif($row["visits"]>=5000) {
								$dostig.= '<div class="progress_style stat_3" title="Кликер (Золото)"><img src="/img/progress/clicker.png"></div>';
							}elseif($row["visits"]>=1000) {
								$dostig.= '<div class="progress_style stat_2" title="Кликер (Серебро)"><img src="/img/progress/clicker.png"></div>';
							}elseif($row["visits"]>=100) {
								$dostig.= '<div class="progress_style stat_1" title="Кликер (Бронза)"><img src="/img/progress/clicker.png"></div>';
							}

							if($row["visits_t"]>=1000) {
								$dostig.= '<div class="progress_style stat_5" title="Задания (Изумруд)"><img src="/img/progress/tasker.png"></div>';
							}elseif($row["visits_t"]>=700) {
								$dostig.= '<div class="progress_style stat_4" title="Задания (Платина)"><img src="/img/progress/tasker.png"></div>';
							}elseif($row["visits_t"]>=500) {
								$dostig.= '<div class="progress_style stat_3" title="Задания (Золото)"><img src="/img/progress/tasker.png"></div>';
							}elseif($row["visits_t"]>=300) {
								$dostig.= '<div class="progress_style stat_2" title="Задания (Серебро)"><img src="/img/progress/tasker.png"></div>';
							}elseif($row["visits_t"]>=100) {
								$dostig.= '<div class="progress_style stat_1" title="Задания (Бронза)"><img src="/img/progress/tasker.png"></div>';
							}

							if($row["visits_m"]>=2000) {
								$dostig.= '<div class="progress_style stat_5" title="Письма (Изумруд)"><img src="/img/progress/mailser.png"></div>';
							}elseif($row["visits_m"]>=1000) {
								$dostig.= '<div class="progress_style stat_4" title="Письма (Платина)"><img src="/img/progress/mailser.png"></div>';
							}elseif($row["visits_m"]>=700) {
								$dostig.= '<div class="progress_style stat_3" title="Письма (Золото)"><img src="/img/progress/mailser.png"></div>';
							}elseif($row["visits_m"]>=500) {
								$dostig.= '<div class="progress_style stat_2" title="Письма (Серебро)"><img src="/img/progress/mailser.png"></div>';
							}elseif($row["visits_m"]>=100) {
								$dostig.= '<div class="progress_style stat_1" title="Письма (Бронза)"><img src="/img/progress/mailser.png"></div>';
							}

							if($row["referals"]>=5000) {
								$dostig.= '<div class="progress_style stat_5" title="Рефовод (Изумруд)"><img src="/img/progress/refovod.png"></div>';
							}elseif($row["referals"]>=3000) {
								$dostig.= '<div class="progress_style stat_4" title="Рефовод (Платина)"><img src="/img/progress/refovod.png"></div>';
							}elseif($row["referals"]>=1000) {
								$dostig.= '<div class="progress_style stat_3" title="Рефовод (Золото)"><img src="/img/progress/refovod.png"></div>';
							}elseif($row["referals"]>=500) {
								$dostig.= '<div class="progress_style stat_2" title="Рефовод (Серебро)"><img src="/img/progress/refovod.png"></div>';
							}elseif($row["referals"]>=100) {
								$dostig.= '<div class="progress_style stat_1" title="Рефовод (Бронза)"><img src="/img/progress/refovod.png"></div>';
							}

							if($row["money_rek"]>=20000) {
								$dostig.= '<div class="progress_style stat_5" title="Рекламодатель (Изумруд)"><img src="/img/progress/advertisers.png"></div>';
							}elseif($row["money_rek"]>=10000) {
								$dostig.= '<div class="progress_style stat_4" title="Рекламодатель (Платина)"><img src="/img/progress/advertisers.png"></div>';
							}elseif($row["money_rek"]>=5000) {
								$dostig.= '<div class="progress_style stat_3" title="Рекламодатель (Золото)"><img src="/img/progress/advertisers.png"></div>';
							}elseif($row["money_rek"]>=3000) {
								$dostig.= '<div class="progress_style stat_2" title="Рекламодатель (Серебро)"><img src="/img/progress/advertisers.png"></div>';
							}elseif($row["money_rek"]>=1000) {
								$dostig.= '<div class="progress_style stat_1" title="Рекламодатель (Бронза)"><img src="/img/progress/advertisers.png"></div>';
							}

							if($row["visits_tests"]>=1000) {
								$dostig.= '<div class="progress_style stat_5" title="Исполнитель тестов (Изумруд)"><img src="/img/progress/tester.png"></div>';
							}elseif($row["visits_tests"]>=700) {
								$dostig.= '<div class="progress_style stat_4" title="Исполнитель тестов (Платина)"><img src="/img/progress/tester.png"></div>';
							}elseif($row["visits_tests"]>=500) {
								$dostig.= '<div class="progress_style stat_3" title="Исполнитель тестов (Золото)"><img src="/img/progress/tester.png"></div>';
							}elseif($row["visits_tests"]>=300) {
								$dostig.= '<div class="progress_style stat_2" title="Исполнитель тестов (Серебро)"><img src="/img/progress/tester.png"></div>';
							}elseif($row["visits_tests"]>=100) {
								$dostig.= '<div class="progress_style stat_1" title="Исполнитель тестов (Бронза)"><img src="/img/progress/tester.png"></div>';
							}

							if(($row["money"]+$row["money_out"])>=10000) {
								$dostig.= '<div class="progress_style stat_5" title="Много заработал (Изумруд)"><img src="/img/progress/moneysers.png"></div>';
							}elseif(($row["money"]+$row["money_out"])>=5000) {
								$dostig.= '<div class="progress_style stat_4" title="Много заработал (Платина)"><img src="/img/progress/moneysers.png"></div>';
							}elseif(($row["money"]+$row["money_out"])>=1000) {
								$dostig.= '<div class="progress_style stat_3" title="Много заработал (Золото)"><img src="/img/progress/moneysers.png"></div>';
							}elseif(($row["money"]+$row["money_out"])>=500) {
								$dostig.= '<div class="progress_style stat_2" title="Много заработал (Серебро)"><img src="/img/progress/moneysers.png"></div>';
							}elseif(($row["money"]+$row["money_out"])>=100) {
								$dostig.= '<div class="progress_style stat_1" title="Много заработал (Бронза)"><img src="/img/progress/moneysers.png"></div>';
							}
							
							if($prodawec>=5000) {
								$dostig.= '<div class="progress_style stat_5" title="Торговец рефералами (Изумруд)"><img src="/img/progress/refbirj_prod.png"></div>';
							}elseif($prodawec>=3000) {
								$dostig.= '<div class="progress_style stat_4" title="Торговец рефералами (Платина)"><img src="/img/progress/refbirj_prod.png"></div>';
							}elseif($prodawec>=2000) {
								$dostig.= '<div class="progress_style stat_3" title="Торговец рефералами (Золото)"><img src="/img/progress/refbirj_prod.png"></div>';
							}elseif($prodawec>=1000) {
								$dostig.= '<div class="progress_style stat_2" title="Торговец рефералами (Серебро)"><img src="/img/progress/refbirj_prod.png"></div>';
							}elseif($prodawec>=500) {
								$dostig.= '<div class="progress_style stat_1" title="Торговец рефералами (Бронза)"><img src="/img/progress/refbirj_prod.png"></div>';
							}							
							
							if($pocupatel>=5000) {
								$dostig.= '<div class="progress_style stat_5" title="Покупатель рефералов (Изумруд)"><img src="/img/progress/refbirj_pok.png"></div>';
							}elseif($pocupatel>=3000) {
								$dostig.= '<div class="progress_style stat_4" title="Покупатель рефералов (Платина)"><img src="/img/progress/refbirj_pok.png"></div>';
							}elseif($pocupatel>=2000) {
								$dostig.= '<div class="progress_style stat_3" title="Покупатель рефералов (Золото)"><img src="/img/progress/refbirj_pok.png"></div>';
							}elseif($pocupatel>=1000) {
								$dostig.= '<div class="progress_style stat_2" title="Покупатель рефералов (Серебро)"><img src="/img/progress/refbirj_pok.png"></div>';
							}elseif($pocupatel>=500) {
								$dostig.= '<div class="progress_style stat_1" title="Покупатель рефералов (Бронза)"><img src="/img/progress/refbirj_pok.png"></div>';
							}

							if($count_all_konk_adm >= 100) {
								$dostig.= '<div class="progress_style stat_5" title="Участник конкурсов (Изумруд)"><img src="/img/progress/konkurser.png"></div>';
							}elseif($count_all_konk_adm >= 50) {
								$dostig.= '<div class="progress_style stat_4" title="Участник конкурсов (Платина)"><img src="/img/progress/konkurser.png"></div>';
							}elseif($count_all_konk_adm >= 25) {
								$dostig.= '<div class="progress_style stat_3" title="Участник конкурсов (Золото)"><img src="/img/progress/konkurser.png"></div>';
							}elseif($count_all_konk_adm >= 10) {
								$dostig.= '<div class="progress_style stat_2" title="Участник конкурсов (Серебро)"><img src="/img/progress/konkurser.png"></div>';
							}elseif($count_all_konk_adm >= 5) {
								$dostig.= '<div class="progress_style stat_1" title="Участник конкурсов (Бронза)"><img src="/img/progress/konkurser.png"></div>';
							}
							
							$num_forum = mysql_num_rows(mysql_query("SELECT * FROM `tb_forum_p` WHERE  `username`='$wall_user_name'"));
				
				if( 5000 <= $num_forum) {
                    $dostig .= "<div class=\"progress_style stat_5\" title=\"Активист форума (Изумруд)\"><img src=\"/img/progress/forum.png\"></div>";
                }else{
                    if( 3000 <= $num_forum) {
                        $dostig .= "<div class=\"progress_style stat_4\" title=\"Активист форума (Платина)\"><img src=\"/img/progress/forum.png\"></div>";
                    }else{
                        if( 1000 <= $num_forum) {
                            $dostig .= "<div class=\"progress_style stat_3\" title=\"Активист форума (Золото)\"><img src=\"/img/progress/forum.png\"></div>";
                        }else{
                            if( 500 <= $num_forum) {
                                $dostig .= "<div class=\"progress_style stat_2\" title=\"Активист форума (Серебро)\"><img src=\"/img/progress/forum.png\"></div>";
                            }else{
                                if( 100 <= $num_forum) {
                                    $dostig .= "<div class=\"progress_style stat_1\" title=\"Активист форума (Бронза)\"><img src=\"/img/progress/forum.png\"></div>";
                                }

                            }

                        }

                    }

                }

							if($dostig!=false) {
								$message_text.= '<a href="/progress.php" title="Какие бывают достижения"><span align="center" style="display:block; width:85px; padding-top:0px; color:#007FFF; font-weight: bold; border-bottom: 1px solid #DDD;">Достижения</span></a>';
								$message_text.= '<div align="center" style="overflow:auto; display:block; width:85px; max-height:220px;">'.$dostig.'</div>';
							}
						}
					$message_text.= '</td>';
					$message_text.= '<td align="center" valign="top">';
						$message_text.= '<table class="tab-wall">';
						$message_text.= '<tbody>';
						$message_text.= '<tr>';
							$message_text.= '<td class="td-wall" width="200">ID</td>';
							$message_text.= '<td class="td-wall"><a class="vp-mail" href="/newmsg.php?name='.$row["username"].'" target="_blank" title="Написать пользователю"></a>'.$row["id"].'</td>';
						$message_text.= '</tr>';
						$message_text.= '<tr>';
							$message_text.= '<td class="td-wall">Логин пользователя</td>';
							$message_text.= '<td class="td-wall"><span style="color:#ab0606; text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; font-weight:bold;">'.$row["username"].'</span> ['.($row["imay"] != false ? $row["imay"] : "-").']</td>';
						$message_text.= '</tr>';
						$message_text.= '<tr>';
							$message_text.= '<td class="td-wall">Статус</td>';
							$message_text.= '<td class="td-wall">'.($row["id"]==1 ? '<span style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA; color: #FF0000; font-weight:bold;" class="tipi" title="Статус">Администратор</span>' : $InfoStatus["rang"]).'</td>';
						$message_text.= '</tr>';
						if($row["id"]!=1) {
							if($row["forum_status"]>=1 && $row["forum_status"]<=3) {
								$status_f_arr = array('Пользователь','Администратор','Модератор','Арбитр');
								$message_text.= '<tr>';
									$message_text.= '<td class="td-wall">Должность</td>';
									$message_text.= '<td class="td-wall"><span class="text-green">'.$status_f_arr[$row["forum_status"]].'</span></td>';
								$message_text.= '</tr>';
							}
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Место в ТОП</td>';
								$message_text.= '<td class="td-wall">'.$top_mesto.'</td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Регистрация</td>';
								$message_text.= '<td class="td-wall">'.DATE("d.m.Yг. в H:i", $row["joindate2"]).'</td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Последний визит</td>';
								$message_text.= '<td class="td-wall">'.LastLogin($online_arr, strtolower($row["username"]), $row["lastlogdate2"], $row["vac1"], $row["vac2"]).'</td>';
							$message_text.= '</tr>';
							if(isset($sex_arr[$row["sex"]])) {
								$message_text.= '<tr>';
									$message_text.= '<td class="td-wall">Пол участника</td>';
									$message_text.= '<td class="td-wall">'.$sex_arr[$row["sex"]].'</td>';
								$message_text.= '</tr>';
							}
							if($row["country_cod"] != false) {
								$message_text.= '<tr>';
									$message_text.= '<td class="td-wall">Страна</td>';
									$message_text.= '<td class="td-wall"><img src="//'.$_SERVER['HTTP_HOST'].'/img/flags/'.strtolower($row["country_cod"]).'.gif" border="0" alt="" class="tipi" title="'.get_country($row["country_cod"]).'" style="margin:0; padding:0;" align="absmiddle" /> '.get_country($row["country_cod"]).'</td>';
								$message_text.= '</tr>';
							}
							if(trim($row["http_ref"]) != false) {
								$message_text.= '<tr>';
									$message_text.= '<td class="td-wall">Откуда пришел</td>';
									$message_text.= '<td class="td-wall">'.$row["http_ref"].'</td>';
								$message_text.= '</tr>';
							}
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Задания пользователя</td>';
								$message_text.= '<td class="td-wall">'.($count_all_task > 0 ? '<span style="color: #085C71">Всего: '.$count_all_task.' [<span class="info-task" onClick="window.open(\'/view_task.php?page=task&task_search=4&task_name='.$row["id"].'\');">подробнее</span>]</span>' : '<span style="color: #C80000">Нет</span>').'</td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Репутация исполнителя</td>';
								$message_text.= '<td class="td-wall"><img class="tipi" src="/img/task_16x16.png" border="0" alt="" title="Репутация исполнителя" style="margin:0; padding:0; margin-right:5px;" align="absmiddle" /><span class="'.($row["rep_task"]==0 ? "text-gray" : ($row["rep_task"]>0 ? "text-green" : "text-red")).'">'.($row["rep_task"]>0 ? "+" : false).$row["rep_task"].'</span></td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Активные конкурсы</td>';
								$message_text.= '<td class="td-wall">'.($count_all_konk > 0 ? '<span style="color: #085C71">Всего: '.$count_all_konk.' [<span class="info-kon" onClick="LoadForm(\'false\', \''.$row["id"].'\', \'FormInfoKonk\', \''.$token_konk_info.'\');">подробнее</span>]</span>' : '<span style="color: #C80000">Нет</span>').'</td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Активные бонусы</td>';
								$message_text.= '<td class="td-wall">'.($count_all_bonus > 0 ? '<span style="color: #085C71">Всего: '.$count_all_bonus.' [<span class="info-kon" onClick="LoadForm(\'false\', \''.$row["id"].'\', \'FormInfoBon\', \''.$token_bonus_info.'\');">подробнее</span>]</span>' : '<span style="color: #C80000">Нет</span>').'</td>';
							$message_text.= '</tr>';
							if($row["referer"]!=false) {
								$message_text.= '<tr>';
									$message_text.= '<td class="td-wall">Реферер</td>';
									$message_text.= '<td class="td-wall"><span class="wall-cnt">'.$row["referer"].'</span></td>';
								$message_text.= '</tr>';
							}
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Рефералов</td>';
								$message_text.= '<td class="td-wall"><span id="cnt-ref1" class="wall-cnt">'.$row["referals"].'</span> - <span id="cnt-ref2" class="wall-cnt">'.$row["referals2"].'</span> - <span id="cnt-ref3" class="wall-cnt">'.$row["referals3"].'</span></td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Авторефбэк</td>';
								$message_text.= '<td class="td-wall"><span class="wall-cnt">'.$row["ref_back_all"].' %</span></td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Выиграл конкурсов от системы</td>';
								$message_text.= '<td class="td-wall"><span style="color:#C80000;">'.number_format($count_all_konk_adm_kon,0,".","'").'</span></td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Выиграл конкусов от реферера</td>';
								$message_text.= '<td class="td-wall"><span style="color:#C80000;">'.number_format($count_all_konk_ref_kon,0,".","'").'</span></td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td class="td-wall">Побед на доске почета</td>';
								$message_text.= '<td class="td-wall"><span style="color:#C80000;">'.number_format($count_all_konk_board_kon,0,".","'").'</span></td>';
							$message_text.= '</tr>';
						}
						$message_text.= '</tbody>';
						$message_text.= '</table>';
					$message_text.= '</td>';
				$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center" style="padding-bottom:7px;">';
					if($StatusAddCom==1) $message_text.= '<span class="wall-sub" onClick="LoadForm(\'false\', \''.$wall_user_id.'\', \'FormAddCom\', \''.$token_comm.'\');">Оставить отзыв</span>';
					if($user_name == false) {
						$message_text.= '<span class="wall-sub" onClick="document.location.href=\'/register?r='.$row["id"].'\'">Вступить в мою команду</span>';
					}elseif($user_name != false && strtolower($user_name) != strtolower($wall_user_name) && $user_referer == false) {
						$message_text.= '<span id="AddRef-'.$wall_user_id.'" class="wall-sub" onClick="AddReferer(\''.$wall_user_id.'\', \''.$wall_user_name.'\', \''.$token_add_ref.'\');">Вступить в мою команду</span>';
					}
					if($user_name != false && strtolower($user_name) != strtolower($wall_user_name)) $message_text.= '<span class="wall-sub" onClick="LoadForm(\'false\', \''.$wall_user_id.'\', \'FormAddGift\', \''.$token_gift.'\');">Сделать подарок</span>';
				$message_text.= '</div>';

			$message_text.= '</div>';

			$StatusInfo = (trim($row["wall_my_info"])!=false | ($user_name!=false && strtolower($wall_user_name)==strtolower($user_name))) ? 1 : 0;
			$StatusEditInfo = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($wall_user_name)==strtolower($user_name) && $user_ban_date==0)) ? 1 : 0;

			if($StatusInfo == 1) {
				$message_text.= '<span id="cat-title-userinfo" class="cat-title-open" onClick="ShowHideBlock(\'userinfo\'); return false;">Комментарий владельца стены</span>';
				$message_text.= '<div id="cat-block-userinfo" style="display:block;">';
					$message_text.= '<div class="wall-userinfo">';
						if($StatusEditInfo==1) $message_text.= '<div style="float: right;"><span onClick="LoadForm(\'false\', \''.$wall_user_id.'\', \'FormInfoWall\', \''.$token_info_edit.'\');" class="f-edit" title="Редактировать комментарий"></span></div>';
						$message_text.= '<div id="userinfo-'.$wall_user_id.'" style="margin-right:'.($StatusEditInfo==1 ? "21px" : "0px").';">'.(trim($row["wall_my_info"])!=false ? smile(desc_bb($row["wall_my_info"])) : false).'</div>';
					$message_text.= '</div>';
				$message_text.= '</div>';
			}

			$sql_gift = mysql_query("SELECT * FROM `tb_users_gifts` WHERE `user_id`='$wall_user_id' ORDER BY `id` DESC LIMIT 100") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cnt_gifts = mysql_num_rows($sql_gift);
			if($cnt_gifts > 0) {
				$message_text.= '<div id="usergifts-'.$wall_user_id.'" style="display:block;">';
					$message_text.= '<span id="cat-title-usergifts" class="cat-title-open" onClick="ShowHideBlock(\'usergifts\'); return false;">Подарки, всего: '.$cnt_gifts.'</span>';
					$message_text.= '<div id="cat-block-usergifts" style="display:block;">';
						$message_text.= '<div id="wall-usergifts">';
						while($row_gift = mysql_fetch_assoc($sql_gift)) {
							$message_text.= ListGifts($FOLDER_GIFTS, $row_gift, $user_name, $user_status);
						}
						$message_text.= '</div>';
					$message_text.= '</div>';
				$message_text.= '</div>';
			}else{
				$message_text.= '<div id="usergifts-'.$wall_user_id.'" style="display:none;">';
					$message_text.= '<span id="cat-title-usergifts" class="cat-title-open" onClick="ShowHideBlock(\'usergifts\'); return false;">Подарки</span>';
					$message_text.= '<div id="cat-block-usergifts" style="display:block;">';
						$message_text.= '<div id="wall-usergifts"></div>';
					$message_text.= '</div>';
				$message_text.= '</div>';
			}

			$message_text.= '<span id="cat-title-stat" class="cat-title-close" onClick="LoadStat(\''.$row["id"].'\', \''.$token_stat.'\'); return false;">Статистика пользователя</span>';
			$message_text.= '<div id="cat-block-stat" style="display:none; margin: 10px 0 15px;"></div>';

			$message_text.= '<span id="cat-title-comm" class="cat-title-open" onClick="ShowHideBlock(\'comm\'); return false;">Отзывы о пользователе</span>';
			$message_text.= '<div id="cat-block-comm" style="display:block;">';
				$message_text.= '<div id="cnt-comm" class="cnt-comm" style="'.(($row["wall_com_p"]>0 | $row["wall_com_o"]>0) ? "display:block;" : "display:none;").'">';
					$message_text.= '<span class="cnt-comm-all">Всего отзывов: <span id="cnt-comm-all">'.($row["wall_com_p"]+abs($row["wall_com_o"])).'</span></span><br>';
					$message_text.= '<span class="cnt-comm-plus">Положительных: <span id="cnt-comm-plus">'.$row["wall_com_p"].'</span></span>';
					$message_text.= '<span class="cnt-comm-minus">Отрицательных: <span id="cnt-comm-minus">'.$row["wall_com_o"].'</span></span>';
				$message_text.= '</div>';

				$message_text.= '<div id="wall-comments">';
				$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_id`='$wall_user_id'"));
				$pages_count = ceil($count / $perpage);
				$start_pos = 0;
				$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `user_id`='$wall_user_id' ORDER BY `id` DESC LIMIT $start_pos, $perpage") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_com)>0) {
					while($row_com = mysql_fetch_assoc($sql_com)) {
						$InfoUser = InfoUser($row_com["user_id_com"], $row_com["user_name_com"]);
						$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name))) ? 1 : 0;
						$StatusEditCom = $StatusDelCom==1 ? 1 : 0;
						$StatusAddAnsw = ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name)) ? 1 : 0;
						$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name) && trim($row_com["comment_answ"])!=false)) ? 1 : 0;
						$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;

						$message_text.= ListComm($row_com, $InfoUser, $StatusDelCom, $StatusEditCom, $StatusAddAnsw, $StatusDelAnsw, $StatusEditAnsw, $user_name, $security_key);
					}
				}
				$message_text.= '</div>';

				if($count>$perpage) $message_text.= '<div id="load-pages" data-op="LoadComm" data-id="wall-comments" data-uid="'.$wall_user_id.'" data-page="1" data-close="'.$pages_count.'" data-hash="'.md5($wall_user_id.strtolower($user_name).$perpage.$security_key).'" onClick="LoadComm();">Показать ещё</div>';
			$message_text.= '</div>';

			$result_text = "OK";
			$title_page = "Стена пользователя ".$row["username"];
			exit(my_json_encode($ajax_json, $result_text, $message_text, $title_page));
		}else{
			$message_text = false;
			$message_text.= SeachForm();
			$message_text.= '<span class="msg-error">Пользователь с такими данными не найден!</span>';

			$result_text = "ERROR-ID";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "LoadComm") {
		$page = ( isset($_POST["page"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["page"]))) ) ? htmlspecialchars(trim($_POST["page"])) : false;
		$hash_post = ( isset($_POST["hash"]) && preg_match("|^[0-9a-fA-F]{32}$|", limpiarez(trim($_POST["hash"]))) ) ? strtolower(limpiarez($_POST["hash"])) : false;
		$hash_user = strtolower(md5($wall_user_id.strtolower($user_name).$perpage.$security_key));

		$message_text = false;
		$title_page = false;

		$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_id`='$wall_user_id'"));
		$pages_count = ceil($count / $perpage);
		$start_pos = intval($page * $perpage);

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `user_id`='$wall_user_id' ORDER BY `id` DESC LIMIT $start_pos, $perpage") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			if($hash_post != $hash_user) {
				$result_text = "ERROR";
				$message_text = "Отзывы не найдены";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

			while($row_com = mysql_fetch_assoc($sql_com)) {
				$InfoUser = InfoUser($row_com["user_id_com"], $row_com["user_name_com"]);
				$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name))) ? 1 : 0;
				$StatusEditCom = $StatusDelCom==1 ? 1 : 0;
				$StatusAddAnsw = ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name)) ? 1 : 0;
				$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name) && trim($row_com["comment_answ"])!=false)) ? 1 : 0;
				$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;

				$message_text.= ListComm($row_com, $InfoUser, $StatusDelCom, $StatusEditCom, $StatusAddAnsw, $StatusDelAnsw, $StatusEditAnsw, $user_name, $security_key);
			}

			$page++;
			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text, $title_page, $page));
		}else{
			$result_text = "ERROR";
			$message_text = "Отзывы не найдены";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "LoadStat") {
		$message_text = false;
		$week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
		$week_arr_ru = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб");
		$week_ru = array();
		$week_en = array();
		$date_s = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
		$date_e = strtotime(DATE("d.m.Y"));
		$period = (24*60*60);
		$color_arr = array('serf'=>'#508ED3', 'auto_serf'=>'#CD5C5C', 'ban_serf'=>'#FA8072', 'mails'=>'#009126', 'task'=>'#ED5A62', 'tests'=>'#FF8000', 'you'=>'#0000ff', 'autoyoutube_ser'=>'#CD5C5C', 'pay_visits'=>'#0000ff');
		$types_arr = array('serf'=>'СЕРФИНГ', 'auto_serf'=>'АВТО-СЕРФИНГ', 'ban_serf'=>'БАННЕРНЫЙ СЕРФИНГ', 'mails'=>'ПИСЬМА', 'task'=>'ЗАДАНИЯ', 'tests'=>'ТЕСТЫ', 'you'=>'YOUTUBE-СЕРФИНГ', 'autoyoutube_ser'=>'АВТО-СЕРФИНГ YOUTUBE', 'pay_visits'=>'ОПЛАЧИВАЕМЫЕ ПОСЕЩЕНИЯ');
		$tab_in_arr = array_keys($types_arr);
		$tab_in = implode("', '", $tab_in_arr);
		for($i=$date_s; $i<=$date_e; $i=$i+$period) {
			$week_ru[] = $week_arr_ru[strtolower(DATE("w", $i))];
			$week_en[] = $week_arr_en[strtolower(DATE("w", $i))];
		}
		$data_graff = array();

		$sql_w = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_w)>0) {
			$row_w = mysql_fetch_assoc($sql_w);
			$wall_user_id = $row_w["id"];
			$wall_user_name = $row_w["username"];
			$token_stat = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."STAT".$security_key));

			if($token_post == false | $token_post != $token_stat) {
				$result_text = "ERROR";
				$message_text = '<span class="msg-error">Пользователь с такими данными не найден!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

			for($i=0; $i<count($tab_in_arr); $i++) {
				$StatUser[$tab_in_arr[$i]] = mysql_fetch_assoc(mysql_query("SELECT * FROM `tb_users_stat` WHERE `username`='$wall_user_name' AND `type`='".$tab_in_arr[$i]."'"));

				for($y=0; $y<count($week_en); $y++) {
					$data_graff[$tab_in_arr[$i]][] = isset($StatUser[$tab_in_arr[$i]][$week_en[$y]]) ? $StatUser[$tab_in_arr[$i]][$week_en[$y]] : 0;
				}
				$data_graff[$tab_in_arr[$i]] = implode(", ", $data_graff[$tab_in_arr[$i]]);
			}
			foreach($data_graff as $key=>$val) {
				$series_arr[] = "{name: '".mb_convert_case($types_arr[$key], MB_CASE_TITLE, "CP1251")."', data: [$val], color: '".$color_arr[$key]."'}";
			}
			$categories = isset($week_ru) ? "'".implode("', '", $week_ru)."'" : false;
			$series = isset($series_arr) ? implode(", ", $series_arr) : false;

			$message_text.= '<div style="width:650px; height:250px; margin:0 auto;" id="WallChart"></div>';
			$message_text.= "<script type=\"text/javascript\" language=\"JavaScript\">
			Highcharts.chart(\"WallChart\", {
				chart: 	{ backgroundColor: false, type: 'line', width: 650, height: 250 }, 
				credits: { enabled: false }, title: { text: '' }, subtitle: { text: '' }, 
				xAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, categories: [$categories] }, 
				yAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, title: { text: '' } }, 
				legend: { /*enabled: false*/ layout: 'vertical', align: 'right', verticalAlign: 'middle' }, 
				series: [$series]
			});
			</script>";

			$message_text.= '<table class="tables" style="">';
			$message_text.= '<thead>';
			$message_text.= '<tr>';
				$message_text.= '<th></th>';
				$message_text.= '<th style="width:12%;">Всего</th>';
				$message_text.= '<th style="width:10%;">За месяц</th>';
				for($i=0; $i<count($week_ru); $i++) {
					$message_text.= '<th style="width:8%; '.($week_en[$i]==strtolower(DATE("D")) ? "background:green;" : false).'">'.$week_ru[$i].'</th>';
				}
			$message_text.= '</tr>';
			$message_text.= '</thead>';
			$message_text.= '<tbody>';
			for($i=0; $i<count($tab_in_arr); $i++) {
				$message_text.= '<tr>';
					$message_text.= '<td align="center" style="color:'.$color_arr[$tab_in_arr[$i]].';">'.$types_arr[$tab_in_arr[$i]].'</td>';
					$message_text.= '<td align="center"><b>'.(isset($StatUser[$tab_in_arr[$i]]["all_views"]) ? $StatUser[$tab_in_arr[$i]]["all_views"] : "0").'</b></td>';
					$message_text.= '<td align="center"><b>'.(isset($StatUser[$tab_in_arr[$i]]["month"]) ? $StatUser[$tab_in_arr[$i]]["month"] : "0").'</b></td>';
					for($y=0; $y<count($week_en); $y++) {
						$message_text.= '<td align="center">'.(isset($StatUser[$tab_in_arr[$i]][$week_en[$y]]) ? $StatUser[$tab_in_arr[$i]][$week_en[$y]] : "0").'</td>';
					}
				$message_text.= '</tr>';
			}
			$message_text.= '</tbody>';
			$message_text.= '</table>';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR";
			$message_text = '<span class="msg-error">Пользователь с такими данными не найден!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "SeachUser") {
		$id_seach = (isset($_POST["user"]) && preg_match("|^[0-9]{1,11}$|", trim($_POST["user"]))) ? intval(trim($_POST["user"])) : false;
		$login_seach = (isset($_POST["user"]) && preg_match("|^[a-zA-Z0-9\-_-]{1,20}$|", trim($_POST["user"]))) ? htmlentities(stripslashes(trim($_POST["user"]))) : false;
		$message_text = false;

		if($id_seach == false && $login_seach == false) {
			$result_text = "ERROR";
			$message_text = '<span class="msg-w" style="margin:0px 3px;">Поиск не дал результатов!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			if($id_seach != false) {
				$sql_s = mysql_query("SELECT `id`,`username`,`lastlogdate2`,`avatar`,`vac1`,`vac2` FROM `tb_users` WHERE `id`='$id_seach' LIMIT $LIMIT_SEACH") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			}elseif($login_seach != false) {
				$sql_s = mysql_query("SELECT `id`,`username`,`lastlogdate2`,`avatar`,`vac1`,`vac2` FROM `tb_users` WHERE `username` LIKE '%$login_seach%' LIMIT $LIMIT_SEACH") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			}
			if(mysql_num_rows($sql_s)>0) {
				$sql_o = mysql_query("SELECT `username` FROM `tb_online` WHERE `username`!=''") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_o)>0) {
					while ($row_o = mysql_fetch_assoc($sql_o)) { $online_arr[] = strtolower($row_o["username"]); }
				}else{
					$online_arr = array();
				}
				while ($row_s = mysql_fetch_assoc($sql_s)) {
					$message_text.= '<div class="seach-in" onClick="LoadWall(\''.$row_s["id"].'\');">';
						$message_text.= '<span class="seach-box-a"><img src="avatar/'.$row_s["avatar"].'" class="small-avatar" align="absmiddle" alt="" title="" /></span>';
						$message_text.= '<span class="seach-box-b">'.$row_s["username"].'</span>';
						$message_text.= '<span class="seach-box-c">'.LastLogin($online_arr, strtolower($row_s["username"]), $row_s["lastlogdate2"], $row_s["vac1"], $row_s["vac2"]).'</span>';
					$message_text.= '</div>';
				}

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR";
				$message_text = '<span class="msg-w" style="margin:0px 3px;">Поиск не дал результатов!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}


	}elseif($option == "FormInfoWall") {
		$message_text = false;

		$sql = mysql_query("SELECT `id`,`username`,`wall_my_info` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_info_edit = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_EDIT_INFO".$security_key));
			$token_info_sub = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."EDIT_INFO".$security_key));
			$StatusEditInfo = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($wall_user_name)==strtolower($user_name) && $user_ban_date==0)) ? 1 : 0;

			$title_box = "Комментарий владельца стены <b>$wall_user_name</b>";

			if($token_post == false | $token_post != $token_info_edit){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для редактирования комментария необходимо авторизоваться</div>';
			}elseif($StatusEditInfo != 1){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Недостаточно прав для редактирования комментария</div>';
			}else{
				$message_text.= '<table class="tables" id="newform" style="margin:5px auto;">';
				$message_text.= '<thead><tr><th align="center">Ваш комментарий</th></tr><thead>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td style="padding:3px 8px;">';
							$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
								$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="InsertTags(\'[b]\',\'[/b]\', \'comment\'); return false;">Ж</span>';
								$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="InsertTags(\'[i]\',\'[/i]\', \'comment\'); return false;">К</span>';
								$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="InsertTags(\'[u]\',\'[/u]\', \'comment\'); return false;">Ч</span>';
								$message_text.= '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="InsertTags(\'[s]\',\'[/s]\', \'comment\'); return false;">ST</span>';
								$message_text.= '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="InsertTags(\'[left]\',\'[/left]\', \'comment\'); return false;"></span>';
								$message_text.= '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="InsertTags(\'[center]\',\'[/center]\', \'comment\'); return false;"></span>';
								$message_text.= '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="InsertTags(\'[right]\',\'[/right]\', \'comment\'); return false;"></span>';
								$message_text.= '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="InsertTags(\'[justify]\',\'[/justify]\', \'comment\'); return false;"></span>';
							$message_text.= '</div>';
							$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$wallcom_count_text.'</div>';
							$message_text.= '<div style="display: block; padding-top:4px">';
								$message_text.= '<textarea id="comment" class="ok" style="height:120px;" placeholder="Укажите текст комментария" onKeyup="descchange(\'1\', this, \''.$wallcom_count_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$wallcom_count_text.'\');" onClick="descchange(\'1\', this, \''.$wallcom_count_text.'\');">'.$row["wall_my_info"].'</textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:3px 8px;">';
							$message_text.= '<div id="Smiles" align="center">';
								for($i=0; $i<=50; $i++){
									$message_text.= '<span onClick="InSmile(\'comment\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
								}
							$message_text.= '</div>';
					$message_text.= '</tr>';
					$message_text.= '<tr id="tr-info" style="display:none;">';
						$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-com"></div></td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center"><span id="sub-addcom" onClick="EditInfoWall(\''.$wall_user_id.'\', \''.$token_info_sub.'\');" class="wall-sub" style="float:none; width:150px;">Сохранить</span><span id="sub-addcom" onClick="$(\'#LoadModal\').modalpopup(\'close\');" class="wall-sub" style="float:none; width:150px;">Отмена</span></div>';
			}
		}else{
			$title_box = "Комментарий владельца стены";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Пользователь с такими данными не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormAddCom") {
		$message_text = false;

		$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_comm = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."COMM".$security_key));
			$token_comm_sub = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."COMM_ADD".$security_key));
			$My_InfoStatus = ($user_name!=false) ? InfoStatus($user_id, $user_reit, $user_ban_date) : array("wall_comm" => "0");
			$StatusAddCom = ($user_name!=false && strtolower($user_name)!=strtolower($wall_user_name) && $My_InfoStatus["wall_comm"]==1) ? 1 : 0;

			$title_box = "Отзыв о пользователе <b>$wall_user_name</b>";

			if($token_post == false | $token_post != $token_comm){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для добавления отзыва необходимо авторизоваться</div>';
			}elseif(strtolower($user_name)==strtolower($wall_user_name)) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Вы не можете оставлять отзывы на своей стене</div>';
			}elseif($StatusAddCom != 1){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Недостаточно прав для добавления отзыва</div>';
			}else{
				$message_text.= '<table class="tables" id="newform" style="margin:5px auto;">';
				$message_text.= '<thead><tr><th align="center">Ваш комментарий</th></tr><thead>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:7px">';
							$message_text.= '<span style="margin-right:10px;"><input type="radio" id="ocenka" name="ocenka" value="1" checked="checked" /> <b style="color:#008000;">Положительный отзыв</b></span>';
							$message_text.= '<span style="margin-left:10px;"><input type="radio" id="ocenka" name="ocenka" value="-1" /> <b style="color:#FF0000;">Отрицательный отзыв</b></span>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td style="padding:3px 8px;">';
							$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
								$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="InsertTags(\'[b]\',\'[/b]\', \'comment\'); return false;">Ж</span>';
								$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="InsertTags(\'[i]\',\'[/i]\', \'comment\'); return false;">К</span>';
								$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="InsertTags(\'[u]\',\'[/u]\', \'comment\'); return false;">Ч</span>';
							$message_text.= '</div>';
							$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$wallcom_count_text.'</div>';
							$message_text.= '<div style="display: block; padding-top:4px">';
								$message_text.= '<textarea id="comment" class="ok" style="height:120px;" placeholder="Укажите текст отзыва" onKeyup="descchange(\'1\', this, \''.$wallcom_count_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$wallcom_count_text.'\');" onClick="descchange(\'1\', this, \''.$wallcom_count_text.'\');"></textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:3px 8px;">';
							$message_text.= '<div id="Smiles" align="center">';
								for($i=0; $i<=50; $i++){
									$message_text.= '<span onClick="InSmile(\'comment\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
								}
							$message_text.= '</div>';
					$message_text.= '</tr>';
					$message_text.= '<tr id="tr-info" style="display:none;">';
						$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-com"></div></td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center"><span id="sub-addcom" onClick="AddComWall(\''.$wall_user_id.'\', \''.$token_comm_sub.'\');" class="wall-sub" style="float:none; width:150px;">Добавить отзыв</span></div>';
			}
		}else{
			$title_box = "Отзыв о пользователе";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Пользователь с такими данными не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormEditCom") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
		$message_text = false;

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$wall_user_id = $row_com["user_id"];
			$wall_user_name = $row_com["user_name"];
			$token_com_edit = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_COMM_EDIT".$security_key));
			$token_com_sub  = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."COMM_EDIT".$security_key));
			$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name))) ? 1 : 0;
			$StatusEditCom = $StatusDelCom==1 ? 1 : 0;

			$title_box = "Редактирование отзыва о пользователе <b>".$row_com["user_name"]."</b>";

			if($token_post == false | $token_post != $token_com_edit){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для редактирования отзыва необходимо авторизоваться</div>';
			}elseif($StatusEditCom != 1){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Недостаточно прав для редактирования отзыва</div>';
			}else{
				$message_text.= '<table class="tables" id="newform" style="margin:5px auto;">';
				$message_text.= '<thead><tr><th align="center">Ваш комментарий</th></tr><thead>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:7px">';
							$message_text.= '<span style="margin-right:10px;"><input type="radio" id="ocenka" name="ocenka" value="1" '.($row_com["ocenka"]==1 ? 'checked="checked"' : false).' /> <b style="color:#008000;">Положительный отзыв</b></span>';
							$message_text.= '<span style="margin-left:10px;"><input type="radio" id="ocenka" name="ocenka" value="-1" '.($row_com["ocenka"]==-1 ? 'checked="checked"' : false).' /> <b style="color:#FF0000;">Отрицательный отзыв</b></span>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td style="padding:3px 8px;">';
							$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
								$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="InsertTags(\'[b]\',\'[/b]\', \'comment\'); return false;">Ж</span>';
								$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="InsertTags(\'[i]\',\'[/i]\', \'comment\'); return false;">К</span>';
								$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="InsertTags(\'[u]\',\'[/u]\', \'comment\'); return false;">Ч</span>';
							$message_text.= '</div>';
							$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$wallcom_count_text.'</div>';
							$message_text.= '<div style="display: block; padding-top:4px">';
								$message_text.= '<textarea id="comment" class="ok" style="height:120px;" placeholder="Укажите текст отзыва" onKeyup="descchange(\'1\', this, \''.$wallcom_count_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$wallcom_count_text.'\');" onClick="descchange(\'1\', this, \''.$wallcom_count_text.'\');">'.$row_com["comment"].'</textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:3px 8px;">';
							$message_text.= '<div id="Smiles" align="center">';
								for($i=0; $i<=50; $i++){
									$message_text.= '<span onClick="InSmile(\'comment\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
								}
							$message_text.= '</div>';
					$message_text.= '</tr>';
					$message_text.= '<tr id="tr-info" style="display:none;">';
						$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-com"></div></td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center"><span id="sub-addcom" onClick="EditComWall(\''.$row_com["id"].'\', \''.$token_com_sub.'\');" class="wall-sub" style="float:none; width:150px;">Сохранить</span><span id="sub-addcom" onClick="$(\'#LoadModal\').modalpopup(\'close\');" class="wall-sub" style="float:none; width:150px;">Отмена</span></div>';
			}
		}else{
			$title_box = "Редактирование отзыва о пользователе";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Отзыв не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormAddAnsw") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
		$message_text = false;

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment' AND `user_name`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$wall_user_id = $row_com["user_id"];
			$wall_user_name = $row_com["user_name"];
			$token_answ_add = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_ANSW_ADD".$security_key));
			$token_answ_sub = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_ADD".$security_key));
			$StatusAddAnsw = ($user_name!=false && strtolower($wall_user_name)==strtolower($user_name)) ? 1 : 0;

			$title_box = "Ответ на отзыв пользователя <b>".$row_com["user_name_com"]."</b>";

			if($token_post == false | $token_post != $token_answ_add){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для добавления ответа на отзыв необходимо авторизоваться</div>';
			}elseif($StatusAddAnsw != 1){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Недостаточно прав для добавления ответа на отзыв</div>';
			}else{
				$message_text.= '<table class="tables" id="newform" style="margin:5px auto;">';
				$message_text.= '<thead><tr><th align="center">Ваш комментарий</th></tr><thead>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td style="padding:3px 8px;">';
							$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
								$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="InsertTags(\'[b]\',\'[/b]\', \'comment_answ\'); return false;">Ж</span>';
								$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="InsertTags(\'[i]\',\'[/i]\', \'comment_answ\'); return false;">К</span>';
								$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="InsertTags(\'[u]\',\'[/u]\', \'comment_answ\'); return false;">Ч</span>';
							$message_text.= '</div>';
							$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$wallcom_count_text.'</div>';
							$message_text.= '<div style="display: block; padding-top:4px">';
								$message_text.= '<textarea id="comment_answ" class="ok" style="height:120px;" placeholder="Укажите текст ответа" onKeyup="descchange(\'1\', this, \''.$wallcom_count_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$wallcom_count_text.'\');" onClick="descchange(\'1\', this, \''.$wallcom_count_text.'\');"></textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:3px 8px;">';
							$message_text.= '<div id="Smiles" align="center">';
								for($i=0; $i<=50; $i++){
									$message_text.= '<span onClick="InSmile(\'comment_answ\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
								}
							$message_text.= '</div>';
					$message_text.= '</tr>';
					$message_text.= '<tr id="tr-info" style="display:none;">';
						$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-com"></div></td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center"><span id="sub-addcom" onClick="AddAnswWall(\''.$row_com["id"].'\', \''.$token_answ_sub.'\');" class="wall-sub" style="float:none; width:150px;">Добавить ответ</span></div>';
			}
		}else{
			$title_box = "Ответ на отзыв пользователя";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Отзыв не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormEditAnsw") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
		$message_text = false;

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$wall_user_id = $row_com["user_id"];
			$wall_user_name = $row_com["user_name"];
			$token_answ_edit = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_ANSW_EDIT".$security_key));
			$token_answ_sub = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_EDIT".$security_key));
			$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name))) ? 1 : 0;
			$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;

			$title_box = "Редактирование ответа на отзыв пользователя <b>".$row_com["user_name_com"]."</b>";

			if($token_post == false | $token_post != $token_answ_edit){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для редактирования ответа на отзыв необходимо авторизоваться</div>';
			}elseif($StatusEditAnsw != 1){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Недостаточно прав для редактирования ответа на отзыв</div>';
			}else{
				$message_text.= '<table class="tables" id="newform" style="margin:5px auto;">';
				$message_text.= '<thead><tr><th align="center">Ваш комментарий</th></tr><thead>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td style="padding:3px 8px;">';
							$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
								$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="InsertTags(\'[b]\',\'[/b]\', \'comment_answ\'); return false;">Ж</span>';
								$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="InsertTags(\'[i]\',\'[/i]\', \'comment_answ\'); return false;">К</span>';
								$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="InsertTags(\'[u]\',\'[/u]\', \'comment_answ\'); return false;">Ч</span>';
							$message_text.= '</div>';
							$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$wallcom_count_text.'</div>';
							$message_text.= '<div style="display: block; padding-top:4px">';
								$message_text.= '<textarea id="comment_answ" class="ok" style="height:120px;" placeholder="Укажите текст ответа" onKeyup="descchange(\'1\', this, \''.$wallcom_count_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$wallcom_count_text.'\');" onClick="descchange(\'1\', this, \''.$wallcom_count_text.'\');">'.$row_com["comment_answ"].'</textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:3px 8px;">';
							$message_text.= '<div id="Smiles" align="center">';
								for($i=0; $i<=50; $i++){
									$message_text.= '<span onClick="InSmile(\'comment_answ\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
								}
							$message_text.= '</div>';
					$message_text.= '</tr>';
					$message_text.= '<tr id="tr-info" style="display:none;">';
						$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-com"></div></td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center"><span id="sub-addcom" onClick="EditAnswWall(\''.$row_com["id"].'\', \''.$token_answ_sub.'\');" class="wall-sub" style="float:none; width:150px;">Сохранить</span><span id="sub-addcom" onClick="$(\'#LoadModal\').modalpopup(\'close\');" class="wall-sub" style="float:none; width:150px;">Отмена</span></div>';
			}
		}else{
			$title_box = "Редактирование ответа на отзыв пользователя";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Отзыв не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormAddGift") {
		$message_text = false;

		$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_gift = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_ADD_GIFT".$security_key));

			$sql_gift = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='gift_cena'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$gift_cena = number_format(mysql_result($sql_gift,0,0), 2, ".", "");

			$title_box = '<div id="title-gift-1" style="display:block;">Выберите подарок для пользователя <b>'.$wall_user_name.'</b></div>';
			$title_box.= '<div id="title-gift-2" style="display:none;">Подарок для <b>'.$wall_user_name.'</b></div>';

			if($token_post == false | $token_post != $token_gift){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для отправки подарка необходимо авторизоваться</div>';
			}elseif(strtolower($user_name)==strtolower($wall_user_name)) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Вы не можете отправлять подарки самому себе</div>';
			}elseif($user_ban_date > 0) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Вы не можете отправлять подарки, Ваш аккаунт заблокирован</div>';
			}else{
				$message_text.= '<div id="block-gift" style="width:100%; min-height:135px; margin:0 auto; display:block; text-align:center;">';
				for($i=1; $i<=$COUNT_GIFTS; $i++) {
					$file_img = "gift-$i.png";
					//$file_img = "gift-$i.jpg";
					if(is_file(ROOT_DIR.$FOLDER_GIFTS.$file_img)) {
						$token_gift = strtolower(md5($i.$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."PAY_GIFT".$security_key));
						$message_text.= '<div class="d-gift" data-id="'.$i.'" data-uid="'.$wall_user_id.'" data-gift="'.$FOLDER_GIFTS.$file_img.'" data-token="'.$token_gift.'">';
							$message_text.= '<img src="'.$FOLDER_GIFTS.$file_img.'" class="img-gift" alt="" />';
						$message_text.= '</div>';
					}
				}
				$message_text.= '</div>';

				$message_text.= '<div id="user-gift" style="display:none; text-align:center;">';
					$message_text.= '<div id="load-gift"></div>';
					if( $user_id == 2 && $user_status == 1 ) {
                                $message_text .= "<div id=\"cena-gift\">Стоимость подарка: <b class=\"text-green\">для пользователя <b>Znata</b> бесплатно!</b></div>";
							}else{	
					$message_text.= '<div id="cena-gift">Стоимость подарка: <b class="text-green">'.($user_id==1 && $user_status==1 ? "для админа бесплатно!" : "$gift_cena руб.").'</b></div>';
								}
					$message_text.= '<div id="mess-gift"><div id="newform" style="width:95%; margin:10px auto; text-align:left;">';
						$message_text.= '<div style="display: inline-block; float:left; padding:5px 2px 0px 0px;"><b>Ваше сообщение</b></div>';
						$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$gift_count_text.'</div>';
						$message_text.= '<textarea id="comment-gift" class="ok" style="width:calc(100% - 12px); margin:0; padding:5px;" onKeyup="descchange(\'1\', this, \''.$gift_count_text.'\');" onKeydown="descchange(\'1\', this, \''.$gift_count_text.'\');" onClick="descchange(\'1\', this, \''.$gift_count_text.'\');"></textarea>';
						$message_text.= '<input type="checkbox" id="privat-gift" value="1" /> <b>приватный подарок</b> (только получатель узнает, кто сделал подарок)';
					$message_text.= '</div></div>';
					$message_text.= '<div id="info-msg-error" style="display:none;"></div>';
					$message_text.= '<div id="sub-gift">';
						$message_text.= '<span id="GiftPay" class="wall-sub" style="float:none; width:150px;">Отправить</span>';
						$message_text.= '<span id="GiftChange" class="wall-sub" style="float:none; width:150px;">Выбрать другой</span>';
					$message_text.= '</div>';
				$message_text.= '</div>';
			}
		}else{
			$title_box = '<div id="title-gift">Подарок для пользователя</div>';
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Пользователь с такими данными не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "600px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormInfoKonk") {
		$message_text = false;

		$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_konk_info = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."KONK_INFO".$security_key));

			$title_box = "Информация о конкурсах от <b>$wall_user_name</b>";

			if($token_post == false | $token_post != $token_konk_info){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}else{
				$sql_k = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$wall_user_name' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `type_kon` ASC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_k)>0) {
					$message_text.= '<table class="tables">';
					$message_text.= '<thead><tr align="center">';
						$message_text.= '<th align="center">Конкурс</th>';
						$message_text.= '<th align="center">Начало</th>';
						$message_text.= '<th align="center">Окончание</th>';
					$message_text.= '</tr></thead>';
					while ($row_k = mysql_fetch_assoc($sql_k)) {
						switch($row_k["count_kon"]){
							case 1: $m="место"; break;
							case 2: case 3: case 4: $m="места"; break;
							case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19:  case 20:$m="мест"; break;
						}
						$p = 0;
						for($i=1; $i<=$row_k["count_kon"]; $i++) {
							$p = $p + $row_k["p$i"];
						}
						if($row_k["type_kon"]==1) {
							$type_kon = '<b>Выполнение заданий</b>';
						}elseif($row_k["type_kon"]==2) {
							$type_kon = '<b>Выполнение оплачиваемых кликов</b>';
						}elseif($row_k["type_kon"]==3) {
							$type_kon = '<b>Привлечение рефералов</b>';
						}elseif($row_k["type_kon"]==4) {
							$type_kon = "<b>Комплексный (по баллам)</b>";
						}elseif($row_k["type_kon"]==5) {
						$type_kon = "<b>Просмотр видеороликов <span style=\"color: #3F3F3F;\">You</span><span style=\"border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;\">Tube</span></b>";
					}elseif($row_k["type_kon"]==6) {
						$type_kon = "Сумма заработанная для реферера";
						}else{
							$type_kon = '<b style="color:#FF0000;">Тип конкурса не определен!</b>';
						}
						$message_text.= '<tr>';
							$message_text.= '<td>'.$type_kon.'<br>['.$row_k["count_kon"].' '.$m.', призовой фонд <b>'.$p.'</b> руб.]</td>';
							$message_text.= '<td align="center">'.DATE("d.m.Y", $row_k["date_s"]).'<br>'.DATE("H:i", $row_k["date_s"]).'</td>';
							$message_text.= '<td align="center">'.DATE("d.m.Y", $row_k["date_e"]).'<br>'.DATE("H:i:s", $row_k["date_e"]).'</td>';
						$message_text.= '</tr>';
					}
					$message_text.= '</table>';
				}else{
					$message_text.= '<div class="msg-w" style="margin:5px auto;">Активных конкурсов нет!</div>';
				}
			}
		}else{
			$title_box = "Информация о конкурсах";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Пользователь с такими данными не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "FormInfoBon") {
		$message_text = false;

		$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_bonus_info = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."BONUS_INFO".$security_key));

			$title_box = "Информация о бонусах от <b>$wall_user_name</b>";

			if($token_post == false | $token_post != $token_bonus_info){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Ошибка доступа</div>';
			}else{
				$sql_b = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$wall_user_name' ORDER BY `type_bon` ASC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_b)>0) {
					$message_text.= '<table class="tables">';
					$message_text.= '<thead><tr align="center">';
						$message_text.= '<th align="center">Тип бонуса</th>';
						$message_text.= '<th align="center">Сумма</th>';
					$message_text.= '</tr></thead>';
					while ($row_b = mysql_fetch_assoc($sql_b)) {
						if($row_b["type_bon"]==1) {
							$type_bon = '<b>За регистрацию</b>';
						}elseif($row_b["type_bon"]==2) {
							$type_bon = '<b>За привлечение рефералов</b>';
						}elseif($row_b["type_bon"]==3) {
							$type_bon = '<b>За просмотр каждых '.$row_b["count_nado"].' сайтов в серфинге</b>';
						}elseif($row_b["type_bon"]==4) {
							$type_bon = '<b>За выполнение каждых '.$row_b["count_nado"].' заданий</b>';
						}elseif($row_b["type_bon"]==5) {
							$type_bon = '<b>За просмотр каждых '.$row_b["count_nado"].' видеороликов <span style=\"color: #3F3F3F;\">You</span><span style=\"border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;\">Tube</span></b>';
						}elseif($row_b["type_bon"]==6) {
							$type_bon = '<b>За заработок каждых '.number_format($row_b["count_nado"],2,'.','`').' руб.</b>';
						}else{
							$type_bon = '<b style="color:#FF0000;">Тип бонуса не определен!</b>';
						}
						$message_text.= '<tr>';
							$message_text.= '<td style="padding: 5px 10px;">'.$type_bon.'</td>';
							$message_text.= '<td style="padding: 5px 10px; text-align:center;"><b>'.$row_b["bonus"].'</b> руб.</td>';
						$message_text.= '</tr>';
					}
					$message_text.= '</table>';
				}else{
					$message_text.= '<div class="msg-w" style="margin:5px auto;">Активных бонусов нет!</div>';
				}
			}
		}else{
			$title_box = "Информация о бонусах";
			$message_text = '<span class="msg-error" style="margin:0px 3px;">Пользователь с такими данными не найден!</span>';
		}

		$result_text = "OK";
		$message_text = BoxModal($title_box, $message_text, "550px");
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "EditInfo") {
		$comment = isset($_POST["comment"]) ? limpiarez($_POST["comment"]) : false;
		$comment = get_magic_quotes_gpc() ? stripslashes($comment) : $comment;
		$comment = limitatexto($comment, $wallcom_count_text);

		$sql = mysql_query("SELECT `id`,`username`,`wall_my_info` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_info_edit = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."EDIT_INFO".$security_key));
			$StatusEditInfo = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($wall_user_name)==strtolower($user_name) && $user_ban_date==0)) ? 1 : 0;

			if($token_post == false | $token_post != $token_info_edit){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Для редактирования комментария необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusEditInfo != 1){
				$result_text = "ERROR"; $message_text = "Недостаточно прав для редактирования комментария";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("UPDATE `tb_users` SET `wall_my_info`='$comment' WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_info = mysql_query("SELECT `wall_my_info` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$row_info = mysql_fetch_assoc($sql_info);

				$result_text = "OK";
				$message_text = smile(desc_bb(isset($row_info["wall_my_info"]) ? $row_info["wall_my_info"] : $comment));
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Пользователь с такими данными не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "AddCom") {
		$ocenka = ( isset($_POST["ocenka"]) && preg_match("|^[-+]?[1]{1}$|", trim($_POST["ocenka"])) ) ? intval(trim($_POST["ocenka"])) : false;
		$comment = isset($_POST["comment"]) ? limpiarez($_POST["comment"]) : false;
		$comment = get_magic_quotes_gpc() ? stripslashes($comment) : $comment;
		$comment = limitatexto($comment, $wallcom_count_text);

		$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_comm_add = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."COMM_ADD".$security_key));
			$My_InfoStatus = ($user_name!=false) ? InfoStatus($user_id, $user_reit, $user_ban_date) : array("wall_comm" => "0");
			$StatusAddCom = ($user_name!=false && strtolower($user_name)!=strtolower($wall_user_name) && $My_InfoStatus["wall_comm"]==1) ? 1 : 0;

			$sql_wc = mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='$wall_user_name' AND `user_name_com`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			if($token_post == false | $token_post != $token_comm_add){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Для добавления отзыва необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strtolower($user_name)==strtolower($wall_user_name)) {
				$result_text = "ERROR"; $message_text = "Вы не можете оставлять отзывы на своей стене";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusAddCom != 1){
				$result_text = "ERROR"; $message_text = "Недостаточно прав для добавления отзыва";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(mysql_num_rows($sql_wc)>0) {
				$result_text = "ERROR"; $message_text = "Вы уже оставляли отзыв о пользователе $wall_user_name";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($ocenka == false){
				$result_text = "ERROR"; $message_text = "Тип отзыва не определен";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($comment == false){
				$result_text = "ERROR"; $message_text = "Укажите текст комментария";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("INSERT INTO `tb_wall_comments` (`user_id`,`user_name`,`user_id_com`,`user_name_com`,`comment`,`ocenka`,`time`,`ip`) 
				VALUES('$wall_user_id','$wall_user_name','$user_id','$user_name','$comment','$ocenka','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_wall_comments`");
				$id_comment = mysql_result($sql_last_id,0,0);

				$cnt_plus = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='$wall_user_name' AND `ocenka`='1'"));
				$cnt_minus = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='$wall_user_name' AND `ocenka`='-1'"));
				$cnt_all = intval($cnt_plus + $cnt_minus);

				mysql_query("UPDATE `tb_users` SET `wall_com_p`='$cnt_plus', `wall_com_o`='$cnt_minus' WHERE `username`='$wall_user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_com)>0) {
					$row_com = mysql_fetch_assoc($sql_com);
					$InfoUser = InfoUser($row_com["user_id_com"], $row_com["user_name_com"]);
					$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name))) ? 1 : 0;
					$StatusEditCom = $StatusDelCom==1 ? 1 : 0;
					$StatusAddAnsw = ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name)) ? 1 : 0;
					$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name) && trim($row_com["comment_answ"])!=false)) ? 1 : 0;
					$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;

					$message_text = ListComm($row_com, $InfoUser, $StatusDelCom, $StatusEditCom, $StatusAddAnsw, $StatusDelAnsw, $StatusEditAnsw, $user_name, $security_key);

					$result_text = "OK";
					$message_text = "$message_text";
					$cnt = '{"cnt_all":"'.$cnt_all.'", "cnt_plus":"'.$cnt_plus.'", "cnt_minus":"'.$cnt_minus.'"}';
					exit(my_json_encode($ajax_json, $result_text, $message_text, false, false, $cnt));
				}else{
					$result_text = "ERROR";
					$message_text = "Не удалось загрузить комментарий";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Пользователь с такими данными не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "AddAnsw") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
		$comment_answ = isset($_POST["comment_answ"]) ? limpiarez($_POST["comment_answ"]) : false;
		$comment_answ = get_magic_quotes_gpc() ? stripslashes($comment_answ) : $comment_answ;
		$comment_answ = limitatexto($comment_answ, $wallcom_count_text);

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$wall_user_id = $row_com["user_id"];
			$wall_user_name = $row_com["user_name"];
			$StatusAddAnsw = ($user_name!=false && strtolower($wall_user_name)==strtolower($user_name)) ? 1 : 0;
			$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name))) ? 1 : 0;
			$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;
			$token_answ_add  = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_ADD".$security_key));
			$token_answ_del  = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_DEL".$security_key));
			$token_answ_edit = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_ANSW_EDIT".$security_key));

			if($token_post == false | $token_post != $token_answ_add){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Для добавления ответа на отзыв необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusAddAnsw != 1){
				$result_text = "ERROR"; $message_text = "Недостаточно прав для добавления ответа на отзыв";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($comment_answ == false){
				$result_text = "ERROR"; $message_text = "Укажите текст комментария";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("UPDATE `tb_wall_comments` SET `comment_answ`='$comment_answ', `ip_answ`='$my_lastiplog' WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_answ = mysql_query("SELECT `comment_answ` FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$row_answ = mysql_fetch_assoc($sql_answ);

				$ListAnsw = false;
				$ListAnsw.= '<span class="wall_answer">';
					if($StatusDelAnsw==1 | $StatusEditAnsw==1) {
						$ListAnsw.= '<div align="right" style="float: right;">';
							if($StatusDelAnsw==1)  $ListAnsw.= '<span onClick="DelAnswWall(\''.$row_com["id"].'\', \''.$token_answ_del.'\');" class="f-del" title="Удалить ответ"></span>';
							if($StatusEditAnsw==1) $ListAnsw.= '<span onClick="LoadForm(\''.$row_com["id"].'\', \''.$row_com["user_id"].'\', \'FormEditAnsw\', \''.$token_answ_edit.'\');" class="f-edit" title="Редактировать ответ на отзыв"></span>';
						$ListAnsw.= '</div>';
					}
					$ListAnsw.= '<span class="wall_answer_title">Ответ владельца аккаунта:</span>';
					$ListAnsw.= '<span class="wall_answer_text">'.smile(desc_bb(isset($row_answ["comment_answ"]) ? $row_answ["comment_answ"] : $comment_answ)).'</span>';
				$ListAnsw.= '</span>';

				$result_text = "OK";
				$message_text = "$ListAnsw";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Отзыв не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "EditCom") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
		$ocenka = ( isset($_POST["ocenka"]) && preg_match("|^[-+]?[1]{1}$|", trim($_POST["ocenka"])) ) ? intval(trim($_POST["ocenka"])) : false;
		$comment = isset($_POST["comment"]) ? limpiarez($_POST["comment"]) : false;
		$comment = get_magic_quotes_gpc() ? stripslashes($comment) : $comment;
		$comment = limitatexto($comment, $wallcom_count_text);

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$wall_user_id = $row_com["user_id"];
			$wall_user_name = $row_com["user_name"];
			$token_comm_edit = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."COMM_EDIT".$security_key));
			$My_InfoStatus = ($user_name!=false) ? InfoStatus($user_id, $user_reit, $user_ban_date) : array("wall_comm" => "0");
			$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name) && $My_InfoStatus["wall_comm"]==1)) ? 1 : 0;
			$StatusEditCom = $StatusDelCom==1 ? 1 : 0;

			if($token_post == false | $token_post != $token_comm_edit){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Для редактирования отзыва необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusEditCom != 1){
				$result_text = "ERROR"; $message_text = "Недостаточно прав для редактирования отзыва";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($ocenka == false){
				$result_text = "ERROR"; $message_text = "Тип отзыва не определен";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($comment == false){
				$result_text = "ERROR"; $message_text = "Укажите текст комментария";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("UPDATE `tb_wall_comments` SET `comment`='$comment' ".(($user_status!=1 | strtolower($row_com["user_name_com"])==strtolower($user_name)) ? ", `ocenka`='$ocenka', `time`='".time()."', `ip`='$my_lastiplog'" : false)." WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$cnt_plus = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='$wall_user_name' AND `ocenka`='1'"));
				$cnt_minus = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='$wall_user_name' AND `ocenka`='-1'"));
				$cnt_all = intval($cnt_plus + $cnt_minus);

				mysql_query("UPDATE `tb_users` SET `wall_com_p`='$cnt_plus', `wall_com_o`='$cnt_minus' WHERE `username`='$wall_user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_com)>0) {
					$row_com = mysql_fetch_assoc($sql_com);
					$InfoUser = InfoUser($row_com["user_id_com"], $row_com["user_name_com"]);
					$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name))) ? 1 : 0;
					$StatusEditCom = $StatusDelCom==1 ? 1 : 0;
					$StatusAddAnsw = ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name)) ? 1 : 0;
					$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name) && trim($row_com["comment_answ"])!=false)) ? 1 : 0;
					$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;

					$message_text = ListComm($row_com, $InfoUser, $StatusDelCom, $StatusEditCom, $StatusAddAnsw, $StatusDelAnsw, $StatusEditAnsw, $user_name, $security_key);

					$result_text = "OK";
					$message_text = "$message_text";
					$cnt = '{"cnt_all":"'.$cnt_all.'", "cnt_plus":"'.$cnt_plus.'", "cnt_minus":"'.$cnt_minus.'"}';
					exit(my_json_encode($ajax_json, $result_text, $message_text, false, false, $cnt));
				}else{
					$result_text = "ERROR";
					$message_text = "Не удалось загрузить комментарий";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Отзыв не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "EditAnsw") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
		$comment_answ = isset($_POST["comment_answ"]) ? limpiarez($_POST["comment_answ"]) : false;
		$comment_answ = get_magic_quotes_gpc() ? stripslashes($comment_answ) : $comment_answ;
		$comment_answ = limitatexto($comment_answ, $wallcom_count_text);

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$wall_user_id = $row_com["user_id"];
			$wall_user_name = $row_com["user_name"];
			$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name))) ? 1 : 0;
			$StatusEditAnsw = $StatusDelAnsw==1 ? 1 : 0;
			$token_answ_add  = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_ADD".$security_key));
			$token_answ_del  = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_DEL".$security_key));
			$token_answ_edit = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."F_ANSW_EDIT".$security_key));
			$token_answ_sub  = strtolower(md5($row_com["id"].$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ANSW_EDIT".$security_key));

			if($token_post == false | $token_post != $token_answ_sub){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Для редактирования ответа на отзыв необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusEditAnsw != 1){
				$result_text = "ERROR"; $message_text = "Недостаточно прав для редактирования ответа на отзыв";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($comment_answ == false){
				$result_text = "ERROR"; $message_text = "Укажите текст комментария";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("UPDATE `tb_wall_comments` SET `comment_answ`='$comment_answ', `ip_answ`='$my_lastiplog' WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_answ = mysql_query("SELECT `comment_answ` FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$row_answ = mysql_fetch_assoc($sql_answ);

				$ListAnsw = false;
				$ListAnsw.= '<span class="wall_answer">';
					if($StatusDelAnsw==1 | $StatusEditAnsw==1) {
						$ListAnsw.= '<div align="right" style="float: right;">';
							if($StatusDelAnsw==1)  $ListAnsw.= '<span onClick="DelAnswWall(\''.$row_com["id"].'\', \''.$token_answ_del.'\');" class="f-del" title="Удалить ответ"></span>';
							if($StatusEditAnsw==1) $ListAnsw.= '<span onClick="LoadForm(\''.$row_com["id"].'\', \''.$row_com["user_id"].'\', \'FormEditAnsw\', \''.$token_answ_edit.'\');" class="f-edit" title="Редактировать ответ на отзыв"></span>';
						$ListAnsw.= '</div>';
					}
					$ListAnsw.= '<span class="wall_answer_title">Ответ владельца аккаунта:</span>';
					$ListAnsw.= '<span class="wall_answer_text">'.smile(desc_bb(isset($row_answ["comment_answ"]) ? $row_answ["comment_answ"] : $comment_answ)).'</span>';
				$ListAnsw.= '</span>';

				$result_text = "OK";
				$message_text = "$ListAnsw";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Отзыв не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "DelCom") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$token_comm_del = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."COMM_DEL".$security_key));

			$My_InfoStatus = ($user_name!=false) ? InfoStatus($user_id, $user_reit, $user_ban_date) : array("wall_comm" => "0");
			$StatusDelCom =  (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name_com"])==strtolower($user_name)) && $My_InfoStatus["wall_comm"]==1) ? 1 : 0;

			if($user_name == false) {
				$result_text = "ERROR"; $message_text = "Необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($token_post == false | $token_post != $token_comm_del){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusDelCom==1) {
				mysql_query("DELETE FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$cnt_plus = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='".$row_com["user_name"]."' AND `ocenka`='1'"));
				$cnt_minus = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_wall_comments` WHERE `user_name`='".$row_com["user_name"]."' AND `ocenka`='-1'"));
				$cnt_all = intval($cnt_plus + $cnt_minus);

				mysql_query("UPDATE `tb_users` SET `wall_com_p`='$cnt_plus', `wall_com_o`='$cnt_minus' WHERE `username`='".$row_com["user_name"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$result_text = "OK";
				$message_text = "";
				$cnt = '{"cnt_all":"'.$cnt_all.'", "cnt_plus":"'.$cnt_plus.'", "cnt_minus":"'.$cnt_minus.'"}';
				exit(my_json_encode($ajax_json, $result_text, $message_text, false, false, $cnt));
			}else{
				$result_text = "ERROR";
				$message_text = "Недостаточно прав!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Отзыв не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "DelAnsw") {
		$id_comment = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;

		$sql_com = mysql_query("SELECT * FROM `tb_wall_comments` WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);
			$token_answ_del = strtolower(md5($row_com["id"].$row_com["user_id"].strtolower($row_com["user_name"]).strtolower($user_name)."ANSW_DEL".$security_key));

			$My_InfoStatus = ($user_name!=false) ? InfoStatus($user_id, $user_reit, $user_ban_date) : array("wall_comm" => "0");
			$StatusDelAnsw = (($user_name!=false && $user_status==1 | $user_status==2) | ($user_name!=false && strtolower($row_com["user_name"])==strtolower($user_name) && trim($row_com["comment_answ"])!=false) && $My_InfoStatus["wall_comm"]==1) ? 1 : 0;

			if($user_name == false) {
				$result_text = "ERROR"; $message_text = "Необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($token_post == false | $token_post != $token_answ_del){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(trim($row_com["comment_answ"]) == false) {
				$result_text = "ERROR"; $message_text = "Ответ на отзыв не найден, либо уже удален!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($StatusDelAnsw==1) {
				mysql_query("UPDATE `tb_wall_comments` SET `comment_answ`='' WHERE `id`='$id_comment'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$result_text = "OK";
				$message_text = "";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR";
				$message_text = "Недостаточно прав!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Отзыв не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "AddReferer") {
		$sql = mysql_query("SELECT `id`,`username`,`referer`,`referer2`,`referer3`,`ref_back_all` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$wall_user_referer_1 = $row["referer"];
			$wall_user_referer_2 = $row["referer2"];
			$wall_user_referer_3 = $row["referer3"];
			
			$wall_user_ref_back = $row["ref_back_all"];
			$token_add_ref = strtolower(md5($wall_user_id.strtolower($wall_user_name).strtolower($user_name)."ADD_REF".$security_key));

			if($token_post == false | $token_post != $token_add_ref){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_referer != false) {
				$result_text = "ERROR"; $message_text = "У Вас уже есть реферер";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strtolower($user_name)==strtolower($wall_user_name)) {
				$result_text = "ERROR"; $message_text = "Вы не можете быть рефералом у самого себя";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strtolower($user_name)==strtolower($wall_user_referer_1)) {
				$result_text = "ERROR"; $message_text = "Вы не можете быть рефералом пользователя $wall_user_name. Это ваш реферал I-уровня.";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strtolower($user_name)==strtolower($wall_user_referer_2)) {
				$result_text = "ERROR"; $message_text = "Вы не можете быть рефералом пользователя $wall_user_name. Это ваш реферал II-уровня.";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strtolower($user_name)==strtolower($wall_user_referer_3)) {
				$result_text = "ERROR"; $message_text = "Вы не можете быть рефералом пользователя $wall_user_name. Это ваш реферал III-уровня.";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
				
			

			}else{
				mysql_query("UPDATE `tb_users` SET `referer`='" . $wall_user_name . "', `referer2`='" . $wall_user_referer_1 . "', `referer3`='" . $wall_user_referer_2 . "', `ref_back`='" . $wall_user_ref_back . "' WHERE `username`='" . $user_name . "' AND `referer`=''") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                
				mysql_query("UPDATE `tb_users` SET `referer2`='" . $wall_user_name . "', `referer3`='" . $wall_user_referer_1 . "' WHERE `referer`='" . $user_name . "'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                
				mysql_query("UPDATE `tb_users` SET `referer3`='" . $wall_user_name . "' WHERE `referer2`='" . $user_name . "'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				
                $sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='" . $wall_user_name . "'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                $cnt_referals1 = mysql_num_rows($sql_r1);
                $sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='" . $wall_user_name . "'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                $cnt_referals2 = mysql_num_rows($sql_r2);
                $sql_r3 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='" . $wall_user_name . "'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                $cnt_referals3 = mysql_num_rows($sql_r3);
                
                mysql_query("UPDATE `tb_users` SET `referals`='" . $cnt_referals1 . "', `referals2`='" . $cnt_referals2 . "', `referals3`='" . $cnt_referals3 . "' WHERE `id`='" . $wall_user_id . "'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                
				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('" . $wall_user_name . "','Система','Системное сообщение','У вас новый реферал " . $user_name . ". Присоеденился через Вашу стену','0','" . time() . "','" . $my_lastiplog . "')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
                
				$result_text = "OK";
				$message_text = "Вы успешно стали рефералом пользователя $wall_user_name";
				$cnt = '{"cnt_ref1":"'.$cnt_referals1.'", "cnt_ref2":"'.$cnt_referals2.'"}';
				exit(my_json_encode($ajax_json, $result_text, $message_text, false, false, $cnt));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Пользователь с такими данными не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "GiftPay") {
		$gift_id = ( isset($_POST["gift_id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["gift_id"]))) ) ? intval(trim($_POST["gift_id"])) : false;
		$gift_comment = isset($_POST["gift_comment"]) ? limitatexto(limpiarez($_POST["gift_comment"]), $gift_count_text) : false;
		$gift_privat = ( isset($_POST["gift_privat"]) && preg_match("|^[0-1]{1}$|", trim($_POST["gift_privat"])) ) ? intval(trim($_POST["gift_privat"])) : false;
		$file_img = "gift-$gift_id.png";

		$sql = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$wall_user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$wall_user_id = $row["id"];
			$wall_user_name = $row["username"];
			$token_gift = strtolower(md5($gift_id.$wall_user_id.strtolower($wall_user_name).strtolower($user_name)."PAY_GIFT".$security_key));

			$sql_gift = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='gift_cena'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$gift_cena = number_format(mysql_result($sql_gift,0,0), 2, ".", "");

			if($token_post == false | $token_post != $token_gift){
				$result_text = "ERROR"; $message_text = "Ошибка доступа";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_name == false) {
				$result_text = "ERROR"; $message_text = "Необходимо авторизоваться";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strtolower($user_name)==strtolower($wall_user_name)) {
				$result_text = "ERROR"; $message_text = "Нельзя дарить подарки самому себе";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_ban_date > 0) {
				$result_text = "ERROR"; $message_text = "Вы не можете отправлять подарки, Ваш аккаунт заблокирован";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(!is_file(ROOT_DIR.$FOLDER_GIFTS.$file_img)) {
				$result_text = "ERROR"; $message_text = "Ошибка, подарок не найден";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_money_rb < $gift_cena && ($user_id!=1 | $user_status!=1) && ($user_id!=2 | $user_status!=1)) {
				$result_text = "ERROR"; $message_text = "На Вашем счету недостаточно средств";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				if($user_id!=1 | $user_status!=1) {
					if($user_id!=2 | $user_status!=1) {
						
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$gift_cena' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$gift_cena','Оплата подарка для <b>$wall_user_name</b>','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				   }
			    }
			

				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
				VALUES('$wall_user_name','Система','Системное сообщение','Поступил новый подарок от пользователя $user_name.','0','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("INSERT INTO `tb_users_gifts` (`user_id`,`user_name`,`user_id_gift`,`user_name_gift`,`privat_gift`,`img_gift`,`comment`,`time`,`money`) 
				VALUES('$wall_user_id','$wall_user_name','$user_id','$user_name','$gift_privat','$file_img','$gift_comment','".time()."','$gift_cena')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_last_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_users_gifts`");
				$id_gifts = mysql_result($sql_last_id,0,0);

				$cnt_gifts = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users_gifts` WHERE `user_name`='$wall_user_name'"));

				$sql_gift = mysql_query("SELECT * FROM `tb_users_gifts` WHERE `id`='$id_gifts'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$row_gift = mysql_fetch_assoc($sql_gift);
				$gifts = ListGifts($FOLDER_GIFTS, $row_gift, $user_name, $user_status);

				if($user_id!=1 | $user_status!=1) {
					if($user_id!=2 | $user_status!=1) {
					stat_pay("gifts_pay", $gift_cena);
					invest_stat($gift_cena, 6);
				}
			}

				$result_text = "OK";
				$message_text = '<span class="msg-ok">Подарок успешно отправлен для '.$wall_user_name.'</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text, false, false, $cnt_gifts, $gifts));
			}
		}else{
			$result_text = "ERROR";
			$message_text = "Пользователь с такими данными не найден";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}else{
		$result_text = "ERROR"; $message_text = "ERROR: NO OPTION";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>