<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ������-������������ �������</b></h3>';

if(count($_POST)>0) {

	$cena_psevdo = floatval(trim($_POST["cena_psevdo"]));
	$cena_color_psevdo = floatval(trim($_POST["cena_color_psevdo"]));
	$min_psevdo = floatval(trim($_POST["min_psevdo"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_psevdo' WHERE `item`='cena_psevdo'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_color_psevdo' WHERE `item`='cena_color_psevdo'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$min_psevdo' WHERE `item`='min_psevdo'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������!</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_psevdo' AND `howmany`='1'");
$cena_psevdo = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_color_psevdo' AND `howmany`='1'");
$cena_color_psevdo = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_psevdo' AND `howmany`='1'");
$min_psevdo = mysql_result($sql,0,0);


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table>';
	echo '<tr><th width="200" align="left">��������� �� 1 �����:</th><td><input type="text" size="10" name="cena_psevdo" style="text-align:right;" value="'.$cena_psevdo.'"> ���.</td></tr>';
	echo '<tr><th width="200" align="left">��������� ���������:</th><td><input type="text" size="10" name="cena_color_psevdo" style="text-align:right;" value="'.$cena_color_psevdo.'"> ���./�����</td></tr>';
	echo '<tr><th width="200" align="left">����������� �����:</th><td><input type="text" size="10" name="min_psevdo" style="text-align:right;" value="'.$min_psevdo.'"> �����</td></tr>';
	echo '<tr><td width="200" align="left">&nbsp</td><td><input type="submit" value="��������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>