<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� �����</b></h3>';

$sql = mysql_query("SELECT count(id) FROM `tb_users`");
$users_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_users` WHERE `lastlogdate2`>='".strtotime(DATE("d.m.Y", time()-1*24*60*60))."'");
$active_users = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_users` WHERE `joindate`='".DATE("d.m.Y",(time()-24*60*60))."'");
$users_v = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_users` WHERE `joindate`='".DATE("d.m.Y")."'");
$users_s = mysql_result($sql,0,0);

$sql = mysql_query("SELECT sum(money) FROM `tb_users` WHERE `money`>'0'");
$money_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT sum(money_rb) FROM `tb_users` WHERE `money_rb`>'0'");
$money_rb_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT sum(amount) FROM `tb_history` WHERE `date` LIKE '".date("d.m.Y",(time()-24*60*60))."%'AND tipo='0'");
$money_v = mysql_result($sql,0,0);

$sql = mysql_query("SELECT sum(amount) FROM `tb_history` WHERE `date` LIKE '".date("d.m.Y")."%'AND tipo='0'");
$money_s = mysql_result($sql,0,0);

$sql_t = mysql_query("SELECT `id`,`zdprice`,`totals`,`wait` FROM `tb_ads_task` ORDER BY `id` ASC");
if(mysql_num_rows($sql_t)) {
	$balance_task = 0;
	while ($row_t = mysql_fetch_row($sql_t)) {
		$balance_task = $balance_task + ($row_t["1"] * ($row_t["2"] + $row_t["3"]));

		//echo $row_t["0"]." | ".$balance_task." + ".$row_t["1"]." * ".$row_t["2"]." + ".$row_t["1"]." * ".$row_t["3"]."<br>";
	}
}else{
	$balance_task = 0;
}

$sql_kon_0 = mysql_query("SELECT `p1`,`p2`,`p3`,`p4`,`p5`,`p6`,`p7`,`p8`,`p9`,`p10`,`p11`,`p12`,`p13`,`p14`,`p15`,`p16`,`p17`,`p18`,`p19`,`p20` FROM `tb_refkonkurs` WHERE `status`='0' ORDER BY `id` ASC");
if(mysql_num_rows($sql_kon_0)) {
	$balance_kon_0 = 0;
	while ($row_kon_0 = mysql_fetch_row($sql_kon_0)) {
		$balance_kon_0 = $balance_kon_0 + $row_kon_0["0"] + $row_kon_0["1"] + $row_kon_0["2"] + $row_kon_0["3"] + $row_kon_0["4"] + $row_kon_0["5"] + $row_kon_0["6"] + $row_kon_0["7"] + $row_kon_0["8"] + $row_kon_0["9"] + $row_kon_0["10"] + $row_kon_0["11"] + $row_kon_0["12"] + $row_kon_0["13"] + $row_kon_0["14"] + $row_kon_0["15"] + $row_kon_0["16"] + $row_kon_0["17"] + $row_kon_0["18"] + $row_kon_0["19"];
	}
}else{
	$balance_kon_0 = 0;
}

$sql_kon_1 = mysql_query("SELECT `p1`,`p2`,`p3`,`p4`,`p5`,`p6`,`p7`,`p8`,`p9`,`p10`,`p11`,`p12`,`p13`,`p14`,`p15`,`p16`,`p17`,`p18`,`p19`,`p20` FROM `tb_refkonkurs` WHERE `status`='1' ORDER BY `id` ASC");
if(mysql_num_rows($sql_kon_1)) {
	$balance_kon_1 = 0;
	while ($row_kon_1 = mysql_fetch_row($sql_kon_1)) {
		$balance_kon_1 = $balance_kon_1 + $row_kon_1["0"] + $row_kon_1["1"] + $row_kon_1["2"] + $row_kon_1["3"] + $row_kon_1["4"] + $row_kon_1["5"] + $row_kon_1["6"] + $row_kon_1["7"] + $row_kon_1["8"] + $row_kon_1["9"] + $row_kon_1["10"] + $row_kon_1["11"] + $row_kon_1["12"] + $row_kon_1["13"] + $row_kon_1["14"] + $row_kon_1["15"] + $row_kon_1["16"] + $row_kon_1["17"] + $row_kon_1["18"] + $row_kon_1["19"];
	}
}else{
	$balance_kon_1 = 0;
}

echo '<table class="tables" style="width:800px;">';
echo '<thead><tr><th>��������</th><th width="100">��������</th><th width="25">��.</th></tr></thead>';
echo '<tr>';
	echo '<td><b>����� �������������:</b></td>';
	echo '<td align="right"><b style="color:#000; font-size:125%;">'.number_format($users_all,0,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>�������� ������������� �� 24 ����:</b></td>';
	echo '<td align="right"><b style="color:#000; font-size:125%;">'.number_format($active_users,0,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>���������������� �����:</b></td>';
	echo '<td align="right"><b style="color:#000; font-size:125%;">'.$users_v.'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>���������������� �������:</b></td>';
	echo '<td align="right"><b style="color:#000; font-size:125%;">'.$users_s.'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>��������� �����:</b></td>';
	echo '<td align="right"><b style="color:#000; font-size:125%;">'.number_format($money_v,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>��������� �������:</b></td>';
	echo '<td align="right"><b style="color:#000; font-size:125%;">'.number_format($money_s,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>����� ����� �� �������� �����:</b></td>';
	echo '<td align="right"><b style="color:#FF0000; font-size:125%;">'.number_format($money_all,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>����� ����� �� ��������� �����:</b></td>';
	echo '<td align="right"><b style="color:#FF0000; font-size:125%;">'.number_format($money_rb_all,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>����� ����� �� ������� �������:</b></td>';
	echo '<td align="right"><b style="color:#FF0000; font-size:125%;">'.number_format($balance_task,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>����� ����� �� ������� �� �������� ���������:</b></td>';
	echo '<td align="right"><b style="color:#FF0000; font-size:125%;">'.number_format($balance_kon_0,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '<tr>';
	echo '<td><b>����� ����� �� ������� �������� ���������:</b></td>';
	echo '<td align="right"><b style="color:#FF0000; font-size:125%;">'.number_format($balance_kon_1,2,".","`").'</b></td>';
	echo '<td align="right">���.</td>';
