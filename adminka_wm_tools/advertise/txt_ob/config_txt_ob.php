<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ���������� ����������</b></h3>';

if(count($_POST)>0) {

	$cena_txt_ob = floatval(trim($_POST["cena_txt_ob"]));
	$min_txt_ob = intval(trim($_POST["min_txt_ob"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_txt_ob' WHERE `item`='cena_txt_ob'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$min_txt_ob' WHERE `item`='min_txt_ob'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_txt_ob' AND `howmany`='1'");
$cena_txt_ob = mysql_result($sql,0,0);
$cena_txt_ob = number_format($cena_txt_ob,2,".","");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_txt_ob' AND `howmany`='1'");
$min_txt_ob = mysql_result($sql,0,0);
$min_txt_ob = number_format($min_txt_ob,0,".","");

echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table>';
	echo '<tr><th width="200" align="left">��������� ����������:</th><td><input type="text" class="ok12" name="cena_txt_ob" style="text-align:right;" value="'.$cena_txt_ob.'"> ���./�����</td></tr>';
	echo '<tr><th width="200" align="left">����������� �����:</th><td><input type="text" class="ok12" name="min_txt_ob" style="text-align:right;" value="'.$min_txt_ob.'"> ����</td></tr>';
	echo '<tr><td width="200" align="left">&nbsp</td><td><input type="submit" value="��������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>