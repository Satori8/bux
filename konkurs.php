<?php
require_once('.zsecurity.php');
$pagetitle = "��������";
include('header.php');
require('config.php');

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='status'");
$konk_ads_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_start'");
$konk_ads_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='date_end'");
$konk_ads_date_end = mysql_result($sql,0,0);

### ������� �������������� �2 ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='status'");
$konk_ads_big_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_start'");
$konk_ads_big_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='date_end'");
$konk_ads_big_date_end = mysql_result($sql,0,0);
### ������� �������������� �2 ###

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='date_start'");
$konk_ref_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='date_end'");
$konk_ref_date_end = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='status'");
$konk_ref_status = mysql_result($sql,0,0);


$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='status'");
$konk_click_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_start'");
$konk_click_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_end'");
$konk_click_date_end = mysql_result($sql,0,0);

### ������� ������ ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='status'");
$konk_test_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_start'");
$konk_test_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='date_end'");
$konk_test_date_end = mysql_result($sql,0,0);
### ������� ������ ###

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='status'");
$konk_youtub_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_start'");
$konk_youtub_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='date_end'");
$konk_youtub_date_end = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
$konk_task_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
$konk_task_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
$konk_task_date_end = mysql_result($sql,0,0);

### ������� ����������� ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='status'");
$konk_hit_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='date_start'");
$konk_hit_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='date_end'");
$konk_hit_date_end = mysql_result($sql,0,0);
### ������� ����������� ###

### ������� �� ���������� ������ � �������� ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='status'");
$konk_serf_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_start'");
$konk_serf_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='date_end'");
$konk_serf_date_end = mysql_result($sql,0,0);
### ������� �� ���������� ������ � �������� ###

### ����������� ������� ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'");
$konk_complex_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'");
$konk_complex_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'");
$konk_complex_date_end = mysql_result($sql,0,0);
### ����������� ������� ###

### ������� �� ������ ������� ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='status'");
$konk_ads_task_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='date_start'");
$konk_ads_task_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='date_end'");
$konk_ads_task_date_end = mysql_result($sql,0,0);
### ������� �� ������ ������� ###

### ������� �� ��������� �������� ###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='status'");
$konk_clic_ref_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='date_start'");
$konk_clic_ref_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='date_end'");
$konk_clic_ref_date_end = mysql_result($sql,0,0);
### ������� �� ��������� �������� ###

### ������� �� ��������� �������� ����� ���������###
$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='status'");
$konk_best_ref_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_start'");
$konk_best_ref_date_start = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='date_end'");
$konk_best_ref_date_end = mysql_result($sql,0,0);
### ������� �� ��������� �������� ����� ��������� ###

