<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}
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

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='beg_stroka_cena' AND `howmany`='1'");
$beg_stroka_cena = number_format(mysql_result($sql,0,0),2,".","");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='beg_stroka_cena_color' AND `howmany`='1'");
$beg_stroka_cena_color = number_format(mysql_result($sql,0,0),2,".","");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='beg_stroka_min' AND `howmany`='1'");
$beg_stroka_min = number_format(mysql_result($sql,0,0),0,".","");

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
		$id_pay = (isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"]))) ? intval(limpiar(trim($_POST["id_pay"]))) : false;

		$sql_id = mysql_query("SELECT * FROM `tb_ads_beg_stroka` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$plan = $row["plan"];
			$money_pay = $row["money"];
			$merch_tran_id = $row["merch_tran_id"];

			if($money_user>=$money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($my_referer!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_beg_stroka` SET `status`='1', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay',  '������ �������: ��������� ����������($plan ��.)','�������','rashod')") or die(mysql_error());

				stat_pay('bstroka', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 7);
				BonusSurf($username, $money_pay);

				require_once("".DOC_ROOT."/merchant/func_cache.php");
				cache_beg_stroka();

				echo '<span class="msg-ok">���� ������ ������� ���������!<br>�������, ��� ����������� �������� ������ �������</span>';
				include('footer.php');
				exit();
			}else{
				echo '<span class="msg-error">�� ����� ��������� ����� ������������ ������� ��� ������ ������!</span>';
				include('footer.php');
				exit();
			}
		}else{
			echo '<span class="msg-error">������ ������� � �'.$id_pay.' �� ����������, ���� ����� ��� ��� �������!</span>';
			include('footer.php');
			exit();
		}
	}
}

if(count($_POST)>0) {
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),100) : false;
	$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
	$laip = getRealIP();
	$black_url = @getHost($url);

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
	}elseif($description==false) {
		echo '<span class="msg-error">�� ��������� ���� �������� ������.</span><br>';
	}elseif($plan<$beg_stroka_min) {
		echo '<span class="msg-error">����������� ����� - '.$beg_stroka_min.' (����).</span><br>';
	}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		echo '<span class="msg-error">'.SFB_YANDEX($url).'</span>';
	}else{
		$system_pay[1] = "WebMoney";
		$system_pay[2] = "RoboKassa";
		$system_pay[3] = "Wallet One";
		$system_pay[4] = "InterKassa";
		$system_pay[5] = "Payeer";
		$system_pay[6] = "Qiwi";
		$system_pay[7] = "PerfectMoney";
		$system_pay[8] = "YandexMoney";
		$system_pay[21] = "AdvCash (Advanced cash)";
        $system_pay[10] = "��������� ����";
        $system_pay[9] = "MEGAKASSA.RU";
        $system_pay[20] = "Free-Kassa";

		$color_to[0]="���";
		$color_to[1]="�� (".number_format(($plan * $color * $beg_stroka_cena_color),2,".","'")." ���.)";

		$summa = $plan * ($beg_stroka_cena + $color * $beg_stroka_cena_color);
		$summa = number_format(($summa * (100-$cab_skidka)/100),2,".","");

		mysql_query("DELETE FROM `tb_ads_beg_stroka` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_beg_stroka` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_beg_stroka` SET `merch_tran_id`='$merch_tran_id', `method_pay`='$method_pay', `wmid`='$wmid_user', `username`='$username', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `plan`='$plan', `url`='$url', `description`='$description', `color`='$color', `ip`='$laip', `money`='$summa' WHERE `status`='0' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_beg_stroka` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`date_end`,`plan`,`url`,`description`,`color`,`ip`,`money`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$wmid_user','$username','".time()."','".(time()+$plan*24*60*60)."','$plan','$url','$description','$color','$laip','$summa')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_beg_stroka` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);

		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td align="left" width="200">���� � (ID)</td><td align="left">'.number_format($merch_tran_id, 0,".", "").' ('.$id_zakaz.')</td></tr>';
			echo '<tr><td><b>�������� ������:</b></td><td>'.$description.'</td></tr>';
			echo '<tr><td><b>URL �����:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td>���������� ����</td><td>'.$plan.' ('.number_format(($plan * $beg_stroka_cena),2,".","'").' ���.)</td></tr>';
			echo '<tr><td>��������� ������</td><td>'.$color_to[$color].'</td></tr>';
			echo "$cab_text";
			echo '<tr><td>������ ������</td><td><b>'.$system_pay[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
			@require_once("".$_SERVER['DOCUMENT_ROOT']."/curs/curs.php");
				$money_add_usd = number_format(round(($summa/$CURS_USD),2),2,".","");
				
			if($method_pay==8) {
				if(($summa*0.005)<0.01) {$money_add_ym = $summa + 0.01;}else{$money_add_ym = number_format(($summa*1.005),2,".","");}

				echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_ym,2,".","`").'</b> <b>���.</b></td></tr>';
			
			}elseif($method_pay==3) {
				$money_add_w1 = number_format(($summa * 1.05), 2, ".", "");

				echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#76B15D;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#76B15D;">'.number_format($money_add_w1,2,".","`").'</b> <b>���.</b></td></tr>';
				
			}elseif($method_pay==7) {
						echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
						echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_usd,2,".","`").'</b> <b>USD</b></td></tr>';
			}else{
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
			}
		echo '</table>';

		$shp_item = "20";
		$inv_desc = "������ �������: ������ � ������� ������, ����:$plan, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: links of beg stroka, plan:$plan, order:$merch_tran_id";
		$money_add = number_format($summa,2,".","");
		require_once("".DOC_ROOT."/method_pay/method_pay.php");

		include('footer.php');
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

function number_format(number, decimals, dec_point, thousands_sep) {
	var minus = "";
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	if(number < 0){
		minus = "-";
		number = number*-1;
	}
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',

	toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return '' + (Math.round(n * k) / k).toFixed(prec);
	};
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return minus + s.join(dec);
}

function PlanChange(){
	var plan = gebi('plan').value;
	var color = gebi('color').value;
	var cena = <?=$beg_stroka_cena;?>;
	var cena_color = <?=$beg_stroka_cena_color;?>;
	var price_one = (cena + color * cena_color);
	var price_all = (plan * price_one);

	gebi('pricet_1').innerHTML = '��������� ������ � �����';
	gebi('prices_1').innerHTML = '<span style="color:#228B22;">' + number_format(price_one, 2, '.', ' ') + ' ���.</span>';
	gebi('pricet').innerHTML = '��������� ������';
	gebi('prices').innerHTML = '<span style="color:#FF0000;">' + number_format(price_all, 2, '.', ' ') + ' ���.</span>';
	gebi('prices1').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		gebi('prices2').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		gebi('prices3').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		gebi('prices4').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		//gebi('prices5').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		gebi('prices6').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		gebi('prices7').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		gebi('prices8').innerHTML = '<b style="color:#f6f9f6;">' + number_format((price_all/60), 2, '.', ' ') + '</b>';
		//gebi('prices9').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		//gebi('prices10').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
		//gebi('prices11').innerHTML = '<b style="color:#f6f9f6;">' + number_format(price_all, 2, '.', ' ') + '</b>';
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
}
</script><?php

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">������� � ������� ������ - ��� ���?</span>';
		echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
	echo '��� ��������� ������, ������� ����������� ������ ���� ������� ����� ��� ������ "��������� �������", � ������������ � ��������� �������.<br />';
echo '</div>';
echo '<br><br>';

echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
echo '<table class="tables">';
echo '<thead><tr><th width="200">��������</th><th>��������</th></tr></thead>';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>�������� ������</b></td>';
		echo '<td align="left">';
			echo '<textarea id="description" name="description" class="ok" style="width:97%; height:90px;" onKeyup="descchange(\'1\', this, \'255\');" onKeydown="descchange(\'1\', this, \'255\'); this.style.background=\'#FFFFFF\';" onClick="descchange(\'1\', this, \'255\');"></textarea>';
			echo '<span id="count1" style="display: block; float:right; font-size:11px; color:#696969; margin-top:3px; margin-right:3px;">�������� ��������: 255</span>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL �����</b></td>';
		echo '<td align="left"><input type="text" name="url" maxlength="300" value="http://" style="margin-bottom:1px;" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">���������� ����</td>';
		echo '<td align="left"><input type="text" name="plan" id="plan" maxlength="11" value="'.$beg_stroka_min.'" class="ok12" style="margin:0; text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(������� '.$beg_stroka_min.')</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">�������� ������</td>';
		echo '<td align="left">';
			echo '<select name="color" id="color" style="margin-bottom:1px;" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0">���</option>';
				echo '<option value="1">�� (+'.number_format($beg_stroka_cena_color,2,".","'").' ���./�����)</option>';
			echo '</select>';
		echo '</td>';
	echo '</tr>';
	//echo '<tr>';
		//echo '<td align="left">������ ������</td>';
		//echo '<td align="left">';
			//echo '<select name="method_pay" style="margin-bottom:1px;">';
				//require_once("".DOC_ROOT."/method_pay/method_pay_form.php");
			//echo '</select>';
		//echo '</td>';
	//echo '</tr>';
	echo '<tr>';
		echo '<td id="pricet_1" align="left"></td>';
		echo '<td id="prices_1" align="left"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td id="pricet" align="left"></td>';
		echo '<td id="prices" align="left"></td>';
	echo '</tr>';
	//echo '<tr>';
		//echo '<td colspan="2" align="center"><input type="submit" value="�������� �����" class="sub-blue160" style="float:none;" /></td>';
	//echo '</tr>';
echo '</tbody>';
echo '</table>';
echo '</div>';
		echo '<div class="blok" style="text-align:center;">';
	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">������� ������ ������</span>';
	echo '<div id="adv-block3" style="display:block;">';
	//	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		echo '<button id="method_pay"  name="method_pay" value="10" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rs1">';
      echo '<div><div><div><span class="line-green"><span id="prices1"></span> ���.</span></div></div></div>';
	  echo '</div> </button>';
	//	}else{
			
//	}
    
    if($site_pay_wm!=1) {
        echo '<div class="cash-wm1">';
    	  echo '<div class="cash-wm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="1" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-wm1">';
    echo '<div><div><div><span class="line-green"><span id="prices2"></span> ���. (+0.8%)</span></div></div></div>';
	echo '</div> </button>';
	}
	
   if($site_pay_ym!=1) {
        echo '<div class="cash-yd1">';
    	  echo '<div class="cash-yd1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="8" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-yd1">';
    echo '<div><div><div><span class="line-green"><span id="prices3"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_robo!=1) {
        echo '<div class="cash-rb1">';
    	  echo '<div class="cash-rb1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="2" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rb1">';
    echo '<div><div><div><span class="line-green"><span id="prices4"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}

	if($site_pay_mega!=1) {
    	  echo '<div class="cash-ik1">';
    	  echo '<div class="cash-ik1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="9" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ik1">';
    echo '<div><div><div><span class="line-green"><span id="prices5"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}

  if($site_pay_qw!=1) {
    	  echo '<div class="cash-qw1">';
    	  echo '<div class="cash-qw1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="6" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-qw1">';
    echo '<div><div><div><span class="line-green"><span id="prices6"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
  if($site_pay_payeer!=1) {
    	  echo '<div class="cash-pr1">';
    	  echo '<div class="cash-pr1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="5" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pr1">';
    echo '<div><div><div><span class="line-green"><span id="prices7"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
  if($site_pay_pm!=1) {
    	  echo '<div class="cash-pm1">';
    	  echo '<div class="cash-pm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
     echo '<button id="method_pay" name="method_pay" value="7" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pm1" >';
     echo '<div><div><div><span class="line-green"><span id="prices8"></span> USD</span></div></div></div>';
	 echo '</div> </button>';
	}
	
	if($site_pay_advcash!=1) {
    	  echo '<div class="cash-ah1">';
    	  echo '<div class="cash-ah1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="21" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ah1">';
    echo '<div><div><div><span class="line-green"><span id="prices11">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	 echo '</div>';
echo '</form>';

?>
<script language="JavaScript"> PlanChange();</script>