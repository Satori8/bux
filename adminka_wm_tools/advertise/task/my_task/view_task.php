<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


echo '<b>������������ �������:</b><br /><br />';

echo '
	<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=add_task"><img src="../../img/add.png" border="0" alt="" align="middle" title="������� ����� �������" /></a>
	<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=add_task" title="������� ����� �������">������� ����� �������</a><br><br>
';

$date_s = strtotime(DATE("d.m.Y"));
$date_v = strtotime(DATE("d.m.Y",(time()-24*60*60)));

echo '<table align="center" border="0" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #000;">';
	echo '<tr bgcolor="#7EC0EE">';
	echo '<th align="center" colspan="2" style="border: 1px solid #000;">��������</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="80">������</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="80">��������� ����������</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="90">�������</th>';
	echo '<th align="center" style="border: 1px solid #000000;" width="80">������</th>';
	echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {

	$sql_n = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
	$nacenka_task = mysql_result($sql_n,0,0);

	while ($row = mysql_fetch_array($sql)) {
		echo '<tr>';
		echo '<td align="center" width="45">'.$row["id"].'</td>';
		echo '<td align="left" style="padding:5px;">';
			echo '<table width="100%" border="0">';
			echo '<tr>';
				echo '<td align="left" width="100%">
					<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=task_stat&amp;rid='.$row["id"].'">'.$row["zdname"].'</a><br>
					�������:&nbsp;<b>'.round($row["reiting"],2).'</b>&nbsp;|&nbsp;����� �������������:&nbsp;<b>'.$row["all_coments"].'</b><br><br>
					[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=edit_task&amp;rid='.$row["id"].'">�������������</a>]&nbsp;
					[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=addmoney_task&amp;rid='.$row["id"].'">���������&nbsp;������</a>]&nbsp;';

					if($row["status"]=="wait")
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'if(!confirm("�� ����� ������ ������� �������?")) return false;\'>�������</a>]';
					elseif($row["status"]=="pause" && $row["date_act"]>(time()-7*24*60*60))
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'alert("����� ������� �������, ��� ���������� ���������� � ��������� 7 ����. ���� �� ������� �� ����� �����, �� ������� ����� ����� ������� � ������ ������� ����� ��������� �� ������ ��������."); return false;\'>�������</a>]';
					elseif($row["status"]=="pay" | $row["date_act"]>(time()-7*24*60*60))
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'alert("����� ������� �������, ��� ���������� ���������� � ��������� 7 ����. ���� �� ������� �� ����� �����, �� ������� ����� ����� ������� � ������ ������� ����� ��������� �� ������ ��������."); return false;\'>�������</a>]';
					else{
						echo '[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=del_task&amp;rid='.$row["id"].'" onClick=\'if(!confirm("�� ����� ������ ������� �������?")) return false;\'>�������</a>]';
					}
				echo '</td>';

				if($row["zdre"]==3)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="������� ����� ��������� ������ 3 ����" /></td>';
				elseif($row["zdre"]==6)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="������� ����� ��������� ������ 6 �����" /></td>';
				elseif($row["zdre"]==12)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="������� ����� ��������� ������ 12 �����" /></td>';
				elseif($row["zdre"]==24)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="������� ����� ��������� ������ 24 ���� (1 �����)" /></td>';
				elseif($row["zdre"]==48)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="������� ����� ��������� ������ 48 ����� (2-� �����)" /></td>';
				elseif($row["zdre"]==72)
					echo '<td><img src="../../img/clock.png" border="0" alt="" align="middle" title="������� ����� ��������� ������ 72 ���� (3-� �����)" /></td>';
				else{
					echo '';
				}

				echo '<td>';
					if( $row["date_up"] < (time() - 1*60*60) ) {
						echo '<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=up_task&amp;rid='.$row["id"].'"><img src="../../img/up_task.png" border="0" alt="������� ���� ������� '.DATE("d.m.Y�. � H:i:s", $row["date_up"]).'" align="middle" title="������� ���� ������� '.DATE("d.m.Y�. � H:i:s", $row["date_up"]).'" /></a>';
					}else{
						echo '<img src="../../img/up_task.png" border="0" alt="������� ���� ������� '.DATE("d.m.Y�. � H:i:s", $row["date_up"]).'" align="middle" title="������� ���� ������� '.DATE("d.m.Y�. � H:i:s", $row["date_up"]).'" />';
					}
				echo '</td>';

			echo '</tr>';
			echo '</table>';
		echo '</td>';

		if($row["status"]=="end")
			echo '<td align="center"><span style="color: #FF0000;">�� �������</span><br>[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=active_task&amp;rid='.$row["id"].'">���������</a>]</td>';
		elseif($row["status"]=="pause" | $row["status"]=="wait")
			echo '<td align="center"><span style="color: #FF0000;">�� �������</span><br>[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=active_task&amp;rid='.$row["id"].'">���������</a>]</td>';
		elseif($row["status"]=="pay")
			echo '<td align="center"><span style="color: #006400;">�������</span><br>[<a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=pause_task&amp;rid='.$row["id"].'">�����</a>]</td>';
		else {echo '<td></td>';}

		echo '<td align="center">'.DATE("d.m.Y",$row["date_act"]).'<br>'.DATE("H:i",$row["date_act"]).'</td>';
		echo '<td align="center"><a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=task_get&amp;rid='.$row["id"].'">���������:&nbsp;'.$row["goods"].'</a><br>��������:&nbsp;'.$row["totals"].'<br><a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page=task_mod&amp;rid='.$row["id"].'">������:&nbsp;'.$row["wait"].'</td>';
		echo '<td align="center">'.number_format(( ($row["totals"] * $row["zdprice"]) * (100 + $nacenka_task) / 100 ),2,".","'").' ���.</td>';
		echo '</tr>';
	}
}else{
	echo '<tr><td align="center" colspan="6">��������� ������� ���!</td></tr>';
}
echo '</table>';

echo '<br><br><b style="color:#FF0000;">*</b> - �������� ������� �������� 1 ��� � ���. ��������� ������ �������� 0.25�.<br>';

?>