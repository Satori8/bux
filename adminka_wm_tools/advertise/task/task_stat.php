<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

$WHERE_ADD = false;
$WHERE_ADD_to_get = false;

require("navigator/navigator.php");
$perpage = 25;
$count =  mysql_numrows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`!='' AND  `type`='task' $WHERE_ADD")); 
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sql = mysql_query("SELECT * FROM `tb_ads_task_pay`  WHERE `status`!='' AND  `type`='task' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
$all_pay = mysql_num_rows($sql);
echo '<table class="adv-cab" style="margin:0; padding:0; margin-bottom:1px;"><tr>';
echo '<td align="left" width="230" valign="middle" style="border-right:solid 1px #DDDDDD;">';
	if($WHERE_ADD=="") {
		echo '�����: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_pay.'</b> �� <b>'.$count.'</b>';
	}else{
	 	echo '�������: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_pay.'</b> �� <b>'.$count.'</b>';
	}
echo '</td>';
echo '</table>';
if($count>$perpage) {universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op$WHERE_ADD_to_get");}
echo '<table class="tables">';
	echo '<thead><tr>';
	echo '</tr>';
	echo '<tr bgcolor="#42aaf">';
	echo '<th align="center" class="top">id �������</th>';
	echo '<th align="center" class="top">������</th>';
	echo '<th align="center" class="top">IP �����������<br/>�����</th>';
	echo '<th align="center" class="top">���-�� ������</th>';
	echo '<th align="center" class="top">������<br>���������</th>';
	echo '<th align="center" class="top">�����</th>';
	echo '</tr></thead>';

if($all_pay>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td align="center">'.$row["ident"].'<br/>������������� <b>'.$row["rek_name"].'</b></td>';

		if($row["status"]=="bad" && $row["kol_otv"]<3){
			echo '<td align="center"><img src="/img/no.png" border="0" alt="" align="middle" title="������� ���������, �� ������������� �� ���������� ����������" /><br/>������� ���������, �� ������������� �� ���������� ����������<br><b>'.$row["why"].'</b></td>';
		}elseif($row["status"]=="bad" && $row["kol_otv"]>=3){
			echo '<td align="center"><img src="/img/no.png" border="0" alt="" align="middle" title="������� �� ���������, ���������� ����� ������� ������ �� ������" /><br/>������� �� ���������, ���������� ����� ������� ������ �� ������</td>';
		}elseif($row["status"]=="good"){
			echo '<td align="center"><img src="/img/yes.png" border="0" alt="" align="middle" title="������� ��������� � ������������� ���������� ����������" /></td>';
		}elseif($row["status"]=="wait"){
			echo '<td align="center"><img src="/img/help.png" border="0" alt="" align="middle" title="������� ��������� � ������� �������� ��������������" /></td>';
		}elseif($row["status"]=="dorab"){
			echo '<td align="center"><img src="/img/no.png" border="0" alt="" align="middle" title="������� ����������� �� ���������" /><br/>������� ���������� �� ���������<br><b>'.$row["why"].'</td>';
		}else{
			echo '<td align="center"></td>';
		}

		echo '<td align="center">'.$row["ip"].'<br/><b>'.$row["user_name"].'</b></td>';
		echo '<td align="center">'.$row["kol_otv"].'</td>';
		echo '<td align="center">'.DATE("d.m.Y�. H:i", $row["date_start"]).'</br>';
		echo ''.DATE("d.m.Y�. H:i", $row["date_end"]).'</td>';

		if($row["status"]=="bad")
			echo '<td align="center">'.$row["ctext"].'</td>';
		elseif($row["status"]=="good")
			echo '<td align="center">'.$row["ctext"].'</td>';
		elseif($row["status"]=="wait")
			echo '<td align="center">'.$row["ctext"].'</td>';
		elseif($row["status"]=="dorab")
			echo '<td align="center">'.$row["why"].'</td>';
		else{
			
		}

		echo '</tr>';
	}
	echo '<tr>';
		echo '</td>';
	echo '</tr>';
}else{
	echo '<tr><td align="center" colspan="9">������ �� �������!</td></tr>';
}
echo '</table><br>';
if($count>$perpage) {universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op$WHERE_ADD_to_get");}
?>