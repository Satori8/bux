<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../'));
require(ROOT_DIR."/config_mysqli.php");
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

//$mysqli->query("UPDATE `tb_users` SET `notif_sent_time`='0' ORDER BY `id` ASC LIMIT 10000") or die($mysqli->error);
//$mysqli->query("UPDATE `tb_users` SET `notif_sent_time`='0'") or die($mysqli->error);
//exit();

require_once(ROOT_DIR.'/class/email.conf.php');
require_once(ROOT_DIR.'/class/smtp.class.php');

$mail_out = new mailPHP();

if(function_exists('desc_bb')===false) {
	function desc_bb($desc) {
		$desc = new bbcode($desc);
		$desc = $desc->get_html();
		$desc = str_replace("&amp;", "&", $desc);
		return $desc;
	}
}

/*function mess_email($user_name, $user_email, $subject, $message_txt, $headers) {
	$message_txt = str_ireplace("%USER_NAME%", $user_name, $message_txt);
	$message_txt = str_ireplace("%HTTP_HOST%", $_SERVER["HTTP_HOST"], $message_txt);

	$message   = array();
	$message[] = '<html>';
	$message[] = '<head><title></title></head>';
	$message[] = '<body>';
	$message[] = '<table align="center" border="0" cellpadding="6" cellspacing="0" style="width:100%; background-color:#E5E5E5;">';
	$message[] = "<tbody>";
	$message[] = '<tr><td align="center">';
		$message[] = '<table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">';
		$message[] = "<tbody>";
		$message[] = '<tr><td align="left" style="background:url(https://'.$_SERVER["HTTP_HOST"].'/img/logo/logo.gif) no-repeat bottom left; background-size:250px auto; background-color:#dfba86; padding:35px;"></td></tr>';
		$message[] = '<tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Доброго времени суток, уважаемый пользователь проекта <a style="text-decoration:none; color:#FFF;">'.strtoupper($_SERVER["HTTP_HOST"]).'</a></td></tr>';
		$message[] = '<tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">'.$message_txt.'</td></tr>';
		$message[] = '<tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">';
			$message[] = 'С уважением, Администрация сайта '.strtoupper($_SERVER["HTTP_HOST"]).''."<br>";
			$message[] = '<i>Это автоматическое сообщение, отвечать на него не надо.</i>'."<br>";
		$message[] = '</td></tr>';
		$message[] = "</tbody>";
		$message[] = "</table>";
	$message[] = '</td></tr>';
	$message[] = "</tbody>";
	$message[] = "</table>";
	$message[] = "</body>";
	$message[] = "</html>";

	$message = iconv("windows-1251", "utf-8", implode("\r\n", $message));

	if(mail($user_email, $subject, $message, implode("\r\n", $headers))) {
		return "TRUE";
	}else{
		return "FALSE";
	}
}*/

$sql_conf = $mysqli->query("SELECT * FROM `tb_notif_conf` WHERE `id`='1' AND `status`='1'") or die($mysqli->error);
if($sql_conf->num_rows > 0) {
	$row_conf = $sql_conf->fetch_assoc();
	$notif_days_not_active = $row_conf["days_not_active"];
	$notif_days_resending = $row_conf["days_resending"];
	$notif_text_message = desc_bb($row_conf["text_message"]);
	
	$var = array('{TEXT}');
    $zamena = array($notif_text_message);
    $msgtext = str_replace($var, $zamena, $email_temp['rass2']);

	$sql_users = $mysqli->query("SELECT `id`,`username`,`email` FROM `tb_users` WHERE `lastlogdate2`!='0' AND `lastlogdate2`<'".(time()-$notif_days_not_active*24*60*60)."' AND `notif_sent_time`<'".(time()-$notif_days_resending*24*60*60)."' ORDER BY `id` ASC LIMIT 50") or die($mysqli->error);
	if($sql_users->num_rows > 0) {
		echo "Всего: ".$sql_users->num_rows."<br>";
		while ($row_users = $sql_users->fetch_assoc()) {
			$user_id = $row_users["id"];
			$user_name = $row_users["username"];
			$user_email = $row_users["email"];

			echo "$user_id | $user_name | $user_email<br>";

			//mess_email($user_name, $user_email, $subject, $notif_text_message, $headers);
			if(!$mail_error = $mail_out->send($user_email,$user_name,iconv("CP1251", "UTF-8", 'Уведомление с Scorpionbux.info'), iconv("CP1251", "UTF-8", $msgtext))) {

			$mysqli->query("UPDATE `tb_users` SET `notif_sent_time`='".time()."' WHERE `id`='$user_id'") or die($mysqli->error);
			}else{
			}
		}
	}

	$sql_users->free();
}

$sql_conf->free();
$mysqli->close();
?>