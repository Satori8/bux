<?php
require_once('.zsecurity.php');
$pagetitle = "���������� ������������";
include('header.php');

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_ref' AND `howmany`='1'") or die(mysql_error());
$bon_reg_ref = mysql_result($sql,0,0);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
	include('footer.php');
	exit();
}else{
    ?><script type="text/javascript" src="/js/highcharts.js"></script>
	<script type="text/javascript">
	var tm;
	var LoadBlock = false;
	var StatsBlock = false;

	function HideMsg(id, timer) {
		clearTimeout(tm);
		tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
		return false;
	}

	function LoadStatUser(uid, token) {
		if(LoadBlock) {
			return false;
		} else if(StatsBlock == uid) {
			StatsBlock = false;
			$("#cat-title-stat").attr("class", "cat-title-close");
			$("#cat-block-stat").slideToggle('slow');
			$("#cat-block-stat").html("");
			return false;
		} else {
			$("#cat-title-stat").attr("class", "cat-title-open");
			$.ajax({
				type: "POST", url: "ajax/ajax_stat_user.php", data: {'op':'LoadStat', 'uid':uid, 'token':token}, dataType: 'json',
				error: function() { $("#loading").slideToggle(); LoadBlock = false; StatsBlock = uid; $("#cat-block-stat").html('<span class="msg-error">������ AJAX!</span>').slideToggle('slow'); },
				beforeSend: function() { $("#loading").slideToggle(); LoadBlock = true; }, 
				success: function(data) { $("#loading").slideToggle(); LoadBlock = false; StatsBlock = uid; $("#cat-block-stat").html(data.message).slideToggle(400); return false; }
			});
			return false;
		}
	}
	
	function FuncStatRef(op, token, title_win) {
		if(!LoadBlock){$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_stat_ref.php", dataType: 'json', data: { 'op':op, 'token':token }, 
			error: function(request, status, errortext) { LoadBlock = false; $("#loading").slideToggle(); alert("������ Ajax! \n"+status+"\n"+errortext); console.log(status, errortext, request); },
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false; $("#loading").slideToggle();
				var result = data.result!=undefined ? data.result : data;
				var message = data.message!=undefined ? data.message : data;
				title_win = (!title_win | result!="OK") ? "������" : title_win;

				if (result == "OK") {
					ModalStart(title_win, (title_win=="������" ? StatusMsg('ERROR', message) : message), 700, true, true, false);
				} else {
					ModalStart(title_win, StatusMsg(result, message), 500, true, true, false);
				}
			}
		});}
		return false;
	}
	</script>
	<?php
	$username = uc($_SESSION["userLog"]);

	require('config.php');

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
	$dlink_cena_hits = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_hits' AND `howmany`='1'");
