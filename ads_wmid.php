<?php
$pagetitle = "������ ��������������";
include('header.php');
echo '<div align="justify">� ������ ������� ����������� ������ �������������� ������� &copy; '.ucfirst($_SERVER["HTTP_HOST"]).'. ��� ������������������ ��������� ��� ����� WMID ������.</div><br>';

require("navigator/navigator.php");
require ("config.php");

$perpage = 30;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_wmid` WHERE `moneys`>'0'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sql_wmid = mysql_query("SELECT * FROM `tb_ads_wmid` WHERE `moneys`>'0' ORDER BY `moneys` DESC LIMIT $start_pos,$perpage");
if(mysql_numrows($sql_wmid)>0) {
	$i=$start_pos;

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

	echo '<table class="tables">';
	echo '<thead><tr align="center"><th class="top">�</th><th class="top">WMID</th><th class="top">���-�� �������</th><th class="top">�� �����</th></tr></thead>';

	while($row=mysql_fetch_array($sql_wmid)) {
		$i++;
		echo'<tr align="center"><td>'.$i.'</td><td>'.substr($row["wmid"], 0, -3).'<font color="red">XXX</font></td><td>'.$row["zakazov"].'</td><td>'.$row["moneys"].' ���.</td></tr>';
	}
	echo'</table>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");
}else{
	echo '<table class="tables">';
	echo '<thead><tr align="center"><th class="top">�</th><th class="top">WMID</th><th class="top">���-�� �������</th><th class="top">�� �����</th></tr></thead>';
		echo'<tr align="center"><td colspan="4">������ �� �������</td></tr>';
	echo'</table>';
}

include('footer.php');?>