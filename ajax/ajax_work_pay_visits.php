<?php 
session_start();
header("Content-type: text/html; charset=windows-1251");
if( !DEFINED("DOC_ROOT") ) {
    DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
}

if( !DEFINED("ROOT_DIR") ) {
    DEFINE("ROOT_DIR", DOC_ROOT);
}

if( !DEFINED("CABINET") ) {
    DEFINE("CABINET", true);
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    global $ajax_json;
    global $message_text;
    $errfile = str_ireplace(ROOT_DIR, false, $errfile);
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
    exit( my_json_encode("ERROR", $message_text) );
}

function json_encode_cp1251($json_arr) {
    $json_arr = json_encode($json_arr);
    $arr_replace_cyr = array( "\\u0410", "\\u0430", "\\u0411", "\\u0431", "\\u0412", "\\u0432", "\\u0413", "\\u0433", "\\u0414", "\\u0434", "\\u0415", "\\u0435", "\\u0401", "\\u0451", "\\u0416", "\\u0436", "\\u0417", "\\u0437", "\\u0418", "\\u0438", "\\u0419", "\\u0439", "\\u041a", "\\u043a", "\\u041b", "\\u043b", "\\u041c", "\\u043c", "\\u041d", "\\u043d", "\\u041e", "\\u043e", "\\u041f", "\\u043f", "\\u0420", "\\u0440", "\\u0421", "\\u0441", "\\u0422", "\\u0442", "\\u0423", "\\u0443", "\\u0424", "\\u0444", "\\u0425", "\\u0445", "\\u0426", "\\u0446", "\\u0427", "\\u0447", "\\u0428", "\\u0448", "\\u0429", "\\u0449", "\\u042a", "\\u044a", "\\u042b", "\\u044b", "\\u042c", "\\u044c", "\\u042d", "\\u044d", "\\u042e", "\\u044e", "\\u042f", "\\u044f" );
    $arr_replace_utf = array( "А", "а", "Б", "б", "В", "в", "Г", "г", "Д", "д", "Е", "е", "Ё", "ё", "Ж", "ж", "З", "з", "И", "и", "Й", "й", "К", "к", "Л", "л", "М", "м", "Н", "н", "О", "о", "П", "п", "Р", "р", "С", "с", "Т", "т", "У", "у", "Ф", "ф", "Х", "х", "Ц", "ц", "Ч", "ч", "Ш", "ш", "Щ", "щ", "Ъ", "ъ", "Ы", "ы", "Ь", "ь", "Э", "э", "Ю", "ю", "Я", "я" );
    $json_arr = str_ireplace($arr_replace_cyr, $arr_replace_utf, $json_arr);
    return $json_arr;
}

function arrIconv($from, $to, $obj) {
    if( is_array($obj) | is_object($obj) ) {
        foreach( $obj as &$val ) {
            $val = arrIconv($from, $to, $val);
        }
        return $obj;
    }else{
        return iconv($from, $to, $obj);
    }

}

function my_json_encode($result_text, $message_text) {
    global $ajax_json;
    return ($ajax_json == "json" ? json_encode_cp1251(array( "result" => iconv("CP1251", "UTF-8", $result_text), "message" => arriconv("CP1251", "UTF-8", $message_text) )) : $message_text);
}

function escape($value) {
    global $mysqli;
    return $mysqli->real_escape_string($value);
}

function SqlConfig($item, $howmany = 1, $decimals = false) {
    global $ajax_json;
    global $mysqli;
    $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='" . $item . "' AND `howmany`='" . $howmany . "'") or die( ($ajax_json == "json" ? my_json_encode("ERROR", $mysqli->error) : $mysqli->error) );
    $price = $sql->num_rows>0 ? $sql->fetch_object()->price : die($ajax_json=="json" ? exit(my_json_encode("ERROR", "Error: item['$item'] or howmany['$howmany'] not found in `tb_config`")) : exit("Error: item['$item'] or howmany['$howmany'] not found in `tb_config`"));

	return ($decimals!==false && is_numeric($price)) ? round($price, $decimals) : $price;
	}

