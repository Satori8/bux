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

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_slink' AND `howmany`='1'");
$cena_slink = mysql_result($sql,0,0);
$cena_slink = number_format($cena_slink,2,".","");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_slink_color' AND `howmany`='1'");
$cena_slink_color = mysql_result($sql,0,0);
$cena_slink_color = number_format($cena_slink_color,2,".","");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_slink_min' AND `howmany`='1'");
$cena_slink_min = mysql_result($sql,0,0);
$cena_slink_min = number_format($cena_slink_min,0,".","`");

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
	
	### ������ ��������� ###
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && isset($my_referer)) {
		if($my_referer!=false) {
			$sql_p = mysql_query("SELECT `p_sl`,`discount_partner` FROM `tb_users_partner` WHERE `username`='$my_referer' AND `discount_partner`>'0'");
			if(mysql_num_rows($sql_p)>0) {
				$row_p = mysql_fetch_array($sql_p);
				$p_sl_ref = $row_p["p_sl"];
				$discount_partner = $row_p["discount_partner"];
				$my_discount = p_floor(($p_sl_ref * $discount_partner)/100, 2);
				$my_discount = number_format($my_discount,2,".","");
			}else{
				$my_discount = 0;
			}
		}else{
			$my_discount = 0;
		}
	}else{
		$my_discount = 0;
	}
	### ������ ��������� ###

}else{
	$username = false;
	$wmid_user = false;
	$wmr_user = false;
	$money_user = false;
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
		echo '<span class="msg-error">��� ������ � ���������� ����� ���������� ��������������!</span>';
		include('footer.php');
		exit();
	}else{
		$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

		$sql_id = mysql_query("SELECT * FROM `tb_ads_slink` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
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
				mysql_query("UPDATE `tb_ads_slink` SET `status`='1', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay',  '������ �������: ����������� ������ ($plan ��.)','�������','rashod')") or die(mysql_error());

				stat_pay('statlink', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				BonusSurf($username, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $username);

				require_once("".DOC_ROOT."/merchant/func_cache.php");
				cache_stat_links();
				
				PartnerSet($username, "p_sl", $start_cena, $plan, $type_banner=false);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
				$partner_max_percent = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day'");
				if(mysql_num_rows($sql)>0) {
					$row_pd = mysql_fetch_array($sql);
					$partner_count_day = $row_pd["howmany"];
					$partner_count_per = $row_pd["price"];
				}else{
					$partner_count_day = 1;
					$partner_count_per = 1;
				}

				$add_per = floor($plan/$partner_count_day * $partner_count_per);
				$add_percent_user = floor($p_sl + $add_per);
				if($add_percent_user>$partner_max_percent) $add_percent_user = $partner_max_percent;
				mysql_query("UPDATE `tb_users` SET `p_sl`='$add_percent_user' WHERE `username`='$username'") or die(mysql_error());

				if($my_referer!=false) {
					$sql_up = mysql_query("SELECT `p_sl` FROM `tb_users` WHERE `username`='$my_referer' AND `p_sl`>'0'");
					if(mysql_num_rows($sql_up)>0) {
						$row_up = mysql_fetch_row($sql_up);
						$ref_p_sl = $row_up["0"];

						$money_add_referer = floatval($money_pay / 100 * $ref_p_sl);
						mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_add_referer' WHERE `username`='$my_referer'") or die(mysql_error());

						mysql_query("INSERT INTO `tb_partner` (`time`, `username`, `referer`, `type`, `money`, `percent`) VALUES('".time()."','$username','$my_referer','p_sl','$money_add_referer', '$ref_p_sl')") or die("Error".mysql_error());
					}
				}


				echo '<span class="msg-ok">���� ������ ������� ���������!<br>�������, ��� ����������� �������� ������ �������!</span>';
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
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
	$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
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
	}elseif($plan<$cena_slink_min) {
		echo '<span class="msg-error">����������� ����� - '.$cena_slink_min.' ����.</span><br>';
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
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[21] = "AdvCash";
        $system_pay[10] = "��������� ����";
        

		$color_to[0]="���";
		$color_to[1]="�� (".number_format(($cena_slink_color * $plan),2,".","`")." ���.)";

		$summa = round(($plan * ($cena_slink + $cena_slink_color * $color)),2);
		$summa = number_format(($summa * (100-$cab_skidka)/100),2,".",""); 

		mysql_query("DELETE FROM `tb_ads_slink` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_slink` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_slink` SET `merch_tran_id`='$merch_tran_id', `method_pay`='$method_pay', `wmid`='$wmid_user', `username`='$username', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `plan`='$plan', `url`='$url', `description`='$description', `color`='$color', `ip`='$laip', `money`='$summa' WHERE `status`='0' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_slink` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`date_end`,`plan`,`url`,`description`,`color`,`ip`,`money`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$wmid_user','$username','".time()."','".(time()+$plan*24*60*60)."','$plan','$url','$description','$color','$laip','$summa')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_slink` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);
	
		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="220"><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
			echo '<tr><td><b>URL ������:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>����� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
			echo '<tr><td><b>���������� ����:</b></td><td>'.$plan.' ('.number_format(($plan * $cena_slink),2,".","'").' ���.)</td></tr>';
			echo '<tr><td><b>��������� ������:</b></td><td>'.$color_to[$color].'</td></tr>';
			echo "$cab_text";
			echo '<tr><td><b>������ ������:</b></td><td><b>'.$system_pay[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
			@require_once("".$_SERVER['DOCUMENT_ROOT']."/curs/curs.php");
				$money_add_usd = number_format(round(($summa/$CURS_USD),2),2,".","");
				if(isset($cab_text)) echo "$cab_text";
			if(isset($my_discount) && $my_discount>0) echo '<tr><td><b>������ �� ������ ��������:</b></td><td>'.$my_discount.'%</td></tr>';
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

		$shp_item = "6";
		$inv_desc = "������ �������: ����������� ������, ����:$plan, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: stat link, plan:$plan, order:$merch_tran_id";
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
				arrayElem[i+0].style.background = "#FFDBDB";
				arrayElem[i+0].focus();
				return false;
			}else{
				arrayElem[i+0].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].description.value == '')) {
				alert('�� �� ������� �������� ������');
				arrayElem[i+1].style.background = "#FFDBDB";
				arrayElem[i+1].focus();
				return false;
			}else{
				arrayElem[i+1].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].plan.value == '') | (document.forms["formzakaz"].plan.value < <?=@$cena_slink_min;?>) ) {
				alert('���������� ���������� ���� <?=@$cena_slink_min;?>');
				arrayElem[i+2].style.background = "#FFDBDB";
				arrayElem[i+2].focus();
				return false;
			}else{
				arrayElem[i+2].style.background = "#FFFFFF";
			}
		}

		document.forms["formzakaz"].submit();
		return true;
	}

	function _obsch(){
		var plan = gebi('plan').value;
		var color = gebi('color').value;
		var cena_p = <?php echo $cena_slink;?>;
		var cena_color = <?php echo $cena_slink_color;?>;
		var price = (color * cena_color + cena_p) * plan;
		gebi('pricet').innerHTML = '<b>��������� ������:</b>';
		gebi('prices').innerHTML = '<b style="color:#228B22;">' + price.toFixed(2) + ' ���.</b>';
		gebi('prices1').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices2').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices3').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices4').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices5').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices6').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices7').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices8').innerHTML = '<b style="color:#f6f9f6;">' + (price/60).toFixed(2) + '</b>';
		//gebi('prices9').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices10').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		gebi('prices11').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
	}
	</script>

	<?php

	echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">����������� ������</span>';
		echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo '����������� �� ������������ ���������� ����. ��� ���� ���������� ��������� �� ��� ����� ���� ��������������. �� ��������� ������ ���������������� ����������� �� ���� ������. ';
		echo '��� ���� �� �������� ��������, ��� ������ ���������� ������� ����������� �� ��� ����. ';
		echo '������ ����������� �� ��������� - �������� ������ � ������� ������.';
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
			echo '<td><input type="text" name="description" maxlength="80" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>���������� ����:</b></td>';
			echo '<td><input type="text" name="plan" id="plan" maxlength="7" value="1" class="ok12" style="text-align:center;" onChange="_obsch();" onKeyUp="_obsch();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;(������� '.@$cena_slink_min.')</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� ������:</b></td>';
			echo '<td>';
				echo '<select name="color" id="color" onChange="_obsch();" onClick="_obsch();">';
					echo '<option value="0">���</option>';
					echo '<option value="1">�� (+'.number_format($cena_slink_color,2,".","'").' ���./�����)</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td id="pricet"></td>';
			echo '<td id="prices"></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '<div class="blok" style="text-align:center;">';
	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">������� ������ ������</span>';
	echo '<div id="adv-block3" style="display:block;">';

		echo '<button id="method_pay"  name="method_pay" value="10" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rs1">';
      echo '<div><div><div><span class="line-green"><span id="prices1"></span> ���.</span></div></div></div>';
	  echo '</div> </button>';
		
    if($site_pay_wm!=1) {
        echo '<div class="cash-wm1">';
    	  echo '<div class="cash-wm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="1" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-wm1">';
    echo '<div><div><div><span class="line-green"><span id="prices2"></span> (+0.8%) ���.</span></div></div></div>';
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
     echo '<div><div><div><span class="line-green"><span id="prices8"></span> ���.</span></div></div></div>';
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
}

?>

<script language="JavaScript">_obsch();</script>