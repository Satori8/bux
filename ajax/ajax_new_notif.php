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

function my_json_encode($ajax_json, $result_text, $message_text, $title_page=false) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text), "title_page" => iconv("CP1251", "UTF-8", $title_page))) : $message_text;
}

function count_text($count, $text1, $text2, $text3) {
	if($count>0) {
		if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
			return "$count $text1";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "$count $text1"; break;
				case 2: case 3: case 4: return "$count $text2"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "$count $text3"; break;
			}
		}
	}
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
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");

		function ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url) {
			$ListNotif = false;
			$ListNotif.= '<div class="notif-block" onClick="document.location.href=\''.$notif_url.'\';">';
				$ListNotif.= '<div class="notif-title">'.$notif_title.'</div>';
				$ListNotif.= '<div class="notif-avatar"><img src="'.$notif_avatar.'" class="bg-avatar" /></div>';
				$ListNotif.= '<div class="notif-content">'.$notif_content.'</div>';
			$ListNotif.= '</div>';

			return $ListNotif;
		}

		function GetAvatar($username, $system=false) {
			$GetAvatar = "avatar/no.png";
			if($system!=false && $system==1) {
				$GetAvatar = "avatar/SG.gif";
			}elseif($system!=false && $system==2) {
				$GetAvatar = "avatar/SG.gif";
			}else{
				$sql_i = mysql_query("SELECT `avatar` FROM `tb_users` WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					$row_i = mysql_fetch_assoc($sql_i);
					$GetAvatar = "avatar/".$row_i["avatar"];
				}
			}
			return $GetAvatar;
		}

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlspecialchars(trim($_SESSION["userPas"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;

		if($option == "new_notif") {
			$ListNotif = false;
			$Notif_arr = array();

			$sql_m = mysql_query("SELECT `id`,`nameout`,`date` FROM `tb_mail_in` WHERE `status`='0' AND `namein`='$user_name' ORDER BY `id` ASC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cnt_m = mysql_num_rows($sql_m);

			$sql_tw = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='wait' AND `type`='task' AND `rek_name`='$user_name' AND `ident` IN (SELECT `id` FROM `tb_ads_task` WHERE `username`='$user_name')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cnt_task_wait = mysql_num_rows($sql_tw);

			mysql_query("DELETE FROM `tb_ads_task_notif` WHERE `time`<='".(time()-30*24*60*60)."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			$sql_task = mysql_query("SELECT `type`,`ident`,`rek_name` FROM `tb_ads_task_notif` WHERE `status`='0' AND `user_name`='$user_name' ORDER BY `id` ASC") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$cnt_task = mysql_num_rows($sql_task);
			
			if($cnt_m > 0) {
				while ($row_m = mysql_fetch_assoc($sql_m)) {
					$notif_title = "Новое сообщение";
					$notif_avatar = GetAvatar($row_m["nameout"], ($row_m["nameout"]=="Система" ? 1 : false));

					if($row_m["nameout"]=="Система") {
						$notif_content = 'Поступило новое системное сообщение';
						$notif_content.= '<div style="margin-top:26px; font-size:11px; float:right;">'.DATE("d.m.Y в H:i:s", $row_m["date"]).'</div>';
					}else{
						$notif_content = 'Поступило новое сообщение от пользователя <span style="color:#00FF00;">'.$row_m["nameout"].'</span>';
						$notif_content.= '<div style="margin-top:12px; font-size:11px; float:right;">'.DATE("d.m.Y в H:i:s", $row_m["date"]).'</div>';
					}
					$notif_url = "/inbox.php?view=".$row_m["id"];
					$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);
				}
			}
/*
			if($cnt_m > 0) {
				while ($row_m = mysql_fetch_assoc($sql_m)) {
					$notif_title = "Новое сообщение";
					$notif_avatar = GetAvatar($row_m["nameout"], ($row_m["nameout"]=="Система" ? 1 : false));

					if($row_m["nameout"]=="Система") {
						$notif_content = 'Поступило новое системное сообщение';
						$notif_content.= '<div style="margin-top:26px; font-size:11px; float:right;">'.DATE("d.m.Y в H:i:s", $row_m["date"]).'</div>';
			                        $notif_url = "/prml.php?from=su";
			                       
					}else{
						$notif_content = 'Поступило новое сообщение от пользователя <span style="color:#00FF00;">'.$row_m["nameout"].'</span>';
						$notif_content.= '<div style="margin-top:12px; font-size:11px; float:right;">'.DATE("d.m.Y в H:i:s", $row_m["date"]).'</div>';
			                        $notif_url = "/prml.php?from=" . $row_m["nameout"];
					}
//					$notif_url = "/inbox.php?view=".$row_m["id"];
					$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);
				}
			}
*/
			$sql_u = mysql_query("SELECT `id`,`read_news` FROM `tb_users` WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_u)>0) {
				$row_u = mysql_fetch_assoc($sql_u);
				$my_read_news = $row_u["read_news"] == 0 ? 1 : 0;

				if($my_read_news == 1) {
					$notif_title = "Новости";
					$notif_avatar = GetAvatar(false, 1);

					$notif_content = 'У нас есть свежие новости сайта, которые ждут вашего прочтения.';
					$notif_url = "/news";
					$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);
				}
			}

			if($cnt_task_wait > 0) {
				$notif_title = "Задания на проверку";
				$notif_avatar = GetAvatar(false, 2);
				$notif_content = 'У Вас есть '.count_text($cnt_task_wait, "задание требующее", "задания требующих", "заданий требующих").' подтверждения.';
				$notif_url = "/ads_task.php?page=task&option=task_view";
				$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);
			}

		if($cnt_task > 0 && $cnt_task <= 3) {
				while ($row_task = mysql_fetch_assoc($sql_task)) {
					if($row_task["type"]=="good") {
						$notif_title = "Выполненное задание";
						$notif_avatar = GetAvatar($row_task["rek_name"], false);
						$notif_content = 'Рекламодатель <span style="color:#00FF00;">'.$row_task["rek_name"].'</span> подтвердил выполненное Вами задание (ID: '.$row_task["ident"].').';
						$notif_url = "/view_task.php?page=task&op=stat";
						$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);

					}elseif($row_task["type"]=="good_auto") {
						$notif_title = "Выполненное задание";
						$notif_avatar = GetAvatar(false, 2);
						$notif_content = 'Выполненное Вами задание [ID: '.$row_task["ident"].'] подтверждено автоматически системой.';
						$notif_url = "/view_task.php?page=task&op=stat";
						$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);

					}elseif($row_task["type"]=="dorab") {
						$notif_title = "Задание на доработку";
						$notif_avatar = GetAvatar($row_task["rek_name"], false);
						$notif_content = 'Рекламодатель <span style="color:#00FF00;">'.$row_task["rek_name"].'</span> отправил выполненное Вами задание (ID: '.$row_task["ident"].') на доработку.';
						$notif_url = "/view_task.php?page=task&op=dorab";
						$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);

					}elseif($row_task["type"]=="bad") {
						$notif_title = "Отклоненное задание";
						$notif_avatar = GetAvatar($row_task["rek_name"], false);
						$notif_content = 'Рекламодатель <span style="color:#00FF00;">'.$row_task["rek_name"].'</span> отклонил выполненное Вами задание (ID: '.$row_task["ident"].').';
						$notif_url = "/view_task.php?page=task&op=stat";
						$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);
					}
				}
			}elseif($cnt_task > 3) {
				$notif_title = "Задания";
				$notif_avatar = GetAvatar(false, 2);
				$notif_content = count_text($cnt_task, "новое уведомление о задании", "новых уведомления о заданиях", "новых уведомлений о заданиях");
				$notif_url = "/task_notifications";
				$Notif_arr[] = ListNotif($notif_title, $notif_avatar, $notif_content, $notif_url);
			}

			if(count($Notif_arr) > 0) {
				rsort($Notif_arr);
				for($i=0; $i<=(count($Notif_arr)-1); $i++) {
					$ListNotif.= $Notif_arr[$i];
				}

				$result_text = "OK";
				$message_text = $ListNotif;

				if($my_read_news>0 && $cnt_m==0 && $cnt_task==0 && $cnt_task_wait==0) {
					$title_page = "Новости сайта";

				}elseif($cnt_m>0 && $my_read_news==0 && $cnt_task==0 && $cnt_task_wait==0) {
					$title_page = count_text($cnt_m, "новое сообщение", "новых сообщения", "новых сообщений");

				}elseif(($cnt_task>0 | $cnt_task_wait>0) && $my_read_news==0 && $cnt_m==0) {
					$title_page = count_text($cnt_task, "уведомление о задании", "уведомления о заданиях", "уведомлений о заданиях");

				}else{
					$title_page = "Новые уведомления";
				}

				exit(my_json_encode($ajax_json, $result_text, $message_text, $title_page));
			}else{
				$result_text = "ERROR"; $message_text = "Нет уведомлений!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = "ERROR: NO OPTION";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "Необходимо авторизоваться!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$result_text = "ERROR"; $message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text));
?>