function limpiarez($mensaje) {
    $mensaje = trim($mensaje);
    $mensaje = str_ireplace(array( "`", "\$", "&&", "  " ), array( "'", "&#036;", "&", " " ), $mensaje);
    $mensaje = preg_replace("#([-0-9a-z_\\.]+@[-0-9a-z_\\.]+\\.[a-z]{1,6})#i", "", $mensaje);
    $mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
    $mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);
    $mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars($mensaje, ENT_QUOTES, "CP1251", false));
    $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, "CP1251", false);
    return trim($mensaje);
}

$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);
if( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" ) {
    require(ROOT_DIR . "/config_mysqli.php");
    require(ROOT_DIR . "/funciones.php");
    require(ROOT_DIR . "/merchant/func_mysqli.php");
    $user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\\-_-]{3,25}\$|", trim($_SESSION["userLog"])) ? escape(htmlentities(stripslashes(trim($_SESSION["userLog"])))) : false);
    $user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}\$|", trim($_SESSION["userPas"])) ? escape(htmlentities(stripslashes(trim($_SESSION["userPas"])))) : false);
    $user_lastip = getRealIP();
    $id = (isset($_POST["id"]) && is_string($_POST["id"]) && preg_match("|^[\\d]{1,11}\$|", intval(limpiarez($_POST["id"]))) ? escape(intval(limpiarez($_POST["id"]))) : false);
    $option = (isset($_POST["op"]) && is_string($_POST["op"]) && preg_match("|^[a-zA-Z0-9\\-_]{3,20}\$|", limpiarez($_POST["op"])) ? limpiarez($_POST["op"]) : false);
    $token_post = (isset($_POST["token"]) && is_string($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}\$|", limpiarez($_POST["token"])) ? strtolower(limpiarez($_POST["token"])) : false);
    $security_key = "lilacbux^(*pay_visit7&&9unjhu78*";
    if( isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) ) {
        $sql_user = $mysqli->query("SELECT * FROM `tb_users` WHERE `username`='" . $user_name . "' AND md5(`password`)='" . $user_pass . "'") or die( my_json_encode("ERROR", $mysqli->error) );
        if($sql_user->num_rows>0 ) {
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
            $user_joindate = $row_user["joindate2"];
            $user_sex = $row_user["sex"];
            $user_fl18 = $row_user["fl18"];
            $user_country = $row_user["country_cod"];
            $user_reiting = $row_user["reiting"];
            $user_ref_back = ($user_referer_1 != false ? $row_user["ref_back"] : 0);
            $sql_user->free();
            $user_day_reg = floor((time() - $user_joindate) / (24 * 60 * 60));
            $user_no_ref = ($user_referer_1 == false ? 1 : 0);
            $user_lastip_ex = explode(".", $user_lastip);
            $user_lastip_ex = $user_lastip_ex[0] . "." . $user_lastip_ex[1] . ".";
            if($user_ban_date>0 ) {
                if( isset($_SESSION) ) {
                    session_destroy();
                }

                $result_text = "ERROR";
                $message_text = "Ваш аккаунт заблокирован за нарушение правил проекта";
                exit( my_json_encode($result_text, $message_text) );
            }

            $sql = $mysqli->query("SELECT `id` FROM `tb_config_rang` WHERE `r_ot`<='" . $user_reiting . "' AND `r_do`>='" . floor($user_reiting) . "'") or die( my_json_encode("ERROR", $mysqli->error) );
            $user_id_rang = (0 < $sql->num_rows ? $sql->fetch_object()->id : 1);
            $sql->free();
            $mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='3',`date_end`='" . time() . "' WHERE `status`='1' AND `balance`<`price_adv`") or die( my_json_encode("ERROR", $mysqli->error) );
            $mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='1' WHERE `status`='3' AND `balance`>=`price_adv`") or die( my_json_encode("ERROR", $mysqli->error) );
            if( $option == "claims-form" | $option == "claims-add" ) {
                $sql = $mysqli->query("SELECT `id` FROM `tb_ads_pay_vis` USE INDEX(`status_id`) WHERE `id`='" . $id . "' AND `status`='1' AND `balance`>=`price_adv`") or die( my_json_encode("ERROR", $mysqli->error) );
                if($sql->num_rows>0 ) {
                    $row = $sql->fetch_assoc();
                    $id = $row["id"];
                    $sql->free();
                    $claims_text = (isset($_POST["claims_text"]) ? escape(limitatexto(limpiarez($_POST["claims_text"]), 255)) : false);
                    if( $option == "claims-form" ) {
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "token-claims-form" . $security_key));
                        $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "token-claims-add" . $security_key));
                    }else{
                        if( $option == "claims-add" ) {
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "token-claims-add" . $security_key));
                        }

                    }

                    if( $token_post == false | $token_post != $token_check ) {
                        $result_text = "ERROR";
                        $message_text = "Не верный токен, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $option == "claims-add" && strlen($claims_text) < 10 ) {
                        $result_text = "ERROR";
                        $message_text = "Текст жалобы должен содержать не менее 10 символов!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $sql = $mysqli->query("SELECT `id` FROM `tb_ads_claims` WHERE `ident`='" . $id . "' AND `type`='pay_visits' AND `username`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    if($sql->num_rows>0 ) {
                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Вы уже подавали жалобу на рекламную площадку ID:<b>" . $id . "</b>!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $sql->free();
                    if( $option == "claims-form" ) {
                        $message_text = "<div id=\"newform\">";
                        $message_text .= "<div style=\"background: url(img/warning.png) no-repeat left top; background-size: 50px; padding:10px 0px 10px 60px;\">";
                        $message_text .= "Уважаемый, <b>" . $user_name . "</b>, все жалобы проверяются администрацией.<br>";
                        $message_text .= "<span class=\"text-red\">За систематическую подачу ложных жалоб БЛОКИРОВКА аккаунта!</span>";
                        $message_text .= "</div>";
                        $message_text .= "<div id=\"count1\" style=\"display: inline-block; float:right; padding:5px 2px 0px 0px; color:#B5B5B5; font-size:11px;\">Осталось символов: 255</div>";
                        $message_text .= "<div style=\"display: block; padding-top:4px\">";
                        $message_text .= "<textarea id=\"claims_text\" class=\"ok\" style=\"height:90px;\" placeholder=\"Укажите текст жалобы\" onKeyup=\"DescChange(1, this, 255);\" onKeydown=\"DescChange(1, this, 255);\"></textarea>";
                        $message_text .= "</div>";
                        $message_text .= "<div style=\"text-align:center;\">";
                        $message_text .= "<span class=\"sd_sub big orange\" onClick=\"FuncWork(" . $id . ", 'claims-add', '" . $token_next . "', 'modal', 525);\">Отправить жалобу</span>";
                        $message_text .= "<span id=\"inf_add_claims\" style=\"display:none;\"></span>";
                        $message_text .= "</div>";
                        $message_text .= "</div>";
                        $result_text = "OK";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $option == "claims-add" ) {
                        $mysqli->query("INSERT INTO `tb_ads_claims` (`ident`,`type`,`username`,`claims`,`time`,`ip`) VALUES('" . $id . "','pay_visits','" . $user_name . "','" . $claims_text . "','" . time() . "','" . $user_lastip . "')") or die( my_json_encode("ERROR", $mysqli->error) );
                        $mysqli->query("UPDATE `tb_ads_pay_vis` SET `cnt_claims`=`cnt_claims`+'1' WHERE `id`='" . $id . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                        $result_text = "OK";
                        $message_text = "<script>TmMod = setTimeout(function(){\$.modalpopup(\"close\");}, 3000);</script>";
                        $message_text .= "<span class=\"msg-ok\" style=\"margin:0 auto; line-height:20px;\">Жалоба успешно принята и будет рассмотрена администрацией!</span>";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $result_text = "ERROR";
                    $message_text = "Нет корректного AJAX ответа";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Рекламная площадка ID:<b>" . $id . "</b> не найдена!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "start-work" ) {
                include_once(ROOT_DIR . "/geoip/geoipcity.inc");
                $gi = geoip_open(ROOT_DIR . "/geoip/GeoLiteCity.dat", GEOIP_STANDARD);
                $record = @geoip_record_by_addr($gi, $user_lastip);
                $user_country = (isset($record->country_code) && $record->country_code != false ? $record->country_code : $user_country);
                geoip_close($gi);
                $sql = $mysqli->query("SELECT `id` FROM `tb_ads_pay_vis` WHERE `id`='" . $id . "' AND `status`='1' AND `balance`>=`price_adv` AND `date_reg_user`<='" . $user_day_reg . "' AND `reit_user`<='" . $user_id_rang . "' AND `no_ref`<='" . $user_no_ref . "' AND (`sex_user`='0' OR `sex_user`='" . $user_sex . "') AND (`to_ref`='0' \tOR (`to_ref`='1' AND `username`!='' AND (`username`='" . $user_referer_1 . "') ) \tOR (`to_ref`='2' AND `username`!='' AND (`username`='" . $user_referer_1 . "' OR `username`='" . $user_referer_2 . "' OR `username`='" . $user_referer_3 . "') ) ) AND (`content`='0' OR `content`!='" . $user_fl18 . "') AND (`geo_targ`='' OR `geo_targ` REGEXP '" . $user_country . "') AND `id` NOT IN (\tSELECT `ident` FROM `tb_ads_pay_visits` WHERE `status`='1' AND `time_next`>='" . time() . "' AND ((`tb_ads_pay_vis`.`uniq_ip`='0' AND `user_name`='" . $user_name . "') \t     OR (`tb_ads_pay_vis`.`uniq_ip`='1' AND (`user_name`='" . $user_name . "' OR `ip`='" . $user_lastip . "')) \t     OR (`tb_ads_pay_vis`.`uniq_ip`='2' AND (`user_name`='" . $user_name . "' OR `ip` REGEXP '" . $user_lastip_ex . "'))\t))\r\n") or die( my_json_encode("ERROR", $mysqli->error) );
                if($sql->num_rows>0 ) {
                    $row = $sql->fetch_assoc();
                    $id = $row["id"];
                    $sql->free();
                    $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "token-start-work" . $security_key));
                    $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "token-go-work" . $security_key));
                    if( $token_post == false | $token_post != $token_check ) {
                        $result_text = "ERROR";
                        $message_text = "Ошибка, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $_SESSION["_id_pv"] = $id;
                    $_SESSION["status_pv"] = false;
                    $url_link = "//" . $_SERVER["HTTP_HOST"] . "/work-pay-visit?id=" . $id . "&token=" . $token_next;
                    $result_text = "OK";
                    $message_text = "<span class=\"sub_work blue\" onClick=\"return !window.open('" . $url_link . "'); return false;\">Приступить к просмотру</span>";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Площадка ID:<b>" . $id . "</b> не доступна!";
                exit( my_json_encode($result_text, $message_text) );
            }

            if( $option == "success-work" ) {
                $id_ses = (isset($_SESSION["id_pv"]) && is_string($_SESSION["id_pv"]) && preg_match("|^[\\d]{1,11}\$|", intval(trim($_SESSION["id_pv"]))) ? intval(trim($_SESSION["id_pv"])) : false);
                $status_pv = (isset($_SESSION["status_pv"]) && is_string($_SESSION["status_pv"]) && preg_match("|^[success-]{1,10}[\\d]{1,11}\$|", limpiarez($_SESSION["status_pv"])) ? limpiarez($_SESSION["status_pv"]) : false);
                $mysqli->query("DELETE FROM `tb_ads_pay_visits` WHERE `ident`='" . $id . "' AND `status`='1' AND `user_name`='" . $user_name . "' AND `time_next`<='" . time() . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                $sql = $mysqli->query("SELECT `id`,`revisit`,`price_adv` FROM `tb_ads_pay_vis` WHERE `id`='" . $id . "' AND `status`='1' AND `balance`>=`price_adv`") or die( my_json_encode("ERROR", $mysqli->error) );
                if($sql->num_rows>0 ) {
                    $row = $sql->fetch_assoc();
                    $id = $row["id"];
                    $price_adv = $row["price_adv"];
                    $revisit = $row["revisit"];
                    $sql->free();
                    $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "token-success-work" . $security_key));
                    if( $token_post == false | $token_post != $token_check ) {
                        $result_text = "ERROR";
                        $message_text = "Ошибка, обновите страницу!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    if( $id_ses != $id ) {
                        $result_text = "ERROR";
                        $message_text = "Просматривать можно только по одной ссылке!";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $sql = $mysqli->query("SELECT `id` FROM `tb_ads_pay_visits` WHERE `ident`='" . $id . "' AND `status`='1' AND `user_name`='" . $user_name . "' AND `time_next`>='" . time() . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    if($sql->num_rows>0 ) {
                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "Вы уже просматривали эту ссылку за требуемый период.";
                        exit( my_json_encode($result_text, $message_text) );
                    }

                    $sql->free();
                    $ref_proc[1] = 0;
                    $ref_proc[2] = 0;
                    $ref_proc[3] = 0;
					
                    if( $user_referer_1 != false ) {
                        $sql = $mysqli->query("SELECT `id`,`reiting`,`money_rb`,`referer` FROM `tb_users` WHERE `username`='" . $user_referer_1 . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
                        if($sql->num_rows>0) {
                            $row = $sql->fetch_assoc();
                            $user_referer_2 = $row["referer"];
                            $reit_ref[1] = $row["reiting"];
                            $sql->free();
                            $sql = $mysqli->query("SELECT `pv_1` FROM `tb_config_rang` WHERE `r_ot`<='" . $reit_ref[1] . "' AND `r_do`>='" . floor($reit_ref[1]) . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
                            $ref_proc[1] = ($sql->num_rows>0 ? $sql->fetch_object()->pv_1 : 0);
                            $sql->free();
                            if( $user_referer_2 != false ) {
                                $sql = $mysqli->query("SELECT `reiting`,`referer` FROM `tb_users` WHERE `username`='" . $user_referer_2 . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
                                if($sql->num_rows>0) {
                                    $row = $sql->fetch_assoc();
                                    $user_referer_3 = $row["referer"];
                                    $reit_ref[2] = $row["reiting"];
                                    $sql->free();
                                    $sql = $mysqli->query("SELECT `pv_2` FROM `tb_config_rang` WHERE `r_ot`<='" . $reit_ref[2] . "' AND `r_do`>='" . floor($reit_ref[2]) . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
                                    $ref_proc[2] = ($sql->num_rows>0 ? $sql->fetch_object()->pv_2 : 0);
                                    $sql->free();
                                    if( $user_referer_3 != false ) {
                                        $sql = $mysqli->query("SELECT `reiting` FROM `tb_users` WHERE `username`='" . $user_referer_3 . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
                                        if($sql->num_rows>0) {
                                            $reit_ref[3] = $sql->fetch_object()->reiting;
                                            $sql->free();
                                            $sql = $mysqli->query("SELECT `pv_3` FROM `tb_config_rang` WHERE `r_ot`<='" . $reit_ref[3] . "' AND `r_do`>='" . floor($reit_ref[3]) . "'") or exit( my_json_encode("ERROR", $mysqli->error) );
                                            $ref_proc[3] = ($sql->num_rows>0 ? $sql->fetch_object()->pv_3 : 0);
                                            $sql->free();
                                        }else{
                                            $sql->free();
                                            $user_referer_3 = false;
                                        }

                                    }else{
                                        $user_referer_3 = false;
                                    }

                                }else{
                                    $sql->free();
                                    $user_referer_2 = false;
                                    $user_referer_3 = false;
                                }

                            }else{
                                $user_referer_2 = false;
                                $user_referer_3 = false;
                            }

                        }else{
                            $sql->free();
                            $user_ref_back = 0;
                            $user_referer_1 = false;
                            $user_referer_2 = false;
                            $user_referer_3 = false;
                        }

                    }else{
                        $user_ref_back = 0;
                        $user_referer_1 = false;
                        $user_referer_2 = false;
                        $user_referer_3 = false;
                    }

                    if( $revisit == 0 ) {
                        $time_next = time() + 1 * 24 * 60 * 60;
                    }else{
                        if( $revisit == 1 ) {
                            $time_next = time() + 2 * 24 * 60 * 60;
                        }else{
                            if( $revisit == 2 ) {
                                $time_next = time() + 30 * 24 * 60 * 60;
                            }else{
                                $time_next = time() + 1 * 24 * 60 * 60;
                            }

                        }

                    }

                    $pvis_reiting = sqlconfig("reit_pay_visits", 1, 2);
                    $pvis_comis_sys = sqlconfig("pvis_comis_sys", 1, 0);
                    $cena_hit_user = ($price_adv / (1 + $pvis_comis_sys / 100));
                    $_SumNac = $price_adv - $cena_hit_user;
                    $user_ref_back = $_SumNac * $ref_proc[1] / 100 * $user_ref_back / 100;
					$cena_pv = my_num_format($cena_hit_user + $user_ref_back, 4, ".", "", 2);
                    $add_money_r_1 = round(p_floor($cena_pv * $ref_proc[1] / 100 - $user_ref_back, 6), 6);
                    $add_money_r_2 = round(p_floor($cena_pv * $ref_proc[2] / 100, 6), 6);
                    $add_money_r_3 = round(p_floor($cena_pv * $ref_proc[3] / 100, 6), 6);
                    $mysqli->query("UPDATE `tb_ads_pay_vis` SET `cnt_visits_all`=`cnt_visits_all`+'1', `cnt_visits_now`=`cnt_visits_now`+'1', `balance`=`balance`-`price_adv` WHERE `id`='" . $id . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    $mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='3',`date_end`='" . time() . "' WHERE `status`='1' AND `balance`<`price_adv`") or die( my_json_encode("ERROR", $mysqli->error) );
                    $mysqli->query("INSERT INTO `tb_ads_pay_visits` (`ident`,`status`,`user_name`,`time`,`time_next`,`money`,`ip`) VALUES('" . $id . "','1','" . $user_name . "','" . time() . "','" . $time_next . "','" . $cena_pv . "','" . $user_lastip . "')") or die( my_json_encode("ERROR", $mysqli->error) );
                    $mysqli->query("UPDATE `tb_users` SET `reiting`=`reiting`+'" . $pvis_reiting . "', `money`=`money`+'" . $cena_pv . "', `money_pv`=`money_pv`+'" . $cena_pv . "', `visits_pv`=`visits_pv`+'1' WHERE `username`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                    StatsUsers($user_name, strtolower(DATE("D")), "pay_visits");
                    if( $user_referer_1 != false ) {
                        $mysqli->query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'" . $add_money_r_1 . "', `money`=`money`+'" . $add_money_r_1 . "' WHERE `username`='" . $user_referer_1 . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                        $mysqli->query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'" . $add_money_r_1 . "', `dohod_r_all`=`dohod_r_all`+'" . $add_money_r_1 . "' WHERE `username`='" . $user_name . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                        if( $user_referer_2 != false ) {
                            $mysqli->query("UPDATE `tb_users` SET `referal2visits`=`referal2visits`+'1', `ref2money`=`ref2money`+'" . $add_money_r_2 . "', `money`=`money`+'" . $add_money_r_2 . "' WHERE `username`='" . $user_referer_2 . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                            $mysqli->query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'" . $add_money_r_2 . "', `dohod_r_all`=`dohod_r_all`+'" . $add_money_r_2 . "' WHERE `username`='" . $user_referer_1 . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                            if( $user_referer_3 != false ) {
                                $mysqli->query("UPDATE `tb_users` SET `referal3visits`=`referal3visits`+'1', `ref3money`=`ref3money`+'" . $add_money_r_3 . "', `money`=`money`+'" . $add_money_r_3 . "' WHERE `username`='" . $user_referer_3 . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                                $mysqli->query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'" . $add_money_r_3 . "', `dohod_r_all`=`dohod_r_all`+'" . $add_money_r_3 . "' WHERE `username`='" . $user_referer_2 . "'") or die( my_json_encode("ERROR", $mysqli->error) );
                                
                                
                            }

                        }

                    }
                       

                    $result_text = "OK";
                    $message_text = "<script>parent.opener.postMessage('{\"op\":\"pv_mess\", \"id\":\"" . $id . "\", \"mess\":\"За просмотр ссылки на Ваш баланс начислено <b>" . $cena_pv . "</b> руб.\"}', 'https://" . $_SERVER["HTTP_HOST"] . "');</script>";
                    $message_text .= "Спасибо за посещение! Оплата за просмотр <b>'." . $cena_pv . ".' руб.</b> зачислена.";
                    exit( my_json_encode($result_text, $message_text) );
                }

                $sql->free();
                $result_text = "ERROR";
                $message_text = "Площадка ID:<b>" . $id . "</b> не доступна!";
                exit( my_json_encode($result_text, $message_text) );
            }

            $result_text = "ERROR";
            $message_text = "Option [" . $option . "] not found...";
            exit( my_json_encode($result_text, $message_text) );
        }

        $sql_user->free();
        if( isset($_SESSION) ) {
            session_destroy();
        }

        $result_text = "ERROR";
        $message_text = "Необходимо авторизоваться";
        exit( my_json_encode($result_text, $message_text) );
    }

    if( isset($_SESSION) ) {
        session_destroy();
    }

    $result_text = "ERROR";
    $message_text = "Необходимо авторизоваться";
    exit( my_json_encode($result_text, $message_text) );
}

$result_text = "ERROR";
$message_text = "Access denied";
exit( my_json_encode($result_text, $message_text) );

?>