<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ �������� ���������</b></h3>';

$domen = ucfirst($_SERVER['HTTP_HOST']);
$domen = str_replace("wm","WM", $domen);
$domen = str_replace("ru","RU", $domen);

$attestat[100]='<font color="#000"><img src="../img/att/att_100.ico" alt="" align="absmiddle" border="0" /> �������� ����������</font>';
$attestat[110]='<font color="green"><img src="../img/att/att_110.ico" alt="" align="absmiddle" border="0" /> ���������� ��������</font>';
$attestat[120]='<font color="green"><img src="../img/att/att_120.ico" alt="" align="absmiddle" border="0" /> ��������� ��������</font>';
$attestat[130]='<font color="green"><img src="../img/att/att_130.ico" alt="" align="absmiddle" border="0" /> ������������ ��������</font>';
$attestat[135]='<font color="green"><img src="../img/att/att_135.ico" alt="" align="absmiddle" border="0" /> �������� ��������</font>';
$attestat[136]='<font color="green"><img src="../img/att/att_136.ico" alt="" align="absmiddle" border="0" /> �������� Capitaller</font>';
$attestat[140]='<font color="green"><img src="../img/att/att_140.ico" alt="" align="absmiddle" border="0" /> �������� ������������</font>';
$attestat[150]='<font color="green"><img src="../img/att/att_150.ico" alt="" align="absmiddle" border="0" /> �������� ������������</font>';
$attestat[170]='<font color="green"><img src="../img/att/att_170.ico" alt="" align="absmiddle" border="0" /> �������� �������</font>';
$attestat[190]='<font color="green"><img src="../img/att/att_190.ico" alt="" align="absmiddle" border="0" /> �������� �������</font>';
$attestat[300]='<font color="green"><img src="../img/att/att_300.ico" alt="" align="absmiddle" border="0" /> �������� ���������</font>';
$attestat[0]='<font color="red">����������</font>';
$attestat[1]='<font color="red">����������</font>';
$attestat[-1]='<font color="red">����������</font>';

// �������
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time' AND `howmany`='1'");
$auc_time = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_end_add' AND `howmany`='1'");
$auc_time_end_add = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_add' AND `howmany`='1'");
$auc_time_add = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_comis' AND `howmany`='1'");
$auc_comis = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_click_user' AND `howmany`='1'");
$auc_limit_click_user = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_last_user' AND `howmany`='1'");
$auc_limit_activ_last_user = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_max' AND `howmany`='1'");
$auc_max = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_all_user' AND `howmany`='1'");
$auc_limit_activ_all_user = mysql_result($sql,0,0);
// END �������


$sql_all = mysql_query("SELECT `username` FROM `tb_online` WHERE `username`!='' AND `page` LIKE '%auction.php%'") or die(mysql_error());
$online_game = mysql_num_rows($sql_all);

echo '<table align="center" border="0" width="100%" cellspacing="1" cellpadding="2">';
echo '<tr><td colspan="5">������������ �� ��������: (<b>'.$online_game.'</b>):&nbsp;';
	while ($row_o = mysql_fetch_array($sql_all)) {
		echo '<span style="color:blue;">'.$row_o["username"].',</span> ';
	}
echo '</td></tr>';
echo "</table>";

