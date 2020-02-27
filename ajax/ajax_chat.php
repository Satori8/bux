<?php 
session_start();
header("Content-type: text/html; charset=windows-1251");
if( !DEFINED("ROOT_DIR") ) 
{
    DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
}
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	 global $ajax_json;
    global $message_text;
	
	$errfile = str_replace(ROOT_DIR, "", $errfile);
	switch($errno) {
		case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
		case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
		case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
		default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
	}
	//$message_text = '<div class="block-error">'.$message_text.'</div>';
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u04'", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function arrIconv($from, $to, $obj)
{
    if( is_array($obj) | is_object($obj) ) 
    {
        foreach( $obj as &$val ) 
        {
            $val = arrIconv($from, $to, $val);
        }
        return $obj;
    }
    else
    {
        return iconv($from, $to, $obj);
    }

}

function my_json_encode($result_text, $message_text)
{
    global $ajax_json;
    return ($ajax_json == "json" ? json_encode_cp1251(array( "result" => iconv("CP1251", "UTF-8", $result_text), "message" => arriconv("CP1251", "UTF-8", $message_text) )) : $message_text);
}

function json_encode_socket($data)
{
    return json_encode(arriconv("CP1251", "UTF-8", $data));
}

function escape($value)
{
    global $mysqli;
    return $mysqli->real_escape_string($value);
}

function limpiarez($mensaje, $erase = false)
{
    $mensaje = trim($mensaje);
    $mensaje = str_ireplace(array( "`", "\$", "&&", "  " ), array( "'", "&#036;", "&", " " ), $mensaje);
    $mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);
    //$mensaje = preg_replace(array( "#\\[img\\](.*?)\\[/img\\]#si", "#\\[img\\]#si", "#\\[/img\\]#si" ), array( "\$1", "", "" ), $mensaje);
    $mensaje = preg_replace(array( "#\\[b\\]\\[/b\\]#si", "#\\[i\\]\\[/i\\]#si", "#\\[u\\]\\[/u\\]#si", "#\\[s\\]\\[/s\\]#si" ), array( "\$1", "\$1", "\$1", "\$1" ), $mensaje);
    $mensaje = strip_tags($mensaje);
    $mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars($mensaje, ENT_QUOTES, "CP1251", false));
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, "CP1251", false);
    if( $erase != false ) 
    {
        $mensaje = preg_replace_callback("#https?://[^\\s]++#iU", "checkURL", $mensaje);
    }

    return trim($mensaje);
}

function checkURL($matches)
{
	global $allowed_url;

	foreach ($matches as $key => $val ) {
		$_obfuscate_DQQkCwoWERYRFCc8Ng8_EjsJJRM_PDI = InsIMG($val);

		if ($_obfuscate_DQQkCwoWERYRFCc8Ng8_EjsJJRM_PDI != false) {
			$matches[$key] = $_obfuscate_DQQkCwoWERYRFCc8Ng8_EjsJJRM_PDI;
		}
		 else if (array_search(getHost($val), $allowed_url) === false) {
			//$matches[$key] = '[font color="red"]' . "ссылка" . ' ' . "вырезана" . '[/font]';
			$matches[$key] = "[img]https://lilacbux.com/images/otyn/no_task.png[/img] [br][u][font color=\"red\"]Cсылка вырезана[/font][/u]";
		}

	}

	return (isset($matches[0]) ? $matches[0] : false);
}

function InsIMG($url) {
	global $max_size_img;
	$taime = 2;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $taime);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$content = curl_exec($curl);
	$status = trim(curl_getinfo($curl, CURLINFO_CONTENT_TYPE));
	$video = str_replace('www.', '', parse_url($url, PHP_URL_HOST));
	curl_close($curl);
	$img_video = array('image/PJPEG' => 'jpeg', 'image/pjpeg' => 'jpeg', 'image/JPEG' => 'jpeg', 'image/jpeg' => 'jpeg', 'image/JPG' => 'jpg', 'image/jpg' => 'jpg', 'image/GIF' => 'gif', 'image/gif' => 'gif', 'image/X-PNG' => 'png', 'image/x-png' => 'png', 'image/PNG' => 'png', 'image/png' => 'png', 'image/BMP' => 'bmp', 'image/bmp' => 'bmp');
	$youtuber = $youtube = $youtube_img = 1;

	if (($status != false) && (array_key_exists($status, $img_video) !== false)) {
		$video_img = imagecreatefromstring($content);
		$imagereplace = imagesx($video_img);
		$videoreplace = imagesy($video_img);
		$youtube = (($max_size_img < $imagereplace ? round($imagereplace / $max_size_img, 2) : $youtuber));
		$youtube_img = (($max_size_img < $videoreplace ? round($videoreplace / $max_size_img, 2) : $youtuber));
		$youtuber = (($youtube_img <= $youtube ? $youtube : $youtube_img));
		$youtuber_img = ((1 < $youtuber ? floor($imagereplace / $youtuber) : $imagereplace));
		$youtuber_vid = ((1 < $youtuber ? floor($videoreplace / $youtuber) : $videoreplace));
		return '[img width="' . $youtuber_img . '" height="' . $youtuber_vid . '"]' . $url . '[/img]';
	}


	if (($video == 'youtube.com') | ($video == 'youtu.be')) {
		$parser = parse_url($url, PHP_URL_QUERY);
		$video_parser = (($parser != false ? parse_str($parser, $youtuber_parser) : false));
		$youtube_parser = ((isset($youtuber_parser['v']) ? $youtuber_parser['v'] : false));
		$youtube_parser = (($video == 'youtu.be' ? str_ireplace('/', '', parse_url($url, PHP_URL_PATH)) : $youtube_parser));
		return ($youtube_parser != false ? '<iframe src="//www.youtube.com/embed/' . $youtube_parser . '" frameborder="0"></iframe>' : false);
	}


	return false;
}

function desc_bb($desc)
{
    $desc = str_ireplace(array( "[url][font color=\"red\"]", "[url=[font color=\"red\"]", "[a][font color=\"red\"]", "[a=[font color=\"red\"]" ), "[font color=\"red\"]", $desc);
    $desc = new bbcode($desc);
    $desc = $desc->get_html();
    $desc = str_ireplace("&amp;", "&", $desc);
    return smile($desc);
}

function smile($mensaje)
{
    return preg_replace("#:smile-([\\d]|[1-9][\\d]|100):#si", "<img src=\"pohta/smile-\$1.gif\" class=\"smile_img\" alt=\"\" />", $mensaje);
}

function LengthMessPay($mensaje)
{
	$mensaje = trim($mensaje);
	$mensaje = str_ireplace(array(' ', "\r", "\n"), '', $mensaje);
	$mensaje = preg_replace(array('#\\[b(.*?)\\](.*?)\\[/b\\]#si', '#\\[b\\]#si', '#\\[/b\\]#si'), array('$2', '', ''), $mensaje);
	$mensaje = preg_replace(array('#\\[i(.*?)\\](.*?)\\[/i\\]#si', '#\\[i\\]#si', '#\\[/i\\]#si'), array('$2', '', ''), $mensaje);
	$mensaje = preg_replace(array('#\\[u(.*?)\\](.*?)\\[/u\\]#si', '#\\[u\\]#si', '#\\[/u\\]#si'), array('$2', '', ''), $mensaje);
	$mensaje = preg_replace(array('#\\[s(.*?)\\](.*?)\\[/s\\]#si', '#\\[s\\]#si', '#\\[/s\\]#si'), array('$2', '', ''), $mensaje);
	$mensaje = preg_replace(array('#\\[url(.*?)\\](.*?)\\[/url\\]#si', '#\\[url\\]#si', '#\\[/url\\]#si'), array('$2', '', ''), $mensaje);
	$mensaje = preg_replace(array('#\\[img(.*?)\\](.*?)\\[/img\\]#si', '#\\[img\\]#si', '#\\[/img\\]#si'), array('$2', '', ''), $mensaje);
	$mensaje = preg_replace('#https?://[^\\s]++#iU', '', $mensaje);
	$mensaje = preg_replace('#:smile-([\\d]|[1-9][\\d]|100):#si', '', $mensaje);
	return strlen($mensaje);
}

