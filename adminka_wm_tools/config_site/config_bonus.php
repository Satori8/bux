<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� ������� �� ������������</b></h3>';

if(count($_POST)>0) {
	$bon_reg_user = (isset($_POST["bon_reg_user"])) ? number_format(abs(floatval(str_replace(",",".",trim($_POST["bon_reg_user"])))),2,".","") : false;
	$bon_reg_ref = (isset($_POST["bon_reg_ref"])) ? number_format(abs(floatval(str_replace(",",".",trim($_POST["bon_reg_ref"])))),2,".","") : false;
	$comis_sys_bon = isset($_POST["comis_sys_bon"]) ? number_format(floatval($_POST["comis_sys_bon"]), 2, ".", "") : 0;

$bon_popoln = (isset($_POST["bon_popoln"])) ? number_format(abs(floatval(str_replace(",",".",trim($_POST["bon_popoln"])))),2,".","") : false;
$min_click_ref = (isset($_POST["min_click_ref"])) ? number_format(abs(floatval(str_replace(",",".",trim($_POST["min_click_ref"])))),2,".","") : false;

	mysql_query("UPDATE `tb_config` SET `price`='$bon_reg_user' WHERE `item`='bon_reg_user' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$bon_reg_ref' WHERE `item`='bon_reg_ref' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$min_click_ref' WHERE `item`='min_click_ref' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$bon_popoln' WHERE `item`='bon_popoln' AND `howmany`='1'") or die(mysql_error());
	mysql_query("UPDATE `tb_config` SET `price`='$comis_sys_bon' WHERE `item`='comis_sys_bon'") or die(mysql_error());
	
	$bonus = isset($_POST["bonus"]) ? floatval(trim($_POST["bonus"])) : "0";
	

	
	mysql_query("UPDATE `tb_config` SET `price`='$bonus' WHERE `item`='bonus' AND `howmany`='1'") or die(mysql_error());

	echo '<span class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_user' AND `howmany`='1'") or die(mysql_error());
$bon_reg_user = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_ref' AND `howmany`='1'") or die(mysql_error());
$bon_reg_ref = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_click_ref' AND `howmany`='1'") or die(mysql_error());
$min_click_ref = number_format(mysql_result($sql,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_popoln' AND `howmany`='1'") or die(mysql_error());
$bon_popoln = number_format(mysql_result($sql,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus' AND `howmany`='1'");
$bonus = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon'");
$comis_sys_bon = (mysql_num_rows($sql)>0 && mysql_result($sql,0,0)>0 ) ? mysql_result($sql,0,0) : 0;

echo '<form method="post" action="" id="newform">';
echo '<table>';
	echo '<tr><th width="280">����� �� ����������� ������ ������������:</th><td><input type="text" name="bon_reg_user" class="ok12" style="text-align:right;" value="'.$bon_reg_user.'"> ���.</td></tr>';
	echo '<tr><th width="280">����� �� ����������� ��������:</th><td><input type="text" name="bon_reg_ref" class="ok12" style="text-align:right;" value="'.$bon_reg_ref.'"> ���.</td></tr>';
	echo '<tr><th width="280">������� ������ �� ����������� ��������:</th><td><input type="text" name="min_click_ref" class="ok12" style="text-align:right;" value="'.$min_click_ref.'"> ��� ������ � ��������.</td></tr>';
	echo '<tr><th width="280">����� ��� ����������:</th><td><input type="text" name="bon_popoln" class="ok12" style="text-align:right;" value="'.$bon_popoln.'"> %</td></tr>';
        echo '<tr><th width="280">���������� �����:</th><td><input type="text" name="bonus" class="ok12" style="text-align:right;" value="'.$bonus.'"> %</td></tr>';
        
        echo '<tr><th width="280">������� �� ��� �����</b>:</th><td><input type="text" class="ok12" name="comis_sys_bon" style="text-align:right;" value="'.number_format($comis_sys_bon, 0, ".", "").'"> [ % ]</td></tr>';

	echo '<tr><td width="280">&nbsp</td><td><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>