<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������� ������� ����� �� �����</b></h3>';

require("navigator/navigator.php");
$perpage = 25;
$sql_p = mysql_query("SELECT `id` FROM `tb_invest_birj_history`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");
echo '<table class="tables" style="margin:2px auto;">';
echo '<thead>';
echo '<tr>';
	echo '<th align="center" width="70">#</th>';
	echo '<th align="center">��������</th>';
	echo '<th align="center">����������</th>';
	echo '<th align="center">���������� �����</th>';
	echo '<th align="center">���� ��������</th>';
	echo '<th align="center">����� ��������</th>';
	echo '<th align="center">��������� ��������</th>';
	echo '<th align="center">����� �������</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$sql = mysql_query("SELECT * FROM `tb_invest_birj_history` ORDER BY `id` DESC LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr>';
			echo '<td align="center"><b>'.$row["id"].'</b></td>';
			echo '<td align="center"><b>'.$row["seller_name"].'</b></td>';
			echo '<td align="center"><b>'.$row["buyer_name"].'</b></td>';
			echo '<td align="center"><b>'.number_format($row["count_shares"], 0, ".", "`").' ��.</b></td>';
			echo '<td align="center"><b>'.DATE("d.m.Y�. H:i", $row["time_op"]).'</b></td>';
			echo '<td align="center"><b>'.number_format($row["money"], 2, ".", "`").' ���.</b></td>';
			echo '<td align="center"><b>'.number_format($row["money_seller"], 2, ".", "`").' ���.</b></td>';
			echo '<td align="center"><b>'.number_format(($row["money"]-$row["money_seller"]), 2, ".", "`").' ���.</b></td>';
		echo '</tr>';
	}
}else{
	echo '<tr>';
		echo '<td align="center" colspan="8"><b>���������� �� �������</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");

?>