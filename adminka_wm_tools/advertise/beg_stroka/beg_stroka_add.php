<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� ������� ������</b></h3>';

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

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

	$sql_id = mysql_query("SELECT * FROM `tb_ads_beg_stroka` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_array($sql_id);

		mysql_query("UPDATE `tb_ads_beg_stroka` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

		require_once("".$_SERVER['DOCUMENT_ROOT']."/merchant/func_cache.php");
		cache_beg_stroka();

		echo '<span class="msg-ok">������ ������� ���������!</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
		exit();
	}else{
		echo '<span class="msg-error">������! ������� �� �������.</span>';
		exit();
	}

}elseif(count($_POST)>0) {
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),255) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),255) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"]))) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
	$method_pay = 0;
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
	}elseif($plan<1) {
		echo '<span class="msg-error">����������� ���������� ���� - 1.</span><br>';
	}else{
		$color_to[0]="���";
		$color_to[1]="��";

		mysql_query("DELETE FROM `tb_ads_beg_stroka` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_beg_stroka` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_beg_stroka` SET `wmid`='$wmid_user', `username`='$username', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `plan`='$plan', `url`='$url', `description`='$description', `color`='$color', `ip`='$laip', `money`='0' WHERE `status`='0' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_beg_stroka` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`date_end`,`plan`,`url`,`description`,`color`,`ip`,`money`) 
			VALUES('0','".session_id()."','0','$method_pay','$wmid_user','$username','".time()."','".(time()+$plan*24*60*60)."','$plan','$url','$description','$color','$laip','0')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_beg_stroka` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);
	
		echo '<table class="tables">';
			echo '<thead><tr><th class="top" width="200">��������</a><th class="top">��������</a></thead></tr>';
			echo '<tr><td><b>URL ������:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>�������� ������:</b></td><td>'.$description.'</td></tr>';
			echo '<tr><td><b>���������� ����:</b></td><td>'.$plan.'</td></tr>';
			echo '<tr><td><b>��������� ������:</b></td><td>'.$color_to[$color].'</td></tr>';
			echo '<tr><td colspan="2"><div align="center"><form action="" method="post"><input type="hidden" name="id_pay" value="'.$id_zakaz.'"><input type="submit" value="���������� ������" class="sub-blue160"></form></div></td></tr>';
		echo '</table><br/>';
		exit();
	}
}

?><script type="text/javascript" language="JavaScript"> 
function gebi(id){
	return document.getElementById(id)
}

function SbmFormB() {
	if (document.forms["formzakaz"].description.value == '') {
		alert('�� �� ������� ������� ������.');
		document.forms["formzakaz"].description.style.background = "#FFDBDB";
		document.forms["formzakaz"].description.focus();
		return false;
	}
	if (document.forms["formzakaz"].url.value == '' | document.forms["formzakaz"].url.value == 'http://' | document.forms["formzakaz"].url.value == 'https://') {
		alert('�� �� ������� URL-����� �����.');
		document.forms["formzakaz"].url.style.background = "#FFDBDB";
		document.forms["formzakaz"].url.focus();
		return false;
	}
	if (document.forms["formzakaz"].plan.value == '' | document.forms["formzakaz"].plan.value < 1 ) {
		alert('����������� ���������� ���� - 1');
		document.forms["formzakaz"].plan.style.background = "#FFDBDB";
		document.forms["formzakaz"].plan.focus();
		return false;
	}
	document.forms["formzakaz"].submit();
	return true;
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
}
</script><?php

echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
echo '<table class="tables">';
echo '<thead><tr><th width="200">��������</th><th>��������</th></tr></thead>';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>�������� ������</b></td>';
		echo '<td align="left">';
			echo '<textarea id="description" name="description" class="ok" style="width:99%; height:90px;" onKeyup="descchange(\'1\', this, \'255\');" onKeydown="descchange(\'1\', this, \'255\'); this.style.background=\'#FFFFFF\';" onClick="descchange(\'1\', this, \'255\');"></textarea>';
			echo '<span id="count1" style="display: block; float:right; font-size:11px; color:#696969; margin-top:3px; margin-right:3px;">�������� ��������: 255</span>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL �����</b></td>';
		echo '<td align="left"><input type="text" name="url" maxlength="300" value="http://" style="margin-bottom:1px;" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">���������� ����</td>';
		echo '<td align="left"><input type="text" name="plan" id="plan" maxlength="11" value="" class="ok12" style="margin:0; text-align:right;" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">�������� ������</td>';
		echo '<td align="left">';
			echo '<select name="color" id="color" style="margin-bottom:1px; width:125px;">';
				echo '<option value="0">���</option>';
				echo '<option value="1">��</option>';
			echo '</select>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td colspan="2"><div align="center"><input type="submit" value="���������" class="sub-blue" /></div></td>';
	echo '</tr>';
	echo '</tbody>';
echo '</table>';
echo '</form>';

?>