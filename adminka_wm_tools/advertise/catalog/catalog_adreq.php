<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
mysql_query("UPDATE `tb_ads_catalog` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������������ ������ ������ � ��������</b></h3>';

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

if(count($_POST)>0) {
	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiar(trim($_POST["id"]))) : false;
	$option = (isset($_POST["option"])) ? limpiar($_POST["option"]) : false;

	if($option=="add") {
		$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `id`='$id' AND `status`='0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("UPDATE `tb_ads_catalog` SET `status`='1', `date`='".time()."', `date_end`=`plan`*'".(24*60*60)."'+'".time()."' WHERE `id`='$id'") or die(mysql_error());

			require_once(ROOT_DIR."/merchant/func_cache.php");
			cache_catalog();

			echo '<span id="info-msg" class="msg-ok">������� ���������.</span>';
		}
	}

	if($option=="dell") {
		$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `id`='$id' AND `status`='0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_catalog` WHERE `id`='$id'") or die(mysql_error());

			require_once(ROOT_DIR."/merchant/func_cache.php");
			cache_catalog();

			echo '<span id="info-msg" class="msg-error">������� �������.</span>';
		}

	}

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1400);
		HideMsg("info-msg", 1500);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}


echo '<table class="tables" style="margin:2px auto; padding:0px;">';
echo '<thead><tr align="center">';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th>���-�� �����</th>';
	echo '<th>����</th>';
	echo '<th colspan="2"></th>';
echo '</tr><thead>';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `status`='0' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr align="center">';
			echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
			echo '<td>'.($row["wmid"]!=false ? $row["wmid"]."<br>".$row["username"] : $row["username"]).'</td>';
			echo '<td>'.$system_pay[$row["method_pay"]].'</td>';
			echo '<td align="left">';
				echo '���������: <b '.($row["color"]=="1" ? 'style="color:#FF0000;"' : false).'>'.(strlen($row["title"])>50 ? limitatexto($row["title"],50)."...." : $row["title"]).'</b><br>';
				echo 'URL: '.'<a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>60 ? "<b>".limitatexto($row["url"],60)."</b>...." : "<b>".$row["url"]."</b>").'</a><br>';
				echo 'IP: <b>'.$row["ip"].'</b>';
			echo '</td>';
			echo '<td>�� '.DATE("d.m.Y H:i", $row["date"]).'<br>�� '.DATE("d.m.Y H:i", $row["date_end"]).'</td>';
			echo '<td>'.$row["plan"].'</td>';
			echo '<td>'.number_format($row["money"], 2, ".", " ").' ���.</td>';

			echo '<td width="80">';
				echo '<form method="POST" action="">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="add">';
					echo '<input type="submit" value="��������" class="sub-green">';
				echo '</form>';
			echo '</td>';
			echo '<td width="80">';
				echo '<form method="POST" action="">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="dell">';
					echo '<input type="submit" value="�������" class="sub-red">';
				echo '</form>';
			echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="9"><b>�� ���������� ������� ������� ���!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';

?>