<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
require("".$_SERVER['DOCUMENT_ROOT']."/merchant/func_mysql.php");

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� ������ ���� ����-������</b></h3>';

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

if(count($_POST)>0) {
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),60) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),300) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$timer = 20;
	$color = 0;
	$revisit = 0;
	$type_serf = 10;
	$active = 0;
	$content = 0;
	$method_pay = 0;
	$nolimit = 0;
	$nolimitdate = 0;
	$limit_d = "0";
	$limit_h = "0";
	$laip = getRealIP();

	if($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
	}elseif($title==false) {
		echo '<span class="msg-error">�� �� ������� ��������� ������.</span><br>';
	}elseif($description==false) {
		echo '<span class="msg-error">�� �� ������� ������� �������� ������.</span><br>';
	}elseif($plan<1000) {
		echo '<span class="msg-error">������� 1000 ����������.</span><br>';
	}else{
		mysql_query("DELETE FROM `tb_ads_dlink` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		mysql_query("INSERT INTO `tb_ads_dlink` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`type_serf`,`date`,`wmid`,`username`,`geo_targ`,`content`,`active`,`revisit`,`color`,`timer`,`nolimit`,`limit_d`,`limit_h`,`limit_d_now`,`limit_h_now`,`url`,`title`,`description`,`plan`,`totals`,`ip`,`money`) 
		VALUES('1','".session_id()."','0','$method_pay','$type_serf','".time()."','$wmid_user','$username','','$content','$active','$revisit','$color','$timer','$nolimitdate','$limit_d','$limit_h','$limit_d','$limit_h','$url','$title','$description','$plan','$plan','$laip','0')") or die(mysql_error());
	
		echo '<span class="msg-ok">������ ��� ����-����-������� ���������</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
}

?><script type="text/javascript" language="JavaScript"> 
function SbmFormB() {
	var title = $.trim($("#title").val());
	var description = $.trim($("#description").val());
	var url = $.trim($("#url").val());
	var plan = $.trim($("#plan").val());

	if(url == '' | url == 'http://' | url == 'https://') {
		$("#url").focus().attr("class", "err");
		alert("�� �� ������� URL-����� �����");
		return false;
	} else if(title == '') {
		$("#title").focus().attr("class", "err");
		alert("�� �� ������� ��������� ������");
		return false;
	} else if(description == '') {
		$("#description").focus().attr("class", "err");
		alert("�� �� ������� �������� ������");
		return false;
	} else if(plan < 1000) {
		$("#plan").focus().attr("class", "err12");
		alert("����������� ���������� ���������� - 1000");
		return false;
	} else {
		return true;
	}
}
</script><?php

echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
echo '<table class="tables" style="margin:0 auto 10px auto;">';
echo '<thead><tr align="center"><th width="250">��������</th><th>��������</th></tr></thead>';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>URL �����</b></td>';
		echo '<td><input type="text" id="url" name="url" maxlength="300" value="http://" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>��������� ������</b></td>';
		echo '<td><input type="text" id="title" name="title" maxlength="60" value="" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>������� �������� ������</b></td>';
		echo '<td><input type="text" id="description" name="description" maxlength="80" value="" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>���������� ����������</b></td>';
		echo '<td><input type="text" id="plan" name="plan" maxlength="11" value="1000" class="ok12" style="text-align:center;" onKeyDown="$(this).attr(\'class\', \'ok12\');"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td colspan="2" align="center"><input type="submit" value="��������" class="sub-blue160" style="float:none;" /></td>';
	echo '</tr>';
echo '</tbody>';
echo '</table>';
echo '</form>';

if(isset($_GET["id"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="dell"){
		$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `id`='$id'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_dlink` WHERE `id`='$id'") or die(mysql_error());
			echo '<span id="info-msg" class="msg-error">������ ���� ����-���������� ������� �������!</span>';
		}

		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ ���� ����-������</b></h3>';
echo '<table class="tables" style="margin:0 auto 10px auto;">';
echo '<tr>';
	echo '<th></th>';
	echo '<th>����������</th>';
	echo '<th>����������</th>';
	echo '<th width="100">��������</th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `status`>'0' AND `type_serf`='10' ORDER BY `id` DESC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
			echo '<td align="center" width="30" style="border-right:none;">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="1") {
						echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'dlink\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'dlink\');"></span>';
					}elseif($row["status"]=="3") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" style="border-left:none;">';
				echo 'ID:&nbsp;<b>'.$row["id"].'</b><br>';
				echo '���������:&nbsp;<b>'.$row["title"].'</b><br>';
				echo '��������:&nbsp;<b>'.$row["description"].'</b><br>';
				echo 'URL �����:&nbsp;<a href="'.$row["url"].'" target="_blank"><b>'.$row["url"].'</b></a>';
			echo '</td>';

			echo '<td align="left">';
				echo '������� ������:&nbsp;<b>'.$row["members"].'</b><br>';
				echo '�������� ������:&nbsp;<b>'.$row["totals"].'</b>';
			echo '</td>';

			echo '<td>';
				echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("����������� �������� ������\nID: '.$row["id"].'")) return false;\'>';
					echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="dell">';
					echo '<input type="submit" value="�������" class="sub-red">';
				echo '</form>';
			echo '</td>';
		echo '</tr>';
	}
}
echo '</table><br>';


require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_auto_clicker`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sql_all = mysql_query("SELECT COUNT(*) FROM `tb_auto_clicker` GROUP BY `username` HAVING COUNT(`username`)>'0'");
$count_all = mysql_numrows($sql_all);


echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>����� ��������� ����� �� ������� ���� ����-������</b></h3>';
echo '����� ������: <b>'.$count_all.'</b><br>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}
echo '<table class="tables" style="margin:2px auto;">';
echo '<tr>';
	echo '<th>ID ������</th>';
	echo '<th>�����</th>';
	echo '<th>����</th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_auto_clicker` ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
			echo '<td>'.$row["ident"].'</td>';
			echo '<td>'.$row["username"].'</td>';
			echo '<td>'.DATE("d.m.Y�. � H:i:s", $row["time"]).'</td>';
		echo '</tr>';
	}
}
echo '</table>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}
?>