<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;">������ IP ����� ��� ����� � ������ ������, ����������� ��� �����������.</h3>';

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "cp1251");
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

if(count($_POST)>0) {
	if(isset($_POST["option"]) && limpiarez($_POST["option"])=="add_ban") {
		$ip_block = isset($_POST["ip_block"]) ? htmlspecialchars(trim($_POST["ip_block"])) : false;
		$cause = (isset($_POST["cause"])) ? limitatexto(limpiarez($_POST["cause"]),200) : false;

		$pattern_1 = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/";
		$pattern_2 = "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.$/";
		$pattern_3 = "/^\d{1,3}\.\d{1,3}\.$/";
		preg_match($pattern_1, $ip_block, $matches_1);
		preg_match($pattern_2, $ip_block, $matches_2);
		preg_match($pattern_3, $ip_block, $matches_3);
		$matches_1 = isset($matches_1[0]) ? $matches_1[0] : false;
		$matches_2 = isset($matches_2[0]) ? $matches_2[0] : false;
		$matches_3 = isset($matches_3[0]) ? $matches_3[0] : false;

		if($ip_block==false) {
			echo '<span id="info-msg" class="msg-error">���������� ������� IP ����� ��� �����!</span>';
		}elseif($matches_1==false && $matches_2==false && $matches_3==false) {
			echo '<span id="info-msg" class="msg-error">IP ����� ��� ����� ������� �� �����!</span>';
		}elseif($cause==false) {
			echo '<span id="info-msg" class="msg-error">���������� ������� �������!</span>';
		}else{
			if($matches_1!=false) 		{ $ip_block = $matches_1; }
			elseif($matches_2!=false) 	{ $ip_block = $matches_2; }
			elseif($matches_3!=false) 	{ $ip_block = $matches_3; }

			$sql_� = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_block'");
			if(mysql_num_rows($sql_�)>0) {
				echo '<span id="info-msg" class="msg-error">IP ����� ��� ����� '.$ip_block.' ��� ���� � ������ ������!</span>';
			}else{
				mysql_query("INSERT INTO `tb_black_ip` (`ip_block`,`who_block`,`cause`,`time`) 
				VALUES('$ip_block','$username','$cause','".time()."')") or die(mysql_error());

				echo '<span id="info-msg" class="msg-ok">IP ����� ��� ����� '.$ip_block.' ������� ������� � ������ ������!</span>';
				unset($ip_block, $cause);
			}
		}

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
			}, 100);
			HideMsg("info-msg", 2000);
		</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';

	}
}

echo '<form action="" method="POST" id="newform">';
echo '<input type="hidden" name="option" value="add_ban" />';
echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tr><th width="200">��������:</th><th>��������</th></tr>';
	echo '<tr><td><b>IP ����� ��� �����:</b></td><td><input type="text" name="ip_block" class="ok" value="'.(isset($ip_block) ? $ip_block : false).'" placeholder="������: 178.213.151.12 ��� 178.213.151. ��� 178.151." autocomplete="off" /></td></tr>';
	echo '<tr><td><b>������� ����������:</b></td><td><input type="text" name="cause" class="ok" value="'.(isset($cause) ? $cause : false).'" /> </td></tr>';
	echo '<tr align="center"><td colspan="2"><input type="submit" class="sub-blue160" value="�������������"></td></tr>';
echo '</form>';
echo '</table>';


echo '<h3 class="sp" style="margin-top:0; padding-top:40px;">������ IP ������� (��� ����� IP �������), ����������� ��� �����������.</h3>';

if(count($_POST)>0) {
	if(isset($_POST["option"]) && limpiarez($_POST["option"])=="del_ban") {
		$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiarez(trim($_POST["id"]))) : false;

		$sql_� = mysql_query("SELECT `ip_block` FROM `tb_black_ip` WHERE `id`='$id'");
		if(mysql_num_rows($sql_�)>0) {
			$row_c = mysql_fetch_assoc($sql_�);
			$ip_block = $row_c["ip_block"];

			mysql_query("DELETE FROM `tb_black_ip` WHERE `id`='$id'") or die(mysql_error());

			echo '<span id="info-msg" class="msg-ok">IP ����� ��� ����� <b>'.$ip_block.'</b> ������� ������� �� ������� ������.</span>';
			unset($ip_block, $cause);
		}

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
			}, 100);
			HideMsg("info-msg", 1500);
		</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';

	}
}

require("navigator/navigator.php");
$PER_PAGE = 30;
$sql_p = mysql_query("SELECT `id` FROM `tb_black_ip`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $PER_PAGE);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$START_POS = ($page - 1) * $PER_PAGE;
if($START_POS<0) $START_POS = 0;

$sql = mysql_query("SELECT * FROM `tb_black_ip` ORDER BY `id` DESC LIMIT $START_POS, $PER_PAGE");
$all_users = mysql_num_rows($sql);

if($count>$PER_PAGE) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $PER_PAGE, 10, '&page=', "?op=$op");
echo '<table class="tables" style="margin:1px auto;">';
echo '<thead><tr>';
	echo '<th align="center">#</th>';
	echo '<th align="center">IP ����� ��� �����</th>';
	echo '<th align="center">������� ����������</th>';
	echo '<th align="center">���� ����������</th>';
	echo '<th align="center">������������</th>';
	echo '<th align="center">��������</th>';
echo '</tr>';

echo '<tbody>';
if($all_users>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr align="center">';
			echo '<td>'.$row["id"].'</td>';
			echo '<td><b>'.$row["ip_block"].'</b></td>';
			echo '<td><b>'.$row["cause"].'</b></td>';
			echo '<td><b>'.DATE("d.m.Y H:i", $row["time"]).'</b></td>';
			echo '<td align="center">'.($row["who_block"]!=false ? "<b>".$row["who_block"]."</b>" : '<span style="color:#9C9C9C;">�������</span>').'</td>';

			echo '<td width="190" nowrap="nowrap"><div align="center">';
				echo '<form id="newform" action="" method="POST" onClick=\'if(!confirm("������� IP ����� '.$row["ip_block"].' �� ������� ������?")) return false;\'>';
					echo '<input type="hidden" name="option" value="del_ban">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="submit" value="�����" class="sub-red">';
				echo '</form></div>';
			echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr>';
		echo '<td colspan="6" align="center"><b>������ ����!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';

if($count>$PER_PAGE) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $PER_PAGE, 10, '&page=', "?op=$op");

?>