<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������� ���������� ������� ���������</b></h3>';

require("navigator/navigator.php");
$perpage = 25;
$sql_p = mysql_query("SELECT `id` FROM `tb_invest_money_in` WHERE `status`='1'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sort_param_arr = array('id','username','method_pay','time','money');
$order_by_arr = array('ASC','DESC');

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
$system_pay[10] = "�������� ����";

$sort_param = ( isset($_GET["sort_param"]) && array_search(htmlspecialchars(trim($_GET["sort_param"])), $sort_param_arr)!==false ) ? htmlspecialchars(trim($_GET["sort_param"])) : "id";
$order_by = ( isset($_GET["order_by"]) && array_search(htmlspecialchars(trim($_GET["order_by"])), $order_by_arr)!==false ) ? htmlspecialchars(trim($_GET["order_by"])) : "DESC";
$sort_table = "ORDER BY `$sort_param` $order_by";

echo '<table class="tables" id="newform">';
echo '<tr>';
	echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
		echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';

		echo '<td align="left" nowrap="nowrap" width="100">����������� ��:</td>';
		echo '<td align="center">';
			echo '<select name="sort_param" class="ok">';
				echo '<option value="id" '.($sort_param=="id" ? 'selected="selected"' : false).'>ID</option>';
				echo '<option value="username" '.($sort_param=="username" ? 'selected="selected"' : false).'>����� ���������</option>';
				echo '<option value="method_pay" '.($sort_param=="method_pay" ? 'selected="selected"' : false).'>������ ������</option>';
				echo '<option value="time" '.($sort_param=="time" ? 'selected="selected"' : false).'>���� ��������</option>';
				echo '<option value="money" '.($sort_param=="money" ? 'selected="selected"' : false).'>����� ��������</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="150">';
			echo '<select name="order_by" class="ok">';
				echo '<option value="ASC" '.($order_by=="ASC" ? 'selected="selected"' : false).'>�� �����������</option>';
				echo '<option value="DESC" '.($order_by=="DESC" ? 'selected="selected"' : false).'>�� ��������</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" class="sub-blue" value="�����������"></td>';
	echo '</form>';

	echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
		echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
		echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" class="sub-red" value="��������"></td>';
	echo '</form>';

echo '</tr>';
echo '</table>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op&sort_param=$sort_param&order_by=$order_by");
echo '<table class="tables" style="margin:2px auto;">';
echo '<thead>';
echo '<tr>';
	echo '<th align="center" width="70">#</th>';
	echo '<th align="center">����� ���������</th>';
	echo '<th align="center">������ ������</th>';
	echo '<th align="center">����� ����������</th>';
	echo '<th align="center">���� ��������</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$sql = mysql_query("SELECT * FROM `tb_invest_money_in` WHERE `status`='1' $sort_table LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr>';
			echo '<td align="center"><b>'.$row["id"].'</b></td>';
			echo '<td align="center"><b>'.$row["username"].'</b></td>';
			echo '<td align="center"><b>'.$system_pay[$row["method_pay"]].'</b></td>';
			echo '<td align="center"><b>'.number_format($row["money"], 2, ".", "`").' ���.</b></td>';
			echo '<td align="center"><b>'.DATE("d.m.Y�. H:i", $row["time"]).'</b></td>';
		echo '</tr>';
	}
}else{
	echo '<tr>';
		echo '<td align="center" colspan="5"><b>���������� �� �������</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op&sort_param=$sort_param&order_by=$order_by");

?>