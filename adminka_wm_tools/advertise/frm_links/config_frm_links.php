<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ������ �� ������</b></h3>';

if(count($_POST)>0) {

	$cena_frm_links = floatval(trim($_POST["cena_frm_links"]));
	$min_frm_links = intval(trim($_POST["min_frm_links"]));

	mysql_query("UPDATE `tb_config` SET `price`='$cena_frm_links' WHERE `item`='cena_frm_links'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$min_frm_links' WHERE `item`='min_frm_links'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_frm_links' AND `howmany`='1'");
$cena_frm_links = mysql_result($sql,0,0);
$cena_frm_links = number_format($cena_frm_links,2,".","");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_frm_links' AND `howmany`='1'");
$min_frm_links = mysql_result($sql,0,0);
$min_frm_links = number_format($min_frm_links,0,".","");

echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
echo '<table>';
	echo '<tr><th width="200" align="left">��������� ����������:</th><td><input type="text" class="ok12" name="cena_frm_links" style="text-align:right;" value="'.$cena_frm_links.'"> ���./�����</td></tr>';
	echo '<tr><th width="200" align="left">����������� �����:</th><td><input type="text" class="ok12" name="min_frm_links" style="text-align:right;" value="'.$min_frm_links.'"> ����</td></tr>';
	echo '<tr><td width="200" align="left">&nbsp</td><td><input type="submit" value="��������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>