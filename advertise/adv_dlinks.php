<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}
require(DOC_ROOT."/advertise/func_load_banners.php");

?><script type="text/javascript">
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
</script><?php

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("?","&#063;",$mensaje);
	$mensaje = str_replace(">","&#062;",$mensaje);
	$mensaje = str_replace("<","&#060;",$mensaje);
	$mensaje = str_replace('\'',"&#091;",$mensaje);
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

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
$dlink_cena_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
$dlink_cena_hits_bs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_nacenka' AND `howmany`='1'");
$dlink_nacenka = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits' AND `howmany`='1'");
$dlink_min_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_min_hits_vip' AND `howmany`='1'");
$dlink_min_hits_vip = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_color' AND `howmany`='1'");
$dlink_cena_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_active' AND `howmany`='1'");
$dlink_cena_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_ot' AND `howmany`='1'");
$dlink_timer_ot = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_do' AND `howmany`='1'");
$dlink_timer_do = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_timer' AND `howmany`='1'");
$dlink_cena_timer = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='1'");
$dlink_cena_revisit_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='2'");
$dlink_cena_revisit_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='0'");
$dlink_cena_nolimit = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='1'");
$dlink_cena_nolimit_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='2'");
$dlink_cena_nolimit_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='3'");
$dlink_cena_nolimit_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='4'");
$dlink_cena_nolimit_4 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='1'");
$dlink_cena_unic_ip_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='2'");
$dlink_cena_unic_ip_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_uplist' AND `howmany`='1'");
$dlink_cena_uplist = mysql_result($sql,0,0);


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
		echo '<span class="msg-error">��� ������ � ���������� ����� ���������� ��������������!</span>';
		include('footer.php');
		exit();
	}else{
		$id_pay = (isset($_POST["id_pay"])) ? intval(($_POST["id_pay"])) : false;

		$sql_id = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$money_pay = $row["money"];
			$type_serf = $row["type_serf"];
			$merch_tran_id = $row["merch_tran_id"];
			$timer = $row["timer"];
			$geo_targ = $row["geo_targ"];
			$new_users = $row["new_users"];
			$no_ref = $row["no_ref"];
			$to_ref = $row["to_ref"];
			$sex_adv = $row["sex_adv"];
			$revisit = $row["revisit"];
			$unic_ip = $row["unic_ip"];

            if($type_serf==3) {
				$vip_serf_tab = ", `vip_serf`=`vip_serf`+'200'";
			}elseif($type_serf==4) {
				$vip_serf_tab = ", `ban_serf`=`ban_serf`+'200'";
			}else{
				$vip_serf_tab = false;
			}
			
			if($money_user>=$money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
				$reit_ref_rek = mysql_result($sql,0,0);
							    
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_vip'");
				$reit_vip = mysql_result($sql,0,0);
                

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;
				if($type_serf==3 || $type_serf==4) {
				$reit_add_3 = $reit_vip;
                }else{
				$reit_add_3=0;
				}
				
				if($my_referer!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer'") or die(mysql_error());}
               
          
			   mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1'+'$reit_add_3', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' $vip_serf_tab WHERE `username`='$username'") or die(mysql_error());
				//mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1'+'$reit_add_3', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' $vip_serf_tab WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_dlink` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) 
				VALUES('$username', '".DATE("d.m.Y H:i")."', '$money_pay', '������ ������� [�������], ID:#$id_pay', '��������', 'reklama')") or die(mysql_error());

				stat_pay('dlink', $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);

				BonusSurf($username, $money_pay);
				
				//konkurs_serf_new($wmid_user, $username, $money_pay);
				if($geo_targ == false && $new_users == 0 && $no_ref == 0 && $to_ref == 0 && $sex_adv == 0 && $revisit== 0 && $unic_ip == 0) {
				$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='serf' AND `item`='timer_ot'");
				$konk_serf_timer = mysql_result($sql,0,0);
				
				if($timer>=$konk_serf_timer){
				konkurs_serf_new($wmid_user, $username, $money_pay);
				}
				}
				konkurs_ads_new($wmid_user, $username, $money_pay);
				
				invest_stat($money_pay, 1);
				ads_date();

				echo '<span class="msg-ok">������� ������� ���������!<br>�������, ��� ����������� �������� ������ �������!</span>';
				include('footer.php');
				exit();
			}else{
				echo '<span class="msg-error">�� ����� ��������� ����� ������������ ������� ��� ������ �������!</span>';
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
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),60) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),300) : false;
	$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) ) ? intval(limpiarez(trim($_POST["plan"]))) : false;
	$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,3}$|", trim($_POST["timer"])) && intval(limpiarez(trim($_POST["timer"]))) >= $dlink_timer_ot  && intval(limpiarez(trim($_POST["timer"]))) <= $dlink_timer_do ) ? intval(limpiarez(trim($_POST["timer"]))) : false;
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
	$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])==0 | intval($_POST["revisit"])==1 | intval($_POST["revisit"])==2)) ? intval(trim($_POST["revisit"])) : "0";
	$type_serf = (isset($_POST["type_serf"]) && (intval($_POST["type_serf"])==1 | intval($_POST["type_serf"])==2 | intval($_POST["type_serf"])==3 | intval($_POST["type_serf"])==4)) ? intval(trim($_POST["type_serf"])) : "1";

	if($type_serf==2 | $type_serf==4) {
		$color = 0;
		$title = false;
		$description = (isset($_POST["url_banner"])) ? limitatexto(limpiarez($_POST["url_banner"]),300) : false;
		$black_url = @getHost($url);
		$black_url_banner = @getHost($description);
	}else{
		$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
		$black_url = @getHost($url);
		$black_url_banner = false;
	}

	$active = (isset($_POST["active"]) && (intval($_POST["active"])==0 | intval($_POST["active"])==1)) ? intval(trim($_POST["active"])) : "0";
	$content = (isset($_POST["content"]) && (intval($_POST["content"])==0 | intval($_POST["content"])==1)) ? intval($_POST["content"]) : "0";
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
	$nolimit = (isset($_POST["nolimit"])) ? intval(trim($_POST["nolimit"])) : "0";
	$limit_d = ( isset($_POST["limit_d"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_d"])) ) ? intval(limpiarez(trim($_POST["limit_d"]))) : 0;
	$limit_h = ( isset($_POST["limit_h"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_h"])) ) ? intval(limpiarez(trim($_POST["limit_h"]))) : 0;
	$laip = getRealIP();

	$up_list = (isset($_POST["up_list"]) && (intval($_POST["up_list"])==0 | intval($_POST["up_list"])==1)) ? intval(trim($_POST["up_list"])) : "0";
	$new_users = (isset($_POST["new_users"]) && (intval($_POST["new_users"])==0 | intval($_POST["new_users"])==1)) ? intval($_POST["new_users"]) : "0";
	$unic_ip = (isset($_POST["unic_ip"]) && (intval($_POST["unic_ip"])==0 | intval($_POST["unic_ip"])==1 | intval($_POST["unic_ip"])==2)) ? intval($_POST["unic_ip"]) : "0";
	$no_ref = (isset($_POST["no_ref"]) && (intval($_POST["no_ref"])==0 | intval($_POST["no_ref"])==1)) ? intval($_POST["no_ref"]) : "0";
	$sex_adv = (isset($_POST["sex_adv"]) && (intval($_POST["sex_adv"])==0 | intval($_POST["sex_adv"])==1 | intval($_POST["sex_adv"])==2)) ? intval($_POST["sex_adv"]) : "0";
	$to_ref = (isset($_POST["to_ref"]) && (intval($_POST["to_ref"])==0 | intval($_POST["to_ref"])==1 | intval($_POST["to_ref"])==2)) ? intval($_POST["to_ref"]) : "0";

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
		1  => '������', 	2  => '�������', 	3  => '����������', 	4  => '��������',
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

	if($nolimit>0) {
		$plan = 0;
		$timer = 20;
		$up_list = 1;
		$color = 1;
		$active = 0;
		$revisit = 2;
		$unic_ip = 1;
	}

	if($nolimit==1) {
		$nolimitdate = time() + 7*24*60*60;
	}elseif($nolimit==2) {
		$nolimitdate = time() + 14*24*60*60;
	}elseif($nolimit==3) {
		$nolimitdate = time() + 21*24*60*60;
	}elseif($nolimit==4) {
		$nolimitdate = time() + 30*24*60*60;
	}else{
		$nolimit = 0;
		$nolimitdate = 0;
	}

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
	$sql_bl_banner = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url_banner'");

	if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row = mysql_fetch_array($sql_bl);
		echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>�������: '.$row["cause"].'</span>';
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
    }elseif(is_url($url)!="true") {
		echo is_url($url);
	}elseif(($type_serf==1 | $type_serf==3) && $title==false) {
		echo '<span class="msg-error">�� �� ������� ��������� ������.</span><br>';
	}elseif(($type_serf==1 | $type_serf==3) && $description==false) {
		echo '<span class="msg-error">�� �� ������� ������� �������� ������.</span><br>';

	}elseif(($type_serf==2 | $type_serf==4) && mysql_num_rows($sql_bl_banner)>0 && $black_url_banner!=false) {
		$row = mysql_fetch_array($sql_bl);
		echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>�������: '.$row["cause"].'</span>';
	}elseif(($type_serf==2 | $type_serf==4) && ($description==false | $description=="http://" | $description=="https://")) {
		echo '<span class="msg-error">�� ������� ������ �� ������!</span>';
	}elseif(($type_serf==2 | $type_serf==4) && ((substr($description, 0, 7) != "http://" && substr($description, 0, 8) != "https://"))) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ������!</span>';
	}elseif(($type_serf==2 | $type_serf==4) && is_url($url)!="true") {
		echo is_url($url);
	}elseif(($type_serf==2 | $type_serf==4) && is_url_img($description)!="true") {
		echo is_url_img($description);
	}elseif(($type_serf==2 | $type_serf==4) && is_img_size("468", "60", $description)!="true") {
		echo is_img_size("468", "60", $description);

	}elseif($nolimit==0 && ($type_serf==1 | $type_serf==2) && $plan<$dlink_min_hits) {
		echo '<span class="msg-error">����������� ����� - '.$dlink_min_hits.' �������.</span><br>';
	}elseif($nolimit==0 && ($type_serf==3 | $type_serf==4) && $plan<$dlink_min_hits_vip) {
		echo '<span class="msg-error">����������� ����� - '.$dlink_min_hits_vip.' �������.</span><br>';
	}elseif($limit_d!=false && $limit_d<$dlink_min_hits) {
		echo '<span class="msg-error">����������� ���������� ������� � ����� ������ ���� �� ����� '.$dlink_min_hits.' ���������� ���� 0 - ��� �����������.</span>';
	}elseif($limit_h!=false && $limit_h<$dlink_min_hits) {
		echo '<span class="msg-error">����������� ���������� ������� � ��� ������ ���� �� ����� '.$dlink_min_hits.' ���������� ���� 0 - ��� �����������.</span>';
	}elseif($timer==false) {
		echo '<span class="msg-error">����� ��������� ������ ���� � �������� �� '.$dlink_timer_ot.' ���. �� '.$dlink_timer_do.' ���.</span>';
	}elseif(($type_serf==3 | $type_serf==4) && $timer<20) {
		echo '<span class="msg-error">��� VIP �������� ����� ��������� ������ ���� � �������� �� 20 ���. �� '.$dlink_timer_do.' ���.</span>';
		
	}elseif(($type_serf==2 | $type_serf==4) && img_get_save($description)!="true") {
		echo img_get_save($description);

	}else{
		if(($type_serf==2 | $type_serf==4)) {
			$urlbanner_load = img_get_save($description, 1);
		}else{
			$urlbanner_load = false;
		}
	
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
		$system_pay[21] = "AdvCash (Advanced cash)";
		$system_pay[10] = "��������� ����";
		
		if($limit_d>0) {$limit_d_to="$limit_d ������� � �����";}else{$limit_d_to="��� �����������";}
		if($limit_h>0) {$limit_h_to="$limit_h ������� � ���";}else{$limit_h_to="��� �����������";}

		$color_to[0]="��� (0.00 ���./��������)";
		$color_to[1]="�� (".number_format(($plan * $color * $dlink_cena_color),4,".","")." ���.)";

		$up_list_to[0]="���";
		$up_list_to[1]="�� (".number_format(($up_list * $dlink_cena_uplist),4,".","")." ���.)";

		$content_to[0]="���";
		$content_to[1]="��";

		$new_users_to[0]="��� ������������ �������";
		$new_users_to[1]="�� (�� 7 ���� � ������� �����������)";

		$unic_ip_to[0]="���";
		$unic_ip_to[1]="�� (100% ����������) &mdash; (".number_format(($plan * $dlink_cena_unic_ip_1),4,".","")." ���.)";
		$unic_ip_to[2]="��������� �� ����� �� 2 ����� (255.255.X.X) &mdash; (".number_format(($plan * $dlink_cena_unic_ip_2),4,".","")." ���.)";

		$no_ref_to[0]="���";
		$no_ref_to[1]="��";

		$sex_adv_to[0]="��� ������������ �������";
		$sex_adv_to[1]="������ �������";
		$sex_adv_to[2]="������ �������";

		$to_ref_to[0]="��� ������������ �������";
		$to_ref_to[1]="��������� 1-�� ������";
		$to_ref_to[2]="��������� ���� �������";

		$active_to[0]="��� (0.00 ���./��������)";
		$active_to[1]="�� (".number_format(($plan * $active * $dlink_cena_active * (100+$dlink_nacenka)/100),4,".","")." ���./��������)";

		if($timer>$dlink_timer_ot) {
			$timer_to = "$timer (".number_format(($plan * ($timer-$dlink_timer_ot) * $dlink_cena_timer * (100+$dlink_nacenka)/100),4,".","")." ���.)";
		}else{
			$timer_to = "$timer (0.00 ���.)";
		}

		if($revisit==0) {$dlink_cena_revisit = 0; $revisit_to = "������ 24 ���� (0.00 ���./��������)";}
		elseif($revisit==1) {$dlink_cena_revisit = $dlink_cena_revisit_1; $revisit_to = "������ 48 ����� (".number_format(($dlink_cena_revisit_1*$plan),4,".","")." ���./��������)";}
		elseif($revisit==2) {$dlink_cena_revisit = $dlink_cena_revisit_2; $revisit_to = "1 ��� (".number_format(($dlink_cena_revisit_2*$plan),4,".","")." ���./��������)";}

		if($unic_ip==0) { $dlink_cena_inic_ip = 0; }
		else if($unic_ip==1) { $dlink_cena_inic_ip = $dlink_cena_unic_ip_1; }
		else if($unic_ip==2) { $dlink_cena_inic_ip = $dlink_cena_unic_ip_2; }

		if($nolimit==1) {
			$summa = number_format($dlink_cena_nolimit_1,2,".","");
		}elseif($nolimit==2) {
			$summa = number_format($dlink_cena_nolimit_2,2,".","");
		}elseif($nolimit==3) {
			$summa = number_format($dlink_cena_nolimit_3,2,".","");
		}elseif($nolimit==4) {
			$summa = number_format($dlink_cena_nolimit_4,2,".","");
		}else{
			if($type_serf==2 | $type_serf==4) $dlink_cena_hits = $dlink_cena_hits_bs;

			$precio = $plan * ($dlink_cena_hits + ($dlink_cena_timer * ($timer-$dlink_timer_ot)) + ($active * $dlink_cena_active)) * ($dlink_nacenka + 100)/100 + $plan * ( ($color * $dlink_cena_color) + $dlink_cena_revisit + $dlink_cena_inic_ip) + ($up_list * $dlink_cena_uplist);
			$summa = number_format(($precio * (100-$cab_skidka)/100),2,".","");
		}

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());
		mysql_query("DELETE FROM `tb_ads_dlink` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_dlink` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`type_serf`='$type_serf',`date`='".time()."',`up_list`='".($up_list * time())."',`wmid`='$wmid_user',`username`='$username',`geo_targ`='$country',`content`='$content',`active`='$active',`revisit`='$revisit',`color`='$color',`timer`='$timer',`nolimit`='$nolimitdate',`limit_d`='$limit_d',`limit_h`='$limit_h',`limit_d_now`='$limit_d',`limit_h_now`='$limit_h',`new_users`='$new_users',`unic_ip`='$unic_ip',`no_ref`='$no_ref',`sex_adv`='$sex_adv',`to_ref`='$to_ref',`url`='$url',`title`='$title',`description`='$description',`urlbanner_load`='$urlbanner_load',`plan`='$plan',`totals`='$plan',`ip`='$laip',`money`='$summa' WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_dlink` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`type_serf`,`date`,`up_list`,`wmid`,`username`,`geo_targ`,`content`,`active`,`revisit`,`color`,`timer`,`nolimit`,`limit_d`,`limit_h`,`limit_d_now`,`limit_h_now`,`new_users`,`unic_ip`,`no_ref`,`sex_adv`,`to_ref`,`url`,`title`,`description`,`urlbanner_load`,`plan`,`totals`,`ip`,`money`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$type_serf','".time()."','".($up_list * time())."','$wmid_user','$username','$country','$content','$active','$revisit','$color','$timer','$nolimitdate','$limit_d','$limit_h','$limit_d','$limit_h','$new_users','$unic_ip','$no_ref','$sex_adv','$to_ref','$url','$title','$description','$urlbanner_load','$plan','$plan','$laip','$summa')") or die(mysql_error());
		}
		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);
	
		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="230"><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
			if($type_serf==3) {
				echo '<tr><td><b>VIP �������</b></td><td>��� ����� �������� 200 ���������� ������ � VIP ��������</td></tr>';
			}
			if($type_serf==4) {
				echo '<tr><td><b>VIP �������</b></td><td>��� ����� �������� 200 ���������� �������� � VIP ��������</td></tr>';
			}
			if($type_serf==2 | $type_serf==4) {
				echo '<tr><td><b>URL �����:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
				echo '<tr><td><b>URL �������:</b></td><td><a href="'.$description.'" target="_blank">'.$description.'</a></td></tr>';
			}else{
				echo '<tr><td><b>��������� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$title.'</a></td></tr>';
				echo '<tr><td><b>������� �������� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
				echo '<tr><td><b>URL �����:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			}

			if($nolimit>0) {
				echo '<tr><td><b>���������� �������:</b></td><td>�� ����������</td></tr>';
				echo '<tr><td><b>������, ���.:</b></td><td>'.$timer.'</td></tr>';
				echo '<tr><td>���������� � ������ ������</td><td>��</td></tr>';
				if($type_serf==1 | $type_serf==3) echo '<tr><td><b>��������� ������:</b></td><td>��</td></tr>';
				echo '<tr><td><b>�������� ����:</b></td><td>���</td></tr>';
				echo '<tr><td><b>�������� ��� ���������:</b></td><td>1 ���</td></tr>';
			}else{
				echo '<tr><td><b>���������� �������:</b></td><td>'.$plan.' ('.number_format(($plan * $dlink_cena_hits * (100+$dlink_nacenka)/100),2,".","").' ���.)</td></tr>';
				echo '<tr><td><b>������, ���.:</b></td><td>'.$timer_to.'</td></tr>';
				echo '<tr><td>���������� � ������ ������</td><td>'.$up_list_to[$up_list].'</td></tr>';
				if($type_serf==1 | $type_serf==3) echo '<tr><td><b>��������� ������:</b></td><td>'.$color_to[$color].'</td></tr>';
				echo '<tr><td><b>�������� ����:</b></td><td>'.$active_to[$active].'</td></tr>';
				echo '<tr><td><b>�������� ��� ���������:</b></td><td>'.$revisit_to.'</td></tr>';
			}
			echo '<tr><td><b>����������� ���������� ������� � �����:</b></td><td>'.$limit_d_to.'</td></tr>';
			echo '<tr><td><b>����������� ���������� ������� � ���:</b></td><td>'.$limit_h_to.'</td></tr>';
			echo '<tr><td><b>������� "18+":</b></td><td>'.$content_to[$content].'</td></tr>';

			echo '<tr><td>���������� ������ ��������</td><td>'.$new_users_to[$new_users].'</td></tr>';
			echo '<tr><td>���������� IP</td><td>'.$unic_ip_to[$unic_ip].'</td></tr>';
			echo '<tr><td>����� ������������� ��� ��������  �� '.$_SERVER["HTTP_HOST"].'</td><td>'.$no_ref_to[$no_ref].'</td></tr>';
			echo '<tr><td>�� �������� ��������</td><td>'.$sex_adv_to[$sex_adv].'</td></tr>';
			echo '<tr><td>���������� ������ ���������</td><td>'.$to_ref_to[$to_ref].'</td></tr>';

			echo '<tr><td><b>������������:</b></td><td>'.$country_to.'</td></tr>';
			if(isset($cab_text)) echo "$cab_text";
			echo '<tr><td><b>������ ������:</b></td><td><b>'.$system_pay[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';
			
			@require_once("".$_SERVER['DOCUMENT_ROOT']."/curs/curs.php");
				$money_add_usd = number_format(round(($summa/$CURS_USD),2),2,".","");
				
			if($method_pay==8) {
				if(($summa*0.005)<0.01) {$money_add_ym = $summa + 0.01;}else{$money_add_ym = number_format(($summa*1.005),2,".","");}

				echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,"."," ").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_ym,2,"."," ").'</b> <b>���.</b></td></tr>';
			}elseif($method_pay==3) {
				$money_add_w1 = number_format(($summa * 1.05), 2, ".", "");

				echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#76B15D;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#76B15D;">'.number_format($money_add_w1,2,".","`").'</b> <b>���.</b></td></tr>';
			}elseif($method_pay==7) {
						echo '<tr><td><b>��������� ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,".","`").'</b> <b>���.</b></td></tr>';
						echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($money_add_usd,2,".","`").'</b> <b>USD</b></td></tr>';

			}else{
				echo '<tr><td><b>����� � ������:</b></td><td><b style="color:#FF0000;">'.number_format($summa,2,"."," ").'</b> <b>���.</b></td></tr>';
			}
		echo '</table>';

		if($nolimit>0) $plan = "�������� �� $nolimit ���.";
		if($nolimit==4) $plan = "�������� �� 1 �����.";

		if($type_serf==1) {
			$inv_desc = "������ �������: ������������ �������, �����:$plan, ����:$merch_tran_id";
			$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
			$inv_desc_en = "Pay advertise: dinamic surfing, plan:$plan, order:$merch_tran_id";
		}elseif($type_serf==2) {
			$inv_desc = "������ �������: ��������� �������, �����:$plan, ����:$merch_tran_id";
			$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
			$inv_desc_en = "Pay advertise: banners surfing, plan:$plan, order:$merch_tran_id";
		}elseif($type_serf==3) {
			$inv_desc = "������ �������: ������������ �������(vip), �����:$plan, ����:$merch_tran_id";
			$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
			$inv_desc_en = "Pay advertise: dinamic surfing(vip), plan:$plan, order:$merch_tran_id";
		}elseif($type_serf==4) {
			$inv_desc = "������ �������: ��������� �������(vip), �����:$plan, ����:$merch_tran_id";
			$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
			$inv_desc_en = "Pay advertise: banners surfing(vip), plan:$plan, order:$merch_tran_id";
		}else{
			$inv_desc = "������ �������: ������������ �������, �����:$plan, ����:$merch_tran_id";
			$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
			$inv_desc_en = "Pay advertise: dinamic link, plan:$plan, order:$merch_tran_id";
		}

		if(($type_serf==3 | $type_serf==4) && $method_pay!=10 ) {
			echo '<span class="msg-error">������ VIP �������� �������� ������ � ���������� �����!</span>';
			include('footer.php');
			exit();
		}

		$shp_item = "2";
		$money_add = number_format($summa,2,".","");
		require_once("".DOC_ROOT."/method_pay/method_pay.php");
	}

	include('footer.php');
	exit();
}

