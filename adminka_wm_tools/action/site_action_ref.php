<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0px; padding-top:0;"><b>�������� ������, ���������� � ������ ����� "�������� ����"</b></h3>';

function my_status($my_reiting){
	$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='$my_reiting' AND `r_do`>='".floor($my_reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_assoc($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_assoc($sql_rang);
	}
	return '<span style="cursor:pointer; color: #006699;" title="������">'.$row_rang["rang"].'</span>';
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status_ref' AND `howmany`='1'");
$site_action_status_ref = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `r_ot` FROM `tb_config_rang` WHERE `id`='$site_action_status_ref'");
$ref_reit_ot = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_date_ref' AND `howmany`='1'");
$site_action_date_ref = number_format(mysql_result($sql,0,0), 0, ".", "");


require("navigator/navigator.php");

$perpage = 30;
$sql_p = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='Admin' AND `user_status`='0' AND `ban_date`='0' AND `reiting`>='$ref_reit_ot' AND `lastlogdate2`>='".(time()-$site_action_date_ref*24*60*60)."'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

$attestat[100]='<font color="#000"><img src="/img/att/att_100.ico" alt="" align="absmiddle" border="0" /> �������� ����������</font>';
$attestat[110]='<font color="green"><img src="/img/att/att_110.ico" alt="" align="absmiddle" border="0" /> ���������� ��������</font>';
$attestat[120]='<font color="green"><img src="/img/att/att_120.ico" alt="" align="absmiddle" border="0" /> ��������� ��������</font>';
$attestat[130]='<font color="green"><img src="/img/att/att_130.ico" alt="" align="absmiddle" border="0" /> ������������ ��������</font>';
$attestat[135]='<font color="green"><img src="/img/att/att_135.ico" alt="" align="absmiddle" border="0" /> �������� ��������</font>';
$attestat[136]='<font color="green"><img src="/img/att/att_136.ico" alt="" align="absmiddle" border="0" /> �������� Capitaller</font>';
$attestat[140]='<font color="green"><img src="/img/att/att_140.ico" alt="" align="absmiddle" border="0" /> �������� ������������</font>';
$attestat[150]='<font color="green"><img src="/img/att/att_150.ico" alt="" align="absmiddle" border="0" /> �������� ������������</font>';
$attestat[170]='<font color="green"><img src="/img/att/att_170.ico" alt="" align="absmiddle" border="0" /> �������� �������</font>';
$attestat[190]='<font color="green"><img src="/img/att/att_190.ico" alt="" align="absmiddle" border="0" /> �������� �������</font>';
$attestat[300]='<font color="green"><img src="/img/att/att_300.ico" alt="" align="absmiddle" border="0" /> �������� ���������</font>';
$attestat[0]='<font color="red"></font>';
$attestat[1]='<font color="red"></font>';
$attestat[-1]='<font color="red"></font>';


$s = (isset($_GET["s"]) && preg_match("|^[\d]{1,2}$|", trim($_GET["s"])) && intval(limpiar(trim($_GET["s"])))>=0 && intval(limpiar(trim($_GET["s"])))<=14) ? abs(intval(limpiar(trim($_GET["s"])))) : "0";
$u = (isset($_GET["u"]) && preg_match("|^[\d]{1}$|", trim($_GET["u"])) && intval(limpiar(trim($_GET["u"])))>=0 && intval(limpiar(trim($_GET["u"])))<=1) ? abs(intval(limpiar(trim($_GET["u"])))) : "0";
$s_arr = array('id','username','reiting','visits','visits_a','visits_m','visits_t','visits_tests','money_rek','joindate2','lastlogdate2','referals','referals2','referals3','not_get_ref_a');
$u_arr = array('ASC','DESC');
$s_tab = $s_arr[$s];
$u_tab = $u_arr[$u];


if(isset($_POST["option"]) && limpiar($_POST["option"])=="not_get_ref_a") {
	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiar(trim($_POST["id"]))) : false;

	mysql_query("UPDATE `tb_users` SET `not_get_ref_a`='1' WHERE `id`='$id' AND `referer`='Admin' AND `user_status`='0' AND `not_get_ref_a`='0' AND `ban_date`='0'") or die(mysql_error());

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
		}, 150);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
}

if(isset($_POST["option"]) && limpiar($_POST["option"])=="get_ref") {
	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiar(trim($_POST["id"]))) : false;

	mysql_query("UPDATE `tb_users` SET `not_get_ref_a`='0' WHERE `id`='$id' AND `referer`='Admin' AND `user_status`='0' AND `not_get_ref_a`='1' AND `ban_date`='0'") or die(mysql_error());

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
		}, 150);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
}

