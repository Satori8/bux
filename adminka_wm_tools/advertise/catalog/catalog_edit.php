<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������������� ������ � ��������</b></h3>';

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

mysql_query("UPDATE `tb_ads_catalog` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());

require("navigator/navigator.php");
$perpage = 25;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_catalog` WHERE `status`>'0'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace('"', "&#34;", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");

	$mensaje = str_replace("  ", " ", $mensaje);
	$mensaje = str_replace("&amp amp ", "&", $mensaje);
	$mensaje = str_replace("&amp;amp;", "&", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);
	$mensaje = str_replace("&#063;", "?", $mensaje);

	return $mensaje;
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='catalog_min' AND `howmany`='1'");
$catalog_min = number_format(mysql_result($sql,0,0), 0, ".", "");

if(isset($_GET["option"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="edit") {

		if(count($_POST)>0) {
			$wmid = (isset($_POST["wmid"]) && preg_match("|^[0-9\+]{5,30}$|", trim($_POST["wmid"]))) ? limpiarez(trim($_POST["wmid"])) : false;
			$username = (isset($_POST["username"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["username"]))) ? htmlspecialchars(stripslashes(trim($_POST["username"]))) : false;
			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 30) : false;
			$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
			$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
			$color = (isset($_POST["color"]) && preg_match("|^[0-1]{1}$|", trim($_POST["color"])) ) ? intval(limpiarez(trim($_POST["color"]))) : 0;
			$laip = getRealIP();
			$black_url = @getHost($url);

			$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

			if($title==false) {
				echo '<span class="msg-error">�� ��������� ���� ��������� ������.</span><br>';
			}elseif(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
				$row = mysql_fetch_assoc($sql_bl);
				echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>�������: '.$row["cause"].'</span>';
			}elseif($url==false | $url=="http://" | $url=="https://") {
				echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
			}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
				echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
			}elseif(is_url($url)!="true") {
				echo is_url($url);
			}elseif($plan<1) {
				echo '<span class="msg-error">����������� ���������� ���� - 1.</span><br>';
			}else{
				$save = "ok";
				mysql_query("UPDATE `tb_ads_catalog` SET `wmid`='$wmid', `username`='$username', `date_end`=`date`+'".($plan*24*60*60)."', `plan`='$plan', `url`='$url', `title`='$title', `color`='$color' WHERE `id`='$id'") or die(mysql_error());

				require_once(ROOT_DIR."/merchant/func_cache.php");
				cache_catalog();

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

			function SbmFormB() {
				var title = $.trim($("#title").val());
				var url = $.trim($("#url").val());
				var plan = $.trim($("#plan").val());

				if (title == '') {
					$("#title").focus().attr("class", "err");
					alert("�� �� ������� ��������� ������.");
					return false;
				} else if (url == '' | url == 'http://' | url == 'https://') {
					$("#url").focus().attr("class", "err");
					alert("�� �� ������� URL-����� �����.");
					return false;
				} else if (plan == '' | plan < <?php echo $catalog_min;?>) {
					$("#plan").focus().attr("class", "err12");
					alert("����������� ���������� ���� - <?php echo $catalog_min;?>.");
					return false;
				} else {
					return true;
				}
			}
			</script><?php

			$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `id`='$id' AND `status`>'0' ORDER BY `id` ASC");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);

				echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
				echo '<table class="tables" style="margin:2px auto; padding:0px;">';
				echo '<thead><tr>';
					echo '<th class="top">��������</a>';
					echo '<th class="top">��������</a>';
				echo '</thead></tr>';
				echo '<tbody>';
				echo '<tr>';
					echo '<td width="160"><b>ID</b> | <b>���� �</b></td>';
					echo '<td><input type="hidden" name="id" value="'.$row["id"].'">'.$row["id"].' | '.$row["merch_tran_id"].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>WMID</b></td>';
					echo '<td><input type="text" name="wmid" maxlength="160" value="'.$row["wmid"].'" class="ok12" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>�����</b></td>';
					echo '<td><input type="text" name="username" maxlength="160" value="'.$row["username"].'" class="ok12" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>��������� ������</b></td>';
					echo '<td align="left"><input type="text" id="title" name="title" maxlength="30" value="'.$row["title"].'" style="margin-bottom:1px;" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>URL �����</b></td>';
					echo '<td align="left"><input type="text" id="url" name="url" maxlength="300" value="'.$row["url"].'" style="margin-bottom:1px;" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>���������� ����</b></td>';
					echo '<td><input type="text" id="plan" name="plan" maxlength="7" value="'.$row["plan"].'" class="ok12" style="text-align:center;" onKeyDown="$(this).attr(\'class\', \'ok12\');"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>�������� ������</td>';
					echo '<td>';
						echo '<select name="color" id="color" style="width:125px;">';
						echo '<option value="0" '.($row["color"] == "0" ? 'selected="selected"' : false).'>���</option>';
						echo '<option value="1" '.($row["color"] == "1" ? 'selected="selected"' : false).'>��</option>';
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="2"><div align="center"><input type="submit" value="���������" class="sub-blue160" /></div></td>';
				echo '</tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</form>';
			}else{
				echo '<span id="info-msg" class="msg-error">������� �� �������.</span>';
			}
			exit();
		}
	}

	if($option=="dell") {
		$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `id`='$id'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_catalog` WHERE `id`='$id'") or die(mysql_error());

			require_once($_SERVER['DOCUMENT_ROOT']."/merchant/func_cache.php");
			cache_catalog();

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


if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}

echo '<table class="tables" style="margin:2px auto; padding:0;"><tr>';
echo '<thead><tr align="center">';
	echo '<th></th>';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th>���-�� �����</th>';
	echo '<th>����</th>';
	echo '<th colspan="2"></th>';
echo '</tr></thead>';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `status`>'0' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="1" && $row["date_end"]>time()) {
						echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="alert_nopause();"></span>';
					}elseif($row["status"]=="1" && $row["date_end"]<time()) {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="2") {

					}elseif($row["status"]=="3") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
					}
				echo '</div>';
			echo '</td>';
			echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
			echo '<td>'.($row["wmid"]!=false ? $row["wmid"]."<br>".$row["username"] : $row["username"]).'</td>';
			echo '<td>'.$system_pay[$row["method_pay"]].'</td>';
			echo '<td align="left">';
				echo '���������: <b '.($row["color"]=="1" ? 'style="color:#FF0000;"' : false).'>'.(strlen($row["title"])>50 ? limitatexto($row["title"],50)."...." : $row["title"]).'</b><br>';
				echo 'URL: '.'<a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>60 ? "<b>".limitatexto($row["url"],60)."</b>...." : "<b>".$row["url"]."</b>").'</a><br>';
				echo 'IP: <b>'.$row["ip"].'</b>';
			echo '</td>';

			echo '<td>�� '.DATE("d.m.Y H:i", $row["date"]).'<br>�� '.DATE("d.m.Y H:i", $row["date_end"]).'</td>';

			echo '<td>'.$row["plan"].'</td>';
			echo '<td>'.number_format($row["money"], 2, ".", " ").' ���.</td>';

			echo '<td width="80">';
				echo '<form method="get" action="'.$_SERVER["PHP_SELF"].'">';
					echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
					echo '<input type="hidden" name="page" value="'.$page.'">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="edit">';
					echo '<input type="submit" value="��������" class="sub-green">';
				echo '</form>';
			echo '</td>';
			echo '<td width="80">';
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
		echo '<td colspan="11"><b>������� �� �������!</b></td>';
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}
?>