$youtube_cena_hits = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
	$dlink_cena_hits_bs = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
	$cena_hits_aserf = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='3'");
	$cena_mails_3 = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
	$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
	$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

	$tests_cena_user = ($tests_cena_hit / (($tests_nacenka+100)/100));

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
	$cena_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT * FROM tb_users WHERE username='$username'");
	$row = mysql_fetch_array($sql);

	$sql_r = mysql_query("SELECT * FROM tb_users WHERE username='".$row["referer"]."'");
	if(mysql_num_rows($sql_r)>0) {
		$row_r = mysql_fetch_array($sql_r);
	}else{
		$row_r["ref_bonus2"] = 0;
	}

	if($row["referer"]!=false) {
		$wall_com = $row_r["wall_com_p"] - $row_r["wall_com_o"];
		$info_wall = '<img src="img/reiting.png" border="0" alt="" align="middle" title="�������" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_r["reiting"], 2).'</span>, ';
		$info_wall.= '<a href="/wall.php?uid='.$row_r["id"].'" target="_blank"><img src="../img/wall20.png" title="����� ������������ '.$row_r["username"].'" width="20" border="0" align="absmiddle" />';
		if($wall_com>0) {
			$info_wall.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
		}elseif($wall_com<0) {
			$info_wall.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
		}else{
			$info_wall.= '<b style="color:#000000;">0</b></a>';
		}
		$info_wall.= "&nbsp;&nbsp;";
	}else{
		$info_wall="";
	}


	if(isset($_GET["passport"]) && limpiar($_GET["passport"])=="upgrade") {
		include('auto_pay_req/wmxml.inc.php');
		$r = _WMXML11($row["wmid"]);
		$attkod = $r['att'];

		mysql_query("UPDATE tb_users SET attestat='$attkod' WHERE username='$username'") or die(mysql_error());

		echo '<script type="text/javascript">location.replace("'.$_SERVER['PHP_SELF'].'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER['PHP_SELF'].'"></noscript>';
	}

	$autoref = $row["autoref"];
		if($autoref=="0") {$active='<font color="#FF0000">���<font>';}
		if($autoref=="1") {$active='<font color="#00cc00">��</font>';}

	$attestat[100]='<font color="#00cc00">�������� ����������</font>';
	$attestat[110]='<font color="#00cc00">���������� ��������</font>';
	$attestat[120]='<font color="#00cc00">��������� ��������</font>';
	$attestat[130]='<font color="#00cc00">������������ ��������</font>';
	$attestat[135]='<font color="#00cc00">�������� ��������</font>';
	$attestat[136]='<font color="#00cc00">�������� Capitaller</font>';
	$attestat[140]='<font color="#00cc00">�������� ������������</font>';
	$attestat[150]='<font color="#00cc00">�������� ������������</font>';
	$attestat[170]='<font color="#00cc00">�������� �������</font>';
	$attestat[190]='<font color="#00cc00">�������� �������</font>';
	$attestat[300]='<font color="#00cc00">�������� ���������</font>';
	$attestat[0]='<font color="red">����������</font>';

	$block_agent = $row["block_agent"];
	$block_wmid = $row["block_wmid"];
	if($block_wmid=="0" && $block_agent=="0") $levelsecurity = '<font color="red">������!</font>';
	elseif($block_wmid=="1" && $block_agent=="0")	$levelsecurity = '<font color="orange">�������!</font>';
	elseif($block_wmid=="0" && $block_agent!="0")	$levelsecurity = '<font color="orange">�������!</font>';
	elseif($block_wmid=="1" && $block_agent!="0")	$levelsecurity = '<font color="#006400">�������!</font>';
	else $levelsecurity = '<font color="red">����������</font>';

	$sql = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='wait' AND `rek_name`='$username'");
	$kol_zd_w = mysql_num_rows($sql);

	$sql_zd_b = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`!='' AND `user_name`='$username' AND `ban_user`='1'");
	$kol_zd_b = mysql_num_rows($sql_zd_b);

	if($row["referer"]!="") {
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center" class="top" width="50%">������ �� ������ ��������</th>';
			echo '<th align="center" class="top" width="50%">������ ������</th>';
		echo '</tr></thead>';

		$sql_b = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`>'0' AND `username`='$my_referer' ORDER BY `type_bon` ASC");
		if(mysql_num_rows($sql_b)>0) {
			while ($row_b = mysql_fetch_array($sql_b)) {
				echo '<tr align="center">';
					$sql_b_stat = mysql_query("SELECT `status` FROM `tb_refbonus_stat` WHERE /*`status`='1' AND*/ `username`='$username' AND `type`='1'");
					if(mysql_num_rows($sql_b_stat)>0) {
						$row_b_stat = mysql_fetch_row($sql_b_stat);
						$status_bonus_1 = $row_b_stat["0"];
						if($status_bonus_1==-1) {
							$type_bon_reg_email = '<br><span style="color:#FF0000;">���������� ����������� e-mail</span>';
						}else{
							$type_bon_reg_email = false;
						}
					}else{
						$status_bonus_1 = 1;
						$type_bon_reg_email = false;
					}
					if($row_b["type_bon"]==1) {
						if($row["ref_bonus_get_1"]==0 && $status_bonus_1!=1) $type_bon="�� ����������� $type_bon_reg_email";
						else $type_bon = false;
					}elseif($row_b["type_bon"]==2) {
						if($row["ref_bonus_add"]==1) $type_bon="�� ����������� ���������, ������� ������ ���������� �� ����� <b>".$row_b["count_nado"]."</b> ������ � ��������";
						else $type_bon = false;
					}elseif($row_b["type_bon"]==3) {
						$type_bon="�� �������� ������ <b>".$row_b["count_nado"]."</b> ������ � ��������";
					}elseif($row_b["type_bon"]==4) {
						$type_bon="�� ���������� ������ <b>".$row_b["count_nado"]."</b> �������";
					}elseif($row_b["type_bon"]==5) {
						$type_bon="�� �������� ������ <b>".$row_b["count_nado"]."</b> ������������ � <span style='color: #3F3F3F;'>You</span><span style='border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;'>Tube</span> ��������";
					}elseif($row_b["type_bon"]==6) {
							$type_bon = '<b>�� ��������� ������ '.number_format($row_b["count_nado"],2,'.','`').' ���.</b>';   
					}

					if(isset($type_bon) && $type_bon!=false) echo '<td>'.$type_bon.'</td><td><b>'.$row_b["bonus"].'</b> ���.</td>';
				echo '</tr>';
			}
		}

		echo '<tr align="center"><td>��� ������</td><td><b>'.$row["ref_back"].'%</b></td></tr>';

		echo '</table><br>';
	}