?>

<script type="text/javascript" src="js/jquery.simpletip-1.3.1.pack.js"></script>
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
				  document.getElementById('url_banner').value="https://<?=$_SERVER["HTTP_HOST"];?>/uploads/"+file;
				} else{
					$('<li></li>').appendTo('#files').text('���� �� ��������!' + file).addClass('msg-error'); 
				}
			}
		});
		
	});
</script> 

<script type="text/javascript" language="JavaScript"> 

$(document).ready(function(){
	$("#hint1").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�������� ��� ��������</b> - �������� ����� ������� �� ������ �������� ������� ��� VIP.<br><b style="color:red;">��������!<br>���� �� ������������� � ������, ����� ���� ������ �������� ������ ����������, ��������� ������������ ��� ��������� ������� (�� VIP). ��� ��������� ����� ������ � ������, �������������� ��������������� �����������: ��������� ������, ���������� � ������ ������, ����� ������ � �������� ����.<br>���� �� ������������� �� ��������� ������ � ������ �������� ������ ��������� ������ ��� ���������, ��������� ��� �������� ������������-VIP ��� ���������-VIP.</b>'
	});
	$("#hint2").simpletip({
		fixed: true, position: ["-622", "-45"], focus: false,
		content: '<b>����������� �������</b> - �� ������ �������� ����������� ������� �� 1 �� 4 ������.'
	});
	$("#hint3").simpletip({
		fixed: true, position: ["-622", "-30"], focus: false,
		content: '<b>��������� ������</b> - ������� ��������� ������.'
	});
	$("#hint4").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�������� ��� ���������</b> - <b>�������� ���� ������ 24 ����</b> - ��� ������, ��� ���� ������������ ������ ����������� ���� ������ ���� ��� � �����.<br><b>�������� ���� ������ 48 �����</b> - ��� ������, ��� ���� ������������ ������ ����������� ���� ������ ���� ��� � 48 �����.'
	});
	$("#hint5").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�������� ������</b> - ��� ������ ����� <b style="color:red;">������� ������� ������</b> �� �������������� �����.'
	});
	$("#hint6").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� IP</b> - �� ������ ���������� ���������� ������ ����� ��� ������ ���������� IP ��� �� ����� �� 2 ����� (255.255.X.X)'
	});
	$("#hint7").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� ���� �����������</b> - �� ������ ���������� ����� ����� �������, ��������, �������� ���������� �� ������ ��������, ������� ������������������ �� ������� 7-�� ���� �����.'
	});
	$("#hint8").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� �������� ��������</b> - �� ������ ���������� ����� ����� ������� �� �������� �������� �������������, �������� ����� ����� ������� ������ ������������� �������� ���� �������� ����.'
	});
	$("#hint9").simpletip({
		fixed: true, position: ["-622", "-45"], focus: false,
		content: '<b>������������ �� �������</b> - �������� ������ ����� �������� ������������� �� ��������� �����, ��� �� �� ������ ��������� ���� ��� ��������� �����'
	});
	$("#hint10").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>������ ������</b> - �������� �������� ���������� ��� ��� ������ ������ ������.'
	});
	$("#hint11").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>��������� ������:</b> - ����� ��������� ������ ������.'
	});
	$("#hint12").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� ������ ���������</b> - ��������� 1-�� ������ ��� ��������� ���� �������.'
	});
	$("#hint13").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>������� 18+</b> - ��� �� ������ ������� ��� �� ����� ����� ������������ ��������� ��� ��������.'
	});
	$("#hint14").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>����������� ������� � �����</b> - �� ������ ������������ ����������� ������� � ����� ����� �������.'
	});
	$("#hint15").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� ������� ��������</b> - �� ������ ������������ ����� ����� ������� ������������� ��� ��������.'
	});
	$("#hint16").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>����������� ������� � ���</b> - �� ������ ������������ ����������� ������� � ��� ����� �������.'
	});
	$("#hint17").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>������� �������� ������</b> - ������� ������� �������� ������.'
	});
	$("#hint18").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� ����������</b> - ������� ���������� ���������� ����� �������.'
	});
	$("#hint19").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>����� ���������</b> - ������� ����� ��������� ����� �������.'
	});
	$("#hint20").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� � ������ ������</b> - �� ������ ���������� ���� ������� � ������ ������.'
	});
	$("#hint21").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�������� ����</b> - �� ������ ������������ ����� ����� ������� ������ � �������� ����.'
	});
	$("#hinturl").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>URL-����� �����</b> - ������ ���������� � http:// ��� https:// � ��������� �� ����� 300 ��������.<br>�� ����������� HTML-���� � Java-�������. �� ������� ������ �������, ��������� - �������� ��������.'
	});
	$("#hintur2").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>URL-����� �������</b> - ������ ���������� � http:// ��� https:// � ��������� �� ����� 300 ��������.<br>�� ����������� HTML-���� � Java-�������. �� ������� ������ �������, ��������� - �������� ��������.'
	});
})

