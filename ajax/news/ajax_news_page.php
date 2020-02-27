<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "page" => "Access denied!", "ajax_code" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $page, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "page" => intval($page), "ajax_code" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function desc_bb($desc) {
	$desc = new bbcode($desc);
	$desc = $desc->get_html();
	$desc = str_replace("&amp;", "&", $desc);
	return $desc;
}

function smile($mes) {
	$mes = str_ireplace("<br><br>", "<br>", $mes);
	for($i=0; $i<=45; $i++) {
		$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-$i.gif\" alt=\"\" align=\"absmiddle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
	}
	return $mes;
}

function InfoUser($user_id) {
	$sql_i = mysql_query("SELECT `id`,`username`,`reiting`,`avatar`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_i)>0) {
		$row_i = mysql_fetch_assoc($sql_i);
		$info_user["id"] = $row_i["id"];
		$info_user["username"] = $row_i["username"];
		$info_user["reiting"] = $row_i["reiting"];
		$info_user["avatar"] = $row_i["avatar"];
		$info_user["wall_com"] = ($row_i["wall_com_p"] - $row_i["wall_com_o"]);

		if($info_user["wall_com"]>0) {
			$info_user["wall_com"] = '<b style="color:#008000;">'.$info_user["wall_com"].'</b>';
		}elseif($info_user["wall_com"]<0) {
			$info_user["wall_com"] = '<b style="color:#FF0000;">-'.abs($info_user["wall_com"]).'</b>';
		}else{
			$info_user["wall_com"] = '<b>0</b>';
		}

		$info_user["reiting"] = '<img src="img/reiting.png" border="0" alt="" align="absmiddle" title="Рейтинг" style="margin:0; padding:0;" />&nbsp;<span style="color:blue;">'.round($info_user["reiting"], 2).'</span>';
		$info_user["wall_com"] = '<a href="/wall?uid='.$info_user["id"].'" target="_blank" style="text-decoration:none;"><img src="img/wall20.png" title="Стена пользователя" width="16" height="16" border="0" align="absmiddle" style="margin-left:5px;" />&nbsp;'.$info_user["wall_com"].'</a>';
	}else{
		$info_user["id"] = false;
	}

	return $info_user;
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
	exit(my_json_encode($ajax_json, "ERROR", "0", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/funciones.php");
	require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;

	$option = ( isset($_POST["load"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["load"]))) ) ? htmlspecialchars(trim($_POST["load"])) : false;
	$page = ( isset($_POST["page"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["page"]))) ) ? htmlspecialchars(trim($_POST["page"])) : false;
	$perpage = ( isset($_POST["num"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["num"]))) ) ? htmlspecialchars(trim($_POST["num"])) : false;
	$id_news = ( isset($_POST["idb"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["idb"]))) ) ? intval(trim($_POST["idb"])) : false;
	$hash_post = ( isset($_POST["hash"]) && preg_match("/^[0-9a-fA-F]{32}$/", htmlspecialchars(trim($_POST["hash"]))) ) ? htmlspecialchars(trim($_POST["hash"])) : false;

	if($option == "news") {
		$news_file = ROOT_DIR."/cache/cache_news.inc";
		$news_arr = is_file($news_file) ? @unserialize(file_get_contents($news_file)) : false;
		$message_text = false;

		if(is_array($news_arr) && count($news_arr)>0) {
			$count = count($news_arr);
			$pages_count = ceil($count / $perpage);
			$start_pos = intval($page * $perpage);

			$news_arr = array_slice($news_arr, $start_pos, $perpage, false);
			$count_news = count($news_arr);

			for($i=0; $i<$count_news && $count_news>0; $i++) {
				$message_text.= '<tr><td class="messtitle">'.DATE("d.m.Y", $news_arr[$i]["time_news"]).'</td></tr>';
				$message_text.= '<tr>';
					$message_text.= '<td valign="top" style="border: none; text-align: justify;">';
						$message_text.= '<div class="message" style="padding-bottom:3px;">';
							$message_text.= '<div class="newssitestitle">'.$news_arr[$i]["title_news"].'</div>';
							$message_text.= '<div>'.$news_arr[$i]["desc_news"].'</div>';
						$message_text.= '</div>';
						if(isset($news_arr[$i]["link_forum_news"]) && $news_arr[$i]["link_forum_news"] != false) {
							$message_text.= '<div class="news_forum" onClick="GoForum(\''.$news_arr[$i]["link_forum_news"].'\');">Обсудить на форуме</div>';
						}
						if($news_arr[$i]["comments_news_status"] == 1 | $news_arr[$i]["comments_news"] > 0) {
							$message_text.= '<div class="news_comment" onClick="LoadNews(\''.$news_arr[$i]["id_news"].'\');">Комментарии <span>'.$news_arr[$i]["comments_news"].'</span></div>';
						}
					$message_text.= '</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr><td style="border:none; background:none; height:1px; padding:1px 0px;"></td></tr>';
			}

			if($message_text != false) {
				$result_text = "OK";
				$page++;
				exit(my_json_encode($ajax_json, $result_text, $page, $message_text));
			}
		}

	}elseif($option == "news-comments") {
		require(ROOT_DIR."/config.php");

		$sql_user = mysql_query("SELECT `id`,`user_status` FROM `tb_users` WHERE `username`='$user_name' AND `password`='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_status = $row_user["user_status"];
		}else{
			$user_id = false;
			$user_status = false;
		}

		$message_text = false;
		$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_news_comments` WHERE `ident`='$id_news'"));
		$pages_count = ceil($count / $perpage);
		$start_pos = intval($page * $perpage);

		$sql_last_com = mysql_query("SELECT * FROM `tb_news_comments` WHERE `ident`='$id_news' ORDER BY `id` DESC LIMIT $start_pos, $perpage") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_last_com)>0) {
			while($row_com = mysql_fetch_assoc($sql_last_com)) {
				$InfoUser = InfoUser($row_com["user_id"]);
				
				$sql_igr_us=mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$row_com["user_name"]."'"));

				$message_text.= '<table class="tables_inv" style="margin-top:1px; margin-bottom:10px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);" id="news-comment-'.$row_com["id"].'">';
				$message_text.= '<tbody>';
				$message_text.= '<tr>';
					$message_text.= '<td colspan="'.($user_status==1 ? "3" : "2").'" style="border-top:none; border-right:none; padding:5px 5px;">';
						$message_text.= '<span style="float:left;">';
							$message_text.= '<span style="color:#0080EC; font-size:12px; font-weight: bold; text-shadow: 0 1px 0 #FFF, 1px 2px 2px #AAA; margin-right:8px;">'.$row_com["user_name"].'</span>';
							$message_text.= $InfoUser["id"] != false ? $sql_igr_us["imay"]."&nbsp;".$InfoUser["reiting"]."&nbsp;".$InfoUser["wall_com"] : '[<span style="color:#FF0000;">пользователь удален</span>]';
						$message_text.= '</span>';
						$message_text.= '<span style="float:right; color:#4F4F4F; text-shadow: 0 1px 0 #FFF, 1px 1px 1px #AAA;">'.DATE("H:i d.m.Yг.", $row_com["time"]).'</span>';
					$message_text.= '</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr>';
					$message_text.= '<td align="center" valign="top" style="border:none; width:75px; background:#FFFFF0; padding:6px 0px 10px 4px;">';
						$message_text.= '<img src="../avatar/'.($InfoUser["id"] != false ? $InfoUser["avatar"] : "no.png").'" class="avatar" style="width:40px;height:40px;" border="0" alt="" align="absmiddle" />';
					$message_text.= '</td>';
					$message_text.= '<td align="justify" valign="top" style="border:none; background:#FFFFF0; padding:6px 10px 6px 20px;">';
						$message_text.= smile(desc_bb($row_com["comment"]));
						$message_text.= '<div id="answer-'.$row_com["id"].'">';
							if(trim($row_com["comment_answ"]) != false) {
								$message_text.= '<span class="news_answer">';
									if($user_status==1) {
										$message_text.= '<div align="right" style="float: right;">';
											$message_text.= '<span onClick="DelAnswCom(\''.$row_com["id"].'\', \''.$row_com["user_name"].'\');" class="workcomp" title="Удалить ответ"></span>';
											$message_text.= '<span onClick="LoadFormAnsw(\''.$row_com["id"].'\');" class="edit_comment" title="Редактировать ответ на комментарий"></span>';
										$message_text.= '</div>';
									}
									$message_text.= '<span class="news_answer_title">Ответ администратора:</span>';
									$message_text.= '<span class="news_answer_text">'.smile(desc_bb($row_com["comment_answ"])).'</span>';
								$message_text.= '</span>';
							}
						$message_text.= '</div>';
					$message_text.= '</td>';
					if($user_status==1) {
						$message_text.= '<td align="left" valign="top" style="width:40px; border:0px solid #CCC; background:#FFFFF0; padding:8px 4px;">';
									$message_text.= '<span onClick="DelComNews(\''.$row_com["id"].'\', \''.$row_com["user_name"].'\');" class="workcomp" title="Удалить комментарий"></span>';
									$message_text.= '<span id="sub-comm-'.$row_com["id"].'" '.(trim($row_com["comment_answ"]) == false ? false : 'style="display:none;"').' onClick="LoadFormAnsw(\''.$row_com["id"].'\');" class="add_comment" title="Ответить на комментарий"></span>';
								$message_text.= '</td>';
					}
				$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';
			}
		}

		$result_text = "OK";
		$page++;
		exit(my_json_encode($ajax_json, $result_text, $page, $message_text));

	}else{
		$result_text = "ERROR"; $message_text = "ERROR OPTION";
		exit(my_json_encode($ajax_json, $result_text, 0, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, 0, $message_text));
}


$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, 0, $message_text));

?>