<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
//echo '<h3 class="sp" style="margin-top:0; padding-top:0; border:none;"><b>��������� ������������ ������</b></h3>';

if(count($_POST)>0) {
	$youtube_cena_hits = floatval(abs(trim($_POST["youtube_cena_hits"])));
	$youtube_cena_hits_bs = floatval(abs(trim($_POST["youtube_cena_hits_bs"])));
	$youtube_nacenka = intval(abs(trim($_POST["youtube_nacenka"])));
	$youtube_min_hits = intval(abs(trim($_POST["youtube_min_hits"])));
	$youtube_min_hits_vip = intval(abs(trim($_POST["youtube_min_hits_vip"])));
	$youtube_cena_color = floatval(abs(trim($_POST["youtube_cena_color"])));
	$youtube_cena_active = floatval(abs(trim($_POST["youtube_cena_active"])));
	$youtube_timer_ot = intval(abs(trim($_POST["youtube_timer_ot"])));
	$youtube_timer_do = intval(abs(trim($_POST["youtube_timer_do"])));
	$youtube_cena_timer = floatval(abs(trim($_POST["youtube_cena_timer"])));
	$youtube_cena_revisit_1 = floatval(abs(trim($_POST["youtube_cena_revisit_1"])));
	$youtube_cena_revisit_2 = floatval(abs(trim($_POST["youtube_cena_revisit_2"])));

	$youtube_cena_nolimit = intval(abs(trim($_POST["youtube_cena_nolimit"])));
	$youtube_cena_nolimit_1 = floatval(abs(trim($_POST["youtube_cena_nolimit_1"])));
	$youtube_cena_nolimit_2 = floatval(abs(trim($_POST["youtube_cena_nolimit_2"])));
	$youtube_cena_nolimit_3 = floatval(abs(trim($_POST["youtube_cena_nolimit_3"])));
	$youtube_cena_nolimit_4 = floatval(abs(trim($_POST["youtube_cena_nolimit_4"])));

	$youtube_cena_uplist = floatval(abs(trim($_POST["youtube_cena_uplist"])));
	$youtube_cena_unic_ip_1 = floatval(abs(trim($_POST["youtube_cena_unic_ip_1"])));
	$youtube_cena_unic_ip_2 = floatval(abs(trim($_POST["youtube_cena_unic_ip_2"])));

	$testdrive_youtube_status = ( isset($_POST["testdrive_youtube_status"]) && intval(trim($_POST["testdrive_youtube_status"]))==1 ) ? 1 : 0;
	$testdrive_reset = ( isset($_POST["testdrive_reset"]) && intval(trim($_POST["testdrive_reset"]))==1 ) ? 1 : 0;
	$testdrive_youtube_count = isset($_POST["testdrive_youtube_count"]) ? intval(abs(trim($_POST["testdrive_youtube_count"]))) : 100;
	$testdrive_youtube_timer = isset($_POST["testdrive_youtube_timer"]) ? intval(abs(trim($_POST["testdrive_youtube_timer"]))) : 15;

	if($testdrive_youtube_timer<$youtube_timer_ot | $testdrive_youtube_timer>$youtube_timer_do) {
		echo '<span id="info-msg" class="msg-error" style="margin-bottom:0;">������ ��� Test Drive ������ ���� � �������� �� '.$youtube_timer_ot.' �� '.$youtube_timer_do.' ���.</span>';
		echo '<script type="text/javascript">HideMsg("info-msg", 4000);</script>';
	}else{
		if($testdrive_reset==1) {
			mysql_query("UPDATE `tb_users` SET `test_drive_youtube`='0' WHERE `test_drive_youtube`='1'") or die(mysql_error());
		}

		mysql_query("UPDATE `tb_config` SET `price`='$testdrive_youtube_status' WHERE `item`='testdrive_youtube_status' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$testdrive_youtube_count' WHERE `item`='testdrive_youtube_count' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$testdrive_youtube_timer' WHERE `item`='testdrive_youtube_timer' AND `howmany`='1'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_hits' WHERE `item`='youtube_cena_hits' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_hits_bs' WHERE `item`='youtube_cena_hits_bs' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_nacenka' WHERE `item`='youtube_nacenka' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_min_hits' WHERE `item`='youtube_min_hits' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_min_hits_vip' WHERE `item`='youtube_min_hits_vip' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_color' WHERE `item`='youtube_cena_color' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_active' WHERE `item`='youtube_cena_active' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_timer_ot' WHERE `item`='youtube_timer_ot' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_timer_do' WHERE `item`='youtube_timer_do' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_timer' WHERE `item`='youtube_cena_timer' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_revisit_1' WHERE `item`='youtube_cena_revisit' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_revisit_2' WHERE `item`='youtube_cena_revisit' AND `howmany`='2'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_nolimit' WHERE `item`='youtube_cena_nolimit' AND `howmany`='0'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_nolimit_1' WHERE `item`='youtube_cena_nolimit' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_nolimit_2' WHERE `item`='youtube_cena_nolimit' AND `howmany`='2'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_nolimit_3' WHERE `item`='youtube_cena_nolimit' AND `howmany`='3'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_nolimit_4' WHERE `item`='youtube_cena_nolimit' AND `howmany`='4'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_uplist' WHERE `item`='youtube_cena_uplist' AND `howmany`='1'") or die(mysql_error());

		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_unic_ip_1' WHERE `item`='youtube_cena_unic_ip' AND `howmany`='1'") or die(mysql_error());
		mysql_query("UPDATE `tb_config` SET `price`='$youtube_cena_unic_ip_2' WHERE `item`='youtube_cena_unic_ip' AND `howmany`='2'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok" style="margin-bottom:0;">��������� ������� ���������. '.($testdrive_reset==1 ? "������������� ������� TestDrive �������� ��� ���� �������������!" : false).'</span>';

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.(isset($_GET["op"]) ? limpiar($_GET["op"]) : false).'");
			}, 1000);
			HideMsg("info-msg", 2000);
		</script>';
	}
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_hits' AND `howmany`='1'");
$youtube_cena_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_hits_bs' AND `howmany`='1'");
$youtube_cena_hits_bs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_nacenka' AND `howmany`='1'");
$youtube_nacenka = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits' AND `howmany`='1'");
$youtube_min_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits_vip' AND `howmany`='1'");
$youtube_min_hits_vip = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_color' AND `howmany`='1'");
$youtube_cena_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_active' AND `howmany`='1'");
$youtube_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_ot' AND `howmany`='1'");
$youtube_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_do' AND `howmany`='1'");
$youtube_timer_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_timer' AND `howmany`='1'");
$youtube_cena_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_revisit' AND `howmany`='1'");
$youtube_cena_revisit_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_revisit' AND `howmany`='2'");
$youtube_cena_revisit_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='0'");
$youtube_cena_nolimit = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='1'");
$youtube_cena_nolimit_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='2'");
$youtube_cena_nolimit_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='3'");
$youtube_cena_nolimit_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='4'");
$youtube_cena_nolimit_4 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_uplist' AND `howmany`='1'");
$youtube_cena_uplist = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_unic_ip' AND `howmany`='1'");
$youtube_cena_unic_ip_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_unic_ip' AND `howmany`='2'");
$youtube_cena_unic_ip_2 = mysql_result($sql,0,0);


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_youtube_status' AND `howmany`='1'");
$testdrive_youtube_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_youtube_count' AND `howmany`='1'");
$testdrive_youtube_count = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_youtube_timer' AND `howmany`='1'");
$testdrive_youtube_timer = mysql_result($sql,0,0);

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
	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">YouTube �������</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr align="left"><td><b>���� �� 1 ���� ������������ �������</b>, (���.)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_hits" value="'.$youtube_cena_hits.'" class="ok12" style="text-align:right;"></td></tr>';
		//echo '<tr align="left"><td><b>���� �� 1 ���� ��������� �������</b>, (���.)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_hits_bs" value="'.$youtube_cena_hits_bs.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>���� �� ��������� ������</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_color" value="'.$youtube_cena_color.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>���� �� �������� ����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_active" value="'.$youtube_cena_active.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������</b>, (���.)<b>:</b></td><td align="right">�� <input type="text" name="youtube_timer_ot" value="'.$youtube_timer_ot.'" class="ok12" style="text-align:center; width:40px;"> �� <input type="text" name="youtube_timer_do" value="'.$youtube_timer_do.'" class="ok12" style="text-align:center; width:40px;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� ������ �� 1 ���.</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_timer" value="'.$youtube_cena_timer.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� �������� ��� ��������� ������ 48 �����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_revisit_1" value="'.$youtube_cena_revisit_1.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� �������� ��� ��������� 1 ���</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_revisit_2" value="'.$youtube_cena_revisit_2.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>����������� �����</b>, (��.)<b>:</b></td><td align="right"><input type="text" name="youtube_min_hits" value="'.$youtube_min_hits.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>����������� ����� VIP</b>, (��.)<b>:</b></td><td align="right"><input type="text" name="youtube_min_hits_vip" value="'.$youtube_min_hits_vip.'" class="ok12" style="text-align:right;"></td></tr>';

		echo '<tr align="left"><td><b>�������� ������ � ������</b>, (���.)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_uplist" value="'.$youtube_cena_uplist.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� ���������� IP, 100% ����������</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_unic_ip_1" value="'.$youtube_cena_unic_ip_1.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>������� �� ���������� IP, �� ����� �� 2 �����</b>, (���./��������)<b>:</b></td><td align="right"><input type="text" name="youtube_cena_unic_ip_2" value="'.$youtube_cena_unic_ip_2.'" class="ok12" style="text-align:right;"></td></tr>';

		echo '<tr align="left"><td><b>������� �������</b>, (%)<b>:</b></td><td align="right"><input type="text" name="youtube_nacenka" value="'.$youtube_nacenka.'" class="ok12" style="text-align:right;"></td></tr>';
	echo '</table>';