/*
	$token_ajax = md5($username.$username.$row["id"]);
	echo '<table class="tables" style="margin-top:5px;">';
		echo '<thead><tr><th>';
			echo '���������� ������������';
			echo '<span id="img_stat'.$row["id"].'" onclick="ShowHideStats(\''.$row["id"].'\', \''.$token_ajax.'\');" style="cursor: pointer; float:right;"><img src="/img/down_blue20.png" align="middle" border="0" alt="" title="�������� ����������" style="margin:0; padding:0; padding-right:5px;" /></span>';
	echo '</table>';
	echo '<div id="usersstat'.$row["id"].'" style="display: none;"></div><br>';
*/

$token_stat = strtolower(md5($row["id"].strtolower($username).strtolower($username)."STAT_USER"));
	echo '<span id="cat-title-stat" class="cat-title-close" onClick="LoadStatUser(\''.$row["id"].'\', \''.$token_stat.'\'); return false;">��� ����������</span>';
	echo '<div id="cat-block-stat" style="display:none; margin: 0px 0px 15px; background:#F7F7F7;"></div><br>';
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_click_ref' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						    $konk_ref_min_click_ref = mysql_result($sql,0,0);
	
	echo '<table class="tables">';
	echo '<thead><tr><th class="top" colspan="2">����&emsp;&emsp;<input style="color:#FF0000;width:360px;text-align:center;margin:3px;padding:3px;type=" text"="" value="https://scorpionbux.info/?r='.$partnerid.'" onfocus="this.select();" readonly="" class="ok">&emsp;&emsp;����������� ������</th></tr></thead>';
		//echo '<tr><thead><th class="top" colspan="2">��������:</th></tr></thead>';
		if($bon_reg_ref>0) echo '<tr><td width="50%"><span style="color:#DE1200">����� �� ����������� �������� �� ������� -  <b>'.number_format($bon_reg_ref, 2, ".", "").' ���.</span></b></td><td style="text-align:right;color:#0000ff;">������� ������ ������� �� ����� <b>'.$konk_ref_min_click_ref,'</b> ������ � ��������!</td></tr>';
		echo '<tr><td width="50%">������� ������ �������� [<a href="/profile.php">��������</a>]</td><td style="text-align:right;">'.$levelsecurity.'</td></tr>';
		echo '<tr><td>��� ID</td><td style="text-align:right;">'.number_format($row["id"],0,'.','`').'</td></tr>';
		echo '<tr><td>���� � ����� ����������� �� �������</td><td style="text-align:right;">'.DATE("d.m.Y�. � H:i",$row["joindate2"]).'</td></tr>';
		echo '<tr><td>���� ���������� ����� � �������</td><td style="text-align:right;">'.DATE("d.m.Y�. � H:i",$row["lastlogdate2"]).'</td></tr>';
		echo '<tr><td>��� �������</td><td style="text-align:right;">'.($row["referer"]!=false ? $row["referer"]." ".$info_wall.' [<a href="/prml.php?to='.$row["referer"].'">���������</a>]' : "���").'</td></tr>';
		echo '<tr><td>�������� ����, ���.</td><td style="text-align:right;">'.number_format($money,4,'.','`').'</td></tr>';
		echo '<tr><td>��������� ����, ���.</td><td style="text-align:right;">'.number_format($money_rb,4,'.','`').'</td></tr>';
		echo '<tr><td>��������� [<a href="/history.php" title="����������� ������� ��������">������� ��������</a>]</td><td style="text-align:right;">'.number_format($row["money_out"],2,".","`").'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><thead><th class="top" colspan="2">���������� ����������:</th></tr></thead>';
		echo '<tr><td>����������� ������ � ��������</td><td style="text-align:right;">'.number_format($row["visits"],0,".","`").'</td></tr>';
		echo '<tr><td class="stat" width="50%">����������� ������������ � �������� <b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b</td><td class="stat" style="text-align:right;">'.number_format($row["visits_you"],0,".","`").'</td></tr>';
		echo '<tr><td>����������� ������ � ��������� ��������</td><td style="text-align:right;">'.number_format($row["visits_bs"],0,".","`").'</td></tr>';
		echo '<tr><td>����������� ������ � ����-��������</td><td style="text-align:right;">'.number_format($row["visits_a"],0,".","`").'</td></tr>';
		echo '<tr><td>��������� �����</td><td style="text-align:right;">'.number_format($row["visits_m"],0,".","`").'</td></tr>';
		echo '<tr><td>��������� ������</td><td style="text-align:right;">'.number_format($row["visits_tests"],0,".","`").'</td></tr>';
		echo '<tr><td>��������� �������</td><td style="text-align:right;">'.number_format($row["visits_t"],0,".","`").'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><thead><th class="top" colspan="2">���������� ���������:</th></tr></thead>';
		echo '<tr><td>���������� �� ��������</td><td style="text-align:right;">'.number_format($row["money_ds"],4,".","`").'</td></tr>';
		echo '<tr><td class="stat" width="50%">���������� �� �������� <b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b</td><td class="stat" style="text-align:right;">'.number_format($row["money_you"],4,".","`").'</td></tr>';
		echo '<tr><td>���������� �� ��������� ��������</td><td style="text-align:right;">'.number_format($row["money_bs"],4,".","`").'</td></tr>';
		echo '<tr><td>���������� �� ����-��������</td><td style="text-align:right;">'.number_format($row["money_a"],4,".","`").'</td></tr>';
		echo '<tr><td>���������� �� ��������� �����</td><td style="text-align:right;">'.number_format($row["money_m"],4,".","`").'</td></tr>';
		echo '<tr><td>���������� �� ������, ���.</td><td style="text-align:right;">'.number_format($row["money_tests"],4,".","`").'</td></tr>';
		echo '<tr><td>���������� �� ��������, ���.</td><td style="text-align:right;">'.number_format($row["money_t"],4,".","`").'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><thead><th class="top" colspan="2">��������� ����������:</th></tr></thead>';
		echo '<tr><td width="50%">�������, ���.</td><td style="text-align:right;">�� '.number_format($dlink_cena_hits,4,".","`").'</td></tr>';
		echo '<tr><td class="stat" width="50%">������� <b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b, ���.</td><td class="stat" style="text-align:right;">�� '.number_format($youtube_cena_hits,4,".","`").'</td></tr>';
		echo '<tr><td>��������� �������, ���.</td><td style="text-align:right;">�� '.number_format($dlink_cena_hits_bs,4,".","`").'</td></tr>';
		echo '<tr><td>����-�������, ���.</td><td style="text-align:right;">�� '.number_format($cena_hits_aserf,4,".","`").'</td></tr>';
		echo '<tr><td>������, ���.</td><td style="text-align:right;">�� '.number_format($cena_mails_3,4,".","`").'</td></tr>';
		echo '<tr><td>�������, ���.</td><td style="text-align:right;">�� '.number_format($cena_task,4,".","`").'</td></tr>';
	        echo '<tr><td>�����, ���.</td><td style="text-align:right;">�� '.number_format($tests_cena_user, 4, ".", "").'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><thead><th class="top" colspan="2">���� ���.�������:</th></tr></thead>';
		echo '<tr><td width="50%">��� ������ <a href="/status.php">[���������]</a></td><td style="text-align:right;"><a href="/status.php"><b>'.$my_rang.'</b></a></td></tr>';
		echo '<tr><td>���.�������, �������</td><td style="text-align:right;">'.$my_r_c_1.'% - '.$my_r_c_2.'% - '.$my_r_c_3.'%</td></tr>';
		echo '<tr><td>���.�������, ������� <b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b</td><td style="text-align:right;">'.$my_r_youtube_1.'% - '.$my_r_youtube_2.'% - '.$my_r_youtube_3.'%</td></tr>';
		echo '<tr><td>���.�������, ������</td><td style="text-align:right;">'.$my_r_m_1.'% - '.$my_r_m_2.'% - '.$my_r_m_3.'%</td></tr>';
		echo '<tr><td>���.�������, �����</td><td style="text-align:right;">'.$my_r_test_1.'% - '.$my_r_test_2.'% - '.$my_r_test_3.'%</td></tr>';
		echo '<tr><td>���.�������, �������</td><td style="text-align:right;">'.$my_r_t_1.'% - '.$my_r_t_2.'% - '.$my_r_t_3.'%</td></tr>';
		echo '<tr><td class="stat">���.������� [���������]</td><td class="stat" style="text-align:right;">'.$my_r_pv_1.'% - '.$my_r_pv_2.'% - '.$my_r_pv_3.'%</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		$security_key = "jbmiym,oi5//bmiyt";
		$token_stat_ref1 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL1".$security_key));
		$token_stat_ref2 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL2".$security_key));
		$token_stat_ref3 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL3".$security_key));
		//$token_stat_ref4 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL4".$security_key));
		//$token_stat_ref5 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL5".$security_key));
		echo '<tr><thead><th class="top" colspan="2">��������</th></tr></thead>';
		echo '<tr><td width="50%">���������� ������ ����� ��������� <a href="referals1.php" title="������� � ������ ��������� 1-�� ������">[��������]</a></td><td style="text-align:right;">'.number_format($row["ref_back_all"],0,'.','').' %</td></tr>';
		echo '<tr><td>�������� 1-�� ������</td><td style="text-align:right;"><a href="referals1.php" title="������� � ������ ��������� 1-�� ������">'.number_format($row["referals"],0,'.','`').'</a></td></tr>';
		echo '<tr><td>�������� 2-�� ������</td><td style="text-align:right;"><a href="referals2.php" title="������� � ������ ��������� 2-�� ������">'.number_format($row["referals2"],0,'.','`').'</td></tr>';
		echo '<tr><td>�������� 3-�� ������</td><td style="text-align:right;"><a href="referals3.php" title="������� � ������ ��������� 3-�� ������">'.number_format($row["referals3"],0,'.','`').'</td></tr>';
		//echo '<tr><td>�������� 4-�� ������:</td><td style="text-align:right;"><a href="referals4.php" title="������� � ������ ��������� 4-�� ������">'.number_format($row["referals4"],0,'.','`').'</td></tr>';
		//echo '<tr><td>�������� 5-�� ������:</td><td style="text-align:right;"><a href="referals5.php" title="������� � ������ ��������� 5-�� ������">'.number_format($row["referals5"],0,'.','`').'</td></tr>';
		echo '<tr><td>��������� ��������� 1-�� ������</td><td style="text-align:right;">'.number_format($row["referalvisits"],0,'.','`').'</td></tr>';
		echo '<tr><td>��������� ��������� 2-�� ������</td><td style="text-align:right;">'.number_format($row["referal2visits"],0,'.','`').'</td></tr>';
		echo '<tr><td>��������� ��������� 3-�� ������</td><td style="text-align:right;">'.number_format($row["referal3visits"],0,'.','`').'</td></tr>';
		//echo '<tr><td>��������� ��������� 4-�� ������:</td><td style="text-align:right;">'.number_format($row["referalvisits4"],0,'.','`').'</td></tr>';
		//echo '<tr><td>��������� ��������� 5-�� ������:</td><td style="text-align:right;">'.number_format($row["referalvisits5"],0,'.','`').'</td></tr>';
		echo '<tr><td>���������� �������� 1-�� ������, ���.<span class="adv-statistics" style="margin-top:1px;" title="���������� ��������� ���������� I ������" onClick="FuncStatRef(\'StatRefCashL1\', \''.$token_stat_ref1.'\', \'���������� ��������� ���������� I ������\')"></span></td><td style="text-align:right;">'.number_format($row["refmoney"],4,'.','`').'</td></tr>';
		echo '<tr><td>���������� �������� 2-�� ������, ���.<span class="adv-statistics" style="margin-top:1px;" title="���������� ��������� ���������� II ������" onClick="FuncStatRef(\'StatRefCashL2\', \''.$token_stat_ref2.'\', \'���������� ��������� ���������� II ������\')"></span></td><td style="text-align:right;">'.number_format($row["ref2money"],4,'.','`').'</td></tr>';
		echo '<tr><td>���������� �������� 3-�� ������, ���.<span class="adv-statistics" style="margin-top:1px;" title="���������� ��������� ���������� III ������" onClick="FuncStatRef(\'StatRefCashL3\', \''.$token_stat_ref3.'\', \'���������� ��������� ���������� III ������\')"></span></td><td style="text-align:right;">'.number_format($row["ref3money"],4,'.','`').'</td></tr>';
		//echo '<tr><td>���������� �������� 4-�� ������, ���.<span class="adv-statistics" style="margin-top:1px;" title="���������� ��������� ���������� IV ������" onClick="FuncStatRef(\'StatRefCashL4\', \''.$token_stat_ref4.'\', \'���������� ��������� ���������� IV ������\')"></span></td><td style="text-align:right;">'.number_format($row["refmoney4"],4,'.','`').'</td></tr>';
		//echo '<tr><td>���������� �������� 5-�� ������, ���.<span class="adv-statistics" style="margin-top:1px;" title="���������� ��������� ���������� V ������" onClick="FuncStatRef(\'StatRefCashL5\', \''.$token_stat_ref5.'\', \'���������� ��������� ���������� V ������\')"></span></td><td style="text-align:right;">'.number_format($row["refmoney5"],4,'.','`').'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><thead><th class="top" colspan="2">�������</th></tr></thead>';
		echo '<tr><td width="50%">��������� �������</td><td style="text-align:right;">'.$row["visits_t"].'</td></tr>';
		echo '<tr><td>���������� �� ��������</td><td style="text-align:right;">'.number_format($row["money_t"],2,'.','`').'</td></tr>';
		echo '<tr><td>������, �������� �� ���</td><td style="text-align:right;">'.$kol_zd_b.'</td></tr>';
		echo '<tr><td>������ ������ �� ���������� ����� �������</td><td style="text-align:right;">'.$kol_zd_w.'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		
		echo '<tr><thead><th class="top" colspan="2">�������� �������</th></tr></thead>';
		echo '<tr><td width="50%">��������� �� �������</td><td style="text-align:right;">'.number_format($my_money_rek,2,'.','`').'</td></tr>';
		echo '<tr><td>��������� �� �����</td><td style="text-align:right;">'.number_format($my_money_in,2,'.','`').'</td></tr>';
		echo '<tr><td colspan="2">&nbsp;</td></tr>';

		echo '<tr><thead><th class="top" colspan="2">������</th></tr></thead>';
		//echo '<tr><td width="50%">��������� ������� [<a href="ref_auto.php">�����������</a>]</td><td style="text-align:right;">'.$active.'</td></tr>';
		echo '<tr><td>E-mail [<a href="/profile">��������</a>]</td><td style="text-align:right;">'.$row["email"].'</td></tr>';
		if( isset($row["wmid"]) && preg_match("|^[\d]{12}$|", trim($row["wmid"])) ) {		
			echo '<tr><td>BL (������ �������)</td><td style="text-align:right;"><img src="//bl.wmtransfer.com/img/bl/'.$row["wmid"].'?w=45&h=18&bg=0XDBE2E9" title="������ ������� [BL]" border="0" width="45" height="18" style="vertical-align:middle;"></td></tr>';
			echo '<tr><td>�������� WebMoney [<a href="'.$_SERVER['PHP_SELF'].'?passport=upgrade" title="�������� ����������">��������</a>]</td><td style="text-align:right;">'.$attestat[$row["attestat"]].'</td></tr>';
		}
	echo '</table>';
}

include('footer.php');
?>