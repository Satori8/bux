<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
//echo '<h3 class="sp" style="margin-top:0; padding-top:0; border:none;"><b>��������� ������������ ������</b></h3>';

if(count($_POST)>0) {
	$dlink_cena_hits = floatval(abs(trim($_POST["dlink_cena_hits"])));
	$dlink_cena_hits_bs = floatval(abs(trim($_POST["dlink_cena_hits_bs"])));
	$dlink_nacenka = intval(abs(trim($_POST["dlink_nacenka"])));
	$dlink_min_hits = intval(abs(trim($_POST["dlink_min_hits"])));
	$dlink_min_hits_vip = intval(abs(trim($_POST["dlink_min_hits_vip"])));
	$dlink_cena_color = floatval(abs(trim($_POST["dlink_cena_color"])));
	$dlink_cena_active = floatval(abs(trim($_POST["dlink_cena_active"])));
	$dlink_timer_ot = intval(abs(trim($_POST["dlink_timer_ot"])));
	$dlink_timer_do = intval(abs(trim($_POST["dlink_timer_do"])));
	$dlink_cena_timer = floatval(abs(trim($_POST["dlink_cena_timer"])));
	$dlink_cena_revisit_1 = floatval(abs(trim($_POST["dlink_cena_revisit_1"])));
	$dlink_cena_revisit_2 = floatval(abs(trim($_POST["dlink_cena_revisit_2"])));

	$dlink_cena_nolimit = intval(abs(trim($_POST["dlink_cena_nolimit"])));
	$dlink_cena_nolimit_1 = floatval(abs(trim($_POST["dlink_cena_nolimit_1"])));
	$dlink_cena_nolimit_2 = floatval(abs(trim($_POST["dlink_cena_nolimit_2"])));
	$dlink_cena_nolimit_3 = floatval(abs(trim($_POST["dlink_cena_nolimit_3"])));
	$dlink_cena_nolimit_4 = floatval(abs(trim($_POST["dlink_cena_nolimit_4"])));

	$dlink_cena_uplist = floatval(abs(trim($_POST["dlink_cena_uplist"])));
	$dlink_cena_unic_ip_1 = floatval(abs(trim($_POST["dlink_cena_unic_ip_1"])));
	$dlink_cena_unic_ip_2 = floatval(abs(trim($_POST["dlink_cena_unic_ip_2"])));

	$testdrive_status = ( isset($_POST["testdrive_status"]) && intval(trim($_POST["testdrive_status"]))==1 ) ? 1 : 0;
	$testdrive_reset = ( isset($_POST["testdrive_reset"]) && intval(trim($_POST["testdrive_reset"]))==1 ) ? 1 : 0;
	$testdrive_count = isset($_POST["testdrive_count"]) ? intval(abs(trim($_POST["testdrive_count"]))) : 100;
	$testdrive_timer = isset($_POST["testdrive_timer"]) ? intval(abs(trim($_POST["testdrive_timer"]))) : 15;
    
	$bonus_surf_status = ( isset($_POST["bonus_surf_status"]) && abs(intval(trim($_POST["bonus_surf_status"])))==1 ) ? 1 : 0;
	$bonus_surf_cnt_money = isset($_POST["bonus_surf_cnt_money"]) ? abs(round(trim($_POST["bonus_surf_cnt_money"]),2)) : 0;
	$bonus_surf_cnt_visits = isset($_POST["bonus_surf_cnt_visits"]) ? abs(intval(trim($_POST["bonus_surf_cnt_visits"]))) : 0;

	if($testdrive_timer<$dlink_timer_ot | $testdrive_timer>$dlink_timer_do) {
		echo '<span id="info-msg" class="msg-error" style="margin-bottom:0;">������ ��� Test Drive ������ ���� � �������� �� '.$dlink_timer_ot.' �� '.$dlink_timer_do.' ���.</span>';
		echo '<script type="text/javascript">HideMsg("info-msg", 4000);</script>';
	}else{
		if($testdrive_reset==1) {
			mysql_query("UPDATE `tb_users` SET `test_drive`='0' WHERE `test_drive`='1'") or die(mysql_error());
		}
		
		mysql_query("UPDATE `tb_config` SET `price`='$bonus_surf_status' WHERE `item`='bonus_surf_status' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$bonus_surf_cnt_money' WHERE `item`='bonus_surf_cnt_money' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$bonus_surf_cnt_visits' WHERE `item`='bonus_surf_cnt_visits' AND `howmany`='1'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$testdrive_status' WHERE `item`='testdrive_status' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$testdrive_count' WHERE `item`='testdrive_count' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$testdrive_timer' WHERE `item`='testdrive_timer' AND `howmany`='1'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_hits' WHERE `item`='dlink_cena_hits' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_hits_bs' WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_nacenka' WHERE `item`='dlink_nacenka' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_min_hits' WHERE `item`='dlink_min_hits' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_min_hits_vip' WHERE `item`='dlink_min_hits_vip' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_color' WHERE `item`='dlink_cena_color' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_active' WHERE `item`='dlink_cena_active' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_timer_ot' WHERE `item`='dlink_timer_ot' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_timer_do' WHERE `item`='dlink_timer_do' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_timer' WHERE `item`='dlink_cena_timer' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_revisit_1' WHERE `item`='dlink_cena_revisit' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_revisit_2' WHERE `item`='dlink_cena_revisit' AND `howmany`='2'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_nolimit' WHERE `item`='dlink_cena_nolimit' AND `howmany`='0'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_nolimit_1' WHERE `item`='dlink_cena_nolimit' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_nolimit_2' WHERE `item`='dlink_cena_nolimit' AND `howmany`='2'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_nolimit_3' WHERE `item`='dlink_cena_nolimit' AND `howmany`='3'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_nolimit_4' WHERE `item`='dlink_cena_nolimit' AND `howmany`='4'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_uplist' WHERE `item`='dlink_cena_uplist' AND `howmany`='1'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_unic_ip_1' WHERE `item`='dlink_cena_unic_ip' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$dlink_cena_unic_ip_2' WHERE `item`='dlink_cena_unic_ip' AND `howmany`='2'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok" style="margin-bottom:0;">��������� ������� ���������. '.($testdrive_reset==1 ? "������������� ������� TestDrive �������� ��� ���� �������������!" : false).'</span>';

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.(isset($_GET["op"]) ? limpiar($_GET["op"]) : false).'");
			}, 1000);
			HideMsg("info-msg", 2000);
		</script>';
	}
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
$dlink_cena_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
$dlink_cena_hits_bs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_nacenka' AND `howmany`='1'");
$dlink_nacenka = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits' AND `howmany`='1'");
$dlink_min_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits_vip' AND `howmany`='1'");
$dlink_min_hits_vip = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_color' AND `howmany`='1'");
$dlink_cena_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_active' AND `howmany`='1'");
$dlink_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_ot' AND `howmany`='1'");
$dlink_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_do' AND `howmany`='1'");
$dlink_timer_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_timer' AND `howmany`='1'");
$dlink_cena_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='1'");
$dlink_cena_revisit_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='2'");
$dlink_cena_revisit_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='0'");
$dlink_cena_nolimit = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='1'");
$dlink_cena_nolimit_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='2'");
$dlink_cena_nolimit_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='3'");
$dlink_cena_nolimit_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='4'");
$dlink_cena_nolimit_4 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_uplist' AND `howmany`='1'");
$dlink_cena_uplist = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='1'");
$dlink_cena_unic_ip_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='2'");
$dlink_cena_unic_ip_2 = mysql_result($sql,0,0);


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_status' AND `howmany`='1'");
$testdrive_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_count' AND `howmany`='1'");
$testdrive_count = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_timer' AND `howmany`='1'");
$testdrive_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_status' AND `howmany`='1'");
$bonus_surf_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_money' AND `howmany`='1'");
$bonus_surf_cnt_money = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_visits' AND `howmany`='1'");
$bonus_surf_cnt_visits = number_format(mysql_result($sql,0,0), 0, ".", "");

