<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������������ ������ ��������� �����</b></h3>';

mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date_end`='".time()."' WHERE `status`>'0' AND (`totals`<'1' OR ( `nolimit`>'0' AND `nolimit`<='".time()."') )") or die(mysql_error());

$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney Merchant";
$system_pay[2] = "RoboKassa.com";
$system_pay[3] = "LiqPay.com";
$system_pay[4] = "Interkassa.com";
$system_pay[5] = "Payeer.com";
$system_pay[6] = "Qiwi.com";
$system_pay[7] = "PerfectMoney.com";
$system_pay[8] = "YandexMoney";
$system_pay[11] = "MegaKassa";
$system_pay[10] = "��������� ����";

if(count($_POST)>0) {
	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiar(trim($_POST["id"]))) : false;
	$option = ( isset($_POST["option"]) && preg_match("|^[a-zA-Z0-9\-_-]{1,20}$|", trim($_POST["option"])) ) ? limpiar(trim($_POST["option"])) : false;

	$sql = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='0'");
	if(mysql_num_rows($sql)>0) {
		if($option=="add") {
			mysql_query("UPDATE `tb_ads_mails` SET `status`='1' WHERE `id`='$id'") or die(mysql_error());
			echo '<span id="info-msg" class="msg-ok">������� ���������!</span>';
		}elseif($option=="dell") {
			mysql_query("DELETE FROM `tb_ads_mails` WHERE `id`='$id'") or die(mysql_error());
			echo '<span id="info-msg" class="msg-error">����� ������!</span>';
		}

		echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 3000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="3;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
}

echo '<table>';
echo '<tr align="center">';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>��������� URL</th>';
	echo '<th>����������</th>';
	echo '<th>���������</th>';
	echo '<th>���������</th>';
	echo '<th></th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='0' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	$revisit_to[0] = "������ 24 ����";
	$revisit_to[1] = "������ 48 �����";
	$revisit_to[2] = "1 ��� � �����";

	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
		echo '<td>'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td>'.$system_pay[$row["method_pay"]].'</td>';

		echo '<td>';
			echo $row["title"];
			echo '<br>';
			echo '<a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a>';
		echo '</td>';

		echo '<td align="left">';
			echo '���� ������: <b>'.DATE("d.m.Y H:i", $row["date"]).'</b><br>';
			if($row["nolimit"]>0) {
				echo '��������: �� <b>'.DATE("d.m.Y H:i",$row["nolimit"]).'</b><br>';
			}else{
				echo '��������: <b>'.$row["plan"].'</b> ���������<br>';
			}
			echo '������: <b>'.$row["timer"].' ���.</b>';
		echo '</td>';

		echo '<td>';
			echo '<span style="float:left;">���������� � ������ ������:</span> <span style="float:right; font-weight:bold;">'.($row["up_list"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">���������� �������� ������:</span> <span style="float:right; font-weight:bold;">'.$revisit_to[$row["revisit"]].'</span><br>';
			echo '<span style="float:left;">�������� ������:</span> <span style="float:right; font-weight:bold;">'.($row["color"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">�������� ����:</span> <span style="float:right; font-weight:bold;">'.($row["active"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">������� �� ����:</span> <span style="float:right; font-weight:bold;">'.($row["active"]==1 ? '��' : '���').'</span><br>';
			echo '<span style="float:left;">���������� IP:</span> <span style="float:right; font-weight:bold;">'.($row["unic_ip"]==1 ? '��' : '���').'</span><br>';
			//echo '<span style="float:left;">���������� ��������:</span> <span style="float:right; font-weight:bold;">'.($row["new_users"]==1 ? '��' : '���').'</span><br>';
			//echo '<span style="float:left;">�� ������� ��������:</span> <span style="float:right; font-weight:bold;">'.($row["no_ref"]==1 ? '���' : '��� ��������').'</span><br>';
			//echo '<span style="float:left;">�� �������� ��������:</span> <span style="float:right; font-weight:bold;">'.($row["sex_adv"]!=0 ? '��' : '���').'</span>';
		echo '</td>';

		echo '<td>'.number_format($row["money"], 2, ".", " ").' ���.</td>';

		echo '<td>';
			echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="add">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="dell">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
}
echo '</table>';
?>