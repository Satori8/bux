<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0; width:600px;"><b>��������� �������������� ���������</b></h3>';

if(count($_POST)>0) {
	$option = ( isset($_POST["option"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiar($_POST["option"])) ) ? limpiar($_POST["option"]) : false;

	if($option=="edit_config") {
		$cena_one_shares = isset($_POST["cena_one_shares"]) ? number_format(abs(trim($_POST["cena_one_shares"])), 2, ".", "") : false;
		$comis_sys_birj = isset($_POST["comis_sys_birj"]) ? number_format(abs(trim($_POST["comis_sys_birj"])), 2, ".", "") : false;
		$min_reit_for_shares = isset($_POST["min_reit_for_shares"]) ? number_format(abs(trim($_POST["min_reit_for_shares"])), 0, ".", "") : false;
		$cena_min_shares_birj = isset($_POST["cena_min_shares_birj"]) ? number_format(abs(trim($_POST["cena_min_shares_birj"])), 2, ".", "") : false;
		$cena_max_shares_birj = isset($_POST["cena_max_shares_birj"]) ? number_format(abs(trim($_POST["cena_max_shares_birj"])), 2, ".", "") : false;

		for($i=1; $i<=8; $i++) {
			$proc_dividend[$i] = isset($_POST["proc_dividend"][$i]) ? number_format(abs(trim($_POST["proc_dividend"][$i])), 0, ".", "") : false;
		}

		mysql_query("UPDATE `tb_invest_config` SET `price`='$cena_one_shares' WHERE `item`='cena_one_shares'") or die(mysql_error());
		mysql_query("UPDATE `tb_invest_config` SET `price`='$comis_sys_birj' WHERE `item`='comis_sys_birj'") or die(mysql_error());
		mysql_query("UPDATE `tb_invest_config` SET `price`='$min_reit_for_shares' WHERE `item`='min_reit_for_shares'") or die(mysql_error());
		mysql_query("UPDATE `tb_invest_config` SET `price`='$cena_min_shares_birj' WHERE `item`='cena_min_shares_birj'") or die(mysql_error());
		mysql_query("UPDATE `tb_invest_config` SET `price`='$cena_max_shares_birj' WHERE `item`='cena_max_shares_birj'") or die(mysql_error());

		for($i=1; $i<=8; $i++) {
			mysql_query("UPDATE `tb_invest_config` SET `price`='".$proc_dividend[$i]."' WHERE `item`='proc_dividend' AND `type`='$i'") or die(mysql_error());
		}

		echo '<span id="info-msg" class="msg-ok">��������� ������� ���������.</span>';
	}

	if($option=="add_shares") {
		$add_shares = isset($_POST["add_shares"]) ? number_format(abs(trim($_POST["add_shares"])), 0, ".", "") : false;

		mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'$add_shares' WHERE `item`='all_shares'") or die(mysql_error());
		mysql_query("UPDATE `tb_invest_config` SET `price`=`price`+'$add_shares' WHERE `item`='ost_shares'") or die(mysql_error());

		echo '<span id="info-msg" class="msg-ok">����� ������� ��������!</span>';
	}

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 10);
		HideMsg("info-msg", 1500);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares'");
$all_shares = number_format(mysql_result($sql,0,0), 0, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='ost_shares'");
$ost_shares = number_format(mysql_result($sql,0,0), 0, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='all_shares_buy'");
$all_shares_buy = number_format(mysql_result($sql,0,0), 0, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='money_shares_buy'");
$money_shares_buy = number_format(mysql_result($sql,0,0), 2, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='money_shares_sell'");
$money_shares_sell = number_format(mysql_result($sql,0,0), 2, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='pay_all_dividend'");
$pay_all_dividend = number_format(mysql_result($sql,0,0), 2, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_one_shares'");
$cena_one_shares = number_format(mysql_result($sql,0,0), 2, ".", "");

for($i=1; $i<=8; $i++) {
	$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='proc_dividend' AND `type`='$i'");
	$proc_dividend[$i] = number_format(mysql_result($sql,0,0), 0, ".", "");
}

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='comis_sys_birj'");
$comis_sys_birj = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='min_reit_for_shares'");
$min_reit_for_shares = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_min_shares_birj'");
$cena_min_shares_birj = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='cena_max_shares_birj'");
$cena_max_shares_birj = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='count_shares_buy_birj'");
$count_shares_buy_birj = number_format(mysql_result($sql,0,0), 0, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='money_shares_buy_birj'");
$money_shares_buy_birj = number_format(mysql_result($sql,0,0), 2, ".", "`");

$sql = mysql_query("SELECT `price` FROM `tb_invest_config` WHERE `item`='money_sys_birj'");
$money_sys_birj = number_format(mysql_result($sql,0,0), 2, ".", "`");

echo '<form method="POST" action="" id="newform" autocomplete="off">';
echo '<input type="hidden" name="option" value="edit_config">';
echo '<table class="tables" style="width:600px; margin-top:1px;">';
echo '<thead>';
echo '<tr>';
	echo '<th>��������</th>';
	echo '<th width="100">��������</th>';
	echo '<th width="30"></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
echo '<tr>';
	echo '<td align="left"><b>��������� ����� �����</b></td>';
	echo '<td align="center"><input type="text" name="cena_one_shares" value="'.$cena_one_shares.'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>����������� ������� ������������</b></td>';
	echo '<td align="center"><input type="text" name="min_reit_for_shares" value="'.$min_reit_for_shares.'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">������</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (�������, ������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>, ������, �����, ����� �������, ������������ ���������)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[1]" value="'.$proc_dividend[1].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (����-�������, �������)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[2]" value="'.$proc_dividend[2].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (������� - �������� � ������, �������� � VIP ����)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[3]" value="'.$proc_dividend[3].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (������� � ����� ��������� � ������ �� �������)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[5]" value="'.$proc_dividend[5].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (���-����� ������� �� �����)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[6]" value="'.$proc_dividend[6].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (������� ���������, ������� ������, �������� �������������)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[7]" value="'.$proc_dividend[7].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ������� �� �����</b>, (��� ��������� �������)</td>';
	echo '<td align="center"><input type="text" name="proc_dividend[4]" value="'.$proc_dividend[4].'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left"><b>�������� ������� ��� ������� ����� �� �����</b></td>';
	echo '<td align="center"><input type="text" name="comis_sys_birj" value="'.$comis_sys_birj.'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">%</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>����������� ��������� ����� ��� ������� �� �����</b></td>';
	echo '<td align="center"><input type="text" name="cena_min_shares_birj" value="'.$cena_min_shares_birj.'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������������ ��������� ����� ��� ������� �� �����</b></td>';
	echo '<td align="center"><input type="text" name="cena_max_shares_birj" value="'.$cena_max_shares_birj.'" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr align="center"><td colspan="3"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';

echo '<h3 class="sp" style="margin-top:20px; padding-top:0; width:600px;"><b>����������</b></h3>';
echo '<table class="tables" style="width:600px; margin-top:1px;">';
echo '<thead>';
echo '<tr>';
	echo '<th>��������</th>';
	echo '<th width="100">��������</th>';
	echo '<th width="30"></th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
echo '<tr>';
	echo '<td align="left"><b>����� �������� �����</b></td>';
	echo '<td align="center"><b>'.$all_shares.'</b></td>';
	echo '<td align="center">��.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>����� � �������</b>, ��������� ��� �������</td>';
	echo '<td align="center"><b>'.$ost_shares.'</b></td>';
	echo '<td align="center">��.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>����� � ����������</b></td>';
	echo '<td align="center"><b>'.$all_shares_buy.'</b></td>';
	echo '<td align="center">��.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ����� � �������� �� �����</b></td>';
	echo '<td align="center"><b>'.$money_shares_buy.'</b></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ����� �� �����</b></td>';
	echo '<td align="center"><b>'.$count_shares_buy_birj.'</b></td>';
	echo '<td align="center">��.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>������� ����� �� ����� �� �����</b></td>';
	echo '<td align="center"><b>'.$money_shares_buy_birj.'</b></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>����� ������� � �����</b></td>';
	echo '<td align="center"><b>'.$money_sys_birj.'</b></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '<tr>';
	echo '<td align="left"><b>��������� ����������</b></td>';
	echo '<td align="center"><b>'.$pay_all_dividend.'</b></td>';
	echo '<td align="center">���.</td>';
echo '</tr>';
echo '</table>';
echo '</table>';

echo '<h3 class="sp" style="margin-top:20px; padding-top:0; width:600px;"><b>������ �����</b></h3>';
echo '<form method="POST" action="" id="newform" autocomplete="off">';
echo '<input type="hidden" name="option" value="add_shares">';
echo '<table class="tables" style="width:auto; margin-top:1px;">';
echo '<tbody>';
echo '<tr>';
	echo '<td align="left" style="padding:2px 10px 2px 5px;"><b>������� ���������� ����� ��� �������</b></td>';
	echo '<td align="center" width="100"><input type="text" name="add_shares" value="100" class="ok12" style="text-align:center; width:90px;"></td>';
	echo '<td align="center" width="100"><input type="submit" value="���������" class="sub-green"></td>';
echo '</tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';

?>