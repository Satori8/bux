<?php
session_start();
error_reporting (E_ALL);
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
	return ($ajax_json=="json") ? json_encode_cp1251(array("status" => iconv("CP1251", "UTF-8", $result_text), "html" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
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

function limpiarezz($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	//$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
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

$set_error_handler = set_error_handler("myErrorHandler", E_ALL);
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
require(ROOT_DIR."/config.php");
include("../merchant/func_mysql.php");
include("../funciones.php");
//include('/configsite/config_site.php');
//$username = $_SESSION["userLog"];
//$ip=$_SERVER['REMOTE_ADDR'];

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
$ip = getRealIP();

//$password = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? ($_SESSION["userPas"]) : false;
//sleep(1);
//настройки
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
$cena_task = mysql_result($sql,0,0);
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
$nacenka_task = mysql_result($sql,0,0);
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='limit_min_task' AND `howmany`='1'");
$limit_min_task = mysql_result($sql,0,0);
$color_price = 1.00;
$up_task_pr = 1;
$up_vip_task_pr = 3.00;
//
//$func=$_POST['func'];
//$id_z=$_POST['id'];
$func = ( isset($_POST["func"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["func"]))) ) ? htmlspecialchars(trim($_POST["func"])) : false;
$id_z = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
$rid = $id_z;
//echo $func;

$user_ca = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$username."'"));
$my_country=$user_ca['country_cod'];

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Для доступа к этой странице необходимо авторизоваться!</b>';
	exit();
}


if($func == "zero_otchet") {


$task_sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='".$id_z."'");
$zero_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' && `user_name`='$username' &&`ident`='".$id_z."'");
if(mysql_num_rows($task_sql)==0){
echo '<span class="msg-warning">Такого задания нету!</span>';
exit();
}elseif(mysql_num_rows($zero_sql)==0){
echo '<span class="msg-warning">Это не ваш отчет или редактировать его нельзя!</span>';
exit();
}else{
$zero_arr=mysql_fetch_array($zero_sql);
?>
<link rel="stylesheet" type="text/css" href="/style/task.css" />
<div class="text-popup" id="js-popup">
    <form method="post" action="/ajax/ajax_task.php" class="aj-form_z" data-infa="true,<?=$rid;?>,">
	    <table class="tables-popup">
	      <tbody><tr>
		      <td>Ваш отчет [<b><span id="length">3000</span></b>]</td>
		    </tr>
			  <tr>
			    <td><textarea name="ask_reply" id="coment" style="width: 98%;height: 200px; background-color: #FBFCCC;" onkeyup="desk_limit('#coment', 3000, '#length')"><?=$zero_arr['ctext'];?></textarea></td>
			  </tr>
			  <tr>
			    <td class="butt-td">
				  <button><span class="btn green" style="width: 150px;">Сохранить изменения</span></button>
				  <button><span class="btn red" style="width: 200px;" onclick="$('#zero-del').submit();return false;">Отказаться от выполнения</span></button>
				  </td>
			  </tr>
	    </tbody></table>           
	    <input name="func" type="hidden" value="zero_edit">
	    <input name="id" type="hidden" value="<?=$rid;?>">      
    </form>
	
    <form method="post" action="/ajax/ajax_task.php" class="aj-form_z" id="zero-del" data-infa="true,<?=$rid;?>,">
      <input name="func" type="hidden" value="zero_del">
	  <input name="id" type="hidden" value="<?=$rid;?>">
    </form>
</div>

<?
}
	}elseif($func == "zero_del") {
$task_sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='".$id_z."'");
$zero_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='wait' or `status`='dorab' or `status`='') && `user_name`='$username' &&`ident`='".$id_z."'");
$zero_sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='wait' or `status`='dorab') && `user_name`='$username' &&`ident`='".$id_z."'");
		if(mysql_num_rows($task_sql)==0){
echo '<span class="msg-warning">Такого задания нету!</span>';
exit();
		}elseif(mysql_num_rows($zero_sql)==0){
echo '<span class="msg-warning">Это не ваш отчет или редактировать его нельзя!</span>';
exit();
		}else{		
		if(mysql_num_rows($zero_sql_p)>0){
		mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `wait`=`wait`-'1' WHERE `id`='$id_z'") or die(mysql_error());
		}
		mysql_query("DELETE FROM `tb_ads_task_pay` WHERE (`status`='wait' or `status`='') && `user_name`='$username' && `ident`='$rid'") or die(mysql_error());	
		$ajax_json="json"; $result_text = "ok_del"; $message_text = '<span class="msg-ok">Отчет успешно удален!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
		}		
	
	}elseif($func == "zero_edit") {
$task_sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='".$id_z."'");
$zero_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' && `user_name`='$username' &&`ident`='".$id_z."'");
		if(mysql_num_rows($task_sql)==0){
echo '<span class="msg-warning">Такого задания нету!</span>';
exit();
		}elseif(mysql_num_rows($zero_sql)==0){
echo '<span class="msg-warning">Это не ваш отчет или редактировать его нельзя!</span>';
exit();
		}else{
$zero_arr=mysql_fetch_array($zero_sql);
//$ctext=iconv("UTF-8", "cp1251",$_POST['ask_reply']);
$ctext = (isset($_POST["ask_reply"])) ? limpiarezz($_POST["ask_reply"]) : false;
mysql_query("UPDATE `tb_ads_task_pay` SET `ctext`='$ctext' WHERE `user_name`='$username' && `ident`='$rid' && `status`='wait'");
$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет отредактирован!</span>';
exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

}elseif($func == "otc_z") {
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите отказаться от выполнения задания №<?=$rid;?></p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<button><span class="proc-btn-d" style="width: 200px;" onclick="$('#zero-del').submit();return false;">Да</span></button>
	    <form method="post" action="/ajax/ajax_task.php" class="aj-form_z" id="zero-del" data-infa="true,<?=$rid;?>,">
      <input name="func" type="hidden" value="zero_del">
	  <input name="id" type="hidden" value="<?=$rid;?>">
    </form>
	<!--<span class="proc-btn-d" onclick="task_fun($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=color_task&id=<?=$id_z;?>');return false;">Да</span>  -->
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?
}elseif($func == "done_otch") {
///////////////////////////////////////////////
$text_false_otv="";
$no_form=0; $kolvo="";
$ost=0;
//echo 'gg'; 
$partnerid_sql = mysql_fetch_array(mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$username."'"));
$partnerid=$partnerid_sql['id'];

$sql11 = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
if(mysql_num_rows($sql11)>0) {
	$row = mysql_fetch_array($sql11);

	$zdre = $row["zdre"];
	$rek_name = $row["username"];
	$rek_id = $row["user_id"];
	$zdprice = $row["zdprice"];
	$zdurl = $row["zdurl"];
	$zdcheck = $row["zdcheck"];
	$zdotv = $row["zdotv"];
	$country_targ = $row["country_targ"];
	$mailwm = $row["mailwm"];
	$wmid_task = $row["wmid"];

	$time_dist=time()-$row['distrib'];
	$sql_distrib = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `date_start`>'$time_dist' && ident='$rid'");
	
	$sql_pss = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1"));

	$check_user = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name' AND `user_name`='$username'");
	if(mysql_num_rows($check_user)) {
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Вы не можете выполнять задания этого рекламодателя, так как рекламодатель запретил вам выполнять его задания!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}elseif(strtolower($rek_name)==strtolower($username)) {
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Вы не можете выполнять свои задания!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}elseif($country_targ==2 && strtolower($my_country)!="ua") {
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! По условиям Гео-Таргетинга задание не дустопно для выполнения и оплаты!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}elseif($country_targ==1 && strtolower($my_country)!="ru") {
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! По условиям Гео-Таргетинга задание не дустопно для выполнения и оплаты!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		
	}elseif($row['distrib']>0 && mysql_num_rows($sql_distrib)>0 && $sql_pss<=0) {
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Задание пока что на автоматической паузе!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}


	if(count($_POST>0) && isset($_POST["ask_reply"])) {

		$ctext = (isset($_POST["ask_reply"])) ? limpiarezz($_POST["ask_reply"]) : false;

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_p)>0) {
			$row_p = mysql_fetch_array($sql_p);

			$ost = ($row_p["date_end"] + ($zdre * 60 * 60) );
			$ost = ( $ost - time() );

			if(isset($_POST["ask_reply"])) {
				if($row_p["status"]=="" || $row_p["status"]=="dorab") {
					if($ctext==false) {
						$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Вы не указали информацию (из описания задания) для проверки задания рекламодателем!</span>';
						exit(my_json_encode($ajax_json, $result_text, $message_text));
					}else{
						if($zdcheck==2) {

							if(strtolower($zdotv)==strtolower($ctext)) {

								$sql_rt = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
								$reit_task = mysql_result($sql_rt,0,0);

								mysql_query("UPDATE `tb_ads_task_pay` SET `status`='good', `rek_id`='$rek_id', `user_id`='$partnerid', `ctext`='$ctext', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
								mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`-'1', `goods`=`goods`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task',  `visits_t`=`visits_t`+1, `money`=`money`+'$zdprice', `money_t`=`money_t`+'$zdprice' WHERE `username`='$username'") or die(mysql_error());

								#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
								$cnt_unique_rep = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `user_name`='$username' AND `ident`='$rid'"));
								if($cnt_unique_rep==1) mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`+'1' WHERE `username`='$username'") or die(mysql_error());
								#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###

								### START СБОР СТАТИСТИКИ ####
								$DAY_S = strtolower(DATE("D"));
								stats_users($username, $DAY_S, 'task');
								### END СБОР СТАТИСТИКИ ######

								#### КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
								konkurs_ads_new($wmid_task, $rek_name, $zdprice);
								#### КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
								/*#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###
					konkurs_ads_task_new($wmiduser, $rek_name, $zdprice);
					#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###
								#### ИНВЕСТ ПРОЕКТ ###
								stat_pay('task_pay', $zdprice);
								invest_stat($zdprice, 2);
								#### ИНВЕСТ ПРОЕКТ ###*/
								
								#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###
								$cnt_konk = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `rek_name`='$rek_name' AND `user_name`='$username' AND `date_end`>='".(time()-24*60*60)."'"));
								#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###
								
								### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
								if($country_targ == 0 && strtolower($rek_name) != strtolower($username) && $my_ban_date == 0) {
									$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
									$konk_complex_status = mysql_result($sql,0,0);

									if($konk_complex_status==1 && $cnt_konk<=3) {
										$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
										$konk_complex_date_start = mysql_result($sql,0,0);

										$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
										$konk_complex_date_end = mysql_result($sql,0,0);

										$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".($zdre==0 ? "task" : "task_re")."'") or die(mysql_error());
										$konk_complex_point = mysql_result($sql,0,0);

										if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
											mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$username'") or die(mysql_error());
										}
									}
								}
								### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###

								#### NEW КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###
								$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
								$konk_task_status = mysql_result($sql,0,0);

								if($konk_task_status==1 && $cnt_konk<=3) {
									$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
									$konk_task_date_start = mysql_result($sql,0,0);

									$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
									$konk_task_date_end = mysql_result($sql,0,0);

									if($konk_task_date_end>=time() && $konk_task_date_start<time()) {
										mysql_query("UPDATE `tb_users` SET `konkurs_task`=`konkurs_task`+'1' WHERE `username`='$username'") or die(mysql_error());
									}
								}
								#### NEW КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###

									$sql_r = mysql_query("SELECT `id`,`referer`,`referer2`,`referer3`,`referer4` FROM `tb_users` WHERE `username`='$username'");
					$row_r = mysql_fetch_row($sql_r);

								$my_referer_1 = $row_r["0"];
								$my_referer_2 = $row_r["1"];
								$my_referer_3 = $row_r["2"];
								

								$add_money_r_1=0; $add_money_r_2=0; $add_money_r_3=0;

									if($my_referer_1!=false) {
					$sql_r_1 = mysql_query("SELECT `id`,`money_rb`,`reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
					$row_r_1 = mysql_fetch_array($sql_r_1);
					$user_id_ref_1 = $row_r_1["id"];
					$money_rb_ref_1 = $row_r_1["money_rb"];
					$reiting_1 = $row_r_1["reiting"];

						$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_1' AND `r_do`>='".floor($reiting_1)."'");
						if(mysql_num_rows($sql_rang)>0) {
							$row_rang = mysql_fetch_array($sql_rang);
						}else{
							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
							$row_rang = mysql_fetch_array($sql_rang);
						}
						$ref_proc_task_1 = $row_rang["t_1"];

						$add_money_r_1 = p_floor( ($zdprice * $ref_proc_task_1 / 100), 6);

						mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$username'") or die(mysql_error());
						$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");
						konkurs_clic_ref($my_referer_1, $add_money_r_1);
					 konkurs_best_ref($username, $add_money_r_1);
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

						### РЕФ КОНКУРС I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
								}
							}
						}
						### РЕФ КОНКУРС I ур. ###

						### РЕФ КОНКУРС I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
								}
							}
						}
						### РЕФ КОНКУРС I ур. ###
						
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					if($country_targ=="0") {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='6' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							//if($row_t["limit_kon_sum"]!=0 && ($my_visits+$my_visits_bs+$add_money_r_1)==$row_t["limit_kon_sum"]) {
							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_1' WHERE `username`='$username' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$username','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
				//}
					### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###

						if($my_referer_2!="") {
							$sql_r_2 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
							$row_r_2 = mysql_fetch_array($sql_r_2);
							$reiting_2 = $row_r_2["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_2' AND `r_do`>='".floor($reiting_2)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_array($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_array($sql_rang);
							}
							$ref_proc_task_2 = $row_rang["t_2"];

							$add_money_r_2 = p_floor( ($zdprice * $ref_proc_task_2 / 100), 6);

							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_2' AND `type`='ref2'");
							konkurs_clic_ref($my_referer_2, $add_money_r_2);
					 konkurs_best_ref($username, $add_money_r_2);
	                       if(mysql_num_rows($sql_stat_re)>0) {
	                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `type`='ref2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }else{
					           mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_2','ref2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				         }

							### РЕФ КОНКУРС II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
									}
								}
							}
							### РЕФ КОНКУРС II ур. ###

							### РЕФ КОНКУРС II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
									}
								}
							}
							### РЕФ КОНКУРС II ур. ###
							
								### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_2)==$row_t["limit_kon_sum"]) {
								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_2' WHERE `username`='$username' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$username','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
					//}
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###

							if($my_referer_3!="") {
								$sql_r_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
								$row_r_3 = mysql_fetch_array($sql_r_3);
								$reiting_3 = $row_r_3["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_3' AND `r_do`>='$reiting_3'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$ref_proc_task_3 = $row_rang["t_3"];

								$add_money_r_3 = p_floor( ($zdprice * $ref_proc_task_3 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());
								$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_3' AND `type`='ref3'");
								konkurs_clic_ref($my_referer_3, $add_money_r_3);
					 konkurs_best_ref($username, $add_money_r_3);
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `type`='ref3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_3','ref3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }
								### РЕФ КОНКУРС III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### РЕФ КОНКУРС III ур. ###

								### РЕФ КОНКУРС III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### РЕФ КОНКУРС III ур. ###
								
								### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_3' WHERE `username`='$username' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$username','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
					}
			   }
		   }
								
								### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
								if($my_referer_1 != false) {
									$sql_r_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
									if(mysql_num_rows($sql_r_1)>0) {
										$row_r_1 = mysql_fetch_assoc($sql_r_1);
										$user_id_ref_1 = $row_r_1["id"];
										$money_ref_1 = $row_r_1["money_rb"];

										$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='4' ORDER BY `id` DESC LIMIT 1");
										if(mysql_num_rows($sql_r_b_1)>0) {
											$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

											$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(mysql_error());
											$comis_sys_bon = mysql_result($sql_b,0,0);

											$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `ident`='".$row_r_b_1["id"]."' AND `type`='4' ORDER BY `id` DESC LIMIT 1");
											if(mysql_num_rows($sql_r_b_stat_1)>0) {
												$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

												if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
													$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
													$money_ureferera_nado = round($money_ureferera_nado, 2);

													if($money_ref_1 >= $money_ureferera_nado) {
														mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
														mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

														mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$username'") or die(mysql_error());
														mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());

														mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
														VALUES('1','$username','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." выполненных заданий','Зачислено','rashod')") or die(mysql_error());

														mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
														VALUES('1','$my_referer_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $username за ".$row_r_b_1["count_nado"]." выполненных заданий','Списано','rashod')") or die(mysql_error());

														if(trim($row_r_b_1["description"])!=false) {
															mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
															VALUES('$username','Система','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." кликов в серфинге','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
														}
													}else{
														mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
													}
												}else{
													mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
												}
											}else{
												mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
												VALUES('-1','$username','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(mysql_error());
											}
										}
									//}
								//}
								### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
								
								### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='6' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$username' AND `ident`='".$row_r_b_1["id"]."' AND `type`='6' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if((number_format($row_r_b_stat_1["stat_info"],2,".","`"))==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$username'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$username','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Зачислено','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $username за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$username','Система','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$username','$add_money_r_1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
				}
			}
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###

								$sql_rek = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$rek_name'");
								$row_rek = mysql_fetch_row($sql_rek);

								$referer_rek = $row_rek["0"];

								if($referer_rek!="") {
									$sql_cr = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_rek' AND `howmany`='1'");
									$ref_proc_rek = mysql_result($sql_cr,0,0);

									$add_balans_rek = round(($zdprice * $ref_proc_rek / 100), 6);

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'$add_balans_rek', `ref_money_rek`=`ref_money_rek`+'$add_balans_rek' WHERE `username`='$referer_rek'") or die(mysql_error());
								}

								$ajax_json="json"; $result_text = "ok_ot"; $message_text = "<span class='msg-ok'>Задание одобрено!</span><META HTTP-EQUIV='REFRESH' CONTENT='2;URL=view_task.php?page=task&rid=".$rid."'><br>".$kolvo_t.".</span>";
								exit(my_json_encode($ajax_json, $result_text, $message_text));
								
							}else{
								if($row_p["kol_otv"]>=2) {
									$kolvo = (2-$row_p["kol_otv"]);
									if($kolvo==2) {
									$kolvo_t="Допустить ошибку Вы можете еще 2 раза";
									}
									if($kolvo==1) {
									$kolvo_t="Допустить ошибку Вы можете еще 1 раз";
									}
									if(!$kolvo) {
									$kolvo_t="Лимит попыток закончился.";
									$no_form=1;}
									
								

									if($kolvo==0) {
										mysql_query("UPDATE `tb_ads_task_pay` SET `status`='bad', `rek_id`='$rek_id', `user_id`='$partnerid', `ctext`='$ctext', `kol_otv`=`kol_otv`+'1', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
										mysql_query("UPDATE `tb_ads_task` SET `bads`=`bads`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());

										#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
										mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`-'1' WHERE `username`='$username'") or die(mysql_error());
										#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
									}
										$ajax_json="json"; $result_text = "error"; $message_text ="<span class='msg-error'>Ответ неправильный!<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=view_task.php?page=task&rid=".$rid."'><br>".$kolvo_t.".</span>";
									
							     exit(my_json_encode($ajax_json, $result_text, $message_text));
								/*	$text_false_otv = "<div align=\"center\" style=\"color:#FF0000; font-weight:bold;\">Ответ неправильный!<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=view_task.php?page=task&rid=".$id."'><br>".$kolvo_t.".</div>";*/
								}else{
									$kolvo = (2-$row_p["kol_otv"]);
									if($kolvo==2) {$kolvo_t="Допустить ошибку Вы можете еще 2 раза";}
									if($kolvo==1) {$kolvo_t="Допустить ошибку Вы можете еще 1 раз";} 
									if(!$kolvo) {$kolvo_t="Лимит попыток закончился."; $no_form=1;}
									
									mysql_query("UPDATE `tb_ads_task_pay` SET `ctext`='$ctext', `rek_id`='$rek_id', `user_id`='$partnerid', `kol_otv`=`kol_otv`+'1', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
									
									
									$ajax_json="json"; $result_text = "error"; $message_text ="<span class='msg-error'>Ответ неправильный!<META HTTP-EQUIV='REFRESH' CONTENT='2;URL=view_task.php?page=task&rid=".$rid."'><br>".$kolvo_t.".</span>";
								exit(my_json_encode($ajax_json, $result_text, $message_text));
									
								}

							}
						}else{
							mysql_query("UPDATE `tb_ads_task_pay` SET `status`='wait', `rek_id`='$rek_id', `user_id`='$partnerid', `ctext`='$ctext', `pay`='$zdprice', `date_end`='".time()."', `ip`='$ip' WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
							mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`-'1',`wait`=`wait`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
							
							$ajax_json="json"; $result_text = "ok_ot"; $message_text = '<span class="msg-ok">Отчет отправлен!</span>';
								exit(my_json_encode($ajax_json, $result_text, $message_text));
						}
					}
				}else{
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Вы уже выполнили это задание.</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}else{
				if($zdre>0) {
					if($ost >= 0 && $row_p["status"]!=""){
						$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Вы уже выполнили это задание.</span>';
						exit(my_json_encode($ajax_json, $result_text, $message_text));
					}
				}else{
					if($row_p["status"]!=""){
						$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Вы уже выполнили это задание.</span>';
						exit(my_json_encode($ajax_json, $result_text, $message_text));
					}
				}
			}
		}else{
			if($zdre==0){
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Вы еще не начали выполнять это задание!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}
			if( ($zdre>0 && mysql_num_rows($sql_p)<1 ) | ($zdre!=0 && mysql_num_rows($sql_p)<2 && $ost<=0) ) {
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Вы еще не начали выполнять это задание!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}
		
}else{
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-ok">Ошибка! Такого задания нет, либо оно не активно!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
}
///////////////////////////////////////////////
}elseif($func == "all_com") {
?>

<div class="text-popup" id="js-popup">
  <div id="all-task-otziv-scroll" style="max-height:600px;overflow-y: auto;">
    <table class="tables" border="0" cellpadding="0" cellspacing="1" style="width:100%;" id="history-task-otziv">
      

<?
function smile($mes) {
				for($i=0; $i<=37; $i++) {
					$mes = str_ireplace("<br><br>", "<br>", $mes);
					$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-".$i.".gif\" alt=\"\" align=\"middle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
				}
				return $mes;
			}
			
$otziv_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `coment`!='' &&`ident`='".$id_z."'");
$otziv_num = mysql_num_rows($otziv_sql);
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
if($otziv_num>0){
while ($otziv = mysql_fetch_array($otziv_sql)) {
$otziv_s = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$otziv['user_name']."'"));
?>
<tr id="otziv-del<?=$id_z;?>">
    <td style="padding: 2px; border-bottom: 1px solid #ccc;">
      <img border="0" src="/avatar/<?=$otziv_s['avatar'];?>" align="" absmiddle"="" width="60" height="60">
    </td>
    <td style="padding: 5px; border-bottom: 1px solid #ccc; text-align: left;" width="90%" align="left">
	  <b><?=$otziv['user_name'];?></b> (ID: <?=$otziv_s['id'];?>) 
	  <span class="desctext"><? echo DATE("d.m.Yг. H:i", $otziv["date_com"]);?></span>
      <span class="job-reply">   
	  
	  <?if($otziv['ocenka']>='0' and $otziv['ocenka']<'1'){?>
            <span class="rating0" title=""></span>
			<?}elseif($otziv['ocenka']>='1' and $otziv['ocenka']<'2'){?>
            <span class="rating1" title=""></span>
			<?}elseif($otziv['ocenka']>='2' and $otziv['ocenka']<'3'){?>
            <span class="rating2" title=""></span>
			<?}elseif($otziv['ocenka']>='3' and $otziv['ocenka']<'4'){?>
            <span class="rating3" title=""></span>
			<?}elseif($otziv['ocenka']>='4' and $otziv['ocenka']<'4,5'){?>
            <span class="rating4" title=""></span>
			<?}elseif($otziv['ocenka']>='4,5' and $otziv['ocenka']<='5'){?>
            <span class="rating5" title=""></span>
			<?}?>
	  
       
		
        <? echo smile($otziv['coment']);?>           
      </span>
    </td>
  </tr>
 
  <?
}
}else{
echo '<span class="msg-warning">Отзывов еще небыло!</span>';
}
?>
</table> 
  </div>
