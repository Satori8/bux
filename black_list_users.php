<?php
$pagetitle="������ ��������������� �������������";
include('header.php');
require("navigator/navigator.php");
require('config.php');

$perpage = 30;
$sql_p = mysql_query("SELECT `id` FROM `tb_black_users`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

$sql = mysql_query("SELECT * FROM `tb_black_users` ORDER BY `id` DESC LIMIT $start_pos,$perpage");
$all_users = mysql_num_rows($sql);

echo '<div align="justify">';
	echo '��� ���� ��� �� ����� ���, ��� ����� ��������� ���� ��������� ���� �� 15.00 ���., ����� ���� �������� �������������� � ������� �������������� �������.';
	echo '<br>��� ��������� ��������� ������ �������, WMID ������������ ����� ������ � ������ ������.';
	echo '<br>�� ������ ������ ������ 5 ����, ����� ���� ������� ����� ������. ������������� ��������� �� ����� �����, ������� �������� ����������� ���������������.';
echo '</div>';

echo '<table style="margin:0; padding:0; margin-bottom:10px; margin-top:15px;"><tr>';
echo '<td valign="top">';
	echo '������������� ������������� �����: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_users.'</b> �� <b>'.$count.'</b>';
echo '</td>';
echo '</table>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

echo '<table class="tables">';
echo '<thead><tr align="center">';
	echo '<th>#</th>';
	echo '<th>�����</th>';
	echo '<th>������� ����������</th>';
	echo '<th>���� ����������</th>';
echo '</tr></thead>';

if($all_users>0) {
	$i=$start_pos;

	while ($row = mysql_fetch_array($sql)) {
		$i++;

		echo '<tr align="center">';
			echo '<td>'.$i.'</td>';
			echo '<td><b>'.$row["name"].'</b></td>';
			echo '<td>'.$row["why"].'</td>';
			echo '<td>'.DATE("d.m.Y H:i", $row["time"]).'</td>';
		echo '</tr>';
	}
	echo '</table>';
}else{
	echo '<tr>';
		echo '<td colspan="4" align="center"><b>��������������� ������������� ���</b></td>';
	echo '</tr>';
	echo '</table>';
}
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

include('footer.php');
?>