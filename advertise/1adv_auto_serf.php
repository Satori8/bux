<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}

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
	$mensaje = str_replace("&amp;quot;","&quot;",$mensaje);
	return $mensaje;
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
$cena_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_hits_aserf' AND `howmany`='1'");
$nacenka_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_hits_aserf' AND `howmany`='1'");
$min_hits_aserf = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_ot' AND `howmany`='1'");
$timer_aserf_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='timer_aserf_do' AND `howmany`='1'");
$timer_aserf_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_timer_aserf' AND `howmany`='1'");
$cena_timer_aserf = mysql_result($sql,0,0);

if(isset($_GET["editurl"])) {
	if(isset($_SESSION["checkurl"])) unset($_SESSION["checkurl"]);
	if(isset($_SESSION["checkurl_ok"])) unset($_SESSION["checkurl_ok"]);
}

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_user = mysql_query("SELECT `wmid`,`wm_purse`,`money_rb` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_row($sql_user);
		$wmid_user = $row_user["0"];
		$wmr_user = $row_user["1"];
		$money_user = $row_user["2"];
	}else{
		$username = false;
		$wmid_user = false;
		$wmr_user = false;
		$money_user = false;

		echo '<span class="msg-error">������������ �� ������.</span><br>';
		include('footer.php');
		exit();
	}

}else{
	$username = false;
	$wmid_user = false;
	$wmr_user = false;
	$money_user = false;
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
		echo '<span class="msg-error">������! ��� ������ � ���������� ����� ���������� ��������������!</span>';
		include('footer.php');
		exit();
	}else{
		$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

		$sql_id = mysql_query("SELECT * FROM `tb_ads_auto` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$plan = $row["plan"];
			$money_pay = $row["money"];

			if($money_user>=$money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($my_referer!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_auto` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay',  '������ ������� [����-�������], ID:#$id_pay','�������','rashod')") or die(mysql_error());

				if(isset($_SESSION["checkurl"])) unset($_SESSION["checkurl"]);
				if(isset($_SESSION["checkurl_ok"])) unset($_SESSION["checkurl_ok"]);

				stat_pay('autoserf', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				
				ActionRef(number_format($money_pay,2,".",""), $username);

				echo '<span class="msg-ok">���� ������ ������� ���������!<br>�������, ��� ����������� �������� ������ �������</span>';
				include('footer.php');
				exit();
			}else{
				echo '<span class="msg-error">������! �� ����� ��������� ����� ������������ ������� ��� ������ ������!</span>';
				include('footer.php');
				exit();
			}
		}else{
			echo '<span class="msg-error">������! ������ ������� � �'.$id_pay.' �� ����������, ���� ����� ��� ��� �������!</span>';
			include('footer.php');
			exit();
		}
	}
}


if(count($_POST)>0) {
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;

	if(isset($_SESSION["checkurl"]) | isset($_SESSION["checkurl_ok"])) {
		$url = (isset($_SESSION["checkurl"])) ? limpiarez($_SESSION["checkurl"]) : false;
	}else{
		$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	}

	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) && intval(limpiarez(trim($_POST["plan"]))) >= $min_hits_aserf ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["timer"])) && intval(limpiarez(trim($_POST["timer"]))) >= $timer_aserf_ot  && intval(limpiarez(trim($_POST["timer"]))) <= $timer_aserf_do ) ? intval(limpiarez(trim($_POST["timer"]))) : false;
	$limits = ( isset($_POST["limits"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limits"])) ) ? intval(limpiarez(trim($_POST["limits"]))) : false;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
	$laip = getRealIP();
	$black_url = @getHost($url);
	
	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
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
		echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>�������: '.$row["cause"].'</span>';
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
	}elseif(is_url($url)!="true") {
		echo is_url($url);
	}elseif(!isset($_SESSION["checkurl"]) | !isset($_SESSION["checkurl_ok"])) {
		$_SESSION["checkurl"] = $url; 
		echo '<script type="text/javascript"> document.location.href = "http://'.$_SERVER["HTTP_HOST"].'/check_url_as.php"; </script>';
		echo ' <noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=http://'.$_SERVER["HTTP_HOST"].'/check_url_as.php"></noscript>';
		include('footer.php');
		exit();
	}elseif($description==false) {
		echo '<span class="msg-error">�� ��������� ���� �������� ������.</span><br>';
	}elseif($plan<$min_hits_aserf) {
		echo '<span class="msg-error">����������� ����� - '.$min_hits_aserf.' �������.</span><br>';
	}elseif($limits!=false && $limits<$min_hits_aserf) {
		echo '<span class="msg-error">����������� ���������� ������� � ����� ������ ���� �� ����� '.$min_hits_aserf.' ���������� ���� 0 - ��� �����������.</span>';
	//}elseif($limits!=false && $limits>$plan) {
	//	echo '<span class="msg-error">����������� ���������� ������� � ����� �� ����� ���� ������ ��� ���������� ���������� ������� ('.$plan.').</span><br></div></div>';
	}elseif($timer==false) {
		echo '<span class="msg-error">����� ��������� ������ ���� � �������� �� '.$timer_aserf_ot.' ���. �� '.$timer_aserf_do.' ���.</span>';
	//}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		//echo '<span class="msg-error">'.SFB_YANDEX($url).'</span>';
	}else{
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
		$system_pay[10] = "��������� ����";

		if($limits>0) {
			if($limits>$plan) {
				$limits_table = $plan; $limits_text = "��� �����������";
			}else{
				$limits_table = $limits; $limits_text = $limits;
			}
		}else{
			$limits_table = $plan; $limits_text = "��� �����������";
		}

		if($timer>$timer_aserf_ot) {
			$timer_to = "$timer (".number_format(($plan * ($timer-$timer_aserf_ot) * ($cena_timer_aserf*(100+$nacenka_hits_aserf)/100)),2,".","")." ���.)";
		}else{
			$timer_to = "$timer (0.00 ���.)";
		}

		$summa = ($plan * ( ($timer - $timer_aserf_ot) * $cena_timer_aserf + $cena_hits_aserf) * (100+$nacenka_hits_aserf)/100);
		$summa = round($summa,2);
		$summa = number_format(($summa * (100-$cab_skidka)/100),2,".","");

		mysql_query("DELETE FROM `tb_ads_auto` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+1 WHERE `id`='1'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_auto` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_auto` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$wmid_user',`username`='$username', `geo_targ`='$country',`timer`='$timer', `date`='".time()."', `plan`='$plan', `totals`='$plan', `limits`='$limits_table',`limits_now`='$limits_table', `url`='$url', `description`='$description', `check_url`='1', `ip`='$laip', `money`='$summa' WHERE `status`='0' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_auto` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`geo_targ`,`timer`,`date`,`plan`,`totals`,`limits`,`limits_now`,`members`,`url`,`description`,`check_url`,`claims`,`ip`,`money`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$wmid_user','$username','$country','$timer','".time()."','$plan','$plan','$limits_table','$limits_table','0','$url','$description','1','0','$laip','$summa')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_auto` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);

		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="150"><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
			echo '<tr><td><b>URL ������:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>����� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
			echo '<tr><td><b>���������� ����������:</b></td><td>'.$plan.' ('.number_format(($plan * $cena_hits_aserf * (100+$nacenka_hits_aserf)/100),2,".","").' ���.)</td></tr>';
			echo '<tr><td><b>����������� ���������� ������� � �����:</b></td><td>'.$limits_text.'</td></tr>';
			echo '<tr><td><b>������, ���.:</b></td><td>'.$timer_to.'</td></tr>';
			echo '<tr><td><b>������������:</b></td><td>'.$country_to.'</td></tr>';
			echo "$cab_text";
			echo '<tr><td><b>������ ������:</b></td><td><b>'.$system_pay[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
			if($method_pay==8) {
				if(($summa*0.005)<0.01) {$money_add_ym = $summa + 0.01;}else{$money_add_ym = number_format(($summa*1.005),2,".","");}

				echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_ym,2,".","`").'</b> <b>���.</b></td></tr>';
			}elseif($method_pay==3) {
				$money_add_w1 = number_format(($summa * 1.05), 2, ".", "");

				echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#76B15D;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#76B15D;">'.number_format($money_add_w1,2,".","`").'</b> <b>���.</b></td></tr>';

			}else{
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
			}
		echo '</table>';

		$shp_item = "5";
		$inv_desc = "������ �������: ����-�������, ����:$plan, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: auto-serf, plan:$plan, order:$merch_tran_id";
		$money_add = number_format($summa,2,".","");
		require_once("".DOC_ROOT."/method_pay/method_pay.php");

		include('footer.php');
		exit();
	}
}else{
	?>

	<script type="text/javascript" language="JavaScript"> 

	function gebi(id){
		return document.getElementById(id)
	}

	function ShowHideBlock(id) {
		if($("#adv-title"+id).attr("class") == "adv-title-open") {
			$("#adv-title"+id).attr("class", "adv-title-close")
		} else {
			$("#adv-title"+id).attr("class", "adv-title-open")
		}
		$("#adv-block"+id).slideToggle("slow");
	}
	
	function SetChecked(type){
	    var nodes = document.getElementsByTagName("input");
	    for (var i = 0; i < nodes.length; i++) {
		    if (nodes[i].name == "country[]") {
			    if(type == "paste") nodes[i].checked = true;
			    else  nodes[i].checked = false;
		    }
	    }
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
				alert('����������� ���������� ������� <?=$min_hits_aserf;?>');
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

	function obsch(){
		var plan = gebi('plan').value;
		var timer = gebi('timer').value;

		if(timer<<?=$timer_aserf_ot;?>) { timer = <?=$timer_aserf_ot;?>}
		if(timer><?=$timer_aserf_do;?>) { timer = <?=$timer_aserf_do;?>}

		var cena_hits = <?php echo ($cena_hits_aserf*(100+$nacenka_hits_aserf)/100);?>;
		var cena_timer = <?php echo ($cena_timer_aserf*(100+$nacenka_hits_aserf)/100);?>;

		var price = ((cena_hits + (timer-<?=$timer_aserf_ot;?>) * cena_timer) * plan);

		gebi('pricet').innerHTML = '<b>��������� ������:</b>';
		gebi('price').innerHTML = '<b style="color:#228B22;">' + price.toFixed(2) + ' ���.';
	}
	</script>

	<?php

	echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:6px;">';
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">����-������� - ��� ���?</span>';
		echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
			echo '���������, ����������� � ��������� ������� �� <b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b> � ���������� ����������� ����������� ������� ��������� �� ��� ��������-������. ������ ������������� ������������ ������ � ������ ���� ������������ � ����� ���������� ��� ��������.';
		echo '</div>';
	echo '</div>';

	echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';

		if(isset($_SESSION["checkurl"]) | isset($_SESSION["checkurl_ok"])) {
			echo '<thead>';
				echo '<tr><th colspan="2" class="top">����� ������ �������</th></tr>';
			echo '</thead>';

			echo '<tr>';
				echo '<td width="180"><b>URL ����� (������):</b></td>';
				echo '<td><input type="text" name="url" maxlength="160" value="'.limpiarez($_SESSION["checkurl"]).'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';" disabled="disabled"><br><a href="'.$_SERVER["PHP_SELF"].'?ads='.$ads.'&editurl">������� ������ ������</a></td>';
			echo '</tr>';                                                                                                                                                                                         

			echo '<tr>';
				echo '<td><b>�������� ������:</b></td>';
				echo '<td><input type="text" name="description" maxlength="80" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>���������� ����������:</b></td>';
				echo '<td><input type="text" name="plan" id="plan" maxlength="7" value="" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(������� - '.$min_hits_aserf.')</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>����� ���������:</b></td>';
				echo '<td><input type="text" name="timer" id="timer" maxlength="3" value="'.$timer_aserf_ot.'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(�� '.$timer_aserf_ot.' �� '.$timer_aserf_do.' ���.)</td>';
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
            echo '</div></td></tr>';
			echo '<tr>';
				echo '<td align="left"><b>�������� ������ ������:</b></td>';
				echo '<td align="left">';
					echo '<select name="method_pay">';
						require_once("".DOC_ROOT."/method_pay/method_pay_form.php");
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td id="pricet"></td>';
				echo '<td id="price"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="2" align="center"><input type="submit" value="�������� �����" class="sub-blue160" style="float:none;" /></td>';
			echo '</tr>';
		}else{
			echo '<thead>';
				echo '<tr><th colspan="3" class="top">����� ������ �������</th></tr>';
			echo '</thead>';

			echo '<tr>';
				echo '<td width="180"><b>URL ����� (������):</b></td>';
				echo '<td><input type="text" name="url" maxlength="160" value="http://" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
				echo '<td width="160" align="right"><input type="submit" value="��������� ������" class="sub-blue160" style="float:none;" /></td>';
			echo '</tr>';
		}
	echo '</table>';
	echo '</form>';
}

?>
<script language="JavaScript">obsch();</script>