$sql = mysql_query("SELECT * FROM `tb_users` WHERE `referer`='Admin' AND `user_status`='0' AND `ban_date`='0' AND `reiting`>='$ref_reit_ot' AND `lastlogdate2`>='".(time()-$site_action_date_ref*24*60*60)."' ORDER BY $s_tab $u_tab LIMIT $start_pos,$perpage");
$all_users = mysql_num_rows($sql);

$sql_act = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='Admin' AND `user_status`='0' AND `not_get_ref_a`='0' AND `ban_date`='0' AND `reiting`>='$ref_reit_ot' AND `lastlogdate2`>='".(time()-$site_action_date_ref*24*60*60)."'");
$count_act = mysql_numrows($sql_act);

echo '<table class="tables" style="margin:0; padding:0;"><tr>';
echo '<td valign="top">';
	echo '��������� �� �������� �����, �����: <b>'.$count.'</b><br>';
	echo '�������� ������� ����� ������, �����: <b>'.$count_act.'</b><br>';
	echo '�������� ������� �� ��������: <b>'.$all_users.'</b> �� <b>'.$count.'</b>';
echo '</td>';
echo '</tr>';
echo '</table>';

echo '<form method="GET" action="" id="newform">';
echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';

echo '<table class="tables" style="margin:0; padding:0;">';
echo '<tr>';
	echo '<td align="left" nowrap="nowrap" width="100">����������� ��:</td>';
	echo '<td align="center">';
		echo '<select name="s" class="ok">';
			echo '<option value="0" '.("0" == "$s" ? 'selected="selected"' : false).'>ID</option>';
			echo '<option value="1" '.("1" == "$s" ? 'selected="selected"' : false).'>������</option>';
			echo '<option value="2" '.("2" == "$s" ? 'selected="selected"' : false).'>��������</option>';
			echo '<option value="3" '.("3" == "$s" ? 'selected="selected"' : false).'>��������</option>';
			echo '<option value="4" '.("4" == "$s" ? 'selected="selected"' : false).'>����-��������</option>';
			echo '<option value="5" '.("5" == "$s" ? 'selected="selected"' : false).'>�������</option>';
			echo '<option value="6" '.("6" == "$s" ? 'selected="selected"' : false).'>��������</option>';
			echo '<option value="7" '.("7" == "$s" ? 'selected="selected"' : false).'>������</option>';
			echo '<option value="8" '.("8" == "$s" ? 'selected="selected"' : false).'>�������� �� �������</option>';
			echo '<option value="9" '.("9" == "$s" ? 'selected="selected"' : false).'>���� �����������</option>';
			echo '<option value="10" '.("10" == "$s" ? 'selected="selected"' : false).'>���� ����������</option>';
			echo '<option value="11" '.("11" == "$s" ? 'selected="selected"' : false).'>��������� 1 ������</option>';
			echo '<option value="12" '.("12" == "$s" ? 'selected="selected"' : false).'>��������� 2 ������</option>';
			echo '<option value="13" '.("13" == "$s" ? 'selected="selected"' : false).'>��������� 3 ������</option>';
			echo '<option value="14" '.("14" == "$s" ? 'selected="selected"' : false).'>�� ��� �����</option>';
		echo '</select>';
	echo '</td>';
	echo '<td align="center" width="150">';
		echo '<select name="u" class="ok">';
			echo '<option value="0" '.("0" == "$u" ? 'selected="selected"' : false).'>�� �����������</option>';
			echo '<option value="1" '.("1" == "$u" ? 'selected="selected"' : false).'>�� ��������</option>';
		echo '</select>';
	echo '</td>';
	echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" class="sub-blue" value="�����������"></td>';
echo '</tr>';
echo '</table>';
echo '<input type="hidden" name="page" value="'.$page.'">';
echo '</form>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op&s=$s&u=$u");

echo '<table class="tables" style="margin:1px auto;">';
echo '<tr style="text-align:center;" align="center">';
	echo '<th style="text-align:center;">ID</th>';
	echo '<th style="text-align:center;" colspan="2">������������</th>';
	echo '<th style="text-align:center;">����������</th>';
	echo '<th style="text-align:center;">��������</th>';
	echo '<th style="text-align:center;">���.����<br>����.����</th>';
	echo '<th style="text-align:center;">���������<br>���������</th>';
	echo '<th style="text-align:center;">IP ���-��<br>IP �����</th>';
	echo '<th style="text-align:center;">���� ���-��<br>���� �����</th>';
	echo '<th style="text-align:center;">��������</th>';
