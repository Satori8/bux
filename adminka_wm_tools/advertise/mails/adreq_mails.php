<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������������ ������ ��������� �����</b></h3>';

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

mysql_query("UPDATE `tb_ads_mails` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

if(count($_POST)>0) {
	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiar(trim($_POST["id"]))) : false;
	$option = ( isset($_GET["option"]) && preg_match("|^[\d]{1}$|", trim($_GET["option"])) ) ? intval(limpiar(trim($_GET["option"]))) : false;

	if($option==1) {
		mysql_query("UPDATE `tb_ads_mails` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());

		echo '<span class="msg-ok">������� ���������.</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
	if($option==2) {
		mysql_query("DELETE FROM `tb_ads_mails` WHERE `id`='$id'") or die(mysql_error());

		echo '<span class="msg-error">����� ������.</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
}

echo '<table>';
echo '<tr>';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>���� ���������</th>';
	echo '<th>��������� URL</th>';
	echo '<th>�������� ����</th>';
	echo '<th>����</th>';
	echo '<th>�������� ����</th>';
	echo '<th>������� �� ����</th>';
	echo '<th>���-��</th>';
	echo '<th>���������</th>';
	echo '<th></th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='0' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
		echo '<td>'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td>'.$system_pay[$row["method_pay"]].'</td>';
		echo '<td>'.DATE("d.m.Y H:i",$row["date"]).'</td>';

		echo '<td>';
			echo $row["title"];
			echo '<br>';
			echo '<a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a>';
		echo '</td>';

		echo '<td>';
			echo ("1" == $row["tarif"] ? 'VIP' : false);
			echo ("2" == $row["tarif"] ? 'Standart' : false);
			echo ("3" == $row["tarif"] ? 'Lite' : false);
		echo '</td>';

		echo '<td>';
			echo ("0" == $row["color"] ? '���' : false);
			echo ("1" == $row["color"] ? '��' : false);
		echo '</td>';                                     

		echo '<td>';
			echo ("0" == $row["active"] ? '���' : false);
			echo ("1" == $row["active"] ? '��' : false);
		echo '</td>';

		echo '<td>';
			echo ("0" == $row["gotosite"] ? '���' : false);
			echo ("1" == $row["gotosite"] ? '��' : false);
		echo '</td>';

		echo '<td>'.$row["plan"].'.</td>';

		echo '<td>'.number_format($row["money"], 2, ".", " ").' ���.</td>';

		echo '<td>';
			echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&option=1">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&option=2">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
}
echo '</table>';
?>