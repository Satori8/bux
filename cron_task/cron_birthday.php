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
			//�������� ��
			mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
			VALUES('$db_user_name','�������','��������� ���������','[center][b]������������� ����������� ���  � ���� ��������!<br><br>� ���� �������� �����������<br>� ������� ���� �� ����<br>���� ���������� � ����,<br>������ ������ �� �����.<br><br>�������� �� ��������,<br>����� ����� � �����,<br>���� ������ ���� �����<br>�, ������� ��, �����!<br><br>����� ������� ����� ��������,<br>����������� � �����.<br>���� ������ ������,<br>����, �����, �������!<br><br>� ��������� ������������� �������![/b][/center]','0','".time()."','0.0.0.0')") or die(mysql_error());	
			//������ ������ � ���� ��� ������� ����������
			mysql_query("UPDATE `tb_users` SET `birthday`='1' WHERE `username`='".$db_user_name."'");
			
			
			
			
			
			$subject = "� ���� ��������!";
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
		$message[] = "<tr><td style=\"background-color:#48A0F7; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;\">��������� ".$db_user_name." ������������ ��� � ���� ��������!</td></tr>";
		//$message[] = "<tr><td align=\"center\" style=\"font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;\">��������� <b>".$db_user_name."</b> ������������� ������� <b style=\"color:#009E58;\">".$_SERVER["HTTP_HOST"]."</b> ����������� ��� ����� ��������!</td></tr>";
		$message[] = "<tr><td align=\"center\" style=\"font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;\"><b>� ���� �������� �����������<br>� ������� ���� �� ����<br>���� ���������� � ����,<br>������ ������ �� �����.<br><br>�������� �� ��������,<br>����� ����� � �����,<br>���� ������ ���� �����<br>�, ������� ��, �����!<br><br>����� ������� ����� ��������,<br>����������� � �����.<br>���� ������ ������,<br>����, �����, �������!</b></td></tr>";
		$message[] = "<tr><td align=\"center\" style=\"font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;\">� ��������� ������������� ������� <b style=\"color:#009E58;\">".strtoupper($domen)."</b></b></td></tr>";
		$message[] = "<tr><td align=\"left\" style=\"border-top:1px solid #DDD; font-size:12px; padding:10px 20px;\">� ���������, �������������� ������ ����������� <a href=\"http://".strtoupper($domen)."\" style=\"color:#009E58;\">".strtoupper($domen)."</a></td></tr>";
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
	echo '���� �������� ������� ���';
}







$sql = mysql_query("SELECT `id`,`username`,`avatar`,`birthday` FROM `tb_users` WHERE `birthday`!='0' && `db_date` NOT LIKE '%".DATE("d.m.", time())."%'");
$all_birthdays = mysql_num_rows($sql);
if($all_birthdays>0) {
		while ($row_db = mysql_fetch_assoc($sql)){
			$db_id = $row_db["id"];
			$db_user_name = $row_db["username"];
			//������ ������ � ���� ��� �� ��������� ����� ��� ���
			mysql_query("UPDATE `tb_users` SET `birthday`='0' WHERE `username`='".$db_user_name."'");	
		}

}


?>