echo '</tr>';

if($all_users>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr align="center">';
			echo '<td>'.$row["id"].'</td>';

			echo '<td align="center" valign="top" width="60px" style="border-right:none; padding-right:0;">';
				echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" width="60" height="60" border="0" alt="avatar" title="avatar" />';
			echo '</td>';

			echo '<td align="left" valign="top" style="border-left:none; padding-left:2px; padding-top:7px;">';
				echo '<b style="cursor:pointer;" title="�����">'.$row["username"].'</b><br>';

				echo '<img src="/img/reiting.png" border="0" alt="" align="absmiddle" title="�������" style="cursor:pointer; margin:0; padding:0;" />&nbsp;<span style="color:blue; cursor:pointer;" title="�������">'.round($row["reiting"], 2).'</span>&nbsp;&nbsp;';
				echo '<b>'.my_status($row["reiting"]).'</b><br>';

				if(trim($row["wmid"])!=false) {
					echo '<a href="https://passport.webmoney.ru/asp/certview.asp?wmid='.$row["wmid"].'" target="_blank" title="WMID">'.$row["wmid"].'</a><br>';
					echo '<img src="/img/att/att_'.@$row["attestat"].'.ico" alt="" width="20" height="20" align="absmiddle" border="0" /> ';
				}
				if($row["country_cod"]!="") {
					echo '<img src="http://'.$_SERVER["HTTP_HOST"].'/img/flags/'.@strtolower($row["country_cod"]).'.gif" alt="" title="'.get_country($row["country_cod"]).'" width="16" height="11" style="margin:0; padding:0;" align="absmiddle" />&nbsp; ';
				}
				if($row["ban_date"]>0) echo '&nbsp;<b style="color:#FF0000;">��������</b>';
				if($row["http_ref"]!="") {echo '<br>������ � '.$row["http_ref"];}
			echo '</td>';

			echo '<td align="center">';
				echo '<table align="center" width="100%" style="margin:0; padding:0;"><tr>';
					echo '<tr><td align="left" style="border:none; padding:1px 3px; margin:0; height:13px;">�������:</td><td align="right" style="border:none; padding:1px 3px; margin:0; height:13px;"><b>'.number_format($row["visits"],0,".","'").'</b></td></tr>';
					echo '<tr><td align="left" style="border:none; padding:1px 3px; margin:0; height:13px;">����-�������:</td><td align="right" style="border:none; padding:1px 3px; margin:0; height:13px;"><b>'.number_format($row["visits_a"],0,".","'").'</b></td></tr>';
					echo '<tr><td align="left" style="border:none; padding:1px 3px; margin:0; height:13px;">������:</td><td align="right" style="border:none; padding:1px 3px; margin:0; height:13px;"><b>'.number_format($row["visits_m"],0,".","'").'</b></td></tr>';
					echo '<tr><td align="left" style="border:none; padding:1px 3px; margin:0; height:13px;">�������:</td><td align="right" style="border:none; padding:1px 3px; margin:0; height:13px;"><b>'.number_format($row["visits_t"],0,".","'").'</b></td></tr>';
					echo '<tr><td align="left" style="border:none; padding:1px 3px; margin:0; height:13px;">�����:</td><td align="right" style="border:none; padding:1px 3px; margin:0; height:13px;"><b>'.number_format($row["visits_tests"],0,".","'").'</b></td></tr>';
					echo '<tr><td align="left" style="border:none; padding:1px 3px; margin:0; height:13px;">�������:</td><td align="right" style="border:none; padding:1px 3px; margin:0; height:13px;"><b>'.number_format($row["money_rek"],2,".","'").'</b></td></tr>';
			        echo '</table>';
			echo '</td>';

			echo '<td><b>'.$row["referals"].'</b> - <b>'.$row["referals2"].'</b> - <b>'.$row["referals3"].'</b></td>';

			echo '<td><b style="color:#0000EE;">'.number_format($row["money"],4,"."," ").'</b><br><br><b style="color:#00688B;">'.number_format($row["money_rb"],2,"."," ").'</b></td>';
			echo '<td><b style="color:#0000EE;">'.number_format($row["money_out"],2,"."," ").'</b><br><br><b style="color:#00688B;">'.number_format($row["money_in"],2,"."," ").'</b></td>';

			echo '<td>';
				if($row["lastiplog"]==false) $row["lastiplog"] = $row["ip"];
				$ip_reg_exp = explode(".", $row["ip"]);
				$ip_reg_1 = $ip_reg_exp[0]; $ip_reg_2 = $ip_reg_exp[1]; $ip_reg_3 = $ip_reg_exp[2]; $ip_reg_4 = $ip_reg_exp[3];

				$ip_last_exp = explode(".", $row["lastiplog"]);
				$ip_last_1 = $ip_last_exp[0]; $ip_last_2 = $ip_last_exp[1]; $ip_last_3 = $ip_last_exp[2]; $ip_last_4 = $ip_last_exp[3];

				$color_red = ' style="color:#FF0000;"'; $color_green = ' style="color:#008B00;"';

				if($row["ip"]=="") {
					echo "-";
				}else{
					echo '<b'.$color_green.'>'.$row["ip"].'</b>';
				}
				echo '<br><br>';

				if($row["lastiplog"]=="") {
					echo "-";
				}else{
					$color_1 = $color_green; $color_2 = $color_green; $color_3 = $color_green; $color_4 = $color_green;

					if($ip_reg_1!=$ip_last_1) {
						$color_1 = $color_red; $color_2 = $color_red; $color_3 = $color_red; $color_4 = $color_red;
					}elseif($ip_reg_2!=$ip_last_2) {
						$color_2 = $color_red; $color_3 = $color_red; $color_4 = $color_red;
					}elseif($ip_reg_3!=$ip_last_3) {
						$color_3 = $color_red; $color_4 = $color_red;
					}elseif($ip_reg_4!=$ip_last_4) {
						$color_4 = $color_red;
					}

					echo '<b'.$color_1.'>'.$ip_last_1.'</b><b'.$color_2.'>.'.$ip_last_2.'</b><b'.$color_3.'>.'.$ip_last_3.'</b><b'.$color_4.'>.'.$ip_last_4.'</b>';
				}
			echo '</td>';


			echo '<td>';
				if(DATE("d.m.Y", $row["joindate2"])==DATE("d.m.Y", time())) {
					echo '<b style="color:#008B00">�������, � '.DATE("H:i", $row["joindate2"]).'</b>';
				}elseif(DATE("d.m.Y", $row["joindate2"])==DATE("d.m.Y", (time()-24*60*60))) {
					echo '<b style="color:#528B8B">�����, � '.DATE("H:i", $row["joindate2"]).'</b>';
				}else{
					echo '<b>'.DATE("d.m.Y�. H:i", $row["joindate2"]).'</b>';
				}
				echo '<br><br>';
				if(DATE("d.m.Y", $row["lastlogdate2"])==DATE("d.m.Y", time())) {
					echo '<b style="color:#008B00">�������, � '.DATE("H:i", $row["lastlogdate2"]).'</b>';
				}elseif(DATE("d.m.Y", $row["lastlogdate2"])==DATE("d.m.Y", (time()-24*60*60))) {
					echo '<b style="color:#528B8B">�����, � '.DATE("H:i", $row["lastlogdate2"]).'</b>';
				}else{
					echo '<b>'.DATE("d.m.Y�. H:i", $row["lastlogdate2"]).'</b>';
				}
			echo '</td>';
			echo '<td width="100" nowrap="nowrap">';
				if($row["not_get_ref_a"]==0) {
					echo '<span style="color:green">������� ����� ���� ����� � �������� �����</span>';
					echo '<form method="POST" action="" /*onClick=\'if(!confirm("�� ������� ��� �� ������ �������� ������������ '.$row["username"].' � �������� ����� � ���������?")) return false;\'*/>';
						echo '<input type="hidden" name="id" value="'.$row["id"].'">';
						echo '<input type="hidden" name="option" value="not_get_ref_a">';
						echo '<input type="submit" value="�� ��������" class="sub-red" style="float:none;">';
					echo '</form>';
				}else{
					echo '<span style="color:red">������� �� ����� ����� � �������� �����</span>';
					echo '<form method="POST" action="" /*onClick=\'if(!confirm("�� ������� ��� ������ �������� ������������ '.$row["username"].' � �������� ����� � ���������?")) return false;\'*/>';
						echo '<input type="hidden" name="id" value="'.$row["id"].'">';
						echo '<input type="hidden" name="option" value="get_ref">';
						echo '<input type="submit" value="��������" class="sub-green" style="float:none;">';
					echo '</form>';
				}
			echo '</td>';
		echo '</tr>';
	}
	echo '</table>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op&s=$s&u=$u");
}else{
	echo '<tr>';
		echo '<td colspan="11" align="center" style="padding:0;"><span class="msg-error" style="margin:0;">������������� �� �������</span></td>';
	echo '</tr>';
	echo '</table>';
}


?>