function SbmFormB() {
	var title = $.trim($("#title").val());
	var description = $.trim($("#description").val());
	var url = $.trim($("#url").val());
	var url_banner = $.trim($("#url_banner").val());
	var type_serf = $.trim($("#type_serf").val());
	var plan = $.trim($("#plan").val());
	var nolimit = $.trim($("#nolimit").val());
	var timer = $.trim($("#timer").val());
	var method_pay = $.trim($("#method_pay").val());

	if(url == '' | url == 'http://' | url == 'https://') {
		$("#url").focus().attr("class", "err");
		alert("�� �� ������� URL-����� �����");
		return false;

	} else if( (type_serf == '1' | type_serf == '3' ) && title == '') {
		$("#title").focus().attr("class", "err");
		alert("�� �� ������� ��������� ������");
		return false;

	} else if( (type_serf == '1' | type_serf == '3' ) && description == '') {
		$("#description").focus().attr("class", "err");
		alert("�� �� ������� �������� ������");
		return false;

	} else if( (type_serf == '2' | type_serf == '4' ) && (url_banner == '' | url_banner == 'http://' | url_banner == 'https://') ) {
		$("#url_banner").focus().attr("class", "err");
		alert("�� �� ������� URL-����� �������");
		return false;

	} else if(nolimit == '0' && (plan == '' | plan < <?=$dlink_min_hits;?>) ) {
		$("#plan").focus().attr("class", "err12");
		alert("����������� ���������� ������� - <?=$dlink_min_hits;?>");
		return false;

	} else if(nolimit == '0' && (type_serf == '3' | type_serf == '4' ) && (plan == '' | plan < <?=$dlink_min_hits_vip;?>) ) {
		$("#plan").focus().attr("class", "err12");
		alert("����������� ���������� ������� - <?=$dlink_min_hits_vip;?>");
		return false;

	} else if(nolimit == '0' && (timer == '' | timer < <?=$dlink_timer_ot;?> | timer > <?=$dlink_timer_do;?>) ) {
		$("#timer").focus().attr("class", "err12");
		alert("����� ��������� ������ ���� � �������� �� <?=$dlink_timer_ot;?> ���. �� <?=$dlink_timer_do;?> ���.");
		return false;

	} else if( (type_serf == '3' | type_serf == '4' ) && method_pay != '10' ) {
		alert("����� VIP-�������� �������� ������ � ���������� �����");
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
	var nolimit = $.trim($("#nolimit").val());
	var plan = $.trim($("#plan").val());
	var timer = $.trim($("#timer").val());
	var color = $.trim($("#color").val());
	var type_serf = $.trim($("#type_serf").val());
	var up_list = $.trim($("#up_list").val());
	var active = $.trim($("#active").val());
	var revisit = $.trim($("#revisit").val());
	var unic_ip = $.trim($("#unic_ip").val());

	var cena_timer = <?php echo $dlink_cena_timer;?>;
	var cena_color = <?php echo $dlink_cena_color;?>;
	var cena_active = <?php echo $dlink_cena_active;?>;
	var cena_up_list = <?php echo $dlink_cena_uplist;?>;
	var cena_nac = <?php echo (100+$dlink_nacenka)/100;?>;

	if(type_serf==1 | type_serf==2) {
		$("#type_serf_z").css("display", "table-row");
		$("#type_serf_r").css("display", "");
		$("#type_serf_t").css("display", "");
		$("#type_serf_y").css("display", "");
		$("#type_serf_u").css("display", "");
	    $("#adv-title2").css("display", "");
	}else{
		$("#type_serf_z").css("display", "none");
		$("#type_serf_r").css("display", "none");
		$("#type_serf_t").css("display", "none");
		$("#type_serf_y").css("display", "none");
		$("#type_serf_u").css("display", "none");
		$("#adv-title2").css("display", "none");
	}
	
	if(type_serf==2 | type_serf==4) {
		var color = 0;
		var cena_hits = <?php echo $dlink_cena_hits_bs;?>;
		$("#type_serf_a").css("display", "none");
		$("#type_serf_b").css("display", "none");
		$("#type_serf_c").css("display", "table-row");
		$("#type_serf_d").css("display", "none");
		
	} else { 
		var cena_hits = <?php echo $dlink_cena_hits;?>;
		$("#type_serf_a").css("display", "table-row");
		$("#type_serf_b").css("display", "table-row");
		$("#type_serf_c").css("display", "none");
		$("#type_serf_d").css("display", "table-row");
	}

	if(type_serf==3 | type_serf==4) {
		$("#min_serf").html('&nbsp;&nbsp;[������� - <?php echo $dlink_min_hits_vip;?>]');
		$("#timer_serf").html('&nbsp;&nbsp;[<?php echo "�� 20 ���. �� $dlink_timer_do ���.";?>]');
	} else {
		$("#min_serf").html('&nbsp;&nbsp;[������� - <?php echo $dlink_min_hits;?>]');
		$("#timer_serf").html('&nbsp;&nbsp;[<?php echo "�� $dlink_timer_ot ���. �� $dlink_timer_do ���.";?>]');
	}

	if(timer<<?=$dlink_timer_ot;?>) { timer = <?=$dlink_timer_ot;?>;}
	if(timer><?=$dlink_timer_do;?>) { timer = <?=$dlink_timer_do;?>;}

	if(revisit==0) {var cena_revisit = 0;}
	if(revisit==1) {var cena_revisit = <?php echo $dlink_cena_revisit_1;?>;}
	if(revisit==2) {var cena_revisit = <?php echo $dlink_cena_revisit_2;?>;}

	if(unic_ip==0) {var cena_unic_ip = 0;}
	if(unic_ip==1) {var cena_unic_ip = <?php echo $dlink_cena_unic_ip_1;?>;}
	if(unic_ip==2) {var cena_unic_ip = <?php echo $dlink_cena_unic_ip_2;?>;}

	if(nolimit==1) {
		var price = <?=@$dlink_cena_nolimit_1;?>;
	}else if(nolimit==2) {
		var price = <?=@$dlink_cena_nolimit_2;?>;
	}else if(nolimit==3) {
		var price = <?=@$dlink_cena_nolimit_3;?>;
	}else if(nolimit==4) {
		var price = <?=@$dlink_cena_nolimit_4;?>;
	}else{
		nolimit = 0;
	}

	if(nolimit>0) {
		$("#bl1").hide(); $("#bl11").html('<b>�� ����������</b>');
		$("#bl2").hide(); $("#bl21").html('<b>20</b> ���.');
		$("#bl3").hide(); $("#bl31").html('<b>��</b>');
		$("#bl4").hide(); $("#bl41").html('');
		$("#bl5").hide(); $("#bl51").html('<b>���</b>');
		$("#bl6").hide(); $("#bl61").html('<b>1 ���</b>');
		$("#bl7").hide(); $("#bl71").html('<b>��</b>');
		$("#bl8").hide(); $("#bl81").html('<b>��</b>');
	}else{
		$("#bl1").show(); $("#bl11").html('');
		$("#bl2").show(); $("#bl21").html('');
		$("#bl3").show(); $("#bl31").html('');
		$("#bl4").show(); $("#bl41").html('');
		$("#bl5").show(); $("#bl51").html('');
		$("#bl6").show(); $("#bl61").html('');
		$("#bl7").show(); $("#bl71").html('');
		$("#bl8").show(); $("#bl81").html('');

		var price = plan * cena_nac * (cena_hits + (cena_timer * (timer-<?=$dlink_timer_ot;?>)) + (active * cena_active)) + plan * ((color * cena_color) + cena_revisit + cena_unic_ip) + (up_list * cena_up_list);
	}

	$("#pricet").html('<b>��������� ������:</b>');
	$("#price").html('<b style="color:#228B22;">' + number_format(price, 2, '.', ' ') + ' ���.</b>');
	$("#price1").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price2").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price3").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price4").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price5").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price6").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price7").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price8").html('<b style="color:#f1f5f1;">' + number_format((price/60), 2, '.', ' ') + ' </b>');
	//$("#price9").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price10").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
	$("#price11").html('<b style="color:#f1f5f1;">' + number_format(price, 2, '.', ' ') + ' </b>');
}
</script><?php

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">������� � �������� - ��� ���?</span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo '���������, ����������� � ��������� ������� �� <b style="color:#3A5FCD">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; ���������� ����������� ����������� ������� ��������� �� ��� ��������-������. ';
		echo '������ ������������� ������������ ������ � ������ ���� ������������ � ����� ���������� ��� ��������. ';
		echo '������� ����������� ���������� ��������� �������������� ����� � ������� 24 �����. ';
		echo '����� ����, �� ������ ����������� ����� ������������ ����� ������ ��� �����������, ��� ������� ��� ���� ����� �������� ���������.';
	echo '</div>';

	echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">����������� �������</span>';
		echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                         echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#fbf3ef" align="center"> ';
                         echo '<b>����������� �������.</b><br> ';
                         echo '�� ������ �������� ����������� ������� �� 1 �� 4 ������.<br> ';
                         echo '��������� ������������ �������� <span style="color:green;">�� '.number_format($dlink_cena_nolimit_1,2,".","`").' �� '.number_format($dlink_cena_nolimit_4,2,".","`").' ���...</span>.<br> ';
                         echo '������ ������� ��<span style="color:#6c8bff;"> '.number_format($dlink_cena_nolimit,0,".","`").'</span> ����������.</div> ';
		echo '</div>';