?><script type="text/javascript">
function SbmFormB() {
	var testdrive_reset = $("#testdrive_reset").prop("checked") == true ? 1 : 0;

	if( testdrive_reset==1 && !confirm("�� ������� ��� ������ �������� ������������ ������� TestDrive ��� ���� �������������?") ) {
		return false;
	}

	return true;
}
</script><?php

echo '<form method="post" action="" id="newform" onsubmit="return SbmFormB(); return false;">';
echo '<table class="adv-cab">';
echo '<tr><td width="50%" valign="top" style="margin:0 auto; padding:0 2px 0 0; border:none; background:none;">';
	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">������������/��������� �������</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr align="left"><td><b>���� �� 1 ���� ������������ �������</b>, (���.)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_hits" value="'.$dlink_cena_hits.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>���� �� 1 ���� ��������� �������</b>, (���.)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_hits_bs" value="'.$dlink_cena_hits_bs.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>���� �� ��������� ������</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_color" value="'.$dlink_cena_color.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>���� �� �������� ����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_active" value="'.$dlink_cena_active.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������</b>, (���.)<b>:</b></td><td align="right">�� <input type="text" name="dlink_timer_ot" value="'.$dlink_timer_ot.'" class="ok12" style="text-align:center; width:40px;"> �� <input type="text" name="dlink_timer_do" value="'.$dlink_timer_do.'" class="ok12" style="text-align:center; width:40px;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� ������ �� 1 ���.</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_timer" value="'.$dlink_cena_timer.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� �������� ��� ��������� ������ 48 �����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_revisit_1" value="'.$dlink_cena_revisit_1.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� �������� ��� ��������� 1 ��� � �����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_revisit_2" value="'.$dlink_cena_revisit_2.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>����������� �����</b>, (��.)<b>:</b></td><td align="right"><input type="text" name="dlink_min_hits" value="'.$dlink_min_hits.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>����������� ����� VIP</b>, (��.)<b>:</b></td><td align="right"><input type="text" name="dlink_min_hits_vip" value="'.$dlink_min_hits_vip.'" class="ok12" style="text-align:right;"></td></tr>';

		echo '<tr align="left"><td><b>�������� ������ � ������</b>, (���.)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_uplist" value="'.$dlink_cena_uplist.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� ���������� IP, 100% ����������</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_unic_ip_1" value="'.$dlink_cena_unic_ip_1.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� ���������� IP, �� ����� �� 2 �����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="dlink_cena_unic_ip_2" value="'.$dlink_cena_unic_ip_2.'" class="ok12" style="text-align:right;"></td></tr>';

		echo '<tr align="left"><td><b>������� �������</b>, (%)<b>:</b></td><td align="right"><input type="text" name="dlink_nacenka" value="'.$dlink_nacenka.'" class="ok12" style="text-align:right;"></td></tr>';
	echo '</table>';

