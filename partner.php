<?php
$pagetitle = "����������� ���������";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
	$partner_max_percent = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent_pack' AND `howmany`='1'");
	$partner_max_percent_pack = mysql_result($sql,0,0);

	$sql_p = mysql_query("SELECT `discount_partner`,`moneys`,`moneys_p` FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_row($sql_p);
		$discount_partner = $row_p["0"];
		$moneys_partner = $row_p["1"];
		$moneys_partner_p = $row_p["2"];
		$moneys_partner_m =  $moneys_partner + $moneys_partner_p;
	}else{
		$discount_partner = 0;
		$moneys_partner = 0;
		$moneys_partner_p = 0;
		$moneys_partner_m = 0;
	}
	
	//echo '<div align="center" style="margin:0 auto;"><a href="/reflinks.php"><img src="img/SG.gif" width="740" height="170" border="0" align="absmiddle"></a></div>';

	echo '<table align="center" style="width:100%; margin:0 auto; border-collapse: separate; border-spacing: 5px 5px;">';
	echo '<tr>';
		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">����������� ��������� ��� ����������� �������</h1>';
			echo '<div align="center">����������� �������������� ���������� �� '.$partner_max_percent.'% �� ����� ������� �������, ���������� � ����������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������, ������ ����������</div>';
			echo '<h2 class="sp" style="text-align:left;">����������� �������������� ����������� �� �������:</h2>';
			echo '1. <a href="/advertise.php?ads=stat_links" title="����������� ������">����������� ������</a><br>';
			echo '2. <a href="/advertise.php?ads=txt_ob" title="��������� ����������">��������� ����������</a><br>';
			echo '3. <a href="/advertise.php?ads=banners" title="����������� �������� 468�60">����������� �������� (468�60)</a><br>';
			echo '4. <a href="/advertise.php?ads=banners" title="����������� �������� 200�300">����������� �������� (200�300)</a><br>';
			echo '5. <a href="/advertise.php?ads=banners" title="����������� �������� 100x100">����������� �������� (100�100)</a><br>';
			echo '6. <a href="/advertise.php?ads=frm_links" title="������ �� ������">������ �� ������</a><br>';
			echo '7. <a href="/advertise.php?ads=psevdo" title="������-������������ ������">������-������������ ������</a><br><br>';

			echo '<div align="center" style=""><span onClick="window.open(\'partner_stat.php\'); return false;" class="proc-btn">���������</span></div>';
		echo '</td>';

		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">����������� ��������� ��� ��������� �������</h1>';
			echo '<div align="center">����������� �������������� ���������� �� '.$partner_max_percent_pack.'% �� ����� ������� �������, ���������� � ����������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������, ������ ����������</div>';
			echo '<h2 class="sp" style="text-align:left;">����������� �������������� ����������� �� �������:</h2>';
			echo '1. <a href="/advertise.php?ads=packet&p=1" title="������ ������� �1">������ ������� �1</a><br>';
			echo '2. <a href="/advertise.php?ads=packet&p=2" title="������ ������� �2">������ ������� �2</a><br>';
			echo '3. <a href="/advertise.php?ads=packet&p=3" title="������ ������� �3">������ ������� �3</a><br>';
			echo '4. <a href="/advertise.php?ads=packet&p=4" title="������ ������� �4">������ ������� �4</a><br>';
			echo '5. <a href="/advertise.php?ads=packet&p=5" title="������ ������� �5">������ ������� �5</a><br><br><br><br>';
	
			echo '<div align="center" style=""><span onClick="window.open(\'partner_packet.php\'); return false;" class="proc-btn">���������</span></div>';
		echo '</td>';
	echo '</tr>';

	echo '<tr>';
		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">������ ��������� 1-�� ������</h1>';
			echo '<div align="left">���������� ����� ���������-�������������� ��������� ���������!<br>������������ ���������� ������� ������� �� ����������� ���������!</div>';

			if($discount_partner!=false) echo '<br><div align="center">� ��������� ������ ����������� ������: <b>'.$discount_partner.'%</b></div>';
			else echo '<br><br>';

			echo '<br><br><br><br>';

			echo '<div align="center" style=""><span onClick="window.open(\'partner_discount.php\'); return false;" class="proc-btn">���������</span></div>';
		echo '</td>';

		echo '<td width="50%" valign="top" style="border: 2px double #0088AF; padding:2px 10px; box-shadow:1px 2px 3px rgba(0, 0, 0, 0.4);">';
			echo '<h1 class="sp" style="font-size:14px; letter-spacing:normal; color: #ab0606; text-align:center; margin:0; margin-top:2px; margin-bottom:15px;">������� ����������� ����������</h1>';
			echo '<div align="left">����� ����������� ���������� �� �����: <b>'.$moneys_partner.' ���.</b></div>';
			echo '<div align="left">��� ������������ �������� �� ����� �� ��������: <b>'.$moneys_partner_m.' ���.</b></div>';
			echo '<div align="left">�������� ������� �� �����: <b>'.$moneys_partner_p.' ���.</b></div>';

			echo '<br><div align="center">����� �� ������ ���������� ������� ����� ����������� ����������</div>';
			echo '<br><br>';

			echo '<div align="center" style=""><span onClick="window.open(\'partner_history.php\'); return false;" class="proc-btn">���������</span></div>';
		echo '</td>';
	echo '</tr>';
	echo '</table>';
}

include('footer.php');?>