echo '</div>';

echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';
	echo '<thead><th colspan="3" class="top">����� ������ �������</th></thead>';

	echo '<tbody>';
	echo '<tr>';
		echo '<td width="200" align="left"><b>�������� ��� �������</b></td>';
		echo '<td>';
			echo '<select id="type_serf" name="type_serf" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="1">������������ �������</option>';
				echo '<option value="2">��������� �������</option>';
				echo '<option value="3">������������ ������� - VIP (��� ����� �������� 200 ���������� ������ � VIP ��������)</option>';
				echo '<option value="4">��������� ������� - VIP (��� ����� �������� 200 ���������� �������� � VIP ��������)</option>';
			echo '</select>';
		echo '</td>';
                echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint1" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>����������� �������</b></td>';
		echo '<td>';
			echo '<select id="nolimit" name="nolimit" onChange="PlanChange();" onClick="PlanChange();">';
				echo '<option value="0">���</option>';
				echo '<option value="1">1&nbsp;������&nbsp;('.number_format($dlink_cena_nolimit_1,2,".","`").' ���.)</option>';
				echo '<option value="2">2&nbsp;������&nbsp;('.number_format($dlink_cena_nolimit_2,2,".","`").' ���.)</option>';
				echo '<option value="3">3&nbsp;������&nbsp;('.number_format($dlink_cena_nolimit_3,2,".","`").' ���.)</option>';
				echo '<option value="4">1&nbsp;&nbsp;�����&nbsp;&nbsp;('.number_format($dlink_cena_nolimit_4,2,".","`").' ���.)</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint2" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL �����</b></td>';
		echo '<td><input type="text" id="url" name="url" maxlength="300" value="http://" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hinturl" class="hint-quest"></span></td>';
	echo '</tr>';
        
	echo '<tr  id="type_serf_a">';
		echo '<td align="left"><b>��������� ������</b></td>';
		echo '<td><input type="text" id="title" name="title" maxlength="60" value="" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
                
		echo '<td align="center" width="16" tyle="background: #EDEDED;"><span id="hint3" class="hint-quest"></span></td>';
	echo '</tr>';
        
	echo '<tr id="type_serf_b">';
		echo '<td align="left"><b>������� �������� ������</b></td>';
		echo '<td><input type="text" id="description" name="description" maxlength="80" value="" class="ok" onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint17" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr id="type_serf_c" style="display: none;">';
		echo '<td align="left"><b>URL �������</b> (�������� �� PC:)</td>';
		echo '<td align="left"><div style="position:relative;overflow:hidden;"><input type="text" id="url_banner" name="url_banner" maxlength="300" placeholder="http://website.ru/banner.gif" value="" class="ok" ><div class="open" style="position:absolute;top:0;right:0;width:86px;text-align:center;" id="open">�����</div><br/> <span align="center" id="status"></span></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hintur2" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>���������� ����������</b></td>';
		echo '<td><div id="bl1">';
			echo '<input type="text" id="plan" name="plan" maxlength="11" value="1000" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok12\');">';
			echo '<span id="min_serf"></span>';
		echo '</div><div id="bl11" style="text-align:left;"></div></td>';
		echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint18" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>����� ���������</b></td>';
		echo '<td><div id="bl2">';
			echo '<input type="text" id="timer" name="timer" maxlength="3" value="20" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok12\');">';
			echo '<span id="timer_serf"></span>';
			echo '</div><div id="bl21" style="text-align:left;"></div></td>';
			echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint19" class="hint-quest"></span></td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<span id="adv-title1" class="adv-title-close" onclick="ShowHideBlock(1);">�������������� ���������</span>';
	echo '<div id="adv-block1" style="display:none;">';
		echo '<table class="tables">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td align="left">���������� � ������ ������</td>';
				echo '<td style="height:31px;"><div id="bl7">';
					echo '<select name="up_list" id="up_list" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">���</option>';
						echo '<option value="1">�� (+'.number_format($dlink_cena_uplist, 4,".","").' ���.)</option>';
					echo '</select>';
				echo '</div><div id="bl71" style="text-align:left;"></div></td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint20" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr id="type_serf_d">';
				echo '<td align="left" width="200">�������� ������</td>';
				echo '<td style="height:31px;"><div id="bl3"><select name="color" id="color" onChange="PlanChange();" onClick="PlanChange();"><option value="0">���</option><option value="1">�� (+'.number_format($dlink_cena_color, 4,".","").' ���./�����)</option></select></div><div id="bl31" style="text-align:left;"></div></td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint5" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left" width="200">�������� ����</td>';
				echo '<td style="height:31px;"><div id="bl5"><select name="active" id="active" onChange="PlanChange();" onClick="PlanChange();"><option value="0">���</option><option value="1">�� (+'.number_format(($dlink_cena_active * (100+$dlink_nacenka)/100), 4,".","").' ���./�����)</option></select></div><div id="bl51" style="text-align:left;"></div></td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint21" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left" width="200"><b>�������� ��� ���������</b></td>';
				echo '<td style="height:31px;"><div id="bl6">';
					echo '<select name="revisit" id="revisit" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">������ 24 ����</option>';
						echo '<option value="1">������ 48 ����� (+ '.number_format($dlink_cena_revisit_1, 4,".","").' ���./�����)</option>';
						echo '<option value="2">1 ��� (+ '.number_format($dlink_cena_revisit_2, 4,".","").' ���./�����)</option>';
					echo '</select>';
					echo '</div><div id="bl61" style="text-align:left;"></div></td>';
					echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint4" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr id="type_serf_r">';
				echo '<td align="left">���������� IP</td>';
				echo '<td style="height:31px;"><div id="bl8">';
					echo '<select name="unic_ip" id="unic_ip" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">���</option>';
						echo '<option value="1">�� (100% ����������) &mdash; (+ '.number_format($dlink_cena_unic_ip_1, 4,".","").' ���./�����)</option>';
						echo '<option value="2">��������� �� ����� �� 2 ����� (255.255.X.X) &mdash; (+ '.number_format($dlink_cena_unic_ip_2, 4,".","").' ���./�����)</option>';
					echo '</select>';
					echo '</div><div id="bl81" style="text-align:left;"></div></td>';
					echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint6" class="hint-quest"></span></td>';
				
			echo '</tr>';
			echo '<tr id="type_serf_t">';
			
				echo '<td align="left">���������� ������ ��������</td>';
				echo '<td>';
					echo '<select name="new_users" id="new_users" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">�� (�� 7 ���� � ������� �����������)</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint7" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr id="type_serf_y">';
				echo '<td align="left">�� ������� ��������</td>';
				echo '<td>';
					echo '<select name="no_ref" id="no_ref" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">������������� ��� �������� �� '.$_SERVER["HTTP_HOST"].'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint15" class="hint-quest"></span></td>';
			echo '</tr>';
			
			echo '<tr id="type_serf_u">';
				echo '<td align="left">�� �������� ��������</td>';
				echo '<td>';
					echo '<select name="sex_adv" id="sex_adv" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">������ �������</option>';
						echo '<option value="2">������ �������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint8" class="hint-quest"></span></td>';
			echo '</tr>';
			//if($type_serf==1 | $type_serf==2) {
			echo '<tr id="type_serf_z">';
				echo '<td align="left">���������� ������ ���������</td>';
				echo '<td>';
					echo '<select name="to_ref" id="to_ref" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">��������� 1-�� ������</option>';
						echo '<option value="2">��������� ���� �������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint12" class="hint-quest"></span></td>';
			echo '</tr>';
		//	}
			echo '<tr>';
				echo '<td align="left">������� 18+</td>';
				echo '<td><input type="checkbox" name="content" value="1"> - �� ���� ����� ������������ ��������� ��� ��������</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint13" class="hint-quest"></span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';

		echo '<table class="tables" style="margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td align="left" width="200">����������� ������� � �����</td>';
				echo '<td width="125"><input type="text" id="limit_d" name="limit_d" maxlength="11" value="0" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok12\');"></td>';
				echo '<td align="left" rowspan="2">(0 - ��� �����������)</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint14" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left">����������� ������� � ���</td>';
				echo '<td width="125"><input type="text" id="limit_h" name="limit_h" maxlength="11" value="0" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok12\');"></td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint16" class="hint-quest"></span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	echo '</div>';
