<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ����������� ��������</b></h3>';

if(count($_POST)>0) {
	$banner100x100 = floatval(trim($_POST["banner100x100"]));
	$banner200x300 = floatval(trim($_POST["banner200x300"]));
	$banner468x60 = floatval(trim($_POST["banner468x60"]));
	$banner468x60_frm = floatval(trim($_POST["banner468x60_frm"]));
	$banner728x90 = floatval(trim($_POST["banner728x90"]));

	mysql_query("UPDATE `tb_config` SET `price`='$banner100x100' WHERE `item`='banner100x100' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$banner200x300' WHERE `item`='banner200x300' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$banner468x60' WHERE `item`='banner468x60' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$banner468x60_frm' WHERE `item`='banner468x60_frm' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$banner728x90' WHERE `item`='banner728x90' AND `howmany`='1'") or die(mysql_error());

	echo '<span id="info-msg" class="msg-ok">��������� ������� ���������.</span>';
	echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 2000); </script>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner468x60_frm' AND `howmany`='1'");
$cena_banner468x60_frm = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner468x60' AND `howmany`='1'");
$cena_banner468x60 = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner200x300' AND `howmany`='1'");
$cena_banner200x300 = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner100x100' AND `howmany`='1'");
$cena_banner100x100 = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner728x90' AND `howmany`='1'");
$cena_banner728x90 = number_format(mysql_result($sql,0,0), 2, ".", "");


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table class="tables" style="width:600px;">';
	echo '<thead><tr><th>��������</th><th width="120">��������</th></tr></thead>';
	echo '<tr>';
		echo '<td><b>��������� ���������� ������� 468x60</b>, ���./�����</td>';
		echo '<td><input type="text" name="banner468x60" value="'.$cena_banner468x60.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>��������� ���������� ������� 468x60 �� ������</b>, ���./�����</td>';
		echo '<td><input type="text" name="banner468x60_frm" value="'.$cena_banner468x60_frm.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>��������� ���������� ������� 200x300</b>, ���./�����</td>';
		echo '<td><input type="text" name="banner200x300" value="'.$cena_banner200x300.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>��������� ���������� ������� 100x100</b>, ���./�����</td>';
		echo '<td><input type="text" name="banner100x100" value="'.$cena_banner100x100.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>��������� ���������� ������� 728x90</b>, ���./�����</td>';
		echo '<td><input type="text" name="banner728x90" value="'.$cena_banner728x90.'" class="ok12" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>