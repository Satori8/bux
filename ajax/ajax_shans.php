<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$ajax_json="";

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

function isValidText($mensaje) {
		$mensaje = stripslashes(trim($mensaje));
		$mensaje = strip_tags(trim($mensaje));
		$mensaje = str_replace("<", "", $mensaje);
		$mensaje = str_replace(">", "", $mensaje);
		$mensaje = str_replace(";", "", $mensaje);
		$mensaje = str_replace("'", "", $mensaje);
		$mensaje = str_replace("`", "", $mensaje);
		$mensaje = str_replace('"', "", $mensaje);
		$mensaje = str_replace("$", "", $mensaje);
		$mensaje = str_replace("&", "", $mensaje);
		$mensaje = str_replace("?", "", $mensaje);
		return trim(iconv("UTF-8", "CP1251//TRANSLIT", trim($mensaje)));
}

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
require(ROOT_DIR."/config.php");
include("../merchant/func_mysql.php");
include("../funciones.php");
//$username = $_SESSION["userLog"];
//$ip=$_SERVER['REMOTE_ADDR'];
//$func=$_POST['func'];
//$id_b=$_POST['id'];

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
$ip = getRealIP();
$func = ( isset($_POST["func"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["func"]))) ) ? htmlspecialchars(trim($_POST["func"])) : false;
$id_b = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["id"]))) ) ? intval(trim($_POST["id"])) : false;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_cena' AND `howmany`='1'");
$shans_bonus_cena = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_reit' AND `howmany`='1'");
$shans_bonus_reit = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_sum' AND `howmany`='1'");
$shans_bonus_sum = mysql_result($sql,0,0);

$summa_igr=($shans_bonus_cena*50)/100*$shans_bonus_sum;

$sql_igr=mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='active'");
$shans=mysql_fetch_array($sql_igr);


	if(mysql_num_rows($sql_igr)==0){
	
			mysql_query("INSERT INTO `tb_bonus_shans_igr` (`user_priz`,`time`,`sum`,`status`,`ost`) VALUES('','".time()."','".$summa_igr."','active','50')") or die(mysql_error());
			$sql_igr_ok=mysql_fetch_array(mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='active'"));
		
		for($i=1; $i<=50; $i++){
		
			mysql_query("INSERT INTO `tb_bonus_shans` (`igr_shans`,`username`,`num`,`time`,`status`,`sum`) VALUES('".$sql_igr_ok['id']."','','".$i."','','active','".$shans_bonus_cena."')") or die(mysql_error());
			
		}
	}

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Для доступа к этой странице необходимо авторизоваться!</b>';
	exit();
}

if($func == "buy_save") {
$buy=isValidText($id_b);
	$sql_ig=mysql_query("SELECT * FROM `tb_bonus_shans` WHERE `num`='".$buy."' && `username`='' && `time`='' && `igr_shans`='".$shans['id']."'");
	$row_ig=mysql_fetch_array($sql_ig);
	if(mysql_num_rows($sql_igr)>0 && mysql_num_rows($sql_ig)>0){
		$sql_user_igr=mysql_query("SELECT * FROM `tb_bonus_shans` WHERE username='$username' && `igr_shans`='".$shans['id']."'");
			if(mysql_num_rows($sql_user_igr)<10){
				$sql_user = mysql_query("SELECT `id`,`username`,`avatar`,`money_rb` FROM `tb_users` WHERE `username`='$username'");
						$row_user = mysql_fetch_assoc($sql_user);
						$user_id = $row_user["id"];
						$user_name = $row_user["username"];
						$money_user =  $row_user["money_rb"];

				if($row_ig['sum']<=$money_user) {
				    $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_reit'") or die(mysql_error());
				    $shans_bonus_reit = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);
					
					mysql_query("UPDATE `tb_bonus_shans_igr` SET `ost`=`ost`-'1' WHERE `id`='".$shans['id']."'");
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".$row_ig['sum']."', `reiting`=`reiting`+'".$shans_bonus_reit."' WHERE `username`='$username'");
					mysql_query("UPDATE `tb_bonus_shans` SET `username`='$username', `time`='".time()."' WHERE `num`='".$buy."' && `igr_shans`='".$shans['id']."'");

					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$shans_bonus_cena','Покупка билета №$buy в игре Бонус-Удача','Списано','rashod')") or die(mysql_error());
					
					stat_pay("bonus_pay", $shans_bonus_cena);
					invest_stat($shans_bonus_cena, 8);

					$sql_igrs=mysql_query("SELECT * FROM `tb_bonus_shans_igr` WHERE `status`='active'");
					$shanss=mysql_fetch_array($sql_igrs);
					
					if($shanss['ost']<=0){
					    
					    $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='shans_bonus_reit_igr'") or die(mysql_error());
				        $shans_bonus_reit_igr = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);
				        
						$rand=rand(1, 50);
						$sql_igra_p=mysql_fetch_array(mysql_query("SELECT * FROM `tb_bonus_shans` WHERE `num`='".$rand."' && `igr_shans`='".$shans['id']."'"));
						mysql_query("UPDATE `tb_bonus_shans_igr` SET `status`='over',`user_priz`='".$sql_igra_p['username']."',`time`='".time()."' WHERE `id`='".$shans['id']."'");
						mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$shans['sum']."', `reiting`=`reiting`+'".$shans_bonus_reit_igr."' WHERE `username`='".$sql_igra_p['username']."'");
						mysql_query("UPDATE `tb_bonus_shans` SET `bonus`='ok' WHERE `num`='".$rand."' && `igr_shans`='".$shans['id']."'");
						$sql_us = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='".$sql_igra_p['username']."'");
						$row_us = mysql_fetch_assoc($sql_us);
						$user_idu = $row_us["id"];
						$user_nameu = $row_us["username"];
					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$user_nameu','$user_idu','".DATE("d.m.Y H:i")."','".time()."','".$shans['sum']."','Выиграл билет №$rand в игре Бонус-Удача','Зачислено','prihod')") or die(mysql_error());
					$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Билет №'.$buy.' успешно куплен!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
					
					}
										
					$ajax_json="json"; $result_text = "ok"; $message_text = '<span class="msg-ok">Билет №'.$buy.' успешно куплен!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Недостаточно средств на балансе!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
 
			}else{
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Вы можете купить только 10 билетов  в одной игре!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
			}		
	}else{
					$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Это место занято!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));	
	}

	include('footer.php');
	exit();
}else{
$ajax_json="json"; $result_text = "error"; $message_text = '<span class="msg-error">Перегрузите страницу!</span>';
					exit(my_json_encode($ajax_json, $result_text, $message_text));
}

}else{
	$result_text = "error"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}
?>