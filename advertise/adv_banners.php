<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}
require(DOC_ROOT."/advertise/func_load_banners.php");

function limpiarez($mensaje) {
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
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

$type_banner_arr = array(
	"468x60" 	=> "(��� ��������, � ����� �����)", 
	"468x60_frm" 	=> "(�� ������ ��������� �������)", 
	"200x300" 	=> "(��� ��������)", 
	"100x100" 	=> "(��� ��������)",
	"728x90" 	=> "(������� ��������)"
);

foreach ($type_banner_arr as $key => $val) {
	$sql_price = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner".$key."' AND `howmany`='1'");
	$cena_banner[$key] = mysql_num_rows($sql_price)>0 ? number_format(mysql_result($sql_price,0,0), 2, ".", "") : false;
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
	$my_discount = 0;
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
		echo '<span class="msg-error">������! ��� ������ � ���������� ����� ���������� ��������������!</span>';
		include('footer.php');
		exit();
	}else{
		$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

		$sql_id = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$plan = $row["plan"];
			$money_pay = $row["money"];
			$type_banner = $row["type"];
			$merch_tran_id = $row["merch_tran_id"];
			$start_cena = $row["start_cena"];

			$size_banner_arr = explode("_", $type_banner);
			$size_banner = $size_banner_arr[0];

			if($money_rb>=$money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_banner` SET `status`='1', `date`='".time()."', `date_end`='".(time()+$plan*24*60*60)."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay',  '������ ������� [������ $size_banner], ID:#$id_pay','�������','rashod')") or die(mysql_error());

				stat_pay('banners', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				BonusSurf($username, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $username);

				require_once(DOC_ROOT."/merchant/func_cache.php");
				cache_banners();

				if($size_banner!="728x90") PartnerSet($username, "p_b", $start_cena, $plan, $size_banner);

				echo '<span class="msg-ok">��� ������ ������� ��������!<br>�������, ��� ����������� �������� ������ �������</span>';
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
	$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	$urlbanner = (isset($_POST["urlbanner"])) ? limpiarez($_POST["urlbanner"]) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) && intval(limpiarez(trim($_POST["plan"]))) >= 1 ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
	$type_banner = (isset($_POST["type_banner"]) && array_key_exists($_POST["type_banner"], $type_banner_arr) !== false) ? limpiarez($_POST["type_banner"]) : "";
	$laip = getRealIP();
	$black_url = getHost($url);
	$black_url_ban = getHost($url);

	$size_banner_arr = explode("_", $type_banner);
	$size_banner = $size_banner_arr[0];

	$wh = explode("x", $size_banner);
	$w = isset($wh["0"]) ? intval($wh["0"]) : false;
	$h = isset($wh["1"]) ? intval($wh["1"]) : false;

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
	$sql_bl_ban = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url_ban'");

	$sql_price = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner".$type_banner."' AND `howmany`='1'");
	$cena_banner = mysql_num_rows($sql_price)>0 ? number_format(mysql_result($sql_price,0,0), 2, ".", "") : false;

	if(array_key_exists($type_banner, $type_banner_arr) === false | $type_banner === false) {
		echo '<span class="msg-error">����������� ������ ��� �������!</span>';

	}elseif($cena_banner === false) {
		echo '<span class="msg-error">�� ������� ���������� ��������� �������!</span>';

	}elseif(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row = mysql_fetch_array($sql_bl);
		echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).'!<br>�������: '.$row["cause"].'</span>';

	}elseif(mysql_num_rows($sql_bl_ban)>0 && $black_url_ban!=false) {
		$row_ban = mysql_fetch_array($sql_bl_ban);
		echo '<span class="msg-error">���� <a href="http://'.$row_ban["domen"].'/" target="_blank" style="color:#0000FF">'.$row_ban["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).'!<br>�������: '.$row_ban["cause"].'</span>';

	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ����!</span>';

	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';

	}elseif($urlbanner==false | $urlbanner=="http://" | $urlbanner=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ������!</span>';

	}elseif((substr($urlbanner, 0, 7) != "http://" && substr($urlbanner, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ������!</span>';

	}elseif(is_url($url)!="true") {
		echo is_url($url);

	}elseif(is_url_img($urlbanner)!="true") {
		echo is_url_img($urlbanner);

	}elseif(is_img_size($w, $h, $urlbanner)!="true") {
		echo is_img_size($w, $h, $urlbanner);

	}elseif($plan==false && $plan<1) {
		echo '<span class="msg-error">����������� ����� - 1 ����.</span><br>';

	}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		echo '<span class="msg-error">'.SFB_YANDEX($url).'</span>';
	}elseif(@getHost($urlbanner)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($urlbanner)!=false) {
		echo '<span class="msg-error">'.SFB_YANDEX($urlbanner).'</span>';

	}elseif(img_get_save($urlbanner)!="true") {
		echo img_get_save($urlbanner);

	}else{
		$urlbanner_orig = $urlbanner;
		$urlbanner_load = img_get_save($urlbanner, 1);

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

		### ������ ��������� ###
		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && isset($my_referer)) {
			if($my_referer!=false) {
				$sql_p = mysql_query("SELECT `p_b".$size_banner."`,`discount_partner` FROM `tb_users_partner` WHERE `username`='$my_referer' AND `discount_partner`>'0'");
				if(mysql_num_rows($sql_p)>0) {
					$row_p = mysql_fetch_row($sql_p);
					$ref_p_b = $row_p["0"];
					$discount_partner = $row_p["1"];
					$my_discount = p_floor(($ref_p_b * $discount_partner)/100, 2);
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

		$summa = round(($cena_banner * $plan),2);
		$start_cena = number_format($summa,2,".","");
		$summa = number_format(($summa * (100-($cab_skidka+$my_discount))/100),2,".","");

		mysql_query("DELETE FROM `tb_ads_banner` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());

		$check_wmid = mysql_query("SELECT `id` FROM `tb_ads_banner` WHERE `status`='0' AND `type`='$type_banner' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($check_wmid)>0) {
			mysql_query("UPDATE `tb_ads_banner` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$wmid_user',`username`='$username',`type`='$type_banner',`plan`='$plan',`date`='".time()."',`date_end`='".(time()+$plan*24*60*60)."',`url`='$url',`urlbanner`='$urlbanner',`urlbanner_load`='$urlbanner_load',`ip`='$laip',`money`='$summa',`start_cena`='$start_cena' WHERE `status`='0' AND `type`='$type_banner' AND `session_ident`='".session_id()."'") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_banner` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`type`,`plan`,`date`,`date_end`,`url`,`urlbanner`,`urlbanner_load`,`ip`,`money`,`start_cena`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$wmid_user','$username','$type_banner','$plan','".time()."','".(time()+$plan*24*60*60)."','$url','$urlbanner','$urlbanner_load','$laip','$summa','$start_cena')") or die(mysql_error());
		}

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_banner` WHERE `status`='0' AND `type`='$type_banner' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);

		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="220"><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
			echo '<tr><td><b>������:</b></td><td>'.$size_banner.' '.(array_key_exists($type_banner, $type_banner_arr) ? $type_banner_arr[$type_banner] : false).'</td></tr>';
			echo '<tr><td><b>URL �����:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>URL �������:</b></td><td><a href="'.$urlbanner.'" target="_blank">'.$urlbanner.'</a></td></tr>';
			echo '<tr><td><b>���������� ����:</b></td><td>'.$plan.'</td></tr>';
			if(isset($cab_text)) echo "$cab_text";
			if(isset($my_discount) && $my_discount>0) echo '<tr><td><b>������ �� ������ ��������:</b></td><td>'.$my_discount.'%</td></tr>';
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
			//echo '<tr><td align="center" colspan="2"><a href="'.$url.'" target="_blank"><img src="'.$urlbanner.'" width="'.$w.'" height="'.$h.'" border="0" alt="" title="" /></td></tr>';
		echo '</table>';

		$shp_item = "8";
		$inv_desc = "������ �������: ������ $type_banner, ����:$plan, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: banner $type_banner, plan:$plan, order:$merch_tran_id";
		$money_add = number_format($summa,2,".","");
		require_once(DOC_ROOT."/method_pay/method_pay.php");

		include('footer.php');
		exit();
	}

	include('footer.php');
	exit();
}

?>

<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>
<script type="text/javascript" >
	$(function(){
		var btnUpload=$('#open');
		var status=$('#status');
		new AjaxUpload(btnUpload, {
			action: '/upload-file.php',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				if (! (ext && /^(bmp|jpg|png|jpeg|gif)$/.test(ext))){ 
                    // extension is not allowed 
					status.html('<span class="msg-error">�������������� ������� bmp, jpg, jpeg, png, gif</span>');
					return false;
				}
				//status.text('��������...');
				status.html('<img src="/images/otyn/load-offers1.gif" class="load-popup">');
			},
			
			onComplete: function(file, response){
				status.text('');
				if(response==="success"){
				  document.getElementById('urlbanner').value="https://<?=$_SERVER["HTTP_HOST"];?>/uploads/"+file;
				} else{
					$('<li></li>').appendTo('#files').text('���� �� ��������!' + file).addClass('msg-error'); 
				}
			}
		});
		
	});
</script> 

<script type="text/javascript" language="JavaScript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}
function SbmFormB() {
	var url = $.trim($("#url").val());
	var urlbanner = $.trim($("#urlbanner").val());
	var plan = $.trim($("#plan").val());

	if (url == '' | url == 'http://' | url == 'https://') {
		$("#url").focus().attr("class", "err");
		alert("�� �� ������� URL-����� �����");
		return false;
	} else if (urlbanner == '' | urlbanner == 'http://' | urlbanner == 'https://') {
		$("#urlbanner").focus().attr("class", "err");
		alert("�� �� ������� URL-����� �������");
		return false;
	} else if (plan == '' | plan < 1) {
		$("#plan").focus().attr("class", "err12");
		alert("����������� ���������� ���� - 1");
		return false;
	} else {
		return true;
	}
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
	var plan = $.trim($("#plan").val());
	var type_banner = $.trim($("#type_banner").val());

	if(type_banner=="468x60") {
		var price_one = <?=$cena_banner["468x60"];?>;
	}else if(type_banner=="468x60_frm") {
		var price_one = <?=$cena_banner["468x60_frm"];?>;
	}else if(type_banner=="200x300") {
		var price_one = <?=$cena_banner["200x300"];?>;
	}else if(type_banner=="100x100") {
		var price_one = <?=$cena_banner["100x100"];?>;
	}else if(type_banner=="728x90") {
		var price_one = <?=$cena_banner["728x90"];?>;
	}

	var price_all = plan * price_one;

	$("#price_one").html('<td align="left">��������� ������ � �����</td><td align="left"><span style="color:#228B22;">' + number_format(price_one, 2, '.', ' ') + ' ���.</span></td>');
	$("#price_all").html('<td align="left">��������� ������</td><td align="left"><span style="color:#FF0000;">' + number_format(price_all, 2, '.', ' ') + ' ���.</span></td>');
	$("#prices").html('<span style="color:#FF0000;">' + price_all.toFixed(2) + ' ���.</span>');
		$("#prices1").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices2").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices3").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices4").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices5").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices6").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices7").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices8").html('<b style="color:#f6f9f6;">' + (price_all/60).toFixed(2) + '</b>');
		//gebi('prices9').innerHTML = '<b style="color:#f6f9f6;">' + price.toFixed(2) + '</b>';
		$("#prices10").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
		$("#prices11").html('<b style="color:#f6f9f6;">' + price_all.toFixed(2) + '</b>');
}

</script>

<?php

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:10px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:1px;">��������� ������� - ��� ���?</span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo '������� ������������ ��������� ������� �� <b style="color:#3A5FCD">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; ��� ����������� ������������ ���������. ������������ �� �������� �������������� �� ���� �� ����� �������. ';
		echo '� ���� ��� ������� - ������ ��� ��� ������ ������������� ���������. ������� ������������ � �������� � ��������� �������. ';
		echo '<br>';
	echo '</div>';
echo '</div>';

echo '<form method="POST" action="" onSubmit="return SbmFormB(); return false;" id="newform">';
echo '<table class="tables">';
echo '<thead><tr><th width="220">��������</th><th>��������</th></tr></thead>';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>������</b></td>';
		echo '<td align="left">';
			echo '<select id="type_banner" name="type_banner" onChange="PlanChange();" onClick="PlanChange();">';
				foreach ($type_banner_arr as $key => $val) {
					$size_banner_arr = explode("_", $key);
					$size_banner = $size_banner_arr[0];
					echo '<option value="'.$key.'">'.$size_banner.' '.$val.'</option>';
				}
			echo '</select>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL �����</b> (������� http://)</td>';
		echo '<td align="left"><input type="text" id="url" name="url" maxlength="300" value="http://" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL �������</b> (�������� �� PC:)</td>';
		echo '<td align="left"><div style="position:relative;overflow:hidden;"><input type="text" id="urlbanner" name="urlbanner" maxlength="300" placeholder="http://website.ru/banner.gif" value="" class="ok" ><div class="open" style="position:absolute;top:0;right:0;width:86px;text-align:center;" id="open">�����</div><br/> <span align="center" id="status"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left">���������� ����</td>';
		echo '<td align="left"><input type="text" id="plan" name="plan" maxlength="7" value="1" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok12\');">&nbsp;&nbsp;&nbsp;(������� 1)</td>';
	echo '</tr>';
	//echo '<tr>';
		//echo '<td align="left">������ ������</td>';
		//echo '<td align="left">';
			//echo '<select name="method_pay" style="margin-bottom:1px;">';
			//require_once("".DOC_ROOT."/method_pay/method_pay_form.php");
			//echo '</select>';
		//echo '</td>';
	//echo '</tr>';
	//echo '<tr>';
			//echo '<td id=""><b>��������� ������ � �����</b></td>';
			//echo '<td id="price"></td>';
		//echo '</tr>';
	//	echo '<tr>';
			echo '<tr id="price_one"></tr>';
	echo '<tr id="price_all"></tr>';
		//echo '</tr>';
	//echo '<tr>';
		//echo '<td colspan="2" align="center"><input type="submit" value="�������� �����" class="sub-blue160" style="float:none;" /></td>';
	//echo '</tr>';
echo '</tbody>';
echo '</table>';
//echo '</div>';
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
	echo '<button id="method_pay" name="method_pay" value="11" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ik1">';
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