$type_kon = ( isset($_GET["type"])  && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|",  limpiar(htmlspecialchars(trim($_GET["type"])))) ) ? limpiar(htmlspecialchars(trim($_GET["type"]))) : "ads";

echo '<table class="tables">';
echo '<tr align="center">';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ref" '.($type_kon=="ref" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>������ �������</a></b>';
		echo ( ($konk_ref_status==1 && $konk_ref_date_start<=time() && $konk_ref_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ads" '.($type_kon=="ads" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�������������� �1</a></b>';
		echo ( ($konk_ads_status==1 && $konk_ads_date_start<=time() && $konk_ads_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	echo '<td height="40" width="33.3%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ads_big" '.($type_kon=="ads_big" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�������������� �2</a></b>';
		echo ( ($konk_ads_big_status==1 && $konk_ads_big_date_start<=time() && $konk_ads_big_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	
echo '</tr>';
echo '<tr align="center">';
echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=click" '.($type_kon=="click" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>��������</a></b>';
		echo ( ($konk_click_status==1 && $konk_click_date_start<=time() && $konk_click_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
    echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=task" '.($type_kon=="task" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>���������� �������</a></b>';
		echo ( ($konk_task_status==1 && $konk_task_date_start<=time() && $konk_task_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
    echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=youtub" '.($type_kon=="youtub" ? 'style="cursor:pointer; color:#FF0000;"' : false).'><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> �������</a></b>';
		echo ( ($konk_youtub_status==1 && $konk_youtub_date_start<=time() && $konk_youtub_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	
	echo '</tr>';
echo '<tr align="center">';
//echo '<td height="40" width="25%" nowrap="nowrap">';
		//echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=test" '.($type_kon=="test" ? 'style="cursor:pointer; color:#FF0000;"' : false).'> ����������� ������</a></b>';
		//echo ( ($konk_test_status==1 && $konk_test_date_start<=time() && $konk_test_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	//echo '</td>';
	echo '<td height="40" width="25%" nowrap="nowrap" colspan="2">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=serf" '.($type_kon=="serf" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�� ���������� ������ � ��������</a></b>';
		echo ( ($konk_serf_status==1 && $konk_serf_date_start<=time() && $konk_serf_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	echo '<td height="40" width="30%" nowrap="nowrap" colspan="3">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=complex" '.($type_kon=="complex" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�����������</a></b>';
		echo ( ($konk_complex_status==1 && $konk_complex_date_start<=time() && $konk_complex_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>'; 
	
	/*echo '<td height="40" width="30%" nowrap="nowrap" colspan="0">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=hit" '.($type_kon=="hit" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�����������</a></b>';
		echo ( ($konk_hit_status==1 && $konk_hit_date_start<=time() && $konk_hit_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>'; */
	
//	echo '</tr>';
	//echo '<tr align="center">';
	//echo '<td height="40" width="33.3%" nowrap="nowrap" >';
		//echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=ads_task" '.($type_kon=="ads_task" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�� ������ �������</a></b>';
		//echo ( ($konk_ads_task_status==1 && $konk_ads_task_date_start<=time() && $konk_ads_task_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	echo '</tr>';
	/*echo '<tr align="center">';
	echo '<td height="40" width="25%" nowrap="nowrap" >';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=clic_ref" '.($type_kon=="clic_ref" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>������ �������</a></b>';
		echo ( ($konk_clic_ref_status==1 && $konk_clic_ref_date_start<=time() && $konk_clic_ref_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=best_ref" '.($type_kon=="best_ref" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>������ �������</a></b>';
		echo ( ($konk_best_ref_status==1 && $konk_best_ref_date_start<=time() && $konk_best_ref_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	
		//echo '</tr>';
	
	//echo '<tr align="center">';
	echo '<td height="40" width="25%" nowrap="nowrap">';
		echo '<b style="text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;"><a href="/konkurs?type=serf" '.($type_kon=="serf" ? 'style="cursor:pointer; color:#FF0000;"' : false).'>�� ���������� ������ � ��������</a></b>';
		echo ( ($konk_serf_status==1 && $konk_serf_date_start<=time() && $konk_serf_date_end>=time()) ? '<div style="color:#ff7f50;">[��������]</div>' : '<div style="color:#CD5C5C;">[��� ���������]</div>');
	echo '</td>';
	echo '</tr>';*/
echo '</table><br>';

$domen = ($_SERVER["HTTP_HOST"]);
$MY_USERNAME = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

function get_avatar($USER_WMID) {
	$sql_us = mysql_query("SELECT `avatar` FROM `tb_users` WHERE `wmid`='$USER_WMID'");
	if(mysql_num_rows($sql_us)>0) {
		$row_us = mysql_fetch_row($sql_us);
		return $row_us["0"];
	}else{
		return "no.png";
	}
}

function count_text($count, $text1, $text2, $text3, $text) {
	if($count>0) {
		if( ($count>=10) && ($count<=20) ) {
			return "<b>$count</b>$text1";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "<b>$count</b>$text2"; break;
				case 2: case 3: case 4: return "<b>$count</b>$text3"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b>$text1"; break;
			}
		}
	}
}


if($type_kon=="ads") {
	### ������� �������������� �1 ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='title'");
	$konkurs_title_ads = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='min_do'");
	$konk_ads_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='all_count_prize'");
	$konk_ads_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='count_prize'");
	$konk_ads_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='type_prize'");
	$konk_ads_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ads_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ads_status==1 && $konk_ads_date_end>=(time()) && $konk_ads_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ads!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ads.'</h1>';

               
		echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� ��������������</b></h3>';
		echo '<b>������� ���������� ��������:</b> ��� ������� � �������� ���������� ���� ������������������ ������������� ������� <b><a href="/">'.$domen.'</a></b>. � �������� ����������� �����, ����������� �� ���������� ����� ������� � ������������ �� ���� ������� � ���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������.<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_ads_all_count_prize.'</b><br>';
		echo '����������� ����������� ����� ��� ������� � �������� - <b>'.number_format($konk_ads_min,2,'.',' ').' ���.</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_all_count_prize; $i++) {
				if($konk_ads_prizes[$y][$i]==0) {
					$_Ads_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;��&nbsp;���.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;���.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;���.&nbsp;����", "");
					if($y==2) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;��&nbsp;����.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;����.&nbsp;����", "&nbsp;���.&nbsp;��&nbsp;����.&nbsp;����", "");
					if($y==3) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
					if($y==4) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");
					if($y==5) $_Ads_Prizes[$y][$i] = count_text(number_format($konk_ads_prizes[$y][$i],0,'.',' '), "%&nbsp;��&nbsp;�����������&nbsp;�����&nbsp;", "%&nbsp;��&nbsp;�����������&nbsp;�����&nbsp;", "%&nbsp;��&nbsp;�����������&nbsp;�����&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_ads_all_count_prize; $i++) {
			$_Ads_Prizes_New = $_Ads_Prizes[1][$i]."|".$_Ads_Prizes[2][$i]."|".$_Ads_Prizes[5][$i]."|".$_Ads_Prizes[3][$i]."|".$_Ads_Prizes[4][$i];
			$_Ads_Prizes_New = explode("|", $_Ads_Prizes_New);
			$_Ads_Prizes_New = trim(implode(" ", $_Ads_Prizes_New));
			$_Ads_Prizes_New = str_replace("  ", " ", $_Ads_Prizes_New);
			$_Ads_Prizes_New = str_replace(" ", "&nbsp;", $_Ads_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Ads_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_ads_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_ads_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">�����</th><th class="top" colspan="2">������������</th><th class="top">��������� �� �������</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ads`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ads_all_count_prize && $row["1"]>=$konk_ads_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],2,'.',' ').'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.$row["0"].'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],2,'.',' ').'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ads`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],2,'.',' ').'</b></td>';
						echo '</tr>';
					}
				}
			}

		echo '</table><br><br>';
	}


	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ads' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">��������� �� �������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 2, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                          
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
echo '</div>';
	### ������� �������������� �1 ###

}elseif($type_kon=="ads_big") {
	### ������� �������������� �2 ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='title'");
	$konkurs_title_ads_big = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='min_do'");
	$konk_ads_big_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='all_count_prize'");
	$konk_ads_big_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='count_prize'");
	$konk_ads_big_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='type_prize'");
	$konk_ads_big_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_big' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ads_big_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ads_big_status==1 && $konk_ads_big_date_end>=(time()) && $konk_ads_big_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ads_big!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ads_big.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: �������� �������������</b></h3>';
		echo '<b>������� ���������� ��������:</b> ��� ������� � �������� ���������� ���� ������������������ ������������� ������� <b><a href="/">'.$domen.'</a></b>. � �������� ����������� �����, ����������� �� ���������� ����� ������� � ������������ �� ���� ������� � ���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������.<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_ads_big_all_count_prize.'</b><br>';
		echo '����������� ����������� ����� ��� ������� � �������� - <b>'.number_format($konk_ads_big_min, 2, ".", "`").' ���.</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_big_all_count_prize; $i++) {
				if($konk_ads_big_prizes[$y][$i]==0) {
					$_Ads_Big_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],2, ".", "`"), " ���.[���.����]", " ���.[���.����]", " ���.[���.����]", "");
					if($y==2) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],2, ".", "`"), " ���.[����.����]", " ���.[����.����]", " ���.[����.����]", "");
					if($y==3) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],0, ".", "`"), " % �� ����������� �����[���.����]", " % �� ����������� �����[���.����]", " % �� ����������� �����[���.����]", "");
					if($y==4) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],0, ".", "`"), " % �� ����������� �����[����.����]", " % �� ����������� �����[����.����]", " % �� ����������� �����[����.����]", "");
					if($y==5) $_Ads_Big_Prizes[$y][$i] = count_text(number_format($konk_ads_big_prizes[$y][$i],0, ".", "`"), " ������", " ����", " �����", "");
				}
			}
		}

		for($i=0; $i<$konk_ads_big_all_count_prize; $i++) {
			$_Ads_Big_Prizes_New = array();
			for($y=1; $y<=count($_Ads_Big_Prizes); $y++) {
				if($_Ads_Big_Prizes[$y][$i]!=false) $_Ads_Big_Prizes_New[].= $_Ads_Big_Prizes[$y][$i];
			}
			$_Ads_Big_Prizes_New = implode(" +&nbsp;", $_Ads_Big_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Ads_Big_Prizes_New.'<br><br>';
		}

		echo '<b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_ads_big_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_ads_big_date_end-1).'';

		echo '<table class="tables_inv">';
			echo '<thead><tr align="center">';
				echo '<th>�����</th>';
				echo '<th colspan="2">������������</th>';
				echo '<th>��������� �� �������</th>';
				echo '<th>����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ads_big`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ads_big_all_count_prize && $row["konkurs_ads_big"]>=$konk_ads_big_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_ads_big_prizes[1][$idkonc-1] + $row["konkurs_ads_big"] * $konk_ads_big_prizes[3][$idkonc-1]/100);
						$sum_prize_rb = ($konk_ads_big_prizes[2][$idkonc-1] + $row["konkurs_ads_big"] * $konk_ads_big_prizes[4][$idkonc-1]/100);
						$sum_prize_reit = ($konk_ads_big_prizes[5][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.ucfirst($row["username"]).'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_big"],2,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ads_big`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_big"], 2, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads_big' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ads_big' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">��������� �� �������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 2, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}

	### ������� �������������� �2 ### 

}elseif($type_kon=="ads_task") {
	### ������� �� ������ ������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='title'");
	$konkurs_title_ads_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='min_do'");
	$konk_ads_task_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='all_count_prize'");
	$konk_ads_task_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='count_prize'");
	$konk_ads_task_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='type_prize'");
	$konk_ads_task_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ads_task' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ads_task_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ads_task_status==1 && $konk_ads_task_date_end>=(time()) && $konk_ads_task_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ads_task!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ads_task.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� �� ������ �������</b></h3>';
		echo '<b>������� ���������� ��������:</b> ��� ������� � �������� ���������� ���� ������������������ ������������� ������� <b><a href="/">'.$domen.'</a></b>. � �������� ����������� ����� �������������� ������������� �� ����������� � ���������� ������� � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b>. ������� ��������������, �������������, ������� ������� � �������� �� �����������!<br><br>';

		echo '<span style="color:#FF0000;">��������:</span> ��������������� ������������ � ������������� � �������� �� ���������!!!<br><br>';
		
		echo '���������� �������� ���� - <b>'.$konk_ads_task_all_count_prize.'</b><br>';
		echo '����������� ����� �������������� ������������� ��� ������� � �������� - <b>'.number_format($konk_ads_task_min, 2, ".", "`").' ���.</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_ads_task_all_count_prize; $i++) {
				if($konk_ads_task_prizes[$y][$i]==0) {
					$_Ads_Task_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],2, ".", "`"), " ���.[���.����]", " ���.[���.����]", " ���.[���.����]", "");
					if($y==2) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],2, ".", "`"), " ���.[����.����]", " ���.[����.����]", " ���.[����.����]", "");
					if($y==3) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],0, ".", "`"), " % �� ����������� �����[���.����]", " % �� ����������� �����[���.����]", " % �� ����������� �����[���.����]", "");
					if($y==4) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],0, ".", "`"), " % �� ����������� �����[����.����]", " % �� ����������� �����[����.����]", " % �� ����������� �����[����.����]", "");
					if($y==5) $_Ads_Task_Prizes[$y][$i] = count_text(number_format($konk_ads_task_prizes[$y][$i],0, ".", "`"), " ������", " ����", " �����", "");
				}
			}
		}

		for($i=0; $i<$konk_ads_task_all_count_prize; $i++) {
			$_Ads_Task_Prizes_New = array();
			for($y=1; $y<=count($_Ads_Task_Prizes); $y++) {
				if($_Ads_Task_Prizes[$y][$i]!=false) $_Ads_Task_Prizes_New[].= $_Ads_Task_Prizes[$y][$i];
			}
			$_Ads_Task_Prizes_New = implode(" +&nbsp;", $_Ads_Task_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Ads_Task_Prizes_New.'<br><br>';
		}

		echo '<b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_ads_task_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_ads_task_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>�����</th>';
				echo '<th colspan="2">�������������</th>';
				echo '<th>����� ��������������</th>';
				echo '<th>����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ads_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_task` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ads_task_all_count_prize && $row["konkurs_ads_task"]>=$konk_ads_task_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_ads_task_prizes[1][$idkonc-1] + $row["konkurs_ads_task"] * $konk_ads_task_prizes[3][$idkonc-1]/100);
						$sum_prize_rb = ($konk_ads_task_prizes[2][$idkonc-1] + $row["konkurs_ads_task"] * $konk_ads_task_prizes[4][$idkonc-1]/100);
						$sum_prize_reit = ($konk_ads_task_prizes[5][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.ucfirst($row["username"]).'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_task"],2,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ads_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_task` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_ads_task"], 2, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ads_task' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ads_task' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�������������</th>';
					echo '<th class="top">����� ��������������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 2, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}

	### ������� �� ������ ������� ### 
	
}elseif($type_kon=="ref") {

	### ������� �������� ��������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='title'");
	$konkurs_title_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_ref'");
	$konk_ref_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_click'");
	$konk_ref_min_click = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='min_day_act'");
	$konk_ref_min_day_act = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='all_count_prize'");
	$konk_ref_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='count_prize'");
	$konk_ref_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='type_prize'");
	$konk_ref_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='ref' AND `item`='prizes' AND `howmany`='$y'");
		$konk_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_ref_status==1 && $konk_ref_date_end>=(time()) && $konk_ref_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_ref!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_ref.'</h1>';

               
		echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: "����������� �������� ���������"</b></h3>';
		echo '<br><b>�������� � ������� ���������� ��������.</b><br>� �������� ����������� ������ �������� ��������. �������� ��������� ��� �������, ������� ����������������� � ������ ���������� �������� � <b>������ �� ����� '.$konk_ref_min_click.' ������������ ������ �� ������������ �������</b>, � ����� <b>������� ������� �� ������� '.$konk_ref_min_day_act.' ����(���) �����</b>.<br>';
		echo '����� �������, �� ������ ��������� �������� ����������� ������ �� ��������, ������� ������������� ��������� ���� ��������!<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_ref_all_count_prize.'</b><br>';
		echo '����������� ���������� ������������ �������� ��������� ��� ������� � �������� - <b>'.number_format($konk_ref_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_ref_all_count_prize; $i++) {
				if($konk_ref_prizes[$y][$i]==0) {
					$_Ref_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Ref_Prizes[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "");
					if($y==2) $_Ref_Prizes[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
					if($y==3) $_Ref_Prizes[$y][$i] = count_text(number_format($konk_ref_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_ref_all_count_prize; $i++) {
			$_Ref_Prizes_New = $_Ref_Prizes[1][$i]."|".$_Ref_Prizes[2][$i]."|".$_Ref_Prizes[3][$i];
			$_Ref_Prizes_New = explode("|", $_Ref_Prizes_New);
			$_Ref_Prizes_New = trim(implode(" ", $_Ref_Prizes_New));
			$_Ref_Prizes_New = str_replace("  ", " ", $_Ref_Prizes_New);
			$_Ref_Prizes_New = str_replace(" ", "&nbsp;+&nbsp;", $_Ref_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Ref_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_ref_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_ref_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">�����</th><th class="top" colspan="2">������������</th><th class="top">���������� �������� ���������</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ref` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_ref_all_count_prize && $row["1"]>=$konk_ref_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.$row["0"].'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ref` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.$row["0"].'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='ref' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='ref' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">���������� ���������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                         
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
  echo '</div>';
	### ������� ������ ������� ###

}elseif($type_kon=="click") {

	### ������� �������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='title'");
	$konkurs_title_click = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='min_do'");
	$konk_click_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='all_count_prize'");
	$konk_click_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='count_prize'");
	$konk_click_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='type_prize'");
	$konk_click_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='prizes' AND `howmany`='$y'");
		$konk_click_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_click_status==1 && $konk_click_date_end>=(time()) && $konk_click_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_click!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_click.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: �������� ������</b></h3>';
		echo '<b>������� ���������� ��������:</b> � �������� ����������� ���������� ������ �� ������������ ������� � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b>.<br>�� ��������� ��������, ����� �� ����� ������������ ������� �� �����������. ';
		echo '����� ��� ������������� ������� �� ����������� ����� �� ������� � ������������� ����������� �� �������, � ����������� "���������� ������������� ��� ��������" � "���������� ������ ��������".<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_click_all_count_prize.'</b><br>';
		echo '����������� ���������� ������ ��� ������� � �������� - <b>'.number_format($konk_click_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_click_all_count_prize; $i++) {
				if($konk_click_prizes[$y][$i]==0) {
					$_Click_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Click_Prizes[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "");
					if($y==2) $_Click_Prizes[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
					if($y==3) $_Click_Prizes[$y][$i] = count_text(number_format($konk_click_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_click_all_count_prize; $i++) {
			$_Click_Prizes_New = $_Click_Prizes[1][$i]."|".$_Click_Prizes[2][$i]."|".$_Click_Prizes[3][$i];
			$_Click_Prizes_New = explode("|", $_Click_Prizes_New);
			$_Click_Prizes_New = trim(implode(" ", $_Click_Prizes_New));
			$_Click_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Click_Prizes_New);
			$_Click_Prizes_New = str_replace(" ", " ", $_Click_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Click_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_click_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_click_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">�����</th><th class="top" colspan="2">������������</th><th class="top">������</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_click`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_click` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_click_all_count_prize && $row["1"]>=$konk_click_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_click`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_click` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='click' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='click' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">������� ������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
echo '</div>';
	### ������� �������� ###

}elseif($type_kon=="test") {

	### ������� ������ ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='title'");
	$konkurs_title_test = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='min_do'");
	$konk_test_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='all_count_prize'");
	$konk_test_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='count_prize'");
	$konk_test_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='type_prize'");
	$konk_test_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='test' AND `item`='prizes' AND `howmany`='$y'");
		$konk_test_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_test_status==1 && $konk_test_date_end>=(time()) && $konk_test_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_test!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_test.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: ����������� ������</b></h3>';
		echo '<b>������� ���������� ��������:</b> � �������� ����������� ���������� ��������� ������ � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b><br><br>';

		echo '���������� �������� ���� - <b>'.$konk_test_all_count_prize.'</b><br>';
		echo '����������� ���������� ����������� ��� ������� � �������� - <b>'.number_format($konk_test_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_test_all_count_prize; $i++) {
				if($konk_test_prizes[$y][$i]==0) {
					$_Test_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Test_Prizes[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "");
					if($y==2) $_Test_Prizes[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
					if($y==3) $_Test_Prizes[$y][$i] = count_text(number_format($konk_test_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_test_all_count_prize; $i++) {
			$_Test_Prizes_New = $_Test_Prizes[1][$i]."|".$_Test_Prizes[2][$i]."|".$_Test_Prizes[3][$i];
			$_Test_Prizes_New = explode("|", $_Test_Prizes_New);
			$_Test_Prizes_New = trim(implode(" ", $_Test_Prizes_New));
			$_Test_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Test_Prizes_New);
			$_Test_Prizes_New = str_replace(" ", " ", $_Test_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Test_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_test_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_test_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">�����</th><th class="top" colspan="2">������������</th><th class="top">�����������</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_test`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_test` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_test_all_count_prize && $row["1"]>=$konk_test_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_test`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_test` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='test' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='test' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">������� ������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
echo '</div>';
	### ������� ������ ###
	
}elseif($type_kon=="youtub") {

	### ������� YOUTUBE ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='title'");
	$konkurs_title_youtub = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='min_do'");
	$konk_youtub_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='all_count_prize'");
	$konk_youtub_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='count_prize'");
	$konk_youtub_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='type_prize'");
	$konk_youtub_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='youtub' AND `item`='prizes' AND `howmany`='$y'");
		$konk_youtub_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_youtub_status==1 && $konk_youtub_date_end>=(time()) && $konk_youtub_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_youtub!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_youtub.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: �������� ����� ������</b></h3>';
		echo '<b>������� ���������� ��������:</b> � �������� ����������� ���������� ���������� � ����� �������� YouTube � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b>.<br>�� ��������� ��������, ��������� ����� ������������ �� �����������. ';
		echo '����� ��� ������������� ������� �� ����������� ��������� ������������ � ������������� ����������� �� �������, � ����������� "���������� ������������� ��� ��������" � "���������� ������ ��������".<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_youtub_all_count_prize.'</b><br>';
		echo '����������� ���������� ���������� ��� ������� � �������� - <b>'.number_format($konk_youtub_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_youtub_all_count_prize; $i++) {
				if($konk_youtub_prizes[$y][$i]==0) {
					$_Youtub_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Youtub_Prizes[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "");
					if($y==2) $_Youtub_Prizes[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
					if($y==3) $_Youtub_Prizes[$y][$i] = count_text(number_format($konk_youtub_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_youtub_all_count_prize; $i++) {
			$_Youtub_Prizes_New = $_Youtub_Prizes[1][$i]."|".$_Youtub_Prizes[2][$i]."|".$_Youtub_Prizes[3][$i];
			$_Youtub_Prizes_New = explode("|", $_Youtub_Prizes_New);
			$_Youtub_Prizes_New = trim(implode(" ", $_Youtub_Prizes_New));
			$_Youtub_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Youtub_Prizes_New);
			$_Youtub_Prizes_New = str_replace(" ", " ", $_Youtub_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Youtub_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_youtub_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_youtub_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">�����</th><th class="top" colspan="2">������������</th><th class="top">����������</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_youtub`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_youtub` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_youtub_all_count_prize && $row["1"]>=$konk_youtub_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_youtub`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_youtub` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='youtub' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='youtub' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">������� ����������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
echo '</div>';
	### ������� YOUTUBE ###
	
}elseif($type_kon=="task") {

	### ������� �� ���������� ������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='title'");
	$konkurs_title_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='min_do'");
	$konk_task_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='all_count_prize'");
	$konk_task_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='count_prize'");
	$konk_task_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='type_prize'");
	$konk_task_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='prizes' AND `howmany`='$y'");
		$konk_task_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_task_status==1 && $konk_task_date_end>=(time()) && $konk_task_date_start<(time())) {
                 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_task!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_task.'</h1>';

                
		//echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� �� ���������� �������</b></h3>';
		//echo '<b>������� ���������� ��������:</b> � �������� ����������� ����� ����������� � ���������� ������� � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b><br><br>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� �� ���������� �������</b></h3>';
		echo '<b>������� ���������� ��������:</b> � �������� ����������� ����� ����������� � ���������� ������� � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b>, �� �� ����� 3-� ������� �� ������ ������������� � ������� �����.<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_task_all_count_prize.'</b><br>';
		echo '����������� ����� �������������� ������������� ��� ������� � �������� - <b>'.number_format($konk_task_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_task_all_count_prize; $i++) {
				if($konk_task_prizes[$y][$i]==0) {
					$_Task_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Task_Prizes[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],2,'.',' '), "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "&nbsp;���.&nbsp;", "");
					if($y==2) $_Task_Prizes[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],0,'.',' '), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
					if($y==3) $_Task_Prizes[$y][$i] = count_text(number_format($konk_task_prizes[$y][$i],0,'.',' '), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");
				}
			}
		}

		for($i=0; $i<$konk_task_all_count_prize; $i++) {
			$_Task_Prizes_New = $_Task_Prizes[1][$i]."|".$_Task_Prizes[2][$i]."|".$_Task_Prizes[3][$i];
			$_Task_Prizes_New = explode("|", $_Task_Prizes_New);
			$_Task_Prizes_New = trim(implode(" ", $_Task_Prizes_New));
			$_Task_Prizes_New = str_replace("  ", "&nbsp;+&nbsp;", $_Task_Prizes_New);
			$_Task_Prizes_New = str_replace(" ", "", $_Task_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Task_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_task_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_task_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center"><th class="top">�����</th><th class="top" colspan="2">������������</th><th class="top">��������� �������</th></tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			//$sql = mysql_query("SELECT `id`, `username`,`konkurs_hit`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_hit` DESC limit 10");
			$sql = mysql_query("SELECT `username`,`konkurs_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_task` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_row($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_task_all_count_prize && $row["1"]>=$konk_task_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
					if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
					}else{
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>'.ucfirst($row["0"]).'</td>';
						echo '<td align="center" width="45%" '.$style1.'>'.number_format($row["1"],0,".","`").'</td>';
					}
					echo '</tr>';
				}
			}

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_task`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_task` DESC");
				if(mysql_num_rows($sql_c)>0) {
					while($row = mysql_fetch_array($sql_c)) {
						$idkonc++;
						if(strtolower($row["0"])==strtolower($MY_USERNAME)) {
							$ok_get = 1;
							break;
						}
					}

					if(isset($ok_get)) {
						echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'><b>'.$idkonc.'</b></td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["2"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'><b>'.ucfirst($row["0"]).'</b></td>';
						echo '<td align="center" width="45%" '.$style1.'><b>'.number_format($row["1"],0,".","`").'</b></td>';
						echo '</tr>';
					}
				}
			}
		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='task' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_row($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["0"];
			else $list_ident_arr.= "', '".$row_ident["0"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='task' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_array($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">��������� �������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
                        
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
echo '</div>';
	### ������� �� ���������� ������� ###
/*	
}elseif($type_kon=="hit") {

	### ������� ����������� ###

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='title'");
	$konk_hit_title = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='min_do'");
	$konk_hit_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='all_count_prize'");
	$konk_hit_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='count_prize'");
	$konk_hit_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='type_prize'");
	$konk_hit_type_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='country'");
	$konk_hit_ed_country = explode("; ", mysql_result($sql,0,0));

	$geo_name_arr = array(
		'RU' => '������', 	'UA' => '�������', 	'BY' => '����������', 	'MD' => '��������', 	'KZ' => '����������', 	'AM' => '�������', 	'UZ' => '�����������',	'LV' => '������',
		'DE' => '��������', 	'GE' => '������', 	'LT' => '�����', 	'FR' => '�������', 	'AZ' => '������������', 'US' => '���', 		'VN' => '��������', 	'PT' => '����������',
		'GB' => '������', 	'BE' => '�������', 	'ES' => '�������', 	'CN' => '�����',	'TJ' => '������������', 'EE' => '�������', 	'IT' => '������', 	'KG' => '��������',
		'IL' => '�������', 	'CA' => '������', 	'TM' => '�������������','BG' => '��������',	'IR' => '�����', 	'GR' => '������', 	'TR' => '������', 	'PL' => '������',
		'FI' => '���������', 	'EG' => '�������', 	'SE' => '������', 	'RO' => '�������'
	);
	if(is_array($konk_hit_ed_country)) {
		foreach($konk_hit_ed_country as $key => $val) {
			$country_arr_ru[] = isset($geo_name_arr[$val]) ? $geo_name_arr[$val] : false;
		}
	}
	$konk_hit_ed_country_to = isset($country_arr_ru) ? trim(strtoupper(implode(', ', $country_arr_ru))) : false;

	for($y=1; $y<=5; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='prizes' AND `howmany`='$y'");
		$konk_hit_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_hit_status==1 && $konk_hit_date_end>=(time()) && $konk_hit_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konk_hit_title!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konk_hit_title.'</h1>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� �����������</b></h3>';
		echo '��� ������ � ��������, ������ ����������� ����������� �� ��� ���� �� ����� ����������� ������!<br>';
		echo '����������� ������ ��������, ���������� � ������� 24 ����� ��������';

		if($konk_hit_ed_country_to!=false) { echo ' �� '.$konk_hit_ed_country_to.'.<br><br>'; }else{ echo '.<br><br>'; }

		echo '���������� �������� ���� - <b>'.$konk_hit_all_count_prize.'</b><br>';
		echo '����������� ���������� ����������� ��� ������� � �������� - <b>'.number_format($konk_hit_min,0,'.',' ').'</b><br><br>';

		echo '<div id="newform" align="center">';
			echo '<b>���� ���. ������:</b> ';
			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
				echo '<input value="https://lilacbux.com?r='.$partnerid.'" class="ok" onClick="this.select();" readonly="readonly" style="width:180px; height:auto; margin: 5px; padding: 5px; text-align:center;"><br>';
			}else{
				echo '<b style="background-color:#FFBBFF;">��� ��������� ���. ������ ���������� ��������������</b><br><br>';
			}
		echo '</div>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=5; $y++) {
			for($i=0; $i<$konk_hit_all_count_prize; $i++) {
				if($y==1) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;���.[���.&nbsp;����]", "&nbsp;���.[���.&nbsp;����]", "&nbsp;���.[���.&nbsp;����]", "") : false;
				if($y==2) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;���.[����.&nbsp;����]", "&nbsp;���.[����.&nbsp;����]", "&nbsp;���.[����.&nbsp;����]", "") : false;
				if($y==3) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;���./����������[���.&nbsp;����]", "&nbsp;���./����������[���.&nbsp;����]", "&nbsp;���./����������[���.&nbsp;����]", "") : false;
				if($y==4) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(round($konk_hit_prizes[$y][$i],4), "&nbsp;���./����������[����.&nbsp;����]", "&nbsp;���./����������[����.&nbsp;����]", "&nbsp;���./����������[����.&nbsp;����]", "") : false;
				if($y==5) $_hit_Prizes[$y][$i] = $konk_hit_prizes[$y][$i]>0 ? count_text(number_format($konk_hit_prizes[$y][$i], 0, ".", ""), "&nbsp;������", "&nbsp;����", "&nbsp;�����", "") : false;
			}
		}

		for($i=0; $i<$konk_hit_all_count_prize; $i++) {
			$_hit_Prizes_New = array();
			for($y=1; $y<=count($_hit_Prizes); $y++) {
				if($_hit_Prizes[$y][$i]!=false) $_hit_Prizes_New[].= $_hit_Prizes[$y][$i];
			}
			$_hit_Prizes_New = implode(" +&nbsp;", $_hit_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_hit_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_hit_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_hit_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th class="top">�����</th>';
				echo '<th class="top" colspan="2">������������</th>';
				echo '<th class="top">���������� �����������</th>';
				echo '<th class="top">����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_hit`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_hit` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_hit_all_count_prize && $row["konkurs_hit"]>=$konk_hit_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_hit_prizes[1][$idkonc-1] + $row["konkurs_hit"] * $konk_hit_prizes[3][$idkonc-1]);
						$sum_prize_rb = ($konk_hit_prizes[2][$idkonc-1] + $row["konkurs_hit"] * $konk_hit_prizes[4][$idkonc-1]);
						$sum_prize_reit = ($konk_hit_prizes[5][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,4)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,4)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="������� �� ����� ����� '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_hit"],0,'.',' ').'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_hit`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_hit` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="5" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_hit"], 0, ".", " ").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}
		echo '</table><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='hit' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='hit' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<span class="msg-w" style="margin:20px auto 10px;">����������� ��������, ��������� 5 �����������</span>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i:s", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">���������� �����������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<span class="msg-w" style="margin:30px auto;">�������� ��� �� �����������!</span>';
	}

	### ������� ����������� ###
*/
}elseif($type_kon=="complex") {

	### ����������� ������� ###
	$points_arr = array(
		"serf"    => "�������� ������ � ��������",
		"youtub"    => "�������� ������������ � �������� <span style=\"color: #3F3F3F;\">You</span><span style=\"border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;\">Tube</span>",
		"aserf"    => "�������� ������ � ����-��������",
		"mail"    => "������ ������������� ������", 
		"task"    => "���������� ������������ �������", 
		"task_re" => "���������� ������������� �������", 
		"test"    => "����������� �����", 
		"refs"    => "����������� ��������"
	);
	$points_key_arr = array_keys($points_arr);
	$points_val_arr = array_values($points_arr);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='title'");
	$konkurs_title_complex = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='min_do'");
	$konk_complex_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='all_count_prize'");
	$konk_complex_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='count_prize'");
	$konk_complex_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='type_prize'");
	$konk_complex_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=4; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='prizes' AND `howmany`='$y'");
		$konk_complex_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	foreach($points_key_arr as $key) {
		$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".$key."'") or die(mysql_error());
		$konk_complex_point[$key] = mysql_result($sql,0,0);
	}

	if($konk_complex_status==1 && $konk_complex_date_end>=(time()) && $konk_complex_date_start<(time())) {
	    echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
	        if($konkurs_title_complex!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_complex.'</h1>';

		//echo '<h3 class="sp" style="margin:0; padding:0;"><b>����������� �������</b></h3>';
		//echo '<b>������� ���������� ��������:</b> �������� ����� ������ ��, ��� ������� ���������� ���������� ������ � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b><br><br>';

		echo '<h3 class="sp" style="margin:0; padding:0;"><b>����������� �������</b></h3>';
		echo '<b>������� ���������� ��������:</b> �������� ����� ������ ��, ��� ������� ���������� ���������� ������ � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b>. ��� ���������� ������ �� ���������� �������, ����������� �� ����� 3-� ������� �� ������ ������������� � ������� �����.<br><br>';

		
		echo '<b>����� ����������� �� �����:</b><br>';
		$_list_points = array();
		foreach($points_arr as $key => $val) {
			if(isset($konk_complex_point[$key]) && $konk_complex_point[$key]>0) $_list_points[] = "&nbsp;- ".$points_arr[$key].": ".count_text($konk_complex_point[$key], " ������", " ����", " �����", "");
		}
		$_list_points = implode(";<br>", $_list_points);
		echo $_list_points.".<br><br>";

		echo '<span style="color:#FF0000;">��������:</span> ����� �� ����������� �� ����� �������, �� ������� � ������������� ���-�����������, ����� �� ����������� ��������������� �������������! �� ����������� ��������� ����� ����������� ������ ��� ������� ��������� ��������!<br><br>';

		echo '���������� �������� ���� - <b>'.$konk_complex_all_count_prize.'</b><br>';
		echo '����������� ���������� ������ ��� ������� � �������� - <b>'.number_format($konk_complex_min,0,'.',' ').'</b><br><br>';
		
		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=4; $y++) {
			for($i=0; $i<$konk_complex_all_count_prize; $i++) {
				if($y==1) $_Complex_Prizes[$y][$i] = count_text(round($konk_complex_prizes[$y][$i],4), "&nbsp;���.[���.&nbsp;����]", "&nbsp;���.[���.&nbsp;����]", "&nbsp;���.[���.&nbsp;����]", "");
				if($y==2) $_Complex_Prizes[$y][$i] = count_text(round($konk_complex_prizes[$y][$i],4), "&nbsp;���.[����.&nbsp;����]", "&nbsp;���.[����.&nbsp;����]", "&nbsp;���.[����.&nbsp;����]", "");
				if($y==3) $_Complex_Prizes[$y][$i] = count_text(number_format($konk_complex_prizes[$y][$i], 0, ".", ""), "&nbsp;������", "&nbsp;����", "&nbsp;�����", "");
				if($y==4) $_Complex_Prizes[$y][$i] = count_text(number_format($konk_complex_prizes[$y][$i], 0, ".", ""), "&nbsp;���������", "&nbsp;�������", "&nbsp;��������", "");
			}
		}

		for($i=0; $i<$konk_complex_all_count_prize; $i++) {
			$_Complex_Prizes_New = array();
			for($y=1; $y<=count($_Complex_Prizes); $y++) {
				if($_Complex_Prizes[$y][$i]!=false) $_Complex_Prizes_New[].= $_Complex_Prizes[$y][$i];
			}
			$_Complex_Prizes_New = implode(" +&nbsp;", $_Complex_Prizes_New);

			echo '<b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Complex_Prizes_New.'<br>';
		}

		echo '<br><b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_complex_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_complex_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>�����</th>';
				echo '<th colspan="2">������������</th>';
				echo '<th>������� ������</th>';
				echo '<th>����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `username`,`konkurs_complex`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_complex` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_complex_all_count_prize && $row["konkurs_complex"]>=$konk_complex_min) {
						$style1 = 'style="background-color:#b0f9f9;"';
						$style2 = 'style="background-color:#b0f9f9; border-right:none;"';
						$style3 = 'style="background-color:#b0f9f9; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_complex_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_complex_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_complex_prizes[3][$idkonc-1]);
						$sum_prize_ref = ($konk_complex_prizes[4][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,4)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,4)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");
						if($sum_prize_ref>0) 	$sum_prizes[].= count_text(number_format($sum_prize_ref, 0, ".", ""), "&nbsp;���������&nbsp;", "&nbsp;�������&nbsp;", "&nbsp;��������&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+&nbsp;", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_complex"],0,'.',' ').'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_complex`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `username`='$MY_USERNAME' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_complex` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="5" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_complex"], 0, ".", " ").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}
		echo '</table>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='complex' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 3");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			if($list_ident_arr==false) $list_ident_arr.= $row_ident["ident"];
			else $list_ident_arr.= "', '".$row_ident["ident"];
		}
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='complex' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<span class="msg-ok" style="margin:30px auto 20px auto;">����������� ��������, ��������� 5 �����������</span>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">������� ������</th>';
					echo '<th class="top">����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.ucfirst($row["username"]).'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<span class="msg-w" style="margin:30px auto;">�������� ��� �� �����������!</span>';
	}

}elseif($type_kon=="serf") {
	### ������� �� ���������� ������ � �������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='title'");
	$konkurs_title_serf = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='min_do'");
	$konk_serf_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='all_count_prize'");
	$konk_serf_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='count_prize'");
	$konk_serf_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='type_prize'");
	$konk_serf_type_prize = explode("; ", mysql_result($sql,0,0));
	
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='timer_ot'");
    $konk_serf_timer = mysql_result($sql,0,0);

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='prizes' AND `howmany`='$y'");
		$konk_serf_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_serf_status==1 && $konk_serf_date_end>=(time()) && $konk_serf_date_start<(time())) {
	        if($konkurs_title_serf!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_serf.'</h1>';

			 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
			echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: �� ���������� ������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������</b></h3><br/>';
		echo '<b>������� ���������� ��������:</b> ��� ������� � �������� ���������� ���� ������������������ ������������� ������� <b><a href="/">'.$domen.'</a></b>. � �������� ����������� ���������� ����������� ������/�������� � �������� � <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> �������� � ������ ���������� �������� �� ������� <b><a href="/">'.$domen.'</a></b>. ������ ������� ����� ����������� ��� � ���������� ����� ��� � � ������� ��������� ������ �� ��� ������� ��� ������� ��������� � �������� ����� ����������� �� �������.<br><br>';

		echo '<span style="color:#FF0000;">��������:</span> ��������������� ������������ � �������� �� ���������!!!<br><br>';
		
		echo '<span style="color:#FF0000;">��������:</span> � �������� ��������� ������ � �������� �� <b>'.number_format($konk_serf_timer,0,".","`").' ���.</b>!!!<br><br>';
		
		echo '<span style="color:#FF0000;">��������:</span> � �������� �� ��������� ������ � <b>���-����������� � ���. �����������</b>!!!<br><br>';
		
		echo '���������� �������� ���� - <b>'.$konk_serf_all_count_prize.'</b><br>';
		echo '����������� ���������� ����������� ������/�������� � �������� ��� ������� � �������� - <b>'.number_format($konk_serf_min,0,".","`").'</b><br><br>';

		echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_serf_all_count_prize; $i++) {
				if($konk_serf_prizes[$y][$i]==0) {
					$_Serf_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Serf_Prizes[$y][$i] = count_text(number_format($konk_serf_prizes[$y][$i],2, ".", "`"), " ���.[���.����]", " ���.[���.����]", " ���.[���.����]", "");
					if($y==2) $_Serf_Prizes[$y][$i] = count_text(number_format($konk_serf_prizes[$y][$i],2, ".", "`"), " ���.[����.����]", " ���.[����.����]", " ���.[����.����]", "");
					if($y==3) $_Serf_Prizes[$y][$i] = count_text(number_format($konk_serf_prizes[$y][$i],0, ".", "`"), " ������", " ����", " �����", "");
				}
			}
		}

		for($i=0; $i<$konk_serf_all_count_prize; $i++) {
			$_Serf_Prizes_New = array();
			for($y=1; $y<=count($_Serf_Prizes); $y++) {
				if($_Serf_Prizes[$y][$i]!=false) $_Serf_Prizes_New[].= $_Serf_Prizes[$y][$i];
			}
			$_Serf_Prizes_New = implode(" +&nbsp;", $_Serf_Prizes_New);

			echo '<div align="left" style="margin-bottom:6px;"><b style="color:coral;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Serf_Prizes_New.'</div>';
		}
		echo '<br>';

		echo '<b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_serf_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_serf_date_end-1).'';

		echo '<table class="tables_inv">';
			echo '<thead><tr align="center">';
				echo '<th>�����</th>';
				echo '<th colspan="2">������������</th>';
				echo '<th>��������� ������</th>';
				echo '<th>����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_serf`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_serf` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_serf_count_prize && $row["konkurs_serf"]>=$konk_serf_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_serf_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_serf_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_serf_prizes[3][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 0, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="������� �� ����� ����� '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_serf"],0,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_serf`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_serf"], 0, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='serf' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='serf' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">�����</th>';
					echo '<th class="top">��������� ������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
	
}elseif($type_kon=="clic_ref") {
	### ������� ����� �� ��������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='title'");
	$konkurs_title_clic_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='min_do'");
	$konk_clic_ref_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='all_count_prize'");
	$konk_clic_ref_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='count_prize'");
	$konk_clic_ref_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='type_prize'");
	$konk_clic_ref_type_prize = explode("; ", mysql_result($sql,0,0));
	
	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='$y'");
		$konk_clic_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_clic_ref_status==1 && $konk_clic_ref_date_end>=(time()) && $konk_clic_ref_date_start<(time())) {
	        if($konkurs_title_clic_ref!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_clic_ref.'</h1>';

			 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="justify"> ';
			echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� "������ �������"</b></h3><br/>';
		echo '<b>������� ���������� ��������:</b> ��� ������� � �������� ���������� ���� ������������������ ������������� ������� <b><a href="/">'.$domen.'</a></b>. �������� ����� ������ �� ��������, ��� �������� �������� ���������� ����� ������ �������� �� ����� ���������� ��������. � �������� ����������� ����� ����������� ���������� ���� ������� � ��������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, �����, ������ � �������.<br><br>';

		echo '<span style="color:#FF0000;">��������:</span> ��������������� ������������ � �������� �� ���������!!!<br>������ � ������ �������� �� �����������.<br><br>';
		
		echo '���������� �������� ���� - <b>'.$konk_clic_ref_all_count_prize.'</b><br>';
		echo '����������� ������������ ����� ���������� I-V��. ��� ������� � �������� - <b>'.number_format($konk_clic_ref_min,0,".","`").'</b><br><br>';

		//echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_clic_ref_all_count_prize; $i++) {
				if($konk_clic_ref_prizes[$y][$i]==0) {
					$_Clic_ref_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Clic_ref_Prizes[$y][$i] = count_text(number_format($konk_clic_ref_prizes[$y][$i],2, ".", "`"), " ���.[���.����]", " ���.[���.����]", " ���.[���.����]", "");
					if($y==2) $_Clic_ref_Prizes[$y][$i] = count_text(number_format($konk_clic_ref_prizes[$y][$i],2, ".", "`"), " ���.[����.����]", " ���.[����.����]", " ���.[����.����]", "");
					if($y==3) $_Clic_ref_Prizes[$y][$i] = count_text(number_format($konk_clic_ref_prizes[$y][$i],0, ".", "`"), " ������", " ����", " �����", "");
				}
			}
		}
		
		$summ=0;
		$sum=0;
		$su=0;
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='1'");
		$count_all_konk_admin = explode("; ",mysql_result($sql,0,0));
		for($i=0;$i<count($count_all_konk_admin);$i++){ 
        $summ=$summ+$count_all_konk_admin[$i];
          }
			$sqlll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='2' ");
			$count_all_konk_ad = explode("; ",mysql_result($sqlll,0,0));
			for($i=0;$i<count($count_all_konk_ad);$i++){ 
            $sum=$sum+$count_all_konk_ad[$i];
            }
			$sqll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='clic_ref' AND `item`='prizes' AND `howmany`='3' ");
			$count_all_konk_a = explode("; ",mysql_result($sqll,0,0));
			for($i=0;$i<count($count_all_konk_a);$i++){ 
            $su=$su+$count_all_konk_a[$i];
            }
			
			echo '<div style="text-align:center; margin:1px auto 5px; line-height:20px;">';			
			echo '<div class="text-red" style="font-size:16px; font-weight:bold;">�������� ����:</div>';
			echo '<div><b class="text-blue" style="font-size:14px;color:#108;">'.number_format($summ,2,".","`").'</b>&nbsp;���.[���.&nbsp;����]<span style="color:#CCC; padding:0 5px;">�</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($sum,2,".","`").'</b>&nbsp;���.[����.&nbsp;����]<span style="color:#CCC; padding:0 5px;">�</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($su,0,".","`").'</b>&nbsp;������</div>';
			echo '</div>';
			//echo '<br>';
			
			echo '<table style="margin:0 auto 15px;"><tr><td>';

		for($i=0; $i<$konk_clic_ref_all_count_prize; $i++) {
			$_Clic_ref_Prizes_New = array();
			for($y=1; $y<=count($_Clic_ref_Prizes); $y++) {
				if($_Clic_ref_Prizes[$y][$i]!=false) $_Clic_ref_Prizes_New[].= $_Clic_ref_Prizes[$y][$i];
			}
			$_Clic_ref_Prizes_New = implode(" +&nbsp;", $_Clic_ref_Prizes_New);
			
            echo '<div align="left" style="line-height:18px;"><b style="color:#108;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Clic_ref_Prizes_New.'</div>';
		}
		echo '</td></tr></table>';
		//echo '<br>';

		echo '<b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_clic_ref_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_clic_ref_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>�����</th>';
				echo '<th colspan="2">������������</th>';
				echo '<th>����� �� ���������</th>';
				echo '<th>����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_clic_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_clic_ref` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_clic_ref_count_prize && $row["konkurs_clic_ref"]>=$konk_clic_ref_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_clic_ref_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_clic_ref_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_clic_ref_prizes[3][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 2, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="������� �� ����� ����� '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_clic_ref"],6,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_clic_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_ads_big` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_clic_ref"], 6, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='clic_ref' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='clic_ref' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">������������</th>';
					echo '<th class="top">����� �� ���������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
	
	### ������� ����� �� ��������� ###
	
}elseif($type_kon=="best_ref") {
	### ������� ����� �� ��������� ����� ��������� ###
	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='title'");
	$konkurs_title_best_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='min_do'");
	$konk_best_ref_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='all_count_prize'");
	$konk_best_ref_all_count_prize = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='count_prize'");
	$konk_best_ref_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='type_prize'");
	$konk_best_ref_type_prize = explode("; ", mysql_result($sql,0,0));
	
	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='$y'");
		$konk_best_ref_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}

	if($konk_best_ref_status==1 && $konk_best_ref_date_end>=(time()) && $konk_best_ref_date_start<(time())) {
	        if($konkurs_title_best_ref!=false) echo '<h1 class="sp" style="color:#C80000; font-size:20px; text-align:center;">'.$konkurs_title_best_ref.'</h1>';

			 echo '<div style="color:#ad5348; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f7ead9" align="left"> ';
			echo '<h3 class="sp" style="margin:0; padding:0;"><b>�������: "������ �������"</b></h3><br/>';
		echo '<b>������� ���������� ��������:</b> ��� ������� � �������� ���������� ���� ������������������ ������������� ������� <b><a href="/">'.$domen.'</a></b> � ����� �������� ��� ������� I ������. �������� ����� ������ ��, ��� ������� ���������� ����� ����� ��������� �� ����� ���������� ��������. � �������� ����������� ����� ����������� ��������� ���� ������� � ��������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, �����, ������ � �������.<br><br>';

		echo '<span style="color:#FF0000;">��������:</span> ��������������� ������������ � �������� �� ���������!!!<br>������ � ������ �������� �� �����������.<br><br>';
		
		echo '���������� �������� ���� - <b>'.$konk_best_ref_all_count_prize.'</b><br>';
		echo '����������� ������������ ����� ��� ��������� I-V��. ��� ������� � �������� - <b>'.number_format($konk_best_ref_min,0,".","`").'</b><br><br>';

		//echo '<b style="color:coral;"><u>�����:</u></b><br>';
		for($y=1; $y<=3; $y++) {
			for($i=0; $i<$konk_best_ref_all_count_prize; $i++) {
				if($konk_best_ref_prizes[$y][$i]==0) {
					$_Best_ref_Prizes[$y][$i] = false;
				}else{
					if($y==1) $_Best_ref_Prizes[$y][$i] = count_text(number_format($konk_best_ref_prizes[$y][$i],2, ".", "`"), " ���.[���.����]", " ���.[���.����]", " ���.[���.����]", "");
					if($y==2) $_Best_ref_Prizes[$y][$i] = count_text(number_format($konk_best_ref_prizes[$y][$i],2, ".", "`"), " ���.[����.����]", " ���.[����.����]", " ���.[����.����]", "");
					if($y==3) $_Best_ref_Prizes[$y][$i] = count_text(number_format($konk_best_ref_prizes[$y][$i],0, ".", "`"), " ������", " ����", " �����", "");
				}
			}
		}
		$summ=0;
		$sum=0;
		$su=0;
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='1'");
		$count_all_konk_admin = explode("; ",mysql_result($sql,0,0));
		for($i=0;$i<count($count_all_konk_admin);$i++){ 
        $summ=$summ+$count_all_konk_admin[$i];
          }
			$sqlll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='2' ");
			$count_all_konk_ad = explode("; ",mysql_result($sqlll,0,0));
			for($i=0;$i<count($count_all_konk_ad);$i++){ 
            $sum=$sum+$count_all_konk_ad[$i];
            }
			$sqll = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='best_ref' AND `item`='prizes' AND `howmany`='3' ");
			$count_all_konk_a = explode("; ",mysql_result($sqll,0,0));
			for($i=0;$i<count($count_all_konk_a);$i++){ 
            $su=$su+$count_all_konk_a[$i];
            }
			
			echo '<div style="text-align:center; margin:1px auto 5px; line-height:20px;">';			
			echo '<div class="text-red" style="font-size:16px; font-weight:bold;">�������� ����:</div>';
			echo '<div><b class="text-blue" style="font-size:14px;color:#108;">'.number_format($summ,2,".","`").'</b>&nbsp;���.[���.&nbsp;����]<span style="color:#CCC; padding:0 5px;">�</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($sum,2,".","`").'</b>&nbsp;���.[����.&nbsp;����]<span style="color:#CCC; padding:0 5px;">�</span>';
			echo '<b class="text-blue" style="font-size:14px;color:#108;">'.number_format($su,0,".","`").'</b>&nbsp;������</div>';
			echo '</div>';
			//echo '<br>';
			
			echo '<table style="margin:0 auto 15px;"><tr><td>';

		for($i=0; $i<$konk_best_ref_all_count_prize; $i++) {
			$_Best_ref_Prizes_New = array();
			for($y=1; $y<=count($_Best_ref_Prizes); $y++) {
				if($_Best_ref_Prizes[$y][$i]!=false) $_Best_ref_Prizes_New[].= $_Best_ref_Prizes[$y][$i];
			}
			$_Best_ref_Prizes_New = implode(" +&nbsp;", $_Best_ref_Prizes_New);

			echo '<div align="left" style="line-height:18px;"><b style="color:#108;">'.($i+1).' �����:</b>&nbsp;&nbsp;'.$_Best_ref_Prizes_New.'</div>';
		}
		echo '</td></tr></table>';

		echo '<b>������ ����������:</b> c '.DATE("d.m.Y�. H:i", $konk_best_ref_date_start).' &mdash; '.DATE("d.m.Y�. H:i:s", $konk_best_ref_date_end-1).'';

		echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th>�����</th>';
				echo '<th colspan="2">������������</th>';
				echo '<th>����� ������ ���������</th>';
				echo '<th>����</th>';
			echo '</tr></thead>';

			$idkonc = 0; unset($MY_USERNAME_Y);
			$sql = mysql_query("SELECT `id`, `username`,`konkurs_best_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_best_ref` DESC limit 10");
			if(mysql_num_rows($sql)>0) {
				while($row = mysql_fetch_assoc($sql)) {
					$idkonc++; 
					if($idkonc<=$konk_best_ref_count_prize && $row["konkurs_best_ref"]>=$konk_best_ref_min) {
						$style1 = 'style="background-color:#FFF3A4;"';
						$style2 = 'style="background-color:#FFF3A4; border-right:none;"';
						$style3 = 'style="background-color:#FFF3A4; border-left:none;"';

						$sum_prizes = array();
						$sum_prize_os = ($konk_best_ref_prizes[1][$idkonc-1]);
						$sum_prize_rb = ($konk_best_ref_prizes[2][$idkonc-1]);
						$sum_prize_reit = ($konk_best_ref_prizes[3][$idkonc-1]);

						if($sum_prize_os>0) 	$sum_prizes[].= "<b>".round($sum_prize_os,2)."</b>&nbsp;���.[���.&nbsp;����]";
						if($sum_prize_rb>0) 	$sum_prizes[].= "<b>".round($sum_prize_rb,2)."</b>&nbsp;���.[����.&nbsp;����]";
						if($sum_prize_reit>0) 	$sum_prizes[].= count_text(number_format($sum_prize_reit, 2, ".", ""), "&nbsp;������&nbsp;", "&nbsp;����&nbsp;", "&nbsp;�����&nbsp;", "");

						$sum_prizes = ( is_array($sum_prizes) && count($sum_prizes)>0) ? implode("<br>+", $sum_prizes) : "0";
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
						$sum_prizes = 0;
					}

					if(strtolower($row["username"])==strtolower($MY_USERNAME)) {
						$MY_USERNAME_Y = 1;
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.$idkonc.'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							echo '<a href="/wall?uid='.$row["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" title="������� �� ����� ����� '.$row["username"].'" /></a>';
						echo '</td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_best_ref"],6,".","`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.$sum_prizes.'</td>';
					echo '</tr>';
				}
			}

			if($MY_USERNAME!==false && !isset($MY_USERNAME_Y)) {
				$idkonc = 10; unset($ok_get);

				$sql_c = mysql_query("SELECT `username`,`konkurs_best_ref`,`avatar` FROM `tb_users` WHERE `wmid`!='$site_wmid' AND `ban_date`='0' AND `username`='$MY_USERNAME' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`) ORDER BY `konkurs_best_ref` DESC");
				if(mysql_num_rows($sql_c)>0) {
					$row = mysql_fetch_assoc($sql_c);

					echo '<tr>';
						echo '<td align="center" colspan="4" style="padding:3px 1px; background:none; border:none;"></td>';
					echo '</tr>';

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>#</td>';
						echo '<td width="30px" align="center" '.$style2.'><img class="avatar" src="/avatar/'.$row["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" /></td>';
						echo '<td align="left" width="30%" '.$style3.'>'.$row["username"].'</td>';
						echo '<td align="center" width="30%" '.$style1.'>'.number_format($row["konkurs_best_ref"], 6, ".", "`").'</td>';
						echo '<td align="center" width="30%" '.$style1.'>0</td>';
					echo '</tr>';
				}
			}

		echo '</table><br><br>';
	}

	$sql_ident = mysql_query("SELECT `ident` FROM `tb_konkurs_rezult` WHERE `type`='best_ref' GROUP BY `ident` ORDER BY `ident` DESC LIMIT 5");
	if(mysql_num_rows($sql_ident)>0) {
		$list_ident_arr = false;
		while ($row_ident = mysql_fetch_assoc($sql_ident)) {
			$list_ident_arr[] = $row_ident["ident"];
		}
		$list_ident_arr = implode("', '", $list_ident_arr);
	}else{
		$list_ident_arr = false;
	}

	$i = 0;
	$sql = mysql_query("SELECT * FROM `tb_konkurs_rezult` WHERE `type`='best_ref' AND `ident` IN ('$list_ident_arr') ORDER BY `ident` DESC, `mesto` ASC");
	if(mysql_num_rows($sql)>0) {
		echo '<br><br><br><span class="msg-w">����������� ��������, ��������� 5 �����������</span><br>';
		while($row = mysql_fetch_assoc($sql)) {
			$i++;
			if($i==1) {
				echo '<h3 class="sp" style="margin:0; padding:0; margin-bottom:5px;">&nbsp;';
					echo '<div style="float:left">������� �'.$row["ident"].'</div>';
					echo '<div style="float:right">������ ����������: '.DATE("d.m.Y�. H:i", $row["date_s"]).' &mdash; '.DATE("d.m.Y�. H:i:s", $row["date_e"]-1).'</div>';
				echo '</h3>';
				echo '<table class="tables">';
				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top">������������</th>';
					echo '<th class="top">����� ������ ���������</th>';
					echo '<th class="top">�����</th>';
				echo '</tr></thead>';
			}
			echo '<tr>';
				echo '<td align="center" width="10%">'.$row["mesto"].'</td>';
				echo '<td align="center" width="15%">'.$row["username"].'</td>';
				echo '<td align="center" width="30%">'.number_format($row["kolvo"], 0, ".","`").'</td>';
				echo '<td align="center" width="45%">'.$row["summa_array"].'</td>';
			echo '</tr>';
			if($i==$row["count_priz"]) {
				$i=0; echo '</table><br>';
			}
		}
	}else{
		echo '<br><br><br><span class="msg-w">�������� ��� �� �����������!</span><br>';
	}
	
	### ������� ����� �� ��������� ����� ��������� ###
}

echo '</div>';
include('footer.php');?>