<?php
require('funciones.php');
require('config.php');

$httpreferer = isset($_SERVER["HTTP_REFERER"]) ? limpiar(trim($_SERVER["HTTP_REFERER"])) : false;

function GET_DOMEN($url) {
	$host = str_replace("www.www.","www.", trim($url));
	$host = @parse_url($host);
	$host = trim($host['host'] ? $host['host'] : array_shift(explode('/', $host['path'], 2)));

	if(in_array("www", explode(".", $host))) {
		$just_domain = explode("www.", $host);
		return $just_domain[1];
	}else{
		return $host;
	}
}

if(!isset($_SESSION["http_ref"])) {
	$http_ref = (isset($httpreferer)) ? @GET_DOMEN(trim($httpreferer)) : false;
	if($http_ref!=false) $_SESSION["http_ref"] = $http_ref;
}

$_REF_ID = ( isset($_GET["r"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["r"])) ) ? intval(limpiar(trim($_GET["r"]))) : false;
//if( $_REF_ID>0 && !isset($_SESSION["r"]) ) $_SESSION["r"] = $_REF_ID;
if($_REF_ID != false) $_SESSION["r"] = $_REF_ID;

$_REF_ID_SES = ( isset($_SESSION["r"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["r"])) ) ? intval(limpiar(trim($_SESSION["r"]))) : false;
$_HTTP_REFERER = (isset($_SESSION["http_ref"]) && ($_SESSION["http_ref"]!="" && $_SESSION["http_ref"]!=$_SERVER["HTTP_HOST"]) ) ? limpiar($_SESSION["http_ref"]) : false;
$laip = getRealIP();

