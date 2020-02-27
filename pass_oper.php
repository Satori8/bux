<?php
session_start();
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/funciones.php");
require_once(ROOT_DIR."/auto_pay_req/wmxml.inc.php");
error_reporting (E_ALL);

$user_id = ( isset($_SESSION["userID"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["userID"])) ) ? intval(trim($_SESSION["userID"])) : false;
$laip = getRealIP();

function generate_password($number) {
	$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
	$pass = "";
	for($i = 0; $i < $number; $i++) {
		$index = rand(0, count($arr) - 1);
		$pass .= $arr[$index];
	}
	return $pass;
}

$pass_oper = generate_password(mt_rand(7,9));

$sql = mysql_query("SELECT `id`,`username`,`email`,`wmid` FROM `tb_users` WHERE `id`='$user_id'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_assoc($sql);
	$user_id = $row["id"];
	$user_name = $row["username"];
	$user_email = $row["email"];
	$user_wmid =  $row["wmid"];

	$subject_wm = "Пароль для операций на проекте ".strtoupper($_SERVER["HTTP_HOST"])."";
	$message_wm = "Здравствуйте!\n\n";
	$message_wm.= "Вы запросили пароль для операций на проекте http://".$_SERVER["HTTP_HOST"]."/\n";
	$message_wm.= "Запрос был осуществлен с IP адреса: $laip\n\n";
	$message_wm.= "Ваш ID: <b>$user_id</b>\n";
	$message_wm.= "Ваш логин: <b>$user_name</b>\n";
	$message_wm.= "Ваш пароль для операций: <b>$pass_oper</b>\n\n";
	$message_wm.= "<q>Внимание: обязательно запишите или запомните пароль для операций. При заказе нового пароля, старый будет недействителен!</q>\n\n";
	$message_wm.= "---------------------------\n";
	$message_wm.= "С уважением, автоматическая служба уведомлений http://".$_SERVER["HTTP_HOST"]."/";

	$_RES_WM_6 =  (isset($user_wmid) && preg_match("|^[\d]{12}$|", trim($user_wmid)) ) ? _WMXML6($user_wmid, $message_wm, $subject_wm) : "ERROR";

	if($_RES_WM_6!="ERROR" && isset($_RES_WM_6["retval"]) && $_RES_WM_6["retval"]==0) {
		echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" style="font-size:12px;"><tr><td valign="top">';
			echo '<b>Пароль для операций успешно отправлен на Ваш WMID</b><br><br>';
			echo '<b>Обязательно запишите</b> новый пароль для операций! Восстановить пароль для операции невозможно, можно только сгенерить новый пароль.<br><br>';
			echo '<b>Не запрашивайте новый пароль для операций, пока не получите этот!</b> Получение пароля для операций может занять некоторое время, но обычно несколько минут.<br><br>';
			echo '<b>Не сообщайте никому</b> свой пароль для операций, а так же внимательно проверяйте сайт, где указываете пароль для операций. Часто мошенники создают сайты очень похожие на наш сайт, чтобы украсть Ваши данные.';
		echo '</td></tr>';
		echo '</table>';

		mysql_query("UPDATE `tb_users` SET `pass_oper`='$pass_oper' WHERE `id`='$user_id'") or die(mysql_error());
	}else{
		//require_once($_SERVER['DOCUMENT_ROOT'].'/class/email_add.conf.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/class/email.conf.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/class/smtp.class.php');
		
		$var = array('{ID}', '{LOGIN}', '{PIN}', '{EMAIL}', '{IP}');
    $zamena = array($user_id, $user_name, $pass_oper, $user_email, $laip);
    $msgtext = str_replace($var, $zamena, $email_temp["pass_op"]);
		
		$mail_out = new mailPHP();
	
		if(!$mail_error = $mail_out->send($user_email, $user_name, iconv("CP1251", "UTF-8", 'Пароль для операций на проекте '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext))) {
			mysql_query("UPDATE `tb_users` SET `pass_oper`='$pass_oper' WHERE `id`='$user_id'") or die(mysql_error());

			echo '<table width="100%" border="0" cellspacing="1" cellpadding="1" style="font-size:12px;"><tr><td valign="top">';
				echo '<b>Пароль для операций успешно отправлен на Ваш Email</b><br><br>';
				echo '<b>Обязательно запишите</b> новый пароль для операций! Восстановить пароль для операции невозможно, можно только сгенерить новый пароль.<br><br>';
				echo '<b>Не запрашивайте новый пароль для операций, пока не получите этот!</b> Получение пароля для операций может занять некоторое время, но обычно несколько минут.<br><br>';
				echo '<b>Не сообщайте никому</b> свой пароль для операций, а так же внимательно проверяйте сайт, где указываете пароль для операций. Часто мошенники создают сайты очень похожие на наш сайт, чтобы украсть Ваши данные.';
			echo '</td></tr>';
			echo '</table>';
		}else{
			echo '<span class="msg-error">Ошибка! Не удалось отправить данные, повторите попытку позже!</span>';
		}
	}
}else{
	echo '<span class="msg-error">Ошибка! Пользователь не найден!</span>';
}
?>