</div>
<?
}elseif($func == "add_izb_task") {
$partnerid_sql = mysql_fetch_array(mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$username."'"));
$partnerid=$partnerid_sql['id'];

$sql_zv = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
if(mysql_num_rows($sql_zv)>0) {
	$row_sv = mysql_fetch_array($sql_zv);
	$rek_id = $row_sv['user_id'];
	//echo $rek_id;
	
	$sql_f = mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `user_id`='$partnerid' AND `rek_id`='$rid'");
	//echo mysql_num_rows($sql_f);
	if (mysql_num_rows($sql_f) > 0) {
		mysql_query("UPDATE `tb_ads_task_fav` SET `type`='favorite' WHERE `user_id`='$partnerid' AND `rek_id`='$rid'") or die(mysql_error());
		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание добавлено в избранные!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{

		mysql_query("INSERT INTO `tb_ads_task_fav` (`type`,`user_id`,`rek_id`,`date_add`,`ip`) VALUES ('favorite','$partnerid','$rid','".time()."','$ip')");
		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание добавлено в избранные!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
	}

}else{
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Такого задания нету!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
}

}elseif($func == "del_izb_task") {
$partnerid_sql = mysql_fetch_array(mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$username."'"));
$partnerid=$partnerid_sql['id'];

$sql_zv = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
if(mysql_num_rows($sql_zv)>0) {
	$row_sv = mysql_fetch_array($sql_zv);
	$rek_id = $row_sv["user_id"];

	$sql_f = mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='favorite' AND `user_id`='$partnerid' AND `rek_id`='$rid'");
	if (mysql_num_rows($sql_f) > 0) {

		mysql_query("DELETE FROM `tb_ads_task_fav` WHERE `type`='favorite' AND `user_id`='$partnerid' AND `rek_id`='$rid'") or die(mysql_error());
		
		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание удалено с избранных!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Что то пошло не так!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
}
}else{
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Такого задания нету!</span>';
	    exit(my_json_encode($ajax_json, $result_text, $message_text));
}

 
}elseif($func == "stata-task") {
?>
<link rel="stylesheet" type="text/css" href="/style/task.css" />
<?
////////////////////////////////////
//$mode=$_POST['mode'];
$mode = ( isset($_POST["mode"]) && preg_match("|^[a-zA-Z0-9\-_]{2,20}$|", htmlspecialchars(trim($_POST["mode"]))) ) ? htmlspecialchars(trim($_POST["mode"])) : false;
if($mode=='good'){
//////////////////////////////////////////////////////
$good_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='good' or `status`='good_auto') && `ident`='".$id_z."' ORDER BY `id` DESC");
$good_num = mysql_num_rows($good_sql);
if($good_num>0){
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
while ($good = mysql_fetch_array($good_sql)) {
$date_start = date("Y-m-d H:i:s" , $good['date_start']); 
$date_end = date("Y-m-d H:i:s" , $good['date_end']); 
$good_s = mysql_fetch_array(mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$good['user_name']."'"));
?>
<div class="new-task-stata" id="zaiavca-<?=$id_z;?>">
    <div class="user-otchet">
      <b class="line-b">Исполнитель</b> <a href="/wall?uid=<?=$good_s['id'];?>" target="_blank" title="Стена пользователя <?=$good['user_name'];?>"><b><?=$good['user_name'];?></b></a>   
         </div>
		<div class="nach-ip"><b class="line-b">Начало выполнения</b> <?=$date_start;?> <b class="line-b1">IP</b> <?=$good['ip'];?></div>
		<div class="closed-ip"><b class="line-b">Отчет подан</b> <?=$date_end;?> <b class="line-b1">IP</b> <?=$good['ip'];?></div>
		<div class="text-otchet">
      <b>Отчёт исполнителя:</b><br> <? echo  $good['ctext'];?> 
    </div>
  </div>  
  <?
}
}else{
echo '<span class="msg-warning">Задание еще не выполняли!</span>';
}
echo '</div>';
//////////////////////////////////////////////////////
}elseif($mode=='wait'){
//////////////////////////////////////////////////////
$wait_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='wait' or `status`='dorab') &&`ident`='".$id_z."' ORDER BY `id` DESC");
$wait_num = mysql_num_rows($wait_sql);
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
if($wait_num>0){
while ($wait = mysql_fetch_array($wait_sql)) {
$date_start = date("Y-m-d H:i:s" , $wait['date_start']); 
$date_end = date("Y-m-d H:i:s" , $wait['date_end']); 
$wait_s = mysql_fetch_array(mysql_query("SELECT id, username FROM `tb_users` WHERE `username`='".$wait['user_name']."'"));
?>
<div class="new-task-stata" id="zaiavca-<?=$id_z;?>">
    <div class="user-otchet">
      <b class="line-b">Исполнитель</b> <a href="/wall?uid=<?=$wait_s['id'];?>" target="_blank" title="Стена пользователя <?=$wait['user_name'];?>"><b><?=$wait['user_name'];?></b></a>   
         </div>
		<div class="nach-ip"><b class="line-b">Начало выполнения</b> <?=$date_start;?> <b class="line-b1">IP</b> <?=$wait['ip'];?></div>
		<?if($wait['ctext']!=''){?>
		<div class="closed-ip"><b class="line-b">Отчет подан</b> <?=$date_end;?> <b class="line-b1">IP</b> <?=$wait['ip'];?></div>
		<div class="text-otchet">
      <b>Отчёт исполнителя:</b><br> <? echo $wait['ctext'];?>    
	  <?}else{?>
	  <div class="closed-ip"><b class="line-b">Отчет еще не подан.</b></div>
		<div class="text-otchet">
	  <?}?>
    </div>
  </div>
  <?
}
}else{
echo '<span class="msg-warning">На даный момент нету пользователей в этом списке!</span>';
}
echo '</div>';
/////////////////////////////////////////////////////////
}elseif($mode=='bad'){
//////////////////////////////////////////////////////
$bad_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='bad' &&`ident`='".$id_z."' ORDER BY `id` DESC");
$bad_num = mysql_num_rows($bad_sql);
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
if($bad_num>0){
while ($bad = mysql_fetch_array($bad_sql)) {
$date_start = date("Y-m-d H:i:s" , $bad['date_start']); 
$date_end = date("Y-m-d H:i:s" , $bad['date_end']); 
$bad_s = mysql_fetch_array(mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$bad['user_name']."'"));
?>
<div class="new-task-stata" id="zaiavca-<?=$id_z;?>">
    <div class="user-otchet">
      <b class="line-b">Исполнитель</b> <a href="/wall?uid=<?=$bad_s['id'];?>" target="_blank" title="Стена пользователя <?=$bad['user_name'];?>"><b><?=$bad['user_name'];?></b></a>   
         </div>
		<div class="nach-ip"><b class="line-b">Начало выполнения</b> <?=$date_start;?> <b class="line-b1">IP</b> <?=$bad['ip'];?></div>
		<div class="closed-ip"><b class="line-b">Отчет подан</b> <?=$date_end;?> <b class="line-b1">IP</b> <?=$bad['ip'];?></div>
		<div class="text-otchet">
      <b>Отчёт исполнителя:</b><br> <? echo $bad['ctext'];?> 
    </div>
  </div> 
  <?
}
}else{
echo '<span class="msg-warning">Нету отклоненных отчетов!</span>';
}
echo '</div>';
/////////////////////////////////////////////////////////
}elseif($mode=='otziv'){
/////////////////////////////////////////////////////////
function smile($mes) {
				for($i=0; $i<=37; $i++) {
					$mes = str_ireplace("<br><br>", "<br>", $mes);
					$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-".$i.".gif\" alt=\"\" align=\"middle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
				}
				return $mes;
			}
			
$otziv_sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `coment`!='' &&`ident`='".$id_z."'");
$otziv_num = mysql_num_rows($otziv_sql);
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
if($otziv_num>0){
while ($otziv = mysql_fetch_array($otziv_sql)) {
$otziv_s = mysql_fetch_array(mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$otziv['user_name']."'"));
?>
<div class="new-task-stata" id="zaiavca-<?=$id_z;?>">
    <div class="user-otchet">
      <b class="line-b">Исполнитель:</b> <a href="/wall?uid=<?=$otziv_s['id'];?>" target="_blank" title="Стена пользователя <?=$otziv['user_name'];?>"><b><?=$otziv['user_name'];?></b></a>   
         </div>
		<div class="text-otchet">
      <b>Отзыв исполнителя:</b><br> <? echo smile($otziv['coment']);?> 
    </div>
  </div> 
  <?
}
}else{
echo '<span class="msg-warning">Отзывов еще небыло!</span>';
}
echo '</div>';
/////////////////////////////////////////////////////////
}elseif($mode=='pay'){
/////////////////////////////////////////////////////////
$pay_sql = mysql_fetch_array(mysql_query("SELECT `totals` FROM `tb_ads_task` WHERE `id`='".$id_z."'"));
echo '<span class="msg-ok">Это задание еще может выполнить <b>'.$pay_sql['totals'].'</b> чел. Что бы увеличить это количество, пополните баланс этого задания.</span>';
/////////////////////////////////////////////////////////
}elseif($mode=='stat'){
/////////////////////////////////////////////////////////
$date_s = DATE("d.m.Y");
$date_v = DATE("d.m.Y", (time()-24*60*60));

echo '<span class="msg-ok"><b>Подробная статистика по оплачиваемому заданию #'.$rid.':</b></span>';

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$sql_vs = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='view' AND `ident`='$rid' AND `date`='$date_s'");
	$views_s = mysql_num_rows($sql_vs);
	$sql_vv = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='view' AND `ident`='$rid' AND `date`='$date_v'");
	$views_v = mysql_num_rows($sql_vv);

	$sql_cs = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='click' AND `ident`='$rid' AND `date`='$date_s'");
	$clicks_s = mysql_num_rows($sql_cs);
	$sql_cv = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='click' AND `ident`='$rid' AND `date`='$date_v'");
	$clicks_v = mysql_num_rows($sql_cv);

	if($views_s>0) {$ctr_s = (($clicks_s/$views_s) * 100);}else{$ctr_s=0;}
	if($views_v>0) {$ctr_v = (($clicks_v/$views_v) * 100);}else{$ctr_v=0;}
	if($row["views"]>0) {$ctr = (($row["clicks"]/$row["views"]) * 100);}else{$ctr=0;}
echo '<table class="tables">';
		echo '<thead><tr align="center"><th align="center" colspan="2" class="top">Статистика по заданию</th></tr></thead>';
		echo '<tr>';
			echo '<td align="center" width="200">';
				echo '<table>';
					echo '<tr><td align="left" width="170" style="border:none;"><b>Выполнено и оплачено:</b></td><td align="right" style="border:none;"><b style="color:#54b948;">'.$row["goods"].'</b></td></tr>';
					echo '<tr><td align="left" width="170" style="border:none;"><b>Отказано в оплате:</b></td><td align="right" style="border:none;"><b style="color:#c24c2c;">'.$row["bads"].'</b></td></tr>';
					echo '<tr><td align="left" width="170" style="border:none;"><b>Непроверенных заявок:</b></td><td align="right" style="border:none;"><b style="color:#AAAAAA;">'.$row["wait"].'</b></td></tr>';
				echo '</table>';
			echo '</td>';
			echo '<td align="center">';
				echo '<table class="tables">';
					echo '<thead><tr align="center"><th align="center" class="top"></th><th align="center" class="top">просмотров</th><th align="center" class="top">кликов</th><th align="center" class="top">CTR</th></tr></thead>';
					echo '<tr><td align="left">Сегодня:</td><td align="center">'.$views_s.'</td><td align="center">'.$clicks_s.'</td><td align="center">'.round($ctr_s, 2).'%</td></tr>';
					echo '<tr><td align="left">Вчера:</td><td align="center">'.$views_v.'</td><td align="center">'.$clicks_v.'</td><td align="center">'.round($ctr_v, 2).'%</td></tr>';
					echo '<tr><td align="left">Всего:</td><td align="center">'.$row["views"].'</td><td align="center">'.$row["clicks"].'</td><td align="center">'.round($ctr, 2).'%</td></tr>';
				echo '</table>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
	}else{
	echo '<span class="msg-warning">Ошибка! У Вас нет такого задания!</span>';
}

