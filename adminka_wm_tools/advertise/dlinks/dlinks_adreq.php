<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������������ ������ ������������ ������</b></h1>';
require($_SERVER['DOCUMENT_ROOT']."/merchant/func_mysql.php");

$system_pay[-2] = "Test Drive";
$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "Wallet One";
$system_pay[4] = "InterKassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "YandexMoney";
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[10] = "����. ����";

$type_serf[1] = "������������";
$type_serf[2] = "���������";
$type_serf[3] = "������������-VIP";
$type_serf[4] = "���������-VIP";
$type_serf[-1] = "���� �����";

if(count($_GET)>0) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="add") {
		$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `id`='$id' AND `status`='0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("UPDATE `tb_ads_dlink` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());

			echo '<span id="info-msg" class="msg-ok">������� ������� ���������!</span>';
		}
	}

	if($option=="delete") {
		$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `id`='$id' AND `status`='0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_dlink` WHERE `id`='$id'") or die(mysql_error());

			echo '<span id="info-msg" class="msg-error">������� ������� �������!</span>';
		}
	}


	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1550);
		HideMsg("info-msg", 1500);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

echo '<table class="tables" style="margin:1px auto;">';
echo '<thead><tr align="center">';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>����������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th>IP</th>';
	echo '<th>��������</th>';
echo '</tr></thead>';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `status`='0' AND `type_serf`!='10' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';

		echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
		echo '<td>'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td>'.$system_pay[$row["method_pay"]].'</td>';

		echo '<td align="left">';
			echo '<b>��� ��������:</b> '.$type_serf[$row["type_serf"]].'<br>';
			if($row["type_serf"]==2 | $row["type_serf"]==4) {
				echo '<b>URL �����:</b> <a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a><br>';
				echo '<b>URL �������:</b> <a href="'.$row["description"].'" target="_blank">'.$row["description"].'</a><br>';
			}else{
				echo '<b>���������:</b> '.$row["title"].'<br>';
				echo '<b>��������:</b> '.$row["description"].'<br>';
				echo '<b>URL �����:</b> <a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a><br>';
			}
		echo '</td>';

		echo '<td align="left">';
			echo '<b>���� ������:</b> '.DATE("d.m.Y H:i", $row["date"]).'<br>';
			if($row["nolimit"]>0) {
				echo '<b>��������:</b> �� '.DATE("d.m.Y H:i",$row["nolimit"]).'<br>';
				echo '<b>������:</b> '.$row["timer"].' ���.<br>';
			}else{
				echo '<b>��������:</b> '.$row["plan"].' ����������<br>';
				echo '<b>������:</b> '.$row["timer"].' ���.<br>';
			}
		echo '</td>';

		echo '<td>'.$row["money"].' ���.</td>';
		echo '<td>'.$row["ip"].'</td>';
		echo '<td width="95">';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("�������� ������� � ID: '.$row["id"].'")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
				echo '<input type="hidden" name="option" value="add">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("������� ������� � ID: '.$row["id"].'")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
				echo '<input type="hidden" name="option" value="delete">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';

		echo '</tr>';
	}
}else{
	echo '<tr align="center"><td colspan="8"><b>������������ ������� ���</b></td></tr>';
}
echo '</tbody>';
echo '</table>';
?>