echo'</tr>';
echo '</table>';

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ ������������� �� �������</b></h3>';

$sql = mysql_query("SELECT `country_cod`, count(`country_cod`) FROM `tb_users` GROUP BY `country_cod` ORDER BY 2 DESC");
echo '<table class="tables" style="width:800px;">';
echo '<tr align="center">';
	echo '<th align="right">�</th>';
	echo '<th>����</th>';
	echo '<th align="left">������</th>';
	echo '<th width="20" nowrap="nowrap">���.</th>';
	echo '<th align="right" width="50" nowrap="nowrap">%</th>';
echo '</tr>';
if (mysql_num_rows($sql) > 0) {
	$i = 0;
	while ($row = mysql_fetch_row($sql)) {
		$i++;
		$percent = $row["1"] * 100 / $users_all;
		if ($row["0"] == "") { $flag = ""; } else { $flag = $row["0"]; }
		echo '<tr>';
			echo '<td align="right" width="20" nowrap="nowrap"><b>'.$i.'</b></td>';
			echo '<td align="center" width="20" nowrap="nowrap"><img src="//'.$_SERVER['HTTP_HOST'].'/img/flags/'.strtolower($row["0"]).'.gif" width="'.( (strtolower($row["0"])=="a1" | strtolower($row["0"])=="a2") ? "18" : "16").'" border="0" alt="" valign="baseline" title="'.get_country($row["0"]).'" style="margin:0; padding:0;" /></td>';
			echo '<td align="left"><b>'.get_country($row["0"]).'</b></td>';
			echo '<td align="right"><b style="color:green;">'.$row["1"].'</b></td>';
			echo '<td align="right"><b style="color:blue;">'.round(number_format($percent,2,".","`"),2).'</b> <b>%</b></td>';
		echo '</tr>';
	}
}
echo '</table>';

?>