/////////////////////////////////////////////////////////
}elseif($mode=='izbr'){
/////////////////////////////////////////////////////////
$izbr_sql = mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='favorite' && `rek_id`='".$id_z."'");
$izbr_num = mysql_num_rows($izbr_sql);
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
if($izbr_num>0){
while ($izbr = mysql_fetch_array($izbr_sql)) {
$izbr_s = mysql_fetch_array(mysql_query("SELECT `username` FROM `tb_users` WHERE `id`='".$izbr['user_id']."'"));
?>
<div class="new-task-stata" id="zaiavca-<?=$id_z;?>">
    <div class="user-otchet">
      <b class="line-b">Пользователь:</b> <a href="/wall?uid=<?=$izbr['user_id'];?>" target="_blank" title="Стена пользователя <?=$izbr_s['username'];?>"><b><?=$izbr_s['username'];?> добавил в избранные ваши задания.</b></a>   
         </div>

  </div> 
  <?
}
}else{
echo '<span class="msg-warning">Ваши задания еще не добавляли в избранные. !</span>';
}
echo '</div>';
/////////////////////////////////////////////////////////
}elseif($mode=='bl'){
/////////////////////////////////////////////////////////
$izbr_sql = mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='BL' && `rek_id`='".$id_z."'");
$izbr_num = mysql_num_rows($izbr_sql);
echo '<div id="history-task-scroll" style="max-height:650px;overflow-y: auto;">';
if($izbr_num>0){
while ($izbr = mysql_fetch_array($izbr_sql)) {
$izbr_s = mysql_fetch_array(mysql_query("SELECT `username` FROM `tb_users` WHERE `id`='".$izbr['user_id']."'"));
?>
<div class="new-task-stata" id="zaiavca-<?=$id_z;?>">
    <div class="user-otchet">
      <b class="line-b">Пользователь:</b> <a href="/wall?uid=<?=$izbr['user_id'];?>" target="_blank" title="Стена пользователя <?=$izbr_s['username'];?>"><b><?=$izbr_s['username'];?> добавил в BL ваши задания.</b></a>   
         </div>

  </div> 
  <?
}
}else{
echo '<span class="msg-warning">Ваши задания еще не добавляли в BL. !</span>';
}
echo '</div>';
/////////////////////////////////////////////////////////
}else{
echo '<span class="msg-error">Ошибка обработки запроса! Попробуйте еще раз!</span>';
}


