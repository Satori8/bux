<?php
$pagetitle = "������ �������";
include('header.php');
require('config.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
	
}else{
    
echo '<div align="center">����� �������� �� ���������� ��������� 1 ��.:</div>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">�����</th>';
	echo '<th class="top">����� �� �����</th>';
	echo '<th class="top">��������� 1��.</th>';
echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM tb_users WHERE `id`!='1' ORDER BY referals DESC limit 7");
	while($stat=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td width="33%">'.$stat["username"].'</td>';
		echo '<td width="33%">'.round($stat["money"],2).' ���.</td>';
		echo '<td width="33%">'.$stat["referals"].'</td>';
		echo '</tr>';
	}
echo '</table><br /><br />';


echo '<div align="center">����� �������� �� ���������� ��������� 2 ��.:</div>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">�����</th>';
	echo '<th class="top">����� �� �����</th>';
	echo '<th class="top">��������� 2��.</th>';
echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM tb_users WHERE `id`!='1' ORDER BY referals2 DESC limit 7");
	while($stat=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td width="33%">'.$stat["username"].'</td>';
		echo '<td width="33%">'.round($stat["money"],2).' ���.</td>';
		echo '<td width="33%">'.$stat["referals2"].'</td>';
		echo '</tr>';
	}
echo '</table><br /><br />';



echo '<div align="center">������� �������� ��������:</div>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">�����</th>';
	echo '<th class="top">�������</th>';
	echo '<th class="top">������</th>';
echo '</tr></thead>';
	$sql = mysql_query("SELECT * FROM tb_users WHERE `id`!='1' AND visits>0 ORDER BY id DESC limit 10");
	while($stat=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td width="33%">'.$stat["username"].'</td>';
		echo '<td width="33%">'.$stat["referer"].'</td>';
		echo '<td width="33%">'.$stat["visits"].'</td>';
		echo '</tr>';
	}
echo '</table><br /><br />';

}
include('footer.php');?>