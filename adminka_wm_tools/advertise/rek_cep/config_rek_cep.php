<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ��������� �������</b></h3>';

if(count($_POST)>0) {

	$cena_rek_cep = floatval(trim($_POST["cena_rek_cep"]));
	$cena_color_rek_cep = floatval(trim($_POST["cena_color_rek_cep"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_rek_cep' WHERE `item`='cena_rek_cep'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_color_rek_cep' WHERE `item`='cena_color_rek_cep'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������!</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_rek_cep' and howmany='1'");
$cena_rek_cep = mysql_result($sql,0,0);

$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_color_rek_cep' and howmany='1'");
$cena_color_rek_cep = mysql_result($sql,0,0);


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table>';
	echo '<tr><th width="200" align="left">��������� ����������:</th><td><input type="text" class="ok12" name="cena_rek_cep" style="text-align:right;" value="'.$cena_rek_cep.'"> ���.</td></tr>';
	echo '<tr><th width="200" align="left">��������� ���������:</th><td><input type="text" class="ok12" name="cena_color_rek_cep" style="text-align:right;" value="'.$cena_color_rek_cep.'"> ���.</td></tr>';
	echo '<tr><td width="200" align="left">&nbsp</td><td><input type="submit" value="��������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>