////////////////////////////////////
}elseif($func == "z_pause") {
////////////////////////////////////
$sql = mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
$us_v = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='wait' or `status`='dorab') &&`ident`='".$rid."'"));
if($us_v==0){
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	mysql_query("UPDATE `tb_ads_task` SET `status`='pause', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
	$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание остановлено!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание остановлено!</span>'));

}else{
    $ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}
}else{
   $ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас есть заявки на подтверждения или задание сейчас выполняют!</span>';
   exit(my_json_encode($ajax_json, $result_text, $message_text));
   //echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас есть заявки на подтверждения или задание сейчас выполняют!</span>'));
}
////////////////////////////////////
}elseif($func == "z_play") {
////////////////////////////////////
$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	if($row["plan"] > 0 && $row["totals"] > 0) {
		mysql_query("UPDATE `tb_ads_task` SET `status`='pay', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание запущено!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание запущено!</span>'));

	}else{
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Необходимо пополнить баланс задания!</span>';
    exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Необходимо пополнить баланс задания!</span>'));
	}
}else{
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
    exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}
////////////////////////////////////
}elseif($func == "z_color") {
////////////////////////////////////
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите выделить цветом‚ площадку ID: <?=$id_z;?>?</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fun($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=color_task&id=<?=$id_z;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?
////////////////////////////////////
}elseif($func == "z_vip") {
////////////////////////////////////
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите выделить знаком VIP и разместить в отдельном блоке‚ площадку ID: <?=$id_z;?>?</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fun($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=vip_task&id=<?=$id_z;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?
////////////////////////////////////
}elseif($func == "z_up") {
////////////////////////////////////
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите поднять в списке‚ площадку ID: <?=$id_z;?>?</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fun($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=up_task&id=<?=$id_z;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?
}elseif($func == "up") {
////////////////////////////////////
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите включить функцию авто-поднятие для площадки ID: <?=$id_z;?>?</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fu($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=up_t&id=<?=$id_z;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>

<?////////////////////////////////////
}elseif($func == "z_vozvrat") {
////////////////////////////////////
$sum=$_POST['sum'];
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите вернуть средства на рекламный баланс <br> (будет зачислено  <?=$sum;?> руб.), задание будет удалено‚ площадка ID: <?=$id_z;?>?</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fun_del($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=vozvrat_task&id=<?=$id_z;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?////////////////////////////////////
}elseif($func == "z_perevod") {
////////////////////////////////////
//$z_popoln=$_POST['z_popoln'];
//$add_plan=$_POST['plan'];
//$id_zz=$_POST['id'];

$z_popoln = ( isset($_POST["z_popoln"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["z_popoln"]))) ) ? intval(trim($_POST["z_popoln"])) : false;
$add_plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["plan"]))) ) ? intval(trim($_POST["plan"])) : false;
$id_zz = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;

if($z_popoln<1){
echo '<span class="msg-warning">Вы не выбрали задание на которое нужно перевести средства!</span>';
exit();
}
$sql_pс = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' and id='".$id_zz."'");
if(mysql_num_rows($sql_pс)<=0){
echo '<span class="msg-warning">Задание с которого вы хочете списать средства не ваше!</span>';exit();
}
$sql_pс_z = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' and id='".$z_popoln."'");
if(mysql_num_rows($sql_pс_z)<=0){
echo '<span class="msg-warning">Задание на которое вы хочете перевести средства не ваше!</span>';exit();
}
if($add_plan<1){
echo '<span class="msg-warning">Минимальное количество пополнения: 1 выполнение!</span>';exit();
}
$z_sn=mysql_fetch_array($sql_pс);
$z_za=mysql_fetch_array($sql_pс_z);
if($z_sn['status']!='pause'){
	echo '<span class="msg-warning">Задание должно быть на паузе!</span>';exit();
}
$bal_zsn=($z_sn['zdprice']+(($z_sn['zdprice']/100)*$nacenka_task))*$z_sn['totals'];
$bal_zz=($z_za['zdprice']+(($z_za['zdprice']/100)*$nacenka_task))*$add_plan;
if($bal_zsn<$bal_zz){
echo '<span class="msg-warning">Недостаточно средств для снятия!</span>';exit();
}

$bal_ost=($bal_zsn-$bal_zz)/($z_sn['zdprice']+(($z_sn['zdprice']/100)*$nacenka_task));
$x_za=($bal_ost-floor($bal_ost))*($z_sn['zdprice']+(($z_sn['zdprice']/100)*$nacenka_task));
//$x_za=$bal_ost;
$ost_z=$x_za-($x_za/100*$nacenka_task);
$sp_c=round($ost_z, 3, PHP_ROUND_HALF_ODD);
$snat=$x_za+$bal_zz;		
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">C задания №<?=$id_zz;?> будет снята сумма в размере <?=round($snat,2);?> руб. Вы уверены что хотите перевести средства на баланс площадки ID: <?=$z_popoln;?> в размере <?=$bal_zz;?> руб. Остальные средства(<?=round($x_za,2);?> руб.) будут зачислены на рекламный баланс за вычетом комисии системы, к зачислению на рекламный баланс <?=$sp_c;?> руб.</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fun($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=perevod_task&id=<?=$id_z;?>&plan=<?=$add_plan;?>&z_popoln=<?=$z_popoln;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?
///////////////////////////////////
}elseif($func == "perevod_task") {
//$z_popoln=$_POST['z_popoln'];
//$add_plan=$_POST['plan'];
//$id_zz=$_POST['id'];

$z_popoln = ( isset($_POST["z_popoln"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["z_popoln"]))) ) ? intval(trim($_POST["z_popoln"])) : false;
$add_plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["plan"]))) ) ? intval(trim($_POST["plan"])) : false;
$id_zz = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;

if($z_popoln<1){
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Вы не выбрали задание на которое нужно перевести средства!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}
$sql_pс = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' and id='".$id_zz."'");
if(mysql_num_rows($sql_pс)<=0){
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Задание с которого вы хочете списать средства не ваше!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}
$sql_pс_z = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' and id='".$z_popoln."'");
if(mysql_num_rows($sql_pс_z)<=0){
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Задание на которое вы хочете перевести средства не ваше!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}
if($add_plan<1){
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Минимальное количество пополнения: 1 выполнение!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}
$z_sn=mysql_fetch_array($sql_pс);
$z_za=mysql_fetch_array($sql_pс_z);

if($z_sn['status']!='pause'){
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Задание должно быть на паузе!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$bal_zsn=($z_sn['zdprice']+(($z_sn['zdprice']/100)*$nacenka_task))*$z_sn['totals'];
$bal_zz=($z_za['zdprice']+(($z_za['zdprice']/100)*$nacenka_task))*$add_plan;
if($bal_zsn<$bal_zz){
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-warning">Недостаточно средств для снятия!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$bal_ost=($bal_zsn-$bal_zz)/($z_sn['zdprice']+(($z_sn['zdprice']/100)*$nacenka_task));
$x_za=($bal_ost-floor($bal_ost))*($z_sn['zdprice']+(($z_sn['zdprice']/100)*$nacenka_task));
$ost_z=$x_za-($x_za/100*$nacenka_task);
$sp_c=round($ost_z, 3, PHP_ROUND_HALF_ODD);
$snat=$x_za+$bal_zz;	
//обработка
		mysql_query("UPDATE `tb_ads_task` SET `totals`='".floor($bal_ost)."', `plan`=`plan`-'".floor($bal_ost)."' WHERE `id`='$id_zz' and `username`='$username'") or die(mysql_error());

		mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'$add_plan', `plan`=`plan`+'$add_plan' WHERE `id`='$z_popoln' and `username`='$username'") or die(mysql_error());
		
		mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$sp_c' WHERE `username`='$username'") or die(mysql_error());

		mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
		VALUES('2','$username','0','".DATE("d.m.Y H:i")."','".time()."','$sp_c','Зачисление средств, остаток от перевода с задания [ID:".$id_zz."] на задание [ID:".$z_popoln."]','Зачислено','prihod')") or die(mysql_error());
		
		mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
		VALUES('2','$username','0','".DATE("d.m.Y H:i")."','".time()."','$bal_zz','Зачисление средств, НА ЗАДАНИЕ [ID:".$z_popoln."] в результате перевода с задания [ID:".$id_zz."]','Зачислено','prihod')") or die(mysql_error());

		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание #'.$z_popoln.' успешно пополнено, остаток от перевода переведен на рекламный баланс!</span><META HTTP-EQUIV="REFRESH" CONTENT="2;URL=ads_task.php">';
		exit(my_json_encode($ajax_json, $result_text, $message_text));


////////////////////////////////////
////////////////////////////////////
}elseif($func == "z_money") {
////////////////////////////////////
//$add_plan=$_POST['plan'];
$add_plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["plan"]))) ) ? intval(trim($_POST["plan"])) : false;
$system=$_POST['system'];
if($system=='0'){
$balans_v='рекламного';
}elseif($system=='1'){
$balans_v='основного';
}else{
$balans_v='';
echo '<span class="msg-error">Что-то пошло не так! Поробуйте сначало!</span>';
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=ads_task.php">';
exit();
}
?>
<div class="text-popup" id="js-popup">
    <p style="padding: 10px 20px;text-align: center;">Вы уверены что хотите пополнить баланс площадки ID: <?=$id_z;?> с <?=$balans_v;?> баланса?</p>
    <div style="text-align: center;margin-bottom: 10px;">	  
	<span class="proc-btn-d" onclick="task_fun($('#task-block<?=$id_z;?>'), '../ajax/ajax_task.php', 'func=money_task&id=<?=$id_z;?>&plan=<?=$add_plan;?>&system=<?=$system;?>');return false;">Да</span>  
    <input type="submit" class="proc-btn-n" value="Нет" onclick="closed_popup();return false;">
    </div>     
</div>
<?
////////////////////////////////////
}elseif($func == "vozvrat_task") {
////////////////////////////////////

$sqlq = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sqlq)>0) {
	$row = mysql_fetch_array($sqlq);
	$id = $row["id"];
	$wait = $row["wait"];
	$totals = $row["totals"];
	$zdprice = $row["zdprice"];
	if($row["status"]=="wait" && $row["wait"]<=0 ) {
		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='wait' AND `username`='$username'") or die(mysql_error());
		
		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>'));
		
	}elseif( $row["status"]=="pause" && $row["date_act"] > (time()-1*24*60*60) ) {
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Чтобы удалить задание, его необходимо остановить и подождать 1 день. Если на задание не будет жалоб, то задание можно будет удалить и баланс задания будет переведен на баланс аккаунта.</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Чтобы удалить задание, его необходимо остановить и подождать 1 день. Если на задание не будет жалоб, то задание можно будет удалить и баланс задания будет переведен на баланс аккаунта.</span>'));
	}elseif( $row["status"]=="pay" | $row["date_act"] > (time()-1*24*60*60) ) {
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Чтобы удалить задание, его необходимо остановить и подождать 1 день. Если на задание не будет жалоб, то задание можно будет удалить и баланс задания будет переведен на баланс аккаунта.</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Чтобы удалить задание, его необходимо остановить и подождать 1 день. Если на задание не будет жалоб, то задание можно будет удалить и баланс задания будет переведен на баланс аккаунта.</span>'));
	}elseif( $row["wait"] > 0 ) {
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Чтобы удалить задание необходимо проверить поданные заявки на проверку выполнения задания!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Чтобы удалить задание необходимо проверить поданные заявки на проверку выполнения задания!</span>'));
	}elseif( $row["status"]=="pause" | $row["date_act"] < (time()-1*24*60*60) ) {
		if($row["totals"] > 0 ) {
			$sql_a = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
			$nacenka_task = mysql_result($sql_a,0,0);
			$money_back = ( (100 + $nacenka_task) / 100) * ($totals * $zdprice);
			mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back', `money_rek`=`money_rek`-'$money_back' WHERE `username`='$username'") or die(mysql_error());

			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$money_back','Зачисление средств, удаление задания [ID:".$id."]','Зачислено','prihod')") or die(mysql_error());
			
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>'));
		}else{
			mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
			
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>'));
		}
	}elseif( $row["totals"] <= 0 ) {
		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
		
		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>'));
	}elseif( $row["status"]=="pause" && $row["totals"] > 0 ) {
		$sql_a = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
		$nacenka_task = mysql_result($sql_a,0,0);
		$money_back = ( (100 + $nacenka_task) / 100) * ($totals * $zdprice);
		
		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
		mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back', `money_rek`=`money_rek`-'$money_back' WHERE `username`='$username'") or die(mysql_error());

		mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
		VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$money_back','Зачисление средств, удаление задания [ID:".$id."]','$Зачислено','prihod')") or die(mysql_error());

		$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание #'.$id.' успешно удалено!</span>'));
	}else{
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Баланс этого задания пуст!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
    //echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Баланс этого задания пуст!</span>'));
	}
}else{ 
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}

/*$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Услуга временно приостановлена! Попробуйте позже!</span>'; 
	//exit(my_json_encode($ajax_json, $result_text, $message_text));*/