//if($type_serf==3 | $type_serf==4) {
	echo '<span id="adv-title2" class="adv-title-close" onclick="ShowHideBlock(2);">���-���������</span>';
	echo '<div id="adv-block2" class="tables" style="display:none; margin:0 auto; padding:0;">';
		echo '<table class="tables" style="margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td colspan="2" align="center"><a onclick="setChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
				echo '<td colspan="2" align="center"><a onclick="setChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
				echo '<td align="center" width="16" rowspan="10" style="background: #EDEDED;"><span id="hint9" class="hint-quest"></span></td>';
			echo '</tr>';
			include($_SERVER["DOCUMENT_ROOT"]."/advertise/func_geotarg.php");
		echo '</tbody>';
		echo '</table>';
	echo '</div>';
//}
		echo '<table class="tables">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td id="pricet" width="200"></td>';
				echo '<td id="price"></td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint11" class="hint-quest"></span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	//echo '</div>';
		
		echo '<div style="text-align:center;">';
	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">������� ������ ������</span>';
	echo '<div id="adv-block3" style="display:block;">';
		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		echo '<button id="method_pay"  name="method_pay" value="10" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rs1 cash-start">';
      echo '<div><div><div><span class="line-green"><span id="price1"></span> ���.</span></div></div></div>';
	  echo '</div> </button>';
		}else{
			
	}
    
    if($site_pay_wm!=1) {
        echo '<div class="cash-wm1">';
    	  echo '<div class="cash-wm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="1" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-wm1 cash-start">';
    echo '<div><div><div><span class="line-green"><span id="price2"></span> (+0.8%) ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_ym!=1) {
        echo '<div class="cash-yd1">';
    	  echo '<div class="cash-yd1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="8" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-yd1 cash-start">';
    echo '<div><div><div><span class="line-green"><span id="price3"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_robo!=1) {
        echo '<div class="cash-rb1">';
    	  echo '<div class="cash-rb1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="2" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rb1 cash-start">';
    echo '<div><div><div><span class="line-green"><span id="price4"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}

	if($site_pay_mega!=1) {
    	  echo '<div class="cash-ik1">';
    	  echo '<div class="cash-ik1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button id="method_pay" name="method_pay" value="9" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ik1 cash-start">';
    echo '<div><div><div><span class="line-green"><span id="price5"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
    if($site_pay_qw!=1) {
    	  echo '<div class="cash-qw1">';
    	  echo '<div class="cash-qw1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="6" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-qw1 cash-start">';
    echo '<div><div><div><span class="line-green"><span id="price6"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	if($site_pay_payeer!=1) {
    	  echo '<div class="cash-pr1">';
    	  echo '<div class="cash-pr1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button id="method_pay" name="method_pay" value="5" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pr1 cash-start">';
    echo '<div><div><div><span class="line-green"><span id="price7"></span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
  
  if($site_pay_pm!=1) {
    	  echo '<div class="cash-pm1">';
    	  echo '<div class="cash-pm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
     echo '<button id="method_pay" name="method_pay" value="7" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pm1 cash-start" >';
     echo '<div><div><div><span class="line-green"><span id="price8"></span> USD</span></div></div></div>';
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
    echo '<div><div><div><span class="line-green"><span id="price11">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
echo '</div>';
echo '</form>';

?>

<script language="JavaScript">PlanChange();</script>