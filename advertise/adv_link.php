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


$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_link' and howmany='1'");
$cena_link = mysql_result($sql,0,0);

$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_color_link' and howmany='1'");
$cena_color_link = mysql_result($sql,0,0);



$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_user = mysql_query("SELECT `wmid`,`pemail`,`money_rb` FROM `tb_users` WHERE `username`='$username'");
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

		$sql_id = mysql_query("SELECT `money` FROM `tb_ads_link` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
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
				mysql_query("UPDATE `tb_ads_link` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay',  '������ �������: - ������� ������','�������','rashod')") or die(mysql_error());

				stat_pay('rekcep', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				BonusSurf($username, $money_pay);

				require_once("".DOC_ROOT."/merchant/func_cache.php");
				cache_link();

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
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),60) : false;
	$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
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
	    $system_pay[9] = "AdvCash (Advanced cash)";
        $system_pay[10] = "��������� ����";
        $system_pay[11] = "MEGAKASSA.RU";
        $system_pay[12] = "Free-Kassa";
        
        $color=1;
		$color_to[1]="�� (���������)";

		$summa = round(($cena_link),2);
		/* $summa = number_format(($summa * (100-$cab_skidka)/100),2,".",""); */

		mysql_query("DELETE FROM `tb_ads_link` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");  
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+1 WHERE `id`='1'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_link` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_link` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`date`='".time()."',`wmid`='$wmid_user',`username`='$username',`color`='$color',`url`='$url',`description`='$description',`ip`='$laip',`money`='$summa' WHERE `status`='0' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_link` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`date`,`wmid`,`username`,`color`,`url`,`description`,`ip`,`money`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','".time()."','$wmid_user','$username','$color','$url','$description','$laip','$summa')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_link` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);
	
		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="220"><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
			echo '<tr><td><b>URL ������:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>����� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
			echo '<tr><td><b>��������� ������:</b></td><td>'.$color_to[$color].'</td></tr>';
			//echo $color_to[$color];
			echo "$cab_text";
			echo '<tr><td><b>������ ������:</b></td><td><b>'.$system_pay[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
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

		$shp_item = "18";
		$inv_desc = "������ �������: ������� ������, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: Pay line, order:$merch_tran_id";
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

	function SbmFormB() {
		arrayElem = document.forms["formzakaz"];
		var col=0;

		for (var i=0;i<arrayElem.length;i++){
			if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')|(document.forms["formzakaz"].url.value == 'https://')) {
				alert('�� �� ������� URL-����� �����');
				arrayElem[i].style.background = "#FFDBDB";
				arrayElem[i].focus();
				return false;
			}else{
				arrayElem[i].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].description.value == '')) {
				alert('�� �� ������� �������� ������');
				arrayElem[i+1].style.background = "#FFDBDB";
				arrayElem[i+1].focus();
				return false;
			}else{
				arrayElem[i+1].style.background = "#FFFFFF";
			}
		}

		document.forms["formzakaz"].submit();
		return true;
	}

	function obsch(){
		var color = gebi('color').value;
		var cena = <?php echo $cena_link;?>;
	        var cena_color = <?php echo $cena_color_link;?>;
		var price = color * cena_color + cena;
		gebi('pricet').innerHTML = '<b>��������� ������:</b>';
		gebi('price').innerHTML = '<b style="color:#228B22;">' + price.toFixed(2) + ' ���.';
	}
	</script>

	<?php

	echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">������� ������ - ��� ���?</span>';
		echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo '��� ������, ����� ������������ ������ �� ���� ���������, ���� �� �� ������ ������ ������������! ������ ����������� ������������� � ���������� ������� ������! ������ ������ ����� ���������� ���, ��� ������ ����� ���� ��������� �� ��������� �����, � �������� � �� ��������� ����! ������ ����� ������� ��� ��������������, ������� ������� ����� �������� � ����������� ������ �������, � ����� ������� �������� �� ����� �����. �� ��������� �����, ��� ����� ��������� ������ �� ������ �� ���� ����, �� � ���� ����������� ������. ����������� �� ���������� ����������� ������ ���!';
	echo '</div>';
	echo '<br><br>';

	echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';
	echo '<thead><th colspan="2" class="top">����� ������ �������</th></thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="150"><b>URL ����� (������):</b></td>';
			echo '<td><input type="text" name="url" maxlength="160" value="http://" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>�������� ������:</b></td>';
			echo '<td><input type="text" name="description" maxlength="60" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������� ������:</b></td>';
			echo '<td align="left" style="color:#ff0000; font-size:14px;">'.$cena_link.' ���.</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
		echo '</div>';
		echo '<div class="blok" style="text-align:center;">';
	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">������� ������ ������</span>';
	echo '<div id="adv-block3" style="display:block;">';

		echo '<button id="method_pay"  name="method_pay" value="10" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rs1">';
      echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	  echo '</div> </button>';
	
    if($site_pay_wm!=1) {
        echo '<div class="cash-wm1">';
    	  echo '<div class="cash-wm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="1" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-wm1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' (+0.8%) ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_ym!=1) {
        echo '<div class="cash-yd1">';
    	  echo '<div class="cash-yd1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="8" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-yd1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_robo!=1) {
        echo '<div class="cash-rb1">';
    	  echo '<div class="cash-rb1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="2" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rb1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_wo!=1) {
    	  echo '<div class="cash-wo1">';
    	  echo '<div class="cash-wo1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button name="method_pay" value="3" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-wo1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_mega!=1) {
    	  echo '<div class="cash-ik1">';
    	  echo '<div class="cash-ik1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="11" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ik1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
  
  if($site_pay_qw!=1) {
    	  echo '<div class="cash-qw1">';
    	  echo '<div class="cash-qw1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="6" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-qw1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_payeer!=1) {
    	  echo '<div class="cash-pr1">';
    	  echo '<div class="cash-pr1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="5" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pr1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
  
  if($site_pay_pm!=1) {
    	  echo '<div class="cash-pm1">';
    	  echo '<div class="cash-pm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
     echo '<button id="method_pay" name="method_pay" value="7" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pm1" >';
     echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">0.09 USD.</span></span></div></div></div>';
	 echo '</div> </button>';
	}
	
	if($site_pay_advcash!=1) {
    	  echo '<div class="cash-ah1">';
    	  echo '<div class="cash-ah1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="9" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ah1">';
    echo '<div><div><div><span class="line-green"><span style="color:#f6f9f6; font-size:14px;">'.$cena_link.' ���.</span></span></div></div></div>';
	echo '</div> </button>';
	}
	 echo '</div>';
	echo '</form>';
}

?>

<script language="JavaScript">obsch();</script>