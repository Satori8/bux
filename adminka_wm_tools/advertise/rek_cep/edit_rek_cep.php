<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������������� ������ � ��������� �������</b></h3>';

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
	$mensaje = str_replace("&amp;amp;","&",$mensaje);
	$mensaje = str_replace("&&","&",$mensaje);
	$mensaje = str_replace("http://http://","http://",$mensaje);
	$mensaje = str_replace("https://https://","https://",$mensaje);
	$mensaje = str_replace("&#063;","?",$mensaje);
	return $mensaje;
}

if(isset($_GET["option"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="edit") {

		if(count($_POST)>0) {
			$wmid = (isset($_POST["wmid"]) && preg_match("|^[0-9\+]{5,30}$|", trim($_POST["wmid"]))) ? limpiarez(trim($_POST["wmid"])) : false;
			$username = (isset($_POST["username"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["username"]))) ? uc($_POST["username"]) : false;
			$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
			$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
			$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
			$laip = getRealIP();
			$black_url = @getHost($url);

			$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
			if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
				$row = mysql_fetch_array($sql_bl);
				echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).'!<br>�������: '.$row["cause"].'</span>';
			}elseif($description==false) {
				echo '<span class="msg-error">�� ��������� ���� �������� ������.</span><br>';
			}elseif($url==false | $url=="http://" | $url=="https://") {
				echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
			}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
				echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
			}elseif(is_url($url)!="true") {
				echo is_url($url);
			}else{
				$save = "ok";
				mysql_query("UPDATE `tb_ads_rc` SET `wmid`='$wmid', `username`='$username', `url`='$url', `description`='$description', `color`='$color' WHERE `id`='$id'") or die(mysql_error());

				require_once("".$_SERVER['DOCUMENT_ROOT']."/merchant/func_cache.php");
				cache_rek_cep();

				echo '<span class="msg-ok">��������� ������� ���������.</span>';
				echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'")\', 1000); </script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'"></noscript>';
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
				}
				document.forms["formzakaz"].submit();
				return true;
			}
			</script><?php

			$sql = mysql_query("SELECT * FROM `tb_ads_rc` WHERE `id`='$id' AND `status`='1' ORDER BY `id` ASC");
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
					echo '<td><b>WMID:</b></td>';
					echo '<td><input type="text" name="wmid" maxlength="160" value="'.$row["wmid"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>�����:</b></td>';
					echo '<td><input type="text" name="username" maxlength="160" value="'.$row["username"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>URL ����� (������):</b></td>';
					echo '<td><input type="text" name="url" maxlength="160" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>�������� ������:</b></td>';
					echo '<td><input type="text" name="description" maxlength="60" value="'.$row["description"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>�������� ������:</b></td>';
					echo '<td>';
						echo '<select name="color" id="color">';
						echo '<option value="0" '.("".$row["color"]."" == "0" ? 'selected="selected"' : false).'>���</option>';
						echo '<option value="1" '.("".$row["color"]."" == "1" ? 'selected="selected"' : false).'>��</option>';
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
				echo '<span class="msg-error">������� �� �������.</span>';
			}
		}
	}

	if($option=="dell") {
		mysql_query("DELETE FROM `tb_ads_rc` WHERE `id`='$id'") or die(mysql_error());

		require_once("".$_SERVER['DOCUMENT_ROOT']."/merchant/func_cache.php");
		cache_rek_cep();

		echo '<span class="msg-error">������� �������.</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'"></noscript>';
	}
}


echo '<table class="tables" style="margin:1px auto;">';
echo '<thead><tr align="center">';
echo '<tr>';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>���� ������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th></th>';
echo '</tr></thead>';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_rc` WHERE `status`='1' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
		echo '<td>'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td>'.$system_pay[$row["method_pay"]].'</td>';

		echo '<td>'.DATE("d.m.Y H:i", $row["date"]).'</td>';

		echo '<td align="left">';
			echo '��������: <b '.($row["color"]=="1" ? 'style="color:#FF0000;"' : false).'>'.(strlen($row["description"])>50 ? limitatexto($row["description"],50)."...." : $row["description"]).'</b><br>';
			echo 'URL: '.'<a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>60 ? limitatexto($row["url"],60)."...." : $row["url"]).'</a><br>';
			echo 'IP: <b>'.$row["ip"].'</b>';
		echo '</td>';

		echo '<td>'.$row["money"].'&nbsp;���.</td>';
		echo '<td>';
			echo '<form method="get" action="'.$_SERVER["PHP_SELF"].'">';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="edit">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("����������� �������� �������\nID �������: '.$row["id"].'")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="dell">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="7"><b>������� �� �������!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';

?>