////////////////////////////////////
}elseif($func == "vip_task") { 
////////////////////////////////////

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
$sql_b = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'"));
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	if($row["vip"] < (time() - 1*60*60) ) {
		if($sql_b['money_rb'] >= $up_vip_task_pr) {
			mysql_query("UPDATE `tb_ads_task` SET `vip`='".time()."', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$up_vip_task_pr' WHERE `username`='$username'") or die(mysql_error());

			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$up_vip_task_pr','Поднятие задания в VIP-блок [ID:".$id."]','Списано','rashod')") or die(mysql_error());
			
			#### ИНВЕСТ ПРОЕКТ ###
			stat_pay("task_vip", $up_vip_task_pr);
			invest_stat($up_vip_task_pr, 4);
			#### ИНВЕСТ ПРОЕКТ ###
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание размещено в VIP блоке!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание размещено в VIP блоке!</span>'));
			
		}else{
		
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">На Вашем рекламном счете не хватает средств для размещения задания в VIP блоке.</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">На Вашем рекламном счете не хватает средств для размещения задания в VIP блоке.</span>'));
		}
	}else{
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Размещать задание в VIP блоке можно только 1 раз в час.</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Размещать задание в VIP блоке можно только 1 раз в час.</span>'));
	}
}else{

	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}
////////////////////////////////////
}elseif($func == "up_task") {
////////////////////////////////////
$sql = mysql_query("SELECT `id`,`date_up` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
$sql_b = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'"));
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	if($row["date_up"] < (time() - 100) ) {
		if($sql_b['money_rb'] >= $up_task_pr) {
			mysql_query("UPDATE `tb_ads_task` SET `date_up`='".time()."', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$up_task_pr' WHERE `username`='$username'") or die(mysql_error());
			#### ИНВЕСТ ПРОЕКТ ###
			stat_pay("task_up", $up_task_pr);
			invest_stat($up_task_pr, 4);
			#### ИНВЕСТ ПРОЕКТ ###
			
			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$up_task_pr','Поднятие задания в списке [ID:".$id."]','Списано','rashod')") or die(mysql_error());
			
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание поднято в списке!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание поднято в списке!</span>'));
		}else{
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! На Вашем рекламном счету недостаточно средств для поднятия задания в списке! Стоимость одного поднятия составляет - '.$up_task_pr.' руб.</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! На Вашем рекламном счету недостаточно средств для поднятия задания в списке! Стоимость одного поднятия составляет - '.$up_task_pr.' руб.</span>'));
		}
	}else{
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Поднятие задания возможно 1 раз в 100 секунд. Стоимость одного поднятия '.$up_task_pr.' руб.</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Поднятие задания возможно 1 раз в секунду. Стоимость одного поднятия '.$up_task_pr.' руб.</span>'));
	}
}else{
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}

}elseif($func == "up_t") {
	
	$up = (isset($_POST["up"]) && (intval($_POST["up"])==600 | intval($_POST["up"])==1200 | intval($_POST["up"])==1800 | intval($_POST["up"])==3600 | intval($_POST["up"])==7200 | intval($_POST["up"])==10800 | intval($_POST["up"])==21600 | intval($_POST["up"])==43200 | intval($_POST["up"])==86400 )) ? intval($_POST["up"]) : "8";
////////////////////////////////////
////////////////////////////////////
$sql = mysql_query("SELECT `id`,`up` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
//$sql_b = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'"));
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	//if($row["date_up"] < (time() - 100) ) {
		//if($sql_b['money_rb'] >= $up_task_pr) {
			mysql_query("UPDATE `tb_ads_task` SET `up`='$up' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());
			/*mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$up_task_pr' WHERE `username`='$username'") or die(mysql_error());
			#### ИНВЕСТ ПРОЕКТ ###
			stat_pay("task_up", $up_task_pr);
			invest_stat($up_task_pr, 4);
			#### ИНВЕСТ ПРОЕКТ ###
			
			//mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			//VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$up_task_pr','Поднятие задания в списке [ID:".$id."]','Списано','rashod')") or die(mysql_error());
			*/
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Функция авто-поднятия задания в списке активирована!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание авто-поднято в списке активирована!</span>'));
		/*}else{
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! На Вашем рекламном счету недостаточно средств для поднятия задания в списке! Стоимость одного поднятия составляет - '.$up_task_pr.' руб.</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! На Вашем рекламном счету недостаточно средств для поднятия задания в списке! Стоимость одного поднятия составляет - '.$up_task_pr.' руб.</span>'));
		}
	}else{
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Поднятие задания возможно 1 раз в 100 секунд. Стоимость одного поднятия '.$up_task_pr.' руб.</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Поднятие задания возможно 1 раз в секунду. Стоимость одного поднятия '.$up_task_pr.' руб.</span>'));
	}*/
}else{
	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}
////////////////////////////////////
}elseif($func == "money_task") {
////////////////////////////////////
//$add_plan=$_POST['plan'];
$add_plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["plan"]))) ) ? intval(trim($_POST["plan"])) : false;
$system=$_POST['system'];
if($add_plan>0){
if($system=='0'){
/////////
$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$id = $row["id"];
		$zdprice = $row["zdprice"];
			if($add_plan >= $limit_min_task) {
				$sql = mysql_query("SELECT `money_rb` FROM `tb_users` WHERE `username`='$username'");
				$row = mysql_fetch_row($sql);
				$money_users = $row["0"];
				$add_balans = ($add_plan * $zdprice * (100 + $nacenka_task) / 100);
				if($money_users >= $add_balans) {
					mysql_query("UPDATE `tb_ads_task` SET `status`='pay', `plan`=`plan`+'$add_plan', `totals`=`totals`+'$add_plan' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$add_balans', `money_rek`=`money_rek`+'$add_balans' WHERE `username`='$username'") or die(mysql_error());

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$add_balans','Пополнение баланса задания [ID:".$id."]','Списано','rashod')") or die(mysql_error());
					
					$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Баланс задания успешно пополнен!<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=ads_task.php"></span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
					//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Баланс задания успешно пополнен!<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=ads_task.php"></span>'));
				}else{
				
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Недостаточно средств на рекламном балансе аккаунта, не хватает '.number_format(($add_balans-$money_users),2,"."," ").' руб.!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
					//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Недостаточно средств на рекламном балансе аккаунта, не хватает '.number_format(($add_balans-$money_users),2,"."," ").' руб.!</span>'));
				}
			}else{
			
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Минимальное количество заданий для пополнения должно быть не менее '.$limit_min_task.' шт.</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
				//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Минимальное количество заданий для пополнения должно быть не менее '.$limit_min_task.' шт.</span>'));
			}
	}else{
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
	}
/////////
}elseif($system=='1'){
/////////
$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$id = $row["id"];
		$zdprice = $row["zdprice"];
			if($add_plan >= $limit_min_task) {
				$sql = mysql_query("SELECT `money` FROM `tb_users` WHERE `username`='$username'");
				$row = mysql_fetch_row($sql);
				$money_users = $row["0"];
				$add_balans = ($add_plan * $zdprice * (100 + $nacenka_task) / 100);
				if($money_users >= $add_balans) {
					mysql_query("UPDATE `tb_ads_task` SET `status`='pay', `plan`=`plan`+'$add_plan', `totals`=`totals`+'$add_plan' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
					
					mysql_query("UPDATE `tb_users` SET `money`=`money`-'$add_balans', `money_rek`=`money_rek`+'$add_balans' WHERE `username`='$username'") or die(mysql_error());

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$add_balans','Пополнение баланса задания [ID:".$id."]','Списано','rashod')") or die(mysql_error());
					
					$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Баланс задания успешно пополнен!</span><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=ads_task.php">';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
					//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Баланс задания успешно пополнен!</span><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=ads_task.php">'));					
				}else{
				
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Недостаточно средств на рекламном балансе аккаунта, не хватает '.number_format(($add_balans-$money_users),2,"."," ").' руб.!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
					//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Недостаточно средств на рекламном балансе аккаунта, не хватает '.number_format(($add_balans-$money_users),2,"."," ").' руб.!</span>'));
				}
			}else{
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Минимальное количество заданий для пополнения должно быть не менее '.$limit_min_task.' шт.</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
				//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Минимальное количество заданий для пополнения должно быть не менее '.$limit_min_task.' шт.</span>'));
			}
	}else{
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
	}
/////////
}else{
echo '<span class="msg-error">Что-то пошло не так! Поробуйте сначало!</span>';
}
}else{
$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Минимально 1  выполнение!</span>';
exit(my_json_encode($ajax_json, $result_text, $message_text));
//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Минимально 1  выполнение!</span>'));
}
////////////////////////////////////
}elseif($func == "color_task") {
////////////////////////////////////
$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
$sql_b = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'"));
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	if($row["time_color"] < (time() - 24*60*60) ) {
		if($sql_b['money_rb'] >= $color_price) {
			mysql_query("UPDATE `tb_ads_task` SET `time_color`='".time()."' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());	
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$color_price' WHERE `username`='$username'") or die(mysql_error());
			
			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$username','0','".DATE("d.m.Y H:i")."','".time()."','$color_price','Выделение цветом задания [ID:".$id."]','Списано','rashod')") or die(mysql_error());
			
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Задание выделено цветом!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Задание выделено цветом!</span>'));
		}else{
		
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">На Вашем рекламном счете не хватает средств для выделение задания цветом синим цветом.</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">На Вашем рекламном счете не хватает средств для выделение задания цветом синим цветом.</span>'));
		}
	}else{
	
		$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Выделять задание можно только 1 раз в 24 часа.</span>';
		exit(my_json_encode($ajax_json, $result_text, $message_text));
		//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Выделять задание можно только 1 раз в 24 часа.</span>'));
	}
}else{

	$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>'));
}

///////////////////////////////////////////
}elseif($func == "otch_task") {
///////////////////////////////////////////
//$id_otchet=$_POST['id_otchet'];
$id_otchet = ( isset($_POST["id_otchet"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id_otchet"]))) ) ? intval(trim($_POST["id_otchet"])) : false;
$status_t=$_POST['status_t'];

$sqlp = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
	if(mysql_num_rows($sqlp)>0) {
		$row = mysql_fetch_assoc($sqlp);
		$zdre = $row["zdre"];
		$rek_name = $row["username"];
		$zdprice = $row["zdprice"];
		$country_targ = $row["country_targ"];

$sql_w = mysql_query("SELECT `user_name` FROM `tb_ads_task_pay` WHERE `id`='$id_otchet' AND `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid'");
			if(mysql_num_rows($sql_w)>0) {
				$row_w = mysql_fetch_assoc($sql_w);
				$user_name = $row_w["user_name"];
/////////////////
if($status_t==1) {
/////////////////   
					$status = "good"; $text_bad=""; $ban_user="0";
					
					$user_sql=mysql_fetch_array(mysql_query("SELECT `wmid` FROM `tb_users` WHERE `username`='$username'"));
					$wmiduser=$user_sql['wmid'];
					
					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
					$reit_task = mysql_result($sql,0,0);

					mysql_query("UPDATE `tb_ads_task` SET `goods`=`goods`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task', `visits_t`=`visits_t`+1, `money`=`money`+'$zdprice', `money_t`=`money_t`+'$zdprice' WHERE `username`='$user_name'") or die(mysql_error());
					//mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task' WHERE `username`='$username'") or die(mysql_error());

					#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
					$cnt_unique_rep = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `user_name`='$user_name' AND `ident`='$rid'"));
					if($cnt_unique_rep==0) mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`+'1' WHERE `username`='$user_name'") or die(mysql_error());
					#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###

					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
					VALUES('0', 'good', '$rid', '$username', '$user_name', '".time()."', '')") or die(mysql_error());
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					
					#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###
					$cnt_konk = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `rek_name`='$rek_name' AND `user_name`='$user_name' AND `date_end`>='".(time()-24*60*60)."'"));
					#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###


					$sql_r = mysql_query("SELECT `referer`,`referer2`,`referer3`,`ban_date` FROM `tb_users` WHERE `username`='$user_name'");
					$row_r = mysql_fetch_assoc($sql_r);

					$my_referer_1 = $row_r["referer"];
					$my_referer_2 = $row_r["referer2"];
					$my_referer_3 = $row_r["referer3"];
					$user_ban_date = $row_r["ban_date"];

					$add_money_r_1 = 0;
					$add_money_r_2 = 0;
					$add_money_r_3 = 0;

					/*#### ИНВЕСТ ПРОЕКТ ###
					stat_pay("task_pay", $zdprice);
					invest_stat($zdprice, 2);
					#### ИНВЕСТ ПРОЕКТ ###*/

					### START СБОР СТАТИСТИКИ ####
					$DAY_S = strtolower(DATE("D"));
					stats_users($user_name, $DAY_S, 'task');
					### END СБОР СТАТИСТИКИ ######

					#### АДМИН-КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
					konkurs_ads_new($wmiduser, $username, $zdprice);
					#### АДМИН-КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
					
					/*#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###
					konkurs_ads_task_new($wmiduser, $username, $zdprice);
					#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###*/
					
					#### NEW КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###
					$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
					$konk_task_status = mysql_result($sql,0,0);

					if($konk_task_status==1 && $cnt_konk<=3) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
						$konk_task_date_start = mysql_result($sql,0,0);

						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
						$konk_task_date_end = mysql_result($sql,0,0);

						if($konk_task_date_end>=time() && $konk_task_date_start<time()) {
							mysql_query("UPDATE `tb_users` SET `konkurs_task`=`konkurs_task`+'1' WHERE `username`='$user_name'") or die(mysql_error());
						}
					}
					#### NEW КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###
					
					### КОМПЛЕКСНЫЙ NEW ###
					if($country_targ == 0 && strtolower($rek_name) != strtolower($user_name) && $user_ban_date == 0) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
						$konk_complex_status = mysql_result($sql,0,0);

						if($konk_complex_status==1 && $cnt_konk<=3) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
							$konk_complex_date_start = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
							$konk_complex_date_end = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".($zdre==0 ? "task" : "task_re")."'") or die(mysql_error());
							$konk_complex_point = mysql_result($sql,0,0);

							if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
								mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$user_name'") or die(mysql_error());
							}
						}
					}
					### КОМПЛЕКСНЫЙ NEW ###
					

								if($my_referer_1!=false) {
					$sql_r_1 = mysql_query("SELECT `id`,`money_rb`,`reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
					$row_r_1 = mysql_fetch_array($sql_r_1);
					$user_id_ref_1 = $row_r_1["id"];
					$money_rb_ref_1 = $row_r_1["money_rb"];
					$reiting_1 = $row_r_1["reiting"];

						$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_1' AND `r_do`>='".floor($reiting_1)."'");
						if(mysql_num_rows($sql_rang)>0) {
							$row_rang = mysql_fetch_array($sql_rang);
						}else{
							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
							$row_rang = mysql_fetch_array($sql_rang);
						}
						$ref_proc_task_1 = $row_rang["t_1"];

						$add_money_r_1 = p_floor( ($zdprice * $ref_proc_task_1 / 100), 6);

						mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$user_name'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");
							konkurs_clic_ref($my_referer_1, $add_money_r_1);
					 konkurs_best_ref($user_name, $add_money_r_1);
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

						### РЕФ КОНКУРС I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
								}
							}
						}
						### РЕФ КОНКУРС I ур. ###

						### РЕФ КОНКУРС I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
								}
							}
						}
						### РЕФ КОНКУРС I ур. ###
						
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					if($country_targ=="0") {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='6' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							//if($row_t["limit_kon_sum"]!=0 && ($my_visits+$my_visits_bs+$add_money_r_1)==$row_t["limit_kon_sum"]) {
							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
				//}
					### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###

						if($my_referer_2!="") {
							$sql_r_2 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
							$row_r_2 = mysql_fetch_array($sql_r_2);
							$reiting_2 = $row_r_2["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_2' AND `r_do`>='".floor($reiting_2)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_array($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_array($sql_rang);
							}
							$ref_proc_task_2 = $row_rang["t_2"];

							$add_money_r_2 = p_floor( ($zdprice * $ref_proc_task_2 / 100), 6);

							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_2' AND `type`='ref2'");
							konkurs_clic_ref($my_referer_2, $add_money_r_2);
					 konkurs_best_ref($user_name, $add_money_r_2);
	                       if(mysql_num_rows($sql_stat_re)>0) {
	                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `type`='ref2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }else{
					           mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_2','ref2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				         }

							### РЕФ КОНКУРС II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
									}
								}
							}
							### РЕФ КОНКУРС II ур. ###

							### РЕФ КОНКУРС II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
									}
								}
							}
							### РЕФ КОНКУРС II ур. ###
							
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_2)==$row_t["limit_kon_sum"]) {
								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_2' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
					//}
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###

							if($my_referer_3!="") {
								$sql_r_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
								$row_r_3 = mysql_fetch_array($sql_r_3);
								$reiting_3 = $row_r_3["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_3' AND `r_do`>='$reiting_3'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$ref_proc_task_3 = $row_rang["t_3"];

								$add_money_r_3 = p_floor( ($zdprice * $ref_proc_task_3 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());
								$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_3' AND `type`='ref3'");
								konkurs_clic_ref($my_referer_3, $add_money_r_3);
					 konkurs_best_ref($user_name, $add_money_r_3);
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `type`='ref3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_3','ref3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }

								### РЕФ КОНКУРС III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### РЕФ КОНКУРС III ур. ###

								### РЕФ КОНКУРС III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### РЕФ КОНКУРС III ур. ###
								### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_3' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
					}
				}
			}
								
								### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
					if($my_referer_1 != false) {
						$sql_r_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
						if(mysql_num_rows($sql_r_1)>0) {
							$row_r_1 = mysql_fetch_assoc($sql_r_1);
							$user_id_ref_1 = $row_r_1["id"];
							$money_ref_1 = $row_r_1["money_rb"];

							$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='4' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_r_b_1)>0) {
								$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

								$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(mysql_error());
								$comis_sys_bon = mysql_result($sql_b,0,0);

								$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='4' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_r_b_stat_1)>0) {
									$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

									if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
										$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
										$money_ureferera_nado = round($money_ureferera_nado, 2);

										if($money_ref_1>=$money_ureferera_nado) {
											mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

											mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(mysql_error());
											mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$user_name','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." выполненных заданий','Зачислено','rashod')") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$my_referer_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $user_name за ".$row_r_b_1["count_nado"]." выполненных заданий','Списано','rashod')") or die(mysql_error());

											if(trim($row_r_b_1["description"])!=false) {
												mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
												VALUES('$user_name','Система','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." кликов в серфинге','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
											}
										}else{
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
										}
									}else{
										mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
									}
								}else{
									mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
									VALUES('-1','$user_name','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(mysql_error());
								}
							}
						//}
					//}
					### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
					
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='6' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='6' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if((number_format($row_r_b_stat_1["stat_info"],2,".","`"))==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Зачислено','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $user_name за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$user_name','Система','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$user_name','$add_money_r_1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
				}
			}

					$sql_rek = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$username'");
					$row_rek = mysql_fetch_row($sql_rek);

					$referer_rek = $row_rek["0"];

					if($referer_rek!="") {
						$sql_cr = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_rek' AND `howmany`='1'");
						$ref_proc_rek = mysql_result($sql_cr,0,0);

						$add_balans_rek = round(($zdprice * $ref_proc_rek / 100), 6);

						mysql_query("UPDATE `tb_users` SET `money`=`money`+'$add_balans_rek', `ref_money_rek`=`ref_money_rek`+'$add_balans_rek' WHERE `username`='$referer_rek'") or die(mysql_error());
					}

						//$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет одобрено!</span>';
						//exit(my_json_encode($ajax_json, $result_text, $message_text));
						//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Отчет одобрено!</span>'));

						$time_dorab=time();
						mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_otchet' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
						
						$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет одобрено!</span>';
						exit(my_json_encode($ajax_json, $result_text, $message_text));