function LoadMess($row_chat, $user_name, $chat_status)
{
    global $security_key;
    $load_mess = "<div class=\"box-message\" id=\"chat-mess-" . $row_chat["id"] . "\">";
    $load_mess .= "<table class=\"chat-table\">";
    $load_mess .= "<tbody>";
    $load_mess .= "<tr>";
    $load_mess .= "<td class=\"ta-center\" width=\"46\" style=\"padding-left:0;\"><a href=\"/wall?uid=" . $row_chat["user_id"] . "\" target=\"_blank\"><img src=\"avatar/" . $row_chat["user_avatar"] . "\" class=\"chat-avatar\" align=\"absmiddle\" alt=\"\" title=\"Перейти на стену пользователя " . $row_chat["user_name"] . "\" /></a></td>";
    $load_mess .= "<td class=\"ta-left\">";
    $load_mess .= "<div class=\"chat-mess\">";
    $load_mess .= "<div class=\"chat-users\">";
    $load_mess .= "<span class=\"chat-author\" " . (($row_chat["user_color"] != false ? "style=\"color:" . $row_chat["user_color"] . ";\"" : false)) . " onClick=\"UserToChat('chat-user-to', '" . $row_chat["user_name"] . "');\">" . $row_chat["user_name"] . "</span>";
    $load_mess .= ($row_chat["user_name_to"] != false ? "<span class=\"user-to\"><b>&#10148;</b> " . $row_chat["user_name_to"] . "</span>" . (($row_chat["privat"] == 1 ? "<span class=\"chat-privat\" title=\"Приватное сообщение\"></span>" : false)) : false);
    $load_mess .= "</div>";
	$load_mess .= html_entity_decode($row_chat['chat_mess'], ENT_QUOTES);
    //$load_mess .= desc_bb($row_chat["chat_mess"]);
    if( $chat_status == 1 | ($chat_status == 2 && strtolower($user_name) != strtolower($row_chat["user_name"])) ) 
    {
        $token_next_del = strtolower(md5($row_chat["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-mess-del" . $security_key));
        $token_next_ban = strtolower(md5($row_chat["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-form-ban-user" . $security_key));
        $load_mess .= "<div style=\"display:inline-block; width:auto; height:20px; float:right; text-align:right;\">";
        if( strtolower($user_name) != strtolower($row_chat["user_name"]) ) 
        {
            $load_mess .= "<span class=\"ban-user\" title=\"Заблокировать пользователя " . $row_chat["user_name"] . "\" onClick=\"FuncChat(" . $row_chat["id"] . ", 'form-ban-user', false, '" . $token_next_ban . "', true, 'Блокировка пользователя', 550);\"></span>";
        }

        $load_mess .= "<span class=\"mess-del\" title=\"Удалить сообщение\" onClick=\"FuncChat(" . $row_chat["id"] . ", 'mess-del', false, '" . $token_next_del . "', true, 'Информация', 500);\"></span>";
        $load_mess .= "</div>";
    }

    $load_mess .= "</div>";
    $load_mess .= "</td>";
    $load_mess .= "<td class=\"ta-right\" width=\"50\">";
    $load_mess .= "<span class=\"chat-time\">" . ((DATE("d.m.Y", $row_chat["time"]) == DATE("d.m.Y", time()) ? DATE("H:i", $row_chat["time"]) : (DATE("d.m.Y", $row_chat["time"]) == DATE("d.m.Y", time() - 24 * 60 * 60) ? DATE("H:i", $row_chat["time"]) . "<br>вчера" : DATE("H:i d.m.Y", $row_chat["time"])))) . "</span>";
    $load_mess .= "</td>";
    $load_mess .= "</tr>";
    $load_mess .= "</tbody>";
    $load_mess .= "</table>";
    $load_mess .= "</div>";
    return $load_mess;
}

$set_error_handler = set_error_handler("myErrorHandler", E_ALL);
if( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" ) {
    require(ROOT_DIR . "/config_mysqli.php");
    require(ROOT_DIR . "/funciones.php");
    require(ROOT_DIR . "/merchant/func_mysqli.php");
    require_once(ROOT_DIR . "/api/socket.io/socket.io.php");
    require_once(ROOT_DIR . "/bbcode/bbcode.lib.php");
    $user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\\-_-]{3,25}\$|", trim($_SESSION["userLog"])) ? escape(htmlentities(stripslashes(trim($_SESSION["userLog"])))) : false);
    $user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}\$|", trim($_SESSION["userPas"])) ? escape(htmlentities(stripslashes(trim($_SESSION["userPas"])))) : false);
    $user_lastip = getRealIP();
    $id = (isset($_POST["id"]) && is_string($_POST["id"]) && preg_match("|^[\\d]{1,11}\$|", intval(limpiarez($_POST["id"]))) ? intval(limpiarez($_POST["id"])) : false);
    $option = (isset($_POST["op"]) && is_string($_POST["op"]) && preg_match("|^[a-zA-Z0-9\\-_]{3,20}\$|", limpiarez($_POST["op"])) ? limpiarez($_POST["op"]) : false);
    $token_post = (isset($_POST["token"]) && is_string($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}\$|", limpiarez($_POST["token"])) ? strtolower(limpiarez($_POST["token"])) : false);
    $security_key = "lilacbux.com^revjredfg5767gfe33etdre";
	$max_size_img = 300;
    $maxlength_mess = 300;
    $tab_max_mess = 200;
    $last_id_mess = 0;
    $allowed_url = array( $_SERVER["HTTP_HOST"], 'youtu.be', 'youtube.com', "yadi.sk", "skrinshoter.ru", "prntscr.com", "radikall.com", "radikal.ru", "rghost.ru", "joxi.ru", "mepic.ru", "screenshotlink.ru", "clip2net.com", "snag.gy", "kvotka.ru");
    $login_color_arr = array( "#CFCFCF", "#FFCCCC", "#FFCC99", "#FFFF99", "#FFFFCC", "#99FFFF", "#CCFFFF", "#CCCCFF", "#FFCCFF", "#CCCCCC", "#FF6666", "#FF9966", "#FFFF66", "#FFFF33", "#33FFFF", "#66FFFF", "#9999FF", "#FF99FF", "#C0C0C0", "#FF0000", "#FF9900", "#FFCC66", "#FFFF00", "#66CCCC", "#33CCFF", "#6666CC", "#CC66CC", "#999999", "#CC0000", "#FF6600", "#FFCC33", "#FFCC00", "#00CCCC", "#3366FF", "#6633FF", "#CC33CC", "#666666", "#990000", "#CC6600", "#CC9933", "#999900", "#3'999", "#3333FF", "#6600CC", "#993'9", "#333333", "#660000", "#993300", "#996633", "#666600", "#336666", "#000099", "#333'9", "#663366", "#000000", "#330000", "#663300", "#663333", "#333300", "#003333", "#000066", "#330099", "#330033" );
    $ban_period_arr = array( "30" => "30 минут", "60" => "1 час ", "720" => "12 часов", "1440" => "24 часа", "10080" => "7 дней", "43200" => "30 дней", "259200" => "6 месяцев", "525600" => "1 год" );
    if( isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) ) 
    {
        $sql_user = $mysqli->query("SELECT `id`,`username`,`money`,`money_rb`,`reiting`,`avatar`,`user_status`,`ban_date` FROM `tb_users` WHERE `username`='" . $user_name . "' AND md5(`password`)='" . $user_pass . "'") or die( my_json_encode("ERROR", $mysqli->error) );
        if( 0 < $sql_user->num_rows ) 
        {
            $row_user = $sql_user->fetch_assoc();
            $user_id = $row_user["id"];
            $user_name = $row_user["username"];
            $user_reiting = $row_user["reiting"];
            $user_money_ob = $row_user["money"];
            $user_money_rb = $row_user["money_rb"];
            $user_avatar = $row_user["avatar"];
            $user_status = $row_user["user_status"];
            $user_ban_date = $row_user["ban_date"];
            $sql_user->free();
            if( 0 < $user_ban_date ) 
            {
                if( isset($_SESSION) ) 
                {
                    session_destroy();
                }

                $result_text = "ERROR-LOGIN";
                $message_text = "Ваш аккаунт заблокирован за нарушение правил проекта";
                exit( my_json_encode($result_text, $message_text) );
            }

            $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='chat_access_reit'") or die( my_json_encode("ERROR", $mysqli->error) );
            $chat_access_reit = (0 < $sql->num_rows ? $sql->fetch_object()->price : 0);
            $sql_chat = $mysqli->query("SELECT * FROM `tb_chat_users` WHERE `user_name`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
            if( 0 < $sql_chat->num_rows ) 
            {
                $row_chat = $sql_chat->fetch_assoc();
                $chat_status = $row_chat["user_status"];
                $chat_status = ($user_status == 1 && $chat_status != 2 && $chat_status != -1 ? 1 : $chat_status);
                $chat_color = $row_chat["user_color"];
                $chat_color_end = $row_chat["color_time_end"];
                $chat_ban_end = $row_chat["ban_time_end"];
                $chat_color = (time() < $chat_color_end ? $chat_color : false);
                $chat_color = ($chat_status == 1 && $chat_color == false ? "#006600" : $chat_color);
                $chat_color = ($chat_status == 2 && $chat_color == false ? "#267E0E" : $chat_color);
                if( 0 < $chat_color_end && $chat_color_end < time() ) 
                {
                    $mysqli->query("UPDATE `tb_chat_users` SET `user_color`='', `color_time_end`='0' WHERE `user_name`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                }

                if( 0 < $chat_ban_end && $chat_ban_end < time() ) 
                {
                    $mysqli->query("UPDATE `tb_chat_users` SET `user_status`='0', `ban_time_end`='0' WHERE `user_name`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $mysqli->query("UPDATE `tb_chat_mess` SET `user_status`='0' WHERE `user_name`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $chat_status = 0;
                }

            }
            else
            {
                $chat_status = 0;
                $chat_color = false;
                $chat_color_end = false;
                $mysqli->query("INSERT INTO `tb_chat_users` (`user_name`,`ip`) VALUES('" . $user_name . "','" . escape($user_lastip) . "')") or die( my_json_encode("ERROR", $mysqli->error) );
            }

            $sql_chat->free();
            $_SESSION["ChatStatus"] = $chat_status;
            $_SESSION["ChatAvatar"] = $user_avatar;
            if( $chat_status != -1 ) 
            {
                $mysqli->query("INSERT INTO `tb_chat_online` (`user_name`,`user_id`,`user_avatar`,`user_status`,`time`,`ip`) VALUES('" . $user_name . "','" . $user_id . "','" . $user_avatar . "','" . $chat_status . "','" . (time() + 120) . "','" . $user_lastip . "') ON DUPLICATE KEY UPDATE `user_name`='" . $user_name . "', `user_id`='" . $user_id . "', `user_avatar`='" . $user_avatar . "', `user_status`='" . $chat_status . "', `time`='" . (time() + 120) . "', `ip`='" . $user_lastip . "'") or die( my_json_encode("ERROR", $mysqli->error) );
            }

            $mysqli->query("DELETE FROM `tb_chat_online` WHERE `time`<'" . time() . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
            if( $option == "mess-load" ) 
            {
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "ban", "chat_ban_info" => "За нарушение правил чата!", "chat_ban_end" => "До <b>" . DATE("H:i d.m.Y", $chat_ban_end) . "</b>" );
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql = $mysqli->query("SELECT * FROM `tb_chat_mess` WHERE `status`='1' ORDER BY `id` ASC") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    $load_mess = false;
                    $last_user_name = false;
                    $last_user_name_to = false;
                    while( $row = $sql->fetch_assoc() ) 
                    {
                        if( $row["privat"] == 0 | ($row["privat"] == 1 && $chat_status == 1 | $chat_status == 2 | strtolower($user_name) == strtolower($row["user_name"]) | strtolower($user_name) == strtolower($row["user_name_to"])) ) 
                        {
                            $last_id_mess = $row["id"];
                            $last_user_name = strtolower($row["user_name"]);
                            $last_user_name_to = strtolower($row["user_name_to"]);
                            $load_mess .= loadmess($row, $user_name, $chat_status);
                        }

                    }
                    $sql->free();
                    $script_text = (strtolower($user_name) == $last_user_name_to ? "SoundsChat(2);" : (strtolower($user_name) != $last_user_name ? "SoundsChat(1);" : false));
                    $script_text = ($load_mess != false && $script_text != false ? "<script id=\"chat_script\">" . $script_text . " setTimeout(function(){\$(\"#chat_script\").remove()}, 500);</script>" : false);
                    $message_arr = array( "chat_messages" => $script_text . $load_mess, "last_id" => $last_id_mess );
                    $message_arr = ($chat_status != 1 && $chat_status != 2 && $user_reiting < $chat_access_reit ? array_merge($message_arr, array( "chat_status" => "reit", "chat_ban_info" => $chat_access_reit )) : $message_arr);
                    $result_text = "OK";
                    $message_text = ($load_mess != false ? $message_arr : "Сообщений нет!");
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Сообщений нет!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "mess-load-new" ) 
            {
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "ban", "chat_ban_info" => "За нарушение правил чата!", "chat_ban_end" => "До <b>" . DATE("H:i d.m.Y", $chat_ban_end) . "</b>" );
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql = $mysqli->query("SELECT * FROM `tb_chat_mess` WHERE `status`='1' AND `id`>'" . $id . "' ORDER BY `id` ASC") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    $load_mess = false;
                    $last_user_name = false;
                    $last_user_name_to = false;
                    while( $row = $sql->fetch_assoc() ) 
                    {
                        if( $row["privat"] == 0 | ($row["privat"] == 1 && $chat_status == 1 | strtolower($user_name) == strtolower($row["user_name"]) | strtolower($user_name) == strtolower($row["user_name_to"])) ) 
                        {
                            $last_id_mess = $row["id"];
                            $last_user_name = strtolower($row["user_name"]);
                            $last_user_name_to = strtolower($row["user_name_to"]);
                            $load_mess .= loadmess($row, $user_name, $chat_status);
                        }

                    }
                    $sql->free();
                    $script_text = (strtolower($user_name) == $last_user_name_to ? "SoundsChat(2);" : (strtolower($user_name) != $last_user_name ? "SoundsChat(1);" : false));
                    $script_text = ($load_mess != false && $script_text != false ? "<script id=\"chat_script\">" . $script_text . " setTimeout(function(){\$(\"#chat_script\").remove()}, 500);</script>" : false);
                    $message_arr = array( "chat_messages" => $script_text . $load_mess, "last_id" => $last_id_mess );
                    $message_arr = ($chat_status != 1 && $chat_status != 2 && $user_reiting < $chat_access_reit ? array_merge($message_arr, array( "chat_status" => "reit", "chat_ban_info" => $chat_access_reit )) : $message_arr);
                    $result_text = "OK";
                    $message_text = ($load_mess != false ? $message_arr : "Сообщений нет!");
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Сообщений нет!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "mess-add" ) 
            {
                $chat_mess = (isset($_POST["chat_mess"]) ? limitatexto(limpiarez($_POST["chat_mess"], 1), $maxlength_mess) : false);
                $chat_mess = (get_magic_quotes_gpc() ? stripslashes($chat_mess) : $chat_mess);
                $chat_privat = (isset($_POST["chat_privat"]) && preg_match("|^[1]{1}\$|", trim($_POST["chat_privat"])) ? intval(trim($_POST["chat_privat"])) : 0);
                $chat_user_to = (isset($_POST["chat_user_to"]) && preg_match("|^[a-zA-Z0-9\\-_-]{3,25}\$|", trim($_POST["chat_user_to"])) ? htmlentities(stripslashes(trim($_POST["chat_user_to"]))) : false);
                $chat_user_to = ($chat_user_to != false && strtolower($chat_user_to) != strtolower($user_name) ? $chat_user_to : false);
                $chat_privat = ($chat_privat == 1 && $chat_user_to != false ? 1 : 0);
                $status_chat_rules = (!isset($_COOKIE["status_chat_rules"]) | (isset($_COOKIE["status_chat_rules"]) && intval($_COOKIE["status_chat_rules"]) != 1) ? 0 : 1);
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $status_chat_rules != 1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Необходимо принять правила ЧАТа!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "ban", "chat_ban_info" => "За нарушение правил чата!", "chat_ban_end" => "До <b>" . DATE("H:i d.m.Y", $chat_ban_end) . "</b>" );
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status != 1 && $chat_status != 2 && $user_reiting < $chat_access_reit ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "reit", "chat_ban_info" => $chat_access_reit );
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_mess != false ) 
                {
                    if( $chat_user_to != false ) 
                    {
                        $sql = $mysqli->query("SELECT `username` FROM `tb_users` WHERE `username`='" . $chat_user_to . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                        $chat_user_to = (0 < $sql->num_rows ? $sql->fetch_object()->username : false);
                    }

                    $chat_mess_length = lengthmesspay($chat_mess);
			        $chat_mess = desc_bb($chat_mess);
					$mysqli->query("INSERT INTO `tb_chat_mess` (`status`,`user_status`,`user_id`,`user_name`,`user_avatar`,`user_color`,`privat`,`user_name_to`,`chat_mess`,`time`,`ip`) VALUES('1','" . $chat_status . "','" . $user_id . "','" . $user_name . "','" . escape($user_avatar) . "','" . escape($chat_color) . "','" . escape($chat_privat) . "','" . escape($chat_user_to) . "','" . escape($chat_mess) . "','" . time() . "','" . escape($user_lastip) . "')") or die( my_json_encode("ERROR", $mysqli->error) );
                    $last_id_mess = $mysqli->insert_id;
                    $mysqli->query("UPDATE `tb_chat_mess` SET `user_status`='" . $chat_status . "' WHERE `user_name`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $sql_cng = $mysqli->query("SELECT `id` FROM `tb_chat_mess` WHERE `status`='1'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $cnt_cng = ($tab_max_mess < $sql_cng->num_rows ? intval($sql_cng->num_rows - $tab_max_mess) : 0);
                    if( 0 < $cnt_cng ) 
                    {
                        $mysqli->query("UPDATE `tb_chat_mess` SET `status`='0' WHERE `status`='1' ORDER BY `id` ASC LIMIT " . $cnt_cng) or die( my_json_encode("ERROR", $mysqli->error) );
                    }

                    $sql_cng->free();
                    $data_socket = array( "chat_op" => "mess-load-new", "last_id" => $last_id_mess, "time" => time() );
                    $data_socket = json_encode_socket($data_socket);
                    $socketio = @new SocketIO();
                    $sentSocket = @$socketio->send("ssl://" . $_SERVER["HTTP_HOST"], 3000, "Chat Update Ajax", $data_socket);
                    if( $sentSocket === true ) 
                    {
                        $result_text = "OK";
                        $message_text = "Сообщение добавлено!";
                    }
                    else
                    {
                        $result_text = "ERROR";
                        $message_text = "Что-то пошло не так, код ошибки: [\"FAILED-SENT-TO-SOCKET\"] сообщите об этом администрации!";
                    }

                    exit( my_json_encode($result_text, $message_text) );
                }

                $result_text = "ERROR";
                $message_text = "Введите текст сообщения. HTML теги и ссылки запрещены";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "mess-del" ) 
            {
                $sql = $mysqli->query("SELECT `id`,`user_name` FROM `tb_chat_mess` WHERE `id`='" . $id . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    $row = $sql->fetch_assoc();
                    $sql->free();
                    $token_check = strtolower(md5($row["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-mess-del" . $security_key));
                    if( $token_post == false | $token_post != $token_check ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Не верный токен, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $chat_status != 1 && $chat_status != 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Недостаточно прав для удаления сообщения!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $chat_status == 2 && strtolower($user_name) == strtolower($row["user_name"]) ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Недостаточно прав для удаления сообщения!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $mysqli->query("UPDATE `tb_chat_mess` SET `status`='2' WHERE `id`='" . $id . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $sql_cng = $mysqli->query("SELECT `id` FROM `tb_chat_mess` WHERE `status`='1'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $cnt_cng = ($sql_cng->num_rows < $tab_max_mess ? intval($tab_max_mess - $sql_cng->num_rows) : 0);
                    if( 0 < $cnt_cng ) 
                    {
                        $mysqli->query("UPDATE `tb_chat_mess` SET `status`='1' WHERE `status`='0' ORDER BY `id` DESC LIMIT " . $cnt_cng) or exit( my_json_encode("ERROR", $mysqli->error) );
                    }

                    $sql_cng->free();
                    $data_socket = array( "chat_op" => "mess-del", "id_mess" => $id, "time" => time() );
                    $data_socket = json_encode_socket($data_socket);
                    $socketio = @new SocketIO();
                    $sentSocket = @$socketio->send("ssl://" . $_SERVER["HTTP_HOST"], 3000, "Chat Update Ajax", $data_socket);
                    if( $sentSocket === true ) 
                    {
                        $result_text = "OK";
                        $message_text = "Изменения успешно сохранены!";
                    }
                    else
                    {
                        $result_text = "ERROR";
                        $message_text = "Что-то пошло не так, код ошибки: [\"FAILED-SENT-TO-SOCKET\"] сообщите об этом администрации!";
                    }

                    exit( my_json_encode($result_text, $message_text) );
                }

                $result_text = "ERROR";
                $message_text = "Реклама с ID: " . $id . " не найдена!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "form-color-login" ) 
            {
                $color_login = (isset($_POST["color"]) && is_string($_POST["color"]) && preg_match("|^#[a-f0-9]{6}\$|i", trim($_POST["color"])) && array_search(trim($_POST["color"]), $login_color_arr) !== false ? htmlspecialchars(trim($_POST["color"])) : false);
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token" . $security_key));
                $token_next = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token-pay" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "ban", "chat_ban_info" => "За нарушение правил чата!", "chat_ban_end" => "До <b>" . DATE("H:i d.m.Y", $chat_ban_end) . "</b>" );
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $color_login == false ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не выбран цвет логина!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status != 1 && $chat_status != 2 && $user_reiting < $chat_access_reit ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "reit", "chat_ban_info" => (string) $chat_access_reit );
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='cena_color_login'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cena_color_login = (0 < $sql->num_rows ? $sql->fetch_object()->price : 0);
                $sql->free();
                $message_text = false;
                $message_text = "<script>chat_color_login = false; function PlanChange(){var cena_color_login = " . $cena_color_login . "; var plan = parseInt(\$.trim(\$(\"#days_color\").val())); var price = cena_color_login * plan;\$(\"#sum_pay\").html('<span class=\"text-green\"><b>'+number_format_js(price, 2, \".\", \" \")+'</b> руб.</span>');} PlanChange();</script>";
                $message_text .= "<div id=\"newform\" class=\"form-chat-color ec.c\"><form method=\"POST\" id=\"form_color_login\" onSubmit=\"FuncChat(false, 'color-login-pay', \$(this).attr('id'), '" . $token_next . "'); return false;\">";
                $message_text .= "<input type=\"hidden\" id=\"method_pay\" name=\"method_pay\" value=\"2\">";
                $message_text .= "<input type=\"hidden\" id=\"color_login\" name=\"color_login\" value=\"" . $color_login . "\">";
                $message_text .= "<table class=\"tables_inv\">";
                $message_text .= "<tbody>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" width=\"200\" height=\"25\">Выбранный цвет логина</td>";
                $message_text .= "<td align=\"left\" height=\"25\"><span class=\"color_choise\" style=\"background-color:" . $color_login . ";\"></span></td>";
                $message_text .= "</tr>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" height=\"25\">Количество дней</td>";
                $message_text .= "<td align=\"left\" height=\"25\"><input type=\"number\" id=\"days_color\" name=\"days_color\" value=\"1\" min=\"1\" max=\"365\" step=\"1\" required=\"required\" class=\"ok\" style=\"text-align:center; width:80px;\" onKeyUp=\"PlanChange();\" onKeyDown=\"PlanChange();\"></td>";
                $message_text .= "</tr>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" height=\"25\">Стоимость заказа</td>";
                $message_text .= "<td align=\"left\" height=\"25\"><span id=\"sum_pay\"></span></td>";
                $message_text .= "</tr>";
                $message_text .= "</tbody>";
                $message_text .= "</table>";
                $message_text .= "<div align=\"center\" style=\"padding:10px 0 5px;\">";
                $message_text .= "<button class=\"sd_sub green\" onClick=\"\$('#method_pay').val(1);\">Оплатить с рекламного счёта</button>";
                $message_text .= "<button class=\"sd_sub green\" onClick=\"\$('#method_pay').val(2);\">Оплатить с основного счёта</button>";
                $message_text .= "</div>";
                $message_text .= "</form></div>";
                $message_text .= "<div align=\"center\" id=\"info-msg-chat\" style=\"display:none;\"></div>";
                $result_text = "OK";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "color-login-pay" ) 
            {
                $color_login = (isset($_POST["color_login"]) && is_string($_POST["color_login"]) && preg_match("|^#[a-f0-9]{6}\$|i", trim($_POST["color_login"])) && array_search(trim($_POST["color_login"]), $login_color_arr) !== false ? htmlspecialchars(trim($_POST["color_login"])) : false);
                $method_pay = (isset($_POST["method_pay"]) && preg_match("|^[1-2]{1}\$|", trim($_POST["method_pay"])) ? intval(trim($_POST["method_pay"])) : false);
                $days_color = (isset($_POST["days_color"]) && preg_match("|^[\\d]{1,3}\$|", trim($_POST["days_color"])) ? intval(trim($_POST["days_color"])) : false);
                $days_color = ($days_color != false && 1 <= intval($days_color) && intval($days_color) <= 365 ? intval($days_color) : 1);
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token-pay" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Доступ к ЧАТу заблокирован за нарушение правил!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status != 1 && $chat_status != 2 && $user_reiting < $chat_access_reit ) 
                {
                    $result_text = "ERROR";
                    $message_text = array( "chat_status" => "reit", "chat_ban_info" => (string) $chat_access_reit );
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $color_login == false ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не выбран цвет логина!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $method_pay == false ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Выберите счёт для оплаты!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='cena_color_login'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cena_color_login = (0 < $sql->num_rows ? $sql->fetch_object()->price : 0);
                $sql->free();
                $sum_pay = number_format($days_color * $cena_color_login, 2, ".", "");
                if( $method_pay == 1 && $user_money_rb < $sum_pay ) 
                {
                    $result_text = "ERROR";
                    $message_text = "На Вашем рекламном счету недостаточно средств!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $method_pay == 2 && $user_money_ob < $sum_pay ) 
                {
                    $result_text = "ERROR";
                    $message_text = "На Вашем основном счету недостаточно средств!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $tab_money = ($method_pay == 1 ? "`money_rb`=`money_rb`-'" . $sum_pay . "'" : "`money`=`money`-'" . $sum_pay . "'");
                $tab_method = ($method_pay == 1 ? "рекламный" : "основной");
                $mysqli->query("UPDATE `tb_users` SET " . $tab_money . " WHERE `username`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) VALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $sum_pay . "','Оплата за цвет логина в ЧАТе, кол-во дней:" . $days_color . ", счёт:" . $tab_method . "','Оплачено','reklama')") or die( my_json_encode("ERROR", $mysqli->error) );
                $color_time_end = ($chat_color_end < time() ? intval(time() + $days_color * 24 * 60 * 60) : intval($chat_color_end + $days_color * 24 * 60 * 60));
                $mysqli->query("INSERT INTO `tb_chat_users` (`user_name`, `user_status`, `user_color`, `color_time_end`, `ip`) VALUES('" . $user_name . "', '" . escape($chat_status) . "', '" . escape($color_login) . "', '" . escape($color_time_end) . "', '" . escape($user_lastip) . "') ON DUPLICATE KEY UPDATE `user_name`='" . $user_name . "', `user_color`='" . escape($color_login) . "', `color_time_end`='" . escape($color_time_end) . "', `ip`='" . escape($user_lastip) . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                stat_pay("chat_color_log", $sum_pay);
                $result_text = "OK";
                $message_text = "<script>chat_color_login = false; \$(\".colors_sub\").css(\"background\", \"" . $color_login . "\");</script>";
                $message_text .= "Оплата прошла успешно!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "form-chat-promotion" ) 
            {
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token" . $security_key));
                $token_next = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-promotion-pay" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Доступ к ЧАТу заблокирован за нарушение правил!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='cena_adv'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cena_adv = (0 < $sql->num_rows ? number_format($sql->fetch_object()->price, 2, ".", "") : 0);
                $sql->free();
                $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='cena_adv_color'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cena_adv_color = (0 < $sql->num_rows ? number_format($sql->fetch_object()->price, 2, ".", "") : 0);
                $sql->free();
                $message_text = "<script>function PlanChange(){var cena_adv = " . $cena_adv . "; var cena_adv_color = " . $cena_adv_color . "; var adv_color = parseInt(\$.trim(\$(\"#promo_color\").val()))==1 ? 1 : 0; var price = cena_adv + adv_color * cena_adv_color; \$(\"#sum_pay\").html('<span class=\"text-green\"><b>'+number_format_js(price, 2, \".\", \" \")+'</b> руб.</span>');} PlanChange();</script>";
                $message_text .= "<div style=\"padding:5px 3px 10px 3px; text-align:center;\">Маскимальное кол-во доступных мест для размещения рекламы в ЧАТе - <b>5</b>.<br>Каждое последующее размещение будет сдвигать все остальные ссылки на одну позицию ниже. Ссылки отображаються в ротаторе, тоесть в случайном прорядке, по одной ссылке на странице!</div>";
                $message_text .= "<div id=\"newform\" class=\"form-chat-promo ec.c\"><form method=\"POST\" id=\"form_promotion\" onSubmit=\"FuncChat(false, 'chat-promotion-pay', \$(this).attr('id'), '" . $token_next . "'); return false;\">";
                $message_text .= "<input type=\"hidden\" id=\"method_pay\" name=\"method_pay\" value=\"\">";
                $message_text .= "<table class=\"tables_inv\" style=\"padding:0 8px; margin:0 auto;\">";
                $message_text .= "<thead>";
                $message_text .= "<tr><th width=\"160\">Параметр</th><th>Значение</th></tr>";
                $message_text .= "</thead>";
                $message_text .= "<tbody>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" height=\"25\">Описание ссылки</td>";
                $message_text .= "<td align=\"left\" height=\"25\"><input type=\"text\" id=\"promo_desc\" name=\"promo_desc\" value=\"\" class=\"ok\" maxlength=\"50\" required=\"required\"></td>";
                $message_text .= "</tr>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" height=\"25\">URL сайта (включая http://)</td>";
                $message_text .= "<td align=\"left\" height=\"25\"><input type=\"url\" id=\"promo_url\" name=\"promo_url\" value=\"\" class=\"ok\" maxlength=\"300\" required=\"required\"></td>";
                $message_text .= "</tr>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" height=\"25\">Выделить ссылку</td>";
                $message_text .= "<td align=\"left\" height=\"25\">";
                $message_text .= "<select id=\"promo_color\" name=\"promo_color\" class=\"ok\" onChange=\"PlanChange();\">";
                $message_text .= "<option value=\"0\">Нет</option>";
                $message_text .= "<option value=\"1\">Да, красным " . ((0 < $cena_adv_color ? "&mdash; (+" . $cena_adv_color . " руб.)" : false)) . "</option>";
                $message_text .= "</select>";
                $message_text .= "</td>";
                $message_text .= "</tr>";
                $message_text .= "<tr>";
                $message_text .= "<td align=\"left\" height=\"25\">Стоимость заказа</td>";
                $message_text .= "<td align=\"left\" height=\"25\"><span id=\"sum_pay\"></span></td>";
                $message_text .= "</tr>";
                $message_text .= "</tbody>";
                $message_text .= "</table>";
                $message_text .= "<div align=\"center\" style=\"padding:10px 0 5px;\">";
                $message_text .= "<button class=\"sd_sub lilac\" onClick=\"\$('#method_pay').val(1);\">Оплатить с рекламного счёта</button>";
                $message_text .= "<button class=\"sd_sub lilac\" onClick=\"\$('#method_pay').val(2);\">Оплатить с основного счёта</button>";
                $message_text .= "</div>";
                $message_text .= "</form></div>";
                $message_text .= "<div align=\"center\" id=\"info-msg-chat\" style=\"display:none;\"></div>";
                $result_text = "OK";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "chat-promotion-pay" ) 
            {
                $promo_desc = (isset($_POST["promo_desc"]) ? limitatexto(limpiarez($_POST["promo_desc"]), 50) : false);
                $promo_url = (isset($_POST["promo_url"]) ? str_ireplace("&amp;", "&", limitatexto(limpiarez($_POST["promo_url"]), 300)) : false);
                $promo_color = (isset($_POST["promo_color"]) && preg_match("|^[0-1]{1}\$|", trim($_POST["promo_color"])) ? intval(trim($_POST["promo_color"])) : 0);
                $method_pay = (isset($_POST["method_pay"]) && preg_match("|^[1-2]{1}\$|", trim($_POST["method_pay"])) ? intval(trim($_POST["method_pay"])) : false);
                $black_url = StringUrl($promo_url);
                $sql_bl = $mysqli->query("SELECT `domen` FROM `tb_black_sites` WHERE `domen` IN (" . $black_url . ")") or die( my_json_encode("ERROR", $mysqli->error) );
                $cnt_bl = $sql_bl->num_rows;
                if( $cnt_bl <= 0 ) 
                {
                    $sql_bl->free();
                }

                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-promotion-pay" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Доступ к ЧАТу заблокирован за нарушение правил!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $promo_desc == false ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Укажите описание ссылки!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( strlen($promo_desc) < 5 ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Описание ссылки должно содержать не менее 5 символов!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $promo_url == false | $promo_url == "http://" | $promo_url == "https://" ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Вы не указали URL-адрес сайта!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( substr($promo_url, 0, 7) != "http://" && substr($promo_url, 0, 8) != "https://" ) 
                {
                    $result_text = "ERROR";
                    $message_text = "URL-адрес сайта указан неверно!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( 0 < $cnt_bl && $black_url != false ) 
                {
                    $row_bl = $sql_bl->fetch_assoc();
                    $sql_bl->free();
                    $result_text = "ERROR";
                    $message_text = "Сайт " . $row_bl["domen"] . " находится в черном списке проекта " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $method_pay == false ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Выберите счёт для оплаты!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='cena_adv'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cena_adv = (0 < $sql->num_rows ? number_format($sql->fetch_object()->price, 2, ".", "") : 0);
                $sql->free();
                $sql = $mysqli->query("SELECT `price` FROM `tb_chat_conf` WHERE `item`='cena_adv_color'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cena_adv_color = (0 < $sql->num_rows ? number_format($sql->fetch_object()->price, 2, ".", "") : 0);
                $sql->free();
                $sum_pay = number_format($cena_adv + $promo_color * $cena_adv_color, 2, ".", "");
                if( $method_pay == 1 && $user_money_rb < $sum_pay ) 
                {
                    $result_text = "ERROR";
                    $message_text = "На Вашем рекламном счету недостаточно средств!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $method_pay == 2 && $user_money_ob < $sum_pay ) 
                {
                    $result_text = "ERROR";
                    $message_text = "На Вашем основном счету недостаточно средств!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $tab_money = ($method_pay == 1 ? "`money_rb`=`money_rb`-'" . $sum_pay . "'" : "`money`=`money`-'" . $sum_pay . "'");
                $tab_method = ($method_pay == 1 ? "с рекламного счёта" : "с основного счёта");
                $mysqli->query("UPDATE `tb_users` SET " . $tab_money . " WHERE `username`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                $mysqli->query("INSERT INTO `tb_chat_adv` (`status`,`method_pay`,`user_name`,`description`,`url`,`color`,`time_add`,`money`,`ip`) VALUES('1','" . $method_pay . "','" . $user_name . "','" . escape($promo_desc) . "','" . escape($promo_url) . "','" . escape($promo_color) . "','" . time() . "','" . $sum_pay . "','" . escape($user_lastip) . "')") or die( my_json_encode("ERROR", $mysqli->error) );
                $promo_id = $mysqli->insert_id;
                $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) VALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $sum_pay . "','Размещение рекламы в ЧАТе " . $tab_method . ", [ID: " . $promo_id . "]','Оплачено','reklama')") or die( my_json_encode("ERROR", $mysqli->error) );
                stat_pay("chat_adv", $sum_pay);
                $sql_cng = $mysqli->query("SELECT `id` FROM `tb_chat_adv` WHERE `status`='1'") or die( my_json_encode("ERROR", $mysqli->error) );
                $cnt_cng = (5 < $sql_cng->num_rows ? intval($sql_cng->num_rows - 5) : 0);
                if( 0 < $cnt_cng ) 
                {
                    $mysqli->query("UPDATE `tb_chat_adv` SET `status`='0' WHERE `status`='1' ORDER BY `id` ASC LIMIT " . $cnt_cng) or exit( my_json_encode("ERROR", $mysqli->error) );
                }

                $sql_cng->free();
                $data_socket = array( "chat_op" => "promo-load", "last_id" => $promo_id, "time" => time() );
                $data_socket = json_encode_socket($data_socket);
                $socketio = @new SocketIO();
                $sentSocket = @$socketio->send("ssl://" . $_SERVER["HTTP_HOST"], 3000, "Chat Update Ajax", $data_socket);
                if( $sentSocket === true ) 
                {
                    $result_text = "OK";
                    $message_text = "Реклама успешно размещена!";
                }
                else
                {
                    $result_text = "ERROR";
                    $message_text = "Что-то пошло не так, код ошибки: [\"FAILED-SENT-TO-SOCKET\"] сообщите об этом администрации!";
                }

                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "promo-list" ) 
            {
                $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-token" . $security_key));
                if( $token_post == false | $token_post != $token_check ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Не верный токен, обновите страницу!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                if( $chat_status == -1 ) 
                {
                    $result_text = "ERROR";
                    $message_text = "Доступ к ЧАТу заблокирован за нарушение правил!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $message_text = false;
                $message_text .= "<table class=\"tables_inv\">";
                $message_text .= "<tbody>";
                $sql = $mysqli->query("SELECT * FROM `tb_chat_adv` WHERE `status`='1' ORDER BY `id` DESC") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    while( $row = $sql->fetch_assoc() ) 
                    {
                        $message_text .= "<tr>";
                        $message_text .= "<td align=\"left\" width=\"120\" style=\"padding-left:8px;\"><b>" . $row["user_name"] . "</b></td>";
                        $message_text .= "<td align=\"center\" width=\"130\">" . DATE("d.m.Y в H:i:s", $row["time_add"]) . "</td>";
                        $message_text .= "<td align=\"left\">";
                        $message_text .= "<a href=\"" . $row["url"] . "\" class=\"" . (($row["color"] == 1 ? "promo-link-h" : "promo-link-n")) . "\" style=\"font-weight:normal;\" target=\"_blank\">" . $row["description"] . "</a>";
                        if( $chat_status == 1 | $chat_status == 2 ) 
                        {
                            $token_next = strtolower(md5($row["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-promotion-edit" . $security_key));
                            $message_text .= "<div style=\"width:20px; height:20px; float:right;\">";
                            $message_text .= "<span class=\"promo-edit\" title=\"Редактировать ссылку\" onClick=\"FuncChat(" . $row["id"] . ", 'promo-edit', false, '" . $token_next . "', true, 'Редактирование ссылки', 550);\"></span>";
                            $message_text .= "</div>";
                        }

                        $message_text .= "</td>";
                        $message_text .= "</tr>";
                    }
                }
                else
                {
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"center\"><span class=\"msg-w\" style=\"margin:0 auto;\">Реклама не найдена</span></td>";
                    $message_text .= "</tr>";
                }

                $sql->free();
                $message_text .= "</tbody>";
                $message_text .= "</table>";
                $message_text .= "<div align=\"center\" id=\"info-msg-chat\" style=\"display:none;\"></div>";
                $result_text = "OK";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "promo-edit" ) 
            {
                $sql = $mysqli->query("SELECT * FROM `tb_chat_adv` WHERE `id`='" . $id . "' AND `status`='1'") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    $row = $sql->fetch_assoc();
                    $sql->free();
                    $token_check = strtolower(md5($row["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-promotion-edit" . $security_key));
                    $token_next = strtolower(md5($row["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-promotion-save" . $security_key));
                    if( $token_post == false | $token_post != $token_check ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Не верный токен, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $chat_status != 1 && $chat_status != 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Недостаточно прав для редактирования рекламы!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $message_text = "<div id=\"newform\" class=\"form-chat-promo ec.c\"><form method=\"POST\" id=\"form_promotion\" onSubmit=\"FuncChat(" . $row["id"] . ", 'promo-save', \$(this).attr('id'), '" . $token_next . "'); return false;\">";
                    $message_text .= "<table class=\"tables_inv\" style=\"padding:0; margin:0 auto;\">";
                    $message_text .= "<tbody>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" width=\"160\" height=\"25\">ID ссылки</td>";
                    $message_text .= "<td align=\"left\" height=\"25\"><b>" . $row["id"] . "</b></td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" height=\"25\">Описание ссылки</td>";
                    $message_text .= "<td align=\"left\" height=\"25\"><input type=\"text\" id=\"promo_desc\" name=\"promo_desc\" value=\"" . $row["description"] . "\" class=\"ok\" maxlength=\"50\" required=\"required\"></td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" height=\"25\">URL сайта (включая http://)</td>";
                    $message_text .= "<td align=\"left\" height=\"25\"><input type=\"url\" id=\"promo_url\" name=\"promo_url\" value=\"" . $row["url"] . "\" class=\"ok\" maxlength=\"300\" required=\"required\"></td>";
                    $message_text .= "</tr>";
                    $message_text .= "</tbody>";
                    $message_text .= "</table>";
                    $message_text .= "<div align=\"center\" style=\"padding:5px 0;\">";
                    $message_text .= "<button class=\"sd_sub lilac\">Сохранить</button>";
                    $message_text .= "</div>";
                    $message_text .= "</form></div>";
                    $message_text .= "<div align=\"center\" id=\"info-msg-chat\" style=\"display:none;\"></div>";
                    $result_text = "OK";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Реклама с ID: " . $id . " не найдена!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "promo-save" ) 
            {
                $sql = $mysqli->query("SELECT * FROM `tb_chat_adv` WHERE `id`='" . $id . "' AND `status`='1'") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    $row = $sql->fetch_assoc();
                    $sql->free();
                    $promo_desc = (isset($_POST["promo_desc"]) ? limitatexto(limpiarez($_POST["promo_desc"]), 50) : false);
                    $promo_url = (isset($_POST["promo_url"]) ? str_ireplace("&amp;", "&", limitatexto(limpiarez($_POST["promo_url"]), 300)) : false);
                    $token_check = strtolower(md5($row["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-promotion-save" . $security_key));
                    $black_url = StringUrl($promo_url);
                    $sql_bl = $mysqli->query("SELECT `domen` FROM `tb_black_sites` WHERE `domen` IN (".$black_url.")") or die( my_json_encode("ERROR", $mysqli->error) );
                    $cnt_bl = $sql_bl->num_rows;
                    if( $cnt_bl <= 0 ) 
                    {
                        $sql_bl->free();
                    }

                    if( $token_post == false | $token_post != $token_check ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Не верный токен, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $chat_status != 1 && $chat_status != 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Недостаточно прав для редактирования рекламы!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $promo_desc == false ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Укажите описание ссылки!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( strlen($promo_desc) < 5 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Описание ссылки должно содержать не менее 5 символов!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $promo_url == false | $promo_url == "http://" | $promo_url == "https://" ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Вы не указали URL-адрес сайта!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( substr($promo_url, 0, 7) != "http://" && substr($promo_url, 0, 8) != "https://" ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "URL-адрес сайта указан неверно!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( 0 < $cnt_bl && $black_url != false ) 
                    {
                        $row_bl = $sql_bl->fetch_assoc();
                        $sql_bl->free();
                        $result_text = "ERROR";
                        $message_text = "Сайт " . $row_bl["domen"] . " находится в черном списке проекта " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $mysqli->query("UPDATE `tb_chat_adv` SET `description`='" . escape($promo_desc) . "', `url`='" . escape($promo_url) . "' WHERE `id`='" . $id . "' AND `status`='1'") or exit( my_json_encode("ERROR", $mysqli->error) );
                    $data_socket = array( "chat_op" => "promo-load", "last_id" => $id, "time" => time() );
                    $data_socket = json_encode_socket($data_socket);
                    $socketio = @new SocketIO();
                    $sentSocket = @$socketio->send("ssl://" . $_SERVER["HTTP_HOST"], 3000, "Chat Update Ajax", $data_socket);
                    if( $sentSocket === true ) 
                    {
                        $result_text = "OK";
                        $message_text = "Изменения успешно сохранены!";
                    }
                    else
                    {
                        $result_text = "ERROR";
                        $message_text = "Что-то пошло не так, код ошибки: [\"FAILED-SENT-TO-SOCKET\"] сообщите об этом администрации!";
                    }

                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Реклама с ID: " . $id . " не найдена!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "form-ban-user" ) 
            {
                $sql = $mysqli->query("SELECT * FROM `tb_chat_mess` WHERE `id`='" . $id . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                if( 0 < $sql->num_rows ) 
                {
                    $row = $sql->fetch_assoc();
                    $sql->free();
                    $token_check = strtolower(md5($row["id"] . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-form-ban-user" . $security_key));
                    $token_next = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-ban-user" . $security_key));
                    if( $token_post == false | $token_post != $token_check ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Не верный токен, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $chat_status != 1 && $chat_status != 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Недостаточно прав для блокировки пользователей!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( strtolower($user_name) == strtolower($row["user_name"]) ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Вы не можете заблокировать себя!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $message_text = "<div id=\"newform\" class=\"form-chat-ban ec.c\"><form method=\"POST\" id=\"form_ban_user\" onSubmit=\"FuncChat(false, 'ban-user', \$(this).attr('id'), '" . $token_next . "'); return false;\">";
                    $message_text .= "<input type=\"hidden\" id=\"ban_user\" name=\"ban_user\" value=\"" . $row["user_name"] . "\">";
                    $message_text .= "<table class=\"tables_inv\">";
                    $message_text .= "<tbody>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" width=\"170\" height=\"25\">Логин</td>";
                    $message_text .= "<td align=\"left\" height=\"25\"><b>" . $row["user_name"] . "</b></td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" height=\"25\">Период блокировки</td>";
                    $message_text .= "<td align=\"left\" height=\"25\">";
                    $message_text .= "<select id=\"ban_period\" name=\"ban_period\" class=\"ok\">";
                    foreach( $ban_period_arr as $key => $val ) 
                    {
                        $message_text .= "<option value=\"" . $key . "\">" . $val . "</option>";
                    }
                    $message_text .= "</select>";
                    $message_text .= "</td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" height=\"25\">Причина блокировки</td>";
                    $message_text .= "<td align=\"left\" height=\"25\"><input type=\"text\" id=\"ban_cause\" name=\"ban_cause\" value=\"Нарушение правил чата п.\" class=\"ok\" maxlength=\"200\" required=\"required\"></td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" height=\"25\">Удалить сообщения</td>";
                    $message_text .= "<td align=\"left\" height=\"25\"><input type=\"checkbox\" id=\"ban_mess_del\" name=\"ban_mess_del\" value=\"1\" checked=\"checked\" style=\"width:16px; height:16px;\"></td>";
                    $message_text .= "</tr>";
                    $message_text .= "</tbody>";
                    $message_text .= "</table>";
                    $message_text .= "<div align=\"center\" style=\"padding:10px 0 5px;\">";
                    $message_text .= "<button class=\"sd_sub big red\">Заблокировать</button>";
                    $message_text .= "</div>";
                    $message_text .= "</form></div>";
                    $message_text .= "<div align=\"center\" id=\"info-msg-chat\" style=\"display:none;\"></div>";
                    $result_text = "OK";
                    exit( my_json_encode($result_text, $message_text) );
                }
                else
                {
                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "Ошибка блокировки: сообщение с ID " . $id . " не найдено!";
                    exit( my_json_encode($result_text, $message_text) );
                }

            }
            else
            {
                if( $option == "ban-user" ) 
                {
                    $ban_user = (isset($_POST["ban_user"]) && preg_match("|^[a-zA-Z0-9\\-_-]{3,25}\$|", trim($_POST["ban_user"])) ? htmlentities(stripslashes(trim($_POST["ban_user"]))) : false);
                    $ban_period = (isset($_POST["ban_period"]) && array_key_exists(intval($_POST["ban_period"]), $ban_period_arr) !== false ? intval($_POST["ban_period"]) : false);
                    $ban_cause = (isset($_POST["ban_cause"]) ? limitatexto(limpiarez($_POST["ban_cause"]), 200) : false);
                    $ban_mess_del = (isset($_POST["ban_mess_del"]) && preg_match("|^[0-1]{1}\$|", trim($_POST["ban_mess_del"])) ? intval(trim($_POST["ban_mess_del"])) : false);
                    $token_check = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "chat-ban-user" . $security_key));
                    if( $token_post == false | $token_post != $token_check ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Не верный токен, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $chat_status != 1 && $chat_status != 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Недостаточно прав для блокировки пользователей!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $ban_user == false ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Укажите логин пользователя!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $ban_period == false ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Укажите период блокировки!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $ban_cause == false ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Укажите причину блокировки!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( mb_strtolower($ban_cause, "CP1251") == "нарушение правил чата п." ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Укажите в причине блокировки пункт правил!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( strtolower($user_name) == strtolower($ban_user) ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Вы не можете заблокировать себя!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $sql = $mysqli->query("SELECT `user_status` FROM `tb_chat_users` WHERE `user_name`='" . $ban_user . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $ban_user_status = (0 < $sql->num_rows ? $sql->fetch_object()->user_status : 0);
                    $sql->free();
                    if( $ban_user_status == 1 && $chat_status == 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Вы не можете заблокировать администратора!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $ban_user_status == 2 && $chat_status == 2 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Заблокировать модератора может только администратор!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $ban_user_status == -1 ) 
                    {
                        $result_text = "ERROR";
                        $message_text = "Пользователь " . $ban_user . " уже заблокирован!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $mysqli->query("INSERT INTO `tb_chat_users` (`user_name`,`user_status`,`banned_user`,`ban_info`,`ban_period`,`ban_time`,`ban_time_end`) VALUES('" . escape($ban_user) . "','-1','" . $user_name . "','" . escape($ban_cause) . "','" . escape($ban_period) . "','" . time() . "','" . (time() + $ban_period * 60) . "') ON DUPLICATE KEY UPDATE `user_status`='-1', `banned_user`='" . $user_name . "', `ban_info`='" . escape($ban_cause) . "', `ban_period`='" . escape($ban_period) . "', `ban_time`='" . time() . "', `ban_time_end`='" . (time() + $ban_period * 60) . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    if( $ban_mess_del == 1 ) 
                    {
                        $mysqli->query("UPDATE `tb_chat_mess` SET `status`='2', `user_status`='-1' WHERE `status`='1' AND `user_name`='" . escape($ban_user) . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    }
                    else
                    {
                        $mysqli->query("UPDATE `tb_chat_mess` SET `user_status`='-1' WHERE `status`='1' AND `user_name`='" . escape($ban_user) . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    }

                    $mysqli->query("DELETE FROM `tb_chat_online` WHERE `user_name`='" . escape($ban_user) . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $chat_mess = "[font color=\"red\"]Пользователь [b]" . $ban_user . "[/b] получил бан на [b]" . $ban_period_arr[$ban_period] . "[/b], по следующей причине: " . mb_strtolower($ban_cause, "CP1251") . "[/font]";
                    $mysqli->query("INSERT INTO `tb_chat_mess` (`status`,`user_status`,`user_id`,`user_name`,`user_avatar`,`user_color`,`chat_mess`,`time`,`ip`) VALUES('1','" . $chat_status . "','" . $user_id . "','" . $user_name . "','" . escape($user_avatar) . "','" . escape($chat_color) . "','" . escape($chat_mess) . "','" . time() . "','" . escape($user_lastip) . "')") or die( my_json_encode("ERROR", $mysqli->error) );
                    $last_id_mess = $mysqli->insert_id;
                    $sql_cng = $mysqli->query("SELECT `id` FROM `tb_chat_mess` WHERE `status`='1'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $cnt_cng = ($sql_cng->num_rows < $tab_max_mess ? intval($tab_max_mess - $sql_cng->num_rows) : 0);
                    if( 0 < $cnt_cng ) 
                    {
                        $mysqli->query("UPDATE `tb_chat_mess` SET `status`='1' WHERE `status`='0' ORDER BY `id` DESC LIMIT " . $cnt_cng) or die( my_json_encode("ERROR", $mysqli->error) );
                    }

                    $sql_cng->free();
                    $data_socket = array( "chat_op" => "mess-load", "last_id" => 0, "time" => time() );
                    $data_socket = json_encode_socket($data_socket);
                    $socketio = @new SocketIO();
                    $sentSocket = @$socketio->send("ssl://" . $_SERVER["HTTP_HOST"], 3000, "Chat Update Ajax", $data_socket);
                    $result_text = "OK";
                    $message_text = "Пользователь " . $ban_user . " успешно заблокирован!";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $result_text = "ERROR";
                $message_text = "Option [" . $option . "] not found...";
                exit( my_json_encode($result_text, $message_text) );
            }

        }
        else
        {
            $sql_user->free();
            if( isset($_SESSION) ) 
            {
                session_destroy();
            }

            $result_text = "ERROR-LOGIN";
            $message_text = "Необходимо авторизоваться";
            exit( my_json_encode($result_text, $message_text) );
        }

    }
    else
    {
        if( isset($_SESSION) ) 
        {
            session_destroy();
        }

        $result_text = "ERROR-LOGIN";
        $message_text = "Необходимо авторизоваться";
        exit( my_json_encode($result_text, $message_text) );
    }

}else{
    $result_text = "ERROR";
    $message_text = "Access denied";
    exit( my_json_encode($result_text, $message_text) );
}

?>