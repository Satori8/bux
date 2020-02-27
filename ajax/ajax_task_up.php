<?php 
session_start();
error_reporting(E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");

function myErrorHandler($errno, $errstr, $errfile, $errline) {
	$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
	$message_text = false;
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
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М","м","Н","н","О","о","П","п","Р","р","С","с","Т","т","У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ","щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function escape($value) {
    global $mysqli;
    return $mysqli->real_escape_string($value);
}

function SqlTask($item) {
    global $mysqli;
    $ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
    $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='".$item."' AND `howmany`='1'") or die( ($ajax_json == "json" ? my_json_encode($ajax_json, "ERROR", $mysqli->error) : $mysqli->error) );
    ($sql->num_rows>0 ? $sql->fetch_object()->price : 0);
}

function limpiarez($mensaje) {
    $mensaje = trim($mensaje);
    $mensaje = str_ireplace(array( "'", "`" ), "", $mensaje);
    $mensaje = str_ireplace("\"", "&#34;", $mensaje);
    $mensaje = str_ireplace("?", "&#063;", $mensaje);
    $mensaje = str_ireplace("\$", "&#036;", $mensaje);
    $mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
    $mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);
    $mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), NULL, "CP1251"));
    $mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
    $mensaje = str_ireplace("  ", " ", $mensaje);
    $mensaje = str_ireplace("&amp amp ", "&", $mensaje);
    $mensaje = str_ireplace("&amp;amp;", "&", $mensaje);
    $mensaje = str_ireplace("&&", "&", $mensaje);
    $mensaje = str_ireplace("http://http://", "http://", $mensaje);
    $mensaje = str_ireplace("https://https://", "https://", $mensaje);
    $mensaje = str_ireplace("&#063;", "?", $mensaje);
    $mensaje = str_ireplace("&amp;", "&", $mensaje);
    return $mensaje;
}

	function count_text($count, $text1, $text2, $text3) {
    if( 0 <= $count ) {
        if( (10 <= $count && $count <= 20) | (10 <= substr($count, -2, 2) && substr($count, -2, 2) <= 20) ) {
            return (string) $count . "32" . $text3;
        }

        switch( substr($count, -1, 1) ) {
            case 1:
                return (string) $count . "32" . $text1;
            case 2:
            case 3:
            case 4:
                return (string) $count . "32" . $text2;
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 0:
                return (string) $count . "32" . $text3;
        }
    }

}