/////////////////
}elseif($status_t==2) {
/////////////////
	//$commenttext= iconv("UTF-8", "CP1251", $_POST['commenttext']);
	$commenttext = (isset($_POST["commenttext"])) ? limpiarezz($_POST["commenttext"]) : false;

	if($commenttext!=''){
	$status = "dorab"; $text_bad=$commenttext; $ban_user="0";
	mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
	
	### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
	mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
	VALUES('0', 'dorab', '$rid', '$username', '$user_name', '".time()."', '$text_bad')") or die(mysql_error());
	### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
	
	$time_dorab=time();
	mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_otchet' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
	
	$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет отправлен на доработку!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));
	//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Отчет отправлен на доработку!</span>'));
		}else{
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Вы не указали что нужно доработать!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Вы не указали что нужно доработать!</span>'));
		}		
//////////////////
	}elseif($status_t==3) {
	    //$commenttext= iconv("UTF-8", "CP1251", $_POST['commenttext']);
		$commenttext = (isset($_POST["commenttext"])) ? limpiarezz($_POST["commenttext"]) : false;
		$status = "bad"; $text_bad=$commenttext;
		$ban_user="0";
		mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `bads`=`bads`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());

		#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
		mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`-'1' WHERE `username`='$user_name'") or die(mysql_error());
		#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###

		### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
		mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
		VALUES('0', 'bad', '$rid', '$username', '$user_name', '".time()."', '$text_bad')") or die(mysql_error());
		### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
		
		$time_dorab=time();
		mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_otchet' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
		
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет отклонен!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Отчет отклонен!</span>'));
	
	}else{
	
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Неверно указано действие!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Неверно указано действие!</span>'));
	}
			}else{
			
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Это не Ваш отчет, поэтому проверять его не Вам!!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
				//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Это не Ваш отчет, поэтому проверять его не Вам!!</span>'));
			} 
				}else{
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Необходимо пополнить баланс задания!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
					//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка! Необходимо пополнить баланс задания!</span>'));
				}

///////////////////////////////////////////
}elseif($func == "otchet-task") {
///////////////////////////////////////////
?>
<link rel="stylesheet" type="text/css" href="/style/task.css" />
<?

$sql_otchet=mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' and `ident`='".$id_z."'");
$otchet_num = mysql_num_rows($sql_otchet);
//echo $otchet_num;
if($otchet_num >0){
while ($otchet = mysql_fetch_array($sql_otchet)) {

$otchet_s = mysql_fetch_array(mysql_query("SELECT `id`,`username`,`avatar`,`reiting` FROM `tb_users` WHERE `username`='".$otchet['user_name']."'"));
$date_start = date("Y-m-d H:i:s" , $otchet['date_start']); 
$date_end = date("Y-m-d H:i:s" , $otchet['date_end']); 


$goog_us =mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='good_auto' or `status`='good') && `user_name`='".$otchet['user_name']."'"));
$bad_us = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='bad' && `user_name`='".$otchet['user_name']."'"));
$wait_us = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' && `user_name`='".$otchet['user_name']."'"));
$dorab_us=mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_notif` WHERE `type`='dorab' && `user_name`='".$otchet['user_name']."'"));

?>
<div id='otchet=<?=$id_z;?>'>
 <table class="job-note" id="zaiavca-<?=$otchet['id'];?>" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
	  <td style="text-align: center; width:80px;">
	    <a href="/wall?uid=<?=$otchet_s['id'];?>" target="_blank" title="Стена исполнителя">
		  <img class="avatar_wall" border="0" src="../avatar/<?=$otchet_s['avatar'];?>" align="absmiddle" width="60" height="60">
		</a>
	  </td>
	  <td>
		Исполнитель:  <a href="/wall?uid=<?=$otchet_s['id'];?>" target="_blank" title="Cтена" style="border-bottom: 1px dotted #006699;"><b><?=$otchet['user_name'];?></b></a>
		&nbsp;(ID:<?=$otchet_s['id'];?>)&nbsp;&nbsp;
		
		<span class="status">
		  <font color="green" title="Выполнено заданий" style="cursor: help;"><?=$goog_us;?></font> -
		  <font color="gray" title="Отправлено на доработку" style="cursor: help;"><?=$dorab_us;?></font> -
		  <font color="C80000" title="Отказано в оплате" style="cursor: help;"><?=$bad_us;?></font>  -
		  <font color="blue" title="Сейчас на проверке" style="cursor: help;"><?=$wait_us;?></font>
		</span>&nbsp;

		<img src="../images/otyn/rating16.png" border="0" alt="" align="absmiddle" title="Рейтинг исполнителя <?=$otchet_s['reiting'];?>" style="margin:0; padding:0;"> <?=$otchet_s['reiting'];?><br>
	    Начало выполнения: <b> <?=$date_start;?></b><br>
		Дата подачи отчёта: <b> <?=$date_end;?></b><br>
		<!--<span class="taskcut" title="До автоматического одобрения осталось 167 часов">167</span>-->
	  </td>
	</tr>
	<tr>
	  <td colspan="2" style="padding:0 5px 0px 5px;">
	    <span class="job-reply">
	      <div style="width: 500px;  word-wrap: break-word;">  
	          <span class="ip_reply" title="IP адрес исполнителя, на начало выполнения задания"><?=$otchet['ip'];?>&nbsp;&nbsp;&nbsp;&nbsp;<small>ip начала выполнения</small></span>
	        
          
            <span class="ip_reply" title="IP адрес исполнителя с которого был подан очет"><?=$otchet['ip'];?>&nbsp;&nbsp;&nbsp;&nbsp;<small>ip подачи отчёта</small></span>
          
            <? echo $otchet['ctext'];?>
		  </div>
	    </span>
	  </td>
    </tr>
    <tr>
      <td align="right" colspan="2" style="padding:0 5px 0 5px;">
        <div class="job-reply-btns">
          
          <div id="btns<?=$id_z;?><?=$otchet['user_name'];?>">
             <input name="odob_task[]" type="checkbox" value="<?=$otchet['id'];?>" style="float:right;">
            
              <a class="fbtn-blue160" title="Отправить на доработку задание" style="cursor: pointer;" id="blue<?=$id_z;?><?=$otchet['user_name'];?>" onclick="funcjs['dorblockactivate']('<?=$id_z;?><?=$otchet['user_name'];?>');"><font color="#ffffff">Отправить на доработку</font></a>
            
            <a class="fbtn-red" title="Отклонить поданную заявку" style="cursor: pointer;" id="red<?=$id_z;?><?=$otchet['user_name'];?>" onclick="funcjs['delblockactivate']('<?=$id_z;?><?=$otchet['user_name'];?>');"><font color="#ffffff">Отклонить</font></a>
			
			
			
			<form method="post" action="../ajax/ajax_task.php" class="aj-form" data-infa="true,<?=$otchet['id'];?>,">
              <input type="hidden" name="func" value="otch_task">
              <input type="hidden" name="id" value="<?=$id_z;?>">
              <input type="hidden" name="id_otchet" value="<?=$otchet['id'];?>">
              <input type="hidden" name="status_t" value="1">
              <input class="fbtn-green" type="submit" value="Принять">
            </form>
			
			
			
		  </div>
		  

		  <div id="delcomment<?=$id_z;?><?=$otchet['user_name'];?>" style="display: none;">
		    Укажите причину отказа: (осталось <span id="limit-del-<?=$otchet['id'];?>">100</span> символов)<br>
			<form method="post" action="../ajax/ajax_task.php" class="aj-form" data-infa="true,<?=$otchet['id'];?>,">
			  <center>
			    <textarea class="commenttext" name="commenttext" id="otcaz-text-<?=$otchet['id'];?>" style="width: 98%; height: 100px; display: block; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#otcaz-text-<?=$otchet['id'];?>', 100, '#limit-del-<?=$otchet['id'];?>');"></textarea>
			  </center><br>
              <input type="hidden" name="func" value="otch_task">
              <input type="hidden" name="id_otchet" value="<?=$otchet['id'];?>">
              <input type="hidden" name="id" value="<?=$id_z;?>">
              <input type="hidden" name="status_t" value="3">
			  <input class="fbtn-red" type="submit" value="Отклонить" style="margin-top:-10px;">
			</form>

		  </div>

		  
			  <div id="dorabotka<?=$id_z;?><?=$otchet['user_name'];?>" style="display: none;">
			    Укажите что именно необходимо доработать: (осталось <span id="limit-dor-<?=$otchet['id'];?>">300</span> символов)<br>
				<form method="post" action="../ajax/ajax_task.php" class="aj-form" data-infa="true,<?=$otchet['id'];?>,">
				  <center>
				    <textarea name="commenttext" id="dor-text-<?=$otchet['id'];?>" style="width: 98%; height: 100px; display: block; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#dor-text-<?=$otchet['id'];?>', 300, '#limit-dor-<?=$otchet['id'];?>');"></textarea> 
				  </center>
				  К времени отведенному исполнителю на выполнение задания, добавится 24 часа.
	              <input type="hidden" name="func" value="otch_task">
	              <input type="hidden" name="id_otchet" value="<?=$otchet['id'];?>">
	              <input type="hidden" name="id" value="<?=$id_z;?>">
	              <input type="hidden" name="status_t" value="2">
				  <input class="fbtn-blue160" type="submit" value="Отправить на доработку">
				</form>
			  </div>
	    </div>
	    
	  </td>
	</tr>  
	<tr>
      <td class="taskmsg_ot" colspan="3" style="display: none; ">
	  </td>
    </tr>
	<tr>
      <td class="taskmsg_otchet" colspan="3" style="display: none; ">
	  </td>
    </tr>
	
  </tbody>
  </table>
  </div>
