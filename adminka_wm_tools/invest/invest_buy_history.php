<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������� ������� �����</b></h3>';

require("navigator/navigator.php");
$perpage = 25;
$sql_p = mysql_query("SELECT `id` FROM `tb_invest_history`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sort_param_arr = array('id','username','count_shares','time_op','money');
$order_by_arr = array('ASC','DESC');

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
				echo '<option value="count_shares" '.($sort_param=="count_shares" ? 'selected="selected"' : false).'>���������� �����</option>';
				echo '<option value="time_op" '.($sort_param=="time_op" ? 'selected="selected"' : false).'>���� ��������</option>';
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
	echo '<th align="center">���������� �����</th>';
	echo '<th align="center">���� ��������</th>';
	echo '<th align="center">����� ��������</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
$sql = mysql_query("SELECT * FROM `tb_invest_history` $sort_table LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr>';
			echo '<td align="center"><b>'.$row["id"].'</b></td>';
			echo '<td align="center"><b>'.$row["username"].'</b></td>';
			echo '<td align="center"><b>'.number_format($row["count_shares"], 0, ".", "`").' ��.</b></td>';
			echo '<td align="center"><b>'.DATE("d.m.Y�. H:i", $row["time_op"]).'</b></td>';
			echo '<td align="center"><b>'.number_format($row["money"], 2, ".", "`").' ���.</b></td>';
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