$sql = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `timer_end`>='".time()."' ORDER BY `timer_end` ASC LIMIT $auc_max");
if(mysql_num_rows($sql)>0) {

	echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="op" value="'.limpiar($_GET["op"]).'"><input type="submit" class="submit" value="�������� ������ ���������"></form></div><br>';
	echo '<script type="text/javascript" src="../scripts/auction.js"></script>';

	while ($row = mysql_fetch_array($sql)) {

		$sql_u = mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$row["referal"]."'");
		$row_u = mysql_fetch_array($sql_u);

		echo '<div align="center" style="padding: 0px 0px 20px 0px; margin: 0px 10px 20px 10px; border-bottom: 2px dotted #669966;">';
		echo '<table align="center" border="0" width="100%" cellspacing="3" cellpadding="3" style="line-height : 1.5em; border-collapse: collapse; border: 1px solid #1E90FF;">';
		echo '<tr bgcolor="#1E90FF" align="center"><th align="center" colspan="3">������� #'.$row["id"].' (��������: '.$row["username"].')</th></tr>';
		echo '<tr bgcolor="#ADD8E6">';
			echo '<td width="60%" style="padding:5px;">';
				echo '<table border="0" cellspacing="1" cellpadding="0">';
				echo '<tr><td align="left" style="padding-right:20px;"><u>���������� � ��������:</u></td><td></td></tr>';
				echo '<tr><td align="left">ID:</td><td align="left">#'.$row_u["id"].'</td></tr>';
				echo '<tr><td align="left">�����:</td><td align="left">'.$row_u["username"].'</td></tr>';
				echo '<tr><td align="left">���� �����������:</td><td align="left">'.DATE("d.m.Y�. H:i", $row_u["joindate2"]).'</td></tr>';
				echo '<tr><td align="left">���� ���������� �����:</td><td align="left">'.DATE("d.m.Y�. H:i", $row_u["lastlogdate2"]).'</td></tr>';
				echo '<tr><td align="left">�������� � ������� WMT:</td><td align="left">'.$attestat[$row_u["attestat"]].'</td></tr>';
				echo '<tr><td align="left">��������:</td><td align="left">'.$row_u["referals"].' - '.$row_u["referals2"].' - '.$row_u["referals3"].'</td></tr>';
				echo '<tr><td align="left">�����:</td><td align="left">'.number_format($row_u["visits"],0,".","'").'</td></tr>';
				echo '<tr><td align="left">�����������:</td><td align="left">'.number_format($row_u["visits_a"],0,".","'").'</td></tr>';
				echo '<tr><td align="left">�������:</td><td align="left">'.number_format($row_u["task_good"],0,".","'").'</td></tr>';
				echo '<tr><td align="left">�������:</td><td align="left">'.number_format($row_u["money_rek"],2,".","'").' ���.</td></tr>';
				if($row["inforef"]!="") {echo '<tr><td align="left">���. ���������� �� ��������:</td><td></td></tr><tr><td align="left" colspan="2">'.$row["inforef"].'</td></tr>';}
				echo '<tr><td align="left" style="padding-top:10px;"><u>����������  �� ��������:</u></td><td></td></tr>';
				echo '<tr><td align="left">������ ������:</td><td align="left"><b style="color: green;">'.number_format($row["stavka"],2,".","'").' ���.</b></td></tr>';
				if($row["kolstv"]>0) {
					echo '<tr><td align="left">���������� ������:</td><td align="left"><b>'.number_format($row["kolstv"],0,".","'").'</b></td></tr>';
					echo '<tr><td align="left">����� ��������:</td><td align="left"><b>'.$row["lider"].'</b></td></tr>';
				}else{
					echo '<tr><td colspan="2" align="left">������ ��� ������!</td></tr>';
				}
				echo '</table>';
			echo '</td>';
			echo '<td align="center" width="40%" style="padding:5px;">';
				echo '<table border="0" cellspacing="1" cellpadding="0">';
				echo '<tr><td><img src="../avatar/'.$row_u["avatar"].'" width="80" height="80" border="0" align="middle" alt="" /><br><br></td></tr>';
				echo '<tr><td><b>��������� �������� �����:</b></td></tr>';
				echo '<tr><td><span style="font-size:150%; font-weight:bold; color:blue;" class="end_time">'.DATE("i:s", ($row["timer_end"]-time())).'</SPAN>&nbsp;���.</td></tr>';
				echo '<tr><td><div id="form"><form method="POST" action=""><input type="hidden" name="id" value="'.$row["id"].'"><input type="submit" class="submit" value="������� ������"></form></div></td></tr>';
				echo '</table>';
			echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo '</div>';
	}
	echo '<script type="text/javascript">timer_init();</script>';
}else{
	echo '<div align="center" style="color:#FF0000; font-weight:bold;">� ����� ������ �������� ��������� ���.</div><br><br>';
	echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="op" value="'.limpiar($_GET["op"]).'"><input type="submit" class="submit" value="�������� ������ ���������"></form></div><br><br>';
}
?>