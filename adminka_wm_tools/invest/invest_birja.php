<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>����� �����, ����� ������������ ����������� �� �������</b></h3>';

if(isset($_GET["id"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="dell"){
		$sql = mysql_query("SELECT * FROM `tb_invest_birj` WHERE `id`='$id'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_invest_birj` WHERE `id`='$id'") or die(mysql_error());
			echo '<span id="info-msg" class="msg-error">�������� ������ �������, ����� ����� � �������!</span>';
		}

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).((isset($_GET["page"]) && intval($_GET["page"])!=1) ? "&page=".intval($_GET["page"]) : false).'");
			}, 1500);
			HideMsg("info-msg", 1500);
		</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).((isset($_GET["page"]) && intval($_GET["page"])!=1) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
	}
}

require("navigator/navigator.php");
$perpage = 25;
$sql_p = mysql_query("SELECT `id` FROM `tb_invest_birj`");
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
	echo '<th align="center">���� ��������</th>';
	echo '<th align="center">���������� �����</th>';
	echo '<th align="center">���� �� 1 �����</th>';
	echo '<th align="center">����� ���������</th>';
	echo '<th align="center"></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$sql = mysql_query("SELECT * FROM `tb_invest_birj` ORDER BY `id` DESC LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr>';
			echo '<td align="center"><b>'.$row["id"].'</b></td>';
			echo '<td align="center"><b>'.$row["seller_name"].'</b></td>';
			echo '<td align="center"><b>'.DATE("d.m.Y�. H:i", $row["time_op"]).'</b></td>';
			echo '<td align="center"><b>'.number_format($row["count_shares"], 0, ".", "`").' ��.</b></td>';
			echo '<td align="center"><b>'.number_format($row["money_one"], 2, ".", "`").' ���.</b></td>';
			echo '<td align="center"><b>'.number_format($row["money_sum"], 2, ".", "`").' ���.</b></td>';

			echo '<td width="95">';
				echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("����� � ������� ����� ��������� '.$row["seller_name"].', ID:'.$row["id"].' ?")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="dell">';
				echo '<input type="submit" value="�����" class="sub-red">';
			echo '</form>';
		echo '</td>';

		echo '</tr>';
	}
}else{
	echo '<tr>';
		echo '<td align="center" colspan="7"><b>���������� �� �������</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");

?>