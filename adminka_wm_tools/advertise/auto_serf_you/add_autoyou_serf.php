<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� ���������� � ����-�������</b> <b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b></h3>';
?>
<script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function setChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script>
<?php
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

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits_aserf' AND `howmany`='1'");
$min_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_aserf_ot' AND `howmany`='1'");
$timer_aserf_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_aserf_do' AND `howmany`='1'");
$timer_aserf_do = mysql_result($sql,0,0);


if(count($_POST)>0 && isset($_POST["id_pay"])) {
	$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

	$sql_id = mysql_query("SELECT `id`,`money` FROM `tb_ads_autoyoutube` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_array($sql_id);

		mysql_query("UPDATE `tb_ads_autoyoutube` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

		echo '<span class="msg-ok">������� ������� ���������!</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'"></noscript>';
		exit();
	}else{
		echo '<span class="msg-error">������! ������� �� �������.</span>';
		exit();
	}
}

if(count($_POST)>0) {
	$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) && intval(limpiarez(trim($_POST["plan"]))) >= $min_hits_aserf ) ? intval(limpiarez(trim($_POST["plan"]))) : $min_hits_aserf;
	$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_POST["timer"]))) && intval(limpiarez(trim($_POST["timer"]))) >= $timer_aserf_ot  && intval(limpiarez(trim($_POST["timer"]))) <= $timer_aserf_do ) ? intval(limpiarez(trim($_POST["timer"]))) : false;
	$content = (isset($_POST["content"]) && (intval($_POST["content"])==0 | intval($_POST["content"])==1)) ? intval($_POST["content"]) : "0";
	$limits = ( isset($_POST["limits"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limits"])) ) ? intval(limpiarez(trim($_POST["limits"]))) : false;

	$method_pay = 0;
	$laip = getRealIP();
	$black_url = getHost($url);
	$p = explode("youtu.be/", $_POST["url"]);
	
	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map(array($mysqli, 'real_escape_string'), $_POST["country"])) : false;
	//$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
	$geo_cod_arr = array(
		1  => 'RU', 2  => 'UA', 3  => 'BY', 4  => 'MD', 
		5  => 'KZ', 6  => 'AM', 7  => 'UZ', 8  => 'LV', 
		9  => 'DE', 10 => 'GE',	11 => 'LT', 12 => 'FR', 
		13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 
		17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 
		21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 
		25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 
		29 => 'IR', 30 => 'GR', 31 => 'TR', 32 => 'PL', 
		33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO',
	);
	$geo_name_arr = array(
		1  => '������', 	2  => '�������', 	3  => '���������', 	4  => '��������',
 		5  => '���������', 	6  => '�������', 	7  => '����������', 	8  => '������',
		9  => '��������', 	10 => '������', 	11 => '�����', 		12 => '�������', 
		13 => '�����������', 	14 => '���', 		15 => '�������', 	16 => '����������',
		17 => '������', 	18 => '�������', 	19 => '�������', 	20 => '�����',
		21 => '�����������', 	22 => '�������', 	23 => '������', 	24 => '��������',
		25 => '�������', 	26 => '������', 	27 => '������������', 	28 => '��������',
		29 => '����', 		30 => '������', 	31 => '������', 	32 => '������',
		33 => '���������', 	34 => '������', 	35 => '������', 	36 => '�������',
	);
	if(is_array($country)) {
		foreach($country as $key => $val) {
			if(array_search($val, $geo_cod_arr)) {
				$id_country = array_search($val, $geo_cod_arr);
				$country_arr[] = $val;
				$country_arr_ru[] = $geo_name_arr[$id_country];
			}
		}
	}
	$country = isset($country_arr) ? trim(strtoupper(implode(', ', $country_arr))) : false;
	$country_to = isset($country_arr_ru) ? trim(strtoupper(implode(', ', $country_arr_ru))) : false;
	if($country_to!=false) {$country_to="$country_to";}else{$country_to="���";}

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
	}elseif(count($p)<='1') {
		echo '<span class="msg-error">�� ����� ������� ������ youtub!</span>';
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
		
		$p_time = explode("?", $p[1]);  
		$img_youtube='https://img.youtube.com/vi/'.$p_time[0].'/1.jpg';
	    $ytflag=1;

		mysql_query("DELETE FROM `tb_ads_autoyoutube` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_autoyoutube` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_autoyoutube` SET `img_youtube`='$img_youtube', `merch_tran_id`='0',`method_pay`='$method_pay',`wmid`='$wmid_user',`username`='$username', `timer`='$timer', `date`='".time()."', `plan`='$plan', `totals`='$plan', `limits`='$limits_table',`limits_now`='$limits_table', `url`='$url', `description`='$description', `check_url`='1', `ip`='$laip', `money`='0' WHERE `status`='0' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_autoyoutube` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`timer`,`date`,`plan`,`totals`,`limits`,`limits_now`,`members`,`url`,`description`,`check_url`,`claims`,`ip`,`money`, `ytflag`, `img_youtube`) 
			VALUES('0','".session_id()."','0','$method_pay','$wmid_user','$username','$timer','".time()."','$plan','$plan','$limits_table','$limits_table','0','$url','$description','1','0','$laip','0','$ytflag', '$img_youtube')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_autoyoutube` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);

		echo '<table class="tables">';
			echo '<thead><tr><th class="top" width="250">��������</a><th class="top">��������</a></thead></tr>';
			echo '<tr><td><b>URL �����:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>����� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
			echo '<tr><td><b>���������� ����������:</b></td><td>'.$plan.'</td></tr>';
			echo '<tr><td><b>����������� ���������� ������� � �����:</b></td><td>'.$limits_text.'</td></tr>';
			echo '<tr><td><b>������, ���.:</b></td><td>'.$timer.'</td></tr>';
			echo '<tr><td><b>������������:</b></td><td>'.$country_to.'</td></tr>';
			echo '<tr><td colspan="2"><div align="center"><form action="" method="post"><input type="hidden" name="id_pay" value="'.$id_zakaz.'"><input type="submit" value="���������� ������" class="sub-blue160"></form></div></td></tr>';
		echo '</table><br/>';
		exit();
	}
}else{
	?>

	<script type="text/javascript" language="JavaScript"> 

	function gebi(id){
		return document.getElementById(id)
	}

	function SbmFormB() {
		arrayElem = document.forms["formzakaz"];
		var col=0;

		for (var i=0;i<arrayElem.length;i++){
			if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')) {
				alert('�� �� ������� URL-����� �����');
				arrayElem[i+0].style.background = "#FFDBDB";
				arrayElem[i+0].focus();
				return false;
			}else{
				arrayElem[i+0].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].description.value == '')|(document.forms["formzakaz"].description.value == 'http://')) {
				alert('�� �� ������� �������� ������');
				arrayElem[i+1].style.background = "#FFDBDB";
				arrayElem[i+1].focus();
				return false;
			}else{
				arrayElem[i+1].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].plan.value == '')|(document.forms["formzakaz"].plan.value < <?=$min_hits_aserf;?> )) {
				alert('���������� ���������� ������� <?=$min_hits_aserf;?>');
				arrayElem[i+2].style.background = "#FFDBDB";
				arrayElem[i+2].focus();
				return false;
			}else{
				arrayElem[i+2].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].timer.value == '')|(document.forms["formzakaz"].timer.value < <?=$timer_aserf_ot;?> )|(document.forms["formzakaz"].timer.value > <?=$timer_aserf_do;?> )) {
				alert('����� ��������� ������ ���� � �������� �� <?=$timer_aserf_ot;?> ���. �� <?=$timer_aserf_do;?> ���.');
				arrayElem[i+3].style.background = "#FFDBDB";
				arrayElem[i+3].focus();
				return false;
			}else{
				arrayElem[i+3].style.background = "#FFFFFF";
			}
		}

		document.forms["formzakaz"].submit();
		return true;
	}
	</script>


	<?php

	echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';
	echo '<thead><th colspan="2" class="top">����� ������ �������</th></thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="250"><b>URL �����:</b></td>';
			echo '<td align="left"><input type="text" name="url" maxlength="160" value="http://" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>�������� ������:</b></td>';
			echo '<td><input type="text" name="description" maxlength="80" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>���������� ����������:</b></td>';
			echo '<td><input type="text" name="plan" id="plan" maxlength="7" value="'.$min_hits_aserf.'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(������� - '.$min_hits_aserf.')</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>����� ���������:</b></td>';
			echo '<td align="left"><input type="text" name="timer" id="timer" maxlength="3" value="'.$timer_aserf_ot.'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(�� '.$timer_aserf_ot.' �� '.$timer_aserf_do.' ���.)</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>����������� ���������� ������� � �����:</b></td>';
			echo '<td><input type="text" name="limits" id="limits" maxlength="7" value="0" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(0 - ��� �����������)</td>';
		echo '</tr>';
		echo '<tr><td colspan=2><span id="adv-title2" class="adv-title-close" onclick="ShowHideBlock(2);">��������� ��������������� ����������</span></td></tr>';
	        echo '<tr><td colspan=2><div id="adv-block2" style="display:none;"><table class="tables">';
	        echo '<tbody>';
		        echo '<tr>';
			       echo '<td colspan="2" align="center" style="border-right:none;"><a id="paste" onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
			       echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
		        echo '</tr>';
		        include(DOC_ROOT."/advertise/func_geotarg.php");
				echo '</tbody>';
			echo '</table>';
			//echo '</div>';
		echo '<tr>';
			echo '<td colspan="2" align="center"><input type="submit" value="�����" class="sub-blue160" style="float:none;" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</form>';
}

?>