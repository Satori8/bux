<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>��������� �������� �� ���������� ���������� �����</b></h3>';

if(count($_POST)>0) {
	$bon_inv_summa = isset($_POST["bon_inv_summa"]) ? number_format(floatval($_POST["bon_inv_summa"]), 2, ".", "") : 0;
	$bon_inv_reiting = isset($_POST["bon_inv_reiting"]) ? number_format(floatval($_POST["bon_inv_reiting"]), 2, ".", "") : 0;
	$bon_inv_money = isset($_POST["bon_inv_money"]) ? number_format(floatval($_POST["bon_inv_money"]), 2, ".", "") : 0;
	$bon_inv_status = isset($_POST["bon_inv_status"]) ? $_POST["bon_inv_status"] : 0;
		
		mysql_query("UPDATE `tb_config` SET `price`='$bon_inv_summa' WHERE `item`='bon_inv_summa'") or die(mysql_error());
		
		mysql_query("UPDATE `tb_config` SET `price`='$bon_inv_reiting' WHERE `item`='bon_inv_reiting'") or die(mysql_error());
		
		mysql_query("UPDATE `tb_config` SET `price`='$bon_inv_money' WHERE `item`='bon_inv_money'") or die(mysql_error());
		
		mysql_query("UPDATE `tb_config` SET `price`='$bon_inv_status' WHERE `item`='bon_inv_status'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1000);
		HideMsg("info-msg", 1050);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_status'");
$bon_inv_status = (mysql_num_rows($sql)>0 && mysql_result($sql,0,0)>0 ) ? mysql_result($sql,0,0) : 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_summa'");
$bon_inv_summa = (mysql_num_rows($sql)>0 && mysql_result($sql,0,0)>0 ) ? mysql_result($sql,0,0) : 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_money'");
$bon_inv_money = (mysql_num_rows($sql)>0 && mysql_result($sql,0,0)>0 ) ? mysql_result($sql,0,0) : 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_inv_reiting'");
$bon_inv_reiting = (mysql_num_rows($sql)>0 && mysql_result($sql,0,0)>0 ) ? mysql_result($sql,0,0) : 0;

echo '<form method="post" action="" id="newform">';
echo '<table class="tables" style="width:500px;">';
echo '<thead><tr align="center"><th>��������</th><th width="120">��������</th></tr></thead>';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>��������/��������� ����� ��� ����������</b>, <br>(1-��������; 0-���������)</td>';
		echo '<td align="center"><input type="text" class="ok12" name="bon_inv_status" value="'.$bon_inv_status.'" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>������ ���� ���� ���������� ������(�����) ���</b>, ���</td>';
		echo '<td align="center"><input type="text" class="ok12" name="bon_inv_summa" value="'.number_format($bon_inv_summa, 2, ".", "").'" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>����� ��������</b>, ��.</td>';
		echo '<td align="center"><input type="text" class="ok12" name="bon_inv_reiting" value="'.number_format($bon_inv_reiting, 2, ".", "").'" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>����� �����</b>, ���</td>';
		echo '<td align="center"><input type="text" class="ok12" name="bon_inv_money" value="'.number_format($bon_inv_money, 2, ".", "").'" style="text-align:center;"></td>';
	echo '</tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" value="C��������" class="sub-green"></td></tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';

?>