<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0; width:600px"><b>��������� ����-��������</b></h3>';

if(count($_POST)>0) {

	$cena_hits_aserf = floatval(trim($_POST["cena_hits_aserf"]));
	$nacenka_hits_aserf = floatval(trim($_POST["nacenka_hits_aserf"]));
	$min_hits_aserf = abs(intval(trim($_POST["min_hits_aserf"])));
	$timer_aserf_ot = floatval(trim($_POST["timer_aserf_ot"]));
	$timer_aserf_do = floatval(trim($_POST["timer_aserf_do"]));
	$cena_timer_aserf = floatval(trim($_POST["cena_timer_aserf"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_hits_aserf' WHERE `item`='cena_hits_aserf' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$nacenka_hits_aserf' WHERE `item`='nacenka_hits_aserf' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$min_hits_aserf' WHERE `item`='min_hits_aserf' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$timer_aserf_ot' WHERE `item`='timer_aserf_ot' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$timer_aserf_do' WHERE `item`='timer_aserf_do' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$cena_timer_aserf' WHERE `item`='cena_timer_aserf' AND `howmany`='1'") or die(mysql_error());

	echo '<span class="msg-ok" style="width:560px;">��������� ������� ���������!</span>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
$cena_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_hits_aserf' AND `howmany`='1'");
$nacenka_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_hits_aserf' AND `howmany`='1'");
$min_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
$timer_aserf_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
$timer_aserf_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_timer_aserf' AND `howmany`='1'");
$cena_timer_aserf = mysql_result($sql,0,0);


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table style="width:600px;">';
	echo '<thead><tr><th>��������</th><th width="120">��������</th></tr></thead>';
	echo '<tr>';
		echo '<td><b>���� �� ����</b>, ���./�������</td>';
		echo '<td><input type="text" name="cena_hits_aserf" value="'.$cena_hits_aserf.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>������� �������</b>, %</td>';
		echo '<td><input type="text" name="nacenka_hits_aserf" value="'.$nacenka_hits_aserf.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>����������� �����</b>, ���������</td>';
		echo '<td><input type="text" name="min_hits_aserf" value="'.$min_hits_aserf.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>����� ���������</b>, ���.</td>';
		echo '<td><input type="text" name="timer_aserf_ot" value="'.$timer_aserf_ot.'" class="ok12" style="text-align:right; width:53px;"> - <input type="text" name="timer_aserf_do" value="'.$timer_aserf_do.'" class="ok12" style="text-align:right; width:53px;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>������� �� ������</b>, �� 1 ���.</td>';
		echo '<td><input type="text" name="cena_timer_aserf" value="'.$cena_timer_aserf.'" class="ok12" style="text-align:right;"></td>';
	echo '</tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>