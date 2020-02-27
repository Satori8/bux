<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0; width:700px"><b>Настройки рассылки пользователям проекта</b></h3>';

if(count($_POST)>0) {

	$cena_mail_refs_count1 = abs(intval(trim($_POST["cena_mail_refs_count1"])));
	$cena_mail_refs_count2 = abs(intval(trim($_POST["cena_mail_refs_count2"])));
	$cena_mail_refs = floatval(trim($_POST["cena_mail_refs"]));
	$cena_mail_refs_sk = intval(trim($_POST["cena_mail_refs_sk"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_refs_count1' WHERE `item`='cena_mail_refs_count' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_refs_count2' WHERE `item`='cena_mail_refs_count' AND `howmany`='2'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_refs' WHERE `item`='cena_mail_refs' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_refs_sk' WHERE `item`='cena_mail_refs_sk' AND `howmany`='1'") or die(mysql_error());

	echo '<span class="msg-ok" style="width:660px;">Изменения успешно сохранены!</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs_count' AND `howmany`='1'");
$mail_users_count_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs_count' AND `howmany`='2'");
$mail_users_count_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs' AND `howmany`='1'");
$cena_mail_refs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs_sk' AND `howmany`='1'");
$cena_mail_refs_sk = mysql_result($sql,0,0);


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table style="width:700px;">';
	echo '<thead><tr><th>Параметр</th><th width="120">Значение</th></tr></thead>';
	echo '<tr>';
		echo '<td><b>Количество символов в теме сообщения</b>, шт.</td>';
		echo '<td><input type="text" name="cena_mail_refs_count1" value="'.$mail_users_count_1.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Количество символов в тексте сообщения</b>, шт.</td>';
		echo '<td><input type="text" name="cena_mail_refs_count2" value="'.$mail_users_count_2.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Стоимость отправки сообщения всем пользователям</b>, руб./сообщение</td>';
		echo '<td><input type="text" name="cena_mail_refs" value="'.$cena_mail_refs.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Скидка при отправке активным в течении 2-х недель пользователям</b>, %</td>';
		echo '<td><input type="text" name="cena_mail_refs_sk" value="'.$cena_mail_refs_sk.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="Cохранить изменения" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>