<?

}
?>
<script>
      $(document).ready(function(){
        $('input[name="odob_task[]"]').unbind().click(function(){
    	  var arr = $('input[name="odob_task[]"]'), n = 0;
          $('.odob-task').html('');
          for(i=0; i<arr.length; i++) {
            if(arr[i].checked){
              $('.odob-task').append('<input name="new_p_t[]" type="hidden" value="'+arr[i].value+'">');
              n++;
            }
          }
          if(n > 0){
            $('.menu-arr').html('Снять все отметки').data('c','0');
            $('#tabl-dop').removeAttr("style");
          }else{
            $('.menu-arr').html('Отметить все отчеты').data('c','1');
            $('#tabl-dop').css('display','none');
          }
        });

        $('.menu-arr').unbind().click(function(){
          var arr = $('input[name="odob_task[]"]'), status = $(this).data('c');

          status = (status == 1)? false : true ;
          for(i=0; i<arr.length; i++) { arr[i].checked = status; }

          $('input[name="odob_task[]"]').click();
        });

      });

    </script>
<span class="menu-arr" data-c="1">Отметить все отчеты</span>
<table class="job-note" id="tabl-dop" border="0" cellpadding="0" cellspacing="0" width="100%" style="display:none;">
    <tbody>
	  <tr>
	    <td style="text-align: center;">
		  <h3>Комплексная обработка выделенных отчетов</h3>
		  
		  
		  <form method="post" style="display:inline-block;" action="/ajax/ajax_task.php" class="aj-form_mass" data-infa="true,<?=$id_z;?>,">
            <input type="hidden" name="func" value="odob-arr">
            <span class="odob-task" style="display:none;"></span>
            <input type="hidden" name="id" value="<?=$id_z;?>">
			<input type="hidden" name="status_t" value="1">
            <input class="fbtn-green" type="submit" style="float:none;display:inline-block;" value="Принять">
          </form>
		  
		  
		  
          <a title="Отклонить поданную заявку" class="fbtn-red" style="cursor: pointer;float:none;display:inline-block;" onclick="show_window('#delcommentarr');return false;"><font color="#ffffff">Отклонить</font></a>
          <a class="fbtn-blue160" style="float:none;display:inline-block;" title="Отправить на доработку задание" onclick="show_window('#dorabotkaarr');return false;"><font color="#ffffff">Отправить на доработку</font></a>

          <div id="delcommentarr" style="display: none;">Укажите причину отказа: (осталось <span id="limit-delarr<?=$id_z;?>">300</span> символов)<br>
		    <form method="post" action="/ajax/ajax_task.php" class="aj-form_mass" data-infa="true,<?=$id_z;?>,">
			  <center>
			    <textarea name="commenttext" id="otcazarr-text-<?=$id_z;?>" style="width: 98%; height: 100px; display: block; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#otcazarr-text-<?=$id_z;?>', 300, '#limit-delarr<?=$id_z;?>');"></textarea>
			  </center><br>
			  <input type="hidden" name="func" value="odob-arr">
              <span class="odob-task" style="display:none;"></span>
              <input type="hidden" name="id" value="<?=$id_z;?>">
              <input type="hidden" name="status_t" value="3">
			  <input class="fbtn-red" type="submit" value="Отклонить" style="margin-top:-10px;">
			</form>
		  </div>

		  <div id="dorabotkaarr" style="display: none;">Укажите что именно необходимо доработать: (осталось <span id="limit-dorarr<?=$id_z;?>">300</span> символов)<br>
		    <form method="post" action="/ajax/ajax_task.php" class="aj-form_mass" data-infa="true,<?=$id_z;?>,">
			  <center>
			    <textarea name="commenttext" id="dorarr-text-<?=$id_z;?>" style="width: 98%; height: 100px; display: block; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#dorarr-text-<?=$id_z;?>', 300, '#limit-dorarr<?=$id_z;?>');"></textarea>
			  </center>
			  К времени отведенному исполнителю на выполнение задания, добавится 24 часа.
			  <input type="hidden" name="func" value="odob-arr">
              <span class="odob-task" style="display:none;"></span>
              <input type="hidden" name="id" value="<?=$id_z;?>">
              <input type="hidden" name="status_t" value="2">
			  <input class="fbtn-blue160" type="submit" value="Отправить на доработку">
			</form>
		  </div>
        </td>
	  </tr><tr>
    </tr></tbody>
    </table>
