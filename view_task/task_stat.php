<?php

$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`!='' AND `user_name`='$username'"));
$pages_count = ceil($count / $perpage);

if ($page > $pages_count) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos=0;

$sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`!='' AND `user_name`='$username' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
$all_pay = mysql_num_rows($sql);

echo '<br><table class="tables">';
	echo '<thead><tr>';
		echo '<td colspan="9">������� ������� <b>'.$count.'</b>, �������� <b>'.$all_pay.'</b><br>';
		if($count>$perpage) {echo '<div align="left"><b>��������:</b> '; universal_link_bar($page, $count, $pages_count, 8, $sort, $sort_z, $type, $task_search, $task_name, $task_auto, $task_price); echo '</div>';}
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th align="center" class="top">id �������</th>';
	echo '<th align="center" colspan="2" class="top">������</th>';
	echo '<th align="center" class="top">IP</th>';
	echo '<th align="center" class="top">���-�� ������</th>';
	echo '<th align="center" class="top">������</th>';
	echo '<th align="center" class="top">���������</th>';
	echo '<th align="center" class="top">����</th>';
	echo '<th align="center" class="top">������</th>';
	echo '</tr></thead>';

if($all_pay>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr>';
		echo '<td align="center" style="border: 1px solid #1E90FF;"><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&amp;rid='.$row["ident"].'">'.$row["ident"].'</a></td>';

		if($row["status"]=="bad" && $row["kol_otv"]<3)
			echo '<td align="center" style="border: 1px solid #1E90FF;"><img src="img/no.png" border="0" alt="" align="middle" title="������� ���������, �� ������������� �� ���������� ����������" /></td border="0" style="border-left:0; solid #000;"><td align="center">������� ���������, �� ������������� �� ���������� ����������<br><b>'.$row["why"].'</b></td>';
		elseif($row["status"]=="bad" && $row["kol_otv"]>=3)
			echo '<td align="center" style="border: 1px solid #1E90FF;"><img src="img/no.png" border="0" alt="" align="middle" title="������� �� ���������, ���������� ����� ������� ������ �� ������" /></td border="0" style="border-left:0; solid #000;"><td align="center">������� �� ���������, ���������� ����� ������� ������ �� ������</td>';
		elseif($row["status"]=="good")
			echo '<td align="center" style="border: 1px solid #1E90FF;"><img src="img/yes.png" border="0" alt="" align="middle" title="������� ��������� � ������������� ���������� ����������" /></td border="0" style="border-left:0; solid #000;"><td align="center">������� ��������� � ������������� ���������� ����������</td>';
		elseif($row["status"]=="good_auto")
			echo '<td align="center" style="border: 1px solid #1E90FF;"><img src="img/yes.png" border="0" alt="" align="middle" title="������� ��������� � ������������� ���������� ����������" /></td border="0" style="border-left:0; solid #000;"><td align="center">������� ��������� � ������������ ������������� ��������</td>';
		elseif($row["status"]=="wait")
			echo '<td align="center" style="border: 1px solid #1E90FF;"><img src="img/help.png" border="0" alt="" align="middle" title="������� ��������� � ������� �������� ��������������" /></td border="0" style="border-left:0; solid #000;"><td align="center">������� ��������� � ������� �������� ��������������</td>';
		else{
			echo '<td align="center" style="border: 1px solid #1E90FF;"></td><td align="center"></td>';
		}

		echo '<td align="center" style="border: 1px solid #1E90FF;">'.$row["ip"].'</td>';
		echo '<td align="center" style="border: 1px solid #1E90FF;">'.$row["kol_otv"].'</td>';
		echo '<td align="center" style="border: 1px solid #1E90FF;">'.DATE("d.m.Y�. H:i", $row["date_start"]).'</td>';
		echo '<td align="center" style="border: 1px solid #1E90FF;">'.DATE("d.m.Y�. H:i", $row["date_end"]).'</td>';

		if($row["status"]=="bad")
			echo '<td align="center" style="border: 1px solid #1E90FF;">-</td>';
		elseif($row["status"]=="good")
			echo '<td align="center" style="border: 1px solid #1E90FF;">'.$row["pay"].' ���.</td>';
		elseif($row["status"]=="wait")
			echo '<td align="center" style="border: 1px solid #1E90FF;">-</td>';
		else{
			echo '<td align="center" style="border: 1px solid #1E90FF;">-</td>';
		}

		if($row["ocenka"] > 0) {
			echo '<td align="center" style="border: 1px solid #1E90FF;">'.$row["ocenka"].'</td>';
		}else{
			echo '<td align="center" style="border: 1px solid #1E90FF;">[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&amp;rid='.$row["ident"].'">�������������</a>]</td>';
		}

		echo '</tr>';
	}
	echo '<tr>';
		echo '<td colspan="9" style="border: 1px solid #1E90FF;">������� ������� <b>'.$count.'</b>, �������� <b>'.$all_pay.'</b><br>';
		if($count>$perpage) {echo '<div align="left"><b>��������:</b> '; universal_link_bar($page, $count, $pages_count, 8, $sort, $sort_z, $type, $task_search, $task_name, $task_auto, $task_price); echo '</div>';}
		echo '</td>';
	echo '</tr>';
}else{
	echo '<tr><td align="center" colspan="9">�� ��� �� ��������� �������!</td></tr>';
}
echo '</table><br>';

?>