$set_error_handler = set_error_handler("myErrorHandler", E_ALL);
if( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" ) {
    require(ROOT_DIR . "/config_mysqli.php");
    require(ROOT_DIR . "/funciones.php");
    require(ROOT_DIR . "/merchant/func_mysqli.php");
    $user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\\-_-]{3,25}\$|", trim($_SESSION["userLog"])) ? escape(htmlentities(stripslashes(trim($_SESSION["userLog"])))) : false);
    $user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}\$|", trim($_SESSION["userPas"])) ? escape(htmlentities(stripslashes(trim($_SESSION["userPas"])))) : false);
    $my_lastiplog = getRealIP();
    $id = (isset($_POST["id"]) && is_string($_POST["id"]) && preg_match("|^[\\d]{1,11}\$|", intval(limpiarez($_POST["id"]))) ? escape(intval(limpiarez($_POST["id"]))) : false);
    $option = (isset($_POST["op"]) && is_string($_POST["op"]) && preg_match("|^[a-zA-Z0-9\\-_]{3,20}\$|", limpiarez($_POST["op"])) ? limpiarez($_POST["op"]) : false);
    $type_ads = (isset($_POST["type"]) && is_string($_POST["type"]) && preg_match("|^[a-zA-Z0-9\\-_]{1,20}\$|", limpiarez($_POST["type"])) ? limpiarez($_POST["type"]) : false);
    $token_post = (isset($_POST["token"]) && is_string($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}\$|", limpiarez($_POST["token"])) ? strtolower(limpiarez($_POST["token"])) : false);
    $security_key = "TUlnli^&*@if%Yl957kj630n(*7p0UFn#*hglkj?t";
    if( isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) ) {
        $sql_user = $mysqli->query("SELECT `id`,`username`,`wmid`,`wm_purse`,`money`,`money_rb`,`money_rek`,`referer`,`referer2`,`referer3`,`lastiplog`,`ban_date` FROM `tb_users` WHERE `username`='".$user_name."' AND md5(`password`)='".$user_pass."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
        if($sql_user->num_rows>0) {
            $row_user = $sql_user->fetch_assoc();
            $user_id = $row_user["id"];
            $user_name = $row_user["username"];
            $user_wm_id = $row_user["wmid"];
            $user_wm_purse = $row_user["wm_purse"];
            $user_money_ob = $row_user["money"];
            $user_money_rb = $row_user["money_rb"];
            $user_money_rek = $row_user["money_rek"];
            $user_referer_1 = $row_user["referer"];
            $user_referer_2 = $row_user["referer2"];
            $user_referer_3 = $row_user["referer3"];
            $user_ban_date = $row_user["ban_date"];
            $sql_user->free();
            $task_int_autoup_arr = array( "Интервал авто-поднятия", "600" => "Каждые 10 минут", "1200" => "Каждые 20 минут", "1800" => "Каждые 30 минут", "3600" => "Каждый час", "7200" => "Каждые 2 часа", "10800" => "Каждые 3 часа", "21600" => "Каждые 6 часов", "43200" => "Каждые 12 часов", "86400" => "Каждые сутки" );
            if( $type_ads == "task" ) {

                if( $option == "form_up" ) {
                    $sql = $mysqli->query("SELECT `id`,`status`,`date_up` FROM `tb_ads_task` WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if($sql->num_rows>0) {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_date_up = $row["date_up"];
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "FormUp" . $security_key));
                        $token_next_h = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmHUp" . $security_key));
                        $token_next_a = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmAUp" . $security_key));
                        if( $token_post == false | $token_post != $token_check ) {
                            $result_text = "ERROR";
                            $message_text = "Ошибка доступа, попробуйте позже!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }
						
						$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_up' AND `howmany`='1'");
                        $task_cena_up = $sql->fetch_object()->price;
   
                        $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_sale_up' AND `howmany`='1'");
                        $task_sale_up = $sql->fetch_object()->price;

                        //$task_cena_up = sqltask("task_cena_up");
                       //$task_sale_up = sqltask("task_sale_up");
                        $sql = $mysqli->query("SELECT `int_autoup`,`cnt_autoup`,`time_last`,`time_next` FROM `tb_ads_task_up` WHERE `ident`='".$id."' AND `user_name`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if($sql->num_rows>0) {
                            $row_aup = $sql->fetch_assoc();
                            $int_autoup = $row_aup["int_autoup"];
                            $cnt_autoup = $row_aup["cnt_autoup"];
                            $time_last = (0 < $row_aup["time_last"] && $adv_date_up < $row_aup["time_last"] ? $row_aup["time_last"] : $adv_date_up);
                            $time_next = $row_aup["time_next"];
                            $sql->free();
                        }else{
                            $cnt_autoup = false;
                            $int_autoup = false;
                            $time_last = $adv_date_up;
                            $time_next = false;
                            $sql->free();
                        }

                        $result_text = "OK";
                        $message_text = "<div align=\"center\" style=\"color:#294a9f;background: #f9efe0;\"class=\"\">";
                        $message_text .= "<div>Задание будет поднято на первую позицию в общем списке.</div>";
                        $message_text .= "<div>Стоимость поднятия задания составляет <b>" . $task_cena_up . "</b> руб.</div>";
                        $message_text .= "<div style=\"margin-top:10px;\"><span class=\"sd_sub lilac\" style=\"min-width:110px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'confirm_hup', '" . $token_next_h . "', 'Подтверждение');\">Поднять задание вручную</span></div>";
                        $message_text .= "<div><hr style=\"margin:8px 4px;\"></div>";
                        $message_text .= "</div>";
                        $message_text .= "<div align=\"center\" style=\"background: #f9efe0;\"class=\"text-blue\">";
                        $message_text .= "<div>Автоматическое поднятие. Текущие настроки:</div>";
                        $message_text .= "<div>Интервал: <span class=\"text-red\">" . ((0 < $int_autoup && isset($task_int_autoup_arr[$int_autoup]) ? mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") : "нет")) . "</span>. Осталось: <span class=\"text-red\">" . ((0 < $cnt_autoup ? $cnt_autoup : "0")) . "</span></div>";
                        $message_text .= "<div>Последнее поднятие: <span class=\"text-red\">" . DATE("d.m.Y в H:i", $time_last) . "</span></div>";
                        if( $adv_status == "pay" && 0 < $cnt_autoup && 0 < $time_next ) {
                            $message_text .= "<div>Следующее авто-поднятие: <span class=\"text-red\">" . DATE("d.m.Y в H:i", $time_next) . "</span> [<span style=\"font-family:arial;\">&#177;</span> 2 минуты]</div>";
                        }

                        if( 0 < $task_sale_up ) {
                            $message_text .= "<div style=\"color:#006699; margin:7px auto;\">При оплате <b>100 авто-поднятий и более</b> &mdash; скидка <b>" . $task_sale_up . "</b>%. <b>100 авто-поднятий = " . round(($task_cena_up * 100 * (100 - $task_sale_up)) / 100, 2) . "</b> руб.</div>";
                        }

                        $message_text .= "<div id=\"newform\">";
                        $message_text .= "<select id=\"int_autoup" . $id . "\" class=\"ok\" style=\"width:190px; height:29px; padding:0px 3px; margin:0;\">";
                        foreach( $task_int_autoup_arr as $key => $val ) {
                            $message_text .= "<option value=\"" . $key . "\">" . $val . "</option>";
                        }
                        $message_text .= "</select>";
                        $message_text .= "<input type=\"text\" id=\"cnt_autoup" . $id . "\" maxlength=\"5\" value=\"\" class=\"ok\" style=\"width:190px; height:26px; padding:1px 3px 0 3px; margin:0 0 0 7px; text-align:center;\" placeholder=\"Количество авто-поднятий\" />";
                        $message_text .= "</div>";
                        $message_text .= "<div><span class=\"sd_sub lilac\" style=\"min-width:110px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'confirm_aup', '" . $token_next_a . "', 'Подтверждение');\">Оплатить</span></div>";
                        $message_text .= "<div style=\"padding:5px; font-size:11px; line-height:12px;\">При включении авто-поднятия, с Вашего рекламного счета будет списана необходимая сумма, задание будет сразу поднято.<br>Время следующего поднятия будет отсчитываться с момента последнего поднятия.<br>Если после включения функции, вы поставите задание на паузу, то задание автоматически подниматься не будет, оставшееся количество авто-поднятий сохранится и будет использовано при следующем запуске задания.</div>";
                        $message_text .= "</div>";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }else{
                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Задание №" . $id . " не найдено!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                }else{
                    if( $option == "confirm_hup" ) {
                        $sql = $mysqli->query("SELECT `id`,`status` FROM `tb_ads_task` WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if($sql->num_rows>0) {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmHUp" . $security_key));
                            $token_next_h = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayHUp" . $security_key));
                            if( $token_post == false | $token_post != $token_check ) {
                                $result_text = "ERROR";
                                $message_text = "Ошибка доступа, попробуйте позже!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }
							
							$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_up' AND `howmany`='1'");
                        $task_cena_up = $sql->fetch_object()->price;                        

                            //$task_cena_up = sqltask("task_cena_up", 2);
                            $result_text = "OK";
                            $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                            $message_text .= "С вашего <b>рекламного</b> счета будет списана сумма в размере <b>".$task_cena_up."</b> руб.<br>";
                            $message_text .= "Поднять задание №<b>".$id."</b> в списке?";
                            $message_text .= "</div>";
                            $message_text .= "<div style=\"text-align:center;\">";
                            $message_text .= "<span class=\"sd_sub lilac\" style=\"min-width:30px;\" onClick=\"FuncAdv(".$id.", 'task', 'pay_hup', '".$token_next_h."', 'Информация');\">Да</span>";
                            $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">Нет</span>";
                            $message_text .= "</div>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Задание №" . $id . " не найдено!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "pay_hup" )  {
                        $sql = $mysqli->query("SELECT `id`,`status`,`zdurl`,`totals` FROM `tb_ads_task` WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if($sql->num_rows>0) {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $cnt_totals = $row["totals"];
                            $black_url = StringUrl($row["zdurl"]);
                            $sql->free();
                            $token_check = strtolower(md5($id.strtolower($user_name).$_SERVER["HTTP_HOST"]."PayHUp".$security_key));
                            $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (".$black_url.")") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $row_bl = (0 < $sql_bl->num_rows ? $sql_bl->fetch_assoc() : false);
                            $sql_bl->free();
							$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_up' AND `howmany`='1'");
                            $task_cena_up = $sql->fetch_object()->price;
                            //$task_cena_up = sqltask("task_cena_up", 2);
                            if( $token_post == false | $token_post != $token_check ) {
                                $result_text = "ERROR";
                                $message_text = "Ошибка доступа, попробуйте позже!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( 0 < $user_ban_date ) {
                                $result_text = "ERROR";
                                $message_text = "Ваш аккаунт заблокирован за нарушение правил!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "block" )  {
                                $result_text = "ERROR";
                                $message_text = "Задание заблокировано, необходимо внести изменения!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status != "pay" ) {
                                $result_text = "ERROR";
                                $message_text = "Для поднятия в списке, задание необходимо запустить!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "pay" && $cnt_totals <= 0 ) {
                                $result_text = "ERROR";
                                $message_text = "Для поднятия в списке, необходимо пополнить баланс задания!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $row_bl != false && $black_url != false ) {
                                $result_text = "ERROR";
                                $message_text = "Сайт ".$row_bl["domen"]." находится в черном списке проекта ".strtoupper($_SERVER["HTTP_HOST"])."";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $user_money_rb < $task_cena_up ) {
                                $result_text = "ERROR";
                                $message_text = "На Вашем рекламном счету недостаточно средств для поднятия задания!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "pay" && 0 < $cnt_totals )  {
                                $sql_pos = $mysqli->query("SELECT COUNT(*) AS `position` FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='".$id."')");
                                $position_up = ($sql_pos->num_rows>0 ? $sql_pos->fetch_object()->position + 1 : false);
                                $sql_pos->free();
                            }else{
                                unset($position_up);
                            }

                            if( isset($position_up) && $position_up == 1 ) {
                                $result_text = "ERROR";
                                $message_text = "Ваше задание №" . $id . " уже находится на первой позиции в списке!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $mysqli->query("UPDATE `tb_ads_task` SET `date_up`='".time()."', `ip`='".$my_lastiplog."' WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".$task_cena_up."', `money_rek`=`money_rek`+'".$task_cena_up."' WHERE `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) VALUES('1','".$user_name."','".$user_id."','".DATE("d.m.Y H:i")."','".time()."','".$task_cena_up."','Поднятие задания №".$id." в списке','Списано','rashod')") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            stat_pay("task_up", $task_cena_up);
                            invest_stat($task_cena_up, 4);
                            $cnt_user_rb = p_floor($user_money_rb - $task_cena_up, 4);
                            $pos_class = "adv-down";
                            $pos_title = "Позиция задания в общем списке выдачи: 1";
                            $pos_text = "1";
                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">
							s_h = false; 
							new_id = false;
							\$(\"#task_info".$id."\").hide();
							\$(\"#mess_info".$id."\").html(\"\");
							\$(\"#my_bal_rs\").html(\"".$cnt_user_rb."\");
							\$(\"#task_up".$id."\").attr({class:\"".$pos_class."\", title:\"".$pos_title."\"}).html(\"".$pos_text."\");
							setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},1500);
							</script>";
                            $message_text .= "Задание №".$id." успешно поднято в списке!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Задание №".$id." не найдено!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "confirm_aup" ) {
                        $sql = $mysqli->query("SELECT `id`,`status` FROM `tb_ads_task` WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if($sql->num_rows>0) {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmAUp" . $security_key));
                            $token_next_a = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayAUp" . $security_key));
                            $int_autoup = (isset($_POST["int_autoup"]) && !is_array($_POST["int_autoup"]) && array_key_exists(intval(trim($_POST["int_autoup"])), $task_int_autoup_arr) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["int_autoup"])) ? escape(intval(limpiarez($_POST["int_autoup"]))) : false);
                            $cnt_autoup = (isset($_POST["cnt_autoup"]) && !is_array($_POST["cnt_autoup"]) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["cnt_autoup"])) ? escape(intval(limpiarez($_POST["cnt_autoup"]))) : false);
                            if( $token_post == false | $token_post != $token_check ) {
                                $result_text = "ERROR";
                                $message_text = "Ошибка доступа, попробуйте позже!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $int_autoup == false ) {
                                $result_text = "ERROR";
                                $message_text = "Укажите интервал авто-поднятия!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup == false ) {
                                $result_text = "ERROR";
                                $message_text = "Укажите количество авто-поднятий!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup < 3 ) {
                                $result_text = "ERROR";
                                $message_text = "Количество авто-поднятий дожно быть не менее 3-х!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }
							
							$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_up' AND `howmany`='1'");
                        $task_cena_up = $sql->fetch_object()->price;
   
                        $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_sale_up' AND `howmany`='1'");
                        $task_sale_up = $sql->fetch_object()->price;

                            //$task_cena_up = sqltask("task_cena_up", 2);
                            //$task_sale_up = sqltask("task_sale_up", 0);
                            if( 100 <= $cnt_autoup && 0 < $task_sale_up ) {
                                $task_cena_up = ($task_cena_up * (100 - $task_sale_up)) / 100;
                            }

                            $money_pay = number_format($cnt_autoup * $task_cena_up, 3, ".", "");
                            $money_pay = (substr($money_pay, -1) == 0 ? number_format($money_pay, 2, ".", " ") : number_format($money_pay, 3, ".", " "));
                            $result_text = "OK";
                            $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                            $message_text .= "<b>Авто-поднятие задания:</b><br>";
                            $message_text .= "Интервал: <span class=\"text-red\"><b>" . mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") . "</b></span>. ";
                            $message_text .= "Количество авто-поднятий: <span class=\"text-red\"><b>" . $cnt_autoup . "</b></span><br>";
                            $message_text .= "С вашего <b>рекламного</b> счета будет списана сумма в размере <span class=\"text-grey\"><b>" . $money_pay . "</b> руб</span>.<br>";
                            $message_text .= "Включить авто-поднятие задания №<b>" . $id . "</b> в списке?";
                            $message_text .= "</div>";
                            $message_text .= "<div style=\"text-align:center;\">";
                            $message_text .= "<span class=\"sd_sub lilac\" style=\"min-width:30px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'pay_aup', '" . $token_next_a . "', 'Информация');\">Да</span>";
                            $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">Нет</span>";
                            $message_text .= "</div>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                       $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Задание №" . $id . " не найдено!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "pay_aup" ) {
                        $sql = $mysqli->query("SELECT `id`,`status`,`zdurl`,`totals` FROM `tb_ads_task` WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if($sql->num_rows>0) {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $cnt_totals = $row["totals"];
                            $black_url = StringUrl($row["zdurl"]);
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayAUp" . $security_key));
                            $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (".$black_url.")") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $row_bl = ($sql_bl->num_rows>0 ? $sql_bl->fetch_assoc() : false);
                            $sql_bl->free();
                            $int_autoup = (isset($_POST["int_autoup"]) && !is_array($_POST["int_autoup"]) && array_key_exists(intval(trim($_POST["int_autoup"])), $task_int_autoup_arr) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["int_autoup"])) ? escape(intval(limpiarez($_POST["int_autoup"]))) : false);
                            $cnt_autoup = (isset($_POST["cnt_autoup"]) && !is_array($_POST["cnt_autoup"]) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["cnt_autoup"])) ? escape(intval(limpiarez($_POST["cnt_autoup"]))) : false);
                            $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_up' AND `howmany`='1'");
                        $task_cena_up = $sql->fetch_object()->price;
   
                        $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_sale_up' AND `howmany`='1'");
                        $task_sale_up = $sql->fetch_object()->price;
							//$task_cena_up = sqltask("task_cena_up", 2);
                            //$task_sale_up = sqltask("task_sale_up", 0);
                            if( 100 <= $cnt_autoup && 0 < $task_sale_up ) {
                                $task_cena_up = ($task_cena_up * (100 - $task_sale_up)) / 100;
                            }

                            $money_pay = number_format($cnt_autoup * $task_cena_up, 3, ".", "");
                            $money_pay = (substr($money_pay, -1) == 0 ? number_format($money_pay, 2, ".", "") : number_format($money_pay, 3, ".", ""));
                            if( $token_post == false | $token_post != $token_check ) {
                                $result_text = "ERROR";
                                $message_text = "Ошибка доступа, попробуйте позже!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( 0 < $user_ban_date ) {
                                $result_text = "ERROR";
                                $message_text = "Ваш аккаунт заблокирован за нарушение правил!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "block" ) {
                                $result_text = "ERROR";
                                $message_text = "Задание заблокировано, необходимо внести изменения!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "wait" ) {
                                $result_text = "ERROR";
                                $message_text = "Для заказа авто-поднятия в списке, необходимо пополнить баланс задания!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $row_bl != false && $black_url != false ) {
                                $result_text = "ERROR";
                                $message_text = "Сайт " . $row_bl["domen"] . " находится в черном списке проекта " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $int_autoup == false ) {
                                $result_text = "ERROR";
                                $message_text = "Укажите интервал авто-поднятия!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup == false ) {
                                $result_text = "ERROR";
                                $message_text = "Укажите количество авто-поднятий!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup < 3 ) {
                                $result_text = "ERROR";
                                $message_text = "Количество авто-поднятий дожно быть не менее 3-х!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $user_money_rb < $money_pay ) {
                                $result_text = "ERROR";
                                $message_text = "На Вашем рекламном счету недостаточно средств!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $time_now = strtotime(DATE("d.m.Y H:i", time()));
                            $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".$money_pay."', `money_rek`=`money_rek`+'".$money_pay."' WHERE `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            stat_pay("task_aup", $money_pay);
                            invest_stat($money_pay, 4);
                            if( $adv_status == "pay" && 0 < $cnt_totals ) {
                                $sql_pos = $mysqli->query("SELECT COUNT(*) AS `position` FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='".$id."')");
                                $position_up = ($sql_pos->num_rows>0 ? $sql_pos->fetch_object()->position + 1 : false);
                                $sql_pos->free();
                            }else{
                                unset($position_up);
                            }

                            $sql = $mysqli->query("SELECT `id`,`time_next` FROM `tb_ads_task_up` WHERE `ident`='".$id."' AND `user_name`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            if($sql->num_rows>0) {
                                $row_aup = $sql->fetch_assoc();
                                $time_next = $row_aup["time_next"];
                                $sql->free();
                                $mysqli->query("UPDATE `tb_ads_task_up` SET `int_autoup`='".$int_autoup."', `cnt_autoup`=`cnt_autoup`+'".$cnt_autoup."', `time_change`='".time()."', `money_pay`=`money_pay`+'".$money_pay."' WHERE `ident`='".$id."' AND `user_name`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $text_history = "Продление авто-поднятия задания №" . $id . " в списке (Интервал: " . mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") . " Количество: +" . $cnt_autoup . "";
                            }else{
                                $time_next = false;
                                $sql->free();
                                $mysqli->query("INSERT INTO `tb_ads_task_up` (`ident`,`user_name`,`int_autoup`,`cnt_autoup`,`time_last`,`time_next`,`time_change`,`money_pay`) VALUES('".$id."','".$user_name."','".$int_autoup."','".$cnt_autoup."','0','".($time_now+$int_autoup)."','".time()."','".$money_pay."')") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $text_history = "Авто-поднятие задания №".$id." в списке (Интервал: ".mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251")." Количество: ".$cnt_autoup."";
                            }

                            $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) VALUES('1','".$user_name."','".$user_id."','".DATE("d.m.Y H:i")."','".time()."','".$money_pay."','".$text_history."','Списано','rashod')") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            if( $adv_status == "pay" && isset($position_up) && $position_up != 1 && $time_next < $time_now ) {
                                $mysqli->query("UPDATE `tb_ads_task_up` SET `cnt_autoup`=`cnt_autoup`-'1', `time_last`='".$time_now."', `time_next`='".($time_now+$int_autoup)."' WHERE `ident`='".$id."' AND `user_name`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $mysqli->query("UPDATE `tb_ads_task` SET `date_up`='".time()."', `ip`='".$my_lastiplog."' WHERE `id`='".$id."' AND `username`='".$user_name."'") or die( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $pos_class = "adv-down";
                                $pos_title = "Позиция задания в общем списке выдачи: 1";
                                $pos_text = "1";
                            }else{
                                if( isset($position_up) && 0 < $position_up && $position_up < 100 ) {
                                    $pos_class = "adv-down";
                                    $pos_title = "Позиция задания в общем списке выдачи: " . $position_up;
                                    $pos_text = $position_up;
                                }else{
                                    $pos_class = "adv-up";
                                    $pos_title = "Поднять задание в списке";
                                    $pos_text = "&uarr;";
                                }

                            }

                            $cnt_user_rb = p_floor($user_money_rb - $money_pay, 4);
                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">
							s_h = false; 
							new_id = false; 
							$(\"#task_info" . $id . "\").hide();
							$(\"#mess_info" . $id . "\").html(\"\"); 
							$(\"#my_bal_rs\").html(\"" . $cnt_user_rb . "\");
							$(\"#task_up" . $id . "\").attr({class:\"" . $pos_class . "\", title:\"" . $pos_title . "\"}).html(\"" . $pos_text . "\"); 
							setTimeout(function(){
							$(\"#LoadModal\").modalpopup(\"close\");},2000);
								</script>";
                            $message_text .= "Авто-подъем для задания №" . $id . " успешно активирован!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Задание №" . $id . " не найдено!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $result_text = "ERROR";
                    $message_text = "Option[" . $option . "] not found...";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

            }else{
                $result_text = "ERROR";
                $message_text = "Ошибка: Тип рекламы не определен!";
                exit( my_json_encode($ajax_json, $result_text, $message_text) );
            }

        }else{
            $sql_user->free();
            $result_text = "ERROR";
            $message_text = "Пользователь не идентифицирован!";
            exit( my_json_encode($ajax_json, $result_text, $message_text) );
        }

    }else{
        $result_text = "ERROR";
        $message_text = "Необходимо авторизоваться!";
        exit( my_json_encode($ajax_json, $result_text, $message_text) );
    }

}else{
    $result_text = "ERROR";
    $message_text = "Access denied!";
    exit( my_json_encode($ajax_json, $result_text, $message_text) );
}

$result_text = "ERROR"; 
$message_text = "Нет корректного AJAX запроса.";
exit(my_json_encode($ajax_json, $result_text, $message_text));
?>