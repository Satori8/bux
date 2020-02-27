<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Необходимо авторизоваться!</b>';
}else{
	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$uid = (isset($_GET["uid"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["uid"]))) ? intval(limpiar(trim($_GET["uid"]))) : false;
	$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
	$domen = ucfirst($_SERVER["HTTP_HOST"]);

	//echo "$uid $username";

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_reactref' AND `howmany`='1'");
	$cena_mail_reactref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_reactref_wm' AND `howmany`='1'");
	$cena_mail_reactref_wm = mysql_result($sql,0,0);

	if($username==false) {
		echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Пользователь не определен!</b><br>';
	}else{
		$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$username'");
		if(mysql_num_rows($sql)>0) {
			$sql_c = mysql_query("SELECT * FROM `tb_users` WHERE `id`='$uid' AND `referer`='$username'");
			if(mysql_num_rows($sql_c)>0) {
				$row_c = mysql_fetch_array($sql_c);

				$referal = $row_c["username"];
				$lastdate_reactref = $row_c["lastdate_reactref"];

				if($lastdate_reactref>(time()-5*24*60*60)) {
					echo '<b style="color:#FF0000; font-size:12px;">Вы уже отправляли этому рефералу сообщение за последние 5 дней.</b><br>';
				}else{
					echo '<form action="" method="post" onkeypress="if((event.ctrlKey) &amp;&amp; ((event.keyCode == 0xA)||(event.keyCode == 0xD))){this.submit();}">';
					echo '<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td valign="top">';
						echo '<input type="hidden" name="uid" value="'.$uid.'">';
						echo 'Ваш реферал #'.$uid.' <b>'.$referal.'</b> давно не посещал свой аккаунт. Вы можете написать рефералу сообщение на его e-mail адрес и предложить ему вернуться на проект.<br>';
						echo 'Стоимость отправки сообщения: <b>'.$cena_mail_reactref.' руб.</b><br><br>';
						echo '<input type="checkbox" name="mailtowm" value="1"> - <b>Отправить сообщение на WMID</b> (стоимость - '.$cena_mail_reactref_wm.' руб.)<br><br>';
						echo '<b>Сообщение рефералу:</b> (не обязательно)<br>';
						echo '<textarea  cols="" rows="5" name="mess_for_ref" style="width: 94%; background-color: #FBFCCC;"></textarea><br><br>';
						echo '<center><input type="submit" name="submit" value="Разбудить реферала" style="background: #2087DB; color: #FFFFFF; padding: 0px; padding: 3px 10px; font-size: 12px; border: 1px solid #5656E4;"></center>';
					echo '</td></tr></table>';
					echo '</form>';
				}
			}else{
				echo '<b style="color:#FF0000; font-size:12px;">Ошибка! У вас нет такого реферала.</b><br>';
			}
		}else{
			echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Пользователь не определен.</b><br>';
		}
	}
}
?>