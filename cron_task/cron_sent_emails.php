<?php
header("Content-type: text/html; charset=UTF-8");
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

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

$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `status`='1' ORDER BY `id` ASC LIMIT 2");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		//$row = mysql_fetch_assoc($sql);
		$id = $row["id"];
		$admin_msg = $row["admin"];
		$subject = $row["subject"];
		//$subject = "=?utf-8?B?".base64_encode(iconv("windows-1251", "utf-8", $subject))."?=";
		//$message_txt =  iconv("windows-1251", "utf-8", $row["message"]);
		$message_txt =  desc_bb($row["message"]);
		$last_id =  $row["last_id"];
		
		$var = array('{TEXT}');
    $zamena = array($message_txt);
    $msgtext = str_replace($var, $zamena, $email_temp['rass']);

		$sql_u = mysql_query("SELECT `id`,`username`,`email`,`email_sent` FROM `tb_users` WHERE `id`>'$last_id' AND `email_sent`='1' ORDER BY `id` ASC LIMIT 100");
		if(mysql_num_rows($sql_u)>0) {
			while ($row_u = mysql_fetch_assoc($sql_u)) {
				$id_u = $row_u["id"];
				$user_sent = $row_u["username"];
				$email_user = $row_u["email"];
				$email_sent = $row_u["email_sent"];

				if(!$mail_error = $mail_out->send($email_user,$user_sent,iconv("CP1251", "UTF-8", $subject), iconv("CP1251", "UTF-8", $msgtext))) {
					mysql_query("UPDATE `tb_ads_emails` SET `count`=`count`+'1', `sent`=`sent`+'1' WHERE `id`='$id'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_emails` SET `nosent`=`nosent`+'1' WHERE `id`='$id'") or die(mysql_error());
				}
			}

			mysql_query("UPDATE `tb_ads_emails` SET `last_id`='$id_u' WHERE `id`='$id'") or die(mysql_error());
		}else{
			mysql_query("UPDATE `tb_ads_emails` SET `status`='3' WHERE `id`='$id'") or die(mysql_error());
		}
	}
}

?>