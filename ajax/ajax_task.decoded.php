<?php 
session_start();
error_reporting(E_ALL);
header("Content-type: text/html; charset=windows-1251");
if( !DEFINED("ROOT_DIR") ) 
{
    DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
}

$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
$result_text = "ERROR";
$message_text = "��� ����������� AJAX �������.";
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);
if( isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" ) 
{
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
    if( isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) ) 
    {
        $sql_user = $mysqli->query("SELECT `id`,`username`,`wmid`,`wm_purse`,`money`,`money_rb`,`money_rek`,`referer`,`referer2`,`referer3`,`lastiplog`,`ban_date` FROM `tb_users` WHERE `username`='" . $user_name . "' AND md5(`password`)='" . $user_pass . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
        if( 0 < $sql_user->num_rows ) 
        {
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
            $task_int_autoup_arr = array( "�������� ����-��������", "600" => "������ 10 �����", "1200" => "������ 20 �����", "1800" => "������ 30 �����", "3600" => "������ ���", "7200" => "������ 2 ����", "10800" => "������ 3 ����", "21600" => "������ 6 �����", "43200" => "������ 12 �����", "86400" => "������ �����" );
            if( $type_ads == "task" ) 
            {
                $mysqli->query("UPDATE `tb_ads_task` SET `status`='pause' WHERE `status` NOT IN ('wait','block') AND `totals`<='0'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                if( $option == "get_url" ) 
                {
                    $sql = $mysqli->query("SELECT `id` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $sql->free();
                        $result_text = "OK";
                        $message_text = "����� ������������� ������� ID: " . $id . " � �������� - ���������� ��������� ������ ��� ������:<br>";
                        $message_text .= "<div style=\"border:1px solid #CCC; padding:6px 5px; margin:8px 20px 0; border-radius:2px; font-weight:bold;\"><a href=\"/view_task.php?rid=" . $id . "\" target=\"_blank\" title=\"������ �� ���� �������\" style=\"color:#005EB8;\">" . ((isset($_HTTPS) && $_HTTPS == true ? "https://" : "http://")) . $_SERVER["HTTP_HOST"] . "/view_task.php?rid=" . $id . "</a></div>";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "PlayPause" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`plan`,`totals`,`wait`,`zdurl` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $cnt_plan = $row["plan"];
                        $cnt_totals = $row["totals"];
                        $cnt_wait = $row["wait"];
                        $black_url = StringUrl($row["zdurl"]);
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PlayPause" . $security_key));
                        $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (" . $black_url . ")") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        $row_bl = (0 < $sql_bl->num_rows ? $sql_bl->fetch_assoc() : false);
                        $sql_bl->free();
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( 0 < $user_ban_date ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "��� ������� ������������ �� ��������� ������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $adv_status == "block" ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������� �������������, ���������� ������ ���������!";
                            $status_text = "<span class=\"adv-play\" title=\"��������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_check . "');\"></span>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                        }

                        if( $row_bl != false && $black_url != false ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "���� " . $row_bl["domen"] . " ��������� � ������ ������ ������� " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $adv_status == "wait" ) 
                        {
                            $result_text = "OK";
                            $message_text = "������ �� ��������, ���������� ��������� ������ �������!";
                            $status_text = "<span class=\"adv-play\" title=\"��������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_check . "');\"></span>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                        }

                        if( $adv_status == "pause" && 0 < $cnt_plan && 0 < $cnt_totals ) 
                        {
                            $mysqli->query("UPDATE `tb_ads_task` SET `status`='pay', `ip`='" . $my_lastiplog . "' WHERE `id`='" . $id . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $result_text = "OK";
                            $message_text = false;
                            $status_text = "<span class=\"adv-pause\" title=\"������������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_check . "');\"></span>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                        }

                        if( $adv_status == "pause" && $cnt_plan <= 0 | $cnt_totals <= 0 ) 
                        {
                            $result_text = "OK";
                            $message_text = "������ �� ��������, ���������� ��������� ������ �������!";
                            $status_text = "<span class=\"adv-play\" title=\"��������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_check . "');\"></span>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                        }

                        if( $adv_status == "pay" && 0 < $cnt_wait ) 
                        {
                            $result_text = "OK";
                            $message_text = "������������� ������� �� ��������, ������� �� ����������� ������!";
                            $status_text = "<span class=\"adv-pause\" title=\"������������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_check . "');\"></span>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                        }

                        if( $adv_status == "pay" && $cnt_wait <= 0 ) 
                        {
                            $mysqli->query("UPDATE `tb_ads_task` SET `status`='pause', `ip`='" . $my_lastiplog . "' WHERE `id`='" . $id . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $result_text = "OK";
                            $message_text = false;
                            $status_text = "<span class=\"adv-play\" title=\"��������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_check . "');\"></span>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                        }

                        $result_text = "ERROR";
                        $message_text = "������ �� ���������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "confirm_del" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`zdprice`,`totals` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_price = $row["zdprice"];
                        $cnt_totals = $row["totals"];
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmDel" . $security_key));
                        $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "YesDel" . $security_key));
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $task_nacenka = sqltask("nacenka_task", 0);
                        $task_comis_del = sqltask("task_comis_del", 0);
                        $task_comis_del = (0 < $task_comis_del ? $task_comis_del : 0);
                        $balance_adv = number_format(($cnt_totals * $adv_price * (100 + $task_nacenka)) / 100, 3, ".", "");
                        $money_back = number_format($balance_adv - $balance_adv * $task_comis_del / 100, 3, ".", "");
                        $money_back = (substr($money_back, -1) == 0 ? number_format($money_back, 2, ".", "") : number_format($money_back, 3, ".", ""));
                        $result_text = "OK";
                        $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                        $message_text .= "�� ������� ��� ������ ������� ������� �<b>" . $id . "</b></b>?" . ((1 <= $money_back ? "<br>�� ��������� ���� ����� ���������� <b>" . $money_back . "</b> ���." : false)) . "";
                        $message_text .= "</div>";
                        $message_text .= "<div style=\"text-align:center;\">";
                        $message_text .= "<span class=\"sd_sub green\" style=\"min-width:30px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'yes_del', '" . $token_next . "', '����������');\">��</span>";
                        $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">���</span>";
                        $message_text .= "</div>";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "yes_del" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`zdprice`,`totals`,`wait`,`cnt_start`,`cnt_claims`,`date_act` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_price = $row["zdprice"];
                        $cnt_totals = $row["totals"];
                        $cnt_wait = $row["wait"];
                        $cnt_start = $row["cnt_start"];
                        $cnt_claims = $row["cnt_claims"];
                        $adv_date_act = $row["date_act"];
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "YesDel" . $security_key));
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( 0 < $user_ban_date ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "��� ������� ������������ �� ��������� ������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $adv_status == "block" ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������� �������������, ���������� ������ ���������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $adv_status == "pay" ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "��� �������� ������� �" . $id . " ��� ���������� ��������� �� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( 0 < $cnt_start | 0 < $cnt_wait | (0 < $cnt_claims && time() - 5 * 24 * 60 * 60 < $adv_date_act) ) 
                        {
                            $result_text = "ERROR";
                            if( 0 < $cnt_start ) 
                            {
                                $message_text = "������� �" . $id . " �����������, �������� ����������!";
                            }
                            else
                            {
                                if( 0 < $cnt_wait ) 
                                {
                                    $message_text = "������� �" . $id . " ������� ������!<br>���������� ��������� �������� ������ �� �������� ���������� �������.";
                                }
                                else
                                {
                                    if( 0 < $cnt_claims && time() - 5 * 24 * 60 * 60 < $adv_date_act ) 
                                    {
                                        $message_text = "�� ������� �" . $id . " ������ ������, �������� �������� ����������!";
                                    }
                                    else
                                    {
                                        $message_text = "��������� ������, ������� �" . $id . " ������� ����������!";
                                    }

                                }

                            }

                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $adv_status == "wait" | ($cnt_totals == 0 && $cnt_start == 0 && $cnt_wait == 0 && $cnt_claims == 0) ) 
                        {
                            $mysqli->query("DELETE FROM `tb_ads_task` WHERE `id`='" . $id . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">s_h = false; new_id = false; \$(\"#task_info" . $id . "\").remove(); \$(\"#adv_dell" . $id . "\").remove(); setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},1500);</script>";
                            $message_text .= "������� �" . $id . " ������� �������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( 0 < $cnt_totals ) 
                        {
                            $task_nacenka = sqltask("nacenka_task", 0);
                            $task_comis_del = sqltask("task_comis_del", 0);
                            $task_comis_del = (0 < $task_comis_del ? $task_comis_del : 0);
                            $balance_adv = number_format(($cnt_totals * $adv_price * (100 + $task_nacenka)) / 100, 3, ".", "");
                            $money_back = number_format($balance_adv - $balance_adv * $task_comis_del / 100, 3, ".", "");
                            $money_back = (substr($money_back, -1) == 0 ? number_format($money_back, 2, ".", "") : number_format($money_back, 3, ".", ""));
                            $mysqli->query("DELETE FROM `tb_ads_task` WHERE `id`='" . $id . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            if( 1 <= $money_back ) 
                            {
                                $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'" . $money_back . "', `money_rek`=`money_rek`-'" . $money_back . "' WHERE `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) \r\n\t\t\t\t\t\t\tVALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $money_back . "','������� ������� �� �������� ������� �" . $id . "','�������','rashod')") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $cnt_user_rb = p_floor($user_money_rb + $money_back, 4);
                            }
                            else
                            {
                                $cnt_user_rb = p_floor($user_money_rb, 4);
                            }

                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">s_h = false; new_id = false; \$(\"#task_info" . $id . "\").remove(); \$(\"#adv_dell" . $id . "\").remove(); \$(\"#my_bal_rs\").html(\"" . $cnt_user_rb . "\"); setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},1500);</script>";
                            $message_text .= "������� �" . $id . " ������� �������, �� ��������� ���� ��������� <b>" . $money_back . "</b> ���.";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $result_text = "ERROR";
                        $message_text = "������� �\$id ������� ������! �� ��������� ������.";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "form_balance" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`zdprice` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_price = $row["zdprice"];
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "FormBalance" . $security_key));
                        $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmBalance" . $security_key));
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $task_nacenka = sqltask("nacenka_task", 0);
                        $task_min_add = sqltask("limit_min_task", 0);
                        $adv_price = number_format(($adv_price * (100 + $task_nacenka)) / 100, 3, ".", "");
                        $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">function PlanChange(){var task_cena = " . $adv_price . "; var plan_add = parseInt(\$.trim(\$(\"#plan_add" . $id . "\").val())); sum_pay = (plan_add>0 && plan_add>=" . $task_min_add . ") ? (plan_add*task_cena) : 0; sum_pay = parseFloat(number_format_js(sum_pay, 3, \".\", \"\")); if(sum_pay>0) {\$(\"#SumPay\").html(\"��������� <b>\"+sum_pay+\"</b> ���.\");}else{\$(\"#SumPay\").html(\"������� ���������� ����������, ������� " . count_text($task_min_add, "����������", "����������", "����������") . "\");} } PlanChange();</script>";
                        $message_text .= "������� ���������� ����������, ������� �� ������ ������ � ������ ��������� ��������<br>(������� " . count_text($task_min_add, "����������", "����������", "����������") . ")";
                        $message_text .= "<form onSubmit=\"return false;\">";
                        $message_text .= "<input id=\"plan_add" . $id . "\" type=\"text\" maxlength=\"5\" value=\"100\" class=\"payadv\" autocomplete=\"off\" onChange=\"PlanChange();\" onKeydowm=\"PlanChange();\" onKeyup=\"PlanChange();\" />";
                        $message_text .= "<div id=\"SumPay\" style=\"text-align:center; color:#696969; height:19px;\"></div>";
                        $message_text .= "<span class=\"sd_sub green\" style=\"min-width:110px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'confirm_balance', '" . $token_next . "', '�������������');\">��������</span>";
                        $message_text .= "</form>";
                        $result_text = "OK";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "confirm_balance" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`zdprice` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_price = $row["zdprice"];
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmBalance" . $security_key));
                        $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayBalance" . $security_key));
                        $plan_add = (isset($_POST["plan_add"]) && preg_match("|^[\\d]{1,11}\$|", intval(limpiarez($_POST["plan_add"]))) ? intval(limpiarez($_POST["plan_add"])) : false);
                        $task_min_add = sqltask("limit_min_task", 0);
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $plan_add < $task_min_add ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "����������� ���������� ���������� ������ ���� �� ����� " . $task_min_add . " ��.";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $task_nacenka = sqltask("nacenka_task", 0);
                        $money_pay = number_format(($plan_add * $adv_price * (100 + $task_nacenka)) / 100, 3, ".", "");
                        $money_pay = (substr($money_pay, -1) == 0 ? number_format($money_pay, 2, ".", " ") : number_format($money_pay, 3, ".", " "));
                        $result_text = "OK";
                        $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                        $message_text .= "� ������ <b>����������</b> ����� ����� ������� ����� � ������� <b>" . $money_pay . "</b> ���.<br>";
                        $message_text .= "��������� ������ ������� �<b>" . $id . "</b> �� <b>" . count_text($plan_add, "����������", "����������", "����������") . "</b>?";
                        $message_text .= "</div>";
                        $message_text .= "<div style=\"text-align:center;\">";
                        $message_text .= "<span class=\"sd_sub green\" style=\"min-width:30px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'pay_balance', '" . $token_next . "', '����������');\">��</span>";
                        $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">���</span>";
                        $message_text .= "</div>";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "pay_balance" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`zdprice`,`zdurl`,`totals`,`bads`,`goods`,`wait`,`cnt_start` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_price = $row["zdprice"];
                        $cnt_totals = $row["totals"];
                        $cnt_bad = $row["bads"];
                        $cnt_good = $row["goods"];
                        $cnt_wait = $row["wait"];
                        $cnt_start = $row["cnt_start"];
                        $black_url = StringUrl($row["zdurl"]);
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayBalance" . $security_key));
                        $token_pp = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PlayPause" . $security_key));
                        $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (" . $black_url . ")") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        $row_bl = (0 < $sql_bl->num_rows ? $sql_bl->fetch_assoc() : false);
                        $sql_bl->free();
                        $plan_add = (isset($_POST["plan_add"]) && preg_match("|^[\\d]{1,11}\$|", intval(limpiarez($_POST["plan_add"]))) ? escape(intval(limpiarez($_POST["plan_add"]))) : false);
                        $task_nacenka = sqltask("nacenka_task", 0);
                        $task_min_add = sqltask("limit_min_task", 0);
                        $money_pay = number_format(($plan_add * $adv_price * (100 + $task_nacenka)) / 100, 3, ".", "");
                        $money_pay = (substr($money_pay, -1) == 0 ? number_format($money_pay, 2, ".", "") : number_format($money_pay, 3, ".", ""));
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( 0 < $user_ban_date ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "��� ������� ������������ �� ��������� ������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $adv_status == "block" ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������� �������������, ���������� ������ ���������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $row_bl != false && $black_url != false ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "���� " . $row_bl["domen"] . " ��������� � ������ ������ ������� " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $plan_add < $task_min_add ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "����������� ���������� ���������� ������ ���� �� ����� " . $task_min_add . " ��.";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        if( $user_money_rb < $money_pay ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "�� ����� ��������� ����� ������������ ������� ��� ����������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $mysqli->query("UPDATE `tb_ads_task` SET `status`='pay', `plan`=`plan`+'" . $plan_add . "', `totals`=`totals`+'" . $plan_add . "', `ip`='" . $my_lastiplog . "' WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'" . $money_pay . "', `money_rek`=`money_rek`+'" . $money_pay . "' WHERE `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) \r\n\t\t\t\t\tVALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $money_pay . "','���������� ������� ������� �" . $id . " �� " . count_text($plan_add, "����������", "����������", "����������") . "','�������','rashod')") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        stat_pay("task", $money_pay);
                        $cnt_user_rb = p_floor($user_money_rb - $money_pay, 4);
                        $balance_adv = number_format((($cnt_totals + $plan_add) * $adv_price * (100 + $task_nacenka)) / 100, 3, ".", "");
                        $balance_adv = (substr($balance_adv, -1) == 0 ? number_format($balance_adv, 2, ".", " ") : number_format($balance_adv, 3, ".", " "));
                        $sql_pos = $mysqli->query("SELECT COUNT(*) AS `position` FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='" . $id . "')");
                        $position_up = (0 < $sql_pos->num_rows ? $sql_pos->fetch_object()->position + 1 : false);
                        $sql_pos->free();
                        if( isset($position_up) && 0 < $position_up && $position_up < 100 ) 
                        {
                            $pos_class = "adv-down";
                            $pos_title = "������� ������� � ����� ������ ������: " . $position_up;
                            $pos_text = $position_up;
                        }
                        else
                        {
                            $pos_class = "adv-up";
                            $pos_title = "������� ������� � ������";
                            $pos_text = "&uarr;";
                        }

                        $result_text = "OK";
                        $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">s_h = false; new_id = false; \$(\"#task_info" . $id . "\").hide(); \$(\"#mess_info" . $id . "\").html(\"\"); \$(\"#my_bal_rs\").html(\"" . $cnt_user_rb . "\"); \$(\"#bal_task" . $id . "\").html(\"" . $balance_adv . "\"); \$(\"#cnt_good" . $id . "\").html(\"" . $cnt_good . "\"); \$(\"#cnt_start" . $id . "\").html(\"" . $cnt_start . "\"); \$(\"#cnt_bad" . $id . "\").html(\"" . $cnt_bad . "\"); \$(\"#cnt_totals" . $id . "\").html(\"" . ($cnt_totals + $plan_add) . "\"); \$(\"#cnt_wait" . $id . "\").attr(" . ((0 < $cnt_wait ? "{class:\"taskstatus-mod\", title:\"��������� ����������!\"}" : "{class:\"taskstatus-mod-no\", title:\"���������� ���\"}")) . ").html(\"" . ((0 < $cnt_wait ? "���������:&nbsp;<b>[" . $cnt_wait . "]</b>" : "���������� ���")) . "\"); \$(\"#task_up" . $id . "\").attr({class:\"" . $pos_class . "\", title:\"" . $pos_title . "\"}).html(\"" . $pos_text . "\"); setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},1500);</script>";
                        $message_text .= "������ ��������� �������� �" . $id . " ������� ��������";
                        $status_text = "<span class=\"adv-pause\" title=\"������������� �������\" onClick=\"FuncAdv(" . $id . ", 'task', 'PlayPause', '" . $token_pp . "');\"></span>";
                        exit( my_json_encode($ajax_json, $result_text, $message_text, $status_text) );
                    }

                    $sql->free();
                    $result_text = "ERROR";
                    $message_text = "������� �" . $id . " �� �������!";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

                if( $option == "form_up" ) 
                {
                    $sql = $mysqli->query("SELECT `id`,`status`,`date_up` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                    if( 0 < $sql->num_rows ) 
                    {
                        $row = $sql->fetch_assoc();
                        $id = $row["id"];
                        $adv_status = $row["status"];
                        $adv_date_up = $row["date_up"];
                        $sql->free();
                        $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "FormUp" . $security_key));
                        $token_next_h = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmHUp" . $security_key));
                        $token_next_a = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmAUp" . $security_key));
                        if( $token_post == false | $token_post != $token_check ) 
                        {
                            $result_text = "ERROR";
                            $message_text = "������ �������, ���������� �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $task_cena_up = sqltask("task_cena_up", 2);
                        $task_sale_up = sqltask("task_sale_up", 0);
                        $sql = $mysqli->query("SELECT `int_autoup`,`cnt_autoup`,`time_last`,`time_next` FROM `tb_ads_task_up` WHERE `ident`='" . $id . "' AND `user_name`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row_aup = $sql->fetch_assoc();
                            $int_autoup = $row_aup["int_autoup"];
                            $cnt_autoup = $row_aup["cnt_autoup"];
                            $time_last = (0 < $row_aup["time_last"] && $adv_date_up < $row_aup["time_last"] ? $row_aup["time_last"] : $adv_date_up);
                            $time_next = $row_aup["time_next"];
                            $sql->free();
                        }
                        else
                        {
                            $cnt_autoup = false;
                            $int_autoup = false;
                            $time_last = $adv_date_up;
                            $time_next = false;
                            $sql->free();
                        }

                        $result_text = "OK";
                        $message_text = "<div class=\"\">";
                        $message_text .= "<div>������� ����� ������� �� ������ ������� � ����� ������.</div>";
                        $message_text .= "<div>��������� �������� ������� ���������� <b>" . $task_cena_up . "</b> ���.</div>";
                        $message_text .= "<div style=\"margin-top:10px;\"><span class=\"sd_sub green\" style=\"min-width:110px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'confirm_hup', '" . $token_next_h . "', '�������������');\">������� ������� �������</span></div>";
                        $message_text .= "<div><hr style=\"margin:8px 4px;\"></div>";
                        $message_text .= "</div>";
                        $message_text .= "<div class=\"text-blue\">";
                        $message_text .= "<div>�������������� ��������. ������� ��������:</div>";
                        $message_text .= "<div>��������: <span class=\"text-red\">" . ((0 < $int_autoup && isset($task_int_autoup_arr[$int_autoup]) ? mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") : "���")) . "</span>. ��������: <span class=\"text-red\">" . ((0 < $cnt_autoup ? $cnt_autoup : "0")) . "</span></div>";
                        $message_text .= "<div>��������� ��������: <span class=\"text-red\">" . DATE("d.m.Y � H:i", $time_last) . "</span></div>";
                        if( $adv_status == "pay" && 0 < $cnt_autoup && 0 < $time_next ) 
                        {
                            $message_text .= "<div>��������� ����-��������: <span class=\"text-red\">" . DATE("d.m.Y � H:i", $time_next) . "</span> [<span style=\"font-family:arial;\">&#177;</span> 2 ������]</div>";
                        }

                        if( 0 < $task_sale_up ) 
                        {
                            $message_text .= "<div style=\"color:#006699; margin:7px auto;\">��� ������ <b>100 ����-�������� � �����</b> &mdash; ������ <b>" . $task_sale_up . "</b>%. <b>100 ����-�������� = " . round(($task_cena_up * 100 * (100 - $task_sale_up)) / 100, 2) . "</b> ���.</div>";
                        }

                        $message_text .= "<div id=\"newform\">";
                        $message_text .= "<select id=\"int_autoup" . $id . "\" class=\"ok\" style=\"width:190px; height:29px; padding:0px 3px; margin:0;\">";
                        foreach( $task_int_autoup_arr as $key => $val ) 
                        {
                            $message_text .= "<option value=\"" . $key . "\">" . $val . "</option>";
                        }
                        $message_text .= "</select>";
                        $message_text .= "<input type=\"text\" id=\"cnt_autoup" . $id . "\" maxlength=\"5\" value=\"\" class=\"ok\" style=\"width:190px; height:26px; padding:1px 3px 0 3px; margin:0 0 0 7px; text-align:center;\" placeholder=\"���������� ����-��������\" />";
                        $message_text .= "</div>";
                        $message_text .= "<div><span class=\"sd_sub green\" style=\"min-width:110px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'confirm_aup', '" . $token_next_a . "', '�������������');\">��������</span></div>";
                        $message_text .= "<div style=\"padding:5px; font-size:11px; line-height:12px;\">��� ��������� ����-��������, � ������ ���������� ����� ����� ������� ����������� �����, ������� ����� ����� �������.<br>����� ���������� �������� ����� ������������� � ������� ���������� ��������.<br>���� ����� ��������� �������, �� ��������� ������� �� �����, �� ������� ������������� ����������� �� �����, ���������� ���������� ����-�������� ���������� � ����� ������������ ��� ��������� ������� �������.</div>";
                        $message_text .= "</div>";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }
                    else
                    {
                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                }
                else
                {
                    if( $option == "confirm_hup" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmHUp" . $security_key));
                            $token_next_h = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayHUp" . $security_key));
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $task_cena_up = sqltask("task_cena_up", 2);
                            $result_text = "OK";
                            $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                            $message_text .= "� ������ <b>����������</b> ����� ����� ������� ����� � ������� <b>" . $task_cena_up . "</b> ���.<br>";
                            $message_text .= "������� ������� �<b>" . $id . "</b> � ������?";
                            $message_text .= "</div>";
                            $message_text .= "<div style=\"text-align:center;\">";
                            $message_text .= "<span class=\"sd_sub green\" style=\"min-width:30px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'pay_hup', '" . $token_next_h . "', '����������');\">��</span>";
                            $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">���</span>";
                            $message_text .= "</div>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "pay_hup" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status`,`zdurl`,`totals` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $cnt_totals = $row["totals"];
                            $black_url = StringUrl($row["zdurl"]);
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayHUp" . $security_key));
                            $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (" . $black_url . ")") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $row_bl = (0 < $sql_bl->num_rows ? $sql_bl->fetch_assoc() : false);
                            $sql_bl->free();
                            $task_cena_up = sqltask("task_cena_up", 2);
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( 0 < $user_ban_date ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� ������� ������������ �� ��������� ������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "block" ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� �������������, ���������� ������ ���������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status != "pay" ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� �������� � ������, ������� ���������� ���������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "pay" && $cnt_totals <= 0 ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� �������� � ������, ���������� ��������� ������ �������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $row_bl != false && $black_url != false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���� " . $row_bl["domen"] . " ��������� � ������ ������ ������� " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $user_money_rb < $task_cena_up ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "�� ����� ��������� ����� ������������ ������� ��� �������� �������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "pay" && 0 < $cnt_totals ) 
                            {
                                $sql_pos = $mysqli->query("SELECT COUNT(*) AS `position` FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='" . $id . "')");
                                $position_up = (0 < $sql_pos->num_rows ? $sql_pos->fetch_object()->position + 1 : false);
                                $sql_pos->free();
                            }
                            else
                            {
                                unset($position_up);
                            }

                            if( isset($position_up) && $position_up == 1 ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���� ������� �" . $id . " ��� ��������� �� ������ ������� � ������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $mysqli->query("UPDATE `tb_ads_task` SET `date_up`='" . time() . "', `ip`='" . $my_lastiplog . "' WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'" . $task_cena_up . "', `money_rek`=`money_rek`+'" . $task_cena_up . "' WHERE `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) \r\n\t\t\t\t\t\tVALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $task_cena_up . "','�������� ������� �" . $id . " � ������','�������','rashod')") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            stat_pay("task_up", $task_cena_up);
                            invest_stat($task_cena_up, 3);
                            $cnt_user_rb = p_floor($user_money_rb - $task_cena_up, 4);
                            $pos_class = "adv-down";
                            $pos_title = "������� ������� � ����� ������ ������: 1";
                            $pos_text = "1";
                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">s_h = false; new_id = false; \$(\"#task_info" . $id . "\").hide(); \$(\"#mess_info" . $id . "\").html(\"\"); \$(\"#my_bal_rs\").html(\"" . $cnt_user_rb . "\"); \$(\"#task_up" . $id . "\").attr({class:\"" . $pos_class . "\", title:\"" . $pos_title . "\"}).html(\"" . $pos_text . "\"); setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},1500);</script>";
                            $message_text .= "������� �" . $id . " ������� ������� � ������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "confirm_aup" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmAUp" . $security_key));
                            $token_next_a = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayAUp" . $security_key));
                            $int_autoup = (isset($_POST["int_autoup"]) && !is_array($_POST["int_autoup"]) && array_key_exists(intval(trim($_POST["int_autoup"])), $task_int_autoup_arr) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["int_autoup"])) ? escape(intval(limpiarez($_POST["int_autoup"]))) : false);
                            $cnt_autoup = (isset($_POST["cnt_autoup"]) && !is_array($_POST["cnt_autoup"]) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["cnt_autoup"])) ? escape(intval(limpiarez($_POST["cnt_autoup"]))) : false);
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $int_autoup == false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� �������� ����-��������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup == false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� ���������� ����-��������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup < 3 ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���������� ����-�������� ����� ���� �� ����� 3-�!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $task_cena_up = sqltask("task_cena_up", 2);
                            $task_sale_up = sqltask("task_sale_up", 0);
                            if( 100 <= $cnt_autoup && 0 < $task_sale_up ) 
                            {
                                $task_cena_up = ($task_cena_up * (100 - $task_sale_up)) / 100;
                            }

                            $money_pay = number_format($cnt_autoup * $task_cena_up, 3, ".", "");
                            $money_pay = (substr($money_pay, -1) == 0 ? number_format($money_pay, 2, ".", " ") : number_format($money_pay, 3, ".", " "));
                            $result_text = "OK";
                            $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                            $message_text .= "<b>����-�������� �������:</b><br>";
                            $message_text .= "��������: <span class=\"text-red\"><b>" . mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") . "</b></span>. ";
                            $message_text .= "���������� ����-��������: <span class=\"text-red\"><b>" . $cnt_autoup . "</b></span><br>";
                            $message_text .= "� ������ <b>����������</b> ����� ����� ������� ����� � ������� <span class=\"text-grey\"><b>" . $money_pay . "</b> ���</span>.<br>";
                            $message_text .= "�������� ����-�������� ������� �<b>" . $id . "</b> � ������?";
                            $message_text .= "</div>";
                            $message_text .= "<div style=\"text-align:center;\">";
                            $message_text .= "<span class=\"sd_sub green\" style=\"min-width:30px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'pay_aup', '" . $token_next_a . "', '����������');\">��</span>";
                            $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">���</span>";
                            $message_text .= "</div>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "pay_aup" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status`,`zdurl`,`totals` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $cnt_totals = $row["totals"];
                            $black_url = StringUrl($row["zdurl"]);
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayAUp" . $security_key));
                            $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (" . $black_url . ")") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $row_bl = (0 < $sql_bl->num_rows ? $sql_bl->fetch_assoc() : false);
                            $sql_bl->free();
                            $int_autoup = (isset($_POST["int_autoup"]) && !is_array($_POST["int_autoup"]) && array_key_exists(intval(trim($_POST["int_autoup"])), $task_int_autoup_arr) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["int_autoup"])) ? escape(intval(limpiarez($_POST["int_autoup"]))) : false);
                            $cnt_autoup = (isset($_POST["cnt_autoup"]) && !is_array($_POST["cnt_autoup"]) && preg_match("|^[\\d]{1,11}\$|", trim($_POST["cnt_autoup"])) ? escape(intval(limpiarez($_POST["cnt_autoup"]))) : false);
                            $task_cena_up = sqltask("task_cena_up", 2);
                            $task_sale_up = sqltask("task_sale_up", 0);
                            if( 100 <= $cnt_autoup && 0 < $task_sale_up ) 
                            {
                                $task_cena_up = ($task_cena_up * (100 - $task_sale_up)) / 100;
                            }

                            $money_pay = number_format($cnt_autoup * $task_cena_up, 3, ".", "");
                            $money_pay = (substr($money_pay, -1) == 0 ? number_format($money_pay, 2, ".", "") : number_format($money_pay, 3, ".", ""));
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( 0 < $user_ban_date ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� ������� ������������ �� ��������� ������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "block" ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� �������������, ���������� ������ ���������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "wait" ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� ������ ����-�������� � ������, ���������� ��������� ������ �������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $row_bl != false && $black_url != false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���� " . $row_bl["domen"] . " ��������� � ������ ������ ������� " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $int_autoup == false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� �������� ����-��������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup == false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� ���������� ����-��������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $cnt_autoup < 3 ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���������� ����-�������� ����� ���� �� ����� 3-�!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $user_money_rb < $money_pay ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "�� ����� ��������� ����� ������������ �������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $time_now = strtotime(DATE("d.m.Y H:i", time()));
                            $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'" . $money_pay . "', `money_rek`=`money_rek`+'" . $money_pay . "' WHERE `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            stat_pay("task_aup", $money_pay);
                            invest_stat($money_pay, 3);
                            if( $adv_status == "pay" && 0 < $cnt_totals ) 
                            {
                                $sql_pos = $mysqli->query("SELECT COUNT(*) AS `position` FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='" . $id . "')");
                                $position_up = (0 < $sql_pos->num_rows ? $sql_pos->fetch_object()->position + 1 : false);
                                $sql_pos->free();
                            }
                            else
                            {
                                unset($position_up);
                            }

                            $sql = $mysqli->query("SELECT `id`,`time_next` FROM `tb_ads_task_up` WHERE `ident`='" . $id . "' AND `user_name`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            if( 0 < $sql->num_rows ) 
                            {
                                $row_aup = $sql->fetch_assoc();
                                $time_next = $row_aup["time_next"];
                                $sql->free();
                                $mysqli->query("UPDATE `tb_ads_task_up` SET `int_autoup`='" . $int_autoup . "', `cnt_autoup`=`cnt_autoup`+'" . $cnt_autoup . "', `time_change`='" . time() . "', `money_pay`=`money_pay`+'" . $money_pay . "' WHERE `ident`='" . $id . "' AND `user_name`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $text_history = "��������� ����-�������� ������� �" . $id . " � ������ (��������: " . mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") . " ����������: +" . $cnt_autoup . ")";
                            }
                            else
                            {
                                $time_next = false;
                                $sql->free();
                                $mysqli->query("INSERT INTO `tb_ads_task_up` (`ident`,`user_name`,`int_autoup`,`cnt_autoup`,`time_last`,`time_next`,`time_change`,`money_pay`) \r\n\t\t\t\t\t\tVALUES('" . $id . "','" . $user_name . "','" . $int_autoup . "','" . $cnt_autoup . "','0','" . ($time_now + $int_autoup) . "','" . time() . "','" . $money_pay . "')") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $text_history = "����-�������� ������� �" . $id . " � ������ (��������: " . mb_strtolower($task_int_autoup_arr[$int_autoup], "CP1251") . " ����������: " . $cnt_autoup . ")";
                            }

                            $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) \r\n\t\t\t\t\tVALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $money_pay . "','" . $text_history . "','�������','rashod')") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            if( $adv_status == "pay" && isset($position_up) && $position_up != 1 && $time_next < $time_now ) 
                            {
                                $mysqli->query("UPDATE `tb_ads_task_up` SET `cnt_autoup`=`cnt_autoup`-'1', `time_last`='" . $time_now . "', `time_next`='" . ($time_now + $int_autoup) . "' WHERE `ident`='" . $id . "' AND `user_name`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $mysqli->query("UPDATE `tb_ads_task` SET `date_up`='" . time() . "', `ip`='" . $my_lastiplog . "' WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                                $pos_class = "adv-down";
                                $pos_title = "������� ������� � ����� ������ ������: 1";
                                $pos_text = "1";
                            }
                            else
                            {
                                if( isset($position_up) && 0 < $position_up && $position_up < 100 ) 
                                {
                                    $pos_class = "adv-down";
                                    $pos_title = "������� ������� � ����� ������ ������: " . $position_up;
                                    $pos_text = $position_up;
                                }
                                else
                                {
                                    $pos_class = "adv-up";
                                    $pos_title = "������� ������� � ������";
                                    $pos_text = "&uarr;";
                                }

                            }

                            $cnt_user_rb = p_floor($user_money_rb - $money_pay, 4);
                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">s_h = false; new_id = false; \$(\"#task_info" . $id . "\").hide(); \$(\"#mess_info" . $id . "\").html(\"\"); \$(\"#my_bal_rs\").html(\"" . $cnt_user_rb . "\"); \$(\"#task_up" . $id . "\").attr({class:\"" . $pos_class . "\", title:\"" . $pos_title . "\"}).html(\"" . $pos_text . "\"); setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},2000);</script>";
                            $message_text .= "����-������ ��� ������� �" . $id . " ������� �����������!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "form_vip" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "FormVIP" . $security_key));
                            $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmVIP" . $security_key));
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $task_cena_vip = sqltask("task_cena_vip", 2);
                            $task_max_vip = sqltask("task_max_vip", 0);
                            $result_text = "OK";
                            $message_text = "<div>������� ����� ��������� � VIP �����. ���� \"VIP �������\" ��������� �� ���� ��������� � ���������.</div>";
                            $message_text .= "<div>������������ ���������� ������� � VIP ����� - <b>" . $task_max_vip . "</b> ��.</div>";
                            $message_text .= "<div>��������� ���������� ������� � VIP ����� ���������� <b>" . $task_cena_vip . "</b> ���.</div>";
                            $message_text .= "<div style=\"margin-top:10px;\"><span class=\"sd_sub green\" style=\"min-width:110px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'confirm_vip', '" . $token_next . "', '�������������');\">�������� ������� � VIP ����</span></div>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "confirm_vip" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "ConfirmVIP" . $security_key));
                            $token_next = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayVIP" . $security_key));
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $task_cena_vip = sqltask("task_cena_vip", 2);
                            $result_text = "OK";
                            $message_text = "<div style=\"text-align:center; margin:10px 0px 15px 0px; line-height:18px;\">";
                            $message_text .= "� ������ <b>����������</b> ����� ����� ������� ����� � ������� <b>" . $task_cena_vip . "</b> ���.<br>";
                            $message_text .= "���������� ������� �<b>" . $id . "</b> � VIP �����?";
                            $message_text .= "</div>";
                            $message_text .= "<div style=\"text-align:center;\">";
                            $message_text .= "<span class=\"sd_sub green\" style=\"min-width:30px;\" onClick=\"FuncAdv(" . $id . ", 'task', 'pay_vip', '" . $token_next . "', '����������');\">��</span>";
                            $message_text .= "<span class=\"sd_sub red\" style=\"min-width:30px;\" onClick=\"\$('#LoadModal').modalpopup('close'); return false;\">���</span>";
                            $message_text .= "</div>";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    if( $option == "pay_vip" ) 
                    {
                        $sql = $mysqli->query("SELECT `id`,`status`,`vip`,`zdprice`,`zdurl`,`totals` FROM `tb_ads_task` WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                        if( 0 < $sql->num_rows ) 
                        {
                            $row = $sql->fetch_assoc();
                            $id = $row["id"];
                            $adv_status = $row["status"];
                            $adv_date_vip = $row["vip"];
                            $adv_price = $row["zdprice"];
                            $cnt_totals = $row["totals"];
                            $black_url = StringUrl($row["zdurl"]);
                            $sql->free();
                            $token_check = strtolower(md5($id . strtolower($user_name) . $_SERVER["HTTP_HOST"] . "PayVIP" . $security_key));
                            $sql_bl = $mysqli->query("SELECT `id`,`domen` FROM `tb_black_sites` WHERE `domen` IN (" . $black_url . ")") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $row_bl = (0 < $sql_bl->num_rows ? $sql_bl->fetch_assoc() : false);
                            $sql_bl->free();
                            $task_cena_vip = sqltask("task_cena_vip", 2);
                            if( $token_post == false | $token_post != $token_check ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������ �������, ���������� �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( 0 < $user_ban_date ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� ������� ������������ �� ��������� ������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "block" ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "������� �������������, ���������� ������ ���������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status != "pay" ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� ���������� � VIP �����, ������� ���������� ���������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $adv_status == "pay" && $cnt_totals <= 0 ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "��� ���������� � VIP ����� ���������� ��������� ������ �������!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $row_bl != false && $black_url != false ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���� " . $row_bl["domen"] . " ��������� � ������ ������ ������� " . strtoupper($_SERVER["HTTP_HOST"]) . "";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            if( $user_money_rb < $task_cena_vip ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "�� ����� ��������� ����� ������������ ������� ��� ���������� ������� � VIP �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $task_max_vip = sqltask("task_max_vip", 0);
                            if( $adv_status == "pay" && 0 < $adv_date_vip && 0 < $cnt_totals ) 
                            {
                                $sql_pos = $mysqli->query("SELECT COUNT(*) AS `position` FROM `tb_ads_task` WHERE `status`='pay' AND `vip`>(SELECT `vip` FROM `tb_ads_task` WHERE `id`='" . $id . "')");
                                $position_vip = (0 < $sql_pos->num_rows ? $sql_pos->fetch_object()->position + 1 : false);
                                $sql_pos->free();
                            }
                            else
                            {
                                unset($position_vip);
                            }

                            if( isset($position_vip) && $position_vip < $task_max_vip && $position_vip == 1 ) 
                            {
                                $result_text = "ERROR";
                                $message_text = "���� ������� �" . $id . " ��� ��������� �� ������ ������� � VIP �����!";
                                exit( my_json_encode($ajax_json, $result_text, $message_text) );
                            }

                            $mysqli->query("UPDATE `tb_ads_task` SET `vip`='" . time() . "', `ip`='" . $my_lastiplog . "' WHERE `id`='" . $id . "' AND `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $mysqli->query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'" . $task_cena_vip . "', `money_rek`=`money_rek`+'" . $task_cena_vip . "' WHERE `username`='" . $user_name . "'") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            $mysqli->query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) \r\n\t\t\t\t\t\tVALUES('1','" . $user_name . "','" . $user_id . "','" . DATE("d.m.Y H:i") . "','" . time() . "','" . $task_cena_vip . "','�������� ������� �" . $id . " � VIP ����','�������','rashod')") or exit( my_json_encode($ajax_json, "ERROR", $mysqli->error) );
                            stat_pay("task_vip", $task_cena_vip);
                            invest_stat($task_cena_vip, 3);
                            $cnt_user_rb = p_floor($user_money_rb - $task_cena_vip, 4);
                            $result_text = "OK";
                            $message_text = "<script type=\"text/javascript\" language=\"JavaScript\">s_h = false; new_id = false; \$(\"#task_info" . $id . "\").hide(); \$(\"#mess_info" . $id . "\").html(\"\"); \$(\"#my_bal_rs\").html(\"" . $cnt_user_rb . "\"); setTimeout(function(){\$(\"#LoadModal\").modalpopup(\"close\");},1500);</script>";
                            $message_text .= "������� �" . $id . " ������� ��������� � VIP �����!";
                            exit( my_json_encode($ajax_json, $result_text, $message_text) );
                        }

                        $sql->free();
                        $result_text = "ERROR";
                        $message_text = "������� �" . $id . " �� �������!";
                        exit( my_json_encode($ajax_json, $result_text, $message_text) );
                    }

                    $result_text = "ERROR";
                    $message_text = "Option[" . $option . "] not found...";
                    exit( my_json_encode($ajax_json, $result_text, $message_text) );
                }

            }
            else
            {
                $result_text = "ERROR";
                $message_text = "������: ��� ������� �� ���������!";
                exit( my_json_encode($ajax_json, $result_text, $message_text) );
            }

        }
        else
        {
            $sql_user->free();
            $result_text = "ERROR";
            $message_text = "������������ �� ���������������!";
            exit( my_json_encode($ajax_json, $result_text, $message_text) );
        }

    }
    else
    {
        $result_text = "ERROR";
        $message_text = "���������� ��������������!";
        exit( my_json_encode($ajax_json, $result_text, $message_text) );
    }

}
else
{
    $result_text = "ERROR";
    $message_text = "Access denied!";
    exit( my_json_encode($ajax_json, $result_text, $message_text) );
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    $ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
    $message_text = false;
    $errfile = str_ireplace(ROOT_DIR, false, $errfile);
    switch( $errno ) 
    {
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
            $message_text = "91" . $errno . "] " . $errstr . " in line " . $errline . " in " . $errfile;
            break;
    }
    exit( my_json_encode($ajax_json, "ERROR", $message_text) );
}

function json_encode_cp1251($json_arr)
{
    $json_arr = json_encode($json_arr);
    $arr_replace_cyr = array( "\\u0)0", "\\u0430", "\\u0)1", "\\u0431", "\\u0)2", "\\u0432", "\\u0)3", "\\u0433", "\\u0)4", "\\u0434", "\\u0)5", "\\u0435", "\\u0401", "\\u0451", "\\u0)6", "\\u0436", "\\u0)7", "\\u0437", "\\u0)8", "\\u0438", "\\u0)9", "\\u04'", "\\u0)a", "\\u043a", "\\u0)b", "\\u043b", "\\u0)c", "\\u043c", "\\u0)d", "\\u043d", "\\u0)e", "\\u043e", "\\u0)f", "\\u043f", "\\u0420", "\\u0440", "\\u0421", "\\u04)", "\\u0422", "\\u0442", "\\u0423", "\\u0443", "\\u0424", "\\u0444", "\\u0425", "\\u0445", "\\u0426", "\\u0446", "\\u0427", "\\u0447", "\\u0428", "\\u0448", "\\u0429", "\\u0449", "\\u042a", "\\u044a", "\\u042b", "\\u044b", "\\u042c", "\\u044c", "\\u042d", "\\u044d", "\\u042e", "\\u044e", "\\u042f", "\\u044f" );
    $arr_replace_utf = array( "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�" );
    $json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
    return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text, $status_text = false, $balances_text = false)
{
    return ($ajax_json == "json" ? json_encode_cp1251(array( "result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text), "status" => iconv("CP1251", "UTF-8", $status_text), "balances" => iconv("CP1251", "UTF-8", $status_text) )) : $message_text);
}

function escape($value)
{
    global $mysqli;
    return $mysqli->real_escape_string($value);
}

function SqlTask($item, $decimals = false)
{
    global $mysqli;
    $ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ? "json" : "nojson");
    $sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='" . $item . "' AND `howmany`='1'") or exit( ($ajax_json == "json" ? my_json_encode($ajax_json, "ERROR", $mysqli->error) : $mysqli->error) );
    (0 < $sql->num_rows ? $sql->fetch_object()->price : exit);
}

function limpiarez($mensaje)
{
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

function count_text($count, $text1, $text2, $text3)
{
    if( 0 <= $count ) 
    {
        if( (10 <= $count && $count <= 20) | (10 <= substr($count, -2, 2) && substr($count, -2, 2) <= 20) ) 
        {
            return (string) $count . "32" . $text3;
        }

        switch( substr($count, -1, 1) ) 
        {
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