echo '</td><td width="50%" valign="top" style="margin:0 auto; padding:0 0 0 2px; border:none; background:none;">';
	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">����������� �������</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr align="left"><td><b>1 ������</b>, (���.)</td><td align="right"><input type="text" name="dlink_cena_nolimit_1" value="'.$dlink_cena_nolimit_1.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>2 ������</b>, (���.)</td><td align="right"><input type="text" name="dlink_cena_nolimit_2" value="'.$dlink_cena_nolimit_2.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>3 ������</b>, (���.)</td><td align="right"><input type="text" name="dlink_cena_nolimit_3" value="'.$dlink_cena_nolimit_3.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>1 �����</b>, (���.)</td><td align="right"><input type="text" name="dlink_cena_nolimit_4" value="'.$dlink_cena_nolimit_4.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>C����� ������� ����� XXX ����������, (1 �����):</b></td><td align="right"><input type="text" name="dlink_cena_nolimit" value="'.$dlink_cena_nolimit.'" class="ok12" style="text-align:right;"></td></tr>';
	echo '</table><br>';

	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">Test Drive</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ ���������</b></td>';
			echo '<td align="right">';
				echo '<select name="testdrive_status" class="ok12" style="width:126px; text-align:center;">';
					echo '<option value="0" '.("0" == $testdrive_status ? 'selected="selected"' : false).'>��������</option>';
					echo '<option value="1" '.("1" == $testdrive_status ? 'selected="selected"' : false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>������</b>, (���.)</td>';
			echo '<td align="right"><input type="text" name="testdrive_timer" value="'.$testdrive_timer.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>���������� �������</b>, (��.)</td>';
			echo '<td align="right"><input type="text" name="testdrive_count" value="'.$testdrive_count.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '</tr>';

		echo '<tr align="left">';
			echo '<td colspan="2" style="padding:3px 5px; border-right:1px solid #DDDDDD;">';
				echo '<input type="checkbox" id="testdrive_reset" name="testdrive_reset" value="1" style="width:18px; height:18px; margin:0px; padding:0; display:block; float:left;" />';
				echo '<span style="margin-top:2px; padding-left:5px; font-weight:bold; display:block; float:left;"> - �������� ������������ ������� TestDrive ��� ���� �������������</span>';
			echo '</td>';
		echo '</tr>';

	echo '</table><br>';

	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">�������� �������</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td align="right">';
				echo '<select name="bonus_surf_status" class="ok12" style="width:126px; text-align:center;">';
					echo '<option value="0" '.("0" == $bonus_surf_status ? 'selected="selected"' : false).'>���������</option>';
					echo '<option value="1" '.("1" == $bonus_surf_status ? 'selected="selected"' : false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>�� ������ ������ ����������� �������� �� ������ �������</b>, (���.)</td>';
			echo '<td align="right"><input type="text" name="bonus_surf_cnt_money" value="'.$bonus_surf_cnt_money.'" class="ok12" style="text-align:center;"></td></tr>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>��������� �������� ������� � ��������</b>, (��.)</td>';
			echo '<td align="right"><input type="text" name="bonus_surf_cnt_visits" value="'.$bonus_surf_cnt_visits.'" class="ok12" style="text-align:center;"></td></tr>';
		echo '</tr>';
	echo '</table>';

echo '</td></tr>';
echo '<tr align="center"><td colspan="2" style="border:none; background:none; padding-top:10px;"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>