### START  ŒÕ ”–— œŒ—≈“»“≈À≈… ####
$konk_hit_ed_status = mysql_result(mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='status'"),0,0);

if( $_REF_ID>0 && $konk_hit_ed_status==1 ) {
	$konk_hit_date_start = mysql_result(mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='date_start'"),0,0);
	$konk_hit_date_end = mysql_result(mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='date_end'"),0,0);

	if( $konk_hit_date_end>=time() && $konk_hit_date_start<=time() ) {
		$konk_hit_ed_country = mysql_result(mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='hit' AND `item`='country'"),0,0);

		require_once("geoip/geoipcity.inc");
		$gi = @geoip_open("geoip/GeoLiteCity.dat", GEOIP_STANDARD);
		$record = @geoip_record_by_addr($gi, $laip);
		@geoip_close($gi);
		$country_code = ( isset($record->country_code) && $record->country_code != false ) ? $record->country_code : false;

		if($_HTTP_REFERER!=false && $country_code!=false && (trim($konk_hit_ed_country)=="" | strpos(strtoupper($konk_hit_ed_country), strtoupper($country_code)) !== false) ) {
			$sql_check_us_id = mysql_query("SELECT `id` FROM `tb_users` WHERE `id`='$_REF_ID' AND `ban_date`='0'");
			if(mysql_num_rows($sql_check_us_id)>0) {

				$_TEST_URL = $_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
				$_TEST_URL = "$_TEST_URL\n$httpreferer";

				mysql_query("DELETE FROM `tb_hit_log` WHERE `time`<='".(time()-24*60*60)."'");

				$sql_check_ip = mysql_query("SELECT `id` FROM `tb_hit_log` WHERE `ip`='$laip'");
				if(mysql_num_rows($sql_check_ip)>0) {
					$sql_hit = mysql_query("SELECT `id`,`time` FROM `tb_hit_log` WHERE `ref_id`='$_REF_ID' AND `ip`='$laip'");
					if(mysql_num_rows($sql_hit)>0) {
						$_ROW_HIT = mysql_fetch_assoc($sql_hit);
						$_ID_HIT = $_ROW_HIT["id"];
						$_TIME_HIT = $_ROW_HIT["time"];

						if(($_TIME_HIT+24*60*60) < time()) {
							mysql_query("UPDATE `tb_hit_log` SET `time`='".time()."', `date`='".DATE("d.m.Y H:i")."', `http_ref`='$country_code | $_HTTP_REFERER', `url`='$_TEST_URL' WHERE `id`='$_ID_HIT'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `konkurs_hit`=`konkurs_hit`+'1' WHERE `id`='$_REF_ID' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die(mysql_error());
						}
					}
				}else{
					mysql_query("INSERT INTO `tb_hit_log` (`ref_id`,`time`,`date`,`ip`,`http_ref`,`url`) 
					VALUES('$_REF_ID','".time()."','".DATE("d.m.Y H:i")."','$laip','$country_code | $_HTTP_REFERER', '$_TEST_URL')") or die(mysql_error());

					mysql_query("UPDATE `tb_users` SET `konkurs_hit`=`konkurs_hit`+'1' WHERE `id`='$_REF_ID' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)") or die(mysql_error());
				}
			}
		}
	}
}
### END  ŒÕ ”–— œŒ—≈“»“≈À≈… ####

$sql_site = mysql_query("SELECT * FROM tb_site WHERE id='1'");
$row_site = mysql_fetch_array($sql_site);
$site_wmid = $row_site["sitewmid"];
$site_wmr = $row_site["sitewmr"];
$site_email = $row_site["siteemail"];
$site_isq = $row_site["siteisq"];
$site_telefon = $row_site["sitetelefon"];
$site_fio = $row_site["site_fio"];
$startdate = intval((time() - strtotime($row_site["startdate"])));
$start_date = $row_site["startdate"];
$start_time = intval((time() - strtotime($start_date)));

$sql_upstat = mysql_query("SELECT * FROM tb_statistics WHERE id='1'");
$row_stat = mysql_fetch_array($sql_upstat);
$users_all = $row_stat["users_all"];
//$users_active = $row_stat["users_active"];
$users_24h = $row_stat["users_24h"];
$users_new_v = $row_stat["users_v"];
$users_new_s = $row_stat["users_s"];
//$users_count_advs = $row_stat["count_advs"];
//$count_pay = $row_stat["count_pay"];
//$sum_pay = $row_stat["sum_pay"];
$sumvisits = $row_stat["hitov"];
$payall = $row_stat["viplat"];
	$sumpay = $row_stat["sumpay"];
	
	$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_wm' AND `howmany`='1'");
$site_pay_wm = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_robo' AND `howmany`='1'");
$site_pay_robo = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_payeer' AND `howmany`='1'");
$site_pay_payeer = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_qw' AND `howmany`='1'");
$site_pay_qw = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_ym' AND `howmany`='1'");
$site_pay_ym = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_pm' AND `howmany`='1'");
$site_pay_pm = mysql_result($sql,0,0);
	
$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_wo' AND `howmany`='1'");
$site_pay_wo = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_mega' AND `howmany`='1'");
$site_pay_mega = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_free' AND `howmany`='1'");
$site_pay_free = mysql_result($sql,0,0);

$sql=mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_pay_advcash' AND `howmany`='1'");
$site_pay_advcash = mysql_result($sql,0,0);


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
$dlink_cena_hits = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
$dlink_cena_hits_bs = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hits_aserf' AND `howmany`='1'");
$cena_hits_aserf = mysql_result($sql,0,0);


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_task' AND howmany='1'");
$ref_proc_task_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_task' AND howmany='2'");
$ref_proc_task_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_task' AND howmany='3'");
$ref_proc_task_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_active' AND `howmany`='1'");
$reit_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_status' AND `howmany`='1'");
$testdrive_status = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_count' AND `howmany`='1'");
$testdrive_count = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='site_action_status' AND `howmany`='1'");
$site_action_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus' AND `howmany`='1'");
$bonus = mysql_result($sql,0,0);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
	$password = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? ($_SESSION["userPas"]) : false;
}else{
	$username = false;
	$password = false;
}

$laip = getRealIP();
$ip = $laip;

$page_user = (isset($_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"]!=false) ? $_SERVER["REQUEST_URI"] : $_SERVER["PHP_SELF"];
$page_user = "https://".$_SERVER["HTTP_HOST"].$page_user;
$_SESSION["page_user"] = $page_user;

$sql_moder = mysql_query("SELECT `username` FROM `tb_users` WHERE `user_status`='2' ORDER BY `lastlogdate2` DESC");
$count_moder = mysql_num_rows($sql_moder);
if($count_moder == 1) {
	$row_moder = mysql_fetch_assoc($sql_moder);
	$link_moder = "/newmsg.php?name=".$row_moder["username"];
}elseif($count_moder > 1) {
	$link_moder = "/contact.php";
}else{
	$link_moder = "/contact.php";
}

$sql_where = mysql_query("SELECT `pagetitle` FROM `online` WHERE `username`='Admin'");
  if(mysql_num_rows($sql_where)>0) {
     $where_user = '<b style="color:black;">[</b><b style="color:#0dbd0d;">OnLine</b><b style="color:black;">]</b>';
  }else{
   $where_user = '<b style="color:black;">[OffLine]</b>';
  }


if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_user = mysql_query("SELECT * FROM tb_users WHERE username='$username'");
	$row_user = mysql_fetch_array($sql_user);

	$user_status = $row_user["user_status"];
	$partnerid = $row_user["id"];
	$user_id = $partnerid;
	$wesk = $row_user["username"];
	$wezk = md5($row_user["password"]);
	$my_ref_back= $row_user["ref_back"];
	$my_country = $row_user["country_cod"];
	$my_rep_task = $row_user["rep_task"];

	$wmiduser = $row_user["wmid"];
	$wmruser = $row_user["wm_purse"];
	$my_wm_purse = $wmruser;
	$my_ym_purse = $row_user["ym_purse"];
	$my_qw_purse = $row_user["qw_purse"];
	$my_pm_purse = $row_user["pm_purse"];
	$my_py_purse = $row_user["py_purse"];
	$my_mb_purse = $row_user["mb_purse"];
	$my_sb_purse = $row_user["sb_purse"];
	$my_ac_purse = $row_user["ac_purse"];
	$my_me_purse = $row_user["me_purse"];
	$my_vs_purse = $row_user["vs_purse"];
	$my_ms_purse = $row_user["ms_purse"];
	$my_be_purse = $row_user["be_purse"];
	$my_mt_purse = $row_user["mt_purse"];
	$my_mg_purse = $row_user["mg_purse"];
	$my_tl_purse = $row_user["tl_purse"];

	$my_purse["wm"] = $my_wm_purse;
	$my_purse["ym"] = $my_ym_purse;
	$my_purse["pm"] = $my_pm_purse;
	$my_purse["pe"] = $my_py_purse;
	$my_purse["qw"] = $my_qw_purse;
	$my_purse["mb"] = $my_mb_purse;
	$my_purse["sb"] = $my_sb_purse;
	$my_purse["ac"] = $my_ac_purse;
	$my_purse["me"] = $my_me_purse;
	$my_purse["vs"] = $my_vs_purse;
	$my_purse["ms"] = $my_ms_purse;
	$my_purse["be"] = $my_be_purse;
	$my_purse["mt"] = $my_mt_purse;
	$my_purse["mg"] = $my_mg_purse;
	$my_purse["tl"] = $my_tl_purse;

	$my_money_ob = $row_user["money"];
	$my_money_rb = $row_user["money_rb"];
	
	$my_bonus_surf = $row_user["bonus_surf"];

	$reiting = $row_user["reiting"];

	$referer = $row_user["referer"];
	$my_referer = $row_user["referer"];
	$my_referer_1 = $my_referer;
	$my_referer_2 = $row_user["referer2"];
	$my_referer_3 = $row_user["referer3"];
	//$my_referer_4 = $row_user["referer4"];
	//$my_referer_5 = $row_user["referer5"];

	$my_lastiplog = $row_user["lastiplog"];
	$my_joindate = $row_user["joindate2"];
	$my_sex = $row_user["sex"];
	$my_reiting = $reiting;
	$my_fl18 = $row_user["fl18"];

	$my_vip_serf = $row_user["vip_serf"];
	$my_ban_serf = $row_user["ban_serf"];
	$my_youtube_serf = $row_user["youtube_serf"];

	$visits = $row_user["visits"];
	$referals = $row_user["referals"];
	$referalvisits = $row_user["referalvisits"];

	$money = $row_user["money"];
	$money_user = $money;
	$money_users = $money;
       	$money_rb = $row_user["money_rb"];
       	$my_money_rb = $money_rb;
       	$my_money_rek = $row_user["money_rek"];
       	$my_ban_date = $row_user["ban_date"];
		$my_money_in = $row_user["money_in"];

	$avatar = $row_user["avatar"];
	$my_attestat = $row_user["attestat"];
	$referals1 = $row_user["referals"];
	$referals2 = $row_user["referals2"];
	$referals3 = $row_user["referals3"];
	$avatar = $row_user["avatar"];
	$autoref = $row_user["autoref"];
	$imay = $row_user["imay"];

	$email_ok = $row_user["email_ok"];
	$pass_oper = $row_user["pass_oper"];
	$lastiplog = $row_user["lastiplog"];
	$block_agent = $row_user["block_agent"];
	$agent = $row_user["agent"];
	$read_news = $row_user["read_news"];
	$test_drive = $row_user["test_drive"];
	$test_drive_youtube = $row_user["test_drive_youtube"];
	$reit_act_usr = $row_user["reit_act"];
	$bonus_act_usr = $row_user["bonus_act"];
        $db_time = $row_user["db_time"];

	$vac1 = $row_user["vac1"];
	$vac2 = $row_user["vac2"];

	### œ¿–“Õ≈– ¿ NEW ###
	$sql_p = mysql_query("SELECT * FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_array($sql_p);

		$p_sl = $row_p["p_sl"];
		$p_txt = $row_p["p_txt"];
		$p_b468x60 = $row_p["p_b468x60"];
		$p_b200x300 = $row_p["p_b200x300"];
		$p_b100x100 = $row_p["p_b100x100"];
		$p_frm = $row_p["p_frm"];
		$p_psd = $row_p["p_psd"];
		$p_packet[1] = $row_p["p_packet_1"];
		$p_packet[2] = $row_p["p_packet_2"];
		$p_packet[3] = $row_p["p_packet_3"];
		$p_packet[4] = $row_p["p_packet_4"];
		$p_packet[5] = $row_p["p_packet_5"];
		$discount_partner = $row_p["discount_partner"];
	}else{
		$p_sl = 0;
		$p_txt = 0;
		$p_b468x60 = 0;
		$p_b200x300 = 0;
		$p_b100x100 = 0;
		$p_frm = 0;
		$p_psd = 0;
		$p_packet[1] = 0;
		$p_packet[2] = 0;
		$p_packet[3] = 0;
		$p_packet[4] = 0;
		$p_packet[5] = 0;
		$discount_partner = 0;
	}
	### œ¿–“Õ≈– ¿ NEW ###

	$my_reit = floor($row_user["reiting"]);
	if($my_reit<0) $my_reit = '<span style="color:#FF0000; text-shadow: 1px 1px #000;">'.$my_reit.'</span>';
	$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting' AND `r_do`>='".floor($reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_array($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_array($sql_rang);
	}
	$my_id_rang = $row_rang["id"];
	$my_rang = $row_rang["rang"];
	$my_r_c_1 = $row_rang["c_1"];
	$my_r_c_2 = $row_rang["c_2"];
	$my_r_c_3 = $row_rang["c_3"];
	//$my_r_c_4 = $row_rang["c_4"];
	//$my_r_c_5 = $row_rang["c_5"];
	$my_r_m_1 = $row_rang["m_1"];
	$my_r_m_2 = $row_rang["m_2"];
	$my_r_m_3 = $row_rang["m_3"];
	//$my_r_m_4 = $row_rang["m_4"];
	//$my_r_m_5 = $row_rang["m_5"];
	$my_r_t_1 = $row_rang["t_1"];
	$my_r_t_2 = $row_rang["t_2"];
	$my_r_t_3 = $row_rang["t_3"];
	//$my_r_t_4 = $row_rang["t_4"];
	//$my_r_t_5 = $row_rang["t_5"];
	$my_r_test_1 = $row_rang["test_1"];
	$my_r_test_2 = $row_rang["test_2"];
	$my_r_test_3 = $row_rang["test_3"];
	//$my_r_test_4 = $row_rang["test_4"];
	//$my_r_test_5 = $row_rang["test_5"];
	$my_r_youtube_1 = $row_rang["youtube_1"];
	$my_r_youtube_2 = $row_rang["youtube_2"];
	$my_r_youtube_3 = $row_rang["youtube_3"];
	//$my_r_youtube_4 = $row_rang["youtube_4"];
	//$my_r_youtube_5 = $row_rang["youtube_5"];
	$my_r_pv_1 = $row_rang["pv_1"];
	$my_r_pv_2 = $row_rang["pv_2"];
	$my_r_pv_3 = $row_rang["pv_3"];
	//$my_r_pv_4 = $row_rang["pv_4"];
	//$my_r_pv_5 = $row_rang["pv_5"];
	
	$wall_my_com = ($row_user["wall_com_p"] - $row_user["wall_com_o"]);
	if($wall_my_com > 0) {
		$wall_my_com = '<b class="text-green">'.$wall_my_com.'</b>';
	}elseif($wall_my_com < 0) {
		$wall_my_com = '<b class="text-red">-'.abs($wall_my_com).'</b>';
	}else{
		$wall_my_com = '<b class="text-grey">0</b>';
	}

	if($autoref=="0") {$active='<font color="#00cc00">Õ≈“<font>';}
	if($autoref=="1") {$active='<font color="red">ƒ‡</font>';}


	$sql = mysql_query("SELECT `id` FROM `tb_auction` WHERE `timer_end`>='".time()."'");
	$all_auc = mysql_num_rows($sql);

	if($all_auc > 0) {
		$auction='¿ÍÚË‚Ì˚ı ‡ÛÍˆËÓÌÓ‚ <font color="red" size="3"><b>'.$all_auc.'</b></font><br />';
	}else{
		$auction='¿ÍÚË‚Ì˚ı ‡ÛÍˆËÓÌÓ‚ <b>Õ≈“</b><br />';
	}

	mysql_query("UPDATE `tb_users` SET `lastlogdate2`='".time()."' WHERE `username`='$username'") or die(mysql_error());

	$sql_mail = mysql_query("SELECT `id` FROM `tb_mail_in` WHERE `namein`='$username' AND `status`='0'");
	$inbox = mysql_num_rows($sql_mail);

	$sql_mail = mysql_query("SELECT `id` FROM `tb_mail_out` WHERE `nameout`='$username' AND `status`='0'");
	$outbox = mysql_num_rows($sql_mail);

	$sql_tw = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='wait' AND `type`='task' AND `rek_name`='$username' AND ident IN (SELECT `id` FROM `tb_ads_task` WHERE `username`='$username')");	
	$my_task_w = mysql_num_rows($sql_tw);

	$sql_td = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='dorab' AND `type`='task' AND `user_name`='$username' AND ident IN (SELECT `id` FROM `tb_ads_task` WHERE `status`='pay' AND `totals`>'0')");	
	$my_task_dorab = mysql_num_rows($sql_td);

	$sql_kon_ref = mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` ASC");
	$count_ref_kon = mysql_num_rows($sql_kon_ref);

	$sql_my_kon_ref = mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$username' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` ASC");
	$count_my_ref_kon = mysql_num_rows($sql_my_kon_ref);


	$_admin_online = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_online` WHERE `username`='Admin'"));
}

?>	