<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0; width:700px"><b>��������� �������� �������������  �������</b></h3>';

if(count($_POST)>0) {

	$cena_mail_user_count3 = abs(intval(trim($_POST["cena_mail_user_count3"])));
	$cena_mail_user_count4 = abs(intval(trim($_POST["cena_mail_user_count4"])));
	$cena_mail_user = floatval(trim($_POST["cena_mail_user"]));
	$cena_mail_user_sk = intval(trim($_POST["cena_mail_user_sk"]));

	
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_user_count3' WHERE `item`='cena_mail_user_count' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_user_count4' WHERE `item`='cena_mail_user_count' AND `howmany`='2'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_user' WHERE `item`='cena_mail_user' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_mail_user_sk' WHERE `item`='cena_mail_user_sk' AND `howmany`='1'") or die(mysql_error());

		

	echo '<span class="msg-ok" style="width:660px;">��������� ������� ���������!</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user_count' AND `howmany`='1'");
$mail_users_count_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user_count' AND `howmany`='2'");
$mail_users_count_4 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user' AND `howmany`='1'");
$cena_mail_user = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user_sk' AND `howmany`='1'");
$cena_mail_user_sk = mysql_result($sql,0,0);


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table style="width:700px;">';

		echo '<thead><tr><th>��������</th><th width="120">��������</th></tr></thead>';
	echo '<tr>';
		echo '<td><b>���������� �������� � ���� ���������</b>, ��.</td>';
		echo '<td><input type="text" name="cena_mail_user_count3" value="'.$mail_users_count_3.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>���������� �������� � ������ ���������</b>, ��.</td>';
		echo '<td><input type="text" name="cena_mail_user_count4" value="'.$mail_users_count_4.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>��������� �������� ��������� ���� �������������</b>, ���./���������</td>';
		echo '<td><input type="text" name="cena_mail_user" value="'.$cena_mail_user.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>������ ��� �������� �������� � ������� 2-� ������ �������������</b>, %</td>';
		echo '<td><input type="text" name="cena_mail_user_sk" value="'.$cena_mail_user_sk.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';



	
	echo '<tr align="center"><td colspan="2"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>