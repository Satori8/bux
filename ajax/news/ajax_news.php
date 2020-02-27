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

function my_json_encode($ajax_json, $result_text, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
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

	return $mensaje;
}

function smile($mes) {
	$mes = str_ireplace("<br><br>", "<br>", $mes);

	for($i=0; $i<=45; $i++) {
		$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-$i.gif\" alt=\"\" align=\"absmiddle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
	}
	return $mes;
}

function InfoUser($user_id) {
	$sql_i = mysql_query("SELECT `id`,`username`,`imay`,`reiting`,`avatar`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_i)>0) {
		$row_i = mysql_fetch_assoc($sql_i);
		$info_user["id"] = $row_i["id"];
		$info_user["username"] = $row_i["username"];
		$info_user["imay"] = $row_i["imay"];
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
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/funciones.php");
	require(ROOT_DIR."/config.php");
	require_once(ROOT_DIR."/merchant/func_cache.php");
	require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
	$id_news = ( isset($_POST["id_news"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id_news"]))) ) ? intval(trim($_POST["id_news"])) : false;
	$id_comm = ( isset($_POST["id_comm"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id_comm"]))) ) ? intval(trim($_POST["id_comm"])) : false;
	$my_lastiplog = getRealIP();

	$news_file = ROOT_DIR."/cache/cache_news.inc";
	$news_arr = is_file($news_file) ? @unserialize(file_get_contents($news_file)) : false;
	$count_com_text = 500;

	$sql_user = mysql_query("SELECT `id`,`username`,`imay`,`avatar`,`ban_date`,`reiting`,`user_status` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_assoc($sql_user);
		$user_id = $row_user["id"];
		$user_name = $row_user["username"];
		$user_imay = $row_user["imay"];
		$user_avatar = $row_user["avatar"];
		$user_ban_date = $row_user["ban_date"];
		$user_reiting = $row_user["reiting"];
		$user_status = $row_user["user_status"];

		mysql_query("UPDATE `tb_users` SET `read_news`='1' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	}else{
		$user_id = false;
		$user_name = false;
		$user_imay = false;
		$user_avatar = false;
		$user_ban_date = false;
		$user_reiting = false;
		$user_status = false;
	}


	if($option == "LoadPage" && !isset($_POST["id_news"])) {
		$message_text = false;

		if(is_array($news_arr) && count($news_arr)>0) {
			$perpage = 5;
			$count = count($news_arr);
			$pages_count = ceil($count / $perpage);
			$news_arr = array_slice($news_arr, 0, $perpage, false);
			$count_news = count($news_arr);

			$message_text.= '<table class="newssites tables_inv" id="table-news">';
			$message_text.= '<tbody>';
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
							$message_text.= '<span class="news_comment" onClick="LoadNews(\''.$news_arr[$i]["id_news"].'\'); return false;">Комментарии <span>'.$news_arr[$i]["comments_news"].'</span></a>';
						}
					$message_text.= '</td>';
				$message_text.= '</tr>';
				$message_text.= '<tr><td style="border:none; background:none; height:1px; padding:1px 0px;"></td></tr>';
			}
			$message_text.= '</tbody>';
			$message_text.= '</table>';
			if($count>$perpage) $message_text.= '<div id="load-pages" data-load="news" data-id="table-news" data-idb="" data-page="1" data-close="'.$pages_count.'" data-num="'.$perpage.'" data-status="0" data-hash="'.md5("48915022".$pages_count.$perpage).'" data-link="ajax/news/ajax_news_page.php" onclick="LoadPages();">Показать ещё</div>';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = "Новостей нет!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

	}elseif($option == "LoadPage" && isset($_POST["id_news"])) {
		$message_text = false;

		if(is_array($news_arr) && isset($news_arr[$id_news])) {
			$message_text.= '<table class="newssites tables_inv" id="table-news">';
			$message_text.= '<tbody>';
			$message_text.= '<tr><td class="messtitle">'.DATE("d.m.Y", $news_arr[$id_news]["time_news"]).'</td></tr>';
			$message_text.= '<tr>';
				$message_text.= '<td valign="top" style="border: none; text-align: justify;">';
					$message_text.= '<div class="message" style="padding-bottom:3px;">';
						$message_text.= '<div class="newssitestitle">'.$news_arr[$id_news]["title_news"].'</div>';
						$message_text.= '<div>'.$news_arr[$id_news]["desc_news"].'</div>';
					$message_text.= '</div>';
				$message_text.= '</td>';
			$message_text.= '</tr>';
			$message_text.= '<tr><td style="border:none; background:none; height:1px; padding:1px 0px;"></td></tr>';
			$message_text.= '</tbody>';
			$message_text.= '</table>';

			if($user_name != false) {
				if($news_arr[$id_news]["comments_news_status"] == 1) {
					$message_text.= '<div id="AddComment" align="center" class="news_add_comment" onClick="LoadFormCom(\''.$id_news.'\'); return false;">Оставить свой комментарий</div>';
				}elseif($news_arr[$id_news]["comments_news_status"] != 1 && $news_arr[$id_news]["comments_news"] > 0) {
					$message_text.= '<div id="AddComment" align="center" style="margin:10px auto; font-weight:bold; color:#828282;"><div color="lime" size="2" style="text-shadow:0 0 7px lime;"><i><span style="color:#ff0000;font-size:14px">Комментирование новости закрыто. Всем спасибо за участие!</span></i></div></div>';
				}
			}else{
				$message_text.= '<div id="AddComment" align="center" style="margin:20px auto;"></div>';
			}

			$perpage = 10;
			$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_news_comments` WHERE `ident`='$id_news'"));
			$pages_count = ceil($count / $perpage);
			$start_pos = 0;

			$message_text.= '<div id="news-comments">';
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
					//$message_text.= $sql_igr_us["imay"] != false ? $sql_igr_us["imay"] : '';
									$message_text.= $InfoUser["id"] != false ?   $sql_igr_us["imay"]."&nbsp;".$InfoUser["reiting"]."&nbsp;".$InfoUser["wall_com"] : '[<span style="color:#FF0000;">пользователь удален</span>]';
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
			$message_text.= '</div>';

			if($count>$perpage) $message_text.= '<div id="load-pages" data-load="news-comments" data-id="news-comments" data-idb="'.$id_news.'" data-page="1" data-close="'.$pages_count.'" data-num="'.$perpage.'" data-status="0" data-hash="'.md5("48915022".$pages_count.$perpage).'" data-link="ajax/news/ajax_news_page.php" onclick="LoadPages();">Показать ещё</div>';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = "Новость не найдена!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "LoadFormCom") {
		$message_text = false;

		$message_text.= '<div class="box-modal" id="FormCom" style="text-align:justify; width:600px;">';
			$message_text.= '<div class="box-modal-title">Комментарий к новости</div>';
			$message_text.= '<div class="box-modal-close modalpopup-close"></div>';
			$message_text.= '<div class="box-modal-content" style="margin:0 auto; padding:8px 12px; font-size:13px; text-align:justify;">';

			if(!isset($news_arr[$id_news])) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Новость не найдена!</div>';

			}elseif(isset($news_arr[$id_news]) && $news_arr[$id_news]["comments_news_status"] != 1) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Комментирование данной новости закрыто!</div>';

			}elseif($user_name == false) {
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Для комментирования новости необходимо авторизоваться!</div>';

			}elseif($user_ban_date > 0){
				$message_text.= '<div class="msg-error" style="margin:5px auto;">Вы не можете оставить комментарий, Ваш аккаунт заблокирован!</div>';

			}else{
				$message_text.= '<table class="tables_inv" id="newform" style="margin:5px auto;">';
				$message_text.= '<thead><tr><th align="center">Ваш комментарий</th></tr><thead>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td style="padding:3px 8px;">';
							$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
								$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'comment_text\'); return false;">Ж</span>';
								$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'comment_text\'); return false;">К</span>';
								$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'comment_text\'); return false;">Ч</span>';
							$message_text.= '</div>';
							$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$count_com_text.'</div>';
							$message_text.= '<div style="display: block; padding-top:4px">';
								$message_text.= '<textarea id="comment_text" class="ok" style="height:120px;" placeholder="Укажите текст комментария" onKeyup="descchange(\'1\', this, \''.$count_com_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$count_com_text.'\');" onClick="descchange(\'1\', this, \''.$count_com_text.'\');"></textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" style="padding:3px 8px;">';
							$message_text.= '<div id="Smiles" align="center">';
								for($i=0; $i<45; $i++){
									$message_text.= '<span onClick="InSmile(\'comment_text\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
								}
							$message_text.= '</div>';
					$message_text.= '</tr>';
					$message_text.= '<tr id="tr-info" style="display:none;">';
						$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-com"></div></td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';

				$message_text.= '<div align="center"><span onClick="AddComNews(\''.$id_news.'\');" class="news_add_comment" style="float:none; width:150px;">Добавить комментарий</span></div>';
			}
			$message_text.= '</div>';
		$message_text.= '</div>';
		
		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));


	}elseif($option == "AddComNews") {
		$comment_text = isset($_POST["comment_text"]) ? limpiarez($_POST["comment_text"]) : false;
		$comment_text = get_magic_quotes_gpc() ? stripslashes($comment_text) : $comment_text;
		$message_text = false;

		if(!isset($news_arr[$id_news])) {
			$message_text.= "Новость не найдена!";
			$result_text = "ERROR";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif(isset($news_arr[$id_news]) && $news_arr[$id_news]["comments_news_status"] != 1) {
			$message_text.= "Комментирование данной новости закрыто!";
			$result_text = "ERROR";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($user_name == false) {
			$message_text.= "Для комментирования новости необходимо авторизоваться!";
			$result_text = "ERROR";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($user_ban_date > 0){
			$result_text = "ERROR"; $message_text = "Вы не можете оставить комментарий, Ваш аккаунт заблокирован!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}else{
			$sql_check = mysql_query("SELECT `id` FROM `tb_news_comments` WHERE `ident`='$id_news' AND `user_name`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_check)>0 && $user_status != "1") {
				$result_text = "ERROR"; $message_text = "Вы уже комментировали данную новость!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($comment_text == false) {
				$result_text = "ERROR"; $message_text = "Укажите текст комментария!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif(strlen($comment_text) < 10) {
				$result_text = "ERROR"; $message_text = "Текст комментария должен содержать минимум 10 символов!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("INSERT INTO `tb_news_comments` (`ident`,`user_name`,`user_id`,`comment`,`time`,`ip`) 
				VALUES('$id_news','$user_name','$user_id','$comment_text','".time()."','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$id_last_com = mysql_result(mysql_query("SELECT LAST_INSERT_ID() FROM `tb_news_comments`"),0,0) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				mysql_query("UPDATE `tb_news` SET `comments`=`comments`+'1' WHERE `id`='$id_news'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				cache_news();

				$sql_last_com = mysql_query("SELECT * FROM `tb_news_comments` WHERE `id`='$id_last_com' AND `user_name`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_last_com)>0) {
					$row_com = mysql_fetch_assoc($sql_last_com);

					$InfoUser = InfoUser($row_com["user_id"]);

					$message_text.= '<table class="tables_inv" style="margin-top:1px; margin-bottom:10px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);" id="news-comment-'.$row_com["id"].'">';
					$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td colspan="'.($user_status==1 ? "3" : "2").'" style="border-top:none; border-right:none; padding:5px 5px;">';
							$message_text.= '<span style="float:left;">';
								$message_text.= '<span style="color:#0080EC; font-size:12px; font-weight: bold; text-shadow: 0 1px 0 #FFF, 1px 2px 2px #AAA; margin-right:8px;">'.$row_com["user_name"].'</span>';
								$message_text.= $InfoUser["id"] != false ? $InfoUser["reiting"]."&nbsp;".$InfoUser["wall_com"] : '[<span style="color:#FF0000;">пользователь удален</span>]';
							$message_text.= '</span>';
							$message_text.= '<span style="float:right; color:#4F4F4F; text-shadow: 0 1px 0 #FFF, 1px 1px 1px #AAA;">'.DATE("H:i d.m.Yг.", $row_com["time"]).'</span>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td align="center" valign="top" style="border:none; width:75px; background:#FFFFF0; padding:6px 0px 6px 4px;">';
							$message_text.= '<img src="../avatar/'.($InfoUser["id"] != false ? $InfoUser["avatar"] : "no.png").'" class="avatar" border="0" alt="" align="absmiddle" />';
						$message_text.= '</td>';
						$message_text.= '<td align="justify" valign="top" style="border:none; background:#FFFFF0; padding:6px 10px 6px 2px;">';
							$message_text.= smile(desc_bb($row_com["comment"]));
							$message_text.= '<div id="answer-'.$row_com["id"].'"></div>';
						$message_text.= '</td>';
						if($user_status==1) {
							$message_text.= '<td align="left" valign="top" style="width:40px; border:0px solid #CCC; background:#FFFFF0; padding:8px 4px;">';
								$message_text.= '<span onClick="DelComNews(\''.$row_com["id"].'\', \''.$row_com["user_name"].'\');" class="workcomp" title="Удалить комментарий"></span>';
								$message_text.= '<span id="sub-comm-'.$row_com["id"].'" onClick="LoadFormAnsw(\''.$row_com["id"].'\');" class="add_comment" title="Ответить на комментарий"></span>';
							$message_text.= '</td>';
						}
					$message_text.= '</tr>';
					$message_text.= '</tbody>';
					$message_text.= '</table>';

					$result_text = "OK";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					$result_text = "ERROR"; $message_text = "Что-то пошло не так, комментарий не найден!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}
		}		


	}elseif($option == "DelComNews") {
		$message_text = false;

		if($user_name != false && $user_status==1 && $user_ban_date == 0) {
			$sql_com = mysql_query("SELECT `ident` FROM `tb_news_comments` WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_com)>0) {
				$row_com = mysql_fetch_assoc($sql_com);

				mysql_query("UPDATE `tb_news` SET `comments`=`comments`-'1' WHERE `id`='".$row_com["ident"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("DELETE FROM `tb_news_comments` WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				cache_news();

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "Что-то пошло не так, комментарий не найден!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = "Не достаточно прав для удаления комментария!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "LoadFormAnsw") {
		$message_text = false;

		$sql_com = mysql_query("SELECT * FROM `tb_news_comments` WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_com)>0) {
			$row_com = mysql_fetch_assoc($sql_com);

			$message_text.= '<div class="box-modal" id="FormAnsw" style="text-align:justify; width:600px;">';
				$message_text.= '<div class="box-modal-title">Ответ на комментарий пользователя <b>'.$row_com["user_name"].'</b></div>';
				$message_text.= '<div class="box-modal-close modalpopup-close"></div>';
				$message_text.= '<div class="box-modal-content" style="margin:0 auto; padding:8px 12px; font-size:13px; text-align:justify;">';
					if($user_name == false) {
						$message_text.= '<div class="msg-error" style="margin:5px auto;">Для ответа на комментарий необходимо авторизоваться!</div>';

					}elseif($user_ban_date > 0) {
						$message_text.= '<div class="msg-error" style="margin:5px auto;">Вы не можете ответить на комментарий, Ваш аккаунт заблокирован!</div>';

					}elseif($user_status != 1) {
						$message_text.= '<div class="msg-error" style="margin:5px auto;">Не достаточно прав для ответа на комментарий!</div>';

					}else{ 
						$message_text.= '<table class="tables_inv" id="newform" style="margin:5px auto;">';
						$message_text.= '<thead><tr><th align="center">Ваш ответ на комментарий</th></tr><thead>';
						$message_text.= '<tbody>';
							$message_text.= '<tr>';
								$message_text.= '<td style="padding:3px 8px;">';
									$message_text.= '<div style="display: inline-block; float:left; padding-top:4px;">';
										$message_text.= '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'answer_text\'); return false;">Ж</span>';
										$message_text.= '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'answer_text\'); return false;">К</span>';
										$message_text.= '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'answer_text\'); return false;">Ч</span>';
									$message_text.= '</div>';
									$message_text.= '<div id="count1" style="display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5;">Осталось символов: '.$count_com_text.'</div>';
									$message_text.= '<div style="display: block; padding-top:4px">';
										$message_text.= '<textarea id="answer_text" class="ok" style="height:120px;" placeholder="Укажите текст ответа на комментарий пользователя" onKeyup="descchange(\'1\', this, \''.$count_com_text.'\');" onKeydown="$(this).attr(\'class\', \'ok\'); descchange(\'1\', this, \''.$count_com_text.'\');" onClick="descchange(\'1\', this, \''.$count_com_text.'\');">'.$row_com["comment_answ"].'</textarea>';
									$message_text.= '</div>';
								$message_text.= '</td>';
							$message_text.= '</tr>';
							$message_text.= '<tr>';
								$message_text.= '<td align="center" style="padding:3px 8px;">';
									$message_text.= '<div id="Smiles" align="center">';
										for($i=0; $i<45; $i++){
											$message_text.= '<span onClick="InSmile(\'answer_text\', \'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="absmiddle" border="0" style="cursor:pointer; padding:0; margin:0; margin-right:5px;"></span>';
										}
									$message_text.= '</div>';
							$message_text.= '</tr>';
							$message_text.= '<tr id="tr-info-answ" style="display:none;">';
								$message_text.= '<td align="center" style="padding:2px; margin:0;"><div id="info-msg-answ"></div></td>';
							$message_text.= '</tr>';
						$message_text.= '</tbody>';
						$message_text.= '</table>';

						$message_text.= '<div align="center"><span onClick="AddAnswCom(\''.$id_comm.'\');" class="news_add_comment" style="float:none; width:150px;">Добавить ответ</span></div>';
					}
				$message_text.= '</div>';
			$message_text.= '</div>';
		
			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = "Комментарий не найден!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}


	}elseif($option == "AddAnswCom") {
		$answer_text = isset($_POST["answer_text"]) ? limpiarez($_POST["answer_text"]) : false;
		$answer_text = get_magic_quotes_gpc() ? stripslashes($answer_text) : $answer_text;
		$message_text = false;

		if($user_name == false) {
			$result_text = "ERROR"; $message_text = "Для ответа на комментарий необходимо авторизоваться!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($user_ban_date > 0) {
			$result_text = "ERROR"; $message_text = "Вы не можете ответить на комментарий, Ваш аккаунт заблокирован!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($user_status != 1) {
			$result_text = "ERROR"; $message_text = "Не достаточно прав для ответа на комментарий!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif($answer_text == false) {
			$result_text = "ERROR"; $message_text = "Укажите текст ответа на комментарий!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}elseif(strlen($answer_text) < 10) {
			$result_text = "ERROR"; $message_text = "Текст ответа на комментарий должен содержать минимум 10 символов!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}else{ 
			$sql_com = mysql_query("SELECT `id` FROM `tb_news_comments` WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_com)>0) {
				mysql_query("UPDATE `tb_news_comments` SET `comment_answ`='$answer_text' WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$sql_answ = mysql_query("SELECT `user_name`,`comment_answ` FROM `tb_news_comments` WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				$row_answ = mysql_fetch_assoc($sql_answ);

				//$message_text = smile(desc_bb($row_answ["comment_answ"]));

				$message_text = false;
				$message_text.= '<span class="news_answer">';
					if($user_status==1) {
						$message_text.= '<div align="right" style="float: right;">';
							$message_text.= '<span onClick="DelAnswCom(\''.$id_comm.'\', \''.$row_answ["user_name"].'\');" class="workcomp" title="Удалить ответ"></span>';
							$message_text.= '<span onClick="LoadFormAnsw(\''.$id_comm.'\');" class="edit_comment" title="Редактировать ответ на комментарий"></span>';
						$message_text.= '</div>';
					}
					$message_text.= '<span class="news_answer_title">Ответ администратора:</span>';
					$message_text.= '<span class="news_answer_text">'.smile(desc_bb($row_answ["comment_answ"])).'</span>';
				$message_text.= '</span>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "Комментарий не найден!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}


	}elseif($option == "DelAnswCom") {
		$message_text = false;

		if($user_name != false && $user_status==1 && $user_ban_date == 0) {
			$sql_com = mysql_query("SELECT `ident` FROM `tb_news_comments` WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_com)>0) {
				$row_com = mysql_fetch_assoc($sql_com);

				mysql_query("UPDATE `tb_news_comments` SET `comment_answ`='' WHERE `id`='$id_comm'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "Что-то пошло не так, комментарий не найден!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = "Не достаточно прав для удаления комментария!";
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