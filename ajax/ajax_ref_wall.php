<?php 
session_start();
error_reporting(E_ALL);
header("Content-type: text/html; charset=windows-1251");
if( !DEFINED("ROOT_DIR") ) {
    DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
}

$js_result = array( "result" => "ERROR", "message" => "Access denied!" );
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
sleep(0);

function json_encode_cp1251($json_arr){
    $json_arr = json_encode($json_arr);
    $arr_replace_cyr = array( "\\u0410", "\\u0430", "\\u0411", "\\u0431", "\\u0412", "\\u0432", "\\u0413", "\\u0433", "\\u0414", "\\u0434", "\\u0415", "\\u0435", "\\u0401", "\\u0451", "\\u0416", "\\u0436", "\\u0417", "\\u0437", "\\u0418", "\\u0438", "\\u0419", "\\u0439", "\\u041a", "\\u043a", "\\u041b", "\\u043b", "\\u041c", "\\u043c", "\\u041d", "\\u043d", "\\u041e", "\\u043e", "\\u041f", "\\u043f", "\\u0420", "\\u0440", "\\u0421", "\\u0441", "\\u0422", "\\u0442", "\\u0423", "\\u0443", "\\u0424", "\\u0444", "\\u0425", "\\u0445", "\\u0426", "\\u0446", "\\u0427", "\\u0447", "\\u0428", "\\u0448", "\\u0429", "\\u0449", "\\u042a", "\\u044a", "\\u042b", "\\u044b", "\\u042c", "\\u044c", "\\u042d", "\\u044d", "\\u042e", "\\u044e", "\\u042f", "\\u044f" );
    $arr_replace_utf = array( "А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж", "ж", "З", "з", "И", "и", "Й", "й", "К", "к", "Л", "л", "М", "м", "Н", "н", "О", "о", "П", "п", "Р", "р", "С", "с", "Т", "т", "У", "у", "Ф", "ф", "Х", "х", "Ц", "ц", "Ч", "ч", "Ш", "ш", "Щ", "щ", "Ъ", "ъ", "Ы", "ы", "Ь", "ь", "Э", "э", "Ю", "ю", "Я", "я" );
    $json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
    return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text){
    return ($ajax_json == "json" ? json_encode_cp1251(array( "result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text) )) : $message_text);
}

function limpiarez($mensaje){
    $mensaje = trim($mensaje);
    $mensaje = str_replace("'", "", $mensaje);
    $mensaje = str_replace("`", "", $mensaje);
    $mensaje = str_replace("?", "&#063;", $mensaje);
    $mensaje = str_replace("\$", "&#036;", $mensaje);
    $mensaje = preg_replace("#([-0-9a-z_\\.]+@[-0-9a-z_\\.]+\\.[a-z]{2,6})#i", "", $mensaje);
    $mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
    $mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);
    $mensaje = mysql_real_escape_string(trim($mensaje));
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

function clear_html_tags($text){
    $text = trim($text);
    $text = preg_replace("#([-0-9a-z_\\.]+@[-0-9a-z_\\.]+\\.[a-z]{2,6})#i", "", $text);
    $text = preg_replace("'<script[^>]*?>.*?</script>'si", "", $text);
    $text = preg_replace("#<a\\s+href=['|\"]?([^\\#]+?)['|\"]?\\s.*>(?!<img).+?</a>#i", "", $text);
    $text = preg_replace("#http://[^\\s]+#i", "", $text);
    $text = preg_replace("#https://[^\\s]+#i", "", $text);
    $text = preg_replace("#www\\.[-\\d\\w\\._&\\?=%]+#i", "", $text);
    return $text;
}

function BoxModal($title, $content, $width = false, $height = false){
    $BoxModal = false;
    $BoxModal .= "<div class=\"box-modal\" style=\"text-align:justify; " . (($width != false ? "width: " . $width . ";" : false)) . " " . (($height != false ? "min-height: " . $height . ";" : false)) . "\">";
    $BoxModal .= "<div class=\"box-modal-title\">" . $title . "</div>";
    $BoxModal .= "<div class=\"box-modal-close modalpopup-close\"></div>";
    $BoxModal .= "<div class=\"box-modal-content\" style=\"margin:0 auto; padding:2px; font-size:13px; text-align:justify;\">";
    $BoxModal .= $content;
    $BoxModal .= "</div>";
    $BoxModal .= "</div>";
    return $BoxModal;
}

function InfoStatus($_Status_Arr, $user_reiting){
    if( 2147483647 < $user_reiting ) {
        $user_reiting = -2147483649;
    }

    for( $i = 1; $i <= count($_Status_Arr); $i++ ) {
        if( $_Status_Arr[$i]["r_ot"] <= $user_reiting && floor($user_reiting) <= $_Status_Arr[$i]["r_do"] ) {
            $InfoSatus["id"] = $i;
            $InfoSatus["rang"] = $_Status_Arr[$i]["rang"];
        }

    }
    $InfoSatus["id"] = (isset($InfoSatus["id"]) ? $InfoSatus["id"] : 1);
    $InfoSatus["rang"] = (isset($InfoSatus["rang"]) ? $InfoSatus["rang"] : $_Status_Arr[$InfoSatus["id"]]["rang"]);
    return $InfoSatus;
}

function LoadRW($id, $user_name, $user_referer, $user_id_rw, $user_name_rw, $user_reiting_rw, $user_refback_rw, $user_avatar_rw, $user_comment_rw, $InfoStatus, $security_key){
    $LoadRW = false;
    $token_addrefrw = strtolower(md5($id . $user_id_rw . strtolower($user_name_rw) . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "F_AddRW" . $security_key));
    $LoadRW .= "<div class=\"ref-wall\">";
    if( $user_name != false && $user_referer != false ) {
        $LoadRW .= "<div class=\"rw_addsub\" style=\"height:5px;\"></div>";
    }else{
        if( $user_name == false ) {
            $LoadRW .= "<center><div class=\"rw_addsub\"><span class=\"proc-btn\" onclick=\"window.open('/register?r=" . $user_id_rw . "');\">Зарегистрироваться</span></div></center>";
        }else{
            if( strtolower($user_name) == strtolower($user_name_rw) ) {
                $LoadRW .= "<div class=\"rw_addsub\" style=\"height:25px;\"></div>";
            }else{
                if( $user_name != false ) {
                    $LoadRW .= "<center><div><span class=\"proc-btn\" onclick=\"LoadForm('" . $id . "', '" . $user_id_rw . "', 'FormAddRefRW', '" . $token_addrefrw . "');\">Присоединиться</span></div></center>";
                }

            }

        }

    }
$count_all_konk = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='".$user_name_rw."' AND `date_s`<='".time()."' AND `date_e`>='".time()."'")));
$count_all_bonus = intval(mysql_num_rows(mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `status`='1' AND `username`='".$user_name_rw."'")));
    $LoadRW .= "<div onClick=\"" . (($user_name != false ? "window.open('/wall?uid=" . $user_id_rw . "');" : "window.open('/register?r=" . $user_id_rw . "');")) . "\">";
    $LoadRW .= "<div class=\"rw_login\" title=\"Логин\">" . $user_name_rw . "</div>";
    $LoadRW .= "<center><div align=\"center\" class=\"user-status-".$InfoStatus["id"]."\" ".($user_reiting_rw < 0 ? "style=\"color:#FF0000;\"" : false) . " title=\"Рейтинг: " . p_floor($user_reiting_rw, 2) . "\">" . p_floor($user_reiting_rw, 0) . "</div></center>";
    $LoadRW .= "<div class=\"rw_status\" title=\"Статус\">" . $InfoStatus["rang"] . "</div>";
    $LoadRW .= "<div class=\"rw_ref_back\">АвтоРефбек: <span>" . $user_refback_rw . "%</span></div>";
    $LoadRW .= '<div class="rw_login" title="Конкурсы">Активных конкурсов: '.($count_all_konk > 0 ? '<span style="color: #e63200">Всего: '.$count_all_konk.'' : '<span style="color: #C80000"><b>0</b></span>').'</div><br>';
    $LoadRW .= '<div class="rw_login" title="бонусы">Активных бонусов: '.($count_all_bonus > 0 ? '<span style="color: #e63200">Всего: '.$count_all_bonus.'' : '<span style="color: #C80000"><b>0</b></span>').'</div><br>';
    $LoadRW .= "<div class=\"rw_avatar\"><img src=\"avatar/" . $user_avatar_rw . "\" alt=\"\"></div>";
    $LoadRW .= "<div class=\"rw_comment\">" . $user_comment_rw . "</div>";
    $LoadRW .= "</div>";
    $LoadRW .= "</div>";
    return $LoadRW;
}

function DelRW($ref_wall_cnt_all) {
    $sql_rw = mysql_query("SELECT `id` FROM `tb_ref_wall`");
    $all_rw = mysql_num_rows($sql_rw);
    if( $ref_wall_cnt_all < $all_rw ) {
        $cnt_del_rw = $all_rw - $ref_wall_cnt_all;
        mysql_query("DELETE FROM `tb_ref_wall` ORDER BY `id` ASC LIMIT " . $cnt_del_rw);
        mysql_query("ALTER TABLE `tb_ref_wall` ORDER BY `id`");
        mysql_query("OPTIMIZE TABLE `tb_ref_wall`");
    }

}

function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
    $ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
    $message_text = false;
    $errfile = str_replace(ROOT_DIR, "", $errfile);
    switch( $errno ) {
        case 1:
            $message_text = "Fatal error[" . $errno . "]: " . $errstr . " in line " . $errline . " in " . $errfile;
            break;
        case 2:
            $message_text = "Warning[" . $errno . "]: " . $errstr . " in line " . $errline . " in " . $errfile;
            break;
        case 8:
            $message_text = "Notice[" . $errno . "]: " . $errstr . " in line " . $errline . " in " . $errfile;
            break;
        default:
            $message_text = "[" . $errno . "] " . $errstr . " in line " . $errline . " in " . $errfile;
            break;
    }
    $message_text = (string) $message_text;
    exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$set_error_handler = set_error_handler("myErrorHandler", E_ALL);
if( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" ) {
    require(ROOT_DIR . "/config.php");
    require(ROOT_DIR . "/funciones.php");
    require_once(ROOT_DIR . "/merchant/func_mysql.php");
    $user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\\-_-]{3,20}\$|", trim($_SESSION["userLog"])) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false);
    $user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}\$|", trim($_SESSION["userPas"])) ? htmlspecialchars(trim($_SESSION["userPas"])) : false);
    $option = (isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\\-_]{3,20}\$|", htmlspecialchars(trim($_POST["op"]))) ? htmlspecialchars(trim($_POST["op"])) : false);
    $token_post = (isset($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}\$|", trim($_POST["token"])) ? strtolower(limpiarez($_POST["token"])) : false);
    
    $my_lastiplog = getRealIP();
    $security_key = "Ulnli^&*@if%Ylkj630n(*7p0UFn#*hglkj?";
    if( isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) ) {
        $sql_user = mysql_query("SELECT `id`,`username`,`referer`,`money_rb`,`reiting`,`ban_date` FROM `tb_users` WHERE `username`='" . $user_name . "' AND md5(`password`)='" . $user_pass . "'") or exit();
        if( 0 < mysql_num_rows($sql_user) ) {
            $row_user = mysql_fetch_assoc($sql_user);
            $user_id = $row_user["id"];
            $user_name = $row_user["username"];
            $user_referer = $row_user["referer"];
            $user_money_rb = $row_user["money_rb"];
            $user_reit = $row_user["reiting"];
            $user_ban_date = $row_user["ban_date"];
        }else{
            $user_id = false;
            $user_name = false;
            $user_money_rb = false;
            $user_referer = false;
            $user_reit = false;
            $user_ban_date = false;
            $user_status = false;
        }

    }else{
        $user_id = false;
        $user_name = false;
        $user_money_rb = false;
        $user_referer = false;
        $user_reit = false;
        $user_ban_date = false;
        $user_status = false;
    }

    if( $option == "FormSetRW" ) {
        $token_setrw = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "F_SetRW" . $security_key));
        $token_setrw_sub = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "SetRW" . $security_key));
        $message_text = false;
        $title_box = "Размещение на Реф-Стене";
        if( $user_name == false ) {
            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Необходимо авторизоваться!</div>";
        }else{
            if( $token_post == false | $token_post != $token_setrw ) {
                $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ошибка доступа!</div>";
            }else{
                if( 0 < $user_ban_date ) {
                    $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ваш аккаунт заблокирован, размещение не возможно!</div>";
                }else{
                    $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cena' AND `howmany`='1'") or exit();
                    $ref_wall_cena = (0 < mysql_num_rows($sql) ? number_format(mysql_result($sql, 0, 0), 2, ".", "") : 30);
                    $message_text .= "<table class=\"tables_inv\" id=\"newform\" style=\"margin:0px auto; margin-top:-2px;\">";
                    $message_text .= "<tbody>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"center\" colspan=\"2\"><span style=\"color: #C80000;\">Запрещено указывать в комментарии информацию о финансовом вознаграждении за присоединение</span></td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\" width=\"140\">Комментарий</td>";
                    $message_text .= "<td align=\"center\" style=\"padding:3px; height:25px;\"><input type=\"text\" id=\"comment\" class=\"ok\" value=\"\" maxlength=\"35\" placeholder=\"Укажите текст комментария, необязательно\" style=\"margin:0; padding:1px 5px; width:calc(100% - 16px);\" /></td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"left\">Стоимость размещения</td>";
                    $message_text .= "<td align=\"left\" style=\"padding:3px 8px; height:25px;\"><span class=\"wall-cnt text-green\">" . $ref_wall_cena . "</span> руб.</td>";
                    $message_text .= "</tr>";
                    $message_text .= "<tr>";
                    $message_text .= "<td align=\"center\" colspan=\"2\" style=\"padding:0;\"><span id=\"sub-addrw\" onClick=\"SetRW('SetRW', '" . $token_setrw_sub . "');\" class=\"sub-blue160\">Оплатить</span></td>";
                    $message_text .= "</tr>";
                    $message_text .= "</tbody>";
                    $message_text .= "</table>";
                }

            }

        }

        $result_text = "OK";
        $message_text = boxmodal($title_box, $message_text, "500px");
        exit(my_json_encode($ajax_json, $result_text, $message_text));
    }

    if( $option == "FormAddRefRW" ) {
        $id = (isset($_POST["id"]) && preg_match("|^[\\d]{1,11}\$|", htmlspecialchars(trim($_POST["id"]))) ? htmlspecialchars(trim($_POST["id"])) : false);
        $uid = (isset($_POST["uid"]) && preg_match("|^[\\d]{1,11}\$|", htmlspecialchars(trim($_POST["uid"]))) ? htmlspecialchars(trim($_POST["uid"])) : false);
        $message_text = false;
        $title_box = "Подтверждение присоединения";
        $sql_rw = mysql_query("SELECT `id`,`user_id`,`user_name` FROM `tb_ref_wall` WHERE `id`='" . $id . "'") or exit();
        if( 0 < mysql_num_rows($sql_rw) ) {
            $row_rw = mysql_fetch_assoc($sql_rw);
            $token_addrefrw = strtolower(md5($row_rw["id"] . $row_rw["user_id"] . strtolower($row_rw["user_name"]) . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "F_AddRW" . $security_key));
            $token_addrefrw_sub = strtolower(md5($row_rw["id"] . $row_rw["user_id"] . strtolower($row_rw["user_name"]) . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "AddRW" . $security_key));
            $sql = mysql_query("SELECT `id`,`username`,`referer`,`referer2`,`referer3`,`ref_back_all` FROM `tb_users` WHERE `id`='" . $row_rw["user_id"] . "'") or exit();
            if( 0 < mysql_num_rows($sql) ) {
                $row = mysql_fetch_assoc($sql);
                $wall_user_id = $row["id"];
                $wall_user_name = $row["username"];
                $wall_user_referer_1 = $row["referer"];
                $wall_user_referer_2 = $row["referer2"];
                $wall_user_referer_3 = $row["referer3"];
                
                $wall_user_ref_back = $row["ref_back_all"];
                if( $user_name == false ) {
                    $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Необходимо авторизоваться!</div>";
                }else{
                    if( $token_post == false | $token_post != $token_addrefrw ) {
                        $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ошибка доступа!</div>";
                    }else{
                        if( 0 < $user_ban_date ) {
                            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ваш аккаунт заблокирован!</div>";
                        }else{
                            if( $user_referer != false ) {
                                $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">У Вас уже есть реферер!</div>";
                            }else{
                                if( strtolower($user_name) == strtolower($wall_user_name) ) {
                                    $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом у самого себя!</div>";
                                }else{
                                    if( strtolower($user_name) == strtolower($wall_user_referer_1) ) {
                                        $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом пользователя <b>" . $wall_user_name . "</b>.<br>Это ваш реферал <b>I</b>-уровня.</div>";
									}else{
                                        if( strtolower($user_name) == strtolower($wall_user_referer_2) ) {
                                            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом пользователя <b>" . $wall_user_name . "</b>.<br>Это ваш реферал <b>II</b>-уровня.</div>";
                                        }else{
                                            if( strtolower($user_name) == strtolower($wall_user_referer_3) ) {
                                                $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом пользователя <b>" . $wall_user_name . "</b>.<br>Это ваш реферал <b>III</b>-уровня.</div>";
                                            }else{
                                            
                                                $message_text .= "<div align=\"center\" style=\"margin:15px auto;\">";
                                                $message_text .= "Вы действительно хотите присоединиться к пользователю <b>" . $row_rw["user_name"] . "</b>?";
                                                $message_text .= "</div>";
                                                $message_text .= "<div align=\"center\" style=\"margin:10px auto 8px;\">";
                                                $message_text .= "<span class=\"sd_sub green\" style=\"width:50px;\" onClick=\"AddRefRW('" . $row_rw["id"] . "', '" . $wall_user_id . "', 'AddRefRW', '" . $token_addrefrw_sub . "');\">Да</span>";
                                                $message_text .= "<span class=\"sd_sub red\" style=\"width:50px;\" onClick=\"\$('#LoadModal').modalpopup('close');\">Нет</span>";
                                                $message_text .= "</div>";
                                            

                                        

                                    }

                                }

                            }

                        }

                    }

                }
            }
        }

            }else{
                $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Пользователь с такими данными не найден!</div>";
            }

        }else{
            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ошибка. Пользователя нет на стене!</div>";
        }

        $result_text = "OK";
        $message_text = boxmodal($title_box, $message_text, "500px");
        exit(my_json_encode($ajax_json, $result_text, $message_text));
    }

    if( $option == "SetRW" ) {
        $comment = (isset($_POST["comment"]) ? clear_html_tags(limpiarez($_POST["comment"])) : false);
        $comment = limitatexto($comment, 35);
        $token_setrw = strtolower(md5($user_id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "SetRW" . $security_key));
        $message_text = false;
        if( $user_name == false ) {
            $result_text = "ERROR";
            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Необходимо авторизоваться!</div>";
            exit(my_json_encode($ajax_json, "ERROR", $message_text));
        }

        if( $token_post == false | $token_post != $token_setrw ) {
            $result_text = "ERROR";
            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ошибка доступа!</div>";
            exit(my_json_encode($ajax_json, "ERROR", $message_text));
        }

        if( 0 < $user_ban_date ) {
            $result_text = "ERROR";
            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">Ваш аккаунт заблокирован, размещение не возможно!</div>";
            exit(my_json_encode($ajax_json, "ERROR", $message_text));
        }

        $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cena' AND `howmany`='1'") or exit();
        $ref_wall_cena = (0 < mysql_num_rows($sql) ? number_format(mysql_result($sql, 0, 0), 2, ".", "") : 30);
        if( $user_money_rb < $ref_wall_cena ) {
            $result_text = "ERROR";
            $message_text .= "<div class=\"msg-error\" style=\"margin:5px;\">На Вашем рекламном счету недостаточно средств!</div>";
            exit(my_json_encode($ajax_json, "ERROR", $message_text));
        }

        $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cnt_all' AND `howmany`='1'") or exit();
        $ref_wall_cnt_all = (0 < mysql_num_rows($sql) ? number_format(mysql_result($sql, 0, 0), 0, ".", "") : 15);
        mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'" . $ref_wall_cena . "' WHERE `username`='" . $user_name . "'") or exit();
        mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) VALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $ref_wall_cena . "','Оплата за размещение аватара на Реф-Стене','Списано','rashod')") or exit();
        mysql_query("INSERT INTO `tb_ref_wall` (`user_id`,`user_name`,`user_comment`,`time`,`ip`,`money`) VALUES('" . $user_id . "','" . $user_name . "','" . $comment . "','" . time() . "','" . $my_lastiplog . "','" . $ref_wall_cena . "')") or exit();
        delrw($ref_wall_cnt_all);
        stat_pay("ref_wall", $ref_wall_cena);
		invest_stat($ref_wall_cena, 6);
        $_Status_Arr = array(  );
        $sql_s = mysql_query("SELECT `id`,`rang`,`r_ot`,`r_do` FROM `tb_config_rang` ORDER BY `id` ASC") or exit();
        if( 0 < mysql_num_rows($sql_s) ) {
            while( $row_s = mysql_fetch_assoc($sql_s) ) {
                $_Status_Arr[$row_s["id"]] = array( "rang" => $row_s["rang"], "r_ot" => $row_s["r_ot"], "r_do" => $row_s["r_do"] );
            }
        }

        $sql_rw = mysql_query("SELECT `id`,`user_id`,`user_comment` FROM `tb_ref_wall` ORDER BY `id` DESC LIMIT " . $ref_wall_cnt_all) or exit();
        if( 0 < mysql_num_rows($sql_rw) ) {
            while( $row_rw = mysql_fetch_assoc($sql_rw) ) {
                $sql_user = mysql_query("SELECT `id`,`username`,`reiting`,`ref_back_all`,`avatar` FROM `tb_users` WHERE `id`='" . $row_rw["user_id"] . "'") or exit();
                if( 0 < mysql_num_rows($sql_user) ) {
                    $row_user = mysql_fetch_assoc($sql_user);
                    $InfoStatus = infostatus($_Status_Arr, $row_user["reiting"]);
                    $message_text .= loadrw($row_rw["id"], $user_name, $user_referer, $row_user["id"], $row_user["username"], $row_user["reiting"], $row_user["ref_back_all"], $row_user["avatar"], $row_rw["user_comment"], $InfoStatus, $security_key);
                    
                }

            }
        }

        $result_text = "OK";
        exit(my_json_encode($ajax_json, $result_text, $message_text));
    }

    if( $option == "AddRefRW" ) {
        $id = (isset($_POST["id"]) && preg_match("|^[\\d]{1,11}\$|", htmlspecialchars(trim($_POST["id"]))) ? htmlspecialchars(trim($_POST["id"])) : false);
        $uid = (isset($_POST["uid"]) && preg_match("|^[\\d]{1,11}\$|", htmlspecialchars(trim($_POST["uid"]))) ? htmlspecialchars(trim($_POST["uid"])) : false);
        $sql_rw = mysql_query("SELECT `id`,`user_id`,`user_name` FROM `tb_ref_wall` WHERE `id`='" . $id . "'") or exit();
        if( 0 < mysql_num_rows($sql_rw) ) {
            $row_rw = mysql_fetch_assoc($sql_rw);
            $token_addrefrw = strtolower(md5($row_rw["id"] . $row_rw["user_id"] . strtolower($row_rw["user_name"]) . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "AddRW" . $security_key));
            $sql = mysql_query("SELECT `id`,`username`,`referer`,`referer2`,`referer3`,`ref_back_all`,`welcome_ref` FROM `tb_users` WHERE `id`='" . $row_rw["user_id"] . "'") or exit();
            if( 0 < mysql_num_rows($sql) ) {
                $row = mysql_fetch_assoc($sql);
                $wall_user_id = $row["id"];
                $wall_user_name = $row["username"];
                $wall_user_referer_1 = $row["referer"];
                $wall_user_referer_2 = $row["referer2"];
                $wall_user_referer_3 = $row["referer3"];
                
                $wall_user_ref_back = $row["ref_back_all"];
                $wall_welcome_ref = $row["welcome_ref"];
                if( $user_name == false ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Необходимо авторизоваться!</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( $token_post == false | $token_post != $token_addrefrw ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Ошибка доступа!</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( 0 < $user_ban_date ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Ваш аккаунт заблокирован!</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( $user_referer != false ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">У Вас уже есть реферер!</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( strtolower($user_name) == strtolower($wall_user_name) ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом у самого себя!</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( strtolower($user_name) == strtolower($wall_user_referer_1) ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом пользователя <b>" . $wall_user_name . "</b>.<br>Это ваш реферал <b>I</b>-уровня.</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( strtolower($user_name) == strtolower($wall_user_referer_2) ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом пользователя <b>" . $wall_user_name . "</b>.<br>Это ваш реферал <b>II</b>-уровня.</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }

                if( strtolower($user_name) == strtolower($wall_user_referer_3) ) {
                    $result_text = "ERROR";
                    $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Вы не можете быть рефералом пользователя <b>" . $wall_user_name . "</b>.<br>Это ваш реферал <b>III</b>-уровня.</div>";
                    exit(my_json_encode($ajax_json, "ERROR", $message_text));
                }
                
                

                mysql_query("UPDATE `tb_users` SET `statusref`='5', `referer`='" . $wall_user_name . "', `referer2`='" . $wall_user_referer_1 . "', `referer3`='" . $wall_user_referer_2 . "' `ref_back`='" . $wall_user_ref_back . "' WHERE `username`='" . $user_name . "' AND `referer`=''") or exit();
                mysql_query("UPDATE `tb_users` SET `referer2`='" . $wall_user_name . "', `referer3`='" . $wall_user_referer_1 . "' WHERE `referer`='" . $user_name . "'") or exit();
                mysql_query("UPDATE `tb_users` SET `referer3`='" . $wall_user_name . "' WHERE `referer2`='" . $user_name . "'") or exit();
                
                $sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='" . $wall_user_name . "'") or exit();
                $cnt_referals1 = mysql_num_rows($sql_r1);
                $sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='" . $wall_user_name . "'") or exit();
                $cnt_referals2 = mysql_num_rows($sql_r2);
                $sql_r3 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='" . $wall_user_name . "'") or exit();
                $cnt_referals3 = mysql_num_rows($sql_r3);
                 
                mysql_query("UPDATE `tb_users` SET `referals`='" . $cnt_referals1 . "', `referals2`='" . $cnt_referals2 . "', `referals3`='" . $cnt_referals3 . "' WHERE `id`='" . $wall_user_id . "'") or exit();
                mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('" . $wall_user_name . "','Система','Системное сообщение','У вас новый реферал " . $user_name . ". Присоеденился через Реф-стену','0','" . time() . "','" . $my_lastiplog . "')") or exit();
                if( isset($wall_welcome_ref) && trim($wall_welcome_ref) != false ) {
                    mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('" . $user_name . "','" . $wall_user_name . "','Приветствие от реферера','" . $wall_welcome_ref . "','0','" . time() . "','127.0.0.1')");
                }

                $result_text = "OK";
                $message_text = "<div class=\"msg-ok\" style=\"margin:5px;\">Вы успешно стали рефералом пользователя <b>" . $wall_user_name . "</b></div>";
                exit(my_json_encode($ajax_json, $result_text, $message_text));
            }

            $result_text = "ERROR";
            $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Пользователь с такими данными не найден!</div>";
            exit(my_json_encode($ajax_json, "ERROR", $message_text));
        }

        $result_text = "ERROR";
        $message_text = "<div class=\"msg-error\" style=\"margin:5px;\">Ошибка. Пользователя нет на стене!</div>";
        exit(my_json_encode($ajax_json, "ERROR", $message_text));
    }

    if( $option == "LoadRW" ) {
        $message_text = false;
        $_Status_Arr = array(  );
        $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cnt_all' AND `howmany`='1'") or exit();
        $ref_wall_cnt_all = (0 < mysql_num_rows($sql) ? number_format(mysql_result($sql, 0, 0), 0, ".", "") : 15);
        $sql_s = mysql_query("SELECT `id`,`rang`,`r_ot`,`r_do` FROM `tb_config_rang` ORDER BY `id` ASC") or exit();
        if( 0 < mysql_num_rows($sql_s) ) {
            while( $row_s = mysql_fetch_assoc($sql_s) ) {
                $_Status_Arr[$row_s["id"]] = array( "rang" => $row_s["rang"], "r_ot" => $row_s["r_ot"], "r_do" => $row_s["r_do"] );
            }
        }

        $sql_rw = mysql_query("SELECT `id`,`user_id`,`user_comment` FROM `tb_ref_wall` ORDER BY `id` DESC LIMIT " . $ref_wall_cnt_all) or exit();
        if( 0 < mysql_num_rows($sql_rw) ) {
            while( $row_rw = mysql_fetch_assoc($sql_rw) ) {
                $sql_user = mysql_query("SELECT `id`,`username`,`reiting`,`ref_back_all`,`avatar` FROM `tb_users` WHERE `id`='" . $row_rw["user_id"] . "'") or exit();
                if( 0 < mysql_num_rows($sql_user) ) {
                    $row_user = mysql_fetch_assoc($sql_user);
                    $InfoStatus = infostatus($_Status_Arr, $row_user["reiting"]);
                    $message_text .= loadrw($row_rw["id"], $user_name, $user_referer, $row_user["id"], $row_user["username"], $row_user["reiting"], $row_user["ref_back_all"], $row_user["avatar"], $row_rw["user_comment"], $InfoStatus, $security_key);
                }

            }
        }

        $result_text = "OK";
        exit(my_json_encode($ajax_json, $result_text, $message_text));
    }

    $result_text = "ERROR";
    $message_text = "ERROR: NO OPTION";
    exit(my_json_encode($ajax_json, "ERROR", $message_text));
}

$result_text = "ERROR";
$message_text = "Access denied!";
exit(my_json_encode($ajax_json, "ERROR", $message_text));

?>
