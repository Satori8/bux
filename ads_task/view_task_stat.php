<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;
$date_s = DATE("d.m.Y");
$date_v = DATE("d.m.Y", (time()-24*60*60));

echo '<b>��������� ���������� �� ������������� ������� #'.$rid.':</b><br><br>';

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$sql_vs = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='view' AND `ident`='$rid' AND `date`='$date_s'");
	$views_s = mysql_num_rows($sql_vs);
	$sql_vv = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='view' AND `ident`='$rid' AND `date`='$date_v'");
	$views_v = mysql_num_rows($sql_vv);

	$sql_cs = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='click' AND `ident`='$rid' AND `date`='$date_s'");
	$clicks_s = mysql_num_rows($sql_cs);
	$sql_cv = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='click' AND `ident`='$rid' AND `date`='$date_v'");
	$clicks_v = mysql_num_rows($sql_cv);

	if($views_s>0) {$ctr_s = (($clicks_s/$views_s) * 100);}else{$ctr_s=0;}
	if($views_v>0) {$ctr_v = (($clicks_v/$views_v) * 100);}else{$ctr_v=0;}
	if($row["views"]>0) {$ctr = (($row["clicks"]/$row["views"]) * 100);}else{$ctr=0;}

	echo '<table class="tables">';
		echo '<thead><tr align="center"><th align="center" colspan="2" class="top">������������ �������</th></tr></thead>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>��������:</b></td>';
			echo '<td>&nbsp;'.str_replace("\r\n","<br>", $row["zdname"]).'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>��� �������:</b></td>';
			if($row["zdtype"]==1)
				echo '<td>�����</td>';
			elseif($row["zdtype"]==2)
				echo '<td>����������� ��� ����������</td>';
			elseif($row["zdtype"]==3)
				echo '<td>����������� � �����������</td>';
			elseif($row["zdtype"]==4)
				echo '<td>������� � �����</td>';
			elseif($row["zdtype"]==5)
				echo '<td>������� � �����</td>';
			elseif($row["zdtype"]==6)
				echo '<td>�����������</td>';
			elseif($row["zdtype"]==7)
				echo '<td>�������� ������</td>';
			elseif($row["zdtype"]==9)
				echo '<td>���������� ����</td>';
			elseif($row["zdtype"]==10)
				echo '<td>YouTube</td>';
			elseif($row["zdtype"]==11)
				echo '<td>������ � ������</td>';
			elseif($row["zdtype"]==8)
				echo '<td>������</td>';
			else{
				echo '<td></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>URL:</b></td>';
			echo '<td><a href="'.$row["zdurl"].'" target="_blank">'.$row["zdurl"].'</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>������ ������ '; if($row["zdre"]>0) {echo $row["zdre"];}else{echo "XX";} echo ' �.:</b></td>';
			if($row["zdre"]>0) {
				echo '<td>&nbsp;��</td>';
			}else{
				echo '<td>&nbsp;���</td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>�������� ��������:</b></td>';
			if($row["zdcheck"]==1) {
				echo '<td>&nbsp;������ �����</td>';
			}else{
				echo '<td>&nbsp;�������������� �����</td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>��������� ����������:</b></td>';
			echo '<td>&nbsp;'.number_format($row["zdprice"],2,".","").' ���.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="right" height="30px"><b>��������� �� �������:</b></td>';
			echo '<td>&nbsp;';
			if($row["country_targ"]==1)
				echo '������ ������';
			elseif($row["country_targ"]==2)
				echo '������ �������';
			else{
				echo '����� ������';
			}
			echo '</td>';
		echo '</tr>';
	echo '</table>';

	echo '<table class="tables">';
		echo '<thead><tr align="center"><th align="center" colspan="2" class="top">���������� �� �������</th></tr></thead>';
		echo '<tr>';
			echo '<td align="center" width="200">';
				echo '<table>';
					echo '<tr><td align="left" width="170" style="border:none;"><b>��������� � ��������:</b></td><td align="right" style="border:none;"><b style="color:#54b948;">'.$row["goods"].'</b></td></tr>';
					echo '<tr><td align="left" width="170" style="border:none;"><b>�������� � ������:</b></td><td align="right" style="border:none;"><b style="color:#c24c2c;">'.$row["bads"].'</b></td></tr>';
					echo '<tr><td align="left" width="170" style="border:none;"><b>������������� ������:</b></td><td align="right" style="border:none;"><b style="color:#AAAAAA;">'.$row["wait"].'</b></td></tr>';
				echo '</table>';
			echo '</td>';
			echo '<td align="center">';
				echo '<table class="tables">';
					echo '<thead><tr align="center"><th align="center" class="top"></th><th align="center" class="top">����������</th><th align="center" class="top">������</th><th align="center" class="top">CTR</th></tr></thead>';
					echo '<tr><td align="left">�������:</td><td align="center">'.$views_s.'</td><td align="center">'.$clicks_s.'</td><td align="center">'.round($ctr_s, 2).'%</td></tr>';
					echo '<tr><td align="left">�����:</td><td align="center">'.$views_v.'</td><td align="center">'.$clicks_v.'</td><td align="center">'.round($ctr_v, 2).'%</td></tr>';
					echo '<tr><td align="left">�����:</td><td align="center">'.$row["views"].'</td><td align="center">'.$row["clicks"].'</td><td align="center">'.round($ctr, 2).'%</td></tr>';
				echo '</table>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
}else{
	echo '<fieldset class="errorp">������! � ��� ��� ������ �������!</fieldset>';
}

?>