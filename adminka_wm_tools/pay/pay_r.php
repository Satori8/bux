<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ �������</b></h3>';

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis' AND `howmany`='1'");
$pay_comis1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis' AND `howmany`='2'");
$pay_comis2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_qw' AND `howmany`='1'");
$pay_comis_qw = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='pay_comis_sberbank' AND `howmany`='1'");
$pay_comis_sberbank = mysql_result($sql,0,0);


if(isset($_GET["option"]) && isset($_POST["id"]) && $_GET["option"]=="pay") {
	$id = (isset($_POST["id"])) ? intval($_POST["id"]) : false;

	$sql = mysql_query("SELECT * FROM `tb_history` WHERE `id`='$id' AND `status_pay`='0' AND (`method`='WebMoney' OR `method`='Qiwi' OR `method`='SberBank') AND `status`='' AND `tipo`='0'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$username = $row["user"];
		$money_pay = abs($row["amount"]);

		mysql_query("UPDATE `tb_users` SET `money_out`=`money_out`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
		mysql_query("UPDATE `tb_history` SET `status_pay`='1', `date`='".DATE("d.m.Y H:i",time())."',`time`='".time()."' WHERE `id`='$id' AND `status_pay`='0'") or die(mysql_error());
		mysql_query("UPDATE `tb_statistics` SET `viplat`=`viplat`+'1', `sumpay`=`sumpay`+'$money_pay' WHERE `id`='1'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok">������� #'.$row["tran_id"].' ������������ '.$username.' ������� �����������!</span>';
	}else{
		echo '<span id="info-msg" class="msg-error">������� #'.$row["tran_id"].' ���, ���� ������� ��� ���� ������� (��� ��������)!</span>';
	}

	echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 3000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="3;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

if(isset($_GET["option"]) && isset($_POST["id"]) && $_GET["option"]=="dell_pay") {
	$id = (isset($_POST["id"])) ? intval($_POST["id"]) : false;

	$sql = mysql_query("SELECT * FROM `tb_history` WHERE `id`='$id' AND `status_pay`='0' AND (`method`='WebMoney' OR `method`='Qiwi' OR `method`='SberBank') AND `status`='' AND `tipo`='0'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$username = $row["user"];
		$money_pay = abs($row["amount"]);

		mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
		mysql_query("UPDATE `tb_history` SET `status_pay`='2', `date`='".DATE("d.m.Y H:i",time())."',`time`='".time()."' WHERE `id`='$id' AND `status_pay`='0'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok">�������� ������������ '.$username.' ��������, ������ ���������� �� ������ ��������!</span>';
	}else{
		echo '<span id="info-msg" class="msg-error">������� #'.$row["tran_id"].' ���, ���� ������� ��� ���� ������� (��� ��������)!</span>';
	}

	echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 3000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="3;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT * FROM `tb_history` WHERE `status_pay`='0' AND (`method`='WebMoney' OR `method`='Qiwi' OR `method`='SberBank') AND `status`='' AND `tipo`='0' ORDER BY `id` ASC");
$kol = mysql_num_rows($sql);
if($kol>0) {
	$sql_s = mysql_query("SELECT sum(`amount`) FROM `tb_history` WHERE `status_pay`='0' AND (`method`='WebMoney' OR `method`='Qiwi' OR `method`='SberBank') AND `status`='' AND `tipo`='0'");
	$sumpay_wait = mysql_result($sql_s,0,0);

	echo '<br><b>�����:</b> <b style="color:#FF0000;">'.$kol.'</b> �� ����� <b style="color:#FF0000;">'.p_floor($sumpay_wait, 2).'</b> ���.';
}

echo '<table>';
echo '<tr align="center">';
	echo '<th>ID</th>';
	echo '<th>�����</th>';
	echo '<th>�������</th>';
	echo '<th>���� ������</th>';
	echo '<th>����� ������</th>';
	echo '<th>����������</th>';
	echo '<th></th>';
	echo '<th></th>';
	echo '<th></th>';
echo '</tr>';

$sum_m = 0;
if($kol>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr align="center">';
		echo '<td>'.$row["tran_id"].'</td>';
		echo '<td>'.$row["user"].'</td>';
		echo '<td>'.$row["method"].': '.$row["wmr"].'</td>';
		echo '<td>'.DATE("d.m.Y H:i", $row["time"]).'</td>';

		if(strtolower($row["method"])==strtolower("WebMomey")) {
			if(abs($row["amount"])>1.25) {
				$summa_topay = p_floor( ((abs($row["amount"]) * (100 - $pay_comis1)/100) - $pay_comis2), 2);
			}else{
				$summa_topay = (abs($row["amount"]) - 0.01 - $pay_comis2);
			}

		}elseif(strtolower($row["method"])==strtolower("Qiwi")) {
			$summa_topay = p_floor( ((abs($row["amount"]) * (100 - $pay_comis_qw)/100)), 2);

		}elseif(strtolower($row["method"])==strtolower("PayPal")) {
			$summa_topay = p_floor( ((abs($row["amount"]) * (100 - $pay_comis_paypal)/100)), 2);

		}elseif(strtolower($row["method"])==strtolower("SberBank")) {
			$summa_topay = p_floor( ((abs($row["amount"]) * (100 - $pay_comis_sberbank)/100)), 2);

		}else{
			$summa_topay = '<span style="color:#FF0000;">������!</span>';
		}

		$desc = "������� � ".$url." ����� - ".$row["user"].". ���������� �� ������!";

		echo '<td>'.$summa_topay.'</td>';
		echo '<td>'.$desc.'</td>';

		if(strtolower($row["method"])==strtolower("WebMomey")) {
			echo '<td width="80"><a href="wmk:payto?Purse='.$row["wmr"].'&Amount='.$summa_topay.'&Desc='.$desc.'&BringToFront=Y" class="sub-blue" style="padding:0;">���������</a></td>';
		}else{
			echo '<td>&nbsp;&nbsp;</td>';
		}

		echo '<td width="80"><form method="post" action="'.$_SERVER['PHP_SELF'].'?op='.intval($_GET["op"]).'&option=pay"><input type="hidden" name="id" value="'.$row["id"].'"><input type="submit" class="sub-green" value="��������"></form></td>';
		echo '<td width="80"><form method="post" action="'.$_SERVER['PHP_SELF'].'?op='.intval($_GET["op"]).'&option=dell_pay"><input type="hidden" name="id" value="'.$row["id"].'"><input type="submit" class="sub-red" value="��������"></form></td>';
		echo '</tr>';
	}
	echo '</table>';
}else{
	echo '<tr><td colspan="10"><div align="center" style="color:#FF0000; font-weight:bold;">������ � �������� ���.</div></td></tr>';
	echo '</table>';
}
?>
