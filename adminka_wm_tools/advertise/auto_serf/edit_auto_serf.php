<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������� � �������������� ������ � ����-��������</b></h3>';

$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "Wallet One";
$system_pay[4] = "InterKassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "YandexMoney";
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[10] = "����. ����";

mysql_query("UPDATE `tb_ads_auto` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND `totals`<'1' ") or die(mysql_error());

$metode = false;
$search = false;
$operator = 0;
$WHERE_ADD = false;
$WHERE_ADD_to_get = false;

if(isset($_POST["search"]) && isset($_POST["metode"])) {
	$metode = isset($_POST["metode"]) ? mysql_real_escape_string(trim($_POST["metode"])) : false;
	$search = isset($_POST["search"]) ? mysql_real_escape_string(trim($_POST["search"])) : false;
	$operator = isset($_POST["operator"]) ? intval(mysql_real_escape_string(trim($_POST["operator"]))) : 0;

	if($metode != "" && $search != false) {
		if($operator == "0") {
			$WHERE_ADD = " AND `$metode`='$search'";
		}else{
			$WHERE_ADD = " AND `$metode` LIKE '%$search%'";
		}
		$WHERE_ADD_to_get = "&metode=$metode&operator=$operator&search=$search";
	}
}
if(isset($_GET["search"]) && isset($_GET["metode"])) {
	$metode = isset($_GET["metode"]) ? mysql_real_escape_string(trim($_GET["metode"])) : false;
	$search = isset($_GET["search"]) ? mysql_real_escape_string(trim($_GET["search"])) : false;
	$operator = isset($_GET["operator"]) ? intval(mysql_real_escape_string(trim($_GET["operator"]))) : false;

	if($metode != "" && $search != false) {
		if($operator == "0") {
			$WHERE_ADD = " AND `$metode`='$search'";
		}else{
			$WHERE_ADD = " AND `$metode` LIKE '%$search%'";
		}
		$WHERE_ADD_to_get = "&metode=$metode&operator=$operator&search=$search";
	}
}

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_auto` WHERE `status`>'0' $WHERE_ADD");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("?","&#063;",$mensaje);
	$mensaje = str_replace(">","&#062;",$mensaje);
	$mensaje = str_replace("<","&#060;",$mensaje);
	$mensaje = str_replace("'","&#039;",$mensaje);
	$mensaje = str_replace("`","&#096;",$mensaje);
	$mensaje = str_replace("$","&#036;",$mensaje);
	$mensaje = str_replace('"',"&#034;",$mensaje);
	$mensaje = str_replace("  "," ",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	$mensaje = str_replace("&&","&",$mensaje);
	$mensaje = str_replace("http://http://","http://",$mensaje);
	$mensaje = str_replace("https://https://","https://",$mensaje);
	$mensaje = str_replace("&#063;","?",$mensaje);
	return $mensaje;
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_hits_aserf' AND `howmany`='1'");
$min_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
$timer_aserf_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
$timer_aserf_do = mysql_result($sql,0,0);


if(isset($_GET["option"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="edit") {

		if(count($_POST)>0) {
			$wmid = (isset($_POST["wmid"]) && preg_match("|^[0-9\+]{5,30}$|", trim($_POST["wmid"]))) ? limpiarez(trim($_POST["wmid"])) : false;
			$username = (isset($_POST["username"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["username"]))) ? uc($_POST["username"]) : false;
			$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
			$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
			$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"]))) ? intval(limpiarez(trim($_POST["plan"]))) : false;
			$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["timer"])) && intval(limpiarez(trim($_POST["timer"]))) >= $timer_aserf_ot  && intval(limpiarez(trim($_POST["timer"]))) <= $timer_aserf_do ) ? intval(limpiarez(trim($_POST["timer"]))) : false;
			$content = (isset($_POST["content"]) && (intval($_POST["content"])==0 | intval($_POST["content"])==1)) ? intval($_POST["content"]) : "0";
			$limits = ( isset($_POST["limits"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limits"])) ) ? intval(limpiarez(trim($_POST["limits"]))) : false;
			$black_url = getHost($url);

			$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
			if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
				$row = mysql_fetch_array($sql_bl);
				echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).'!<br>�������: '.$row["cause"].'</span>';
			}elseif($url==false | $url=="http://" | $url=="https://") {
				echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
			}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
				echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
			}elseif(is_url($url)!="true") {
				echo is_url($url);
			}elseif($description==false) {
				echo '<span class="msg-error">�� ��������� ���� �������� ������.</span><br>';
			}elseif($limits!=false && $limits<$min_hits_aserf) {
				echo '<span class="msg-error">����������� ���������� ������� � ����� ������ ���� �� ����� '.$min_hits_aserf.' ���������� ���� 0 - ��� �����������.</span>';
			}elseif($limits!=false && $limits>$plan) {
				echo '<span class="msg-error">����������� ���������� ������� � ����� �� ����� ���� ������ ��� ���������� ���������� ������� ('.$plan.').</span><br></div></div>';
			}elseif($plan<$min_hits_aserf) {
				echo '<span class="msg-error">����������� ����� - '.$min_hits_aserf.' ����������.</span><br>';
			}elseif($timer==false) {
				echo '<span class="msg-error">����� ��������� ������ ���� � �������� �� '.$timer_aserf_ot.' ���. �� '.$timer_aserf_do.' ���.</span>';
			}else{
				if($limits>0) {
					$limits_table = $limits; $limits_text = $limits;
				}else{
					$limits_table = $plan; $limits_text = "��� �����������";
				}
				$save = "ok";

				$sql_totals = mysql_query("SELECT `plan`,`totals` FROM `tb_ads_auto` WHERE `id`='$id'");
				if(mysql_num_rows($sql_totals)>0) {
					$row_totals = mysql_fetch_array($sql_totals);
					$plan_table = $row_totals["plan"];
					$totals_table = $row_totals["totals"];

					if($plan==$plan_table) {
						$new_plan = $plan;
						$new_totals = $totals_table;
					}elseif($plan>$plan_table) {
						$new_plan = $plan;
						$new_totals = $totals_table + ($plan - $plan_table);
					}elseif($plan<$plan_table) {
						echo '<span class="msg-error">���������� ���������� ������ ���������.</span><br>';
						exit();
					}else{
						$new_plan = $plan;
						$new_totals = $totals_table;
					}

					mysql_query("UPDATE `tb_ads_auto` SET `wmid`='$wmid',`username`='$username',`timer`='$timer',`plan`='$new_plan',`totals`='$new_totals',`limits`='$limits_table',`limits_now`='$limits_table',`url`='$url',`description`='$description' WHERE `id`='$id'") or die(mysql_error());
				}

				echo '<span id="info-msg" class="msg-ok">��������� ������� ���������.</span>';

				echo '<script type="text/javascript">
					setTimeout(function() {
						window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
					}, 1550);
					HideMsg("info-msg", 1500);
				</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
			}
		}

		if(!isset($save)) {
			?><script type="text/javascript" language="JavaScript"> 
				function gebi(id){
					return document.getElementById(id)
				}

				function SbmFormB() {
					arrayElem = document.forms["formzakaz"];
					var col=0;

					for (var i=0;i<arrayElem.length;i++){
						if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')) {
							alert('�� �� ������� URL-����� �����');
							arrayElem[i+3].style.background = "#FFDBDB";
							arrayElem[i+3].focus();
							return false;
						}else{
							arrayElem[i+3].style.background = "#FFFFFF";
						}
						if ((document.forms["formzakaz"].description.value == '')|(document.forms["formzakaz"].description.value == 'http://')) {
							alert('�� �� ������� �������� ������');
							arrayElem[i+4].style.background = "#FFDBDB";
							arrayElem[i+4].focus();
							return false;
						}else{
							arrayElem[i+4].style.background = "#FFFFFF";
						}
						if ((document.forms["formzakaz"].plan.value == '')|(document.forms["formzakaz"].plan.value < <?=$min_hits_aserf;?> )) {
							alert('���������� ���������� ������� <?=$min_hits_aserf;?>');
							arrayElem[i+5].style.background = "#FFDBDB";
							arrayElem[i+5].focus();
							return false;
						}else{
							arrayElem[i+5].style.background = "#FFFFFF";
						}
						if ((document.forms["formzakaz"].timer.value == '')|(document.forms["formzakaz"].timer.value < <?=$timer_aserf_ot;?> )|(document.forms["formzakaz"].timer.value > <?=$timer_aserf_do;?> )) {
							alert('����� ��������� ������ ���� � �������� �� <?=$timer_aserf_ot;?> ���. �� <?=$timer_aserf_do;?> ���.');
							arrayElem[i+6].style.background = "#FFDBDB";
							arrayElem[i+6].focus();
							return false;
						}else{
							arrayElem[i+6].style.background = "#FFFFFF";
						}

						document.forms["formzakaz"].submit();
						return true;
					}
				}
			</script><?php


			$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' ORDER BY `id` ASC");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);

				echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
				echo '<table class="tables">';
				echo '<thead><tr>';
					echo '<th class="top">��������</a>';
					echo '<th class="top">��������</a>';
				echo '</thead></tr>';
				echo '<tbody>';
				echo '<tr>';
					echo '<td width="160"><b>�</b></td>';
					echo '<td><input type="hidden" name="id" value="'.$row["id"].'">'.$row["id"].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>���� �</b></td>';
					echo '<td>'.$row["merch_tran_id"].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td width="160"><b>WMID:</b></td>';
					echo '<td><input type="text" name="wmid" maxlength="160" value="'.$row["wmid"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td width="160"><b>�����:</b></td>';
					echo '<td><input type="text" name="username" maxlength="160" value="'.$row["username"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td width="220"><b>URL �����:</b></td>';
					echo '<td align="left"><input type="text" name="url" maxlength="160" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>�������� ������:</b></td>';
					echo '<td><input type="text" name="description" maxlength="60" value="'.$row["description"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>���������� ����������:</b></td>';
					echo '<td align="left"><input type="text" name="plan" id="plan" maxlength="7" value="'.$row["plan"].'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>����� ���������:</b></td>';
					echo '<td align="left"><input type="text" name="timer" id="timer" maxlength="3" value="'.$row["timer"].'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>����������� ���������� ������� � �����</b></td>';
					if($row["limits"]>=$row["plan"]) {$row["limits"]=0;}
					echo '<td><input type="text" name="limits" value="'.$row["limits"].'" class="ok12" style="text-align:right;"> (0 - ��� �����������)</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="2"><div align="center"><input type="submit" value="��������� ���������" class="sub-blue160" /></div></td>';
				echo '</tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</form>';
			}else{
				echo '<span class="msg-error">������� �� �������.</span>';
			}
			exit();
		}
	}

	if($option=="dell") {
		$sql = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id' AND `status`>'0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_auto` WHERE `id`='$id'") or die(mysql_error());

			echo '<span id="info-msg" class="msg-error">������� ������� �������!</span>';
		}

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
			}, 1550);
			HideMsg("info-msg", 1500);
		</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
	}
}

$sql_ads = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `status`>'0' $WHERE_ADD ORDER BY `id` DESC LIMIT $start_pos, $perpage") or die(mysql_error());
$all_ads = mysql_num_rows($sql_ads);

echo '<table class="adv-cab" style="margin:0; padding:0; margin-bottom:1px;"><tr>';
echo '<td align="left" width="230" valign="middle" style="border-right:solid 1px #DDDDDD;">';
	if($WHERE_ADD=="") {
		echo '�����: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_ads.'</b> �� <b>'.$count.'</b>';
	}else{
	 	echo '�������: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_ads.'</b> �� <b>'.$count.'</b>';
	}
echo '</td>';
echo '<td align="center" valign="middle" style="border-left:solid 2px #FFFFFF;">';
	echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
	echo '<table class="adv-cab" style="width:auto; margin:0; padding:0;">';
	echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="60" style="margin:0; padding:2px; border:none;"><b>����� ��:</b></td>';
		echo '<td nowrap="nowrap" width="100" align="center" style="margin:0; padding:2px; border:none;">';
			echo '<select name="metode" style="text-align:left; padding-left:3px;">';
				echo '<option value="id" '.("id" == $metode ? 'selected="selected"' : false).'>ID</option>';
				echo '<option value="merch_tran_id" '.("merch_tran_id" == $metode ? 'selected="selected"' : false).'>� �����</option>';
				echo '<option value="status" '.("status" == $metode ? 'selected="selected"' : false).'>������</option>';
				echo '<option value="wmid" '.("wmid" == $metode ? 'selected="selected"' : false).'>WMID</option>';
				echo '<option value="username" '.("username" == $metode ? 'selected="selected"' : false).'>�����</option>';
			echo '</select>';
		echo '</td>';

		echo '<td nowrap="nowrap" width="100" align="center" style="margin:0; padding:2px; border:none;">';
			echo '<select name="operator" style="text-align:center;">';
				echo '<option value="0" '.($operator == "0" ? 'selected="selected"' : false).' style="text-align:center;">=</option>';
				echo '<option value="1" '.($operator == "1" ? 'selected="selected"' : false).' style="text-align:center;">��������</option>';
			echo '</select>';
		echo '</td>';

		echo '<td nowrap="nowrap" width="135" align="center" style="margin:0; padding:2px; border:none;"><input type="text" class="ok" style="height:18px; text-align:center;" name="search" value="'.$search.'"></td>';
		echo '<td nowrap="nowrap" width="85"  align="center" style="margin:0; padding:3px 0px 2px 6px; border:none;"><input type="submit" value="�����" class="sub-green" style="float:none;"></td>';
	echo '</tr>';

	echo '</table>';
	echo '</form>';
echo '</td>';

echo '<td align="center" width="160">';
	echo '<form method="get" action="">';
		echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
		echo '<input type="submit" value="�������� �����" class="sub-blue160" style="float:none;">';
	echo '</form>';
echo '</td>';

echo '</tr>';
echo '</table>';
echo '<div align="center" style="margin-bottom:20px;">��� ������ �� <b>�������</b> �������: <b>1</b> [��������], <b>2</b> [�� �����], <b>3</b> [��������� �����]</div>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op$WHERE_ADD_to_get");}

echo '<table class="tables" style="margin:1px auto;">';
echo '<thead><tr align="center">';
echo '<tr>';
	echo '<th>������</th>';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>����������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th></th>';
echo '</tr></thead>';
echo '<tbody>';

if(mysql_num_rows($sql_ads)>0) {
	while ($row = mysql_fetch_assoc($sql_ads)) {
		echo '<tr align="center">';

		echo '<td align="center" width="30" class="noborder1">';
			echo '<div id="playpauseimg'.$row["id"].'">';
				if($row["status"]=="1") {
					echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'autoserf\');"></span>';
				}elseif($row["status"]=="2") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'autoserf\');"></span>';
				}elseif($row["status"]=="3") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
				}
			echo '</div>';
		echo '</td>';

		echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';

		echo '<td>'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td>'.$system_pay[$row["method_pay"]].'</td>';

		echo '<td align="left">';
			echo '������:&nbsp;<b>'.$row["timer"].'</b>&nbsp;���.<br>';
			echo '��������:&nbsp;<b>'.number_format($row["plan"], 0, ".", "'").'</b><br>';
			echo '����������:&nbsp;<b>'.number_format($row["members"], 0, ".", "'").'</b><br>';
			echo '��������:&nbsp;<b>'.number_format($row["totals"], 0, ".", "'").'</b>';
		echo '</td>';

		echo '<td align="left" width="320">';
			echo '��������: '.(strlen($row["description"])>40 ? "<b>".limitatexto($row["description"], 40)."</b>...." : "<b>".$row["description"]."</b>").'<br>';
			echo 'URL �����: <a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>40 ? "<b>".limitatexto($row["url"], 40)."</b>...." : "<b>".$row["url"]."</b>").'</a><br>';
			echo '���� ���������:&nbsp;'.DATE("d.m.Y H:i", $row["date"]).'</b><br>';
			echo 'IP: '.$row["ip"].'<br>';
		echo '</td>';

		echo '<td>'.$row["money"].' ���.</td>';

		echo '<td width="95">';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="edit">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("����������� �������� �������\nID �������: '.$row["id"].'")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="dell">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="8"><b>������� �� �������!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) {universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op$WHERE_ADD_to_get");}

?>