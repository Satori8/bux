<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");

$domen = "lilacbux.com";

$sql = mysql_query("SELECT `id`,`username`,`avatar`,`birthday`,email FROM `tb_users` WHERE `birthday`!='1' &&`db_date` LIKE '%".DATE("d.m.", time())."%'");
$all_birthdays = mysql_num_rows($sql);
if($all_birthdays>0) {
		while ($row_db = mysql_fetch_assoc($sql)){
			$db_id = $row_db["id"];
			$db_user_name = $row_db["username"];
			$email_user = $row_db["email"];
			//отправка вп
			mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
			VALUES('$db_user_name','Система','Системное сообщение','[center][b]Администрация поздравляет Вас  с Днем Рождения!<br><br>С днем рожденья поздравляем<br>И желааем день за днем<br>Быть счастливее и ярче,<br>Словно солнце за окном.<br><br>Пожелаем мы здоровья,<br>Много смеха и тепла,<br>Чтоб родные были рядом<br>И, конечно же, добра!<br><br>Пусть деньжат будет побольше,<br>Путешествий и любви.<br>Чашу полную заботы,<br>Мира, света, красоты!<br><br>С уважением Администрация проекта![/b][/center]','0','".time()."','0.0.0.0')") or die(mysql_error());	
			//ставим статус в базе что сегодня отправлено
			mysql_query("UPDATE `tb_users` SET `birthday`='1' WHERE `username`='".$db_user_name."'");
			
			
			
			
			
			$subject = "С днем рождения!";
			$subject = "=?utf-8?B?" . base64_encode(iconv("windows-1251", "utf-8", $subject)) . "?=";
		$headers   = array();
$headers[] = "From: ".strtoupper($domen)." <support@".$domen.">";
$headers[] = "Reply-To: ".strtoupper($domen)." <support@".$domen.">";
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-Type: text/html; charset=\"utf-8\"";
$headers[] = "Content-Transfer-Encoding: base64";
$headers[] = "X-Priority: 3";
$headers[] = "X-Mailer: PHP/".phpversion();
			$message = array();
			$message[] = "<html>";
		$message[] = "<head>";
		$message[] = "<title></title>";
		$message[] = "</head>";
		$message[] = "<body>";
		$message[] = "<table align=\"center\" border=\"0\" cellpadding=\"10\" cellspacing=\"0\" style=\"width:100%; background-color:#E5E5E5;\">";
		$message[] = "<tbody>";
		$message[] = "<tr><td align=\"center\">";
		$message[] = "<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"border:1px solid #DDD; width:100%; background-color:#FFFFFF;\">";
		$message[] = "<tbody>";
		$message[] = "<tr><td align=\"left\" style=\"background:url(http://lilacbux.com/img/logotipik.png) no-repeat bottom left; background-color:#1B3C71; padding:46px;\"></td></tr>";
		$message[] = "<tr><td style=\"background-color:#48A0F7; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;\">Уважаемый ".$db_user_name." поздравсляем вас с днем рождения!</td></tr>";
		//$message[] = "<tr><td align=\"center\" style=\"font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;\">Уважаемый <b>".$db_user_name."</b> Администрация проекта <b style=\"color:#009E58;\">".$_SERVER["HTTP_HOST"]."</b> поздравляет вас сднем рождения!</td></tr>";
		$message[] = "<tr><td align=\"center\" style=\"font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;\"><b>С днем рожденья поздравляем<br>И желааем день за днем<br>Быть счастливее и ярче,<br>Словно солнце за окном.<br><br>Пожелаем мы здоровья,<br>Много смеха и тепла,<br>Чтоб родные были рядом<br>И, конечно же, добра!<br><br>Пусть деньжат будет побольше,<br>Путешествий и любви.<br>Чашу полную заботы,<br>Мира, света, красоты!</b></td></tr>";
		$message[] = "<tr><td align=\"center\" style=\"font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;\">С уважением Администрация проекта <b style=\"color:#009E58;\">".strtoupper($domen)."</b></b></td></tr>";
		$message[] = "<tr><td align=\"left\" style=\"border-top:1px solid #DDD; font-size:12px; padding:10px 20px;\">С уважением, автоматическая служба уведомлений <a href=\"http://".strtoupper($domen)."\" style=\"color:#009E58;\">".strtoupper($domen)."</a></td></tr>";
		$message[] = "</tbody>";
		$message[] = "</table>";
		$message[] = "</td></tr>";
		$message[] = "</tbody>";
		$message[] = "</table>";
		$message[] = "</body>";
		$message[] = "</html>";
			$message = iconv("windows-1251", "utf-8", implode("\r\n", $message));
			mail($email_user, $subject, base64_encode($message), implode("\r\n", $headers));


			
			
			
		}

}else{
	echo 'Дней рождения сегодня нет';
}







$sql = mysql_query("SELECT `id`,`username`,`avatar`,`birthday` FROM `tb_users` WHERE `birthday`!='0' && `db_date` NOT LIKE '%".DATE("d.m.", time())."%'");
$all_birthdays = mysql_num_rows($sql);
if($all_birthdays>0) {
		while ($row_db = mysql_fetch_assoc($sql)){
			$db_id = $row_db["id"];
			$db_user_name = $row_db["username"];
			//ставим статус в базе что бы отправить потом еще раз
			mysql_query("UPDATE `tb_users` SET `birthday`='0' WHERE `username`='".$db_user_name."'");	
		}

}


?>