echo '</td><td width="50%" valign="top" style="margin:0 auto; padding:0 0 0 2px; border:none; background:none;">';
	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">����������� �������</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr align="left"><td><b>1 ������</b>, (���.)</td><td align="right"><input type="text" name="youtube_cena_nolimit_1" value="'.$youtube_cena_nolimit_1.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>2 ������</b>, (���.)</td><td align="right"><input type="text" name="youtube_cena_nolimit_2" value="'.$youtube_cena_nolimit_2.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>3 ������</b>, (���.)</td><td align="right"><input type="text" name="youtube_cena_nolimit_3" value="'.$youtube_cena_nolimit_3.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>1 �����</b>, (���.)</td><td align="right"><input type="text" name="youtube_cena_nolimit_4" value="'.$youtube_cena_nolimit_4.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '<tr align="left"><td><b>C����� ������� ����� XXX ����������, (1 �����):</b></td><td align="right"><input type="text" name="youtube_cena_nolimit" value="'.$youtube_cena_nolimit.'" class="ok12" style="text-align:right;"></td></tr>';
	echo '</table><br>';

	echo '<h3 class="sp" style="margin-top:0; padding-top:0;">Test Drive</h3>';
	echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ ���������</b></td>';
			echo '<td align="right">';
				echo '<select name="testdrive_youtube_status" class="ok12" style="width:126px; text-align:center;">';
					echo '<option value="0" '.("0" == $testdrive_youtube_status ? 'selected="selected"' : false).'>��������</option>';
					echo '<option value="1" '.("1" == $testdrive_youtube_status ? 'selected="selected"' : false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>������</b>, (���.)</td>';
			echo '<td align="right"><input type="text" name="testdrive_youtube_timer" value="'.$testdrive_youtube_timer.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '</tr>';
		echo '<tr align="left">';
			echo '<td><b>���������� �������</b>, (��.)</td>';
			echo '<td align="right"><input type="text" name="testdrive_youtube_count" value="'.$testdrive_youtube_count.'" class="ok12" style="text-align:right;"></td></tr>';
		echo '</tr>';

		echo '<tr align="left">';
			echo '<td colspan="2" style="padding:3px 5px; border-right:1px solid #DDDDDD;">';
				echo '<input type="checkbox" id="testdrive_reset" name="testdrive_reset" value="1" style="width:18px; height:18px; margin:0px; padding:0; display:block; float:left;" />';
				echo '<span style="margin-top:2px; padding-left:5px; font-weight:bold; display:block; float:left;"> - �������� ������������ ������� TestDrive ��� ���� �������������</span>';
			echo '</td>';
		echo '</tr>';

	echo '</table>';

echo '</td></tr>';
echo '<tr align="center"><td colspan="2" style="border:none; background:none; padding-top:10px;"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</table>';
echo '</form>';

?>