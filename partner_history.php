<?php
$pagetitle = "������� ����������� ����������";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
	//$username="Admin";

	$sql_p = mysql_query("SELECT `discount_partner`,`moneys`,`moneys_p` FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_row($sql_p);
		$discount_partner = $row_p["0"];
		$moneys_partner = $row_p["1"];
		$moneys_partner_p = $row_p["2"];
		$moneys_partner_m =  $moneys_partner + $moneys_partner_p;
	}else{
		$discount_partner = false;
		$moneys_partner = false;
		$moneys_partner_p = false;
		$moneys_partner_m = false;
	}

	echo '<br>';
	echo '<div align="left">����� ����������� ���������� �� �����: <b>'.$moneys_partner.' ���.</b></div>';
	echo '<div align="left">��� ������������ �������� �� ����� �� ��������: <b>'.$moneys_partner_m.' ���.</b></div>';
	echo '<div align="left">�������� ������� �� �����: <b>'.$moneys_partner_p.' ���.</b></div><br>';

	require("navigator/navigator.php");

	$perpage = 25;
	$sql_p = mysql_query("SELECT `id` FROM `tb_partner` WHERE `referer`='$username'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	echo '<h3 class="sp">����� �� ������ ���������� ������� ����� ����������� ����������</h3>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '?page=', "");
	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th>����</th>';
		echo '<th>�������</th>';
		echo '<th>����������� ���������</th>';
		echo '<th>��� %</th>';
		echo '<th>���������</th>';
	echo '</tr></thead>';

	$sql_p = mysql_query("SELECT * FROM `tb_partner` WHERE `referer`='$username' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql_p)>0) {
		echo '<tbody>';

		while($row_p = mysql_fetch_array($sql_p)) {
			echo '<tr align="center">';
				echo '<td>'.DATE("d.m.Y H:i", $row_p["time"]).'</td>';
				echo '<td>'.$row_p["username"].'</td>';

				if($row_p["type"]=="p_sl") {
					echo '<td>����������� ������</td>';
				}elseif($row_p["type"]=="p_txt"){
					echo '<td>��������� ����������</td>';
				}elseif($row_p["type"]=="p_b468x60"){
					echo '<td>������� 468x60</td>';
				}elseif($row_p["type"]=="p_b200x300"){
					echo '<td>������� 200x300</td>';
				}elseif($row_p["type"]=="p_b100x100"){
					echo '<td>������� 100x100</td>';
				}elseif($row_p["type"]=="p_frm"){
					echo '<td>������ �� ������</td>';
				}elseif($row_p["type"]=="p_psd"){
					echo '<td>������-������������ ������</td>';
				}elseif($row_p["type"]=="p_packet_1"){
					echo '<td>����� ������� �1</td>';
				}elseif($row_p["type"]=="p_packet_2"){
					echo '<td>����� ������� �2</td>';
				}elseif($row_p["type"]=="p_packet_3"){
					echo '<td>����� ������� �3</td>';
				}elseif($row_p["type"]=="p_packet_4"){
					echo '<td>����� ������� �4</td>';
				}elseif($row_p["type"]=="p_packet_5"){
					echo '<td>����� ������� �5</td>';
				}else{
					echo '<td>�� ����������</td>';
				}

				echo '<td>'.$row_p["percent"].' %</td>';
				echo '<td>'.number_format($row_p["money"],2,".","").' ���.</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	}else{
		echo '<tbody>';
			echo '<tr align="center"><td colspan="5"><b>����������� ���������� �� ��� ���� ��� �� ����</b></td></tr>';
		echo '</tbody>';
	}

	echo '</table>';
	if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '?page=', "");
}

include('footer.php');?>