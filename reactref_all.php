<?php
session_start();
header("Content-type: text/html; charset=windows-1251");
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Необходимо авторизоваться!</b>';
}else{
	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_reactref' AND `howmany`='1'");
	$cena_mail_reactref = mysql_result($sql,0,0);

	if($username==false) {
		echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Пользователь не определен!</b><br>';
	}else{
		$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$username'");
		if(mysql_num_rows($sql)>0) {
			$sql_c = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username' AND `lastlogdate2`<'".(time()-7*24*60*60)."' AND `lastdate_reactref`<'".(time()-5*24*60*60)."'");
			$all = mysql_num_rows($sql_c);
			if($all>0) {

				echo '<form action="" method="post" onkeypress="if((event.ctrlKey) &amp;&amp; ((event.keyCode == 0xA)||(event.keyCode == 0xD))){this.submit();}">';
				echo '<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td valign="top">';
					echo '<input type="hidden" name="uids" value="1">';
					echo 'Количество рефералов для отправки сообщений - '.$all.'. Вы можете написать рефералам сообщение на их e-mail адрес и предложить им вернуться на проект.<br>';
					echo 'Стоимость отправки одного сообщения: <b>'.$cena_mail_reactref.' руб.</b><br><br>';
					echo 'Стоимость отправки сообщений: <b>'.($cena_mail_reactref*$all).' руб.</b><br><br>';
					echo '<b>Сообщение рефералам:</b> (не обязательно)<br>';
					echo '<textarea  cols="" rows="5" name="mess_for_ref" style="width: 94%; background-color: #FBFCCC;"></textarea><br><br>';
					echo '<center><input type="submit" name="submit" value="Разбудить рефералов" style="background: #2087DB; color: #FFFFFF; padding: 0px; padding: 3px 10px; font-size: 12px; border: 1px solid #5656E4;"></center>';
				echo '</td></tr></table>';
				echo '</form>';
			}else{
				echo '<b style="color:#FF0000; font-size:12px;">Нет рефералов для отправки им сообщений.</b><br>';
			}
		}else{
			echo '<b style="color:#FF0000; font-size:12px;">Ошибка! Пользователь не определен.</b><br>';
		}
	}
}
?>