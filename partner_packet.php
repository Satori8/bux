<?php
$pagetitle = "����������� ��������� ��� ��������� �������";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'");
	$partner_max_percent_pack = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_active_pack' AND `howmany`='1'");
	$partner_active_pack = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day_pack'");
	if(mysql_num_rows($sql)>0) {
		$row_pd = mysql_fetch_array($sql);
		$partner_count_day_pack = $row_pd["howmany"];
		$partner_count_per_pack = $row_pd["price"];
	}else{
		$partner_count_day_pack = 1;
		$partner_count_per_pack = 1;
	}

	echo '<h1 class="sp" style="color: #ab0606; text-align:center; margin:0; margin-top:20px;">����������� �������������� ���������� �� '.$partner_max_percent_pack.'%</h1>';
	echo '<div align="center">�� ����� ������ ������ �������, ����������� � ���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������, ������ ����������</div>';
	echo '<h2 class="sp" style="text-align:left;">����������� �������������� ����������� �� �������:</h2>';
		echo '1. <a href="/advertise.php?ads=packet&p=1" title="������ ������� �1">������ ������� �1</a><br>';
		echo '2. <a href="/advertise.php?ads=packet&p=2" title="������ ������� �2">������ ������� �2</a><br>';
		echo '3. <a href="/advertise.php?ads=packet&p=3" title="������ ������� �3">������ ������� �3</a><br>';
		echo '4. <a href="/advertise.php?ads=packet&p=4" title="������ ������� �4">������ ������� �4</a><br>';
		echo '5. <a href="/advertise.php?ads=packet&p=5" title="������ ������� �5">������ ������� �5</a><br><br>';

	echo '<h5 class="sp">��� ����� ���������� ����������� ���������?</h5>';
	echo '<div style="text-align:justify;">����� ����������� � ����������� ���������, ����� ����� ���� ������������ ��. ��� ��������� ����������� ����� ���� <b>'.$partner_active_pack.' ������ ��������</b> (��������� ����� 1 ��� ��� ������� ���� �������), ��� ���� ���� ����������� �������������� ����� ���������� � 0 �� 1%. ����� ��������� �� ������ �������� ������� ������������ ��������������, �������� ������ ������� �� ����� �����.</div>';

	echo '<h5 class="sp">����� ������� � ���� ��������?</h5>';
	echo '<div style="text-align:justify;">������� ������ �������������� �������� ������� �� ������ ����������� ������. 
	<b>'.$partner_count_day_pack.'</b> ����� ������ ����� <b>'.$partner_count_per_pack.'%</b> ����������� (����������) �� ������� ���������, ���������� � ���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������.<br><br>��� ���� ������ ����������� � �������������� ����������� � ������������� ��� ����������� �������. ����� �������, ��� ������ �� ������������, ��� ������� ������� �� ��������� �� ������� ���������. ����������� ��������� �������������� <b>'.$partner_max_percent_pack.'%</b>.</div>';

	echo '<h5 class="sp" style="color:#FF0000;">�����!</h5>';
	echo '<div style="text-align:justify;">��� ��������� ��������� <b>'.$partner_active_pack.' ������ ��������</b> (��������� ����� 1 ��� ��� ������� ������ �������). ��� <b>���������� ������������ ��������������</b> ������ ������� ���������� ���������� � <b>���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������</b>. �������������� ����� ����������� �� ��� �������� ���� � � ����� ������ ����� �������� ��� ������ �� �������.</div>';
	echo '<br><br>';

	for($i=1; $i<=5; $i++) {
		if(isset($_GET["active"]) && limpiar($_GET["active"])==$i) {
			if($p_packet[$i]>0) {
				echo '<span class="msg-error">��� ������ ������� �'.$i.' ����������� ��������� ��� ������������!</span>';
			}elseif($my_reiting>$partner_active_pack) {
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active_pack' WHERE `username`='$username'") or die(mysql_error());

				$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
				if(mysql_num_rows($sql_p)>0) {
					mysql_query("UPDATE `tb_users_partner` SET `p_packet_".$i."`='1' WHERE `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_packet_".$i."`) VALUES('$username','1')") or die(mysql_error());
				}
			}else{
				echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
			}
		}
	}

	$sql_p = mysql_query("SELECT * FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_array($sql_p);

		$p_packet[1] = $row_p["p_packet_1"];
		$p_packet[2] = $row_p["p_packet_2"];
		$p_packet[3] = $row_p["p_packet_3"];
		$p_packet[4] = $row_p["p_packet_4"];
		$p_packet[5] = $row_p["p_packet_5"];
	}else{
		$p_packet[1] = 0;
		$p_packet[2] = 0;
		$p_packet[3] = 0;
		$p_packet[4] = 0;
		$p_packet[5] = 0;
	}

	echo '<a name="tab"></a><table class="tables">';
	echo '<thead><tr>';
		echo '<th>����������� ���������</th>';
		echo '<th>������� �� ������� ���������</th>';
		echo '<th></th>';
	echo '</tr></thead>';

	echo '<tbody>';
		for($i=1; $i<=5; $i++) {
			echo '<tr>';
				echo '<td>����� ������� �'.$i.'</td>';
				if($p_packet[$i]>0) {
					echo '<td align="center"><b style="color: green;">'.$p_packet[$i].'%</b> �� ������ ���������</td>';
					echo '<td align="center"><a href="/advertise.php?ads=packet&p='.$i.'">��������� %</a></td>';
				}else{
					echo '<td align="center">[0%] �� ������������</td>';
					echo '<td align="center"><a href="?active='.$i.'">������������ �� <b>'.$partner_active_pack.'</b> ������ ��������</a></td>';
				}
			echo '</tr>';
		}
	echo '</tbody>';
	echo '</table>';

}

include('footer.php');?>