<?
}else{
echo '<span class="msg-warning">Отчетов еще не было!</span>';
}
///////////////////////////////////////////
}elseif($func == "odob-arr") {
 
//}elseif($func == "odob-arr1") {  

//$id_zavd=$_POST['id'];
$id_zavd = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;
$status_t=$_POST['status_t'];
$mass_z=$_POST['new_p_t'];

$sqlp = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
	if(mysql_num_rows($sqlp)>0) {
		$row = mysql_fetch_assoc($sqlp);
		$zdre = $row["zdre"];
		$rek_name = $row["username"];
		$zdprice = $row["zdprice"];
		$country_targ = $row["country_targ"];

	if($status_t==1) {
	
			for($i = 0; $i < count($mass_z); ++$i) {
				$id_otchet=$mass_z[$i]; 	
				
	$sql_w = mysql_query("SELECT `user_name` FROM `tb_ads_task_pay` WHERE `id`='$id_otchet' AND `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid'");
			if(mysql_num_rows($sql_w)>0) {
				$row_w = mysql_fetch_assoc($sql_w);
				$user_name = $row_w["user_name"];
				
	$status = "good"; $text_bad=""; $ban_user="0";
					
					$user_sql=mysql_fetch_array(mysql_query("SELECT `wmid` FROM `tb_users` WHERE `username`='$username'"));
					$wmiduser=$user_sql['wmid'];
					
					$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
					$reit_task = mysql_result($sql,0,0);

					mysql_query("UPDATE `tb_ads_task` SET `goods`=`goods`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task', `visits_t`=`visits_t`+1, `money`=`money`+'$zdprice', `money_t`=`money_t`+'$zdprice' WHERE `username`='$user_name'") or die(mysql_error());
					//mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task' WHERE `username`='$username'") or die(mysql_error());

					#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
					$cnt_unique_rep = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `user_name`='$user_name' AND `ident`='$rid'"));
					if($cnt_unique_rep==0) mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`+'1' WHERE `username`='$user_name'") or die(mysql_error());
					#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###

					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
					VALUES('0', 'good', '$rid', '$username', '$user_name', '".time()."', '')") or die(mysql_error());
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####

					$sql_r = mysql_query("SELECT `referer`,`referer2`,`referer3`,`ban_date` FROM `tb_users` WHERE `username`='$user_name'");
					$row_r = mysql_fetch_assoc($sql_r);

					$my_referer_1 = $row_r["referer"];
					$my_referer_2 = $row_r["referer2"];
					$my_referer_3 = $row_r["referer3"];
					$user_ban_date = $row_r["ban_date"];

					$add_money_r_1=0; $add_money_r_2=0; $add_money_r_3=0;

					/*#### ИНВЕСТ ПРОЕКТ ###
					stat_pay("task_pay", $zdprice);
					invest_stat($zdprice, 2);
					#### ИНВЕСТ ПРОЕКТ ###*/

					### START СБОР СТАТИСТИКИ ####
					$DAY_S = strtolower(DATE("D"));
					stats_users($user_name, $DAY_S, 'task');
					### END СБОР СТАТИСТИКИ ######

					#### АДМИН-КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
					konkurs_ads_new($wmiduser, $username, $zdprice);
					#### АДМИН-КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###

					/*#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###
					konkurs_ads_task_new($wmiduser, $username, $zdprice);
					#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###*/
					
					#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###
					$cnt_konk = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `rek_name`='$rek_name' AND `user_name`='$user_name' AND `date_end`>='".(time()-24*60*60)."'"));
					#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###

					#### АДМИН-КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###
					$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
					$konk_task_status = mysql_result($sql,0,0);

					if($konk_task_status==1 && $cnt_konk<=3) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
						$konk_task_date_start = mysql_result($sql,0,0);

						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
						$konk_task_date_end = mysql_result($sql,0,0);

						if($konk_task_date_end>=time() && $konk_task_date_start<time()) {
							mysql_query("UPDATE `tb_users` SET `konkurs_task`=`konkurs_task`+'1' WHERE `username`='$user_name'") or die(mysql_error());
						}
							
					}
					#### АДМИН-КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###

					### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###
					if($country_targ == 0 && strtolower($rek_name) != strtolower($user_name) && $user_ban_date == 0) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
						$konk_complex_status = mysql_result($sql,0,0);

						if($konk_complex_status==1 && $cnt_konk<=3) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
							$konk_complex_date_start = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
							$konk_complex_date_end = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".($zdre==0 ? "task" : "task_re")."'") or die(mysql_error());
							$konk_complex_point = mysql_result($sql,0,0);

							if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
							
								$time_syt=strtotime('today');
				$time_syt_pl1=strtotime('today')+24*60*60;
				$sql_syt = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$user_name' and `rek_name`='$rek_name' and `date_end`>'$time_syt' and `date_end`<'$time_syt_pl1'"));
					if($sql_syt<5){				
					mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$user_name'") or die(mysql_error());	
					}
							
							
							/*	mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$user_name'") or die(mysql_error());*/
							}
						}
					}
					### АДМИН-КОНКУРС КОМПЛЕКСНЫЙ NEW ###


					if($my_referer_1 != false) {
						$sql_r_1 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
						$row_r_1 = mysql_fetch_assoc($sql_r_1);
						$reiting_1 = $row_r_1["reiting"];

						$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_1' AND `r_do`>='".floor($reiting_1)."'");
						if(mysql_num_rows($sql_rang)>0) {
							$row_rang = mysql_fetch_assoc($sql_rang);
						}else{
							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
							$row_rang = mysql_fetch_assoc($sql_rang);
						}
						$ref_proc_task_1 = $row_rang["t_1"];

						$add_money_r_1 = p_floor( ($zdprice * $ref_proc_task_1 / 100), 6);

						mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$user_name'") or die(mysql_error());

						### Статистика дохода от рефералов I ур. ###
						$sql_stat = mysql_query("SELECT `id` FROM `tb_ref_stat` WHERE `user_name`='$my_referer_1' AND `ref_level`='1'") or die(mysql_error());
						if(mysql_num_rows($sql_stat)>0) {
							mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `user_name`='$my_referer_1' AND `ref_level`='1'") or die(mysql_error());
						}else{
							mysql_query("INSERT INTO `tb_ref_stat` (`user_name`,`ref_level`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) 
							VALUES('$my_referer_1','1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(mysql_error());
						}
						### Статистика дохода от рефералов I ур. ###

						### РЕФКОНКУРС ЗАДАНИЙ I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
								}
							}
						}
						### РЕФКОНКУРС ЗАДАНИЙ I ур. ###

						### РЕФКОНКУРС КОМПЛЕКСНЫЙ I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
								}
							}
						}
						### РЕФКОНКУРС КОМПЛЕКСНЫЙ I ур. ###

						### РЕФ-КОНКУРС ПО ЗАРАБОТАННОЙ СУММЕ I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='5' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `cnt_sum`=`cnt_sum`+'$add_money_r_1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`cnt_sum`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_1')") or die(mysql_error());
								}
							}
						}
						### РЕФ-КОНКУРС ПО ЗАРАБОТАННОЙ СУММЕ I ур. ###

						if($my_referer_2!="") {
							$sql_r_2 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
							$row_r_2 = mysql_fetch_assoc($sql_r_2);
							$reiting_2 = $row_r_2["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_2' AND `r_do`>='".floor($reiting_2)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_assoc($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_assoc($sql_rang);
							}
							$ref_proc_task_2 = $row_rang["t_2"];

							$add_money_r_2 = p_floor( ($zdprice * $ref_proc_task_2 / 100), 6);

							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_2', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());

							### Статистика дохода от рефералов II ур. ###
							$sql_stat = mysql_query("SELECT `id` FROM `tb_ref_stat` WHERE `user_name`='$my_referer_2' AND `ref_level`='2'") or die(mysql_error());
							if(mysql_num_rows($sql_stat)>0) {
								mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `user_name`='$my_referer_2' AND `ref_level`='2'") or die(mysql_error());
							}else{
								mysql_query("INSERT INTO `tb_ref_stat` (`user_name`,`ref_level`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) 
								VALUES('$my_referer_2','2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(mysql_error());
							}
							### Статистика дохода от рефералов II ур. ###

							### РЕФКОНКУРС ЗАДАНИЙ II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
									}
								}
							}
							### РЕФКОНКУРС ЗАДАНИЙ II ур. ###

							### РЕФКОНКУРС КОМПЛЕКСНЫЙ II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
									}
								}
							}
							### РЕФКОНКУРС КОМПЛЕКСНЫЙ II ур. ###

							### РЕФ-КОНКУРС ПО ЗАРАБОТАННОЙ СУММЕ II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='5' AND `glubina`='1' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `cnt_sum`=`cnt_sum`+'$add_money_r_2' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`cnt_sum`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_2')") or die(mysql_error());
									}
								}
							}
							### РЕФ-КОНКУРС ПО ЗАРАБОТАННОЙ СУММЕ II ур. ###

							if($my_referer_3!="") {
								$sql_r_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
								$row_r_3 = mysql_fetch_assoc($sql_r_3);
								$reiting_3 = $row_r_3["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_3' AND `r_do`>='$reiting_3'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_assoc($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_assoc($sql_rang);
								}
								$ref_proc_task_3 = $row_rang["t_3"];

								$add_money_r_3 = p_floor( ($zdprice * $ref_proc_task_3 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_3', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());

								### Статистика дохода от рефералов III ур. ###
								$sql_stat = mysql_query("SELECT `id` FROM `tb_ref_stat` WHERE `user_name`='$my_referer_3' AND `ref_level`='3'") or die(mysql_error());
								if(mysql_num_rows($sql_stat)>0) {
									mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `user_name`='$my_referer_3' AND `ref_level`='3'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_ref_stat` (`user_name`,`ref_level`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) 
									VALUES('$my_referer_3','3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(mysql_error());
								}
								### Статистика дохода от рефералов III ур. ###

								### РЕФКОНКУРС ЗАДАНИЙ III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_assoc($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### РЕФКОНКУРС ЗАДАНИЙ III ур. ###

								### РЕФКОНКУРС КОМПЛЕКСНЫЙ III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_assoc($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### РЕФКОНКУРС КОМПЛЕКСНЫЙ III ур. ###

								### РЕФ-КОНКУРС ПО ЗАРАБОТАННОЙ СУММЕ III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='5' AND `glubina`='1' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_assoc($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `cnt_sum`=`cnt_sum`+'$add_money_r_3' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`cnt_sum`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_3')") or die(mysql_error());
										}
									}
								}
								### РЕФ-КОНКУРС ПО ЗАРАБОТАННОЙ СУММЕ III ур. ###
							}
						}
					}

					### АДМИН КОНКУРС: ЛУЧШИЙ РЕФЕРЕР ###
					if($my_referer_1 != false && $user_ban_date == 0) {
						$sql = mysql_query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ref_sum' AND `item`='status' AND `price`='1'") or die(mysql_error());
						if(mysql_num_rows($sql)>0) {
							$sql_start = mysql_query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ref_sum' AND `item`='date_start' AND `price`<='".time()."'") or die(mysql_error());
							$sql_end = mysql_query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='ref_sum' AND `item`='date_end' AND `price`>='".time()."'") or die(mysql_error());

							if(mysql_num_rows($sql_start) > 0 && mysql_num_rows($sql_end) > 0) {
								mysql_query("UPDATE `tb_users` SET `konkurs_ref_sum`=`konkurs_ref_sum`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `ban_date`='0'") or die(mysql_error());

								if($my_referer_2 != false) {
									mysql_query("UPDATE `tb_users` SET `konkurs_ref_sum`=`konkurs_ref_sum`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `ban_date`='0'") or die(mysql_error());

									if($my_referer_3 != false) {
										mysql_query("UPDATE `tb_users` SET `konkurs_ref_sum`=`konkurs_ref_sum`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `ban_date`='0'") or die(mysql_error());
									}
								}
							}
						}
					}
					### АДМИН КОНКУРС: ЛУЧШИЙ РЕФЕРЕР ###

					### АДМИН КОНКУРС: ЛУЧШИЙ РЕФЕРАЛ ###
					if($my_referer_1 != false && $user_ban_date == 0) {
						$sql = mysql_query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='status' AND `price`='1'") or die(mysql_error());
						if(mysql_num_rows($sql)>0) {
							$sql_start = mysql_query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_start' AND `price`<='".time()."'") or die(mysql_error());
							$sql_end = mysql_query("SELECT `id` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_end' AND `price`>='".time()."'") or die(mysql_error());

							if(mysql_num_rows($sql_start) > 0 && mysql_num_rows($sql_end) > 0) {
								$add_money_r_sum = $add_money_r_1;
								if($my_referer_2 != false) {
									$add_money_r_sum = ($add_money_r_sum + $add_money_r_2);
									if($my_referer_3 != false) {
										$add_money_r_sum = ($add_money_r_sum + $add_money_r_3);
									}
								}
								mysql_query("UPDATE `tb_users` SET `konkurs_best_ref`=`konkurs_best_ref`+'$add_money_r_sum' WHERE `username`='$user_name'") or die(mysql_error());
							}
						}
					}
					### АДМИН КОНКУРС: ЛУЧШИЙ РЕФЕРАЛ ###

					### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
					if($my_referer_1 != false) {
						$sql_r_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
						if(mysql_num_rows($sql_r_1)>0) {
							$row_r_1 = mysql_fetch_assoc($sql_r_1);
							$user_id_ref_1 = $row_r_1["id"];
							$money_ref_1 = $row_r_1["money_rb"];

							$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='4' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_r_b_1)>0) {
								$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

								$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(mysql_error());
								$comis_sys_bon = mysql_result($sql_b,0,0);

								$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='4' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_r_b_stat_1)>0) {
									$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

									if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
										$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
										$money_ureferera_nado = round($money_ureferera_nado, 2);

										if($money_ref_1>=$money_ureferera_nado) {
											mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

											mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(mysql_error());
											mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());
											
											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$user_name','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." выполненных заданий','Зачислено','rashod')") or die(mysql_error());
											
											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$my_referer_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $user_name за ".$row_r_b_1["count_nado"]." выполненных заданий','Списано','rashod')") or die(mysql_error());

											if(trim($row_r_b_1["description"])!=false) {
											
												mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
												VALUES('$user_name','Система','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." кликов в серфинге','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
											}
										}else{
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
										}
									}else{
										mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
									}
								}else{
									mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
									VALUES('-1','$user_name','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(mysql_error());
								}
							}
						}
					}
					### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###

					$sql_rek = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$username'");
					$row_rek = mysql_fetch_row($sql_rek);

					$referer_rek = $row_rek["0"];

					if($referer_rek!="") {
						$sql_cr = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_rek' AND `howmany`='1'");
						$ref_proc_rek = mysql_result($sql_cr,0,0);

						$add_balans_rek = round(($zdprice * $ref_proc_rek / 100), 6);

						mysql_query("UPDATE `tb_users` SET `money`=`money`+'$add_balans_rek', `ref_money_rek`=`ref_money_rek`+'$add_balans_rek' WHERE `username`='$referer_rek'") or die(mysql_error());
					}

						//$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет одобрено!</span>';
						//exit(my_json_encode($ajax_json, $result_text, $message_text));
						//echo json_encode(array('status'=> 'ok', 'html'=> '<span class="msg-ok">Отчет одобрено!</span>'));

						$time_dorab=time();
						mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_otchet' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());

					}
					/*else{
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Это не Ваш отчет, поэтому проверять его не Вам!!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			} 	*/
						
			}
			
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет одобрено!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			
	}elseif($status_t==2) {

//$commenttext= iconv("UTF-8", "CP1251", $_POST['commenttext']);
$commenttext = (isset($_POST["commenttext"])) ? limpiarezz($_POST["commenttext"]) : false;
	if($commenttext!=''){
		
	for($i = 0; $i < count($mass_z); ++$i) {
    $id_otchet=$mass_z[$i]; 
	
	$sql_w = mysql_query("SELECT `user_name` FROM `tb_ads_task_pay` WHERE `id`='$id_otchet' AND `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid'");
			if(mysql_num_rows($sql_w)>0) {
				$row_w = mysql_fetch_assoc($sql_w);
				$user_name = $row_w["user_name"];
	
	$status = "dorab"; $text_bad=$commenttext; $ban_user="0";
	mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
	
	### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
	mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
	VALUES('0', 'dorab', '$rid', '$username', '$user_name', '".time()."', '$text_bad')") or die(mysql_error());
	### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
	
	$time_dorab=time();
	mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_otchet' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
	
	}
	/*else{
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Это не Ваш отчет, поэтому проверять его не Вам!!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			} */
	
	}
	$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет отправлен на доработку!</span>';
	exit(my_json_encode($ajax_json, $result_text, $message_text));

	
	
	
		}else{
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Вы не указали что нужно доработать!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));

		}	

	}elseif($status_t==3) {
	
for($i = 0; $i < count($mass_z); $i++) {
    $id_otchet=$mass_z[$i]; 
	//echo $id_otchet;

$sql_w = mysql_query("SELECT `user_name` FROM `tb_ads_task_pay` WHERE `id`='$id_otchet' AND `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid'");
			if(mysql_num_rows($sql_w)>0) {
				$row_w = mysql_fetch_assoc($sql_w);
				$user_name = $row_w["user_name"];
	
	    //$commenttext= iconv("UTF-8", "CP1251", $_POST['commenttext']);
		$commenttext = (isset($_POST["commenttext"])) ? limpiarezz($_POST["commenttext"]) : false;
		$status = "bad"; $text_bad=$commenttext;
		$ban_user="0";
		mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `bads`=`bads`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());

		#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
		mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`-'1' WHERE `username`='$user_name'") or die(mysql_error());
		#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###

		### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
		mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
		VALUES('0', 'bad', '$rid', '$username', '$user_name', '".time()."', '$text_bad')") or die(mysql_error());
		### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
		
		$time_dorab=time();
		mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_otchet' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
		}
		/*else{
				$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Это не Ваш отчет, поэтому проверять его не Вам!!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			} */

		
} 
			$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Отчет отклонен!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
			
			
			
	}else{	
			$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Неверно указано действие!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
			
				}else{
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка! Необходимо пополнить баланс задания!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				} 





}else{
$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Ошибка обработки запроса! Попробуйте еще раз! Перезагрузите страницу!</span>';
exit(my_json_encode($ajax_json, $result_text, $message_text));
//echo json_encode(array('status'=> 'error', 'html'=> '<span class="msg-error">Ошибка обработки запроса! Попробуйте еще раз! Перезагрузите страницу!</span>'));
//echo '<span class="msg-error">Ошибка обработки запроса! Попробуйте еще раз! Перезагрузите страницу!</span>';
}

}else{
	$result_text = "error"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}
?>